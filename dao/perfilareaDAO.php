<?php

require_once 'model/area.php';
require_once 'model/perfil_area.php';
require_once 'model/empresa.php';

class PerfilAreaDAO
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
	public function guardar($perfil_area){
	
		$error_desc = '';				

		try {

			$sQuery = "
			        select 
			        	rv_msj_error 
			        from 
			        	perfil_area_guardar(?,?,?,?);
			    ";
			$stm = $this->pdo->prepare($sQuery);

			$stm->execute(
				array(
					$perfil_area->getPerfil()->getId(),
					$perfil_area->getArea()->getId(), 
					$perfil_area->getEstado(), 
					$perfil_area->getAuditoria()->getId()
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
					"perfil_area" => $perfil_area,
	    			"error_desc" => $error_desc
	    			);	

	}
	public function listar($IdPerfil){

		try{
			$sQuery = "
				select 
					a.id_area, a.ruc_empresa, a.nombre_area, a.desc_area, pa.estado_perfil_area 
				from 
					area a 
				inner join 
					empresa e on e.ruc_empresa = a.ruc_empresa 					
				left join 
					perfil_area pa on a.id_area = pa.id_area";

			if($IdPerfil !== '')
				$sQuery .= " and pa.id_perfil = ".$IdPerfil;
			else
				$sQuery .= " and pa.id_perfil = 0";

			$stm = $this->pdo->prepare($sQuery);
			$stm->execute();
			$a_respuesta = array();


			while($reg = $stm->fetch())
			{
				$area = new Area();
				$area->setId($reg[0]);

				$empresa = new Empresa();
				$empresa->setRuc($reg[1]);

				$area->setEmpresa($empresa);
				$area->setNombre($reg[2]);
			    $area->setDescripcion($reg[3]);
			    $area->setEstado($reg[4]);

			    array_push($a_respuesta, $area);
			}
		}
		catch(Exception $e){
			die($e->getMessage());
		}

		return $a_respuesta;
	}
}