<?php require_once(BASE_PATH . '/app/Views/admin/layouts/head-tag.php'); ?>

        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h5"><i class="fas fa-cog"></i> Website Settings</h1>
        </div>

<section class="row my-3">
    <section class="col-12">
        
        <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="alert alert-<?= $_SESSION['flash_type'] ?> alert-dismissible fade show" role="alert">
                <?= $_SESSION['flash_message'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['flash_message'], $_SESSION['flash_type']); ?>
        <?php endif; ?>

        <form method="post" action="<?= url('admin/settings') ?>" enctype="multipart/form-data">
            
            <!-- Website Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Website Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="site_name" class="form-label">Site Name</label>
                                <input type="text" class="form-control" id="site_name" name="site_name" 
                                       value="<?= getSetting($settings, 'site_name', 'Online News Site') ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="admin_email" class="form-label">Admin Email</label>
                                <input type="email" class="form-control" id="admin_email" name="admin_email" 
                                       value="<?= getSetting($settings, 'admin_email', 'admin@example.com') ?>" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="site_description" class="form-label">Site Description</label>
                        <textarea class="form-control" id="site_description" name="site_description" rows="3"><?= getSetting($settings, 'site_description', 'Your trusted news source') ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="site_keywords" class="form-label">SEO Keywords</label>
                        <input type="text" class="form-control" id="site_keywords" name="site_keywords" 
                               value="<?= getSetting($settings, 'site_keywords', 'news, online, breaking news') ?>"
                               placeholder="Separate keywords with commas">
                        <small class="form-text text-muted">Keywords for SEO, separated by commas</small>
                    </div>
                </div>
            </div>

            <!-- Logo Upload -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Website Logo</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="logo" class="form-label">Upload New Logo</label>
                                <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                                <small class="form-text text-muted">Max size: 2MB. Formats: JPG, PNG, GIF, WebP</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Current Logo</label>
                                <div>
                                    <?php $currentLogo = getSetting($settings, 'logo', 'public/setting/logo.png'); ?>
                                    <?php if ($currentLogo && file_exists($currentLogo)): ?>
                                        <img src="<?= url($currentLogo) ?>" alt="Current Logo" class="img-thumbnail" style="max-height: 100px;">
                                    <?php else: ?>
                                        <p class="text-muted">No logo uploaded</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Site Settings -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Site Configuration</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="posts_per_page" class="form-label">Posts per Page</label>
                                <input type="number" class="form-control" id="posts_per_page" name="posts_per_page" 
                                       value="<?= getSetting($settings, 'posts_per_page', '10') ?>" min="1" max="50">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Features</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="enable_comments" name="enable_comments" value="1"
                                           <?= getSetting($settings, 'enable_comments', '1') == '1' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="enable_comments">
                                        Enable Comments
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="enable_toxic_detection" name="enable_toxic_detection" value="1"
                                           <?= getSetting($settings, 'enable_toxic_detection', '1') == '1' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="enable_toxic_detection">
                                        Enable Toxic Comment Detection
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save me-1"></i>
                    Save Settings
                </button>
                <a href="<?= url('admin/dashboard') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>
                    Back to Dashboard
                </a>
            </div>

        </form>

    </section>
</section>

<?php
// Helper function để lấy setting value
function getSetting($settings, $key, $default = '') {
    foreach ($settings as $setting) {
        if ($setting['setting_key'] == $key) {
            return htmlspecialchars($setting['setting_value']);
        }
    }
    return htmlspecialchars($default);
}
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Preview logo upload
    document.getElementById('logo').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.querySelector('.img-thumbnail');
                if (preview) {
                    preview.src = e.target.result;
                }
            };
            reader.readAsDataURL(file);
        }
    });
});
</script>

<?php require BASE_PATH . '/app/Views/admin/layouts/footer.php'; ?>