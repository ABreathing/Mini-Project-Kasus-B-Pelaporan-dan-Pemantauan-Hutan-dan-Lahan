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
            @if ($report->forwarding)
                <tr>
                    <th>Diteruskan ke</th>
                    <td>
                        {{ $report->forwarding->pihak_terkait }}
                        @if ($report->forwarding->catatan)
                            <br>{{ $report->forwarding->catatan }}
                        @endif
                        <br>
                        <small>
                            dicatat oleh {{ $report->forwarding->koordinator?->name ?? '-' }}
                            pada {{ $report->forwarding->created_at->format('d M Y H:i') }}
                        </small>
                    </td>
                </tr>
            @endif
        </table>

        <div class="aksi">
            <a href="{{ route('reports.index') }}" class="btn btn-light">Kembali</a>

            @if (auth()->id() === $report->user_id)
                <a href="{{ route('reports.edit', $report) }}" class="btn">Edit</a>

                <form action="{{ route('reports.destroy', $report) }}" method="POST" class="inline"
                      onsubmit="return confirm('Hapus laporan ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            @endif
        </div>
    </div>

    @if (count($nextStatuses) > 0)
        <div class="card section">
            <h2>{{ auth()->user()->isRelawan() ? 'Tinjau Laporan' : 'Selesaikan Laporan' }}</h2>
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

                <label for="catatan">Catatan {{ auth()->user()->isRelawan() ? '' : '(opsional)' }}</label>
                <textarea id="catatan" name="catatan" rows="3" @if (auth()->user()->isRelawan()) required @endif>{{ old('catatan') }}</textarea>
                @error('catatan')
                    <p class="error">{{ $message }}</p>
                @enderror

                <button type="submit" class="btn">Simpan</button>
            </form>
        </div>
    @endif

    @if (auth()->user()->isKoordinator() && $report->status === 'perlu_diteruskan')
        <div class="card section">
            <h2>Catat Penerusan ke Pihak Terkait</h2>
            <p class="note">
                Isi setelah informasi ini benar-benar kamu sampaikan ke pihak terkait
                (misalnya BPBD, Manggala Agni, atau Damkar). Status akan berubah menjadi "diteruskan".
            </p>

            <form action="{{ route('reports.forward', $report) }}" method="POST">
                @csrf

                <label for="pihak_terkait">Pihak terkait</label>
                <input type="text" id="pihak_terkait" name="pihak_terkait" value="{{ old('pihak_terkait') }}" required>
                @error('pihak_terkait')
                    <p class="error">{{ $message }}</p>
                @enderror

                <label for="catatan_penerusan">Catatan (opsional)</label>
                <textarea id="catatan_penerusan" name="catatan" rows="3">{{ old('catatan') }}</textarea>
                @error('catatan')
                    <p class="error">{{ $message }}</p>
                @enderror

                <button type="submit" class="btn">Catat Penerusan</button>
            </form>
        </div>
    @endif

    <div class="card section">
        <h2>Riwayat Status</h2>

        @if ($report->statusLogs->isEmpty())
            <p>Belum ada perubahan status.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Dari</th>
                        <th>Menjadi</th>
                        <th>Oleh</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($report->statusLogs as $log)
                        <tr>
                            <td>{{ $log->created_at->format('d M Y H:i') }}</td>
                            <td>{{ $log->status_lama ?? '-' }}</td>
                            <td><span class="badge">{{ $log->status_baru }}</span></td>
                            <td>{{ $log->pengubah?->name ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

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