<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class combo_json extends CI_Controller {

	function __construct() {
		parent::__construct();
		
		//kauth
		if (!$this->kauth->getInstance()->hasIdentity())
		{
			// trow to unauthenticated page!
			redirect('login');
		}       
		
		/* GLOBAL VARIABLE */ 
		
		$this->ID = $this->kauth->getInstance()->getIdentity()->ID;   
		$this->USER_LOGIN_ID = $this->kauth->getInstance()->getIdentity()->USER_LOGIN_ID;   
		$this->USER_LOGIN = $this->kauth->getInstance()->getIdentity()->USER_LOGIN;   
		$this->NRP = $this->kauth->getInstance()->getIdentity()->USER_LOGIN_ID;   
		$this->USER_NAMA = $this->kauth->getInstance()->getIdentity()->USER_NAMA;   
		$this->USER_TYPE_ID = $this->kauth->getInstance()->getIdentity()->USER_TYPE_ID;  
		$this->USER_TYPE = $this->kauth->getInstance()->getIdentity()->USER_TYPE;  
		$this->UNIT_KERJA_ID = $this->kauth->getInstance()->getIdentity()->UNIT_KERJA_ID; 
		$this->UNIT_KERJA = $this->kauth->getInstance()->getIdentity()->UNIT_KERJA;         
	}	
	
	function comboSifatNaskah() 
	{
		$i = 0;
		$arr_json[$i]['id']		= "Biasa";
		$arr_json[$i]['text']	= "Biasa";
		$i++;
		$arr_json[$i]['id']		= "Segera";
		$arr_json[$i]['text']	= "Segera";
		$i++;
		$arr_json[$i]['id']		= "Rahasia";
		$arr_json[$i]['text']	= "Rahasia";
		$i++;
		$arr_json[$i]['id']		= "Sangat Rahasia";
		$arr_json[$i]['text']	= "Sangat Rahasia";
		
		echo json_encode($arr_json);
	}

	function comboaksibalasan() 
	{
		$i = 0;
		$arr_json[$i]['id']		= "";
		$arr_json[$i]['text']	= "";
		$i++;
		$arr_json[$i]['id']		= "1";
		$arr_json[$i]['text']	= "Ya";
		$i++;
		$arr_json[$i]['id']		= "2";
		$arr_json[$i]['text']	= "Tidak";
		$i++;
		
		echo json_encode($arr_json);
	}
	
	function comboUserGroup() 
	{
		$i = 0;
		$arr_json[$i]['id']		= "ADMIN";
		$arr_json[$i]['text']	= "Administrator";
		$i++;
		$arr_json[$i]['id']		= "SEKRETARIS";
		$arr_json[$i]['text']	= "Sekretaris";
		$i++;
		$arr_json[$i]['id']		= "TATAUSAHA";
		$arr_json[$i]['text']	= "Tata Usaha";
		$i++;
		$arr_json[$i]['id']		= "PEGAWAI";
		$arr_json[$i]['text']	= "Pegawai";
		$i++;
		$arr_json[$i]['id']		= "SURAT";
		$arr_json[$i]['text']	= "Admin Surat";
		$i++;
		echo json_encode($arr_json);
	}
		
	
	function comboTandaTangan() 
	{
		$i = 0;
		$arr_json[$i]['id']		= "QRCODE";
		$arr_json[$i]['text']	= "QR Code";
		$i++;
		$arr_json[$i]['id']		= "BASAH";
		$arr_json[$i]['text']	= "Tanda Tangan Basah";
		$i++;
		echo json_encode($arr_json);
	}
		
	
	function comboTandaTanganEksternal() 
	{
		$i = 0;
		$arr_json[$i]['id']		= "BASAH";
		$arr_json[$i]['text']	= "Tanda Tangan Basah";
		$i++;
		echo json_encode($arr_json);
	}
		
	function comboJenisTTD() 
	{

		$reqId = $this->input->get("reqId");

		if($reqId == ""){
			$i = 0;
			$arr_json[$i]['id']		= "QRCODE";
			$arr_json[$i]['text']	= "QR Code";
			$i++;
			$arr_json[$i]['id']		= "BASAH";
			$arr_json[$i]['text']	= "Tanda Tangan Basah";
			$i++;
		}
		elseif($reqId == "QRCODE"){
			$i = 0;
			$arr_json[$i]['id']		= "QRCODE";
			$arr_json[$i]['text']	= "QR Code";
			$i++;
		}
		elseif($reqId == "BASAH"){
			$i = 0;
			$arr_json[$i]['id']		= "BASAH";
			$arr_json[$i]['text']	= "Tanda Tangan Basah";
			$i++;
		}
		else{
			$i = 0;
			$arr_json[$i]['id']		= "QRCODE";
			$arr_json[$i]['text']	= "QR Code";
			$i++;
			$arr_json[$i]['id']		= "BASAH";
			$arr_json[$i]['text']	= "Tanda Tangan Basah";
			$i++;
		}
		

		

		echo json_encode($arr_json);
	}
	
	
	function comboLevel() 
	{
		$i = 0;
		$arr_json[$i]['id']		= "DIRUT";
		$arr_json[$i]['text']	= "Direktur Utama";
		$i++;
		$arr_json[$i]['id']		= "DIREKSI";
		$arr_json[$i]['text']	= "Direksi";
		$i++;
		$arr_json[$i]['id']		= "VP";
		$arr_json[$i]['text']	= "Setara VP";
		$i++;
		$arr_json[$i]['id']		= "SM";
		$arr_json[$i]['text']	= "Setara SM";
		$i++;
		$arr_json[$i]['id']		= "MAN";
		$arr_json[$i]['text']	= "Setara Manager";
		$i++;
		echo json_encode($arr_json);
	}
		
	
	function comboLevelCabang() 
	{
		$i = 0;
		$arr_json[$i]['id']		= "GM";
		$arr_json[$i]['text']	= "General Manager";
		$i++;
		$arr_json[$i]['id']		= "SM";
		$arr_json[$i]['text']	= "Setara SM";
		$i++;
		$arr_json[$i]['id']		= "MANAGER";
		$arr_json[$i]['text']	= "Setara Manager";
		$i++;
		echo json_encode($arr_json);
	}
		
	
	function comboTipeNaskah() 
	{
		$i = 0;
		$arr_json[$i]['id']		= "INTERNAL";
		$arr_json[$i]['text']	= "Internal";
		$i++;
		$arr_json[$i]['id']		= "EKSTERNAL";
		$arr_json[$i]['text']	= "Eksternal";
		$i++;
		echo json_encode($arr_json);
	}
		
	function comboPenerbitNomor() 
	{
		$i = 0;
		$arr_json[$i]['id']		= "TATAUSAHA";
		$arr_json[$i]['text']	= "Tata Usaha";
		$i++;
		$arr_json[$i]['id']		= "SEKRETARIS";
		$arr_json[$i]['text']	= "Sekretaris";
		$i++;
		echo json_encode($arr_json);
	}
		
		
	
	function comboPenyampaianSurat() 
	{
		$i = 0;
		$arr_json[$i]['id']		= "APLIKASI";
		$arr_json[$i]['text']	= "Aplikasi";
		$i++;
		$arr_json[$i]['id']		= "HARDCOPY";
		$arr_json[$i]['text']	= "Hardcopy";
		$i++;
		echo json_encode($arr_json);
	}
		
	
	function comboStatusKlasifikasi() 
	{
		$i = 0;
		$arr_json[$i]['id']		= "1";
		$arr_json[$i]['text']	= "Aktif";
		$i++;
		$arr_json[$i]['id']		= "0";
		$arr_json[$i]['text']	= "Tampilkan Non-Aktif";
		$i++;
		echo json_encode($arr_json);
	}
		
		
	function comboKodeKonten() 
	{
		$i = 0;
		$arr_json[$i]['id']		= "PETUNJUK";
		$arr_json[$i]['text']	= "Petunjuk Penggunaan";
		$i++;
		$arr_json[$i]['id']		= "KEAMANAN";
		$arr_json[$i]['text']	= "Klasifikasi Keamanan";
		$i++;
		$arr_json[$i]['id']		= "HAK_AKSES";
		$arr_json[$i]['text']	= "Hak Akses Arsip";
		$i++;
		$arr_json[$i]['id']		= "VISI";
		$arr_json[$i]['text']	= "Visi";
		$i++;
		$arr_json[$i]['id']		= "MISI";
		$arr_json[$i]['text']	= "Misi";
		$i++;
		echo json_encode($arr_json);
	}	
	
		
}

