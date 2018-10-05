<! DOCTYPE html">
<html xmlns= "http://www.w3.org/1999/xhtml" xml:lang="es" lang= "es"/>
	<head>
		<meta http-equiv="Content-Type" content="text/html charset=UTF-8"/>
		<link href="./css/tiendaVirtual.css" rel="stylesheet" type = "text/css">	
		<title>Tienda virtual</title>
	</head>
	
	<body>
		<ul>
			<li><a href="indice.html">Volver</a></li>
			<li class="dropdown">
				<a href="javascript:void(0)" class="dropbtn">Cliente</a>
				<div class="dropdown-content">
					<a href = "logueoCliente.html">Iniciar sesion cliente</a>
					<a href = "registrarCliente.html">Registrarse</a>
				</div>
			</li>
		</ul>
	</body>
</html>

<?php

	$nombre = $_POST['nombre'];
	$direccion = $_POST['direccion'];
	$cp = $_POST['cp'];
	$correo = $_POST['correo'];
	$passwd = $_POST['passwd'];
	$telefono = $_POST['telefono'];
	$tarjetas = $_POST['tarjetas'];
	$vnumTarjeta = $_POST['numTarjeta'];
	$fechaCad = $_POST['fechaCad'];
	$fechaActual = new DateTime('now');
	$fecha = $fechaActual->format("Y-m-d");
	$fechaActPart = explode("-", $fecha);
	$fechaRecibida = explode("-", $fechaCad);
	
	echo '<div id = "contenedor"><h1>Cliente regristrado</h1>';
	
	if ((validarDatos($nombre)) && (validarDatos($direccion)) && (validarDatos($cp)) && (validarDatos($correo)) 
		&& (validarDatos($passwd)) && (validarDatos($telefono)) && ($tarjetas != "") && (validarDatos($vnumTarjeta)) && (validarDatos($fechaCad))
		&& (($fechaActPart[0] <= $fechaRecibida[0]) || ($fechaActPart[1] <= $fechaRecibida[1]))){
		inscribirCliente();  	
	} else {
		reescribir();
	}
	
	function inscribirCliente(){
		$nombre = $_POST['nombre'];
		$direccion = $_POST['direccion'];
		$cp = $_POST['cp'];
		$correo = $_POST['correo'];
		$passwd = $_POST['passwd'];
		$telefono = $_POST['telefono'];
		$tarjetas = $_POST['tarjetas'];
		$vnumTarjeta = $_POST['numTarjeta'];
		$fechaCad = $_POST['fechaCad'];
		
		try{
			$con = new PDO ('mysql:dbname=tienda;host=localhost', 'root', 'root');
			$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$con->exec("set names utf8");
			$buscarCliente = $con->prepare("select count(clienteId) as clienteRegistrado from clientes where email like :correo
											or cp like :codigoPostal and nombre like :nom and direccion like :dir 
											and tlfno like :telefono or tipoTarjeta like :tipo and numTarjeta like :tarjeta 
											and fechaCaducidad like :fecha");
			$buscarCliente->bindValue(":nom", $nombre, PDO::PARAM_STR);
			$buscarCliente->bindValue(":dir", $direccion, PDO::PARAM_STR);
			$buscarCliente->bindValue(":codigoPostal", $cp, PDO::PARAM_STR);
			$buscarCliente->bindValue(":correo", $correo, PDO::PARAM_STR);
			$buscarCliente->bindValue(":telefono", $telefono, PDO::PARAM_STR);
			$buscarCliente->bindValue(":tipo", $tarjetas, PDO::PARAM_STR);
			$buscarCliente->bindValue(":tarjeta", $vnumTarjeta, PDO::PARAM_STR);
			$buscarCliente->bindValue(":fecha", $fechaCad, PDO::PARAM_STR);
			$buscarCliente->execute();
			$resultadoConsulta = $buscarCliente->fetch(PDO::FETCH_ASSOC);
			
			if ($resultadoConsulta['clienteRegistrado'] > 0){
				echo "<h4>Ya estaba registrado el cliente <i>$nombre</i></h4>
					<i>En cinco segundo se te redireccionará a la pagina para poder iniciar sesion</i>";
				header("Refresh:5; url=./logueoCliente.html", true, 303);
			} else {
				$insertar = $con->prepare("Insert into clientes values (0, :nombreC, :direccionC, :codPostal, :email, :tlfno, :tarjeta, :numTarjeta, :fechaCaducidad)");
				$insertar->bindValue(":nombreC", $nombre, PDO::PARAM_STR);
				$insertar->bindValue(":direccionC", $direccion, PDO::PARAM_STR);
				$insertar->bindValue(":codPostal", $cp, PDO::PARAM_STR);
				$insertar->bindValue(":email", $correo, PDO::PARAM_STR);
				$insertar->bindValue(":tlfno", $telefono, PDO::PARAM_STR);
				$insertar->bindValue(":tarjeta", $tarjetas, PDO::PARAM_STR);
				$insertar->bindValue(":numTarjeta", $vnumTarjeta, PDO::PARAM_STR);
				$insertar->bindValue(":fechaCaducidad", $fechaCad, PDO::PARAM_STR);
				$insertar->execute();
				if ($insertar->rowCount() > 0){
					echo "<h4>Se ha registrado el cliente con nombre: <i>$nombre</i> de forma correcta de la base de datos</h4>
							<i>En cinco segundo se te redireccionará a la pagina para poder iniciar sesion</i>";
					$insertarCredenciales = $con->prepare("Insert into credenciales values (0, :email, :passwd)");
					$insertarCredenciales->bindValue(":email", $correo, PDO::PARAM_STR);
					$insertarCredenciales->bindValue(":passwd", $passwd, PDO::PARAM_STR);
					$insertarCredenciales->execute();
					header("Refresh:5; url=./logueoCliente.html", true, 303);
				} else {
					echo "<h4>No se ha registrado el cliente con nombre: <i>$nombreC</i> de forma correcta de la base de datos</h4>";
				}
			}
		}catch (PDOException $e) {
			echo 'Error: '.$e->getMessage();
		}
	}
	
	function validarDatos($campo){
		if (empty($campo)){
			return false;
		} 
		return true;
	} 

	function reescribir(){
		$nombre = $_POST['nombre'];
		$direccion = $_POST['direccion'];
		$cp = $_POST['cp'];
		$correo = $_POST['correo'];
		$passwd = $_POST['passwd'];
		$telefono = $_POST['telefono'];
		$tarjetas = $_POST['tarjetas'];
		$vnumTarjeta = $_POST['numTarjeta'];
		$fechaCad = $_POST['fechaCad'];
		$fechaActual = new DateTime('now');
		$fecha = $fechaActual->format("Y-m-d");
		$fechaActual = new DateTime('now');
		$fecha = $fechaActual->format("Y-m-d");
		$fechaActPart = explode("-", $fecha);
		$fechaRecibida = explode("-", $fechaCad);

		if (!validarDatos($nombre)){
$delimitador = <<<xxx
		<form action = "registrarCliente.html" method  = "post">
			Nombre: 
				<input type = "text" name = "nombre" placeholder = "Maria"/><br>
				<label style = "color: red">Este campo no puede estar vacio</label><br><br>
xxx;
		} else {
$delimitador = <<<xxx
			<form action = "registrarCliente.html" method  = "post">
				Nombre: $nombre<br><br>
xxx;
		}
		
		if (!validarDatos($direccion)){
$delimitador .= <<<xxx
			Direccion: 
				<input type = "text" name = "direccion" placeholder = "c\las flores"/><br><br>
				<label style = "color: red">Este campo no puede estar vacio</label><br><br>
xxx;
		} else {
$delimitador .= <<<xxx
			Direccion: $direccion<br><br>
xxx;
		}
		
		if (!validarDatos($cp)){
$delimitador .= <<<xxx
			CP: 
				<input type = "text" name = "cp" placeholder = "45223"/><br><br>
				<label style = "color: red">Este campo no puede estar vacio</label><br><br>
xxx;
		} else {
$delimitador .= <<<xxx
			CP: $cp<br><br>
xxx;
		}
		
		if (!validarDatos($correo)){
$delimitador .= <<<xxx
			Email: 
				<input type = "emial" name = "correo" placeholder = "correoejemplo@gmail.com"/><br><br>
				<label style = "color: red">Este campo no puede estar vacio</label><br><br>
xxx;
		} else {
$delimitador .= <<<xxx
			Email:  $correo<br><br>
xxx;
		}
		
		if (!validarDatos($passwd)){
$delimitador .= <<<xxx
				Contraseña: 
				<input type = "password" name = "passwd"/><br><br>
				<label style = "color: red">Este campo no puede estar vacio</label><br><br>
xxx;
		}
		
		if (!validarDatos($telefono)){
$delimitador .= <<<xxx
			Teléfono: 
				<input type = "text" name = "telefono" placeholder = "660214259"/><br><br>
				<label style = "color: red">Este campo no puede estar vacio</label><br><br>
xxx;
		} else {
$delimitador .= <<<xxx
			Teléfono:  $telefono<br><br>
xxx;
		}
		
		if ($tarjetas == ""){
$delimitador .= <<<xxx
			Tipo de tarjeta: 
				<select mame = "tarjetas" >
					<option value = ""></option>
					<option value = "debito">Debito</option>
					<option value = "credito">Credito</option>
					<option value = "prepago">Prepago</option>
					<option value = "revolving">Revolving</option>
				</select><br><br>
				<label style = "color: red">Este campo no puede estar vacio</label><br><br>
xxx;
		} else {
$delimitador .= <<<xxx
			Tipo de tarjeta: $tarjetas<br><br>
xxx;
		}
		
		if (!validarDatos($vnumTarjeta)){
$delimitador .= <<<xxx
			Numero tarjeta de crédito:
				<input type = "text" name = "numTarjeta" placeholder = "1234567891234567" maxlength = "16" minlength = "16"/><br><br>
				<label style = "color: red">Este campo no puede estar vacio</label><br><br>
xxx;
		}
		
		if ((!validarDatos($fechaCad)) || (($fechaActPart[0] <= $fechaRecibida[0]) || ($fechaActPart[1] <= $fechaRecibida[1]))){
$delimitador .= <<<xxx
			Fecha de caducidad:
				<input type = "date" name = "fechaCad"/><br><br>
				<label style = "color: red">Este campo no puede estar vacio o la fecha debe de ser superior a la actual</label><br><br>
xxx;
		} else {
$delimitador .= <<<xxx
				Fecha de caducidad: $fechaCad<br><br>
xxx;
		}
$delimitador .= <<<xxx
				<input type = 'submit' value = "Volver a inscribirse"/>
			</form>
xxx;

	echo $delimitador;	
		
	}
?>