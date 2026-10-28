@extends('layouts.app')

@section('title', 'Laporan Saya')

@section('content')
    <div class="card">
        <h1>Laporan Saya</h1>

        @if ($reports->isEmpty())
            <p>Belum ada laporan.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Waktu kejadian</th>
                        <th>Judul</th>
                        <th>Area</th>
                        <th>Tingkat</th>
                        <th>Status</th>
                        <th>Bukti</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reports as $report)
                        <tr>
                            <td>{{ $report->waktu_kejadian->format('d M Y H:i') }}</td>
                            <td>{{ $report->judul }}</td>
                            <td>{{ $report->area->nama }}</td>
                            <td>{{ $report->tingkat }}</td>
                            <td><span class="badge">{{ $report->status }}</span></td>
                            <td>
                                @if ($report->evidence_path)
                                    <a href="{{ route('reports.evidence', $report) }}" target="_blank">Lihat</a>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <a href="{{ route('reports.create') }}" class="btn">Buat Laporan</a>
    </div>
@endsection