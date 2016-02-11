#!/usr/bin/php-cgi
<?php 
	include('includes/funcions.php');
	if(!(comprova_auth())){
		header("Location: practica_php.php");
	}

	
	//sino recuperar lista vehicles
	require_once('classes/Llistes/LlistaVehicle.php');
	$llista_vehicles = new llistaVehicle();
?>


<html lang="es-ES">
	<head>
		<title>Llista vehicles</title>
		<meta name="keywords" content="HTML, CSS, PHP">
		<meta name="description" content="Llistat de vehicles">
		<meta charset="UTF-8">
		<meta name="author" content="Claude Chaillet">
		<?php include('includes/css.php'); ?>
		<script src="http://code.jquery.com/jquery-latest.js"></script>
		<script src="assets/js/bootstrap.min.js"></script>
	</head>

	<body>

		<header >
			<?php $page="llista"; include('includes/header.php'); ?>
		</header>

		<section class="container">
			<!--c)
				Consultar els vehicles de l’empresa (tots els vehicles de l’empresa i, en cas que estiguin llogats, la data d’inici del lloguer).
-->
			<h1>Llista vehicles</h1>
			<hr/>

			<table class="text-center table table-bordered">
				<thead>
					<tr>
						<td>Matricula</td>
						<td>Model</td>
						<td>Color</td>
						<td>Combustible</td>
						<td>Data Inici Lloguer</td>
					</tr>
				</thead>
				<tbody>
			<?php 
				$i=0;

				if(!count($llista_vehicles->getTotVehicles())){

			?>
				<div class="alert alert-success">
					<p>No tenim cap dada disponible.</p>
				</div>
			<?php
				}

				foreach($llista_vehicles->getTotVehicles() as $vehicle){
					$i++;
			?>
					<tr <?php if($i%2 == 0) echo 'class="active"'; ?>>
						<td><?php echo $vehicle->MATRICULA; ?></td>
						<td><?php echo $vehicle->MODEL; ?></td>
						<td><?php echo $vehicle->COLOR; ?></td>
						<td><?php echo $vehicle->COMBUSTIBLE; ?></td>
						<td><?php
							//echo $vehicle->DATAF;
							//echo " ".$vehicle->DATAI; 
							if(empty($vehicle->DATAF)){
								 echo $vehicle->DATAI;
							}
						    ?></td>
					</tr>
			<?php
				}
			?>
				</body>
			</table>
		</section>

	</body>
</html>
