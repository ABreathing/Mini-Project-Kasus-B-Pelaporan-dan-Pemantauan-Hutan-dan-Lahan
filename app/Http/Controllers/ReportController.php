<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Report;
use App\Models\ReportForwarding;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['q', 'area_id', 'status', 'tingkat']);

        $reports = Report::with(['area', 'pelapor'])
            ->visibleTo($request->user())
            ->filter($filters)
            ->latest('waktu_kejadian')
            ->orderByDesc('id')
            ->paginate(5)
            ->withQueryString(); // supaya filter tetap aktif waktu pindah halaman

        $areas = Area::orderBy('nama')->get();

        return view('reports.index', compact('reports', 'areas', 'filters'));
    }

    public function create()
    {
        $areas = Area::orderBy('nama')->get();

        return view('reports.create', compact('areas'));
    }

    public function store(Request $request)
    {
        $data = $request->validate(
            array_merge($this->fieldRules(), [
                'evidence' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            ]),
            array_merge($this->fieldMessages(), [
                'evidence.file' => 'Bukti harus berupa file',
                'evidence.mimes' => 'Bukti harus berformat JPG, JPEG, PNG, atau PDF',
                'evidence.max' => 'Ukuran bukti maksimal 2 MB',
                'evidence.uploaded' => 'File gagal diunggah, pastikan ukurannya maksimal 2 MB',
            ])
        );

        $jumlahDikirim = Report::where('user_id', $request->user()->id)
            ->where('status', 'dikirim')
            ->count();

        if ($jumlahDikirim >= Report::MAX_DIKIRIM) {
            return back()->withInput()->with(
                'error',
                'Kamu masih punya ' . Report::MAX_DIKIRIM . ' laporan berstatus dikirim. Tunggu sampai ada yang ditinjau.'
            );
        }

        $data['user_id'] = $request->user()->id;
        $data['status'] = 'dikirim';

        if ($request->hasFile('evidence')) {
            $data['evidence_path'] = $request->file('evidence')->store('evidence', 'local');
        }
        unset($data['evidence']);

        Report::create($data);

        return redirect()->route('reports.index')
            ->with('success', 'Laporan berhasil dikirim');
    }

    public function show(Request $request, Report $report)
    {
        Gate::authorize('view', $report);

        $user = $request->user();
        $report->load(['area', 'pelapor', 'reviewer', 'forwarding.koordinator', 'statusLogs.pengubah']);
        $nextStatuses = $report->nextStatusesFor($user);

        $related = collect();
        if ($user->isRelawan() || $user->isKoordinator()) {
            $related = Report::where('area_id', $report->area_id)
                ->where('id', '!=', $report->id)
                ->latest('waktu_kejadian')
                ->get();
        }

        return view('reports.show', compact('report', 'nextStatuses', 'related'));
    }

    public function edit(Report $report)
    {
        Gate::authorize('update', $report);

        if ($report->status !== 'dikirim') {
            return redirect()->route('reports.show', $report)
                ->with('error', 'Laporan yang sudah diproses tidak bisa diubah');
        }

        $areas = Area::orderBy('nama')->get();

        return view('reports.edit', compact('report', 'areas'));
    }

    public function update(Request $request, Report $report)
    {
        Gate::authorize('update', $report);

        if ($report->status !== 'dikirim') {
            return redirect()->route('reports.show', $report)
                ->with('error', 'Laporan yang sudah diproses tidak bisa diubah');
        }

        $data = $request->validate($this->fieldRules(), $this->fieldMessages());

        $report->update($data);

        return redirect()->route('reports.show', $report)
            ->with('success', 'Laporan berhasil diperbarui');
    }

    public function destroy(Report $report)
    {
        Gate::authorize('delete', $report);

        if ($report->status !== 'dikirim') {
            return redirect()->route('reports.show', $report)
                ->with('error', 'Laporan yang sudah diproses tidak bisa dihapus');
        }

        if ($report->evidence_path) {
            Storage::disk('local')->delete($report->evidence_path);
        }

        $report->delete();

        return redirect()->route('reports.index')
            ->with('success', 'Laporan berhasil dihapus');
    }

    public function updateStatus(Request $request, Report $report)
    {
        Gate::authorize('review', $report);

        $user = $request->user();

        if ($user->isRelawan() && $report->user_id === $user->id) {
            return back()->with('error', 'Kamu tidak boleh meninjau laporan milikmu sendiri');
        }

        $data = $request->validate([
            'status' => 'required|in:ditinjau,perlu_diteruskan,diteruskan,selesai',
            'catatan' => ($user->isRelawan() ? 'required' : 'nullable') . '|string|max:500',
        ], [
            'status.required' => 'Status wajib dipilih',
            'status.in' => 'Status tidak valid',
            'catatan.required' => 'Catatan wajib diisi',
            'catatan.max' => 'Catatan maksimal 500 karakter',
        ]);

        if (! in_array($data['status'], $report->nextStatusesFor($user))) {
            return back()->withErrors([
                'status' => 'Perubahan status dari ' . $report->status . ' ke ' . $data['status'] . ' tidak diperbolehkan',
            ]);
        }

        $perubahan = ['status' => $data['status']];

        if ($user->isRelawan()) {
            $perubahan['catatan_relawan'] = $data['catatan'];
            $perubahan['reviewed_by'] = $user->id;
        }

        $report->update($perubahan);

        return redirect()->route('reports.show', $report)
            ->with('success', 'Status laporan diperbarui');
    }

    public function forward(Request $request, Report $report)
    {
        Gate::authorize('forward', $report);

        if ($report->status !== 'perlu_diteruskan') {
            return back()->with('error', 'Penerusan hanya bisa dicatat untuk laporan berstatus perlu_diteruskan');
        }

        $data = $request->validate([
            'pihak_terkait' => 'required|string|max:100',
            'catatan' => 'nullable|string|max:500',
        ], [
            'pihak_terkait.required' => 'Pihak terkait wajib diisi',
            'pihak_terkait.max' => 'Pihak terkait maksimal 100 karakter',
            'catatan.max' => 'Catatan maksimal 500 karakter',
        ]);

        DB::transaction(function () use ($report, $data, $request) {
            ReportForwarding::create([
                'report_id' => $report->id,
                'forwarded_by' => $request->user()->id,
                'pihak_terkait' => $data['pihak_terkait'],
                'catatan' => $data['catatan'] ?? null,
            ]);

            $report->update(['status' => 'diteruskan']);
        });

        return redirect()->route('reports.show', $report)
            ->with('success', 'Penerusan ke pihak terkait berhasil dicatat');
    }

    public function evidence(Report $report)
    {
        Gate::authorize('view', $report);

        abort_if(
            ! $report->evidence_path || ! Storage::disk('local')->exists($report->evidence_path),
            404
        );

        return Storage::disk('local')->response($report->evidence_path);
    }

    // aturan validasi field laporan, dipakai di store dan update
    private function fieldRules(): array
    {
        return [
            'area_id' => 'required|exists:areas,id',
            'judul' => 'required|string|max:150',
            'kondisi' => 'required|string|min:10|max:1000',
            'tingkat' => 'required|in:ringan,sedang,berat',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'waktu_kejadian' => 'required|date|before_or_equal:now',
        ];
    }

    private function fieldMessages(): array
    {
        return [
            'area_id.required' => 'Area wajib dipilih',
            'area_id.exists' => 'Area tidak valid',
            'judul.required' => 'Judul wajib diisi',
            'kondisi.required' => 'Kondisi wajib diisi',
            'kondisi.min' => 'Kondisi minimal 10 karakter',
            'tingkat.required' => 'Tingkat wajib dipilih',
            'tingkat.in' => 'Tingkat harus ringan, sedang, atau berat',
            'latitude.required' => 'Latitude wajib diisi',
            'latitude.numeric' => 'Latitude harus berupa angka',
            'latitude.between' => 'Latitude harus antara -90 sampai 90',
            'longitude.required' => 'Longitude wajib diisi',
            'longitude.numeric' => 'Longitude harus berupa angka',
            'longitude.between' => 'Longitude harus antara -180 sampai 180',
            'waktu_kejadian.required' => 'Waktu kejadian wajib diisi',
            'waktu_kejadian.date' => 'Format waktu kejadian tidak valid',
            'waktu_kejadian.before_or_equal' => 'Waktu kejadian tidak boleh di masa depan',
        ];
    }
}