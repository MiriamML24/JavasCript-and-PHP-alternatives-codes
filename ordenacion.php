<?php
	// Declaramos el header como aplicación json y que interprete los caracteres especial UTF-8
	/* header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8"); */

	$cadena = $_GET['cadena'];
	$valor = $_GET['valor'];
	$tipoOrdenacion = $_GET['tipoOrdenacion'];

	if ($cadena == "undefined"){
		$cadena = "";
	}
	
	$sql = "Select * from trabajadores where nombre like '%".$cadena."%' or primerApellido like '%".$cadena."%' 
			or segundoApellido like '%".$cadena."%' or email like'%".$cadena."%'";
	
	switch($valor){	
		case 1: 
			$sql .= " order by nombre ";
			break;
		case 2:
			$sql .= " order by primerApellido ";
			break;
		case 3:
			$sql .= " order by segundoApellido ";
			break;
		case 4:
			$sql .= " order by email ";
			break;
	}
	
	if($tipoOrdenacion == 0){
		$sql .= " desc";
	} else {
		$sql .= " asc";
	}
	
	$conn = new mysqli("localhost", "root", "", "personal");
	// Conectamos a la base de datos y hacemos un select
	$result = $conn->query($sql);
	$outp = "";
  
	// Formateamos nuestro JSON
	while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
		if ($outp != "") {
			$outp .= ",";
		}
		$outp .= '{"nombre":"'  . $rs["nombre"] . '",';
		$outp .= '"apellido1":"'   . $rs["primerApellido"]        . '",';
		$outp .= '"apellido2":"'. $rs["segundoApellido"]     . '",';
		$outp .= '"email":"'   . $rs["email"]        . '"}';
	}
	$outp ='{"records":['.$outp.']}';
	$conn->close();

	echo($outp);
?>