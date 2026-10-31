@extends('layouts.app')

@section('title', 'Rekap Laporan')

@section('content')
    <div class="card">
        <h1>Rekap Laporan</h1>

        <form action="{{ route('rekap') }}" method="GET" class="filter">
            <select name="area_id">
                <option value="">Semua area</option>
                @foreach ($areas as $area)
                    <option value="{{ $area->id }}" @selected($areaId == $area->id)>{{ $area->nama }}</option>
                @endforeach
            </select>

            <button type="submit" class="btn btn-filter">Terapkan</button>
            <a href="{{ route('rekap') }}" class="btn btn-light btn-filter">Reset</a>
        </form>
        @error('area_id')
            <p class="error">{{ $message }}</p>
        @enderror

        <div class="stats">
            <div class="stat">
                <div class="stat-angka">{{ $total }}</div>
                <div class="stat-label">Total laporan</div>
            </div>
            <div class="stat">
                <div class="stat-angka">{{ $aktif }}</div>
                <div class="stat-label">Masih aktif</div>
            </div>
            <div class="stat">
                <div class="stat-angka">{{ $perluDiteruskan }}</div>
                <div class="stat-label">Perlu diteruskan</div>
            </div>
            <div class="stat">
                <div class="stat-angka">{{ $selesai }}</div>
                <div class="stat-label">Selesai</div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Area</th>
                    @foreach (\App\Models\Report::STATUSES as $status)
                        <th>{{ $status }}</th>
                    @endforeach
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($baris as $b)
                    <tr>
                        <td>{{ $b['area']->nama }}</td>
                        @foreach (\App\Models\Report::STATUSES as $status)
                            <td>{{ $b['per_status'][$status] }}</td>
                        @endforeach
                        <td><strong>{{ $b['total'] }}</strong></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p class="note">Laporan "aktif" artinya statusnya belum selesai.</p>
    </div>
@endsection