<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class coba_satuan_kerja_json extends CI_Controller
{

	function __construct()
	{
		parent::__construct();

		if (!$this->kauth->getInstance()->hasIdentity()) {
			redirect('login');
		}

		$this->db->query("SET DATESTYLE TO PostgreSQL,European;");
		$this->ID = $this->kauth->getInstance()->getIdentity()->ID;
		$this->NAMA = $this->kauth->getInstance()->getIdentity()->NAMA;
		$this->JABATAN = $this->kauth->getInstance()->getIdentity()->JABATAN;
		$this->HAK_AKSES = $this->kauth->getInstance()->getIdentity()->HAK_AKSES;
		$this->LAST_LOGIN = $this->kauth->getInstance()->getIdentity()->LAST_LOGIN;
		$this->USERNAME = $this->kauth->getInstance()->getIdentity()->USERNAME;
		$this->USER_LOGIN_ID = $this->kauth->getInstance()->getIdentity()->USER_LOGIN_ID;
		$this->USER_GROUP = $this->kauth->getInstance()->getIdentity()->USER_GROUP;
		$this->CABANG_ID = $this->kauth->getInstance()->getIdentity()->CABANG_ID;
		$this->CABANG = $this->kauth->getInstance()->getIdentity()->CABANG;
		$this->SATUAN_KERJA_ID_ASAL	= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_ID_ASAL;
		$this->SATUAN_KERJA_ASAL	= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_ASAL;
		$this->SATUAN_KERJA_HIRARKI	= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_HIRARKI;
		$this->SATUAN_KERJA_JABATAN	= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_JABATAN;
		$this->KD_LEVEL = $this->kauth->getInstance()->getIdentity()->KD_LEVEL;
		$this->KD_LEVEL_PEJABAT = $this->kauth->getInstance()->getIdentity()->KD_LEVEL_PEJABAT;
		$this->KELOMPOK_JABATAN = $this->kauth->getInstance()->getIdentity()->KELOMPOK_JABATAN;
		$this->KODE_PARENT = $this->kauth->getInstance()->getIdentity()->KODE_PARENT;

		$this->TREETABLE_COUNT = 0;
	}

	function json()
	{
		$this->load->model("SatuanKerja");
		$satuan_kerja = new SatuanKerja();

		$reqStatus = $this->input->get("reqStatus");
		// echo $reqStatus;exit;

		$aColumns = array("SATUAN_KERJA_ID", "NAMA", "STATUS_AKTIF_DESC");
		$aColumnsAlias	= array("SATUAN_KERJA_ID",  "NAMA", "STATUS_AKTIF_DESC");


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
			if (trim($sOrder) == "ORDER BY SATUAN_KERJA_ID asc") {
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY SATUAN_KERJA_ID asc";
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

		$statement_privacy .= "  AND STATUS_AKTIF = '" . $reqStatus . "' ";

		$statement_privacy .= "  AND SATUAN_KERJA_ID_PARENT = 'SATKER'";

		$statement = " AND (UPPER(NAMA) LIKE '%" . strtoupper($_GET['sSearch']) . "%')";
		$allRecord = $satuan_kerja->getCountByParams(array(), $statement_privacy . $statement);
		// echo $allRecord;
		// exit;
		if ($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else
			$allRecordFilter =  $satuan_kerja->getCountByParams(array(), $statement_privacy . $statement);

		$satuan_kerja->selectByParams(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);
		// echo $satuan_kerja->query;exit;

		/*
			 * Output 
			 */
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $allRecord,
			"iTotalDisplayRecords" => $allRecordFilter,
			"aaData" => array()
		);

		while ($satuan_kerja->nextRow()) {
			$row = array();
			for ($i = 0; $i < count($aColumns); $i++) {
				if ($aColumns[$i] == "KETERANGAN")
					$row[] = truncate($satuan_kerja->getField($aColumns[$i]), 2);
				else
					$row[] = $satuan_kerja->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		echo json_encode($output);
	}



	function json_jabatan()
	{
		$this->load->model("SatuanKerja");
		$satuan_kerja = new SatuanKerja();

		$reqSatkerId = $this->input->get("reqSatkerId");


		// echo $reqKategori;exit;

		$aColumns		= array("SATUAN_KERJA_ID", "NAMA", "NAMA_PEGAWAI", "NIP", "KODE_SURAT");
		$aColumnsAlias	= array("SATUAN_KERJA_ID", "NAMA", "NAMA_PEGAWAI", "NIP", "KODE_SURAT");


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
			if (trim($sOrder) == "ORDER BY SATUAN_KERJA_ID asc") {
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY KODE_SO asc";
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

		$statement_privacy .= "  AND SATUAN_KERJA_ID_PARENT = '" . $reqSatkerId . "' ";

		$statement = " AND (UPPER(NAMA) LIKE '%" . strtoupper($_GET['sSearch']) . "%')";
		$allRecord = $satuan_kerja->getCountByParams(array(), $statement_privacy . $statement);
		// echo $allRecord;exit;
		if ($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else
			$allRecordFilter =  $satuan_kerja->getCountByParams(array(), $statement_privacy . $statement);

		$satuan_kerja->selectByParams(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);

		// echo $satuan_kerja->query;exit;

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

		while ($satuan_kerja->nextRow()) {
			$row = array();
			for ($i = 0; $i < count($aColumns); $i++) {
				if ($aColumns[$i] == "KETERANGAN")
					$row[] = truncate($satuan_kerja->getField($aColumns[$i]), 2);
				else
					$row[] = $satuan_kerja->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		echo json_encode($output);
	}


	function change_status()
	{
		$this->load->model("SatuanKerja");
		$satuan_kerja = new SatuanKerja();


		$reqId 		= $this->input->get("reqId");
		$reqStatus	= $this->input->get("reqStatus");


		$satuan_kerja->setField("SATUAN_KERJA_ID", $reqId);
		$satuan_kerja->setField("FIELD", "STATUS_AKTIF");
		$satuan_kerja->setField("FIELD_VALUE", $reqStatus);
		$satuan_kerja->setField("LAST_UPDATE_USER", $this->USERNAME);
		$satuan_kerja->updateByField();

		echo "Data berhasil diubah.";
	}


	function add_kode_surat()
	{
		$this->load->model("SatuanKerja");
		$satuan_kerja = new SatuanKerja();


		$reqMode = $this->input->post("reqMode");
		$reqId = $this->input->post("reqId");

		$reqNama = $this->input->post("reqNama");
		$reqKodeSurat = $this->input->post("reqKodeSurat");

		$satuan_kerja->setField("SATUAN_KERJA_ID", $reqId);
		$satuan_kerja->setField("FIELD", "KODE_SURAT");
		$satuan_kerja->setField("FIELD_VALUE", strtoupper(trim($reqKodeSurat)));
		$satuan_kerja->setField("LAST_UPDATE_USER", $this->USERNAME);
		$satuan_kerja->updateByField();

		echo "Data berhasil disimpan.";
	}

	function add_satker()
	{
		// include_once("functions/excel_reader2.php");

		include "libraries/excel/excel_reader2.php";

		$uploads_dir = 'asset';
		if ($_FILES["reqLinkFile"]["error"] == UPLOAD_ERR_OK) {
			$tmp_name = $_FILES["reqLinkFile"]["tmp_name"];
			// basename() may prevent filesystem traversal attacks;
			// further validation/sanitation of the filename may be appropriate
			$name = time() . "." . getExtension($_FILES["reqLinkFile"]["name"]);
			$moved = move_uploaded_file($tmp_name, "$uploads_dir/$name");
		}

		// echo ("$uploads_dir/$name");
		// exit;

		$data = new Spreadsheet_Excel_Reader(realpath("$uploads_dir/$name"));

		$this->load->model("SatuanKerja");

		$baris = $data->rowcount($sheet_index = 0);

		for ($i = 2; $i <= $baris; $i++) {
			$reqId = $data->val($i, 1);
			$reqJabatan = $data->val($i, 3);
			$reqKodeSurat = $data->val($i, 4);
			$reqKelompokJabatan	= $data->val($i, 5);


			$satuan_kerja = new SatuanKerja();
			$satuan_kerja->setField("SATUAN_KERJA_ID", $reqId);
			$satuan_kerja->setField("FIELD1", "KODE_SURAT");
			$satuan_kerja->setField("FIELD_VALUE1", (trim($reqKodeSurat)));
			$satuan_kerja->setField("FIELD2", "KELOMPOK_JABATAN");
			$satuan_kerja->setField("FIELD_VALUE2", (trim($reqKelompokJabatan)));
			$satuan_kerja->setField("FIELD3", "JABATAN");
			$satuan_kerja->setField("FIELD_VALUE3", (trim($reqJabatan)));
			$satuan_kerja->setField("LAST_UPDATE_USER", $this->USERNAME);
			$satuan_kerja->updateByField3();
			unset($satuan_kerja);
		}

		echo "Data berhasil diimport.";
	}

	function sinkronisasi()
	{
		$this->load->model("SatuanKerja");
		$satuan_kerja = new SatuanKerja();

		//$str = "011";
		//echo substr($str, 0,3);
		//echo substr($str, 3,1);
		//return;

		$url = "https://hcis.angkasapura1.co.id/API/orgstruct.cfm";
		$json = file_get_contents($url, true);
		$arr = json_decode($json, true);

		$satuan_kerja->deleteHCIS();

		for ($i = 0; $i < count($arr["LEVEL1"]); $i++) {
			$satuan_kerja = new SatuanKerja();

			$positionCode = $arr["LEVEL1"][$i]["POSITION_CODE"];

			if (is_numeric(substr($positionCode, 3, 1))) {
				$noSurat = substr($positionCode, 0, 3);
				$noSuratInternal = substr($positionCode, 0, 5);
			} else {
				$noSurat = substr($positionCode, 0, 4);
				$noSuratInternal = substr($positionCode, 0, 6);
			}

			$satuan_kerja->setField("SATUAN_KERJA_ID", $noSurat);
			$satuan_kerja->setField("SATUAN_KERJA_ID_PARENT", "SATKER");

			if ($noSurat == "01")
				$satuan_kerja->setField("URUT", "1");
			else
				$satuan_kerja->setField("URUT", "NULL");


			$satuan_kerja->setField("NAMA", $arr["LEVEL1"][$i]["POSITION_NAME"]);
			$satuan_kerja->setField("NAMA_PEGAWAI", $arr["LEVEL1"][$i]["POSITION_NAME"]);
			$satuan_kerja->setField("TREE_ID", $positionCode);
			$satuan_kerja->setField("TREE_PARENT", "0");
			$satuan_kerja->setField("KODE_LEVEL", "LEVEL1");
			$satuan_kerja->setField("KODE_SURAT",  $noSuratInternal);
			$satuan_kerja->setField("KODE_SURAT_KELUAR", $noSurat);


			$satuan_kerja->setField("LAST_CREATE_USER", $this->USERNAME);
			$satuan_kerja->insertHCIS();
			$this->sinkronisasi_detil($positionCode, $arr["LEVEL1"][$i]["LEVEL2"], 3);
		}


		$satuan_kerja = new SatuanKerja();
		$satuan_kerja->updateNama();

		echo "Sinkronisasi berhasil.";
	}

	function sinkronisasi_detil($parentCode, $arrData, $level)
	{

		$this->load->model("SatuanKerja");
		$satuan_kerja = new SatuanKerja();

		for ($i = 0; $i < count($arrData); $i++) {
			$satuan_kerja = new SatuanKerja();

			$positionCode = $arrData[$i]["POSITION_CODE"];

			if (is_numeric(substr($positionCode, 3, 1))) {
				$noSurat = substr($positionCode, 0, 3);
				$noSuratInternal = substr($positionCode, 0, 5);
			} else {
				$noSurat = substr($positionCode, 0, 4);
				$noSuratInternal = substr($positionCode, 0, 6);
			}


			if (($level - 1) == "2") {
				$satuan_kerja->setField("SATUAN_KERJA_ID", $positionCode);
				$satuan_kerja->setField("SATUAN_KERJA_ID_PARENT", "SATKER");

				if ($positionCode == "01")
					$satuan_kerja->setField("URUT", "1");
				else
					$satuan_kerja->setField("URUT", "NULL");
			} else {
				$satuan_kerja->setField("URUT", "NULL");
				$satuan_kerja->setField("SATUAN_KERJA_ID", $positionCode);
				$satuan_kerja->setField("SATUAN_KERJA_ID_PARENT", $noSurat);
				$satuan_kerja->setField("KODE_SO", $positionCode);

				if ($parentCode == $noSurat)
					$satuan_kerja->setField("KODE_PARENT", "0");
				else
					$satuan_kerja->setField("KODE_PARENT", $parentCode);
			}


			$satuan_kerja->setField("NAMA", $arrData[$i]["POSITION_NAME"]);
			$satuan_kerja->setField("NAMA_PEGAWAI", $arrData[$i]["POSITION_NAME"]);
			$satuan_kerja->setField("TREE_ID", $positionCode);
			$satuan_kerja->setField("TREE_PARENT", $parentCode);
			$satuan_kerja->setField("KODE_LEVEL", "LEVEL" . ($level - 1));
			$satuan_kerja->setField("KODE_SURAT",  $noSuratInternal);
			$satuan_kerja->setField("KODE_SURAT_KELUAR", $noSurat);
			$satuan_kerja->setField("LAST_CREATE_USER", $this->USERNAME);
			$satuan_kerja->insertHCIS();
			$this->sinkronisasi_detil($positionCode, $arrData[$i]["LEVEL" . $level], $level + 1);
		}
	}

	function combo()
	{
		$reqPencarian = $this->input->get("q");

		$this->load->model("SatuanKerja");
		$satuan_kerja = new SatuanKerja();

		if ($reqPencarian == "") {
		} else
			$statement = " AND (UPPER(A.NAMA) LIKE '%" . strtoupper($reqPencarian) . "%' OR UPPER(A.JABATAN) LIKE '%" . strtoupper($reqPencarian) . "%' OR UPPER(A.NAMA_PEGAWAI) LIKE '%" . strtoupper($reqPencarian) . "%') ";

		$satuan_kerja->selectByParams(array("NOT SATUAN_KERJA_ID_PARENT" => "SATKER", "SATUAN_KERJA_ID_PARENT" => $this->CABANG_ID), -1, -1, $statement, " ORDER BY A.URUT ASC ");
		// echo $satuan_kerja->query;exit;
		$i = 0;
		while ($satuan_kerja->nextRow()) {
			$arr_json[$i]['id']					= $satuan_kerja->getField("SATUAN_KERJA_ID");
			$arr_json[$i]['text']				= $satuan_kerja->getField("NAMA");
			$arr_json[$i]['SATUAN_KERJA_ID']	= $satuan_kerja->getField("SATUAN_KERJA_ID");
			$arr_json[$i]['SATUAN_KERJA']		= $satuan_kerja->getField("NAMA");
			$arr_json[$i]['NAMA']				= $satuan_kerja->getField("NAMA");
			$arr_json[$i]['JABATAN']			= coalesce($satuan_kerja->getField("JABATAN"), $satuan_kerja->getField("NAMA"));
			$arr_json[$i]['NAMA_PEGAWAI']		= $satuan_kerja->getField("NAMA_PEGAWAI");
			$arr_json[$i]['NIP']				= $satuan_kerja->getField("NIP");
			$arr_json[$i]['JABATAN']			= $satuan_kerja->getField("JABATAN");
			$i++;
		}

		echo json_encode($arr_json);
	}



	function combo_level()
	{
		$reqId = $this->input->get("reqId");

		$this->load->model("SatuanKerja");
		$satuan_kerja = new SatuanKerja();

		// $satuan_kerja->selectByParamsHirarki($this->SATUAN_KERJA_ID_ASAL, 
		// 					" AND EXISTS(SELECT 1 FROM (SELECT TRIM(UPPER(regexp_split_to_table('".$reqId."', ','))) AS KD_LEVEL) X 
		// 					 WHERE X.KD_LEVEL = A.KELOMPOK_JABATAN) ");
		$satuan_kerja->selectByParamsHirarki($this->SATUAN_KERJA_ID_ASAL);
		// echo $satuan_kerja->query;exit;
		$i = 0;

		$arr_json = array();
		while ($satuan_kerja->nextRow()) {
			$arr_json[$i]['id']					= $satuan_kerja->getField("SATUAN_KERJA_ID");
			if ($satuan_kerja->getField("KODE_SURAT") == "")
				$arr_json[$i]['text']				= $satuan_kerja->getField("NAMA");
			else
				$arr_json[$i]['text']				= $satuan_kerja->getField("NAMA") . " (" . $satuan_kerja->getField("KODE_SURAT") . ")";

			$arr_json[$i]['SATUAN_KERJA_ID']	= $satuan_kerja->getField("SATUAN_KERJA_ID");
			$arr_json[$i]['SATUAN_KERJA']		= $satuan_kerja->getField("NAMA");
			$arr_json[$i]['NAMA']				= $satuan_kerja->getField("NAMA");
			$arr_json[$i]['NAMA_PEGAWAI']		= $satuan_kerja->getField("NAMA_PEGAWAI");
			$arr_json[$i]['NIP']				= $satuan_kerja->getField("NIP");
			$arr_json[$i]['JABATAN']			= $satuan_kerja->getField("JABATAN");
			$arr_json[$i]['KODE_LEVEL']			= $satuan_kerja->getField("KODE_LEVEL");
			$i++;
		}

		echo json_encode($arr_json);
	}


	function combo_paraf()
	{
		$reqId = $this->input->get("reqId");

		$this->load->model("SatuanKerja");
		$satuan_kerja = new SatuanKerja();

		// $satuan_kerja->selectByParamsHirarkiSO($reqId, " AND NOT A.SATUAN_KERJA_ID = '".$reqId."' ");
		// $satuan_kerja->selectByParamsHirarkiSO2($reqId);
		$satuan_kerja->selectByParamsHirarkiSO2($reqId, " AND SATUAN_KERJA_ID NOT IN ('".$reqId."') ");
		
		// echo $satuan_kerja->query;exit;
		// exit;
		$i = 0;

		$arr_json = array();
		while ($satuan_kerja->nextRow()) {
			if ($satuan_kerja->getField("NIP") == "" || $satuan_kerja->getField("NIP") == $this->ID) {
			} else {
				$arr_json[$i]['id']					= $satuan_kerja->getField("SATUAN_KERJA_ID");
				if ($satuan_kerja->getField("KODE_SURAT") == "")
					$arr_json[$i]['text']				= $satuan_kerja->getField("JABATAN");
				else
					$arr_json[$i]['text']				= $satuan_kerja->getField("JABATAN") . " (" . $satuan_kerja->getField("NAMA_PEGAWAI") . ")";

				$arr_json[$i]['SATUAN_KERJA_ID']	= $satuan_kerja->getField("SATUAN_KERJA_ID");
				$arr_json[$i]['SATUAN_KERJA']		= $satuan_kerja->getField("NAMA");
				$arr_json[$i]['NAMA']				= $satuan_kerja->getField("NAMA");
				$arr_json[$i]['NAMA_PEGAWAI']		= $satuan_kerja->getField("NAMA_PEGAWAI");
				$arr_json[$i]['NIP']				= $satuan_kerja->getField("NIP");
				$arr_json[$i]['JABATAN']			= $satuan_kerja->getField("JABATAN");
				$i++;
			}
		}

		echo json_encode($arr_json);
	}


	function combo_request_nomor()
	{
		$reqId = $this->input->get("reqId");
		$reqPenerbit = $this->input->get("reqPenerbit");


		$this->load->model("SatuanKerja");
		$satuan_kerja = new SatuanKerja();

		$satuan_kerja->selectByParamsHirarkiJabatan(
			$this->SATUAN_KERJA_ID_ASAL,
			" AND EXISTS(SELECT 1 FROM (SELECT TRIM(UPPER(regexp_split_to_table('" . $reqId . "', ','))) AS KD_LEVEL) X 
							 WHERE X.KD_LEVEL = A.KELOMPOK_JABATAN)"
		);
		// echo $satuan_kerja->query;exit;
		$i = 0;

		$arr_json = array();
		while ($satuan_kerja->nextRow()) {
			$arr_json[$i]['id']					= $satuan_kerja->getField("SATUAN_KERJA_ID");
			if ($satuan_kerja->getField("KODE_SURAT") == "")
				$arr_json[$i]['text']				= $satuan_kerja->getField("NAMA");
			else
				$arr_json[$i]['text']				= $satuan_kerja->getField("NAMA") . " (" . $satuan_kerja->getField("KODE_SURAT") . ")";

			$arr_json[$i]['SATUAN_KERJA_ID']	= $satuan_kerja->getField("SATUAN_KERJA_ID");
			$arr_json[$i]['SATUAN_KERJA']		= $satuan_kerja->getField("NAMA");
			$arr_json[$i]['NAMA']				= $satuan_kerja->getField("NAMA");
			$arr_json[$i]['NAMA_PEGAWAI']		= $satuan_kerja->getField("NAMA_PEGAWAI");
			$arr_json[$i]['NIP']				= $satuan_kerja->getField("NIP");
			$arr_json[$i]['JABATAN']			= $satuan_kerja->getField("JABATAN");
			$arr_json[$i]['PENERBIT']			= $reqPenerbit;

			if ($reqPenerbit == "TATAUSAHA")
				$arr_json[$i]['PENERBIT_NOMOR']		= "Tata Usaha " . $this->CABANG;
			else
				$arr_json[$i]['PENERBIT_NOMOR']		= "Sekretaris " . $satuan_kerja->getField("JABATAN");

			$i++;
		}

		echo json_encode($arr_json);
	}


	function teruskan()
	{
		if ($reqUnitKerjaId == "")
			$reqUnitKerjaId = $this->CABANG_ID;

		$this->load->model("SatuanKerja");
		$satuan_kerja = new SatuanKerja();
		$this->load->model("Pegawai");
		$pegawai = new Pegawai();


		$i = 0;
		$arr_json = array();

		if ($this->KELOMPOK_JABATAN == "DIRUT" || $this->KELOMPOK_JABATAN == "DIREKSI") 
		{
			$arrStatement = array("COALESCE(NULLIF(KODE_PARENT, ''), '0')" => "0", "NOT SATUAN_KERJA_ID_PARENT" => "SATKER", "SATUAN_KERJA_ID_PARENT" => $reqUnitKerjaId);
			$statement = " AND A.KODE_LEVEL = '" . $this->KD_LEVEL_PEJABAT . "' ";
		} 
		else 
		{
			// $arrStatement = array("NOT SATUAN_KERJA_ID_PARENT" => "SATKER", "SATUAN_KERJA_ID_PARENT" => $reqUnitKerjaId, "COALESCE(NULLIF(KODE_PARENT, ''), '0')" => $this->KODE_PARENT);
			// $statement = " AND A.KODE_LEVEL = '" . $this->KD_LEVEL . "' ";

			// tambahan khusus
			$arrStatement = array("NOT SATUAN_KERJA_ID_PARENT" => "SATKER", "SATUAN_KERJA_ID_PARENT" => $reqUnitKerjaId, "COALESCE(NULLIF(KODE_PARENT, ''), '0')" => $this->KODE_PARENT, "NOT SATUAN_KERJA_ID" => $this->SATUAN_KERJA_ID_ASAL);

			//$statement = " AND A.SATUAN_KERJA_ID = '".$this->SATUAN_KERJA_ID_ASAL."' ";				
		}

		$satuan_kerja->selectByParams($arrStatement, -1, -1, $statement . $statement_privacy, " ORDER BY KODE_SO ASC ");
		// echo $satuan_kerja->query;exit;
		while ($satuan_kerja->nextRow()) {
			$arr_json[$i]['id']					= $satuan_kerja->getField("KODE_SO");
			$arr_json[$i]['text']				= coalesce($satuan_kerja->getField("JABATAN"), $satuan_kerja->getField("NAMA")) . " - " . $satuan_kerja->getField("NAMA_PEGAWAI");
			$arr_json[$i]['SATUAN_KERJA_ID']	= $satuan_kerja->getField("SATUAN_KERJA_ID");
			$arr_json[$i]['SATUAN_KERJA']		= $satuan_kerja->getField("NAMA");
			$arr_json[$i]['NAMA']				= $satuan_kerja->getField("NAMA");
			$arr_json[$i]['NAMA_PEGAWAI']		= $satuan_kerja->getField("NAMA_PEGAWAI");
			$arr_json[$i]['NIP']				= $satuan_kerja->getField("NIP");

			if ($this->SATUAN_KERJA_ID_ASAL == $satuan_kerja->getField("SATUAN_KERJA_ID"))
				$arr_json[$i]['children']			= $this->teruskan_children($satuan_kerja->getField("KODE_SO"), $satuan_kerja->getField("SATUAN_KERJA_ID_PARENT"));
			else
			{
				$arr_json[$i]['children']= $this->teruskansatuanak($satuan_kerja->getField("KODE_SO"), $satuan_kerja->getField("SATUAN_KERJA_ID_PARENT"));
			}

			$i++;
		}

		if ($i == 0) {
			$pegawai->selectByParamsMonitoring(array("A.DEPARTEMEN_ID" => $this->SATUAN_KERJA_ID_ASAL), -1, -1, " AND NOT EXISTS(SELECT 1 FROM SATUAN_KERJA X WHERE X.NIP = A.NIP) ");
			while ($pegawai->nextRow()) {


				$arr_json[$i]['id']					= "PEGAWAI" . $pegawai->getField("PEGAWAI_ID");
				$arr_json[$i]['text']				= $pegawai->getField("JABATAN") . " - " . $pegawai->getField("NAMA");
				$arr_json[$i]['SATUAN_KERJA_ID']	= $pegawai->getField("DEPARTEMEN_ID");
				$arr_json[$i]['SATUAN_KERJA']		= $pegawai->getField("DEPARTEMEN");
				$arr_json[$i]['NAMA']				= $pegawai->getField("DEPARTEMEN");
				$arr_json[$i]['NAMA_PEGAWAI']		= $pegawai->getField("NAMA");
				$arr_json[$i]['NIP']				= $pegawai->getField("NIP");

				$i++;
			}
		}
		else
		{
			// tambahan khusus
			if ($this->KELOMPOK_JABATAN == "DIRUT" || $this->KELOMPOK_JABATAN == "DIREKSI"){}
			else
			{
				$setdetil= new SatuanKerja();
				$setdetil->selectByParams(array(), -1,-1, " AND A.SATUAN_KERJA_ID = '".$this->SATUAN_KERJA_ID_ASAL."'", "ORDER BY A.SATUAN_KERJA_ID");
				// $setdetil->query;exit;
				$setdetil->firstRow();
				$infokodeso= $setdetil->getField("KODE_SO");
				// echo $infokodeso;exit;

				if(!empty($infokodeso))
				{
					$arrStatement = array("COALESCE(NULLIF(KODE_PARENT, ''), '0')" => $infokodeso, "NOT SATUAN_KERJA_ID_PARENT" => "SATKER");

					$setdetil->selectByParams($arrStatement, -1, -1, $statement, " ORDER BY KODE_SO ASC ");
					// echo $setdetil->query;exit;
					while ($setdetil->nextRow()) 
					{
						$arr_json[$i]['id']					= $setdetil->getField("KODE_SO");
						$arr_json[$i]['text']				= coalesce($setdetil->getField("JABATAN"), $setdetil->getField("NAMA")) . " - " . $setdetil->getField("NAMA_PEGAWAI");
						$arr_json[$i]['SATUAN_KERJA_ID']	= $setdetil->getField("SATUAN_KERJA_ID");
						$arr_json[$i]['SATUAN_KERJA']		= $setdetil->getField("NAMA");
						$arr_json[$i]['NAMA']				= $setdetil->getField("NAMA");
						$arr_json[$i]['NAMA_PEGAWAI']		= $setdetil->getField("NAMA_PEGAWAI");
						$arr_json[$i]['NIP']				= $setdetil->getField("NIP");
						$i++;
					}
				}
			}
		}


		echo json_encode($arr_json);
	}

	function teruskansatuanak($infokodeso, $satkerId)
	{
		// tambahan khusus
		$setdetil= new SatuanKerja();
		if ($this->KELOMPOK_JABATAN == "DIRUT" || $this->KELOMPOK_JABATAN == "DIREKSI"){}
		else
		{
			if(!empty($infokodeso))
			{
				$i = 0;
				$arr_json = array();
				$arrStatement = array("COALESCE(NULLIF(KODE_PARENT, ''), '0')" => $infokodeso, "NOT SATUAN_KERJA_ID_PARENT" => "SATKER");

				$setdetil->selectByParams($arrStatement, -1, -1, $statement, " ORDER BY KODE_SO ASC ");
				// echo $setdetil->query;exit;
				while ($setdetil->nextRow()) 
				{
					$arr_json[$i]['id']					= $setdetil->getField("KODE_SO");
					$arr_json[$i]['text']				= coalesce($setdetil->getField("JABATAN"), $setdetil->getField("NAMA")) . " - " . $setdetil->getField("NAMA_PEGAWAI");
					$arr_json[$i]['SATUAN_KERJA_ID']	= $setdetil->getField("SATUAN_KERJA_ID");
					$arr_json[$i]['SATUAN_KERJA']		= $setdetil->getField("NAMA");
					$arr_json[$i]['NAMA']				= $setdetil->getField("NAMA");
					$arr_json[$i]['NAMA_PEGAWAI']		= $setdetil->getField("NAMA_PEGAWAI");
					$arr_json[$i]['NIP']				= $setdetil->getField("NIP");
					$i++;
				}
				return $arr_json;
			}
		}
	}

	function teruskan_children($id, $satkerId)
	{

		$this->load->model("SatuanKerja");
		$satuan_kerja = new SatuanKerja();
		$this->load->model("Pegawai");
		$pegawai = new Pegawai();


		$i = 0;
		$arr_json = array();

		$pegawai->selectByParamsMonitoring(array("A.DEPARTEMEN_ID" => $id), -1, -1, " AND NOT EXISTS(SELECT 1 FROM SATUAN_KERJA X WHERE X.NIP = A.NIP) ");
		// echo $pegawai->query;exit;
		while ($pegawai->nextRow()) {


			$arr_json[$i]['id']					= "PEGAWAI" . $pegawai->getField("PEGAWAI_ID");
			$arr_json[$i]['text']				= $pegawai->getField("JABATAN") . " - " . $pegawai->getField("NAMA");
			$arr_json[$i]['SATUAN_KERJA_ID']	= $pegawai->getField("DEPARTEMEN_ID");
			$arr_json[$i]['SATUAN_KERJA']		= $pegawai->getField("DEPARTEMEN");
			$arr_json[$i]['NAMA']				= $pegawai->getField("DEPARTEMEN");
			$arr_json[$i]['NAMA_PEGAWAI']		= $pegawai->getField("NAMA");
			$arr_json[$i]['NIP']				= $pegawai->getField("NIP");

			$i++;
		}



		$arrStatement = array("COALESCE(NULLIF(KODE_PARENT, ''), '0')" => $id, "NOT SATUAN_KERJA_ID_PARENT" => "SATKER", "SATUAN_KERJA_ID_PARENT" => $satkerId);

		$satuan_kerja->selectByParams($arrStatement, -1, -1, $statement, " ORDER BY KODE_SO ASC ");
		//echo $satuan_kerja->query;exit;
		while ($satuan_kerja->nextRow()) {

			$arr_json[$i]['id']					= $satuan_kerja->getField("KODE_SO");
			$arr_json[$i]['text']				= coalesce($satuan_kerja->getField("JABATAN"), $satuan_kerja->getField("NAMA")) . " - " . $satuan_kerja->getField("NAMA_PEGAWAI");
			$arr_json[$i]['SATUAN_KERJA_ID']	= $satuan_kerja->getField("SATUAN_KERJA_ID");
			$arr_json[$i]['SATUAN_KERJA']		= $satuan_kerja->getField("NAMA");
			$arr_json[$i]['NAMA']				= $satuan_kerja->getField("NAMA");
			$arr_json[$i]['NAMA_PEGAWAI']		= $satuan_kerja->getField("NAMA_PEGAWAI");
			$arr_json[$i]['NIP']				= $satuan_kerja->getField("NIP");
			$arr_json[$i]['children']			= $this->teruskan_children($satuan_kerja->getField("KODE_SO"), $satuan_kerja->getField("SATUAN_KERJA_ID_PARENT"));

			$i++;
		}

		return $arr_json;
	}




	function disposisi()
	{
		if ($reqUnitKerjaId == "") {
			$reqUnitKerjaId = $this->CABANG_ID;
		}

		$reqTujuan = $this->input->get("reqTujuan");
		
		if ($reqTujuan == "") {
		}
			$reqTujuan = $this->SATUAN_KERJA_ID_ASAL;
		
		// $reqTujuan = "010102";

		$this->load->model("SatuanKerja");
		$satuan_kerja = new SatuanKerja();
		$this->load->model("Pegawai");
		$pegawai = new Pegawai();


		$i = 0;
		$arr_json = array();

		$pegawai->selectByParamsMonitoring(array("A.DEPARTEMEN_ID" => $reqTujuan),-1,-1," AND NOT PEGAWAI_ID='".$this->ID."' ");
		// echo $pegawai->query;exit;
		while ($pegawai->nextRow()) {


			$arr_json[$i]['id']					= "PEGAWAI" . $pegawai->getField("PEGAWAI_ID");
			$arr_json[$i]['text']				= $pegawai->getField("JABATAN") . " - " . $pegawai->getField("NAMA");
			$arr_json[$i]['SATUAN_KERJA_ID']	= $pegawai->getField("DEPARTEMEN_ID");
			$arr_json[$i]['SATUAN_KERJA']		= $pegawai->getField("DEPARTEMEN");
			$arr_json[$i]['NAMA']				= $pegawai->getField("DEPARTEMEN");
			$arr_json[$i]['NAMA_PEGAWAI']		= $pegawai->getField("NAMA");
			$arr_json[$i]['NIP']				= $pegawai->getField("NIP");

			$i++;
		}

		// tambahan khusus
		$filterkelompok= $filtersemuadivisi= "";
		$arrStatement = array("COALESCE(NULLIF(KODE_PARENT, ''), '0')" => $reqTujuan, "NOT SATUAN_KERJA_ID_PARENT" => "SATKER", "SATUAN_KERJA_ID_PARENT" => $reqUnitKerjaId);

		$satuan_kerja->selectByParams($arrStatement, -1, -1, $statement . $statement_privacy, " ORDER BY KODE_SO ASC ");
		// echo $satuan_kerja->query;exit;
		while ($satuan_kerja->nextRow()) {
			// tambahan khusus
			$filterkelompok= $satuan_kerja->getField("KELOMPOK_JABATAN");
			$filtersatuankerjaid= $satuan_kerja->getField("SATUAN_KERJA_ID");

			if(!empty($filtersatuankerjaid))
			{
				if(empty($filtersemuadivisi))
				{
					$filtersemuadivisi= "'".$filtersatuankerjaid."'";
				}
				else
				{
					$filtersemuadivisi= $filtersemuadivisi.", '".$filtersatuankerjaid."'";
				}
			}

			$arr_json[$i]['id']					= $satuan_kerja->getField("KODE_SO");
			$arr_json[$i]['text']				= coalesce($satuan_kerja->getField("JABATAN"), $satuan_kerja->getField("NAMA")) . " - " . $satuan_kerja->getField("NAMA_PEGAWAI");
			$arr_json[$i]['SATUAN_KERJA_ID']	= $satuan_kerja->getField("SATUAN_KERJA_ID");
			$arr_json[$i]['SATUAN_KERJA']		= $satuan_kerja->getField("NAMA");
			$arr_json[$i]['NAMA']				= $satuan_kerja->getField("NAMA");
			$arr_json[$i]['NAMA_PEGAWAI']		= $satuan_kerja->getField("NAMA_PEGAWAI");
			$arr_json[$i]['NIP']				= $satuan_kerja->getField("NIP");

			//$arr_json[$i]['children']			= $this->disposisi_children($satuan_kerja->getField("KODE_SO"), $satuan_kerja->getField("SATUAN_KERJA_ID_PARENT"));

			$i++;
		}
		// echo $filtersemuadivisi;exit;

		// tambahan khusus
		$carialldevisi= "";
		$kondisisemuadivisi= "AND NOT SATUAN_KERJA_ID_PARENT = 'SATKER' AND SATUAN_KERJA_ID_PARENT = '".$reqUnitKerjaId."' ";
		if(!empty($filterkelompok))
		{
			$kondisisemuadivisi.= " AND A.KELOMPOK_JABATAN = '".$filterkelompok."'";
			$carialldevisi= "1";
		}

		if(!empty($filtersemuadivisi))
		{
			$kondisisemuadivisi.= " AND A.SATUAN_KERJA_ID NOT IN (".$filtersemuadivisi.")";
			$carialldevisi= "1";
		}
		// echo $kondisisemuadivisi;exit;

		if($carialldevisi == "1")
		{
			$satuan_kerja->selectByParams(array(), -1, -1, $kondisisemuadivisi, " ORDER BY KODE_SO ASC ");
			// echo $satuan_kerja->query;exit;
			while ($satuan_kerja->nextRow()) {
				$filterkelompok= $satuan_kerja->getField("KELOMPOK_JABATAN");
				if(empty($filtersemuadivisi))
				{
					$filtersemuadivisi= "'".$satuan_kerja->getField("SATUAN_KERJA_ID")."'";
				}
				else
				{
					$filtersemuadivisi= ",".$filtersemuadivisi."'".$satuan_kerja->getField("SATUAN_KERJA_ID")."'";
				}

				$arr_json[$i]['id']					= $satuan_kerja->getField("KODE_SO");
				$arr_json[$i]['text']				= coalesce($satuan_kerja->getField("JABATAN"), $satuan_kerja->getField("NAMA")) . " - " . $satuan_kerja->getField("NAMA_PEGAWAI");
				$arr_json[$i]['SATUAN_KERJA_ID']	= $satuan_kerja->getField("SATUAN_KERJA_ID");
				$arr_json[$i]['SATUAN_KERJA']		= $satuan_kerja->getField("NAMA");
				$arr_json[$i]['NAMA']				= $satuan_kerja->getField("NAMA");
				$arr_json[$i]['NAMA_PEGAWAI']		= $satuan_kerja->getField("NAMA_PEGAWAI");
				$arr_json[$i]['NIP']				= $satuan_kerja->getField("NIP");

				$i++;
			}
		}

		echo json_encode($arr_json);
	}

	function disposisi_children($id, $satkerId)
	{

		$this->load->model("SatuanKerja");
		$satuan_kerja = new SatuanKerja();
		$this->load->model("Pegawai");
		$pegawai = new Pegawai();


		$i = 0;
		$arr_json = array();

		$pegawai->selectByParamsMonitoring(array("A.DEPARTEMEN_ID" => $id), -1, -1, " AND NOT EXISTS(SELECT 1 FROM SATUAN_KERJA X WHERE X.NIP = A.NIP) ");
		while ($pegawai->nextRow()) {


			$arr_json[$i]['id']					= "PEGAWAI" . $pegawai->getField("PEGAWAI_ID");
			$arr_json[$i]['text']				= $pegawai->getField("JABATAN") . " - " . $pegawai->getField("NAMA");
			$arr_json[$i]['SATUAN_KERJA_ID']	= $pegawai->getField("DEPARTEMEN_ID");
			$arr_json[$i]['SATUAN_KERJA']		= $pegawai->getField("DEPARTEMEN");
			$arr_json[$i]['NAMA']				= $pegawai->getField("DEPARTEMEN");
			$arr_json[$i]['NAMA_PEGAWAI']		= $pegawai->getField("NAMA");
			$arr_json[$i]['NIP']				= $pegawai->getField("NIP");

			$i++;
		}



		$arrStatement = array("COALESCE(NULLIF(KODE_PARENT, ''), '0')" => $id, "NOT SATUAN_KERJA_ID_PARENT" => "SATKER", "SATUAN_KERJA_ID_PARENT" => $satkerId);

		$satuan_kerja->selectByParams($arrStatement, -1, -1, $statement, " ORDER BY KODE_SO ASC ");
		//echo $satuan_kerja->query;exit;
		while ($satuan_kerja->nextRow()) {

			$arr_json[$i]['id']					= $satuan_kerja->getField("KODE_SO");
			$arr_json[$i]['text']				= coalesce($satuan_kerja->getField("JABATAN"), $satuan_kerja->getField("NAMA")) . " - " . $satuan_kerja->getField("NAMA_PEGAWAI");
			$arr_json[$i]['SATUAN_KERJA_ID']	= $satuan_kerja->getField("SATUAN_KERJA_ID");
			$arr_json[$i]['SATUAN_KERJA']		= $satuan_kerja->getField("NAMA");
			$arr_json[$i]['NAMA']				= $satuan_kerja->getField("NAMA");
			$arr_json[$i]['NAMA_PEGAWAI']		= $satuan_kerja->getField("NAMA_PEGAWAI");
			$arr_json[$i]['NIP']				= $satuan_kerja->getField("NIP");
			$arr_json[$i]['children']			= $this->disposisi_children($satuan_kerja->getField("KODE_SO"), $satuan_kerja->getField("SATUAN_KERJA_ID_PARENT"));

			$i++;
		}

		return $arr_json;
	}

	function register_surat()
	{
		if ($reqUnitKerjaId == "")
			$reqUnitKerjaId = $this->CABANG_ID;

		$this->load->model("SatuanKerja");
		$satuan_kerja = new SatuanKerja();

		$arrStatement = array("NOT SATUAN_KERJA_ID_PARENT" => "SATKER", "SATUAN_KERJA_ID_PARENT" => $reqUnitKerjaId, "STATUS_AKTIF" => "1");

		if ($this->USER_GROUP == "TATAUSAHA") {
		} else {
			$statement = " AND A.SATUAN_KERJA_ID = '" . $this->SATUAN_KERJA_ID_ASAL . "' ";
		}

		$satuan_kerja->selectByParams($arrStatement, -1, -1, $statement . $statement_privacy, " ORDER BY KODE_SO ASC ");
		// echo $satuan_kerja->query;exit;
		$i = 0;
		while ($satuan_kerja->nextRow()) {
			$arr_json[$i]['id']					= $satuan_kerja->getField("KODE_SO");
			$arr_json[$i]['text']				= coalesce($satuan_kerja->getField("JABATAN"), $satuan_kerja->getField("NAMA"));
			$arr_json[$i]['SATUAN_KERJA_ID']	= $satuan_kerja->getField("SATUAN_KERJA_ID");
			$arr_json[$i]['SATUAN_KERJA']		= $satuan_kerja->getField("NAMA");
			$arr_json[$i]['NAMA']				= $satuan_kerja->getField("NAMA");
			$arr_json[$i]['NAMA_PEGAWAI']		= $satuan_kerja->getField("NAMA_PEGAWAI");
			$arr_json[$i]['NIP']				= $satuan_kerja->getField("NIP");
			$arr_json[$i]['children']			= $this->register_surat_children($satuan_kerja->getField("KODE_SO"), $satuan_kerja->getField("SATUAN_KERJA_ID_PARENT"));

			$i++;
		}

		echo json_encode($arr_json);
	}

	function register_surat_children($id, $satkerId)
	{

		$this->load->model("SatuanKerja");
		$satuan_kerja = new SatuanKerja();

		$arrStatement = array("COALESCE(NULLIF(KODE_PARENT, ''), '0')" => $id, "NOT SATUAN_KERJA_ID_PARENT" => "SATKER", "SATUAN_KERJA_ID_PARENT" => $satkerId, "STATUS_AKTIF" => "1");

		$satuan_kerja->selectByParams($arrStatement, -1, -1, $statement, " ORDER BY KODE_SO ASC ");
		//echo $satuan_kerja->query;exit;
		$i = 0;
		$arr_json = array();
		while ($satuan_kerja->nextRow()) {

			$arr_json[$i]['id']					= $satuan_kerja->getField("KODE_SO");
			$arr_json[$i]['text']				= coalesce($satuan_kerja->getField("JABATAN"), $satuan_kerja->getField("NAMA"));
			$arr_json[$i]['SATUAN_KERJA_ID']	= $satuan_kerja->getField("SATUAN_KERJA_ID");
			$arr_json[$i]['SATUAN_KERJA']		= $satuan_kerja->getField("NAMA");
			$arr_json[$i]['NAMA']				= $satuan_kerja->getField("NAMA");
			$arr_json[$i]['NAMA_PEGAWAI']		= $satuan_kerja->getField("NAMA_PEGAWAI");
			$arr_json[$i]['NIP']				= $satuan_kerja->getField("NIP");
			$arr_json[$i]['children']			= $this->register_surat_children($satuan_kerja->getField("KODE_SO"), $satuan_kerja->getField("SATUAN_KERJA_ID_PARENT"));

			$i++;
		}

		return $arr_json;
	}

	function combotree()
	{
		if ($reqUnitKerjaId == "")
			$reqUnitKerjaId = $this->CABANG_ID;

		$this->load->model("SatuanKerja");
		$satuan_kerja = new SatuanKerja();

		$arrStatement = array("COALESCE(NULLIF(KODE_PARENT, ''), '0')" => "0", "NOT SATUAN_KERJA_ID_PARENT" => "SATKER", "SATUAN_KERJA_ID_PARENT" => $reqUnitKerjaId, "STATUS_AKTIF" => "1");

		$satuan_kerja->selectByParams($arrStatement, -1, -1, $statement . $statement_privacy, " ORDER BY KODE_SO ASC ");
		// echo $satuan_kerja->query;exit;
		$i = 0;
		while ($satuan_kerja->nextRow()) {
			$arr_json[$i]['id']					= $satuan_kerja->getField("KODE_SO");
			$arr_json[$i]['text']				= coalesce($satuan_kerja->getField("JABATAN"), $satuan_kerja->getField("NAMA"));
			$arr_json[$i]['SATUAN_KERJA_ID']	= $satuan_kerja->getField("SATUAN_KERJA_ID");
			$arr_json[$i]['SATUAN_KERJA']		= $satuan_kerja->getField("NAMA");
			$arr_json[$i]['NAMA']				= $satuan_kerja->getField("NAMA");
			$arr_json[$i]['NAMA_PEGAWAI']		= $satuan_kerja->getField("NAMA_PEGAWAI");
			$arr_json[$i]['NIP']				= $satuan_kerja->getField("NIP");
			$arr_json[$i]['children']			= $this->combotree_children($satuan_kerja->getField("KODE_SO"), $satuan_kerja->getField("SATUAN_KERJA_ID_PARENT"));

			$i++;
		}

		echo json_encode($arr_json);
	}


	function combotree_children($id, $satkerId)
	{

		$this->load->model("SatuanKerja");
		$satuan_kerja = new SatuanKerja();

		$arrStatement = array("COALESCE(NULLIF(KODE_PARENT, ''), '0')" => $id, "NOT SATUAN_KERJA_ID_PARENT" => "SATKER", "SATUAN_KERJA_ID_PARENT" => $satkerId, "STATUS_AKTIF" => "1");

		$satuan_kerja->selectByParams($arrStatement, -1, -1, $statement, " ORDER BY KODE_SO ASC ");
		//echo $satuan_kerja->query;exit;
		$i = 0;
		$arr_json = array();
		while ($satuan_kerja->nextRow()) {

			$arr_json[$i]['id']					= $satuan_kerja->getField("KODE_SO");
			$arr_json[$i]['text']				= coalesce($satuan_kerja->getField("JABATAN"), $satuan_kerja->getField("NAMA"));
			$arr_json[$i]['SATUAN_KERJA_ID']	= $satuan_kerja->getField("SATUAN_KERJA_ID");
			$arr_json[$i]['SATUAN_KERJA']		= $satuan_kerja->getField("NAMA");
			$arr_json[$i]['NAMA']				= $satuan_kerja->getField("NAMA");
			$arr_json[$i]['NAMA_PEGAWAI']		= $satuan_kerja->getField("NAMA_PEGAWAI");
			$arr_json[$i]['NIP']				= $satuan_kerja->getField("NIP");
			$arr_json[$i]['children']			= $this->combotree_children($satuan_kerja->getField("KODE_SO"), $satuan_kerja->getField("SATUAN_KERJA_ID_PARENT"));

			$i++;
		}

		return $arr_json;
	}



	function combotree_jabatan()
	{
		if ($reqUnitKerjaId == "")
			$reqUnitKerjaId = $this->CABANG_ID;

		$this->load->model("SatuanKerja");
		$satuan_kerja = new SatuanKerja();

		$arrStatement = array("COALESCE(NULLIF(KODE_PARENT, ''), '0')" => "0", "NOT SATUAN_KERJA_ID_PARENT" => "SATKER", "SATUAN_KERJA_ID_PARENT" => $reqUnitKerjaId, "STATUS_AKTIF" => "1");

		$satuan_kerja->selectByParams($arrStatement, -1, -1, $statement . $statement_privacy, " ORDER BY KODE_SO ASC ");
		//echo $satuan_kerja->query;exit;
		$i = 0;
		while ($satuan_kerja->nextRow()) {
			$arr_json[$i]['id']					= $satuan_kerja->getField("SATUAN_KERJA_ID");
			$arr_json[$i]['text']				= coalesce($satuan_kerja->getField("JABATAN"), $satuan_kerja->getField("NAMA"));
			$arr_json[$i]['SATUAN_KERJA_ID']	= $satuan_kerja->getField("SATUAN_KERJA_ID");
			$arr_json[$i]['SATUAN_KERJA']		= $satuan_kerja->getField("NAMA");
			$arr_json[$i]['NAMA']				= $satuan_kerja->getField("NAMA");
			$arr_json[$i]['JABATAN']			= coalesce($satuan_kerja->getField("JABATAN"), $satuan_kerja->getField("NAMA"));
			$arr_json[$i]['NAMA_PEGAWAI']		= $satuan_kerja->getField("NAMA_PEGAWAI");
			$arr_json[$i]['NIP']				= $satuan_kerja->getField("NIP");
			$arr_json[$i]['children']			= $this->combotree_jabatan_children($satuan_kerja->getField("KODE_SO"), $satuan_kerja->getField("SATUAN_KERJA_ID_PARENT"));

			$i++;
		}

		echo json_encode($arr_json);
	}


	function combotree_jabatan_children($id, $satkerId)
	{

		$this->load->model("SatuanKerja");
		$satuan_kerja = new SatuanKerja();

		$arrStatement = array("COALESCE(NULLIF(KODE_PARENT, ''), '0')" => $id, "NOT SATUAN_KERJA_ID_PARENT" => "SATKER", "SATUAN_KERJA_ID_PARENT" => $satkerId, "STATUS_AKTIF" => "1");

		$satuan_kerja->selectByParams($arrStatement, -1, -1, $statement, " ORDER BY KODE_SO ASC ");
		//echo $satuan_kerja->query;exit;
		$i = 0;
		$arr_json = array();
		while ($satuan_kerja->nextRow()) {

			$arr_json[$i]['id']					= $satuan_kerja->getField("SATUAN_KERJA_ID");
			$arr_json[$i]['text']				= coalesce($satuan_kerja->getField("JABATAN"), $satuan_kerja->getField("NAMA"));
			$arr_json[$i]['SATUAN_KERJA_ID']	= $satuan_kerja->getField("SATUAN_KERJA_ID");
			$arr_json[$i]['SATUAN_KERJA']		= $satuan_kerja->getField("NAMA");
			$arr_json[$i]['NAMA']				= $satuan_kerja->getField("NAMA");
			$arr_json[$i]['JABATAN']			= coalesce($satuan_kerja->getField("JABATAN"), $satuan_kerja->getField("NAMA"));
			$arr_json[$i]['NAMA_PEGAWAI']		= $satuan_kerja->getField("NAMA_PEGAWAI");
			$arr_json[$i]['NIP']				= $satuan_kerja->getField("NIP");
			$arr_json[$i]['children']			= $this->combotree_jabatan_children($satuan_kerja->getField("KODE_SO"), $satuan_kerja->getField("SATUAN_KERJA_ID_PARENT"));

			$i++;
		}

		return $arr_json;
	}


	function combotree_satker()
	{
		if ($reqUnitKerjaId == "")
			$reqUnitKerjaId = $this->CABANG_ID;

		$this->load->model("SatuanKerja");
		$satuan_kerja = new SatuanKerja();

		$arrStatement = array("COALESCE(NULLIF(KODE_PARENT, ''), '0')" => "0", "NOT SATUAN_KERJA_ID_PARENT" => "SATKER", "SATUAN_KERJA_ID_PARENT" => $reqUnitKerjaId, "STATUS_AKTIF" => "1");

		$satuan_kerja->selectByParams($arrStatement, -1, -1, $statement . $statement_privacy, " ORDER BY KODE_SO ASC ");
		// echo $satuan_kerja->query;exit;
		$i = 0;
		while ($satuan_kerja->nextRow()) {
			$arr_json[$i]['id']					= $satuan_kerja->getField("SATUAN_KERJA_ID");
			$arr_json[$i]['text']				= $satuan_kerja->getField("NAMA");
			$arr_json[$i]['SATUAN_KERJA_ID']	= $satuan_kerja->getField("SATUAN_KERJA_ID");
			$arr_json[$i]['SATUAN_KERJA']		= $satuan_kerja->getField("NAMA");
			$arr_json[$i]['NAMA']				= $satuan_kerja->getField("NAMA");
			$arr_json[$i]['JABATAN']			= coalesce($satuan_kerja->getField("JABATAN"), $satuan_kerja->getField("NAMA"));
			$arr_json[$i]['NAMA_PEGAWAI']		= $satuan_kerja->getField("NAMA_PEGAWAI");
			$arr_json[$i]['NIP']				= $satuan_kerja->getField("NIP");
			$arr_json[$i]['children']			= $this->combotree_satker_children($satuan_kerja->getField("KODE_SO"), $satuan_kerja->getField("SATUAN_KERJA_ID_PARENT"));

			$i++;
		}

		echo json_encode($arr_json);
	}


	function combotree_satker_children($id, $satkerId)
	{

		$this->load->model("SatuanKerja");
		$satuan_kerja = new SatuanKerja();

		$arrStatement = array("COALESCE(NULLIF(KODE_PARENT, ''), '0')" => $id, "NOT SATUAN_KERJA_ID_PARENT" => "SATKER", "SATUAN_KERJA_ID_PARENT" => $satkerId, "STATUS_AKTIF" => "1");

		$satuan_kerja->selectByParams($arrStatement, -1, -1, $statement, " ORDER BY KODE_SO ASC ");
		//echo $satuan_kerja->query;exit;
		$i = 0;
		$arr_json = array();
		while ($satuan_kerja->nextRow()) {

			$arr_json[$i]['id']					= $satuan_kerja->getField("SATUAN_KERJA_ID");
			$arr_json[$i]['text']				= $satuan_kerja->getField("NAMA");
			$arr_json[$i]['SATUAN_KERJA_ID']	= $satuan_kerja->getField("SATUAN_KERJA_ID");
			$arr_json[$i]['SATUAN_KERJA']		= $satuan_kerja->getField("NAMA");
			$arr_json[$i]['NAMA']				= $satuan_kerja->getField("NAMA");
			$arr_json[$i]['JABATAN']			= coalesce($satuan_kerja->getField("JABATAN"), $satuan_kerja->getField("NAMA"));
			$arr_json[$i]['NAMA_PEGAWAI']		= $satuan_kerja->getField("NAMA_PEGAWAI");
			$arr_json[$i]['NIP']				= $satuan_kerja->getField("NIP");
			$arr_json[$i]['children']			= $this->combotree_satker_children($satuan_kerja->getField("KODE_SO"), $satuan_kerja->getField("SATUAN_KERJA_ID_PARENT"));

			$i++;
		}

		return $arr_json;
	}


	function combobox_cabang_satker()
	{
		$reqPencarian = $this->input->get("q");

		$this->load->model("SatuanKerja");
		$satuan_kerja = new SatuanKerja();


		if ($this->CABANG_ID == "01") {
		} else
			$statement = " AND A.SATUAN_KERJA_ID = '" . $this->CABANG_ID . "' ";

		$satuan_kerja->selectByParamsAktif(array("SATUAN_KERJA_ID_PARENT" => "SATKER", "STATUS_AKTIF" => "1"), -1, -1, $statement, " ORDER BY A.URUT, A.NAMA ASC ");
		$i = 0;
		while ($satuan_kerja->nextRow()) {
			$arr_json[$i]['id']		= $satuan_kerja->getField("SATUAN_KERJA_ID");
			$arr_json[$i]['text']	= $satuan_kerja->getField("NAMA");
			$arr_json[$i]['SATUAN_KERJA_ID']	= $satuan_kerja->getField("SATUAN_KERJA_ID");
			$arr_json[$i]['SATUAN_KERJA']	= $satuan_kerja->getField("NAMA");
			$arr_json[$i]['JABATAN']	= coalesce($satuan_kerja->getField("JABATAN"), $satuan_kerja->getField("NAMA"));
			$arr_json[$i]['NAMA_PEGAWAI']	= $satuan_kerja->getField("NAMA_PEGAWAI");
			$arr_json[$i]['NIP']	= $satuan_kerja->getField("NIP");
			$arr_json[$i]['CABANG_ID']	= $satuan_kerja->getField("SATUAN_KERJA_ID");
			$arr_json[$i]['DEPARTEMEN_ID']	= "";
			$arr_json[$i]['children'] = $this->combobox_cabang_satker_children("0", $satuan_kerja->getField("SATUAN_KERJA_ID"));
			$i++;
		}

		echo json_encode($arr_json);
	}

	function combobox_cabang_satker_children($id, $satkerId)
	{
		$this->load->model("SatuanKerja");
		$satuan_kerja = new SatuanKerja();

		$arrStatement = array("COALESCE(NULLIF(KODE_PARENT, ''), '0')" => $id, "SATUAN_KERJA_ID_PARENT" => $satkerId, "STATUS_AKTIF" => "1");

		$satuan_kerja->selectByParams($arrStatement, -1, -1, $statement, " ORDER BY KODE_SO ASC ");
		//echo $satuan_kerja->query;exit;
		$i = 0;
		$arr_json = array();
		while ($satuan_kerja->nextRow()) {

			$arr_json[$i]['id']					= $satuan_kerja->getField("SATUAN_KERJA_ID");
			$arr_json[$i]['text']				= $satuan_kerja->getField("NAMA");
			$arr_json[$i]['SATUAN_KERJA_ID']	= $satuan_kerja->getField("SATUAN_KERJA_ID");
			$arr_json[$i]['SATUAN_KERJA']		= $satuan_kerja->getField("NAMA");
			$arr_json[$i]['NAMA']				= $satuan_kerja->getField("NAMA");
			$arr_json[$i]['JABATAN']			= coalesce($satuan_kerja->getField("JABATAN"), $satuan_kerja->getField("NAMA"));
			$arr_json[$i]['NAMA_PEGAWAI']		= $satuan_kerja->getField("NAMA_PEGAWAI");
			$arr_json[$i]['NIP']				= $satuan_kerja->getField("NIP");
			$arr_json[$i]['CABANG_ID']			= $satkerId;
			$arr_json[$i]['DEPARTEMEN_ID']		= $satuan_kerja->getField("SATUAN_KERJA_ID");
			$arr_json[$i]['children']			= $this->combobox_cabang_satker_children($satuan_kerja->getField("KODE_SO"), $satkerId);

			$i++;
		}

		return $arr_json;
	}

	function comboboxkelompokjabatan()
	{
		$reqPencarian = $this->input->get("q");

		$this->load->model("SatuanKerja");
		$satuan_kerja = new SatuanKerja();

		$satuan_kerja->selectByParamsKelompokJabatan(" AND STATUS_AKTIF = '1' ");
		$i = 0;
		while ($satuan_kerja->nextRow()) {
			$arr_json[$i]['id']= $satuan_kerja->getField("KELOMPOK_JABATAN");
			$arr_json[$i]['text']= $satuan_kerja->getField("KELOMPOK_JABATAN");
			$arr_json[$i]['JABATAN']= $satuan_kerja->getField("KELOMPOK_JABATAN");
			$i++;
		}

		$arr_json[$i]['id']= "KARYAWAN";
		$arr_json[$i]['text']= "KARYAWAN";
		$arr_json[$i]['JABATAN']= "KARYAWAN";

		echo json_encode($arr_json);
	}

	function combobox_cabang_jabatan()
	{
		$reqPencarian = $this->input->get("q");

		$this->load->model("SatuanKerja");
		$satuan_kerja = new SatuanKerja();

		$satuan_kerja->selectByParamsAktif(array("SATUAN_KERJA_ID_PARENT" => "SATKER", "STATUS_AKTIF" => "1"), -1, -1, $statement, " ORDER BY A.URUT, A.NAMA ASC ");
		$i = 0;
		while ($satuan_kerja->nextRow()) {
			$arr_json[$i]['id']		= $satuan_kerja->getField("SATUAN_KERJA_ID");
			$arr_json[$i]['text']	= coalesce($satuan_kerja->getField("JABATAN"), $satuan_kerja->getField("NAMA"));
			$arr_json[$i]['SATUAN_KERJA_ID']	= $satuan_kerja->getField("SATUAN_KERJA_ID");
			$arr_json[$i]['SATUAN_KERJA']	= $satuan_kerja->getField("NAMA");
			$arr_json[$i]['JABATAN']	= coalesce($satuan_kerja->getField("JABATAN"), $satuan_kerja->getField("NAMA"));
			$arr_json[$i]['NAMA_PEGAWAI']	= $satuan_kerja->getField("NAMA_PEGAWAI");
			$arr_json[$i]['NIP']	= $satuan_kerja->getField("NIP");
			$arr_json[$i]['CABANG_ID']	= $satuan_kerja->getField("SATUAN_KERJA_ID");
			$arr_json[$i]['DEPARTEMEN_ID']	= "";
			$arr_json[$i]['children'] = $this->combobox_cabang_jabatan_children("0", $satuan_kerja->getField("SATUAN_KERJA_ID"));
			$i++;
		}

		echo json_encode($arr_json);
	}

	function combobox_cabang_jabatan_children($id, $satkerId)
	{
		$this->load->model("SatuanKerja");
		$satuan_kerja = new SatuanKerja();

		$arrStatement = array("COALESCE(NULLIF(KODE_PARENT, ''), '0')" => $id, "SATUAN_KERJA_ID_PARENT" => $satkerId, "STATUS_AKTIF" => "1");

		$satuan_kerja->selectByParams($arrStatement, -1, -1, $statement, " ORDER BY KODE_SO ASC ");
		//echo $satuan_kerja->query;exit;
		$i = 0;
		$arr_json = array();
		while ($satuan_kerja->nextRow()) {

			$arr_json[$i]['id']					= $satuan_kerja->getField("SATUAN_KERJA_ID");
			$arr_json[$i]['text']				= coalesce($satuan_kerja->getField("JABATAN"), $satuan_kerja->getField("NAMA"));
			$arr_json[$i]['SATUAN_KERJA_ID']	= $satuan_kerja->getField("SATUAN_KERJA_ID");
			$arr_json[$i]['SATUAN_KERJA']		= $satuan_kerja->getField("NAMA");
			$arr_json[$i]['NAMA']				= $satuan_kerja->getField("NAMA");
			$arr_json[$i]['JABATAN']			= coalesce($satuan_kerja->getField("JABATAN"), $satuan_kerja->getField("NAMA"));
			$arr_json[$i]['NAMA_PEGAWAI']		= $satuan_kerja->getField("NAMA_PEGAWAI");
			$arr_json[$i]['NIP']				= $satuan_kerja->getField("NIP");
			$arr_json[$i]['CABANG_ID']			= $satkerId;
			$arr_json[$i]['DEPARTEMEN_ID']		= $satuan_kerja->getField("SATUAN_KERJA_ID");
			$arr_json[$i]['children']			= $this->combobox_cabang_jabatan_children($satuan_kerja->getField("KODE_SO"), $satkerId);

			$i++;
		}

		return $arr_json;
	}



	function combo_cabang_all()
	{
		$reqPencarian = $this->input->get("q");

		$this->load->model("SatuanKerja");
		$satuan_kerja = new SatuanKerja();

		$satuan_kerja->selectByParamsAktif(array("SATUAN_KERJA_ID_PARENT" => "SATKER"), -1, -1, $statement, " ORDER BY A.URUT, A.NAMA ASC ");
		// echo $satuan_kerja->query;exit;
		$i = 0;
		// $arr_json[$i]['id'] = "0";
		// $arr_json[$i]['text'] = "PT ASDP (Persero)";
		// $arr_json[$i]['SATUAN_KERJA_ID'] = "0";
		// $arr_json[$i]['SATUAN_KERJA'] = "PT ASDP (Persero)";
		// $arr_json[$i]['NAMA_PEGAWAI'] = "";
		// $arr_json[$i]['NIP'] = "";
		// $i++;
		while ($satuan_kerja->nextRow()) {
			$arr_json[$i]['id'] = $satuan_kerja->getField("SATUAN_KERJA_ID");
			$arr_json[$i]['text'] = $satuan_kerja->getField("NAMA");
			$arr_json[$i]['SATUAN_KERJA_ID'] = $satuan_kerja->getField("SATUAN_KERJA_ID");
			$arr_json[$i]['SATUAN_KERJA'] = $satuan_kerja->getField("NAMA");
			$arr_json[$i]['NAMA_PEGAWAI'] = $satuan_kerja->getField("NAMA_PEGAWAI");
			$arr_json[$i]['NIP'] = $satuan_kerja->getField("NIP");
			$i++;
		}

		echo json_encode($arr_json);
	}


	function combo_cabang()
	{
		$reqPencarian = $this->input->get("q");

		$this->load->model("SatuanKerja");
		$satuan_kerja = new SatuanKerja();


		if ($this->CABANG_ID == "01") {
		} else
			$statement = " AND SATUAN_KERJA_ID = '" . $this->CABANG_ID . "' ";

		$satuan_kerja->selectByParamsAktif(array("SATUAN_KERJA_ID_PARENT" => "SATKER"), -1, -1, $statement, " ORDER BY A.URUT, A.NAMA ASC ");
		$i = 0;
		while ($satuan_kerja->nextRow()) {
			$arr_json[$i]['id'] = $satuan_kerja->getField("SATUAN_KERJA_ID");
			$arr_json[$i]['text'] = $satuan_kerja->getField("NAMA");
			$arr_json[$i]['SATUAN_KERJA_ID'] = $satuan_kerja->getField("SATUAN_KERJA_ID");
			$arr_json[$i]['SATUAN_KERJA'] = $satuan_kerja->getField("NAMA");
			$arr_json[$i]['NAMA_PEGAWAI'] = $satuan_kerja->getField("NAMA_PEGAWAI");
			$arr_json[$i]['NIP'] = $satuan_kerja->getField("NIP");
			$i++;
		}

		echo json_encode($arr_json);
	}


	function combo_cabang_alamat()
	{
		$reqPencarian = $this->input->get("q");
		$reqJenisSurat = $this->input->get("reqJenisSurat");

		$this->load->model("SatuanKerja");

		$satuan_kerja = new SatuanKerja();

		if ($reqJenisSurat == "INTERNAL")
			$statement = " AND SATUAN_KERJA_ID = '" . $this->CABANG_ID . "' ";
		else
			$statement = " AND NOT SATUAN_KERJA_ID = '" . $this->CABANG_ID . "' ";

		$satuan_kerja->selectByParamsAktif(array("SATUAN_KERJA_ID_PARENT" => "SATKER"), -1, -1, $statement, " ORDER BY A.URUT, A.NAMA ASC ");
		// echo $satuan_kerja->query;exit;
		$i = 0;
		while ($satuan_kerja->nextRow()) {
			$arr_json[$i]['id']		= $satuan_kerja->getField("SATUAN_KERJA_ID");
			$arr_json[$i]['text']	= $satuan_kerja->getField("NAMA");
			$arr_json[$i]['SATUAN_KERJA_ID']	= $satuan_kerja->getField("SATUAN_KERJA_ID");
			$arr_json[$i]['SATUAN_KERJA']	= $satuan_kerja->getField("NAMA");
			$arr_json[$i]['NAMA_PEGAWAI']	= $satuan_kerja->getField("NAMA_PEGAWAI");
			$arr_json[$i]['NIP']	= $satuan_kerja->getField("NIP");
			$i++;
		}

		if ($reqJenisSurat == "INTERNAL") {
		} else {
			$arr_json[$i]['id']		= "0";
			$arr_json[$i]['text']	= "Pihak Eksternal";
			$arr_json[$i]['SATUAN_KERJA_ID']	= "0";
			$arr_json[$i]['SATUAN_KERJA']		= "Pihak Eksternal";
			$arr_json[$i]['NAMA_PEGAWAI']		= "0";
			$arr_json[$i]['NIP']				= "0";
			$i++;
		}

		// $set= new SatuanKerjaKelompok();
		// $arr_json[$i]['id']= "1";
		// $arr_json[$i]['text']= "Kelompok";
		// $arr_json[$i]['SATUAN_KERJA_ID']= "1";
		// $arr_json[$i]['SATUAN_KERJA']= "Kelompok";
		// $arr_json[$i]['NAMA_PEGAWAI']= "";
		// $arr_json[$i]['NIP']= "";

		echo json_encode($arr_json);
	}





	function combo_all()
	{
		$reqPencarian = $this->input->get("q");

		$this->load->model("SatuanKerja");
		$satuan_kerja = new SatuanKerja();

		if ($reqPencarian == "") {
		} else
			$statement = " AND (UPPER(A.NAMA) LIKE '%" . strtoupper($reqPencarian) . "%' OR UPPER(A.NAMA_PEGAWAI) LIKE '%" . strtoupper($reqPencarian) . "%') ";

		$satuan_kerja->selectByParamsAktif(array(), -1, -1, $statement, " ORDER BY A.URUT ASC ");
		$i = 0;
		while ($satuan_kerja->nextRow()) {
			$arr_json[$i]['id']		= $satuan_kerja->getField("SATUAN_KERJA_ID");
			$arr_json[$i]['text']	= $satuan_kerja->getField("NAMA");
			$arr_json[$i]['SATUAN_KERJA_ID']	= $satuan_kerja->getField("SATUAN_KERJA_ID");
			$arr_json[$i]['SATUAN_KERJA']	= $satuan_kerja->getField("NAMA");
			$arr_json[$i]['NAMA_PEGAWAI']	= $satuan_kerja->getField("NAMA_PEGAWAI");
			$arr_json[$i]['NIP']	= coalesce($satuan_kerja->getField("NIP"), "");
			$i++;
		}

		echo json_encode($arr_json);
	}


	function treetable()
	{

		$reqUnitKerjaId = $this->input->get("reqUnitKerjaId");

		if ($reqUnitKerjaId == "")
			$reqUnitKerjaId = $this->CABANG_ID;

		// echo $reqUnitKerjaId;exit;

		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : 50;
		$id   = isset($_POST['id']) ? intval($_POST['id']) : 0;
		$offset = ($page - 1) * $rows;

		$reqPencarian = trim($this->input->get("reqPencarian"));
		$reqMode = $this->input->get("reqMode");


		$this->load->model("SatuanKerja");
		$satuan_kerja = new SatuanKerja();

		if ($reqPencarian == "")
			$arrStatement = array("NOT SATUAN_KERJA_ID_PARENT" => "SATKER", "SATUAN_KERJA_ID_PARENT" => $reqUnitKerjaId, "STATUS_AKTIF" => '1');
		else {
			$arrStatement = array("NOT SATUAN_KERJA_ID_PARENT" => "SATKER", "SATUAN_KERJA_ID_PARENT" => $reqUnitKerjaId, "STATUS_AKTIF" => '1');
			$statement = " AND (UPPER(NAMA) LIKE '%" . strtoupper($reqPencarian) . "%' OR UPPER(JABATAN) LIKE '%" . strtoupper($reqPencarian) . "%' OR UPPER(NAMA_PEGAWAI) LIKE '%" . strtoupper($reqPencarian) . "%' OR UPPER(NIP) LIKE '%" . strtoupper($reqPencarian) . "%') ";
		}


		$rowCount = $satuan_kerja->getCountByParams($arrStatement, $statement . $statement_privacy);
		$satuan_kerja->selectByParams($arrStatement, $rows, $offset, $statement . $statement_privacy, " ORDER BY KODE_SO ASC ");
		// echo $satuan_kerja->query;exit;
		$i = 0;
		$items = array();
		while ($satuan_kerja->nextRow()) {
			$this->TREETABLE_COUNT++;

			$row['id'] = coalesce($satuan_kerja->getField("KODE_SO"), $satuan_kerja->getField("SATUAN_KERJA_ID"));
			$row['parentId'] = $satuan_kerja->getField("KODE_PARENT");
			$row['text'] = $satuan_kerja->getField("NAMA");
			$row['SATUAN_KERJA_ID']	= $satuan_kerja->getField("SATUAN_KERJA_ID");
			$row['SATUAN_KERJA_ID_PARENT']	= $satuan_kerja->getField("SATUAN_KERJA_ID_PARENT");
			$row['NAMA'] = $satuan_kerja->getField("NAMA");
			$row['NAMA_PEGAWAI'] = $satuan_kerja->getField("NAMA_PEGAWAI");
			$row['JABATAN'] = $satuan_kerja->getField("JABATAN");
			$row['NIP'] = $satuan_kerja->getField("NIP");
			$row['KODE_SURAT'] = $satuan_kerja->getField("KODE_SURAT");
			$row['KELOMPOK_JABATAN'] = $satuan_kerja->getField("KELOMPOK_JABATAN");
			if (trim($reqPencarian) == "") {
				$row['state'] = $this->has_child($row['id']);
				$row['children'] = $this->children($satuan_kerja->getField("KODE_SO"), $satuan_kerja->getField("SATUAN_KERJA_ID_PARENT"));
			}
			$i++;
			array_push($items, $row);
			unset($row);
		}
		$result["rows"] = $items;
		$result["total"] = $this->TREETABLE_COUNT;

		echo json_encode($result);
	}

	function children($id, $satkerId)
	{
		$this->load->model("SatuanKerja");
		$satuan_kerja = new SatuanKerja();


		$arrStatement = array("COALESCE(NULLIF(KODE_PARENT, ''), '0')" => $id, "NOT SATUAN_KERJA_ID_PARENT" => "SATKER", "SATUAN_KERJA_ID_PARENT" => $satkerId, "STATUS_AKTIF" => '1');

		$rowCount = $satuan_kerja->getCountByParams($arrStatement, $statement . $statement_privacy);
		$satuan_kerja->selectByParams($arrStatement, $rows, $offset, $statement . $statement_privacy, " ORDER BY KODE_SO ASC ");
		// echo $satuan_kerja->query;exit;
		$i = 0;
		$items = array();
		while ($satuan_kerja->nextRow()) {
			$this->TREETABLE_COUNT++;

			$row['id']				= coalesce($satuan_kerja->getField("KODE_SO"), $satuan_kerja->getField("SATUAN_KERJA_ID"));
			$row['parentId']		= $satuan_kerja->getField("KODE_PARENT");
			$row['text']			= $satuan_kerja->getField("NAMA");
			$row['SATUAN_KERJA_ID']	= $satuan_kerja->getField("SATUAN_KERJA_ID");
			$row['SATUAN_KERJA_ID_PARENT']	= $satuan_kerja->getField("SATUAN_KERJA_ID_PARENT");
			$row['NAMA']			= $satuan_kerja->getField("NAMA");
			$row['NAMA_PEGAWAI']	= $satuan_kerja->getField("NAMA_PEGAWAI");
			$row['JABATAN']			= $satuan_kerja->getField("JABATAN") ;
			$row['NIP']				= $satuan_kerja->getField("NIP");
			$row['KODE_SURAT']				= $satuan_kerja->getField("KODE_SURAT");
			$row['KELOMPOK_JABATAN']		= $satuan_kerja->getField("KELOMPOK_JABATAN");

			$state = $this->has_child($row['id']);


			$row['state'] 			= $state;
			if ($state)
				$row['children'] 		= $this->children($satuan_kerja->getField("KODE_SO"), $satkerId);

			$i++;
			array_push($items, $row);
			unset($row);
		}

		return $items;
	}


	function has_child($id)
	{
		$this->load->model("SatuanKerja");
		$satuan_kerja = new SatuanKerja();
		$adaData = $satuan_kerja->getCountByParams(array("COALESCE(NULLIF(KODE_PARENT, ''), '0')" => $id));
		return $adaData > 0 ? true : false;
	}




	function treetable_master() 
	{	
		$reqUnitKerjaId = $this->input->get("reqUnitKerjaId");
		$reqStatus = $this->input->get("reqStatus");
		
		if($reqUnitKerjaId == "")
			$reqUnitKerjaId = $this->CABANG_ID;
		
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : 50;
		$id   = isset($_POST['id']) ? intval($_POST['id']) : 0;
		$offset = ($page-1)*$rows;
		
		$reqPencarian = trim($this->input->get("reqPencarian"));
		$reqMode = $this->input->get("reqMode");
		
		$this->load->model("SatuanKerja");
		$this->load->model("SatuanKerjaKelompok");

		$satuan_kerja = new SatuanKerja();

		if($reqPencarian == "")
		{
			if($reqStatus == "1")
			{
				$arrStatement = array("COALESCE(NULLIF(KODE_PARENT, ''), '0')" => $reqUnitKerjaId, "NOT SATUAN_KERJA_ID_PARENT" => "SATKER", "SATUAN_KERJA_ID_PARENT" => $reqUnitKerjaId, "STATUS_AKTIF" => '1');
			}
			else
			{
				$arrStatement = array("COALESCE(NULLIF(KODE_PARENT, ''), '0')" => $reqUnitKerjaId, "NOT SATUAN_KERJA_ID_PARENT" => "SATKER", "SATUAN_KERJA_ID_PARENT" => $reqUnitKerjaId);
			}
		}
		else
		{
			$arrStatement = array("NOT SATUAN_KERJA_ID_PARENT" => "SATKER", "SATUAN_KERJA_ID_PARENT" => $reqUnitKerjaId, "STATUS_AKTIF" => '1');
			$statement = " AND (UPPER(NAMA) LIKE '%".strtoupper($reqPencarian)."%' OR UPPER(JABATAN) LIKE '%".strtoupper($reqPencarian)."%') ";
		}
		
			
		$rowCount = $satuan_kerja->getCountByParams($arrStatement, $statement.$statement_privacy);
		$satuan_kerja->selectByParams($arrStatement, $rows, $offset, $statement.$statement_privacy, " ORDER BY KODE_SO ASC ");
		// echo $satuan_kerja->query;exit;
		$i = 0;
		$items = array();
		while($satuan_kerja->nextRow())
		{
			$this->TREETABLE_COUNT++;
			
			$row['id']				= coalesce($satuan_kerja->getField("KODE_SO"), $satuan_kerja->getField("SATUAN_KERJA_ID"));
			$row['parentId']		= $satuan_kerja->getField("KODE_PARENT");
			$row['text']			= $satuan_kerja->getField("NAMA");
			$row['SATUAN_KERJA_ID']	= $satuan_kerja->getField("SATUAN_KERJA_ID");
			$row['SATUAN_KERJA_ID_PARENT']	= $satuan_kerja->getField("SATUAN_KERJA_ID_PARENT");
			$row['NAMA']			= $satuan_kerja->getField("NAMA");
			$row['NAMA_PEGAWAI']	= $satuan_kerja->getField("NAMA_PEGAWAI");
			$row['JABATAN']			= $satuan_kerja->getField("JABATAN")." - ".$satuan_kerja->getField("SATUAN_KERJA_ID_PARENT");
			$row['NIP']				= $satuan_kerja->getField("NIP");
			$row['KODE_SURAT']				= $satuan_kerja->getField("KODE_SURAT");
			$row['KELOMPOK_JABATAN']		= $satuan_kerja->getField("KELOMPOK_JABATAN");
			$row['STATUS_AKTIF']			= $satuan_kerja->getField("STATUS_AKTIF");
			$row['STATUS_AKTIF_DESC']		= $satuan_kerja->getField("STATUS_AKTIF_DESC");
			$row['LINK_URL']		= $satuan_kerja->getField("LINK_URL");
			if(trim($reqPencarian) == "")
			{
				$row['state'] 			= $this->has_child($row['id']);
				$row['children'] 		= $this->children_master($satuan_kerja->getField("SATUAN_KERJA_ID"), $satuan_kerja->getField("SATUAN_KERJA_ID_PARENT"), $reqStatus);
			}
			$i++;
			array_push($items, $row);
			unset($row);
		}

		$set= new SatuanKerjaKelompok();
		$set->selectByParamsMonitoring(array(), -1,-1, " AND A.CABANG_OWNER = '".$reqUnitKerjaId."'");
		$set->firstRow();
		$infoid= $set->getField("SATUAN_KERJA_KELOMPOK_ID");
		unset($set);

		if(!empty($infoid))
		{
			$this->TREETABLE_COUNT++;
			$row['id']= "KELOMPOK";
			$row['parentId']= "xxx";
			$row['text']= "Kelompok Jabatan";
			$row['state']= true;
			$row['children']= $this->satuankerjakelompok($reqUnitKerjaId);
			array_push($items, $row);
			unset($row);
		}

		$result["rows"] = $items;
		$result["total"] = $this->TREETABLE_COUNT;
		
		echo json_encode($result);
	}
	
	function satuankerjakelompok($reqUnitKerjaId)
	{
		$this->load->model("SatuanKerjaKelompok");

		$items= array();
		$set= new SatuanKerjaKelompok();
		$set->selectByParamsMonitoring(array(), -1,-1, " AND A.CABANG_OWNER = '".$reqUnitKerjaId."'");
		while($set->nextRow())
		{
			$this->TREETABLE_COUNT++;
			$row['id']= "KELOMPOK".$set->getField("SATUAN_KERJA_KELOMPOK_ID");
			$row['parentId']= "KELOMPOK";
			$row['text']= $set->getField("NAMA");
			array_push($items, $row);
			unset($row);
		}
		return $items;
	}

	function children_master($id, $satkerId, $reqStatus="1")
	{
		$this->load->model("SatuanKerja");
		$satuan_kerja = new SatuanKerja();
		
		if($reqStatus == "1")
		{
			$arrStatement = array("COALESCE(NULLIF(KODE_PARENT, ''), '0')" => $id, "NOT SATUAN_KERJA_ID_PARENT" => "SATKER", "SATUAN_KERJA_ID_PARENT" => $satkerId, "STATUS_AKTIF" => '1');
		}
		else
			$arrStatement = array("COALESCE(NULLIF(KODE_PARENT, ''), '0')" => $id, "NOT SATUAN_KERJA_ID_PARENT" => "SATKER", "SATUAN_KERJA_ID_PARENT" => $satkerId);

		$rowCount = $satuan_kerja->getCountByParams($arrStatement, $statement.$statement_privacy);
		$satuan_kerja->selectByParams($arrStatement, $rows, $offset, $statement.$statement_privacy, " ORDER BY KODE_SO ASC ");
		// echo $satuan_kerja->query;exit;
		$i = 0;
		$items = array();
		while($satuan_kerja->nextRow())
		{
			$this->TREETABLE_COUNT++;
			
			$row['id']				= coalesce($satuan_kerja->getField("KODE_SO"), $satuan_kerja->getField("SATUAN_KERJA_ID"));
			$row['parentId']		= $satuan_kerja->getField("KODE_PARENT");
			$row['text']			= $satuan_kerja->getField("NAMA");
			$row['SATUAN_KERJA_ID']	= $satuan_kerja->getField("SATUAN_KERJA_ID");
			$row['SATUAN_KERJA_ID_PARENT']	= $satuan_kerja->getField("SATUAN_KERJA_ID_PARENT");
			$row['NAMA']			= $satuan_kerja->getField("NAMA");
			$row['NAMA_PEGAWAI']	= $satuan_kerja->getField("NAMA_PEGAWAI");
			$row['JABATAN']			= $satuan_kerja->getField("JABATAN")." - ".$satuan_kerja->getField("SATUAN_KERJA_ID_PARENT");
			$row['NIP']				= $satuan_kerja->getField("NIP");
			$row['KODE_SURAT']				= $satuan_kerja->getField("KODE_SURAT");
			$row['KELOMPOK_JABATAN']		= $satuan_kerja->getField("KELOMPOK_JABATAN");
			$row['STATUS_AKTIF']			= $satuan_kerja->getField("STATUS_AKTIF");
			$row['STATUS_AKTIF_DESC']		= $satuan_kerja->getField("STATUS_AKTIF_DESC");
			$row['LINK_URL']		= $satuan_kerja->getField("LINK_URL");
			
			$state = $this->has_child($row['id']);
	
	
			$row['state'] 			= $state;
			if($state)
				$row['children'] 		= $this->children_master($satuan_kerja->getField("KODE_SO"), $satkerId, $reqStatus);
	
			$i++;
			array_push($items, $row);
			unset($row);
		}
		
		return $items;
	}
	



	function treetable_all()
	{

		$reqUnitKerjaId = $this->input->get("reqUnitKerjaId");
		$reqSatuanKerjaId = $this->input->get("reqSatuanKerjaId");

		if ($reqUnitKerjaId == "")
			$reqUnitKerjaId = $this->CABANG_ID;

		if ($reqSatuanKerjaId == "")
			$reqSatuanKerjaId = "0";

		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : 50;
		$id   = isset($_POST['id']) ? intval($_POST['id']) : 0;
		$offset = ($page - 1) * $rows;

		$reqPencarian = trim($this->input->get("reqPencarian"));
		$reqMode = $this->input->get("reqMode");


		$this->load->model("SatuanKerja");
		$this->load->model("Pegawai");
		$satuan_kerja = new SatuanKerja();
		$pegawai = new Pegawai();


		$i = 0;
		$items = array();


		if ($reqPencarian == "")
			$arrStatement = array("A.DEPARTEMEN_ID" => $reqSatuanKerjaId);
		else {
			$arrStatement = array("A.DEPARTEMEN_ID" => $reqSatuanKerjaId);
			$statement = " AND (UPPER(NAMA) LIKE '%" . strtoupper($reqPencarian) . "%' OR UPPER(JABATAN) LIKE '%" . strtoupper($reqPencarian) . "%') ";
		}

		$pegawai->selectByParamsMonitoring($arrStatement, -1, -1, $statement . " AND NOT EXISTS(SELECT 1 FROM SATUAN_KERJA X WHERE X.NIP = A.NIP) ");
		while ($pegawai->nextRow()) {
			$this->TREETABLE_COUNT++;

			$row['id']				= coalesce($pegawai->getField("PEGAWAI_ID"));
			$row['parentId']		= $id;
			$row['text']			= $pegawai->getField("NAMA");
			$row['SATUAN_KERJA_ID']	= $pegawai->getField("DEPARTEMEN_ID");
			$row['SATUAN_KERJA_ID_PARENT']	= $pegawai->getField("SATUAN_KERJA_ID");
			$row['NAMA']			= $pegawai->getField("NAMA");
			$row['NAMA_PEGAWAI']	= "";
			$row['JABATAN']			= $pegawai->getField("JABATAN");
			$row['NIP']				= $pegawai->getField("NIP");
			$row['KODE_SURAT']				= "";
			$row['KELOMPOK_JABATAN']		= "";

			$i++;

			array_push($items, $row);
			unset($row);
		}




		if ($reqPencarian == "")
			$arrStatement = array("COALESCE(NULLIF(KODE_PARENT, ''), '0')" => $reqSatuanKerjaId, "NOT SATUAN_KERJA_ID_PARENT" => "SATKER", "SATUAN_KERJA_ID_PARENT" => $reqUnitKerjaId);
		else {
			$arrStatement = array("NOT SATUAN_KERJA_ID_PARENT" => "SATKER", "SATUAN_KERJA_ID_PARENT" => $reqUnitKerjaId);
			$statement = " AND (UPPER(NAMA_PEGAWAI) LIKE '%" . strtoupper($reqPencarian) . "%' OR UPPER(JABATAN) LIKE '%" . strtoupper($reqPencarian) . "%') ";
		}

		$rowCount = $satuan_kerja->getCountByParams($arrStatement, $statement . $statement_privacy);
		$satuan_kerja->selectByParams($arrStatement, $rows, $offset, $statement . $statement_privacy, " ORDER BY KODE_SO ASC ");
		while ($satuan_kerja->nextRow()) {
			$this->TREETABLE_COUNT++;

			$row['id']				= coalesce($satuan_kerja->getField("KODE_SO"), $satuan_kerja->getField("SATUAN_KERJA_ID"));
			$row['parentId']		= $satuan_kerja->getField("KODE_PARENT");
			$row['text']			= $satuan_kerja->getField("NAMA");
			$row['SATUAN_KERJA_ID']	= $satuan_kerja->getField("SATUAN_KERJA_ID");
			$row['SATUAN_KERJA_ID_PARENT']	= $satuan_kerja->getField("SATUAN_KERJA_ID_PARENT");
			$row['NAMA']			= $satuan_kerja->getField("NAMA");
			$row['NAMA_PEGAWAI']	= $satuan_kerja->getField("NAMA_PEGAWAI");
			$row['JABATAN']			= $satuan_kerja->getField("JABATAN");
			$row['NIP']				= $satuan_kerja->getField("NIP");
			$row['KODE_SURAT']				= $satuan_kerja->getField("KODE_SURAT");
			$row['KELOMPOK_JABATAN']		= $satuan_kerja->getField("KELOMPOK_JABATAN");
			if (trim($reqPencarian) == "") {
				$row['state'] 			= $this->has_child($row['id']);
				$row['children'] 		= $this->children_all($satuan_kerja->getField("KODE_SO"), $satuan_kerja->getField("SATUAN_KERJA_ID_PARENT"));
			}
			$i++;
			array_push($items, $row);
			unset($row);
		}
		$result["rows"] = $items;
		$result["total"] = $this->TREETABLE_COUNT;

		echo json_encode($result);
	}

	function children_all($id, $satkerId)
	{
		$this->load->model("SatuanKerja");
		$this->load->model("Pegawai");
		$satuan_kerja = new SatuanKerja();
		$pegawai = new Pegawai();


		$i = 0;
		$items = array();


		$pegawai->selectByParamsMonitoring(array("A.DEPARTEMEN_ID" => $id), -1, -1, " AND NOT EXISTS(SELECT 1 FROM SATUAN_KERJA X WHERE X.NIP = A.NIP) ");
		while ($pegawai->nextRow()) {
			$this->TREETABLE_COUNT++;

			$row['id']				= coalesce($pegawai->getField("PEGAWAI_ID"));
			$row['parentId']		= $id;
			$row['text']			= $pegawai->getField("NAMA");
			$row['SATUAN_KERJA_ID']	= $pegawai->getField("DEPARTEMEN_ID");
			$row['SATUAN_KERJA_ID_PARENT']	= $pegawai->getField("SATUAN_KERJA_ID");
			$row['NAMA']			= $pegawai->getField("NAMA");
			$row['NAMA_PEGAWAI']	= "";
			$row['JABATAN']			= $pegawai->getField("JABATAN");
			$row['NIP']				= $pegawai->getField("NIP");
			$row['KODE_SURAT']				= "";
			$row['KELOMPOK_JABATAN']		= "";

			$i++;

			array_push($items, $row);
			unset($row);
		}


		$arrStatement = array("COALESCE(NULLIF(KODE_PARENT, ''), '0')" => $id, "NOT SATUAN_KERJA_ID_PARENT" => "SATKER", "SATUAN_KERJA_ID_PARENT" => $satkerId);
		$rowCount = $satuan_kerja->getCountByParams($arrStatement, $statement . $statement_privacy);
		$satuan_kerja->selectByParams($arrStatement, $rows, $offset, $statement . $statement_privacy, " ORDER BY KODE_SO ASC ");
		// echo $satuan_kerja->query;exit;
		while ($satuan_kerja->nextRow()) {
			$this->TREETABLE_COUNT++;

			$row['id']				= coalesce($satuan_kerja->getField("KODE_SO"), $satuan_kerja->getField("SATUAN_KERJA_ID"));
			$row['parentId']		= $satuan_kerja->getField("KODE_PARENT");
			$row['text']			= $satuan_kerja->getField("NAMA");
			$row['SATUAN_KERJA_ID']	= $satuan_kerja->getField("SATUAN_KERJA_ID");
			$row['SATUAN_KERJA_ID_PARENT']	= $satuan_kerja->getField("SATUAN_KERJA_ID_PARENT");
			$row['NAMA']			= $satuan_kerja->getField("NAMA");
			$row['NAMA_PEGAWAI']	= $satuan_kerja->getField("NAMA_PEGAWAI");
			$row['JABATAN']			= $satuan_kerja->getField("JABATAN");
			$row['NIP']				= $satuan_kerja->getField("NIP");
			$row['KODE_SURAT']				= $satuan_kerja->getField("KODE_SURAT");
			$row['KELOMPOK_JABATAN']		= $satuan_kerja->getField("KELOMPOK_JABATAN");

			$state = $this->has_child($row['id']);


			$row['children'] 		= $this->children_all($satuan_kerja->getField("KODE_SO"), $satkerId);

			$i++;
			array_push($items, $row);
			unset($row);
		}

		return $items;
	}



	function excel()
	{
		/** Include PHPExcel */
		require_once APPPATH . '/libraries/Excel.php';

		$objPHPExcel = new PHPExcel();

		// judul
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A1', 'SATUAN_KERJA_ID')
			->setCellValue('B1', 'NAMA')
			->setCellValue('C1', 'JABATAN')
			->setCellValue('D1', 'KODE_SURAT')
			->setCellValue('E1', 'KELOMPOK_JABATAN');


		$reqUnitKerjaId = $this->input->get("reqUnitKerjaId");

		if ($reqUnitKerjaId == "")
			$reqUnitKerjaId = $this->CABANG_ID;

		if ($reqUnitKerjaId == "01")
			$kelompokJabatan = "DIRUT/DIREKSI/VP/SM/MAN";
		else
			$kelompokJabatan = "GM/SM/MAN";

		$this->load->model("SatuanKerja");
		$satuan_kerja = new SatuanKerja();

		$satuan_kerja->selectByParams(array("NOT SATUAN_KERJA_ID_PARENT" => "SATKER", "SATUAN_KERJA_ID_PARENT" => $reqUnitKerjaId), -1, -1, $statement, " ORDER BY A.KODE_SO ASC ");
		// echo $satuan_kerja->query;exit;
		$i = 2;
		while ($satuan_kerja->nextRow()) {
			$objPHPExcel->setActiveSheetIndex(0)
				// isi
				->setCellValue('A' . $i, $satuan_kerja->getField("SATUAN_KERJA_ID"))
				->setCellValue('B' . $i, $satuan_kerja->getField("NAMA"))
				->setCellValue('C' . $i, $satuan_kerja->getField("JABATAN"))
				->setCellValue('D' . $i, $satuan_kerja->getField("KODE_SURAT"))
				->setCellValue('E' . $i, $satuan_kerja->getField("KELOMPOK_JABATAN"));
			$i++;

			// $arr_json[$i]['SATUAN_KERJA_ID']	= $satuan_kerja->getField("SATUAN_KERJA_ID");
			// $arr_json[$i]['SATUAN_KERJA']		= $satuan_kerja->getField("NAMA");
			// $arr_json[$i]['NAMA_JABATAN']		= $satuan_kerja->getField("JABATAN");
			// $arr_json[$i]['KODE_JABATAN']		= $satuan_kerja->getField("KODE_SURAT");
			// $arr_json[$i][$kelompokJabatan]		= $satuan_kerja->getField("KELOMPOK_JABATAN");
			// $i++;
		}

		$fileName = "satuan_kerja_excel" . date("dmYHis") . ".xls";

		// Redirect output to a client’s web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . $fileName . '"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
		header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header('Pragma: public'); // HTTP/1.0

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;


		// if ($arr_json) {
		// 	function filterData(&$str)
		// 	{
		// 		$str = preg_replace("/\t/", "\\t", $str);
		// 		$str = preg_replace("/\r?\n/", "\\n", $str);
		// 		if (strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
		// 	}

		// 	// headers for download
		// 	header("Content-Disposition: attachment; filename=\"$fileName\"");
		// 	header("Content-Type: application/vnd.ms-excel");

		// 	$flag = false;
		// 	foreach ($arr_json as $row) {
		// 		if (!$flag) {
		// 			// display column names as first row
		// 			echo implode("\t", array_keys($row)) . "\n";
		// 			$flag = true;
		// 		}
		// 		// filter data
		// 		array_walk($row, 'filterData');
		// 		echo implode("\t", array_values($row)) . "\n";
		// 	}
		// 	exit;
		// }
	}



	function excel_template_direktorat()
	{

		$reqUnitKerjaId = $this->input->get("reqUnitKerjaId");


		$this->load->model("SatuanKerja");
		$satuan_kerja = new SatuanKerja();

		$satuan_kerja->selectByParams(array("NOT SATUAN_KERJA_ID_PARENT" => "SATKER", "SATUAN_KERJA_ID_PARENT" => $this->CABANG_ID, "STATUS_AKTIF" => "1"), -1, -1, $statement, " ORDER BY A.SATUAN_KERJA_ID_PARENT, A.KODE_SO ASC ");
		$i = 0;
		while ($satuan_kerja->nextRow()) {
			$arr_json[$i]['UNIT_KERJA_ID']	= $satuan_kerja->getField("SATUAN_KERJA_ID_PARENT");
			$arr_json[$i]['DIREKTORAT_ID']	= $satuan_kerja->getField("SATUAN_KERJA_ID");
			$arr_json[$i]['DIREKTORAT']		= $satuan_kerja->getField("NAMA");
			$i++;
		}

		$fileName = "direktorat" . date("dmYHis") . ".xls";

		if ($arr_json) {
			function filterData(&$str)
			{
				$str = preg_replace("/\t/", "\\t", $str);
				$str = preg_replace("/\r?\n/", "\\n", $str);
				if (strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
			}

			// headers for download
			header("Content-Disposition: attachment; filename=\"$fileName\"");
			header("Content-Type: application/vnd.ms-excel");

			$flag = false;
			foreach ($arr_json as $row) {
				if (!$flag) {
					// display column names as first row
					echo implode("\t", array_keys($row)) . "\n";
					$flag = true;
				}
				// filter data
				array_walk($row, 'filterData');
				echo implode("\t", array_values($row)) . "\n";
			}
			exit;
		}
	}

	function ubah_alamat()
	{


		$this->load->model("SatuanKerja");
		$satuan_kerja = new SatuanKerja();

		$reqMode 					= $this->input->post("reqMode");
		$reqId 						= $this->input->post("reqId");
		$reqAlamat					= $this->input->post("reqAlamat");
		$reqFax						= $this->input->post("reqFax");
		$reqTelepon					= $this->input->post("reqTelepon");
		$reqLokasi					= $this->input->post("reqLokasi");

		$satuan_kerja->setField("KODE", $reqId);
		$satuan_kerja->setField("ALAMAT", $reqAlamat);
		$satuan_kerja->setField("FAX", $reqFax);
		$satuan_kerja->setField("TELEPON", $reqTelepon);
		$satuan_kerja->setField("LOKASI", $reqLokasi);
		$satuan_kerja->setField("LAST_UPDATE_USER", $this->USERNAME);
		$satuan_kerja->updateAlamat();

		echo "Data berhasil disimpan.";
	}
}
