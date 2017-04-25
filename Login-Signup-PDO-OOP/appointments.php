<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
<?php require_once('includes/head_inc.php');
      $apoid = $_GET['apoid'];
?>
<title><?php print($userRow['user_name']); ?></title>
</head>
<body>
<?php include('includes/navbar_inc.php'); ?>

   <form method="post" class="form-signin" action="appointments.php?apoid=<?php echo $apoid; ?>">
            <h4 class="form-signin-heading">Uw kunt hier een afspraak aanpassen:</h4><hr />
		     	<div class="form-group">
			<label for="update">Bijwerken : <input type="radio" name="confirm" value="update"/></lable> <span> en of </span>
			<label for="delete">Wissen : <input type="radio" name="confirm" value="delete"/></label>
			</div>
      	     <div class="form-group">
					Naam:<input type="text" name="txt_uname" class="form-control"  placeholder="uw naam" value="<?php if(isset($error)){echo $uname;} else {print($userAppointments->userName); } ?>"/>
					</div>
												
			  <div class="form-group">
					Geboortedatum:<input type="date" name="txt_ubday" class="form-control geboortedatum" formated="true" placeholder="uw geboortedatum" value="<?php if(isset($error)){ echo $ubday; } else { print($userAppointments->userBirthday); }?>"/>
					</div>						
												
			   <div class="form-group">
					E-Mail:<input type="email" name="txt_umail" class="form-control" data-parsley-trigger="keyup" placeholder="uw email" value="<?php if(isset($error)){echo $umail;} else { print($userAppointments->userEmail); }?>"/>
					</div>
					
			   <div class="form-group">
					Telefoonnummer:<input type="text" name="txt_uphone" class="form-control"  placeholder="uw telefoonnummer" value="<?php if(isset($error)){echo $uphone;} else { print($userAppointments->userPhone);  }?>"/>
					</div>
					
	           <div class="form-group">
					Datum voor afspraak:<input type="date" name="txt_udatum" class="form-control datum" placeholder="Datum voor afspraak" value="<?php if(isset($error)){echo $udatum;} else { print($userAppointments->userDateOfAppointment); }?>"/>
					</div>

			   <div class="form-group">
					Tijd voor afspraak:<input type="text" name="txt_tijd" class="form-control timepicker"  placeholder="Tijd voor afspraak:" value="<?php if(isset($error)){echo $utijd;} else { print($userAppointments->userTimeOfAppointment); }?>"/>
					</div>
					
				<div class="form-group">
					Uw boodschap:<textarea required name="txt_umsg" class="form-control" value="<?php if(isset($error)){echo $umsg;}?>"><?php print($userAppointments->userMessage); ?> </textarea>
					</div>
				<div class="form-group">
					<button type="submit" class="btn btn-primary" name="submit">Opslan</button>
					<button type="delete"  class="btn btn-default m-l-5"  name="delete">Verwijderen</button>
				</div>
			 </form>  
   </body>
</html>
