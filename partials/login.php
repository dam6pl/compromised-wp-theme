<div class="col-4">
    <div class="alert alert-warning mb-3 d-none" id="response-error" role="alert"></div>

    <form id="login-form" onsubmit="login(event)" method="POST">
        <div class="form-group mb-2">
            <label for="email">Email address</label>
            <input type="email" class="form-control" id="email" aria-describedby="emailHelp">
        </div>
        <div class="form-group mb-2">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password">
        </div>
        <?php \wp_nonce_field('demo-nonce', 'security'); ?>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>