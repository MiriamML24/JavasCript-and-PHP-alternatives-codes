<?php
	// Declaramos el header como aplicación json y que interprete los caracteres especial UTF-8
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	
	try {
		// Conectamos a la base de datos y hacemos un select
		$conn = new PDO('mysql:dbname=bbdd;host=localhost', 'root', '');
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$conn->exec("set names utf8");
		
		/* Muestra las cabecera y características de una tabla */
		$result = $conn->query("Show columns from mdl_capabilities");
		$cadena = "";
		
		/* Se recorre los datos y se guarda en una cadena */
		while($row = $result->fetch(PDO::FETCH_ASSOC)){
			if ($cadena != "") {
				$cadena .= ",";
			}
			$cadena .= '{"field":"'.$row["Field"].'"}';
		}
		
		/* La cadena se convierte en array */
		$cadena ='['.$cadena.']';
		/* Paso la cadena al js */
		echo $cadena;
		
	}catch(PDOException $e){
		echo 'Error ' .$e->getMessage();
	} 
	
?>