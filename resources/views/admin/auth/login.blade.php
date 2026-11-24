<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login - Fox Grooming</title>
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-image: url("{{ asset('assets/images/bg.jpg') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-container {
            width: 100%;
            max-width: 450px;
        }
        
        .login-card {
            background-color: #ffffff00;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 50px 40px;
            box-shadow: 0 10px 40px rgb(95 95 95 / 50%);
            backdrop-filter: blur(10px);
        }
        
        .login-logo {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .login-logo img {
            max-width: 220px;
            height: auto;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 35px;
        }
        
        .login-title {
            font-size: 28px;
            font-weight: 700;
            color: #FFFFFF;
            margin-bottom: 8px;
        }
        
        .login-subtitle {
            font-size: 14px;
            color: #B0B0B0;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #FFFFFF;
            font-size: 14px;
        }
        
        .form-control {
            width: 100%;
            padding: 14px 16px;
            background-color: #ffffff !important;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            color: #000;
            font-size: 14px;
            transition: all 0.2s ease;
            /* backdrop-filter: blur(10px); */
        }
        
        .form-control:focus {
            outline: none;
            border-color: #ff0015ff;
            box-shadow: 0 0 0 3px rgba(230, 57, 70, 0.1);
            background-color: #1A1A1A;
        }
        
        .form-control::placeholder {
            color: #707070;
        }
        
        .input-icon {
            position: relative;
        }
        
        .input-icon i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #707070;
            font-size: 16px;
        }
        
        .input-icon .form-control {
            padding-left: 45px;
        }
        
        .form-check {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
        }
        
        .form-check input[type="checkbox"] {
            width: 18px;
            height: 18px;
            margin-right: 10px;
            cursor: pointer;
            accent-color: #ff0015ff;
        }
        
        .form-check label {
            font-size: 14px;
            color: #B0B0B0;
            cursor: pointer;
        }
        
        .btn-login {
            width: 100%;
            padding: 14px;
            background-color: #ff0015ff;
            color: #FFFFFF;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .btn-login:hover {
            background-color: #ff0015ff;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(230, 57, 70, 0.3);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .alert {
            padding: 14px 18px;
            border-radius: 10px;
            margin-bottom: 25px;
            border-left: 4px solid;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .alert-danger {
            background-color: rgba(239, 68, 68, 0.1);
            border-color: #ff0000ff;
            color: #ff0000ff;
        }
        
        .alert i {
            font-size: 18px;
        }
        
        .login-footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 25px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .login-footer p {
            font-size: 13px;
            color: #707070;
        }
        
        /* Loading State */
        .btn-login.loading {
            pointer-events: none;
            opacity: 0.7;
        }
        
        .btn-login .spinner {
            display: none;
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }
        
        .btn-login.loading .spinner {
            display: block;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-logo">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Fox Grooming">
            </div>
            
            <div class="login-header">
                <h1 class="login-title">Admin Login</h1>
                <p class="login-subtitle">Sign in to access your dashboard</p>
            </div>
            
            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
            
            <form action="{{ route('admin.login') }}" method="POST" id="loginForm">
                @csrf
                
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <div class="input-icon">
                        <i class="fas fa-envelope"></i>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            class="form-control" 
                            placeholder="admin@foxgrooming.com"
                            value="{{ old('email') }}"
                            required
                            autofocus
                        >
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-icon">
                        <i class="fas fa-lock"></i>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="form-control" 
                            placeholder="Enter your password"
                            required
                        >
                    </div>
                </div>
                
                <!-- <div class="form-check">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Remember me</label>
                </div> -->
                
                <button type="submit" class="btn-login">
                    <span class="spinner"></span>
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Sign In</span>
                </button>
            </form>
            
            <div class="login-footer">
                <p>&copy; {{ date('Y') }} Fox Grooming. All rights reserved.</p>
            </div>
        </div>
    </div>
    
    <script>
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const btn = this.querySelector('.btn-login');
            btn.classList.add('loading');
        });
    </script>
</body>
</html>
