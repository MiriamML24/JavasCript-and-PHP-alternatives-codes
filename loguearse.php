<?php	
$delimitador = <<<xxx
	<h1 style = "color:blue">Inscripcion de clientes</h1>
	<form action = "logueoCliente.php" method = "post">
		Alias: 
			<input type = "text" name = "alias"/><br><br>
		Password:
			<input type = "password" name = "pswd"/><br><br>
		<input type = "submit" value = "Registrarse"/>
	</form>
xxx;
	echo $delimitador;
?>