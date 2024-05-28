<?php
 
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/default.func.php");
 
class tujuan_surat_json extends REST_Controller {
 
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

		$this->load->library("Pagination");

		$this->load->model("SatuanKerja");
		$satuan_kerja = new SatuanKerja();
	
		$reqSearch = $this->input->get("reqSearch");		
		
		
		$statement = " AND (UPPER(A.NAMA) LIKE '%".strtoupper($reqSearch)."%' OR UPPER(A.NAMA_PEGAWAI) LIKE '%".strtoupper($reqSearch)."%') ";
		
		
		$satuan_kerja->selectByParams(array(), -1, -1, $statement_privacy.$statement, " ORDER BY A.URUT ASC "); 
		
		$i = 0;
		$arrColumn = array("SATUAN_KERJA_ID", "NAMA", "NAMA_PEGAWAI", "NIP");
		
		$arrData = array();					
        while($satuan_kerja->nextRow())
        {
			for($iCol=0;$iCol<count($arrColumn);$iCol++)
			{
				$arrData[$i][$arrColumn[$iCol]] = $satuan_kerja->getField($arrColumn[$iCol]);
			}
            $i++;
        }
		
		
        $arrResponse["children"] = $arrData; 
        $arrResponse["after"] = $reqShow + $reqPage;    
        if($satuan_kerja->rowCount > 0)
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