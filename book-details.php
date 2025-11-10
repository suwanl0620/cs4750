<?php 
require('connect-db.php');
require('book-db.php');

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
    <header>
        <nav>
            <a href="homepage.php" class="logo">📚</a>
            <div class="nav-links">
                <a href="homepage.php">Home</a>
                <a href="#">My Reviews</a>
                <a href="#">Profile</a>
            </div>
        </nav>
    </header>

    <div class="container">
        <div class="book-details">
            <div class="book-cover-section">
                <div class="book-cover" 
                     style="background-image: url('<?php echo htmlspecialchars($book['coverImage']); ?>');">
                </div>
                <div class="action-buttons">
                    <button class="action-btn add-to-list-btn">⭐ Add to List</button>
                    <button class="action-btn review-btn">Review</button>
                    <div class="star-selector">
                        <span>☆</span>
                        <span>☆</span>
                        <span>☆</span>
                        <span>☆</span>
                        <span>☆</span>
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
            </div>
        </div>
    </div>
</body>
</html>