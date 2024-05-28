<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class absensi_manual_json extends CI_Controller {

	function __construct() {
		parent::__construct();
		
		//kauth
		if (!$this->kauth->getInstance()->hasIdentity())
		{
			// trow to unauthenticated page!
			//redirect('Login');
		}       
		
		/* GLOBAL VARIABLE */
		$this->username = $this->kauth->getInstance()->getIdentity()->USERNAME;   
	}	
	
	function json() 
	{
		$this->load->model('AbsensiManual');

		$absensi_manual = new AbsensiManual();
		
		
		ini_set("memory_limit","500M");
		ini_set('max_execution_time', 520);
		
		/* SEARCHING */
		$reqSearchKey = $this->input->get("reqSearchKey");
		$reqSearchValue = $this->input->get("reqSearchValue");
		
		$reqDepartemen = $this->input->get("reqDepartemen");
		$reqTahun= $this->input->get("reqTahun");
		$reqBulan= $this->input->get("reqBulan");
		$reqId= $this->input->get("reqId");
		$reqRefId = $this->input->get("reqRefId");
		$reqMode = $this->input->get("reqMode");
		
		$search_statement = "";
		if($reqSearchKey == "")
		{}
		else
		{
			$arrSearchKey = explode(",", $reqSearchKey);
			$arrSearchValue = explode(",", $reqSearchValue);
		
			for($i=0;$i<count($arrSearchKey);$i++)
			{
				if($arrSearchKey[$i] == "")
				{}
				else
					$search_statement .= " AND UPPER(".$arrSearchKey[$i].") LIKE '%".strtoupper($arrSearchValue[$i])."%'";
			}
		}
		/* SEARCHING */
		
		$aColumns = array('ABSENSI_MANUAL_ID','NRP', 'NAMA', 'DEPARTEMEN', 'JENIS', 'JAM', 'BUKTI');
		$aColumnsAlias = array('ABSENSI_MANUAL_ID', 'NRP', 'B.NAMA', 'DEPARTEMEN', 'JENIS', 'JAM', 'BUKTI');
		
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
			if ( trim($sOrder) == "ORDER BY B.NRP asc" )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY B.NIK DESC";
				 
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
		
		if ( $reqId <> "" && $reqMode == "Approve") {
			$absensi_manual->setField("LAST_CREATE_USER", $userLogin->nama);
			$absensi_manual->ApproveAbsensiManual($reqId);
		}
		
		if ( $reqId <> "" && $reqMode == "Hapus") {
			$absensi_manual->AbsensiManualHapus($reqId);
		}
		
		/* FILTER DEPARTEMEN */
		$arrDepartemen = explode("-", $reqDepartemen);
		$reqCabangId = $arrDepartemen[0];
		$reqDepartemenId = $arrDepartemen[1];
		$reqSubDepartemenId = $arrDepartemen[2];
		
		if(trim($reqCabangId) !== '')
			$statement .= " AND B.CABANG_ID LIKE '".$reqCabangId."%'";
		if (trim($reqDepartemenId) !== '')
			$statement .= " AND B.DEPARTEMEN_ID LIKE '".$reqDepartemenId."%'";
		if (trim($reqSubDepartemenId) !== "")
			$statement .= " AND B.SUB_DEPARTEMEN_ID LIKE '".$reqSubDepartemenId."%' ";
		
		if ( $reqTahun <> "" ) {
			$statement_privacy = " AND TO_CHAR(a.JAM, 'MMYYYY') = '".$reqBulan.$reqTahun."' ";
		}
		
		$allRecord = $absensi_manual->getCountByParams(array(), $statement.$statement_privacy);
		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter = $absensi_manual->getCountByParams(array(),  " AND (UPPER(B.NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%') ". $statement. $statement_privacy , " ORDER BY JAM DESC ");
		
		$absensi_manual->selectByParamsMonitoring(array(), $dsplyRange, $dsplyStart,  " AND (UPPER(B.NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%') ". $statement. $statement_privacy , " ORDER BY JAM DESC ");     		
		
		//echo $absensi_manual->query;
		//echo "IKI ".$_GET['iDisplayStart'];
			/*
			 * Output 
			 */
			$output = array(
				"sEcho" => intval($_GET['sEcho']),
				"iTotalRecords" => $allRecord,
				"iTotalDisplayRecords" => $allRecordFilter,
				"aaData" => array()
			);
			
			while($absensi_manual->nextRow())
			{
				$row = array();
				for ( $i=0 ; $i<count($aColumns) ; $i++ )
				{
					if($aColumns[$i] == "JAM")
					{
						$row[] = getFormattedDateTime($absensi_manual->getField("JAM"));	
					}
					else
					{
						$row[] = $absensi_manual->getField(trim($aColumns[$i]));
					}
				}
				
				$output['aaData'][] = $row;
		
			}
			
			echo json_encode( $output );
			
	}
	
	function add()
	{
		
		$this->load->model('AbsensiManual');

		$absensi_manual = new AbsensiManual();

		$reqId = $this->input->post("reqId");
		$reqMode = $this->input->post("reqMode");
		
		$reqPegawaiId= $this->input->post("reqPegawaiId");
		$reqStatus= $this->input->post("reqStatus");
		$reqBukti= $this->input->post("reqBukti");
		$reqJam= " TO_DATE('".$this->input->post("reqJam")."', 'DD-MM-YYYY HH24:MI:SS') ";
		$reqKeterangan= $this->input->post("reqKeterangan");
		
		$absensi_manual->setField('ABSENSI_MANUAL_ID', $reqId);
		$absensi_manual->setField('PEGAWAI_ID', $reqPegawaiId);
		$absensi_manual->setField('STATUS', $reqStatus);
		$absensi_manual->setField('BUKTI', $reqBukti);
		$absensi_manual->setField('JAM', $reqJam);
		$absensi_manual->setField('KETERANGAN', $reqKeterangan);
		if($reqMode == "insert")
		{
			$absensi_manual->setField("LAST_CREATE_USER", $this->username);
			$absensi_manual->setField("LAST_CREATE_DATE", "SYSDATE");	
			
			if($absensi_manual->insert())
				echo "Data berhasil disimpan.";
		}
		else
		{	
			$absensi_manual->setField("LAST_UPDATE_USER", $this->username);
			$absensi_manual->setField("LAST_UPDATE_DATE", "SYSDATE");
			
			if($absensi_manual->update()) {
				echo "Data berhasil disimpan.";
			}
		}
		
	}
	
	function delete()
	{
		$this->load->model('AbsensiManual');
		$absensi_manual = new AbsensiManual();
		
		$reqId = $this->uri->segment(3, "");
		$absensi_manual->setField("ABSENSI_MANUAL_ID", $reqId);
		
		if($absensi_manual->delete())
			$arrJson["PESAN"] = "Data berhasil dihapus.";
		else
			$arrJson["PESAN"] = "Data gagal dihapus.";		
		
		echo json_encode($arrJson);
	}	
	
}
?>
