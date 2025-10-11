<!DOCTYPE html>
<html lang="en">

<head>
    <title>Forgot Password - Online News</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Minimalistic Auth CSS -->
    <style>
        :root {
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
            max-width: 500px;
            margin: 2rem auto;
            box-shadow: 0 4px 12px rgba(26, 26, 26, 0.1);
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
        
        .icon-circle {
            width: 80px;
            height: 80px;
            background: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            margin: 0 auto 1.5rem;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="auth-container">
                    <div class="auth-content">
                        <div class="text-center mb-4">
                            <div class="icon-circle">
                                <i class="fas fa-key"></i>
                            </div>
                            <h3 class="fw-bold text-dark mb-2">Forgot Password?</h3>
                            <p class="text-muted">Enter your email to reset your password</p>
                        </div>

                        <!-- Flash Messages -->
                        <?php if(flash('forgot_error')) { ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <?= flash('forgot_error') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php } ?>

                        <!-- Forgot Password Form -->
                        <form action="<?= url('forgot/request') ?>" method="post" class="needs-validation" novalidate>
                            <!-- Email Field -->
                            <div class="mb-4">
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

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-primary w-100 mb-4">
                                <i class="fas fa-paper-plane me-2"></i>Send Reset Link
                            </button>

                            <!-- Back to Login -->
                            <div class="text-center">
                                <p class="mb-0">
                                    Remember your password? 
                                    <a href="<?= url('login') ?>" class="text-decoration-none fw-semibold">
                                        Back to Login
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
    </script>
</body>
</html>
