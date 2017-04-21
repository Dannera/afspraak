<?php
session_start();
//require_once("session.php");
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
			$stmt = $userAppointment->runQuery("SELECT user_name, user_email FROM appointments WHERE user_name=:uname OR user_email=:umail");
			$stmt->execute(array(':uname'=>$uname, ':umail'=>$umail));
			$row=$stmt->fetch(PDO::FETCH_ASSOC);
				
			if($row['user_name']==$uname) {
				$error[] = $uname . ', ' . message::APO_NAME_EXIST_ERROR . ", <a href='appointments.php'>appointment</a>";
			}
			else if($row['user_email']==$umail) {
				$error[] = message::APO_EMAIL_EXIST_ERROR . ' ' . $umail . " <a href='appointments.php'>appointment</a>";
			}
			else
			{
				if($userAppointment->register($user_id, $uname, $ubday, $umail, $uphone, $udatum, $utijd, $umsg)){
					$user->redirect('index.php');
				}
			}
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}	
}

//          ==/ THE-END /==  
?>
<!DOCTYPE html>
<html>
<head>
<?php include('links.php'); ?>
<title>Afspraak Maken</title>

</head>
<body>

<?php include('navbar.php'); ?> 

<div class="signin-form">

<div class="container">
    	
        <form method="post" class="form-signin">
            <h2 class="form-signin-heading">Een Afspraak Maken:</h2><hr />
            <?php
			//error and validation messages will appear here if exists
			if(isset($error))
			{
			 	foreach($error as $error)
			 	{
					 ?>
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
			if(isset($error) && empty($error)){ foreach($error as $error) { ?>
                     <div class="alert alert-danger">
                        <i class="glyphicon glyphicon-warning-sign"></i> &nbsp; 
						<?php echo "Error: " . $error; ?> </div>
<?php } } else if(isset($_GET['submit'])){ ?>
                 <div class="alert alert-info">
                      <i class="glyphicon glyphicon-log-in"></i> &nbsp; Done! </div>
<?php }
			//          ==/ THE-END /==  ?>

					<div class="form-group">
					Naam:<input type="text" name="txt_uname" class="form-control"  placeholder="uw naam" value="<?php if(isset($error)){echo $uname;}?>"/>
					</div>
												
					<div class="form-group">
					Geboortedatum:<input type="date" name="txt_ubday" class="form-control geboortedatum" formated="true" placeholder="uw geboortedatum" value="<?php if(isset($error)){echo $ubday;}?>"/>
					</div>						
												
					<div class="form-group">
					E-Mail:<input type="email" name="txt_umail" class="form-control" data-parsley-trigger="keyup" placeholder="Enter a valid e-mail" value="<?php if(isset($error)){echo $umail;}?>"/>
					</div>
					
					<div class="form-group">
					Telefoonnummer:<input type="text" name="txt_uphone" class="form-control"  placeholder="uw telefoonnummer" value="<?php if(isset($error)){echo $uphone;}?>"/>	
					</div>
					
	                <div class="form-group">
					Datum voor afspraak:<input type="date" name="txt_udatum" class="form-control datum" placeholder="datum voor afspraak" value="<?php if(isset($error)){echo $udatum;}?>"/>
					</div>

					<div class="form-group">
					Tijd voor afspraak:<input type="text" name="txt_tijd" class="form-control timepicker"  placeholder="tijd voor afspraak" value="<?php if(isset($error)){echo $utijd;}?>"/>
                    <span class="help-line">...</span>
					</div>
					
					<div class="form-group">
					Uw boodschap:<textarea required name="txt_umsg" class="form-control" value="<?php if(isset($error)){echo $umsg;}?>"></textarea>
					</div>
					
					<div class="form-group">
					<button type="submit" class="btn btn-primary" name="submit">Maak een afspraak</button>
					<button type="reset" class="btn btn-default m-l-5">Cancel</button>
					</div>

				</form>
			 </div>
</div>
</div>

<script>
$(document).ready(function(){
	//$('form').parsley();
});
</script>

  <script> $( function() {  
           $( ".geboortedatum" ).datepicker( {
             changeMonth:true, 
             changeYear:true,        
             dateFormat:"yy-mm-dd", });

      $( ".datum" ).datepicker({
            changeMonth:true,
            changeYear:true,           
            dateFormat:"yy-mm-dd",
            minDate: +1,
        });
     });

	//timepicker
	$('input.timepicker').timepicker({
    timeFormat: 'h:mm p',
    interval: 60,
    minTime: '8:30',
    maxTime: '16:00pm',
    defaultTime: '8:30',
    startTime: '9:00',
    dynamic: false,
    dropdown: true,
    scrollbar: true
    } );
    </script>

</body>
</html>
