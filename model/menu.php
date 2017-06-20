<?php

class Menu implements JsonSerializable
{
	
	private $Id;
	private $Descripcion;
	private $Estado;
	private $Icon;
	private $ListSubmenu;


    public function jsonSerialize() {
        return [
            'Id' => $this->Id,
            'Descripcion' => $this->Descripcion,
            'Estado' => $this->Estado,
            'Icon' => $this->Icon,
            'ListSubmenu' => $this->ListSubmenu
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
	public function getIcon(){
		return $this->Icon;
	}
	public function getListSubmenu(){
		return $this->ListSubmenu;
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
	public function setIcon($Icon){
		$this->Icon = $Icon;
	}	
	public function setListSubmenu($ListSubmenu){
		$this->ListSubmenu = $ListSubmenu;
	}		
}
