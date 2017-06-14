<?php

require_once 'model/empleado.php';
require_once 'model/tipodocumentoidentidad.php';
require_once 'model/empresa.php';
require_once 'model/perfil.php';
require_once 'model/area.php';

class EmpleadoDAO
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
	public function guardar($empleado){
	
		$error_desc = '';				

		try {

			$sQuery = "";

			if(is_null($empleado->getId())){
				$sQuery = "
				        select 
				        	rid_empleado, rv_msj_error 
				        from 
				        	empleado_nuevo(?,?,?,?,?,?,?,?);
				    ";
				$stm = $this->pdo->prepare($sQuery);

				$stm->execute(
					array(
						$empleado->getNumeroDocumento(),
						$empleado->getTipoDocumentoIdentidad(),
						$empleado->getEmpresa()->getRuc(),
						$empleado->getPerfil()->getId(),
						$empleado->getArea()->getId(),
						$empleado->getCorreo(),
						$empleado->getTelefono(),
						$empleado->getAuditoria()->getId()
						)
					);

				$row = $stm->fetch();			

				if($row){
					$error_desc = $row['rv_msj_error'];
					$empleado->setId($row['rid_empleado']);
				}

			}else{
				$sQuery = "
				        select 
				        	rv_msj_error 
				        from 
				        	empleado_editar(?,?,?,?,?,?,?,?,?,?);
				    ";
				$stm = $this->pdo->prepare($sQuery);

				$stm->execute(
					array(
						$empleado->getId(),
						$empleado->getNumeroDocumento(),
						$empleado->getTipoDocumentoIdentidad(),
						$empleado->getEmpresa()->getRuc(),
						$empleado->getPerfil()->getId(),
						$empleado->getArea()->getId(),
						$empleado->getCorreo(),
						$empleado->getTelefono(),
						$empleado->getEstado(),
						$empleado->getAuditoria()->getId()
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
					"empleado" => $empleado,
	    			"error_desc" => $error_desc
	    			);	

	}
	public function validar($usuario, $password, $estado){
		$modulo = "empleadoDAO-validar";
		$error = Constants::FLAG_INCORRECTO;
		$empleado = new Empleado();

		try
		{		
			$sQuery = "
			select 
				e.id_empleado, e.nro_doc_emp, e.nombres_emp, e.apellidos_emp, e.usuario_emp, e.password_emp, e.correo_emp, e.telefono_emp, e.estado_emp, 
				tdi.id_tipo_documento_identidad, tdi.nombre_tipo_documento_identidad, 
				em.ruc_empresa, em.razon_social_empresa, em.nombre_comercial_empresa, em.direccion_empresa, em.telefono_empresa, em.correo_empresa, em.estado_empresa, 
				p.id_perfil, p.nombre_perfil, p.desc_perfil, p. estado_perfil, 
				a.id_area, a.nombre_area, a.desc_area, a.estado_area 
			from 
				empleado e 
			inner join 
				tipo_documento_identidad tdi on tdi.id_tipo_documento_identidad = e.id_tipo_documento_identidad and (estado_tipo_documento_identidad  = '".$estado."' or '".$estado."' = 'T') 
			inner join 
				empresa em on em.ruc_empresa = e.ruc_empresa and (estado_empresa  = '".$estado."' or '".$estado."' = 'T') 
			inner join 
				perfil p on p.id_perfil = e.id_perfil and (estado_perfil  = '".$estado."' or '".$estado."' = 'T') 
			inner join 
				area a on a.id_area = e.id_area and (estado_area = '".$estado."' or '".$estado."' = 'T') 
			where 
				e.usuario_emp = ?";

			$stm = $this->pdo->prepare($sQuery);
			$stm->execute(array($usuario));
			$row = $stm->fetch(); 

			if($row){
				$error = Constants::FLAG_CORRECTO;
				/*
	            $check_password = hash('sha256', $password); 

	            for($round = 0; $round < 65536; $round++){
	                $check_password = hash('sha256', $check_password);
	            } 
	            if($check_password === trim($row['password_emp'])){
	                $error = Constants::FLAG_CORRECTO;
	            } 
	            */
	            if($error == Constants::FLAG_CORRECTO){
	            	$empleado->setId($row['id_empleado']);
	            	$empleado->setNumeroDocumento($row['nro_doc_emp']);
	            	$empleado->setNombres($row['nombres_emp']);
	            	$empleado->setApellidos($row['apellidos_emp']);
	            	$empleado->setUsuario($row['usuario_emp']);
	            	$empleado->setCorreo($row['correo_emp']);
	            	$empleado->setTelefono($row['telefono_emp']);
	            	$empleado->setEstado($row['estado_emp']);

	            	$tdi = new TipoDocumentoIdentidad();
	            	$tdi->setId($row['id_tipo_documento_identidad']);
	            	$tdi->setNombre($row['nombre_tipo_documento_identidad']);
	            	$empleado->setTipoDocumentoIdentidad($tdi);

	            	$empresa = new Empresa();
	            	$empresa->setRuc($row['ruc_empresa']);
	            	$empresa->setRazonSocial($row['razon_social_empresa']);
	            	$empresa->setNombreComercial($row['nombre_comercial_empresa']);
	            	$empresa->setDireccion($row['direccion_empresa']);
	            	$empresa->setTelefono($row['telefono_empresa']);
	            	$empresa->setCorreo($row['correo_empresa']);
	            	$empresa->setEstado($row['estado_empresa']);
	            	$empleado->setEmpresa($empresa);

	            	$perfil = new Perfil();
	            	$perfil->setId($row['id_perfil']);
	            	$perfil->setEmpresa($empresa);
	            	$perfil->setNombre($row['nombre_perfil']);
	            	$perfil->setDescripcion($row['desc_perfil']);
	            	$perfil->setEstado($row['estado_perfil']);
	            	$empleado->setPerfil($perfil);

	            	$area = new Area();
	            	$area->setId($row['id_area']);
	            	$area->setEmpresa($empresa);
	            	$area->setNombre($row['nombre_area']);
	            	$area->setDescripcion($row['desc_area']);
	            	$area->setEstado($row['estado_area']);
	            	$empleado->setArea($area);
	            }
			}


		} catch(Exception $e){
			$error = $e->getMessage();	
		}

		return array(
					"modulo" => $modulo,
					"empleado" => $empleado,
	    			"error" => $error
	    			);			
	}

}