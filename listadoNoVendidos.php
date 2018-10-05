<?php
	session_start();
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
		$fecha = new DateTime('now');
		$limite = ($numPagina - 1) * 5;	

		try{
			$con = new PDO ('mysql:dbname=subastas;host=localhost', 'root', 'root');
			$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$con->exec("set names utf8");
			$consulta = $con->prepare('Select descripcion, precioSalida, max(precioPuja), count(pujas.usuario), articulos.vendedor
									from articulos	
										left join pujas on articulos.articulo = pujas.articulo
									where fechaLimite <= :fechaActual 
									and horaLimite >= :hora 
									and comprador is null
									group by articulos.articulo');
			$consulta->bindValue(":fechaActual", $fecha->format("Y-m-d"), PDO::PARAM_STR);
			$consulta->bindValue(":hora", $fecha->format("H:i:s"), PDO::PARAM_STR);
			$consulta->execute();
			$totalRegistros = $consulta->rowCount();
		
			$filasRestantes = $totalRegistros - $limite;
			
			$consulta2 = $con->prepare('Select descripcion, precioSalida, max(precioPuja), count(pujas.usuario), articulos.vendedor
									from articulos	
										left join pujas on articulos.articulo = pujas.articulo
									where fechaLimite <= :fechaActual 
									and horaLimite >= :hora
									group by articulos.articulo');
			$consulta2->bindValue(":fechaActual", $fecha->format("Y-m-d"), PDO::PARAM_STR);
			$consulta2->bindValue(":hora", $fecha->format("H:i:s"), PDO::PARAM_STR);
			$consulta2->execute();
						
			echo "<h1 style = 'color:blue'>Productos no vendidos</h1>Mostrando ".($limite + 5). " de $totalRegistros registros<br><br>";
			echo "<table border = '1px solid' style = 'text-align:center'><tr><th>Descripcion</th>
			<th>Precio salida</th>
			<th>Max precio puja</th>
			<th>Numero pujas</th>
			<th>Vendedor</th></tr>";
			while ($fila = $consulta2->fetch(PDO::FETCH_ASSOC)) {
				echo "<tr>";
				foreach ($fila as $valor){
					echo "<td>".$valor."</td>";
				}
				echo "</tr>";
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
						<form action = "listadoNoVendidos.php" method = "post">
							<input type = "hidden" name = 'pag' value = "$numPagina"/>
							<input type = "submit" name = 'anterior' value = "Anterior"/>
						</form>
					</td>
					<td>
						<form action = "listadoNoVendidos.php" method = "post">
							<input type = "hidden" name = 'pag' value = "$numPagina"/>
							<input type = "submit" name = 'boton1' value = "$boton1"/>
						</form>
					</td>
					<td>
						<form action = "listadoNoVendidos.php" method = "post">
							<input type = "hidden" name = 'pag' value = "$numPagina"/>
							<input type = "submit" name = 'boton2' value = "$boton2"/>
						</form>
					</td>
					<td>
						<form action = "listadoNoVendidos.php" method = "post">
							<input type = "hidden" name = 'pag' value = "$numPagina"/>
							<input type = "submit" name = 'boton3' value = "$boton3"/>
						</form>
					</td>
					<td>
						<form action = "listadoNoVendidos.php" method = "post">
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
						<form action = "listadoNoVendidos.php" method = "post">
							<input type = "hidden" name = 'pag' value = "$numPagina"/>
							<input type = "submit" name = 'boton1' value = "$boton1"/>
						</form>
					</td>
					<td>
						<form action = "listadoNoVendidos.php" method = "post">
							<input type = "hidden" name = 'pag' value = "$numPagina"/>
							<input type = "submit" name = 'boton2' value = "$boton2"/>
						</form>
					</td>
					<td>
						<form action = "listadoNoVendidos.php" method = "post">
							<input type = "hidden" name = 'pag' value = "$numPagina"/>
							<input type = "submit" name = 'boton3' value = "$boton3"/>
						</form>
					</td>
					<td>
						<form action = "listadoNoVendidos.php" method = "post">
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
						<form action = "listadoNoVendidos.php" method = "post">
							<input type = "hidden" name = 'pag' value = "$numPagina"/>
							<input type = "submit" name = 'anterior' value = "Anterior"/>
						</form>
					</td>
				</tr>
			</table>
xxx;
			echo $delimitador;
		} 
	}
	obtenerValor();
	contenido();
	generarBotones();
	echo "<a href = 'indiceAdministrador.php'>[Volver]</a>";
?> 