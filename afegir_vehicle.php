#!/usr/bin/php-cgi
<?php
	include_once('includes/funcions.php');
	include_once('classes/Form/Formulari.php');
	include_once('classes/Form/Camp.php');
	include_once('classes/Vehicle.php');

	if(!(comprova_auth())){
		header("Location: practica_php.php");
	}


	//$vehicle = new Vehicle(7369);
	//$vehicle->obtenirDades();

	$errors = false;

	$form = new Formulari();

	$model = $form->crearElement("model_codi");
	$model->setTipus("numeric");
	$model->setRestriccio(true);

	/*tipus matricula*/
	$matricula = $form->crearElement("matricula");
	$matricula->setTipus("matricula");
	$matricula->setRestriccio(true);

	$color = $form->crearElement("color");
	$color->setTipus("texte");
	$color->setRestriccio(true);

	$data_compra = $form->crearElement("data_compra");
	$data_compra->setTipus("data");
	$data_compra->setRestriccio(true);

	$combustible = $form->crearElement("combustible");
	$combustible->setTipus("seleccio",array('Electric','Diesel','Gasolina'));
	$combustible->setRestriccio(true);

	$asseguranca = $form->crearElement("asseguranca");
	$asseguranca->setTipus("numeric");
	$asseguranca->setRestriccio(false);

	include('classes/Llistes/LlistaModels.php');
	$models = new LlistaModels();

	if(count($_POST)){
			if($form->valida($_POST)){
				$infos = array();
				array_push($infos, $matricula, $model, $color, $data_compra, $combustible, $asseguranca);

				$vehicle = new Vehicle();
				$vehicle->setInfos($infos);

				if($vehicle->save()){
					header('location: afegir_vehicle.php?inserit='.$matricula->getValor());
					exit();
				}
				else{
					$errors = $vehicle->getErrors();
					echo "ups";
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
		<title>Afegir vehicle</title>
		<meta name="keywords" content="HTML, CSS, PHP">
		<meta name="description" content="Afegir un vehicle">
		<meta charset="UTF-8">
		<meta name="author" content="Claude Chaillet">
		<?php include('includes/css.php'); ?>
		<script src="http://code.jquery.com/jquery-latest.js"></script>

		<script type="text/javascript">
			$(document).ready(function ()
			{
				$('#guardaModel').click(function(e){	
					var form = $('#dades').serialize()+ '&' +$('#dades').serialize();
					e.preventDefault();

					$.ajax({
						type: "POST",
						url: "ajax/nou_model.php",
						data: form,
						dataType: "json",
						error : function(){
							$("#result").empty();
							$("#result").removeClass();
							$("#result").addClass('btn-danger');
							$("#result").append('');
						},
						success: function(datas){
							if(datas.success === true){
								$("#tancaModel").html("Tancar");
								$(".modal-body #result").empty();
								$(".modal-body #result").append('<p class="alert alert-success">'+datas.mess+'</p>');
								//recaregar models;
							}
							else{
								$(".modal-body #result").empty();
								$(".modal-body #result").append('<p class="alert alert-danger">'+datas.mess+'</p>');
							}
						}
					});
				});
			});
		</script>
	</head>

	<body>

		<header >
			<?php 
				$page="afegir"; 
				include('includes/header.php'); 
			?>
		</header>

		<section class="container">
		<?php 
			if(count($errors)>0){ 
		?>
			<div class="alert alert-warning">
				<?php var_dump($errors);	?>	
			</div>
		<?php
			}
		?>
			<h1>Afegir vehicle</h1>
			<hr/>

			<?php
				if(isset($_GET['inserit'])){
			?>
				<div class="alert alert-success">
					El vehicle amb matricula <strong><?php echo $_GET['inserit'];?></strong> s'ha inserit correctament
				</div>
			<?php
				}
			?>
			<form method="post" action="#" class="form-horizontal">
				<div class="form-group">
					<label class="col-md-3">Matricula</label>
					<div class="col-md-6">
						<input placeholder="GI-9536-BB / 3364DSR" class="form-control" name="matricula" value="<?php echo $matricula->getValor();?>" type="text" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3">Data de compra</label>
					<div class="col-md-6">
						<input class="form-control" placeholder="01/01/1970 o 01-01-1970" name="data_compra" value="<?php echo $data_compra->getValor();?>" type="text" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3">Color</label>
					<div class="col-md-6">
						<input class="form-control" name="color" value="<?php echo $color->getValor();?>" type="text" required>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-3">Model <a href="#" data-toggle="modal" data-target="#myModal">(Afegir Model)</a></label>
					<div class="col-md-6">
						<select  class="form-control" name="model_codi">
					<?php
						foreach($models->getModels() as $m){
							$check=""; 
							if($m->CODI == $model->getValor()) $check='selected="selected"';
							echo '<option value="'.$m->CODI.'" '.$check.'>'.$m->NOM.'</option>';
						}
					?>
						</select>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-3">Combustible</label>
					<div class="col-md-6">
						<select  class="form-control" name="combustible">
							<option value="Gasolina">Gasolina</option>
							<option value="Diesel">Diesel</option>
							<option value="Electric">Electric</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3">Assegurança</label>
					<div class="col-md-6">
						<input class="form-control" name="asseguranca" value="<?php echo $asseguranca->getValor();?>" type="text" required>
					</div>
				</div>
				<button class="btn btn-primary " type="submit">Enviar</button>
			</form>
		</section>

		<!-- Modal -->
		<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="myModalLabel">Afegir un model</h4>
					</div>
					<form class="form-horizontal" method="post" id="dades">
						<div class="modal-body">
							<div id="result"></div>
							<form method="post" action="#" class="form-horizontal">
								<div class="form-group">
									<label class="col-md-3">Nom del model</label>
									<div class="col-md-9">
										<input class="form-control" name="nom" id="nom" type="text" required>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3">Grup</label>
									<div class="col-md-9">
										<select class="form-control" name="grup" id="grup" type="text" >	
											<option value="A">A</option>
											<option value="B">B</option>
											<option value="C">C</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3">Tarifa dia</label>
									<div class="col-md-9">
										<input class="form-control" name="tarifa_dia" id="tarifa_dia" type="text" required>
									</div>
								</div>
							</form>
						</div>

						<div class="modal-footer">
							<button id="tancaModel" type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
							<button  id="guardaModel" type="button"class="btn btn-primary">Guardar</button>
						</div>
					</form>
				</div>
			</div>
		</div>


		<!--<script src="assets/jquery/jquery-2.0.3.min.js"></script>
      		<script src="assets/bootstrap/bootstrap-2.3.1.min.js"></script>-->

		<script src="assets/js/bootstrap.min.js"></script>
	</body>
</html>

