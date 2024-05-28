<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class template_surat_json extends CI_Controller {

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
		$this->load->model("TemplateSurat");
		$template_surat = new TemplateSurat();

		$reqId = $this->input->get("reqId");
		// echo $reqId;exit;
		
		$aColumns		= array("TEMPLATE_SURAT_ID","NAMA","JENIS_NASKAH","KETERANGAN");
		$aColumnsAlias	= array("TEMPLATE_SURAT_ID","A.NAMA","JENIS_NASKAH","B.KETERANGAN");

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
			if ( trim($sOrder) == "ORDER BY TEMPLATE_SURAT_ID asc" )
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
		$allRecord = $template_surat->getCountByParams(array(), $statement_privacy.$statement);
		// echo $allRecord;exit;
		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter =  $template_surat->getCountByParams(array(), $statement_privacy.$statement);
		
		 $template_surat->selectByParams(array(), $dsplyRange, $dsplyStart, $statement_privacy.$statement, $sOrder);
		 // echo $template_surat->query;exit;
		
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
		
		while($template_surat->nextRow())		
		{		
			$row = array();		
			for ( $i=0 ; $i<count($aColumns) ; $i++ )		
			{	
				// if($aColumns[$i] == "KETERANGAN")
				// 	$row[] = truncate($template_surat->getField($aColumns[$i]), 5);
				// else
					$row[] = $template_surat->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		echo json_encode( $output );
	}
	
	function add() 
	{
		$this->load->model("TemplateSurat");
		$template_surat = new TemplateSurat();

		$reqMode 			= $this->input->post("reqMode");
		$reqId 				= $this->input->post("reqId");
		
		$reqNama			= $this->input->post("reqNama");
		$reqJenisNaskah		= $this->input->post("reqJenisNaskah");
		$reqKeterangan		= $this->input->post("reqKeterangan");
		
		$template_surat->setField("TEMPLATE_SURAT_ID", $reqId);
		$template_surat->setField("NAMA", $reqNama);
		$template_surat->setField("JENIS_NASKAH_ID", $reqJenisNaskah);
		$template_surat->setField("KETERANGAN", $reqKeterangan);

		if($reqMode == "insert")
		{
			$template_surat->setField("LAST_CREATE_USER", $this->USERNAME);
			$template_surat->insert();
			$reqId = $template_surat->id;
		}
		else
		{
			$template_surat->setField("LAST_UPDATE_USER", $this->USERNAME);
			$template_surat->update();
		}	
		
		/* WAJIB UNTUK UPLOAD DATA */
		$this->load->library("FileHandler");
		$file = new FileHandler();
		$FILE_DIR = "uploads/";
		
		$reqAttachment = $_FILES["reqAttachment"];
		$reqAttachmentTempSize	=  $this->input->post("reqAttachmentTempSize");
		$reqAttachmentTempTipe	=  $this->input->post("reqAttachmentTempTipe");
		$reqAttachmentTemp		=  $this->input->post("reqAttachmentTemp");

		$reqJenis = $reqNama.generateZero($reqId, 5);
		for($i=0;$i<count($reqAttachment);$i++)
		{
			$renameFile = $reqJenis.date("Ymdhis").rand().".".getExtension($reqAttachment['name'][$i]);
		
			if($file->uploadToDirArray('reqAttachment', $FILE_DIR, $renameFile, $i))
			{	
				$insertLinkSize = $file->uploadedSize;
				$insertLinkTipe =  $file->uploadedExtension;
				$insertLinkFile =  $renameFile;
				
				if($insertLinkFile == "")
				{}
				else
				{
					$template_surat_attachement = new TemplateSurat();
					$template_surat_attachement->setField("TEMPLATE_SURAT_ID", $reqId);
					$template_surat_attachement->setField("ATTACHMENT", $renameFile);
					$template_surat_attachement->setField("UKURAN", $insertLinkSize);
					$template_surat_attachement->setField("TIPE", $insertLinkTipe);
					$template_surat_attachement->setField("NAMA", $reqAttachment['name'][$i]);
					$template_surat_attachement->setField("LAST_CREATE_USER", $this->ID);
					$template_surat_attachement->insertAttachment();
				}
			}
		}

		/* END UPLOAD DATA */

		echo "Data berhasil disimpan.";
	}
	
	function delete() 
	{
		$reqId	= $this->input->get('reqId');
		$this->load->model("TemplateSurat");
		$template_surat = new TemplateSurat();

		$template_surat->setField("TEMPLATE_SURAT_ID", $reqId);

		if($template_surat->delete())
			$arrJson["PESAN"] = "Data berhasil dihapus.";
		else
			$arrJson["PESAN"] = "Data gagal dihapus.";		
		
		echo json_encode($arrJson);
	}	
	
	function combo() 
	{
		$this->load->model("TemplateSurat");
		$template_surat = new TemplateSurat();

		$template_surat->selectByParams(array("NOT TEMPLATE_SURAT_ID" => "0"));
		$i = 0;
		while($template_surat->nextRow())
		{
			$arr_json[$i]['id']		= $template_surat->getField("TEMPLATE_SURAT_ID");
			$arr_json[$i]['text']	= $template_surat->getField("NAMA");
			$i++;
		}
		
		echo json_encode($arr_json);
	}
	
}

