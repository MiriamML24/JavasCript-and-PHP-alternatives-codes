<?php 
	
	class UsuarioOcasional extends Usuario{
		private $maxPrestamosAUsuariosO = 2;
		private $limitePrestamosAUsuariosO = 15;
		
		function __construct($dni,$nom){
			parent::__construct($dni, $nom, $this->maxPrestamosAUsuariosO, $this->limitePrestamosAUsuariosO);
		}
		public function __toString(){
			return parent::__toString()." Usuario Ocasional";
		}
	}

?>