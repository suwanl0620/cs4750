<?php
require_once 'connect-db.php';

function getUserByUsername($username) {
    global $db;
    $stmt = $db->prepare('SELECT * FROM Users WHERE userID = :u');
    $stmt->execute([':u' => $username]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function createUser($username, $password_hash) {
    global $db;
    $stmt = $db->prepare('INSERT INTO Users (userID, password) VALUES (:u, :p)');
    return $stmt->execute([':u' => $username, ':p' => $password_hash]);
}
?>