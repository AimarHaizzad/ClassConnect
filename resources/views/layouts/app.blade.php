<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ClassConnect') - High School Learning Platform</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: #F2EFDF;
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow: hidden;
        }

        /* Top Navigation Bar */
        .top-nav {
            background: white;
            height: 70px;
            display: flex;
            align-items: center;
            padding: 0 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .hamburger {
            font-size: 24px;
            cursor: pointer;
            margin-right: 20px;
            color: #333;
        }

        .logo {
            font-family: 'Brush Script MT', cursive;
            font-size: 28px;
            font-weight: bold;
            color: #000000;
            flex: 1;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-details {
            text-align: right;
        }

        .user-name {
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }

        .user-role {
            font-size: 12px;
            color: #666;
            display: flex;
            align-items: center;
            gap: 5px;
            cursor: pointer;
        }

        .user-info {
            cursor: pointer;
            transition: opacity 0.2s;
        }

        .user-info:hover {
            opacity: 0.8;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #667eea;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }

        /* Main Container */
        .main-container {
            display: flex;
            flex: 1;
            overflow: hidden;
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            background: white;
            border-radius: 20px 20px 0 0;
            margin: 10px 0 10px 10px;
            padding: 20px 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
        }

        .nav-item {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #333;
            text-decoration: none;
            transition: background 0.2s;
            cursor: pointer;
        }

        .nav-item:hover {
            background: #f0f0f0;
        }

        .nav-item.active {
            background: #795E2E;
            color: white;
        }

        .nav-item.active.discussion {
            background: #795E2E;
            color: white;
        }

        .nav-icon {
            width: 24px;
            height: 24px;
            margin-right: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .nav-label {
            flex: 1;
        }

        .nav-arrow {
            font-size: 12px;
            color: #999;
        }

        .nav-submenu {
            padding-left: 56px;
            display: none;
        }

        .nav-item.expanded .nav-submenu {
            display: block;
        }

        .nav-submenu .nav-item {
            padding: 10px 20px;
            font-size: 14px;
        }

        .nav-submenu .nav-item.active {
            background: #795E2E;
            color: white;
        }

        .nav-item.expandable {
            position: relative;
        }

        .nav-item.expandable .nav-arrow {
            transition: transform 0.3s;
        }

        .nav-item.expandable.expanded .nav-arrow {
            transform: rotate(90deg);
        }

        /* Main Content Area */
        .content-area {
            flex: 1;
            background: #F2EFDF;
            margin: 10px 10px 10px 0;
            border-radius: 20px 20px 0 0;
            padding: 30px;
            overflow-y: auto;
        }
    </style>
</head>
<body>
    <!-- Top Navigation Bar -->
    <div class="top-nav">
        <div class="hamburger">☰</div>
        <div class="logo">ClassConnect</div>
        <div class="user-info" onclick="handleProfileClick()" style="cursor: pointer;">
            <div class="user-details">
                <div class="user-name">Amira Sofea</div>
                <div class="user-role">
                    Teacher
                    <span>▼</span>
                </div>
            </div>
            <div class="user-avatar">AS</div>
        </div>
    </div>

    <!-- Main Container -->
    <div class="main-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <a href="/dashboard" class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
                <div class="nav-icon">🖥️</div>
                <div class="nav-label">Dashboard</div>
            </a>

            <div class="nav-item expandable {{ request()->is('profiles*') || request()->is('password*') ? 'expanded' : '' }}" onclick="toggleProfileMenu(event)">
                <div class="nav-icon">👤</div>
                <div class="nav-label">Profile</div>
                <div class="nav-arrow">▶</div>
            </div>
            <div class="nav-submenu {{ request()->is('profiles*') || request()->is('password*') ? '' : '' }}" style="{{ request()->is('profiles*') || request()->is('password*') ? 'display: block;' : '' }}">
                <a href="/profiles" class="nav-item {{ request()->is('profiles') && !request()->is('profiles/*') ? 'active' : '' }}">
                    User Profile
                </a>
                <a href="/password/change" class="nav-item {{ request()->is('password*') ? 'active' : '' }}">
                    Change Password
                </a>
            </div>

            <a href="/lessons" class="nav-item {{ request()->is('lessons*') ? 'active' : '' }}">
                <div class="nav-icon">📚</div>
                <div class="nav-label">Lesson</div>
            </a>

            <a href="/assignments" class="nav-item {{ request()->is('assignments*') ? 'active' : '' }}">
                <div class="nav-icon">📁</div>
                <div class="nav-label">Assignment</div>
            </a>

            <a href="/discussions" class="nav-item {{ request()->is('discussions*') || request()->is('subjects*') ? 'active' : '' }}">
                <div class="nav-icon">💬</div>
                <div class="nav-label">Discussion</div>
            </a>
        </div>

        <!-- Main Content Area -->
        <div class="content-area">
            @yield('content')
        </div>
    </div>

    <script>
        function handleProfileClick() {
            // Profile click handler - can be extended later
            console.log('Profile clicked');
        }

        function toggleProfileMenu(event) {
            event.preventDefault();
            const menuItem = event.currentTarget;
            menuItem.classList.toggle('expanded');
            const submenu = menuItem.nextElementSibling;
            if (submenu && submenu.classList.contains('nav-submenu')) {
                submenu.style.display = submenu.style.display === 'none' ? 'block' : 'none';
            }
        }
    </script>
</body>
</html>

