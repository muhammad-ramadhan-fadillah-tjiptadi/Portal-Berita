<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login - menit.com</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #0da2e7;
            --primary-dark: #0c8dcc;
            --primary-light: #3db3ea;
            --neutral-50: #f9fafb;
            --neutral-100: #f3f4f6;
            --neutral-200: #e5e7eb;
            --neutral-300: #d1d5db;
            --neutral-600: #4b5563;
            --neutral-700: #374151;
            --neutral-900: #111827;
            --success-color: #10b981;
            --danger-color: #ef4444;
            --border-radius: 12px;
        }

        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--neutral-50);
            color: var(--neutral-900);
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

        .login-container {
            display: flex;
            min-height: 100vh;
            background-color: #fff;
        }

        /* Left Side - Illustration */
        .login-illustration {
            flex: 1;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            position: relative;
            overflow: hidden;
        }

        .login-illustration::before {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            top: -100px;
            right: -100px;
        }

        .login-illustration::after {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            bottom: -50px;
            left: -50px;
        }

        .illustration-content {
            position: relative;
            z-index: 1;
            text-align: center;
            color: white;
        }

        .illustration-icon {
            font-size: 80px;
            margin-bottom: 30px;
            opacity: 0.9;
        }

        .illustration-content h2 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 15px;
            line-height: 1.3;
        }

        .illustration-content p {
            font-size: 16px;
            opacity: 0.9;
            line-height: 1.6;
            max-width: 300px;
        }

        /* Right Side - Form */
        .login-form-section {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            background-color: #fff;
        }

        .login-form-wrapper {
            width: 100%;
            max-width: 420px;
            padding: 0 20px;
        }

        .login-header {
            margin-bottom: 40px;
        }

        .login-header h1 {
            font-size: 28px;
            font-weight: 700;
            color: var(--neutral-900);
            margin-bottom: 8px;
        }

        .login-header p {
            font-size: 14px;
            color: var(--neutral-600);
        }

        /* Alert Messages */
        .alert {
            border: none;
            border-radius: var(--border-radius);
            padding: 12px 16px;
            font-size: 14px;
            margin-bottom: 20px;
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-success {
            background-color: #d1fae5;
            color: #065f46;
            border-left: 4px solid var(--success-color);
        }

        .alert-danger {
            background-color: #fee2e2;
            color: #7f1d1d;
            border-left: 4px solid var(--danger-color);
        }

        /* Form Group */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: var(--neutral-700);
            margin-bottom: 8px;
        }

        .form-control {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid var(--neutral-200);
            border-radius: var(--border-radius);
            font-size: 14px;
            transition: all 0.3s ease;
            background-color: var(--neutral-50);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            background-color: #fff;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .form-control.is-invalid {
            border-color: var(--danger-color) !important;
            background-color: #fff;
            padding-right: calc(1.5em + 0.75rem);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }

        .form-control.is-invalid:focus {
            box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
        }

        /* Error Messages */
        .error-message {
            display: block;
            font-size: 13px;
            color: var(--danger-color);
            margin-top: 6px;
            font-weight: 500;
        }

        /* Submit Button */
        .btn-login {
            width: 100%;
            padding: 12px 16px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: var(--border-radius);
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .btn-login:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(37, 99, 235, 0.3);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        /* Footer Link */
        .login-footer {
            margin-top: 30px;
            text-align: center;
        }

        /* Divider */
        .auth-divider {
            height: 1px;
            background-color: var(--neutral-200);
            margin: 32px 0;
            width: 100%;
        }

        .auth-footer {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            margin-top: 24px;
            padding: 0 16px;
            font-size: 14px;
            color: var(--neutral-600);
            text-align: center;
        }

        .auth-footer .text-muted {
            color: var(--neutral-600);
        }

        .auth-footer .auth-link {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
        }

        .auth-footer .divider {
            color: var(--neutral-300);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
            }

            .login-illustration {
                min-height: 250px;
                padding: 30px 20px;
            }

            .illustration-icon {
                font-size: 60px;
                margin-bottom: 20px;
            }

            .illustration-content h2 {
                font-size: 24px;
                margin-bottom: 10px;
            }

            .illustration-content p {
                font-size: 14px;
            }

            .login-form-section {
                padding: 30px 20px;
                min-height: auto;
            }

            .login-form-wrapper {
                max-width: 100%;
            }

            .login-header h1 {
                font-size: 24px;
            }
        }

        @media (max-width: 480px) {
            .login-illustration {
                min-height: 200px;
                padding: 20px;
            }

            .illustration-icon {
                font-size: 48px;
                margin-bottom: 15px;
            }

            .illustration-content h2 {
                font-size: 20px;
            }

            .login-form-section {
                padding: 20px;
            }

            .login-header {
                margin-bottom: 30px;
            }

            .login-header h1 {
                font-size: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <!-- Left Side - Illustration -->
        <div class="login-illustration">
            <div class="illustration-content">
                <div class="illustration-icon">
                    <i class="fas fa-lock"></i>
                </div>
                <h2>Selamat Datang</h2>
                <p>Masuk ke akun Anda untuk melanjutkan ke platform kami</p>
            </div>
        </div>

        <!-- Right Side - Form -->
        <div class="login-form-section">
            <div class="login-form-wrapper">
                <div class="login-header">
                    <h1>Login</h1>
                    <p>Gunakan email dan password Anda</p>
                </div>

                <!-- Session Messages -->
                @if (Session::get('success'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ Session::get('success') }}
                    </div>
                @endif

                @if (Session::get('error'))
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ Session::get('error') }}
                    </div>
                @endif

                <!-- Login Form -->
                <form method="POST" action="{{ route('auth') }}" novalidate>
                    @csrf

                    <!-- Email Input -->
                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" id="email" name="email"
                            class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}"
                            placeholder="nama@email.com" required {{-- Remove browser validation --}}
                            oninvalid="this.setCustomValidity('')" oninput="this.setCustomValidity('')" />
                        @error('email')
                            <div class="error-message mt-2">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Password Input -->
                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password"
                            class="form-control @error('password') is-invalid @enderror" placeholder="••••••••" required
                            {{-- Remove browser validation --}} oninvalid="this.setCustomValidity('')"
                            oninput="this.setCustomValidity('')" />
                        @error('password')
                            <div class="error-message mt-2">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-login">
                        <i class="fas fa-sign-in-alt me-2"></i>
                        Login
                    </button>
                </form>

                <!-- Divider -->
                <div class="auth-divider"></div>

                <!-- Footer Links -->
                <div class="auth-footer">
                    <span class="text-muted">Belum punya akun?</span>
                    <a href="{{ route('signup') }}" class="auth-link">
                        Daftar di sini
                    </a>
                    <span class="divider">|</span>
                    <a href="{{ route('home') }}" class="auth-link">
                        <i class="fas fa-arrow-left me-1"></i>
                        Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
