<?php require('connect-db.php'); // only let user connect if they can connect to the database
require('request-db.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NYT Bestsellers - Book Reviews</title>
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

        .book-grid {
            display: grid;
            gap: 1.5rem;
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
    <header>
        <nav>
            <div class="logo">📚</div>
            <div class="nav-links">
                <a href="#">Home</a>
                <a href="#">My Reviews</a>
                <a href="#">Profile</a>
            </div>
            <div class="auth-buttons">
                <button class="sign-in">Sign In</button>
                <button class="register">Register</button>
            </div>
        </nav>
    </header>

    <section class="hero">
        <h1>NYT Bestsellers</h1>
    </section>

    <div class="container">
        <div class="section-header">
            <h2>Top Books</h2>
            <div class="filter-container">
                <span class="filter-icon">🔽</span>
                <button class="filter-btn">Fiction</button>
                <button class="filter-btn">Science Fiction</button>
                <button class="filter-btn">Young Adult</button>
                <button class="filter-btn">•••</button>
            </div>
        </div>

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