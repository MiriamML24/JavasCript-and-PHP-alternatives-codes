<?php 
	$usuario = 'root';
	$pwd = '';
	
	try {
		//Crea conexion
		$con = new PDO ('mysql:dbname=jardineria;host=localhost', $usuario, $pwd);
		$con -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$con -> exec("set names utf8");
		
		//Consulta 
		$stmt1 = $con -> prepare("SELECT * FROM pedidos");
		$stmt1 -> execute();
		
		//Muestra el resultado de la consulta
		$result = $stmt1->fetchAll(PDO::FETCH_ASSOC);
		
		//Obtiene la cantidad de filas que hay
		$stmt2 = $con->prepare("SELECT count(*) AS count FROM pedidos");
		$stmt2 -> execute();
		$row = $stmt2->fetch(PDO::FETCH_ASSOC);
		$count = $row['count'];
		
		$arrayData = array('result'=>$result, 'name'=>"pedidos");
		
		echo json_encode($arrayData);
		
	}catch (PDOException $e) {
		echo 'Error: '.$e->getMessage();
	}
?>