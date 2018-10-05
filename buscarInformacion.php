<?php 
	
	//error_reporting(0);
	
	$arrayColumnNames = $_GET['stringColumnNames'];
	$arrayValues = $_GET['stringValues'];
	$arrayOrdenation = $_GET['stringOrdenation'];
	$arrayInputValues = $_GET['stringInputValues'];
	$arrayInputOptions = $_GET['stringInputOptions'];
	$arrayInputMaxValues = $_GET['stringInputMaxValues'];
	$arraySelectMultiple = $_GET['stringSelectMultiple'];
	
	$pagenum = $_GET['page'];
	$pagesize = $_GET['size'];
	
	$arrayColumnNames = explode("|", $arrayColumnNames);
	$arrayValues = explode("|", $arrayValues);
	$arrayOrdenation = explode("|", $arrayOrdenation);
	$arrayInputValues = explode("|", $arrayInputValues);
	$arrayInputOptions = explode("|", $arrayInputOptions);
	$arrayInputMaxValues = explode("|", $arrayInputMaxValues);
	
	$arraySelectMultiple = explode("|", $arraySelectMultiple);
	
	
	if(max($arraySelectMultiple) != "") {
		for($i = 0; $i < count($arraySelectMultiple); $i++) {
			$arraySelectMultiple[$i] = explode(";", $arraySelectMultiple[$i]);
		}
	}
	
	$offset = ($pagenum - 1) * $pagesize;
	
	$longitud = count($arrayColumnNames);
	
	$usuario = 'root';
	$pwd = '';
	
	try {
		//Crea conexion
		$con = new PDO ('mysql:dbname=jardineria;host=localhost', $usuario, $pwd);
		$con -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$con -> exec("set names utf8");
		
		
		$query = "SELECT * FROM pedidos ";
		
		
		$bandera = false;
		
		for($i = 0; $i < $longitud - 1; $i++) {
			if(!empty($arrayInputValues[$i]) && !$bandera) {
				$query .= " WHERE ";
			}
			
			if(!empty($arrayInputValues[$i])) {
				switch($arrayInputOptions[$i]) {
					case 1: $query .= " $arrayColumnNames[$i] LIKE '%$arrayInputValues[$i]%' ";  continue; //Contiene
					case 2: $query .= " $arrayColumnNames[$i] < '$arrayInputValues[$i]' ";  continue; //Menor que
					case 3: $query .= " $arrayColumnNames[$i] > '$arrayInputValues[$i]' ";  continue; //Mayor que
					case 4: $query .= " $arrayColumnNames[$i] BETWEEN '$arrayInputValues[$i]' AND '$arrayInputMaxValues[$i]' ";  continue; //Entre
					case 5: $query .= selectQuery($arrayColumnNames[$i], $i); continue; //Select multiple
					case 6: $query .= " $arrayColumnNames[$i] LIKE '$arrayInputValues[$i]%' ";  continue; //Empiece por
					case 7: $query .= " $arrayColumnNames[$i] LIKE '%$arrayInputValues[$i]' ";  continue; //Termine por
					case 8: $query .= " $arrayColumnNames[$i] LIKE '$arrayInputValues[$i]' ";  continue; //Igual a
					case 9: $query .= " $arrayColumnNames[$i] NOT LIKE '$arrayInputValues[$i]' ";  continue; //No igual
					case 10: $query .= " $arrayColumnNames[$i] NOT LIKE '%$arrayInputValues[$i]%' ";  continue; //No contiene
					case 11: $query .= " $arrayColumnNames[$i] <= '$arrayInputValues[$i]' ";  continue; //Menor o igual a
					case 12: $query .= " $arrayColumnNames[$i] >= '$arrayInputValues[$i]' ";  continue; //Mayor o igual a
					case 13: $query .= " $arrayColumnNames[$i] IS NULL ";  continue; //Es nulo
					case 14: $query .= " $arrayColumnNames[$i] IS NOT NULL ";  continue; //No es nulo
					
				}
				$bandera = true;
			}
			
			if(!empty($arrayInputValues[$i+1]) && $bandera ) {
				$query .= " AND ";
			}
		}
		
		$num = max($arrayOrdenation);
		$counter = 1;
		
		$activacion = 0;
		
		for($i = 0; $i < $longitud - 1; $i++) {
			
			for($j = 0; $j < $longitud; $j++) {
				if($arrayOrdenation[$j] == $i + 1) {
					
					if(($arrayValues[$j] == 1 || $arrayValues[$j] == 2) && $activacion == 0) {
						$query .= " ORDER BY ";
					}
					
					switch($arrayValues[$j]) {
						case 1: $query .= " $arrayColumnNames[$j] ASC "; $activacion = 1; continue;
						case 2: $query .= " $arrayColumnNames[$j] DESC "; $activacion = 1; continue;
						case 3: $query .= "";
					}
					
					$j = $longitud;
				}
			}

			if($counter < $num && $activacion == 1) {
				$counter++;
				$query .= " , ";
			}
			
		}
		
		$query2 = $query;
		
		$query .= " LIMIT $offset, $pagesize";
		
		//echo $query."\n\n";
		
		//Consulta 
		$stmt1 = $con -> prepare($query);
		$stmt1 -> execute();
		
		//Muestra el resultado de la consulta
		$result = $stmt1->fetchAll();
		
		$stmt2 = $con -> prepare($query2);
		$stmt2 -> execute();
		$count = $stmt2->rowCount();
		
		$arrayData = array('result'=>$result, 'totalCount'=>$count);
		
		echo json_encode($arrayData);
		
	}catch (PDOException $e) {
		echo 'Error: '.$e->getMessage();
	}

	
	function selectQuery($columnName, $position) {
		global $arraySelectMultiple;
		$string = " ( ";
			$longitud = count($arraySelectMultiple[$position]);
			$counter = 0;
			
			for($i = 0; $i < $longitud - 1; $i++) {
				
				$string .= " $columnName LIKE '".$arraySelectMultiple[$position][$i]."' ";
				
				if($counter < $longitud - 2){
					$string .= " OR ";
					$counter++;
				}
			}
			
		$string .= " ) ";
			return $string;
		
	}
?>