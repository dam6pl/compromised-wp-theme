<?php

/**
 * Scope of the application:
 * 1. Allow guests to login to their wordpress account.
 * 2. Allow users to list published posts with pagination.
 * 3. Allow users to remove (trash) post.
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
    require 'partials/' . $_GET['view'] . '.php';
    die;
}

add_action('wp_ajax_nopriv_login', 'login');
add_action('wp_ajax_login', 'login');

function login(): void
{
    $email = $_GET['email'];
    $password = $_GET['password'];

    if ($email) {
        if ($password) {
            $user = wp_signon(['user_login' => $email, 'user_password' => $password]);
            if (is_wp_error($user)) {
                echo $user->get_error_message();
                http_response_code(400);
                die;
            }
        }else {
            echo 'Invalid password!';
            http_response_code(400);
            die;
        }
    } else {
        echo 'Invalid email!';
        http_response_code(400);
        die;
    }

    http_response_code(200);
    die;
}

add_action('wp_ajax_nopriv_delete_post', 'delete_post');
add_action('wp_ajax_delete_post', 'delete_post');

function delete_post(): void
{
    $post_id = $_GET['post'];

    if ($post_id) {
        $delete = wp_delete_post($post_id);

            if (!$delete) {
                echo 'Post cannot be delted';
                http_response_code(400);
                die;
            }

    } else {
        echo 'Invalid post id!';
        http_response_code(400);
        die;
    }

    http_response_code(200);
    die;
}

add_action('wp_ajax_nopriv_logout', 'logout');
add_action('wp_ajax_logout', 'logout');

function logout(): void
{
    wp_logout();

    http_response_code(200);
    die;
}