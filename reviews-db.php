<?php
    function getUserReviews($userID) {
        global $db;

        $query = "SELECT R.ISBN, B.title, R.rating, R.description, R.timestamp, B.coverImage, B.author
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
        try {
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

            return true;

        } catch (PDOException $e) {
            // check if duplicate
            if ($e->getCode() == 23000) {  // integrity constraint violation
                return "duplicate";
            }
            else {
                return false;
            }
        }

    }
    

    function getUserRatingForBook($userID, $ISBN) {
        global $db;

        $query = "SELECT rating 
              FROM Reviews 
              WHERE userID = :userID AND ISBN = :ISBN
              ORDER BY timestamp DESC
              LIMIT 1";

        $statement = $db->prepare($query);
        $statement->bindValue(':userID', $userID);
        $statement->bindValue(':ISBN', $ISBN);
        $statement->execute();

        $rating = $statement->fetchColumn();
        $statement->closeCursor();

        return $rating ?: 0;   // return 0 if none -- also used to check if you've already left a review
    }
        
    function getReviewsForBook($ISBN) {
        global $db;

        $query = "SELECT R.userID, R.rating, R.description, R.timestamp, U.userID
                FROM Reviews R
                JOIN Users U ON R.userID = U.userID
                WHERE R.ISBN = :ISBN
                ORDER BY R.timestamp DESC;"; 

        $statement = $db->prepare($query);
        $statement->bindValue(':ISBN', $ISBN);
        $statement->execute();
        $results = $statement->fetchAll();
        $statement->closeCursor();

        return $results;

    }

    /*
    function getUserReviewByBook($userID, $isbn) {
        global $db;

        $query = "SELECT * FROM Reviews 
                  WHERE userID = :userID AND ISBN = :ISBN";
        $statement = $db->prepare($query);
        $statement->bindValue(':userID', $userID);
        $statement->bindValue(':ISBN', $ISBN);
        $statement->execute();
        $results = $statement->fetch(); // fetch instead of fetchAll since it should be only one row
        $statement->closeCursor();

        return $results;
    }
        */
    
    function updateReview($userID, $ISBN, $rating, $description) {
        try {
            global $db;
        
            $query = "UPDATE Reviews
                        SET rating = :rating,
                        description = :description,
                        timestamp = CURRENT_TIMESTAMP
                        WHERE userID = :userID AND ISBN = :ISBN;";

            $statement = $db->prepare($query);
            $statement->bindValue(':rating', $rating);
            $statement->bindValue(':description', $description);
            $statement->bindValue(':userID', $userID);
            $statement->bindValue(':ISBN', $ISBN);
            
            $statement->execute();
            $statement->closeCursor();
            return true;
        } catch (PDOException $e) {
            return false;
        }


    }


?>