<?php

require_once 'dao/perfiltipodocumentoDAO.php';

class PerfiltipodocumentoController{

	private $dao;

	public function __CONSTRUCT(){
		$this->dao = new PerfilTipoDocumentoDAO();
	}
	
	public function listar(){
		//$Ruc = $this->sess_usuario->getEmpresa()->getRuc();;
		//$Estado = 'A';
		$idperfil = $_REQUEST['idperfil'];
		echo json_encode($this->dao->listar($idperfil));	

	}
}