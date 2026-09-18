<?php

	

	if(isset($newsletter)){
		
		$select = "SELECT * FROM `sabloane_newsletter` WHERE `id` = '".$newsletter."'";
		
		$rezultat = $db->execute($select);
		
		$row = mysqli_fetch_assoc($rezultat);
		
		echo base64_decode($row['editor']);
		
		
		
		
		}
		else
		{
			
		}
?>