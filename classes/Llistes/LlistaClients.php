<?php
	$dir = dirname(__FILE__);

	require_once($dir.'/../../includes/funcions.php');
	require_once($dir.'/../Oci.php');

	class LlistaClients{
		protected $_llista = array();
		protected $_exceptions = array();
		
		//Si existeix llista -> retornar-la
		//Si no existeix la llista, per lo tan si està buida, fer la peticio SQL i després retornala

			function __construct() {
				return $this;
			}
			
			/*
				Retorna una llista amb informació dels clients
			*/
			function getClients(){
				if(count($this->_llista)){
					//echo "llista ok";
				}
				else{
					$this->consultarBdd();
				}
				return $this->_llista;
			}

			private function consultarBdd(){
				$oci = new Oci($_SESSION['user']['usuari'], $_SESSION['user']['passwd']);
				$req = 'SELECT
						codi, nom, cognoms, dni
					FROM 
						client
					';

				$stid = oci_parse($oci->getConnection(), $req);
				oci_execute($stid);

				if ($stid)
				{
					$result_array = array();
					$i = 0;  
					while ($temp = oci_fetch_object($stid))
					{
						$result_array[$i] = $temp;
						$i++;				
					}
				}

				oci_free_statement($stid);
				$oci->tancarConnexio();	

				$this->_llista = $result_array;
			}
	}
?>
