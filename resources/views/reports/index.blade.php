@extends('layouts.app')

@section('title', auth()->user()->isPelapor() ? 'Laporan Saya' : 'Semua Laporan')

@section('content')
    <div class="card">
        <h1>{{ auth()->user()->isPelapor() ? 'Laporan Saya' : 'Semua Laporan' }}</h1>

        @if ($reports->isEmpty())
            <p>Belum ada laporan.</p>
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
        @endif

        <a href="{{ route('reports.create') }}" class="btn">Buat Laporan</a>
    </div>
@endsection