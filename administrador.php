<?php	
$delimitador = <<<xxx
	<h1 style = "color:blue">Inscripcion de clientes</h1>
	<form action = "logueoAdmin.php" method = "post">
		Nombre: 
			<input type = "text" name = "user"/><br><br>
		Password:
			<input type = "password" name = "paswd"/><br><br>
		<input type = "submit" value = "Iniciar sesion"/>
	</form>
xxx;
	echo $delimitador;
?>