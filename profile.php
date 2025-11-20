<?php 
require_once 'auth.php';
require('connect-db.php');
// require some kind of profile db (have to do w/ mina?)
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
        <h1>My Profile</h1>
    </section>
</body>