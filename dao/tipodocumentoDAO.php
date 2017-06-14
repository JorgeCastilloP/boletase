<?php

require_once 'model/tipodocumento.php';

class TipodocumentoDAO
{
	private $pdo;

	public function __CONSTRUCT()
	{
		try
		{
			$this->pdo = Database::Conectar();     
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	public function listar($Ruc, $Estado){
	
		try
		{
			$sQuery = "
					select 
						td.id_tipo_documento, 
						e.ruc_empresa, e.razon_social_empresa, e.nombre_comercial_empresa, 
						td.desc_tipo_documento, 
						td.estado_tipo_documento,
						a.id_auditoria 
					from 
						tipo_documento td						
					inner join 
						empresa e on e.ruc_empresa = td.ruc_empresa 
					inner join 
						auditoria a on a.id_auditoria = td.id_auditoria ";

			if($Ruc !== 'T' && $Estado !== 'T')
				$sQuery .= "
					where 
						e.ruc_empresa = '".$Ruc."' and (estado_tipo_documento  = '".$Estado."' or '".$Estado."' = 'T')";

			$stm = $this->pdo->prepare($sQuery);
			$stm->execute();
			$a_respuesta = array();

			while($reg = $stm->fetch())
			{
				$tdi = new TipoDocumentoIdentidad();
				$tdi->setId($reg[0]);
			    $tdi->setNombre($reg[1]);
			    $tdi->setEstado($reg[2]);
			    array_push($a_respuesta, $tdi);
			}	
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}	
		
		return $a_respuesta;	
	}	
	public function guardar($tipodocumento){
	
		$error_desc = '';				

		try {

			$sQuery = "";

			if(is_null($tipodocumento->getId())){
				$sQuery = "
				        select 
				        	rid_tipo_documento, rv_msj_error 
				        from 
				        	tipo_documento_nuevo(?,?,?);
				    ";
				$stm = $this->pdo->prepare($sQuery);

				$stm->execute(
					array(
						$tipodocumento->getEmpresa()->getRuc(),
						$tipodocumento->getDescripcion(),
						$tipodocumento->getAuditoria()->getId()
						)
					);

				$row = $stm->fetch();			

				if($row){
					$error_desc = $row['rv_msj_error'];
					$tipodocumento->setId($row['rid_tipo_documento']);
				}

			}else{
				$sQuery = "
				        select 
				        	rv_msj_error 
				        from 
				        	tipo_documento_editar(?,?,?,?,?);
				    ";
				$stm = $this->pdo->prepare($sQuery);

				$stm->execute(
					array(
						$tipodocumento->getId(),
						$tipodocumento->getEmpresa()->getRuc(),
						$tipodocumento->getDescripcion(),
						$tipodocumento->getEstado(),
						$tipodocumento->getAuditoria()->getId()
						)
					);								

				$row = $stm->fetch();			

				if($row){
					$error_desc = $row['rv_msj_error'];
				}				
			}


		} catch (Exception $e) {
			$error_desc = $e->getMessage();				
		}

		return array(
					"tipodocumento" => $tipodocumento,
	    			"error_desc" => $error_desc
	    			);	

	}	
}