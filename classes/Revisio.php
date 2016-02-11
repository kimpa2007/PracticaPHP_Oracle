<?php
	$dir = dirname(__FILE__);
   	include_once($dir."/../includes/configuracio.php");
   	include_once($dir."/../includes/funcions.php");
	include_once('Oci.php');
	include_once('Form/Camp.php');

	class Revisio{
		private $_id = null;
		private $_infos = array();
		private $_exceptions = array();
		private $_accessoris = array();		

		function __construct($infos) {
			$this->_infos = $infos;
		}


		function inserirRevisio(){
			//Buscar el venedor mes jove de la delegacio
			$codi_venedor = $this->buscarMesJove();

			//Inserir revisio
			$oci = new Oci($_SESSION['user']['usuari'], $_SESSION['user']['passwd']);
			$req = "INSERT INTO revisio (CODI,CODI_VEHICLE, DATA_L, KMS, CODI_VENEDOR) values (null, :codi_vehicle, to_date(SYSDATE, 'DD-MM-YYYY HH24:MI:SS'), :kms, :codi_venedor )";

			$stid = oci_parse($oci->getConnection(), $req);
			oci_bind_by_name($stid, ":codi_vehicle", $this->_infos->CODI_VEHICLE);
			oci_bind_by_name($stid, ":kms", $this->_infos->KMF);
			oci_bind_by_name($stid, ":codi_venedor", $codi_venedor);
			$r = oci_execute($stid);
			oci_free_statement($stid);
			$oci->tancarConnexio();

			if($r){
				return true;
			}
			else{
				return false;
			}
		}


		/*		
			Serveix per obtenir el codi del venedor més jove del vehicle on s'ha realitzat el lloguer
		*/
		private function buscarMesJove(){
			$codi_delegacio = $this->_infos->CODI_DELEGACIO;
			
			$oci = new Oci($_SESSION['user']['usuari'], $_SESSION['user']['passwd']);
			$req = "SELECT codi_delegacio, min(data_alta), max(codi) as CODI FROM venedor WHERE codi_delegacio = :codi_delegacio group by codi_delegacio";
			$stid = oci_parse($oci->getConnection(), $req);
			oci_bind_by_name($stid, ":codi_delegacio", $codi_delegacio);
			oci_execute($stid);
			$jove = oci_fetch_object($stid);
			return $jove->CODI;
		}

	}

?>
