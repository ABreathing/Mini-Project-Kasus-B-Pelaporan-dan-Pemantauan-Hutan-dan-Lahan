@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
    <div class="card">
        <h1>Halo, {{ auth()->user()->name }}</h1>
        <p>Kamu login sebagai <strong>{{ auth()->user()->role }}</strong>.</p>
        <p>Fitur laporan akan ditambahkan di langkah berikutnya.</p>
    </div>
@endsection