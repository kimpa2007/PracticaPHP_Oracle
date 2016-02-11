<?php

	//include('includes/funcions.php');
	include_once('Oci.php');
	include_once('Form/Camp.php');

	class Model{
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
			Serveix per recuperar les dades del model
		*/
		function obtenirDades(){
			if(empty($this->_infos)){
				$oci = new Oci($_SESSION['user']['usuari'], $_SESSION['user']['passwd']);
				$req = "SELECT * FROM models WHERE codi = :codi";

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
				oci_close($oci);

				$this->_infos = $result_array;
			}
			return $this->_infos;
		}
		
		/*
			Per fer un insert de l'informació del model.
		*/
		function save(){
			$res = true;
			//realitzar insert
			//foreach $infos as $info
			if(count($this->_infos)> 0)	
			{

				$oci = new Oci($_SESSION['user']['usuari'], $_SESSION['user']['passwd']);
				$req = "INSERT INTO model";
				$camps ="(";
				$bindeados= "(";


				foreach($this->_infos as $camp){
					$camps .= $camp->getNom() . ",";
					$bindeados .= ":".$camp->getNom() . ",";

				}
				$camps .= "codi)";
				$bindeados .= ":codi)";
				$req .= $camps . " VALUES " . $bindeados;

				$stid = oci_parse($oci->getConnection(), $req);

				oci_bind_by_name($stid, ":codi", $this->_id);

				foreach($this->_infos as $camp){
					oci_bind_by_name($stid, ':'.$camp->getNom(), $camp->getValor());
				}
				$r = oci_execute($stid);
				
				if (!$r) {
					if(DEV){
					    $e = oci_error($stid);  
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
	}	
?>
