<?php

require("includes/Database.php");
$db = new Database();
	
		$export = "SELECT cod_unic_participant,numele,prenumele,cat_varsta,traseu,insotitor,pachet_vip,tricou FROM `comana bike fest 2014` WHERE taxa = \"neachitata\" ORDER BY traseu asc
INTO OUTFILE 'public_html/salvari/comana bike fest 2014.csv'
FIELDS TERMINATED BY ','
ENCLOSED BY '\"'
LINES TERMINATED BY '\n' ";

		$result = $db->execute($export);
		
		if($result){
			
			echo "Tabelul a fost exportat";
			}
			else
			{
			echo "Am primit eroare";	
			}

?>