<?php
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class dashboard_json extends REST_Controller {
 
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

        // $infogantijabatan= "";
        // if($userMobile->SATUAN_KERJA_ID_ASAL_ASLI == $userMobile->SATUAN_KERJA_ID_ASAL){}
        // else
        // {
        //     $infogantijabatan= "1";
        // }
        
        /* END OF AMBIL TOKEN */


        $this->load->library('suratmasukinfo');
        $this->load->model("SuratMasuk");
        $this->load->model("Pegawai");

        $suratmasukinfo = new suratmasukinfo();
        $surat_masuk = new SuratMasuk();
        $pegawai = new Pegawai();


        $result = array();


        $pegawai->selectByParamsInformasi(array("A.PEGAWAI_ID" => $userMobile->ID));
        
        $pegawai->firstRow();
        $result["FOTO"] = base_url().getFotoProfile($userMobile->USERNAME);
        $result["PEGAWAI_ID"] = $pegawai->getField("PEGAWAI_ID");
        $result["NIP"] = $pegawai->getField("NIP");
        $result["NAMA"] = $pegawai->getField("NAMA");
        $result["JABATAN"] = $pegawai->getField("JABATAN");
        $result["UNIT_KERJA"] = $pegawai->getField("SATUAN_KERJA");
        $result["DIREKTORAT"] = $pegawai->getField("DEPARTEMEN");
        $result["JABATAN_ATASAN"] = $pegawai->getField("JABATAN_ATASAN");


        // $jumlah_disposisi= 0;
        // $surat_masuk->selectByParamsDisposisi(array(), -1, -1, $userMobile->ID, "");
        // while($surat_masuk->nextRow())
        // {
        //     $infodisposisiuserid= $userMobile->ID;
        //     $infodisposisiterbacainfo= $surat_masuk->getField("TERBACA_INFO");

        //     $arrcheckterbaca= explode(";", $infodisposisiterbacainfo);
        //     if(!empty($arrcheckterbaca) && !empty($infodisposisiterbacainfo))
        //     {
        //         while (list($key, $val) = each($arrcheckterbaca))
        //         {
        //             $arrcheckterbacadetil= explode(",", $val);
        //             if($infodisposisiuserid == $arrcheckterbacadetil[0])
        //             {
        //                 $jumlah_disposisi--;
        //                 break;
        //             }
        //         }
        //     }
        //     $jumlah_disposisi++;
        // }
        
        // $suratmasukinfo->getnewjumlahsurat($userMobile->ID, $userMobile->USER_GROUP, $userMobile->CABANG_ID, $userMobile->ID_ATASAN, $userMobile->KELOMPOK_JABATAN, $infogantijabatan);

        // $result["JUMLAH_SURAT_MASUK"]       = $suratmasukinfo->JUMLAH_INBOX;
        // $result["JUMLAH_DISPOSISI"]         = $jumlah_disposisi;
        // $result["JUMLAH_VALIDASI"]          = $suratmasukinfo->JUMLAH_VALIDASI;
        // $result["JUMLAH_DELEGASI"]          = '0';

        // $result["JUMLAH_BADGE_SURAT_MASUK"] = $suratmasukinfo->JUMLAH_KOTAK_MASUK_SEMUA;
        // $result["JUMLAH_BADGE_VALIDASI"]    = $suratmasukinfo->JUMLAH_VALIDASI;
        

		$this->response(array('status' => 'success', 'message' => 'success', 'code' => 200, 'result' => $result));
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