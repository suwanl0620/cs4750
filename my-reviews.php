<?php 
require_once 'auth.php';
require('connect-db.php');
require('reviews-db.php');
// require some kind of reviews db 
// should only be available if logged in
$userID = $_SESSION['user_id'] ?? null;
if (!$userID) { header("Location: login.php"); exit; }

$reviews = getUserReviews($userID);
?>

 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Reviews - TopShelf</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background-color: #f5f5f5;
        }

        header {
            background-color: white;
            padding: 1rem 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .nav-links a {
            text-decoration: none;
            color: #333;
            font-size: 0.95rem;
        }

        .nav-links a:hover {
            color: #666;
        }

        .hero {
            background-color: #f0f0f0;
            padding: 5rem 2rem;
            text-align: center;
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: bold;
            color: #333;
            position: relative;
            display: inline-block;
        }

        .hero h1::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(to right, #7c3aed, #ec4899);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 3rem 2rem;
        }

        .reviews-table-container {
            background-color: white;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            overflow: hidden;
        }

        .reviews-table {
            width: 100%;
            border-collapse: collapse;
        }

        .reviews-table thead {
            background-color: #f9f9f9;
        }

        .reviews-table th {
            padding: 1rem;
            text-align: left;
            font-size: 0.9rem;
            font-weight: 600;
            color: #333;
            border-bottom: 1px solid #e0e0e0;
        }

        .reviews-table td {
            padding: 1rem;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: top;
        }

        .reviews-table tbody tr:last-child td {
            border-bottom: none;
        }

        .reviews-table tbody tr:hover {
            background-color: #fafafa;
        }

        .book-cover-small {
            width: 60px;
            height: 90px;
            background-color: #ddd;
            border-radius: 4px;
        }

        .star-display {
            color: #ffd700;
            font-size: 1rem;
        }

        .review-text {
            max-width: 300px;
            color: #666;
            font-size: 0.9rem;
            line-height: 1.4;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .edit-btn, .delete-btn {
            padding: 0.4rem 0.8rem;
            border: 1px solid #ddd;
            background-color: white;
            cursor: pointer;
            font-size: 0.85rem;
            border-radius: 4px;
        }

        .edit-btn:hover {
            background-color: #f0f0f0;
        }

        .delete-btn {
            color: #dc2626;
            border-color: #dc2626;
        }

        .delete-btn:hover {
            background-color: #fee;
        }

        /* Modal Styles */
        .modal-overlay {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            overflow-y: auto;
        }

        .modal-overlay.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background-color: white;
            padding: 2rem;
            border-radius: 8px;
            width: 90%;
            max-width: 600px;
            position: relative;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .close-btn {
            position: absolute;
            right: 1.5rem;
            top: 1.5rem;
            font-size: 1.5rem;
            cursor: pointer;
            color: #666;
            background: none;
            border: none;
            padding: 0;
            line-height: 1;
            text-decoration: none;
        }

        .close-btn:hover {
            color: #333;
        }

        .modal-header {
            margin-bottom: 1.5rem;
        }

        .modal-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: #333;
        }

        .modal-author {
            color: #666;
            font-size: 0.9rem;
            margin-top: 0.25rem;
        }

        .rating-section {
            margin-bottom: 1.5rem;
        }

        .rating-label {
            font-size: 0.9rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
            display: block;
        }

        .star-rating-input {
            display: flex;
            gap: 0.5rem;
            font-size: 1.5rem;
        }

        .star-rating-input input[type="radio"] {
            display: none;
        }

        .star-rating-input label {
            cursor: pointer;
            color: #ddd;
        }

        .star-rating-input input[type="radio"]:checked ~ label {
            color: #ffd700;
        }

        .review-section {
            margin-bottom: 1.5rem;
        }

        .review-textarea {
            width: 100%;
            min-height: 150px;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-family: inherit;
            font-size: 0.95rem;
            resize: vertical;
        }

        .review-textarea:focus {
            outline: none;
            border-color: #7c3aed;
        }

        .modal-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
        }

        .save-btn, .cancel-btn {
            padding: 0.6rem 1.5rem;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9rem;
            border: 1px solid #ddd;
            text-decoration: none;
            display: inline-block;
        }

        .save-btn {
            background-color: #333;
            color: white;
            border-color: #333;
        }

        .save-btn:hover {
            background-color: #555;
        }

        .cancel-btn {
            background-color: white;
            color: #333;
        }

        .cancel-btn:hover {
            background-color: #f0f0f0;
        }
    </style>
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
                                    <!-- Add your edit functionality here -->
                                    <button class="edit-btn">Edit</button>
                                    <!-- Add your delete functionality here -->
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

    <!-- Edit Review Modal (add condition to show/hide based on your PHP logic) -->
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
                        <!-- Implement your star rating system here -->
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