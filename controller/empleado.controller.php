<?php
//session_cache_limiter('public');

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

class EmpleadoController{

	private $dao;
	private $sess_usuario;

	public function __CONSTRUCT(){
		$this->dao = new EmpleadoDAO();
		$this->sess_usuario=unserialize($_SESSION['empleado']);
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
			$Ruc, 
			$Estado, 
			$iDisplayStart, $iDisplayLength,
			$sSearch,
			$sSortDir_0, $iSortCol_0);

		echo json_encode($reg);	

	}

	public function eliminar(){
		$response='';
		$idEmpleado=$_POST["idempleado"];

		$empleado=new Empleado();


		$auditoria=new Auditoria();
		$auditoria=Constants::getAuditoria($auditoria);

		$empleado->setAuditoria($auditoria);
		$empleado->setId($idEmpleado);
		$empleado->setEstado('I');
	
		echo json_encode($this->dao->guardar($empleado));

	}

	public function guardar(){

		$response='';
		$ruc ='';

		if(isset($_POST["ruc"])&& trim($_POST["ruc"])!=='')
			$ruc=$_POST["ruc"];
		else
			$ruc=$this->sess_usuario->getEmpresa()->getRuc();

		$empleado =new Empleado();


		if(isset($_POST["idempleado"]) && trim($_POST["idempleado"])!=='')
			$empleado->setId($_POST["idempleado"]);

		$empleado->setNumeroDocumento($_POST["nrodocumentoidentidad"]);
		$empleado->setNombres($_POST["nombre"]);
		$empleado->setApellidos($_POST["apellido"]);
		$empleado->setCorreo($_POST["correo"]);
		$empleado->setTelefono($_POST["telefono"]);
		$empleado->setUsuario($_POST["usuario"]);
		$empleado->setPassword('123456');
		
		$tdi = new TipoDocumentoIdentidad();
		$tdi->setId($_POST["idtdi"]);
		$empleado->setTipoDocumentoIdentidad($tdi);

		$empresa =new Empresa();
		$empresa->setRuc($ruc);
		$empleado->setEmpresa($empresa);

		$area = new Area();
		$area->setId($_POST["idarea"]);
		$empleado->setArea($area);

		$perfil= new Perfil();
		$perfil->setId($_POST["idperfil"]);
		$empleado->setPerfil($perfil);

		$auditoria =new Auditoria();
		$auditoria = Constants::getAuditoria($auditoria);
		$empleado->setAuditoria($auditoria);

		$response = $this->dao->guardar($empleado);

		echo json_encode($response);


	}



}

