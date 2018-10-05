<?php	
	session_start();
	//error_reporting(0);
	echo '<h1 style = "color:blue">Inscripcion de productos</h1>';
	$descripcion = $_POST['descripcion'];
	$estado = $_POST['estado'];
	$fechaLimit = $_POST['fechaLimit'];
	$horaLimit = $_POST['horaLimit'];
	$precioSalida = $_POST['precioSalida'];
	$categoria = $_POST['categoria'];
	
	if((validarDatos($descripcion)) && ($estado != "") && (validarDatos($fechaLimit)) && (validarDatos($horaLimit)) && (validarDatos($precioSalida))
		&& ($categoria != "")){
		inscribirProducto();
	} else {
		reescribirFormulario();
	}
	
	function inscribirProducto(){
		global $descripcion;
		global $estado;
		global $fechaLimit;
		global $horaLimit;
		global $precioSalida;
		global $categoria;
		$emailUsuario = $_COOKIE['email'];
		$sesion = $_SESSION['identificado'];
		try{
			$con = new PDO ('mysql:dbname=subastas;host=localhost', 'root', 'root');
			$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$con->exec("set names utf8");
			
			$ultimoArticulo = $con->query("Select * from articulos order by 1 desc limit 1");
			$ultimo = $ultimoArticulo->fetch(PDO::FETCH_ASSOC);
			$contador = $ultimo['articulo']+1;
			
			$buscarVendedor = $con->prepare("Select * from usuarios where email like :correo and nombre like :nombre");
			$buscarVendedor->bindValue(":correo", $emailUsuario, PDO::PARAM_STR);
			$buscarVendedor->bindValue(":nombre", $sesion, PDO::PARAM_STR);
			$buscarVendedor->execute();
			$aliasUsuario = $buscarVendedor->fetch(PDO::FETCH_ASSOC);
				
			$insertarVenta = $con->prepare('Insert into articulos values (:numArticulo, :descrip, :estado, :fechaLimit, :horaLimit, :precio, :vendedor, null)');
			$insertarVenta->bindValue(":numArticulo", $contador, PDO::PARAM_STR);
			$insertarVenta->bindValue(":descrip", $descripcion, PDO::PARAM_STR);
			$insertarVenta->bindValue(":estado", $estado, PDO::PARAM_STR);
			$insertarVenta->bindValue(":fechaLimit", $fechaLimit, PDO::PARAM_STR);
			$insertarVenta->bindValue(":horaLimit", $horaLimit, PDO::PARAM_INT);
			$insertarVenta->bindValue(":precio", $precioSalida);
			$insertarVenta->bindValue(":vendedor", $aliasUsuario['alias'], PDO::PARAM_STR);
			$insertarVenta->execute();
			
			if ($insertarVenta->rowCount() == 1){
				$insertarCategoria = $con->prepare("Insert into clasificacion values (:numArticulo, :cat)");
				$insertarCategoria->bindValue(":numArticulo", $contador, PDO::PARAM_STR);
				$insertarCategoria->bindValue(":cat", $categoria, PDO::PARAM_STR);
				$insertarCategoria->execute();
				echo "<h4>Se ha insertado el producto con descripcion <i>$descripcion</i> de forma correcta</h4>";
			}else {
				echo "<h4>No se ha insertado el producto con la descripcion <i>$descripcion</i></h4>";
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
		global $descripcion;
		global $estado;
		global $fechaLimit;
		global $horaLimit;
		global $precioSalida;
		global $categoria;
			
		if (!validarDatos($descripcion)){
$delimitador = <<<xxx
		<form action = "ponerVenta.php" method  = "post">
			Descripcion del producto: 
				<input type = "text" name = "descripcion"/><br>
				<label style = "color: red">Este campo no puede estar vacio</label><br><br>
xxx;
		} else {
$delimitador = <<<xxx
			<form action = "ponerVenta.php" method  = "post">
				Descripcion del producto:  $descripcion<br><br>
xxx;
		}
		
		if ($estado == ""){
$delimitador .= <<<xxx
			Estado:		
				<select name = "estado">
					<option value = ""></option>
					<option value = "usado">Usado</option>
					<option value = "nuevo">Nuevo</option>
				</select><br>
				<label style = "color: red">Este campo no puede estar vacio</label><br><br>
xxx;
		} else {
$delimitador .= <<<xxx
			Estado: $estado<br><br>
xxx;
		}
		
		if (!validarDatos($fechaLimit)){
$delimitador .= <<<xxx
			Fecha limite:
				<input type = "date" name = "fechaLimit" /><br>
				<label style = "color: red">Este campo no puede estar vacio</label><br><br>
xxx;
		} else {
$delimitador .= <<<xxx
			Fecha limite: $fechaLimit<br><br>
xxx;
		}
		
		if (!validarDatos($horaLimit)){
$delimitador .= <<<xxx
			Hora limite: 
				<input type = "time" name = "horaLimit" /><br>
				<label style = "color: red">Este campo no puede estar vacio</label><br><br>
xxx;
		} else {
$delimitador .= <<<xxx
			Hora limite: $horaLimit<br><br>
xxx;
		}
		
		if (($precioSalida < 0) || (!validarDatos($precioSalida))){
$delimitador .= <<<xxx
			Precio salida: 
				<input type = "number" name = "precioSalida" step = "any" min = "0,0" value = "0,0"/><br>
				<label style = "color: red">Este campo no puede estar vacio</label><br><br>
xxx;
		} else {
$delimitador .= <<<xxx
			Precio salida: $precioSalida<br><br>
xxx;
		}
		echo $delimitador;
		if ($categoria == ""){
			try{
				$con = new PDO ('mysql:dbname=subastas;host=localhost', 'root', 'root');
				$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$con->exec("set names utf8");
				
				$buscarVendedor = $con->query("Select * from categorias");
				echo "Categoria: 
						<select name = 'categoria'>
						<option value ''></option>";
				while ($categoria = $buscarVendedor->fetch(PDO::FETCH_ASSOC)){
					echo "<option value = '$categoria[nombreCategoria]'>".$categoria['nombreCategoria']."</option>";
				}
			}catch (PDOException $e){
					echo $e->getMessage();
			} 
			echo '</select><br><label style = "color: red">Este campo no puede estar vacio</label><br><br>';
		} else {
			echo "Categoria: $categoria<br><br>";
		}

		echo '<input type  = "submit" value = "Volver a subir articulo"/>
				</form>
				<br>';
	}
	echo "<a href = 'indiceUsuario.php'>[Volver]</a>";
?>