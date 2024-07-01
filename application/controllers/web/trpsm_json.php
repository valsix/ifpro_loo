<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class trpsm_json extends CI_Controller
{

	function __construct()
	{
		parent::__construct();

		if (!$this->kauth->getInstance()->hasIdentity()) {
			//redirect('login');
		}

		$this->db->query("SET DATESTYLE TO PostgreSQL,European;");
		$this->ID= $this->kauth->getInstance()->getIdentity()->ID;
		$this->NAMA= $this->kauth->getInstance()->getIdentity()->NAMA;
		$this->JABATAN= $this->kauth->getInstance()->getIdentity()->JABATAN;
		$this->HAK_AKSES= $this->kauth->getInstance()->getIdentity()->HAK_AKSES;
		$this->LAST_LOGIN= $this->kauth->getInstance()->getIdentity()->LAST_LOGIN;
		$this->USERNAME= $this->kauth->getInstance()->getIdentity()->USERNAME;
		$this->USER_LOGIN_ID= $this->kauth->getInstance()->getIdentity()->USER_LOGIN_ID;
		$this->USER_GROUP= $this->kauth->getInstance()->getIdentity()->USER_GROUP;
		$this->MULTIROLE= $this->kauth->getInstance()->getIdentity()->MULTIROLE;
		$this->CABANG_ID= $this->kauth->getInstance()->getIdentity()->CABANG_ID;
		$this->CABANG= $this->kauth->getInstance()->getIdentity()->CABANG;
		$this->SATUAN_KERJA_ID_ASAL= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_ID_ASAL;
		$this->SATUAN_KERJA_ASAL= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_ASAL;
		$this->SATUAN_KERJA_HIRARKI= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_HIRARKI;
		$this->SATUAN_KERJA_JABATAN= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_JABATAN;
		$this->KD_LEVEL= $this->kauth->getInstance()->getIdentity()->KD_LEVEL;
		$this->KD_LEVEL_PEJABAT= $this->kauth->getInstance()->getIdentity()->KD_LEVEL_PEJABAT;
		$this->JENIS_KELAMIN= $this->kauth->getInstance()->getIdentity()->JENIS_KELAMIN;
		$this->KELOMPOK_JABATAN= $this->kauth->getInstance()->getIdentity()->KELOMPOK_JABATAN;
		$this->KODE_PARENT= $this->kauth->getInstance()->getIdentity()->KODE_PARENT;
		$this->ID_ATASAN= $this->kauth->getInstance()->getIdentity()->ID_ATASAN;
		$this->DEPARTEMEN_PARENT_ID= $this->kauth->getInstance()->getIdentity()->DEPARTEMEN_PARENT_ID;

		$this->NIP_BY_DIVISI= $this->kauth->getInstance()->getIdentity()->NIP_BY_DIVISI;
		$this->KELOMPOK_JABATAN_BY_DIVISI= $this->kauth->getInstance()->getIdentity()->KELOMPOK_JABATAN_BY_DIVISI;

		$this->SATUAN_KERJA_ID_ASAL_ASLI= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_ID_ASAL_ASLI;
	}

	function json()
	{
		$this->load->model("TrPsm");
		$trloo = new TrPsm();

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
		$allRecord = $trloo->getCountByParams(array(), $statement_privacy . $statement);
		// echo $allRecord;exit;
		if ($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else
			$allRecordFilter =  $trloo->getCountByParams(array(), $statement_privacy . $statement);

		$trloo->selectByParams(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);
		// echo $trloo ->query; exit;
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

		while ($trloo->nextRow()) {
			$row = array();
			for ($i = 0; $i < count($aColumns); $i++) {
				if ($aColumns[$i] == "KETERANGAN")
					$row[] = truncate($trloo->getField($aColumns[$i]), 2);
				elseif ($aColumns[$i] == "ATTACHMENT")
					$row[] = "<a href='uploads/'" . $trloo->getField($aColumns[$i]) . " target='_blank'>" . $trloo->getField($aColumns[$i]) . "</a>";
				else
					$row[] = $trloo->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		echo json_encode($output);
	}

	function add()
	{
		$this->load->model("TrPsm");
		$this->load->model("TrPsmDetil");
		$this->load->model("TrPsmParaf");
		$this->load->model("SatuanKerja");
		$this->load->library("FileHandler");

		$reqMode= $this->input->post("reqMode");
		$reqId= $this->input->post("reqId");
		$cekquery= $this->input->post("cekquery");

		$reqStatusData= $this->input->post("reqStatusData");
		$reqSatuanKerjaPengirimId= $this->input->post("reqSatuanKerjaPengirimId");
		$reqProdukId= $this->input->post("reqProdukId");
		$reqCustomerId= $this->input->post("reqCustomerId");
		$reqLokasiLooId= $this->input->post("reqLokasiLooId");
		$reqPph= $this->input->post("reqPph");
		$reqTotalLuasIndoor= $this->input->post("reqTotalLuasIndoor");
		$reqTotalLuasOutdoor= $this->input->post("reqTotalLuasOutdoor");
		$reqTotalLuas= $this->input->post("reqTotalLuas");
		$reqTotalDiscInSewa= $this->input->post("reqTotalDiscInSewa");
		$reqTotalDiscOutSewa= $this->input->post("reqTotalDiscOutSewa");
		$reqTotalDiscInService= $this->input->post("reqTotalDiscInService");
		$reqTotalDiscOutService= $this->input->post("reqTotalDiscOutService");
		$reqHargaIndoorSewa= $this->input->post("reqHargaIndoorSewa");
		$reqHargaOutdoorSewa= $this->input->post("reqHargaOutdoorSewa");
		$reqHargaIndoorService= $this->input->post("reqHargaIndoorService");
		$reqHargaOutdoorService= $this->input->post("reqHargaOutdoorService");
		$reqDp= $this->input->post("reqDp");
		$reqPeriodeSewa= $this->input->post("reqPeriodeSewa");
		$reqTop= $this->input->post("reqTop");
		$reqSewaBiayaSatuanUnit= $this->input->post("reqSewaBiayaSatuanUnit");
		$reqSewaBiayaSatuanService= $this->input->post("reqSewaBiayaSatuanService");
		$reqSewaTotalBiayaUnit= $this->input->post("reqSewaTotalBiayaUnit");
		$reqSewaBiayaPerBulanUnit= $this->input->post("reqSewaBiayaPerBulanUnit");
		$reqSewaBiayaPerBulanService= $this->input->post("reqSewaBiayaPerBulanService");
		$reqSewaTotalBiayaService= $this->input->post("reqSewaTotalBiayaService");
		$reqTotalBiayaPerBulanNoPpn= $this->input->post("reqTotalBiayaPerBulanNoPpn");
		$reqTotalBiayaPerBulanPpn= $this->input->post("reqTotalBiayaPerBulanPpn");
		$reqTotalBiayaNoPpn= $this->input->post("reqTotalBiayaNoPpn");
		$reqTotalBiayaPpn= $this->input->post("reqTotalBiayaPpn");
		$reqSecurityDeposit= $this->input->post("reqSecurityDeposit");
		$reqFittingOut= $this->input->post("reqFittingOut");
		$reqTanggalAwal= $this->input->post("reqTanggalAwal");
		$reqTanggalAkhir= $this->input->post("reqTanggalAkhir");
		$reqPromotionLevy= $this->input->post("reqPromotionLevy");
		$reqPicPenandatangan= $this->input->post("reqPicPenandatangan");
		$reqJabatanPenandatangan= $this->input->post("reqJabatanPenandatangan");
		$reqSaksiNama= $this->input->post("reqSaksiNama");
		$reqSaksiJabatan= $this->input->post("reqSaksiJabatan");
		$reqSaksiPenyewaNama= $this->input->post("reqSaksiPenyewaNama");
		$reqSaksiPenyewaJabatan= $this->input->post("reqSaksiPenyewaJabatan");

		if($reqStatusData == "UBAHDATAPARAF" || $reqStatusData == "UBAHDATAREVISI")
		{
			$reqKondisiStatusData= $reqStatusData;
			$reqStatusData= "DRAFT";
		}

		$infostatuslog= "";
		if($reqStatusData == "UBAHDATADRAFTPARAF")
		{
			$reqKondisiStatusData= $reqStatusData;
			$reqStatusData= "PARAF";
		}

		if($reqStatusData == "UBAHDATAPOSTING")
		{
			$reqKondisiStatusData= $reqStatusData;
			$reqStatusData= "POSTING";
		}

		if($reqStatusData == "UBAHDATAVALIDASI")
		{
			$reqKondisiStatusData= $reqStatusData;
			$reqStatusData= "VALIDASI";
		}

		if($reqStatusData == "REVISI")
		{
			$infostatuslog= "Revisi";	
		}

		$sesid= $this->ID;
		$set= new TrPsm();
		$set->setField("TR_PSM_ID", $reqId);
		$set->setField("PRODUK_ID", ValToNullDB($reqProdukId));
		$set->setField("CUSTOMER_ID", ValToNullDB($reqCustomerId));
		$set->setField("LOKASI_LOO_ID", ValToNullDB($reqLokasiLooId));
		$set->setField("PPH", ValToNullDB(dotToNo($reqPph)));
		$set->setField("TOTAL_LUAS_INDOOR", ValToNullDB(dotToNo($reqTotalLuasIndoor)));
		$set->setField("TOTAL_LUAS_OUTDOOR", ValToNullDB(dotToNo($reqTotalLuasOutdoor)));
		$set->setField("TOTAL_LUAS", ValToNullDB(dotToNo($reqTotalLuas)));
		$set->setField("TOTAL_DISKON_INDOOR_SEWA", ValToNullDB(dotToNo($req)));
		$set->setField("TOTAL_DISKON_OUTDOOR_SEWA", ValToNullDB(dotToNo($req)));
		$set->setField("TOTAL_DISKON_INDOOR_SERVICE", ValToNullDB(dotToNo($req)));
		$set->setField("TOTAL_DISKON_OUTDOOR_SERVICE", ValToNullDB(dotToNo($req)));
		$set->setField("HARGA_INDOOR_SEWA", ValToNullDB(dotToNo($reqHargaIndoorSewa)));
		$set->setField("HARGA_OUTDOOR_SEWA", ValToNullDB(dotToNo($reqHargaOutdoorSewa)));
		$set->setField("HARGA_INDOOR_SERVICE", ValToNullDB(dotToNo($reqHargaIndoorService)));
		$set->setField("HARGA_OUTDOOR_SERVICE", ValToNullDB(dotToNo($reqHargaOutdoorService)));
		$set->setField("DP", ValToNullDB(dotToNo($reqDp)));
		$set->setField("PERIODE_SEWA", ValToNullDB(dotToNo($reqPeriodeSewa)));
		$set->setField("TOP", ValToNullDB(dotToNo($reqTop)));
		$set->setField("SEWA_BIAYA_SATUAN_UNIT", ValToNullDB(dotToNo($reqSewaBiayaSatuanUnit)));
		$set->setField("SEWA_BIAYA_SATUAN_SERVICE", ValToNullDB(dotToNo($reqSewaBiayaSatuanService)));
		$set->setField("SEWA_TOTAL_BIAYA_UNIT", ValToNullDB(dotToNo($reqSewaTotalBiayaUnit)));
		$set->setField("SEWA_BIAYA_PER_BULAN_UNIT", ValToNullDB(dotToNo($reqSewaBiayaPerBulanUnit)));
		$set->setField("SEWA_BIAYA_PER_BULAN_SERVICE", ValToNullDB(dotToNo($reqSewaBiayaPerBulanService)));
		$set->setField("SEWA_TOTAL_BIAYA_SERVICE", ValToNullDB(dotToNo($reqSewaTotalBiayaService)));
		$set->setField("TOTAL_BIAYA_PER_BULAN_NO_PPN", ValToNullDB(dotToNo($reqTotalBiayaPerBulanNoPpn)));
		$set->setField("TOTAL_BIAYA_PER_BULAN_PPN", ValToNullDB(dotToNo($reqTotalBiayaPerBulanPpn)));
		$set->setField("TOTAL_BIAYA_NO_PPN", ValToNullDB(dotToNo($reqTotalBiayaNoPpn)));
		$set->setField("TOTAL_BIAYA_PPN", ValToNullDB(dotToNo($reqTotalBiayaPpn)));
		$set->setField("SECURITY_DEPOSIT", ValToNullDB(dotToNo($reqSecurityDeposit)));
		$set->setField("FITTING_OUT", ValToNullDB(dotToNo($reqFittingOut)));

		$set->setField("TANGGAL_AWAL", dateToDbCheck($reqTanggalAwal));
		$set->setField("TANGGAL_AKHIR", dateToDbCheck($reqTanggalAkhir));
		$set->setField("PROMOTION_LEVY", ValToNullDB(dotToNo($reqPromotionLevy)));

		$set->setField("PIC_PENANDATANGAN", setQuote($reqPicPenandatangan));
		$set->setField("JABATAN_PENANDATANGAN", setQuote($reqJabatanPenandatangan));

		$set->setField("SAKSI_NAMA", setQuote($reqSaksiNama));
		$set->setField("SAKSI_JABATAN", setQuote($reqSaksiJabatan));
		$set->setField("SAKSI_PENYEWA_NAMA", setQuote($reqSaksiPenyewaNama));
		$set->setField("SAKSI_PENYEWA_JABATAN", setQuote($reqSaksiPenyewaJabatan));

		$set->setField("USER_PEMBUAT_ID", $sesid);
		$set->setField("STATUS_DATA", $reqStatusData);
		$set->setField("SATUAN_KERJA_PENGIRIM_ID", $reqSatuanKerjaPengirimId);

		$buttonlampiranupload= $this->input->post("buttonlampiranupload");
		if($reqStatusData == "LAMPIRAN" && !empty($buttonlampiranupload))
		{
			// upload baru
			$arrDataAttach= [];
			$this->load->model("TrLoi");
			$reqTrLoiId= $this->input->post("reqTrLoiId");
			$file = new FileHandler();
			$FILE_DIR = "uploadsloi/".$reqTrLoiId."/";
			makedirs($FILE_DIR);

			$reqLinkModeFile = $_FILES["reqLinkModeFile"];
			$reqLinkModeFileTempSize	=  $this->input->post("reqLinkModeFileTempSize");
			$reqLinkModeFileTempTipe	=  $this->input->post("reqLinkModeFileTempTipe");
			$reqLinkModeFileTemp		=  $this->input->post("reqLinkModeFileTemp");
			$reqLinkModeFileTempNama	=  $this->input->post("reqLinkModeFileTempNama");
			// print_r($reqLinkModeFile['name']); exit;
			// echo count($reqLinkModeFile['name']);exit();
			$reqJenisTujuan= "detil";
			$set_attachement = new TrLoi();
			$set_attachement->setField("TR_LOI_ID", $reqTrLoiId);
			$set_attachement->setField("VMODE", $reqJenisTujuan);
			$set_attachement->deleteModeAttachment();
			$reqJenis = $reqJenisTujuan.generateZero($reqTrLoiId, 10);
			$jumlahfile= 0;
			for ($i = 0; $i < count($reqLinkModeFile['name']); $i++) {
				$renameFile = $reqJenis.date("Ymdhis").rand().".".getExtension($reqLinkModeFile['name'][$i]);
				
				if ($file->uploadToDirArray('reqLinkModeFile', $FILE_DIR, $renameFile, $i)) {
					$insertLinkSize = $file->uploadedSize;
					$insertLinkTipe =  $file->uploadedExtension;
					$insertLinkFile =  $renameFile;

					if ($insertLinkFile == "") {
					} else {
						$set_attachement = new TrLoi();
						$set_attachement->setField("TR_LOI_ID", $reqTrLoiId);
						$set_attachement->setField("VMODE", $reqJenisTujuan);
						$set_attachement->setField("ATTACHMENT", setQuote($renameFile, ""));
						$set_attachement->setField("UKURAN", $insertLinkSize);
						$set_attachement->setField("TIPE", $insertLinkTipe);
						$set_attachement->setField("NAMA", setQuote($reqLinkModeFile['name'][$i], ""));
						$set_attachement->setField("LAST_CREATE_USER", $this->ID);
						$set_attachement->insertAttachment();
						// echo $set_attachement->query;exit;
						// print_r($reqLinkModeFile['name'][$i]);

						$arrDataAttach[$z]['temp_size'] = $insertLinkSize;
						$arrDataAttach[$z]['temp_tipe'] = $insertLinkTipe;
						$arrDataAttach[$z]['temp'] = $renameFile;
						$arrDataAttach[$z]['temp_nama'] = $reqLinkModeFile['name'][$i];
						$z++;
						$jumlahfile++;
					}
				}
			}

			/* SIMPAN DATA UPLOAD*/
			for ($i = 0; $i < count($reqLinkModeFileTemp); $i++) {
				$insertLinkSize = $reqLinkModeFileTempSize[$i];
				$insertLinkTipe =  $reqLinkModeFileTempTipe[$i];
				$insertLinkFile =  $reqLinkModeFileTemp[$i];
				$insertLinkNama =  $reqLinkModeFileTempNama[$i];
						// echo $i."if";

				if ($insertLinkFile == "") {
				} else {
					$set_attachement = new TrLoi();
					$set_attachement->setField("TR_LOI_ID", $reqTrLoiId);
					$set_attachement->setField("VMODE", $reqJenisTujuan);
					$set_attachement->setField("ATTACHMENT", setQuote($insertLinkFile, ""));
					$set_attachement->setField("UKURAN", $insertLinkSize);
					$set_attachement->setField("TIPE", $insertLinkTipe);
					$set_attachement->setField("NAMA", setQuote($insertLinkNama, ""));
					$set_attachement->setField("LAST_CREATE_USER", $this->ID);
					$set_attachement->insertAttachment();
					// print_r($reqLinkModeFile['name'][$i]);
					$jumlahfile++;
				}
			}

			// hapus file
			for ($i=0; $i < count($arrDataAttach); $i++) { 
				$insertLinkTipe =  $arrDataAttach[$i]['temp_tipe'];
				$insertLinkFile =  $arrDataAttach[$i]['temp'];
				$insertLinkNama =  $arrDataAttach[$i]['temp_nama'];

				$cek_data_attach = new TrLoi();
				$cek_data_attach->selectByParamsAttachment(array("ATTACHMENT"=>setQuote($insertLinkFile, ""), "TIPE"=>$insertLinkTipe, "NAMA"=>setQuote($insertLinkNama, "")), -1,-1, " AND A.VMODE = '".$reqJenisTujuan."'");
				$cek_data_attach->firstRow();

				if ($cek_data_attach->getField("ATTACHMENT")=="") {
					unlink($FILE_DIR.$insertLinkFile); // hapus file
				}
			}
			// batas file

			$inforeturninfo= "Lampiran bukti gagal di simpan";
			if($jumlahfile > 0)
				$inforeturninfo= "Lampiran bukti berhasil di simpan";
			
			echo $reqId."xxx".$inforeturninfo;exit;
		}

		$reqSimpan="";
		if ($reqMode == "insert") {

			if($set->insert())
			{
				$reqSimpan=1;
				$reqId= $set->id;
			}
		}
		else 
		{
			if($set->update())
			{
				$reqSimpan=1;
			}
		}

		if($cekquery == "1")
		{
			echo $set->query;exit;
		}

		if ($reqSimpan==1) 
		{
			$setdetil= new TrPsmDetil();
			$setdetil->setField("TR_PSM_ID", $reqId);
			$setdetil->delete();

			$vmode= $this->input->post("vmode");
			$vid= $this->input->post("vid");
			$vnilai= $this->input->post("vnilai");
			$vketerangan= $this->input->post("vketerangan");
			
			foreach ($vmode as $k => $v) {
				$setdetil= new TrPsmDetil();
				$setdetil->setField("TR_PSM_ID", $reqId);
				$setdetil->setField("VMODE", $v);
				$setdetil->setField("VID", $vid[$k]);
				$setdetil->setField("NILAI", ValToNullDB(dotToNo($vnilai[$k])));
				$setdetil->setField("KETERANGAN", $vketerangan[$k]);
				$setdetil->insert();
			}

			/* UNTUK CEK DATA TERUPLOAD */
			$arrDataAttach= array();
			$data_attachement = new TrPsm();
			$data_attachement->selectByParamsAttachment(array("TR_PSM_ID"=>$reqId), -1,-1, " AND COALESCE(NULLIF(A.VMODE, ''), NULL) IS NULL");
			$z=0;
			while ($data_attachement->nextRow()) 
			{
				$arrDataAttach[$z]['temp_size'] = $data_attachement->getField("UKURAN");
				$arrDataAttach[$z]['temp_tipe'] = $data_attachement->getField("TIPE");
				$arrDataAttach[$z]['temp'] = $data_attachement->getField("ATTACHMENT");
				$arrDataAttach[$z]['temp_nama'] = $data_attachement->getField("NAMA");
				$z++;
			}

			// batas file WAJIB UNTUK UPLOAD FILE
			$file = new FileHandler();
			$FILE_DIR = "uploadspsm/".$reqId."/";
			makedirs($FILE_DIR);

			$reqLinkFile = $_FILES["reqLinkFile"];
			$reqLinkFileTempSize	=  $this->input->post("reqLinkFileTempSize");
			$reqLinkFileTempTipe	=  $this->input->post("reqLinkFileTempTipe");
			$reqLinkFileTemp		=  $this->input->post("reqLinkFileTemp");
			$reqLinkFileTempNama	=  $this->input->post("reqLinkFileTempNama");
			// print_r($reqLinkFile['name']); exit;
			// echo count($reqLinkFile['name']);exit();
			$set_attachement = new TrPsm();
			$set_attachement->setField("TR_PSM_ID", $reqId);
			$set_attachement->deleteAttachment();
			$reqJenis = $reqJenisTujuan.generateZero($reqId, 10);
			for ($i = 0; $i < count($reqLinkFile['name']); $i++) {
				$renameFile = $reqJenis.date("Ymdhis").rand().".".getExtension($reqLinkFile['name'][$i]);
				
				if ($file->uploadToDirArray('reqLinkFile', $FILE_DIR, $renameFile, $i)) {
					$insertLinkSize = $file->uploadedSize;
					$insertLinkTipe =  $file->uploadedExtension;
					$insertLinkFile =  $renameFile;

					if ($insertLinkFile == "") {
					} else {
						$set_attachement = new TrPsm();
						$set_attachement->setField("TR_PSM_ID", $reqId);
						$set_attachement->setField("ATTACHMENT", setQuote($renameFile, ""));
						$set_attachement->setField("UKURAN", $insertLinkSize);
						$set_attachement->setField("TIPE", $insertLinkTipe);
						$set_attachement->setField("NAMA", setQuote($reqLinkFile['name'][$i], ""));
						$set_attachement->setField("LAST_CREATE_USER", $this->ID);
						$set_attachement->insertAttachment();
						// echo $set_attachement->query;exit;
						// print_r($reqLinkFile['name'][$i]);

						$arrDataAttach[$z]['temp_size'] = $insertLinkSize;
						$arrDataAttach[$z]['temp_tipe'] = $insertLinkTipe;
						$arrDataAttach[$z]['temp'] = $renameFile;
						$arrDataAttach[$z]['temp_nama'] = $reqLinkFile['name'][$i];
						$z++;
					}
				}
			}

			/* SIMPAN DATA UPLOAD*/
			for ($i = 0; $i < count($reqLinkFileTemp); $i++) {
				$insertLinkSize = $reqLinkFileTempSize[$i];
				$insertLinkTipe =  $reqLinkFileTempTipe[$i];
				$insertLinkFile =  $reqLinkFileTemp[$i];
				$insertLinkNama =  $reqLinkFileTempNama[$i];
						// echo $i."if";

				if ($insertLinkFile == "") {
				} else {
					$set_attachement = new TrPsm();
					$set_attachement->setField("TR_PSM_ID", $reqId);
					$set_attachement->setField("ATTACHMENT", setQuote($insertLinkFile, ""));
					$set_attachement->setField("UKURAN", $insertLinkSize);
					$set_attachement->setField("TIPE", $insertLinkTipe);
					$set_attachement->setField("NAMA", setQuote($insertLinkNama, ""));
					$set_attachement->setField("LAST_CREATE_USER", $this->ID);
					$set_attachement->insertAttachment();
					// print_r($reqLinkFile['name'][$i]);
				}
			}

			// hapus file
			for ($i=0; $i < count($arrDataAttach); $i++) { 
				$insertLinkTipe =  $arrDataAttach[$i]['temp_tipe'];
				$insertLinkFile =  $arrDataAttach[$i]['temp'];
				$insertLinkNama =  $arrDataAttach[$i]['temp_nama'];

				$cek_data_attach = new TrPsm();
				$cek_data_attach->selectByParamsAttachment(array("ATTACHMENT"=>setQuote($insertLinkFile, ""), "TIPE"=>$insertLinkTipe, "NAMA"=>setQuote($insertLinkNama, "")), -1,-1, " AND COALESCE(NULLIF(A.VMODE, ''), NULL) IS NULL");
				$cek_data_attach->firstRow();

				if ($cek_data_attach->getField("ATTACHMENT")=="") {
					unlink($FILE_DIR.$insertLinkFile); // hapus file
				}
			}
			// batas file

			// untuk data pemaraf
			$reqSatuanKerjaIdParaf= $this->input->post("reqSatuanKerjaIdParaf");
			if ( ($reqStatusData == "DRAFT" && empty($reqKondisiStatusData)) || ($reqStatusData == "PARAF" && $reqKondisiStatusData == "UBAHDATADRAFTPARAF") || ($reqStatusData == "DRAFT" && $reqKondisiStatusData == "UBAHDATAREVISI") )
			{
				$setdetil= new TrPsmParaf();
				$setdetil->setField("TR_PSM_ID", $reqId);
				$setdetil->setField("LAST_CREATE_USER", $sesid);
				$setdetil->deleteParent();
				
				// tambahan khusus
				if(!empty($reqSatuanKerjaIdParaf))
				{
					// $reqSatuanKerjaIdParaf= explode(",", $reqSatuanKerjaIdParaf);
					for ($i = 0; $i < count($reqSatuanKerjaIdParaf); $i++) {
						if ($reqSatuanKerjaIdParaf[$i] == "") {
						} else {
							$setdetil = new TrPsmParaf();

							$adaData= $setdetil->getCountByParams(array("TR_PSM_ID" => $reqId, "SATUAN_KERJA_ID_TUJUAN" => $reqSatuanKerjaIdParaf[$i]));

							// kalau satuan kerja sebagai pengirim, maka jangan di simpan
							if( $reqSatuanKerjaId !== $reqSatuanKerjaIdParaf[$i])
							{
								if ($adaData == 0) 
								{
									// tambahan khusus
									$userbantu= new SatuanKerja();
									$userbantu->selectByParams(array(),-1,-1, " AND A.SATUAN_KERJA_ID = '".$reqSatuanKerjaIdParaf[$i]."'");
									$userbantu->firstRow();
									$userbantuuserid= $userbantu->getField("USER_BANTU");
									unset($userbantu);

									if(!empty($userbantuuserid))
									{
										$setdetil = new TrPsmParaf();
										$setdetil->setField("TR_PSM_ID", $reqId);
										$setdetil->setField("SATUAN_KERJA_ID_TUJUAN", $reqSatuanKerjaIdParaf[$i]);
										$setdetil->setField("LAST_CREATE_USER", $sesid);
										$setdetil->insertbantu();
									}
						
									$setdetil->setField("TR_PSM_ID", $reqId);
									$setdetil->setField("SATUAN_KERJA_ID_TUJUAN", $reqSatuanKerjaIdParaf[$i]);
									$setdetil->setField("LAST_CREATE_USER", $sesid);
									$setdetil->insert();

									if($cekquery == "2")
									{
										echo $setdetil->query;exit;
									}
								}
							}
						}
					}
				}

				// tambahan khusus
				$userbantu= new SatuanKerja();
				$userbantu->selectByParams(array(),-1,-1, " AND A.SATUAN_KERJA_ID = '".$reqSatuanKerjaPengirimId."'");
				$userbantu->firstRow();
				$userbantuuserid= $userbantu->getField("USER_BANTU");
				unset($userbantu);

				if(!empty($userbantuuserid))
				{
					$setdetil = new TrPsmParaf();
					$setdetil->setField("TR_PSM_ID", $reqId);
					$setdetil->setField("SATUAN_KERJA_ID_TUJUAN", $reqSatuanKerjaPengirimId);
					$setdetil->setField("LAST_CREATE_USER", $sesid);
					$setdetil->insertbantu();
					if($cekquery == "105")
					{
						// echo $setdetil->query;exit;
					}
				}

				if(!empty($reqSatuanKerjaIdParaf))
				{
					$userbantu= new TrPsmParaf();
					$userbantu->setField("TR_PSM_ID", $reqId);
					$userbantu->deleteuserbantu();
					unset($userbantu);

					// reset ulang nomor
					$userbantu= new TrPsmParaf();
					$userbantu->selectByParams(array("TR_PSM_ID" => $reqId), -1, -1, " AND COALESCE(NULLIF(KONDISI_PARAF, ''), NULL) IS NULL", "ORDER BY NO_URUT ASC");
					$nomorurut=1;
					while($userbantu->nextRow())
					{
						$checkparaf= new TrPsmParaf();
						$checkparaf->setField("SURAT_MASUK_PARAF_ID", $userbantu->getField("SURAT_MASUK_PARAF_ID"));
						$checkparaf->setField("NO_URUT", $nomorurut);
						$checkparaf->resetnourut();
						$nomorurut++;
					}

					// kalau jenis keputusan direksi
					if($reqJenisNaskah == 8)
					{
						$checkparaf= new TrPsmParaf();
						$checkparaf->setField("TR_PSM_ID", $reqId);
						$checkparaf->resetparalelnourut();
					}
				}

				// kondisi tukar data paralel apabila urutan user bantu lebih besar
				$checkparaf= new TrPsmParaf();
				$checkparaf->selectByParams(array(), -1, -1, " AND A.STATUS_BANTU IS NULL AND KONDISI_PARAF = 'PARALEL' AND A.TR_PSM_ID = ".$reqId);
				$checkparaf->firstRow();
				$checkparafuserdireksi= $checkparaf->getField("NO_URUT");

				$checkparaf= new TrPsmParaf();
				$checkparaf->selectByParams(array(), -1, -1, " AND A.STATUS_BANTU = 1 AND KONDISI_PARAF = 'PARALEL' AND A.TR_PSM_ID = ".$reqId);
				$checkparaf->firstRow();
				$checkparafuserbantu= $checkparaf->getField("NO_URUT");

				// echo $checkparafuserdireksi."xxx".$checkparafuserbantu;exit;

				if(!empty($checkparafuserdireksi) && !empty($checkparafuserbantu) && $checkparafuserbantu > $checkparafuserdireksi)
				{
					$checkparaf= new TrPsmParaf();
					$checkparaf->setField("TR_PSM_ID", $reqId);
					$checkparaf->setField("NO_URUT_DIREKSI", $checkparafuserbantu);
					$checkparaf->setField("NO_URUT_BANTU", $checkparafuserdireksi);
					$checkparaf->tukarurutanparalel();
				}
			}

			// upload baru
			$arrDataAttach= [];
			$this->load->model("TrLoi");
			$reqTrLoiId= $this->input->post("reqTrLoiId");
			$file = new FileHandler();
			$FILE_DIR = "uploadsloi/".$reqTrLoiId."/";
			makedirs($FILE_DIR);

			$reqLinkModeFile = $_FILES["reqLinkModeFile"];
			$reqLinkModeFileTempSize	=  $this->input->post("reqLinkModeFileTempSize");
			$reqLinkModeFileTempTipe	=  $this->input->post("reqLinkModeFileTempTipe");
			$reqLinkModeFileTemp		=  $this->input->post("reqLinkModeFileTemp");
			$reqLinkModeFileTempNama	=  $this->input->post("reqLinkModeFileTempNama");
			// print_r($reqLinkModeFile['name']); exit;
			// echo count($reqLinkModeFile['name']);exit();
			$reqJenisTujuan= "detil";
			$set_attachement = new TrLoi();
			$set_attachement->setField("TR_LOI_ID", $reqTrLoiId);
			$set_attachement->setField("VMODE", $reqJenisTujuan);
			$set_attachement->deleteModeAttachment();
			$reqJenis = $reqJenisTujuan.generateZero($reqTrLoiId, 10);
			for ($i = 0; $i < count($reqLinkModeFile['name']); $i++) {
				$renameFile = $reqJenis.date("Ymdhis").rand().".".getExtension($reqLinkModeFile['name'][$i]);
				
				if ($file->uploadToDirArray('reqLinkModeFile', $FILE_DIR, $renameFile, $i)) {
					$insertLinkSize = $file->uploadedSize;
					$insertLinkTipe =  $file->uploadedExtension;
					$insertLinkFile =  $renameFile;

					if ($insertLinkFile == "") {
					} else {
						$set_attachement = new TrLoi();
						$set_attachement->setField("TR_LOI_ID", $reqTrLoiId);
						$set_attachement->setField("VMODE", $reqJenisTujuan);
						$set_attachement->setField("ATTACHMENT", setQuote($renameFile, ""));
						$set_attachement->setField("UKURAN", $insertLinkSize);
						$set_attachement->setField("TIPE", $insertLinkTipe);
						$set_attachement->setField("NAMA", setQuote($reqLinkModeFile['name'][$i], ""));
						$set_attachement->setField("LAST_CREATE_USER", $this->ID);
						$set_attachement->insertAttachment();
						// echo $set_attachement->query;exit;
						// print_r($reqLinkModeFile['name'][$i]);

						$arrDataAttach[$z]['temp_size'] = $insertLinkSize;
						$arrDataAttach[$z]['temp_tipe'] = $insertLinkTipe;
						$arrDataAttach[$z]['temp'] = $renameFile;
						$arrDataAttach[$z]['temp_nama'] = $reqLinkModeFile['name'][$i];
						$z++;
					}
				}
			}

			/* SIMPAN DATA UPLOAD*/
			for ($i = 0; $i < count($reqLinkModeFileTemp); $i++) {
				$insertLinkSize = $reqLinkModeFileTempSize[$i];
				$insertLinkTipe =  $reqLinkModeFileTempTipe[$i];
				$insertLinkFile =  $reqLinkModeFileTemp[$i];
				$insertLinkNama =  $reqLinkModeFileTempNama[$i];
						// echo $i."if";

				if ($insertLinkFile == "") {
				} else {
					$set_attachement = new TrLoi();
					$set_attachement->setField("TR_LOI_ID", $reqTrLoiId);
					$set_attachement->setField("VMODE", $reqJenisTujuan);
					$set_attachement->setField("ATTACHMENT", setQuote($insertLinkFile, ""));
					$set_attachement->setField("UKURAN", $insertLinkSize);
					$set_attachement->setField("TIPE", $insertLinkTipe);
					$set_attachement->setField("NAMA", setQuote($insertLinkNama, ""));
					$set_attachement->setField("LAST_CREATE_USER", $this->ID);
					$set_attachement->insertAttachment();
					// print_r($reqLinkModeFile['name'][$i]);
				}
			}

			// hapus file
			for ($i=0; $i < count($arrDataAttach); $i++) { 
				$insertLinkTipe =  $arrDataAttach[$i]['temp_tipe'];
				$insertLinkFile =  $arrDataAttach[$i]['temp'];
				$insertLinkNama =  $arrDataAttach[$i]['temp_nama'];

				$cek_data_attach = new TrLoi();
				$cek_data_attach->selectByParamsAttachment(array("ATTACHMENT"=>setQuote($insertLinkFile, ""), "TIPE"=>$insertLinkTipe, "NAMA"=>setQuote($insertLinkNama, "")), -1,-1, " AND A.VMODE = '".$reqJenisTujuan."'");
				$cek_data_attach->firstRow();

				if ($cek_data_attach->getField("ATTACHMENT")=="") {
					unlink($FILE_DIR.$insertLinkFile); // hapus file
				}
			}
			// batas file

			$inforeturninfo= "";
			if ($reqStatusData == "DRAFT" || $reqKondisiStatusData == "UBAHDATAVALIDASI") {

				if($reqStatusData == "DRAFT")
				{
					$arrtriger= array("reqId"=>$reqId, "mode"=>"tnomor");
					$this->trigerpaksa($arrtriger);
				}

				if($reqKondisiStatusData == "UBAHDATAPARAF" || $reqKondisiStatusData == "UBAHDATAREVISI" || $reqKondisiStatusData == "UBAHDATAVALIDASI")
				{
					$inforeturninfo= "Naskah berhasil disimpan.";
				}
				else
				{
					$inforeturninfo= "Naskah berhasil disimpan sebagai DRAFT.";
				}
			}
			elseif ($reqStatusData == "REVISI") {
				$arrparam= array("reqId"=>$reqId, "reqInfoLog"=>$reqInfoLog, "reqInfoStatus"=>$infostatuslog);
				$this->setlog($arrparam);

				$arrtriger= array("reqId"=>$reqId, "mode"=>"revisi");
				$this->trigerpaksa($arrtriger);

				$inforeturninfo= "Naskah telah dikembalikan ke pembuat surat.";
			}

			if(!empty($inforeturninfo))
			{
				echo $reqId."xxx".$inforeturninfo;
			}
			else
			{
				if($reqKondisiStatusData == "UBAHDATADRAFTPARAF")
				{
					$arrtriger= array("reqId"=>$reqId, "mode"=>"updateuserlihatstatus");
					$this->trigerpaksa($arrtriger);
				}

				$reqInfoLog= $this->input->post("reqInfoLog");
				
				if($reqStatusData == "POSTING")
				{
					$arrparam= array("reqId"=>$reqId, "reqInfoLog"=>$reqInfoLog, "vSource"=>$reqStatusData);
				}
				else
				{
					$arrparam= array("reqId"=>$reqId, "reqInfoLog"=>$reqInfoLog);
				}

				$this->paraf_proses($arrparam);
				
				// $arrparam= array("reqId"=>$reqId);
				// $this->posting_proses($arrparam);
			}
		}
		else
		{
			echo "xxxData gagal disimpan.";
		}
		
	}

	function trigerpaksa($arrparam)
	{
		$this->load->model("TrPsm");
		$reqId= $arrparam["reqId"];
		$mode= $arrparam["mode"];
		$cekquery= $arrparam["cekquery"];

		$tgr= new TrPsm();
		$tgr->setField("TR_PSM_ID", $reqId);
		$tgr->setField("PAKSA_DB", $mode);
		$tgr->updatetriger();
		if(!empty($cekquery))
		{
			echo $tgr->query;exit;
		}
	}

	function tesparaf()
	{
		$reqId= $this->input->get("reqId");
		$reqInfoLog= "Coba";

		$arrparam= array("reqId"=>$reqId, "reqInfoLog"=>$reqInfoLog);
		$this->paraf_proses($arrparam);
	}

	function setlog($arrparam)
	{
		$this->load->model("TrPsm");

		$reqId= $arrparam["reqId"];
		$reqInfoLog= $arrparam["reqInfoLog"];
		$reqInfoStatus= $arrparam["reqInfoStatus"];
		
		$sesid= $this->ID;
		$sesnama= $this->NAMA;
		$sesjabatan= $this->JABATAN;
		$sesusername= $this->USERNAME;
		$satuankerjaganti= $this->SATUAN_KERJA_ID_ASAL;

		// simpan log data, kalau ada data varible reqInfoLog
		if(!empty($reqInfoLog))
		{
			$slog= new TrPsm();
			$slog->setField("TR_PSM_ID", $reqId);
			$slog->setField("STATUS_SURAT", $reqInfoStatus);
			$slog->setField("INFORMASI", $sesjabatan." (".$sesnama.")");
			$slog->setField("CATATAN", $reqInfoLog);
			$slog->setField("LAST_CREATE_USER", $sesid);
			$slog->insertlog();
			unset($slog);
		}
	}

	function revisi()
	{
		$reqId= $this->input->post('reqId');
		$reqRevisi= $this->input->post('reqRevisi');
		$reqMode= $this->input->post('reqMode');
		$reqSatuanKerjaIdAsal= $this->input->post('reqSatuanKerjaIdAsal');
		$reqInfoLog= $this->input->post('reqInfoLog');

		$this->load->model("TrPsm");

		$sesid= $this->ID;
		$sesnama= $this->NAMA;
		$sesjabatan= $this->JABATAN;
		$sesusername= $this->USERNAME;
		$satuankerjaganti= $this->SATUAN_KERJA_ID_ASAL;

		$set= new TrPsm();
		if ($this->USER_GROUP == "TATAUSAHA") 
		{
			$reqSatuanKerjaIdAsal = $reqSatuanKerjaIdAsal;
		} 
		else 
		{
			if($reqMode == "manual"){}
			else
			$reqSatuanKerjaIdAsal = $this->SATUAN_KERJA_ID_ASAL;
		}

		$set->setField("TR_PSM_ID", $reqId);
		$set->setField("REVISI", $reqRevisi);
		$set->setField("SATUAN_KERJA_ID_ASAL", $reqSatuanKerjaIdAsal);
		$set->setField("REVISI_BY", $sesusername);
		if ($set->revisi()) 
		{
			// simpan log data, kalau ada data varible reqInfoLog
			if(!empty($reqInfoLog))
			{
				$arrparam= array("reqId"=>$reqId, "reqInfoLog"=>$reqInfoLog, "reqInfoStatus"=>"Revisi");
				$this->setlog($arrparam);
			}

			$arrtriger= array("reqId"=>$reqId, "mode"=>"revisi");
			$this->trigerpaksa($arrtriger);

			echo "Naskah berhasil dikembalikan";
			return;
		}
	}

	function logparaf()
	{
		$reqId= $this->input->post('reqId');
		$reqInfoLog= $this->input->post('reqInfoLog');

		$arrparam= array("reqId"=>$reqId, "reqInfoLog"=>$reqInfoLog);
		$this->paraf_proses($arrparam);
	}

	function paraf_proses($arrparam)
	{
		$reqId= $arrparam["reqId"];
		$reqInfoLog= $arrparam["reqInfoLog"];
		$reqInfoStatus= $arrparam["reqInfoStatus"];
		$vSource= $arrparam["vSource"];

		$this->load->model("TrPsm");
		$set= new TrPsm();

		$sesid= $this->ID;
		$sesnama= $this->NAMA;
		$sesjabatan= $this->JABATAN;
		$sesusername= $this->USERNAME;
		$satuankerjaganti= $this->SATUAN_KERJA_ID_ASAL;
		$kodeParaf = "PARAF".$sesid.generateZero($reqId, 6).date("dmYHis");

		$set->setField("TR_PSM_ID", $reqId);
		$set->setField("SATUAN_KERJA_ID_TUJUAN", $satuankerjaganti);
		$set->setField("KODE_PARAF", $kodeParaf);
		$set->setField("USER_ID", $sesid);
		$set->setField("LAST_UPDATE_USER", $sesusername);

		if ($set->paraf()) {

			// simpan log data, kalau ada data varible reqInfoLog
			if(!empty($reqInfoLog))
			{
				$set= new TrPsm();
				$statusSurat= $set->getStatusSurat(array("A.TR_PSM_ID" => $reqId));
				if($statusSurat == "VALIDASI")
				{

				}
				elseif($statusSurat == "PARAF")
				{
					$reqInfoStatus= "Kirim Paraf";
					$arrparam= array("reqId"=>$reqId, "reqInfoLog"=>$reqInfoLog, "reqInfoStatus"=>$reqInfoStatus);
					$this->setlog($arrparam);
				}

			}

			include_once("libraries/phpqrcode/qrlib.php");

			/*$FILE_DIR = "uploads/".$reqId."/";
			makedirs($FILE_DIR);
			$filename = $FILE_DIR.$kodeParaf.'.png';
			$errorCorrectionLevel = 'L';
			$matrixPointSize = 2;
			QRcode::png($kodeParaf, $filename, $errorCorrectionLevel, $matrixPointSize, 2);*/
			// END OF GENERATE QRCODE 

			// SETIAP POSTING HIT POSTING SUPAYA APABILA PARAF SUDAH KOMPLIT LANGSUNG TERPOSTING 
			$arrparam= array("reqId"=>$reqId, "reqInfoLog"=>$reqInfoLog, "vSource"=>$vSource);
			$this->posting_proses($arrparam);
		}

	}

	function logposting()
	{
		$reqId= $this->input->post('reqId');
		$reqInfoLog= $this->input->post('reqInfoLog');
		$reqInfoNomor= $this->input->post('reqInfoNomor');
		
		if(!empty($reqInfoNomor) && !empty($reqInfoLog))
		{
			/*$this->load->model("SuratMasuk");

			$checksurat= new SuratMasuk();
			$checksurat->selectByParamsCheckNomor("CHECK", $reqId, $reqInfoNomor, dateToDbCheck($reqInfoLog));
			$checksurat->firstRow();
			$valicheck= $checksurat->getField("INFO_NOMOR_SURAT");
			// echo $valicheck;exit;
			unset($checksurat);

			if($valicheck == "1")
			{
				$checksurat= new SuratMasuk();
				$checksurat->selectByParamsCheckNomor("SAVE", $reqId, $reqInfoNomor, dateToDbCheck($reqInfoLog));
				$checksurat->firstRow();
				$valicheck= $checksurat->getField("INFO_NOMOR_SURAT");
				// echo $valicheck;exit;
				unset($checksurat);
			}
			else
			{
				echo "0";
				exit;
			}*/
		}
		
		// untuk ambil data nomor berdasarkan tanggal entri
		$arrparam= array("reqId"=>$reqId, "reqInfoLog"=>$reqInfoLog, "vSource"=>"POSTING");
		$this->posting_proses($arrparam);
	}

	function posting_proses($arrparam)
	{
		$this->load->model("TrPsm");
		$sesid= $this->ID;
		$sesusername= $this->USERNAME;

		$reqId= $arrparam["reqId"];
		$vSource= $arrparam["vSource"];
		$reqInfoLog= $arrparam["reqInfoLog"];

		$set= new TrPsm();
		$statusSurat= $set->getStatusSurat(array("A.TR_PSM_ID" => $reqId));
		// echo $statusSurat;exit;

		$setdetil= new TrPsm();
		$setdetil->setField("TR_PSM_ID", $reqId);
		$setdetil->setField("NOMOR", $valicheck);
		// $setdetil->setField("SATUAN_KERJA_ID_ASAL", $this->SATUAN_KERJA_ID_ASAL);
		$setdetil->setField("PEMARAF_ID", $sesid);
		$setdetil->setField("FIELD", "STATUS_DATA");
		$setdetil->setField("FIELD_VALUE", "POSTING"); // apabila yang bikin staff nya sama trigger sudah otomatis diganti VALIDASI
		$setdetil->setField("LAST_UPDATE_USER", $sesusername);
		$setdetil->setField("USER_ID", $sesid);

		if(empty($valicheck))
		{
			if ($setdetil->updateByFieldValidasi()) 
			{
				$simpaninfo= "1";
			}
		}
		else
		{
			if ($setdetil->updateByFieldValidasiNomor()) 
			{
				$simpaninfo= "1";
			}
		}
		// echo $setdetil->query;exit;

		if($simpaninfo == "1")
		{
			$arrtriger= array("reqId"=>$reqId, "mode"=>"updateparaf");
			$this->trigerpaksa($arrtriger);

			if($statusSurat == "VALIDASI" || $statusSurat == "POSTING")
			{
				if($vSource !== "POSTING")
				{
					$arrtriger= array("reqId"=>$reqId, "mode"=>"tnomor", "cekquery"=>"");
					$this->trigerpaksa($arrtriger);

					$reqInfoStatus= "Kirim Paraf";
					$inforeturninfo= "Naskah berhasil diposting ke atasan untuk validasi";
				}
				else
				{
					$reqInfoStatus= "Posting";
					$inforeturninfo= "Naskah berhasil diposting";
				}

				$arrparam= array("reqId"=>$reqId, "reqInfoLog"=>$reqInfoLog, "reqInfoStatus"=>$reqInfoStatus);
				$this->setlog($arrparam);
			}
			elseif($statusSurat == "PARAF")
			{
				if ($reqSource == "PARAF")
				{
					$inforeturninfo= "Naskah berhasil diparaf";
				}
				else
				{
					$inforeturninfo= "Naskah berhasil diposting ke pemaraf sebelum diposting ke tujuan";
				}
			}

			echo $reqId."xxx".$inforeturninfo;
		}
	}

	function delete()
	{
		$reqId	= $this->input->get('reqId');
		$this->load->model("TrPsm");
		$trloo = new TrPsm();


		$trloo->setField("PRODUK_ID", $reqId);
		if ($trloo->delete())
			$arrJson["PESAN"] = "Data berhasil dihapus.";
		else
			$arrJson["PESAN"] = "Data gagal dihapus.";

		echo json_encode($arrJson);
	}

}