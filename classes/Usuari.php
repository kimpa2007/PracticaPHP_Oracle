<?php 
	include_once('../includes/funcions.php');
	include_once('Oci.php');

	class Usuari {
		
		protected $_usuari = "texte"; 
		protected $_contrasenya = false;
		protected $_infos;
		protected $_exceptions = array();
		protected $_ip = "";


		function __construct($usuari, $contrasenya) {
			$this->_usuari = $usuari->getValor();
			$this->_contrasenya = $contrasenya->getValor();
        		return $this;
		}

		/*
			Comprova si el usuari es pot autenticar contre la base de dades
		*/

		function comprovaAcces(){
			$user = $this->_usuari;
			$pass = $this->_contrasenya; 

			$oci = new Oci($user, $pass);
			$conectat = false;


			if($oci->getConnection() === null){
				$this->_exceptions[] = 'Error al establir la conexió';
			}
			
			else{
				$conectat = true;
				$_SESSION['user']['usuari'] = $this->_usuari;
				$_SESSION['user']['passwd'] = $this->_contrasenya;
			}
			return $conectat;

		}

		function mostrarErrors(){
			if(count($this->_exceptions > 0)){
				echo '<div class="alert alert-danger"><ul>';
				foreach($this->_exceptions as $error){
					echo '<li>'.$error.'</li>';
				}
				echo '</ul></div>';
			}
		}
	}
?>
