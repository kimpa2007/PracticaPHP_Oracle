#!/usr/bin/php-cgi
<?php
	include_once('includes/funcions.php');
	include_once('classes/Form/Formulari.php');
	include_once('classes/Form/Camp.php');
	include_once('classes/Vehicle.php');

	if(!(comprova_auth())){
		header("Location: practica_php.php");
	}

	$errors = false;

	$form = new Formulari();

	$matricula = $form->crearElement("matricula");
	$matricula->setTipus("matricula");
	$matricula->setRestriccio(true);
	
	if(count($_POST)){
			if($form->valida($_POST)){
				$vehicle = new Vehicle();
				$v = $vehicle->getVehicleByMatricula($matricula->getValor());

				if($v){
					header('location: veure_revisions.php?codi='.$v);
					exit();
				}
				else{
					$errors = "No existeix cap vehicle amb aquest codi";
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
		<title>Obtenir revisions d'un vehicle</title>
		<meta name="keywords" content="HTML, CSS, PHP">
		<meta name="description" content="Obtenir codi vehicle">
		<meta charset="UTF-8">
		<meta name="author" content="Claude Chaillet">
		<?php include('includes/css.php'); ?>
		<script src="http://code.jquery.com/jquery-latest.js"></script>
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
				<?php echo $errors; ?>	
			</div>
		<?php
			}
		?>
			<h1>Obtenir revisions d'un vehicle</h1>
			<hr/>

			<form method="post" action="#" class="form-horizontal">
				<div class="form-group">
					<label class="col-md-3">Matricula del vehicle</label>
					<div class="col-md-6">
						<input placeholder="Entrar la matricula" class="form-control" name="matricula" value="<?php echo $matricula->getValor();?>" type="text">
					</div>
				</div>
				
				<button class="btn btn-primary " type="submit">Enviar</button>
			</form>
		</section>
		<script src="assets/js/bootstrap.min.js"></script>
	</body>
</html>

