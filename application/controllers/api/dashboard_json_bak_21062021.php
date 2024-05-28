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
        /* END OF AMBIL TOKEN */

        $this->load->library('suratmasukinfo');
		$this->load->model("Konten");
        $this->load->model("Faq");
        $this->load->model("SuratMasuk");
        $this->load->model("SuratKeluar");
        $this->load->model("Disposisi");
        $this->load->model("Arsip");
        $this->load->model("Pegawai");
        $suratmasukinfo = new suratmasukinfo();
		$konten = new Konten();
        $konten_slider = new Konten();
        $faq = new Faq();
        $surat_masuk = new SuratMasuk();
        $surat_keluar = new SuratKeluar();
        $disposisi = new Disposisi();
        $arsip = new Arsip();
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


        if($reqUserGroup == "TATAUSAHA"){
	        $result["JUMLAH_SURAT_MASUK"]   = $surat_masuk->getCountByParams(array());
	    }
	    else{
            $statement_jmlsurat .= " AND B.DISPOSISI_PARENT_ID = 0
                AND (
                    A.STATUS_SURAT = 'POSTING' OR
                    A.STATUS_SURAT = 'TU-NOMOR' OR
                    (
                        A.STATUS_SURAT = 'TU-IN' AND
                        EXISTS(SELECT 1 FROM SURAT_MASUK_ARSIP X WHERE X.SURAT_MASUK_ID = A.SURAT_MASUK_ID AND X.CABANG_ID = '".$userMobile->CABANG_ID."')
                    )
                )";

            if($userMobile->KD_LEVEL_PEJABAT == "")
            {
                $statement_jmlsurat.= " AND 
                (
                    (
                        ( B.USER_ID = '".$userMobile->ID."' OR B.USER_ID = '')
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
                            WHERE B.KELOMPOK_JABATAN = '".$reqKelompokJabatan."'
                        ) X WHERE X.SURAT_MASUK_ID = B.SURAT_MASUK_ID AND X.DISPOSISI_KELOMPOK_ID = B.DISPOSISI_KELOMPOK_ID
                        AND B.SATUAN_KERJA_ID_TUJUAN = '".$userMobile->CABANG_ID."'

                        )
                    )
                    ";
            }
            else
            {
                $statement_jmlsurat.= " AND (B.SATUAN_KERJA_ID_TUJUAN = '".$userMobile->SATUAN_KERJA_ID_ASAL."' OR B.USER_ID = '".$userMobile->ID."' OR B.USER_ID_OBSERVER = '".$userMobile->ID."') ";
            }

	    }


        $suratmasukinfo->getModifJumlahSurat($userMobile->ID, $userMobile->USER_GROUP, $userMobile->CABANG_ID, $userMobile->ID_ATASAN, $userMobile->KELOMPOK_JABATAN);

        $result["JUMLAH_SURAT_MASUK"]       = $suratmasukinfo->JUMLAH_INBOX;
        $result["JUMLAH_DISPOSISI"]         = $surat_masuk->getCountByParamsInbox(array(), " AND (A.USER_ID = '".$userMobile->ID."' OR B.USER_ID = '".$userMobile->ID."') ");
        $result["JUMLAH_VALIDASI"]          = $suratmasukinfo->JUMLAH_VALIDASI;
        $result["JUMLAH_DELEGASI"]          = '1';

        $result["JUMLAH_BADGE_SURAT_MASUK"] = $suratmasukinfo->JUMLAH_KOTAK_MASUK_SEMUA;
        $result["JUMLAH_BADGE_VALIDASI"]    = $suratmasukinfo->JUMLAH_VALIDASI;

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