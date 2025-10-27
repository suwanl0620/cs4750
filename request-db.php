<?php
function addRequests($reqDate, $roomNumber, $reqBy, $repairDesc, $reqPriority)
{
    global $db;
    // if no columns are skipped, you don't need to explicitly specify columns/values
    $query = "INSERT INTO requests
              (reqDate, roomNumber, reqBy, repairDesc, reqPriority)
              VALUES
              ('" . $reqDate . "', '" . $roomNumber . "', '" . $reqBy . "', '" . $repairDesc . "', '" . $reqPriority . "')";
    try {
        $statement = $db->prepare($query);
        $statement->execute();
        $statement->closeCursor();
    } catch (PDOException $e) {
        echo $e->getMessage();
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

function getAllRequests()
{
    global $db;
    $query = "SELECT * FROM requests";
   
    // run query
    $statement = $db->prepare($query); // compile the query
    $statement->execute();  // run query, currently not saving the result
    $results = $statement->fetchAll(); // fetchAll returns an array of all the rows in result (fetch just returns first row)
    $statement->closeCursor();

    return $results;

}

function getRequestById($id)  
{
    global $db;

    $query = "SELECT * FROM requests WHERE reqID = :id";
    $statement = $db->prepare($query);
    $statement->bindValue(':id', $id);
    $statement->execute();
    $results = $statement->fetch(); // fetch instead of fetchAll since it should be only one row
    $statement->closeCursor();

    return $results;
}

function updateRequest($reqId, $reqDate, $roomNumber, $reqBy, $repairDesc, $reqPriority)
{
    global $db;

    $query = "UPDATE requests SET reqDate = :reqDate, roomNumber = :roomNumber, reqBy = :reqBy, repairDesc = :repairDesc, reqPriority = :reqPriority WHERE reqID = :reqId";
    $statement = $db->prepare($query);
    $statement->bindValue(':reqDate', $reqDate);
    $statement->bindValue(':roomNumber', $roomNumber);
    $statement->bindValue(':reqBy', $reqBy);
    $statement->bindValue(':repairDesc', $repairDesc);
    $statement->bindValue(':reqPriority', $reqPriority);
    $statement->bindValue(':reqId', $reqId);
    $statement->execute();
    $statement->closeCursor(); // can also insert try/catch block in case execution fails, display error message

    return $results;
}

function deleteRequest($reqId)
{
    global $db;

    $query = "DELETE FROM requests WHERE reqId = :fillin";
    try {
        $statement = $db->prepare($query);
        $statement->bindValue(':fillin', $reqId); // the actual value you want to fill in
        $statement->execute();
        $statement->closeCursor();  // don't need to return anything
    } catch (PDOException $e) {
        echo $e->getMessage();
    } catch (Exception $e) {
        echo $e->getMessage();
    }
    
}

?>
