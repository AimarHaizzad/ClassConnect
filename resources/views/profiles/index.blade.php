@extends('layouts.app')

@section('title', 'User Profile')

@section('content')
    <style>
        .profile-container {
            max-width: 1200px;
        }

        .profile-title {
            font-size: 32px;
            font-weight: bold;
            color: #333;
            margin-bottom: 30px;
        }

        .profile-content {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
        }

        .profile-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .profile-picture-card {
            flex: 0 0 300px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .profile-info-card {
            flex: 1;
            min-width: 400px;
        }

        .profile-picture {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: #E0E0E0;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            position: relative;
            overflow: hidden;
        }

        .profile-picture-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #D0D0D0;
            color: #999;
            font-size: 80px;
        }

        .change-photo-btn {
            background: #E8F5E9;
            color: #4CAF50;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: background 0.2s;
        }

        .change-photo-btn:hover {
            background: #C8E6C9;
        }

        .profile-info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .profile-info-table tr {
            border-bottom: 1px solid #F0F0F0;
        }

        .profile-info-table tr:last-child {
            border-bottom: none;
        }

        .profile-info-table td {
            padding: 15px 0;
            vertical-align: top;
        }

        .profile-info-label {
            font-weight: 600;
            color: #666;
            width: 40%;
            padding-right: 20px;
        }

        .profile-info-value {
            color: #333;
        }

        .edit-profile-btn {
            background: #4CAF50;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            float: right;
            margin-top: 20px;
            transition: background 0.2s;
        }

        .edit-profile-btn:hover {
            background: #45a049;
        }

        @media (max-width: 768px) {
            .profile-content {
                flex-direction: column;
            }

            .profile-picture-card {
                flex: 1;
            }

            .profile-info-card {
                min-width: 100%;
            }
        }
    </style>

    <div class="profile-container">
        <h1 class="profile-title">Profile</h1>
        
        <div class="profile-content">
            <!-- Profile Picture Card -->
            <div class="profile-card profile-picture-card">
                <div class="profile-picture">
                    <div class="profile-picture-placeholder">👤</div>
                </div>
                <button class="change-photo-btn" onclick="handleChangePhoto()">Change Photo</button>
            </div>

            <!-- Profile Information Card -->
            <div class="profile-card profile-info-card">
                <table class="profile-info-table">
                    <tr>
                        <td class="profile-info-label">Name</td>
                        <td class="profile-info-value">Amira Sofea binti Safuan</td>
                    </tr>
                    <tr>
                        <td class="profile-info-label">Username</td>
                        <td class="profile-info-value">Amira Sofea</td>
                    </tr>
                    <tr>
                        <td class="profile-info-label">Email</td>
                        <td class="profile-info-value">amirasofea@gmail.com</td>
                    </tr>
                    <tr>
                        <td class="profile-info-label">Phone Number</td>
                        <td class="profile-info-value">011-45567889</td>
                    </tr>
                    <tr>
                        <td class="profile-info-label">Date of birth</td>
                        <td class="profile-info-value">25/08/1999</td>
                    </tr>
                    <tr>
                        <td class="profile-info-label">User ID</td>
                        <td class="profile-info-value">TC10001</td>
                    </tr>
                </table>
                <button class="edit-profile-btn" onclick="handleEditProfile()">Edit Profile</button>
            </div>
        </div>
    </div>

    <script>
        function handleChangePhoto() {
            // Handle change photo functionality
            console.log('Change photo clicked');
            // TODO: Implement photo upload
        }

        function handleEditProfile() {
            // Handle edit profile functionality
            console.log('Edit profile clicked');
            // TODO: Navigate to edit profile page
        }
    </script>
@endsection
