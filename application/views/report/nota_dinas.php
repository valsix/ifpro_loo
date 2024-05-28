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
// $reqJabatan                    = $suratmasuk->getField("USER_ATASAN_JABATAN");
// var_dump ($reqJabatan);exit;


$this->load->library('suratkeluarinfo');

$reqId = httpFilterGet("reqId");
$reqJenisSurat = httpFilterGet("reqJenisSurat");

$this->load->model("SuratMasuk");
$suratmasuk = new SuratMasuk();
$suratmasuk->selectByParamsPltJabatan(array("A.SURAT_MASUK_ID" => $reqId));
$suratmasuk->firstRow();
$reqJabatan                    = $suratmasuk->getField("JABATAN");
$reqAtasanJabatan                    = $suratmasuk->getField("USER_ATASAN_JABATAN");
$reqAnTambahan                    = $suratmasuk->getField("AN_TAMBAHAN");

// var_dump ($reqAnTambahan);exit;


$suratmasukinfo->getInfoAsc($reqId, $reqJenisSurat);
$telp=$suratmasukinfo->TELEPON_UNIT;
$fax=$suratmasukinfo->FAX_UNIT;
$alamat=$suratmasukinfo->ALAMAT_UNIT;
$an_status = $suratmasukinfo->AN_STATUS;
$an_nama = $suratmasukinfo->AN_NAMA;
$perihal = $suratmasukinfo->PERIHAL;
$alamatunit=str_replace(array('<p>', '</p>'), array('<i>', '</i>'), $alamat);
?>
<link href="<?= base_url() ?>css/gaya-surat.css" rel="stylesheet" type="text/css">
<link href="<?= base_url() ?>lib/froala_editor_2.9.8/css/froala_style.css" rel="stylesheet" type="text/css">
<style>
  body{
/*      background-image:url('<?= base_url() ?>images/bg_cetak.jpg')  ;
      background-image-resize:6;
      background-size: cover;*/
  }
</style>
<body>
  <table>
    <tr>
      <td>
        <img src="<?=base_url().'images/logo.png'?>" height="100px">
      </td>
    </tr>
    <tr>
      <td style="text-align:right;font-size: 9pt;width: 25%;">
        <b><u>PT. INDONESIA FERRY PROPERTI</u></b><br>
        Gedung PT. ASDP Indonesia Ferry (Persero) <br>
        Jl. Jenderal Ahmad Yani Kav 52A<br>
        Jakarta Pusat 10510<br>
        contact@ifpro.co.id | <u>www.ifpro.co.id</u>
      </td>
      <td style="text-align:center;font-size: 15pt;">
        <span><b><u>NOTA DINAS</u></b><br><?= $suratmasukinfo->NOMOR ?></span>
      </td>
      <td style="text-align:right;font-size: 9pt;width: 25%;"></td>
    </tr>
  </table>
  <br>
  <br>
  <br>
  <table style="width:100%;font-size: 12pt;">
    <tr>
      <td style="width:15%">
        Kepada Yth 
      </td>
      <td style="width:1%">:</td>
      <td >
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
    <tr>
      <td style="width:15%">
        Dari 
      </td>
      <td style="width:1%">:</td>
      <td >
        <?= $suratmasukinfo->USER_ATASAN_JABATAN?>
      </td>
    </tr>
    <tr>
      <td style="width:10%">
        Perihal 
      </td>
      <td style="width:1%">:</td>
      <td >
        <?= $suratmasukinfo->PERIHAL?>
      </td>
    </tr>
    <tr>
      <td style="width:10%">
        Tembusan 
      </td>
      <td style="width:1%">:</td>
      <td >
        <?
          if ($suratmasukinfo->TEMBUSAN == "") {
          } else {
              $arrTembusan= explode("xxx", $suratmasukinfo->TEMBUSAN_PARAM);
              ?>
              <?
              $number = 1;
              for ($i = 0; $i < count($arrTembusan); $i++) {
              ?>
                <?= $number ?>. <?= $arrTembusan[$i] ?><br>
              <?
                $number++;
              }
          }
          ?>
      </td>
    </tr>
  </table>
<!-- End Kop Surat -->
  <hr style="border: 1px solid black;">
<!-- Start Isi Naskah -->
<div class="isi-naskah" style="padding-right: 40px;">
  <table style="text-align:justify;margin-right: 10px;width: 100%;">
    <tr>
      <td style="width: 100%;">
        <?
        $visi= str_replace("uploads/froala/",base_url()."/uploads/froala/",$suratmasukinfo->ISI);
        $visi= str_replace("font", "fontxx", $visi);
        $visi= str_replace("https://eoffice.myifpro.co.id/https://eoffice.myifpro.co.id//", base_url(),$visi);
        $visi= preg_replace('/[^(\x20-\x7F)\x0A\x0D]*/','', $visi);
        echo $visi;
        ?>  
      </td>
    </tr>
  </table>
</div>
<!-- End Isi Naskah -->

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
          <?=$suratmasukinfo->USER_ATASAN_JABATAN?><br> 

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
          <b><u><?= strtoupper($suratmasukinfo->USER_ATASAN)  ?></u></b>
        </td>
      </tr>
    </table>
  </td>
</tr>
</table>

<!-- End Isi Naskah -->


</body>
<!-- End Maker Surat -->