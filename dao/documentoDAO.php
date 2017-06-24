<?php

require_once 'model/documento.php';
require_once 'model/area.php';
require_once 'model/tipodocumento.php';
require_once 'model/empresa.php';
require_once 'model/empleado.php';

class DocumentoDAO
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
	
	public function listarDocumentosBoletas(
			$RucEmpresa, $RazonSocial, $NombreComercial, 
			$TipoDocumentoIdentidad, 
			$ApellidoEmpleado, $NombreEmpleado, 
			$TipoDocumento, 
			$Area, 
			$RutaAlmacenamiento, $FechaIncorporacion, $FechaVisualiacion, $FechaBaja, $Erp, $Web, $Estado, 
			$EstadoDocumento, 
			$iDisplayStart, $iDisplayLength, $sSearch, $sSortDir_0, $iSortCol_0){

		//$mcomprobante = new Mcomprobante();
		//return $RowsDeleted;

		try
		{
			$result = array();
			$sSelect = "
			select 
				d.id_documento, 
				e.ruc_empresa, e.razon_social_empresa, e.nombre_comercial_empresa, 
				tdi.nombre_tipo_documento_identidad, 
				em.apellidos_emp, em.nombres_emp, 
				td.desc_tipo_documento, 
				a.nombre_area, 
				d.fecha_documento, d.ruta_almacenamiento, d.fecha_incorporacion, d.fecha_visualizacion, d.fecha_baja, d.id_erp, 
				d.id_web, d.estado_documento 
			from documento d 
				inner join 
					empresa e on e.ruc_empresa = d.ruc_empresa and (e.estado_empresa = '".$Estado."' or '".$Estado."' = 'T') 
				inner join 
					empleado em on em.id_empleado = d.id_empleado and (em.estado_emp = '".$Estado."' or '".$Estado."' = 'T') 
				inner join 
					tipo_documento_identidad tdi on tdi.id_tipo_documento_identidad = em.id_tipo_documento_identidad and 
					(tdi.estado_tipo_documento_identidad = '".$Estado."' or '".$Estado."' = 'T') 
				inner join 
					tipo_documento td on td.id_tipo_documento = d.id_tipo_documento and 
					(td.estado_tipo_documento = '".$Estado."' or '".$Estado."' = 'T') 
				inner join 
					area a on a.id_area = d.id_area and (a.estado_area  = '".$Estado."' or '".$Estado."' = 'T') ";
 
			$sLimit = "";

			if ( $iDisplayStart !='' && $iDisplayLength != '-1' )
			{
				$sLimit = "LIMIT ".intval($iDisplayLength)." OFFSET ".
				intval($iDisplayStart);
			}

			$sWhere = "where (a.estado_documento  = '".$EstadoDocumento."' or '".$EstadoDocumento."' = 'T') ";

			if(!is_null($TipoDocumento))
				$sWhere .= "and td.id_tipo_documento = ".$TipoDocumento." ";				

			if ( $sSearch != "" )
		    {

		        $sWhere .= "and ";
	            $sWhere .= "(";
	            $sWhere .= "d.fecha_documento::text ILIKE '%$sSearch%' OR ";
	            $sWhere .= "em.apellidos_emp ILIKE '%$sSearch%' OR ";
	            $sWhere .= "em.nombres_emp ILIKE '%$sSearch%' OR ";
	            $sWhere .= "a.nombre_area ILIKE '%$sSearch%' OR ";
	            $sWhere .= "d.fecha_incorporacion::text ILIKE '%$sSearch%' OR ";
	            $sWhere .= "d.fecha_visualizacion::text ILIKE '%$sSearch%' OR ";
		        $sWhere = substr_replace( $sWhere, "", -3 );
		       	$sWhere .= ")";

		    }
			//Metodo de ordenamiento
		    $sOrder = "";

			if ($iSortCol_0 != ""){		

				$sOrder = "ORDER BY  ";		    

				switch ($iSortCol_0) {
				    case 2:
						$sOrder .= "d.fecha_documento ".($sSortDir_0==='asc' ? 'asc' : 'desc').", ";
				        break;
				    case 3:
							$sOrder .= "em.apellidos_emp ".($sSortDir_0==='asc' ? 'asc' : 'desc').", ";
				        break;
				    case 4:
							$sOrder .= "em.nombres_emp ".($sSortDir_0==='asc' ? 'asc' : 'desc').", ";
				        break;
				    case 5:
							$sOrder .= "a.nombre_area ".($sSortDir_0==='asc' ? 'asc' : 'desc').", ";
				        break;
				    case 6:
							$sOrder .= "d.fecha_incorporacion ".($sSortDir_0==='asc' ? 'asc' : 'desc').", ";
				        break;
				    case 7:
							$sOrder .= "d.fecha_visualizacion ".($sSortDir_0==='asc' ? 'asc' : 'desc').", ";
				        break;
				    case 8:
							$sOrder .= "a.estado_documento ".($sSortDir_0==='asc' ? 'asc' : 'desc').", ";
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
		    //echo $sSearch;

			$stm = $this->pdo->prepare($sQuery);
			$stm->execute();
			$a_respuesta = array();
			//$reg = $stm->fetchAll(PDO::FETCH_OBJ);
			while($reg = $stm->fetch())
			{
				$documento = new Documento();
				$documento->setId($reg[0]);

				$empresa = new Empresa();
				$empresa->setRuc($reg[1]);
				$empresa->setRazonSocial($reg[2]);
				$empresa->setNombreComercial($reg[3]);

				$documento->setEmpresa($empresa);

				$empleado = new Empleado();
				$tdi = new TipoDocumentoIdentidad();
				$tdi->setNombre($reg[4]);
				$empleado->setNombres($reg[5]);
				$empleado->setApellidos($reg[6]);
				$empleado->setTipoDocumentoIdentidad($tdi);

				$documento->setEmpleado($empleado);

				$td = new TipoDocumento();
				$td->setDescripcion($reg[7]);

				$area = new Area();
				$area->setNombre($reg[8]);

				$documento->setFechaDocumento($reg[9]);
				$documento->setRutaAlmacenamiento($reg[10]);
				$documento->setFechaIncorporacion($reg[11]);
				$documento->setFechaVisualizacion($reg[12]);
				$documento->setFechaBaja($reg[13]);
				$documento->setIdErp($reg[14]);
				$documento->setIdWeb($reg[15]);
				$documento->setEstado($reg[16]);



			    array_push($a_respuesta, $documento);
			}

			$sQuery = "
				select 
					d.id_documento, 
					e.ruc_empresa, e.razon_social_empresa, e.nombre_comercial_empresa, 
					tdi.nombre_tipo_documento_identidad, 
					em.apellidos_emp, em.nombres_emp, 
					td.desc_tipo_documento, 
					a.nombre_area, 
					d.fecha_documento, d.ruta_almacenamiento, d.fecha_incorporacion, d.fecha_visualizacion, d.fecha_baja, d.id_erp, d.id_web, d.estado_documento 
				from documento d 
					inner join 
						empresa e on e.ruc_empresa = d.ruc_empresa and (e.estado_empresa = '".$Estado."' or '".$Estado."' = 'T') 
					inner join 
						empleado em on em.id_empleado = d.id_empleado and (em.estado_emp = '".$Estado."' or '".$Estado."' = 'T') 
					inner join 
						tipo_documento_identidad tdi on tdi.id_tipo_documento_identidad = em.id_tipo_documento_identidad and 
						(tdi.estado_tipo_documento_identidad = '".$Estado."' or '".$Estado."' = 'T') 
					inner join 
						tipo_documento td on td.id_tipo_documento = d.id_tipo_documento and 
						(td.estado_tipo_documento = '".$Estado."' or '".$Estado."' = 'T') 
					inner join 
						area a on a.id_area = d.id_area and (a.estado_area  = '".$Estado."' or '".$Estado."' = 'T') ";
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
    		//echo $result;	
		}
		catch(Exception $e)
		{
			die($e->getMessage());	
		}
	}

	public function guardar($documento){
	
		$error_desc = '';				

		try {

			$sQuery = "";

			if(is_null($documento->getId())){
				$sQuery = "
				        select 
				        	rid_documento, rv_msj_error 
				        from 
				        	documento_nuevo(?,?,?,?,?,?,?,?,?,?,?);
				    ";
				$stm = $this->pdo->prepare($sQuery);

				$stm->execute(
					array(
						$documento->getEmpresa()->getRuc(),
						$documento->getEmpleado()->getId(), 
						$documento->getTipoDocumento()->getId(),
						$documento->getArea()->getId(),
						$documento->getRutaAlmacenamiento(), 
						$documento->getFechaIncorporacion(), 
						$documento->getFechaVisualizacion(), 
						$documento->getFechaBaja(), 
						$documento->getIdErp(),
						$documento->getIdWeb(),
						$documento->getAuditoria()->getId()
						)
					);

				$row = $stm->fetch();			

				if($row){
					$error_desc = $row['rv_msj_error'];
					$documento->setId($row['rid_documento']);
				}

			}else{
				$sQuery = "
				        select 
				        	rv_msj_error 
				        from 
				        	documento_editar(?,?,?,?,?,?,?,?,?,?,?,?,?);
				    ";
				$stm = $this->pdo->prepare($sQuery);

				$stm->execute(
					array(
						$documento->getId(),
						$documento->getEmpresa()->getRuc(),
						$documento->getEmpleado()->getId(), 
						$documento->getTipoDocumento()->getId(),
						$documento->getArea()->getId(),
						$documento->getRutaAlmacenamiento(), 
						$documento->getFechaIncorporacion(), 
						$documento->getFechaVisualizacion(), 
						$documento->getFechaBaja(), 
						$documento->getIdErp(),
						$documento->getIdWeb(),
						$documento->getEstado(),
						$documento->getAuditoria()->getId()
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
					"documento" => $documento,
	    			"error_desc" => $error_desc
	    			);	
	}



	public function listarTablaDocumentos(
		$Estado,
		$Rucempresa,
		$TipoDocumento,
		$iDisplayStart, $iDisplayLength, $sSearch, $sSortDir_0, $iSortCol_0){

		try
		{
			$result = array();
			$sSelect = "
			select 
				d.id_documento, 
				e.ruc_empresa, e.razon_social_empresa, e.nombre_comercial_empresa, 
				tdi.nombre_tipo_documento_identidad, 
				em.nombres_emp, em.apellidos_emp,
				td.desc_tipo_documento, 
				a.nombre_area, 
				d.fecha_documento, d.ruta_almacenamiento, d.fecha_incorporacion, d.fecha_visualizacion, d.fecha_baja, d.id_erp, 
				d.id_web, d.estado_documento 
			from documento d 
				inner join 
					empresa e on e.ruc_empresa = d.ruc_empresa and (e.estado_empresa = '".$Estado."' or '".$Estado."' = 'T') 
				inner join 
					empleado em on em.id_empleado = d.id_empleado and (em.estado_emp = '".$Estado."' or '".$Estado."' = 'T') 
				inner join 
					tipo_documento_identidad tdi on tdi.id_tipo_documento_identidad = em.id_tipo_documento_identidad and 
					(tdi.estado_tipo_documento_identidad = '".$Estado."' or '".$Estado."' = 'T') 
				inner join 
					tipo_documento td on td.id_tipo_documento = d.id_tipo_documento and 
					(td.estado_tipo_documento = '".$Estado."' or '".$Estado."' = 'T') 
				inner join 
					area a on a.id_area = d.id_area and (a.estado_area  = '".$Estado."' or '".$Estado."' = 'T') ";
 
			$sLimit = "";

			if ( $iDisplayStart !='' && $iDisplayLength != '-1' )
			{
				$sLimit = "LIMIT ".intval($iDisplayLength)." OFFSET ".
				intval($iDisplayStart);
			}

			//$sWhere = "where (a.estado_documento  = '".$EstadoDocumento."' or '".$EstadoDocumento."' = 'T') ";

			$sWhere = "where (d.ruc_empresa ='".$Rucempresa."' or '".$Estado."'='T' ) ";
			//$sWhere .= "and d.ruc_empresa ='".$Rucempresa."'";

			if(!is_null($TipoDocumento))
				$sWhere .= "and td.id_tipo_documento = ".$TipoDocumento." ";

				//$sWhere .= "and td.id_tipo_documento =1 ";

			if ( $sSearch != "" )
		    {

		        $sWhere .= "and ";
	            $sWhere .= "(";
	            $sWhere .= "d.fecha_documento::text ILIKE '%$sSearch%' OR ";
	            $sWhere .= "em.apellidos_emp ILIKE '%$sSearch%' OR ";
	            $sWhere .= "em.nombres_emp ILIKE '%$sSearch%' OR ";
	            $sWhere .= "a.nombre_area ILIKE '%$sSearch%' OR ";
	            $sWhere .= "d.fecha_incorporacion::text ILIKE '%$sSearch%' OR ";
	            $sWhere .= "d.fecha_visualizacion::text ILIKE '%$sSearch%' OR ";
		        $sWhere = substr_replace( $sWhere, "", -3 );
		       	$sWhere .= ")";

		    }


		    			//Metodo de ordenamiento
		    $sOrder = "";

			if ($iSortCol_0 != ""){		

				$sOrder = "ORDER BY  ";		    

				switch ($iSortCol_0) {
				    case 2:
						$sOrder .= "d.fecha_documento ".($sSortDir_0==='asc' ? 'asc' : 'desc').", ";
				        break;
				    case 3:
							$sOrder .= "em.apellidos_emp ".($sSortDir_0==='asc' ? 'asc' : 'desc').", ";
				        break;
				    case 4:
							$sOrder .= "em.nombres_emp ".($sSortDir_0==='asc' ? 'asc' : 'desc').", ";
				        break;
				    case 5:
							$sOrder .= "a.nombre_area ".($sSortDir_0==='asc' ? 'asc' : 'desc').", ";
				        break;
				    case 6:
							$sOrder .= "d.fecha_incorporacion ".($sSortDir_0==='asc' ? 'asc' : 'desc').", ";
				        break;
				    case 7:
							$sOrder .= "d.fecha_visualizacion ".($sSortDir_0==='asc' ? 'asc' : 'desc').", ";
				        break;
				    case 8:
							$sOrder .= "a.estado_documento ".($sSortDir_0==='asc' ? 'asc' : 'desc').", ";
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
		        
		        
		    ";	
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
				$documento = new Documento();
				$documento->setId($reg[0]);

				$empresa = new Empresa();
				$empresa->setRuc($reg[1]);
				$empresa->setRazonSocial($reg[2]);
				$empresa->setNombreComercial($reg[3]);

				$documento->setEmpresa($empresa);

				$empleado = new Empleado();
				$tdi = new TipoDocumentoIdentidad();
				$tdi->setNombre($reg[4]);
				$empleado->setNombres($reg[5].', ' .$reg[6]);
				$empleado->setApellidos($reg[6]);
				$empleado->setTipoDocumentoIdentidad($tdi);

				$documento->setEmpleado($empleado);

				$td = new TipoDocumento();
				$td->setDescripcion($reg[7]);

				$area = new Area();
				$area->setNombre($reg[8]);
				$documento->setArea($area);

				$documento->setFechaDocumento($reg[9]);
				$documento->setRutaAlmacenamiento($reg[10]);
				$documento->setFechaIncorporacion($reg[11]);
				$documento->setFechaVisualizacion($reg[12]);
				$documento->setFechaBaja($reg[13]);
				$documento->setIdErp($reg[14]);
				$documento->setIdWeb($reg[15]);
				$documento->setEstado($reg[16]);



			    array_push($a_respuesta, $documento);
			}

			$sQuery = "
				select 
					d.id_documento, 
					e.ruc_empresa, e.razon_social_empresa, e.nombre_comercial_empresa, 
					tdi.nombre_tipo_documento_identidad, 
					em.apellidos_emp, em.nombres_emp, 
					td.desc_tipo_documento, 
					a.nombre_area, 
					d.fecha_documento, d.ruta_almacenamiento, d.fecha_incorporacion, d.fecha_visualizacion, d.fecha_baja, d.id_erp, d.id_web, d.estado_documento 
				from documento d 
					inner join 
						empresa e on e.ruc_empresa = d.ruc_empresa and (e.estado_empresa = '".$Estado."' or '".$Estado."' = 'T') 
					inner join 
						empleado em on em.id_empleado = d.id_empleado and (em.estado_emp = '".$Estado."' or '".$Estado."' = 'T') 
					inner join 
						tipo_documento_identidad tdi on tdi.id_tipo_documento_identidad = em.id_tipo_documento_identidad and 
						(tdi.estado_tipo_documento_identidad = '".$Estado."' or '".$Estado."' = 'T') 
					inner join 
						tipo_documento td on td.id_tipo_documento = d.id_tipo_documento and 
						(td.estado_tipo_documento = '".$Estado."' or '".$Estado."' = 'T') 
					inner join 
						area a on a.id_area = d.id_area and (a.estado_area  = '".$Estado."' or '".$Estado."' = 'T') ";
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

	public function listar(
		$IdEmpleado,
		$Estado,
		$Rucempresa,
		$TipoDocumento){

		try
		{		
			$a_respuesta = array();

			$sQuery = "
			select 
				d.id_documento, 
				e.ruc_empresa, e.razon_social_empresa, e.nombre_comercial_empresa, 
				tdi.nombre_tipo_documento_identidad, 
				em.nombres_emp, em.apellidos_emp,
				td.desc_tipo_documento, 
				a.nombre_area, 
				d.fecha_documento, d.ruta_almacenamiento, d.fecha_incorporacion, d.fecha_visualizacion, d.fecha_baja, d.id_erp, 
				d.id_web, d.estado_documento 
			from documento d 
				inner join 
					empresa e on e.ruc_empresa = d.ruc_empresa and (e.estado_empresa = '".$Estado."' or '".$Estado."' = 'T') 
				inner join 
					empleado em on em.id_empleado = d.id_empleado and (em.estado_emp = '".$Estado."' or '".$Estado."' = 'T') 
				inner join 
					tipo_documento_identidad tdi on tdi.id_tipo_documento_identidad = em.id_tipo_documento_identidad and 
					(tdi.estado_tipo_documento_identidad = '".$Estado."' or '".$Estado."' = 'T') 
				inner join 
					tipo_documento td on td.id_tipo_documento = d.id_tipo_documento and 
					(td.estado_tipo_documento = '".$Estado."' or '".$Estado."' = 'T') 
				inner join 
					area a on a.id_area = d.id_area and (a.estado_area  = '".$Estado."' or '".$Estado."' = 'T') ";


			$sQuery .= "where (d.ruc_empresa ='".$Rucempresa."' or '".$Estado."'='T' ) ";
			//$sWhere .= "and d.ruc_empresa ='".$Rucempresa."'";

			if(!is_null($TipoDocumento))
				$sQuery .= "and td.id_tipo_documento = ".$TipoDocumento." ";

			if(!is_null($IdEmpleado))
				$sQuery .= "and d.id_empleado = ".$IdEmpleado." ";	

			$stm = $this->pdo->prepare($sQuery);
			$stm->execute();
			$a_respuesta = array();

			while($reg = $stm->fetch())
			{
				$documento = new Documento();
				$documento->setId($reg[0]);

				$empresa = new Empresa();
				$empresa->setRuc($reg[1]);
				$empresa->setRazonSocial($reg[2]);
				$empresa->setNombreComercial($reg[3]);

				$documento->setEmpresa($empresa);

				$empleado = new Empleado();
				$tdi = new TipoDocumentoIdentidad();
				$tdi->setNombre($reg[4]);
				$empleado->setNombres($reg[5].', ' .$reg[6]);
				$empleado->setApellidos($reg[6]);
				$empleado->setTipoDocumentoIdentidad($tdi);

				$documento->setEmpleado($empleado);

				$td = new TipoDocumento();
				$td->setDescripcion($reg[7]);

				$area = new Area();
				$area->setNombre($reg[8]);
				$documento->setArea($area);

				$documento->setFechaDocumento($reg[9]);
				$documento->setRutaAlmacenamiento($reg[10]);
				$documento->setFechaIncorporacion($reg[11]);
				$documento->setFechaVisualizacion($reg[12]);
				$documento->setFechaBaja($reg[13]);
				$documento->setIdErp($reg[14]);
				$documento->setIdWeb($reg[15]);
				$documento->setEstado($reg[16]);

			    array_push($a_respuesta, $documento);
			}						
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}	
		return $a_respuesta;	
	}

	public function obtenerUltimaActualizacion($Ruc){

		$maximo = "";

		$sQuery = "select max(fecha_visualizacion) as maximo from documento where ruc_empresa= '" .$Ruc."'";

		try
		{		
			$stm = $this->pdo->prepare($sQuery);
			$stm->execute();
			$row = $stm->fetch();			

			if($row){
				$maximo = $row['maximo'];
			}

		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}

		return $maximo;

	}

}