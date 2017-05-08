<?php
require_once('dbconfig.php');

class APPOINTMENT
{	
	private $conn;
    public $apoID, $userName, $userBirthday, $userEmail, $userPhone, $userDateOfAppointment, $userTimeOfAppointment, $userMessage;
	
	public function __construct()
	{
		$database = new Database();
		$db = $database->dbConnection();
		$this->conn = $db;
    }
	
	public function runQuery($sql)
	{
		$stmt = $this->conn->prepare($sql);
		return $stmt;
	}


    public function yearsMonthsBetween( $date1, $date2 ) 
    {
	
	   $d1 = new DateTime( $date1 );
	   $d2 = new DateTime( $date2 );
	
	   $diff = $d2->diff( $d1 );
	
	   $betweenYear = $diff->y;

         return $betweenYear;
        }

	
	public function register($user_id, $userName,$userBirthday,$userEmail,$userPhone,$userDateOfAppointment,$userTimeOfAppointment,$userMessage)
	{
		try
		{
			$stmt = $this->conn->prepare("INSERT INTO appointments(session_id,
                                                                   user_name,
                                                                   user_birthday,
                                                                   user_email,
                                                                   user_phone,
                                                                   user_apodate,
                                                                   user_apotime,
                                                                   user_msg)
		                                    VALUES(:user_id,
                                                        :userName, 
                                                        :userBirthday, 
                                                        :userEmail, 
                                                        :userPhone, 
                                                        :userDateOfAppointment, 
                                                        :userTimeOfAppointment, 
                                                        :userMessage)");
            
			$stmt->bindparam(":user_id", $user_id);
			$stmt->bindparam(":userName", $userName);
			$stmt->bindparam(":userBirthday", $userBirthday);
            $stmt->bindparam(":userEmail", $userEmail);
            $stmt->bindparam(":userPhone", $userPhone);
            $stmt->bindparam(":userDateOfAppointment", $userDateOfAppointment);
			$stmt->bindparam(":userTimeOfAppointment", $userTimeOfAppointment);
			$stmt->bindparam(":userMessage", $userMessage);
            $stmt->execute();	
				  		        
     return $stmt; 
    }

	 catch(PDOException $e)
       {
           
          echo '<pre>'; 
          echo 'Regel: '.$e->getLine().'<br>'; 
          echo 'Bestand: '.$e->getFile().'<br>'; 
          echo 'Foutmelding: '.$e->getMessage(); 
          echo '</pre>'; 
       }   
    }

	public function selectAppointments($apoid)
	{
		
		try
		{	
			$stmt = $this->conn->prepare("SELECT * FROM appointments WHERE apo_id='$apoid'");
            $stmt->execute(array(':apo_id'=>$apoid));
            $stmt->execute();  
     
         while($aRow = $stmt->fetch(PDO::FETCH_ASSOC)) 
         { 
                 $this->userName = $aRow['user_name'];
                 $this->userBirthday = $aRow['user_birthday'];
                 $this->userEmail = $aRow['user_email'];
                 $this->userPhone = $aRow['user_phone'];
                 $this->userDateOfAppointment = $aRow['user_apodate'];
                 $this->userTimeOfAppointment = $aRow['user_apotime'];
                 $this->userMessage = $aRow['user_msg'];

                 $output = array($this->userName,$this->userBirthday,$this->userEmail,$this->userPhone,$this->userDateOfAppointment,$this->userTimeOfAppointment,$this->userMessage); 
         }   
        
     return $stmt; 
    }

	 catch(PDOException $e)
       { 
          echo '<pre>'; 
          echo 'Regel: '.$e->getLine().'<br>'; 
          echo 'Bestand: '.$e->getFile().'<br>'; 
          echo 'Foutmelding: '.$e->getMessage(); 
          echo '</pre>'; 
       }   
    }


public function listAppointments($user_id)
	{
		
		try
		{	
			$stmt = $this->conn->prepare("SELECT * FROM appointments WHERE session_id='$user_id'");
            $stmt->execute(array(':session_id'=>$user_id));
            $stmt->execute();  

         while($aRow = $stmt->fetch(PDO::FETCH_ASSOC)) 
         { 
             $this->apoID = $aRow['apo_id'];
             $unames = $aRow['user_name'];

	          foreach((array) $this->apoID as $item){
                  echo '<option value="' . $item . '">';
                   foreach((array) $unames as $uname){echo $uname . '</option>';}
              }  
         }
        
    return $stmt; 
        }

	 catch(PDOException $e)
       {
           
          echo '<pre>'; 
          echo 'Regel: '.$e->getLine().'<br>'; 
          echo 'Bestand: '.$e->getFile().'<br>'; 
          echo 'Foutmelding: '.$e->getMessage(); 
          echo '</pre>'; 
       }   
    }
	

	public function updateAppointments($apoid, $userName,$userBirthday,$userEmail,$userPhone,$userDateOfAppointment,$userTimeOfAppointment,$userMessage)
	{
		
		try
		{
			$sqlUpdate = "UPDATE appointments SET user_name='$userName',
                                                  user_birthday='$userBirthday', 
                                                  user_email='$userEmail', 
                                                  user_phone='$userPhone', 
                                                  user_apodate='$userDateOfAppointment', 
                                                  user_apotime='$userTimeOfAppointment', 
                                                  user_msg='$userMessage'  
                                                  WHERE apo_id='$apoid'";
			
			$stmt = $this->conn->prepare($sqlUpdate);
           
			$stmt->bindparam(":userName", $userName);
			$stmt->bindparam(":userBirthday", $userBirthday);
            $stmt->bindparam(":userEmail", $userEmail);
            $stmt->bindparam(":userPhone", $userPhone);
            $stmt->bindparam(":userDateOfAppointment", $userDateOfAppointment);
			$stmt->bindparam(":userTimeOfAppointment", $userTimeOfAppointment);
			$stmt->bindparam(":userMessage", $userMessage);
            $stmt->execute();
            				  		        
     return $stmt; 
    }
       
       catch(PDOException $e)
       {
           
          echo '<pre>'; 
          echo 'Regel: '.$e->getLine().'<br>'; 
          echo 'Bestand: '.$e->getFile().'<br>'; 
          echo 'Foutmelding: '.$e->getMessage(); 
          echo '</pre>'; 
       }    
    }

    public function deleteAppointments($apoid)
	{
		
		try
		{
			$sqldelete = "DELETE FROM appointments WHERE apo_id = :apoid";
			$stmt = $this->conn->prepare($sqldelete);

            $stmt->bindParam(':apoid', $apoid, PDO::PARAM_INT);   
            $stmt->execute();

     return $stmt; 
    }
       
       catch(PDOException $e)
       {
           
          echo '<pre>'; 
          echo 'Regel: '.$e->getLine().'<br>'; 
          echo 'Bestand: '.$e->getFile().'<br>'; 
          echo 'Foutmelding: '.$e->getMessage(); 
          echo '</pre>'; 
       }    
    }
}


?>