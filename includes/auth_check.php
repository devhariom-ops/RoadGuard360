<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Hardcode Administrator Session for Direct Access
$_SESSION['user_id'] = 1;
$_SESSION['user_name'] = 'Administrator';
$_SESSION['user_email'] = 'admin@saferoads.org';
$_SESSION['user_role'] = 'admin';

function is_logged_in() {
    return true;
}

function get_logged_in_user() {
    return [
        'id' => 1,
        'name' => 'Administrator',
        'email' => 'admin@saferoads.org',
        'role' => 'admin'
    ];
}

function is_admin() {
    return true;
}

function require_login() {
    return true;
}

function require_admin() {
    return true;
}
?>
