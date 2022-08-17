<div class="col-8">
    <div class="alert alert-warning mb-3 d-none" id="response-error" role="alert"></div>
    <h3>Add new post</h3>

    <form id="edit-form" onsubmit="insertPost(event)">
        <div class="form-group mb-2">
            <label for="title">Post title</label>
            <input type="text" class="form-control" id="title" name="post_title"
                   value="<?= $post->post_title ?>">
        </div>
        <div class="form-group mb-2">
            <label for="content">Post content</label>
            <textarea class="form-control" id="content" name="post_content"><?= $post->post_content ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Add</button>
    </form>
</div>