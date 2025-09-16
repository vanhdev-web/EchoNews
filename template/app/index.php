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
?>

<style>
    /* Minimalistic Homepage Styles */
    .hero-section {
        background: var(--bg-white);
        padding: 2rem 0;
    }
    
    .featured-card {
        border: 1px solid var(--border-light);
        border-radius: 8px;
        overflow: hidden;
        transition: border-color 0.2s ease;
        background: var(--bg-white);
    }
    
    .featured-card:hover {
        border-color: var(--border-medium);
    }
    
    .category-badge {
        background: var(--primary);
        color: var(--white);
        padding: 0.25rem 0.75rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .post-meta {
        color: var(--text-muted);
        font-size: 0.875rem;
    }
    
    .post-title {
        color: var(--text-primary);
        font-weight: 600;
        line-height: 1.4;
        margin: 1rem 0 0.5rem 0;
    }
    
    .post-title:hover {
        color: var(--text-secondary);
    }
    
    .card-title {
        color: var(--text-primary) !important;
        font-weight: 600;
    }
    
    .card-title:hover {
        color: var(--text-secondary) !important;
    }
    
    .post-title a {
        color: var(--text-primary) !important;
        text-decoration: none;
    }
    
    .post-title a:hover {
        color: var(--text-secondary) !important;
    }
    
    .breaking-news {
        background: var(--bg-light);
        border-left: 4px solid var(--primary);
        padding: 1rem;
        margin-bottom: 2rem;
    }
    
    .breaking-news h5 {
        color: var(--primary);
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .section-title {
        color: var(--text-primary);
        font-weight: 600;
        border-bottom: 2px solid var(--border-light);
        padding-bottom: 0.5rem;
        margin-bottom: 1.5rem;
    }
    
    .category-grid {
        gap: 1rem;
    }
    
    .category-card {
        border: 1px solid var(--border-light);
        border-radius: 8px;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.2s ease;
        background: var(--bg-white);
    }
    
    .category-card:hover {
        border-color: var(--border-medium);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }
    
    .category-card i {
        font-size: 2rem;
        color: var(--primary);
        margin-bottom: 1rem;
    }
    
    .category-card h6 {
        color: var(--text-primary);
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .category-card .text-muted {
        color: var(--text-muted) !important;
        font-size: 0.875rem;
    }
</style>

<main>
    <!-- Hero Section with Featured Posts -->
    <section class="hero-section">
        <div class="container">
            <?php if(!empty($breakingNews)) { ?>
            <!-- Breaking News Alert -->
            <div class="breaking-news">
                <div class="d-flex align-items-center">
                    <h5 class="me-3 mb-0">Breaking News</h5>
                    <a href="<?= url('show-post/' . $breakingNews['id']) ?>" class="text-decoration-none">
                        <?= $breakingNews['title'] ?>
                    </a>
                </div>
            </div>
            <?php } ?>

            <div class="row g-4">
                <!-- Main Featured Post -->
                <div class="col-lg-8">
                    <?php if(isset($topSelectedPosts[0])) { ?>
                    <div class="featured-card h-100">
                        <div class="position-relative">
                            <img src="<?= asset($topSelectedPosts[0]['image']) ?>" 
                                 class="w-100" 
                                 alt="<?= $topSelectedPosts[0]['title'] ?>"
                                 style="height: 400px; object-fit: cover;">
                            <div class="position-absolute top-0 start-0 m-3">
                                <span class="category-badge">
                                    <i class="<?= getCategoryIcon($topSelectedPosts[0]['category']) ?> me-1"></i><?= $topSelectedPosts[0]['category'] ?>
                                </span>
                            </div>
                        </div>
                        <div class="p-4">
                            <div class="post-meta mb-2">
                                <span class="me-3"><?= $topSelectedPosts[0]['username'] ?></span>
                                <span><?= date('M j, Y', strtotime($topSelectedPosts[0]['created_at'])) ?></span>
                            </div>
                            <h2 class="post-title">
                                <a href="<?= url('show-post/' . $topSelectedPosts[0]['id']) ?>" 
                                   class="text-decoration-none">
                                    <?= $topSelectedPosts[0]['title'] ?>
                                </a>
                            </h2>
                            <p class="post-excerpt">
                                <?= substr(strip_tags($topSelectedPosts[0]['body']), 0, 150) ?>...
                            </p>
                        </div>
                    </div>
                    <?php } ?>
                </div>

                <!-- Secondary Featured Posts -->
                <div class="col-lg-4">
                    <div class="row g-3">
                        <?php if(isset($topSelectedPosts[1])) { ?>
                        <div class="col-12">
                            <div class="featured-card h-100">
                                <div class="position-relative">
                                    <img src="<?= asset($topSelectedPosts[1]['image']) ?>" 
                                         class="w-100" 
                                         alt="<?= $topSelectedPosts[1]['title'] ?>"
                                         style="height: 180px; object-fit: cover;">
                                    <div class="position-absolute top-0 start-0 m-3">
                                        <span class="category-badge">
                                            <?= $topSelectedPosts[1]['category'] ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="p-3">
                                    <div class="post-meta mb-2">
                                        <span class="me-3"><?= $topSelectedPosts[1]['username'] ?></span>
                                        <span><?= date('M j', strtotime($topSelectedPosts[1]['created_at'])) ?></span>
                                    </div>
                                    <h5 class="post-title">
                                        <a href="<?= url('show-post/' . $topSelectedPosts[1]['id']) ?>" 
                                           class="text-decoration-none">
                                            <?= $topSelectedPosts[1]['title'] ?>
                                        </a>
                                    </h5>
                                </div>
                            </div>
                        </div>
                        <?php } ?>

                        <?php if(isset($topSelectedPosts[2])) { ?>
                        <div class="col-12">
                            <div class="featured-card h-100">
                                <div class="position-relative">
                                    <img src="<?= asset($topSelectedPosts[2]['image']) ?>" 
                                         class="w-100" 
                                         alt="<?= $topSelectedPosts[2]['title'] ?>"
                                         style="height: 180px; object-fit: cover;">
                                    <div class="position-absolute top-0 start-0 m-3">
                                        <span class="category-badge">
                                            <?= $topSelectedPosts[2]['category'] ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="p-3">
                                    <div class="post-meta mb-2">
                                        <span class="me-3"><?= $topSelectedPosts[2]['username'] ?></span>
                                        <span><?= date('M j', strtotime($topSelectedPosts[2]['created_at'])) ?></span>
                                    </div>
                                    <h5 class="post-title">
                                        <a href="<?= url('show-post/' . $topSelectedPosts[2]['id']) ?>" 
                                           class="text-decoration-none">
                                            <?= $topSelectedPosts[2]['title'] ?>
                                        </a>
                                    </h5>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Latest News Section -->
    <section class="latest-news py-5 bg-light">
        <div class="container">
            <div class="row">
                <!-- Main Content -->
                <div class="col-lg-8">
                    <div class="d-flex align-items-center mb-4">
                        <h2 class="fw-bold mb-0 me-3">Latest News</h2>
                        <div class="flex-grow-1 bg-secondary" style="height: 3px;"></div>
                    </div>

                    <div class="row g-4">
                        <?php foreach ($lastPosts as $index => $lastPost) { ?>
                        <div class="col-md-6">
                            <article class="card border-0 shadow-sm h-100 hover-shadow">
                                <div class="position-relative">
                                    <img src="<?= asset($lastPost['image']) ?>" 
                                         class="card-img-top" 
                                         alt="<?= $lastPost['title'] ?>"
                                         style="height: 200px; object-fit: cover;">
                                    <span class="position-absolute top-0 start-0 m-2 badge bg-secondary text-white">
                                        <i class="<?= getCategoryIcon($lastPost['category']) ?> me-1"></i><?= $lastPost['category'] ?>
                                    </span>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <a href="<?= url('show-post/' . $lastPost['id']) ?>" 
                                           class="text-decoration-none text-dark">
                                            <?= $lastPost['title'] ?>
                                        </a>
                                    </h5>
                                    <p class="card-text text-muted">
                                        <?= isset($lastPost['summary']) ? substr(strip_tags($lastPost['summary']), 0, 120) : substr(strip_tags($lastPost['body']), 0, 120) ?>...
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex text-muted small">
                                            <span class="me-3">
                                                <i class="fas fa-user me-1"></i><?= $lastPost['username'] ?>
                                            </span>
                                            <span class="me-3">
                                                <i class="fas fa-calendar me-1"></i><?= $lastPost['created_at'] ?>
                                            </span>
                                            <span>
                                                <i class="fas fa-comments me-1"></i><?= $lastPost['comments_count'] ?>
                                            </span>
                                        </div>
                                        <a href="<?= url('show-post/' . $lastPost['id']) ?>" 
                                           class="btn btn-outline-primary btn-sm">
                                            Read More <i class="fas fa-arrow-right ms-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </article>
                        </div>
                        <?php } ?>
                    </div>

                    <!-- Banner Ad -->
                    <?php if(!empty($bodyBanner)) { ?>
                    <div class="mt-5">
                        <div class="card border-0 shadow-sm">
                            <img src="<?= asset($bodyBanner['image']) ?>" 
                                 class="card-img-top" 
                                 alt="Advertisement"
                                 style="height: 120px; object-fit: cover;">
                        </div>
                    </div>
                    <?php } ?>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <div class="row g-4">
                        <!-- Popular Posts -->
                        <div class="col-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0"><i class="fas fa-fire me-2"></i>Popular Posts</h5>
                                </div>
                                <div class="card-body p-0">
                                    <?php foreach ($popularPosts as $index => $popularPost) { ?>
                                    <div class="d-flex p-3 border-bottom">
                                        <img src="<?= asset($popularPost['image']) ?>" 
                                             class="rounded me-3" 
                                             alt="<?= $popularPost['title'] ?>"
                                             style="width: 80px; height: 60px; object-fit: cover;">
                                        <div>
                                            <h6 class="mb-1">
                                                <a href="<?= url('show-post/' . $popularPost['id']) ?>" 
                                                   class="text-decoration-none text-dark">
                                                    <?= substr($popularPost['title'], 0, 60) ?>...
                                                </a>
                                            </h6>
                                            <small class="text-muted">
                                                <i class="fas fa-calendar me-1"></i><?= $popularPost['created_at'] ?>
                                            </small>
                                        </div>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>

                        <!-- Categories -->
                        <div class="col-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-secondary text-white">
                                    <h5 class="mb-0"><i class="fas fa-list me-2"></i>Categories</h5>
                                </div>
                                <div class="card-body p-0">
                                    <?php foreach ($categories as $category) { ?>
                                    <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                                        <a href="<?= url('show-category/' . $category['id']) ?>" 
                                           class="text-decoration-none text-dark">
                                            <?= $category['name'] ?>
                                        </a>
                                        <span class="badge bg-light text-dark"><?= $category['count'] ?></span>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>

                        <!-- Newsletter -->
                        <div class="col-12">
                            <div class="card border-0 bg-light">
                                <div class="card-body text-center">
                                    <i class="fas fa-envelope fa-3x mb-3"></i>
                                    <h5>Subscribe to Newsletter</h5>
                                    <p class="small">Get the latest news and updates delivered to your inbox.</p>
                                    <form>
                                        <div class="input-group mb-3">
                                            <input type="email" class="form-control" placeholder="Your email">
                                            <button class="btn btn-light" type="submit">
                                                <i class="fas fa-paper-plane"></i>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- AOS Animation -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<script>
    // Initialize AOS
    AOS.init({
        duration: 800,
        once: true
    });

    // Add smooth hover effects
    document.querySelectorAll('.hover-shadow').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
</script>

<?php require_once "template/app/layouts/footer.php" ?>