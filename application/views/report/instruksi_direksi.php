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
$kepada = $suratmasukinfo->KEPADA;
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
  ?>
  <br>
  <br>
<table>
  <tr style="page-break-inside: avoid;">
    <td style="padding: 4px 8px; width: 150px;">Menimbang</td>
    <td style="padding: 4px 8px; width: 20px;">:</td>
    <td style="padding: 4px 8px; text-align: justify;"><?= $reqMenimbang?></td>
  </tr>
   <tr style="page-break-inside: avoid;">
    <td style="padding: 4px 8px;">Mengingat</td>
    <td style="padding: 4px 8px;">:</td>
    <td style="padding: 4px 8px; text-align: justify;"><?= $reqMengingat?></td>
  </tr>
</table>
  <!-- <pagebreak /> -->
  <br>
  <div class="nama-jenis-naskah" style="letter-spacing: 2px;">
    <b>MENGINSTRUKSIKAN :</b><br>
  </div>
  <br>
<table>
   <tr style="page-break-inside: avoid;">
    <td style="padding: 4px 8px; width: 150px; text-transform: uppercase;">Kepada</td>
    <td style="padding: 4px 8px; width: 20px;">:</td>
    <td style="padding: 4px 8px;">
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
    </td>
  </tr>
   <tr style="page-break-inside: avoid;">
    <td style="padding: 4px 8px; text-transform: uppercase;">Pertama</td>
    <td style="padding: 4px 8px;">:</td>
    <td style="padding: 4px 8px; text-align: justify;"><?= $reqPertama?></td>
  </tr>
  <?
  if ($reqKedua!="") {
    ?>
      <tr style="page-break-inside: avoid;">
        <td style="padding: 4px 8px; text-transform: uppercase;">Kedua</td>
        <td style="padding: 4px 8px;">:</td>
        <td style="padding: 4px 8px; text-align: justify;"><?= $reqKedua?></td>
      </tr>
    <?
  }
  ?>  
  <?
  if ($reqKetiga!="") {
    ?>
      <tr style="page-break-inside: avoid;">
        <td style="padding: 4px 8px; text-transform: uppercase;">Ketiga</td>
        <td style="padding: 4px 8px;">:</td>
        <td style="padding: 4px 8px; text-align: justify;"><?= $reqKetiga?></td>
      </tr>
    <?
  }
  ?>  
  <?
  if ($reqKeempat!="") {
    ?>
      <tr style="page-break-inside: avoid;">
        <td style="padding: 4px 8px; text-transform: uppercase;">Keempat</td>
        <td style="padding: 4px 8px;">:</td>
        <td style="padding: 4px 8px; text-align: justify;"><?= $reqKeempat?></td>
      </tr>
    <?
  }
  ?>  
  <?
  if ($reqKelima!="") {
    ?>
      <tr style="page-break-inside: avoid;">
        <td style="padding: 4px 8px; text-transform: uppercase;">Kelima</td>
        <td style="padding: 4px 8px;">:</td>
        <td style="padding: 4px 8px; text-align: justify;"><?= $reqKelima?></td>
      </tr>
    <?
  }
  ?>  
  <?
  if ($reqKeenam!="") {
    ?>
      <tr style="page-break-inside: avoid;">
        <td style="padding: 4px 8px; text-transform: uppercase;">Keenam</td>
        <td style="padding: 4px 8px;">:</td>
        <td style="padding: 4px 8px; text-align: justify;"><?= $reqKeenam?></td>
      </tr>
    <?
  }
  ?>  
  <?
  if ($reqKetujuh!="") {
    ?>
      <tr style="page-break-inside: avoid;">
        <td style="padding: 4px 8px; text-transform: uppercase;">Ketujuh</td>
        <td style="padding: 4px 8px;">:</td>
        <td style="padding: 4px 8px; text-align: justify;"><?= $reqKetujuh?></td>
      </tr>
    <?
  }
  ?>  
  <?
  if ($reqKedelapan!="") {
    ?>
      <tr style="page-break-inside: avoid;">
        <td style="padding: 4px 8px; text-transform: uppercase;">Kedelapan</td>
        <td style="padding: 4px 8px;">:</td>
        <td style="padding: 4px 8px; text-align: justify;"><?= $reqKedelapan?></td>
      </tr>
    <?
  }
  ?>  
  <?
  if ($reqKesembilan!="") {
    ?>
      <tr style="page-break-inside: avoid;">
        <td style="padding: 4px 8px; text-transform: uppercase;">Kesembilan</td>
        <td style="padding: 4px 8px;">:</td>
        <td style="padding: 4px 8px; text-align: justify;"><?= $reqKesembilan?></td>
      </tr>
    <?
  }
  ?>  
  <?
  if ($reqKesepuluh!="") {
    ?>
      <tr style="page-break-inside: avoid;">
        <td style="padding: 4px 8px; text-transform: uppercase;">Kesepuluh</td>
        <td style="padding: 4px 8px;">:</td>
        <td style="padding: 4px 8px; text-align: justify;"><?= $reqKesepuluh?></td>
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
          <td><b>Memperhatikan</b></td>
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
          <td colspan="3" align="center"><b>MENGINSTRUKSIKAN :</b></td>
        </tr>
        <tr>
          <td colspan="3" align="center">&nbsp;</td>
        </tr>
        <tr>
          <td><b>Kepada</b></td>
          <td>:</td>
          <td>
            <?/*
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
            */?>
          </td>
        </tr>
      </table>
    </div> -->
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
    <br style="font-size: 12pt;">A.n <?=$an_nama?>
  <?
  }
  ?>
  <br style="font-size: 12pt;"><?= strtoupper($suratmasukinfo->USER_ATASAN_JABATAN) ?>
 
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
  <br><u><b><?= strtoupper($suratmasukinfo->USER_ATASAN) ?></b></u>
</div>
<!-- End Isi Naskah -->

<!-- Start Tembusan -->
<?
if ($suratmasukinfo->TEMBUSAN == "") {
} else {
?>
  <div class="tembusan">
    <b><u>Tembusan Yth :</u></b>
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
  <i style="font-size:9px;"><?= $suratmasukinfo->KODE_UNIT ?>/<?= $suratmasukinfo->KD_SURAT ?>/<?= strtoupper($alias)*/ ?></i>
</div> -->
<!-- End Maker Surat -->