<?php
function getAllClubs() {
    global $db;

    $query = "SELECT `name`, `description`,
              (SELECT COUNT(*) FROM Membership WHERE bookClubName = BookClubs.name) as member_count
              FROM BookClubs
              ORDER BY `name` ASC";

    $statement = $db->prepare($query);
    $statement->execute();
    $results = $statement->fetchAll();
    $statement->closeCursor();

    return $results;
}

function getClubByName($clubName) {
    global $db;

    $query = "SELECT `name`, `description`,
              (SELECT COUNT(*) FROM Membership WHERE bookClubName = BookClubs.name) as member_count
              FROM BookClubs
              WHERE `name` = :clubName";
    
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

function createClub($name, $description, $userId) {
    global $db;

    try {
        // Check if club name already exists
        $checkQuery = "SELECT `name` FROM BookClubs WHERE `name` = :name";
        $checkStmt = $db->prepare($checkQuery);
        $checkStmt->bindValue(':name', $name);
        $checkStmt->execute();
        
        if ($checkStmt->fetch()) {
            return ['success' => false, 'message' => 'A club with this name already exists.'];
        }
        $checkStmt->closeCursor();

        // Create the club
        $query = "INSERT INTO BookClubs (`name`, `description`) VALUES (:name, :description)";
        
        $statement = $db->prepare($query);
        $statement->bindValue(':name', $name);
        $statement->bindValue(':description', $description);
        $statement->execute();
        $statement->closeCursor();
        
        // Automatically join the creator to the club
        joinClub($name, $userId);
        
        return ['success' => true, 'club_name' => $name];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Error creating club: ' . $e->getMessage()];
    }
}

function joinClub($clubName, $userId) {
    global $db;

    try {
        // Check if already a member
        $checkQuery = "SELECT * FROM Membership WHERE bookClubName = :bookClubName AND userID = :userID";
        $checkStmt = $db->prepare($checkQuery);
        $checkStmt->bindValue(':bookClubName', $clubName);
        $checkStmt->bindValue(':userID', $userId);
        $checkStmt->execute();
        
        if ($checkStmt->fetch()) {
            $checkStmt->closeCursor();
            return ['success' => false, 'message' => 'You are already a member of this club.'];
        }
        $checkStmt->closeCursor();

        // Join the club
        $query = "INSERT INTO Membership (userID, bookClubName) VALUES (:userID, :bookClubName)";
        $statement = $db->prepare($query);
        $statement->bindValue(':userID', $userId);
        $statement->bindValue(':bookClubName', $clubName);
        $statement->execute();
        $statement->closeCursor();
        
        return ['success' => true, 'message' => 'Successfully joined the club!'];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Error joining club: ' . $e->getMessage()];
    }
}

function leaveClub($clubName, $userId) {
    global $db;

    try {
        $query = "DELETE FROM Membership WHERE bookClubName = :bookClubName AND userID = :userID";
        $statement = $db->prepare($query);
        $statement->bindValue(':bookClubName', $clubName);
        $statement->bindValue(':userID', $userId);
        $statement->execute();
        $statement->closeCursor();
        
        return ['success' => true, 'message' => 'Successfully left the club.'];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Error leaving club: ' . $e->getMessage()];
    }
}

function isUserMember($clubName, $userId) {
    global $db;

    try {
        $query = "SELECT * FROM Membership WHERE bookClubName = :bookClubName AND userID = :userID";
        $statement = $db->prepare($query);
        $statement->bindValue(':bookClubName', $clubName);
        $statement->bindValue(':userID', $userId);
        $statement->execute();
        $result = $statement->fetch();
        $statement->closeCursor();
        
        return $result !== false;
    } catch (PDOException $e) {
        return false;
    }
}

function getUserClubs($userId) {
    global $db;

    try {
        $query = "SELECT bc.`name`, bc.`description`,
                  (SELECT COUNT(*) FROM Membership WHERE bookClubName = bc.name) as member_count
                  FROM BookClubs bc
                  INNER JOIN Membership m ON bc.`name` = m.bookClubName
                  WHERE m.userID = :userID
                  ORDER BY bc.`name` ASC";
        
        $statement = $db->prepare($query);
        $statement->bindValue(':userID', $userId);
        $statement->execute();
        $results = $statement->fetchAll();
        $statement->closeCursor();
        
        return $results;
    } catch (PDOException $e) {
        return [];
    }
}
?>