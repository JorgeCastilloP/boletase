<?php

require_once 'model/auditoria.php';

class Perfil implements JsonSerializable
{
	
	private $Id;
	private $Empresa;
	private $Nombre;
	private $Descripcion;
	private $Estado;
	private $Auditoria;
	
 	public function __CONSTRUCT() {
 		$this->Id = null;
 		$this->Empresa = new Empresa();
 		$this->Nombre = null;
 		$this->Descripcion = null;
 		$this->Estado = null;
 		$this->Auditoria = null;
    }
	
    public function jsonSerialize() {
        return [
            'Id' => $this->Id,
            'Empresa' => $this->Empresa,
            'Nombre' => $this->Nombre,
            'Descripcion' => $this->Descripcion,
            'Estado' => $this->Estado,
            'Auditoria' => $this->Auditoria
        ];
    }

	public function getId(){
		return $this->Id;
	}
	public function getEmpresa(){
		return $this->Empresa;
	}
	public function getNombre(){
		return $this->Nombre;
	}
	public function getDescripcion(){
		return $this->Descripcion;
	}
	public function getEstado(){
		return $this->Estado;
	}
	public function getAuditoria(){
		return $this->Auditoria;
	}

	public function setId($Id){
		$this->Id = $Id;
	}
	public function setEmpresa($Empresa){
		$this->Empresa = $Empresa;
	}
	public function setNombre($Nombre){
		$this->Nombre = $Nombre;
	}
	public function setDescripcion($Descripcion){
		$this->Descripcion = $Descripcion;
	}
	public function setEstado($Estado){
		$this->Estado = $Estado;
	}
	public function setAuditoria($Auditoria){
		$this->Auditoria = $Auditoria;
	}
}
