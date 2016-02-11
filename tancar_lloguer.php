#!/usr/bin/php-cgi
<?php
	include_once('includes/funcions.php');
	include_once('classes/Form/Formulari.php');
	include_once('classes/Form/Camp.php');
	include_once('classes/Lloguer.php');

	if(!(comprova_auth())){
		header("Location: practica_php.php");
		exit;
	}

	if(!(isset($_GET['matricula'])) or (isset($_GET['matricula']) and empty($_GET['matricula']))) {
		header("Location: llista_lloguer.php");
		exit;
	}
	
	
	$matricula= $_GET['matricula'];

	$lloguer = new Lloguer();
	$llog  = $lloguer->getLloguerEnCursPerVehicle($matricula);
	
	$errors = array();
	$form = new Formulari();
	
	$dataf = $form->crearElement("dataf");
	$dataf->setTipus("data");
	$dataf->setRestriccio(true);

	$kmf = $form->crearElement("kmf");
	$kmf->setTipus("numeric");
	$kmf->setRestriccio(true);

	$retorn = $form->crearElement("retorn");
	$retorn->setTipus("seleccio",array('A','B','C','D'));
	$retorn->setRestriccio(true);

	$codi = $form->crearElement("codi");
	$codi->setTipus("numeric");
	$codi->setRestriccio(true);

	if(count($_POST)){
			if($form->valida($_POST)){
				if($lloguer->tancarLloguer($dataf->getValor(), $kmf->getValor(), $retorn->getValor(), $codi->getValor())){
					header('location: llista_lloguer.php?tancat='.$matricula);
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
		<title>Retornar un vehicle</title>
		<meta name="keywords" content="HTML, CSS, PHP">
		<meta name="description" content="Retornar un vehicle">
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
				<?php var_dump($errors);	?>	
			</div>
		<?php
			}
		?>
			<h1>Retornar un vehicle</h1>
			<hr/>

			<div class="text-center">
				<p><strong>Codi lloguer: </strong> <?php echo $llog->CODI;?> </p>
				<p><strong>Vehicle: </strong> <?php echo $llog->VEHICLE;?> </p>
				<p><strong>Client: </strong> <?php echo $llog->CLIENT;?> </p>
				<p><strong>Data inici: </strong> <?php echo $llog->DATAI;?> </p>
				<p><strong>Kms incials: </strong> <?php echo $llog->KMI;?> </p>
				<hr/>
			</div>
			<form method="post" action="#" class="form-horizontal">
				<div class="form-group">
					<label class="col-md-3">Data de retorn</label>
					<div class="col-md-6">
						<input class="form-control" placeholder="01/01/1970 o 01-01-1970" name="dataf" value="<?php echo $dataf->getValor();?>" type="text">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3">Kms al retorn</label>
					<div class="col-md-6">
						<input class="form-control" name="kmf" value="<?php echo $kmf->getValor();?>" type="text">
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-3">Estat</label>
					<div class="col-md-6">
						<select  class="form-control" name="retorn">
							<option value="A">Impecable</option>
							<option value="B">Necessita neteja</option>
							<option value="C">Portar al taller</option>
							<option value="D">Sinistre</option>
						</select>
					</div>
				</div>
				<input name="codi" value="<?php echo $llog->CODI; ?>" type="hidden">
				<button class="btn btn-primary " type="submit">Enviar</button>
			</form>
		</section>
		<script src="assets/js/bootstrap.min.js"></script>
	</body>
</html>

