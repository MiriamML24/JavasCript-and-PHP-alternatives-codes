<! DOCTYPE html">
<html xmlns= "http://www.w3.org/1999/xhtml" xml:lang="es" lang= "es"/>
	<head>
		<meta http-equiv="Content-Type" content="text/html charset=UTF-8"/>
		<link href="./css/tiendaVirtual.css" rel="stylesheet" type = "text/css">	
		<title>Tienda virtual</title>
	</head>
	
	<body>
		<ul>
			<li><a href="generarAlbaran.php">Generar albarán</a></li>
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
			$mostrarLista = $con->prepare("select itemPedido.*, productos.* from itemPedido inner join productos on productos.productoId = itemPedido.productoId");
			$mostrarLista->execute();
			echo "<table style = 'text-align: center'><tr>
				<th style = 'background-color:#FFE4FD; width: 150px;'>Nombre del producto</th>
				<th style = 'background-color:#FFE4FD; width: 150px;'>Imagen del producto</th>
				<th style = 'background-color:#FFE4FD; width: 150px;'>Cantidad añadida</th>
				<th style = 'background-color:#FFE4FD; width: 150px;'>Precio articulo</th></tr>";
			while ($lista = $mostrarLista->fetch(PDO::FETCH_ASSOC)){
				$operacion = ($lista['unidades'] * $lista['precio'] * (100-$lista['iva'])/100 + ($lista['unidades'] * $lista['precio']));
				echo "<tr><td style = 'background-color:#E4F3FF'>$lista[nombre]</td>
					<td style = 'background-color:#E4F3FF'><img src = './img/".$lista['imagen']."' width = 50 height = 50></img></td>
					<td style = 'background-color:#E4F3FF'>$lista[unidades]</td>
					<td style = 'background-color:#E4F3FF'>".$operacion."</td>
				</tr>";
			}
			$total = $con->prepare("Select * from pedidos where clienteId like :cliente");
			$total->bindValue(":cliente", $_SESSION['identificado'], PDO::PARAM_INT);
			$total->execute();
			$precioTotal = $total->fetch(PDO::FETCH_ASSOC);
			echo "<h4>Precio total: " . $precioTotal['totalPedido'];
			
		}catch (PDOException $e){
			echo $e->getMessage();
		} 
	}
?> 