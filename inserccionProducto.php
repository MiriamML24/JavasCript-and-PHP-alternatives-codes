<! DOCTYPE html">
<html xmlns= "http://www.w3.org/1999/xhtml" xml:lang="es" lang= "es"/>
	<head>
		<meta http-equiv="Content-Type" content="text/html charset=UTF-8"/>
		<link href="./css/tiendaVirtual.css" rel="stylesheet" type = "text/css">	
		<title>Tienda virtual</title>
	</head>
	
	<body>
		<ul>
			<li>
				<a href="indiceAdministrador.html">Volver</a>
			</li>
		</ul>

	</body>
</html>
<?php

	echo "<div id = 'contenedor'><h1>Añadir producto</h1>";
	error_reporting(0);
	$nombreProducto = $_POST['nombreProducto'];
	$descripProducto = $_POST['descripProducto'];
	$precioProducto = $_POST['precioProducto'];
	$ivaProducto = $_POST['ivaProducto'];
	$stockProducto = $_POST['stockProducto'];
	
	if ((validarDatos($nombreProducto)) && (validarDatos($descripProducto)) && ($precioProducto > 0) && ($ivaProducto > 0) && ($stockProducto > 0) && (validarFoto())){
		insertarProducto();
	} else { 
		reescribirFormulario();
	} 
	
	function insertarProducto(){
		$nombreProducto = $_POST['nombreProducto'];
		$descripProducto = $_POST['descripProducto'];
		$precioProducto = $_POST['precioProducto'];
		$ivaProducto = $_POST['ivaProducto'];
		$stockProducto = $_POST['stockProducto'];
		$nombreFichero = $_FILES['imagen']['name'];
		
		try{
			$con = new PDO ('mysql:dbname=tienda;host=localhost', 'root', 'root');
			$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$con->exec("set names utf8");
			$ultimoProducto = $con->query("Select productoId from productos order by 1 desc limit 1");
			$ultimoId = $ultimoProducto->fetch(PDO::FETCH_ASSOC);
			$contador = $ultimoId['productoId'] + 1;
			
			$insertar = $con->prepare('Insert into productos values (:productId, :nombreP, :descripcionP, :precioP, :ivaP, :img, :stock)');
			$insertar->bindValue(":productId", $contador, PDO::PARAM_INT);
			$insertar->bindValue(":nombreP", $nombreProducto, PDO::PARAM_STR);
			$insertar->bindValue(":descripcionP", $descripProducto, PDO::PARAM_STR);
			$insertar->bindValue(":precioP", $precioProducto);
			$insertar->bindValue(":ivaP", $ivaProducto, PDO::PARAM_INT);
			$insertar->bindValue(":img", $nombreFichero, PDO::PARAM_STR);
			$insertar->bindValue(":stock", $stockProducto, PDO::PARAM_INT);
			$insertar->execute();
			if ($insertar->rowCount() == 1)
				echo "<h4>El producto <i>$nombreProducto</i> se ha insertado de forma correcta</h4>";
				header("Location: listarProductos.php")
			else {
				echo "<h4>No se ha insertado el producto con el nombre <i>$nombreProducto</i></h4>";
			}
		}catch (PDOException $e) {
			echo 'Error: '.$e->getMessage();
		}
	} 
	
	function validarDatos($campo){
		if (empty($campo)){
			return false;
		} 
		return true;
	} 
	
	function validarFoto(){
		$fotoLocal = $_FILES['imagen']['tmp_name'];
		if (is_uploaded_file($_FILES['imagen']['tmp_name'])){
			$nombreDirectorio = ".\\img\\";
			$nombreFichero = $_FILES['imagen']['name'];
			$nombreCompleto = $nombreDirectorio.$nombreFichero;
			if (is_dir($nombreDirectorio)){
				$nombreCompleto = $nombreDirectorio.$nombreFichero;
				move_uploaded_file($fotoLocal,$nombreCompleto);
				return true;	
			}
		}
		return false;		
	}
	
	function reescribirFormulario(){
		$nombreProducto = $_POST['nombreProducto'];
		$descripProducto = $_POST['descripProducto'];
		$precioProducto = $_POST['precioProducto'];
		$ivaProducto = $_POST['ivaProducto'];
		$stockProducto = $_POST['stockProducto'];
		
		if (!validarDatos($nombreProducto)){
$delimitador = <<<xxx
		<form action = "insertarProducto.html" method  = "post">
				Nombre del producto: 
				<input type = "text" name = "nombreProducto" /><br>
				<label style = "color: red">Este campo no puede estar vacio</label><br><br>
xxx;
		} else {
$delimitador = <<<xxx
			<form action = "insertarProducto.html" method  = "post">
				Nombre del producto: $nombreProducto<br><br>
xxx;
		}
		
		if (!validarDatos($descripProducto)){
$delimitador .= <<<xxx
				Descripcion del producto: 
				<input type = "text" name = "descripProducto"/><br>
				<label style = "color: red">Este campo no puede estar vacio</label><br><br>
xxx;
		} else {
$delimitador .= <<<xxx
			Descripcion del producto:  $descripProducto<br><br>
xxx;
		}
		
		if ($precioProducto <= 0){
$delimitador .= <<<xxx
				Precio del producto: 
				<input type = "number" name = "precioProducto" step = "any"/><br>
				<label style = "color: red">Este campo no puede estar vacio</label><br><br>
xxx;
		} else {
$delimitador .= <<<xxx
				Precio del producto: $precioProducto<br><br>
xxx;
		}
		
		if ($ivaProducto <= 0){
$delimitador .= <<<xxx
				IVA del producto:
				<input type = "number" name = "ivaProducto "/><br>	
				<label style = "color: red">Este campo no puede estar vacio</label><br><br>
xxx;
		} else {
$delimitador .= <<<xxx
				IVA del producto: $ivaProducto<br><br>	
xxx;
		}
		
		if ($stockProducto <= 0){
$delimitador .= <<<xxx
				Stock del producto:
				<input type = "number" name = "stockProducto "/><br>	
				<label style = "color: red">Este campo no puede estar vacio</label><br><br>
xxx;
		} else {
$delimitador .= <<<xxx
				Stock del producto: $stockProducto<br><br>	
xxx;
		}
		
		if(!validarFoto()){
$delimitador .= <<<xxx
			<input type = "file" name = "imagen"/><br>
			<label style = 'color: red;'>El tamaño de la foto es mayor de 5MB y extension distinta de gif, jpeg o png</label><br><br>
xxx;
		} else {
$delimitador .= <<<xxx
				<label>Fichero subido con el nombre: $nombreFichero </label><br>
				<label>Nombre original: $fotoNombre</label><br>
				<label>Tamaño: $fotoSize</label><br>
				<label>Tipo de imagen: $fotoTipo</label><br>
xxx;
		}
$delimitador .= <<<xxx
		<input type = "submit" name = "registro" value = "Volver insertar producto"/>
		</form>
xxx;
		echo $delimitador;
		
	} 
?>