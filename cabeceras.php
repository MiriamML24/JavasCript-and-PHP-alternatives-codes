<?php 
	header('Content-Type: application/json');
	
	$usuario = 'root';
	$pwd = '';
	
	$count = 0;
	try {
		//Crea conexion
		$con = new PDO ('mysql:dbname=jardineria;host=localhost', $usuario, $pwd);
		$con -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$con -> exec("set names utf8");
	
		//Consulta 
		$stmt1 = $con -> prepare("SHOW COLUMNS FROM pedidos");
		$stmt1 -> execute();
		
		$outp = "";
			//Muestra el resultado de la consulta
			while ($fila = $stmt1->fetch(PDO::FETCH_ASSOC)) {
				if ($outp != "") {
					$outp .= ",";
				}
				$outp .= '{"field" : "'.$fila['Field'].'", "type" : "'.$fila['Type'].'", "numberColumn" : "'.$count.'", "url" : " " , "group" : "';
				if($count >= 1 && $count <= 3) {
					$outp .= "Fechas";
					if( $count == 1 || $count == 2) {
						$outp .= ",Estimacion";
					}
				}
				if($count == 4 || $count == 5) {
					$outp .= "Detalles";
				}
				$outp .= '"';
				$type = explode("(",$fila['Type']);
				 if( $type[0] == "char" || $type[0] == "varchar" || $type[0] == "tinytext" || $type[0] == "text" || $type[0] == "blob" || $type[0] == "mediumtext" || $type[0] == "longtext" || $type[0] == "longblob" || $type[0] == "enum" || $type[0] == "set" || $type[0] == "string") {
					 $stmt2 = $con -> prepare("SELECT distinct(".$fila['Field'].") FROM pedidos");
					 $stmt2 -> execute();
					 $resultSelect = $stmt2->fetchAll();
					 $outp .= ', "opciones" : '.json_encode($resultSelect).'';
				 }
				 
				 if($fila['Field'] == "Comentarios") {
					 $outp .= ', "anchura" : "min-width: 750px; max-width: 750px" ';
				 } else if($fila['Field'] == "CodigoPedido" || $fila['Field'] == "CodigoCliente" ) {
					 $outp .= ', "anchura" : "min-width: 150px; max-width: 150px" ';
				 }  else {
					 $outp .= ', "anchura" : "min-width: 200px; max-width: 200px" ';
				 }
				 
				 if($fila['Field'] == "CodigoPedido") { 
					$outp .= ', "fixed" : "columnFixed" ';
				 }
				 
				$outp .= ' }';
				$count++;
			}
			
		$outp ='['.$outp.']';
		echo $outp;
		
	}catch (PDOException $e) {
		echo 'Error: '.$e->getMessage();
	}
?>