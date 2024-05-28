<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class surat_keluar_json extends CI_Controller {

	function __construct() {
		parent::__construct();
		
		if (!$this->kauth->getInstance()->hasIdentity())
		{
			redirect('login');
		}    
		
		$this->db->query("SET DATESTYLE TO PostgreSQL,European;"); 
		$this->ID				= $this->kauth->getInstance()->getIdentity()->ID;   
		$this->NAMA				= $this->kauth->getInstance()->getIdentity()->NAMA;   
		$this->JABATAN			= $this->kauth->getInstance()->getIdentity()->JABATAN;   
		$this->HAK_AKSES		= $this->kauth->getInstance()->getIdentity()->HAK_AKSES;   
		$this->LAST_LOGIN		= $this->kauth->getInstance()->getIdentity()->LAST_LOGIN;   
		$this->USERNAME			= $this->kauth->getInstance()->getIdentity()->ID;  
		$this->USER_LOGIN_ID	= $this->kauth->getInstance()->getIdentity()->USER_LOGIN_ID;  
		$this->USER_GROUP		= $this->kauth->getInstance()->getIdentity()->USER_GROUP;  
		$this->CABANG_ID		= $this->kauth->getInstance()->getIdentity()->CABANG_ID;  
		$this->CABANG			= $this->kauth->getInstance()->getIdentity()->CABANG;  
		$this->SATUAN_KERJA_ID_ASAL	= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_ID_ASAL;  
		$this->SATUAN_KERJA_ASAL	= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_ASAL;  
		
		
	}
	
	function json() 
	{
		$this->load->model("SuratKeluar");
		$surat_keluar = new SuratKeluar();

		$reqJenisTujuan = $this->input->get("reqJenisTujuan");
		// echo $reqKategori;exit;
		
		$aColumns = array("SURAT_KELUAR_ID", "STATUS_SURAT", "NOMOR", "NO_AGENDA", "TANGGAL_ENTRI", "TANGGAL", 
						  "JENIS_NASKAH", "PERIHAL", "SIFAT_NASKAH", 
						  "KEPADA", "TEMBUSAN", "INSTANSI_ASAL", "TERBACA", "TERDISPOSISI", "TERBALAS", "USER_ID");
		$aColumnsAlias = array("A.SURAT_KELUAR_ID", "A.STATUS_SURAT", "A.NOMOR", "A.NO_AGENDA", "A.TANGGAL_ENTRI", "A.TANGGAL", 
						  "JENIS_NASKAH_ID", "PERIHAL", "SIFAT_NASKAH", 
						  "PERIHAL", "PERIHAL", "INSTANSI_ASAL", "TERBALAS", "TERDISPOSISI", "TERBALAS", "USER_ID");

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
			if ( trim($sOrder) == "ORDER BY A.SURAT_KELUAR_ID asc" )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.TANGGAL_ENTRI DESC";
				 
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


		$statement_privacy .= " AND A.JENIS_TUJUAN = '".$reqJenisTujuan."' ";
		//$statement_privacy .= " AND A.SATUAN_KERJA_ID_ASAL = '".$this->SATUAN_KERJA_ID_ASAL."' ";
		
		$statement_privacy .= " AND A.USER_ID = '".$this->ID."' ";
		
		
		$statement= " AND (
						UPPER(A.NO_AGENDA) LIKE '%".strtoupper($_GET['sSearch'])."%' OR 
						UPPER(A.NOMOR) LIKE '%".strtoupper($_GET['sSearch'])."%' OR
						UPPER(A.PERIHAL) LIKE '%".strtoupper($_GET['sSearch'])."%' OR 
						UPPER(A.INSTANSI_ASAL) LIKE '%".strtoupper($_GET['sSearch'])."%' 
						) ";
						
		$allRecord = $surat_keluar->getCountByParamsMonitoring(array(), $statement_privacy.$statement);
		// echo $allRecord;exit;
		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter =  $surat_keluar->getCountByParamsMonitoring(array(), $statement_privacy.$statement);
		
		 $surat_keluar->selectByParamsMonitoring(array(), $dsplyRange, $dsplyStart, $statement_privacy.$statement, $sOrder);
		
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
		
		while($surat_keluar->nextRow())		
		{		
			$row = array();		
			for ( $i=0 ; $i<count($aColumns) ; $i++ )		
			{	
				if($aColumns[$i] == "KETERANGAN")
					$row[] = truncate($surat_keluar->getField($aColumns[$i]), 2);
				elseif($aColumns[$i] == "TERBACA" || $aColumns[$i] == "TERDISPOSISI" || $aColumns[$i] == "TERBALAS")
				{
					if((int)$surat_keluar->getField($aColumns[$i]) > 0)
						$row[] = "<i class=\"fa fa-check fa-md\" aria-hidden=\"true\"></i>";
					else
						$row[] = "<i class=\"fa fa-close fa-md\" aria-hidden=\"true\"></i>";
				}					
				else
					$row[] = $surat_keluar->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		echo json_encode( $output );	

		
	}
	
	
	
	function json_pemberitahuan() 
	{
		$this->load->model("SuratKeluar");
		$surat_keluar = new SuratKeluar();

		$reqJenisTujuan = $this->input->get("reqJenisTujuan");
		// echo $reqKategori;exit;
		
		$aColumns = array("SURAT_KELUAR_ID", "STATUS_SURAT", "NOMOR", "NO_AGENDA", "TANGGAL_ENTRI", "TANGGAL", 
						  "JENIS_NASKAH", "PERIHAL", "SIFAT_NASKAH", 
						  "KEPADA", "TEMBUSAN", "INSTANSI_ASAL", "USER_ID");
		$aColumnsAlias = array("A.SURAT_KELUAR_ID", "A.STATUS_SURAT", "A.NOMOR", "A.NO_AGENDA", "A.TANGGAL_ENTRI", "A.TANGGAL", 
						  "JENIS_NASKAH_ID", "PERIHAL", "SIFAT_NASKAH", 
						  "PERIHAL", "PERIHAL", "INSTANSI_ASAL", "USER_ID");

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
			if ( trim($sOrder) == "ORDER BY A.SURAT_KELUAR_ID asc" )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.TANGGAL_ENTRI DESC";
				 
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


		$statement_privacy .= " AND A.JENIS_TUJUAN = '".$reqJenisTujuan."' ";
		
		$statement_privacy .= " AND A.USER_ID = '".$this->ID."' ";
		//$statement_privacy .= " AND A.SATUAN_KERJA_ID_ASAL = '".$this->SATUAN_KERJA_ID_ASAL."' ";
		
		$statement= " AND (
						UPPER(A.NO_AGENDA) LIKE '%".strtoupper($_GET['sSearch'])."%' OR 
						UPPER(A.NOMOR) LIKE '%".strtoupper($_GET['sSearch'])."%' OR
						UPPER(A.PERIHAL) LIKE '%".strtoupper($_GET['sSearch'])."%' OR 
						UPPER(A.INSTANSI_ASAL) LIKE '%".strtoupper($_GET['sSearch'])."%' 
						) ";
						
		$allRecord = $surat_keluar->getCountByParamsMonitoring(array(), $statement_privacy.$statement);
		// echo $allRecord;exit;
		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter =  $surat_keluar->getCountByParamsMonitoring(array(), $statement_privacy.$statement);
		
		 $surat_keluar->selectByParamsMonitoring(array(), $dsplyRange, $dsplyStart, $statement_privacy.$statement, $sOrder);
		
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
		
		while($surat_keluar->nextRow())		
		{		
			$row = array();		
			for ( $i=0 ; $i<count($aColumns) ; $i++ )		
			{	
				if($aColumns[$i] == "KETERANGAN")
					$row[] = truncate($surat_keluar->getField($aColumns[$i]), 2);
				elseif($aColumns[$i] == "TERBACA" || $aColumns[$i] == "TERDISPOSISI" || $aColumns[$i] == "TERBALAS")
				{
					if((int)$surat_keluar->getField($aColumns[$i]) > 0)
						$row[] = "<i class=\"fa fa-check fa-md\" aria-hidden=\"true\"></i>";
					else
						$row[] = "<i class=\"fa fa-close fa-md\" aria-hidden=\"true\"></i>";
						
					
				}
				else
					$row[] = $surat_keluar->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		echo json_encode( $output );	

		
	}
	
	function add() 
	{
		
		$this->load->model("SuratKeluar");
		$this->load->model("DisposisiKeluar");
		$surat_keluar = new SuratKeluar();
		$disposisi = new DisposisiKeluar();

		$reqMode 					= $this->input->post("reqMode");
		$reqId 						= $this->input->post("reqId");
		$refDisposisiKeluarId 			= $this->input->post("refDisposisiKeluarId");
		
		if($refDisposisiKeluarId == "")
			$reqIdRef = "";
		else
		{
			$surat_keluar_ref = new SuratKeluar();
			$surat_keluar_ref->selectByParams(array(), -1, -1, " AND EXISTS(SELECT 1 FROM DISPOSISI X WHERE X.SURAT_KELUAR_ID = A.SURAT_KELUAR_ID AND MD5('BALAS' || X.DISPOSISI_ID) = '".$refDisposisiKeluarId."') ");
			$surat_keluar_ref->firstRow();
			$reqIdRef = $surat_keluar_ref->getField("SURAT_KELUAR_ID");
		}

		$reqJenisTujuan = $this->input->post("reqJenisTujuan");
		$reqJenisNaskah = $this->input->post("reqJenisNaskah");
		$reqNoAgenda 	= $this->input->post("reqNoAgenda");
		$reqNoSurat 	= $this->input->post("reqNoSurat");
		$reqTanggal 	= $this->input->post("reqTanggal");
		$reqPerihal	    = $this->input->post("reqPerihal");
		$reqKeterangan  = $_POST["reqKeterangan"];
		$reqSifatNaskah = $this->input->post("reqSifatNaskah");
		$reqStatusSurat = $this->input->post("reqStatusSurat");
		$reqAsalSuratKota 		=  $this->input->post("reqAsalSuratKota");
		$reqAsalSuratAlamat 	=  $this->input->post("reqAsalSuratAlamat");
		$reqAsalSuratInstansi	=  $this->input->post("reqAsalSuratInstansi");
		$reqLokasiSurat			=  $this->input->post("reqLokasiSurat");
		$reqSatuanKerjaIdTujuan		=  $this->input->post("reqSatuanKerjaIdTujuan");
		$reqSatuanKerjaIdTembusan	=  $this->input->post("reqSatuanKerjaIdTembusan");	
		$reqSatuanKerjaIdParaf   	=  $this->input->post("reqSatuanKerjaIdParaf");	
		$reqKlasifikasiId   	=  $this->input->post("reqKlasifikasiId");
		$reqPenyampaianSurat	=  $this->input->post("reqPenyampaianSurat");
		
		$reqTanggalKegiatan 	 =  $this->input->post("reqTanggalKegiatan");
		$reqTanggalKegiatanAkhir =  $this->input->post("reqTanggalKegiatanAkhir");
		$reqJamKegiatan          =  $this->input->post("reqJamKegiatan");
		$reqJamKegiatanAkhir     =  $this->input->post("reqJamKegiatanAkhir");
		$reqIsEmail       =  $this->input->post("reqIsEmail");
		$reqIsMeeting     =  $this->input->post("reqIsMeeting");
		$reqRevisi     	  =  $this->input->post("reqRevisi");
		$reqPrioritasSuratId     	  =  $this->input->post("reqPrioritasSuratId");
		$reqMediaPengirimanId     	  =  $this->input->post("reqMediaPengirimanId");
		
		if(count($reqSatuanKerjaIdTujuan) == 0)
		{
			echo "0-Tujuan surat belum ditentukan.";	
			return;
		}
		if(trim($reqPerihal) == "")
		{
			echo "0-Judul surat belum diisi.";	
			return;
		}
		
		$reqTanggalKeg = "NULL";
		$reqTanggalKegAkhir = "NULL";
		if($reqIsMeeting == "Y")
		{
			if($reqTanggalKegiatan == "")
			{
				$reqTanggalKeg = "NULL";
				$reqTanggalKegAkhir = "NULL";
			}
			else
			{
				if($reqJamKegiatan == "")
					$reqTanggalKeg = "TO_TIMESTAMP('".$reqTanggalKegiatan."', 'DD-MM-YYYY')";
				else
					$reqTanggalKeg = "TO_TIMESTAMP('".$reqTanggalKegiatan." ".$reqJamKegiatan."', 'DD-MM-YYYY HH24:MI')";
				
				if($reqTanggalKegiatanAkhir == "")
				{
					$reqTanggalKegAkhir = "NULL";
				}
				else
				{
					if($reqJamKegiatanAkhir == "")
						$reqTanggalKegAkhir = "TO_TIMESTAMP('".$reqTanggalKegiatanAkhir."', 'DD-MM-YYYY')";
					else
						$reqTanggalKegAkhir = "TO_TIMESTAMP('".$reqTanggalKegiatanAkhir." ".$reqJamKegiatanAkhir."', 'DD-MM-YYYY HH24:MI')";
				}
				
			}
		}
		
		$surat_keluar->setField("TANGGAL_KEGIATAN", $reqTanggalKeg);
		$surat_keluar->setField("TANGGAL_KEGIATAN_AKHIR", $reqTanggalKegAkhir);
		$surat_keluar->setField("IS_MEETING", $reqIsMeeting);
		$surat_keluar->setField("IS_EMAIL", $reqIsEmail);
		$surat_keluar->setField("PRIORITAS_SURAT_ID", $reqPrioritasSuratId);
		$surat_keluar->setField("MEDIA_PENGIRIMAN_ID", $reqMediaPengirimanId);

		$surat_keluar->setField("PENYAMPAIAN_SURAT", $reqPenyampaianSurat);
		$surat_keluar->setField("JENIS_TUJUAN", $reqJenisTujuan);
		$surat_keluar->setField("SURAT_KELUAR_REF_ID", $reqIdRef);
		$surat_keluar->setField("SURAT_KELUAR_ID", $reqId);
		$surat_keluar->setField("NO_AGENDA", $reqNoAgenda);
		$surat_keluar->setField("LOKASI_SIMPAN", $reqLokasiSurat);
		$surat_keluar->setField("NOMOR", $reqNoSurat);
		$surat_keluar->setField("TANGGAL", "CURRENT_DATE");//dateToDbCheck($reqTanggal));
		$surat_keluar->setField("JENIS_NASKAH_ID", $reqJenisNaskah);
		$surat_keluar->setField("SIFAT_NASKAH", $reqSifatNaskah); 
		$surat_keluar->setField("STATUS_SURAT", $reqStatusSurat);
		$surat_keluar->setField("PERIHAL", $reqPerihal);
		$surat_keluar->setField("KLASIFIKASI_ID", $reqKlasifikasiId);
		$surat_keluar->setField("SATUAN_KERJA_ID_ASAL", $this->SATUAN_KERJA_ID_ASAL);
		$surat_keluar->setField("INSTANSI_ASAL", $reqAsalSuratInstansi);
		$surat_keluar->setField("ALAMAT_ASAL", $reqAsalSuratAlamat);
		$surat_keluar->setField("KOTA_ASAL", $reqAsalSuratKota);
		$surat_keluar->setField("KETERANGAN_ASAL", "");
		$surat_keluar->setField("ISI", str_replace("'", "&quot;", $reqKeterangan));
		$surat_keluar->setField("CATATAN", "");
		$surat_keluar->setField("USER_ID", $this->ID);
		$surat_keluar->setField("NAMA_USER", $this->NAMA);
		$surat_keluar->setField("CABANG_ID", $this->CABANG_ID);
		
		
		$reqTanggalKegiatan 	 =  $this->input->post("reqTanggalKegiatan");
		$reqTanggalKegiatanAkhir =  $this->input->post("reqTanggalKegiatanAkhir");
		$reqJamKegiatan          =  $this->input->post("reqJamKegiatan");
		$reqJamKegiatanAkhir     =  $this->input->post("reqJamKegiatanAkhir");
		$reqIsEmail       =  $this->input->post("reqIsEmail");
		$reqIsMeeting     =  $this->input->post("reqIsMeeting");
		
		if($reqMode == "insert")
		{
			$surat_keluar->setField("LAST_CREATE_USER", $this->ID);
			$surat_keluar->insert();
			$reqId = $surat_keluar->id;
		}
		else
		{
			$surat_keluar->setField("LAST_UPDATE_USER", $this->ID);
			$surat_keluar->update();
		}	
		
		/* WAJIB UNTUK UPLOAD DATA */
		$this->load->library("FileHandler");
		$file = new FileHandler();
		$FILE_DIR= "uploads/eksternal/".$reqId."/";
		makedirs($FILE_DIR);
		
		$reqLinkFile = $_FILES["reqLinkFile"];
		$reqLinkFileTempSize	=  $this->input->post("reqLinkFileTempSize");
		$reqLinkFileTempTipe	=  $this->input->post("reqLinkFileTempTipe");
		$reqLinkFileTemp		=  $this->input->post("reqLinkFileTemp");
		$reqLinkFileTempNama	=  $this->input->post("reqLinkFileTempNama");


		$surat_keluar_attachement = new SuratKeluar();
		$surat_keluar_attachement->setField("SURAT_KELUAR_ID", $reqId);
		$surat_keluar_attachement->deleteAttachment();


		$reqJenis = $reqJenisTujuan.generateZero($reqId, 5);
		for($i=0;$i<count($reqLinkFile);$i++)
		{
			$renameFile = $reqJenis.date("Ymdhis").rand().".".getExtension($reqLinkFile['name'][$i]);
		
			if($file->uploadToDirArray('reqLinkFile', $FILE_DIR, $renameFile, $i))
			{	
				$insertLinkSize = $file->uploadedSize;
				$insertLinkTipe =  $file->uploadedExtension;
				$insertLinkFile =  $renameFile;
				
				if($insertLinkFile == "")
				{}
				else
				{
					$surat_keluar_attachement = new SuratKeluar();
					$surat_keluar_attachement->setField("SURAT_KELUAR_ID", $reqId);
					$surat_keluar_attachement->setField("ATTACHMENT", $renameFile);
					$surat_keluar_attachement->setField("UKURAN", $insertLinkSize);
					$surat_keluar_attachement->setField("TIPE", $insertLinkTipe);
					$surat_keluar_attachement->setField("NAMA", $reqLinkFile['name'][$i]);
					$surat_keluar_attachement->setField("LAST_CREATE_USER", $this->ID);
					$surat_keluar_attachement->insertAttachment();
				}
			}
			
		}

		for($i=0;$i<count($reqLinkFileTemp);$i++)
		{ 
			$insertLinkSize = $reqLinkFileTempSize[$i];
			$insertLinkTipe =  $reqLinkFileTempTipe[$i];
			$insertLinkFile =  $reqLinkFileTemp[$i];
			$insertLinkNama =  $reqLinkFileTempNama[$i];
			
			if($insertLinkFile == "")
			{}
			else
			{
				$surat_keluar_attachement = new SuratKeluar();
				$surat_keluar_attachement->setField("SURAT_KELUAR_ID", $reqId);
				$surat_keluar_attachement->setField("ATTACHMENT", $insertLinkFile);
				$surat_keluar_attachement->setField("UKURAN", $insertLinkSize);
				$surat_keluar_attachement->setField("TIPE", $insertLinkTipe);
				$surat_keluar_attachement->setField("NAMA", $insertLinkNama);
				$surat_keluar_attachement->setField("LAST_CREATE_USER", $this->ID);
				$surat_keluar_attachement->insertAttachment();
			}
			
		}



		$disposisi = new DisposisiKeluar();
		$disposisi->setField("SURAT_KELUAR_ID", $reqId);
		$disposisi->setField("LAST_CREATE_USER", $this->ID);
		$disposisi->deleteParent();
		for($i=0;$i<count($reqSatuanKerjaIdTujuan);$i++)
		{
			if($reqSatuanKerjaIdTujuan[$i] == "")
			{}
			else
			{
				$disposisi = new DisposisiKeluar();
				$disposisi->setField("SURAT_KELUAR_ID", $reqId);
				$disposisi->setField("SATUAN_KERJA_ID_ASAL", $this->SATUAN_KERJA_ID_ASAL);
				$disposisi->setField("SATUAN_KERJA_ID_TUJUAN", $reqSatuanKerjaIdTujuan[$i]);
				$disposisi->setField("STATUS_DISPOSISI_KELUAR", "TUJUAN");
				$disposisi->setField("LAST_CREATE_USER", $this->ID);
				$disposisi->insert();
			}
		}
		
		for($i=0;$i<count($reqSatuanKerjaIdTembusan);$i++)
		{
			if($reqSatuanKerjaIdTembusan[$i] == "")
			{}
			else
			{
				$disposisi = new DisposisiKeluar();
				$disposisi->setField("SURAT_KELUAR_ID", $reqId);
				$disposisi->setField("SATUAN_KERJA_ID_ASAL", $this->SATUAN_KERJA_ID_ASAL);
				$disposisi->setField("SATUAN_KERJA_ID_TUJUAN", $reqSatuanKerjaIdTembusan[$i]);
				$disposisi->setField("STATUS_DISPOSISI_KELUAR", "TEMBUSAN");
				$disposisi->setField("LAST_CREATE_USER", $this->ID);
				$disposisi->insert();
			}
		}
		
		$this->load->model("SuratKeluarParaf");
		$surat_keluar_paraf = new SuratKeluarParaf();
		$surat_keluar_paraf->setField("SURAT_KELUAR_ID", $reqId);
		$surat_keluar_paraf->setField("LAST_CREATE_USER", $this->ID);
		$surat_keluar_paraf->deleteParent();
		
		for($i=0;$i<count($reqSatuanKerjaIdParaf);$i++)
		{
			if($reqSatuanKerjaIdParaf[$i] == "")
			{}
			else
			{
				$surat_keluar_paraf = new SuratKeluarParaf();
				$surat_keluar_paraf->setField("SURAT_KELUAR_ID", $reqId);
				$surat_keluar_paraf->setField("SATUAN_KERJA_ID_TUJUAN", $reqSatuanKerjaIdParaf[$i]);
				$surat_keluar_paraf->setField("LAST_CREATE_USER", $this->ID);
				$surat_keluar_paraf->insert();
			}
		}
		
		if($reqStatusSurat == "DRAFT")
		{
			echo $reqId."-Naskah berhasil disimpan sebagai DRAFT.";
			return;
		}
		elseif($reqStatusSurat == "VALIDASI")
		{
			echo $reqId."-Naskah berhasil disimpan sebagai DRAFT.";
			return;
		}
		elseif($reqStatusSurat == "REVISI")
		{
			$surat_keluar->setField("SURAT_KELUAR_ID", $reqId);
			$surat_keluar->setField("REVISI", $reqRevisi);
			$surat_keluar->setField("SATUAN_KERJA_ID_ASAL", $this->SATUAN_KERJA_ID_ASAL);
			$surat_keluar->setField("REVISI_BY", $this->USERNAME);
			if($surat_keluar->revisi())
			{
				$this->revisi_notifikasi($reqId);	
				echo "Naskah telah dikembalikan ke pembuat surat.";	
				return;
			}
		}
		
		/* JIKA BUKAN DRAFT YANG HANDLE ADALAH POSTING_PROSES */
		$this->posting_proses($reqId);
	
	}
	
	function paraf()
	{
		$reqId	= $this->input->get('reqId');
		
		$this->load->model("SuratKeluar");
		$surat_keluar = new SuratKeluar();
		

		$kodeParaf = "PARAF".$this->ID.generateZero($reqId, 6).date("dmYHis");
		
				
		$surat_keluar->setField("SURAT_KELUAR_ID", $reqId);
		$surat_keluar->setField("KODE_PARAF", $kodeParaf);
		$surat_keluar->setField("USER_ID", $this->ID);
		$surat_keluar->setField("LAST_UPDATE_USER", $this->USERNAME);
		   
		
		if($surat_keluar->paraf())
		{
			
			/* GENERATE QRCODE */
			include_once("libraries/phpqrcode/qrlib.php");
	
			$FILE_DIR= "uploads/eksternal/".$reqId."/";
			makedirs($FILE_DIR);
			$filename = $FILE_DIR.$kodeParaf.'.png';
			$errorCorrectionLevel = 'L';   
			$matrixPointSize = 2;
			QRcode::png($kodeParaf, $filename, $errorCorrectionLevel, $matrixPointSize, 2);    
			/* END OF GENERATE QRCODE */
			
			$this->terparaf_notifikasi($reqId);	
			echo "Data berhasil diparaf. ";
			
			/* SETIAP POSTING HIT POSTING SUPAYA APABILA PARAF SUDAH KOMPLIT LANGSUNG TERPOSTING */
			$this->posting_proses($reqId);
			
		}
		
	}
	
	function revisi()
	{
		$reqId	= $this->input->post('reqId');
		$reqRevisi	= $this->input->post('reqRevisi');
		
		$this->load->model("SuratKeluar");
		$surat_keluar = new SuratKeluar();
		
		$surat_keluar->setField("SURAT_KELUAR_ID", $reqId);
		$surat_keluar->setField("REVISI", $reqRevisi);
		$surat_keluar->setField("SATUAN_KERJA_ID_ASAL", $this->SATUAN_KERJA_ID_ASAL);
		$surat_keluar->setField("REVISI_BY", $this->USERNAME);
		if($surat_keluar->revisi())
		{
			$this->revisi_notifikasi($reqId);	
			echo "Data berhasil dikembalikan.";
		}
		
	}


	function posting()
	{
		$reqId	= $this->input->get('reqId');
		$this->posting_proses($reqId);
	}
	

	function posting_proses($reqId)
	{
		/* POSTING PROSES SEBENARNYA YANG HANDLE ADALAH TRIGGER */
		$this->load->model("SuratKeluar");
		$surat_keluar = new SuratKeluar();
		$surat_keluar->setField("SURAT_KELUAR_ID", $reqId);
		$surat_keluar->setField("SATUAN_KERJA_ID_ASAL", $this->SATUAN_KERJA_ID_ASAL);
		$surat_keluar->setField("PEMARAF_ID", $this->ID);
		$surat_keluar->setField("FIELD", "STATUS_SURAT");
		$surat_keluar->setField("FIELD_VALUE", "POSTING"); // apabila yang bikin staff nya sama trigger sudah otomatis diganti VALIDASI
		$surat_keluar->setField("LAST_UPDATE_USER", $this->USERNAME);
		if($surat_keluar->updateByFieldValidasi())
		{
			$statusSurat = $surat_keluar->getStatusSurat(array("A.SURAT_KELUAR_ID" => $reqId));
			if($statusSurat == "VALIDASI")
			{
				$this->validasi_notifikasi($reqId);	
				echo "draft-Naskah berhasil diposting ke atasan untuk validasi.";
			}
			elseif($statusSurat == "PARAF")
			{
				$this->paraf_notifikasi($reqId);	
				echo "draft-Naskah berhasil diposting ke pemaraf sebelum diposting ke tujuan.";
			}
			else
			{
				
				/* SISIPKAN GENERATE QRCODE PENANDA TANGAN ASLI APABILA POSTING */
				$kodeParaf  = $surat_keluar->getTtdSurat(array("A.SURAT_KELUAR_ID" => $reqId));
		
				include_once("libraries/phpqrcode/qrlib.php");
		
				$FILE_DIR= "uploads/eksternal/".$reqId."/";
				makedirs($FILE_DIR);
				$filename = $FILE_DIR.$kodeParaf.'.png';
				$errorCorrectionLevel = 'L';   
				$matrixPointSize = 5;
				QRcode::png($kodeParaf, $filename, $errorCorrectionLevel, $matrixPointSize, 2);    
				/* END OF GENERATE QRCODE */		
				
				
				$this->posting_notifikasi($reqId);	
				echo "sent-Naskah berhasil diposting.";
			}
		}
		
	}
	
	
	function paraf_notifikasi($reqId) 
	{
		
		$this->load->model("SuratKeluar");
		$surat_keluar = new SuratKeluar();
		$surat_keluar->selectByParamsMonitoring(array("A.SURAT_KELUAR_ID" => $reqId));
		$surat_keluar->firstRow();
		$reqTitle = $surat_keluar->getField("NOMOR");
		$reqBody  = $surat_keluar->getField("PERIHAL");
		/* SEND PUSH NOTIF */
		$this->load->library("PushNotification"); 
		$this->load->model("UserLoginMobile");

		$user_login_mobile = new UserLoginMobile();
		$user_login_mobile->selectByParams(array("A.STATUS" => "1"), -1, -1, " AND EXISTS(SELECT 1 FROM SURAT_KELUAR_PARAF X WHERE X.USER_ID = A.PEGAWAI_ID AND 
																														  X.SURAT_KELUAR_ID = '".$reqId."' 
																						  ) ");
		while($user_login_mobile->nextRow())
		{
			$row = array();
			$row['to'] = $user_login_mobile->getField("TOKEN_FIREBASE");
			$row['data']["title"] = "[PARAF]".$reqTitle;
			$row['data']["body"]  = $reqBody;
			$row['data']["tipe"]  = "INTERNAL"; // INFORMASI / CHAT
			$pushData = $row;
			$pushNotification = new PushNotification();
			$pushNotification->send_notification_v2($pushData);
			unset($row);
		}	
		
	}	
	
	function revisi_notifikasi($reqId) 
	{
		
		$this->load->model("SuratKeluar");
		$surat_keluar = new SuratKeluar();
		$surat_keluar->selectByParamsMonitoring(array("A.SURAT_KELUAR_ID" => $reqId));
		$surat_keluar->firstRow();
		$reqTitle = $surat_keluar->getField("NOMOR");
		$reqBody  = $surat_keluar->getField("PERIHAL");
		/* SEND PUSH NOTIF */
		$this->load->library("PushNotification"); 
		$this->load->model("UserLoginMobile");

		$user_login_mobile = new UserLoginMobile();
		$user_login_mobile->selectByParams(array("A.STATUS" => "1"), -1, -1, " AND EXISTS(SELECT 1 FROM SURAT_KELUAR X WHERE X.USER_ID = A.PEGAWAI_ID AND 
																														  X.SURAT_KELUAR_ID = '".$reqId."' AND 
																														  X.STATUS_SURAT IN ('REVISI') 
																						  ) ");
		while($user_login_mobile->nextRow())
		{
			$row = array();
			$row['to'] = $user_login_mobile->getField("TOKEN_FIREBASE");
			$row['data']["title"] = "[DRAFT]".$reqTitle;
			$row['data']["body"]  = $reqBody;
			$row['data']["tipe"]  = "INTERNAL"; // INFORMASI / CHAT
			$pushData = $row;
			$pushNotification = new PushNotification();
			$pushNotification->send_notification_v2($pushData);
			unset($row);
		}	
		
	}	
	
	function terparaf_notifikasi($reqId) 
	{
		
		$this->load->model("SuratKeluar");
		$surat_keluar = new SuratKeluar();
		$surat_keluar->selectByParamsMonitoring(array("A.SURAT_KELUAR_ID" => $reqId));
		$surat_keluar->firstRow();
		$reqTitle = $surat_keluar->getField("NOMOR");
		$reqBody  = $surat_keluar->getField("PERIHAL");
		/* SEND PUSH NOTIF */
		$this->load->library("PushNotification"); 
		$this->load->model("UserLoginMobile");

		$user_login_mobile = new UserLoginMobile();
		$user_login_mobile->selectByParams(array("A.STATUS" => "1"), -1, -1, " AND EXISTS(SELECT 1 FROM SURAT_KELUAR X WHERE X.USER_ATASAN_ID = A.PEGAWAI_ID AND 
																														  X.SURAT_KELUAR_ID = '".$reqId."' 
																						  ) ");
		while($user_login_mobile->nextRow())
		{
			$row = array();
			$row['to'] = $user_login_mobile->getField("TOKEN_FIREBASE");
			$row['data']["title"] = "[TERPARAF]".$reqTitle;
			$row['data']["body"]  = $reqBody;
			$row['data']["tipe"]  = "INTERNAL"; // INFORMASI / CHAT
			$pushData = $row;
			$pushNotification = new PushNotification();
			$pushNotification->send_notification_v2($pushData);
			unset($row);
		}	
		
	}	
	
	function validasi_notifikasi($reqId) 
	{
		
		$this->load->model("SuratKeluar");
		$surat_keluar = new SuratKeluar();
		$surat_keluar->selectByParamsMonitoring(array("A.SURAT_KELUAR_ID" => $reqId));
		$surat_keluar->firstRow();
		$reqTitle = $surat_keluar->getField("NOMOR");
		$reqBody  = $surat_keluar->getField("PERIHAL");
		/* SEND PUSH NOTIF */
		$this->load->library("PushNotification"); 
		$this->load->model("UserLoginMobile");

		$user_login_mobile = new UserLoginMobile();
		$user_login_mobile->selectByParams(array("A.STATUS" => "1"), -1, -1, " AND EXISTS(SELECT 1 FROM SURAT_KELUAR X WHERE X.USER_ATASAN_ID = A.PEGAWAI_ID AND 
																														  X.SURAT_KELUAR_ID = '".$reqId."' AND 
																														  X.STATUS_SURAT IN ('VALIDASI') 
																						  ) ");
		while($user_login_mobile->nextRow())
		{
			$row = array();
			$row['to'] = $user_login_mobile->getField("TOKEN_FIREBASE");
			$row['data']["title"] = "[DRAFT]".$reqTitle;
			$row['data']["body"]  = $reqBody;
			$row['data']["tipe"]  = "INTERNAL"; // INFORMASI / CHAT
			$pushData = $row;
			$pushNotification = new PushNotification();
			$pushNotification->send_notification_v2($pushData);
			unset($row);
		}	
		
	}	
	
	function posting_notifikasi($reqId) 
	{
		
		$this->load->model("SuratKeluar");
		$surat_keluar = new SuratKeluar();
		$surat_keluar->selectByParamsMonitoring(array("A.SURAT_KELUAR_ID" => $reqId));
		$surat_keluar->firstRow();
		$reqTitle = $surat_keluar->getField("NOMOR");
		$reqBody  = $surat_keluar->getField("PERIHAL");
		
		
		/* SEND PUSH NOTIF */
		$this->load->library("PushNotification"); 
		$this->load->model("UserLoginMobile");

		$user_login_mobile = new UserLoginMobile();
		$user_login_mobile->selectByParams(array("A.STATUS" => "1"), -1, -1, " AND EXISTS(SELECT 1 FROM DISPOSISI_KELUAR X WHERE X.USER_ID = A.PEGAWAI_ID AND 
																														  X.SURAT_KELUAR_ID = '".$reqId."' AND 
																														  X.STATUS_DISPOSISI_KELUAR IN ('TUJUAN', 'TEMBUSAN') 
																						  ) ");
		while($user_login_mobile->nextRow())
		{
			$row = array();
			$row['to'] = $user_login_mobile->getField("TOKEN_FIREBASE");
			$row['data']["title"] = $reqTitle;
			$row['data']["body"]  = $reqBody;
			$row['data']["tipe"]  = "INTERNAL"; // INFORMASI / CHAT
			$pushData = $row;
			$pushNotification = new PushNotification();
			$pushNotification->send_notification_v2($pushData);
			unset($row);
		}	
		
	}	
	
	function delete() 
	{
		$reqId	= $this->input->get('reqId');
		$this->load->model("SuratKeluar");
		$surat_keluar = new SuratKeluar();

		
		$surat_keluar->setField("SURAT_KELUAR_ID", $reqId);
		$surat_keluar->setField("LAST_CREATE_USER", $this->ID);
		if($surat_keluar->delete())
			$arrJson["PESAN"] = "Data berhasil dihapus.";
		else
			$arrJson["PESAN"] = "Data gagal dihapus.";		
		
		echo json_encode($arrJson);
	}	
	
	function combo() 
	{
		$this->load->model("SuratKeluar");
		$surat_keluar = new SuratKeluar();

		$surat_keluar->selectByParams(array());
		$i = 0;
		while($surat_keluar->nextRow())
		{
			$arr_json[$i]['id']		= $surat_keluar->getField("SURAT_KELUAR_ID");
			$arr_json[$i]['text']	= $surat_keluar->getField("NAMA");
			$i++;
		}
		
		echo json_encode($arr_json);
	}
	
	function get_no_surat()
	{
		$reqSatkerId	  = $this->SATUAN_KERJA_ID_ASAL;
		$reqJenisNaskahId = $this->input->post("reqJenisNaskahId");
		$reqJenisTujuan	  = $this->input->post("reqJenisTujuan");
		$reqTanggal 	  = $this->input->post("reqTanggal");

		$this->load->model("SuratKeluar");
		$surat_keluar = new SuratKeluar();

		echo $surat_keluar->getNoSurat($reqSatkerId, $reqJenisNaskahId, $reqJenisTujuan, $reqTanggal);
			
	}
	
}

