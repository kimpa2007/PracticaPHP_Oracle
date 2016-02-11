#!/usr/bin/php-cgi
<?php
	$dir = dirname(__FILE__);

	include_once($dir.'/includes/funcions.php');
	include_once($dir.'/classes/Form/Formulari.php');
	include_once($dir.'/classes/Form/Camp.php');
	include_once($dir.'/classes/Lloguer.php');

	if(!(comprova_auth())){
		header("Location: practica_php.php");
	}

	$errors = array();

	$form = new Formulari();

	$venedor = $form->crearElement("codi_venedor");
	$venedor->setTipus("numeric");
	$venedor->setRestriccio(true);

	$client = $form->crearElement("codi_client");
	$client->setTipus("numeric");
	$client->setRestriccio(true);

	$vehicle = $form->crearElement("codi_vehicle");
	$vehicle->setTipus("numeric");
	$vehicle->setRestriccio(true);

	$datai = $form->crearElement("datai");
	$datai->setTipus("data");
	$datai->setRestriccio(true);

	$kmi = $form->crearElement("kmi");
	$kmi->setTipus("numeric");
	$kmi->setRestriccio(true);

	$accessoris = $form->crearElement("codi_accessori");
	$accessoris->setTipus("array");
	$accessoris->setRestriccio(false);


	include('classes/Llistes/LlistaVenedors.php');
	$venedors = new LlistaVenedors();

	include('classes/Llistes/LlistaClients.php');
	$clients = new LlistaClients();

	include('classes/Llistes/LlistaVehicle.php');
	$vehicles = new LlistaVehicle();

	if(count($_POST)){
			if($form->valida($_POST)){
				$infos = array();
				array_push($infos, $venedor, $client, $vehicle, $datai, $kmi);

				$lloguer = new Lloguer();
				$lloguer->setInfos($infos);
				$lloguer->setAccessoris($accessoris->getValor());
				;
				if($lloguer->save()){
					header('location: lloga_vehicle.php?inserit');
					exit();
				}
				else{
					$errors = $lloguer->getErrors();
				}
			}			
			else{
				$errors = $form->mostrarErrors();
			}
	}
	else{
		$errors = $form->mostrarErrors();
	}

	
?>


<html lang="es-ES">
	<head>
		<title>Afegir lloguer</title>
		<meta name="keywords" content="HTML, CSS, PHP">
		<meta name="description" content="Afegir un lloguer">
		<meta charset="UTF-8">
		<meta name="author" content="Claude Chaillet">
		<?php 	include('includes/css.php'); ?>

		<script src="http://code.jquery.com/jquery-latest.js"></script>

		<script type="text/javascript">
			$(document).ready(function ()
			{

				function getAccessoris(){
					var form = $('#codi_vehicle').serialize()+ '&' +$('#codi_vehicle').serialize();
					$.ajax({
						type: "POST",
						url: "ajax/get_accessoris.php",
						data: form,
						dataType: "json",
						error : function(){
							alert("Error greu de l'aplicació");
						},
						success: function(datas){
							if(datas.success === true){
								$("#accesoris").empty();
								$.each(datas.llista, function(k, v){
									var codi =  v.codi;
									var descripcio = v.descripcio;
									$("#accesoris").append("<option value='"+codi+"'>"+descripcio+"</option>");
							   	});
							}
						}
					});

				}

				getAccessoris();
				$('#codi_vehicle').click(function(e){
					getAccessoris();
				});
			});
		</script>
	</head>

	<body>
		<header >
			<?php 
				$page="lloguer"; 
				include('includes/header.php'); 
			?>
		</header>

		<section class="container">
		<?php 
			if(count($errors)>0){ 
		?>
			<div class="alert alert-warning">
				<?php echo $errors;?>	
			</div>
		<?php
			}
		?>
			<h1>Llogar vehicle</h1>
			<hr/>

			<?php
				if(isset($_GET['inserit'])){
			?>
				<div class="alert alert-success">
					El vehicle s'ha llogat correctament
				</div>
			<?php
				}
			?>
			<form method="post" action="#" class="form-horizontal">

				<div class="form-group">
					<label class="col-md-3">Venedor</label>
					<div class="col-md-6">
						<select  class="form-control" name="codi_venedor">
					<?php
						foreach($venedors->getVenedors() as $v){
							$check=""; 
							if($v->CODI == $venedor->getValor()) $check='selected="selected"';
							echo '<option value="'.$v->CODI.'" '.$check.'>'.$v->NOM.' '.$v->COGNOMS.' ('.$v->DELEGACIO.')'.'</option>';
						}
					?>
						</select>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-3">Client</label>
					<div class="col-md-6">
						<select  class="form-control" name="codi_client">
					<?php
						foreach($clients->getClients() as $c){
							$check=""; 
							if($c->CODI == $client->getValor()) $check='selected="selected"';
							echo '<option value="'.$c->CODI.'" '.$check.'>'.$c->NOM.' '.$c->COGNOMS.' ('.$c->DNI.')'.'</option>';
				 		}
					?>
						</select>
					</div>
				</div>


				<div class="form-group">
					<label class="col-md-3">Vehicle</label>
					<div class="col-md-6">
						<select  class="form-control" id="codi_vehicle" name="codi_vehicle">
					<?php
						foreach($vehicles->getVehiclesDisponibles() as $v){
							$check=""; 
							if($v->CODI == $vehicle->getValor()) $check='selected="selected"';
							echo '<option value="'.$v->CODI.'" '.$check.'>'.$v->MODEL.' ('.$v->MATRICULA.')'.'</option>';
						}
					?>
						</select>
					</div>
				</div>


				<div class="form-group">
					<label class="col-md-3">Data Inici</label>
					<div class="col-md-6">
						<input  class="form-control" name="datai" value="<?php echo $datai->getValor();?>" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3">Kms Inicials</label>
					<div class="col-md-6">
						<input  class="form-control" name="kmi" value="<?php echo $kmi->getValor();?>" required>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-3">Accesoris disponibles</label>
					<div class="col-md-6">
						<select  class="form-control" id="accesoris" name="codi_accessori[]" multiple="multiple">
						</select>
					</div>
				</div>


				<button class="btn btn-primary " type="submit">Enviar</button>
			</form>
		</section>
		<script src="assets/js/bootstrap.min.js"></script>
	</body>
</html>

