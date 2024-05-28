<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once("functions/image.func.php");
include_once("functions/string.func.php");

class App extends CI_Controller {

	function __construct() {
		parent::__construct();
		//kauth
		if (!$this->kauth->getInstance()->hasIdentity())
		{
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
		$this->MULTIROLE		= $this->kauth->getInstance()->getIdentity()->MULTIROLE;  
		$this->CABANG_ID		= $this->kauth->getInstance()->getIdentity()->CABANG_ID;  
		$this->CABANG			= $this->kauth->getInstance()->getIdentity()->CABANG;  
		$this->SATUAN_KERJA_ID_ASAL	= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_ID_ASAL;  
		$this->SATUAN_KERJA_ASAL	= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_ASAL;  
		$this->SATUAN_KERJA_HIRARKI	= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_HIRARKI;  
		$this->SATUAN_KERJA_JABATAN	= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_JABATAN;  
		$this->KD_LEVEL			= $this->kauth->getInstance()->getIdentity()->KD_LEVEL;  
		$this->KD_LEVEL_PEJABAT = $this->kauth->getInstance()->getIdentity()->KD_LEVEL_PEJABAT;  
		$this->JENIS_KELAMIN 	= $this->kauth->getInstance()->getIdentity()->JENIS_KELAMIN;  
		$this->KELOMPOK_JABATAN = $this->kauth->getInstance()->getIdentity()->KELOMPOK_JABATAN;  
		$this->ID_ATASAN 		= $this->kauth->getInstance()->getIdentity()->ID_ATASAN;  
		
		$this->NIP_BY_DIVISI 			= $this->kauth->getInstance()->getIdentity()->NIP_BY_DIVISI;
		$this->KELOMPOK_JABATAN_BY_DIVISI 			= $this->kauth->getInstance()->getIdentity()->KELOMPOK_JABATAN_BY_DIVISI;
		
		$this->SATUAN_KERJA_ID_ASAL_ASLI= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_ID_ASAL_ASLI;
	}
	
	
	public function index()
	{
		
		$pg = $this->uri->segment(3, "home");
		$reqParse1 = $this->uri->segment(4, "");
		$reqParse2 = $this->uri->segment(5, "");
		$reqParse3 = $this->uri->segment(6, "");
		$reqParse4 = $this->uri->segment(7, "");
		$reqParse5 = $this->uri->segment(5, "");
		$reqId = $this->input->get("reqId");

				
		$view = array(
			'pg' => $pg,
			'linkBack' => $file."_detil",
			'reqParse1' => $reqParse1,
			'reqParse2'	=> $reqParse2,
			'reqParse3'	=> $reqParse3,
			'reqParse4'	=> $reqParse4,
			'reqParse5'	=> $reqParse5
		);	
		
		$data = array(
			'breadcrumb' => $breadcrumb,
			'pg' => $pg,
			'reqParse1' => $reqParse1,
			'reqParse2'	=> $reqParse2,
			'reqParse3'	=> $reqParse3,
			'reqParse4'	=> $reqParse4,
			'reqParse5'	=> $reqParse5
		);	
		
		//$this->load->view('app/index', $data);
		$this->load->view('main/index', $data);
	}	
	
	public function loadUrl()
	{
		
		$reqFolder = $this->uri->segment(3, "");
		$reqFilename = $this->uri->segment(4, "");
		$reqParse1 = $this->uri->segment(5, "");
		$reqParse2 = $this->uri->segment(6, "");
		$reqParse3 = $this->uri->segment(7, "");
		$reqParse4 = $this->uri->segment(8, "");
		$reqParse5 = $this->uri->segment(9, "");
		$data = array(
			'reqParse1' => urldecode($reqParse1),
			'reqParse2' => urldecode($reqParse2),
			'reqParse3' => urldecode($reqParse3),
			'reqParse4' => urldecode($reqParse4),
			'reqParse5' => urldecode($reqParse5)
		);
		if($reqFolder == "main")
			$this->session->set_userdata('currentUrl', $reqFilename);
		
		$this->load->view($reqFolder.'/'.$reqFilename, $data);
	}	
	
	
	public function getJumlahSurat()
	{
		$reqId = $this->input->get("reqId");
		$reqJenisSurat = $this->input->get("reqJenisSurat");
		$cq = $this->input->get("cq");
		
		$this->load->library('suratmasukinfo'); $suratmasukinfo = new suratmasukinfo();
		
		if($reqId > 0)
		{
			//if($reqJenisSurat == "INTERNAL")
				$suratmasukinfo->setTerbaca($reqId, $this->ID);			
			//else
			//{
			//	$this->load->library('suratkeluarinfo'); $suratkeluarinfo = new suratkeluarinfo();
		   //		$suratkeluarinfo->setTerbaca($reqId, $this->ID);
			//}
		}
		
		$infogantijabatan= "";
		if($this->SATUAN_KERJA_ID_ASAL_ASLI == $this->SATUAN_KERJA_ID_ASAL){}
		else
		{
			$infogantijabatan= "1";
		}
		$infogantijabatan= $this->SATUAN_KERJA_ID_ASAL;

		// echo "userId:".$this->ID."<br/>";
		// echo "userGroup:".$this->USER_GROUP."<br/>";
		// echo "cabangId:".$this->CABANG_ID."<br/>";
		// echo "useridatasan:".$this->ID_ATASAN."<br/>";
		// echo "userkelompok:".$this->KELOMPOK_JABATAN."<br/>";
		// exit;
		// $suratmasukinfo->getJumlahSurat($this->ID, $this->USER_GROUP, $this->CABANG_ID);
		// $suratmasukinfo->getModifJumlahSurat($this->ID, $this->USER_GROUP, $this->CABANG_ID, $this->ID_ATASAN, $this->KELOMPOK_JABATAN);
		
		$suratmasukinfo->getnewjumlahsurat($this->ID, $this->USER_GROUP, $this->CABANG_ID, $this->ID_ATASAN, $this->KELOMPOK_JABATAN, $infogantijabatan, $cq);
		$arrData["JUMLAH_INBOX"] = $suratmasukinfo->JUMLAH_INBOX;
		// echo "userId:".$suratmasukinfo->JUMLAH_INBOX."<br/>";
		$arrData["JUMLAH_VALIDASI"] = $suratmasukinfo->JUMLAH_VALIDASI;
		$arrData["JUMLAH_DRAFT"] = $suratmasukinfo->JUMLAH_DRAFT;
		$arrData["JUMLAH_PERSETUJUAN"] = $suratmasukinfo->JUMLAH_PERSETUJUAN;
		$arrData["JUMLAH_DRAFT_MANUAL"] = $suratmasukinfo->JUMLAH_DRAFT_MANUAL;

		$suratmasukinfo->getkotakmasukbelum();
		$arrData["JUMLAH_KOTAK_MASUK_SEMUA"] = $suratmasukinfo->JUMLAH_KOTAK_MASUK_SEMUA;
		$arrData["JUMLAH_KOTAK_MASUK_NOTA_DINAS"] = $suratmasukinfo->JUMLAH_KOTAK_MASUK_NOTA_DINAS;
		$arrData["JUMLAH_KOTAK_MASUK_SURAT_KELUAR"] = $suratmasukinfo->JUMLAH_KOTAK_MASUK_SURAT_KELUAR;
		$arrData["JUMLAH_KOTAK_MASUK_SURAT_EDARAN"] = $suratmasukinfo->JUMLAH_KOTAK_MASUK_SURAT_EDARAN;
		$arrData["JUMLAH_KOTAK_MASUK_SURAT_PERINTAH"] = $suratmasukinfo->JUMLAH_KOTAK_MASUK_SURAT_PERINTAH;
		$arrData["JUMLAH_KOTAK_MASUK_SURAT_KEPUTUSAN_DIREKSI"] = $suratmasukinfo->JUMLAH_KOTAK_MASUK_SURAT_KEPUTUSAN_DIREKSI;
		$arrData["JUMLAH_KOTAK_MASUK_KEPUTUSAN_DIREKSI"] = $suratmasukinfo->JUMLAH_KOTAK_MASUK_KEPUTUSAN_DIREKSI;
		$arrData["JUMLAH_KOTAK_MASUK_INSTRUKSI_DIREKSI"] = $suratmasukinfo->JUMLAH_KOTAK_MASUK_INSTRUKSI_DIREKSI;
		$arrData["JUMLAH_KOTAK_MASUK_MANUAL"] = $suratmasukinfo->JUMLAH_KOTAK_MASUK_MANUAL;
		
		echo json_encode($arrData);
	}
	
	public function ubahFoto()
	{
		
		/* WAJIB UNTUK UPLOAD DATA */
		$this->load->library("FileHandler");
		$file = new FileHandler();
		$FILE_DIR= "uploads/profil/";
		
		$reqLinkFile = $_FILES["reqLinkFile"];
		
		$uploadFile = $this->ID."PROFIL.".getExtension($reqLinkFile['name']);
		$renameFile = $this->ID.".".getExtension($reqLinkFile['name']);
	
		if($file->uploadToDir('reqLinkFile', $FILE_DIR, $uploadFile))
		{
			createThumbnail($FILE_DIR.$uploadFile, $FILE_DIR.$renameFile, 300);
			unlink($uploadFile);
			echo "Profil berhasil diubah.";	
		}
	}


	function tesqr()
	{

			$reqId = 1000;
			$kodeParaf = "1000";

			/* GENERATE QRCODE */
			include_once("libraries/phpqrcode/qrlib.php");
			
			$FILE_DIR= "uploads/".$reqId."/";
			makedirs($FILE_DIR);
			$filename = $FILE_DIR.$kodeParaf.'.jpg';
			$errorCorrectionLevel = 'L';   
			$matrixPointSize = 2;
			QRcode::png($kodeParaf, $filename, $errorCorrectionLevel, $matrixPointSize, 2);    
			/* END OF GENERATE QRCODE */
			echo "bisa";

	}

	function tesqr2()
	{
		$reqId = 46;
$this->load->model("SuratMasuk");
			$getinfottd = new SuratMasuk();
				$getinfottd->selectByParamsGetInfoTtdSurat(array("A.SURAT_MASUK_ID" => $reqId));
				$getinfottd->firstRow();

				$pesanQrCode = "ID: ".$getinfottd->getField("TTD_KODE")."\n";
				$pesanQrCode.= "ApprovedBy: ".$getinfottd->getField("APPROVED_BY")."\n";
				$pesanQrCode.= "Nomor Surat: ".$getinfottd->getField("NOMOR")."\n";
				$pesanQrCode.= "Tanggal Surat: ".$getinfottd->getField("APPROVAL_DATE");

				include_once("libraries/phpqrcode/qrlib.php");

				$FILE_DIR = "uploads/" . $reqId . "/";
				makedirs($FILE_DIR);
				$filename = $FILE_DIR . $getinfottd->getField("TTD_KODE") . '.png';
				$errorCorrectionLevel = 'L';
				$matrixPointSize = 5;
				QRcode::png($pesanQrCode, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
			echo "bisa";

	}

}

