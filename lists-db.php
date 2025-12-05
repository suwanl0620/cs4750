<?php
   function removeFromRead($userID, $ISBN) {
        try {
            global $db;

            // remove from ReadBooks table
            $query = "DELETE FROM ReadBooks
                WHERE ISBN = :ISBN AND userID = :userID";

            $statement = $db->prepare($query);
            $statement->bindValue(':ISBN', $ISBN);
            $statement->bindValue(':userID', $userID);
            $statement->execute();
            $statement->closeCursor();

            return true;

        } catch (PDOException $e) {
            return false;
        }
    }

    function removeFromWantToRead($userID, $ISBN) {
        try {
            global $db;

            // remove from WantToRead table
            $query = "DELETE FROM WantToRead
                WHERE ISBN = :ISBN AND userID = :userID";

            $statement = $db->prepare($query);
            $statement->bindValue(':ISBN', $ISBN);
            $statement->bindValue(':userID', $userID);
            $statement->execute();
            $statement->closeCursor();

            return true;

        } catch (PDOException $e) {
            return false;
        }
    }

    function markAsRead($userID, $ISBN) {
        try {
            global $db;

            // insert into ReadBooks table
            $query = "INSERT INTO ReadBooks (ISBN, userID)
                VALUES (:ISBN, :userID)";

            $statement = $db->prepare($query);
            $statement->bindValue(':ISBN', $ISBN);
            $statement->bindValue(':userID', $userID);
            $statement->execute();
            $statement->closeCursor();

            // remove from WantToRead table if exists
            $query = "DELETE FROM WantToRead
                WHERE ISBN = :ISBN AND userID = :userID";

            $statement = $db->prepare($query);
            $statement->bindValue(':ISBN', $ISBN);
            $statement->bindValue(':userID', $userID);
            $statement->execute();
            $statement->closeCursor();

            return true;

        } catch (PDOException $e) {
            return false;
        }
    }

    function wantToRead($userID, $ISBN) {
        try {
            global $db;

            $query = "INSERT INTO WantToRead (ISBN, userID)
                VALUES (:ISBN, :userID)";

            $statement = $db->prepare($query);
            $statement->bindValue(':ISBN', $ISBN);
            $statement->bindValue(':userID', $userID);
            $statement->execute();
            $statement->closeCursor();

            return true;

        } catch (PDOException $e) {
            return false;
        }
    }

    function getReadList($userID) {
        global $db;

        $query = "SELECT B.ISBN, B.title, B.author, B.description, B.coverImage, W.timestamp,
                ROUND(AVG(R.rating), 1) AS avgRating,
                COUNT(R.rating) AS ratingCount
                FROM ReadBooks W
                LEFT JOIN Books B ON W.ISBN = B.ISBN
                LEFT JOIN Reviews R ON B.ISBN = R.ISBN
                WHERE W.userID = :userID
                GROUP BY W.ISBN
                ORDER BY timestamp DESC;";

        $statement = $db->prepare($query);
        $statement->bindValue(':userID', $userID);
        $statement->execute();
        $results = $statement->fetchAll();
        $statement->closeCursor();

        return $results;
    }

    function getWantToReadList($userID) {
        global $db;

        $query = "SELECT B.ISBN, B.title, B.author, B.description, B.coverImage,
                ROUND(AVG(R.rating), 1) AS avgRating,
                COUNT(R.rating) AS ratingCount
                FROM WantToRead W
                LEFT JOIN Books B ON W.ISBN = B.ISBN
                LEFT JOIN Reviews R ON B.ISBN = R.ISBN
                WHERE W.userID = :userID
                GROUP BY W.ISBN;";

        $statement = $db->prepare($query);
        $statement->bindValue(':userID', $userID);
        $statement->execute();
        $results = $statement->fetchAll();
        $statement->closeCursor();

        return $results;
    }
?>