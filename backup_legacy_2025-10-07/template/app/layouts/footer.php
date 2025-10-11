    <!-- Footer -->
    <footer class="bg-dark text-white py-5 mt-5">
        <div class="container">
            <div class="row">
                <!-- Popular News -->
                <div class="col-lg-6 col-md-6 mb-4">
                    <h5 class="text-uppercase fw-bold mb-3 text-white">
                        <i class="fas fa-fire text-danger me-2"></i>Popular News
                    </h5>
                    <ul class="list-unstyled">
                        <?php foreach ($popularPosts as $popularPost) { ?>
                        <li class="mb-2">
                            <a href="<?= url('show-post/' . $popularPost['id']) ?>" 
                               class="text-light text-decoration-none small">
                                <i class="fas fa-chevron-right me-2 text-primary"></i>
                                <?= substr($popularPost['title'], 0, 50) ?>...
                            </a>
                        </li>
                        <?php } ?>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div class="col-lg-6 col-md-12 mb-4">
                    <h5 class="text-uppercase fw-bold mb-3 text-white">
                        <i class="fas fa-envelope text-warning me-2"></i>Contact Us
                    </h5>
                    <div class="mb-3">
                        <p class="mb-2 text-white">
                            <i class="fas fa-phone me-2 text-success"></i>
                            +1 958-787-1176
                        </p>
                        <p class="mb-2 text-white">
                            <i class="fas fa-envelope me-2 text-info"></i>
                            admin@onlinenews.com
                        </p>
                    </div>
                    
                    <!-- Social Media -->
                    <h6 class="fw-bold mb-2 text-white">Follow Us</h6>
                    <div class="d-flex gap-2">
                        <a href="#" class="btn btn-outline-light btn-sm">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="btn btn-outline-light btn-sm">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="btn btn-outline-light btn-sm">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="btn btn-outline-light btn-sm">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="#" class="btn btn-outline-light btn-sm">
                            <i class="fab fa-telegram-plane"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Footer Bottom -->
            <hr class="my-4">
            <div class="row">
                <div class="col-12 text-center">
                    <p class="mb-0 text-white">
                        <i class="far fa-copyright me-1"></i>
                        <script>document.write(new Date().getFullYear());</script> 
                        Online News Site. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Minimal JavaScript -->
    <script>
        // Simple back to top functionality
        const backToTopBtn = document.createElement('button');
        backToTopBtn.innerHTML = '<i class="fas fa-chevron-up"></i>';
        backToTopBtn.className = 'btn btn-primary position-fixed';
        backToTopBtn.style.cssText = 'bottom: 20px; right: 20px; display: none; z-index: 1050; border-radius: 50%; width: 50px; height: 50px;';
        document.body.appendChild(backToTopBtn);

        window.addEventListener('scroll', () => {
            backToTopBtn.style.display = window.pageYOffset > 300 ? 'block' : 'none';
        });

        backToTopBtn.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    </script>

</body>
</html>