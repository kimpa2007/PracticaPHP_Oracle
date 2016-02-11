<?php
	$dir = dirname(__FILE__);
   	include_once($dir."/../includes/configuracio.php");
   	include_once($dir."/../includes/funcions.php");
   	include_once($dir."/Llistes/LlistaRevisio.php");
	include_once('Oci.php');
	include_once('Form/Camp.php');

	class Lloguer{
		private $_id = null;
		private $_infos = array();
		private $_exceptions = array();
		private $_accessoris = array();		

		function __construct($id = null) {
			$this->_id = $id;
		}

		function setInfos($infos){
			$this->_infos = $infos;
		}
		
		function setAccessoris($accessoris){
			$this->_accessoris = $accessoris;
		}

		/*
			Serveix a recuperar les dades del lloguer
		*/
		function obtenirDades(){
			$oci = new Oci($_SESSION['user']['usuari'], $_SESSION['user']['passwd']);
			$req = "SELECT * FROM lloguer WHERE codi = :codi";

			$stid = oci_parse($oci->getConnection(), $req);
			oci_bind_by_name($stid, ":codi", $this->_id);
			oci_execute($stid);

			if ($stid)
			{
				$result_array = array();
				while ($temp = oci_fetch_object($stid))
				{
					$result_array[] = $temp;
				}
			}
			oci_free_statement($stid);
			$oci->tancarConnexio();
			$this->_infos = $result_array;
			
			return $this->_infos;
		}

		/*
			Permet tancar un lloguer del qual s'hagi donat les dades necessàries.
			Al tancar-lo es comprova si cal passar a revisió.
		*/
		function tancarLloguer($dataf, $kmf, $retorn, $codi){
			$oci = new Oci($_SESSION['user']['usuari'], $_SESSION['user']['passwd']);
			$req = "UPDATE 
					lloguer 
				SET dataf = to_date(:dataf, 'DD-MM-YYYY HH24:MI:SS'), kmf =:kmf, retorn=:retorn 
				WHERE codi = :codi";
			$stid = oci_parse($oci->getConnection(), $req);
			oci_bind_by_name($stid, ":dataf", $dataf);
			oci_bind_by_name($stid, ":kmf", $kmf);
			oci_bind_by_name($stid, ":retorn", $retorn);
			oci_bind_by_name($stid, ":codi", $codi);
			$r = oci_execute($stid);
			oci_free_statement($stid);
			$oci->tancarConnexio();

			if(!$r){
				$this->_exception = "S'ha produit un error al tancar el lloguer";
				return false;
			}
			else{
				$oci = new Oci($_SESSION['user']['usuari'], $_SESSION['user']['passwd']);

				//Recuperar la informació del vehicle llogat
				$req = "SELECT codi_vehicle , v.combustible, l.kmi, l.kmf, l.retorn, ve.codi_delegacio FROM lloguer l JOIN vehicle v ON v.codi = l.codi_vehicle JOIN venedor ve ON ve.codi = l.codi_venedor WHERE l.codi = :codi";

				$stid = oci_parse($oci->getConnection(), $req);
				oci_bind_by_name($stid, ":codi", $codi);
				oci_execute($stid);
				$oci->tancarConnexio();
				$vehicle_llogat = oci_fetch_object($stid);
				
				//Es comprova si cal passa el vehicle a revisió, donan la seva informació
				$this->comprovarSiCalRevisio($vehicle_llogat);

				return true;
			}
		}

		/*
			Serveix a comprovar si el vehicle necessitarà passar una revisió.
			Si es el cas la crea.
		*/
		private function comprovarSiCalRevisio($vehicle_llogat){
			//Es comprova si el vehicle ja ha tingut alguna revisió
			$llista_revisio = new LlistaRevisio();
			$revisions_vehicle = $llista_revisio->getRevisioPerVehicle($vehicle_llogat->CODI_VEHICLE);

			include_once('Revisio.php');
			//Si esta a la taula de revisio =>
			if($revisions_vehicle)
			{
				echo "a la lista";
				//si retorn == c => cal revisio
				if($vehicle_llogat->RETORN == 'C')
				{
					$revisio = new Revisio($vehicle_llogat);
					if(!($revisio->inserirRevisio()) AND (DEV)){
						echo "S'ha produit un error inserint la revisio!!";
					}
				}

				//si combustible=gasolina i kmsrevisio + 5000 > kmf => revisio
				else if($vehicle_llogat->COMBUSTIBLE == 'Gasolina')
				{
					$dades_ultima_r = $llista_revisio->getUltimaRevisioVehicle($vehicle_llogat->CODI_VEHICLE);				
					if(($dades_ultima_r->KMS+5000) < $vehicle_llogat->KMF){
						$revisio = new Revisio($vehicle_llogat);
						if(!($revisio->inserirRevisio()) AND (DEV)){
							echo "S'ha produit un error inserint la revisio!!";
						}				
					}
				}
				//si combustible=diesel i kmsrevisio + 7500 > kmf => revisio
				else if($vehicle_llogat->COMBUSTIBLE == 'Diesel')
				{
					$dades_ultima_r = $llista_revisio->getUltimaRevisioVehicle($vehicle_llogat->CODI_VEHICLE);				
					if(($dades_ultima_r->KMS+7500) < $vehicle_llogat->KMF){
						$revisio = new Revisio($vehicle_llogat);
						if(!($revisio->inserirRevisio()) AND (DEV)){
							echo "S'ha produit un error inserint la revisio!!";
						}				
					}
				}
				//si combustible=electric i kmsrevisio + 10000 > kmf => revisio
				else if($vehicle_llogat->COMBUSTIBLE == 'Electric' or $vehicle_llogat->COMBUSTIBLE == 'El?ctric')
				{
					$dades_ultima_r = $llista_revisio->getUltimaRevisioVehicle($vehicle_llogat->CODI_VEHICLE);				
					if(($dades_ultima_r->KMS+10000) < $vehicle_llogat->KMF){
						$revisio = new Revisio($vehicle_llogat);
						if(!($revisio->inserirRevisio()) AND (DEV)){
							echo "S'ha produit un error inserint la revisio!!";
						}				
					}
					
				}

			}
			else
			{
				//Si no esta a la taula de revisio => se'n va a revisió
				$revisio = new Revisio($vehicle_llogat);
				if(!($revisio->inserirRevisio()) AND (DEV)){
					echo "S'ha produit un error inserint la revisio!!";
				}
			}
		}

		/*
			Es recupera el lloguer en curs pel vehicle del que es dona la matricula.
			Si tot es coherent només tindrem un lloguer en curs per vehicle.
		*/
		
		function getLloguerEnCursPerVehicle($matricula){

			if(isset($matricula)){
				$oci = new Oci($_SESSION['user']['usuari'], $_SESSION['user']['passwd']);

				//la matricula es d'un vehicle existent?
				$req = "SELECT codi FROM vehicle WHERE matricula LIKE :matricula";
				$stid = oci_parse($oci->getConnection(), $req);
				oci_bind_by_name($stid, ":matricula", $matricula);
				oci_execute($stid);
				$dades = oci_fetch_array($stid, OCI_ASSOC);
				
				if(isset($dades) and isset($dades['CODI'])){
					//retornar tots els lloguers del vehicle, lloguer que no tinguin data de retorn
					//si n'hi ha mes d'un, generar un error
					$codi_vehicle = $dades['CODI'];
					$req = "SELECT  
							l.codi AS CODI, 
							c.nom || ' ' ||  c.cognoms as CLIENT, 
							ve.nom || ' ' || ve.cognoms as VENEDOR, 
							m.nom || ' - ' || v.matricula  as VEHICLE, 
							l.kmi, 
							l.datai
						FROM lloguer l JOIN client c ON l.codi_client = c.codi 
							       JOIN venedor ve ON l.codi_venedor = ve.codi 
                        				       JOIN vehicle v ON l.codi_vehicle = v.codi
							       JOIN model m ON v.model_codi = m.codi
						WHERE l.codi_vehicle = :codivehicle";

					$stid = oci_parse($oci->getConnection(), $req);
					oci_bind_by_name($stid, ":codivehicle", $codi_vehicle);
					oci_execute($stid);
					
					$i = 0;
					if ($stid)
					{
						$result = "";
						while ($temp = oci_fetch_object($stid))
						{
							$i++;
							$result = $temp;
						}
					}

					oci_free_statement($stid);
					$oci->tancarConnexio();

					if($i == 1)	$result="";

					return $result;
				}
				else{
					$this->_exception[] = "El vehicle que es vol retornar no està llogat";
				}


			}
		}
		
		/*
			Funció per guardar les dades del objectes, per tant, per realitzar un insert a la base de dades
		*/
		function save(){
			$res = true;
			if(count($this->_infos)> 0)	
			{

				$oci = new Oci($_SESSION['user']['usuari'], $_SESSION['user']['passwd']);
				$req = "INSERT INTO lloguer";
				$camps ="(";
				$bindeados= "(";

				//Preparar el insert del lloguer, segons el tipus de dades. 
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
				/*
					Ja que necessitem el codi del lloguer per inserir els accesoris, cal que se'ns	 						el retorni. Això es fa amb el returning.
				*/
			
				$req .= $camps . " VALUES " . $bindeados. "returning codi into :lastInsertId";

				$stid = oci_parse($oci->getConnection(), $req);
				oci_bind_by_name($stid, ':lastInsertId', $lastInsertId, 8);
				oci_bind_by_name($stid, ":codi", $this->_id);

				foreach($this->_infos as $camp){
					if($camp->getTipus == "data"){
						oci_bind_by_name($stid, ':'.$camp->getNom(), $camp->getValor());
					}
					else{
						oci_bind_by_name($stid, ':'.$camp->getNom(), $camp->getValor());
					}
				}

				//Preparar la request de accessoris
				$ok = true;

				if(!(empty($this->_accessoris))){
					$r = oci_execute($stid, OCI_NO_AUTO_COMMIT);
					if($r){
						//mentres for i a validi ves inserint
						foreach($this->_accessoris as $a){
							$req2 = "INSERT INTO ACCESSORIS_LLOGATS(CODI_LLOGUER,CODI_ACCESSORI) VALUES (:lastInsertId, :codi_accessori)";
				
							$stid2 = oci_parse($oci->getConnection(), $req2);
							oci_bind_by_name($stid2, ':lastInsertId', $lastInsertId);
							oci_bind_by_name($stid2, ':codi_accessori', $a);
							$r2 = oci_execute($stid2);

							//Va comprovant si es pot anar fer els insert, a la que falla un, plega
							if(!$r2){
								$ok = false;
								break;
							}
						}
						//Si s'ha produit un error es fa un rollback
						if(!$ok){
							$oci->rollback();
							$this->_exception [] = "S'ha produit un error";
						}
						//Si tot ha aanat bé es fa un commit
						else{
							$oci->commit();
						}
					
					}

				}
				else{
					$r = oci_execute($stid);
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
