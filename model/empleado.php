<?php
require_once 'model/tipodocumentoidentidad.php';
require_once 'model/perfil.php';
require_once 'model/area.php';
require_once 'model/empresa.php';

class Empleado implements JsonSerializable
{
	
	private $Id;
	private $NumeroDocumento;
	private $TipoDocumentoIdentidad;
	private $Empresa;
	private $Perfil;
	private $Area;
	private $Nombres;
	private $Apellidos;
	private $NombreCompleto;
	private $Usuario;
	private $Password;
	private $Correo;
	private $Telefono;
	private $Estado;
	private $Auditoria;

 	public function __CONSTRUCT() {
 		$this->Id = null;
 		$this->NumeroDocumento = null;
 		$this->TipoDocumentoIdentidad = new TipoDocumentoIdentidad();
 		$this->Empresa = new Empresa();
 		$this->Perfil = new Perfil();
 		$this->Area = new Area();
 		$this->Nombres = null;
 		$this->Apellidos = null;
 		$this->NombreCompleto = null;
 		$this->Usuario = null;
 		$this->Password = null;
 		$this->Correo = null;
 		$this->Telefono = null;
 		$this->Estado = null;
 		$this->Auditoria = new Auditoria();
    }

    public function jsonSerialize() {
        return [
            'Id' => $this->Id,
            'NumeroDocumento' => $this->NumeroDocumento,
            'TipoDocumentoIdentidad' => $this->TipoDocumentoIdentidad,
            'Empresa' => $this->Empresa,
            'Perfil' => $this->Perfil,
            'Area' => $this->Area,
            'Nombres' => $this->Nombres,
            'Apellidos' => $this->Apellidos,
            'NombreCompleto' => $this->Nombres." ".$this->Apellidos,
            'Usuario' => $this->Usuario,
            'Correo' => $this->Correo,
            'Telefono' => $this->Telefono,
            'Estado' => $this->Estado,
            'Auditoria' => $this->Auditoria
        ];
    }

	public function getId(){
		return $this->Id;
	}
	public function getNumeroDocumento(){
		return $this->NumeroDocumento;
	}	
	public function getTipoDocumentoIdentidad(){
		return $this->TipoDocumentoIdentidad;
	}
	public function getEmpresa(){
		return $this->Empresa;
	}
	public function getPerfil(){
		return $this->Perfil;
	}
	public function getArea(){
		return $this->Area;
	}
	public function getNombres(){
		return $this->Nombres;
	}
	public function getApellidos(){
		return $this->Apellidos;
	}
	public function getUsuario(){
		return $this->Usuario;
	}
	public function getPassword(){
		return $this->Password;
	}		
	public function getCorreo(){
		return $this->Correo;
	}
	public function getTelefono(){
		return $this->Telefono;
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
	public function setNumeroDocumento($NumeroDocumento){
		$this->NumeroDocumento = $NumeroDocumento;
	}	
	public function setTipoDocumentoIdentidad($TipoDocumentoIdentidad){
		$this->TipoDocumentoIdentidad = $TipoDocumentoIdentidad;
	}
	public function setEmpresa($Empresa){
		$this->Empresa = $Empresa;
	}		
	public function setPerfil($Perfil){
		$this->Perfil = $Perfil;
	}
	public function setArea($Area){
		$this->Area = $Area;
	}
	public function setNombres($Nombres){
		$this->Nombres = $Nombres;
	}
	public function setApellidos($Apellidos){
		$this->Apellidos = $Apellidos;
	}
	public function setUsuario($Usuario){
		$this->Usuario = $Usuario;
	}
	public function setPassword($Password){
		$this->Password = $Password;
	}
	public function setCorreo($Correo){
		$this->Correo = $Correo;
	}
	public function setTelefono($Telefono){
		$this->Telefono = $Telefono;
	}
	public function setEstado($Estado){
		$this->Estado = $Estado;
	}
	public function setAuditoria($Auditoria){
		$this->Auditoria = $Auditoria;
	}	
}
