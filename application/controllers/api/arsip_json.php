<?php
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class arsip_json extends REST_Controller {
 
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

        $reqSatuanKerjaIdAsal = $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_ID_ASAL;


        $this->load->model("JenisNaskah");
        $jenis_naskah = new JenisNaskah();

        $reqPencarian = $this->input->get("reqPencarian");
        $reqMode = $this->input->get("reqMode");
        
        
        $this->load->model("Arsip");
        $arsip = new Arsip();

        if($reqPencarian == "")
        {}
        else
            $statement = " AND UPPER(A.NAMA) LIKE '%".strtoupper($reqPencarian)."%' ";
        
        $statement_privacy = "";
        
        if($this->USER_GROUP == "TATAUSAHA")
            $statement_privacy .= " AND A.SATUAN_KERJA_ID = '".$userMobile->CABANG_ID."' ";
        else
            $statement_privacy .= " AND A.SATUAN_KERJA_ID = '".$reqSatuanKerjaIdAsal."' ";
            
        
        $rowCount = $arsip->getCountByParams(array(), $statement.$statement_privacy);
        $arsip->selectByParams(array(), $rows, $offset, $statement.$statement_privacy);
        $i = 0;
        $items = array();
        while($arsip->nextRow())
        {
            $row['id']      = $arsip->getField("ARSIP_ID");
            $row['text']    = $arsip->getField("NAMA");
            $row['ARSIP_ID']    = $arsip->getField("ARSIP_ID");
            $row['NAMA']    = $arsip->getField("NAMA");
            $row['CABANG']  = $arsip->getField("SATUAN_KERJA");
            $row['JABATAN'] = $arsip->getField("JABATAN");
            $row['KETERANGAN']  = $arsip->getField("KETERANGAN");
            $row["KLASIFIKASI"] = $arsip->getField("KLASIFIKASI_KODE")." - ".$arsip->getField("NAMA");
            $row["BERKAS"]  = $arsip->getField("KLASIFIKASI_KODE")."/".$arsip->getField("KODE")." - ".$arsip->getField("NAMA");
            $row["KODE"]                = $arsip->getField("KODE");
            $row['state'] = 'close';
            $i++;
            array_push($items, $row);
        }
        $result["rows"] = $items;
        $result["total"] = $rowCount;
            
        $result[] = $row;


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