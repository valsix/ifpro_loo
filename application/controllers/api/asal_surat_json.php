<?php
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class asal_surat_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();
        $this->methods['index_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['index_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['index_put']['limit'] = 50; // 50 requests per hour per user/key
    }
 
    // show data entitas
	function index_get() {
		
		
        $reqBantuMode = $this->input->get("reqBantuMode");



	   	/* AMBIL INFO LOGIN */
        $this->load->model('UserLoginMobile');
        $user_login_mobile = new UserLoginMobile();
        $reqToken = $this->input->get('reqToken');	
		$reqPegawaiId = $user_login_mobile->getTokenPegawaiId(array("TOKEN" => $reqToken, "STATUS" => '1'));
		
		$this->load->library("usermobile"); $userMobile = new usermobile();
        $userMobile->getInfo($reqPegawaiId, $reqToken);
	   	/* END OF AMBIL INFO LOGIN */
				
				
        if($reqPegawaiId <> "0")
        {
            $this->load->model("DaftarAlamat");
            $daftar_alamat = new DaftarAlamat();

            $searchJson = " AND (UPPER(INSTANSI) LIKE '%".strtoupper($_GET['sSearch'])."%')";
	        $daftar_alamat->selectByParams(array(), -1, -1, $searchJson);
            $result = array();
            $i = 0;
            $aColumns = array("DAFTAR_ALAMAT_ID", "INSTANSI", "ALAMAT", "KOTA", "NO_TELP");
    
	        while($daftar_alamat->nextRow())
            {
                $row = array();
                for ( $i=0 ; $i<count($aColumns) ; $i++ )
                {
                    if($aColumns[$i] == "TANGGAL")
                        $row[trim($aColumns[$i])] = getFormattedDate($daftar_alamat->getField($aColumns[$i]));
                    else if($aColumns[$i] == "KETERANGAN")
                        $row[trim($aColumns[$i])] = truncate($daftar_alamat->getField($aColumns[$i]), 5)."...";
                    else
                        $row[trim($aColumns[$i])] = $daftar_alamat->getField($aColumns[$i]);
                }
				$row['value'] = $daftar_alamat->getField('INSTANSI');
                
                $result[] = $row;
            }
            
            $this->response(array('status' => 'success', 'message' => 'success', 'code' => 200, 'count' => count($aColumns) ,'result' => $result));
        }
        else
            $this->response(array('status' => 'fail', 'message' => 'Sesi anda telah berakhir', 'code' => 502));
        
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