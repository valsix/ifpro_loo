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
    <div class="logo-kop"><img src="<?= base_url(); ?>/images/logo_fullcolor.png" width="220px" height="*"></div>
    <div class="alamat-kop">
        <!-- <b>PT.ASDP Indonesia Ferry (Persero)</b><br>
        <i>Jl. Jend Ahmad Yani Kav. 52 A, Jakarta 10510,</i><br>
        <i> Indonesia</i><br>
        tel : +6221 4208911-13-15 <br>
        fax : +6221 4210544 <br>
        web : www.indonesiaferry.co.id -->

        <b>PT.JEMBATAN NUSANTARA</b><br>
        <i>Kantor Pusat<br>
        Gedung Pelni Heritage Lt.2 <br>Jl. Pahlawan No. 112 – 114 <br>Surabaya 60175, East Java Indonesia</i><br>
        tel : +62 31 99220000
    </div>
</div>
<!-- End Kop Surat -->

<!-- Start Jenis Naskah -->
<!-- <div class="jenis-naskah">
        <div class="nama-jenis-naskah"><b><u>S&nbsp;&nbsp;U&nbsp;&nbsp;R&nbsp;&nbsp;A&nbsp;&nbsp;T&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;K&nbsp;&nbsp;E&nbsp;&nbsp;L&nbsp;&nbsp;U&nbsp;&nbsp;A&nbsp;&nbsp;R&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;I&nbsp;&nbsp;N&nbsp;&nbsp;T&nbsp;&nbsp;E&nbsp;&nbsp;R&nbsp;&nbsp;N&nbsp;&nbsp;A&nbsp;&nbsp;L</u></b></div>
        <div class="nomor-naskah">NOMOR : <?/*= $suratmasukinfo->NOMOR; */ ?></div> -->
<!-- <div class="nomor-naskah">Nomor: 0211/ND-PPU/VIII/ASDP-2020</div> -->
<!-- </div> -->
<!-- End Jenis Naskah -->

<!-- Start Tujuan Naskah -->
<div class="tujuan-naskah">
    <table width="100%" border="">

        <tr>
            <td style="width: 79px;">Nomor</td>
            <td style="width: 5px;">:</td>
            <td style="width: 250px;"><?= $suratmasukinfo->NOMOR; ?></td>
            <td style="width: 79px;"></td>
            <td style="width: 5px;"></td>
            <td style="width: 250px;"><?= $suratmasukinfo->LOKASI_UNIT ?>, <?= getFormattedDate($suratmasukinfo->TANGGAL); ?></td>
            <!-- <td align="right">Jakarta, 17 Juli 2020</td> -->
        </tr>
        <br>
        <!-- <br> -->

        <tr>
            <td style="width: 79px;">Perihal</td>
            <td style="width: 5px;">:</td>
            <td style="width: 250px;"><?= $suratmasukinfo->PERIHAL; ?></td>
            <td style="width: 79px;">Kepada Yth.</td>
            <td style="width: 5px;">:</td>
            <td style="width: 250px;">
                <?
                $arrayKepada =  explode(',', strtoupper($suratmasukinfo->KEPADA));
                if (count($arrayKepada) == 1) {
                    echo $suratmasukinfo->KEPADA;
                    // echo "<table>";

                    // echo "<tr>";
                    // echo "<td>1.</td>";
                    // echo "<td>Senior General Regional IV</td>";
                    // echo "</tr>";

                    // echo "<tr>";
                    // echo "<td>2.</td>";
                    // echo "<td>Vice President Perencanaan Dan Pengendalian Keuangan</td>";
                    // echo "</tr>";

                    // echo "</table>";
                } else {
                ?>
                    <ol>
                        <?
                        foreach ($arrayKepada as $itemKepada) {
                        ?>
                            <li><?= $itemKepada ?></li>
                        <?
                        }
                        ?>
                    </ol>
                <?
                }
                ?>
            </td>
        </tr>


        <!-- <tr>
            <td>Perihal</td>
            <td>:</td>
            <td><?/*= $suratmasukinfo->PERIHAL */ ?></td> -->
        <!-- <td align="justify"> Jawaban Permohonan Persetujuan Pekerjaan Renovasi Rumah Dinas GM Cabang Biak</td> -->
        <!-- </tr> -->

        <tr>
            <td style="width: 79px;"></td>
            <td style="width: 5px;"></td>
            <td style="width: 250px;"></td>
            <td style="width: 79px;">Di</td>
            <td style="width: 5px;">:</td>
            <td style="width: 250px;"><?= $suratmasukinfo->ALAMAT_ASAL ?></td>
            <!-- <td align="justify">Ambon</td> -->
        </tr>

    </table>
</div>
<!-- End Tujuan Naskah -->

<!-- Start Pembatas -->
<!-- <div class="pembatas"></div> -->
<!-- End Pembatas -->

<!-- Start Isi Naskah -->
<div class="isi-naskah">

    <?= $suratmasukinfo->ISI  ?>

    <!-- <table>

        <tr>
            <td>1.</td>
            <td align="justify">Memperhatikan dan mendasari:</td>
        </tr>

        <tr>
            <td></td>
            <td>
                <table>
                    <tr>
                        <td>a.</td>
                        <td align="justify">
                        </td>
                    </tr> -->
    <!-- <tr>
                        <td>b.</td>
                        <td align="justify">Surat General Manager Cabang Biak Nomor UM.008/0161/VI/ASDP-BIK/2020 perihal Permohonan Persetujuan Pekerjaan Renovasi
                            Rumah Dinas GM Cabang Biak Tahun 2020 tanggal 29 Juni 2020</td>
                    </tr> -->
    <!-- </table>
            </td>
        </tr>

        <tr>
            <td>2.</td>
            <td align="justify">Memperhatikan dan mendasari:</td>
        </tr>

        <tr>
            <td></td>
            <td>
                <table>
                    <tr>
                        <td>a.</td>
                        <td align="justify"> 
                        </td>
                    </tr> -->
    <!-- <tr>
                        <td>b.</td>
                        <td align="justify"> Instruksi Direksi PT. ASDP Indonesia Ferry (Persero) Nomor: INST. 25/PA.201/ASDP-2020 perihal Program Prioritas Investasi,
                            Optimalisasi Pendapatan dan Efisiensi PT. ASDP Indonesia Ferry (Persero) tanggal 26 Mei 2020</td>
                    </tr>
                    <tr>
                        <td>c.</td>
                        <td align="justify"> Pandemi Virus Corona Covid-19 berdampak kepada penurunan produksi di seluruh cabang PT. ASDP Indonesia Ferry (Persero) yang
                            berakibat penurunan pendapatan dibandingkan tahun 2019</td>
                    </tr> -->
    <!-- </table> -->
    <!-- </td>
        </tr> -->

    <!-- <tr>
            <td>3.</td>
            <td align="justify"> Menindaklanjuti Surat Senior General Manager Regional IV Nomor: UM.008/2269/VI/ASDP-2020 perihal Persetujuan Pekerjaan Renovasi
                Rumah Dinas GM Cabang Biak, maka bersama ini disampaikan bahwa permohonan tersebut Rp. 122.971.610,- (Seratus Dua Puluh Dua Juta
                Sembilan Ratus Tujuh Puluh Satu Ribu Enam Ratus Sepuluh Rupiah) tidak dapat disetujui dengan berbagai pertimbangan yang telah
                disebutkan pada butir 2 (dua)</td>
        </tr>
        <tr>
            <td>4.</td>
            <td align="justify"> Adapun untuk dapat mengakomodir kebutuhan rumah dinas General Manager Cabang Biak dapat dilakukan dengan melakukan perbaikan
                dan pemeliharaan rutin atau dengan sewa rumah dinas menyesuaikan anggaran biaya yang tersedia oleh Cabang Biak dengan pola
                pelaksanaan yang paling efektif dan efisien</td>
        </tr>
        <tr>
            <td>5.</td>
            <td align="justify">Selanjutnya agar Regional IV maupun cabang dapat melaporkan realisasi proses pengadaan kepada Divisi Properti dan Umum.
            </td>
        </tr> -->
    <!-- <tr>
            <td>6.</td>
            <td align="justify"> Demikian disampaikan, atas perhatiannya diucapkan terimakasih.
            </td>
        </tr>

    </table> -->

</div>
<!-- End Isi Naskah -->

<!-- Start Tanda Tangan -->
<div class="tanda-tangan-kanan">
    <?= $suratmasukinfo->LOKASI_UNIT ?>, <?= getFormattedDate($suratmasukinfo->TANGGAL)  ?><br>
    <?= strtoupper($suratmasukinfo->USER_ATASAN_JABATAN) ?>,<br>
    <!-- Jakarta, 28 Agustus 2020<br>
    DIREKTUR SDM DAN LAYANAN KORPORASI
    <br> -->
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
    <br>
    <b><?= strtoupper($suratmasukinfo->USER_ATASAN)  ?></b>
    <!-- <b align="justify">ARIEF EKO K</b> -->
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
    <i><?= $suratmasukinfo->KODE_UNIT ?>/<?= $suratmasukinfo->KD_SURAT ?>/<?= strtoupper($alias) ?></i>
</div>
<!-- End Maker Surat -->