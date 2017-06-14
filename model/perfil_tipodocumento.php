<?php

class PerfilTipoDocumento implements JsonSerializable
{
	
	private $Perfil;
	private $TipoDocumento;
	private $Estado;
	private $Auditoria;


    public function jsonSerialize() {
        return [
            'Perfil' => $this->Perfil,
            'TipoDocumento' => $this->TipoDocumento,
            'Estado' => $this->Estado,
            'Auditoria' => $this->Auditoria
        ];
    }

	public function getPerfil(){
		return $this->Perfil;
	}
	public function getTipoDocumento(){
		return $this->TipoDocumento;
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
	public function setTipoDocumento($TipoDocumento){
		$this->TipoDocumento = $TipoDocumento;
	}	
	public function setEstado($Estado){
		$this->Estado = $Estado;
	}		
	public function setAuditoria($Auditoria){
		$this->Auditoria = $Auditoria;
	}

}
