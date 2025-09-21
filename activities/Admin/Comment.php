<?php

namespace Admin;

use database\DataBase;

class Comment extends Admin{ 
        
        public function index()
        {
                $db = new DataBase();
                $commentsResult = $db->select('SELECT comments.*, posts.title AS post_title, users.email AS email FROM comments LEFT JOIN posts ON comments.post_id = posts.id LEFT JOIN users ON comments.user_id = users.id ORDER BY `id` DESC');
                
                // Convert PDOStatement to array
                $comments = [];
                if ($commentsResult) {
                    while ($row = $commentsResult->fetch()) {
                        $comments[] = $row;
                    }
                }
                
                $unseenCommentsResult = $db->select('SELECT * FROM comments WHERE status = ?', ['unseen']);
                if ($unseenCommentsResult) {
                    while ($comment = $unseenCommentsResult->fetch()) {
                        $db->update('comments', $comment['id'], ['status'], ['seen']);
                    }
                }
                
                // Calculate statistics for the template
                $pendingCount = 0;
                $approvedCount = 0;
                foreach ($comments as $comment) {
                    if ($comment['status'] == 'pending') {
                        $pendingCount++;
                    } elseif ($comment['status'] == 'approved') {
                        $approvedCount++;
                    }
                }
                
                require_once(BASE_PATH . '/template/admin/comments/index.php');
        }

        public function changeStatus($id)
        {
                $db = new DataBase();
                $comment = $db->select('SELECT * FROM comments WHERE id = ?;', [$id])->fetch();
                if(empty($comment)){
                        $this->redirectBack();  
                }
                
                // Handle different status transitions
                if($comment['status'] == 'pending'){
                        // Approve pending comment (from toxic detection)
                        $db->update('comments', $id, ['status'], ['approved']);
                }
                elseif($comment['status'] == 'seen'){
                        // Approve seen comment
                        $db->update('comments', $id, ['status'], ['approved']);
                }
                else{
                        // Mark as seen for review
                        $db->update('comments', $id, ['status'], ['seen']);
                }
                $this->redirectBack();
        }

        /**
         * Approve a comment (force approve regardless of AI prediction)
         */
        public function approve($id)
        {
                $db = new DataBase();
                $comment = $db->select('SELECT * FROM comments WHERE id = ?;', [$id])->fetch();
                if(empty($comment)){
                        $this->redirectBack();  
                }
                
                // Force approve - admin override
                $db->update('comments', $id, ['status'], ['approved']);
                $this->redirectBack();
        }

        /**
         * Unapprove a comment (move back to pending for review)
         */
        public function unapprove($id)
        {
                $db = new DataBase();
                $comment = $db->select('SELECT * FROM comments WHERE id = ?;', [$id])->fetch();
                if(empty($comment)){
                        $this->redirectBack();  
                }
                
                // Move back to pending status
                $db->update('comments', $id, ['status'], ['pending']);
                $this->redirectBack();
        }

        /**
         * Delete a comment permanently
         */
        public function delete($id)
        {
                $db = new DataBase();
                $comment = $db->select('SELECT * FROM comments WHERE id = ?;', [$id])->fetch();
                if(empty($comment)){
                        $this->redirectBack();  
                }
                
                // Delete comment
                $db->delete('comments', $id);
                $this->redirectBack();
        }

     
     

}