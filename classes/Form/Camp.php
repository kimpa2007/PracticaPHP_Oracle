<?php
	class Camp{

		protected $_valor = "";
		protected $_tipus = "texte"; 
		protected $_valorsPossibles;
		protected $_restriccio = false;
		protected $_nom;
		protected $_errors = array();

		function __construct($nom) {
			if (is_string($nom)) 
			{
				$this->_nom = $nom;
        			return $this;
			}
		}
		
		public function setTipus($tipus, $arrayValorsPossibles=null){
			$this->_tipus = $tipus;
			$this->_valorsPossibles = $arrayValorsPossibles;
		}
				

		public function setValor($valor){
			$this->_valor = $valor;
		}

		public function setRestriccio($restriccio){
			$this->_restriccio = $restriccio;
		}


		public function getNom(){
			return $this->_nom;
		}
		
		public function getValor(){
			return  $this->_valor;
		}

		public function getTipus(){
			return  $this->_tipus;
		}

		public function getErrors(){
			return $this->_errors;
		}

		/*
			Serveix a validar si un camp te el tipus de valor que li toca
		*/
		public function valida($valor){
			$res = false;

			if ($this->_tipus == "cadena") {
				$res = true;
			}
			else if ($this->_tipus == "texte") {
				$res = $this->comprovar_text($valor);
			}
			else if ($this->_tipus == "numeric") {
				$res = $this->comprovar_numeric($valor);
			}
			else if ($this->_tipus == "email") {
				$res = $this->comprovar_email($valor);
			}
			else if ($this->_tipus == "data") {
				$res = $this->comprovar_data($valor);
			}
			else if ($this->_tipus == "matricula") {
				$res = $this->comprovar_matricula($valor);
			}
			else if($this->_tipus == "seleccio"){
				$res = $this->comprovar_seleccio($valor);
			}
			else if($this->_tipus == "array"){
				$res = $this->comprovar_array($valor);
			}
			return $res;
		}
		
		/*
			Per comprovar si es una cadena de text, composada per només lletres.
			Es passa la cadena, si es un camp obligatori i el nom del camp que serveix per indicar on es troba el error
		*/
		function comprovar_seleccio($valor){	
			$res = true;
			$valor = ucfirst($valor);		

			if($this->_restriccio){
				if (empty($valor)) {
				    $this->_errors[] = "El camp ".$this->_nom." es buit i es un camp obligatori.";
				    $res = false;			
				} 
			}

			if (!(in_array($valor,$this->_valorsPossibles))){
				$this->_errors[] = "El camp ".$this->_nom." no compté un valor permes.";
				$res = false;
			}
			return $res;
		}

	
		function comprovar_matricula($valor){
			$res = true;	

			if($this->_restriccio){
				if (empty($valor)) {
				    $this->_errors[] = "El camp ".$this->_nom." es buit i es un camp obligatori.";
				    $res = false;			

				} 
			}
			if(!(preg_match('/(^[a-zA-Z]+)(\d{4}+)([a-zA-Z]{2}$)/i', $valor)) and !(preg_match('/(\d{4}+)([a-zA-Z]{3}$)/i', $valor))){
				$this->_errors[] = "El valor del camp ".$this->_nom." es invàlid.";
				$res = false;		
			}



			return $res;
		}

		function comprovar_text($valor){
			$res = true;			

			if($this->_restriccio){
				if (empty($valor)) {
				    $this->_errors[] = "El camp ".$this->_nom." es buit i es un camp obligatori.";
				    $res = false;			

				} 
			}
			if (!is_string($valor)){
			 	 $this->_errors[] = "El camp ".$this->_nom. " ha de ser un text"; 
			 	$res = false;			
			}

			return $res;
		}

		/*  
			Per comprovar si el camp està composat només per numeros.
			Es passa la cadena, si es un camp obligatori i el nom del camp que serveix per indicar on es troba el error
		*/
		function comprovar_numeric($valor){
			$res = true;
	
			if($this->_restriccio){
				if (empty($valor)) {
 					$this->_errors[] = "El camp  <strong>".$this->_nom."</strong> es buit i es un camp obligatori.";
					$res = false;
				} 
				
			}
			if (!empty($valor) > 0 and !is_numeric($valor)){
			 	$this->_errors[] = "El camp <strong>".$this->_nom. "</strong> ha de ser un numero"; 
				$res = false;
			}
			return $res;
		}

		function comprovar_array($valor){
			$res = true;
	
			if($this->_restriccio){
				if (empty($valor)) {
 					$this->_errors[] = "El camp  <strong>".$this->_nom."</strong> es buit i es un camp obligatori.";
					$res = false;
				} 
				
			}
			if (!empty($valor) > 0 and !is_array($valor)){
			 	$this->_errors[] = "El camp <strong>".$this->_nom. "</strong> ha de ser un array"; 
				$res = false;
			}
			return $res;
		}


		/*  
			Per comprovar si el camp es un email.
			Es passa la cadena, si es un camp obligatori i el nom del camp que serveix per indicar on es troba el error.
		*/
		function comprovar_email($valor){
			$res = true;

			if($this->_restriccio){
				if (empty($valor)) {
					$this->_errors[] = "El camp <strong>" .$this->_nom."</strong> es buit i es un camp obligatori.";
					$res = false;
				} 
			}
			
			if (!filter_var($valor, FILTER_VALIDATE_EMAIL)) {
			 	$this->_errors[] = "El camp <strong>".$this->_nom. "</strong> es un correu invàlid"; 
				$res = false;
			}
			return $res;
		}


		/*  
			Per comprovar si el camp es una data.
			Són vàlids els formats dd/mm/yyyy i dd-mm-yyyy
			Es passa la cadena, si es un camp obligatori i el nom del camp que serveix per indicar on es troba el error
		*/
		function comprovar_data($valor){
			$res = true;

			if($this->_restriccio){
				if (empty($valor)) {
					$this->_errors[] = "El camp <strong>" .$this->_nom."</strong> es buit i es un camp obligatori.";
					$res = false;
				} 
			}
		
			
			/*
				Els usuaris entraran la data en format dd/mm/yyyy o dd-mm-yyyy pero la funcio checkdate de php les valida en mm/dd/yyyy per lo tan fem servir un explode per intercambiar-los.
			*/
			$separator = null;

			$separators_valids = array("/","-");
			if(strrpos($valor, "/")){
				$separator = "/";
			}
			else if(strrpos($valor, "-")){
				$separator = "-";
			}
			
			if($separator){
				$data_separada= explode($separator,$valor);
		    	$res = checkdate($data_separada[1],$data_separada[0],$data_separada[2]);
			}
			else {
				$res = false; ;
				$this->_errors[] = "La data entrada es invàlida.";
			}
			
			return $res;
		}
	}	
?>
