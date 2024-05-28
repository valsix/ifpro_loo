<?php
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class foto_profil_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();
		
		$this->db->query("SET DATESTYLE TO PostgreSQL,European;");  
		
        $this->methods['index_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['index_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['index_put']['limit'] = 50; // 50 requests per hour per user/key
    }
 
    // show data entitas
	function index_get() 
	{
        
    }
	
    // insert new data to entitas
 //    function index_post() 
	// {
		
	// 	/* AMBIL TOKEN */
 //        $this->load->model('UserLoginMobile');
 //        $user_login_mobile = new UserLoginMobile();
 //        $reqToken = $this->input->get('reqToken');
 //        $reqUserLoginId = $user_login_mobile->getTokenUserLoginId(array("TOKEN" => $reqToken, "STATUS" => '1'));
		
	// 	if($reqUserLoginId == "0" || $reqUserLoginId == "")
	// 	{
	// 		$this->response(array('status' => 'fail', 'message' => 'Sesi anda telah berakhir', 'code' => 502));
	// 		return;	
	// 	}
		
	// 	$this->load->library("usermobile"); 
	// 	$userMobile = new usermobile();
 //        $userMobile->getInfo($reqUserLoginId, $reqToken);
	// 	/* END OF AMBIL TOKEN */
		
	// 	$this->load->model("UserLogin");
	// 	$user_login = new UserLogin();
		
	// 	$reqMode				= $this->input->post("reqMode");
	// 	$reqFoto 				= $_FILES['reqFoto'];
	// 	$reqFotoTemp 			= $this->input->post('reqFotoTemp');
		
	// 	/* WAJIB UNTUK UPLOAD DATA */
	// 	$this->load->library("FileHandler");
	// 	$FILE_DIR = "uploads/domestic_fire/";
	// 	$insertFoto = "";
	// 	for($i=0;$i<count($reqFoto);$i++)
	// 	{
	// 		$file = new FileHandler();
	// 		$renameFile = date("dmYHis").$i.".".getExtension($reqFoto['name'][$i]);
	// 		if($file->uploadToDirArray('reqFoto', $FILE_DIR, $renameFile, $i))
	// 		{
	// 			if($insertFoto == ""){		
	// 				$insertFoto =  $renameFile;
	// 			}
	// 			else{
	// 				$insertFoto .=  ",".$renameFile;					
	// 			}
	// 		}
	// 	}
		
	// 	for($i=0;$i<count($reqFotoTemp);$i++)
	// 	{
	// 		if($reqFotoTemp[$i] == "")
	// 		{}
	// 		else
	// 		{
	// 			if($insertFoto == ""){	
	// 				$insertFoto =  $reqFotoTemp[$i];
	// 			}
	// 			else{
	// 				$insertFoto .=  ",".$reqFotoTemp[$i];		
	// 			}
	// 		}
	// 	}		
	// 	/* WAJIB UNTUK UPLOAD DATA */
		
	// 	$user_login->setField("USER_LOGIN_ID", $reqUserLoginId);
	// 	$user_login->setField("FIELD", "FOTO");
	// 	$user_login->setField("FIELD_VALUE", $insertFoto);

	// 	if($reqMode == "insert")
	// 	{}
	// 	else
	// 	{
	// 		$user_login->setField("UPDATED_BY", $userMobile->NAMA);
	// 		$user_login->updateByField();
	// 	}
				
	// 	$this->response(array('status' => 'success', 'message' => 'Data berhasil disimpan.'));
 //    }

    function index_post() 
	{
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
		
		$this->load->model("UserLogin");
		
		$reqMode				= $this->input->post("reqMode");
		
		/* WAJIB UNTUK UPLOAD DATA */
		include_once("functions/image.func.php");
		$this->load->library("FileHandler");
		$file = new FileHandler();
		$FILE_DIR = "uploads/profil/";
		
		$reqFoto 			= $_FILES["reqFoto"];
		$reqFotoTemp		= $this->input->post("reqFotoTemp");
		$reqFotoTempSize	= $this->input->post("reqFotoTempSize");
		$reqFotoTempTipe	= $this->input->post("reqFotoTempTipe");

		$i=0;
		
		$renameFile = date("dmYhis").rand().".".getExtension($reqFoto['name']);
		$renameFix  = date("dmYhis").rand().".".getExtension($reqFoto['name']);
		if($file->uploadToDir('reqFoto', $FILE_DIR, $renameFile))
		{	
			createThumbnail($FILE_DIR.$renameFile, $FILE_DIR.$renameFix, 800);
			unlink($FILE_DIR.$renameFile);
			
			$insertLinkSize = $file->uploadedSize;
			$insertLinkTipe =  $file->uploadedExtension;
			$insertFoto 	=  $renameFix;
			
			$user_login = new UserLogin();
			$user_login->setField("USER_LOGIN_ID", $reqUserLoginId);
			$user_login->setField("FIELD", "FOTO");
			$user_login->setField("FIELD_VALUE", $insertFoto);
			$user_login->setField("UPDATED_BY", $userMobile->NAMA);
			$user_login->updateByField();
		}
		
				
		$this->response(array('status' => 'success', 'message' => 'Data berhasil disimpan.'));
    }
 
    // update data entitas
    function index_put() {
		
    }
 
    // delete entitas
    function index_delete() {	
		
    }
	
	function security_mobile(){
		/* AMBIL TOKEN */
        $this->load->model('UserLoginMobile');
        $user_login_mobile = new UserLoginMobile();
        $reqToken = $this->input->post('reqToken');
        $reqUserLoginId = $user_login_mobile->getTokenPegawaiId(array("TOKEN" => $reqToken, "STATUS" => '1'));
		
		if($reqUserLoginId == "0" || $reqUserLoginId == "")
		{
			$this->response(array('status' => 'fail', 'message' => 'Sesi anda telah berakhir', 'code' => 502));
			return;	
		}
		/* END OF AMBIL TOKEN */
	}
 
}