<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class histori_surat_json extends CI_Controller
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


	function jsonsuratmasuk()
	{
		$this->load->model("HistoriSurat");

		$set= new HistoriSurat();

		$reqJenisNaskahId= $this->input->get("reqJenisNaskahId");
		$reqTahun= $this->input->get("reqTahun");
		$reqPilihan= $this->input->get("reqPilihan");
		$reqStatusSurat= $this->input->get("reqStatusSurat");
		// echo $reqPilihan;exit();

		$aColumns = array("TIPE_NAMA", "NOMOR_SURAT_KONVERSI", "PEMBUAT_NAMA", "NAMA_TUJUAN", "PERIHAL", "TANGGAL_SURAT", "SURAT_OLD_ID");
		$aColumnsAlias = $aColumns;

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
			if (trim($sOrder) == "ORDER BY A.SURAT_MASUK_ID asc") {
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

		$reqPencarian= $this->input->get("reqPencarian");
		$searchJson= " 
		AND 
		(
			UPPER(A.NOMOR_SURAT) LIKE '%".strtoupper($reqPencarian)."%' OR 
			UPPER(A.NOMOR_SURAT_KONVERSI) LIKE '%".strtoupper($reqPencarian)."%' OR 
			UPPER(A.PERIHAL) LIKE '%".strtoupper($reqPencarian)."%' OR 
			UPPER(A.PEMBUAT_NIP) LIKE '%".strtoupper($reqPencarian)."%' OR 
			UPPER(A.PEMBUAT_NAMA) LIKE '%".strtoupper($reqPencarian)."%' OR
			(EXISTS (SELECT 1 FROM surat_old_tujuan XXX WHERE UPPER(nama) LIKE '%".strtoupper($reqPencarian)."%' AND A.SURAT_OLD_ID = XXX.SURAT_OLD_ID))
		)";
		// OR UPPER(ambilhistorikepada(A.SURAT_OLD_ID)) LIKE '%".strtoupper($reqPencarian)."%'
		
		if(!empty($reqJenisNaskahId))
		{
			$statement.= " AND A.TIPE_SURAT IN ('".$reqJenisNaskahId."')";
		}

		if(!empty($reqTahun))
		{
			$statement.= " AND A.TAHUN = ".$reqTahun;
		}

		$allRecord = $set->getCountByParams(array(), $statement);
		// echo $allRecord;exit;
		if ($reqPencarian == "")
			$allRecordFilter = $allRecord;
		else
			$allRecordFilter =  $set->getCountByParams(array(), $statement.$searchJson);

		// $sOrder= "";
		$sOrder = " ORDER BY A.TANGGAL_SURAT DESC NULLS LAST";
		$set->selectByParams(array(), $dsplyRange, $dsplyStart, $statement.$searchJson, $sOrder);
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
			$infojenisnaskahid= $set->getField("JENIS_NASKAH_ID");
			$row = array();

			for ($i = 0; $i < count($aColumns); $i++) {
				if ($aColumns[$i] == "KETERANGAN")
					$row[] = truncate($set->getField($aColumns[$i]), 2);
				else if ($aColumns[$i] == "TANGGAL_SURAT")
					$row[] = dateToPageCheck($set->getField($aColumns[$i]));
				else if ($aColumns[$i] == "PEMBUAT_NAMA")
				{
					$infopembuatnip= $set->getField("PEMBUAT_NIP");
					$infopembuatnama= $set->getField("PEMBUAT_NAMA");

					if(!empty($infopembuatnip))
						$infopembuatnama= $infopembuatnip." - ".$infopembuatnama;

					$row[] = $infopembuatnama;
				}
				else
					$row[] = $set->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		echo json_encode($output);
	}

}