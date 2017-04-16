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
     else if($uphone=="" || strlen($uphone) < 10)	{
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
			$stmt = $user->runQuery("SELECT user_name, user_email FROM appointments WHERE user_name=:uname OR user_email=:umail");
			$stmt->execute(array(':uname'=>$uname, ':umail'=>$umail));
			$row=$stmt->fetch(PDO::FETCH_ASSOC);
			
      		$userRedirect->redirect('afspraken.php');
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
      $stmt = $userAfspraak->runQuery("SELECT * FROM appointments WHERE user_email=:user_email");
      $stmt->execute(array(':user_email'=>$userRow['user_email']));
	
	    $afsprakenRow=$stmt->fetch(PDO::FETCH_ASSOC);

//                  ==/THE-END/==
?> 


<div class="signin-form">

<div class="container">
    	
        <form method="post" class="form-signin">
            <h2 class="form-signin-heading">Afspraak aanpasse:</h2><hr />
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
			if(isset($error) && empty($error))
			{
			 	foreach($error as $error)
			 	{
					 ?>
                     <div class="alert alert-danger">
                        <i class="glyphicon glyphicon-warning-sign"></i> &nbsp; <?php echo "Error: " . $error; ?>
                     </div>
                     <?php
				}
			}
			else if(isset($_GET['submit']))
			{
				 ?>
                 <div class="alert alert-info">
                      <i class="glyphicon glyphicon-log-in"></i> &nbsp; Done! </div>
                 <?php
			}

			//          ==/ THE-END /==  
			?>


<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "afspraak";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    


  $uname  =  $afsprakenRow['user_name'];
  $ubday  =  $afsprakenRow['user_birthday'];
	$umail  =  $afsprakenRow['user_email'];
	$uphone =  $afsprakenRow['user_phone'];
  $udatum =  $afsprakenRow['user_apodate'];
	$utijd  =  $afsprakenRow['user_apotime'];
	$umsg   =  $afsprakenRow['user_msg'];
 
  
  $sqll  = 'UPDATE appointments SET user_name=$uname, user_birthday=$udatum, user_email=$umail, user_phone=$uphone, user_apodate=$utijd, user_msg=$umsg  WHERE apo_id=10';

    // Prepare statement
    $stmt = $conn->prepare($sqll);

    // execute the query
    $stmt->execute();

    // echo a message to say the UPDATE succeeded
    echo $stmt->rowCount() . " records UPDATED successfully";
    }
catch(PDOException $e)
    {
    echo $sqll . "<br>" . $e->getMessage();
    }

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