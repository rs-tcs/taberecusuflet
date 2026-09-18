<?php 
include('includes/Database.php');
$db = new Database();


$table = $_GET['numeTabel'];
$filename = tempnam(sys_get_temp_dir(), "csv");



$file = fopen($filename,"w");

// se scriu numele coloanelor
$result = $db->execute("SHOW COLUMNS FROM `".$table."`");
for ($i = 0; $i < $db->getCount($result); $i++) {
    $colArray[$i] = $db->getAssoc($result);
    $fieldArray[$i] = $colArray[$i]['Field'];
}
fputcsv($file,$fieldArray);


if(isset($_GET['traseu'])){
$traseu = $_GET['traseu'];
$result = $db->execute("SELECT * FROM `".$table."` WHERE `traseu` = '".$traseu."' AND `taxa` = 'achitata'");	
}
else if(isset($_GET['categoria']))
{
$categoria = $_GET['categoria'];
$result = $db->execute("SELECT * FROM `".$table."` WHERE `categoria` = '".$categoria."' AND `taxa` = 'achitata'");	
}
else if(isset($_GET['taxa'])){
$taxa = $_GET['taxa'];	
$result = $db->execute("SELECT * FROM `".$table."` WHERE `taxa` = '".$taxa."'");
}
else if(isset($_GET['traseu_bicicleta'])){
$traseu = $_GET['traseu_bicicleta'];
$result = $db->execute("SELECT * FROM `".$table."` WHERE `traseu_bicicleta` = '".$traseu."' AND `taxa` = 'achitata'");	
}
else if(isset($_GET['traseu_alergare'])){
$traseu = $_GET['traseu_alergare'];
$result = $db->execute("SELECT * FROM `".$table."` WHERE `traseu_alergare` = '".$traseu."' AND `taxa` = 'achitata'");
}
else
{
$result = $db->execute("SELECT * FROM `".$table."` WHERE `taxa` = 'neachitata'");	
}
// se scriu informatiile celulelor

if($db->getCount($result) >=1){
for ($i = 0; $i < $db->getCount($result); $i++) {
    $dataArray[$i] = $db->getAssoc($result);
	fputcsv($file,$dataArray[$i]);
}

fclose($file);

header("Content-Type: application/csv");
header("Content-Disposition: attachment;Filename=".$table.".csv");


// send file to browser
readfile($filename);
unlink($filename);

}
else
{
$pagina = $_SERVER['HTTP_REFERER'];	
$mesaj = base64_encode("Nu ai persoane cu taxă neachitată în acest tabel <br /> sau criteriul de selecție nu permite salvarea fisierului");
header('Location: '.$pagina."&eroare=".$mesaj);
exit();
}
?>