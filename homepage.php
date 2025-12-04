<?php
require_once 'auth.php';
require('connect-db.php'); // only let user connect if they can connect to the database
require('book-db.php');

// Pick list to show based on user selection or default
$listName = $_GET['list'] ?? 'Hardcover Fiction';
$date = $_GET['date'] ?? '2025-09-27';  // Default date

// take in post requests to set variables (otherwise default)
/*
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // if hardcover button clicked
    if (!empty($_POST[]) ) {
        $listname = "Hardcover Nonfiction";
    }
    // if fiction button clicked
    // if combined button
}
    */

// Fetch the books
$top_books = getBooksByList($listName, $date);

// For displaying active button styling
function isActiveList($current, $selected) {
    return $current === $selected ? 'active-filter' : '';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TopShelf</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include('header.php'); ?>

    <section class="hero">
        <h1>TopShelf</h1>
    </section>

    <div class="container">
        <div class="section-header">
            <h2>Top Books</h2>
            <!-- list filter buttons -->
            <div class="filter-container">
            <a href="?list=Combined%20Print%20%26%20E-Book%20Fiction&date=<?php echo $date; ?>">
                <button class="filter-btn <?php echo isActiveList($listName, 'Combined Print & E-Book Fiction'); ?>">
                    Combined Print & E-Book Fiction
                </button>
            </a>
            <a href="?list=Hardcover%20Fiction&date=<?php echo $date; ?>">
                <button class="filter-btn <?php echo isActiveList($listName, 'Hardcover Fiction'); ?>">
                    Hardcover Fiction
                </button>
            </a>
            <a href="?list=Hardcover%20Nonfiction&date=<?php echo $date; ?>">
                <button class="filter-btn <?php echo isActiveList($listName, 'Hardcover Nonfiction'); ?>">
                    Hardcover Nonfiction
                </button>
            </a>
            </div>
            <!-- date filter dropdown menu -->
            <div class="date-container">
                <form method="get" style="display:flex; gap:0.5rem; align-items:center;">
                    <input type="hidden" name="list" value="<?php echo htmlspecialchars($listName); ?>">
                    <label for="date">Date:</label>
                        <select id="date" name="date" onchange="this.form.submit()" class="date-dropdown">
                        <?php
                            // Fetch all available dates for this list
                            $availableDates = getAvailableDatesForList($listName);
                            foreach ($availableDates as $row) {
                                $selected = ($row['date'] === $date) ? 'selected' : '';
                                echo "<option value='{$row['date']}' $selected>{$row['date']}</option>";
                            }
                        ?>
                    </select>
                </form>
            </div>
        </div>
        
        <!-- top books table -->
        <div class="book-grid">
            <?php if (!empty($top_books)): ?>
                <?php foreach ($top_books as $book): ?>
                <div class="book-card" style="cursor: pointer;" onclick="window.location.href='book-details.php?isbn=<?php echo urlencode($book['ISBN']); ?>'">
                    <div class="book-cover"
                        style="background-image: url('<?php echo htmlspecialchars($book['coverImage']); ?>');
                                background-size: cover;
                                background-position: center;">
                    </div>
                    <div class="book-info">
                        <p><strong>Rank #<?php echo htmlspecialchars($book['listRank']); ?></strong></p>
                        <h3 class="book-title"><?php echo htmlspecialchars($book['title']); ?></h3>
                        <p class="book-author"><?php echo htmlspecialchars($book['author']); ?></p>
                        

                        <!-- Rating Display -->
                        <?php
                            $rating = (float)($book['avgRating'] ?? 0);
                            $filledStars = floor($rating);
                            $halfStar = ($rating - $filledStars >= 0.5);
                            $emptyStars = 5 - $filledStars - ($halfStar ? 1 : 0);
                        ?>
                        <div class="star-rating">
                            <?php echo str_repeat('★', $filledStars); ?>
                            <?php if ($halfStar) echo '✩'; ?>
                            <?php echo str_repeat('☆', $emptyStars); ?>
                            <span style="font-size:0.9rem; color:#666;">
                                (<?php echo htmlspecialchars(number_format($rating, 1)); ?> / 5, 
                                <?php echo htmlspecialchars($book['ratingCount']); ?> reviews)
                            </span>
                        </div>

                        <p class="book-description"><?php echo htmlspecialchars($book['description']); ?></p>
                        <?php if (function_exists('is_logged_in') && is_logged_in()): ?>  <!-- only show menu buttons if logged in -->
                            <button class="add-to-list-btn" onclick="event.stopPropagation();">⭐ Add To List</button>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No books found for <?php echo htmlspecialchars($listName); ?> on <?php echo htmlspecialchars($date); ?>.</p>
            <?php endif; ?>
        </div>
                <!--
        <div class="book-grid">
            <div class="book-card">
                <div class="book-cover"></div>
                <div class="book-info">
                    <h3 class="book-title">Title</h3>
                    <p class="book-author">AUTHOR</p>
                    <div class="star-rating">☆ ☆ ☆ ☆ ☆</div>
                    <p class="book-description">description of book goes here</p>
                    <button class="add-to-list-btn">⭐ Add To List</button>
                </div>
            </div>
        </div>
    -->

        <!-- saved books -->
        <div class="saved-books-section">
            <h2 style="margin-bottom: 2rem;">Your Saved Books</h2>
            <div class="saved-books-grid">
                <div class="saved-book-card">
                    <div class="saved-book-cover"></div>
                    <h3 class="saved-book-title">Title</h3>
                    <p class="saved-book-author">AUTHOR</p>
                    <p class="saved-book-description">description of book goes here</p>
                </div>

                <div class="saved-book-card">
                    <div class="saved-book-cover"></div>
                    <h3 class="saved-book-title">Title</h3>
                    <p class="saved-book-author">AUTHOR</p>
                    <p class="saved-book-description">description of book goes here</p>
                </div>

                <div class="saved-book-card">
                    <div class="saved-book-cover"></div>
                    <h3 class="saved-book-title">Title</h3>
                    <p class="saved-book-author">AUTHOR</p>
                    <p class="saved-book-description">description of book goes here</p>
                </div>
            </div>
        </div>
    </div>



</body>
</html>