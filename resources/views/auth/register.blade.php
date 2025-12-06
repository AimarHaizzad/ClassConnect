<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - ClassConnect</title>
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
            max-width: 500px;
            width: 100%;
            background: white;
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .logo-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 30px;
            justify-content: center;
        }

        .logo-icon {
            font-size: 32px;
        }

        .logo-text {
            font-family: 'Brush Script MT', cursive;
            font-size: 32px;
            color: #000;
        }

        .form-title {
            font-size: 28px;
            font-weight: bold;
            color: #000;
            margin-bottom: 30px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-label {
            display: block;
            font-size: 14px;
            color: #333;
            margin-bottom: 8px;
            font-weight: 500;
        }

        .required {
            color: #dc3545;
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
            background: #f9f9f9;
            color: #333;
        }

        .form-input:focus {
            outline: none;
            border-color: #6B7F5E;
            background: white;
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
            background: #f9f9f9;
            color: #333;
            appearance: none;
            cursor: pointer;
        }

        .form-select:focus {
            outline: none;
            border-color: #6B7F5E;
            background: white;
        }

        .select-arrow {
            position: absolute;
            right: 15px;
            color: #666;
            pointer-events: none;
        }

        .register-button {
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

        .register-button:hover {
            background: #333;
        }

        .error-message {
            color: #dc3545;
            font-size: 14px;
            margin-top: 5px;
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            color: #6B7F5E;
            text-decoration: none;
            font-size: 14px;
        }

        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo-header">
            <div class="logo-icon">🎓</div>
            <div class="logo-text">ClassConnect</div>
        </div>

        <h2 class="form-title">Sign Up</h2>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-group">
                <label class="form-label">Name <span class="required">*</span></label>
                <div class="input-wrapper">
                    <span class="input-icon">👤</span>
                    <input type="text" name="name" class="form-input" placeholder="Name" value="{{ old('name') }}" required>
                </div>
                @error('name')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Username <span class="required">*</span></label>
                <div class="input-wrapper">
                    <span class="input-icon">👤</span>
                    <input type="text" name="username" class="form-input" placeholder="Username" value="{{ old('username') }}" required>
                </div>
                @error('username')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Email <span class="required">*</span></label>
                <div class="input-wrapper">
                    <span class="input-icon">✉</span>
                    <input type="email" name="email" class="form-input" placeholder="Email" value="{{ old('email') }}" required>
                </div>
                @error('email')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Mobile Phone <span class="required">*</span></label>
                <div class="input-wrapper">
                    <span class="input-icon">📞</span>
                    <input type="text" name="mobile_phone" class="form-input" placeholder="Mobile Phone" value="{{ old('mobile_phone') }}" required>
                </div>
                @error('mobile_phone')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Date of Birth <span class="required">*</span></label>
                <div class="input-wrapper">
                    <span class="input-icon">📅</span>
                    <input type="date" name="date_of_birth" class="form-input" value="{{ old('date_of_birth') }}" required>
                </div>
                @error('date_of_birth')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Password <span class="required">*</span></label>
                <div class="input-wrapper">
                    <span class="input-icon">🔒</span>
                    <input type="password" name="password" class="form-input" placeholder="Password" required>
                </div>
                @error('password')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">User ID <span class="required">*</span></label>
                <div class="input-wrapper">
                    <span class="input-icon">📁</span>
                    <input type="text" name="user_id" class="form-input" placeholder="User ID" value="{{ old('user_id') }}" required>
                </div>
                @error('user_id')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Select User</label>
                <div class="input-wrapper">
                    <span class="input-icon">▼</span>
                    <select name="user_type" class="form-select">
                        <option value="">Select User</option>
                        <option value="student" {{ old('user_type') == 'student' ? 'selected' : '' }}>Student</option>
                        <option value="lecturer" {{ old('user_type') == 'lecturer' ? 'selected' : '' }}>Lecturer</option>
                    </select>
                    <span class="select-arrow">▼</span>
                </div>
                @error('user_type')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="register-button">Register</button>

            <div class="back-link">
                <a href="{{ route('login') }}">Already have an account? Login</a>
            </div>
        </form>
    </div>
</body>
</html>
