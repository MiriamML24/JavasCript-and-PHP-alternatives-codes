<?php	
	$correo = $_POST["correo"];
	$paswd = $_POST["paswd"];
	
	try{
		$con = new PDO ('mysql:dbname=tienda;host=localhost', 'root', 'root');
		$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$con->exec("set names utf8");
		$buscarLogin = $con->prepare("Select * from credenciales where email like :email and password like :paswd and clienteId 
									in (select clienteId from clientes where email like :email2)");
		$buscarLogin->bindValue(":email", $correo, PDO::PARAM_STR);
		$buscarLogin->bindValue(":email2", $correo, PDO::PARAM_STR);
		$buscarLogin->bindValue(":paswd", $paswd, PDO::PARAM_STR);
		$buscarLogin->execute();
		$loginCliente = $buscarLogin->fetch(PDO::FETCH_ASSOC);
		
		$nombreCliente = $con->prepare("Select * from clientes where email like :email and clienteId 
									in (select clienteId from credenciales where email like :email2 and password like :paswd)");
		$nombreCliente->bindValue(":email", $correo, PDO::PARAM_STR);
		$nombreCliente->bindValue(":email2", $correo, PDO::PARAM_STR);
		$nombreCliente->bindValue(":paswd", $paswd, PDO::PARAM_STR);
		$nombreCliente->execute();
		$clienteNombre = $nombreCliente->fetch(PDO::FETCH_ASSOC);
		
		if ($buscarLogin->rowCount() == 1){
			session_start();
			if(!isset($_SESSION['identificado'])){
				echo $correo."<br><br>";
				echo $loginCliente['email']."<br><br>";
				echo $paswd."<br><br>";
				echo $loginCliente['password']."<br><br>";
				
				if((isset($correo)) && (isset($paswd))){
					if(($correo == $loginCliente['email']) && ($paswd == $loginCliente['password'])){
						echo $_SESSION['token'] = $_POST['token'];
						if($_SESSION['token'] == $_POST['token']){
							$_SESSION['identificado'] = $clienteNombre["clienteId"];
							echo $_SESSION['identificado'];
							header("Location:indiceCliente.html");
						} else {
							header("Location:registrarCliente.html");
						}
					} else {
						header("Location:registrarCliente.html ");
					}
				}else{
					header("Location:indiceCliente.html");
				}
			}  else {
				header("Location:indiceCliente.html");
			}  
		} else {
			header("Location:registrarCliente.html");
		}
	}catch (PDOException $e) {
		echo 'Error: '.$e->getMessage();
	}
	
	
?>