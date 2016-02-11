#!/usr/bin/php-cgi
<?php 

	if(isset($_SESSION)){
		header('location: index.php');
		exit;
	}
	include('includes/funcions.php');
	include('includes/configuracio.php');
	include('includes/css.php');

	require_once('classes/Form/Formulari.php');
	require_once('classes/Form/Camp.php');

	$errors = false;
	$form = new Formulari();

	$usuari = $form->crearElement("usuari");
	$usuari->setTipus("cadena");
	$usuari->setRestriccio(true);

	$contrasenya = $form->crearElement("contrasenya");
	$contrasenya->setTipus("cadena");
	$contrasenya->setRestriccio(true);

	if(count($_POST)){
		if($form->valida($_POST)){
			//El formulari vàlida i per lo tan hem rebut el tipus de dades esperades.
			//Ara cal comprovar si els accessos proporcionat per el usuari són els correctes
			require_once('classes/Usuari.php');
			$user = new Usuari($usuari, $contrasenya);

			/* Es comprova si es pot establir connexio*/
			if($user->comprovaAcces()){
				header('location: index.php');
				exit;
			}
			else{
				$errors = $user->mostrarErrors();
				
			}
		}
		else{
			$errors = $form->mostrarErrors();
		}
	}
?>

<html lang="es-ES">
	<head>
		<title>Login</title>
		<meta name="keywords" content="HTML, CSS, PHP">
		<meta name="description" content="Login a la pàgina de prova">
		<meta charset="UTF-8">
		<meta name="author" content="Claude Chaillet">
	</head>

	<body class="container">

		<header>
				<h1>Login</h1>
				<h4>Entri les seves dades </h4>
				<hr/>
		</header>

		<section>
			<?php if($errors){
					foreach ($errors as $error){
						var_dump($error);
					}
				}
			?>

			<form method="post" action="#" class="form-horizontal">
				<div class="form-group">
					<label class="col-md-3">Usuari</label>
					<div class="col-md-6">
						<input class="form-control" name="usuari" value="<?php echo $usuari->getValor();?>" type="text" name="usuari">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3">Contrasenya</label>
					<div class="col-md-6">
						<input class="form-control" name="contrasenya" value="" type="password" name="contrasenya">
					</div>
				</div>
				<button class="btn btn-primary" type="submit">Enviar</button>
			</form>
			
		</section>
	</body>
</html>
