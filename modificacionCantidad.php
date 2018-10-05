<?php
	echo "<h1 style = 'color:blue'>Modificación de la cantidad</h1>";
	$productoId = $_POST["productoId"];
	$cantidadProducto = $_POST["cantidadProducto"];
	
	if(!isset($_SESSION['identificado'])){
		session_start();
		if ($cantidadProducto <= 0){
			echo "<h4>Si modificas la cantidad a cero quizás deberias de quitar el producto de la lista</h4>
				<i>Esta página se redicionará de forma automática en 2 segundos</i></h4>";
			header("Refresh:2; url=./verCarrito.php", true, 303);
		} else {
			try{
				$con = new PDO ('mysql:dbname=tienda;host=localhost', 'root', 'root');
				$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$con->exec("set names utf8");
				
				$actuazlizarCantidad = $con->prepare("Update itemcesta set cantidad = :cant where productoId like :producto and clienteId like :cliente");
				$actuazlizarCantidad->bindValue(":producto", $productoId, PDO::PARAM_INT);
				$actuazlizarCantidad->bindValue(":cant", $cantidadProducto, PDO::PARAM_INT);
				$actuazlizarCantidad->bindValue(":cliente", $_SESSION['identificado'], PDO::PARAM_INT);
				$actuazlizarCantidad->execute();
				
				if ($actuazlizarCantidad->rowCount() == 1){
					$cambiarStock = $con->prepare("Update productos set stock = (stock - :cant) where productoId like :producto");
					$cambiarStock->bindValue(":producto", $productoId, PDO::PARAM_INT);
					$cambiarStock->bindValue(":cant", $cantidadProducto, PDO::PARAM_INT);
					$cambiarStock->execute();
					header("Location:verCarrito.php");
				} else {
					echo "<h4>No se ha podido actualizar la cantidad del producto insertar el producto en el carrito</h4>";
					echo "<form method = 'post' action = 'listadoProductos.php'>
							<input type = 'submit' value = 'Seguir comprando'/>
							</form><br><br>";
				}	
			}catch (PDOException $e){
					echo $e->getMessage();
			} 
		}
	} else {
		header("Location:indice.html");
	}
	
?> 