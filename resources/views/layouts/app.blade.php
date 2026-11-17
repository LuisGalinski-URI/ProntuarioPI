<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Prontuário') | Prontuário</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="app-shell d-flex min-vh-100">
        <aside class="offcanvas-lg offcanvas-start app-sidebar" tabindex="-1" id="appSidebar" aria-labelledby="appSidebarLabel">
            <div class="app-sidebar-content">
                <div class="app-sidebar-heading">
                    <a class="app-brand" href="{{ route('dashboard') }}" id="appSidebarLabel">
                        <span class="app-brand-mark" aria-hidden="true">P</span>
                        <span class="app-brand-label">Prontuário</span>
                    </a>
                    <button class="btn btn-sm btn-light app-sidebar-close d-lg-none"
                            type="button"
                            data-bs-dismiss="offcanvas"
                            data-bs-target="#appSidebar"
                            aria-label="Fechar menu">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <nav class="nav flex-column app-nav" aria-label="Navegação principal">
                    <a class="nav-link {{ request()->routeIs('dashboard', 'home') ? 'active' : '' }}"
                       href="{{ route('dashboard') }}"
                       aria-label="Dashboard"
                       @if (request()->routeIs('dashboard', 'home')) aria-current="page" @endif>
                        <svg class="app-nav-icon" viewBox="0 0 16 16" aria-hidden="true">
                            <path d="M8 1.5 1.5 7h1.3v7h4v-4h2.4v4h4v-7h1.3L8 1.5Z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.4"/>
                        </svg>
                        <span class="app-nav-label">Dashboard</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('pacientes.*') ? 'active' : '' }}"
                       href="{{ route('pacientes.index') }}"
                       aria-label="Pacientes"
                       @if (request()->routeIs('pacientes.*')) aria-current="page" @endif>
                        <svg class="app-nav-icon" viewBox="0 0 16 16" aria-hidden="true">
                            <circle cx="6" cy="5" r="2.4" fill="none" stroke="currentColor" stroke-width="1.3"/>
                            <path d="M1.8 13.8c.3-2.4 1.8-3.7 4.2-3.7s3.9 1.3 4.2 3.7M11 4.3a2.1 2.1 0 0 1 0 4.1m1.1 1.8c1.3.5 2 1.7 2.1 3.6" fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="1.3"/>
                        </svg>
                        <span class="app-nav-label">Pacientes</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('consultas.*') ? 'active' : '' }}"
                       href="{{ route('consultas.index') }}"
                       aria-label="Consultas"
                       @if (request()->routeIs('consultas.*')) aria-current="page" @endif>
                        <svg class="app-nav-icon" viewBox="0 0 16 16" aria-hidden="true">
                            <rect x="1.8" y="3" width="12.4" height="11" rx="1.2" fill="none" stroke="currentColor" stroke-width="1.3"/>
                            <path d="M5 1.8v2.5m6-2.5v2.5M1.8 6.3h12.4m-9.3 2.4h1.2m2.2 0h1.2m-4.6 2.4h1.2m2.2 0h1.2" fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="1.3"/>
                        </svg>
                        <span class="app-nav-label">Consultas</span>
                    </a>
                </nav>

                <div class="app-sidebar-footer">
                    <button class="btn app-collapse-toggle d-none d-lg-flex"
                            type="button"
                            data-sidebar-toggle
                            aria-controls="appSidebar"
                            aria-expanded="true"
                            aria-label="Recolher menu">
                        <svg class="app-collapse-icon" viewBox="0 0 16 16" aria-hidden="true">
                            <path d="m10 3-5 5 5 5" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"/>
                        </svg>
                        <span class="app-nav-label">Recolher menu</span>
                    </button>
                </div>
            </div>
        </aside>

        <div class="app-main flex-grow-1">
            <header class="app-topbar d-flex align-items-center">
                <button class="btn btn-outline-secondary d-lg-none me-3"
                        type="button"
                        data-bs-toggle="offcanvas"
                        data-bs-target="#appSidebar"
                        aria-controls="appSidebar"
                        aria-label="Abrir menu">
                    <svg class="app-nav-icon" viewBox="0 0 16 16" aria-hidden="true">
                        <path d="M2 3.5h12M2 8h12M2 12.5h12" fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="1.5"/>
                    </svg>
                </button>
                <span class="app-topbar-title">@yield('title', 'Prontuário')</span>
                @if (request()->routeIs('pacientes.index'))
                    <a class="btn btn-primary ms-auto" href="{{ route('pacientes.create') }}">Cadastrar paciente</a>
                @elseif (request()->routeIs('consultas.index'))
                    <a class="btn btn-primary ms-auto" href="{{ route('consultas.create') }}">Cadastrar consulta</a>
                @endif
            </header>

            <main class="app-content container-fluid">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
