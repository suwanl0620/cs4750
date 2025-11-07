<?php require('connect-db.php'); // only let user connect if they can connect to the database
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

        .auth-buttons {
            display: flex;
            gap: 1rem;
        }

        .auth-buttons button {
            padding: 0.5rem 1.2rem;
            border: 1px solid #333;
            background-color: white;
            cursor: pointer;
            font-size: 0.9rem;
            border-radius: 4px;
        }

        .auth-buttons button:hover {
            background-color: #f0f0f0;
        }

        .auth-buttons .register {
            background-color: #333;
            color: white;
        }

        .auth-buttons .register:hover {
            background-color: #555;
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

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .section-header h2 {
            font-size: 1.5rem;
            color: #333;
        }

        .filter-container {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .filter-icon {
            font-size: 1.2rem;
            cursor: pointer;
            padding: 0.5rem;
        }

        .filter-btn {
            padding: 0.5rem 1rem;
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9rem;
        }

        .filter-btn:hover {
            background-color: #f9f9f9;
        }

        /* for graying out selected filter */
        .active-filter {
            background-color: #d3d3d3;   
            color: #666;
            border-color: #aaa;
            cursor: default;             
            pointer-events: none;        /* make it unclickable */
        }

        .date-container {
            display: flex;
            gap: 0.5rem;
            align-items: center
        }

        .date-dropdown {
            padding: 0.4rem 0.6rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 0.9rem;
            background-color: #fff;
            cursor: pointer;
        }
        
        .date-dropdown:hover {
            background-color: #f9f9f9;
        }
        /*
        .book-grid {
            display: grid;
            gap: 1.5rem;
            max-height: calc((150px + 3rem) * 3 + 1.5rem * 2); // Height of 3 cards + gaps 
            overflow-y: auto;
            overflow-x: hidden;
            padding-right: 0.5rem;
            scroll-behavior: smooth;
        }
        */
        
        .book-table-container {
            background-color: white;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            overflow: hidden;
        }

        .book-table {
            width: 100%;
            border-collapse: collapse;
        }

        .book-table thead {
            background-color: #f9f9f9;
        }

        .book-table th {
            padding: 1rem;
            text-align: left;
            font-size: 0.9rem;
            font-weight: 600;
            color: #333;
            border-bottom: 1px solid #e0e0e0;
        }

        .book-table td {
            padding: 1rem;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: top;
        }

        .book-table tbody tr:last-child td {
            border-bottom: none;
        }

        .book-table tbody tr:hover {
            background-color: #fafafa;
        }

        .book-card {
            background-color: white;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 1.5rem;
            display: flex;
            gap: 1.5rem;
        }

        .book-cover {
            width: 100px;
            height: 150px;
            background-color: #ddd;
            border-radius: 4px;
            flex-shrink: 0;
        }

        .book-info {
            flex: 1;
        }

        .book-title {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #333;
        }

        .book-author {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .star-rating {
            color: #333;
            margin-bottom: 0.8rem;
            font-size: 1.1rem;
        }

        .book-description {
            color: #666;
            font-size: 0.95rem;
            line-height: 1.5;
            margin-bottom: 1rem;
        }

        .add-to-list-btn {
            padding: 0.5rem 1rem;
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .add-to-list-btn:hover {
            background-color: #f9f9f9;
        }

        .saved-books-section {
            margin-top: 4rem;
        }

        .saved-books-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .saved-book-card {
            background-color: white;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 1.5rem;
        }

        .saved-book-cover {
            width: 100%;
            height: 200px;
            background-color: #ddd;
            border-radius: 4px;
            margin-bottom: 1rem;
        }

        .saved-book-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.3rem;
            color: #333;
        }

        .saved-book-author {
            color: #666;
            font-size: 0.85rem;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
        }

        .saved-book-description {
            color: #666;
            font-size: 0.9rem;
            line-height: 1.4;
        }

    </style>
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
                        <h3 class="book-title"><?php echo htmlspecialchars($book['title']); ?></h3>
                        <p class="book-author"><?php echo htmlspecialchars($book['author']); ?></p>

                        <!-- ⭐️ Rating Display -->
                        <?php
                            $rating = (float)($book['avgRating'] ?? 0);
                            $filledStars = floor($rating);
                            $halfStar = ($rating - $filledStars >= 0.5);
                            $emptyStars = 5 - $filledStars - ($halfStar ? 1 : 0);
                        ?>
                        <div class="star-rating">
                            <?php echo str_repeat('⭐', $filledStars); ?>
                            <?php if ($halfStar) echo '✩'; ?>
                            <?php echo str_repeat('☆', $emptyStars); ?>
                            <span style="font-size:0.9rem; color:#666;">
                                (<?php echo htmlspecialchars(number_format($rating, 1)); ?> / 5, 
                                <?php echo htmlspecialchars($book['ratingCount']); ?> reviews)
                            </span>
                        </div>

                        <p class="book-description"><?php echo htmlspecialchars($book['description']); ?></p>
                        <p><strong>Rank #<?php echo htmlspecialchars($book['listRank']); ?></strong></p>
                        <button class="add-to-list-btn" onclick="event.stopPropagation();">⭐ Add To List</button>
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