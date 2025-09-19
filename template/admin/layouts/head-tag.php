<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Panel - Online News</title>
    <link rel="shortcut icon" href="" type="image/x-icon" />

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Minimalistic Admin Styles -->
    <style>
        :root {
            --sidebar-width: 260px;
            --primary: #1a1a1a;
            --secondary: #666666;
            --accent: #f5f5f5;
            --white: #ffffff;
            --text-primary: #1a1a1a;
            --text-secondary: #666666;
            --text-muted: #999999;
            --bg-white: #ffffff;
            --bg-light: #fafafa;
            --border-light: #e5e5e5;
            --border-medium: #d4d4d4;
            --success: #22c55e;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: var(--bg-light);
            color: var(--text-primary);
            line-height: 1.6;
        }

        /* Minimalistic Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--bg-white);
            border-right: 1px solid var(--border-light);
            z-index: 1000;
            transition: transform 0.3s ease;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }

        /* Custom Scrollbar */
        .sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: var(--border-medium);
            border-radius: 2px;
        }

        /* Brand Header */
        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-light);
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: var(--primary);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: white;
        }

        .brand-info {
            flex: 1;
        }

        .brand-title {
            color: var(--text-primary);
            font-weight: 600;
            font-size: 1.1rem;
            margin: 0;
        }

        .brand-subtitle {
            color: var(--text-muted);
            font-size: 0.8rem;
            font-weight: 400;
        }

        /* Profile Section */
        .sidebar-profile {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border-light);
        }

        .profile-card {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem;
            background: var(--bg-light);
            border-radius: 8px;
            border: 1px solid var(--border-light);
        }

        .profile-avatar {
            font-size: 1.5rem;
            color: var(--primary);
        }

        .profile-info {
            flex: 1;
        }

        .profile-name {
            color: var(--text-primary);
            font-weight: 500;
            margin: 0;
            font-size: 0.9rem;
        }

        .profile-role {
            color: var(--text-muted);
            font-size: 0.75rem;
            display: block;
        }

        .profile-status {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            margin-top: 0.25rem;
        }

        .status-indicator {
            width: 6px;
            height: 6px;
            background: var(--success);
            border-radius: 50%;
        }

        .profile-status small {
            color: var(--success);
            font-weight: 500;
            font-size: 0.7rem;
        }

        /* Navigation */
        .sidebar-nav {
            padding: 1rem 0;
            flex: 1;
        }

        .nav-section {
            margin-bottom: 1.5rem;
        }

        .nav-section-title {
            padding: 0 1.5rem 0.5rem;
        }

        .nav-section-title span {
            color: var(--text-muted);
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .nav-item {
            margin: 0.25rem 1rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: var(--text-muted);
            text-decoration: none;
            border-radius: 6px;
            transition: all 0.2s ease;
            font-weight: 400;
        }

        .nav-link:hover {
            background: var(--bg-light);
            color: var(--primary);
        }

        .nav-link.active {
            background: var(--bg-light);
            color: var(--primary);
            font-weight: 500;
            border-left: 3px solid var(--primary);
        }

        .nav-icon {
            width: 18px;
            text-align: center;
            margin-right: 0.75rem;
            font-size: 0.9rem;
        }

        .nav-text {
            flex: 1;
            font-size: 0.85rem;
        }

        .nav-badge .badge {
            font-size: 0.65rem;
            padding: 0.2rem 0.4rem;
            border-radius: 4px;
        }

        .nav-counter {
            margin-left: auto;
        }

        .counter-badge {
            background: var(--border-color);
            color: var(--text-muted);
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
            font-size: 0.7rem;
            font-weight: 500;
        }

        .nav-notification {
            margin-left: auto;
        }

        .notification-dot {
            width: 6px;
            height: 6px;
            background: #ef4444;
            border-radius: 50%;
            display: block;
        }

        /* Sidebar Footer */
        .sidebar-footer {
            margin-top: auto;
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--border-color);
        }

        .footer-actions {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
            margin-bottom: 1rem;
        }

        .footer-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 0.75rem;
            color: var(--text-muted);
            text-decoration: none;
            border-radius: 4px;
            transition: all 0.2s ease;
            font-size: 0.8rem;
        }

        .footer-link:hover {
            background: var(--bg-light);
            color: var(--primary);
        }

        .logout-link:hover {
            color: var(--danger);
        }

        .footer-info {
            text-align: center;
            padding-top: 0.75rem;
            border-top: 1px solid var(--border-light);
        }

        .footer-info small {
            color: var(--text-muted);
            font-size: 0.7rem;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            padding: 2rem;
            transition: margin-left 0.3s ease;
        }

        /* Top Navigation */
        .top-navbar {
            background: var(--bg-white);
            border-bottom: 1px solid var(--border-light);
            padding: 1rem 2rem;
            margin: -2rem -2rem 2rem -2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .top-navbar {
                margin-left: -2rem;
                margin-right: -2rem;
            }

            #sidebarToggle {
                display: block !important;
            }
        }

        /* Card Enhancements */
        .card {
            border: 1px solid var(--border-light);
            box-shadow: 0 1px 3px rgba(26, 26, 26, 0.05);
            transition: all 0.2s ease;
            border-radius: 8px;
        }

        .card-hover:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(26, 26, 26, 0.1);
        }

        /* Table Enhancements */
        .table thead th {
            background: var(--bg-light);
            border: none;
            color: var(--text-muted);
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table tbody tr {
            transition: background-color 0.2s ease;
        }

        .table tbody tr:hover {
            background-color: var(--bg-light);
        }

        /* Form Enhancements */
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(26, 26, 26, 0.1);
        }

        .btn-primary {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background: var(--primary-light);
            border-color: var(--primary-light);
        }

        /* Modern Admin Enhancements */
        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
        }

        .bg-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        }

        .bg-gradient.bg-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        }

        .bg-gradient.bg-success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%) !important;
        }

        .bg-gradient.bg-warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%) !important;
        }

        .bg-gradient.bg-info {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%) !important;
        }

        /* Enhanced Sidebar */
        .nav-link {
            padding: 0.75rem 1.5rem;
            margin: 0.25rem 0;
            border-radius: 8px;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-link:hover {
            background: #f8fafc;
            color: #667eea;
            transform: translateX(5px);
        }

        .nav-link.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: #667eea;
            border-radius: 0 2px 2px 0;
        }

        /* Top Navbar Improvements */
        .top-navbar {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.05) 100%);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(102, 126, 234, 0.1);
        }

        /* Card Enhancements */
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        /* Button Improvements */
        .btn {
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }

        /* Badge Enhancements */
        .badge {
            font-weight: 500;
            padding: 0.5rem 0.75rem;
        }

        /* Table Improvements */
        .table {
            border-radius: 10px;
            overflow: hidden;
        }

        .table thead th {
            background: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.875rem;
            letter-spacing: 0.5px;
        }

        .table tbody tr:hover {
            background: #f8fafc;
            transform: scale(1.01);
            transition: all 0.2s ease;
        }

        /* Animation Classes */
        .animate-fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-slide-in {
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from { transform: translateX(-20px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        /* Loading States */
        .loading {
            position: relative;
            overflow: hidden;
        }

        .loading::after {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .top-navbar {
                padding: 1rem;
            }
            
            .card-body {
                padding: 1rem;
            }
        }

        /* Custom Scrollbar cho toàn bộ trang */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* Text Colors */
        .text-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
    </style>
</head>

<body>

    <!-- Modern Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Header with Brand -->
        <div class="sidebar-header">
            <div class="brand-logo">
                <div class="logo-icon">
                    <i class="fas fa-newspaper"></i>
                </div>
                <div class="brand-info">
                    <h5 class="brand-title mb-0">NewsAdmin</h5>
                    <small class="brand-subtitle">Management Panel</small>
                </div>
            </div>
        </div>

        <!-- User Profile Section -->
        <div class="sidebar-profile">
            <div class="profile-card">
                <div class="profile-avatar">
                    <i class="fas fa-user-circle"></i>
                </div>
                <div class="profile-info">
                    <h6 class="profile-name">Administrator</h6>
                    <span class="profile-role">Super Admin</span>
                    <div class="profile-status">
                        <span class="status-indicator"></span>
                        <small>Online</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="sidebar-nav">
            <!-- Main Section -->
            <div class="nav-section">
                <div class="nav-section-title">
                    <span>MAIN</span>
                </div>
                <div class="nav-item">
                    <a href="<?= url('admin') ?>" class="nav-link">
                        <div class="nav-icon">
                            <i class="fas fa-tachometer-alt"></i>
                        </div>
                        <span class="nav-text">Dashboard</span>
                        <div class="nav-badge">
                            <span class="badge bg-primary">New</span>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Content Management -->
            <div class="nav-section">
                <div class="nav-section-title">
                    <span>CONTENT</span>
                </div>
                <div class="nav-item">
                    <a href="<?= url('admin/post') ?>" class="nav-link">
                        <div class="nav-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <span class="nav-text">Articles</span>
                        <div class="nav-counter">
                            <small class="counter-badge">24</small>
                        </div>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="<?= url('admin/category') ?>" class="nav-link">
                        <div class="nav-icon">
                            <i class="fas fa-folder-open"></i>
                        </div>
                        <span class="nav-text">Categories</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="<?= url('admin/comment') ?>" class="nav-link">
                        <div class="nav-icon">
                            <i class="fas fa-comments"></i>
                        </div>
                        <span class="nav-text">Comments</span>
                        <div class="nav-notification">
                            <span class="notification-dot"></span>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Media & Design -->
            <div class="nav-section">
                <div class="nav-section-title">
                    <span>MEDIA</span>
                </div>
                <div class="nav-item">
                    <a href="<?= url('admin/banner') ?>" class="nav-link">
                        <div class="nav-icon">
                            <i class="fas fa-images"></i>
                        </div>
                        <span class="nav-text">Banners</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="<?= url('admin/menu') ?>" class="nav-link">
                        <div class="nav-icon">
                            <i class="fas fa-bars"></i>
                        </div>
                        <span class="nav-text">Navigation</span>
                    </a>
                </div>
            </div>

            <!-- User Management -->
            <div class="nav-section">
                <div class="nav-section-title">
                    <span>USERS</span>
                </div>
                <div class="nav-item">
                    <a href="<?= url('admin/user') ?>" class="nav-link">
                        <div class="nav-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <span class="nav-text">Manage Users</span>
                    </a>
                </div>
            </div>

            <!-- System -->
            <div class="nav-section">
                <div class="nav-section-title">
                    <span>SYSTEM</span>
                </div>
                <div class="nav-item">
                    <a href="<?= url('admin/web-setting') ?>" class="nav-link">
                        <div class="nav-icon">
                            <i class="fas fa-cog"></i>
                        </div>
                        <span class="nav-text">Settings</span>
                    </a>
                </div>
            </div>
        </nav>

        <!-- Sidebar Footer -->
        <div class="sidebar-footer">
            <div class="footer-actions">
                <a href="<?= url('/') ?>" class="footer-link" target="_blank" title="View Website">
                    <i class="fas fa-external-link-alt"></i>
                    <span>View Site</span>
                </a>
                <a href="<?= url('logout') ?>" class="footer-link logout-link" title="Logout">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </div>
            <div class="footer-info">
                <small>&copy; 2025 NewsAdmin</small>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navigation -->
        <nav class="top-navbar">
            <div class="d-flex align-items-center">
                <button class="btn btn-link d-md-none me-3" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <h5 class="mb-0 text-dark">Content Management System</h5>
            </div>
            <div class="d-flex align-items-center">
                <div class="dropdown">
                    <div class="user-avatar" data-bs-toggle="dropdown" style="cursor: pointer; padding: 0.5rem; border-radius: 50%; background: #f8fafc;">
                        <i class="fas fa-user text-primary"></i>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profile</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?= url('logout') ?>"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Enhanced JavaScript -->
        <script>
            // DOM Ready
            document.addEventListener('DOMContentLoaded', function() {
                // Add animation classes to cards
                const cards = document.querySelectorAll('.card');
                cards.forEach((card, index) => {
                    setTimeout(() => {
                        card.classList.add('animate-fade-in');
                    }, index * 100);
                });

                // Enhanced hover effects for statistics cards
                const statCards = document.querySelectorAll('.card-hover');
                statCards.forEach(card => {
                    card.addEventListener('mouseenter', function() {
                        this.style.transform = 'translateY(-8px) scale(1.02)';
                        this.style.boxShadow = '0 15px 35px rgba(0,0,0,0.2)';
                    });
                    
                    card.addEventListener('mouseleave', function() {
                        this.style.transform = 'translateY(0) scale(1)';
                        this.style.boxShadow = '0 5px 15px rgba(0,0,0,0.08)';
                    });
                });

                // Mobile sidebar toggle
                const sidebarToggle = document.getElementById('sidebarToggle');
                const sidebar = document.querySelector('.sidebar');
                
                if (sidebarToggle && sidebar) {
                    sidebarToggle.addEventListener('click', function() {
                        sidebar.classList.toggle('show');
                    });
                }

                // Initialize tooltips
                const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            });

            // Global notification function
            function showNotification(message, type = 'info') {
                const notification = document.createElement('div');
                notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
                notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
                notification.innerHTML = `
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                document.body.appendChild(notification);
                
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.remove();
                    }
                }, 5000);
            }
        </script>