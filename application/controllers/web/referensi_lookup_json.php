<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class referensi_lookup_json extends CI_Controller {

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
	}

	

	function combo() 
	{

		// ini_set('display_errors', 1);
		// ini_set('display_startup_errors', 1);
		// error_reporting(E_ALL);
		
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : 50;
		$offset = ($page-1)*$rows;
		
		$reqPencarian = $this->input->get("reqPencarian");
		$reqMode = $this->input->get("reqMode");
		$reqJenisNaskahId= $this->input->get("reqJenisNaskahId");
		$reqTahun= $this->input->get("reqTahun");
		
		
		$this->load->model("SuratMasuk");
		$surat_masuk = new SuratMasuk();

		// if($reqPencarian == "")
		// {}
		// else
		// 	// $statement = " AND (UPPER(A.NAMA) LIKE '%".strtoupper($reqPencarian)."%' OR UPPER(A.PEGAWAI_ID) LIKE '%".strtoupper($reqPencarian)."%') ";
		
		// // if($this->CABANG_ID == "01")
		// // 	$statement_privacy = "";
		// // else
		// // 	$statement_privacy = " AND A.SATUAN_KERJA_ID = '".$this->CABANG_ID."' ";
			$searchJson= " 
		AND 
		(
			UPPER(A.NO_AGENDA) LIKE '%".strtoupper($_GET['sSearch'])."%' OR 
			UPPER(A.NOMOR) LIKE '%".strtoupper($_GET['sSearch'])."%' OR
			UPPER(A.PERIHAL) LIKE '%".strtoupper($_GET['sSearch'])."%' OR 
			UPPER(A.INSTANSI_ASAL) LIKE '%".strtoupper($_GET['sSearch'])."%'
		)";

		$statement= " 
		AND B.DISPOSISI_PARENT_ID = 0
		AND 
		(
			A.STATUS_SURAT = 'POSTING' OR
			A.STATUS_SURAT = 'TU-NOMOR' OR
			(
				A.STATUS_SURAT = 'TU-IN' AND
				EXISTS(SELECT 1 FROM SURAT_MASUK_ARSIP X WHERE X.SURAT_MASUK_ID = A.SURAT_MASUK_ID AND X.CABANG_ID = '".$this->CABANG_ID."')
			)
		)";

		if($this->KD_LEVEL_PEJABAT == "")
		{
			// $statement.= " AND (B.USER_ID = '".$this->ID."' OR B.USER_ID = '".$this->ID_ATASAN."' ) ";
			$statement.= " AND 
			(
				(
					( B.USER_ID = '".$this->ID."' OR B.USER_ID = '".$this->ID_ATASAN."')
					AND B.DISPOSISI_KELOMPOK_ID = 0
				)
				OR
				EXISTS
				(
					SELECT 1
					FROM
					(
						SELECT A.DISPOSISI_KELOMPOK_ID, A.SURAT_MASUK_ID
						FROM disposisi_kelompok A 
						INNER JOIN satuan_kerja_kelompok_group B ON A.SATUAN_KERJA_KELOMPOK_ID = B.SATUAN_KERJA_KELOMPOK_ID
						WHERE B.KELOMPOK_JABATAN = '".$this->KELOMPOK_JABATAN."'
					) X WHERE X.SURAT_MASUK_ID = B.SURAT_MASUK_ID
				";

				if($this->KELOMPOK_JABATAN == "KARYAWAN"){}
				else
				{
					$statement.= " AND B.SATUAN_KERJA_ID_TUJUAN = '".$this->KODE_PARENT."' ";
				}
			$statement.= "
				)
			) ";
		}
		else
		{
			$statement.= " AND (B.SATUAN_KERJA_ID_TUJUAN = '".$this->SATUAN_KERJA_ID_ASAL."' OR B.USER_ID = '".$this->ID."' OR B.USER_ID_OBSERVER = '".$this->ID."') ";
		}

		if(!empty($reqJenisNaskahId))
		{
			$statement.= " AND A.JENIS_NASKAH_ID IN (".$reqJenisNaskahId.")";
		}

		if(!empty($reqTahun))
		{
			$statement.= " AND A.TAHUN = ".$reqTahun;
		}
		
		$rowCount = $surat_masuk->getCountByParamsSuratMasuk(array(), $statement.$statement_privacy);
		$surat_masuk->selectByParamsSuratMasuk(array(), $rows, $offset, $statement.$statement_privacy);
		$i = 0;
		$items = array();
		while($surat_masuk->nextRow())
		{
			$row['id']		= $surat_masuk->getField("SURAT_MASUK_ID");
			$row['text']	= $surat_masuk->getField("TEXT");
			$row['NAMA']	= $surat_masuk->getField("USER_ATASAN")."<br/>".$surat_masuk->getField("USER_ATASAN_JABATAN");
			$row['NOMOR']	= $surat_masuk->getField("NOMOR")."<br/>".$surat_masuk->getField("PERIHAL")."<br/>".getFormattedExtDateTimeCheck($surat_masuk->getField("TANGGAL_ENTRI"));
			$row['PERIHAL']	= $surat_masuk->getField("PERIHAL");
			$row['TANGGAL_ENTRI']	= $surat_masuk->getField("TANGGAL_ENTRI");
			$row['state'] 	= 'close';
			$i++;
			array_push($items, $row);
		}
		$result["rows"] = $items;
		$result["total"] = $rowCount;
		echo json_encode($result);
	}
	
	
}

