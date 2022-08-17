<?php 
    $currentPage = (int)($_GET['page'] ?? 1);
?>

<div class="col-8">
    <button type="button" class="btn btn-outline-primary mb-3" onclick="logout()">Logout</button>

    <div class="alert alert-warning mb-3 d-none" id="response-error" role="alert"></div>

    <div class="row">
        <div class="col-6">
            <p>Current page: <?= $currentPage ?></p>
        </div>
        <div class="col-6 d-flex justify-content-end">
            <button onclick="load_view('add')" type="button"
                    class="btn btn-outline-success">Add new</button>
        </div>
    </div>

    <table class="table">
        <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Title</th>
            <th scope="col">Date</th>
            <th scope="col">Author</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $posts = new WP_Query([
            'post_type' => 'post',
            'posts_per_page' => 3,
            'paged' => $currentPage
        ]);

        foreach ($posts->get_posts() as $post) {

            $author = get_user_by('ID', $post->post_author);
            ?>

            <tr>
                <th scope="row"><?php echo $post->ID ?></th>
                <td><?= $post->post_title ?></td>
                <td><?= $post->post_date ?></td>
                <td><?= $author->display_name ?></td>
                <td class="gap-10">
                    <button onclick="load_view('edit', {post: <?= $post->ID ?>})" type="button"
                            class="btn btn-secondary">Edit</button>
                    <button onclick="deletePost(event, <?= $post->ID ?>)" type="button"
                            class="btn btn-danger">Delete</button>
                </td>
            </tr>
        <?php }
        ?>
        </tbody>
    </table>

    <nav aria-label="Page navigation example">
        <ul class="pagination">
            <?php foreach (range(1, $posts->max_num_pages) as $item): ?>
                <li class="page-item <?= $currentPage == $item ? 'active' : '' ?>">
                    <button class="page-link" onclick="load_view('dashboard', {page: <?= $item?>})">
                        <?= $item ?>
                    </button>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
</div>