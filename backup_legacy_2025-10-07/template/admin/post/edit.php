<?php

        require_once (BASE_PATH . '/template/admin/layouts/head-tag.php')

?>



<section class="pt-3 pb-1 mb-2 border-bottom">
    <h1 class="h5">Edit Article</h1>
</section>

<section class="row my-3">
    <section class="col-12">

        <form method="post" action="<?= url('admin/post/update/' . $post['id']) ?>" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" class="form-control" id="title" name="title" value="<?= $post['title'] ?>" required autofocus>
                </div>

                <div class="form-group">
                    <label for="cat_id">Category</label>
                    <select name="cat_id" id="cat_id" class="form-control" required autofocus>
                <?php foreach ($categories as $category) { ?>
                 <option value="<?= $category['id'] ?>" <?php if($category['id'] == $post['cat_id']) echo 'selected' ?> >
                        <?= $category['name'] ?>
                    </option>

                    <?php } ?>
                  

                    </select>
        </div>

        <div class="form-group">
            <label for="image">Image</label>
            <input type="file" id="image" name="image" class="form-control-file"  autofocus>
        </div>
        <img src="<?= asset($post['image']) ?>" alt="">

        <div class="form-group">
            <label for="published_at">published at</label>
            <input type="text" class="form-control d-none" id="published_at" name="published_at">
            <input type="text" class="form-control" id="published_at_view" required autofocus>
        </div>

        <div class="form-group">
            <label for="summary">summary</label>
            <textarea class="form-control" id="summary" name="summary" placeholder="summary ..." rows="3" required autofocus><?= $post['summary'] ?></textarea>
        </div>

        <div class="form-group">
            <label for="body">body</label>
            <textarea class="form-control" id="body" name="body" placeholder="body ..." rows="5" required autofocus><?= $post['body'] ?></textarea>
        </div>

        <div class="form-group">
            <label for="selected">Featured Post</label>
            <select name="selected" id="selected" class="form-control">
                <option value="1" <?= $post['selected'] == 1 ? 'selected' : '' ?>>No</option>
                <option value="2" <?= $post['selected'] == 2 ? 'selected' : '' ?>>Yes</option>
            </select>
        </div>

        <div class="form-group">
            <label for="breaking_news">Breaking News</label>
            <select name="breaking_news" id="breaking_news" class="form-control">
                <option value="1" <?= $post['breaking_news'] == 1 ? 'selected' : '' ?>>No</option>
                <option value="2" <?= $post['breaking_news'] == 2 ? 'selected' : '' ?>>Yes</option>
            </select>
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="status" class="form-control">
                <option value="1" <?= $post['status'] == 1 ? 'selected' : '' ?>>Published</option>
                <option value="0" <?= $post['status'] == 0 ? 'selected' : '' ?>>Draft</option>
            </select>
        </div>

        <div class="form-group mt-4">
            <button type="submit" class="btn btn-primary btn-lg" style="background-color: #0d6efd; border-color: #0d6efd; color: white; pointer-events: auto !important; z-index: 9999 !important;">
                <i class="fas fa-save me-2"></i>
                Update Article
            </button>
            <a href="<?= url('admin/post') ?>" class="btn btn-secondary btn-lg ms-2">
                <i class="fas fa-times me-2"></i>
                Cancel
            </a>
        </div>
        </form>
        </section>
        </section>

<style>
    /* Ensure all form elements are visible and properly styled */
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-control, .form-select {
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
        height: auto !important;
        min-height: 40px !important;
    }
    
    textarea.form-control {
        min-height: 80px !important;
        resize: vertical;
    }
    
    .btn {
        pointer-events: auto !important;
        z-index: 9999 !important;
    }
    
    .btn-primary {
        background-color: #0d6efd !important;
        border-color: #0d6efd !important;
        color: white !important;
    }
    
    .btn-primary:hover {
        background-color: #0b5ed7 !important;
        border-color: #0a58ca !important;
    }
</style>

<script>
    // Set current timestamp for published_at field if empty
    document.addEventListener('DOMContentLoaded', function() {
        const publishedAt = document.getElementById('published_at');
        const publishedAtView = document.getElementById('published_at_view');
        
        // Set current time if field is empty
        if (!publishedAt.value) {
            publishedAt.value = Math.floor(Date.now() / 1000);
        }
        
        // Convert timestamp to readable format for display
        if (publishedAt.value) {
            const date = new Date(publishedAt.value * 1000);
            publishedAtView.value = date.toISOString().slice(0, 16);
        }
        
        // Update hidden field when display field changes
        publishedAtView.addEventListener('change', function() {
            const date = new Date(this.value);
            publishedAt.value = Math.floor(date.getTime() / 1000);
        });
    });
</script>




<?php

require_once (BASE_PATH . '/template/admin/layouts/footer.php')

?>