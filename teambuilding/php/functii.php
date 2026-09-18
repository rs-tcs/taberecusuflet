<?php

			
function uniCode($nume,$prenume,$data){
		
		// numele
		if($nume){
			
			$nume = ucfirst(substr($nume,0,1));
			}
			else
			{
			$nume = "";	
			}
			
		// prenumele	
		if($prenume){
			
			$prenume = ucfirst(substr($prenume,0,1));
			}
			else
			{
			$prenume = "";	
			}
			
			
		// anul		
		$anul = substr($data,2,2);		
			
		
	
		// luna
		$luna = substr($data,5,2);
				if($luna < 10){
					
					$luna = substr($luna,1,1);
					}
				else if($luna == 10){
					
					$luna = "A";
					}
				else if ($luna > 10){
					
					if($luna == 11) $luna = "B";
					if($luna == 12) $luna = "C";
					if($luna == 13) $luna = "D";
					}
			
			
		// ziua
		
		$ziua = substr($data,8,2);
			 if( $ziua < 10){
				 
				$ziua = substr($ziua,1,1);
				 }
			 else if($ziua == 10){
				 
				 $ziua = "A";
				 }
		
		      else if($ziua > 10){
				  
				if($ziua == 11) $ziua = "B";
				if($ziua == 12) $ziua = "C";
				if($ziua == 13) $ziua = "D";
				if($ziua == 14) $ziua = "E";
				if($ziua == 15) $ziua = "F";
				if($ziua == 16) $ziua = "G";
				if($ziua == 17) $ziua = "H";
				if($ziua == 18) $ziua = "I";
				if($ziua == 19) $ziua = "J";
				if($ziua == 20) $ziua = "K";
				if($ziua == 21) $ziua = "L";
				if($ziua == 22) $ziua = "M";
				if($ziua == 23) $ziua = "N";
				if($ziua == 24) $ziua = "O";
				if($ziua == 25) $ziua = "P";
				if($ziua == 26) $ziua = "R";
				if($ziua == 27) $ziua = "S";
				if($ziua == 28) $ziua = "T";
				if($ziua == 29) $ziua = "U";
				if($ziua == 30) $ziua = "V"; 
				if($ziua == 31) $ziua = "X"; 
				  
				  
				  }
		
			$cod_unic = $anul.$nume.$prenume.$luna.$ziua;
			return $cod_unic;	
		}
		
		
		
function catVarsta($varsta){
	
				
			// genereaza piticoti
			for($x = 2; $x <= 5; $x++){			
			if($varsta == $x){				
			$varsta = "piticoti";
			}
			}
			
			// genereaza pitici
			for($x = 6; $x <= 7; $x++){			
			if($varsta == $x){				
			$varsta = "pitici";
			}
			}
			
			//genereaza copii
			for($x = 8; $x <= 9; $x++){
			if($varsta == $x) {			
			$varsta = "copii";
			}
			}
			
			// genereaza spiridusi
			for($x = 10; $x <= 11; $x++){
			if($varsta == $x){			
			$varsta = "spiridusi";
			}
			}
			
			// genereaza uriasi
			for($x = 12; $x <= 13; $x++){
			if($varsta == $x){			
			$varsta = "uriasi";
			}
			}
			
			
			// genereaza tineri
			for($x = 14; $x <= 19; $x++){			
			if($varsta == $x){			
			$varsta = "tineri";			
			}
			}
			
			// genereaza adulti
			for($x = 20; $x <= 39; $x++){			
			if($varsta == $x){			
			$varsta = "adulti";
			}
			}
			
			
			// genereaza seniori
			for($x = 40; $x <= 49; $x++){			
			if($varsta == $x){			
			$varsta = "seniori";
			}
			}
			
			// genereaza forever_young
			
			if($varsta >= 50){			
			$varsta = "forever_young";
			}
			
			
			return $varsta;
		}
		
	

function redirectUser(){
		
			if(isset($_SESSION['cod_unic'])){
				unset($_SESSION['cod_unic']);
			
				
				}	
		
		}
		
function capitalCase($nume){
	
	if(strrchr($nume, '-')){
				
		 $name= str_replace('-',' ',$nume);
		 $each = explode(' ',$name);
		 $nume = '';
		 	foreach($each as $elem){
				$nume .= ucfirst(strtolower($elem)).'-';
			}
			
			$result = substr($nume,0,-1);			
		 
	}
	else if(strrchr($nume, '_'))
	{
		$name = str_replace('_',' ',$nume);
		$each = explode(' ',$name);
		 $nume = '';
		 	foreach($each as $elem){
				$nume .= ucfirst(strtolower($elem)).'_';
			}
			$result = substr($nume,0,-1);		
	}
	elseif(strrchr($nume, ' ')){		
		$each = explode(' ',$nume);
		 $nume = '';
		 	foreach($each as $elem){
				$nume .= ucfirst(strtolower($elem)).'&nbsp;';
			}
			$result = substr($nume,0,-1);		
	}
	else
	{
		$result = ucfirst(strtolower($nume));	
		
	}
	
	return $result;
}
		
		

?>