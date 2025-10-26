<?php
// header('Access-Control-Allow-Origin: http://localhost:4200');
// header('Access-Control-Allow-Origin: *');
// header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Accept, Origin, Authorization');
// header('Access-Control-Max-Age: 1000');
// header('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
?>

<?php require('connect-db.php'); // only let user connect if they can connect to the database
require('request-db.php');

// for debugging
$list_of_requests = getAllRequests();
var_dump($list_of_requests);

?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') { // $ indicates variable, SERVER array is predefined in php
  // if the Add button is clicked
  if (!empty($_POST['addBtn'])) {
    addRequests($_POST['requestedDate'],
                $_POST['roomNo'],
                $_POST['requestedBy'],
                $_POST['requestDesc'],
                $_POST['priority_option']);
    $list_of_requests = getAllRequests();  // refresh the list after adding to immediately display

  } else if (!empty($_POST['deleteBtn'])) {  
    // if delete button clicked, call delete function
    deleteRequest($_POST['reqId']);    // only need reqId since that's the primary key
    $list_of_requests = getAllRequests(); 
  }
}

?>

<!DOCTYPE html>
<html>
<head>  <!--metadata, about page connections, etc -->
  <meta charset="utf-8">    
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="Lily, Mina, Suwan">
  <meta name="description" content="Maintenance request form, a small/toy web app for ISP homework assignment, used by CS 3250 (Software Testing)">
  <meta name="keywords" content="CS 3250, Upsorn, Praphamontripong, Software Testing">
  <link rel="icon" href="https://www.cs.virginia.edu/~up3f/cs3250/images/st-icon.png" type="image/png" />  
  
  <title>Maintenance Services</title>
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">  
  <link rel="stylesheet" href="maintenance-system.css">  
</head>

<body>  
<?php include('header.php'); ?>
<div class="container">
  <div class="row g-3 mt-2">
    <div class="col">
      <h2>Maintenance Request</h2>
    </div>  
  </div>
  
  <!---------------->
  <!-- whenever the form is submitted, it is processed as a post request -->
  <form method="post" action="<?php $_SERVER['PHP_SELF'] ?>" onsubmit="return validateInput()">
    <table style="width:98%">
      <tr>
        <td width="50%">
          <div class='mb-3'>
            Requested date:
            <input type='text' class='form-control' 
                   id='requestedDate' name='requestedDate' 
                   placeholder='Format: yyyy-mm-dd' 
                   pattern="\d{4}-\d{1,2}-\d{1,2}" 
                   value="" />
          </div>
        </td>
        <td>
          <div class='mb-3'>
            Room Number:
            <input type='text' class='form-control' id='roomNo' name='roomNo' 
                   value="" />
          </div>
        </td>
      </tr>
      <tr>
        <td colspan=2>
          <div class='mb-3'>
            Requested by: 
            <input type='text' class='form-control' id='requestedBy' name='requestedBy'
                   placeholder='Enter your name'
                   value="" />
          </div>
        </td>
      </tr>
      <tr>
        <td colspan=2>
          <div class="mb-3">
            Description of work/repair:
            <input type='text' class='form-control' id='requestDesc' name='requestDesc'
                   value="" />
        </div>
        </td>
      </tr>
      <tr>
        <td colspan=2>
          <div class='mb-3'>
            Requested Priority:
            <select class='form-select' id='priority_option' name='priority_option'>
              <option selected></option>
              <option value='high' >
                High - Must be done within 24 hours</option>
              <option value='medium' >
                Medium - Within a week</option>
              <option value='low' >
                Low - When you get a chance</option>
            </select>
          </div>
        </td>
      </tr>
    </table>

    <div class="row g-3 mx-auto">    
      <div class="col-4 d-grid ">
        <!-- use 'addBtn' to refer to this button when processing a php request -->
      <input type="submit" value="Add" id="addBtn" name="addBtn" class="btn btn-dark"
           title="Submit a maintenance request" />                  
      </div>	    
      <div class="col-4 d-grid ">
      <input type="submit" value="Confirm update" id="cofmBtn" name="cofmBtn" class="btn btn-primary"
           title="Update a maintenance request" />                  
      </div>	    
      <div class="col-4 d-grid">
        <input type="reset" value="Clear form" name="clearBtn" id="clearBtn" class="btn btn-secondary" />
      </div>      
    </div>  
    <div>
  </div>  
  </form>

</div>

<!-- request table code-->
<hr/>
<div class="container">
<h3>List of requests</h3>
<div class="row justify-content-center">  
<table class="w3-table w3-bordered w3-card-4 center" style="width:100%">
  <thead>
  <tr style="background-color:#B0B0B0">
    <th width="30%"><b>ReqID</b></th>
    <th width="30%"><b>Date</b></th>        
    <th width="30%"><b>Room#</b></th> 
    <th width="30%"><b>By</b></th>
    <th width="30%"><b>Description</b></th>        
    <th width="30%"><b>Priority</b></th> 
    <th><b>Update?</b></th>
    <th><b>Delete?</b></th>
  </tr>
  </thead>
<!-- populate table with data from the list_of_requests array -->
  <?php foreach ($list_of_requests as :req_info): ?>
  <tr>  <!-- loop through each request info associative array, td=column, tr = row -->
    <td> <?php echo req_info['reqId']; ?> </td>  <!-- first column -->
    <td> <?php echo req_info['reqDate']; ?> </td>
    <td> <?php echo req_info['roomNumber']; ?> </td>
    <td> <?php echo req_info['reqBy']; ?> </td>
    <td> <?php echo req_info['repairDesc']; ?> </td>
    <td> <?php echo req_info['reqPriority']; ?> </td>
    <td> <!-- update button -->
      <form action="request.php" method="post"> <!-- send post request to that file (as object) -->
        <!--specify how data will be packaged and sent to the server -->

        <!-- delete button, using bootstrap btn class (optional), title text appears when hovering mouse -->
        <input type="submit" value="Update" 
               name ="updateBtn" class="btn btn-bright" 
               title="Click to update this request"
        /> 
        <input type="hidden" name="reqId" 
               value="<?php echo req_info['reqId']; ?>" />

      </form>
    </td>
    <td> <!-- delete button -->
      <form action="request.php" method="post"> <!-- send post request to that file (as object) -->
        <!--specify how data will be packaged and sent to the server -->

        <!-- delete button, using bootstrap btn class (optional), title text appears when hovering mouse -->
        <input type="submit" value="Delete" 
               name ="deleteBtn" class="btn btn-danger" 
               title="Click to delete this request"
        /> 
        <input type="hidden" name="reqId" 
               value="<?php echo req_info['reqId']; ?>" />

      </form>
    </td>
  </tr>
  <?php endforeach; ?>
<!-- end of table -->
</table>
</div>   


<br/><br/>

<?php // include('footer.html') ?> 

<!-- <script src='maintenance-system.js'></script> -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</body>
</html>