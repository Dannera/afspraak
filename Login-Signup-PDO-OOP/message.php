<?php

class Message{
//messages

const LOCATION = 'Huidige locatie'; 
const TRAVELMETHOD = 'Reis methode';
const CALC_TRAVELMETHOD_MSG = 'Deze resultaten zijn berekend op basis van door uw gekozen reis methode';

function travelOptions(){
	$travelOptionsList = array("DRIVING"=>"Auto", "WALKING"=>"Lopen", "BICYCLING"=>"Fietsen", "TRANSIT"=>"OV",);

	foreach($travelOptionsList as $key => $value){
            
			echo "<option value='" . $key . "'>" . $value . "</option>";
		
	}

	
}
}

?>