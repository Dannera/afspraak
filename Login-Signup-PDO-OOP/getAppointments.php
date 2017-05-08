<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
<?php include_once('includes/links_inc.php'); ?>
<?php require_once('includes/head_inc.php');?>
<title><?php print($userRow['user_name']); ?></title>
</head>
<body>
<?php include('includes/navbar_inc.php'); ?>
<?php 
$appintmentDetails = new FRONTLINKS();
$APPOINTMENT = new APPOINTMENT(); 
?>

<div class="col-md-3">
      <p class="h4"> Afspraken:</p>
      <form name='ajaxform'>
      <select id='apoid' onchange='ajaxFunction()'>
                        <?php $APPOINTMENT->listAppointments($appintmentDetails->userId); ?>
                </select>
      </form>
       <p class="h4"> <a href="addAppointments.php" target="_self"> - Maak een nieuwe Afspraak:</a></p> 
 </ul>
 </div>

 <div class="col-md-6">
 <div id = 'ajaxDiv'></div>
 </div>

 <script type="text/javascript">
 /* this function will create a 'zise='attribute' for the left selectBox with id=apoid and size='length of db_row count'*/
 function sizeOfoptionBox(){
 var sizeOFlastOpValue = document.getElementById('apoid');
 var lastElement = sizeOFlastOpValue.options[sizeOFlastOpValue.options.length - 1].value;
 var attr = document.createAttribute("size");
 attr.value = sizeOFlastOpValue.options.length;
 var selectBox = document.getElementsByTagName("select")[0];
 return selectBox.setAttributeNode(attr); 
 }
sizeOfoptionBox();
 </script>


