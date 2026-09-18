<?php
				ob_start();
				session_start();
				if(!$_SESSION['username']){
		
				header("Location: index.php");
				}
				else
				{
				$user = $_SESSION['username'];
				}
								
				unset($_SESSION['username']);
				unset($_SESSION['uniq_id']);
				header("Location: index.php");
				exit();
				ob_flush();

?>