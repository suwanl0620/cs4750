<?php
    function getAllClubs() {
        global $db;

        $query = "SELECT name, description
                  FROM BookClubs
                  ORDER BY name ASC";

        $statement = $db->prepare($query);
        $statement->execute();
        $results = $statement->fetchAll();
        $statement->closeCursor();

        return $results;
    }

    function getClubByName($clubName) {
        global $db;
    
        $query = " SELECT name, description
            FROM BookClubs
            WHERE name = :clubName
        ";
        try {
            $statement = $db->prepare($query);
            $statement->bindValue(':clubName', $clubName);
            $statement->execute();
            $result = $statement->fetch();
            $statement->closeCursor();
            
            return $result;
        } catch (PDOException $e) {
            echo "Error fetching club details: " . $e->getMessage();
            return null;
        }
    }
?>