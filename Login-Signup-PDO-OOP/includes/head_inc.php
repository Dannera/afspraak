<?php
//imports
require_once('class.user.php');
require_once('class.appointments.php');
require_once('message.php');
include_once('links_inc.php'); 

$APPOINTMENT = new APPOINTMENT();



class FRONTLINKS extends APPOINTMENT{
//Global properties
public $userId, $apoId, $userName, $userBirthday, $userEmail, $userPhone, $userDateOfAppointment, $userTimeOfAppointment, $userMessage, $error;


public function __construct(){

	   $this->userId = $_SESSION['user_session'];
	   $apoId = $_GET['apoid'];
}

//
public function userDetailsFromUsersTable(){

	$stmt = $USER->runQuery("SELECT * FROM users WHERE user_id=$this->userId");
	$stmt->execute(array(":userId"=>$this->userId));
	$stmt->fetch(PDO::FETCH_ASSOC);

 return $stmt;
}

public function userInputsFomHTMLform(){

if(isset($_POST['submit']))
  {
	$this->userName = preg_replace('/[^A-Za-z ]/u','', strip_tags($_POST['txt_uname']));
    $this->userBirthday = preg_replace('/\D/', '',strip_tags($_POST['txt_ubday']));
	$this->userEmail = strip_tags($_POST['txt_umail']);
	$this->userPhone = preg_replace('/[^0-9]/', '',  strip_tags($_POST['txt_uphone']));
    $this->userDateOfAppointment = preg_replace('/\D/', '',strip_tags($_POST['txt_udatum']));
	$this->userTimeOfAppointment = preg_replace('/\D/', '',strip_tags($_POST['txt_tijd']));
	$this->userMessage = strip_tags($_POST['txt_umsg']);
    
	//check if all imput fields are set 
	if($this->userName || $this->userBirthday || $this->userEmail || $this->userPhone || $this->userDateOfAppointment || $this->userTimeOfAppointment || $this->userMessage == "")
		{
		 switch(isset($this->error)){
			 case $this->userName:
			      $this->error = USERNAME_ERROR;
			 break;
			 case  $this->userBirthday && $GLOBALS['APPOINTMENT']->yearsMonthsBetween( $this->userBirthday,date( 'Y-m-d' ) < AGE_MIN ):
		               $this->error = BDAY_ERROR;              
			 break;
			 case $this->userEmail:
			      $this->error = USER_EMAIL_ERROR;
			 break;
			 case $this->userEmail != "" || !filter_var( $this->userEmail, FILTER_VALIDATE_EMAIL):
			      $this->error = USER_EMAIL_ERROR;
			 break;

			 case $this->userPhone:
			      $this->error = USER_PHONE_ERROR;
			 break;
			
			 case $this->userDateOfAppointment:
			      $this->error = APO_DATE_ERROR;
			 break;
			 case $this->userTimeOfAppointment:
			      $this->error = APO_TIME_ERROR; 
			 break;
			 case $this->userMessage:
			      $this->error = USER_MSG_ERROR;
			 break;
			 case $this->error:
			      $this->error = " - Validatie voltooid.<br>"; 
			 break;
			 default:

	         return $this->userInputsFomHTMLform();
			 
			    
		 }
			return $this->error;	  
	
   }
 }
}

public function register(){


if(isset($_POST['submit']))
  {
   if(
	   $GLOBALS['APPOINTMENT']->yearsMonthsBetween( $this->userBirthday,date( 'Y-m-d' ) <! AGE_MIN)){
                   $GLOBALS['APPOINTMENT']->register(
					    $this->userId, $this->userName, 
					    $this->userBirthday, 
					    $this->userEmail, 
					    $this->userPhone,
					    $this->userDateOfAppointment, 
						$this->userTimeOfAppointment, 
						$this->userMessage);
        echo "- Bedankt, uw egistratie is gereed. <br/>";
  }
  }else{
         echo "Vul, alstublieft de volgende velden correct in.";
  }

}
   

}

?>