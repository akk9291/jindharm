<!DOCTYPE html>
<html lang="hi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>प्रवेश करें (Login) - Universal Dharmik CMS</title>
    
    <!-- Google Fonts & Font Awesome -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Noto+Sans+Devanagari:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #ea580c;
            --primary-hover: #c2410c;
            --bg-dark: #0f172a;
            --text-dark: #1e293b;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', 'Noto Sans Devanagari', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            width: 100%;
            max-width: 440px;
            padding: 40px;
            color: white;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3), 0 10px 10px -5px rgba(0, 0, 0, 0.3);
            position: relative;
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), #f97316);
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
        }

        .brand-logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .brand-logo i {
            color: var(--primary);
            font-size: 44px;
            margin-bottom: 12px;
        }

        .brand-logo h1 {
            font-size: 22px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .brand-logo p {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.6);
            margin-top: 4px;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 8px;
            color: rgba(255, 255, 255, 0.9);
        }

        .input-group {
            position: relative;
        }

        .input-group i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.4);
            font-size: 16px;
        }

        .form-control {
            width: 100%;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 12px 14px 12px 42px;
            color: white;
            font-size: 15px;
            outline: none;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(234, 88, 12, 0.2);
        }

        .remember-flex {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 30px;
            font-size: 13px;
        }

        .checkbox-container {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .btn-submit {
            width: 100%;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 14px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-submit:hover {
            background: var(--primary-hover);
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .alert-success {
            background: rgba(34, 197, 94, 0.15);
            border: 1px solid rgba(34, 197, 94, 0.3);
            color: #86efac;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="brand-logo">
            <i class="fa-solid fa-om"></i>
            <h1>धर्म CMS (Dharmik CMS)</h1>
            <p>यूनिवर्सल धार्मिक प्रबंधन प्रणाली (SaaS Ready)</p>
        </div>

        @if(session('success'))
            <div class="alert-success">
                <i class="fa-solid fa-circle-check"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="alert-error">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form method="POST" action="{{ url('/admin/login') }}">
            @csrf
            
            <div class="form-group">
                <label class="form-label" for="email">ईमेल पता (Email Address)</label>
                <div class="input-group">
                    <i class="fa-solid fa-envelope"></i>
                    <input class="form-control" type="email" name="email" id="email" value="{{ old('email') }}" required placeholder="admin@jindharm.com">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">पासवर्ड (Password)</label>
                <div class="input-group">
                    <i class="fa-solid fa-lock"></i>
                    <input class="form-control" type="password" name="password" id="password" required placeholder="••••••••">
                </div>
            </div>

            <div class="remember-flex">
                <label class="checkbox-container">
                    <input type="checkbox" name="remember" id="remember">
                    <span>मुझे याद रखें (Remember Me)</span>
                </label>
            </div>

            <button type="submit" class="btn-submit">प्रवेश करें (Login) <i class="fa-solid fa-arrow-right-to-bracket" style="margin-left: 6px;"></i></button>
        </form>
    </div>

</body>
</html>
