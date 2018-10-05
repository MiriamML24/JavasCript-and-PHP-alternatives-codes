<! DOCTYPE html">
<html xmlns= "http://www.w3.org/1999/xhtml" xml:lang="es" lang= "es"/>
	<head>
		<meta http-equiv="Content-Type" content="text/html charset=UTF-8"/>
		<link href="./css/tiendaVirtual.css" rel="stylesheet" type = "text/css">	
		<title>Tienda virtual</title>
	</head>
	
	<body>
		<ul>
			<li><a href="indiceCliente.html">Volver</a></li>
			<li>
				<a href = "verCarrito.php">Ver carrito</a>
			</li>
			<li>
				<a href = "listadoProductos.php">Listado de productos</a>
			</li>
		</ul>

	</body>
</html>
<?php
	echo "<div id = 'contenedor'><h1>Modificar cantidad</h1>";
	$productoId = $_POST["productoId"];
	
	if(!isset($_SESSION['identificado'])){
		session_start();
		try{
			$con = new PDO ('mysql:dbname=tienda;host=localhost', 'root', 'root');
			$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$con->exec("set names utf8");
			
			$productoModificar = $con->prepare("Select productos.*, itemcesta.* from itemcesta 
												inner join productos on productos.productoId = itemcesta.productoId
												where itemcesta.productoId like :producto and clienteId like :cliente");
			$productoModificar->bindValue(":producto", $productoId, PDO::PARAM_INT);
			$productoModificar->bindValue(":cliente", $_SESSION['identificado'], PDO::PARAM_INT);
			$productoModificar->execute();
			$productoCant = $productoModificar->fetch(PDO::FETCH_ASSOC);
			
			$reestablecerStock = $con->prepare("Update productos set stock = (stock + :cantidad) where productoId like :producto");
			$reestablecerStock->bindValue(":cantidad", $productoCant['cantidad']);
			$reestablecerStock->bindValue(":producto", $productoId);
			$reestablecerStock->execute();
			
			$cantidadCesta = $con->prepare("Update itemcesta set cantidad = 0 where productoId like :producto and clienteId like :cliente");
			$cantidadCesta->bindValue(":producto", $productoId);
			$cantidadCesta->bindValue(":cliente", $_SESSION['identificado']);
			$cantidadCesta->execute();
			
			echo "<table style = 'text-align: center'><tr>
				<th style = 'background-color:#FFE4FD; width: 150px;'>Nombre del producto</th>
				<th style = 'background-color:#FFE4FD; width: 150px;'>Imagen del producto</th>
				<th style = 'background-color:#FFE4FD; width: 150px;'>Cantidad añadida</th></tr>
				<td style = 'background-color:#E4F3FF'>$productoCant[nombre]</td>
				<td style = 'background-color:#E4F3FF'><img src = './img/".$productoCant['imagen']."' width = 50 height = 50></img></td>
				<td style = 'background-color:#E4F3FF'>
					<form method = 'post' action =  'modificacionCantidad.php'>
					<input type = 'number' name = 'cantidadProducto' value = '$productoCant[cantidad]' style = 'width: 100px;'/>
					<input type = 'hidden' name = 'productoId' value = '$productoCant[productoId]'/>
				</td>
				<td>
					<input type = 'submit' value = 'Modificar cantidad'/>
					</form>
				</td>
				</tr>";
		}catch (PDOException $e){
				echo $e->getMessage();
		} 
	} else {
		header("Location:indice.html");
	}
	
?> 