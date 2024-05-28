<?php
 
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/default.func.php");
 
class sifat_naskah_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();
    }
 
    // show data entitas
    function index_get() {
		

		$this->db->query("SET DATESTYLE TO PostgreSQL,European;"); 
		
        /* VARIABEL WAJIB */
        $reqPage = $this->input->get("reqPage");
        $reqShow = $this->input->get("reqShow");
        $reqPage = !empty($reqPage)?$reqPage:0;
        $reqShow = 10;


	   	/* AMBIL INFO LOGIN */
        $this->load->model('UserLoginMobile');
        $user_login_mobile = new UserLoginMobile();
        $reqToken = $this->input->get('reqToken');	
		$reqPegawaiId = $user_login_mobile->getTokenPegawaiId(array("TOKEN" => $reqToken, "STATUS" => '1'));

		/* CHECK SESION */
		if($reqPegawaiId === 0){
			$this->response(array('status' => 'fail', 'message' => 'Sesi Anda telah berakhir.', 'code' => 502));
		}
		
		$this->load->library("usermobile"); $userMobile = new usermobile();
        $userMobile->getInfo($reqPegawaiId, $reqToken);
	   	/* END OF AMBIL INFO LOGIN */

        $i=0;
        $arrData[$i]["id"] = "Biasa";
        $arrData[$i]["text"] = "Biasa";
		$i++;
        $arrData[$i]["id"] = "Rahasia";
        $arrData[$i]["text"] = "Rahasia";
		$i++;
        $arrData[$i]["id"] = "Segera";
        $arrData[$i]["text"] = "Segera";
		$i++;
        $arrData[$i]["id"] = "Sangat Segera";
        $arrData[$i]["text"] = "Sangat Segera";
		$i++;

        $result = $arrData;
            
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