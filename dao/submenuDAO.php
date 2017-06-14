<?php

require_once 'model/submenu.php';
require_once 'model/menu.php';

class SubmenuDAO
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
					s.id_submenu, s.desc_submenu, s.estado_submenu, 
					m.id_menu, m.desc_menu, m.estado_menu 
				from 
					submenu s 
				inner join 
					menu m on m.id_menu = s.id_menu and (m.estado_menu  = '".$Estado."' or '".$Estado."' = 'T') 
					";

			if($Estado !== 'T')
				$sQuery .= "
					where 
						estado_submenu  = '".$Estado."'";

			$stm = $this->pdo->prepare($sQuery);
			$stm->execute();
			$a_respuesta = array();

			while($reg = $stm->fetch())
			{
				$submenu = new Submenu();
				$submenu->setId($reg[0]);
			    $submenu->setDescripcion($reg[1]);
			    $submenu->setEstado($reg[2]);

			    $menu = new Menu();
			    $menu->setId($reg[3]);
			    $menu->setDescripcion($reg[4]);
			    $menu->setEstado($reg[5]);

			    $submenu->setMenu($menu);

			    array_push($a_respuesta, $submenu);
			}	
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}	
		return $a_respuesta;		
	}	
	public function guardar($submenu){
	
		$error_desc = '';				

		try {

			$sQuery = "";

			if(is_null($submenu->getId())){
				$sQuery = "
				        select 
				        	rid_submenu, rv_msj_error 
				        from 
				        	submenu_nuevo(?,?);
				    ";
				$stm = $this->pdo->prepare($sQuery);

				$stm->execute(
					array(
						$submenu->getMenu()->getId(),
						$submenu->getDescripcion()
						)
					);

				$row = $stm->fetch();			

				if($row){
					$error_desc = $row['rv_msj_error'];
					$submenu->setId($row['rid_submenu']);
				}

			}else{
				$sQuery = "
				        select 
				        	rv_msj_error 
				        from 
				        	submenu_editar(?,?,?,?);
				    ";
				$stm = $this->pdo->prepare($sQuery);

				$stm->execute(
					array(
						$submenu->getId(), 
						$submenu->getMenu()->getId(),
						$submenu->getDescripcion(), 
						$submenu->getEstado()
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
					"submenu" => $submenu,
	    			"error_desc" => $error_desc
	    			);	

	}	
}