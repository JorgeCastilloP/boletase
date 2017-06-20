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
	private $ListSubmenu;
	private $ListArea;
	private $ListTipoDocumento;
	
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
            'Auditoria' => $this->Auditoria,
            'ListSubmenu' => $this->ListSubmenu,
            'ListArea' => $this->ListArea,
            'ListTipoDocumento' => $this->ListTipoDocumento
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
	public function getListSubmenu(){
		return $this->ListSubmenu;
	}
	public function getListArea(){
		return $this->ListArea;
	}
	public function getListTipoDocumento(){
		return $this->ListTipoDocumento;
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
	public function setListSubmenu($ListSubmenu){
		$this->ListSubmenu = $ListSubmenu;
	}
	public function setListArea($ListArea){
		$this->ListArea = $ListArea;
	}
	public function setListTipoDocumento($ListTipoDocumento){
		$this->ListTipoDocumento = $ListTipoDocumento;
	}
}
