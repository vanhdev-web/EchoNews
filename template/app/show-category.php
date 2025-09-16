<?php

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
    } else {
        return 'fas fa-newspaper';
    }
}

require_once(BASE_PATH . '/template/app/layouts/header.php'); 
?>

<style>
:root {
    --bg-light: #f8f9fa;
    --border-light: #e9ecef;
    --text-primary: #495057;
    --shadow-light: 0 2px 10px rgba(0,0,0,0.1);
}

.posts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 280px));
    gap: 1.5rem;
    margin-top: 2rem;
    justify-content: start;
}

.post-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: var(--shadow-light);
    transition: all 0.3s ease;
    border: 1px solid var(--border-light);
    height: 100%;
    display: flex;
    flex-direction: column;
}

.post-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.1);
}

.post-image {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.post-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.post-card:hover .post-image img {
    transform: scale(1.03);
}

.post-content {
    padding: 1.5rem;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.post-title {
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 0.8rem;
    line-height: 1.4;
    flex: 1;
}

.post-title a {
    color: var(--text-primary);
    text-decoration: none;
    transition: color 0.3s ease;
}

.post-title a:hover {
    color: #0d6efd;
}

.post-excerpt {
    color: #6c757d;
    line-height: 1.5;
    margin-bottom: 1rem;
    font-size: 0.9rem;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.post-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 0.8rem;
    border-top: 1px solid var(--border-light);
    font-size: 0.8rem;
    color: #6c757d;
    margin-top: auto;
}

.post-author {
    font-weight: 500;
    font-size: 0.8rem;
}

.post-date, .post-comments {
    font-size: 0.75rem;
}

.no-posts {
    text-align: center;
    padding: 100px 20px;
    color: #6c757d;
}

.no-posts i {
    font-size: 5rem;
    margin-bottom: 2rem;
    opacity: 0.5;
}

.no-posts h3 {
    font-size: 2rem;
    margin-bottom: 1rem;
}

.no-posts p {
    font-size: 1.1rem;
    margin-bottom: 2rem;
}

.breadcrumb {
    background: transparent;
    padding: 0;
    margin-bottom: 2rem;
}

.breadcrumb-item a {
    color: rgba(255,255,255,0.8);
    text-decoration: none;
}

.breadcrumb-item a:hover {
    color: white;
}

.breadcrumb-item.active {
    color: white;
    font-weight: 500;
}

@media (min-width: 1200px) {
    .posts-grid {
        grid-template-columns: repeat(auto-fill, minmax(280px, 300px));
        max-width: 100%;
    }
}

@media (min-width: 992px) and (max-width: 1199px) {
    .posts-grid {
        grid-template-columns: repeat(auto-fill, minmax(280px, 280px));
    }
}

@media (max-width: 768px) {
    .category-header {
        padding: 60px 0;
    }
    
    .category-title {
        font-size: 2rem;
    }
    
    .category-icon {
        font-size: 3rem;
    }
    
    .stat-number {
        font-size: 2rem;
    }
    
    .posts-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 250px));
        gap: 1rem;
        justify-content: center;
    }
    
    .post-content {
        padding: 1.2rem;
    }
    
    .post-image {
        height: 180px;
    }
    
    .post-title {
        font-size: 1.1rem;
    }
    
    .post-meta {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
}

@media (max-width: 480px) {
    .posts-grid {
        grid-template-columns: 1fr;
        justify-content: stretch;
    }
    
    .post-content {
        padding: 1rem;
    }
    
    .post-image {
        height: 160px;
    }
}

/* Category Banner Slideshow */
.category-banner {
    position: relative;
    height: 500px;
    overflow: hidden;
    margin-bottom: 3rem;
}

.banner-slide {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    opacity: 0;
    transition: opacity 1s ease-in-out;
    cursor: pointer;
}

.banner-slide.active {
    opacity: 1;
}

.banner-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.4) 100%);
    display: flex;
    align-items: center;
    justify-content: center;
}

.banner-content {
    text-align: center;
    color: white;
    max-width: 90%;
    z-index: 2;
}

.banner-title {
    font-size: 3.5rem;
    font-weight: 700;
    text-shadow: 3px 3px 6px rgba(0,0,0,0.7);
    line-height: 1.2;
    margin: 0;
}

.banner-indicators {
    position: absolute;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 8px;
    z-index: 3;
}

.banner-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: rgba(255,255,255,0.5);
    cursor: pointer;
    transition: all 0.3s ease;
}

.banner-dot.active {
    background: white;
    transform: scale(1.2);
}

.banner-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(255,255,255,0.2);
    border: none;
    color: white;
    font-size: 1.5rem;
    padding: 15px 20px;
    cursor: pointer;
    transition: all 0.3s ease;
    z-index: 3;
    border-radius: 50%;
}

.banner-nav:hover {
    background: rgba(255,255,255,0.3);
    transform: translateY(-50%) scale(1.1);
}

.banner-prev {
    left: 20px;
}

.banner-next {
    right: 20px;
}

@media (max-width: 768px) {
    .category-banner {
        height: 350px;
        margin-bottom: 2rem;
    }
    
    .banner-title {
        font-size: 2.2rem;
    }
    
    .banner-nav {
        padding: 10px 15px;
        font-size: 1.2rem;
    }
}

@media (max-width: 480px) {
    .category-banner {
        height: 300px;
    }
    
    .banner-title {
        font-size: 1.8rem;
    }
    
    .banner-content {
        max-width: 95%;
    }
}
</style>

<main>
    <!-- Category Banner Slideshow -->
    <?php if (!empty($categoryPosts)): ?>
    <section class="category-banner-section">
        <div class="container-fluid px-0">
            <div class="category-banner" id="categoryBanner">
                <?php 
                $bannerPosts = array_slice($categoryPosts, 0, 5); // Lấy 5 bài đầu tiên
                foreach ($bannerPosts as $index => $post): 
                ?>
                <div class="banner-slide <?= $index === 0 ? 'active' : '' ?>" 
                     style="background-image: url('<?= asset($post['image']) ?>')"
                     onclick="window.location.href='<?= url('show-post/' . $post['id']) ?>'">
                    <div class="banner-overlay">
                        <div class="banner-content">
                            <h2 class="banner-title"><?= $post['title'] ?></h2>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                
                <!-- Navigation Arrows -->
                <button class="banner-nav banner-prev" onclick="changeBannerSlide(-1)">
                    <i class="fa fa-chevron-left"></i>
                </button>
                <button class="banner-nav banner-next" onclick="changeBannerSlide(1)">
                    <i class="fa fa-chevron-right"></i>
                </button>
                
                <!-- Dots Indicators -->
                <div class="banner-indicators">
                    <?php foreach ($bannerPosts as $index => $post): ?>
                    <div class="banner-dot <?= $index === 0 ? 'active' : '' ?>" 
                         onclick="goToBannerSlide(<?= $index ?>)"></div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
    <?php else: ?>
    <!-- Empty Category Header -->
    <section class="category-header">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= url('/') ?>">Trang chủ</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span>Danh mục</span>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <?= $category['name'] ?>
                    </li>
                </ol>
            </nav>
            <div class="text-center">
                <div class="category-icon">
                    <i class="<?= getCategoryIcon($category['name']) ?>"></i>
                </div>
                <h1 class="category-title"><?= $category['name'] ?></h1>
                <p class="category-description">Chưa có bài viết nào trong danh mục này</p>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Category Posts -->
    <section class="py-5">
        <div class="container">
            <?php if(!empty($categoryPosts)) { ?>
            <!-- Posts Grid -->
            <div class="posts-grid">
                <?php foreach ($categoryPosts as $post) { ?>
                <article class="post-card">
                    <!-- Post Image -->
                    <div class="post-image">
                        <img src="<?= asset($post['image']) ?>" 
                             alt="<?= $post['title'] ?>">
                    </div>
                    
                    <!-- Post Content -->
                    <div class="post-content">
                        <h2 class="post-title">
                            <a href="<?= url('show-post/' . $post['id']) ?>">
                                <?= $post['title'] ?>
                            </a>
                        </h2>
                        
                        <div class="post-excerpt">
                            <?= isset($post['summary']) && strlen($post['summary']) > 80 ? substr($post['summary'], 0, 80) . '...' : (isset($post['body']) ? substr(strip_tags($post['body']), 0, 80) . '...' : '') ?>
                        </div>
                        
                        <div class="post-meta">
                            <div class="post-author">
                                <i class="fas fa-user me-1"></i>
                                <?= $post['username'] ?>
                            </div>
                            <div class="d-flex gap-3">
                                <div class="post-date">
                                    <i class="fas fa-calendar me-1"></i>
                                    <?= $post['created_at'] ?>
                                </div>
                                <div class="post-comments">
                                    <i class="fas fa-comments me-1"></i>
                                    <?= $post['comments_count'] ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
                <?php } ?>
            </div>
            <?php } else { ?>
            <!-- No Posts Message -->
            <div class="no-posts">
                <i class="fas fa-newspaper"></i>
                <h3>No Articles Found</h3>
                <p>There are currently no articles in this category. Please check back later!</p>
                <a href="<?= url('/') ?>" class="btn btn-primary mt-3">
                    <i class="fas fa-arrow-left me-2"></i>Back to Homepage
                </a>
            </div>
            <?php } ?>
        </div>
    </section>
</main>

<script>
// Banner Slideshow JavaScript
let currentBannerSlide = 0;
let bannerSlides = document.querySelectorAll('.banner-slide');
let bannerDots = document.querySelectorAll('.banner-dot');
let bannerInterval;

function showBannerSlide(n) {
    // Remove active class from all slides and dots
    bannerSlides.forEach(slide => slide.classList.remove('active'));
    bannerDots.forEach(dot => dot.classList.remove('active'));
    
    // Wrap around if necessary
    if (n >= bannerSlides.length) currentBannerSlide = 0;
    if (n < 0) currentBannerSlide = bannerSlides.length - 1;
    
    // Show current slide and dot
    if (bannerSlides[currentBannerSlide]) {
        bannerSlides[currentBannerSlide].classList.add('active');
    }
    if (bannerDots[currentBannerSlide]) {
        bannerDots[currentBannerSlide].classList.add('active');
    }
}

function changeBannerSlide(direction) {
    clearInterval(bannerInterval);
    currentBannerSlide += direction;
    showBannerSlide(currentBannerSlide);
    startBannerAutoSlide();
}

function goToBannerSlide(n) {
    clearInterval(bannerInterval);
    currentBannerSlide = n;
    showBannerSlide(currentBannerSlide);
    startBannerAutoSlide();
}

function nextBannerSlide() {
    currentBannerSlide++;
    showBannerSlide(currentBannerSlide);
}

function startBannerAutoSlide() {
    bannerInterval = setInterval(nextBannerSlide, 4000); // Chuyển slide mỗi 4 giây
}

// Initialize banner slideshow
document.addEventListener('DOMContentLoaded', function() {
    if (bannerSlides.length > 0) {
        showBannerSlide(currentBannerSlide);
        startBannerAutoSlide();
        
        // Pause auto-slide when hovering over banner
        const banner = document.getElementById('categoryBanner');
        if (banner) {
            banner.addEventListener('mouseenter', () => clearInterval(bannerInterval));
            banner.addEventListener('mouseleave', startBannerAutoSlide);
        }
    }
});

// Keyboard navigation
document.addEventListener('keydown', function(e) {
    if (bannerSlides.length > 0) {
        if (e.key === 'ArrowLeft') {
            changeBannerSlide(-1);
        } else if (e.key === 'ArrowRight') {
            changeBannerSlide(1);
        }
    }
});
</script>

<?php require_once(BASE_PATH . '/template/app/layouts/footer.php'); ?>