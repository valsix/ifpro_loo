<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class nomor_surat_json extends CI_Controller {

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
		$this->load->model("NomorSurat");
		$nomor_surat = new NomorSurat();

		$reqSatuanKerjaId = $this->input->get("reqSatuanKerjaId");
		$reqJenisNaskahId = $this->input->get("reqJenisNaskahId");
		// echo $reqKategori;exit;

		$aColumns		= array("SATUAN_KERJA_ID", "SURAT_MASUK_ID", "NOMOR", "TANGGAL_SURAT", "PERUNTUKAN", "SATUAN_KERJA", "SATUAN_KERJA", "JENIS_NASKAH", "TIPE_NASKAH_KET");
		$aColumnsAlias	= array("SATUAN_KERJA_ID", "SURAT_MASUK_ID", "NOMOR", "TANGGAL_SURAT", "PERUNTUKAN", "SATUAN_KERJA", "SATUAN_KERJA", "JENIS_NASKAH", "TIPE_NASKAH_KET");

		
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
			if ( trim($sOrder) == "ORDER BY A.SATUAN_KERJA_ID asc" )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY B.NOMOR asc";
				 
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

		if($reqSatuanKerjaId == "")
		{}
		else
		{
			$statement_privacy .= " AND A.SATUAN_KERJA_ID = '".$reqSatuanKerjaId."'";
		}

		if($reqJenisNaskahId == "")
		{}
		else
		{
			$statement_privacy .= " AND A.JENIS_NASKAH_ID = '".$reqJenisNaskahId."'";
		}
		
		$allRecord = $nomor_surat->getCountByParamsMonitoring(array(), $statement_privacy.$statement);
		// echo $allRecord;exit;
		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter =  $nomor_surat->getCountByParamsMonitoring(array(), $statement_privacy.$statement);
		
		$nomor_surat->selectByParamsMonitoring(array(), $dsplyRange, $dsplyStart, $statement_privacy.$statement, $sOrder);

		// echo $nomor_surat->query;exit;
		
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
		
		while($nomor_surat->nextRow())		
		{		
			$row = array();		
			for ( $i=0 ; $i<count($aColumns) ; $i++ )		
			{	
				if($aColumns[$i] == "KETERANGAN")
					$row[] = truncate($nomor_surat->getField($aColumns[$i]), 2);
				else
					$row[] = $nomor_surat->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		echo json_encode( $output );	

		
	}
	
	
	function add() 
	{
		$this->load->model("NomorSurat");
		$nomor_surat = new NomorSurat();

		$reqMode 					= $this->input->post("reqMode");
		$reqId 						= $this->input->post("reqId");
		
		$reqCabangId				= $this->input->post("reqCabangId");
		$reqPeruntukan				= $this->input->post("reqPeruntukan");
		$reqKeterangan				= $this->input->post("reqKeterangan");
		$reqTanggalSurat			= $this->input->post("reqTanggalSurat");
		$reqTipeNaskah				= $this->input->post("reqTipeNaskah");
		$reqSuratMasukId			= $this->input->post("reqSuratMasukId");
		$reqSuratKeluarId			= $this->input->post("reqSuratKeluarId");
		$reqJenisNaskahId		= $this->input->post("reqJenisNaskahId");
		$reqJenisNaskah			= $this->input->post("reqJenisNaskah");
		$reqKdLevel				= $this->input->post("reqKdLevel");
		$reqSatuanKerjaId		= $this->input->post("reqSatuanKerjaId");
		$reqSatuanKerja			= $this->input->post("reqSatuanKerja");
		
		
		
		//echo $reqMode;
		$nomor_surat->setField("SATUAN_KERJA_ID", $reqId);
		$nomor_surat->setField("CABANG_ID", $this->CABANG_ID);
		$nomor_surat->setField("SATUAN_KERJA_ID", $reqSatuanKerjaId);
		$nomor_surat->setField("SATUAN_KERJA", $reqSatuanKerja);
		$nomor_surat->setField("JENIS_NASKAH_ID", $reqJenisNaskahId);
		$nomor_surat->setField("JENIS_NASKAH", $reqJenisNaskah);
		$nomor_surat->setField("KD_LEVEL", $reqKdLevel);
		$nomor_surat->setField("PERUNTUKAN", $reqPeruntukan);
		$nomor_surat->setField("KETERANGAN", $reqKeterangan);
		$nomor_surat->setField("TANGGAL_SURAT", dateToDbCheck($reqTanggalSurat));
		$nomor_surat->setField("TIPE_NASKAH", $reqTipeNaskah);
		$nomor_surat->setField("SURAT_MASUK_ID", $reqSuratMasukId);
		$nomor_surat->setField("SURAT_KELUAR_ID", $reqSuratKeluarId);
		
		

		if($reqMode == "insert")
		{
			$nomor_surat->setField("LAST_CREATE_USER", $this->USERNAME);
			$nomor_surat->setField("LAST_CREATED_DATE", "CURRENT_DATE");
			$nomor_surat->insert();
		}
		else
		{
			$nomor_surat->setField("LAST_UPDATE_USER", $this->USERNAME);
			$nomor_surat->setField("LAST_UPDATED_DATE", "CURRENT_DATE");
			$nomor_surat->update();
		}	
		
		echo "Data berhasil disimpan.";
	}
	
	
	function approval() 
	{
		$this->load->model("NomorSurat");
		$nomor_surat = new NomorSurat();

		$reqMode 					= $this->input->post("reqMode");
		$reqId 						= $this->input->post("reqId");
		$reqNomorSurat 				= $this->input->post("reqNomorSurat");
		
		//echo $reqMode;
		$nomor_surat->setField("SATUAN_KERJA_ID", $reqId);
		$nomor_surat->setField("SURAT_NOMOR", $reqNomorSurat);
		$nomor_surat->setField("LAST_APPROVE_USER", $this->USERNAME);
		$nomor_surat->setField("LAST_APPROVE_DATE", "CURRENT_DATE");


		
		$statement_privacy .= " AND B.PENERBIT_NOMOR = '".$this->USER_GROUP."' ";
		if($this->USER_GROUP == "SEKRETARIS")
			$statement_privacy .= " AND (SATUAN_KERJA_ID = '".$this->SATUAN_KERJA_ID_ASAL."') ";
		else
			$statement_privacy .= " AND (CABANG_ID = '".$this->CABANG_ID."') ";
			
		$nomor_surat->approval($statement);
		
		echo "Data berhasil disimpan.";
	}
	
	
	function delete() 
	{
		$reqId	= $this->input->get('reqId');
		$this->load->model("NomorSurat");
		$nomor_surat = new NomorSurat();

		$nomor_surat->setField("SATUAN_KERJA_ID", $reqId);

		if($nomor_surat->delete())
			$arrJson["PESAN"] = "Data berhasil dihapus.";
		else
			$arrJson["PESAN"] = "Data gagal dihapus.";		
		
		echo json_encode($arrJson);
	}
	


	
	function combo_statement() 
	{
		$this->load->model("NomorSurat");
		$nomor_surat = new NomorSurat();

		$reqSatuanKerjaId = $this->input->get("reqSatuanKerjaId");
		$reqJenisNaskahId = $this->input->get("reqJenisNaskahId");
		
			
		$nomor_surat->selectByParams(array("A.SATUAN_KERJA_ID" => $reqSatuanKerjaId, "JENIS_NASKAH_ID" => $reqJenisNaskahId, "LAST_CREATE_USER" => $this->USERNAME), -1, -1, " AND SURAT_NOMOR IS NOT NULL AND SURAT_MASUK_ID IS NULL AND SURAT_KELUAR_ID IS NULL ");
		$i = 0;
		$arr_json = array();
		while($nomor_surat->nextRow())
		{
			$arr_json[$i]['id']		= $nomor_surat->getField("SATUAN_KERJA_ID");
			$arr_json[$i]['text']	= $nomor_surat->getField("SURAT_NOMOR")." | ".$nomor_surat->getField("PERUNTUKAN")."";
			$i++;
		}
		
		echo json_encode($arr_json);
	}
	
	function combo() 
	{
		
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : 50;
		$offset = ($page-1)*$rows;
		
		$reqPencarian = $this->input->get("reqPencarian");
		$reqMode = $this->input->get("reqMode");
		
		
		$this->load->model("NomorSurat");
		$nomor_surat = new NomorSurat();

		if($reqPencarian == "")
		{}
		else
			// $statement = " AND UPPER(A.NAMA) LIKE '%".strtoupper($reqPencarian)."%' ";
		
		$statement_privacy = "";
		//if($reqMode == "user_login")
		//	$statement_privacy .= " AND NOT EXISTS(SELECT 1 FROM USER_LOGIN X WHERE X.SATUAN_KERJA_ID = A.SATUAN_KERJA_ID) ";
		
		
		$rowCount = $nomor_surat->getCountByParams(array(), $statement.$statement_privacy);
		$nomor_surat->selectByParams(array(), $rows, $offset, $statement.$statement_privacy);
		$i = 0;
		$items = array();
		while($nomor_surat->nextRow())
		{
			$row['id']		= $nomor_surat->getField("SATUAN_KERJA_ID");
			$row['text']	= $nomor_surat->getField("NAMA");
			$row['SATUAN_KERJA_ID']	= $nomor_surat->getField("SATUAN_KERJA_ID");
			$row['NAMA']	= $nomor_surat->getField("NAMA");
			$row['CABANG']	= $nomor_surat->getField("SATUAN_KERJA");
			
			$row['state'] = 'close';
			$i++;
			array_push($items, $row);
		}
		$result["rows"] = $items;
		$result["total"] = $rowCount;
		echo json_encode($result);
	}
	
	
}

