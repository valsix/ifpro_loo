<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class permohonan_stpd_json extends CI_Controller
{
	var $calendarId = 'cohu8p4q74iks6dpilk7mrpvi4@group.calendar.google.com';

	function __construct()
	{
		parent::__construct();

		$reqToken = $this->input->get("reqToken");
		if ($reqToken == "") {
			$reqToken = $this->input->post("reqToken");
		}

		if (!empty($reqToken)) {
			$this->load->model('UserLoginMobile');
			$user_login_mobile = new UserLoginMobile();
			$reqPegawaiId = $user_login_mobile->getTokenPegawaiId(array("TOKEN" => $reqToken, "STATUS" => '1'));
			
			if ($reqPegawaiId == "0") {
				$arrReturn = array('status' => 'fail', 'message' => 'Sesi anda telah berakhir', 'code' => 502);
				echo json_encode($arrReturn);
				return;
			}

			$this->kauth->mobileVerification($reqPegawaiId, $reqToken);
			$this->CALLER = "MOBILE";
		}

		if (!$this->kauth->getInstance()->hasIdentity()) {
			redirect('login');
		}

		$this->db->query("SET DATESTYLE TO PostgreSQL,European;");
		$this->ID					= $this->kauth->getInstance()->getIdentity()->ID;
		$this->NAMA					= $this->kauth->getInstance()->getIdentity()->NAMA;
		$this->JABATAN				= $this->kauth->getInstance()->getIdentity()->JABATAN;
		$this->HAK_AKSES			= $this->kauth->getInstance()->getIdentity()->HAK_AKSES;
		$this->LAST_LOGIN			= $this->kauth->getInstance()->getIdentity()->LAST_LOGIN;
		$this->USERNAME				= $this->kauth->getInstance()->getIdentity()->USERNAME;
		$this->USER_LOGIN_ID		= $this->kauth->getInstance()->getIdentity()->USER_LOGIN_ID;
		$this->USER_GROUP			= $this->kauth->getInstance()->getIdentity()->USER_GROUP;
		$this->MULTIROLE			= $this->kauth->getInstance()->getIdentity()->MULTIROLE;
		$this->CABANG_ID			= $this->kauth->getInstance()->getIdentity()->CABANG_ID;
		$this->CABANG				= $this->kauth->getInstance()->getIdentity()->CABANG;
		$this->SATUAN_KERJA_ID_ASAL	= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_ID_ASAL;
		$this->SATUAN_KERJA_ASAL	= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_ASAL;
		$this->SATUAN_KERJA_HIRARKI	= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_HIRARKI;
		$this->SATUAN_KERJA_JABATAN	= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_JABATAN;
		$this->KD_LEVEL				= $this->kauth->getInstance()->getIdentity()->KD_LEVEL;
		$this->KD_LEVEL_PEJABAT 	= $this->kauth->getInstance()->getIdentity()->KD_LEVEL_PEJABAT;
		$this->JENIS_KELAMIN 		= $this->kauth->getInstance()->getIdentity()->JENIS_KELAMIN;
		$this->KELOMPOK_JABATAN 	= $this->kauth->getInstance()->getIdentity()->KELOMPOK_JABATAN;
		$this->KODE_PARENT= $this->kauth->getInstance()->getIdentity()->KODE_PARENT;
		$this->ID_ATASAN 			= $this->kauth->getInstance()->getIdentity()->ID_ATASAN;
		$this->DEPARTEMEN_PARENT_ID 			= $this->kauth->getInstance()->getIdentity()->DEPARTEMEN_PARENT_ID;

		$this->NIP_BY_DIVISI 			= $this->kauth->getInstance()->getIdentity()->NIP_BY_DIVISI;
		$this->KELOMPOK_JABATAN_BY_DIVISI 			= $this->kauth->getInstance()->getIdentity()->KELOMPOK_JABATAN_BY_DIVISI;

		$this->SATUAN_KERJA_ID_ASAL_ASLI= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_ID_ASAL_ASLI;
	}


	function json()
	{
		// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
		$this->load->model("PermohonanStpd");
		$set = new PermohonanStpd();

		$reqKeluar = $this->input->get("reqKeluar");
		$reqDraft = $this->input->get("reqDraft");
		// echo $reqKategori;exit;


		$aColumns = array(
			"NOMOR", "TANGGAL",  "DOKUMEN_ACUAN", "JUMLAH_PELAKSANA","STATUS_SURAT","PERMOHONAN_STPD_ID"
		);
		$aColumnsAlias =$aColumns;

		/*
		 * Ordering
		 */
		if (isset($_GET['iSortCol_0'])) {
			$sOrder = " ORDER BY ";

			//Go over all sorting cols
			for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
				//If need to sort by current col
				if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
					//Add to the order by clause
					$sOrder .= $aColumnsAlias[intval($_GET['iSortCol_' . $i])];

					//Determine if it is sorted asc or desc
					if (strcasecmp(($_GET['sSortDir_' . $i]), "asc") == 0) {
						$sOrder .= " asc, ";
					} else {
						$sOrder .= " desc, ";
					}
				}
			}

			//Remove the last space / comma
			$sOrder = substr_replace($sOrder, "", -2);

			//Check if there is an order by clause
			if (trim($sOrder) == "ORDER BY A.PERMOHONAN_SPTD_ID asc") {
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.PERMOHONAN_SPTD_ID DESC";
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
		if (isset($_GET['sSearch'])) {
			$sWhereGenearal = $_GET['sSearch'];
		} else {
			$sWhereGenearal = '';
		}

		if ($_GET['sSearch'] != "") {
			//Set a default where clause in order for the where clause not to fail
			//in cases where there are no searchable cols at all.
			$sWhere = " AND (";
			for ($i = 0; $i < count($aColumnsAlias) + 1; $i++) {
				//If current col has a search param
				if ($_GET['bSearchable_' . $i] == "true") {
					//Add the search to the where clause
					$sWhere .= $aColumnsAlias[$i] . " LIKE '%" . $_GET['sSearch'] . "%' OR ";
					$nWhereGenearalCount += 1;
				}
			}
			$sWhere = substr_replace($sWhere, "", -3);
			$sWhere .= ')';
		}

		/* Individual column filtering */
		$sWhereSpecificArray = array();
		$sWhereSpecificArrayCount = 0;
		for ($i = 0; $i < count($aColumnsAlias); $i++) {
			if ($_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {
				//If there was no where clause
				if ($sWhere == "") {
					$sWhere = "AND ";
				} else {
					$sWhere .= " AND ";
				}

				//Add the clause of the specific col to the where clause
				$sWhere .= $aColumnsAlias[$i] . " LIKE '%' || :whereSpecificParam" . $sWhereSpecificArrayCount . " || '%' ";

				//Inc sWhereSpecificArrayCount. It is needed for the bind var.
				//We could just do count($sWhereSpecificArray) - but that would be less efficient.
				$sWhereSpecificArrayCount++;

				//Add current search param to the array for later use (binding).
				$sWhereSpecificArray[] =  $_GET['sSearch_' . $i];
			}
		}

		//If there is still no where clause - set a general - always true where clause
		if ($sWhere == "") {
			$sWhere = " AND 1=1";
		}

		//Bind variables.
		if (isset($_GET['iDisplayStart'])) {
			$dsplyStart = $_GET['iDisplayStart'];
		} else {
			$dsplyStart = 0;
		}
		if (isset($_GET['iDisplayLength']) && $_GET['iDisplayLength'] != '-1') {
			$dsplyRange = $_GET['iDisplayLength'];
			if ($dsplyRange > (2147483645 - intval($dsplyStart))) {
				$dsplyRange = 2147483645;
			} else {
				$dsplyRange = intval($dsplyRange);
			}
		} else {
			$dsplyRange = 2147483645;
		}


		$statement = " AND (
						UPPER(A.NOMOR) LIKE '%" . strtoupper($_GET['sSearch']) . "%' OR
						UPPER(A.DOKUMEN_ACUAN) LIKE '%" . strtoupper($_GET['sSearch']) . "%' 
						) ";


		if($reqKeluar)
		{
			$statement_privacy = " AND A.SATUAN_KERJA_ID_ASAL ='".$this->SATUAN_KERJA_ID_ASAL."'";


			$allRecord = $set->getCountByParams(array(), $statement_privacy . $statement);
			// echo $allRecord;exit;
			if ($_GET['sSearch'] == "")
				$allRecordFilter = $allRecord;
			else
				$allRecordFilter =  $set->getCountByParams(array(), $statement_privacy . $statement);

			$set->selectByParamsKeluar(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);
		// echo $set->query;exit;
		}

		if($reqDraft==1)
		{
			$statement_privacy = " AND A.SATUAN_KERJA_ID_ASAL ='".$this->SATUAN_KERJA_ID_ASAL."' AND A.STATUS_SURAT='DRAFT'";


			$allRecord = $set->getCountByParams(array(), $statement_privacy . $statement);
			// echo $allRecord;exit;
			if ($_GET['sSearch'] == "")
				$allRecordFilter = $allRecord;
			else
				$allRecordFilter =  $set->getCountByParams(array(), $statement_privacy . $statement);

			$set->selectByParamsKeluar(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);
		// echo $set->query;exit;
		}
		// else
		// {
		// 	$statement_privacy = " AND B.SATUAN_KERJA_ID ='".$this->SATUAN_KERJA_ID_ASAL."' AND A.STATUS_SURAT='SELESAI'";
		// 	$allRecord = $set->getCountByParams(array(), $statement_privacy . $statement);
		// 	// echo $allRecord;exit;
		// 	if ($_GET['sSearch'] == "")
		// 		$allRecordFilter = $allRecord;
		// 	else
		// 		$allRecordFilter =  $set->getCountByParams(array(), $statement_privacy . $statement);

		// 	$set->selectByParams(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);
		// }


		/*
			 * Output 
			 */
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $allRecord,
			"iTotalDisplayRecords" => $allRecordFilter,
			"aaData" => array()
		);

		while ($set->nextRow()) {
			$row = array();
			for ($i = 0; $i < count($aColumns); $i++) {
				if ($aColumns[$i] == "KETERANGAN")
					$row[] = truncate($set->getField($aColumns[$i]), 2);
				elseif ($aColumns[$i] == "TERBACA" || $aColumns[$i] == "TERDISPOSISI" || $aColumns[$i] == "TERBALAS") {
					if ((int)$set->getField($aColumns[$i]) > 0)
						$row[] = "<i class=\"fa fa-check fa-md\" aria-hidden=\"true\"></i>";
					else
						$row[] = "<i class=\"fa fa-close fa-md\" aria-hidden=\"true\"></i>";
				} else
					$row[] = $set->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		echo json_encode($output);
	}

	function json_persetujuan()
	{
		// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
		$this->load->model("PermohonanStpd");
		$set = new PermohonanStpd();

		$reqKeluar = $this->input->get("reqKeluar");
		// echo $reqKategori;exit;


		$aColumns = array(
			"NOMOR", "TANGGAL",  "DOKUMEN_ACUAN", "JUMLAH_PELAKSANA","STATUS_SURAT","SATUAN_KERJA_ID_NAMA","PERMOHONAN_STPD_ID"
		);
		$aColumnsAlias =$aColumns;

		/*
		 * Ordering
		 */
		if (isset($_GET['iSortCol_0'])) {
			$sOrder = " ORDER BY ";

			//Go over all sorting cols
			for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
				//If need to sort by current col
				if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
					//Add to the order by clause
					$sOrder .= $aColumnsAlias[intval($_GET['iSortCol_' . $i])];

					//Determine if it is sorted asc or desc
					if (strcasecmp(($_GET['sSortDir_' . $i]), "asc") == 0) {
						$sOrder .= " asc, ";
					} else {
						$sOrder .= " desc, ";
					}
				}
			}

			//Remove the last space / comma
			$sOrder = substr_replace($sOrder, "", -2);

			//Check if there is an order by clause
			if (trim($sOrder) == "ORDER BY A.PERMOHONAN_SPTD_ID asc") {
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.PERMOHONAN_SPTD_ID DESC";
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
		if (isset($_GET['sSearch'])) {
			$sWhereGenearal = $_GET['sSearch'];
		} else {
			$sWhereGenearal = '';
		}

		if ($_GET['sSearch'] != "") {
			//Set a default where clause in order for the where clause not to fail
			//in cases where there are no searchable cols at all.
			$sWhere = " AND (";
			for ($i = 0; $i < count($aColumnsAlias) + 1; $i++) {
				//If current col has a search param
				if ($_GET['bSearchable_' . $i] == "true") {
					//Add the search to the where clause
					$sWhere .= $aColumnsAlias[$i] . " LIKE '%" . $_GET['sSearch'] . "%' OR ";
					$nWhereGenearalCount += 1;
				}
			}
			$sWhere = substr_replace($sWhere, "", -3);
			$sWhere .= ')';
		}

		/* Individual column filtering */
		$sWhereSpecificArray = array();
		$sWhereSpecificArrayCount = 0;
		for ($i = 0; $i < count($aColumnsAlias); $i++) {
			if ($_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {
				//If there was no where clause
				if ($sWhere == "") {
					$sWhere = "AND ";
				} else {
					$sWhere .= " AND ";
				}

				//Add the clause of the specific col to the where clause
				$sWhere .= $aColumnsAlias[$i] . " LIKE '%' || :whereSpecificParam" . $sWhereSpecificArrayCount . " || '%' ";

				//Inc sWhereSpecificArrayCount. It is needed for the bind var.
				//We could just do count($sWhereSpecificArray) - but that would be less efficient.
				$sWhereSpecificArrayCount++;

				//Add current search param to the array for later use (binding).
				$sWhereSpecificArray[] =  $_GET['sSearch_' . $i];
			}
		}

		//If there is still no where clause - set a general - always true where clause
		if ($sWhere == "") {
			$sWhere = " AND 1=1";
		}

		//Bind variables.
		if (isset($_GET['iDisplayStart'])) {
			$dsplyStart = $_GET['iDisplayStart'];
		} else {
			$dsplyStart = 0;
		}
		if (isset($_GET['iDisplayLength']) && $_GET['iDisplayLength'] != '-1') {
			$dsplyRange = $_GET['iDisplayLength'];
			if ($dsplyRange > (2147483645 - intval($dsplyStart))) {
				$dsplyRange = 2147483645;
			} else {
				$dsplyRange = intval($dsplyRange);
			}
		} else {
			$dsplyRange = 2147483645;
		}


		$statement = " AND (UPPER(A.NOMOR) LIKE '%" . strtoupper($_GET['sSearch']) . "%' OR UPPER(A.DOKUMEN_ACUAN) LIKE '%" . 			strtoupper($_GET['sSearch']) . "%' ) ";


		// $statement_privacy = " AND B.SATUAN_KERJA_ID_INFO LIKE '%".$this->SATUAN_KERJA_ID_ASAL."%' AND A.STATUS_SURAT = 'KIRIM'";
		$statement_privacy = " AND B.SATUAN_KERJA_ID_INFO LIKE '%".$this->SATUAN_KERJA_ID_ASAL."%' AND A.STATUS_SURAT != 'DRAFT'";
		$allRecord = $set->getCountByParams(array(), $statement_privacy . $statement);
		if ($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else
			$allRecordFilter =  $set->getCountByParams(array(), $statement_privacy . $statement);

		$set->selectByParamsPersetujuan(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);

		// echo $set->query;exit;

		

		/*
			 * Output 
			 */
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $allRecord,
			"iTotalDisplayRecords" => $allRecordFilter,
			"aaData" => array()
		);

		while ($set->nextRow()) {
			$row = array();
			for ($i = 0; $i < count($aColumns); $i++) {
				if ($aColumns[$i] == "KETERANGAN")
					$row[] = truncate($set->getField($aColumns[$i]), 2);
				elseif ($aColumns[$i] == "TERBACA" || $aColumns[$i] == "TERDISPOSISI" || $aColumns[$i] == "TERBALAS") {
					if ((int)$set->getField($aColumns[$i]) > 0)
						$row[] = "<i class=\"fa fa-check fa-md\" aria-hidden=\"true\"></i>";
					else
						$row[] = "<i class=\"fa fa-close fa-md\" aria-hidden=\"true\"></i>";
				} else
					$row[] = $set->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		echo json_encode($output);
	}


	function json_status()
	{
		// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
		$this->load->model("PermohonanStpd");
		$set = new PermohonanStpd();

		$reqKeluar = $this->input->get("reqKeluar");
		// echo $reqKategori;exit;


		$aColumns = array(
			"NOMOR", "TANGGAL",  "DOKUMEN_ACUAN", "JUMLAH_PELAKSANA","STATUS_SURAT","SATUAN_KERJA_ID_NAMA","PERMOHONAN_STPD_ID"
		);
		$aColumnsAlias =$aColumns;

		/*
		 * Ordering
		 */
		if (isset($_GET['iSortCol_0'])) {
			$sOrder = " ORDER BY ";

			//Go over all sorting cols
			for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
				//If need to sort by current col
				if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
					//Add to the order by clause
					$sOrder .= $aColumnsAlias[intval($_GET['iSortCol_' . $i])];

					//Determine if it is sorted asc or desc
					if (strcasecmp(($_GET['sSortDir_' . $i]), "asc") == 0) {
						$sOrder .= " asc, ";
					} else {
						$sOrder .= " desc, ";
					}
				}
			}

			//Remove the last space / comma
			$sOrder = substr_replace($sOrder, "", -2);

			//Check if there is an order by clause
			if (trim($sOrder) == "ORDER BY A.PERMOHONAN_SPTD_ID asc") {
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.PERMOHONAN_SPTD_ID DESC";
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
		if (isset($_GET['sSearch'])) {
			$sWhereGenearal = $_GET['sSearch'];
		} else {
			$sWhereGenearal = '';
		}

		if ($_GET['sSearch'] != "") {
			//Set a default where clause in order for the where clause not to fail
			//in cases where there are no searchable cols at all.
			$sWhere = " AND (";
			for ($i = 0; $i < count($aColumnsAlias) + 1; $i++) {
				//If current col has a search param
				if ($_GET['bSearchable_' . $i] == "true") {
					//Add the search to the where clause
					$sWhere .= $aColumnsAlias[$i] . " LIKE '%" . $_GET['sSearch'] . "%' OR ";
					$nWhereGenearalCount += 1;
				}
			}
			$sWhere = substr_replace($sWhere, "", -3);
			$sWhere .= ')';
		}

		/* Individual column filtering */
		$sWhereSpecificArray = array();
		$sWhereSpecificArrayCount = 0;
		for ($i = 0; $i < count($aColumnsAlias); $i++) {
			if ($_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {
				//If there was no where clause
				if ($sWhere == "") {
					$sWhere = "AND ";
				} else {
					$sWhere .= " AND ";
				}

				//Add the clause of the specific col to the where clause
				$sWhere .= $aColumnsAlias[$i] . " LIKE '%' || :whereSpecificParam" . $sWhereSpecificArrayCount . " || '%' ";

				//Inc sWhereSpecificArrayCount. It is needed for the bind var.
				//We could just do count($sWhereSpecificArray) - but that would be less efficient.
				$sWhereSpecificArrayCount++;

				//Add current search param to the array for later use (binding).
				$sWhereSpecificArray[] =  $_GET['sSearch_' . $i];
			}
		}

		//If there is still no where clause - set a general - always true where clause
		if ($sWhere == "") {
			$sWhere = " AND 1=1";
		}

		//Bind variables.
		if (isset($_GET['iDisplayStart'])) {
			$dsplyStart = $_GET['iDisplayStart'];
		} else {
			$dsplyStart = 0;
		}
		if (isset($_GET['iDisplayLength']) && $_GET['iDisplayLength'] != '-1') {
			$dsplyRange = $_GET['iDisplayLength'];
			if ($dsplyRange > (2147483645 - intval($dsplyStart))) {
				$dsplyRange = 2147483645;
			} else {
				$dsplyRange = intval($dsplyRange);
			}
		} else {
			$dsplyRange = 2147483645;
		}


		$statement = " AND (UPPER(A.NOMOR) LIKE '%" . strtoupper($_GET['sSearch']) . "%' OR UPPER(A.DOKUMEN_ACUAN) LIKE '%" . 			strtoupper($_GET['sSearch']) . "%' ) ";


		$statement_privacy = " AND A.SATUAN_KERJA_ID_ASAL ='".$this->SATUAN_KERJA_ID_ASAL."' ";
		$allRecord = $set->getCountByParams(array(), $statement_privacy . $statement);
		if ($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else
			$allRecordFilter =  $set->getCountByParams(array(), $statement_privacy . $statement);

		$set->selectByParamsStatus(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);

		// print_r($set) ;exit;

		

		/*
			 * Output 
			 */
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $allRecord,
			"iTotalDisplayRecords" => $allRecordFilter,
			"aaData" => array()
		);

		while ($set->nextRow()) {
			$row = array();
			for ($i = 0; $i < count($aColumns); $i++) {
				if ($aColumns[$i] == "KETERANGAN")
					$row[] = truncate($set->getField($aColumns[$i]), 2);
				elseif ($aColumns[$i] == "TERBACA" || $aColumns[$i] == "TERDISPOSISI" || $aColumns[$i] == "TERBALAS") {
					if ((int)$set->getField($aColumns[$i]) > 0)
						$row[] = "<i class=\"fa fa-check fa-md\" aria-hidden=\"true\"></i>";
					else
						$row[] = "<i class=\"fa fa-close fa-md\" aria-hidden=\"true\"></i>";
				} else
					$row[] = $set->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		echo json_encode($output);
	}

	function add()
	{

		// print_r(1);exit;

		$this->load->model("PermohonanStpd");

		$set = new PermohonanStpd();

		$reqMode 					= $this->input->post("reqMode");
		$reqId 						= $this->input->post("reqId");

		$reqNomor 			= $this->input->post("reqNomor");
		$reqTanggal 			= $this->input->post("reqTanggal");
		$reqDokumenAcuan 			= $this->input->post("reqDokumenAcuan");
		$reqJumlah 			= $this->input->post("reqJumlah");
		$reqLokasiDinas= $this->input->post("reqLokasiDinas");
		$reqTanggalBerangkat= $this->input->post("reqTanggalBerangkat");
		$reqTanggalKembali= $this->input->post("reqTanggalKembali");
		$reqTotalPeriodeHari= $this->input->post("reqTotalPeriodeHari");
		$reqTotalPeriodeMalam= $this->input->post("reqTotalPeriodeMalam");
		$reqStatusSurat= $this->input->post("reqStatusSurat");

		$reqPemimpinId= $this->input->post("reqPemimpinId");
		$reqPelaksanaId= $this->input->post("reqPelaksanaId");
		$reqPengajuanDisiapkanId= $this->input->post("reqPengajuanDisiapkanId");
		$reqPengajuanDisetujuiId= $this->input->post("reqPengajuanDisetujuiId");
		$reqRealisasiDisiapkanId= $this->input->post("reqRealisasiDisiapkanId");
		$reqRealisasiMengetahuiId= $this->input->post("reqRealisasiMengetahuiId");
		$reqRealisasiDisetujuiId= $this->input->post("reqRealisasiDisetujuiId");

		$reqAlokasi= $this->input->post("reqAlokasi");
		$reqPengajuan= $this->input->post("reqPengajuan");
		$reqRealisasi= $this->input->post("reqRealisasi");

		$reqSatuanKerjaIdTujuan= $this->input->post("reqSatuanKerjaIdTujuan");
		$reqUntukId= $this->input->post("reqUntukId");

		$reqTotalRealisasi= $this->input->post("reqTotalRealisasi");

		// print_r($reqSatuanKerjaIdTujuan);exit;


		$set->setField("NOMOR", $reqNomor);
		$set->setField("TANGGAL", dateToDBCheck($reqTanggal));
		$set->setField("DOKUMEN_ACUAN", str_replace("'", "&quot;", $reqDokumenAcuan));
		$set->setField("JUMLAH_PELAKSANA", $reqJumlah);
		$set->setField("LOKASI_DINAS", str_replace("'", "&quot;", $reqLokasiDinas));
		$set->setField("TANGGAL_BERANGKAT", dateToDBCheck($reqTanggalBerangkat));
		$set->setField("TANGGAL_KEMBALI", dateToDBCheck($reqTanggalKembali));
		$set->setField("TOTAL_PERIODE_HARI", ValToNullDB($reqTotalPeriodeHari));
		$set->setField("TOTAL_PERIODE_MALAM", ValToNullDB($reqTotalPeriodeMalam));
		$set->setField("STATUS_SURAT", $reqStatusSurat);


		$set->setField("SATUAN_KERJA_ID_ASAL", $this->SATUAN_KERJA_ID_ASAL);

		$set->setField("PEMIMPIN_ID", ValToNullDB($reqPemimpinId));
		$set->setField("PELAKSANA_ID", ValToNullDB($reqPelaksanaId));
		$set->setField("PENGAJUAN_DISIAPKAN_ID", ValToNullDB($reqPengajuanDisiapkanId));
		$set->setField("PENGAJUAN_DISETUJUI_ID", ValToNullDB($reqPengajuanDisetujuiId));
		$set->setField("REALISASI_DISIAPKAN_ID", ValToNullDB($reqRealisasiDisiapkanId));
		$set->setField("REALISASI_MENGETAHUI_ID", ValToNullDB($reqRealisasiMengetahuiId));
		$set->setField("REALISASI_DISETUJUI_ID", ValToNullDB($reqRealisasiDisetujuiId));

		$set->setField("TOTAL_REALISASI", ValToNullDB($reqTotalRealisasi));

		$set->setField("PERMOHONAN_STPD_ID", $reqId);
		
		$reqSimpan=0;


		if($reqStatusSurat == "REVISI" || $reqStatusSurat == "SETUJU")
		{

			$jumlah= new PermohonanStpd();
			$statement=" AND A.STATUS IS NULL";
			$hitung=$jumlah->getCountByParamsUntuk(array("A.PERMOHONAN_STPD_ID" => $reqId), $statement);

			// print_r($hitung);exit;
			$statusup="";
			if($hitung==1)
			{
				$reqStatusSurat="SELESAI";
				$statusup=3;
			}

			$status = new PermohonanStpd();
			$status->setField("PERMOHONAN_STPD_ID", $reqId);
			$status->setField("STATUS_SURAT", $reqStatusSurat);
			if($status->updatestatus())
			{
				$reqSimpan=1;
			}

			unset($status);

			
			if($reqStatusSurat == "REVISI")
			{
				$statusup=2;
			}
			if($reqStatusSurat == "SETUJU")
			{
				$statusup=1;
			}

			// print_r($statusup);exit;


			$status = new PermohonanStpd();
			$status->setField("PERMOHONAN_STPD_ID", $reqId);
			$status->setField("SATUAN_KERJA_ID", $this->SATUAN_KERJA_ID_ASAL);
			$status->setField("STATUS", $statusup);
			if($status->updatestatusdetil())
			{
				// print_r($statusup);exit;

				$reqSimpan=1;
			}
		}
		else
		{
			if ($reqMode == "insert") {
				$set->setField("LAST_CREATE_USER", $this->ID);
				if($set->insert())
				{
					$reqId = $set->id;
					$reqSimpan=1;
				}

			} 
			else
			{
				$set->setField("LAST_UPDATE_USER", $this->ID);
				if($set->update())
				{
					$reqSimpan=1;
				}
			}

		}
		
		if($reqStatusSurat == "DRAFT" || $reqStatusSurat == "REVISI")
		{

			if(!empty($reqAlokasi) && !empty($reqId))
			{	
				$reqSimpan=0;
				$setdelete = new PermohonanStpd();
				$setdelete->setField("PERMOHONAN_STPD_ID", $reqId);
				$setdelete->deletebiaya();
				foreach ($reqAlokasi as $key => $value) {
					$setinsert = new PermohonanStpd();
					$setinsert->setField("PERMOHONAN_STPD_ID", $reqId);
					$setinsert->setField("ALOKASI_BIAYA", $value);
					$setinsert->setField("PENGAJUAN_BIAYA", ValToNullDB($reqPengajuan[$key]));
					$setinsert->setField("REALISASI", ValToNullDB($reqRealisasi[$key]));
					if($setinsert->insertbiaya())
					{
						$reqSimpan=1;
					}
				}
			}


			if(!empty($reqSatuanKerjaIdTujuan))
			{	
			// $setdelete = new PermohonanStpd();
			// $setdelete->setField("PERMOHONAN_STPD_ID", $reqId);
			// $setdelete->deleteuntuk();

				for ($i = 0; $i < count($reqSatuanKerjaIdTujuan); $i++) {
					if ($reqSatuanKerjaIdTujuan[$i] == "") {
					} else 
					{
						
						$reqSimpan=0;
						$setinsert = new PermohonanStpd();
						$setinsert->setField("PERMOHONAN_STPD_ID", $reqId);
						$setinsert->setField("SATUAN_KERJA_ID", $reqSatuanKerjaIdTujuan[$i]);
						$setinsert->setField("PERMOHONAN_STPD_UNTUK_ID", $reqUntukId[$i]);
						$setinsert->setField("STATUS", ValToNullDB($req));
						if(empty($reqUntukId[$i]))
						{
							if($setinsert->insertuntuk())
							{
								$reqSimpan=1;
							}
						}
						else
						{
							if($setinsert->updateuntuk())
							{
								$reqSimpan=1;
							}
						}

					}
				}

			}

		}
		

		$inforeturninfo="";
		if ($reqSimpan==1 ) {
			if($reqStatusSurat == "KIRIM")
			{
				$inforeturninfo= "Naskah berhasil dikirim.";
			}
			else
			{
				if($reqStatusSurat == "REVISI")
				{
					$inforeturninfo= "Naskah berhasil dikembalikan.";
				}
				else if($reqStatusSurat == "SETUJU")
				{
					$inforeturninfo= "Naskah berhasil disetujui.";
				}
				else if($reqStatusSurat == "DRAFT")
				{
					$inforeturninfo= "Naskah berhasil disimpan sebagai DRAFT.";
				}
				else if($reqStatusSurat == "SELESAI")
				{
					$inforeturninfo= "Naskah berhasil disetujui";
				}
			}	
		}
		else
		{
			$inforeturninfo= "Data gagal disimpan";	
		}

		echo $reqId."-".$inforeturninfo;

	}

	function delete()
	{
		$reqId	= $this->input->get('reqId');
		$this->load->model("PermohonanStpd");
		$set = new PermohonanStpd();

		$set->setField("PERMOHONAN_STPD_ID", $reqId);
		if ($set->delete())
			$arrJson["PESAN"] = "Data berhasil dihapus.";
		else
			$arrJson["PESAN"] = "Data gagal dihapus.";

		echo json_encode($arrJson);
	}

	function deletebiaya()
	{
		$reqId	= $this->input->get('reqId');
		$this->load->model("PermohonanStpd");
		$set = new PermohonanStpd();

		$set->setField("PERMOHONAN_STPD_BIAYA_DINAS_ID", $reqId);
		if ($set->deletebiayabaris())
			$arrJson["PESAN"] = "Data berhasil dihapus.";
		else
			$arrJson["PESAN"] = "Data gagal dihapus.";

		echo json_encode($arrJson);
	}

}