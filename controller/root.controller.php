<?php
class RootController{
	public function Areas(){
		require_once 'view/header.html';
		require_once 'view/mantenimiento/area/area.html';
		require_once 'view/footer.html';
	}
	public function Boletas(){
		require_once 'view/header.html';
		require_once 'view/documentos/boletas.html';
		require_once 'view/footer.html';
	}	
	public function Colaboradores(){
		require_once 'view/header.html';
		require_once 'view/colaboradores.html';
		require_once 'view/footer.html';
	}	
	public function Inicio(){
		require_once 'view/header.html';
		require_once 'view/colaboradores.html';
		require_once 'view/footer.html';	
	}		
	public function Perfiles(){
		require_once 'view/header.html';
		require_once 'view/mantenimiento/perfil/perfil.html';
		require_once 'view/footer.html';
	}
	public function Usuarios(){
		require_once 'view/header.html';
		require_once 'view/mantenimiento/usuarios/usuarios.html';
		require_once 'view/footer.html';
	}			
}

