<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class meeting_room_json extends CI_Controller {

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
		$this->load->model("MeetingRoom");
		$meeting_room = new MeetingRoom();

		$reqKategori = $this->input->get("reqKategori");
		// echo $reqKategori;exit;
		
		$aColumns		= array("MEETING_ROOM_ID","NAMA","TEMPAT","KAPASITAS", "KETERANGAN");
		$aColumnsAlias	= array("MEETING_ROOM_ID","NAMA","TEMPAT","KAPASITAS", "KETERANGAN");

		
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
			if ( trim($sOrder) == "ORDER BY MEETING_ROOM_ID asc" )
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

		$statement_privacy .= " AND A.LOKASI_ID = '".$this->CABANG_ID."' ";
		
		 $statement= " AND (UPPER(A.NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%')";
		$allRecord = $meeting_room->getCountByParams(array(), $statement_privacy.$statement);
		// echo $allRecord;exit;
		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter =  $meeting_room->getCountByParams(array(), $statement_privacy.$statement);
		
		 $meeting_room->selectByParams(array(), $dsplyRange, $dsplyStart, $statement_privacy.$statement, $sOrder);
		
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
		
		while($meeting_room->nextRow())		
		{		
			$row = array();		
			for ( $i=0 ; $i<count($aColumns) ; $i++ )		
			{	
				if($aColumns[$i] == "KETERANGAN")
					$row[] = truncate($meeting_room->getField($aColumns[$i]), 2);
				else
					$row[] = $meeting_room->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		echo json_encode( $output );	

		
	}
	
	function add() 
	{
		$this->load->model("MeetingRoom");
		$this->load->model("MeetingRoomApproval");
		$meeting_room = new MeetingRoom();
		$meeting_room_approval = new MeetingRoomApproval();

		$reqMode 					= $this->input->post("reqMode");
		$reqId 						= $this->input->post("reqId");
		
		$reqNama					= $this->input->post("reqNama");
		$reqTempat 				 	= $this->input->post("reqTempat");
		$reqKeterangan 				= $_POST["reqKeterangan"];
		$reqKapasitas 				= $this->input->post("reqKapasitas");
		
		$reqPegawaiId = $this->input->post("reqPegawaiId");
		$reqPegawai = $this->input->post("reqPegawai");
		
		$meeting_room->setField("MEETING_ROOM_ID", $reqId);
		$meeting_room->setField("NAMA", $reqNama);
		$meeting_room->setField("TEMPAT", $reqTempat);
		$meeting_room->setField("LOKASI_ID", $this->CABANG_ID);
		$meeting_room->setField("KAPASITAS", $reqKapasitas);
		$meeting_room->setField("KETERANGAN", $reqKeterangan);
		
		
		
		/* WAJIB UNTUK UPLOAD DATA */
		include_once("functions/image.func.php");
		$this->load->library("FileHandler");
		$file = new FileHandler();
		$FILE_DIR = "uploads/";
		
		$reqLinkFile = $_FILES["reqLinkFile"];
		$reqLinkFileTempSize	=  $this->input->post("reqLinkFileTempSize");
		$reqLinkFileTempTipe	=  $this->input->post("reqLinkFileTempTipe");
		$reqLinkFileTemp		=  $this->input->post("reqLinkFileTemp");

		$reqJenis = "MEETINGROOM";
		$reqInsertLink = "";
		for($i=0;$i<count($reqLinkFile);$i++)
		{
			$renameFile = "".date("dmYhis").rand().".".getExtension($reqLinkFile['name'][$i]);
		
			if($file->uploadToDirArray('reqLinkFile', $FILE_DIR, $renameFile, $i))
			{	
			
				createThumbnail($FILE_DIR.$renameFile, $FILE_DIR.$reqJenis.$renameFile, 800);
				unlink($FILE_DIR.$renameFile);
				
				$insertLinkSize = $file->uploadedSize;
				$insertLinkTipe =  $file->uploadedExtension;
				$insertLinkFile =  $reqJenis.$renameFile;
				
				if($reqInsertLink == "")
					$reqInsertLink = $insertLinkFile;
				else
				{
					if($insertLinkFile == "")
					{}
					else
						$reqInsertLink .= ",".$insertLinkFile;
				}
					
			}
		}
		
		for($i=0;$i<count($reqLinkFileTemp);$i++)
		{
			$insertLinkSize = $reqLinkFileTempSize[$i];
			$insertLinkTipe =  $reqLinkFileTempTipe[$i];
			$insertLinkFile =  $reqLinkFileTemp[$i];
			
			
			if($reqInsertLink == "")
				$reqInsertLink = $insertLinkFile;
			else
			{
				if($insertLinkFile == "")
				{}
				else
					$reqInsertLink .= ",".$insertLinkFile;
			}
		}
		
		$meeting_room->setField("LINK_FOTO", $reqInsertLink);
				
		if($reqMode == "insert")
		{
			$meeting_room->setField("LAST_CREATE_USER", $this->USERNAME);
			$meeting_room->insert();
			$reqId = $meeting_room->id;
		}
		else
		{
			$meeting_room->setField("LAST_UPDATE_USER", $this->USERNAME);
			$meeting_room->update();
		}	
		

		$meeting_room_approval = new MeetingRoomApproval();
		$meeting_room_approval->setField("MEETING_ROOM_ID", $reqId);
		$meeting_room_approval->deleteParent();
					
		for($i=0;$i<count($reqPegawaiId);$i++)
		{
			$meeting_room_approval = new MeetingRoomApproval();
			$meeting_room_approval->setField("MEETING_ROOM_ID", $reqId);
			$meeting_room_approval->setField("PEGAWAI_ID", $reqPegawaiId[$i]);
			$meeting_room_approval->setField("PEGAWAI", $reqPegawai[$i]);
			$meeting_room_approval->setField("LAST_CREATE_USER", $this->USERNAME);
			$meeting_room_approval->insert();

		}
		echo "Data berhasil disimpan.";
	
	}
	
	function delete() 
	{
		$reqId	= $this->input->get('reqId');
		$this->load->model("MeetingRoom");
		$meeting_room = new MeetingRoom();

		
		$meeting_room->setField("MEETING_ROOM_ID", $reqId);
		if($meeting_room->delete())
			$arrJson["PESAN"] = "Data berhasil dihapus.";
		else
			$arrJson["PESAN"] = "Data gagal dihapus.";		
		
		echo json_encode($arrJson);
	}	
	
	function combo() 
	{
		$this->load->model("MeetingRoom");
		$meeting_room = new MeetingRoom();

		$meeting_room->selectByParams(array());
		$i = 0;
		while($meeting_room->nextRow())
		{
			$arr_json[$i]['id']		= $meeting_room->getField("MEETING_ROOM_ID");
			$arr_json[$i]['text']	= $meeting_room->getField("NAMA");
			$i++;
		}
		
		echo json_encode($arr_json);
	}
	
}

