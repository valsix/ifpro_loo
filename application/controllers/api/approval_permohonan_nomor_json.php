<?php
error_reporting(1);
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class approval_permohonan_nomor_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();

        $this->load->library('Kauth');

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

        $reqSatuanKerjaIdAsal = $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_ID_ASAL;
				
        $this->load->model("PermohonanNomor");
        $permohonan_nomor = new PermohonanNomor();
        $permohonan_nomor_baru = new PermohonanNomor();

        $aColumns    = array("PERMOHONAN_NOMOR_ID","PERUNTUKAN","KETERANGAN","TANGGAL_SURAT","TIPE_NASKAH","JENIS_NASKAH","SATUAN_KERJA","KD_LEVEL",
            "KETERANGAN","TANGGAL_SURAT","TIPE_NASKAH","JENIS_NASKAH","SATUAN_KERJA","KD_LEVEL","SURAT_NOMOR","SURAT_NOMOR_FIX");
        $result = array();

        $statement_privacy .= " AND B.PENERBIT_NOMOR = '".$userMobile->USER_GROUP."' ";
        if($userMobile->USER_GROUP == "SEKRETARIS"){
            $statement_privacy .= " AND (SATUAN_KERJA_ID = '".$reqSatuanKerjaIdAsal."') ";
        }
        else{
            $statement_privacy .= " AND (CABANG_ID = '".$userMobile->CABANG_ID."') ";
        }
        
        $statement_privacy .= " AND SURAT_NOMOR IS NULL ";
        
        $permohonan_nomor->selectByParamsVerifikasi(array(),-1,-1,$statement_privacy.$statement, " ORDER BY PERMOHONAN_NOMOR_ID DESC"); 
        // echo $permohonan_nomor->query;exit;
        while($permohonan_nomor->nextRow())
        {
            for($i=0;$i<count($aColumns);$i++)
            {
                if($aColumns[$i] == "TANGGAL_SURAT"){
                    $row[trim($aColumns[$i])] = getFormattedDate($permohonan_nomor->getField(trim($aColumns[$i]))); 
                }
                elseif($aColumns[$i] == "TERBACA")
                {
                    if($permohonan_nomor->getField(trim($aColumns[$i])) == 1){
                        $row[trim($aColumns[$i])] = "1";  
                    }
                    else{
                        $row[trim($aColumns[$i])] = "0";              
                    }
                }
                elseif($aColumns[$i] == "ISI"){
                    $row[trim($aColumns[$i])] = strip_tags($permohonan_nomor->getField(trim($aColumns[$i]))); 
                }
                elseif($aColumns[$i] == "FOTO"){
                    $row["FOTO"] = generateFotoMobile("X", $permohonan_nomor->getField("NAMA_USER_ASAL"));
                }
                else{
                    $row[trim($aColumns[$i])] = $permohonan_nomor->getField(trim($aColumns[$i]));
                }
            }
            
            $permohonan_nomor_baru->selectByParamsApproval(array("A.PERMOHONAN_NOMOR_ID" => $permohonan_nomor->getField("PERMOHONAN_NOMOR_ID")), -1, -1, $statement_privacy." AND SURAT_NOMOR IS NULL ");
            // echo $permohonan_nomor_baru->query;exit;
            $permohonan_nomor_baru->firstRow();

            $reqNomor           = $permohonan_nomor_baru->getField("NOMOR_BARU");
            $arrNomor           = explode("[]", $reqNomor);
            $row["NOMOR_SURAT"]  = $arrNomor[0];
            
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
        
        $this->load->model("PermohonanNomor");
        $permohonan_nomor = new PermohonanNomor();

        $reqMode                    = $this->input->post("reqMode");
        $reqId                      = $this->input->post("reqId");
        $reqNomorSurat              = $this->input->post("reqNomorSurat");

        if($reqMode == "update")
        {
            $permohonan_nomor->setField("PERMOHONAN_NOMOR_ID", $reqId);
            $permohonan_nomor->setField("SURAT_NOMOR", $reqNomorSurat);
            $permohonan_nomor->setField("LAST_APPROVE_USER", $userMobile->USERNAME);
            $permohonan_nomor->setField("LAST_APPROVE_DATE", "CURRENT_DATE");
            $statement_privacy .= " AND B.PENERBIT_NOMOR = '".$userMobile->USER_GROUP."' ";

            if($userMobile->USER_GROUP == "SEKRETARIS")
                $statement_privacy .= " AND (SATUAN_KERJA_ID = '".$userMobile->SATUAN_KERJA_ID_ASAL."') ";
            else
                $statement_privacy .= " AND (CABANG_ID = '".$userMobile->CABANG_ID."') ";
                
            $permohonan_nomor->approval($statement);

            $this->response(array('status' => 'success', 'code' => 200, 'message' => 'Berhasil'));
        }
    }

}