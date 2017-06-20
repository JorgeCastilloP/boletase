<?php

	require_once 'model/tipodocumentoidentidad.php';

	require_once 'dao/tipodocumentoidentidadDAO.php';

class TipoDocumentoIdentidadController{
	private $dao;




	public function __CONSTRUCT(){
		$this->dao=new TipodocumentoidentidadDAO();

	}

	public function listar(){
		$Estado='A';
		$reg=$this->dao->listar($Estado);
		echo json_encode($reg);

	}

}