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
    <!-- <div class="nama-jenis-naskah"><b><u>S&nbsp;&nbsp;&nbsp;&nbsp;U&nbsp;&nbsp;&nbsp;&nbsp;R&nbsp;&nbsp;&nbsp;&nbsp;A&nbsp;&nbsp;&nbsp;&nbsp;T&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E&nbsp;&nbsp;&nbsp;&nbsp;D&nbsp;&nbsp;&nbsp;&nbsp;A&nbsp;&nbsp;&nbsp;&nbsp;R&nbsp;&nbsp;&nbsp;&nbsp;A&nbsp;&nbsp;&nbsp;&nbsp;N</u></b></div> -->
    <div class="nama-jenis-naskah"><b><u>SURAT KEPUTUSAN DIREKSI PT ASDP INDONESIA FERRY (PERSERO)</u></b></div>
    <div class="nomor-naskah">NOMOR : <?= $suratmasukinfo->NOMOR  ?></div>
    <!-- <div class="nomor-naskah">Nomor: SE.0034/UM.209/ASDP-2020</div> -->
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
    <div class="nomor-naskah"><strong>TENTANG</strong> </div>
    <div class="nomor-naskah"><?= $suratmasukinfo->PERIHAL  ?></div>
    <!-- <div class="nomor-naskah"><strong>PERUBAHAN KEPUTUSAN DIREKSI NOMOR ..... TENTANG PROGRAM PEMILIKAN MOBIL- CAR OWNERSHIP PROGRAM PT ASDP INDONESIA FERRY (PERSERO) </strong></div> -->
</div>
<!-- End Tentang Naskah -->

<!-- Start Pembatas -->
<div class="pembatas"></div>
<!-- End Pembatas -->

<!-- Start Tentang Naskah -->
<div class="jenis-naskah">
    <!-- <div class="nomor-naskah"><strong>TENTANG</strong> </div> -->
    <div class="nomor-naskah"><strong><?= $suratmasukinfo->USER_ATASAN_JABATAN ?> PT ASDP INDONESIA FERRY (PERSERO)</strong></div>
    <!-- <div class="nomor-naskah"><?/*= $suratmasukinfo->PERIHAL */ ?></div> -->
</div>
<!-- End Tentang Naskah -->

<!-- Start Isi Naskah -->
<div class="isi-naskah">

    <?= $suratmasukinfo->ISI ?>

    <!-- <table>

        <tr>
            <td>Menimbang</td>
            <td>:</td>
            <td>
                <table>
                    <tr>
                        <td>a.</td>
                        <td align="justify"> 
                    </tr> -->
    <!-- <tr>
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
                    </tr> -->
    <!-- </table>
            </td>
        </tr>

        <tr>
            <td>Mengingat</td>
            <td>:</td>
            <td>
                <table>
                    <tr>
                        <td>1.</td>
                        <td align="justify"><?/*= $suratmasukinfo->ISI; */ ?></td>
                    </tr> -->
    <!-- <tr>
                        <td>2.</td>
                        <td align="justify">Seluruh karyawan PT ASDP Indonesia Ferry (Persero) wajib mengikuti rangkaian upacara Kemerdekaan Republik Indonesia 17 agustus 2020 dengan
                            khidmat dan lengkap secara daring (online) maupun melalui siaran media televisi dengan menggunakan Pakaian Dinas Harian (PDH) lengkap.
                        </td>
                    </tr>
                    <tr>
                        <td>3.</td>
                        <td align="justify">Sejak awal sirine hingga selesainya lagu Indonesia Raya, seluruh peserta upacara diminta berdiri, kecuali yang dalam keadaan sakit atau memiliki
                            keterbatasan fisik</td>
                    </tr>
                    <tr>
                        <td>4.</td>
                        <td align="justify">Selanjutnya karyawan melaporkan kegiatan upacara tersebut dalam bentuk dokumentasi berupa foto atau video, yang disampaikan ke fungsi yang
                            membidangi SDM pada wilayah (Kantor Pusat, Regional, dan Cabang) masing-masing.
                        </td>
                    </tr>
                    <tr>
                        <td>5.</td>
                        <td align="justify">Selanjutnya karyawan melaporkan kegiatan upacara tersebut dalam bentuk dokumentasi berupa foto atau video, yang disampaikan ke fungsi yang
                            membidangi SDM pada wilayah (Kantor Pusat, Regional, dan Cabang) masing-masing.
                        </td>
                    </tr> -->
    <!-- </table>
            </td>
        </tr>


    </table> -->

</div>
<!-- End Isi Naskah -->


<!-- Start Tentang Naskah -->
<!-- <div class="jenis-naskah"> -->
<!-- <div class="nomor-naskah"><strong>TENTANG</strong> </div> -->
<!-- <div class="nomor-naskah">MEMUTUSKAN</div> -->
<!-- <div class="nomor-naskah"><?/*= $suratmasukinfo->PERIHAL */ ?></div> -->
<!-- </div> -->
<!-- End Tentang Naskah -->


<!-- Start Isi Naskah -->
<!-- <div class="isi-naskah">

    <table>

        <tr>
            <td>Menetapkan</td>
            <td>:</td>
            <td>
                <table>
                    <tr>
                        <td>a.</td>
                        <td align="justify"> <?/*= $suratmasukinfo->ISI  */ ?></td>
                    </tr>

                </table>
            </td>
        </tr>

    </table> -->

<?/*= $suratmasukinfo->ISI */ ?>
<!-- </div> -->
<!-- End Isi Naskah -->


<!-- Start Tentang Naskah -->
<!-- <div class="jenis-naskah">
    <div class="nomor-naskah">PASAL 1</div>
    <div class="nomor-naskah">BEBERAPA KEPUTUSAN DAN KETENTUAN DIREKSI PT ASDP INDONESIA FERRY , TENTANG CAR OWNERSHIP PROGRAM PT ASDP INDONESIA FERRY, MENGALAMI PERUBAHAN ANTARA LAIN :</div> -->
<!-- <div class="nomor-naskah"><?/*= $suratmasukinfo->PERIHAL */ ?></div> -->
<!-- </div> -->
<!-- End Tentang Naskah -->

<!-- Start Isi Naskah -->
<!-- <div class="isi-naskah"> -->
<?/*= $suratmasukinfo->ISI */ ?>

<!-- <table>

    <tr>
        <td>.</td>
        <td>Ketentuan Pada Pasal 1 ditambahkan 1 ayat yang berbunyi :</td>
    </tr>

    <tr>
        <td></td>
        <td>
            <table>
                <tr>
                    <td></td>
                    <td align="justify"> "<?/*= $suratmasukinfo->ISI; */ ?>"</td>
                </tr>
                <tr>
                    <td></td>
                    <td align="justify">Sehingga Pasal 1 menjadi berbunyi sebagai berikut :</td>
                </tr>
                <tr>
                    <td></td>
                    <td align="justify">Dalam keputusan ini yang dimaksud dengan :</td>
                </tr>

                <tr>
                    <td></td>
                    <td>
                        <table>
                            <tr>
                                <td>a.</td>
                                <td> <?/*= $suratmasukinfo->ISI  */ ?></td>
                            </tr> -->
<!-- <tr>
                                    <td>b.</td>
                                    <td>Perusahaan adalah perusahaan PT ASDP Indonesia Ferry</td>
                                </tr>
                                <tr>
                                    <td>c.</td>
                                    <td>Perusahaan adalah perusahaan PT ASDP Indonesia Ferry</td>
                                </tr>
                                <tr>
                                    <td>d.</td>
                                    <td>Perusahaan adalah perusahaan PT ASDP Indonesia Ferry</td>
                                </tr>
                                <tr>
                                    <td>e.</td>
                                    <td>Perusahaan adalah perusahaan PT ASDP Indonesia Ferry</td>
                                </tr> -->
<!-- </table>
</td>
</tr>

</table>
</td>
</tr>

</table>
<table>

    <tr>
        <td>2.</td>
        <td>Ketentuan Pada Pasal 1 ditambahkan 1 ayat yang berbunyi :</td>
    </tr>

    <tr>
        <td></td>
        <td>
            <table>
                <tr>
                    <td></td>
                    <td align="justify"> "<?/*= $suratmasukinfo->ISI; */ ?>".</td>
                </tr>
                <tr>
                    <td></td>
                    <td align="justify">Sehingga Pasal 1 menjadi berbunyi sebagai berikut :</td>
                </tr>
                <tr>
                    <td></td>
                    <td align="justify">Dalam keputusan ini yang dimaksud dengan :</td>
                </tr>

                <tr>
                    <td></td>
                    <td>
                        <table>
                            <tr>
                                <td>a.</td>
                                <td><?/*= $suratmasukinfo->ISI; */ ?></td>
                            </tr> -->
<!-- <tr>
                                    <td>b.</td>
                                    <td>Perusahaan adalah perusahaan PT ASDP Indonesia Ferry</td>
                                </tr>
                                <tr>
                                    <td>c.</td>
                                    <td>Perusahaan adalah perusahaan PT ASDP Indonesia Ferry</td>
                                </tr>
                                <tr>
                                    <td>d.</td>
                                    <td>Perusahaan adalah perusahaan PT ASDP Indonesia Ferry</td>
                                </tr>
                                <tr>
                                    <td>e.</td>
                                    <td>Perusahaan adalah perusahaan PT ASDP Indonesia Ferry</td>
                                </tr> -->
<!-- </table>
</td>
</tr>

</table>
</td>
</tr>

</table> -->

<!-- </div> -->
<!-- End Isi Naskah -->


<!-- Start Tentang Naskah -->
<!-- <div class="jenis-naskah">
    <div class="nomor-naskah">PASAL 2</div> -->
<!-- <div class="nomor-naskah">BEBERAPA KEPUTUSAN DAN KETENTUAN DIREKSI PT ASDP INDONESIA FERRY , TENTANG CAR OWNERSHIP PROGRAM PT ASDP INDONESIA FERRY, MENGALAMI PERUBAHAN ANTARA LAIN :</div> -->
<!-- <div class="nomor-naskah"><?/*= $suratmasukinfo->PERIHAL */ ?></div> -->
<!-- </div> -->
<!-- End Tentang Naskah -->

<!-- Start Isi Naskah -->
<!-- <div class="isi-naskah"> -->
<?/*= $suratmasukinfo->ISI */ ?>

<!-- <table> -->
<!-- 
<tr>
    <td>1.</td>
    <td>Ketentuan Pada Pasal 2 ditambahkan 1 ayat yang berbunyi :</td>
</tr>

<tr>
    <td></td>
    <td>
        <table>
            <tr>
                <td></td>
                <td align="justify"> "<?/*= $suratmasukinfo->ISI; */ ?>".</td>
            </tr>
            <tr>
                <td></td>
                <td align="justify">Sehingga Pasal 1 menjadi berbunyi sebagai berikut :</td>
            </tr>
            <tr>
                <td></td>
                <td align="justify">Dalam keputusan ini yang dimaksud dengan :</td>
            </tr>

            <tr>
                <td></td>
                <td>
                    <table>
                        <tr>
                            <td>a.</td>
                            <td><?/*= $suratmasukinfo->ISI; */ ?></td>
                        </tr> -->
<!-- <tr>
                                    <td>b.</td>
                                    <td>Perusahaan adalah perusahaan PT ASDP Indonesia Ferry</td>
                                </tr>
                                <tr>
                                    <td>c.</td>
                                    <td>Perusahaan adalah perusahaan PT ASDP Indonesia Ferry</td>
                                </tr>
                                <tr>
                                    <td>d.</td>
                                    <td>Perusahaan adalah perusahaan PT ASDP Indonesia Ferry</td>
                                </tr>
                                <tr>
                                    <td>e.</td>
                                    <td>Perusahaan adalah perusahaan PT ASDP Indonesia Ferry</td>
                                </tr> -->
<!-- </table>
</td>
</tr>

</table>
</td>
</tr>

</table>
<table>

    <tr>
        <td>(2.)</td>
        <td>Ketentuan Pada Pasal 1 ditambahkan 1 ayat yang berbunyi :</td>
    </tr>

    <tr>
        <td></td>
        <td>
            <table>
                <tr>
                    <td></td>
                    <td align="justify"> "<?= $suratmasukinfo->ISI; ?>".</td>
                </tr>
                <tr>
                    <td></td>
                    <td align="justify">Sehingga Pasal 1 menjadi berbunyi sebagai berikut :</td>
                </tr>
                <tr>
                    <td></td>
                    <td align="justify">Dalam keputusan ini yang dimaksud dengan :</td>
                </tr>

                <tr>
                    <td></td>
                    <td>
                        <table>
                            <tr>
                                <td>a.</td>
                                <td><?/*= $suratmasukinfo->ISI; */ ?></td>
                            </tr> -->
<!-- <tr>
                                    <td>b.</td>
                                    <td>Perusahaan adalah perusahaan PT ASDP Indonesia Ferry</td>
                                </tr>
                                <tr>
                                    <td>c.</td>
                                    <td>Perusahaan adalah perusahaan PT ASDP Indonesia Ferry</td>
                                </tr>
                                <tr>
                                    <td>d.</td>
                                    <td>Perusahaan adalah perusahaan PT ASDP Indonesia Ferry</td>
                                </tr>
                                <tr>
                                    <td>e.</td>
                                    <td>Perusahaan adalah perusahaan PT ASDP Indonesia Ferry</td>
                                </tr> -->
<!-- </table>
</td>
</tr>

</table>
</td>
</tr>

</table>

</div> -->
<!-- End Isi Naskah -->

<!-- Start Tanda Tangan -->
<div class="tanda-tangan-kanan">
    <table width="100%">
        <tr>
            <td width="40%" align="justify">DITETAPKAN</td>
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
    <br> DIREKTUR UTAMA
    <br> LAYANAN KORPORASI -->
    <br><?= strtoupper($suratmasukinfo->USER_ATASAN_JABATAN)  ?>
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
    <br><u><b><?= strtoupper($suratmasukinfo->USER_ATASAN) ?></b></u>
    <!-- <br><u><b>IRA PUSPADEWI</b></u> -->
</div>
<!-- End Isi Naskah -->

<!-- Start Tentang Naskah -->
<div class="jenis-naskah">
    <div class="">Salinan Keputusan Direksi Ini </div>
    <div class="">Disampaikan Kepada YTH.</div>
    <!-- <div class="nomor-naskah"><?/*= $suratmasukinfo->PERIHAL */ ?></div> -->
</div>
<!-- End Tentang Naskah -->


<!-- Start Isi Naskah -->
<div class="isi-naskah">

    <table>

        <tr>
            <td></td>
            <td>
                <table>
                    <tr>
                        <td>1.</td>
                        <td align="justify"><?= $suratmasukinfo->USER_ATASAN_JABATAN; ?></td>
                    </tr>
                    <!-- <tr>
                        <td>2.</td>
                        <td align="justify">Surat General Manager Cabang Biak Nomor UM.008/0161/VI/ASDP-BIK/2020 perihal Permohonan Persetujuan Pekerjaan Renovasi
                            Rumah Dinas GM Cabang Biak Tahun 2020 tanggal 29 Juni 2020</td>
                    </tr>
                    <tr>
                        <td>2.</td>
                        <td align="justify"> Pandemi Virus Corona Covid-19 berdampak kepada penurunan produksi di seluruh cabang PT. ASDP Indonesia Ferry (Persero) yang
                            berakibat penurunan pendapatan dibandingkan tahun 2019</td>
                    </tr>
                    <tr>
                        <td>e.</td>
                        <td align="justify"> Pandemi Virus Corona Covid-19 berdampak kepada penurunan produksi di seluruh cabang PT. ASDP Indonesia Ferry (Persero) yang
                            berakibat penurunan pendapatan dibandingkan tahun 2019</td>
                    </tr>
                    <tr>
                        <td>f.</td>
                        <td align="justify"> Pandemi Virus Corona Covid-19 berdampak kepada penurunan produksi di seluruh cabang PT. ASDP Indonesia Ferry (Persero) yang
                            berakibat penurunan pendapatan dibandingkan tahun 2019</td>
                    </tr> -->
                </table>
            </td>
        </tr>

    </table>

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