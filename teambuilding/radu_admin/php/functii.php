<?php


function uniCode($nume,$prenume,$data){
		
		if($nume){
			
			$nume = ucfirst(substr($nume,0,1));
			}
			else
			{
			$nume = "";	
			}
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
			for($x = 2; $x < 6; $x++){			
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

	function categorie($categorie){
		
			// genereaza piticoti
			for($x = 2; $x < 6; $x++){			
			if($categorie == $x){				
			$categorie = "piticoti";
			}
			}
			
		
			// genereaza pitici
			for($x = 6; $x <= 7; $x++){			
			if($categorie == $x){				
			$categorie = "pitici";
			}
			}
			
			//genereaza copii
			for($x = 8; $x <= 9; $x++){
			if($categorie == $x) {			
			$categorie = "copii";
			}
			}
			
			// genereaza spiridusi
			for($x = 10; $x <= 11; $x++){
			if($categorie == $x){			
			$categorie = "spiridusi";
			}
			}
			
			// genereaza uriasi
			for($x = 12; $x <= 13; $x++){
			if($categorie == $x){			
			$categorie = "uriasi";
			}
			}
			
			
			// genereaza tineri
			for($x = 14; $x <= 19; $x++){			
			if($categorie == $x){			
			$categorie = "tineri";			
			}
			}
			
			// genereaza adulti
			for($x = 20; $x <= 39; $x++){			
			if($categorie == $x){			
			$categorie = "adulti";
			}
			}
			
			
			// genereaza seniori
			for($x = 40; $x <= 49; $x++){			
			if($categorie == $x){			
			$categorie = "seniori";
			}
			}
			
			// genereaza forever_young
			
			if($categorie >= 50){			
			$categorie = "forever_young";
			}
			
			
			return $categorie;
		}
		
		/*
		function cleanFormat($nume){
		
		if(strstr($nume, "_", true)){
			
			$nume = str_replace("_"," ",$nume);
			}
		if(strstr($nume, "-", true)){
			$nume = str_replace("-"," ",$nume);
			}
		if(strstr($nume, " ", true)){
			$nume = str_replace(" "," ",$nume);
			}	
			return $nume;
		}
		*/
?>