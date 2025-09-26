# EchoNews - MVC Architecture Documentation

## 🏗️ **Cấu trúc MVC mới**

EchoNews đã được refactor thành kiến trúc MVC (Model-View-Controller) chuẩn PSR-4 với các tính năng hiện đại:

### 📁 **Cấu trúc thư mục**

```
OnlineNewsSite/
├── app/
│   ├── Controllers/         # Controllers xử lý logic
│   │   ├── HomeController.php
│   │   ├── AuthController.php
│   │   └── Admin/          # Admin controllers
│   │       ├── DashboardController.php
│   │       ├── PostController.php
│   │       ├── CommentController.php
│   │       ├── CategoryController.php
│   │       └── UserController.php
│   ├── Models/             # Models tương tác database
│   │   ├── Post.php
│   │   ├── User.php
│   │   ├── Comment.php
│   │   └── Category.php
│   ├── Views/              # Views hiển thị giao diện
│   │   ├── layouts/        # Layout templates
│   │   ├── home/           # Public views
│   │   ├── auth/           # Authentication views
│   │   ├── admin/          # Admin panel views
│   │   └── components/     # Reusable components
│   └── Core/               # Core framework classes
│       ├── BaseModel.php
│       ├── BaseController.php
│       ├── ViewEngine.php
│       ├── Router.php
│       └── Autoloader.php
├── database/               # Database classes (kept for compatibility)
├── public/                 # Static assets
├── routes.php              # Route definitions
├── helpers.php             # Global helper functions
├── index_new_mvc.php       # New MVC entry point
└── index.php              # Original entry point (legacy)
```

## 🎯 **Core Components**

### 1. **BaseModel** (`app/Core/BaseModel.php`)
ORM-like functionality với các methods:
- `find($id)` - Tìm record theo ID
- `all()` - Lấy tất cả records
- `where($column, $operator, $value)` - Query với điều kiện
- `create($data)` - Tạo record mới
- `update($id, $data)` - Cập nhật record
- `delete($id)` - Xóa record
- `paginate($page, $perPage)` - Phân trang

### 2. **BaseController** (`app/Core/BaseController.php`)
Base class cho controllers với:
- `render($view, $data)` - Render views
- `redirect($url, $message)` - Redirect với flash message
- `json($data)` - JSON response
- `validate($data, $rules)` - Input validation
- `requireAuth()` - Authentication middleware
- `requireAdmin()` - Admin middleware

### 3. **ViewEngine** (`app/Core/ViewEngine.php`)
Template engine với:
- Layout system
- Component rendering
- Helper functions
- Flash messages
- CSRF protection

### 4. **Router** (`app/Core/Router.php`)
Modern routing với:
- RESTful routes
- Route parameters
- Middleware support
- Named routes

## 📝 **Models**

### **Post Model**
```php
$postModel = new \App\Models\Post();

// Get posts with relations
$posts = $postModel->getWithRelations(10);

// Get post with full details
$post = $postModel->getWithFullDetails($id);

// Get posts by category
$related = $postModel->getByCategory($categoryId, $excludeId, 3);

// Search posts
$results = $postModel->search('keyword', 10);

// Increment view
$postModel->incrementView($id);
```

### **User Model**
```php
$userModel = new \App\Models\User();

// Create user with hashed password
$userModel->createUser([
    'username' => 'john_doe',
    'email' => 'john@example.com',
    'password' => 'password123'
]);

// Verify credentials
$user = $userModel->verifyCredentials('email', 'password');

// Check if email exists
$exists = $userModel->emailExists('email@example.com');
```

### **Comment Model**
```php
$commentModel = new \App\Models\Comment();

// Create comment with toxic detection
$commentModel->createWithToxicCheck([
    'user_id' => 1,
    'post_id' => 1,
    'comment' => 'Great article!'
]);

// Get comments for post
$comments = $commentModel->getByPost($postId, 'approved');

// Approve/reject comments
$commentModel->approve($id);
$commentModel->reject($id);
```

## 🛣️ **Routing**

### **Route Definition** (`routes.php`)
```php
// Public routes
$router->get('/', 'HomeController', 'index');
$router->get('/show-post/{id}', 'HomeController', 'showPost');

// Auth routes
$router->get('/login', 'AuthController', 'loginForm');
$router->post('/login', 'AuthController', 'login');

// Admin routes (with middleware)
$router->get('/admin/posts', 'Admin\PostController', 'index');
$router->post('/admin/posts', 'Admin\PostController', 'store');
```

### **URL Generation**
```php
// In views or controllers
echo url('show-post/' . $post['id']);
echo asset('images/logo.png');
```

## 🎨 **Views**

### **Layout System**
```php
// In controller
return $this->render('home.index', [
    'posts' => $posts,
    'categories' => $categories
]);
```

### **View File** (`app/Views/home/index.php`)
```php
<h1>Welcome to EchoNews</h1>

<?php foreach ($posts as $post): ?>
    <article>
        <h2><?= htmlspecialchars($post['title']) ?></h2>
        <p><?= str_limit($post['summary'], 100) ?></p>
        <a href="<?= url('show-post/' . $post['id']) ?>">Read More</a>
    </article>
<?php endforeach; ?>
```

## 🔒 **Authentication & Authorization**

### **Middleware Usage**
```php
class AdminController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->requireAdmin(); // Auto-check admin permission
    }
}
```

### **Helper Functions**
```php
// Check authentication
if (is_auth()) {
    echo "Welcome " . auth_username();
}

// Check admin
if (is_admin()) {
    echo '<a href="/admin">Admin Panel</a>';
}
```

## 🛡️ **Security Features**

### **CSRF Protection**
```php
// In forms
echo csrf_field();

// Manual verification
if (verify_csrf($_POST['csrf_token'])) {
    // Process form
}
```

### **Input Sanitization**
```php
$cleanData = sanitize($_POST);
$title = sanitize($_POST['title']);
```

### **Validation**
```php
$errors = $this->validate($_POST, [
    'title' => 'required|min:5|max:200',
    'email' => 'required|email',
    'password' => 'required|min:6'
]);
```

## 🔧 **Helper Functions**

### **Common Helpers**
```php
// URL generation
url('path/to/page')
asset('css/style.css')

// Flash messages
flash('message')
flash('type')

// Old input (for forms)
old('field_name', 'default_value')

// Date formatting
format_date($date, 'Y-m-d')
time_ago($datetime)

// String helpers
str_limit($text, 100)
str_slug($text)
```

## 📊 **Database Operations**

### **Query Builder Style**
```php
// Using models
$posts = $postModel->where('status', 1)
                  ->orderBy('created_at', 'DESC')
                  ->limit(10);

// Custom queries
$results = $postModel->query("SELECT * FROM posts WHERE title LIKE ?", ['%keyword%']);
```

## 🚀 **Migration Guide**

### **Switching to MVC**

1. **Use new entry point:**
   ```
   http://localhost/OnlineNewsSite/index_new_mvc.php
   ```

2. **Update routes:**
   - Old: `index.php?page=show-post&id=1`
   - New: `show-post/1`

3. **Controller methods:**
   ```php
   // Old way
   $home = new App\Home();
   $home->index();
   
   // New way
   $controller = new App\Controllers\HomeController();
   $controller->index();
   ```

## ✅ **Benefits của MVC Architecture**

1. **Separation of Concerns**: Logic tách biệt rõ ràng
2. **Reusability**: Components có thể tái sử dụng
3. **Maintainability**: Dễ bảo trì và debug
4. **Scalability**: Dễ mở rộng tính năng
5. **Testing**: Dễ viết unit tests
6. **PSR-4 Compliant**: Chuẩn PHP modern
7. **Security**: Built-in security features
8. **Performance**: Optimized autoloading

## 🔄 **Next Steps**

1. Migrate views từ `template/` sang `app/Views/`
2. Update existing URLs để sử dụng new routing
3. Add middleware cho advanced features
4. Implement caching layer
5. Add API endpoints
6. Unit testing setup

---

**Note**: Legacy system vẫn hoạt động thông qua `index.php` để đảm bảo backward compatibility.