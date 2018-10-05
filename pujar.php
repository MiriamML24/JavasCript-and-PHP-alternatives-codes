<?php	
	session_start();
	$fecha = new DateTime('now');
	
$delimitador = <<<xxx
	<h1 style = "color:blue">Pujar por un producto</h1>
	<form action = "hacerPuja.php" method = "post">
		Dinero que vas a pujar: 
			<input type = "number" name = "dinero" step = "any" min = "0.0" placeholder = "0.0"/><br><br>
		Productos a pujar:		
			<select name = "productos">
				<option value = ''></option>
xxx;
	echo $delimitador;
	try{
			$con = new PDO ('mysql:dbname=subastas;host=localhost', 'root', 'root');
			$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$con->exec("set names utf8");
			
			$listaArticulos = $con->prepare('Select * from articulos');
			$listaArticulos->bindValue(":fecha", $fecha->format("Y-m-d"), PDO::PARAM_STR);
			$listaArticulos->execute();
			
			while ($articulosListados = $listaArticulos->fetch(PDO::FETCH_ASSOC)){
				echo "<option value = '$articulosListados[articulo]'>".$articulosListados['descripcion']."</option>";
			}
		 
		}catch (PDOException $e){
				echo $e->getMessage();
		} 
	echo '</select><br><br>
			<input type = "submit" value = "Pujar"/>
		</form>';
?>