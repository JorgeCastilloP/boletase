<?php

require_once 'model/perfil.php';
require_once 'model/area.php';
require_once 'model/empresa.php';
require_once 'model/empleado.php';

require_once 'dao/perfilDAO.php';
require_once 'dao/auditoriaDAO.php';

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
		$idAuditoria  = $_POST["idauditoria"];

		$auditoria = new Auditoria();
		$auditoria = Constants::getAuditoria($auditoria);
		$auditoria->setId($idAuditoria);
		$auditoriadao = new AuditoriaDAO();
		$response = $auditoriadao->guardar($auditoria);


		$perfil = new Perfil();
		$perfil->setId($idPerfil);
		$perfil->setEstado('I');

		if($response["error"] == Constants::FLAG_CORRECTO){
			$perfil->setAuditoria($response["auditoria"]);
			$response = $this->dao->guardar($perfil);
		}			
		echo json_encode($response);
	}
	public function listar(){

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

		$reg = $this->dao->listarTabla(
			$Estado, 
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

		if(isset($_POST["idauditoria"]) && trim($_POST["idauditoria"]) !=='')
			$auditoria->setId($_POST["idauditoria"]);

		$auditoriadao = new AuditoriaDAO();
		$response = $auditoriadao->guardar($auditoria);

		if($response["error"] == Constants::FLAG_CORRECTO){
			$perfil->setAuditoria($response["auditoria"]);
			$response = $this->dao->guardar($perfil);
		}

		echo json_encode($response);

		/*
		$nombre  = $_POST["nombre"];
		$descripcion  = $_POST["descripcion"];
		$ruc = $_POST["ruc"];		
		$idperfil = $_POST["idperfil"];
		$idauditoria = $_POST["idauditoria"];
		echo json_encode(
			array(
				"nombre"=>$nombre,
				"descripcion"=>$descripcion,
				"ruc"=>$ruc,
				"idperfil"=>$idperfil,
				"idauditoria"=>$idauditoria
				));
		*/	
	}
}