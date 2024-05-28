<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class permohonan_nomor_json extends CI_Controller
{

	function __construct()
	{
		parent::__construct();

		if (!$this->kauth->getInstance()->hasIdentity()) {
			redirect('login');
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
		$this->CABANG_ID		= $this->kauth->getInstance()->getIdentity()->CABANG_ID;
		$this->SATUAN_KERJA_ID_ASAL	= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_ID_ASAL;
		$this->CABANG		= $this->kauth->getInstance()->getIdentity()->CABANG;
	}

	function json()
	{
		$this->load->model("PermohonanNomor");
		$permohonan_nomor = new PermohonanNomor();

		// $reqKategori = $this->input->get("reqKategori");
		// echo $reqKategori;exit;



		$aColumns		= array("PERMOHONAN_NOMOR_ID", "PERUNTUKAN", "TANGGAL_SURAT", "TIPE_NASKAH_KET", "JENIS_NASKAH", "SATUAN_KERJA", "SURAT_NOMOR_FIX");
		$aColumnsAlias	= array("PERMOHONAN_NOMOR_ID", "PERUNTUKAN", "TANGGAL_SURAT", "TIPE_NASKAH", "JENIS_NASKAH", "SATUAN_KERJA", "SURAT_NOMOR_FIX");


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
			if (trim($sOrder) == "ORDER BY PERMOHONAN_NOMOR_ID asc") {
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

		$statement_privacy .= " AND (LAST_CREATE_USER = '" . $this->ID . "' OR SATUAN_KERJA_ID = '" . $this->SATUAN_KERJA_ID_ASAL . "' ) ";


		$allRecord = $permohonan_nomor->getCountByParams(array(), $statement_privacy . $statement);
		// echo $allRecord;exit;
		if ($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else
			$allRecordFilter =  $permohonan_nomor->getCountByParams(array(), $statement_privacy . $statement);

		$permohonan_nomor->selectByParams(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);
		// echo $permohonan_nomor->query;exit();

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

		while ($permohonan_nomor->nextRow()) {
			$row = array();
			for ($i = 0; $i < count($aColumns); $i++) {
				if ($aColumns[$i] == "KETERANGAN")
					$row[] = truncate($permohonan_nomor->getField($aColumns[$i]), 2);
				else
					$row[] = $permohonan_nomor->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		echo json_encode($output);
	}



	function json_approval()
	{
		$this->load->model("PermohonanNomor");
		$permohonan_nomor = new PermohonanNomor();

		// $reqKategori = $this->input->get("reqKategori");
		// echo $reqKategori;exit;

		$aColumns		= array("PERMOHONAN_NOMOR_ID", "SURAT_NOMOR", "PERUNTUKAN", "TANGGAL_SURAT", "TIPE_NASKAH_KET", "JENIS_NASKAH", "SATUAN_KERJA", "SURAT_NOMOR_FIX");
		$aColumnsAlias	= array("PERMOHONAN_NOMOR_ID", "SURAT_NOMOR", "PERUNTUKAN", "TANGGAL_SURAT", "TIPE_NASKAH", "JENIS_NASKAH", "SATUAN_KERJA", "SURAT_NOMOR_FIX");


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
			if (trim($sOrder) == "ORDER BY PERMOHONAN_NOMOR_ID asc") {
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

		$statement_privacy .= " AND B.PENERBIT_NOMOR = '" . $this->USER_GROUP . "' ";

		if ($this->USER_GROUP == "SEKRETARIS") {
			$statement_privacy .= " AND (SATUAN_KERJA_ID = '" . $this->SATUAN_KERJA_ID_ASAL . "') ";
		} else {
			$statement_privacy .= " AND (CABANG_ID = '" . $this->CABANG_ID . "') ";
		}


		$allRecord = $permohonan_nomor->getCountByParamsVerifikasi(array(), $statement_privacy . $statement);
		// echo $allRecord;exit;
		if ($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else
			$allRecordFilter =  $permohonan_nomor->getCountByParamsVerifikasi(array(), $statement_privacy . $statement);

		$permohonan_nomor->selectByParamsVerifikasi(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);
		// echo $permohonan_nomor->query;exit();

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

		while ($permohonan_nomor->nextRow()) {
			$row = array();
			for ($i = 0; $i < count($aColumns); $i++) {
				if ($aColumns[$i] == "KETERANGAN")
					$row[] = truncate($permohonan_nomor->getField($aColumns[$i]), 2);
				else
					$row[] = $permohonan_nomor->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		echo json_encode($output);
	}

	function add()
	{
		$this->load->model("PermohonanNomor");
		$permohonan_nomor = new PermohonanNomor();

		$reqMode 				= $this->input->post("reqMode");
		$reqId 					= $this->input->post("reqId");

		$reqCabangId			= $this->input->post("reqCabangId");
		$reqPeruntukan			= $this->input->post("reqPeruntukan");
		$reqKeterangan			= $this->input->post("reqKeterangan");
		$reqTanggalSurat		= $this->input->post("reqTanggalSurat");
		$reqTipeNaskah			= $this->input->post("reqTipeNaskah");
		$reqSuratMasukId		= $this->input->post("reqSuratMasukId");
		$reqSuratKeluarId		= $this->input->post("reqSuratKeluarId");
		$reqJenisNaskahId		= $this->input->post("reqJenisNaskahId");
		$reqJenisNaskah			= $this->input->post("reqJenisNaskah");
		$reqKdLevel				= $this->input->post("reqKdLevel");
		$reqSatuanKerjaId		= $this->input->post("reqSatuanKerjaId");
		$reqSatuanKerja			= $this->input->post("reqSatuanKerja");

		//echo $reqMode;
		$permohonan_nomor->setField("PERMOHONAN_NOMOR_ID", $reqId);
		$permohonan_nomor->setField("CABANG_ID", $this->CABANG_ID);
		$permohonan_nomor->setField("SATUAN_KERJA_ID", $reqSatuanKerjaId);
		$permohonan_nomor->setField("SATUAN_KERJA", $reqSatuanKerja);
		$permohonan_nomor->setField("JENIS_NASKAH_ID", $reqJenisNaskahId);
		$permohonan_nomor->setField("JENIS_NASKAH", $reqJenisNaskah);
		$permohonan_nomor->setField("KD_LEVEL", $reqKdLevel);
		$permohonan_nomor->setField("PERUNTUKAN", $reqPeruntukan);
		$permohonan_nomor->setField("KETERANGAN", $reqKeterangan);
		$permohonan_nomor->setField("TANGGAL_SURAT", dateToDbCheck($reqTanggalSurat));
		$permohonan_nomor->setField("TIPE_NASKAH", $reqTipeNaskah);
		$permohonan_nomor->setField("SURAT_MASUK_ID", $reqSuratMasukId);
		$permohonan_nomor->setField("SURAT_KELUAR_ID", $reqSuratKeluarId);

		if ($reqMode == "insert") {
			$permohonan_nomor->setField("LAST_CREATE_USER", $this->USERNAME);
			$permohonan_nomor->setField("LAST_CREATED_DATE", "CURRENT_DATE");
			$permohonan_nomor->insert();
		} else {
			$permohonan_nomor->setField("LAST_UPDATE_USER", $this->USERNAME);
			$permohonan_nomor->setField("LAST_UPDATED_DATE", "CURRENT_DATE");
			$permohonan_nomor->update();
		}

		echo "Data berhasil disimpan.";
	}


	function approval()
	{
		$this->load->model("PermohonanNomor");
		$permohonan_nomor = new PermohonanNomor();

		$reqMode 					= $this->input->post("reqMode");
		$reqId 						= $this->input->post("reqId");
		$reqNomorSurat 				= $this->input->post("reqNomorSurat");
		// echo $reqNomorSurat;exit();

		//echo $reqMode;
		$permohonan_nomor->setField("PERMOHONAN_NOMOR_ID", $reqId);
		$permohonan_nomor->setField("SURAT_NOMOR", $reqNomorSurat);
		$permohonan_nomor->setField("LAST_APPROVE_USER", $this->USERNAME);
		$permohonan_nomor->setField("LAST_APPROVE_DATE", "CURRENT_DATE");



		$statement_privacy .= " AND B.PENERBIT_NOMOR = '" . $this->USER_GROUP . "' ";
		if ($this->USER_GROUP == "SEKRETARIS")
			$statement_privacy .= " AND (SATUAN_KERJA_ID = '" . $this->SATUAN_KERJA_ID_ASAL . "') ";
		else
			$statement_privacy .= " AND (CABANG_ID = '" . $this->CABANG_ID . "') ";

		$permohonan_nomor->approval($statement);

		echo "Data berhasil disimpan.";
	}


	function delete()
	{
		$reqId	= $this->input->get('reqId');
		$this->load->model("PermohonanNomor");
		$permohonan_nomor = new PermohonanNomor();

		$permohonan_nomor->setField("PERMOHONAN_NOMOR_ID", $reqId);

		if ($permohonan_nomor->delete())
			$arrJson["PESAN"] = "Data berhasil dihapus.";
		else
			$arrJson["PESAN"] = "Data gagal dihapus.";

		echo json_encode($arrJson);
	}

	function generate_nomor()
	{
		$this->load->model("PermohonanNomor");
		$this->load->model("SuratMasuk");
		$permohonan_nomor = new PermohonanNomor();
		$surat_masuk = new SuratMasuk();

		$reqId 					= $this->input->get("reqId");
		$reqSatuanKerjaIdAsal 	= $this->input->get("reqSatuanKerjaIdAsal");
		$reqJenisNaskahId 		= $this->input->get("reqJenisNaskahId");
		$reqSifatNaskah 		= $this->input->get("reqSifatNaskah");
		$reqKlasifikasiKode 	= $this->input->get("reqKlasifikasiKode");
		$reqJenisSurat 			= $this->input->get("reqJenisSurat");

		//ambil awal huruf sifat naskah
		$arrSifatNaskah = explode(" ", $reqSifatNaskah);
		$singkatan = "";
		foreach ($arrSifatNaskah as $kata) {
			$singkatan .= substr($kata, 0, 1);
		}

		//generate nomor dan insert
		$nomor = $permohonan_nomor->getGenerateNomor($this->CABANG_ID, $reqSatuanKerjaIdAsal, $reqJenisNaskahId, "CURRENT_DATE", "NULL", $reqKlasifikasiKode, $reqJenisSurat, $reqId);
		$arrNomor = explode("-", $nomor);

		if ($arrNomor[1] != "") {
			$nomorBaru = $arrNomor[0] . "-" . $singkatan;
		} else {
			$nomorBaru = $nomor;
		}

		$surat_masuk->setField("SURAT_MASUK_ID", $reqId);
		$surat_masuk->setField("FIELD", "NOMOR");
		$surat_masuk->setField("FIELD_VALUE", $nomorBaru);
		$surat_masuk->setField("LAST_UPDATE_USER", $this->USERNAME);
		$surat_masuk->updateByField();

		include_once("libraries/phpqrcode/qrlib.php");
		$getinfottd = new SuratMasuk();
		$getinfottd->selectByParamsGetInfoTtdSurat(array("A.SURAT_MASUK_ID" => $reqId));
		$getinfottd->firstRow();

				// tambahan khusus
		$pesanQrCode = "ID: ".$getinfottd->getField("TTD_KODE")."\n";
		$pesanQrCode.= "ApprovedBy: ".$getinfottd->getField("APPROVED_BY")."\n";
		$pesanQrCode.= "Nomor Surat: ".$getinfottd->getField("NOMOR")."\n";

		$qrdate= $getinfottd->getField("APPROVAL_QR_DATE");
		if(!empty($qrdate))
		{
			$pesanQrCode.= "Tanggal Surat: ".str_replace("-", "/", dateTimeToPageCheck($qrdate));
		}

		$FILE_DIR = "uploads/" . $reqId . "/";
		makedirs($FILE_DIR);
		$filename = $FILE_DIR . $getinfottd->getField("TTD_KODE") . '.png';
		$errorCorrectionLevel = 'L';
		$matrixPointSize = 5;
		QRcode::png($pesanQrCode, $filename, $errorCorrectionLevel, $matrixPointSize, 2);

		echo $nomorBaru;
	}


	function combo_statement()
	{
		$this->load->model("PermohonanNomor");
		$permohonan_nomor = new PermohonanNomor();

		$reqTipeNaskah = $this->input->get("reqTipeNaskah");
		$reqSatuanKerjaId = $this->input->get("reqSatuanKerjaId");
		$reqJenisNaskahId = $this->input->get("reqJenisNaskahId");
		$reqId = $this->input->get("reqId");


		if ($reqTipeNaskah == "") {
			$reqTipeNaskah = "INTERNAL";
		}

		$statement = " AND TIPE_NASKAH = '" . $reqTipeNaskah . "' ";

		if ($reqId == "") {
			$statement .= " AND SURAT_NOMOR IS NOT NULL AND SURAT_MASUK_ID IS NULL AND SURAT_KELUAR_ID IS NULL ";
		} else {
			if ($reqTipeNaskah == "INTERNAL") {
				$statement .= " AND SURAT_NOMOR IS NOT NULL AND ((SURAT_MASUK_ID IS NULL AND SURAT_KELUAR_ID IS NULL) OR (SURAT_MASUK_ID = '" . $reqId . "')) ";
			} else {
				$statement .= " AND SURAT_NOMOR IS NOT NULL AND ((SURAT_MASUK_ID IS NULL AND SURAT_KELUAR_ID IS NULL) OR (SURAT_KELUAR_ID = '" . $reqId . "')) ";
			}
		}

		$permohonan_nomor->selectByParams(array("A.SATUAN_KERJA_ID" => (int)$reqSatuanKerjaId, "JENIS_NASKAH_ID" => (int)$reqJenisNaskahId, "LAST_CREATE_USER" => $this->USERNAME), -1, -1, $statement);
		// echo $permohonan_nomor->query;exit;
		$i = 0;
		$arr_json = array();
		while ($permohonan_nomor->nextRow()) {
			$arr_json[$i]['id']		= $permohonan_nomor->getField("PERMOHONAN_NOMOR_ID");
			$arr_json[$i]['text']	= $permohonan_nomor->getField("SURAT_NOMOR") . " | " . $permohonan_nomor->getField("PERUNTUKAN") . "";
			$i++;
		}

		echo json_encode($arr_json);
	}

	function combo()
	{

		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : 50;
		$offset = ($page - 1) * $rows;

		$reqPencarian = $this->input->get("reqPencarian");
		$reqMode = $this->input->get("reqMode");


		$this->load->model("PermohonanNomor");
		$permohonan_nomor = new PermohonanNomor();

		if ($reqPencarian == "") {
		} else
			// $statement = " AND UPPER(A.NAMA) LIKE '%".strtoupper($reqPencarian)."%' ";

			$statement_privacy = "";
		//if($reqMode == "user_login")
		//	$statement_privacy .= " AND NOT EXISTS(SELECT 1 FROM USER_LOGIN X WHERE X.PERMOHONAN_NOMOR_ID = A.PERMOHONAN_NOMOR_ID) ";


		$rowCount = $permohonan_nomor->getCountByParams(array(), $statement . $statement_privacy);
		$permohonan_nomor->selectByParams(array(), $rows, $offset, $statement . $statement_privacy);
		$i = 0;
		$items = array();
		while ($permohonan_nomor->nextRow()) {
			$row['id']		= $permohonan_nomor->getField("PERMOHONAN_NOMOR_ID");
			$row['text']	= $permohonan_nomor->getField("NAMA");
			$row['PERMOHONAN_NOMOR_ID']	= $permohonan_nomor->getField("PERMOHONAN_NOMOR_ID");
			$row['NAMA']	= $permohonan_nomor->getField("NAMA");
			$row['CABANG']	= $permohonan_nomor->getField("SATUAN_KERJA");

			$row['state'] = 'close';
			$i++;
			array_push($items, $row);
		}
		$result["rows"] = $items;
		$result["total"] = $rowCount;
		echo json_encode($result);
	}
}
