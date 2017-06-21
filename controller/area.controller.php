<?php

require_once 'model/area.php';
require_once 'model/empresa.php';
require_once 'model/empleado.php';

require_once 'dao/areaDAO.php';
require_once 'dao/auditoriaDAO.php';

class AreaController{

	private $dao;
	private $sess_usuario;


	public function __CONSTRUCT(){
		$this->dao = new AreaDAO();
		$this->sess_usuario = unserialize($_SESSION['empleado']);
	}	
	public function eliminar(){

		$response = '';

		$idArea  = $_POST["idarea"];
		$idAuditoria  = $_POST["idauditoria"];
		$ruc=$_POST["empresaruc"];

		$auditoria = new Auditoria();
		$auditoria = Constants::getAuditoria($auditoria);
		$auditoria->setId($idAuditoria);
		$auditoriadao = new AuditoriaDAO();
		$response = $auditoriadao->guardar($auditoria);



		$area = new Area();
		$area->setId($idArea);
		$area->setEstado('I');

		$empresa = new Empresa();
		$empresa->setRuc($ruc);
		$area->setEmpresa($empresa);

		if($response["error"] == Constants::FLAG_CORRECTO){
			$area->setAuditoria($response["auditoria"]);
			$response = $this->dao->guardar($area);
		}			
		echo json_encode($response);
	}
	public function listar(){
		$Ruc = $this->sess_usuario->getEmpresa()->getRuc();;
		$Estado = 'A';
		echo json_encode($this->dao->listar($Ruc,$Estado));		
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

		$area = new Area();

		if(isset($_POST["idarea"]) && trim($_POST["idarea"]) !=='')
			$area->setId($_POST["idarea"]);

		$area->setNombre($nombre);
		$area->setDescripcion($descripcion);

		$empresa = new Empresa();
		$empresa->setRuc($ruc);
		$area->setEmpresa($empresa);

		$auditoria = new Auditoria();
		$auditoria = Constants::getAuditoria($auditoria);		

		if(isset($_POST["idauditoria"]) && trim($_POST["idauditoria"]) !=='')
			$auditoria->setId($_POST["idauditoria"]);

		$auditoriadao = new AuditoriaDAO();
		$response = $auditoriadao->guardar($auditoria);

		if($response["error"] == Constants::FLAG_CORRECTO){
			$area->setAuditoria($response["auditoria"]);
			$response = $this->dao->guardar($area);
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

	public function listarcbo()
	{
		$Estado = 'A';
		$ruc=$this->sess_usuario->getEmpresa()->getRuc();
		$reg=$this->dao->listar($ruc,$Estado);

		echo json_encode($reg);
		

	}


}