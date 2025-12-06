<?php
require_once 'auth.php';
require('connect-db.php');
require('posts-db.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: book-clubs.php');
    exit;
}

$clubName = $_POST['club_name'] ?? '';
$content = trim($_POST['content'] ?? '');
$parentPostID = $_POST['parent_post_id'] ?? null;

if (empty($clubName) || empty($content)) {
    $_SESSION['post_message'] = 'Content cannot be empty.';
    $_SESSION['post_message_type'] = 'error';
    header('Location: club-details.php?clubName=' . urlencode($clubName));
    exit;
}

$userId = $_SESSION['user_id'];

// If parentPostID is empty string, convert to null
if ($parentPostID === '') {
    $parentPostID = null;
}

$result = createPost($userId, $clubName, $content, $parentPostID);

$_SESSION['post_message'] = $result['message'];
$_SESSION['post_message_type'] = $result['success'] ? 'success' : 'error';

header('Location: club-details.php?clubName=' . urlencode($clubName));
exit;
?>