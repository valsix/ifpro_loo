<?php
 
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/default.func.php");
 
class balas_cepat_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();
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



        $reqPencarian = $this->input->get("reqPencarian");
        $result = array();

        $this->load->model("BalasCepat");
        $balas_cepat = new BalasCepat();

        if($reqPencarian == "")
        {}
        else{
            $statement = " AND (UPPER(NAMA) LIKE '%".strtoupper($reqPencarian)."%') ";
        }

        $i = 0;
        $balas_cepat->selectByParamsDistinct(array(), -1, -1, $statement);
        while($balas_cepat->nextRow())
        {
            $arr_json[$i]['id'] = $balas_cepat->getField("NAMA");
            $arr_json[$i]['text']   = $balas_cepat->getField("NAMA");
            $i++;
        }

        $result = $arr_json;

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