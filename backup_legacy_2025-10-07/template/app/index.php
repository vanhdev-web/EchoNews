<?php 
    require_once(BASE_PATH . '/template/app/layouts/header.php');

    // Function to get icon based on category name
    function getCategoryIcon($categoryName) {
        $categoryName = strtolower($categoryName);
        
        if (strpos($categoryName, 'technology') !== false || strpos($categoryName, 'tech') !== false) {
            return 'fas fa-microchip';
        } elseif (strpos($categoryName, 'business') !== false || strpos($categoryName, 'finance') !== false) {
            return 'fas fa-chart-line';
        } elseif (strpos($categoryName, 'sport') !== false || strpos($categoryName, 'sports') !== false) {
            return 'fas fa-futbol';
        } elseif (strpos($categoryName, 'science') !== false || strpos($categoryName, 'research') !== false) {
            return 'fas fa-flask';
        } elseif (strpos($categoryName, 'health') !== false || strpos($categoryName, 'medical') !== false) {
            return 'fas fa-heartbeat';
        } elseif (strpos($categoryName, 'entertainment') !== false || strpos($categoryName, 'movie') !== false) {
            return 'fas fa-film';
        } elseif (strpos($categoryName, 'politics') !== false || strpos($categoryName, 'government') !== false) {
            return 'fas fa-landmark';
        } elseif (strpos($categoryName, 'travel') !== false || strpos($categoryName, 'tourism') !== false) {
            return 'fas fa-plane';
        } elseif (strpos($categoryName, 'food') !== false || strpos($categoryName, 'cooking') !== false) {
            return 'fas fa-utensils';
        } elseif (strpos($categoryName, 'education') !== false || strpos($categoryName, 'learning') !== false) {
            return 'fas fa-graduation-cap';
        }
        
        return 'fas fa-tag'; // default for homepage
    }

    // Function to get category color
    function getCategoryColor($categoryName) {
        $categoryName = strtolower($categoryName);
        
        if (strpos($categoryName, 'technology') !== false || strpos($categoryName, 'tech') !== false) {
            return '#667eea';
        } elseif (strpos($categoryName, 'business') !== false || strpos($categoryName, 'finance') !== false) {
            return '#fa709a';
        } elseif (strpos($categoryName, 'sport') !== false || strpos($categoryName, 'sports') !== false) {
            return '#f093fb';
        } elseif (strpos($categoryName, 'science') !== false || strpos($categoryName, 'research') !== false) {
            return '#4facfe';
        } elseif (strpos($categoryName, 'health') !== false || strpos($categoryName, 'medical') !== false) {
            return '#43e97b';
        } elseif (strpos($categoryName, 'entertainment') !== false || strpos($categoryName, 'movie') !== false) {
            return '#fa8072';
        } elseif (strpos($categoryName, 'politics') !== false || strpos($categoryName, 'government') !== false) {
            return '#a8edea';
        } elseif (strpos($categoryName, 'travel') !== false || strpos($categoryName, 'tourism') !== false) {
            return '#ffecd2';
        } elseif (strpos($categoryName, 'food') !== false || strpos($categoryName, 'cooking') !== false) {
            return '#fcb69f';
        } elseif (strpos($categoryName, 'education') !== false || strpos($categoryName, 'learning') !== false) {
            return '#89f7fe';
        }
        
        return '#667eea'; // default color
    }
?>

<style>
    /* Modern Newspaper Layout - Inspired by Screenshots */
    :root {
        --newspaper-navy: #1e3a8a;
        --newspaper-light-blue: #3b82f6;
        --newspaper-dark: #1f2937;
        --newspaper-gray: #6b7280;
        --newspaper-light-gray: #f3f4f6;
        --newspaper-white: #ffffff;
        --newspaper-border: #e5e7eb;
        --newspaper-accent: #dc2626;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: var(--newspaper-dark);
        background-color: var(--newspaper-white);
        line-height: 1.6;
    }

    /* Hero Section with Grid Layout */
    .newspaper-hero {
        background: var(--newspaper-white);
        padding: 2rem 0 1rem 0;
        margin-bottom: 1.5rem;
    }

    .hero-main-post {
        position: relative;
        height: 395px;
        overflow: hidden;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .hero-main-post img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .hero-main-post:hover img {
        transform: scale(1.05);
    }

    .hero-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(transparent, rgba(0,0,0,0.8));
        color: white;
        padding: 2rem;
    }

    .hero-category {
        display: inline-block;
        background: var(--newspaper-light-blue);
        color: white;
        padding: 0.3rem 0.8rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 1rem;
    }

    .hero-title {
        font-size: 1.8rem;
        font-weight: 700;
        line-height: 1.3;
        margin-bottom: 0.5rem;
    }

    .hero-title a {
        color: white;
        text-decoration: none;
    }

    .hero-meta {
        font-size: 0.875rem;
        opacity: 0.9;
    }

    .hero-meta span {
        margin-right: 1rem;
    }

    /* Search Results Styles */
    .search-results-section {
        background: #f8f9fa;
        min-height: 60vh;
    }

    .search-header h2 {
        font-weight: 700;
        color: #2c3e50;
    }

    .search-result-card {
        transition: all 0.3s ease;
        height: 200px;
    }

    .search-result-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
    }

    .search-result-card .card-title a:hover {
        color: var(--bs-primary) !important;
    }

    .no-results {
        background: white;
        border-radius: 10px;
        padding: 3rem;
    }

    .search-result-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    @media (max-width: 768px) {
        .search-result-card {
            height: auto;
        }
        .search-result-card img {
            height: 200px;
        }
    }

    .hero-side-post {
        position: relative;
        height: 190px;
        overflow: hidden;
        border-radius: 8px;
        margin-bottom: 1rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .hero-side-post img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .hero-side-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(transparent, rgba(0,0,0,0.7));
        color: white;
        padding: 1rem;
    }

    .hero-side-category {
        display: inline-block;
        background: var(--newspaper-accent);
        color: white;
        padding: 0.2rem 0.6rem;
        border-radius: 3px;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 0.5rem;
    }

    .hero-side-title {
        font-size: 0.95rem;
        font-weight: 600;
        line-height: 1.3;
        margin: 0;
    }

    .hero-side-title a {
        color: white;
        text-decoration: none;
    }

    /* Section Headers */
    .section-header {
        border-left: 4px solid var(--newspaper-navy);
        padding-left: 1rem;
        margin-bottom: 2rem;
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--newspaper-navy);
        text-transform: uppercase;
        letter-spacing: 1px;
        margin: 0;
    }

    /* News Cards */
    .news-card {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        height: 100%;
    }

    .news-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .news-card-image {
        position: relative;
        height: 200px;
        overflow: hidden;
    }

    .news-card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .news-card:hover .news-card-image img {
        transform: scale(1.1);
    }

    .news-card-category {
        position: absolute;
        top: 1rem;
        left: 1rem;
        background: var(--newspaper-light-blue);
        color: white;
        padding: 0.3rem 0.7rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .news-card-content {
        padding: 1.5rem;
    }

    .news-card-title {
        font-size: 1.1rem;
        font-weight: 600;
        line-height: 1.4;
        margin-bottom: 0.8rem;
        color: var(--newspaper-dark);
    }

    .news-card-title a {
        color: inherit;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .news-card-title a:hover {
        color: var(--newspaper-light-blue);
    }

    .news-card-meta {
        font-size: 0.85rem;
        color: var(--newspaper-gray);
        margin-bottom: 0.8rem;
    }

    .news-card-meta span {
        margin-right: 1rem;
    }

    .news-card-excerpt {
        color: var(--newspaper-gray);
        font-size: 0.9rem;
        line-height: 1.6;
    }

    /* Category Cards Special Styling */
    .category-icon-display {
        background: linear-gradient(135deg, var(--newspaper-navy), var(--newspaper-light-blue));
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .category-icon-display .text-center {
        z-index: 2;
    }

    .category-icon-display::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
        opacity: 0.3;
    }

    /* Popular Posts Special Badge */
    .popular-badge {
        background: linear-gradient(45deg, var(--newspaper-accent), #ff6b6b) !important;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.7); }
        70% { box-shadow: 0 0 0 10px rgba(220, 38, 38, 0); }
        100% { box-shadow: 0 0 0 0 rgba(220, 38, 38, 0); }
    }

    /* Small News Cards for Most Viewed Bottom Section */
    .small-news-card {
        transition: all 0.3s ease;
        border: 1px solid var(--newspaper-border);
    }

    .small-news-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
        border-color: var(--newspaper-light-blue);
    }

    .small-news-image {
        flex-shrink: 0;
        overflow: hidden;
        border-radius: 6px;
    }

    .small-news-image img {
        transition: transform 0.3s ease;
    }

    .small-news-card:hover .small-news-image img {
        transform: scale(1.05);
    }

    .small-news-title a:hover {
        color: var(--newspaper-light-blue) !important;
    }

    .small-news-meta i {
        color: var(--newspaper-light-blue);
        margin-right: 3px;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .hero-main-post {
            height: 300px;
            margin-bottom: 1rem;
        }
        
        .hero-side-post {
            height: 150px;
        }
        
        .hero-title {
            font-size: 1.4rem;
        }
        
        .section-title {
            font-size: 1.3rem;
        }
</style>

<main>
    <?php if(isset($_GET['search']) && !empty($_GET['search'])): ?>
    <!-- Search Results Section -->
    <section class="search-results-section py-5">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="search-header mb-4">
                        <h2 class="mb-2">Search Results for: <span class="text-primary">"<?= htmlspecialchars($_GET['search']) ?>"</span></h2>
                        <p class="text-muted"><?= count($lastPosts) ?> articles found</p>
                    </div>
                </div>
            </div>
            
            <?php if(empty($lastPosts)): ?>
            <div class="row">
                <div class="col-12">
                    <div class="no-results text-center py-5">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h3 class="text-muted">No articles found</h3>
                        <p class="text-muted">Try searching with different keywords</p>
                        <a href="<?= url('/') ?>" class="btn btn-primary">Back to Homepage</a>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <div class="row g-4">
                <?php foreach($lastPosts as $post): ?>
                <div class="col-lg-6">
                    <article class="search-result-card card border-0 shadow-sm h-100">
                        <div class="row g-0 h-100">
                            <div class="col-md-4">
                                <img src="<?= asset($post['image']) ?>" alt="<?= $post['title'] ?>" class="img-fluid rounded-start h-100" style="object-fit: cover;">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body d-flex flex-column h-100">
                                    <div>
                                        <span class="badge bg-primary mb-2"><?= $post['category'] ?></span>
                                        <h5 class="card-title">
                                            <a href="<?= url('show-post/' . $post['id']) ?>" class="text-decoration-none text-dark"><?= $post['title'] ?></a>
                                        </h5>
                                        <p class="card-text text-muted small"><?= substr(strip_tags($post['body']), 0, 150) ?>...</p>
                                    </div>
                                    <div class="mt-auto">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="author-info">
                                                <small class="text-muted">
                                                    <i class="fas fa-user"></i> <?= $post['username'] ?>
                                                </small>
                                            </div>
                                            <div class="post-stats">
                                                <small class="text-muted">
                                                    <i class="fas fa-eye"></i> <?= $post['view'] ?>
                                                    <i class="fas fa-comments ms-2"></i> <?= $post['comments_count'] ?>
                                                </small>
                                            </div>
                                        </div>
                                        <small class="text-muted">
                                            <i class="fas fa-calendar"></i> <?= date('M j, Y', strtotime($post['created_at'])) ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </section>
    
    <?php else: ?>
    <!-- Hero Section - Newspaper Layout -->
    <section class="newspaper-hero">
        <div class="container">
            <div class="row g-4">
                <!-- Main Featured Post (Left) -->
                <div class="col-lg-8">
                    <?php if(!empty($lastPosts) && isset($lastPosts[0])) { ?>
                    <article class="hero-main-post">
                        <img src="<?= asset($lastPosts[0]['image']) ?>" alt="<?= $lastPosts[0]['title'] ?>">
                        <div class="hero-overlay">
                            <span class="hero-category">
                                <i class="<?= getCategoryIcon($lastPosts[0]['category']) ?>"></i> <?= $lastPosts[0]['category'] ?>
                            </span>
                            <h1 class="hero-title">
                                <a href="<?= url('show-post/' . $lastPosts[0]['id']) ?>"><?= $lastPosts[0]['title'] ?></a>
                            </h1>
                            <div class="hero-meta">
                                <span><i class="fas fa-user"></i> <?= $lastPosts[0]['username'] ?></span>
                                <span><i class="fas fa-calendar"></i> <?= date('M j, Y', strtotime($lastPosts[0]['created_at'])) ?></span>
                            </div>
                        </div>
                    </article>
                    <?php } ?>
                    
                    <!-- Bottom Secondary Post to fill the gap -->
                    <?php if(!empty($lastPosts) && isset($lastPosts[4])) { ?>
                    <article class="hero-side-post mt-3">
                        <img src="<?= asset($lastPosts[4]['image']) ?>" alt="<?= $lastPosts[4]['title'] ?>">
                        <div class="hero-side-overlay">
                            <span class="hero-side-category">
                                <?= $lastPosts[4]['category'] ?>
                            </span>
                            <h3 class="hero-side-title">
                                <a href="<?= url('show-post/' . $lastPosts[4]['id']) ?>"><?= $lastPosts[4]['title'] ?></a>
                            </h3>
                        </div>
                    </article>
                    <?php } ?>
                </div>

                <!-- Side Posts (Right) -->
                <div class="col-lg-4">
                    <?php 
                    $sidePosts = array_slice($lastPosts, 1, 3);
                    foreach($sidePosts as $index => $sidePost) { ?>
                    <article class="hero-side-post">
                        <img src="<?= asset($sidePost['image']) ?>" alt="<?= $sidePost['title'] ?>">
                        <div class="hero-side-overlay">
                            <span class="hero-side-category">
                                <?= $sidePost['category'] ?>
                            </span>
                            <h3 class="hero-side-title">
                                <a href="<?= url('show-post/' . $sidePost['id']) ?>"><?= $sidePost['title'] ?></a>
                            </h3>
                        </div>
                    </article>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Latest News Section -->
    <section class="py-5" style="background-color: #f8f9fa;">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Latest News</h2>
            </div>
            
            <div class="row g-4">
                <?php 
                $latestNews = array_slice($lastPosts, 4, 6);
                foreach($latestNews as $news) { ?>
                <div class="col-lg-4 col-md-6">
                    <article class="news-card">
                        <div class="news-card-image">
                            <img src="<?= asset($news['image']) ?>" alt="<?= $news['title'] ?>">
                            <span class="news-card-category">
                                <?= $news['category'] ?>
                            </span>
                        </div>
                        <div class="news-card-content">
                            <h3 class="news-card-title">
                                <a href="<?= url('show-post/' . $news['id']) ?>"><?= $news['title'] ?></a>
                            </h3>
                            <div class="news-card-meta">
                                <span><i class="fas fa-user"></i> <?= $news['username'] ?></span>
                                <span><i class="fas fa-calendar"></i> <?= date('M j, Y', strtotime($news['created_at'])) ?></span>
                            </div>
                            <p class="news-card-excerpt">
                                <?= isset($news['summary']) ? substr(strip_tags($news['summary']), 0, 100) : substr(strip_tags($news['body']), 0, 100) ?>...
                            </p>
                        </div>
                    </article>
                </div>
                <?php } ?>
            </div>
        </div>
    </section>

    <!-- Most Viewed Section -->
    <section class="py-5">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Most Viewed</h2>
            </div>
            
            <?php 
            // Đảm bảo có đủ dữ liệu cho Most Viewed
            $allMostViewed = [];
            
            // Kết hợp cả popularPosts và lastPosts để có đủ dữ liệu
            if (!empty($popularPosts)) {
                $allMostViewed = array_merge($allMostViewed, $popularPosts);
            }
            if (!empty($lastPosts)) {
                $allMostViewed = array_merge($allMostViewed, $lastPosts);
            }
            
            // Loại bỏ duplicate và lấy 9 bài đầu tiên
            $uniquePosts = [];
            $seenIds = [];
            foreach ($allMostViewed as $post) {
                if (!in_array($post['id'], $seenIds)) {
                    $uniquePosts[] = $post;
                    $seenIds[] = $post['id'];
                    if (count($uniquePosts) >= 9) break;
                }
            }
            
            // Lấy 3 bài view cao nhất cho top section
            $topViewed = array_slice($uniquePosts, 0, 3);
            // Lấy các bài còn lại cho bottom section (2 cột)
            $bottomViewed = array_slice($uniquePosts, 3, 6);
            ?>
            
            <!-- Top 3 Most Viewed (Large Cards) -->
            <div class="row g-4 mb-5">
                <?php foreach($topViewed as $index => $popular) { ?>
                <div class="col-lg-4 col-md-6">
                    <article class="news-card" style="min-height: 420px;">
                        <div class="news-card-image" style="height: 250px;">
                            <img src="<?= asset($popular['image']) ?>" alt="<?= $popular['title'] ?>">
                            <span class="news-card-category popular-badge">
                                <i class="fas fa-fire"></i> #<?= $index + 1 ?> Popular
                            </span>
                        </div>
                        <div class="news-card-content">
                            <h3 class="news-card-title" style="font-size: 1.1rem;">
                                <a href="<?= url('show-post/' . $popular['id']) ?>"><?= $popular['title'] ?></a>
                            </h3>
                            <div class="news-card-meta">
                                <span><i class="fas fa-user"></i> <?= $popular['username'] ?></span>
                                <span><i class="fas fa-eye"></i> <?= number_format($popular['view']) ?> views</span>
                            </div>
                            <p class="news-card-excerpt">
                                <?= isset($popular['summary']) ? substr(strip_tags($popular['summary']), 0, 120) : substr(strip_tags($popular['body']), 0, 120) ?>...
                            </p>
                        </div>
                    </article>
                </div>
                <?php } ?>
            </div>
            
            <!-- Bottom Most Viewed (Small Cards - 2 Columns) -->
            <div class="row g-3">
                <?php 
                // Nếu không có đủ bottomViewed, sử dụng thêm từ lastPosts
                if (count($bottomViewed) < 6) {
                    $additionalPosts = array_slice($lastPosts, count($topViewed) + count($bottomViewed), 6 - count($bottomViewed));
                    $bottomViewed = array_merge($bottomViewed, $additionalPosts);
                }
                
                foreach($bottomViewed as $index => $popular) { ?>
                <div class="col-lg-6 col-md-6">
                    <article class="d-flex small-news-card bg-white rounded shadow-sm p-4 h-100" style="min-height: 140px;">
                        <div class="small-news-image me-4">
                            <img src="<?= asset($popular['image']) ?>" 
                                 alt="<?= $popular['title'] ?>"
                                 class="rounded"
                                 style="width: 140px; height: 100px; object-fit: cover;">
                        </div>
                        <div class="small-news-content flex-grow-1">
                            <h5 class="small-news-title mb-3" style="font-size: 1rem; line-height: 1.4; font-weight: 600;">
                                <a href="<?= url('show-post/' . $popular['id']) ?>" 
                                   class="text-decoration-none text-dark">
                                    <?= strlen($popular['title']) > 70 ? substr($popular['title'], 0, 70) . '...' : $popular['title'] ?>
                                </a>
                            </h5>
                            <div class="small-news-meta mb-3" style="font-size: 0.85rem; color: #6b7280;">
                                <span class="me-3">
                                    <i class="fas fa-user"></i> <?= $popular['username'] ?>
                                </span>
                                <span>
                                    <i class="fas fa-eye"></i> <?= number_format($popular['view']) ?>
                                </span>
                            </div>
                            <p class="small-news-excerpt mb-0" style="font-size: 0.9rem; color: #6b7280; line-height: 1.5;">
                                <?= isset($popular['summary']) ? substr(strip_tags($popular['summary']), 0, 100) : substr(strip_tags($popular['body']), 0, 100) ?>...
                            </p>
                        </div>
                    </article>
                </div>
                <?php } ?>
            </div>
        </div>
    </section>

    <!-- Featured Categories Section -->
    <section class="py-5" style="background-color: #f8f9fa;">
        <div class="container">
            <?php 
            // Lấy tất cả categories có bài viết
            $featuredCategories = [];
            
            // Nếu có dữ liệu categories từ database
            if (!empty($categories)) {
                foreach($categories as $category) {
                    $featuredCategories[] = $category['name'];
                }
            } else {
                // Fallback: Lấy categories từ bài viết hiện có
                $uniqueCategories = array_unique(array_column($lastPosts, 'category'));
                $featuredCategories = array_slice($uniqueCategories, 0, 6); // Lấy tối đa 6 categories
            }
            
            // Nếu vẫn không có, sử dụng default
            if (empty($featuredCategories)) {
                $featuredCategories = ['Technology', 'Business', 'Sports', 'Health', 'Entertainment', 'Science'];
            }
            
            foreach($featuredCategories as $categoryName) {
                // Lọc bài viết theo category
                $categoryPosts = array_filter($lastPosts, function($post) use ($categoryName) {
                    return stripos($post['category'], $categoryName) !== false;
                });
                
                // Nếu không có bài viết của category này, bỏ qua
                if (empty($categoryPosts)) continue;
                
                // Hiển thị TẤT CẢ bài viết có trong category (không giới hạn)
                // $categoryPosts = array_slice($categoryPosts, 0, 8); // Bỏ giới hạn
            ?>
            
            <div class="section-header mb-4">
                <h2 class="section-title">
                    <i class="<?= getCategoryIcon($categoryName) ?> me-2"></i><?= $categoryName ?>
                    <span class="badge bg-secondary ms-2" style="font-size: 0.6rem;"><?= count($categoryPosts) ?> articles</span>
                </h2>
            </div>
            
            <div class="row g-4 mb-5">
                <?php foreach($categoryPosts as $post) { ?>
                <div class="col-lg-3 col-md-6">
                    <article class="news-card">
                        <div class="news-card-image">
                            <img src="<?= asset($post['image']) ?>" alt="<?= $post['title'] ?>">
                            <span class="news-card-category" style="background-color: <?= getCategoryColor($categoryName) ?>;">
                                <i class="<?= getCategoryIcon($categoryName) ?>"></i> <?= $post['category'] ?>
                            </span>
                        </div>
                        <div class="news-card-content">
                            <h3 class="news-card-title">
                                <a href="<?= url('show-post/' . $post['id']) ?>"><?= $post['title'] ?></a>
                            </h3>
                            <div class="news-card-meta">
                                <span><i class="fas fa-user"></i> <?= $post['username'] ?></span>
                                <span><i class="fas fa-calendar"></i> <?= date('M j, Y', strtotime($post['created_at'])) ?></span>
                            </div>
                            <p class="news-card-excerpt">
                                <?= isset($post['summary']) ? substr(strip_tags($post['summary']), 0, 100) : substr(strip_tags($post['body']), 0, 100) ?>...
                            </p>
                        </div>
                    </article>
                </div>
                <?php } ?>
            </div>
            
            <?php } ?>
            
            <?php 
            // Nếu không có bài viết nào từ featured categories, hiển thị fallback
            $hasAnyPosts = false;
            foreach($featuredCategories as $cat) {
                $filtered = array_filter($lastPosts, function($post) use ($cat) {
                    return stripos($post['category'], $cat) !== false;
                });
                if (!empty($filtered)) {
                    $hasAnyPosts = true;
                    break;
                }
            }
            
            if (!$hasAnyPosts) { ?>
            <!-- Fallback: Show random posts grouped by available categories -->
            <div class="section-header mb-4">
                <h2 class="section-title">
                    <i class="fas fa-newspaper me-2"></i>Featured Articles
                </h2>
            </div>
            
            <div class="row g-4">
                <?php 
                $fallbackPosts = array_slice($lastPosts, 6, 6);
                foreach($fallbackPosts as $post) { ?>
                <div class="col-lg-4 col-md-6">
                    <article class="news-card">
                        <div class="news-card-image">
                            <img src="<?= asset($post['image']) ?>" alt="<?= $post['title'] ?>">
                            <span class="news-card-category" style="background-color: <?= getCategoryColor($post['category']) ?>;">
                                <i class="<?= getCategoryIcon($post['category']) ?>"></i> <?= $post['category'] ?>
                            </span>
                        </div>
                        <div class="news-card-content">
                            <h3 class="news-card-title">
                                <a href="<?= url('show-post/' . $post['id']) ?>"><?= $post['title'] ?></a>
                            </h3>
                            <div class="news-card-meta">
                                <span><i class="fas fa-user"></i> <?= $post['username'] ?></span>
                                <span><i class="fas fa-calendar"></i> <?= date('M j, Y', strtotime($post['created_at'])) ?></span>
                            </div>
                            <p class="news-card-excerpt">
                                <?= isset($post['summary']) ? substr(strip_tags($post['summary']), 0, 100) : substr(strip_tags($post['body']), 0, 100) ?>...
                            </p>
                        </div>
                    </article>
                </div>
                <?php } ?>
            </div>
            <?php } ?>
        </div>
    </section>

    <!-- Banner Advertisement -->
    <?php if(!empty($bodyBanner)) { ?>
    <section class="py-4" style="background-color: #e5e7eb;">
        <div class="container">
            <div class="text-center">
                <img src="<?= asset($bodyBanner['image']) ?>" 
                     alt="Advertisement" 
                     class="img-fluid rounded"
                     style="max-height: 120px; width: 100%; object-fit: cover;">
            </div>
        </div>
    </section>
    <?php } ?>
</main>
<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Modern newspaper interactions
    document.addEventListener('DOMContentLoaded', function() {
        // Smooth hover effects for news cards
        const newsCards = document.querySelectorAll('.news-card');
        newsCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-8px)';
                this.style.transition = 'all 0.3s ease';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });

        // Hero post hover effect
        const heroPost = document.querySelector('.hero-main-post');
        if (heroPost) {
            heroPost.addEventListener('mouseenter', function() {
                this.querySelector('img').style.transform = 'scale(1.05)';
            });
            
            heroPost.addEventListener('mouseleave', function() {
                this.querySelector('img').style.transform = 'scale(1)';
            });
        }

        // Side posts hover effects
        const sidePosts = document.querySelectorAll('.hero-side-post');
        sidePosts.forEach(post => {
            post.addEventListener('mouseenter', function() {
                this.querySelector('img').style.transform = 'scale(1.1)';
                this.style.transform = 'translateX(5px)';
            });
            
            post.addEventListener('mouseleave', function() {
                this.querySelector('img').style.transform = 'scale(1)';
                this.style.transform = 'translateX(0)';
            });
        });
    });
</script>

<?php endif; ?>

<?php require_once "template/app/layouts/footer.php" ?>