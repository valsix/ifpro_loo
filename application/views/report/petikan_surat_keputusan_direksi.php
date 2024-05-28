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
$suratmasukinfo->getInfoAsc($reqId, $reqJenisSurat);
$telp=$suratmasukinfo->TELEPON_UNIT;
$fax=$suratmasukinfo->FAX_UNIT;
$alamat=$suratmasukinfo->ALAMAT_UNIT;
$an_status = $suratmasukinfo->AN_STATUS;
$an_nama = $suratmasukinfo->AN_NAMA;
$alamatunit=str_replace(array('<p>', '</p>'), array('<i>', '</i>'), $alamat);

$this->load->model("SuratMasuk");
$suratmasuk = new SuratMasuk();
$suratmasuk->selectByParamsPltJabatan(array("A.SURAT_MASUK_ID" => $reqId));
$suratmasuk->firstRow();
$reqJabatan                    = $suratmasuk->getField("JABATAN");
$reqAtasanJabatan                    = $suratmasuk->getField("USER_ATASAN_JABATAN");
$reqAnTambahan                    = $suratmasuk->getField("AN_TAMBAHAN");

?>
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>-->
<script>
$(document).ready(function(){
    judul= $("#judul").text();
    nomor= $("#nomor").text();
    tentang= $("#tentang").text();
    perihal= $("#perihal").text();
    menimbang= $("#menimbang").text();
    memutuskan= $("#memutuskan").text();
    menetapkan= $("#menetapkan").text();
    ttd= $("#ttd").text();
    kepada= $("#kepada").text();
    tembusan= $("#tembusan").text();
    maker= $("#maker").text();
    total= menimbang.length+menetapkan.length
    console.log(total);
});
</script>
<link href="<?= base_url() ?>css/gaya-surat.css" rel="stylesheet" type="text/css">
<body>

<table>
    <tr>
      <td>
        <img src="<?=base_url().'images/logo.png'?>" height="100px">
      </td>
    </tr>
    <tr>
      <td style="text-align:right;font-size: 9pt;">
        <b><u>PT. INDONESIA FERRY PROPERTI</u></b><br>
        Gedung PT. ASDP Indonesia Ferry (Persero) <br>
        Jl. Jenderal Ahmad Yani Kav 52A<br>
        Jakarta Pusat 10510<br>
        contact@ifpro.co.id | <u>www.ifpro.co.id</u>
      </td>
    </tr>
  </table>
  <br>
  <br>
  <br>
  <table style="width:100%;font-size: 12pt;">
    <tr>
      <td style="width:10%">
        Nomor 
      </td>
      <td style="width:40%">
        : <?= $suratmasukinfo->NOMOR ?>
      </td>
      <td style="width:25%">
      </td>
      <td rowspan="3" style="width:25%">
        <?=$suratmasukinfo->LOKASI_UNIT?> <?=getFormattedDateTime($suratmasukinfo->APPROVAL_QR_DATE, false)?><br>
        Kepada:<br>
        Yth.
        <!-- Yth. Vice President Komersial<br> -->
         <?
            $arrayKepada= explode("xxx", $suratmasukinfo->KEPADA_PARAM);
            if (count($arrayKepada) == 1) {
              echo $suratmasukinfo->KEPADA;
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
        PT. Indonesia Ferry Properti<br> 
        di-<br>
        <u><b>TEMPAT</b></u>
      </td>
    </tr>
    <tr>
      <td style="width:10%">
        Lampiran 
      </td>
      <td style="width:40%">
        : -
      </td>
    </tr>
    <tr>
      <td style="width:10%">
        Perihal 
      </td>
      <td style="width:40%">
        : <?= $suratmasukinfo->PERIHAL; ?>
      </td>
    </tr>
  </table>
<!-- End Kop Surat -->

	<?
    $surat_masuk = new SuratMasuk();
    $surat_masuk->selectByParams(array("A.SURAT_MASUK_ID" => $reqId), -1, -1, $statement);
    $surat_masuk->firstRow();
    $reqMenimbang=  $surat_masuk->getField("MENIMBANG"); $totMenimbang= strlen($reqMenimbang);
    $reqMengingat= $surat_masuk->getField("MENGINGAT"); $totMengingat= strlen($reqMengingat);
    $reqMemperhatikan= $surat_masuk->getField("MEMPERHATIKAN"); $totMemperhatikan= strlen($reqMemperhatikan);
    $reqMenetapkan=  $surat_masuk->getField("MENETAPKAN"); $totMenetapkan= strlen($reqMenetapkan);
    $reqPertama= $surat_masuk->getField("PERTAMA"); $totPertama= strlen($reqPertama);
    $reqKedua= $surat_masuk->getField("KEDUA"); $totKedua= strlen($reqKedua);
    $reqKetiga= $surat_masuk->getField("KETIGA"); $totKetiga= strlen($reqKetiga);
    $reqKeempat=$surat_masuk->getField("KEEMPAT"); $totKeempat= strlen($reqKeempat);
    $reqKelima= $surat_masuk->getField("KELIMA"); $totKelima= strlen($reqKelima);
    $reqKeenam= $surat_masuk->getField("KEENAM"); $totKeenam= strlen($reqKeenam);
    $reqKetujuh= $surat_masuk->getField("KETUJUH"); $totKetujuh= strlen($reqKetujuh);
    $reqKedelapan= $surat_masuk->getField("KEDELAPAN"); $totKedelapan= strlen($reqKedelapan);
    $reqKesembilan= $surat_masuk->getField("KESEMBILAN"); $totKesembilan= strlen($reqKesembilan);
    $reqKesepuluh= $surat_masuk->getField("KESEPULUH"); $totKesepuluh= strlen($reqKesepuluh);
    $reqKesebelas= $surat_masuk->getField("KESEBELAS"); $totKesebelas= strlen($reqKesebelas);
    $reqKeduabelas= $surat_masuk->getField("KEDUABELAS"); $totKeduabelas= strlen($reqKeduabelas);
    $reqKetigabelas= $surat_masuk->getField("KETIGABELAS"); $totKetigabelas= strlen($reqKetigabelas);
    $reqKeempatbelas= $surat_masuk->getField("KEEMPATBELAS"); $totKeempatbelas= strlen($reqKeempatbelas);
    $reqKelimabelas= $surat_masuk->getField("KELIMABELAS"); $totKelimabelas= strlen($reqKelimabelas);
    $reqKeenambelas= $surat_masuk->getField("KEENAMBELAS"); $totKeenambelas= strlen($reqKeenambelas);
    $reqKetujuhbelas= $surat_masuk->getField("KETUJUHBELAS"); $totKetujuhbelas= strlen($reqKetujuhbelas);
    $reqKedelapanbelas= $surat_masuk->getField("KEDELAPANBELAS"); $totKedelapanbelas= strlen($reqKedelapanbelas);
    $reqKesembilanbelas= $surat_masuk->getField("KESEMBILANBELAS"); $totKesembilanbelas= strlen($reqKesembilanbelas);
    $reqKeduapuluh= $surat_masuk->getField("KEDUAPULUH"); $totKeduapuluh= strlen($reqKeduapuluh);
    $reqKeduapuluhsatu= $surat_masuk->getField("KEDUAPULUHSATU"); $totKeduapuluhsatu= strlen($reqKeduapuluhsatu);
    $reqKeduapuluhdua= $surat_masuk->getField("KEDUAPULUHDUA"); $totKeduapuluhdua= strlen($reqKeduapuluhdua);
    $reqKeduapuluhtiga= $surat_masuk->getField("KEDUAPULUHTIGA"); $totKeduapuluhtiga= strlen($reqKeduapuluhtiga);
    $reqKeduapuluhempat= $surat_masuk->getField("KEDUAPULUHEMPAT"); $totKeduapuluhempat= strlen($reqKeduapuluhempat);
    $reqKeduapuluhlima= $surat_masuk->getField("KEDUAPULUHLIMA"); $totKeduapuluhlima= strlen($reqKeduapuluhlima);
// echo $totMenimbang+$totMengingat+$totMenetapkan;exit();
    if (($totMenimbang+$totMengingat) >= 3200) 
    {
       $break1=1;
    } 
    elseif (($totMenimbang+$totMengingat+$totMenetapkan) >= 3500) 
    {
       $break2=1;
    }
    elseif (($totMenimbang+$totMengingat+$totMenetapkan+$totPertama+strlen($suratmasukinfo->LOKASI_UNIT)+strlen($suratmasukinfo->TANGGAL)+strlen($suratmasukinfo->USER_ATASAN_JABATAN)+strlen($suratmasukinfo->USER_ATASAN)) >= 3200) 
    {
      $break3=1;
    }
    elseif (($totMenimbang+$totMengingat+$totMenetapkan+$totPertama+$totKedua+strlen($suratmasukinfo->LOKASI_UNIT)+strlen($suratmasukinfo->TANGGAL)+strlen($suratmasukinfo->USER_ATASAN_JABATAN)+strlen($suratmasukinfo->USER_ATASAN)) >= 3200) 
    {
       $break4=1;
    }
    elseif (($totMenimbang+$totMengingat+$totMenetapkan+$totPertama+$totKedua+$totKetiga+strlen($suratmasukinfo->LOKASI_UNIT)+strlen($suratmasukinfo->TANGGAL)+strlen($suratmasukinfo->USER_ATASAN_JABATAN)+strlen($suratmasukinfo->USER_ATASAN)) >= 3200) 
    {
       $break5=1;
    }
    elseif (($totMenimbang+$totMengingat+$totMenetapkan+$totPertama+$totKedua+$totKetiga+$totKeempat+$totKelima+strlen($suratmasukinfo->LOKASI_UNIT)+strlen($suratmasukinfo->TANGGAL)+strlen($suratmasukinfo->USER_ATASAN_JABATAN)+strlen($suratmasukinfo->USER_ATASAN)) >= 3200)
    {
       $break6=1;
    }

	?>
<hr style="border: 2px solid black">

<div class="nomor-naskah" id="direksi" style="text-transform: uppercase;">DIREKSI PT. ASDP INDONESIA FERRY (PERSERO)</div>

<table id="menimbang" style="page-break-inside: avoid;">
  <tr style="page-break-inside: avoid;">
    <td style="padding: 4px 8px; width: 150px;">Menimbang</td>
    <td style="padding: 4px 8px; width: 20px;">:</td>
    <td style="padding: 4px 8px; text-align: justify;"><?= $reqMenimbang?></td>
  </tr>
</table>

  <?php if ($break1==1): ?>
    <pagebreak />
  <?php endif ?>
  
<table id="mengingat" style="page-break-inside: avoid;">
  <tr>
    <td style="padding: 4px 8px; width: 150px;">Mengingat</td>
    <td style="padding: 4px 8px; width: 20px;">:</td>
    <td style="padding: 4px 8px; text-align: justify;"><?= $reqMengingat?></td>
  </tr>
</table>

  <?php if ($break==1): ?>
    <pagebreak />
  <?php endif ?>

<table id="memperhatikan" style="page-break-inside: avoid;">
  <tr>
    <td style="padding: 4px 8px; width: 150px;">Memperhatikan</td>
    <td style="padding: 4px 8px; width: 20px;">:</td>
    <td style="padding: 4px 8px; text-align: justify;"><?= $reqMemperhatikan?></td>
  </tr>
</table>

  <?php if ($break2==1): ?>
    <pagebreak />
  <?php endif ?>

  <br>
  <div class="nama-jenis-naskah" style="letter-spacing: 2px;">
    <b id="memutuskan">MEMUTUSKAN :</b><br>
  </div>
  <br>

<table id="menetapkan" style="page-break-inside: avoid;">
   <tr >
    <td style="padding: 4px 8px; width: 150px;">Menetapkan</td>
    <td style="padding: 4px 8px; width: 20px;">:</td>
    <td style="padding: 4px 8px; text-align: justify; text-transform: uppercase;"><b><?= $reqMenetapkan?></b></td>
  </tr>
</table>

  <?php if ($break3==1): ?>
    <pagebreak />
  <?php endif ?>

<table id="pertama" style="page-break-inside: avoid;">
  <tr >
    <td style="padding: 4px 8px; width: 150px; text-transform: uppercase;">Pertama</td>
    <td style="padding: 4px 8px; width: 20px;">:</td>
    <td style="padding: 4px 8px; text-align: justify;"><?= $reqPertama?></td>
  </tr>
</table>

  <?php if ($break4==1): ?>
    <pagebreak />
  <?php endif ?>

<table id="kedua" style="page-break-inside: avoid;">
  <?
  if ($reqKedua!="") {
    ?>
      <tr >
        <td style="padding: 4px 8px; width: 150px; text-transform: uppercase;">Kedua</td>
        <td style="padding: 4px 8px; width: 20px;">:</td>
        <td style="padding: 4px 8px; text-align: justify;"><?= $reqKedua?></td>
      </tr>
    <?
  }
  ?>
</table>

  <?php if ($break5==1): ?>
    <pagebreak />
  <?php endif ?>

<?
  if ($reqKetiga!="") {
    ?>
    <table id="ketiga" style="page-break-inside: avoid;">
      <tr >
        <td style="padding: 4px 8px; width: 150px; text-transform: uppercase;">Ketiga</td>
        <td style="padding: 4px 8px; width: 20px;">:</td>
        <td style="padding: 4px 8px; text-align: justify;"><?= $reqKetiga?></td>
      </tr>
    </table>
    <?
  }
?>

  <?php if ($break6==1): ?>
    <pagebreak />
  <?php endif ?>

<?
  if ($reqKeempat!="") {
    ?>
    <table id="keempat" style="page-break-inside: avoid;">
      <tr >
        <td style="padding: 4px 8px; width: 150px; text-transform: uppercase;">Keempat</td>
        <td style="padding: 4px 8px; width: 20px;">:</td>
        <td style="padding: 4px 8px; text-align: justify;"><?= $reqKeempat?></td>
      </tr>
    </table>
    <?
  }
?>

  <?php if ($break7==1): ?>
    <pagebreak />
  <?php endif ?>

<?
  if ($reqKelima!="") {
    ?>
    <table id="kelima" style="page-break-inside: avoid;">
      <tr >
        <td style="padding: 4px 8px; width: 150px; text-transform: uppercase;">Kelima</td>
        <td style="padding: 4px 8px; width: 20px;">:</td>
        <td style="padding: 4px 8px; text-align: justify;"><?= $reqKelima?></td>
      </tr>
    </table>
    <?
  }
?>

  <?php if ($break8==1): ?>
    <pagebreak />
  <?php endif ?>

<?
  if ($reqKeenam!="") {
    ?>
    <table id="keenam" style="page-break-inside: avoid;">
      <tr >
        <td style="padding: 4px 8px; width: 150px; text-transform: uppercase;">Keenam</td>
        <td style="padding: 4px 8px; width: 20px;">:</td>
        <td style="padding: 4px 8px; text-align: justify;"><?= $reqKeenam?></td>
      </tr>
    </table>
    <?
  }
?>

  <?php if ($break9==1): ?>
    <pagebreak />
  <?php endif ?>

<?
  if ($reqKetujuh!="") {
    ?>
    <table id="ketujuh" style="page-break-inside: avoid;">
      <tr >
        <td style="padding: 4px 8px; width: 150px; text-transform: uppercase;">Ketujuh</td>
        <td style="padding: 4px 8px; width: 20px;">:</td>
        <td style="padding: 4px 8px; text-align: justify;"><?= $reqKetujuh?></td>
      </tr>
    </table>
    <?
  }
?>

  <?php if ($break10==1): ?>
    <pagebreak />
  <?php endif ?>

<?
  if ($reqKedelapan!="") {
    ?>
    <table id="kedelapan" style="page-break-inside: avoid;">
      <tr >
        <td style="padding: 4px 8px; width: 150px; text-transform: uppercase;">Kedelapan</td>
        <td style="padding: 4px 8px; width: 20px;">:</td>
        <td style="padding: 4px 8px; text-align: justify;"><?= $reqKedelapan?></td>
      </tr>
    </table>
    <?
  }
?>

  <?php if ($break11==1): ?>
    <pagebreak />
  <?php endif ?>

<?
  if ($reqKesembilan!="") {
    ?>
    <table id="kesembilan" style="page-break-inside: avoid;">
      <tr >
        <td style="padding: 4px 8px; width: 150px; text-transform: uppercase;">Kesembilan</td>
        <td style="padding: 4px 8px; width: 20px;">:</td>
        <td style="padding: 4px 8px; text-align: justify;"><?= $reqKesembilan?></td>
      </tr>
    </table>
    <?
  }
?>

  <?php if ($break12==1): ?>
    <pagebreak />
  <?php endif ?>

<?
  if ($reqKesepuluh!="") {
    ?>
    <table id="kesepuluh" style="page-break-inside: avoid;">
      <tr >
        <td style="padding: 4px 8px; width: 150px; text-transform: uppercase;">Kesepuluh</td>
        <td style="padding: 4px 8px; width: 20px;">:</td>
        <td style="padding: 4px 8px; text-align: justify;"><?= $reqKesepuluh?></td>
      </tr>
    </table>
    <?
  }
?>

<?
  if ($reqKesebelas!="") {
    ?>
    <table id="kesebelas" style="page-break-inside: avoid;">
      <tr >
        <td style="padding: 4px 8px; width: 150px; text-transform: uppercase;">Kesebelas</td>
        <td style="padding: 4px 8px; width: 20px;">:</td>
        <td style="padding: 4px 8px; text-align: justify;"><?= $reqKesebelas?></td>
      </tr>
    </table>
    <?
  }
?>

<?
  if ($reqKeduabelas!="") {
    ?>
    <table id="keduabelas" style="page-break-inside: avoid;">
      <tr >
        <td style="padding: 4px 8px; width: 150px; text-transform: uppercase;">Keduabelas</td>
        <td style="padding: 4px 8px; width: 20px;">:</td>
        <td style="padding: 4px 8px; text-align: justify;"><?= $reqKeduabelas?></td>
      </tr>
    </table>
    <?
  }
?>

<?
  if ($reqKetigabelas!="") {
    ?>
    <table id="ketigabelas" style="page-break-inside: avoid;">
      <tr >
        <td style="padding: 4px 8px; width: 150px; text-transform: uppercase;">Ketigabelas</td>
        <td style="padding: 4px 8px; width: 20px;">:</td>
        <td style="padding: 4px 8px; text-align: justify;"><?= $reqKetigabelas?></td>
      </tr>
    </table>
    <?
  }
?>

<?
  if ($reqKeempatbelas!="") {
    ?>
    <table id="keempatbelas" style="page-break-inside: avoid;">
      <tr >
        <td style="padding: 4px 8px; width: 150px; text-transform: uppercase;">Keempatbelas</td>
        <td style="padding: 4px 8px; width: 20px;">:</td>
        <td style="padding: 4px 8px; text-align: justify;"><?= $reqKeempatbelas?></td>
      </tr>
    </table>
    <?
  }
?>

<?
  if ($reqKelimabelas!="") {
    ?>
    <table id="kelimabelas" style="page-break-inside: avoid;">
      <tr >
        <td style="padding: 4px 8px; width: 150px; text-transform: uppercase;">Kelimabelas</td>
        <td style="padding: 4px 8px; width: 20px;">:</td>
        <td style="padding: 4px 8px; text-align: justify;"><?= $reqKelimabelas?></td>
      </tr>
    </table>
    <?
  }
?>

<?
  if ($reqKeenambelas!="") {
    ?>
    <table id="keenambelas" style="page-break-inside: avoid;">
      <tr >
        <td style="padding: 4px 8px; width: 150px; text-transform: uppercase;">Keenambelas</td>
        <td style="padding: 4px 8px; width: 20px;">:</td>
        <td style="padding: 4px 8px; text-align: justify;"><?= $reqKeenambelas?></td>
      </tr>
    </table>
    <?
  }
?>

<?
  if ($reqKetujuhbelas!="") {
    ?>
    <table id="ketujuhbelas" style="page-break-inside: avoid;">
      <tr >
        <td style="padding: 4px 8px; width: 150px; text-transform: uppercase;">Ketujuhbelas</td>
        <td style="padding: 4px 8px; width: 20px;">:</td>
        <td style="padding: 4px 8px; text-align: justify;"><?= $reqKetujuhbelas?></td>
      </tr>
    </table>
    <?
  }
?>

<?
  if ($reqKedelapanbelas!="") {
    ?>
    <table id="kedelapanbelas" style="page-break-inside: avoid;">
      <tr >
        <td style="padding: 4px 8px; width: 150px; text-transform: uppercase;">Kedelapanbelas</td>
        <td style="padding: 4px 8px; width: 20px;">:</td>
        <td style="padding: 4px 8px; text-align: justify;"><?= $reqKedelapanbelas?></td>
      </tr>
    </table>
    <?
  }
?>

<?
  if ($reqKesembilanbelas!="") {
    ?>
    <table id="kesembilanbelas" style="page-break-inside: avoid;">
      <tr >
        <td style="padding: 4px 8px; width: 150px; text-transform: uppercase;">Kesembilanbelas</td>
        <td style="padding: 4px 8px; width: 20px;">:</td>
        <td style="padding: 4px 8px; text-align: justify;"><?= $reqKesembilanbelas?></td>
      </tr>
    </table>
    <?
  }
?>

<?
  if ($reqKeduapuluh!="") {
    ?>
    <table id="keduapuluh" style="page-break-inside: avoid;">
      <tr >
        <td style="padding: 4px 8px; width: 150px; text-transform: uppercase;">Keduapuluh</td>
        <td style="padding: 4px 8px; width: 20px;">:</td>
        <td style="padding: 4px 8px; text-align: justify;"><?= $reqKeduapuluh?></td>
      </tr>
    </table>
    <?
  }
?>

<?
  if ($reqKeduapuluhsatu!="") {
    ?>
    <table id="keduapuluhsatu" style="page-break-inside: avoid;">
      <tr >
        <td style="padding: 4px 8px; width: 150px; text-transform: uppercase;">Keduapuluhsatu</td>
        <td style="padding: 4px 8px; width: 20px;">:</td>
        <td style="padding: 4px 8px; text-align: justify;"><?= $reqKeduapuluhsatu?></td>
      </tr>
    </table>
    <?
  }
?>

<?
  if ($reqKeduapuluhdua!="") {
    ?>
    <table id="keduapuluhdua" style="page-break-inside: avoid;">
      <tr >
        <td style="padding: 4px 8px; width: 150px; text-transform: uppercase;">Keduapuluhdua</td>
        <td style="padding: 4px 8px; width: 20px;">:</td>
        <td style="padding: 4px 8px; text-align: justify;"><?= $reqKeduapuluhdua?></td>
      </tr>
    </table>
    <?
  }
?>

<?
  if ($reqKeduapuluhtiga!="") {
    ?>
    <table id="keduapuluhtiga" style="page-break-inside: avoid;">
      <tr >
        <td style="padding: 4px 8px; width: 150px; text-transform: uppercase;">Keduapuluhtiga</td>
        <td style="padding: 4px 8px; width: 20px;">:</td>
        <td style="padding: 4px 8px; text-align: justify;"><?= $reqKeduapuluhtiga?></td>
      </tr>
    </table>
    <?
  }
?>

<?
  if ($reqKeduapuluhempat!="") {
    ?>
    <table id="keduapuluhempat" style="page-break-inside: avoid;">
      <tr >
        <td style="padding: 4px 8px; width: 150px; text-transform: uppercase;">Keduapuluhempat</td>
        <td style="padding: 4px 8px; width: 20px;">:</td>
        <td style="padding: 4px 8px; text-align: justify;"><?= $reqKeduapuluhempat?></td>
      </tr>
    </table>
    <?
  }
?>

<?
  if ($reqKeduapuluhlima!="") {
    ?>
    <table id="keduapuluhlima" style="page-break-inside: avoid;">
      <tr >
        <td style="padding: 4px 8px; width: 150px; text-transform: uppercase;">Keduapuluhlima</td>
        <td style="padding: 4px 8px; width: 20px;">:</td>
        <td style="padding: 4px 8px; text-align: justify;"><?= $reqKeduapuluhlima?></td>
      </tr>
    </table>
    <?
  }
?>

<!-- Start Jenis Naskah -->
<!-- <div class="jenis-naskah">
  <div class="nama-jenis-naskah"><u style="text-transform:uppercase;">Surat Perintah Tugas</u></div>
  <div class="nomor-naskah">NO : <?= $suratmasukinfo->NOMOR ?></div>
</div> -->
<!-- End Jenis Naskah -->

<!-- Start Tentang Naskah -->
<!-- <div class="jenis-naskah">
</div> -->
<!-- End Tentang Naskah -->

<!-- Start Isi Naskah -->
<div class="isi-naskah">
  <?
  $visi= str_replace("uploads/froala/",base_url()."/uploads/froala/",$suratmasukinfo->ISI);
  $visi= str_replace("font", "fontxx", $visi);
  $visi= str_replace("https://eoffice.myifpro.co.id/https://eoffice.myifpro.co.id//", base_url(),$visi);
  $visi= preg_replace('/[^(\x20-\x7F)\x0A\x0D]*/','', $visi);
  echo $visi;
  ?>
</div>
   <!--  <div class="isi-naskah">
      <table width="100%">
        <tr>
          <td width="20%"><b>Menimbang</b></td>
          <td width="1%">:</td>
          <td width="79%">
            <ol type="a">
              <li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas sed faucibus dui. Morbi tempor, risus at sodales fringilla, ligula lorem tincidunt elit, ac efficitur ligula dolor id lorem. Donec vel augue lectus. Proin facilisis metus quis mi rhoncus luctus.</li>
              <li>Pellentesque ultrices placerat eros id luctus. Pellentesque vestibulum bibendum eros id posuere. Pellentesque ornare tincidunt lacus quis iaculis. Ut at consectetur metus. Proin dignissim nunc at nulla auctor sagittis.</li>
            </ol>
          </td>
        </tr>
        <tr>
          <td><b>Mengingat</b></td>
          <td>:</td>
          <td>
            <ol>
              <li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas sed faucibus dui. Morbi tempor, risus at sodales fringilla, ligula lorem tincidunt elit, ac efficitur ligula dolor id lorem. Donec vel augue lectus. Proin facilisis metus quis mi rhoncus luctus.</li>
              <li>Pellentesque ultrices placerat eros id luctus. Pellentesque vestibulum bibendum eros id posuere. Pellentesque ornare tincidunt lacus quis iaculis. Ut at consectetur metus. Proin dignissim nunc at nulla auctor sagittis.</li>
            </ol>
          </td>
        </tr>
        <tr>
          <td colspan="3" align="center">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="3" align="center"><b>MEMUTUSKAN :</b></td>
        </tr>
        <tr>
          <td colspan="3" align="center">&nbsp;</td>
        </tr>
        <tr>
          <td><b>Menetapkan</b></td>
          <td>:</td>
          <td></td>
        </tr>
      </table>
    </div> -->
<!-- End Isi Naskah -->

<!-- Start Tanda Tangan -->
<table style="width: 100%;">
<tr>
<td width="70%"></td>
<td style="text-align: right;">
  <table style="font-size: 12pt;">
    <tr>
      <td style="text-align: left;" width="40%">Ditetapkan di</td>
      <td style="text-align: left;" width="1%">:</td>
      <td style="text-align: left;" width="59%"><?= $suratmasukinfo->LOKASI_UNIT ?></td>
    </tr>
    <tr class="border-bottom">
      <td style="text-align: left;" >Pada tanggal</td>
      <td style="text-align: left;" >:</td>
      <td style="text-align: left;" >
        <!-- <?= getFormattedDate2($suratmasukinfo->TANGGAL) ?> -->
        <?=getFormattedDateTime($suratmasukinfo->APPROVAL_QR_DATE, false)?>
      </td>
    </tr>
    <tr class="border-bottom">
      <td colspan="3" style="text-align: center; border: none;">
      	<br>&nbsp;
		      <?
          if ($an_status == 1)
          {
            ?>
            <br>A.n <?=$an_nama?>
            <?
          }
          ?>
          <br><?= strtoupper($suratmasukinfo->USER_ATASAN_JABATAN) ?>
          <?
          if(stripos($reqAtasanJabatan, 'plt') !== false || stripos($reqAtasanJabatan, 'plh') !== false)  {
            ?>
            <div style="line-height:150%;">
                  <span><?=strtoupper($reqJabatan)?></span>
            </div>
          <?
          }
          ?>
          <br>
          <?
          $ttdKode = $suratmasukinfo->TTD_KODE;
          if ($ttdKode == "" || $suratmasukinfo->JENIS_TTD == "BASAH")
            echo "<br><br>";
          else {
          ?>
            <!-- <img src="<?= base_url() ?>/<?= $suratmasukinfo->FOLDER_PATH ?>/<?= $suratmasukinfo->SURAT_MASUK_ID ?>/<?= $suratmasukinfo->TTD_KODE ?>.png" height="100px"> -->
            <img src="/var/www/html/<?= $suratmasukinfo->FOLDER_PATH ?>/<?= $suratmasukinfo->SURAT_MASUK_ID ?>/<?= $suratmasukinfo->TTD_KODE ?>.png" height="100px">
            <br>
          <!--     <span style="font-size:10px;"><i>&nbsp;</i></span>
          -->  <?
          }
          ?>
          <u>Untuk Petikan</u>
          <br>Dipetik sesuai dengan bunyi aslinya oleh:
          <br><?= strtoupper($suratmasukinfo->USER_ATASAN_PETIKAN) ?>

          <br><br>
          <br><u><b><?= strtoupper($suratmasukinfo->USER_ATASAN) ?></b></u>
      </td>
    </tr>
  </table>
  
</td>
</tr>
</table>
<!-- END Tanda Tangan -->
<!-- End Isi Naskah -->

<!-- Start Kepada -->
<?
if ($suratmasukinfo->KEPADA == "") {
} else {
?>
  <div class="tembusan" id="kepada">
   <!--  <b>SALINAN</b> Surat Keputusan Direksi ini 
    <br> -->
    <!-- <u>disampaikan kepada Yth :</u> -->
    <u>Kepada Yth :</u>
    <?
    $arrKepada= explode("xxx", $suratmasukinfo->KEPADA_PARAM);
    ?>
    <ol type="1">
      <?
      for ($i = 0; $i < count($arrKepada); $i++) {
      ?>
        <li>Sdr. <?= $arrKepada[$i] ?></li>
       <!--  <p>Sdr. <?= $arrKepada[$i] ?></p> -->
      <?
      }
      ?>
    </ol>
    di -
    <br>
    <u>T&nbsp;E&nbsp;M&nbsp;P&nbsp;A&nbsp;T</u>
  </div>
<?
}
?>
<!-- End Kepada -->

<!-- Start Tembusan -->
<?/*
if ($suratmasukinfo->TEMBUSAN == "") {
} else {
?>
  <div class="tembusan" id="tembusan">
    <b>SALINAN</b> Surat Keputusan Direksi ini 
    <br>
    <u>disampaikan kepada Yth :</u>
    <?
    $arrTembusan = explode(",", $suratmasukinfo->TEMBUSAN);
    ?>
    <ol type="1">
      <?
      for ($i = 0; $i < count($arrTembusan); $i++) {
      ?>
        <li><?= $arrTembusan[$i] ?></li>
      <?
      }
      ?>
    </ol>
  </div>
<?
}
*/?>
<!-- End Tembusan -->

<!-- Start Maker Surat -->
<!-- <div class="maker-surat" id="maker">
  <?/*
  $arrNama = explode(" ", $suratmasukinfo->NAMA_USER);
  $jumlahNama = count($arrNama);
  if ($jumlahNama > 1) {
    $inisial = substr($arrNama[0], 0, 1);
    $lastname = $arrNama[$jumlahNama - 1];
    $alias = $inisial . "." . $lastname;
  } else
    $alias = $arrNama[0];
  ?>
  <i style="font-size:9px;"><?= $suratmasukinfo->KODE_UNIT ?>/<?= $suratmasukinfo->KD_SURAT ?>/<?= strtoupper($alias) */?></i>
</div>
 -->
<!-- <style>
  .page-break {
    page-break-after: always;
}

.page-break:last-child {
    page-break-after: avoid;
}
  </style>
 -->

<!-- End Maker Surat -->