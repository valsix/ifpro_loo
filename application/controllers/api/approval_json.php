<?php
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class approval_json extends REST_Controller {
 
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

        $reqToken        = $this->input->get("reqToken");
        $reqStatusSurat  = $this->input->get("reqStatusSurat");
        $reqTanggalAwal  = $this->input->get("reqTanggalAwal");
        $reqTanggalAkhir = $this->input->get("reqTanggalAkhir");
        $reqSearch       = $this->input->get("reqSearch");
        
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
        $this->load->model("Disposisi");
        $surat_masuk = new SuratMasuk();
        $disposisi   = new Disposisi();

        $aColumns = array("SURAT_MASUK_ID","TERBACA_VALIDASI","JENIS_SURAT","FOTO","KEPADA",
            "JENIS_NASKAH","SIFAT_NASKAH","PRIORITAS_SURAT","TANGGAL_ENTRI","PERIHAL","ISI");


        $result = array();

        // $statement_privacy .= " AND ((A.USER_ATASAN_ID = '".$userMobile->ID."' AND  A.APPROVAL_DATE IS NULL) OR (A.USER_ATASAN_ID = '".$reqUserGroup.$userMobile->ID."' AND A.APPROVAL_DATE IS NOT NULL )) ";
        
        // $statement_privacy .= " AND A.STATUS_SURAT IN ('VALIDASI', 'PARAF') ";

        // $surat_masuk->selectByParamsMonitoringApproval(array(), -1, -1, $statement_privacy.$statement," ORDER BY TANGGAL_ENTRI::TIMESTAMP DESC"); 
        // echo $surat_masuk->query;exit;


        $searchJson= " 
                        AND 
                        (
                            UPPER(A.PERIHAL) LIKE '%" . strtoupper($reqSearch) . "%' OR
                            UPPER(A.PERIHAL) LIKE '%" . strtoupper($reqSearch) . "%'
                        )
                    ";

        if (empty($reqStatusSurat))
            $reqStatusSurat = "PERLU_PERSETUJUAN";


        if($reqStatusSurat == "PERLU_PERSETUJUAN")
        {
            $statement= " 
            AND 
            (
                (
                    (
                        A.USER_ATASAN_ID = '".$userMobile->ID."' AND A.APPROVAL_DATE IS NULL AND COALESCE(NULLIF(A.NIP_ATASAN_MUTASI, ''), NULL) IS NULL
                        AND TERPARAF IS NULL
                    )
                    OR 
                    (
                        A.NIP_ATASAN_MUTASI = '".$userMobile->ID."' AND A.APPROVAL_DATE IS NULL AND COALESCE(NULLIF(A.USER_ATASAN_ID, ''), NULL) IS NOT NULL
                        AND TERPARAF IS NULL
                        AND A.USER_ID IS NOT NULL
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
            ) AND A.STATUS_SURAT IN ('VALIDASI', 'PARAF', 'PEMBUAT')
            ";

            $satuankerjaganti= "";
            if($userMobile->SATUAN_KERJA_ID_ASAL_ASLI == $userMobile->SATUAN_KERJA_ID_ASAL)
            {}
            else
            {
                $satuankerjaganti= $userMobile->SATUAN_KERJA_ID_ASAL;
            }
            $satuankerjaganti= $userMobile->SATUAN_KERJA_ID_ASAL;


            if (!empty($reqTanggalAwal)) 
            {
                if(empty($reqTanggalAkhir))
                    $reqTanggalAkhir = $reqTanggalAwal;


                $statement .= "AND A.TANGGAL_ENTRI BETWEEN TO_TIMESTAMP('$reqTanggalAwal 00:00', 'DD-MM-YYYY HH24:MI') AND TO_TIMESTAMP('$reqTanggalAkhir 00:00', 'DD-MM-YYYY HH24:MI')";
            }


            $sOrder = " ORDER BY INFO_STATUS_TANGGAL DESC";


            $surat_masuk->selectByParamsNewPersetujuan(array(), -1, -1, $userMobile->ID, $userMobile->USER_GROUP, $statement.$searchJson, $sOrder, "", $satuankerjaganti);
            
        }
        elseif($reqStatusSurat == "AKAN_DISETUJUI")
        {
            $statement= " 
                    AND SM_INFO IN ('AKAN_DISETUJUI', 'NEXT_DISETUJUI') 
                    AND CASE WHEN A.TERPARAF = 1 AND A.JENIS_NASKAH_ID IN (8,17,18,19,20) THEN A.STATUS_SURAT IN ('PARAF', 'VALIDASI', 'PEMBUAT') ELSE A.STATUS_SURAT IN ('PARAF', 'VALIDASI') END
                    ";


            $satuankerjaganti= "";
            if($userMobile->SATUAN_KERJA_ID_ASAL_ASLI == $userMobile->SATUAN_KERJA_ID_ASAL)
            {}
            else
            {
                $satuankerjaganti= $userMobile->SATUAN_KERJA_ID_ASAL;
            }
            $infostatus= "1";
            $satuankerjaganti= $userMobile->SATUAN_KERJA_ID_ASAL;


            if (!empty($reqTanggalAwal)) 
            {
                if(empty($reqTanggalAkhir))
                    $reqTanggalAkhir = $reqTanggalAwal;


                $statement .= "AND A.TANGGAL_ENTRI BETWEEN TO_TIMESTAMP('$reqTanggalAwal 00:00', 'DD-MM-YYYY HH24:MI') AND TO_TIMESTAMP('$reqTanggalAkhir 00:00', 'DD-MM-YYYY HH24:MI')";
            }


            $sOrder = " ORDER BY INFO_STATUS_TANGGAL DESC";

            $surat_masuk->selectByParamsStatusTujuan(array(), -1, -1, $userMobile->ID, $infostatus, $satuankerjaganti, $statement.$searchJson, $sOrder);

        }

        // echo $surat_masuk->query;exit;

        while($surat_masuk->nextRow())
        {
            for($i=0;$i<count($aColumns);$i++)
            {
                if($aColumns[$i] == "TANGGAL_ENTRI"){
                    $row[trim($aColumns[$i])] = getFormattedDate($surat_masuk->getField(trim($aColumns[$i]))); 
                }
                elseif($aColumns[$i] == "TERBACA_VALIDASI")
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
                    $row["FOTO"] = generateFotoMobile("X", $surat_masuk->getField("KEPADA"));
                }
                else{
                    $row[trim($aColumns[$i])] = $surat_masuk->getField(trim($aColumns[$i]));
                }
            }
            
            $result[] = $row;

        }
        
        $this->response(array('status' => 'success', 'message' => 'success', 'code' => 200, 'count' => count($aColumns) ,'result' => $result));
    }
    
    // insert new data to entitas
    function index_post() {
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
        
        $this->load->model('SuratMasuk');
        $surat_masuk = new SuratMasuk();

        $reqMode = $this->input->post("reqMode");
        // echo $reqMode;exit();
        $reqSuratMasukId = $this->input->post("reqSuratMasukId");

        if($reqMode == "BACA")
        {
            $surat_masuk->setField("FIELD", "TERBACA_VALIDASI");
            $surat_masuk->setField("FIELD_VALUE", "1");
            $surat_masuk->setField("LAST_UPDATE_USER", $userMobile->ID);
            $surat_masuk->setField("SURAT_MASUK_ID", $reqSuratMasukId);
            $surat_masuk->updateByField();

            $this->response(array('status' => 'success', 'code' => 200, 'message' => 'Berhasil'));
        }
        
    }

    // update data entitas
    function index_put() {

    }
 
    // delete entitas
    function index_delete() {

    }
 
}