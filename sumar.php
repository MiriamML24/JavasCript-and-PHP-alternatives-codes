<?php 
	
	$nameColumns = $_GET['nameColumns'];
	
	try {
		//Crea conexion
		$con = new PDO ('mysql:dbname=jardineria;host=localhost', 'root', '');
		$con -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$con -> exec("set names utf8");
		
		
		$query ="SELECT SUM($nameColumns) as suma FROM pedidos limit 1";
		$stmt1 = $con->query($query);
	
		//Muestra el resultado de la consulta
		$result = $stmt1->fetchAll();
		$arrayData = array('result'=>$result);
		echo json_encode($arrayData); 

	}catch (PDOException $e) {
		echo 'Error: '.$e->getMessage();
	}

?>