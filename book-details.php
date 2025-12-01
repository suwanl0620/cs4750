<?php 
require_once 'auth.php';
require('connect-db.php');
require('book-db.php');
require('reviews-db.php');

// error catching stuff
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Get ISBN from URL parameter
$isbn = $_GET['isbn'] ?? null;

if (!$isbn) {
    header('Location: homepage.php');
    exit;
}

// Fetch book details
$book = getBookByISBN($isbn);

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

// let user submit/write a review
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['user_id'])) {
        // if user is not logged in, redirect to login page
        header("Location: login.php");
        exit();
    }
    
    if (!empty($_POST['submit_review'])) {
        addReview(
            $_SESSION['user_id'],      // user ID from session
            $_POST['isbn'],           // book ISBN
            $_POST['rating'],         // star rating
            $_POST['description']     // review text
        );

        // refresh the list of reviews
        $user_reviews = getUserReviews($_SESSION['user_id']);

        // redirect page to avoid duplicate submissions
        header("Location: book-details.php?isbn=" . $_POST['isbn']);
        exit();
    }
}




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($book['title']); ?> - TopShelf</title>
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
            text-decoration: none;
            color: #333;
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

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 3rem 2rem;
        }

        .book-details {
            display: flex;
            gap: 3rem;
            background-color: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .book-cover-section {
            flex-shrink: 0;
        }

        .book-cover {
            width: 240px;
            height: 360px;
            background-color: #ddd;
            border-radius: 4px;
            background-size: cover;
            background-position: center;
            margin-bottom: 1rem;
        }

        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            align-items: center;
        }

        .action-btn {
            padding: 0.75rem 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: background-color 0.2s;
        }

        .action-btn:hover {
            text-decoration: none;
        }

        .add-to-list-btn {
            background-color: #333;
            color: white;
        }

        .add-to-list-btn:hover {
            background-color: #555;
        }

        .review-btn {
            background-color: white;
            color: #333;
            border: 1px solid #ddd;
        }

        .review-btn:hover {
            background-color: #f9f9f9;
        }

        .star-selector {
            display: flex;
            gap: 0.25rem;
            justify-content: center;
            font-size: 1.5rem;
            margin-top: 0.5rem;
        }

        .star-selector span {
            cursor: pointer;
        }

        .rating-label {
            text-align: center;
            font-size: 0.85rem;
            color: #666;
            margin-top: 0.5rem;
        }

        .book-info-section {
            flex: 1;
        }

        .book-title {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
            color: #333;
        }

        .book-author {
            font-size: 1.1rem;
            color: #666;
            margin-bottom: 1rem;
        }

        .star-rating {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.5rem;
            margin-bottom: 2rem;
        }

        .rating-text {
            font-size: 0.95rem;
            color: #666;
        }

        .book-description {
            color: #333;
            font-size: 1rem;
            line-height: 1.7;
            margin-bottom: 1.5rem;
        }

        .book-description p {
            margin-bottom: 1rem;
        }

        /* copy-pasted from my reviews style */
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


        .book-meta {
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid #e0e0e0;
        }

        .meta-item {
            display: flex;
            flex-direction: column;
        }

        .meta-label {
            font-size: 0.85rem;
            color: #999;
            margin-bottom: 0.25rem;
        }

        .meta-value {
            font-size: 0.95rem;
            color: #333;
        }
    </style>
</head>
<body>
    <?php include('header.php'); ?>

    <div class="container">
        <div class="book-details">
            <div class="book-cover-section">
                <div class="book-cover" 
                     style="background-image: url('<?php echo htmlspecialchars($book['coverImage']); ?>');">
                </div>
                <div class="action-buttons">
                    <button class="action-btn add-to-list-btn">⭐ Add to List</button>
        
                    <a href="?isbn=<?php echo $isbn; ?>&review=1" class="action-btn review-btn">Review</a>


                <?php if (isset($_GET['review']) && $_GET['review'] == 1): ?>
                    <div style="border:1px solid #ccc; padding:15px; margin-top:20px;">
                        <h3>Write a Review</h3>

                        <form action="book-details.php?isbn=<?php echo $isbn; ?>" method="POST">
                            <!-- Send ISBN to backend -->
                            <input type="hidden" name="isbn" value="<?php echo $isbn; ?>">

                            <label>Rating:</label><br>
                            <select name="rating" required>
                                <option value="">Select...</option>
                                <option value="5">5 - Excellent</option>
                                <option value="4">4 - Good</option>
                                <option value="3">3 - Average</option>
                                <option value="2">2 - Poor</option>
                                <option value="1">1 - Terrible</option>
                            </select>
                            <br><br>

                            <label>Your Review:</label><br>
                            <textarea name="description" rows="4" cols="40" required></textarea>
                            <br><br>

                            <button type="submit" name="submit_review" value="1">Submit Review</button>
                        </form>

                        <br>
                        <a href="book-details.php?isbn=<?php echo $isbn; ?>">Cancel</a>
                    </div>
                <?php endif; ?>
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
                <section class="hero">  <!-- maybe change this class later for formatting -->
                    <h1>Book Reviews</h1>
                </section>
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
</body>
</html>