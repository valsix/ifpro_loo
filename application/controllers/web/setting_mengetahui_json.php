<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class setting_mengetahui_json extends CI_Controller {

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
	
	function json() 
	{
		$this->load->model("SettingMengetahui");
		$set = new SettingMengetahui();

		$reqKategori = $this->input->get("reqKategori");
		// echo $reqKategori;exit;
		
		$aColumns		= array("SETTING_MENGETAHUI_ID","NAMA","STATUS_INFO");
		$aColumnsAlias	= array("SETTING_MENGETAHUI_ID","NAMA","STATUS_INFO");

		
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
			if ( trim($sOrder) == "ORDER BY SETTING_MENGETAHUI_ID asc" )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY SETTING_MENGETAHUI_ID asc";
				 
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

		$statement_privacy .= "   ";
		
		 $statement= " AND (UPPER(NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%')";
		$allRecord = $set->getCountByParams(array(), $statement_privacy.$statement);
		// echo $allRecord;exit;
		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter =  $set->getCountByParams(array(), $statement_privacy.$statement);
		
		 $set->selectByParams(array(), $dsplyRange, $dsplyStart, $statement_privacy.$statement, $sOrder);
		
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
		
		while($set->nextRow())		
		{		
			$row = array();		
			for ( $i=0 ; $i<count($aColumns) ; $i++ )		
			{	
				if($aColumns[$i]=="STATUS")
				{ 
					if(!empty($set->getField($aColumns[$i])))
   					{
						$row[] = number_format($set->getField($aColumns[$i],0,',','.'));
					}
					else
					{
						$row[] = $set->getField($aColumns[$i]);
					}
				}
				else
				{
					$row[] = $set->getField($aColumns[$i]);
				}
				
			}
			$output['aaData'][] = $row;
		}
		echo json_encode( $output );	

		
	}
	
	function add()
	{
		$this->load->model("SettingMengetahui");
		$set = new SettingMengetahui();


		$reqMode= $this->input->post("reqMode");
		$reqId= $this->input->post("reqId");

		$reqNama					= $this->input->post("reqNama");
		$reqStatus					= $this->input->post("reqStatus");
		$reqSatkerId					= $this->input->post("reqSatkerId");
		$reqUrut					= $this->input->post("reqUrut");		

		$set->setField("SETTING_MENGETAHUI_ID", $reqId);
		$set->setField("NAMA", $reqNama);
		$set->setField("STATUS", ValToNullDB($reqStatus));
		$set->setField("LAST_CREATE_USER", $this->USERNAME);
		$set->setField("LAST_UPDATE_USER", $this->USERNAME);
		
		if (!$reqStatus) 
		{
			$set->updateStatusNonAktif();
		}

		if($reqMode == "insert")
		{
			$set->insert();
			$reqId = $set->id;
		}
		else
		{
			$set->update();
		}

		

		// print_r($reqSatkerId);exit;

		if(!empty($reqSatkerId) && !empty($reqId))
		{

			$reqSimpan=0;
			$setdelete = new SettingMengetahui();
			$setdelete->setField("SETTING_MENGETAHUI_ID", $reqId);
			$setdelete->deletedetil();
			foreach ($reqSatkerId as $key => $value) 
			{
				$setinsert = new SettingMengetahui();
				$setinsert->setField("SETTING_MENGETAHUI_ID", $reqId);
				$setinsert->setField("URUT", $reqUrut[$key]);
				$setinsert->setField("SATUAN_KERJA_ID", $value);
				if($setinsert->insertdetil())
				{
					$reqSimpan=1;
				}
			}
		}
		
		echo "Data berhasil disimpan.";
			
	}

	function delete()
	{
		$this->load->model("SettingMengetahui");
		$this->load->model("SettingMengetahuiDetil");
		$set = new SettingMengetahui();
		$set_detil = new SettingMengetahuiDetil();

		$reqId = $this->input->get("reqId");
		$set->setField("SETTING_MENGETAHUI_ID", $reqId);
		$set_detil->setField("SETTING_MENGETAHUI_ID", $reqId);
		$set_detil->deletePerent();
		if($set->delete()){
			echo "Data berhasil dihapus.";
		}else{
			echo "Data gagal dihapus.";
		}
	}


	function deletedetil()
	{
		$this->load->model("SettingMengetahui");
		$set = new SettingMengetahui();
		$reqId = $this->input->get("reqId");
		$reqIdDetil = $this->input->get("reqIdDetil");
		$set->setField("SETTING_MENGETAHUI_ID", $reqId);
		$set->setField("SETTING_MENGETAHUI_DETIL_ID", $reqIdDetil);
		if($set->deletedetilbaris()){
			echo "Data berhasil dihapus.";
		}else{
			echo "Data gagal dihapus.";
		}
	}


	
}

