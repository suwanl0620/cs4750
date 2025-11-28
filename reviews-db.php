<?php
    function getUserReviews($userID) {
        global $db;

        $query = "SELECT R.ISBN, B.title, R.rating, R.description, R.timestamp
                FROM Reviews R
                JOIN Books B ON R.ISBN = B.ISBN
                WHERE R.userID = :userID
                ORDER BY R.timestamp DESC;
                ";

        $statement = $db->prepare($query);
        $statement->bindValue(':userID', $userID);
        $statement->execute();
        $results = $statement->fetchAll();
        $statement->closeCursor();

        return $results;
    }



?>