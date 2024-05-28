<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class arsip_json extends CI_Controller {

	function __construct() {
		parent::__construct();
		
		if (!$this->kauth->getInstance()->hasIdentity())
		{
			//edirect('login');
		}    
		
		$this->db->query("SET DATESTYLE TO PostgreSQL,European;");   
		$this->ID				= $this->kauth->getInstance()->getIdentity()->ID;   
		$this->NAMA				= $this->kauth->getInstance()->getIdentity()->NAMA;   
		$this->JABATAN			= $this->kauth->getInstance()->getIdentity()->JABATAN;   
		$this->HAK_AKSES		= $this->kauth->getInstance()->getIdentity()->HAK_AKSES;   
		$this->LAST_LOGIN		= $this->kauth->getInstance()->getIdentity()->LAST_LOGIN;   
		$this->USERNAME			= $this->kauth->getInstance()->getIdentity()->USERNAME;  
		$this->USER_LOGIN_ID	= $this->kauth->getInstance()->getIdentity()->USER_LOGIN_ID;  
		$this->USER_GROUP		= $this->kauth->getInstance()->getIdentity()->USER_GROUP;  
		$this->MULTIROLE		= $this->kauth->getInstance()->getIdentity()->MULTIROLE;  
		$this->CABANG_ID		= $this->kauth->getInstance()->getIdentity()->CABANG_ID;  
		$this->CABANG			= $this->kauth->getInstance()->getIdentity()->CABANG;  
		$this->SATUAN_KERJA_ID_ASAL	= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_ID_ASAL;  
		$this->SATUAN_KERJA_ASAL	= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_ASAL;  
		$this->SATUAN_KERJA_HIRARKI	= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_HIRARKI;  
		$this->SATUAN_KERJA_JABATAN	= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_JABATAN;  
		$this->KD_LEVEL = $this->kauth->getInstance()->getIdentity()->KD_LEVEL;  
		$this->JENIS_KELAMIN = $this->kauth->getInstance()->getIdentity()->JENIS_KELAMIN;  
		
		
	}
	
	function json() 
	{
		$this->load->model("Arsip");
		$arsip = new Arsip();

		// $reqKategori = $this->input->get("reqKategori");
		// echo $reqKategori;exit;
		
		$aColumns		= array("ARSIP_ID", "KLASIFIKASI_KODE",  "PENYUSUTAN_AKHIR_KODE", "LOKASI_ARSIP_KODE", "KODE", "NAMA", "KETERANGAN", "RETENSI_AKTIF", "RETENSI_INAKTIF");
		$aColumnsAlias	= array("ARSIP_ID", "KLASIFIKASI_KODE",  "PENYUSUTAN_AKHIR_KODE", "LOKASI_ARSIP_KODE", "KODE", "NAMA", "KETERANGAN", "RETENSI_AKTIF", "RETENSI_INAKTIF");

		
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
			if ( trim($sOrder) == "ORDER BY ARSIP_ID asc" )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.NAMA asc";
				 
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

		$statement_privacy .= " ";
		
		 $statement= " AND (UPPER(A.NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%')";
		$allRecord = $arsip->getCountByParams(array(), $statement_privacy.$statement);
		// echo $allRecord;exit;
		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter =  $arsip->getCountByParams(array(), $statement_privacy.$statement);
		
		 $arsip->selectByParams(array(), $dsplyRange, $dsplyStart, $statement_privacy.$statement, $sOrder);
		
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
		
		while($arsip->nextRow())		
		{		
			$row = array();		
			for ( $i=0 ; $i<count($aColumns) ; $i++ )		
			{	
				if($aColumns[$i] == "KETERANGAN")
					$row[] = truncate($arsip->getField($aColumns[$i]), 2);
				else
					$row[] = $arsip->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		echo json_encode( $output );	

		
	}
	
	function json_kelola() 
	{
		$this->load->model("Arsip");
		$arsip = new Arsip();

		// $reqKategori = $this->input->get("reqKategori");
		// echo $reqKategori;exit;
		
		$aColumns		= array("ARSIP_ID", "KLASIFIKASI_KODE", "KODE", "NAMA", "KETERANGAN", "RETENSI_AKTIF", "RETENSI_INAKTIF", "LOKASI_ARSIP");
		$aColumnsAlias	= array("ARSIP_ID", "KLASIFIKASI_KODE",  "KODE", "NAMA", "KETERANGAN", "RETENSI_AKTIF", "RETENSI_INAKTIF", "LOKASI_ARSIP");

		
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
			if ( trim($sOrder) == "ORDER BY ARSIP_ID asc" )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.NAMA asc";
				 
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
		
		
		if($this->USER_GROUP == "TATAUSAHA")
			$statement_privacy .= " AND A.SATUAN_KERJA_ID = '".$this->CABANG_ID."' ";
		else
			$statement_privacy .= " AND A.SATUAN_KERJA_ID = '".$this->SATUAN_KERJA_ID_ASAL."' ";
		
		 $statement= " AND (UPPER(A.NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%')";
		$allRecord = $arsip->getCountByParamsMonitoring(array(), $statement_privacy.$statement);
		// echo $allRecord;exit;
		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter =  $arsip->getCountByParamsMonitoring(array(), $statement_privacy.$statement);
		
		 $arsip->selectByParamsMonitoring(array(), $dsplyRange, $dsplyStart, $statement_privacy.$statement, $sOrder);
		
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
		
		while($arsip->nextRow())		
		{		
			$row = array();		
			for ( $i=0 ; $i<count($aColumns) ; $i++ )		
			{	
				if($aColumns[$i] == "KETERANGAN")
					$row[] = truncate($arsip->getField($aColumns[$i]), 2);
				else
					$row[] = $arsip->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		echo json_encode( $output );	

		
	}
	
	function add() 
	{
		$this->load->model("Arsip");
		$arsip = new Arsip();

		$reqMode 					= $this->input->post("reqMode");
		$reqId 						= $this->input->post("reqId");
		
		$reqCabangId				= $this->input->post("reqCabangId");
		$reqSatuanKerjaId			= $this->input->post("reqSatuanKerjaId");
		$reqKlasifikasiId			= $this->input->post("reqKlasifikasiId");
		$reqKlasifikasiKode			= $this->input->post("reqKlasifikasiKode");
		$reqArsipKode				= $this->input->post("reqArsipKode");
		$reqPenyusutanAkhirId		= $this->input->post("reqPenyusutanAkhirId");
		$reqPenyusutanAkhirKode		= $this->input->post("reqPenyusutanAkhirKode");
		$reqLokasiArsipId			= $this->input->post("reqLokasiArsipId");
		$reqLokasiArsipKode			= $this->input->post("reqLokasiArsipKode");
		$reqUrut					= $this->input->post("reqUrut");
		$reqKode					= $this->input->post("reqKode");
		$reqNama					= $this->input->post("reqNama");
		$reqKeterangan				= $this->input->post("reqKeterangan");
		$reqRetensiAktif			= $this->input->post("reqRetensiAktif");
		$reqRetensiInaktif			= $this->input->post("reqRetensiInaktif");
		
		//echo $reqMode;
		$arsip->setField("ARSIP_ID", $reqId);
		$arsip->setField("CABANG_ID", $this->CABANG_ID);
		
		if($this->USER_GROUP == "TATAUSAHA")
			$arsip->setField("SATUAN_KERJA_ID", $this->CABANG_ID);
		else
			$arsip->setField("SATUAN_KERJA_ID", $this->SATUAN_KERJA_ID_ASAL);
		
		$arsip->setField("KLASIFIKASI_ID", $reqKlasifikasiId);
		$arsip->setField("KLASIFIKASI_KODE", $reqKlasifikasiKode);
		$arsip->setField("PENYUSUTAN_AKHIR_ID", $reqPenyusutanAkhirId);
		$arsip->setField("LOKASI_ARSIP_ID", $reqLokasiArsipId);
		$arsip->setField("URUT", $reqUrut);
		$arsip->setField("KODE", $reqKode);
		$arsip->setField("NAMA", $reqNama);
		$arsip->setField("KETERANGAN", $reqKeterangan);
		$arsip->setField("RETENSI_AKTIF", $reqRetensiAktif);
		$arsip->setField("RETENSI_INAKTIF", $reqRetensiInaktif);
		
		

		if($reqMode == "insert")
		{
			$arsip->setField("LAST_CREATE_USER", $this->USERNAME);
			$arsip->setField("LAST_CREATED_DATE", "CURRENT_DATE");
			$arsip->insert();
		}
		else
		{
			$arsip->setField("LAST_UPDATE_USER", $this->USERNAME);
			$arsip->setField("LAST_UPDATED_DATE", "CURRENT_DATE");
			$arsip->update();
		}	
		
		echo "Data berhasil disimpan.";
	}
	
	function delete() 
	{
		$reqId	= $this->input->get('reqId');
		$this->load->model("Arsip");
		$arsip = new Arsip();

		$arsip->setField("ARSIP_ID", $reqId);

		if($arsip->delete())
			$arrJson["PESAN"] = "Data berhasil dihapus.";
		else
			$arrJson["PESAN"] = "Data gagal dihapus.";		
		
		echo json_encode($arrJson);
	}
	


	
	function combo() 
	{
		
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : 50;
		$offset = ($page-1)*$rows;
		
		$reqPencarian = $this->input->get("reqPencarian");
		$reqMode = $this->input->get("reqMode");
		
		
		$this->load->model("Arsip");
		$arsip = new Arsip();

		if($reqPencarian == "")
		{}
		else
			$statement = " AND UPPER(A.NAMA) LIKE '%".strtoupper($reqPencarian)."%' ";
		
		$statement_privacy = "";
		
		if($this->USER_GROUP == "TATAUSAHA")
			$statement_privacy .= " AND A.SATUAN_KERJA_ID = '".$this->CABANG_ID."' ";
		else
			$statement_privacy .= " AND A.SATUAN_KERJA_ID = '".$this->SATUAN_KERJA_ID_ASAL."' ";
			
		
		$rowCount = $arsip->getCountByParams(array(), $statement.$statement_privacy);
		$arsip->selectByParams(array(), $rows, $offset, $statement.$statement_privacy);
		// echo $arsip->query;exit;
		$i = 0;
		$items = array();
		while($arsip->nextRow())
		{
			$row['id']		= $arsip->getField("ARSIP_ID");
			$row['text']	= $arsip->getField("NAMA");
			$row['ARSIP_ID']	= $arsip->getField("ARSIP_ID");
			$row['NAMA']	= $arsip->getField("NAMA");
			$row['CABANG']	= $arsip->getField("SATUAN_KERJA");
			$row['JABATAN']	= $arsip->getField("JABATAN");
			$row['KETERANGAN']	= $arsip->getField("KETERANGAN");
			$row["KLASIFIKASI"]	= $arsip->getField("KLASIFIKASI_KODE")." - ".$arsip->getField("NAMA");
			$row["BERKAS"]	= $arsip->getField("KLASIFIKASI_KODE")."/".$arsip->getField("KODE")." - ".$arsip->getField("NAMA");
			$row["KODE"]				= $arsip->getField("KODE");
			$row['state'] = 'close';
			$i++;
			array_push($items, $row);
		}
		$result["rows"] = $items;
		$result["total"] = $rowCount;
		echo json_encode($result);
	}
	
	
	
	
}

