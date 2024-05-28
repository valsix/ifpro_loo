<?php
/* INCLUDE FILE */
include_once("functions/date.func.php");
include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("libraries/vendor/autoload.php");

$this->load->model("DaftarAlamat");
$this->load->library('suratmasukinfo');
$suratmasukinfo = new suratmasukinfo();

$this->load->library('suratkeluarinfo');

$reqId = httpFilterGet("reqId");
$reqJenisSurat = httpFilterGet("reqJenisSurat");
$cekquery= httpFilterGet("c");

$suratmasukinfo->getInfoAsc($reqId, $reqJenisSurat);
// print_r($suratmasukinfo);exit;

// $suratmasukinfo->LOKASI_UNIT
$telp=$suratmasukinfo->TELEPON_UNIT;
$fax=$suratmasukinfo->FAX_UNIT;
$alamat=$suratmasukinfo->ALAMAT_UNIT;
$lokasi=$suratmasukinfo->LOKASI_UNIT;
$kota=$suratmasukinfo->KOTA_TUJUAN;

$internal_kepada= $suratmasukinfo->KEPADA;
$internal_tembusan= $suratmasukinfo->TEMBUSAN_PARAM;

$eksternalid=$suratmasukinfo->EKSTERNAL_KEPADA_ID;
$eksternal_kepada=$suratmasukinfo->EKSTERNAL_KEPADA;

if($cekquery == "eksternalid")
{
  echo $eksternalid;exit;
}

if($cekquery == "eksternal_kepada")
{
  echo $eksternal_kepada;exit;
}

$tembusanid=$suratmasukinfo->EKSTERNAL_TEMBUSAN_ID;
$tembusan_kepada=$suratmasukinfo->EKSTERNAL_TEMBUSAN;
// var_dump ($check);exit;

// echo $suratmasukinfo->TEMBUSAN."<br/>".$tembusan_kepada;exit;

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

$infourldata= base_url();
// echo $infourldata; exit;
// $infourldata= "/var/www/html";
?>
<link href="<?= base_url() ?>css/gaya-surat.css" rel="stylesheet" type="text/css">

<div  class="kop-surat">
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
      <td style="width:20%">
      </td>
      <td rowspan="3" style="width:30%">
        <?=$lokasi?> <?=getFormattedDate2($suratmasukinfo->TANGGAL)?><br>
        Kepada:<br>
        Yth.
        <!-- Yth. Vice President Komersial<br> -->
        <?
        $arrayKepada = array();
        if (!empty($internal_kepada))
        {
          $arrayKepada= explode("xxx", $suratmasukinfo->KEPADA_PARAM);
        }
        $arrayEkternal = array();
          if(!empty($eksternalid))
          {
            $arreksternalid= explode(",", $eksternalid);
            foreach ($arreksternalid as $kext => $vext) 
            {
              $daftaralamat= new DaftarAlamat();
              $daftaralamat->selectByParams(array(), -1,-1, " AND DAFTAR_ALAMAT_ID IN (".$vext.") 
                ORDER BY DAFTAR_ALAMAT_ID desc");
              // echo $daftaralamat->query; exit;
              while($daftaralamat->nextRow())
              {
                array_push($arrayEkternal, $daftaralamat->getField("INSTANSI"));
              }
            }
            /*$daftaralamat= new DaftarAlamat();
            $daftaralamat->selectByParams(array(), -1,-1, " AND DAFTAR_ALAMAT_ID IN (".$eksternalid.") 
              ORDER BY DAFTAR_ALAMAT_ID desc");
            // echo $daftaralamat->query; exit;
            while($daftaralamat->nextRow())
            {
              array_push($arrayEkternal, $daftaralamat->getField("INSTANSI"));
            }*/
          }
          $arrayAllKepada = array_merge($arrayKepada, $arrayEkternal);
        ?>
          <?
          $i=1;
          foreach ($arrayAllKepada as $itemKepada) {
            if(count($arrayAllKepada)>1){
            ?>
              <br><?=$i?>.&ensp;<?= $itemKepada ?>
            <?  
            }
            else{?>
              <br>&emsp;<?= $itemKepada ?>
            <?}
          $i++;
          }
          ?>
        <?

        ?>
        <br>
        di<br>
        <u><b>Tempat</b></u>
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
<!-- End Isi Naskah -->

<!-- Start Tanda Tangan -->
<div class="tanda-tangan-kanan" style="font-size:12pt">
  <center>Hormat Kami,<br>
  <b>PT. INDONESIA FERRY PROPERTI</b></center>

   <?
    if ($an_status == 1)
    {
        ?>
        <br>A.n <?=$an_nama?>
        <?
    }
    ?>
 
  <?
  if
  (
    (stripos($reqAtasanJabatan, 'plt') !== false || stripos($reqAtasanJabatan, 'plh') !== false)
    &&
    strtoupper($suratmasukinfo->USER_ATASAN_JABATAN) !== strtoupper($reqJabatan)
  )  
  {
    ?>
    <div style="line-height:150%;">
          <span><?=strtoupper($reqJabatan)?></span>
    </div>
  <?
  }
  ?>
 
  <!-- Jakarta, 28 Agustus 2020<br>
  VICE PRESIDENT PROPERTI DAN UMUM<br> -->
  <?
  $ttdKode = $suratmasukinfo->TTD_KODE;
  if ($ttdKode == "" || $suratmasukinfo->JENIS_TTD == "BASAH") {
    echo "<br><br>";
  } else {
  ?>
    <img src="<?=$infourldata?>/<?= $suratmasukinfo->FOLDER_PATH ?>/<?= $suratmasukinfo->SURAT_MASUK_ID ?>/<?= $suratmasukinfo->TTD_KODE ?>.png" height="100px">
    <br>
    <span style="font-size:10px;" align="justify"><i>&nbsp;</i></span>
  <?
  }
  ?>
  <br>
  <b><u> <?= strtoupper($suratmasukinfo->USER_ATASAN)  ?> </u></b>
  <br><?= strtoupper($suratmasukinfo->USER_ATASAN_JABATAN) ?><br>

  <!-- <b align="justify">ARIEF EKO K</b> -->
</div>
<!-- End Isi Naskah -->

<!-- Start Tembusan -->
<?

if (!empty($internal_tembusan) || !empty($tembusan_kepada)) {
// } 
// else {
?>
  <div class="tembusan">
    <b><u>Tembusan Yth. :</u></b>
    <br>
    <?
    $arrTembusan = array();
    $arrTembusanKepada = array();
    if (!empty($internal_tembusan))
    {
      $arrTembusan = explode("xxx", $internal_tembusan);
    }
    if ($tembusan_kepada)
    {

      // if($reqId == 630)
      // {
        if(substr($tembusanid, 0, 1) == ",")
        {
          $tembusanid= ltrim($tembusanid, ',');
        }
      // }
      // $eksternalid;$tembusanid
        if(!empty($tembusanid))
        {
          $daftaralamat= new DaftarAlamat();
          $daftaralamat->selectByParams(array(), -1,-1, " AND DAFTAR_ALAMAT_ID IN (".$tembusanid.") 
                ORDER BY ARRAY_POSITION(array[".$tembusanid."], DAFTAR_ALAMAT_ID ::INTEGER);");
          while($daftaralamat->nextRow())
          {
            array_push($arrTembusanKepada, $daftaralamat->getField("INSTANSI"));
          }
          // $arrTembusanKepada = explode(",", $tembusan_kepada);
        }
    }



    // if(!empty($suratmasukinfo->TEMBUSAN) && empty($tembusan_kepada))
    //   $arrayAllTembusan = $arrTembusan;
    // else if(empty($suratmasukinfo->TEMBUSAN) && !empty($tembusan_kepada))
    //   $arrayAllTembusan = $arrTembusanKepada;
    // else
      $arrayAllTembusan = array_merge($arrTembusan, $arrTembusanKepada);
      if($reqId == "51153")
      {
        // echo $suratmasukinfo->TEMBUSAN;exit();
        // print_r($arrTembusanKepada);
        // print_r($arrTembusan);
        // print_r($arrayAllTembusan);
        // exit();
      }
    ?>
    <?
    $number = 1;
    for ($i = 0; $i < count($arrayAllTembusan); $i++) {
    ?>
      <?= $number ?>. <?= $arrayAllTembusan[$i] ?><br>
    <?
      $number++;
    }
    ?>
  </div>
<?
}
?>
<!-- End Tembusan -->
</body>