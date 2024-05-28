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
		$arrData[$i]["SIFAT_NASKAH_ID"] = "Biasa";
		$arrData[$i]["NAMA"] = "Biasa";
        $arrData[$i]["label"] = "Biasa";
        $arrData[$i]["value"] = "Biasa";
		$i++;
		$arrData[$i]["SIFAT_NASKAH_ID"] = "Segera";
		$arrData[$i]["NAMA"] = "Segera";
        $arrData[$i]["label"] = "Segera";
        $arrData[$i]["value"] = "Segera";
		$i++;
		$arrData[$i]["SIFAT_NASKAH_ID"] = "Rahasia";
		$arrData[$i]["NAMA"] = "Rahasia";
        $arrData[$i]["label"] = "Rahasia";
        $arrData[$i]["value"] = "Rahasia";
		$i++;
		$arrData[$i]["SIFAT_NASKAH_ID"] = "Sangat Rahasia";
		$arrData[$i]["NAMA"] = "Sangat Rahasia";
        $arrData[$i]["label"] = "Sangat Rahasia";
        $arrData[$i]["value"] = "Sangat Rahasia";
		$i++;
		
        $arrResponse["children"] = $arrData; 
        $arrResponse["after"] = $reqShow + $reqPage;    
        if($jenis_naskah->rowCount > 0)
            $arrResponse["after"] = $reqShow + $reqPage;  
        else
            $arrResponse["after"] = 0;
            
        $this->response(array('status' => 'success', 'message' => 'success', 'code' => 200,'data' => $arrResponse));
       
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