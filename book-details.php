<?php 
require_once 'auth.php';
require('connect-db.php');
require('book-db.php');
require('reviews-db.php');
require('lists-db.php');

// error catching stuff
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// Get ISBN from URL parameter
$isbn = $_GET['isbn'] ?? null;

if (!$isbn) {
    header('Location: homepage.php');
    exit;
}

// Fetch book details
$book = getBookByISBN($isbn);
$want_to_read = getWantToReadList($_SESSION['user_id']);
$want_to_read_book = array_filter($want_to_read, function($b) use ($isbn) {
    return $b['ISBN'] === $isbn;
});
$read = getReadList($_SESSION['user_id']);
$read_book = array_filter($read, function($b) use ($isbn) {
    return $b['ISBN'] === $isbn;
});

if (!$book) {
    echo "Book not found.";
    exit;
}

// get info for "your rating" star display
$userRating = 0;
if (isset($_SESSION['user_id'])) {
    $userRating = getUserRatingForBook($_SESSION['user_id'], $isbn);
}

// get info for displaying all reviews for the book
$reviews = getReviewsForBook($isbn);

// check if user has already reviewed this book
$userHasReviewed = false;
$errorMessage = '';
if (isset($_SESSION['user_id'])) {
    foreach ($reviews as $review) {
        if ($review['userID'] == $_SESSION['user_id']) {
            $userHasReviewed = true;
            break;
        }
    }
}

// check if we should show the review modal
$show_review_modal = false;
if (isset($_GET['review']) && $_GET['review'] == 1) {
    if ($userHasReviewed) {
        $errorMessage = "You have already left a review for this book.";
    } else {
        $show_review_modal = true;
    }
}

// let user submit/write a review
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // if user is not logged in, redirect to login page
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    /*
    // check if user has already left a rating for this book
    $leftRating = getUserRatingForBook($_SESSION['user_id'], $isbn);
    if ($leftRating != 0) {
        $errorMessage = "You have already left a review for this book.";
    }
        */
    if (!empty($_POST['submit_review'])) {
        $result = addReview(
            $_SESSION['user_id'],      // user ID from session
            $_POST['isbn'],           // book ISBN
            $_POST['rating'],         // star rating
            $_POST['description']     // review text
        );
        
        if ($result === "duplicate") {
            // user has already left a review
            echo "<p style='color:red;'>You have already left a review for this book.</p>";
        } else if ($result === true) {
            // successful review
            // refresh the list of reviews
            $user_reviews = getUserReviews($_SESSION['user_id']);

            // redirect page to avoid duplicate submissions
            header("Location: book-details.php?isbn=" . $_POST['isbn']);
            exit();
        } else {
            echo "<p style='color:red;'>An unexpected error occurred. Please try again.</p>";
        }
    }
    
    else if (!empty($_POST['want_to_read'])) {
        $result = wantToRead(
            $_SESSION['user_id'],      // user ID from session
            $_POST['isbn'],           // book ISBN
        );
        
        if ($result === true) {
            // redirect page to avoid duplicate submissions
            header("Location: book-details.php?isbn=" . $_POST['isbn']);
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
    <title><?php echo htmlspecialchars($book['title']); ?> - TopShelf</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Star rating input styling for review form */
        .review-modal .star-rating-input {
            display: flex;
            flex-direction: row-reverse;
            justify-content: center;
            gap: 0.5rem;
            font-size: 2rem;
            margin: 1rem 0;
        }

        .modal-overlay .modal-header {
            text-align: center;
        }
        
        .review-modal .star-rating-input input[type="radio"] {
            display: none;
        }
        
        .review-modal .star-rating-input label {
            cursor: pointer;
            color: #ddd;
        }
        
        .review-modal .star-rating-input input[type="radio"]:checked ~ label,
        .review-modal .star-rating-input input[type="radio"]:checked + label {
            color: #ffd700;
        }
        
        .review-modal .star-rating-input label:hover,
        .review-modal .star-rating-input label:hover ~ label {
            color: #ffd700;
        }
        
    </style>
</head>
<body>
    <?php include('header.php'); ?>

    <!-- error message -->
    <?php if (!empty($errorMessage)): ?>
            <div class="error-popup">
                <strong>Error:</strong> <?php echo htmlspecialchars($errorMessage); ?>
            </div>
    <?php endif; ?>

    <div class="container">
        <div class="book-details">
            <div class="book-cover-section">
                <div class="book-cover" 
                     style="background-image: url('<?php echo htmlspecialchars($book['coverImage']); ?>');">
                </div>
                <?php if (function_exists('is_logged_in') && is_logged_in()): ?>
                    <div class="action-buttons">
                        <!-- only display if not already in want to read list -->
                        <?php if (empty($want_to_read_book) && empty($read_book)): ?>
                            <form action="book-details.php?isbn=<?php echo $isbn; ?>" method="POST">
                                <!-- Send ISBN to backend -->
                                <input type="hidden" name="isbn" value="<?php echo $isbn; ?>">
                                <button type="submit" name="want_to_read" value="1" class="action-btn add-to-list-btn">⭐ Want to Read</button>
                            </form>
                        <?php elseif (empty($read_book)): ?>
                            <button class="action-btn in-list-btn" disabled>✔ In Want to Read List</button>
                        <?php else: ?>
                            <button class="action-btn in-list-btn" disabled>✔ Read Book</button>    
                        <?php endif; ?>
            
                        <a href="?isbn=<?php echo $isbn; ?>&review=1" class="action-btn review-btn">Review</a>
                    
                        <div class="star-selector">
                            <?php 
                                $filled = $userRating;
                                $empty = 5 - $filled;

                                echo str_repeat('<span>★</span>', $filled);
                                echo str_repeat('<span>☆</span>', $empty);

                            
                            ?>
                        </div>
                        <div class="rating-label">Your Rating</div>
                    </div>
                <?php else: ?>
                    <p><strong>Sign in to save or review this book!</strong></p>
                <?php endif; ?>
            </div>

            <div class="book-info-section">
                <h1 class="book-title"><?php echo htmlspecialchars($book['title']); ?></h1>
                <p class="book-author"><?php echo htmlspecialchars($book['author']); ?></p>

                <?php
                    $rating = (float)($book['avgRating'] ?? 0);
                    $ratingCount = (int)($book['ratingCount'] ?? 0);
                    $reviewCount = (int)($book['reviewCount'] ?? 0);
                    $filledStars = floor($rating);
                    $halfStar = ($rating - $filledStars >= 0.5);
                    $emptyStars = 5 - $filledStars - ($halfStar ? 1 : 0);
                ?>
                <div class="star-rating">
                    <div>
                        <?php echo str_repeat('★', $filledStars); ?>
                        <?php if ($halfStar) echo '★'; ?>
                        <?php echo str_repeat('☆', $emptyStars); ?>
                    </div>
                    <span class="rating-text">
                        <?php echo number_format($rating, 2); ?> · 
                        <?php echo number_format($ratingCount); ?> Ratings · 
                        <?php echo number_format($reviewCount); ?> Reviews
                    </span>
                </div>

                <div class="book-description">
                    <p><?php echo nl2br(htmlspecialchars($book['description'])); ?></p>
                </div>
                
                <!-- book reviews -->
                <h2 style="margin-bottom: 0.5rem;">Book Reviews</h2>
                <div class="reviews-table-container">
                    <table class="reviews-table">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Rating</th>
                                <th>Review</th>
                                <th>Date Added</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($reviews)):?>
                                <?php foreach ($reviews as $index => $review): ?>
                                <tr>           
                                    <td>
                                        <!-- username -->
                                        <?php echo htmlspecialchars($review['userID']); ?>
                                    </td>                         
                                    <td> 
                                        <!-- rating -->
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
                                        <!-- review text -->
                                        <div class="review-text">
                                            <?php echo htmlspecialchars($review['description']); ?>
                                        </div>
                                    </td>

                                    <td>
                                        <!-- date added -->
                                        <?php echo htmlspecialchars($review['timestamp']); ?>
                                    </td>

                                   
                                 </tr>
                            <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 2rem; color: #666;">
                                No reviews yet.
                            </td>
                        </tr>
                    <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                

                </div>
            </div>
        </div>
    </div>

    <!-- Write Review Modal -->
    <?php if ($show_review_modal): ?>
    <div class="modal-overlay active review-modal">
        <div class="modal-content">
            <a href="book-details.php?isbn=<?php echo $isbn; ?>" class="close-btn">×</a>
            
            <div class="modal-header">
                <h2 class="modal-title"><?php echo htmlspecialchars($book['title']); ?></h2>
                <p class="modal-author"><?php echo htmlspecialchars($book['author']); ?></p>
            </div>

            <form method="post" action="book-details.php?isbn=<?php echo $isbn; ?>">
                <input type="hidden" name="isbn" value="<?php echo $isbn; ?>">
                
                <div class="rating-section">
                    <label class="rating-label">My Rating:</label>
                    <div class="star-rating-input">
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                            <input type="radio" name="rating" value="<?php echo $i; ?>" id="star<?php echo $i; ?>" required>
                            <label for="star<?php echo $i; ?>">☆</label>
                        <?php endfor; ?>
                    </div>
                </div>

                <div class="review-section">
                    <label class="rating-label">My Review:</label>
                    <textarea name="description" class="review-textarea" placeholder="Write your review here..." required></textarea>
                </div>

                <div class="modal-actions">
                    <a href="book-details.php?isbn=<?php echo $isbn; ?>" class="cancel-btn">Cancel</a>
                    <button type="submit" name="submit_review" value="1" class="save-btn">Submit Review</button>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>

</body>
</html>