<?php
	$dir = dirname(__FILE__);

	include_once($dir.'/../../includes/funcions.php');
	include_once($dir.'/../Oci.php');

	class LlistaLloguer{
		protected $_llista = array();
		protected $_exceptions = array();
		
		//Si existeix llista -> retornar-la
		//Si no existeix la llista, per lo tan si està buida, fer la peticio SQL i després retornala

			function __construct() {
				return $this;
			}
			
			/*
				Retorna la llista del lloguers actuals, llogues no acabats
			*/
			function getLloguers(){
				if(count($this->_llista)){
					//echo "llista ok";
				}
				else{
					$this->consultarBdd();
				}
				//formatar surtida
				return $this->_llista;
			}

			private function consultarBdd(){
				$oci = new Oci($_SESSION['user']['usuari'], $_SESSION['user']['passwd']);
				$req = "SELECT  l.codi, 
						c.nom || ' ' ||  c.cognoms as CLIENT, 
						v.nom || ' ' || v.cognoms as VENEDOR, 
						ve.matricula,
						l.kmi, 
						l.datai
					FROM	
						lloguer l JOIN client c ON l.codi_client = c.codi 
		  					  JOIN venedor v ON l.codi_venedor = v.codi
							  JOIN vehicle ve ON l.codi_vehicle = ve.codi 
						WHERE dataf is null
					";
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
