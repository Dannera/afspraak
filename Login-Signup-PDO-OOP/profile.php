<?php
//user session
	require_once("session.php");
	
	require_once("class.user.php");
	$auth_user = new USER();
	$user_id = $_SESSION['user_session'];
	
	$stmt = $auth_user->runQuery("SELECT * FROM users WHERE user_id=:user_id");
	$stmt->execute(array(":user_id"=>$user_id));
	
	$userRow=$stmt->fetch(PDO::FETCH_ASSOC);

//          ==/ THE-END /==    
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen"> 
<link href="bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" media="screen"> 
<!-- <script type="text/javascript" src="jquery-1.11.3-jquery.min.js"></script> -->
<link rel="stylesheet" href="style.css" type="text/css"  />
<title>welcome - <?php print($userRow['user_email']); ?></title>
</head>

<body>


<?php include('navbar.php'); ?>

	<div class="clearfix"></div>
	
    <div class="container-fluid" style="margin-top:80px;">
	
    <div class="container">
    
    	<label class="h5">welcome : <?php print($userRow['user_name']); ?></label>
        <hr />
        
        <table class="table">
          <tr>
          <td><h3>User name:</h3></td> <td ><p> <?php print($userRow['user_name']); ?> </p> </td>
           <td><i>Edit</i></td>
           </tr>
           <tr>
          <td><h3>Email:</h3></td> <td ><p> <?php print($userRow['user_email']); ?> </p> </td>
           <td><i>Edit</i></td>
           </tr>
           <tr>
          <td><h3>Password:</h3></td> <td ><p> <?php print($userRow['user_password'] = "*****#****"); ?> </p> </td>
           <td><i>Edit</i></td>
           </tr>
           </table>
       

  
    
    </div>

</div>




<script src="bootstrap/js/bootstrap.min.js"></script>

</body>
</html>