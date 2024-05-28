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
$alamatunit=str_replace(array('<p>', '</p>'), array('<i>', '</i>'), $alamat);
?>
<!-- Start Kop Surat -->

  <!--<sethtmlpagefooter name="firstpage"  value="on" show-this-page="1" />-->

<div  class="kop-surat">
<!--   <img src="<?=$link?>/images/logo_fullcolor.png" style="font-size: 8px; width: 15%; margin-left: 65px;margin-bottom: -15px;"  >
	<div class="logo-kop" style="font-size: 8px; width: 35%; text-align: right;font-family: Calibri">
        <br>
        <br>
        <b>PT.JEMBATAN NUSANTARA</b><br>
        <i>Kantor Pusat</i><br>
        Gedung Pelni Heritage Lt.2 <br>Jl. Pahlawan No. 112 – 114 <br>Surabaya 60175, East Java Indonesia</i><br>
       
        <div>Telp : +62 31 99220000</div>

	</div> -->
  <!-- <div style="font-family: Arial;font-size: 11px;text-align: center;  "> -->
    <div style="font-family: Arial;font-size: 11px;text-align: center;  ">
      <br>
      <br>
      <br>
      <br>
      <div style="margin-left: -60px;">
       <b><u>NOTA DINAS</u></b><br>
       Nomor : <?= $suratmasukinfo->NOMOR ?>
     </div>
     <!--<div class="nomor-naskah" style=" text-align: left; border: 1px solid red; text-align: center;"></div>-->
   </div>

    <div class="tujuan-naskah" style="width: 100%;font-family: Arial;font-size: 11px;" >
      <table width="100%" style="margin-left: 140px;" >
      	<?
        $arrKepada = explode(",", $suratmasukinfo->KEPADA);
        // print_r($arrKepada);exit;
        ?>
        <?
        if ($suratmasukinfo->KEPADA == "")
        {
          $number = "";  
        }
        else
        {
          $number = 1; 
        }

        $jumlahKepada = count($arrKepada);
        // echo $jumlahKepada;exit;
        if($jumlahKepada < 4)
        {
          for ($i = 0; $i < count($arrKepada); $i++) 
          {
          ?>
          <tr>
              <?
              if ($i==0)
              {
              ?>
                <td>Kepada Yth.</td>
                <td>:</td>
              <?
              }
              else
              {
              ?>
                <td></td>
                <td></td>
              <?
              }
              ?>
        
              <?            
              if($jumlahKepada == 1)
              {
                  $number = "";
              } 
              else 
              {
                  $number = $number .".";
              }
              ?>
              <td align="justify">&nbsp;<?= $number ?> <?= $arrKepada[$i] ?></td>
          </tr>
        <?
            $number++;
          }
        }
        else
        {
        ?>
        <tr>
          <td>Kepada Yth.</td>
          <td>:</td>
          <td align="justify">&nbsp;<label><em><?="Nama - nama yang tersebut dalam surat lampiran ini"?></em></label></td>
        </tr>
        <?
        }
        ?>
        
        <?php /*?><tr>
          <td width="30%">Kepada Yth.</td>
          <td width="1%">:</td>
          <td width="86%" style="padding-left: 5px;">
            <?
            //$arrayKepada =  explode(',', strtoupper($suratmasukinfo->KEPADA));
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
          </td>
        </tr><?php */?>

        <tr>
          <td>Dari</td>
          <td>:</td>
          <td align="justify" style="padding-left: 5px;"><?= $suratmasukinfo->USER_ATASAN_JABATAN ?></td>
        </tr>

        <tr>
          <td>Perihal</td>
          <td>:</td>
          <td align="justify" style="padding-left: 5px;"><?= $suratmasukinfo->PERIHAL; ?></td>
        </tr>
        
        <?
        if ($suratmasukinfo->TEMBUSAN !== "")
        {
          $arrTembusan = explode(",", $suratmasukinfo->TEMBUSAN);
          if ($suratmasukinfo->TEMBUSAN == "")
          {
            $number = "";  
          }
          else
          {
            $number = 1; 
          }

          $jumlahTembusan = count($arrTembusan);
          //echo $jumlahTembusan;
          if($jumlahTembusan < 4)
          {
            for ($i = 0; $i < count($arrTembusan); $i++) 
            {
          ?>
              <tr>
                <?
                if ($i==0)
                {
                ?>
                  <td>Tembusan Yth.</td>
                  <td>:</td>
                <?
                }
                else
                {
                ?>
                  <td></td>
                  <td></td>
                <?
                }
                ?>

                <?            
                if($jumlahTembusan == 1)
                {
                  $number = "";
                }
                else 
                {
                  $number = $number .".";
                }
                ?>
                
                <td align="justify">&nbsp;<?= $number ?> <?= $arrTembusan[$i] ?></td>
              </tr>
          <?
              $number++;
            }
          }
          else
          {
          ?>
          <tr>
            <td>Tembusan Yth.</td>
            <td>:</td>
            <td align="justify">&nbsp;<label><em><?="Nama - nama yang tersebut dalam surat lampiran ini"?></em></label></td>
          </tr>
          <?
          }
        }
        ?>
       <!--  <tr>
          <td>Tembusan Yth.</td>
          <td>:</td>
          <td align="justify">&nbsp; <?= $suratmasukinfo->TEMBUSAN; ?></td>
        </tr> -->
      </table>
  </div>
</div>
<!-- End Kop Surat -->

<!-- Start Pembatas -->
<div class="pembatas"></div>
<!-- End Pembatas -->

<!-- Start Isi Naskah -->
<div class="isi-naskah" style="font-size: 11px;font-family: Arial;">
  <?= $suratmasukinfo->ISI; ?>
</div>
<!-- End Isi Naskah -->

<!-- Start Tanda Tangan -->
<table style="width: 100%;">
<tr>
<td style="text-align: right;">
	<table style="width: 25%;">
    <tr>
      <td style="text-align: center;">
      
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
    <!-- <div style="line-height:150%;">
          <span><?=strtoupper($reqJabatan)?></span>
    </div> -->
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
    <!-- <img src="<?= base_url() ?>/<?= $suratmasukinfo->FOLDER_PATH ?>/<?= $suratmasukinfo->SURAT_MASUK_ID ?>/<?= $suratmasukinfo->TTD_KODE ?>.png" height="100px"> -->
    <img src="/var/www/html/<?= $suratmasukinfo->FOLDER_PATH ?>/<?= $suratmasukinfo->SURAT_MASUK_ID ?>/<?= $suratmasukinfo->TTD_KODE ?>.png" height="100px">

    
    <br>
    <!--<span style="font-size:10px;" align="justify"><i>&nbsp;</i></span>-->
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
<!--     <br><?= strtoupper($reqJabatan) ?><br>
 -->
  <!-- <b align="justify">ARIEF EKO K</b> -->
</td>
</tr>
</table>

<!-- End Isi Naskah -->

<!-- Start Tembusan -->
<?php /*?><?
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
<?php */?>
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
  <i><?= $suratmasukinfo->KODE_UNIT ?>/<?= $suratmasukinfo->KD_SURAT ?>/<?= strtoupper($alias) */?></i>
</div>
 -->

<?
if($jumlahKepada >= 4 || $jumlahTembusan >= 4)
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
      <td align="justify"><?=$suratmasukinfo->TANGGAL?></td>
    </tr>
    <tr>
      <td><b>Tentang</b></td>
      <td>:</td>
      <td align="justify"><?=$suratmasukinfo->PERIHAL?></td>
    </tr>
    <?
    if($jumlahKepada >= 4)
    {
    ?>
    <tr>
      <td style="padding-top: 50px"><b>Kepada Yth. </b></td>
      <td style="padding-top: 50px">:</td>
      <td style="padding-top: 50px" align="justify">
        <ol>
          <?
          for($i = 0; $i < count($arrKepada); $i++) 
          {
          ?>
          <li><?=$arrKepada[$i]?></li>
          <?
          }
          ?>
        </ol>
      </td>
    </tr>
    <?
    }
    ?>
    <?
    if($jumlahTembusan >= 4)
    {
    ?>
    <tr>
      <td style="padding-top: 50px"><b>Tembusan Yth. </b></td>
      <td style="padding-top: 50px">:</td>
      <td style="padding-top: 50px" align="justify">
        <ol>
          <?
          for($i = 0; $i < count($arrTembusan); $i++) 
          {
          ?>
          <li><?=$arrTembusan[$i]?></li>
          <?
          }
          ?>
        </ol>
      </td>
    </tr>
    <?
    }
    ?>
  </table>
</div>
<?
}
?>
  <!--<sethtmlpagefooter name="firstpage"  value="off" show-this-page="1" />-->
 <!--    <htmlpagefooter name="firstpage">
      <div style="text-align: center;">
      <img src="/var/www/html/images/img-bangga.jpg" style="height: 35px;width: 40%";>
      </div>
  </htmlpagefooter>

  <sethtmlpagefooter name="firstpage"  value="on" show-this-page="1" /> -->

<!-- End Maker Surat -->