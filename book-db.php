<?php
    function getBooksByList($listName, $date = '2025-09-27') {
        global $db; // use the connection from connect-db.php
    

        $query = "
            SELECT 
                b.ISBN,
                b.title,
                b.author,
                b.description,
                b.coverImage,
                bs.listRank,
                ROUND(AVG(r.rating), 1) AS avgRating,
                COUNT(r.rating) AS ratingCount
                FROM Books b
                INNER JOIN Bestsellers bs 
                    ON b.ISBN = bs.ISBN
                INNER JOIN BestsellerLists bl 
                    ON bs.listName = bl.listName AND bs.date = bl.date
                LEFT JOIN Reviews r 
                    ON b.ISBN = r.ISBN
                WHERE bl.listName = :listName
                AND bl.date = :date
                GROUP BY b.ISBN, b.title, b.author, b.description, b.coverImage, bs.listRank
                ORDER BY bs.listRank ASC;
            ";

    
        $statement = $db->prepare($query);
        $statement->bindValue(':listName', $listName);
        $statement->bindValue(':date', $date);
        $statement->execute();
        $results = $statement->fetchAll();
        $statement->closeCursor();
    
        return $results;
    }

    function getAvailableDatesForList($listName) {
        global $db;
        $query = "SELECT DISTINCT date FROM BestsellerLists WHERE listName = :listName ORDER BY date DESC";
        $statement = $db->prepare($query);
        $statement->bindValue(':listName', $listName);
        $statement->execute();
        $results = $statement->fetchAll();
        $statement->closeCursor();
        return $results;
    }

    function getBookByISBN($isbn) {
        global $db;
    
        $query = "
            SELECT 
                b.ISBN,
                b.title,
                b.author,
                b.description,
                b.coverImage,
                ROUND(AVG(r.rating), 1) AS avgRating,
                COUNT(DISTINCT r.rating) AS ratingCount
            FROM Books b
            LEFT JOIN Reviews r ON b.ISBN = r.ISBN
            WHERE b.ISBN = :isbn
            GROUP BY b.ISBN, b.title, b.author, b.description, b.coverImage
        ";
        // COUNT(DISTINCT CASE WHEN r.reviewText IS NOT NULL AND r.reviewText != '' THEN r.userID END) AS reviewCount
    
        try {
            $statement = $db->prepare($query);
            $statement->bindValue(':isbn', $isbn);
            $statement->execute();
            $result = $statement->fetch();
            $statement->closeCursor();
            
            return $result;
        } catch (PDOException $e) {
            echo "Error fetching book details: " . $e->getMessage();
            return null;
        }
    }
    
?>