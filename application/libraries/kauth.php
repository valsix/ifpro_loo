<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'kloader.php';
include_once("libraries/nusoap-0.9.5/lib/nusoap.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
class kauth {
    //put your code here
    // private $ldap_config = array('server1'=>array(   'host'=>'10.0.0.11',
    //                                 'useStartTls'=>false,
    //                                 'accountDomainName'=>'pp3.co.id',
    //                                 'accountDomainNameShort'=>'PP3',
    //                                 'accountCanonicalForm'=>3,
    //                                 'baseDn'=>"DC=pp3,DC=co,DC=id"));


        function __construct() {
//        load the auth class
        kloader::load('Zend_Auth');
        kloader::load('Zend_Auth_Storage_Session');
        
//        set the unique storege
        Zend_Auth::getInstance()->setStorage(new Zend_Auth_Storage_Session("an6k4s4puraTNDE"));
    }
    
	public function localAuthenticate($username,$credential, $tokenFirebase="") {
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();
		
		$CI =& get_instance();
		$CI->load->model("Users");	
		$CI->load->model("UserLoginWeb");	
		// $CI->load->model("UserType");
		
		$users = new Users();
		$users->selectByIdPassword($username, md5($credential), $tokenFirebase);
		// echo $users->query;exit;
		
		if($users->firstRow())
		{
			$infousergroupid= $users->getField("USER_GROUP_ID");

            $identity = new stdClass();
            $identity->ID = $users->getField("PEGAWAI_ID");
            $identity->NAMA = $users->getField("NAMA");
            $identity->CABANG_ID = $users->getField("SATUAN_KERJA_ID");
            $identity->CABANG = $users->getField("SATUAN_KERJA");
            $identity->JABATAN = $users->getField("JABATAN");
            $identity->HAK_AKSES = $infousergroupid;
            $identity->LAST_LOGIN = date("d-m-Y H:i:s");
            $identity->USERNAME = $users->getField("PEGAWAI_ID");
            $identity->USER_LOGIN_ID = $users->getField("PEGAWAI_ID");
            $identity->JENIS_KELAMIN = $users->getField("JENIS_KELAMIN");
			
			if($infousergroupid == "")
			{
				$identity->MULTIROLE = "PEGAWAI";
				$identity->USER_GROUP = "PEGAWAI";
			}
			else
			{
				$identity->MULTIROLE = $infousergroupid;
				$identity->USER_GROUP = $infousergroupid;
			}
	
			/* JIKA SEBAGAI PEGAWAI ADALAH LOGIN SEBAGAI DIRINYA SENDIRI */
			$info = new Users();
			$info->selectByPegawai($users->getField("PEGAWAI_ID"));
			$info->firstRow();
			// echo $info->query;exit;
			$identity->SATUAN_KERJA_ID_ASAL_ASLI = $info->getField("SATUAN_KERJA_ID");
			$identity->SATUAN_KERJA_ID_ASAL = $info->getField("SATUAN_KERJA_ID");
			$identity->SATUAN_KERJA_ASAL = $info->getField("SATUAN_KERJA");
			$identity->SATUAN_KERJA_HIRARKI = $info->getField("HIRARKI");
			$identity->SATUAN_KERJA_JABATAN = $info->getField("SATUAN_KERJA_JABATAN");
			$identity->KD_LEVEL = $info->getField("KODE_LEVEL");
			$identity->KD_LEVEL_PEJABAT = $info->getField("KODE_LEVEL_PEJABAT");
			$infokelompokjabatan= $info->getField("KELOMPOK_JABATAN");
			if(empty($infokelompokjabatan))
				$infokelompokjabatan= "KARYAWAN";
			// echo $infokelompokjabatan;exit;
			$identity->KELOMPOK_JABATAN = $infokelompokjabatan;
			$identity->KODE_PARENT = $info->getField("KODE_PARENT");
			$identity->DEPARTEMEN_PARENT_ID = $info->getField("DEPARTEMEN_PARENT_ID");

			$identity->SATKER_ID_NOT_PENGGANTI = $info->getField("SATUAN_KERJA_ID");
			$identity->SATKER_JABATAN_NOT_PENGGANTI = $info->getField("SATUAN_KERJA_JABATAN");
			
			$infosatuankerjaso= $info->getField("SATUAN_KERJA_SO");
			$infosatuankerjaso= str_replace(",", "','", $infosatuankerjaso);
			$infosatuankerjaso= "'".$infosatuankerjaso."'";
			// echo $infosatuankerjaso;exit;

			/* UNTUK DIVISI */
			$divisi= new Users();
			// $divisi->selectByDivisi($info->getField("KODE_SURAT"));
			$divisi->selectByDivisi($infosatuankerjaso);
			// echo $divisi->query;exit();
			$reqNip= $reqDataNip= $reqKelompokJabatan= $reqDataKelompokJabatan= "";
			while($divisi->nextRow())
			{
				if ($divisi->getField("NIP")) 
				{
					$reqDataNip= "'".$divisi->getField("NIP")."'";

					if(empty($reqNip))
						$reqNip= $reqDataNip;
					else
					{
						$reqNip= $reqNip.", ".$reqDataNip;
					}
				}
				if ($divisi->getField("KELOMPOK_JABATAN")) 
				{
					// $reqDataKelompokJabatan= "'".$divisi->getField("KELOMPOK_JABATAN")."'";
					$reqDataKelompokJabatan= $divisi->getField("KELOMPOK_JABATAN");

					if(empty($reqKelompokJabatan))
						$reqKelompokJabatan= $reqDataKelompokJabatan;
					else
					{
						$arrkelompok= explode(",", $reqKelompokJabatan);
						if(in_array($reqDataKelompokJabatan, $arrkelompok)){}
						else
						{
							$reqKelompokJabatan= $reqKelompokJabatan.",".$reqDataKelompokJabatan;
						}
					}
				}
			}

			$reqKelompokJabatan= str_replace(",", "','", $reqKelompokJabatan);
			$reqKelompokJabatan= "'".$reqKelompokJabatan."'";

			// tambahan untuk divisi
			if(!empty($infosatuankerjaso) && in_array("DIVISI", explode(",", $infousergroupid)))
			{
				$statementdivisi= "AND A.DISPOSISI_PARENT_ID = 0
				AND A.SATUAN_KERJA_ID_TUJUAN IN (".$infosatuankerjaso.")
				";
				// echo $statementdivisi;exit;

				$divisi= new Users();
				$divisi->selectdisposisi($statementdivisi);
				// echo $divisi->query;exit();
				while($divisi->nextRow())
				{
					if ($divisi->getField("USER_ID")) 
					{
						$reqDataNip= "'".$divisi->getField("USER_ID")."'";

						if(empty($reqNip))
							$reqNip= $reqDataNip;
						else
						{
							$reqNip= $reqNip.", ".$reqDataNip;
						}
					}
				}
				// echo $reqNip;exit;
			}
			
			$identity->NIP_BY_DIVISI = $reqNip;
			$identity->KELOMPOK_JABATAN_BY_DIVISI = $reqKelompokJabatan;


			/* UNTUK PLT/PLH */
			/*$pltplh = new Users();
			$pltplh->selectByPltPlh($users->getField("NIP"));
			$pltplh->firstRow();
			// echo $pltplh->query;exit;
			$identity->AN_TAMBAHAN = $pltplh->getField("AN_TAMBAHAN");
			// $identity->COBA = "";
			// $CI->session->set_userdata("COBA", "");
			$identity->SATKER_ID_PENGGANTI = $pltplh->getField("SATUAN_KERJA_ID");
			$identity->SATKER_JABATAN_PENGGANTI = $pltplh->getField("JABATAN");*/
			$arrmultijabatan= [];
			$pltplh = new Users();
			$pltplh->selectByPltPlh($users->getField("NIP"));
			// $pltplh->firstRow();
			// echo $pltplh->query;exit;
			while($pltplh->nextRow())
			{
				$infopltplhantambahan= $pltplh->getField("AN_TAMBAHAN");
				$infopltplhsatuankerjaid= $pltplh->getField("SATUAN_KERJA_ID");
				$infopltplhjabatan= $pltplh->getField("JABATAN");

				$arrdata= [];
				$arrdata["AN_TAMBAHAN"]= $infopltplhantambahan;
				$arrdata["SATKER_ID_PENGGANTI"]= $infopltplhsatuankerjaid;
				$arrdata["SATKER_JABATAN_PENGGANTI"]= $infopltplhjabatan;
				array_push($arrmultijabatan, $arrdata);

				$identity->AN_TAMBAHAN = $infopltplhantambahan;
				$identity->SATKER_ID_PENGGANTI = $infopltplhsatuankerjaid;
				$identity->SATKER_JABATAN_PENGGANTI = $infopltplhjabatan;
			}

			// Sekretaris
			// untuk user bantu
			$userbantu = new Users();
			$userbantu->selectByUserBantu($users->getField("NIP"));
			// echo $userbantu->query;exit;
			while($userbantu->nextRow())
			{
				$infojabatanuserbantu= "Sekretaris ".$userbantu->getField("JABATAN");
				$infopltplhantambahan= $infojabatanuserbantu;
				$infopltplhsatuankerjaid= $userbantu->getField("SATUAN_KERJA_ID");
				$infopltplhjabatan= $infojabatanuserbantu;
				$infouserbantu= $userbantu->getField("USER_BANTU");

				$arrdata= [];
				$arrdata["AN_TAMBAHAN"]= $infopltplhantambahan;
				$arrdata["SATKER_ID_PENGGANTI"]= $infopltplhsatuankerjaid;
				$arrdata["SATKER_JABATAN_PENGGANTI"]= $infopltplhjabatan;
				$arrdata["USER_BANTU"]= $infouserbantu;
				array_push($arrmultijabatan, $arrdata);

				$identity->AN_TAMBAHAN = $infopltplhantambahan;
				$identity->SATKER_ID_PENGGANTI = $infopltplhsatuankerjaid;
				$identity->SATKER_JABATAN_PENGGANTI = $infopltplhjabatan;
				$identity->AN_TAMBAHAN = $infopltplhantambahan;
				$identity->USER_BANTU = $infouserbantu;
			}
			// $auth->clearIdentity();
			// print_r($arrmultijabatan);exit;

			$identity->MULTIJABATAN= $arrmultijabatan;

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
				return true;
			}
		}
		else
		{
			return false;		
		}
    }

    public function localcoba($username, $infochange) {
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();
		
		$CI =& get_instance();
		$CI->load->model("Users");	
		$CI->load->model("UserLoginWeb");	
		// $CI->load->model("UserType");
		
		$users = new Users();
		$users->selectByIdInfo($username);
		// echo $users->query;exit;
		
		if($users->firstRow())
		{
			$infousergroupid= $users->getField("USER_GROUP_ID");

            $identity = new stdClass();
            $identity->ID = $users->getField("PEGAWAI_ID");
            $identity->NAMA = $users->getField("NAMA");
            $identity->CABANG_ID = $users->getField("SATUAN_KERJA_ID");
            $identity->CABANG = $users->getField("SATUAN_KERJA");
            $identity->JABATAN = $users->getField("JABATAN");
            $identity->HAK_AKSES = $infousergroupid;
            $identity->LAST_LOGIN = date("d-m-Y H:i:s");
            $identity->USERNAME = $users->getField("PEGAWAI_ID");
            $identity->USER_LOGIN_ID = $users->getField("PEGAWAI_ID");
            $identity->JENIS_KELAMIN = $users->getField("JENIS_KELAMIN");
			
			if($infousergroupid == "")
			{
				$identity->MULTIROLE = "PEGAWAI";
				$identity->USER_GROUP = "PEGAWAI";
			}
			else
			{
				$identity->MULTIROLE = $infousergroupid;
				$identity->USER_GROUP = $infousergroupid;
			}
	
			/* JIKA SEBAGAI PEGAWAI ADALAH LOGIN SEBAGAI DIRINYA SENDIRI */
			$info = new Users();
			$info->selectByPegawai($users->getField("PEGAWAI_ID"));
			$info->firstRow();
			// echo $info->query;exit;
			$identity->SATUAN_KERJA_ID_ASAL_ASLI = $info->getField("SATUAN_KERJA_ID");
			$identity->SATUAN_KERJA_ID_ASAL = $info->getField("SATUAN_KERJA_ID");
			$identity->SATUAN_KERJA_ASAL = $info->getField("SATUAN_KERJA");
			$identity->SATUAN_KERJA_HIRARKI = $info->getField("HIRARKI");
			$identity->SATUAN_KERJA_JABATAN = $info->getField("SATUAN_KERJA_JABATAN");
			$identity->KD_LEVEL = $info->getField("KODE_LEVEL");
			$identity->KD_LEVEL_PEJABAT = $info->getField("KODE_LEVEL_PEJABAT");
			$infokelompokjabatan= $info->getField("KELOMPOK_JABATAN");
			if(empty($infokelompokjabatan))
				$infokelompokjabatan= "KARYAWAN";
			// echo $infokelompokjabatan;exit;
			$identity->KELOMPOK_JABATAN = $infokelompokjabatan;
			$identity->KODE_PARENT = $info->getField("KODE_PARENT");
			$identity->DEPARTEMEN_PARENT_ID = $info->getField("DEPARTEMEN_PARENT_ID");
			
			$identity->SATKER_ID_NOT_PENGGANTI = $info->getField("SATUAN_KERJA_ID");
			$identity->SATKER_JABATAN_NOT_PENGGANTI = $info->getField("SATUAN_KERJA_JABATAN");

			/* UNTUK PLT/PLH */
			/*$pltplh = new Users();
			$pltplh->selectByPltPlh($users->getField("NIP"));
			$pltplh->firstRow();
			// echo $pltplh->query;exit;
			$identity->AN_TAMBAHAN = $pltplh->getField("AN_TAMBAHAN");
			$identity->COBA = "tesss";
			// $CI->session->set_userdata("COBA", "");
			$identity->SATKER_ID_PENGGANTI = $pltplh->getField("SATUAN_KERJA_ID");
			$identity->SATKER_JABATAN_PENGGANTI = $pltplh->getField("JABATAN");

			if($infochange !== "pribadi")
			{
				$identity->CABANG_ID = $pltplh->getField("SATUAN_KERJA_ID_PARENT");
            	$identity->CABANG = $pltplh->getField("UNIT_KERJA_NAMA");
            	$identity->JABATAN = $pltplh->getField("JABATAN");
            	
				$identity->SATUAN_KERJA_ID_ASAL = $pltplh->getField("SATUAN_KERJA_ID");
				$identity->SATUAN_KERJA_JABATAN = $pltplh->getField("JABATAN");
			}*/
			$arrmultijabatan= [];
			$pltplh = new Users();
			$pltplh->selectByPltPlh($users->getField("NIP"));
			// $pltplh->firstRow();
			// echo $pltplh->query;exit;
			while($pltplh->nextRow())
			{
				$infopltplhantambahan= $pltplh->getField("AN_TAMBAHAN");
				$infopltplhsatuankerjaid= $pltplh->getField("SATUAN_KERJA_ID");
				$infopltplhjabatan= $pltplh->getField("JABATAN");

				$arrdata= [];
				$arrdata["AN_TAMBAHAN"]= $infopltplhantambahan;
				$arrdata["SATKER_ID_PENGGANTI"]= $infopltplhsatuankerjaid;
				$arrdata["SATKER_JABATAN_PENGGANTI"]= $infopltplhjabatan;
				array_push($arrmultijabatan, $arrdata);

				$identity->AN_TAMBAHAN = $infopltplhantambahan;
				$identity->SATKER_ID_PENGGANTI = $infopltplhsatuankerjaid;
				$identity->SATKER_JABATAN_PENGGANTI = $infopltplhjabatan;

				if($infochange == $infopltplhantambahan)
				{
					$identity->CABANG_ID = $pltplh->getField("SATUAN_KERJA_ID_PARENT");
	            	$identity->CABANG = $pltplh->getField("UNIT_KERJA_NAMA");
	            	$identity->JABATAN = $infopltplhjabatan;

	            	$identity->KELOMPOK_JABATAN = $pltplh->getField("KELOMPOK_JABATAN");
	            	
					$identity->SATUAN_KERJA_ID_ASAL = $infopltplhsatuankerjaid;
					$identity->SATUAN_KERJA_JABATAN = $infopltplhjabatan;
				}

			}

			// Sekretaris
			// untuk user bantu
			$userbantu = new Users();
			$userbantu->selectByUserBantu($users->getField("NIP"));
			// echo $userbantu->query;exit;
			while($userbantu->nextRow())
			{
				$infojabatanuserbantu= "Sekretaris ".$userbantu->getField("JABATAN");
				$infopltplhantambahan= $infojabatanuserbantu;
				$infopltplhsatuankerjaid= $userbantu->getField("SATUAN_KERJA_ID");
				$infopltplhjabatan= $infojabatanuserbantu;
				$infouserbantu= $userbantu->getField("USER_BANTU");

				$arrdata= [];
				$arrdata["AN_TAMBAHAN"]= $infopltplhantambahan;
				$arrdata["SATKER_ID_PENGGANTI"]= $infopltplhsatuankerjaid;
				$arrdata["SATKER_JABATAN_PENGGANTI"]= $infopltplhjabatan;
				$arrdata["USER_BANTU"]= $infouserbantu;
				array_push($arrmultijabatan, $arrdata);

				$identity->AN_TAMBAHAN = $infopltplhantambahan;
				$identity->SATKER_ID_PENGGANTI = $infopltplhsatuankerjaid;
				$identity->SATKER_JABATAN_PENGGANTI = $infopltplhjabatan;
				$identity->USER_BANTU = $infouserbantu;

				if($infochange == $infopltplhantambahan)
				{
					$identity->CABANG_ID = $userbantu->getField("SATUAN_KERJA_ID_PARENT");
	            	$identity->CABANG = $userbantu->getField("UNIT_KERJA_NAMA");
	            	$identity->JABATAN = $infopltplhjabatan;

	            	$identity->KELOMPOK_JABATAN = $userbantu->getField("KELOMPOK_JABATAN");
	            	
					$identity->SATUAN_KERJA_ID_ASAL = $infopltplhsatuankerjaid;
					$identity->SATUAN_KERJA_JABATAN = $infopltplhjabatan;
					$identity->USER_BANTU = $infouserbantu;
				}
			}
			// $auth->clearIdentity();
			// print_r($arrmultijabatan);exit;

			$identity->MULTIJABATAN= $arrmultijabatan;

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
				return true;
			}
		}
		else
		{
			/* JIKA BUKAN OPERATOR CHECK APAKAH HANYA PEGAWAI */
			$users = new Users();
			$users->selectByIdInfoMobile($username);
			// echo $users->query;exit;
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
          		$identity->JENIS_KELAMIN = $users->getField("JENIS_KELAMIN");

	            /* TOKEN FIREBASE */
	            $identity->TOKEN_FIREBASE = $tokenFirebase;
	            /* TOKEN FIREBASE */

				/* USER_LOGIN_WEB */
				$token = $this->getToken($users->getField("PEGAWAI_ID"));
	            $identity->TOKEN = $token;
	            /* USER_LOGIN_WEB */
				
				/* JIKA SEBAGAI PEGAWAI ADALAH LOGIN SEBAGAI DIRINYA SENDIRI */
				$info = new Users();
				$info->selectByPegawai($users->getField("PEGAWAI_ID"));
				$info->firstRow();
				$identity->SATUAN_KERJA_ID_ASAL_ASLI = $info->getField("SATUAN_KERJA_ID");
				$identity->SATUAN_KERJA_ID_ASAL = $info->getField("SATUAN_KERJA_ID");
				$identity->SATUAN_KERJA_ASAL = $info->getField("SATUAN_KERJA");
				$identity->SATUAN_KERJA_HIRARKI = $info->getField("HIRARKI");
				$identity->SATUAN_KERJA_JABATAN = $info->getField("SATUAN_KERJA_JABATAN");
				$identity->KD_LEVEL = $info->getField("KODE_LEVEL");
				$identity->KD_LEVEL_PEJABAT = $info->getField("KODE_LEVEL_PEJABAT");
				$infokelompokjabatan= $info->getField("KELOMPOK_JABATAN");
				if(empty($infokelompokjabatan))
					$infokelompokjabatan= "KARYAWAN";
				// echo $infokelompokjabatan;exit;
				$identity->KELOMPOK_JABATAN = $infokelompokjabatan;
				// $identity->KELOMPOK_JABATAN = $info->getField("KELOMPOK_JABATAN");
				$identity->KODE_PARENT = $info->getField("KODE_PARENT");
			
				$auth->getStorage()->write($identity);
	
				if($users->getField("PEGAWAI_ID") == "")			
					return false;
				else {
					return true;
				}	
			}
			
		}
    }
	
	public function mobileVerification($reqPegawaiId,$reqToken) {

        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();
		
		$CI =& get_instance();
		$CI->load->model("Users");	
		$CI->load->model("UserLoginWeb");	
		// $CI->load->model("UserType");
		
		$users = new Users();
		$users->selectByIdBypass($reqPegawaiId,$reqToken);
		// echo $users->query;exit;
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
            $identity->JENIS_KELAMIN = $users->getField("JENIS_KELAMIN");
			
			$groupId = $users->getField("USER_GROUP_ID");
			$tokenFirebase = $users->getField("TOKEN_FIREBASE");


			$identity->USER_GROUP = $groupId;
			
			if($groupId == "SEKRETARIS")
			{
				/* JIKA SEBAGAI SEKRETARIS, SEKRETARISNYA SIAPA */
				$info = new Users();
				$info->selectByUserLogin($identity->ID);
				$info->firstRow();
				$identity->SATUAN_KERJA_ID_ASAL = $info->getField("SATUAN_KERJA_ID_ASAL");
				$identity->SATUAN_KERJA_ASAL = $info->getField("SATUAN_KERJA");
				$identity->SATUAN_KERJA_HIRARKI = $info->getField("HIRARKI");
				$identity->SATUAN_KERJA_JABATAN = $info->getField("SATUAN_KERJA_JABATAN");
				$identity->KODE_PARENT = $info->getField("KODE_PARENT");
				$identity->ID_ATASAN  = ($info->getField("NIP_ATASAN"));
				
				
				$identity->KD_LEVEL = $info->getField("KODE_LEVEL");

			}
			else
			{
	
				/* JIKA SEBAGAI PEGAWAI ADALAH LOGIN SEBAGAI DIRINYA SENDIRI */
				$info = new Users();
				$info->selectByPegawai($users->getField("PEGAWAI_ID"));
				$info->firstRow();
				$identity->SATUAN_KERJA_ID_ASAL = $info->getField("SATUAN_KERJA_ID");
				$identity->SATUAN_KERJA_ASAL = $info->getField("SATUAN_KERJA");
				$identity->SATUAN_KERJA_HIRARKI = $info->getField("HIRARKI");
				$identity->SATUAN_KERJA_JABATAN = $info->getField("SATUAN_KERJA_JABATAN");
				$identity->KD_LEVEL = $info->getField("KODE_LEVEL");
				$identity->KD_LEVEL_PEJABAT = $info->getField("KODE_LEVEL_PEJABAT");
				$identity->KELOMPOK_JABATAN = $info->getField("KELOMPOK_JABATAN");
				$identity->KODE_PARENT = $info->getField("KODE_PARENT");

			}

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
    }
    
	public function mobileAuthenticate($username,$credential) {
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();
		
		$CI =& get_instance();
		$CI->load->model("Users");	
		// $CI->load->model("UserType");
		
		$users = new Users();
		$users->selectByIdPasswordMobile($username, md5($credential));
		// echo $users->query;exit;
		if($users->firstRow())
		{
            $identity = new stdClass();
            $identity->ID = $users->getField("PEGAWAI_ID");
            $identity->USER_LOGIN_ID = $users->getField("PEGAWAI_ID");
            $identity->USERNAME = $users->getField("PEGAWAI_ID");
            $identity->USER_GROUP_ID ="PEGAWAI";
            $identity->USER_GROUP = $users->getField("USER_GROUP");


           $arrMultiroleAkses = array();

			if($users->getField("USER_GROUP_ID") == "PEGAWAI" || $users->getField("USER_GROUP_ID") == "ADMIN")
            	$MULTIROLE = "";
            else
            {
            	$MULTIROLE = $users->getField("USER_GROUP_ID").",PEGAWAI";

            	$arrMultirole = explode(",", $MULTIROLE);
            	$x = 0;
            	for($i=0;$i<count($arrMultirole);$i++)
            	{

            		if($arrMultirole[$i] == "ADMIN")
            		{}
            		else
            		{
            			$arrMultiroleAkses[$x] = $arrMultirole[$i];
            			$x++;
            		}

            	}


            }

            $identity->MULTIROLE = $arrMultiroleAkses;



            $identity->PEGAWAI_ID = $users->getField("PEGAWAI_ID");
            $identity->NAMA = $users->getField("NAMA");
            $identity->EMAIL = $users->getField("EMAIL");
            $identity->JABATAN = $users->getField("JABATAN");
            $identity->CABANG_ID = $users->getField("CABANG_ID");
            $identity->CABANG = $users->getField("CABANG");
            $identity->SATUAN_KERJA_ID_ASAL = $users->getField("SATUAN_KERJA_ID_ASAL");
            $identity->SATUAN_KERJA_ASAL = $users->getField("SATUAN_KERJA_ASAL");
            $identity->KODE_LEVEL = $users->getField("KODE_LEVEL");
            $identity->TREE_ID = $users->getField("TREE_ID");
            $identity->TREE_PARENT = $users->getField("TREE_PARENT");
            $identity->KELOMPOK_JABATAN = $users->getField("KELOMPOK_JABATAN");
            $identity->SATUAN_KERJA_ID_PARENT = $users->getField("SATUAN_KERJA_ID_PARENT");
            $identity->KODE_SO = $users->getField("KODE_SO");
            $identity->KODE_PARENT = $users->getField("KODE_PARENT");
            $identity->KODE_SURAT = $users->getField("KODE_SURAT");
            $identity->KODE_SURAT_KELUAR = $users->getField("KODE_SURAT_KELUAR");
            $identity->KD_LEVEL_PEJABAT = $users->getField("KODE_LEVEL_PEJABAT");
            $identity->ID_ATASAN = $users->getField("NIP_ATASAN");
            $identity->LAST_LOGIN = date("d-m-Y H:i:s");
            $identity->HAK_AKSES = "PEGAWAI";
			
            $auth->getStorage()->write($identity);

			if($users->getField("PEGAWAI_ID") == ""){			
				return false;
			}
			else {
				return true;
			}
		}
		else{
			return false;
		}
    }

	public function mobileAuthenticateUserGroup($username,$credential,$reqUserGroup) {
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();
		
		$CI =& get_instance();
		$CI->load->model("Users");	
		// $CI->load->model("UserType");
		
		$users = new Users();
		$users->selectByIdPasswordMobile($username, md5($credential));
		// echo $users->query;exit;
		if($users->firstRow())
		{
            $identity = new stdClass();
            $identity->ID = $users->getField("PEGAWAI_ID");
            $identity->USER_LOGIN_ID = $users->getField("PEGAWAI_ID");
            $identity->USERNAME = $users->getField("PEGAWAI_ID");
            $identity->USER_GROUP_ID = $reqUserGroup;
            $identity->USER_GROUP = $reqUserGroup;

            $identity->PEGAWAI_ID = $users->getField("PEGAWAI_ID");
            $identity->NAMA = $users->getField("NAMA");
            $identity->EMAIL = $users->getField("EMAIL");
            $identity->JABATAN = $users->getField("JABATAN");
            $identity->CABANG_ID = $users->getField("CABANG_ID");
            $identity->CABANG = $users->getField("CABANG");
            $identity->SATUAN_KERJA_ID_ASAL = $users->getField("SATUAN_KERJA_ID_ASAL");
            $identity->SATUAN_KERJA_ASAL = $users->getField("SATUAN_KERJA_ASAL");
            $identity->KODE_LEVEL = $users->getField("KODE_LEVEL");
            $identity->TREE_ID = $users->getField("TREE_ID");
            $identity->TREE_PARENT = $users->getField("TREE_PARENT");
            $identity->KELOMPOK_JABATAN = $users->getField("KELOMPOK_JABATAN");
            $identity->SATUAN_KERJA_ID_PARENT = $users->getField("SATUAN_KERJA_ID_PARENT");
            $identity->KODE_SO = $users->getField("KODE_SO");
            $identity->KODE_PARENT = $users->getField("KODE_PARENT");
            $identity->KODE_SURAT = $users->getField("KODE_SURAT");
            $identity->KODE_SURAT_KELUAR = $users->getField("KODE_SURAT_KELUAR");
            $identity->KD_LEVEL_PEJABAT = $users->getField("KODE_LEVEL_PEJABAT");
            $identity->ID_ATASAN = $users->getField("NIP_ATASAN");
            $identity->LAST_LOGIN = date("d-m-Y H:i:s");
            $identity->HAK_AKSES = "PEGAWAI";


			if($reqUserGroup == "PEGAWAI")
			{
				
				/* JIKA SEBAGAI PEGAWAI ADALAH LOGIN SEBAGAI DIRINYA SENDIRI */
				$info = new Users();
				$info->selectByPegawai($identity->ID);
				$info->firstRow();
				$identity->SATUAN_KERJA_ID_ASAL = $info->getField("SATUAN_KERJA_ID");
				$identity->SATUAN_KERJA_ASAL = $info->getField("SATUAN_KERJA");
				$identity->SATUAN_KERJA_HIRARKI = $info->getField("HIRARKI");
				$identity->SATUAN_KERJA_JABATAN = $info->getField("SATUAN_KERJA_JABATAN");
				$identity->KD_LEVEL = $info->getField("KODE_LEVEL");
				$identity->KD_LEVEL_PEJABAT = $info->getField("KODE_LEVEL_PEJABAT");
				$identity->KELOMPOK_JABATAN = $info->getField("KELOMPOK_JABATAN");
				$identity->KODE_PARENT = $info->getField("KODE_PARENT");
				
				
			}
			elseif($reqUserGroup == "SEKRETARIS")
			{
			
				/* JIKA SEBAGAI SEKRETARIS, SEKRETARISNYA SIAPA */
				$info = new Users();
				$info->selectByUserLogin($identity->ID);
				$info->firstRow();
				$identity->SATUAN_KERJA_ID_ASAL = $info->getField("SATUAN_KERJA_ID_ASAL");
				$identity->SATUAN_KERJA_ASAL = $info->getField("SATUAN_KERJA");
				$identity->SATUAN_KERJA_HIRARKI = $info->getField("HIRARKI");
				$identity->SATUAN_KERJA_JABATAN = $info->getField("SATUAN_KERJA_JABATAN");
				$identity->KODE_PARENT = $info->getField("KODE_PARENT");
				$identity->ID_ATASAN  = ($info->getField("NIP_ATASAN"));
				$identity->KD_LEVEL = $info->getField("KODE_LEVEL");

			}



			
            $auth->getStorage()->write($identity);

			if($users->getField("PEGAWAI_ID") == ""){			
				return false;
			}
			else {
				return true;
			}
		}
		else{
			return false;
		}
    }

    public function multiAkses($groupId) {
      		
        $auth = Zend_Auth::getInstance();
        $CI =& get_instance();
		$CI->load->model("Users");	
		$CI->load->model("SatuanKerja");	

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
		$identity->JENIS_KELAMIN = $auth->getIdentity()->JENIS_KELAMIN;
			
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
		{
			$identity->USER_GROUP = "PEGAWAI";
			
			/* JIKA SEBAGAI PEGAWAI ADALAH LOGIN SEBAGAI DIRINYA SENDIRI */
			$info = new Users();
			$info->selectByPegawai($identity->ID);
			$info->firstRow();
			$identity->SATUAN_KERJA_ID_ASAL = $info->getField("SATUAN_KERJA_ID");
			$identity->SATUAN_KERJA_ASAL = $info->getField("SATUAN_KERJA");
			$identity->SATUAN_KERJA_HIRARKI = $info->getField("HIRARKI");
			$identity->SATUAN_KERJA_JABATAN = $info->getField("SATUAN_KERJA_JABATAN");
			$identity->KD_LEVEL = $info->getField("KODE_LEVEL");
			$identity->KD_LEVEL_PEJABAT = $info->getField("KODE_LEVEL_PEJABAT");
			$identity->KELOMPOK_JABATAN = $info->getField("KELOMPOK_JABATAN");
			$identity->KODE_PARENT = $info->getField("KODE_PARENT");
			
			
		}
		else
		{
			$identity->USER_GROUP = $groupId;
			
			if($groupId == "SEKRETARIS")
			{
				/* JIKA SEBAGAI SEKRETARIS, SEKRETARISNYA SIAPA */
				$info = new Users();
				$info->selectByUserLogin($identity->ID);
				$info->firstRow();
				$identity->SATUAN_KERJA_ID_ASAL = $info->getField("SATUAN_KERJA_ID_ASAL");
				$identity->SATUAN_KERJA_ASAL = $info->getField("SATUAN_KERJA");
				$identity->SATUAN_KERJA_HIRARKI = $info->getField("HIRARKI");
				$identity->SATUAN_KERJA_JABATAN = $info->getField("SATUAN_KERJA_JABATAN");
				$identity->KODE_PARENT = $info->getField("KODE_PARENT");
				$identity->ID_ATASAN  = ($info->getField("NIP_ATASAN"));
				
				
				$identity->KD_LEVEL = $info->getField("KODE_LEVEL");


			}
			
		}
	
		
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