<?php

require_once 'model/menu.php';

class MenuDAO
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
					select id_menu, desc_menu, estado_menu 
				from 
					menu ";

			if($Estado !== 'T')
				$sQuery .= "
					where 
						estado_menu  = '".$Estado."'";

			$stm = $this->pdo->prepare($sQuery);
			$stm->execute();
			$a_respuesta = array();

			while($reg = $stm->fetch())
			{
				$menu = new Menu();
				$menu->setId($reg[0]);
			    $menu->setDescripcion($reg[1]);
			    $menu->setEstado($reg[2]);

			    array_push($a_respuesta, $menu);
			}	
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}	
		return $a_respuesta;		
	}	
	public function guardar($menu){
	
		$error_desc = '';				

		try {

			$sQuery = "";

			if(is_null($menu->getId())){
				$sQuery = "
				        select 
				        	rid_menu, rv_msj_error 
				        from 
				        	menu_nuevo(?);
				    ";
				$stm = $this->pdo->prepare($sQuery);

				$stm->execute(
					array(
						$menu->getDescripcion()
						)
					);

				$row = $stm->fetch();			

				if($row){
					$error_desc = $row['rv_msj_error'];
					$menu->setId($row['rid_menu']);
				}

			}else{
				$sQuery = "
				        select 
				        	rv_msj_error 
				        from 
				        	menu_editar(?,?,?);
				    ";
				$stm = $this->pdo->prepare($sQuery);

				$stm->execute(
					array(
						$menu->getId(), 
						$menu->getDescripcion(), 
						$menu->getEstado()
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
					"menu" => $menu,
	    			"error_desc" => $error_desc
	    			);	

	}	
}