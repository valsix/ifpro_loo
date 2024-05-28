<!DOCTYPE html>
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

$this->load->library('suratkeluarinfo');

$reqId = httpFilterGet("reqId");
$reqJenisSurat = httpFilterGet("reqJenisSurat");

$this->load->model("SuratMasuk");
$suratmasuk = new SuratMasuk();
$suratmasuk->selectByParamsPltJabatan(array("A.SURAT_MASUK_ID" => $reqId));
$suratmasuk->firstRow();
$reqJabatan= $suratmasuk->getField("JABATAN");
$reqAtasanJabatan= $suratmasuk->getField("USER_ATASAN_JABATAN");
$reqAnTambahan= $suratmasuk->getField("AN_TAMBAHAN");

$suratmasukinfo->getInfoAsc($reqId, $reqJenisSurat);
$telp=$suratmasukinfo->TELEPON_UNIT;
$fax=$suratmasukinfo->FAX_UNIT;
$alamat=$suratmasukinfo->ALAMAT_UNIT;
$an_status = $suratmasukinfo->AN_STATUS;
$an_nama = $suratmasukinfo->AN_NAMA;
$tanggal = $suratmasukinfo->TANGGAL;
$alamatunit=str_replace(array('<p>', '</p>'), array('<i>', '</i>'), $alamat);
$nama_unit = $suratmasukinfo->NAMA_UNIT;
$nama_user = $suratmasukinfo->NAMA_USER;
$jabatan_pengirim = $suratmasukinfo->JABATAN_PENGIRIM;
?>

<base href="<?=base_url();?>">
<link rel="stylesheet" href="css/gaya-surat.css" type="text/css">
<!-- <link rel="stylesheet" href="lib/tinyMCE/skins/lightgray/content.min.css" type="text/css"> -->
<link rel="stylesheet" href="lib/summernote/summernote-bs4.css">
<html>
<head>
</head>
<body>
  <table style="width:100%">
    <tr>
      <td style="width:40%;vertical-align:top">
        <h1>PT JEMBATAN NUSANTARA</h1> 
      </td>
      <td style="width:30%">
      </td>
      <td style="width:30%;">
        <h3><b><?= $suratmasukinfo->NOMOR ?></b></h3>
        <?= $suratmasukinfo->LOKASI_UNIT ?>, <?= getFormattedDate2($suratmasukinfo->TANGGAL)  ?>
      </td>
    </tr>
  </table>
  <br>
  <center>
    <h1>BON PERMINTAAN BARANG UMUM<br>KANTOR PUSAT</h1>
    <h3>DEPARTEMEN : <?=$nama_unit?></h3>
  </center>
  <!-- <br>
    <?= $suratmasukinfo->ISI; ?>
  <br> -->
  <table style="width:100%">
    <thead>
      <tr>
        <th colspan="5" style="border: solid black 1pt;text-align: center;">DIISI OLEH DEPARTEMEN YANG MEMINTA</th>
        <th colspan="5" style="border: solid black 1pt;text-align: center;">DIISI OLEH BAGIAN RUMAH TANGGA</th>
      </tr>
      <tr>
        <th rowspan="2" style="border: solid black 1pt;text-align: center; background-color: lightgray;">No</th>
        <th rowspan="2" style="border: solid black 1pt;text-align: center; background-color: lightgray;">Nama Barang</th>
        <th rowspan="2" style="border: solid black 1pt;text-align: center; background-color: lightgray;">Qtty</th>
        <th rowspan="2" style="border: solid black 1pt;text-align: center; background-color: lightgray;">Satuan</th>
        <th rowspan="2" style="border: solid black 1pt;text-align: center; background-color: lightgray;">Keperluan</th>
        <th rowspan="2" style="border: solid black 1pt;text-align: center; background-color: lightgray; width: 7%;">Sisa Stock (QTY)</th>
        <th rowspan="2" style="border: solid black 1pt;text-align: center; background-color: lightgray; width: 7;">Disetujui (QTY)</th>
        <th colspan="2" style="border: solid black 1pt;text-align: center; background-color: lightgray;">Pemenuhan</th>
        <th rowspan="2" style="border: solid black 1pt;text-align: center; background-color: lightgray;">Keterangan</th>
      </tr>
      <tr>
        <th style="border: solid black 1pt;text-align: center; width: 7%; background-color: lightgray;">Stock (QTY)</th>
        <th style="border: solid black 1pt;text-align: center; width: 7%; background-color: lightgray;">Pengadaan (QTY)</th>
      </tr>
    </thead>
    <tbody>
      <?
      $BarangInclude = new SuratMasuk();
      $BarangInclude->selectByParamsBarang(array(), -1, -1, " AND SURAT_MASUK_ID =".$reqId);
      $No=1;
      while($BarangInclude->nextRow()){
          $reqIdBarang = $BarangInclude->getField("SURAT_MASUK_BARANG_ID");
          $reqKodeBarang = $BarangInclude->getField("KODE");
          $reqNamaBarang = $BarangInclude->getField("NAMA");
          $reqSatuanBarang = $BarangInclude->getField("SATUAN");
          $reqKtsBarang = $BarangInclude->getField("QTY");
          $reqKeperluanBarang = $BarangInclude->getField("KEPERLUAN");
          $reqSisaStockBarang = $BarangInclude->getField("SISA_STOCK");
          $reqDisetujuiBarang = $BarangInclude->getField("DISETUJUI");
          $reqStockBarang = $BarangInclude->getField("STOCK");
          $reqPengadaanBarang = $BarangInclude->getField("PENGADAAN");
          $reqKeteranganBarang = $BarangInclude->getField("KETERANGAN");
      ?>
          <tr>
              <td style="border: solid black 1pt;;text-align: center;">
                  <?=$No?>
              </td>
              <td style="border: solid black 1pt;">
                  <?=$reqNamaBarang?>
              </td>
              <td style="border: solid black 1pt;">
                  <?=$reqKtsBarang?>
              </td>
              <td style="border: solid black 1pt;">
                  <?=$reqSatuanBarang?>
              </td>
              <td style="border: solid black 1pt;">
                  <?=$reqKeperluanBarang?>
              </td>
              <td style="border: solid black 1pt;">
                  <?=$reqSisaStockBarang?>
              </td>
              <td style="border: solid black 1pt;">
                  <?=$reqDisetujuiBarang?>
              </td>
              <td style="border: solid black 1pt;">
                  <?=$reqStockBarang?>
              </td>
              <td style="border: solid black 1pt;">
                  <?=$reqPengadaanBarang?>
              </td>
              <td style="border: solid black 1pt;">
                  <?=$reqKeteranganBarang?>
              </td>
          </tr>
      <?
      $No++;
      }
      ?>
    </tbody>
  </table>
  <br>
  <table style="width:100%">
    <tr>
      <td style="width:20%;border: solid black 1pt;text-align: center;">Yang Meminta</td>
      <td style="width:20%;border: solid black 1pt;text-align: center;">Mengetahui</td>
      <td style="width:20%;border: solid black 1pt;text-align: center;">Penerima BPBU</td>
      <td style="width:20%;border: solid black 1pt;text-align: center;">Yang Menyetujui</td>
      <td style="width:20%;border: solid black 1pt;text-align: center;">Keterangan</td>
    </tr>
    <tr>
      <td style="width:20%;border: solid black 1pt;"><br><br><br></td>
      <td style="width:20%;border: solid black 1pt;"><br><br><br></td>
      <td style="width:20%;border: solid black 1pt;"><br><br><br></td>
      <td style="width:20%;border: solid black 1pt;"><br><br><br></td>
      <td rowspan="2" style="width:20%;border: solid black 1pt;">
        <table style="margin-left:10px">
          <tr>
            <td style="width:40%">Putih</td>
            <td style="width:5%">:</td>
            <td>Arsip Gudang</td>
          </tr>
          <tr>
            <td style="width:40%">Merah</td>
            <td style="width:5%">:</td>
            <td>Arsip Rumah Tangga</td>
          </tr>
          <tr>
            <td style="width:40%">Kuning</td>
            <td style="width:5%">:</td>
            <td>Dept. Yang Meminta</td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td style="width:20%;border: solid black 1pt;text-align: center;"><?=$nama_user?><br> <?=$jabatan_pengirim?></td>
      <td style="width:20%;border: solid black 1pt;text-align: center;">Nama<br> Jabatan</td>
      <td style="width:20%;border: solid black 1pt;text-align: center;">Bagian Rumah Tangga</td>
      <td style="width:20%;border: solid black 1pt;text-align: center;">Manager SDM Darat & Ur.Umum</td>
    </tr>
  </table>
</body>


