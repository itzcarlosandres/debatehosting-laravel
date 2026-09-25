<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel de Administración — DebateHosting')</title>

    <!-- Tipografías: Plus Jakarta Sans & IBM Plex Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Iconos Lucide -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        :root {
            /* Colores Base - Dark Minimalista */
            --bg-body: #090A0C;
            --bg-sidebar: #0D0F12;
            --bg-card: #13161C;
            --bg-card-subtle: #181B22;
            --bg-hover: #1E222B;
            --bg-input: #101217;

            /* Bordes */
            --border-subtle: rgba(255, 255, 255, 0.07);
            --border-medium: rgba(255, 255, 255, 0.12);
            --border-hover: rgba(255, 255, 255, 0.2);

            /* Tipografía */
            --text-main: #F3F4F6;
            --text-muted: #9CA3AF;
            --text-dim: #94A3B8;
            --text-dimmer: #64748B;

            /* Acentos */
            --emerald-primary: #10B981;
            --emerald-dark: #059669;
            --emerald-subtle: rgba(16, 185, 129, 0.12);
            --emerald-glow: rgba(16, 185, 129, 0.25);

            --rose-primary: #F43F5E;
            --rose-subtle: rgba(244, 63, 94, 0.12);

            --sky-primary: #38BDF8;
            --sky-subtle: rgba(56, 189, 248, 0.12);

            --amber-primary: #F59E0B;
            --amber-subtle: rgba(245, 158, 11, 0.12);

            /* Tipografías */
            --font-sans: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            --font-mono: 'IBM Plex Mono', monospace;

            /* Dimensiones */
            --sidebar-w: 270px;
            --header-h: 64px;
            --radius-sm: 6px;
            --radius-md: 10px;
            --radius-lg: 14px;
        }

        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: var(--bg-body);
            color: var(--text-main);
            font-family: var(--font-sans);
            font-size: 14px;
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Barra Lateral (Sidebar) */
        .admin-sidebar {
            width: var(--sidebar-w);
            background-color: var(--bg-sidebar);
            border-right: 1px solid var(--border-subtle);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 50;
            transition: transform 0.28s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.28s ease;
        }

        .sidebar-header {
            height: var(--header-h);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.25rem 0 1.5rem;
            border-bottom: 1px solid var(--border-subtle);
        }

        .sidebar-close-btn {
            display: none;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border-subtle);
            color: var(--text-muted);
            width: 34px;
            height: 34px;
            border-radius: var(--radius-sm);
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .sidebar-close-btn:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #FFFFFF;
            border-color: var(--border-hover);
        }

        /* Backdrop translúcido para versión móvil */
        .sidebar-backdrop {
            position: fixed;
            inset: 0;
            background-color: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            z-index: 45;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.25s ease;
        }

        body.sidebar-open .sidebar-backdrop {
            opacity: 1;
            pointer-events: auto;
        }

        body.sidebar-open {
            overflow: hidden;
        }

        /* Botón Hamburguesa Topbar */
        .topbar-sidebar-toggle {
            display: none;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            border-radius: var(--radius-sm);
            background: var(--bg-card);
            border: 1px solid var(--border-medium);
            color: var(--text-main);
            cursor: pointer;
            transition: all 0.15s ease;
            flex-shrink: 0;
        }

        .topbar-sidebar-toggle:hover {
            background: var(--bg-hover);
            border-color: var(--emerald-primary);
            color: var(--emerald-primary);
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: var(--text-main);
        }

        .brand-icon {
            width: 34px;
            height: 34px;
            background: linear-gradient(135deg, var(--emerald-primary), #047857);
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #FFFFFF;
            box-shadow: 0 2px 10px var(--emerald-glow);
        }

        .brand-text-title {
            font-weight: 800;
            font-size: 1.05rem;
            letter-spacing: -0.02em;
            color: #FFFFFF;
        }

        .brand-text-title span {
            color: var(--emerald-primary);
        }

        .brand-badge {
            font-family: var(--font-mono);
            font-size: 0.65rem;
            font-weight: 700;
            color: var(--emerald-primary);
            background: var(--emerald-subtle);
            border: 1px solid rgba(16, 185, 129, 0.2);
            padding: 0.15rem 0.45rem;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .sidebar-nav-container {
            flex: 1;
            overflow-y: auto;
            padding: 1.25rem 1rem;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .sidebar-group {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .sidebar-group-title {
            font-family: var(--font-mono);
            font-size: 0.68rem;
            font-weight: 700;
            color: var(--text-dim);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            padding: 0 0.75rem;
            margin-bottom: 0.4rem;
        }

        .nav-item-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.65rem 0.85rem;
            min-height: 42px;
            border-radius: var(--radius-sm);
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.15s ease;
        }

        .nav-item-link:hover {
            background-color: var(--bg-hover);
            color: var(--text-main);
        }

        .nav-item-link.active {
            background-color: var(--emerald-subtle);
            color: var(--emerald-primary);
            font-weight: 600;
            border: 1px solid rgba(16, 185, 129, 0.25);
        }

        .nav-item-left {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .nav-item-badge {
            font-family: var(--font-mono);
            font-size: 0.7rem;
            padding: 0.1rem 0.45rem;
            border-radius: 4px;
            background: rgba(255, 255, 255, 0.08);
            color: var(--text-muted);
        }

        .nav-item-link.active .nav-item-badge {
            background: rgba(16, 185, 129, 0.2);
            color: var(--emerald-primary);
        }

        /* Footer del Usuario en Sidebar */
        .sidebar-footer {
            padding: 1rem 1.25rem;
            border-top: 1px solid var(--border-subtle);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: rgba(0, 0, 0, 0.2);
        }

        .user-profile-wrap {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            overflow: hidden;
        }

        .user-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #1F2430;
            border: 1px solid var(--border-medium);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.8rem;
            color: var(--emerald-primary);
            flex-shrink: 0;
            position: relative;
        }

        .user-status-dot {
            width: 8px;
            height: 8px;
            background: var(--emerald-primary);
            border: 1.5px solid var(--bg-sidebar);
            border-radius: 50%;
            position: absolute;
            bottom: -1px;
            right: -1px;
        }

        .user-info-text {
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .user-name {
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--text-main);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-role {
            font-size: 0.72rem;
            color: var(--text-dim);
        }

        .btn-logout-icon {
            background: none;
            border: none;
            color: var(--text-dim);
            cursor: pointer;
            padding: 0.4rem;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s ease;
        }

        .btn-logout-icon:hover {
            color: var(--rose-primary);
            background-color: var(--rose-subtle);
        }

        /* Contenedor Principal (Main Area) */
        .admin-main-wrapper {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: var(--bg-body);
        }

        /* Barra Superior (Topbar) */
        .admin-topbar {
            height: var(--header-h);
            background-color: var(--bg-sidebar);
            border-bottom: 1px solid var(--border-subtle);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            position: sticky;
            top: 0;
            z-index: 40;
            backdrop-filter: blur(8px);
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .topbar-breadcrumbs {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.84rem;
            color: var(--text-dim);
        }

        .topbar-breadcrumbs a {
            color: var(--text-muted);
            text-decoration: none;
            transition: color 0.15s ease;
        }

        .topbar-breadcrumbs a:hover {
            color: var(--text-main);
        }

        .topbar-breadcrumbs .current {
            color: var(--text-main);
            font-weight: 600;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .live-status-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background-color: rgba(16, 185, 129, 0.08);
            border: 1px solid rgba(16, 185, 129, 0.2);
            color: var(--emerald-primary);
            font-family: var(--font-mono);
            font-size: 0.72rem;
            font-weight: 600;
            padding: 0.25rem 0.65rem;
            border-radius: 20px;
        }

        .pulse-live-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background-color: var(--emerald-primary);
            box-shadow: 0 0 6px var(--emerald-primary);
            animation: pulseAnimation 2s infinite ease-in-out;
        }

        @keyframes pulseAnimation {
            0% { opacity: 0.5; transform: scale(0.9); }
            50% { opacity: 1; transform: scale(1.15); }
            100% { opacity: 0.5; transform: scale(0.9); }
        }

        .btn-view-site {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            background-color: var(--bg-card);
            border: 1px solid var(--border-medium);
            color: var(--text-muted);
            font-size: 0.8rem;
            font-weight: 600;
            padding: 0.45rem 0.85rem;
            border-radius: var(--radius-sm);
            text-decoration: none;
            transition: all 0.15s ease;
        }

        .btn-view-site:hover {
            background-color: var(--bg-hover);
            color: #FFFFFF;
            border-color: var(--border-hover);
        }

        /* Área de Contenido */
        .admin-content {
            flex: 1;
            padding: 2rem 2.5rem 4rem 2.5rem;
            max-width: 1320px;
            width: 100%;
            margin: 0 auto;
        }

        /* Page Headers */
        .admin-page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 2rem;
            padding-bottom: 1.25rem;
            border-bottom: 1px solid var(--border-subtle);
        }

        .page-header-title {
            font-size: 1.6rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: #FFFFFF;
            margin-bottom: 0.25rem;
        }

        .page-header-subtitle {
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .page-header-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        /* Botones Globales Minimalistas */
        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            background-color: var(--emerald-primary);
            color: #FFFFFF;
            font-size: 0.84rem;
            font-weight: 600;
            padding: 0.55rem 1.1rem;
            border-radius: var(--radius-sm);
            border: 1px solid rgba(255, 255, 255, 0.15);
            text-decoration: none;
            cursor: pointer;
            box-shadow: 0 2px 8px var(--emerald-glow);
            transition: all 0.15s ease;
        }

        .btn-primary:hover {
            background-color: var(--emerald-dark);
            transform: translateY(-1px);
        }

        .btn-secondary {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            background-color: var(--bg-card);
            color: var(--text-main);
            font-size: 0.84rem;
            font-weight: 500;
            padding: 0.55rem 1rem;
            border-radius: var(--radius-sm);
            border: 1px solid var(--border-medium);
            text-decoration: none;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .btn-secondary:hover {
            background-color: var(--bg-hover);
            border-color: var(--border-hover);
        }

        .btn-danger {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            background-color: var(--rose-subtle);
            color: var(--rose-primary);
            font-size: 0.84rem;
            font-weight: 600;
            padding: 0.55rem 1rem;
            border-radius: var(--radius-sm);
            border: 1px solid rgba(244, 63, 94, 0.25);
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .btn-danger:hover {
            background-color: var(--rose-primary);
            color: #FFFFFF;
        }

        /* Notificaciones / Flash Messages */
        .toast-banner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.9rem 1.25rem;
            border-radius: var(--radius-sm);
            margin-bottom: 1.75rem;
            font-size: 0.86rem;
            font-weight: 500;
        }

        .toast-banner.success {
            background-color: rgba(16, 185, 129, 0.09);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #A7F3D0;
        }

        .toast-banner.error {
            background-color: rgba(244, 63, 94, 0.09);
            border: 1px solid rgba(244, 63, 94, 0.3);
            color: #FECDD3;
        }

        /* Tarjetas de Estadísticas (Stats) */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.25rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background-color: var(--bg-card);
            border: 1px solid var(--border-subtle);
            border-radius: var(--radius-md);
            padding: 1.35rem 1.5rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
            transition: border-color 0.15s ease, transform 0.15s ease;
        }

        .stat-card:hover {
            border-color: var(--border-medium);
            transform: translateY(-2px);
        }

        .stat-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.75rem;
        }

        .stat-label {
            font-size: 0.78rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-dim);
        }

        .stat-icon-box {
            width: 32px;
            height: 32px;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stat-value {
            font-family: var(--font-mono);
            font-size: 1.85rem;
            font-weight: 800;
            color: #FFFFFF;
            letter-spacing: -0.02em;
            line-height: 1.1;
        }

        .stat-footer {
            margin-top: 0.6rem;
            font-size: 0.74rem;
            color: var(--text-dim);
            display: flex;
            align-items: center;
            gap: 0.35rem;
        }

        /* Tablas Minimalistas */
        .table-card {
            background-color: var(--bg-card);
            border: 1px solid var(--border-subtle);
            border-radius: var(--radius-md);
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25);
        }

        .table-card-header {
            padding: 1.1rem 1.5rem;
            border-bottom: 1px solid var(--border-subtle);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .table-card-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        .minimal-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 0.85rem;
        }

        .minimal-table th {
            background-color: var(--bg-sidebar);
            padding: 0.85rem 1.25rem;
            font-family: var(--font-mono);
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--text-dim);
            border-bottom: 1px solid var(--border-subtle);
        }

        .minimal-table td {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border-subtle);
            color: var(--text-main);
            vertical-align: middle;
        }

        .minimal-table tr:last-child td {
            border-bottom: none;
        }

        .minimal-table tr:hover td {
            background-color: rgba(255, 255, 255, 0.02);
        }

        /* Badges de Estado */
        .badge-status {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            font-family: var(--font-mono);
            font-size: 0.72rem;
            font-weight: 600;
            padding: 0.2rem 0.55rem;
            border-radius: 4px;
        }

        .badge-status.active {
            background-color: var(--emerald-subtle);
            color: var(--emerald-primary);
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .badge-status.inactive {
            background-color: var(--rose-subtle);
            color: var(--rose-primary);
            border: 1px solid rgba(244, 63, 94, 0.2);
        }

        /* Formularios Minimalistas */
        .form-panel {
            background-color: var(--bg-card);
            border: 1px solid var(--border-subtle);
            border-radius: var(--radius-md);
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .form-panel-header {
            margin-bottom: 1.75rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border-subtle);
        }

        .form-panel-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: #FFFFFF;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-panel-desc {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-top: 0.25rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-muted);
            margin-bottom: 0.45rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .form-control {
            width: 100%;
            background-color: var(--bg-input);
            border: 1px solid var(--border-medium);
            border-radius: var(--radius-sm);
            color: #FFFFFF;
            padding: 0.65rem 0.9rem;
            font-family: inherit;
            font-size: 0.88rem;
            outline: none;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        .form-control:focus {
            border-color: var(--emerald-primary);
            box-shadow: 0 0 0 2px var(--emerald-subtle);
        }

        .form-control::placeholder {
            color: var(--text-dim);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 80px;
        }

        .form-help {
            font-size: 0.74rem;
            color: var(--text-dim);
            margin-top: 0.35rem;
        }

        .form-actions-bar {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 0.75rem;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border-subtle);
        }

        /* Toggle Checkbox Estilizado */
        .toggle-label-wrap {
            display: inline-flex;
            align-items: center;
            gap: 0.65rem;
            cursor: pointer;
            user-select: none;
        }

        .toggle-label-wrap input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: var(--emerald-primary);
            cursor: pointer;
        }

        .toggle-text {
            font-size: 0.85rem;
            color: var(--text-main);
            font-weight: 500;
        }

        @media (max-width: 900px) {
            .topbar-sidebar-toggle {
                display: inline-flex;
            }
            .sidebar-close-btn {
                display: inline-flex;
            }
            .admin-sidebar {
                transform: translateX(-100%);
                z-index: 100;
                box-shadow: 0 0 40px rgba(0, 0, 0, 0.85);
            }
            body.sidebar-open .admin-sidebar {
                transform: translateX(0);
            }
            .admin-main-wrapper {
                margin-left: 0;
                width: 100%;
                min-width: 0;
            }
            .admin-content {
                padding: 1.25rem 1rem 3rem 1rem;
            }
            .admin-topbar {
                padding: 0 1rem;
                gap: 0.75rem;
            }
            .topbar-left {
                gap: 0.75rem;
                min-width: 0;
                flex: 1;
            }
            .topbar-breadcrumbs {
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
                font-size: 0.82rem;
            }
            .topbar-breadcrumbs .current {
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }
        }

        @media (max-width: 640px) {
            .admin-topbar {
                height: 58px;
                padding: 0 0.85rem;
            }
            .topbar-breadcrumbs a,
            .topbar-breadcrumbs .breadcrumb-separator {
                display: none;
            }
            .topbar-breadcrumbs .current {
                font-size: 0.85rem;
                font-weight: 700;
            }
            .live-status-pill .live-status-text {
                display: none;
            }
            .live-status-pill {
                padding: 0.4rem;
                border-radius: 50%;
                justify-content: center;
            }
            .btn-view-site span {
                display: none;
            }
            .btn-view-site {
                padding: 0.45rem;
                width: 34px;
                height: 34px;
                justify-content: center;
            }
            .admin-page-header {
                flex-direction: column;
                align-items: stretch;
                gap: 1rem;
                margin-bottom: 1.5rem;
            }
            .page-header-title {
                font-size: 1.35rem;
            }
            .page-header-subtitle {
                font-size: 0.82rem;
            }
            .page-header-actions {
                width: 100%;
                flex-wrap: wrap;
            }
            .page-header-actions .btn-primary,
            .page-header-actions .btn-secondary,
            .page-header-actions .btn-danger {
                flex: 1;
                min-width: 130px;
                justify-content: center;
            }
            .stats-grid {
                grid-template-columns: 1fr;
                gap: 0.85rem;
            }
            .table-card-header {
                flex-direction: column;
                align-items: stretch;
                gap: 0.75rem;
                padding: 1rem;
            }
            .table-card-header > div {
                width: 100%;
            }
            .table-card-header input {
                width: 100% !important;
                box-sizing: border-box;
            }
            .form-panel {
                padding: 1.25rem 1rem;
            }
            .form-actions-bar {
                flex-direction: column;
                align-items: stretch;
            }
            .form-actions-bar .btn-primary,
            .form-actions-bar .btn-secondary {
                width: 100%;
                justify-content: center;
            }
        }

        /* Pills para Categorías */
        .category-toggle-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            font-family: var(--font-mono);
            font-size: 0.82rem;
            padding: 0.45rem 0.85rem;
            border-radius: 6px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid var(--border-medium);
            color: var(--text-muted);
            cursor: pointer;
            user-select: none;
            transition: all 0.15s ease;
        }

        .category-toggle-pill:hover {
            background: rgba(255, 255, 255, 0.08);
            color: #FFFFFF;
            border-color: var(--border-hover);
        }

        .category-toggle-pill.is-selected {
            background: rgba(16, 185, 129, 0.15);
            border-color: var(--emerald-primary);
            color: #10B981;
            font-weight: 700;
        }

        /* Pills para Badges */
        .badge-select-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            font-family: var(--font-mono);
            font-size: 0.8rem;
            font-weight: 700;
            padding: 0.45rem 0.85rem;
            border-radius: 6px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid var(--border-subtle);
            color: var(--text-muted);
            cursor: pointer;
            user-select: none;
            transition: all 0.15s ease;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .badge-select-pill:hover {
            border-color: var(--border-hover);
            color: #FFFFFF;
        }

        .badge-select-pill.is-selected {
            background: rgba(255, 255, 255, 0.14);
            border-color: #FFFFFF;
            color: #FFFFFF;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.1);
        }

        .badge-select-pill.badge-pill-green {
            background: rgba(16, 185, 129, 0.1);
            border-color: rgba(16, 185, 129, 0.3);
            color: #10B981;
        }
        .badge-select-pill.badge-pill-green.is-selected {
            background: rgba(16, 185, 129, 0.25);
            border-color: #10B981;
            color: #34D399;
            box-shadow: 0 0 12px rgba(16, 185, 129, 0.2);
        }

        .badge-select-pill.badge-pill-gold {
            background: rgba(245, 158, 11, 0.1);
            border-color: rgba(245, 158, 11, 0.3);
            color: #F59E0B;
        }
        .badge-select-pill.badge-pill-gold.is-selected {
            background: rgba(245, 158, 11, 0.25);
            border-color: #F59E0B;
            color: #FBBF24;
            box-shadow: 0 0 12px rgba(245, 158, 11, 0.2);
        }

        .badge-select-pill.badge-pill-red {
            background: rgba(239, 68, 68, 0.1);
            border-color: rgba(239, 68, 68, 0.3);
            color: #EF4444;
        }
        .badge-select-pill.badge-pill-red.is-selected {
            background: rgba(239, 68, 68, 0.25);
            border-color: #EF4444;
            color: #F87171;
            box-shadow: 0 0 12px rgba(239, 68, 68, 0.2);
        }

        .badge-select-pill.badge-pill-dark {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.15);
            color: #CBD5E1;
        }
        .badge-select-pill.badge-pill-dark.is-selected {
            background: rgba(255, 255, 255, 0.18);
            border-color: #CBD5E1;
            color: #FFFFFF;
        }

        .badge-select-pill.badge-pill-sky {
            background: rgba(14, 165, 233, 0.1);
            border-color: rgba(14, 165, 233, 0.3);
            color: #0EA5E9;
        }
        .badge-select-pill.badge-pill-sky.is-selected {
            background: rgba(14, 165, 233, 0.25);
            border-color: #0EA5E9;
            color: #38BDF8;
        }

        .badge-select-pill.badge-pill-rose {
            background: rgba(244, 63, 94, 0.1);
            border-color: rgba(244, 63, 94, 0.3);
            color: #F43F5E;
        }
        .badge-select-pill.badge-pill-rose.is-selected {
            background: rgba(244, 63, 94, 0.25);
            border-color: #F43F5E;
            color: #FB7185;
        }
    </style>
    @stack('admin-styles')
</head>
<body>
    <!-- Barra Lateral -->
    <aside class="admin-sidebar">
        <!-- Logo & Encabezado -->
        <!-- Logo & Encabezado -->
        <div class="sidebar-header">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
                <div class="brand-icon" style="background: rgba(16, 185, 129, 0.15); border-color: rgba(16, 185, 129, 0.3); color: var(--emerald-primary);">
                    <i data-lucide="shield" style="width: 17px; height: 17px;"></i>
                </div>
                <div>
                    <div class="brand-text-title" style="font-family: var(--font-serif); font-size: 1.08rem; font-weight: 800; letter-spacing: -0.01em;">
                        Debate <span style="font-family: var(--font-sans); font-size: 0.82rem; font-weight: 600; color: var(--text-muted);">Admin</span>
                    </div>
                </div>
            </a>
            <button type="button" id="sidebar-close-btn" class="sidebar-close-btn" aria-label="Cerrar menú" title="Cerrar menú">
                <i data-lucide="x" style="width: 18px; height: 18px;"></i>
            </button>
        </div>

        <!-- Menú de Navegación -->
        <div class="sidebar-nav-container">
            <!-- MÉTRICAS & RESUMEN -->
            <div class="sidebar-group">
                <div class="sidebar-group-title">MÉTRICAS & RESUMEN</div>
                <a href="{{ route('admin.dashboard') }}" class="nav-item-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <div class="nav-item-left">
                        <i data-lucide="layout-dashboard" style="width: 15px; height: 15px;"></i>
                        <span>Dashboard</span>
                    </div>
                </a>
            </div>

            <!-- CATÁLOGO -->
            <div class="sidebar-group">
                <div class="sidebar-group-title">CATÁLOGO</div>
                <a href="{{ route('admin.providers.index') }}" class="nav-item-link {{ request()->routeIs('admin.providers.*') ? 'active' : '' }}">
                    <div class="nav-item-left">
                        <i data-lucide="server" style="width: 15px; height: 15px;"></i>
                        <span>Proveedores</span>
                    </div>
                    <span class="nav-item-badge">{{ \App\Models\Provider::count() }}</span>
                </a>

                <a href="{{ route('admin.coupons.index') }}" class="nav-item-link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
                    <div class="nav-item-left">
                        <i data-lucide="ticket" style="width: 15px; height: 15px;"></i>
                        <span>Cupones</span>
                    </div>
                    <span class="nav-item-badge">{{ \App\Models\Coupon::count() }}</span>
                </a>

                <a href="{{ route('admin.categories.index') }}" class="nav-item-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <div class="nav-item-left">
                        <i data-lucide="layers" style="width: 15px; height: 15px;"></i>
                        <span>Categorías</span>
                    </div>
                </a>

                <a href="{{ route('admin.badges.index') }}" class="nav-item-link {{ request()->routeIs('admin.badges.*') ? 'active' : '' }}">
                    <div class="nav-item-left">
                        <i data-lucide="award" style="width: 15px; height: 15px;"></i>
                        <span>Badges</span>
                    </div>
                </a>
            </div>

            <!-- EDITORIAL -->
            <div class="sidebar-group">
                <div class="sidebar-group-title">EDITORIAL</div>
                <a href="{{ route('admin.reviews.index') }}" class="nav-item-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                    <div class="nav-item-left">
                        <i data-lucide="file-text" style="width: 15px; height: 15px;"></i>
                        <span>Reseñas & Análisis</span>
                    </div>
                    <span class="nav-item-badge">{{ \App\Models\Review::count() }}</span>
                </a>

                <a href="{{ route('admin.sections.index') }}" class="nav-item-link {{ request()->routeIs('admin.sections.*') ? 'active' : '' }}">
                    <div class="nav-item-left">
                        <i data-lucide="sliders" style="width: 15px; height: 15px;"></i>
                        <span>Portada & Secciones</span>
                    </div>
                </a>

                <a href="{{ route('admin.subscribers.index') }}" class="nav-item-link {{ request()->routeIs('admin.subscribers.*') ? 'active' : '' }}">
                    <div class="nav-item-left">
                        <i data-lucide="users" style="width: 15px; height: 15px;"></i>
                        <span>Suscriptores</span>
                    </div>
                    <span class="nav-item-badge">{{ \App\Models\Subscriber::count() }}</span>
                </a>
            </div>

            <!-- SISTEMA -->
            <div class="sidebar-group">
                <div class="sidebar-group-title">SISTEMA</div>
                <a href="{{ route('admin.settings') }}" class="nav-item-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                    <div class="nav-item-left">
                        <i data-lucide="settings" style="width: 15px; height: 15px;"></i>
                        <span>Configuración</span>
                    </div>
                </a>
            </div>
        </div>

        <!-- Pie de Usuario y Salida -->
        <div class="sidebar-footer" style="padding-top: 1rem; border-top: 1px solid var(--border-subtle); display: flex; flex-direction: column; gap: 0.75rem;">
            <div style="width: 100%;">
                <button type="button" class="btn-secondary" style="width: 100%; justify-content: center; font-size: 0.78rem; padding: 0.4rem 0.5rem; background: rgba(255, 255, 255, 0.03); display: inline-flex; align-items: center; gap: 0.4rem;">
                    <i data-lucide="moon" style="width: 13px; height: 13px;"></i>
                    <span>Modo Dark Black</span>
                </button>
            </div>

            <div style="display: flex; align-items: center; justify-content: space-between; width: 100%;">
                <div style="font-size: 0.74rem; color: var(--text-dim); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 150px;" title="{{ Auth::user()->email ?? 'admin@debatehosting.com' }}">
                    {{ Auth::user()->email ?? 'admin@debatehosting.com' }}
                </div>
                <form action="{{ route('admin.logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn-logout-icon" title="Cerrar sesión" style="padding: 4px;">
                        <i data-lucide="log-out" style="width: 14px; height: 14px;"></i>
                    </button>
                </form>
        </div>
    </aside>

    <!-- Backdrop translúcido para Menú Móvil -->
    <div id="sidebar-backdrop" class="sidebar-backdrop" aria-hidden="true"></div>

    <!-- Envoltorio Principal -->
    <div class="admin-main-wrapper">
        <!-- Topbar Superior -->
        <header class="admin-topbar">
            <div class="topbar-left">
                <button type="button" id="sidebar-toggle-btn" class="topbar-sidebar-toggle" aria-label="Abrir menú de navegación" title="Abrir menú">
                    <i data-lucide="menu" style="width: 19px; height: 19px;"></i>
                </button>
                <div class="topbar-breadcrumbs">
                    <a href="{{ route('admin.dashboard') }}">Consola</a>
                    <span class="breadcrumb-separator">/</span>
                    <span class="current">@yield('title', 'Panel')</span>
                </div>
            </div>

            <div class="topbar-right">
                <div class="live-status-pill" title="Monitor activo">
                    <span class="pulse-live-dot"></span>
                    <span class="live-status-text">MONITOR ACTIVO</span>
                </div>

                <a href="{{ route('home') }}" target="_blank" class="btn-view-site" title="Ver sitio web en vivo">
                    <span>Sitio en Vivo</span>
                    <i data-lucide="external-link" style="width: 13px; height: 13px;"></i>
                </a>
            </div>
        </header>

        <!-- Contenido Central -->
        <main class="admin-content">
            @if(session('success'))
                <div class="toast-banner success">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <i data-lucide="check-circle-2" style="width: 16px; height: 16px; color: var(--emerald-primary);"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="toast-banner error">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <i data-lucide="alert-triangle" style="width: 16px; height: 16px; color: var(--rose-primary);"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Inicializar Iconos Lucide y Control de Menú Móvil -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.lucide) {
                lucide.createIcons();
            }

            const toggleBtn = document.getElementById('sidebar-toggle-btn');
            const closeBtn = document.getElementById('sidebar-close-btn');
            const backdrop = document.getElementById('sidebar-backdrop');

            function openSidebar() {
                document.body.classList.add('sidebar-open');
                if (window.lucide) {
                    lucide.createIcons();
                }
            }

            function closeSidebar() {
                document.body.classList.remove('sidebar-open');
            }

            if (toggleBtn) {
                toggleBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    if (document.body.classList.contains('sidebar-open')) {
                        closeSidebar();
                    } else {
                        openSidebar();
                    }
                });
            }

            if (closeBtn) {
                closeBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    closeSidebar();
                });
            }

            if (backdrop) {
                backdrop.addEventListener('click', closeSidebar);
            }

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && document.body.classList.contains('sidebar-open')) {
                    closeSidebar();
                }
            });

            // Cerrar menú al hacer clic en enlaces de navegación en móvil
            document.querySelectorAll('.admin-sidebar .nav-item-link').forEach(function(link) {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 900) {
                        closeSidebar();
                    }
                });
            });
        });
    </script>
    @stack('admin-scripts')
</body>
</html>
