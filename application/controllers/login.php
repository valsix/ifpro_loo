<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once("functions/image.func.php");
include_once("functions/string.func.php");

class login extends CI_Controller {

	function __construct() {
		parent::__construct();
		/* GLOBAL VARIABLE */
		$this->db->query("SET DATESTYLE TO PostgreSQL,European;");
		$this->ID				= $this->kauth->getInstance()->getIdentity()->ID;   
	}
	
	public function index()
	{
		
		$pg = $this->uri->segment(3, "home");
		$reqParse1 = $this->uri->segment(4, "");
		$reqParse2 = $this->uri->segment(5, "");
		$reqParse3 = $this->uri->segment(6, "");
		$reqParse4 = $this->uri->segment(7, "");
		$reqParse5 = $this->uri->segment(5, "");

		$view = array(
			'pg' => $pg,
			'reqParse1' => $reqParse1,
			'reqParse2'	=> $reqParse2,
			'reqParse3'	=> $reqParse3,
			'reqParse4'	=> $reqParse4,
			'reqParse5'	=> $reqParse5
		);	

		$data = array(
			'pg' => $pg,
			'reqParse1' => $reqParse1,
			'reqParse2'	=> $reqParse2,
			'reqParse3'	=> $reqParse3,
			'reqParse4'	=> $reqParse4,
			'reqParse5'	=> $reqParse5
		);	
		
		$this->load->view('login/index', $data);
	}	
	
	public function action()
	{

		$this->load->library("crfs_protect"); $csrf = new crfs_protect('_crfs_login');

		$CI =& get_instance();
		$configdata= $CI->config;
        $configvlxsessfolder= $configdata->config["vlxsessfolder"];
		$reqUser= $this->input->post("reqUser");
		$reqPasswd= $this->input->post("reqPasswd");
		// $reqCaptcha= $this->input->post("reqCaptcha");
		
		if(!empty($reqUser) AND !empty($reqPasswd))
		{
			$respon = $this->kauth->localAuthenticate($reqUser,$reqPasswd);
			// echo $respon; exit;
			if($respon == "1")
			{
				redirect('main/index/login');
			}
			else
			{
				$data['pesan']="username dan password salah.";
				$this->load->view('login/index', $data);	
			}
		}
		else
		{
			$data['pesan']="username dan password salah.";
			$this->load->view('login/index', $data);	
		}
	}


	public function change()
	{
		$reqId = $this->input->post("reqId");
		$respon = $this->kauth->multiAkses($reqId);
		
		if($respon == "1")
			redirect('app');
		else
			redirect('login');
	}

	public function change_satker()
	{
		$reqId = $this->input->post("reqId");
		$this->kauth->localcoba($this->ID, $reqId);
	}


	public function logout()
	{
		$reqUser = $this->kauth->getInstance()->getIdentity()->ID;
		$reqTokenFirebase = $this->kauth->getInstance()->getIdentity()->TOKEN_FIREBASE;

		$this->load->library('PushNotification'); 
		$push_notification = new PushNotification();
		$push_notification->unSubscribeTokenToTopic($reqTokenFirebase, $reqUser);
		
		$this->load->model("UserLoginWeb");
		$user_login_web = new UserLoginWeb();

		$user_login_web->setField("PEGAWAI_ID", $this->ID);
		$user_login_web->setField("STATUS", "0");
		$user_login_web->updateStatusPegawai();

		$this->kauth->getInstance()->clearIdentity();
		redirect ('app');
	}

	public function loadUrl()
	{
		
		$reqFolder = $this->uri->segment(3, "");
		$reqFilename = $this->uri->segment(4, "");
		$reqParse1 = $this->uri->segment(5, "");
		$reqParse2 = $this->uri->segment(6, "");
		$reqParse3 = $this->uri->segment(7, "");
		$reqParse4 = $this->uri->segment(8, "");
		$reqParse5 = $this->uri->segment(9, "");
		$data = array(
			'reqParse1' => urldecode($reqParse1),
			'reqParse2' => urldecode($reqParse2),
			'reqParse3' => urldecode($reqParse3),
			'reqParse4' => urldecode($reqParse4),
			'reqParse5' => urldecode($reqParse5)
		);
		$this->load->view($reqFolder.'/'.$reqFilename, $data);
	}	

	public function getTokenFirebase()
	{
		$reqToken = $this->kauth->getInstance()->getIdentity()->TOKEN;

        $this->load->model('UserLoginWeb');

        $user_login_web = new UserLoginWeb();
		
		$reqPegawaiId = $user_login_web->getTokenFirebase(array("TOKEN" => $reqToken, "STATUS" => '1'));

		echo($reqPegawaiId);
	}	

	public function setTokenFirebase()
	{
		$reqToken = $this->kauth->getInstance()->getIdentity()->TOKEN;
		$reqTokenFirebase = $this->input->post('reqTokenFirebase');

        $this->load->model('UserLoginWeb');

        $user_login_web = new UserLoginWeb();

        $user_login_web->setField("TOKEN", $reqToken);
		$user_login_web->setField("TOKEN_FIREBASE", $reqTokenFirebase);
		if($user_login_web->updateTokenFirebase()){
			echo("1");
		}else{
			echo("0");
		}
		
	}

	function sso()
	{
		$ch = curl_init();
		$data = array("username" => "in_f1d145", 
					  "password" => "@5dp-f1d145", 
					  "c_password" => "@5dp-f1d145");
		$payload = json_encode($data);
					  
		curl_setopt($ch, CURLOPT_URL, "http://sso.indonesiaferry.id/api/login");
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($payload))
		);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		curl_close($ch);
		
		var_dump($response);
		$obj = json_decode($response);
	}

}

