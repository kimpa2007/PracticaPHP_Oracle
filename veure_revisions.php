#!/usr/bin/php-cgi
<?php 
	include_once('classes/Vehicle.php');
	//include('includes/funcions.php');
	if(!(comprova_auth())){
		header("Location: practica_php.php");
		exit;
	}

	if(!(isset($_GET['codi'])))
	{
		header("Location: dades_revisio.php");
		exit;
	}

	$codi = $_GET['codi'];
	$vehicle = new Vehicle($codi);
	$v = $vehicle->obtenirDades();

	if($v){
	}
	else{
		header("Location: dades_revisio.php");
		exit;
	}

	//sino recuperar lista vehicles
	include_once('classes/Llistes/LlistaRevisio.php');
	$llista_revisio = new llistaRevisio();
	$llista_revisio = $llista_revisio->getRevisioPerVehicle($codi);
	
?>


<html lang="es-ES">
	<head>
		<title>Lloguers en curs</title>
		<meta name="keywords" content="HTML, CSS, PHP">
		<meta name="description" content="Llistat de revisions">
		<meta charset="UTF-8">
		<meta name="author" content="Claude Chaillet">
		<?php include('includes/css.php'); ?>
		<script src="http://code.jquery.com/jquery-latest.js"></script>
	</head>

	<body>

		<header >
			<?php $page="llista"; include('includes/header.php'); ?>
		</header>

		<section class="container">
			<h1>Llista de revisions del vehicle CODI <?php echo $_GET['codi']; ?></h1>
			<hr/>

			<h3 class="row">
				<span class="pull-left">	
					<strong>Propera revisió: </strong> <?php echo $vehicle->getKmsRevisio();?>
				</span>
				<span class="pull-right">	
					<strong>Estat: </strong> 
				<?php 
					if ($vehicle->estaLlogat()){
						echo "llogat";
					}
					else{
						echo "no llogat";
					}
 				?>
				</span>
			</h3>

		<?php 
			if($llista_revisio){
		?>		
			<table style="margin-top: 75px;" class="text-center table table-bordered">
				<thead>
					<tr>
						<td>Codi Revisio</td>
						<td>Data</td>
						<td>Kms a revisio</td>
						<td>Encarregat revisio</td>
					</tr>
				</thead>
				<tbody>
			<?php 
				$i=0;

				if(!count($llista_revisio)){

			?>
				<div class="row col-md-12 row alert alert-success">
					<p>No tenim cap dada disponible.</p>
				</div>
			<?php
				}

				foreach($llista_revisio as $rev){
					$i++;
			?>
					<tr <?php if($i%2 == 0) echo 'class="active"'; ?>>
						<td><?php echo $rev->CODI; ?></td>
						<td><?php echo $rev->DATA_L; ?></td>
						<td><?php echo $rev->KMS; ?></td>
						<td><?php echo $rev->NOM . " " . $rev->COGNOMS; ?></td>
					</tr>
			<?php
				}
			?>
				</body>
			</table>
		<?php 
			}
			else
			{
		?>
			<div class="alert alert-warning">
				Aquest vehicle encara no ha passat revisions.
			</div>
		<?php
			}
		?>
		</section>
		<script src="assets/js/bootstrap.min.js"></script>
	</body>
</html>
