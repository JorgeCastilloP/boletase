<?php

class Empresa implements JsonSerializable
{
	
	private $Ruc;
	private $RazonSocial;
	private $NombreComercial;
	private $Direccion;
	private $Telefono;
	private $Correo;
	private $Password;
	private $Estado;


    public function jsonSerialize() {
        return [
            'Ruc' => $this->Ruc,
            'RazonSocial' => $this->RazonSocial,
            'NombreComercial' => $this->NombreComercial,
            'Direccion' => $this->Direccion,
            'Telefono' => $this->Telefono,
            'Correo' => $this->Correo,
            'Password' => $this->Password,
            'Estado' => $this->Estado
        ];
    }

	public function getRuc(){
		return $this->Ruc;
	}
	public function getRazonSocial(){
		return $this->RazonSocial;
	}	
	public function getNombreComercial(){
		return $this->NombreComercial;
	}
	public function getDireccion(){
		return $this->Direccion;
	}
	public function getTelefono(){
		return $this->Telefono;
	}
	public function getCorreo(){
		return $this->Correo;
	}
	public function getPassword(){
		return $this->Password;
	}
	public function getEstado(){
		return $this->Estado;
	}

	public function setRuc($Ruc){
		$this->Ruc = $Ruc;
	}
	public function setRazonSocial($RazonSocial){
		$this->RazonSocial = $RazonSocial;
	}	
	public function setNombreComercial($NombreComercial){
		$this->NombreComercial = $NombreComercial;
	}
	public function setDireccion($Direccion){
		$this->Direccion = $Direccion;
	}
	public function setTelefono($Telefono){
		$this->Telefono = $Telefono;
	}
	public function setCorreo($Correo){
		$this->Correo = $Correo;
	}
	public function setPassword($Password){
		$this->Password = $Password;
	}	
	public function setEstado($Estado){
		$this->Estado = $Estado;
	}
}
