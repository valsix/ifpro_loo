<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class pegawai_json extends CI_Controller {

	function __construct() {
		parent::__construct();
		
		if (!$this->kauth->getInstance()->hasIdentity())
		{
			//edirect('login');
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
		$this->CABANG_ID		= $this->kauth->getInstance()->getIdentity()->CABANG_ID;  
		$this->CABANG		= $this->kauth->getInstance()->getIdentity()->CABANG;  
		
		
	}
	
	function json() 
	{
		$this->load->model("Pegawai");
		$pegawai = new Pegawai();

		$reqCabangId = $this->input->get("reqCabangId");
		$reqDepartemenId = $this->input->get("reqDepartemenId");
		$reqJenis = $this->input->get("reqJenis");
		// echo $reqKategori;exit;
		
		$aColumns		= array("PEGAWAI_ID", "NIP", "NAMA", "JABATAN", "SATUAN_KERJA", "DEPARTEMEN", "JENIS_PEGAWAI", "EMAIL");
		$aColumnsAlias	= $aColumns;

		
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
			if ( trim($sOrder) == "ORDER BY PEGAWAI_ID asc" )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY CASE 
								WHEN UPPER(A.JABATAN) LIKE '%KOMISARIS UTAMA%' THEN  1 
								WHEN UPPER(A.JABATAN) LIKE '%KOMISARIS%' THEN  2 
								WHEN UPPER(A.JABATAN) LIKE '%DIREKTUR UTAMA%' THEN  3 
								WHEN UPPER(A.JABATAN) LIKE '%DIREKTUR%' THEN  4 
								WHEN UPPER(A.JABATAN) LIKE '%PRESIDENT%' THEN  5 
								WHEN UPPER(A.JABATAN) LIKE '%GENERAL MANAGER%' THEN  5 
								WHEN UPPER(A.JABATAN) LIKE '%SENIOR%' THEN  6
								WHEN UPPER(A.JABATAN) LIKE '%HEAD%' THEN  7 
								WHEN UPPER(A.JABATAN) LIKE '%MANAGER%' THEN 8
								ELSE 99 END ASC, A.DEPARTEMEN_ID";
				 
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

		if($reqCabangId == "")
		{}
		else
			$statement_privacy .= " AND A.SATUAN_KERJA_ID = '".$reqCabangId."' ";

		if($reqDepartemenId == "")
		{}
		else
			$statement_privacy .= " AND EXISTS(SELECT 1 FROM (SELECT TRIM(UPPER(regexp_split_to_table(AMBIL_HIRARKI_SO('".$reqDepartemenId."'), ','))) AS DEPARTEMEN_ID) X 
							 WHERE X.DEPARTEMEN_ID = A.DEPARTEMEN_ID) ";

		if(!empty($reqJenis))
		{
			$statement_privacy .= " AND A.JENIS_PEGAWAI = '".$reqJenis."'";
		}

		
		$statement= " AND (UPPER(A.NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%' OR UPPER(A.NIP) LIKE '%".strtoupper($_GET['sSearch'])."%' OR UPPER(A.JABATAN) LIKE '%".strtoupper($_GET['sSearch'])."%')";
		$allRecord = $pegawai->getCountByParams(array(), $statement_privacy.$statement);
		//echo $allRecord;exit;
		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter =  $pegawai->getCountByParams(array(), $statement_privacy.$statement);
		
		 $pegawai->selectByParamsMonitoring(array(), $dsplyRange, $dsplyStart, $statement_privacy.$statement, $sOrder);

		 // echo $pegawai->query;exit;
		
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
		
		while($pegawai->nextRow())		
		{		
			$row = array();		
			for ( $i=0 ; $i<count($aColumns) ; $i++ )		
			{	
				if($aColumns[$i] == "KETERANGAN")
					$row[] = truncate($pegawai->getField($aColumns[$i]), 2);
				else
					$row[] = $pegawai->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		echo json_encode( $output );	

		
	}


	
	function json_non_pegawai() 
	{
		$this->load->model("Pegawai");
		$pegawai = new Pegawai();

		$reqCabangId = $this->input->get("reqCabangId");
		$reqDepartemenId = $this->input->get("reqDepartemenId");
		// echo $reqKategori;exit;
		
		$aColumns		= array("PEGAWAI_ID", "NIP", "NAMA", "JABATAN", "SATUAN_KERJA", "DEPARTEMEN");
		$aColumnsAlias	= array("PEGAWAI_ID", "NIP", "NAMA", "JABATAN", "SATUAN_KERJA", "DEPARTEMEN");

		
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
			if ( trim($sOrder) == "ORDER BY PEGAWAI_ID asc" )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY CASE 
								WHEN UPPER(A.JABATAN) LIKE '%KOMISARIS UTAMA%' THEN  1 
								WHEN UPPER(A.JABATAN) LIKE '%KOMISARIS%' THEN  2 
								WHEN UPPER(A.JABATAN) LIKE '%DIREKTUR UTAMA%' THEN  3 
								WHEN UPPER(A.JABATAN) LIKE '%DIREKTUR%' THEN  4 
								WHEN UPPER(A.JABATAN) LIKE '%PRESIDENT%' THEN  5 
								WHEN UPPER(A.JABATAN) LIKE '%GENERAL MANAGER%' THEN  5 
								WHEN UPPER(A.JABATAN) LIKE '%SENIOR%' THEN  6
								WHEN UPPER(A.JABATAN) LIKE '%HEAD%' THEN  7 
								WHEN UPPER(A.JABATAN) LIKE '%MANAGER%' THEN 8
								ELSE 99 END ASC, A.DEPARTEMEN_ID";
				 
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

		$statement_privacy = " AND SOURCE_DATA = 'IMPORT' ";

		if($reqCabangId == "")
		{}
		else
			$statement_privacy .= " AND A.SATUAN_KERJA_ID = '".$reqCabangId."' ";

		if($reqDepartemenId == "")
		{}
		else
			$statement_privacy .= " AND EXISTS(SELECT 1 FROM (SELECT TRIM(UPPER(regexp_split_to_table(AMBIL_HIRARKI_SO('".$reqDepartemenId."'), ','))) AS DEPARTEMEN_ID) X 
							 WHERE X.DEPARTEMEN_ID = A.DEPARTEMEN_ID) ";

		$statement = " AND (UPPER(A.NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%' OR UPPER(A.NIP) LIKE '%".strtoupper($_GET['sSearch'])."%')";
		$allRecord = $pegawai->getCountByParams(array(), $statement_privacy.$statement);
		//echo $allRecord;exit;
		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter =  $pegawai->getCountByParams(array(), $statement_privacy.$statement);
		
		 $pegawai->selectByParamsMonitoring(array(), $dsplyRange, $dsplyStart, $statement_privacy.$statement, $sOrder);

		 // echo $pegawai->query;exit;
		
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
		
		while($pegawai->nextRow())		
		{		
			$row = array();		
			for ( $i=0 ; $i<count($aColumns) ; $i++ )		
			{	
				if($aColumns[$i] == "KETERANGAN")
					$row[] = truncate($pegawai->getField($aColumns[$i]), 2);
				else
					$row[] = $pegawai->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		echo json_encode( $output );	

		
	}

	function add() 
	{
		$this->load->model("Pegawai");
		$this->load->model("SatuanKerja");

		$pegawai = new Pegawai();

		$reqMode= $this->input->post("reqMode");
		$reqId= $this->input->post("reqId");
		$reqNip= $this->input->post("reqNip");
		$reqNama= $this->input->post("reqNama");
		$reqJabatan= $this->input->post("reqJabatan");
		$reqJabatan= setQuote($reqJabatan);
		$reqEmail= $this->input->post("reqEmail");
		$reqIdDirektorat= $this->input->post("reqIdDirektorat");
		$reqNamaDirektorat= $this->input->post("reqNamaDirektorat");
		$reqUnitKerjaId= $this->input->post("reqUnitKerjaId");
		$reqUnitKerjaNama= $this->input->post("reqUnitKerjaNama");
		$reqJenisPegawai= $this->input->post("reqJenisPegawai");

		$reqJenisKelamin= $this->input->post("reqJenisKelamin");
		$reqAlamat= $this->input->post("reqAlamat");
		$reqTempatLahir= $this->input->post("reqTempatLahir");
		$reqTanggalLahir= $this->input->post("reqTanggalLahir");
		$reqTanggalMasuk= $this->input->post("reqTanggaLMasuk");
		$reqBpjs= $this->input->post("reqBpjs");
		$reqNamaRekening= $this->input->post("reqNamaRekening");
		$reqNoRekening= $this->input->post("reqNoRekening");
		$reqPendidikan= $this->input->post("reqPendidikan");
		$reqAgama= $this->input->post("reqAgama");
		$reqNpwp= $this->input->post("reqNpwp");
		$reqKtp= $this->input->post("reqKtp");

		if ($reqMode=='insert') {
			// $cek= $pegawai->getCountByParams(array("PEGAWAI_ID"=>$reqNip, "SATUAN_KERJA_ID"=>$reqUnitKerjaId));
			$cek= $pegawai->getCountByParams(array("PEGAWAI_ID"=>$reqNip));
			if ($cek != 0) 
			{
				echo "xxx-Nip yang anda inputkan sudah digunakan, cek kembali inputan anda.";exit();
			}

			$chk= new SatuanKerja();
			$jumlahchk= $chk->getCountByParams(array(), " AND TREE_PARENT = '".$reqIdDirektorat."' AND UPPER(NAMA) = '".strtoupper($reqJabatan)."'");
			// echo $chk->query;exit;

			if($jumlahchk > 0)
			{
				echo "xxx-Unit Kerja ".$reqJabatan." sudah ada.";
				exit;
			}
		}
		// exit;

		//echo $reqMode;
		// $pegawai->setField("JENIS_KELAMIN", $reqJenisKelamin);
		// $pegawai->setField("ALAMAT", $reqAlamat);
		// $pegawai->setField("EMAIL", $reqEmail);
		// $pegawai->setField("PHONE", $reqPhone);
		// $pegawai->setField("DEPARTEMEN", $reqDepartemen);
		$pegawai->setField("PEGAWAI_ID", $reqId);
		$pegawai->setField("NIP", $reqNip);
		$pegawai->setField("NAMA", setQuote($reqNama));
		$pegawai->setField("JABATAN", $reqJabatan);
		$pegawai->setField("DEPARTEMEN_ID", $reqIdDirektorat);
		$pegawai->setField("DEPARTEMEN", setQuote($reqNamaDirektorat));
		$pegawai->setField("SATUAN_KERJA_ID", $reqUnitKerjaId);
		$pegawai->setField("SATUAN_KERJA", setQuote($reqUnitKerjaNama));
		$pegawai->setField("JENIS_PEGAWAI", $reqJenisPegawai);
		$pegawai->setField("EMAIL", $reqEmail);

		$pegawai->setField("JENIS_KELAMIN", $reqJenisKelamin);
		$pegawai->setField("ALAMAT", $reqAlamat);
		$pegawai->setField("TEMPAT_LAHIR", $reqTempatLahir);
		$pegawai->setField("TANGGAL_LAHIR", $reqTanggalLahir);
		$pegawai->setField("TANGGAL_MASUK", $reqTanggalMasuk);
		$pegawai->setField("BPJS", $reqBpjs);
		$pegawai->setField("BANK_ID", $reqNamaRekening);
		$pegawai->setField("NOMOR_REKENING", $reqNoRekening);
		$pegawai->setField("LAST_PENDIDIKAN_ID", $reqPendidikan);
		$pegawai->setField("AGAMA", $reqAgama);
		$pegawai->setField("NPWP", $reqNpwp);
		$pegawai->setField("KTP", $reqKtp);
		
		if($reqMode == "insert")
		{
			$pegawai->setField("LAST_CREATE_USER", $this->USERNAME);
			$pegawai->setField("LAST_CREATED_DATE", "CURRENT_DATE");
			$pegawai->insert();
		}
		else
		{
			$pegawai->setField("LAST_UPDATE_USER", $this->USERNAME);
			$pegawai->setField("LAST_UPDATED_DATE", "CURRENT_DATE");
			$pegawai->update();
			$pegawai->updateSatker();
			$pegawai->updateUserLogin();

			$set= new Pegawai();
			$set->setField("PEGAWAI_ID", $reqId);
			if($reqJenisPegawai == "ORGANIK")
			{
				$set->setField("STATUS", "1");
			}
			else
			{
				$set->setField("STATUS", "0");
			}
			$set->statususer();
		}
		
		echo "-Data berhasil disimpan.";
	}
	
	function delete() 
	{
		$reqId	= $this->input->get('reqId');
		$this->load->model("Pegawai");
		$pegawai = new Pegawai();

		$pegawai->setField("PEGAWAI_ID", $reqId);

		if($pegawai->delete())
			$arrJson["PESAN"] = "Data berhasil dihapus.";
		else
			$arrJson["PESAN"] = "Data gagal dihapus.";		
		
		echo json_encode($arrJson);
	}
	
	
	function nonaktif() 
	{
		$reqId	= $this->input->get('reqId');
		$this->load->model("Pegawai");
		$pegawai = new Pegawai();

		$pegawai->setField("PEGAWAI_ID", $reqId);

		if($pegawai->nonaktif())
			echo "Pegawai berhasil dinonaktifkan.";
		else
			echo "Pegawai gagal dinonaktifkan.";		
		
	}
	
	
	function sinkronisasi() 
	{
		$this->load->model("Pegawai");
		$pegawai = new Pegawai();
		

		$ch = curl_init("http://172.16.220.77:8080/esbclient/rest/2/HCIS-GET");
		$headers  = [
					'Content-Type: text/plain'
					];
		$postData = [];
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));         
		$result     = curl_exec ($ch);
		
		$arr = json_decode($result, true);
		
		//$pegawai->deleteParent();	
			
			
		for($i=0;$i<count($arr);$i++)
		{
			$pegawai = new Pegawai();
			$pegawai->setField("PEGAWAI_ID", $arr[$i]["EMPLOYEE_NO"]);
			$pegawai->setField("NIP", $arr[$i]["EMPLOYEE_NO"]);
			$pegawai->setField("NAMA", str_replace("'", "''", $arr[$i]["EMPLOYEE_NAME"]));
			$pegawai->setField("JABATAN", $arr[$i]["EMPLOYEE_POSITION"]);
			$pegawai->setField("JENIS_KELAMIN", substr($arr[$i]["GENDER"], 0, 1));
			$pegawai->setField("ALAMAT", $arr[$i]["ADDRESS"]);
			$pegawai->setField("EMAIL", $arr[$i]["EMAIL"]);
			$pegawai->setField("PHONE", $arr[$i]["PHONE"]);
			$pegawai->setField("DEPARTEMEN", $arr[$i]["ORGANIZATION_UNIT"]);
			$pegawai->setField("SATUAN_KERJA_ID", $arr[$i]["WORK_LOCATION_CODE"]);
			$pegawai->setField("SATUAN_KERJA", $arr[$i]["WORK_LOCATION"]);
			$pegawai->setField("LAST_CREATE_USER", $this->USERNAME);
			$pegawai->insertHCIS();
			
		}
		
		echo "Sinkronisasi berhasil.";
			
	
	}


	function import_non_pegawai() 
	{
		$this->load->model("Pegawai");
		$pegawai = new Pegawai();
		
		include "libraries/excel/excel_reader2.php";
		
		$data = new Spreadsheet_Excel_Reader($_FILES['reqLinkFile']['tmp_name']);
		
		$baris = $data->rowcount($sheet_index=0);
		
		$sukses = 0;
		$gagal  = 0;
		for ($i=2; $i<=$baris; $i++)
		{	
			$VALIDASI = 1;
			
			$NAMA			= $data->val($i, 1);
			$EMAIL			= $data->val($i, 2);
			$PHONE			= $data->val($i, 3);
			$JABATAN		= $data->val($i, 4);
			$TAHUN_LAHIR		= trim($data->val($i, 5));
			$TAHUN_MASUK		= trim($data->val($i, 6));
			$JENIS_PEGAWAI		= strtoupper(trim($data->val($i, 7)));
			$SATUAN_KERJA_ID	= $data->val($i, 8);
			$DEPARTEMEN_ID		= $data->val($i, 9);
			
			if(strlen($TAHUN_LAHIR) == 4)
			{}
			else
				$VALIDASI = 0;
				
			if(strlen($TAHUN_MASUK) == 4)
			{}
			else
				$VALIDASI = 0;
				

			if($JENIS_PEGAWAI == "PKWT" || $JENIS_PEGAWAI == "OS")
			{}
			else
				$VALIDASI = 0;
			
			if($VALIDASI == 1)
			{
				$pegawai = new Pegawai();
				$NIP_URUT  = (int)$pegawai->getNipUrut(array("TAHUN_MASUK" => $TAHUN_MASUK, "SATUAN_KERJA_ID" => $SATUAN_KERJA_ID)) + 1;				
				
				$generateSatker = $SATUAN_KERJA_ID;
				$generateLahir  = substr($TAHUN_LAHIR, 2, 2);
				$generateMasuk  = substr($TAHUN_MASUK, 2, 2);				
				$generateUrut   = generateZero($NIP_URUT, 3); 
				$generateJenis  = substr($JENIS_PEGAWAI, 0, 1);
				//DPS1988184-O
				$NIP = $generateSatker.$generateMasuk.$generateLahir.$generateUrut."-".$generateJenis;
				
				$pegawai->setField("TAHUN_LAHIR", $TAHUN_LAHIR);
				$pegawai->setField("TAHUN_MASUK", $TAHUN_MASUK);
				$pegawai->setField("JENIS_PEGAWAI", $JENIS_PEGAWAI);
				$pegawai->setField("PEGAWAI_ID", $NIP);
				$pegawai->setField("NIP", $NIP);
				$pegawai->setField("NIP_URUT", $NIP_URUT);	
				$pegawai->setField("NAMA", str_replace("'", "''", $NAMA));
				$pegawai->setField("EMAIL", $EMAIL);
				$pegawai->setField("PHONE", $PHONE);
				$pegawai->setField("JABATAN", $JABATAN);
				$pegawai->setField("JABATAN", $JABATAN);
				$pegawai->setField("SATUAN_KERJA_ID", $SATUAN_KERJA_ID);
				$pegawai->setField("DEPARTEMEN_ID", $DEPARTEMEN_ID);
				$pegawai->setField("SOURCE_DATA", "IMPORT");
				$pegawai->setField("LAST_CREATE_USER", $this->USERNAME);
				$pegawai->import();
				
				if($pegawai->flag == "Y")
					$sukses++;
				else
					$gagal++;			

			}
			else
				$gagal++;
			
			
		}
		
		echo "Import data berhasil. ".$sukses." data ditambahkan, ".$gagal." data gagal validasi.";
	}
	
	function combo() 
	{
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : 50;
		$offset = ($page-1)*$rows;
		
		$reqPencarian = $this->input->get("reqPencarian");
		$reqMode = $this->input->get("reqMode");
		$reqSatuanKerjaId = $this->input->get("reqSatuanKerjaId");
		$reqParentId = $this->input->get("reqParentId");
		$c = $this->input->get("c");
		
		$this->load->model("Pegawai");
		$pegawai = new Pegawai();

		if($reqPencarian == "")
		{}
		else
			$statement = " AND (UPPER(A.NAMA) LIKE '%".strtoupper($reqPencarian)."%' OR UPPER(A.PEGAWAI_ID) LIKE '%".strtoupper($reqPencarian)."%') ";
		
		if($reqMode == "mutasi")
		{
			if(empty($reqSatuanKerjaId))
				$statement_privacy = " AND A.SATUAN_KERJA_ID = '".$this->CABANG_ID."' ";
			else
				$statement_privacy = " AND A.SATUAN_KERJA_ID = '".$reqSatuanKerjaId."' ";

			$statement_privacy .= " AND (A.JENIS_PEGAWAI NOT IN ('PENSIUN') OR COALESCE(NULLIF(A.JENIS_PEGAWAI, ''), NULL) IS NULL) ";
		}
		else
		{
			if($this->CABANG_ID == "PST")
				$statement_privacy = "";
			else
				$statement_privacy = " AND A.SATUAN_KERJA_ID = '".$this->CABANG_ID."' ";
		}
		
		//	$statement_privacy .= " AND NOT EXISTS(SELECT 1 FROM USER_LOGIN X WHERE X.PEGAWAI_ID = A.PEGAWAI_ID) ";

		if($reqMode == "user_bantu")
		{
			// $statement_privacy .= " AND EXISTS
			// (
			// 	SELECT 1
			// 	FROM
			// 	(
			// 		SELECT PEGAWAI_ID PID
			// 		FROM USER_LOGIN_CABANG 
			// 		WHERE USER_GROUP_ID LIKE '%SEKRETARIS%'
			// 	) X WHERE PEGAWAI_ID = PID
			// )";
			
			// $statement_privacy .= " AND B.SATUAN_KERJA_ID = '".$reqParentId."'";
			// $statement_privacy .= " AND UPPER(A.JABATAN) LIKE '%SEKRETARIS%'";
			$rowCount = $pegawai->getCountByParamsUserBantu(array(), $statement.$statement_privacy);
			$pegawai->selectByParamsUserBantu(array(), $rows, $offset, $statement.$statement_privacy);
			// echo $pegawai->query; exit;
		}
		else if($reqMode == "approval_sttpd")
		{
			$rowCount = $pegawai->getCountByParamsApprovalSttpd(array(), $statement.$statement_privacy);
			$pegawai->selectByParamsApprovalSttpd(array(), $rows, $offset, $statement.$statement_privacy);
			// echo $pegawai->query; exit;
		}
		else
		{
			$rowCount = $pegawai->getCountByParams(array(), $statement.$statement_privacy);
			$pegawai->selectByParams(array(), $rows, $offset, $statement.$statement_privacy);
		}

		if(!empty($c))
		{
			echo $pegawai->query;exit;
		}

		$i = 0;
		$items = array();
		while($pegawai->nextRow())
		{
			$row['id']		= $pegawai->getField("PEGAWAI_ID");
			$row['text']	= $pegawai->getField("NAMA");
			$row['DEPARTEMEN_ID']	= $pegawai->getField("DEPARTEMEN_ID");
			$row['INFO_DEPARTEMEN_NAMA']	= $pegawai->getField("INFO_DEPARTEMEN_NAMA");
			$row['PEGAWAI_ID']	= $pegawai->getField("PEGAWAI_ID");
			$row['NAMA']	= $pegawai->getField("NAMA");
			$row['CABANG']	= $pegawai->getField("SATUAN_KERJA");
			$row['JABATAN']	= $pegawai->getField("JABATAN");
			$row['state'] 	= 'close';
			$i++;
			array_push($items, $row);
		}
		$result["rows"] = $items;
		$result["total"] = $rowCount;
		echo json_encode($result);
	}

	function combojenis() 
	{
		$reqMode= $this->input->get("reqMode");
		$i = 0;

		if(empty($reqMode))
		{
			$arr_json[$i]['id']		= "";
			$arr_json[$i]['text']	= "Semua";
			$i++;
		}

		$arr_json[$i]['id']		= "ORGANIK";
		$arr_json[$i]['text']	= "Organik";
		$i++;
		$arr_json[$i]['id']		= "PENSIUN";
		$arr_json[$i]['text']	= "Pensiun";
		$i++;
		echo json_encode($arr_json);
	}
	
}