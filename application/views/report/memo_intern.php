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
?>

<div class="margin-atas"></div>

<!-- Start Jenis Naskah -->
<div class="jenis-naskah-memo-intern">
  <div class="nama-jenis-naskah-memo-intern">MEMO INTERN
    <br>
    <span><?= $suratmasukinfo->INSTANSI_ASAL ?></span>
  </div>
  <div class="hologram-naskah-memo-intern"><img src="<?= base_url(); ?>/images/logo-surat.png" width="220px" height="*"></div>
</div>
<!-- End Jenis Naskah -->
<!-- Start Pembatas -->
<div class="pembatas"></div>
<!-- End Pembatas -->

<!-- Start Tujuan Naskah -->
<div class="tujuan-naskah">
  <table width="100%">
    <tr>
      <td width="20%">KEPADA YTH.</td>
      <td width="1%">:</td>
      <td width="79%">
        <?
        $arrayKepada =  explode(',', strtoupper($suratmasukinfo->KEPADA));
        if (count($arrayKepada) == 1) {
          echo strtoupper($suratmasukinfo->KEPADA);
        } else {
        ?>
          <ol>
            <?
            foreach ($arrayKepada as $itemKepada) {
            ?>
              <li><?= $itemKepada ?></li>
            <?
            }
            ?>
          </ol>
        <?
        }
        ?>
      </td>
    </tr>
    <tr>
      <td>DARI</td>
      <td>:</td>
      <td><?= strtoupper($suratmasukinfo->INSTANSI_ASAL) ?></td>
    </tr>
    <tr>
      <td>PERIHAL</td>
      <td>:</td>
      <td><?= $suratmasukinfo->PERIHAL ?></td>
    </tr>
  </table>
</div>
<!-- End Tujuan Naskah -->

<!-- Start Pembatas -->
<div class="pembatas"></div>
<!-- End Pembatas -->

<!-- Start Isi Naskah -->
<div class="isi-naskah">
  <?= $suratmasukinfo->ISI ?>
</div>
<!-- End Isi Naskah -->

<!-- Start Tanda Tangan -->
<div class="tanda-tangan-kiri">
  <?= $suratmasukinfo->LOKASI_UNIT ?>, <?= getFormattedDate($suratmasukinfo->TANGGAL) ?><br>
  <?= strtoupper($suratmasukinfo->USER_ATASAN_JABATAN) ?><br>
  <?
  $ttdKode = $suratmasukinfo->TTD_KODE;
  if ($ttdKode == "") {
    echo "<br><br>";
  } else {
  ?>
    <img src="<?= base_url() ?>/<?= $suratmasukinfo->FOLDER_PATH ?>/<?= $suratmasukinfo->SURAT_MASUK_ID ?>/<?= $suratmasukinfo->TTD_KODE ?>.png" height="*">
    <br>
    <span style="font-size:10px;"><i>&nbsp;</i></span>
  <?
  }
  ?>
  <br>
  <u><b><?= strtoupper($suratmasukinfo->USER_ATASAN) ?></b></u>
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