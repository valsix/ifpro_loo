<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class klasifikasi_json extends CI_Controller {

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
		$this->MULTIROLE		= $this->kauth->getInstance()->getIdentity()->MULTIROLE;  
		$this->CABANG_ID		= $this->kauth->getInstance()->getIdentity()->CABANG_ID;  
		$this->CABANG			= $this->kauth->getInstance()->getIdentity()->CABANG;  
		$this->SATUAN_KERJA_ID_ASAL	= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_ID_ASAL;  
		$this->SATUAN_KERJA_ASAL	= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_ASAL;  
		$this->SATUAN_KERJA_HIRARKI	= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_HIRARKI;  
		$this->SATUAN_KERJA_JABATAN	= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_JABATAN;  
		$this->KD_LEVEL = $this->kauth->getInstance()->getIdentity()->KD_LEVEL;  
		$this->JENIS_KELAMIN = $this->kauth->getInstance()->getIdentity()->JENIS_KELAMIN;  
		
		
	}
	
	function json() 
	{
		$this->load->model("Klasifikasi");
		$klasifikasi = new Klasifikasi();

		$reqKategori = $this->input->get("reqKategori");
		// echo $reqKategori;exit;
		
		$aColumns		= array("KLASIFIKASI_ID","NAMA","KODE_SURAT","KODE_SURAT_KELUAR");
		$aColumnsAlias	= array("KLASIFIKASI_ID","NAMA","KODE_SURAT","KODE_SURAT_KELUAR");

		
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
			if ( trim($sOrder) == "ORDER BY KLASIFIKASI_ID asc" )
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
		$allRecord = $klasifikasi->getCountByParams(array(), $statement_privacy.$statement);
		// echo $allRecord;exit;
		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter =  $klasifikasi->getCountByParams(array(), $statement_privacy.$statement);
		
		 $klasifikasi->selectByParams(array(), $dsplyRange, $dsplyStart, $statement_privacy.$statement, $sOrder);
		
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
		
		while($klasifikasi->nextRow())		
		{		
			$row = array();		
			for ( $i=0 ; $i<count($aColumns) ; $i++ )		
			{	
				if($aColumns[$i] == "KETERANGAN")
					$row[] = truncate($klasifikasi->getField($aColumns[$i]), 2);
				else
					$row[] = $klasifikasi->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		echo json_encode( $output );	

		
	}
	
	function add() 
	{
		$this->load->model("Klasifikasi");
		$klasifikasi = new Klasifikasi();

		$reqMode 					= $this->input->post("reqMode");
		$reqId 						= $this->input->post("reqId");
		
		$reqParent					= $this->input->post("reqParent");
		$reqNama					= $this->input->post("reqNama");
		$reqKode					= $this->input->post("reqKode");
		$reqKeterangan				= $this->input->post("reqKeterangan");
		$reqRetensiAktif			= $this->input->post("reqRetensiAktif");
		$reqRetensiInAktif			= $this->input->post("reqRetensiInAktif");
		$reqPenyusutanAkhirId		= $this->input->post("reqPenyusutanAkhirId");
		$reqPenyusutanAkhirKode		= $this->input->post("reqPenyusutanAkhirKode");
	
		$klasifikasi->setField("KLASIFIKASI_ID", $reqId);
		$klasifikasi->setField("KLASIFIKASI_ID_PARENT", $reqParent);
		$klasifikasi->setField("NAMA", $reqNama);
		$klasifikasi->setField("KODE", $reqKode);
		$klasifikasi->setField("KETERANGAN", $reqKeterangan);
		$klasifikasi->setField("RETENSI_AKTIF", $reqRetensiAktif);
		$klasifikasi->setField("RETENSI_INAKTIF", $reqRetensiInAktif);
		$klasifikasi->setField("PENYUSUTAN_AKHIR_ID", $reqPenyusutanAkhirId);
		$klasifikasi->setField("PENYUSUTAN_AKHIR_KODE", $reqPenyusutanAkhirKode);

		if($reqMode == "insert")
		{
			$klasifikasi->setField("LAST_CREATE_USER", $this->USERNAME);
			$klasifikasi->insert();
		}
		else
		{
			$klasifikasi->setField("LAST_UPDATE_USER", $this->USERNAME);
			$klasifikasi->update();
		}	
		
		echo "Data berhasil disimpan.";
	
	}
	
	function delete() 
	{
		$reqId	= $this->input->get('reqId');
		$this->load->model("Klasifikasi");
		$klasifikasi = new Klasifikasi();

		
		$klasifikasi->setField("KLASIFIKASI_ID", $reqId);
		if($klasifikasi->delete())
			echo "Data berhasil dihapus.";
		else
			echo "Data gagal dihapus.";		
		
	}	
	
	function aktif() 
	{
		$reqId	= $this->input->get('reqId');
		$this->load->model("Klasifikasi");
		$klasifikasi = new Klasifikasi();
		
		
		
		$klasifikasi->setField("KLASIFIKASI_ID", $reqId);
		$klasifikasi->setField("STATUS_AKTIF", "1");
		$klasifikasi->updateStatus();
		$this->ubahStatus($reqId, "1");
		
		echo "Klasifikasi berhasil diaktifkan";
	}	

	function nonaktif() 
	{
		$reqId	= $this->input->get('reqId');
		$this->load->model("Klasifikasi");
		$klasifikasi = new Klasifikasi();
		
		
		
		$klasifikasi->setField("KLASIFIKASI_ID", $reqId);
		$klasifikasi->setField("STATUS_AKTIF", "0");
		$klasifikasi->updateStatus();
		
		$this->ubahStatus($reqId, "0");
		
		echo "Klasifikasi berhasil di-nonaktifkan";
	}	

	function ubahStatus($reqId, $reqStatus)
	{
		
		$this->load->model("Klasifikasi");
		$klasifikasi = new Klasifikasi();
		$klasifikasi->selectByParams(array("KLASIFIKASI_ID_PARENT" => $reqId));
		while($klasifikasi->nextRow())
		{
			
			$reqId = $klasifikasi->getField("KLASIFIKASI_ID");

			$klasifikasi_update = new Klasifikasi();
			$klasifikasi_update->setField("KLASIFIKASI_ID", $reqId);
			$klasifikasi_update->setField("STATUS_AKTIF", $reqStatus);
			$klasifikasi_update->updateStatus();

			$this->ubahStatus($reqId, $reqStatus);

		}
		
	}
	
	function combo() 
	{
		$this->load->model("Klasifikasi");
		$klasifikasi = new Klasifikasi();

		$klasifikasi->selectByParams(array("KLASIFIKASI_ID_PARENT" => "0"));
		$i = 0;
		while($klasifikasi->nextRow())
		{
			$arr_json[$i]['id']		= $klasifikasi->getField("KLASIFIKASI_ID");
			$arr_json[$i]['text']	= $klasifikasi->getField("NAMA");
			$i++;
		}
		
		echo json_encode($arr_json);
	}
	
	
	function combotree() 
	{
		$this->load->model("Klasifikasi");
		$klasifikasi = new Klasifikasi();

		$klasifikasi->selectByParams(array("KLASIFIKASI_ID_PARENT" => "0", "STATUS_AKTIF" => "1"));
		$i = 0;
		while($klasifikasi->nextRow())
		{
			$arr_json[$i]['id']		= $klasifikasi->getField("KLASIFIKASI_ID");
			$arr_json[$i]['text']	= $klasifikasi->getField("KODE")." - ".$klasifikasi->getField("NAMA");
			$arr_json[$i]['children']	= $this->combotree_children($arr_json[$i]['id']);
			$i++;
		}
		
		echo json_encode($arr_json);
	}
	
	
	function combotree_children($id) 
	{
		$this->load->model("Klasifikasi");
		$klasifikasi = new Klasifikasi();

		$klasifikasi->selectByParams(array("KLASIFIKASI_ID_PARENT" => $id, "STATUS_AKTIF" => "1"));
		$i = 0;
		$arr_json = array();
		while($klasifikasi->nextRow())
		{
			$arr_json[$i]['id']		= $klasifikasi->getField("KLASIFIKASI_ID");
			$arr_json[$i]['text']	= $klasifikasi->getField("KODE")." - ".$klasifikasi->getField("NAMA");
			$arr_json[$i]['children']	= $this->combotree_children($arr_json[$i]['id']);
			$i++;
		}
		
		return $arr_json;
	}
	
	
	function combotree_arsip() 
	{
		$this->load->model("Klasifikasi");
		$klasifikasi = new Klasifikasi();

		$klasifikasi->selectByParamsGenerateArsip($this->SATUAN_KERJA_ID_ASAL, array("A.KLASIFIKASI_ID_PARENT" => "0"));
		$i = 0;
		while($klasifikasi->nextRow())
		{
			$arr_json[$i]['id']		= $klasifikasi->getField("KLASIFIKASI_ID");
			$arr_json[$i]['text']	= $klasifikasi->getField("KODE")." - ".$klasifikasi->getField("NAMA");
			$arr_json[$i]['KODE']	= $klasifikasi->getField("KODE");
			$arr_json[$i]['KODE_TERAKHIR']	= $klasifikasi->getField("KODE_TERAKHIR");
			$arr_json[$i]['PENYUSUTAN_AKHIR_ID']	= $klasifikasi->getField("PENYUSUTAN_AKHIR_ID");
			$arr_json[$i]['RETENSI_AKTIF']	= $klasifikasi->getField("RETENSI_AKTIF");
			$arr_json[$i]['RETENSI_INAKTIF']	= $klasifikasi->getField("RETENSI_INAKTIF");
			$arr_json[$i]['children']	= $this->combotree_arsip_children($arr_json[$i]['id']);
			$i++;
		}
		
		echo json_encode($arr_json);
	}
	
	
	function combotree_arsip_children($id) 
	{
		$this->load->model("Klasifikasi");
		$klasifikasi = new Klasifikasi();

		$klasifikasi->selectByParamsGenerateArsip($this->SATUAN_KERJA_ID_ASAL, array("A.KLASIFIKASI_ID_PARENT" => $id));
		$i = 0;
		$arr_json = array();
		while($klasifikasi->nextRow())
		{
			$arr_json[$i]['id']		= $klasifikasi->getField("KLASIFIKASI_ID");
			$arr_json[$i]['text']	= $klasifikasi->getField("KODE")." - ".$klasifikasi->getField("NAMA");
			$arr_json[$i]['KODE']	= $klasifikasi->getField("KODE");
			$arr_json[$i]['KODE_TERAKHIR']	= $klasifikasi->getField("KODE_TERAKHIR");
			$arr_json[$i]['PENYUSUTAN_AKHIR_ID']	= $klasifikasi->getField("PENYUSUTAN_AKHIR_ID");
			$arr_json[$i]['RETENSI_AKTIF']	= $klasifikasi->getField("RETENSI_AKTIF");
			$arr_json[$i]['RETENSI_INAKTIF']	= $klasifikasi->getField("RETENSI_INAKTIF");
			$arr_json[$i]['children']	= $this->combotree_arsip_children($arr_json[$i]['id']);
			$i++;
		}
		
		return $arr_json;
	}
	
	function dokumen() 
	{
		$this->load->model("Klasifikasi");
		$klasifikasi = new Klasifikasi();

		$klasifikasi->selectByParams(array("KLASIFIKASI_ID_PARENT" => "1"));
		// echo $klasifikasi->query;exit;
		
		$i = 0;
		while($klasifikasi->nextRow())
		{
			// $arr_json[$i]['id']					= $klasifikasi->getField("KLASIFIKASI_ID");
			// $arr_json[$i]['pId']			= $klasifikasi->getField("KLASIFIKASI_ID_PARENT");
			// $arr_json[$i]['penyusutanAkhirId']	= $klasifikasi->getField("PENYUSUTAN_AKHIR_ID");
			// $arr_json[$i]['value']				= $klasifikasi->getField("NAMA");
			// $arr_json[$i]['perihal']			= $klasifikasi->getField("KETERANGAN");
			// $arr_json[$i]['tanggal']			= $klasifikasi->getField("LAST_CREATE_DATE");
			// $arr_json[$i]['kepada']				= "";
			// $arr_json[$i]['unduh']				= "";
			// $arr_json[$i]['jenis']				= "Folder";
			// $arr_json[$i]['type']				= "folder";
			// $arr_json[$i]['open']				= "true";
			// $arr_json[$i]['state']				= $this->has_child($arr_json[$i]['id']);
			// $arr_json[$i]['data']				= $this->dokumen_children($arr_json[$i]['id']);
			// $arr_json[$i]['children']			= $this->dokumen_children($arr_json[$i]['id']);

			$arr_json[$i]['id']		= $klasifikasi->getField("KLASIFIKASI_ID");
			$arr_json[$i]['kode']	= $klasifikasi->getField("KODE");
			$arr_json[$i]['value']	= $klasifikasi->getField("NAMA");
			$arr_json[$i]['data']	= $this->dokumen_children($arr_json[$i]['id']);
			$arr_json[$i]['type'] 	= "folder";
			$arr_json[$i]['open'] 	= open;
			$arr_json[$i]['date'] 	= strtotime($klasifikasi->getField("LAST_CREATE_DATE"));
			$arr_json[$i]['size'] 	= 0;
			$i++;
		}
		header('Content-Type: application/json');

		echo json_encode($arr_json);
	}

	function dokumen_children($id)
	{
		$this->load->model("Klasifikasi");
		$this->load->model("SuratMasuk");
		$klasifikasi = new Klasifikasi();

		$arrStatement = array("KLASIFIKASI_ID_PARENT" => $id);
			
		$rowCount = $klasifikasi->getCountByParams($arrStatement, $statement.$statement_privacy);
		$klasifikasi->selectByParams($arrStatement, $rows, $offset, $statement.$statement_privacy);
		// echo $klasifikasi->query;exit;
		$i = 0;
		$items = array();
		while($klasifikasi->nextRow())
		{
			// $row['id']				= $klasifikasi->getField("KLASIFIKASI_ID");
			// $row['pId']		= $klasifikasi->getField("KLASIFIKASI_ID_PARENT");
			// $row['value']			= $klasifikasi->getField("NAMA");
			// $row['KLASIFIKASI_ID']	= $klasifikasi->getField("KLASIFIKASI_ID");
			// $row['state'] 			= $this->has_child($row['id']);
			// $row['children'] 		= $this->dokumen_children($row['id']);
			// $row['data'] 			= $this->dokumen_data($row['id']);
			// $row['type'] 			= 'folder';

			$row['id']		= $klasifikasi->getField("KLASIFIKASI_ID");
			$row['kode']	= $klasifikasi->getField("KODE");
			$row['value']	= $klasifikasi->getField("NAMA");
			$row['data']	= $this->dokumen_children($row['id']);
			$row['type'] 	= "folder";
			$row['open'] 	= false;
			$row['date'] 	= strtotime($klasifikasi->getField("LAST_CREATE_DATE"));
			$row['size'] 	= 0;

			$i++;
			array_push($items, $row);
		}
		
		return $items;
	}

	function dokumen_data($id)
	{
		$this->load->model("SuratMasuk");
		$surat_masuk = new SuratMasuk();

		$reqKode = $this->input->get("reqKode");
		$reqSearch = $this->input->get("reqSearch");

		$statement = " AND C.KODE LIKE '".$reqKode."%'";

		if($reqSearch <> ""){
			$statement .= " AND (
							A.NOMOR LIKE '%".$reqSearch."%' OR 
							UPPER(A.PERIHAL) LIKE '%".strtoupper($reqSearch)."%' OR 
							UPPER(A.JENIS) LIKE '%".strtoupper($reqSearch)."%'
						)";
		}

		$surat_masuk->selectByParamsSuratDokumenKlasifikasi(array(), -1, -1, $statement);
		// echo $surat_masuk->query;exit;
		$i = 0;
		$items = array();
		while($surat_masuk->nextRow())
		{
			// $row['id']		= $surat_masuk->getField("SURAT_MASUK_ID");
			$row['value'] 		= $surat_masuk->getField("NOMOR");
			$row['perihal'] 	= $surat_masuk->getField("PERIHAL");
			$row['kepada'] 		= $surat_masuk->getField("KEPADA");
			$row['jenis'] 		= $surat_masuk->getField("JENIS");
			$row['unduh'] 		= "<a href='uploads/".$surat_masuk->getField("ATTACHMENT")."' target='_blank'><i class='webix_icon icon fa fa-download' style='margin-top:10px; color: black;'></i></a>";
			$row['type'] 		= "pdf";
			$row['tanggal'] 	= ($surat_masuk->getField("TANGGAL"));
			$row['date'] 		= ($surat_masuk->getField("TANGGAL"));
			$row['size']	 	= 0;

			$i++;
			$items[] = $row;
		}
		
		echo json_encode($items);
	}

	function treetable() 
	{
		
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : 50;
		$id   = isset($_POST['id']) ? intval($_POST['id']) : 0;
		$offset = ($page-1)*$rows;
		
		$reqPencarian = $this->input->get("reqPencarian");
		$reqMode = $this->input->get("reqMode");
		$reqStatus = $this->input->get("reqStatus");
		
		if($reqStatus == "")
			$reqStatus = "1";
		
		$this->load->model("Klasifikasi");
		$klasifikasi = new Klasifikasi();

		if($reqPencarian == "")
		{}
		else
			$statement = " AND UPPER(A.NAMA) LIKE '%".strtoupper($reqPencarian)."%' ";
		
		if($reqStatus == "0")
		{}
		else
			$statement_privacy = " AND STATUS_AKTIF = '".$reqStatus."' ";
		
		$arrStatement = array("COALESCE(NULLIF(KLASIFIKASI_ID_PARENT, ''), '0')" => $id);
			
		$rowCount = $klasifikasi->getCountByParams($arrStatement, $statement.$statement_privacy);
		$klasifikasi->selectByParams($arrStatement, $rows, $offset, $statement.$statement_privacy, " ORDER BY NO_URUT ASC ");
		// echo $klasifikasi->query;exit;
		$i = 0;
		$items = array();
		while($klasifikasi->nextRow())
		{
			$row['id']				= coalesce($klasifikasi->getField("KODE_SO"), $klasifikasi->getField("KLASIFIKASI_ID"));
			$row['parentId']		= $klasifikasi->getField("KLASIFIKASI_ID_PARENT");
			$row['text']			= $klasifikasi->getField("KODE")." - ".$klasifikasi->getField("NAMA");
			$row['KLASIFIKASI_ID']	= $klasifikasi->getField("KLASIFIKASI_ID");
			$row['NAMA']			= $klasifikasi->getField("NAMA");
			$row['KODE']			= $klasifikasi->getField("KODE");
			$row['STATUS_AKTIF']	= $klasifikasi->getField("STATUS_AKTIF");
			if($klasifikasi->getField("STATUS_AKTIF") == "1")
				$row['STATUS_AKTIF_DESC']	= "Aktif";
			else
				$row['STATUS_AKTIF_DESC']	= "Non-Aktif";
			
			$row['state'] 			= $this->has_child($row['id']);
			$row['children'] 		= $this->children($row['id'], $reqStatus);
			$i++;
			array_push($items, $row);
		}
		$result["rows"] = $items;
		$result["total"] = $rowCount;
		
		echo json_encode($result);
	}
	
	function children($id, $reqStatus)
	{
		$this->load->model("Klasifikasi");
		$klasifikasi = new Klasifikasi();
		$arrStatement = array("COALESCE(NULLIF(KLASIFIKASI_ID_PARENT, ''), '0')" => $id, "NOT KLASIFIKASI_ID_PARENT" => "0");

		if($reqStatus == "0")
		{}
		else
			$statement_privacy = " AND STATUS_AKTIF = '".$reqStatus."' ";
		
					
		$rowCount = $klasifikasi->getCountByParams($arrStatement, $statement.$statement_privacy);
		$klasifikasi->selectByParams($arrStatement, $rows, $offset, $statement.$statement_privacy, " ORDER BY NO_URUT ASC ");
		// echo $klasifikasi->query;exit;
		$i = 0;
		$items = array();
		while($klasifikasi->nextRow())
		{
			$row['id']				= coalesce($klasifikasi->getField("KODE_SO"), $klasifikasi->getField("KLASIFIKASI_ID"));
			$row['parentId']		= $klasifikasi->getField("KLASIFIKASI_ID_PARENT");
			$row['text']			= $klasifikasi->getField("KODE")." - ".$klasifikasi->getField("NAMA");
			$row['KLASIFIKASI_ID']	= $klasifikasi->getField("KLASIFIKASI_ID");
			$row['NAMA']			= $klasifikasi->getField("NAMA");
			$row['KODE']			= $klasifikasi->getField("KODE");
			$row['STATUS_AKTIF']	= $klasifikasi->getField("STATUS_AKTIF");
			
			
			if($klasifikasi->getField("STATUS_AKTIF") == "1")
				$row['STATUS_AKTIF_DESC']	= "Aktif";
			else
				$row['STATUS_AKTIF_DESC']	= "Non-Aktif";
			
			$row['state'] 			= $this->has_child($row['id']);
			$row['children'] 		= $this->children($row['id'], $reqStatus);
			$i++;
			array_push($items, $row);
		}
		
		return $items;
	}
	
	function has_child($id)
	{
		$this->load->model("Klasifikasi");
		$klasifikasi = new Klasifikasi();
		$adaData = $klasifikasi->getCountByParams(array("COALESCE(NULLIF(KLASIFIKASI_ID_PARENT, ''), '0')" => $id));
		return $adaData > 0 ? true : false;
	}
	
}

