<?php

class PerfilSubmenu implements JsonSerializable
{
	
	private $Perfil;
	private $Submenu;
	private $Estado;
	private $Auditoria;


    public function jsonSerialize() {
        return [
            'Perfil' => $this->Perfil,
            'Submenu' => $this->Submenu,
            'Estado' => $this->Estado,
            'Auditoria' => $this->Auditoria
        ];
    }

	public function getPerfil(){
		return $this->Perfil;
	}
	public function getSubmenu(){
		return $this->Submenu;
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
	public function setSubmenu($Submenu){
		$this->Submenu = $Submenu;
	}	
	public function setEstado($Estado){
		$this->Estado = $Estado;
	}		
	public function setAuditoria($Auditoria){
		$this->Auditoria = $Auditoria;
	}

}
