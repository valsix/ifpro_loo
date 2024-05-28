<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Kauth
 *
 * @author user
 */

class suratmasukinfo
{
	// var $USER_LOGIN_ID;

	var $SURAT_MASUK_ID;
	var $TAHUN;
	var $NOMOR;
	var $NO_AGENDA;
	var $TANGGAL;
	var $TANGGAL_DITERUSKAN;
	var $APPROVAL_QR_DATE;
	var $TANGGAL_BATAS;
	var $JENIS;
	var $JENIS_TUJUAN;
	var $PERIHAL;
	var $KLASIFIKASI_ID;
	var $INSTANSI_ASAL;
	var $ALAMAT_ASAL;
	var $KOTA_ASAL;
	var $KETERANGAN_ASAL;
	var $SATUAN_KERJA_ID_TUJUAN;
	var $ISI;
	var $JUMLAH_LAMPIRAN;
	var $CATATAN;
	var $TERBALAS;
	var $TERDISPOSISI;
	var $TERBACA;
	var $TERPARAF;
	var $SATUAN_KERJA_ID_ASAL;
	var $TANGGAL_ENTRI;
	var $USER_ID;
	var $NAMA_USER;
	var $KLASIFIKASI_JENIS;
	var $POSISI_SURAT_MASUK;
	var $JENIS_NASKAH_ID;
	var $SIFAT_NASKAH;
	var $STATUS_SURAT;
	var $BLN_SURAT;
	var $THN_SURAT;
	var $USER_ATASAN_ID;
	var $USER_ATASAN;
	var $USER_ATASAN_JABATAN;
	var $USER_ATASAN_PETIKAN;
	var $TEMBUSAN;
	var $LOKASI_SURAT;
	var $TTD_KODE;
	var $KEPADA_INFO_NEW;
	var $KEPADA;
	var $ALAMAT_UNIT;
	var $TELEPON_UNIT;
	var $FAX_UNIT;
	var $NAMA_UNIT;
	var $LOKASI_UNIT;
	var $JENIS_TTD;

	var $AKSES;
	var $PDF;
	var $TEMPLATE;
	var $STATUS_PARAF;
	var $TERBACA_VALIDASI;

	var $JUMLAH_INBOX;
	var $JUMLAH_VALIDASI;
	var $JUMLAH_DRAFT;
	var $JUMLAH_PERSETUJUAN;
	var $JUMLAH_DRAFT_MANUAL;

	var $JUMLAH_KOTAK_MASUK_SEMUA;
	var $JUMLAH_KOTAK_MASUK_NOTA_DINAS;
	var $JUMLAH_KOTAK_MASUK_SURAT_KELUAR;
	var $JUMLAH_KOTAK_MASUK_SURAT_EDARAN;
	var $JUMLAH_KOTAK_MASUK_SURAT_PERINTAH;
	var $JUMLAH_KOTAK_MASUK_SURAT_KEPUTUSAN_DIREKSI;
	var $JUMLAH_KOTAK_MASUK_KEPUTUSAN_DIREKSI;
	var $JUMLAH_KOTAK_MASUK_INSTRUKSI_DIREKSI;
	var $JUMLAH_KOTAK_MASUK_MANUAL;
	var $JUMLAH_KOTAK_MASUK_SEMUA_TERBACA;
	var $JUMLAH_KOTAK_MASUK_NOTA_DINAS_TERBACA;
	var $JUMLAH_KOTAK_MASUK_MANUAL_TERBACA;
	var $JUMLAH_KOTAK_MASUK_SURAT_KELUAR_TERBACA;
	var $JUMLAH_KOTAK_MASUK_SURAT_EDARAN_TERBACA;
	var $JUMLAH_KOTAK_MASUK_SURAT_PERINTAH_TERBACA;
	var $JUMLAH_KOTAK_MASUK_SURAT_KEPUTUSAN_DIREKSI_TERBACA;
	var $JUMLAH_KOTAK_MASUK_KEPUTUSAN_DIREKSI_TERBACA;
	var $JUMLAH_KOTAK_MASUK_INSTRUKSI_DIREKSI_TERBACA;

	var $FOLDER_PATH;

	var $KD_SURAT;
	var $KELOMPOK_JABATAN;
	var $KOTA_TUJUAN;
	var $DASAR;
	var $DIPERINTAHKAN_KEPADA;
	var $ISI_PERINTAH;
	var $LAIN_LAIN;
	var $EKSTERNAL_KEPADA_ID;
	var $EKSTERNAL_KEPADA;
	var $EKSTERNAL_TEMBUSAN_ID;
	var $EKSTERNAL_TEMBUSAN;




	/******************** CONSTRUCTOR **************************************/
	function suratmasukinfo()
	{

		$this->emptyProps();
	}

	/******************** METHODS ************************************/
	/** Empty the properties **/
	function emptyProps()
	{
		$this->SURAT_MASUK_ID = "";
		$this->TAHUN = "";
		$this->NOMOR = "";
		$this->NO_AGENDA = "";
		$this->TANGGAL = "";
		$this->TANGGAL_DITERUSKAN = "";
		$this->APPROVAL_QR_DATE = "";
		$this->TANGGAL_BATAS = "";
		$this->JENIS = "";
		$this->JENIS_TUJUAN = "";
		$this->PERIHAL = "";
		$this->KLASIFIKASI_ID = "";
		$this->INSTANSI_ASAL = "";
		$this->ALAMAT_ASAL = "";
		$this->KOTA_ASAL = "";
		$this->KETERANGAN_ASAL = "";
		$this->SATUAN_KERJA_ID_TUJUAN = "";
		$this->ISI = "";
		$this->JUMLAH_LAMPIRAN = "";
		$this->CATATAN = "";
		$this->TERBALAS = "";
		$this->TERDISPOSISI = "";
		$this->TERBACA = "";
		$this->TERPARAF = "";
		$this->SATUAN_KERJA_ID_ASAL = "";
		$this->TANGGAL_ENTRI = "";
		$this->USER_ID = "";
		$this->NAMA_USER = "";
		$this->KLASIFIKASI_JENIS = "";
		$this->POSISI_SURAT_MASUK = "";
		$this->JENIS_NASKAH_ID = "";
		$this->SIFAT_NASKAH = "";
		$this->STATUS_SURAT = "";
		$this->BLN_SURAT = "";
		$this->THN_SURAT = "";
		$this->USER_ATASAN_ID = "";
		$this->USER_ATASAN = "";
		$this->USER_ATASAN_JABATAN = "";
		$this->USER_ATASAN_PETIKAN = "";
		$this->TEMBUSAN = "";
		$this->LOKASI_SURAT = "";
		$this->TTD_KODE = "";
		$this->KEPADA_INFO_NEW = "";
		$this->KEPADA = "";
		$this->ALAMAT_UNIT = "";
		$this->TELEPON_UNIT = "";
		$this->FAX_UNIT = "";
		$this->NAMA_UNIT = "";
		$this->LOKASI_UNIT = "";
		$this->KD_SURAT = "";
		$this->KODE_UNIT = "";
		$this->JENIS_TTD = "";
		$this->KELOMPOK_JABATAN = "";



		$this->AKSES = "";
		$this->TERBACA = "";
		$this->TERBALAS = "";
		$this->TERDISPOSISI = "";
		$this->STATUS_PARAF = "";
		$this->TERBACA_VALIDASI = "";
		$this->STATUS_SURAT = "";

		$this->PDF = "";
		$this->TEMPLATE = "";

		$this->JUMLAH_INBOX = "0";
		$this->JUMLAH_VALIDASI = "0";
		$this->JUMLAH_DRAFT = "0";
		$this->JUMLAH_PERSETUJUAN = "0";
		$this->JUMLAH_DRAFT_MANUAL = "0";

		$this->JUMLAH_KOTAK_MASUK_SEMUA = "0";
		$this->JUMLAH_KOTAK_MASUK_NOTA_DINAS = "0";
		$this->JUMLAH_KOTAK_MASUK_SURAT_KELUAR = "0";
		$this->JUMLAH_KOTAK_MASUK_SURAT_EDARAN = "0";
		$this->JUMLAH_KOTAK_MASUK_SURAT_PERINTAH = "0";
		$this->JUMLAH_KOTAK_MASUK_SURAT_KEPUTUSAN_DIREKSI = "0";
		$this->JUMLAH_KOTAK_MASUK_KEPUTUSAN_DIREKSI = "0";
		$this->JUMLAH_KOTAK_MASUK_INSTRUKSI_DIREKSI = "0";
		$this->JUMLAH_KOTAK_MASUK_MANUAL = "0";
		$this->JUMLAH_KOTAK_MASUK_SEMUA_TERBACA = "0";
		$this->JUMLAH_KOTAK_MASUK_NOTA_DINAS_TERBACA = "0";
		$this->JUMLAH_KOTAK_MASUK_MANUAL_TERBACA = "0";
		$this->JUMLAH_KOTAK_MASUK_SURAT_KELUAR_TERBACA = "0";
		$this->JUMLAH_KOTAK_MASUK_SURAT_EDARAN_TERBACA = "0";
		$this->JUMLAH_KOTAK_MASUK_SURAT_PERINTAH_TERBACA = "0";
		$this->JUMLAH_KOTAK_MASUK_SURAT_KEPUTUSAN_DIREKSI_TERBACA = "0";
		$this->JUMLAH_KOTAK_MASUK_KEPUTUSAN_DIREKSI_TERBACA = "0";
		$this->JUMLAH_KOTAK_MASUK_INSTRUKSI_DIREKSI_TERBACA = "0";


		$this->FOLDER_PATH = "";
		$this->AN_STATUS = "";
		$this->AN_NAMA = "";
		$this->KOTA_TUJUAN = "";
		$this->DASAR = "";
		$this->DIPERINTAHKAN_KEPADA = "";
		$this->ISI_PERINTAH = "";
		$this->LAIN_LAIN = "";
		$this->EKSTERNAL_KEPADA_ID = "";
		$this->EKSTERNAL_KEPADA = "";
		$this->EKSTERNAL_TEMBUSAN_ID = "";
		$this->EKSTERNAL_TEMBUSAN = "";
	}

	function infolinkdetil($jenisid)
	{
		$arrdata= [];
		if($jenisid == "1")
		{
			$arrdata= array(
				"linkstatusdetil"=>"surat_masuk_manual_lihat"
				, "linkstatusedit"=>"surat_masuk_manual_add"
			);
		}
		else if($jenisid == "2")
		{
			$arrdata= array(
				"linkstatusdetil"=>"nota_dinas_lihat"
				, "linkstatusedit"=>"nota_dinas_add"
			);
		}
		else if($jenisid == "8")
		{
			$arrdata= array(
				"linkstatusdetil"=>"keputusan_direksi_lihat"
				, "linkstatusedit"=>"keputusan_direksi_add"
			);
		}
		else if($jenisid == "13")
		{
			$arrdata= array(
				"linkstatusdetil"=>"surat_edaran_lihat"
				, "linkstatusedit"=>"surat_edaran_add"
			);
		}
		else if($jenisid == "15")
		{
			$arrdata= array(
				"linkstatusdetil"=>"surat_keluar_lihat"
				, "linkstatusedit"=>"surat_keluar_add"
			);
		}
		else if($jenisid == "17")
		{
			$arrdata= array(
				"linkstatusdetil"=>"surat_keputusan_direksi_lihat"
				, "linkstatusedit"=>"surat_keputusan_direksi_add"
			);
		}
		else if($jenisid == "18")
		{
			$arrdata= array(
				"linkstatusdetil"=>"surat_perintah_lihat"
				, "linkstatusedit"=>"surat_perintah_add"
			);
		}
		else if($jenisid == "19")
		{
			$arrdata= array(
				"linkstatusdetil"=>"instruksi_direksi_lihat"
				, "linkstatusedit"=>"instruksi_direksi_add"
			);
		}
		return $arrdata;
	}

	/** Verify user login. True when login is valid**/
	function getInfo($suratMasukId, $jenisSurat = "INTERNAL")
	{

		$CI = &get_instance();
		$CI->load->model("SuratMasuk");
		$CI->load->model("SuratKeluar");

		if ($jenisSurat == "INTERNAL") {
			$surat_masuk = new SuratMasuk();
			$surat_masuk->selectByParamsSurat(array("A.SURAT_MASUK_ID" => $suratMasukId));
			$this->FOLDER_PATH = "uploads";
		} else {
			$surat_masuk = new SuratMasuk();
			$surat_masuk->selectByParamsSurat(array("A.SURAT_MASUK_ID" => $suratMasukId));
			//$surat_masuk = new SuratKeluar();
			//$surat_masuk->selectByParamsSurat(array("A.SURAT_KELUAR_ID" => $suratMasukId));	
			//$this->FOLDER_PATH = "uploads/eksternal";
			$this->FOLDER_PATH = "uploads";
		}
		// echo $surat_masuk->query;exit;
		if ($surat_masuk->firstRow()) {

			$this->SURAT_MASUK_ID = $surat_masuk->getField("SURAT_MASUK_ID");
			$this->TAHUN = $surat_masuk->getField("TAHUN");
			$infonomor= $surat_masuk->getField("NOMOR");
			if(empty($infonomor))
				$infonomor= $surat_masuk->getField("INFO_NOMOR_SURAT");
			$this->NOMOR = $infonomor;
			
			$this->NO_AGENDA = $surat_masuk->getField("NO_AGENDA");
			$this->TANGGAL = $surat_masuk->getField("TANGGAL");
			$this->TANGGAL_DITERUSKAN = $surat_masuk->getField("TANGGAL_DITERUSKAN");
			$this->APPROVAL_QR_DATE = $surat_masuk->getField("APPROVAL_QR_DATE");
			$this->TANGGAL_BATAS = $surat_masuk->getField("TANGGAL_BATAS");
			$this->JENIS = $surat_masuk->getField("JENIS");
			$this->JENIS_TUJUAN = $surat_masuk->getField("JENIS_TUJUAN");
			$this->KEPADA = $surat_masuk->getField("KEPADA");
			$this->PERIHAL = $surat_masuk->getField("PERIHAL");
			$this->KLASIFIKASI_ID = $surat_masuk->getField("KLASIFIKASI_ID");
			$this->INSTANSI_ASAL = $surat_masuk->getField("INSTANSI_ASAL");
			$this->ALAMAT_ASAL = $surat_masuk->getField("ALAMAT_ASAL");
			$this->KOTA_ASAL = $surat_masuk->getField("KOTA_ASAL");
			$this->KETERANGAN_ASAL = $surat_masuk->getField("KETERANGAN_ASAL");
			$this->SATUAN_KERJA_ID_TUJUAN = $surat_masuk->getField("SATUAN_KERJA_ID_TUJUAN");
			$this->ISI = $surat_masuk->getField("ISI");
			$this->JUMLAH_LAMPIRAN = $surat_masuk->getField("JUMLAH_LAMPIRAN");
			$this->CATATAN = $surat_masuk->getField("CATATAN");
			$this->TERBALAS = $surat_masuk->getField("TERBALAS");
			$this->TERDISPOSISI = $surat_masuk->getField("TERDISPOSISI");
			$this->TERBACA = $surat_masuk->getField("TERBACA");
			$this->TERPARAF = $surat_masuk->getField("TERPARAF");
			$this->SATUAN_KERJA_ID_ASAL = $surat_masuk->getField("SATUAN_KERJA_ID_ASAL");
			$this->TANGGAL_ENTRI = $surat_masuk->getField("TANGGAL_ENTRI");
			$this->USER_ID = $surat_masuk->getField("USER_ID");
			$this->NAMA_USER = $surat_masuk->getField("NAMA_USER");
			$this->KLASIFIKASI_JENIS = $surat_masuk->getField("KLASIFIKASI_JENIS");
			$this->POSISI_SURAT_MASUK = $surat_masuk->getField("POSISI_SURAT_MASUK");
			$this->JENIS_NASKAH_ID = $surat_masuk->getField("JENIS_NASKAH_ID");
			$this->SIFAT_NASKAH = $surat_masuk->getField("SIFAT_NASKAH");
			$this->STATUS_SURAT = $surat_masuk->getField("STATUS_SURAT");
			$this->BLN_SURAT = $surat_masuk->getField("BLN_SURAT");
			$this->THN_SURAT = $surat_masuk->getField("THN_SURAT");
			$this->USER_ATASAN_ID = $surat_masuk->getField("USER_ATASAN_ID");
			$this->USER_ATASAN_JABATAN = $surat_masuk->getField("USER_ATASAN_JABATAN");
			$this->USER_ATASAN = $surat_masuk->getField("USER_ATASAN");
			$this->TEMBUSAN = $surat_masuk->getField("TEMBUSAN");
			$this->LOKASI_SURAT = $surat_masuk->getField("LOKASI_SURAT");
			$this->TTD_KODE = $surat_masuk->getField("TTD_KODE");
			$this->ALAMAT_UNIT = $surat_masuk->getField("ALAMAT_UNIT");
			$this->TELEPON_UNIT = $surat_masuk->getField("TELEPON_UNIT");
			$this->FAX_UNIT = $surat_masuk->getField("FAX_UNIT");
			$this->NAMA_UNIT = $surat_masuk->getField("NAMA_UNIT");
			$this->LOKASI_UNIT = $surat_masuk->getField("LOKASI_UNIT");
			$this->KD_SURAT = $surat_masuk->getField("KD_SURAT");
			$this->KODE_UNIT = $surat_masuk->getField("KODE_UNIT");
			$this->JENIS_TTD = $surat_masuk->getField("JENIS_TTD");
			$this->KELOMPOK_JABATAN = $surat_masuk->getField("KELOMPOK_JABATAN");
			$this->AN_STATUS = $surat_masuk->getField("AN_STATUS");
			$this->AN_NAMA = $surat_masuk->getField("AN_NAMA");
			$this->KOTA_TUJUAN = $surat_masuk->getField("KOTA_TUJUAN");
			$this->DASAR = $surat_masuk->getField("DASAR");
			$this->DIPERINTAHKAN_KEPADA = $surat_masuk->getField("DIPERINTAHKAN_KEPADA");
			$this->ISI_PERINTAH = $surat_masuk->getField("ISI_PERINTAH");
			$this->LAIN_LAIN = $surat_masuk->getField("LAIN_LAIN");
			$this->EKSTERNAL_KEPADA_ID = $surat_masuk->getField("EKSTERNAL_KEPADA_ID");
			$this->EKSTERNAL_KEPADA = $surat_masuk->getField("EKSTERNAL_KEPADA");
			$this->EKSTERNAL_TEMBUSAN_ID = $surat_masuk->getField("EKSTERNAL_TEMBUSAN_ID");
			$this->EKSTERNAL_TEMBUSAN = $surat_masuk->getField("EKSTERNAL_TEMBUSAN");
		}
	}


	/** Verify user login. True when login is valid**/
	function getInfoAsc($suratMasukId, $jenisSurat = "INTERNAL")
	{

		$CI = &get_instance();
		$CI->load->model("SuratMasuk");
		$CI->load->model("SuratKeluar");

		if ($jenisSurat == "INTERNAL") {
			$surat_masuk = new SuratMasuk();
			$surat_masuk->selectByParamsSuratAsc(array("A.SURAT_MASUK_ID" => $suratMasukId));
			$this->FOLDER_PATH = "uploads";
		} else {
			$surat_masuk = new SuratMasuk();
			$surat_masuk->selectByParamsSuratAsc(array("A.SURAT_MASUK_ID" => $suratMasukId));
			//$surat_masuk = new SuratKeluar();
			//$surat_masuk->selectByParamsSurat(array("A.SURAT_KELUAR_ID" => $suratMasukId));	
			//$this->FOLDER_PATH = "uploads/eksternal";
			$this->FOLDER_PATH = "uploads";
		}
		// echo $surat_masuk->query;exit;
		if ($surat_masuk->firstRow()) {

			$this->SURAT_MASUK_ID = $surat_masuk->getField("SURAT_MASUK_ID");
			$this->TAHUN = $surat_masuk->getField("TAHUN");
			$infonomor= $surat_masuk->getField("NOMOR");
			if(empty($infonomor))
				$infonomor= $surat_masuk->getField("INFO_NOMOR_SURAT");
			$this->NOMOR = $infonomor;
			
			$this->NO_AGENDA = $surat_masuk->getField("NO_AGENDA");
			$this->TANGGAL = $surat_masuk->getField("TANGGAL");
			$this->TANGGAL_DITERUSKAN = $surat_masuk->getField("TANGGAL_DITERUSKAN");
			$this->APPROVAL_QR_DATE = $surat_masuk->getField("APPROVAL_QR_DATE");
			$this->TANGGAL_BATAS = $surat_masuk->getField("TANGGAL_BATAS");
			$this->JENIS = $surat_masuk->getField("JENIS");
			$this->JENIS_TUJUAN = $surat_masuk->getField("JENIS_TUJUAN");
			$this->KEPADA_INFO_NEW = $surat_masuk->getField("KEPADA_INFO_NEW");
			$this->KEPADA = $surat_masuk->getField("KEPADA");
			$this->PERIHAL = $surat_masuk->getField("PERIHAL");
			$this->KLASIFIKASI_ID = $surat_masuk->getField("KLASIFIKASI_ID");
			$this->INSTANSI_ASAL = $surat_masuk->getField("INSTANSI_ASAL");
			$this->ALAMAT_ASAL = $surat_masuk->getField("ALAMAT_ASAL");
			$this->KOTA_ASAL = $surat_masuk->getField("KOTA_ASAL");
			$this->KETERANGAN_ASAL = $surat_masuk->getField("KETERANGAN_ASAL");
			$this->SATUAN_KERJA_ID_TUJUAN = $surat_masuk->getField("SATUAN_KERJA_ID_TUJUAN");
			$this->ISI = $surat_masuk->getField("ISI");
			$this->JUMLAH_LAMPIRAN = $surat_masuk->getField("JUMLAH_LAMPIRAN");
			$this->CATATAN = $surat_masuk->getField("CATATAN");
			$this->TERBALAS = $surat_masuk->getField("TERBALAS");
			$this->TERDISPOSISI = $surat_masuk->getField("TERDISPOSISI");
			$this->TERBACA = $surat_masuk->getField("TERBACA");
			$this->TERPARAF = $surat_masuk->getField("TERPARAF");
			$this->SATUAN_KERJA_ID_ASAL = $surat_masuk->getField("SATUAN_KERJA_ID_ASAL");
			$this->TANGGAL_ENTRI = $surat_masuk->getField("TANGGAL_ENTRI");
			$this->USER_ID = $surat_masuk->getField("USER_ID");
			$this->NAMA_USER = $surat_masuk->getField("NAMA_USER");
			$this->KLASIFIKASI_JENIS = $surat_masuk->getField("KLASIFIKASI_JENIS");
			$this->POSISI_SURAT_MASUK = $surat_masuk->getField("POSISI_SURAT_MASUK");
			$this->JENIS_NASKAH_ID = $surat_masuk->getField("JENIS_NASKAH_ID");
			$this->SIFAT_NASKAH = $surat_masuk->getField("SIFAT_NASKAH");
			$this->STATUS_SURAT = $surat_masuk->getField("STATUS_SURAT");
			$this->BLN_SURAT = $surat_masuk->getField("BLN_SURAT");
			$this->THN_SURAT = $surat_masuk->getField("THN_SURAT");
			$this->USER_ATASAN_ID = $surat_masuk->getField("USER_ATASAN_ID");
			$this->USER_ATASAN_JABATAN = $surat_masuk->getField("USER_ATASAN_JABATAN");
			$this->USER_ATASAN = $surat_masuk->getField("USER_ATASAN");
			$this->USER_ATASAN_PETIKAN = $surat_masuk->getField("USER_ATASAN_PETIKAN");
			$this->TEMBUSAN = $surat_masuk->getField("TEMBUSAN");
			$this->LOKASI_SURAT = $surat_masuk->getField("LOKASI_SURAT");
			$this->TTD_KODE = $surat_masuk->getField("TTD_KODE");
			$this->ALAMAT_UNIT = $surat_masuk->getField("ALAMAT_UNIT");
			$this->TELEPON_UNIT = $surat_masuk->getField("TELEPON_UNIT");
			$this->FAX_UNIT = $surat_masuk->getField("FAX_UNIT");
			$this->NAMA_UNIT = $surat_masuk->getField("NAMA_UNIT");
			$this->LOKASI_UNIT = $surat_masuk->getField("LOKASI_UNIT");
			$this->KD_SURAT = $surat_masuk->getField("KD_SURAT");
			$this->KODE_UNIT = $surat_masuk->getField("KODE_UNIT");
			$this->JENIS_TTD = $surat_masuk->getField("JENIS_TTD");
			$this->KELOMPOK_JABATAN = $surat_masuk->getField("KELOMPOK_JABATAN");
			$this->AN_STATUS = $surat_masuk->getField("AN_STATUS");
			$this->AN_NAMA = $surat_masuk->getField("AN_NAMA");
			$this->KOTA_TUJUAN = $surat_masuk->getField("KOTA_TUJUAN");
			$this->DASAR = $surat_masuk->getField("DASAR");
			$this->DIPERINTAHKAN_KEPADA = $surat_masuk->getField("DIPERINTAHKAN_KEPADA");
			$this->ISI_PERINTAH = $surat_masuk->getField("ISI_PERINTAH");
			$this->LAIN_LAIN = $surat_masuk->getField("LAIN_LAIN");
			$this->EKSTERNAL_KEPADA_ID = $surat_masuk->getField("EKSTERNAL_KEPADA_ID");
			$this->EKSTERNAL_KEPADA = $surat_masuk->getField("EKSTERNAL_KEPADA");
			$this->EKSTERNAL_TEMBUSAN_ID = $surat_masuk->getField("EKSTERNAL_TEMBUSAN_ID");
			$this->EKSTERNAL_TEMBUSAN = $surat_masuk->getField("EKSTERNAL_TEMBUSAN");
		}
	}


	/** Verify user login. True when login is valid**/
	function getAkses($suratMasukId, $userId, $infodivisi="", $infogantijabatan= "")
	{
		$CI = &get_instance();

		$CI = &get_instance();
		$CI->load->model("SuratMasuk");

		if(in_array("SURAT", explode(",", $CI->USER_GROUP)))
		{
			$statement= " AND 
			(
				EXISTS
				(
					SELECT 1
					FROM
					(
						SELECT B.PEGAWAI_ID
						FROM USER_LOGIN A
						INNER JOIN PEGAWAI B ON A.PEGAWAI_ID = B.PEGAWAI_ID
						WHERE 
						B.SATUAN_KERJA_ID LIKE '".$CI->CABANG_ID."%'
					) X WHERE X.PEGAWAI_ID = A.USER_ID
				)
				OR
				A.USER_ID LIKE '".$CI->CABANG_ID."%'
			)
			";
		}
		else
		{
			$statement= " AND A.USER_ID = '".$userId."'";
		}

		if(!empty($infodivisi))
		{
			$userdivisiId= $userId= $CI->NIP_BY_DIVISI;
			$userId= str_replace("', '", "xx, xx", $userId);
			$userId= str_replace("'", "", $userId);
			$userId= str_replace("xx, xx", "'', ''", $userId);
			// echo $userId;exit;
			/*if(in_array("DIVISI", explode(",", $CI->NIP_BY_DIVISI))) 
		    {
		    	$userId= $infodivisi;
		    	$statement= " AND A.USER_ID IN (".$userId.")";
		    }*/
		    $statement= " AND A.USER_ID IN (".$userdivisiId.")";
		}

		if(!empty($infogantijabatan))
		{
			$statement= " AND
			EXISTS
			(
				SELECT 1
				FROM
				(
					SELECT SURAT_MASUK_ID
					FROM
					(
						SELECT SURAT_MASUK_ID
						FROM disposisi B
						WHERE B.SATUAN_KERJA_ID_TUJUAN = '".$infogantijabatan."' AND B.SURAT_MASUK_ID = ".$suratMasukId." AND B.DISPOSISI_PARENT_ID = 0
						GROUP BY SURAT_MASUK_ID
						UNION ALL
						SELECT SURAT_MASUK_ID
						FROM disposisi B
						WHERE B.SATUAN_KERJA_ID_TUJUAN = '".$infogantijabatan."' AND B.SURAT_MASUK_ID = ".$suratMasukId."  
						GROUP BY SURAT_MASUK_ID
						UNION ALL
						SELECT SURAT_MASUK_ID
						FROM surat_masuk B
						WHERE B.SATUAN_KERJA_ID_ASAL = '".$infogantijabatan."' AND B.SURAT_MASUK_ID = ".$suratMasukId."
						GROUP BY SURAT_MASUK_ID
						UNION ALL
						SELECT SURAT_MASUK_ID
						FROM surat_masuk_paraf B
						WHERE B.SATUAN_KERJA_ID_TUJUAN = '".$infogantijabatan."' AND B.SURAT_MASUK_ID = ".$suratMasukId."
						GROUP BY SURAT_MASUK_ID
					) A
					GROUP BY SURAT_MASUK_ID
				) X WHERE A.SURAT_MASUK_ID = X.SURAT_MASUK_ID
			)";
		}

		$surat_masuk = new SuratMasuk();
		// $surat_masuk->selectByParamsAkses(array("A.SURAT_MASUK_ID" => $suratMasukId), -1, -1, $statement);
		// $surat_masuk->selectByParamsNewAkses(array(), -1, -1, $suratMasukId, $statement);
		$surat_masuk->selectByParamsNewAkses(array(), -1, -1, $suratMasukId, $userId, $statement);
		// echo $surat_masuk->query;exit;
		// echo explode(",", $CI->USER_GROUP);
		$surat_masuk->firstRow();

		$this->AKSES = $surat_masuk->getField("AKSES");
		$this->PDF = $surat_masuk->getField("SURAT_PDF");
		$this->TEMPLATE = $surat_masuk->getField("TEMPLATE_SURAT");
		$this->TERBACA = $surat_masuk->getField("TERBACA");
		$this->TERBALAS = $surat_masuk->getField("TERBALAS");
		$this->TERDISPOSISI = $surat_masuk->getField("TERDISPOSISI");
		$this->STATUS_PARAF = $surat_masuk->getField("STATUS_PARAF");
		$this->TERBACA_VALIDASI = $surat_masuk->getField("TERBACA_VALIDASI");
		$this->STATUS_SURAT = $surat_masuk->getField("STATUS_SURAT");
	}


	/** Verify user login. True when login is valid**/
	function getJumlahSurat($userId, $userGroup, $cabangId)
	{
		$CI = &get_instance();

		$CI->load->model("SuratMasuk");

		$surat_masuk = new SuratMasuk();
		$surat_masuk->selectByParamsJumlahSurat($userId, $userGroup, $cabangId);
		// echo $surat_masuk->query;exit;
		$surat_masuk->firstRow();

		$this->JUMLAH_INBOX = $surat_masuk->getField("JUMLAH_INBOX");
		$this->JUMLAH_VALIDASI = $surat_masuk->getField("JUMLAH_VALIDASI");
		$this->JUMLAH_DRAFT = $surat_masuk->getField("JUMLAH_DRAFT");
		$this->JUMLAH_PERSETUJUAN = $surat_masuk->getField("JUMLAH_PERSETUJUAN");
		$this->JUMLAH_DRAFT_MANUAL = $surat_masuk->getField("JUMLAH_DRAFT_MANUAL");
	}

	function getModifJumlahSurat($userId, $userGroup, $cabangId, $useridatasan, $userkelompok)
	{
		$CI = &get_instance();

		$CI->load->model("SuratMasuk");

		$surat_masuk = new SuratMasuk();
		$surat_masuk->selectByParamsModifJumlahSurat($userId, $userGroup, $cabangId, $useridatasan, $userkelompok);
		 // echo $surat_masuk->query;
		$surat_masuk->firstRow();

		$this->JUMLAH_INBOX = $surat_masuk->getField("JUMLAH_INBOX");
		$this->JUMLAH_VALIDASI = $surat_masuk->getField("JUMLAH_VALIDASI");
		$this->JUMLAH_DRAFT = $surat_masuk->getField("JUMLAH_DRAFT");
		$this->JUMLAH_PERSETUJUAN = $surat_masuk->getField("JUMLAH_PERSETUJUAN");
		$this->JUMLAH_DRAFT_MANUAL = $surat_masuk->getField("JUMLAH_DRAFT_MANUAL");

		$surat_masuk = new SuratMasuk();
		$surat_masuk->selectByParamsDataJumlahSurat($userId, $userGroup, $cabangId, $useridatasan, $userkelompok);
		// echo $surat_masuk->query;exit;
		$JUMLAH_KOTAK_MASUK_SEMUA= $JUMLAH_KOTAK_MASUK_NOTA_DINAS= $JUMLAH_KOTAK_MASUK_SURAT_KELUAR= $JUMLAH_KOTAK_MASUK_SURAT_EDARAN= $JUMLAH_KOTAK_MASUK_SURAT_PERINTAH= $JUMLAH_KOTAK_MASUK_SURAT_KEPUTUSAN_DIREKSI= $JUMLAH_KOTAK_MASUK_KEPUTUSAN_DIREKSI= $JUMLAH_KOTAK_MASUK_INSTRUKSI_DIREKSI= $JUMLAH_KOTAK_MASUK_MANUAL= 0;
		while($surat_masuk->nextRow())
		{
			$reqJenisNaskahId= $surat_masuk->getField("JENIS_NASKAH_ID");

			if($reqJenisNaskahId == "1")
			    $linkUbah = "surat_masuk_manual_add";
			else if($reqJenisNaskahId == "2")
			    $JUMLAH_KOTAK_MASUK_NOTA_DINAS++;
			else if($reqJenisNaskahId == "13")
				$JUMLAH_KOTAK_MASUK_SURAT_EDARAN++;
			else if($reqJenisNaskahId == "15")
			    $JUMLAH_KOTAK_MASUK_SURAT_KELUAR++;
			else if($reqJenisNaskahId == "18")
			    $JUMLAH_KOTAK_MASUK_SURAT_PERINTAH++;
			else if($reqJenisNaskahId == "17")
			    $JUMLAH_KOTAK_MASUK_SURAT_KEPUTUSAN_DIREKSI++;
			else if($reqJenisNaskahId == "8")
			    $JUMLAH_KOTAK_MASUK_KEPUTUSAN_DIREKSI++;
			else if($reqJenisNaskahId == "19")
			    $JUMLAH_KOTAK_MASUK_INSTRUKSI_DIREKSI++;
			// else if($reqJenisNaskahId == "20")
			//     $linkUbah = "petikan_skd_add";

			$JUMLAH_KOTAK_MASUK_SEMUA++;
		}

		$this->JUMLAH_KOTAK_MASUK_SEMUA = $JUMLAH_KOTAK_MASUK_SEMUA;
		$this->JUMLAH_KOTAK_MASUK_NOTA_DINAS = $JUMLAH_KOTAK_MASUK_NOTA_DINAS;
		$this->JUMLAH_KOTAK_MASUK_SURAT_KELUAR = $JUMLAH_KOTAK_MASUK_SURAT_KELUAR;
		$this->JUMLAH_KOTAK_MASUK_SURAT_EDARAN = $JUMLAH_KOTAK_MASUK_SURAT_EDARAN;
		$this->JUMLAH_KOTAK_MASUK_SURAT_PERINTAH = $JUMLAH_KOTAK_MASUK_SURAT_PERINTAH;
		$this->JUMLAH_KOTAK_MASUK_SURAT_KEPUTUSAN_DIREKSI = $JUMLAH_KOTAK_MASUK_SURAT_KEPUTUSAN_DIREKSI;
		$this->JUMLAH_KOTAK_MASUK_KEPUTUSAN_DIREKSI = $JUMLAH_KOTAK_MASUK_KEPUTUSAN_DIREKSI;
		$this->JUMLAH_KOTAK_MASUK_INSTRUKSI_DIREKSI = $JUMLAH_KOTAK_MASUK_INSTRUKSI_DIREKSI;
		$this->JUMLAH_KOTAK_MASUK_MANUAL = $JUMLAH_KOTAK_MASUK_MANUAL;

		// $this->JUMLAH_KOTAK_MASUK_SEMUA = $surat_masuk->getField("JUMLAH_KOTAK_MASUK_SEMUA");
		// $this->JUMLAH_KOTAK_MASUK_NOTA_DINAS = $surat_masuk->getField("JUMLAH_KOTAK_MASUK_NOTA_DINAS");
		// $this->JUMLAH_KOTAK_MASUK_SURAT_KELUAR = $surat_masuk->getField("JUMLAH_KOTAK_MASUK_SURAT_KELUAR");
		// $this->JUMLAH_KOTAK_MASUK_SURAT_EDARAN = $surat_masuk->getField("JUMLAH_KOTAK_MASUK_SURAT_EDARAN");
		// $this->JUMLAH_KOTAK_MASUK_SURAT_PERINTAH = $surat_masuk->getField("JUMLAH_KOTAK_MASUK_SURAT_PERINTAH");
		// $this->JUMLAH_KOTAK_MASUK_SURAT_KEPUTUSAN_DIREKSI = $surat_masuk->getField("JUMLAH_KOTAK_MASUK_SURAT_KEPUTUSAN_DIREKSI");
		// $this->JUMLAH_KOTAK_MASUK_KEPUTUSAN_DIREKSI = $surat_masuk->getField("JUMLAH_KOTAK_MASUK_KEPUTUSAN_DIREKSI");
		// $this->JUMLAH_KOTAK_MASUK_INSTRUKSI_DIREKSI = $surat_masuk->getField("JUMLAH_KOTAK_MASUK_INSTRUKSI_DIREKSI");
		// $this->JUMLAH_KOTAK_MASUK_MANUAL = $surat_masuk->getField("JUMLAH_KOTAK_MASUK_MANUAL");

		// $this->JUMLAH_KOTAK_MASUK_SEMUA_TERBACA = $surat_masuk->getField("JUMLAH_KOTAK_MASUK_SEMUA_TERBACA");
		// $this->JUMLAH_KOTAK_MASUK_NOTA_DINAS_TERBACA = $surat_masuk->getField("JUMLAH_KOTAK_MASUK_NOTA_DINAS_TERBACA");
		// $this->JUMLAH_KOTAK_MASUK_MANUAL_TERBACA = $surat_masuk->getField("JUMLAH_KOTAK_MASUK_MANUAL_TERBACA");
		// $this->JUMLAH_KOTAK_MASUK_SURAT_KELUAR_TERBACA = $surat_masuk->getField("JUMLAH_KOTAK_MASUK_SURAT_KELUAR_TERBACA");
		// $this->JUMLAH_KOTAK_MASUK_SURAT_EDARAN_TERBACA = $surat_masuk->getField("JUMLAH_KOTAK_MASUK_SURAT_EDARAN_TERBACA");
		// $this->JUMLAH_KOTAK_MASUK_SURAT_PERINTAH_TERBACA = $surat_masuk->getField("JUMLAH_KOTAK_MASUK_SURAT_PERINTAH_TERBACA");
		// $this->JUMLAH_KOTAK_MASUK_SURAT_KEPUTUSAN_DIREKSI_TERBACA = $surat_masuk->getField("JUMLAH_KOTAK_MASUK_SURAT_KEPUTUSAN_DIREKSI_TERBACA");
		// $this->JUMLAH_KOTAK_MASUK_KEPUTUSAN_DIREKSI_TERBACA = $surat_masuk->getField("JUMLAH_KOTAK_MASUK_KEPUTUSAN_DIREKSI_TERBACA");
		// $this->JUMLAH_KOTAK_MASUK_INSTRUKSI_DIREKSI_TERBACA = $surat_masuk->getField("JUMLAH_KOTAK_MASUK_INSTRUKSI_DIREKSI_TERBACA");
	}

	function getnewjumlahsurat($userId, $userGroup, $cabangId, $useridatasan, $userkelompok, $infogantijabatan)
	{
		$CI = &get_instance();

		$CI->load->model("SuratMasuk");

		$surat_masuk = new SuratMasuk();
		$surat_masuk->selectByParamsModifJumlahSurat($userId, $userGroup, $cabangId, $useridatasan, $userkelompok);
		// echo $surat_masuk->query;exit;
		$surat_masuk->firstRow();

		$this->JUMLAH_INBOX = $surat_masuk->getField("JUMLAH_INBOX");
		$this->JUMLAH_VALIDASI = $surat_masuk->getField("JUMLAH_VALIDASI");
		$this->JUMLAH_DRAFT = $surat_masuk->getField("JUMLAH_DRAFT");
		// $this->JUMLAH_PERSETUJUAN = $surat_masuk->getField("JUMLAH_PERSETUJUAN");
		$this->JUMLAH_DRAFT_MANUAL = $surat_masuk->getField("JUMLAH_DRAFT_MANUAL");

		$statement= " 
		--AND SM_INFO NOT IN ('AKAN_DISETUJUI', 'NEXT_DISETUJUI')
		AND 
		(
			(
				(
					A.USER_ATASAN_ID = '".$userId."' AND A.APPROVAL_DATE IS NULL AND COALESCE(NULLIF(A.NIP_ATASAN_MUTASI, ''), NULL) IS NULL
					AND TERPARAF IS NULL
					--AND CASE WHEN A.STATUS_SURAT = 'PEMBUAT' THEN A.USER_ATASAN_ID = A.USER_ID END
				)
				OR 
				(
					A.NIP_ATASAN_MUTASI = '".$userId."' AND A.APPROVAL_DATE IS NULL AND COALESCE(NULLIF(A.USER_ATASAN_ID, ''), NULL) IS NOT NULL
					AND TERPARAF IS NULL
					-- TAMBAHAN ONE TES
					AND A.USER_ID IS NOT NULL
					--AND CASE WHEN A.STATUS_SURAT = 'PEMBUAT' THEN A.USER_ATASAN_ID = A.USER_ID END
				)
			) 
			OR 
			(
				(
					A.USER_ATASAN_ID = '".$userGroup.$userId."' AND A.APPROVAL_DATE IS NOT NULL AND COALESCE(NULLIF(A.NIP_ATASAN_MUTASI, ''), NULL) IS NULL
				)
				OR 
				(
					A.NIP_ATASAN_MUTASI = '".$userGroup.$userId."' AND A.APPROVAL_DATE IS NOT NULL AND COALESCE(NULLIF(A.USER_ATASAN_ID, ''), NULL) IS NOT NULL
				)
			)
			OR 
			(
				A.USER_ID = '".$userId."'
				AND CASE WHEN A.USER_ID = '".$userId."' THEN TERPARAF IS NOT NULL ELSE TERPARAF IS NULL END
				AND A.STATUS_SURAT = 'PEMBUAT'
			)
			OR 
			(
				A.USER_ID = '".$userId."'
				AND CASE WHEN A.USER_ID = '".$userId."' THEN TERPARAF IS NULL ELSE TERPARAF IS NOT NULL END
				AND A.STATUS_SURAT != 'PEMBUAT'
			)
		) AND A.STATUS_SURAT IN ('VALIDASI', 'PARAF', 'PEMBUAT')";

		$surat_masuk = new SuratMasuk();
		
		$this->JUMLAH_PERSETUJUAN = $surat_masuk->getCountByParamsNewPersetujuan(array(), $userId, $userGroup, $statement, "", $infogantijabatan);

		// echo $surat_masuk->query;exit;
		$JUMLAH_KOTAK_MASUK_SEMUA= $JUMLAH_KOTAK_MASUK_NOTA_DINAS= $JUMLAH_KOTAK_MASUK_SURAT_KELUAR= $JUMLAH_KOTAK_MASUK_SURAT_EDARAN= $JUMLAH_KOTAK_MASUK_SURAT_PERINTAH= $JUMLAH_KOTAK_MASUK_SURAT_KEPUTUSAN_DIREKSI= $JUMLAH_KOTAK_MASUK_KEPUTUSAN_DIREKSI= $JUMLAH_KOTAK_MASUK_INSTRUKSI_DIREKSI= $JUMLAH_KOTAK_MASUK_MANUAL= 0;
		/*$surat_masuk = new SuratMasuk();
		$surat_masuk->selectByParamsNewDataJumlahSurat($userId, $userGroup, $cabangId, $useridatasan, $userkelompok, $infogantijabatan);
		while($surat_masuk->nextRow())
		{
			$reqJenisNaskahId= $surat_masuk->getField("JENIS_NASKAH_ID");

			if($reqJenisNaskahId == "1")
			    $linkUbah = "surat_masuk_manual_add";
			else if($reqJenisNaskahId == "2")
			    $JUMLAH_KOTAK_MASUK_NOTA_DINAS++;
			else if($reqJenisNaskahId == "13")
				$JUMLAH_KOTAK_MASUK_SURAT_EDARAN++;
			else if($reqJenisNaskahId == "15")
			    $JUMLAH_KOTAK_MASUK_SURAT_KELUAR++;
			else if($reqJenisNaskahId == "18")
			    $JUMLAH_KOTAK_MASUK_SURAT_PERINTAH++;
			else if($reqJenisNaskahId == "17")
			    $JUMLAH_KOTAK_MASUK_SURAT_KEPUTUSAN_DIREKSI++;
			else if($reqJenisNaskahId == "8")
			    $JUMLAH_KOTAK_MASUK_KEPUTUSAN_DIREKSI++;
			else if($reqJenisNaskahId == "19")
			    $JUMLAH_KOTAK_MASUK_INSTRUKSI_DIREKSI++;
			// else if($reqJenisNaskahId == "20")
			//     $linkUbah = "petikan_skd_add";

			$JUMLAH_KOTAK_MASUK_SEMUA++;
		}*/

		

		$this->JUMLAH_KOTAK_MASUK_SEMUA = $surat_masuk->selectByParamsValNewDataJumlahSurat($userId, $userGroup, $cabangId, $useridatasan, $userkelompok, $infogantijabatan);
		// $this->JUMLAH_KOTAK_MASUK_SEMUA = $JUMLAH_KOTAK_MASUK_SEMUA;
		$this->JUMLAH_KOTAK_MASUK_NOTA_DINAS = $JUMLAH_KOTAK_MASUK_NOTA_DINAS;
		$this->JUMLAH_KOTAK_MASUK_SURAT_KELUAR = $JUMLAH_KOTAK_MASUK_SURAT_KELUAR;
		$this->JUMLAH_KOTAK_MASUK_SURAT_EDARAN = $JUMLAH_KOTAK_MASUK_SURAT_EDARAN;
		$this->JUMLAH_KOTAK_MASUK_SURAT_PERINTAH = $JUMLAH_KOTAK_MASUK_SURAT_PERINTAH;
		$this->JUMLAH_KOTAK_MASUK_SURAT_KEPUTUSAN_DIREKSI = $JUMLAH_KOTAK_MASUK_SURAT_KEPUTUSAN_DIREKSI;
		$this->JUMLAH_KOTAK_MASUK_KEPUTUSAN_DIREKSI = $JUMLAH_KOTAK_MASUK_KEPUTUSAN_DIREKSI;
		$this->JUMLAH_KOTAK_MASUK_INSTRUKSI_DIREKSI = $JUMLAH_KOTAK_MASUK_INSTRUKSI_DIREKSI;
		$this->JUMLAH_KOTAK_MASUK_MANUAL = $JUMLAH_KOTAK_MASUK_MANUAL;
	}

	/** Verify user login. True when login is valid**/
	function setTerbaca($suratMasukId, $userId)
	{

		$CI = &get_instance();
		$CI->load->model("SuratMasuk");
		$CI->load->model("Disposisi");
		$CI->load->model("SuratMasukParaf");

		/* SET APAKAH PUNYA AKSES */
		$this->getAkses($suratMasukId, $userId);
		if ($this->AKSES == "")
			return;

		/* JIKA STATUS VALIDASI DAN YANG BUKA SEORANG ATASAN */
		if ($this->AKSES == "ATASAN" && $this->STATUS_SURAT == "VALIDASI") {
			$surat_masuk = new SuratMasuk();
			$surat_masuk->setField("FIELD", "TERBACA_VALIDASI");
			$surat_masuk->setField("FIELD_VALUE", "1");
			$surat_masuk->setField("LAST_UPDATE_USER", $userId);
			$surat_masuk->setField("SURAT_MASUK_ID", $suratMasukId);
			$surat_masuk->updateByField();
		} elseif ($this->AKSES == "PEMARAF") {
			$surat_masuk_paraf = new SuratMasukParaf();
			$surat_masuk_paraf->setField("LAST_UPDATE_USER", $userId);
			$surat_masuk_paraf->setField("USER_ID", $userId);
			$surat_masuk_paraf->setField("SURAT_MASUK_ID", $suratMasukId);
			$surat_masuk_paraf->paraf();
		} elseif ($this->AKSES == "DISPOSISI" && ($this->STATUS_SURAT == "POSTING" || $this->STATUS_SURAT == "TU-IN")) {
			$disposisi = new Disposisi();
			$disposisi->setField("FIELD", "TERBACA");
			$disposisi->setField("FIELD_VALUE", "1");
			$disposisi->setField("LAST_UPDATE_USER", $userId);
			$disposisi->setField("SURAT_MASUK_ID", $suratMasukId);
			$disposisi->setField("USER_ID", $userId);
			$disposisi->updateByFieldValidasiUser();
		}

		/*$surat_masuk = new SuratMasuk();
		$surat_masuk->selectByParamsJumlahSurat($userId);
		$surat_masuk->firstRow();
		
		$this->JUMLAH_INBOX = $surat_masuk->getField("JUMLAH_INBOX");
		$this->JUMLAH_DRAFT = $surat_masuk->getField("JUMLAH_DRAFT");
		$this->JUMLAH_VALIDASI = $surat_masuk->getField("JUMLAH_VALIDASI");*/
	}
}

/***** INSTANTIATE THE GLOBAL OBJECT */
$suratMasukInfo = new suratmasukinfo();
