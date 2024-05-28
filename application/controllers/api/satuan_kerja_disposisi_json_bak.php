<?php
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class satuan_kerja_disposisi_json extends REST_Controller {
 
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
        
        if($reqUnitKerjaId == ""){
            $reqUnitKerjaId = $userMobile->CABANG_ID;
        }
        
        $reqTujuan = $this->input->get("reqTujuan");
        
        if($reqTujuan == ""){
            $reqTujuan = $userMobile->SATUAN_KERJA_ID_ASAL;
        }
        
        $this->load->model("SatuanKerja");
        $satuan_kerja = new SatuanKerja();
        $this->load->model("Pegawai");
        $pegawai = new Pegawai();


        $i = 0;
        $arr_json = array();

        $pegawai->selectByParamsMonitoring(array("A.DEPARTEMEN_ID" => $reqTujuan), -1, -1, " AND NOT EXISTS(SELECT 1 FROM SATUAN_KERJA X WHERE X.NIP = A.NIP) ");
        // echo $pegawai->query;exit;
        while($pegawai->nextRow())
        {
            
            
            $arr_json[$i]['id']                 = "PEGAWAI".$pegawai->getField("PEGAWAI_ID");
            $arr_json[$i]['text']               = $pegawai->getField("JABATAN")." - ".$pegawai->getField("NAMA");
            $arr_json[$i]['SATUAN_KERJA_ID']    = $pegawai->getField("DEPARTEMEN_ID");
            $arr_json[$i]['SATUAN_KERJA']       = $pegawai->getField("DEPARTEMEN");
            $arr_json[$i]['NAMA']               = $pegawai->getField("DEPARTEMEN");
            $arr_json[$i]['NAMA_PEGAWAI']       = $pegawai->getField("NAMA");
            $arr_json[$i]['NIP']                = $pegawai->getField("NIP");
            
            $i++;
        }
        
        $arrStatement = array("COALESCE(NULLIF(KODE_PARENT, ''), '0')" => $reqTujuan, "NOT SATUAN_KERJA_ID_PARENT" => "SATKER", "SATUAN_KERJA_ID_PARENT" => $reqUnitKerjaId);
        
        $satuan_kerja->selectByParams($arrStatement, -1, -1, $statement.$statement_privacy, " ORDER BY KODE_SO ASC ");
        // echo $satuan_kerja->query;exit;
        while($satuan_kerja->nextRow())
        {
            $arr_json[$i]['id']                 = $satuan_kerja->getField("KODE_SO");
            $arr_json[$i]['text']               = coalesce($satuan_kerja->getField("JABATAN"), $satuan_kerja->getField("NAMA"))." - ".$satuan_kerja->getField("NAMA_PEGAWAI");
            $arr_json[$i]['SATUAN_KERJA_ID']    = $satuan_kerja->getField("SATUAN_KERJA_ID");
            $arr_json[$i]['SATUAN_KERJA']       = $satuan_kerja->getField("NAMA");
            $arr_json[$i]['NAMA']               = $satuan_kerja->getField("NAMA");
            $arr_json[$i]['NAMA_PEGAWAI']       = $satuan_kerja->getField("NAMA_PEGAWAI");
            $arr_json[$i]['NIP']                = $satuan_kerja->getField("NIP");
            
            // $arr_json[$i]['children']         = $this->disposisi_children($satuan_kerja->getField("KODE_SO"), $satuan_kerja->getField("SATUAN_KERJA_ID_PARENT"));
            
            $i++;
        }
        
        $result = $arr_json;

        $this->response(array('status' => 'success', 'message' => 'success', 'code' => 200, 'count' => count($aColumns) ,'result' => $result));
    }

    

    function disposisi_children($id, $satkerId)
    {

        $this->load->model("SatuanKerja");
        $satuan_kerja = new SatuanKerja();
        $this->load->model("Pegawai");
        $pegawai = new Pegawai();


        $i = 0;
        $arr_json = array();

        $pegawai->selectByParamsMonitoring(array("A.DEPARTEMEN_ID" => $id), -1, -1, " AND NOT EXISTS(SELECT 1 FROM SATUAN_KERJA X WHERE X.NIP = A.NIP) ");
        while($pegawai->nextRow())
        {
            
            
            $arr_json[$i]['id']                 = "PEGAWAI".$pegawai->getField("PEGAWAI_ID");
            $arr_json[$i]['text']               = $pegawai->getField("JABATAN")." - ".$pegawai->getField("NAMA");
            $arr_json[$i]['SATUAN_KERJA_ID']    = $pegawai->getField("DEPARTEMEN_ID");
            $arr_json[$i]['SATUAN_KERJA']       = $pegawai->getField("DEPARTEMEN");
            $arr_json[$i]['NAMA']               = $pegawai->getField("DEPARTEMEN");
            $arr_json[$i]['NAMA_PEGAWAI']       = $pegawai->getField("NAMA");
            $arr_json[$i]['NIP']                = $pegawai->getField("NIP");
            
            $i++;
        }



        $arrStatement = array("COALESCE(NULLIF(KODE_PARENT, ''), '0')" => $id, "NOT SATUAN_KERJA_ID_PARENT" => "SATKER", "SATUAN_KERJA_ID_PARENT" => $satkerId);
            
        $satuan_kerja->selectByParams($arrStatement, -1, -1, $statement, " ORDER BY KODE_SO ASC ");
        //echo $satuan_kerja->query;exit;
        while($satuan_kerja->nextRow())
        {
            
            $arr_json[$i]['id']                 = $satuan_kerja->getField("KODE_SO");
            $arr_json[$i]['text']               = coalesce($satuan_kerja->getField("JABATAN"), $satuan_kerja->getField("NAMA"))." - ".$satuan_kerja->getField("NAMA_PEGAWAI");
            $arr_json[$i]['SATUAN_KERJA_ID']    = $satuan_kerja->getField("SATUAN_KERJA_ID");
            $arr_json[$i]['SATUAN_KERJA']       = $satuan_kerja->getField("NAMA");
            $arr_json[$i]['NAMA']               = $satuan_kerja->getField("NAMA");
            $arr_json[$i]['NAMA_PEGAWAI']       = $satuan_kerja->getField("NAMA_PEGAWAI");
            $arr_json[$i]['NIP']                = $satuan_kerja->getField("NIP");
            $arr_json[$i]['children']           = $this->disposisi_children($satuan_kerja->getField("KODE_SO"), $satuan_kerja->getField("SATUAN_KERJA_ID_PARENT"));
            
            $i++;
        }
        
        return $arr_json;
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