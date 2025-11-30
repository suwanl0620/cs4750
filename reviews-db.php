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


    function addReview($userID, $ISBN, $rating, $description) {
        global $db;

        $query = "INSERT INTO Reviews (userID, ISBN, rating, description, timestamp)
              VALUES (:userID, :ISBN, :rating, :description, CURRENT_TIMESTAMP)";


        $statement = $db->prepare($query);
        $statement->bindValue(':userID', $userID);
        $statement->bindValue(':ISBN', $ISBN);
        $statement->bindValue(':rating', $rating);
        $statement->bindValue(':description', $description);        
        $statement->execute();
        $statement->closeCursor();
    }

?>