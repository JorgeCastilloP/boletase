<?php
require_once 'model/database.php';
require_once 'model/empresa.php';

class EmpresaDAO
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
	public function obtener($RucEmpresa){
		$sQuery = "
		        select 
		        	ruc_empresa, razon_social_empresa, nombre_comercial_empresa, direccion_empresa, 
		        	telefono_empresa, correo_empresa, estado_empresa 
		        from 
		        	empresa
		        where 
		        	ruc_empresa = ?;
		    ";
		$stm = $this->pdo->prepare($sQuery);

		$stm->execute(array($RucEmpresa));

		$empresa = new Empresa();

		$row = $stm->fetch();			

		if($row){
			$empresa->setRuc($row[0]);
			$empresa->setRazonSocial($row[1]);
			$empresa->setNombreComercial($row[2]);
			$empresa->setDireccion($row[3]);
			$empresa->setTelefono($row[4]);
			$empresa->setCorreo($row[5]);
			$empresa->setEstado($row[6]);
		}

		return array(
					"empresa" => $empresa,
	    			);			
	}
	public function guardar($empresa){
	
		$error_desc = '';				

		try {

			$sQuery = "";

			if(is_null($empresa->getRuc())){
				$sQuery = "
				        select 
				        	rv_msj_error 
				        from 
				        	empresa_nuevo(?,?,?,?,?,?,?);
				    ";
				$stm = $this->pdo->prepare($sQuery);

				$stm->execute(
					array(
						$empresa->getRuc(),
						$empresa->getRazonSocial(), 
						$empresa->getNombreComercial(), 
						$empresa->getDireccion(),
						$empresa->getTelefono(),
						$empresa->getCorreo(),
						$empresa->getPassword(),
						)
					);

				$row = $stm->fetch();			

				if($row){
					$error_desc = $row['rv_msj_error'];
				}

			}else{
				$sQuery = "
				        select 
				        	rv_msj_error 
				        from 
				        	empresa_editar(?,?,?,?,?,?,?,?);
				    ";
				$stm = $this->pdo->prepare($sQuery);

				$stm->execute(
					array(
						$empresa->getRuc(),
						$empresa->getRazonSocial(), 
						$empresa->getNombreComercial(), 
						$empresa->getDireccion(),
						$empresa->getTelefono(),
						$empresa->getCorreo(),
						$empresa->getPassword(),
						$empresa->getEstado()
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
					"empresa" => $empresa,
	    			"error_desc" => $error_desc
	    			);	

	}	
}