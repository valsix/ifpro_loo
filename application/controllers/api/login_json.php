<?php
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class login_json extends REST_Controller {
 
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
        
    }
    
    // insert new data to entitas
    function index_post() {
        
        $reqUser 			= $this->input->post("reqUser");
        $reqPasswd 			= $this->input->post("reqPasswd");
        $reqDeviceID 		= $this->input->post("reqDeviceID");
        $reqImei 			= $this->input->post("reqImei");
        $reqUserLoginId 	= $this->input->post("reqUserLoginId");
        $reqUserGroupId 	= $this->input->post("reqUserGroupId");
        $reqTokenFirebase 	= $this->input->post("reqTokenFirebase");
        
        $this->load->model('UserLoginMobile');
        $this->load->model('Pegawai');
        $this->load->model('UserLogin');
        $this->load->model('Users');

		$user_login_mobile 	= new UserLoginMobile();
		$user_login 		= new Users();
		$user_login_count 	= new UserLogin();
		$pegawai_atasan 	= new Pegawai();        


        if($reqUserGroupId == "")
        {}
        else
        {
            $respon = $this->kauth->mobileAuthenticateUserGroup($reqUser,$reqPasswd,$reqUserGroupId);
            
            // $reqPegawaiId 		  = $reqUser; // komen kvd
			$reqPegawaiId 		  = $this->kauth->getInstance()->getIdentity()->PEGAWAI_ID;
            $reqUserName 		  = $this->kauth->getInstance()->getIdentity()->USERNAME;
            $reqUserGroupId 	  = $this->kauth->getInstance()->getIdentity()->USER_GROUP_ID;
            $reqUserGroup 		  = $this->kauth->getInstance()->getIdentity()->USER_GROUP;
            $reqNama 			  = $this->kauth->getInstance()->getIdentity()->NAMA;
            $reqEmail 			  = $this->kauth->getInstance()->getIdentity()->EMAIL;
            $reqJabatan 		  = $this->kauth->getInstance()->getIdentity()->JABATAN;
            $reqCabangId 		  = $this->kauth->getInstance()->getIdentity()->CABANG_ID;
            $reqCabang 			  = $this->kauth->getInstance()->getIdentity()->CABANG;
            $reqSatuanKerjaIdAsal = $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_ID_ASAL;
            $reqSatuanKerjaAsal   = $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_ASAL;
            $reqHakAkses 		  = $this->kauth->getInstance()->getIdentity()->HAK_AKSES;
			
            $user_login_mobile = new UserLoginMobile();

            // $user_login_mobile->setField("PEGAWAI_ID", trim($reqUser)); // komen kvd
			$user_login_mobile->setField("PEGAWAI_ID", trim($reqPegawaiId));
            $user_login_mobile->setField("WAKTU_LOGIN", "CURRENT_TIMESTAMP");
            $user_login_mobile->setField("STATUS", "1");
            $user_login_mobile->setField("DEVICE_ID", $reqDeviceID);
            $user_login_mobile->setField("IMEI", $reqImei);
            $user_login_mobile->setField("USER_GROUP_ID", $reqUserGroupId);
            $user_login_mobile->setField("TOKEN_FIREBASE", $reqTokenFirebase);
            
            if($user_login_mobile->insert()) 
            {
                $this->response(array('status' => 'success', 'message' => 'Berhasil Login', 'token' => $user_login_mobile->idToken, 
                    'reqPegawaiId' => $reqPegawaiId, 'reqUserName' => $reqUserName, 'reqUserGroupId' => $reqUserGroupId, 'reqUserGroup' => $reqUserGroup, 
                    'reqNama' => $reqNama, 'reqEmail' => $reqEmail, 'reqJabatan' => $reqJabatan, 'reqCabangId' => $reqCabangId, 'reqCabang' => $reqCabang, 
                    'reqSatuanKerjaIdAsal' => $reqSatuanKerjaIdAsal, 'reqSatuanKerjaAsal' => $reqSatuanKerjaAsal, 'reqHakAkses' => $reqHakAkses, 'code' => 200));
            } 
            else 
            {
                $this->response(array('status' => 'fail', 'message' => 'Gagal Login', 'code' => 502));
            }

        }



        $temp = array();
        if(!empty($reqUser) AND !empty($reqPasswd))
        {
			
            $respon = $this->kauth->mobileAuthenticate($reqUser,$reqPasswd);

            if($respon == "1")
            {
                // $reqPegawaiId = $reqUser;
				$reqPegawaiId = $this->kauth->getInstance()->getIdentity()->PEGAWAI_ID;
                $reqUserName = $this->kauth->getInstance()->getIdentity()->USERNAME;
                $reqUserGroupId = $this->kauth->getInstance()->getIdentity()->USER_GROUP_ID;
                $reqUserGroup = $this->kauth->getInstance()->getIdentity()->USER_GROUP;
                $reqNama = $this->kauth->getInstance()->getIdentity()->NAMA;
                $reqEmail = $this->kauth->getInstance()->getIdentity()->EMAIL;
                $reqJabatan = $this->kauth->getInstance()->getIdentity()->JABATAN;
                $reqCabangId = $this->kauth->getInstance()->getIdentity()->CABANG_ID;
                $reqCabang = $this->kauth->getInstance()->getIdentity()->CABANG;
                $reqSatuanKerjaIdAsal = $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_ID_ASAL;
                $reqSatuanKerjaAsal = $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_ASAL;
                $reqHakAkses = $this->kauth->getInstance()->getIdentity()->HAK_AKSES;
                $reqArrMultirole = $this->kauth->getInstance()->getIdentity()->MULTIROLE;
				
                if(count($reqArrMultirole) > 0)
                {
                    $this->response(array('status' => 'success', 'message' => 'Berhasil Login', 'token' => $user_login_mobile->idToken, 
                        'reqPegawaiId' => $reqPegawaiId, 'reqUserName' => $reqUserName, 'reqPasswd' => $reqPasswd, 'reqMultirole' => $reqArrMultirole, 'code' => 200));
                    return;

                }

				// $user_login_mobile->setField("PEGAWAI_ID", trim($reqUser)); // komen kvd
				$user_login_mobile->setField("PEGAWAI_ID", trim($reqPegawaiId));
				$user_login_mobile->setField("WAKTU_LOGIN", "CURRENT_TIMESTAMP");
				$user_login_mobile->setField("STATUS", "1");
				$user_login_mobile->setField("DEVICE_ID", $reqDeviceID);
				$user_login_mobile->setField("IMEI", $reqImei);
                $user_login_mobile->setField("USER_GROUP_ID", $reqUserGroupId);
				$user_login_mobile->setField("TOKEN_FIREBASE", $reqTokenFirebase);
                if($user_login_mobile->insert()) 
                {
                    $this->response(array('status' => 'success', 'message' => 'Berhasil Login', 'token' => $user_login_mobile->idToken, 
                        'reqPegawaiId' => $reqPegawaiId, 'reqUserName' => $reqUserName, 'reqUserGroupId' => $reqUserGroupId, 'reqUserGroup' => $reqUserGroup, 
                        'reqNama' => $reqNama, 'reqEmail' => $reqEmail, 'reqJabatan' => $reqJabatan, 'reqCabangId' => $reqCabangId, 'reqCabang' => $reqCabang, 
                        'reqSatuanKerjaIdAsal' => $reqSatuanKerjaIdAsal, 'reqSatuanKerjaAsal' => $reqSatuanKerjaAsal, 'reqHakAkses' => $reqHakAkses, 'code' => 200));
                } 
                else 
                {
                    $this->response(array('status' => 'fail', 'message' => 'Gagal Login', 'code' => 502));
                }
            }
            else
            {
                $this->response(array('status' => 'fail', 'message' => 'Username atau Password Anda Salah', 'code' => 502));
            }
            
        }
        else
        {
            $this->response(array('status' => 'fail', 'message' => 'Masukkan Username atau Password.', 'code' => 502));
        }
    }
 
    // update data entitas
    function index_put() {
        $reqToken = $this->input->get('reqToken');
        $reqTokenFirebase = $this->input->get('reqTokenFirebase');

        $this->load->model('UserLoginMobile');

        $user_login_mobile = new UserLoginMobile();

        $reqPegawaiId = $user_login_mobile->getTokenPegawaiId(array("TOKEN" => $reqToken, "STATUS" => '1'));

        if($reqPegawaiId <> "0")
        {
            $user_login_mobile->setField("TOKEN_FIREBASE", $reqTokenFirebase);
            $user_login_mobile->setField("TOKEN", $reqToken);
            $user_login_mobile->updateTokenFirebase();
            $this->response(array('status' => 'success', 'message' => 'Berhasil diupdate'));
        }
        else
        {
            $this->response(array('status' => 'fail', 'message' => 'Gagal diupdate'));
        }
    }
 
    // delete entitas
    function index_delete() {

    }
 
}