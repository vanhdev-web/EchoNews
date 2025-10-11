<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register - Online News</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #1a1a1a;
            --secondary: #666666;
            --white: #ffffff;
            --bg-light: #fafafa;
            --border-light: #e5e5e5;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: var(--bg-light);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        
        .auth-container {
            background: var(--white);
            border-radius: 12px;
            border: 1px solid var(--border-light);
            overflow: hidden;
            max-width: 900px;
            margin: 2rem auto;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
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
                        <div class="col-lg-6">
                            <div class="auth-content">
                                <div class="text-center mb-4">
                                    <h3 class="fw-bold text-dark mb-2">Create Your Account</h3>
                                    <p class="text-muted">Join our community and stay updated with the latest news</p>
                                </div>

                                <form action="<?= url('register/store') ?>" method="post" class="needs-validation" novalidate>
                                    <?php if(flash('register_error')) { ?>
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        <?= flash('register_error') ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                    <?php } ?>

                                    <div class="mb-3">
                                        <label for="username" class="form-label fw-semibold">
                                            <i class="fas fa-user me-2"></i>Username
                                        </label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="username" 
                                               name="username" 
                                               placeholder="Enter your username"
                                               value="<?= isset($_POST['username']) ? $_POST['username'] : '' ?>"
                                               required>
                                        <div class="invalid-feedback">
                                            Please provide a username.
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label fw-semibold">
                                            <i class="fas fa-envelope me-2"></i>Email Address
                                        </label>
                                        <input type="email" 
                                               class="form-control" 
                                               id="email" 
                                               name="email" 
                                               placeholder="Enter your email"
                                               value="<?= isset($_POST['email']) ? $_POST['email'] : '' ?>"
                                               required>
                                        <div class="invalid-feedback">
                                            Please provide a valid email address.
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="password" class="form-label fw-semibold">
                                            <i class="fas fa-lock me-2"></i>Password
                                        </label>
                                        <div class="position-relative">
                                            <input type="password" 
                                                   class="form-control" 
                                                   id="password" 
                                                   name="password" 
                                                   placeholder="Create a strong password"
                                                   minlength="8"
                                                   required>
                                            <button type="button" 
                                                    class="btn btn-outline-secondary position-absolute end-0 top-0 h-100 border-0"
                                                    id="togglePassword">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        <div class="invalid-feedback">
                                            Password must be at least 8 characters long.
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100 mb-3">
                                        <i class="fas fa-user-plus me-2"></i>Register
                                    </button>

                                    <div class="text-center">
                                        <p class="mb-0">
                                            Already have an account? 
                                            <a href="<?= url('login') ?>" class="text-decoration-none fw-semibold">
                                                Sign In
                                            </a>
                                        </p>
                                    </div>
                                </form>

                                <div class="text-center mt-4 pt-3 border-top">
                                    <a href="<?= url('/') ?>" class="text-decoration-none text-muted">
                                        <i class="fas fa-arrow-left me-2"></i>Back to Homepage
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-6 auth-image">
                            <div class="text-center">
                                <div class="news-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <h2 class="fw-bold mb-3">Join Our Community</h2>
                                <p class="mb-4">
                                    Get access to exclusive content, personalized news feed, and connect with fellow readers.
                                </p>
                                <div class="d-flex justify-content-center gap-4">
                                    <div class="text-center">
                                        <h4 class="fw-bold">Free</h4>
                                        <small>Registration</small>
                                    </div>
                                    <div class="text-center">
                                        <h4 class="fw-bold">Daily</h4>
                                        <small>Updates</small>
                                    </div>
                                    <div class="text-center">
                                        <h4 class="fw-bold">Fast</h4>
                                        <small>Loading</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
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