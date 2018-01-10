<?php
	if ( ! session_id() ) @ session_start();
	if(!empty($_POST['nombre'])){
		// Se carga el archivo que esta en el mismo directorio
		$archivo = file_get_contents("peliculas.json");
		// Se lo convierte en array
		$pelis = json_decode($archivo);
		// Saber cuantas peliculas hay
		$tamaño = (count($pelis->Peliculas)-1);
		$estado = true;
		$contador = 0;
		// Se inicializa la sesion para ir guardando las peliculas que ya salieron asi no se repiten.
		if(!isset($_SESSION['ya_esta'])) $_SESSION['ya_esta'] = [];
		
		while ($estado) { // Si la pelicula ya salió, va a buscar otra...
			$random = rand(0, $tamaño);
			if(!array_key_exists($random, $_SESSION['ya_esta'])){
				$tu_peli = $pelis->Peliculas[$random];
				$_SESSION['ya_esta'][$random] = ['pelicula' => $tu_peli->nombre, 'nombre' => $_POST['nombre']];
				$estado = false;
			}

			if($contador == $tamaño){ $tu_peli = (object)["nombre" => "No hay mas películas :("]; $estado = false; }
			$contador++;
		}
	}
	if(!empty($_GET['reiniciar'])){
		session_destroy();
		header("Location: index.php");
	}

?>
<!-- Creado por Favio Brntn (https://github.com/Faviobrntn)
=========================================================== -->
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
    	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	    <meta name="author" content="Favio Barnatan">
	    <meta name="application-name" content="Digalo con mimica">
	    <meta name="description" content="Es un juego donde tu grupo debe adivinar la pelicula antes que el tiempo se agote.">
		<title>Digalo con mimica</title>
		<link rel="manifest" href="manifest.json">
    	<link href="css/bootstrap.min.css" rel="stylesheet">
    	<link href="css/navbar-top-fixed.css" rel="stylesheet">
	</head>

	<body>

		<!-- BARRA SUPERIOR -->
		<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
			<a class="navbar-brand" href="#">Tiempo:  <span id="timer">--</span></a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarCollapse">
				<ul class="navbar-nav mr-auto">
					<li class="nav-item active">
						<a class="nav-link comenzar" href="#">Comenzar <span class="sr-only">(current)</span></a>
					</li>
					<li class="nav-item active">
						<a class="nav-link detener" href="#" style="display: none;">Detener</a>
					</li>
				</ul>

				<form class="form-inline mt-2 mt-md-0" id="form_tiempo">
					<input class="form-control mr-sm-2" type="number" placeholder="Tiempo" aria-label="Search" id="tiempo" min="1">
					<button class="btn btn-outline-success my-2 my-sm-0" type="submit" id="guardar_tiempo">
					Fijar tiempo</button>
				</form>
			</div>
		</nav>

		<main role="main" class="container">
			<!-- BOX PRINCIPAL -->
			<div class="jumbotron">
				<h1>Digalo con mimica</h1>

				<?php if (empty($tu_peli)): ?>
				<form class="form-inline mt-2 mt-md-0" action="index.php" method="post">
					<input class="form-control mr-sm-2" type="text" placeholder="Tu nombre" aria-label="Search" name="nombre" autocomplete="off">
					<input type="submit" class="btn btn-md btn-primary my-2 my-sm-0" value="Ver que película me toca! &raquo;"><br>
				</form>
				<?php endif ?>

				<?php if(!empty($tu_peli)): ?>
					<p class="lead">Tu película es: <span><b><?= $tu_peli->nombre ?></b></span></p>
					<a class="btn btn-lg btn-success comenzar" href="#">Comenzar</a>
					<a class="btn btn-lg btn-danger detener" href="#" style="display: none;">Detener</a>
					<a class="btn btn-lg btn-info pull-right" href="." role="button">OTRO TURNO &raquo;</a>
				<?php endif ?>
			</div>

			<!-- TABLA DE LOS QUE YA SALIERON -->
			<?php if (!empty($_SESSION['ya_esta'])): ?>
			<h2>Los que ya salieron.. 
				<small><a href="?reiniciar=true" class="btn btn-sm btn-dark" onclick="return confirm('¿Esta seguro de querer reiniciar el juego?')">Reiniciar juego</a></small>
			</h2>
			<div class="table-responsive">
	            <table class="table table-striped">
		            <thead>
		                <tr>
			                <th>#</th>
			                <th>Jugador</th>
			                <th>Película</th>
		                </tr>
		            </thead>
	              	<tbody>
	              		<?php foreach ($_SESSION['ya_esta'] as $key => $salio): ?>
	                	<tr>
	                  		<td><?= $key ?></td>
	                  		<td><?= ucfirst($salio['nombre']); ?></td>
	                  		<td><?= $salio['pelicula'] ?></td>
	                	</tr>
	              		<?php endforeach ?>
	              	</tbody>
	            </table>
	        </div>
	        <?php endif ?>
		</main>


		<footer class="footer">
	      	<div class="container">
	        	<span class="text-muted"><a href="https://github.com/Faviobrntn/mimica">Digalo con mimica</a> creado por <a href="https://github.com/Faviobrntn">Favio Brntn</a></span>
	      	</div>
    	</footer>

		<!-- SONIDOS -->
		<audio id="alarma">
		  	<source src="sonidos/alarma.mp3" type="audio/mpeg">
		</audio>
		<audio id="tic">
		  	<source src="sonidos/tic.mp3" type="audio/mpeg">
		</audio>

		
		<!-- Bootstrap core JavaScript
	    ================================================== -->
    	<script src="js/jquery.min.js"></script>
	    <script src="js/popper.min.js"></script>
	    <script src="js/bootstrap.min.js"></script>
	    <script src="js/index.js"></script>

		<script type="text/javascript">
			window.onload = function(){
				var crono = new Cronometro();
			}
		</script>

	</body>
</html>