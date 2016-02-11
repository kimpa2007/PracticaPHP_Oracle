<?php
	$dir = dirname(__FILE__);

	include_once($dir.'/../../includes/funcions.php');
	include_once($dir.'/../Oci.php');

	class LlistaRevisio{
		protected $_exceptions = array();

			function __construct() {
				return $this;
			}

			/*
				Retorna una llista amb totes les revisions
			*/
			function getRevisions(){
				$oci = new Oci($_SESSION['user']['usuari'], $_SESSION['user']['passwd']);
				$req = 'SELECT
						*
					FROM 
						revisio
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

				return $result_array;
			}

			/*
				Retorna totes les revisions que correpsponen a un vehicle segons el seu codi
			*/
			function getRevisioPerVehicle($codiVehicle){
				$oci = new Oci($_SESSION['user']['usuari'], $_SESSION['user']['passwd']);

				$req = 'SELECT r.codi, r.codi_vehicle, r.data_l, r.kms, ven.nom, ven.cognoms FROM revisio r JOIN venedor ven ON r.codi_venedor = ven.codi WHERE codi_vehicle=:codiVehicle ORDER BY r.codi';
				$stid = oci_parse($oci->getConnection(), $req);
				oci_bind_by_name($stid, ":codiVehicle", $codiVehicle);
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

				return $result_array;

			}

			/*
				Retorna la ultima revisio d'un vehicle
			*/
			function getUltimaRevisioVehicle($codi_vehicle){

				$oci = new Oci($_SESSION['user']['usuari'], $_SESSION['user']['passwd']);
				$req = "SELECT 
						data_l, 
						kms 
					FROM 
						revisio 
					WHERE codi IN ( 
						select max(rev.codi) 
						from revisio rev 
						where rev.codi_vehicle = :codi_vehicle 
						group by rev.codi_vehicle 
					)";
				$stid = oci_parse($oci->getConnection(), $req);
				oci_bind_by_name($stid, ":codi_vehicle", $codi_vehicle);
				oci_execute($stid);
				$ultima = oci_fetch_object($stid);
				return $ultima;
			}
	}
?>
