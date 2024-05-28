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
<!-- <div class="jenis-naskah">
    <div class="nama-jenis-naskah"><b><u>S&nbsp;&nbsp;&nbsp;U&nbsp;&nbsp;&nbsp;R&nbsp;&nbsp;&nbsp;A&nbsp;&nbsp;&nbsp;T&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;T&nbsp;&nbsp;&nbsp;A&nbsp;&nbsp;&nbsp;N&nbsp;&nbsp;&nbsp;G&nbsp;&nbsp;&nbsp;G&nbsp;&nbsp;&nbsp;A&nbsp;&nbsp;&nbsp;P&nbsp;&nbsp;&nbsp;A&nbsp;&nbsp;&nbsp;N</u></b></div> -->
<!-- <div class="nomor-naskah">NOMOR : <?/*= $suratmasukinfo->NOMOR  */ ?></div> -->
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
            <td style="width: 250px;" align="right"><?= $suratmasukinfo->LOKASI_UNIT ?>, <?= getFormattedDate($suratmasukinfo->TANGGAL); ?></td>
            <!-- <td align="right">Jakarta, 17 Juli 2020</td> -->
        </tr>
        <br>
        <!-- <br> -->

        <tr>
            <td style="width: 79px;">Perihal</td>
            <td style="width: 5px;">:</td>
            <td style="width: 250px;"><?= $suratmasukinfo->PERIHAL; ?></td>
        </tr>

        <tr>
            <td style="width: 79px;">Lampiran</td>
            <td style="width: 5px;">:</td>
            <td style="width: 250px;"><?= $suratmasukinfo->JUMLAH_LAMPIRAN; ?> Berkas</td>
        </tr>

        <tr>
            <!-- <td style="width: 79px;">Perihal</td>
            <td style="width: 5px;">:</td>
            <td style="width: 250px;"><?/*= $suratmasukinfo->PERIHAL; */ ?></td> -->
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
            <!-- <td style="width: 79px;"></td>
            <td style="width: 5px;"></td>
            <td style="width: 250px;"></td> -->
            <td style="width: 79px;">Di</td>
            <td style="width: 5px;">:</td>
            <td style="width: 250px;"><?= $suratmasukinfo->ALAMAT_ASAL ?></td>
            <!-- <td align="justify">Ambon</td> -->
        </tr>

    </table>
</div>
<!-- End Tujuan Naskah -->

<!-- Start Tujuan Naskah -->
<!-- <div class="tujuan-naskah">
    <table width="100%">

        <tr>
            <td></td>
            <td></td>
            <td align="right"><?/*= getFormattedDate($suratmasukinfo->TANGGAL)  */ ?></td> -->
<!-- <td align="right">Jakarta,12 Juli 2020</td> -->
<!-- </tr>
        <br> -->
<!-- <br> -->
<!-- 
        <tr>
            <td width="20%">Kepada Yth.</td>
            <td width="1%">:</td>
            <td width="79%"> -->
<?
// $arrayKepada =  explode(',', strtoupper($suratmasukinfo->KEPADA));
// if (count($arrayKepada) == 1) {
//     echo $suratmasukinfo->KEPADA;
// echo "<table>";

// echo "<tr>";
// echo "<td>1.</td>";
// echo "<td>Pimpinan Kantor Advokat & Konsultan Hukum Pasakalis dan Rekan</td>";
// echo "</tr>";

// echo "<tr>";
// echo "<td>2.</td>";
// echo "<td>Vice President Perencanaan Dan Pengendalian Keuangan</td>";
// echo "</tr>";

// echo "</table>";
/*} else {/*
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
               */ ?>
<!-- </td>
        </tr> -->

<!-- 
        <tr>
            <td>Perihal</td>
            <td>:</td>
            <td><?/*= $suratmasukinfo->PERIHAL  */ ?></td> -->
<!-- <td align="justify"> Jawaban Tanggapan Dasar Hukum</td> -->
<!-- </tr> -->
<!-- 
        <tr>
            <td>Lampiran</td>
            <td>:</td>
            <td><?/*= $suratmasukinfo->JUMLAH_LAMPIRAN  */ ?> BERKAS</td>
            <td align="justify">1 Berkas</td> -->
<!-- </tr> -->
<!-- 
        <tr>
            <td>Di</td>
            <td>:</td>
            <td><?/*= $suratmasukinfo->ALAMAT_ASAL */ ?></td> -->
<!-- <td align="justify">Jakarta</td> -->
<!-- </tr>

    </table>
</div> -->
<!-- End Tujuan Naskah -->

<!-- Start Pembatas -->
<!-- <div class="pembatas"></div> -->
<!-- End Pembatas -->

<!-- Start Tentang Naskah -->
<!-- <div class="jenis-naskah"> -->
<!-- <div class="nomor-naskah">TENTANG</div> -->
<!-- <div class="nomor-naskah"><?/*= $suratmasukinfo->PERIHAL */ ?></div> -->
<!-- <div>Sehubungan dengan surat Saudara Nomor : ...... Perihal Permintaan Tanggapan Dasar Hukum tanggal 6 Agustus 2020, maka dapat kami sampaikan beberapa hal sebagai berikut :</div>
</div> -->
<!-- End Tentang Naskah -->

<!-- Start Isi Naskah -->
<div class="isi-naskah">
    <?= $suratmasukinfo->ISI   ?>

    <!-- <table>

        <tr>
            <td></td>
            <td>
                <table>
                    <tr>
                        <td>a.</td>
                        <td align="justify"><?= $suratmasukinfo->ISI; ?></td>
                    </tr> -->
    <!-- <tr>
                        <td>b.</td>
                        <td align="justify">Surat General Manager Cabang Biak Nomor UM.008/0161/VI/ASDP-BIK/2020 perihal Permohonan Persetujuan Pekerjaan Renovasi
                            Rumah Dinas GM Cabang Biak Tahun 2020 tanggal 29 Juni 2020</td>
                    </tr>
                    <tr>
                        <td>c.</td>
                        <td align="justify"> Pandemi Virus Corona Covid-19 berdampak kepada penurunan produksi di seluruh cabang PT. ASDP Indonesia Ferry (Persero) yang
                            berakibat penurunan pendapatan dibandingkan tahun 2019</td>
                    </tr>
                    <tr>
                        <td>d.</td>
                        <td align="justify"> Pandemi Virus Corona Covid-19 berdampak kepada penurunan produksi di seluruh cabang PT. ASDP Indonesia Ferry (Persero) yang
                            berakibat penurunan pendapatan dibandingkan tahun 2019</td>
                    </tr>
                    <tr>
                        <td>e.</td>
                        <td align="justify"> Pandemi Virus Corona Covid-19 berdampak kepada penurunan produksi di seluruh cabang PT. ASDP Indonesia Ferry (Persero) yang
                            berakibat penurunan pendapatan dibandingkan tahun 2019</td>
                    </tr> -->
    <!-- </table>
    </td>
    </tr>

    </table> -->

</div>
<!-- End Isi Naskah -->

<!-- Start Tentang Naskah -->
<!-- <div class="jenis-naskah"> -->
<!-- <div class="nomor-naskah">TENTANG</div> -->
<!-- <div class="nomor-naskah"><?/*= $suratmasukinfo->PERIHAL */ ?></div> -->
<!-- <div>Dalam hal sebelumnya telah terjadi kesalahpahaman komunikasi antar Bp. Wendy dan Ibu aura klien saudara , yang telah diselesaikan pada pertemuan tanggal 11 Agustus 2020, di kantor PT ASDP Indonesia Ferry </div>
</div> -->
<!-- End Tentang Naskah -->

<!-- Start Tentang Naskah -->
<!-- <div class="jenis-naskah"> -->
<!-- <div class="nomor-naskah">TENTANG</div> -->
<!-- <div>Demikian Surat ini kami sampaikan , atas perhatian dan kerjasamanya diucapkan terima kasih.</div> -->
<!-- <div class="nomor-naskah"><?/*= $suratmasukinfo->PERIHAL */ ?></div> -->
<!-- </div> -->
<!-- End Tentang Naskah -->

<!-- Start Tanda Tangan -->
<div class="tanda-tangan-kiri">
    <!-- AN Direktur SDM & Layanan Korporasi <br>
    Vice President Properti & Umum
    <br> -->
    <?= $suratmasukinfo->LOKASI_UNIT ?>, <?= getFormattedDate($suratmasukinfo->TANGGAL) ?><br>
    <?= strtoupper($suratmasukinfo->USER_ATASAN_JABATAN)  ?>,<br>
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