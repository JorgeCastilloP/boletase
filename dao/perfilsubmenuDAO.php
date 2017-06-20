<?php

require_once 'model/submenu.php';

class PerfilSubmenuDAO
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
	public function guardar($perfil_submenu){
	
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
					$perfil_submenu->getPerfil()->getId(),
					$perfil_submenu->getSubmenu()->getId(), 
					$perfil_submenu->getEstado(), 
					$perfil_submenu->getAuditoria()->getId()
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
					"perfil_submenu" => $perfil_submenu,
	    			"error_desc" => $error_desc
	    			);	

	}
	public function listar($IdPerfil,$IdMenu){
		try
		{
			$sQuery = "
				select 
					s.id_submenu, s.desc_submenu, ps.estado_perfil_submenu 
				from 
					submenu s 
				left join 
					perfil_submenu ps on ps.id_submenu = s.id_submenu";


			if($IdPerfil !== '')
				$sQuery .= " and ps.id_perfil = ".$IdPerfil;
			else
				$sQuery .= " and ps.id_perfil = 0";	

			$sQuery .="
				 where 
					id_menu = ".$IdMenu;


			$stm = $this->pdo->prepare($sQuery);
			$stm->execute();
			$a_respuesta = array();

			while($reg = $stm->fetch())
			{
				$submenu = new Submenu();
				$submenu->setId($reg[0]);
			    $submenu->setDescripcion($reg[1]);
			    $submenu->setEstado($reg[2]);

			    array_push($a_respuesta, $submenu);
			}	
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}	
		return $a_respuesta;		
	}	
}