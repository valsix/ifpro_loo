<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class produk_json extends CI_Controller
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
		$this->load->model("Produk");
		$produk = new Produk();

		$reqKategori = $this->input->get("reqKategori");
		// echo $reqKategori;exit;

		$aColumns		= array("PRODUK_ID", "KODE", "NAMA", "DESKRIPSI", "NAMA_BRAND_CUSTOMER");
		$aColumnsAlias	= $aColumns;


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
			if (trim($sOrder) == "ORDER BY PRODUK_ID asc") {
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
		$allRecord = $produk->getCountByParams(array(), $statement_privacy . $statement);
		// echo $allRecord;exit;
		if ($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else
			$allRecordFilter =  $produk->getCountByParams(array(), $statement_privacy . $statement);

		$produk->selectByParams(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);
		// echo $produk ->query; exit;
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

		while ($produk->nextRow()) {
			$row = array();
			for ($i = 0; $i < count($aColumns); $i++) {
				if ($aColumns[$i] == "KETERANGAN")
					$row[] = truncate($produk->getField($aColumns[$i]), 2);
				elseif ($aColumns[$i] == "ATTACHMENT")
					$row[] = "<a href='uploads/'" . $produk->getField($aColumns[$i]) . " target='_blank'>" . $produk->getField($aColumns[$i]) . "</a>";
				else
					$row[] = $produk->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		echo json_encode($output);
	}

	function add()
	{
		$this->load->model("Produk");
		// $this->load->model("NaskahTemplate");
		$produk = new Produk();
		// $naskah_template = new NaskahTemplate();

		$reqMode 					= $this->input->post("reqMode");
		$reqId 						= $this->input->post("reqId");

		$reqKode					= $this->input->post("reqKode");
		$reqNama					= $this->input->post("reqNama");
		$reqDeskripsi				= $this->input->post("reqDeskripsi");
		$reqCustomerId				= $this->input->post("reqCustomerId");


		// $naskah_template->selectByParams(array("A.LINK_URL" => $reqNaskahTemplate));
		// $naskah_template->firstRow();

		// $reqNaskahTemplateId = $naskah_template->getField("NASKAH_TEMPLATE_ID");;

		// $this->load->library("FileHandler");
		// $file = new FileHandler();
		// $FILE_DIR = "uploads/";
		// $reqLinkFile 			= $_FILES["reqLinkFile"];
		// $reqLinkFileTempSize	=  $this->input->post("reqLinkFileTempSize");
		// $reqLinkFileTempTipe	=  $this->input->post("reqLinkFileTempTipe");
		// $reqLinkFileTemp		=  $this->input->post("reqLinkFileTemp");

		$produk->setField("PRODUK_ID", $reqId);
		$produk->setField("KODE", $reqKode);
		$produk->setField("NAMA", $reqNama);
		$produk->setField("DESKRIPSI", $reqDeskripsi);
		$produk->setField("CUSTOMER_ID", $reqCustomerId);


		// $reqJenis = "JENIS-NASKAH-" . generateZero($reqId, 4);
		// for ($i = 0; $i < count($reqLinkFile); $i++) {
		// 	$renameFile = $reqJenis . date("Ymdhis") . rand() . "." . getExtension($reqLinkFile['name'][$i]);

		// 	if ($file->uploadToDirArray('reqLinkFile', $FILE_DIR, $renameFile, $i)) {
		// 		$insertLinkSize = $file->uploadedSize;
		// 		$insertLinkTipe =  $file->uploadedExtension;
		// 		$insertLinkFile =  $renameFile;
		// 	}
		// }

		// $produk->setField("ATTACHMENT", $insertLinkFile);

		if ($reqMode == "insert") {
			$produk->setField("LAST_CREATE_USER", $this->USERNAME);
			$produk->insert();
		} else {
			$produk->setField("LAST_UPDATE_USER", $this->USERNAME);
			$produk->update();
		}

		echo "Data berhasil disimpan.";
	}



	function add_template()
	{
		$this->load->model("Produk");
		$produk = new Produk();

		$reqMode 					= $this->input->post("reqMode");
		$reqId 						= $this->input->post("reqId");


		$this->load->library("FileHandler");
		$file = new FileHandler();
		$FILE_DIR = "uploads/";
		$reqLinkFile 			= $_FILES["reqLinkFile"];
		$reqLinkFileTempSize	=  $this->input->post("reqLinkFileTempSize");
		$reqLinkFileTempTipe	=  $this->input->post("reqLinkFileTempTipe");
		$reqLinkFileTemp		=  $this->input->post("reqLinkFileTemp");
		$reqProdukId		=  $this->input->post("reqProdukId");

		$produk->setField("SATUAN_KERJA_ID", $reqId);
		$produk->deleteTemplate();

		$reqJenis = "TEMPLATE" . generateZero($reqId, 4);
		for ($i = 0; $i < count($reqLinkFile); $i++) {
			$renameFile = $reqJenis . generateZero($reqProdukId[$i], 4) . "." . getExtension($reqLinkFile['name'][$i]);

			if ($file->uploadToDirArray('reqLinkFile', $FILE_DIR, $renameFile, $i)) {

				$insertLinkSize = $file->uploadedSize;
				$insertLinkTipe =  $file->uploadedExtension;
				$insertLinkFile =  $renameFile;
			} else
				$insertLinkFile =  $reqLinkFileTemp[$i];

			if ($reqProdukId[$i] == "") {
			} else {
				$produk->setField("SATUAN_KERJA_ID", $reqId);
				$produk->setField("CUSTOMER_ID", $reqProdukId[$i]);
				$produk->setField("ATTACHMENT", $insertLinkFile);
				$produk->setField("LAST_CREATE_USER", $this->USERNAME);
				$produk->insertTemplate();
			}
		}

		echo "Data berhasil disimpan.";
	}

	function delete()
	{
		$reqId	= $this->input->get('reqId');
		$this->load->model("Produk");
		$produk = new Produk();


		$produk->setField("PRODUK_ID", $reqId);
		if ($produk->delete())
			$arrJson["PESAN"] = "Data berhasil dihapus.";
		else
			$arrJson["PESAN"] = "Data gagal dihapus.";

		echo json_encode($arrJson);
	}

	function combo()
	{
		$this->load->model("Produk");
		$produk = new Produk();

		$produk->selectByParams(array("NOT CUSTOMER_ID" => "0"));
		$i = 0;
		while ($produk->nextRow()) {
			$arr_json[$i]['id']		= $produk->getField("CUSTOMER_ID");
			$arr_json[$i]['text']	= $produk->getField("NAMA");
			$arr_json[$i]['JENIS_TTD']	= $produk->getField("JENIS_TTD");
			$arr_json[$i]['PENERBIT_NOMOR']	= $produk->getField("PENERBIT_NOMOR");
			$i++;
		}

		echo json_encode($arr_json);
	}


	function combo_statement()
	{
		$this->load->model("Produk");
		$produk = new Produk();

		$reqId = $this->input->get("reqId");
		$reqKelompokJabatan = $this->input->get("reqKelompokJabatan");


		$statement .= " AND TIPE_NASKAH LIKE '%" . $reqId . "%' ";

		$arr_json = array();
		$produk->selectByParams(array("NOT CUSTOMER_ID" => "0"), -1, -1, $statement);
		// echo $produk->query;exit;
		$i = 0;
		while ($produk->nextRow()) {
			$arr_json[$i]['id']		= $produk->getField("CUSTOMER_ID");
			$arr_json[$i]['text']	= $produk->getField("NAMA");
			$arr_json[$i]['JENIS_TTD']	= $produk->getField("JENIS_TTD");
			$arr_json[$i]['PENERBIT_NOMOR']	= $produk->getField("PENERBIT_NOMOR");

			if ($this->CABANG_ID == "01") {
				$arr_json[$i]['KD_LEVEL']	= $produk->getField("KD_LEVEL");
			} else {
				$arr_json[$i]['KD_LEVEL']	= $produk->getField("KD_LEVEL_CABANG");
			}

			$i++;
		}

		echo json_encode($arr_json);
	}




	function combo_request()
	{
		$this->load->model("Produk");
		$produk = new Produk();

		$reqId = $this->input->get("reqId");


		$statement = " AND TIPE_NASKAH LIKE '%" . $reqId . "%' ";
		$statement .= " AND NOT COALESCE(NULLIF(KODE_SURAT, ''), 'X') = 'X' ";

		$arr_json = array();
		$produk->selectByParams(array("NOT CUSTOMER_ID" => "0"), -1, -1, $statement);
		$i = 0;
		while ($produk->nextRow()) {
			$arr_json[$i]['id']		= $produk->getField("CUSTOMER_ID");
			$arr_json[$i]['text']	= $produk->getField("NAMA");
			$arr_json[$i]['PENERBIT_NOMOR']	= $produk->getField("PENERBIT_NOMOR");
			if ($this->CABANG_ID == "01")
				$arr_json[$i]['KD_LEVEL']	= $produk->getField("KD_LEVEL");
			else
				$arr_json[$i]['KD_LEVEL']	= $produk->getField("KD_LEVEL_CABANG");
			$i++;
		}

		echo json_encode($arr_json);
	}


	function combo_level()
	{
		$this->load->model("Produk");
		$produk = new Produk();

		$reqId = $this->input->get("reqId");


		$statement = " AND TIPE_NASKAH LIKE '%" . $reqId . "%' ";


		$produk->selectByParams(array("NOT CUSTOMER_ID" => "0"), -1, -1, $statement);
		$i = 0;
		$arr_json = array();
		while ($produk->nextRow()) {
			$arr_json[$i]['id']		= $produk->getField("CUSTOMER_ID");
			$arr_json[$i]['text']	= $produk->getField("NAMA");
			if ($this->CABANG_ID == "01")
				$arr_json[$i]['KD_LEVEL']	= $produk->getField("KD_LEVEL");
			else
				$arr_json[$i]['KD_LEVEL']	= $produk->getField("KD_LEVEL_CABANG");

			$i++;
		}

		echo json_encode($arr_json);
	}
}

