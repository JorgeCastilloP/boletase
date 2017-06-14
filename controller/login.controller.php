<?php
session_cache_limiter('public');
require_once 'dao/empresaDAO.php';
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
	/*
	public function Sesion(){

		if(!isset($_SESSION['usuario'])){
			
			if(isset($_REQUEST["usuario"]) && isset($_REQUEST["password"])){

				$emisordao=new EmisorDAO();

				$reg=$emisordao->validarUsuario(pg_escape_string($_REQUEST["usuario"]), pg_escape_string($_REQUEST["password"]));
				
				if($reg['respuesta'] == Constants::getFlagIncorrecto()){
					require_once 'view/login.html';
					echo '<script>$(document).ready(function(){ $("#modErrorLogeo").modal("show");}); </script>';		
				}else{
					$_SESSION["usuario"]=serialize($reg['emisor']);
					//print_r($reg['a_usuario']);

					require_once 'view/header.html';
					require_once 'view/blank.html';
					require_once 'view/footer.html';				
				}
		
			}else{
				require_once 'view/login.php';	
			}
			
		}else{
				require_once 'view/header.php';
		        require_once 'view/inicio/inicio.php';
		        require_once 'view/footer.php';			
		}


	}
	*/
}