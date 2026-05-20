<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'EvaluaPractica')</title>

    {{-- CSS del prototipo --}}
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body class="page" data-page="@yield('page', '')">

    {{-- Inyectar icons.svg para que los <use href="#icon-..."> funcionen --}}
    <div style="display:none">
        {!! file_get_contents(public_path('assets/icons.svg')) !!}
    </div>

    <div class="layout">
        <aside class="sidebar" aria-label="Menú">
            <div class="brand-side">
                <img src="{{ asset('assets/logo.svg') }}" alt="Logo EvaluaPractica">
                <div>
                    <div class="title">EvaluaPractica</div>
                    <div class="sub">Gestión</div>
                </div>
            </div>

            <nav class="menu">
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <svg aria-hidden="true"><use href="#icon-dashboard"></use></svg>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('bitacoras.index') }}" class="{{ request()->routeIs('bitacoras.*') ? 'active' : '' }}">
                    <svg aria-hidden="true"><use href="#icon-book"></use></svg>
                    <span>Bitácoras</span>
                </a>

                <a href="#" onclick="return false;" style="opacity:.55; cursor:not-allowed;">
                    <svg aria-hidden="true"><use href="#icon-clipboard"></use></svg>
                    <span>Evaluación</span>
                </a>
            </nav>
        </aside>

        <main class="main" role="main">
            <div class="top-row">
                <div>
                    <h1 class="h-title">@yield('title')</h1>
                    <p class="h-sub">@yield('subtitle')</p>
                </div>

                <div style="display:flex; gap:12px; align-items:center;">
                    @yield('top_action')

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn--ghost" type="submit">Cerrar sesión</button>
                    </form>
                </div>
            </div>

            @if(session('success'))
                <div class="card" style="padding:14px 16px; margin-bottom:14px;">
                    <strong style="color:#15803D;">{{ session('success') }}</strong>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>