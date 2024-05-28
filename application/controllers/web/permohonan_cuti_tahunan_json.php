<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class permohonan_cuti_tahunan_json extends CI_Controller {

	function __construct() {
		parent::__construct();
		
		//kauth
		if (!$this->kauth->getInstance()->hasIdentity())
		{
			// trow to unauthenticated page!
			//redirect('Login');
		}       
		
		/* GLOBAL VARIABLE */
		$this->ID = $this->kauth->getInstance()->getIdentity()->ID;   			
		$this->USER_GROUP_ID = $this->kauth->getInstance()->getIdentity()->USER_GROUP_ID;   
		$this->NAMA = $this->kauth->getInstance()->getIdentity()->NAMA;   
		$this->USERNAME = $this->kauth->getInstance()->getIdentity()->USERNAME;
		$this->KODE_CABANG = $this->kauth->getInstance()->getIdentity()->KODE_CABANG;
		$this->CABANG = $this->kauth->getInstance()->getIdentity()->CABANG;
		$this->DEPARTEMEN = $this->kauth->getInstance()->getIdentity()->DEPARTEMEN;
		$this->SUB_DEPARTEMEN = $this->kauth->getInstance()->getIdentity()->SUB_DEPARTEMEN;
		$this->JABATAN = $this->kauth->getInstance()->getIdentity()->JABATAN;  
		$this->APPROVAL_MONITORING = $this->kauth->getInstance()->getIdentity()->APPROVAL_MONITORING;  
		$this->TAHUN_CUTI = $this->kauth->getInstance()->getIdentity()->TAHUN_CUTI;  
	}	
	
	function json() 
	{
		$reqStatus = $this->input->get("reqStatus");
		
		$this->load->model('PermohonanCutiTahunan');
		$permohonan_cuti_tahunan = new PermohonanCutiTahunan;
		
		if($this->USER_GROUP_ID == 1 || $this->USER_GROUP_ID == 2)
		{
			$aColumns = array("PERMOHONAN_CUTI_TAHUNAN_ID", "APPROVAL","NAMA_PEGAWAI", "NOMOR","TAHUN", "TANGGAL", "LAMA_CUTI", "TANGGAL_AWAL", "TANGGAL_AKHIR", "APPROVAL1", "APPROVAL2");
			$aColumnsAlias = array("PERMOHONAN_CUTI_TAHUNAN_ID", "APPROVAL", "NAMA_PEGAWAI", "NOMOR","TAHUN", "TANGGAL", "LAMA_CUTI", "TANGGAL_AWAL", "TANGGAL_AKHIR", "APPROVAL1", "APPROVAL2");
		}
		else
		{
			$aColumns = array("PERMOHONAN_CUTI_TAHUNAN_ID", "APPROVAL","NAMA_PEGAWAI", "NOMOR","TAHUN", "TANGGAL", "LAMA_CUTI", "TANGGAL_AWAL", "TANGGAL_AKHIR", "STATUS_APPROVAL", "STATUS_APPROVAL_LAIN");
			$aColumnsAlias = array("PERMOHONAN_CUTI_TAHUNAN_ID", "APPROVAL", "NAMA_PEGAWAI", "NOMOR","TAHUN", "TANGGAL", "LAMA_CUTI", "TANGGAL_AWAL", "TANGGAL_AKHIR", "STATUS_APPROVAL", "STATUS_APPROVAL_LAIN");
		}
		
		/*
		 * Ordering
		 */
		if ( isset( $_GET['iSortCol_0'] ) )
		{
			$sOrder = " ORDER BY ";
			 
			//Go over all sorting cols
			for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
			{
				//If need to sort by current col
				if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
				{
					//Add to the order by clause
					$sOrder .= $aColumnsAlias[ intval( $_GET['iSortCol_'.$i] ) ];
					 
					//Determine if it is sorted asc or desc
					if (strcasecmp(( $_GET['sSortDir_'.$i] ), "asc") == 0)
					{
						$sOrder .=" asc, ";
					}else
					{
						$sOrder .=" desc, ";
					}
				}
			}
			
			//Remove the last space / comma
			$sOrder = substr_replace( $sOrder, "", -2 );
			
			//Check if there is an order by clause
			if ( trim($sOrder) == "ORDER BY PERMOHONAN_CUTI_TAHUNAN_ID asc" )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY PERMOHONAN_CUTI_TAHUNAN_ID DESC";
				 
			}
		}
		 
		/*
		 * Filtering
		 * NOTE this does not match the built-in DataTables filtering which does it
		 * word by word on any field. It's possible to do here, but concerned about efficiency
		 * on very large tables.
		 */
		$sWhere = "";
		$nWhereGenearalCount = 0;
		if (isset($_GET['sSearch']))
		{
			$sWhereGenearal = $_GET['sSearch'];
		}
		else
		{
			$sWhereGenearal = '';
		}
		
		if ( $_GET['sSearch'] != "" )
		{
			//Set a default where clause in order for the where clause not to fail
			//in cases where there are no searchable cols at all.
			$sWhere = " AND (";
			for ( $i=0 ; $i<count($aColumnsAlias)+1 ; $i++ )
			{
				//If current col has a search param
				if ( $_GET['bSearchable_'.$i] == "true" )
				{
					//Add the search to the where clause
					$sWhere .= $aColumnsAlias[$i]." LIKE '%".$_GET['sSearch']."%' OR ";
					$nWhereGenearalCount += 1;
				}
			}
			$sWhere = substr_replace( $sWhere, "", -3 );
			$sWhere .= ')';
		}
		 
		/* Individual column filtering */
		$sWhereSpecificArray = array();
		$sWhereSpecificArrayCount = 0;
		for ( $i=0 ; $i<count($aColumnsAlias) ; $i++ )
		{
			if ( $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
			{
				//If there was no where clause
				if ( $sWhere == "" )
				{
					$sWhere = "AND ";
				}
				else
				{
					$sWhere .= " AND ";
				}
				 
				//Add the clause of the specific col to the where clause
				$sWhere .= $aColumnsAlias[$i]." LIKE '%' || :whereSpecificParam".$sWhereSpecificArrayCount." || '%' ";
				 
				//Inc sWhereSpecificArrayCount. It is needed for the bind var.
				//We could just do count($sWhereSpecificArray) - but that would be less efficient.
				$sWhereSpecificArrayCount++;
				 
				//Add current search param to the array for later use (binding).
				$sWhereSpecificArray[] =  $_GET['sSearch_'.$i];
				 
			}
		}
		 
		//If there is still no where clause - set a general - always true where clause
		if ( $sWhere == "" )
		{
			$sWhere = " AND 1=1";
		}
		 
		//Bind variables.
		if ( isset( $_GET['iDisplayStart'] ))
		{
			$dsplyStart = $_GET['iDisplayStart'];
		}
		else{
			$dsplyStart = 0;
		}
		if ( isset( $_GET['iDisplayLength'] ) && $_GET['iDisplayLength'] != '-1' )
		{
			$dsplyRange = $_GET['iDisplayLength'];
			if ($dsplyRange > (2147483645 - intval($dsplyStart)))
			{
				$dsplyRange = 2147483645;
			}
			else

			{
				$dsplyRange = intval($dsplyRange);
			}
		}
		else
		{
			$dsplyRange = 2147483645;
		}
		
		if($this->USER_GROUP_ID == 1)
			$statement_privacy = "";
		elseif($this->USER_GROUP_ID == 2)
			$statement_privacy = " AND B.CABANG_ID = '".$this->KODE_CABANG."'"; 
		else
			$statement_privacy = " AND (PEGAWAI_ID_APPROVAL1 = '".$this->ID."' OR (PEGAWAI_ID_APPROVAL2 = '".$this->ID."' AND NVL(APPROVAL1, 'X') IN ('Y') )) ";
		
		if($reqStatus == "")
		{}
		else
		{
			if($reqStatus == "B")
			{
				if($this->USER_GROUP_ID == 1 || $this->USER_GROUP_ID == 2)
					$statement_privacy .= " AND EXISTS(SELECT 1 FROM PERMOHONAN_CUTI_TAHUNAN X WHERE X.PERMOHONAN_CUTI_TAHUNAN_ID = A.PERMOHONAN_CUTI_TAHUNAN_ID AND ((NVL(APPROVAL1, 'X') = 'X') OR (NVL(APPROVAL2, 'X') = 'X'  AND NVL(APPROVAL1, 'X') IN ('X', 'Y'))) ) ";	
				else
					$statement_privacy .= " AND EXISTS(SELECT 1 FROM PERMOHONAN_CUTI_TAHUNAN X WHERE X.PERMOHONAN_CUTI_TAHUNAN_ID = A.PERMOHONAN_CUTI_TAHUNAN_ID AND ((PEGAWAI_ID_APPROVAL1 = '".$this->ID."' AND NVL(APPROVAL1, 'X') = 'X') OR (PEGAWAI_ID_APPROVAL2 = '".$this->ID."' AND NVL(APPROVAL2, 'X') = 'X'  AND NVL(APPROVAL1, 'X') IN ('Y'))) ) ";	
			}
			else
			{
				if($this->USER_GROUP_ID == 1 || $this->USER_GROUP_ID == 2)
					$statement_privacy .= " AND EXISTS(SELECT 1 FROM PERMOHONAN_CUTI_TAHUNAN X WHERE X.PERMOHONAN_CUTI_TAHUNAN_ID = A.PERMOHONAN_CUTI_TAHUNAN_ID AND ((NOT NVL(APPROVAL1, 'X') = 'X') OR (NOT NVL(APPROVAL2, 'X') = 'X' AND NVL(APPROVAL1, 'X') IN ('X', 'Y'))) ) ";
				else
					$statement_privacy .= " AND EXISTS(SELECT 1 FROM PERMOHONAN_CUTI_TAHUNAN X WHERE X.PERMOHONAN_CUTI_TAHUNAN_ID = A.PERMOHONAN_CUTI_TAHUNAN_ID AND ((PEGAWAI_ID_APPROVAL1 = '".$this->ID."' AND NOT NVL(APPROVAL1, 'X') IN ('X')) OR (PEGAWAI_ID_APPROVAL2 = '".$this->ID."' AND NOT NVL(APPROVAL2, 'X') = 'X' AND NVL(APPROVAL1, 'X') IN ('Y'))) ) ";
			}
		}
		
		$statement= " AND (UPPER(B.NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%' OR UPPER(C.NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%')";
		
		$allRecord = $permohonan_cuti_tahunan->getCountByParams(array(), $statement_privacy);
		//echo $allRecord;
		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter =  $permohonan_cuti_tahunan->getCountByParams(array(), $statement_privacy.$statement);
		
		$permohonan_cuti_tahunan->selectByParamsApproval($this->ID, array(), $dsplyRange, $dsplyStart, $statement_privacy.$statement, $sOrder);
		
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $allRecord,
			"iTotalDisplayRecords" => $allRecordFilter,
			"aaData" => array()
		);
		
		while($permohonan_cuti_tahunan->nextRow())		
		{		
			$row = array();		
			for ( $i=0 ; $i<count($aColumns) ; $i++ )		
			{		
				$row[] = $permohonan_cuti_tahunan->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		
		echo json_encode( $output );	

		
	}
	
	function json_user() 
	{
		$this->load->model('PermohonanCutiTahunan');
		$permohonan_cuti_tahunan = new PermohonanCutiTahunan;
																													
		$aColumns = array("PERMOHONAN_CUTI_TAHUNAN_ID", "NOMOR", "TANGGAL", "LAMA_CUTI", "TANGGAL_AWAL", "TANGGAL_AKHIR");
		for($i=0;$i<count($this->APPROVAL_MONITORING);$i++)
		{
			array_push($aColumns, $this->APPROVAL_MONITORING[$i]);
		}

		$aColumnsAlias = array("PERMOHONAN_CUTI_TAHUNAN_ID", "NOMOR", "TANGGAL", "LAMA_CUTI", "TANGGAL_AWAL", "TANGGAL_AKHIR", 
							   "NAMA_APPROVAL1", "STATUS_APPROVAL1", "NAMA_APPROVAL2", "STATUS_APPROVAL2");
		
		/*
		 * Ordering
		 */
		if ( isset( $_GET['iSortCol_0'] ) )
		{
			$sOrder = " ORDER BY ";
			 
			//Go over all sorting cols
			for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
			{
				//If need to sort by current col
				if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
				{
					//Add to the order by clause
					$sOrder .= $aColumnsAlias[ intval( $_GET['iSortCol_'.$i] ) ];
					 
					//Determine if it is sorted asc or desc
					if (strcasecmp(( $_GET['sSortDir_'.$i] ), "asc") == 0)
					{
						$sOrder .=" asc, ";
					}else
					{
						$sOrder .=" desc, ";
					}
				}
			}
			
			//Remove the last space / comma
			$sOrder = substr_replace( $sOrder, "", -2 );
			
			//Check if there is an order by clause
			if ( trim($sOrder) == "ORDER BY PERMOHONAN_CUTI_TAHUNAN_ID asc" )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY PERMOHONAN_CUTI_TAHUNAN_ID DESC";
				 
			}
		}
		 
		/*
		 * Filtering
		 * NOTE this does not match the built-in DataTables filtering which does it
		 * word by word on any field. It's possible to do here, but concerned about efficiency
		 * on very large tables.
		 */
		$sWhere = "";
		$nWhereGenearalCount = 0;
		if (isset($_GET['sSearch']))
		{
			$sWhereGenearal = $_GET['sSearch'];
		}
		else
		{
			$sWhereGenearal = '';
		}
		
		if ( $_GET['sSearch'] != "" )
		{
			//Set a default where clause in order for the where clause not to fail
			//in cases where there are no searchable cols at all.
			$sWhere = " AND (";
			for ( $i=0 ; $i<count($aColumnsAlias)+1 ; $i++ )
			{
				//If current col has a search param
				if ( $_GET['bSearchable_'.$i] == "true" )
				{
					//Add the search to the where clause
					$sWhere .= $aColumnsAlias[$i]." LIKE '%".$_GET['sSearch']."%' OR ";
					$nWhereGenearalCount += 1;
				}
			}
			$sWhere = substr_replace( $sWhere, "", -3 );
			$sWhere .= ')';
		}
		 
		/* Individual column filtering */
		$sWhereSpecificArray = array();
		$sWhereSpecificArrayCount = 0;
		for ( $i=0 ; $i<count($aColumnsAlias) ; $i++ )
		{
			if ( $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
			{
				//If there was no where clause
				if ( $sWhere == "" )
				{
					$sWhere = "AND ";
				}
				else
				{
					$sWhere .= " AND ";
				}
				 
				//Add the clause of the specific col to the where clause
				$sWhere .= $aColumnsAlias[$i]." LIKE '%' || :whereSpecificParam".$sWhereSpecificArrayCount." || '%' ";
				 
				//Inc sWhereSpecificArrayCount. It is needed for the bind var.
				//We could just do count($sWhereSpecificArray) - but that would be less efficient.
				$sWhereSpecificArrayCount++;
				 
				//Add current search param to the array for later use (binding).
				$sWhereSpecificArray[] =  $_GET['sSearch_'.$i];
				 
			}
		}
		 
		//If there is still no where clause - set a general - always true where clause
		if ( $sWhere == "" )
		{
			$sWhere = " AND 1=1";
		}
		 
		//Bind variables.
		if ( isset( $_GET['iDisplayStart'] ))
		{
			$dsplyStart = $_GET['iDisplayStart'];
		}
		else{
			$dsplyStart = 0;
		}
		if ( isset( $_GET['iDisplayLength'] ) && $_GET['iDisplayLength'] != '-1' )
		{
			$dsplyRange = $_GET['iDisplayLength'];
			if ($dsplyRange > (2147483645 - intval($dsplyStart)))
			{
				$dsplyRange = 2147483645;
			}
			else

			{
				$dsplyRange = intval($dsplyRange);
			}
		}
		else
		{
			$dsplyRange = 2147483645;
		}
		
		$statement_privacy = " AND A.PEGAWAI_ID = '".$this->ID."' ";
		
		$statement= " AND (UPPER(NOMOR) LIKE '%".strtoupper($_GET['sSearch'])."%'  OR UPPER(D.NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%' OR UPPER(E.NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%' OR UPPER(B.NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%' )";
		
		$allRecord = $permohonan_cuti_tahunan->getCountByParams(array(), $statement_privacy.$statement);
		//echo $allRecord;
		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter =  $permohonan_cuti_tahunan->getCountByParams(array(), $statement_privacy.$statement);
		
		$permohonan_cuti_tahunan->selectByParams(array(), $dsplyRange, $dsplyStart, $statement_privacy.$statement, $sOrder);
		
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $allRecord,
			"iTotalDisplayRecords" => $allRecordFilter,
			"aaData" => array()
		);
		
		while($permohonan_cuti_tahunan->nextRow())		
		{		
			$row = array();		
			for ( $i=0 ; $i<count($aColumns) ; $i++ )		
			{		
				$row[] = $permohonan_cuti_tahunan->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		
		echo json_encode( $output );	

		
	}
	
	function json_nomor_permohonan() 
	{
		$this->load->model('PermohonanCutiTahunan');
		$permohonan_cuti_tahunan = new PermohonanCutiTahunan;
																													
		$aColumns = array("PERMOHONAN_CUTI_TAHUNAN_ID", "NOMOR", "TANGGAL", "LAMA_CUTI", "TANGGAL_AWAL", "TANGGAL_AKHIR");
		$aColumnsAlias = array("PERMOHONAN_CUTI_TAHUNAN_ID", "NOMOR", "TANGGAL", "LAMA_CUTI", "TANGGAL_AWAL", "TANGGAL_AKHIR");
		
		/*
		 * Ordering
		 */
		if ( isset( $_GET['iSortCol_0'] ) )
		{
			$sOrder = " ORDER BY ";
			 
			//Go over all sorting cols
			for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
			{
				//If need to sort by current col
				if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
				{
					//Add to the order by clause
					$sOrder .= $aColumnsAlias[ intval( $_GET['iSortCol_'.$i] ) ];
					 
					//Determine if it is sorted asc or desc
					if (strcasecmp(( $_GET['sSortDir_'.$i] ), "asc") == 0)
					{
						$sOrder .=" asc, ";
					}else
					{
						$sOrder .=" desc, ";
					}
				}
			}
			
			//Remove the last space / comma
			$sOrder = substr_replace( $sOrder, "", -2 );
			
			//Check if there is an order by clause
			if ( trim($sOrder) == "ORDER BY PERMOHONAN_CUTI_TAHUNAN_ID asc" )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY PERMOHONAN_CUTI_TAHUNAN_ID DESC";
				 
			}
		}
		 
		/*
		 * Filtering
		 * NOTE this does not match the built-in DataTables filtering which does it
		 * word by word on any field. It's possible to do here, but concerned about efficiency
		 * on very large tables.
		 */
		$sWhere = "";
		$nWhereGenearalCount = 0;
		if (isset($_GET['sSearch']))
		{
			$sWhereGenearal = $_GET['sSearch'];
		}
		else
		{
			$sWhereGenearal = '';
		}
		
		if ( $_GET['sSearch'] != "" )
		{
			//Set a default where clause in order for the where clause not to fail
			//in cases where there are no searchable cols at all.
			$sWhere = " AND (";
			for ( $i=0 ; $i<count($aColumnsAlias)+1 ; $i++ )
			{
				//If current col has a search param
				if ( $_GET['bSearchable_'.$i] == "true" )
				{
					//Add the search to the where clause
					$sWhere .= $aColumnsAlias[$i]." LIKE '%".$_GET['sSearch']."%' OR ";
					$nWhereGenearalCount += 1;
				}
			}
			$sWhere = substr_replace( $sWhere, "", -3 );
			$sWhere .= ')';
		}
		 
		/* Individual column filtering */
		$sWhereSpecificArray = array();
		$sWhereSpecificArrayCount = 0;
		for ( $i=0 ; $i<count($aColumnsAlias) ; $i++ )
		{
			if ( $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
			{
				//If there was no where clause
				if ( $sWhere == "" )
				{
					$sWhere = "AND ";
				}
				else
				{
					$sWhere .= " AND ";
				}
				 
				//Add the clause of the specific col to the where clause
				$sWhere .= $aColumnsAlias[$i]." LIKE '%' || :whereSpecificParam".$sWhereSpecificArrayCount." || '%' ";
				 
				//Inc sWhereSpecificArrayCount. It is needed for the bind var.
				//We could just do count($sWhereSpecificArray) - but that would be less efficient.
				$sWhereSpecificArrayCount++;
				 
				//Add current search param to the array for later use (binding).
				$sWhereSpecificArray[] =  $_GET['sSearch_'.$i];
				 
			}
		}
		 
		//If there is still no where clause - set a general - always true where clause
		if ( $sWhere == "" )
		{
			$sWhere = " AND 1=1";
		}
		 
		//Bind variables.
		if ( isset( $_GET['iDisplayStart'] ))
		{
			$dsplyStart = $_GET['iDisplayStart'];
		}
		else{
			$dsplyStart = 0;
		}
		if ( isset( $_GET['iDisplayLength'] ) && $_GET['iDisplayLength'] != '-1' )
		{
			$dsplyRange = $_GET['iDisplayLength'];
			if ($dsplyRange > (2147483645 - intval($dsplyStart)))
			{
				$dsplyRange = 2147483645;
			}
			else

			{
				$dsplyRange = intval($dsplyRange);
			}
		}
		else
		{
			$dsplyRange = 2147483645;
		}
		
		$statement_privacy = " AND A.PEGAWAI_ID = '".$this->ID."' AND APPROVAL1 = 'Y' AND APPROVAL2 = 'Y' AND NOT EXISTS(SELECT 1 FROM PERMOHONAN_CUTI_REVISI X WHERE X.PERMOHONAN_CUTI_TAHUNAN_ID = A.PERMOHONAN_CUTI_TAHUNAN_ID AND (NVL(X.APPROVAL1, 'X') IN ('X', 'Y') OR NVL(X.APPROVAL2, 'X') IN ('X', 'Y') )) ";
		
		$statement= " AND (UPPER(NOMOR) LIKE '%".strtoupper($_GET['sSearch'])."%')";
		
		$allRecord = $permohonan_cuti_tahunan->getCountByParams(array(), $statement_privacy);
		//echo $allRecord;
		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter =  $permohonan_cuti_tahunan->getCountByParams(array(), $statement_privacy.$statement);
		
		$permohonan_cuti_tahunan->selectByParams(array(), $dsplyRange, $dsplyStart, $statement_privacy.$statement, $sOrder);
		
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $allRecord,
			"iTotalDisplayRecords" => $allRecordFilter,
			"aaData" => array()
		);
		
		while($permohonan_cuti_tahunan->nextRow())		
		{		
			$row = array();		
			for ( $i=0 ; $i<count($aColumns) ; $i++ )		
			{		
				$row[] = $permohonan_cuti_tahunan->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		
		echo json_encode( $output );	

		
	}
	
	function add() 
	{
		$this->load->model('PermohonanCutiTahunan');
		$this->load->model('NoSurat');
        $this->load->model('UserLoginLog');
		$this->load->library('PushNotification'); $pushNotification = new PushNotification();
		$permohonan_cuti_tahunan = new PermohonanCutiTahunan();
		$permohonan_cuti_tahunan_count = new PermohonanCutiTahunan();
		$no_surat = new NoSurat();
		$nomor = new NoSurat();
        $user_login_log_notif = new UserLoginLog();
        $user_login_log_notif_count = new UserLoginLog();
		
		$reqPeriode = date('Y');
		$no_surat->selectByParams(array("PERIODE"=>$reqPeriode, "KODE"=>"CT"), -1, -1 );
		$nomor->selectByParamsNomor(array("PERIODE"=>$reqPeriode, "KODE"=>"CT", "CABANG_ID" => $this->KODE_CABANG));
		$no_surat->firstRow();
		$nomor->firstRow();
		
		$reqKode = $no_surat->getField("KODE");
		$reqAwalan = $no_surat->getField("AWALAN");
		$reqNo = $nomor->getField("NOMOR");
		$reqNomor = "".$reqKode."/".$reqAwalan.$this->KODE_CABANG."/".$reqPeriode."/".$reqNo;
		
		$reqId = $this->input->post('reqId');
		$reqMode = $this->input->post('reqMode');
		
		//$reqNomor = $this->input->post('reqNomor');
		$reqTanggal = $this->input->post('reqTanggal');
		$reqJumlahHari = $this->input->post('reqJumlahHari');
		$reqTanggalAwal = $this->input->post('reqTanggalAwal');
		$reqTanggalAkhir = $this->input->post('reqTanggalAkhir');
		$reqKeterangan = $this->input->post('reqKeterangan');
		$reqAlamat = $this->input->post('reqAlamat');
		$reqTelepon = $this->input->post('reqTelepon');
		$reqPegawaiIdApproval1 = $this->input->post('reqPegawaiIdApproval1');
		$reqPegawaiIdApproval2 = $this->input->post('reqPegawaiIdApproval2');
		$reqApproval = $this->input->post('reqApproval');
		$reqTahun = $this->input->post('reqTahun');
		

		if(getMonth($reqTanggalAwal) == "03" && getMonth($reqTanggalAkhir) == "04")
		{
			echo "F-Anda tidak di ijinkan untuk mengambil cuti tahunan lintas bulan antara bulan maret dan bulan april";
			return false;
		}
		elseif(getMonth($reqTanggalAwal) == "12" && getMonth($reqTanggalAkhir) == "01")
		{
			echo "F-Anda tidak di ijinkan untuk mengambil cuti tahunan lintas tahun antara bulan desember dan bulan januari";
			return false;	
		}
		
		// elseif(getMonth($reqTanggalAwal) >= "04" && getMonth($reqTanggalAwal) <= "12" && getYear(dateToDB($reqTanggalAwal)) == $this->TAHUN_CUTI)
		// {
		// 	$reqTahun = $this->TAHUN_CUTI;
		// }
		// else
		// {
		// 	$reqTahun = $reqTahun;
		// }

		// elseif(date("n") <= 3)
		// 	$reqTahun = $this->TAHUN_CUTI-1;
		// else
		// 	$reqTahun = $reqTahun;

		$permohonan_cuti_tahunan->setField("PERMOHONAN_CUTI_TAHUNAN_ID", $reqId);
		$permohonan_cuti_tahunan->setField("PEGAWAI_ID", $this->ID);
		$permohonan_cuti_tahunan->setField("TAHUN", $reqTahun);
		$permohonan_cuti_tahunan->setField("NOMOR", $reqNomor);
		$permohonan_cuti_tahunan->setField("TANGGAL", dateToDBCheck($reqTanggal));
		$permohonan_cuti_tahunan->setField("JABATAN", $this->JABATAN);
		$permohonan_cuti_tahunan->setField("CABANG_ID", $this->KODE_CABANG);
		$permohonan_cuti_tahunan->setField("CABANG", $this->CABANG);
		$permohonan_cuti_tahunan->setField("DEPARTEMEN", $this->DEPARTEMEN);
		$permohonan_cuti_tahunan->setField("SUB_DEPARTEMEN", $this->SUB_DEPARTEMEN);
		$permohonan_cuti_tahunan->setField("TANGGAL_AWAL", dateToDBCheck($reqTanggalAwal));
		$permohonan_cuti_tahunan->setField("TANGGAL_AKHIR", dateToDBCheck($reqTanggalAkhir));
		$permohonan_cuti_tahunan->setField("LAMA_CUTI", $reqJumlahHari);
		$permohonan_cuti_tahunan->setField("KETERANGAN", $reqKeterangan);
		$permohonan_cuti_tahunan->setField("ALAMAT", $reqAlamat);
		$permohonan_cuti_tahunan->setField("TELEPON", $reqTelepon);
		$permohonan_cuti_tahunan->setField("PEGAWAI_ID_APPROVAL1", $reqPegawaiIdApproval1);
		$permohonan_cuti_tahunan->setField("PEGAWAI_ID_APPROVAL2", $reqPegawaiIdApproval2);

		/* JIKA PEGAWAI APPROVAL1 TIDAK DIISI PASTI JABATANNYA BUKAN STAFF BIASA :) JADI LANGSUNG BYPASS 'Y' */
		if($reqPegawaiIdApproval1 == "")
			$permohonan_cuti_tahunan->setField("APPROVAL1", "Y");

		$statement = "  AND (
							(".dateToDBCheck($reqTanggalAwal)." BETWEEN A.TANGGAL_AWAL AND A.TANGGAL_AKHIR) OR 
							(".dateToDBCheck($reqTanggalAkhir)." BETWEEN A.TANGGAL_AWAL AND A.TANGGAL_AKHIR) OR
							(A.TANGGAL_AWAL BETWEEN ".dateToDBCheck($reqTanggalAwal)." AND ".dateToDBCheck($reqTanggalAkhir).") OR 
							(A.TANGGAL_AKHIR BETWEEN ".dateToDBCheck($reqTanggalAwal)." AND ".dateToDBCheck($reqTanggalAkhir).")
							) 
							AND (NVL(A.APPROVAL1, 'X') IN ('X','Y') OR NVL(A.APPROVAL2, 'X') IN ('X','Y')) AND NOT EXISTS( SELECT 1 FROM PERMOHONAN_CUTI_REVISI X WHERE X.PERMOHONAN_CUTI_TAHUNAN_ID = A.PERMOHONAN_CUTI_TAHUNAN_ID ) ";
		$jumlah = $permohonan_cuti_tahunan_count->getCountByParams(array("A.PEGAWAI_ID" => $this->ID), $statement);
		
		if($jumlah > 0)
		{
			echo "F-Anda sudah megajukan permohonan cuti tahunan antara tanggal ".$reqTanggalAwal." sampai tanggal ".$reqTanggalAkhir." ";
			return;
		}
		
		
		if($reqMode == "insert"){
			$permohonan_cuti_tahunan->setField("LAST_CREATE_DATE", "SYSDATE");
			$permohonan_cuti_tahunan->setField("LAST_CREATE_USER", $this->USERNAME);
			$permohonan_cuti_tahunan->insert();
			
			$no_surat->setField("KODE", "CT");
			$no_surat->setField("PERIODE", $reqPeriode);
			$no_surat->setField("CABANG_ID", $this->KODE_CABANG);
			$no_surat->updateByField();
			unset($no_surat);

			$reqRowId = $permohonan_cuti_tahunan->id;

			if($reqPegawaiIdApproval1 == "")
			{}
			else
			{
				$jumlah = $user_login_log_notif_count->getCountByParams(array("A.PEGAWAI_ID" => $reqPegawaiIdApproval1));
				if($jumlah > 0)
				{
					$user_login_log_notif->selectByParams(array("A.PEGAWAI_ID" => $reqPegawaiIdApproval1, "A.STATUS" => 1));
					while ($user_login_log_notif->nextRow()) {
						$reqDeviceId = $user_login_log_notif->getField("DEVICE_ID");
						$reqType = "PERMOHONAN";
						$reqId = $reqRowId;
						$reqJenis = "PERMOHONAN_CUTI_TAHUNAN";
						$reqTitle = "Permohonan Cuti Tahunan";
						$reqBody = $this->NAMA." Mengajukan Permohonan Cuti Tahunan." ;
						$pushNotification->send_notification($reqDeviceId, $reqType, $reqId, $reqJenis, $reqTitle, $reqBody);
					}
				}
			}


			/* APPROVAL BERTINGKAT JADI BARU DIKIRIM JIKA APPROVAL 1 TIDAK ADA DATANYA */
			if($reqPegawaiIdApproval1 == "")
			{			
				$jumlah = $user_login_log_notif_count->getCountByParams(array("A.PEGAWAI_ID" => $reqPegawaiIdApproval2));
				if($jumlah > 0)
				{
					$user_login_log_notif->selectByParams(array("A.PEGAWAI_ID" => $reqPegawaiIdApproval2, "A.STATUS" => 1));
					while ($user_login_log_notif->nextRow()) {
						$reqDeviceId = $user_login_log_notif->getField("DEVICE_ID");
						$reqType = "PERMOHONAN";
						$reqId = $reqRowId;
						$reqJenis = "PERMOHONAN_CUTI_TAHUNAN";
						$reqTitle = "Permohonan Cuti Tahunan";
						$reqBody = $this->NAMA." Mengajukan Permohonan Cuti Tahunan." ;
						$pushNotification->send_notification($reqDeviceId, $reqType, $reqId, $reqJenis, $reqTitle, $reqBody);
					}
				}
			}
			
		}else{
			$permohonan_cuti_tahunan->setField("LAST_UPDATE_DATE", "SYSDATE");
			$permohonan_cuti_tahunan->setField("LAST_UPDATE_USER", $this->USERNAME);
			$permohonan_cuti_tahunan->update();
		}
		echo "T-Data berhasil disimpan.";
	}	
	
	function delete()
	{
		$this->load->model('PermohonanCutiTahunan');
		$permohonan_cuti_tahunan = new PermohonanCutiTahunan;
		
		$reqId = $this->input->get("reqId");
		$permohonan_cuti_tahunan->setField("PERMOHONAN_CUTI_TAHUNAN_ID", $reqId);
		
		if($permohonan_cuti_tahunan->delete())
			$arrJson["PESAN"] = "Data berhasil dihapus.";
		else
			$arrJson["PESAN"] = "Data gagal dihapus.";		
		
		echo json_encode($arrJson);
	}
	
	function approval()
	{
		$this->load->model('PermohonanCutiTahunan');
		$permohonan_cuti_tahunan = new PermohonanCutiTahunan;
		
		$reqId = $this->input->post('reqId');
		$reqMode = $this->input->post('reqMode');
		
		$reqApprovalKe = $this->input->post('reqApprovalKe');
		$reqAlasan = $this->input->post('reqAlasan');
		$reqAlasanTolak = $this->input->post('reqAlasanTolak');
		
		$permohonan_cuti_tahunan->setField("PERMOHONAN_CUTI_TAHUNAN_ID", $reqId);
		$permohonan_cuti_tahunan->setField("APPROVAL", $reqAlasan);
		$permohonan_cuti_tahunan->setField("ALASAN_TOLAK", $reqAlasanTolak);
		
		
		if($reqMode == "update")
		{
			$permohonan_cuti_tahunan->setField("LAST_UPDATE_DATE", "SYSDATE");
			$permohonan_cuti_tahunan->setField("LAST_UPDATE_USER", $this->USERNAME);
			if($reqApprovalKe == "1"){
				$permohonan_cuti_tahunan->setField("APPROVAL_TANGGAL1", "SYSDATE");
				$permohonan_cuti_tahunan->approval1();
				
				if($reqAlasan == "T")
				{
					/* JIKA APPROVAL 1 MENOLAK MAKA APPROVAL YANG KE 2 JUGA MENOLAK */
					$permohonan_cuti_tahunan->setField("PERMOHONAN_CUTI_TAHUNAN_ID", $reqId);		
					$permohonan_cuti_tahunan->setField("LAST_UPDATE_DATE", "SYSDATE");
					$permohonan_cuti_tahunan->setField("LAST_UPDATE_USER", $this->USERNAME);
					$permohonan_cuti_tahunan->setField("APPROVAL_TANGGAL2", "SYSDATE");
					$permohonan_cuti_tahunan->approval2();	
				}
				
			}elseif($reqApprovalKe == "2"){
				$permohonan_cuti_tahunan->setField("APPROVAL_TANGGAL2", "SYSDATE");
				$permohonan_cuti_tahunan->approval2();
				
				/* JIKA APPROVAL 2 SUDAH OKE MAKA APPROVAL YANG KE 1 JUGA OKE BEGITU SEBALIKNYA 
				$permohonan_cuti_tahunan->setField("PERMOHONAN_CUTI_TAHUNAN_ID", $reqId);
				$permohonan_cuti_tahunan->setField("APPROVAL", $reqAlasan);
				$permohonan_cuti_tahunan->setField("ALASAN_TOLAK", $reqAlasanTolak);								
				$permohonan_cuti_tahunan->setField("LAST_UPDATE_DATE", "SYSDATE");
				$permohonan_cuti_tahunan->setField("LAST_UPDATE_USER", $this->USERNAME);
				$permohonan_cuti_tahunan->setField("APPROVAL_TANGGAL1", "SYSDATE");
				$permohonan_cuti_tahunan->approval1();		*/		
				
			}
		}
		echo "Data berhasil disimpan.";
	}	

}

