<?php 
	include "Documento.class.php";
	class Revista extends Documento{

		function __construct($cod, $til){
			parent::__construct($cod, $til);
		}
		public function plazoPrestamo(){ 
			return parent::plazoPrestamo()/3;
		}
	}
?>