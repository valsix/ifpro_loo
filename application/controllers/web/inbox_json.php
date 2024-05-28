<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class inbox_json extends CI_Controller {

	function __construct() {
		parent::__construct();
		
		if (!$this->kauth->getInstance()->hasIdentity())
		{
			redirect('login');
		}    
		
		$this->db->query("SET DATESTYLE TO PostgreSQL,European;"); 
		$this->ID				= $this->kauth->getInstance()->getIdentity()->ID;   
		$this->NAMA				= $this->kauth->getInstance()->getIdentity()->NAMA;   
		$this->JABATAN			= $this->kauth->getInstance()->getIdentity()->JABATAN;   
		$this->HAK_AKSES		= $this->kauth->getInstance()->getIdentity()->HAK_AKSES;   
		$this->LAST_LOGIN		= $this->kauth->getInstance()->getIdentity()->LAST_LOGIN;   
		$this->USERNAME			= $this->kauth->getInstance()->getIdentity()->ID;  
		$this->USER_LOGIN_ID	= $this->kauth->getInstance()->getIdentity()->USER_LOGIN_ID;  
		$this->USER_GROUP		= $this->kauth->getInstance()->getIdentity()->USER_GROUP;  
		$this->CABANG_ID		= $this->kauth->getInstance()->getIdentity()->CABANG_ID;  
		$this->CABANG			= $this->kauth->getInstance()->getIdentity()->CABANG;  
		$this->SATUAN_KERJA_ID_ASAL	= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_ID_ASAL;  
		$this->SATUAN_KERJA_ASAL	= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_ASAL;  
		
		
	}
	
	function json() 
	{
		$this->load->model("SuratMasuk");
		$surat_masuk = new SuratMasuk();

		$reqJenisTujuan = $this->input->get("reqJenisTujuan");
		// echo $reqKategori;exit;
		
		$aColumns = array("SURAT_MASUK_ID", "NOMOR", "TANGGAL_ENTRI", 
							"TANGGAL",  "JENIS_TUJUAN",
							"PERIHAL", "JENIS_NASKAH", "SIFAT_NASKAH", 
							"INSTANSI_ASAL", "TERBACA", "TERDISPOSISI", "TERBALAS");
		$aColumnsAlias = array("A.SURAT_MASUK_ID", "A.NOMOR", "A.TANGGAL_ENTRI", 
							"A.TANGGAL",  "A.JENIS_TUJUAN",
							"A.PERIHAL", "A.JENIS_NASKAH_ID", "A.SIFAT_NASKAH", 
							"A.INSTANSI_ASAL", "B.TERBALAS", "B.TERDISPOSISI", "B.TERBACA");

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
			if ( trim($sOrder) == "ORDER BY A.SURAT_MASUK_ID asc" )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.TANGGAL_ENTRI DESC";
				 
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

		$statement_privacy .= " AND A.STATUS_SURAT = 'POSTING' ";
		$statement_privacy .= " AND B.SATUAN_KERJA_ID_TUJUAN = '".$this->SATUAN_KERJA_ID_ASAL."' ";
		
		$statement= " AND (
						UPPER(A.NO_AGENDA) LIKE '%".strtoupper($_GET['sSearch'])."%' OR 
						UPPER(A.NOMOR) LIKE '%".strtoupper($_GET['sSearch'])."%' OR
						UPPER(A.PERIHAL) LIKE '%".strtoupper($_GET['sSearch'])."%' OR 
						UPPER(A.INSTANSI_ASAL) LIKE '%".strtoupper($_GET['sSearch'])."%' 
						) ";
						
		$allRecord = $surat_masuk->getCountByParamsInbox(array(), $statement_privacy.$statement);
		// echo $allRecord;exit;
		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter =  $surat_masuk->getCountByParamsInbox(array(), $statement_privacy.$statement);
		
		 $surat_masuk->selectByParamsInbox(array(), $dsplyRange, $dsplyStart, $statement_privacy.$statement, $sOrder);
		
		// echo "IKI ".$_GET['iDisplayStart'];
		
			/*
			 * Output 
			 */
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $allRecord,
			"iTotalDisplayRecords" => $allRecordFilter,
			"aaData" => array()
		);
		
		while($surat_masuk->nextRow())		
		{		
			$strongFirst = "";
			$strongLast  = "";	
			if($surat_masuk->getField("TERBACA") == "")
			{
				$strongFirst = "<strong>";
				$strongLast  = "</strong>";	
			}

			$row = array();		
			for ( $i=0 ; $i<count($aColumns) ; $i++ )		
			{
				if($aColumns[$i] == "KETERANGAN")
					$row[] = truncate($surat_masuk->getField($aColumns[$i]), 2);
				elseif($i==0)
					$row[] = $surat_masuk->getField($aColumns[$i]);
				elseif($aColumns[$i] == "TERBACA" || $aColumns[$i] == "TERDISPOSISI" || $aColumns[$i] == "TERBALAS")
				{
					if((int)$surat_masuk->getField($aColumns[$i]) > 0)
						$row[] = "<i class=\"fa fa-check fa-md\" aria-hidden=\"true\"></i>";
					else
						$row[] = "<i class=\"fa fa-close fa-md\" aria-hidden=\"true\"></i>";
				}					
				else
					$row[] = $strongFirst.$surat_masuk->getField($aColumns[$i]).$strongLast;
			}
			$output['aaData'][] = $row;
		}
		echo json_encode( $output );	

		
	}
	
	
	function json_draft() 
	{
		$this->load->model("SuratMasuk");
		$surat_masuk = new SuratMasuk();

		$reqJenisTujuan = $this->input->get("reqJenisTujuan");
		// echo $reqKategori;exit;
		
		$aColumns = array("SURAT_MASUK_ID", "LINK_VALIDASI", "NOMOR", "TANGGAL_ENTRI", 
							"TANGGAL",  "JENIS_TUJUAN",
							"PERIHAL", "JENIS_NASKAH", "SIFAT_NASKAH", 
							"NAMA_USER");
		$aColumnsAlias = array("A.SURAT_MASUK_ID", "LINK_VALIDASI", "A.NOMOR", "A.TANGGAL_ENTRI", 
							"A.TANGGAL",  "A.JENIS_TUJUAN",
							"A.PERIHAL", "A.JENIS_NASKAH_ID", "A.SIFAT_NASKAH", 
							"A.NAMA_USER");

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
			if ( trim($sOrder) == "ORDER BY A.SURAT_MASUK_ID asc" )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.TANGGAL_ENTRI DESC";
				 
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

		$statement_privacy .= " AND A.STATUS_SURAT IN ('VALIDASI', 'PARAF') ";
		$statement_privacy .= " AND (
									A.USER_ATASAN_ID = '".$this->ID."' OR EXISTS(SELECT 1 FROM SURAT_MASUK_PARAF X WHERE X.SURAT_MASUK_ID = A.SURAT_MASUK_ID 
																					AND X.USER_ID = '".$this->ID."' AND COALESCE(NULLIF(X.STATUS_PARAF, ''), 'X') = 'X' 
																				 )
								) ";
		
		$statement= " AND (
						UPPER(A.NO_AGENDA) LIKE '%".strtoupper($_GET['sSearch'])."%' OR 
						UPPER(A.NOMOR) LIKE '%".strtoupper($_GET['sSearch'])."%' OR
						UPPER(A.PERIHAL) LIKE '%".strtoupper($_GET['sSearch'])."%' OR 
						UPPER(A.INSTANSI_ASAL) LIKE '%".strtoupper($_GET['sSearch'])."%' 
						) ";
						
		$allRecord = $surat_masuk->getCountByParamsMonitoring(array(), $statement_privacy.$statement);
		// echo $allRecord;exit;
		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter =  $surat_masuk->getCountByParamsMonitoring(array(), $statement_privacy.$statement);
		
		 $surat_masuk->selectByParamsDraft(array(), $dsplyRange, $dsplyStart, $statement_privacy.$statement, $sOrder);
		
		// echo "IKI ".$_GET['iDisplayStart'];
		
			/*
			 * Output 
			 */
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $allRecord,
			"iTotalDisplayRecords" => $allRecordFilter,
			"aaData" => array()
		);
		
		while($surat_masuk->nextRow())		
		{		
			$strongFirst = "";
			$strongLast  = "";	
			if($surat_masuk->getField("TERTANDA_TANGANI") == "")
			{
				$strongFirst = "<strong>";
				$strongLast  = "</strong>";	
			}

			$row = array();		
			for ( $i=0 ; $i<count($aColumns) ; $i++ )		
			{
				if($aColumns[$i] == "KETERANGAN")
					$row[] = truncate($surat_masuk->getField($aColumns[$i]), 2);
				elseif($i==0 || $i==1)
					$row[] = $surat_masuk->getField($aColumns[$i]);
				elseif($aColumns[$i] == "TERBACA" || $aColumns[$i] == "TERDISPOSISI" || $aColumns[$i] == "TERBALAS")
				{
					if((int)$surat_masuk->getField($aColumns[$i]) > 0)
						$row[] = "<i class=\"fa fa-check fa-md\" aria-hidden=\"true\"></i>";
					else
						$row[] = "<i class=\"fa fa-close fa-md\" aria-hidden=\"true\"></i>";
				}					
				else
					$row[] = $strongFirst.$surat_masuk->getField($aColumns[$i]).$strongLast;
			}
			$output['aaData'][] = $row;
		}
		echo json_encode( $output );	

		
	}
	
	
	
	function json_sent() 
	{
		$this->load->model("SuratMasuk");
		$surat_masuk = new SuratMasuk();

		$reqJenisTujuan = $this->input->get("reqJenisTujuan");
		// echo $reqKategori;exit;
		
		$aColumns = array("SURAT_MASUK_ID", "LINK_VALIDASI", "NOMOR", "TANGGAL_ENTRI", 
							"TANGGAL",  "JENIS_TUJUAN",
							"PERIHAL", "JENIS_NASKAH", "SIFAT_NASKAH", 
							"NAMA_USER");
		$aColumnsAlias = array("A.SURAT_MASUK_ID", "LINK_VALIDASI", "A.NOMOR", "A.TANGGAL_ENTRI", 
							"A.TANGGAL",  "A.JENIS_TUJUAN",
							"A.PERIHAL", "A.JENIS_NASKAH_ID", "A.SIFAT_NASKAH", 
							"A.NAMA_USER");

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
			if ( trim($sOrder) == "ORDER BY A.SURAT_MASUK_ID asc" )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.TANGGAL_ENTRI DESC";
				 
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

		$statement_privacy .= " AND A.STATUS_SURAT IN ('PARAF', 'POSTING') ";
		$statement_privacy .= " AND (
									A.USER_ATASAN_ID = '".$this->ID."' OR EXISTS(SELECT 1 FROM SURAT_MASUK_PARAF X WHERE X.SURAT_MASUK_ID = A.SURAT_MASUK_ID 
																					AND X.USER_ID = '".$this->ID."' AND NOT COALESCE(NULLIF(X.STATUS_PARAF, ''), 'X') = 'X' 
																				 )
								) ";
		
		$statement= " AND (
						UPPER(A.NO_AGENDA) LIKE '%".strtoupper($_GET['sSearch'])."%' OR 
						UPPER(A.NOMOR) LIKE '%".strtoupper($_GET['sSearch'])."%' OR
						UPPER(A.PERIHAL) LIKE '%".strtoupper($_GET['sSearch'])."%' OR 
						UPPER(A.INSTANSI_ASAL) LIKE '%".strtoupper($_GET['sSearch'])."%' 
						) ";
						
		$allRecord = $surat_masuk->getCountByParamsMonitoring(array(), $statement_privacy.$statement);
		// echo $allRecord;exit;
		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter =  $surat_masuk->getCountByParamsMonitoring(array(), $statement_privacy.$statement);
		
		 $surat_masuk->selectByParamsSent(array(), $dsplyRange, $dsplyStart, $statement_privacy.$statement, $sOrder);
		
		// echo "IKI ".$_GET['iDisplayStart'];
		
			/*
			 * Output 
			 */
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $allRecord,
			"iTotalDisplayRecords" => $allRecordFilter,
			"aaData" => array()
		);
		
		while($surat_masuk->nextRow())		
		{		
			$strongFirst = "";
			$strongLast  = "";	
			if($surat_masuk->getField("TERTANDA_TANGANI") == "")
			{
				$strongFirst = "<strong>";
				$strongLast  = "</strong>";	
			}

			$row = array();		
			for ( $i=0 ; $i<count($aColumns) ; $i++ )		
			{
				if($aColumns[$i] == "KETERANGAN")
					$row[] = truncate($surat_masuk->getField($aColumns[$i]), 2);
				elseif($i==0 || $i==1)
					$row[] = $surat_masuk->getField($aColumns[$i]);
				elseif($aColumns[$i] == "TERBACA" || $aColumns[$i] == "TERDISPOSISI" || $aColumns[$i] == "TERBALAS")
				{
					if((int)$surat_masuk->getField($aColumns[$i]) > 0)
						$row[] = "<i class=\"fa fa-check fa-md\" aria-hidden=\"true\"></i>";
					else
						$row[] = "<i class=\"fa fa-close fa-md\" aria-hidden=\"true\"></i>";
				}					
				else
					$row[] = $strongFirst.$surat_masuk->getField($aColumns[$i]).$strongLast;
			}
			$output['aaData'][] = $row;
		}
		echo json_encode( $output );	

		
	}
	
	function add() 
	{
		$this->load->model("SuratMasuk");
		$this->load->model("Disposisi");
		$surat_masuk = new SuratMasuk();
		$disposisi = new Disposisi();

		$reqMode 					= $this->input->post("reqMode");
		$reqId 						= $this->input->post("reqId");
		$reqDisposisiId 			= $this->input->post("reqDisposisiId");
		$reqInfoSatuankerjaTujuanId= $this->input->post("reqInfoSatuankerjaTujuanId");

		$reqSatuanKerjaIdTujuan		=  $this->input->post("reqSatuanKerjaIdTujuan");
		$reqSatuanKerjaIdTembusan	=  $this->input->post("reqSatuanKerjaIdTembusan");	
		$reqBalasCepatId   			=  $this->input->post("reqBalasCepatId");	
		$reqBalasCepat   			=  $this->input->post("reqBalasCepat");	
		$reqKeterangan   			=  $this->input->post("reqKeterangan");	
		$reqSifatNaskah 			=  $this->input->post("reqSifatNaskah");	
		$reqSifatNaskahNama 		=  $this->input->post("reqSifatNaskahNama");
		// echo $this->input->post("p1");
		// echo $reqSatuanKerjaIdTujuan;exit;
		// print_r($reqSatuanKerjaIdTujuan);exit;

		if(count($reqSatuanKerjaIdTujuan) == 0 || $reqSatuanKerjaIdTujuan[0] == "")
		{
			echo "X-Isi terlebih dahulu tujuan disposisi.";	
			return;
		}
		if($reqBalasCepat == "")
		{
			echo "X-Isi terlebih dahulu pesan disposisi.";	
			return;
		}

		for($i=0;$i<count($reqSatuanKerjaIdTujuan);$i++)
		{
			if($reqSatuanKerjaIdTujuan[$i] == "")
			{}
			else
			{
				/*$check= new Disposisi();
				$checkdata= $check->getCountByParams(array(), " AND SURAT_MASUK_ID = ".$reqId." AND STATUS_DISPOSISI IN ('TUJUAN', 'DISPOSISI') AND SATUAN_KERJA_ID_TUJUAN = '".$reqSatuanKerjaIdTujuan[$i]."'");

				if($checkdata > 0)
				{
					echo "X-gagal disimpan, karena ada data pernah disposisi diteruskan kepada tujuan.";
					return;
				}*/
			}
		}

		/* UPDATE STATUS TERDISPOSISI */
		$disposisi->setField("FIELD", "TERDISPOSISI");
		$disposisi->setField("FIELD_VALUE", "1");
		$disposisi->setField("LAST_UPDATE_USER", $this->ID);
		$disposisi->setField("DISPOSISI_ID", $reqDisposisiId);
		$disposisi->updateByField();
		
		/* WAJIB UNTUK UPLOAD DATA */
		$this->load->library("FileHandler");
		$file = new FileHandler();
		$FILE_DIR= "uploads/".$reqId."/";
		makedirs($FILE_DIR);

		
		$reqLinkFile = $_FILES["reqLinkFile"];
		$reqLinkFileTempSize	=  $this->input->post("reqLinkFileTempSize");
		$reqLinkFileTempTipe	=  $this->input->post("reqLinkFileTempTipe");
		$reqLinkFileTemp		=  $this->input->post("reqLinkFileTemp");

		$reqJenis = "DIS".generateZero($reqId, 5);
		for($i=0;$i<count($reqLinkFile);$i++)
		{
			$renameFile = $reqJenis.date("Ymdhis").rand().".".getExtension($reqLinkFile['name'][$i]);
		
			if($file->uploadToDirArray('reqLinkFile', $FILE_DIR, $renameFile, $i))
			{	
				$insertLinkSize = $file->uploadedSize;
				$insertLinkTipe =  $file->uploadedExtension;
				$insertLinkFile =  $renameFile;
				
				if($insertLinkFile == "")
				{}
				else
				{
					$surat_masuk_attachement = new Disposisi();
					$surat_masuk_attachement->setField("SURAT_MASUK_ID", $reqId);
					$surat_masuk_attachement->setField("DISPOSISI_ID", $reqDisposisiId);
					$surat_masuk_attachement->setField("ATTACHMENT", $renameFile);
					$surat_masuk_attachement->setField("UKURAN", $insertLinkSize);
					$surat_masuk_attachement->setField("TIPE", $insertLinkTipe);
					$surat_masuk_attachement->setField("NAMA", $reqLinkFile['name'][$i]);
					$surat_masuk_attachement->setField("LAST_CREATE_USER", $this->ID);
					$surat_masuk_attachement->insertAttachment();
				}
			}
		}
		
		/*$reqBalasCepat = "";
		for($i=0;$i<count($reqBalasCepatId);$i++)
		{
			if($i==0)
				$reqBalasCepat .= $reqBalasCepatId[$i];
			else
				$reqBalasCepat .= ";".$reqBalasCepatId[$i];		
		}*/
		
		for($i=0;$i<count($reqSatuanKerjaIdTujuan);$i++)
		{
			if($reqSatuanKerjaIdTujuan[$i] == "")
			{}
			else
			{
				$disposisi = new Disposisi();
				$disposisi->setField("DISPOSISI_PARENT_ID", $reqDisposisiId);
				$disposisi->setField("SURAT_MASUK_ID", $reqId);
				$disposisi->setField("ISI", $reqBalasCepat);
				$disposisi->setField("KETERANGAN", setQuote($reqKeterangan, ""));
				if($reqInfoSatuankerjaTujuanId == $this->SATUAN_KERJA_ID_ASAL)
					$disposisi->setField("SATUAN_KERJA_ID_ASAL", $this->SATUAN_KERJA_ID_ASAL);
				else
					$disposisi->setField("SATUAN_KERJA_ID_ASAL", $reqInfoSatuankerjaTujuanId);
				
				$disposisi->setField("SATUAN_KERJA_ID_TUJUAN", $reqSatuanKerjaIdTujuan[$i]);
				$disposisi->setField("STATUS_DISPOSISI", "DISPOSISI");
				$disposisi->setField("SIFAT_NAMA", $reqSifatNaskahNama);
				$disposisi->setField("LAST_CREATE_USER", $this->ID);
				$disposisi->insert();
			}
		}
		
		for($i=0;$i<count($reqSatuanKerjaIdTembusan);$i++)
		{
			if($reqSatuanKerjaIdTembusan[$i] == "")
			{}
			else
			{
				$disposisi = new Disposisi();
				$disposisi->setField("DISPOSISI_PARENT_ID", $reqDisposisiId);
				$disposisi->setField("SURAT_MASUK_ID", $reqId);
				$disposisi->setField("ISI", $reqBalasCepat);
				$disposisi->setField("KETERANGAN", setQuote($reqKeterangan, ""));
				$disposisi->setField("SATUAN_KERJA_ID_ASAL", $this->SATUAN_KERJA_ID_ASAL);
				$disposisi->setField("SATUAN_KERJA_ID_TUJUAN", $reqSatuanKerjaIdTembusan[$i]);
				$disposisi->setField("STATUS_DISPOSISI", "DISPOSISI_TEMBUSAN");
				$disposisi->setField("SIFAT_NAMA", $reqSifatNaskahNama);
				$disposisi->setField("LAST_CREATE_USER", $this->ID);
				$disposisi->insert();
			}
		}
		
		
		$this->posting_proses("DISPOSISI", $reqId, $reqDisposisiId);
			
		echo "-Disposisi berhasil dikirim.";
	
	}
	
	
	function balas() 
	{
		$this->load->model("SuratMasuk");
		$this->load->model("Disposisi");
		$surat_masuk = new SuratMasuk();
		$disposisi = new Disposisi();

		$reqMode 					= $this->input->post("reqMode");
		$reqId 						= $this->input->post("reqId");
		$reqDisposisiId 			= $this->input->post("reqDisposisiId");

		$reqSatuanKerjaIdTujuan		=  $this->input->post("reqSatuanKerjaIdTujuan");
		$reqSatuanKerjaIdTembusan	=  $this->input->post("reqSatuanKerjaIdTembusan");	
		$reqBalasCepatId   	=  $this->input->post("reqBalasCepatId");	
		$reqBalasCepat   	=  $this->input->post("reqBalasCepat");	
		$reqKeterangan   	=  $this->input->post("reqKeterangan");	
		
		if(count($reqSatuanKerjaIdTujuan) == 0 || $reqSatuanKerjaIdTujuan[0] == "")
		{
			echo "X-Isi terlebih dahulu tujuan balasan.";	
			return;
		}

		$check= new Disposisi();
		$checkdata= $check->getCountByParams(array(), " AND DISPOSISI_PARENT_ID = ".$reqDisposisiId);

		if($checkdata > 0)
		{
			echo "X-gagal disimpan, karena ada data pernah terbalas.";
			return;
		}
		
		// if($reqBalasCepat == "")
		// {
		// 	echo "X-Isi terlebih dahulu pesan balasan.";	
		// 	return;
		// }
		
		/* UPDATE STATUS TERBALAS */
		$disposisi->setField("FIELD", "TERBALAS");
		$disposisi->setField("FIELD_VALUE", "1");
		$disposisi->setField("LAST_UPDATE_USER", $this->ID);
		$disposisi->setField("DISPOSISI_ID", $reqDisposisiId);
		$disposisi->updateByField();
		
		/* WAJIB UNTUK UPLOAD DATA */
		$this->load->library("FileHandler");
		$file = new FileHandler();
		$FILE_DIR= "uploads/".$reqId."/";
		makedirs($FILE_DIR);

		
		$reqLinkFile = $_FILES["reqLinkFile"];
		$reqLinkFileTempSize	=  $this->input->post("reqLinkFileTempSize");
		$reqLinkFileTempTipe	=  $this->input->post("reqLinkFileTempTipe");
		$reqLinkFileTemp		=  $this->input->post("reqLinkFileTemp");

		$reqJenis = "BLS".generateZero($reqId, 5);
		for($i=0;$i<count($reqLinkFile);$i++)
		{
			$renameFile = $reqJenis.date("Ymdhis").rand().".".getExtension($reqLinkFile['name'][$i]);
		
			if($file->uploadToDirArray('reqLinkFile', $FILE_DIR, $renameFile, $i))
			{	
				$insertLinkSize = $file->uploadedSize;
				$insertLinkTipe =  $file->uploadedExtension;
				$insertLinkFile =  $renameFile;
				
				if($insertLinkFile == "")
				{}
				else
				{
					$surat_masuk_attachement = new Disposisi();
					$surat_masuk_attachement->setField("SURAT_MASUK_ID", $reqId);
					$surat_masuk_attachement->setField("DISPOSISI_ID", $reqDisposisiId);
					$surat_masuk_attachement->setField("ATTACHMENT", $renameFile);
					$surat_masuk_attachement->setField("UKURAN", $insertLinkSize);
					$surat_masuk_attachement->setField("TIPE", $insertLinkTipe);
					$surat_masuk_attachement->setField("NAMA", $reqLinkFile['name'][$i]);
					$surat_masuk_attachement->setField("LAST_CREATE_USER", $this->ID);
					$surat_masuk_attachement->insertAttachment();
				}
			}
		}
		
		/*$reqBalasCepat = "";
		for($i=0;$i<count($reqBalasCepatId);$i++)
		{
			if($i==0)
				$reqBalasCepat .= $reqBalasCepatId[$i];
			else
				$reqBalasCepat .= ";".$reqBalasCepatId[$i];		
		}*/
		
		$disposisi = new Disposisi();
		$disposisi->setField("DISPOSISI_PARENT_ID", $reqDisposisiId);
		$disposisi->setField("SURAT_MASUK_ID", $reqId);
		$disposisi->setField("ISI", $reqBalasCepat);
		$disposisi->setField("KETERANGAN", setQuote($reqKeterangan, ""));
		if($this->KD_LEVEL_PEJABAT == "")
			$disposisi->setField("SATUAN_KERJA_ID_ASAL", "PEGAWAI".$this->ID);
		else
			$disposisi->setField("SATUAN_KERJA_ID_ASAL", $this->SATUAN_KERJA_ID_ASAL);
		
		$disposisi->setField("SATUAN_KERJA_ID_TUJUAN", $reqSatuanKerjaIdTujuan);
		$disposisi->setField("STATUS_DISPOSISI", "BALASAN");
		$disposisi->setField("LAST_CREATE_USER", $this->ID);
		$disposisi->insertbalas();
		// echo $disposisi->query;exit;
		
		$this->posting_proses("BALAS", $reqId, $reqDisposisiId);
			
		echo "-Balasan berhasil dikirim.";
	
	}
	
	
	
	function teruskan() 
	{
		$this->load->model("SuratMasuk");
		$this->load->model("Disposisi");
		$surat_masuk = new SuratMasuk();
		$disposisi = new Disposisi();

		$reqMode 					= $this->input->post("reqMode");
		$reqId 						= $this->input->post("reqId");
		$reqDisposisiId 			= $this->input->post("reqDisposisiId");

		$reqSatuanKerjaIdTujuan		=  $this->input->post("reqSatuanKerjaIdTujuan");
		$reqSatuanKerjaIdTembusan	=  $this->input->post("reqSatuanKerjaIdTembusan");	
		$reqBalasCepatId   	=  $this->input->post("reqBalasCepatId");	
		$reqBalasCepat   	=  $this->input->post("reqBalasCepat");	
		$reqKeterangan   	=  $this->input->post("reqKeterangan");	
		
		if(count($reqSatuanKerjaIdTujuan) == 0 || $reqSatuanKerjaIdTujuan[0] == "")
		{
			echo "X-Isi terlebih dahulu tujuan.";	
			return;
		}
		
		/* UPDATE STATUS TERDISPOSISI */
		$disposisi->setField("FIELD", "TERUSKAN");
		$disposisi->setField("FIELD_VALUE", "1");
		$disposisi->setField("LAST_UPDATE_USER", $this->ID);
		$disposisi->setField("DISPOSISI_ID", $reqDisposisiId);
		$disposisi->updateByField();
		
		for($i=0;$i<count($reqSatuanKerjaIdTujuan);$i++)
		{
			if($reqSatuanKerjaIdTujuan[$i] == "")
			{}
			else
			{
				$disposisi = new Disposisi();
				$disposisi->setField("DISPOSISI_PARENT_ID", $reqDisposisiId);
				$disposisi->setField("SURAT_MASUK_ID", $reqId);
				$disposisi->setField("ISI", $reqBalasCepat);
				$disposisi->setField("KETERANGAN", setQuote($reqKeterangan, ""));
				$disposisi->setField("SATUAN_KERJA_ID_ASAL", $this->SATUAN_KERJA_ID_ASAL);
				$disposisi->setField("SATUAN_KERJA_ID_TUJUAN", $reqSatuanKerjaIdTujuan[$i]);
				$disposisi->setField("STATUS_DISPOSISI", "TERUSAN");
				$disposisi->setField("LAST_CREATE_USER", $this->ID);
				$disposisi->insert();
			}
		}
		
		$this->posting_proses("TERUSKAN", $reqId, $reqDisposisiId);
			
		echo "-Naskah / Disposisi berhasil diteruskan.";
	
	}
	
	function posting_proses($reqJenis, $reqId, $reqDisposisiId) 
	{
		
		if($reqJenis == "DISPOSISI")
			$inParam = "'DISPOSISI', 'DISPOSISI_TEMBUSAN'";
		elseif($reqJenis == "BALAS")
			$inParam = "'BALASAN'";
		elseif($reqJenis == "TERUSKAN")
			$inParam = "'TERUSAN'";
		else
			return;
		
		// // tambahan khusus
		// $this->load->model("SuratMasuk");
		// $surat_masuk = new SuratMasuk();
		// $surat_masuk->selectByParamsMonitoring(array("A.SURAT_MASUK_ID" => $reqId));
		// $surat_masuk->firstRow();
		// $reqTitle = $surat_masuk->getField("NOMOR");
		// $reqBody  = $surat_masuk->getField("PERIHAL");
		// /* SEND PUSH NOTIF */
		// $this->load->library("PushNotification"); 
		// $this->load->model("UserLoginMobile");

		// $user_login_mobile = new UserLoginMobile();
		// $user_login_mobile->selectByParams(array("A.STATUS" => "1"), -1, -1, " AND EXISTS(SELECT 1 FROM DISPOSISI X WHERE X.USER_ID = A.PEGAWAI_ID AND X.SURAT_MASUK_ID = '".$reqId."' AND X.DISPOSISI_PARENT_ID = '".$reqDisposisiId."' 
		// 	AND X.STATUS_DISPOSISI IN (".$inParam.")) ");
		// while($user_login_mobile->nextRow())
		// {
		// 	$row = array();
		// 	$row['to'] = $user_login_mobile->getField("TOKEN_FIREBASE");
		// 	$row['data']["title"] = $reqTitle;
		// 	$row['data']["body"]  = $reqBody;
		// 	$row['data']["tipe"]  = "INTERNAL"; // INFORMASI / CHAT
		// 	$pushData = $row;
		// 	$pushNotification = new PushNotification();
		// 	$pushNotification->send_notification_v2($pushData);
		// 	unset($row);
		// }	
		
	}	
	
	function delete() 
	{
		$reqId	= $this->input->get('reqId');
		$this->load->model("SuratMasuk");
		$surat_masuk = new SuratMasuk();

		
		$surat_masuk->setField("SURAT_MASUK_ID", $reqId);
		if($surat_masuk->delete())
			$arrJson["PESAN"] = "Data berhasil dihapus.";
		else
			$arrJson["PESAN"] = "Data gagal dihapus.";		
		
		echo json_encode($arrJson);
	}	
	
	function combo() 
	{
		$this->load->model("SuratMasuk");
		$surat_masuk = new SuratMasuk();

		$surat_masuk->selectByParams(array());
		$i = 0;
		while($surat_masuk->nextRow())
		{
			$arr_json[$i]['id']		= $surat_masuk->getField("SURAT_MASUK_ID");
			$arr_json[$i]['text']	= $surat_masuk->getField("NAMA");
			$i++;
		}
		
		echo json_encode($arr_json);
	}
	
}

