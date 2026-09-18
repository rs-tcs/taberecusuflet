<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "https://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="https://www.w3.org/1999/xhtml" xml:lang="en">
<meta http-equiv="content-type" content="text/html;charset=utf-8">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>TeamXpert® | Contact</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link type="text/css" rel="stylesheet" media="all" href="css/mainStyle.css" />
    
<!-- <script type="text/javascript" src="js/siteNavigation.js"></script> -->
<script type="text/javascript" src="js/jquery.v1.3.2.js"></script>

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
</head>
<body class="page-products-with-gorilla section-product-landing">
    <!-- Layout -->
    <div class="container" id="section-products-with-gorilla"> <!-- add "showgrid" class to display grid -->
    <div class="page-products-with-gorilla section-product-landing" id="access-rules"> 
  
    	<div id="header" class="clearfix">
        	<a href="/" title="TeamExpert®">
            	<img src="images/logoTeamexpert.png" alt="Teamexpert" id="logo" /></a><div class="social">
          <span>Urmariti-ne pe:</span>
          <a href="https://www.facebook.com/teamexpert.ro" class="fb sn">Facebook</a> 
          <a href="https://twitter.com/radusavin" class="twitter sn">Twitter</a>
          <a href="https://teamexpert1.wordpress.com/" class="wordpress sn">WordPress</a>  
          
        </div>
                
        <ul class="links primary-links">
          <li class="menu-680"><a href="/index.html" title="Prima Pagina">Home</a></li>
          <li class="menu-1647"><a href="/servicii.html" 
          	title="Servicii Teamexpert">Servicii</a></li>
          <li class="menu-688"><a href="/evenimente.html" title="Evenimente">Evenimente</a></li>
          <li class="menu-689 last"><a href="/clienti-fericiti.html" title="Clienti fericiti">Clienti fericiti</a></li>
          <li class="menu-1392"><a href="/noi.html" title="Despre noi">Noi</a></li>
          <li class="menu-1387"><a href="/galerie.html" title="Galerie">Galerie</a></li>
          <li class="menu-775 active-trail first active">
          <a href="/contact.html" title="Contacteaza-ne" class="active">Contact</a></li>
          <li class="menu-222"><a href="/contact-us.html" title="English version" ><img src="/images/Eng.png" alt="Eng" /></a></li>
	     </ul>        
      <div class="clear"></div>
      </div> <!-- /#header -->
      <div class="clear"></div>


<div id="inner" class="node-type-product_landing">

	<div id="main">
		<div id="contact-header">
			<div class="contact-address">
         TeamExpert Europe SRL<br />
          Bucuresti, str. Pictor Obedeanu 18, sector 2<br />
                Tel: 0755 822 373 | 0723 131 543<br />
                Email: <a href="mailto:office@teamexpert.ro">office@teamexpert.ro</a>  
  			</div>
			
            <h1>Contact</h1>  
            <p>&nbsp;</p>

			<div class="clear"></div>
		</div> <!-- /#contact-header -->
        
		<div id="contact-body">
			<p>&nbsp;</p>
            
                <div align="left" style="width: 500px;">
                
                <fieldset class="info_fieldset"><legend>Informatiile dvs.</legend>
                
                <div id="note">
<?php
define("WEBMASTER_EMAIL", 'radu@teamexpert.ro');
// error_reporting (E_ALL ^ E_NOTICE);
$post = (!empty($_POST)) ? true : false;
if($post) {
	function ValidateEmail($email) {
/*
(Name) Letters, Numbers, Dots, Hyphens and Underscores
(@ sign)
(Domain) (with possible subdomain(s) ).
Contains only letters, numbers, dots and hyphens (up to 255 characters)
(. sign)
(Extension) Letters only (up to 10 (can be increased in the future) characters)
*/

$regex = '/([a-z0-9_.-]+)'. # name

'@'. # at

'([a-z0-9.-]+){2,255}'. # domain & possibly subdomains

'.'. # period

'([a-z]+){2,10}/i'; # domain extension 

if($email == '') { 
	return false;
}
else {
$eregi = preg_replace($regex, '', $email);
}

return empty($eregi) ? true : false;
}
	$name = stripslashes($_POST['name']);
	$email = trim($_POST['email']);
	$subject = stripslashes($_POST['subject']);
	$message = stripslashes($_POST['message']);
	$error = '';

	// Check name
	if(!$name) {
		$error .= 'Va rugam introduceti numele dvs.<br />';
		}
	// Check email
	if(!$email) {
		$error .= 'Va rugam introduceti adresa dvs de email.<br />';
		}
	if($email && !ValidateEmail($email)) {
		$error .= 'Va rugam introduceti o adresa valida de email.<br />';
		}
	// Check message (length)
	if(!$message || strlen($message) < 10) {
		$error .= "Va rugam introduceti un mesaj de minim 10 caractere.<br />";
		}

	if(!$error) {
		$mail = mail(WEBMASTER_EMAIL, $subject, $message,
			 "From: ".$name." <".$email.">\r\n"
			."Reply-To: ".$email."\r\n"
			."X-Mailer: PHP/" . phpversion());

		if($mail) {echo 'Va multumim ca ne-ati contactat si vom reveni cu un raspuns cat mai curand posibil.';}
		} else {echo '<div class="notification_error">'.$error.'</div>';}
}
?>                </div>
                <div id="fields">
                
                <?php session_start();
if(isset($_POST['Submit'])) {
$youremail = 'office@teamexpert.ro';
$fromsubject = '';
$fname = $_POST['fname'];
$mail = $_POST['mail'];
$phone = $_POST['phone']; 
$subject = $_POST['subject']; 
$message = $_POST['message']; 
	$to = $youremail; 
	$mailsubject = 'Masage recived from'.$fromsubject.' Contact Page';
	$body = $fromsubject.'
	
	The person that contacted you is  '.$fname.'
	 Phone Number: '.$phone.'
	 E-mail: '.$mail.'
	 Subject: '.$subject.'
	
	 Message: 
	 '.$message.'
	
	|---------END MESSAGE----------|'; 
echo "Va multumim pentru mesaj. Vom reveni cu un raspuns cat mai curand posibil."; 
								mail($to, $subject, $body);
 } else { 
echo "You must write a message. </br> Please go to <a href='/contact.html'>Contact Page</a>"; 
}
?> 
                </div>
                
                </fieldset>
                
                </div>
                
		</div> <!-- /#contact-body -->
        <div id="raspuns-body"></div>
	</div> <!-- /#main -->      
	<div id="footer" class="clear">
		<div id="block-menu-secondary-links" class="block block-menu">

		<div class="content">
          <ul class="menu"><li class="leaf first even"><a href="https://teamexpert1.wordpress.com/" target="_blank" title="Blog">Blog</a></li>
                                <li class="leaf odd"><a href="/contact.html" title="Contacteaza-ne" class="contact-link">Contacteaza-ne</a></li>
            <li class="leaf last odd"><a href="termeni.html" title="Termeni si Conditii">Termeni si conditii</a></li>
          </ul>
		</div>
		</div>
		<div class="copyright">&copy; 2002 - 2025 Teamexpert Europe SRL. Toate drepturile rezervate</div>
	</div> <!-- /#footer -->
</div><!-- end inner -->
</div> <!-- /.container -->
<!-- /layout -->


</body>
</html>
