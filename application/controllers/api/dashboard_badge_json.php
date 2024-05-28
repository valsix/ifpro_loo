<?php
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class dashboard_badge_json extends REST_Controller {
 
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

        $result = array();

        $infogantijabatan= "";
        if($userMobile->SATUAN_KERJA_ID_ASAL_ASLI == $userMobile->SATUAN_KERJA_ID_ASAL){}
        else
        {
            $infogantijabatan= "1";
        }
        $infogantijabatan= $userMobile->SATUAN_KERJA_ID_ASAL;

        $jumlah_disposisi= 0;
        
        $surat_masuk_disposisi = new SuratMasuk();
        $surat_masuk_disposisi->selectByParamsDisposisi(array(), -1, -1, $userMobile->ID, "");
        while($surat_masuk_disposisi->nextRow())
        {
            $infodisposisiuserid= $userMobile->ID;
            $infodisposisiterbacainfo= $surat_masuk_disposisi->getField("TERBACA_INFO");

            $arrcheckterbaca= explode(";", $infodisposisiterbacainfo);
            if(!empty($arrcheckterbaca) && !empty($infodisposisiterbacainfo))
            {
                while (list($key, $val) = each($arrcheckterbaca))
                {
                    $arrcheckterbacadetil= explode(",", $val);
                    if($infodisposisiuserid == $arrcheckterbacadetil[0])
                    {
                        $jumlah_disposisi--;
                        break;
                    }
                }
            }
            $jumlah_disposisi++;
        }

        $result["JUMLAH_DISPOSISI"] = $jumlah_disposisi;


        $statement= " 
        --AND SM_INFO NOT IN ('AKAN_DISETUJUI', 'NEXT_DISETUJUI')
        AND 
        (
            (
                (
                    A.USER_ATASAN_ID = '".$userMobile->ID."' AND A.APPROVAL_DATE IS NULL AND COALESCE(NULLIF(A.NIP_ATASAN_MUTASI, ''), NULL) IS NULL
                    AND TERPARAF IS NULL
                    --AND CASE WHEN A.STATUS_SURAT = 'PEMBUAT' THEN A.USER_ATASAN_ID = A.USER_ID END
                )
                OR 
                (
                    A.NIP_ATASAN_MUTASI = '".$userMobile->ID."' AND A.APPROVAL_DATE IS NULL AND COALESCE(NULLIF(A.USER_ATASAN_ID, ''), NULL) IS NOT NULL
                    AND TERPARAF IS NULL
                    -- TAMBAHAN ONE TES
                    AND A.USER_ID IS NOT NULL
                    --AND CASE WHEN A.STATUS_SURAT = 'PEMBUAT' THEN A.USER_ATASAN_ID = A.USER_ID END
                )
            ) 
            OR 
            (
                (
                    A.USER_ATASAN_ID = '".$userMobile->USER_GROUP.$userMobile->ID."' AND A.APPROVAL_DATE IS NOT NULL AND COALESCE(NULLIF(A.NIP_ATASAN_MUTASI, ''), NULL) IS NULL
                )
                OR 
                (
                    A.NIP_ATASAN_MUTASI = '".$userMobile->USER_GROUP.$userMobile->ID."' AND A.APPROVAL_DATE IS NOT NULL AND COALESCE(NULLIF(A.USER_ATASAN_ID, ''), NULL) IS NOT NULL
                )
            )
            OR 
            (
                A.USER_ID = '".$userMobile->ID."'
                AND CASE WHEN A.USER_ID = '".$userMobile->ID."' THEN TERPARAF IS NOT NULL ELSE TERPARAF IS NULL END
                AND A.STATUS_SURAT = 'PEMBUAT'
            )
            OR 
            (
                A.USER_ID = '".$userMobile->ID."'
                AND CASE WHEN A.USER_ID = '".$userMobile->ID."' THEN TERPARAF IS NULL ELSE TERPARAF IS NOT NULL END
                AND A.STATUS_SURAT != 'PEMBUAT'
            )
        ) AND A.STATUS_SURAT IN ('VALIDASI', 'PARAF', 'PEMBUAT')";

        $surat_masuk = new SuratMasuk();
        // $jumlah_validasi = $surat_masuk->getCountJumlahValidasi($userMobile->ID, $userMobile->USER_GROUP)->row()->jumlah;
        $jumlah_validasi = $surat_masuk->getCountByParamsNewPersetujuan(array(), $userMobile->ID, $userMobile->USER_GROUP, $statement, "", $infogantijabatan);
        // echo $surat_masuk->query; exit;

        $result["JUMLAH_VALIDASI"] = $jumlah_validasi;
        $result["JUMLAH_BADGE_VALIDASI"]    = $jumlah_validasi;

        $surat_masuk_semua = new SuratMasuk();
        
        // $surat_masuk_semua->selectByParamsNewDataJumlahSurat($userMobile->ID, $userMobile->USER_GROUP, $userMobile->CABANG_ID, "", $userMobile->KELOMPOK_JABATAN, $infogantijabatan);
        // echo $surat_masuk_semua->query; exit;

        // $JUMLAH_KOTAK_MASUK_SEMUA = 0;
        // while($surat_masuk_semua->nextRow())
        // {
        //     $JUMLAH_KOTAK_MASUK_SEMUA++;
        // }

        $JUMLAH_KOTAK_MASUK_SEMUA = $surat_masuk_semua->selectByParamsValNewDataJumlahSurat($userMobile->ID, $userMobile->USER_GROUP, $userMobile->CABANG_ID, "", $userMobile->KELOMPOK_JABATAN, $infogantijabatan);

        $result["JUMLAH_SURAT_MASUK"] = $JUMLAH_KOTAK_MASUK_SEMUA;
        $result["JUMLAH_BADGE_SURAT_MASUK"] = $JUMLAH_KOTAK_MASUK_SEMUA;
        
        
        $result["JUMLAH_DELEGASI"] = '0';        
        

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