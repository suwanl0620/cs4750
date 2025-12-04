<?php 
require_once 'auth.php';
require('connect-db.php');
require('reviews-db.php');
// require some kind of reviews db 
// should only be available if logged in
/*
$userID = $_SESSION['user_id'] ?? null;
if (!$userID) { header("Location: login.php"); exit; }

$reviews = getUserReviews($userID);
*/
$user_reviews = getUserReviews($_SESSION['user_id']);

// sample data
/*
$user_reviews = [
    [
        'coverImage' => '',
        'title' => 'To Kill A Mockingbird',
        'author' => 'Lee, Harper',
        'avgRating' => 4.26,
        'userRating' => 5,
        'review' => 'One of the required readings in high school but there is definitely a good reason for this. I will explain in the next few lines of this review, please keep reading. I think that it because it teaches you so much about life and allow you to explore outlying themes of empathy, prejudice, and small-town, to gain insights was human nature, and to understand how courage is demonstrated through action rather than physical strength',
        'dateAdded' => 'Sep 12, 2025'
    ],
    [
        'coverImage' => '',
        'title' => 'One fish, two fish, red fish, blue fish',
        'author' => 'Dr. Seuss',
        'avgRating' => 4.57,
        'userRating' => 5,
        'review' => 'This book is exactly good. I love reading this book when I was young. I always told my mom to keep on reading. This fish, red fish, red fish, blue fish to them, so much...',
        'dateAdded' => 'Apr 8, 2025'
    ],
    [
        'coverImage' => '',
        'title' => '90 Algorithms Every Programmer Should Know',
        'author' => 'Ahmed, Imran',
        'avgRating' => 4.11,
        'userRating' => 4,
        'review' => 'Mastering algorithms is a highly recommend. Aspiring computer scientists should understand the most popular algorithms every programmer should know thoroughly.',
        'dateAdded' => 'Jan 5, 2025'
    ]
];
*/
?>

 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Reviews - TopShelf</title>
    <link rel="stylesheet" href="styles.css">
</head>
    <body>
    <?php include('header.php'); ?>

    <section class="hero">
        <h1>My Reviews</h1>
    </section>

    <div class="container">
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