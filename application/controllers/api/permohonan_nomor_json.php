<?php
error_reporting(1);
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class permohonan_nomor_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();
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
				
        $this->load->model("PermohonanNomor");
        $permohonan_nomor = new PermohonanNomor();

        $aColumns = array("PERMOHONAN_NOMOR_ID","CABANG_ID","PERUNTUKAN","KETERANGAN","TANGGAL_SURAT","TIPE_NASKAH","JENIS_NASKAH_ID",
            "JENIS_NASKAH","KD_LEVEL","SATUAN_KERJA_ID","SATUAN_KERJA","SURAT_NOMOR","SURAT_NOMOR_FIX","TIPE_NASKAH_KET","SURAT_MASUK_ID","SURAT_KELUAR_ID");
        $result = array();

        $statement_privacy .= " AND (LAST_CREATE_USER = '".$userMobile->ID."' OR SATUAN_KERJA_ID = '".$userMobile->SATUAN_KERJA_ID_ASAL."' ) ";

        $sOrder=" ORDER BY TANGGAL_ENTRI::TIMESTAMP DESC ";

		$permohonan_nomor->selectByParams(array(), -1, -1, $statement_privacy.$statement, $sOrder); 
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
            
            $result[] = $row;

        }
        
        $this->response(array('status' => 'success', 'message' => 'success', 'code' => 200, 'count' => count($aColumns) ,'result' => $result));
    }

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
        $this->load->model("JenisNaskah");
        $permohonan_nomor = new PermohonanNomor();
        $jenis_naskah = new JenisNaskah();

        $reqMode             = $this->input->post("reqMode");   //khavid
        $reqId               = $this->input->post("reqId");     //khavid
       
        $reqCabangId         = $this->input->post("reqCabangId");
        $reqPeruntukan       = $this->input->post("reqPeruntukan"); //khavid
        $reqKeterangan       = $this->input->post("reqKeterangan"); //khavid
        $reqTanggalSurat     = $this->input->post("reqTanggalSurat"); //khavid
        $reqTipeNaskah       = $this->input->post("reqTipeNaskah"); //khavid
        $reqSuratMasukId     = $this->input->post("reqSuratMasukId");
        $reqSuratKeluarId    = $this->input->post("reqSuratKeluarId");
        $reqJenisNaskahId    = $this->input->post("reqJenisNaskahId");  //khavid
        $reqJenisNaskah      = $this->input->post("reqJenisNaskah"); //khavid
        $reqSatuanKerjaId    = $this->input->post("reqSatuanKerjaId");  //khavid
        $reqSatuanKerja      = $this->input->post("reqSatuanKerja"); //khavid

        $jenis_naskah->selectByParams(array("JENIS_NASKAH_ID" => $reqJenisNaskahId));
        $jenis_naskah->firstRow();

        $reqKdLevel = $jenis_naskah->getField("KD_LEVEL");

        $permohonan_nomor->setField("PERMOHONAN_NOMOR_ID", $reqId);
        $permohonan_nomor->setField("CABANG_ID", $userMobile->CABANG_ID);
        $permohonan_nomor->setField("SATUAN_KERJA_ID", $reqSatuanKerjaId);
        $permohonan_nomor->setField("SATUAN_KERJA", $reqSatuanKerja);
        $permohonan_nomor->setField("JENIS_NASKAH_ID", $reqJenisNaskahId);
        $permohonan_nomor->setField("JENIS_NASKAH", $reqJenisNaskah);
        $permohonan_nomor->setField("KD_LEVEL", $reqKdLevel);
        $permohonan_nomor->setField("PERUNTUKAN", $reqPeruntukan);
        $permohonan_nomor->setField("KETERANGAN", $reqKeterangan);
        $permohonan_nomor->setField("TANGGAL_SURAT", dateToDbCheck($reqTanggalSurat));
        $permohonan_nomor->setField("TIPE_NASKAH", $reqTipeNaskah);
        $permohonan_nomor->setField("SURAT_MASUK_ID", "0");
        $permohonan_nomor->setField("SURAT_KELUAR_ID", "0");

        if($reqMode == "insert")
        {
            $permohonan_nomor->setField("LAST_CREATE_USER", $userMobile->USERNAME);
            $permohonan_nomor->setField("LAST_CREATED_DATE", "CURRENT_DATE");
            $permohonan_nomor->insert();
        }
        else
        {
            $permohonan_nomor->setField("LAST_UPDATE_USER", $userMobile->USERNAME);
            $permohonan_nomor->setField("LAST_UPDATED_DATE", "CURRENT_DATE");
            $permohonan_nomor->update();
        }

        $this->response(array('status' => 'success', 'code' => 200, 'message' => 'Data berhasil disimpan.'));
    }

}