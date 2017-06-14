<?php

require_once 'model/perfil_submenu.php';

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
}