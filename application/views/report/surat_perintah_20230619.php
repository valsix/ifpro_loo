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
$suratmasukinfo->getInfoAsc($reqId, $reqJenisSurat);
$telp=$suratmasukinfo->TELEPON_UNIT;
$fax=$suratmasukinfo->FAX_UNIT;
$alamat=$suratmasukinfo->ALAMAT_UNIT;
$an_status = $suratmasukinfo->AN_STATUS;
$an_nama = $suratmasukinfo->AN_NAMA;
$kepada = $suratmasukinfo->KEPADA;
$kepadainfonew = $suratmasukinfo->KEPADA_INFO_NEW;

$infokepada= [];
$arrkepadainfonew= explode(",", $kepadainfonew);
$jumlahkepada= count($arrkepadainfonew);
// echo $jumlahkepada;
// print_r($arrkepadaid);exit;
foreach ($arrkepadainfonew as $vkepada) 
{
  array_push($infokepada, $vkepada);
}
// print_r($infokepada);
// exit;

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
        Jl. Jenderal Ahamad Yani Kav 52A<br>
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
            $arrayKepada =  explode(',', $suratmasukinfo->KEPADA);
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
<div class="isi-naskah">

  <table width="100%">
    <tr>
      <td style="width: 150px;"><b>Dasar</b></td>
      <td width="5%">:</td>
      <td width="65%" align="justify">
        <?= $suratmasukinfo->DASAR ?>
      </td>
    </tr>
     <tr>
      <td>
      </td>
    </tr>
    <tr>
      <td><b>DIPERINTAHKAN KEPADA</b></td>
      <td>:</td>
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
      <td><b>ISI PERINTAH</b></td>
      <td >:</td>
      <td align="justify">
        <?= $suratmasukinfo->ISI_PERINTAH ?>
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
    <tr>
      <td><b>LAIN-LAIN</b></td>
      <td>:</td>
      <td align="justify">
        <?= $suratmasukinfo->LAIN_LAIN ?>
      </td>
    </tr>

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