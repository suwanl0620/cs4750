<?php
require_once 'auth.php';
require('connect-db.php');
require('clubs-db.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: book-clubs.php');
    exit;
}

$action = $_POST['action'] ?? '';
$clubName = $_POST['club_name'] ?? '';

if (!$clubName || !$action) {
    header('Location: book-clubs.php');
    exit;
}

$userId = $_SESSION['user_id'];

if ($action === 'join') {
    $result = joinClub($clubName, $userId);
} elseif ($action === 'leave') {
    $result = leaveClub($clubName, $userId);
} else {
    header('Location: book-clubs.php');
    exit;
}

// Store message in session to display on next page
$_SESSION['club_message'] = $result['message'];
$_SESSION['club_message_type'] = $result['success'] ? 'success' : 'error';

// Redirect back to club details
header('Location: club-details.php?clubName=' . urlencode($clubName));
exit;
?>