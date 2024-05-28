<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/class-list-util.php");
include_once("functions/class-list-util-serverside.php");

class surat_masuk_json extends CI_Controller
{
	var $calendarId = 'cohu8p4q74iks6dpilk7mrpvi4@group.calendar.google.com';

	function __construct()
	{
		parent::__construct();

		$reqToken = $this->input->get("reqToken");
		if ($reqToken == "") {
			$reqToken = $this->input->post("reqToken");
		}

		if (!empty($reqToken)) {
			$this->load->model('UserLoginMobile');
			$user_login_mobile = new UserLoginMobile();
			$reqPegawaiId = $user_login_mobile->getTokenPegawaiId(array("TOKEN" => $reqToken, "STATUS" => '1'));

			if ($reqPegawaiId == "0") {
				$arrReturn = array('status' => 'fail', 'message' => 'Sesi anda telah berakhir', 'code' => 502);
				echo json_encode($arrReturn);
				return;
			}

			$this->kauth->mobileVerification($reqPegawaiId, $reqToken);
			$this->CALLER = "MOBILE";
		}

		if (!$this->kauth->getInstance()->hasIdentity()) {
			redirect('login');
		}

		$this->db->query("SET DATESTYLE TO PostgreSQL,European;");
		$this->ID					= $this->kauth->getInstance()->getIdentity()->ID;
		$this->NAMA					= $this->kauth->getInstance()->getIdentity()->NAMA;
		$this->JABATAN				= $this->kauth->getInstance()->getIdentity()->JABATAN;
		$this->HAK_AKSES			= $this->kauth->getInstance()->getIdentity()->HAK_AKSES;
		$this->LAST_LOGIN			= $this->kauth->getInstance()->getIdentity()->LAST_LOGIN;
		$this->USERNAME				= $this->kauth->getInstance()->getIdentity()->USERNAME;
		$this->USER_LOGIN_ID		= $this->kauth->getInstance()->getIdentity()->USER_LOGIN_ID;
		$this->USER_GROUP			= $this->kauth->getInstance()->getIdentity()->USER_GROUP;
		$this->MULTIROLE			= $this->kauth->getInstance()->getIdentity()->MULTIROLE;
		$this->CABANG_ID			= $this->kauth->getInstance()->getIdentity()->CABANG_ID;
		$this->CABANG				= $this->kauth->getInstance()->getIdentity()->CABANG;
		$this->SATUAN_KERJA_ID_ASAL	= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_ID_ASAL;
		$this->SATUAN_KERJA_ASAL	= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_ASAL;
		$this->SATUAN_KERJA_HIRARKI	= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_HIRARKI;
		$this->SATUAN_KERJA_JABATAN	= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_JABATAN;
		$this->KD_LEVEL				= $this->kauth->getInstance()->getIdentity()->KD_LEVEL;
		$this->KD_LEVEL_PEJABAT 	= $this->kauth->getInstance()->getIdentity()->KD_LEVEL_PEJABAT;
		$this->JENIS_KELAMIN 		= $this->kauth->getInstance()->getIdentity()->JENIS_KELAMIN;
		$this->KELOMPOK_JABATAN 	= $this->kauth->getInstance()->getIdentity()->KELOMPOK_JABATAN;
		$this->KODE_PARENT= $this->kauth->getInstance()->getIdentity()->KODE_PARENT;
		$this->ID_ATASAN 			= $this->kauth->getInstance()->getIdentity()->ID_ATASAN;
		$this->DEPARTEMEN_PARENT_ID 			= $this->kauth->getInstance()->getIdentity()->DEPARTEMEN_PARENT_ID;

		$this->NIP_BY_DIVISI 			= $this->kauth->getInstance()->getIdentity()->NIP_BY_DIVISI;
		$this->KELOMPOK_JABATAN_BY_DIVISI 			= $this->kauth->getInstance()->getIdentity()->KELOMPOK_JABATAN_BY_DIVISI;

		$this->SATUAN_KERJA_ID_ASAL_ASLI= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_ID_ASAL_ASLI;
		$this->USER_BANTU= $this->kauth->getInstance()->getIdentity()->USER_BANTU;
	}

	function jsonsuratmasuk()
	{
		$this->load->model("SuratMasuk");
		$this->load->model("Disposisi");

		$reqJenisNaskahId= $this->input->get("reqJenisNaskahId");
		$reqTahun= $this->input->get("reqTahun");
		$reqPilihan= $this->input->get("reqPilihan");
		$reqStatusSurat= $this->input->get("reqStatusSurat");
		$cekquery= $this->input->get("c");

		$checkdisposisi= new Disposisi();
		$checkdisposisitujuan= new Disposisi();
		$surat_masuk= new SuratMasuk();

		if ( isset( $_REQUEST['columnsDef'] ) && is_array( $_REQUEST['columnsDef'] ) ) {
			$columnsDefault = [];
			foreach ( $_REQUEST['columnsDef'] as $field ) {
				$columnsDefault[ $field ] = "true";
			}
		}
		// print_r($columnsDefault);exit;

		$displaystart= -1;
		$displaylength= -1;

		$arrinfodata= [];

		$reqPencarian= $this->input->get("reqPencarian");
		$searchJson= "";
		$infosearch= $_REQUEST['search']["value"];

		/*if(!empty($infosearch))
		{
			$reqPencarian= $infosearch;
		}*/

		if(!empty($reqPencarian))
		{
			$searchJson= " 
			AND 
			(
				UPPER(A.NO_AGENDA) LIKE '%".strtoupper($reqPencarian)."%' OR 
				UPPER(A.NOMOR) LIKE '%".strtoupper($reqPencarian)."%' OR
				UPPER(CASE 
				WHEN B.STATUS_DISPOSISI = 'DISPOSISI' THEN '[DISPOSISI] ' 
				WHEN B.STATUS_DISPOSISI = 'TEMBUSAN' THEN '[TEMBUSAN] ' 
				WHEN B.STATUS_DISPOSISI = 'DISPOSISI_TEMBUSAN' THEN '[TEMBUSAN DISPOSISI] ' 
				WHEN B.STATUS_DISPOSISI = 'TERUSAN' THEN '[FWD] ' 
				WHEN B.STATUS_DISPOSISI = 'BALASAN' THEN '[RE] ' 
				ELSE '' END || A.PERIHAL) LIKE '%".strtoupper($reqPencarian)."%' OR 
				UPPER(A.INSTANSI_ASAL) LIKE '%".strtoupper($reqPencarian)."%' OR 
				UPPER(A.DARI_INFO) LIKE '%".strtoupper($reqPencarian)."%' OR 
				UPPER(A.USER_ATASAN) LIKE '%".strtoupper($reqPencarian)."%' OR 
				UPPER(USER_ATASAN_JABATAN) LIKE '%".strtoupper($reqPencarian)."%' OR 
				UPPER(A.NOMOR_SURAT_INFO) LIKE '%".strtoupper($reqPencarian)."%'  
			)";
		}

		// untuk buat session
		session_start();
		$arrsession= [];
		$_SESSION['kotak_masuk'.$reqJenisNaskahId]= $arrsession;
		$arrsession["reqTahun"]= $reqTahun;
		$arrsession["reqPilihan"]= $reqPilihan;
		$arrsession["reqStatusSurat"]= $reqStatusSurat;
		if(!empty($infosearch))
			$arrsession["reqPencarian"]= $infosearch;
		else
			$arrsession["reqPencarian"]= $reqPencarian;
		$_SESSION['kotak_masuk'.$reqJenisNaskahId]= $arrsession;

		if ($reqPilihan=='divisi') 
		{
			$reqNIPPP= $this->NIP_BY_DIVISI;
			$reqNIPPP= "'".str_replace("'", "''", $reqNIPPP)."'";
			$reqKelompokJabatann= $this->KELOMPOK_JABATAN_BY_DIVISI;
			$reqKelompokJabatann= "'".str_replace("'", "''", $reqKelompokJabatann)."'";
			$reqsatuankerjadivisi= "";
		} 
		else 
		{
			$reqNIPPP= "'".$this->ID."'";
			$reqKelompokJabatann= "'".$this->KELOMPOK_JABATAN."'";
			$reqsatuankerjadivisi= $this->SATUAN_KERJA_ID_ASAL_ASLI;
		}

		$infogantijabatan= "";
		if(in_array("SURAT", explode(",", $this->USER_GROUP)))
		{
			$statement= " 
			AND A.TTD_KODE IS NOT NULL
			";
			/*AND EXISTS
			(
				SELECT 1
				FROM
				(
					SELECT X.SURAT_MASUK_ID, MAX(DISPOSISI_ID) DISPOSISI_ID
					FROM DISPOSISI X
					WHERE X.SATUAN_KERJA_ID_TUJUAN LIKE '".$this->CABANG_ID."%'
					AND X.DISPOSISI_PARENT_ID = 0
					GROUP BY X.SURAT_MASUK_ID
				) X WHERE X.SURAT_MASUK_ID = B.SURAT_MASUK_ID AND X.DISPOSISI_ID = B.DISPOSISI_ID
			)*/
		}
		else
		{
			$statement= " 
			AND B.DISPOSISI_PARENT_ID = 0
			AND 
			(
				A.STATUS_SURAT = 'POSTING' OR
				A.STATUS_SURAT = 'TU-NOMOR' OR
				(
					A.STATUS_SURAT = 'TU-IN' AND
					EXISTS(SELECT 1 FROM SURAT_MASUK_ARSIP X WHERE X.SURAT_MASUK_ID = A.SURAT_MASUK_ID AND X.CABANG_ID = '".$this->CABANG_ID."')
				)
			)";

			if($this->SATUAN_KERJA_ID_ASAL_ASLI == $this->SATUAN_KERJA_ID_ASAL)
			{
				if(!empty($this->USER_BANTU))
				{
					$statement.= " AND B.SATUAN_KERJA_ID_TUJUAN = '".$this->SATUAN_KERJA_ID_ASAL."'";
				}
			}
			else
			{
				$infogantijabatan= "1";
				// $statement.= " AND B.SATUAN_KERJA_ID_TUJUAN = '".$this->SATUAN_KERJA_ID_ASAL."'";

				// sebelum code
				/*$statement.= "
				AND
				(
					B.SATUAN_KERJA_ID_TUJUAN = '".$this->SATUAN_KERJA_ID_ASAL."'
					OR
					B.SATUAN_KERJA_ID_TUJUAN = '".$this->CABANG_ID."'
				)
				";*/
				// echo $reqKelompokJabatann;exit;

				if(!empty($this->USER_BANTU))
				{
					$statement.= " AND B.STATUS_BANTU = 1 AND A.STATUS_SURAT IN ('POSTING')";
				}

				$statement.= "
				AND
				(
					B.SATUAN_KERJA_ID_TUJUAN = '".$this->SATUAN_KERJA_ID_ASAL."'
					OR
					(
						B.SATUAN_KERJA_ID_TUJUAN = '".$this->CABANG_ID."'
						and 
						exists
						(
							select 1
							from
							(
								select a.disposisi_kelompok_id, a1.kelompok_jabatan
								from disposisi_kelompok a
								inner join
								(
									select satuan_kerja_kelompok_id, kelompok_jabatan
									from satuan_kerja_kelompok_group
								) a1 on a.satuan_kerja_kelompok_id = a1.satuan_kerja_kelompok_id
							) x where b.disposisi_kelompok_id = x.disposisi_kelompok_id and x.kelompok_jabatan in (".$reqKelompokJabatann.")
						)
					)
				)
				";
			}
		}

		if(!empty($reqJenisNaskahId))
		{
			$statement.= " AND A.JENIS_NASKAH_ID IN (".$reqJenisNaskahId.")";
		}

		if(!empty($reqTahun))
		{
			$statement.= " AND A.TAHUN = ".$reqTahun;
		}

		if($reqStatusSurat == 1)
		{
			$statement.= " AND B.TERBACA_INFO LIKE '%".$this->ID."%'";
		}
		else if ($reqStatusSurat == 2)
		{
			$statement.= " AND COALESCE(B.TERBACA_INFO, '')  NOT ILIKE '%".$this->ID."%'";
		}
		
		if($infogantijabatan == "1")
		{
			$sOrder = " ORDER BY  
				CASE WHEN B.STATUS_DISPOSISI = 'DISPOSISI' THEN B.TANGGAL_DISPOSISI  
					WHEN B.STATUS_DISPOSISI = 'DISPOSISI_TEMBUSAN' THEN B.TANGGAL_DISPOSISI 
					ELSE TANGGAL_ENTRI END DESC";
			$surat_masuk->selectByParamsSuratMasuk(array(), $dsplyRange, $dsplyStart, $statement.$searchJson, $sOrder);
		}
		else
		{
			$sOrder = " ORDER BY  
				CASE WHEN B.STATUS_DISPOSISI = 'DISPOSISI' THEN B.TANGGAL_DISPOSISI  
					WHEN B.STATUS_DISPOSISI = 'DISPOSISI_TEMBUSAN' THEN B.TANGGAL_DISPOSISI 
					ELSE TANGGAL_ENTRI END DESC";
			$surat_masuk->selectByParamsNewSuratMasuk(array(), $dsplyRange, $dsplyStart, $reqNIPPP, $this->CABANG_ID, $reqKelompokJabatann, $statement.$searchJson, $sOrder, $reqsatuankerjadivisi);
		}

		if(!empty($cekquery))
		{
			echo $surat_masuk->query;exit;
		}
		
		$infobatasdetil= $_REQUEST['start'] + $_REQUEST['length'];
		$infonomor= 0;
		while ($surat_masuk->nextRow()) 
		{
			$infonomor++;
			$infojenisnaskahid= $surat_masuk->getField("JENIS_NASKAH_ID");
			$infosuratmasukid= $surat_masuk->getField("SURAT_MASUK_ID");

			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{
				if ($valkey == "SORDERDEFAULT")
				{
					// $row[$valkey]= "1";
					$row[$valkey]= $surat_masuk->getField("TANGGAL_DISPOSISI");
				}
				
				else if($valkey == "NOMOR")
				{
					// if($infojenisnaskahid == "1")
					// 	$row[$valkey] = $surat_masuk->getField("NOMOR_SURAT_INFO");
					// else
						// $row[$valkey] = $surat_masuk->getField($valkey)."-".$infonomor."-".$infosuratmasukid;
						$row[$valkey] = $surat_masuk->getField($valkey);
				}
				else if ($valkey == "INFO_TERBACA")
				{
					$infoterbaca= "";
					// if(in_array("SURAT", explode(",", $this->USER_GROUP)))
					// {
					// 	$infoterbaca= "1";
					// }
					// else
					// {
						$infodisposisiuserid= $this->ID;
						$infodisposisiterbacainfo= $surat_masuk->getField("TERBACA_INFO");

						if(!empty($infodisposisiterbacainfo))
						{
							$arrcheckterbaca= explode(";", $infodisposisiterbacainfo);
					        if(!empty($arrcheckterbaca) && !empty($infodisposisiterbacainfo))
					        {
					            while (list($key, $val) = each($arrcheckterbaca))
					            {
					                $arrcheckterbacadetil= explode(",", $val);
					                if($infodisposisiuserid == $arrcheckterbacadetil[0])
					                {
					                    $infoterbaca= "1";
					                    break;
					                }
					            }
					        }
						}
				    // }
					$row[$valkey] = $infoterbaca;
				}
				else if($valkey == "INFO_DARI")
				{
					if($infojenisnaskahid == "1")
						$row[$valkey] = $surat_masuk->getField("DARI_INFO");
					else
						$row[$valkey] = $surat_masuk->getField("USER_ATASAN")."<br/>".$surat_masuk->getField("USER_ATASAN_JABATAN");
				}
				else if($valkey == "TANGGAL_DISPOSISI")
				{
					$inforeturn= "";
					if($infonomor <= $infobatasdetil && $infonomor >= $_REQUEST['start'])
					{
						//tambahan cek icon disposisi dan teruskan 15022021
						$infoicondisposisi= "";
						$infoiconteruskan= "";
						$infoteruskan= "";
						$checkdisposisitujuan->selectByParams(array("A.DISPOSISI_ID"=>$surat_masuk->getField("DISPOSISI_ID")), -1, -1);
						$checkdisposisitujuan->firstRow();
						// echo $checkdisposisitujuan->query;exit;
						$infosatuankerjaidtujuan= $checkdisposisitujuan->getField("SATUAN_KERJA_ID_TUJUAN");
						$infostatusbantu= $checkdisposisitujuan->getField("STATUS_BANTU");

						$statementcheck= " AND A.STATUS_BANTU IS NULL AND A.SURAT_MASUK_ID = ".$surat_masuk->getField("SURAT_MASUK_ID")." AND A.SATUAN_KERJA_ID_TUJUAN = '".$infosatuankerjaidtujuan."'";
						$checkdisposisi->selectByParams(array(), -1,-1, $statementcheck);
						$checkdisposisi->firstRow();
						// echo $infostatusbantu;exit;
						$checkdisposisiid= $checkdisposisi->getField("DISPOSISI_ID");

						if ($infostatusbantu == "1" && !empty($checkdisposisiid))
						{
							$infoteruskan = "0";
						}

						$setcheck= new SuratMasuk();
						$jumlahcheck= $setcheck->cekjumlahkelompok(array("A.SURAT_MASUK_ID"=>$surat_masuk->getField("SURAT_MASUK_ID")));
						if($jumlahcheck > 0)
						{
							$statementcheck= " AND A.STATUS_BANTU IS NULL AND A.SURAT_MASUK_ID = ".$surat_masuk->getField("SURAT_MASUK_ID")." AND A.SATUAN_KERJA_ID_TUJUAN IN (SELECT SATUAN_KERJA_ID FROM SATUAN_KERJA WHERE USER_BANTU = '".$this->ID."')";
							$checkdisposisi->selectByParams(array(), -1,-1, $statementcheck);
							$checkdisposisi->firstRow();
							// echo $infostatusbantu;exit;
							$checkdisposisiid= $checkdisposisi->getField("DISPOSISI_ID");
							if(!empty($checkdisposisiid))
							{
								$infoteruskan = "0";
							}
						}

						if($surat_masuk->getField("TERDISPOSISI") == "1")
						{
							$infoicondisposisi= "<i class='fa fa-share-alt' aria-hidden='true'></i> ";
							
						}
						// echo $infoteruskan;
						if ($infoteruskan == "0" )
						{
							$infoiconteruskan= "<i class='fa fa-share' aria-hidden='true'></i>";
						}

						$inforeturn= getFormattedExtDateTimeCheck($surat_masuk->getField($valkey))." ".$infoicondisposisi.$infoiconteruskan;
					}

					// $row[$valkey]= $inforeturn.$infonomor.$_REQUEST['search']["value"];

					if(empty($inforeturn))
					{
						$inforeturn= getFormattedExtDateTimeCheck($surat_masuk->getField($valkey));
					}

					$row[$valkey]= $inforeturn;
				}
				else
					$row[$valkey]= $surat_masuk->getField($valkey);
			}
			array_push($arrinfodata, $row);
		}

		// get all raw data
		$alldata = $arrinfodata;
		// print_r($alldata);exit;

		$data = [];
		// internal use; filter selected columns only from raw data
		foreach ( $alldata as $d ) {
			// $data[] = filterArray( $d, $columnsDefault );
			$data[] = $d;
		}

		// count data
		$totalRecords = $totalDisplay = count( $data );

		// filter by general search keyword
		if ( isset( $_REQUEST['search'] ) ) {
			$data         = filterKeyword( $data, $_REQUEST['search'] );
			$totalDisplay = count( $data );
		}

		if ( isset( $_REQUEST['columns'] ) && is_array( $_REQUEST['columns'] ) ) {
			foreach ( $_REQUEST['columns'] as $column ) {
				if ( isset( $column['search'] ) ) {
					$data         = filterKeyword( $data, $column['search'], $column['data'] );
					$totalDisplay = count( $data );
				}
			}
		}

		// sort
		if ( isset( $_REQUEST['order'][0]['column'] ) && $_REQUEST['order'][0]['dir'] ) {
			$column = $_REQUEST['order'][0]['column'];
			// if(count($columnsDefault) - 2 == $column){}
			// else
			// {
				$dir    = $_REQUEST['order'][0]['dir'];
				usort( $data, function ( $a, $b ) use ( $column, $dir ) {
					$a = array_slice( $a, $column, 1 );
					$b = array_slice( $b, $column, 1 );
					$a = array_pop( $a );
					$b = array_pop( $b );

					if ( $dir === 'asc' ) {
						return $a > $b ? true : false;
					}

					return $a < $b ? true : false;
				} );
			// }
		}

		// pagination length
		if ( isset( $_REQUEST['length'] ) ) {
			$data = array_splice( $data, $_REQUEST['start'], $_REQUEST['length'] );
		}

		// return array values only without the keys
		if ( isset( $_REQUEST['array_values'] ) && $_REQUEST['array_values'] ) {
			$tmp  = $data;
			$data = [];
			foreach ( $tmp as $d ) {
				$data[] = array_values( $d );
			}
		}

		$result = [
		    'recordsTotal'    => $totalRecords,
		    'recordsFiltered' => $totalDisplay,
		    'data'            => $data,
		];

		header('Content-Type: application/json');
		echo json_encode( $result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);	
	}

	function jsonsuratdisposisi()
	{
		$this->load->model("SuratMasuk");

		$reqJenisNaskahId= $this->input->get("reqJenisNaskahId");
		$reqTahun= $this->input->get("reqTahun");

		$surat_masuk= new SuratMasuk();

		if ( isset( $_REQUEST['columnsDef'] ) && is_array( $_REQUEST['columnsDef'] ) ) {
			$columnsDefault = [];
			foreach ( $_REQUEST['columnsDef'] as $field ) {
				$columnsDefault[ $field ] = "true";
			}
		}
		// print_r($columnsDefault);exit;

		$displaystart= -1;
		$displaylength= -1;

		$arrinfodata= [];

		$reqPencarian= $this->input->get("reqPencarian");
		$searchJson= "";
		$infosearch= $_REQUEST['search']["value"];
		if(!empty($reqPencarian))
		{
			$searchJson= " 
			AND 
			(
				UPPER(A.NO_AGENDA) LIKE '%".strtoupper($reqPencarian)."%' OR 
				UPPER(A.NOMOR) LIKE '%".strtoupper($reqPencarian)."%' OR
				UPPER(A.PERIHAL) LIKE '%".strtoupper($reqPencarian)."%' OR 
				UPPER(A.INSTANSI_ASAL) LIKE '%".strtoupper($reqPencarian)."%' OR 
				UPPER(A.DARI_INFO) LIKE '%".strtoupper($reqPencarian)."%' OR 
				UPPER(A.USER_ATASAN) LIKE '%".strtoupper($reqPencarian)."%' OR 
				UPPER(USER_ATASAN_JABATAN) LIKE '%".strtoupper($reqPencarian)."%' OR 
				UPPER(A.NOMOR_SURAT_INFO) LIKE '%".strtoupper($reqPencarian)."%' OR 
				UPPER(B.NAMA_USER_ASAL) LIKE '%".strtoupper($reqPencarian)."%' OR 
				UPPER(B.NAMA_SATKER_ASAL) LIKE '%".strtoupper($reqPencarian)."%'
			)";
		}

		// untuk buat session
		session_start();
		$arrsession= [];
		$_SESSION['kotak_masuk_disposisi']= $arrsession;
		$arrsession["reqTahun"]= $reqTahun;
		if(!empty($infosearch))
			$arrsession["reqPencarian"]= $infosearch;
		else
			$arrsession["reqPencarian"]= $reqPencarian;
		$_SESSION['kotak_masuk_disposisi']= $arrsession;

		$statement= "
		AND 
		(
			A.STATUS_SURAT = 'POSTING' OR
			A.STATUS_SURAT = 'TU-NOMOR' OR
			(
				A.STATUS_SURAT = 'TU-IN' AND
				EXISTS(SELECT 1 FROM SURAT_MASUK_ARSIP X WHERE X.SURAT_MASUK_ID = A.SURAT_MASUK_ID AND X.CABANG_ID = '".$this->CABANG_ID."')
			)
		)";

		$infouserid= $this->ID;
		if($this->SATUAN_KERJA_ID_ASAL_ASLI == $this->SATUAN_KERJA_ID_ASAL){}
		else
		{
			$infouserid= $infouserid."pejabatpengganti".$this->SATUAN_KERJA_ID_ASAL;
		}
		// echo $infouserid;exit;

		if(!empty($reqJenisNaskahId))
		{
			$statement.= " AND A.JENIS_NASKAH_ID IN (".$reqJenisNaskahId.")";
		}

		if(!empty($reqTahun))
		{
			$statement.= " AND A.TAHUN = ".$reqTahun;
		}

		$sOrder = " ORDER BY B.TANGGAL_DISPOSISI DESC";
		$surat_masuk->selectByParamsDisposisiNew(array(), $dsplyRange, $dsplyStart, $infouserid, $statement.$searchJson, $sOrder);
		// echo $surat_masuk->query;exit;

		$infonomor= 0;
		while ($surat_masuk->nextRow()) 
		{
			$infonomor++;
			$infojenisnaskahid= $surat_masuk->getField("JENIS_NASKAH_ID");
			$infosuratmasukid= $surat_masuk->getField("SURAT_MASUK_ID");

			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{
				if ($valkey == "SORDERDEFAULT")
					$row[$valkey]= "1";
				else if($valkey == "NOMOR")
				{
					if($infojenisnaskahid == "1")
						$row[$valkey] = $surat_masuk->getField("NOMOR_SURAT_INFO");
					else
						$row[$valkey] = $surat_masuk->getField($valkey);
				}

				else if ($valkey == "INFO_TERBACA")
				{
					$infoterbaca= "";
					$infodisposisiuserid= $this->ID;
					$infodisposisiterbacainfo= $surat_masuk->getField("TERBACA_INFO");

					$arrcheckterbaca= explode(";", $infodisposisiterbacainfo);
			        if(!empty($arrcheckterbaca) && !empty($infodisposisiterbacainfo))
			        {
			            while (list($key, $val) = each($arrcheckterbaca))
			            {
			                $arrcheckterbacadetil= explode(",", $val);
			                if($infodisposisiuserid == $arrcheckterbacadetil[0])
			                {
			                    $infoterbaca= "1";
			                    break;
			                }
			            }
			        }
					$row[$valkey] = $infoterbaca;
				}
				else if($valkey == "INFO_DARI")
				{
					if($infojenisnaskahid == "1")
						$row[$valkey] = $surat_masuk->getField("DARI_INFO");
					else
						$row[$valkey] = $surat_masuk->getField("USER_ATASAN")."<br/>".$surat_masuk->getField("USER_ATASAN_JABATAN");
				}
				else if($valkey == "DETIL_INFO_DARI_DIPOSISI")
				{
					$row[$valkey] = $surat_masuk->getField("NAMA_USER_ASAL")."<br/>".$surat_masuk->getField("NAMA_SATKER_ASAL");
				}
				else if ($valkey == "TANGGAL_DISPOSISI")
				{
					$infoicondisposisi= "";
					if($surat_masuk->getField("TERDISPOSISI") == "1")
					{
						$infoicondisposisi= "<i class='fa fa-share-alt' aria-hidden='true'></i>";
					}
					$row[$valkey] = getFormattedExtDateTimeCheck($surat_masuk->getField($valkey))." ".$infoicondisposisi;
				}
				else
					$row[$valkey]= $surat_masuk->getField($valkey);
			}
			array_push($arrinfodata, $row);
		}

		// get all raw data
		$alldata = $arrinfodata;
		// print_r($alldata);exit;

		$data = [];
		// internal use; filter selected columns only from raw data
		foreach ( $alldata as $d ) {
			// $data[] = filterArray( $d, $columnsDefault );
			$data[] = $d;
		}

		// count data
		$totalRecords = $totalDisplay = count( $data );

		// filter by general search keyword
		if ( isset( $_REQUEST['search'] ) ) {
			$data         = filterKeyword( $data, $_REQUEST['search'] );
			$totalDisplay = count( $data );
		}

		if ( isset( $_REQUEST['columns'] ) && is_array( $_REQUEST['columns'] ) ) {
			foreach ( $_REQUEST['columns'] as $column ) {
				if ( isset( $column['search'] ) ) {
					$data         = filterKeyword( $data, $column['search'], $column['data'] );
					$totalDisplay = count( $data );
				}
			}
		}

		// sort
		if ( isset( $_REQUEST['order'][0]['column'] ) && $_REQUEST['order'][0]['dir'] ) {
			$column = $_REQUEST['order'][0]['column'];
			if(count($columnsDefault) - 2 == $column){}
			else
			{
				$dir    = $_REQUEST['order'][0]['dir'];
				usort( $data, function ( $a, $b ) use ( $column, $dir ) {
					$a = array_slice( $a, $column, 1 );
					$b = array_slice( $b, $column, 1 );
					$a = array_pop( $a );
					$b = array_pop( $b );

					if ( $dir === 'asc' ) {
						return $a > $b ? true : false;
					}

					return $a < $b ? true : false;
				} );
			}
		}

		// pagination length
		if ( isset( $_REQUEST['length'] ) ) {
			$data = array_splice( $data, $_REQUEST['start'], $_REQUEST['length'] );
		}

		// return array values only without the keys
		if ( isset( $_REQUEST['array_values'] ) && $_REQUEST['array_values'] ) {
			$tmp  = $data;
			$data = [];
			foreach ( $tmp as $d ) {
				$data[] = array_values( $d );
			}
		}

		$result = [
		    'recordsTotal'    => $totalRecords,
		    'recordsFiltered' => $totalDisplay,
		    'data'            => $data,
		];

		header('Content-Type: application/json');
		echo json_encode( $result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);	
	}

	function jsonpersetujuan()
	{
		$this->load->model("SuratMasuk");

		$reqStatusSurat = $this->input->get("reqStatusSurat");

		$surat_masuk= new SuratMasuk();

		if ( isset( $_REQUEST['columnsDef'] ) && is_array( $_REQUEST['columnsDef'] ) ) {
			$columnsDefault = [];
			foreach ( $_REQUEST['columnsDef'] as $field ) {
				$columnsDefault[ $field ] = "true";
			}
		}
		// print_r($columnsDefault);exit;

		$displaystart= -1;
		$displaylength= -1;

		$arrinfodata= [];

		$reqPencarian= $this->input->get("reqPencarian");
		$searchJson= "";
		if(!empty($reqPencarian))
		{
			$searchJson= " 
			AND 
			(
				UPPER(A.PERIHAL) LIKE '%".strtoupper($reqPencarian)."%' OR
				UPPER(A.PERIHAL) LIKE '%".strtoupper($reqPencarian)."%'
			)";
		}

		if($reqStatusSurat == "PERLU_PERSETUJUAN")
		{
			$statement= " 
			--AND SM_INFO NOT IN ('AKAN_DISETUJUI', 'NEXT_DISETUJUI')
			AND 
			(
				(
					(
						A.USER_ATASAN_ID = '".$this->ID."' AND A.APPROVAL_DATE IS NULL AND COALESCE(NULLIF(A.NIP_ATASAN_MUTASI, ''), NULL) IS NULL
						AND TERPARAF IS NULL
						--AND CASE WHEN A.STATUS_SURAT = 'PEMBUAT' THEN A.USER_ATASAN_ID = A.USER_ID END
					)
					OR 
					(
						A.NIP_ATASAN_MUTASI = '".$this->ID."' AND A.APPROVAL_DATE IS NULL AND COALESCE(NULLIF(A.USER_ATASAN_ID, ''), NULL) IS NOT NULL
						AND TERPARAF IS NULL
						-- TAMBAHAN ONE TES
						AND A.USER_ID IS NOT NULL
						--AND CASE WHEN A.STATUS_SURAT = 'PEMBUAT' THEN A.USER_ATASAN_ID = A.USER_ID END
					)
				) 
				OR 
				(
					(
						A.USER_ATASAN_ID = '".$this->USER_GROUP.$this->ID."' AND A.APPROVAL_DATE IS NOT NULL AND COALESCE(NULLIF(A.NIP_ATASAN_MUTASI, ''), NULL) IS NULL
					)
					OR 
					(
						A.NIP_ATASAN_MUTASI = '".$this->USER_GROUP.$this->ID."' AND A.APPROVAL_DATE IS NOT NULL AND COALESCE(NULLIF(A.USER_ATASAN_ID, ''), NULL) IS NOT NULL
					)
				)
				OR 
				(
					A.USER_ID = '".$this->ID."'
					AND CASE WHEN A.USER_ID = '".$this->ID."' THEN TERPARAF IS NOT NULL ELSE TERPARAF IS NULL END
					AND A.STATUS_SURAT = 'PEMBUAT'
				)
				OR 
				(
					A.USER_ID = '".$this->ID."'
					AND CASE WHEN A.USER_ID = '".$this->ID."' THEN TERPARAF IS NULL ELSE TERPARAF IS NOT NULL END
					AND A.STATUS_SURAT != 'PEMBUAT'
				)
			) AND A.STATUS_SURAT IN ('VALIDASI', 'PARAF', 'PEMBUAT')";

			$satuankerjaganti= "";
			// echo $this->SATUAN_KERJA_ID_ASAL_ASLI." == ".$this->SATUAN_KERJA_ID_ASAL;exit;
			if($this->SATUAN_KERJA_ID_ASAL_ASLI == $this->SATUAN_KERJA_ID_ASAL)
			{
			}
			else
			{
				$satuankerjaganti= $this->SATUAN_KERJA_ID_ASAL;
			}
			$satuankerjaganti= $this->SATUAN_KERJA_ID_ASAL;

			$sOrder = " ORDER BY INFO_STATUS_TANGGAL DESC";
			$surat_masuk->selectByParamsNewPersetujuan(array(), $dsplyRange, $dsplyStart, $this->ID, $this->USER_GROUP, $statement.$searchJson, $sOrder, "", $satuankerjaganti);
		}
		else
		{
			$statement= " 
			AND SM_INFO IN ('AKAN_DISETUJUI', 'NEXT_DISETUJUI') 
			AND CASE WHEN A.TERPARAF = 1 AND A.JENIS_NASKAH_ID IN (8,17,18,19,20) THEN A.STATUS_SURAT IN ('PARAF', 'VALIDASI', 'PEMBUAT') ELSE A.STATUS_SURAT IN ('PARAF', 'VALIDASI') END";

			$satuankerjaganti= "";
			if($this->SATUAN_KERJA_ID_ASAL_ASLI == $this->SATUAN_KERJA_ID_ASAL)
			{
			}
			else
			{
				$satuankerjaganti= $this->SATUAN_KERJA_ID_ASAL;
			}

			$infostatus= "1";
			$satuankerjaganti= $this->SATUAN_KERJA_ID_ASAL;

			$sOrder = " ORDER BY INFO_STATUS_TANGGAL DESC";
			$surat_masuk->selectByParamsStatusTujuan(array(), $dsplyRange, $dsplyStart, $this->ID, $infostatus, $satuankerjaganti, $statement.$searchJson, $sOrder);
		}
		// echo $surat_masuk->query;exit;

		$infonomor= 0;
		while ($surat_masuk->nextRow()) 
		{
			$infonomor++;

			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{
				if ($valkey == "SORDERDEFAULT")
					$row[$valkey]= "1";
				else if($valkey == "INFO_STATUS_TANGGAL")
					$row[$valkey] = getFormattedExtDateTimeCheck($surat_masuk->getField($valkey));
				else
					$row[$valkey]= $surat_masuk->getField($valkey);
			}
			array_push($arrinfodata, $row);
		}

		// get all raw data
		$alldata = $arrinfodata;
		// print_r($alldata);exit;

		$data = [];
		// internal use; filter selected columns only from raw data
		foreach ( $alldata as $d ) {
			// $data[] = filterArray( $d, $columnsDefault );
			$data[] = $d;
		}

		// count data
		$totalRecords = $totalDisplay = count( $data );

		// filter by general search keyword
		if ( isset( $_REQUEST['search'] ) ) {
			$data         = filterKeyword( $data, $_REQUEST['search'] );
			$totalDisplay = count( $data );
		}

		if ( isset( $_REQUEST['columns'] ) && is_array( $_REQUEST['columns'] ) ) {
			foreach ( $_REQUEST['columns'] as $column ) {
				if ( isset( $column['search'] ) ) {
					$data         = filterKeyword( $data, $column['search'], $column['data'] );
					$totalDisplay = count( $data );
				}
			}
		}

		// sort
		if ( isset( $_REQUEST['order'][0]['column'] ) && $_REQUEST['order'][0]['dir'] ) {
			$column = $_REQUEST['order'][0]['column'];
			if(count($columnsDefault) - 2 == $column){}
			else
			{
				$dir    = $_REQUEST['order'][0]['dir'];
				usort( $data, function ( $a, $b ) use ( $column, $dir ) {
					$a = array_slice( $a, $column, 1 );
					$b = array_slice( $b, $column, 1 );
					$a = array_pop( $a );
					$b = array_pop( $b );

					if ( $dir === 'asc' ) {
						return $a > $b ? true : false;
					}

					return $a < $b ? true : false;
				} );
			}
		}

		// pagination length
		if ( isset( $_REQUEST['length'] ) ) {
			$data = array_splice( $data, $_REQUEST['start'], $_REQUEST['length'] );
		}

		// return array values only without the keys
		if ( isset( $_REQUEST['array_values'] ) && $_REQUEST['array_values'] ) {
			$tmp  = $data;
			$data = [];
			foreach ( $tmp as $d ) {
				$data[] = array_values( $d );
			}
		}

		$result = [
		    'recordsTotal'    => $totalRecords,
		    'recordsFiltered' => $totalDisplay,
		    'data'            => $data,
		];

		header('Content-Type: application/json');
		echo json_encode( $result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);	
	}

	function jsonstatus()
	{
		$this->load->model("SuratMasuk");

		$reqStatusSurat= $this->input->get("reqStatusSurat");
		$reqPilihan= $this->input->get("reqPilihan");
		$cekquery= $this->input->get("c");

		$surat_masuk= new SuratMasuk();

		if ( isset( $_REQUEST['columnsDef'] ) && is_array( $_REQUEST['columnsDef'] ) ) {
			$columnsDefault = [];
			foreach ( $_REQUEST['columnsDef'] as $field ) {
				$columnsDefault[ $field ] = "true";
			}
		}
		// print_r($columnsDefault);exit;

		$displaystart= -1;
		$displaylength= -1;

		$arrinfodata= [];

		$reqPencarian= $this->input->get("reqPencarian");
		$searchJson= "";
		if(!empty($reqPencarian))
		{
			$searchJson= " 
			AND 
			(
				UPPER(A.NO_AGENDA) LIKE '%".strtoupper($reqPencarian)."%' OR 
				UPPER(A.NOMOR) LIKE '%".strtoupper($reqPencarian)."%' OR
				UPPER(A.PERIHAL) LIKE '%".strtoupper($reqPencarian)."%' OR 
				UPPER(A.INSTANSI_ASAL) LIKE '%".strtoupper($reqPencarian)."%' 
			)";
		}

		$statement= " AND CASE WHEN A.TERPARAF = 1 AND A.JENIS_NASKAH_ID IN (8,17,18,19,20) THEN SM_INFO IN ('AKAN_DISETUJUI') ELSE SM_INFO IN ('AKAN_DISETUJUI', 'PEMBUAT') END";

		$infostatus= "1";
		if(!empty($reqStatusSurat))
		{
			if($reqStatusSurat == "PARAF")
				$statement.= " AND CASE WHEN A.TERPARAF = 1 AND A.JENIS_NASKAH_ID IN (8,17,18,19,20) THEN A.STATUS_SURAT IN ('PARAF', 'VALIDASI', 'PEMBUAT') ELSE A.STATUS_SURAT IN ('PARAF', 'VALIDASI') END";
			else
			{
				$statement= " AND A.STATUS_SURAT = '".$reqStatusSurat."'";
				$infostatus= "-1";
			}
		}

		if($reqPilihan == 'divisi') 
		{
			$reqNIPPP= $this->NIP_BY_DIVISI;
			// $reqNIPPP= "'".str_replace("'", "''", $reqNIPPP)."'";
			$reqNIPPP= str_replace("'", "''", $reqNIPPP);
			// echo $reqNIPPP;exit;
			// $reqKelompokJabatann= $this->KELOMPOK_JABATAN_BY_DIVISI;
			// $reqKelompokJabatann= "'".str_replace("'", "''", $reqKelompokJabatann)."'";
			$reqsatuankerjadivisi= "";
		}
		else
		{
			// echo $reqNIPPP
		}
		$satuankerjaganti= $this->SATUAN_KERJA_ID_ASAL;

		$sOrder = " ORDER BY INFO_STATUS_TANGGAL DESC";

		if($reqPilihan == 'divisi') 
		{
			$surat_masuk->selectByParamsDivisiStatusTujuan(array(), $dsplyRange, $dsplyStart, $reqNIPPP, $infostatus, $satuankerjaganti, $statement.$searchJson, $sOrder);
		}
		else
		{
			$surat_masuk->selectByParamsStatusTujuan(array(), $dsplyRange, $dsplyStart, $this->ID, $infostatus, $satuankerjaganti, $statement.$searchJson, $sOrder);
		}
		
		if(!empty($cekquery))
		{
			echo $surat_masuk->query;exit;
		}

		$infonomor= 0;
		while ($surat_masuk->nextRow()) 
		{
			$infonomor++;
			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{
				if ($valkey == "SORDERDEFAULT")
					$row[$valkey]= "1";
				else if($valkey == "INFO_STATUS_TANGGAL")
					$row[$valkey] = getFormattedExtDateTimeCheck($surat_masuk->getField($valkey));
				else
					$row[$valkey]= $surat_masuk->getField($valkey);
			}
			array_push($arrinfodata, $row);
		}

		// get all raw data
		$alldata = $arrinfodata;
		// print_r($alldata);exit;

		$data = [];
		// internal use; filter selected columns only from raw data
		foreach ( $alldata as $d ) {
			// $data[] = filterArray( $d, $columnsDefault );
			$data[] = $d;
		}

		// count data
		$totalRecords = $totalDisplay = count( $data );

		// filter by general search keyword
		if ( isset( $_REQUEST['search'] ) ) {
			$data         = filterKeyword( $data, $_REQUEST['search'] );
			$totalDisplay = count( $data );
		}

		if ( isset( $_REQUEST['columns'] ) && is_array( $_REQUEST['columns'] ) ) {
			foreach ( $_REQUEST['columns'] as $column ) {
				if ( isset( $column['search'] ) ) {
					$data         = filterKeyword( $data, $column['search'], $column['data'] );
					$totalDisplay = count( $data );
				}
			}
		}

		// sort
		if ( isset( $_REQUEST['order'][0]['column'] ) && $_REQUEST['order'][0]['dir'] ) {
			$column = $_REQUEST['order'][0]['column'];
			if(count($columnsDefault) - 2 == $column){}
			else
			{
				$dir    = $_REQUEST['order'][0]['dir'];
				usort( $data, function ( $a, $b ) use ( $column, $dir ) {
					$a = array_slice( $a, $column, 1 );
					$b = array_slice( $b, $column, 1 );
					$a = array_pop( $a );
					$b = array_pop( $b );

					if ( $dir === 'asc' ) {
						return $a > $b ? true : false;
					}

					return $a < $b ? true : false;
				} );
			}
		}

		// pagination length
		if ( isset( $_REQUEST['length'] ) ) {
			$data = array_splice( $data, $_REQUEST['start'], $_REQUEST['length'] );
		}

		// return array values only without the keys
		if ( isset( $_REQUEST['array_values'] ) && $_REQUEST['array_values'] ) {
			$tmp  = $data;
			$data = [];
			foreach ( $tmp as $d ) {
				$data[] = array_values( $d );
			}
		}

		$result = [
		    'recordsTotal'    => $totalRecords,
		    'recordsFiltered' => $totalDisplay,
		    'data'            => $data,
		];

		header('Content-Type: application/json');
		echo json_encode( $result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);	
	}

	function jsonsurattanggapan()
	{
		$this->load->model("SuratMasuk");

		$reqTahun= $this->input->get("reqTahun");

		$surat_masuk= new SuratMasuk();

		if ( isset( $_REQUEST['columnsDef'] ) && is_array( $_REQUEST['columnsDef'] ) ) {
			$columnsDefault = [];
			foreach ( $_REQUEST['columnsDef'] as $field ) {
				$columnsDefault[ $field ] = "true";
			}
		}
		// print_r($columnsDefault);exit;

		$displaystart= -1;
		$displaylength= -1;

		$arrinfodata= [];

		$reqPencarian= $this->input->get("reqPencarian");
		$searchJson= "";
		if(!empty($reqPencarian))
		{
			$searchJson= " 
			AND 
			(
				UPPER(A.NO_AGENDA) LIKE '%".strtoupper($reqPencarian)."%' OR 
				UPPER(A.NOMOR) LIKE '%".strtoupper($reqPencarian)."%' OR
				UPPER(A.PERIHAL) LIKE '%".strtoupper($reqPencarian)."%' OR 
				UPPER(A.INSTANSI_ASAL) LIKE '%".strtoupper($reqPencarian)."%' OR 
				UPPER(A.DARI_INFO) LIKE '%".strtoupper($reqPencarian)."%' OR 
				UPPER(A.USER_ATASAN) LIKE '%".strtoupper($reqPencarian)."%' OR 
				UPPER(USER_ATASAN_JABATAN) LIKE '%".strtoupper($reqPencarian)."%' OR 
				UPPER(A.NOMOR_SURAT_INFO) LIKE '%".strtoupper($reqPencarian)."%' OR 
				UPPER(B.NAMA_USER_ASAL) LIKE '%".strtoupper($reqPencarian)."%' OR 
				UPPER(B.NAMA_SATKER_ASAL) LIKE '%".strtoupper($reqPencarian)."%'
			)";
		}

		$statement= "
		AND 
		(
			A.STATUS_SURAT = 'POSTING' OR
			A.STATUS_SURAT = 'TU-NOMOR' OR
			(
				A.STATUS_SURAT = 'TU-IN' AND
				EXISTS(SELECT 1 FROM SURAT_MASUK_ARSIP X WHERE X.SURAT_MASUK_ID = A.SURAT_MASUK_ID AND X.CABANG_ID = '".$this->CABANG_ID."')
			)
		)";

		if(!empty($reqTahun))
		{
			$statement.= " AND A.TAHUN = ".$reqTahun;
		}

		$sOrder = " ORDER BY B.TANGGAL_DISPOSISI DESC";
		$surat_masuk->selectByParamsTanggapanDisposisi(array(), $dsplyRange, $dsplyStart, $this->ID, $statement.$searchJson, $sOrder);
		// echo $surat_masuk->query;exit;

		$infonomor= 0;
		while ($surat_masuk->nextRow()) 
		{
			$infonomor++;
			$infojenisnaskahid= $surat_masuk->getField("JENIS_NASKAH_ID");
			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{
				if ($valkey == "SORDERDEFAULT")
					$row[$valkey]= "1";
				else if($valkey == "INFO_DARI")
				{
					if($infojenisnaskahid == "1")
						$row[$valkey] = $surat_masuk->getField("DARI_INFO");
					else
						$row[$valkey] = $surat_masuk->getField("USER_ATASAN")."<br/>".$surat_masuk->getField("USER_ATASAN_JABATAN");
				}
				else if($valkey == "DETIL_INFO_DARI_DIPOSISI")
					$row[$valkey] = $surat_masuk->getField("NAMA_USER_ASAL")."<br/>".$surat_masuk->getField("NAMA_SATKER_ASAL");
				else
					$row[$valkey]= $surat_masuk->getField($valkey);
			}
			array_push($arrinfodata, $row);
		}

		// get all raw data
		$alldata = $arrinfodata;
		// print_r($alldata);exit;

		$data = [];
		// internal use; filter selected columns only from raw data
		foreach ( $alldata as $d ) {
			// $data[] = filterArray( $d, $columnsDefault );
			$data[] = $d;
		}

		// count data
		$totalRecords = $totalDisplay = count( $data );

		// filter by general search keyword
		if ( isset( $_REQUEST['search'] ) ) {
			$data         = filterKeyword( $data, $_REQUEST['search'] );
			$totalDisplay = count( $data );
		}

		if ( isset( $_REQUEST['columns'] ) && is_array( $_REQUEST['columns'] ) ) {
			foreach ( $_REQUEST['columns'] as $column ) {
				if ( isset( $column['search'] ) ) {
					$data         = filterKeyword( $data, $column['search'], $column['data'] );
					$totalDisplay = count( $data );
				}
			}
		}

		// sort
		if ( isset( $_REQUEST['order'][0]['column'] ) && $_REQUEST['order'][0]['dir'] ) {
			$column = $_REQUEST['order'][0]['column'];
			if(count($columnsDefault) - 2 == $column){}
			else
			{
				$dir    = $_REQUEST['order'][0]['dir'];
				usort( $data, function ( $a, $b ) use ( $column, $dir ) {
					$a = array_slice( $a, $column, 1 );
					$b = array_slice( $b, $column, 1 );
					$a = array_pop( $a );
					$b = array_pop( $b );

					if ( $dir === 'asc' ) {
						return $a > $b ? true : false;
					}

					return $a < $b ? true : false;
				} );
			}
		}

		// pagination length
		if ( isset( $_REQUEST['length'] ) ) {
			$data = array_splice( $data, $_REQUEST['start'], $_REQUEST['length'] );
		}

		// return array values only without the keys
		if ( isset( $_REQUEST['array_values'] ) && $_REQUEST['array_values'] ) {
			$tmp  = $data;
			$data = [];
			foreach ( $tmp as $d ) {
				$data[] = array_values( $d );
			}
		}

		$result = [
		    'recordsTotal'    => $totalRecords,
		    'recordsFiltered' => $totalDisplay,
		    'data'            => $data,
		];

		header('Content-Type: application/json');
		echo json_encode( $result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);	
	}

	function jsonsuratkeluar()
	{
		$this->load->model("SuratMasuk");
		$this->load->model("SatuanKerja");

		$reqJenisNaskahId= $this->input->get("reqJenisNaskahId");
		$reqTahun= $this->input->get("reqTahun");
		$reqPilihan= $this->input->get("reqPilihan");
		$cekquery= $this->input->get("c");

		$surat_masuk= new SuratMasuk();

		if ( isset( $_REQUEST['columnsDef'] ) && is_array( $_REQUEST['columnsDef'] ) ) {
			$columnsDefault = [];
			foreach ( $_REQUEST['columnsDef'] as $field ) {
				$columnsDefault[ $field ] = "true";
			}
		}
		// print_r($columnsDefault);exit;

		$displaystart= -1;
		$displaylength= -1;

		$arrinfodata= [];

		$reqPencarian= $this->input->get("reqPencarian");
		$searchJson= "";
		$infosearch= $_REQUEST['search']["value"];
		if(!empty($reqPencarian))
		{
			$searchJson= " 
			AND 
			(
				UPPER(A.NO_AGENDA) LIKE '%".strtoupper($reqPencarian)."%' OR 
				UPPER(A.NOMOR) LIKE '%".strtoupper($reqPencarian)."%' OR
				UPPER(A.PERIHAL) LIKE '%".strtoupper($reqPencarian)."%' OR 
				UPPER(A.INSTANSI_ASAL) LIKE '%".strtoupper($reqPencarian)."%' OR 
				UPPER(A.DARI_INFO) LIKE '%".strtoupper($reqPencarian)."%' OR 
				UPPER(A.USER_ATASAN) LIKE '%".strtoupper($reqPencarian)."%' OR 
				UPPER(USER_ATASAN_JABATAN) LIKE '%".strtoupper($reqPencarian)."%' OR 
				UPPER(A.NOMOR_SURAT_INFO) LIKE '%".strtoupper($reqPencarian)."%'
			)";
		}

		// untuk buat session
		session_start();
		$arrsession= [];
		$_SESSION['kotak_keluar'.$reqJenisNaskahId]= $arrsession;
		$arrsession["reqTahun"]= $reqTahun;
		$arrsession["reqPilihan"]= $reqPilihan;
		if(!empty($infosearch))
			$arrsession["reqPencarian"]= $infosearch;
		else
			$arrsession["reqPencarian"]= $reqPencarian;
		$_SESSION['kotak_keluar'.$reqJenisNaskahId]= $arrsession;

		$statement= " AND (A.STATUS_SURAT IN ('TATAUSAHA','POSTING') OR A.STATUS_SURAT LIKE 'TU%')";

		if(!empty($reqJenisNaskahId))
		{
			$statement.= " AND A.JENIS_NASKAH_ID IN (".$reqJenisNaskahId.")";
		}

		if(!empty($reqTahun))
		{
			$statement.= " AND A.TAHUN = ".$reqTahun;
		}

		if ($reqPilihan=='divisi') 
		{
			$reqNIPPP= $this->NIP_BY_DIVISI;
		} 
		else 
		{
			$statement.= " AND CASE WHEN (SM_INFO = 'PEMBUAT' OR SM_INFO = 'AKAN_DISETUJUI') THEN TRUE ELSE A.SATUAN_KERJA_ID_ASAL = '".$this->SATUAN_KERJA_ID_ASAL."' END";
			if($this->SATUAN_KERJA_ID_ASAL_ASLI == $this->SATUAN_KERJA_ID_ASAL)
			{
				$reqNIPPP= "'".$this->ID."'";
			}
			else
			{
				/*$setdetil= new SatuanKerja();
				$setdetil->selectdata(array(), -1,-1, " AND A.SATUAN_KERJA_ID = '".$this->SATUAN_KERJA_ID_ASAL."'");
				$setdetil->firstRow();
				$infonipmutasi= $setdetil->getField("NIP_MUTASI");
				// echo $infonipmutasi;exit;

				if(empty($infonipmutasi))
					$reqNIPPP= "'".$this->ID."'";
				else
					$reqNIPPP= "'".$infonipmutasi."'";*/

				// $statementuser= " AND A.SATUAN_KERJA_ID_ASAL = '".$this->SATUAN_KERJA_ID_ASAL."' AND A.USER_ATASAN_ID NOT IN ('".$this->ID."')";
				/*$statementuser= "
				AND 
				(
					(COALESCE(NULLIF(A.NIP_ATASAN_MUTASI, ''), NULL) IS NOT NULL AND A.NIP_ATASAN_MUTASI = '".$this->ID."')
					OR A.USER_ATASAN_ID = '".$this->ID."'
				)
				AND A.SATUAN_KERJA_ID_ASAL = '".$this->SATUAN_KERJA_ID_ASAL."'
				";
				$setdetil= new SatuanKerja();
				$setdetil->selectatasanuser(array(), -1,-1, $statementuser);*/

				$statementuser= " AND A.SATUAN_KERJA_ID_ASAL = '".$this->SATUAN_KERJA_ID_ASAL."'";
				if(!empty($reqTahun))
				{
					$statementuser.= " AND A.TAHUN = ".$reqTahun;
				}
				
				$setdetil= new SatuanKerja();
				$setdetil->selectuseratasan($statementuser);
				$vreturn= "";
				while($setdetil->nextRow())
				{
					$infonipmutasi= $setdetil->getField("USER_ATASAN_ID");
					if(empty($vreturn))
						$vreturn = "'".$infonipmutasi."'";
					else
					{
						$vreturn = $vreturn.",'".$infonipmutasi."'";
					}
				}
				// echo $vreturn;exit;

				if(empty($vreturn))
					$reqNIPPP= "'".$this->ID."'";
				else
					$reqNIPPP= $vreturn;
			}
		}

		$sOrder = " ORDER BY A.TANGGAL_DISPOSISI DESC";
		$surat_masuk->selectByParamsSuratKeluarNew(array(), $dsplyRange, $dsplyStart, $reqNIPPP, $statement.$searchJson, $sOrder);

		if(!empty($cekquery))
		{
			echo $surat_masuk->query;exit;
		}

		$infonomor= 0;
		while ($surat_masuk->nextRow()) 
		{
			$infonomor++;
			$infojenisnaskahid= $surat_masuk->getField("JENIS_NASKAH_ID");
			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{
				if ($valkey == "SORDERDEFAULT")
					$row[$valkey]= "1";
				else if($valkey == "INFO_DARI")
				{
					if($infojenisnaskahid == "1")
						$row[$valkey] = $surat_masuk->getField("DARI_INFO");
					else
						$row[$valkey] = $surat_masuk->getField("USER_ATASAN")."<br/>".$surat_masuk->getField("USER_ATASAN_JABATAN");
				}
				else if($valkey == "TANGGAL_DISPOSISI")
					$row[$valkey] = getFormattedExtDateTimeCheck($surat_masuk->getField($valkey));
				else
					$row[$valkey]= $surat_masuk->getField($valkey);
			}
			array_push($arrinfodata, $row);
		}

		// get all raw data
		$alldata = $arrinfodata;
		// print_r($alldata);exit;

		$data = [];
		// internal use; filter selected columns only from raw data
		foreach ( $alldata as $d ) {
			// $data[] = filterArray( $d, $columnsDefault );
			$data[] = $d;
		}

		// count data
		$totalRecords = $totalDisplay = count( $data );

		// filter by general search keyword
		if ( isset( $_REQUEST['search'] ) ) {
			$data         = filterKeyword( $data, $_REQUEST['search'] );
			$totalDisplay = count( $data );
		}

		if ( isset( $_REQUEST['columns'] ) && is_array( $_REQUEST['columns'] ) ) {
			foreach ( $_REQUEST['columns'] as $column ) {
				if ( isset( $column['search'] ) ) {
					$data         = filterKeyword( $data, $column['search'], $column['data'] );
					$totalDisplay = count( $data );
				}
			}
		}

		// sort
		if ( isset( $_REQUEST['order'][0]['column'] ) && $_REQUEST['order'][0]['dir'] ) {
			$column = $_REQUEST['order'][0]['column'];
			if(count($columnsDefault) - 2 == $column){}
			else
			{
				$dir    = $_REQUEST['order'][0]['dir'];
				usort( $data, function ( $a, $b ) use ( $column, $dir ) {
					$a = array_slice( $a, $column, 1 );
					$b = array_slice( $b, $column, 1 );
					$a = array_pop( $a );
					$b = array_pop( $b );

					if ( $dir === 'asc' ) {
						return $a > $b ? true : false;
					}

					return $a < $b ? true : false;
				} );
			}
		}

		// pagination length
		if ( isset( $_REQUEST['length'] ) ) {
			$data = array_splice( $data, $_REQUEST['start'], $_REQUEST['length'] );
		}

		// return array values only without the keys
		if ( isset( $_REQUEST['array_values'] ) && $_REQUEST['array_values'] ) {
			$tmp  = $data;
			$data = [];
			foreach ( $tmp as $d ) {
				$data[] = array_values( $d );
			}
		}

		$result = [
		    'recordsTotal'    => $totalRecords,
		    'recordsFiltered' => $totalDisplay,
		    'data'            => $data,
		];

		header('Content-Type: application/json');
		echo json_encode( $result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);	
	}

	function jsonsuratkeluardisposisi()
	{
		$this->load->model("SuratMasuk");

		$reqJenisNaskahId= $this->input->get("reqJenisNaskahId");
		$reqTahun= $this->input->get("reqTahun");
		$reqPilihan= $this->input->get("reqPilihan");

		$surat_masuk= new SuratMasuk();
		$setdetil= new SuratMasuk();

		if ( isset( $_REQUEST['columnsDef'] ) && is_array( $_REQUEST['columnsDef'] ) ) {
			$columnsDefault = [];
			foreach ( $_REQUEST['columnsDef'] as $field ) {
				$columnsDefault[ $field ] = "true";
			}
		}
		// print_r($columnsDefault);exit;

		$displaystart= -1;
		$displaylength= -1;

		$arrinfodata= [];

		$reqPencarian= $this->input->get("reqPencarian");
		$searchJson= "";
		$infosearch= $_REQUEST['search']["value"];
		if(!empty($reqPencarian))
		{
			$searchJson= " 
			AND 
			(
				UPPER(A.NO_AGENDA) LIKE '%".strtoupper($reqPencarian)."%' OR 
				UPPER(A.NOMOR) LIKE '%".strtoupper($reqPencarian)."%' OR
				UPPER(A.PERIHAL) LIKE '%".strtoupper($reqPencarian)."%' OR 
				UPPER(A.INSTANSI_ASAL) LIKE '%".strtoupper($reqPencarian)."%' OR 
				UPPER(A.DARI_INFO) LIKE '%".strtoupper($reqPencarian)."%' OR 
				UPPER(A.USER_ATASAN) LIKE '%".strtoupper($reqPencarian)."%' OR 
				UPPER(USER_ATASAN_JABATAN) LIKE '%".strtoupper($reqPencarian)."%'
				--OR UPPER(INFO_KEPADA_DIPOSISI(B.DISPOSISI_PARENT_ID, TO_CHAR(B.TANGGAL_DISPOSISI, 'YYYY-MM-DD HH24:MI'))) LIKE '%".strtoupper($reqPencarian)."%'
			)";
		}

		// untuk buat session
		session_start();
		$arrsession= [];
		$_SESSION['kotak_keluar_disposisi']= $arrsession;
		$arrsession["reqTahun"]= $reqTahun;
		if(!empty($infosearch))
			$arrsession["reqPencarian"]= $infosearch;
		else
			$arrsession["reqPencarian"]= $reqPencarian;
		$_SESSION['kotak_keluar_disposisi']= $arrsession;

		$statement= "
		AND 
		(
			A.STATUS_SURAT = 'POSTING' OR
			A.STATUS_SURAT = 'TU-NOMOR' OR
			(
				A.STATUS_SURAT = 'TU-IN' AND
				EXISTS(SELECT 1 FROM SURAT_MASUK_ARSIP X WHERE X.SURAT_MASUK_ID = A.SURAT_MASUK_ID AND X.CABANG_ID = '".$this->CABANG_ID."')
			)
		)";

		if(!empty($reqTahun))
		{
			$statement.= " AND A.TAHUN = ".$reqTahun;
		}

		$sOrder = " ORDER BY B.TANGGAL_DISPOSISI DESC";
		$surat_masuk->selectByParamsKeluarDisposisiNo(array(), $dsplyRange, $dsplyStart, $this->ID, $statement.$searchJson, $sOrder);
		// $surat_masuk->selectByParamsKeluarDisposisi(array(), $_REQUEST['length'], $_REQUEST['start'], $this->ID, $statement.$searchJson, $sOrder);
		// echo $surat_masuk->query;exit;

		$infobatasdetil= $_REQUEST['start'] + $_REQUEST['length'];
		$infonomor= 0;
		while ($surat_masuk->nextRow()) 
		{
			$infonomor++;
			$infojenisnaskahid= $surat_masuk->getField("JENIS_NASKAH_ID");
			$infodisposisiid= $surat_masuk->getField("DISPOSISI_ID");
			$infoparentdisposisiid= $surat_masuk->getField("DISPOSISI_PARENT_ID");
			$infotanggaldisposisi= $surat_masuk->getField("TANGGAL_DISPOSISI");
			$infolastcreateuserdisposisi= $surat_masuk->getField("LAST_CREATE_USER");
			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{
				if ($valkey == "SORDERDEFAULT")
					$row[$valkey]= "1";
				else if($valkey == "DETIL_INFO_KEPADA_DIPOSISI")
				{
					$inforeturn= "";
					if($infonomor <= $infobatasdetil && $infonomor >= $_REQUEST['start'])
					{
						$setdetil->selectdisposisiinfokepada($infoparentdisposisiid, $infotanggaldisposisi, $infolastcreateuserdisposisi);
						$setdetil->firstRow();
						// echo $setdetil->query;exit;
						$inforeturn= $setdetil->getField("DETIL_INFO_KEPADA_DIPOSISI");
					}
					$row[$valkey]= $inforeturn;
				}
				else if($valkey == "NOMOR")
				{
					if($infojenisnaskahid == "1")
						$row[$valkey] = $surat_masuk->getField("NOMOR_SURAT_INFO");
					else
						$row[$valkey] = $surat_masuk->getField($valkey);
				}
				else if($valkey == "INFO_DARI")
				{
					$row[$valkey] = $surat_masuk->getField("USER_ATASAN")."<br/>".$surat_masuk->getField("USER_ATASAN_JABATAN");
				}
				else if($valkey == "TANGGAL_DISPOSISI")
					$row[$valkey] = getFormattedExtDateTimeCheck($surat_masuk->getField($valkey));
				else
					$row[$valkey]= $surat_masuk->getField($valkey);
			}
			array_push($arrinfodata, $row);
		}

		// get all raw data
		$alldata = $arrinfodata;
		// print_r($alldata);exit;

		$data = [];
		// internal use; filter selected columns only from raw data
		foreach ( $alldata as $d ) {
			// $data[] = filterArray( $d, $columnsDefault );
			$data[] = $d;
		}

		// count data
		$totalRecords = $totalDisplay = count( $data );

		// filter by general search keyword
		if ( isset( $_REQUEST['search'] ) ) {
			$data         = filterKeyword( $data, $_REQUEST['search'] );
			$totalDisplay = count( $data );
		}

		if ( isset( $_REQUEST['columns'] ) && is_array( $_REQUEST['columns'] ) ) {
			foreach ( $_REQUEST['columns'] as $column ) {
				if ( isset( $column['search'] ) ) {
					$data         = filterKeyword( $data, $column['search'], $column['data'] );
					$totalDisplay = count( $data );
				}
			}
		}

		// sort
		if ( isset( $_REQUEST['order'][0]['column'] ) && $_REQUEST['order'][0]['dir'] ) {
			$column = $_REQUEST['order'][0]['column'];
			if(count($columnsDefault) - 2 == $column){}
			else
			{
				$dir    = $_REQUEST['order'][0]['dir'];
				usort( $data, function ( $a, $b ) use ( $column, $dir ) {
					$a = array_slice( $a, $column, 1 );
					$b = array_slice( $b, $column, 1 );
					$a = array_pop( $a );
					$b = array_pop( $b );

					if ( $dir === 'asc' ) {
						return $a > $b ? true : false;
					}

					return $a < $b ? true : false;
				} );
			}
		}

		// pagination length
		if ( isset( $_REQUEST['length'] ) ) {
			$data = array_splice( $data, $_REQUEST['start'], $_REQUEST['length'] );
		}

		// return array values only without the keys
		if ( isset( $_REQUEST['array_values'] ) && $_REQUEST['array_values'] ) {
			$tmp  = $data;
			$data = [];
			foreach ( $tmp as $d ) {
				$data[] = array_values( $d );
			}
		}

		// khusus kalau code hitung ulang
		// $surat_masuk= new SuratMasuk();
		// $totalRecords= $surat_masuk->getCountByParamsKeluarDisposisi(array(), $this->ID, $statement);
		// if ($reqPencarian == "")
		// 	$totalDisplay= $totalRecords;

		$result = [
		    'recordsTotal'    => $totalRecords,
		    'recordsFiltered' => $totalDisplay,
		    'data'            => $data,
		];

		header('Content-Type: application/json');
		echo json_encode( $result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);	
	}

	function jsonsuratkeluartanggapan()
	{
		$this->load->model("SuratMasuk");

		$reqJenisNaskahId= $this->input->get("reqJenisNaskahId");
		$reqTahun= $this->input->get("reqTahun");
		$reqPilihan= $this->input->get("reqPilihan");

		$surat_masuk= new SuratMasuk();
		$setdetil= new SuratMasuk();

		if ( isset( $_REQUEST['columnsDef'] ) && is_array( $_REQUEST['columnsDef'] ) ) {
			$columnsDefault = [];
			foreach ( $_REQUEST['columnsDef'] as $field ) {
				$columnsDefault[ $field ] = "true";
			}
		}
		// print_r($columnsDefault);exit;

		$displaystart= -1;
		$displaylength= -1;

		$arrinfodata= [];

		$reqPencarian= $this->input->get("reqPencarian");
		$searchJson= "";
		if(!empty($reqPencarian))
		{
			$searchJson= " 
			AND 
			(
				UPPER(A.NO_AGENDA) LIKE '%".strtoupper($reqPencarian)."%' OR 
				UPPER(A.NOMOR) LIKE '%".strtoupper($reqPencarian)."%' OR
				UPPER(A.PERIHAL) LIKE '%".strtoupper($reqPencarian)."%' OR 
				UPPER(A.INSTANSI_ASAL) LIKE '%".strtoupper($reqPencarian)."%' OR 
				UPPER(A.DARI_INFO) LIKE '%".strtoupper($reqPencarian)."%' OR 
				UPPER(A.USER_ATASAN) LIKE '%".strtoupper($reqPencarian)."%' OR 
				UPPER(USER_ATASAN_JABATAN) LIKE '%".strtoupper($reqPencarian)."%' OR 
				UPPER(B.NAMA_USER) LIKE '%".strtoupper($reqPencarian)."%' OR 
				UPPER(B.NAMA_SATKER) LIKE '%".strtoupper($reqPencarian)."%'
			)";
		}

		$statement= "
		AND 
		(
			A.STATUS_SURAT = 'POSTING' OR
			A.STATUS_SURAT = 'TU-NOMOR' OR
			(
				A.STATUS_SURAT = 'TU-IN' AND
				EXISTS(SELECT 1 FROM SURAT_MASUK_ARSIP X WHERE X.SURAT_MASUK_ID = A.SURAT_MASUK_ID AND X.CABANG_ID = '".$this->CABANG_ID."')
			)
		)";

		if(!empty($reqTahun))
		{
			$statement.= " AND A.TAHUN = ".$reqTahun;
		}

		$sOrder = " ORDER BY B.TANGGAL_DISPOSISI DESC";
		$surat_masuk->selectByParamsTanggapanKeluarDisposisi(array(), $dsplyRange, $dsplyStart, $this->ID, $statement.$searchJson, $sOrder);
		// echo $surat_masuk->query;exit;

		$infobatasdetil= $_REQUEST['start'] + $_REQUEST['length'];
		$infonomor= 0;
		while ($surat_masuk->nextRow()) 
		{
			$infonomor++;
			$infojenisnaskahid= $surat_masuk->getField("JENIS_NASKAH_ID");
			$infodisposisiid= $surat_masuk->getField("DISPOSISI_ID");
			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{
				if ($valkey == "SORDERDEFAULT")
					$row[$valkey]= "1";
				else if($valkey == "NOMOR")
				{
					if($infojenisnaskahid == "1")
						$row[$valkey] = $surat_masuk->getField("NOMOR_SURAT_INFO");
					else
						$row[$valkey] = $surat_masuk->getField($valkey);
				}
				else if($valkey == "DETIL_INFO_DARI_DIPOSISI")
				{
					$row[$valkey] = $surat_masuk->getField("NAMA_USER")."<br/>".$surat_masuk->getField("NAMA_SATKER");
				}
				else if($valkey == "INFO_DARI")
				{
					$row[$valkey] = $surat_masuk->getField("USER_ATASAN")."<br/>".$surat_masuk->getField("USER_ATASAN_JABATAN");
				}
				else if($valkey == "TANGGAL_DISPOSISI")
					$row[$valkey] = getFormattedExtDateTimeCheck($surat_masuk->getField($valkey));
				else
					$row[$valkey]= $surat_masuk->getField($valkey);
			}
			array_push($arrinfodata, $row);
		}

		// get all raw data
		$alldata = $arrinfodata;
		// print_r($alldata);exit;

		$data = [];
		// internal use; filter selected columns only from raw data
		foreach ( $alldata as $d ) {
			// $data[] = filterArray( $d, $columnsDefault );
			$data[] = $d;
		}

		// count data
		$totalRecords = $totalDisplay = count( $data );

		// filter by general search keyword
		if ( isset( $_REQUEST['search'] ) ) {
			$data         = filterKeyword( $data, $_REQUEST['search'] );
			$totalDisplay = count( $data );
		}

		if ( isset( $_REQUEST['columns'] ) && is_array( $_REQUEST['columns'] ) ) {
			foreach ( $_REQUEST['columns'] as $column ) {
				if ( isset( $column['search'] ) ) {
					$data         = filterKeyword( $data, $column['search'], $column['data'] );
					$totalDisplay = count( $data );
				}
			}
		}

		// sort
		if ( isset( $_REQUEST['order'][0]['column'] ) && $_REQUEST['order'][0]['dir'] ) {
			$column = $_REQUEST['order'][0]['column'];
			if(count($columnsDefault) - 2 == $column){}
			else
			{
				$dir    = $_REQUEST['order'][0]['dir'];
				usort( $data, function ( $a, $b ) use ( $column, $dir ) {
					$a = array_slice( $a, $column, 1 );
					$b = array_slice( $b, $column, 1 );
					$a = array_pop( $a );
					$b = array_pop( $b );

					if ( $dir === 'asc' ) {
						return $a > $b ? true : false;
					}

					return $a < $b ? true : false;
				} );
			}
		}

		// pagination length
		if ( isset( $_REQUEST['length'] ) ) {
			$data = array_splice( $data, $_REQUEST['start'], $_REQUEST['length'] );
		}

		// return array values only without the keys
		if ( isset( $_REQUEST['array_values'] ) && $_REQUEST['array_values'] ) {
			$tmp  = $data;
			$data = [];
			foreach ( $tmp as $d ) {
				$data[] = array_values( $d );
			}
		}

		$result = [
		    'recordsTotal'    => $totalRecords,
		    'recordsFiltered' => $totalDisplay,
		    'data'            => $data,
		];

		header('Content-Type: application/json');
		echo json_encode( $result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);	
	}

}