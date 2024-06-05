<?php
include_once("functions/date.func.php");
include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("libraries/vendor/autoload.php");

$this->load->model("TrLoo");
$this->load->model("TrLooDetil");
$this->load->model("Combo");

$reqId= $this->input->get("reqId");

if(empty($reqId)) $reqId= -1;

$statement= " AND A.TR_LOO_ID = ".$reqId;
$set= new TrLoo();
$set->selectcetak(array(), -1,-1, $statement);
$set->firstRow();
$reqLokasiNama= $set->getField("LOKASI_NAMA");
$reqCustomerNama= $set->getField("NAMA_PEMILIK");
$reqCustomerTempat= $set->getField("TEMPAT");
$reqCustomerTlp= $set->getField("TELP");
$reqCustomerEmail= $set->getField("EMAIL");
$reqCustomerBrand= $set->getField("NAMA_BRAND");
$reqProdukNama= $set->getField("PRODUK_NAMA");
$reqTotalLuas= $set->getField("TOTAL_LUAS");
$reqHargaIndoorSewa= $set->getField("HARGA_INDOOR_SEWA");
$reqHargaOutdoorSewa= $set->getField("HARGA_OUTDOOR_SEWA");
$reqHargaIndoorService= $set->getField("HARGA_INDOOR_SERVICE");
$reqHargaOutdoorService= $set->getField("HARGA_OUTDOOR_SERVICE");
$reqDp= $set->getField("DP");
$reqPeriodeSewa= $set->getField("PERIODE_SEWA");

$reqSewaBiayaSatuanUnit= $set->getField("SEWA_BIAYA_SATUAN_UNIT");
$reqSewaBiayaSatuanService= $set->getField("SEWA_BIAYA_SATUAN_SERVICE");
$reqSewaTotalBiayaUnit= $set->getField("SEWA_TOTAL_BIAYA_UNIT");
$reqSewaBiayaPerBulanUnit= $set->getField("SEWA_BIAYA_PER_BULAN_UNIT");
$reqSewaBiayaPerBulanService= $set->getField("SEWA_BIAYA_PER_BULAN_SERVICE");
$reqSewaTotalBiayaService= $set->getField("SEWA_TOTAL_BIAYA_SERVICE");
$reqTotalBiayaPerBulanNoPpn= $set->getField("TOTAL_BIAYA_PER_BULAN_NO_PPN");
$reqTotalBiayaNoPpn= $set->getField("TOTAL_BIAYA_NO_PPN");
$reqTotalBiayaPerBulanPpn= $set->getField("TOTAL_BIAYA_PER_BULAN_PPN");
$reqTotalBiayaPpn= $set->getField("TOTAL_BIAYA_PPN");

$arrlokasi= [];
$statement= " AND A.TR_LOO_ID = ".$reqId." AND VMODE ILIKE '%luas_sewa%'";
$set= new TrLooDetil();
$set->selectlokasi(array(), -1,-1, $statement);
while($set->nextRow())
{
    $valid= $set->getField("VID");
    $valmode= $set->getField("VMODE");

    $arrdata= [];
    $arrdata["key"]= $valid."-".$valmode;
    $arrdata["rowdetilid"]= $set->getField("TR_LOO_DETIL_ID");;
    $arrdata["rowid"]= $set->getField("TR_LOO_ID");
    $arrdata["vmode"]= $valmode;
    $arrdata["vid"]= $valid;
    $arrdata["vnilai"]= $set->getField("NILAI");
    $arrdata["kode"]= $set->getField("KODE");
    $arrdata["nama"]= $set->getField("NAMA");
    $arrdata["lantai"]= $set->getField("LANTAI");
    array_push($arrlokasi, $arrdata);
}
// print_r($arrlokasi);exit;

$arrdetil= [];
$statement= " AND A.TR_LOO_ID = ".$reqId;
$set= new TrLooDetil();
$set->selectByParams(array(), -1,-1, $statement);
while($set->nextRow())
{
    $valid= $set->getField("VID");
    $valmode= $set->getField("VMODE");
    $arrdata= [];
    $arrdata["keyrowdetil"]= $valid."-".$valmode;
    $arrdata["rowdetilid"]= $set->getField("TR_LOO_DETIL_ID");;
    $arrdata["rowid"]= $set->getField("TR_LOO_ID");
    $arrdata["vmode"]= $valmode;
    $arrdata["vid"]= $valid;
    $arrdata["vnilai"]= $set->getField("NILAI");
    $arrdata["vketerangan"]= $set->getField("KETERANGAN");

    $keymodesewa= "";
    if(isStrContain($valmode, "tarif_sewa_unit_indoor"))
      $keymodesewa= $valid."-"."tarif_sewa_unit_indoor";
    else if(isStrContain($valmode, "tarif_sewa_unit_outdoor"))
      $keymodesewa= $valid."-"."tarif_sewa_unit_outdoor";
    $arrdata["keymodesewa"]= $keymodesewa;

    array_push($arrdetil, $arrdata);
}
// print_r($arrdetil);exit;

$arrutilitycharge= [];
$set= new Combo();
$set->comboUtilityCharge(array(), -1,-1, "", "ORDER BY UTILITY_CHARGE_ID");
while($set->nextRow())
{
    $arrdata= [];
    $arrdata["id"]= $set->getField("UTILITY_CHARGE_ID");
    $arrdata["nama"]= $set->getField("NAMA");
    $arrdata["ket"]= $set->getField("KETERANGAN");
    array_push($arrutilitycharge, $arrdata);
}
// print_r($arrutilitycharge);exit;
?>
<base href="<?=base_url();?>">
<link href="css/gaya-surat.css" rel="stylesheet" type="text/css">
<link href="lib/froala_editor_2.9.8/css/froala_style.css" rel="stylesheet" type="text/css">
<style>
  body{
  /*background-image:url('<?= base_url() ?>images/bg_cetak.jpg')  ;
    background-image-resize:6;
    background-size: cover;*/
  }
</style>
<body>
  <!-- head -->
  <table style="width: 100%;">
    <tr>
      <td colspan="7">
        <img src="<?='images/logo.png'?>" height="100px">
      </td>
    </tr>
    <tr>
      <td style="text-align:center;" colspan="7">
        <h1><b><u>LETTER OF OFFERING</u></b></h1>
      </td>
    </tr>
    <tr>
      <td style="width:10%">NO</td>
      <td style="width:1%">:</td>
      <td style="width:19%"></td>
      <td style="width:45%"></td>
      <td style="width:10%">Lampiran  </td>
      <td style="width:1%">:</td>
      <td style="width:14%;text-align: right;">2 (dua) Halaman</td>
    </tr>
    <tr>
      <td>TANGGAL</td>
      <td>:</td>
      <td></td>
      <td></td>
      </tr>
    <tr>
      <td><br></td>
    </tr>
    <tr>
      <td colspan=7>
        <?=$reqLokasiNama?>
      </td>
    </tr>
    <tr>
      <td><br></td>
    </tr>
    <tr>
      <td colspan="3">Kepada Yth.</td>
    </tr>
    <tr>
      <td colspan="3">Bapak <?=$reqCustomerNama?></td>
    </tr>
    <tr>
      <td colspan="3"><?=$reqCustomerTempat?></td>
    </tr>
    <tr>
      <td colspan="3">
        <table>
          <tr>
            <td>Telp./HP</td>
            <td>:</td>
            <td><?=$reqCustomerTlp?></td>
          </tr>
          <tr>
            <td>Email</td>
            <td>:</td>
            <td><?=$reqCustomerEmail?></td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td colspan="3">di Tempat</td>
    </tr>
  </table>
  <br>
  <!-- body -->
  <table style="width: 100%;">
    <tr>
      <td colspan="9">Salam hangat dari <b>PT Indonesia Ferry Properti.</b></td>
    </tr>
    <tr>
      <td colspan="9">Bersama ini kami ingin menawarkan ruang sewa kami, dibawah ini adalah rincian lokasinya:</td>
    </tr>
    <tr>
      <td><br></td>
    </tr>
    <?
    $nomorhead= 1;
    ?>
    <tr>
      <td style="width:3%"><?=$nomorhead?></td>
      <td colspan="4">NAMA PEMILIK</td>
      <td ><b>:</b></td>
      <td colspan="3"><b><?=$reqCustomerNama?></b></td>
    </tr>
    <?
    $nomorhead+=1;
    ?>
    <tr>
      <td style="width:3%"><?=$nomorhead?></td>
      <td colspan="4">NAMA BRAND</td>
      <td ><b>:</b></td>
      <td colspan="3"><b><?=$reqCustomerBrand?></b></td>
    </tr>
    <?
    $nomorhead+=1;
    ?>
    <tr>
      <td style="width:3%"><?=$nomorhead?></td>
      <td colspan="4">PRODUK</td>
      <td ><b>:</b></td>
      <td colspan="3"><b><?=$reqProdukNama?></b></td>
    </tr>
    <?
    $nomorhead+=1;
    ?>
    <tr>
      <td style="width:3%"><?=$nomorhead?></td>
      <td colspan="4" >LUAS SEWA</td>
    </tr>
    <?
    $nomorsubhead= 0;
    $nomorsubheaddetil= 0;

    $infocarikey= "luas_sewa_indoor";
    $arrkondisicheck= in_array_column($infocarikey, "vmode", $arrdetil);
    if(count($arrkondisicheck) > 0)
    {
      $nomorsubhead+=1;
      $vnomordetil= $nomorhead.".".$nomorsubhead;
    ?>
      <tr>
        <td></td>
        <td style="width:5%"><?=$vnomordetil?></td>
        <td colspan="5">Indoor</td>
      </tr>
    <?
      foreach ($arrkondisicheck as $k)
      {
        $v= $arrdetil[$k];
        // print_r($v);exit;
        $nomorsubheaddetil+=1;

        $vkode= $vlantai= "";
        $infocarikey= $v["keyrowdetil"];
        $arrkondisicheckdetil= in_array_column($infocarikey, "key", $arrlokasi);
        if(!empty($arrkondisicheckdetil))
        {
          $vd= $arrlokasi[$arrkondisicheckdetil[0]];
          $vkode= $vd["kode"];
          $vlantai= $vd["lantai"];
        }
    ?>
        <tr>
          <td></td>
          <td></td>
          <td style="width:5%"><?=$vnomordetil.".".$nomorsubheaddetil?></td>
          <td style="width:10%"><?=$vkode?></td>
          <td style="width:10%"><?=$vlantai?></td>
          <td style="width:5%">:</td>
          <td style="width:10%"><?=numberToIna($v["vnilai"])?></td>
          <td style="width:10%">m2</td>
        </tr>
    <?
      }
    }
    ?>

    <?
    $infocarikey= "luas_sewa_outdoor";
    $arrkondisicheck= in_array_column($infocarikey, "vmode", $arrdetil);
    if(count($arrkondisicheck) > 0)
    {
      $nomorsubhead+=1;
      $vnomordetil= $nomorhead.".".$nomorsubhead;
    ?>
      <tr>
        <td></td>
        <td style="width:5%"><?=$vnomordetil?></td>
        <td colspan="5">Outdor</td>
      </tr>
    <?
      foreach ($arrkondisicheck as $k)
      {
        $v= $arrdetil[$k];
        // print_r($v);exit;
        $nomorsubheaddetil+=1;

        $vkode= $vlantai= "";
        $infocarikey= $v["keyrowdetil"];
        $arrkondisicheckdetil= in_array_column($infocarikey, "key", $arrlokasi);
        if(!empty($arrkondisicheckdetil))
        {
          $vd= $arrlokasi[$arrkondisicheckdetil[0]];
          $vkode= $vd["kode"];
          $vlantai= $vd["lantai"];
        }
    ?>
        <tr>
          <td></td>
          <td></td>
          <td style="width:5%"><?=$vnomordetil.".".$nomorsubheaddetil?></td>
          <td style="width:10%"><?=$vkode?></td>
          <td style="width:10%"><?=$vlantai?></td>
          <td style="width:5%">:</td>
          <td style="width:10%"><?=numberToIna($v["vnilai"])?></td>
          <td style="width:10%">m2</td>
        </tr>
    <?
      }
    }
    ?>

    <?
    $nomorsubhead+=1;
    $vnomordetil= $nomorhead.".".$nomorsubhead;
    ?>
    <tr>
      <td></td>
      <td style="width:5%"><?=$vnomordetil?></td>
      <td colspan="3">Total Luas Sewa</td>
      <td style="width:5%">:</td>
      <td style="width:10%"><?=numberToIna($reqTotalLuas)?></td>
      <td style="width:10%">m2</td>
    </tr>

    <?
    $nomorhead+=1;
    ?>
    <tr>
      <td style="width:3%"><?=$nomorhead?></td>
      <td colspan="4" >TARIF SEWA</td>
    </tr>

    <?
    $nomorsubhead= 0;
    $nomorsubheaddetil= 0;

    $nomorsubhead+=1;
    $vnomordetil= $nomorhead.".".$nomorsubhead;
    ?>
    <tr>
      <td></td>
      <td style="width:5%"><?=$vnomordetil?></td>
      <td colspan="5">Unit</td>
    </tr>

    <?
    $infocarikey= "luas_sewa_indoor";
    $arrkondisicheck= in_array_column($infocarikey, "vmode", $arrdetil);
    if(count($arrkondisicheck) > 0)
    {
      foreach ($arrkondisicheck as $k)
      {
        $v= $arrdetil[$k];
        // print_r($v);exit;
        $nomorsubheaddetil+=1;

        $vkode= $vlantai= "";
        $infocarikey= $v["keyrowdetil"];
        $arrkondisicheckdetil= in_array_column($infocarikey, "key", $arrlokasi);
        if(!empty($arrkondisicheckdetil))
        {
          $vd= $arrlokasi[$arrkondisicheckdetil[0]];
          $vid= $vd["vid"];
          $vkode= $vd["kode"];
          $vlantai= $vd["lantai"];
        }

        $vnilai= "";
        $infocarikey= $vid."-tarif_sewa_unit_indoor";
        $arrcheck= in_array_column($infocarikey, "keyrowdetil", $arrdetil);
        if(!empty($arrcheck))
        {
          $vnilai= numberToIna($arrdetil[$arrcheck[0]]["vnilai"]);
        }
    ?>
      <tr>
        <td></td>
        <td></td>
        <td style="width:5%"><?=$vnomordetil.".".$nomorsubheaddetil?></td>
        <td colspan="2">Indoor <?=$vkode." ".$vlantai?></td>
        <td style="width:5%">:</td>
        <td style="width:10%"> <?=$vnilai?></td>
        <td style="width:5%">Rp / m2</td>
      </tr>

      <?
      $vnilai= "";
      $infocarikey= $vid."-tarif_sewa_unit_indoor_diskon";
      $arrcheck= in_array_column($infocarikey, "keyrowdetil", $arrdetil);
      if(!empty($arrcheck))
      {
        $vnilai= numberToIna($arrdetil[$arrcheck[0]]["vnilai"]);
      }
      ?>
      <tr>
        <td colspan="3"></td>
        <td colspan="2">Discount</td>
        <td style="width:5%">:</td>
        <td style="width:10%"> <?=$vnilai?></td>
        <td style="width:5%">%</td>
      </tr>

      <?
      $vnilai= "";
      $infocarikey= $vid."-tarif_sewa_unit_indoor_after_diskon";
      $arrcheck= in_array_column($infocarikey, "keyrowdetil", $arrdetil);
      if(!empty($arrcheck))
      {
        $vnilai= numberToIna($arrdetil[$arrcheck[0]]["vnilai"]);
      }
      ?>
      <tr>
        <td colspan="3"></td>
        <td colspan="2">Tarif (after discount)</td>
        <td style="width:5%">:</td>
        <td style="width:10%"> <?=$vnilai?></td>
        <td style="width:5%">Rp / m2</td>
      </tr>
    <?
      }
    }
    ?>

    <?
    $infocarikey= "luas_sewa_outdoor";
    $arrkondisicheck= in_array_column($infocarikey, "vmode", $arrdetil);
    if(count($arrkondisicheck) > 0)
    {
      foreach ($arrkondisicheck as $k)
      {
        $v= $arrdetil[$k];
        // print_r($v);exit;
        $nomorsubheaddetil+=1;

        $vkode= $vlantai= "";
        $infocarikey= $v["keyrowdetil"];
        $arrkondisicheckdetil= in_array_column($infocarikey, "key", $arrlokasi);
        if(!empty($arrkondisicheckdetil))
        {
          $vd= $arrlokasi[$arrkondisicheckdetil[0]];
          $vid= $vd["vid"];
          $vkode= $vd["kode"];
          $vlantai= $vd["lantai"];
        }

        $vnilai= "";
        $infocarikey= $vid."-tarif_sewa_unit_outdoor";
        $arrcheck= in_array_column($infocarikey, "keyrowdetil", $arrdetil);
        if(!empty($arrcheck))
        {
          $vnilai= numberToIna($arrdetil[$arrcheck[0]]["vnilai"]);
        }
    ?>
      <tr>
        <td></td>
        <td></td>
        <td style="width:5%"><?=$vnomordetil.".".$nomorsubheaddetil?></td>
        <td colspan="2">Outdoor <?=$vkode." ".$vlantai?></td>
        <td style="width:5%">:</td>
        <td style="width:10%"> <?=$vnilai?></td>
        <td style="width:5%">Rp / m2</td>
      </tr>

      <?
      $vnilai= "";
      $infocarikey= $vid."-tarif_sewa_unit_outdoor_diskon";
      $arrcheck= in_array_column($infocarikey, "keyrowdetil", $arrdetil);
      if(!empty($arrcheck))
      {
        $vnilai= numberToIna($arrdetil[$arrcheck[0]]["vnilai"]);
      }
      ?>
      <tr>
        <td colspan="3"></td>
        <td colspan="2">Discount</td>
        <td style="width:5%">:</td>
        <td style="width:10%"> <?=$vnilai?></td>
        <td style="width:5%">%</td>
      </tr>

      <?
      $vnilai= "";
      $infocarikey= $vid."-tarif_sewa_unit_outdoor_after_diskon";
      $arrcheck= in_array_column($infocarikey, "keyrowdetil", $arrdetil);
      if(!empty($arrcheck))
      {
        $vnilai= numberToIna($arrdetil[$arrcheck[0]]["vnilai"]);
      }
      ?>
      <tr>
        <td colspan="3"></td>
        <td colspan="2">Tarif (after discount)</td>
        <td style="width:5%">:</td>
        <td style="width:10%"> <?=$vnilai?></td>
        <td style="width:5%">Rp / m2</td>
      </tr>
    <?
      }
    }
    ?>

    <?
    $nomorsubheaddetil= 0;

    $nomorsubhead+=1;
    $vnomordetil= $nomorhead.".".$nomorsubhead;
    ?>
    <tr>
      <td></td>
      <td style="width:5%"><?=$vnomordetil?></td>
      <td colspan="5">Service Charge</td>
    </tr>

    <?
    $infocarikey= "luas_sewa_indoor";
    $arrkondisicheck= in_array_column($infocarikey, "vmode", $arrdetil);
    if(count($arrkondisicheck) > 0)
    {
      foreach ($arrkondisicheck as $k)
      {
        $v= $arrdetil[$k];
        // print_r($v);exit;
        $nomorsubheaddetil+=1;

        $vkode= $vlantai= "";
        $infocarikey= $v["keyrowdetil"];
        $arrkondisicheckdetil= in_array_column($infocarikey, "key", $arrlokasi);
        if(!empty($arrkondisicheckdetil))
        {
          $vd= $arrlokasi[$arrkondisicheckdetil[0]];
          $vid= $vd["vid"];
          $vkode= $vd["kode"];
          $vlantai= $vd["lantai"];
        }

        $vnilai= "";
        $infocarikey= $vid."-tarif_sewa_sc_indoor";
        $arrcheck= in_array_column($infocarikey, "keyrowdetil", $arrdetil);
        if(!empty($arrcheck))
        {
          $vnilai= numberToIna($arrdetil[$arrcheck[0]]["vnilai"]);
        }
    ?>
      <tr>
        <td></td>
        <td></td>
        <td style="width:5%"><?=$vnomordetil.".".$nomorsubheaddetil?></td>
        <td colspan="2">Indoor <?=$vkode." ".$vlantai?></td>
        <td style="width:5%">:</td>
        <td style="width:10%"> <?=$vnilai?></td>
        <td style="width:5%">Rp / m2</td>
      </tr>

      <?
      $vnilai= "";
      $infocarikey= $vid."-tarif_sewa_sc_indoor_diskon";
      $arrcheck= in_array_column($infocarikey, "keyrowdetil", $arrdetil);
      if(!empty($arrcheck))
      {
        $vnilai= numberToIna($arrdetil[$arrcheck[0]]["vnilai"]);
      }
      ?>
      <tr>
        <td colspan="3"></td>
        <td colspan="2">Discount</td>
        <td style="width:5%">:</td>
        <td style="width:10%"> <?=$vnilai?></td>
        <td style="width:5%">%</td>
      </tr>

      <?
      $vnilai= "";
      $infocarikey= $vid."-tarif_sewa_sc_indoor_after_diskon";
      $arrcheck= in_array_column($infocarikey, "keyrowdetil", $arrdetil);
      if(!empty($arrcheck))
      {
        $vnilai= numberToIna($arrdetil[$arrcheck[0]]["vnilai"]);
      }
      ?>
      <tr>
        <td colspan="3"></td>
        <td colspan="2">Tarif (after discount)</td>
        <td style="width:5%">:</td>
        <td style="width:10%"> <?=$vnilai?></td>
        <td style="width:5%">Rp / m2</td>
      </tr>
    <?
      }
    }
    ?>

    <?
    $infocarikey= "luas_sewa_outdoor";
    $arrkondisicheck= in_array_column($infocarikey, "vmode", $arrdetil);
    if(count($arrkondisicheck) > 0)
    {
      foreach ($arrkondisicheck as $k)
      {
        $v= $arrdetil[$k];
        // print_r($v);exit;
        $nomorsubheaddetil+=1;

        $vkode= $vlantai= "";
        $infocarikey= $v["keyrowdetil"];
        $arrkondisicheckdetil= in_array_column($infocarikey, "key", $arrlokasi);
        if(!empty($arrkondisicheckdetil))
        {
          $vd= $arrlokasi[$arrkondisicheckdetil[0]];
          $vid= $vd["vid"];
          $vkode= $vd["kode"];
          $vlantai= $vd["lantai"];
        }

        $vnilai= "";
        $infocarikey= $vid."-tarif_sewa_sc_outdoor";
        $arrcheck= in_array_column($infocarikey, "keyrowdetil", $arrdetil);
        if(!empty($arrcheck))
        {
          $vnilai= numberToIna($arrdetil[$arrcheck[0]]["vnilai"]);
        }
    ?>
      <tr>
        <td></td>
        <td></td>
        <td style="width:5%"><?=$vnomordetil.".".$nomorsubheaddetil?></td>
        <td colspan="2">Outdoor <?=$vkode." ".$vlantai?></td>
        <td style="width:5%">:</td>
        <td style="width:10%"> <?=$vnilai?></td>
        <td style="width:5%">Rp / m2</td>
      </tr>

      <?
      $vnilai= "";
      $infocarikey= $vid."-tarif_sewa_sc_outdoor_diskon";
      $arrcheck= in_array_column($infocarikey, "keyrowdetil", $arrdetil);
      if(!empty($arrcheck))
      {
        $vnilai= numberToIna($arrdetil[$arrcheck[0]]["vnilai"]);
      }
      ?>
      <tr>
        <td colspan="3"></td>
        <td colspan="2">Discount</td>
        <td style="width:5%">:</td>
        <td style="width:10%"> <?=$vnilai?></td>
        <td style="width:5%">%</td>
      </tr>

      <?
      $vnilai= "";
      $infocarikey= $vid."-tarif_sewa_sc_outdoor_after_diskon";
      $arrcheck= in_array_column($infocarikey, "keyrowdetil", $arrdetil);
      if(!empty($arrcheck))
      {
        $vnilai= numberToIna($arrdetil[$arrcheck[0]]["vnilai"]);
      }
      ?>
      <tr>
        <td colspan="3"></td>
        <td colspan="2">Tarif (after discount)</td>
        <td style="width:5%">:</td>
        <td style="width:10%"> <?=$vnilai?></td>
        <td style="width:5%">Rp / m2</td>
      </tr>
    <?
      }
    }
    ?>

    <?
    $nomorhead+=1;

    $nomorsubhead= 0;

    $nomorsubhead+=1;
    $vnomordetil= $nomorhead.".".$nomorsubhead;
    ?>
    <tr>
      <td style="width:3%"><?=$nomorhead?></td>
      <td colspan="4" >HARGA SEWA</td>
    </tr>
    <tr>
      <td></td>
      <td style="width:5%"><?=$vnomordetil?></td>
      <td colspan="3">Indoor</td>
      <td style="width:5%">:</td>
      <td style="width:10%"> Rp  <?=numberToIna($reqHargaIndoorSewa)?></td>
      <td style="width:5%">/ m2 / bulan</td>
    </tr>
    <?
    $nomorsubhead+=1;
    $vnomordetil= $nomorhead.".".$nomorsubhead;
    ?>
    <tr>
      <td></td>
      <td style="width:5%"><?=$vnomordetil?></td>
      <td colspan="3">Outdoor</td>
      <td style="width:5%">:</td>
      <td style="width:10%"> Rp  <?=numberToIna($reqHargaOutdoorSewa)?></td>
      <td style="width:5%">/ m2 / bulan</td>
    </tr>

    <?
    $nomorhead+=1;

    $nomorsubhead= 0;

    $nomorsubhead+=1;
    $vnomordetil= $nomorhead.".".$nomorsubhead;
    ?>
    <tr>
      <td style="width:3%"><?=$nomorhead?></td>
      <td colspan="4" >HARGA SERVICE CHARGE</td>
    </tr>
    <tr>
      <td></td>
      <td style="width:5%"><?=$vnomordetil?></td>
      <td colspan="3">Indoor</td>
      <td style="width:5%">:</td>
      <td style="width:10%"> Rp  <?=numberToIna($reqHargaIndoorService)?></td>
      <td style="width:5%">/ m2 / bulan</td>
    </tr>
    <?
    $nomorsubhead+=1;
    $vnomordetil= $nomorhead.".".$nomorsubhead;
    ?>
    <tr>
      <td></td>
      <td style="width:5%"><?=$vnomordetil?></td>
      <td colspan="3">Outdoor</td>
      <td style="width:5%">:</td>
      <td style="width:10%"> Rp  <?=numberToIna($reqHargaOutdoorService)?></td>
      <td style="width:5%">/ m2 / bulan</td>
    </tr>

    <?
    $nomorhead+=1;

    $nomorsubhead= 0;
    ?>
    <tr>
      <td style="width:3%"><?=$nomorhead?></td>
      <td colspan="4" >HARGA SERVICE CHARGE</td>
    </tr>
    <?
    foreach ($arrdetil as $k => $v)
    {
        $valid= $v["vid"];
        $valnilai= $v["vnilai"];
        $valketerangan= $v["vketerangan"];
        $vmode= $v["vmode"];
        $valmode= "harga_utility_charge";
        if($vmode == $valmode)
        {
          $nomorsubhead+=1;
          $vnomordetil= $nomorhead.".".$nomorsubhead;

          $vnama= "";
          $infocarikey= $valid;
          $arrkondisicheck= in_array_column($infocarikey, "id", $arrutilitycharge);
          if(!empty($arrkondisicheck))
          {
              $vindex= $arrkondisicheck[0];
              $vnama= $arrutilitycharge[$vindex]["nama"];
          }
    ?>
    <tr>
      <td></td>
      <td style="width:5%"><?=$vnomordetil?></td>
      <td colspan="3"><?=$vnama?></td>
      <td style="width:5%">:</td>
      <td style="width:10%"> Rp   <?=numberToIna($valnilai)?></td>
      <td style="width:5%">/ <?=$valketerangan?></td>
    </tr>
    <?
        }
    }
    ?>

    <?
    $nomorhead+=1;
    ?>
    <tr>
      <td style="width:3%"><?=$nomorhead?></td>
      <td colspan="4">DOWN PAYMENT</td>
      <td >:</td>
      <td><?=numberToIna($reqDp)?></td>
      <td>%</td>
    </tr>

    <?
    $nomorhead+=1;
    ?>
    <tr>
      <td style="width:3%"><?=$nomorhead?></td>
      <td colspan="4">PERIODE SEWA</td>
      <td >:</td>
      <td><?=numberToIna($reqPeriodeSewa)?></td>
      <td>bulan</td>
    </tr>

    <?
    $nomorhead+=1;
    $nomorsubhead= 0;
    ?>
    <tr>
      <td style="width:3%"><?=$nomorhead?></td>
      <td colspan="4" >JAM OPERASIONAL</td>
    </tr>

    <?

    $nomorsubhead+=1;
    $vnomordetil= $nomorhead.".".$nomorsubhead;

    $vkeyid= 1;
    $valmode= "jam_operasional_gedung";
    $infocarikey= $vkeyid."-".$valmode;
    $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrdetil);
    if(!empty($arrkondisicheck))
    {
        $vindex= $arrkondisicheck[0];
        $valketerangan= $arrdetil[$vindex]["vketerangan"];
    }
    ?>
    <tr>
      <td></td>
      <td style="width:5%"><?=$vnomordetil?></td>
      <td colspan="3">Gedung</td>
      <td style="width:5%">:</td>
      <td colspan="2"><?=$valketerangan?></td>
    </tr>

    <?

    $nomorsubhead+=1;
    $vnomordetil= $nomorhead.".".$nomorsubhead;
    
    $vkeyid= 2;
    $valmode= "jam_operasional_tenan";
    $infocarikey= $vkeyid."-".$valmode;
    $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrdetil);
    if(!empty($arrkondisicheck))
    {
        $vindex= $arrkondisicheck[0];
        $valketerangan= $arrdetil[$vindex]["vketerangan"];
    }
    ?>
    <tr>
      <td></td>
      <td style="width:5%"><?=$vnomordetil?></td>
      <td colspan="3">Tenant</td>
      <td style="width:5%">:</td>
      <td colspan="2">10:00 s/d 22:00 </td>
    </tr>
  </table>
  <!-- rincian sewa -->
  <table style="width: 100%;">
    <tr>
      <td colspan="8"><u><b>RINCIAN NILAI SEWA</b></u></td>
    </tr>
    <tr>
      <td style="border:solid black 0.5;text-align: center;">NO</td>
      <td style="border:solid black 0.5;text-align: center;">KETERANGAN          </td>
      <td style="border:solid black 0.5;text-align: center;">BIAYA SATUAN      </td>
      <td style="border:solid black 0.5;text-align: center;">PER BULAN     </td>
      <td style="border:solid black 0.5;text-align: center;">TOTAL     </td>
    </tr>
    <tr>
      <td style="border:solid black 0.5;text-align: center;">1</td>
      <td style="border:solid black 0.5;padding-left: 5px;">Harga Sewa Unit</td>
      <td style="border:solid black 0.5;text-align:right;padding-right: 5px;"><?=numberToIna($reqSewaBiayaSatuanUnit)?></td>
      <td style="border:solid black 0.5;text-align:right;padding-right: 5px;"><?=numberToIna($reqSewaBiayaPerBulanUnit)?></td>
      <td style="border:solid black 0.5;text-align:right;padding-right: 5px;"><?=numberToIna($reqSewaTotalBiayaUnit)?></td>
    </tr>
    <tr>
      <td style="border:solid black 0.5;text-align: center;">2</td>
      <td style="border:solid black 0.5;padding-left: 5px;">Service Charge Tahun Pertama          </td>
      <td style="border:solid black 0.5;text-align:right;padding-right: 5px;"><?=numberToIna($reqSewaBiayaSatuanService)?></td>
      <td style="border:solid black 0.5;text-align:right;padding-right: 5px;"><?=numberToIna($reqSewaBiayaPerBulanService)?></td>
      <td style="border:solid black 0.5;text-align:right;padding-right: 5px;"><?=numberToIna($reqSewaTotalBiayaService)?></td>
    </tr>
    <tr>
      <td style="border:solid black 0.5;padding-left: 5px;" colspan="3">Total (Tanpa PPN)         </td>
      <td style="border:solid black 0.5;text-align:right;padding-right: 5px;"><?=numberToIna($reqTotalBiayaPerBulanNoPpn)?></td>
      <td style="border:solid black 0.5;text-align:right;padding-right: 5px;"><?=numberToIna($reqTotalBiayaNoPpn)?></td>
    </tr>
    <tr>
      <td style="border:solid black 0.5;padding-left: 5px;" colspan="3">Total (Dengan PPN)          </td>
      <td style="border:solid black 0.5;text-align:right;padding-right: 5px;"><?=numberToIna($reqTotalBiayaPerBulanPpn)?></td>
      <td style="border:solid black 0.5;text-align:right;padding-right: 5px;"><?=numberToIna($reqTotalBiayaPpn)?></td>
    </tr>
    <tr>
      <td><br></td>
    </tr>
    <tr>
      <td colspan="8"><u><b>KONDISI DAN PERSYARATAN</b></u></td>
    </tr>
    <tr>
      <td ><b>1</b></td>
      <td colspan="5"><b>BIAYA LAYANAN (SERVICE CHARGE)</b>        </td>
    </tr>
    <tr>
      <td ></td>
      <td colspan="5"> Harga biaya pelayanan sewaktu - waktu dapat berubah mengikuti ketentuan Gedung yang berlaku. Biaya pelayanan dibayarkan setiap bulan dimuka sejak toko mulai beroperasi. Biaya pelayanan ini dapat ditinjau kembali berdasarkan kenaikan biaya operasional Gedung.                               
      </td>
    </tr>
    <tr>
      <td ><b>2</b></td>
      <td colspan="5"><b>JAMINAN SEWA (SECURITY DEPOSIT)</b>        </td>
    </tr>
    <tr>
      <td ></td>
      <td colspan="5">Jaminan Sewa sebesar<b> Rp 42.675.178    </b>        </td>
    </tr>
    <tr>
      <td ></td>
      <td colspan="5"> Uang jaminan dibayarkan pada saat penandatangan surat persetujuan. Pada saat masa sewa berakhir, Uang jaminan dapat dikembalikan dan diserahkan kepada Penyewa sesuai dengan mata uang Rupiah dan Jumlah nominal yang senyatanya dibayarkan pada waktu dilakukannya pembayaran uang jaminan, bebas bunga setelah dikurangi dengan pembayaran yang masih tertunggak dan/atau kewajiban – kewajiban Penyewa lainnya terhadap Pengelola Gedung (jika ada) berdasarkan Perjanjian Sewa Menyewa.                                                               
      </td>
    </tr>
    <tr>
      <td ><b>3</b></td>
      <td colspan="5"><b>FITTING OUT</b>        </td>
    </tr>
    <tr>
      <td ></td>
      <td colspan="5"> Fitting out sewa sebesar     <b>Rp   3.500.000</b>                                 
      </td>
    </tr>
    <tr>
      <td ></td>
      <td colspan="5"> 
      Uang Jaminan Fitting out ini akan dibayarkan paling lambat pada saat serah terima ruangan dengan ketentuan pembayaran dilakukan selambatnya 7 (tujuh) hari kerja setelah tagihan diterima oleh Penyewa dan uang jaminan Fitting out akan dikembalikan saat renovasi berakhir. Selain itu selama periode fit out. Penyewa akan dikenakan biaya Fitting out (air kerja, listrik kerja, koordinasi security) yang besarkan akan ditentukan kemudian oleh Pengelola Gedung. Pengelola Gedung memberikan waktu untuk periode Fitting out ruang sewa selama 7 (tujuh) hari dari tanggal serah terima ruang sewa.                                                               
      </td>
    </tr>
    <tr>
      <td ><b>4</b></td>
      <td colspan="5"><b>AIR, LISTRIK DAN GAS</b>        </td>
    </tr>
    <tr>
      <td ></td>
      <td colspan="5">
      Seluruh biaya penggunaan air,gas, listrik yang diadakan di tempat yang disewakan akan dibayar terpisah sesuai yang tercantum pada meteran terpasang.                                                               
      </td>
    </tr>
    <tr>
      <td ><b>5</b></td>
      <td colspan="5"><b>PAJAK PERTAMBAHAN NILAI</b>        </td>
    </tr>
    <tr>
      <td ></td>
      <td colspan="5">Pajak Pertambahan Nilai (PPN) ditanggung oleh Penyewa karena tidak termasuk dalam harga sewa menyewa dan biaya lainnya dan akan disesuaikan dengan peraturan pemerintah.                               
      </td>
    </tr>
    <tr>
      <td ><b>6</b></td>
      <td colspan="5"><b>CARA PEMBAYARAN</b>        </td>
    </tr>
    <tr>
      <td></td>
      <td colspan="5">
        <table style="width:100%">
          <tr>
            <td style="width:5%">
              6.1
            </td>
            <td style="width:30%">
               Down Payment sebesar    
            </td>
            <td style="width:5%">:</td>
            <td >Rp   71.295.034     </td>
          </tr>
        </table>
      </td>      
      </tr>
    <tr>
      <td></td>
      <td colspan="5">
        <table style="width:100%">
          <tr>
            <td style="width:5%">
              6.2
            </td>
            <td style="width:30%">
               Security Deposit sebesar
            </td>
            <td style="width:5%">:</td>
            <td >Rp    42.675.178</td>
          </tr>
        </table>
      </td>
      </tr>
    <tr>
      <td></td>
      <td colspan="5">
        <table style="width:100%">
          <tr>
            <td style="width:5%">
              6.3
            </td>
            <td style="width:30%">
               Fitting Out sebesar
            </td>
            <td style="width:5%">:</td>
            <td >Rp    3.500.000     </td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td></td>
      <td colspan="5"> 
        <table>
          <tr>
            <td style="width:5%">
              6.4 
            </td>
            <td>
              Pembayaran Downpayment 20%, Security Deposit & Fitting Out wajib dibayarkan paling lambat 7 (tujuh) hari setelah penandatanganan Letter of Intent (LOI). Apabila pembayaran belum kami terima sampai tanggal yang tercantum di surat LOI, maka kami anggap batal dan pemilik dapat menawarkan lokasi dan tempat tersebut kepada pihak lain.    
            </td>
          </tr>
        </table>                              
      </td>
    </tr>
    <tr>
    <tr>
      <td></td>
      <td colspan="5"> 
        <table>
          <tr>
            <td style="width:5%">
              6.5
            </td>
            <td>
              Uang sewa dibayarkan pada saat awal sewa adapun biaya-biaya lain dibayar setiap tanggal 10 setiap bulan, apabila lewat tanggal tersebut maka dikenakan denda sebesar 0.1% per hari dan dibulatkan menjadi 5% per bulan apabila denda sudah melewati lebih dari 30 hari. dari jumlah tertunggak untuk setiap hari keterlambatan terhitung sejak pembayaran tersebut harus dibayarkan sampai seluruh pembayaran yang tertunggak dilunasi.     
            </td>
          </tr>
        </table>                              
      </td>
    </tr>
    <tr>
      <td></td>
      <td colspan="5"> 
        <table style="width:100%">
          <tr>
            <td style="width:5%">
              6.6
            </td>
            <td colspan="3">
              Semua pembayaran dilakukan dengan transfer dalam mata uang Rupiah ke rekening berikut:
            </td>
          </tr>
          <tr>
            <td style="width:5%">
            </td>
            <td style="width:30%">
              No. Rekening
            </td>
            <td style="width:5%">
              :
            </td>
            <td>
              2050051300    
            </td>
          </tr>
          <tr>
            <td style="width:5%">
            </td>
            <td style="width:30%">
              Atas Nama
            </td>
            <td style="width:5%">
              :
            </td>
            <td>
              PT Indonesia Ferry Properti    
            </td>
          </tr>
          <tr>
            <td style="width:5%">
            </td>
            <td style="width:30%">
              Bank
            </td>
            <td style="width:5%">
              :
            </td>
            <td>
              BCA (Bank Central Asia)
            </td>
          </tr>
          <tr>
            <td style="width:5%">
            </td>
            <td style="width:30%">
              Cabang
            </td>
            <td style="width:5%">
              :
            </td>
            <td>
              Merak    
            </td>
          </tr>
        </table>                              
      </td>
    </tr>
    <tr>
      <td></td>
      <td colspan="5"> 
        <table>
          <tr>
            <td style="width:5%">
              6.7
            </td>
            <td>
              Biaya Sewa dan Biaya Jasa Pelayanan dikenakan PPN sesuai dengan tarif yang berlaku untuk masing-masing beban biaya. Beban PPh dari pembayaran biaya sewa, biaya jasa pelayanan serta biaya penggunaan listrik dibebankan kepada pihak Penyewa.
            </td>
          </tr>
        </table>                              
      </td>
    </tr>
    <tr>
      <td></td>
      <td colspan="5">Apabila telah melakukan pembayaran mohon mengirimkan bukti pembayaran ke email nolita@ifpro.co.id                        
      </td>
    </tr>
    <tr>
      <td ><b>7</b></td>
      <td ><b>TEMPAT YANG DISEWAKAN SELALU BEROPERASI</b>        </td>
    </tr>
    <tr>
      <td ></td>
      <td colspan="5">Selama masa sewa, penyewa setuju untuk membuka dan mengoperasikan tempat yang disewakan untuk umum sesuai dengan waktu operasional Sosoro Mall. Pelanggaran atas ketentuan ini Pengelola Gedung berhak mengenakan sanksi, termasuk namun tidak terbatas pada sanksi denda dan/ atau pemutusan fasilitas yang diadakan di tempat yang disewakan.                                                              
      </td>
    </tr>
    <tr>
      <td ><b>8</b></td>
      <td ><b>DENAH TEMPAT YANG DISEWAKAN</b>        </td>
    </tr>
    <tr>
      <td ></td>
      <td colspan="5">Ukuran serta luasan tempat yang disewakan sesuai dengan yang tercantum dalam Perjanjian Sewa Menyewa yang merupakan hasil akhir dari pengukuran bersama/ kenyataan dilapangan yang dilakukan pada saat serah terima tempat yang disewakan.                                                              
      </td>
    </tr>
    <tr>
      <td ><b>9</b></td>
      <td ><b>RELOKASI</b>        </td>
    </tr>
    <tr>
      <td ></td>
      <td colspan="5">Apabila sepanjang masa sewa Penyewa di Sosoro Mall terjadi perubahan yang diperlukan untuk kepentingan Gedung yang akan merubah denah secara keseluruhan, maka Penyewa bersedia untuk dipindahkan ke tempat lain dengan pemberitahuan 30 (tiga puluh) hari sebelumnya atau apabila Penyewa tidak setuju dengan tempat pemindahan tersebut, maka Penyewa dapat membatalkan sewa dengan menerima kembali, tanpa bunga maupun ganti rugi, jaminan sewa, uang yang telah dibayarkan kepada Pengelola Gedung setelah dikurangi dengan uang sewa untuk masa sewa yang telah dipergunakan sampai dengan tanggal efektif pengakhiran.                                                              
      </td>
    </tr>
    <tr>
      <td ><b>10</b></td>
      <td ><b>DOKUMEN PENDUKUNG</b>        </td>
    </tr>
    <tr>
      <td ></td>
      <td colspan="5">Jika Penyewa adalah perusahaan, mohon untuk menyiapkan copy dokumen kepada Pengelola Gedung pada saat Surat Persetujuan Sewa telah ditandatangani dikembalikan kepada Pengelola Gedung yaitu sebagai berikut:                               
      </td>
    </tr>
    <tr>
      <td ></td>
      <td colspan="5">       
        <table style="width:100%">
          <tr>
            <td style="width:5%">
            10.1</td>
            <td>
              Akta Pendirian Perusahaan
            </td>
          </tr>
          <tr>
            <td style="width:5%">
            10.2</td>
            <td >
              Akta Perubahan Terakhir Perusahaan
            </td>
          </tr>
          <tr>
            <td style="width:5%">
            10.3</td>
            <td >
              Nomor Induk Berusaha (OSS)
            </td>
          </tr>
          <tr>
            <td style="width:5%">
            10.4</td>
            <td >
              KTP Direksi    
            </td>
          </tr>
          <tr>
            <td style="width:5%">
            10.5</td>
            <td >
              NPWP & NNPKP    
            </td>
          </tr>
          <tr>
            <td style="width:5%">
            10.6</td>
            <td >
              Surat Kuasa (Jika penandatangan bukan Direksi)    
            </td>
          </tr>
          <tr>
            <td style="width:5%">
            10.7</td>
            <td >
              Jika penyewa adalah perorangan, maka Penyewa wajib memberikan copy KTP dan NPWP *)    
            </td>
          </tr>
        </table>                              
      </td>
    </tr>
  </table>
  <br>
  <!-- footer -->
  <table>
    <tr>
      <td>
        Apabila hal-hal tersebut diatas telah di setujui, mohon dapat menandatangani surat LOO ini dan dikembalikan kepada kami yang merupakan tanda konfirmasi sewa yang selanjutnya menjadi dasar penyusunan LOI. Kami menunggu konfirmasi dari Bapak/Ibu paling lambat hari Kamis tanggal 02 Mei 2024                                  
      </td>
    </tr>
    <tr><td><br></td></tr>
    <tr>
      <td>
        Demikian penawaran ini kami sampaikan, apabila ada hal yang perlu kami jelaskan lebih lanjut, mohon berkenan menghubungi kami di 0896-0100-1997 (BapakTriadi). Atas perhatiannya diucapkan terima kasih.                                  
      </td>
    </tr>
  </table>
  <br>
  <br>
  <table style="width:100%">
    <tr>
      <td style="width:30%;text-align: center;">
        Hormat kami,          
      </td>
      <td style="width:40%"></td>
      <td style="width:30%;text-align: center;">
        Menyetujui,           
      </td>
    </tr>
    <tr>
      <td style="width:30%;text-align: center;">
        <b>PT Indonesia Ferry Properti</b>                   
      </td>
      <td style="width:40%"></td>
      <td style="width:30%;text-align: center;">
        <b><?=$reqCustomerTempat?></b>
      </td>
    </tr>
    <tr>
      <td><br></td>
    </tr>
    <tr>
      <td><br></td>
    </tr>
    <tr>
      <td><br></td>
    </tr>
    <tr>
      <td><br></td>
    </tr>
    <tr>
      <td style="width:30%;text-align: center;">
        <u><b>Fajar Saiful Bahri</b></u>                    
      </td>
      <td style="width:40%"></td>
      <td style="width:30%;text-align: center;">
        <u><b><?=$reqCustomerNama?></b></u>                       
      </td>
    </tr>
    <tr>
      <td style="width:30%;text-align: center;">
        Direktur          
      </td>
      <td style="width:40%"></td>
      <td style="width:30%;text-align: center;">
        Corporate Senior Manager                
      </td>
    </tr>
  </table>
  <br>
  <br>


</body>
<!-- End Maker Surat -->