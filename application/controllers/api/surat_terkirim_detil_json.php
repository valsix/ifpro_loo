<?php
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class surat_terkirim_detil_json extends REST_Controller {
 
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
        $surat_masuk = new SuratMasuk();
        $surat_masuk_attachment = new SuratMasuk();
        $disposisi   = new Disposisi();
        $surat_masuk_paraf = new SuratMasukParaf();

        $reqJenisTujuan = "NI";
        $reqId = $this->input->get("reqId");

        $aColumns = array("SURAT_MASUK_ID","DISPOSISI_ID","SURAT_MASUK_REF_ID","FOTO","NAMA_USER_ASAL","NAMA_SATKER_ASAL",
            "ALAMAT_ASAL","JENIS","SIFAT_NASKAH","TANGGAL_DISPOSISI","PERIHAL","NOMOR","KLASIFIKASI_KODE","KLASIFIKASI","ISI");

        $result = array();

        $statement_privacy .= " AND (A.USER_ATASAN_ID = '".$userMobile->ID_ATASAN."' OR A.USER_ID = '".$userMobile->ID_ATASAN."' OR A.USER_ATASAN_ID = '".$userMobile->ID."' OR A.USER_ID = '".$userMobile->ID."' OR A.USER_ID_OBSERVER = '".$userMobile->ID."' OR EXISTS(SELECT 1 FROM SURAT_MASUK_PARAF X WHERE X.SURAT_MASUK_ID = A.SURAT_MASUK_ID AND X.USER_ID = '".$userMobile->ID."'))  ";
        
        $statement_privacy .= " AND (A.STATUS_SURAT IN ('TATAUSAHA','POSTING') OR A.STATUS_SURAT LIKE 'TU%') ";

		$surat_masuk->selectByParamsLihat(array("A.SURAT_MASUK_ID" => $reqId), -1, -1, $statement_privacy);
        // echo $surat_masuk->query;exit;
		while($surat_masuk->nextRow())
		{
            for($i=0;$i<count($aColumns);$i++ )
            {
                if($aColumns[$i] == "TANGGAL_DISPOSISI"){
                    $row[trim($aColumns[$i])] = getFormattedDate($surat_masuk->getField(trim($aColumns[$i]))); 
                }
                elseif($aColumns[$i] == "TERBACA")
                {
                    if($surat_masuk->getField(trim($aColumns[$i])) == 1){
                        $row[trim($aColumns[$i])] = "1";  
                    }
                    else{
                        $row[trim($aColumns[$i])] = "0";              
                    }
                }
                elseif($aColumns[$i] == "ISI"){
                    if($surat_masuk->getField("DISPOSISI") == ""){
                        $row[trim($aColumns[$i])] = $surat_masuk->getField(trim($aColumns[$i])); 
                    }
                    else{
                        $row[trim($aColumns[$i])] = $surat_masuk->getField("DISPOSISI");
                    }
                }
                elseif($aColumns[$i] == "FOTO"){
                    $row["FOTO"] = generateFotoMobile("X", $surat_masuk->getField("NAMA_USER_ASAL"));
                }
                else{
                    $row[trim($aColumns[$i])] = $surat_masuk->getField(trim($aColumns[$i]));
                }
            }
            

		}
            $result = $row;

        //Attachment surat
        $adaAttachment = $surat_masuk_attachment->getCountByParamsAttachment(array("A.SURAT_MASUK_ID" => (int)$reqId));
        if($adaAttachment > 0)
        {
            $surat_masuk_attachment->selectByParamsAttachment(array("A.SURAT_MASUK_ID" => (int)$reqId));
            $i = 0;
            while($surat_masuk_attachment->nextRow())
            {
                $result["LAMPIRAN"][$i]["NAMA"] = $surat_masuk_attachment->getField("NAMA");
                $result["LAMPIRAN"][$i]["UKURAN"] = round(($surat_masuk_attachment->getField("UKURAN")/1024), 2)." KB";
                $result["LAMPIRAN"][$i]["URI"] = base_url()."uploads/".$reqId."/".$surat_masuk_attachment->getField("ATTACHMENT");
                $i++;
            }
        }
        else{
            $result["LAMPIRAN"] = [];
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