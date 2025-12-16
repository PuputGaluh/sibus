<!DOCTYPE html>
<html>
<head>
    <title>Login - INKA</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #dc143c 0%, #8b0000 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .login-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 400px;
            padding: 40px 30px;
            border-top: 5px solid #dc143c;
        }

        .logo-section {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
        }

        .logo-circle {
            width: 35px;
            height: 35px;
            background: #e74c3c;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 8px;
            position: relative;
        }

        .logo-circle::before {
            content: '';
            width: 12px;
            height: 12px;
            background: white;
            border-radius: 50%;
            position: absolute;
        }

        .logo-text {
            font-size: 32px;
            font-weight: bold;
            color: #1a1a1a;
            letter-spacing: 1px;
        }

        .subtitle {
            font-size: 14px;
            color: #1a1a1a;
            font-weight: 600;
            line-height: 1.4;
        }

        .error-message {
            background: #fee;
            color: #c33;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 14px;
            border-left: 4px solid #e74c3c;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 8px;
            font-size: 14px;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 5px;
            font-size: 14px;
            transition: all 0.3s;
            background: white;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #dc143c;
            background: white;
            box-shadow: 0 0 0 3px rgba(220, 20, 60, 0.1);
        }

        input::placeholder {
            color: #999;
            font-size: 13px;
        }

        .login-btn {
            width: 100%;
            padding: 14px;
            background: #1a1a1a;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
        }

        .login-btn:hover {
            background: #dc143c;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 20, 60, 0.3);
        }

        .register-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }

        .register-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo-section">
            <div class="logo">
                <div class="logo-circle"></div>
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

        <form method="POST" action="{{ url('login') }}">
            @csrf
            <div class="form-group">
                <label for="email">Username</label>
                <input 
                    type="email" 
                    id="email"
                    name="email" 
                    placeholder="Masukkan Email atau Username ........"
                    required
                    value="{{ old('email') }}"
                >
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input 
                    type="password" 
                    id="password"
                    name="password" 
                    placeholder="Masukkan Password ...................."
                    required
                >
            </div>

            <button type="submit" class="login-btn">Login</button>
        </form>
<!-- 
        <div class="register-link">
            <a href="{{ route('register') }}">Belum punya akun? Register</a>
        </div> -->
    </div>
</body>
</html>