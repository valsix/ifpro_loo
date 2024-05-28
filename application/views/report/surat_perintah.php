<?php
/* INCLUDE FILE */
include_once("functions/date.func.php");
include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("libraries/vendor/autoload.php");

$this->load->model("SatuanKerja");
$this->load->library('suratmasukinfo');
$suratmasukinfo = new suratmasukinfo();


$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$xx=explode("/",$actual_link);
$link="http://192.168.88.100/".$xx[3];

$reqId = httpFilterGet("reqId");
$reqJenisSurat = httpFilterGet("reqJenisSurat");
$cekquery = httpFilterGet("c");

$suratmasukinfo->getInfoAsc($reqId, $reqJenisSurat);
$telp=$suratmasukinfo->TELEPON_UNIT;
$fax=$suratmasukinfo->FAX_UNIT;
$alamat=$suratmasukinfo->ALAMAT_UNIT;
$an_status = $suratmasukinfo->AN_STATUS;
$an_nama = $suratmasukinfo->AN_NAMA;
$kepada = $suratmasukinfo->KEPADA;
$kepadainfonew = $suratmasukinfo->KEPADA_INFO_NEW;
// $kepadainfonew = $suratmasukinfo->KEPADA_PARAM;

$infokepada= [];
$arrkepadainfonew= explode(",", $kepadainfonew);
$jumlahkepada= count($arrkepadainfonew);
// echo $jumlahkepada;
// print_r($arrkepadaid);exit;
foreach ($arrkepadainfonew as $vkepada) 
{
  // ambil nama saja
  $vnew= explode(" - ", $vkepada);
  $vkepada= $vnew[1];
  array_push($infokepada, $vkepada);
}

if($cekquery == "1")
{
  print_r($infokepada);
  exit;
}

// $statement="";
// $kepada = new SuratMasuk();
// $kepada->selectByParamsKepada(array("A.SURAT_MASUK_ID" => $reqId), -1, -1, $statement);


$alamatunit=str_replace(array('<p>', '</p>'), array('<i>', '</i>'), $alamat);
$this->load->model("SuratMasuk");
$suratmasuk = new SuratMasuk();
$suratmasuk->selectByParamsPltJabatan(array("A.SURAT_MASUK_ID" => $reqId));
$suratmasuk->firstRow();
$reqJabatan                    = $suratmasuk->getField("JABATAN");
$reqAtasanJabatan                    = $suratmasuk->getField("USER_ATASAN_JABATAN");
$reqAnTambahan                    = $suratmasuk->getField("AN_TAMBAHAN");
?>
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
  <table width="100%" style="text-align:center;">
    <tr>
      <td style="font-size:20pt"><u><b>SURAT PERINTAH TUGAS</u></b></td>
    </tr>
    <tr>
      <td><?= $suratmasukinfo->NOMOR ?></td>
    </tr>
  </table>
  <br>
  <br>
<div class="isi-naskah">

  <table width="100%">
    <tr>
      <td style="width: 15%;"><b>Dasar</b></td>
      <td width="5%">:</td>
      <td align="justify">
        <?= $suratmasukinfo->DASAR ?>
      </td>
    </tr>
    <tr>
      <td><b>Pertimbangan</b></td>
      <td>:</td>
      <td align="justify">
        <?= $suratmasukinfo->LAIN_LAIN ?>
      </td>
    </tr>
     <tr>
      <td>
      </td>
    </tr>
  </table>
  <br>

  <table>
    <tr>
      <td colspan="3" style="text-align:center;"> <b>MENUGASKAN</b></td>
    </tr>
  </table>
  <br>

  <table style="width:100%">
    <tr>
      <td style="width: 15%;"><b>Kepada Yth</b></td>
      <td style="width: 5%;">:</td>
      <td align="justify">
        <?
        if($jumlahkepada == 1) 
        {
          echo strtoupper($kepadainfonew);
        }
        elseif($jumlahkepada <= 4)
        {
        ?>
        <ol>
          <?
          foreach ($infokepada as $itemKepada) 
          {
          ?>
          <li><?= $itemKepada ?></li>
          <?
          }
          ?>
        </ol>
        <?
        }
        else
        {
        ?>
          <label><em><?="Nama - nama yang tersebut dalam surat lampiran ini"?></em></label>
        <?
        }
        ?>
      </td>
    </tr>
    <tr>
      <td>
      </td>
    </tr>
    <tr>
      <td><b>Untuk</b></td>
      <td >:</td>
      <td align="justify">
        <?
        $visi= str_replace("uploads/froala/",base_url()."/uploads/froala/",$suratmasukinfo->ISI_PERINTAH);
        $visi= str_replace("font", "fontxx", $visi);
        $visi= str_replace("https://eoffice.myifpro.co.id/https://eoffice.myifpro.co.id//", base_url(),$visi);
        $visi= preg_replace('/[^(\x20-\x7F)\x0A\x0D]*/','', $visi);
        echo $visi;
        ?>
      </td>
    </tr>
     <tr>
      <td>
      </td>
    </tr>
     <tr>
      <td>
      </td>
    </tr>
    <!-- <tr>
      <td><b>LAIN-LAIN</b></td>
      <td>:</td>
      <td align="justify">
        <?= $suratmasukinfo->LAIN_LAIN ?>
      </td>
    </tr> -->

  </table>
</div> 
<!-- End Isi Naskah -->

<!-- Start Tanda Tangan -->
<div class="tanda-tangan-kanan">
  <table width="100%">
    <tr>
      <td width="40%">Dikeluarkan di</td>
      <td width="1%">:</td>
      <td width="59%"><?= strtoupper($suratmasukinfo->LOKASI_UNIT) ?></td>
    </tr>
    <tr class="border-bottom">
      <td>Pada tanggal</td>
      <td>:</td>
      <td>
        <!-- <?=getFormattedDate2($suratmasukinfo->TANGGAL)?> -->
        <?=getFormattedDateTime($suratmasukinfo->APPROVAL_QR_DATE, false)?>
      </td>
    </tr>
  </table>
  <br>&nbsp;
   <?
  if ($an_status == 1)
  {
  ?>
    <br><strong style="font-size: 12pt;">A.n <?=$an_nama?></strong>
  <?
  }
  ?>
  <br><strong style="font-size: 12pt;"><?= strtoupper($suratmasukinfo->USER_ATASAN_JABATAN) ?></strong>
  
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
    <img src="/var/www/html/eoffice/<?= $suratmasukinfo->FOLDER_PATH ?>/<?= $suratmasukinfo->SURAT_MASUK_ID ?>/<?= $suratmasukinfo->TTD_KODE ?>.png" height="100px">
    <br>
     <!-- <img src="<?=$infourldata?>/<?= $suratmasukinfo->FOLDER_PATH ?>/<?= $suratmasukinfo->SURAT_MASUK_ID ?>/<?= $suratmasukinfo->TTD_KODE ?>.png" height="100px">
    <br> -->
  <?
  }
  ?>
  <br><u style="font-size: 12pt;"><b><?= strtoupper($suratmasukinfo->USER_ATASAN) ?></b></u>
  <br>



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

<?
if($jumlahkepada > 4)
{
?>
<pagebreak />
<div class="isi-naskah">
  <table width="100%">
    <tr>
      <td style="width: 150px;"><b>Lampiran No</b></td>
      <td width="5%">:</td>
      <td width="65%" align="justify"><?=$suratmasukinfo->NOMOR?></td>
    </tr>
    <tr>
      <td><b>Tanggal</b></td>
      <td>:</td>
       <!--  <td align="justify"><?=$suratmasukinfo->TANGGAL?></td> -->
      <td align="justify"><?=getFormattedDateTime($suratmasukinfo->APPROVAL_QR_DATE, false)?></td>
    </tr>
    <tr>
      <td><b>Tentang</b></td>
      <td>:</td>
      <td align="justify"><?=$suratmasukinfo->PERIHAL?></td>
    </tr>
    <tr>
      <td style="padding-top: 50px"><b>Kepada Yth. </b></td>
      <td style="padding-top: 50px">:</td>
      <td style="padding-top: 50px" align="justify">
        <ol>
          <?
          foreach ($infokepada as $itemKepada) 
          {
          ?>
          <li><?= $itemKepada ?></li>
          <?
          }
          ?>
        </ol>
      </td>
    </tr>
  </table>
</div>
<?
}
?>

</body>