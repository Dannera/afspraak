<?php
//session
          require_once("session.php");

//afsprakken require once
          require_once('class.afspraken.php');
          $userAfspraak = new AFSPRAAK();

//user require once
	        require_once("class.user.php");
         	$auth_user = new USER();
	        $user_id = $_SESSION['user_session'];
          
//message constants
require_once('message.php');

//db configuration file
require_once('class.afspraken.php');
$user = new AFSPRAAK();

//select users from db          
	        $stmt = $auth_user->runQuery("SELECT * FROM users WHERE user_id=:user_id");
	        $stmt->execute(array(":user_id"=>$user_id));
	
	$userRow=$stmt->fetch(PDO::FETCH_ASSOC);

  $user = new AFSPRAAK();
  $userRedirect = new USER();

//collect user inputs
if(isset($_POST['submit']))
{
  $uname  =  strip_tags($_POST['txt_uname']);
  $ubday  =  strip_tags($_POST['txt_ubday']);
  $umail  =  strip_tags($_POST['txt_umail']);
  $uphone =  strip_tags($_POST['txt_uphone']);
  $udatum =  strip_tags($_POST['txt_udatum']);
  $utijd  =  strip_tags($_POST['txt_tijd']);
  $umsg   =  strip_tags($_POST['txt_umsg']);	

   // delete this line: $test = $user->yearsMonthsBetween($ubday,date( 'Y-m-d' )); .

	if($uname=="")	{
		$error[] = message::USERNAME_ERROR;	
	}
    else if($ubday=="" || ( $user->yearsMonthsBetween($ubday,date( 'Y-m-d' ))  < 16 )  )	{
		$error[] = message::BDAY_ERROR;	
	}
	else if($umail=="")	{
		$error[] = MESSAGE::USER_EMAIL_ERROR ;	
	}
	else if(!filter_var($umail, FILTER_VALIDATE_EMAIL))	{
	    $error[] = MESSAGE::USER_EMAIL_ERROR ;
	}
     else if($uphone=="" || strlen($uphone) < MESSAGE::PHONE_NUMBER_MAX_LENGTH)	{
		$error[] = message::USER_PHONE_ERROR;	
	}
     else if($udatum=="")	{
		$error[] = message::APO_DATE_ERROR;	
	}
	else if($utijd=="")	{
		$error[] = message::APO_TIME_ERROR;
	}
	else if($umsg==""){
		$error[] = message::USER_MSG_ERROR;	
	}
	else
	{
		try
		{
			$stmt = $userAfspraak->runQuery("SELECT * FROM appointments WHERE session_id=$user_id");
			$stmt->execute(array(':session_id'=>$user_id));
			$row=$stmt->fetch(PDO::FETCH_ASSOC);
				
			if($row['session_id']!=$user_id) {
				$error[] = $uname . ', ' . message::APO_NAME_EXIST_ERROR . ", <a href='afspraken.php'>appointment</a>";
			}
			
			else
			{
				if($userAfspraak->updateAppointments($apo_id=$row['apo_id'], $uname, $ubday, $umail, $uphone,  $udatum, $utijd, $umsg)){
					//$user->redirect('afspraken.php');
				}
			}
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}

     
	}	
}
// ==/ THE-END /==  

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

    <div class="container" style="margin-top:80px;">

<?php
//select for user appointment data
      $stmt = $userAfspraak->runQuery("SELECT * FROM appointments WHERE session_id=$user_id");
      $stmt->execute(array(':session_id'=>$user_id));
	  $afsprakenRow=$stmt->fetch(PDO::FETCH_ASSOC);
// ==/THE-END/==
?> 


<div class="signin-form">

<div class="container">
    	
        <form method="post" class="form-signin">
            <h2 class="form-signin-heading">Afspraak aanpasse:</h2><hr />
            <?php
			//error and validation messages will appear here if exists
			if(isset($error)){ foreach($error as $error)
			 	{ ?>
                     <div class="alert alert-danger">
                        <i class="glyphicon glyphicon-warning-sign"></i> &nbsp; <?php echo $error; ?>
                     </div>
                     <?php
				}
			}
			else if(isset($_GET['joined']))
			{
				 ?>
                 <div class="alert alert-info">
                      <i class="glyphicon glyphicon-log-in"></i> &nbsp; Successfully registered <a href='index.php'>login</a> here
                 </div>
                 <?php
			}

			//          ==/ THE-END /==  
	?>
       
            <?php
			//validation step
			if(isset($error) && empty($error))
			{	foreach($error as $error)
			 	{ ?>
                     <div class="alert alert-danger">
                        <i class="glyphicon glyphicon-warning-sign"></i> &nbsp; <?php echo "Error: " . $error; ?>
                     </div>
                     <?php } } else if(isset($_GET['submit'])) {
				 ?>
                 <div class="alert alert-info">
                      <i class="glyphicon glyphicon-log-in"></i> &nbsp; Done! </div>
                 <?php
			}

			//          ==/ THE-END /==  
	?>


				<div class="form-group">
					Naam:<input type="text" name="txt_uname" class="form-control"  placeholder="uw naam" value="<?php if(isset($error)){echo $uname;} else {print($afsprakenRow['user_name']); } ?>"/>
					</div>
												
					<div class="form-group">
					Geboortedatum:<input type="date" name="txt_ubday" class="form-control geboortedatum" formated="true" placeholder="uw geboortedatum" value="<?php if(isset($error)){echo $ubday;} else { print($afsprakenRow['user_birthday']); }?>"/>
					</div>						
												
					<div class="form-group">
					E-Mail:<input type="email" name="txt_umail" class="form-control" data-parsley-trigger="keyup" placeholder="uw email" value="<?php if(isset($error)){echo $umail;} else { print($afsprakenRow['user_email']); }?>"/>
					</div>
					
					<div class="form-group">
					Telefoonnummer:<input type="text" name="txt_uphone" class="form-control"  placeholder="uw telefoonnummer" value="<?php if(isset($error)){echo $uphone;} else { print($afsprakenRow['user_phone']);  }?>"/>
					</div>
					
	           <div class="form-group">
					Datum voor afspraak:<input type="date" name="txt_udatum" class="form-control datum" placeholder="Datum voor afspraak" value="<?php if(isset($error)){echo $udatum;} else { print($afsprakenRow['user_apodate']); }?>"/>
					</div>

					<div class="form-group">
					Tijd voor afspraak:<input type="text" name="txt_tijd" class="form-control timepicker"  placeholder="Tijd voor afspraak:" value="<?php if(isset($error)){echo $utijd;} else { print($afsprakenRow['user_apotime']); }?>"/>
					</div>
					
					<div class="form-group">
			
					Uw boodschap:<textarea required name="txt_umsg" class="form-control" value="<?php if(isset($error)){echo $umsg;}?>"><?php print($afsprakenRow['user_msg']); ?> </textarea>
					</div>
					
					<div class="form-group">
					<button type="submit" class="btn btn-primary" name="submit">Afspraak opslan</button>
					<button type="reset" class="btn btn-default m-l-5">Afspraak verwijderen</button>
					</div>
				</form>
			 </div>
</div>
</div>
    
        
    </div>

</div>

<script src="bootstrap/js/bootstrap.min.js"></script>

</body>
</html>