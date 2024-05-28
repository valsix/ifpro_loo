<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class mutasi_pejabat_json extends CI_Controller {

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

		$this->TREETABLE_COUNT = 0;
		
	}
	
	function json() 
	{
		$this->load->model("MutasiPejabat");
		$mutasi_pejabat = new MutasiPejabat();

		$reqKategori = $this->input->get("reqKategori");
		// echo $reqKategori;exit;

		$aColumns= array("TIPE_INFO", "NO_SK", "TANGGAL_MUTASI", "PEGAWAI_NIP", "PEGAWAI_NAMA", "PEGAWAI_JABATAN_NAMA", "PEGAWAI_JABATAN_NAMA_BARU_INFO", "MUTASI_PEJABAT_ID");
		$aColumnsAlias= $aColumns;
		
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
			if ( trim($sOrder) == "ORDER BY MUTASI_PEJABAT_ID asc" )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.PEGAWAI_NAMA asc";
				 
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

		$statement= "
		AND 
		(
			UPPER(A.PEGAWAI_NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%'
			OR UPPER(A.NO_SK) LIKE '%".strtoupper($_GET['sSearch'])."%'
			OR UPPER(A.PEGAWAI_NIP) LIKE '%".strtoupper($_GET['sSearch'])."%'
			OR UPPER(A.PEGAWAI_JABATAN_NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%'
			OR UPPER(A.PEGAWAI_JABATAN_NAMA_BARU) LIKE '%".strtoupper($_GET['sSearch'])."%' 
		)";

		$reqUnitKerjaId= $this->CABANG_ID;
		if ($reqUnitKerjaId == "PST"){}
		else
			$statement.= " AND CABANG_ID = '".$reqUnitKerjaId."'";

		$allRecord = $mutasi_pejabat->getCountByParams(array(), $statement_privacy.$statement);
		// echo $allRecord;exit;
		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter =  $mutasi_pejabat->getCountByParams(array(), $statement_privacy.$statement);
		
		$sOrder= " ORDER BY A.LAST_CREATE_DATE DESC";
		$mutasi_pejabat->selectByParams(array(), $dsplyRange, $dsplyStart, $statement_privacy.$statement, $sOrder);
		
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
		
		while($mutasi_pejabat->nextRow())		
		{		
			$row = array();		
			for ( $i=0 ; $i<count($aColumns) ; $i++ )		
			{	
				if($aColumns[$i] == "KETERANGAN")
					$row[] = truncate($mutasi_pejabat->getField($aColumns[$i]), 2);
				else
					$row[] = $mutasi_pejabat->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}

		echo json_encode( $output );
	}
		
	function add() 
	{ 
		$this->load->model("MutasiPejabat");
		$this->load->model("SatuanKerja");

		$mutasi_pejabat = new MutasiPejabat();

		$reqMode= $this->input->post("reqMode");
		$reqId= $this->input->post("reqId");
		$reqNoSk= $this->input->post("reqNoSk");
		$reqTanggalMutasi= $this->input->post("reqTanggalMutasi");
		$reqTipe= $this->input->post("reqTipe");

		$reqCabangId= $this->CABANG_ID;
		$reqPegawaiCabangId= $this->input->post("reqPegawaiCabangId");
		$reqPegawaiNip= $this->input->post("reqPegawaiNip");
		$reqPegawaiNama= $this->input->post("reqPegawaiNama");
		$reqUnitKerjaId= $this->input->post("reqUnitKerjaId");
		$reqUnitKerjaNama= $this->input->post("reqUnitKerjaNama");
		$reqJabatanNama= $this->input->post("reqJabatanNama");

		$reqPegawaiJabatanNipBaru= $this->input->post("reqPegawaiJabatanNipBaru");
		$reqPegawaiJabatanNamaBaru= $this->input->post("reqPegawaiJabatanNamaBaru");
		$reqPegawaiJabatanUnitKerjaId= $this->input->post("reqPegawaiJabatanUnitKerjaId");
		$reqPegawaiJabatanUnitKerjaNama= $this->input->post("reqPegawaiJabatanUnitKerjaNama");
		$reqPegawaiJabatanUnitKerjaEntri= $this->input->post("reqPegawaiJabatanUnitKerjaEntri");
		$reqPegawaiJabatanUnitKerjaEntriIdTujuan= $this->input->post("reqPegawaiJabatanUnitKerjaEntriIdTujuan");
		$reqPegawaiJabatanUnitKerjaEntriId= $this->input->post("reqPegawaiJabatanUnitKerjaEntriId");
		$reqJabatanBaru= $this->input->post("reqJabatanBaru");
		$reqAksiPejabatPengganti= $this->input->post("reqAksiPejabatPengganti");

		$reqPegawaiJabatanUnitKerjaEntriStatus= "";
		if(!empty($reqPegawaiJabatanUnitKerjaEntri))
		{
			$reqPegawaiJabatanUnitKerjaEntriStatus= "baru";
		}

		if(!empty($reqPegawaiJabatanUnitKerjaId) && !empty($reqPegawaiJabatanUnitKerjaNama))
		{
			$chk= new SatuanKerja();
			$jumlahchk= $chk->getCountByParams(array(), " AND TREE_PARENT = '".$reqPegawaiJabatanUnitKerjaId."' AND UPPER(NAMA) = '".strtoupper($reqPegawaiJabatanUnitKerjaEntri)."'");
			// echo $jumlahchk;exit;
			// echo $chk->query;exit;

			if($jumlahchk > 0)
			{
				echo "error-Unit Kerja ".$reqPegawaiJabatanUnitKerjaEntri." sudah ada.";
				exit;
			}
		}

		// if(!empty($reqPegawaiJabatanUnitKerjaEntri))
		// {
		// 	$setdetil= new SatuanKerja();
		// }

		$mutasi_pejabat->setField("MUTASI_PEJABAT_ID", $reqId);
		$mutasi_pejabat->setField("CABANG_ID", $reqCabangId);
		$mutasi_pejabat->setField("NO_SK", $reqNoSk);
		$mutasi_pejabat->setField("TIPE", $reqTipe);
		$mutasi_pejabat->setField("TANGGAL_MUTASI", dateToDbCheck($reqTanggalMutasi));
		$mutasi_pejabat->setField("PEGAWAI_CABANG_ID", $reqPegawaiCabangId);
		$mutasi_pejabat->setField("PEGAWAI_NIP", $reqPegawaiNip);
		$mutasi_pejabat->setField("PEGAWAI_NAMA", $reqPegawaiNama);
		$mutasi_pejabat->setField("UNIT_KERJA_ID", $reqUnitKerjaId);
		$mutasi_pejabat->setField("UNIT_KERJA_NAMA", $reqUnitKerjaNama);
		$mutasi_pejabat->setField("PEGAWAI_JABATAN_NAMA", $reqJabatanNama);
		$mutasi_pejabat->setField("PEGAWAI_JABATAN_NIP_BARU", $reqPegawaiJabatanNipBaru);
		$mutasi_pejabat->setField("PEGAWAI_JABATAN_PEGAWAI_BARU", $reqPegawaiJabatanNamaBaru);
		$mutasi_pejabat->setField("PEGAWAI_JABATAN_UNIT_KERJA_ID", $reqPegawaiJabatanUnitKerjaId);
		$mutasi_pejabat->setField("PEGAWAI_JABATAN_UNIT_KERJA_NAMA", $reqPegawaiJabatanUnitKerjaNama);
		$mutasi_pejabat->setField("PEGAWAI_JABATAN_UNIT_KERJA_ENTRI_STATUS", $reqPegawaiJabatanUnitKerjaEntriStatus);
		$mutasi_pejabat->setField("PEGAWAI_JABATAN_UNIT_KERJA_ENTRI_ID_TUJUAN", $reqPegawaiJabatanUnitKerjaEntriIdTujuan);
		$mutasi_pejabat->setField("PEGAWAI_JABATAN_UNIT_KERJA_ENTRI_ID", $reqPegawaiJabatanUnitKerjaEntriId);
		$mutasi_pejabat->setField("PEGAWAI_JABATAN_UNIT_KERJA_ENTRI", $reqPegawaiJabatanUnitKerjaEntri);
		$mutasi_pejabat->setField("PEGAWAI_JABATAN_NAMA_BARU", $reqJabatanBaru);
		$mutasi_pejabat->setField("AKSI_PEJABAT_PENGGANTI", ValToNullDB($reqAksiPejabatPengganti));
		if($reqMode == "insert")
		{
			$mutasi_pejabat->setField("LAST_CREATE_USER", $this->USERNAME);
			$mutasi_pejabat->insert();
		}
		// else
		// {
		// 	$mutasi_pejabat->setField("LAST_UPDATE_USER", $this->USERNAME);
		// 	$mutasi_pejabat->update();
		// }	
		// echo $mutasi_pejabat->query;exit;
		
		echo "success-Data berhasil disimpan.";
	
	}
	
	function delete() 
	{
		$reqId	= $this->input->get('reqId');
		$this->load->model("MutasiPejabat");
		$mutasi_pejabat = new MutasiPejabat();
		$mutasi_pejabat->setField("mutasi_pejabat_ID", $reqId);
		if($mutasi_pejabat->delete())
			$arrJson["PESAN"] = "Data berhasil dihapus.";
		else
			$arrJson["PESAN"] = "Data gagal dihapus.";		
		echo json_encode($arrJson);
	}

	function combojabatan()
	{
		$reqPencarian = $this->input->get("q");
		$reqMode = $this->input->get("reqMode");

		if($reqMode == "baru")
		{
			$i = 0;
			$arr_json[$i]['id']= "";
			$arr_json[$i]['text']= "Jabatan Kosong";
			$i++;

			$arr_json[$i]['id']= "baru";
			$arr_json[$i]['text']= "Jabatan Baru";
			$i++;
		}
		else
		{
			$i = 0;
			$arr_json[$i]['id']= "";
			$arr_json[$i]['text']= "Semua";
			$i++;

			$arr_json[$i]['id']= "kosong";
			$arr_json[$i]['text']= "Jabatan Kosong";
			$i++;	
		}

		echo json_encode($arr_json);
	}

	function combo_cabang_alamat()
	{
		$reqPencarian = $this->input->get("q");
		$reqJenisSurat = $this->input->get("reqJenisSurat");

		$this->load->model("SatuanKerja");

		$satuan_kerja = new SatuanKerja();

		$satuan_kerja->selectByParamsAktif(array("SATUAN_KERJA_ID_PARENT" => "SATKER"), -1, -1, $statement, " ORDER BY A.URUT, A.NAMA ASC ");
		// echo $satuan_kerja->query;exit;
		$i = 0;
		while ($satuan_kerja->nextRow()) {
			$arr_json[$i]['id']		= $satuan_kerja->getField("SATUAN_KERJA_ID");
			$arr_json[$i]['text']	= $satuan_kerja->getField("NAMA");
			$arr_json[$i]['SATUAN_KERJA_ID']	= $satuan_kerja->getField("SATUAN_KERJA_ID");
			$arr_json[$i]['SATUAN_KERJA']	= $satuan_kerja->getField("NAMA");
			$arr_json[$i]['NAMA_PEGAWAI']	= $satuan_kerja->getField("NAMA_PEGAWAI");
			$arr_json[$i]['NIP']	= $satuan_kerja->getField("NIP");
			$i++;
		}

		echo json_encode($arr_json);
	}

	function treetable_master() 
	{	
		$reqUnitKerjaId = $this->input->get("reqUnitKerjaId");
		$reqTipe = $this->input->get("reqTipe");
		
		if($reqUnitKerjaId == "")
			$reqUnitKerjaId = $this->CABANG_ID;
		
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : 50;
		$id   = isset($_POST['id']) ? intval($_POST['id']) : 0;
		$offset = ($page-1)*$rows;
		
		$reqPencarian = trim($this->input->get("reqPencarian"));
		$reqMode = $this->input->get("reqMode");
		
		$this->load->model("SatuanKerja");
		$this->load->model("SatuanKerjaKelompok");

		$satuan_kerja = new SatuanKerja();

		if($reqPencarian == "")
		{
			if($reqTipe == "1")
			{
				$arrStatement = array("COALESCE(NULLIF(KODE_PARENT, ''), '0')" => $reqUnitKerjaId, "NOT SATUAN_KERJA_ID_PARENT" => "SATKER", "SATUAN_KERJA_ID_PARENT" => $reqUnitKerjaId, "STATUS_AKTIF" => '1');
			}
			else
			{
				if($reqMode == "jabatan_baru_cari")
				{
					$arrStatement = array("SATUAN_KERJA_ID_PARENT" => $reqUnitKerjaId);
				}
				else
				{
					$arrStatement = array("COALESCE(NULLIF(KODE_PARENT, ''), '0')" => $reqUnitKerjaId, "NOT SATUAN_KERJA_ID_PARENT" => "SATKER", "SATUAN_KERJA_ID_PARENT" => $reqUnitKerjaId);
				}

			}
		}
		else
		{
			$arrStatement = array("NOT SATUAN_KERJA_ID_PARENT" => "SATKER", "SATUAN_KERJA_ID_PARENT" => $reqUnitKerjaId, "STATUS_AKTIF" => '1');
			$statement = " AND (UPPER(NAMA) LIKE '%".strtoupper($reqPencarian)."%' OR UPPER(JABATAN) LIKE '%".strtoupper($reqPencarian)."%') ";
		}

		if($reqTipe == "2" && $reqMode == "jabatan_baru_cari")
			$statement .= " AND ( COALESCE(NULLIF(KELOMPOK_JABATAN, ''), '0') NOT IN ('0') AND (CHECK_ADA_PEJABAT = 0 OR COALESCE(NULLIF(NIP, ''), '0') IN ('0')) )";
			// $statement .= " AND CHECK_ADA_PEJABAT = 0";
			
		$rowCount = $satuan_kerja->getCountByParamsFix($arrStatement, $statement.$statement_privacy);
		$satuan_kerja->selectByParams($arrStatement, $rows, $offset, $statement.$statement_privacy, " ORDER BY KODE_SO ASC ");
		// echo $satuan_kerja->query;exit;
		$i = 0;
		$items = array();
		while($satuan_kerja->nextRow())
		{
			$this->TREETABLE_COUNT++;
			
			$row['id']				= coalesce($satuan_kerja->getField("KODE_SO"), $satuan_kerja->getField("SATUAN_KERJA_ID"));
			$row['parentId']		= $satuan_kerja->getField("KODE_PARENT");
			$row['text']			= $satuan_kerja->getField("NAMA");
			$row['SATUAN_KERJA_ID']	= $satuan_kerja->getField("SATUAN_KERJA_ID");
			$row['SATUAN_KERJA_ID_PARENT']	= $satuan_kerja->getField("SATUAN_KERJA_ID_PARENT");
			$row['NAMA']			= $satuan_kerja->getField("NAMA");
			$row['NAMA_PEGAWAI']	= $satuan_kerja->getField("NAMA_PEGAWAI");
			if($reqTipe == "1" || $reqMode == "jabatan_baru")
				$row['JABATAN']= $satuan_kerja->getField("JABATAN");
			else
				$row['JABATAN']= str_replace("Plh. ", "", str_replace("Plt. ", "", $satuan_kerja->getField("JABATAN")));

			$row['NIP']				= $satuan_kerja->getField("NIP");
			$row['KODE_SURAT']				= $satuan_kerja->getField("KODE_SURAT");
			$row['KELOMPOK_JABATAN']		= $satuan_kerja->getField("KELOMPOK_JABATAN");
			$row['STATUS_AKTIF']			= $satuan_kerja->getField("STATUS_AKTIF");
			$row['STATUS_AKTIF_DESC']		= $satuan_kerja->getField("STATUS_AKTIF_DESC");

			$chekadapejabat= $satuan_kerja->getField("CHECK_ADA_PEJABAT");
			$row['CHECK_ADA_PEJABAT']= $chekadapejabat;
			if($chekadapejabat == 1)
				$chekadapejabat= "";
			else
				$chekadapejabat= "1";
			$row['MUTASI_AKSI_PEJABAT_PENGGANTI']= $chekadapejabat;
			$row['MUTASI_NIP']= $satuan_kerja->getField("NIP");
			$row['MUTASI_NAMA_PEGAWAI']= $satuan_kerja->getField("NAMA_PEGAWAI");
			$row['MUTASI_NAMA']= $satuan_kerja->getField("NAMA");
			$row['MUTASI_JABATAN']= str_replace("Plh. ", "", str_replace("Plt. ", "", $satuan_kerja->getField("JABATAN")));

			$row['LINK_URL']		= $satuan_kerja->getField("LINK_URL");
			if(trim($reqPencarian) == "")
			{
				$row['state'] 			= $this->has_child($row['id']);
				$row['children'] 		= $this->children_master($satuan_kerja->getField("SATUAN_KERJA_ID"), $satuan_kerja->getField("SATUAN_KERJA_ID_PARENT"), $reqTipe, $reqMode);
			}
			$i++;
			array_push($items, $row);
			unset($row);
		}

		$result["rows"] = $items;
		$result["total"] = $this->TREETABLE_COUNT;
		
		echo json_encode($result);
	}

	function children_master($id, $satkerId, $reqTipe="1", $reqMode= "")
	{
		$this->load->model("SatuanKerja");
		$satuan_kerja = new SatuanKerja();
		
		if($reqTipe == "1")
		{
			$arrStatement = array("COALESCE(NULLIF(KODE_PARENT, ''), '0')" => $id, "NOT SATUAN_KERJA_ID_PARENT" => "SATKER", "SATUAN_KERJA_ID_PARENT" => $satkerId, "STATUS_AKTIF" => '1');
		}
		else
			$arrStatement = array("COALESCE(NULLIF(KODE_PARENT, ''), '0')" => $id, "NOT SATUAN_KERJA_ID_PARENT" => "SATKER", "SATUAN_KERJA_ID_PARENT" => $satkerId);

		if($reqTipe == "2" && $reqMode == "jabatan_baru_cari")
			$statement .= " AND ( COALESCE(NULLIF(KELOMPOK_JABATAN, ''), '0') NOT IN ('0') AND (CHECK_ADA_PEJABAT = 0 OR COALESCE(NULLIF(NIP, ''), '0') IN ('0')) )";
			// $statement .= " AND CHECK_ADA_PEJABAT = 0";

		$rowCount = $satuan_kerja->getCountByParamsFix($arrStatement, $statement.$statement_privacy);
		$satuan_kerja->selectByParams($arrStatement, $rows, $offset, $statement.$statement_privacy, " ORDER BY KODE_SO ASC ");
		// echo $satuan_kerja->query;exit;
		$i = 0;
		$items = array();
		while($satuan_kerja->nextRow())
		{
			$this->TREETABLE_COUNT++;
			
			$row['id']				= coalesce($satuan_kerja->getField("KODE_SO"), $satuan_kerja->getField("SATUAN_KERJA_ID"));
			$row['parentId']		= $satuan_kerja->getField("KODE_PARENT");
			$row['text']			= $satuan_kerja->getField("NAMA");
			$row['SATUAN_KERJA_ID']	= $satuan_kerja->getField("SATUAN_KERJA_ID");
			$row['SATUAN_KERJA_ID_PARENT']	= $satuan_kerja->getField("SATUAN_KERJA_ID_PARENT");
			$row['NAMA']			= $satuan_kerja->getField("NAMA");
			$row['NAMA_PEGAWAI']	= $satuan_kerja->getField("NAMA_PEGAWAI");
			if($reqTipe == "1" || $reqMode == "jabatan_baru")
				$row['JABATAN']= $satuan_kerja->getField("JABATAN");
			else
				$row['JABATAN']= str_replace("Plh. ", "", str_replace("Plt. ", "", $satuan_kerja->getField("JABATAN")));

			$row['NIP']				= $satuan_kerja->getField("NIP");
			$row['KODE_SURAT']				= $satuan_kerja->getField("KODE_SURAT");
			$row['KELOMPOK_JABATAN']		= $satuan_kerja->getField("KELOMPOK_JABATAN");
			$row['STATUS_AKTIF']			= $satuan_kerja->getField("STATUS_AKTIF");
			$row['STATUS_AKTIF_DESC']		= $satuan_kerja->getField("STATUS_AKTIF_DESC");

			$chekadapejabat= $satuan_kerja->getField("CHECK_ADA_PEJABAT");
			$row['CHECK_ADA_PEJABAT']= $chekadapejabat;
			if($chekadapejabat == 1)
				$chekadapejabat= "";
			else
				$chekadapejabat= "1";
			$row['MUTASI_AKSI_PEJABAT_PENGGANTI']= $chekadapejabat;
			$row['MUTASI_NIP']= $satuan_kerja->getField("NIP");
			$row['MUTASI_NAMA_PEGAWAI']= $satuan_kerja->getField("NAMA_PEGAWAI");
			$row['MUTASI_NAMA']= $satuan_kerja->getField("NAMA");
			$row['MUTASI_JABATAN']= str_replace("Plh. ", "", str_replace("Plt. ", "", $satuan_kerja->getField("JABATAN")));

			$row['LINK_URL']		= $satuan_kerja->getField("LINK_URL");
			
			$state = $this->has_child($row['id']);
	
	
			$row['state'] 			= $state;
			if($state)
				$row['children'] 		= $this->children_master($satuan_kerja->getField("KODE_SO"), $satkerId, $reqTipe, $reqMode);
	
			$i++;
			array_push($items, $row);
			unset($row);
		}
		
		return $items;
	}

	function treetable()
	{
		$reqUnitKerjaId = $this->input->get("reqUnitKerjaId");
		$reqJabatanJenis = $this->input->get("reqJabatanJenis");

		if ($reqUnitKerjaId == "")
			$reqUnitKerjaId = $this->CABANG_ID;

		// echo $reqUnitKerjaId;exit;

		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : 50;
		$id   = isset($_POST['id']) ? intval($_POST['id']) : 0;
		$offset = ($page - 1) * $rows;

		$reqPencarian = trim($this->input->get("reqPencarian"));
		$reqMode = $this->input->get("reqMode");

		$this->load->model("SatuanKerja");
		$this->load->model("SatuanKerjaKelompok");

		$satuan_kerja = new SatuanKerja();

		if ($reqPencarian == "")
			$arrStatement = array("NOT SATUAN_KERJA_ID_PARENT" => "SATKER", "SATUAN_KERJA_ID_PARENT" => $reqUnitKerjaId, "STATUS_AKTIF" => '1');
		else {
			$arrStatement = array("NOT SATUAN_KERJA_ID_PARENT" => "SATKER", "SATUAN_KERJA_ID_PARENT" => $reqUnitKerjaId, "STATUS_AKTIF" => '1');
			$statement = " AND (UPPER(NAMA) LIKE '%" . strtoupper($reqPencarian) . "%' OR UPPER(JABATAN) LIKE '%" . strtoupper($reqPencarian) . "%' OR UPPER(NAMA_PEGAWAI) LIKE '%" . strtoupper($reqPencarian) . "%' OR UPPER(NIP) LIKE '%" . strtoupper($reqPencarian) . "%') ";
		}

		if($reqJabatanJenis == "baru" || $reqJabatanJenis == "kosong")
		{
			// $statement .= " AND COALESCE(NULLIF(KELOMPOK_JABATAN, ''), '0') NOT IN ('KARYAWAN')";
			if($reqJabatanJenis == "kosong")
			{
				// $statement .= " AND ( COALESCE(NULLIF(KELOMPOK_JABATAN, ''), '0') IN ('0') OR CHECK_ADA_PEJABAT = 0)";
				$statement .= " AND ( COALESCE(NULLIF(KELOMPOK_JABATAN, ''), '0') IN ('0') OR CHECK_ADA_PEJABAT = 0 OR COALESCE(NULLIF(NIP, ''), '0') IN ('0')) ";
				// $statement .= " AND ( CHECK_ADA_PEJABAT = 0 )";
				$rows= $offset= -1;
			}
			else
			{
				$statement .= " AND COALESCE(NULLIF(KELOMPOK_JABATAN, ''), '0') IN ('0')";
			}
		}

		if($reqMode == "jabatan_baru_cari" && empty($reqJabatanJenis))
		{	
			$statement .= " AND ( COALESCE(NULLIF(KELOMPOK_JABATAN, ''), '0') NOT IN ('0') AND (CHECK_ADA_PEJABAT = 0 OR COALESCE(NULLIF(NIP, ''), '0') IN ('0')) )";
			$rows= $offset= -1;
		}
			// $statement .= " AND CHECK_ADA_PEJABAT = 0";

		// $statement .= " AND SATUAN_KERJA_ID = 'PST4300000'";

		$rowCount = $satuan_kerja->getCountByParamsFix($arrStatement, $statement . $statement_privacy);
		$satuan_kerja->selectByParams($arrStatement, $rows, $offset, $statement . $statement_privacy, " ORDER BY KODE_SO ASC ");
		// echo $rows."--".$offset;exit;
		// echo $satuan_kerja->query."\n\n\n";
		// exit;
		$i = 0;
		$items = array();
		while ($satuan_kerja->nextRow()) {
			$this->TREETABLE_COUNT++;

			$row['id'] = coalesce($satuan_kerja->getField("KODE_SO"), $satuan_kerja->getField("SATUAN_KERJA_ID"));
			$row['parentId'] = $satuan_kerja->getField("KODE_PARENT");
			$row['text'] = $satuan_kerja->getField("NAMA");
			$row['SATUAN_KERJA_ID']	= $satuan_kerja->getField("SATUAN_KERJA_ID");
			$row['SATUAN_KERJA_ID_PARENT']	= $satuan_kerja->getField("SATUAN_KERJA_ID_PARENT");
			$row['NAMA'] = $satuan_kerja->getField("NAMA");
			$row['NAMA_PEGAWAI'] = $satuan_kerja->getField("NAMA_PEGAWAI");
			if($reqMode == "jabatan_baru")
				$row['JABATAN']= $satuan_kerja->getField("JABATAN");
			else
				$row['JABATAN']= str_replace("Plh. ", "", str_replace("Plt. ", "", $satuan_kerja->getField("JABATAN")));
			$row['NIP'] = $satuan_kerja->getField("NIP");
			$row['KODE_SURAT'] = $satuan_kerja->getField("KODE_SURAT");
			$row['KELOMPOK_JABATAN'] = $satuan_kerja->getField("KELOMPOK_JABATAN");

			$chekadapejabat= $satuan_kerja->getField("CHECK_ADA_PEJABAT");
			$row['CHECK_ADA_PEJABAT']= $chekadapejabat;
			if($chekadapejabat == 1)
				$chekadapejabat= "";
			else
				$chekadapejabat= "1";
			$row['MUTASI_AKSI_PEJABAT_PENGGANTI']= $chekadapejabat;
			$row['MUTASI_NIP']= $satuan_kerja->getField("NIP");
			$row['MUTASI_NAMA_PEGAWAI']= $satuan_kerja->getField("NAMA_PEGAWAI");
			$row['MUTASI_NAMA']= $satuan_kerja->getField("NAMA");
			$row['MUTASI_JABATAN']= str_replace("Plh. ", "", str_replace("Plt. ", "", $satuan_kerja->getField("JABATAN")));

			if (trim($reqPencarian) == "") {
				if($reqJabatanJenis !== "kosong")
				{
					$row['state'] = $this->has_child($row['id']);
					$row['children'] = $this->children($satuan_kerja->getField("KODE_SO"), $satuan_kerja->getField("SATUAN_KERJA_ID_PARENT"), $reqJabatanJenis, $reqMode);
				}
			}
			$i++;
			array_push($items, $row);
			unset($row);
		}

		$result["rows"] = $items;
		$result["total"] = $this->TREETABLE_COUNT;

		echo json_encode($result);
	}

	function children($id, $satkerId, $reqJabatanJenis, $reqMode)
	{
		$this->load->model("SatuanKerja");
		$satuan_kerja = new SatuanKerja();

		$arrStatement = array("COALESCE(NULLIF(KODE_PARENT, ''), '0')" => $id, "NOT SATUAN_KERJA_ID_PARENT" => "SATKER", "SATUAN_KERJA_ID_PARENT" => $satkerId, "STATUS_AKTIF" => '1');

		if($reqJabatanJenis == "baru" || $reqJabatanJenis == "kosong")
		{
			// $statement .= " AND COALESCE(NULLIF(KELOMPOK_JABATAN, ''), '0') NOT IN ('KARYAWAN')";
			if($reqJabatanJenis == "kosong")
			{
				// $statement .= " AND ( COALESCE(NULLIF(KELOMPOK_JABATAN, ''), '0') IN ('0') OR CHECK_ADA_PEJABAT = 0)";
				$statement .= " AND ( COALESCE(NULLIF(KELOMPOK_JABATAN, ''), '0') IN ('0') OR CHECK_ADA_PEJABAT = 0 OR COALESCE(NULLIF(NIP, ''), '0') IN ('0')) ";
				// $statement .= " AND ( CHECK_ADA_PEJABAT = 0 )";
			}
			else
			{
				$statement .= " AND COALESCE(NULLIF(KELOMPOK_JABATAN, ''), '0') IN ('0')";
			}
		}

		if($reqMode == "jabatan_baru_cari" && empty($reqJabatanJenis))
			$statement .= " AND ( COALESCE(NULLIF(KELOMPOK_JABATAN, ''), '0') NOT IN ('0') AND (CHECK_ADA_PEJABAT = 0 OR COALESCE(NULLIF(NIP, ''), '0') IN ('0')) )";
			// $statement .= " AND CHECK_ADA_PEJABAT = 0";

		// $statement .= " AND SATUAN_KERJA_ID = 'PST4300000'";
		$rowCount = $satuan_kerja->getCountByParamsFix($arrStatement, $statement . $statement_privacy);
		$satuan_kerja->selectByParams($arrStatement, $rows, $offset, $statement . $statement_privacy, " ORDER BY KODE_SO ASC ");
		// echo $satuan_kerja->query."\n\n\n";
		// exit;
		$i = 0;
		$items = array();
		while ($satuan_kerja->nextRow()) {
			$this->TREETABLE_COUNT++;

			$row['id']				= coalesce($satuan_kerja->getField("KODE_SO"), $satuan_kerja->getField("SATUAN_KERJA_ID"));
			$row['parentId']		= $satuan_kerja->getField("KODE_PARENT");
			$row['text']			= $satuan_kerja->getField("NAMA");
			$row['SATUAN_KERJA_ID']	= $satuan_kerja->getField("SATUAN_KERJA_ID");
			$row['SATUAN_KERJA_ID_PARENT']	= $satuan_kerja->getField("SATUAN_KERJA_ID_PARENT");
			$row['NAMA']			= $satuan_kerja->getField("NAMA");
			$row['NAMA_PEGAWAI']	= $satuan_kerja->getField("NAMA_PEGAWAI");
			if($reqMode == "jabatan_baru")
				$row['JABATAN']= $satuan_kerja->getField("JABATAN");
			else
				$row['JABATAN']= str_replace("Plh. ", "", str_replace("Plt. ", "", $satuan_kerja->getField("JABATAN")));
			$row['NIP']				= $satuan_kerja->getField("NIP");
			$row['KODE_SURAT']				= $satuan_kerja->getField("KODE_SURAT");
			$row['KELOMPOK_JABATAN']		= $satuan_kerja->getField("KELOMPOK_JABATAN");
			$chekadapejabat= $satuan_kerja->getField("CHECK_ADA_PEJABAT");
			$row['CHECK_ADA_PEJABAT']= $chekadapejabat;
			if($chekadapejabat == 1)
				$chekadapejabat= "";
			else
				$chekadapejabat= "1";
			$row['MUTASI_AKSI_PEJABAT_PENGGANTI']= $chekadapejabat;
			$row['MUTASI_NIP']= $satuan_kerja->getField("NIP");
			$row['MUTASI_NAMA_PEGAWAI']= $satuan_kerja->getField("NAMA_PEGAWAI");
			$row['MUTASI_NAMA']= $satuan_kerja->getField("NAMA");
			$row['MUTASI_JABATAN']= str_replace("Plh. ", "", str_replace("Plt. ", "", $satuan_kerja->getField("JABATAN")));

			$state = $this->has_child($row['id']);


			$row['state'] 			= $state;
			if ($state)
				$row['children'] 		= $this->children($satuan_kerja->getField("KODE_SO"), $satkerId, $reqJabatanJenis, $reqMode);

			$i++;
			array_push($items, $row);
			unset($row);
		}

		return $items;
	}

	function has_child($id)
	{
		$this->load->model("SatuanKerja");
		$satuan_kerja = new SatuanKerja();
		$adaData = $satuan_kerja->getCountByParams(array("COALESCE(NULLIF(KODE_PARENT, ''), '0')" => $id));
		return $adaData > 0 ? true : false;
	}

}