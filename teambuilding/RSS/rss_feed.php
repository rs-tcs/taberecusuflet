<?php
header('Content-type: text/xml; charset ="UTF-8"'); // tipul paginii

include('../setari/Database.php');

// se verifica daca variabila get exista
if(isset($_GET['ses'])){
	
	// decriptarea variabilei
	$session = base64_decode($_GET['ses']);
	
	// daca variabila are parametrii corespunzatori atunci este pornit feed-ul
	if($session == '5307d10e2e059'){
	
$db = new Database();

$rss = "<?xml version='1.0' encoding='UTF-8'?>"; // inceputul codului XML
$rss .= "<rss version='2.0'>"; // incepe tagul rss
$rss .= "<channel>"; // se deschide canalul
$rss .= "<title>Baza de date</title>"; // titlul feedului
$rss .= "<link>http:\\www.teamexpert.ro</link>"; //
$rss .= "<description>Export date inscrisi TeamXpert</description>"; // descrierea feedului
$rss .= "<language>Ro-ro</language>"; // limba folosita
$rss .= "<copyright>TeamXpert ".date('Y')."</copyright>"; // copyright

$select = "SELECT * FROM `inscrisi_teamexpert` WHERE 1";
$result = $db->execute($select);
while($row = $db->getObject($result)){
	$rss .= "<participant>"; // deschiderea unui inscris	
		$rss .= "<id>".$row['id']."</id>";
		$rss .= "<cod_unic>".$row['cod_unic']."</cod_unic>";
		$rss .= "<nume>".htmlentities($row['nume'], ENT_QUOTES, 'UTF-8')."</nume>";
		$rss .= "<prenume>".htmlentities($row['prenume'], ENT_QUOTES, 'UTF-8')."</prenume>";
		$rss .= "<sex>".$row['sex']."</sex>";
		$rss .= "<data_nasterii>".$row['data_nasterii']."</data_nasterii>";
		$rss .= "<varsta>".$row['varsta']."</varsta>";
		$rss .= "<categorie_varsta>".$row['categorie_varsta']."</categorie_varsta>";
		$rss .= "<telefon>".$row['telefon']."</telefon>";
		$rss .= "<email>".$row['email']."</email>";
		$rss .= "<activ>".$row['activ']."</activ>";
		$rss .= "<news_activ>".$row['news_activ']."</news_activ>";
		$rss .= "<oras>".htmlentities($row['oras'], ENT_QUOTES, 'UTF-8')."</oras>";
		$rss .= "<club>".htmlentities($row['club'], ENT_QUOTES, 'UTF-8')."</club>";
		$rss .= "<descriere>".htmlentities($row['descriere'], ENT_QUOTES, 'UTF-8')."</descriere>";
		$rss .= "<data_inscriere>".$row['data_inscriere']."</data_inscriere>";
		$rss .= "<ip>".$row['ip']."</ip>";
	$rss .= "</participant>"; // inchiderea unui inscris
	
}

$rss .= "</channel>"; // inchiderea canalului
$rss .= "</rss>"; // inchiderea feedului


echo $rss; // afisarea feedului

	}
	else
	{
		// cand parametri feed-ului nu sunt corecti eroare 404
		header('HTTP/1.0 404 Not found');
		
	}
}
else
{
	// cand nu este stabilit parametrul get se face redirectare catre pagina de login
	header('Location: https://www.teamexpert.ro/radu_admin');
	exit();
}

?>