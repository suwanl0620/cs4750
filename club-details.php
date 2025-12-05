<?php 
require_once 'auth.php';
require('connect-db.php');
require('clubs-db.php');

// error catching stuff
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Get club name from URL parameter
$clubName = $_GET['clubName'] ?? null;

if (!$clubName) {
    header('Location: book-clubs.php');
    exit;
}

// Fetch club details
$club = getClubByName($clubName);

if (!$club) {
    echo "Club not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($club['name']); ?> - TopShelf</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include('header.php'); ?>

    <section class="hero">
        <h1>Book Clubs</h1>
        <h2 style="padding-top: 2rem; text-transform: uppercase;"><?php echo htmlspecialchars($club['name']); ?></h2>
        <h2 style="text-transform: capitalize;"><?php echo htmlspecialchars($club['description']); ?></h2>
    </section>
</body>
</html>