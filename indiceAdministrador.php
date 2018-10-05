<?php	
	error_reporting(0);
	session_start();
	if($_SESSION['identificado'] == "Administrador"){
		$token = uniqid();
		$_SESSION['token'] = $token;
		echo '<h1 style = "color:blue">Menú del administrador</h1>Has iniciado sesion con el nombre de: ' .$_SESSION['identificado'];
$delimitador = <<<xxx
		<br><br>
		<input type = 'hidden' name = 'token' value = '$token'/>
		<a style = "color:black" href = "listadoNoVendidos.php">Listado productos no vendidos</a><br><br>
		<a style = "color:black" href = "ejecutaVentas.php">Ejectuar las ventas</a>
		<br><br>
		<form action = "cerrarSesion.php" method = "post">
			<input type = "submit" name = "cerrarSesion" value = "Cerrar sesion"/>
		</form>	
xxx;
	} 
	echo $delimitador;
?>