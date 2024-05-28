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
      <td style="width:40%;vertical-align:top">
        <table>
          <tr>
            <td>
             <img style="vertical-align:top; width: 70px;" src="images/logo_b&w.png">        
            </td>
            <td>
              <center style="margin-left: 10px;">
                <h2 style="margin-bottom:-20px ; margin-top: -3px ;">PT JEMBATAN NUSANTARA</h2> 
                <h4 style="margin-bottom:-15px ;letter-spacing: 6px;">WE SERVE BETTER</h4> 
                <h3>KANTOR PUSAT</h3> 
              </center>
            </td>
          </tr>
        </table>
      </td>
      <td style="width:30%">
      </td>
      <td style="width:30%;">
        <h3 style="margin-top: -3px ;margin-bottom: -1px ;"><b>SURAT PENGANTAR PENGIRIMAN</b></h3>
        Kepada Yth. :<br>
        GM Keuangan Kantor Pusat<br>
        Di Tempat
      </td>
    </tr>
  </table>
  <br>
  <center>
    DARAT KE DARAT
    <h3 style="margin-top:-3px ;"><u>LAPORAN KERUSAKAN GEDUNG / KANTOR DAN INVENTARIS KANTOR PUSAT</u></h3>
    <table>
      <tr>
        <td><h3 style="margin-bottom:-3px ;margin-top:-3px ;">NAMA GEDUNG / KANTOR </h3></td>
        <td><h3 style="margin-bottom:-3px ;margin-top:-3px ;"> : </h3></td>
        <td><h3 style="margin-bottom:-3px ;margin-top:-3px ;">......................................................</h3></td>
      </tr>
      <tr>
        <td><h3 style="margin-bottom:-3px ;margin-top:-3px ;">ALAMAT</h3></td>
        <td><h3 style="margin-bottom:-3px ;margin-top:-3px ;"> : </h3></td>
        <td><h3 style="margin-bottom:-3px ;margin-top:-3px ;">......................................................</h3></td>
      </tr>
      <tr>
        <td><h3 style="margin-bottom:-3px ;margin-top:-3px ;">DEVISI</h3></td>
        <td><h3 style="margin-bottom:-3px ;margin-top:-3px ;"> : </h3></td>
        <td><h3 style="margin-bottom:-3px ;margin-top:-3px ;">......................................................</h3></td>
      </tr>
      <tr>
        <td><h3 style="margin-bottom:-3px ;margin-top:-3px ;">DEPARTEMEN / BAGIAN</h3></td>
        <td><h3 style="margin-bottom:-3px ;margin-top:-3px ;"> : </h3></td>
        <td><h3 style="margin-bottom:-3px ;margin-top:-3px ;">......................................................</h3></td>
      </tr>
      <tr>
        <td><h3 style="margin-bottom:-3px ;margin-top:-3px ;">HARI / TANGGAL</h3></td>
        <td><h3 style="margin-bottom:-3px ;margin-top:-3px ;"> : </h3></td>
        <td><h3 style="margin-bottom:-3px ;margin-top:-3px ;">......................................................</h3></td>
      </tr>
    </table>
  </center>
  <br>
  <!-- <br>
    <?= $suratmasukinfo->ISI; ?>
  <br> -->
  <table style="width:100%">
    <thead>
      <tr>
        <th style="border: solid black 1pt;text-align: center; background-color: lightgray;">No</th>
        <th style="border: solid black 1pt;text-align: center; background-color: lightgray;">Nomor Inventaris</th>
        <th style="border: solid black 1pt;text-align: center; background-color: lightgray;">Nama & Posisi Barang</th>
        <th style="border: solid black 1pt;text-align: center; background-color: lightgray;">Uraian Kerusakan</th>
        <th style="border: solid black 1pt;text-align: center; background-color: lightgray;">Penyebab Kerusakan</th>
        <th style="border: solid black 1pt;text-align: center; background-color: lightgray;">Usaha Penanggulangan</th>
      </tr>
    </thead>
    <tbody>
      <?
      $KerusakanBarang = new SuratMasuk();
      $KerusakanBarang->selectByParamsKerusakan(array(), -1, -1, " AND SURAT_MASUK_ID =".$reqId);
      $no=1;
      while($KerusakanBarang->nextRow()){
          $reqKerusakanId = $KerusakanBarang->getField("SURAT_MASUK_KERUSAKAN_ID");
          $reqKerusakanInventaris = $KerusakanBarang->getField("NO_INVENTARIS");
          $reqKerusakanPosisi = $KerusakanBarang->getField("POSISI");
          $reqKerusakanNama = $KerusakanBarang->getField("NAMA");
          $reqKerusakanUraian = $KerusakanBarang->getField("KERUSAKAN");
          $reqKerusakanPenyebab = $KerusakanBarang->getField("PENYEBAB");
          $reqKerusakanPenanggulangan = $KerusakanBarang->getField("PENANGGULANGAN");
      ?>
          <tr>
              <td style="border: solid black 1pt;text-align: center;">
                  <?=$no?>
              </td>
              <td style="border: solid black 1pt;text-align: center;">
                  <?=$reqKerusakanInventaris?>
              </td>
              <td style="border: solid black 1pt;">
                  <?=$reqKerusakanNama?> ( <?=$reqKerusakanPosisi?> )
              </td>
              <td style="border: solid black 1pt;">
                  <?=$reqKerusakanUraian?>
              </td>        
              <td style="border: solid black 1pt;">
                  <?=$reqKerusakanPenyebab?>
              </td>        
              <td style="border: solid black 1pt;">
                  <?=$reqKerusakanPenanggulangan?>
              </td>
          </tr>
      <?
      $no++;
      }?>
    <tr>
      <td colspan="3" style="width:20%;border: solid black 1pt;text-align: center; background-color: lightgray;">Pembuat</td>
      <td style="width:20%;border: solid black 1pt;text-align: center; background-color: lightgray;">Pemeriksa</td>
      <td style="width:20%;border: solid black 1pt;text-align: center; background-color: lightgray;">Menyetujui</td>
      <td style="width:20%;border: solid black 1pt;text-align: center; background-color: lightgray;">Keterangan</td>
    </tr>
    <tr>
      <td colspan="3" style="width:20%;border: solid black 1pt;"><br><br><br></td>
      <td style="width:20%;border: solid black 1pt;"><br><br><br></td>
      <td style="width:20%;border: solid black 1pt;"><br><br><br></td>
      <td rowspan="2" style="width:20%;border: solid black 1pt;">
        <table style="margin-left:10px">
          <tr>
            <td style="width:20%">Putih</td>
            <td style="width:5%">:</td>
            <td>Dept. HRD & GA (Kantor Pusat)</td>
          </tr>
          <tr>
            <td style="width:20%">Merah</td>
            <td style="width:5%">:</td>
            <td>Arsip Pembuat</td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td colspan="3" style="width:20%;border: solid black 1pt;text-align: center;">Nama<br> Jabatan</td>
      <td style="width:20%;border: solid black 1pt;text-align: center;">Nama<br> Jabatan</td>
      <td style="width:20%;border: solid black 1pt;text-align: center;">Nama<br> Jabatan</td>
    </tr>
  </table>
</body>


