<?php
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class surat_masuk_detil_json extends REST_Controller {
 
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

        $aColumns = array("SURAT_MASUK_ID","DISPOSISI_ID","DARI_INFO","SATUAN_KERJA_ID_ASAL","SATUAN_KERJA_ID_TUJUAN","NAMA_SATKER_ASAL",
            "ALAMAT_ASAL","JENIS","SIFAT_NASKAH","TANGGAL_ENTRI","TANGGAL_DISPOSISI","PERIHAL","NOMOR", "NOMOR_SURAT_INFO","KLASIFIKASI_KODE","KLASIFIKASI","ISI", "PEMESAN_SATUAN_KERJA_ISI", "KETERANGAN","INFO_DISPOSISI");

        $result = array();

        if (!empty($reqDisposisiId)) 
        {
            $statement= " AND A.SURAT_MASUK_ID = ".$reqId." AND B.DISPOSISI_ID = ".$reqDisposisiId;
            $surat_masuk->selectByParamsSuratMasuk(array(), -1,-1, $statement);
        }
        else
        {
            $statement= " AND A.SURAT_MASUK_ID = ".$reqId;
            // $surat_masuk->selectByParams(array("A.SURAT_MASUK_ID" => $reqId), -1,-1);
            $surat_masuk->selectByParamsStatus(array(), -1,-1, $userMobile->ID, $statement);  
        }
        
        // echo $surat_masuk->query;exit;

		while($surat_masuk->nextRow())
		{
            for($i=0;$i<count($aColumns);$i++ )
            {
                if ($aColumns[$i] == "TANGGAL_ENTRI") {
                    $row[trim($aColumns[$i])] = getFormattedExtDateTimeCheck($surat_masuk->getField("TANGGAL_ENTRI"));                
                }
                else
                {
                    $row[trim($aColumns[$i])] = $surat_masuk->getField(trim($aColumns[$i]));
                }
            }
		}

        $result = $row;


        $disposisi->selectByParams(array("A.DISPOSISI_ID"=>$reqDisposisiId), -1, -1);
        $disposisi->firstRow();
        $infodisposisistatus      = $disposisi->getField("STATUS_DISPOSISI");
        $infodisposisiterbaca     = $disposisi->getField("TERBACA");
        $infodisposisiterbacainfo = $disposisi->getField("TERBACA_INFO");

        if($infodisposisistatus == "TUJUAN" || $infodisposisistatus == "TEMBUSAN")
        {
            $simpan= "";

            $statementdetil= " AND 
            (
                (
                    A.USER_ID = '".$userMobile->ID."' AND A.STATUS_BANTU IS NULL
                )
                OR
                EXISTS
                (
                    SELECT 1
                    FROM
                    (
                        SELECT NIP, SATUAN_KERJA_ID FROM SATUAN_KERJA_FIX WHERE USER_BANTU = '".$userMobile->ID."'
                    ) XXX WHERE XXX.NIP = A.USER_ID --AND A.STATUS_BANTU = 1
                    AND
                    EXISTS
                    (
                        SELECT 1
                        FROM
                        (
                            SELECT DISTINCT DISPOSISI_ID
                            FROM
                            (
                                SELECT DISPOSISI_ID
                                FROM DISPOSISI WHERE
                                SURAT_MASUK_ID IN
                                (
                                    SELECT SURAT_MASUK_ID FROM SURAT_MASUK WHERE JENIS_NASKAH_ID = 1
                                ) AND STATUS_BANTU = 1
                            ) A
                        ) YYY WHERE A.DISPOSISI_ID = YYY.DISPOSISI_ID
                    )
                )
                OR
                EXISTS
                (
                    SELECT 1
                    FROM
                    (
                        SELECT
                        CASE WHEN COALESCE(NULLIF(NIP, ''), 'X') = 'X' THEN NIP_OBSERVER ELSE NIP END NIP_OBSERVER
                        , SATUAN_KERJA_ID
                        FROM SATUAN_KERJA_FIX WHERE 1=1
                        AND (NIP_OBSERVER = '".$userMobile->ID."' OR NIP = '".$userMobile->ID."')
                    ) X WHERE X.SATUAN_KERJA_ID = A.SATUAN_KERJA_ID_TUJUAN
                    AND
                    EXISTS
                    (
                        SELECT 1
                        FROM
                        (
                            SELECT DISTINCT DISPOSISI_ID
                            FROM
                            (
                                SELECT DISPOSISI_ID
                                FROM DISPOSISI WHERE
                                SURAT_MASUK_ID IN
                                (
                                    SELECT SURAT_MASUK_ID FROM SURAT_MASUK WHERE JENIS_NASKAH_ID = 1
                                ) AND STATUS_BANTU IS NULL
                                UNION ALL
                                SELECT DISPOSISI_ID
                                FROM DISPOSISI WHERE
                                SURAT_MASUK_ID IN
                                (
                                    SELECT SURAT_MASUK_ID FROM SURAT_MASUK WHERE JENIS_NASKAH_ID NOT IN (1)
                                )
                            ) A
                        ) YYY WHERE A.DISPOSISI_ID = YYY.DISPOSISI_ID
                    )
                )
                
            )";

            $setdetil= new Disposisi();
            $setdetil->selectByParams(array("A.DISPOSISI_ID"=>$reqDisposisiId), -1, -1, $statementdetil." AND DISPOSISI_KELOMPOK_ID = 0");
            $setdetil->firstRow();
            // echo $setdetil->query;exit;

            $infodisposisiuserid= $setdetil->getField("USER_ID");
            $infodisposisinipmutasi= $setdetil->getField("NIP_MUTASI");
            $infodisposisistatusbantu= $setdetil->getField("STATUS_BANTU");
            $infodisposisipejabatrehatsekarang= $setdetil->getField("PEJABAT_REHAT_SEKARANG_NIP");
            $infodisposisipejabatrehatcheck= $setdetil->getField("PEJABAT_REHAT_CHECK");
            
            // kalau jenis naskah surat keluar maka check dulu user bantu
            if($infodisposisistatusbantu == "1" && ($infojenisnaskahid == 15 || $infojenisnaskahid == 1))
            {
                $userbantu= new SatuanKerja();
                $userbantu->selectByParams(array(),-1,-1, " AND A.SATUAN_KERJA_ID = '".$setdetil->getField("SATUAN_KERJA_ID_TUJUAN")."'");
                $userbantu->firstRow();
                // echo $userbantu->query;exit;
                $infodisposisiuserid= $userbantu->getField("USER_BANTU");
                // echo $infodisposisiuserid;exit;
                unset($userbantu);
            }

            if($infodisposisipejabatrehatcheck == 0)
            {
                $infodisposisiuserid= $userMobile->ID;
            }

            if(!empty($infodisposisinipmutasi))
            {
                $infodisposisiuserid= $infodisposisinipmutasi;
            }

            if($infodisposisiuserid !== $userMobile->ID)
            {
                $result["AKSI_BUTTON"] = "0";
            }else{
                $result["AKSI_BUTTON"] = "1";
            }

            $setterbaca= $infodisposisiuserid.",".date("Y-m-d H:i:s");

            unset($setdetil);

            if(!empty($infodisposisiuserid))
            {
                $simpan= "";
            }
            else
            {
                $setdetil= new Disposisi();
                $setdetil->selectByParamsPara(array("A.DISPOSISI_ID"=>$reqDisposisiId , "XXX.PEGAWAI_ID"=> $userMobile->ID), -1, -1, " AND DISPOSISI_KELOMPOK_ID > 0");
                $setdetil->firstRow();
                // echo $setdetil->query;exit;
                $infodisposisiuserid = $setdetil->getField("PEGAWAI_ID");
                $setterbaca= $infodisposisiuserid.",".date("Y-m-d H:i:s");

                if(empty($infodisposisiuserid))
                {
                    $simpan= "1";
                }
            }
            // echo $simpan.$infodisposisiuserid;exit;
            
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
                // echo $simpan;
                // print_r($arrcheckterbaca);exit;
            }

            if(empty($simpan))
            {
                // echo $infodisposisiterbacainfo;exit;
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
                // echo $setdetil->query;exit;
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