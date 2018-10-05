<?php

	if(!empty($_FILES['fichero'])){
		$archivo = $_FILES['fichero'];
		if($archivo['type'] == 'csv'){
			$fopen = fopen($archivo['name'], 'r');
			$contenido = fread($fopen, filesize($archivo]['name']));
			echo $contenido;
		}
	}
	
?>