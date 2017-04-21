<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
<?php 
include('links.php');
require_once('message.php');
 ?>
<title>welcome - <?php //print($userRow['user_name']); ?></title>
</head>
<body>
<?php include('navbar.php'); ?>

<?php 
$user_id = $_SESSION['user_session'];
//user require once
require_once("class.user.php");
$user = new USER();

//get apponintment methods
require_once('class.appointments.php');
$userAppointments = new APPOINTMENT();

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
				
			if($user_id !== $row['session_id']) {
                    echo "something went wrong!";
			}
			else if($user_id === $row['session_id']){
                $userAppointments->updateAppointments($apoid=$row['apo_id'],$uname, $ubday, $umail, $uphone,  $udatum, $utijd, $umsg);
             }else
			{
				
					$user->redirect('index.php');
				}
			}
		
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}	
}
?>     

<script language = "javascript" type = "text/javascript">
         <!--
            //Browser Support Code
            function ajaxFunction(){
               var ajaxRequest;  // The variable that makes Ajax possible!
               
               try {
                  // Opera 8.0+, Firefox, Safari
                  ajaxRequest = new XMLHttpRequest();
               }catch (e) {
                  // Internet Explorer Browsers
                  try {
                     ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
                  }catch (e) {
                     try{
                        ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
                     }catch (e){
                        // Something went wrong
                        alert("Your browser broke!");
                        return false;
                     }
                  }
               }
               
               // Create a function that will receive data 
               // sent from the server and will update
               // div section in the same page.
					
               ajaxRequest.onreadystatechange = function(){
                  if(ajaxRequest.readyState == 4){
                     var ajaxDisplay = document.getElementById('ajaxDiv');
                     ajaxDisplay.innerHTML = ajaxRequest.responseText;
                  }
               }
               
               // Now get the value from user and pass it to
               // server script.
					
               var apoid = document.getElementById('apoid').value;
               var queryString = "?apoid=" + apoid ;
            
               queryString +=  "&apoid=" + apoid + "&apoid=" + apoid;
               ajaxRequest.open("GET", "appointments.php" + queryString, true);
               ajaxRequest.send(null); 
            }
         //-->
      </script>

<div class="clearfix"></div>   
<div class="container-fluid" style="margin-top:80px;">
	
    <div class="container">
        
      <form name='myForm'>
         Afspraken:<select id = 'apoid' onchange='ajaxFunction()'>
             <option value="Kies een afspraak">Kies een afspraak</option>
             <?php $userAppointments->listAppointments($user_id); ?>
             </select>
      </form>
       <p class="h4"> <a href="addAppointments.php" target="_self"> - Maak een nieuwe Afspraak:</a></p> 
     

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
 <div id = 'ajaxDiv'></div>

 




     


   