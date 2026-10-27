<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $reports = Report::with('area')
            ->where('user_id', $request->user()->id)
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
        ]);

        $data['user_id'] = $request->user()->id;
        $data['status'] = 'dikirim';

        Report::create($data);

        return redirect()->route('reports.index')
            ->with('success', 'Laporan berhasil dikirim');
    }
}