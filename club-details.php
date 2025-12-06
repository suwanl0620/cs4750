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

// Check if current user is a member
$userId = $_SESSION['user_id'];
$isMember = isUserMember($club['name'], $userId);

// Get any messages from session
$message = $_SESSION['club_message'] ?? null;
$messageType = $_SESSION['club_message_type'] ?? 'error';
unset($_SESSION['club_message'], $_SESSION['club_message_type']);

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

    <div class="container">
        <div class="club-details-container">
            <?php if ($message): ?>
                <div class="message message-<?php echo $messageType; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
            
            <div class="club-stats">
                <div>
                    <strong>Members:</strong> <?php echo $club['member_count']; ?>
                </div>
            </div>

            <div class="club-actions">
                <?php if ($isMember): ?>
                    <form method="POST" action="club-actions.php" style="display: inline;">
                        <input type="hidden" name="action" value="leave">
                        <input type="hidden" name="club_name" value="<?php echo htmlspecialchars($club['name']); ?>">
                        <button type="submit" class="btn btn-leave" onclick="return confirm('Are you sure you want to leave this club?')">Leave Club</button>
                    </form>
                    <span style="margin-left: 1rem; color: #28a745; font-weight: bold;">✓ Member</span>
                <?php else: ?>
                    <form method="POST" action="club-actions.php" style="display: inline;">
                        <input type="hidden" name="action" value="join">
                        <input type="hidden" name="club_name" value="<?php echo htmlspecialchars($club['name']); ?>">
                        <button type="submit" class="btn btn-join">Join Club</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>