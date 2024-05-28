<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once("functions/image.func.php");
include_once("functions/string.func.php");

class divisi extends CI_Controller {

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
		$cabangid= $this->input->post("cabangid");

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

		if($obj->status == "200")
		{
			$tokenUser = $obj->token;
			$channelCode = $obj->channel_code;

			$statement= "";
			if(!empty($cabangid))
				$statement= " and satuan_kerja_id_parent = '".$cabangid."'";

			$sql="
			select satuan_kerja_id divisiid, nama divisi
			from satuan_kerja_fix
			where 1=1 ".$statement." and length(satuan_kerja_id) = 6 order by satuan_kerja_id";
			// echo $sql;exit;
			$infoquery= $this->db->query($sql)->result_array();
			$arrResult= $infoquery;
			// print_r($arrResult);exit;
			header('Content-Type: application/json; charset=utf-8');
			echo json_encode($arrResult);
		}
		else
		{
			$arrResult = array();
			$arrResult["code"] = $obj->status;
			$arrResult["status"]  = "failed";
			$arrResult["message"] = $obj->error;
		}
		//echo json_encode($arrResult);
		exit;
		
	}
	
}