
<! DOCTYPE html">
<html xmlns= "http://www.w3.org/1999/xhtml" xml:lang="es" lang= "es"/>
	<head>
		<meta http-equiv="Content-Type" content="text/html charset=UTF-8"/>
		<link href="./css/tiendaVirtual.css" rel="stylesheet" type = "text/css">	
		<title>Tienda virtual</title>
	</head>
	
	<body>
		<ul>
			<li><a href="finalizarCompra.html">Volver</a></li>
		</ul>
	</body>
</html>
<?php
	echo "<div id = 'contenedor'><h1>Finalizacion de la compra</h1>";
	$medioPago = $_POST["medioPago"];
	$tipoEnvio = $_POST["tipoEnvio"];
	$fechaActual = new DateTime('now');
	$fechaEnvio = new DateTime('now');
	$fechaResultante = $fechaEnvio->add(new DateInterval('P3D'));
	
	if(!isset($_SESSION['identificado'])){
		session_start();
		try{
			$con = new PDO ('mysql:dbname=tienda;host=localhost', 'root', 'root');
			$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$con->exec("set names utf8");
			
			$insertarCesta = $con->prepare("Insert into cesta values (:cliente, :fecha, :tipoEnvio, :medioPago)");
			$insertarCesta->bindValue(":cliente", $_SESSION['identificado'], PDO::PARAM_INT);
			$insertarCesta->bindValue(":fecha", $fechaActual->format("Y-m-d"));
			$insertarCesta->bindValue(":tipoEnvio", $tipoEnvio, PDO::PARAM_STR);
			$insertarCesta->bindValue(":medioPago", $medioPago, PDO::PARAM_STR);
			$insertarCesta->execute();
			
			$itemCesta = $con->prepare("Select * from itemCesta where clienteId like :cliente");
			$itemCesta->bindValue(":cliente", $_SESSION['identificado'], PDO::PARAM_INT);
			$itemCesta->execute();
			
			$precioTotal = $con->prepare("select sum(((itemcesta.cantidad * productos.precio * (100 - iva))/100) + (itemcesta.cantidad * productos.precio)) as total, sum((itemcesta.cantidad * productos.precio*(100-iva))/100) as iva
										from itemcesta
										inner join productos on itemcesta.productoId = productos.productoId
										where itemcesta.clienteId like :cliente");
			$precioTotal->bindValue(":cliente", $_SESSION['identificado'], PDO::PARAM_INT);
			$precioTotal->execute();
			$total = $precioTotal->fetch(PDO::FETCH_ASSOC);
			
			$insertarPedidos = $con->prepare("Insert into pedidos values (0, :cliente, :fecha, :medioPago, :tipoEnvio, :totalPedido, :totalIva, :fechaEnvio)");
			$insertarPedidos->bindValue(":cliente", $_SESSION['identificado'], PDO::PARAM_INT);  
			$insertarPedidos->bindValue(":fecha", $fechaActual->format("Y-m-d"));
			$insertarPedidos->bindValue(":medioPago", $medioPago, PDO::PARAM_STR);
			$insertarPedidos->bindValue(":tipoEnvio", $tipoEnvio, PDO::PARAM_STR);
			$insertarPedidos->bindValue(":totalPedido", $total['total']);
			$insertarPedidos->bindValue(":totalIva", $total['iva']);
			$insertarPedidos->bindValue(":fechaEnvio", $fechaResultante->format("Y-m-d"));
			$insertarPedidos->execute();
			
			while($productos = $itemCesta->fetch(PDO::FETCH_ASSOC)){
				$buscarProducto = $con->prepare("Select * from productos where productoId like :producto");
				$buscarProducto->bindValue(":producto", $productos['productoId'], PDO::PARAM_INT); 
				$buscarProducto->execute();
				$producto = $buscarProducto->fetch(PDO::FETCH_ASSOC);
				
				$ultimoPedido = $con->prepare("Select * from pedidos where clienteId like :cliente order by 1 desc limit 1");
				$ultimoPedido->bindValue(":cliente", $_SESSION['identificado'], PDO::PARAM_INT); 
				$ultimoPedido->execute();
				$pedidoUltimo = $ultimoPedido->fetch(PDO::FETCH_ASSOC);

				$productosItem = $con->prepare("Insert into itemPedido values (0, :pedido, :producto, :unidades, :precio, :iva)");
				$productosItem->bindValue(":pedido", $pedidoUltimo['pedidoId'], PDO::PARAM_INT);
				$productosItem->bindValue(":producto", $productos['productoId'], PDO::PARAM_INT);
				$productosItem->bindValue(":unidades", $productos['cantidad'], PDO::PARAM_INT);
				$productosItem->bindValue(":precio", $producto['precio'], PDO::PARAM_INT);
				$productosItem->bindValue(":iva", $producto['iva'], PDO::PARAM_INT);
				$productosItem->execute();
				
			}
			$borrarCesta = $con->prepare("Delete from itemcesta where clienteId like :cliente");
			$borrarCesta->bindValue(":cliente", $_SESSION['identificado'], PDO::PARAM_INT);  
			$borrarCesta->execute();
			header("Location:mostrarPedidos.php");
			
		}catch (PDOException $e){
				echo $e->getMessage();
		} 
	} else {
		header("Location:indice.html");
	}
	
?> 