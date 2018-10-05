<! DOCTYPE html">
<html xmlns= "http://www.w3.org/1999/xhtml" xml:lang="es" lang= "es"/>
	<head>
		<meta http-equiv="Content-Type" content="text/html charset=UTF-8"/>
		<link href="./css/tiendaVirtual.css" rel="stylesheet" type = "text/css">	
		<title>Tienda virtual</title>
	</head>
	
	<body>
		<ul>
			<li><a href="listadoProductos.php">Seguir comprando</a></li>
			<li>
				<a href = "finalizarCompra.php">Finalizar compra</a>
			</li>
			<li>
				<a href = "indiceCliente.html">Volver</a>
			</li>
		</ul>
	</body>
</html>
<?php
	echo "<div id = 'contenedor'><h1>Cesta actual de la compra</h1>";

	if(!isset($_SESSION['identificado'])){
		session_start();
		verCarrito();
	} else {
		header("Location:indice.html");
	} 
	function verCarrito(){
		try{
			$con = new PDO ('mysql:dbname=tienda;host=localhost', 'root', 'root');
			$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$con->exec("set names utf8");
			$mostrarLista = $con->prepare("select itemcesta.*, productos.* from itemcesta inner join productos on productos.productoId = itemcesta.productoId where clienteId like :cliente");
			$mostrarLista->bindValue(":cliente", $_SESSION['identificado'], PDO::PARAM_INT);
			$mostrarLista->execute();
			echo "<table style = 'text-align: center'><tr>
				<th style = 'background-color:#FFE4FD; width: 150px;'>Nombre del producto</th>
				<th style = 'background-color:#FFE4FD; width: 150px;'>Imagen del producto</th>
				<th style = 'background-color:#FFE4FD; width: 150px;'>Cantidad añadida</th>
				<th style = 'background-color:#FFE4FD; width: 150px;'>Precio total</th>
				<th style = 'background-color:#FFE4FD; width: 150px;'>Stock</th></tr>";
			while ($lista = $mostrarLista->fetch(PDO::FETCH_ASSOC)){
				$operacion = ($lista['cantidad'] * $lista['precio'] * (100-$lista['iva'])/100 + ($lista['cantidad'] * $lista['precio']));
				echo "<tr><td style = 'background-color:#E4F3FF'>$lista[nombre]</td>
					<td style = 'background-color:#E4F3FF'><img src = './img/".$lista['imagen']."' width = 50 height = 50></img></td>
					<td style = 'background-color:#E4F3FF'>$lista[cantidad]</td>
					<td style = 'background-color:#E4F3FF'>".$operacion."</td>
					<td style = 'background-color:#E4F3FF'>$lista[stock]</td>
					<td>
						<form method = 'post' action =  'eliminarArtCarrito.php'>
							<input type = 'hidden' value = '$lista[productoId]' name = 'productoId'/>
							<input type = 'submit' value = 'Eliminar producto'/>
						</form>
					</td>
					<td>
						<form method = 'post' action =  'modificarCantidad.php'>
							<input type = 'hidden' value = '$lista[productoId]' name = 'productoId'/>
							<input type = 'submit' value = 'Modificar cantidad'/>
						</form>
					</td>
				</tr>";
			}
		}catch (PDOException $e){
			echo $e->getMessage();
		} 
	}
?> 