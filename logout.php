#!/usr/bin/php-cgi
<?php
	include_once('includes/configuracio.php');

	if(isset($_SESSION)){
		session_unset(); 
 		session_destroy(); 
	}

	header("location: practica_php.php");
	exit;	
?>
