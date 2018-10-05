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
				<a href="indiceCliente.html">Volver</a>
			</li>
		</ul>

	</body>
</html>
<?php
	echo "<div id = 'contenedor'><h1>Insertado carrito</h1>";
	$cantidadProducto = $_POST["cantidadProducto"];
	$productoId = $_POST["productoId"];
	
	if(!isset($_SESSION['identificado'])){
		session_start();
		try{
			$con = new PDO ('mysql:dbname=tienda;host=localhost', 'root', 'root');
			$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$con->exec("set names utf8");

			$stockActual = $con->prepare("Select stock from productos where productoId like :producto");
			$stockActual->bindValue(":producto", $productoId);
			$stockActual->execute();
			$stock = $stockActual->fetch(PDO::FETCH_ASSOC);
			
			if (($cantidadProducto <= 0) || ($cantidadProducto > $stock['stock'])){
				echo "<h4>Debes de cambiar la cantidad de producto que has añadido a la cesta <br><br>
					<i>Esta página se redicionará de forma automática en 2 segundos</i></h4>";
				header("Refresh:2; url=./listadoProductos.php", true, 303);
			} else {
				$productoCesta = $con->prepare("Select count(*) as encesta from itemcesta where productoId like :producto and clienteId like :cliente");
				$productoCesta->bindValue(":producto", $productoId, PDO::PARAM_INT);
				$productoCesta->bindValue(":cliente", $_SESSION['identificado'], PDO::PARAM_INT);
				$productoCesta->execute();
				$productoExiste = $productoCesta->fetch(PDO::FETCH_ASSOC);
				
				if ($productoExiste["encesta"] == 0){
					$insertar = $con->prepare("Insert into itemcesta value (0, :id, :producto, :cant)");
					$insertar->bindValue(":id", $_SESSION['identificado'], PDO::PARAM_INT);
					$insertar->bindValue(":producto", $productoId, PDO::PARAM_INT);
					$insertar->bindValue(":cant", $cantidadProducto);			
					$insertar->execute();
					
					if ($insertar->rowCount() == 1){
						$actualizarStock = $con->prepare("Update productos set stock = (stock - :cant) where productoId like :producto");
						$actualizarStock->bindValue(":producto", $productoId, PDO::PARAM_INT);
						$actualizarStock->bindValue(":cant", $cantidadProducto);
						$actualizarStock->execute();
						header("Location:verCarrito.php");
					} else {
						echo "<h4>No se ha podido insertar el producto en el carrito</h4>
							<form method = 'post' action = 'listadoProductos.php'>
								<input type = 'submit' value = 'Seguir comprando'/>
							</form><br><br>
							<form method = 'post' action = 'finalizarCompra.php'>
								<input type = 'submit' value = 'Finalizar compra'/>
							</form><br><br>";
					}
				} else {
					$actualizarCantidad = $con->prepare("Update itemCesta set cantidad = (cantidad + :cant) where productoId like :producto and clienteId like :id");
					$actualizarCantidad->bindValue(":id", $_SESSION['identificado'], PDO::PARAM_INT);
					$actualizarCantidad->bindValue(":producto", $productoId, PDO::PARAM_INT);
					$actualizarCantidad->bindValue(":cant", $cantidadProducto);			
					$actualizarCantidad->execute();
					
					if ($actualizarCantidad->rowCount() == 1){
						$actualizarStock = $con->prepare("Update productos set stock = (stock - :cant) where productoId like :producto");
						$actualizarStock->bindValue(":producto", $productoId, PDO::PARAM_INT);
						$actualizarStock->bindValue(":cant", $cantidadProducto);
						$actualizarStock->execute();
						
						$cantidadTotal = $con->prepare("Select cantidad from itemcesta where productoId like :producto and clienteId like :id");
						$cantidadTotal->bindValue(":id", $_SESSION['identificado'], PDO::PARAM_INT);
						$cantidadTotal->bindValue(":producto", $productoId, PDO::PARAM_INT);
						$cantidadTotal->execute();
						$cantidad = $cantidadTotal->fetch(PDO::FETCH_ASSOC);
						header("Location:verCarrito.php");
					} else {
						echo "<h4>No se ha podido insertar el producto en el carrito</h4>
							<form method = 'post' action = 'listadoProductos.php'>
								<input type = 'submit' value = 'Seguir comprando'/>
							</form><br><br>
							<form method = 'post' action = 'finalizarCompra.php'>
								<input type = 'submit' value = 'Finalizar compra'/>
							</form><br><br>";
					}	
				}
			} 
		}catch (PDOException $e){
			echo $e->getMessage();
		}
	} else {
		header("Location:indice.html");
	}
	
?> 