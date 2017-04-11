<?php

// My Birthday :)
$date_1 = new DateTime( '1989-06-15' );

// Todays date
$date_2 = new DateTime( date( 'Y-m-d' ) );

$difference = $date_2->diff( $date_1 );

// Echo the as string to display in browser for testing
echo (string)$difference->y;
echo "<br/>";


/**
 * In a function
 */
function yearsMonthsBetween ( $date1, $date2 ) {
	
	$d1 = new DateTime( $date1 );
	$d2 = new DateTime( $date2 );

	//minimum year
	$minYear = 16;
	
	$diff = $d2->diff( $d1 );
	
	$betweenYear = $diff->y;
if($betweenYear < $minYear){
	$result =  "You must be atleast " . $minYear . " old";
} else{
	 $result = $betweenYear;
}
 return $result;
}

echo yearsMonthsBetween('2005-06-15', date( 'Y-m-d' ) );

?>