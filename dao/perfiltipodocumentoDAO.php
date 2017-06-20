<?php

require_once 'model/perfil_tipodocumento.php';
require_once 'model/tipodocumento.php';
require_once 'model/empresa.php';

class PerfilTipoDocumentoDAO
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
	public function guardar($perfil_tipodocumento){
	
		$error_desc = '';				

		try {

			$sQuery = "
			        select 
			        	rv_msj_error 
			        from 
			        	perfil_submenu_guardar(?,?,?,?);
			    ";
			$stm = $this->pdo->prepare($sQuery);

			$stm->execute(
				array(
					$perfil_tipodocumento->getPerfil()->getId(),
					$perfil_tipodocumento->getTipoDocumento()->getId(), 
					$perfil_tipodocumento->getEstado(), 
					$perfil_tipodocumento->getAuditoria()->getId()
					)
				);

			$row = $stm->fetch();			

			if($row){
				$error_desc = $row['rv_msj_error'];
			}

		} catch (Exception $e) {
			$error_desc = $e->getMessage();				
		}

		return array(
					"perfil_tipodocumento" => $perfil_tipodocumento,
	    			"error_desc" => $error_desc
	    			);	

	}
	public function listar($IdPerfil){
	
		try
		{
			$sQuery = "
				select 
					td.id_tipo_documento, 
					e.ruc_empresa, e.razon_social_empresa, e.nombre_comercial_empresa, 
					td.desc_tipo_documento, 
					pt.estado_perfil_tipo_documento 
				from 
					tipo_documento td 
				inner join 
					empresa e on e.ruc_empresa = td.ruc_empresa					
				left join
					perfil_tipo_documento pt on pt.id_tipo_documento = td.id_tipo_documento"; 

			if($IdPerfil !== '')
				$sQuery .= " and pt.id_perfil = ".$IdPerfil;
			else
				$sQuery .= " and pt.id_perfil = 0";					

			$stm = $this->pdo->prepare($sQuery);
			$stm->execute();
			$a_respuesta = array();

			while($reg = $stm->fetch())
			{
				$td = new TipoDocumento();
				$td->setId($reg[0]);

				$empresa = new Empresa();
				$empresa->setRuc(1);
				$empresa->setRazonSocial(2);
				$empresa->setNombreComercial(3);

				$td->setEmpresa($empresa);
				
			    $td->setDescripcion($reg[4]);
			    $td->setEstado($reg[5]);
			    array_push($a_respuesta, $td);
			}	
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}	
		
		return $a_respuesta;	
	}		
}