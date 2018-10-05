<?php 
	error_reporting(0);
	class Biblioteca {
		private $usuarios;
		private $documentos;
		private $maxUsuarios = 50;
		private $numUsu;
		private $maxDocus = 200;
		private $numDocus;
		
		function __construct(){
			$this->usuarios = array($this->maxUsuarios);
			$this->documentos= array($this->maxDocus);
			$this->numUsu = 0;
			$this->numDocus = 0;
		}
		public function añadeDocumento($doc){
			if ($doc != null){
				$this->documentos[$this->numDocus]= $doc;
				$this->numDocus++;
			}
		}
		public function añadeUsuario($user){
			if ($user != null){
				$this->usuarios[$this->numUsu] = $user;
				$this->numUsu++;
			}
		}
		public function buscaDocumento($codigo){
			for ($i = 0; $i < $this->numDocus; $i++){
				if (strcmp($this->documentos[$i]->getCodigo(),$codigo)){
					return $this->documentos[$i];
				}
			}
			return null;
		}
		public function buscaUsuario($dni){
			for ($i = 0; i < $this->numUsu; $i++){
				if(strcmp($this->usuarios[$i]->getDNI(), $dni)){
					return true;
				}
			}
			return false;
		}
		public function prestaDocumento($doc, $user){
			if ($this->buscaUsuario($user->getDNI())){
				if ($this->buscaDocumento($doc->getCodigo()) != null){
					$doc->prestaAUsuario($user);
				}
			}
		}
		public function devuelveDocumento($doc){
			if ($doc->estaPrestado()){
				$doc->devuelve();
			}else {
				echo"Documento con código: ".$doc->getCodigo()." no estaba prestado";
			}
		}
		public function buscaDocumentos($texto){
			foreach(Documento as $v => $this->documentos){
				if (strrpos($v->getTitulo(), $texto) != -1){
					return $v;
				}
			}
			return null;
		}
		public function muestraInformePrestamos(){
			for($i = 0; $i < $this->numDocus; $i++){
				if ($this->documentos[$i]->estaPrestado()){
					echo $this->documentos[$i];
				}
			}
		}
	}

?>