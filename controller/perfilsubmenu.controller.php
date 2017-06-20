<?php

require_once 'model/menu.php';
require_once 'dao/menuDAO.php';
require_once 'dao/perfilsubmenuDAO.php';

class PerfilsubmenuController{

	private $dao;

	public function __CONSTRUCT(){
		$this->dao = new PerfilSubmenuDAO();
	}
	
	public function listar(){
		//$Ruc = $this->sess_usuario->getEmpresa()->getRuc();;
		//$Estado = 'A';

		$menudao = new menuDAO();
		$idperfil = $_REQUEST['idperfil'];
		$lst = $menudao->listar('A');

		$a_respuesta = array();

		foreach($lst as $menu) {
			$lstSubmenu = $this->dao->listar($idperfil, $menu->getId());
			$menu->setListSubmenu($lstSubmenu);
			array_push($a_respuesta, $menu);
		}

		echo json_encode($a_respuesta);	

	}
}