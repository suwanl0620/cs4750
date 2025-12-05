<?php 
require_once 'auth.php';
require('connect-db.php');
require('clubs-db.php');

$all_clubs = getAllClubs();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Clubs - TopShelf</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <?php include('header.php'); ?>

    <section class="hero">
        <h1>Book Clubs</h1>
    </section>

    <div class="container">
        <div class="book-grid">
            <?php if (!empty($all_clubs)): ?>
                <?php foreach ($all_clubs as $club): ?>
                <div class="book-card" style="cursor: pointer;" onclick="window.location.href='club-details.php?clubName=<?php echo urlencode($club['name']); ?>'">
                    <div class="book-info">
                        <h3 class="book-title"><?php echo htmlspecialchars($club['name']); ?></h3>
                        <p class="book-author" style="text-transform: capitalize;"><?php echo htmlspecialchars($club['description']); ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No book clubs found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>