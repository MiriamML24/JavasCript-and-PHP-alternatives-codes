<?php

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
	
	$query = "Delete from trabajadores where nombre like '$nombre' and primerApellido like '$apellido1' and segundoApellido like '$apellido2' and email like '$email'";
	if($con->query($query) == true){
		echo "\nSe ha eliminado de forma correcta";
	} else {
		echo $con->error;
	}
	$con->close();   