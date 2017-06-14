<?php

class Auditoria implements JsonSerializable
{
	
	private $Id;
	private $Usucrea;
	private $Ipcrea;
	private $Pccrea;
	private $Fechacrea;
	private $Usuedita;
	private $Ipedita;
	private $Pcedita;
	private $Fechaedita;
/*
 	public function __CONSTRUCT() {
 		$this->Id = null;
 		$this->Usucrea = null;
 		$this->Ipcrea = null;
 		$this->Pccrea = null;
 		$this->Fechacrea = null;
 		$this->Usuedita = null;
 		$this->Ipedita = null;
 		$this->Pcedita = null;
 		$this->Fechaedita = null;
    }
*/
    public function jsonSerialize() {
        return [
            'Id' => $this->Id,
            'Usucrea' => $this->Usucrea,
            'Ipcrea' => $this->Ipcrea,
            'Pccrea' => $this->Pccrea,
            'Fechacrea' => $this->Fechacrea,
            'Usuedita' => $this->Usuedita,
            'Ipedita' => $this->Ipedita,
            'Pcedita' => $this->Pcedita,
            'Fechaedita' => $this->Fechaedita
        ];
    }

	public function getId(){
		return $this->Id;
	}
	public function getUsucrea(){
		return $this->Usucrea;
	}
	public function getIpcrea(){
		return $this->Ipcrea;
	}		
	public function getPccrea(){
		return $this->Pccrea;
	}
	public function getFechacrea(){
		return $this->Fechacrea;
	}	
	public function getUsuedita(){
		return $this->Usuedita;
	}
	public function getIpedita(){
		return $this->Ipedita;
	}		
	public function getPcedita(){
		return $this->Pcedita;
	}
	public function getFechaedita(){
		return $this->Fechaedita;
	}


	public function setId($Id){
		$this->Id = $Id;
	}
	public function setUsucrea($Usucrea){
		$this->Usucrea = $Usucrea;
	}
	public function setIpcrea($Ipcrea){
		$this->Ipcrea = $Ipcrea;
	}
	public function setPccrea($Pccrea){
		$this->Pccrea = $Pccrea;
	}			
	public function setFechacrea($Fechacrea){
		$this->Fechacrea = $Fechacrea;
	}
	public function setUsuedita($Usuedita){
		$this->Usuedita = $Usuedita;
	}
	public function setIpedita($Ipedita){
		$this->Ipedita = $Ipedita;
	}
	public function setPcedita($Pcedita){
		$this->Pcedita = $Pcedita;
	}			
	public function setFechaedita($Fechaedita){
		$this->Fechaedita = $Fechaedita;
	}		
}
