@extends('layouts.app')

@section('title', 'Edit Laporan')

@section('content')
    <div class="card">
        <h1>Edit Laporan</h1>

        <form action="{{ route('reports.update', $report) }}" method="POST">
            @csrf
            @method('PUT')

            @include('reports._fields')

            <p class="note">File bukti tidak bisa diganti di sini.</p>

            <button type="submit" class="btn">Simpan Perubahan</button>
            <a href="{{ route('reports.show', $report) }}" class="btn btn-light">Batal</a>
        </form>
    </div>
@endsection