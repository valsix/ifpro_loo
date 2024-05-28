<?php
/* INCLUDE FILE */
include_once("functions/date.func.php");
include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("libraries/vendor/autoload.php");

$this->load->library('suratmasukinfo');
$suratmasukinfo = new suratmasukinfo();

$reqId = httpFilterGet("reqId");
$reqDisposisiId = httpFilterGet("reqDisposisiId");
$reqJenisSurat = httpFilterGet("reqJenisSurat");
$suratmasukinfo->getInfo($reqId, $reqJenisSurat);

$this->load->model("Disposisi");
$disposisi = new Disposisi();
$disposisi->selectByParams(array("DISPOSISI_ID" => $reqDisposisiId));
$disposisi->firstRow();
$reqDisposisiParentId = $disposisi->getField("DISPOSISI_PARENT_ID");

?>

<!-- Start Kop Surat -->
<div class="kop-surat">
  <div class="logo-kop"><img src="<?=base_url();?>/images/logo-surat.jpg" width="220px" height="*"></div>
  <div class="alamat-kop">
    <b>PT Angkasa Pura I (Persero)</b><br>
    <i><?=$suratmasukinfo->NAMA_UNIT?></i><br>
    <?=$suratmasukinfo->ALAMAT_UNIT?><br>
    tel : <?=$suratmasukinfo->TELEPON_UNIT?> &nbsp;&nbsp;fax : <?=$suratmasukinfo->FAX_UNIT?><br>
    web : www.ap1.co.id
  </div>
</div>
<!-- End Kop Surat -->

<!-- Start Pembatas -->
<div class="pembatas-atas" style="margin-top: 20px;"></div>
<!-- End Pembatas -->

<!-- Start Jenis Naskah -->
<div class="jenis-naskah" style="margin-top: 0px;">
  <div class="nama-jenis-naskah"><h3>LEMBAR DISPOSISI</h3></div>
  <!-- <div class="nomor-naskah"><?=strtoupper($disposisi->getField("NAMA_SATKER_ASAL"))?></div> -->
</div>
<!-- End Jenis Naskah -->

<!-- Start Tujuan Naskah -->
<div class="disposisi">
  <table width="100%">
    <tr>
      <td width="4%"  style="padding:10px">A</td>
      <td width="43%" style="padding:10px">Nomor Indeks :<br><?=$disposisi->getField("DISPOSISI_ID")?></td>
      <td width="43%" style="padding:10px">Tanggal Disposisi :<br><?=$disposisi->getField("TANGGAL_DISPOSISI")?></td>
    </tr>
    <tr>
      <td width="4%"  style="padding:10px"></td>
      <td width="43%" style="padding:10px">Nomor Surat :<br><?=$suratmasukinfo->NOMOR?></td>
      <td width="43%" style="padding:10px">Tanggal Surat :<br><?=$suratmasukinfo->TANGGAL?></td>
    </tr>
    <tr>
      <td width="4%"  style="padding:10px">B</td>
      <td width="43%" style="padding:10px">DITERUSKAN KEPADA :
      									<? 
										$disposisi_parent = new Disposisi();
										$disposisi_parent->selectByParams(array("DISPOSISI_PARENT_ID" => (int)$reqDisposisiParentId, "SURAT_MASUK_ID" => $reqId, "STATUS_DISPOSISI" => "DISPOSISI"));
										while($disposisi_parent->nextRow())
										{
											echo "<br>&raquo; ".$disposisi_parent->getField("NAMA_SATKER");	
										}
										?></td>
      <td width="43%" style="padding:10px">ISI DISPOSISI :
      									<? 
										$arrDisposisi = explode(",", $disposisi->getField("ISI"));
										for($i=0;$i<count($arrDisposisi);$i++)
										{
											echo "<br>&raquo; ".$arrDisposisi[$i];	
										}
										?></td>
    </tr>
    <tr>
      <td width="4%"  style="padding:10px"></td>
      <td width="43%" style="padding:10px" colspan="2">SIFAT : <?=$suratmasukinfo->SIFAT_NASKAH?></td>
    </tr>
    <tr>
      <td width="4%"  style="padding:10px; height:200px">C</td>
      <td width="43%" style="padding:10px" colspan="2">CATATAN LAIN :</td>
    </tr>
    <tr>
  </table>
</div>