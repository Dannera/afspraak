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