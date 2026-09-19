<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Redacción — Debatehosting</title>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Space Grotesk', sans-serif; }
        body { background: #0F172A; color: #F8FAFC; display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 1.5rem; }
        .login-card { background: #1E293B; border: 1px solid #334155; border-radius: 8px; width: 100%; max-width: 400px; padding: 2.5rem; box-shadow: 0 10px 25px rgba(0,0,0,0.5); }
        .login-title { font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem; text-align: center; }
        .login-title span { color: #10B981; }
        .login-subtitle { font-size: 0.85rem; color: #94A3B8; text-align: center; margin-bottom: 2rem; }
        .form-group { margin-bottom: 1.25rem; }
        label { display: block; font-size: 0.85rem; color: #94A3B8; margin-bottom: 0.4rem; }
        input[type="email"], input[type="password"] { width: 100%; background: #0F172A; border: 1px solid #334155; color: #fff; padding: 0.75rem 1rem; border-radius: 6px; font-size: 0.95rem; }
        input:focus { outline: none; border-color: #10B981; }
        .btn-submit { width: 100%; background: #10B981; color: #fff; font-weight: 700; border: none; padding: 0.8rem; border-radius: 6px; font-size: 1rem; cursor: pointer; margin-top: 1rem; }
        .btn-submit:hover { background: #059669; }
        .error-msg { background: #7F1D1D; color: #FECACA; padding: 0.75rem; border-radius: 6px; font-size: 0.85rem; margin-bottom: 1.25rem; }
    </style>
</head>
<body>
    <div class="login-card">
        <h1 class="login-title">Debate<span>Hosting</span></h1>
        <p class="login-subtitle">Panel de Control Editorial y Auditorías</p>

        @if($errors->any())
            <div class="error-msg">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('admin.login.submit') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="admin@debatehosting.com">
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required placeholder="••••••••">
            </div>

            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem; font-size: 0.85rem; color: #94A3B8;">
                <input type="checkbox" name="remember" id="remember">
                <label for="remember" style="margin-bottom: 0; cursor: pointer;">Recordar sesión</label>
            </div>

            <button type="submit" class="btn-submit">Entrar al Panel</button>
        </form>
    </div>
</body>
</html>
