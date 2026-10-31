<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Report;

class AreaController extends Controller
{
    public function ringkasan(Area $area)
    {
        $perStatus = Report::where('area_id', $area->id)
            ->selectRaw('status, count(*) as jumlah')
            ->groupBy('status')
            ->pluck('jumlah', 'status');

        $hasil = [];
        foreach (Report::STATUSES as $status) {
            $hasil[$status] = (int) ($perStatus[$status] ?? 0);
        }

        return response()->json([
            'area' => $area->nama,
            'kabupaten' => $area->kabupaten,
            'aktif' => array_sum($hasil) - $hasil['selesai'],
            'per_status' => $hasil,
        ]);
    }
}