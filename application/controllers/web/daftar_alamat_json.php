<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class daftar_alamat_json extends CI_Controller {

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
		$this->CABANG_ID		= $this->kauth->getInstance()->getIdentity()->CABANG_ID;  
		$this->CABANG		= $this->kauth->getInstance()->getIdentity()->CABANG;  
		
		
	}
	

	function json(){
		
		$this->load->model("DaftarAlamat");
		$daftar_alamat = new DaftarAlamat();

		$reqKategori = $this->input->get("reqKategori");
		// echo $reqKategori;exit;
		
		$aColumns		= array("DAFTAR_ALAMAT_ID","INSTANSI", "ALAMAT", "KOTA", "NO_TELP", "EMAIL", "STATUS", 
			"KODE_POS", "FAX", "NAMA_KEPALA", "HP");
		$aColumnsAlias	= array("DAFTAR_ALAMAT_ID",  "INSTANSI", "ALAMAT", "KOTA", "NO_TELP", "EMAIL", "STATUS", 
			"KODE_POS", "FAX", "NAMA_KEPALA", "HP");
		
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
			if ( trim($sOrder) == "ORDER BY DAFTAR_ALAMAT_ID asc" )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY DAFTAR_ALAMAT_ID asc";
				 
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

	//	$statement_privacy .= "  AND SATUAN_KERJA_ID_PARENT = 'SATKER' ";
		
		// $statement= " AND (UPPER(NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%')";
		$allRecord = $daftar_alamat->getCountByParams(array(), $statement_privacy.$statement);
		// echo $allRecord;exit;
		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter =  $daftar_alamat->getCountByParams(array(), $statement_privacy.$statement);
		
		 $daftar_alamat->selectByParams(array(), $dsplyRange, $dsplyStart, $statement_privacy.$statement, $sOrder);
		
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
		
		while($daftar_alamat->nextRow())		
		{		
			$row = array();		
			for ( $i=0 ; $i<count($aColumns) ; $i++ )		
			{	
				if($aColumns[$i] == "KETERANGAN")
					$row[] = truncate($daftar_alamat->getField($aColumns[$i]), 2);
				else
					$row[] = $daftar_alamat->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		echo json_encode( $output );	

		
	}

	function checkadd()
	{
		$this->load->model("DaftarAlamat");
		$reqInstansi= $this->input->post("reqInstansi");

		$set= new DaftarAlamat();
		$set->selectByParams(array(), -1,-1, " AND UPPER(INSTANSI) = UPPER('".$reqInstansi."')");
		$set->firstRow();
		// echo $set->query;exit;
		$infoid= $set->getField("DAFTAR_ALAMAT_ID");
		if(empty($infoid))
		{
			$setdetil= new DaftarAlamat();
			$setdetil->setField("INSTANSI", $reqInstansi);
			$setdetil->setField("LAST_CREATE_USER", $this->USERNAME);
			$setdetil->insert();
			$infoid= $setdetil->id;
		}

		echo json_encode((int)$infoid);
	}
	
	function add()
	{
		$this->load->model("DaftarAlamat");
		$daftar_alamat = new DaftarAlamat();

		$reqMode= $this->input->post("reqMode");
		$reqId= $this->input->post("reqId");

		$reqDaftarAlamatId 				= $this->input->post("reqDaftarAlamatId");
		$reqCabangId					= $this->input->post("reqCabangId");
		$reqInstansi 					= $this->input->post("reqInstansi");
		$reqAlamat 						= $this->input->post("reqAlamat");
		$reqKota 						= $this->input->post("reqKota");
		$reqNoTelp 						= $this->input->post("reqNoTelp");
		$reqEmail 						= $this->input->post("reqEmail");
		$reqStatus 						= $this->input->post("reqStatus");
		$reqKodePos						= $this->input->post("reqKodePos");
		$reqFax 						= $this->input->post("reqFax");
		$reqNamaKepala 					= $this->input->post("reqNamaKepala");
		$reqJabatanKepala 				= $this->input->post("reqJabatanKepala");
		$reqHp							= $this->input->post("reqHp");
		$reqDaftarAlamatGroupId			= $this->input->post("reqDaftarAlamatGroupId");
		$reqCabangId 					= $this->input->post("reqCabangId");



		$daftar_alamat->setField("DAFTAR_ALAMAT_ID", $reqId);
		$daftar_alamat->setField("CABANG_ID", $this->SATUAN_KERJA_ID);
		$daftar_alamat->setField("INSTANSI", $reqInstansi);
		$daftar_alamat->setField("ALAMAT", $reqAlamat);
		$daftar_alamat->setField("KOTA", $reqKota);
		$daftar_alamat->setField("NO_TELP", $reqNoTelp);
		$daftar_alamat->setField("EMAIL", $reqEmail);
		$daftar_alamat->setField("STATUS", $reqStatus);
		$daftar_alamat->setField("KODE_POS", $reqKodePos);
		$daftar_alamat->setField("FAX", $reqFax);
		$daftar_alamat->setField("NAMA_KEPALA", $reqNamaKepala);
		$daftar_alamat->setField("JABATAN_KEPALA", $reqJabatanKepala);
		$daftar_alamat->setField("HP", $reqHp);
		$daftar_alamat->setField("DAFTAR_ALAMAT_GROUP_ID", $reqDaftarAlamatGroupId);
		$daftar_alamat->setField("CABANG_ID", $reqCabangId);
		
	

		if($reqMode == "insert")
		{
			$daftar_alamat->setField("LAST_CREATE_USER", $this->USERNAME);
			$daftar_alamat->insert();
		}
		else
		{
			$daftar_alamat->setField("LAST_UPDATE_USER", $this->USERNAME);
			$daftar_alamat->update();
		}	
		
		echo "Data berhasil disimpan.";
			
	}
	
	function delete()
	{
		$this->load->model("DaftarAlamat");
		$daftar_alamat = new DaftarAlamat();

		$reqId= $this->input->get("reqId");
		$daftar_alamat->setField("DAFTAR_ALAMAT_ID", $reqId);
		if($daftar_alamat->delete())
			$arrJson["PESAN"] = "Data berhasil dihapus.";
		else
			$arrJson["PESAN"] = "Data gagal dihapus.";		
		
		echo json_encode($arrJson);
	}

	function combo() 
	{
		$this->load->model("DaftarAlamat");
		$daftar_alamat = new DaftarAlamat();

		$daftar_alamat->selectByParams(array("NOT DAFTAR_ALAMAT_ID" => "0"));
		$i = 0;
		while($daftar_alamat->nextRow())
		{
			$arr_json[$i]['id']		= $daftar_alamat->getField("DAFTAR_ALAMAT_ID");
			$arr_json[$i]['text']	= $daftar_alamat->getField("INSTANSI");
			$i++;
		}
		
		echo json_encode($arr_json);
	}
	
	
	
	function treetable() 
	{
		
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : 50;
		$id   = isset($_POST['id']) ? intval($_POST['id']) : 0;
		$offset = ($page-1)*$rows;
		
		$reqPencarian = trim($this->input->get("reqPencarian"));
		$reqMode = $this->input->get("reqMode");
		
		
		$this->load->model("DaftarAlamat");
		$daftar_alamat = new DaftarAlamat();

		$statement = " AND (UPPER(INSTANSI) LIKE '%".strtoupper($reqPencarian)."%' OR UPPER(NAMA_KEPALA) LIKE '%".strtoupper($reqPencarian)."%') ";
			
		$rowCount = $daftar_alamat->getCountByParams($arrStatement, $statement.$statement_privacy);
		$daftar_alamat->selectByParams($arrStatement, $rows, $offset, $statement.$statement_privacy, " ORDER BY INSTANSI ASC ");
		$i = 0;
		$items = array();
		while($daftar_alamat->nextRow())
		{
			$row['id']				= $daftar_alamat->getField("DAFTAR_ALAMAT_ID");
			$row['text']			= $daftar_alamat->getField("INSTANSI");
			$row['INSTANSI']		= $daftar_alamat->getField("INSTANSI");
			$row['ALAMAT']			= $daftar_alamat->getField("ALAMAT");
			$row['EMAIL']			= $daftar_alamat->getField("EMAIL");
			$row['NAMA_KEPALA']		= $daftar_alamat->getField("NAMA_KEPALA");
			$row['JABATAN_KEPALA']	= $daftar_alamat->getField("JABATAN_KEPALA");
			$i++;
			array_push($items, $row);
			unset($row);
		}
		$result["rows"] = $items;
		$result["total"] = $rowCount;
		
		echo json_encode($result);
	}
	
	
	
}

