<?php
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class surat_disposisi_detil_json extends REST_Controller {
 
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
		
        include_once("libraries/vendor/autoload.php");
        $this->load->library('ReportPDF');
        $this->load->library('suratmasukinfo');

        $suratmasukinfo = new suratmasukinfo();

        $this->load->model("SuratMasuk");
        $this->load->model("SuratMasukParaf");
        $this->load->model("Disposisi");
        $this->load->model("DisposisiKelompok");
        $this->load->model("SatuanKerja");
        
        $surat_masuk            = new SuratMasuk();
        $surat_masuk_attachment = new SuratMasuk();
        $disposisi              = new Disposisi();
        $surat_masuk_paraf      = new SuratMasukParaf();

        // $reqJenisTujuan = "NI";
        $reqId          = $this->input->get("reqId");
        $reqDisposisiId = $this->input->get("reqDisposisiId");
        // $reqIdDraft     = $reqId;

        $aColumns = array("DISPOSISI_ID", "DISPOSISI_PARENT_ID", "STATUS_DISPOSISI", "NAMA_SATKER_ASAL", "NAMA_USER_ASAL", "NAMA_SATKER",  "NAMA_USER", "TANGGAL_DISPOSISI", "INFO_KEPADA", "TINDAKAN", "CATATAN", "SIFAT_NAMA", "USER_ID", "NIP_MUTASI", "TERBALAS", "TERBACA_INFO");

        $result = array();

        if (!empty($reqDisposisiId)) 
        {

            $surat_masuk->selectByParamsInfoDisposisi(array("A.DISPOSISI_ID"=>$reqDisposisiId));
            
            while($surat_masuk->nextRow())
            {
                for($i=0;$i<count($aColumns);$i++)
                {
                    if ($aColumns[$i] == "INFO_KEPADA") {
                        $infoKepada = $surat_masuk->getField("INFO_KEPADA");
                        $infoKepada = str_replace('<li>', '', $infoKepada);
                        $arrInfoKepada = explode('</li>', $infoKepada);
                        
                        if (!empty($arrInfoKepada)) {
                            while (list($key, $val) = each($arrInfoKepada))
                            {
                                if ($val == "") 
                                {}
                                else
                                $kepada[$key] = $val;
                            }
                        }
                        $row[trim($aColumns[$i])] = $kepada;
                    }
                    elseif ($aColumns[$i] == "TINDAKAN") {
                        $dataTindakan = $surat_masuk->getField("TINDAKAN");
                        $arrTindakan = explode(',', $dataTindakan);
                        
                        if (!empty($arrTindakan)) {
                            while (list($key, $val) = each($arrTindakan))
                            {
                                if ($val == "") 
                                {}
                                else
                                $tindakan[$key] = $val;
                            }
                        }
                        $row[trim($aColumns[$i])] = $tindakan;
                    }
                    else
                    {
                        $row[trim($aColumns[$i])] = $surat_masuk->getField(trim($aColumns[$i]));
                    }
                }
                
                $result = $row;
            }

            $setterbaca= $infodisposisiuserid.",".date("Y-m-d H:i:s");

            $simpan= "";
            if(!empty($infodisposisiuserid))
            {
                $simpan= "";
            }

                
            $arrcheckterbaca= explode(";", $infodisposisiterbacainfo);
            if(!empty($arrcheckterbaca) && !empty($infodisposisiterbacainfo))
            {
                while (list($key, $val) = each($arrcheckterbaca))
                {
                    $arrcheckterbacadetil= explode(",", $val);
                    if($infodisposisiuserid == $arrcheckterbacadetil[0])
                    {
                        $simpan= "1";
                        break;
                    }
                }
            }

            if(!empty($infodisposisiusermutasiid))
            {
                $simpan= "";
                $setterbaca= $infodisposisiusermutasiid.",".date("Y-m-d H:i:s");
            }

            $arrcheckterbaca= explode(";", $infodisposisiterbacainfo);
            if(!empty($arrcheckterbaca) && !empty($infodisposisiterbacainfo))
            {
                while (list($key, $val) = each($arrcheckterbaca))
                {
                    $arrcheckterbacadetil= explode(",", $val);
                    if($infodisposisiusermutasiid == $arrcheckterbacadetil[0])
                    {
                        $simpan= "1";
                        break;
                    }
                }
            }

            if(empty($simpan))
            {
                if(empty($infodisposisiterbacainfo))
                {
                    $infodisposisiterbacainfo= $setterbaca;
                }
                else
                {
                    $infodisposisiterbacainfo= $infodisposisiterbacainfo.";".$setterbaca;
                }
                $setdetil= new Disposisi();
                $setdetil->setField("DISPOSISI_ID", $reqDisposisiId);
                $setdetil->setField("TERBACA_INFO", $infodisposisiterbacainfo);
                $setdetil->updateterbaca();
            }

        }

        

        /* GET AKSES SURAT */
        $suratmasukinfo->getAkses($reqId, $userMobile->ID);
        $aksesSurat = $suratmasukinfo->AKSES;
        $linkPDF    = $suratmasukinfo->PDF;
        $templateSurat  = $suratmasukinfo->TEMPLATE;

        if($aksesSurat == "")
        {
            $this->response(array('status' => 'fail', 'code' => 502, 'message' => 'Anda tidak memiliki hak akses melihat surat'));
            return;
        }

        if($aksesSurat == "DISPOSISI")
        {
            if($linkPDF == "")
            {

            }
        }

        if($templateSurat == "")
        {
            $this->response(array('status' => 'fail', 'code' => 502, 'message' => 'Template surat belum dibuat'));
            return;
        }

        if($linkPDF == "")
        {
            $report = new ReportPDF();
            $docPDF = $report->generate($reqId, $templateSurat);          
        }
        else{
            $docPDF = $linkPDF;
        }

        $result["SURAT_PDF"] = base_url()."uploads/".$reqId."/".$docPDF;
        
        $this->response(array('status' => 'success', 'message' => 'success', 'code' => 200, 'count' => count($aColumns) ,'result' => $result, 'set_terbaca' => $simpan));
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