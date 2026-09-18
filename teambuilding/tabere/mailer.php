<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="https://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-26750954-1']);
  _gaq.push(['_setDomainName', 'teamexpert.ro']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'https://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
<meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
<?php 

$date = date ("l, F jS, Y"); 
$time = date ("h:i A"); 
$to = "office@teamexpert.ro";
$subject = "Mesaj despre Tabere cu Suflet";
$headers = "From: www.taberecusufle.ro";
$forward = 0;
$location = "index.html";

$msg = "Below is the result of your feedback form. It was submitted on $date at $time.\n\n"; 

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    foreach ($_POST as $key => $value) { 
        $msg .= ucfirst ($key) ." : ". $value . "\n"; 
    }
}
else {
    foreach ($_GET as $key => $value) { 
        $msg .= ucfirst ($key) ." : ". $value . "\n"; 
    }
}

mail($to, $subject, $msg, $headers); 
if ($forward == 1) { 
    header ("Location:$location"); 
} 
else { 
    echo "Va multumim pentru mesaj. Va vom contacta cat mai curand posibil."; 
} 
	
	
	
	
	// reCaptcha info
		$secret = "6LcVyTwUAAAAACbaF3WPRG6jN17VgaF3N7bk9VV3";
		$remoteip = $_SERVER["REMOTE_ADDR"];
		$url = "https://www.google.com/recaptcha/api/siteverify";

		// Form info
		$response = $_POST["g-recaptcha-response"];

		// Curl Request
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, array(
			'secret' => $secret,
			'response' => $response,
			'remoteip' => $remoteip
			));
		$curlData = curl_exec($curl);
		curl_close($curl);

		// Parse data
		$recaptcha = json_decode($curlData, true);
		if ($recaptcha["success"])
			echo "Va multumim pentru mesaj. Va vom contacta in cel mai scurt timp posibil.\n\nEchipa Tabere cu Suflet";
		else
			echo "Din pacate nu ati trecut testul reCaptcha, iar mesajul dvs nu a putut fi transmis! \nVa rugam sa ne contactati pe adresa de email. \n\nVa multumim pentru intelegere!";


?>
</body>
</html>