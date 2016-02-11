<?php
	$dir = dirname(__FILE__);

	include_once($dir.'/../../includes/funcions.php');
	include_once($dir.'/../Oci.php');

	class LlistaVehicle{
		protected $_exceptions = array();

			function __construct() {
				return $this;
			}

			/*
				Retorna la llista de tots els vehicles que tenim, amb infomacio de lloguer
			*/
			function getTotVehicles(){
				$oci = new Oci($_SESSION['user']['usuari'], $_SESSION['user']['passwd']);
				$req = 'SELECT
						v.codi, 
						v.matricula, 
						v.color, 
						v.combustible, 
						m.nom as MODEL, 
						max(l.datai) as "DATAI", 
						max(l.dataf) as "DATAF"
					FROM 
						vehicle v JOIN model m ON v.model_codi = m.codi
						LEFT JOIN lloguer l ON l.codi_vehicle = v.codi	
					WHERE 
						l.codi IN (SELECT max(codi) FROM lloguer GROUP BY codi_vehicle)

					GROUP BY
						v.codi, v.matricula, v.color, v.combustible, m.nom
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
				Retorna tots els vehicles no llogats
			*/
			function getVehiclesDisponibles(){
				$oci = new Oci($_SESSION['user']['usuari'], $_SESSION['user']['passwd']);
				$req = "SELECT 
						v.codi, 
						v.matricula, 
						v.color, 
						v.combustible,
						m.nom as MODEL 
					FROM vehicle v JOIN model m ON v.model_codi = m.codi
					WHERE v.codi NOT IN (
						SELECT codi_vehicle 
						FROM lloguer WHERE dataf is null) 
					GROUP BY v.codi, v.matricula, v.color, v.combustible, m.nom";

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
	}
?>
