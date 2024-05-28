<?php
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class pegawai_json extends REST_Controller {
 
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
        
        if($reqPegawaiId === 0){
            $this->response(array('status' => 'fail', 'message' => 'Sesi anda telah berakhir', 'code' => 502));
            return; 
        }


        $result = $this->db->query(" SELECT pegawai_id, nip, nama, email, jabatan, satuan_kerja_id, departemen_id, 
                               departemen_parent_id
                          FROM link.pegawai_integrasi order by satuan_kerja_id, departemen_id ")->result_array();

        $this->response(array('status' => 'success', 'message' => 'success', 'code' => 200, 'result' => $result));
    }
    
    // insert new data to entitas
    function index_post() {
        
        /* AMBIL TOKEN */
        $this->load->model('UserLoginMobile');
        $user_login_mobile = new UserLoginMobile();
        $reqToken = $this->input->post("reqToken");
        $reqPegawaiId = $user_login_mobile->getTokenPegawaiId(array("TOKEN" => $reqToken, "STATUS" => '1'));
        
        if($reqPegawaiId === 0){
            $this->response(array('status' => 'fail', 'message' => 'Sesi anda telah berakhir', 'code' => 502));
            return; 
        }


        $result = $this->db->query(" SELECT pegawai_id, nip, nama, email, jabatan, satuan_kerja_id, departemen_id, 
                               departemen_parent_id
                          FROM link.pegawai_integrasi order by satuan_kerja_id, departemen_id ")->result_array();

        $this->response(array('status' => 'success', 'message' => 'success', 'code' => 200, 'result' => $result));

    }
 
    // update data entitas
    function index_put() {
        
    }
 
    // delete entitas
    function index_delete() {
        
    }
 
}