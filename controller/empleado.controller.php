<?php
//session_cache_limiter('public');
require_once 'dao/empleadoDAO.php';
require_once 'model/empleado.php';

require_once 'dao/perfilareaDAO.php';

require_once 'dao/menuDAO.php';
require_once 'dao/perfilsubmenuDAO.php';

require_once 'dao/perfiltipodocumentoDAO.php';

class EmpleadoController{

	private $dao;

	public function __CONSTRUCT(){
		$this->dao = new EmpleadoDAO();
	}
	public function Sesion(){
		if(isset($_REQUEST["usuario"]) && isset($_REQUEST["password"])){
			
			$respuesta = $this->dao->validar(pg_escape_string($_REQUEST["usuario"]), pg_escape_string($_REQUEST["password"]), 'A');

			if($respuesta["error"] == Constants::FLAG_CORRECTO){
				$_SESSION["empleado"] = serialize($respuesta["empleado"]);

				//listando las areas
				$padao = new PerfilAreaDAO();
				$_SESSION["lstarea"] = serialize($padao->listar($respuesta["empleado"]->getPerfil()->getId()));

				//listando los submenu
				$a_respuesta = array();

				$mdao = new menuDAO();
				$lst = $mdao->listar('A');

				$psdao = new PerfilSubmenuDAO();

				foreach($lst as $menu) {
					$lstSubmenu = $psdao->listar($respuesta["empleado"]->getPerfil()->getId(), $menu->getId());
					$menu->setListSubmenu($lstSubmenu);
					array_push($a_respuesta, $menu);
				}

				$_SESSION["lstsubmenu"] = serialize($a_respuesta);

				//listando los tipos de documento
				$ptdao = new PerfilTipoDocumentoDAO();
				$_SESSION["lsttipodocumento"] = serialize($ptdao->listar($respuesta["empleado"]->getPerfil()->getId()));

				require_once 'view/header.html';
		        require_once 'view/footer.html';

			}else{
				require_once 'view/login.html';
			}

		}else{
			require_once 'view/login.html';
		}
	}
}

