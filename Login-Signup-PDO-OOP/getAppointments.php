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

<div class="container-fluid" style="margin-top:80px;">  
      <form name='ajaxform'>
       Afspraken:<select id = 'apoid' onchange='ajaxFunction()'>
                        <option value="Kies een afspraak">Kies een afspraak</option>
                        <?php $userAppointments->listAppointments($user_id); ?>
                </select>
      </form>

       <p class="h4"> <a href="addAppointments.php" target="_self"> - Maak een nieuwe Afspraak:</a></p> 
     
 <div id = 'ajaxDiv'></div>