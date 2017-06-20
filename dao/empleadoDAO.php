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

			if(is_null($empleado->getId())){
				$sQuery = "
				        select 
				        	rid_empleado, rv_msj_error 
				        from 
				        	empleado_nuevo(?,?,?,?,?,?,?,?,?,?,?);
				    ";
				$stm = $this->pdo->prepare($sQuery);

				$stm->execute(
					array(
						$empleado->getNumeroDocumento(),
						$empleado->getTipoDocumentoIdentidad(),
						$empleado->getEmpresa()->getRuc(),
						$empleado->getPerfil()->getId(),
						$empleado->getArea()->getId(),
						$empleado->getNombres(),
						$empleado->getApellidos(),
						$empleado->getUsuario(),
						$empleado->getCorreo(),
						$empleado->getTelefono(),
						$empleado->getAuditoria()->getId()
						)
					);

				$row = $stm->fetch();			

				if($row){
					$error = $row['rv_msj_error']==null?Constants::FLAG_CORRECTO:$row['rv_msj_error'];
					$empleado->setId($row['rid_empleado']);
				}

			}else{
				$sQuery = "
				         select 
				        	rid_empleado, rv_msj_error 
				        from 
				        	empleado_nuevo(?,?,?,?,?,?,?,?,?,?,?);
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
					$error = $row['rv_msj_error']==null?Constants::FLAG_CORRECTO:$row['rv_msj_error'];
				}				
			}


		} catch (Exception $e) {
			$error_desc = $e->getMessage();				
		}

		return  array(
					"modulo"=>$modulo,
					"empleado"=>$empleado,
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
		$Estado, 
		$iDisplayStart, $iDisplayLength, $sSearch, $sSortDir_0, $iSortCol_0){
		try
		{
			$result = array();

			$sSelect = "
			select 
				e.id_empleado,e.nro_doc_emp,e.id_tipo_documento_identidad,e.ruc_empresa,e.id_perfil,e.id_area,e.nombres_emp,e.apellidos_emp,e.usuario_emp,e.correo_emp,e.telefono_emp,e.id_auditoria 
			from 
				empleado e 
			inner join 
				empresa emp on e.ruc_empresa=emp.ruc_empresa";

			/*"select 
				a.id_area, a.nombre_area, a.desc_area, a.estado_area, 
				e.ruc_empresa, e.razon_social_empresa, e.nombre_comercial_empresa,
				a.id_auditoria 
			from 
				area a 
			inner join 
				empresa e on e.ruc_empresa =a.ruc_empresa ";*/

			$sLimit = "";

			if ( $iDisplayStart !='' && $iDisplayLength != '-1' )
			{
				$sLimit = "LIMIT ".intval($iDisplayLength)." OFFSET ".
				intval($iDisplayStart);
			}

			$sWhere = "where (e.estado_emp  = '".$Estado."' or '".$Estado."' = 'T') and (emp.estado_empresa  = '".$Estado."' or '".$Estado."' = 'T')";

			if ( $sSearch != "" )
		    {

		        $sWhere .= "and ";
	            $sWhere .= "(";
	            $sWhere .= "e.nombres_emp ILIKE '%$sSearch%' OR ";
	            $sWhere .= "e.apellidos_emp ILIKE '%$sSearch%' OR ";
	            $sWhere .= "e.correo_emp ILIKE '%$sSearch%' OR ";
		        $sWhere = substr_replace( $sWhere, "", -3 );
		       	$sWhere .= ")";

		    }

		    $sOrder = "";

			if ($iSortCol_0 != ""){		

				$sOrder = "ORDER BY  ";		    

				switch ($iSortCol_0) {
				    case 2:
						$sOrder .= "e.nombres_emp".($sSortDir_0==='asc' ? 'asc' : 'desc').", ";
				        break;
				    case 3:
						$sOrder .= "e.apellidos_emp".($sSortDir_0==='asc' ? 'asc' : 'desc').", ";
				        break;
				    case 3:
						$sOrder .= "e.correo_emp".($sSortDir_0==='asc' ? 'asc' : 'desc').", ";						
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
	
		

			$stm = $this->pdo->prepare($sQuery);
			$stm->execute();
			$a_respuesta = array();

			while($reg = $stm->fetch())
			{



				$Usuario = new Empleado();
				$Usuario->setId($reg[0]);
				$Usuario->setNumeroDocumento($reg[1]);
				$Usuario->setTipoDocumentoIdentidad($reg[2]);

				$empresa =new Empresa();
				$empresa->setRuc($reg[3]);

				//$Usuario->setEmpresa($reg[3]);
				//$Usuario->setPerfil($reg[4]);

				$perfil=new Perfil();
				$perfil->setId($reg[4]);

				//$Usuario->setArea($reg[5]);
				$area=new Area();
				$area->setId($reg[5]);

				$Usuario->setNombres($reg[6]);
				$Usuario->setApellidos($reg[7]);
				$Usuario->setUsuario($reg[8]);
				$Usuario->setCorreo($reg[9]);
				$Usuario->setTelefono($reg[10]);

				$auditoria=new Auditoria();
				$auditoria->setId($reg[11]);

				$Usuario->setAuditoria($auditoria);
				$Usuario->setEmpresa($empresa);
				$Usuario->setPerfil($perfil);
				$Usuario->setArea($area);
				


				/*$empresa = new Empresa();
				$empresa->setRuc($reg[4]);
				$empresa->setRazonSocial($reg[5]);
				$empresa->setNombreComercial($reg[6]);

				$auditoria = new Auditoria();
				$auditoria->setId($reg[7]);

				$area->setEmpresa($empresa);
				$area->setAuditoria($auditoria);*/

				array_push($a_respuesta, $Usuario);
			}

			$sQuery = "
			select 
				e.id_empleado,e.nro_doc_emp,e.id_tipo_documento_identidad,e.ruc_empresa,e.id_perfil,e.id_area,e.nombres_emp,e.apellidos_emp,e.usuario_emp,e.correo_emp,e.telefono_emp 
			from 
				empleado e 
			inner join 
				empresa emp on e.ruc_empresa=emp.ruc_empresa";
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
		        //"sEcho" => intval($sEcho),
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