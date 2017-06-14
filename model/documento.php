<?php

class Documento implements JsonSerializable
{
	
	private $Id;
	private $Empresa;
	private $Empleado;
	private $TipoDocumento;
	private $Area;
	private $RutaAlmacenamiento;
	private $FechaDocumento;
	private $FechaIncorporacion;
	private $FechaVisualizacion;
	private $FechaBaja;
	private $IdErp;
	private $IdWeb;
	private $Estado;
	private $Auditoria;


    public function jsonSerialize() {
        return [
            'Id' => $this->Id,
            'Empresa' => $this->Empresa,
            'Empleado' => $this->Empleado,
            'TipoDocumento' => $this->TipoDocumento,
            'Area' => $this->Area,
            'RutaAlmacenamiento' => $this->RutaAlmacenamiento,
            'FechaDocumento' => $this->FechaDocumento,
            'FechaIncorporacion' => $this->FechaIncorporacion,
            'FechaVisualizacion' => $this->FechaVisualizacion,
            'FechaBaja' => $this->FechaBaja,
            'IdErp' => $this->IdErp,
            'IdWeb' => $this->IdWeb,
            'Estado' => $this->Estado,
            'Auditoria' => $this->Auditoria
        ];
    }

	public function getId(){
		return $this->Id;
	}
	public function getEmpresa(){
		return $this->Empresa;
	}	
	public function getEmpleado(){
		return $this->Empleado;
	}
	public function getTipoDocumento(){
		return $this->TipoDocumento;
	}
	public function getArea(){
		return $this->Area;
	}
	public function getRutaAlmacenamiento(){
		return  $this->RutaAlmacenamiento;
	}
	public function getFechaDocumento(){
		return  $this->FechaDocumento;
	}	
	public function getFechaIncorporacion(){
		return $this->FechaIncorporacion;
	}
	public function getFechaVisualizacion(){
		return $this->FechaVisualizacion;
	}
	public function getFechaBaja(){
		return $this->FechaBaja;
	}
	public function getIdErp(){
		return $this->IdErp;
	}
	public function getIdWeb(){
		return $this->IdWeb;
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
	public function setEmpresa($Empresa){
		$this->Empresa = $Empresa;
	}	
	public function setEmpleado($Empleado){
		$this->Empleado = $Empleado;
	}
	public function setTipoDocumento($TipoDocumento){
		$this->TipoDocumento = $TipoDocumento;
	}
	public function setArea($Area){
		$this->Area = $Area;
	}
	public function setRutaAlmacenamiento($RutaAlmacenamiento){
		$this->RutaAlmacenamiento = $RutaAlmacenamiento;
	}
	public function setFechaDocumento($FechaDocumento){
		$this->FechaDocumento = $FechaDocumento;
	}	
	public function setFechaIncorporacion($FechaIncorporacion){
		$this->FechaIncorporacion = $FechaIncorporacion;
	}
	public function setFechaVisualizacion($FechaVisualizacion){
		$this->FechaVisualizacion = $FechaVisualizacion;
	}
	public function setFechaBaja($FechaBaja){
		$this->FechaBaja = $FechaBaja;
	}
	public function setIdErp($IdErp){
		$this->IdErp = $IdErp;
	}
	public function setIdWeb($IdWeb){
		$this->IdWeb = $IdWeb;
	}
	public function setEstado($Estado){
		$this->Estado = $Estado;
	}
	public function setAuditoria($Auditoria){
		$this->Auditoria = $Auditoria;
	}
}
