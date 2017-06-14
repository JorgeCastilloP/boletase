<?php

class Menu implements JsonSerializable
{
	
	private $Id;
	private $Descripcion;
	private $Estado;


    public function jsonSerialize() {
        return [
            'Id' => $this->Id,
            'Descripcion' => $this->Descripcion,
            'Estado' => $this->Estado
        ];
    }

	public function getId(){
		return $this->Id;
	}
	public function getDescripcion(){
		return $this->Descripcion;
	}
	public function getEstado(){
		return $this->Estado;
	}

	public function setId($Id){
		$this->Id = $Id;
	}
	public function setDescripcion($Descripcion){
		$this->Descripcion = $Descripcion;
	}
	public function setEstado($Estado){
		$this->Estado = $Estado;
	}
}
