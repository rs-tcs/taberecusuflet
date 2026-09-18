<?php 
include('includes/Database.php');
$db = new Database();


$table = $_GET['numeTabel'];
$filename = tempnam(sys_get_temp_dir(), "csv");

$file = fopen($filename,"w");

$result = $db->execute("SELECT `cod_unic_participant`,`numele`,`prenumele`,`email`,`telefon` FROM `".$table."` WHERE 1");	

// se scriu informatiile celulelor

if(mysqli_num_rows($result) >=1){
for ($i = 0; $i < $db->getCount($result); $i++) {
    $dataArray[$i] = $db->getAssoc($result);
}
foreach ($dataArray as $line) {
    fputcsv($file,$line);
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
$mesaj = base64_encode("Nu ai concurenți în acest tabel");
header('Location: '.$pagina."&eroare=".$mesaj);
exit();
}
?>