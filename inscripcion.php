<?php	

	error_reporting(0);
	echo '<h1 style = "color:blue">Inscripcion de clientes</h1>';
	$alias = $_POST['alias'];
	$correo = $_POST['correo'];
	$apellidos = $_POST['apellidos'];
	$nombre = $_POST['nombre'];
	$direccion = $_POST['direccion'];
	$sexo = $_POST['sexo'];
	$pswd = $_POST['pswd'];

	if((validarDatos($alias)) && (validarDatos($correo)) && (validarDatos($apellidos)) && (validarDatos($nombre)) && (validarDatos($direccion)) 
		&& (validarRadio($sexo)) && (validarDatos($pswd))){
		insertarDatos();
	} else {
		reescribirFormulario();
	}
	
	function insertarDatos(){
		global $alias;
		global $correo;
		global $apellidos;
		global $nombre;
		global $direccion;
		global $sexo;
		global $pswd;
		
		try{
			$con = new PDO ('mysql:dbname=subastas;host=localhost', 'root', 'root');
			$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$con->exec("set names utf8");
			
			$insertar = $con->prepare('Insert into usuarios values (:alias, :correo, :apellidos, :nombre, :direccion, :sexo, :password)');
			$insertar->bindValue(":alias", $alias, PDO::PARAM_STR);
			$insertar->bindValue(":correo", $correo, PDO::PARAM_STR);
			$insertar->bindValue(":apellidos", $apellidos, PDO::PARAM_STR);
			$insertar->bindValue(":nombre", $nombre, PDO::PARAM_INT);
			$insertar->bindValue(":direccion", $direccion, PDO::PARAM_INT);
			$insertar->bindValue(":sexo", $sexo, PDO::PARAM_STR);
			$insertar->bindValue(":password", $pswd, PDO::PARAM_STR);
			$insertar->execute();
			
			if ($insertar->rowCount() == 1)
				echo "<h4>El usuario <i>$nombre $apellidos</i> se ha insertado de forma correcta</h4>";
			else {
				echo "<h4>No se ha insertado el usuario con el nombre <i>$nombre $apellidos</i></h4>";
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
	function validarRadio($campo){
		if (isset($campo)){
			return true;
		} 
		return false;
	}
	
	function reescribirFormulario(){
		global $alias;
		global $correo;
		global $apellidos;
		global $nombre;
		global $direccion;
		global $sexo;
		global $pswd;
	
		if (!validarDatos($nombre)){
$delimitador = <<<xxx
		<form action = "inscribirse.php" method  = "post">
			Alias: 
			<input type = "text" name = "alias"/><br>
				<label style = "color: red">Este campo no puede estar vacio</label><br><br>
xxx;
		} else {
$delimitador = <<<xxx
			<form action = "inscribirse.php" method  = "post">
				Alias: $alias<br><br>
xxx;
		}
		
		if (!validarDatos($correo)){
$delimitador .= <<<xxx
			Email: 
				<input type = "email" name = "correo" /><br>
				<label style = "color: red">Este campo no puede estar vacio</label><br><br>
xxx;
		} else {
$delimitador .= <<<xxx
			Email: $correo<br><br>
xxx;
		}
		
		if (!validarDatos($apellidos)){
$delimitador .= <<<xxx
			Apellidos: 
				<input type = "text" name = "apellidos" /><br>
				<label style = "color: red">Este campo no puede estar vacio</label><br><br>
xxx;
		} else {
$delimitador .= <<<xxx
			Apellidos: $apellidos<br><br>
xxx;
		}
		
		if (!validarDatos($nombre)){
$delimitador .= <<<xxx
			Nombre: 
				<input type = "text" name = "nombre" /><br>
				<label style = "color: red">Este campo no puede estar vacio</label><br><br>
xxx;
		} else {
$delimitador .= <<<xxx
			Nombre:  $nombre<br><br>
xxx;
		}
		
		if (!validarDatos($direccion)){
$delimitador .= <<<xxx
			Direccion: 
				<input type = "text" name = "direccion"/><br>
				<label style = "color: red">Este campo no puede estar vacio</label><br><br>
xxx;
		} else {
$delimitador .= <<<xxx
			Direccion:  $direccion<br><br>
xxx;
		}
		
		if (!validarRadio($sexo)){
$delimitador .= <<<xxx
			Sexo:
				<input type = "radio" name = "sexo" value = "h"/>Hombre
				<input type = "radio" name = "sexo" value = "m"/>Mujer<br>
				<label style = "color: red">Este campo no puede estar vacio</label><br><br>
xxx;
		} else {
$delimitador .= <<<xxx
			Sexo: $sexo<br><br>
xxx;
		}	
		
		if (!validarDatos($pswd)){
$delimitador .= <<<xxx
			Password:
			<input type = "password" name = "pswd"/>
				<label style = "color: red">Este campo no puede estar vacio</label><br><br>
xxx;
		} else {
$delimitador .= <<<xxx
			Password:  $pswd<br><br>
xxx;
		}
$delimitador .= <<<xxx
			<input type  = "submit" value = "Inscripcion"/>
		</form>
		<br>
xxx;
	
		echo $delimitador;
	}
	echo "<a href = 'indice.php'>[Volver]</a>";
?>