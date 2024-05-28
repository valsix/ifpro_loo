<?php
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class surat_masuk_trace_json extends REST_Controller {
 
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

        if (empty($userMobile->KELOMPOK_JABATAN)) 
            $reqKelompokJabatan = "KARYAWAN";
        else
          $reqKelompokJabatan = $userMobile->KELOMPOK_JABATAN;
                
        $this->load->model("SuratMasuk");
        $this->load->model("Disposisi");
        $surat_masuk = new SuratMasuk();
        $disposisi = new Disposisi();

        $reqPencarian = $this->input->get("reqPencarian");

        $aColumns = array("SURAT_MASUK_ID","DISPOSISI_ID","TERBACA","FOTO","NAMA_USER_ASAL","SATUAN_KERJA_ID_ASAL","NAMA_SATKER_ASAL","ALAMAT_ASAL",
            "JENIS_NASKAH","SIFAT_NASKAH","NOMOR","TANGGAL_ENTRI","PERIHAL","ISI");

        $result = array();

        // $statement_privacy .= " AND (A.STATUS_SURAT = 'POSTING' OR (
        //     A.STATUS_SURAT = 'TU-IN' AND EXISTS(SELECT 1 FROM SURAT_MASUK_ARSIP X WHERE X.SURAT_MASUK_ID = A.SURAT_MASUK_ID 
        //     AND X.CABANG_ID = '".$userMobile->CABANG_ID."'))
        // )";

        $statement_privacy .= " AND B.DISPOSISI_PARENT_ID = 0
        AND 
        (
            A.STATUS_SURAT = 'POSTING' OR
            A.STATUS_SURAT = 'TU-NOMOR' OR
            (
                A.STATUS_SURAT = 'TU-IN' AND
                EXISTS(SELECT 1 FROM SURAT_MASUK_ARSIP X WHERE X.SURAT_MASUK_ID = A.SURAT_MASUK_ID AND X.CABANG_ID = '".$userMobile->CABANG_ID."')
            )
        )";

        // if($userMobile->KD_LEVEL_PEJABAT == ""){
        //     $statement_privacy .= " AND (B.USER_ID = '".$userMobile->ID."' ) ";
        // }
        // else{
        //     $statement_privacy .= " AND (B.SATUAN_KERJA_ID_TUJUAN = '".$userMobile->SATUAN_KERJA_ID_ASAL."' OR B.USER_ID = '".$userMobile->ID."' OR B.USER_ID_OBSERVER = '".$userMobile->ID."') ";
        // }

        if($userMobile->KD_LEVEL_PEJABAT == "")
        {
            // $statement.= " AND (B.USER_ID = '".$this->ID."' OR B.USER_ID = '".$this->ID_ATASAN."' ) ";
            $statement_privacy.= " AND 
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
                ";

                // if($userMobile->KELOMPOK_JABATAN == "KARYAWAN"){}
                // else
                // {
                    $statement_privacy.= " AND B.SATUAN_KERJA_ID_TUJUAN = '".$userMobile->CABANG_ID."' ";
                // }
            $statement_privacy.= "
                )
            ) ";
        }
        else
        {
            $statement_privacy.= " AND (B.SATUAN_KERJA_ID_TUJUAN = '".$userMobile->SATUAN_KERJA_ID_ASAL."' OR B.USER_ID = '".$userMobile->ID."' OR B.USER_ID_OBSERVER = '".$userMobile->ID."') ";
        }

        $statement = "
            AND 
            (
                UPPER(A.NO_AGENDA) LIKE '%".strtoupper($reqPencarian)."%' OR 
                UPPER(A.NOMOR) LIKE '%".strtoupper($reqPencarian)."%' OR
                UPPER(A.PERIHAL) LIKE '%".strtoupper($reqPencarian)."%' OR 
                UPPER(A.INSTANSI_ASAL) LIKE '%".strtoupper($reqPencarian)."%'
            )
            ";

        // $surat_masuk->selectByParamsInbox(array(), -1, -1, $statement_privacy.$statement," ORDER BY A.SURAT_MASUK_ID DESC, B.TERBACA DESC, TANGGAL_ENTRI DESC"); 

        $surat_masuk->selectByParamsSuratMasuk(array(), -1, -1, $statement_privacy.$statement," ORDER BY B.TANGGAL_DISPOSISI DESC"); 
        // echo $surat_masuk->query;exit;


        while($surat_masuk->nextRow())
        {
            for($i=0;$i<count($aColumns);$i++)
            {
                if($aColumns[$i] == "TANGGAL_ENTRI"){
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

            $jumlahDisposisi = $disposisi->getCountByParams(array("SURAT_MASUK_ID" => $surat_masuk->getField("SURAT_MASUK_ID"), "SATUAN_KERJA_ID_ASAL" => $userMobile->SATUAN_KERJA_ID_ASAL, "STATUS_DISPOSISI" => "DISPOSISI"));

            if($jumlahDisposisi > 0){
                $row["STATUS_DISPOSISI"] = "1";
            }
            else{
                $row["STATUS_DISPOSISI"] = "0";
            }

            
            $result[] = $row;

        }
        

        $this->response(array('status' => 'success', 'message' => 'success', 'code' => 200, 'result' => $result));
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
        
        $this->load->model('Disposisi');
        $disposisi = new Disposisi();

        $reqMode = $this->input->post("reqMode");
        // echo $reqMode;exit();
        $reqSuratMasukId = $this->input->post("reqSuratMasukId");
        $reqDisposisiId = $this->input->post("reqDisposisiId");

        if($reqMode == "BACA")
        {
            $disposisi->setField("FIELD", "TERBACA");
            $disposisi->setField("FIELD_VALUE", "1");
            $disposisi->setField("LAST_UPDATE_USER", $userMobile->ID);
            $disposisi->setField("SURAT_MASUK_ID", $reqSuratMasukId);
            $disposisi->setField("DISPOSISI_ID", $reqDisposisiId);
            $disposisi->setField("USER_ID", $userMobile->ID);
            $disposisi->updateByFieldValidasiUserBaca();

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