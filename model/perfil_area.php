<?php

class PerfilArea implements JsonSerializable
{
	
	private $Perfil;
	private $Area;
	private $Estado;
	private $Auditoria;


    public function jsonSerialize() {
        return [
            'Perfil' => $this->Perfil,
            'Area' => $this->Area,
            'Estado' => $this->Estado,
            'Auditoria' => $this->Auditoria
        ];
    }

	public function getPerfil(){
		return $this->Perfil;
	}
	public function getArea(){
		return $this->Area;
	}	
	public function getEstado(){
		return $this->Estado;
	}
	public function getAuditoria(){
		return $this->Auditoria;
	}	

	public function setPerfil($Perfil){
		$this->Perfil = $Perfil;
	}
	public function setArea($Area){
		$this->Area = $Area;
	}	
	public function setEstado($Estado){
		$this->Estado = $Estado;
	}		
	public function setAuditoria($Auditoria){
		$this->Auditoria = $Auditoria;
	}

}
