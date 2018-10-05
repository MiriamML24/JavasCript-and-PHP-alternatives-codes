<?php 
	header('Content-Type: application/json');
	
	$pagenum = $_GET['page'];
	$pagesize = $_GET['size'];
	
	$offset = ($pagenum - 1) * $pagesize;
	
	
	$usuario = 'root';
	$pwd = '';
	
	try {
		//Crea conexion
		$con = new PDO ('mysql:dbname=jardineria;host=localhost', $usuario, $pwd);
		$con -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$con -> exec("set names utf8");
		
		//Consulta 
		$stmt1 = $con -> prepare("SELECT * FROM pedidos LIMIT $offset, $pagesize");
		$stmt1 -> execute();
		
		//Muestra el resultado de la consulta
		$result = $stmt1->fetchAll();
		
		//Obtiene la cantidad de filas que hay
		$stmt2 = $con->prepare("SELECT count(*) AS count FROM pedidos");
		$stmt2 -> execute();
		$row = $stmt2->fetch(PDO::FETCH_ASSOC);
		$count = $row['count'];
		
		$arrayData = array('result'=>$result, 'totalCount'=>$count);
		
		echo json_encode($arrayData);
		
	}catch (PDOException $e) {
		echo 'Error: '.$e->getMessage();
	}
?>