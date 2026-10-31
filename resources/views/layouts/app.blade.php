<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Karhutla') - Pelaporan Karhutla</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <header class="navbar">
        <div class="nav-left">
            <a href="{{ route('home') }}" class="brand">Karhutla</a>

            @auth
                <a href="{{ route('reports.index') }}" class="nav-link">
                    {{ auth()->user()->isPelapor() ? 'Laporan Saya' : 'Semua Laporan' }}
                </a>
                <a href="{{ route('reports.create') }}" class="nav-link">Buat Laporan</a>
                                @if (auth()->user()->isRelawan() || auth()->user()->isKoordinator())
                    <a href="{{ route('rekap') }}" class="nav-link">Rekap</a>
                @endif
            @endauth
        </div>

        @auth
            <div class="nav-right">
                <span>{{ auth()->user()->name }} ({{ auth()->user()->role }})</span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-small">Logout</button>
                </form>
            </div>
        @endauth
    </header>

    <main class="container">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>