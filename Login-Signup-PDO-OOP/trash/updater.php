<?php
//session
require_once("session.php");
		  
//user require once
require_once("class.user.php");
$user = new USER();
$user_id = $_SESSION['user_session'];
          
//message constants
require_once('message.php');

//get apponintment methods
require_once('class.appointments.php');
$userAppointments = new APPONINTMENT();


//select users from db          
$stmt = $user->runQuery("SELECT * FROM users WHERE user_id=:user_id");
$stmt->execute(array(":user_id"=>$user_id));	
$userRow=$stmt->fetch(PDO::FETCH_ASSOC);

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
		$error[] = MESSAGE::USERNAME_ERROR;	
	}
    else if($ubday=="" || ( $userAppointments->yearsMonthsBetween($ubday,date( 'Y-m-d' ))  < MESSAGE::AGE_MIN)  )	{
		$error[] = MESSAGE::BDAY_ERROR;	
	}
	else if($umail=="")	{
		$error[] = MESSAGE::USER_EMAIL_ERROR ;	
	}
	else if(!filter_var($umail, FILTER_VALIDATE_EMAIL))	{
	    $error[] = MESSAGE::USER_EMAIL_ERROR ;
	}
     else if($uphone=="" || strlen($uphone) < MESSAGE::PHONE_NUMBER_MAX_LENGTH)	{
		$error[] = MESSAGE::USER_PHONE_ERROR;	
	}
     else if($udatum=="")	{
		$error[] = MESSAGE::APO_DATE_ERROR;	
	}
	else if($utijd=="")	{
		$error[] = MESSAGE::APO_TIME_ERROR;
	}
	else if($umsg==""){
		$error[] = MESSAGE::USER_MSG_ERROR;	
	}
	else
	{
		try
		{
			$stmt = $user->runQuery("SELECT * FROM appointments WHERE session_id=$user_id");
			$stmt->execute(array(':session_id'=>$user_id));
			$row=$stmt->fetch(PDO::FETCH_ASSOC);
				
			if($row['session_id']!==$user_id) {
				$user->redirect('index.php');
			}

			else
			{
				if($userAppointments->updateAppointments($apoid=$row['apo_id'],$uname, $ubday, $umail, $uphone,  $udatum, $utijd, $umsg)){
					$user->redirect('afspraken.php');
				}
			}
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}	
}
?>
<!DOCTYPE html>
<html>
<head>
<?php include('links.php'); ?>
<title>welcome - <?php print($userRow['user_name']); ?></title>
</head>

<body>

<?php include('navbar.php'); ?>

<br/><br/><br/><br/><br/><br/>

   <form method="post" class="form-signin">
            <h2 class="form-signin-heading">Afspraak aanpasse:</h2><hr />
<?php 
	 $stmt = $user->runQuery("SELECT * FROM appointments WHERE apo_id=$apoid");
      $stmt->execute(array(':apo_id'=>$apoid));
      $stmt->execute();  
     //
     while($aRow = $stmt->fetch(PDO::FETCH_ASSOC)) 
    { 
	 echo  "<p>" . $aRow['user_name']  . ": " . $aRow['user_email']  . "</p>"; 
	}
  ?>


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
	?>

				<div class="form-group">
					Naam:<input type="text" name="txt_uname" class="form-control"  placeholder="uw naam" value="<?php if(isset($error)){echo $uname;} else {print($aRow['user_name']); } ?>"/>
					</div>
												
					<div class="form-group">
					Geboortedatum:<input type="date" name="txt_ubday" class="form-control geboortedatum" formated="true" placeholder="uw geboortedatum" value="<?php if(isset($error)){echo $ubday;} else { print($aRow['user_birthday']); }?>"/>
					</div>						
												
					<div class="form-group">
					E-Mail:<input type="email" name="txt_umail" class="form-control" data-parsley-trigger="keyup" placeholder="uw email" value="<?php if(isset($error)){echo $umail;} else { print($aRow['user_email']); }?>"/>
					</div>
					
					<div class="form-group">
					Telefoonnummer:<input type="text" name="txt_uphone" class="form-control"  placeholder="uw telefoonnummer" value="<?php if(isset($error)){echo $uphone;} else { print($aRow['user_phone']);  }?>"/>
					</div>
					
	           <div class="form-group">
					Datum voor afspraak:<input type="date" name="txt_udatum" class="form-control datum" placeholder="Datum voor afspraak" value="<?php if(isset($error)){echo $udatum;} else { print($aRow['user_apodate']); }?>"/>
					</div>

					<div class="form-group">
					Tijd voor afspraak:<input type="text" name="txt_tijd" class="form-control timepicker"  placeholder="Tijd voor afspraak:" value="<?php if(isset($error)){echo $utijd;} else { print($aRow['user_apotime']); }?>"/>
					</div>
					
					<div class="form-group">
					Uw boodschap:<textarea required name="txt_umsg" class="form-control" value="<?php if(isset($error)){echo $umsg;}?>"><?php print($aRow['user_msg']); ?> </textarea>
					</div>
					<div class="form-group">
					<button type="submit" class="btn btn-primary" name="submit">Afspraak opslan</button>
					<button type="reset" class="btn btn-default m-l-5">Afspraak verwijderen</button>
				</div>
			 </form>


<script src="bootstrap/js/bootstrap.min.js"></script>

</body>
</html>


<?php

require_once('class.appointments.php');
require_once('class.user.php');
require_once('message.php');

    $userAppointment = new APPOINTMENT();
    $user = new USER();

	$user_id = $_SESSION['user_session'];
	//get users from db
	$stmt = $user->runQuery("SELECT * FROM users WHERE user_id=:user_id");
	$stmt->execute(array(":user_id"=>$user_id));
	$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
//collect user inputs
if(isset($_POST['submit']))
{
	$uname  =  preg_replace('/[^A-Za-z ]/u','', strip_tags($_POST['txt_uname']));
    $ubday  =  preg_replace('/\D/', '',strip_tags($_POST['txt_ubday']));
	$umail  =  strip_tags($_POST['txt_umail']);
	$uphone =  preg_replace('/[^0-9]/', '',  strip_tags($_POST['txt_uphone']));
    $udatum =  preg_replace('/\D/', '',strip_tags($_POST['txt_udatum']));
	$utijd  =  preg_replace('/\D/', '',strip_tags($_POST['txt_tijd']));
	$umsg   =  strip_tags($_POST['txt_umsg']);	

   //error control 
	if($uname=="")	{
		$error[] = MESSAGE::USERNAME_ERROR;	
	}
    else if($ubday=="" || ( $userAppointment->yearsMonthsBetween($ubday,date( 'Y-m-d' ))   < MESSAGE::AGE_MIN )  )	{
		$error[] = MESSAGE::BDAY_ERROR;	}
	else if($umail=="")	{
		$error[] = MESSAGE::USER_EMAIL_ERROR ;	}
	else if(!filter_var($umail, FILTER_VALIDATE_EMAIL))	{
	    $error[] = MESSAGE::USER_EMAIL_ERROR ; }
     else if($uphone=="" || strlen($uphone) < MESSAGE::PHONE_NUMBER_MAX_LENGTH)	{
		$error[] = MESSAGE::USER_PHONE_ERROR; }
     else if($udatum=="")	{
		$error[] = MESSAGE::APO_DATE_ERROR;	}
	else if($utijd=="")	{
		$error[] = MESSAGE::APO_TIME_ERROR;}
	else if($umsg==""){
		$error[] = MESSAGE::USER_MSG_ERROR;	}
	else
	{
		try
		{
			$stmt = $userAppointment->runQuery("SELECT * FROM appointments WHERE user_name=:uname OR user_email=:umail");
			$stmt->execute(array(':uname'=>$uname, ':umail'=>$umail));
			$row=$stmt->fetch(PDO::FETCH_ASSOC);
				
			if(strcasecmp($uname,$row['user_name']) == 0) {
				$error[] = $uname . ', ' . message::APO_NAME_EXIST_ERROR . ", <a href='getAppointments.php'>appointment</a>";
			}
			else if($row['user_apodate'] == $udatum || $row['user_apotime'] == $utijd) {
				$error[] = message::APO_EMAIL_EXIST_ERROR . ' Datum: ' . $udatum .' of Tijd: ' .$utijd . " is al geregistreerd <a href='getAppointments.php'>appointment</a>";
			}
			else
			{
				if($userAppointment->register($user_id, $uname, $ubday, $umail, $uphone, $udatum, $utijd, $umsg)){
					$user->redirect('getAppointments.php');
				}
			}
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}	
}
?>