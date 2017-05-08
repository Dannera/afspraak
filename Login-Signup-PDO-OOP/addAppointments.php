<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
<?php include_once('includes/links_inc.php'); ?>
<?php require_once('includes/head_inc.php');?>
<title><?php //print($userRow['user_name']); ?></title>
</head>
<body>
<?php include_once('includes/navbar_inc.php'); ?> 
<div class="signin-form">
<div class="container">
        <form method="post" class="form-signin">
            <h2 class="form-signin-heading">Een Afspraak Maken:</h2><hr />
<div class="alert alert-info">
<?php 
$appintmentDetails = new FRONTLINKS();
echo $appintmentDetails->userInputsFomHTMLform();  
$appintmentDetails->register();
?>
</div>
					<div class="form-group">
					Naam:<input type="text" name="txt_uname" class="form-control"  placeholder="uw naam" value="<?php echo $appintmentDetails->userName; ?>"/>
					</div>
												
					<div class="form-group">
					Geboortedatum:<input type="date" name="txt_ubday" class="form-control geboortedatum" formated="true" placeholder="uw geboortedatum" value="<?php echo $appintmentDetails->userBirthday; ?>"/>
					</div>						
												
					<div class="form-group">
					E-Mail:<input type="email" name="txt_umail" class="form-control" data-parsley-trigger="keyup" placeholder="Enter a valid e-mail" value="<?php  echo $appintmentDetails->userEmail; ?>"/>
					</div>
					
					<div class="form-group">
					Telefoonnummer:<input type="text" name="txt_uphone" class="form-control"  placeholder="uw telefoonnummer" value="<?php  echo $appintmentDetails->userPhone; ?>"/>	
					</div>
					
	                <div class="form-group">
					Datum voor afspraak:<input type="date" name="txt_udatum" class="form-control datum" placeholder="datum voor afspraak" value="<?php  echo $appintmentDetails->$userDateOfAppointment; ?>"/>
					</div>

					<div class="form-group">
					Tijd voor afspraak:<input type="text" name="txt_tijd" class="form-control timepicker"  placeholder="tijd voor afspraak" value="<?php  echo $appintmentDetails->userTimeOfAppointment;  ?>"/>
                    <span class="help-line">...</span>
					</div>
					
					<div class="form-group">
					Uw boodschap:<textarea required name="txt_umsg" class="form-control" value="<?php  echo $appintmentDetails->userMessage; ?>"></textarea>
					</div>
					
					<div class="form-group">
					<button type="submit" class="btn btn-primary" name="submit">Maak een afspraak</button>
					<button type="reset" class="btn btn-default m-l-5">Cancel</button>
					</div>

				</form>
			 </div>
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
