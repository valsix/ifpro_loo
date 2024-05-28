<?php
 error_reporting(-1);
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class satuan_kerja_fix_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();
		
    }
 
    // show data entitas
	function index_get() {
		
		
		$result = $this->db->query(" select a.*,b.email email2 from satuan_kerja_fix a left join pegawai b on b.nip = a.nip ")->result_array();
		 
		$this->response(array('status' => 'success', 'message' => 'success', 'code' => 200, 'result' => $result));
		
		
    }
	
 
}