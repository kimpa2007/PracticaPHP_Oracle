<?php
	include('Camp.php');

	class Formulari{

	 	protected $_camps = array();
	   	protected $erreurForm = array();
	   	protected $_exceptions = array();	

		/* Crea un nou camp en el formulari */
		public function crearElement($nom)
		{
			if(!empty($nom) AND is_string($nom)){
				$camp = new Camp($nom);
				$this->addElement($camp);
				return $camp;
			}
		}

		/* Guarda el nou camp en el array de camps del formulari */
		public function addElement($camp){
			$nom = $camp->getNom();
			$this->camps[$nom] = $camp;
		}

		/*  */
		public function getCamp($clau){
			return $this->camps[$clau] ;
		}

		/* Comprova si les dades rebudes corresponen en el tipus de dades que el formulari espera per cadascun dels camps 
			definits.
		*/

		public function valida($dades_rebudes){
			$valid = false;
			
			if(is_array($dades_rebudes) AND count($dades_rebudes)){
		
				foreach ($this->camps as $camp) {
					//Associar dades rebudes i camp
					if($camp->valida($dades_rebudes[$camp->getNom()])){
						$camp->setValor($dades_rebudes[$camp->getNom()]);
					}
					else{
						$this->_exceptions[] = $camp->getErrors();
					}
				}
				//Si no hi han errors, el formulari està vàlidat.
				if(count($this->_exceptions) <= 0){
					$valid = true;
				}
			
			}
			else{
				$this -> exceptions[] = "No s'han rebut dades";
			}
			return $valid;
		}

		/* Mostra els errors generats en el formulari */
		public function mostrarErrors(){
			if(!(empty($this->_exceptions))){
				echo '<div class="alert alert-danger"><ul>';
				foreach($this->_exceptions as $errors){
					foreach($errors as $error){
						echo '<li>'.$error.'</li>';
					}
				}
				echo '</ul></div>';
			}
		}
	}
?>
