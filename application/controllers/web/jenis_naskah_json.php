<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class jenis_naskah_json extends CI_Controller
{

	function __construct()
	{
		parent::__construct();

		if (!$this->kauth->getInstance()->hasIdentity()) {
			//redirect('login');
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
		$this->CABANG		= $this->kauth->getInstance()->getIdentity()->CABANG;
		$this->HIRARKI		= $this->kauth->getInstance()->getIdentity()->HIRARKI;
		$this->KD_LEVEL		= $this->kauth->getInstance()->getIdentity()->KD_LEVEL;
		$this->KELOMPOK_JABATAN		= $this->kauth->getInstance()->getIdentity()->KELOMPOK_JABATAN;
	}

	function json()
	{
		$this->load->model("JenisNaskah");
		$jenis_naskah = new JenisNaskah();

		$reqKategori = $this->input->get("reqKategori");
		// echo $reqKategori;exit;

		$aColumns		= array("JENIS_NASKAH_ID", "NAMA", "NAMA_LEVEL", "NAMA_LEVEL_CABANG", "KODE_SURAT", "TIPE_NASKAH");
		$aColumnsAlias	= array("JENIS_NASKAH_ID", "NAMA", "NAMA_LEVEL", "NAMA_LEVEL_CABANG", "KODE_SURAT", "TIPE_NASKAH");


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
			if (trim($sOrder) == "ORDER BY JENIS_NASKAH_ID asc") {
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


		$statement = " AND (UPPER(A.NAMA) LIKE '%" . strtoupper($_GET['sSearch']) . "%')";
		$allRecord = $jenis_naskah->getCountByParams(array(), $statement_privacy . $statement);
		// echo $allRecord;exit;
		if ($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else
			$allRecordFilter =  $jenis_naskah->getCountByParams(array(), $statement_privacy . $statement);

		$jenis_naskah->selectByParams(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);
		// echo $jenis_naskah ->query; exit;
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

		while ($jenis_naskah->nextRow()) {
			$row = array();
			for ($i = 0; $i < count($aColumns); $i++) {
				if ($aColumns[$i] == "KETERANGAN")
					$row[] = truncate($jenis_naskah->getField($aColumns[$i]), 2);
				elseif ($aColumns[$i] == "ATTACHMENT")
					$row[] = "<a href='uploads/'" . $jenis_naskah->getField($aColumns[$i]) . " target='_blank'>" . $jenis_naskah->getField($aColumns[$i]) . "</a>";
				else
					$row[] = $jenis_naskah->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		echo json_encode($output);
	}

	function add()
	{
		$this->load->model("JenisNaskah");
		$this->load->model("NaskahTemplate");
		$jenis_naskah = new JenisNaskah();
		$naskah_template = new NaskahTemplate();

		$reqMode 					= $this->input->post("reqMode");
		$reqId 						= $this->input->post("reqId");

		$reqNama					= $this->input->post("reqNama");
		$reqPrefix					= $this->input->post("reqPrefix");
		$reqKodeSurat				= $this->input->post("reqKodeSurat");
		$reqKodeSuratKeluar			= $this->input->post("reqKodeSuratKeluar");
		$reqDigit					= $this->input->post("reqDigit");
		$reqKeterangan				= $this->input->post("reqKeterangan");
		$reqNaskahTemplate			= $this->input->post("reqNaskahTemplate");
		$reqKdLevel					= $this->input->post("reqKdLevel");
		$reqNamaLevel				= $this->input->post("reqNamaLevel");
		$reqKdLevelCabang			= $this->input->post("reqKdLevelCabang");
		$reqNamaLevelCabang			= $this->input->post("reqNamaLevelCabang");
		$reqTipeNaskah				= $this->input->post("reqTipeNaskah");
		$reqPenerbit				= $this->input->post("reqPenerbit");
		$reqJenisTTD				= $this->input->post("reqJenisTTD");


		// $naskah_template->selectByParams(array("A.LINK_URL" => $reqNaskahTemplate));
		// $naskah_template->firstRow();

		// $reqNaskahTemplateId = $naskah_template->getField("NASKAH_TEMPLATE_ID");;

		$this->load->library("FileHandler");
		$file = new FileHandler();
		$FILE_DIR = "uploads/";
		$reqLinkFile 			= $_FILES["reqLinkFile"];
		$reqLinkFileTempSize	=  $this->input->post("reqLinkFileTempSize");
		$reqLinkFileTempTipe	=  $this->input->post("reqLinkFileTempTipe");
		$reqLinkFileTemp		=  $this->input->post("reqLinkFileTemp");

		$jenis_naskah->setField("JENIS_NASKAH_ID", $reqId);
		$jenis_naskah->setField("NAMA", $reqNama);
		$jenis_naskah->setField("PREFIX", $reqPrefix);
		$jenis_naskah->setField("KODE_SURAT", $reqKodeSurat);
		$jenis_naskah->setField("KODE_SURAT_KELUAR", $reqKodeSuratKeluar);
		$jenis_naskah->setField("DIGIT_NOMOR", $reqDigit);
		$jenis_naskah->setField("KETERANGAN", $reqKeterangan);
		$jenis_naskah->setField("LINK_URL", $reqNaskahTemplate);
		$jenis_naskah->setField("KD_LEVEL", $reqKdLevel);
		$jenis_naskah->setField("NAMA_LEVEL", $reqNamaLevel);
		$jenis_naskah->setField("KD_LEVEL_CABANG", $reqKdLevelCabang);
		$jenis_naskah->setField("NAMA_LEVEL_CABANG", $reqNamaLevelCabang);
		$jenis_naskah->setField("TIPE_NASKAH", $reqTipeNaskah);
		$jenis_naskah->setField("PENERBIT_NOMOR", $reqPenerbit);
		$jenis_naskah->setField("JENIS_TTD", $reqJenisTTD);




		$reqJenis = "JENIS-NASKAH-" . generateZero($reqId, 4);
		for ($i = 0; $i < count($reqLinkFile); $i++) {
			$renameFile = $reqJenis . date("Ymdhis") . rand() . "." . getExtension($reqLinkFile['name'][$i]);

			if ($file->uploadToDirArray('reqLinkFile', $FILE_DIR, $renameFile, $i)) {
				$insertLinkSize = $file->uploadedSize;
				$insertLinkTipe =  $file->uploadedExtension;
				$insertLinkFile =  $renameFile;
			}
		}

		$jenis_naskah->setField("ATTACHMENT", $insertLinkFile);

		if ($reqMode == "insert") {
			$jenis_naskah->setField("LAST_CREATE_USER", $this->USERNAME);
			$jenis_naskah->insert();
		} else {
			$jenis_naskah->setField("LAST_UPDATE_USER", $this->USERNAME);
			$jenis_naskah->update();
		}

		echo "Data berhasil disimpan.";
	}



	function add_template()
	{
		$this->load->model("JenisNaskah");
		$jenis_naskah = new JenisNaskah();

		$reqMode 					= $this->input->post("reqMode");
		$reqId 						= $this->input->post("reqId");


		$this->load->library("FileHandler");
		$file = new FileHandler();
		$FILE_DIR = "uploads/";
		$reqLinkFile 			= $_FILES["reqLinkFile"];
		$reqLinkFileTempSize	=  $this->input->post("reqLinkFileTempSize");
		$reqLinkFileTempTipe	=  $this->input->post("reqLinkFileTempTipe");
		$reqLinkFileTemp		=  $this->input->post("reqLinkFileTemp");
		$reqJenisNaskahId		=  $this->input->post("reqJenisNaskahId");

		$jenis_naskah->setField("SATUAN_KERJA_ID", $reqId);
		$jenis_naskah->deleteTemplate();

		$reqJenis = "TEMPLATE" . generateZero($reqId, 4);
		for ($i = 0; $i < count($reqLinkFile); $i++) {
			$renameFile = $reqJenis . generateZero($reqJenisNaskahId[$i], 4) . "." . getExtension($reqLinkFile['name'][$i]);

			if ($file->uploadToDirArray('reqLinkFile', $FILE_DIR, $renameFile, $i)) {

				$insertLinkSize = $file->uploadedSize;
				$insertLinkTipe =  $file->uploadedExtension;
				$insertLinkFile =  $renameFile;
			} else
				$insertLinkFile =  $reqLinkFileTemp[$i];

			if ($reqJenisNaskahId[$i] == "") {
			} else {
				$jenis_naskah->setField("SATUAN_KERJA_ID", $reqId);
				$jenis_naskah->setField("JENIS_NASKAH_ID", $reqJenisNaskahId[$i]);
				$jenis_naskah->setField("ATTACHMENT", $insertLinkFile);
				$jenis_naskah->setField("LAST_CREATE_USER", $this->USERNAME);
				$jenis_naskah->insertTemplate();
			}
		}

		echo "Data berhasil disimpan.";
	}

	function delete()
	{
		$reqId	= $this->input->get('reqId');
		$this->load->model("JenisNaskah");
		$jenis_naskah = new JenisNaskah();


		$jenis_naskah->setField("JENIS_NASKAH_ID", $reqId);
		if ($jenis_naskah->delete())
			$arrJson["PESAN"] = "Data berhasil dihapus.";
		else
			$arrJson["PESAN"] = "Data gagal dihapus.";

		echo json_encode($arrJson);
	}

	function combo()
	{
		$this->load->model("JenisNaskah");
		$jenis_naskah = new JenisNaskah();

		$jenis_naskah->selectByParams(array("NOT JENIS_NASKAH_ID" => "0"));
		$i = 0;
		while ($jenis_naskah->nextRow()) {
			$arr_json[$i]['id']		= $jenis_naskah->getField("JENIS_NASKAH_ID");
			$arr_json[$i]['text']	= $jenis_naskah->getField("NAMA");
			$arr_json[$i]['JENIS_TTD']	= $jenis_naskah->getField("JENIS_TTD");
			$arr_json[$i]['PENERBIT_NOMOR']	= $jenis_naskah->getField("PENERBIT_NOMOR");
			$i++;
		}

		echo json_encode($arr_json);
	}


	function combo_statement()
	{
		$this->load->model("JenisNaskah");
		$jenis_naskah = new JenisNaskah();

		$reqId = $this->input->get("reqId");
		$reqKelompokJabatan = $this->input->get("reqKelompokJabatan");


		$statement .= " AND TIPE_NASKAH LIKE '%" . $reqId . "%' ";

		$arr_json = array();
		$jenis_naskah->selectByParams(array("NOT JENIS_NASKAH_ID" => "0"), -1, -1, $statement);
		// echo $jenis_naskah->query;exit;
		$i = 0;
		while ($jenis_naskah->nextRow()) {
			$arr_json[$i]['id']		= $jenis_naskah->getField("JENIS_NASKAH_ID");
			$arr_json[$i]['text']	= $jenis_naskah->getField("NAMA");
			$arr_json[$i]['JENIS_TTD']	= $jenis_naskah->getField("JENIS_TTD");
			$arr_json[$i]['PENERBIT_NOMOR']	= $jenis_naskah->getField("PENERBIT_NOMOR");

			if ($this->CABANG_ID == "01") {
				$arr_json[$i]['KD_LEVEL']	= $jenis_naskah->getField("KD_LEVEL");
			} else {
				$arr_json[$i]['KD_LEVEL']	= $jenis_naskah->getField("KD_LEVEL_CABANG");
			}

			$i++;
		}

		echo json_encode($arr_json);
	}




	function combo_request()
	{
		$this->load->model("JenisNaskah");
		$jenis_naskah = new JenisNaskah();

		$reqId = $this->input->get("reqId");


		$statement = " AND TIPE_NASKAH LIKE '%" . $reqId . "%' ";
		$statement .= " AND NOT COALESCE(NULLIF(KODE_SURAT, ''), 'X') = 'X' ";

		$arr_json = array();
		$jenis_naskah->selectByParams(array("NOT JENIS_NASKAH_ID" => "0"), -1, -1, $statement);
		$i = 0;
		while ($jenis_naskah->nextRow()) {
			$arr_json[$i]['id']		= $jenis_naskah->getField("JENIS_NASKAH_ID");
			$arr_json[$i]['text']	= $jenis_naskah->getField("NAMA");
			$arr_json[$i]['PENERBIT_NOMOR']	= $jenis_naskah->getField("PENERBIT_NOMOR");
			if ($this->CABANG_ID == "01")
				$arr_json[$i]['KD_LEVEL']	= $jenis_naskah->getField("KD_LEVEL");
			else
				$arr_json[$i]['KD_LEVEL']	= $jenis_naskah->getField("KD_LEVEL_CABANG");
			$i++;
		}

		echo json_encode($arr_json);
	}


	function combo_level()
	{
		$this->load->model("JenisNaskah");
		$jenis_naskah = new JenisNaskah();

		$reqId = $this->input->get("reqId");


		$statement = " AND TIPE_NASKAH LIKE '%" . $reqId . "%' ";


		$jenis_naskah->selectByParams(array("NOT JENIS_NASKAH_ID" => "0"), -1, -1, $statement);
		$i = 0;
		$arr_json = array();
		while ($jenis_naskah->nextRow()) {
			$arr_json[$i]['id']		= $jenis_naskah->getField("JENIS_NASKAH_ID");
			$arr_json[$i]['text']	= $jenis_naskah->getField("NAMA");
			if ($this->CABANG_ID == "01")
				$arr_json[$i]['KD_LEVEL']	= $jenis_naskah->getField("KD_LEVEL");
			else
				$arr_json[$i]['KD_LEVEL']	= $jenis_naskah->getField("KD_LEVEL_CABANG");

			$i++;
		}

		echo json_encode($arr_json);
	}
}
