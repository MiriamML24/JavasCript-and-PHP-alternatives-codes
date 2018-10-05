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
				<a href="eliminarProductos.php">Volver</a>
			</li>
		</ul>

	</body>
</html>
<?php
	echo "div id = 'contenedor'><h1 style = 'color:blue'>Eliminacion del producto</h1>";
	$nombreProducto = $_POST['nombreProducto'];
	try{
		$con = new PDO ('mysql:dbname=tienda;host=localhost', 'root', 'root');
		$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$con->exec("set names utf8");
		$productoElimindo = $con->prepare("Delete from productos where nombre like :nombreP");
		$productoElimindo->bindValue(":nombreP", $nombreProducto, PDO::PARAM_STR);
		$productoElimindo->execute();
		
		if ($productoElimindo->rowCount() > 0){
			header("Location: listarProductos.php");
		} else {
			echo "<h4>No se ha eliminado ningún producto con el nombre: <i>$nombreProducto</i> de forma correcta de la base de datos</h4>";
		}
	}catch (PDOException $e) {
		echo 'Error: '.$e->getMessage();
	}

?>