<?php

namespace App;

use Database\DataBase;

class Home{

        public function index()
        {
                $db = new DataBase();

                $setting = $db->select('SELECT * FROM websetting')->fetch();

                $categories = $db->select('SELECT categories.*, (SELECT COUNT(*) FROM posts WHERE posts.cat_id = categories.id AND posts.status = 1) AS count FROM categories ORDER BY name ASC')->fetchAll();

                // Check if this is an AJAX search request
                if (isset($_GET['ajax']) && $_GET['ajax'] == '1' && isset($_GET['search'])) {
                    $searchTerm = $_GET['search'];
                    $searchResults = $this->searchPosts($searchTerm);
                    
                    // Return JSON response
                    header('Content-Type: application/json');
                    echo json_encode($searchResults);
                    exit;
                }

                // Check if search parameter exists
                $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
                
                if (!empty($searchTerm)) {
                    // Perform search
                    $searchResults = $this->searchPosts($searchTerm);
                    
                    // Prepare data for search results page
                    $lastPosts = $searchResults;
                    $isSearchPage = true;
                } else {
                    // Normal homepage logic - lấy nhiều bài hơn để đảm bảo đủ cho các categories
                    $lastPosts = $db->select('SELECT posts.*, (SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.id) AS comments_count, (SELECT username FROM users WHERE users.id = posts.user_id) AS username, (SELECT name FROM categories WHERE categories.id = posts.cat_id) AS category FROM posts WHERE status = 1 ORDER BY created_at DESC LIMIT 0, 200')->fetchAll();
                    $isSearchPage = false;
                }

                // Debug categories
                // var_dump("Categories loaded: ", count($categories));

                $topSelectedPosts = $db->select('SELECT posts.*, (SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.id) AS comments_count, (SELECT username FROM users WHERE users.id = posts.user_id) AS username, (SELECT name FROM categories WHERE categories.id = posts.cat_id) AS category FROM posts WHERE posts.selected = 2 AND status = 1 ORDER BY created_at DESC LIMIT 0, 3')->fetchAll();

                $breakingNews = $db->select('SELECT * FROM posts WHERE breaking_news = 2 AND status = 1 ORDER BY created_at DESC LIMIT 0,1')->fetch();
                
                $bodyBanner = $db->select('SELECT * FROM banners ORDER BY created_at DESC LIMIT 0,1')->fetch();
                $sidebarBanner = $db->select('SELECT * FROM banners ORDER BY created_at DESC LIMIT 0,1')->fetch();

                $popularPosts =$db->select('SELECT posts.*, (SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.id) AS comments_count, (SELECT username FROM users WHERE users.id = posts.user_id) AS username, (SELECT name FROM categories WHERE categories.id = posts.cat_id) AS category FROM posts WHERE status = 1 ORDER BY view DESC LIMIT 0, 3')->fetchAll();

                $mostCommentsPosts =$db->select('SELECT posts.*, (SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.id) AS comments_count, (SELECT username FROM users WHERE users.id = posts.user_id) AS username, (SELECT name FROM categories WHERE categories.id = posts.cat_id) AS category FROM posts WHERE status = 1 ORDER BY comments_count DESC LIMIT 0, 4')->fetchAll();

                require_once (BASE_PATH . '/template/app/index.php');
        }

        public function searchPosts($searchTerm)
        {
                $db = new DataBase();
                
                // Search in title, body, and summary
                $searchQuery = "
                    SELECT posts.*, 
                           (SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.id) AS comments_count, 
                           (SELECT username FROM users WHERE users.id = posts.user_id) AS username, 
                           (SELECT name FROM categories WHERE categories.id = posts.cat_id) AS category 
                    FROM posts 
                    WHERE posts.status = 1 
                    AND (posts.title LIKE ? OR posts.body LIKE ? OR posts.summary LIKE ?)
                    ORDER BY posts.created_at DESC 
                    LIMIT 0, 50
                ";
                
                $searchPattern = '%' . $searchTerm . '%';
                return $db->select($searchQuery, [$searchPattern, $searchPattern, $searchPattern])->fetchAll();
        }


        public function show($id)
        {

                $db = new DataBase();

                $post =$db->select('SELECT posts.*, (SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.id) AS comments_count, (SELECT username FROM users WHERE users.id = posts.user_id) AS username, (SELECT name FROM categories WHERE categories.id = posts.cat_id) AS category FROM posts WHERE id = ?', [$id])->fetch();

                // Tăng view count chỉ một lần mỗi user cho mỗi bài viết (sử dụng cookie + session)
                if($post) {
                        // Khởi tạo session tracking nếu chưa có
                        if (!isset($_SESSION['viewed_posts'])) {
                                $_SESSION['viewed_posts'] = [];
                        }
                        
                        // Tạo cookie key cho bài viết này (tồn tại 24 giờ)
                        $viewCookieName = 'viewed_post_' . $id;
                        $hasViewedInCookie = isset($_COOKIE[$viewCookieName]);
                        $hasViewedInSession = in_array($id, $_SESSION['viewed_posts']);
                        
                        // Chỉ tăng view nếu chưa xem bài này trong 24h qua (cookie) hoặc session hiện tại
                        if (!$hasViewedInCookie && !$hasViewedInSession) {
                                $currentViews = $post['view'];
                                $newViews = $currentViews + 1;
                                $db->update('posts', $id, ['view'], [$newViews]);
                                
                                // Đánh dấu bài viết này đã được xem
                                $_SESSION['viewed_posts'][] = $id;
                                
                                // Tạo cookie tồn tại 24 giờ để tránh spam view
                                setcookie($viewCookieName, '1', time() + (24 * 60 * 60), '/');
                                
                                // Cập nhật view count trong biến $post để hiển thị đúng
                                $post['view'] = $newViews;
                        }
                }

                $comments = $db->select("SELECT *, (SELECT username FROM users WHERE users.id = comments.user_id) AS username FROM comments WHERE post_id = ? AND status = 'approved'", [$id])->fetchAll();



                $setting = $db->select('SELECT * FROM websetting')->fetch();

                $categories = $db->select('SELECT categories.*, (SELECT COUNT(*) FROM posts WHERE posts.cat_id = categories.id) AS count FROM categories ORDER BY name ASC')->fetchAll();


                
                $sidebarBanner = $db->select('SELECT * FROM banners ORDER BY created_at DESC LIMIT 0,1')->fetch();

                $popularPosts =$db->select('SELECT posts.*, (SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.id) AS comments_count, (SELECT username FROM users WHERE users.id = posts.user_id) AS username, (SELECT name FROM categories WHERE categories.id = posts.cat_id) AS category FROM posts  ORDER BY view DESC LIMIT 0, 3')->fetchAll();

                $mostCommentsPosts =$db->select('SELECT posts.*, (SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.id) AS comments_count, (SELECT username FROM users WHERE users.id = posts.user_id) AS username, (SELECT name FROM categories WHERE categories.id = posts.cat_id) AS category FROM posts  ORDER BY comments_count DESC LIMIT 0, 4')->fetchAll();

                require_once (BASE_PATH . '/template/app/show-post.php');

        }


        public function commentStore($request){
               
                if(isset($_SESSION['user']))
                {
                        if($_SESSION['user'] != null)
                        {
                                $db = new DataBase();
                                
                                // Load toxic comment detector
                                require_once BASE_PATH . '/lib/ToxicCommentDetector.php';
                                $toxicDetector = new \ToxicCommentDetector();
                                
                                // Check comment toxicity
                                $toxicResult = $toxicDetector->checkComment($request['comment']);
                                
                                // Determine comment status based on toxicity
                                if ($toxicResult['should_approve']) {
                                    // Non-toxic comment - auto approve and show immediately
                                    $status = 'approved';
                                    $message = [
                                        'type' => 'success',
                                        'text' => 'Bình luận của bạn đã được đăng thành công!'
                                    ];
                                } else {
                                    // Toxic comment - save but mark as pending for admin review
                                    $status = 'pending';
                                    $toxicPercent = round($toxicResult['toxic_probability'] * 100, 1);
                                    $message = [
                                        'type' => 'warning',
                                        'text' => "Bình luận của bạn đang được kiểm duyệt (phát hiện nội dung nhạy cảm: {$toxicPercent}%). Bình luận sẽ hiển thị sau khi admin phê duyệt."
                                    ];
                                }
                                
                                // Insert comment with appropriate status
                                $db->insert('comments', 
                                    ['user_id', 'post_id', 'comment', 'status'], 
                                    [$_SESSION['user'], $request['post_id'], $request['comment'], $status]
                                );
                                
                                // Set message for user
                                $_SESSION['comment_message'] = $message;
                                
                                $this->redirectBack();
                        }
                        else{
                                $this->redirectBack();
                        }
                }
                else{
                        $this->redirectBack();
                }

        }



        public function category($id)
        {
            $db = new DataBase();
            
            // Enhanced query to ensure we get the correct category
            $category = $db->select("SELECT * FROM categories WHERE id = ?", [$id])->fetch();
            
            // If no category found, redirect to home
            if (!$category) {
                header("Location: " . url('/'));
                exit;
            }

            $topSelectedPosts = $db->select("SELECT posts.*, (SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.id) AS comments_count, (SELECT username FROM users WHERE users.id = posts.user_id) AS username , (SELECT name FROM categories WHERE categories.id = posts.cat_id) AS category  FROM posts where posts.selected = 2 AND posts.status = 1 ORDER BY `created_at` DESC LIMIT 0,3 ;")->fetchAll();
    
            
            // Lấy tất cả bài viết published trong category này - không giới hạn số lượng
            $categoryPosts = $db->select("SELECT posts.*, (SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.id) AS comments_count, (SELECT username FROM users WHERE users.id = posts.user_id) AS username , (SELECT name FROM categories WHERE categories.id = posts.cat_id) AS category FROM posts WHERE cat_id = ? AND posts.status = 1 ORDER BY `created_at` DESC ;", [$id])->fetchAll();
    
            $popularPosts = $db->select("SELECT posts.*, (SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.id) AS comments_count, (SELECT username FROM users WHERE users.id = posts.user_id) AS username , (SELECT name FROM categories WHERE categories.id = posts.cat_id) AS category FROM posts WHERE posts.status = 1 ORDER BY `view` DESC LIMIT 0,3 ;")->fetchAll();
    
            $breakingNews = $db->select("SELECT * FROM posts WHERE breaking_news = 2 AND posts.status = 1 ORDER BY `created_at` DESC LIMIT 0,1 ;")->fetch();
    
            $mostCommentsPosts = $db->select("SELECT posts.*, (SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.id) AS comments_count, (SELECT username FROM users WHERE users.id = posts.user_id) AS username  , (SELECT name FROM categories WHERE categories.id = posts.cat_id) AS category FROM posts WHERE posts.status = 1 ORDER BY `comments_count` DESC LIMIT 0,4 ;")->fetchAll();
            
            $categories = $db->select('SELECT categories.*, (SELECT COUNT(*) FROM posts WHERE posts.cat_id = categories.id AND posts.status = 1) AS count FROM categories ORDER BY name ASC')->fetchAll();

            $setting= $db->select("SELECT * FROM `websetting`;")->fetch();
    
            $sidebarBanner= $db->select("SELECT * FROM `banners` LIMIT 0,1;")->fetch();
            $bodyBanner= $db->select("SELECT * FROM `banners` ORDER BY created_at DESC LIMIT 0,1;")->fetch();
    
            require_once (BASE_PATH . "/template/app/show-category.php");
        }


        protected function redirectBack(){
                header("Location: " . $_SERVER['HTTP_REFERER']);
                exit;
        }

}