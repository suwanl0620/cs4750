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
    
/*

// get all books from a specific bestseller list within date range
function getBestsellerBooks($listname, $startDate = null, $endDate = null)
{
    global $db;

    $query = "SELECT DISTINCT b.ISBN, b.title, b.author, b.description, b.coverImage, 
              bs.listRank, bs.date
              FROM Books b
              JOIN Bestsellers bs ON b.ISBN = bs.ISBN
              WHERE bs.listName = :listName";
    
    if (!empty($startDate) && !empty($endDate)) {
        $query .= " AND bs.date BETWEEN :startDate AND :endDate";
    } else {
        // Default: show only the most recent date
        $query .= " AND bs.date = (SELECT MAX(date) FROM Bestsellers WHERE listName = :listName2)";
    }

    $query .= " ORDER BY bs.date DESC, bs.listRank ASC";

    try {
        $statement = $db->prepare($query);
        $statement->bindValue(':listName', $listName);
        
        if (!empty($startDate) && !empty($endDate)) {
            $statement->bindValue(':startDate', $startDate);
            $statement->bindValue(':endDate', $endDate);
        } else {
            $statement->bindValue(':listName2', $listName);
        }
        
        $statement->execute();
        $results = $statement->fetchAll();
        $statement->closeCursor();
        
        return $results;
    } catch (PDOException $e) {
        echo "Error fetching bestseller books: " . $e->getMessage();
        return [];
    }


}
*/

?>