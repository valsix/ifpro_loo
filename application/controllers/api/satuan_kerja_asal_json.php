<?php
error_reporting(-1);
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class satuan_kerja_asal_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();
        $this->methods['index_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['index_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['index_put']['limit'] = 50; // 50 requests per hour per user/key
    }
 
    // show data entitas
	function index_get() {
        $aColumns = array("SATUAN_KERJA_ID", "NAMA_INFO", "NAMA", "CHILDREN");
		
        $this->load->model('UserLoginMobile');
        $reqToken = $this->input->get("reqToken");
        $reqBantuMode = $this->input->get("reqBantuMode");

        //CEK PEGAWAI ID DARI TOKEN
        $user_login_mobile = new UserLoginMobile();
        // $reqPegawaiId = $user_login_mobile->getTokenPegawaiId(array("TOKEN" => $reqToken, "STATUS" => '1'));
        $reqPegawaiId = '9014140KP';

        if($reqPegawaiId <> "0")
        {
            // if($reqBantuMode == "1")
            // $tempSatuanKerjaId= $this->userBantuSatkerId;
            // else
            // $tempSatuanKerjaId= $this->userSatker;

            $tempSatuanKerjaId = 0;

            function getSatuanKerjaByParent($id_induk, $tempSatuanKerjaId)
            {
				$child = array();
                $satker_child = new SatuanKerja();
                $satker_child->selectByParams(array("SATUAN_KERJA_ID_PARENT"=> $id_induk), -1, -1);
                $m = 0;
                while ($satker_child->nextRow()) {
                    $child[$m]['id'] = $satker_child->getField("SATUAN_KERJA_ID");
                    $child[$m]['name'] = $satker_child->getField("NAMA");
					$child[$m]['parentId'] = $satker_child->getField("SATUAN_KERJA_ID_PARENT");
					$child[$m]['sortNo'] = $satker_child->getField("SATUAN_KERJA_ID");
                    $child[$m]['NAMA'] = $satker_child->getField("NAMA");
                    $child[$m]['children'] = getSatuanKerjaByParent($satker_child->getField("SATUAN_KERJA_ID"), $tempSatuanKerjaId);
                    $m++;
                }

                return $child;
            }


            $this->load->model("base-surat/SatuanKerja");
            $satker = new SatuanKerja();
            
            $statement= " AND SATUAN_KERJA_ID_PARENT = '1'";
            $satker->selectByParams(array(), -1, -1, $statement);
            $result = array();
            $i = 0;
    		while($satker->nextRow())
    		{
    			$parent = array();
                
                $parent[$i]['id'] = $satker->getField("SATUAN_KERJA_ID");
				$parent[$i]['name'] = $satker->getField("NAMA");
				$parent[$i]['parentId'] = $satker->getField("SATUAN_KERJA_ID_PARENT");
				$parent[$i]['sortNo'] = $satker->getField("SATUAN_KERJA_ID");
				$parent[$i]['NAMA'] = $satker->getField("NAMA");
				$parent[$i]['children'] = getSatuanKerjaByParent($satker->getField("SATUAN_KERJA_ID"), $tempSatuanKerjaId);
                
                $j++;
    		}
            $result = $parent;
            
		
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