@extends('layouts.app')

@section('title', 'Detail Laporan')

@section('content')
    <div class="card">
        <h1>{{ $report->judul }}</h1>

        <table>
            <tr>
                <th>Status</th>
                <td><span class="badge">{{ $report->status }}</span></td>
            </tr>
            <tr>
                <th>Pelapor</th>
                <td>{{ $report->pelapor->name }}</td>
            </tr>
            <tr>
                <th>Area</th>
                <td>{{ $report->area->nama }}, {{ $report->area->kabupaten }}</td>
            </tr>
            <tr>
                <th>Koordinat</th>
                <td>{{ $report->latitude }}, {{ $report->longitude }}</td>
            </tr>
            <tr>
                <th>Waktu kejadian</th>
                <td>{{ $report->waktu_kejadian->format('d M Y H:i') }}</td>
            </tr>
            <tr>
                <th>Tingkat</th>
                <td>{{ $report->tingkat }}</td>
            </tr>
            <tr>
                <th>Kondisi</th>
                <td>{{ $report->kondisi }}</td>
            </tr>
            <tr>
                <th>Bukti</th>
                <td>
                    @if ($report->evidence_path)
                        <a href="{{ route('reports.evidence', $report) }}" target="_blank">Lihat bukti</a>
                    @else
                        -
                    @endif
                </td>
            </tr>
            <tr>
                <th>Catatan relawan</th>
                <td>
                    {{ $report->catatan_relawan ?? '-' }}
                    @if ($report->reviewer)
                        <br><small>oleh {{ $report->reviewer->name }}</small>
                    @endif
                </td>
            </tr>
        </table>

        <a href="{{ route('reports.index') }}" class="btn">Kembali</a>
    </div>
@endsection