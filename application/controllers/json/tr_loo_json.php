<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/class-list-util.php");
include_once("functions/class-list-util-serverside.php");

class tr_loo_json extends CI_Controller
{
	var $calendarId = 'cohu8p4q74iks6dpilk7mrpvi4@group.calendar.google.com';

	function __construct()
	{
		parent::__construct();

		$reqToken = $this->input->get("reqToken");
		if ($reqToken == "") {
			$reqToken = $this->input->post("reqToken");
		}

		if (!empty($reqToken)) {
			$this->load->model('UserLoginMobile');
			$user_login_mobile = new UserLoginMobile();
			$reqPegawaiId = $user_login_mobile->getTokenPegawaiId(array("TOKEN" => $reqToken, "STATUS" => '1'));

			if ($reqPegawaiId == "0") {
				$arrReturn = array('status' => 'fail', 'message' => 'Sesi anda telah berakhir', 'code' => 502);
				echo json_encode($arrReturn);
				return;
			}

			$this->kauth->mobileVerification($reqPegawaiId, $reqToken);
			$this->CALLER = "MOBILE";
		}

		if (!$this->kauth->getInstance()->hasIdentity()) {
			redirect('login');
		}

		$this->db->query("SET DATESTYLE TO PostgreSQL,European;");
		$this->ID					= $this->kauth->getInstance()->getIdentity()->ID;
		$this->NAMA					= $this->kauth->getInstance()->getIdentity()->NAMA;
		$this->JABATAN				= $this->kauth->getInstance()->getIdentity()->JABATAN;
		$this->HAK_AKSES			= $this->kauth->getInstance()->getIdentity()->HAK_AKSES;
		$this->LAST_LOGIN			= $this->kauth->getInstance()->getIdentity()->LAST_LOGIN;
		$this->USERNAME				= $this->kauth->getInstance()->getIdentity()->USERNAME;
		$this->USER_LOGIN_ID		= $this->kauth->getInstance()->getIdentity()->USER_LOGIN_ID;
		$this->USER_GROUP			= $this->kauth->getInstance()->getIdentity()->USER_GROUP;
		$this->MULTIROLE			= $this->kauth->getInstance()->getIdentity()->MULTIROLE;
		$this->CABANG_ID			= $this->kauth->getInstance()->getIdentity()->CABANG_ID;
		$this->CABANG				= $this->kauth->getInstance()->getIdentity()->CABANG;
		$this->SATUAN_KERJA_ID_ASAL	= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_ID_ASAL;
		$this->SATUAN_KERJA_ASAL	= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_ASAL;
		$this->SATUAN_KERJA_HIRARKI	= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_HIRARKI;
		$this->SATUAN_KERJA_JABATAN	= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_JABATAN;
		$this->KD_LEVEL				= $this->kauth->getInstance()->getIdentity()->KD_LEVEL;
		$this->KD_LEVEL_PEJABAT 	= $this->kauth->getInstance()->getIdentity()->KD_LEVEL_PEJABAT;
		$this->JENIS_KELAMIN 		= $this->kauth->getInstance()->getIdentity()->JENIS_KELAMIN;
		$this->KELOMPOK_JABATAN 	= $this->kauth->getInstance()->getIdentity()->KELOMPOK_JABATAN;
		$this->KODE_PARENT= $this->kauth->getInstance()->getIdentity()->KODE_PARENT;
		$this->ID_ATASAN 			= $this->kauth->getInstance()->getIdentity()->ID_ATASAN;
		$this->DEPARTEMEN_PARENT_ID 			= $this->kauth->getInstance()->getIdentity()->DEPARTEMEN_PARENT_ID;

		$this->NIP_BY_DIVISI 			= $this->kauth->getInstance()->getIdentity()->NIP_BY_DIVISI;
		$this->KELOMPOK_JABATAN_BY_DIVISI 			= $this->kauth->getInstance()->getIdentity()->KELOMPOK_JABATAN_BY_DIVISI;

		$this->SATUAN_KERJA_ID_ASAL_ASLI= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_ID_ASAL_ASLI;
		$this->USER_BANTU= $this->kauth->getInstance()->getIdentity()->USER_BANTU;
	}

	function jsondraft()
	{
		$this->load->model("TrLoo");

		$cekquery= $this->input->get("c");

		$set= new TrLoo();

		if ( isset( $_REQUEST['columnsDef'] ) && is_array( $_REQUEST['columnsDef'] ) ) {
			$columnsDefault = [];
			foreach ( $_REQUEST['columnsDef'] as $field ) {
				$columnsDefault[ $field ] = "true";
			}
		}
		// print_r($columnsDefault);exit;

		$displaystart= -1;
		$displaylength= -1;

		$arrinfodata= [];

		$reqPencarian= $this->input->get("reqPencarian");
		$searchJson= "";
		$infosearch= $_REQUEST['search']["value"];

		/*if(!empty($infosearch))
		{
			$reqPencarian= $infosearch;
		}*/

		if(!empty($reqPencarian))
		{
			$searchJson= " 
			AND 
			(
				UPPER(A1.NAMA_PEMILIK) LIKE '%".strtoupper($reqPencarian)."%' OR 
				UPPER(A1.NAMA_BRAND) LIKE '%".strtoupper($reqPencarian)."%'
			)";
		}

		$sessid= $this->ID;
		$statement.= " AND A.USER_PEMBUAT_ID = '".$sessid."' AND A.STATUS_DATA IN ('DRAFT', 'REVISI')";
		// untuk buat session
		/*session_start();
		$arrsession= [];
		$_SESSION['kotak_masuk'.$reqJenisNaskahId]= $arrsession;
		$arrsession["reqTahun"]= $reqTahun;
		$arrsession["reqPilihan"]= $reqPilihan;
		$arrsession["reqStatusSurat"]= $reqStatusSurat;
		if(!empty($infosearch))
			$arrsession["reqPencarian"]= $infosearch;
		else
			$arrsession["reqPencarian"]= $reqPencarian;
		$_SESSION['kotak_masuk'.$reqJenisNaskahId]= $arrsession;*/

		
		$sOrder = " ORDER BY a.TR_LOO_ID ASC";
		$set->selectdraft(array(), $dsplyRange, $dsplyStart, $statement.$searchJson, $sOrder);

		if(!empty($cekquery))
		{
			echo $set->query;exit;
		}
		
		$infobatasdetil= $_REQUEST['start'] + $_REQUEST['length'];
		$infonomor= 0;
		while ($set->nextRow()) 
		{
			$infonomor++;
			$infojenisnaskahid= $set->getField("JENIS_NASKAH_ID");
			$infosuratmasukid= $set->getField("SURAT_MASUK_ID");

			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{
				if ($valkey == "SORDERDEFAULT")
				{
					$row[$valkey]= $set->getField("TANGGAL_DISPOSISI");
				}
				else if($valkey == "INFO_LAST_CREATE_DATE")
				{
					$row[$valkey] = datetimeToPage($set->getField($valkey), "date");
				}
				else if($valkey == "NOMOR")
				{
					$row[$valkey] = $set->getField($valkey);
				}
				else
					$row[$valkey]= $set->getField($valkey);
			}
			array_push($arrinfodata, $row);
		}

		// get all raw data
		$alldata = $arrinfodata;
		// print_r($alldata);exit;

		$data = [];
		// internal use; filter selected columns only from raw data
		foreach ( $alldata as $d ) {
			// $data[] = filterArray( $d, $columnsDefault );
			$data[] = $d;
		}

		// count data
		$totalRecords = $totalDisplay = count( $data );

		// filter by general search keyword
		if ( isset( $_REQUEST['search'] ) ) {
			$data         = filterKeyword( $data, $_REQUEST['search'] );
			$totalDisplay = count( $data );
		}

		if ( isset( $_REQUEST['columns'] ) && is_array( $_REQUEST['columns'] ) ) {
			foreach ( $_REQUEST['columns'] as $column ) {
				if ( isset( $column['search'] ) ) {
					$data         = filterKeyword( $data, $column['search'], $column['data'] );
					$totalDisplay = count( $data );
				}
			}
		}

		// sort
		if ( isset( $_REQUEST['order'][0]['column'] ) && $_REQUEST['order'][0]['dir'] ) {
			$column = $_REQUEST['order'][0]['column'];
			// if(count($columnsDefault) - 2 == $column){}
			// else
			// {
				$dir    = $_REQUEST['order'][0]['dir'];
				usort( $data, function ( $a, $b ) use ( $column, $dir ) {
					$a = array_slice( $a, $column, 1 );
					$b = array_slice( $b, $column, 1 );
					$a = array_pop( $a );
					$b = array_pop( $b );

					if ( $dir === 'asc' ) {
						return $a > $b ? true : false;
					}

					return $a < $b ? true : false;
				} );
			// }
		}

		// pagination length
		if ( isset( $_REQUEST['length'] ) ) {
			$data = array_splice( $data, $_REQUEST['start'], $_REQUEST['length'] );
		}

		// return array values only without the keys
		if ( isset( $_REQUEST['array_values'] ) && $_REQUEST['array_values'] ) {
			$tmp  = $data;
			$data = [];
			foreach ( $tmp as $d ) {
				$data[] = array_values( $d );
			}
		}

		$result = [
		    'recordsTotal'    => $totalRecords,
		    'recordsFiltered' => $totalDisplay,
		    'data'            => $data,
		];

		header('Content-Type: application/json');
		echo json_encode( $result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);	
	}

	function jsonperlupersetujuan()
	{
		$this->load->model("TrLoo");

		$reqStatusSurat= $this->input->get("reqStatusSurat");
		$cekquery= $this->input->get("c");

		$set= new TrLoo();

		if ( isset( $_REQUEST['columnsDef'] ) && is_array( $_REQUEST['columnsDef'] ) ) {
			$columnsDefault = [];
			foreach ( $_REQUEST['columnsDef'] as $field ) {
				$columnsDefault[ $field ] = "true";
			}
		}
		// print_r($columnsDefault);exit;

		$displaystart= -1;
		$displaylength= -1;

		$arrinfodata= [];

		$reqPencarian= $this->input->get("reqPencarian");
		$searchJson= "";
		$infosearch= $_REQUEST['search']["value"];

		/*if(!empty($infosearch))
		{
			$reqPencarian= $infosearch;
		}*/

		if(!empty($reqPencarian))
		{
			$searchJson= " 
			AND 
			(
				UPPER(A1.NAMA_PEMILIK) LIKE '%".strtoupper($reqPencarian)."%' OR 
				UPPER(A1.NAMA_BRAND) LIKE '%".strtoupper($reqPencarian)."%'
			)";
		}

		$sessid= $this->ID;
		$statement.= " AND A.STATUS_DATA IN ('PARAF', 'VALIDASI')";

		if($reqStatusSurat == "PERLU_PERSETUJUAN")
			$statement.= " AND A.USER_POSISI_PARAF_ID = '".$sessid."'";
		else
			$statement.= " AND A.USER_LIHAT_STATUS LIKE '%".$sessid."%'";

		// untuk buat session
		/*session_start();
		$arrsession= [];
		$_SESSION['kotak_masuk'.$reqJenisNaskahId]= $arrsession;
		$arrsession["reqTahun"]= $reqTahun;
		$arrsession["reqPilihan"]= $reqPilihan;
		$arrsession["reqStatusSurat"]= $reqStatusSurat;
		if(!empty($infosearch))
			$arrsession["reqPencarian"]= $infosearch;
		else
			$arrsession["reqPencarian"]= $reqPencarian;
		$_SESSION['kotak_masuk'.$reqJenisNaskahId]= $arrsession;*/

		
		$sOrder = " ORDER BY a.TR_LOO_ID ASC";
		$set->selectdraft(array(), $dsplyRange, $dsplyStart, $statement.$searchJson, $sOrder);

		if(!empty($cekquery))
		{
			echo $set->query;exit;
		}
		
		$infobatasdetil= $_REQUEST['start'] + $_REQUEST['length'];
		$infonomor= 0;
		while ($set->nextRow()) 
		{
			$infonomor++;
			$infojenisnaskahid= $set->getField("JENIS_NASKAH_ID");
			$infosuratmasukid= $set->getField("SURAT_MASUK_ID");

			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{
				if ($valkey == "SORDERDEFAULT")
				{
					$row[$valkey]= $set->getField("TANGGAL_DISPOSISI");
				}
				
				else if($valkey == "NOMOR")
				{
					$row[$valkey] = $set->getField($valkey);
				}
				else
					$row[$valkey]= $set->getField($valkey);
			}
			array_push($arrinfodata, $row);
		}

		// get all raw data
		$alldata = $arrinfodata;
		// print_r($alldata);exit;

		$data = [];
		// internal use; filter selected columns only from raw data
		foreach ( $alldata as $d ) {
			// $data[] = filterArray( $d, $columnsDefault );
			$data[] = $d;
		}

		// count data
		$totalRecords = $totalDisplay = count( $data );

		// filter by general search keyword
		if ( isset( $_REQUEST['search'] ) ) {
			$data         = filterKeyword( $data, $_REQUEST['search'] );
			$totalDisplay = count( $data );
		}

		if ( isset( $_REQUEST['columns'] ) && is_array( $_REQUEST['columns'] ) ) {
			foreach ( $_REQUEST['columns'] as $column ) {
				if ( isset( $column['search'] ) ) {
					$data         = filterKeyword( $data, $column['search'], $column['data'] );
					$totalDisplay = count( $data );
				}
			}
		}

		// sort
		if ( isset( $_REQUEST['order'][0]['column'] ) && $_REQUEST['order'][0]['dir'] ) {
			$column = $_REQUEST['order'][0]['column'];
			// if(count($columnsDefault) - 2 == $column){}
			// else
			// {
				$dir    = $_REQUEST['order'][0]['dir'];
				usort( $data, function ( $a, $b ) use ( $column, $dir ) {
					$a = array_slice( $a, $column, 1 );
					$b = array_slice( $b, $column, 1 );
					$a = array_pop( $a );
					$b = array_pop( $b );

					if ( $dir === 'asc' ) {
						return $a > $b ? true : false;
					}

					return $a < $b ? true : false;
				} );
			// }
		}

		// pagination length
		if ( isset( $_REQUEST['length'] ) ) {
			$data = array_splice( $data, $_REQUEST['start'], $_REQUEST['length'] );
		}

		// return array values only without the keys
		if ( isset( $_REQUEST['array_values'] ) && $_REQUEST['array_values'] ) {
			$tmp  = $data;
			$data = [];
			foreach ( $tmp as $d ) {
				$data[] = array_values( $d );
			}
		}

		$result = [
		    'recordsTotal'    => $totalRecords,
		    'recordsFiltered' => $totalDisplay,
		    'data'            => $data,
		];

		header('Content-Type: application/json');
		echo json_encode( $result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);	
	}

	function jsonstatus()
	{
		$this->load->model("TrLoo");

		$reqStatusSurat= $this->input->get("reqStatusSurat");
		$cekquery= $this->input->get("c");

		$set= new TrLoo();

		if ( isset( $_REQUEST['columnsDef'] ) && is_array( $_REQUEST['columnsDef'] ) ) {
			$columnsDefault = [];
			foreach ( $_REQUEST['columnsDef'] as $field ) {
				$columnsDefault[ $field ] = "true";
			}
		}
		// print_r($columnsDefault);exit;

		$displaystart= -1;
		$displaylength= -1;

		$arrinfodata= [];

		$reqPencarian= $this->input->get("reqPencarian");
		$searchJson= "";
		$infosearch= $_REQUEST['search']["value"];

		/*if(!empty($infosearch))
		{
			$reqPencarian= $infosearch;
		}*/

		if(!empty($reqPencarian))
		{
			$searchJson= " 
			AND 
			(
				UPPER(A1.NAMA_PEMILIK) LIKE '%".strtoupper($reqPencarian)."%' OR 
				UPPER(A1.NAMA_BRAND) LIKE '%".strtoupper($reqPencarian)."%'
			)";
		}

		$sessid= $this->ID;
		$statement= "";

		if($reqStatusSurat == "PARAF")
			$statement.= " AND A.USER_POSISI_PARAF_ID != '".$sessid."' AND A.USER_LIHAT_STATUS LIKE '%".$sessid."%' AND A.STATUS_DATA IN ('PARAF', 'VALIDASI')";
		else
			$statement= " AND A.STATUS_DATA IN ('REVISI')";

		// untuk buat session
		/*session_start();
		$arrsession= [];
		$_SESSION['kotak_masuk'.$reqJenisNaskahId]= $arrsession;
		$arrsession["reqTahun"]= $reqTahun;
		$arrsession["reqPilihan"]= $reqPilihan;
		$arrsession["reqStatusSurat"]= $reqStatusSurat;
		if(!empty($infosearch))
			$arrsession["reqPencarian"]= $infosearch;
		else
			$arrsession["reqPencarian"]= $reqPencarian;
		$_SESSION['kotak_masuk'.$reqJenisNaskahId]= $arrsession;*/

		
		$sOrder = " ORDER BY a.TR_LOO_ID ASC";
		$set->selectdraft(array(), $dsplyRange, $dsplyStart, $statement.$searchJson, $sOrder);

		if(!empty($cekquery))
		{
			echo $set->query;exit;
		}
		
		$infobatasdetil= $_REQUEST['start'] + $_REQUEST['length'];
		$infonomor= 0;
		while ($set->nextRow()) 
		{
			$infonomor++;
			$infojenisnaskahid= $set->getField("JENIS_NASKAH_ID");
			$infosuratmasukid= $set->getField("SURAT_MASUK_ID");

			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{
				if ($valkey == "SORDERDEFAULT")
				{
					$row[$valkey]= $set->getField("TANGGAL_DISPOSISI");
				}
				
				else if($valkey == "NOMOR")
				{
					$row[$valkey] = $set->getField($valkey);
				}
				else
					$row[$valkey]= $set->getField($valkey);
			}
			array_push($arrinfodata, $row);
		}

		// get all raw data
		$alldata = $arrinfodata;
		// print_r($alldata);exit;

		$data = [];
		// internal use; filter selected columns only from raw data
		foreach ( $alldata as $d ) {
			// $data[] = filterArray( $d, $columnsDefault );
			$data[] = $d;
		}

		// count data
		$totalRecords = $totalDisplay = count( $data );

		// filter by general search keyword
		if ( isset( $_REQUEST['search'] ) ) {
			$data         = filterKeyword( $data, $_REQUEST['search'] );
			$totalDisplay = count( $data );
		}

		if ( isset( $_REQUEST['columns'] ) && is_array( $_REQUEST['columns'] ) ) {
			foreach ( $_REQUEST['columns'] as $column ) {
				if ( isset( $column['search'] ) ) {
					$data         = filterKeyword( $data, $column['search'], $column['data'] );
					$totalDisplay = count( $data );
				}
			}
		}

		// sort
		if ( isset( $_REQUEST['order'][0]['column'] ) && $_REQUEST['order'][0]['dir'] ) {
			$column = $_REQUEST['order'][0]['column'];
			// if(count($columnsDefault) - 2 == $column){}
			// else
			// {
				$dir    = $_REQUEST['order'][0]['dir'];
				usort( $data, function ( $a, $b ) use ( $column, $dir ) {
					$a = array_slice( $a, $column, 1 );
					$b = array_slice( $b, $column, 1 );
					$a = array_pop( $a );
					$b = array_pop( $b );

					if ( $dir === 'asc' ) {
						return $a > $b ? true : false;
					}

					return $a < $b ? true : false;
				} );
			// }
		}

		// pagination length
		if ( isset( $_REQUEST['length'] ) ) {
			$data = array_splice( $data, $_REQUEST['start'], $_REQUEST['length'] );
		}

		// return array values only without the keys
		if ( isset( $_REQUEST['array_values'] ) && $_REQUEST['array_values'] ) {
			$tmp  = $data;
			$data = [];
			foreach ( $tmp as $d ) {
				$data[] = array_values( $d );
			}
		}

		$result = [
		    'recordsTotal'    => $totalRecords,
		    'recordsFiltered' => $totalDisplay,
		    'data'            => $data,
		];

		header('Content-Type: application/json');
		echo json_encode( $result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);	
	}

	function jsonloidraft()
	{
		$this->load->model("TrLoi");

		$cekquery= $this->input->get("c");

		$set= new TrLoi();

		if ( isset( $_REQUEST['columnsDef'] ) && is_array( $_REQUEST['columnsDef'] ) ) {
			$columnsDefault = [];
			foreach ( $_REQUEST['columnsDef'] as $field ) {
				$columnsDefault[ $field ] = "true";
			}
		}
		// print_r($columnsDefault);exit;

		$displaystart= -1;
		$displaylength= -1;

		$arrinfodata= [];

		$reqPencarian= $this->input->get("reqPencarian");
		$searchJson= "";
		$infosearch= $_REQUEST['search']["value"];

		/*if(!empty($infosearch))
		{
			$reqPencarian= $infosearch;
		}*/

		if(!empty($reqPencarian))
		{
			$searchJson= " 
			AND 
			(
				UPPER(A1.NAMA_PEMILIK) LIKE '%".strtoupper($reqPencarian)."%' OR 
				UPPER(A1.NAMA_BRAND) LIKE '%".strtoupper($reqPencarian)."%'
			)";
		}

		$sessid= $this->ID;
		$statement.= " AND A.USER_PEMBUAT_ID = '".$sessid."' AND A.STATUS_DATA IN ('DRAFT', 'REVISI')";
		// untuk buat session
		/*session_start();
		$arrsession= [];
		$_SESSION['kotak_masuk'.$reqJenisNaskahId]= $arrsession;
		$arrsession["reqTahun"]= $reqTahun;
		$arrsession["reqPilihan"]= $reqPilihan;
		$arrsession["reqStatusSurat"]= $reqStatusSurat;
		if(!empty($infosearch))
			$arrsession["reqPencarian"]= $infosearch;
		else
			$arrsession["reqPencarian"]= $reqPencarian;
		$_SESSION['kotak_masuk'.$reqJenisNaskahId]= $arrsession;*/

		
		$sOrder = " ORDER BY a.TR_LOO_ID ASC";
		$set->selectdraft(array(), $dsplyRange, $dsplyStart, $statement.$searchJson, $sOrder);

		if(!empty($cekquery))
		{
			echo $set->query;exit;
		}
		
		$infobatasdetil= $_REQUEST['start'] + $_REQUEST['length'];
		$infonomor= 0;
		while ($set->nextRow()) 
		{
			$infonomor++;
			$infojenisnaskahid= $set->getField("JENIS_NASKAH_ID");
			$infosuratmasukid= $set->getField("SURAT_MASUK_ID");

			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{
				if ($valkey == "SORDERDEFAULT")
				{
					$row[$valkey]= $set->getField("TANGGAL_DISPOSISI");
				}
				else if($valkey == "INFO_LAST_CREATE_DATE")
				{
					$row[$valkey] = datetimeToPage($set->getField($valkey), "date");
				}
				else if($valkey == "NOMOR")
				{
					$row[$valkey] = $set->getField($valkey);
				}
				else
					$row[$valkey]= $set->getField($valkey);
			}
			array_push($arrinfodata, $row);
		}

		// get all raw data
		$alldata = $arrinfodata;
		// print_r($alldata);exit;

		$data = [];
		// internal use; filter selected columns only from raw data
		foreach ( $alldata as $d ) {
			// $data[] = filterArray( $d, $columnsDefault );
			$data[] = $d;
		}

		// count data
		$totalRecords = $totalDisplay = count( $data );

		// filter by general search keyword
		if ( isset( $_REQUEST['search'] ) ) {
			$data         = filterKeyword( $data, $_REQUEST['search'] );
			$totalDisplay = count( $data );
		}

		if ( isset( $_REQUEST['columns'] ) && is_array( $_REQUEST['columns'] ) ) {
			foreach ( $_REQUEST['columns'] as $column ) {
				if ( isset( $column['search'] ) ) {
					$data         = filterKeyword( $data, $column['search'], $column['data'] );
					$totalDisplay = count( $data );
				}
			}
		}

		// sort
		if ( isset( $_REQUEST['order'][0]['column'] ) && $_REQUEST['order'][0]['dir'] ) {
			$column = $_REQUEST['order'][0]['column'];
			// if(count($columnsDefault) - 2 == $column){}
			// else
			// {
				$dir    = $_REQUEST['order'][0]['dir'];
				usort( $data, function ( $a, $b ) use ( $column, $dir ) {
					$a = array_slice( $a, $column, 1 );
					$b = array_slice( $b, $column, 1 );
					$a = array_pop( $a );
					$b = array_pop( $b );

					if ( $dir === 'asc' ) {
						return $a > $b ? true : false;
					}

					return $a < $b ? true : false;
				} );
			// }
		}

		// pagination length
		if ( isset( $_REQUEST['length'] ) ) {
			$data = array_splice( $data, $_REQUEST['start'], $_REQUEST['length'] );
		}

		// return array values only without the keys
		if ( isset( $_REQUEST['array_values'] ) && $_REQUEST['array_values'] ) {
			$tmp  = $data;
			$data = [];
			foreach ( $tmp as $d ) {
				$data[] = array_values( $d );
			}
		}

		$result = [
		    'recordsTotal'    => $totalRecords,
		    'recordsFiltered' => $totalDisplay,
		    'data'            => $data,
		];

		header('Content-Type: application/json');
		echo json_encode( $result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);	
	}

	function jsonloiperlupersetujuan()
	{
		$this->load->model("TrLoi");

		$reqStatusSurat= $this->input->get("reqStatusSurat");
		$cekquery= $this->input->get("c");

		$set= new TrLoi();

		if ( isset( $_REQUEST['columnsDef'] ) && is_array( $_REQUEST['columnsDef'] ) ) {
			$columnsDefault = [];
			foreach ( $_REQUEST['columnsDef'] as $field ) {
				$columnsDefault[ $field ] = "true";
			}
		}
		// print_r($columnsDefault);exit;

		$displaystart= -1;
		$displaylength= -1;

		$arrinfodata= [];

		$reqPencarian= $this->input->get("reqPencarian");
		$searchJson= "";
		$infosearch= $_REQUEST['search']["value"];

		/*if(!empty($infosearch))
		{
			$reqPencarian= $infosearch;
		}*/

		if(!empty($reqPencarian))
		{
			$searchJson= " 
			AND 
			(
				UPPER(A1.NAMA_PEMILIK) LIKE '%".strtoupper($reqPencarian)."%' OR 
				UPPER(A1.NAMA_BRAND) LIKE '%".strtoupper($reqPencarian)."%'
			)";
		}

		$sessid= $this->ID;
		$statement.= " AND A.STATUS_DATA IN ('PARAF', 'VALIDASI')";

		if($reqStatusSurat == "PERLU_PERSETUJUAN")
			$statement.= " AND A.USER_POSISI_PARAF_ID = '".$sessid."'";
		else
			$statement.= " AND A.USER_LIHAT_STATUS LIKE '%".$sessid."%'";

		// untuk buat session
		/*session_start();
		$arrsession= [];
		$_SESSION['kotak_masuk'.$reqJenisNaskahId]= $arrsession;
		$arrsession["reqTahun"]= $reqTahun;
		$arrsession["reqPilihan"]= $reqPilihan;
		$arrsession["reqStatusSurat"]= $reqStatusSurat;
		if(!empty($infosearch))
			$arrsession["reqPencarian"]= $infosearch;
		else
			$arrsession["reqPencarian"]= $reqPencarian;
		$_SESSION['kotak_masuk'.$reqJenisNaskahId]= $arrsession;*/

		
		$sOrder = " ORDER BY a.TR_LOO_ID ASC";
		$set->selectdraft(array(), $dsplyRange, $dsplyStart, $statement.$searchJson, $sOrder);

		if(!empty($cekquery))
		{
			echo $set->query;exit;
		}
		
		$infobatasdetil= $_REQUEST['start'] + $_REQUEST['length'];
		$infonomor= 0;
		while ($set->nextRow()) 
		{
			$infonomor++;
			$infojenisnaskahid= $set->getField("JENIS_NASKAH_ID");
			$infosuratmasukid= $set->getField("SURAT_MASUK_ID");

			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{
				if ($valkey == "SORDERDEFAULT")
				{
					$row[$valkey]= $set->getField("TANGGAL_DISPOSISI");
				}
				
				else if($valkey == "NOMOR")
				{
					$row[$valkey] = $set->getField($valkey);
				}
				else
					$row[$valkey]= $set->getField($valkey);
			}
			array_push($arrinfodata, $row);
		}

		// get all raw data
		$alldata = $arrinfodata;
		// print_r($alldata);exit;

		$data = [];
		// internal use; filter selected columns only from raw data
		foreach ( $alldata as $d ) {
			// $data[] = filterArray( $d, $columnsDefault );
			$data[] = $d;
		}

		// count data
		$totalRecords = $totalDisplay = count( $data );

		// filter by general search keyword
		if ( isset( $_REQUEST['search'] ) ) {
			$data         = filterKeyword( $data, $_REQUEST['search'] );
			$totalDisplay = count( $data );
		}

		if ( isset( $_REQUEST['columns'] ) && is_array( $_REQUEST['columns'] ) ) {
			foreach ( $_REQUEST['columns'] as $column ) {
				if ( isset( $column['search'] ) ) {
					$data         = filterKeyword( $data, $column['search'], $column['data'] );
					$totalDisplay = count( $data );
				}
			}
		}

		// sort
		if ( isset( $_REQUEST['order'][0]['column'] ) && $_REQUEST['order'][0]['dir'] ) {
			$column = $_REQUEST['order'][0]['column'];
			// if(count($columnsDefault) - 2 == $column){}
			// else
			// {
				$dir    = $_REQUEST['order'][0]['dir'];
				usort( $data, function ( $a, $b ) use ( $column, $dir ) {
					$a = array_slice( $a, $column, 1 );
					$b = array_slice( $b, $column, 1 );
					$a = array_pop( $a );
					$b = array_pop( $b );

					if ( $dir === 'asc' ) {
						return $a > $b ? true : false;
					}

					return $a < $b ? true : false;
				} );
			// }
		}

		// pagination length
		if ( isset( $_REQUEST['length'] ) ) {
			$data = array_splice( $data, $_REQUEST['start'], $_REQUEST['length'] );
		}

		// return array values only without the keys
		if ( isset( $_REQUEST['array_values'] ) && $_REQUEST['array_values'] ) {
			$tmp  = $data;
			$data = [];
			foreach ( $tmp as $d ) {
				$data[] = array_values( $d );
			}
		}

		$result = [
		    'recordsTotal'    => $totalRecords,
		    'recordsFiltered' => $totalDisplay,
		    'data'            => $data,
		];

		header('Content-Type: application/json');
		echo json_encode( $result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);	
	}

	function jsonloistatus()
	{
		$this->load->model("TrLoi");

		$reqStatusSurat= $this->input->get("reqStatusSurat");
		$cekquery= $this->input->get("c");

		$set= new TrLoi();

		if ( isset( $_REQUEST['columnsDef'] ) && is_array( $_REQUEST['columnsDef'] ) ) {
			$columnsDefault = [];
			foreach ( $_REQUEST['columnsDef'] as $field ) {
				$columnsDefault[ $field ] = "true";
			}
		}
		// print_r($columnsDefault);exit;

		$displaystart= -1;
		$displaylength= -1;

		$arrinfodata= [];

		$reqPencarian= $this->input->get("reqPencarian");
		$searchJson= "";
		$infosearch= $_REQUEST['search']["value"];

		/*if(!empty($infosearch))
		{
			$reqPencarian= $infosearch;
		}*/

		if(!empty($reqPencarian))
		{
			$searchJson= " 
			AND 
			(
				UPPER(A1.NAMA_PEMILIK) LIKE '%".strtoupper($reqPencarian)."%' OR 
				UPPER(A1.NAMA_BRAND) LIKE '%".strtoupper($reqPencarian)."%'
			)";
		}

		$sessid= $this->ID;
		$statement= "";

		if($reqStatusSurat == "PARAF")
			$statement.= " AND A.USER_POSISI_PARAF_ID != '".$sessid."' AND A.USER_LIHAT_STATUS LIKE '%".$sessid."%' AND A.STATUS_DATA IN ('PARAF', 'VALIDASI')";
		else
			$statement= " AND A.STATUS_DATA IN ('REVISI')";

		// untuk buat session
		/*session_start();
		$arrsession= [];
		$_SESSION['kotak_masuk'.$reqJenisNaskahId]= $arrsession;
		$arrsession["reqTahun"]= $reqTahun;
		$arrsession["reqPilihan"]= $reqPilihan;
		$arrsession["reqStatusSurat"]= $reqStatusSurat;
		if(!empty($infosearch))
			$arrsession["reqPencarian"]= $infosearch;
		else
			$arrsession["reqPencarian"]= $reqPencarian;
		$_SESSION['kotak_masuk'.$reqJenisNaskahId]= $arrsession;*/

		
		$sOrder = " ORDER BY a.TR_LOO_ID ASC";
		$set->selectdraft(array(), $dsplyRange, $dsplyStart, $statement.$searchJson, $sOrder);

		if(!empty($cekquery))
		{
			echo $set->query;exit;
		}
		
		$infobatasdetil= $_REQUEST['start'] + $_REQUEST['length'];
		$infonomor= 0;
		while ($set->nextRow()) 
		{
			$infonomor++;
			$infojenisnaskahid= $set->getField("JENIS_NASKAH_ID");
			$infosuratmasukid= $set->getField("SURAT_MASUK_ID");

			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{
				if ($valkey == "SORDERDEFAULT")
				{
					$row[$valkey]= $set->getField("TANGGAL_DISPOSISI");
				}
				
				else if($valkey == "NOMOR")
				{
					$row[$valkey] = $set->getField($valkey);
				}
				else
					$row[$valkey]= $set->getField($valkey);
			}
			array_push($arrinfodata, $row);
		}

		// get all raw data
		$alldata = $arrinfodata;
		// print_r($alldata);exit;

		$data = [];
		// internal use; filter selected columns only from raw data
		foreach ( $alldata as $d ) {
			// $data[] = filterArray( $d, $columnsDefault );
			$data[] = $d;
		}

		// count data
		$totalRecords = $totalDisplay = count( $data );

		// filter by general search keyword
		if ( isset( $_REQUEST['search'] ) ) {
			$data         = filterKeyword( $data, $_REQUEST['search'] );
			$totalDisplay = count( $data );
		}

		if ( isset( $_REQUEST['columns'] ) && is_array( $_REQUEST['columns'] ) ) {
			foreach ( $_REQUEST['columns'] as $column ) {
				if ( isset( $column['search'] ) ) {
					$data         = filterKeyword( $data, $column['search'], $column['data'] );
					$totalDisplay = count( $data );
				}
			}
		}

		// sort
		if ( isset( $_REQUEST['order'][0]['column'] ) && $_REQUEST['order'][0]['dir'] ) {
			$column = $_REQUEST['order'][0]['column'];
			// if(count($columnsDefault) - 2 == $column){}
			// else
			// {
				$dir    = $_REQUEST['order'][0]['dir'];
				usort( $data, function ( $a, $b ) use ( $column, $dir ) {
					$a = array_slice( $a, $column, 1 );
					$b = array_slice( $b, $column, 1 );
					$a = array_pop( $a );
					$b = array_pop( $b );

					if ( $dir === 'asc' ) {
						return $a > $b ? true : false;
					}

					return $a < $b ? true : false;
				} );
			// }
		}

		// pagination length
		if ( isset( $_REQUEST['length'] ) ) {
			$data = array_splice( $data, $_REQUEST['start'], $_REQUEST['length'] );
		}

		// return array values only without the keys
		if ( isset( $_REQUEST['array_values'] ) && $_REQUEST['array_values'] ) {
			$tmp  = $data;
			$data = [];
			foreach ( $tmp as $d ) {
				$data[] = array_values( $d );
			}
		}

		$result = [
		    'recordsTotal'    => $totalRecords,
		    'recordsFiltered' => $totalDisplay,
		    'data'            => $data,
		];

		header('Content-Type: application/json');
		echo json_encode( $result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);	
	}

	function jsonpsmdraft()
	{
		$this->load->model("TrPsm");

		$cekquery= $this->input->get("c");

		$set= new TrPsm();

		if ( isset( $_REQUEST['columnsDef'] ) && is_array( $_REQUEST['columnsDef'] ) ) {
			$columnsDefault = [];
			foreach ( $_REQUEST['columnsDef'] as $field ) {
				$columnsDefault[ $field ] = "true";
			}
		}
		// print_r($columnsDefault);exit;

		$displaystart= -1;
		$displaylength= -1;

		$arrinfodata= [];

		$reqPencarian= $this->input->get("reqPencarian");
		$searchJson= "";
		$infosearch= $_REQUEST['search']["value"];

		/*if(!empty($infosearch))
		{
			$reqPencarian= $infosearch;
		}*/

		if(!empty($reqPencarian))
		{
			$searchJson= " 
			AND 
			(
				UPPER(A1.NAMA_PEMILIK) LIKE '%".strtoupper($reqPencarian)."%' OR 
				UPPER(A1.NAMA_BRAND) LIKE '%".strtoupper($reqPencarian)."%'
			)";
		}

		$sessid= $this->ID;
		$statement.= " AND A.USER_PEMBUAT_ID = '".$sessid."' AND A.STATUS_DATA IN ('DRAFT', 'REVISI')";
		// untuk buat session
		/*session_start();
		$arrsession= [];
		$_SESSION['kotak_masuk'.$reqJenisNaskahId]= $arrsession;
		$arrsession["reqTahun"]= $reqTahun;
		$arrsession["reqPilihan"]= $reqPilihan;
		$arrsession["reqStatusSurat"]= $reqStatusSurat;
		if(!empty($infosearch))
			$arrsession["reqPencarian"]= $infosearch;
		else
			$arrsession["reqPencarian"]= $reqPencarian;
		$_SESSION['kotak_masuk'.$reqJenisNaskahId]= $arrsession;*/

		
		$sOrder = " ORDER BY a.TR_LOO_ID ASC";
		$set->selectdraft(array(), $dsplyRange, $dsplyStart, $statement.$searchJson, $sOrder);

		if(!empty($cekquery))
		{
			echo $set->query;exit;
		}
		
		$infobatasdetil= $_REQUEST['start'] + $_REQUEST['length'];
		$infonomor= 0;
		while ($set->nextRow()) 
		{
			$infonomor++;
			$infojenisnaskahid= $set->getField("JENIS_NASKAH_ID");
			$infosuratmasukid= $set->getField("SURAT_MASUK_ID");

			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{
				if ($valkey == "SORDERDEFAULT")
				{
					$row[$valkey]= $set->getField("TANGGAL_DISPOSISI");
				}
				else if($valkey == "INFO_LAST_CREATE_DATE")
				{
					$row[$valkey] = datetimeToPage($set->getField($valkey), "date");
				}
				else if($valkey == "NOMOR")
				{
					$row[$valkey] = $set->getField($valkey);
				}
				else
					$row[$valkey]= $set->getField($valkey);
			}
			array_push($arrinfodata, $row);
		}

		// get all raw data
		$alldata = $arrinfodata;
		// print_r($alldata);exit;

		$data = [];
		// internal use; filter selected columns only from raw data
		foreach ( $alldata as $d ) {
			// $data[] = filterArray( $d, $columnsDefault );
			$data[] = $d;
		}

		// count data
		$totalRecords = $totalDisplay = count( $data );

		// filter by general search keyword
		if ( isset( $_REQUEST['search'] ) ) {
			$data         = filterKeyword( $data, $_REQUEST['search'] );
			$totalDisplay = count( $data );
		}

		if ( isset( $_REQUEST['columns'] ) && is_array( $_REQUEST['columns'] ) ) {
			foreach ( $_REQUEST['columns'] as $column ) {
				if ( isset( $column['search'] ) ) {
					$data         = filterKeyword( $data, $column['search'], $column['data'] );
					$totalDisplay = count( $data );
				}
			}
		}

		// sort
		if ( isset( $_REQUEST['order'][0]['column'] ) && $_REQUEST['order'][0]['dir'] ) {
			$column = $_REQUEST['order'][0]['column'];
			// if(count($columnsDefault) - 2 == $column){}
			// else
			// {
				$dir    = $_REQUEST['order'][0]['dir'];
				usort( $data, function ( $a, $b ) use ( $column, $dir ) {
					$a = array_slice( $a, $column, 1 );
					$b = array_slice( $b, $column, 1 );
					$a = array_pop( $a );
					$b = array_pop( $b );

					if ( $dir === 'asc' ) {
						return $a > $b ? true : false;
					}

					return $a < $b ? true : false;
				} );
			// }
		}

		// pagination length
		if ( isset( $_REQUEST['length'] ) ) {
			$data = array_splice( $data, $_REQUEST['start'], $_REQUEST['length'] );
		}

		// return array values only without the keys
		if ( isset( $_REQUEST['array_values'] ) && $_REQUEST['array_values'] ) {
			$tmp  = $data;
			$data = [];
			foreach ( $tmp as $d ) {
				$data[] = array_values( $d );
			}
		}

		$result = [
		    'recordsTotal'    => $totalRecords,
		    'recordsFiltered' => $totalDisplay,
		    'data'            => $data,
		];

		header('Content-Type: application/json');
		echo json_encode( $result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);	
	}

	function jsonpsmperlupersetujuan()
	{
		$this->load->model("TrPsm");

		$reqStatusSurat= $this->input->get("reqStatusSurat");
		$cekquery= $this->input->get("c");

		$set= new TrPsm();

		if ( isset( $_REQUEST['columnsDef'] ) && is_array( $_REQUEST['columnsDef'] ) ) {
			$columnsDefault = [];
			foreach ( $_REQUEST['columnsDef'] as $field ) {
				$columnsDefault[ $field ] = "true";
			}
		}
		// print_r($columnsDefault);exit;

		$displaystart= -1;
		$displaylength= -1;

		$arrinfodata= [];

		$reqPencarian= $this->input->get("reqPencarian");
		$searchJson= "";
		$infosearch= $_REQUEST['search']["value"];

		/*if(!empty($infosearch))
		{
			$reqPencarian= $infosearch;
		}*/

		if(!empty($reqPencarian))
		{
			$searchJson= " 
			AND 
			(
				UPPER(A1.NAMA_PEMILIK) LIKE '%".strtoupper($reqPencarian)."%' OR 
				UPPER(A1.NAMA_BRAND) LIKE '%".strtoupper($reqPencarian)."%'
			)";
		}

		$sessid= $this->ID;
		$statement.= " AND A.STATUS_DATA IN ('PARAF', 'VALIDASI')";

		if($reqStatusSurat == "PERLU_PERSETUJUAN")
			$statement.= " AND A.USER_POSISI_PARAF_ID = '".$sessid."'";
		else
			$statement.= " AND A.USER_LIHAT_STATUS LIKE '%".$sessid."%'";

		// untuk buat session
		/*session_start();
		$arrsession= [];
		$_SESSION['kotak_masuk'.$reqJenisNaskahId]= $arrsession;
		$arrsession["reqTahun"]= $reqTahun;
		$arrsession["reqPilihan"]= $reqPilihan;
		$arrsession["reqStatusSurat"]= $reqStatusSurat;
		if(!empty($infosearch))
			$arrsession["reqPencarian"]= $infosearch;
		else
			$arrsession["reqPencarian"]= $reqPencarian;
		$_SESSION['kotak_masuk'.$reqJenisNaskahId]= $arrsession;*/

		
		$sOrder = " ORDER BY a.TR_LOO_ID ASC";
		$set->selectdraft(array(), $dsplyRange, $dsplyStart, $statement.$searchJson, $sOrder);

		if(!empty($cekquery))
		{
			echo $set->query;exit;
		}
		
		$infobatasdetil= $_REQUEST['start'] + $_REQUEST['length'];
		$infonomor= 0;
		while ($set->nextRow()) 
		{
			$infonomor++;
			$infojenisnaskahid= $set->getField("JENIS_NASKAH_ID");
			$infosuratmasukid= $set->getField("SURAT_MASUK_ID");

			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{
				if ($valkey == "SORDERDEFAULT")
				{
					$row[$valkey]= $set->getField("TANGGAL_DISPOSISI");
				}
				
				else if($valkey == "NOMOR")
				{
					$row[$valkey] = $set->getField($valkey);
				}
				else
					$row[$valkey]= $set->getField($valkey);
			}
			array_push($arrinfodata, $row);
		}

		// get all raw data
		$alldata = $arrinfodata;
		// print_r($alldata);exit;

		$data = [];
		// internal use; filter selected columns only from raw data
		foreach ( $alldata as $d ) {
			// $data[] = filterArray( $d, $columnsDefault );
			$data[] = $d;
		}

		// count data
		$totalRecords = $totalDisplay = count( $data );

		// filter by general search keyword
		if ( isset( $_REQUEST['search'] ) ) {
			$data         = filterKeyword( $data, $_REQUEST['search'] );
			$totalDisplay = count( $data );
		}

		if ( isset( $_REQUEST['columns'] ) && is_array( $_REQUEST['columns'] ) ) {
			foreach ( $_REQUEST['columns'] as $column ) {
				if ( isset( $column['search'] ) ) {
					$data         = filterKeyword( $data, $column['search'], $column['data'] );
					$totalDisplay = count( $data );
				}
			}
		}

		// sort
		if ( isset( $_REQUEST['order'][0]['column'] ) && $_REQUEST['order'][0]['dir'] ) {
			$column = $_REQUEST['order'][0]['column'];
			// if(count($columnsDefault) - 2 == $column){}
			// else
			// {
				$dir    = $_REQUEST['order'][0]['dir'];
				usort( $data, function ( $a, $b ) use ( $column, $dir ) {
					$a = array_slice( $a, $column, 1 );
					$b = array_slice( $b, $column, 1 );
					$a = array_pop( $a );
					$b = array_pop( $b );

					if ( $dir === 'asc' ) {
						return $a > $b ? true : false;
					}

					return $a < $b ? true : false;
				} );
			// }
		}

		// pagination length
		if ( isset( $_REQUEST['length'] ) ) {
			$data = array_splice( $data, $_REQUEST['start'], $_REQUEST['length'] );
		}

		// return array values only without the keys
		if ( isset( $_REQUEST['array_values'] ) && $_REQUEST['array_values'] ) {
			$tmp  = $data;
			$data = [];
			foreach ( $tmp as $d ) {
				$data[] = array_values( $d );
			}
		}

		$result = [
		    'recordsTotal'    => $totalRecords,
		    'recordsFiltered' => $totalDisplay,
		    'data'            => $data,
		];

		header('Content-Type: application/json');
		echo json_encode( $result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);	
	}

	function jsonpsmstatus()
	{
		$this->load->model("TrPsm");

		$reqStatusSurat= $this->input->get("reqStatusSurat");
		$cekquery= $this->input->get("c");

		$set= new TrPsm();

		if ( isset( $_REQUEST['columnsDef'] ) && is_array( $_REQUEST['columnsDef'] ) ) {
			$columnsDefault = [];
			foreach ( $_REQUEST['columnsDef'] as $field ) {
				$columnsDefault[ $field ] = "true";
			}
		}
		// print_r($columnsDefault);exit;

		$displaystart= -1;
		$displaylength= -1;

		$arrinfodata= [];

		$reqPencarian= $this->input->get("reqPencarian");
		$searchJson= "";
		$infosearch= $_REQUEST['search']["value"];

		/*if(!empty($infosearch))
		{
			$reqPencarian= $infosearch;
		}*/

		if(!empty($reqPencarian))
		{
			$searchJson= " 
			AND 
			(
				UPPER(A1.NAMA_PEMILIK) LIKE '%".strtoupper($reqPencarian)."%' OR 
				UPPER(A1.NAMA_BRAND) LIKE '%".strtoupper($reqPencarian)."%'
			)";
		}

		$sessid= $this->ID;
		$statement= "";

		if($reqStatusSurat == "PARAF")
			$statement.= " AND A.USER_POSISI_PARAF_ID != '".$sessid."' AND A.USER_LIHAT_STATUS LIKE '%".$sessid."%' AND A.STATUS_DATA IN ('PARAF', 'VALIDASI')";
		else
			$statement= " AND A.STATUS_DATA IN ('REVISI')";

		// untuk buat session
		/*session_start();
		$arrsession= [];
		$_SESSION['kotak_masuk'.$reqJenisNaskahId]= $arrsession;
		$arrsession["reqTahun"]= $reqTahun;
		$arrsession["reqPilihan"]= $reqPilihan;
		$arrsession["reqStatusSurat"]= $reqStatusSurat;
		if(!empty($infosearch))
			$arrsession["reqPencarian"]= $infosearch;
		else
			$arrsession["reqPencarian"]= $reqPencarian;
		$_SESSION['kotak_masuk'.$reqJenisNaskahId]= $arrsession;*/

		
		$sOrder = " ORDER BY a.TR_LOO_ID ASC";
		$set->selectdraft(array(), $dsplyRange, $dsplyStart, $statement.$searchJson, $sOrder);

		if(!empty($cekquery))
		{
			echo $set->query;exit;
		}
		
		$infobatasdetil= $_REQUEST['start'] + $_REQUEST['length'];
		$infonomor= 0;
		while ($set->nextRow()) 
		{
			$infonomor++;
			$infojenisnaskahid= $set->getField("JENIS_NASKAH_ID");
			$infosuratmasukid= $set->getField("SURAT_MASUK_ID");

			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{
				if ($valkey == "SORDERDEFAULT")
				{
					$row[$valkey]= $set->getField("TANGGAL_DISPOSISI");
				}
				
				else if($valkey == "NOMOR")
				{
					$row[$valkey] = $set->getField($valkey);
				}
				else
					$row[$valkey]= $set->getField($valkey);
			}
			array_push($arrinfodata, $row);
		}

		// get all raw data
		$alldata = $arrinfodata;
		// print_r($alldata);exit;

		$data = [];
		// internal use; filter selected columns only from raw data
		foreach ( $alldata as $d ) {
			// $data[] = filterArray( $d, $columnsDefault );
			$data[] = $d;
		}

		// count data
		$totalRecords = $totalDisplay = count( $data );

		// filter by general search keyword
		if ( isset( $_REQUEST['search'] ) ) {
			$data         = filterKeyword( $data, $_REQUEST['search'] );
			$totalDisplay = count( $data );
		}

		if ( isset( $_REQUEST['columns'] ) && is_array( $_REQUEST['columns'] ) ) {
			foreach ( $_REQUEST['columns'] as $column ) {
				if ( isset( $column['search'] ) ) {
					$data         = filterKeyword( $data, $column['search'], $column['data'] );
					$totalDisplay = count( $data );
				}
			}
		}

		// sort
		if ( isset( $_REQUEST['order'][0]['column'] ) && $_REQUEST['order'][0]['dir'] ) {
			$column = $_REQUEST['order'][0]['column'];
			// if(count($columnsDefault) - 2 == $column){}
			// else
			// {
				$dir    = $_REQUEST['order'][0]['dir'];
				usort( $data, function ( $a, $b ) use ( $column, $dir ) {
					$a = array_slice( $a, $column, 1 );
					$b = array_slice( $b, $column, 1 );
					$a = array_pop( $a );
					$b = array_pop( $b );

					if ( $dir === 'asc' ) {
						return $a > $b ? true : false;
					}

					return $a < $b ? true : false;
				} );
			// }
		}

		// pagination length
		if ( isset( $_REQUEST['length'] ) ) {
			$data = array_splice( $data, $_REQUEST['start'], $_REQUEST['length'] );
		}

		// return array values only without the keys
		if ( isset( $_REQUEST['array_values'] ) && $_REQUEST['array_values'] ) {
			$tmp  = $data;
			$data = [];
			foreach ( $tmp as $d ) {
				$data[] = array_values( $d );
			}
		}

		$result = [
		    'recordsTotal'    => $totalRecords,
		    'recordsFiltered' => $totalDisplay,
		    'data'            => $data,
		];

		header('Content-Type: application/json');
		echo json_encode( $result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);	
	}

}