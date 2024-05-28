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
?>

<!-- Start Kop Surat -->
<div class="kop-surat">
    <div class="logo-kop"><img src="<?= base_url(); ?>/images/logo-surat.jpg" width="220px" height="*"></div>
    <div class="alamat-kop">
        <!-- <b>PT.ASDP Indonesia Ferry (Persero)</b><br>
        <i>Jl. Jend Ahmad Yani Kav. 52 A, Jakarta 10510,</i><br>
        <i> Indonesia</i><br>
        tel : +6221 4208911-13-15 <br>
        fax : +6221 4210544 <br>
        web : www.indonesiaferry.co.id -->
        <b>PT.ASDP Indonesia Ferry (Persero)</b><br>
        <i><?= $suratmasukinfo->NAMA_UNIT  ?></i><br>
        <?= $suratmasukinfo->ALAMAT_UNIT  ?><br>
        tel : <?= $suratmasukinfo->TELEPON_UNIT  ?> &nbsp;&nbsp; <br>
        fax : <?= $suratmasukinfo->FAX_UNIT  ?><br>
        web : www.indonesiaferry.co.id
    </div>
</div>
<!-- End Kop Surat -->

<!-- Start Jenis Naskah -->
<div class="jenis-naskah">
    <div class="nama-jenis-naskah"><b><u>S&nbsp;&nbsp;&nbsp;&nbsp;U&nbsp;&nbsp;&nbsp;&nbsp;R&nbsp;&nbsp;&nbsp;&nbsp;A&nbsp;&nbsp;&nbsp;&nbsp;T&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E&nbsp;&nbsp;&nbsp;&nbsp;D&nbsp;&nbsp;&nbsp;&nbsp;A&nbsp;&nbsp;&nbsp;&nbsp;R&nbsp;&nbsp;&nbsp;&nbsp;A&nbsp;&nbsp;&nbsp;&nbsp;N</u></b></div>
    <div class="nomor-naskah">NOMOR : <?= $suratmasukinfo->NOMOR  ?></div>
    <!-- <div class="nomor-naskah">Nomor: SE.0005/UM.207/ASDP-2020</div> -->
</div>
<!-- End Jenis Naskah -->

<!-- Start Kepada Naskah -->
<!-- <div class="kepada-naskah">
  <div class="kepada-alamat">
    <table width="100%">
      <tr>
        <td width="10%"></td>
        <td width="1%"></td>
        <td width="89%">Kepada</td>
      </tr>
      <tr>
        <td>Yth.</td>
        <td></td>
        <td>
          <?/*
          $arrayKepada =  explode(',', strtoupper($suratmasukinfo->KEPADA));
          if (count($arrayKepada) == 1) {
            echo strtoupper($suratmasukinfo->KEPADA);
          } else {
          */ ?>
            <ol>
              <?/*
              foreach ($arrayKepada as $itemKepada) {
              */ ?>
                <li><?/*= $itemKepada */ ?></li>
              <?/*
              }
              */ ?>
            </ol>
          <?/*
          }
          */ ?>
        </td>
      </tr>
      <tr>
        <td></td>
        <td></td>
        <td>di</td>
      </tr>
      <tr>
        <td></td>
        <td></td>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;- Tempat</td>
      </tr>
    </table>
  </div>
</div> -->
<!-- End Jenis Naskah -->

<!-- Start Tentang Naskah -->
<div class="jenis-naskah">
    <div class="nomor-naskah">TENTANG</div>
    <div class="nomor-naskah"><?= $suratmasukinfo->PERIHAL ?></div>
    <!-- <div class="nomor-naskah">Antisipasi Penyebaran Virus Corona COVID-19</div> -->
</div>
<!-- End Tentang Naskah -->

<!-- Start Isi Naskah -->
<div class="isi-naskah">

    <?= $suratmasukinfo->ISI; ?>
    <!-- <table>

        <tr>
            <td align="justify"><b><?= $suratmasukinfo->PERIHAL; ?> </b>, bersama ini disampaikan hal-hal sebagai berikut:</td>
        </tr>

        <tr>
            <td>
                <table>
                    <tr>
                        <td></td>
                        <td>1.</td>
                        <td align="justify"></td>
                    </tr> -->
    <!-- <tr>
                        <td></td>
                        <td>2.</td>
                        <td align="justify">Seluruh jajaran Manajemen dan Karyawan di lingkungan PT ASDP Indonesia Ferry (Persero) harus menangguhkan perjalanan
                            dinas ke Negara yang terkonfirmasi terinfeksi Virus Corona (COVID-19) ataupun ke lokasi terdekat dari Zona tersebut
                            sebagaimana informasi oleh World Health Organization (WHO) sampai pemberitahuan lebih lanjut.
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>3.</td>
                        <td align="justify">Menghimbau kepada seluruh Karyawan dan keluarganya untuk tidak melakukan perjalanan non kedinasan ke luar negeri
                            terutama ke Negara terinfeksi Virus Corona (COVID-19) oleh Word Health Organization (WHO) sampai pemberitahuan lebih
                            lanjut.</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>4.</td>
                        <td align="justify">Bagi Karyawan dan keluarganya yang telah melakukan perjalanan dinas ataupun non kedinasan ke Negara ataupun zona
                            terdekat sebagaimana dimaksud pada poin 1, dalam kurun waktu 2 (dua) bulan terakhir, agar segera melaporkan riwayat
                            perjalanan tersebut melalui email : umum@indonesiaferry.co.id dan sdm@indonesiaferry.co.id dengan format laporan sesuai
                            Lampiran II (format dapat diunduh di https://drive.google.com/open?id=1NarHkJNQG7OjL2bdbnoBTSu0W39tnySi).
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>5.</td>
                        <td align="justify">Bagi Karyawan yang telah melakukan perjalanan ke Negara sebagaimana dimaksud pada poin 1 dengan status orange
                            alert atau red alert, diwajibkan melakukan prosedur deteksi dini di Puskesmas dan Rumah Sakit setempat dan
                            menghubungi tanggap darurat virus Novel Corona Kemenkes di Nomor Call hotline 021-5210411 dan 081212123119.
                        </td>
                    </tr> -->
    <!-- </table>
    </td>
    </tr>

    <tr>
        <td align="justify">Demikian disampaikan untuk dijadikan pedoman, disosialisasikan dan dilaksakan di lingkungan Unit Kerja masing-masing.</td>
    </tr>

    </table> -->

</div>
<!-- End Isi Naskah -->

<!-- Start Tanda Tangan -->
<div class="tanda-tangan-kanan">
    <table width="100%">
        <tr>
            <td width="40%" align="justify">DIKELUARKAN</td>
            <td width="1%" align="justify">:</td>
            <td width="59%"><?= $suratmasukinfo->LOKASI_UNIT  ?></td>
            <!-- <td width="59%" align="justify">Jakarta</td> -->
        </tr>
        <tr class="border-bottom">
            <td align="justify">PADA TANGGAL</td>
            <td align="justify">:</td>
            <td><?= getFormattedDate($suratmasukinfo->TANGGAL)  ?></td>
            <!-- <td align="justify">14 Agustus 2020</td> -->
        </tr>
    </table>
    <br>&nbsp;
    <!-- <br>A.N. DIREKSI
    <br> DIREKTUR SDM DAN
    <br> LAYANAN KORPORASI -->
    <br><?= strtoupper($suratmasukinfo->USER_ATASAN_JABATAN);  ?>
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
    <br><u><b><?= strtoupper($suratmasukinfo->USER_ATASAN); ?></b></u>
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