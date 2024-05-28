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
    $an_status = $suratmasukinfo->AN_STATUS;
    $an_nama = $suratmasukinfo->AN_NAMA;
    $alamatunit=str_replace(array('<p>', '</p>'), array('<i>', '</i>'), $alamat);
    ?>

    <!-- Start Kop Surat -->
    <div class="kop-surat" style="width:75%;  ">
      <div class="logo-kop" style="font-size: 8px; width:35%; text-align: right; margin-right: 70px  "><img src="/var/www/html/images/logo-surat.jpg" width="120px" height="*" >
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


    </div>
</div>
    <!-- End Kop Surat -->

    <!-- Start Jenis Naskah -->
    <div class="jenis-naskah">
        <div class="nama-jenis-naskah"><b><u>S&nbsp;&nbsp;U&nbsp;&nbsp;R&nbsp;&nbsp;A&nbsp;&nbsp;T&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;&nbsp;K&nbsp;&nbsp;U&nbsp;&nbsp;A&nbsp;&nbsp;S&nbsp;&nbsp;A</u></b></div>
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
    <!-- <div class="jenis-naskah"> -->
    <!-- <div class="nomor-naskah">TENTANG</div> -->
    <!-- <div class="nomor-naskah"><?= $suratmasukinfo->PERIHAL; ?></div> -->
    <!-- <div class="nomor-naskah" align="justify">Pada hari ini .............. Tanggal .............. Bulan .............. Tahun <strong>Dua ribu dua puluh</strong> (.......... - .............. - 2020)</div>
    </div> -->
    <!-- End Tentang Naskah -->

    <!-- Start Isi Naskah -->
    <div class="isi-naskah">
        <?= $suratmasukinfo->ISI; ?>

        <!-- <table>

            <tr>
                <td align="justify">Penanda tangan dibawah ini :</td>
            </tr>

            <tr>
                <td>
                    <table>
                        <tr>
                            <td></td>
                            <td>1.</td>
                            <td align="justify"><?= $suratmasukinfo->USER_ATASAN_JABATAN; ?> PT ASDP INDONESIA FERRY (PERSERO) SUATU perseroan terbatas yang didirikan berdasarkan hukum NKRI, berkedudukan dan berkantor di jakarta, dalam perbuatan hukum ini dijahat oleh , IRA PUSPADEWI berdasarkan Surat Keputusan Menteri Badan Uasha Milik Negara nomor Nomor: SE.0005/UM.207/ASDP-2020 pada tanggal 23 Desember 2017 tentang pemberhentian dan peningkatan direktur utama perusahaan persero PT ASDP INONESIA FERRY , mewakili DIREKSI dari dan oleh karena itu bertindak dan atas nama PT ASDP NDONESIA FERRY (PERSERO)
                        </tr>

                    </table>
                </td>
            </tr>


            <tr>
                <td align="justify">Dengan ini memberi kuasa kepada :</td>
            </tr>

            <tr>
                <td>
                    <table>
                        <tr>
                            <td></td>
                            <td>2.</td>
                            <td align="justify"><?= $suratmasukinfo->USER_ATASAN_JABATAN; ?> PT ASDP INDONESIA FERRY (PERSERO) SUATU perseroan terbatas yang didirikan berdasarkan hukum NKRI, berkedudukan dan berkantor di jakarta, dalam perbuatan hukum ini dijahat oleh , IRA PUSPADEWI berdasarkan Surat Keputusan Menteri Badan Uasha Milik Negara nomor Nomor: SE.0005/UM.207/ASDP-2020 pada tanggal 23 Desember 2017 tentang pemberhentian dan peningkatan direktur utama perusahaan persero PT ASDP INONESIA FERRY , mewakili DIREKSI dari dan oleh karena itu bertindak dan atas nama PT ASDP NDONESIA FERRY (PERSERO)
                        </tr>

                    </table>
                </td>
            </tr>

            <tr>
                <td align="justify">Selanjutnya disebut sebagai "<strong>PENERIMA KUASA</strong>".</td>
            </tr>

        </table> -->

    </div>
    <!-- End Isi Naskah -->

    <!-- Start Tentang Naskah -->
    <!-- <div class="jenis-naskah"> -->
    <!-- <div class="nomor-naskah">TENTANG</div> -->
    <!-- <div class="nomor-naskah"><?= $suratmasukinfo->PERIHAL; ?></div> -->
    <!-- <div class="nomor-naskah">------------------------------------------------------------------ KHUSUS ------------------------------------------------------------</div>
    </div> -->
    <!-- End Tentang Naskah -->

    <!-- Start Isi Naskah -->
    <!-- <div class="isi-naskah"> -->


    <!-- 
        <table>

            <tr>
                <td align="justify">Guna mewakili dan oleh karea itu bertindak dan atas nama "<strong>PEMBERI KUASA</strong>" melakukan tindakan-tindakan terbatas pada hal hal yang diatur dalam Surat Kuasa ini.</td>
            </tr>

            <tr>
                <td align="justify">"<strong>PENERIMA KUASA</strong>" mewakili "<strong>PEMBERI KUASA</strong>" untuk melakukan tindakan-tindakan sebagai berikut :</td>
            </tr>

            <tr>
                <td>
                    <table>
                        <tr>
                            <td></td>
                            <td>1.</td>
                            <td align="justify"> <?/*= $suratmasukinfo->ISI; */ ?>

                        </tr> -->
    <!-- <tr> -->
    <!-- <td></td>
                            <td>2.</td>
                            <td align="justify">Direktur Utama PT ASDP INDONESIA FERRY (PERSERO) SUATU perseroan terbatas yang didirikan berdasarkan hukum NKRI, berkedudukan dan berkantor di jakarta, dalam perbuatan hukum ini dijahat oleh , IRA PUSPADEWI berdasarkan Surat Keputusan Menteri Badan Uasha Milik Negara nomor Nomor: SE.0005/UM.207/ASDP-2020 pada tanggal 23 Desember 2017 tentang pemberhentian dan peningkatan direktur utama perusahaan persero PT ASDP INONESIA FERRY , mewakili DIREKSI dari dan oleh karena itu bertindak dan atas nama PT ASDP NDONESIA FERRY (PERSERO)
                        </tr>
                        <tr>
                            <td></td>
                            <td>3.</td>
                            <td align="justify">Melapoerkan Hasil Pelaksanaan Kegiatan tersebut kepada "<strong>PEMBERI KUASA</strong>".
                        </tr>
                        <tr>
                            <td></td>
                            <td>4.</td>
                            <td align="justify">Segala sesuatu yang berhubungan dengan pemberian kuasa ini "<strong>PEMBERI KUASA</strong>", memilih domisili yang tetap di kantor "<strong>PEMBERI KUASA</strong>"
                        </tr> -->
    <!-- 
        </table>
        </td>
        </tr>


        <tr>
            <td align="justify">Demikian surat kuasa ini di buat untuk dapat dipergunakan sebagaimana mestinya.</td>
        </tr>

        </table>

    </div> -->
    <!-- End Isi Naskah -->



    <!-- Start Tanda Tangan -->
    <div class="tanda-tangan-kiri">
        <br>
        <br>
        <br>
        PENERIMA KUASA

        <!-- Jakarta, 28 Agustus 2020<br>
    VICE PRESIDENT PROPERTI DAN UMUM<br> -->

        <?
        $ttdKode = $suratmasukinfo->TTD_KODE;
        if ($ttdKode == "" || $suratmasukinfo->JENIS_TTD == "BASAH") {
            echo "<br><br>";
        } else {
        ?>
            <!-- <img src="<?= base_url() ?>/<?= $suratmasukinfo->FOLDER_PATH ?>/<?= $suratmasukinfo->SURAT_MASUK_ID ?>/<?= $suratmasukinfo->TTD_KODE ?>.png" height="100px"> -->
            <img src="/var/www/html/<?= $suratmasukinfo->FOLDER_PATH ?>/<?= $suratmasukinfo->SURAT_MASUK_ID ?>/<?= $suratmasukinfo->TTD_KODE ?>.png" height="100px">
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


    <!-- Start Tanda Tangan -->
    <div class="tanda-tangan-kanan">

        <?= $suratmasukinfo->LOKASI_UNIT ?>, <?= getFormattedDate2($suratmasukinfo->TANGGAL)  ?><br>
        <?
        if ($an_status == 1)
        {
            ?>
            <br>A.n <?=$an_nama?>
            <?
        }
        ?>
        <?= $suratmasukinfo->USER_ATASAN_JABATAN; ?>,<br>
        <!-- Jakarta, 28 Agustus 2020<br>
    VICE PRESIDENT PROPERTI DAN UMUM<br> -->
     PEMBERI KUASA
        <?
        $ttdKode = $suratmasukinfo->TTD_KODE;
        if ($ttdKode == "" || $suratmasukinfo->JENIS_TTD == "BASAH") {
            echo "<br><br>";
        } else {
        ?>
            <!-- <img src="<?= base_url() ?>/<?= $suratmasukinfo->FOLDER_PATH ?>/<?= $suratmasukinfo->SURAT_MASUK_ID ?>/<?= $suratmasukinfo->TTD_KODE ?>.png" height="100px"> -->
            <img src="/var/www/html/<?= $suratmasukinfo->FOLDER_PATH ?>/<?= $suratmasukinfo->SURAT_MASUK_ID ?>/<?= $suratmasukinfo->TTD_KODE ?>.png" height="100px">
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
        <i style="font-size:9px;"><?= $suratmasukinfo->KODE_UNIT ?>/<?= $suratmasukinfo->KD_SURAT ?>/<?= strtoupper($alias) ?></i>
    </div>
    <!-- End Maker Surat -->