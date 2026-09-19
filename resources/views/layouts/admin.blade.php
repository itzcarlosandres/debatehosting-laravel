<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel de Administración — Debatehosting')</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;600&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg-body: #0F172A;
            --bg-card: #1E293B;
            --bg-sidebar: #0B1120;
            --border-color: #334155;
            --text-main: #F8FAFC;
            --text-muted: #94A3B8;
            --accent: #10B981;
            --accent-hover: #059669;
            --danger: #EF4444;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Space Grotesk', sans-serif; }
        body { background: var(--bg-body); color: var(--text-main); display: flex; min-height: 100vh; }
        .sidebar { width: 260px; background: var(--bg-sidebar); border-right: 1px solid var(--border-color); padding: 1.5rem; display: flex; flex-direction: column; justify-content: space-between; }
        .sidebar-brand { font-size: 1.25rem; font-weight: 700; color: #fff; text-decoration: none; display: flex; align-items: center; gap: 0.5rem; margin-bottom: 2rem; }
        .sidebar-brand span { color: var(--accent); }
        .nav-links { list-style: none; display: flex; flex-direction: column; gap: 0.5rem; }
        .nav-link { display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: 6px; color: var(--text-muted); text-decoration: none; font-size: 0.95rem; font-weight: 500; transition: all 0.2s; }
        .nav-link:hover, .nav-link.active { background: var(--bg-card); color: #fff; }
        .nav-link.active { border-left: 3px solid var(--accent); }
        .main-content { flex: 1; padding: 2.5rem; overflow-y: auto; }
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; border-bottom: 1px solid var(--border-color); padding-bottom: 1.25rem; }
        .btn-action { background: var(--accent); color: #fff; padding: 0.6rem 1.2rem; border-radius: 6px; text-decoration: none; font-weight: 600; font-size: 0.9rem; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem; }
        .btn-action:hover { background: var(--accent-hover); }
        .btn-danger { background: var(--danger); }
        .table-container { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; overflow: hidden; }
        table { width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem; }
        th, td { padding: 1rem; border-bottom: 1px solid var(--border-color); }
        th { background: #162032; color: var(--text-muted); font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; }
        .alert-success { background: #064E3B; border: 1px solid var(--accent); color: #D1FAE5; padding: 1rem; border-radius: 6px; margin-bottom: 1.5rem; }
        .form-group { margin-bottom: 1.25rem; }
        .form-label { display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.4rem; font-weight: 500; }
        .form-control { width: 100%; background: #0F172A; border: 1px solid var(--border-color); color: #fff; padding: 0.6rem 0.8rem; border-radius: 6px; font-size: 0.95rem; }
        .form-control:focus { outline: none; border-color: var(--accent); }
    </style>
    @stack('admin-styles')
</head>
<body>
    <aside class="sidebar">
        <div>
            <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
                Debate<span>Hosting</span> Admin
            </a>

            <ul class="nav-links">
                <li><a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">📊 Dashboard</a></li>
                <li><a href="{{ route('admin.providers.index') }}" class="nav-link {{ request()->routeIs('admin.providers.*') ? 'active' : '' }}">🏢 Proveedores</a></li>
                <li><a href="{{ route('admin.coupons.index') }}" class="nav-link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">🎟️ Cupones</a></li>
                <li><a href="{{ route('admin.settings') }}" class="nav-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}">⚙️ Portada y Ajustes</a></li>
                <li><a href="{{ route('home') }}" target="_blank" class="nav-link">↗ Ver Web Pública</a></li>
            </ul>
        </div>

        <div>
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="nav-link" style="width: 100%; background: none; border: none; cursor: pointer; color: #EF4444;">
                    🚪 Cerrar Sesión
                </button>
            </form>
        </div>
    </aside>

    <main class="main-content">
        @if(session('success'))
            <div class="alert-success">
                ✔ {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
