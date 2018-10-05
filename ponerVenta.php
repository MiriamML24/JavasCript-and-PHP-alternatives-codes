<?php	
$delimitador = <<<xxx
	<h1 style = "color:blue">Inscripcion de productos</h1>
	<form action = "inscribirProducto.php" method = "post">
		Descripcion del producto: 
			<input type = "text" name = "descripcion"/><br><br>
		Estado:		
			<select name = "estado">
				<option value = ""></option>
				<option value = "usado">Usado</option>
				<option value = "nuevo">Nuevo</option>
			</select><br><br>
		Fecha limite: 
			<input type = "date" name = "fechaLimit" /><br><br>
		Hora limite 
			<input type = "time" name = "horaLimit" /><br><br>
		Precio salida: 
			<input type = "number" name = "precioSalida" step = "any" min = "0.0" placeholder = "0.0"/><br><br>
		Categoria: 
			<select name = 'categoria'>
				<option value ''></option>
xxx;
	echo $delimitador;

		try{
			$con = new PDO ('mysql:dbname=subastas;host=localhost', 'root', 'root');
			$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$con->exec("set names utf8");
			
			$buscarVendedor = $con->query("Select * from categorias");
			echo "";
			while ($categoria = $buscarVendedor->fetch(PDO::FETCH_ASSOC)){
				echo "<option value = '$categoria[nombreCategoria]'>".$categoria['nombreCategoria']."</option>";
			}
			
		}catch (PDOException $e){
				echo $e->getMessage();
		} 
		echo '
			</select><br><br>
			<input type = "submit" value = "Poner a la venta"/></form>';
?>