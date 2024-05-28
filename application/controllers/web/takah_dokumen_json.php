<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class takah_dokumen_json extends CI_Controller {

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
		$this->SATUAN_KERJA_ID_ASAL	= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_ID_ASAL;  
		$this->CABANG		= $this->kauth->getInstance()->getIdentity()->CABANG;  
		
		
	}
	
	function json() 
	{
		$this->load->model("TakahDokumen");
		$takah_dokumen = new TakahDokumen();

		$reqId = $this->input->get("reqId");
		// echo $reqKategori;exit;

		$aColumns		= array("TAKAH_DOKUMEN_ID", "KODE", "NOMOR", "TANGGAL", "NAMA", "ASAL", "LAMPIRAN");
		$aColumnsAlias	= array("TAKAH_DOKUMEN_ID", "KODE", "NOMOR", "TANGGAL", "NAMA", "ASAL", "LAMPIRAN");

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
			if ( trim($sOrder) == "ORDER BY A.TANGGAL asc" )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.TANGGAL DESC";
				 
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

		if($reqId == "")
		{}
		else
		{
			$statement_privacy .= " AND A.TAKAH_ID = '".$reqId."'";
		}
		
		$allRecord = $takah_dokumen->getCountByParamsMonitoring(array(), $statement_privacy.$statement);
		// echo $allRecord;exit;
		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter =  $takah_dokumen->getCountByParamsMonitoring(array(), $statement_privacy.$statement);
		
		$takah_dokumen->selectByParamsMonitoring(array(), $dsplyRange, $dsplyStart, $statement_privacy.$statement, $sOrder);

		// echo $takah_dokumen->query;exit;
		
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
		
		while($takah_dokumen->nextRow())		
		{		
			$row = array();		
			for ( $i=0 ; $i<count($aColumns) ; $i++ )		
			{	
				if($aColumns[$i] == "KETERANGAN")
					$row[] = truncate($takah_dokumen->getField($aColumns[$i]), 2);
				else if($aColumns[$i] == "LAMPIRAN")
					$row[] = "<a href='uploads/".$takah_dokumen->getField($aColumns[$i])."' target='_blank'>".$takah_dokumen->getField($aColumns[$i])."</a>";
				else
					$row[] = $takah_dokumen->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		echo json_encode( $output );	

		
	}
	
	
	function add() 
	{
		$this->load->model("TakahDokumen");
		$this->load->model("TakahDokumenTujuan");
		$takah_dokumen = new TakahDokumen();

		$reqMode 					= $this->input->post("reqMode");
		$reqId 						= $this->input->post("reqId");
		
		$reqTakahId					= $this->input->post("reqTakahId");
		$reqKode					= $this->input->post("reqKode");
		$reqAsal					= $this->input->post("reqAsal");
		$reqNomor					= $this->input->post("reqNomor");
		$reqNama					= $this->input->post("reqNama");
		$reqTanggal					= $this->input->post("reqTanggal");
		$reqKeterangan				= $this->input->post("reqKeterangan");

		//TAKAH DOKUMEN TUJUAN
		$reqTakahDokumenTujuanId	= $this->input->post("reqTakahDokumenTujuanId");
		$reqSatuanKerjaIdTujuan		= $this->input->post("reqSatuanKerjaIdTujuan");
		$reqTanggalKirim			= $this->input->post("reqTanggalKirim");
		$reqTanggalKembali			= $this->input->post("reqTanggalKembali");

		/* WAJIB UNTUK UPLOAD DATA */	
		$this->load->library("FileHandler");
		$file = new FileHandler();
		$FILE_DIR = "uploads/";
		$reqLinkFile 			= $_FILES["reqLinkFile"];
		$reqLinkFileTempSize	=  $this->input->post("reqLinkFileTempSize");
		$reqLinkFileTempTipe	=  $this->input->post("reqLinkFileTempTipe");
		$reqLinkFileTemp		=  $this->input->post("reqLinkFileTemp");

		$i=0;
		$reqJenis   = "TAKAH";
		$renameFile = $reqJenis.date("dmYhis").rand().".".getExtension($reqLinkFile['name'][$i]);
		
		if($file->uploadToDirArray('reqLinkFile', $FILE_DIR, $renameFile, $i))
		{		
			$insertLinkSize = $file->uploadedSize;
			$insertLinkTipe =  $file->uploadedExtension;
			$insertLinkFile =  $renameFile;
		}
		else
		{		
			$insertLinkSize =  $reqLinkFileTempSize[$i];
			$insertLinkTipe =  $reqLinkFileTempTipe[$i];
			$insertLinkFile =  $reqLinkFileTemp[$i];
		}		
		
		$takah_dokumen->setField("TAKAH_ID", $reqTakahId);
		$takah_dokumen->setField("CABANG_ID", $this->CABANG_ID);
		$takah_dokumen->setField("SATUAN_KERJA_ID", $this->SATUAN_KERJA_ID_ASAL);
		$takah_dokumen->setField("KODE", $reqKode);
		$takah_dokumen->setField("ASAL", $reqAsal);
		$takah_dokumen->setField("NOMOR", $reqNomor);
		$takah_dokumen->setField("NAMA", $reqNama);
		$takah_dokumen->setField("TANGGAL", dateToDbCheck($reqTanggal));
		$takah_dokumen->setField("KETERANGAN", $reqKeterangan);
		$takah_dokumen->setField("LAMPIRAN", $insertLinkFile);
		
		if($reqMode == "insert")
		{
			$takah_dokumen->setField("LAST_CREATE_USER", $this->USERNAME);
			$takah_dokumen->setField("LAST_CREATED_DATE", "CURRENT_DATE");
			$takah_dokumen->insert();

			$reqId = $takah_dokumen->id;

			for($i=0;$i<count($reqTakahDokumenTujuanId); $i++)
			{
				if($reqSatuanKerjaIdTujuan[$i] == "")
				{}
				else
				{
					$takah_dokumen_tujuan = new TakahDokumenTujuan();
					$takah_dokumen_tujuan->setField("TAKAH_DOKUMEN_ID", $reqId);
					$takah_dokumen_tujuan->setField("SATUAN_KERJA_ID_TUJUAN", $reqSatuanKerjaIdTujuan[$i]);
					$takah_dokumen_tujuan->setField("SATUAN_KERJA", $reqSatuanKerja[$i]);
					$takah_dokumen_tujuan->setField("TANGGAL_KIRIM", dateToDbCheck($reqTanggalKirim[$i]));
					$takah_dokumen_tujuan->setField("TANGGAL_KEMBALI", dateToDbCheck($reqTanggalKembali[$i]));
					$takah_dokumen_tujuan->setField("LAST_CREATE_USER", $this->USERNAME);
					$takah_dokumen_tujuan->setField("LAST_CREATED_DATE", "CURRENT_DATE");
					$takah_dokumen_tujuan->insert();
					unset($takah_dokumen_tujuan);
				}
			}
		}
		else
		{
			$takah_dokumen->setField("TAKAH_DOKUMEN_ID", $reqId);
			$takah_dokumen->setField("LAST_UPDATE_USER", $this->USERNAME);
			$takah_dokumen->setField("LAST_UPDATED_DATE", "CURRENT_DATE");
			$takah_dokumen->update();

			$takah_dokumen_tujuan = new TakahDokumenTujuan();
			$takah_dokumen_tujuan->setField("TAKAH_DOKUMEN_ID", $reqId);
			$takah_dokumen_tujuan->deleteTakah();

			for($i=0;$i<count($reqTakahDokumenTujuanId); $i++)
			{
				if($reqSatuanKerjaIdTujuan[$i] == "")
				{}
				else
				{
					$takah_dokumen_tujuan = new TakahDokumenTujuan();
					$takah_dokumen_tujuan->setField("TAKAH_DOKUMEN_ID", $reqId);
					$takah_dokumen_tujuan->setField("SATUAN_KERJA_ID_TUJUAN", $reqSatuanKerjaIdTujuan[$i]);
					$takah_dokumen_tujuan->setField("SATUAN_KERJA", $reqSatuanKerja[$i]);
					$takah_dokumen_tujuan->setField("TANGGAL_KIRIM", dateToDbCheck($reqTanggalKirim[$i]));
					$takah_dokumen_tujuan->setField("TANGGAL_KEMBALI", dateToDbCheck($reqTanggalKembali[$i]));
					$takah_dokumen_tujuan->setField("LAST_CREATE_USER", $this->USERNAME);
					$takah_dokumen_tujuan->setField("LAST_CREATED_DATE", "CURRENT_DATE");
					$takah_dokumen_tujuan->insert();
					unset($takah_dokumen_tujuan);
				}
			}

		}

		echo "Data berhasil disimpan.";
	}
	
	
	function delete() 
	{
		$reqId	= $this->input->get('reqId');
		$this->load->model("TakahDokumen");
		$takah_dokumen = new TakahDokumen();

		$takah_dokumen->setField("TAKAH_DOKUMEN_ID", $reqId);

		if($takah_dokumen->delete())
			$arrJson["PESAN"] = "Data berhasil dihapus.";
		else
			$arrJson["PESAN"] = "Data gagal dihapus.";		
		
		echo json_encode($arrJson);
	}
	
	
}

