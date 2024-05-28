<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class satuan_kerja_kelompok_json extends CI_Controller {

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
		$this->load->model("SatuanKerjaKelompok");
		$satuan_kerja_kelompok = new SatuanKerjaKelompok();

		$reqKategori = $this->input->get("reqKategori");
		// echo $reqKategori;exit;
		
		$aColumns		= array("SATUAN_KERJA_KELOMPOK_ID","NAMA");
		$aColumnsAlias	= array("SATUAN_KERJA_KELOMPOK_ID","NAMA");

		
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
			if ( trim($sOrder) == "ORDER BY SATUAN_KERJA_KELOMPOK_ID asc" )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY SATUAN_KERJA_KELOMPOK_ID asc";
				 
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

		$statement_privacy .= "  AND CABANG_OWNER = '".$this->CABANG_ID."' ";
		
		 $statement= " AND (UPPER(NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%')";
		$allRecord = $satuan_kerja_kelompok->getCountByParams(array(), $statement_privacy.$statement);
		// echo $allRecord;exit;
		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter =  $satuan_kerja_kelompok->getCountByParams(array(), $statement_privacy.$statement);
		
		 $satuan_kerja_kelompok->selectByParams(array(), $dsplyRange, $dsplyStart, $statement_privacy.$statement, $sOrder);
		
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
		
		while($satuan_kerja_kelompok->nextRow())		
		{		
			$row = array();		
			for ( $i=0 ; $i<count($aColumns) ; $i++ )		
			{	
				
					$row[] = $satuan_kerja_kelompok->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		echo json_encode( $output );	

		
	}
	
	function add()
	{
		$this->load->model("SatuanKerjaKelompok");
		$satuan_kerja_kelompok = new SatuanKerjaKelompok();

		$this->load->model("SatuanKerjaKelompokDetil");

		$reqMode= $this->input->post("reqMode");
		$reqId= $this->input->post("reqId");

		$reqSatuanKerjaKelompokId 	= $this->input->post("reqSatuanKerjaKelompokId");
		$reqKode					= $this->input->post("reqKode");
		$reqNama					= $this->input->post("reqNama");
		$reqCabang					= $this->input->post("reqCabang");
		$reqSatuanKerjaId			= $this->input->post("reqSatuanKerjaId");


		$reqSatuanKerjaId= $this->input->post("reqSatuanKerjaId");
		$reqKelompokJabatan= $this->input->post("reqKelompokJabatan");
		// echo $reqKelompokJabatan;exit;

		$satuan_kerja_kelompok->setField("SATUAN_KERJA_KELOMPOK_ID", $reqId);
		$satuan_kerja_kelompok->setField("KODE", $reqKode);
		$satuan_kerja_kelompok->setField("NAMA", $reqNama);
		$satuan_kerja_kelompok->setField("CABANG_ID", $reqCabang);
		$satuan_kerja_kelompok->setField("LAST_CREATE_USER", $this->USERNAME);
		$satuan_kerja_kelompok->setField("LAST_UPDATE_USER", $this->USERNAME);
		$satuan_kerja_kelompok->setField("CABANG_OWNER", $this->CABANG_ID);
		

		if($reqMode == "insert")
		{
			$satuan_kerja_kelompok->insert();
			$reqId = $satuan_kerja_kelompok->id;
		}
		else
			$satuan_kerja_kelompok->update();

		$setdetil= new SatuanKerjaKelompokDetil();
		$setdetil->setField("SATUAN_KERJA_KELOMPOK_ID", $reqId);
		$setdetil->deletePerent();
		
		/* JIKA CABANG SUDAH DITENTUKAN TIDAK BOLEH MENENTUKAN JABATAN !!!!! MERUSAK DATA !! */
		if($reqCabang == "")
		{
			if(!empty($reqSatuanKerjaId))
			{
				$reqSatuanKerjaId= explode(",", $reqSatuanKerjaId);
				for($i=0;$i<count($reqSatuanKerjaId);$i++)
				{
					$setdetil= new SatuanKerjaKelompokDetil();
					$setdetil->setField("SATUAN_KERJA_KELOMPOK_ID", $reqId);
					$setdetil->setField("SATUAN_KERJA_ID", $reqSatuanKerjaId[$i]);
					$setdetil->setField("NAMA", $reqNama);
					$setdetil->setField("LAST_CREATE_USER", $this->USERNAME);
					$setdetil->insert();
				}
			}

			if(!empty($reqKelompokJabatan))
			{
				$reqKelompokJabatan= explode(",", $reqKelompokJabatan);
				for($i=0;$i<count($reqKelompokJabatan);$i++)
				{
					$setdetil= new SatuanKerjaKelompokDetil();
					$setdetil->setField("SATUAN_KERJA_KELOMPOK_ID", $reqId);
					$setdetil->setField("KELOMPOK_JABATAN", $reqKelompokJabatan[$i]);
					$setdetil->setField("LAST_CREATE_USER", $this->USERNAME);
					$setdetil->insertkelompok();
				}
			}
		}

		echo "Data berhasil disimpan.";
			
	}

	function delete()
	{
		$this->load->model("SatuanKerjaKelompok");
		$this->load->model("SatuanKerjaKelompokDetil");
		$satuan_kerja_kelompok = new SatuanKerjaKelompok();
		$satuan_kerja_kelompok_detil = new SatuanKerjaKelompokDetil();

		$reqId = $this->input->get("reqId");
		$satuan_kerja_kelompok->setField("SATUAN_KERJA_KELOMPOK_ID", $reqId);
		$satuan_kerja_kelompok_detil->setField("SATUAN_KERJA_KELOMPOK_ID", $reqId);
		$satuan_kerja_kelompok_detil->deletePerent();
		if($satuan_kerja_kelompok->delete()){
			echo "Data berhasil dihapus.";
		}else{
			echo "Data gagal dihapus.";
		}
	}

	
	function combo() 
	{
		$reqPencarian = $this->input->get("q");

		$this->load->model("BalasCepat");
		$balas_cepat = new BalasCepat();

		if($reqPencarian == "")
		{}
		else
			$statement = " AND (UPPER(NAMA) LIKE '%".strtoupper($reqPencarian)."%') ";

		$balas_cepat->selectByParams(array(), -1, -1, $statement);
		$i = 0;
		while($balas_cepat->nextRow())
		{
			$arr_json[$i]['NAMA']	= $balas_cepat->getField("NAMA");
			$arr_json[$i]['NAMA']	= $balas_cepat->getField("NAMA");
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
		
		
		$this->load->model("SatuanKerjaKelompok");
		$satuan_kerja_kelompok = new SatuanKerjaKelompok();

		$statement_privacy .= "  AND CABANG_OWNER = '".$this->CABANG_ID."' ";
		$statement = " AND (UPPER(NAMA) LIKE '%".strtoupper($reqPencarian)."%') ";
			
		$rowCount = $satuan_kerja_kelompok->getCountByParams($arrStatement, $statement.$statement_privacy);
		$satuan_kerja_kelompok->selectByParams($arrStatement, $rows, $offset, $statement.$statement_privacy, " ORDER BY NAMA ASC ");
		$i = 0;
		$items = array();
		while($satuan_kerja_kelompok->nextRow())
		{
			$row['id']				= $satuan_kerja_kelompok->getField("SATUAN_KERJA_KELOMPOK_ID");
			$row['text']			= $satuan_kerja_kelompok->getField("NAMA");
			$row['KELOMPOK']		= $satuan_kerja_kelompok->getField("NAMA");
			$row['JABATAN']			= $satuan_kerja_kelompok->getField("JABATAN");
			$i++;
			array_push($items, $row);
			unset($row);
		}
		$result["rows"] = $items;
		$result["total"] = $rowCount;
		
		echo json_encode($result);
	}
	
	
	
}

