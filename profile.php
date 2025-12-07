<?php 
require_once 'auth.php';
require('connect-db.php');
require('reviews-db.php');
require('lists-db.php');

// error_reporting(E_ALL);
// ini_set('display_errors', 1);

$user_reviews = getUserReviews($_SESSION['user_id']);
$want_to_read = getWantToReadList($_SESSION['user_id']);
$read_list = getReadList($_SESSION['user_id']);

// check if we should show edit modal
$show_modal = false;
$edit_review = null;
if (isset($_GET['edit_isbn'])) {
    // find review to edit
    foreach ($user_reviews as $review) {
        if ($review['ISBN'] === $_GET['edit_isbn']) {
            $edit_review = $review;
            $show_modal = true;
            break;
        }   
    }
}

// let user edit lists
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // if user is not logged in, redirect to login page
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    // update review
    if (!empty($_POST['update_review'])) {
        $result = updateReview(
            $_SESSION['user_id'],
            $_POST['isbn'],
            $_POST['rating'],
            $_POST['review_text']
        );

        if ($result === true) {
            header("Location: profile.php");
            exit();
        } else {
            echo "<p style='color:red;'>An unexpected error occurred. Please try again.</p>";
        }
    }

      // Delete review
      else if (!empty($_POST['delete_review'])) {
        $result = deleteReview($_SESSION['user_id'], $_POST['isbn']);
        
        if ($result === true) {
            header("Location: profile.php");
            exit();
        } else {
            echo "<p style='color:red;'>An unexpected error occurred. Please try again.</p>";
        }
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
    <style>
        /* style override for edit review popup*/
        /* fix star rating to show correctly with row-reverse */
        .star-rating-input {
            flex-direction: row-reverse;
        }
        
        /* color the checked star AND all stars after it (which appear left due to reverse) */
        .star-rating-input input[type="radio"]:checked ~ label,
        .star-rating-input input[type="radio"]:checked + label {
            color: #ffd700;
        }

        /* Center the modal header */
        .modal-overlay .modal-header {
            text-align: center;
        }
        
        /* Center the star rating input */
        .modal-overlay .star-rating-input {
            justify-content: center;
        }
    </style>
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
                        <th>Your Rating</th>
                        <th>Your Review</th>
                        <th>Date Added</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($user_reviews)): ?>
                        <?php foreach ($user_reviews as $review): ?>
                        <tr onclick="window.location.href='book-details.php?isbn=<?php echo urlencode($review['ISBN']); ?>'">
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
                                        $rating = (float)$review['rating'];
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
                                    <?php echo htmlspecialchars($review['description']); ?>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($review['timestamp']); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <!-- edit button -->
                                    <a href="profile.php?edit_isbn=<?php echo urlencode($review['ISBN']); ?>" 
                                       class="edit-btn" style="text-decoration: none; display: inline-block;">Edit</a>
                                 
                                    <!-- delete button -->
                                    <form action="profile.php" method="POST" style="display: inline;">
                                        <input type="hidden" name="isbn" value="<?php echo htmlspecialchars($review['ISBN']); ?>">
                                        <button type="submit" name="delete_review" value="1" class="delete-btn" onclick="return confirm('Are you sure you want to delete this review?');">Delete</button>
                                    </form>

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
    <?php if ($show_modal && $edit_review): ?>
    <div class="modal-overlay active">
        <div class="modal-content">
            <a href="profile.php" class="close-btn">×</a>
            
            <div class="modal-header">
                <h2 class="modal-title"><?php echo htmlspecialchars($edit_review['title']); ?></h2>
                <p class="modal-author"><?php echo htmlspecialchars($edit_review['author']); ?></p>
            </div>

            <form method="post" action="profile.php">
                <input type="hidden" name="isbn" value="<?php echo htmlspecialchars($edit_review['ISBN']); ?>">
                <div class="rating-section">
                    <label class="rating-label">My Rating:</label>
                    <div class="star-rating-input">
                        <!-- Implement star rating system here -->
                        
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                            <input type="radio" name="rating" value="<?php echo $i; ?>" id="star<?php echo $i; ?>" <?php echo (floor($edit_review['rating']) == $i) ? 'checked' : ''; ?>>                            
                            <label for="star<?php echo $i; ?>">☆</label>
                        <?php endfor; ?>

                    </div>
                </div>

                <div class="review-section">
                    <label class="rating-label">My Review:</label>
                    <textarea name="review_text" class="review-textarea" placeholder="Write your review here..."><?php echo htmlspecialchars($edit_review['description']); ?></textarea>
                </div>

                <div class="modal-actions">
                    <a href="profile.php" class="cancel-btn">Cancel</a>
                    <button type="submit" name="update_review" value="1" class="save-btn">Save</button>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>
</body>
</html>