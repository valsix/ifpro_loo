<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class konten_json extends CI_Controller {

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
	}
	
	function json() 
	{
		$this->load->model("Konten");
		$konten = new Konten();

		$reqKategori = $this->input->get("reqKategori");
		// echo $reqKategori;exit;

 
		
		$aColumns		= array("KONTEN_ID", "KODE", "NAMA", "KETERANGAN", "ATTACHMENT");
		$aColumnsAlias	= array("KONTEN_ID", "KODE", "NAMA", "KETERANGAN", "ATTACHMENT");

		
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
			if ( trim($sOrder) == "ORDER BY KONTEN_ID asc" )
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
		$allRecord = $konten->getCountByParams(array(), $statement_privacy.$statement);
		// echo $allRecord;exit;
		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter =  $konten->getCountByParams(array(), $statement_privacy.$statement);
		
		 $konten->selectByParams(array(), $dsplyRange, $dsplyStart, $statement_privacy.$statement, $sOrder);
		
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
		
		while($konten->nextRow())		
		{		
			$row = array();		
			for ( $i=0 ; $i<count($aColumns) ; $i++ )		
			{	
				if($aColumns[$i] == "KETERANGAN")
					$row[] = truncate($konten->getField($aColumns[$i]), 2);
				elseif($aColumns[$i] == "ATTACHMENT")
					$row[] = "<img src='uploads/konten/".$konten->getField($aColumns[$i])."' height='50px'>";
				else
					$row[] = $konten->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		echo json_encode( $output );	
	}
	
	function add() 
	{
		$this->load->model("Konten");
		$konten = new Konten();

		$reqMode 					= $this->input->post("reqMode");
		$reqId 						= $this->input->post("reqId");
		
		$reqKontenId				= $this->input->post("reqKontenId");
		$reqKode					= $this->input->post("reqKode");
		$reqNama					= $this->input->post("reqNama");
		$reqKeterangan				= $_POST["reqKeterangan"];

		$konten->setField("KONTEN_ID", $reqId);
		$konten->setField("KODE", $reqKode);
		$konten->setField("NAMA", $reqNama);
		$konten->setField("KETERANGAN", str_replace("'","",$reqKeterangan));

		/* WAJIB UNTUK UPLOAD DATA */
		$this->load->library("FileHandler");
		$FILE_DIR = "uploads/konten/";
		$insertLinkFile = "";
		$reqLinkFile 		= $_FILES['reqLinkFile'];
		$reqLinkFileTemp 	= $this->input->post('reqLinkFileTemp');
		for($i=0;$i<count($reqLinkFile);$i++)
		{
			$file = new FileHandler();
			$renameFile = date("dmYHis").$i.".".getExtension($reqLinkFile['name'][$i]);
			if($file->uploadToDirArray('reqLinkFile', $FILE_DIR, $renameFile, $i))
			{
				if($insertLinkFile == ""){		
					$insertLinkFile =  $renameFile;
				}
				else{
					$insertLinkFile .=  ",".$renameFile;					
				}
			}
		}
		
		for($i=0;$i<count($reqLinkFileTemp);$i++)
		{
			if($reqLinkFileTemp[$i] == "")
			{}
			else
			{
				if($insertLinkFile == ""){	
					$insertLinkFile =  $reqLinkFileTemp[$i];
				}
				else{
					$insertLinkFile .=  ",".$reqLinkFileTemp[$i];		
				}
			}
		}		
		/* WAJIB UNTUK UPLOAD DATA */
		
		$konten->setField("ATTACHMENT", $insertLinkFile);
				
		if($reqMode == "insert")
		{
			$konten->setField("LAST_CREATE_USER", $this->USERNAME);
			$konten->insert();
		}
		else
		{
			$konten->setField("LAST_UPDATE_USER", $this->USERNAME);
			$konten->update();
		}
		
		echo "Data berhasil disimpan.";
	}
	
	function delete() 
	{
		$reqId	= $this->input->get('reqId');
		$this->load->model("Konten");
		$konten = new Konten();
		$konten->setField("KONTEN_ID", $reqId);
		if($konten->delete())
			$arrJson["PESAN"] = "Data berhasil dihapus.";
		else
			$arrJson["PESAN"] = "Data gagal dihapus.";		
		echo json_encode($arrJson);
	}	

	
	function combo() 
	{
		$this->load->model("Konten");
		$konten = new Konten();
		$konten->selectByParams(array("NOT KONTEN_ID" => "0"));
		$i = 0;
		while($konten->nextRow())
		{
			$arr_json[$i]['id']		= $konten->getField("KONTEN_ID");
			$arr_json[$i]['text']	= $konten->getField("NAMA");
			$i++;
		}
		echo json_encode($arr_json);
	}
	
}

