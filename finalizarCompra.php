<! DOCTYPE html">
<html xmlns= "http://www.w3.org/1999/xhtml" xml:lang="es" lang= "es"/>
	<head>
		<meta http-equiv="Content-Type" content="text/html charset=UTF-8"/>
		<link href="./css/tiendaVirtual.css" rel="stylesheet" type = "text/css">	
		<title>Tienda virtual</title>
	</head>
	
	<body>
		<ul>
			<li><a href="indiceCliente.html">Volver</a></li>
			<li>
				<a href = "listadoProductos.php">Seguir comprando</a>
			</li>
		</ul>
	</body>
</html>
<?php
	echo "<div id = 'contenedor'><h1>Finalizar compra</h1>";
	if(!isset($_SESSION['identificado'])){
		session_start();
		echo "<form method = 'post' action = 'finalizacion.php'>
			Medio de pago:
				<select name = 'medioPago'>
					<option value = 'tarjeta'>Tarjeta</option>
					<option value = 'paypal'>Paypal</option>
					<option value = 'transferencia'>Transferencia</option>
				</select><br><br>
				Tipo de envio: 
				<select name = 'tipoEnvio'>
					<option value = 'correo'>Correo</option>
					<option value = 'mensajeria'>Mensjeria</option>
				</select><br><br>
				<input type = 'submit' value = 'Finalizar compra'/> 
			</form>";
	} else {
		header("Location:indice.html");
	}
	
?> 