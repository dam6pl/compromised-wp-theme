<?php

/**
 * Scope of the application:
 * 1. Allow guests to login to their wordpress account.
 * 2. Allow users to list published posts with pagination.
 * 3. Allow users to CRUD actions on posts.
 * 4. Allow user to log out.
 *
 * Problems that need to be addressed:
 * 1. Security and potential attacks.
 * 2. Code style.
 * 3. Potential performance issues.
 */

add_filter( 'show_admin_bar', '__return_false' );

add_action('wp_ajax_nopriv_load_view', 'load_view');
add_action('wp_ajax_load_view', 'load_view');

function load_view(): void
{    
    $view = filter_input(INPUT_GET, 'view');
    $view = str_replace(['.', '/'], '', $view);

    if(is_user_logged_in()) {
        $view = "auth/{$view}";
    }

    if (!file_exists(get_stylesheet_directory() . "/partials/{$view}.php")) {
        wp_send_json_error("View {$view} not found.", 404);
    }
    
    ob_start();
    require_once 'partials/' . $view . '.php';
    $response = ob_get_clean();

    wp_send_json_success($response);
}

add_action('wp_ajax_nopriv_login', 'login');

function login(): void
{
    $email = \filter_input(\INPUT_POST, 'email', \FILTER_SANITIZE_EMAIL);
    $password = \filter_input(\INPUT_POST, 'password');
    $security = \filter_input(\INPUT_POST, 'nonce');

    if (false === isset($security) || false === \wp_verify_nonce($security, 'demo-nonce')) {
        wp_send_json_error("Nonce is missing", 400);
    }

    if (empty($email)) {
        wp_send_json_error("Email is missing", 400);
    }

    if (empty($password)) {
        wp_send_json_error("Password is missing.", 400);
    }

    $user = wp_signon(['user_login' => $email, 'user_password' => $password]);

    if (is_wp_error($user)) {
        wp_send_json_error($user->get_error_message(), 400);
    }

    wp_send_json_success();
}

add_action('wp_ajax_post_delete', 'post_delete');

function post_delete(): void
{
    if (!\current_user_can('delete_posts')) {
        \wp_send_json_error('You are not allowed to delete post. Please contact with administrator.');
    }

    $postId = (int)($_GET['post'] ?? 0);

    if (!$postId) {
        wp_send_json_error("Wrong post ID.", 400);
    }
    
    if ('post' !== get_post_type($postId)) {
        wp_send_json_error("Wrong post type.", 400);
    }

    $delete = wp_delete_post($postId);

    if (!$delete) {
        wp_send_json_error("Post cannot be deleted.", 400);
    }

    wp_send_json_success();
}

add_action('wp_ajax_post_insert', 'post_insert');

function post_insert(): void
{

    if (!\current_user_can('create_posts')) {
        \wp_send_json_error('You are not allowed to create post. Please contact with administrator.');
    }

    $title = \filter_input(\INPUT_POST, 'title');
    $content = \filter_input(\INPUT_POST, 'content');

    if (empty($title)) {
        \wp_send_json_error('You need post title.');
    }

    $postData = [
        'post_title' => $title ?: '',
        'post_content' => $content ?: '',
    ];
    $post = wp_insert_post($postData, true);

    if (is_wp_error($post)) {
        \wp_send_json_error($post->get_error_message(), 400);
    }

    \wp_send_json_success();
}

add_action('wp_ajax_logout', 'logout');

function logout(): void
{
    \wp_logout();

    \wp_send_json_success();
}