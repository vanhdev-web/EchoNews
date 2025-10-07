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
        <a role="button" href="<?= url('admin/post/create') ?>" class="btn btn-primary" style="background-color: #0d6efd; border-color: #0d6efd; color: white;">
            <i class="fas fa-plus me-2"></i>
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
        <div class="card border-0 shadow-sm bg-light">
            <div class="card-body text-center p-3">
                <div class="text-primary mb-2">
                    <i class="fas fa-plus-circle fa-2x"></i>
                </div>
                <h6 class="card-title mb-2">Quick Action</h6>
                <a href="<?= url('admin/post/create') ?>" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i>
                    New Article
                </a>
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
                <select id="statusFilter" class="form-select form-select-sm" style="width: auto;">
                    <option value="">All Status</option>
                    <option value="published">Published</option>
                    <option value="draft">Draft</option>
                    <option value="breaking">Breaking News</option>
                    <option value="featured">Featured</option>
                </select>
                <input id="searchInput" type="search" class="form-control form-control-sm" placeholder="Search articles..." style="width: 200px;">
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table id="articlesTable" class="table table-hover align-middle mb-0">
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
            
            <!-- Empty State when no articles -->
            <?php if (empty($postsArray)) { ?>
            <tr>
                <td colspan="7" class="text-center py-5">
                    <div class="py-4">
                        <i class="fas fa-newspaper text-muted mb-3" style="font-size: 3rem;"></i>
                        <h5 class="text-muted mb-3">No Articles Found</h5>
                        <p class="text-muted mb-4">You haven't created any articles yet. Start by creating your first article.</p>
                        <a href="<?= url('admin/post/create') ?>" class="btn btn-primary btn-lg">
                            <i class="fas fa-plus me-2"></i>
                            Create Your First Article
                        </a>
                    </div>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
</div>
</div>

<!-- Custom styles for create button -->
<style>
.btn-primary {
    background-color: #0d6efd !important;
    border-color: #0d6efd !important;
    color: white !important;
}

.btn-primary:hover {
    background-color: #0b5ed7 !important;
    border-color: #0a58ca !important;
    color: white !important;
}

.btn-primary:active,
.btn-primary:focus {
    background-color: #0a58ca !important;
    border-color: #0a53be !important;
    color: white !important;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25) !important;
}
</style>

<!-- Initialize tooltips and Search/Filter functionality -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap tooltips  
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Search and Filter functionality
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const articlesTable = document.getElementById('articlesTable');
    const tableRows = articlesTable.querySelectorAll('tbody tr');
    
    // Store original data for filtering
    let articlesData = [];
    tableRows.forEach((row, index) => {
        const cells = row.querySelectorAll('td');
        if (cells.length > 0) {
            const titleElement = cells[1]?.querySelector('.fw-medium');
            const statusBadges = cells[5]?.querySelectorAll('.badge') || [];
            
            articlesData.push({
                element: row,
                title: titleElement?.textContent.trim() || '',
                summary: cells[1]?.querySelector('small')?.textContent.trim() || '',
                category: cells[2]?.textContent.trim() || '',
                author: cells[3]?.textContent.trim() || '',
                views: cells[4]?.textContent.trim() || '0',
                hasBreaking: Array.from(statusBadges).some(badge => badge.textContent.includes('Breaking')),
                hasFeatured: Array.from(statusBadges).some(badge => badge.textContent.includes('Featured')),
                isPublished: Array.from(statusBadges).some(badge => badge.textContent.includes('Published'))
            });
        }
    });

    // Search function
    function performSearch() {
        const searchTerm = searchInput.value.toLowerCase();
        const statusValue = statusFilter.value;
        let visibleCount = 0;
        
        articlesData.forEach(item => {
            const matchesSearch = item.title.toLowerCase().includes(searchTerm) || 
                                item.summary.toLowerCase().includes(searchTerm) ||
                                item.category.toLowerCase().includes(searchTerm) ||
                                item.author.toLowerCase().includes(searchTerm);
            
            let matchesStatus = true;
            if (statusValue === 'published') {
                matchesStatus = item.isPublished;
            } else if (statusValue === 'draft') {
                matchesStatus = !item.isPublished;
            } else if (statusValue === 'breaking') {
                matchesStatus = item.hasBreaking;
            } else if (statusValue === 'featured') {
                matchesStatus = item.hasFeatured;
            }
            
            if (matchesSearch && matchesStatus) {
                item.element.style.display = '';
                visibleCount++;
            } else {
                item.element.style.display = 'none';
            }
        });
        
        // Update results counter
        updateResultsCounter(visibleCount, articlesData.length);
    }

    // Update results counter
    function updateResultsCounter(visible, total) {
        let counter = document.querySelector('.search-results-counter');
        if (!counter) {
            counter = document.createElement('small');
            counter.className = 'search-results-counter text-muted ms-2';
            document.querySelector('h6.card-title').appendChild(counter);
        }
        
        if (visible === total) {
            counter.textContent = `(${total} articles)`;
        } else {
            counter.textContent = `(${visible} of ${total} articles)`;
        }
    }

    // Clear search function
    function clearSearch() {
        searchInput.value = '';
        statusFilter.value = '';
        performSearch();
    }

    // Event listeners
    searchInput.addEventListener('input', performSearch);
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            performSearch();
        }
    });
    
    statusFilter.addEventListener('change', performSearch);

    // Add keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl+F to focus search
        if (e.ctrlKey && e.key === 'f') {
            e.preventDefault();
            searchInput.focus();
        }
        
        // Escape to clear search
        if (e.key === 'Escape') {
            clearSearch();
        }
    });

    // Initial counter setup
    updateResultsCounter(articlesData.length, articlesData.length);
    
    // Add some visual feedback
    searchInput.addEventListener('focus', function() {
        this.style.borderColor = '#0d6efd';
        this.style.boxShadow = '0 0 0 0.25rem rgba(13, 110, 253, 0.25)';
    });
    
    searchInput.addEventListener('blur', function() {
        this.style.borderColor = '';
        this.style.boxShadow = '';
    });
});
</script>

<?php
require_once (BASE_PATH . '/template/admin/layouts/footer.php')
?>