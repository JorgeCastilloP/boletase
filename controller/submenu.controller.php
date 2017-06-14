<?php

require_once 'model/menu.php';
require_once 'model/submenu.php';

require_once 'dao/submenuDAO.php';
require_once 'dao/auditoriaDAO.php';

class submenuController{

	private $dao;
	private $sess_usuario;


	public function __CONSTRUCT(){
		$this->dao = new SubmenuDAO();
		//$this->sess_usuario = unserialize($_SESSION['empleado']);
	}

	public function listar(){

		$Estado = 'A';
		/*
		$array_submenu = $dao->listar($Estado);
		for($i=0; $i<count($reg); $i++)
		{
			$data['data'] = $reg;
		}
		*/			
		echo json_encode($this->dao->listar($Estado));
	}
}