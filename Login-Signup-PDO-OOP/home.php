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
?>
<!DOCTYPE html>
<html>
<head>
<?php include('includes/links_inc.php'); ?>
<title>welcome - <?php print($userRow['user_email']); ?></title>
</head>
<body>
<?php include('includes/navbar_inc.php'); ?>
<div class="clearfix"></div>   
<div class="container-fluid" style="margin-top:80px;">
    <div class="container">
    	<label class="h5">welcome : <?php print($userRow['user_name']); ?></label>
        <hr />
        <p class="h4"> <a href="getAppointments.php" target="_self"> - heeft u al een afspraak gemaakt? </a></p>
		 <p class="h4"> <a href="addAppointments.php" target="_self"> - Maak een nieuwe Afspraak:</a></p>   
    </div>
</div>
</body>
</html>