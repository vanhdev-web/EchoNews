<?php

        require_once (BASE_PATH . '/template/admin/layouts/head-tag.php');

// Convert PDOStatement to array if needed
if ($posts instanceof PDOStatement) {
    $postsArray = $posts->fetchAll(PDO::FETCH_ASSOC);
} else {
    $postsArray = $posts;
}
?>

<!-- Page Header -->
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4">
    <h1 class="h4 text-primary">
        <i class="fas fa-newspaper me-2"></i>
        Articles Management
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-filter"></i> Filter
            </button>
            <button type="button" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-search"></i> Search
            </button>
        </div>
        <a role="button" href="<?= url('admin/post/create') ?>" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i>
            Create New Article
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center p-3">
                <div class="text-primary mb-2">
                    <i class="fas fa-newspaper fa-2x"></i>
                </div>
                <h6 class="card-title mb-1">Total Articles</h6>
                <h4 class="text-primary mb-0"><?= count($postsArray) ?></h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center p-3">
                <div class="text-success mb-2">
                    <i class="fas fa-check-circle fa-2x"></i>
                </div>
                <h6 class="card-title mb-1">Published</h6>
                <h4 class="text-success mb-0"><?= count(array_filter($postsArray, function($p) { return $p['status'] == 1; })) ?></h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center p-3">
                <div class="text-warning mb-2">
                    <i class="fas fa-clock fa-2x"></i>
                </div>
                <h6 class="card-title mb-1">Draft</h6>
                <h4 class="text-warning mb-0"><?= count(array_filter($postsArray, function($p) { return $p['status'] == 0; })) ?></h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center p-3">
                <div class="text-info mb-2">
                    <i class="fas fa-eye fa-2x"></i>
                </div>
                <h6 class="card-title mb-1">Total Views</h6>
                <h4 class="text-info mb-0"><?= number_format(array_sum(array_column($postsArray, 'view'))) ?></h4>
            </div>
        </div>
    </div>
</div>

<!-- Articles Table -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0">
        <div class="d-flex align-items-center justify-content-between">
            <h6 class="card-title mb-0">
                <i class="fas fa-list text-primary me-2"></i>
                Articles List
            </h6>
            <div class="d-flex gap-2">
                <select class="form-select form-select-sm" style="width: auto;">
                    <option>All Status</option>
                    <option>Published</option>
                    <option>Draft</option>
                </select>
                <input type="search" class="form-control form-control-sm" placeholder="Search articles..." style="width: 200px;">
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col" class="border-0">#</th>
                        <th scope="col" class="border-0">Article</th>
                        <th scope="col" class="border-0">Category</th>
                        <th scope="col" class="border-0">Author</th>
                        <th scope="col" class="border-0 text-center">Views</th>
                        <th scope="col" class="border-0 text-center">Status</th>
                        <th scope="col" class="border-0 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
            <?php foreach ($postsArray as $key => $post) { ?>
            <tr>
                <td class="border-0">
                    <span class="text-primary fw-bold"><?= $key + 1 ?></span>
                </td>
                <td class="border-0">
                    <div class="d-flex align-items-center">
                        <?php if(!empty($post['image'])) { ?>
                        <img src="<?= asset($post['image']) ?>" alt="<?= $post['title'] ?>" class="rounded me-3" width="50" height="35" style="object-fit: cover;">
                        <?php } else { ?>
                        <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 35px;">
                            <i class="fas fa-image text-muted"></i>
                        </div>
                        <?php } ?>
                        <div>
                            <div class="fw-medium text-dark"><?= strlen($post['title']) > 50 ? substr($post['title'], 0, 50) . '...' : $post['title'] ?></div>
                            <small class="text-muted"><?= strlen($post['summary']) > 80 ? substr($post['summary'], 0, 80) . '...' : $post['summary'] ?></small>
                        </div>
                    </div>
                </td>
                <td class="border-0">
                    <span class="badge bg-light text-dark">Cat ID: <?= $post['cat_id'] ?></span>
                </td>
                <td class="border-0">
                    <span class="badge bg-info bg-opacity-10 text-info">User ID: <?= $post['user_id'] ?></span>
                </td>
                <td class="border-0 text-center">
                    <span class="badge bg-primary rounded-pill"><?= number_format($post['view']) ?></span>
                </td>
                <td class="border-0 text-center">
                    <div class="d-flex flex-column gap-1 align-items-center">
                        <?php if($post['breaking_news'] == 2) { ?>
                            <span class="badge bg-danger rounded-pill">
                                <i class="fas fa-bolt me-1"></i>Breaking
                            </span>
                        <?php } ?>
                        
                        <?php if($post['selected'] == 2) { ?>
                            <span class="badge bg-primary rounded-pill">
                                <i class="fas fa-star me-1"></i>Featured
                            </span>
                        <?php } ?>
                        
                        <span class="badge bg-success rounded-pill">
                            <i class="fas fa-check me-1"></i>Published
                        </span>
                    </div>
                </td>
                <td class="border-0 text-center">
                    <div class="btn-group-vertical" role="group">
                        <div class="btn-group mb-1" role="group">
                            <a href="<?= url('admin/post/breaking-news/' . $post['id']) ?>" 
                               class="btn btn-outline-<?= $post['breaking_news'] == 2 ? 'danger' : 'secondary' ?> btn-sm" 
                               title="<?= $post['breaking_news'] == 2 ? 'Remove Breaking News' : 'Add Breaking News' ?>">
                                <i class="fas fa-bolt"></i>
                            </a>
                            <a href="<?= url('admin/post/selected/' . $post['id']) ?>" 
                               class="btn btn-outline-<?= $post['selected'] == 2 ? 'primary' : 'secondary' ?> btn-sm" 
                               title="<?= $post['selected'] == 2 ? 'Remove Featured' : 'Add Featured' ?>">
                                <i class="fas fa-star"></i>
                            </a>
                        </div>
                        <div class="btn-group" role="group">
                            <a href="<?= url('admin/post/edit/' . $post['id']) ?>" class="btn btn-outline-primary btn-sm" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="<?= url('admin/post/delete/' . $post['id']) ?>" 
                               class="btn btn-outline-danger btn-sm" 
                               title="Delete"
                               onclick="return confirm('Are you sure you want to delete this article?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </div>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
</div>
</div>

<?php
require_once (BASE_PATH . '/template/admin/layouts/footer.php')
?>