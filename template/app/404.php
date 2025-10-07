<?php require_once(BASE_PATH . '/template/app/layouts/header.php'); ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="error-page">
                <h1 class="display-1" style="color: #667eea;">404</h1>
                <h2 class="mb-4">Page Not Found</h2>
                <p class="lead mb-4">The page you are looking for could not be found.</p>
                <a href="<?=url('')?>" class="btn btn-primary">
                    <i class="fas fa-home"></i> Return to Home
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.error-page {
    padding: 3rem 0;
}

.error-page .display-1 {
    font-size: 8rem;
    font-weight: 700;
    line-height: 1;
}
</style>

<?php require_once(BASE_PATH . '/template/app/layouts/footer.php'); ?>