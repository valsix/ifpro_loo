<?php
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class draft_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();
          
        $this->load->library('Kauth');

        $this->db->query("SET DATESTYLE TO PostgreSQL,European;");  
        
        $this->methods['index_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['index_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['index_put']['limit'] = 50; // 50 requests per hour per user/key
    }
 
    // show data entitas
	function index_get() {
	   	/* AMBIL TOKEN */
        $this->load->model('UserLoginMobile');
        $user_login_mobile = new UserLoginMobile();
        $reqToken = $this->input->get("reqToken");
        $reqPegawaiId = $user_login_mobile->getTokenPegawaiId(array("TOKEN" => $reqToken, "STATUS" => '1'));
        $reqUserGroup = $user_login_mobile->getUserGroupPegawaiId(array("TOKEN" => $reqToken, "STATUS" => '1'));
        
        if($reqPegawaiId === 0){
            $this->response(array('status' => 'fail', 'message' => 'Sesi anda telah berakhir', 'code' => 502));
            return; 
        }

        $this->load->library("usermobile"); 
        $userMobile = new usermobile();
        $userMobile->getInfo($reqPegawaiId, $reqToken, $reqUserGroup);
        /* END OF AMBIL TOKEN */
				
        $this->load->model("SuratMasuk");
        $this->load->model("SuratMasukParaf");
        $this->load->model("Disposisi");
        $this->load->model("DisposisiKelompok");
        $surat_masuk = new SuratMasuk();
        $surat_masuk_attachment = new SuratMasuk();
        $disposisi   = new Disposisi();
        $disposisi_kelompok = new DisposisiKelompok();
        $surat_masuk_paraf = new SuratMasukParaf();

        $reqId = $this->input->get("reqId");

        $aColumns = array("SURAT_MASUK_REF_ID","SURAT_MASUK_ID","JENIS_NASKAH_ID","KLASIFIKASI_ID","NO_AGENDA","NOMOR","TANGGAL",
            "PERIHAL","ISI","SIFAT_NASKAH","STATUS_SURAT","LOKASI_SIMPAN","INSTANSI_ASAL","KOTA_ASAL","ALAMAT_ASAL","PENYAMPAIAN_SURAT",
            "USER_ATASAN_ID","REVISI","TANGGAL_KEGIATAN_EDIT","TANGGAL_KEGIATAN_AKHIR_EDIT","JAM_KEGIATAN_EDIT","JAM_KEGIATAN_AKHIR_EDIT",
            "IS_EMAIL","IS_MEETING","PRIORITAS_SURAT_ID","PERMOHONAN_NOMOR_ID","JENIS_NASKAH_LEVEL","ARSIP_ID","ARSIP","JENIS_TTD","SURAT_PDF",
            "KLASIFIKASI_ID","SATUAN_KERJA_ID_ASAL");

        $result = array();

        if($userMobile->USER_GROUP == "PEGAWAI")
        {
            $statement =" AND ( 
                                (A.USER_ID = '".$userMobile->ID."' AND STATUS_SURAT IN ('DRAFT', 'REVISI', 'PARAF')) 
                                OR 
                                (EXISTS(SELECT 1 FROM SURAT_MASUK_AKSES X WHERE X.SURAT_MASUK_ID = A.SURAT_MASUK_ID AND X.USER_ID = '".$userMobile->ID."' AND STATUS_SURAT IN ('PARAF', 'VALIDASI'))) 
                            ) ";
            
        }
        else
        {
            $statement =" AND ( 
                                (A.USER_ID = '".$userMobile->ID."' AND STATUS_SURAT IN ('DRAFT', 'REVISI')) 
                                OR 
                                (EXISTS(SELECT 1 FROM SURAT_MASUK_AKSES X WHERE X.SURAT_MASUK_ID = A.SURAT_MASUK_ID AND X.USER_ID = '".$userMobile->ID."' AND STATUS_SURAT IN ('PARAF', 'VALIDASI'))) 
                            ) ";    
        }

		$surat_masuk->selectByParams(array("A.SURAT_MASUK_ID" => $reqId), -1, -1, $statement);
        // echo $surat_masuk->query;exit;
		while($surat_masuk->nextRow())
		{
            for($i=0;$i<count($aColumns);$i++)
            {
                if($aColumns[$i] == "TANGGAL"){
                    $row[trim($aColumns[$i])] = getFormattedDate($surat_masuk->getField(trim($aColumns[$i]))); 
                }
                elseif($aColumns[$i] == "TANGGAL_KEGIATAN_EDIT"){
                    $row[trim($aColumns[$i])] = getFormattedDate($surat_masuk->getField(trim($aColumns[$i]))); 
                }
                elseif($aColumns[$i] == "TANGGAL_KEGIATAN_AKHIR_EDIT"){
                    $row[trim($aColumns[$i])] = getFormattedDate($surat_masuk->getField(trim($aColumns[$i]))); 
                }
                elseif($aColumns[$i] == "TERBACA")
                {
                    if($surat_masuk->getField(trim($aColumns[$i])) == 1){
                        $row[trim($aColumns[$i])] = "1";  
                    }
                    else{
                        $row[trim($aColumns[$i])] = "0";              
                    }
                }
                elseif($aColumns[$i] == "ISI"){
                    $row[trim($aColumns[$i])] = strip_tags($surat_masuk->getField(trim($aColumns[$i]))); 
                }
                elseif($aColumns[$i] == "FOTO"){
                    $row["FOTO"] = generateFotoMobile("X", $surat_masuk->getField("NAMA_USER_ASAL"));
                }
                else{
                    $row[trim($aColumns[$i])] = $surat_masuk->getField(trim($aColumns[$i]));
                }
            }
            
            $row["PENERBIT_NOMOR"]      = $this->db->query("SELECT PENERBIT_NOMOR FROM JENIS_NASKAH WHERE JENIS_NASKAH_ID = '".$surat_masuk->getField("JENIS_NASKAH_ID")."' ")->row()->penerbit_nomor;
            $row["PARAF"]               = $surat_masuk_paraf->getParaf(array("SURAT_MASUK_ID" => $reqId));
            $row["KEPADA"]              = $disposisi->getJson(array("SURAT_MASUK_ID" => $reqId, "STATUS_DISPOSISI" => "TUJUAN"));
            $row["TEMBUSAN"]            = $disposisi->getJson(array("SURAT_MASUK_ID" => $reqId, "STATUS_DISPOSISI" => "TEMBUSAN"));
            
            $row["KEPADA_KELOMPOK"]     = $disposisi_kelompok->getJson(array("SURAT_MASUK_ID" => $reqId, "STATUS_DISPOSISI" => "TUJUAN"));
            $row["TEMBUSAN_KELOMPOK"]   = $disposisi_kelompok->getJson(array("SURAT_MASUK_ID" => $reqId, "STATUS_DISPOSISI" => "TEMBUSAN"));

            $result = $row;
		}

        
        $this->response(array('status' => 'success', 'message' => 'success', 'code' => 200, 'count' => count($aColumns), 'result' => $result));
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