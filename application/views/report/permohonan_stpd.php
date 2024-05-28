<?php
/* INCLUDE FILE */
include_once("functions/date.func.php");
include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("libraries/vendor/autoload.php");
include_once("libraries/phpqrcode/qrlib.php");

$this->load->model("PermohonanStpd");
$this->load->model("Kelompok");
$this->load->model("SatuanKerja");

$reqId = $this->input->get("reqId");
$cq= $this->input->get("cq");

$set = new PermohonanStpd();
$set->selectByParamsDraft(array("A.PERMOHONAN_STPD_ID" => $reqId), -1, -1, $statement);
// echo $set->query;exit;
$set->firstRow();
$reqId = $set->getField("PERMOHONAN_STPD_ID");
$reqNomor = $set->getField("NOMOR");
$reqTanggal = $set->getField("TANGGAL");
$reqDokumenAcuan = $set->getField("DOKUMEN_ACUAN");
$reqJumlah = $set->getField("JUMLAH_PELAKSANA");
$reqLokasiDinas = $set->getField("LOKASI_DINAS");
$reqTanggalBerangkat = $set->getField("DATESTART");
$reqTanggalKembali = $set->getField("DATEEND");
// $reqTanggalBerangkat = dateTimeToPageCheck($set->getField("TANGGAL_BERANGKAT"));
// $reqTanggalKembali = dateTimeToPageCheck($set->getField("TANGGAL_KEMBALI"));
$reqTotalPeriodeHari = $set->getField("TOTAL_PERIODE_HARI");
$reqTotalPeriodeMalam = $set->getField("TOTAL_PERIODE_MALAM");
$reqTotalHari = coalesce($set->getField("TOTAL_HARI"),0);
$reqStatusSurat = $set->getField("STATUS_SURAT");
$reqNamaKetua = $set->getField("nama_ketua");
$reqJabatanKetua = $set->getField("jabatan_ketua");

$reqPemimpinId = $set->getField("PEMIMPIN_ID");
$reqPemimpinKelompok = $set->getField("PEMIMPIN_KELOMPOK");

$reqPelaksanaId = $set->getField("PELAKSANA_ID");
$reqPengajuanDisiapkanId = $set->getField("PENGAJUAN_DISIAPKAN_ID");
$reqPengajuanDisetujuiId = $set->getField("PENGAJUAN_DISETUJUI_ID");
$reqRealisasiDisiapkanId = $set->getField("REALISASI_DISIAPKAN_ID");
$reqRealisasiMengetahuiId = $set->getField("REALISASI_MENGETAHUI_ID");
$reqRealisasiDisetujuiId = $set->getField("REALISASI_DISETUJUI_ID");
$reqKetRealisasi = $set->getField("KET_REALISASI");
$reqFileRealisasi = $set->getField("FILE_REALISASI");

$reqTglSetujuKirim = $set->getField("tglsetujuKirim");
$reqTglKirim = $set->getField("tglkirim");
$reqTglSetuju = $set->getField("tglsetuju");

// echo $reqStatusSurat;exit;

$satuan_kerja= new SatuanKerja();
$satuan_kerja->selectByParamsFix(array(), -1, -1, " AND A.SATUAN_KERJA_ID='".$reqPemimpinId."'");
// echo $satuan_kerja->query;exit;
$satuan_kerja->firstRow();
$reqPemimpinJabatan= $satuan_kerja->getField("JABATAN");
$reqPemimpinNama= $satuan_kerja->getField("NAMA");
$reqPemimpinNip= $satuan_kerja->getField("NIP");
 if ($reqId == 49)
    { 
        $reqPemimpinNamaPegawai= 'Galih Saksono';
    }
    else
    {        
        $reqPemimpinNamaPegawai= $satuan_kerja->getField("NAMA_PEGAWAI");
    }
$reqPemimpinDetil= " - ".$reqPemimpinNamaPegawai." (".$reqPemimpinNip.")";
$reqPemimpinInfo= coalesce($reqPemimpinJabatan, $reqPemimpinNama).$reqPemimpinDetil;

$reqTotalRealisasi = number_format(coalesce($set->getField("TOTAL_REALISASI"),0),'0',',','.') ;
$reqSatkerAsal = $set->getField("SATUAN_KERJA_ID_ASAL");

$arrKelompokperjalanan=array();
$kelompok = new PermohonanStpd();
$statement=" AND A.PERMOHONAN_STPD_ID = ".$reqId;
$kelompok->selectByParamsBiaya(array(),-1,-1,$statement);
// echo $kelompok->query;exit;
while ($kelompok->nextRow()) 
{
    $arrData=array();
    $arrData["id"]= $kelompok->getField("PERMOHONAN_STPD_BIAYA_DINAS_ID");
    $arrData["ALOKASI_BIAYA"]= $kelompok->getField("ALOKASI_BIAYA");
    $arrData["carikunci"]= $kelompok->getField("KELOMPOK_ID");
    $arrData["PENGAJUAN_BIAYA"]= $kelompok->getField("PENGAJUAN_BIAYA");
    $arrData["REALISASI"]= $kelompok->getField("REALISASI");
    $arrData["BIAYA_AWAL"]= (float)$kelompok->getField("BIAYA_AWAL");
    array_push($arrKelompokperjalanan, $arrData);
}

if($cq == "1")
{
  print_r($arrKelompokperjalanan);exit;
}

$arrKelompok=array();
$kelompok = new Kelompok();
$statement=" and b.kelompok_id is not null  ";
$statementdetil= " AND B.PERMOHONAN_STPD_ID = ".$reqId;
$kelompok->selectByParamsStpd(array(),-1,-1, $statement, $statementdetil);
// echo $kelompok->query;exit;
while ($kelompok->nextRow()) 
{
    $arrData=array();
    $arrData["KELOMPOK_ID"]= $kelompok->getField("KELOMPOK_ID");
    $arrData["NAMA"]= $kelompok->getField("NAMA");
    $arrData["BIAYA"]= $kelompok->getField("BIAYA");
    $arrData["ALOKASI_BIAYA"]= $kelompok->getField("ALOKASI_BIAYA");
    $arrData["PENGAJUAN_BIAYA"]= $kelompok->getField("PENGAJUAN_BIAYA");
    $arrData["REALISASI"]= $kelompok->getField("REALISASI");
    $arrData["KELOMPOK_ID_STPD"]= $kelompok->getField("KELOMPOK_ID_STPD");
    $arrData["KELOMPOK_ORANG"]= $kelompok->getField("KELOMPOK_ORANG");
    array_push($arrKelompok, $arrData);
}

if($cq == "2")
{
  print_r($arrKelompok);exit;
}

$satuan_kerja = new SatuanKerja();
$satuan_kerja->selectByParams(array(), -1, -1, " AND A.SATUAN_KERJA_ID='".$reqPengajuanDisiapkanId."'", " ORDER BY KODE_SO ASC ");
// echo $satuan_kerja->query;exit;
$satuan_kerja->firstRow();
$reqPengajuanDisiapkanOleh=$satuan_kerja->getField("NAMA");
$reqPengajuanDisiapkanOlehNama=$satuan_kerja->getField("NAMA_pegawai");
unset($satuan_kerja);

$satuan_kerja = new SatuanKerja();
$satuan_kerja->selectByParams(array(), -1, -1," AND A.SATUAN_KERJA_ID='".$reqPengajuanDisetujuiId."'", " ORDER BY KODE_SO ASC ");
// echo $satuan_kerja->query;exit;
$satuan_kerja->firstRow();
$reqPengajuanDisetujuiOleh=$satuan_kerja->getField("NAMA");
$reqPengajuanDisetujuiOlehNama=$satuan_kerja->getField("NAMA_PEGAWAI");
unset($satuan_kerja);

$satuan_kerja = new SatuanKerja();
$satuan_kerja->selectByParams(array(), -1, -1, " AND A.SATUAN_KERJA_ID='".$reqRealisasiDisiapkanId."'", " ORDER BY KODE_SO ASC ");
// echo $satuan_kerja->query;exit;
$satuan_kerja->firstRow();
$reqRealisasiDisiapkanOleh=$satuan_kerja->getField("NAMA");
$reqRealisasiDisiapkanOlehNama=$satuan_kerja->getField("NAMA_PEGAWAI");
unset($satuan_kerja);

$setdetil= new PermohonanStpd();
$setdetil->selectrealisasiparaf(array("A.PERMOHONAN_STPD_ID" => $reqId), -1, -1);
// echo $setdetil->query;exit;
$setdetil->firstRow();
$reqRealisasiMengetahuiOleh= $setdetil->getField("NAMA_SATKER");
$reqRealisasiMengetahuiOlehNama=$setdetil->getField("NAMA_USer");
$reqRealisasiMengetahuiOlehNamaID=$setdetil->getField("user_id");
$reqRealisasiMengetahuiOlehNamaDate=$setdetil->getField("DateUpdate");
?>
<link href="<?= base_url() ?>css/gaya-surat.css" rel="stylesheet" type="text/css">

<body>

  <!-- header bagian 1 -->
  <table border="1" style="width: 100%;">
    <tr>
      <td rowspan="3"width="100px">
        <img src="<?=base_url().'images/logo.png'?>" height="100px">
      </td>
      <td></td>
      <td></td>
      <td colspan="3" style="text-align: center; vertical-align: middle; height: 50px;"><b><center>SURAT TUGAS PERJALANAN DINAS</center></b></td>
    </tr>
    <tr>
      <td>&ensp;Nomor</td>
      <td>&ensp;:</td>
      <td >&ensp;<?=$reqNomor?></td>
    </tr>
    <tr>
      <td>&ensp;Tanggal</td>
      <td>&ensp;:</td>
      <td >&ensp;<?=$reqTanggal?></td>
    </tr>
  </table>
  <!-- akhir header bagian 1 -->

  <!-- isi bagian 1 [dokumen acuan]-->
  <table border="1" style="width: 100%;">
    <tr>
      <td colspan="9">&ensp;</td>
    </tr>
    <tr>
      <td colspan="9"><b>&ensp;DOKUMEN ACUAN</b></td>
    </tr>
    <tr>
      <td colspan="9">&ensp;<?=$reqDokumenAcuan?></td>
    </tr>
    <tr>
      <td colspan="9">&ensp;</td>
    </tr>
  </table>
  <?
    $setinfo= new PermohonanStpd();
    $setinfo->selectByParamsUntuk(array(), -1, -1, " AND A.PERMOHONAN_STPD_ID = ".$reqId);
    $i=0;
    $pelaksana=array();
    while($setinfo->nextRow()){
      $pelaksana[$i]['NAMA']=$setinfo->getField("NAMA_pegawai");
      $pelaksana[$i]['JABATAN']=$setinfo->getField("NAMA");
      $i++;
    }
  ?>
  <!-- isi bagian 1 [pelaksana dinas]-->
  <table border="1" style="width: 100%;">
    <tr>
      <td colspan="9"><b>&ensp;PELAKSANA DINAS</b></td>
    </tr>
    <tr>
      <td rowspan="<?=count($pelaksana)+3?>" style="width: 35px;">&ensp;</td>
      <td style="width: 100px;">&ensp;Jumlah</td>
      <td style="width: 25px;">&ensp;:</td>
      <td style="width: 125px;">&ensp;<?=$reqJumlah?></td>
      <td colspan="5">&ensp;Orang</td>
    </tr>
    <tr>
      <td colspan="7" >&ensp;Data Pelaksana</td>
      <td style="width: 250px;">&ensp;Level Jabatan</td>
    </tr>
    <tr>
      <td>&ensp;Pemimpin</td>
      <td>&ensp;:</td>
      <td colspan="5">&ensp;<?=$reqPemimpinNamaPegawai?></td>
      <td >&ensp;<?=$reqJabatanKetua?></td>
    </tr>
    <?
    for($i=0;$i<count($pelaksana);$i++)
    {?>
      <tr>
        <?if($i==0){?>
          <td>&ensp;Pelaksana</td>
          <td>&ensp;:</td>
          <td colspan="5" width="40%">&ensp;<?=$pelaksana[$i]['NAMA']?></td>
          <td >&ensp;<?=$pelaksana[$i]['JABATAN']?></td>
        <?}else{?>
          <td>&ensp;</td>
          <td>&ensp;</td>
          <td colspan="5" width="40%">&ensp;<?=$pelaksana[$i]['NAMA']?></td>
          <td >&ensp;<?=$pelaksana[$i]['JABATAN']?></td>
        <?}?>
      </tr>
    <?
    }?>
    
  </table>

  <!-- isi bagian 1 [lokasi dinas]-->
  <table border="1" style="width: 100%;">
    <tr>
      <td colspan="9">&ensp;</td>
    </tr>
    <tr>
      <td colspan="9"><b>&ensp;LOKASI DINAS</b></td>
    </tr>
    <tr>
      <td colspan="9">&ensp;<?=$set->getField("LOKASI_DINAS")?></td>
    </tr>
    <tr>
      <td colspan="9">&ensp;</td>
    </tr>
  </table>

  <!-- isi bagian 1 [periode dinas]-->
  <table border="1" style="width: 100%;">
    <tr>
      <td colspan="4"><b>&ensp;PERIODE DINAS</b></td>
    </tr>
    <tr>
      <td rowspan="3" style="width: 4%;">&ensp;</td>
      <td style="width: 25%;">&ensp;Tanggal Berangkat</td>
      <td colspan="2">&ensp;<?=$set->getField("DATESTART")?></td>
    </tr>
    <tr>
      <td >&ensp;Tanggal Kembali</td>
      <td colspan="2">&ensp;<?=$set->getField("DATEEND")?></td>
    </tr>
    <tr>
      <td >&ensp;Total Periode Dinas</td>
      <td >&ensp;<?=$set->getField("TOTAL_PERIODE_HARI")?> hari</td>
      <td >&ensp;<?=$set->getField("TOTAL_PERIODE_MALAM")?> malam</td>
    </tr>
    <tr>
      <td colspan="9">&ensp;</td>
    </tr>
  </table>

  <!-- isi bagian 1 [estimasi biaya dinas]-->
  <table border="1" style="width: 100%;">
    <tr>
      <td colspan="9" ><b>&ensp;ESTIMASI BIAYA DINAS</b></td>
    </tr>
    <tr>
      <td colspan="5">&ensp;Alokasi Biaya</td>
      <td colspan="2" style="width: 25%;">&ensp;Pengajuan Biaya</td>
      <?
      $arrexcept= array("DRAFT", "REVISI", "KIRIM");
      if(!in_array($reqStatusSurat, $arrexcept))
      {
      ?>
      <td colspan="2">&ensp;Realisasi</td>
      <?
      }
      ?>
    </tr>
    <?
    $arrAlokasi = infobiayadinas();

    foreach ($arrAlokasi as $key => $value) 
    {
        $infoalokasinama= $value["nama"];
        $infocarikey= $infoalokasinama;
        $arrcheckgetinfoalokasi= [];
        $arrcheckgetinfoalokasi= in_array_column($infocarikey, "ALOKASI_BIAYA", $arrKelompokperjalanan);
        
        $reqPermohonanStpdBiayaDinasId= "";
        $vbiaya= $infovalrealisasi= 0;
        if(!empty($arrcheckgetinfoalokasi) && !empty($reqId))
        {
            $indexcheckgetinfoalokasi= $arrcheckgetinfoalokasi[0];
            $vbiaya= $arrKelompokperjalanan[$indexcheckgetinfoalokasi]["PENGAJUAN_BIAYA"];
            $vbiaya= number_format($vbiaya,0,',','.');
            $reqPermohonanStpdBiayaDinasId= $arrKelompokperjalanan[$indexcheckgetinfoalokasi]["id"];
            $infovalrealisasi= $arrKelompokperjalanan[$indexcheckgetinfoalokasi]["REALISASI"];
            $infovalrealisasi= number_format($infovalrealisasi,0,',','.');
        }
    ?>  
        <td style="width: 4%;">&ensp;-></td>
        <td colspan="4">&ensp;<?=$infoalokasinama?></td>
        <td style="width: 5%;">&ensp;IDR</td>
        <td>&ensp;<?=$vbiaya?></td>
        <?
        $arrexcept= array("DRAFT", "REVISI", "KIRIM");
        if(!in_array($reqStatusSurat, $arrexcept))
        {
        ?>
        <td style="width: 5%;">&ensp;IDR</td>
        <td>&ensp;<?=$infovalrealisasi?></td>
        <?
        }
        ?>
    </tr>
    <?
    }
    $i=1;
    foreach ($arrKelompok as $key => $value) 
    {
        $vkelompokid= $value["KELOMPOK_ID"];
        $vkelompokorang= $value["KELOMPOK_ORANG"];
        $vkelompokidstpd= $value["KELOMPOK_ID_STPD"];
        $vkelompokbiayastpd= $value["PENGAJUAN_BIAYA"];
        $vkelompokrealisasibiayastpd= $value["REALISASI"];
    ?>
      <tr class="trkelompokuangsaku<?=$vkelompokid?>">
        <?if ($i==1){?>
        <td >&ensp;-></td>
        <td style="width: 10%;">&ensp;Uang Saku</td>
        <?}else{?>
        <td colspan="2">&ensp;</td>
        <?}?>
        <td>&ensp;<?=$value["NAMA"]?></td>
        <td>&ensp;<?=$vkelompokorang?></td>
        <td>&ensp;Orang</td>
        <td >&ensp;IDR</td>
        <td >&ensp;<?=number_format(coalesce($vkelompokbiayastpd,0),'0',',','.')?></td>
        <?
        $arrexcept= array("DRAFT", "REVISI", "KIRIM");
        if(!in_array($reqStatusSurat, $arrexcept))
        {
        ?>
        <td >&ensp;IDR</td>
        <td >&ensp;<?=number_format(coalesce($vkelompokrealisasibiayastpd,0),'0',',','.')?></td>
        <?
        }
        ?>
      </tr>
    <? 
      $i++;  
    }
    ?>
    <?
    $arrexcept= array("DRAFT", "REVISI", "KIRIM");
    if(!in_array($reqStatusSurat, $arrexcept))
    {
    ?>
    <tr>
      <td colspan="7"><b>&ensp;Total Realisasi</b></td>
      <td ><b>&ensp;IDR</b></td>
      <td ><b>&ensp;<?=number_format(coalesce($set->getField("TOTAL_REALISASI"),0),'0',',','.')?></b></td>
    </tr>
    <?
    }
    ?>
    <tr>
      <td colspan="9"><b>&ensp;*Akomodasi disediakan berdasarkan ketentuan Perusahaan</b></td>
    </tr>
    <tr>
      <td colspan="9"><b>&ensp;**Cash advance dilakukan settlement terpisah dari persetujuan ini</b></td>
    </tr>
  </table>
  <!-- akhir isi bagian 1 -->
  
  <br>

  <!-- start tanda tangan -->
  <table border="1" style="width: 100%;">
    <tr>
      <td rowspan="3">&ensp;</td>
      <td colspan="2" style="text-align: center">&ensp;PENGAJUAN STPD</td>
      <td colspan="2" style="text-align: center">&ensp;LAPORAN REALISASI STPD</td>
    </tr>
    <tr>
      <td style="text-align: center">&ensp;Disiapkan Oleh</td>
      <td style="text-align: center">&ensp;Disetujui (Mgr/GM/BOD)</td>
      <td style="text-align: center">&ensp;Disiapkan Oleh</td>
      <td style="text-align: center">&ensp;Mengetahui SDM</td>
    </tr>
    <tr>
      <td style="text-align: center;height: 70px;">
        <?
        $vurlttdsppd= "uploads/sppd/".$reqId."/";
        $arrexceptdraft= array("SETUJU", "SETUJUKIRIM", "SETUJUDRAFT", "SELESAI");
        if(file_exists($vurlttdsppd."KIRIM.png"))
        {
        ?>
          <img src="<?=base_url()?>/<?=$vurlttdsppd?>KIRIM.png">
        <?
        }
        // kalau tidak ketemu maka buat file nya
        else
        {
          if(in_array($reqStatusSurat, $arrexceptdraft))
          {
            makedirs($vurlttdsppd);
            $filename = $vurlttdsppd.'KIRIM.png';
            $errorCorrectionLevel = 'L';
            $matrixPointSize = 2;
            $kodeParaf=date("dmYHis")."-".$reqPengajuanDisiapkanOlehNama;
            QRcode::png($kodeParaf, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
        ?>
            <img src="<?=base_url()?>/<?=$vurlttdsppd?>KIRIM.png">
        <?
          }
        }
        ?>
      </td>
      <td style="text-align: center">
        <?
        if(file_exists($vurlttdsppd."SETUJU.png"))
        {
        ?>
          <img src="<?=base_url()?>/<?=$vurlttdsppd?>SETUJU.png">
        <?
        }
        // kalau tidak ketemu maka buat file nya
        else
        {
          if(in_array($reqStatusSurat, $arrexceptdraft))
          {
            makedirs($vurlttdsppd);
            $filename = $vurlttdsppd.'SETUJU.png';
            $errorCorrectionLevel = 'L';
            $matrixPointSize = 2;
            $kodeParaf=date("dmYHis")."-".$reqPengajuanDisetujuiOlehNama;
            QRcode::png($kodeParaf, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
        ?>
            <img src="<?=base_url()?>/<?=$vurlttdsppd?>SETUJU.png">
        <?
          }
        }
        ?>
      </td>
      <td style="text-align: center">
        <?
        if(file_exists($vurlttdsppd."SETUJUKIRIM.png"))
        {
        ?>
          <img src="<?=base_url()?>/<?=$vurlttdsppd?>SETUJUKIRIM.png">
        <?
        }
        ?>
      </td>
      <td style="text-align: center"><?
        if(file_exists($vurlttdsppd."PARAF".$reqRealisasiMengetahuiOlehNamaID.".png"))
        {
        ?>
          <img src="<?=base_url()?>/<?=$vurlttdsppd?>PARAF<?=$reqRealisasiMengetahuiOlehNamaID?>.png">
        <?
        }
        ?>
      </td>
    </tr>
    <tr>
      <td style="text-align: center">&ensp;Nama</td>
      <td style="text-align: center">&ensp;<?=$reqPengajuanDisiapkanOlehNama?></td>
      <td style="text-align: center">&ensp;<?=$reqPengajuanDisetujuiOlehNama?></td>
      <td style="text-align: center">&ensp;<?=$reqRealisasiDisiapkanOlehNama?></td>
      <td style="text-align: center">&ensp;<?=$reqRealisasiMengetahuiOlehNama?></td>
    </tr>
    <tr>
      <td style="text-align: center">&ensp;Jabatan</td>
      <td style="text-align: center">&ensp;<?=$reqPengajuanDisiapkanOleh?></td>
      <td style="text-align: center">&ensp;<?=$reqPengajuanDisetujuiOleh?></td>
      <td style="text-align: center">&ensp;<?=$reqRealisasiDisiapkanOleh?></td>
      <td style="text-align: center">&ensp;<?=$reqRealisasiMengetahuiOleh?></td>
    </tr>
    <tr>
      <td style="text-align: center">&ensp;Tanggal</td>
      <td style="text-align: center">&ensp;<?=$reqTglKirim?></td>
      <td style="text-align: center">&ensp;<?=$reqTglSetuju?></td>
      <td style="text-align: center">&ensp;<?=$reqTglSetujuKirim?></td>
      <td style="text-align: center">&ensp;<?=$reqRealisasiMengetahuiOlehNamaDate?></td>
    </tr>
  </table>

  <?
  $arrexcept= array("DRAFT", "REVISI", "KIRIM");
  if(!in_array($reqStatusSurat, $arrexcept))
  {
  ?>
  <br>
  <br>
  <p style="page-break-before: always;"></p>

  <!-- header bagian 2 -->
  <table border="1" style="width: 100%;">
    <tr>
      <td rowspan="4" width="100px">
        <img src="<?=base_url().'images/logo.png'?>" height="100px">
      </td>
      <td></td>
      <td></td>
      <td style="text-align: center; vertical-align: middle; height: 50px;"><b><center>LAPORAN HASIL PERJALANAN DINAS</center></b></td>
    </tr>
    <tr>
      <td >&ensp;Untuk</td>
      <td >&ensp;:</td>
      <td style="border-right: none;">&ensp;Div. Keuangan, Unit Umum, Pelaksana Dinas</td>
    </tr>
    <tr>
      <td>&ensp;Nomor</td>
      <td>&ensp;:</td>
      <td >&ensp;</td>
    </tr>
    <tr>
      <td>&ensp;Tanggal</td>
      <td>&ensp;:</td>
      <td >&ensp;</td>
    </tr>
  </table>
  <!-- akhir header bagian 2 -->

  <!-- isi bagian 2 -->
  <table border="1" style="width: 100%;">
    <tr>
      <td style="width: 35px; text-align: center;">NO</td>
      <td colspan="4" style="text-align: center;">LAPORAN</td>
    </tr>
    <tr>
      <td style="text-align: center; height: 400px;">1</td>
      <td ></td>
    </tr>
    <tr>
      <td style="text-align: center; height: 400px;">2</td>
      <td ></td>
    </tr>
    <tr>
      <td style="text-align: center; height: 400px;">3</td>
      <td ></td>
    </tr>
  </table>
  <!-- akhir isi bagian 2 -->
  <?
  }
  ?>