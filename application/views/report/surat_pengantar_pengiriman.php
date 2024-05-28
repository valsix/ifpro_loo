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
$kepada = $suratmasukinfo->KEPADA;
$tempat_kepada = $suratmasukinfo->KOTA_TUJUAN;
$tanggal = $suratmasukinfo->TANGGAL;
$nama_user = $suratmasukinfo->NAMA_USER;
$nama_unit = $suratmasukinfo->NAMA_UNIT;
$perihal = $suratmasukinfo->PERIHAL;
$nama_kapal = $suratmasukinfo->NAMA_KAPAL;
$ppb = $suratmasukinfo->PPB;

$alamatunit=str_replace(array('<p>', '</p>'), array('<i>', '</i>'), $alamat);
?>

<base href="<?=base_url();?>">
<link rel="stylesheet" href="css/gaya-surat.css" type="text/css">
<!-- <link rel="stylesheet" href="lib/tinyMCE/skins/lightgray/content.min.css" type="text/css"> -->
<link rel="stylesheet" href="lib/summernote/summernote-bs4.css">
<html>
<head>
</head>
<body>
  <table style="width:100%;">
    <tr>
      <td style="width:40%">
        <table style="width:100%">
          <tr>
            <td style="width:30%">
              <img style="vertical-align:top" src="images/logo_fullcolor.png">
            </td>
            <td>
              <h1>PT JEMBATAN NUSANTARA</h1>
              <b>GEDUNG PELNI HERITAGE Lt.2</b><br>
              Jl. Pahlawan No 112-114 Surabaya<br>
              Kota Surabaya Jawa Timur<br>
              Indonesia
            </td>
          </tr>
        </table>
      </td>
      <td style="width:20%">
      </td>
      <td style="width:40%">
      </td>
    </tr>
    <tr>
      <td style="width:40%; border-top: solid black 1pt; border-bottom: solid black 1pt;">
        Kirim Ke <?=$kepada?>
      </td>
      <td style="width:20%">
      </td>
      <td rowspan="2" style="width:40%; border-top: solid black 1pt; border-bottom: solid black 1pt;">
        <h3><b>SURAT PENGANTAR PENGIRIMAN (SPP)</b></h3>
      </td>
    </tr>
    <tr>
      <td style="width:40%; border-top: solid black 1pt;">
       <?=$nama_unit?>
      </td>
    </tr>
    <tr>
      <td style="vertical-align:top">
       
      </td>
      <td style="width:20%">
      </td>
      <td>
        <table style="width: 100%;">
          <tr>
            <td>Nomor</td>
            <td>:</td>
            <td><?= $suratmasukinfo->NOMOR ?></td>
          </tr>
          <tr>
            <td>Tanggal</td>
            <td>:</td>
            <td><?=getFormattedDate2($suratmasukinfo->TANGGAL) ?></td>
          </tr>
          <tr>
            <td>No PPB/BPB</td>
            <td>:</td>
            <td><?=$ppb?></td>
          </tr>
          <tr>
            <td>Nama Kapal</td>
            <td>:</td>
            <td><?=$nama_kapal?></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <br>
  <!-- <?= $suratmasukinfo->ISI; ?> -->
  <table style="width:100%;border-bottom: solid black 1pt;">
    <thead>
      <tr>
        <th style="width:30%; border: solid black 1pt;">Kode Barang</th>
        <th style="width:30%; border: solid black 1pt;">Nama Barang</th>
        <th style="width:10%; border: solid black 1pt;">Kts</th>
        <th style="width:10%; border: solid black 1pt;">Satuan</th>
        <th style="width:20%; border: solid black 1pt;">Keterangan</th>
      </tr>
    </thead>
    <?
      $BarangInclude = new SuratMasuk();
      $BarangInclude->selectByParamsBarang(array(), -1, -1, " AND SURAT_MASUK_ID =".$reqId);
      while($BarangInclude->nextRow()){
          $reqIdBarang = $BarangInclude->getField("SURAT_MASUK_BARANG_ID");
          $reqKodeBarang = $BarangInclude->getField("KODE");
          $reqNamaBarang = $BarangInclude->getField("NAMA");
          $reqSatuanBarang = $BarangInclude->getField("SATUAN");
          $reqKtsBarang = $BarangInclude->getField("QTY");
          $reqKeteranganBarang = $BarangInclude->getField("KETERANGAN");
      ?>
      <tbody>
        <tr>
          <td><?=$reqKodeBarang?></td>
          <td><?=$reqNamaBarang?></td>
          <td style="text-align: right;"><?=$reqKtsBarang?></td>
          <td><?=$reqSatuanBarang?></td>
          <td><?=$reqKeteranganBarang?></td>
        </tr>
      </tbody>
      <?}?>
  </table>
  <br>

  <table style="width:100%">
    <tr>
      <td style="width:40%; border-top: solid black 1pt; border-bottom: solid black 1pt;">
        Dari <?=$nama_user?>
      </td>
      <td style="width:20%">
      </td>
      <td style="width:40%; border-top: solid black 1pt; border-bottom: solid black 1pt;">
        Keterangan
      </td>
    </tr>
    <tr>
      <td style="border-bottom:dashed black">
        <?=$an_nama?>
        <?=$nama_unit?>
        <br>
        <br>
        "JL. RAJAWALI 14 A<br>
        Kota Surabaya Jawa Timur<br>
        Indonesia"<br>
        Surabaya Jawa Timur
      </td>
      <td style="width:20%">
      </td>
      <td style="vertical-align:top;border-bottom:dashed black">
       <?=$perihal?>
      </td>      
    </tr>
  </table>
  <br>
   <table width="100%">
    <tr>
      <td style="width:75%"></td>
      <td style="width:25%">
        <center>
          <?
          if ($an_status == 1)
          {
            ?>
            <br>A.n <?=$an_nama?>
            <?
          }
          ?>
          <br><?= strtoupper($suratmasukinfo->USER_ATASAN_JABATAN) ?><br> 
          <?
          $ttdKode = $suratmasukinfo->TTD_KODE;
          if ($ttdKode == "" || $suratmasukinfo->JENIS_TTD == "BASAH") {
            echo "<br><br>";
          } else {
          ?>
            <img src="/var/www/html/<?= $suratmasukinfo->FOLDER_PATH ?>/<?= $suratmasukinfo->SURAT_MASUK_ID ?>/<?= $suratmasukinfo->TTD_KODE ?>.png" height="100px">
            <br>
          <?
          }
          ?>
          <br>
          <br>
          <br>
          <b><u><?= strtoupper($suratmasukinfo->USER_ATASAN)  ?></u></b><br>
          <?= getFormattedDate2($suratmasukinfo->TANGGAL)  ?><br>

        </center>
      </td>
    </tr>
  </table>
</body>


