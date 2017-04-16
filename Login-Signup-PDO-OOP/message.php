<?php

class Message{
//messages
  const USERNAME_ERROR = 'Pls, provide a username!';
  const BDAY_ERROR = 'date must be like dd-mm-yyyy and you must be 16 years or older';
  const USER_EMAIL_ERROR = 'Pls, provide an email';
  const USER_PHONE_ERROR = 'Pls, provide a valid phone number & number should be 10 character long';
  const APO_DATE_ERROR = 'Date must be like dd-mm-yyyy';
  const APO_TIME_ERROR = 'Time is a required field';
  const USER_MSG_ERROR = 'Message is required';
  const APO_NAME_EXIST_ERROR = 'you allready have an upcoming';
  const APO_EMAIL_EXIST_ERROR = 'There is already an open appointment referring to your';


  const LOCATION = 'Huidige locatie'; 
  const TRAVELMETHOD = 'Reis methode';
  const CALC_TRAVELMETHOD_MSG = 'Deze resultaten zijn berekend op basis van door uw gekozen reis methode';

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
}

?>