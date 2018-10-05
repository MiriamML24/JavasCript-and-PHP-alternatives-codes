<?php	
	error_reporting(0);
	session_start();
	if($_SESSION['identificado'] != "Administrador"){
		$token = uniqid();
		$_SESSION['token'] = $token;
		echo '<h1 style = "color:blue">Menú de opciones usuarios</h1>Has iniciado sesion con el nombre de: ' .$_SESSION['identificado'];
$delimitador = <<<xxx
		<input type = 'hidden' name = 'token' value = '$token'/><br><br>
		<a style = "color:black" href = "ponerVenta.php">Poner en venta un producto</a><br><br>
		<a style = "color:black" href = "pujar.php">Pujar por un producto</a>
		<br><br>
		<form action = "cerrarSesion.php" method = "post">
			<input type = "submit" name = "cerrarSesion" value = "Cerrar sesion"/>
		</form>	
xxx;
	} else {
		header("Location:logueoAdmin.php");
	}
	echo $delimitador;
?>