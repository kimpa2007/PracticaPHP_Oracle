<?php
	$dir = dirname(__FILE__);

   	include_once($dir."/../../includes/configuracio.php");
   	include_once($dir."/../../includes/funcions.php");
	include_once($dir.'/../Oci.php');

	class LlistaAccesoris{
		protected $_llista = array();
		protected $_exceptions = array();
		protected $_model = null;
		
		//Si existeix llista -> retornar-la
		//Si no existeix la llista, per lo tan si està buida, fer la peticio SQL i després retornala

			function __construct() {
				return $this;
			}
			
			/*
				Retorna la llista d'accessoris que corresponen al model passat
			*/
			function getAccessorisByModel($model){
				$this->_model = $model;
				
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

				$req = 'SELECT a.descripcio as "DESC", codi_accessori as "CODI" FROM accessori a JOIN model_accessori ma ON a.codi = ma.codi_accessori WHERE codi_model=:cm';
				$stid = oci_parse($oci->getConnection(), $req);
				oci_bind_by_name($stid, ":cm", $this->_model);
					
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
