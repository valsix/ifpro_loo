<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class lokasi_arsip_json extends CI_Controller {

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
		$this->CABANG			= $this->kauth->getInstance()->getIdentity()->CABANG; 
		$this->SATUAN_KERJA_ID_ASAL	= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_ID_ASAL;  
		$this->SATUAN_KERJA_ASAL	= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_ASAL;   
		
		
	}
	
	function json() 
	{
		$this->load->model("LokasiArsip");
		$lokasi_arsip = new LokasiArsip();

		$reqId = $this->input->get("reqId");
		// echo $reqId;exit;
		
		$aColumns		= array("LOKASI_ARSIP_ID","NAMA","KODE","KETERANGAN");
		$aColumnsAlias	= array("LOKASI_ARSIP_ID","NAMA","KODE","KETERANGAN");

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
			if ( trim($sOrder) == "ORDER BY LOKASI_ARSIP_ID asc" )
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

		
		$statement= " AND (UPPER(A.NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%') OR (UPPER(A.KODE) LIKE '%".strtoupper($_GET['sSearch'])."%')";
		$allRecord = $lokasi_arsip->getCountByParams(array(), $statement_privacy.$statement);
		// echo $allRecord;exit;
		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter =  $lokasi_arsip->getCountByParams(array(), $statement_privacy.$statement);
		
		 $lokasi_arsip->selectByParams(array(), $dsplyRange, $dsplyStart, $statement_privacy.$statement, $sOrder);
		
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
		
		while($lokasi_arsip->nextRow())		
		{		
			$row = array();		
			for ( $i=0 ; $i<count($aColumns) ; $i++ )		
			{	
				// if($aColumns[$i] == "KETERANGAN")
				// 	$row[] = truncate($lokasi_arsip->getField($aColumns[$i]), 5);
				// else
					$row[] = $lokasi_arsip->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		echo json_encode( $output );
	}
	
	function add() 
	{
		$this->load->model("LokasiArsip");
		$lokasi_arsip = new LokasiArsip();

		$reqMode 			= $this->input->post("reqMode");
		$reqId 				= $this->input->post("reqId");
		
		$reqNama			= $this->input->post("reqNama");
		$reqParent			= $this->input->post("reqParent");
		$reqKode			= $this->input->post("reqKode");
		$reqKeterangan		= $this->input->post("reqKeterangan");
		$reqSatuanKerjaId	= $this->input->post("reqSatuanKerjaId");
		$reqCabangId		= $this->input->post("reqCabangId");
		
		$lokasi_arsip->setField("LOKASI_ARSIP_ID", $reqId);
		$lokasi_arsip->setField("LOKASI_ARSIP_PARENT_ID", $reqParent);
		$lokasi_arsip->setField("NAMA", $reqNama);
		$lokasi_arsip->setField("KODE", $reqKode);
		$lokasi_arsip->setField("KETERANGAN", $reqKeterangan);
		$lokasi_arsip->setField("CABANG_ID", $this->CABANG_ID);
		
		if($this->USER_GROUP == "TATAUSAHA")
			$lokasi_arsip->setField("SATUAN_KERJA_ID", $this->CABANG_ID);
		else
			$lokasi_arsip->setField("SATUAN_KERJA_ID", $this->SATUAN_KERJA_ID_ASAL);
		
		if($reqMode == "insert")
		{
			$lokasi_arsip->setField("LAST_CREATE_USER", $this->USERNAME);
			$lokasi_arsip->insert();
		}
		else
		{
			$lokasi_arsip->setField("LAST_UPDATE_USER", $this->USERNAME);
			$lokasi_arsip->update();
		}	
		
		echo "Data berhasil disimpan.";
	}
	
	function delete() 
	{
		$reqId	= $this->input->get('reqId');
		$this->load->model("LokasiArsip");
		$lokasi_arsip = new LokasiArsip();

		$lokasi_arsip->setField("LOKASI_ARSIP_ID", $reqId);

		if($lokasi_arsip->delete())
			$arrJson["PESAN"] = "Data berhasil dihapus.";
		else
			$arrJson["PESAN"] = "Data gagal dihapus.";		
		
		echo json_encode($arrJson);
	}	
	
	function combo() 
	{
		$this->load->model("LokasiArsip");
		$lokasi_arsip = new LokasiArsip();

		if($this->USER_GROUP == "TATAUSAHA")
			$statement_privacy = " AND A.SATUAN_KERJA_ID = '".$this->CABANG_ID."' ";
		else
			$statement_privacy = " AND A.SATUAN_KERJA_ID = '".$this->SATUAN_KERJA_ID_ASAL."' ";
			
		$lokasi_arsip->selectByParams(array("NOT LOKASI_ARSIP_ID" => "0"), -1, -1, $statement_privacy);
		$i = 0;
		while($lokasi_arsip->nextRow())
		{
			$arr_json[$i]['id']		= $lokasi_arsip->getField("LOKASI_ARSIP_ID");
			$arr_json[$i]['text']	= $lokasi_arsip->getField("KODE")." - ".$lokasi_arsip->getField("NAMA");
			$arr_json[$i]['text']	= $lokasi_arsip->getField("NAMA");
			
			
			$i++;
		}
		
		echo json_encode($arr_json);
	}


	function combotree() 
	{
		$this->load->model("LokasiArsip");
		$lokasi_arsip = new LokasiArsip();

		if($this->USER_GROUP == "TATAUSAHA"){
			$statement_privacy = " AND A.SATUAN_KERJA_ID = '".$this->SATUAN_KERJA_ID_ASAL."' ";
		}
		else{
			$statement_privacy = " AND A.SATUAN_KERJA_ID = '".$this->SATUAN_KERJA_ID_ASAL."' ";
		}
			
		$lokasi_arsip->selectByParams(array("LOKASI_ARSIP_PARENT_ID" => "0"), -1, -1, $statement_privacy);
		// echo $lokasi_arsip->query;exit;
		$i = 0;
		while($lokasi_arsip->nextRow())
		{
			$arr_json[$i]['id']		= $lokasi_arsip->getField("LOKASI_ARSIP_ID");
			$arr_json[$i]['text']	= $lokasi_arsip->getField("KODE")." - ".$lokasi_arsip->getField("NAMA");
			$arr_json[$i]['children']	= $this->combotree_children($arr_json[$i]['id']);
			
			
			$i++;
		}
		
		echo json_encode($arr_json);
	}

	function combotree_children($id) 
	{
		$this->load->model("LokasiArsip");
		$lokasi_arsip = new LokasiArsip();
		
		
		if($this->USER_GROUP == "TATAUSAHA"){
			$statement_privacy = " AND A.SATUAN_KERJA_ID = '".$this->CABANG_ID."' ";
		}
		else{
			$statement_privacy = " AND A.SATUAN_KERJA_ID = '".$this->SATUAN_KERJA_ID_ASAL."' ";
		}
			

		$lokasi_arsip->selectByParams(array("LOKASI_ARSIP_PARENT_ID" => $id), -1, -1, $statement_privacy);
		$i = 0;
		$arr_json = array();
		while($lokasi_arsip->nextRow())
		{
			$arr_json[$i]['id']		= $lokasi_arsip->getField("LOKASI_ARSIP_ID");
			$arr_json[$i]['text']	= $lokasi_arsip->getField("KODE")." - ".$lokasi_arsip->getField("NAMA");
			$arr_json[$i]['children']	= $this->combotree_children($arr_json[$i]['id']);
			
			
			$i++;
		}
		
		return $arr_json;
	}



	function treetable() 
	{
		
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : 50;
		$id   = isset($_POST['id']) ? intval($_POST['id']) : 0;
		$offset = ($page-1)*$rows;
		
		$reqPencarian = $this->input->get("reqPencarian");
		$reqMode = $this->input->get("reqMode");
		
		
		$this->load->model("LokasiArsip");
		$lokasi_arsip = new LokasiArsip();

		if($reqPencarian == "")
		{}
		else
			$statement = " AND UPPER(A.NAMA) LIKE '%".strtoupper($reqPencarian)."%' ";
		
		$statement_privacy = "";
		
		
		if($this->USER_GROUP == "TATAUSAHA")
			$statement_privacy = " AND A.SATUAN_KERJA_ID = '".$this->CABANG_ID."' ";
		else
			$statement_privacy = " AND A.SATUAN_KERJA_ID = '".$this->SATUAN_KERJA_ID_ASAL."' ";
			
		$arrStatement = array("COALESCE(NULLIF(LOKASI_ARSIP_PARENT_ID, ''), '0')" => $id);
			
		$rowCount = $lokasi_arsip->getCountByParamsMonitoring($arrStatement, $statement.$statement_privacy);
		$lokasi_arsip->selectByParamsMonitoring($arrStatement, $rows, $offset, $statement.$statement_privacy, " ORDER BY LOKASI_ARSIP_ID ASC ");
		// echo $lokasi_arsip->query;exit;
		$i = 0;
		$items = array();
		while($lokasi_arsip->nextRow())
		{
			$row['id']					= coalesce($lokasi_arsip->getField("LOKASI_ARSIP_ID"), $lokasi_arsip->getField("LOKASI_ARSIP_ID"));
			$row['parentId']			= $lokasi_arsip->getField("LOKASI_ARSIP_PARENT_ID");
			$row['text']				= $lokasi_arsip->getField("NAMA");
			$row['LOKASI_ARSIP_ID']		= $lokasi_arsip->getField("LOKASI_ARSIP_ID");
			$row['NAMA_LOKASI_ARSIP']	= $lokasi_arsip->getField("KODE")." - ".$lokasi_arsip->getField("NAMA_LOKASI_ARSIP");
			$row['SATUAN_KERJA']		= $lokasi_arsip->getField("SATUAN_KERJA");
			$row['KODE']				= $lokasi_arsip->getField("KODE");
			$row['state'] 				= $this->has_child($row['id']);
			$row['children'] 			= $this->children($row['id']);
			$i++;
			array_push($items, $row);
		}
		$result["rows"] = $items;
		$result["total"] = $rowCount;
		
		echo json_encode($result);
	}
	
	function children($id)
	{
		$this->load->model("LokasiArsip");
		$lokasi_arsip = new LokasiArsip();
		$arrStatement = array("COALESCE(NULLIF(LOKASI_ARSIP_PARENT_ID, ''), '0')" => $id);


		if($this->USER_GROUP == "TATAUSAHA")
			$statement_privacy = " AND A.SATUAN_KERJA_ID = '".$this->CABANG_ID."' ";
		else
			$statement_privacy = " AND A.SATUAN_KERJA_ID = '".$this->SATUAN_KERJA_ID_ASAL."' ";
						
		$rowCount = $lokasi_arsip->getCountByParamsMonitoring($arrStatement, $statement.$statement_privacy);
		$lokasi_arsip->selectByParamsMonitoring($arrStatement, $rows, $offset, $statement.$statement_privacy, " ORDER BY LOKASI_ARSIP_ID ASC ");
		// echo $lokasi_arsip->query;exit;
		$i = 0;
		$items = array();
		while($lokasi_arsip->nextRow())
		{
			$row['id']					= coalesce($lokasi_arsip->getField("LOKASI_ARSIP_ID"), $lokasi_arsip->getField("LOKASI_ARSIP_ID"));
			$row['parentId']			= $lokasi_arsip->getField("LOKASI_ARSIP_PARENT_ID");
			$row['text']				= $lokasi_arsip->getField("NAMA");
			$row['LOKASI_ARSIP_ID']		= $lokasi_arsip->getField("LOKASI_ARSIP_ID");
			$row['NAMA_LOKASI_ARSIP']	= $lokasi_arsip->getField("NAMA_LOKASI_ARSIP");
			$row['SATUAN_KERJA']		= $lokasi_arsip->getField("SATUAN_KERJA");
			$row['KODE']				= $lokasi_arsip->getField("KODE");
			$row['state'] 				= $this->has_child($row['id']);
			$row['children'] 			= $this->children($row['id']);
			$i++;
			array_push($items, $row);
		}
		
		return $items;
	}
	
	function has_child($id)
	{
		$this->load->model("LokasiArsip");
		$lokasi_arsip = new LokasiArsip();
		$adaData = $lokasi_arsip->getCountByParamsMonitoring(array("COALESCE(NULLIF(LOKASI_ARSIP_PARENT_ID, ''), '0')" => $id));
		return $adaData > 0 ? true : false;
	}
	
}

