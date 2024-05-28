<?php
 
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/default.func.php");
 
class pegawai_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();
    }
 
    // show data entitas
    function index_get() {

		
        /* VARIABEL WAJIB */
        $reqPage = $this->input->get("reqPage");
        $reqShow = $this->input->get("reqShow");
        $reqPage = !empty($reqPage)?$reqPage:0;
        $reqShow = 5000;
       
	   	/* AMBIL INFO LOGIN */
        $this->load->model('UserLoginMobile');
        $user_login_mobile = new UserLoginMobile();
        $reqToken = $this->input->get('reqToken');	
		$reqPegawaiId = $user_login_mobile->getTokenPegawaiId(array("TOKEN" => $reqToken, "STATUS" => '1'));
		
		if($reqPegawaiId == "")
		{
			$this->response(array('status' => 'fail', 'message' => 'Sesi anda telah berakahir', 'code' => 502));
			return;
		}
	   	/* END OF AMBIL INFO LOGIN */
		$this->load->library("usermobile"); $userMobile = new usermobile();
        $userMobile->getInfo($reqPegawaiId, $reqToken);

		$this->load->library("Pagination");
		$this->load->model("Pegawai");
		
		$pegawai = new Pegawai();
		
		$reqSearch = $this->input->get("reqSearch");	
		$reqStatus = $this->input->get("reqStatus");
		
		$statement .= " AND UPPER(A.NAMA) LIKE '%".strtoupper($reqSearch)."%' ";
		$statement .= " AND A.SATUAN_KERJA_ID = '".$userMobile->CABANG_ID."' ";
		$rowCount = $pegawai->getCountByParams(array(), $statement);
		$pegawai->selectByParams(array(), -1, -1, $statement); 
		// echo $pegawai->query; exit;
		
		$i = 0;
		$arrColumn = array("PEGAWAI_ID", "NIP", "NAMA", "JABATAN");
		$arrData = array();					
        while($pegawai->nextRow())
        {
			for($iCol=0;$iCol<count($arrColumn);$iCol++)
			{
				$arrData[$i][$arrColumn[$iCol]] = $pegawai->getField($arrColumn[$iCol]);
			}
            $i++;
        }
                 
        $arrResponse["children"] = $arrData; 
        $arrResponse["after"] = $reqShow + $reqPage;    
        if($pegawai->rowCount > 0)
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