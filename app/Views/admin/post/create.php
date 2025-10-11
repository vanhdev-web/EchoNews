<?php

        require_once (BASE_PATH . '/app/Views/admin/layouts/head-tag.php')

?>



<section class="pt-3 pb-1 mb-2 border-bottom">
    <h1 class="h5">Create Article</h1>
</section>

<section class="row my-3">
    <section class="col-12">

        <form method="post" action="<?= url('admin/posts') ?>" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" class="form-control" id="title" name="title" placeholder="Enter title..." required autofocus>
                </div>

                <div class="form-group">
                    <label for="cat_id">Category</label>
                    <select name="cat_id" id="cat_id" class="form-control" required>
                <?php foreach ($categories as $category) { ?>
                 <option value="<?= $category['id'] ?>">
                        <?= $category['name'] ?>
                    </option>

                    <?php } ?>
                  

                    </select>
        </div>

        <div class="form-group">
            <label for="image">Image</label>
            <input type="file" id="image" name="image" class="form-control-file" required accept="image/*">
        </div>

        <div class="form-group">
            <label for="published_at">Published At</label>
            <input type="hidden" class="form-control" id="published_at" name="published_at" required>
            <input type="datetime-local" class="form-control" id="published_at_view" required>
        </div>

        <div class="form-group">
            <label for="summary">Summary</label>
            <textarea class="form-control" id="summary" name="summary" placeholder="Enter summary..." rows="3" style="display: block !important;"></textarea>
        </div>

        <div class="form-group">
            <label for="body">Body</label>
            <textarea class="form-control" id="body" name="body" placeholder="Enter article body..." rows="5" style="display: block !important;"></textarea>
        </div>

        <div class="form-group">
            <label for="selected">Featured Post</label>
            <select name="selected" id="selected" class="form-control">
                <option value="1">No</option>
                <option value="2">Yes</option>
            </select>
        </div>

        <div class="form-group">
            <label for="breaking_news">Breaking News</label>
            <select name="breaking_news" id="breaking_news" class="form-control">
                <option value="1">No</option>
                <option value="2">Yes</option>
            </select>
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="status" class="form-control">
                <option value="enable">Published</option>
                <option value="disable">Draft</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary btn-sm" onclick="console.log('Button clicked!'); return true;" style="pointer-events: auto !important; z-index: 9999 !important;">store</button>
        <button type="button" onclick="alert('Test button works!')" class="btn btn-success btn-sm ms-2">Test Button</button>
        </form>
        </section>
        </section>

<style>
    /* Force textarea visibility - override any hiding CSS */
    #summary, #body {
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
        height: auto !important;
        min-height: 80px !important;
    }
</style>

<script>
    // Set current timestamp for published_at field
    document.getElementById('published_at').value = Math.floor(Date.now() / 1000);
    
    // Set readable date for display
    var now = new Date();
    document.getElementById('published_at_view').value = now.toISOString().slice(0, 16);
    
    // Update hidden field when display field changes
    document.getElementById('published_at_view').addEventListener('change', function() {
        var date = new Date(this.value);
        document.getElementById('published_at').value = Math.floor(date.getTime() / 1000);
    });
    
    // Debug button click and form submit
    document.addEventListener('DOMContentLoaded', function() {
        const button = document.querySelector('button[type="submit"]');
        const form = document.querySelector('form');
        
        console.log('Button element:', button);
        console.log('Form element:', form);
        
        // Add click event to button
        button.addEventListener('click', function(e) {
            console.log('Button click event triggered!');
            console.log('Event object:', e);
        });
        
        // Add submit event to form
        form.addEventListener('submit', function(e) {
            console.log('Form submit triggered');
            console.log('Form data:');
            const formData = new FormData(this);
            for (let [key, value] of formData.entries()) {
                console.log(key + ':', value);
            }
            
            // Check if required fields are filled
            const title = document.getElementById('title').value;
            const category = document.getElementById('cat_id').value;
            const image = document.getElementById('image').files[0];
            const summary = document.getElementById('summary').value;
            const body = document.getElementById('body').value;
            
            console.log('Validation check:');
            console.log('Title:', title ? 'OK' : 'MISSING');
            console.log('Category:', category ? 'OK' : 'MISSING');
            console.log('Image:', image ? 'OK' : 'MISSING');
            console.log('Summary:', summary ? 'OK' : 'MISSING');
            console.log('Body:', body ? 'OK' : 'MISSING');
            
            if (!title || !category || !image || !summary || !body) {
                alert('Please fill all required fields');
                e.preventDefault();
                return false;
            }
            
            // Let form submit normally
            console.log('Form validation passed, submitting...');
        });
        
        // Check button styles
        const computedStyle = window.getComputedStyle(button);
        console.log('Button pointer-events:', computedStyle.pointerEvents);
        console.log('Button display:', computedStyle.display);
        console.log('Button z-index:', computedStyle.zIndex);
        console.log('Button position:', computedStyle.position);
    });
</script>



<?php

require_once (BASE_PATH . '/app/Views/admin/layouts/footer.php')

?>
