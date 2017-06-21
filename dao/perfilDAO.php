<?php

require_once 'model/perfil.php';
require_once 'model/auditoria.php';
require_once 'model/submenu.php';
require_once 'model/area.php';
require_once 'model/tipodocumento.php';


class PerfilDAO
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

			    array_push($a_respuesta, $perfil);
			}	
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}	
		return $a_respuesta;		
	}
	public function listarTabla(
		$Ruc, $Estado, 
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
				empresa e on e.ruc_empresa = p.ruc_empresa and (e.ruc_empresa = '".$Ruc."' or '".$Ruc."' = 'T') and (e.estado_empresa  = '".$Estado."' or '".$Estado."' = 'T')";

			$sLimit = "";

			if ( $iDisplayStart !='' && $iDisplayLength != '-1' )
			{
				$sLimit = "LIMIT ".intval($iDisplayLength)." OFFSET ".
				intval($iDisplayStart);
			}

			$sWhere = "where (p.estado_perfil  = '".$Estado."' or '".$Estado."' = 'T')";

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
	public function guardar($perfil){
		$modulo = "perfilDAO";
		$error = Constants::FLAG_INCORRECTO;		

		try {

			$sQuery = "";
			$this->pdo->beginTransaction();

			if(is_null($perfil->getId())){
				$modulo .= "-nuevo";
				$sQuery = "
				        select 
				        	rv_msj_error 
				        from 
				        	sp_perfil_nuevo(?,?,?,?,?,?,?,?,?,?);
				    ";
				$stm = $this->pdo->prepare($sQuery);

				$stm->execute(
					array(
						$perfil->getEmpresa()->getRuc(),
						$perfil->getNombre(), 
						$perfil->getDescripcion(), 
						$perfil->getAuditoria()->getUsucrea(),
						$perfil->getAuditoria()->getIpcrea(),
						$perfil->getAuditoria()->getPccrea(),
						$perfil->getAuditoria()->getFechacrea(),
						Constants::to_pg_array($perfil->getListSubmenu()),
						Constants::to_pg_array($perfil->getListArea()),
						Constants::to_pg_array($perfil->getListTipoDocumento())
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
				$modulo .= "-editar";
				$sQuery = "
				        select 
				        	rv_msj_error 
				        from 
				        	sp_perfil_editar(?,?,?,?,?,?,?,?,?,?,?);
				    ";
				$stm = $this->pdo->prepare($sQuery);

				$stm->execute(
					array(
						$perfil->getId(),
						$perfil->getEmpresa()->getRuc(),
						$perfil->getNombre(), 
						$perfil->getDescripcion(), 
						$perfil->getAuditoria()->getUsucrea(),
						$perfil->getAuditoria()->getIpcrea(),
						$perfil->getAuditoria()->getPccrea(),
						$perfil->getAuditoria()->getFechacrea(),
						Constants::to_pg_array($perfil->getListSubmenu()),
						Constants::to_pg_array($perfil->getListArea()),
						Constants::to_pg_array($perfil->getListTipoDocumento())
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



		return array(
					"modulo" => $modulo,
	    			"error" => $error
	    			);	

	}	
}