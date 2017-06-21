<?php

require_once 'model/empleado.php';
require_once 'model/tipodocumentoidentidad.php';
require_once 'model/empresa.php';
require_once 'model/perfil.php';
require_once 'model/area.php';
require_once 'model/auditoria.php';

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

	public function listar(){

		try
		{

			$Estado='A';
			$sQuery= "
			select 
				id_tipo_documento_identidad,
				nombre_tipo_documento_identidad,
				estado_tipo_documento_identidad 
			from 
				tipo_documento_identidad 
			where estado_tipo_documento_identidad='" .$Estado."'";


			$stm=$this->pdo->preapre($sQuery);
			$stm->execute();

			$a_respuesta=array();

			while($reg=$stm->fetch())
			{
				$tipodocumentoidentidad =new TipoDocumentoIdentidad();
				$tipodocumentoidentidad->setId($reg[0]);
				$tipodocumentoidentidad->setNombre($reg[1]);
				$tipodocumentoidentidad->setEstado($reg[2]);

				array_push($a_respuesta, $tipodocumentoidentidad);
 				
			}
		}
		catch(Exception $e )
		{
			die($e->getMessage());
		}
		return $a_respuesta;
	}
	public function guardar($empleado){
		
		$modulo="empleadoDAO";
		$error =  Constants::FLAG_INCORRECTO;;				

		try {

			$sQuery = "";
			$this->pdo->beginTransaction();

			if(is_null($empleado->getId())){
				$sQuery = "
				        select 
				        	rv_msj_error 
				        from 
				        	sp_empleado_nuevo(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);
				    ";
				$stm = $this->pdo->prepare($sQuery);

				$stm->execute(
					array(
						$empleado->getNumeroDocumento(),
						$empleado->getTipoDocumentoIdentidad()->getId(),
						$empleado->getEmpresa()->getRuc(),
						$empleado->getPerfil()->getId(),
						$empleado->getArea()->getId(),
						$empleado->getNombres(),
						$empleado->getApellidos(),
						$empleado->getUsuario(),
						$empleado->getPassword(),
						$empleado->getCorreo(),
						$empleado->getTelefono(),
						$empleado->getAuditoria()->getUsucrea(),
						$empleado->getAuditoria()->getIpcrea(),
						$empleado->getAuditoria()->getPccrea(),
						$empleado->getAuditoria()->getFechacrea()
						)
					);

				$row = $stm->fetch();			

				if($row){

					if($row['rv_msj_error'] == null){
						$error = Constants::FLAG_CORRECTO;
						$this->pdo->commit();
					}else{
						$error = $row['rv_msj_error'];
						$this->pdo->rollBack();
					}
				}

			}else{
				$sQuery = "
				         select 
				        	rv_msj_error 
				        from 
				        	sp_empleado_editar(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);
				    ";
				 
				$stm = $this->pdo->prepare($sQuery);

				$stm->execute(
					array(
						$empleado->getId(),
						$empleado->getNumeroDocumento(),
						$empleado->getTipoDocumentoIdentidad()->getId(),
						$empleado->getEmpresa()->getRuc(),
						$empleado->getPerfil()->getId(),
						$empleado->getArea()->getId(),
						$empleado->getNombres(),
						$empleado->getApellidos(),
						$empleado->getUsuario(),
						$empleado->getPassword(),
						$empleado->getCorreo(),
						$empleado->getTelefono(),
						$empleado->getEstado(),
						$empleado->getAuditoria()->getUsucrea(),
						$empleado->getAuditoria()->getIpcrea(),
						$empleado->getAuditoria()->getPccrea(),
						$empleado->getAuditoria()->getFechacrea()
						)
					);								

				$row = $stm->fetch();			

				if($row){
					if($row['rv_msj_error'] == null){
						$error = Constants::FLAG_CORRECTO;
						$this->pdo->commit();
					}else{
						$error = $row['rv_msj_error'];
						$this->pdo->rollBack();
					}			
				}			
			}


		} catch (Exception $e) {
			$this->pdo->rollBack();
			$error = $e->getMessage();				
		}

		return  array(
					"modulo"=>$modulo,
	    			"error" => $error
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

	//
	public function listarTabla(
		$Ruc, 
		$Estado, 
		$iDisplayStart, $iDisplayLength, $sSearch, $sSortDir_0, $iSortCol_0){
		try
		{
			$result = array();

			$sSelect = "
			select 
				e.id_empleado, e.nro_doc_emp, e.nombres_emp, e.apellidos_emp, e.usuario_emp, e.correo_emp, e.telefono_emp, 
				emp.ruc_empresa, emp.razon_social_empresa, emp.nombre_comercial_empresa, 
				a.id_area, a.nombre_area, 
				p.id_perfil, p.nombre_perfil, 
				tdi.id_tipo_documento_identidad, tdi.nombre_tipo_documento_identidad, 
				au.id_auditoria 				
			from 
				empleado e 
			inner join 
				empresa emp on e.ruc_empresa=emp.ruc_empresa and (e.ruc_empresa = '".$Ruc."' or '".$Ruc."' = 'T') and (emp.estado_empresa  = '".$Estado."' or '".$Estado."' = 'T')
			inner join 
				tipo_documento_identidad tdi on e.id_tipo_documento_identidad = tdi.id_tipo_documento_identidad 
			inner join 
				area a on e.id_area = a.id_area and (a.estado_area  = '".$Estado."' or '".$Estado."' = 'T')
			inner join 
				perfil p on e.id_perfil = p.id_perfil and (p.estado_perfil  = '".$Estado."' or '".$Estado."' = 'T') 
			inner join 
				auditoria au on e.id_auditoria = au.id_auditoria 
				";

			$sLimit = "";

			if ( $iDisplayStart !='' && $iDisplayLength != '-1' )
			{
				$sLimit = "LIMIT ".intval($iDisplayLength)." OFFSET ".
				intval($iDisplayStart);
			}

			$sWhere = "where (e.estado_emp  = '".$Estado."' or '".$Estado."' = 'T')";

			if ( $sSearch != "" )
		    {

		        $sWhere .= "and ";
	            $sWhere .= "(";
	            $sWhere .= "e.apellidos_emp ILIKE '%$sSearch%' OR ";
	            $sWhere .= "e.nombres_emp ILIKE '%$sSearch%' OR ";
				$sWhere .= "e.usuario_emp ILIKE '%$sSearch%' OR ";				            
	            $sWhere .= "e.correo_emp ILIKE '%$sSearch%' OR ";
	            $sWhere .= "e.telefono_emp ILIKE '%$sSearch%' OR ";
	            $sWhere .= "p.nombre_perfil ILIKE '%$sSearch%' OR ";
	            $sWhere .= "a.nombre_area ILIKE '%$sSearch%' OR ";
		        $sWhere = substr_replace( $sWhere, "", -3 );
		       	$sWhere .= ")";

		    }

		    $sOrder = "";

			if ($iSortCol_0 != ""){		

				$sOrder = "ORDER BY  ";		    

				switch ($iSortCol_0) {
				    case 1:
						$sOrder .= "e.apellidos_emp".($sSortDir_0==='asc' ? 'asc' : 'desc').", ";
				        break;					
				    case 2:
						$sOrder .= "e.nombres_emp".($sSortDir_0==='asc' ? 'asc' : 'desc').", ";
				        break;
				    case 3:
						$sOrder .= "e.usuario_emp".($sSortDir_0==='asc' ? 'asc' : 'desc').", ";	
						break;
				    case 4:
						$sOrder .= "e.correo_emp".($sSortDir_0==='asc' ? 'asc' : 'desc').", ";	
						break;
				    case 5:
						$sOrder .= "e.telefono".($sSortDir_0==='asc' ? 'asc' : 'desc').", ";			
						break;
				    case 6:
						$sOrder .= "p.nombre_perfil".($sSortDir_0==='asc' ? 'asc' : 'desc').", ";	
						break;
				    case 7:
						$sOrder .= "a.nombre_area".($sSortDir_0==='asc' ? 'asc' : 'desc').", ";			
				        break;
				}

				$sOrder = substr_replace( $sOrder, "", -2 );
				
				if ( $sOrder == "ORDER BY" )
    			{
        			$sOrder = "";
				}
			}

		    $sQuery = "
		        $sSelect
		        $sWhere
		        $sOrder
		        $sLimit
		    ";		
	
			//echo $sQuery;

			$stm = $this->pdo->prepare($sQuery);
			$stm->execute();
			$a_respuesta = array();

			while($reg = $stm->fetch())
			{

				$empleado = new Empleado();
				$empleado->setId($reg[0]);
				$empleado->setNumeroDocumento($reg[1]);
				$empleado->setNombres($reg[2]);
				$empleado->setApellidos($reg[3]);
				$empleado->setUsuario($reg[4]);
				$empleado->setCorreo($reg[5]);
				$empleado->setTelefono($reg[6]);

				$empresa =new Empresa();
				$empresa->setRuc($reg[7]);
				$empresa->setRazonSocial($reg[8]);
				$empresa->setNombreComercial($reg[9]);

				$area=new Area();
				$area->setId($reg[10]);
				$area->setNombre($reg[11]);

				$perfil=new Perfil();
				$perfil->setId($reg[12]);
				$perfil->setNombre($reg[13]);

				$tdi=new TipoDocumentoIdentidad();
				$tdi->setId($reg[14]);
				$tdi->setNombre($reg[15]);

				$auditoria = new Auditoria();
				$auditoria->setId($reg[16]);

				$empleado->setEmpresa($empresa);
				$empleado->setPerfil($perfil);
				$empleado->setArea($area);
				$empleado->setTipoDocumentoIdentidad($tdi);
				$empleado->setAuditoria($auditoria);
				
				array_push($a_respuesta, $empleado);
			}

			$sQuery = "
			select 
				e.id_empleado, e.nro_doc_emp, e.nombres_emp, e.apellidos_emp, e.usuario_emp, e.correo_emp, e.telefono_emp, 
				emp.ruc_empresa, emp.razon_social_empresa, emp.nombre_comercial_empresa, 
				a.id_area, a.nombre_area, 
				p.id_perfil, p.nombre_perfil, 
				tdi.id_tipo_documento_identidad, tdi.nombre_tipo_documento_identidad, 
				au.id_auditoria 				
			from 
				empleado e 
			inner join 
				empresa emp on e.ruc_empresa=emp.ruc_empresa and (e.ruc_empresa = '".$Ruc."' or '".$Ruc."' = 'T') and (emp.estado_empresa  = '".$Estado."' or '".$Estado."' = 'T')
			inner join 
				tipo_documento_identidad tdi on e.id_tipo_documento_identidad = tdi.id_tipo_documento_identidad 
			inner join 
				area a on e.id_area = a.id_area and (a.estado_area  = '".$Estado."' or '".$Estado."' = 'T')
			inner join 
				perfil p on e.id_perfil = p.id_perfil and (p.estado_perfil  = '".$Estado."' or '".$Estado."' = 'T') 
			inner join 
				auditoria au on e.id_auditoria = au.id_auditoria 				
				";
			/*select 
				a.id_area, a.nombre_area, a.desc_area, a.estado_area, 
				e.ruc_empresa, e.razon_social_empresa, e.nombre_comercial_empresa,
				a.id_auditoria 
			from 
				area a 
			inner join 
				empresa e on e.ruc_empresa =a.ruc_empresa ";*/

			$sQuery .= " 
			        	$sWhere
			        	";

			$stm = $this->pdo->prepare($sQuery);	
			$stm->execute();
			$tot = $stm->fetchAll(PDO::FETCH_OBJ);					

			$result = array(
		        "iTotalRecords" => count($tot),
		        "iTotalDisplayRecords" => count($tot),
		        "aaData" => $a_respuesta
    		);

    		return $result;	
		}
		catch(Exception $e)
		{
			die($e->getMessage());	
		}			
	}	


	//


}