<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class RekapController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('view-rekap');

        $request->validate([
            'area_id' => 'nullable|exists:areas,id',
        ]);

        $areaId = $request->query('area_id');

        $query = Report::query()->when($areaId, fn ($q, $id) => $q->where('area_id', $id));

        $total = (clone $query)->count();
        $selesai = (clone $query)->where('status', 'selesai')->count();
        $perluDiteruskan = (clone $query)->where('status', 'perlu_diteruskan')->count();
        $aktif = $total - $selesai;

        $counts = (clone $query)
            ->selectRaw('area_id, status, count(*) as jumlah')
            ->groupBy('area_id', 'status')
            ->get();

        $areas = Area::orderBy('nama')->get();

        $baris = [];
        foreach ($areas as $area) {
            if ($areaId && $area->id != $areaId) {
                continue;
            }

            $perStatus = [];
            $jumlahArea = 0;

            foreach (Report::STATUSES as $status) {
                $jumlah = $counts->where('area_id', $area->id)->where('status', $status)->sum('jumlah');
                $perStatus[$status] = $jumlah;
                $jumlahArea += $jumlah;
            }

            $baris[] = [
                'area' => $area,
                'per_status' => $perStatus,
                'total' => $jumlahArea,
            ];
        }

        return view('rekap.index', compact(
            'areas', 'areaId', 'baris', 'total', 'aktif', 'selesai', 'perluDiteruskan'
        ));
    }
}