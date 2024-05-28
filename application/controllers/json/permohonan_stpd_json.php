<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/class-list-util.php");
include_once("functions/class-list-util-serverside.php");

class permohonan_stpd_json extends CI_Controller
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
	}

	function json()
	{
		$this->load->model("PermohonanStpd");

		$reqStatusSurat= $this->input->get("reqStatusSurat");

		$set= new PermohonanStpd();

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
		if(!empty($reqPencarian))
		{
			$searchJson= " 
			AND 
			(
				UPPER(A.NOMOR) LIKE '%".strtoupper($reqPencarian)."%'
				OR UPPER(A.DOKUMEN_ACUAN) LIKE '%".strtoupper($reqPencarian)."%'
			)";
		}

		$satuankerjaganti= $this->SATUAN_KERJA_ID_ASAL;
		$userid= $this->ID;
		if($reqStatusSurat == "draft" || $reqStatusSurat == "revisi")
		{
			$statement= " AND A.SATUAN_KERJA_ID_ASAL= '".$satuankerjaganti."' AND A.STATUS_SURAT= '".strtoupper($reqStatusSurat)."' ";
		}
		else if($reqStatusSurat == "setuju" || $reqStatusSurat == "setujudraft" || $reqStatusSurat == "setujurevisi")
		{
			// SATUAN_KERJA_ID_ASAL
			$statement= " AND (A.APPROVAL_NIP= '".$userid."' OR A.LAST_CREATE_USER = '".$userid."') AND A.STATUS_SURAT= '".strtoupper($reqStatusSurat)."' ";
		}
		else if($reqStatusSurat == "selesai")
		{
			$statement= " AND A.LAST_CREATE_USER = '".$userid."' AND A.STATUS_SURAT= '".strtoupper($reqStatusSurat)."' ";
		}

		$sOrder = " ORDER BY TANGGAL DESC";
		$set->selectByParamsKeluar(array(), $dsplyRange, $dsplyStart, $statement.$searchJson, $sOrder);
		// echo $set->query;exit;

		$infonomor= 0;
		while ($set->nextRow()) 
		{
			$infonomor++;
			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{
				if ($valkey == "SORDERDEFAULT")
					$row[$valkey]= "1";
				// else if($valkey == "TANGGAL")
				// 	$row[$valkey] = getFormattedExtDateTimeCheck($set->getField($valkey));
				else if($valkey == "INFO_STATUS_TANGGAL")
					$row[$valkey] = getFormattedExtDateTimeCheck($set->getField($valkey));
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
			if(count($columnsDefault) - 2 == $column){}
			else
			{
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
			}
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

	function json_status()
	{
		$this->load->model("PermohonanStpd");

		$reqStatusSurat= $this->input->get("reqStatusSurat");
		$cekquery= $this->input->get("c");

		$set= new PermohonanStpd();

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
		if(!empty($reqPencarian))
		{
			$searchJson= " 
			AND 
			(
				UPPER(A.NOMOR) LIKE '%".strtoupper($reqPencarian)."%'
				OR UPPER(A.DOKUMEN_ACUAN) LIKE '%".strtoupper($reqPencarian)."%'
			)";
		}

		$satuankerjaganti= $this->SATUAN_KERJA_ID_ASAL;
		$userid= $this->ID;

		$statement = "
		AND 
		(
			A.SATUAN_KERJA_ID_ASAL = '".$satuankerjaganti."'
			OR
			(
				A.PENGAJUAN_DISETUJUI_ID = '".$satuankerjaganti."'
				AND A.STATUS_SURAT NOT IN ('DRAFT', 'REVISI')
			)
		)";

		// TAMBAHAN DI TERUSKAN
		$statement .= "
		AND 
		(
			--untuk atasan
			(
				COALESCE(NULLIF(A.USER_BANTU_APPROVAL_NIP, ''), NULL) IS NOT NULL
				AND
				COALESCE(NULLIF(A.USER_BANTU_APPROVAL_KIRIM, ''), NULL) IS NOT NULL
			)
			--untuk user bantu
			OR
			(
				A.USER_BANTU_APPROVAL_NIP = '".$userid."'
				AND
				COALESCE(NULLIF(A.USER_BANTU_APPROVAL_KIRIM, ''), NULL) IS NOT NULL
			)
			-- untuk pembuat
			OR
			(
				A.SATUAN_KERJA_ID_ASAL = '".$satuankerjaganti."'
				OR
				(
					A.PENGAJUAN_DISETUJUI_ID = '".$satuankerjaganti."'
					AND A.STATUS_SURAT NOT IN ('DRAFT', 'REVISI')
				)
			)
			OR
			(
				COALESCE(NULLIF(A.USER_BANTU_APPROVAL_NIP, ''), NULL) IS NULL
			)
		)";
		
		$sOrder = " ORDER BY TANGGAL DESC";

		$set->selectByParamsStatus(array(), $dsplyRange, $dsplyStart, $statement.$searchJson, $sOrder);
		if(!empty($cekquery))
		{
			echo $set->query;exit;
		}

		$infonomor= 0;
		while ($set->nextRow()) 
		{
			$infonomor++;
			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{
				if ($valkey == "SORDERDEFAULT")
					$row[$valkey]= "1";
				// else if($valkey == "TANGGAL")
				// 	$row[$valkey] = getFormattedExtDateTimeCheck($set->getField($valkey));
				else if($valkey == "INFO_STATUS_TANGGAL")
					$row[$valkey] = getFormattedExtDateTimeCheck($set->getField($valkey));
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
			if(count($columnsDefault) - 2 == $column){}
			else
			{
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
			}
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

	function json_persetujuan()
	{
		$this->load->model("PermohonanStpd");

		$reqStatusSurat= $this->input->get("reqStatusSurat");
		$cekquery= $this->input->get("c");

		$set= new PermohonanStpd();

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
		if(!empty($reqPencarian))
		{
			$searchJson= " 
			AND 
			(
				UPPER(A.NOMOR) LIKE '%".strtoupper($reqPencarian)."%'
				OR UPPER(A.DOKUMEN_ACUAN) LIKE '%".strtoupper($reqPencarian)."%'
			)";
		}

		$satuankerjaganti= $this->SATUAN_KERJA_ID_ASAL;
		$userid= $this->ID;

		$statement = "
		AND
		(
			(
				A.PENGAJUAN_DISETUJUI_ID = '".$satuankerjaganti."' AND A.STATUS_SURAT IN ('KIRIM')
				--TAMBAHAN KL ADA USER BANTU
				AND
				(
					--untuk atasan
					(
						A.APPROVAL_NIP = '".$userid."'
						AND
						COALESCE(NULLIF(A.USER_BANTU_APPROVAL_NIP, ''), NULL) IS NOT NULL
						AND
						COALESCE(NULLIF(A.USER_BANTU_APPROVAL_KIRIM, ''), NULL) IS NOT NULL
					)
					--untuk user bantu
					OR
					(
						A.USER_BANTU_APPROVAL_NIP = '".$userid."'
						AND
						COALESCE(NULLIF(A.USER_BANTU_APPROVAL_KIRIM, ''), NULL) IS NULL
					)
					OR
					(
						COALESCE(NULLIF(A.USER_BANTU_APPROVAL_NIP, ''), NULL) IS NULL
					)
				)
			)
			OR
			(
				A1.SATUAN_KERJA_ID_TUJUAN = '".$satuankerjaganti."' AND A.STATUS_SURAT IN ('SETUJUKIRIM')
			)
		)
		";
		
		$sOrder = " ORDER BY TANGGAL DESC";

		$set->selectByParamsPersetujuan(array(), $dsplyRange, $dsplyStart, $statement.$searchJson, $sOrder);
		if(!empty($cekquery))
		{
			echo $set->query;exit;
		}

		$infonomor= 0;
		while ($set->nextRow()) 
		{
			$infonomor++;
			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{
				if ($valkey == "SORDERDEFAULT")
					$row[$valkey]= "1";
				// else if($valkey == "TANGGAL")
				// 	$row[$valkey] = getFormattedExtDateTimeCheck($set->getField($valkey));
				else if($valkey == "INFO_STATUS_TANGGAL")
					$row[$valkey] = getFormattedExtDateTimeCheck($set->getField($valkey));
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
			if(count($columnsDefault) - 2 == $column){}
			else
			{
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
			}
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