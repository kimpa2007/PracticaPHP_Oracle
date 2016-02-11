<?php
	$dir = dirname(__FILE__);
   	include_once($dir."/../includes/configuracio.php");
   	include_once($dir."/../includes/funcions.php");
	include_once('Oci.php');
	include_once('Form/Camp.php');

	class Vehicle{
		private $_id = null;
		private $_infos = array();
		private $_exceptions = array();
		
		function __construct($id = null) {
			$this->_id = $id;
		}

		function setInfos($infos){
			$this->_infos = $infos;
		}

		/*
			Recuperar les dades del vehicle
		*/
		function obtenirDades(){
			$oci = new Oci($_SESSION['user']['usuari'], $_SESSION['user']['passwd']);
			$req = "SELECT * FROM vehicle WHERE codi = :codi";

			$stid = oci_parse($oci->getConnection(), $req);
			oci_bind_by_name($stid, ":codi", $this->_id);
			oci_execute($stid);

			if ($stid)
			{
				$result_array = array();
				while ($temp = oci_fetch_object($stid))
				{
					$result = $temp;
				}
			}
			oci_free_statement($stid);
			$oci->tancarConnexio();
			$this->_infos = $result;	

			return $this->_infos;
		}
		
		/*		
			Retorna si un vehicle esta llogat o no
		*/
		function estaLlogat(){

			// si al lloguer de un vehicle no té data de fi es que el lloguer no s'ha acabat.
			// Per lo tan el vehicle està llogat
			$oci = new Oci($_SESSION['user']['usuari'], $_SESSION['user']['passwd']);
			$req = "select codi_vehicle from lloguer where dataf is null";
			$stid = oci_parse($oci->getConnection(), $req);
			oci_execute($stid);

			$alquilats = array();
			if ($stid)
			{
				while ($temp = oci_fetch_object($stid))
				{
					$alquilats[] = $temp->CODI_VEHICLE;
				}
			}
			oci_free_statement($stid);
			$oci->tancarConnexio();
			return in_array($this->_id, $alquilats);

		}

		/*	
			Dona el codi d'un vehicle segons la seva matricula
		*/
		function getVehicleByMatricula($matricula){
			$oci = new Oci($_SESSION['user']['usuari'], $_SESSION['user']['passwd']);
			$req = "select codi from vehicle where matricula = :matricula";
			$stid = oci_parse($oci->getConnection(), $req);
			oci_bind_by_name($stid, ":matricula", $matricula);
			oci_execute($stid);
			$tot = oci_fetch_object($stid);
			$this->_id = $tot->CODI;

			oci_free_statement($stid);
			$oci->tancarConnexio();

			$this->obtenirDades();
			return $this->_infos->CODI;
			
		}


		/*
			Retorna quants kms li falta a un vehicle abans de passar la revisio
		*/
		function getKmsRevisio(){
			$oci = new	 Oci($_SESSION['user']['usuari'], $_SESSION['user']['passwd']);

			//Recuperar kms en la ultima revisio
			$req = "SELECT min(data_l), max(kms) as KMS FROM revisio WHERE codi_vehicle = :codi_vehicle GROUP BY codi_vehicle";
			$stid = oci_parse($oci->getConnection(), $req);
			oci_bind_by_name($stid, ":codi_vehicle", $this->_id);
			oci_execute($stid);
			$ultima = oci_fetch_object($stid);

			//Recuperar el total de kms al ultim retorn del vehicle
			$req = "SELECT min(dataf), max(kmf) as KMS FROM lloguer WHERE codi_vehicle = :codi_vehicle GROUP BY codi_vehicle";
			$stid = oci_parse($oci->getConnection(), $req);
			oci_bind_by_name($stid, ":codi_vehicle", $this->_id);
			oci_execute($stid);

			$retorn = oci_fetch_object($stid);

			$this->obtenirDades();

			if($ultima and $retorn)
			{
				if($this->_infos->COMBUSTIBLE == 'Gasolina'){
					$kmsDeSeguentRevisio = $ultima->KMS + 5000;
				}
				elseif($this->_infos->COMBUSTIBLE == 'Diesel'){
					$kmsDeSeguentRevisio = $ultima->KMS + 7500;
				}
				elseif($this->_infos->COMBUSTIBLE == 'Electric' or $this->_infos->COMBUSTIBLE == 'El?ctric'){
					$kmsDeSeguentRevisio = $ultima->KMS + 10000;
				}
				$kms = "Falta " .abs($kmsDeSeguentRevisio - $retorn->KMS) . " kms";
			}
			else
			{
				$kms = "Encara no tenim dades disponibles";
			}

			
			return $kms;
		}


		function save(){
			$res = true;
			//realitzar insert
			//foreach $infos as $info
			if(count($this->_infos)> 0)	
			{

				$oci = new Oci($_SESSION['user']['usuari'], $_SESSION['user']['passwd']);
				$req = "INSERT INTO vehicle";
				$camps ="(";
				$bindeados= "(";


				foreach($this->_infos as $camp){
					$camps .= $camp->getNom() . ",";

					if($camp->getTipus() == "data"){
						$bindeados .= "to_date(:".$camp->getNom().", 'DD-MM-YYYY HH24:MI:SS'),";
					}
					else{
						$bindeados .= ":".$camp->getNom() . ",";
					}

				}
				$camps .= "codi)";
				$bindeados .= ":codi)";
				$req .= $camps . " VALUES " . $bindeados;

				$stid = oci_parse($oci->getConnection(), $req);

				oci_bind_by_name($stid, ":codi", $this->_id);

				foreach($this->_infos as $camp){
					if($camp->getTipus == "data"){
						oci_bind_by_name($stid, ':'.$camp->getNom(), $camp->getValor());
					}
					else{
						oci_bind_by_name($stid, ':'.$camp->getNom(), $camp->getValor());
					}
				}
				$r = oci_execute($stid);
				
				if (!$r) {
					if(DEV){
					    $e = oci_error($stid);  // Para errores de oci_execute, pase el gestor de sentencia
					    print htmlentities($e['message']);
					    print "\n<pre>\n";
					    print htmlentities($e['sqltext']);
					    printf("\n%".($e['offset']+1)."s", "^");
					    print  "\n</pre>\n";
					}
					else{
						$this->_exceptions[] = "No s'ha pogut inserir el nou vehicle";
					}
					$res = false;
				}
			
				oci_free_statement($stid);
				$oci->tancarConnexio();
			}
			return $res;
		}

		function mostrarErrors(){
			if(!(empty($this->_exception))){
				echo '<div class="alert alert-danger"><ul>';
				foreach($this->_exceptions as $error){
					echo '<li>'.$error.'</li>';
				}
				echo '</ul></div>';
			}
		}

	}	
?>
