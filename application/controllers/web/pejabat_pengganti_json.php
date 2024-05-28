<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class pejabat_pengganti_json extends CI_Controller {

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
		$this->KD_LEVEL = $this->kauth->getInstance()->getIdentity()->KD_LEVEL;  
		$this->JENIS_KELAMIN = $this->kauth->getInstance()->getIdentity()->JENIS_KELAMIN;  
		
	}
	
	function json() 
	{
		$this->load->model("PejabatPengganti");
		$pejabat_pengganti = new PejabatPengganti();

		$reqKategori = $this->input->get("reqKategori");
		// echo $reqKategori;exit;

		$aColumns= array("PEJABAT_PENGGANTI_ID", "SATUAN_KERJA", "PEGAWAI_ID", "NAMA", "PEGAWAI_ID_PENGGANTI", "NAMA_PENGGANTI", "TANGGAL_MULAI", "TANGGAL_SELESAI", "STATUS_AKTIF");
		$aColumnsAlias= $aColumns;
		
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
			if ( trim($sOrder) == "ORDER BY PEJABAT_PENGGANTI_ID asc" )
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

		
		$statement= " AND (UPPER(A.NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%')";

		$reqUnitKerjaId= $this->CABANG_ID;
		if ($reqUnitKerjaId == "PST"){}
		else
			$statement.= " AND SK_CABANG_ID = '".$reqUnitKerjaId."'";

		$allRecord = $pejabat_pengganti->getCountByParamsSatuanKerja(array(), $statement_privacy.$statement);
		// echo $allRecord;exit;
		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter =  $pejabat_pengganti->getCountByParamsSatuanKerja(array(), $statement_privacy.$statement);
		
		 $pejabat_pengganti->selectByParamsSatuanKerja(array(), $dsplyRange, $dsplyStart, $statement_privacy.$statement, $sOrder);
		
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
		
		while($pejabat_pengganti->nextRow())		
		{		
			$row = array();		
			for ( $i=0 ; $i<count($aColumns) ; $i++ )		
			{	
				if($aColumns[$i] == "KETERANGAN")
					$row[] = truncate($pejabat_pengganti->getField($aColumns[$i]), 2);
				elseif($aColumns[$i] == "STATUS_AKTIF")
				{
					$infostatusaktif= $pejabat_pengganti->getField($aColumns[$i]);
					$infostatusaktiflabel= "Aktif";
					if($infostatusaktif == "1")
						$infostatusaktiflabel= "Tidak Aktif";

					$row[]= $infostatusaktiflabel;
				}
				else
					$row[] = $pejabat_pengganti->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		echo json_encode( $output );	

		
	}


	function json_kelola() 
	{
		$this->load->model("PejabatPengganti");
		$pejabat_pengganti = new PejabatPengganti();

		$reqKategori = $this->input->get("reqKategori");
		// echo $reqKategori;exit;


		$aColumns		= array("PEJABAT_PENGGANTI_ID", "PEGAWAI_ID_PENGGANTI", "NAMA_PENGGANTI", "TANGGAL_MULAI", "TANGGAL_SELESAI");
		$aColumnsAlias	= array("PEJABAT_PENGGANTI_ID", "PEGAWAI_ID_PENGGANTI", "NAMA_PENGGANTI", "TANGGAL_MULAI", "TANGGAL_SELESAI");

		
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
			if ( trim($sOrder) == "ORDER BY PEJABAT_PENGGANTI_ID asc" )
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

		
		$statement_privacy = " AND A.SATUAN_KERJA_ID = '".$this->SATUAN_KERJA_ID_ASAL."' ";
		
		 $statement= " AND (UPPER(A.NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%')";
		$allRecord = $pejabat_pengganti->getCountByParams(array(), $statement_privacy.$statement);
		// echo $allRecord;exit;
		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter =  $pejabat_pengganti->getCountByParams(array(), $statement_privacy.$statement);
		
		 $pejabat_pengganti->selectByParams(array(), $dsplyRange, $dsplyStart, $statement_privacy.$statement, $sOrder);
		
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
		
		while($pejabat_pengganti->nextRow())		
		{		
			$row = array();		
			for ( $i=0 ; $i<count($aColumns) ; $i++ )		
			{	
				if($aColumns[$i] == "KETERANGAN")
					$row[] = truncate($pejabat_pengganti->getField($aColumns[$i]), 2);
				else
					$row[] = $pejabat_pengganti->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		echo json_encode( $output );	

		
	}
		
	function add() 
	{
		$this->load->model("PejabatPengganti");
		$pejabat_pengganti = new PejabatPengganti();

		$reqMode 					= $this->input->post("reqMode");
		$reqId 						= $this->input->post("reqId");
		
		$reqSatuanKerja				= $this->input->post("reqSatuanKerja");
		$reqSatuanKerjaId			= $this->input->post("reqSatuanKerjaId");
		$reqPegawaiId				= $this->input->post("reqPegawaiId");
		$reqNama					= $this->input->post("reqNama");
		$reqPegawaiIdPengganti		= $this->input->post("reqPegawaiIdPengganti");
		$reqNamaPengganti			= $this->input->post("reqNamaPengganti");
		$reqTanggalMulai			= $this->input->post("reqTanggalMulai");
		$reqTanggalSelesai			= $this->input->post("reqTanggalSelesai");

		// tambahan khusus
		$reqAnTambahan= $this->input->post("reqAnTambahan");
		$reqStatusAktif= $this->input->post("reqStatusAktif");
		
		if($reqSatuanKerjaId == "")
		{
			$reqSatuanKerjaId = $this->SATUAN_KERJA_ID_ASAL;
			$reqSatuanKerja   = $this->SATUAN_KERJA_ASAL;
		}
		
		/* CHECK APAKAH ADA DATA PADA TANGGAL TSB */
		$adaData = $pejabat_pengganti->getCountByParams(
			array(
				"SATUAN_KERJA_ID" => $reqSatuanKerjaId
				, "NOT PEJABAT_PENGGANTI_ID" => (int)$reqId
			)
			,
			" AND 
			(
				TO_DATE('".$reqTanggalMulai."', 'DD-MM-YYYY') BETWEEN TANGGAL_MULAI AND TANGGAL_SELESAI OR
				TO_DATE('".$reqTanggalSelesai."', 'DD-MM-YYYY') BETWEEN TANGGAL_MULAI AND TANGGAL_SELESAI
			)
			AND COALESCE(NULLIF(STATUS_AKTIF, ''), 'X') = 'X'
		");

		if($adaData > 0)
		{
			echo "error-Pejabat terpilih telah digantikan pada range tanggal terpilih.";
			return;	
		}
			
		$pejabat_pengganti->setField("PEJABAT_PENGGANTI_ID", $reqId);
		$pejabat_pengganti->setField("SATUAN_KERJA_ID", $reqSatuanKerjaId);
		$pejabat_pengganti->setField("SATUAN_KERJA", $reqSatuanKerja);
		$pejabat_pengganti->setField("PEGAWAI_ID", $reqPegawaiId);
		$pejabat_pengganti->setField("NAMA", $reqNama);
		$pejabat_pengganti->setField("PEGAWAI_ID_PENGGANTI", $reqPegawaiIdPengganti);
		$pejabat_pengganti->setField("NAMA_PENGGANTI", $reqNamaPengganti);
		$pejabat_pengganti->setField("TANGGAL_MULAI", dateToDBCheck($reqTanggalMulai));
		$pejabat_pengganti->setField("TANGGAL_SELESAI", dateToDBCheck($reqTanggalSelesai));
		$pejabat_pengganti->setField("AN_TAMBAHAN", $reqAnTambahan);
		$pejabat_pengganti->setField("STATUS_AKTIF", $reqStatusAktif);

		if($reqMode == "insert")
		{
			$pejabat_pengganti->setField("LAST_CREATE_USER", $this->USERNAME);
			$pejabat_pengganti->insert();
		}
		else
		{
			$pejabat_pengganti->setField("LAST_UPDATE_USER", $this->USERNAME);
			$pejabat_pengganti->update();
		}	
		
		echo "success-Data berhasil disimpan.";
	
	}
	
	function delete() 
	{
		$reqId	= $this->input->get('reqId');
		$this->load->model("PejabatPengganti");
		$pejabat_pengganti = new PejabatPengganti();
		$pejabat_pengganti->setField("PEJABAT_PENGGANTI_ID", $reqId);
		if($pejabat_pengganti->delete())
			$arrJson["PESAN"] = "Data berhasil dihapus.";
		else
			$arrJson["PESAN"] = "Data gagal dihapus.";		
		echo json_encode($arrJson);
	}	

	
	function combo() 
	{
		$this->load->model("PejabatPengganti");
		$pejabat_pengganti = new PejabatPengganti();
		$pejabat_pengganti->selectByParams(array("NOT PEJABAT_PENGGANTI_ID" => "0"));
		$i = 0;
		while($pejabat_pengganti->nextRow())
		{
			$arr_json[$i]['id']		= $pejabat_pengganti->getField("PEJABAT_PENGGANTI_ID");
			$arr_json[$i]['text']	= $pejabat_pengganti->getField("NAMA");
			$i++;
		}
		echo json_encode($arr_json);
	}
	
}

