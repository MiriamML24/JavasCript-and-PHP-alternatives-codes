<?php 
	// Declaramos el header como aplicación json y que interprete los caracteres especial UTF-8
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	
	$nombre = $_GET['nombre'];
	$apellido1 = $_GET['apellido1'];
	$apellido2 = $_GET['apellido2'];
	$email = $_GET['email'];
	
	echo $nombre." ". $apellido1 . " " .$apellido2 ." ". $email;
	
	$con = new mysqli("localhost", "root", "", "personal");
	if (mysqli_connect_errno()) {
		printf("Falló la conexión: %s\n", mysqli_connect_errno());
		exit();
	}
	
	$query = "Insert into trabajadores values ('$nombre', '$apellido1', '$apellido2', '$email')";
	if($con->query($query) == true){
		echo "se ha insertado de forma correcta";
	} else {
		echo $con->error;
	}
	$con->close();  
	
	/*$nombre = $_POST['nombre'];
	$primerApellido = $_POST['primerApellido'];
	$segundoApellido = $_POST['segundoApellido'];
	$email = $_POST['email'];
	
	//echo $nombre." ". $primerApellido . " " .$segundoApellido ." ". $email;
	
	$con = new mysqli("localhost", "root", "", "personal");
	if (mysqli_connect_errno()) {
		printf("Falló la conexión: %s\n", mysqli_connect_errno());
		exit();
	}
	
	$query = "Insert into trabajadores values ('$nombre', '$primerApellido', '$segundoApellido', '$email')";
	if($con->query($query) == true){
		echo "se ha insertado de forma correcta";
	} else {
		echo $con->error;
	}
	$con->close();  */

?>
