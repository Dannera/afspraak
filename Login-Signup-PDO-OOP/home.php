<?php
//get session 
	require_once("session.php");
	require_once("class.user.php");

	$auth_user = new USER();
	$user_id = $_SESSION['user_session'];
	//get users from db
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
        <p class="h4"> <a href="afspraken.php" target="_self"> - heeft u al een afspraak gemaakt? </a></p>
		 <p class="h4"> <a href="afsprakmaken.php" target="_self"> - Maak een nieuwe Afspraak:</a></p>   
    </div>
</div>
</body>
</html>