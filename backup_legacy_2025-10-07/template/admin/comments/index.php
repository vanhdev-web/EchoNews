<?php

        require_once(BASE_PATH . '/template/admin/layouts/head-tag.php');

        // Calculate counts for statistics
        $pendingCount = 0;
        $approvedCount = 0;
        foreach ($comments as $comment) {
            if ($comment['status'] == 'pending') {
                $pendingCount++;
            } elseif ($comment['status'] == 'approved') {
                $approvedCount++;
            }
        }

?>

<style>
.comment-text {
    max-width: 300px;
    word-wrap: break-word;
    white-space: pre-wrap;
}
.badge {
    font-size: 0.75em;
}
.btn-group-vertical .btn {
    font-size: 0.75em;
    padding: 0.25rem 0.5rem;
}
.table td {
    vertical-align: middle;
}
.toxic-info {
    font-size: 0.8em;
}
.status-pending {
    background: linear-gradient(45deg, #fff3cd, #ffeaa7);
    border-left: 3px solid #ffc107;
}
.status-approved {
    background: linear-gradient(45deg, #d4edda, #c3e6cb);
    border-left: 3px solid #28a745;
}
</style>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h5">
            <i class="fas fa-comments"></i> Comment Management
            <small class="text-muted ms-2">AI-Powered Moderation</small>
        </h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <button type="button" class="btn btn-sm btn-outline-warning">
                    <i class="fas fa-hourglass-half me-1"></i>
                    Pending: <?= $pendingCount ?>
                </button>
                <button type="button" class="btn btn-sm btn-outline-success">
                    <i class="fas fa-check-circle me-1"></i>
                    Approved: <?= $approvedCount ?>
                </button>
            </div>
        </div>
    </div>
    <section class="table-responsive">
        <table class="table table-striped table-sm">
            <caption>List of comments</caption>
            <thead>
                <tr>
                    <th>#</th>
                    <th>User Email</th>
                    <th>Post Title</th>
                    <th>Comment</th>
                    <th>Status</th>
                    <th>Toxic Info</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($comments as $key => $comment) { ?>
                <tr class="<?= $comment['status'] == 'pending' ? 'status-pending' : ($comment['status'] == 'approved' ? 'status-approved' : '') ?>">
                    <td><strong><?= $key += 1 ?></strong></td>
                    <td>
                        <i class="fas fa-user me-1"></i>
                        <?= $comment['email'] ?>
                    </td>
                    <td>
                        <i class="fas fa-newspaper me-1"></i>
                        <span title="<?= htmlspecialchars($comment['post_title']) ?>">
                            <?= strlen($comment['post_title']) > 30 ? substr($comment['post_title'], 0, 30) . '...' : $comment['post_title'] ?>
                        </span>
                    </td>
                    <td>
                        <div class="comment-text" title="<?= htmlspecialchars($comment['comment']) ?>">
                            <?= strlen($comment['comment']) > 100 ? substr($comment['comment'], 0, 100) . '...' : $comment['comment'] ?>
                        </div>
                    </td>
                <td>
                    <?php if ($comment['status'] == 'approved') { ?>
                        <span class="badge bg-success">
                            <i class="fas fa-check-circle me-1"></i>Approved
                        </span>
                    <?php } elseif ($comment['status'] == 'pending') { ?>
                        <span class="badge bg-warning">
                            <i class="fas fa-hourglass-half me-1"></i>Pending Review (AI Flagged)
                        </span>
                    <?php } elseif ($comment['status'] == 'seen') { ?>
                        <span class="badge bg-info">
                            <i class="fas fa-eye me-1"></i>Under Review
                        </span>
                    <?php } else { ?>
                        <span class="badge bg-danger">
                            <i class="fas fa-flag me-1"></i>Needs Review
                        </span>
                    <?php } ?>
                </td>
                <td>
                    <?php if ($comment['status'] == 'pending') { ?>
                        <small class="text-warning">
                            <i class="fas fa-robot me-1"></i>AI Detected Potential Toxicity
                        </small>
                    <?php } elseif ($comment['status'] == 'approved') { ?>
                        <small class="text-success">
                            <i class="fas fa-check me-1"></i>Clean Content
                        </small>
                    <?php } else { ?>
                        <small class="text-muted">
                            <i class="fas fa-question me-1"></i>Awaiting Review
                        </small>
                    <?php } ?>
                </td>
                <td>
                    <div class="btn-group-vertical" role="group">
                        <?php if ($comment['status'] != 'approved') { ?>
                            <a role="button" class="btn btn-sm btn-success mb-1" 
                               href="<?= url('admin/comment/approve/' . $comment['id']) ?>"
                               title="Approve this comment">
                                <i class="fas fa-check me-1"></i>Approve
                            </a>
                        <?php } ?>
                        
                        <?php if ($comment['status'] == 'approved') { ?>
                            <a role="button" class="btn btn-sm btn-warning mb-1" 
                               href="<?= url('admin/comment/unapprove/' . $comment['id']) ?>"
                               title="Move to pending review">
                                <i class="fas fa-undo me-1"></i>Unapprove
                            </a>
                        <?php } ?>
                        
                        <a role="button" class="btn btn-sm btn-danger" 
                           href="<?= url('admin/comment/delete/' . $comment['id']) ?>"
                           onclick="return confirm('Are you sure you want to delete this comment?')"
                           title="Delete this comment">
                            <i class="fas fa-trash me-1"></i>Delete
                        </a>
                    </div>
                </td>
                </tr>

                <?php } ?>
                </tbody>
                </table>
                </section>




<?php

require_once(BASE_PATH . '/template/admin/layouts/footer.php');


?>