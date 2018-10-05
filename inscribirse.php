<?php	
$delimitador = <<<xxx
	<h1 style = "color:blue">Inscripcion de clientes</h1>
	<form action = "inscripcion.php" method = "post">
		Alias: 
			<input type = "text" name = "alias"/><br><br>
		Email: 
			<input type = "email" name = "correo" /><br><br>
		Apellidos: 
			<input type = "text" name = "apellidos" /><br><br>
		Nombre: 
			<input type = "text" name = "nombre" /><br><br>
		Direccion: 
			<input type = "text" name = "direccion"/><br><br>
		Sexo:
			<input type = "radio" name = "sexo" value = "h"/>Hombre
			<input type = "radio" name = "sexo" value = "m"/>Mujer<br><br>
		Password:
			<input type = "password" name = "pswd"/><br><br>
		<input type = "submit" value = "Registrarse"/>
	</form>
xxx;
	echo $delimitador;
?>