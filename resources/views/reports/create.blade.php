@extends('layouts.app')

@section('title', 'Buat Laporan')

@section('content')
    <div class="card">
        <h1>Buat Laporan</h1>

        <form action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            @include('reports._fields')

            <label for="evidence">Bukti pendukung (opsional, JPG/PNG/PDF, maks 2 MB)</label>
            <input type="file" id="evidence" name="evidence">
            @error('evidence')
                <p class="error">{{ $message }}</p>
            @enderror

            <button type="submit" class="btn">Kirim Laporan</button>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        const urlArea = "{{ url('/areas') }}";
        const pilihArea = document.getElementById('area_id');
        const infoArea = document.getElementById('info-area');
        const statusAktif = ['dikirim', 'ditinjau', 'perlu_diteruskan', 'diteruskan'];

        async function muatInfoArea() {
            if (!pilihArea.value) {
                infoArea.textContent = '';
                return;
            }

            infoArea.textContent = 'Memuat informasi area...';

            try {
                const res = await fetch(urlArea + '/' + pilihArea.value + '/ringkasan', {
                    headers: { 'Accept': 'application/json' },
                });

                if (!res.ok) {
                    throw new Error('status ' + res.status);
                }

                const data = await res.json();
                const rincian = statusAktif
                    .map(function (s) { return s + ' ' + data.per_status[s]; })
                    .join(', ');

                infoArea.textContent = data.area + ', ' + data.kabupaten + ': '
                    + data.aktif + ' laporan aktif (' + rincian + ')';
            } catch (e) {
                infoArea.textContent = 'Gagal memuat informasi area';
            }
        }

        pilihArea.addEventListener('change', muatInfoArea);

        muatInfoArea();
    </script>
@endpush