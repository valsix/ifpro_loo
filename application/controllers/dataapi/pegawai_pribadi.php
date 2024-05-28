<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once("functions/image.func.php");
include_once("functions/string.func.php");

class pegawai_pribadi extends CI_Controller {

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

	function setkelompokinfo($cabangid, $kelompokjabatan)
	{
		$sql="
		select a.satuan_kerja_kelompok_id kelompokid, a.nama kelompok
		from satuan_kerja_kelompok a
		where 
		exists
		(
			select 1
			from
			(
				select satuan_kerja_kelompok_id
				from
				(
					select a.satuan_kerja_kelompok_id
					from satuan_kerja_kelompok_group a
					where kelompok_jabatan = '".$kelompokjabatan."'
					and exists
					(
						select 1
						from satuan_kerja_kelompok_detil x
						where x.satuan_kerja_id = '".$cabangid."' and a.satuan_kerja_kelompok_id = x.satuan_kerja_kelompok_id
					)
				) a
				group by satuan_kerja_kelompok_id
			) x where a.satuan_kerja_kelompok_id = x.satuan_kerja_kelompok_id
		)
		order by a.satuan_kerja_kelompok_id";
		$infoquery= $this->db->query($sql)->result_array();
		return $infoquery;
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
					, sk.kelompok_jabatan kode_jabatan
					from pegawai a
					left join
					(
						select * from satuan_kerja where satuan_kerja_id_parent = 'SATKER'
					) cb on a.satuan_kerja_id = cb.satuan_kerja_id
					inner join satuan_kerja_fix sk on a.departemen_id = sk.satuan_kerja_id and a.pegawai_id = sk.nip
					where a.email = '".$username."'";

					$infoquery= $this->db->query($sql)->first_row();
					// print_r($infoquery);exit;
					$arrResult= $infoquery;

					$cabangid= $infoquery->cabangid;
					$departemenawal= $infoquery->jabatanid;
					$kodejabatan= $infoquery->kode_jabatan;

					$setid= substr($departemenawal, 0,4);
					$direktoratiid= $setid;
					$direktorat= $this->setsatuankerjanama($cabangid, $setid);

					$setid= substr($departemenawal, 0,6);
					$divisiid= $setid;
					$divisi= $this->setsatuankerjanama($cabangid, $setid);

					$setid= substr($departemenawal, 0,8);
					$subdivisiid= $setid;
					$subdivisi= $this->setsatuankerjanama($cabangid, $setid);

					unset($arrResult->kode_jabatan);

					// $arrResult->{'kelompok_info'} = $this->setkelompokinfo($cabangid, $kodejabatan);
					$kelompokid= $kelompok= "";
					$setkelompokinfo= $this->setkelompokinfo($cabangid, $kodejabatan);
					// print_r($setkelompokinfo);exit;
					if(!empty($setkelompokinfo))
					{
						$kelompokid= $setkelompokinfo[0]["kelompokid"];
						$kelompok= $setkelompokinfo[0]["kelompok"];
					}
					$arrResult->{'kelompokid'} = $kelompokid;
					$arrResult->{'kelompok'} = $kelompok;

					$arrResult->{'subdivisiid'} = $subdivisiid;
					$arrResult->{'subdivisi'} = $subdivisi;
					$arrResult->{'divisiid'} = $divisiid;
					$arrResult->{'divisi'} = $divisi;
					$arrResult->{'direktoratid'} = $direktoratiid;
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
		}
		
	}
	
}