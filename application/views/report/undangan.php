<?php
/* INCLUDE FILE */
include_once("functions/date.func.php");
include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("libraries/vendor/autoload.php");

$this->load->library('suratmasukinfo');
$suratmasukinfo = new suratmasukinfo();

$reqId = httpFilterGet("reqId");
$suratmasukinfo->getInfo($reqId);
?>

<!-- Start Kop Surat -->
<div class="kop-surat">
  <div class="logo-kop"><img src="<?=base_url();?>/images/logo-surat.jpg" width="250px" height="*"></div>
  <div class="alamat-kop">
    <b>PT Angkasa Pura I (Persero)</b><br>
    <i><?=$suratmasukinfo->NAMA_UNIT?> :</i><br>
    <?=$suratmasukinfo->ALAMAT_UNIT?><br>
    tel : <?=$suratmasukinfo->TELEPON_UNIT?> &nbsp;&nbsp;fax : <?=$suratmasukinfo->FAX_UNIT?><br>
    web : www.ap1.co.id
  </div>
</div>
<!-- End Kop Surat -->

<!-- Start Kepada Naskah -->
<div class="kepada-naskah">
    <table width="100%">
      <tr>
        <td width="15%">Nomor</td>
        <td width="1%">:</td>
        <td width="84%"><?=$suratmasukinfo->NOMOR?></td>
      </tr>
      <tr>
        <td>Lampiran</td>
        <td>:</td>
        <td>
          <? 
          if($suratmasukinfo->JUMLAH_LAMPIRAN == "0"){ 
            echo "-";
          } 
          else{
            echo coalesce($suratmasukinfo->JUMLAH_LAMPIRAN, "-");
          } 
          ?>
        </td>
      </tr>
      <tr>
        <td>Perihal</td>
        <td>:</td>
        <td><?=$suratmasukinfo->PERIHAL?></td>
      </tr>
    </table>
    <p>&nbsp;
    <p>Kepada Yth,
    <p>
      <?
        $arrayKepada =  explode(',', strtoupper($suratmasukinfo->KEPADA));
        if(count($arrayKepada) == 1){
          echo strtoupper($suratmasukinfo->KEPADA);
        }
        else{
        ?>
          <ol>
            <?
            foreach ($arrayKepada as $itemKepada) {
            ?>
              <li><?=$itemKepada?></li>
            <?
            }
            ?>
          </ol>
        <?
        }
        ?>
    <p>di -
    <p>&nbsp;&nbsp;&nbsp;&nbsp;Tempat
</div>
<!-- End Jenis Naskah -->

<!-- Start Isi Naskah -->
<div class="isi-naskah">
  <?=$suratmasukinfo->ISI?>
</div>
<!-- End Isi Naskah -->

<!-- Start Tanda Tangan -->
<div class="tanda-tangan-kiri">
	<?=$suratmasukinfo->LOKASI_UNIT?>, <?=getFormattedDate($suratmasukinfo->TANGGAL)?><br>
    <?
    if($suratmasukinfo->KODE_UNIT == "PST"){
    ?>
    a.n. DIREKSI<br>
    <?=strtoupper($suratmasukinfo->DIREKTORAT_JABATAN)?><br>
    u.b<br>
    <?
    }
    ?>
    <?=strtoupper($suratmasukinfo->USER_ATASAN_JABATAN)?>,<br>
    <?
    $ttdKode = $suratmasukinfo->TTD_KODE;
    if($ttdKode == "" || $suratmasukinfo->JENIS_TTD == "BASAH"){
    	echo "<br><br>";
    }
    else
    {
    ?>
      <img src="<?=base_url()?>/<?=$suratmasukinfo->FOLDER_PATH?>/<?=$suratmasukinfo->SURAT_MASUK_ID?>/<?=$suratmasukinfo->TTD_KODE?>.png" height="50px" width="*">
      <br>
      <span style="font-size:10px;"><i>&nbsp;</i></span>
    <?
    }
    ?>  
  <br>
  <b><?=strtoupper($suratmasukinfo->USER_ATASAN)?></b>
</div>
<!-- End Isi Naskah -->


<!-- Start Tembusan -->
<?
if($suratmasukinfo->TEMBUSAN == "")
{}
else
{
?>
<div class="tembusan">
  <b><u>Tembusan Yth. :</u></b>
  <br>
  <?
  $arrTembusan = explode(";", $suratmasukinfo->TEMBUSAN);
  ?>
  <?
  $number = 1;
  for($i=0;$i<count($arrTembusan);$i++)
  {
  ?>
  <?=$number?>. <?=$arrTembusan[$i]?><br>
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
<div class="maker-surat">
<?
$arrNama = explode(" ", $suratmasukinfo->NAMA_USER);
$jumlahNama = count($arrNama);
if($jumlahNama > 1)
{
	$inisial = substr($arrNama[0], 0, 1);	
	$lastname = $arrNama[$jumlahNama-1];	
	$alias = $inisial.".".$lastname;
}
else
	$alias = $arrNama[0];
?>
  <i style="font-size:9px;"><?=$suratmasukinfo->KODE_UNIT?>/<?=$suratmasukinfo->KD_SURAT?>/<?=strtoupper($alias)?></i>
</div>
<!-- End Maker Surat -->