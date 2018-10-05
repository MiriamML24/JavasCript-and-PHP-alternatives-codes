<! DOCTYPE html">
<html xmlns= "http://www.w3.org/1999/xhtml" xml:lang="es" lang= "es"/>
	<head>
		<meta http-equiv="Content-Type" content="text/html charset=UTF-8"/>
		<link href="./css/tiendaVirtual.css" rel="stylesheet" type = "text/css">	
		<title>Tienda virtual</title>
	</head>
	
	<body>
		<ul>
			<li>
				<a href = "indiceCliente.html">Volver</a>
			</li>
		</ul>
	</body>
</html>

<?php

	if(!isset($_SESSION['identificado'])){
	//	ob_start();
	//	require_once(dirname(__FILE__).'/html2pdf/vendor/autoload.php');
		session_start();
	//	$content = ob_get_clean(); 
		$content = "<div id = 'contenedor'><h2>Albarán de la compra realizada al cliente ";
		
		try{
			/* try { */
				$con = new PDO ('mysql:dbname=tienda;host=localhost', 'root', 'root');
				$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$con->exec("set names utf8");
				
				$nombreCliente = $con->prepare("Select * from clientes where clienteId like :cliente ");
				$nombreCliente->bindValue(":cliente", $_SESSION['identificado'], PDO::PARAM_INT);
				$nombreCliente->execute();
				$nombre = $nombreCliente->fetch(PDO::FETCH_ASSOC);
				$content .= $nombre['nombre']."</h1>";
				
				$mostrarLista = $con->prepare("select itempedido.*, productos.* from itempedido inner join productos on productos.productoId = itemPedido.productoId");
				$mostrarLista->execute();
				$content .= "<table style = 'text-align: center'><tr>
					<th style = 'background-color:#FFE4FD; width: 150px;'>Nombre del producto</th>
					<th style = 'background-color:#FFE4FD; width: 150px;'>Cantidad añadida</th>
					<th style = 'background-color:#FFE4FD; width: 150px;'>Precio articulo</th></tr>";
				while ($lista = $mostrarLista->fetch(PDO::FETCH_ASSOC)){
					$operacion = ($lista['unidades'] * $lista['precio'] * (100-$lista['iva'])/100 + ($lista['unidades'] * $lista['precio']));
					$content .=  "<tr><td style = 'background-color:#E4F3FF'>$lista[nombre]</td>
						<td style = 'background-color:#E4F3FF'>$lista[unidades]</td>
						<td style = 'background-color:#E4F3FF'>".$operacion."</td>
					</tr>";
				}
				$content .= "</table>";
				$total = $con->prepare("Select * from pedidos where clienteId like :cliente");
				$total->bindValue(":cliente", $_SESSION['identificado'], PDO::PARAM_INT);
				$total->execute();
				$precioTotal = $total->fetch(PDO::FETCH_ASSOC);
				$content .=  "<h4>Precio total de la compra es: " . $precioTotal['totalPedido']."</h4>";
				echo $content;
				 
				/* $html2pdf = new HTML2PDF('P', 'A4', 'es');
				$html2pdf->setDefaultFont('Arial');
				//$html2pdf->AddPage();
				$html2pdf->writeHTML($content);
				$html2pdf->Output('./pdf/albaran.pdf', 'I'); */
				
				require "./PHPMailer_5.2.4/class.phpmailer.php";
			 	$mail = new phpmailer();
				$mail->CharSet = "UTF-8";
				$mail->PluginDir = "PHPMailer_5.2.4/";
				$mail->Mailer = "gmail";
				$mail->Host = "smtp.gmail.com"; 
				$mail->AddAddress($nombre['email']);
				$mail->IsHTML(true);
				$mail->Subject = "Albarán de compra";
				$mail->Body = $content;
				//$mail->AddAtachment('./pdf/albaran.pdf'); 
				$mail->Send();
			
				$borrarPedido = $con->query("Delete from itempedido");
			/* }catch(HTML2PDF_exception $e) {
				echo $e;
				exit;		
			}  */
		}catch (PDOException $e){
			echo $e->getMessage();
		} 
	} else {
		header("Location:indice.html");
	}
?>