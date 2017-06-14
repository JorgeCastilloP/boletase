<?php

class TipoDocumento implements JsonSerializable
{
	
	private $Id;
	private $Empresa;
	private $Descripcion;
	private $Estado;
	private $Auditoria;


    public function jsonSerialize() {
        return [
            'Id' => $this->Id,
            'Empresa' => $this->Empresa,
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
