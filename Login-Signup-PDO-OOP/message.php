<?php

define('USERNAME_ERROR', 'Pls provide a username');
define('BDAY_ERROR', 'date must be like dd-mm-yyyy and you must be 16 years or older');
define('USER_EMAIL_ERROR', 'Pls, provide an email');
define('USER_PHONE_ERROR', 'Pls, provide a valid phone number & number should be 10 character long');
define('APO_DATE_ERROR', 'Date must be like dd-mm-yyyy');
define('APO_TIME_ERROR','Time is a required field');
define('USER_MSG_ERROR', 'Message is required');
define('APO_NAME_EXIST_ERROR', 'you allready have an upcoming');
define('APO_EMAIL_EXIST_ERROR','There is already an open appointment referring to your');

//
define('AGE_MIN',16);
define('PHONE_NUMBER_MAX_LENGTH', 10);
define('LOCATION','Huidige locatie'); 
define('TRAVELMETHOD','Reis methode');
define('CALC_TRAVELMETHOD_MSG', 'Deze resultaten zijn berekend op basis van door uw gekozen reis methode');

//list all travel options <
  function travelOptions(){

	  //tavel options in a Associative array box
	  $travelOptionsList = array( "DRIVING"=>"Auto", "WALKING"=>"Lopen", "BICYCLING"=>"Fietsen", "TRANSIT"=>"OV" );

	//get each travel options as key and value <
	foreach($travelOptionsList as $key => $value){
		    
			//save results in the $output variable and retun it
            $output .= "<option value='" . $key . "'>" . $value . "</option>";
			}/* = foreach > = */

	return $output;
  }/* = travelOptions > = */


?>