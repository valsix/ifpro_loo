<?php
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class surat_terkirim_json extends REST_Controller {
 
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
				
        $this->load->model("SuratMasuk");
        $this->load->model("Disposisi");
        $surat_masuk = new SuratMasuk();
        $disposisi = new Disposisi();

        $reqPencarian = $this->input->get("reqPencarian");

        $aColumns = array("NOMOR", "INFO_DARI", "PERIHAL", "TANGGAL_DISPOSISI", "SURAT_MASUK_ID", "DISPOSISI_ID");

        $result = array();

       $searchJson= " 
        AND 
        (
            UPPER(A.NO_AGENDA) LIKE '%".strtoupper($reqPencarian)."%' OR 
            UPPER(A.NOMOR) LIKE '%".strtoupper($reqPencarian)."%' OR
            UPPER(A.PERIHAL) LIKE '%".strtoupper($reqPencarian)."%' OR 
            UPPER(A.INSTANSI_ASAL) LIKE '%".strtoupper($reqPencarian)."%' OR 
            UPPER(A.DARI_INFO) LIKE '%".strtoupper($reqPencarian)."%' OR 
            UPPER(A.USER_ATASAN) LIKE '%".strtoupper($reqPencarian)."%' OR 
            UPPER(USER_ATASAN_JABATAN) LIKE '%".strtoupper($reqPencarian)."%' OR 
            UPPER(A.NOMOR_SURAT_INFO) LIKE '%".strtoupper($reqPencarian)."%'
        )";

        $statement= " AND (A.STATUS_SURAT IN ('TATAUSAHA','POSTING') OR A.STATUS_SURAT LIKE 'TU%')";

		$sOrder = " ORDER BY B.TANGGAL_DISPOSISI DESC";
        $surat_masuk->selectByParamsSuratKeluar(array(), -1, -1, $userMobile->ID, $statement.$searchJson, $sOrder);
        // echo $surat_masuk->query;exit;
		while($surat_masuk->nextRow())
		{
            $infojenisnaskahid= $surat_masuk->getField("JENIS_NASKAH_ID");

            for($i=0;$i<count($aColumns);$i++)
            {
                if($aColumns[$i] == "TANGGAL_ENTRI"){
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
                elseif($aColumns[$i] == "INFO_DARI")
                {
                    if($infojenisnaskahid == "1")
                        $row[trim($aColumns[$i])] = $surat_masuk->getField("DARI_INFO");
                    else
                        $row[trim($aColumns[$i])] = $surat_masuk->getField("USER_ATASAN")." \n".$surat_masuk->getField("USER_ATASAN_JABATAN");
                }
                elseif($aColumns[$i] == "ISI"){
                    $row[trim($aColumns[$i])] = strip_tags($surat_masuk->getField(trim($aColumns[$i]))); 
                }
                elseif($aColumns[$i] == "FOTO"){
                    $row["FOTO"] = generateFotoMobile("X", $surat_masuk->getField("KEPADA"));
                }
                else{
                    $row[trim($aColumns[$i])] = $surat_masuk->getField(trim($aColumns[$i]));
                }
            }
            
            $result[] = $row;

		}
        
        $this->response(array('status' => 'success', 'message' => 'success', 'code' => 200, 'count' => count($aColumns) ,'result' => $result));
    }
	
    // insert new data to entitas
    function index_post() {
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
        
        $this->load->model('Disposisi');
        $disposisi = new Disposisi();

        $reqMode = $this->input->post("reqMode");
        $reqSuratMasukId = $this->input->post("reqSuratMasukId");
        $reqDisposisiId = $this->input->post("reqDisposisiId");

        if($reqMode == "BACA")
        {
            $disposisi->setField("FIELD", "TERBACA");
            $disposisi->setField("FIELD_VALUE", "1");
            $disposisi->setField("LAST_UPDATE_USER", $userMobile->ID);
            $disposisi->setField("SURAT_MASUK_ID", $reqSuratMasukId);
            $disposisi->setField("DISPOSISI_ID", $reqDisposisiId);
            $disposisi->setField("USER_ID", $userMobile->ID);
            $disposisi->updateByFieldValidasiUserBaca();

            $this->response(array('status' => 'success', 'code' => 200, 'message' => 'Berhasil'));
        }
    }

    // update data entitas
    function index_put() {

    }
 
    // delete entitas
    function index_delete() {

    }
 
}