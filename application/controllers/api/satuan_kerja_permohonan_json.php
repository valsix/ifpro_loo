<?php
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class satuan_kerja_permohonan_json extends REST_Controller {
 
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

        $reqId = $this->input->get("reqId");
        // $reqPenerbit = $this->input->get("reqPenerbit");
        

        $this->load->model("JenisNaskah");
        $this->load->model("SatuanKerja");
        $jenis_naskah = new JenisNaskah();
        $satuan_kerja = new SatuanKerja();

        $jenis_naskah->selectByParams(array("JENIS_NASKAH_ID" => $reqId));
        $jenis_naskah->firstRow();

        $reqKodeLevel = $jenis_naskah->getField("KD_LEVEL");
        $reqPenerbit = $jenis_naskah->getField("PENERBIT_NOMOR");
        
        $i = 0;
        $arr_json = array();
        
        $satuan_kerja->selectByParamsHirarkiJabatan($userMobile->SATUAN_KERJA_ID_ASAL, 
            " AND EXISTS(SELECT 1 FROM (SELECT TRIM(UPPER(regexp_split_to_table('".$reqKodeLevel."', ','))) AS KD_LEVEL) X 
            WHERE X.KD_LEVEL = A.KELOMPOK_JABATAN)");
        // echo $satuan_kerja->query;exit;
        while($satuan_kerja->nextRow())
        {
            $arr_json[$i]['id']                 = $satuan_kerja->getField("SATUAN_KERJA_ID");

            if($satuan_kerja->getField("KODE_SURAT") == ""){
                $arr_json[$i]['text']           = $satuan_kerja->getField("NAMA");
            }
            else{
                $arr_json[$i]['text']           = $satuan_kerja->getField("NAMA")." (".$satuan_kerja->getField("KODE_SURAT").")";
            }
            
            $i++;
        }

        $result = $arr_json;

        $this->response(array('status' => 'success', 'message' => 'success', 'code' => 200,'result' => $result));
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