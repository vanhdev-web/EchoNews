<?php

namespace App\Controllers\Admin;

use App\Core\BaseController;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;

/**
 * Admin Comment Controller
 * Quản lý bình luận trong admin panel
 */
class CommentController extends BaseController
{
    protected $commentModel;
    protected $postModel;
    protected $userModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->requireAdmin();
        
        $this->commentModel = new Comment();
        $this->postModel = new Post();
        $this->userModel = new User();
    }
    
    /**
     * Danh sách bình luận
     */
    public function index()
    {
        // Lấy tham số phân trang
        $page = (int)($this->getInput('page') ?? 1);
        $limit = 15;
        $offset = ($page - 1) * $limit;
        
        // Lấy tham số tìm kiếm và lọc
        $search = $this->getInput('search');
        $status = $this->getInput('status');
        $postId = $this->getInput('post_id');
        
        // Lấy danh sách bình luận với phân trang
        $comments = $this->commentModel->getAdminList($offset, $limit, $search, $status, $postId);
        $totalComments = $this->commentModel->getAdminCount($search, $status, $postId);
        $totalPages = ceil($totalComments / $limit);
        
        // Lấy danh sách posts để filter
        $posts = $this->postModel->getAll();
        
        return $this->render('admin.comments.index', [
            'comments' => $comments,
            'posts' => $posts,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalComments' => $totalComments,
            'search' => $search,
            'status' => $status,
            'postId' => $postId
        ]);
    }
    
    /**
     * Hiển thị chi tiết bình luận
     */
    public function show($id)
    {
        $comment = $this->commentModel->getById($id);
        
        if (!$comment) {
            return $this->redirect('admin/comments', 'Comment not found', 'error');
        }
        
        return $this->render('admin.comment.show', [
            'comment' => $comment
        ]);
    }
    
    /**
     * Duyệt bình luận (approve)
     */
    public function approve($id)
    {
        try {
            $comment = $this->commentModel->getById($id);
            
            if (!$comment) {
                return $this->redirect('admin/comments', 'Comment not found', 'error');
            }
            
            $result = $this->commentModel->updateStatus($id, 'approved');
            
            if ($result) {
                return $this->redirect('admin/comments', 'Comment approved successfully', 'success');
            } else {
                return $this->redirect('admin/comments', 'Failed to approve comment', 'error');
            }
            
        } catch (\Exception $e) {
            return $this->redirect('admin/comments', 'Error: ' . $e->getMessage(), 'error');
        }
    }
    
    /**
     * Hủy duyệt bình luận (unapprove)
     */
    public function unapprove($id)
    {
        try {
            $comment = $this->commentModel->getById($id);
            
            if (!$comment) {
                return $this->redirect('admin/comments', 'Comment not found', 'error');
            }
            
            $result = $this->commentModel->updateStatus($id, 'unseen');
            
            if ($result) {
                return $this->redirect('admin/comments', 'Comment moved to pending review', 'success');
            } else {
                return $this->redirect('admin/comments', 'Failed to unapprove comment', 'error');
            }
            
        } catch (\Exception $e) {
            return $this->redirect('admin/comments', 'Error: ' . $e->getMessage(), 'error');
        }
    }
    
    /**
     * Từ chối bình luận (reject)
     */
    public function reject($id)
    {
        try {
            $comment = $this->commentModel->getById($id);
            
            if (!$comment) {
                return $this->redirect('admin/comments', 'Comment not found', 'error');
            }
            
            $result = $this->commentModel->updateStatus($id, 'seen');
            
            if ($result) {
                return $this->redirect('admin/comments', 'Comment rejected successfully', 'success');
            } else {
                return $this->redirect('admin/comments', 'Failed to reject comment', 'error');
            }
            
        } catch (\Exception $e) {
            return $this->redirect('admin/comments', 'Error: ' . $e->getMessage(), 'error');
        }
    }
    
    /**
     * Đánh dấu đã xem
     */
    public function markAsSeen($id)
    {
        try {
            $comment = $this->commentModel->getById($id);
            
            if (!$comment) {
                return $this->redirect('admin/comments', 'Comment not found', 'error');
            }
            
            $result = $this->commentModel->updateStatus($id, 'seen');
            
            if ($result) {
                return $this->redirect('admin/comments', 'Comment marked as seen', 'success');
            } else {
                return $this->redirect('admin/comments', 'Failed to mark comment', 'error');
            }
            
        } catch (\Exception $e) {
            return $this->redirect('admin/comments', 'Error: ' . $e->getMessage(), 'error');
        }
    }
    
    /**
     * Xóa bình luận
     */
    public function delete($id)
    {
        try {
            $comment = $this->commentModel->getById($id);
            
            if (!$comment) {
                return $this->redirect('admin/comments', 'Comment not found', 'error');
            }
            
            $result = $this->commentModel->delete($id);
            
            if ($result) {
                return $this->redirect('admin/comments', 'Comment deleted successfully', 'success');
            } else {
                return $this->redirect('admin/comments', 'Failed to delete comment', 'error');
            }
            
        } catch (\Exception $e) {
            return $this->redirect('admin/comments', 'Error: ' . $e->getMessage(), 'error');
        }
    }
    
    /**
     * Alias for delete method (for compatibility)
     */
    public function destroy($id)
    {
        return $this->delete($id);
    }
    
    /**
     * Bulk actions (approve/reject/delete multiple comments)
     */
    public function bulkAction()
    {
        try {
            $action = $this->getInput('bulk_action');
            $commentIds = $this->getInput('comment_ids');
            
            if (empty($action) || empty($commentIds) || !is_array($commentIds)) {
                return $this->redirect('admin/comments', 'Invalid bulk action request', 'error');
            }
            
            $count = 0;
            foreach ($commentIds as $id) {
                switch ($action) {
                    case 'approve':
                        if ($this->commentModel->updateStatus($id, 'approved')) $count++;
                        break;
                    case 'reject':
                        if ($this->commentModel->updateStatus($id, 'seen')) $count++;
                        break;
                    case 'delete':
                        if ($this->commentModel->delete($id)) $count++;
                        break;
                }
            }
            
            if ($count > 0) {
                $message = "Successfully processed {$count} comment(s)";
                return $this->redirect('admin/comments', $message, 'success');
            } else {
                return $this->redirect('admin/comments', 'No comments were processed', 'warning');
            }
            
        } catch (\Exception $e) {
            return $this->redirect('admin/comments', 'Error: ' . $e->getMessage(), 'error');
        }
    }
}