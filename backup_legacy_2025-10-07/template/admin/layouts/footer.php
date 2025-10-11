
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- CKEditor 5 removed to avoid duplicate fields -->
    
    <script>
        // CKEditor initialization removed to prevent duplicate UI
        // Using plain textareas instead
        
        // Sidebar toggle for mobile
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('show');
        });

        // Active menu highlighting
        const currentUrl = window.location.pathname;
        const menuLinks = document.querySelectorAll('.nav-link');
        
        menuLinks.forEach(link => {
            if (link.getAttribute('href') && currentUrl.includes(link.getAttribute('href').split('/').pop())) {
                link.classList.add('active');
            }
        });

        // Auto-hide alerts
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                if (alert.classList.contains('alert-success') || alert.classList.contains('alert-info')) {
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-20px)';
                    setTimeout(() => alert.remove(), 300);
                }
            });
        }, 5000);

        // Table row hover effects
        document.querySelectorAll('.table tbody tr').forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.backgroundColor = 'rgba(30, 64, 175, 0.05)';
            });
            
            row.addEventListener('mouseleave', function() {
                this.style.backgroundColor = '';
            });
        });

        // Form validation styling
        document.querySelectorAll('.needs-validation').forEach(form => {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            });
        });

        // Animate cards on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-fade-in');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.card').forEach(card => {
            observer.observe(card);
        });

        // Confirmation for delete actions
        document.querySelectorAll('a[href*="delete"], button[onclick*="delete"]').forEach(element => {
            element.addEventListener('click', function(event) {
                if (!confirm('Are you sure you want to delete this item?')) {
                    event.preventDefault();
                }
            });
        });

        // Image preview
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('image-preview');
                    if (preview) {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    }
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // File input styling
        document.querySelectorAll('input[type="file"]').forEach(input => {
            input.addEventListener('change', function() {
                const fileName = this.files[0]?.name || 'Choose file...';
                const label = this.nextElementSibling;
                if (label) {
                    label.textContent = fileName;
                }
                
                // Preview image if it's an image input
                if (this.accept && this.accept.includes('image')) {
                    previewImage(this);
                }
            });
        });

        // Status toggle
        function toggleStatus(id, type) {
            if (confirm('Are you sure you want to change the status?')) {
                // Add your AJAX call here
                console.log(`Toggle ${type} status for ID: ${id}`);
            }
        }
    </script>

</body>
</html>