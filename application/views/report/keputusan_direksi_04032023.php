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
        Jl. Jendaral Ahamad Yani Kav 52A<br>
        Jakarta Pusat 10510<br>
        contact@ifpro.co.id | <u>www.ifpro.co.id</u>
      </td>
    </tr>
  </table>
  <br>
  <br>
  <br>
  <table style="width:100%;font-size: 14pt;">
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
        PT. ASDP Indonesia Ferry<br>
        (Persero)<br>
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
    $reqMenimbang=  $surat_masuk->getField("MENIMBANG");
    $reqMengingat= $surat_masuk->getField("MENGINGAT");
    $reqMenetapkan=  $surat_masuk->getField("MENETAPKAN");
    $reqPertama= $surat_masuk->getField("PERTAMA");
    $reqKedua= $surat_masuk->getField("KEDUA");
    $reqKetiga= $surat_masuk->getField("KETIGA");
    $reqKeempat=$surat_masuk->getField("KEEMPAT");
    $reqKelima= $surat_masuk->getField("KELIMA");
    $reqKeenam= $surat_masuk->getField("KEENAM");
    $reqKetujuh= $surat_masuk->getField("KETUJUH");
    $reqKedelapan= $surat_masuk->getField("KEDELAPAN");
    $reqKesembilan= $surat_masuk->getField("KESEMBILAN");
    $reqKesepuluh= $surat_masuk->getField("KESEPULUH");
    $reqNamaPasal= $surat_masuk->getField("NAMA_PASAL");
    if ($reqBagianNama ==''){
        $reqBagianNama=='PASAL';
     }

  ?>
<div>
  <br>
  <br>
<table style="width: 100%;">
  <tr style="page-break-inside: avoid;">
    <td style="padding: 4px 8px; width: 150px;font-size: 14pt;">Menimbang</td>
    <td style="padding: 4px 8px; width: 20px;">:</td>
    <td style="padding: 4px 8px; text-align: justify;font-size: 14pt;"><?= $reqMenimbang?></td>
  </tr>
   <tr style="page-break-inside: avoid;">
    <td style="padding: 4px 8px;font-size: 14pt;">Mengingat</td>
    <td style="padding: 4px 8px;">:</td>
    <td style="padding: 4px 8px; text-align: justify;font-size: 14pt;"><?= $reqMengingat?></td>
  </tr>
</table>
  <!-- <pagebreak /> -->
  <br>
  <div class="nama-jenis-naskah" style="letter-spacing: 2px;font-size: 14pt;">
    <b>MEMUTUSKAN :</b><br>
  </div>
  <br>
  <br>
  <br>
<table style="width: 100%;font-size: 11px;font-family: Arial">
   <tr style="page-break-inside: avoid;">
    <td style="padding: 4px 8px; width: 150px;font-size: 14pt;font-size: 14pt;"><b>Menetapkan</b></td>
    <td style="padding: 4px 8px; width: 20px;">:</td>
    <td style="padding: 4px 8px; text-align: justify; text-transform: uppercase;font-size: 14pt;"><b><?= $reqMenetapkan?></b></td>
  </tr>
  <tr style="page-break-inside: avoid;">
     <td colspan="3" style="padding: 4px 8px; text-align: center;">&nbsp;</td>
  </tr>
  <tr style="page-break-inside: avoid;">
    <? if($reqNamaPasal!='PASAL'){?>
    <td colspan="3" style="padding: 4px 8px; text-align: center;font-size: 14pt;"><strong><?= $reqNamaPasal?> I</strong></td>
    <?}
    else{?>
    <td colspan="3" style="padding: 4px 8px; text-align: center;font-size: 14pt;"><strong><?= $reqNamaPasal?> 1</strong></td>
    <?}?>
  </tr>
  <tr style="page-break-inside: avoid;">
    <td colspan="3" style="padding: 4px 8px;font-size: 14pt;"><?= $reqPertama?></td>
  </tr>
  <?
  if ($reqKedua!="") {
    ?>
      <tr style="page-break-inside: avoid;">
        <? if($reqNamaPasal!='PASAL'){?>
        <td colspan="3" style="padding: 4px 8px; text-align: center;font-size: 14pt;"><strong><?= $reqNamaPasal?> II</strong></td>
        <?}
        else{?>
        <td colspan="3" style="padding: 4px 8px; text-align: center;font-size: 14pt;"><strong><?= $reqNamaPasal?> 2</strong></td>
        <?}?>
      </tr>
      <tr style="page-break-inside: avoid;">
        <td colspan="3" style="padding: 4px 8px;font-size: 14pt;"><?= $reqKedua?></td>
      </tr>
    <?
  }
  ?>  
  <?
  if ($reqKetiga!="") {
    ?>
      <tr style="page-break-inside: avoid;">
        <? if($reqNamaPasal!='PASAL'){?>
          <td colspan="3" style="padding: 4px 8px; text-align: center;font-size: 14pt;"><strong><?= $reqNamaPasal?> III</strong></td>
          <?}
          else{?>
          <td colspan="3" style="padding: 4px 8px; text-align: center;font-size: 14pt;"><strong><?= $reqNamaPasal?> 3</strong></td>
        <?}?>
      </tr>
      <tr style="page-break-inside: avoid;">
        <td colspan="3" style="padding: 4px 8px;"><?= $reqKetiga?></td>
      </tr>
    <?
  }
  ?>  
  <?
  if ($reqKeempat!="") {
    ?>
      <tr style="page-break-inside: avoid;">
        <? if($reqNamaPasal!='PASAL'){?>
          <td colspan="3" style="padding: 4px 8px; text-align: center;"><strong><?= $reqNamaPasal?> IV</strong></td>
          <?}
          else{?>
          <td colspan="3" style="padding: 4px 8px; text-align: center;"><strong><?= $reqNamaPasal?> 4</strong></td>
        <?}?>
      </tr>
      <tr style="page-break-inside: avoid;">
        <td colspan="3" style="padding: 4px 8px;"><?= $reqKeempat?></td>
      </tr>
    <?
  }
  ?>  
  <?
  if ($reqKelima!="") {
    ?>
      <tr style="page-break-inside: avoid;">
        <? if($reqNamaPasal!='PASAL'){?>
          <td colspan="3" style="padding: 4px 8px; text-align: center;"><strong><?= $reqNamaPasal?> V</strong></td>
          <?}
          else{?>
          <td colspan="3" style="padding: 4px 8px; text-align: center;"><strong><?= $reqNamaPasal?> 5</strong></td>
        <?}?>
      </tr>
      <tr style="page-break-inside: avoid;">
        <td colspan="3" style="padding: 4px 8px;"><?= $reqKelima?></td>
      </tr>
    <?
  }
  ?>  
  <?
  if ($reqKeenam!="") {
    ?>
      <tr style="page-break-inside: avoid;">
        <? if($reqNamaPasal!='PASAL'){?>
          <td colspan="3" style="padding: 4px 8px; text-align: center;"><strong><?= $reqNamaPasal?> VI</strong></td>
          <?}
          else{?>
          <td colspan="3" style="padding: 4px 8px; text-align: center;"><strong><?= $reqNamaPasal?> 6</strong></td>
        <?}?>
      </tr>
      <tr style="page-break-inside: avoid;">
        <td colspan="3" style="padding: 4px 8px;"><?= $reqKeenam?></td>
      </tr>
    <?
  }
  ?>  
  <?
  if ($reqKetujuh!="") {
    ?>
      <tr style="page-break-inside: avoid;">
        <? if($reqNamaPasal!='PASAL'){?>
          <td colspan="3" style="padding: 4px 8px; text-align: center;"><strong><?= $reqNamaPasal?> VII</strong></td>
          <?}
          else{?>
          <td colspan="3" style="padding: 4px 8px; text-align: center;"><strong><?= $reqNamaPasal?> 7</strong></td>
        <?}?>
      </tr>
      <tr style="page-break-inside: avoid;">
        <td colspan="3" style="padding: 4px 8px;"><?= $reqKetujuh?></td>
      </tr>
    <?
  }
  ?>  
  <?
  if ($reqKedelapan!="") {
    ?>
      <tr style="page-break-inside: avoid;">
        <? if($reqNamaPasal!='PASAL'){?>
          <td colspan="3" style="padding: 4px 8px; text-align: center;"><strong><?= $reqNamaPasal?> VIII</strong></td>
          <?}
          else{?>
          <td colspan="3" style="padding: 4px 8px; text-align: center;"><strong><?= $reqNamaPasal?> 8</strong></td>
        <?}?>
      </tr>
      <tr style="page-break-inside: avoid;">
        <td colspan="3" style="padding: 4px 8px;"><?= $reqKedelapan?></td>
      </tr>
    <?
  }
  ?>  
  <?
  if ($reqKesembilan!="") {
    ?>
      <tr style="page-break-inside: avoid;">
        <? if($reqNamaPasal!='PASAL'){?>
          <td colspan="3" style="padding: 4px 8px; text-align: center;"><strong><?= $reqNamaPasal?> IX</strong></td>
          <?}
          else{?>
          <td colspan="3" style="padding: 4px 8px; text-align: center;"><strong><?= $reqNamaPasal?> 9</strong></td>
        <?}?>
      </tr>
      <tr style="page-break-inside: avoid;">
        <td colspan="3" style="padding: 4px 8px;"><?= $reqKesembilan?></td>
      </tr>
    <?
  }
  ?>  
  <?
  if ($reqKesepuluh!="") {
    ?>
      <tr style="page-break-inside: avoid;">
        <? if($reqNamaPasal!='PASAL'){?>
          <td colspan="3" style="padding: 4px 8px; text-align: center;"><strong><?= $reqNamaPasal?> X</strong></td>
          <?}
          else{?>
          <td colspan="3" style="padding: 4px 8px; text-align: center;"><strong><?= $reqNamaPasal?> 10</strong></td>
        <?}?>
      </tr>
      <tr style="page-break-inside: avoid;">
        <td colspan="3" style="padding: 4px 8px;"><?= $reqKesepuluh?></td>
      </tr>
    <?
  }
  ?>  
   
</table>

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
        <?= $suratmasukinfo->ISI; ?>
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
<!-- <div class="tanda-tangan-kanan" style="page-break-after: always;" > -->
  <div class="tanda-tangan-kanan" >
  <table width="100%">
    <tr>
      <td width="40%" style="font-size: 14pt;">Ditetapkan di</td>
      <td width="1%">:</td>
      <td width="59%" style="font-size: 14pt;"><?= $suratmasukinfo->LOKASI_UNIT ?></td>
    </tr>
    <tr class="border-bottom">
      <td style="font-size: 14pt;">Pada tanggal</td>
      <td>:</td>
      <td>
        <!-- <?= getFormattedDate2($suratmasukinfo->TANGGAL) ?> -->
        <?=getFormattedDateTime($suratmasukinfo->APPROVAL_QR_DATE, false)?>
      </td>
    </tr>
  </table>
  <br>&nbsp;
  <?
  if ($an_status == 1)
  {
    ?>
    <br><strong>A.n <?=$an_nama?></strong>
    <?
  }
  ?>
  <br><strong><?= strtoupper($suratmasukinfo->USER_ATASAN_JABATAN) ?></strong>
  <?
if(stripos($reqAtasanJabatan, 'plt') !== false || stripos($reqAtasanJabatan, 'plh') !== false)  {
    ?>
    <div style="line-height:150%;">
          <span style="font-size: 14pt;"><?=strtoupper($reqJabatan)?></span>
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
  <br><u><b><?= strtoupper($suratmasukinfo->USER_ATASAN) ?></b></u>
</div>
<!-- End Isi Naskah -->

<!-- Start Kepada -->
<?
if ($suratmasukinfo->KEPADA == "") {
} else {
?>
  <!-- <div class="tembusan"  style="page-break-before: always;" > -->
    <div class="tembusan"  >
    <b>SALINAN</b> Keputusan Direksi ini 
    <br>
    <u>disampaikan kepada Yth :</u>
    <?
    $arrKepada = explode(",", $suratmasukinfo->KEPADA);
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
/*if ($suratmasukinfo->TEMBUSAN == "") {
} else {
?>
  <div class="tembusan" >
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
<!-- <div class="maker-surat">
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
  <i style="font-size:9px;"><?= $suratmasukinfo->KODE_UNIT ?>/<?= $suratmasukinfo->KD_SURAT ?>/<?= strtoupper($alias) */ ?></i>
</div> -->


<!-- End Maker Surat -->
  <!-- <sethtmlpagefooter name="firstpage"  value="off" show-this-page="1" /> -->
