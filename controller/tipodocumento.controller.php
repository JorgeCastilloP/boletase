<?php
require_once 'model/tipodocumento.php';
require_once 'model/empresa.php';
require_once 'model/empleado.php';
require_once 'dao/tipodocumentoDAO.php';
class TipodocumentoController{

	private $dao;

	public function __CONSTRUCT(){
		$this->dao = new TipodocumentoDAO();
		$this->sess_usuario = unserialize($_SESSION['empleado']);
	}

	public function guardar(){
		/*
		$tipodocumento = new TipoDocumento()
		$tipodocumento->setDescripcion();
		$respuesta = $this->dao->guardar($tipodocumento);
		echo $respuesta->error_desc;
		*/
	}

	public function listar(){
		$Ruc = $this->sess_usuario->getEmpresa()->getRuc();;
		$Estado = 'A';
		echo json_encode($this->dao->listar($Ruc,$Estado));		
	}
}

