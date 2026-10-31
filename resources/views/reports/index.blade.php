@extends('layouts.app')

@section('title', auth()->user()->isPelapor() ? 'Laporan Saya' : 'Semua Laporan')

@section('content')
    <div class="card">
        <h1>{{ auth()->user()->isPelapor() ? 'Laporan Saya' : 'Semua Laporan' }}</h1>

        <form action="{{ route('reports.index') }}" method="GET" class="filter">
            <input type="text" name="q" placeholder="Cari judul atau kondisi" value="{{ $filters['q'] ?? '' }}">

            <select name="area_id">
                <option value="">Semua area</option>
                @foreach ($areas as $area)
                    <option value="{{ $area->id }}" @selected(($filters['area_id'] ?? '') == $area->id)>
                        {{ $area->nama }}
                    </option>
                @endforeach
            </select>

            <select name="status">
                <option value="">Semua status</option>
                @foreach (\App\Models\Report::STATUSES as $status)
                    <option value="{{ $status }}" @selected(($filters['status'] ?? '') == $status)>{{ $status }}</option>
                @endforeach
            </select>

            <select name="tingkat">
                <option value="">Semua tingkat</option>
                @foreach (['ringan', 'sedang', 'berat'] as $tingkat)
                    <option value="{{ $tingkat }}" @selected(($filters['tingkat'] ?? '') == $tingkat)>{{ $tingkat }}</option>
                @endforeach
            </select>

            <button type="submit" class="btn btn-filter">Cari</button>
            <a href="{{ route('reports.index') }}" class="btn btn-light btn-filter">Reset</a>
        </form>

        <p class="note">Ditemukan {{ $reports->total() }} laporan</p>

        @if ($reports->isEmpty())
            <p>Tidak ada laporan yang cocok.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Waktu kejadian</th>
                        <th>Judul</th>
                        @unless (auth()->user()->isPelapor())
                            <th>Pelapor</th>
                        @endunless
                        <th>Area</th>
                        <th>Tingkat</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reports as $report)
                        <tr>
                            <td>{{ $report->waktu_kejadian->format('d M Y H:i') }}</td>
                            <td>{{ $report->judul }}</td>
                            @unless (auth()->user()->isPelapor())
                                <td>{{ $report->pelapor->name }}</td>
                            @endunless
                            <td>{{ $report->area->nama }}</td>
                            <td>{{ $report->tingkat }}</td>
                            <td><span class="badge">{{ $report->status }}</span></td>
                            <td><a href="{{ route('reports.show', $report) }}">Detail</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if ($reports->hasPages())
                <div class="pagination">
                    @if ($reports->onFirstPage())
                        <span>&laquo; Sebelumnya</span>
                    @else
                        <a href="{{ $reports->previousPageUrl() }}">&laquo; Sebelumnya</a>
                    @endif

                    <span>Halaman {{ $reports->currentPage() }} dari {{ $reports->lastPage() }}</span>

                    @if ($reports->hasMorePages())
                        <a href="{{ $reports->nextPageUrl() }}">Berikutnya &raquo;</a>
                    @else
                        <span>Berikutnya &raquo;</span>
                    @endif
                </div>
            @endif
        @endif

        <a href="{{ route('reports.create') }}" class="btn">Buat Laporan</a>
    </div>
@endsection