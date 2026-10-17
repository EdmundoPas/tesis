<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f5f7fb">
    <title>Iniciar sesión | San Luis Gonzaga</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body class="auth-page">
    <main class="auth-card">
        <a class="brand auth-brand" href="{{ route('dashboard') }}">
            <span class="brand-mark">SL</span>
            <span class="brand-copy"><strong>San Luis</strong><small>SEGUIMIENTO ACADÉMICO</small></span>
        </a>
        <div class="eyebrow">PLATAFORMA ESCOLAR</div>
        <h1>Bienvenido de nuevo</h1>
        <p class="page-subtitle">Ingresa con tu cuenta institucional de docente o administrador.</p>

        <form class="auth-form" method="POST" action="{{ route('login') }}">
            @csrf
            <label for="role">Tipo de acceso</label>
            <select id="role" name="role" required>
                <option value="teacher" @selected(old('role') === 'teacher')>Docente</option>
                <option value="admin" @selected(old('role') === 'admin')>Administración</option>
            </select>
            @error('role') <span class="form-error">{{ $message }}</span> @enderror

            <label for="email">Correo institucional</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="username" required autofocus>
            @error('email') <span class="form-error">{{ $message }}</span> @enderror

            <label for="password">Contraseña</label>
            <input id="password" name="password" type="password" autocomplete="current-password" required>
            @error('password') <span class="form-error">{{ $message }}</span> @enderror

            <label class="remember-option"><input type="checkbox" name="remember" value="1"> Mantener mi sesión iniciada</label>
            <button class="button button-primary auth-submit" type="submit">Ingresar al panel <span aria-hidden="true">→</span></button>
        </form>
        <a class="auth-back" href="{{ route('dashboard') }}">← Volver al resumen público</a>
    </main>
</body>
</html>
