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

	public function __CONSTRUCT(){
		$this->dao = new EmpleadoDAO();
		//$this->sess_usuario=unserialize($_SESSION['empleado']);
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

	public function eliminar(){
		$response='';
		$idEmpleado=$_POST["idempleado"];
		$idAuditoria=$_POST["idauditoria"];
		$ruc=$_POST["empresaruc"];
		$idPerfil=$_POST["idperfil"];
		$idArea=$_POST["idarea"];


		$auditoria=new Auditoria();
		$auditoria=Constants::getAuditoria($auditoria);
		$auditoria->setId($idAuditoria);
		$auditoriadao=new AuditoriaDAO();
		$response=$auditoriadao->guardar($auditoria);


		$empleado=new Empleado();
		$empleado->setId($idEmpleado);
		$empleado->setEstado('I');

		$empresa =new Empresa();
		$empresa->setRuc($ruc);

		$perfil=new Perfil();
		$perfil->setId($idPerfil);

		$area=new Area();
		$area->setId($idArea);


		$empleado->setEmpresa($empresa);
		$empleado->setPerfil($perfil);
		$empleado->setArea($area);


		if($response["error"]==Constants::FLAG_CORRECTO){
			$empleado->setAuditoria($response["auditoria"]);
			$response=$this->dao->guardar($empleado);

		}
		echo json_encode($response) ;

	}

	public function guardar(){
		$response='';

		$nombre =     $_POST["nombre"];
		$apellido  =  $_POST["apellido"];
		$idtdi  =  $_POST["idtdi"];
		$nrodocumentoidentidad  =  $_POST["nrodocumentoidentidad"];
		$correo  =  $_POST["correo"];
		$telefono  =  $_POST["telefono"];
		$usuario  =  $_POST["usuario"];
		$idarea  =  $_POST["idarea"];
		$idperfil  =  $_POST["idperfil"];
		$ruc ='';
		$estado = 'A';


		if(isset($_POST["ruc"])&& trim($_POST["ruc"])!=='')
			$ruc=$_POST["ruc"];
		else
			$ruc=$this->sess_usuario->getEmpresa()->getRuc();

		$empleado =new Empleado();


		if(isset($_POST["idempleado"]) && trim($_POST["idempleado"])!=='')
			$empleado->setId($_POST["idempleado"]);

		$empleado->setNombre($nombre);
		$empleado->setApellido($apellido);
		$empleado->setTipoDocumentoIdentidad($idtdi);
		$empleado->setNumeroDocumento($nrodocumentoidentidad);
		$empleado->setCorreo($correo);
		$empleado->setTelefono($telefono);
		$empleado->setUsuario($usuario);
		

		$empresa =new Empresa();
		$empresa->setRuc($ruc);
		$empleado->setEmpresa($empresa);


		$area = new Area();
		$area->setId($idarea);
		$empleado->setArea($area);

		$perfil= new Perfil();
		$perfil->setId($idperfil);
		$empleado->setPerfil($perfil);

		$auditoria =new Auditoria();
		$auditoria = Constants::getAuditoria($auditoria);

		if(isset($_POST["idauditoria"]) && trim($_POST["idauditoria"])!=='')
			$auditoria->setId($_POST["idauditoria"]);

		$auditoriadao = new AuditoriaDAO();
		$response=$auditoriadao->guardar($auditoria);


		if($response["error"] == Constants::FLAG_CORRECTO){
			$empleado->setAuditoria($response["auditoria"]);
			$response=$this->dao->guardar($empleado);
		}

		echo json_encode($response);


	}



}

