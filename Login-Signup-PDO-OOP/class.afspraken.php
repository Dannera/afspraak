<?php
require_once('dbconfig.php');


class AFSPRAAK
{	
	private $conn;
	
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

	
	public function register($uname, $ubday, $umail, $uphone, $udatum, $utijd, $umsg)
	{
		try
		{
		
			
			$stmt = $this->conn->prepare("INSERT INTO appointments(user_name,user_birthday,user_email,user_phone,user_apodate,user_apotime,user_msg)
		                                               VALUES(:uname, :ubday, :umail, :uphone, :udatum, :utijd, :umsg)");

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