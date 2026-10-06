// JavaScript Document



function showOptions(val){
	
		var compMountainbike = $('#comp_mountainbike');
		var compFreeride = $("#comp_freeride");
		var compSkiSnowboard = $("#comp_ski_snowboard");
		var compTrailRunning = $("#comp_trail_running");
		var compDuatlon = $("#comp_duatlon");
		var compAlpinism = $("#comp_alpinism");
		var compEchitatie = $("#comp_echitatie");
		var compInot = $("#comp_inot");
		
		// afisarea optiunilor
		
		if(val == "Ciclism"){		
			compMountainbike.fadeIn(300);			
		}
		else 
		{
			compMountainbike.fadeOut(10);
		}
			
			
		if(val == "Ski/Snowboard"){	
			
			compSkiSnowboard.fadeIn(300);			
		}
		else
		{
			compSkiSnowboard.fadeOut(10);
		}
		
		
		if(val == "Alergare"){	
			
			compTrailRunning.fadeIn(300);
		}
		else
		{
			compTrailRunning.fadeOut(10);
		}	
		
		
		if(val == "Duatlon"){
			
			compDuatlon.fadeIn(300);			
		}
		else
		{
			
			compDuatlon.fadeOut(10);	
		}
		
		
		if(val == "Freeriding"){
				
			compFreeride.fadeIn(300);			
		}
		else
		{
			compFreeride.fadeOut(10);
		}
		
		if(val == "Alpinism"){
				
			compAlpinism.fadeIn(300);			
		}
		else
		{
			compAlpinism.fadeOut(10);
		}
		
		if(val == "Echitatie"){
				
			compEchitatie.fadeIn(300);			
		}
		else
		{
			compEchitatie.fadeOut(10);
		}

		
		if(val == "Inot"){
				
			compInot.fadeIn(300);			
		}
		else
		{
			compInot.fadeOut(10);
		}
		
}

function redirectPage(val){

		//  redirectarea
		
		if(val == "Comana Bike Fest"){	
			
			window.location = "tabel_inscriere_competitie_bikefest.php?tip_comp="+val;
		}
		
		if(val == "On Top of the World"){	
			
			window.location = "tabel_inscriere_competitie_sinaia.php?tip_comp="+val;
		}
		
		if(val == "Cupa Veseliei"){	
			
			window.location = "tabel_inscriere_competitie_bikefest.php?tip_comp="+val;
		}
		
		if(val == "Cupa Tabere cu Suflet"){	
			
			window.location = "tabel_inscriere_competitie_bikefest.php?tip_comp="+val;
		}
		
		if(val == "Cupa 1 Iunie"){	
			
			window.location = "tabel_inscriere_competitie_bikefest.php?tip_comp="+val;
		}
		
		if(val == "Cupa Malinului"){
			
			window.location = "tabel_inscriere_competitie_freeride.php?tip_comp="+val;
			}
		
		if(val == "Maraton Tabere cu Suflet"){
			
			window.location = "tabel_inscriere_competitie_maraton.php?tip_comp="+val;
			}
			
		if(val == "Duatlon Tabere cu Suflet"){
			
			window.location = "tabel_inscriere_competitie_duatlon.php?tip_comp="+val;
			}
			
		if(val == "Ski Indoor"){
			
			window.location = "tabel_inscriere_competitie_ski.php?tip_comp="+val;
			}
			
		if(val == "Cupa Tabere cu Suflet Ski/Snowboard"){
			
			window.location = "tabel_inscriere_competitie_ski.php?tip_comp="+val;
			}
			
			
		if(val == "Cupa Tabere cu Suflet la Escalada"){
			
			window.location = "tabel_inscriere_competitie_alpinism.php?tip_comp="+val;
			}
			
		if(val == "On Top of the World Run Fest"){
			
			window.location = "tabel_inscriere_competitie_maraton.php?tip_comp="+val;
			}
			
		if(val == "Cupa Tabere cu Suflet la Inot"){
			
			window.location = "tabel_inscriere_competitie_inot.php?tip_comp="+val;
			}
			
		if(val == "Cupa Veseliei Run Fest"){
			
			window.location = "tabel_inscriere_competitie_alergare_veseliei.php?tip_comp="+val;
			}
			
			
	}
	
	
function showCompMount(){
	
	var compMountainbike = document.getElementById('comp_mountainbike');
	var mountainbike = document.getElementById('tip_competitie');
	if(mountainbike.value == "mountainbike"){
		
		compMountainbike.style.display = "inline";
		
		}
	
	}

// arata ckeckbox-ul cu bifa family daca traseul copiilor este selectat	
function showInsotitor(){
	
	var traseu = $('#traseu option:selected').text();	
	
	
		if(traseu == "Copiilor"){
			
			$('.cellFamily').fadeIn(500);
			
		}
		else
		{
			$('.cellFamily').fadeOut(500, function(){
				
				$('#insotitor').removeAttr('checked');
				
				});	
			 
		}	
			
	}
	


	
	
// arata mesajul de inscriere in cealalta competitie pentru duatlon	
function showPopup(){
	
	var duatlon = $('#duatlon');
	var popup = $('#popup');
		if(duatlon.is(':checked')){
			
			popup.fadeIn('slow');
			
			}
			else
			{
			
			popup.fadeOut('slow');
				
			}
	
	}
	
// arata mesajul de inscriere in cealalta competitie pentru duatlon	
function showPopup1(){
	
	var duatlon = $('#duatlon');
	var popup1 = $('#popup1');
		if(duatlon.is(':checked')){
			
			popup1.fadeIn('slow');
			
			}
			else
			{
			
			popup1.fadeOut('slow');
				
			}
	
	}


// ascunde optiunile de plata catre magazin	
function modPlata(){
	var taxa = document.getElementById('achitat');
	var modPlata = document.getElementById('mod_plata');
		if(taxa.checked == 1){
			
			modPlata.options[1].disabled = true;
			modPlata.options[2].disabled = true;
			modPlata.options[3].disabled = true;
		}
		else
		{
		//	
		}
	
	}
	
	
	// verifica daca checkbox-ul insotitor este bifat	
	 function familyCheck(){
			
			var checkboxInsotitor = $('#insotitor');
			var result = "";
				
				if(checkboxInsotitor.is(':checked')){
					
					result = true;
				}
				else
				{
					result = false;
				}				
				
				
				return result;				
				
			}
			
	// afisarea mesajului pentru traseul tricicletelor
			
	 function showMessage(){
		 var mesaj = $('#traseu').val();
		 if(mesaj == "Tabere cu Suflet"){
			 
			 $('#traseu').after('<p style="color:red; font-size:12px">La acest traseu recomandam insotirea copiilor! Participarea insotitorilor este gratuită</p>');
			 //$('#duatlon').fadeOut(500);
			 //$('#acord').fadeOut(500);
			 }
			 else
			 {
			$('#traseu').next('p').remove();
			//$('#duatlon').fadeIn(500);
			//$('#acord').fadeIn(500); 
			 }
		 
		 }	
       
		
		// afiseaza valoarea taxei pentru competitiile de la Comana
		function valoareTaxaBikefest(){															
																
			var traseuBikeFest = $('#traseu').val();
			var family = $('#insotitor');
			var vipBikefest = $('#vip');
			var trasee = ['TeamXpert','Narciselor','Tabere cu Suflet','Piticilor'];
			var curier = $('#curier');
			
			// valori taxe
			var taxaBikefest = 50;
			var taxaBikefestPiticoti = 25;
			
			
			
			// taxa family
			var taxaFamily = 10;		
			
			// taxa vip
			var taxaVip = 50;			
			
			// taxa curier
			var taxaBuc = 10;
			var taxaProv = 18;
			
			// daca traseul nu se afla in niciuna din variabilele din array-ul trasee
			if($.inArray($('#traseu').val(), trasee) == -1){
				
				$('#valoare').html('Selectați traseul');
			
			}
			// daca traseul este Copiilor
			else if($('#traseu').val() == 'Tabere cu Suflet')
			{
				$('#valoare').html(taxaBikefest+" "+'Lei');
				
					// daca optiunea family este bifata
					if(family.is(':checked')){
						
						$('#valoare').html((taxaBikefest + taxaFamily)+" "+'Lei');
						
																		
						// daca optiunea vip este bifata
						if(vipBikefest.is(':checked')){
							$('#valoare').html((taxaBikefest + taxaFamily + taxaVip)+" "+'Lei');				
							
							//optiune de cod inactiva, vechiul sistem de curierat
							/****************
							// daca otpiunea curier este bifata
							 if($('#curier').is(':checked')) {
								 
									   if($('#localitate').val() == 'Bucuresti'){
										   
										   $('#valoare').html((taxaBikefest + taxaFamily + taxaVip + taxaBuc)+" "+'Lei');
									   }
									   else if($('#localitate').val() == 'Provincie'){
										   
										   $('#valoare').html((taxaBikefest + taxaFamily + taxaVip + taxaProv)+" "+'Lei');
									  }
									  else 
									  {
										  $('#valoare').html((taxaBikefest + taxaFamily + taxaVip)+" "+'Lei');
									  }									  
								
							 }
							 ******************/
							 						 
						}
						
						
					}
					else // daca optiunea family nu este bifata
					{
						// daca optiunea vip este bifata
						if(vipBikefest.is(':checked')){
							$('#valoare').html((taxaBikefest + taxaVip)+" "+'Lei');	
							
							/*****************************
							// daca optiunea curier este bifata
							 if($('#curier').is(':checked')) {
								 
								 if($('#localitate').val() == 'Bucuresti'){
									 
									 $('#valoare').html((taxaBikefest + taxaVip + taxaBuc)+" "+'Lei');
								 }
								 else if($('#localitate').val() == 'Provincie'){
									 
									 $('#valoare').html((taxaBikefest + taxaVip + taxaProv)+" "+'Lei');
								}
								else
								{
									$('#valoare').html((taxaBikefest + taxaVip)+" "+'Lei');
								}							
							 }
							**********************************/
												
						 }						 
						 
					}
				
			}
			// daca traseul este diferit de cel al Copiilor
			else if($('#traseu').val() == 'TeamXpert' || $('#traseu').val() == 'Narciselor')
			{
				
				$('#valoare').html(taxaBikefest+" "+'Lei');
				
				 // daca optiunea vip este bifata
				if(vipBikefest.is(':checked')){
						$('#valoare').html((taxaBikefest + taxaVip)+" "+'Lei');	
						
						/****************************
						// daca otpiunea curier este bifata
							 if($('#curier').is(':checked')) {
								 
									   if($('#localitate').val() == 'Bucuresti'){
										   
										   $('#valoare').html((taxaBikefest + taxaVip + taxaBuc)+" "+'Lei');
									   }
									   else if($('#localitate').val() == 'Provincie'){
										   
										   $('#valoare').html((taxaBikefest + taxaVip + taxaProv)+" "+'Lei');
									  }
									  else 
									  {
										  $('#valoare').html((taxaBikefest + taxaVip)+" "+'Lei');
									  }									  
								
							 }
						 *****************************/
							 
				}
			}
			// daca traseul este Piticilor
			else if($('#traseu').val() == 'Piticilor'){
				
				$('#valoare').html(taxaBikefestPiticoti+" "+'Lei');
				
				// daca optiunea vip este bifata
				if(vipBikefest.is(':checked')){
						$('#valoare').html((taxaBikefestPiticoti + taxaVip)+" "+'Lei');
						
							/*******************************
							// daca otpiunea curier este bifata
							 if($('#curier').is(':checked')) {
								 
									   if($('#localitate').val() == 'Bucuresti'){
										   
										   $('#valoare').html((taxaVip + taxaBuc)+" "+'Lei');
									   }
									   else if($('#localitate').val() == 'Provincie'){
										   
										   $('#valoare').html((taxaVip + taxaProv)+" "+'Lei');
									  }
									  else 
									  {
										  $('#valoare').html((taxaVip)+" "+'Lei');
									  }									  
								
							 }
							 ***********************************/						
				}
			}
			
		}
		
		
		// afiseaza valoarea taxei pentru competitia Cupa Veseliei
		function valoareTaxaVeseliei(){
			
			var traseu = $('#traseu').val();
			var insotitor = $('#insotitor');
			var vip = $('#vip');
			
			// valori taxe
			var taxaInsotitor = 10;
			var taxaVip = 35;
			var taxaAdult = 10;	
			// taxa curier
			var taxaBuc = 10;
			var taxaProv = 18;	
					
					
					
					// ramura traseu este neselectata	
					if(traseu == ''){
						
						$("#valoare").html( 'Selectați vă rog traseul');
						
					}
					
					// ramura traseu este selectata cu optiunea Copiilor
					else if(traseu == "Copiilor")
					{
						
							$("#valoare").html( 'Gratuit');
							
							// optiunea insotitor este selectata
							if(insotitor.is(':checked')){
								
								
								$("#valoare").html(taxaInsotitor+" "+'Lei');								
								$('.payment').fadeIn(300);
								
									// optiunea vip este selectata
									if(vip.is(':checked')){
										
										$("#valoare").html(taxaInsotitor + taxaVip + " "+'Lei');										
											
												 // optiunea curier este selectata
												  if($('#curier').is(':checked')){
												
														  // optiunea localitate este selectata
														  if($('#localitate').val() == "Bucuresti"){
															  
															  $("#valoare").html(taxaInsotitor + taxaVip + taxaBuc + " "+'Lei');
															  
														  }
														  else if($('#localitate').val() == "Provincie"){
															  
															  $("#valoare").html(taxaInsotitor + taxaVip + taxaProv + " "+'Lei');
														  }
														  else if($('#localitate').val() == "")
														  {
															  
															  $("#valoare").html(taxaInsotitor + taxaVip + " "+'Lei');
														  }	
														  
												  }
												  // optiunea curier nu este selectata
												  else
												  {
													  
													 $("#valoare").html(taxaInsotitor + taxaVip + " "+'Lei'); 
												  }
											
									   }
									
								
							  }
							  // optiunea insotitor nu este selectata
							  else
							  {			
							  
							  		  // optiunea vip este selectata					
									  if(vip.is(':checked')){
										  
										  				 // optiunea localitate este selectata
										  				 if($('#localitate').val() == "Bucuresti"){
															  
															  $("#valoare").html(taxaVip + taxaBuc + " "+'Lei');
															  
														  }
														  else if($('#localitate').val() == "Provincie"){
															  
															  $("#valoare").html(taxaVip + taxaProv + " "+'Lei');
														  }
														  
														  // optiunea localitate nu este selectata
														  else if($('#localitate').val() == "")
														  {
															  
															  $("#valoare").html(taxaVip + " "+'Lei');
														  }	
									  }
									  
									  // optiunea vip nu este selectata
									  else
									  
									  {		
									  
									  		// optiunea metoda de plata se ascunde (optiunea Gratuit este afisata)						
											$('.payment').fadeOut(300, function(){
											
												$('.taxaCell').fadeOut(200);
												$('#achitat').removeAttr('checked');
												$('#mod_plata').val('');
												$('#suma_plata').val('');
												$('#chit_plata').val('');
											
											});
									  }
								  
							  }
						
						
						
					}
					
					// ramura traseu este selectata cu optiunea Veseliei
					else if(traseu == "Veseliei"){
						
						$("#valoare").html(taxaAdult + " "+'Lei');
						$('.payment').fadeIn(300);
									// optiunea vip este selectata
									if(vip.is(':checked')){
										
										$("#valoare").html(taxaAdult + taxaVip + " "+'Lei');										
											
												 // optiunea curier este selectata
												  if($('#curier').is(':checked')){
												
														  // optiunea localitate este selectata
														  if($('#localitate').val() == "Bucuresti"){
															  
															  $("#valoare").html(taxaAdult + taxaVip + taxaBuc + " "+'Lei');
															  
														  }
														  else if($('#localitate').val() == "Provincie"){
															  
															  $("#valoare").html(taxaAdult + taxaVip + taxaProv + " "+'Lei');
														  }
														  // optiunea localitate are valoarea nula
														  else if($('#localitate').val() == "")
														  {
															  
															  $("#valoare").html(taxaAdult + taxaVip + " "+'Lei');
														  }	
														  
												  }
											
									}
									
									
					}
						
						
				
			
			}
			
			
			
		// afiseaza valoarea taxei Cupa 1 Iunie
		function valoareTaxaIunie(){
			
				// valoare taxa
				var taxaInsotitor = 10;
				var taxaVip = 35;
				// taxa curier
				var taxaBuc = 10;
				var taxaProv = 18;	
				
				
				// optiunea traseu nu este selectata
				if($('#traseu').val() == ''){
					
					$('#valoare').html('Selectați traseul');
					
					// la incar
					$('.payment').css({'display': 'none'});	
					
				}
				
				// optiunea traseu este selectata (Copiilor)
				else 
				{
					  $('#valoare').html('Gratuit');
					  
					  // optiunea Family este selectata
					  if($('#insotitor').is(':checked')){					  
						   
						   $("#valoare").html(taxaInsotitor +" "+'Lei');
						   
						   
							 $('.payment').fadeIn(300);
						   
						   		// optiunea vip este selectata
								if($('#vip').is(':checked')){									
									
									$("#valoare").html(taxaInsotitor + taxaVip + " "+'Lei');
									
									
										// optiunea curier este selectata
										if($('#curier').is(':checked')){
											
														// optiunea localitate este selectata
														  if($('#localitate').val() == "Bucuresti"){
															  
															  $("#valoare").html(taxaInsotitor + taxaVip + taxaBuc + " "+'Lei');
															  
														  }
														  else if($('#localitate').val() == "Provincie"){
															  
															  $("#valoare").html(taxaInsotitor + taxaVip + taxaProv + " "+'Lei');
														  }
														  // optiunea localitate are valoarea nula
														  else if($('#localitate').val() == "")
														  {
															  
															  $("#valoare").html(taxaInsotitor + taxaVip + " "+'Lei');
														  }	

											
										}
									
								}
							
								
												   
					   
					   
					  }
					  
					  // optiunea family nu este selectata
					  else
					  {
						  	  
							  // optiunea vip este selectata
							  if($('#vip').is(':checked')){
								  
								  $('.payment').fadeIn(300);
								  $("#valoare").html(taxaVip + " "+'Lei');
								  		
														  // optiunea localitate este selectata
														  if($('#localitate').val() == "Bucuresti"){
															  
															  $("#valoare").html(taxaVip + taxaBuc + " "+'Lei');
															  
														  }
														  else if($('#localitate').val() == "Provincie"){
															  
															  $("#valoare").html(taxaVip + taxaProv + " "+'Lei');
														  }
														  // optiunea localitate are valoarea nula
														  else if($('#localitate').val() == "")
														  {
															  
															  $("#valoare").html(taxaVip + " "+'Lei');
														  }	
								  
							  }
							  
							  // optiunea vip nu este selectata
							  else
							  {
								  	// optiunea payment se ascunde
									$('.payment').fadeOut(300, function(){
													  
														  $('.taxaCell').fadeOut(200);
														  $('#achitat').removeAttr('checked');
														  $('#mod_plata').val('');
														  $('#suma_plata').val('');
														  $('#chit_plata').val('');
													  
									  }); 
								
							  }
							  
					  }
					
				}
			
			
		}
		
		
		
		// afiseaza valoarea taxei pentru competitia de ciclism de la Sinaia
		function valoareTaxaSinaia(){
			
						
			// valori taxe
			var taxaTraseu = 50;
			var taxaTraseuMid = 60;
			var taxaTraseuLate = 70;
			
			
			
			// taxa vip
			var taxaVip = 35;
			
			// taxa curier
			var taxaBuc = 10;
			var taxaProv = 18;
			
				
			// limita de timp dupa care valoarea taxei se schimba
			var limitDateMid = '2016/06/21';
			var limitDateLate = '2016/07/22';
			
			// competitii
			var trasee = ['Hobby Ride', 'Easy Ride', 'Profi Ride', 'Elite Ride'];
			
			if(new Date() <= new Date(limitDateMid)){
			
				if($.inArray($('#traseu').val(), trasee) == -1){				
						
					$('#valoare').html('Selectați un traseu');			
					
				}
				else 
				{	
						
						$("#valoare").html( taxaTraseu+" "+'Lei');	
					
						if($('#vip').is(':checked')){
							
								$("#valoare").html( (taxaTraseu + taxaVip)+" "+'Lei');
								
								if($('#curier').is(':checked')){
									
									// optiunea localitate este selectata
									if($('#localitate').val() == "Bucuresti"){
										
										$("#valoare").html(taxaTraseu + taxaVip + taxaBuc + " "+'Lei');
										
									}
									else if($('#localitate').val() == "Provincie"){
										
										$("#valoare").html(taxaTraseu + taxaVip + taxaProv + " "+'Lei');
									}
									// optiunea localitate are valoarea nula
									else if($('#localitate').val() == "")
									{
										
										$("#valoare").html(taxaTraseu + taxaVip + " "+'Lei');
									}	
									
								}
						}			
					
				}
			}
			else if(new Date() > new Date(limitDateMid) && new Date() <= new Date(limitDateLate))
			{
					if($.inArray($('#traseu').val(), trasee) == -1){				
						
					$('#valoare').html('Selectați un traseu');			
					
					}
					else 
					{	
						
						$("#valoare").html( taxaTraseuMid+" "+'Lei');	
					
						if($('#vip').is(':checked')){
							
								$("#valoare").html( (taxaTraseuMid + taxaVip)+" "+'Lei');
								
								if($('#curier').is(':checked')){
									
									// optiunea localitate este selectata
									if($('#localitate').val() == "Bucuresti"){
										
										$("#valoare").html(taxaTraseuMid + taxaVip + taxaBuc + " "+'Lei');
										
									}
									else if($('#localitate').val() == "Provincie"){
										
										$("#valoare").html(taxaTraseuMid + taxaVip + taxaProv + " "+'Lei');
									}
									// optiunea localitate are valoarea nula
									else if($('#localitate').val() == "")
									{
										
										$("#valoare").html(taxaTraseuMid + taxaVip + " "+'Lei');
									}	
									
								}
						}			
					
				}
				
			}
			else if(new Date() > new Date(limitDateLate)){
				
				if($.inArray($('#traseu').val(), trasee) == -1){				
						
					$('#valoare').html('Selectați un traseu');			
					
					}
					else 
					{	
						
						$("#valoare").html( taxaTraseuLate+" "+'Lei');	
					
						if($('#vip').is(':checked')){
							
								$("#valoare").html( (taxaTraseuLate + taxaVip)+" "+'Lei');
								
								if($('#curier').is(':checked')){
									
									// optiunea localitate este selectata
									if($('#localitate').val() == "Bucuresti"){
										
										$("#valoare").html(taxaTraseuLate + taxaVip + taxaBuc + " "+'Lei');
										
									}
									else if($('#localitate').val() == "Provincie"){
										
										$("#valoare").html(taxaTraseuLate + taxaVip + taxaProv + " "+'Lei');
									}
									// optiunea localitate are valoarea nula
									else if($('#localitate').val() == "")
									{
										
										$("#valoare").html(taxaTraseuLate + taxaVip + " "+'Lei');
									}	
									
								}
						}			
					
				}
				
				
			}
	    }	
	 	
		
		
			
		// verifica daca checkbox-ul vip este activ
		function vipChecked(){
			
			var checkbox = $('#vip');
			var valVip = "";
				if(checkbox.is(':checked')){
					valVip = 35;	
				}
				else
				{
					valVip = 0;	
				}
				return valVip;
		}
		
		// afiseaza valoarea taxei la competitia de maraton de la Comana
		function valSelTraseu(){
			
				var traseu = $("#traseu");				
				var valSelect = traseu.val();				
				// taxa trasee				
				var taxaMaraton = 70;
				var taxaSemimaraton = 50;
				var taxaCros = 50;
				var taxaCopii = 30;
				var taxaDuatlon = 80;
				
				// taxa vip
				var taxaVip = 35;				
				// taxa curier
				var taxaBuc = 10;
				var taxaProv = 18;
				
				
						// optiunea traseu nu este selectata		
						if(valSelect == ""){
							
								$('#valoare').html('Selectați un traseu');
							
						}
						
						// optiunea traseu este Maraton
						else if(valSelect == "Maraton")
						{
							
								$('#valoare').html(taxaMaraton + " " + 'Lei');
								
								// optiunea vip este bifata
								if($('#vip').is(':checked')){
										
										$('#valoare').html(taxaMaraton + taxaVip + " " + 'Lei');
										
										// optiunea curier este bifata
										if($('#curier').is(':checked')){
											
												  // optiunea localitate este selectata
												  if($('#localitate').val() == "Bucuresti"){
													  
													  $("#valoare").html(taxaMaraton + taxaVip + taxaBuc + " "+'Lei');
													  
												  }
												  else if($('#localitate').val() == "Provincie"){
													  
													  $("#valoare").html(taxaMaraton + taxaVip + taxaProv + " "+'Lei');
												  }
												  // optiunea localitate are valoarea nula
												  else if($('#localitate').val() == "")
												  {
													  
													  $("#valoare").html(taxaMaraton + taxaVip + " "+'Lei');
												  }	
											
										}	
									
								}
							
						}
						// optiunea traseu este Semimaraton
						else if(valSelect == "Semimaraton")
						{
								
								$('#valoare').html(taxaSemimaraton + " " + 'Lei');
								
								// optiunea vip este selectata
								if($('#vip').is(':checked')){
										
										$('#valoare').html(taxaSemimaraton + taxaVip + " " + 'Lei');
										
										// optiunea curier este selectata
										if($('#curier').is(':checked')){
											
												  // optiunea localitate este selectata
												  if($('#localitate').val() == "Bucuresti"){
													  
													  $("#valoare").html(taxaSemimaraton + taxaVip + taxaBuc + " "+'Lei');
													  
												  }
												  else if($('#localitate').val() == "Provincie"){
													  
													  $("#valoare").html(taxaSemimaraton + taxaVip + taxaProv + " "+'Lei');
												  }
												  // optiunea localitate are valoarea nula
												  else if($('#localitate').val() == "")
												  {
													  
													  $("#valoare").html(taxaSemimaraton + taxaVip + " "+'Lei');
												  }	
											
										}	
									
								}
							
						
						}
						
						// optiunea traseu este Cros
						else if(valSelect == "Cros")
						{
							
								$('#valoare').html(taxaCros + " " + 'Lei');
								// optiunea vip este selectata
								if($('#vip').is(':checked')){
										
										$('#valoare').html(taxaCros + taxaVip + " " + 'Lei');
										// optiunea curier este selectata
										if($('#curier').is(':checked')){
											
												  // optiunea localitate este selectata
												  if($('#localitate').val() == "Bucuresti"){
													  
													  $("#valoare").html(taxaCros + taxaVip + taxaBuc + " "+'Lei');
													  
												  }
												  else if($('#localitate').val() == "Provincie"){
													  
													  $("#valoare").html(taxaCros + taxaVip + taxaProv + " "+'Lei');
												  }
												  // optiunea localitate are valoarea nula
												  else if($('#localitate').val() == "")
												  {
													  
													  $("#valoare").html(taxaCros + taxaVip + " "+'Lei');
												  }	
											
										}	
									
								}
							
						}
						// optiunea traseu este Crosul Copiilor
						else if(valSelect == "Crosul Copiilor")
						{
							
								$('#valoare').html(taxaCopii + " " + 'Lei');
								
								// optiunea vip este bifata
								if($('#vip').is(':checked')){
										
										$('#valoare').html(taxaCopii + taxaVip + " " + 'Lei');
										
										// optiunea curier este bifata
										if($('#curier').is(':checked')){
											
												  // optiunea localitate este selectata
												  if($('#localitate').val() == "Bucuresti"){
													  
													  $("#valoare").html(taxaCopii + taxaVip + taxaBuc + " "+'Lei');
													  
												  }
												  else if($('#localitate').val() == "Provincie"){
													  
													  $("#valoare").html(taxaCopii + taxaVip + taxaProv + " "+'Lei');
												  }
												  // optiunea localitate are valoarea nula
												  else if($('#localitate').val() == "")
												  {
													  
													  $("#valoare").html(taxaCopii + taxaVip + " "+'Lei');
												  }	
											
										}	
									
								}
							
						}
					
					
				 }
			
		
		// afiseaza valoarea taxei la competitia de maraton de la Comana
		function valoareTaxaCupaVeselieiRunFest(){
			
				var traseu = $("#traseu");				
				var valSelect = traseu.val();				
				// taxa trasee				
				var taxaMaraton = 70;
				var taxaSemimaraton = 50;
				var taxaCros = 50;
				var taxaCopii = 'Gratuit';
				
				
				// taxa vip
				var taxaVip = 35;				
				// taxa curier
				//var taxaBuc = 10;
				//var taxaProv = 18;
				
				
						// optiunea traseu nu este selectata		
						if(valSelect == ""){
							
								$('#valoare').html('Selectați un traseu');
							
						}
						
						// optiunea traseu este Maraton
						else if(valSelect == "Maraton")
						{
							
								$('#valoare').html(taxaMaraton + " " + 'Lei');
								
								// optiunea vip este bifata
								if($('#vip').is(':checked')){
										
										$('#valoare').html(taxaMaraton + taxaVip + " " + 'Lei');
										
										/******* inactiv *********
										// optiunea curier este bifata
										if($('#curier').is(':checked')){
											
												  // optiunea localitate este selectata
												  if($('#localitate').val() == "Bucuresti"){
													  
													  $("#valoare").html(taxaMaraton + taxaVip + taxaBuc + " "+'Lei');
													  
												  }
												  else if($('#localitate').val() == "Provincie"){
													  
													  $("#valoare").html(taxaMaraton + taxaVip + taxaProv + " "+'Lei');
												  }
												  // optiunea localitate are valoarea nula
												  else if($('#localitate').val() == "")
												  {
													  
													  $("#valoare").html(taxaMaraton + taxaVip + " "+'Lei');
												  }	
											
										}
										************************/	
									
								}
							
						}
						// optiunea traseu este Semimaraton
						else if(valSelect == "Semimaraton")
						{
								
								$('#valoare').html(taxaSemimaraton + " " + 'Lei');
								
								// optiunea vip este selectata
								if($('#vip').is(':checked')){
										
										$('#valoare').html(taxaSemimaraton + taxaVip + " " + 'Lei');
										
										
										/*********************8 inactiv *********************
										// optiunea curier este selectata
										if($('#curier').is(':checked')){
											
												  // optiunea localitate este selectata
												  if($('#localitate').val() == "Bucuresti"){
													  
													  $("#valoare").html(taxaSemimaraton + taxaVip + taxaBuc + " "+'Lei');
													  
												  }
												  else if($('#localitate').val() == "Provincie"){
													  
													  $("#valoare").html(taxaSemimaraton + taxaVip + taxaProv + " "+'Lei');
												  }
												  // optiunea localitate are valoarea nula
												  else if($('#localitate').val() == "")
												  {
													  
													  $("#valoare").html(taxaSemimaraton + taxaVip + " "+'Lei');
												  }	
											
										}
										***********************************/	
									
								}
							
						
						}
						
						// optiunea traseu este Cros
						else if(valSelect == "Cros")
						{
							
								$('#valoare').html(taxaCros + " " + 'Lei');
								// optiunea vip este selectata
								if($('#vip').is(':checked')){
										
										$('#valoare').html(taxaCros + taxaVip + " " + 'Lei');
										
										/********************** inactiv ***************************
										// optiunea curier este selectata
										if($('#curier').is(':checked')){
											
												  // optiunea localitate este selectata
												  if($('#localitate').val() == "Bucuresti"){
													  
													  $("#valoare").html(taxaCros + taxaVip + taxaBuc + " "+'Lei');
													  
												  }
												  else if($('#localitate').val() == "Provincie"){
													  
													  $("#valoare").html(taxaCros + taxaVip + taxaProv + " "+'Lei');
												  }
												  // optiunea localitate are valoarea nula
												  else if($('#localitate').val() == "")
												  {
													  
													  $("#valoare").html(taxaCros + taxaVip + " "+'Lei');
												  }	
											
										}
										*******************************************/	
									
								}
							
						}
						// optiunea traseu este Crosul Copiilor
						else if(valSelect == "Crosul Copiilor")
						{
							
								$('#valoare').html(taxaCopii);
								
								// optiunea vip este bifata
								if($('#vip').is(':checked')){
										
										$('#valoare').html(taxaVip + " " + 'Lei');
										
										
										/************************** inactiv *******************************
										// optiunea curier este bifata
										if($('#curier').is(':checked')){
											
												  // optiunea localitate este selectata
												  if($('#localitate').val() == "Bucuresti"){
													  
													  $("#valoare").html(taxaCopii + taxaVip + taxaBuc + " "+'Lei');
													  
												  }
												  else if($('#localitate').val() == "Provincie"){
													  
													  $("#valoare").html(taxaCopii + taxaVip + taxaProv + " "+'Lei');
												  }
												  // optiunea localitate are valoarea nula
												  else if($('#localitate').val() == "")
												  {
													  
													  $("#valoare").html(taxaCopii + taxaVip + " "+'Lei');
												  }	
											
										}	
									  ***************************************************/
								}
							
						}
					
					
				 }
			
		
			
			
		// afiseaza valoarea taxei la competitia de ski
		
		function valoareTaxaSki(){			
			
			var vip = $('#vip');
			var optiune = $('#categoria').val();
			var varsta = $('#varsta').val();				
			var limitDate = '2015/05/20';
			var taxaCopil = 30;
			var taxaCopilExtra = 5;	
			var taxaAdult = 60;
			var taxaAdultExtra = 10;
			var taxaVip = 35;
			// taxa curier
			var taxaBuc = 10;
			var taxaProv = 18;	
			
				if(new Date() < new Date(limitDate)){
					
						
					   // daca nicio optiune de varsta sau disciplina nu este selectata
						if(optiune === "" || varsta === ""){
							
							$('#valoare').html('Selectați o opțiune');
						}
						
						// daca optiunea disciplina are o selectie
						if((optiune != "") && (varsta === ""))
						{
							
							$('#valoare').html('Selectați grupa de vârsta');
						}
						// daca optiunea varsta are o selectie
						else if((varsta != "") && (optiune === ""))
						{
							
							$('#valoare').html('Selectați disciplina');
						}
						
						// daca cele doua selecturi varsta si disciplina sunt selectate
						if((optiune != "") && (varsta != "")){
							
								if(varsta == 'copil'){
									
										$('#valoare').html(taxaCopil+" "+'Lei');
										
										if(vip.is(':checked')){
											
											$('#valoare').html(taxaCopil + taxaVip +" "+'Lei');
											
											if($('#curier').is(':checked')){
													
													if($('#localitate').val() == "Bucuresti"){
														
														$('#valoare').html(taxaCopil + taxaVip + taxaBuc +" "+'Lei');	
													}
													else if($('#localitate').val() == "Provincie")
													{
														$('#valoare').html(taxaCopil + taxaVip + taxaProv +" "+'Lei');
														
													}
													else if($('#localitate').val() === "")
													{
														
														$('#valoare').html(taxaCopil + taxaVip +" "+'Lei');
													}
												
												
											} // curier
											
										} // vip
									
								}
								else if(varsta == 'adult')
								{
									
										$('#valoare').html(taxaAdult+" "+'Lei');
										
										if(vip.is(':checked')){
												
												$('#valoare').html(taxaAdult + taxaVip +" "+'Lei');							
													
														  
														  if($('#curier').is(':checked')){
																  
																  if($('#localitate').val() == "Bucuresti"){
																	  
																	  $('#valoare').html(taxaAdult + taxaVip + taxaBuc +" "+'Lei');	
																  }
																  else if($('#localitate').val() == "Provincie")
																  {
																	  $('#valoare').html(taxaAdult + taxaVip + taxaProv +" "+'Lei');
																	  
																  }
																  else if($('#localitate').val() === "")
																  {
																	  
																	  $('#valoare').html(taxaAdult + taxaVip +" "+'Lei');
																  }
															  
															  
														  }	// curier												  
																								
												
										} // vip
								} // varsta
							
						} // optiune && varsta diferite de null
					
				  }
				  
				  // daca limita de timp a fost atinsa preturile vor suferi o modificare de pret
				  else
				  {
					  	
						// daca nicio optiune de varsta sau disciplina nu este selectata
						if(optiune === "" || varsta === ""){
							
							$('#valoare').html('Selectați o opțiune');
						}
						
						// daca optiunea disciplina are o selectie
						if((optiune != "") && (varsta === ""))
						{
							
							$('#valoare').html('Selectați grupa de vârsta');
						}
						// daca optiunea varsta are o selectie
						else if((varsta != "") && (optiune === ""))
						{
							
							$('#valoare').html('Selectați disciplina');
						}
						
						
						// daca cele doua selecturi varsta si disciplina sunt selectate
						if((optiune != "") && (varsta != "")){
							
								if(varsta == 'copil'){
									
										$('#valoare').html(taxaCopil+ taxaCopilExtra +" "+'Lei');
										
										if(vip.is(':checked')){
											
											$('#valoare').html(taxaCopil + taxaCopilExtra + taxaVip +" "+'Lei');
											
											if($('#curier').is(':checked')){
													
													if($('#localitate').val() == "Bucuresti"){
														
														$('#valoare').html(taxaCopil + taxaCopilExtra + taxaVip + taxaBuc +" "+'Lei');	
													}
													else if($('#localitate').val() == "Provincie")
													{
														$('#valoare').html(taxaCopil + taxaCopilExtra + taxaVip + taxaProv +" "+'Lei');
														
													}
													else if($('#localitate').val() === "")
													{
														
														$('#valoare').html(taxaCopil + taxaCopilExtra + taxaVip +" "+'Lei');
													}
												
												
											} // curier
											
										} // vip
									
								}
								else if(varsta == 'adult')
								{
									
										$('#valoare').html(taxaAdult + taxaAdultExtra +" "+'Lei');
										
										if(vip.is(':checked')){
												
												$('#valoare').html(taxaAdult + taxaAdultExtra + taxaVip +" "+'Lei');							
													
														  
														  if($('#curier').is(':checked')){
																  
																  if($('#localitate').val() == "Bucuresti"){
																	  
																	  $('#valoare').html(taxaAdult + taxaAdultExtra + taxaVip + taxaBuc +" "+'Lei');	
																  }
																  else if($('#localitate').val() == "Provincie")
																  {
																	  $('#valoare').html(taxaAdult + taxaAdultExtra + taxaVip + taxaProv +" "+'Lei');
																	  
																  }
																  else if($('#localitate').val() === "")
																  {
																	  
																	  $('#valoare').html(taxaAdult + taxaAdultExtra + taxaVip +" "+'Lei');
																  }
															  
															  
														  }	// curier												  
																								
												
										} // vip
								} // varsta
							
						} // optiune && varsta diferite de null
						  
						  
				  }
			  
		  }
			
		
		// afiseaza valoarea taxei Ski/Indoor 
		function valoareTaxaSkiIndoor(){
			
			var vip = $('#vip');					
			
			var taxaVip = 30;
			// taxa curier
			var taxaBuc = 10;
			var taxaProv = 18;	
			
					if(vip.is(':checked')){		
							  
							  
									$('#valoare').html(taxaVip +" "+'Lei');
										
										if($('#curier').is(':checked')){
																			  
												if($('#localitate').val() == "Bucuresti"){
													
													$('#valoare').html(taxaVip + taxaBuc +" "+'Lei');	
												}
												else if($('#localitate').val() == "Provincie")
												{
													$('#valoare').html(taxaVip + taxaProv +" "+'Lei');
													
												}
												else if($('#localitate').val() === "")
												{
													
													$('#valoare').html(taxaVip +" "+'Lei');
												}
											
											
										}	// curier	
							  
					}
					else
					{
						
						$('#valoare').html('Taxa de intrare în complex');
						
					}
			
			
			
		}
		
		
		// afiseaza valoarea taxei Ski/Indoor 
		function valoareTaxaCatarat(){
			
			var vip = $('#vip');
			var varsta = $('#varsta').val();					
			
			var taxaVip = 35;
			// taxa curier
			var taxaBuc = 10;
			var taxaProv = 18;	
			
					if(varsta != ""){
						
						$('#valoare').html('Gratuit');
						
								if(vip.is(':checked')){						
							  
							  
										$('#valoare').html(taxaVip +" "+'Lei');
										
										if($('#curier').is(':checked')){
																			  
												if($('#localitate').val() == "Bucuresti"){
													
													$('#valoare').html(taxaVip + taxaBuc +" "+'Lei');	
												}
												else if($('#localitate').val() == "Provincie")
												{
													$('#valoare').html(taxaVip + taxaProv +" "+'Lei');
													
												}
												else if($('#localitate').val() === "")
												{
													
													$('#valoare').html(taxaVip +" "+'Lei');
												}
											
											
										}	// curier	
							  
								  }
								  else
								  {
									  
									 $('#valoare').html('Gratuit');
									  
								  }
			
						
						
					}
					else
					{
						
						$('#valoare').html('Alegeți categoria de vârstă');
						
					}
					
		}
		
		
		
		// valoare taxa Maraton On Top Run Fest
		
		function valoareTaxaSinaiaMaraton(){
			
				// varibile
				var vip = $('#vip');
				var traseu = $('#traseu').val();
				
				// taxa traseu
				var taxaTraseu = 50;
				var taxaTraseuMid = 60;
				var taxaTraseuLate = 70;
				
				// taxa curier
				var taxaBuc = 10;
				var taxaProv = 18;
				
				// data limita de la care pretul se schimba
				var limitDateMid = '2016/06/21';
				var limitDateLate = '2016/07/22';
				
				// taxa vip
				var taxaVip = 35;
				
				//today
				//var today = '2016/07/23';
				
				if(new Date() <= new Date(limitDateMid)){
					
						if(traseu != ""){
							
								$('#valoare').html(taxaTraseu +" "+'Lei');
								
								if(vip.is(':checked')){
									
									$('#valoare').html((taxaTraseu + taxaVip) +" "+'Lei');
										
										if($('#curier').is(':checked')){
																			  
												if($('#localitate').val() == "Bucuresti"){
													
													$('#valoare').html(taxaVip + taxaBuc + taxaTraseu + " "+'Lei');	
												}
												else if($('#localitate').val() == "Provincie")
												{
													$('#valoare').html(taxaVip + taxaProv + taxaTraseu + " "+'Lei');
													
												}
												else if($('#localitate').val() == "")
												{
													
													$('#valoare').html(taxaVip + taxaTraseu +" "+'Lei');
												}
											
											
										}	// curier	
								}
								// daca vip nu este selectat
								else
								{
									$('#valoare').html(taxaTraseu +" "+'Lei');
								}
							
								
						}
						// cand traseul nu este selectat
						else
						{
							
								$('#valoare').html('Selectați vă rog traseul');
						}
					
					
				}
				// cand limita de timp este intre intervale 
				else if (new Date() > new Date(limitDateMid) && new Date() <= new Date(limitDateLate))
				{
						if(traseu != ""){
							
								$('#valoare').html(taxaTraseuMid +" "+'Lei');
								
								if(vip.is(':checked')){
									
									$('#valoare').html((taxaTraseuMid + taxaVip) +" "+'Lei');
										
										if($('#curier').is(':checked')){
																			  
												if($('#localitate').val() == "Bucuresti"){
													
													$('#valoare').html(taxaVip + taxaBuc + taxaTraseuMid + " "+'Lei');	
												}
												else if($('#localitate').val() == "Provincie")
												{
													$('#valoare').html(taxaVip + taxaProv + taxaTraseuMid + " "+'Lei');
													
												}
												else if($('#localitate').val() == "")
												{
													
													$('#valoare').html(taxaVip + taxaTraseuMid + " "+'Lei');
												}
											
											
										}	// curier	
								}
								// daca vip nu este selectat
								else
								{
									$('#valoare').html(taxaTraseuMid +" "+'Lei');
								}
							
								
						}
						// cand traseul nu este selectat
						else
						{
							
								$('#valoare').html('Selectați vă rog traseul');
						}
					
					
				}
				// cand limita de timp depaseste ultimul interval 
				else if(new Date() > new Date(limitDateLate))
				{
					
						if(traseu != ""){
							
								$('#valoare').html(taxaTraseuLate +" "+'Lei');
								
								if(vip.is(':checked')){
									
									$('#valoare').html((taxaTraseuLate + taxaVip) +" "+'Lei');
										
										if($('#curier').is(':checked')){
																			  
												if($('#localitate').val() == "Bucuresti"){
													
													$('#valoare').html(taxaVip + taxaBuc + taxaTraseuLate + " "+'Lei');	
												}
												else if($('#localitate').val() == "Provincie")
												{
													$('#valoare').html(taxaVip + taxaProv + taxaTraseuLate + " "+'Lei');
													
												}
												else if($('#localitate').val() == "")
												{
													
													$('#valoare').html(taxaVip + taxaTraseuLate + " "+'Lei');
												}
											
											
										}	// curier	
								}
								// daca vip nu este selectat
								else
								{
									$('#valoare').html(taxaTraseuLate +" "+'Lei');
								}
							
								
						}
						// cand traseul nu este selectat
						else
						{
							
								$('#valoare').html('Selectați vă rog traseul');
						}
					
					
				}
			
			
		}
		
		
		
		function valoareTaxaMalinului(){
			
			var categoria = $('#traseu').val();
			var vip = $('#vip');
			
			// valoare taxa
			var ski = 50;
			
			// valoare pachet vip
			var taxaVip = 35;
			
			// taxa curier
			var taxaBuc = 10;
			var taxaProv = 18;
			
					if(categoria != ''){
						
						$('#valoare').html(ski + " " +'Lei');
						
						  if(vip.is(':checked')){
							 
							 $('#valoare').html((ski + taxaVip) + " " +'Lei');
							 
								 if($('#curier').is(':checked')){
																				  
											if($('#localitate').val() == "Bucuresti"){
												
												$('#valoare').html(taxaVip + taxaBuc + ski + " "+'Lei');	
											}
											else if($('#localitate').val() == "Provincie")
											{
												$('#valoare').html(taxaVip + taxaProv + ski + " "+'Lei');
												
											}
											else if($('#localitate').val() === "")
											{
												
												$('#valoare').html(taxaVip + ski + " "+'Lei');
											}
											
								 }
							  
						  }
						  
						  
					}
					else
					{
						
						$('#valoare').html('Vă rog să alegeți o categorie');
					}
			
		}
		
		
		// valoare taxa Duatlon
		
		function valoareTaxaDuatlon(){
			
			// variabile trasee
			var trBike = $('#traseu').val();
			var trRunning = $('#traseu_running').val();
			
			// valoare pachet vip
			var taxaVip = 35;
			
			// valoare insotitor
			var family = 10;
			
			// taxa curier
			var taxaBuc = 10;
			var taxaProv = 18;
			
			// valoare taxa duatlon
			var duatlon =80;
			
					if((trBike != '') && (trRunning != '')){					
						
							$('#valoare').html(duatlon + " " +'Lei');
							
							// ramura daca optiunea family este bifata
							if($('#insotitor').is(':checked')){
								
								
								$('#valoare').html(duatlon + family +" " +'Lei');
								
								if($('#vip').is(':checked')){
									
									$('#valoare').html(duatlon + family + taxaVip +" " +'Lei');
									
									// ramura curierului
									if($('#curier').is(':checked')){
																				  
											if($('#localitate').val() == "Bucuresti"){
												
												$('#valoare').html(duatlon + family + taxaVip + taxaBuc + " "+'Lei');	
											}
											else if($('#localitate').val() == "Provincie")
											{
												$('#valoare').html(duatlon + family + taxaVip + taxaProv + " "+'Lei');
												
											}
											else if($('#localitate').val() === "")
											{
												
												$('#valoare').html(duatlon + family + taxaVip + " "+'Lei');
											}
											
								 	}
								}
								
							}
							
							// ramura daca optiunea family nu este bifata
							else if($('#insotitor').not(':checked'))
							{
								
								$('#valoare').html(duatlon +" " +'Lei');
								
								if($('#vip').is(':checked')){
									
									$('#valoare').html(duatlon + taxaVip +" " +'Lei');
									
									if($('#curier').is(':checked')){
										
										
											if($('#localitate').val() == "Bucuresti"){
												
												$('#valoare').html(duatlon + taxaVip + taxaBuc + " "+'Lei');	
											}
											else if($('#localitate').val() == "Provincie")
											{
												$('#valoare').html(duatlon + taxaVip + taxaProv + " "+'Lei');
												
											}
											else if($('#localitate').val() === "")
											{
												
												$('#valoare').html(duatlon + taxaVip + " "+'Lei');
											}
										
										
										
									}
																				  
										
								}
							}
						
					}
					else if((trBike == '') && (trRunning != '')){
						
						$('#valoare').html('Selectați un traseu de bicicleta');
						
					}
					else if((trBike != '') && (trRunning == '')){
						
						$('#valoare').html('Selectați un traseu de alergare');
					}
					else
					{
			
						$('#valoare').html('Selectați o opțiune');
					}
		}
		
		
		// afiseaza valoarea taxei Cupa Tabere cu Suflet la Inot
		function valoareTaxaInot(){
			
			var vip = $('#vip');					
			
			var taxaVip = 30;
			// taxa curier
			var taxaBuc = 10;
			var taxaProv = 18;	
			
					if(vip.is(':checked')){		
							  
							  
									$('#valoare').html(taxaVip +" "+'Lei');
										
										if($('#curier').is(':checked')){
																			  
												if($('#localitate').val() == "Bucuresti"){
													
													$('#valoare').html(taxaVip + taxaBuc +" "+'Lei');	
												}
												else if($('#localitate').val() == "Provincie")
												{
													$('#valoare').html(taxaVip + taxaProv +" "+'Lei');
													
												}
												else if($('#localitate').val() === "")
												{
													
													$('#valoare').html(taxaVip +" "+'Lei');
												}
											
											
										}	// curier	
							  
					}
					else
					{
						
						$('#valoare').html('Taxa de intrare în complex');
						
					}
			
			}
		
		
		
		// afiseaza sau ascunde checkbox-ul insotitor
		function showVipCell(){
			
			var vipCell = $('.vipCell');
			var curierVip = $('.vipCurier');
			
			if(vipCell.is(':hidden')){
				
				// arata sectiunea vip
				vipCell.fadeToggle(300);
				}
				else
				{
				// ascunde sectiunea vip
				vipCell.fadeToggle(300, function(){
					
					$('#tricou').val('');
					$('#print_vip').val('');
					$('#adresaCurier').val('');
					$('#mailCurier').val('');
					$('#telCurier').val('');
				});
					
				
				
			}
				
			
			
		}
		
		//afiseaza sau ascunde modalitatea de plata pentru fiecare competitie
		function showModalitate(){
			
			var taxaCell = $('.taxaCell');
			
			if(taxaCell.is(':hidden')){
				
				taxaCell.fadeToggle(500);
				}
				else
				{
				taxaCell.fadeToggle(500, function(){
					
					$('#mod_plata').val('');
					$('#suma_plata').val('');
					$('#chit_plata').val('');
					});				
				
				}
			
			
			}
			
		
		// afiseaza sau ascunde checkbox-ul insotitor
		function showCurierVip(){
			
			var curier = $('.vipCurier');
			
			
			if(curier.is(':hidden')){
				
				curier.fadeToggle(300);
				}
				else
				{
				curier.fadeToggle(300, function(){
					
					$('#localitate').val('');
					$('#adresaCurier').val('');					
					
					});				
				
				}
				
			
			
			}
			
				
			
		// functia ce inactiveaza atributul required pentru campurile ce nu sunt vizibile
		function attribute(){
			var vip = $('#vip');
			var achitat = $('#achitat');			
			var curier = $('#curier');
				
				// informatii despre pachetul vip
				if(vip.is(':checked')){
					
					$('#tricou').attr('required', true);
					$('#adresaCurier').attr('required', true);
					$('#mailCurier').attr('required', true);
					$('#telCurier').attr('required', true);
					//$('#print_vip').attr('required', true);
					}
					else
					{
					$('#tricou').removeAttr('required');
					$('#adresaCurier').removeAttr('required');
					$('#mailCurier').removeAttr('required');
					$('#telCurier').removeAttr('required');
					//$('#print_vip').removeAttr('required');	
					}
					
				// informatiile despre achitarea platii	
				if(achitat.is(':checked')){
					
					$('#mod_plata').attr('required', true);
					$('#suma_plata').attr('required', true);
					$('#chit_plata').attr('required', true);
					}
				else
				{
					$('#mod_plata').removeAttr('required');
					$('#suma_plata').removeAttr('required');
					$('#chit_plata').removeAttr('required');
					
				}			
				
				/**********
				// informatiile despre curier
				if(curier.is(':checked')){
					
					$('#telCurier').attr('required', true);
					$('#mailCurier').attr('required', true);
					
					}
				else
				{
					$('#telCurier').removeAttr('required');
					$('#mailCurier').removeAttr('required');
					
					
				}
				
				*************/
							
			}
			
		