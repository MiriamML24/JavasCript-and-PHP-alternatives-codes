<?php	

	//error_reporting(0);
	$alias = $_POST['alias'];
	$pswd = $_POST['pswd'];

	if((validarDatos($alias)) && (validarDatos($pswd))){
		iniciarSesion();
	} else {
		reescribirFormulario();
	}
	
	function iniciarSesion(){
		global $alias;
		global $pswd;
		
		try{
			$con = new PDO ('mysql:dbname=subastas;host=localhost', 'root', 'root');
			$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$con->exec("set names utf8");
			
			$buscarUsuario = $con->prepare('Select * from usuarios where alias like :alias and pwd like :password');
			$buscarUsuario->bindValue(":alias", $alias, PDO::PARAM_STR);
			$buscarUsuario->bindValue(":password", $pswd, PDO::PARAM_STR);
			$buscarUsuario->execute();
			$usuario = $buscarUsuario->fetch(PDO::FETCH_ASSOC);

			if ($buscarUsuario->rowCount() == 1){
				session_start();
				if(!isset($_SESSION['identificado'])){
					if((isset($alias)) && (isset($pswd))){
						if(($alias == $usuario['alias']) && ($pswd == $usuario['pwd'])){
							if($_SESSION['token'] == $_POST['token']){
								$_SESSION['identificado']  = $usuario['nombre'];
								setcookie("email", $usuario['email']);
								header("Location:indiceUsuario.php");
							} else {
								header("Location:inscribirse.php");
							}
						} else {
							header("Location:inscribirse.php ");
						}
					}else{
						setcookie("email", $usuario['email']);
						header("Location:indiceUsuario.php");
					}
				}  else { 
					setcookie("email", $usuario['email']);
					header("Location:indiceUsuario.php");
				} 
			}else {
				header("Location:inscribirse.php");
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
		global $alias;
		global $pswd;
	
		if (!validarDatos($nombre)){
$delimitador = <<<xxx
		<form action = "loguearse.php" method  = "post">
			Alias: 
			<input type = "text" name = "alias"/><br>
				<label style = "color: red">Este campo no puede estar vacio</label><br><br>
xxx;
		} else {
$delimitador = <<<xxx
			<form action = "loguearse.php" method  = "post">
				Alias: $alias<br><br>
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