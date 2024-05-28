<?php
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class surat_masuk_log_json extends REST_Controller {
 
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
		
        $this->load->model("Disposisi");
        $disposisi   = new Disposisi();

        $reqId = $this->input->get("reqId");
        $reqDisposisiId = $this->input->get("reqDisposisiId");

        $aColumns = array("SURAT_MASUK_ID","DISPOSISI_ID","STATUS_DISPOSISI","NAMA_USER","NAMA_SATKER",
            "NAMA_USER_ASAL","NAMA_SATKER_ASAL","TANGGAL_DISPOSISI","ISI","KETERANGAN","TERBACA","TERDISPOSISI","TERBALAS","TERUSKAN");

        $result = array();

		$disposisi->selectByParams(array("A.SURAT_MASUK_ID" => (int)$reqId),-1,-1,$statement," ORDER BY DISPOSISI_ID ASC");
        // echo $disposisi->query;exit;
		while($disposisi->nextRow())
		{
            for($i=0;$i<count($aColumns);$i++ )
            {
                if($aColumns[$i] == "TANGGAL_DISPOSISI"){
                    $row[trim($aColumns[$i])] = getFormattedDate($disposisi->getField(trim($aColumns[$i]))); 
                }
                elseif($aColumns[$i] == "TERBACA" || $aColumns[$i] == "TERDISPOSISI" || $aColumns[$i] == "TERBALAS" || $aColumns[$i] == "TERUSKAN")
                {
                    if($disposisi->getField(trim($aColumns[$i])) == 1){
                        $row[trim($aColumns[$i])] = "1";  
                    }
                    else{
                        $row[trim($aColumns[$i])] = "0";              
                    }
                }
                else{
                    $row[trim($aColumns[$i])] = $disposisi->getField(trim($aColumns[$i]));
                }
            }

        $result[] = $row;
        
        }
        
        $this->response(array('status' => 'success', 'message' => 'success', 'code' => 200, 'count' => count($aColumns) ,'result' => $result));
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