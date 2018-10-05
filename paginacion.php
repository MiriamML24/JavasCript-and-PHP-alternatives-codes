<?php
	
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
		$limite = ($numPagina - 1) * 20;	

		try{
			$con = new PDO ('mysql:dbname=bbdd;host=localhost', 'root', '');
			$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$con->exec("set names utf8");
			$consulta = $con->query('Select * from mdl_capabilities');
			$totalRegistros = $consulta->rowCount();
		
			$filasRestantes = $totalRegistros - $limite;
			
			$consulta2 = $con->query("Select * from mdl_capabilities limit $limite,20");
						
			echo "<h1 style='text-align:center; color:#33A5FF'>Paginación</h1>";
					
			if (($limite + 20) >  $totalRegistros){
				echo "<h4 style='margin-left:7%; color:#335EFF'>Mostrando ".$totalRegistros. " de $totalRegistros registros</h4>";
			} else {
				echo "<h4 style='margin-left:7%; color:#335EFF'>Mostrando ".($limite + 20). " de $totalRegistros registros</h4>";
			}
			echo "<div style='margin-left:7%'><table border = '1px solid' style = 'text-align:center'><tr>
					<th width=75px; heigh=75px>ID</th> 
					<th width=300px; heigh=300px>Name</th> 
					<th width=150px; heigh=150px>Captype</th> 
					<th width=150px; heigh=150px>Contextlevel</th> 
					<th width=150px; heigh=150px>Component</th> 
					<th width=150px; heigh=150px>Riskbitmask</th> </tr>";
			while ($fila = $consulta2->fetch(PDO::FETCH_ASSOC)) {
				echo "<tr>";
				foreach ($fila as $valor){
					echo "<td>".$valor."</td>";
				}
				echo "</tr>";
			} 
			echo "</table></div><br>";
		}catch (PDOException $e){
				echo $e->getMessage();
		} 
	}
	
	function obtenerValor(){
		global $numPagina;
		
		if (isset($_POST['actual'])){
			$numPagina = $_POST['pag'];
			$numPagina--;
		}
		
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
		
		$actual = $numPagina;
		$boton1 = $numPagina + 1;
		$boton2 = $numPagina + 2;
		$boton3 = $numPagina + 3;
		
		if (isset($_POST['pag']) && ($numPagina > 1) && ($filasRestantes > 5)){
$delimitador = <<<xxx
			<table style='margin-left:7%'>
				<tr>
					<td>
						<form action = "paginacion.php" method = "post">
							<input type = "hidden" name = 'pag' value = "$numPagina"/>
							<input type = "submit" name = 'anterior' value = "Anterior"/>
						</form>
					</td>
					<td>
						<form action = "paginacion.php" method = "post">
							<input type = "hidden" name = 'pag' value = "$numPagina"/>
							<input type = "submit" name = 'actual' value = "$actual" style='background:#33FFC7'/>
						</form>
					</td>
					<td>
						<form action = "paginacion.php" method = "post">
							<input type = "hidden" name = 'pag' value = "$numPagina"/>
							<input type = "submit" name = 'boton1' value = "$boton1"/>
						</form>
					</td>
					<td>
						<form action = "paginacion.php" method = "post">
							<input type = "hidden" name = 'pag' value = "$numPagina" />
							<input type = "submit" name = 'boton2' value = "$boton2"/>
						</form>
					</td>
					<td>
						<form action = "paginacion.php" method = "post">
							<input type = "hidden" name = 'pag' value = "$numPagina"/>
							<input type = "submit" name = 'boton3' value = "$boton3"/>
						</form>
					</td>
					<td>
						<form action = "paginacion.php" method = "post">
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
			<table style='margin-left:7%'>
				<tr>
					<td>
						<form action = "paginacion.php" method = "post">
							<input type = "hidden" name = 'pag' value = "$numPagina"/>
							<input type = "submit" name = 'actual' value = "$actual" style='background:#33FFC7'/>
						</form>
					</td>
					<td>
						<form action = "paginacion.php" method = "post">
							<input type = "hidden" name = 'pag' value = "$numPagina"/>
							<input type = "submit" name = 'boton1' value = "$boton1"/>
						</form>
					</td>
					<td>
						<form action = "paginacion.php" method = "post">
							<input type = "hidden" name = 'pag' value = "$numPagina"/>
							<input type = "submit" name = 'boton2' value = "$boton2"/>
						</form>
					</td>
					<td>
						<form action = "paginacion.php" method = "post">
							<input type = "hidden" name = 'pag' value = "$numPagina"/>
							<input type = "submit" name = 'boton3' value = "$boton3"/>
						</form>
					</td>
					<td>
						<form action = "paginacion.php" method = "post">
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
			<table style='margin-left:7%'>
				<tr>
					<td>
						<form action = "paginacion.php" method = "post">
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
?> 