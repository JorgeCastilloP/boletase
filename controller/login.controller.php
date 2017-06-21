<?php
session_cache_limiter('public');
require_once 'model/empleado.php';
require_once 'model/area.php';
require_once 'model/empresa.php';
require_once 'model/perfil.php';


require_once 'dao/empleadoDAO.php';
require_once 'dao/auditoriaDAO.php';
require_once 'dao/perfilareaDAO.php';
require_once 'dao/menuDAO.php';
require_once 'dao/perfilsubmenuDAO.php';
require_once 'dao/perfiltipodocumentoDAO.php';
class LoginController{

	public function Index(){
		session_destroy();
/*
		require_once 'view/header.html';
		require_once 'view/blank.html';
		require_once 'view/footer.html';	
*/
		require_once 'view/login.html';
	}


	public function Inicio(){
		/*
		$dao = new EmpresaDAO();
		$sess_empresa = $dao->Obtener('20100011884');
		$_SESSION["empresa"] = serialize($sess_empresa);
		$sess_empresa = unserialize($_SESSION["empresa"]);
		print_r($sess_empresa);		
		*/
		require_once 'view/header.html';
		require_once 'view/colaboradores.html';
		require_once 'view/footer.html';	
	}	
	public function Sesion(){

		if(isset($_REQUEST["usuario"]) && isset($_REQUEST["password"])){

			$dao = new EmpleadoDAO();
			
			$respuesta = $dao->validar(pg_escape_string($_REQUEST["usuario"]), pg_escape_string($_REQUEST["password"]), 'A');

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

				$_SESSION["lstmenu"] = serialize($a_respuesta);

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