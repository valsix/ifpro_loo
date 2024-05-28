<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'kloader.php';
include_once("libraries/nusoap-0.9.5/lib/nusoap.php");
class kauth {
    //put your code here
    private $ldap_config = array('server1'=>array(   'host'=>'10.0.0.11',
                                    'useStartTls'=>false,
                                    'accountDomainName'=>'pp3.co.id',
                                    'accountDomainNameShort'=>'PP3',
                                    'accountCanonicalForm'=>3,
                                    'baseDn'=>"DC=pp3,DC=co,DC=id"));


        function __construct() {
//        load the auth class
        kloader::load('Zend_Auth');
        kloader::load('Zend_Auth_Storage_Session');
        
//        set the unique storege
        Zend_Auth::getInstance()->setStorage(new Zend_Auth_Storage_Session("eP120curementPp3"));
    }
    
	public function localAuthenticate($username,$credential, $tokenFirebase) {
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();
		
		$CI =& get_instance();
		$CI->load->model("Users");	
		$CI->load->model("UserLoginWeb");	
		// $CI->load->model("UserType");
		
		$users = new Users();
		$users->selectByIdPassword($username, md5($credential));
		// var_dump($users);exit;
		
		if($users->firstRow())
		{
            $identity = new stdClass();
            $identity->ID = $users->getField("PEGAWAI_ID");
            $identity->NAMA = $users->getField("NAMA");
            $identity->CABANG_ID = $users->getField("SATUAN_KERJA_ID");
            $identity->CABANG = $users->getField("SATUAN_KERJA");
            $identity->JABATAN = $users->getField("JABATAN");
            $identity->HAK_AKSES = $users->getField("USER_GROUP_ID");
            $identity->LAST_LOGIN = date("d-m-Y H:i:s");
            $identity->USERNAME = $users->getField("PEGAWAI_ID");
            $identity->USER_LOGIN_ID = $users->getField("PEGAWAI_ID");
            $identity->KD_LEVEL = $users->getField("KODE_LEVEL");
			
			
			
            $identity->USER_GROUP = $users->getField("USER_GROUP_ID");
			$identity->MULTIROLE = "PEGAWAI";
			$arrGroup = explode(",", $users->getField("USER_GROUP_ID"));
			if($arrGroup > 1)
			{
	            $identity->USER_GROUP = "PEGAWAI";
				/* JIKA SEBAGAI PEGAWAI ADALAH LOGIN SEBAGAI DIRINYA SENDIRI */
				$info = new Users();
				$info->selectByPegawai($users->getField("PEGAWAI_ID"));
				$info->firstRow();
				$reqSatuanKerjaId = $info->getField("SATUAN_KERJA_ID");
				$reqKdLevel = $info->getField("KODE_LEVEL");
				
	            $identity->MULTIROLE =  $users->getField("USER_GROUP_ID");
			}

			/* SUBDIT */
            $identity->SATUAN_KERJA_ID_ASAL = $users->getField("SATUAN_KERJA_ID_ASAL");
			$CI->load->model("SatuanKerja");	
			$satuan_kerja = new SatuanKerja();
			$satuan_kerja->selectByParamsSimple(array("SATUAN_KERJA_ID" => $users->getField("SATUAN_KERJA_ID_ASAL")));
			$satuan_kerja->firstRow();
            $identity->SATUAN_KERJA_ASAL = $satuan_kerja->getField("NAMA");
            $identity->SATUAN_KERJA_HIRARKI = $satuan_kerja->getField("HIRARKI");
			
			
			/* TOKEN FIREBASE */
            $identity->TOKEN_FIREBASE = $tokenFirebase;
            /* TOKEN FIREBASE */

			/* USER_LOGIN_WEB */
			$token = $this->getToken($users->getField("PEGAWAI_ID"));
            $identity->TOKEN = $token;
            /* USER_LOGIN_WEB */

			

            $auth->getStorage()->write($identity);

			if($users->getField("PEGAWAI_ID") == "")	
				return false;
			else {
				$this->subscribeTopics($identity->ID, $tokenFirebase);
				return true;
			}
		}
		else
		{

			/* JIKA BUKAN OPERATOR CHECK APAKAH HANYA PEGAWAI */
			$users = new Users();
			$users->selectByIdPasswordMobile($username, md5($credential));
			
			if($users->firstRow())
			{
				
				$identity = new stdClass();
				$identity->ID = $users->getField("PEGAWAI_ID");
				$identity->NAMA = $users->getField("NAMA");
				$identity->CABANG_ID = $users->getField("SATUAN_KERJA_ID");
				$identity->CABANG = $users->getField("SATUAN_KERJA");
				$identity->JABATAN = $users->getField("JABATAN");
				$identity->HAK_AKSES = "PEGAWAI";
				$identity->LAST_LOGIN = date("d-m-Y H:i:s");
				$identity->USERNAME = $users->getField("PEGAWAI_ID");
				$identity->USER_LOGIN_ID = $users->getField("PEGAWAI_ID");
				$identity->USER_GROUP ="PEGAWAI";
				$identity->SATUAN_KERJA_ID_ASAL = $users->getField("SATUAN_KERJA_ID_ASAL");
          		$identity->KD_LEVEL = $users->getField("KODE_LEVEL");
				
				/* TOKEN FIREBASE */
	            $identity->TOKEN_FIREBASE = $tokenFirebase;
	            /* TOKEN FIREBASE */
				
				$CI->load->model("SatuanKerja");	
				$satuan_kerja = new SatuanKerja();
				$satuan_kerja->selectByParamsSimple(array("SATUAN_KERJA_ID" => $users->getField("SATUAN_KERJA_ID_ASAL")));
				$satuan_kerja->firstRow();
				$identity->SATUAN_KERJA_ASAL = $satuan_kerja->getField("NAMA");
          		$identity->SATUAN_KERJA_HIRARKI = $satuan_kerja->getField("HIRARKI");
				
				
				$auth->getStorage()->write($identity);
	
				if($users->getField("PEGAWAI_ID") == "")			
					return false;
				else {
					$this->subscribeTopics($identity->ID, $tokenFirebase);
					return true;
				}	
			}
			
		}
    }
	
	
    
	public function mobileAuthenticate($username,$credential) {
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();
		
		$CI =& get_instance();
		$CI->load->model("Users");	
		// $CI->load->model("UserType");
		
		$users = new Users();
		$users->selectByIdPasswordMobile($username, md5($credential));
		
		if($users->firstRow())
		{
			
            $identity = new stdClass();
            $identity->ID = $users->getField("PEGAWAI_ID");
            $identity->NAMA = $users->getField("NAMA");
            $identity->CABANG_ID = $users->getField("SATUAN_KERJA_ID");
            $identity->CABANG = $users->getField("SATUAN_KERJA");
            $identity->JABATAN = $users->getField("JABATAN");
            $identity->HAK_AKSES = "PEGAWAI";
            $identity->LAST_LOGIN = date("d-m-Y H:i:s");
            $identity->USERNAME = $users->getField("PEGAWAI_ID");
            $identity->USER_LOGIN_ID = $users->getField("PEGAWAI_ID");
            $identity->USER_GROUP ="PEGAWAI";
            $identity->SATUAN_KERJA_ID_ASAL = $users->getField("SATUAN_KERJA_ID_ASAL");
			
			
			$CI->load->model("SatuanKerja");	
			$satuan_kerja = new SatuanKerja();
			$satuan_kerja->selectByParamsSimple(array("SATUAN_KERJA_ID" => $users->getField("SATUAN_KERJA_ID_ASAL")));
			$satuan_kerja->firstRow();
            $identity->SATUAN_KERJA_ASAL = $satuan_kerja->getField("NAMA");
			
			
            $auth->getStorage()->write($identity);

			if($users->getField("PEGAWAI_ID") == "")			
				return false;
			else	
				return true;
		}
		else
			return false;
    }

    public function multiAkses($groupId) {
      		
        $auth = Zend_Auth::getInstance();
        $CI =& get_instance();
		

		$identity = new stdClass();
		$identity->USER_LOGIN_ID = $auth->getIdentity()->USER_LOGIN_ID;
		


		$identity = new stdClass();
		$identity->ID = $auth->getIdentity()->ID;
		$identity->NAMA = $auth->getIdentity()->NAMA;
		$identity->CABANG_ID = $auth->getIdentity()->CABANG_ID;
		$identity->CABANG = $auth->getIdentity()->CABANG;
		$identity->JABATAN = $auth->getIdentity()->JABATAN;
		$identity->HAK_AKSES = $auth->getIdentity()->HAK_AKSES;
		$identity->LAST_LOGIN = date("d-m-Y H:i:s");
		$identity->USERNAME = $auth->getIdentity()->USERNAME;
		$identity->USER_LOGIN_ID = $auth->getIdentity()->USER_LOGIN_ID;
		$identity->SATUAN_KERJA_ID_ASAL = $auth->getIdentity()->SATUAN_KERJA_ID_ASAL;
		$identity->MULTIROLE = $auth->getIdentity()->MULTIROLE;
		
		/* CHECK APAKAH MEMANG PUNYA ROLE, KALAU TIDAK PUNYA BERARTI PENIPU HATI */
		$arrGroup = explode(",", $identity->MULTIROLE);
		$adaAkses = 0;
		for($i=0;$i<count($arrGroup);$i++)
		{
			if($arrGroup[$i] == $groupId)
				$adaAkses++;
		}
		if($adaAkses == 0)
			$identity->USER_GROUP = "PEGAWAI";
		else
			$identity->USER_GROUP = $groupId;
	
		$identity->SATUAN_KERJA_ASAL = $auth->getIdentity()->SATUAN_KERJA_ASAL;
		
		
		$auth->getStorage()->write($identity);
		return "1";						
		
    }	
	
    public function getInstance(){
        return Zend_Auth::getInstance();
    }

    public function getToken($reqUser)
    {
    	$user_login_web = new UserLoginWeb();

		$user_login_web->setField("PEGAWAI_ID", trim($reqUser));
		$user_login_web->setField("WAKTU_LOGIN", "CURRENT_TIMESTAMP");
		$user_login_web->setField("STATUS", "1");
		$user_login_web->insert();

		return $user_login_web->idToken;
    }

    public function subscribeTopics($topic, $token)
    {
    	$curlUrl = "https://iid.googleapis.com/iid/v1:batchAdd";
		$mypush = array(
			"to" => "/topics/".$topic, 
			"registration_tokens" => array($token)
		);
		$myjson = json_encode($mypush);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $curlUrl);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_POST, True);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $myjson);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization:key='. API_ACCESS_KEY));
		//getting response from server
		$response = curl_exec($ch);
		// var_dump($response); exit();
    }


    public function unSubscribeTopics()
    {
    	$auth = Zend_Auth::getInstance();
    	$topic = $auth->getIdentity()->ID;
    	$token = $auth->getIdentity()->TOKEN_FIREBASE;

    	$curlUrl = "https://iid.googleapis.com/iid/v1:batchRemove";
		$mypush = array(
			"to" => "/topics/".$topic, 
			"registration_tokens" => array($token)
		);
		$myjson = json_encode($mypush);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $curlUrl);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_POST, True);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $myjson);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization:key='. API_ACCESS_KEY));
		//getting response from server
		$response = curl_exec($ch);
    }
}

?>
