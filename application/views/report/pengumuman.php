<?php
/* INCLUDE FILE */
include_once("functions/date.func.php");
include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("libraries/vendor/autoload.php");

$this->load->library('suratmasukinfo');
$suratmasukinfo = new suratmasukinfo();

$reqId = httpFilterGet("reqId");
$reqJenisSurat = httpFilterGet("reqJenisSurat");
$suratmasukinfo->getInfo($reqId, $reqJenisSurat);
$telp=$suratmasukinfo->TELEPON_UNIT;
$fax=$suratmasukinfo->FAX_UNIT;
$alamat=$suratmasukinfo->ALAMAT_UNIT;
$alamatunit=str_replace(array('<p>', '</p>'), array('<i>', '</i>'), $alamat);
?>

<!-- Start Kop Surat -->
<div class="kop-surat" style="width:100%;  ">
    <div class="logo-kop" style="font-size: 8px; width:35%; text-align: right; margin-right: 70px  "><img src="<?= base_url(); ?>/images/logo-surat.jpg" width="120px" height="*" >
   <br>
    <br>
    <b>PT.ASDP Indonesia Ferry (Persero)</b><br>
    <i><?= $suratmasukinfo->NAMA_UNIT  ?></i><br>
    <?=$alamatunit?><br>
    <?
    if (!empty($telp))
    {
    ?>
      tel : <?=$suratmasukinfo->TELEPON_UNIT  ?> &nbsp;&nbsp;<br>
    <?
    }
    ?>
    <?
    if (!empty($fax))
    {
    ?>
      fax : <?=$suratmasukinfo->FAX_UNIT  ?>&nbsp;&nbsp;<br>
    <?
    }
    ?>

  <!--  <b>PT.ASDP Indonesia Ferry (Persero)</b><br>
    <i>Jl. Jend Ahmad Yani Kav. 52 A, Jakarta 10510,</i><br>
    <i> Indonesia</i><br>
    tel : +6221 4208911-13-15 <br>
    fax : +6221 4210544 <br>
    web : www.indonesiaferry.co.id -->

</div>
</div>
<!-- End Kop Surat -->

<!-- Start Jenis Naskah -->
<div class="jenis-naskah">
  <div class="nama-jenis-naskah"><u>PENGUMUMAN</u></div>
  <div class="nomor-naskah">NOMOR : <?= $suratmasukinfo->NOMOR ?></div>
</div>
<!-- End Jenis Naskah -->

<!-- Start Tentang Naskah -->
<div class="jenis-naskah">
  <div class="nomor-naskah">TENTANG</div>
  <div class="nomor-naskah"><?= $suratmasukinfo->PERIHAL ?></div>
</div>
<!-- End Tentang Naskah -->

<!-- Start Isi Naskah -->
<div class="isi-naskah">
  <?= $suratmasukinfo->ISI ?>
</div>
<!-- End Isi Naskah -->

<!-- Start Tanda Tangan -->
<div class="tanda-tangan-kanan">
  <table width="100%">
    <tr>
      <td width="40%">Dikeluarkan di</td>
      <td width="1%">:</td>
      <td width="59%"><?= $suratmasukinfo->LOKASI_UNIT ?></td>
    </tr>
    <tr class="border-bottom">
      <td>Pada tanggal</td>
      <td>:</td>
      <td><?= getFormattedDate2($suratmasukinfo->TANGGAL) ?></td>
    </tr>
  </table>
  <br><?= strtoupper($suratmasukinfo->USER_ATASAN_JABATAN) ?>
  <br>
  <?
  $ttdKode = $suratmasukinfo->TTD_KODE;
  if ($ttdKode == "" || $suratmasukinfo->JENIS_TTD == "BASAH") {
    echo "<br><br>";
  } else {
  ?>
    <img src="<?= base_url() ?>/<?= $suratmasukinfo->FOLDER_PATH ?>/<?= $suratmasukinfo->SURAT_MASUK_ID ?>/<?= $suratmasukinfo->TTD_KODE ?>.png" height="100px">
    <br>
<!--     <span style="font-size:10px;"><i>&nbsp;</i></span>
 -->  <?
  }
  ?>
  <br><u><b><?= strtoupper($suratmasukinfo->USER_ATASAN) ?></b></u>
</div>
<!-- End Isi Naskah -->

<!-- Start Tembusan -->
<?
if ($suratmasukinfo->TEMBUSAN == "") {
} else {
?>
  <div class="tembusan">
    <b><u>Tembusan Yth. :</u></b>
    <br>
    <?
    $arrTembusan = explode(";", $suratmasukinfo->TEMBUSAN);
    ?>
    <?
    $number = 1;
    for ($i = 0; $i < count($arrTembusan); $i++) {
    ?>
      <?= $number ?>. <?= $arrTembusan[$i] ?><br>
    <?
      $number++;
    }
    ?>
  </div>
<?
}
?>
<!-- End Tembusan -->


<!-- Start Maker Surat -->
<div class="maker-surat">
  <?
  $arrNama = explode(" ", $suratmasukinfo->NAMA_USER);
  $jumlahNama = count($arrNama);
  if ($jumlahNama > 1) {
    $inisial = substr($arrNama[0], 0, 1);
    $lastname = $arrNama[$jumlahNama - 1];
    $alias = $inisial . "." . $lastname;
  } else
    $alias = $arrNama[0];
  ?>
  <i style="font-size:9px;"><?= $suratmasukinfo->KODE_UNIT ?>/<?= $suratmasukinfo->KD_SURAT ?>/<?= strtoupper($alias) ?></i>
</div>
<!-- End Maker Surat -->