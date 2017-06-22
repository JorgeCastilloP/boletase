<?php

require_once 'model/perfil.php';
require_once 'model/empresa.php';
require_once 'model/empleado.php';

require_once 'dao/perfilDAO.php';


class PerfilController{

	private $dao;
	private $sess_usuario;


	public function __CONSTRUCT(){
		$this->dao = new PerfilDAO();
		$this->sess_usuario = unserialize($_SESSION['empleado']);
	}	
	public function eliminar(){

		$response = '';

		$idPerfil  = $_POST["idperfil"];
		$perfil = new Perfil();

		$auditoria = new Auditoria();
		$auditoria = Constants::getAuditoria($auditoria);

		$perfil->setId($idPerfil);
		$perfil->setAuditoria($auditoria);
		$perfil->setEstado('I');
			
		echo json_encode($this->dao->guardar($perfil));
	}
	public function listarTabla(){

		$iDisplayStart=0;
		$iDisplayLength=-1;
		$sSearch="";
		$sSortDir_0 ="";
		$iSortCol_0 ="";

		//$BusquedaGlobal = $_REQUEST["BusquedaGlobal"];
		if(isset($_REQUEST["start"]))
		{
			$iDisplayStart = $_REQUEST["start"];
		}

		if(isset($_REQUEST["length"]))
		{
			$iDisplayLength = $_REQUEST["length"];
		}

		if(isset($_REQUEST["search"]['value']))
		{			
			if($_REQUEST["search"]['value'] != "")
			{
				$iDisplayStart = $_REQUEST["start"];
				$iDisplayLength = $_REQUEST["length"];			
				$sSearch = pg_escape_string($_REQUEST["search"]['value']);
			}	
		}

		if(isset($_REQUEST["order"]))
		{
			$orden = $_REQUEST["order"];
			$sSortDir_0 = $orden[0]['dir'];
			$iSortCol_0 = $orden[0]['column'];
		}
		$Estado = 'A';
		$Ruc = $this->sess_usuario->getEmpresa()->getRuc();

		$reg = $this->dao->listarTabla(
			$Ruc, $Estado, 
			$iDisplayStart, $iDisplayLength,
			$sSearch,
			$sSortDir_0, $iSortCol_0);

		echo json_encode($reg);	

	}
	public function guardar(){


		$response = '';

		$nombre  = $_POST["nombre"];
		$descripcion  = $_POST["descripcion"];
		$ruc = '';

		$lstSubmenu = $_POST["lstSubmenu"];
		$lstArea = $_POST["lstArea"];
		$lstTipoDocumento = $_POST["lstTipoDocumento"];

		if(isset($_POST["ruc"]) && trim($_POST["ruc"]) !=='')
			$ruc = $_POST["ruc"];
		else
			$ruc = $this->sess_usuario->getEmpresa()->getRuc();

		$perfil = new Perfil();

		if(isset($_POST["idperfil"]) && trim($_POST["idperfil"]) !=='')
			$perfil->setId($_POST["idperfil"]);

		$perfil->setNombre($nombre);
		$perfil->setDescripcion($descripcion);

		$empresa = new Empresa();
		$empresa->setRuc($ruc);
		$perfil->setEmpresa($empresa);

		$auditoria = new Auditoria();
		$auditoria = Constants::getAuditoria($auditoria);		
		$perfil->setAuditoria($auditoria);

		$perfil->setListSubmenu($lstSubmenu);
		$perfil->setListArea($lstArea);
		$perfil->setListTipoDocumento($lstTipoDocumento);

		$response = $this->dao->guardar($perfil);	


		echo json_encode($response);
	}

	public function listarcbo(){

		$Estado= 'A';
		$ruc = $this->sess_usuario->getEmpresa()->getRuc();
		$reg = $this->dao->listar($ruc,$Estado);

		echo json_encode($reg);
	
	}
}