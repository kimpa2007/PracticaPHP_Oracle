#!/usr/bin/php-cgi
<?php
	$dir = dirname(__FILE__);

   	include_once($dir."/../includes/configuracio.php");
   	include_once($dir."/../includes/funcions.php");
   	include_once($dir."/../classes/Form/Formulari.php");
   	include_once($dir."/../classes/Form/Camp.php");
   	include_once($dir."/../classes/Vehicle.php");

	
	if(!(comprova_auth())){
		header("Location: practica_php.php");
	}

	
	$form = new Formulari();
	$results = array('success' => false,'llista' => null, 'mess' => null);
	$accessoris = "";
	
	$codi = $form->crearElement("codi_vehicle");
	$codi->setTipus("numeric");
	$codi->setRestriccio(false);

	if(count($_POST)){
			if($form->valida($_POST)){
				$id= $codi->getValor();
				/*recuperar model del vehicle*/
				
				//$vehicle = new Vehicle();
				$vehicle = new Vehicle($id);
				$dades = $vehicle->obtenirDades();
				$model = $dades->MODEL_CODI;
				
				/* recuperar llista accessoris del model*/
				include_once($dir."/../classes/Llistes/LlistaAccesoris.php");
				$accessoris = new LlistaAccesoris();
				$llista = array();
				$i=0;
				foreach($accessoris->getAccessorisByModel($model) as $a){
					$llista[$i]['codi'] = $a->CODI;
					$llista[$i]['descripcio'] = $a->DESC;
					$i++;
				}
				$results['success'] = true;
				$results['llista'] = $llista;
	
			}
	}
	if(count($results)){
		header('content-type: application/json; charset=utf-8');
		echo json_encode($results);
	}
	
?>
