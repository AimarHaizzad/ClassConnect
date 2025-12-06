<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ClassConnect</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: #F2EFDF;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            display: flex;
            max-width: 1200px;
            width: 100%;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .left-panel {
            flex: 2;
            background: white;
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .graduation-icon {
            font-size: 120px;
            margin-bottom: 30px;
            color: #000;
        }

        .left-panel h1 {
            font-size: 32px;
            font-weight: bold;
            color: #000;
            margin-bottom: 20px;
        }

        .left-panel p {
            font-size: 16px;
            color: #333;
            line-height: 1.6;
            max-width: 500px;
        }

        .right-panel {
            flex: 1;
            background: #9A7A4A;
            padding: 50px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .logo-text {
            font-family: 'Brush Script MT', cursive;
            font-size: 36px;
            color: white;
            margin-bottom: 30px;
            text-align: center;
        }

        .form-title {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 30px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            color: #666;
            font-size: 18px;
            z-index: 1;
        }

        .form-input {
            width: 100%;
            padding: 12px 15px 12px 45px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            background: white;
            color: #333;
        }

        .form-input:focus {
            outline: none;
            border-color: #9A7A4A;
        }

        .form-input::placeholder {
            color: #999;
        }

        .form-select {
            width: 100%;
            padding: 12px 15px 12px 45px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            background: white;
            color: #333;
            appearance: none;
            cursor: pointer;
        }

        .form-select:focus {
            outline: none;
            border-color: #9A7A4A;
        }

        .select-arrow {
            position: absolute;
            right: 15px;
            color: #666;
            pointer-events: none;
        }

        .login-button {
            width: 100%;
            padding: 14px;
            background: #000;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 10px;
            transition: background 0.3s;
        }

        .login-button:hover {
            background: #333;
        }

        .form-links {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            font-size: 14px;
        }

        .form-links a {
            color: #333;
            text-decoration: none;
            transition: color 0.3s;
        }

        .form-links a:hover {
            color: #9A7A4A;
        }

        .error-message {
            color: #dc3545;
            font-size: 14px;
            margin-top: 5px;
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .left-panel {
                padding: 40px 20px;
            }

            .right-panel {
                padding: 40px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="left-panel">
            <div class="graduation-icon">🎓</div>
            <h1>Empower Your Education Journey Today!</h1>
            <p>With ClassConnect, students and teachers can access classes, share materials, and communicate effortlessly. Stay updated with announcements, manage assignments, and keep your academic life organized anytime, anywhere.</p>
        </div>

        <div class="right-panel">
            <div class="logo-text">ClassConnect</div>
            <h2 class="form-title">User Login</h2>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <div class="input-wrapper">
                        <span class="input-icon">👤</span>
                        <input type="text" name="username" class="form-input" placeholder="username" value="{{ old('username') }}" required>
                    </div>
                    @error('username')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="input-wrapper">
                        <span class="input-icon">🔒</span>
                        <input type="password" name="password" class="form-input" placeholder="password" required>
                    </div>
                    @error('password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="input-wrapper">
                        <span class="input-icon">▼</span>
                        <select name="user_type" class="form-select" required>
                            <option value="">select user</option>
                            <option value="student" {{ old('user_type') == 'student' ? 'selected' : '' }}>Student</option>
                            <option value="lecturer" {{ old('user_type') == 'lecturer' ? 'selected' : '' }}>Lecturer</option>
                        </select>
                        <span class="select-arrow">▼</span>
                    </div>
                    @error('user_type')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="login-button">Login</button>

                <div class="form-links">
                    <a href="{{ route('register') }}">Create an account</a>
                    <a href="#">Forgot Password?</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
