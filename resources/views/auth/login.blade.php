<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - INKA</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        /* Animated Background Circles */
        body::before,
        body::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.05);
            animation: float 20s infinite ease-in-out;
        }

        body::before {
            width: 400px;
            height: 400px;
            top: -200px;
            right: -200px;
            animation-delay: 0s;
        }

        body::after {
            width: 300px;
            height: 300px;
            bottom: -150px;
            left: -150px;
            animation-delay: 10s;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(50px, 50px) scale(1.1); }
        }

        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 440px;
            padding: 50px 40px;
            border-top: 6px solid #dc2626;
            animation: slideUp 0.6s ease;
            position: relative;
            z-index: 1;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo-section {
            text-align: center;
            margin-bottom: 40px;
        }

        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            animation: fadeInDown 0.6s ease 0.2s backwards;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo-image-wrapper {
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 14px;
            position: relative;
            box-shadow: 0 8px 20px rgba(220, 38, 38, 0.4);
            animation: pulse 2s infinite;
            padding: 8px;
            border: 3px solid #dc2626;
        }

        @keyframes pulse {
            0%, 100% {
                box-shadow: 0 8px 20px rgba(220, 38, 38, 0.4);
                transform: scale(1);
            }
            50% {
                box-shadow: 0 8px 30px rgba(220, 38, 38, 0.6);
                transform: scale(1.05);
            }
        }

        .logo-image-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 50%;
        }

        .logo-text {
            font-size: 42px;
            font-weight: 800;
            color: #1f2937;
            letter-spacing: 3px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.05);
        }

        .subtitle {
            font-size: 15px;
            color: #6b7280;
            font-weight: 500;
            line-height: 1.6;
            animation: fadeIn 0.6s ease 0.4s backwards;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .error-message {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            color: #991b1b;
            padding: 16px 18px;
            border-radius: 12px;
            margin-bottom: 26px;
            font-size: 14px;
            border-left: 5px solid #dc2626;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: shake 0.4s ease, fadeIn 0.3s ease;
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.15);
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }

        .error-message::before {
            content: '⚠';
            font-size: 22px;
            flex-shrink: 0;
        }

        .form-group {
            margin-bottom: 26px;
            animation: fadeInUp 0.6s ease backwards;
        }

        .form-group:nth-child(1) { animation-delay: 0.5s; }
        .form-group:nth-child(2) { animation-delay: 0.6s; }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 10px;
            font-size: 14px;
        }

        label svg {
            width: 18px;
            height: 18px;
            color: #dc2626;
        }

        .input-wrapper {
            position: relative;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 15px 18px 15px 50px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.3s ease;
            background: #f9fafb;
            font-family: inherit;
            color: #1f2937;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #dc2626;
            background: white;
            box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.1);
            transform: translateY(-1px);
        }

        input::placeholder {
            color: #9ca3af;
            font-size: 14px;
        }

        .input-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            pointer-events: none;
            transition: color 0.3s ease;
        }

        input:focus + .input-icon {
            color: #dc2626;
        }

        .login-btn {
            width: 100%;
            padding: 17px;
            background: linear-gradient(135deg, #1f2937, #111827);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            animation: fadeInUp 0.6s ease 0.7s backwards;
            box-shadow: 0 4px 12px rgba(31, 41, 55, 0.3);
        }

        .login-btn:hover {
            background: linear-gradient(135deg, #dc2626, #991b1b);
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(220, 38, 38, 0.4);
        }

        .login-btn:active {
            transform: translateY(-1px);
        }

        .login-btn svg {
            width: 20px;
            height: 20px;
        }

        .register-link {
            text-align: center;
            margin-top: 26px;
            font-size: 14px;
            color: #6b7280;
            animation: fadeIn 0.6s ease 0.8s backwards;
        }

        .register-link a {
            color: #dc2626;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .register-link a:hover {
            color: #991b1b;
            text-decoration: underline;
        }

        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 28px 0;
            color: #9ca3af;
            font-size: 13px;
            animation: fadeIn 0.6s ease 0.9s backwards;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #e5e7eb;
        }

        .divider span {
            padding: 0 14px;
            font-weight: 500;
        }

        /* Show/Hide Password Toggle */
        .toggle-password {
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #9ca3af;
            transition: color 0.3s ease;
        }

        .toggle-password:hover {
            color: #dc2626;
        }

        /* Footer */
        .login-footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            font-size: 12px;
            color: #9ca3af;
            animation: fadeIn 0.6s ease 1s backwards;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .login-container {
                padding: 40px 28px;
                border-radius: 16px;
            }

            .logo-text {
                font-size: 36px;
            }

            .logo-image-wrapper {
                width: 65px;
                height: 65px;
            }

            .subtitle {
                font-size: 14px;
            }

            input[type="text"],
            input[type="password"] {
                padding: 14px 16px 14px 46px;
            }

            .login-btn {
                padding: 15px;
            }
        }

        /* Loading State */
        .login-btn.loading {
            pointer-events: none;
            opacity: 0.7;
        }

        .login-btn.loading::after {
            content: '';
            width: 16px;
            height: 16px;
            border: 2px solid white;
            border-top-color: transparent;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
            margin-left: 8px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo-section">
            <div class="logo">
                <div class="logo-image-wrapper">
                    <img src="{{ asset('img/INKALogo.png') }}" alt="INKA Logo">
                </div>
                <span class="logo-text">INKA</span>
            </div>
            <div class="subtitle">
                Sistem Informasi Manajemen<br>
                Kerusakan Bus Listrik
            </div>
        </div>

        @if($errors->any())
            <div class="error-message">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" id="loginForm">
            @csrf
            <div class="form-group">
                <label for="username">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    Username
                </label>
                <div class="input-wrapper">
                    <input 
                        type="text" 
                        id="username"
                        name="username" 
                        placeholder="Masukkan username anda"
                        required
                        autofocus
                        value="{{ old('username') }}"
                    >
                    <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </div>
            </div>

            <div class="form-group">
                <label for="password">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                    Password
                </label>
                <div class="input-wrapper">
                    <input 
                        type="password" 
                        id="password"
                        name="password" 
                        placeholder="Masukkan password anda"
                        required
                    >
                    <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                    <svg class="toggle-password" id="togglePassword" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                </div>
            </div>

            <button type="submit" class="login-btn" id="loginBtn">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                    <polyline points="10 17 15 12 10 7"></polyline>
                    <line x1="15" y1="12" x2="3" y2="12"></line>
                </svg>
                Login
            </button>
        </form>

        <div class="login-footer">
            © 2025 INKA. All rights reserved.
        </div>
    </div>

    <script>
        // Toggle password visibility
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        if (togglePassword) {
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                // Toggle icon
                if (type === 'text') {
                    this.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>';
                } else {
                    this.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>';
                }
            });
        }

        // Form submit loading state
        const loginForm = document.getElementById('loginForm');
        const loginBtn = document.getElementById('loginBtn');

        if (loginForm) {
            loginForm.addEventListener('submit', function() {
                loginBtn.classList.add('loading');
                loginBtn.innerHTML = '<span>Memproses...</span>';
            });
        }

        // Auto-dismiss error after 5 seconds
        const errorMessage = document.querySelector('.error-message');
        if (errorMessage) {
            setTimeout(() => {
                errorMessage.style.animation = 'fadeOut 0.3s ease forwards';
                setTimeout(() => errorMessage.remove(), 300);
            }, 5000);
        }

        // Keyframe for fadeOut
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeOut {
                to {
                    opacity: 0;
                    transform: translateY(-10px);
                }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>