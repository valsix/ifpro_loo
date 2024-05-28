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

		$disposisi->selectByParams(array("A.SURAT_MASUK_ID" => (int)$reqId),-1,-1, ""," ORDER BY A.TANGGAL_DISPOSISI");
        // echo $disposisi->query;exit;
        $index_data= 0;
        $arrdisposisi= array();
		while($disposisi->nextRow())
        {
            $arrdisposisi[$index_data]["DISPOSISI_ID"]= $disposisi->getField("DISPOSISI_ID");
            $arrdisposisi[$index_data]["DISPOSISI_PARENT_ID"]= $disposisi->getField("DISPOSISI_PARENT_ID");
            $arrdisposisi[$index_data]["INFO_STATUS_DISPOSISI"]= $disposisi->getField("INFO_STATUS_DISPOSISI");
            $arrdisposisi[$index_data]["NAMA_SATKER_ASAL"]= $disposisi->getField("NAMA_SATKER_ASAL");
            $arrdisposisi[$index_data]["NAMA_SATKER"]= $disposisi->getField("NAMA_SATKER");
            $arrdisposisi[$index_data]["ISI"]= $disposisi->getField("ISI");
            $arrdisposisi[$index_data]["KETERANGAN"]= $disposisi->getField("KETERANGAN");
            $arrdisposisi[$index_data]["INFO_TANGGAL_DISPOSISI"]= getFormattedInfoDateTimeCheck($disposisi->getField("INFO_TANGGAL_DISPOSISI"));
            $index_data++;
        }
        
        // var_dump($arrdisposisi);exit;
        $arrheaderdisposisi= array();
        $infodisposisiid= "";
        $arrayKey= in_array_column("0", "DISPOSISI_PARENT_ID", $arrdisposisi);
        // print_r($arrayKey);exit;

        if(!empty($arrayKey))
        {
            for($i=0; $i < count($arrayKey); $i++)
            {
                $index_data= $arrayKey[$i];

                if(empty($infodisposisiid))
                    $infodisposisiid= $arrdisposisi[$index_data]["DISPOSISI_ID"];
                else
                    $infodisposisiid= $infodisposisiid.",".$arrdisposisi[$index_data]["DISPOSISI_ID"];
                // print_r($arrayKey);exit;
            }
        }

        // echo $infodisposisiid;exit;
        $infonotatindakan= "";
        $jumlahheader= 0;
        if(!empty($infodisposisiid))
        {
            $infodisposisiid= explode(",", $infodisposisiid);
            for($x=0; $x < count($infodisposisiid); $x++)
            {
                $arrayKey= in_array_column($infodisposisiid[$x], "DISPOSISI_PARENT_ID", $arrdisposisi);
                // print_r($arrayKey);exit;
                if(!empty($arrayKey))
                {
                    for($i=0; $i < count($arrayKey); $i++)
                    {
                        $arrdata= [];
                        $index_data= $arrayKey[$i];
                        if($i == 0)
                        {
                            $infonotatindakan= $arrdisposisi[$index_data]["ISI"];
                        }
                        $arrdata["DISPOSISI_ID"]= $arrdisposisi[$index_data]["DISPOSISI_ID"];
                        $arrdata["DISPOSISI_PARENT_ID"]= $arrdisposisi[$index_data]["DISPOSISI_PARENT_ID"];
                        $arrdata["INFO_STATUS_DISPOSISI"]= $arrdisposisi[$index_data]["INFO_STATUS_DISPOSISI"];
                        $arrdata["DARI"]= $arrdisposisi[$index_data]["NAMA_SATKER_ASAL"];
                        $arrdata["KEPADA"]= $arrdisposisi[$index_data]["NAMA_SATKER"];
                        $arrdata["ISI"]= $arrdisposisi[$index_data]["ISI"];
                        $arrdata["KETERANGAN"]= $arrdisposisi[$index_data]["KETERANGAN"];
                        $arrdata["INFO_TANGGAL_DISPOSISI"]= $arrdisposisi[$index_data]["INFO_TANGGAL_DISPOSISI"];
                        array_push($arrheaderdisposisi, $arrdata);
                        $jumlahheader++;
                    }

                }
            }
        }

        // print_r($arrheaderdisposisi);exit;
        // echo $jumlahheader;exit;
        

        for($index_data=0; $index_data < $jumlahheader; $index_data++)
        {
            $row[$index_data]["DISPOSISI_ID"] = $arrheaderdisposisi[$index_data]["DISPOSISI_ID"];
            $row[$index_data]["DISPOSISI_PARENT_ID"] = $arrheaderdisposisi[$index_data]["DISPOSISI_PARENT_ID"];
            $row[$index_data]["INFO_STATUS_DISPOSISI"] = $arrheaderdisposisi[$index_data]["INFO_STATUS_DISPOSISI"];
            $row[$index_data]["DARI"] = $arrheaderdisposisi[0]["DARI"];
            $row[$index_data]["KEPADA"] = $arrheaderdisposisi[0]["KEPADA"];
            $row[$index_data]["ISI"] = $arrheaderdisposisi[$index_data]["ISI"];
            $row[$index_data]["KETERANGAN"] = $arrheaderdisposisi[$index_data]["KETERANGAN"];
            $row[$index_data]["INFO_TANGGAL_DISPOSISI"] = $arrheaderdisposisi[$index_data]["INFO_TANGGAL_DISPOSISI"];
            $arrdetildisposisi= [];
            $this->ambildata($arrdisposisi, $arrdetildisposisi, $arrheaderdisposisi[$index_data]["DISPOSISI_ID"]);
            $row[$index_data]["DETIL"] = $arrdetildisposisi; 
        }

        $result = $row;

        $this->response(array('status' => 'success', 'message' => 'success', 'code' => 200, 'count' => count($aColumns) ,'result' => $result));
    }
	
    function ambildata($arrdisposisi, &$arrdetildisposisi, $infodisposisiid)
    {
        $jumlahheader = 0;
        $arrayKey= in_array_column($infodisposisiid, "DISPOSISI_PARENT_ID", $arrdisposisi);
        if(!empty($arrayKey))
        {
            for($i=0; $i < count($arrayKey); $i++)
            {
                $arrdata= [];
                $index_data= $arrayKey[$i];
                $infodisposisiid= $arrdisposisi[$index_data]["DISPOSISI_ID"];
                $arrdata["DISPOSISI_ID"]= $infodisposisiid;
                $arrdata["DISPOSISI_PARENT_ID"]= $arrdisposisi[$index_data]["DISPOSISI_PARENT_ID"];
                $arrdata["INFO_STATUS_DISPOSISI"]= $arrdisposisi[$index_data]["INFO_STATUS_DISPOSISI"];
                $arrdata["DARI"]= $arrdisposisi[$index_data]["NAMA_SATKER_ASAL"];
                $arrdata["KEPADA"]= $arrdisposisi[$index_data]["NAMA_SATKER"];
                $arrdata["ISI"]= $arrdisposisi[$index_data]["ISI"];
                $arrdata["KETERANGAN"]= $arrdisposisi[$index_data]["KETERANGAN"];
                $arrdata["INFO_TANGGAL_DISPOSISI"]= $arrdisposisi[$index_data]["INFO_TANGGAL_DISPOSISI"];
                array_push($arrdetildisposisi, $arrdata);

                $this->ambildata($arrdisposisi, $arrdetildisposisi, $infodisposisiid);
                $jumlahheader++;
            }

            // return $arrdata;
        }
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