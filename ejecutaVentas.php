<?php	
	session_start();
	echo '<h1 style = "color:blue">Ejecutar venta</h1>';
	
	function ejecutarVenta(){
		$fecha = new DateTime('now');
	
		try{
			$con = new PDO ('mysql:dbname=subastas;host=localhost', 'root', 'root');
			$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$con->exec("set names utf8");
			$consulta = $con->prepare('Select articulos.*, max(precioPuja), pujas.usuario, pujas.precioPuja 
										from articulos	
											inner join pujas on articulos.articulo = pujas.articulo
										where fechaLimite < :fechaActual
										and horaLimite > :hora
										and comprador is null
                                        group by articulos.articulo
                                        having max(precioPuja) > articulos.precioSalida');
			$consulta->bindValue(":fechaActual", $fecha->format("Y-m-d"), PDO::PARAM_STR);
			$consulta->bindValue(":hora", $fecha->format("H:i:s"), PDO::PARAM_STR);
			$consulta->execute();
			
			while ($datos = $consulta->fetch(PDO::FETCH_ASSOC)) {
				$actualizarProducto = $con->prepare("Update articulos set comprador = :compra where articulo like :art");
				$actualizarProducto->bindValue(":compra", $datos['usuario'], PDO::PARAM_STR);
				$actualizarProducto->bindValue(":art", $datos['articulo'], PDO::PARAM_INT);
				$actualizarProducto->execute();
				if ($actualizarProducto->rowCount() > 0){
					echo "<h4>Se han actualizado los productos <i>$datos[descripcion]</i></h4>";
				} else {
					echo "<h4>No hay productos a actualizar</h4>";
				}
			}
				
		}catch (PDOException $e){
				echo $e->getMessage();
		} 
	}
	ejecutarVenta();
	echo "<a href = 'indiceUsuario.php'>[Volver]</a>";
?>