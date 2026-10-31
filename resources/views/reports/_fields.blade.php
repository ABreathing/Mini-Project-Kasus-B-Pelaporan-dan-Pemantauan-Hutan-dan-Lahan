{{-- field laporan yang dipakai di form buat dan form edit --}}
@php($r = $report ?? null)

<label for="judul">Judul</label>
<input type="text" id="judul" name="judul" value="{{ old('judul', $r?->judul) }}" required>
@error('judul')
    <p class="error">{{ $message }}</p>
@enderror

<label for="area_id">Area</label>
<select id="area_id" name="area_id" required>
    <option value="">-- pilih area --</option>
    @foreach ($areas as $area)
        <option value="{{ $area->id }}" @selected(old('area_id', $r?->area_id) == $area->id)>
            {{ $area->nama }}, {{ $area->kabupaten }}
        </option>
    @endforeach
</select>
@error('area_id')
    <p class="error">{{ $message }}</p>
@enderror

<label for="tingkat">Tingkat kondisi</label>
<select id="tingkat" name="tingkat" required>
    <option value="">-- pilih tingkat --</option>
    <option value="ringan" @selected(old('tingkat', $r?->tingkat) == 'ringan')>Ringan</option>
    <option value="sedang" @selected(old('tingkat', $r?->tingkat) == 'sedang')>Sedang</option>
    <option value="berat" @selected(old('tingkat', $r?->tingkat) == 'berat')>Berat</option>
</select>
@error('tingkat')
    <p class="error">{{ $message }}</p>
@enderror

<label for="kondisi">Kondisi yang dilihat</label>
<textarea id="kondisi" name="kondisi" rows="4" required>{{ old('kondisi', $r?->kondisi) }}</textarea>
@error('kondisi')
    <p class="error">{{ $message }}</p>
@enderror

<label for="latitude">Latitude</label>
<input type="number" step="any" id="latitude" name="latitude" value="{{ old('latitude', $r?->latitude) }}" required>
@error('latitude')
    <p class="error">{{ $message }}</p>
@enderror

<label for="longitude">Longitude</label>
<input type="number" step="any" id="longitude" name="longitude" value="{{ old('longitude', $r?->longitude) }}" required>
@error('longitude')
    <p class="error">{{ $message }}</p>
@enderror

<label for="waktu_kejadian">Waktu kejadian</label>
<input type="datetime-local" id="waktu_kejadian" name="waktu_kejadian" value="{{ old('waktu_kejadian', $r?->waktu_kejadian?->format('Y-m-d\TH:i')) }}" required>
@error('waktu_kejadian')
    <p class="error">{{ $message }}</p>
@enderror