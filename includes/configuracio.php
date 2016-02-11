<?php

	
	$emmagatzemarSessions="/u/alum/u1933576/public_html/tmp";
	ini_set('session.save_path',$emmagatzemarSessions);

	if(!(isset($_SESSION))){
		session_start();
		DEFINE('DEV','true');
			if(DEV){
				error_reporting(E_ALL);
				ini_set('display_errors', 1);
			}
	 }
?>
