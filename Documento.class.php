<?php 

	class Documento {
		private $codigo;
		private $titulo;
		private $prestadoA;

		function __construct($cod, $til){
			$this->codigo = $cod;
			$this->titulo = $til;
			$this->prestadoA = null;
		}
		public function estaPrestado(){
			if ($this->prestadoA != null){
				return true;
			}
			return false;
		}
		public function prestaAUsuario($user){
			if ($this->prestadoA != null){
				echo "Prestado a ". $this->prestadoA->getNombre();
			}else{
				$this->prestadoA = $user;
				$user->añadeDocumentoPrestado($this);
			}
		}
		public function devuelve(){
			$this->prestadoA->eliminaDocumentoPrestado($this->codigo);
			$this->prestadoA = null;
		}
		public function plazoPrestamo(){
			if ($this->prestadoA != null){
				return $this->prestadoA->plazoPrestamoDocumento();
			}
			return -1;
		}
		public function getCodigo() {
			return $this->codigo;
		}
		public function setCodigo($codigo) {
			$this->codigo = $codigo;
		}
		public function getTitulo() {
			return $this->titulo;
		}
		public function setTitulo($titulo) {
			$this->titulo = $titulo;
		}
		public function getPrestadoA() {
			return $this->prestadoA;
		}
		public function setPrestadoA($prestadoA) {
			$this->prestadoA = $prestadoA;
		}
		public function __toString(){
			return "Código: ". $this->codigo."Título: ". $this->titulo." Prestado a: ".$this->prestadoA."<br>";
		}
	}

?>