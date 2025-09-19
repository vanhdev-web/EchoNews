<?php
require_once(BASE_PATH . "/template/admin/layouts/head-tag.php");
?>

<!-- Dashboard Header -->
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4">
    <h1 class="h4 text-primary">
        <i class="fas fa-tachometer-alt me-2"></i>
        Dashboard Overview
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-download"></i> Export
            </button>
        </div>
        <button type="button" class="btn btn-primary btn-sm">
            <i class="fas fa-sync-alt"></i> Refresh
        </button>
    </div>
</div>

<!-- Views Chart Section - Moved to top -->
<div class="row g-4 mb-5">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 pb-0">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="card-title text-dark mb-0">
                        <i class="fas fa-chart-line text-primary me-2"></i>
                        Views Analytics - Last 7 Days
                    </h5>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-download me-1"></i>Export
                        </button>
                        <button class="btn btn-primary btn-sm" onclick="refreshChart()" id="refreshBtn">
                            <i class="fas fa-sync-alt me-1"></i>Refresh
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div style="position: relative; height: 400px;">
                    <canvas id="viewsChart"></canvas>
                </div>
                <div class="row mt-4">
                    <div class="col-md-3 text-center">
                        <div class="border rounded p-3">
                            <h6 class="text-primary mb-1"><?= number_format($postsViews['SUM(view)']) ?></h6>
                            <small class="text-muted">Total Views</small>
                        </div>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="border rounded p-3">
                            <h6 class="text-success mb-1">
                                <?php 
                                $avgViews = $postCount['COUNT(*)'] > 0 ? round($postsViews['SUM(view)'] / $postCount['COUNT(*)']) : 0;
                                echo number_format($avgViews); 
                                ?>
                            </h6>
                            <small class="text-muted">Avg per Post</small>
                        </div>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="border rounded p-3">
                            <h6 class="text-info mb-1"><?= number_format($postCount['COUNT(*)']) ?></h6>
                            <small class="text-muted">Total Posts</small>
                        </div>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="border rounded p-3">
                            <h6 class="text-warning mb-1">
                                <?php
                                // Tính views hôm nay từ posts được update hôm nay
                                $todayViews = 0;
                                $today = date('Y-m-d');
                                
                                // Lấy tổng views của posts được cập nhật hôm nay
                                $todayViewsResult = $db->select("
                                    SELECT SUM(view) as total_views 
                                    FROM posts 
                                    WHERE DATE(updated_at) = '$today' AND view > 0
                                ")->fetch();
                                
                                $todayViews = $todayViewsResult['total_views'] ? (int)$todayViewsResult['total_views'] : 0;
                                
                                // Nếu không có, lấy từ created_at
                                if($todayViews == 0) {
                                    foreach($viewsLast7Days as $dayData) {
                                        if($dayData['date'] == $today) {
                                            $todayViews = $dayData['total_views'];
                                            break;
                                        }
                                    }
                                }
                                
                                echo number_format($todayViews);
                                ?>
                            </h6>
                            <small class="text-muted">Today's Views</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row g-4 mb-5">
    <div class="col-sm-6 col-xl-3">
        <a href="<?= url('admin/category') ?>" class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100 card-hover">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-gradient bg-primary text-white rounded-3 p-3">
                                <i class="fas fa-clipboard-list fa-lg"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title text-dark mb-1">Categories</h6>
                            <div class="d-flex align-items-center">
                                <h4 class="text-primary mb-0 me-2"><?= $categoryCount['COUNT(*)']; ?></h4>
                                <span class="badge bg-primary bg-opacity-10 text-primary">Total</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="fas fa-arrow-right me-1"></i>
                            Manage Categories
                        </small>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-sm-6 col-xl-3">
        <a href="<?= url('admin/user') ?>" class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100 card-hover">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-gradient bg-success text-white rounded-3 p-3">
                                <i class="fas fa-users fa-lg"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title text-dark mb-1">Users</h6>
                            <div class="d-flex align-items-center">
                                <h4 class="text-success mb-0 me-2"><?= $userCount['COUNT(*)'] + $adminCount['COUNT(*)']; ?></h4>
                                <span class="badge bg-success bg-opacity-10 text-success">Total</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 d-flex justify-content-between">
                        <small class="text-muted">
                            <i class="fas fa-users-cog me-1"></i>
                            Admin: <?= $adminCount['COUNT(*)']; ?>
                        </small>
                        <small class="text-muted">
                            <i class="fas fa-user me-1"></i>
                            Users: <?= $userCount['COUNT(*)']; ?>
                        </small>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-sm-6 col-xl-3">
        <a href="<?= url('admin/post') ?>" class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100 card-hover">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-gradient bg-info text-white rounded-3 p-3">
                                <i class="fas fa-newspaper fa-lg"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title text-dark mb-1">Articles</h6>
                            <div class="d-flex align-items-center">
                                <h4 class="text-info mb-0 me-2"><?= $postCount['COUNT(*)']; ?></h4>
                                <span class="badge bg-info bg-opacity-10 text-info">Published</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="fas fa-eye me-1"></i>
                            Total Views: <?= number_format($postsViews['SUM(view)']); ?>
                        </small>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-sm-6 col-xl-3">
        <a href="<?= url('admin/comment') ?>" class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100 card-hover">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-gradient bg-warning text-white rounded-3 p-3">
                                <i class="fas fa-comments fa-lg"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title text-dark mb-1">Comments</h6>
                            <div class="d-flex align-items-center">
                                <h4 class="text-warning mb-0 me-2"><?= $commentsCount['COUNT(*)']; ?></h4>
                                <span class="badge bg-warning bg-opacity-10 text-warning">Total</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 d-flex justify-content-between">
                        <small class="text-muted">
                            <i class="fas fa-eye-slash me-1"></i>
                            Pending: <?= $commentsUnseenCount['COUNT(*)']; ?>
                        </small>
                        <small class="text-muted">
                            <i class="fas fa-check-circle me-1"></i>
                            Approved: <?= $commentsApprovedCount['COUNT(*)']; ?>
                        </small>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- Analytics Tables -->
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 pb-0">
                <div class="d-flex align-items-center justify-content-between">
                    <h6 class="card-title text-dark mb-0">
                        <i class="fas fa-chart-line text-primary me-2"></i>
                        Most Viewed Posts
                    </h6>
                    <a href="<?= url('admin/post') ?>" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th scope="col" class="border-0 text-muted small">#</th>
                                <th scope="col" class="border-0 text-muted small">Article</th>
                                <th scope="col" class="border-0 text-muted small text-end">Views</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($postsWithView as $key => $post) { ?>
                            <tr>
                                <td class="border-0">
                                    <span class="text-primary fw-bold"><?= $key + 1 ?></span>
                                </td>
                                <td class="border-0">
                                    <a href="<?= url('admin/post') ?>" class="text-dark text-decoration-none">
                                        <div class="fw-medium"><?= strlen($post['title']) > 40 ? substr($post['title'], 0, 40) . '...' : $post['title'] ?></div>
                                    </a>
                                </td>
                                <td class="border-0 text-end">
                                    <span class="badge bg-primary rounded-pill"><?= number_format($post['view']) ?></span>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 pb-0">
                <div class="d-flex align-items-center justify-content-between">
                    <h6 class="card-title text-dark mb-0">
                        <i class="fas fa-comment-dots text-success me-2"></i>
                        Most Commented Posts
                    </h6>
                    <a href="<?= url('admin/post') ?>" class="btn btn-outline-success btn-sm">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th scope="col" class="border-0 text-muted small">#</th>
                                <th scope="col" class="border-0 text-muted small">Article</th>
                                <th scope="col" class="border-0 text-muted small text-end">Comments</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($postsComments as $key => $post) { ?>
                            <tr>
                                <td class="border-0">
                                    <span class="text-success fw-bold"><?= $key + 1 ?></span>
                                </td>
                                <td class="border-0">
                                    <a href="<?= url('admin/post') ?>" class="text-dark text-decoration-none">
                                        <div class="fw-medium"><?= strlen($post['title']) > 40 ? substr($post['title'], 0, 40) . '...' : $post['title'] ?></div>
                                    </a>
                                </td>
                                <td class="border-0 text-end">
                                    <span class="badge bg-success rounded-pill"><?= $post['comment_count'] ?></span>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 pb-0">
                <div class="d-flex align-items-center justify-content-between">
                    <h6 class="card-title text-dark mb-0">
                        <i class="fas fa-comments text-warning me-2"></i>
                        Recent Comments
                    </h6>
                    <a href="<?= url('admin/comment') ?>" class="btn btn-outline-warning btn-sm">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th scope="col" class="border-0 text-muted small">#</th>
                                <th scope="col" class="border-0 text-muted small">User</th>
                                <th scope="col" class="border-0 text-muted small">Comment</th>
                                <th scope="col" class="border-0 text-muted small text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($lastComments as $key => $comment) { ?>
                            <tr>
                                <td class="border-0">
                                    <span class="text-warning fw-bold"><?= $key + 1 ?></span>
                                </td>
                                <td class="border-0">
                                    <a href="<?= url('admin/comment') ?>" class="text-dark text-decoration-none">
                                        <div class="fw-medium"><?= $comment['username'] ?></div>
                                    </a>
                                </td>
                                <td class="border-0">
                                    <div class="text-muted small">
                                        <?= strlen($comment['comment']) > 30 ? substr($comment['comment'], 0, 30) . '...' : $comment['comment'] ?>
                                    </div>
                                </td>
                                <td class="border-0 text-center">
                                    <?php if($comment['status'] == 'approved') { ?>
                                        <span class="badge bg-success rounded-pill">Approved</span>
                                    <?php } elseif($comment['status'] == 'pending') { ?>
                                        <span class="badge bg-warning rounded-pill">Pending</span>
                                    <?php } else { ?>
                                        <span class="badge bg-secondary rounded-pill"><?= ucfirst($comment['status']) ?></span>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Debug: Log data from PHP
console.log('Views data from PHP:', <?= json_encode($viewsLast7Days) ?>);

// Chart configuration
const ctx = document.getElementById('viewsChart').getContext('2d');

// Prepare data from PHP
<?php
$chartLabels = [];
$chartData = [];

// Tạo mảng 7 ngày gần đây
for($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-{$i} days"));
    $formattedDate = date('M j', strtotime($date));
    $chartLabels[] = $formattedDate;
    
    // Tìm views cho ngày này
    $dayViews = 0;
    foreach($viewsLast7Days as $dayData) {
        if($dayData['date'] == $date) {
            $dayViews = (int)$dayData['total_views'];
            break;
        }
    }
    $chartData[] = $dayViews;
}
?>

const chartConfig = {
    labels: <?= json_encode($chartLabels) ?>,
    datasets: [{
        label: 'Daily Views',
        data: <?= json_encode($chartData) ?>,
        borderColor: 'rgb(54, 162, 235)',
        backgroundColor: 'rgba(54, 162, 235, 0.1)',
        borderWidth: 3,
        fill: true,
        tension: 0.4,
        pointBackgroundColor: 'rgb(54, 162, 235)',
        pointBorderColor: '#fff',
        pointBorderWidth: 2,
        pointRadius: 6,
        pointHoverRadius: 8
    }]
};

console.log('Chart config:', chartConfig);

// Chart options
const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        title: {
            display: true,
            text: 'Daily Views Trend',
            font: {
                size: 16,
                weight: 'bold'
            }
        },
        legend: {
            display: false
        },
        tooltip: {
            backgroundColor: 'rgba(0, 0, 0, 0.8)',
            titleColor: '#fff',
            bodyColor: '#fff',
            borderColor: 'rgb(54, 162, 235)',
            borderWidth: 1,
            cornerRadius: 8,
            displayColors: false,
            callbacks: {
                label: function(context) {
                    return 'Views: ' + context.parsed.y.toLocaleString();
                }
            }
        }
    },
    scales: {
        y: {
            beginAtZero: true,
            grid: {
                color: 'rgba(0, 0, 0, 0.1)'
            },
            ticks: {
                font: {
                    size: 12
                },
                callback: function(value) {
                    return value.toLocaleString();
                }
            }
        },
        x: {
            grid: {
                display: false
            },
            ticks: {
                font: {
                    size: 12
                }
            }
        }
    },
    interaction: {
        intersect: false,
        mode: 'index'
    },
    animation: {
        duration: 2000,
        easing: 'easeInOutQuart'
    }
};

// Create chart
const viewsChart = new Chart(ctx, {
    type: 'line',
    data: chartConfig,
    options: chartOptions
});

// Refresh chart function
function refreshChart() {
    const refreshBtn = document.getElementById('refreshBtn');
    const originalContent = refreshBtn.innerHTML;
    refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Refreshing...';
    refreshBtn.disabled = true;
    
    // Hiển thị thông báo đang cập nhật
    const toast = document.createElement('div');
    toast.className = 'position-fixed top-0 end-0 p-3';
    toast.style.zIndex = '9999';
    toast.innerHTML = `
        <div class="toast show" role="alert">
            <div class="toast-header">
                <i class="fas fa-chart-line text-primary me-2"></i>
                <strong class="me-auto">Chart Update</strong>
                <small>just now</small>
            </div>
            <div class="toast-body">
                Refreshing view data...
            </div>
        </div>
    `;
    document.body.appendChild(toast);
    
    // Simulate refresh (reload page to get fresh data)
    setTimeout(() => {
        location.reload();
    }, 1500);
}

// Auto refresh every 30 seconds if user is viewing a post
setInterval(() => {
    // Chỉ auto refresh nếu user không tương tác trong 10 giây
    if (document.visibilityState === 'visible') {
        const lastActivity = localStorage.getItem('lastActivity') || Date.now();
        if (Date.now() - lastActivity > 30000) { // 30 seconds
            console.log('Auto refreshing chart data...');
            // Refresh dữ liệu mà không reload page
            location.reload();
        }
    }
}, 30000);

// Track user activity
document.addEventListener('mousemove', () => {
    localStorage.setItem('lastActivity', Date.now());
});

document.addEventListener('keypress', () => {
    localStorage.setItem('lastActivity', Date.now());
});

// Add hover effects to stats cards
document.querySelectorAll('.border.rounded.p-3').forEach(card => {
    card.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-2px)';
        this.style.boxShadow = '0 4px 8px rgba(0,0,0,0.1)';
        this.style.transition = 'all 0.3s ease';
    });
    
    card.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0)';
        this.style.boxShadow = 'none';
    });
});
</script>

<?php
require_once(BASE_PATH . "/template/admin/layouts/footer.php");
?>