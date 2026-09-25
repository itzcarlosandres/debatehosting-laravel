<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Redacción — DebateHosting</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Iconos Lucide -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        :root {
            --bg-body: #090A0C;
            --bg-card: #13161C;
            --bg-input: #0D0F12;
            --border-subtle: rgba(255, 255, 255, 0.08);
            --border-medium: rgba(255, 255, 255, 0.14);
            --text-main: #F3F4F6;
            --text-muted: #9CA3AF;
            --text-dim: #6B7280;
            --emerald-primary: #10B981;
            --emerald-glow: rgba(16, 185, 129, 0.25);
            --rose-primary: #F43F5E;
            --font-sans: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            --font-mono: 'IBM Plex Mono', monospace;
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
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            position: relative;
            background-image: radial-gradient(circle at 50% 30%, rgba(16, 185, 129, 0.05) 0%, transparent 60%);
        }

        .login-card {
            background-color: var(--bg-card);
            border: 1px solid var(--border-subtle);
            border-radius: 12px;
            width: 100%;
            max-width: 390px;
            padding: 2.25rem;
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.45);
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .brand-emblem {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, var(--emerald-primary), #047857);
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #FFFFFF;
            box-shadow: 0 2px 12px var(--emerald-glow);
            margin-bottom: 1rem;
        }

        .brand-title {
            font-size: 1.35rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: #FFFFFF;
            margin-bottom: 0.25rem;
        }

        .brand-title span {
            color: var(--emerald-primary);
        }

        .brand-desc {
            font-size: 0.82rem;
            color: var(--text-muted);
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--text-muted);
            margin-bottom: 0.45rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .input-wrap {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 0.85rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-dim);
            pointer-events: none;
        }

        .form-control {
            width: 100%;
            background-color: var(--bg-input);
            border: 1px solid var(--border-medium);
            border-radius: 6px;
            color: #FFFFFF;
            padding: 0.7rem 0.9rem 0.7rem 2.4rem;
            font-family: inherit;
            font-size: 0.9rem;
            outline: none;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        .form-control:focus {
            border-color: var(--emerald-primary);
            box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2);
        }

        .btn-submit {
            width: 100%;
            background-color: var(--emerald-primary);
            color: #FFFFFF;
            font-family: inherit;
            font-size: 0.9rem;
            font-weight: 600;
            padding: 0.75rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 6px;
            cursor: pointer;
            box-shadow: 0 2px 10px var(--emerald-glow);
            transition: all 0.15s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            margin-top: 1.5rem;
        }

        .btn-submit:hover {
            background-color: #059669;
            transform: translateY(-1px);
        }

        .toast-error {
            background-color: rgba(244, 63, 94, 0.1);
            border: 1px solid rgba(244, 63, 94, 0.25);
            color: #FECDD3;
            padding: 0.75rem 1rem;
            border-radius: 6px;
            font-size: 0.82rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .login-footer {
            margin-top: 1.75rem;
            text-align: center;
            border-top: 1px solid var(--border-subtle);
            padding-top: 1.25rem;
        }

        .login-footer a {
            color: var(--text-dim);
            text-decoration: none;
            font-size: 0.78rem;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            transition: color 0.15s ease;
        }

        .login-footer a:hover {
            color: var(--text-main);
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <div class="brand-emblem">
                <i data-lucide="terminal" style="width: 22px; height: 22px;"></i>
            </div>
            <h1 class="brand-title">Debate<span>Hosting</span></h1>
            <p class="brand-desc">Consola Editorial y Administración</p>
        </div>

        @if($errors->any())
            <div class="toast-error">
                <i data-lucide="alert-circle" style="width: 16px; height: 16px; color: var(--rose-primary); flex-shrink: 0;"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form action="{{ route('admin.login.submit') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label" for="email">Correo Electrónico</label>
                <div class="input-wrap">
                    <i data-lucide="mail" class="input-icon" style="width: 15px; height: 15px;"></i>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus class="form-control" placeholder="correo@ejemplo.com">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Contraseña</label>
                <div class="input-wrap">
                    <i data-lucide="lock" class="input-icon" style="width: 15px; height: 15px;"></i>
                    <input type="password" id="password" name="password" required class="form-control" placeholder="••••••••">
                </div>
            </div>

            <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 0.5rem; font-size: 0.8rem;">
                <label style="display: flex; align-items: center; gap: 0.45rem; cursor: pointer; color: var(--text-muted); user-select: none;">
                    <input type="checkbox" name="remember" id="remember" style="accent-color: var(--emerald-primary);">
                    <span>Recordar sesión</span>
                </label>
            </div>

            <button type="submit" class="btn-submit">
                <span>Entrar al Panel</span>
                <i data-lucide="arrow-right" style="width: 15px; height: 15px;"></i>
            </button>
        </form>

        <div class="login-footer">
            <a href="{{ route('home') }}">
                <i data-lucide="arrow-left" style="width: 13px; height: 13px;"></i>
                <span>Volver a la web pública</span>
            </a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.lucide) {
                lucide.createIcons();
            }
        });
    </script>
</body>
</html>
