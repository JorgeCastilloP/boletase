<?php
require_once 'model/tipodocumento.php';
require_once 'dao/tipodocumentoDAO.php';
class TipodocumentoController{

	private $dao;

	public function __CONSTRUCT(){
		$this->dao = new TipodocumentoDAO();
	}

	public function guardar(){
		$tipodocumento = new TipoDocumento()


		$tipodocumento->setDescripcion();
		$respuesta = $this->dao->guardar($tipodocumento);
		echo $respuesta->error_desc;
	}

	public function listar(){
		
	}
}

