<?php

use App\Core\Router;

$router = new Router();

// Public routes
$router->get('/', 'HomeController', 'index');
$router->get('/home', 'HomeController', 'index');
$router->get('/show-post/{id}', 'HomeController', 'showPost');
$router->get('/show-category/{id}', 'HomeController', 'showCategory');

// Auth routes
$router->get('/login', 'AuthController', 'loginForm');
$router->post('/login', 'AuthController', 'login');
$router->get('/register', 'AuthController', 'registerForm');
$router->post('/register', 'AuthController', 'register');
// Alternative register routes for testing
$router->get('/signup', 'AuthController', 'registerForm');
$router->post('/signup', 'AuthController', 'register');
$router->post('/logout', 'AuthController', 'logout');
$router->get('/logout', 'AuthController', 'logout');

// Password reset routes
$router->get('/forgot-password', 'AuthController', 'forgotPasswordForm');
$router->post('/forgot-password', 'AuthController', 'forgotPassword');
$router->get('/reset-password', 'AuthController', 'resetPasswordForm');
$router->post('/reset-password', 'AuthController', 'resetPassword');

// Comment routes (authenticated)
$router->post('/comment-store', 'HomeController', 'commentStore');

// Admin routes
$router->get('/admin', 'Admin\DashboardController', 'index');
$router->get('/admin/dashboard', 'Admin\DashboardController', 'index');
$router->get('/admin/system-info', 'Admin\DashboardController', 'systemInfo');
$router->get('/admin/analytics-api', 'Admin\DashboardController', 'analyticsApi');

// Admin Posts
$router->get('/admin/posts', 'Admin\PostController', 'index');
$router->get('/admin/posts/create', 'Admin\PostController', 'create');
$router->post('/admin/posts', 'Admin\PostController', 'store');
$router->get('/admin/posts/{id}', 'Admin\PostController', 'show');
$router->get('/admin/posts/{id}/edit', 'Admin\PostController', 'edit');
$router->post('/admin/posts/{id}', 'Admin\PostController', 'update');
$router->post('/admin/posts/{id}/delete', 'Admin\PostController', 'destroy');
$router->post('/admin/posts/{id}/toggle-status', 'Admin\PostController', 'toggleStatus');

// Post feature toggles (breaking news and featured)
$router->get('/admin/post/breaking-news/{id}', 'Admin\PostController', 'toggleBreakingNews');
$router->get('/admin/post/selected/{id}', 'Admin\PostController', 'toggleFeatured');

// Admin Comments
$router->get('/admin/comments', 'Admin\CommentController', 'index');
$router->post('/admin/comments/{id}/approve', 'Admin\CommentController', 'approve');
$router->post('/admin/comments/{id}/unapprove', 'Admin\CommentController', 'unapprove');
$router->post('/admin/comments/{id}/reject', 'Admin\CommentController', 'reject');
$router->post('/admin/comments/{id}/delete', 'Admin\CommentController', 'destroy');
$router->post('/admin/comments/bulk-action', 'Admin\CommentController', 'bulkAction');

// Admin Categories
$router->get('/admin/categories', 'Admin\CategoryController', 'index');
$router->get('/admin/categories/create', 'Admin\CategoryController', 'create');
$router->post('/admin/categories', 'Admin\CategoryController', 'store');
$router->get('/admin/categories/{id}/edit', 'Admin\CategoryController', 'edit');
$router->post('/admin/categories/{id}', 'Admin\CategoryController', 'update');
$router->post('/admin/categories/{id}/delete', 'Admin\CategoryController', 'destroy');

// Admin Users
$router->get('/admin/users', 'Admin\UserController', 'index');
$router->get('/admin/users/create', 'Admin\UserController', 'create');
$router->post('/admin/users', 'Admin\UserController', 'store');
$router->get('/admin/users/{id}/edit', 'Admin\UserController', 'edit');
$router->post('/admin/users/{id}', 'Admin\UserController', 'update');
$router->delete('/admin/users/{id}', 'Admin\UserController', 'destroy');
$router->get('/admin/users/{id}/promote', 'Admin\UserController', 'promote');
$router->get('/admin/users/{id}/demote', 'Admin\UserController', 'demote');

// Admin Banners
$router->get('/admin/banners', 'Admin\BannerController', 'index');
$router->get('/admin/banners/create', 'Admin\BannerController', 'create');
$router->post('/admin/banners', 'Admin\BannerController', 'store');
$router->get('/admin/banners/{id}', 'Admin\BannerController', 'show');
$router->get('/admin/banners/{id}/edit', 'Admin\BannerController', 'edit');
$router->post('/admin/banners/{id}', 'Admin\BannerController', 'update');
$router->delete('/admin/banners/{id}', 'Admin\BannerController', 'destroy');

// Legacy Banner routes (singular - redirect to plural)
$router->get('/admin/banner', 'Admin\BannerController', 'index');
$router->get('/admin/banner/create', 'Admin\BannerController', 'create');
$router->post('/admin/banner', 'Admin\BannerController', 'store');
$router->get('/admin/banner/edit/{id}', 'Admin\BannerController', 'edit');
$router->get('/admin/banner/delete/{id}', 'Admin\BannerController', 'destroy');

// Admin Menus
$router->get('/admin/menus', 'Admin\MenuController', 'index');
$router->get('/admin/menus/create', 'Admin\MenuController', 'create');
$router->post('/admin/menus', 'Admin\MenuController', 'store');
$router->get('/admin/menus/{id}', 'Admin\MenuController', 'show');
$router->get('/admin/menus/{id}/edit', 'Admin\MenuController', 'edit');
$router->post('/admin/menus/{id}', 'Admin\MenuController', 'update');
$router->delete('/admin/menus/{id}', 'Admin\MenuController', 'destroy');
$router->get('/admin/menus/tree', 'Admin\MenuController', 'getMenuTree');

// Legacy Menu routes (singular - redirect to plural)  
$router->get('/admin/menu', 'Admin\MenuController', 'index');
$router->get('/admin/menu/create', 'Admin\MenuController', 'create');
$router->post('/admin/menu', 'Admin\MenuController', 'store');
$router->get('/admin/menu/edit/{id}', 'Admin\MenuController', 'edit');
$router->get('/admin/menu/delete/{id}', 'Admin\MenuController', 'destroy');

// Admin Settings
$router->get('/admin/settings', 'Admin\SettingController', 'index');
$router->post('/admin/settings', 'Admin\SettingController', 'update');

// Legacy web-setting routes (redirect to settings)
$router->get('/admin/web-setting', 'Admin\SettingController', 'index');
$router->get('/admin/web-setting/set', 'Admin\SettingController', 'index');
$router->post('/admin/web-setting/store', 'Admin\SettingController', 'update');

// API routes
$router->get('/api/search', 'HomeController', 'handleAjaxSearch');

return $router;