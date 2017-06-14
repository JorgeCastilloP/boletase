<?php

class Submenu implements JsonSerializable
{
	
	private $Id;
	private $Menu;
	private $Descripcion;
	private $Estado;


    public function jsonSerialize() {
        return [
            'Id' => $this->Id,
            'Menu' => $this->Menu,
            'Descripcion' => $this->Descripcion,
            'Estado' => $this->Estado
        ];
    }

	public function getId(){
		return $this->Id;
	}
	public function getMenu(){
		return $this->Menu;
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
	public function setMenu($Menu){
		$this->Menu = $Menu;
	}	
	public function setDescripcion($Descripcion){
		$this->Descripcion = $Descripcion;
	}
	public function setEstado($Estado){
		$this->Estado = $Estado;
	}
}
