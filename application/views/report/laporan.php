<?php
/* INCLUDE FILE */
include_once("functions/date.func.php");
include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("libraries/vendor/autoload.php");

$this->load->library('suratmasukinfo');

$suratmasukinfo = new suratmasukinfo();

$reqId = httpFilterGet("reqId");
$reqJenisSurat = httpFilterGet("reqJenisSurat");

$suratmasukinfo->getInfo($reqId, $reqJenisSurat);
$telp=$suratmasukinfo->TELEPON_UNIT;
$fax=$suratmasukinfo->FAX_UNIT;
$alamat=$suratmasukinfo->ALAMAT_UNIT;
$alamatunit=str_replace(array('<p>', '</p>'), array('<i>', '</i>'), $alamat);

?>

<!-- Start Kop Surat -->
<div class="kop-surat" style="width:100%;  ">
    <div class="logo-kop" style="font-size: 8px; width:35%; text-align: right; margin-right: 70px  "><img src="<?= base_url(); ?>/images/logo-surat.jpg" width="120px" height="*" >
   <br>
    <br>
    <b>PT.ASDP Indonesia Ferry (Persero)</b><br>
    <i><?= $suratmasukinfo->NAMA_UNIT  ?></i><br>
    <?=$alamatunit?><br>
    <?
    if (!empty($telp))
    {
    ?>
      tel : <?=$suratmasukinfo->TELEPON_UNIT  ?> &nbsp;&nbsp;<br>
    <?
    }
    ?>
    <?
    if (!empty($fax))
    {
    ?>
      fax : <?=$suratmasukinfo->FAX_UNIT  ?>&nbsp;&nbsp;<br>
    <?
    }
    ?>

  <!--  <b>PT.ASDP Indonesia Ferry (Persero)</b><br>
    <i>Jl. Jend Ahmad Yani Kav. 52 A, Jakarta 10510,</i><br>
    <i> Indonesia</i><br>
    tel : +6221 4208911-13-15 <br>
    fax : +6221 4210544 <br>
    web : www.indonesiaferry.co.id -->

</div>
</div>

<div class="nama-jenis-naskah">
  <br>
  <b><u>LAPORAN <?= $suratmasukinfo->PERIHAL  ?></u></b><br>
 
</div>

  <!-- <div class="nomor-naskah">Partisipasi Menyemarakan Peringatan Hari Ulang Tahun Ke-75 Kemerdekaan Republik Indonesia Tahun 2020</div> -->
<!-- End Tentang Naskah -->


<!-- Start Isi Naskah -->
<div class="isi-naskah">
  <?= $suratmasukinfo->ISI ?> 
  <!-- <table>
    <tr>
      <td>1.</td>
      <td align="justify">Memperhatikan surat Sekretaris Kementerian Badan Usaha Milik Negara nomor : S-159/S.MBU/06/2020 Perihal Partisipasi Menyemarakan Peringatan Hari Ulang
        Tahun Ke-75 Kemerdekaan Republik Indonesia Tahun 2020.</td>
    </tr>

    <tr>
      <td>2.</td>
      <td align="justify">Sehubungan butir 1 (satu) di atas, disampaikan hal-hal sebagai berikut:</td>
    </tr>

    <tr>
      <td></td>
      <td>
        <table>
          <tr>
            <td>a.</td>
            <td align="justify"> 
            </td>
          </tr>
  <tr>
            <td>b.</td>
            <td align="justify">Seluruh karyawan PT ASDP Indonesia Ferry (Persero) wajib mengikuti rangkaian upacara Kemerdekaan Republik Indonesia 17 agustus 2020 dengan
              khidmat dan lengkap secara daring (online) maupun melalui siaran media televisi dengan menggunakan Pakaian Dinas Harian (PDH) lengkap.
            </td>
          </tr>
          <tr>
            <td>c.</td>
            <td align="justify">Sejak awal sirine hingga selesainya lagu Indonesia Raya, seluruh peserta upacara diminta berdiri, kecuali yang dalam keadaan sakit atau memiliki
              keterbatasan fisik</td>
          </tr>
          <tr>
            <td>d.</td>
            <td align="justify">Selanjutnya karyawan melaporkan kegiatan upacara tersebut dalam bentuk dokumentasi berupa foto atau video, yang disampaikan ke fungsi yang
              membidangi SDM pada wilayah (Kantor Pusat, Regional, dan Cabang) masing-masing.
            </td>
          </tr>
  </table>
      </td>
    </tr>


    <tr>
      <td>3.</td>
      <td align="justify">Demikian disampaikan untuk dilaksanakan dengan penuh rasa tanggung jawab, atas perhatian dan kerjasamanya diucapkan terimakasih.
      </td>
    </tr>

  </table> -->

  <?/*= $suratmasukinfo->ISI */ ?>
</div>
<!-- End Isi Naskah -->

<!-- Start Tanda Tangan -->
<div class="tanda-tangan-kanan">
  <table width="100%">
    <tr>
      <td width="20%" align="justify">Tempat/Tanggal</td>
      <td width="1%" align="justify">:</td>
      <td width="59%"><?= $suratmasukinfo->LOKASI_UNIT  ?> / <?= getFormattedDate2($suratmasukinfo->TANGGAL)  ?></td>
      <!-- <td width="59%" align="justify">Jakarta</td> -->
    </tr>
    <tr>
      <td width="20%" align="justify">Nama</td>
      <td width="1%" align="justify">:</td>
      <td width="59%"><?= $suratmasukinfo->USER_ATASAN  ?></td>
      <!-- <td width="59%" align="justify">Jakarta</td> -->
    </tr>
    <tr>
      <td width="20%" align="justify">Jabatan</td>
      <td width="1%" align="justify">:</td>
      <td width="59%"><?= $suratmasukinfo->USER_ATASAN_JABATAN  ?></td>
      <!-- <td width="59%" align="justify">Jakarta</td> -->
    </tr>
    <tr>
      <td width="20%" align="justify">Tanda Tangan</td>
      <td width="1%" align="justify">:</td>
      <td width="59%"><?= $suratmasukinfo->TTD_KODE  ?></td>
      <!-- <td width="59%" align="justify">Jakarta</td> -->
    </tr>
   
  </table>
  <br>&nbsp;
  <!-- <br> A.N. DIREKSI
  <br> DIREKTUR SDM DAN
  <br> LAYANAN KORPORASI -->
<!--   <br><?= strtoupper($suratmasukinfo->USER_ATASAN_JABATAN)  ?>
  <br>
  <?
  $ttdKode = $suratmasukinfo->TTD_KODE;
  if ($ttdKode == "" || $suratmasukinfo->JENIS_TTD == "BASAH") {
    echo "<br><br>";
  } else {
  ?>
    <img src="<?= base_url() ?>/<?= $suratmasukinfo->FOLDER_PATH ?>/<?= $suratmasukinfo->SURAT_MASUK_ID ?>/<?= $suratmasukinfo->TTD_KODE ?>.png" height="*">
    <br>
    <span style="font-size:10px;" align="justify"><i>&nbsp;</i></span>
  <?
  }
  ?>
  <br><u><b><?= strtoupper($suratmasukinfo->USER_ATASAN); ?></b></u> -->
  <!-- <br><u><b>WAHYU WIBOWO</b></u> -->
</div>
<!-- End Isi Naskah -->

<!-- Start Tembusan -->
<?
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
<!-- End Tembusan -->

<!-- Start Maker Surat -->
<div class="maker-surat">
  <?
  $arrNama = explode(" ", $suratmasukinfo->NAMA_USER);
  $jumlahNama = count($arrNama);
  if ($jumlahNama > 1) {
    $inisial = substr($arrNama[0], 0, 1);
    $lastname = $arrNama[$jumlahNama - 1];
    $alias = $inisial . "." . $lastname;
  } else
    $alias = $arrNama[0];
  ?>
  <i style="font-size:9px;"><?= $suratmasukinfo->KODE_UNIT ?>/<?= $suratmasukinfo->KD_SURAT ?>/<?= strtoupper($alias) ?></i>
</div>
<!-- End Maker Surat -->