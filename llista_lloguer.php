#!/usr/bin/php-cgi
<?php 
	include('includes/funcions.php');
	if(!(comprova_auth())){
		header("Location: practica_php.php");
	}

	
	//sino recuperar lista vehicles
	include_once('classes/Llistes/LlistaLloguer.php');
	$llista_lloguers = new llistaLloguer();
	$llista_lloguers->getLloguers();
?>


<html lang="es-ES">
	<head>
		<title>Lloguers en curs</title>
		<meta name="keywords" content="HTML, CSS, PHP">
		<meta name="description" content="Llistat de lloguers en curs">
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
			<h1>Llista de lloguers en curs</h1>
			<hr/>


			<form class="form-inline" method="get" action="tancar_lloguer.php">
				<div class="form-group">
					<input class="form-control" type="text" name="matricula" placeholder="Entri la matricula del vehicle" required>
					<button class="btn btn-primary" >Anar a tancar el lloguer</button>
				</div>
			</form>
			<table class="text-center table table-bordered">
				<thead>
					<tr>
						<td>Codi</td>
						<td>Client</td>
						<td>Venedor</td>
						<td>Vehicle</td>
						<td>Data Inici</td>
						<td>Kms inicials</td>
						<td>Opcions</td>
					</tr>
				</thead>
				<tbody>
			<?php 
				$i=0;

				if(!count($llista_lloguers->getLloguers())){

			?>
				<div class="alert alert-success">
					<p>No tenim cap dada disponible.</p>
				</div>
			<?php
				}

				foreach($llista_lloguers->getLloguers() as $lloguer){
					$i++;
			?>
					<tr <?php if($i%2 == 0) echo 'class="active"'; ?>>
						<td><?php echo $lloguer->CODI; ?></td>
						<td><?php echo $lloguer->CLIENT; ?></td>
						<td><?php echo $lloguer->VENEDOR; ?></td>
						<td><?php echo $lloguer->MATRICULA; ?></td>
						<td><?php echo $lloguer->DATAI; ?></td>
						<td><?php echo $lloguer->KMI; ?></td>
						<td><a class="btn btn-primary" href="tancar_lloguer.php?matricula=<?php echo $lloguer->MATRICULA; ?>">Tancar Lloguer</a></td>
					</tr>
			<?php
				}
			?>
				</body>
			</table>
		</section>
		<script src="assets/js/bootstrap.min.js"></script>
	</body>
</html>
