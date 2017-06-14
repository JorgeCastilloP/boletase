<?php

require_once 'model/area.php';
require_once 'model/auditoria.php';

class AreaDAO
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
	public function listar($Ruc, $Estado){
		try
		{
			$sQuery = "
				select 
					a.id_area, a.ruc_empresa, a.nombre_area, a.desc_area, 
					a.estado_area, a.id_auditoria 
				from 
					area a 
				inner join 
					empresa e on e.ruc_empresa =a.ruc_empresa 
				inner join 
					auditoria d on d.id_auditoria = a.id_auditoria ";

			if($Ruc !== 'T' && $Estado !== 'T')
				$sQuery .= "
					where 
						a.ruc_empresa = '".$Ruc."' and (a.estado_area  = '".$Estado."' or '".$Estado."' = 'T')";

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

			    $auditoria = new Auditoria();
			    $auditoria->setId($reg[5]);

				$area->setAuditoria($auditoria);

			    array_push($a_respuesta, $area);
			}	
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}	
		return $a_respuesta;		
	}
	public function listarTabla(
		$Estado, 
		$iDisplayStart, $iDisplayLength, $sSearch, $sSortDir_0, $iSortCol_0){
		try
		{
			$result = array();

			$sSelect = "
			select 
				a.id_area, a.nombre_area, a.desc_area, a.estado_area, 
				e.ruc_empresa, e.razon_social_empresa, e.nombre_comercial_empresa,
				a.id_auditoria 
			from 
				area a 
			inner join 
				empresa e on e.ruc_empresa =a.ruc_empresa ";

			$sLimit = "";

			if ( $iDisplayStart !='' && $iDisplayLength != '-1' )
			{
				$sLimit = "LIMIT ".intval($iDisplayLength)." OFFSET ".
				intval($iDisplayStart);
			}

			$sWhere = "where (a.estado_area  = '".$Estado."' or '".$Estado."' = 'T') and (e.estado_empresa  = '".$Estado."' or '".$Estado."' = 'T')";

			if ( $sSearch != "" )
		    {

		        $sWhere .= "and ";
	            $sWhere .= "(";
	            $sWhere .= "a.nombre_area ILIKE '%$sSearch%' OR ";
	            $sWhere .= "a.desc_area ILIKE '%$sSearch%' OR ";
	            $sWhere .= "a.estado_area ILIKE '%$sSearch%' OR ";
		        $sWhere = substr_replace( $sWhere, "", -3 );
		       	$sWhere .= ")";

		    }

		    $sOrder = "";

			if ($iSortCol_0 != ""){		

				$sOrder = "ORDER BY  ";		    

				switch ($iSortCol_0) {
				    case 2:
						$sOrder .= "p.nombre_area ".($sSortDir_0==='asc' ? 'asc' : 'desc').", ";
				        break;
				    case 3:
						$sOrder .= "p.desc_area ".($sSortDir_0==='asc' ? 'asc' : 'desc').", ";
				        break;
				    case 3:
						$sOrder .= "p.estado_area ".($sSortDir_0==='asc' ? 'asc' : 'desc').", ";						
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
				$area = new Area();
				$area->setId($reg[0]);
				$area->setNombre($reg[1]);
				$area->setDescripcion($reg[2]);
				$area->setEstado($reg[3]);

				$empresa = new Empresa();
				$empresa->setRuc($reg[4]);
				$empresa->setRazonSocial($reg[5]);
				$empresa->setNombreComercial($reg[6]);

				$auditoria = new Auditoria();
				$auditoria->setId($reg[7]);

				$area->setEmpresa($empresa);
				$area->setAuditoria($auditoria);

				array_push($a_respuesta, $area);
			}

			$sQuery = "
			select 
				a.id_area, a.nombre_area, a.desc_area, a.estado_area, 
				e.ruc_empresa, e.razon_social_empresa, e.nombre_comercial_empresa,
				a.id_auditoria 
			from 
				area a 
			inner join 
				empresa e on e.ruc_empresa =a.ruc_empresa ";

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
	public function guardar($area){

		$modulo = "areaDAO";
		$error = Constants::FLAG_INCORRECTO;				

		try {

			$sQuery = "";

			if(is_null($area->getId())){
				$sQuery = "
				        select 
				        	rid_area, rv_msj_error 
				        from 
				        	area_nuevo(?,?,?,?);
				    ";
				$stm = $this->pdo->prepare($sQuery);

				$stm->execute(
					array(
						$area->getEmpresa()->getRuc(),
						$area->getNombre(), 
						$area->getDescripcion(), 
						$area->getAuditoria()->getId()
						)
					);

				$row = $stm->fetch();			

				if($row){
					$error = ($row['rv_msj_error'] == null ? Constants::FLAG_CORRECTO : $row['rv_msj_error']);
					$area->setId($row['rid_area']);
				}

			}else{
				$sQuery = "
				        select 
				        	rv_msj_error 
				        from 
				        	area_editar(?,?,?,?,?,?);
				    ";
				$stm = $this->pdo->prepare($sQuery);

				$stm->execute(
					array(
						$area->getId(), 
						$area->getEmpresa()->getRuc(),
						$area->getNombre(), 
						$area->getDescripcion(), 
						$area->getEstado(), 
						$area->getAuditoria()->getId()
						)
					);								

				$row = $stm->fetch();			

				if($row){
					$error = ($row['rv_msj_error'] == null ? Constants::FLAG_CORRECTO : $row['rv_msj_error']);
				}				
			}


		} catch (Exception $e) {
			$error_desc = $e->getMessage();				
		}

		return array(
					"modulo" => $modulo,
					"area" => $area,
	    			"error" => $error
	    			);	

	}	
}