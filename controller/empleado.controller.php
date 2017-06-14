<?php
//session_cache_limiter('public');
require_once 'dao/empleadoDAO.php';
require_once 'model/empleado.php';

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

