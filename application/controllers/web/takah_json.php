<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class takah_json extends CI_Controller {

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
		$this->load->model("Takah");
		$takah = new Takah();

		$reqSatuanKerjaId = $this->input->get("reqSatuanKerjaId");
		// echo $reqKategori;exit;

		$aColumns		= array("TAKAH_ID", "KODE", "TANGGAL", "NAMA", "KETERANGAN");
		$aColumnsAlias	= array("TAKAH_ID", "KODE", "TANGGAL", "NAMA", "KETERANGAN");

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
		
		$allRecord = $takah->getCountByParamsMonitoring(array(), $statement_privacy.$statement);
		// echo $allRecord;exit;
		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter =  $takah->getCountByParamsMonitoring(array(), $statement_privacy.$statement);
		
		$takah->selectByParamsMonitoring(array(), $dsplyRange, $dsplyStart, $statement_privacy.$statement, $sOrder);

		// echo $takah->query;exit;
		
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
		
		while($takah->nextRow())		
		{		
			$row = array();		
			for ( $i=0 ; $i<count($aColumns) ; $i++ )		
			{	
				if($aColumns[$i] == "KETERANGAN")
					$row[] = truncate($takah->getField($aColumns[$i]), 2);
				else
					$row[] = $takah->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		echo json_encode( $output );	

		
	}
	
	
	function add() 
	{
		$this->load->model("Takah");
		$this->load->model("TakahAkses");
		$this->load->model("SatuanKerja");
		$takah = new Takah();

		$reqMode 					= $this->input->post("reqMode");
		$reqId 						= $this->input->post("reqId");
		
		$reqKode					= $this->input->post("reqKode");
		$reqNama					= $this->input->post("reqNama");
		$reqKeterangan				= $this->input->post("reqKeterangan");
		$reqTanggal					= $this->input->post("reqTanggal");
		$reqSatuanKerjaIdTujuan		= $this->input->post("reqSatuanKerjaIdTujuan");
		
		/*if(count($reqSatuanKerjaIdTujuan) == 0)
		{
			echo "0-Tujuan tata naskah belum ditentukan.";	
			return;
		}*/

		$takah->setField("CABANG_ID", $this->CABANG_ID);
		$takah->setField("SATUAN_KERJA_ID", $this->SATUAN_KERJA_ID_ASAL);
		$takah->setField("KODE", $reqKode);
		$takah->setField("NAMA", $reqNama);
		$takah->setField("KETERANGAN", $reqKeterangan);
		$takah->setField("TANGGAL", dateToDbCheck($reqTanggal));
		
		if($reqMode == "insert")
		{
			$takah->setField("LAST_CREATE_USER", $this->USERNAME);
			$takah->setField("LAST_CREATED_DATE", "CURRENT_DATE");
			$takah->insert();
			/*$reqId = $takah->id;

			$takah_akses = new TakahAkses();
			$takah_akses->setField("TAKAH_ID", $reqId);
			$takah_akses->deleteTakah();
			
			for($i=0;$i<count($reqSatuanKerjaIdTujuan);$i++)
			{
				if($reqSatuanKerjaIdTujuan[$i] == "")
				{}
				else
				{
					$satuan_kerja = new SatuanKerja();
					$satuan_kerja->selectByParams(array("SATUAN_KERJA_ID" => $reqSatuanKerjaIdTujuan[$i]));
					$satuan_kerja->firstRow();
					$reqSatuanKerja = $satuan_kerja->getField("NAMA");

					$takah_akses = new TakahAkses();
					$takah_akses->setField("TAKAH_ID", $reqId);
					$takah_akses->setField("SATUAN_KERJA_ID_TUJUAN", $reqSatuanKerjaIdTujuan[$i]);
					$takah_akses->setField("SATUAN_KERJA", $reqSatuanKerja);
					$takah_akses->setField("LAST_CREATE_USER", $this->ID);
					$takah_akses->insert();
				}
			}*/	
		}
		else
		{
			$takah->setField("TAKAH_ID", $reqId);
			$takah->setField("LAST_UPDATE_USER", $this->USERNAME);
			$takah->setField("LAST_UPDATED_DATE", "CURRENT_DATE");
			$takah->update();

			/*$takah_akses = new TakahAkses();
			$takah_akses->setField("TAKAH_ID", $reqId);
			$takah_akses->deleteTakah();

			for($i=0;$i<count($reqSatuanKerjaIdTujuan);$i++)
			{
				if($reqSatuanKerjaIdTujuan[$i] == "")
				{}
				else
				{
					$satuan_kerja = new SatuanKerja();
					$satuan_kerja->selectByParams(array("SATUAN_KERJA_ID" => $reqSatuanKerjaIdTujuan[$i]));
					$satuan_kerja->firstRow();
					$reqSatuanKerja = $satuan_kerja->getField("NAMA");

					$takah_akses = new TakahAkses();
					$takah_akses->setField("TAKAH_ID", $reqId);
					$takah_akses->setField("SATUAN_KERJA_ID_TUJUAN", $reqSatuanKerjaIdTujuan[$i]);
					$takah_akses->setField("SATUAN_KERJA", $reqSatuanKerja);
					$takah_akses->setField("LAST_CREATE_USER", $this->ID);
					$takah_akses->insert();
				}
			}*/
		}

		echo "Data berhasil disimpan.";
	}
	
	
	function approval() 
	{
		$this->load->model("Takah");
		$takah = new Takah();

		$reqMode 			= $this->input->post("reqMode");
		$reqId 				= $this->input->post("reqId");
		$reqTakah 			= $this->input->post("reqTakah");
		
		//echo $reqMode;
		$takah->setField("SATUAN_KERJA_ID", $reqId);
		$takah->setField("SURAT_NOMOR", $reqTakah);
		$takah->setField("LAST_APPROVE_USER", $this->USERNAME);
		$takah->setField("LAST_APPROVE_DATE", "CURRENT_DATE");


		
		$statement_privacy .= " AND B.PENERBIT_NOMOR = '".$this->USER_GROUP."' ";
		if($this->USER_GROUP == "SEKRETARIS")
			$statement_privacy .= " AND (SATUAN_KERJA_ID = '".$this->SATUAN_KERJA_ID_ASAL."') ";
		else
			$statement_privacy .= " AND (CABANG_ID = '".$this->CABANG_ID."') ";
			
		$takah->approval($statement);
		
		echo "Data berhasil disimpan.";
	}
	
	
	function delete() 
	{
		$reqId	= $this->input->get('reqId');
		$this->load->model("Takah");
		$takah = new Takah();

		$takah->setField("TAKAH_ID", $reqId);

		if($takah->delete())
			$arrJson["PESAN"] = "Data berhasil dihapus.";
		else
			$arrJson["PESAN"] = "Data gagal dihapus.";		
		
		echo json_encode($arrJson);
	}
	
	
	function combo_statement() 
	{
		$this->load->model("Takah");
		$takah = new Takah();

		$reqSatuanKerjaId = $this->input->get("reqSatuanKerjaId");
		$reqJenisNaskahId = $this->input->get("reqJenisNaskahId");
		
			
		$takah->selectByParams(array("A.SATUAN_KERJA_ID" => $reqSatuanKerjaId, "JENIS_NASKAH_ID" => $reqJenisNaskahId, "LAST_CREATE_USER" => $this->USERNAME), -1, -1, " AND SURAT_NOMOR IS NOT NULL AND SURAT_MASUK_ID IS NULL AND SURAT_KELUAR_ID IS NULL ");
		$i = 0;
		$arr_json = array();
		while($takah->nextRow())
		{
			$arr_json[$i]['id']		= $takah->getField("SATUAN_KERJA_ID");
			$arr_json[$i]['text']	= $takah->getField("SURAT_NOMOR")." | ".$takah->getField("PERUNTUKAN")."";
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
		
		
		$this->load->model("Takah");
		$takah = new Takah();

		if($reqPencarian == "")
		{}
		else
			// $statement = " AND UPPER(A.NAMA) LIKE '%".strtoupper($reqPencarian)."%' ";
		
		$statement_privacy = "";
		//if($reqMode == "user_login")
		//	$statement_privacy .= " AND NOT EXISTS(SELECT 1 FROM USER_LOGIN X WHERE X.SATUAN_KERJA_ID = A.SATUAN_KERJA_ID) ";
		
		
		$rowCount = $takah->getCountByParams(array(), $statement.$statement_privacy);
		$takah->selectByParams(array(), $rows, $offset, $statement.$statement_privacy);
		$i = 0;
		$items = array();
		while($takah->nextRow())
		{
			$row['id']		= $takah->getField("SATUAN_KERJA_ID");
			$row['text']	= $takah->getField("NAMA");
			$row['SATUAN_KERJA_ID']	= $takah->getField("SATUAN_KERJA_ID");
			$row['NAMA']	= $takah->getField("NAMA");
			$row['CABANG']	= $takah->getField("SATUAN_KERJA");
			
			$row['state'] = 'close';
			$i++;
			array_push($items, $row);
		}
		$result["rows"] = $items;
		$result["total"] = $rowCount;
		echo json_encode($result);
	}
	
	
}

