<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $reports = Report::with(['area', 'pelapor'])
            ->visibleTo($request->user())
            ->latest('waktu_kejadian')
            ->get();

        return view('reports.index', compact('reports'));
    }

    public function create()
    {
        $areas = Area::orderBy('nama')->get();

        return view('reports.create', compact('areas'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'area_id' => 'required|exists:areas,id',
            'judul' => 'required|string|max:150',
            'kondisi' => 'required|string|min:10|max:1000',
            'tingkat' => 'required|in:ringan,sedang,berat',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'waktu_kejadian' => 'required|date|before_or_equal:now',
            'evidence' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ], [
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
            'evidence.file' => 'Bukti harus berupa file',
            'evidence.mimes' => 'Bukti harus berformat JPG, JPEG, PNG, atau PDF',
            'evidence.max' => 'Ukuran bukti maksimal 2 MB',
            'evidence.uploaded' => 'File gagal diunggah, pastikan ukurannya maksimal 2 MB',
        ]);

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

    public function show(Report $report)
    {
        Gate::authorize('view', $report);

        $report->load(['area', 'pelapor', 'reviewer']);

        return view('reports.show', compact('report'));
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
}