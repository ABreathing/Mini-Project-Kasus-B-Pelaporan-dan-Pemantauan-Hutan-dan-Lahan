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

    {{-- form peninjauan, hanya muncul kalau user boleh mengubah status laporan ini --}}
    @if (count($nextStatuses) > 0)
        <div class="card section">
            <h2>Tinjau Laporan</h2>
            <p class="note">
                Peninjauan hanya menilai kelengkapan dan kewajaran informasi,
                bukan menetapkan bahwa kebakaran benar-benar terjadi.
                Status "selesai" berarti proses di aplikasi ini selesai.
            </p>

            <form action="{{ route('reports.status', $report) }}" method="POST">
                @csrf
                @method('PATCH')

                <label for="status">Ubah status menjadi</label>
                <select id="status" name="status" required>
                    @foreach ($nextStatuses as $status)
                        <option value="{{ $status }}">{{ $status }}</option>
                    @endforeach
                </select>
                @error('status')
                    <p class="error">{{ $message }}</p>
                @enderror

                <label for="catatan">Catatan</label>
                <textarea id="catatan" name="catatan" rows="3" required>{{ old('catatan') }}</textarea>
                @error('catatan')
                    <p class="error">{{ $message }}</p>
                @enderror

                <button type="submit" class="btn">Simpan</button>
            </form>
        </div>
    @endif

    @if (auth()->user()->isRelawan() || auth()->user()->isKoordinator())
        <div class="card section">
            <h2>Laporan lain di area {{ $report->area->nama }}</h2>

            @if ($related->isEmpty())
                <p>Tidak ada laporan lain di area ini.</p>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>Waktu kejadian</th>
                            <th>Judul</th>
                            <th>Tingkat</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($related as $item)
                            <tr>
                                <td>{{ $item->waktu_kejadian->format('d M Y H:i') }}</td>
                                <td>{{ $item->judul }}</td>
                                <td>{{ $item->tingkat }}</td>
                                <td><span class="badge">{{ $item->status }}</span></td>
                                <td><a href="{{ route('reports.show', $item) }}">Detail</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    @endif
@endsection