<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login - Online News</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Minimalistic Login CSS -->
    <style>
        :root {
            /* Minimalistic Color Palette */
            --primary: #1a1a1a;
            --secondary: #666666;
            --accent: #f5f5f5;
            --border: #e5e5e5;
            --white: #ffffff;
            --subtle: #fafafa;
            --text-primary: #1a1a1a;
            --text-secondary: #666666;
            --text-muted: #999999;
            --bg-white: #ffffff;
            --bg-light: #fafafa;
            --border-light: #e5e5e5;
            --border-medium: #d4d4d4;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: var(--bg-light);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        
        .auth-container {
            background: var(--bg-white);
            border-radius: 12px;
            border: 1px solid var(--border-light);
            overflow: hidden;
            max-width: 900px;
            margin: 2rem auto;
        }
        
        .auth-image {
            background: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            padding: 3rem;
        }
        
        .auth-content {
            padding: 3rem;
        }
        
        .form-control {
            border: 1px solid var(--border-light);
            border-radius: 6px;
            padding: 0.75rem;
            transition: border-color 0.2s ease;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(26, 26, 26, 0.1);
        }
        
        .btn-primary {
            background: var(--primary);
            border: none;
            border-radius: 6px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        
        .btn-primary:hover {
            background: var(--secondary);
            transform: translateY(-1px);
        }
        
        .news-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        
        @media (max-width: 768px) {
            .auth-container {
                margin: 1rem;
                border-radius: 8px;
            }
            
            .auth-content {
                padding: 2rem;
            }
            
            .auth-image {
                padding: 2rem;
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-11">
                <div class="auth-container">
                    <div class="row g-0">
                        <!-- Left Side - Image/Info -->
                        <div class="col-lg-6 auth-image">
                            <div class="text-center">
                                <div class="news-icon">
                                    <i class="fas fa-newspaper"></i>
                                </div>
                                <h2 class="fw-bold mb-3">Welcome Back!</h2>
                                <p class="mb-4">
                                    Stay connected with the latest news and updates.
                                </p>
                                <div class="d-flex justify-content-center gap-4">
                                    <div class="text-center">
                                        <h4 class="fw-bold">1000+</h4>
                                        <small>Articles</small>
                                    </div>
                                    <div class="text-center">
                                        <h4 class="fw-bold">50+</h4>
                                        <small>Categories</small>
                                    </div>
                                    <div class="text-center">
                                        <h4 class="fw-bold">24/7</h4>
                                        <small>Updates</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right Side - Login Form -->
                        <div class="col-lg-6">
                            <div class="auth-content">
                                <div class="text-center mb-4">
                                    <h3 class="fw-bold text-dark mb-2">Login to Your Account</h3>
                                    <p class="text-muted">Enter your credentials to access your dashboard</p>
                                </div>

                                <!-- Login Form -->
                                <form action="<?= url('check-login') ?>" method="post" class="needs-validation" novalidate>
                                    <!-- Flash Messages -->
                                    <?php if(flash('login_error')) { ?>
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        <?= flash('login_error') ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                    <?php } ?>

                                    <?php if(flash('register_success')) { ?>
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <i class="fas fa-check-circle me-2"></i>
                                        <?= flash('register_success') ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                    <?php } ?>

                                    <!-- Email Field -->
                                    <div class="mb-3">
                                        <label for="email" class="form-label fw-semibold">
                                            <i class="fas fa-envelope me-2"></i>Email Address
                                        </label>
                                        <input type="email" 
                                               class="form-control" 
                                               id="email" 
                                               name="email" 
                                               placeholder="Enter your email"
                                               required>
                                        <div class="invalid-feedback">
                                            Please provide a valid email address.
                                        </div>
                                    </div>

                                    <!-- Password Field -->
                                    <div class="mb-3">
                                        <label for="password" class="form-label fw-semibold">
                                            <i class="fas fa-lock me-2"></i>Password
                                        </label>
                                        <div class="position-relative">
                                            <input type="password" 
                                                   class="form-control" 
                                                   id="password" 
                                                   name="password" 
                                                   placeholder="Enter your password"
                                                   required>
                                            <button type="button" 
                                                    class="btn btn-outline-secondary position-absolute end-0 top-0 h-100 border-0"
                                                    id="togglePassword">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        <div class="invalid-feedback">
                                            Please provide your password.
                                        </div>
                                    </div>

                                    <!-- Remember Me & Forgot Password -->
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="remember">
                                            <label class="form-check-label" for="remember">
                                                Remember me
                                            </label>
                                        </div>
                                        <a href="<?= url('forgot') ?>" class="text-decoration-none">
                                            Forgot Password?
                                        </a>
                                    </div>

                                    <!-- Login Button -->
                                    <button type="submit" class="btn btn-primary w-100 mb-4">
                                        <i class="fas fa-sign-in-alt me-2"></i>Login
                                    </button>

                                    <!-- Register Link -->
                                    <div class="text-center">
                                        <p class="mb-0">
                                            Don't have an account? 
                                            <a href="<?= url('register') ?>" class="text-decoration-none fw-semibold">
                                                Create Account
                                            </a>
                                        </p>
                                    </div>
                                </form>

                                <!-- Back to Home -->
                                <div class="text-center mt-4 pt-3 border-top">
                                    <a href="<?= url('/') ?>" class="text-decoration-none text-muted">
                                        <i class="fas fa-arrow-left me-2"></i>Back to Homepage
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Form validation
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                var forms = document.getElementsByClassName('needs-validation');
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();

        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordField = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    </script>

</body>
</html>