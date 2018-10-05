<?php	
	session_start();
	//error_reporting(0);
	echo '<h1 style = "color:blue">Pujar por un producto</h1>';
	$dinero = $_POST['dinero'];
	$productos = $_POST['productos'];
	
	if((validarDatos($dinero)) && ($productos != "")){
		hacerPuja();
	} else {
		reescribirFormulario();
	}
	
	function hacerPuja(){
		global $dinero;
		global $productos;
		$emailUsuario = $_COOKIE['email'];
		$sesion = $_SESSION['identificado'];
		$fecha = new DateTime('now');
		
		try{
			$con = new PDO ('mysql:dbname=subastas;host=localhost', 'root', 'root');
			$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$con->exec("set names utf8");
			
			$buscarVendedor = $con->prepare("Select * from usuarios where email like :correo and nombre like :nombre");
			$buscarVendedor->bindValue(":correo", $emailUsuario, PDO::PARAM_STR);
			$buscarVendedor->bindValue(":nombre", $sesion, PDO::PARAM_STR);
			$buscarVendedor->execute();
			$aliasUsuario = $buscarVendedor->fetch(PDO::FETCH_ASSOC);
			
			$buscarPuja = $con->prepare("Select * from pujas where articulo like :producto");
			$buscarPuja->bindValue(":producto", $productos, PDO::PARAM_STR);
			$buscarPuja->execute();
			
			if ($buscarPuja->rowCount() == 0){
				$precioSalida = $con->prepare("Select * from articulos where articulo like :producto");
				$precioSalida->bindValue(":producto", $productos, PDO::PARAM_INT);
				$precioSalida->execute();
				$precio = $precioSalida->fetch(PDO::FETCH_ASSOC);
				if ($dinero >= $precio['precioSalida']){
					$puja = $con->prepare('Insert into pujas values (:usuario, :art, :fecha, :hora, :precio)');
					$puja->bindValue(":usuario", $aliasUsuario['alias'], PDO::PARAM_STR);
					$puja->bindValue(":art", $productos, PDO::PARAM_INT);
					$puja->bindValue(":fecha", $fecha->format("Y-m-d"), PDO::PARAM_STR);
					$puja->bindValue(":hora", $fecha->format("H:i:s"), PDO::PARAM_STR);
					$puja->bindValue(":precio", $dinero, PDO::PARAM_INT);
					$puja->execute();
					if ($puja->rowCount() == 1)
						echo "<h4>Se ha hecho la puja correcta al nombre de <i>$sesion</i></h4>";
					else {
						echo "<h4>No se ha hecho la puja</h4>";
					} 
				} else {
					echo "<h4>No se ha hecho la puja</h4>";
				}
			} else {
				$ultimaPuja = $con->prepare("Select * from pujas where articulo like :producto order by fechaPuja desc limit 1");
				$ultimaPuja->bindValue(":producto", $productos, PDO::PARAM_INT);
				$ultimaPuja->execute();
				$precio = $ultimaPuja->fetch(PDO::FETCH_ASSOC);
				if ($dinero >= $precio['precioPuja']){
					$puja = $con->prepare('Insert into pujas values (:usuario, :art, :fecha, :hora, :precio)');
					$puja->bindValue(":usuario", $aliasUsuario['alias'], PDO::PARAM_STR);
					$puja->bindValue(":art", $productos, PDO::PARAM_INT);
					$puja->bindValue(":fecha", $fecha->format("Y-m-d"), PDO::PARAM_STR);
					$puja->bindValue(":hora", $fecha->format("H:i:s"), PDO::PARAM_STR);
					$puja->bindValue(":precio", $dinero, PDO::PARAM_INT);
					$puja->execute();
					if ($puja->rowCount() == 1)
						echo "<h4>Se ha hecho la puja correcta al nombre de <i>$sesion</i></h4>";
					else {
						echo "<h4>No se ha hecho la puja</h4>";
					} 
				} else {
					echo "<h4>No se ha hecho la puja</h4>";
				}
			}

		}catch (PDOException $e){
				echo $e->getMessage();
		} 
	}
	
	function validarDatos($campo){
		if (empty($campo)){
			return false;
		} 
		return true;
	} 
	
	function reescribirFormulario(){
		global $dinero;
		global $productos;
			
		if (!validarDatos($dinero)){
$delimitador = <<<xxx
		<form action = "pujar.php" method  = "post">
			Dinero que vas a pujar: 
				<input type = "number" name = "dinero" step = "any" min = "0.0" placeholder = "0.0"/><br>
				<label style = "color: red">Este campo no puede estar vacio</label><br><br>
xxx;
		} else {
$delimitador = <<<xxx
			<form action = "ponerVenta.php" method  = "post">
				Dinero que vas a pujar: $dinero<br><br>
xxx;
		}
	echo $delimitador;	
		if ($productos == ""){
			$fecha = new DateTime('now');
$delimitador = <<<xxx
			Productos a pujar:		
			<select name = "productos">
				<option value = ''></option>
xxx;
			echo $delimitador;
			try{
				$con = new PDO ('mysql:dbname=subastas;host=localhost', 'root', 'root');
				$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$con->exec("set names utf8");
				
				$listaArticulos = $con->prepare('Select * from articulos');
				$listaArticulos->bindValue(":fecha", $fecha->format("Y-m-d"), PDO::PARAM_STR);
				$listaArticulos->execute();
				
				while ($articulosListados = $listaArticulos->fetch(PDO::FETCH_ASSOC)){
					echo "<option value = '$articulosListados[articulo]'>".$articulosListados['descripcion']."</option>";
				}
			 
			}catch (PDOException $e){
					echo $e->getMessage();
			} 
			echo '</select><br><label style = "color: red">Este campo no puede estar vacio</label><br><br>';
		} else {
$delimitador = <<<xxx
			Productos a pujar: $productos<br><br>
xxx;
		echo $delimitador;	
		}
	echo '<input type = "submit" value = "Pujar"/>
		</form>';;
	}
	echo "<a href = 'indiceUsuario.php'>[Volver]</a>";
?>