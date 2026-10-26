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
        <a href="{{ route('home') }}" class="brand">Karhutla</a>

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
        @yield('content')
    </main>
</body>
</html>