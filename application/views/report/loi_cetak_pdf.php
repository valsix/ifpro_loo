<?php
/* INCLUDE FILE */
include_once("functions/date.func.php");
include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("libraries/vendor/autoload.php");

$this->load->model("TrLoi");
$this->load->model("TrLoiDetil");
$this->load->model("LokasiLoo");

$reqId= $this->input->get("reqId");
$ttd= $this->input->get("ttd");

if(empty($reqId)) $reqId= -1;

$statement= " AND A.TR_LOI_ID = ".$reqId;
$set= new TrLoi();
$set->selectcetak(array(), -1,-1, $statement);
$set->firstRow();
$reqLokasiNama= $set->getField("LOKASI_NAMA");
$reqCustomerTempat= $set->getField("TEMPAT");
$reqCustomerNama= $set->getField("NAMA_PEMILIK");
$reqCustomerPenandatanganNama= $set->getField("PIC_PENANDATANGAN");
$reqCustomerPenandatanganJabatan= $set->getField("JABATAN_PENANDATANGAN");
$reqCustomerTlp= $set->getField("TELP");
$reqCustomerNpwp= $set->getField("NPWP");
$reqCustomerNpwpAlamat= $set->getField("NPWP_ALAMAT");
$reqCustomerNomorNior= $set->getField("NOMOR_NIOR");
$reqCustomerAlamatDomisili= $set->getField("ALAMAT_DOMISILI");
$reqTanggalAwal= dateToPageCheck($set->getField("INFO_TANGGAL_AWAL"));
$reqTanggalAkhir= dateToPageCheck($set->getField("INFO_TANGGAL_AKHIR"));
$reqSewaBiayaPerBulanUnit= $set->getField("SEWA_BIAYA_PER_BULAN_UNIT");
$reqTotalBiayaPerBulanPpn= $set->getField("TOTAL_BIAYA_PER_BULAN_PPN");
$reqSatuTahunTotalBiayaPerBulanPpn= 12 * $reqTotalBiayaPerBulanPpn;
$reqPromotionLevy= $set->getField("PROMOTION_LEVY");
$reqAwalSecurityDeposit= $set->getField("SECURITY_DEPOSIT");
$reqAwalFittingOut= $set->getField("FITTING_OUT");
$reqTotalLuasIndoor= $set->getField("TOTAL_LUAS_INDOOR");
$reqTotalLuasOutdoor= $set->getField("TOTAL_LUAS_OUTDOOR");
$reqTotalLuas= $set->getField("TOTAL_LUAS");
$reqPenandaTanganNama= $set->getField("USER_PENGIRIM_NAMA");
$reqPenandaTanganJabatan= $set->getField("USER_PENGIRIM_JABATAN");

$reqNomorSurat= $set->getField("NOMOR_SURAT");
$reqTanggalSurat= $set->getField("INFO_APPROVAL_QR_DATE");
if(empty($reqNomorSurat))
{
  $reqNomorSurat= $set->getField("INFO_NOMOR_SURAT");
  $reqTanggalSurat= $set->getField("INFO_LAST_CREATE_DATE");
}
$reqTanggalSurat= datetimeToPage($reqTanggalSurat, "date");

$vttd= "";
if($ttd == 2)
{
  $vttd= "uploadsloi/".$reqId."/".$set->getField("TTD_KODE").".png";
}
// echo $vttd;exit;

$arrlokasi= [];
$statement= " AND A.TR_LOI_ID = ".$reqId." AND VMODE ILIKE '%luas_sewa%'";
$set= new TrLoiDetil();
$set->selectlokasi(array(), -1,-1, $statement);
while($set->nextRow())
{
    $valid= $set->getField("VID");
    $valmode= $set->getField("VMODE");

    $arrdata= [];
    $arrdata["key"]= $valid."-".$valmode;
    $arrdata["rowdetilid"]= $set->getField("TR_LOI_DETIL_ID");;
    $arrdata["rowid"]= $set->getField("TR_LOI_ID");
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
$statement= " AND A.TR_LOI_ID = ".$reqId;
$set= new TrLoiDetil();
$set->selectByParams(array(), -1,-1, $statement);
while($set->nextRow())
{
    $valid= $set->getField("VID");
    $valmode= $set->getField("VMODE");
    $arrdata= [];
    $arrdata["keyrowdetil"]= $valid."-".$valmode;
    $arrdata["rowdetilid"]= $set->getField("TR_LOI_DETIL_ID");;
    $arrdata["rowid"]= $set->getField("TR_LOI_ID");
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

$statement= " AND A.TR_LOI_ID = ".$reqId;
$set= new TrLoi();
$set->selectlampirandua(array(), -1,-1, $statement);
$set->firstRow();
$reqLokasiLooId= $set->getField("LOKASI_LOO_ID");
$reqInfoDetilNama= $set->getField("INFO_DETIL_NAMA");
$reqNamaPenyewa= $set->getField("NAMA_PENYEWA");
$reqNamaToko= $set->getField("NAMA_TOKO");
$reqLineBusines= $set->getField("LINE_BUSINES");
$reqPolaBisnis= "Sewa";
$reqTahunSewa= $set->getField("TAHUN_SEWA");
$reqMasaKerjaSama= $set->getField("MASA_KERJA_SAMA");
$reqLuasArea= $set->getField("LUAS_AREA");
$reqHargaSewaUnit= numberToIna($set->getField("HARGA_SEWA_UNIT"));
$reqHargaSewaPerBulan= numberToIna($set->getField("TOTAL_SEWA_PERBULAN"));
$reqHargaSewaExPpn= numberToIna($set->getField("TOTAL_SEWA_TAHUN_EX_PPN"));
$reqHargaSewaIncPpn= numberToIna($set->getField("TOTAL_SEWA_TAHUN_INC_PPN"));
$reqFittingOut= numberToIna($set->getField("FITTING_OUT"));
$reqServiceCharge= numberToIna($set->getField("SERVICE_CHARGE"));
$reqSecurityDeposit= numberToIna($set->getField("SECURITY_DEPOSIT"));
$reqDp= $set->getField("DP");
$reqTop= $set->getField("TOP");
$reqAmortasiDp= 0;

$reqDownPayment= numberToIna(round($set->getField("DOWN_PAYMENT")));
$reqAngsuranDp= numberToIna(round($set->getField("ANGSURAN_SISA_DIKURANG_DP")));
$reqAngsuranBulanan= numberToIna($set->getField("ANGSURAN_SEWA_BULANAN"));
$reqBayarSCBulanan= numberToIna($set->getField("BAYAR_SC_BULANAN"));

$arrperhitungansewa= [];
$statement= " AND A.TR_LOI_ID = ".$reqId;
$set= new TrLoi();
$set->selectperhitungantabel(array(), -1,-1, $statement);
// echo $set->query;exit;
while($set->nextRow())
{
  $arrdata= [];
  $arrdata["NAMA_ANGSURAN"]= $set->getField("NAMA_ANGSURAN");
  $arrdata["VBULAN"]= $set->getField("VBULAN");
  $arrdata["SEWA_INC_PPN"]= numberToIna(round($set->getField("SEWA_INC_PPN")));
  $arrdata["TOTAL_SEWA"]= numberToIna(round($set->getField("TOTAL_SEWA")));

  $vdetil= $set->getField("SERVICE_CHARGE");
  if($vdetil !== "TBA") $vdetil= numberToIna($vdetil);
  $arrdata["SERVICE_CHARGE"]= $vdetil;

  $vdetil= $set->getField("SERVICE_CHARGE_INC_PPN");
  if($vdetil !== "TBA") $vdetil= numberToIna($vdetil);
  $arrdata["SERVICE_CHARGE_INC_PPN"]= $vdetil;
  array_push($arrperhitungansewa, $arrdata);
}
// print_r($arrperhitungansewa);exit;

if(empty($reqLokasiLooId)) $reqLokasiLooId= -1;

$set= new LokasiLoo();
$set->selectByParams(array("A.LOKASI_LOO_ID" => $reqLokasiLooId));
$set->firstRow();
$reqEmail= $set->getField("EMAIL");
$reqTelepon= $set->getField("TELEPON");
$reqNamaPj= $set->getField("NAMA_PJ");
$reqNamaBank= $set->getField("NAMA_BANK");
$reqRekeningBank= $set->getField("REKENING_BANK");
$reqAtasNamaBank= $set->getField("ATAS_NAMA_BANK");
$reqNamaCabang= $set->getField("NAMA_CABANG");
?>
<base href="<?=base_url();?>">
<link href="css/gaya-surat.css" rel="stylesheet" type="text/css">
<link href="lib/froala_editor_2.9.8/css/froala_style.css" rel="stylesheet" type="text/css">
<style>
  body{
/*      background-image:url('<?= base_url() ?>images/bg_cetak.jpg')  ;
      background-image-resize:6;
      background-size: cover;*/
  }
  td{
    padding-right: 5px;
    padding-left: 5px;
  }

  tr.border, td.border{
    border: solid black 0.5px;
  }
</style>
<body>
  <!-- head -->
  <table style="width: 100%;">
    <tr>
      <td colspan="7">
        <img src="<?=base_url().'images/logo.png'?>" height="100px">
      </td>
    </tr>
    <tr>
      <td style="text-align:center;" colspan="7">
        <h1><b><u>LETTER OF INTENT</u></b></h1>
      </td>
    </tr>
    <tr>
      <td style="width:10%">NO</td>
      <td style="width:1%">:</td>
      <td style="width:19%"><?=$reqNomorSurat?></td>
      <td style="width:45%"></td>
      <td style="width:10%">Lampiran  </td>
      <td style="width:1%">:</td>
      <td style="width:14%;text-align: right;">2 (dua) Halaman</td>
    </tr>
    <tr>
      <td>TANGGAL</td>
      <td>:</td>
      <td><?=$reqTanggalSurat?></td>
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
      <td colspan="3">.....................</td>
    </tr>
    <tr>
      <td colspan="3">JABATAN</td>
    </tr>
    <tr>
      <td colspan="3"><?=$reqCustomerTempat?></td>
    </tr>
    <tr>
      <td colspan="3">di Tempat</td>
    </tr>
  </table>
  <br>
  <!-- body -->
  <table style="width: 100%;" border="0">
    <tr>
      <td colspan="5">Salam hangat dari PT Indonesia Ferry Properti.</td>
    </tr>
    <tr>
      <td colspan="5">Bersama ini kami sampaikan Letter of Intent untuk lokasi sewa di Area Komersial <?=$reqLokasiNama?> dengan syarat dan kondisi sebagai berikut:                                 </td>
    </tr>
    <tr>
      <td><br></td>
    </tr>
    <tr>
      <td style="width:3%"><b>1</b></td>
      <td colspan="5" ><b>PENYEWA</b></td>
    </tr>
    <tr>
      <td></td>
      <td style="width:5%">1.1</td>
      <td style="width:15%">Nama</td>
      <td style="width:3%">:</td>
      <td><?=$reqCustomerNama?></td>
    </tr>
    <tr>
      <td></td>
      <td style="width:3%">1.2</td>
      <td style="width:15%">Nomor NPWP</td>
      <td style="width:3%">:</td>
      <td><?=$reqCustomerNomorNior?></td>
    </tr>
    <tr>
      <td></td>
      <td style="width:3%">1.3</td>
      <td style="width:15%">Alamat NPWP</td>
      <td style="width:3%">:</td>
      <td><?=$reqCustomerNpwpAlamat?></td>
    </tr>
    <tr>
      <td></td>
      <td style="width:3%">1.4</td>
      <td style="width:15%">Nomor NIORA</td>
      <td style="width:3%">:</td>
      <td><?=$reqCustomerNomorNior?></td>
    </tr>
    <tr>
      <td></td>
      <td style="width:3%">1.5</td>
      <td style="width:15%">Alamat Domisili</td>
      <td style="width:3%">:</td>
      <td><?=$reqCustomerAlamatDomisili?></td>
    </tr>
    <tr>
      <td></td>
      <td style="width:3%">1.6</td>
      <td style="width:15%">Telepon</td>
      <td style="width:3%">:</td>
      <td><?=$reqCustomerTlp?></td>
    </tr>
    <tr>
      <td><br></td>
    </tr>
    <tr>
      <td style="width:3%"><b>2</b></td>
      <td colspan="5" ><b>PERINCIAN UNIT</b></td>
    </tr>

    <?
    $nomorhead= 2;
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
        <td colspan="4">Indoor</td>
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
          <td style="width:5%"><?=$vnomordetil.".".$nomorsubheaddetil." ".$vlantai." (".$vkode.")"?></td>
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
        <td colspan="4">Outdor</td>
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
          <td style="width:5%"><?=$vnomordetil.".".$nomorsubheaddetil." ".$vlantai." (".$vkode.")"?></td>
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
      <td>Total Luas Sewa</td>
      <td style="width:5%">:</td>
      <td style="width:10%"><?=numberToIna($reqTotalLuas)?></td>
      <td style="width:10%">m2</td>
    </tr>

    <?
    // $nomorsubhead= 0;
    // $nomorsubheaddetil= 0;

    $nomorsubhead+=1;
    $vnomordetil= $nomorhead.".".$nomorsubhead;
    ?>
    <tr>
      <td></td>
      <td style="width:5%"><?=$vnomordetil?></td>
      <td colspan="4">Unit</td>
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
        <td style="width:5%"><?=$vnomordetil.".".$nomorsubheaddetil?> Indoor <?=$vkode." ".$vlantai?></td>
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
        <td colspan="2"></td>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Discount</td>
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
        <td colspan="2"></td>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tarif (after discount)</td>
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
        <td style="width:5%"><?=$vnomordetil.".".$nomorsubheaddetil?> Outdoor <?=$vkode." ".$vlantai?></td>
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
        <td colspan="2"></td>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Discount</td>
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
        <td colspan="2"></td>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tarif (after discount)</td>
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
      <td colspan="4">Service Charge</td>
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
        <td style="width:5%"><?=$vnomordetil.".".$nomorsubheaddetil?> Indoor <?=$vkode." ".$vlantai?></td>
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
        <td colspan="2"></td>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Discount</td>
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
        <td colspan="2"></td>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tarif (after discount)</td>
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
        <td style="width:5%"><?=$vnomordetil.".".$nomorsubheaddetil?> Outdoor <?=$vkode." ".$vlantai?></td>
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
        <td colspan="2"></td>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Discount</td>
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
        <td colspan="2"></td>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tarif (after discount)</td>
        <td style="width:5%">:</td>
        <td style="width:10%"> <?=$vnilai?></td>
        <td style="width:5%">Rp / m2</td>
      </tr>
    <?
      }
    }
    ?>

    <tr>
      <td><br></td>
    </tr>
    <tr>
      <td style="width:3%"><b>3</b></td>
      <td colspan="5" ><b>JANGKA WAKTU</b></td>
    </tr>
    <tr>
      <td></td>
      <td style="width:3%">3.1</td>
      <td style="width:15%">Jangka Waktu</td>
      <td style="width:3%">:</td>
      <td><?=$reqMasaKerjaSama?></td>
    </tr>
    <tr>
      <td></td>
      <td style="width:3%">3.2</td>
      <td style="width:15%">Tanggal Awal</td>
      <td style="width:3%">:</td>
      <td><?=$reqTanggalAwal?></td>
    </tr>
    <tr>
      <td></td>
      <td style="width:3%">3.3</td>
      <td style="width:15%">Tanggal Akhir</td>
      <td style="width:3%">:</td>
      <td><?=$reqTanggalAkhir?></td>
    </tr>
    <tr>
      <td><br></td>
    </tr>
    <tr>
      <td style="width:3%"><b>4</b></td>
      <td colspan="5" ><b>HARGA SEWA</b></td>
    </tr>
    <tr>
      <td></td>
      <td style="width:3%">4.1</td>
      <td style="width:15%">Harga Sewa</td>
      <td style="width:3%">:</td>
      <td>
        <table>
          <tr>
            <td style="width:3%">Rp</td>
            <td style="width:20%"><?=numberToIna($reqSewaBiayaPerBulanUnit)?></td>
            <td style="width:15%">/ m2 / bulan</td>
            <td style="width:15%" >(excl. PPN)</td>
            <td style="width:57%" ></td>
          </tr>
          <tr>
            <td colspan="2">Total harga sewa</td>
            <td ><?=$reqMasaKerjaSama?> bulan</td>
          </tr>
          <tr>
            <td colspan="2">Sebesar</td>
            <td >Rp <?=$reqHargaSewaIncPpn?></td>
            <td >(inc. PPN)</td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td></td>
      <td style="width:3%">4.2</td>
      <td style="width:15%">Service Charge</td>
      <td style="width:3%">:</td>
      <td>
        <table>
          <tr>
            <td style="width:3%">Rp</td>
            <td style="width:20%"><?=numberToIna($reqTotalBiayaPerBulanPpn)?></td>
            <td style="width:15%">/ m2 / bulan</td>
            <td style="width:15%" >(excl. PPN)</td>
            <td style="width:57%" ></td>
          </tr>
          <tr>
            <td colspan="5">Total service charge 1 (satu) tahun pertama yaitu sebesar</td>
          </tr>
          <tr>
            <td colspan="2">Rp <?=numberToIna($reqSatuTahunTotalBiayaPerBulanPpn)?></td>
            <td ></td>
            <td >(inc. PPN)</td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td><br></td>
    </tr>
    <tr>
      <td style="width:3%"><b>5</b></td>
      <td colspan="5" ><b>PROMOTION LEVY</b></td>
    </tr>
    <tr>
      <td></td>
      <td style="width:3%">5.1</td>
      <td colspan="3">
        <table style="width:100%">
          <tr>
            <td style="width:45%">Biaya yang dikenakan kepada pihak Penyewa sebesar </td>      
            <td style="width:3%">Rp</td>      
            <td style="width:20%"><?=numberToIna($reqPromotionLevy)?></td>
            <td style="width:10%">/ m2 / bulan</td>
            <td></td>      
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td style="width:3%"><b>6</b></td>
      <td colspan="5" ><b>FASILITAS PENERANGAN, GAS DAN AIR</b></td>
    </tr>
    <tr>
      <td></td>
      <td style="width:3%">6.1</td>
      <td colspan="3">
        Penerangan untuk unit sewa menggunakan fasilitas listrik yang disediakan oleh Pemilik dengan daya listrik sebesar 180 VA/m2 yang wajib dibayarkan setiap bulannya sesuai pemakaian*) dan biaya rekening minimum sebesar (belum termasuk PPN). Apabila Penyewa memerlukan daya listrik melebihi dari yang ditentukan, maka Penyewa wajib membayar kelebihan daya listrik tersebut sesuai dengan ketentuan dan standar biaya yang telah ditetapkan oleh Pemilik.<br>
        *) Biaya listrik sewaktu waktu dapat berubah sesuai dengan ketentuan yang ditetapkan oleh Pemilik.      
      </td>
    </tr>
    <tr>
      <td></td>
      <td style="width:3%">6.2</td>
      <td colspan="3">
        Pemilik menyediakan jaringan bahan bakar gas dan menjamin ketersediaan BBG (Bahan Bakar Gas) setiap hari. Penyewa wajib membayarkan tagihan setiap bulannya sesuai pemakaian*) dan biaya rekening minimum (belum termasuk PPN 11%).<br>
        *) Biaya BBG sewaktu waktu dapat berubah sesuai dengan ketentuan yang ditetapkan oleh Pemilik.
      </td>
    </tr>
    <tr>
      <td></td>
      <td style="width:3%">6.3</td>
      <td colspan="3">
        Pemilik menyediakan jaringan saluran air bersih dan menjamin ketersediaan air bersih setiap hari. Penyewa wajib membayarkan tagihan setiap bulannya sesuai pemakaian*) dan biaya rekening minimum (belum termasuk PPN 11%).<br>
        *) Biaya air bersih sewaktu waktu dapat berubah sesuai dengan ketentuan yang ditetapkan oleh Pemilik
      </td>
    </tr>
    <tr>
      <td><br></td>
    </tr>
    <tr>
      <td style="width:3%"><b>7</b></td>
      <td colspan="5" ><b>FASILITAS LINE TELEPON DAN INTERNET</b></td>
    </tr>
    <tr>
      <td></td>
      <td colspan="5">
        Pemilik tidak menyediakan line telepon namun dikenakan biaya pemasangan Rp 1,500,000.00/line telepon, dimana setiap line telepon akan dikenakan biaya deposit sebesar Rp 3,000,000.00/line telepon, yang harus dibayar oleh Penyewa.Biaya aktivasi untuk internet tanpa IP dedicated dikenakan biaya sebesar Rp 3,500,000.00 dan untuk biaya aktivasi yang membutuhkan IP dedicated dikenakan biaya sebesar Rp 4,200,000.00. Sedangkan untuk biaya berlangganan 10Mbps: Rp 1,600,000.00 per bulan ditambah dengan biaya pemeliharaan sesuai dengan tariff manajemen yang berlaku.
      </td>
    </tr>
    <tr>
      <td><br></td>
    </tr>
    <tr>
      <td style="width:3%"><b>8</b></td>
      <td colspan="5" ><b>DOWN PAYMENT</b></td>
    </tr>
    <tr>
      <td></td>
      <td colspan="5">
        <b>Rp <?=numberToIna($reqDownPayment)?></b>
      </td>
    </tr>
    <tr>
      <td><br></td>
    </tr>
    <tr>
      <td style="width:3%"><b>9</b></td>
      <td colspan="5" ><b>SECURITY DEPOSIT</b></td>
    </tr>
    <tr>
      <td></td>
      <td colspan="5">
        <b>Rp <?=numberToIna($reqAwalSecurityDeposit)?></b>
      </td>
    </tr>
    <tr>
      <td><br></td>
    </tr>
    <tr>
      <td></td>
      <td colspan="5" >
        Security Deposit adalah nilai dari tiga bulan harga sewa ditambah tiga bulan service charge. Uang Jaminan dibayarkan setelah penandatanganan Letter of Intent, Uang Jaminan akan Hangus/ tidak dikembalikan apabila:                                
      </td>
    </tr>
    <tr>
      <td></td>
      <td style="width:3%">9.1</td>
      <td colspan="3">
        Dalam beroperasional tidak buka lebih dari lima hari dalam satu bulan dan sudah dikirimkan Surat Peringatan 1, dan surat peringatan 2 sampai batas waktu yang ditentukan.                             
      </td>
    </tr>
    <tr>
      <td></td>
      <td style="width:3%">9.2</td>
      <td colspan="3">
        Pihak Penyewa membatalkan sewa ruang sebelum periode sewa berakhir secara sepihak.                              
      </td>
    </tr>
    <tr>
      <td><br></td>
    </tr>
    <tr>
      <td style="width:3%"><b>10</b></td>
      <td colspan="5" ><b>CARA PEMBAYARAN</b></td>
    </tr>
    <tr>
      <td></td>
      <td style="width:3%">10.1</td>
      <td colspan="3">
        Downpayment dan Security Deposit dibayarkan paling lambat 7 (tujuh) hari setelah penandatanganan Letter of Intent (LOI)                             
      </td>
    </tr>
    <tr>
      <td></td>
      <td style="width:3%">10.2</td>
      <td colspan="3">
        Pembayaran Down Payment dan Security Deposit wajib dibayarkan apabila pembayaran belum kami terima sampai dengan tanggal tersebut diatas maka surat LOI ini kami anggap batal dan pemilik dapat menawarkan lokasi dan tempat tersebut kepada pihak lain.                              
      </td>
    </tr>
    <tr>
      <td></td>
      <td style="width:3%">10.3</td>
      <td colspan="3">
        Uang sewa dan Service Charge dibayarkan pada saat awal sewa adapun biaya-biaya lain dibayar setiap tanggal 10 setiap bulan, apabila lewat tanggal tersebut maka dikenakan denda sebesar 0.1% per hari dan dibulatkan menjadi 5% per bulan apabila denda sudah melewati lebih dari 30 hari. Dari jumlah tertunggak untuk setiap hari keterlambatan terhitung sejak pembayaran tersebut harus dibayarkan sampai seluruh pembayaran yang tertunggak dilunasi.                              
      </td>
    </tr>
    <tr>
      <td></td>
      <td style="width:3%">10.4</td>
      <td colspan="3">
        Semua pembayaran dilakukan dengan transfer dalam mata uang Rupiah kerekening  berikut:
        <table width="100%">
          <tr>
            <td width="20%">BANK</td>
            <td width="3%">:</td>
            <?=$reqNamaBank?>
          </tr>
          <tr>
            <td>NOMOR REKENING</td>
            <td>:</td>
            <?=$reqRekeningBank?>
          </tr>
          <tr>
            <td>ATAS NAMA</td>
            <td>:</td>
            <?=$reqAtasNamaBank?>
          </tr>
        </table>                     
      </td>
    </tr>
    <tr>
      <td></td>
      <td style="width:3%">10.5</td>
      <td colspan="3">
        Biaya Sewa dan Biaya Service Charge dikenakan PPN sesuai dengan tarif yang berlaku untuk masing-masing beban biaya. Beban PPh dari pembayaran biaya sewa, biaya jasa pelayanan serta biaya penggunaan listrik dibebankan kepada pihak Penyewa.                              
      </td>
    </tr>
    <tr>
      <td></td>
      <td colspan="5">
        Apabila telah melakukan pembayaran mohon mengirimkan bukti pembayaran ke email <?=$reqEmail?>
      </td>
    </tr>
    <tr>
      <td><br></td>
    </tr>
    <tr>
      <td style="width:3%"><b>11</b></td>
      <td colspan="5" ><b>JENIS KEGIATAN USAHA</b></td>
    </tr>
    <tr>
      <td></td>
      <td style="width:15%" colspan="2"><b>NAMA USAHA</b></td>
      <td style="width:3%"><b>:</b></td>
    </tr>
    <tr>
      <td></td>
      <td colspan="5">
        Apabila ada perubahan terhadap jenis kegiatan usaha sebagaimana tersebut diatas wajib mendapat persetujuan tertulis terlebuh dahulu dari pemilik. Seluruh biaya perijinan dan administrasi terhadap jenis kegiatan usaha tersebut merupakan tanggung jawab penyewa, dan penyewa dengan ini menjamin bahwa Mitra telah memiliki segala perijinan dan persetujuan yang diperlukan dari instansi yang berwenang untuk menjalankan kegiatan usahanya dan tidak melakukan kegiatan usaha yang dilarang dan selain dari kegiatan usaha yang disebutkan diatas.
      </td>
    </tr>
    <tr>
      <td><br></td>
    </tr>
    <tr>
      <td style="width:3%"><b>12</b></td>
      <td colspan="5" ><b>BIAYA FITTING OUT</b></td>
    </tr>
    <tr>
      <td></td>
      <td style="width:15%" colspan="2"><b>Rp 3.500.000   </b></td>
    </tr>
    <tr>
      <td></td>
      <td colspan="5">
        Biaya fitting out berdasarkan luas ruang sewa harus dibayarkan selambat-lambatnya 7 hari sejak gambar desain disetujui oleh Pihak Penyewa dan uang jaminan fit out akan dikembalikan saat renovasi berakhir. Selama periode pekerjaan fitting out, Penyewa wajib membayar pemakaian listrik dan air berdasarkan alat ukur meter yang telah dipasang oleh Pemilik dalam obye ksewa. Penyewa juga bertanggung jawab untuk menjaga kebersihan dalam dan luar ruang dari debu dan sampah, dengan membuang sampah/puing secara terus menerus yang timbul selama pekerjaan berlangsung hingga selesai.                                
      </td>
    </tr>
    <tr>
      <td><br></td>
    </tr>
    <tr>
      <td style="width:3%"><b>13</b></td>
      <td colspan="5" ><b>PERIODE FITTING OUT</b></td>
    </tr>
    <tr>
      <td></td>
      <td colspan="5">
        Pihak Penyewa mendapatkan periode Fitting out untuk merenovasi area Ruang Sewa maksimum selama 30 hari sejak tanggal Berita Acara Serah Terima Ruangan. Setiap perubahan wajib mendapat persetujuan dari pihak Pemilik/Pengelola.
        Apabila setelah 30 hari atau maksimum tanggal mulai sewa masih belum dilakukan Fitting out oleh pihak Penyewa, maka pemilik/Pengelola Gedung akan langsung menetapkan tanggal efektif buka toko.
        Penyewa wajib memberikan gambar atau dokumen lainnya yang dibutuhkan dan diminta oleh yang menyewakan sebelum tanggal mulai Fitting out. 
      </td>
    </tr>
    <tr>
      <td><br></td>
    </tr>
    <tr>
      <td style="width:3%"><b>14</b></td>
      <td colspan="5" ><b>KETENTUAN LAIN</b></td>
    </tr>
    <tr>
      <td></td>
      <td style="width:3%">14.1</td>
      <td colspan="3">
        Menandatangani dan/atau menyetujui ketentuan-ketentuan sewa dan syarat-syarat sewa yang akan diatur dalam perjanjian sewa ruang usaha area PT Indonesia Ferry Properti.                             
      </td>
    </tr>
    <tr>
      <td></td>
      <td style="width:3%">14.2</td>
      <td colspan="3">
        Penyewa tidak diperbolehkan memindahtangankan atau menyewakan kembali ruangan kepada pihak lain tanpa ada permohonan tertulis kepada Pemilik/Pengelola serta kesepakatan dengan pihak Pemilik/Pengelola Gedung.                             
      </td>
    </tr>
    <tr>
      <td></td>
      <td style="width:3%">14.3</td>
      <td colspan="3">
        Pemilik/Pengelola Gedung menetapkan jam operasional normal usaha sebagai berikut :<br>
        Senin s/d Minggu        : 10.00 - 22.00                               
      </td>
    </tr>
    <tr>
      <td></td>
      <td style="width:3%">14.4</td>
      <td colspan="3">
        Pemilik/Pengelola Gedung berhaksewaktu-waktu meninjau atau melakukan  perubahan atas jam layanan operasional Gedung berdasarkan kondisi-kondisi yang terkait keamanan, kesehatan dan Force Majeur.                              
      </td>
    </tr>
    <tr>
      <td><br></td>
    </tr>
    <tr>
      <td></td>
      <td colspan="5">
        Apabila hal-hal tersebut diatas telah di setujui, mohon dapat menandatangani Surat Konfirmasi/ LOI ini dan dikembalikan kepada kami.                                
      </td>
    </tr>
    <tr>
      <td><br></td>
    </tr>
    <tr>
      <td></td>
      <td colspan="5">
        Surat Konfirmasi/ LOI ini selambat-lambatnya sudah dapat kami terima pada hari Selasa tanggal 27 Februari 2024. Selanjutnya apabila Surat Konfirmasi Unit sewa ini belum kami terima sampai batas waktu yang telah ditetapkan, maka Surat Konfirmasi Unit sewa ini kami anggap batal dan lokasi tersebutakan kami tawarkan kepihak lain.                                                                
      </td>
    </tr>
    <tr>
      <td><br></td>
    </tr>
    <tr>
      <td></td>
      <td colspan="5">
        Demikian Surat Konfirmasi Unit Sewa Ruang ini kami sampaikan, atas kerjasamanya kami ucapkan terimakasih.                                                                
      </td>
    </tr>
  </table>
  <br>
  <!-- footer -->
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

    <?
    if(!empty($vttd))
    {
    ?>
    <tr>
      <td style="text-align: center;">
        <img src="<?=$vttd?>" height="100px">
      </td>
      <td colspan="2">
      </td>
    </tr>
    <?
    }
    else
    {
    ?>
    <tr>
      <td colspan="3"><br><br><br><br><br></td>
    </tr>
    <?
    }
    ?>

    <tr>
      <td style="width:30%;text-align: center;">
        <u><b><?=$reqPenandaTanganNama?></b></u>
      </td>
      <td style="width:40%"></td>
      <td style="width:30%;text-align: center;">
        <u><b><?=$reqCustomerPenandatanganNama?></b></u>
      </td>
    </tr>
    <tr>
      <td style="width:30%;text-align: center;"><?=$reqPenandaTanganJabatan?></td>
      <td style="width:40%"></td>
      <td style="width:30%;text-align: center;">
        <?=$reqCustomerPenandatanganJabatan?>
      </td>
    </tr>
  </table>
  <br>
  <br>


  <!-- header rincian 1 -->
  <div style="page-break-before:always;"></div>
  <table style="width: 100%;">
    <tr>
      <td colspan="7">
        <img src="<?=base_url().'images/logo.png'?>" height="100px">
      </td>
    </tr>
    <tr>
      <td style="text-align:center;" colspan="7">
        <h1><b>KALKULASI HARGA SEWA</b></h1>
      </td>
    </tr>
  </table>

  <!-- rincian 1 -->
  <table style="width:100%">
    <tr>
      <td colspan="5" style="border:solid black 0.5px;text-align: center;"><b>PROFIL PENYEWA</b></td>
    </tr>
    <tr>
      <td colspan=2 style="border:solid black 0.5px;"> Unit</td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;" colspan="2">
        <?=$reqInfoDetilNama?>
      </td>
    </tr>
    <tr>
      <td colspan=2 style="border:solid black 0.5px;"> Nama Penyewa</td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;"colspan="2">
        <?=$reqNamaPenyewa?>
      </td>
    </tr>
    <tr>
      <td colspan=2 style="border:solid black 0.5px;">Nama Toko</td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;"colspan="2">
        <?=$reqNamaToko?>
      </td>
    </tr>
    <tr>
      <td colspan=2 style="border:solid black 0.5px;">Lini Bisnis</td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;"colspan="2">
        <?=$reqLineBusines?>
      </td>
    </tr>
    <tr>
      <td colspan="5" style="border:solid black 0.5px;text-align: center;"><b>STATUS PENYEWA</b></td>
    </tr>
    <tr>
      <td colspan=2 style="border:solid black 0.5px;">Status Dokumen</td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;"colspan="2">
      </td>
    </tr>
    <tr>
      <td colspan="5" style="border:solid black 0.5px;text-align: center;"><b>TARIF KOMERSIAL</b></td>
    </tr>
    <tr>
      <td colspan=2 style="border:solid black 0.5px;">Pola Bisnis         </td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;" colspan="2"><?=$reqPolaBisnis?></td>
    </tr>
    <tr>
      <td colspan=2 style="border:solid black 0.5px;">Masa Kerja Sama                 </td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="width:20%;border:solid black 0.5px;"><?=$reqMasaKerjaSama?></td>
      <td style="width:40%;border:solid black 0.5px;">Bulan             </td>
    </tr>
    <tr>
      <td colspan=2 style="border:solid black 0.5px;">Luas Area Unit</td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;" colspan="2"><?=$reqLuasArea?></td>
    </tr>
    <tr>
      <td style="width:2%;border:solid black 0.5px;">-</td>
      <td style="width:35%;border:solid black 0.5px;">Indoor        </td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;" colspan="2"><?=$reqTotalLuasIndoor?></td>
    </tr>
    <tr>
      <td style="width:2%;border:solid black 0.5px;">-</td>
      <td style="width:35%;border:solid black 0.5px;">Outdoor       </td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;" colspan="2"><?=$reqTotalLuasOutdoor?></td>
    </tr>
    <tr>
      <td colspan=2 style="border:solid black 0.5px;">Harga Sewa          </td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;" colspan="2"></td>
    </tr>
    <tr>
      <td style="width:2%;border:solid black 0.5px;">-</td>
      <td style="width:35%;border:solid black 0.5px;">Indoor        </td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;">Rp -</td>
      <td style="border:solid black 0.5px;">/ m2 (excl. PPN)      </td>
    </tr>
    <tr>
      <td style="width:2%;border:solid black 0.5px;">-</td>
      <td style="width:35%;border:solid black 0.5px;">Outdoor       </td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;">Rp -</td>
      <td style="border:solid black 0.5px;">/ m2 (excl. PPN)      </td>
    </tr>
    <tr>
      <td colspan=2 style="border:solid black 0.5px;">Harga Sewa Unit Per Bulan          </td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;" colspan="2"></td>
    </tr>
    <tr>
      <td style="width:2%;border:solid black 0.5px;">-</td>
      <td style="width:35%;border:solid black 0.5px;">Indoor        </td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;">Rp -</td>
      <td style="border:solid black 0.5px;">excl. PPN     </td>
    </tr>
    <tr>
      <td style="width:2%;border:solid black 0.5px;">-</td>
      <td style="width:35%;border:solid black 0.5px;">Outdoor       </td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;">Rp -</td>
      <td style="border:solid black 0.5px;">excl. PPN     </td>
    </tr>
    <tr>
      <td colspan=2 style="border:solid black 0.5px;">Harga Sewa Unit Per Bulan          </td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;" colspan="2"></td>
    </tr>
    <tr>
      <td style="width:2%;border:solid black 0.5px;">-</td>
      <td style="width:35%;border:solid black 0.5px;">Indoor        </td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;">Rp -</td>
      <td style="border:solid black 0.5px;">incl. PPN     </td>
    </tr>
    <tr>
      <td style="width:2%;border:solid black 0.5px;">-</td>
      <td style="width:35%;border:solid black 0.5px;">Outdoor       </td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;">Rp -</td>
      <td style="border:solid black 0.5px;">incl. PPN     </td>
    </tr>
    <tr>
      <td colspan=2 style="border:solid black 0.5px;"><b>Total Sewa         </b></td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;" colspan="2"></td>
    </tr>
    <tr>
      <td style="width:2%;border:solid black 0.5px;">-</td>
      <td style="width:35%;border:solid black 0.5px;">Indoor        </td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;">Rp -</td>
      <td style="border:solid black 0.5px;">incl. PPN     </td>
    </tr>
    <tr>
      <td style="width:2%;border:solid black 0.5px;">-</td>
      <td style="width:35%;border:solid black 0.5px;">Outdoor       </td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;">Rp -</td>
      <td style="border:solid black 0.5px;">incl. PPN     </td>
    </tr>
    <tr>
      <td colspan=2 style="border:solid black 0.5px;">Fitting out Charge</td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;" colspan="2">Rp <?=$reqFittingOut?></td>
    </tr>
    <tr>
      <td colspan=2 style="border:solid black 0.5px;">Harga Service Charge</td>
    </tr>
    <tr>
      <td style="width:2%;border:solid black 0.5px;">-</td>
      <td style="width:35%;border:solid black 0.5px;">Indoor        </td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;">Rp -</td>
      <td style="border:solid black 0.5px;">/ m2 (excl. PPN)</td>
    </tr>
    <tr>
      <td style="width:2%;border:solid black 0.5px;">-</td>
      <td style="width:35%;border:solid black 0.5px;">Outdoor       </td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;">Rp -</td>
      <td style="border:solid black 0.5px;">/ m2 (excl. PPN)</td>
    </tr>
    <tr>
      <td colspan=2 style="border:solid black 0.5px;">Harga Service Charge Per Bulan</td>
    </tr>
    <tr>
      <td style="width:2%;border:solid black 0.5px;">-</td>
      <td style="width:35%;border:solid black 0.5px;">Indoor        </td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;">Rp -</td>
      <td style="border:solid black 0.5px;">incl. PPN     </td>
    </tr>
    <tr>
      <td style="width:2%;border:solid black 0.5px;">-</td>
      <td style="width:35%;border:solid black 0.5px;">Outdoor       </td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;">Rp -</td>
      <td style="border:solid black 0.5px;">incl. PPN     </td>
    </tr>
    <tr>
      <td colspan=2 style="border:solid black 0.5px;">Total Service Charge (1 Tahun)</td>
    </tr>
    <tr>
      <td style="width:2%;border:solid black 0.5px;">-</td>
      <td style="width:35%;border:solid black 0.5px;">Indoor        </td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;">Rp -</td>
      <td style="border:solid black 0.5px;">incl. PPN     </td>
    </tr>
    <tr>
      <td style="width:2%;border:solid black 0.5px;">-</td>
      <td style="width:35%;border:solid black 0.5px;">Outdoor       </td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;">Rp -</td>
      <td style="border:solid black 0.5px;">incl. PPN     </td>
    </tr>
    <tr>
      <td colspan=2 style="border:solid black 0.5px;">Marketing Levy</td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;" colspan="2">Rp <?=$reqPromotionLevy?></td>
    </tr>
    <tr>
      <td colspan=2 style="border:solid black 0.5px;">DP</td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;" colspan="2"><?=$reqDp?></td>
    </tr>
    <tr>
      <td colspan=2 style="border:solid black 0.5px;">TOP         </td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;" colspan="2"><?=$reqTop?></td>
    </tr>
    <tr>
      <td colspan="5" style="border:solid black 0.5px;text-align: center;"><b>Cara Pembayaran</b></td>
    </tr>
    <tr>
      <td style="border:solid black 0.5px;" colspan="2">Down Payment</td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;">Rp <?=numberToIna($reqDownPayment)?></td>
      <td style="border:solid black 0.5px;">(incl. PPN)     </td>
    </tr>
    <tr>
      <td style="border:solid black 0.5px;" colspan="2">Angsuran (sisa dikurang DP)</td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;">Rp <?=$reqAngsuranDp?></td>
      <td style="border:solid black 0.5px;">(incl. PPN)          </td>
    </tr>
    <tr>
      <td style="border:solid black 0.5px;" colspan="2">Down Payment</td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;">Rp -</td>
      <td style="border:solid black 0.5px;">(incl. PPN)          </td>
    </tr>
    <tr>
      <td style="border:solid black 0.5px;" colspan="2">Amotrisasi DP         </td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;">Rp <?=$reqAmortasiDp?></td>
      <td style="border:solid black 0.5px;">/ bulan     </td>
    </tr>
    <tr>
      <td style="border:solid black 0.5px;" colspan="2">Biaya Service Charge          t</td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;">Rp <?=$reqBayarSCBulanan?></td>
      <td style="border:solid black 0.5px;">/ bulan      </td>
    </tr>
    <tr>
      <td colspan="5" style="border:solid black 0.5px;text-align: center;"><b>SIMULASI</b></td>
    </tr>
    <tr>
      <td style="border:solid black 0.5px;" colspan="2">Uraian</td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;" colspan="2">Bulan                   </td>
    </tr>
  </table>

  <!-- header rincian 2 -->
  <div style="page-break-before:always;"></div>
  <table style="width: 100%;">
    <tr>
      <td colspan="7">
        <img src="<?=base_url().'images/logo.png'?>" height="100px">
      </td>
    </tr>
    <tr>
      <td style="text-align:center;" colspan="7">
        <h1><b>TABEL PERHITUNGAN SEWA</b></h1>
      </td>
    </tr>
  </table>

<!-- rincian 2 -->
  <table style="width:100%">
    <tr>
      <td style="border:solid black 0.5px; text-align: center;"> ANGSURAN  </td>
      <td style="border:solid black 0.5px; text-align: center;"> SEWA UNIT <br>(Incl. PPN)    </td>
      <td style="border:solid black 0.5px; text-align: center;"> TOTAL SEWA <br> (Incl. PPN)</td>
      <td style="border:solid black 0.5px; text-align: center;"> SERVICE CHARGE <br> (Incl. PPN)</td>
    </tr>
    <?
    foreach ($arrperhitungansewa as $k => $v)
    {
    ?>
    <tr class="border">
      <td class="border"><?=$v["NAMA_ANGSURAN"]?></td>
      <td class="border rgt"><?=$v["SEWA_INC_PPN"]?></td>
      <td class="border rgt"><?=$v["TOTAL_SEWA"]?></td>
      <td class="border rgt"><?=$v["SERVICE_CHARGE_INC_PPN"]?></td>
    </tr>
    <?
    }
    ?>
    <tr>
      <td style="border:solid black 0.5px; text-align: center;" colspan="2"><b> TOTAL</b> </td>
      <td style="border:solid black 0.5px;"></td>
      <td style="border:solid black 0.5px;"></td>
    </tr>

</body>
<!-- End Maker Surat -->