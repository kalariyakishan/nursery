<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | New Vrundavan Nursery Portal 🍃</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;900&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --primary: #1B5E20;
            --primary-light: #4CAF50;
            --primary-dark: #0D3D0F;
            --background: #F6FBF6;
            --surface: #FFFFFF;
            --text-primary: #1A1A1A;
            --text-secondary: #6B7280;
            --transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--background);
            color: var(--text-primary);
            overflow: hidden;
        }

        .nursery-title {
            font-family: 'Times New Roman', serif;
            letter-spacing: -0.02em;
        }

        .login-container {
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            height: 100vh;
            width: 100vw;
        }

        /* --- Full Screen Left Side (Image) --- */
        .login-visual {
            position: relative;
            background: url('{{ asset('indoor_plant_category_1775270994110.png') }}');
            background-size: cover;
            background-position: center;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 4rem;
            color: white;
        }

        .login-visual::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(rgba(13, 61, 15, 0.5), rgba(0, 0, 0, 0.2));
            z-index: 1;
        }

        .visual-top, .visual-bottom { position: relative; z-index: 2; }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: white;
        }

        .logo-text { font-size: 1.5rem; font-weight: 900; text-transform: uppercase; line-height: 1; }

        .visual-bottom h2 {
            font-size: 3rem;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 1rem;
            font-family: 'Poppins', sans-serif;
        }

        .visual-bottom p {
            font-size: 1.1rem;
            opacity: 0.8;
            max-width: 400px;
        }

        /* --- Right Side (Form) --- */
        .login-form-side {
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 4rem;
            position: relative;
        }

        .back-home {
            position: absolute;
            top: 3rem;
            right: 3rem;
            text-decoration: none;
            color: var(--text-secondary);
            font-weight: 600;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: var(--transition);
        }

        .back-home:hover { color: var(--primary); transform: translateX(-5px); }

        .form-wrapper {
            width: 100%;
            max-width: 420px;
        }

        .form-header { margin-bottom: 3rem; }

        .form-header h1 {
            font-size: 2.25rem;
            font-weight: 800;
            color: var(--primary-dark);
            margin-bottom: 0.5rem;
            font-family: 'Poppins', sans-serif;
        }

        .form-header p {
            color: var(--text-secondary);
            font-weight: 500;
        }

        .input-group { margin-bottom: 1.75rem; }

        .input-label {
            display: block;
            margin-bottom: 0.6rem;
            font-weight: 700;
            font-size: 0.85rem;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .input-field {
            width: 100%;
            padding: 1rem 1.25rem;
            background: var(--background);
            border: 2px solid transparent;
            border-radius: 14px;
            font-family: inherit;
            font-weight: 500;
            transition: var(--transition);
            color: var(--text-primary);
        }

        .input-field:focus {
            outline: none;
            border-color: var(--primary-light);
            background: white;
            box-shadow: 0 0 0 4px rgba(76, 175, 80, 0.1);
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2.5rem;
        }

        .checkbox-container {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--text-secondary);
        }

        .checkbox-container input {
            accent-color: var(--primary);
            width: 18px;
            height: 18px;
        }

        .forgot-link {
            text-decoration: none;
            color: var(--primary);
            font-size: 0.9rem;
            font-weight: 600;
            transition: var(--transition);
        }

        .forgot-link:hover { color: var(--primary-dark); text-decoration: underline; }

        .btn-login {
            width: 100%;
            padding: 1.1rem;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 14px;
            font-weight: 800;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            box-shadow: 0 8px 25px rgba(27, 94, 32, 0.2);
        }

        .btn-login:hover {
            background: var(--primary-dark);
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(27, 94, 32, 0.3);
        }

        .error-msg {
            color: #d32f2f;
            font-size: 0.8rem;
            margin-top: 0.5rem;
            font-weight: 600;
        }

        @media (max-width: 1024px) {
            .login-container { grid-template-columns: 1fr; }
            .login-visual { display: none; }
            .login-form-side { padding: 2rem; }
        }
    </style>
</head>
<body>

    <div class="login-container">
        <!-- Visual Side -->
        <div class="login-visual">
            <div class="visual-top">
                <a href="/" class="logo">
                    <i data-lucide="leaf" size="32" stroke-width="2.5"></i>
                    <div>
                        <span class="logo-text nursery-title">New Vrundavan</span>
                    </div>
                </a>
            </div>
            
            <div class="visual-bottom">
                <h2>Breathe Life into <br>Your Dashboard.</h2>
                <p>Management portal for the finest botanical nursery in Junagadh district. Access your inventory and customer data with ease.</p>
            </div>
        </div>

        <!-- Form Side -->
        <div class="login-form-side">
            <a href="/" class="back-home">
                <i data-lucide="arrow-left" size="18"></i>
                Back to Site
            </a>

            <div class="form-wrapper">
                <div class="form-header">
                    <h1>Employee Login</h1>
                    <p>Welcome back! Please enter your credentials.</p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Address -->
                    <div class="input-group">
                        <label class="input-label" for="email">Work Email</label>
                        <input type="email" id="email" name="email" class="input-field" 
                               placeholder="email@vrundavan.com" value="{{ old('email') }}" 
                               required autofocus autocomplete="username">
                        @if($errors->has('email'))
                            <p class="error-msg">{{ $errors->first('email') }}</p>
                        @endif
                    </div>

                    <!-- Password -->
                    <div class="input-group">
                        <label class="input-label" for="password">Password</label>
                        <input type="password" id="password" name="password" class="input-field" 
                               placeholder="••••••••" required autocomplete="current-password">
                        @if($errors->has('password'))
                            <p class="error-msg">{{ $errors->first('password') }}</p>
                        @endif
                    </div>

                    <!-- Remember & Forgot -->
                    <div class="remember-forgot">
                        <label class="checkbox-container">
                            <input type="checkbox" name="remember">
                            <span>Keep me logged in</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="forgot-link">Forgot?</a>
                        @endif
                    </div>

                    <button type="submit" class="btn-login">
                        Sign In <i data-lucide="log-in" size="20"></i>
                    </button>
                    
                    @if (Route::has('register'))
                        <div style="margin-top: 2rem; text-align: center; font-size: 0.9rem; color: var(--text-secondary);">
                            No account yet? <a href="{{ route('register') }}" style="color: var(--primary); font-weight: 700; text-decoration: none;">Contact Manager</a>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
