<?php
include_once("functions/date.func.php");
include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("libraries/vendor/autoload.php");

$this->load->model("TrLoo");
$this->load->model("TrLooDetil");
$this->load->model("Combo");
$this->load->model("LokasiLoo");

$reqId= $this->input->get("reqId");

if(empty($reqId)) $reqId= -1;

$statement= " AND A.TR_LOO_ID = ".$reqId;
$set= new TrLoo();
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

$reqDownPayment= numberToIna($set->getField("DOWN_PAYMENT"));
$reqAngsuranDp= numberToIna($set->getField("ANGSURAN_SISA_DIKURANG_DP"));
$reqAngsuranBulanan= numberToIna($set->getField("ANGSURAN_SEWA_BULANAN"));
$reqBayarSCBulanan= numberToIna($set->getField("BAYAR_SC_BULANAN"));

$arrperhitungansewa= [];
$statement= " AND A.TR_LOO_ID = ".$reqId;
$set= new TrLoo();
$set->selectperhitungantabel(array(), -1,-1, $statement);
// echo $set->query;exit;
while($set->nextRow())
{
  $arrdata= [];
  $arrdata["NAMA_ANGSURAN"]= $set->getField("NAMA_ANGSURAN");
  $arrdata["VBULAN"]= $set->getField("VBULAN");
  $arrdata["SEWA_INC_PPN"]= numberToIna($set->getField("SEWA_INC_PPN"));
  $arrdata["TOTAL_SEWA"]= numberToIna($set->getField("TOTAL_SEWA"));

  $vdetil= $set->getField("SERVICE_CHARGE");
  if($vdetil !== "TBA") $vdetil= numberToIna($vdetil);
  $arrdata["SERVICE_CHARGE"]= $vdetil;

  $vdetil= $set->getField("SERVICE_CHARGE_INC_PPN");
  if($vdetil !== "TBA") $vdetil= numberToIna($vdetil);
  $arrdata["SERVICE_CHARGE_INC_PPN"]= $vdetil;
  array_push($arrperhitungansewa, $arrdata);
}
// print_r($arrperhitungansewa);exit;
?>
<base href="<?=base_url();?>">
<link href="css/gaya-surat.css" rel="stylesheet" type="text/css">
<link href="lib/froala_editor_2.9.8/css/froala_style.css" rel="stylesheet" type="text/css">
<style>
  body{
  }

  tr.group, td.group{
    background-color:#F8F7F7
  }

  tr.border, td.border{
    border: 1px solid black;
    padding: 5px;
  }

  td.cntr{
    text-align:center;
  }

  td.rgt{
    text-align:right;
    padding-right: 15px;
  }
</style>
<body>
  <table style="width: 100%;" border="0">
    <tr>
      <td>
        <img src="<?='images/logo.png'?>" height="100px">
      </td>
    </tr>
    <tr>
      <td colspan="3" class="cntr"><b>LAMPIRAN II</b></td>
    </tr>
    <tr>
      <td colspan="3" class="cntr"><b>KALKULASI HARGA SEWA</b></td>
    </tr>
    <tr>
      <td colspan="3" class="cntr"><b>KOMERSIAL SSM/AAM/MLB</b></td>
    </tr>
    <tr>
      <td colspan="3"><br/></td>
    </tr>
    <tr>
      <td colspan="3" class="group cntr">P R O F I L   P E N Y E W A</td>
    </tr>
    <tr>
      <td style="width: 10%">Unit</td>
      <td colspan="2"><?=$reqInfoDetilNama?></td>
    </tr>
    <tr>
      <td>Nama Penyewa</td>
      <td colspan="2"><?=$reqNamaPenyewa?></td>
    </tr>
    <tr>
      <td>Nama Toko</td>
      <td colspan="2"><?=$reqNamaToko?></td>
    </tr>
    <tr>
      <td>Line Business</td>
      <td colspan="2"><?=$reqLineBusines?></td>
    </tr>
    <tr>
      <td colspan="3" class="group cntr">S T A T U S   P E N Y E W A</td>
    </tr>
    <tr>
      <td>Status Dokumen</td>
      <td colspan="2"><?=$req?></td>
    </tr>
    <tr>
      <td colspan="3" class="group cntr">T A R I F   K O M E R S I A L</td>
    </tr>
    <tr>
      <td style="width: 20%">Pola Bisnis</td>
      <td style="width: 12%"><?=$reqPolaBisnis?></td>
      <td></td>
    </tr>
    <tr>
      <td>Masa Kerja Sama</td>
      <td class="rgt"><?=$reqMasaKerjaSama?></td>
      <td>bulan</td>
    </tr>
    <tr>
      <td>Luas Area</td>
      <td class="rgt"><?=$reqLuasArea?></td>
      <td>m2</td>
    </tr>
    <tr>
      <td>Harga Sewa Unit</td>
      <td class="rgt"><?=$reqHargaSewaUnit?></td>
      <td>Rp/m2/bulan (Excl. PPn)</td>
    </tr>
    <tr>
      <td>Total Sewa Perbulan</td>
      <td class="rgt"><?=$reqHargaSewaPerBulan?></td>
      <td>Rp (Excl. PPn)</td>
    </tr>
    <tr>
      <td>Total Sewa <?=$reqTahunSewa?> Tahun</td>
      <td class="rgt"><?=$reqHargaSewaExPpn?></td>
      <td>Rp. (Ex. PPn)</td>
    </tr>
    <tr>
      <td><b>Total Sewa <?=$reqTahunSewa?> Tahun</b></td>
      <td class="rgt"><?=$reqHargaSewaIncPpn?></td>
      <td>Rp. (Inc. PPn)</td>
    </tr>
    <tr>
      <td>Fit-Out Charge</td>
      <td class="rgt"><?=$reqFittingOut?></td>
      <td>Rp</td>
    </tr>
    <tr>
      <td>Service Charge</td>
      <td class="rgt"><?=$reqServiceCharge?></td>
      <td>Rp/m2/bulan (Excl. PPn)</td>
    </tr>
    <tr>
      <td><b>Security Deposit</b></td>
      <td class="rgt"><?=$reqSecurityDeposit?></td>
      <td>(base rental 3 bulan + S/C 3 bulan)</td>
    </tr>
    <tr>
      <td>Marketing Levy</td>
      <td class="rgt"><?=$req?></td>
      <td>Rp/bulan</td>
    </tr>
    <tr>
      <td>DP</td>
      <td class="rgt"><?=$reqDp?></td>
      <td>%</td>
    </tr>
    <tr>
      <td>TOP</td>
      <td class="rgt"><?=$reqTop?></td>
      <td>bulan</td>
    </tr>
    <tr>
      <td colspan="3" class="group cntr">C A R A   P E M B A Y A R A N</td>
    </tr>
    <tr>
      <td>Down Payment</td>
      <td class="rgt"><?=$reqDownPayment?></td>
      <td>Rp. (Inc. PPn)</td>
    </tr>
    <tr>
      <td>Angsuran (sisa dikurang DP)</td>
      <td class="rgt"><?=$reqAngsuranDp?></td>
      <td>Rp. (Inc. PPn)</td>
    </tr>
    <tr>
      <td>Angsuran Sewa bulanan</td>
      <td class="rgt"><?=$reqAngsuranBulanan?></td>
      <td>Rp. (Inc. PPn)</td>
    </tr>
    <tr>
      <td>Amortisasi DP</td>
      <td class="rgt"><?=$reqMasaKerjaSama?></td>
      <td>Rp/bulan</td>
    </tr>
    <tr>
      <td>Bayar S/C bulanan</td>
      <td class="rgt"><?=$reqBayarSCBulanan?></td>
      <td>Rp/bulan (Exc. PPn)</td>
    </tr>
    <tr>
      <td colspan="3" class="group cntr">S I M U L A S I   (Exc. PPn)</td>
    </tr>
    <tr>
      <td>Uraian</td>
      <td>Bulan</td>
      <td></td>
    </tr>
  </table>

  <div style="page-break-after: always;"></div>

  <table style="width: 100%;" border="0">
    <tr>
      <td>
        <img src="<?='images/logo.png'?>" height="100px">
      </td>
    </tr>
    <tr>
      <td colspan="6" class="cntr"><b>TABEL PERHITUNGAN SEWA</b></td>
    </tr>
    <tr class="group">
      <td style="width: 20%" class="border cntr">ANGSURAN</td>
      <td style="width: 5%" class="border cntr">BULAN</td>
      <td style="width: 15%" class="border cntr">SEWA Inc. PPN</td>
      <td class="border cntr">TOTAL SEWA</td>
      <td style="width: 15%" class="border cntr">SERVICE CHARGE</td>
      <td style="width: 15%" class="border cntr">SERVICE CHARGE Inc. PPN</td>
    </tr>
    <?
    foreach ($arrperhitungansewa as $k => $v)
    {
    ?>
    <tr class="border">
      <td class="border"><?=$v["NAMA_ANGSURAN"]?></td>
      <td class="border cntr"><?=$v["VBULAN"]?></td>
      <td class="border rgt"><?=$v["SEWA_INC_PPN"]?></td>
      <td class="border rgt"><?=$v["TOTAL_SEWA"]?></td>
      <td class="border rgt"><?=$v["SERVICE_CHARGE"]?></td>
      <td class="border rgt"><?=$v["SERVICE_CHARGE_INC_PPN"]?></td>
    </tr>
    <?
    }
    ?>
  </table>

</body>