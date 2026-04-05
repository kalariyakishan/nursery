<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registration | New Vrundavan Nursery Partner 🍃</title>
    
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

        .reg-container {
            display: grid;
            grid-template-columns: 0.9fr 1.1fr; /* Image on right now for variation */
            height: 100vh;
            width: 100vw;
        }

        /* --- Visual Side (Right Side) --- */
        .reg-visual {
            position: relative;
            background: url('{{ asset('flower_plants_category_1775272097882.png') }}');
            background-size: cover;
            background-position: center;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 4rem;
            color: white;
        }

        .reg-visual::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(rgba(0, 0, 0, 0.1), rgba(13, 61, 15, 0.7));
            z-index: 1;
        }

        .visual-content { position: relative; z-index: 2; }

        .visual-content h2 {
            font-size: 3.5rem;
            font-weight: 900;
            line-height: 1;
            margin-bottom: 1.5rem;
            font-family: 'Poppins', sans-serif;
            text-transform: uppercase;
        }

        .badge {
            background: var(--primary);
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 800;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            display: inline-block;
            margin-bottom: 1.5rem;
        }

        /* --- Form Side (Left Side) --- */
        .reg-form-side {
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 4rem;
            position: relative;
            overflow-y: auto;
        }

        .back-home {
            position: absolute;
            top: 3rem;
            left: 3rem;
            text-decoration: none;
            color: var(--text-secondary);
            font-weight: 600;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: var(--transition);
        }

        .back-home:hover { color: var(--primary); transform: translateX(5px); }

        .form-wrapper {
            width: 100%;
            max-width: 480px;
            padding: 2rem 0;
        }

        .form-header { margin-bottom: 3rem; }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: var(--primary);
            margin-bottom: 2rem;
        }

        .logo-text { font-size: 1.5rem; font-weight: 900; text-transform: uppercase; line-height: 1; }

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

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.25rem;
        }

        .input-group { margin-bottom: 1.5rem; }

        .full-width { grid-column: span 2; }

        .input-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 700;
            font-size: 0.8rem;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        .input-field {
            width: 100%;
            padding: 0.9rem 1.25rem;
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
        }

        .btn-reg {
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
            margin-top: 1rem;
            box-shadow: 0 8px 25px rgba(27, 94, 32, 0.2);
        }

        .btn-reg:hover {
            background: var(--primary-dark);
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(27, 94, 32, 0.3);
        }

        .error-msg {
            color: #d32f2f;
            font-size: 0.75rem;
            margin-top: 0.4rem;
            font-weight: 600;
        }

        .footer-link {
            margin-top: 2rem;
            text-align: center;
            font-size: 0.95rem;
            color: var(--text-secondary);
            font-weight: 500;
        }

        .footer-link a {
            color: var(--primary);
            font-weight: 800;
            text-decoration: none;
        }

        @media (max-width: 1024px) {
            .reg-container { grid-template-columns: 1fr; }
            .reg-visual { display: none; }
            .reg-form-side { padding: 2rem; }
            .form-grid { grid-template-columns: 1fr; }
            .full-width { grid-column: span 1; }
        }
    </style>
</head>
<body>

    <div class="reg-container">
        <!-- Form Side -->
        <div class="reg-form-side">
            <a href="/" class="back-home">
                <i data-lucide="home" size="18"></i>
                Home
            </a>

            <div class="form-wrapper">
                <div class="form-header">
                    <a href="/" class="logo">
                        <i data-lucide="leaf" size="32" stroke-width="2.5"></i>
                        <div>
                            <span class="logo-text nursery-title">New Vrundavan</span>
                        </div>
                    </a>
                    <h1>Partner Registration</h1>
                    <p>Join our botanical network and manage your landscapes.</p>
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="form-grid">
                        <!-- Full Name -->
                        <div class="input-group full-width">
                            <label class="input-label" for="name">Full Name</label>
                            <input type="text" id="name" name="name" class="input-field" 
                                   placeholder="e.g. Rahul Sharma" value="{{ old('name') }}" 
                                   required autofocus autocomplete="name">
                            @if($errors->has('name'))
                                <p class="error-msg">{{ $errors->first('name') }}</p>
                            @endif
                        </div>

                        <!-- Email Address -->
                        <div class="input-group full-width">
                            <label class="input-label" for="email">Work Email</label>
                            <input type="email" id="email" name="email" class="input-field" 
                                   placeholder="email@company.com" value="{{ old('email') }}" 
                                   required autocomplete="username">
                            @if($errors->has('email'))
                                <p class="error-msg">{{ $errors->first('email') }}</p>
                            @endif
                        </div>

                        <!-- Password -->
                        <div class="input-group">
                            <label class="input-label" for="password">Password</label>
                            <input type="password" id="password" name="password" class="input-field" 
                                   placeholder="••••••••" required autocomplete="new-password">
                            @if($errors->has('password'))
                                <p class="error-msg">{{ $errors->first('password') }}</p>
                            @endif
                        </div>

                        <!-- Confirm Password -->
                        <div class="input-group">
                            <label class="input-label" for="password_confirmation">Confirm</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" 
                                   class="input-field" placeholder="••••••••" required>
                        </div>
                    </div>

                    <button type="submit" class="btn-reg">
                        Create Account <i data-lucide="user-plus" size="20"></i>
                    </button>
                    
                    <div class="footer-link">
                        Already have an account? <a href="{{ route('login') }}">Sign In</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Visual Side -->
        <div class="reg-visual">
            <div class="visual-content">
                <span class="badge">A Blooming Community</span>
                <h2>Bloom <br>With Us.</h2>
                <p style="font-size: 1.1rem; opacity: 0.9; line-height: 1.5; max-width: 450px;">Become a part of Gadu's largest landscape network. Our digital portal helps you manage procurement, inventory, and design projects seamlessly.</p>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
