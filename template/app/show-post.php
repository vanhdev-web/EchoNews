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
        
        return 'fas fa-newspaper'; // default
    }
?>

<style>
    /* Minimalistic Post Detail Styles */
    .post-detail {
        background: var(--bg-white);
        padding: 2rem 0;
    }
    
    .post-header {
        border-bottom: 1px solid var(--border-light);
        padding-bottom: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .post-title {
        color: var(--text-primary);
        font-weight: 700;
        line-height: 1.3;
        margin-bottom: 1rem;
        font-size: 2.25rem;
        word-wrap: break-word;
        overflow-wrap: break-word;
        max-width: 100%;
        box-sizing: border-box;
    }
    
    .post-meta {
        color: var(--text-muted);
        font-size: 0.9rem;
        margin-bottom: 1rem;
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
        text-decoration: none;
    }
    
    .post-image {
        border-radius: 12px;
        margin: 2rem 0;
        overflow: hidden;
    }
    
    .post-image img {
        border-radius: 12px;
        width: 100%;
        height: auto;
        max-height: 400px;
        object-fit: cover;
    }
    
    .post-content {
        line-height: 1.8;
        color: var(--text-primary);
        font-size: 1.1rem;
        word-wrap: break-word;
        overflow-wrap: break-word;
        max-width: 100%;
        box-sizing: border-box;
    }
    
    .post-content p {
        margin-bottom: 1.5rem;
    }
    
    .post-content img {
        max-width: 100%;
        height: auto;
        display: block;
        margin: 1rem auto;
        border-radius: 8px;
    }
    
    .post-content table {
        max-width: 100%;
        overflow-x: auto;
        display: block;
        white-space: nowrap;
    }
    
    .post-content h2, .post-content h3 {
        color: var(--text-primary);
        margin-top: 2rem;
        margin-bottom: 1rem;
    }
    
    .post-share {
        background: var(--bg-light);
        border-radius: 8px;
        padding: 1.5rem;
        margin-top: 2rem;
        border: 1px solid var(--border-light);
    }
    
    .post-share .btn {
        border-radius: 6px;
        font-size: 0.875rem;
        padding: 0.5rem 1rem;
    }
    
    .comments-section {
        border-top: 1px solid var(--border-light);
        padding-top: 2rem;
        margin-top: 3rem;
    }
    
    .comment-card {
        border: 1px solid var(--border-light);
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        background: var(--bg-light);
        transition: border-color 0.2s ease;
    }
    
    .comment-card:hover {
        border-color: var(--border-medium);
    }
    
    .comment-author {
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }
    
    .comment-date {
        color: var(--text-muted);
        font-size: 0.875rem;
        margin-bottom: 1rem;
    }
    
    .comment-content {
        color: var(--text-primary);
        line-height: 1.6;
    }
    
    .comment-form {
        background: var(--bg-light);
        border-radius: 8px;
        padding: 2rem;
        margin-top: 2rem;
        border: 1px solid var(--border-light);
    }
    
    /* Sidebar Styles */
    .sidebar-card {
        border: 1px solid var(--border-light);
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 1.5rem;
        background: var(--bg-white);
        transition: border-color 0.2s ease;
    }
    
    .sidebar-card:hover {
        border-color: var(--border-medium);
    }
    
    .sidebar-card-header {
        background: var(--primary);
        color: var(--white);
        padding: 1rem 1.25rem;
        font-weight: 600;
        font-size: 1rem;
    }
    
    .sidebar-card-body {
        padding: 1.25rem;
    }
    
    .sidebar-post-item {
        display: flex;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--border-light);
    }
    
    .sidebar-post-item:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }
    
    .sidebar-post-img {
        width: 80px;
        height: 60px;
        border-radius: 6px;
        object-fit: cover;
        margin-right: 1rem;
        flex-shrink: 0;
    }
    
    .sidebar-post-content h6 {
        font-size: 0.875rem;
        font-weight: 600;
        line-height: 1.4;
        margin-bottom: 0.5rem;
    }
    
    .sidebar-post-content h6 a {
        color: var(--text-primary);
        text-decoration: none;
        transition: color 0.2s ease;
    }
    
    .sidebar-post-content h6 a:hover {
        color: var(--secondary);
    }
    
    .sidebar-post-meta {
        color: var(--text-muted);
        font-size: 0.75rem;
    }
    
    /* Responsive adjustments */
    @media (max-width: 767px) {
        .post-title {
            font-size: 1.875rem;
        }
        
        .post-content {
            font-size: 1rem;
        }
        
        /* On mobile, allow normal stacking */
        .row {
            display: flex !important;
            flex-wrap: wrap !important;
        }
        
        .col-md-6, .col-md-4 {
            flex: 0 0 100%;
            max-width: 100%;
        }
    }
    
    /* Ensure sidebar stays on right on medium screens and up */
    @media (min-width: 768px) {
        .col-md-6 {
            flex: 0 0 50%;
            max-width: 50%;
        }
        
        .col-md-4 {
            flex: 0 0 33.333333%;
            max-width: 33.333333%;
        }
        
        .post-single {
            max-width: 100%;
            width: 100%;
            margin-right: auto;
        }
        
        .post-content {
            max-width: 100%;
            padding-right: 1rem;
        }
        
        .sidebar-sticky {
            position: sticky;
            top: 2rem;
            max-height: calc(100vh - 4rem);
            overflow-y: auto;
        }
        
        /* Ensure proper flexbox layout */
        .row {
            display: flex !important;
        }
        
        .row:first-child {
            flex-wrap: nowrap !important;
        }
        
        .col-md-6 {
            order: 1;
        }
        
        .col-md-4 {
            order: 2;
        }
    }
    
    /* Container and grid fixes */
    .container {
        max-width: 1200px;
        width: 100%;
        padding-left: 15px;
        padding-right: 15px;
        margin-left: auto;
        margin-right: auto;
    }
    
    .row {
        display: flex;
        flex-wrap: wrap;
        margin-left: -15px;
        margin-right: -15px;
    }
    
    .col-md-6, .col-md-4 {
        padding-left: 15px;
        padding-right: 15px;
    }
    
    /* Prevent content from breaking layout */
    .post-wrapper {
        max-width: 100%;
        overflow: hidden;
        width: 100%;
        box-sizing: border-box;
    }
    
    .post-single {
        overflow: hidden;
        max-width: 100%;
        width: 100%;
        box-sizing: border-box;
    }
    
    .post-single * {
        max-width: 100%;
        box-sizing: border-box;
    }
    
    .post-header, .post-content, .post-share {
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
    }
</style>

<main>
    <!-- Post Detail Section -->
    <section class="post-detail py-5">
        <div class="container">
            <div class="row">
                <!-- Main Content -->
                <div class="col-md-8">
                    <div class="post-wrapper">
                        <article class="post-single">
                        <!-- Post Header -->
                        <div class="post-header">
                            <!-- Breadcrumb -->
                            <nav aria-label="breadcrumb" class="mb-3">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="<?= url('/') ?>" class="text-decoration-none">Home</a>
                                    </li>
                                    <li class="breadcrumb-item">
                                        <a href="<?= url('show-category/' . $post['cat_id']) ?>" class="text-decoration-none">
                                            <?= $post['category'] ?>
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        Article
                                    </li>
                                </ol>
                            </nav>

                            <!-- Category Badge -->
                            <div class="mb-3">
                                <a href="<?= url('show-category/' . $post['cat_id']) ?>" class="category-badge">
                                    <?= $post['category'] ?>
                                </a>
                            </div>

                            <!-- Post Title -->
                            <h1 class="post-title"><?= $post['title'] ?></h1>

                            <!-- Post Meta -->
                            <div class="post-meta">
                                By <strong><?= $post['username'] ?></strong> • 
                                <?= $post['created_at'] ?> • 
                                <?= $post['comments_count'] ?> Comments
                            </div>
                        </div>
                                    <i class="fas fa-eye me-2"></i>
                                    Views: <?= isset($post['views']) ? $post['views'] : rand(100, 1000) ?>
                                </div>
                            </div>
                        </div>

                        <!-- Featured Image -->
                        <div class="post-image">
                            <img src="<?= asset($post['image']) ?>" 
                                 alt="<?= $post['title'] ?>" 
                                 class="img-fluid">
                        </div>

                        <!-- Post Content -->
                        <div class="post-content">
                            <div class="content-body">
                                <?= $post['body'] ?>
                            </div>

                            <!-- Share Buttons -->
                            <div class="post-share">
                                <h5 class="mb-3">
                                    <i class="fas fa-share-alt me-2"></i>Share this article
                                </h5>
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="#" class="btn btn-primary btn-sm">
                                        <i class="fab fa-facebook-f me-1"></i>Facebook
                                    </a>
                                    <a href="#" class="btn btn-info btn-sm">
                                        <i class="fab fa-twitter me-1"></i>Twitter
                                    </a>
                                    <a href="#" class="btn btn-success btn-sm">
                                        <i class="fab fa-whatsapp me-1"></i>WhatsApp
                                    </a>
                                    <a href="#" class="btn btn-secondary btn-sm">
                                        <i class="fas fa-copy me-1"></i>Copy Link
                                    </a>
                                </div>
                            </div>
                        </div>
                    </article>
                </div>

                <!-- Sidebar -->
                <div class="col-md-4">
                    <div class="sidebar-sticky">
                        <!-- Popular Posts -->
                        <?php if(!empty($popularPosts)) { ?>
                        <div class="sidebar-card">
                            <div class="sidebar-card-header">
                                <i class="fas fa-fire me-2"></i>Popular Posts
                            </div>
                            <div class="sidebar-card-body">
                                <?php foreach (array_slice($popularPosts, 0, 5) as $popularPost) { ?>
                                <div class="sidebar-post-item">
                                    <img src="<?= asset($popularPost['image']) ?>" 
                                         alt="<?= $popularPost['title'] ?>"
                                         class="sidebar-post-img">
                                    <div class="sidebar-post-content">
                                        <h6>
                                            <a href="<?= url('show-post/' . $popularPost['id']) ?>">
                                                <?= strlen($popularPost['title']) > 50 ? substr($popularPost['title'], 0, 50) . '...' : $popularPost['title'] ?>
                                            </a>
                                        </h6>
                                        <div class="sidebar-post-meta">
                                            <i class="fas fa-calendar me-1"></i><?= $popularPost['created_at'] ?>
                                            <span class="ms-2">
                                                <i class="fas fa-comments me-1"></i><?= $popularPost['comments_count'] ?? 0 ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        <?php } ?>

                        <!-- Related Posts -->
                        <?php if(!empty($relatedPosts)) { ?>
                        <div class="sidebar-card">
                            <div class="sidebar-card-header">
                                <i class="fas fa-newspaper me-2"></i>Related Posts
                            </div>
                            <div class="sidebar-card-body">
                                <?php foreach ($relatedPosts as $relatedPost) { ?>
                                <div class="sidebar-post-item">
                                    <img src="<?= asset($relatedPost['image']) ?>" 
                                         alt="<?= $relatedPost['title'] ?>"
                                         class="sidebar-post-img">
                                    <div class="sidebar-post-content">
                                        <h6>
                                            <a href="<?= url('show-post/' . $relatedPost['id']) ?>">
                                                <?= strlen($relatedPost['title']) > 50 ? substr($relatedPost['title'], 0, 50) . '...' : $relatedPost['title'] ?>
                                            </a>
                                        </h6>
                                        <div class="sidebar-post-meta">
                                            <i class="fas fa-calendar me-1"></i><?= $relatedPost['created_at'] ?>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        <?php } ?>

                        <!-- Categories -->
                        <?php if(!empty($categories)) { ?>
                        <div class="sidebar-card">
                            <div class="sidebar-card-header">
                                <i class="fas fa-folder me-2"></i>Categories
                            </div>
                            <div class="sidebar-card-body">
                                <?php foreach ($categories as $category) { ?>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <a href="<?= url('show-category/' . $category['id']) ?>" 
                                       class="text-decoration-none" 
                                       style="color: var(--text-primary);">
                                        <?= $category['name'] ?>
                                    </a>
                                    <span class="badge bg-light text-dark"><?= $category['count'] ?? 0 ?></span>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <!-- Comments Section Row -->
            <div class="row">
                <div class="col-12">
                    <!-- Comments Section -->
                    <section class="comments-section mt-5" data-aos="fade-up">
                        <div class="comments-header d-flex align-items-center mb-4">
                            <h3 class="fw-bold mb-0 me-3">Comments (<?= count($comments) ?>)</h3>
                            <div class="flex-grow-1 bg-secondary" style="height: 2px;"></div>
                        </div>

                        <!-- Comments List -->
                        <?php if(!empty($comments)) { ?>
                        <div class="comments-list mb-5">
                            <?php foreach ($comments as $index => $comment) { ?>
                            <div class="comment-item mb-4 p-4 bg-light rounded" 
                                 data-aos="fade-up" 
                                 data-aos-delay="<?= $index * 100 ?>">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="comment-avatar rounded-circle d-flex align-items-center justify-content-center"
                                             style="width: 50px; height: 50px;">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="comment-header d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <h5 class="mb-1"><?= $comment['username'] ?></h5>
                                                <small class="text-muted">
                                                    <i class="fas fa-clock me-1"></i><?= $comment['created_at'] ?>
                                                </small>
                                            </div>
                                        </div>
                                        <div class="comment-content">
                                            <p class="mb-0"><?= nl2br(htmlspecialchars($comment['comment'])) ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                        <?php } else { ?>
                        <div class="no-comments text-center py-5">
                            <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No comments yet</h5>
                            <p class="text-muted">Be the first to share your thoughts!</p>
                        </div>
                        <?php } ?>

                        <!-- Comment Form -->
                        <?php if(isset($_SESSION['user'])) { ?>
                        <div class="comment-form">
                            <h4 class="mb-4">
                                <i class="fas fa-edit me-2"></i>Add Your Comment
                            </h4>
                            <form action="<?= url('comment-store') ?>" method="post" class="needs-validation" novalidate>
                                <input type="hidden" name="post_id" value="<?= $id ?>">
                                <div class="mb-3">
                                    <label for="comment" class="form-label">Your Comment *</label>
                                    <textarea class="form-control" 
                                              id="comment" 
                                              name="comment" 
                                              rows="5" 
                                              placeholder="Share your thoughts..."
                                              required></textarea>
                                    <div class="invalid-feedback">
                                        Please provide a valid comment.
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-2"></i>Post Comment
                                </button>
                            </form>
                        </div>
                        <?php } else { ?>
                        <div class="comment-login-prompt text-center p-4 bg-light rounded">
                            <i class="fas fa-sign-in-alt fa-2x text-muted mb-3"></i>
                            <h5>Join the Conversation</h5>
                            <p class="text-muted mb-3">Please log in to leave a comment</p>
                            <a href="<?= url('login') ?>" class="btn btn-primary me-2">
                                <i class="fas fa-sign-in-alt me-1"></i>Login
                            </a>
                            <a href="<?= url('register') ?>" class="btn btn-outline-primary">
                                <i class="fas fa-user-plus me-1"></i>Register
                            </a>
                        </div>
                        <?php } ?>
                    </section>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
// Form validation
(function() {
    'use strict';
    window.addEventListener('load', function() {
        var forms = document.getElementsByClassName('needs-validation');
        var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();

// Copy link functionality
document.addEventListener('DOMContentLoaded', function() {
    const copyBtn = document.querySelector('.post-share .btn-warning');
    if (copyBtn) {
        copyBtn.addEventListener('click', function(e) {
            e.preventDefault();
            navigator.clipboard.writeText(window.location.href).then(function() {
                // Show toast or alert
                const originalText = copyBtn.innerHTML;
                copyBtn.innerHTML = '<i class="fas fa-check me-1"></i>Copied!';
                setTimeout(() => {
                    copyBtn.innerHTML = originalText;
                }, 2000);
            });
        });
    }
});
</script>

<?php require_once(BASE_PATH . '/template/app/layouts/footer.php'); ?>