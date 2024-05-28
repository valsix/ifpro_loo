<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class agenda_json extends CI_Controller {

	function __construct() {
		parent::__construct();
		
		if (!$this->kauth->getInstance()->hasIdentity())
		{
			//redirect('login');
		}    
		
		$this->db->query("SET DATESTYLE TO PostgreSQL,European;"); 
		$this->ID				= $this->kauth->getInstance()->getIdentity()->ID;   
		$this->JAWABAN			= $this->kauth->getInstance()->getIdentity()->JAWABAN;   
		$this->JABATAN			= $this->kauth->getInstance()->getIdentity()->JABATAN;   
		$this->HAK_AKSES		= $this->kauth->getInstance()->getIdentity()->HAK_AKSES;   
		$this->LAST_LOGIN		= $this->kauth->getInstance()->getIdentity()->LAST_LOGIN;   
		$this->USERNAME			= $this->kauth->getInstance()->getIdentity()->USERNAME;  
		$this->USER_LOGIN_ID	= $this->kauth->getInstance()->getIdentity()->USER_LOGIN_ID;  
		$this->USER_GROUP		= $this->kauth->getInstance()->getIdentity()->USER_GROUP;  
		$this->CABANG_ID		= $this->kauth->getInstance()->getIdentity()->CABANG_ID;  
		$this->CABANG			= $this->kauth->getInstance()->getIdentity()->CABANG;  

		$this->calendarId = $this->config->item('calendar_id');
	}
	
	function json() 
	{
		$this->load->model("Agenda");
		$agenda = new Agenda();

		
		$aColumns		= array("AGENDA_ID", "EVENT_ID", "NAMA", "KETERANGAN", "TANGGAL");
		$aColumnsAlias	= array("AGENDA_ID", "EVENT_ID", "NAMA", "KETERANGAN", "TANGGAL");

		
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
			if ( trim($sOrder) == "ORDER BY AGENDA_ID asc" )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.TANGGAL asc";
				 
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

		$statement .= " AND A.PEGAWAI_ID = '".$this->ID."' ";
		
		$allRecord = $agenda->getCountByParams(array(), $statement_privacy.$statement);
		// echo $allRecord;exit;
		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter =  $agenda->getCountByParams(array(), $statement_privacy.$statement);
		
		 $agenda->selectByParams(array(), $dsplyRange, $dsplyStart, $statement_privacy.$statement, $sOrder);
		
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
		
		while($agenda->nextRow())		
		{		
			$row = array();		
			for ( $i=0 ; $i<count($aColumns) ; $i++ )		
			{	
				if($aColumns[$i] == "TANGGAL")
					$row[] = getFormattedDate($agenda->getField($aColumns[$i]));
				else
					$row[] = $agenda->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		echo json_encode( $output );	

		
	}
	
	function add() 
	{
		// echo "string xas";exit;
		$this->load->model("Agenda");
		$agenda = new Agenda();

		$this->load->library('GoogleClient');

		$google = new GoogleClient();
		$client = $google->getClient();
		$service = new Google_Service_Calendar($client);

		$reqMode 					= $this->input->post("reqMode");
		$reqId 						= $this->input->post("reqId");
		
		$reqEventId 				= $this->input->post("reqEventId");
		$reqAgendaId				= $this->input->post("reqAgendaId");
		$reqNama					= $this->input->post("reqNama");
		$reqKeterangan				= $this->input->post("reqKeterangan");
		$reqTanggal					= $this->input->post("reqTanggal");
		$reqPegawaiId				= $this->input->post("reqPegawaiId");

				
		$agenda->setField("AGENDA_ID", $reqId);
		$agenda->setField("NAMA", $reqNama);
		$agenda->setField("KETERANGAN", $reqKeterangan);
		$agenda->setField("TANGGAL", dateToDBCheck($reqTanggal));
		$agenda->setField("PEGAWAI_ID", $this->ID);

		$arrEvent = array(
		  'summary' => $reqNama,
		  'description' => $reqKeterangan,
		  'start' => array(
			'dateTime' => dateToDB($reqTanggal).'T00:00:00+07:00'
		  ),
		  'end' => array(
			'dateTime' => dateToDB($reqTanggal).'T23:59:00+07:00'
		  ),
		  'reminders' => array(
			'useDefault' => FALSE,
			'overrides' => array(
			  array('method' => 'email', 'minutes' => 24 * 60),
			  array('method' => 'popup', 'minutes' => 10),
			),
		  ),
		);

		$event = new Google_Service_Calendar_Event($arrEvent);
		
		if($reqMode == "insert")
		{
			$event = $service->events->insert($this->calendarId, $event);
			$reqEventId = $event->id;

			$agenda->setField("EVENT_ID", $reqEventId);
			$agenda->setField("LAST_CREATE_USER", $this->USERNAME);
			$agenda->insert();
		}
		else
		{
			$event = $service->events->update($this->calendarId, $reqEventId, $event);

			$agenda->setField("LAST_UPDATE_USER", $this->USERNAME);
			$agenda->update();
		}
		
		echo "Data berhasil disimpan.";
	
	}
	
	function delete() 
	{
		$reqId		= $this->input->get('reqId');
		$reqEventId	= $this->input->get('reqEventId');

		$this->load->library('GoogleClient');

		$google = new GoogleClient();
		$client = $google->getClient();
		$service = new Google_Service_Calendar($client);
		$event = $service->events->delete($this->calendarId, $reqEventId);

		$this->load->model("Agenda");
		$agenda = new Agenda();

		$agenda->setField("AGENDA_ID", $reqId);

		if($agenda->delete())
			$arrJson["PESAN"] = "Data berhasil dihapus.";
		else
			$arrJson["PESAN"] = "Data gagal dihapus.";	

		echo json_encode($arrJson);
	}	
	
}

