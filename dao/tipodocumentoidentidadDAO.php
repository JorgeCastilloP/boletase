<?php

require_once 'model/tipodocumentoidentidad.php';

class TipodocumentoidentidadDAO
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
	public function listar($Estado){
	
		try
		{
			$sQuery = "
					select 
						id_tipo_documento_identidad, 
						nombre_tipo_documento_identidad, 
						estado_tipo_documento_identidad 
					from 
						tipo_documento_identidad ";
						
			if($Estado !== 'T')
				$sQuery .= "where estado_tipo_documento_identidad  = '".$Estado."'";

			$stm = $this->pdo->prepare($sQuery);
			$stm->execute();

			/*return $stm->fetchAll(PDO::FETCH_OBJ);*/
			$a_respuesta = array();

			while($reg = $stm->fetch())
			{
				$tdi = new TipoDocumentoIdentidad();
				$tdi->setId($reg[0]);
			    $tdi->setNombre($reg[1]);
			    /*$tdi->setEstado($reg[2]);*/
			    array_push($a_respuesta, $tdi);
			}	

			
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}	
		
		return $a_respuesta;

	}	

	//Listado por session
	/*	public function listarPorSession($Estado){
	
		try
		{
			$sQuery = "
					select 
						id_tipo_documento_identidad, 
						nombre_tipo_documento_identidad, 
						estado_tipo_documento_identidad 
					from 
						tipo_documento_identidad ";
						
			if($Estado !== 'T')
				$sQuery .= "where estado_tipo_documento_identidad  = '".$Estado."'"

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
	}	*/

	public function guardar($tipodocumentoidentidad){
	
		$error_desc = '';				

		try {

			$sQuery = "";

			if(is_null($tipodocumentoidentidad->getId())){
				$sQuery = "
				        select 
				        	rid_tipo_documento_identidad, rv_msj_error 
				        from 
				        	tipo_documento_identidad_nuevo(?);
				    ";
				$stm = $this->pdo->prepare($sQuery);

				$stm->execute(
					array(
						$tipodocumentoidentidad->getNombre()
						)
					);

				$row = $stm->fetch();			

				if($row){
					$error_desc = $row['rv_msj_error'];
					$tipodocumentoidentidad->setId($row['rid_tipo_documento_identidad']);
				}

			}else{
				$sQuery = "
				        select 
				        	rv_msj_error 
				        from 
				        	tipo_documento_identidad_editar(?,?,?);
				    ";
				$stm = $this->pdo->prepare($sQuery);

				$stm->execute(
					array(
						$tipodocumentoidentidad->getId(),
						$tipodocumentoidentidad->getNombre(),
						$tipodocumentoidentidad->getEstado()
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
					"tipodocumentoidentidad" => $tipodocumentoidentidad,
	    			"error_desc" => $error_desc
	    			);	

	}	
}