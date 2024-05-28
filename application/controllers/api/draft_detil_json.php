<?php
 
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/default.func.php");
 
class draft_detil_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();
    }
 
    // show data entitas
    function index_get() {
		error_reporting(1);

		$this->db->query("SET DATESTYLE TO PostgreSQL,European;"); 
		
		
		$reqId = $this->input->get("reqId");

	   	/* AMBIL INFO LOGIN */
        $this->load->model('UserLoginMobile');
        $user_login_mobile = new UserLoginMobile();
        $reqToken = $this->input->get('reqToken');	
		$reqPegawaiId = $user_login_mobile->getTokenPegawaiId(array("TOKEN" => $reqToken, "STATUS" => '1'));

		/* CHECK SESION */
		if($reqPegawaiId === 0){
			$this->response(array('status' => 'fail', 'message' => 'Sesi Anda telah berakhir.', 'code' => 502));
		}
		
		$this->load->library("usermobile"); $userMobile = new usermobile();
        $userMobile->getInfo($reqPegawaiId, $reqToken);
	   	/* END OF AMBIL INFO LOGIN */
		
		$this->load->library("Pagination");
		
		$this->load->model("SuratMasuk");
		$this->load->model("SuratMasukParaf");
		$this->load->model("Disposisi");
		$surat_masuk = new SuratMasuk();
		$disposisi 	 = new Disposisi();
		$surat_masuk_paraf = new SuratMasukParaf();
	
		$surat_masuk->selectByParams(array("A.SURAT_MASUK_ID" => $reqId, "A.SATUAN_KERJA_ID_ASAL" => $userMobile->SATUAN_KERJA_ID_ASAL));
		$surat_masuk->firstRow();
			
		$refSuratId = $surat_masuk->getField("SURAT_MASUK_REF_ID");
		$reqId 			= $surat_masuk->getField("SURAT_MASUK_ID");
			
		if($reqId == "")
			$this->response(array('status' => 'fail', 'message' => 'Anda tidak diperbolehkan mengakses surat ini.', 'code' => 502));

		$arrData = array();	


		$surat_masuk_ref = new SuratMasuk();
		$surat_masuk_ref->selectByParams(array("A.SURAT_MASUK_ID" => $refSuratId));
		$surat_masuk_ref->firstRow();
		$refPerihal = $surat_masuk_ref->getField("PERIHAL");
		$refNomor = $surat_masuk_ref->getField("NOMOR");

		$arrData["NOMOR_REF"] 		= $surat_masuk_ref->getField("NOMOR");
		$arrData["PERIHAL_REF"] 	= $surat_masuk_ref->getField("PERIHAL");
		
		

		$arrData["SURAT_MASUK_ID"] = $surat_masuk->getField("SURAT_MASUK_ID");		
		$arrData["JENIS_NASKAH_ID"] = $surat_masuk->getField("JENIS_NASKAH_ID");
		$arrData["NO_AGENDA"] 		= $surat_masuk->getField("NO_AGENDA");
		$arrData["NOMOR"] 			= $surat_masuk->getField("NOMOR");
		$arrData["TANGGAL"] 		= $surat_masuk->getField("TANGGAL");
		$arrData["PERIHAL"] 		= $surat_masuk->getField("PERIHAL");
		$arrData["ISI"] 			= $surat_masuk->getField("ISI");
		$arrData["SIFAT_NASKAH_ID"] 	= $surat_masuk->getField("SIFAT_NASKAH");
		$arrData["STATUS_SURAT"] 		= $surat_masuk->getField("STATUS_SURAT");
		$arrData["LOKASI_SIMPAN"] 		= $surat_masuk->getField("LOKASI_SIMPAN");
		$arrData["INSTANSI_ASAL"] 		=  $surat_masuk->getField("INSTANSI_ASAL");
		$arrData["KOTA_ASAL"] 			=  $surat_masuk->getField("KOTA_ASAL");
		$arrData["ALAMAT_ASAL"] 		=  $surat_masuk->getField("ALAMAT_ASAL");
		
		
				
		$reqKepada		= $disposisi->getJson(array("SURAT_MASUK_ID" => $reqId, "STATUS_DISPOSISI" => "TUJUAN"));
		$arrKepada = json_decode($reqKepada);
		$arrDataKepada = array();
		$i=0;
		foreach ($arrKepada as $key => $value) {
		   $arrDataKepada[$i]["SATUAN_KERJA_ID"] = $value->SATUAN_KERJA_ID;
		   $arrDataKepada[$i]["NAMA"] = $value->SATUAN_KERJA;
		   $arrDataKepada[$i]["NAMA_PEGAWAI"] = $value->NAMA_PEGAWAI;
		   $i++;								   
	    }
		$arrData["KEPADA"]	= $arrDataKepada;		
				   
				   
				   
		$reqTembusan 	= $disposisi->getJson(array("SURAT_MASUK_ID" => $reqId, "STATUS_DISPOSISI" => "TEMBUSAN"));
		$arrTembusan = json_decode($reqTembusan);
		$arrDataTembusan = array();
		$i=0;
		foreach ($arrTembusan as $key => $value) {
		   $arrDataTembusan[$i]["SATUAN_KERJA_ID"] = $value->SATUAN_KERJA_ID;
		   $arrDataTembusan[$i]["NAMA"] = $value->SATUAN_KERJA;
		   $arrDataTembusan[$i]["NAMA_PEGAWAI"] = $value->NAMA_PEGAWAI;
		   $i++;								   
	    }
		$arrData["TEMBUSAN"]	= $arrDataTembusan;		
				   
		$reqParaf 	= $surat_masuk_paraf->getJson(array("SURAT_MASUK_ID" => $reqId));
		$arrParaf = json_decode($reqParaf);
		$arrDataParaf = array();
		$i=0;
		foreach ($arrParaf as $key => $value) {
		   $arrDataParaf[$i]["SATUAN_KERJA_ID"] = $value->SATUAN_KERJA_ID;
		   $arrDataParaf[$i]["NAMA"] = $value->SATUAN_KERJA;
		   $arrDataParaf[$i]["NAMA_PEGAWAI"] = $value->NAMA_PEGAWAI;
		   $i++;								   
	    }
		$arrData["PARAF"]		= $arrDataParaf;		
				   

		$surat_masuk_attachment = new SuratMasuk();
		$surat_masuk_attachment->selectByParamsAttachment(array("A.SURAT_MASUK_ID" => (int)$reqId));
		$arrDataAttachment = array();
		$i = 0;
		while($surat_masuk_attachment->nextRow())
		{
			$arrDataAttachment[$i]["SURAT_MASUK_ATTACHMENT_ID"] = $surat_masuk_attachment->getField("SURAT_MASUK_ATTACHMENT_ID");
			$arrDataAttachment[$i]["ATTACHMENT"] = base_url()."uploads/".$surat_masuk_attachment->getField("ATTACHMENT");
			$arrDataAttachment[$i]["NAMA"] = $surat_masuk_attachment->getField("NAMA");
			$i++;
		}
        $arrData["LAMPIRAN"]			= $arrDataAttachment;		
        
        $arrResponse["children"] = $arrData; 
		
        $this->response(array('status' => 'success', 'message' => 'success', 'code' => 200,'data' => $arrResponse));
       
    }
    
    // insert new data to entitas
    function index_post() {
		
		
			
    }
	
    // update data entitas
    function index_put() {
    }
 
    // delete entitas
    function index_delete() {

    }
 
}