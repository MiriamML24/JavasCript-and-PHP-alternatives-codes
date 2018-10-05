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
	echo "<div id = 'contenedor'><h1>Eliminar producto</h1>";

	try{
		$con = new PDO ('mysql:dbname=tienda;host=localhost', 'root', 'root');
		$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$con->exec("set names utf8");
		$consulta = $con->query('Select nombre from productos');
		echo "<form action = 'eliminacionProducto.php' method = 'post'>";
		echo 'Nombre del producto a elminar:
			<select name = "nombreProducto">';
		while ($nombre = $consulta->fetch(PDO::FETCH_ASSOC)) {
			echo "<option value = '$nombre[nombre]'>".$nombre['nombre']."</option>";
		}
		echo "</select>";
	}catch (PDOException $e) {
		echo 'Error: '.$e->getMessage();
	}
	echo '<br><br><input type = "submit" value = "Eliminar producto"/>
			</form>';
?>