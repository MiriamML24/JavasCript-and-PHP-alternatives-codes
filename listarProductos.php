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
			<li class="dropdown">
				<a href="javascript:void(0)" class="dropbtn">Productos</a>
				<div class="dropdown-content">
					<a href="insertarProducto.html">Insertar producto</a>
					<a href = "eliminarProductos.php">Eliminar productos</a>
				</div>
			</li>
		</ul>

	</body>
</html>
<?php
	echo "<div id = 'contenedor'><h1>Listado productos</h1>";
	if (!isset($_POST['pag'])){
		$numPagina = 1;
	} else {
		$numPagina = $_POST['pag'];
		if ($numPagina <= 1){
			$numPagina = 1;
		}
	}
	
	function contenido(){
		global $numPagina;
		global $filasRestantes;
		$limite = ($numPagina - 1) * 5;	

		try{
			$con = new PDO ('mysql:dbname=tienda;host=localhost', 'root', 'root');
			$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$con->exec("set names utf8");
			$consulta = $con->query('Select * from productos');
			$totalRegistros = $consulta->rowCount();
		
			$filasRestantes = $totalRegistros - $limite;
			
			$consulta2 = $con->query("Select * from productos limit $limite,5");
						
			echo "Mostrando ".($limite + 5). " de $totalRegistros registros<br><br>";
			echo "<table style = 'text-align:center'><tr>
					<th style = 'background-color:#FFE4FD; width: 150px;'>ID producto</th>
					<th style = 'background-color:#FFE4FD; width: 150px;'>Nombre del producto</th>
					<th style = 'background-color:#FFE4FD; width: 150px;'>Imagen</th>
					<th style = 'background-color:#FFE4FD; width: 150px;'>Descripcion</th>
					<th style = 'background-color:#FFE4FD; width: 150px;'>Precio</th>
					<th style = 'background-color:#FFE4FD; width: 150px;'>IVA</th>
					<th style = 'background-color:#FFE4FD; width: 150px;'>Stock</th></tr>";
			while ($fila = $consulta2->fetch(PDO::FETCH_ASSOC)) {
				echo "<tr>";
				echo "<td style = 'background-color:#E4F3FF'>$fila[productoId]</td>";
				echo "<td style = 'background-color:#E4F3FF'>$fila[nombre]</td>";
				echo "<td style = 'background-color:#E4F3FF'><img src = './img/$fila[imagen]' width = '250' height = '125'></img></td>";
				echo "<td style = 'background-color:#E4F3FF'>$fila[descripcion]</td>";
				echo "<td style = 'background-color:#E4F3FF'>$fila[precio]</td>";
				echo "<td style = 'background-color:#E4F3FF'>$fila[iva]</td>";
				echo "<td style = 'background-color:#E4F3FF'>$fila[stock]</td></tr>";
			} 
			echo "</table>";
		}catch (PDOException $e){
				echo $e->getMessage();
		} 
	}
	
	function obtenerValor(){
		global $numPagina;
		
		if (isset($_POST['anterior'])){
			$numPagina = $_POST['pag'];
			$numPagina--;
		}
		if (isset($_POST['boton1'])){
			$numPagina = $_POST['pag'];
			$numPagina++;		
		}
		if (isset($_POST['boton2'])){
			$numPagina = $_POST['pag'];
			$numPagina += 2;		
		}
		if (isset($_POST['boton3'])){
			$numPagina = $_POST['pag'];
			$numPagina += 3;		
		}
		if (isset($_POST['siguiente'])){
			$numPagina = $_POST['pag'];
			$numPagina++;
		}
	}
	
	function generarBotones(){
		global $numPagina;
		global $filasRestantes;
		
		$boton1 = $numPagina + 1;
		$boton2 = $numPagina + 2;
		$boton3 = $numPagina + 3;
		
		if (isset($_POST['pag']) && ($numPagina > 1) && ($filasRestantes > 5)){
$delimitador = <<<xxx
			<table>
				<tr>
					<td>
						<form action = "listarProductos.php" method = "post">
							<input type = "hidden" name = 'pag' value = "$numPagina"/>
							<input type = "submit" name = 'anterior' value = "Anterior"/>
						</form>
					</td>
					<td>
						<form action = "listarProductos.php" method = "post">
							<input type = "hidden" name = 'pag' value = "$numPagina"/>
							<input type = "submit" name = 'boton1' value = "$boton1"/>
						</form>
					</td>
					<td>
						<form action = "listarProductos.php" method = "post">
							<input type = "hidden" name = 'pag' value = "$numPagina"/>
							<input type = "submit" name = 'boton2' value = "$boton2"/>
						</form>
					</td>
					<td>
						<form action = "listarProductos.php" method = "post">
							<input type = "hidden" name = 'pag' value = "$numPagina"/>
							<input type = "submit" name = 'boton3' value = "$boton3"/>
						</form>
					</td>
					<td>
						<form action = "listarProductos.php" method = "post">
							<input type = "hidden" name = 'pag' value = "$numPagina"/>
							<input type = "submit" name = 'siguiente' value = "Siguiente"/>
						</form>
					</td>
				</tr>
			</table>
xxx;
			echo $delimitador;
		} else if ($filasRestantes > 5){
$delimitador = <<<xxx
			<table>
				<tr>
					<td>
						<form action = "listarProductos.php" method = "post">
							<input type = "hidden" name = 'pag' value = "$numPagina"/>
							<input type = "submit" name = 'boton1' value = "$boton1"/>
						</form>
					</td>
					<td>
						<form action = "listarProductos.php" method = "post">
							<input type = "hidden" name = 'pag' value = "$numPagina"/>
							<input type = "submit" name = 'boton2' value = "$boton2"/>
						</form>
					</td>
					<td>
						<form action = "listarProductos.php" method = "post">
							<input type = "hidden" name = 'pag' value = "$numPagina"/>
							<input type = "submit" name = 'boton3' value = "$boton3"/>
						</form>
					</td>
					<td>
						<form action = "listarProductos.php" method = "post">
							<input type = "hidden" name = 'pag' value = "$numPagina"/>
							<input type = "submit" name = 'siguiente' value = "Siguiente"/>
						</form>
					</td>
				</tr>
			</table>
xxx;
			echo $delimitador;
		} else {
$delimitador = <<<xxx
			<table>
				<tr>
					<td>
						<form action = "listarProductos.php" method = "post">
							<input type = "hidden" name = 'pag' value = "$numPagina"/>
							<input type = "submit" name = 'anterior' value = "Anterior"/>
						</form>
					</td>
				</tr>
			</table>
xxx;
			echo $delimitador;
		} 
		echo "La pagina actual <label style= 'color: blue'> $numPagina</label><br><br>";
	}
	obtenerValor();
	contenido();
	generarBotones();
?> 