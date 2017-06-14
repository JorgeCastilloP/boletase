<?php

require_once 'model/auditoria.php';


class AuditoriaDAO
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
					p.id_perfil, p.ruc_empresa, p.nombre_perfil, p.desc_perfil, 
					p.estado_perfil, p.id_auditoria 
				from 
					perfil p 
				inner join 
					empresa e on e.ruc_empresa = p.ruc_empresa 
				inner join 
					auditoria a on a.id_auditoria = p.id_auditoria ";

			if($Ruc !== 'T' && $Estado !== 'T')
				$sQuery .= "
					where 
						e.ruc_empresa = '".$Ruc."' and (estado_perfil  = '".$Estado."' or '".$Estado."' = 'T')";

			$stm = $this->pdo->prepare($sQuery);
			$stm->execute();
			$a_respuesta = array();

			while($reg = $stm->fetch())
			{
				$perfil = new Perfil();
				$perfil->setId($reg[0]);

				$empresa = new Empresa();
				$empresa->setRuc($reg[1]);

				$perfil->setEmpresa($empresa);
				$perfil->setNombre($reg[2]);
			    $perfil->setDescripcion($reg[3]);
			    $perfil->setEstado($reg[4]);

			    $auditoria = new Auditoria();
			    $auditoria->setId($reg[5]);

				$perfil->setAuditoria($auditoria);

			    array_push($a_respuesta, $tdi);
			}	
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}	
		return $a_respuesta;		
	}
	public function listarTabla(
		$EstadoPerfil, $EstadoEmpresa,
		$iDisplayStart, $iDisplayLength, $sSearch, $sSortDir_0, $iSortCol_0){
		try
		{
			$result = array();

			$sSelect = "
			select 
				p.id_perfil, p.nombre_perfil, p.desc_perfil, p.estado_perfil, 
				e.ruc_empresa, e.razon_social_empresa, e.nombre_comercial_empresa, 
				p.id_auditoria 
			from 
				perfil p 
			inner join 
				empresa e on e.ruc_empresa = p.ruc_empresa ";

			$sLimit = "";

			if ( $iDisplayStart !='' && $iDisplayLength != '-1' )
			{
				$sLimit = "LIMIT ".intval($iDisplayLength)." OFFSET ".
				intval($iDisplayStart);
			}

			$sWhere = "where (p.estado_perfil  = '".$EstadoPerfil."' or '".$EstadoPerfil."' = 'T') and (e.estado_empresa  = '".$EstadoEmpresa."' or '".$EstadoEmpresa."' = 'T')";

			if ( $sSearch != "" )
		    {

		        $sWhere .= "and ";
	            $sWhere .= "(";
	            $sWhere .= "p.nombre_perfil ILIKE '%$sSearch%' OR ";
	            $sWhere .= "p.desc_perfil ILIKE '%$sSearch%' OR ";
	            $sWhere .= "p.estado_perfil ILIKE '%$sSearch%' OR ";
		        $sWhere = substr_replace( $sWhere, "", -3 );
		       	$sWhere .= ")";

		    }

		    $sOrder = "";

			if ($iSortCol_0 != ""){		

				$sOrder = "ORDER BY  ";		    

				switch ($iSortCol_0) {
				    case 2:
						$sOrder .= "p.nombre_perfil ".($sSortDir_0==='asc' ? 'asc' : 'desc').", ";
				        break;
				    case 3:
						$sOrder .= "p.desc_perfil ".($sSortDir_0==='asc' ? 'asc' : 'desc').", ";
				        break;
				    case 3:
						$sOrder .= "p.estado_perfil ".($sSortDir_0==='asc' ? 'asc' : 'desc').", ";						
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
				$perfil = new Perfil();
				$perfil->setId($reg[0]);
				$perfil->setNombre($reg[1]);
				$perfil->setDescripcion($reg[2]);
				$perfil->setEstado($reg[3]);

				$empresa = new Empresa();
				$empresa->setRuc($reg[4]);
				$empresa->setRazonSocial($reg[5]);
				$empresa->setNombreComercial($reg[6]);

				$auditoria = new Auditoria();
				$auditoria->setId($reg[7]);

				$perfil->setEmpresa($empresa);
				$perfil->setAuditoria($auditoria);

				array_push($a_respuesta, $perfil);
			}

			$sQuery = "
			select 
				p.id_perfil, p.nombre_perfil, p.desc_perfil, p.estado_perfil, 
				e.ruc_empresa, e.razon_social_empresa, e.nombre_comercial_empresa, 
				p.id_auditoria 
			from 
				perfil p 
			inner join 
				empresa e on e.ruc_empresa = p.ruc_empresa ";

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
	public function guardar($auditoria){
		$modulo = "auditoriaDAO";
		$error = Constants::FLAG_INCORRECTO;		

		try {

			$sQuery = "";

			if(is_null($auditoria->getId())){
				$modulo .= "-nuevo";


				$sQuery = "
				        select 
				        	rid_auditoria, rv_msj_error 
				        from 
				        	auditoria_nuevo(?,?,?,?);
				    ";
				$stm = $this->pdo->prepare($sQuery);

				$stm->execute(
					array(
						$auditoria->getUsucrea(),
						$auditoria->getIpcrea(), 
						$auditoria->getPccrea(), 
						$auditoria->getFechacrea()
						)
					);

				$row = $stm->fetch();			

				if($row){
					$error = ($row['rv_msj_error'] == null ? Constants::FLAG_CORRECTO : $row['rv_msj_error']);
					$auditoria->setId($row['rid_auditoria']);
				}

			}else{
				$modulo .= "-editar";
				$sQuery = "
				        select 
				        	rv_msj_error 
				        from 
				        	auditoria_editar(?,?,?,?,?);
				    ";
				$stm = $this->pdo->prepare($sQuery);

				$stm->execute(
					array(
						$auditoria->getId(), 
						$auditoria->getUsuedita(),
						$auditoria->getIpedita(), 
						$auditoria->getPcedita(), 
						$auditoria->getFechaedita()
						)
					);								

				$row = $stm->fetch();			

				if($row){
					$error = ($row['rv_msj_error'] == null ? Constants::FLAG_CORRECTO : $row['rv_msj_error']);
				}				
			}


		} catch (Exception $e) {
			$error = $e->getMessage();				
		}

		return array(
					"modulo" => $modulo,
					"auditoria" => $auditoria,
	    			"error" => $error
	    			);	

	}	
}