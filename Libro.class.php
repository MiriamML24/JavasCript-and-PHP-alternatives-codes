<?php 
	
	class Libro extends Documento {
		private $añoPublicacion;
		
		function __construct($cod, $til, $anio){
			parent::__construct($cod, $til);
			$this->añoPublicacion = $anio;
		}
		
		public function getAñoPublicacion() {
			return $this->añoPublicacion;
		}
		public function setAñoPublicacion($añoPublicacion) {
			$this->añoPublicacion = $añoPublicacion;
		}
		public function __toString(){
			return parent::__toString()." Año publicación: ".$this->añoPublicacion;
		}
	}

?>