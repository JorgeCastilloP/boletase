<?php
require_once 'model/documento.php';
require_once 'model/empleado.php';
require_once 'model/empresa.php';

require_once 'dao/documentoDAO.php';
require_once 'dao/auditoriaDAO.php';

class DocumentoController{

	private $dao;
	private $sess_usuario;


	public function __CONSTRUCT(){
		$this->dao = new DocumentoDAO();
		$this->sess_usuario = unserialize($_SESSION['empleado']);

	}

	public function listarTablaBoleta(){

		$iDisplayStart=0;
		$iDisplayLength=-1;
		$sSearch="";
		$sSortDir_0="";
		$iSortCol_0="";


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

		$Estado = $this->sess_usuario->getEstado();
		$Ruc  =	$this->sess_usuario->getEmpresa()->getRuc();
		$TipoDocumento=1;
		$reg=$this->dao->listarTablaDocumentos(
				$Estado,$Ruc,$TipoDocumento,
				$iDisplayStart,$iDisplayLength,
				$sSearch,
				$sSortDir_0, $iSortCol_0);

		echo  json_encode($reg);
	
	}

	public function listarPorEmpleado(){

		$Id = $_POST["id"];
		$Ruc = $_POST["ruc"];
		$Estado = 'A';

		echo json_encode($this->dao->listar($Id, $Estado, $Ruc, null));
	}
	public function obtenerUltimaActualizacion(){
		$Ruc = $this->sess_usuario->getEmpresa()->getRuc();
		/*
		

		$date = date_create_from_format('d/M/Y:H:i:s', $this->dao->obtenerUltimaActualizacion($Ruc));;
*/
		$fecha = strtotime($this->dao->obtenerUltimaActualizacion($Ruc));
		echo date("Y-m-d H:i:s", $fecha);
	}

}

