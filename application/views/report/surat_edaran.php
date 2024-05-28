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
// echo $an_status;exit;
$alamatunit=str_replace(array('<p>', '</p>'), array('<i>', '</i>'), $alamat);
$this->load->model("SuratMasuk");
$suratmasuk = new SuratMasuk();
$suratmasuk->selectByParamsPltJabatan(array("A.SURAT_MASUK_ID" => $reqId));
// echo $suratmasuk->query;exit;
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
  </table>
  <center>
    <b>SURAT EDARAN</b><br>
    <b>No : <?= $suratmasukinfo->NOMOR ?></b><br>
    <p style="margin: 5px;">tentang</p>
    <b><?= $suratmasukinfo->PERIHAL; ?></b>
  </center>
  <!-- Start Isi Naskah -->
  <!-- <div class="isi-naskah" style="font-size: 12pt;font-family: Arial;margin-bottom: 10px; "> -->
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
<!--<div class="tanda-tangan-kanan"  style="page-break-inside: avoid;">-->
<table style="width: 100%;">
<tr>
  <td style="width:75%">
    
  </td>
  <td style="text-align: right;">
    <table style="width: 100%;">
      <tr>
        <td style="font-size: 12pt;border-bottom: solid black 0.5px;">
          Ditetapkan di : <?= strtoupper ($suratmasukinfo->LOKASI_UNIT) ?><br>
          Pada Tanggal  : <?= getFormattedDate2($suratmasukinfo->TANGGAL)  ?>   
        </td>
      </tr>
      <tr>
        <td style="text-align: center;font-size: 12pt;">
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
            echo "<br><br><br>";
          } else {
          ?>
            <img src="<?=base_url().$suratmasukinfo->FOLDER_PATH ?>/<?= $suratmasukinfo->SURAT_MASUK_ID ?>/<?= $suratmasukinfo->TTD_KODE ?>.png" height="100px">
            <br>
          <?
          }
          ?>
          <b><?= strtoupper($suratmasukinfo->USER_ATASAN)  ?></b>
        </td>
      </tr>
    </table>
  </td>
</tr>
</table>

<!-- Start Kepada -->
<div style="page-break-inside: auto;">
	<table>
    	<tr>
        	<td>
            	<?
				if ($suratmasukinfo->KEPADA == "") {
				} else {
				?>
				  <!-- <div class="tembusan"  style="page-break-before: always;" > -->
					<div class="tembusan" style="font-size: 12pt;">
					<b><u>Tembusan :</u></b>
					<br>
					
					<?
					$arrKepada= explode("xxx", $suratmasukinfo->KEPADA_PARAM);
					if(count($arrKepada) > 1){
					?>
					
					<ol type="1" style="padding: 0px; margin-top: 0px; margin-bottom: 0px; list-style-position: inside;">
					  <?
					  for ($i = 0; $i < count($arrKepada); $i++) {
					  ?>
						<li><?= $arrKepada[$i] ?></li>
					  <?
					  }
					  ?>
					</ol>
					<?
					} else {
					echo "- ".$suratmasukinfo->KEPADA;	
					}
					?>
				  </div>
				<?
				}
				?>
            </td>
        </tr>
    	<tr>
        	<td>
            	<?
				if ($suratmasukinfo->TEMBUSAN == "") {
				} else {
				?>
				  <div class="tembusan" style="font-size: 12pt;">
					<b><u>Tembusan Yth. :</u></b>
					<br>
					<?
					$arrTembusan= explode("xxx", $suratmasukinfo->TEMBUSAN_PARAM);
					// var_dump(count($arrTembusan));
					?>
					<?
					$number = 1;
					for ($i = 0; $i < count($arrTembusan); $i++) {
					?>
					  <? 
					  if (count($arrTembusan) == 1)
					  {
					  ?>
						  <?= $arrTembusan[$i] ?><br>
					  <?
					  }
					  else
					  {
					  ?>
						  <?= $number ?>. <?= $arrTembusan[$i] ?><br>
					  <?
					  }
					  ?>
					<?
					  $number++;
					}
					?>
				  </div>
				<?
				}
				?>
            </td>
        </tr>
    </table>
	
        	
			<!-- End Kepada -->
			
			<!-- Start Tembusan -->
			
			<!-- End Tembusan -->
        
</div>
</body>
<!-- <sethtmlpagefooter name="firstpage"  value="on" show-this-page="1" /> -->

