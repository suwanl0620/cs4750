<?php 
require_once 'auth.php';
require('connect-db.php');
require('reviews-db.php');
require('lists-db.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

$user_reviews = getUserReviews($_SESSION['user_id']);
$want_to_read = getWantToReadList($_SESSION['user_id']);
$read_list = getReadList($_SESSION['user_id']);

// let user edit lists
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // if user is not logged in, redirect to login page
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    // move book from want to read to read list
    if (!empty($_POST['read_book'])) {
        $result = markAsRead(
            $_SESSION['user_id'],      // user ID from session
            $_POST['isbn']           // book ISBN
        );
        
        if ($result === true) {
            $want_to_read = getWantToReadList($_SESSION['user_id']);
            $read_list = getReadList($_SESSION['user_id']);

            // redirect page to avoid duplicate submissions
            header("Location: profile.php");
            exit();
        } else {
            echo "<p style='color:red;'>An unexpected error occurred. Please try again.</p>";
        }
    }
    
    // remove book from want to read list
    else if (!empty($_POST['remove_want_to_read'])) {
        $result = removeFromWantToRead(
            $_SESSION['user_id'],      // user ID from session
            $_POST['isbn']           // book ISBN
        );
        
        if ($result === true) {
            $want_to_read = getWantToReadList($_SESSION['user_id']);

            // redirect page to avoid duplicate submissions
            header("Location: profile.php");
            exit();
        } else {
            echo "<p style='color:red;'>An unexpected error occurred. Please try again.</p>";
        }
    }

    // remove book from Read list
    else if (!empty($_POST['remove_read'])) {
        $result = removeFromRead(
            $_SESSION['user_id'],      // user ID from session
            $_POST['isbn']           // book ISBN
        );
        
        if ($result === true) {
            $read_list = getReadList($_SESSION['user_id']);

            // redirect page to avoid duplicate submissions
            header("Location: profile.php");
            exit();
        } else {
            echo "<p style='color:red;'>An unexpected error occurred. Please try again.</p>";
        }
    }
}

?>

 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - TopShelf</title>
    <link rel="stylesheet" href="styles.css">
</head>
    <body>
    <?php include('header.php'); ?>

    <section class="hero">
        <h1>My Profile</h1>
    </section>

    <div class="container">
        <h2 style="margin-bottom: 1rem;">My Reviews</h2>
        <div class="reviews-table-container">
            <table class="reviews-table">
                <thead>
                    <tr>
                        <th>Cover</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Avg Rating</th>
                        <th>Review</th>
                        <th>Date Added</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($user_reviews)): ?>
                        <?php foreach ($user_reviews as $index => $review): ?>
                        <tr>
                            <td>
                                <div class="book-cover-small"
                                     style="background-image: url('<?php echo htmlspecialchars($review['coverImage']); ?>');
                                            background-size: cover;
                                            background-position: center;">
                                </div>
                            </td>
                            <td><strong><?php echo htmlspecialchars($review['title']); ?></strong></td>
                            <td><?php echo htmlspecialchars($review['author']); ?></td>
                            <td>
                                <div class="star-display">
                                    <?php
                                        $rating = (float)$review['avgRating'];
                                        $filledStars = floor($rating);
                                        echo str_repeat('★', $filledStars);
                                        echo str_repeat('☆', 5 - $filledStars);
                                    ?>
                                </div>
                                <div style="font-size: 0.85rem; color: #666; margin-top: 0.25rem;">
                                    <?php echo number_format($rating, 2); ?>
                                </div>
                            </td>
                            <td>
                                <div class="review-text">
                                    <?php echo htmlspecialchars($review['review']); ?>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($review['dateAdded']); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <!-- Add edit functionality here -->
                                    <button class="edit-btn">Edit</button>
                                    <!-- Add delete functionality here -->
                                    <button class="delete-btn">Delete</button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 2rem; color: #666;">
                                You haven't written any reviews yet.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="container">
        <h2 style="margin-bottom: 1rem;">Books I Want to Read</h2>
        <div class="reviews-table-container">
            <table class="reviews-table">
                <thead>
                    <tr>
                        <th>Cover</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Avg Rating</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($want_to_read)): ?>
                        <?php foreach ($want_to_read as $book): ?>
                        <tr onclick="window.location.href='book-details.php?isbn=<?php echo urlencode($book['ISBN']); ?>'">
                            <td>
                                <div class="book-cover-small"
                                     style="background-image: url('<?php echo htmlspecialchars($book['coverImage']); ?>');
                                            background-size: cover;
                                            background-position: center;">
                                </div>
                            </td>
                            <td onclick="window.location.href='book-details.php?isbn=<?php echo urlencode($book['ISBN']); ?>'"><strong><?php echo htmlspecialchars($book['title']); ?></strong></td>
                            <td><?php echo htmlspecialchars($book['author']); ?></td>
                            <td>
                                <div class="star-display">
                                    <?php
                                        $rating = (float)$book['avgRating'];
                                        $filledStars = floor($rating);
                                        echo str_repeat('★', $filledStars);
                                        echo str_repeat('☆', 5 - $filledStars);
                                    ?>
                                </div>
                                <div style="font-size: 0.85rem; color: #666; margin-top: 0.25rem;">
                                    <?php echo number_format($rating, 2); ?>
                                </div>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <form action="profile.php" method="POST">
                                        <input type="hidden" name="isbn" value="<?php echo htmlspecialchars($book['ISBN']); ?>">
                                        <!-- Mark as read -->
                                        <button type="submit" name="read_book" value="1" class="edit-btn">Mark as Read</button>
                                    </form>
                                    <form action="profile.php" method="POST">
                                        <input type="hidden" name="isbn" value="<?php echo htmlspecialchars($book['ISBN']); ?>">
                                        <!-- Remove from want to read list -->
                                        <button type="submit" name="remove_want_to_read" value="1" class="delete-btn">Remove from List</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 2rem; color: #666;">
                                You don't currently have any saved books in your Want to Read list.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="container">
        <h2 style="margin-bottom: 1rem;">Books I've Read</h2>
        <div class="reviews-table-container">
            <table class="reviews-table">
                <thead>
                    <tr>
                        <th>Cover</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Avg Rating</th>
                        <th>Date Added</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($read_list)): ?>
                        <?php foreach ($read_list as $book): ?>
                        <tr onclick="window.location.href='book-details.php?isbn=<?php echo urlencode($book['ISBN']); ?>'">
                            <td>
                                <div class="book-cover-small"
                                     style="background-image: url('<?php echo htmlspecialchars($book['coverImage']); ?>');
                                            background-size: cover;
                                            background-position: center;">
                                </div>
                            </td>
                            <td><strong><?php echo htmlspecialchars($book['title']); ?></strong></td>
                            <td><?php echo htmlspecialchars($book['author']); ?></td>
                            <td>
                                <div class="star-display">
                                    <?php
                                        $rating = (float)$book['avgRating'];
                                        $filledStars = floor($rating);
                                        echo str_repeat('★', $filledStars);
                                        echo str_repeat('☆', 5 - $filledStars);
                                    ?>
                                </div>
                                <div style="font-size: 0.85rem; color: #666; margin-top: 0.25rem;">
                                    <?php echo number_format($rating, 2); ?>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($book['timestamp']); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <form action="profile.php" method="POST">
                                        <input type="hidden" name="isbn" value="<?php echo htmlspecialchars($book['ISBN']); ?>">
                                        <!-- Mark as read -->
                                        <button type="submit" name="remove_read" value="1" class="delete-btn">Remove From List</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 2rem; color: #666;">
                                You don't currently have any books marked as read.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Edit Review Modal (add condition to show/hide based on PHP logic) -->
    <div class="modal-overlay">
        <div class="modal-content">
            <a href="#" class="close-btn">×</a>
            
            <div class="modal-header">
                <h2 class="modal-title">To Kill A Mockingbird</h2>
                <p class="modal-author">Lee, Harper</p>
            </div>

            <form method="post" action="">
                <div class="rating-section">
                    <label class="rating-label">My Rating:</label>
                    <div class="star-rating-input">
                        <!-- Implement star rating system here -->
                        <input type="radio" name="rating" value="5" id="star5">
                        <label for="star5">☆</label>
                        <input type="radio" name="rating" value="4" id="star4">
                        <label for="star4">☆</label>
                        <input type="radio" name="rating" value="3" id="star3">
                        <label for="star3">☆</label>
                        <input type="radio" name="rating" value="2" id="star2">
                        <label for="star2">☆</label>
                        <input type="radio" name="rating" value="1" id="star1">
                        <label for="star1">☆</label>
                    </div>
                </div>

                <div class="review-section">
                    <label class="rating-label">My Review:</label>
                    <textarea name="review_text" class="review-textarea" placeholder="Write your review here..."></textarea>
                </div>

                <div class="modal-actions">
                    <a href="#" class="cancel-btn">Cancel</a>
                    <button type="submit" class="save-btn">Save</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>