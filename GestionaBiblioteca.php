<?php 
	
	include "Revista.class.php";
	include "Biblioteca.class.php";
	include "Libro.class.php";
	include "Socio.class.php";
	include "UsuarioOcasional.class.php";
	
	$atenea = new Biblioteca();
	$l1 = new Libro("001","Leyendas",1850);
	$atenea->añadeDocumento($l1);
	$l2 = new Libro("002","El Quijote",1590);
	$atenea->añadeDocumento($l2);
	$r1 = new Revista("003","National Geography");
	$atenea->añadeDocumento($r1);
	$juan = new Socio("123456","Juan");
	$atenea->añadeUsuario($juan);
	$atenea->añadeUsuario(new UsuarioOcasional("76434555","Pedro"));
	$atenea->devuelveDocumento($l1);
	$atenea->prestaDocumento($l1, $juan);
	$atenea->prestaDocumento($r1, $juan);
	$atenea->muestraInformePrestamos();

?>