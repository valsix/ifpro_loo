<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Kauth
 *
 * @author user
 */
class usermobile{
	// var $USER_LOGIN_ID;
	
	var $ID;
	var $NAMA;
	var $CABANG_ID;
	var $CABANG;
	var $JABATAN;
	var $USERNAME;
	var $USER_LOGIN_ID;
	var $USER_LOGIN;
	var $PEGAWAI_ID;
	var $SATUAN_KERJA_ID_ASAL;
	var $SATUAN_KERJA_ASAL;
	
    /******************** CONSTRUCTOR **************************************/
    function usermobile(){
		 $this->emptyProps();
    }

    /******************** METHODS ************************************/
    /** Empty the properties **/
    function emptyProps(){
		$this->ID = "";
		$this->NAMA = "";
		$this->CABANG_ID = "";
		$this->CABANG = "";
		$this->JABATAN = "";
		$this->USERNAME = "";
		$this->USER_LOGIN_ID = "";
		$this->USER_LOGIN = "";
		$this->PEGAWAI_ID = "";
		$this->SATUAN_KERJA_ID_ASAL = "";
		$this->SATUAN_KERJA_ASAL = "";			
    }
		
    
    /** Verify user login. True when login is valid**/
    function getInfo($reqPegawaiId, $reqToken, $reqUserGroup){			
		$CI =& get_instance();
		$CI->load->model("Users");	
		// $CI->load->model("LogPengunjung");
		// $log_pengunjung = new LogPengunjung();	
		
		$users = new Users();
		$users->selectByIdPasswordMobile($reqPegawaiId, md5($reqToken));
		// echo $users->query;exit;
		if($users->firstRow())
		{

			$this->ID = $users->getField("PEGAWAI_ID");
            $this->USER_LOGIN_ID = $users->getField("PEGAWAI_ID");
            $this->USERNAME = $users->getField("PEGAWAI_ID");
            $this->USER_GROUP_ID = $users->getField("USER_GROUP_ID");
            $this->USER_GROUP = $reqUserGroup;
            $this->PEGAWAI_ID = $users->getField("PEGAWAI_ID");
            $this->NAMA = $users->getField("NAMA");
            $this->EMAIL = $users->getField("EMAIL");
            $this->JABATAN = $users->getField("JABATAN");
            $this->CABANG_ID = $users->getField("CABANG_ID");
            $this->CABANG = $users->getField("CABANG");
            $this->SATUAN_KERJA_ID_ASAL_ASLI = $users->getField("CABANG_ID");
            $this->SATUAN_KERJA_ID_ASAL = $users->getField("SATUAN_KERJA_ID_ASAL");
            $this->SATUAN_KERJA_ASAL = $users->getField("SATUAN_KERJA_ASAL");
            $this->KODE_LEVEL = $users->getField("KODE_LEVEL");
            $this->TREE_ID = $users->getField("TREE_ID");
            $this->TREE_PARENT = $users->getField("TREE_PARENT");
            $this->KELOMPOK_JABATAN = $users->getField("KELOMPOK_JABATAN");
            $this->SATUAN_KERJA_ID_PARENT = $users->getField("SATUAN_KERJA_ID_PARENT");
            $this->KODE_SO = $users->getField("KODE_SO");
            $this->KODE_PARENT = $users->getField("KODE_PARENT");
            $this->KODE_SURAT = $users->getField("KODE_SURAT");
            $this->KODE_SURAT_KELUAR = $users->getField("KODE_SURAT_KELUAR");
            $this->KD_LEVEL_PEJABAT = $users->getField("KODE_LEVEL_PEJABAT");
            $this->ID_ATASAN = $users->getField("NIP_ATASAN");
            $this->LAST_LOGIN = date("d-m-Y H:i:s");
            $this->HAK_AKSES = "PEGAWAI";		
		}

    }
			   
}
	
  /***** INSTANTIATE THE GLOBAL OBJECT */
  $userMobile = new usermobile();

?>
