<?php
require_once('dbconfig.php');

class APPOINTMENT
{	
	private $conn;
    public $userName, $userBirthday, $userEmail, $userPhone, $userDateOfAppointment, $userTimeOfAppointment, $userMessage;
	
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

	
	public function register($user_id, $uname, $ubday, $umail, $uphone, $udatum, $utijd, $umsg)
	{
		try
		{
			$stmt = $this->conn->prepare("INSERT INTO appointments(session_id, user_name,user_birthday,user_email,user_phone,user_apodate,user_apotime,user_msg)
		                                               VALUES(:session_id, :uname, :ubday, :umail, :uphone, :udatum, :utijd, :umsg)");
            
			$stmt->bindparam(":session_id", $user_id);
			$stmt->bindparam(":uname", $uname);
			$stmt->bindparam(":ubday", $ubday);
            $stmt->bindparam(":umail", $umail);
            $stmt->bindparam(":uphone", $uphone);
            $stmt->bindparam(":udatum", $udatum);
			$stmt->bindparam(":utijd", $utijd);
			$stmt->bindparam(":umsg", $umsg);
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
             
	     }
         $output = array($this->userName,$this->userBirthday,$this->userEmail,$this->userPhone,$this->userDateOfAppointment,$this->userTimeOfAppointment,$this->userMessage); 
                 
        
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
             $items = $aRow['apo_id'];
             $unames = $aRow['user_name'];

	          foreach((array) $items as $item){
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
	

	public function updateAppointments($apoid, $uname, $ubday, $umail, $uphone,  $udatum, $utijd, $umsg)
	{
		
		try
		{
			$sqlUpdate = "UPDATE appointments SET user_name='$uname', user_birthday='$ubday', user_email='$umail', user_phone='$uphone', user_apodate='$udatum', user_apotime='$utijd', user_msg='$umsg'  WHERE apo_id='$apoid'";
			
			$stmt = $this->conn->prepare($sqlUpdate);

			$stmt->bindparam(":uname", $uname);
			$stmt->bindparam(":ubday", $ubday);
            $stmt->bindparam(":umail", $umail);
            $stmt->bindparam(":uphone", $uphone);
            $stmt->bindparam(":udatum", $udatum);
			$stmt->bindparam(":utijd", $utijd);
			$stmt->bindparam(":umsg", $umsg);
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