<?php

class TipoDocumentoIdentidad implements JsonSerializable
{
	
	private $Id;
	private $Nombre;
	private $Estado;


    public function jsonSerialize() {
        return [
            'Id' => $this->Id,
            'Nombre' => $this->MeNombrenu,
            'Estado' => $this->Estado
        ];
    }

	public function getId(){
		return $this->Id;
	}
	public function getNombre(){
		return $this->Nombre;
	}	
	public function getEstado(){
		return $this->Estado;
	}

	public function setId($Id){
		$this->Id = $Id;
	}
	public function setNombre($Nombre){
		$this->Nombre = $Nombre;
	}	
	public function setEstado($Estado){
		$this->Estado = $Estado;
	}
}
