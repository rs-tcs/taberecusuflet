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




// se scriu informatiile celulelor
$result = $db->execute("SELECT * FROM `".$table."` WHERE 1");

if($db->getCount($result) >=1){
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

?>