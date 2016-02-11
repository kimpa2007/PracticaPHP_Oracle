<?php 
 
	$dir = dirname(__FILE__);
   	include_once($dir."/../includes/configuracio.php");
   	include_once($dir."/../includes/funcions.php");
	
	class Oci {

		protected $_user = "";
		protected $_password = ""; 
		protected $_conn = "";
		protected $_exceptions = array();

		function __construct($user,$pass) {

			if (is_string($user) and is_string($pass))
			{
				$this->_user = $user;
				$this->_password = $pass;
			}
			return $this;
		}

		/*
			Realitzar la conexió amb la bdd
		*/

		function getConnection(){

			$this->_conn = oci_connect($this->_user, $this->_password, 'oracleps');

			if (!$this->_conn) {
				if(DEV){
					$e = oci_error();  // Para errores de oci_execute, pase el gestor de sentencia
				   	print '<div class="alert alert-danger">';
				   	print htmlentities($e['message']);
				    	print htmlentities($e['sqltext']);
				    	print  "\n</div>\n";
				}

				return null;
			}
			
			return $this->_conn;			  
		}
		
		/*
			Tancar la connexió
		*/
		function tancarConnexio(){
			oci_close($this->_conn);
		}

		function rollback(){
			oci_rollback($this->conn);
		}

		function commit(){
			oci_commit($this->conn);
		}
	}
?>
