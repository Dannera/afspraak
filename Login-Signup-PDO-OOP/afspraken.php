<?php
//session
          require_once("session.php");

//afsprakken include once
          require_once('class.afspraken.php');
          $userAfspraak = new AFSPRAAK();

//user include once
	        require_once("class.user.php");
         	$auth_user = new USER();
	        $user_id = $_SESSION['user_session'];
//select users from db          
	        $stmt = $auth_user->runQuery("SELECT * FROM users WHERE user_id=:user_id");
	        $stmt->execute(array(":user_id"=>$user_id));
	
	$userRow=$stmt->fetch(PDO::FETCH_ASSOC);

//                  ==/THE-END/==
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen"> 
<link href="bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" media="screen"> 
<!-- <script type="text/javascript" src="jquery-1.11.3-jquery.min.js"></script> -->
<link rel="stylesheet" href="style.css" type="text/css"  />
<title>welcome - <?php print($userRow['user_name']); ?></title>
</head>

<body>

<?php include('navbar.php'); ?>

	<div class="clearfix"></div>
	
    <div class="container-fluid" style="margin-top:80px;">
	
    <div class="container">
<?php
//select for user appointment data
      $stmt = $userAfspraak->runQuery("SELECT * FROM appointments WHERE user_id=:user_id");
      $stmt->execute(array(":user_id"=>$user_id));
	
	    $afsprakenRow=$stmt->fetch(PDO::FETCH_ASSOC);

//                  ==/THE-END/==
?> 
    
        <table class="table">
          <tr>
          <td><h3>Naam:</h3></td> <td ><p> <?php print($afsprakenRow['user_name']);  ?> </p> </td> <td><i>!</i></td>
           </tr>
            <tr>
          <td><h3>Gebortedatum:</h3></td> <td ><p> <?php print($afsprakenRow['user_birthday']); ?> </p> </td>
           <td><i>!</i></td>
           </tr>
           <tr>
          <td><h3>Email:</h3></td> <td ><p> <?php print($afsprakenRow['user_email']); ?> </p> </td>
           <td><i>Edit</i></td>
           </tr>
           <tr>
          <td><h3>Telefoonnumer:</h3></td> <td ><p> <?php print($afsprakenRow['user_phone']); ?> </p> </td>
           <td><i>Edit</i></td>
           </tr>
            <tr>
          <td><h3>Datum en tijd van de afspraak:</h3></td> <td ><p> <?php print($afsprakenRow['user_apodate']) .", ";  print($afsprakenRow['user_apotime']);?> </p> </td>
           <td><i>Edit</i></td>
           </tr>
           <tr>
          <td><h3>Uw bericht:</h3></td> <td>
          <textarea rows="4" cols="50" readonly>
                     <?php print($afsprakenRow['user_msg']); ?> 
           </textarea> </td>
           <td><i>Edit</i></td>
           </tr>
          <tr>
          <td>Bewerken</td> <td>Opslaan</td> <td>Verwijderen</td>
          </tr>
           </table>
  
    </div>

</div>

<script src="bootstrap/js/bootstrap.min.js"></script>

</body>
</html>