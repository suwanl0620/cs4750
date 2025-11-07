<?php
session_start();
require_once 'auth-db.php';

function is_logged_in() {
    return !empty($_SESSION['user_id']);
}

function current_user() {
    if (!is_logged_in()) return null;
    return ['userID' => $_SESSION['user_id'], 'username' => $_SESSION['username']];
}

function require_login() {
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}
?>