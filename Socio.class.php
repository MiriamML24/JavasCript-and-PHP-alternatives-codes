<?php 
	include "Usuario.class.php";
	class Socio extends Usuario {
		private $maxPrestamosASocios = 20;
		private $limitePrestamosASocios = 30;
		
		function __construct($dni, $nom){
			parent::__construct($dni, $nom, $this->maxPrestamosASocios, $this->limitePrestamosASocios);
		}
		public function __toString(){
			return parent::__toString()." Socio";
		}
	}

?>