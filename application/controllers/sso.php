<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once("functions/image.func.php");
include_once("functions/string.func.php");

class sso extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->library('kauth');     
	}
	
	function index()
	{
		
		$username = $this->input->post("username");
		$password = $this->input->post("password");
		$c_password = $this->input->post("c_password");

		if(!empty($username))
		{
			$ch = curl_init();
			$data = array("username" => $username, 
						  "password" => $password, 
						  "c_password" => $c_password);
					  
			curl_setopt($ch, CURLOPT_URL, "http://sso.indonesiaferry.id/api/login");
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data'));
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$response = curl_exec($ch);
			curl_close($ch);
			
			$obj = json_decode($response);
			// print_r($obj);exit;
			
			$arrResult = array();
			if($obj->status == "200")
			{
				// buat ngecek duang
				// if($username == "in_f1d145")
				// 	$username = "fahmi.zellyan@indonesiaferry.co.id";
					
				$sql = "
				SELECT 
				'200' code,
				'success' status,
				'".$obj->success."' message,
				'".$obj->token."' token,
				'".$obj->expires_token."' expires_token,
				'".$obj->channel_code."' channel_code,
				A.NIP,
				A.NAMA,
				A.JABATAN,
				A.SATUAN_KERJA_ID,
				B.NAMA SATUAN_KERJA, TREE_ID, TREE_PARENT, B.KODE_LEVEL, 
				KODE_LEVEL, C.SATUAN_KERJA_ID_ASAL, C.USER_GROUP_ID, JENIS_KELAMIN
				FROM PEGAWAI A 
				INNER JOIN SATUAN_KERJA B ON A.SATUAN_KERJA_ID = B.SATUAN_KERJA_ID
				INNER JOIN USER_LOGIN C ON A.PEGAWAI_ID = C.PEGAWAI_ID
				WHERE C.USER_LOGIN = '".$username."' 
				AND 
				(
				C.STATUS = '1' 
				OR 
				COALESCE(NULLIF(C.STATUS, ''), NULL) IS NULL
				)
				";
				// echo $sql;exit;
					   
				$arrResult = $this->db->query($sql)->first_row();
				
				if(count($arrResult) == 0)
				{
					$arrResult = array();
					$arrResult["code"]    = "201";
					$arrResult["status"]  = "failed";
					$arrResult["message"] = "Success login, no data found.";
				}
				
			}
			else
			{
				$arrResult["code"] = $obj->status;
				$arrResult["status"]  = "failed";
				$arrResult["message"] = $obj->error;
			}

			echo json_encode($arrResult);
		}
		
	}
	
}