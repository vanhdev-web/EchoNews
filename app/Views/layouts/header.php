<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    // Set default values if variables are not set
    if (!isset($setting)) {
        $setting = [
            'title' => 'Online News',
            'description' => 'Latest news and updates',
            'keywords' => 'news, online, latest',
            'icon' => '',
            'logo' => ''
        ];
    }
    if (!isset($categories)) {
        $categories = [];
    }
    if (!isset($bodyBanner)) {
        $bodyBanner = ['image' => ''];
    }
    ?>
    <!-- Mobile Specific Meta -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Favicon-->
    <link rel="shortcut icon" href="<?=asset($setting['icon'])?>">
    <!-- Meta Description -->
    <meta name="description" content="<?=$setting['description']?>">
    <!-- Meta Keyword -->
    <meta name="keywords" content="<?=$setting['keywords']?>">
    <!-- meta character set -->
    <meta charset="UTF-8">
    <!-- Site Title -->
    <title><?=$setting['title']?></title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        :root {
            /* Minimalistic Color Palette */
            --primary: #1a1a1a;           /* Almost black for text */
            --secondary: #666666;         /* Medium gray for secondary text */
            --accent: #f5f5f5;            /* Very light gray for backgrounds */
            --border: #e5e5e5;            /* Light border color */
            --white: #ffffff;             /* Pure white */
            --subtle: #fafafa;            /* Subtle background */
            
            /* Bootstrap overrides */
            --bs-primary: #1a1a1a;
            --bs-secondary: #666666;
            --bs-light: #f5f5f5;
            --bs-dark: #1a1a1a;
            --bs-gray-100: #fafafa;
            --bs-gray-200: #e5e5e5;
            --bs-gray-300: #d4d4d4;
            --bs-gray-600: #666666;
            --bs-gray-900: #1a1a1a;
            
            /* Text Colors */
            --text-primary: #1a1a1a;
            --text-secondary: #666666;
            --text-muted: #999999;
            --text-white: #ffffff;
            
            /* Background Colors */
            --bg-white: #ffffff;
            --bg-light: #fafafa;
            --bg-subtle: #f5f5f5;
            
            /* Border Colors */
            --border-light: #e5e5e5;
            --border-medium: #d4d4d4;
        }
        
        /* Base Typography - Minimalistic */
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-size: 16px;
            line-height: 1.6;
            color: var(--text-primary);
            background-color: var(--bg-white);
            font-weight: 400;
        }
        
        /* Clean headings */
        h1, h2, h3, h4, h5, h6 {
            font-weight: 600;
            color: var(--text-primary);
            line-height: 1.3;
            margin-bottom: 0.75rem;
        }
        
        /* Minimal navigation */
        .navbar {
            background-color: var(--bg-white) !important;
            border-bottom: 1px solid var(--border-light);
            padding: 1rem 0;
            box-shadow: none;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--text-primary) !important;
        }
        
        .nav-link {
            color: var(--text-secondary) !important;
            font-weight: 500;
            transition: color 0.2s ease;
        }
        
        .nav-link:hover {
            color: var(--text-primary) !important;
        }
        
        /* Minimal buttons */
        .btn {
            border-radius: 6px;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border: none;
            transition: all 0.2s ease;
        }
        
        .btn-primary {
            background-color: var(--primary);
            color: var(--white);
        }
        
        .btn-primary:hover {
            background-color: var(--secondary);
            transform: none;
        }
        
        /* Clean cards */
        .card {
            border: 1px solid var(--border-light);
            border-radius: 8px;
            box-shadow: none;
            transition: all 0.2s ease;
        }
        
        .card:hover {
            border-color: var(--border-medium);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }
        
        /* Minimal links */
        a {
            color: var(--text-primary);
            text-decoration: none;
            transition: color 0.2s ease;
        }
        
        a:hover {
            color: var(--text-secondary);
        }
        
        /* Remove excessive animations */
        * {
            animation: none !important;
            transition: color 0.2s ease, background-color 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease !important;
        }
        
        /* Clean forms */
        .form-control {
            border: 1px solid var(--border-light);
            border-radius: 6px;
            padding: 0.6rem 0.75rem;
            font-size: 0.95rem;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(26, 26, 26, 0.1);
        }
        
        /* Clean Navigation */
        .main-navbar {
            background: var(--bg-white);
            border-bottom: 1px solid var(--border-light);
            padding: 20px 0;
            box-shadow: 0 1px 3px rgba(20, 41, 96, 0.1);
        }
        
        .navbar-brand img {
            max-height: 40px;
        }
        
        .navbar-nav .nav-link {
            font-weight: 500;
            color: var(--text-primary);
            padding: 8px 20px;
            border-radius: 6px;
            transition: all 0.3s ease;
            font-size: 15px;
        }
        
        .navbar-nav .nav-link:hover {
            color: var(--bs-secondary);
            background: var(--bs-light);
        }
        
        .navbar-nav .nav-link.active {
            color: var(--bs-primary);
            background: var(--bs-light);
        }
        
        /* Bootstrap overrides */
        .btn-primary {
            background-color: var(--bs-primary);
            border-color: var(--bs-primary);
        }
        
        .btn-primary:hover {
            background-color: var(--bs-secondary);
            border-color: var(--bs-secondary);
        }
        
        .bg-primary {
            background-color: var(--bs-primary) !important;
        }
        
        .bg-secondary {
            background-color: var(--bs-secondary) !important;
        }
        
        .text-primary {
            color: var(--bs-primary) !important;
        }
        
        /* Minimalistic Search */
        .search-container {
            position: relative;
        }
        
        .search-form {
            position: relative;
            display: flex;
            align-items: center;
        }
        
        .search-form input {
            border: 1px solid var(--border-light);
            border-radius: 8px;
            padding: 10px 16px 10px 40px;
            width: 280px;
            font-size: 14px;
            background: var(--bg-light);
            transition: all 0.2s ease;
        }
        
        .search-form input:focus {
            border-color: var(--bs-secondary);
            background: var(--bg-white);
            box-shadow: 0 0 0 3px rgba(86, 86, 86, 0.1);
            outline: none;
        }
        
        .search-btn {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-muted);
            padding: 0;
            z-index: 10;
        }

        /* Search Results Dropdown */
        .search-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            z-index: 1000;
            max-height: 400px;
            overflow-y: auto;
            margin-top: 5px;
            border: 1px solid #e0e0e0;
        }

        .search-result-item {
            padding: 12px 15px;
            border-bottom: 1px solid #f0f0f0;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .search-result-item:hover {
            background-color: #f8f9fa;
        }

        .search-result-item:last-child {
            border-bottom: none;
        }

        .search-result-title {
            font-weight: 600;
            color: #333;
            margin-bottom: 3px;
            font-size: 14px;
        }

        .search-result-summary {
            color: #666;
            font-size: 12px;
            line-height: 1.4;
            margin-bottom: 5px;
        }

        .search-result-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .search-result-category {
            background: #007bff;
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 500;
        }

        .search-result-views {
            color: #999;
            font-size: 11px;
        }

        .no-results {
            padding: 20px;
            text-align: center;
            color: #666;
            font-style: italic;
        }
        
        /* Minimal Banner */
        .banner-section {
            background: var(--bg-light);
            padding: 1rem 0;
            border-bottom: 1px solid var(--border-light);
        }
        
        .banner-img {
            border-radius: 8px;
            max-height: 80px;
            object-fit: cover;
            border: 1px solid var(--border-light);
        }
        
        /* Mobile Responsive */
        @media (max-width: 768px) {
            .search-form input {
                width: 100%;
                margin-top: 16px;
            }
            
            .top-bar {
                text-align: center;
            }
            
            .top-bar .col-md-6:last-child {
                margin-top: 8px;
            }
        }
    </style>
    
    <!-- Search JavaScript -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const searchResults = document.getElementById('searchResults');
        let searchTimeout;
        
        // Debounced search function
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const searchTerm = this.value.trim();
            
            if (searchTerm.length < 2) {
                searchResults.style.display = 'none';
                return;
            }
            
            searchTimeout = setTimeout(() => {
                performLiveSearch(searchTerm);
            }, 300);
        });
        
        // Hide results when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.search-container')) {
                searchResults.style.display = 'none';
            }
        });
        
        // Show results when focusing on search input
        searchInput.addEventListener('focus', function() {
            if (this.value.length >= 2) {
                searchResults.style.display = 'block';
            }
        });
        
        function performLiveSearch(searchTerm) {
            // Create AJAX request
            fetch(`<?= url('/') ?>?search=${encodeURIComponent(searchTerm)}&ajax=1`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                displaySearchResults(data);
            })
            .catch(error => {
                console.error('Search error:', error);
            });
        }
        
        function displaySearchResults(posts) {
            if (posts.length === 0) {
                searchResults.innerHTML = '<div class="no-results">No articles found</div>';
            } else {
                let html = '';
                posts.slice(0, 5).forEach(post => { // Show max 5 results
                    html += `
                        <div class="search-result-item" onclick="goToPost(${post.id})">
                            <div class="search-result-title">${post.title}</div>
                            <div class="search-result-summary">${post.summary || post.body.substring(0, 100) + '...'}</div>
                            <div class="search-result-meta">
                                <span class="search-result-category">${post.category}</span>
                                <span class="search-result-views">${post.view} views</span>
                            </div>
                        </div>
                    `;
                });
                searchResults.innerHTML = html;
            }
            searchResults.style.display = 'block';
        }
        
        // Navigate to post
        function goToPost(postId) {
            window.location.href = `<?= url('/post') ?>/${postId}`;
        }
        
        // Make goToPost globally accessible
        window.goToPost = goToPost;
    });
    </script>
</head>

<body>
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <span class="text-muted me-4"><?= date('F j, Y') ?></span>
                        <span class="text-muted small">Latest News</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex justify-content-end align-items-center">
                        <a href="<?= url('login') ?>" class="btn btn-outline-primary btn-sm me-2">
                            <i class="fas fa-sign-in-alt me-1"></i>Sign In
                        </a>
                        <a href="<?= url('register') ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-user-plus me-1"></i>Register
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Navigation -->
    <nav class="navbar navbar-expand-lg main-navbar">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand" href="<?= url('/') ?>">
                <?php if(!empty($setting['logo'])) { ?>
                    <img src="<?=asset($setting['logo'])?>" alt="<?=$setting['title']?>" class="img-fluid">
                <?php } else { ?>
                    <span class="fw-bold"><?=$setting['title']?></span>
                <?php } ?>
            </a>

            <!-- Mobile Menu Toggle -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navigation Menu -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= url('/') ?>">
                            Home
                        </a>
                    </li>
                    <?php if(!empty($categories)) { ?>
                        <?php foreach ($categories as $category) { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= url('show-category/' . $category['id']) ?>">
                                <?= $category['name'] ?>
                            </a>
                        </li>
                        <?php } ?>
                    <?php } ?>
                </ul>

                <!-- Search Form -->
                <div class="search-container" style="position: relative;">
                    <form class="search-form" method="GET" action="<?= url('/') ?>" id="searchForm">
                        <button type="submit" class="search-btn">
                            <i class="fas fa-search"></i>
                        </button>
                        <input type="text" name="search" id="searchInput" class="form-control" placeholder="Search articles..." autocomplete="off" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                    </form>
                    <!-- Search Results Dropdown -->
                    <div id="searchResults" class="search-results" style="display: none;"></div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Minimal Banner Section -->
    <?php if(!empty($bodyBanner['image'])) { ?>
    <section class="banner-section">
        <div class="container">
            <div class="text-center">
                <img src="<?=asset($bodyBanner['image'])?>" alt="Banner" class="img-fluid banner-img">
            </div>
        </div>
    </section>
    <?php } ?>
