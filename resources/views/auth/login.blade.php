<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dentalux — Iniciar Sesión</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=DM+Sans:wght@400;500&display=swap"
        rel="stylesheet">
    <style>
        *,
        *::before,
        *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: #F8F7F5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-wrap {
            display: grid;
            grid-template-columns: 1fr 1fr;
            width: 100%;
            max-width: 940px;
            min-height: 580px;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 24px 64px rgba(0, 0, 0, 0.13);
        }

        /* Panel izquierdo */
        .login-left {
            background: #1A1A2E;
            padding: 48px 44px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        /* Círculos decorativos */
        .login-left::before {
            content: '';
            position: absolute;
            width: 340px;
            height: 340px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(200, 57, 90, 0.18) 0%, transparent 70%);
            top: -100px;
            right: -100px;
        }

        .login-left::after {
            content: '';
            position: absolute;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(200, 57, 90, 0.12) 0%, transparent 70%);
            bottom: -60px;
            left: -60px;
        }

        .login-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            position: relative;
            z-index: 1;
        }

        .brand-logo {
            width: 46px;
            height: 46px;
            background: #C8395A;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .brand-name {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 22px;
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.5px;
        }

        .brand-sub {
            font-size: 10px;
            color: rgba(255, 255, 255, 0.38);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .login-hero {
            position: relative;
            z-index: 1;
        }

        .login-hero h2 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 28px;
            font-weight: 700;
            color: #fff;
            line-height: 1.25;
            margin-bottom: 14px;
        }

        .login-hero p {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.48);
            line-height: 1.65;
            max-width: 300px;
        }

        .login-features {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.52);
        }

        .feature-dot {
            width: 20px;
            height: 20px;
            border-radius: 6px;
            background: rgba(200, 57, 90, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .feature-dot svg {
            width: 12px;
            height: 12px;
        }

        /* Panel derecho */
        .login-right {
            background: #fff;
            padding: 52px 48px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-right h1 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 26px;
            font-weight: 700;
            color: #1A1A2E;
            margin-bottom: 6px;
        }

        .login-right .subtitle {
            font-size: 14px;
            color: #6B7280;
            margin-bottom: 32px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #1A1A2E;
            margin-bottom: 7px;
        }

        .input-wrap {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            color: #9CA3AF;
            width: 17px;
            height: 17px;
        }

        .form-control {
            width: 100%;
            padding: 11px 14px 11px 40px;
            border: 1.5px solid #E5E7EB;
            border-radius: 10px;
            font-size: 14px;
            font-family: 'DM Sans', sans-serif;
            color: #1A1A2E;
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
            background: #FAFAFA;
            -webkit-appearance: none;
        }

        .form-control:focus {
            border-color: #C8395A;
            box-shadow: 0 0 0 3px rgba(200, 57, 90, 0.1);
            background: #fff;
        }

        .form-control::placeholder {
            color: #C4C9D4;
        }

        .form-error {
            font-size: 12px;
            color: #DC2626;
            margin-top: 5px;
        }

        .remember-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .remember-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #6B7280;
            cursor: pointer;
            user-select: none;
        }

        .remember-label input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #C8395A;
            cursor: pointer;
        }

        .forgot-link {
            font-size: 13px;
            color: #C8395A;
            text-decoration: none;
            font-weight: 500;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        .btn-login {
            width: 100%;
            padding: 13px;
            background: #C8395A;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-login:hover {
            background: #A62A48;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(200, 57, 90, 0.3);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .alert-error {
            background: #FEE2E2;
            color: #991B1B;
            border-left: 4px solid #DC2626;
            padding: 12px 14px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 20px;
        }

        .login-footer {
            margin-top: 24px;
            text-align: center;
            font-size: 12px;
            color: #9CA3AF;
        }

        /* Responsive */
        @media (max-width: 700px) {
            body {
                padding: 0;
                align-items: stretch;
            }

            .login-wrap {
                grid-template-columns: 1fr;
                border-radius: 0;
                min-height: 100vh;
                box-shadow: none;
            }

            .login-left {
                display: none;
            }

            .login-right {
                padding: 40px 28px;
                justify-content: flex-start;
                padding-top: 60px;
            }
        }

        @media (max-width: 400px) {
            .login-right {
                padding: 32px 20px;
                padding-top: 50px;
            }
        }
    </style>
</head>

<body>

    <div class="login-wrap">

        {{-- Panel izquierdo --}}
        <div class="login-left">
            <div class="login-brand">
                <div class="brand-logo">
                    <svg viewBox="0 0 38 38" fill="none" width="46" height="46">
                        <path
                            d="M19 6C13.5 6 9 10.5 9 16C9 18.5 9.9 20.8 11 23L13 30C13.5 31.8 15.2 33 17 33H21C22.8 33 24.5 31.8 25 30L27 23C28.1 20.8 29 18.5 29 16C29 10.5 24.5 6 19 6Z"
                            fill="white" opacity="0.9" />
                        <path
                            d="M19 6C16 6 13.3 7.5 11.8 9.8C13.2 9.1 14.8 8.7 16.5 8.7C21.2 8.7 25 12.3 25.5 17C27.2 16.1 28.2 14.5 28.2 12.8C28.2 9 23.5 6 19 6Z"
                            fill="white" opacity="0.35" />
                    </svg>
                </div>
                <div>
                    <div class="brand-name">dentalux</div>
                    <div class="brand-sub">Odontología Familiar</div>
                </div>
            </div>

            <div class="login-hero">
                <h2>Sistema de gestión dental moderno</h2>
                <p>Administra pacientes, presupuestos y pagos de manera simple y eficiente desde cualquier dispositivo.
                </p>
            </div>

            <div class="login-features">
                <div class="feature-item">
                    <div class="feature-dot">
                        <svg fill="none" stroke="#C8395A" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    Control de pagos con firma digital
                </div>
                <div class="feature-item">
                    <div class="feature-dot">
                        <svg fill="none" stroke="#C8395A" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    Odontograma interactivo por paciente
                </div>
                <div class="feature-item">
                    <div class="feature-dot">
                        <svg fill="none" stroke="#C8395A" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    Presupuestos e historial clínico
                </div>
                <div class="feature-item">
                    <div class="feature-dot">
                        <svg fill="none" stroke="#C8395A" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    Acceso multi-usuario y multi-dispositivo
                </div>
            </div>
        </div>

        {{-- Panel derecho --}}
        <div class="login-right">

            <h1>Bienvenido de vuelta</h1>
            <p class="subtitle">Ingresa tus credenciales para continuar</p>

            @if (session('status'))
                <div class="alert-error">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="email">Correo Electrónico</label>
                    <div class="input-wrap">
                        <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                        </svg>
                        <input type="email" id="email" name="email" class="form-control"
                            value="{{ old('email') }}" placeholder="correo@ejemplo.com" required autofocus
                            autocomplete="email">
                    </div>
                    @error('email')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Contraseña</label>
                    <div class="input-wrap">
                        <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <input type="password" id="password" name="password" class="form-control"
                            placeholder="••••••••" required autocomplete="current-password">
                    </div>
                    @error('password')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="remember-row">
                    <label class="remember-label">
                        <input type="checkbox" name="remember">
                        Recordarme
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link">
                            ¿Olvidaste tu contraseña?
                        </a>
                    @endif
                </div>

                <button type="submit" class="btn-login">
                    <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                    Ingresar al sistema
                </button>

            </form>

            <div class="login-footer">
                Dentalux Odontología Familiar · Cuenca, Ecuador
            </div>

        </div>
    </div>

</body>

</html>
