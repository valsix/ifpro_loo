<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class lokasi_loo_detil_json extends CI_Controller
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
		$this->load->model("LokasiLooDetil");
		$set = new LokasiLooDetil();

		$reqKategori = $this->input->get("reqKategori");
		// echo $reqKategori;exit;

		$aColumns		= array("LOKASI_LOO_DETIL_ID", "NAMA_LOKASI_LOO", "KODE", "NAMA", "LANTAI", "AREA_INFO", "JENIS_INFO", "PRIME_INFO", "LUAS", "KD_TARIF", "DESKRIPSI");
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
			if (trim($sOrder) == "ORDER BY LOKASI_LOO_DETIL_ID asc") {
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
		$allRecord = $set->getCountByParams(array(), $statement_privacy . $statement);
		// echo $allRecord;exit;
		if ($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else
			$allRecordFilter =  $set->getCountByParams(array(), $statement_privacy . $statement);

		$set->selectByParams(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);
		// echo $set ->query; exit;
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

		while ($set->nextRow()) {
			$row = array();
			for ($i = 0; $i < count($aColumns); $i++) {
				if ($aColumns[$i] == "KETERANGAN")
					$row[] = truncate($set->getField($aColumns[$i]), 2);
				elseif ($aColumns[$i] == "ATTACHMENT")
					$row[] = "<a href='uploads/'" . $set->getField($aColumns[$i]) . " target='_blank'>" . $set->getField($aColumns[$i]) . "</a>";
				elseif ($aColumns[$i] == "LANTAI")
					$row[] = $set->getField('NAMA_LANTAI')." (".$set->getField('TIPE_LANTAI_INFO').")";
				elseif ($aColumns[$i] == "KD_TARIF")
					$row[] = currencyToPage($set->getField($aColumns[$i]),false);
				elseif ($aColumns[$i] == "LUAS")
					$row[] = dotToComma($set->getField($aColumns[$i]));
				else
					$row[] = $set->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		echo json_encode($output);
	}

	function add()
	{
		$this->load->model("LokasiLooDetil");
		// $this->load->model("NaskahTemplate");
		$set = new LokasiLooDetil();
		// $naskah_template = new NaskahTemplate();

		$reqMode 					= $this->input->post("reqMode");
		$reqId 						= $this->input->post("reqId");

		$reqLokasiLooId= $this->input->post("reqLokasiLooId");
		$reqKode= $this->input->post("reqKode");
		$reqNama= $this->input->post("reqNama");
		$reqLantaiLooId= $this->input->post("reqLantaiLooId");
		$reqArea= $this->input->post("reqArea");
		$reqJenis= $this->input->post("reqJenis");
		$reqPrime= $this->input->post("reqPrime");
		$reqLuas= $this->input->post("reqLuas");
		$reqKdTarif= $this->input->post("reqKdTarif");
		$reqDeskripsi= $this->input->post("reqDeskripsi");

		$set->setField("LOKASI_LOO_DETIL_ID", $reqId);
		$set->setField("LOKASI_LOO_ID", $reqLokasiLooId);
		$set->setField("KODE", $reqKode);
		$set->setField("NAMA", $reqNama);
		$set->setField("LANTAI_LOO_ID", $reqLantaiLooId);
		$set->setField("AREA", $reqArea);
		$set->setField("JENIS", $reqJenis);
		$set->setField("PRIME", $reqPrime);
		$set->setField("LUAS", dotToNo($reqLuas));
		$set->setField("KD_TARIF", dotToNo($reqKdTarif));
		$set->setField("DESKRIPSI", $reqDeskripsi);

		if ($reqMode == "insert") {
			$set->setField("LAST_CREATE_USER", $this->USERNAME);
			$set->insert();
		} else {
			$set->setField("LAST_UPDATE_USER", $this->USERNAME);
			$set->update();
		}

		echo "Data berhasil disimpan.";
	}



	function add_template()
	{
		$this->load->model("LokasiLooDetil");
		$set = new LokasiLooDetil();

		$reqMode 					= $this->input->post("reqMode");
		$reqId 						= $this->input->post("reqId");


		$this->load->library("FileHandler");
		$file = new FileHandler();
		$FILE_DIR = "uploads/";
		$reqLinkFile 			= $_FILES["reqLinkFile"];
		$reqLinkFileTempSize	=  $this->input->post("reqLinkFileTempSize");
		$reqLinkFileTempTipe	=  $this->input->post("reqLinkFileTempTipe");
		$reqLinkFileTemp		=  $this->input->post("reqLinkFileTemp");
		$reqLokasiLooDetilId		=  $this->input->post("reqLokasiLooDetilId");

		$set->setField("SATUAN_KERJA_ID", $reqId);
		$set->deleteTemplate();

		$reqJenis = "TEMPLATE" . generateZero($reqId, 4);
		for ($i = 0; $i < count($reqLinkFile); $i++) {
			$renameFile = $reqJenis . generateZero($reqLokasiLooDetilId[$i], 4) . "." . getExtension($reqLinkFile['name'][$i]);

			if ($file->uploadToDirArray('reqLinkFile', $FILE_DIR, $renameFile, $i)) {

				$insertLinkSize = $file->uploadedSize;
				$insertLinkTipe =  $file->uploadedExtension;
				$insertLinkFile =  $renameFile;
			} else
				$insertLinkFile =  $reqLinkFileTemp[$i];

			if ($reqLokasiLooDetilId[$i] == "") {
			} else {
				$set->setField("SATUAN_KERJA_ID", $reqId);
				$set->setField("LOKASI_LOO_DETIL_ID", $reqLokasiLooDetilId[$i]);
				$set->setField("ATTACHMENT", $insertLinkFile);
				$set->setField("LAST_CREATE_USER", $this->USERNAME);
				$set->insertTemplate();
			}
		}

		echo "Data berhasil disimpan.";
	}

	function delete()
	{
		$reqId	= $this->input->get('reqId');
		$this->load->model("LokasiLooDetil");
		$set = new LokasiLooDetil();


		$set->setField("LOKASI_LOO_DETIL_ID", $reqId);
		if ($set->delete())
			$arrJson["PESAN"] = "Data berhasil dihapus.";
		else
			$arrJson["PESAN"] = "Data gagal dihapus.";

		echo json_encode($arrJson);
	}

	// function combo()
	// {
	// 	$this->load->model("LokasiLooDetil");
	// 	$set = new LokasiLooDetil();

	// 	$set->selectByParams(array("NOT LOKASI_LOO_DETIL_ID" => "0"));
	// 	$i = 0;
	// 	while ($set->nextRow()) {
	// 		$arr_json[$i]['id']		= $set->getField("LOKASI_LOO_DETIL_ID");
	// 		$arr_json[$i]['text']	= $set->getField("NAMA");
	// 		$arr_json[$i]['JENIS_TTD']	= $set->getField("JENIS_TTD");
	// 		$arr_json[$i]['PENERBIT_NOMOR']	= $set->getField("PENERBIT_NOMOR");
	// 		$i++;
	// 	}

	// 	echo json_encode($arr_json);
	// }
	function combo()
	{
		$reqId	= $this->input->get('reqId');
		$reqTipe	= $this->input->get('reqTipe');

		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : 50;
		$offset = ($page-1)*$rows;
		
		$reqPencarian = $this->input->get("reqPencarian");
		$reqMode = $this->input->get("reqMode");
		$reqSatuanKerjaId = $this->input->get("reqSatuanKerjaId");
		$reqParentId = $this->input->get("reqParentId");
		$c = $this->input->get("c");
		
		$this->load->model("LokasiLooDetil");
		$set = new LokasiLooDetil();

		if($reqPencarian == "")
		{}
		else
			$statement = " AND (UPPER(A.NAMA) LIKE '%".strtoupper($reqPencarian)."%' OR UPPER(B.NAMA) LIKE '%".strtoupper($reqPencarian)."%' OR UPPER(C.NAMA) LIKE '%".strtoupper($reqPencarian)."%' OR UPPER(A.LANTAI) LIKE '%".strtoupper($reqPencarian)."%' OR UPPER(A.KODE) LIKE '%".strtoupper($reqPencarian)."%' ) ";

		if ($reqId) 
		{
			$statement_privacy = " AND A.LOKASI_LOO_ID = '".$reqId."' AND A.TIPE = '".$reqTipe."' ";
		}

		$rowCount = $set->getCountByParams(array(), $statement.$statement_privacy);
		$set->selectByParams(array(), $rows, $offset, $statement.$statement_privacy);

		if(!empty($c))
		{
			echo $set->query;exit;
		}

		$i = 0;
		$items = array();
		while($set->nextRow())
		{
			$row['id']= $set->getField("LOKASI_LOO_DETIL_ID");
			$row['text']= $set->getField("NAMA");
			$row['LOKASI_LOO_ID']= $set->getField("LOKASI_LOO_ID");
			$row['NAMA_LOKASI_LOO']= $set->getField("NAMA_LOKASI_LOO");
			$row['NAMA']= $set->getField("NAMA");
			$row['KODE']= $set->getField("KODE");
			$row['DESKRIPSI']= $set->getField("DESKRIPSI");
			$row['LANTAI']= $set->getField("LANTAI");
			$row['TIPE_INFO']	= $set->getField("TIPE_INFO");
			$row['state'] 	= 'close';
			$i++;
			array_push($items, $row);
		}
		$result["rows"] = $items;
		$result["total"] = $rowCount;
		echo json_encode($result);
	}


	function combo_statement()
	{
		$this->load->model("LokasiLooDetil");
		$set = new LokasiLooDetil();

		$reqId = $this->input->get("reqId");
		$reqKelompokJabatan = $this->input->get("reqKelompokJabatan");


		$statement .= " AND TIPE_NASKAH LIKE '%" . $reqId . "%' ";

		$arr_json = array();
		$set->selectByParams(array("NOT LOKASI_LOO_DETIL_ID" => "0"), -1, -1, $statement);
		// echo $set->query;exit;
		$i = 0;
		while ($set->nextRow()) {
			$arr_json[$i]['id']		= $set->getField("LOKASI_LOO_DETIL_ID");
			$arr_json[$i]['text']	= $set->getField("NAMA");
			$arr_json[$i]['JENIS_TTD']	= $set->getField("JENIS_TTD");
			$arr_json[$i]['PENERBIT_NOMOR']	= $set->getField("PENERBIT_NOMOR");

			if ($this->CABANG_ID == "01") {
				$arr_json[$i]['KD_LEVEL']	= $set->getField("KD_LEVEL");
			} else {
				$arr_json[$i]['KD_LEVEL']	= $set->getField("KD_LEVEL_CABANG");
			}

			$i++;
		}

		echo json_encode($arr_json);
	}




	function combo_request()
	{
		$this->load->model("LokasiLooDetil");
		$set = new LokasiLooDetil();

		$reqId = $this->input->get("reqId");


		$statement = " AND TIPE_NASKAH LIKE '%" . $reqId . "%' ";
		$statement .= " AND NOT COALESCE(NULLIF(KODE_SURAT, ''), 'X') = 'X' ";

		$arr_json = array();
		$set->selectByParams(array("NOT LOKASI_LOO_DETIL_ID" => "0"), -1, -1, $statement);
		$i = 0;
		while ($set->nextRow()) {
			$arr_json[$i]['id']		= $set->getField("LOKASI_LOO_DETIL_ID");
			$arr_json[$i]['text']	= $set->getField("NAMA");
			$arr_json[$i]['PENERBIT_NOMOR']	= $set->getField("PENERBIT_NOMOR");
			if ($this->CABANG_ID == "01")
				$arr_json[$i]['KD_LEVEL']	= $set->getField("KD_LEVEL");
			else
				$arr_json[$i]['KD_LEVEL']	= $set->getField("KD_LEVEL_CABANG");
			$i++;
		}

		echo json_encode($arr_json);
	}


	function combo_level()
	{
		$this->load->model("LokasiLooDetil");
		$set = new LokasiLooDetil();

		$reqId = $this->input->get("reqId");


		$statement = " AND TIPE_NASKAH LIKE '%" . $reqId . "%' ";


		$set->selectByParams(array("NOT LOKASI_LOO_DETIL_ID" => "0"), -1, -1, $statement);
		$i = 0;
		$arr_json = array();
		while ($set->nextRow()) {
			$arr_json[$i]['id']		= $set->getField("LOKASI_LOO_DETIL_ID");
			$arr_json[$i]['text']	= $set->getField("NAMA");
			if ($this->CABANG_ID == "01")
				$arr_json[$i]['KD_LEVEL']	= $set->getField("KD_LEVEL");
			else
				$arr_json[$i]['KD_LEVEL']	= $set->getField("KD_LEVEL_CABANG");

			$i++;
		}

		echo json_encode($arr_json);
	}
}
