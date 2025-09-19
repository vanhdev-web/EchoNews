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

                // Tăng view count khi người dùng xem bài viết
                if($post) {
                        $currentViews = $post['view'];
                        $newViews = $currentViews + 1;
                        $db->update('posts', $id, ['view'], [$newViews]);
                        
                        // Cập nhật view count trong biến $post để hiển thị đúng
                        $post['view'] = $newViews;
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
                                $db->insert('comments', ['user_id', 'post_id', 'comment'], [$_SESSION['user'], $request['post_id'], $request['comment']]);
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