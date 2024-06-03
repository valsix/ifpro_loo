<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class trloo_json extends CI_Controller
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
		$this->load->model("TrLoo");
		$trloo = new TrLoo();

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
		$this->load->model("TrLoo");
		$this->load->model("TrLooDetil");
		$this->load->model("TrLooParaf");
		$this->load->model("SatuanKerja");

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

		if($reqStatusData == "UBAHDATAPARAF" || $reqStatusData == "UBAHDATAREVISI")
		{
			$reqKondisiStatusData= $reqStatusData;
			$reqStatusData= "DRAFT";
		}

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

		$sesid= $this->ID;
		$set= new TrLoo();
		$set->setField("TR_LOO_ID", $reqId);
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

		$set->setField("USER_PEMBUAT_ID", $sesid);
		$set->setField("STATUS_DATA", $reqStatusData);
		$set->setField("SATUAN_KERJA_PENGIRIM_ID", $reqSatuanKerjaPengirimId);

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
			$setdetil= new TrLooDetil();
			$setdetil->setField("TR_LOO_ID", $reqId);
			$setdetil->delete();

			$vmode= $this->input->post("vmode");
			$vid= $this->input->post("vid");
			$vnilai= $this->input->post("vnilai");
			$vketerangan= $this->input->post("vketerangan");
			
			foreach ($vmode as $k => $v) {
				$setdetil= new TrLooDetil();
				$setdetil->setField("TR_LOO_ID", $reqId);
				$setdetil->setField("VMODE", $v);
				$setdetil->setField("VID", $vid[$k]);
				$setdetil->setField("NILAI", ValToNullDB(dotToNo($vnilai[$k])));
				$setdetil->setField("KETERANGAN", $vketerangan[$k]);
				$setdetil->insert();
			}

			/* UNTUK CEK DATA TERUPLOAD */
			$arrDataAttach= array();
			$data_attachement = new TrLoo();
			$data_attachement->selectByParamsAttachment(array("TR_LOO_ID"=>$reqId));
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
			$this->load->library("FileHandler");
			$file = new FileHandler();
			$FILE_DIR = "uploadsloo/".$reqId."/";
			makedirs($FILE_DIR);

			$reqLinkFile = $_FILES["reqLinkFile"];
			$reqLinkFileTempSize	=  $this->input->post("reqLinkFileTempSize");
			$reqLinkFileTempTipe	=  $this->input->post("reqLinkFileTempTipe");
			$reqLinkFileTemp		=  $this->input->post("reqLinkFileTemp");
			$reqLinkFileTempNama	=  $this->input->post("reqLinkFileTempNama");
			// print_r($reqLinkFile['name']); exit;
			// echo count($reqLinkFile['name']);exit();
			$set_attachement = new TrLoo();
			$set_attachement->setField("TR_LOO_ID", $reqId);
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
						$set_attachement = new TrLoo();
						$set_attachement->setField("TR_LOO_ID", $reqId);
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
					$set_attachement = new TrLoo();
					$set_attachement->setField("TR_LOO_ID", $reqId);
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

				$cek_data_attach = new TrLoo();
				$cek_data_attach->selectByParamsAttachment(array("ATTACHMENT"=>setQuote($insertLinkFile, ""), "TIPE"=>$insertLinkTipe, "NAMA"=>setQuote($insertLinkNama, "")));
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
				$setdetil= new TrLooParaf();
				$setdetil->setField("TR_LOO_ID", $reqId);
				$setdetil->setField("LAST_CREATE_USER", $sesid);
				$setdetil->deleteParent();
				
				// tambahan khusus
				if(!empty($reqSatuanKerjaIdParaf))
				{
					// $reqSatuanKerjaIdParaf= explode(",", $reqSatuanKerjaIdParaf);
					for ($i = 0; $i < count($reqSatuanKerjaIdParaf); $i++) {
						if ($reqSatuanKerjaIdParaf[$i] == "") {
						} else {
							$setdetil = new TrLooParaf();

							$adaData= $setdetil->getCountByParams(array("TR_LOO_ID" => $reqId, "SATUAN_KERJA_ID_TUJUAN" => $reqSatuanKerjaIdParaf[$i]));

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
										$setdetil = new TrLooParaf();
										$setdetil->setField("TR_LOO_ID", $reqId);
										$setdetil->setField("SATUAN_KERJA_ID_TUJUAN", $reqSatuanKerjaIdParaf[$i]);
										$setdetil->setField("LAST_CREATE_USER", $sesid);
										$setdetil->insertbantu();
									}
						
									$setdetil->setField("TR_LOO_ID", $reqId);
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
				$userbantu->selectByParams(array(),-1,-1, " AND A.SATUAN_KERJA_ID = '".$reqSatuanKerjaId."'");
				$userbantu->firstRow();
				$userbantuuserid= $userbantu->getField("USER_BANTU");
				unset($userbantu);

				if(!empty($userbantuuserid))
				{
					$setdetil = new TrLooParaf();
					$setdetil->setField("TR_LOO_ID", $reqId);
					$setdetil->setField("SATUAN_KERJA_ID_TUJUAN", $reqSatuanKerjaId);
					$setdetil->setField("LAST_CREATE_USER", $sesid);
					$setdetil->insertbantu();
					if($cekquery == "105")
					{
						// echo $setdetil->query;exit;
					}
				}

				if(!empty($reqSatuanKerjaIdParaf))
				{
					$userbantu= new TrLooParaf();
					$userbantu->setField("TR_LOO_ID", $reqId);
					$userbantu->deleteuserbantu();
					unset($userbantu);

					// reset ulang nomor
					$userbantu= new TrLooParaf();
					$userbantu->selectByParams(array("TR_LOO_ID" => $reqId), -1, -1, " AND COALESCE(NULLIF(KONDISI_PARAF, ''), NULL) IS NULL", "ORDER BY NO_URUT ASC");
					$nomorurut=1;
					while($userbantu->nextRow())
					{
						$checkparaf= new TrLooParaf();
						$checkparaf->setField("SURAT_MASUK_PARAF_ID", $userbantu->getField("SURAT_MASUK_PARAF_ID"));
						$checkparaf->setField("NO_URUT", $nomorurut);
						$checkparaf->resetnourut();
						$nomorurut++;
					}

					// kalau jenis keputusan direksi
					if($reqJenisNaskah == 8)
					{
						$checkparaf= new TrLooParaf();
						$checkparaf->setField("TR_LOO_ID", $reqId);
						$checkparaf->resetparalelnourut();
					}
				}

				// kondisi tukar data paralel apabila urutan user bantu lebih besar
				$checkparaf= new TrLooParaf();
				$checkparaf->selectByParams(array(), -1, -1, " AND A.STATUS_BANTU IS NULL AND KONDISI_PARAF = 'PARALEL' AND A.TR_LOO_ID = ".$reqId);
				$checkparaf->firstRow();
				$checkparafuserdireksi= $checkparaf->getField("NO_URUT");

				$checkparaf= new TrLooParaf();
				$checkparaf->selectByParams(array(), -1, -1, " AND A.STATUS_BANTU = 1 AND KONDISI_PARAF = 'PARALEL' AND A.TR_LOO_ID = ".$reqId);
				$checkparaf->firstRow();
				$checkparafuserbantu= $checkparaf->getField("NO_URUT");

				// echo $checkparafuserdireksi."xxx".$checkparafuserbantu;exit;

				if(!empty($checkparafuserdireksi) && !empty($checkparafuserbantu) && $checkparafuserbantu > $checkparafuserdireksi)
				{
					$checkparaf= new TrLooParaf();
					$checkparaf->setField("TR_LOO_ID", $reqId);
					$checkparaf->setField("NO_URUT_DIREKSI", $checkparafuserbantu);
					$checkparaf->setField("NO_URUT_BANTU", $checkparafuserdireksi);
					$checkparaf->tukarurutanparalel();
				}
			}


			$inforeturninfo= "";
			if ($reqStatusData == "DRAFT") {

				$arrtriger= array("reqId"=>$reqId, "mode"=>"tnomor");
				$this->trigerpaksa($arrtriger);

				if($reqKondisiStatusData == "UBAHDATAPARAF" || $reqKondisiStatusData == "UBAHDATAREVISI")
				{
					$inforeturninfo= "Naskah berhasil disimpan.";
				}
				else
				{
					$inforeturninfo= "Naskah berhasil disimpan sebagai DRAFT.";
				}
			}

			if(!empty($inforeturninfo))
			{
				echo $reqId."xxx".$inforeturninfo;
			}
			else
			{
				$reqInfoLog= $this->input->post("reqInfoLog");
				
				$arrparam= array("reqId"=>$reqId, "reqInfoLog"=>$reqInfoLog);
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
		$this->load->model("TrLoo");
		$reqId= $arrparam["reqId"];
		$mode= $arrparam["mode"];

		$tgr= new TrLoo();
		$tgr->setField("TR_LOO_ID", $reqId);
		$tgr->setField("PAKSA_DB", $mode);
		$tgr->updatetriger();
	}

	function tesparaf()
	{
		$reqId= $this->input->get("reqId");
		$reqInfoLog= "Coba";

		$arrparam= array("reqId"=>$reqId, "reqInfoLog"=>$reqInfoLog);
		$this->paraf_proses($arrparam);
	}

	function paraf_proses($arrparam)
	{
		$reqId= $arrparam["reqId"];
		$reqInfoLog= $arrparam["reqInfoLog"];

		$this->load->model("TrLoo");
		$set= new TrLoo();

		$sesid= $this->ID;
		$sesnama= $this->NAMA;
		$sesjabatan= $this->JABATAN;
		$sesusername= $this->USERNAME;
		$satuankerjaganti= $this->SATUAN_KERJA_ID_ASAL;
		$kodeParaf = "PARAF".$sesid.generateZero($reqId, 6).date("dmYHis");

		$set->setField("TR_LOO_ID", $reqId);
		$set->setField("SATUAN_KERJA_ID_TUJUAN", $satuankerjaganti);
		$set->setField("KODE_PARAF", $kodeParaf);
		$set->setField("USER_ID", $sesid);
		$set->setField("LAST_UPDATE_USER", $sesusername);

		if ($set->paraf()) {

			// simpan log data, kalau ada data varible reqInfoLog
			if(!empty($reqInfoLog))
			{
				$slog= new TrLoo();
				$slog->setField("TR_LOO_ID", $reqId);
				$slog->setField("STATUS_SURAT", "PARAF");
				$slog->setField("INFORMASI", $sesjabatan." (".$sesnama.")");
				$slog->setField("CATATAN", $reqInfoLog);
				$slog->setField("LAST_CREATE_USER", $sesid);
				$slog->insertlog();
				unset($slog);
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
			$arrparam= array("reqId"=>$reqId);
			$this->posting_proses($arrparam);
		}

	}

	function posting_proses($arrparam)
	{
		$this->load->model("TrLoo");
		$reqId= $arrparam["reqId"];

		$set= new TrLoo();
		$statusSurat= $set->getStatusSurat(array("A.TR_LOO_ID" => $reqId));
		// echo $statusSurat;exit;
	}

	function delete()
	{
		$reqId	= $this->input->get('reqId');
		$this->load->model("TrLoo");
		$trloo = new TrLoo();


		$trloo->setField("PRODUK_ID", $reqId);
		if ($trloo->delete())
			$arrJson["PESAN"] = "Data berhasil dihapus.";
		else
			$arrJson["PESAN"] = "Data gagal dihapus.";

		echo json_encode($arrJson);
	}


}

