<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
<?php require_once('includes/head_inc.php');
$apoId = $_GET['apoid'];
$appointmentDetails = new FRONTLINKS();
$APPOINTMENT = new APPOINTMENT(); 
$USER = new USER(); 

?>
<title><?php print($userRow['user_name']); ?></title>
</head>
<body>


<?php

  $appointmentDetails->userInputsFomHTMLform();  

$stmt = $APPOINTMENT->runQuery("SELECT * FROM appointments WHERE apo_id=$apoId");
			$stmt->execute(array(':session_id'=>$apId));
			$row=$stmt->fetch(PDO::FETCH_ASSOC);

if($_POST['confirm']=='update' && ($appointmentDetails->userId == $row['session_id']) && $apoId == $row['apo_id']){ 
				$APPOINTMENT->updateAppointments(
					    $apoId,
						$appointmentDetails->userName, 
					    $appointmentDetails->userBirthday, 
					    $appointmentDetails->userEmail, 
					    $appointmentDetails->userPhone,
					    $appointmentDetails->userDateOfAppointment, 
						$appointmentDetails->userTimeOfAppointment, 
						$appointmentDetails->userMessage
						);
?>
		<script> location.replace("appointments.php?apoid=<?php echo $apoId; ?>")</script>
<?php 
 }else{}
 
 if(isset($_POST['delete']) || $_POST['confirm']=='delete'){
	         $APPOINTMENT->deleteAppointments($apoId);
 
 ?>
 			<script> 
			 alert("appointments.php?apoid=<?php echo $apoId; ?> is verwijderd."); location.replace("home.php")
			 </script>
<?php } ?>

   <form method="post" class="form-signin" action="appointments.php?apoid=<?php echo $apoId; ?>">
            <h4 class="form-signin-heading">Uw kunt hier een afspraak aanpassen:</h4><hr />
		    <div class="form-group">
			<label for="update">Bijwerken : <input type="radio" name="confirm" value="update"/></lable> <span> en of </span>
			<label for="delete">Wissen : <input type="radio" name="confirm" value="delete"/></label>
			</div>
      	   	<div class="form-group">
					Naam:<input type="text" name="txt_uname" class="form-control"  placeholder="uw naam" value="<?php echo $row['user_name']; ?>"/>
					</div>
												
					<div class="form-group">
					Geboortedatum:<input type="date" name="txt_ubday" class="form-control geboortedatum" formated="true" placeholder="uw geboortedatum" value="<?php echo $row['user_birthday']; ?>"/>
					</div>						
												
					<div class="form-group">
					E-Mail:<input type="email" name="txt_umail" class="form-control" data-parsley-trigger="keyup" placeholder="Enter a valid e-mail" value="<?php  echo $row['user_email']; ?>"/>
					</div>
					
					<div class="form-group">
					Telefoonnummer:<input type="text" name="txt_uphone" class="form-control"  placeholder="uw telefoonnummer" value="<?php  echo $row['user_phone']; ?>"/>	
					</div>
					
	                <div class="form-group">
					Datum voor afspraak:<input type="date" name="txt_udatum" class="form-control datum" placeholder="datum voor afspraak" value="<?php  echo $row['user_apodate']; ?>"/>
					</div>

					<div class="form-group">
					Tijd voor afspraak:<input type="text" name="txt_tijd" class="form-control timepicker"  placeholder="tijd voor afspraak" value="<?php  echo $row['user_apotime'];  ?>"/>
                    <span class="help-line">...</span>
					</div>
					
					<div class="form-group">
					Uw boodschap:<textarea required name="txt_umsg" class="form-control" value=""><?php  echo $row['user_msg']; ?></textarea>
					</div>
					
					<div class="form-group">
					<button type="submit" class="btn btn-primary" name="submit">Maak een afspraak</button>

					<button type="delete" class="btn btn-default m-l-5">Afspraak verwijderen</button>
					</div>

				</form>
			 </div>
   </body>
</html>
