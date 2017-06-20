<?php

require_once 'util/const.php';
require_once 'model/menu.php';
require_once 'model/submenu.php';
require_once 'model/empleado.php';
require_once 'model/area.php';
require_once 'model/tipodocumento.php';

class RootController{

	private $model;
	private $mensaje;

	private $sess_lstarea;
	private $sess_lstsubmenu;
	private $sess_lsttipodocumento;

	public function __CONSTRUCT(){

		if(!isset($_SESSION['empleado'])){
			header("Location: ".Constants::getLinkApp());
		}else{
			$this->sess_empleado = unserialize($_SESSION["empleado"]);
			$this->sess_lstarea = unserialize($_SESSION["lstarea"]);
			$this->sess_lstsubmenu = unserialize($_SESSION["lstsubmenu"]);
			$this->sess_lsttipodocumento = unserialize($_SESSION["lsttipodocumento"]);
		}

		$this->mensaje = '<script>$(document).ready(function(){ bootbox.alert({title: m_001,message: m_0069,size: "small",className: "animated bounceInRight",callback: function(){ /* your callback code */ }});}); </script>';
	}

	public function Areas(){
		require_once 'view/header.html';
		require_once 'view/mantenimiento/area/area.html';
		require_once 'view/footer.html';
	}

	public function Boletas(){

		$flag = Constants::FLAG_INCORRECTO;

		foreach($this->sess_lstsubmenu as $submenu){
   			if($submenu->getId() == Constants::COD_PAG_DOC_BOLETA){
   				$flag = Constants::FLAG_CORRECTO;
   			}
		}

		if ($flag == Constants::FLAG_CORRECTO) {

			require_once 'view/header.html';
			require_once 'view/documentos/boletas.html';
			require_once 'view/footer.html';		

		}else{
			require_once 'view/header.html';
			require_once 'view/footer.html';
			echo $this->mensaje;
		}


	}	

	public function Colaboradores(){

		$flag = Constants::FLAG_INCORRECTO;

		foreach($this->sess_lstsubmenu as $submenu){
   			if($submenu->getId() == Constants::COD_PAG_COLABORADORES){
   				$flag = Constants::FLAG_CORRECTO;
   			}
		}

		if ($flag == Constants::FLAG_CORRECTO) {

			require_once 'view/header.html';
			require_once 'view/colaboradores.html';
			require_once 'view/footer.html';	

		}else{
			require_once 'view/header.html';
			require_once 'view/footer.html';
			echo $this->mensaje;
		}		

	}

	public function Inicio(){
		require_once 'view/header.html';
		require_once 'view/colaboradores.html';
		require_once 'view/footer.html';	
	}		

	public function Perfiles(){

		$flag = Constants::FLAG_INCORRECTO;

		foreach($this->sess_lstsubmenu as $submenu){
   			if($submenu->getId() == Constants::COD_PAG_MAN_PERFIL){
   				$flag = Constants::FLAG_CORRECTO;
   			}
		}

		if ($flag == Constants::FLAG_CORRECTO) {

			require_once 'view/header.html';
			require_once 'view/mantenimiento/perfil/perfil.html';
			require_once 'view/footer.html';	

		}else{
			require_once 'view/header.html';
			require_once 'view/footer.html';
			echo $this->mensaje;
		}			

	}

	public function Usuarios(){
		
		$flag = Constants::FLAG_INCORRECTO;

		foreach($this->sess_lstsubmenu as $submenu){
   			if($submenu->getId() == Constants::COD_PAG_MAN_PERFIL){
   				$flag = Constants::FLAG_CORRECTO;
   			}
		}

		if ($flag == Constants::FLAG_CORRECTO) {

			require_once 'view/header.html';
			require_once 'view/mantenimiento/usuarios/usuarios.html';
			require_once 'view/footer.html';

		}else{
			require_once 'view/header.html';
			require_once 'view/footer.html';
			echo $this->mensaje;
		}		

	}			

}

