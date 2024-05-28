<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");


$this->load->model("SuratMasuk");
$this->load->model("TandaTangan");
$this->load->model("Pegawai");
$surat_masuk = new SuratMasuk();
$tanda_tangan = new TandaTangan();
$pegawai = new Pegawai();

$reqId = $this->input->get("reqId");

if($reqId == "")
{}
else
{
  $reqMode = "ubah";
  $surat_masuk->selectByParamsSurat(array("A.SURAT_MASUK_ID" => $reqId));
  // echo $surat_masuk->query;exit;
  $surat_masuk->firstRow();

  $reqId              = $surat_masuk->getField("SURAT_MASUK_ID");
  $reqUserAtasanId    = $surat_masuk->getField("USER_ATASAN_ID");

  $tanda_tangan->selectByParams(array("A.PEGAWAI_ID" => $reqUserAtasanId));
  $tanda_tangan->firstRow();

  $pegawai->selectByParams(array("A.PEGAWAI_ID" => "'".$reqUserAtasanId."'"));
  $pegawai->firstRow();

}
?>

<div class="konten-naskah">

  <!-- Start Kop Surat -->
  <div class="kop-surat">
    <div class="logo-kop"><img src="<?=base_url();?>/images/logo-surat.png" width="250px" height="*"></div>
    <div class="alamat-kop">
      <b>PT. Angkasa Pura I (Persero)</b><br>
      <i>Kantor Pusat Jakarta :</i><br>
      Kota Baru Bandar Kemayoran Blok B. 12 Kav. 2<br>
      Jakarta 10610, Indonesia<br>
      tel : 021 654 1961 &nbsp;&nbsp;fax : 021 654 1514<br>
      web : www.ap1.co.id
    </div>
  </div>
  <!-- End Kop Surat -->

  <!-- Start Jenis Naskah -->
  <div class="jenis-naskah">
    <div class="nama-jenis-naskah"><u>N O T A&nbsp;&nbsp;&nbsp;D I N A S</u></div>
    <div class="nomor-naskah">NOMOR : <?=$surat_masuk->getField("NOMOR")?></div>
  </div>
  <!-- End Jenis Naskah -->

  <!-- Start Tujuan Naskah -->
  <div class="tujuan-naskah">
    <table width="100%">
      <tr>
        <td width="20%">KEPADA YTH.</td>
        <td width="1%">:</td>
        <td width="79%"><?=$surat_masuk->getField("KEPADA")?></td>
      </tr>
      <tr>
        <td>DARI</td>
        <td>:</td>
        <td><?=$surat_masuk->getField("INSTANSI_ASAL")?></td>
      </tr>
      <tr>
        <td>PERIHAL</td>
        <td>:</td>
        <td><?=$surat_masuk->getField("NOMOR")?></td>
      </tr>
    </table>
  </div>
  <!-- End Tujuan Naskah -->

  <!-- Start Pembatas -->
  <div class="pembatas"></div>
  <!-- End Pembatas -->

  <!-- Start Isi Naskah -->
  <div class="isi-naskah">
    <?=$surat_masuk->getField("ISI")?>
  </div>
  <!-- End Isi Naskah -->

  <!-- Start Tanda Tangan -->
  <div class="tanda-tangan-kanan">
    <?=$surat_masuk->getField("KOTA_ASAL")?>, <?=$surat_masuk->getField("TANGGAL")?><br>
    <?=$pegawai->getField("JABATAN")?><br>
    <img src="<?=base_url();?>/uploads/<?=$tanda_tangan->getField("ATTACHMENT")?>" width="250px" height="*"><br>
    <u><b><?=$pegawai->getField("NAMA")?></b></u>
  </div>
  <!-- End Isi Naskah -->

  <!-- Start Tembusan -->
  <div class="tembusan">
    <b><u>Tembusan Yth. :</u></b>
    <br>
    <?=$surat_masuk->getField("TEMBUSAN")?>
  </div>
  <!-- End Tembusan -->

  <!-- Start Maker Surat -->
  <div class="maker-surat">
    <i><?=$surat_masuk->getField("USER_ID")?>/<?=$surat_masuk->getField("TANGGAL")?></i>
  </div>
  <!-- End Maker Surat -->
</div>