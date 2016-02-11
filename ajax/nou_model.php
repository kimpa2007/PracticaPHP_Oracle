#!/usr/bin/php-cgi
<?php
	$dir = dirname(__FILE__);
   	include_once($dir."/../classes/Form/Formulari.php");
	include_once($dir.'/../includes/funcions.php');
	include_once($dir.'/../classes/Form/Camp.php');
	include_once($dir.'/../classes/Model.php');


	if(!(comprova_auth())){
		header("Location: practica_php.php");
	}


	$form = new Formulari();
	$results = array('success' => false,'datas' => null, 'mess' => null);

	$nom = $form->crearElement("nom");
	$nom->setTipus("texte");
	$nom->setRestriccio(true);

	$grup = $form->crearElement("grup");
	$grup->setTipus("texte");
	$grup->setRestriccio(true);

	$tarifa_dia = $form->crearElement("tarifa_dia");
	$tarifa_dia->setTipus("numeric");
	$tarifa_dia->setRestriccio(false);

	if(count($_POST)){
			if($form->valida($_POST)){
				$infos = array();
				array_push($infos, $grup, $tarifa_dia, $nom);
				$model = new Model();
				$model->setInfos($infos);

				if($model->save()){
					include_once($dir."/../classes/Llistes/LlistaModels.php");
					$models = new LlistaModels();
					$llista = array();
					$i=0;
					foreach($models->getModels() as $m){
						$llista[$i]['codi'] = $m->CODI;
						$llista[$i]['nom'] = $m->NOM;
						$i++;
					}
					$results['success'] = true;
					$results['datas'] = $llista;
					$results['mess'] = "S'ha guardat el model correctament";
				}
				else{
					$results['mess'] = "S'ha produit un error guardant el model";
				}
			}
	}

	if(count($results)){
		header('content-type: application/json; charset=utf-8');
		echo json_encode($results);
	}
?>
