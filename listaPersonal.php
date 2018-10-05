<?php
	// Declaramos el header como aplicación json y que interprete los caracteres especial UTF-8
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
 
	// Conectamos a la base de datos y hacemos un select
	$conn = new mysqli("localhost", "root", "", "personal");
	$result = $conn->query("Select * from trabajadores");
	$outp = "";
  
	// Formateamos nuestro JSON
	while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
		if ($outp != "") {
			$outp .= ",";
		}
			$outp .= '{"nombre":"'. $rs["nombre"] . '",';
			$outp .= '"apellido1":"'. $rs["primerApellido"]. '",';
			$outp .= '"apellido2":"'. $rs["segundoApellido"]. '",';
			$outp .= '"email":"'. $rs["email"]. '"}';
		}
	$outp ='{"records":['.$outp.']}';
	$conn->close();
	echo($outp);
?>