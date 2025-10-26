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
    

}

function updateRequest($reqId, $reqDate, $roomNumber, $reqBy, $repairDesc, $reqPriority)
{


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
