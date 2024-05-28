<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once("functions/image.func.php");
include_once("functions/string.func.php");

class sso_new extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->library('kauth');     
	}

	function setsatuankerjanama($cabangid, $id)
	{
		$sql="
		select *
		from satuan_kerja_fix
		where satuan_kerja_id_parent = '".$cabangid."' and satuan_kerja_id = '".$id."'";
		$infoquery= $this->db->query($sql)->first_row();
		return $infoquery->nama;
		// print_r($infoquery);exit;
	}
	
	function index()
	{
		$username= $this->input->post("username");
		$password= $this->input->post("password");

		if(!empty($username))
		{
			$ch = curl_init();
			$data = array("username" => "in_30ff1c3", 
					"password" => "@5dp-30ff1c3", 
					"c_password" => "@5dp-30ff1c3");

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
				$tokenUser = $obj->token;
				$channelCode = $obj->channel_code;

				$ch = curl_init();
				$data = array("channel_code" => $channelCode, 
							  "email" => $username,
							  "password" => $password);
				$payload = json_encode($data);
							  
				curl_setopt($ch, CURLOPT_URL, "http://sso.indonesiaferry.id/api/access");
				curl_setopt($ch, CURLINFO_HEADER_OUT, true);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
					'Authorization: Bearer '.$tokenUser,
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
				$obj = json_decode($response);
				// print_r($obj);exit;
				if($obj->status == "200")
				{
					$sql="
					select
					a.pegawai_id id, a.nama, a.jenis_kelamin jeniskelamin, a.email
					, departemen_id jabatanid, a.jabatan, a.satuan_kerja_id cabangid, cb.nama cabang
					from pegawai a
					left join
					(
						select * from satuan_kerja where satuan_kerja_id_parent = 'SATKER'
					) cb on a.satuan_kerja_id = cb.satuan_kerja_id
					where a.email = '".$username."'";

					$infoquery= $this->db->query($sql)->first_row();
					// print_r($infoquery);exit;
					$arrResult= $infoquery;

					$cabangid= $infoquery->cabangid;
					$departemenawal= $infoquery->jabatanid;

					$setid= substr($departemenawal, 0,4);
					$direktoratiid= $setid;
					$direktorat= $this->setsatuankerjanama($cabangid, $setid);

					$setid= substr($departemenawal, 0,6);
					$divisiid= $setid;
					$divisi= $this->setsatuankerjanama($cabangid, $setid);

					$setid= substr($departemenawal, 0,8);
					$subdivisiid= $setid;
					$subdivisi= $this->setsatuankerjanama($cabangid, $setid);

					$arrResult->{'subdivisiid'} = $subdivisiid;
					$arrResult->{'subdivisi'} = $subdivisi;
					$arrResult->{'divisiid'} = $divisiid;
					$arrResult->{'divisi'} = $divisi;
					$arrResult->{'direktoratiid'} = $direktoratiid;
					$arrResult->{'direktorat'} = $direktorat;

					// print_r($arrResult);exit;
				}
				else
				{
					$arrResult["code"] = $obj->status;
					$arrResult["status"]  = "failed";
					$arrResult["message"] = $obj->error;
				}
			}
			else
			{
				$arrResult["code"] = $obj->status;
				$arrResult["status"]  = "failed";
				$arrResult["message"] = $obj->error;
			}
			echo json_encode($arrResult);
			exit;

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
			print_r($obj);exit;
			
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