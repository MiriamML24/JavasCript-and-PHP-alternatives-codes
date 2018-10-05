<?php
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	
	$comunidades = array("Andalucía", "Aragón", "Canarias", "Cantabria", "Castilla y León", "Castilla-La Mancha", "Cataluña", "Ceuta", "Comunidad Valenciana", "Comunidad de Madrid", "Extremadura", "Galicia", "Islas Baleares", "La Rioja", "Melilla", "Navarra", "País Vasco", "Principado de Asturias", "Región de Murcia");
	$outp = "";
	foreach($comunidades as $key => $value ) {
		if ($outp != "") {
			$outp .= ",";
		}
		$outp .= '{"nombre":"'.$value.'"}';
	}
 
	$outp ='['.$outp.']';

	echo($outp);
?>