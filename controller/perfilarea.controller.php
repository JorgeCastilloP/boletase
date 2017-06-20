<?php

require_once 'dao/perfilareaDAO.php';

class PerfilareaController{

	private $dao;

	public function __CONSTRUCT(){
		$this->dao = new PerfilAreaDAO();
	}
	
	public function listar(){
		//$Ruc = $this->sess_usuario->getEmpresa()->getRuc();;
		//$Estado = 'A';
		$idperfil = $_REQUEST['idperfil'];
		echo json_encode($this->dao->listar($idperfil));	

	}
}