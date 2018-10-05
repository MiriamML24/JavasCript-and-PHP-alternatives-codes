<?php 

	class Usuario {
		private $DNI;
		private $nombre;
		private $prestamos;
		private $maxPrestamos;
		private $limitePrestamos;
		private $numPrestamos;
		
		function __construct($dni, $nom, $maxP, $lP){
			$this->DNI = $dni;
			$this->nombre = $nom;
			$this->prestamos = array($maxP);
			$this->maxPrestamos = $maxP;
			$this->limitePrestamos = $lP;
			$this->numPrestamos = 0;
		}
		function alcanzadoLimitePrestamos(){
			if ($this->numPrestamos >= $this->maxPrestamos){
				return true;
			} else {
				return false;				
			}
		}
		
		function añadeDocumentoPrestado($doc){
			if (!$this->alcanzadoLimitePrestamos()){
				if (!$doc->estaPrestado()){
					$this->prestamos[$this->numPrestamos] = $doc;
					$this->$numPrestamos++;
				}
			}
		}
		
		function eliminaDocumentoPrestado($codigo){
			$this->pos = buscaDocumentoPrestado($codigo);
			if ($this->pos != -1){
				for ($j = $this->pos; $j < $this->numPrestamos; $j++){
				  $this->prestamos[$j] = $this->prestamos[$j+1];
				}
				$this->numPrestamos--;
			}else {
				echo "El documento con código  $codigo no está prestado";
			}
		}
		
		function buscaDocumentoPrestado($codigo){
			for ($i = 0; i < $numPrestamos; $i++){
				if(strcmp($this->usuarios[$i]->getCodigo(), $codigo)){
					return $i;
				}
			}
			return -1;
		}
		public function getDNI() {
			return $this->DNI;
		}
		public function setDNI($DNI) {
			$this->DNI = DNI;
		}
		public function getNombre() {
			return $this->nombre;
		}
		public function setNombre($nombre) {
			$this->nombre = nombre;
		}
		public function getMaxPrestamos() {
			return $this->maxPrestamos;
		}
		public function setMaxPrestamos($maxPrestamos) {
			$this->maxPrestamos = maxPrestamos;
		}
		public function plazoPrestamoDocumento() {
			return $this->limitePrestamos;
		}
		public function setLimitePrestamos($limitePrestamos) {
			$this->limitePrestamos = limitePrestamos;
		}
		public function __toString(){
			return "DNI: ". $this->DNI." Nombre: ". $this->nombre." Préstamos: ". $this->numPrestamos;
		}
	}

?>