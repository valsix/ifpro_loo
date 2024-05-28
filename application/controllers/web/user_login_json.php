<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class user_login_json extends CI_Controller {

	function __construct() {
		parent::__construct();
		
		if (!$this->kauth->getInstance()->hasIdentity())
		{
			//redirect('login');
		}    
		
		$this->db->query("SET DATESTYLE TO PostgreSQL,European;"); 
		$this->ID				= $this->kauth->getInstance()->getIdentity()->ID;   
		$this->NAMA				= $this->kauth->getInstance()->getIdentity()->NAMA;   
		$this->JABATAN			= $this->kauth->getInstance()->getIdentity()->JABATAN;   
		$this->HAK_AKSES		= $this->kauth->getInstance()->getIdentity()->HAK_AKSES;   
		$this->LAST_LOGIN		= $this->kauth->getInstance()->getIdentity()->LAST_LOGIN;   
		$this->USERNAME			= $this->kauth->getInstance()->getIdentity()->USERNAME;  
		$this->USER_LOGIN_ID	= $this->kauth->getInstance()->getIdentity()->USER_LOGIN_ID;  
		$this->USER_GROUP		= $this->kauth->getInstance()->getIdentity()->USER_GROUP;  
		$this->MULTIROLE		= $this->kauth->getInstance()->getIdentity()->MULTIROLE;  
		$this->CABANG_ID		= $this->kauth->getInstance()->getIdentity()->CABANG_ID;  
		$this->CABANG			= $this->kauth->getInstance()->getIdentity()->CABANG;  
		$this->SATUAN_KERJA_ID_ASAL	= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_ID_ASAL;  
		$this->SATUAN_KERJA_ASAL	= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_ASAL;  
		$this->SATUAN_KERJA_HIRARKI	= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_HIRARKI;  
		$this->SATUAN_KERJA_JABATAN	= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_JABATAN;  
		$this->KD_LEVEL			= $this->kauth->getInstance()->getIdentity()->KD_LEVEL;  
		$this->KD_LEVEL_PEJABAT = $this->kauth->getInstance()->getIdentity()->KD_LEVEL_PEJABAT;  
		$this->JENIS_KELAMIN 	= $this->kauth->getInstance()->getIdentity()->JENIS_KELAMIN;  
		$this->KELOMPOK_JABATAN = $this->kauth->getInstance()->getIdentity()->KELOMPOK_JABATAN;  
		$this->ID_ATASAN 		= $this->kauth->getInstance()->getIdentity()->ID_ATASAN;  
		
	}
	
	function json() 
	{
		$this->load->model("UserLogin");
		$user_login = new UserLogin();

		$reqCabangId = $this->input->get("reqCabangId");
		// echo $reqKategori;exit;
		
		$aColumns		= array("USER_LOGIN_ID","PEGAWAI_ID","NAMA","JABATAN","USER_GROUP_ID","SATUAN_KERJA_ASAL_JABATAN", "STATUS_INFO");
		$aColumnsAlias	= array("USER_LOGIN_ID","PEGAWAI_ID","NAMA","B.JABATAN","USER_GROUP_ID","A.SATUAN_KERJA_ASAL_JABATAN", "STATUS_INFO");
		
		/*
		 * Ordering
		 */
		if ( isset( $_GET['iSortCol_0'] ) )
		{
			$sOrder = " ORDER BY ";
			 
			//Go over all sorting cols
			for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
			{
				//If need to sort by current col
				if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
				{
					//Add to the order by clause
					$sOrder .= $aColumnsAlias[ intval( $_GET['iSortCol_'.$i] ) ];
					 
					//Determine if it is sorted asc or desc
					if (strcasecmp(( $_GET['sSortDir_'.$i] ), "asc") == 0)
					{
						$sOrder .=" asc, ";
					}else
					{
						$sOrder .=" desc, ";
					}
				}
			}
			
			//Remove the last space / comma
			$sOrder = substr_replace( $sOrder, "", -2 );
			
			//Check if there is an order by clause
			if ( trim($sOrder) == "ORDER BY A.USER_LOGIN_ID asc" )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.NAMA asc";
				 
			}
		}
		 
		/*
		 * Filtering
		 * NOTE this does not match the built-in DataTables filtering which does it
		 * word by word on any field. It's possible to do here, but concerned about efficiency
		 * on very large tables.
		 */
		$sWhere = "";
		$nWhereGenearalCount = 0;
		if (isset($_GET['sSearch']))
		{
			$sWhereGenearal = $_GET['sSearch'];
		}
		else
		{
			$sWhereGenearal = '';
		}
		
		if ( $_GET['sSearch'] != "" )
		{
			//Set a default where clause in order for the where clause not to fail
			//in cases where there are no searchable cols at all.
			$sWhere = " AND (";
			for ( $i=0 ; $i<count($aColumnsAlias)+1 ; $i++ )
			{
				//If current col has a search param
				if ( $_GET['bSearchable_'.$i] == "true" )
				{
					//Add the search to the where clause
					$sWhere .= $aColumnsAlias[$i]." LIKE '%".$_GET['sSearch']."%' OR ";
					$nWhereGenearalCount += 1;
				}
			}
			$sWhere = substr_replace( $sWhere, "", -3 );
			$sWhere .= ')';
		}
		 
		/* Individual column filtering */
		$sWhereSpecificArray = array();
		$sWhereSpecificArrayCount = 0;
		for ( $i=0 ; $i<count($aColumnsAlias) ; $i++ )
		{
			if ( $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
			{
				//If there was no where clause
				if ( $sWhere == "" )
				{
					$sWhere = "AND ";
				}
				else
				{
					$sWhere .= " AND ";
				}
				 
				//Add the clause of the specific col to the where clause
				$sWhere .= $aColumnsAlias[$i]." LIKE '%' || :whereSpecificParam".$sWhereSpecificArrayCount." || '%' ";
				 
				//Inc sWhereSpecificArrayCount. It is needed for the bind var.
				//We could just do count($sWhereSpecificArray) - but that would be less efficient.
				$sWhereSpecificArrayCount++;
				 
				//Add current search param to the array for later use (binding).
				$sWhereSpecificArray[] =  $_GET['sSearch_'.$i];
				 
			}
		}
		 
		//If there is still no where clause - set a general - always true where clause
		if ( $sWhere == "" )
		{
			$sWhere = " AND 1=1";
		}
		 
		//Bind variables.
		if ( isset( $_GET['iDisplayStart'] ))
		{
			$dsplyStart = $_GET['iDisplayStart'];
		}
		else{
			$dsplyStart = 0;
		}
		if ( isset( $_GET['iDisplayLength'] ) && $_GET['iDisplayLength'] != '-1' )
		{
			$dsplyRange = $_GET['iDisplayLength'];
			if ($dsplyRange > (2147483645 - intval($dsplyStart)))
			{
				$dsplyRange = 2147483645;
			}

			else
			{
				$dsplyRange = intval($dsplyRange);
			}
		}
		else
		{
			$dsplyRange = 2147483645;
		}
	
		if($this->CABANG_ID == "PST")
		{
			// $statement_privacy .= " AND B.SATUAN_KERJA_ID = '".$reqCabangId."' ";
			$statement_privacy .= " ";
		}
		else
			$statement_privacy .= " AND B.SATUAN_KERJA_ID = '".$this->CABANG_ID."' ";
			
		$statement= " AND (UPPER(A.NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%' OR UPPER(A.PEGAWAI_ID) LIKE '%".strtoupper($_GET['sSearch'])."%')";
		$allRecord = $user_login->getCountByParams(array(), $statement_privacy.$statement);
		// echo $allRecord;exit;
		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter =  $user_login->getCountByParams(array(), $statement_privacy.$statement);
		
		$user_login->selectByParams(array(), $dsplyRange, $dsplyStart, $statement_privacy.$statement, $sOrder);
		
		// echo "IKI ".$_GET['iDisplayStart'];
		
			/*
			 * Output 
			 */
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $allRecord,
			"iTotalDisplayRecords" => $allRecordFilter,
			"aaData" => array()
		);
		
		while($user_login->nextRow())		
		{		
			$row = array();		
			for ( $i=0 ; $i<count($aColumns) ; $i++ )		
			{	
				if($aColumns[$i] == "KETERANGAN")
					$row[] = truncate($user_login->getField($aColumns[$i]), 2);
				else
					$row[] = $user_login->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		echo json_encode( $output );			
	}
	
	function add() 
	{
		$this->load->model("UserLogin");
		$user_login = new UserLogin();
		$reqMode 					= $this->input->post("reqMode");
		$reqId 						= $this->input->post("reqId");
		$reqNama 				 	= $this->input->post("reqNama");
		$reqPegawaiId 				= $this->input->post("reqPegawaiId");
		$reqUserGroupId 			= $this->input->post("reqUserGroupId");
		$reqSatuanKerjaIdAsal 		= $this->input->post("reqSatuanKerjaIdAsal");
		$reqDivisiId= $this->input->post("reqDivisiId");
		
		$this->load->model("Pegawai");
		$pegawai = new Pegawai();
		$pegawai->selectByParams(array("A.PEGAWAI_ID" => $reqPegawaiId));
		$pegawai->firstRow();
		$reqCabangId = $pegawai->getField("SATUAN_KERJA_ID");

		$sudahTerdaftar = $user_login->getCountByParams(array("A.PEGAWAI_ID" => $reqPegawaiId));
		if($sudahTerdaftar > 0)
		{
			//echo "X-Pegawai sudah terdaftar.";	
			//return;
		}
				
		if($reqCabangId == "01")
		{}
		else
		{
			/* CHECK ADA BERAPA ADMIN */	
			if(stristr($reqUserGroupId, "ADMIN"))
			{
				$jumlahAdmin = $user_login->getCountByParams(array("B.SATUAN_KERJA_ID" => $reqCabangId), " AND A.USER_GROUP_ID LIKE '%ADMIN%' AND NOT A.PEGAWAI_ID = '".$reqPegawaiId."' ");
				
				if($jumlahAdmin >= 2)
				{
					echo "X-Admin cabang hanya maksimal 2 (dua).";	
					return;

				}
			}
		}
		
		$user_login->setField("USER_LOGIN_ID", $reqId);
		$user_login->setField("PEGAWAI_ID", $reqPegawaiId);
		$user_login->setField("NAMA", $reqNama);
		$user_login->setField("USER_LOGIN", $reqPegawaiId);
		$user_login->setField("USER_GROUP_ID", $reqUserGroupId);
		$user_login->setField("SATUAN_KERJA_ID_ASAL", $reqSatuanKerjaIdAsal);
		$user_login->setField("USER_PASS", md5($reqPegawaiId."BULOG"));
		$user_login->setField("DIVISI_ID", $reqDivisiId);
				
		if($reqMode == "insert")
		{
			$user_login->setField("LAST_CREATE_USER", $this->USERNAME);
			$user_login->insert();
		}
		else
		{
			$user_login->setField("LAST_UPDATE_USER", $this->USERNAME);
			$user_login->update();
		}	
		
		echo "Y-Data berhasil disimpan.";
	
	}

	function delete() 
	{
		$reqId	= $this->input->get('reqId');
		$this->load->model("UserLogin");
		$user_login = new UserLogin();

		
		$user_login->setField("USER_LOGIN_ID", $reqId);
		if($user_login->delete())
			$arrJson["PESAN"] = "Data berhasil dihapus.";
		else
			$arrJson["PESAN"] = "Data gagal dihapus.";		
		
		echo json_encode($arrJson);
	}	

	function ubah_foto_profil() 
	{
		$this->load->model("UserLogin");
		$user_login = new UserLogin();

		$reqMode 					= $this->input->post("reqMode");
		$reqId 						= $this->input->post("reqId");
				
		/* WAJIB UNTUK UPLOAD DATA */
		include_once("functions/image.func.php");		
		$this->load->library("FileHandler");
		$file = new FileHandler();
		$FILE_DIR = "uploads/";
		$reqLinkFile 			= $_FILES["reqLinkFile"];
		$reqLinkFileTempSize	=  $this->input->post("reqLinkFileTempSize");
		$reqLinkFileTempTipe	=  $this->input->post("reqLinkFileTempTipe");
		$reqLinkFileTemp		=  $this->input->post("reqLinkFileTemp");

		$i=0;
		$reqJenis   = "FOTO-PROFIL";
		$renameFile = date("dmYhis").rand().".".getExtension($reqLinkFile['name'][$i]);
		
	
		if($file->uploadToDirArray('reqLinkFile', $FILE_DIR, $renameFile, $i))
		{	
		
			createThumbnail($FILE_DIR.$renameFile, $FILE_DIR.$reqJenis.$renameFile, 800);
			unlink($FILE_DIR.$renameFile);
			
			$insertLinkSize = $file->uploadedSize;
			$insertLinkTipe =  $file->uploadedExtension;
			$insertLinkFile =  $reqJenis.$renameFile;
		}
		else
		{
			
			$insertLinkSize =  $reqLinkFileTempSize[$i];
			$insertLinkTipe =  $reqLinkFileTempTipe[$i];
			$insertLinkFile =  $reqLinkFileTemp[$i];
		}		
		
		$user_login->setField("PEGAWAI_ID", $reqId);
		$user_login->setField("FOTO", $insertLinkFile);
		$user_login->setField("LAST_UPDATE_USER", $this->USERNAME);
		$user_login->updateFoto();
		
		echo "Data berhasil disimpan.";
	
	}

	function ubah_password() 
	{
		$this->load->model("UserLogin");
		$user_login = new UserLogin();

		$reqPassword = $this->input->post("reqPassword");
		$reqKonfirmasiPassword = $this->input->post("reqKonfirmasiPassword");

		if($reqPassword <> $reqKonfirmasiPassword)
		{
			echo "Konfirmasi password baru tidak sesuai.";	
			return;
		}	
		
		$user_login->setField("PEGAWAI_ID", $this->ID);
		$user_login->setField("USER_PASS", md5($reqKonfirmasiPassword));
		$user_login->setField("LAST_UPDATE_USER", $this->USERNAME);
		$user_login->updatePasswordByPegawaiId();
		
		echo "Data berhasil disimpan.";
	}
	
	
	function combo() 
	{
		$this->load->model("UserLogin");
		$user_login = new UserLogin();

		$user_login->selectByParams(array());
		$i = 0;
		while($user_login->nextRow())
		{
			$arr_json[$i]['id']		= $user_login->getField("USER_LOGIN_ID");
			$arr_json[$i]['text']	= $user_login->getField("NAMA");
			$i++;
		}
		
		echo json_encode($arr_json);
	}

	function ganti() 
	{
		$this->load->model("Users");
		$set = new Users();
		$username 					= $this->ID;
		$reqPass 					= $this->input->post("reqPass");
		$reqNewPass 				= $this->input->post("reqNewPass");

		$set->selectByIdPassword($username,md5($reqPass));
    	$set->firstRow();
    	$reqCek = $set->getField("PEGAWAI_ID");

		if($reqCek != '')
		{
			$set->setField("USER_PASS", md5($reqNewPass));
			$set->setField("USER_LOGIN", $username);
			if($set->updatePassword()){
				echo "x-Data berhasil disimpan.";
			}
		}
		else{
			echo "0-Data gagal disimpan. Password lama salah";
		}
		
	
	}
	
}

