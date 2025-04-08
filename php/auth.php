<?php
session_start();

/**
 * Check if the user is logged in.
 *
 * @return bool True if the user is logged in, false otherwise.
 */
function is_logged_in() {
    return isset($_SESSION['username']);
}

/**
 * Redirect to the login page if the user is not logged in.
 */
function require_login() {
    if (!is_logged_in()) {
        header("Location: login.php");
        exit;
    }
}

/**
 * Log out the user by clearing the session.
 */
function logout_user() {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}
?>