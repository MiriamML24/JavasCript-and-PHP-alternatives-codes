
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
				<a href = "listadoProductos.php">Listado de productos</a>
			</li>
		</ul>

	</body>
</html>
<?php<?php
	echo "<div id = 'contenedor'><h1>Eliminar articulo</h1>";
	$productoId = $_POST["productoId"];
	
	if(!isset($_SESSION['identificado'])){
		session_start();
		try{
			$con = new PDO ('mysql:dbname=tienda;host=localhost', 'root', 'root');
			$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$con->exec("set names utf8");
			
			$productoCesta = $con->prepare("Select count(*) as encesta from itemcesta where productoId like :producto and clienteId like :cliente");
			$productoCesta->bindValue(":producto", $productoId, PDO::PARAM_INT);
			$productoCesta->bindValue(":cliente", $_SESSION['identificado'], PDO::PARAM_INT);
			$productoCesta->execute();
			$productoExiste = $productoCesta->fetch(PDO::FETCH_ASSOC);
			
			if ($productoExiste["encesta"] == 0){
				echo "<h4>No se puede se puede quitar un producto que no existe en la cesta</h4>";
			} else {
				$cantProducto = $con->prepare("Select cantidad from itemcesta where productoId like :producto and clienteId like :id");
				$cantProducto->bindValue(":id", $_SESSION['identificado'], PDO::PARAM_INT);
				$cantProducto->bindValue(":producto", $productoId, PDO::PARAM_INT);
				$cantProducto->execute();
				$productoCant = $cantProducto->fetch(PDO::FETCH_ASSOC);
				
				$actualizarStock = $con->prepare("Update productos set stock = (stock + :cant) where productoId like :producto");
				$actualizarStock->bindValue(":producto", $productoId, PDO::PARAM_INT);
				$actualizarStock->bindValue(":cant", $productoCant['cantidad']);
				$actualizarStock->execute();
				
				$borrarProducto = $con->prepare("Delete from itemCesta where productoId like :producto and clienteId like :id");
				$borrarProducto->bindValue(":id", $_SESSION['identificado'], PDO::PARAM_INT);
				$borrarProducto->bindValue(":producto", $productoId, PDO::PARAM_INT);
				$borrarProducto->execute();

				if ($borrarProducto->rowCount() == 1){
					header("Location:verCarrito.php");
				} else {
					echo "<h4>No se ha podido insertar el producto en el carrito</h4>";
				}	
			}
		}catch (PDOException $e){
				echo $e->getMessage();
		} 
	} else {
		header("Location:indice.html");
	}
	
?> 