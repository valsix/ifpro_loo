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
  <table style="width: 100%">
    <tr>
      <td>
        <center>
          <img src="<?=base_url().'images/logo.png'?>" height="100px">
        </center>
      </td>
    </tr>
  </table>
  <div class="nama-jenis-naskah" style="font-size: 15px; margin-top: -15px">
    <br>
    <br>
    <b><u style="text-transform:uppercase;" id="judul" >SURAT KEPUTUSAN DIREKSI PT INDONESIA FERRY PROPERTI</u></b><br>
    <div id="nomor" class="nomor-naskah" style="font-size: 15px; text-align: center; margin-right: 100px ">Nomor : <?= $suratmasukinfo->NOMOR ?></div>
  </div>
  <div class="nomor-naskah" id="tentang" style="font-size: 14px">tentang</div>
  <div class="nomor-naskah" id="perihal" style="font-size: 15px; text-transform: uppercase;"><strong><?=$suratmasukinfo->PERIHAL?></strong></div>

  <div class="nomor-naskah" style="font-size: 15px">
    <hr/>
    DIREKSI PT INDONESIA FERRY PROPERTI
  </div>

  
<!-- End Kop Surat -->

	<?
    $surat_masuk = new SuratMasuk();
    $surat_masuk->selectByParams(array("A.SURAT_MASUK_ID" => $reqId), -1, -1, $statement);
    $surat_masuk->firstRow();
    $reqMenimbang=  $surat_masuk->getField("MENIMBANG"); $totMenimbang= strlen($reqMenimbang);
    $reqMengingat= $surat_masuk->getField("MENGINGAT"); $totMengingat= strlen($reqMengingat);
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
       // $break1=1;
    } 
    elseif (($totMenimbang+$totMengingat+$totMenetapkan) >= 3500) 
    {
       // $break2=1;
    }
    elseif (($totMenimbang+$totMengingat+$totMenetapkan+$totPertama+strlen($suratmasukinfo->LOKASI_UNIT)+strlen($suratmasukinfo->TANGGAL)+strlen($suratmasukinfo->USER_ATASAN_JABATAN)+strlen($suratmasukinfo->USER_ATASAN)) >= 3200) 
    {
      // $break3=1;
    }
    elseif (($totMenimbang+$totMengingat+$totMenetapkan+$totPertama+$totKedua+strlen($suratmasukinfo->LOKASI_UNIT)+strlen($suratmasukinfo->TANGGAL)+strlen($suratmasukinfo->USER_ATASAN_JABATAN)+strlen($suratmasukinfo->USER_ATASAN)) >= 3200) 
    {
       // $break4=1;
    }
    elseif (($totMenimbang+$totMengingat+$totMenetapkan+$totPertama+$totKedua+$totKetiga+strlen($suratmasukinfo->LOKASI_UNIT)+strlen($suratmasukinfo->TANGGAL)+strlen($suratmasukinfo->USER_ATASAN_JABATAN)+strlen($suratmasukinfo->USER_ATASAN)) >= 3200) 
    {
       // $break5=1;
    }
    elseif (($totMenimbang+$totMengingat+$totMenetapkan+$totPertama+$totKedua+$totKetiga+$totKeempat+$totKelima+strlen($suratmasukinfo->LOKASI_UNIT)+strlen($suratmasukinfo->TANGGAL)+strlen($suratmasukinfo->USER_ATASAN_JABATAN)+strlen($suratmasukinfo->USER_ATASAN)) >= 3200)
    {
       // $break6=1;
    }

	?>

<br>
<br>
<br>
<br>
<table id="menimbang" style="page-break-inside: avoid;font-size: 12pt;">
  <tr style="page-break-inside: avoid;">
    <td style="padding: 4px 8px; width: 150px;">Menimbang</td>
    <td style="padding: 4px 8px; width: 20px;">:</td>
    <td style="padding: 4px 8px; text-align: justify;"><?= $reqMenimbang?></td>
  </tr>
</table>

  <?php if ($break1==1): ?>
    <pagebreak />
  <?php endif ?>
  
<table id="mengingat" style="page-break-inside: avoid;font-size: 12pt;">
  <tr>
    <td style="padding: 4px 8px; width: 150px;">Mengingat</td>
    <td style="padding: 4px 8px; width: 20px;">:</td>
    <td style="padding: 4px 8px; text-align: justify;"><?= $reqMengingat?></td>
  </tr>
</table>

  <?php if ($break2==1): ?>
    <pagebreak />
  <?php endif ?>

  <br>
  <div class="nama-jenis-naskah" style="letter-spacing: 2px;font-size: 12pt;">
    <b id="memutuskan">MEMUTUSKAN :</b><br>
  </div>
  <br>
  <br>

<table id="menetapkan" style="page-break-inside: avoid;font-size: 12pt;">
   <tr >
    <td style="padding: 4px 8px; width: 150px;">Menetapkan</td>
    <td style="padding: 4px 8px; width: 20px;">:</td>
    <td style="padding: 4px 8px; text-align: justify; text-transform: uppercase;"><b><?= $reqMenetapkan?></b></td>
  </tr>
</table>

  <?php if ($break3==1): ?>
    <pagebreak />
  <?php endif ?>

<table id="pertama" style="page-break-inside: avoid;font-size: 12pt;">
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
 
<div class="isi-naskah">
  <?
  $visi= str_replace("uploads/froala/",base_url()."/uploads/froala/",$suratmasukinfo->ISI);
  $visi= str_replace("font", "fontxx", $visi);
  $visi= str_replace("https://eoffice.myifpro.co.id/https://eoffice.myifpro.co.id//", base_url(),$visi);
  $visi= preg_replace('/[^(\x20-\x7F)\x0A\x0D]*/','', $visi);
  echo $visi;
  ?>
</div>
 
<!-- Start Tanda Tangan -->
<!-- Start Tanda Tangan -->
<table style="width: 100%;">
<tr>
  <td style="width:75%">
    
  </td>
  <td style="text-align: right;">
    <table style="width: 100%;">
      <tr>
        <td style="text-align: center;font-size: 12pt;">
              
          <?= $suratmasukinfo->LOKASI_UNIT ?>, <?= getFormattedDate2($suratmasukinfo->TANGGAL)  ?><br>
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
          if(stripos($reqAtasanJabatan, 'plt') !== false || stripos($reqAtasanJabatan, 'plh') !== false)
          {
            ?>
            
          <?
          }
          ?>
          <?
          $ttdKode = $suratmasukinfo->TTD_KODE;
          if ($ttdKode == "" || $suratmasukinfo->JENIS_TTD == "BASAH") {
            echo "<br><br>";
          } else {
          ?>
            <img src="<?=base_url().$suratmasukinfo->FOLDER_PATH ?>/<?= $suratmasukinfo->SURAT_MASUK_ID ?>/<?= $suratmasukinfo->TTD_KODE ?>.png" height="100px">
            <br>
          <?
          }
          ?>
          <br>
          <br>
          <br>    
          <b><?= strtoupper($suratmasukinfo->USER_ATASAN)  ?></b>
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
  <div class="tembusan" id="kepada" style="font-size: 12pt;">
    <b></b> Surat Keputusan Direksi ini 
    <br>
    <u>disampaikan kepada Yth :</u>
    <?
    $arrKepada= explode("xxx", $suratmasukinfo->KEPADA_PARAM);
    ?>
    <ol type="1">
      <?
      for ($i = 0; $i < count($arrKepada); $i++) {
      ?>
        <li><?= $arrKepada[$i] ?></li>
      <?
      }
      ?>
    </ol>
  </div>
<?
}
?>
<!-- End Kepada -->

<!-- Start Tembusan -->
<?
if ($suratmasukinfo->TEMBUSAN == "") {
} else {
?>
  <div class="tembusan" id="tembusan" style="font-size: 12pt;">
    <b>SALINAN</b> Surat Keputusan Direksi ini 
    <br>
    <u>disampaikan kepada Yth :</u>
    <?
    $arrTembusan= explode("xxx", $suratmasukinfo->TEMBUSAN_PARAM);
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
?>

</body>