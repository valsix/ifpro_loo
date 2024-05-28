<?php
error_reporting(1);
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class balas_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();
        $this->methods['index_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['index_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['index_put']['limit'] = 50; // 50 requests per hour per user/key
    }

    // insert new data to entitas
    function index_get() {
        
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

		$this->load->model("SuratMasuk");
		$this->load->model("Disposisi");
		$surat_masuk = new SuratMasuk();
		$disposisi = new Disposisi();

		$reqMode 					= $this->input->post("reqMode");
		$reqId 						= $this->input->post("reqId");
		$reqDisposisiId 			= $this->input->post("reqDisposisiId");

		$reqSatuanKerjaIdTujuan		=  $this->input->post("reqSatuanKerjaIdTujuan");
		$reqSatuanKerjaIdTembusan	=  $this->input->post("reqSatuanKerjaIdTembusan");	
		$reqBalasCepatId   	=  $this->input->post("reqBalasCepatId");	
		$reqBalasCepat   	=  $this->input->post("reqBalasCepat");	
		
		if(count($reqSatuanKerjaIdTujuan) == 0 || $reqSatuanKerjaIdTujuan[0] == "")
		{
			$this->response(array('status' => 'success', 'code' => 200, 'message' =>  "X-Isi terlebih dahulu tujuan balasan."));	
			return;
		}
		if($reqBalasCepat == "")
		{
			$this->response(array('status' => 'success', 'code' => 200, 'message' =>  "X-Isi terlebih dahulu pesan balasan."));	
			return;
		}
		
		/* UPDATE STATUS TERBALAS */
		$disposisi->setField("FIELD", "TERBALAS");
		$disposisi->setField("FIELD_VALUE", "1");
		$disposisi->setField("LAST_UPDATE_USER", $userMobile->ID);
		$disposisi->setField("DISPOSISI_ID", $reqDisposisiId);
		$disposisi->updateByField();
		
		/* WAJIB UNTUK UPLOAD DATA */
		$this->load->library("FileHandler");
		$file = new FileHandler();
		$FILE_DIR= "uploads/".$reqId."/";
		makedirs($FILE_DIR);

		
		$reqLinkFile = $_FILES["reqLinkFile"];
		$reqLinkFileTempSize	=  $this->input->post("reqLinkFileTempSize");
		$reqLinkFileTempTipe	=  $this->input->post("reqLinkFileTempTipe");
		$reqLinkFileTemp		=  $this->input->post("reqLinkFileTemp");

		$reqJenis = "BLS".generateZero($reqId, 5);
		for($i=0;$i<count($reqLinkFile);$i++)
		{
			$renameFile = $reqJenis.date("Ymdhis").rand().".".getExtension($reqLinkFile['name'][$i]);
		
			if($file->uploadToDirArray('reqLinkFile', $FILE_DIR, $renameFile, $i))
			{	
				$insertLinkSize = $file->uploadedSize;
				$insertLinkTipe =  $file->uploadedExtension;
				$insertLinkFile =  $renameFile;
				
				if($insertLinkFile == "")
				{}
				else
				{
					$surat_masuk_attachement = new Disposisi();
					$surat_masuk_attachement->setField("SURAT_MASUK_ID", $reqId);
					$surat_masuk_attachement->setField("DISPOSISI_ID", $reqDisposisiId);
					$surat_masuk_attachement->setField("ATTACHMENT", $renameFile);
					$surat_masuk_attachement->setField("UKURAN", $insertLinkSize);
					$surat_masuk_attachement->setField("TIPE", $insertLinkTipe);
					$surat_masuk_attachement->setField("NAMA", $reqLinkFile['name'][$i]);
					$surat_masuk_attachement->setField("LAST_CREATE_USER", $userMobile->ID);
					$surat_masuk_attachement->insertAttachment();
				}
			}
		}
		
		/*$reqBalasCepat = "";
		for($i=0;$i<count($reqBalasCepatId);$i++)
		{
			if($i==0)
				$reqBalasCepat .= $reqBalasCepatId[$i];
			else
				$reqBalasCepat .= ";".$reqBalasCepatId[$i];		
		}*/
		
		$disposisi = new Disposisi();
		$disposisi->setField("DISPOSISI_PARENT_ID", $reqDisposisiId);
		$disposisi->setField("SURAT_MASUK_ID", $reqId);
		$disposisi->setField("ISI", $reqBalasCepat);
		if($userMobile->KD_LEVEL_PEJABAT == "")
			$disposisi->setField("SATUAN_KERJA_ID_ASAL", "PEGAWAI".$userMobile->ID);
		else
			$disposisi->setField("SATUAN_KERJA_ID_ASAL", $userMobile->SATUAN_KERJA_ID_ASAL);
		
		$disposisi->setField("SATUAN_KERJA_ID_TUJUAN", $reqSatuanKerjaIdTujuan);
		$disposisi->setField("STATUS_DISPOSISI", "BALASAN");
		$disposisi->setField("LAST_CREATE_USER", $userMobile->ID);
		$disposisi->insert();
		
		$this->response(array('status' => 'success', 'code' => 200, 'message' => $disposisi));

		$this->posting_proses("BALAS", $reqId, $reqDisposisiId);
			
		$this->response(array('status' => 'success', 'code' => 200, 'message' => "Balasan berhasil dikirim."));

    }

}