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

$suratmasukinfo->getInfoAsc($reqId, $reqJenisSurat);
$telp=$suratmasukinfo->TELEPON_UNIT;
$fax=$suratmasukinfo->FAX_UNIT;
$alamat=$suratmasukinfo->ALAMAT_UNIT;
$lokasi=$suratmasukinfo->LOKASI_UNIT;
$kota=$suratmasukinfo->KOTA_TUJUAN;

$eksternalid=$suratmasukinfo->EKSTERNAL_KEPADA_ID;
$eksternal_kepada=$suratmasukinfo->EKSTERNAL_KEPADA;
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

// $infourldata= base_url();
$infourldata= "/var/www/html";
?>
 <!-- <htmlpagefooter name="firstpage" style="display:none">
      <div style="text-align: center;">
      <img src="images/img-bangga.jpg" style="height: 35px;width: 40%";>
      </div>
  </htmlpagefooter> -->

  <!-- <sethtmlpagefooter name="firstpage"  value="on" show-this-page="1" /> -->
<!-- Start Kop Surat -->
<div  class="kop-surat"  style="width: 80%;">
  <img src="/var/www/html/images/logo-surat.jpg" width="100px" height="60px" style="font-size: 8px; width: 30%; text-align: right;margin-left: 65px;margin-bottom: -15px;"  >
    <div class="logo-kop" style="font-size: 8px; width:35%; text-align: right; margin-right: 70px;  ">
   <br>
    <br>
    <b>PT.ASDP Indonesia Ferry (Persero)</b><br>
    <i><?= $suratmasukinfo->NAMA_UNIT  ?></i><br>
    <?=$alamatunit?><br>
    <?
    if (!empty($telp))
    {
    ?>
      tel : <?=$suratmasukinfo->TELEPON_UNIT  ?><br>
    <?
    }
    ?>
    <?
    if (!empty($fax))
    {
    ?>
      fax : <?=$suratmasukinfo->FAX_UNIT  ?><br>
    <?
    }
    ?>
    <!-- <span><b >PT.ASDP Indonesia Ferry (Persero)</b></span><br>
    <i>Jl. Jend Ahmad Yani Kav. 52 A, Jakarta 10510,</i><br>
    <i> Indonesia</i><br>
    tel : +6221 4208911-13-15 <br>
    fax : +6221 4210544 <br>
    web : www.indonesiaferry.co.id -->

  </div>
</div>
<div  class="kop-surat"  style="width: 100%;">
  <div class="jenis-naskah"  >
    <table width="100%">
      <tr>
        <td width="20%">Nomor</td>
        <td width="1%">:</td>
        <td ><?= $suratmasukinfo->NOMOR ?></td>
        <td>&nbsp;</td>
        <td width="40%"> <?= $suratmasukinfo->LOKASI_UNIT ?>, <?= getFormattedDate2($suratmasukinfo->TANGGAL)  ?><br>
        </td>
      </tr>
      <!-- <tr>
        <td>Lampiran</td>
        <td>:</td>
        <td><?php /*?><?= $suratmasukinfo->JUMLAH_LAMPIRAN ?><?php */?></td>
      </tr> -->
      <tr>
        <td>Perihal</td>
        <td>:</td>
        <td style="padding-right: 20px;"><?= $suratmasukinfo->PERIHAL ?></td>
        <td align="right" valign="top"><br>Yth. </td>
        <td width="40%"> Kepada<br><?
        $arrayKepada = array();
        if ($suratmasukinfo->KEPADA)
        {
          $arrayKepada =  explode(',', $suratmasukinfo->KEPADA);
        }
        $arrayEkternal = array();
        // if ($eksternal_kepada)
        // {
          if(!empty($eksternalid))
          {
            $daftaralamat= new DaftarAlamat();
            $daftaralamat->selectByParams(array(), -1,-1, " AND DAFTAR_ALAMAT_ID IN (".$eksternalid.") 
              ORDER BY ARRAY_POSITION(array[".$eksternalid."], DAFTAR_ALAMAT_ID ::INTEGER);");
            while($daftaralamat->nextRow())
            {
              array_push($arrayEkternal, $daftaralamat->getField("INSTANSI"));
            }
            // $arrayEkternal =  explode(',', $suratmasukinfo->EKSTERNAL_KEPADA);
          }
        // }
        $arrayAllKepada = array_merge($arrayKepada, $arrayEkternal);
            // print_r($arrayAllKepada);
        ?>
        <ol>
          <?
          foreach ($arrayAllKepada as $itemKepada) {
            if (count($arrayAllKepada)=='1') {
            ?>
              <?= $itemKepada ?>
            <?  
            } else {
            ?>
              <li><?= $itemKepada ?></li>
            <?  
            }
          }
          ?>
        </ol>
        <?

        ?>
        </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>Di--</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><?=$kota?></td>
      </tr>
    </table>
    <!--<table width="95%" style="text-align: right;  ">
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td></td>
        </tr>
      </table>-->
    </div>
<!-- End Kop Surat -->

<!-- Start Jenis Naskah -->
<!-- <div  class="jenis-naskah">
  <div class="nama-jenis-naskah"></div>
 
  <div class="nomor-naskah">Nomor: 0211/ND-PPU/VIII/ASDP-2020</div>
</div> -->
<!-- End Jenis Naskah -->

<!-- Start Tujuan Naskah -->
<!-- <div class="tujuan-naskah" >
 
</div> -->
<!-- End Tujuan Naskah -->

<!-- Start Pembatas -->
<!-- End Pembatas -->

<!-- Start Isi Naskah -->
<div class="isi-naskah">
  <?= $suratmasukinfo->ISI; ?>
</div>
<!-- End Isi Naskah -->

<!-- Start Tanda Tangan -->
<div class="tanda-tangan-kanan">
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
    <img src="/var/www/html/<?= $suratmasukinfo->FOLDER_PATH ?>/<?= $suratmasukinfo->SURAT_MASUK_ID ?>/<?= $suratmasukinfo->TTD_KODE ?>.png" height="100px">
    <br>
    <span style="font-size:10px;" align="justify"><i>&nbsp;</i></span>
  <?
  }
  ?>
  <br>
  <b><?= strtoupper($suratmasukinfo->USER_ATASAN)  ?></b>
  <!-- <b align="justify">ARIEF EKO K</b> -->
</div>
<!-- End Isi Naskah -->

<!-- Start Tembusan -->
<?

if (!empty($suratmasukinfo->TEMBUSAN_PARAM) || !empty($tembusan_kepada)) {
// } 
// else {
?>
  <div class="tembusan">
    <b><u>Tembusan Yth. :</u></b>
    <br>
    <?
    $arrTembusan = array();
    $arrTembusanKepada = array();
    if ($suratmasukinfo->TEMBUSAN_PARAM)
    {
      $arrTembusan = explode("xxxx", $suratmasukinfo->TEMBUSAN_PARAM);
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
  <i><?= $suratmasukinfo->KODE_UNIT ?>/<?= $suratmasukinfo->KD_SURAT ?>/<?= strtoupper($alias) */ ?></i>
</div> -->

<!-- End Maker Surat -->