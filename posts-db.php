<?php

function getClubPosts($clubName) {
    global $db;
    
    try {
        // Get main posts (newest first) and replies (oldest first within each parent)
        $query = "SELECT userID, bookClubName, timestamp, content, parentPostID
                  FROM Posts
                  WHERE bookClubName = :clubName
                  ORDER BY 
                    CASE WHEN parentPostID IS NULL THEN 0 ELSE 1 END,
                    CASE WHEN parentPostID IS NULL THEN timestamp END DESC,
                    CASE WHEN parentPostID IS NOT NULL THEN parentPostID END,
                    CASE WHEN parentPostID IS NOT NULL THEN timestamp END ASC";
        
        $statement = $db->prepare($query);
        $statement->bindValue(':clubName', $clubName);
        $statement->execute();
        $results = $statement->fetchAll();
        $statement->closeCursor();
        
        return $results;
    } catch (PDOException $e) {
        return [];
    }
}

function createPost($userId, $clubName, $content, $parentPostID = null) {
    global $db;
    
    try {
        $query = "INSERT INTO Posts (userID, bookClubName, content, parentPostID) 
                  VALUES (:userID, :bookClubName, :content, :parentPostID)";
        
        $statement = $db->prepare($query);
        $statement->bindValue(':userID', $userId);
        $statement->bindValue(':bookClubName', $clubName);
        $statement->bindValue(':content', $content);
        $statement->bindValue(':parentPostID', $parentPostID);
        $statement->execute();
        $statement->closeCursor();
        
        return ['success' => true, 'message' => 'Post created successfully!'];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Error creating post: ' . $e->getMessage()];
    }
}

function getPostReplies($parentPostID) {
    global $db;
    
    try {
        $query = "SELECT userID, bookClubName, timestamp, content, parentPostID
                  FROM Posts
                  WHERE parentPostID = :parentPostID
                  ORDER BY timestamp ASC";
        
        $statement = $db->prepare($query);
        $statement->bindValue(':parentPostID', $parentPostID);
        $statement->execute();
        $results = $statement->fetchAll();
        $statement->closeCursor();
        
        return $results;
    } catch (PDOException $e) {
        return [];
    }
}

function generatePostID($userId, $clubName, $timestamp) {
    // Parent Post ID Format: userID-clubName-timestamp
    return $userId . '-' . $clubName . '-' . $timestamp;
}

function organizePostsWithReplies($posts) {
    $organized = [];
    $repliesMap = [];
    
    // Separate main posts and replies
    foreach ($posts as $post) {
        if ($post['parentPostID'] === null || $post['parentPostID'] === 'NULL') {
            // Main post
            $postID = generatePostID($post['userID'], $post['bookClubName'], $post['timestamp']);
            $post['replies'] = [];
            $organized[$postID] = $post;
        } else {
            // Reply to main post
            if (!isset($repliesMap[$post['parentPostID']])) {
                $repliesMap[$post['parentPostID']] = [];
            }
            $repliesMap[$post['parentPostID']][] = $post;
        }
    }
    
    // Attach replies to their parent posts
    foreach ($organized as $postID => &$post) {
        if (isset($repliesMap[$postID])) {
            $post['replies'] = $repliesMap[$postID];
        }
    }
    
    return array_values($organized);
}

?>