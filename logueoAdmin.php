<?php	
	session_start();
	if(!isset($_SESSION['identificado'])){
		if((isset($_POST['user'])) && (isset($_POST['paswd']))){
			if(($_POST["user"] == "admin") && ($_POST['paswd'] == "admin")){
				if($_SESSION['token'] == $_POST['token']){
					$_SESSION['identificado']  = "Administrador";
					header("Location:indiceAdministrador.php");
				} else {
					header("Location:indice.php");
				}
			} else {
				header("Location:indice.php ");
			}
		}else{
			header("Location:indiceAdministrador.php");
		}
	}  else { 
		header("Location:indiceAdministrador.php");
	} 
?>