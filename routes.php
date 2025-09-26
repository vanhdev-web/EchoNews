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
$router->delete('/admin/posts/{id}', 'Admin\PostController', 'destroy');
$router->post('/admin/posts/{id}/toggle-status', 'Admin\PostController', 'toggleStatus');

// Admin Comments
$router->get('/admin/comments', 'Admin\CommentController', 'index');
$router->post('/admin/comments/{id}/approve', 'Admin\CommentController', 'approve');
$router->post('/admin/comments/{id}/reject', 'Admin\CommentController', 'reject');
$router->delete('/admin/comments/{id}', 'Admin\CommentController', 'destroy');
$router->post('/admin/comments/bulk-action', 'Admin\CommentController', 'bulkAction');

// Admin Categories
$router->get('/admin/categories', 'Admin\CategoryController', 'index');
$router->get('/admin/categories/create', 'Admin\CategoryController', 'create');
$router->post('/admin/categories', 'Admin\CategoryController', 'store');
$router->get('/admin/categories/{id}/edit', 'Admin\CategoryController', 'edit');
$router->post('/admin/categories/{id}', 'Admin\CategoryController', 'update');
$router->delete('/admin/categories/{id}', 'Admin\CategoryController', 'destroy');

// Admin Users
$router->get('/admin/users', 'Admin\UserController', 'index');
$router->get('/admin/users/create', 'Admin\UserController', 'create');
$router->post('/admin/users', 'Admin\UserController', 'store');
$router->get('/admin/users/{id}/edit', 'Admin\UserController', 'edit');
$router->post('/admin/users/{id}', 'Admin\UserController', 'update');
$router->delete('/admin/users/{id}', 'Admin\UserController', 'destroy');

// Admin Settings
$router->get('/admin/settings', 'Admin\SettingController', 'index');
$router->post('/admin/settings', 'Admin\SettingController', 'update');

// API routes
$router->get('/api/search', 'HomeController', 'handleAjaxSearch');

return $router;