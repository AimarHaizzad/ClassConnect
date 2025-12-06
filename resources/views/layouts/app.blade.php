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
            position: relative;
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

        .user-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            margin-top: 10px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            min-width: 150px;
            display: none;
            z-index: 1000;
        }

        .user-dropdown.show {
            display: block;
        }

        .user-dropdown-item {
            padding: 12px 20px;
            color: #333;
            text-decoration: none;
            display: block;
            transition: background 0.2s;
        }

        .user-dropdown-item:hover {
            background: #f0f0f0;
        }

        .user-dropdown-item.logout {
            color: #dc3545;
            border-top: 1px solid #eee;
        }

        .user-dropdown-item.logout:hover {
            background: #fee;
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
        @auth
        <div class="user-info" onclick="toggleUserDropdown(event)" style="cursor: pointer;">
            <div class="user-details">
                <div class="user-name">{{ auth()->user()->name }}</div>
                <div class="user-role">
                    {{ ucfirst(auth()->user()->user_type) }}
                    <span>▼</span>
                </div>
            </div>
            <div class="user-avatar">
                @php
                    $name = auth()->user()->name;
                    $parts = explode(' ', $name);
                    $initials = strtoupper(substr($parts[0], 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : substr($parts[0], 1, 1)));
                @endphp
                {{ $initials }}
            </div>
            <div class="user-dropdown" id="userDropdown">
                <a href="{{ route('profiles.index') }}" class="user-dropdown-item">Profile</a>
                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                    @csrf
                    <button type="submit" class="user-dropdown-item logout" style="width: 100%; text-align: left; border: none; background: none; cursor: pointer; padding: 12px 20px;">
                        Logout
                    </button>
                </form>
            </div>
        </div>
        @endauth
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
        function toggleUserDropdown(event) {
            event.stopPropagation();
            const dropdown = document.getElementById('userDropdown');
            dropdown.classList.toggle('show');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const userInfo = document.querySelector('.user-info');
            const dropdown = document.getElementById('userDropdown');
            if (userInfo && dropdown && !userInfo.contains(event.target)) {
                dropdown.classList.remove('show');
            }
        });

        function toggleProfileMenu(event) {
            event.preventDefault();
            const menuItem = event.currentTarget;
            menuItem.classList.toggle('expanded');
            const submenu = menuItem.nextElementSibling;
            if (submenu && submenu.classList.contains('nav-submenu')) {
                submenu.style.display = submenu.style.display === 'none' ? 'block' : 'none';
            }
        }

        // Session Management: Keep session alive during rapid navigation
        // This ensures sessions remain active for at least 30 minutes of activity
        @auth
        (function() {
            let lastActivityTime = Date.now();
            let sessionRefreshInterval = null;
            const SESSION_REFRESH_INTERVAL = 5 * 60 * 1000; // Refresh every 5 minutes
            const ACTIVITY_THRESHOLD = 30 * 1000; // Consider user active if activity within 30 seconds

            // Track user activity
            const activityEvents = ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart', 'click'];
            activityEvents.forEach(event => {
                document.addEventListener(event, function() {
                    lastActivityTime = Date.now();
                }, { passive: true });
            });

            // Refresh session periodically if user is active
            function refreshSessionIfActive() {
                const timeSinceLastActivity = Date.now() - lastActivityTime;

                // Only refresh if user has been active recently (within 30 seconds)
                if (timeSinceLastActivity < ACTIVITY_THRESHOLD) {
                    // Make a lightweight request to refresh session
                    fetch('{{ route("session.keep-alive") }}', {
                        method: 'GET',
                        credentials: 'same-origin',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                        }
                    }).catch(function(error) {
                        // Silently fail - session refresh is best effort
                        console.debug('Session refresh failed:', error);
                    });
                }
            }

            // Start periodic session refresh
            sessionRefreshInterval = setInterval(refreshSessionIfActive, SESSION_REFRESH_INTERVAL);

            // Refresh session on page navigation (beforeunload)
            window.addEventListener('beforeunload', function() {
                // Quick session touch before navigation
                navigator.sendBeacon && navigator.sendBeacon('{{ route("session.keep-alive") }}');
            });

            // Refresh session on page visibility change (user switches tabs)
            document.addEventListener('visibilitychange', function() {
                if (!document.hidden && Date.now() - lastActivityTime < ACTIVITY_THRESHOLD) {
                    refreshSessionIfActive();
                }
            });

            // Cleanup on page unload
            window.addEventListener('unload', function() {
                if (sessionRefreshInterval) {
                    clearInterval(sessionRefreshInterval);
                }
            });
        })();
        @endauth
    </script>
</body>
</html>

