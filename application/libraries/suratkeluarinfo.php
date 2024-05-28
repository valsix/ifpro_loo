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

class suratkeluarinfo
{
	// var $USER_LOGIN_ID;

	var $SURAT_KELUAR_ID;
	var $TAHUN;
	var $NOMOR;
	var $NO_AGENDA;
	var $TANGGAL;
	var $TANGGAL_DITERUSKAN;
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
	var $TEMBUSAN;
	var $LOKASI_SURAT;
	var $TTD_KODE;
	var $KEPADA;
	var $ALAMAT_UNIT;
	var $TELEPON_UNIT;
	var $FAX_UNIT;
	var $NAMA_UNIT;
	var $LOKASI_UNIT;


	var $AKSES;
	var $PDF;
	var $TEMPLATE;
	var $STATUS_PARAF;
	var $TERBACA_VALIDASI;

	var $JUMLAH_INBOX;
	var $JUMLAH_VALIDASI;
	var $JUMLAH_DRAFT;


	/******************** CONSTRUCTOR **************************************/
	function suratkeluarinfo()
	{

		$this->emptyProps();
	}

	/******************** METHODS ************************************/
	/** Empty the properties **/
	function emptyProps()
	{
		$this->SURAT_KELUAR_ID = "";
		$this->TAHUN = "";
		$this->NOMOR = "";
		$this->NO_AGENDA = "";
		$this->TANGGAL = "";
		$this->TANGGAL_DITERUSKAN = "";
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
		$this->TEMBUSAN = "";
		$this->LOKASI_SURAT = "";
		$this->TTD_KODE = "";
		$this->KEPADA = "";
		$this->ALAMAT_UNIT = "";
		$this->TELEPON_UNIT = "";
		$this->FAX_UNIT = "";
		$this->NAMA_UNIT = "";
		$this->LOKASI_UNIT = "";



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
	}


	/** Verify user login. True when login is valid**/
	function getInfo($suratKeluarId)
	{

		$CI = &get_instance();
		$CI->load->model("SuratKeluar");


		$surat_keluar = new SuratKeluar();
		$surat_keluar->selectByParamsSurat(array("A.SURAT_KELUAR_ID" => $suratKeluarId));
		// echo $surat_keluar->query;exit;

		if ($surat_keluar->firstRow()) {

			$this->SURAT_KELUAR_ID = $surat_keluar->getField("SURAT_KELUAR_ID");
			$this->TAHUN = $surat_keluar->getField("TAHUN");
			$this->NOMOR = $surat_keluar->getField("NOMOR");
			$this->NO_AGENDA = $surat_keluar->getField("NO_AGENDA");
			$this->TANGGAL = $surat_keluar->getField("TANGGAL");
			$this->TANGGAL_DITERUSKAN = $surat_keluar->getField("TANGGAL_DITERUSKAN");
			$this->TANGGAL_BATAS = $surat_keluar->getField("TANGGAL_BATAS");
			$this->JENIS = $surat_keluar->getField("JENIS");
			$this->JENIS_TUJUAN = $surat_keluar->getField("JENIS_TUJUAN");
			$this->KEPADA = $surat_keluar->getField("KEPADA");
			$this->PERIHAL = $surat_keluar->getField("PERIHAL");
			$this->KLASIFIKASI_ID = $surat_keluar->getField("KLASIFIKASI_ID");
			$this->INSTANSI_ASAL = $surat_keluar->getField("INSTANSI_ASAL");
			$this->ALAMAT_ASAL = $surat_keluar->getField("ALAMAT_ASAL");
			$this->KOTA_ASAL = $surat_keluar->getField("KOTA_ASAL");
			$this->KETERANGAN_ASAL = $surat_keluar->getField("KETERANGAN_ASAL");
			$this->SATUAN_KERJA_ID_TUJUAN = $surat_keluar->getField("SATUAN_KERJA_ID_TUJUAN");
			$this->ISI = $surat_keluar->getField("ISI");
			$this->JUMLAH_LAMPIRAN = $surat_keluar->getField("JUMLAH_LAMPIRAN");
			$this->CATATAN = $surat_keluar->getField("CATATAN");
			$this->TERBALAS = $surat_keluar->getField("TERBALAS");
			$this->TERDISPOSISI = $surat_keluar->getField("TERDISPOSISI");
			$this->TERBACA = $surat_keluar->getField("TERBACA");
			$this->TERPARAF = $surat_keluar->getField("TERPARAF");
			$this->SATUAN_KERJA_ID_ASAL = $surat_keluar->getField("SATUAN_KERJA_ID_ASAL");
			$this->TANGGAL_ENTRI = $surat_keluar->getField("TANGGAL_ENTRI");
			$this->USER_ID = $surat_keluar->getField("USER_ID");
			$this->NAMA_USER = $surat_keluar->getField("NAMA_USER");
			$this->KLASIFIKASI_JENIS = $surat_keluar->getField("KLASIFIKASI_JENIS");
			$this->POSISI_SURAT_MASUK = $surat_keluar->getField("POSISI_SURAT_MASUK");
			$this->JENIS_NASKAH_ID = $surat_keluar->getField("JENIS_NASKAH_ID");
			$this->SIFAT_NASKAH = $surat_keluar->getField("SIFAT_NASKAH");
			$this->STATUS_SURAT = $surat_keluar->getField("STATUS_SURAT");
			$this->BLN_SURAT = $surat_keluar->getField("BLN_SURAT");
			$this->THN_SURAT = $surat_keluar->getField("THN_SURAT");
			$this->USER_ATASAN_ID = $surat_keluar->getField("USER_ATASAN_ID");
			$this->USER_ATASAN_JABATAN = $surat_keluar->getField("USER_ATASAN_JABATAN");
			$this->USER_ATASAN = $surat_keluar->getField("USER_ATASAN");
			$this->TEMBUSAN = $surat_keluar->getField("TEMBUSAN");
			$this->LOKASI_SURAT = $surat_keluar->getField("LOKASI_SURAT");
			$this->TTD_KODE = $surat_keluar->getField("TTD_KODE");
			$this->ALAMAT_UNIT = $surat_keluar->getField("ALAMAT_UNIT");
			$this->TELEPON_UNIT = $surat_keluar->getField("TELEPON_UNIT");
			$this->FAX_UNIT = $surat_keluar->getField("FAX_UNIT");
			$this->NAMA_UNIT = $surat_keluar->getField("NAMA_UNIT");
			$this->LOKASI_UNIT = $surat_keluar->getField("LOKASI_UNIT");
		}
	}


	/** Verify user login. True when login is valid**/
	function getAkses($suratKeluarId, $userId)
	{
		$CI = &get_instance();

		$CI = &get_instance();
		$CI->load->model("SuratKeluar");

		$surat_keluar = new SuratKeluar();
		$surat_keluar->selectByParamsAkses(array("A.SURAT_KELUAR_ID" => $suratKeluarId, "A.USER_ID" => $userId));
		$surat_keluar->firstRow();

		$this->AKSES = $surat_keluar->getField("AKSES");
		$this->PDF = $surat_keluar->getField("SURAT_PDF");
		$this->TEMPLATE = $surat_keluar->getField("TEMPLATE_SURAT");
		$this->TERBACA = $surat_keluar->getField("TERBACA");
		$this->TERBALAS = $surat_keluar->getField("TERBALAS");
		$this->TERDISPOSISI = $surat_keluar->getField("TERDISPOSISI");
		$this->STATUS_PARAF = $surat_keluar->getField("STATUS_PARAF");
		$this->TERBACA_VALIDASI = $surat_keluar->getField("TERBACA_VALIDASI");
		$this->STATUS_SURAT = $surat_keluar->getField("STATUS_SURAT");
	}


	/** Verify user login. True when login is valid**/
	function setTerbaca($suratKeluarId, $userId)
	{

		$CI = &get_instance();
		$CI->load->model("SuratKeluar");

		/* SET APAKAH PUNYA AKSES */
		$this->getAkses($suratKeluarId, $userId);
		if ($this->AKSES == "")
			return;


		/* JIKA STATUS VALIDASI DAN YANG BUKA SEORANG ATASAN */
		if ($this->AKSES == "ATASAN" && $this->STATUS_SURAT == "VALIDASI") {
			$surat_keluar = new SuratKeluar();
			$surat_keluar->setField("FIELD", "TERBACA_VALIDASI");
			$surat_keluar->setField("FIELD_VALUE", "1");
			$surat_keluar->setField("LAST_UPDATE_USER", $userId);
			$surat_keluar->setField("SURAT_KELUAR_ID", $suratKeluarId);
			$surat_keluar->updateByField();
		}
	}
}

/***** INSTANTIATE THE GLOBAL OBJECT */
$suratKeluarInfo = new suratkeluarinfo();
