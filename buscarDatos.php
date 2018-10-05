<?php
	// Declaramos el header como aplicación json y que interprete los caracteres especial UTF-8
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");

	$cadena = $_GET['cadena'];
	
	// Conectamos a la base de datos y hacemos un select
	$conn = new mysqli("localhost", "root", "", "personal");
	$result = $conn->query("Select * from trabajadores where nombre like '%".$cadena."%' or primerApellido like '%".$cadena."%' 
							or segundoApellido like '%".$cadena."%' or email like'%".$cadena."%'");
	$outp = "";
  
	// Formateamos nuestro JSON
	while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
		if ($outp != "") {
			$outp .= ",";
		}
		$outp .= '{"nombre":"'  . $rs["nombre"] . '",';
		$outp .= '"apellido2":"'   . $rs["primerApellido"]        . '",';
		$outp .= '"apellido2":"'. $rs["segundoApellido"]     . '",';
		$outp .= '"email":"'   . $rs["email"]        . '"}';
	}
	$outp ='{"records":['.$outp.']}';
	$conn->close();

	echo($outp);
?>