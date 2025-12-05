@extends('layouts.app')

@section('title', 'Change Password')

@section('content')
    <style>
        .password-container {
            max-width: 600px;
        }

        .password-title {
            font-size: 32px;
            font-weight: bold;
            color: #333;
            margin-bottom: 30px;
        }

        .password-card {
            background: white;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #E0E0E0;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.2s;
        }

        .form-input:focus {
            outline: none;
            border-color: #4CAF50;
        }

        .form-actions {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            margin-top: 30px;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-primary {
            background: #4CAF50;
            color: white;
        }

        .btn-primary:hover {
            background: #45a049;
        }

        .btn-secondary {
            background: #E0E0E0;
            color: #333;
        }

        .btn-secondary:hover {
            background: #D0D0D0;
        }

        .error-message {
            color: #D32F2F;
            font-size: 12px;
            margin-top: 5px;
        }

        .success-message {
            background: #E8F5E9;
            color: #2E7D32;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
    </style>

    <div class="password-container">
        <h1 class="password-title">Change Password</h1>
        
        <div class="password-card">
            <p style="color: #666; text-align: center; padding: 40px 0;">Change Password functionality will be implemented here.</p>
        </div>
    </div>
@endsection

