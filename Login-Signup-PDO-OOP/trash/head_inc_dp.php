<?php 
 $user_id = $_SESSION['user_session'];
//user require once
require_once("class.user.php");
$user = new USER();

include_once('links_inc.php'); 
require_once('message.php');

//get apponintment methods
require_once('class.appointments.php');
$userAppointments = new APPOINTMENT();
$apoid = $_GET['apoid'];
$userAppointments->selectAppointments($apoid); 

//select users from db          
$stmt = $user->runQuery("SELECT * FROM users WHERE user_id=:user_id");
$stmt->execute(array(":user_id"=>$user_id));	
$userRow=$stmt->fetch(PDO::FETCH_ASSOC);

//collect clean user inputs
if(isset($_POST['submit']))
{
    $uname  =  preg_replace('/[^A-Za-z ]/u','', strip_tags($_POST['txt_uname']));
    $ubday  =  preg_replace('/\D/', '',strip_tags($_POST['txt_ubday']));
	$umail  =  strip_tags($_POST['txt_umail']);
	$uphone =  preg_replace('/[^0-9]/', '',  strip_tags($_POST['txt_uphone']));
    $udatum =  preg_replace('/\D/', '',strip_tags($_POST['txt_udatum']));
	$utijd  =  preg_replace('/\D/', '',strip_tags($_POST['txt_tijd']));
	$umsg   =  strip_tags($_POST['txt_umsg']);		

//control and validate inputs
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
			$stmt = $user->runQuery("SELECT * FROM appointments WHERE apo_id=$apoid");
			$stmt->execute(array(':session_id'=>$user_id));
			$row=$stmt->fetch(PDO::FETCH_ASSOC);
            
			//register appointment 
            if(strcasecmp($uname,$row['user_name']) == 0) {
				$error[] = $uname . ', ' . message::APO_NAME_EXIST_ERROR . ", <a href='getAppointments.php'>appointment</a>";
			}
			else if($row['user_apodate'] == $udatum || $row['user_apotime'] == $utijd) {
				$error[] = message::APO_EMAIL_EXIST_ERROR . ' Datum: ' . $udatum .' of Tijd: ' .$utijd . " is al geregistreerd <a href='getAppointments.php'>appointment</a>";
			}
			else if($user_id == $row['session_id'] && $apoid !== $row['apo_id']){
				     $userAppointments->register($user_id, $uname,$ubday,$umail,$uphone,$udatum,$utijd,$umsg);
					//$user->redirect('getAppointments.php');
			}
			else if($_POST['confirm']=='update' && ($user_id == $row['session_id']) && $apoid == $row['apo_id']){ 
				$userAppointments->updateAppointments($apoid,$uname, $ubday, $umail, $uphone,  $udatum, $utijd, $umsg);
         ?>
				 <script> location.replace("appointments.php?apoid=<?php echo $apoid; ?>")</script>
		<?php 
			}else{
				
			}
			}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
}

    //delete row if confirm==delete and form set to delte
	if(isset($_POST['delete']) && $_POST['confirm']=='delete'){
	         $userAppointments->deleteAppointments($apoid);
			 	}         
?>



<?php
//imports
require_once('class.user.php');
require_once('class.appointments.php');
require_once('message.php');
include_once('links_inc.php'); 

$USER = new USER();
$APPOINTMENT = new APPOINTMENT();
class FRONTLINKS {
//definitions

//Global properties
public $USER,$APPOINTMENT;
public $userId, $apoId, $userName, $userBirthday, $userEmail, $userPhone, $userDateOfAppointment, $userTimeOfAppointment, $userMessage, $error;



public function __construct(){
	

	   $this->userId = $_SESSION['user_session'];
	  
}

//
public function userDetailsFromUsersTable(){

	$stmt = $this->USER->runQuery("SELECT * FROM users WHERE user_id=$this->userId");
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
			 case $this->userBirthday:
			      $this->error = BDAY_ERROR;
			 break;
			 case $this->APPOINTMENT->yearsMonthsBetween( $this->userBirthday,date( 'Y-m-d' ) < AGE_MIN ):
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
/*
			case strlen( $this->userPhone ) < PHONE_NUMBER_MAX_LENGTH:
			      $this->error = USER_PHONE_ERROR;
			 break;
			*/
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
			      $this->error = " - Validatie voltooid. Er zijn/is geen <br>"; 
			 break;
			 default:
			      $this->userName = $this->userName;
    		      $this->userBirthday = $this->userBirthday;
			      $this->userEmail =  $this->userEmail;
			      $this->userPhone = $this->userPhone;
    		      $this->userDateOfAppointment = $this->userDateOfAppointment; 
			      $this->userTimeOfAppointment = $this->userTimeOfAppointment;
			      $this->userMessage = $this->userMessage;
		 }
				  if(isset($this->error) || $this->error == "" ){ return $this->error ." " . strlen( $this->userPhone );
		   
    }

	

   }
 }	

}

public function userDetailsFromTableAppointments(){
try
		{
			$stmt = $this->APPOINTMENT->runQuery("SELECT * FROM appointments WHERE user_name=:$this->userName OR user_email=:$this->userEmail");
			$stmt->execute(array(':uname'=>$this->userName, ':umail'=>$this->userEmail));
			$row=$stmt->fetch(PDO::FETCH_ASSOC);

		 }
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
}

public function userDeleteAppointmentFromTable(){
	    //delete row if confirm==delete and form set to delte
	if(isset($_POST['delete']) && $_POST['confirm']=='delete'){
	         $this->APPOINTMENT->deleteAppointments($apoId);
	 }    
 }

}

?>