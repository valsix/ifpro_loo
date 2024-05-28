<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once("functions/image.func.php");
include_once("functions/string.func.php");

class Main2 extends CI_Controller {

	function __construct() {
	
	
		parent::__construct();
		//kauth
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
		$this->KD_LEVEL				= $this->kauth->getInstance()->getIdentity()->KD_LEVEL;  
		$this->KD_LEVEL_PEJABAT 	= $this->kauth->getInstance()->getIdentity()->KD_LEVEL_PEJABAT;  
		$this->JENIS_KELAMIN 		= $this->kauth->getInstance()->getIdentity()->JENIS_KELAMIN;  
		$this->KELOMPOK_JABATAN 	= $this->kauth->getInstance()->getIdentity()->KELOMPOK_JABATAN;  
		$this->ID_ATASAN 			= $this->kauth->getInstance()->getIdentity()->ID_ATASAN;  
		$this->DEPARTEMEN_PARENT_ID	= $this->kauth->getInstance()->getIdentity()->DEPARTEMEN_PARENT_ID;  
		
		//$this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
	}
	
	public function index()
	{
		date_default_timezone_set('Asia/Jakarta');
		
		
		$pg = $this->uri->segment(3, "home");
		$reqParse1 = $this->uri->segment(4, "");
		$reqParse2 = $this->uri->segment(5, "");
		$reqParse3 = $this->uri->segment(6, "");
		$reqParse4 = $this->uri->segment(7, "");
		$reqParse5 = $this->uri->segment(5, "");
		
		if($this->ID == "" && $pg !== "home")
			redirect("app/index/home");
		
		
		$view = array(
			'pg' => $pg,
			'reqParse1' => $reqParse1,
			'reqParse2'	=> $reqParse2,
			'reqParse3'	=> $reqParse3,
			'reqParse4'	=> $reqParse4,
			'reqParse5'	=> $reqParse5,
			'reqFilename' => $pg
		);	
		
		$arrJudul = explode("_", $pg);
		$max = count($arrJudul) - 1;
		if($arrJudul[$max] == "add")
		{		
			$link_monitoring = str_replace("_add", "", $pg);
			$monitoring = str_replace("_", " ", $link_monitoring);
			
			//$breadcrumb = "<li><a href=\"app/index/".$link_monitoring."\">".$monitoring."</a></li>";
			$breadcrumb = "<li><a href=\"main/index/".$link_monitoring."\">".$monitoring."</a></li>";
			$breadcrumb .= "<li> Tambah ".$monitoring."</li>";
		}
		elseif($arrJudul[$max] == "kelompok")
		{		
			$link_monitoring = str_replace("_kelompok", "", $pg);
			$monitoring = str_replace("_", " ", $link_monitoring);
			
			//$breadcrumb = "<li><a href=\"app/index/".$link_monitoring."\">".$monitoring."</a></li>";
			$breadcrumb = "<li><a href=\"main/index/".$link_monitoring."\">".$monitoring."</a></li>";
			$breadcrumb .= "<li> Kelompok Shift</li>";
		}
		elseif($arrJudul[$max] == "pegawai")
		{		
			$link_monitoring = str_replace("_pegawai", "", $pg);
			$monitoring = str_replace("_", " ", $link_monitoring);
			
			//$breadcrumb = "<li><a href=\"app/index/".$link_monitoring."\">".$monitoring."</a></li>";
			$breadcrumb = "<li><a href=\"main/index/".$link_monitoring."\">".$monitoring."</a></li>";
			$breadcrumb .= "<li> Daftar Pegawai</li>";
		}
		elseif($arrJudul[$max] == "jadwal")
		{		
			if($pg == "permohonan_jadwal_shift_jadwal")
			{
			$link_monitoring = "permohonan_jadwal_shift";
			$monitoring = str_replace("_", " ", $link_monitoring);
			
			//$breadcrumb = "<li><a href=\"app/index/".$link_monitoring."\">".$monitoring."</a></li>";
			$breadcrumb = "<li><a href=\"main/index/".$link_monitoring."\">".$monitoring."</a></li>";
			$breadcrumb .= "<li> Jadwal Shift</li>";
			}
			else
			{		
			$link_monitoring = "permohonan_jadwal_keandalan";
			$monitoring = str_replace("_", " ", $link_monitoring);
			
			//$breadcrumb = "<li><a href=\"app/index/".$link_monitoring."\">".$monitoring."</a></li>";
			$breadcrumb = "<li><a href=\"main/index/".$link_monitoring."\">".$monitoring."</a></li>";
			$breadcrumb .= "<li> Jadwal Keandalan</li>";
			}
		}
		elseif($arrJudul[$max] == "login")
		{}
		else
			$breadcrumb = "<li>".str_replace("_", " ", $pg)."</li>";
		
				
		$data = array(
			'breadcrumb' => $breadcrumb,
			// 'content' => $this->load->view("main/".$pg,$view,TRUE),
			'pg' => $pg,
			'reqParse1' => $reqParse1,
			'reqParse2'	=> $reqParse2,
			'reqParse3'	=> $reqParse3,
			'reqParse4'	=> $reqParse4,
			'reqParse5'	=> $reqParse5
		);	
		
		$this->load->view('main/index', $data);
	}	
	
	public function admin()
	{
		
		if($this->ID == "" && $pg !== "home")
			redirect("app/index/home");
		
		
		$this->load->view('app/index', $data);
	}
	
	public function loadUrl()
	{
		
		$reqFolder = $this->uri->segment(3, "");
		$reqFilename = $this->uri->segment(4, "");
		$reqParse1 = $this->uri->segment(5, "");
		$reqParse2 = $this->uri->segment(6, "");
		$reqParse3 = $this->uri->segment(7, "");
		$reqParse4 = $this->uri->segment(8, "");
		$reqParse5 = $this->uri->segment(9, "");
		$data = array(
			'reqParse1' => urldecode($reqParse1),
			'reqParse2' => urldecode($reqParse2),
			'reqParse3' => urldecode($reqParse3),
			'reqParse4' => urldecode($reqParse4),
			'reqParse5' => urldecode($reqParse5)
		);
		$this->load->view($reqFolder.'/'.$reqFilename, $data);
	}	
	
	public function ubahFotoProfil()
	{
		$reqBrowse = $_FILES['reqBrowse'];
		
		$FILE_DIR = "uploads/";
		
		if($reqBrowse['name'] == "")
		{}
		else			
		{
			$renameFile = $this->USERNAME.".".getExtension($reqBrowse['name']);
			if (move_uploaded_file($reqBrowse['tmp_name'], $FILE_DIR.$renameFile))
			{
				if(createThumbnail($FILE_DIR.$renameFile, $FILE_DIR."profile-".$renameFile, 200, "FIT_HEIGHT"))
					unlink($FILE_DIR.$renameFile);
			}			
		}			
		
		redirect('app/index/login');	
		
	}
	
	function apikalender()
	{
		
		$cacheApi = $this->cache->get('cacheApiKalender'.$this->ID);
		if (!empty($cacheApi)) {
			
			echo $cacheApi;
			return;
		}
		
		
		$this->load->model("AbsensiRekap");
		$absensi_rekap = new AbsensiRekap();
		$absensi_rekap->selectByParamsRekapInformasiKehadiran(array("PEGAWAI_ID" => $this->ID), -1, -1);
		while($absensi_rekap->nextRow())
        {
            $badge = $absensi_rekap->getField("MASUK");
                                            
            if(($absensi_rekap->getField("MASUK") == "" || 
              trim($absensi_rekap->getField("NAMA_HARI")) == "SABTU" || 
              trim($absensi_rekap->getField("NAMA_HARI")) == "MINGGU" || 
              trim($absensi_rekap->getField("NAMA_HARI")) == "SATURDAY" || 
              trim($absensi_rekap->getField("NAMA_HARI")) == "SUNDAY") && $this->KELOMPOK == "N")
            {}
            else
            {
				$arrResult[$absensi_rekap->getField("TANGGAL")]["number"] = "&nbsp;";
				$arrResult[$absensi_rekap->getField("TANGGAL")]["badgeClass"] = $badge;
				$arrResult[$absensi_rekap->getField("TANGGAL")]["keterangan"] = $absensi_rekap->getField("MASUK_KETERANGAN");
				 
            }
        }
		
		$return = json_encode($arrResult);
		// Save into the cache for 10 minutes
		$this->cache->save('cacheApiKalender'.$this->ID, $return, 7200);
		
		echo $return;
		
	}
	
	
	function apikehadirantahun()
	{
		$cacheApi = $this->cache->get('cacheApiKehadiranTahun'.$this->ID);
		if (!empty($cacheApi)) {
			
			echo $cacheApi;
			return;
		}
		
		$this->load->model("AbsensiRekap");
		$absensi_rekap_tahunan = new AbsensiRekap();
		$absensi_rekap_tahunan->selectByParamsRekapInformasiKehadiranTahunan2($this->ID,  date("Y")); 
		$i = 0;
		$total_rekap_tahunan = 0;
		while($absensi_rekap_tahunan->nextRow())
		{
			$arrRekapTahunan[$i]["KETERANGAN"] = $absensi_rekap_tahunan->getField("KETERANGAN");
			$arrRekapTahunan[$i]["WARNA"] = $absensi_rekap_tahunan->getField("WARNA");
			$arrRekapTahunan[$i]["SIMBOL"] = $absensi_rekap_tahunan->getField("SIMBOL");
			$arrRekapTahunan[$i]["JUMLAH"] = $absensi_rekap_tahunan->getField("JUMLAH");
			$i++;
			$total_rekap_tahunan += $absensi_rekap_tahunan->getField("JUMLAH");
		}
		
				
		if($total_rekap_tahunan == 0)
			$total_rekap_tahunan = 1;

		for($i=0;$i<count($arrRekapTahunan);$i++)
		{
			$arrRekapTahunan[$i]["PROSENTASE"] = round(($arrRekapTahunan[$i]["JUMLAH"] / $total_rekap_tahunan) * 100, 2);
			$appendTable .= '
            <div class="skillbar clearfix" data-percent="'.$arrRekapTahunan[$i]["PROSENTASE"].'%">
                <div class="skillbar-title"><span class="'.$arrRekapTahunan[$i]["SIMBOL"].'">'.$arrRekapTahunan[$i]["KETERANGAN"].'</span></div>
                <div class="skillbar-bar" style="background: #'.$arrRekapTahunan[$i]["WARNA"].';"></div>
                <div class="skill-bar-percent">'.$arrRekapTahunan[$i]["JUMLAH"].'</div>
            </div> 
			';
		}
		
		$this->load->model("HariLibur");
		$hari_libur = new HariLibur();
		$totalLibur = $hari_libur->getTotalLiburSetahun(date("Y"));
		if($total_rekap_tahunan == 1)
			$persenLibur = $totalLibur;
		else
			$persenLibur = round(($totalLibur / $total_rekap_tahunan) * 100, 2);
		
		$appendTable .= '
						<div class="skillbar clearfix " data-percent="'.$persenLibur.'%">
							<div class="skillbar-title"><span class="libur">Libur</span></div>
							<div class="skillbar-bar" style="background: #9F4184;"></div>
							<div class="skill-bar-percent">'.$totalLibur.'</div>
						</div>';
						
		$appendTable .= '<div class="clearfix"></div>';
			
		$arrResult["TABEL"] 		= $appendTable;
		
		$return = json_encode($arrResult);
		// Save into the cache for 10 minutes
		$this->cache->save('cacheApiKehadiranTahun'.$this->ID, $return, 7200);
		
		echo $return;
		
	}
	
	
	function apirekapcabang()
	{
		$cacheApi = $this->cache->get('cacheApiRekapCabang'.$this->ID);
		if (!empty($cacheApi)) {
			
			echo $cacheApi;
			return;
		}
		
		
		$this->load->model("Pegawai");
		$pegawai_kehadiran = new Pegawai();

		$statement_cabang = " AND B.CABANG_ID NOT IN ('AK', 'ST', 'MKP', 'UMT', 'UPT', 'UBT', 'SKP', 'TJ', 'UGR', 'UMK', 'BE', 'SP', 'LB', 'PJB', 'PSC') ";
		$pegawai_kehadiran->selectByParamsGrafikPresensi(array(), -1, -1, $statement_cabang);
		$i = 0;
		while($pegawai_kehadiran->nextRow())
		{
			$arrCabang[] 	 = $pegawai_kehadiran->getField("CABANG_ID");
			$arrHadir[]  	 = (int)$pegawai_kehadiran->getField("HADIR");
			$arrTidakHadir[] = (int)$pegawai_kehadiran->getField("ALPHA");
			
			$appendTable .= '
			<tr>
				<td class="headerVer">'.($i+1).'</td>
				<td>'.$pegawai_kehadiran->getField("CABANG_ID").'</td>
				<td class="source-img">'.$pegawai_kehadiran->getField("NAMA").'</td>
				<td>'.$pegawai_kehadiran->getField("JUMLAH_PEGAWAI").'</td>
				<td>'.$pegawai_kehadiran->getField("HADIR").'</td>
				<td><span class="alpha">'.$pegawai_kehadiran->getField("ALPHA").'</span></td>
			</tr>
			';
			
			$i++;	
		}		
		
		$arrResult["CABANG"] 	= $arrCabang;
		$arrResult["HADIR"] 	= $arrHadir;
		$arrResult["TIDAK_HADIR"] 	= $arrTidakHadir;
		$arrResult["TABEL"] 		= $appendTable;
		
		$return = json_encode($arrResult);
		// Save into the cache for 10 minutes
		$this->cache->save('cacheApiRekapCabang'.$this->ID, $return, 7200);
		
		echo $return;
		
	}
	
	
	function apirekapkaryawan()
	{
		$cacheApi = $this->cache->get('cacheApiRekapKaryawan'.$this->ID);
		if (!empty($cacheApi)) {
			
			echo $cacheApi;
			return;
		}
				
				
		$this->load->model("Pegawai");
		$this->load->model("PegawaiStatusPegawai");
		
		$pegawai = new Pegawai();
		$pegawai_status_pegawai = new PegawaiStatusPegawai();
		$pegawai_umur = new Pegawai();
		
		$totalPegawai = $pegawai->getCountByParamsEllipse(array(), " AND A.EMP_STATUS = 'A'");
		$totalPegawai = numberToIna($totalPegawai);
		
		$pegawai->selectByParamsJenisKelamin(array(), -1, -1, " AND A.EMP_STATUS = 'A'");
		while($pegawai->nextRow())
		{
			if($pegawai->getField("JENIS_KELAMIN") == "L")
				$totalLaki = $pegawai->getField("JUMLAH");
			else
				$totalPerempuan = $pegawai->getField("JUMLAH");
		}
		
		$pegawai_status_pegawai->selectByParamsGrafik(array(), -1, -1, " AND C.EMP_STATUS = 'A'");
		$pegawai_status_pegawai->firstRow();
		$totalOrganik = $pegawai_status_pegawai->getField("JUMLAH_ORGANIK");
		$totalNonOrganik = $pegawai_status_pegawai->getField("JUMLAH_NON_ORGANIK");
		
		$pegawai_umur->selectByParamsGrafikUmur(array(), -1, -1, " AND EMP_STATUS = 'A'");
		$i = 0;
		$arrColor   = array("#005cbd", "#034489", "#8c9097", "#ffffff");
		
		$arrPieUmur = array();
		while($pegawai_umur->nextRow())
		{
        	$arrPieUmur[$i]["name"]  = "Usia ".$pegawai_umur->getField("KETERANGAN");
        	$arrPieUmur[$i]["y"] 	 = (float)$pegawai_umur->getField("PROSENTASE");
        	$arrPieUmur[$i]["color"] = $arrColor[$i];

			if($i == 0)
			{
				$arrPieUmur[$i]["sliced"] 	= "true";
				$arrPieUmur[$i]["selected"] = "true";
			}
			
			$appendTable .= '<div class="item">
                                <div class="judul">Usia '.$pegawai_umur->getField("KETERANGAN").'</div>
                                <div class="nilai">'.numberToIna($pegawai_umur->getField("JUMLAH")).'</div>
                            </div>';
			$i++;
		}

		$arrResult["TOTAL_PEGAWAI"] 	= $totalPegawai;
		$arrResult["TOTAL_LAKI"] 		= $totalLaki;
		$arrResult["TOTAL_WANITA"] 		= $totalPerempuan;
		$arrResult["TOTAL_ORGANIK"] 	= $totalOrganik;
		$arrResult["TOTAL_NONORGANIK"] 	= $totalNonOrganik;
		$arrResult["PIE_UMUR"] 			= $arrPieUmur;
		$arrResult["LEGEND_UMUR"] 		= $appendTable;
		
		$return = json_encode($arrResult);
		// Save into the cache for 1 hour
		$this->cache->save('cacheApiRekapKaryawan'.$this->ID, $return, 7200);
		
		echo $return;
		
	}
	

}

