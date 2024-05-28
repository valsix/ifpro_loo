<?php
/* INCLUDE FILE */
include_once("functions/date.func.php");
include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("libraries/vendor/autoload.php");

$this->load->library('suratmasukinfo');
$suratmasukinfo = new suratmasukinfo();

$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$xx=explode("/",$actual_link);
$link="http://192.168.88.100/".$xx[3];
// $reqJabatan                    = $suratmasuk->getField("USER_ATASAN_JABATAN");
// var_dump ($reqJabatan);exit;


$this->load->library('suratkeluarinfo');

$reqId = httpFilterGet("reqId");
$reqJenisSurat = httpFilterGet("reqJenisSurat");

$this->load->model("SuratMasuk");
$suratmasuk = new SuratMasuk();
$suratmasuk->selectByParamsPltJabatan(array("A.SURAT_MASUK_ID" => $reqId));
$suratmasuk->firstRow();
$reqJabatan                    = $suratmasuk->getField("JABATAN");
$reqAtasanJabatan                    = $suratmasuk->getField("USER_ATASAN_JABATAN");
$reqAnTambahan                    = $suratmasuk->getField("AN_TAMBAHAN");

// var_dump ($reqAnTambahan);exit;


$suratmasukinfo->getInfoAsc($reqId, $reqJenisSurat);
$telp=$suratmasukinfo->TELEPON_UNIT;
$fax=$suratmasukinfo->FAX_UNIT;
$alamat=$suratmasukinfo->ALAMAT_UNIT;
$an_status = $suratmasukinfo->AN_STATUS;
$an_nama = $suratmasukinfo->AN_NAMA;
$perihal = $suratmasukinfo->PERIHAL;
$alamatunit=str_replace(array('<p>', '</p>'), array('<i>', '</i>'), $alamat);
?>
<link href="<?= base_url() ?>css/gaya-surat.css" rel="stylesheet" type="text/css">
<style>
  body{
/*      background-image:url('<?= base_url() ?>images/bg_cetak.jpg')  ;
      background-image-resize:6;
      background-size: cover;*/
  }
</style>
<body>

  <!-- header bagian 1 -->
  <table border="1" style="width: 100%;">
    <tr>
      <td rowspan="4"width="100px">
        <img src="<?=base_url().'images/logo.png'?>" height="100px">
      </td>
      <td></td>
      <td></td>
      <td colspan="3" style="text-align: center; vertical-align: middle; height: 50px;"><b><center>SURAT TUGAS PERJALANAN DINAS</center></b></td>
    </tr>
    <tr>
      <td >&ensp;Untuk</td>
      <td >&ensp;:</td>
      <td >&ensp;Div. Keuangan, Unit Umum, Pelaksana Dinas</td>
    </tr>
    <tr>
      <td>&ensp;Nomor</td>
      <td>&ensp;:</td>
      <td >&ensp;</td>
    </tr>
    <tr>
      <td>&ensp;Tanggal</td>
      <td>&ensp;:</td>
      <td >&ensp;</td>
    </tr>
  </table>
  <!-- akhir header bagian 1 -->

  <!-- isi bagian 1 [dokumen acuan]-->
  <table border="1" style="width: 100%;">
    <tr>
      <td colspan="9">&ensp;</td>
    </tr>
    <tr>
      <td colspan="9"><b>&ensp;DOKUMEN ACUAN</b></td>
    </tr>
    <tr>
      <td colspan="9">&ensp;</td>
    </tr>
    <tr>
      <td colspan="9">&ensp;</td>
    </tr>
  </table>

  <!-- isi bagian 1 [pelaksana dinas]-->
  <table border="1" style="width: 100%;">
    <tr>
      <td colspan="9"><b>&ensp;PELAKSANA DINAS</b></td>
    </tr>
    <tr>
      <td rowspan="9" style="width: 35px;">&ensp;</td>
      <td style="width: 100px;">&ensp;Jumlah</td>
      <td style="width: 25px;">&ensp;:</td>
      <td style="width: 125px;">&ensp;</td>
      <td colspan="5">&ensp;Orang</td>
    </tr>
    <tr>
      <td colspan="7" >&ensp;Data Pelaksana</td>
      <td style="width: 250px;">&ensp;Level Jabatan</td>
    </tr>
    <tr>
      <td>&ensp;Pemimpin</td>
      <td>&ensp;:</td>
      <td colspan="5">&ensp;</td>
      <td >&ensp;</td>
    </tr>
    <tr>
      <td>&ensp;Pelaksana</td>
      <td>&ensp;:</td>
      <td colspan="5">&ensp;</td>
      <td >&ensp;</td>
    </tr>
    <tr>
      <td rowspan="5">&ensp;</td>
      <td>&ensp;</td>
      <td colspan="5">&ensp;</td>
      <td >&ensp;</td>
    </tr>
    <tr>
      <td>&ensp;</td>
      <td colspan="5">&ensp;</td>
      <td >&ensp;</td>
    </tr>
    <tr>
      <td>&ensp;</td>
      <td colspan="5">&ensp;</td>
      <td >&ensp;</td>
    </tr>
    <tr>
      <td>&ensp;</td>
      <td colspan="5">&ensp;</td>
      <td >&ensp;</td>
    </tr>
    <tr>
      <td>&ensp;</td>
      <td colspan="5">&ensp;</td>
      <td >&ensp;</td>
    </tr>
    <tr>
      <td colspan="9">&ensp;</td>
    </tr>
  </table>

  <!-- isi bagian 1 [lokasi dinas]-->
  <table border="1" style="width: 100%;">
    <tr>
      <td colspan="9"><b>&ensp;LOKASI DINAS</b></td>
    </tr>
    <tr>
      <td colspan="9">&ensp;.......</td>
    </tr>
    <tr>
      <td colspan="9">&ensp;</td>
    </tr>
  </table>

  <!-- isi bagian 1 [periode dinas]-->
  <table border="1" style="width: 100%;">
    <tr>
      <td colspan="4"><b>&ensp;PERIODE DINAS</b></td>
    </tr>
    <tr>
      <td rowspan="3" style="width: 4%;">&ensp;</td>
      <td style="width: 25%;">&ensp;Tanggal Berangkat</td>
      <td colspan="2">&ensp;</td>
    </tr>
    <tr>
      <td >&ensp;Tanggal Kembali</td>
      <td colspan="2">&ensp;</td>
    </tr>
    <tr>
      <td >&ensp;Total Periode Dinas</td>
      <td >&ensp;hari</td>
      <td >&ensp;malam</td>
    </tr>
  </table>

  <!-- isi bagian 1 [estimasi biaya dinas]-->
  <table border="1" style="width: 100%;">
    <tr>
      <td colspan="9" ><b>&ensp;ESTIMASI BIAYA DINAS</b></td>
    </tr>
    <tr>
      <td colspan="5">&ensp;Alokasi Biaya</td>
      <td colspan="2" style="width: 25%;">&ensp;Pengajuan Biaya</td>
      <td colspan="2">&ensp;Realisasi</td>
    </tr>
    <tr>
      <td style="width: 4%;">&ensp;-></td>
      <td colspan="4">&ensp;Perjalanan antar wilayah (Tiket Pesawat/Kereta/Bus)</td>
      <td style="width: 5%;">&ensp;IDR</td>
      <td >&ensp;</td>
      <td style="width: 5%;">&ensp;IDR</td>
      <td >&ensp;</td>
    </tr><tr>
      <td >&ensp;-></td>
      <td colspan="4">&ensp;Perjalanan dalam wilayah</td>
      <td >&ensp;IDR</td>
      <td >&ensp;</td>
      <td >&ensp;IDR</td>
      <td >&ensp;</td>
    </tr><tr>
      <td >&ensp;-></td>
      <td colspan="4">&ensp;Penginapan</td>
      <td >&ensp;IDR</td>
      <td >&ensp;</td>
      <td >&ensp;IDR</td>
      <td >&ensp;</td>
    </tr><tr>
      <td >&ensp;-></td>
      <td colspan="4">&ensp;Lain - Lain</td>
      <td >&ensp;IDR</td>
      <td >&ensp;</td>
      <td >&ensp;IDR</td>
      <td >&ensp;</td>
    </tr>
    <tr>
      <td >&ensp;-></td>
      <td style="width: 10%;">&ensp;Uang Saku</td>
      <td style="width: 10%;">&ensp;BoD/BoC</td>
      <td >&ensp;</td>
      <td >&ensp;orang</td>
      <td >&ensp;IDR</td>
      <td >&ensp;</td>
      <td >&ensp;IDR</td>
      <td >&ensp;</td>
    </tr>
    <tr>
      <td colspan="2" rowspan="3">&ensp;</td>
      <td >&ensp;Div. Head</td>
      <td >&ensp;</td>
      <td  style="width: 15%;">&ensp;orang</td>
      <td >&ensp;IDR</td>
      <td >&ensp;</td>
      <td >&ensp;IDR</td>
      <td >&ensp;</td>
    </tr>
    <tr>
      <td >&ensp;Manager</td>
      <td >&ensp;</td>
      <td >&ensp;orang</td>
      <td >&ensp;IDR</td>
      <td >&ensp;</td>
      <td >&ensp;IDR</td>
      <td >&ensp;</td>
    </tr>
    <tr>
      <td >&ensp;Staff</td>
      <td >&ensp;</td>
      <td >&ensp;orang</td>
      <td >&ensp;IDR</td>
      <td >&ensp;</td>
      <td >&ensp;IDR</td>
      <td >&ensp;</td>
    </tr>
    <tr>
      <td colspan="7"><b>&ensp;Total Realisasi</b></td>
      <td ><b>&ensp;IDR</b></td>
      <td ><b>&ensp;</b></td>
    </tr>
    <tr>
      <td colspan="9"><b>&ensp;*Akomodasi disediakan berdasarkan ketentuan Perusahaan</b></td>
    </tr>
    <tr>
      <td colspan="9"><b>&ensp;**Cash advance dilakukan settlement terpisah dari persetujuan ini</b></td>
    </tr>
    
    <!-- <tr>
      <td style="text-align:right;font-size: 9pt;width: 25%;">
        <b><u>PT. INDONESIA FERRY PROPERTI</u></b><br>
        Gedung PT. ASDP Indonesia Ferry (Persero) <br>
        Jl. Jendaral Ahamad Yani Kav 52A<br>
        Jakarta Pusat 10510<br>
        contact@ifpro.co.id | <u>www.ifpro.co.id</u>
      </td>
      <td style="text-align:center;font-size: 15pt;">
        <span><b><u>SURAT TUGAS PERJALANAN DINAS</u></b><br><?= $suratmasukinfo->NOMOR ?></span>
      </td>
      <td style="text-align:right;font-size: 9pt;width: 25%;"></td>
    </tr> -->
  </table>
  <!-- akhir isi bagian 1 -->
  
  <br>

  <!-- start tanda tangan -->
  <table border="1" style="width: 100%;">
    <tr>
      <td rowspan="3">&ensp;</td>
      <td colspan="2">&ensp;PENGAJUAN STPD</td>
      <td colspan="3">&ensp;LAPORAN REALISASI STPD</td>
    </tr>
    <tr>
      <td >&ensp;Disiapkan Oleh</td>
      <td >&ensp;Disetujui (Mgr/GM/BOD)</td>
      <td >&ensp;Disiapkan Oleh</td>
      <td >&ensp;Mengetahui SDM</td>
      <td >&ensp;Disetujui (BOD)</td>
    </tr>
    <tr>
      <td style="height: 70px;">&ensp;</td>
      <td >&ensp;</td>
      <td >&ensp;</td>
      <td >&ensp;</td>
      <td >&ensp;</td>
    </tr>
    <tr>
      <td >&ensp;Nama</td>
      <td >&ensp;</td>
      <td >&ensp;</td>
      <td >&ensp;</td>
      <td >&ensp;</td>
      <td >&ensp;</td>
    </tr>
    <tr>
      <td >&ensp;Jabatan</td>
      <td >&ensp;</td>
      <td >&ensp;</td>
      <td >&ensp;</td>
      <td >&ensp;</td>
      <td >&ensp;</td>
    </tr>
    <tr>
      <td >&ensp;Tanggal</td>
      <td >&ensp;</td>
      <td >&ensp;</td>
      <td >&ensp;</td>
      <td >&ensp;</td>
      <td >&ensp;</td>
    </tr>
  </table>

  <br>
  <br>
  <p style="page-break-before: always;"></p>

  <!-- header bagian 2 -->
  <table border="1" style="width: 100%;">
    <tr>
      <td rowspan="4" width="100px">
        <img src="<?=base_url().'images/logo.png'?>" height="100px">
      </td>
      <td></td>
      <td></td>
      <td style="text-align: center; vertical-align: middle; height: 50px;"><b><center>LAPORAN HASIL PERJALANAN DINAS</center></b></td>
    </tr>
    <tr>
      <td >&ensp;Untuk</td>
      <td >&ensp;:</td>
      <td style="border-right: none;">&ensp;Div. Keuangan, Unit Umum, Pelaksana Dinas</td>
    </tr>
    <tr>
      <td>&ensp;Nomor</td>
      <td>&ensp;:</td>
      <td >&ensp;</td>
    </tr>
    <tr>
      <td>&ensp;Tanggal</td>
      <td>&ensp;:</td>
      <td >&ensp;</td>
    </tr>
  </table>
  <!-- akhir header bagian 2 -->

  <!-- isi bagian 2 -->
  <table border="1" style="width: 100%;">
    <tr>
      <td style="width: 35px; text-align: center;">NO</td>
      <td colspan="4" style="text-align: center;">LAPORAN</td>
    </tr>
    <tr>
      <td style="text-align: center; height: 400px;">1</td>
      <td ></td>
    </tr>
    <tr>
      <td style="text-align: center; height: 400px;">2</td>
      <td ></td>
    </tr>
    <tr>
      <td style="text-align: center; height: 400px;">3</td>
      <td ></td>
    </tr>
  </table>
  <!-- akhir isi bagian 2 -->