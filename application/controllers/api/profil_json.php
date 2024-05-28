<?php
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class profil_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();
          
        $this->load->library('Kauth');

        $this->db->query("SET DATESTYLE TO PostgreSQL,European;");  
        
        $this->methods['index_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['index_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['index_put']['limit'] = 50; // 50 requests per hour per user/key
    }
 
    // show data entitas
	function index_get() 
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

        $result = array();

		$this->load->model("Pegawai");
		$pegawai = new Pegawai();

		$pegawai->selectByParamsInformasi(array("A.PEGAWAI_ID" => $userMobile->ID));
		
		$pegawai->firstRow();
		// $result["FOTO"] = base_url().ambilFoto($pegawai->getField("PEGAWAI_ID"), $pegawai->getField("JENIS_KELAMIN"));
		$result["FOTO"] = base_url().getFotoProfile($userMobile->USERNAME);
		$result["PEGAWAI_ID"] = $pegawai->getField("PEGAWAI_ID");
		$result["NIP"] = $pegawai->getField("NIP");
		$result["NAMA"] = $pegawai->getField("NAMA");
		$result["JABATAN"] = $pegawai->getField("JABATAN");
		$result["UNIT_KERJA"] = $pegawai->getField("SATUAN_KERJA");
		$result["DIREKTORAT"] = $pegawai->getField("DEPARTEMEN");
		$result["NAMA_ATASAN"] = $pegawai->getField("NAMA_ATASAN");
		$result["JABATAN_ATASAN"] = $pegawai->getField("JABATAN_ATASAN");
        $this->response(array('status' => 'success', 'message' => 'success', 'code' => 200, 'result' => $result));
    }
	
    // insert new data to entitas
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
		$user_login = new UserLogin();
		
		$reqMode = $this->input->post("reqMode");
		

		if($reqMode == "ubah_password")
		{
			$reqPassword 			= $this->input->post("reqPassword");
			$reqKonfirmasiPassword 	= $this->input->post("reqKonfirmasiPassword");

			if($reqPassword <> $reqKonfirmasiPassword)
			{
				$this->response(array('status' => 'fail', 'code' => 502, 'message' => 'Konfirmasi password baru tidak sesuai'));
				return;
			}	
			
			$user_login->setField("PEGAWAI_ID", $reqPegawaiId);
			$user_login->setField("USER_PASS", md5($reqKonfirmasiPassword));
			$user_login->setField("LAST_UPDATE_USER", $userMobile->USERNAME);
			
			if($user_login->updatePasswordByPegawaiId()){
				$this->response(array('status' => 'success', 'code' => 200, 'message' => 'Ubah password berhasil'));
				return;
			}
			else{
				$this->response(array('status' => 'fail', 'code' => 502, 'message' => 'Ubah password gagal'));
				return;
			}
			
		}

		if($reqMode == "ubah_foto")
		{
			/* WAJIB UNTUK UPLOAD DATA */
			$this->load->library("FileHandler");
			$file = new FileHandler();
			$FILE_DIR= "uploads/foto_fix/";
			
			$reqLinkFile = $_FILES["reqLinkFile"];
			
			// $uploadFile = $userMobile->ID."PROFIL.".getExtension($reqLinkFile['name']);
			$uploadFile = $userMobile->ID.".".getExtension($reqLinkFile['name']);
			$renameFile = $userMobile->ID.".".getExtension($reqLinkFile['name']);

			if($file->uploadToDir('reqLinkFile', $FILE_DIR, $uploadFile))
			{
				// createThumbnail($FILE_DIR.$uploadFile, $FILE_DIR.$renameFile, 300);
				// unlink($uploadFile);

				$this->response(array('status' => 'success', 'code' => 200, 'message' => 'Ubah foto berhasil'));
				return;
			}
			else{
				$this->response(array('status' => 'fail', 'code' => 502, 'message' => 'Ubah foto gagal'));
				return;
			}
			
		}
				
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