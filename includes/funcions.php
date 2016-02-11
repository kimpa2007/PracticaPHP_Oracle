<?php
	include_once('configuracio.php');
	function comprova_auth(){

		if((isset($_SESSION['user']['usuari']))){
   			return true;
 		}
		return false;
	}
?>
