<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");
$this->load->model("SuratMasuk");
$this->load->model("SatuanKerja");
$this->load->model("Disposisi");
$this->load->model("DisposisiKelompok");
$this->load->model("SuratMasukReference");

$infoid= $reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqMode= $this->input->get("reqMode");
$reqStatusSurat= $this->input->get("reqStatusSurat");

// $surat_masuk= new SuratMasuk();
// $statement= " AND A.SURAT_MASUK_ID = ".$reqId;
// $surat_masuk->selectByParamsStatus(array(), -1,-1, $this->ID, $statement);
// $surat_masuk->firstRow();
// // echo $surat_masuk->query;exit;
// $reqId= $surat_masuk->getField("SURAT_MASUK_ID");
// if(empty($reqId) && !empty($reqMode))
// {
//     // redirect("main/index/status");
// }
// else
// {
//     // echo "ASd";exit;
//     $reqId= $infoid;
//     // $reqMode= "perlu_persetujuan";
// }

if(!empty($reqRowId))
{
    $surat_masuk= new SuratMasuk();
    $statement= " AND A.SURAT_MASUK_ID = ".$reqId." AND B.DISPOSISI_ID = ".$reqRowId;
    $surat_masuk->selectByParamsSuratMasuk(array(), -1,-1, $statement);
    $surat_masuk->firstRow();
    // echo $surat_masuk->query;exit;
    $infostatus= $surat_masuk->getField("INFO_STATUS");
    $infonomorsurat= $surat_masuk->getField("NOMOR");
    $infotanggalentri= getFormattedExtDateTimeCheck($surat_masuk->getField("TANGGAL_ENTRI"), false);
    $infosifatnaskah= $surat_masuk->getField("SIFAT_NASKAH");
    $infoperihal= $surat_masuk->getField("PERIHAL");
    $infosatuankerjaid= $surat_masuk->getField("SATUAN_KERJA_ID_ASAL");
    $infopemesansatuankerjaid= $surat_masuk->getField("PEMESAN_SATUAN_KERJA_ID");
    $infopemesansatuankerjaisi= $surat_masuk->getField("PEMESAN_SATUAN_KERJA_ISI");
}
else
{
    $surat_masuk= new SuratMasuk();
    $statement= " AND A.SURAT_MASUK_ID = ".$reqId;
    $surat_masuk->selectByParamsStatus(array(), -1,-1, $this->ID, $statement);
    $surat_masuk->firstRow();
    // echo $surat_masuk->query;exit;

    if(empty($surat_masuk->getField("SURAT_MASUK_ID")))
    {
        $statement= " AND A.SURAT_MASUK_ID = ".$reqId." AND (A.STATUS_SURAT IN ('TATAUSAHA','POSTING') OR A.STATUS_SURAT LIKE 'TU%')";
        $surat_masuk->selectByParamsSuratKeluar(array(), -1,-1, $this->ID, $statement);
        $surat_masuk->firstRow();
    }

    // echo $surat_masuk->query;exit;
    $infostatus= $surat_masuk->getField("INFO_STATUS");
    $infonomorsurat= $surat_masuk->getField("INFO_NOMOR_SURAT");
    $infotanggalentri= getFormattedExtDateTimeCheck($surat_masuk->getField("TANGGAL_ENTRI"), false);
    $infosifatnaskah= $surat_masuk->getField("SIFAT_NASKAH");
    $infoperihal= $surat_masuk->getField("PERIHAL");
    $infosatuankerjaid= $surat_masuk->getField("SATUAN_KERJA_ID_ASAL");
    $infopemesansatuankerjaid= $surat_masuk->getField("PEMESAN_SATUAN_KERJA_ID");
    $infopemesansatuankerjaisi= $surat_masuk->getField("PEMESAN_SATUAN_KERJA_ISI");
}

$disposisi= new Disposisi();
$reqKepada = $disposisi->getJson(array("SURAT_MASUK_ID" => $reqId, "STATUS_DISPOSISI" => "TUJUAN"));
$reqTembusan = $disposisi->getJson(array("SURAT_MASUK_ID" => $reqId, "STATUS_DISPOSISI" => "TEMBUSAN"));

$disposisi_kelompok = new DisposisiKelompok();
$reqKepadaKelompok = $disposisi_kelompok->getJson(array("SURAT_MASUK_ID" => $reqId, "STATUS_DISPOSISI" => "TUJUAN"));
$reqTembusanKelompok = $disposisi_kelompok->getJson(array("SURAT_MASUK_ID" => $reqId, "STATUS_DISPOSISI" => "TEMBUSAN"));

$satuan_kerja= new SatuanKerja();
$satuan_kerja->selectByParams(array(), -1, -1, " AND SATUAN_KERJA_ID = '".$infopemesansatuankerjaid."'", " ORDER BY KODE_SO ASC ");
$satuan_kerja->firstRow();
// echo $satuan_kerja->query;exit;
$infoppemesanenandatangankode= $satuan_kerja->getField("KODE_SURAT");
$infopemesanpenandatangannamapejabat= $satuan_kerja->getField("NAMA_PEGAWAI");
$infopemesanpenandatangannip= $satuan_kerja->getField("NIP");
$infopemesanjabatan= $satuan_kerja->getField("JABATAN");

$satuan_kerja= new SatuanKerja();
$satuan_kerja->selectByParams(array(), -1, -1, " AND SATUAN_KERJA_ID = '".$infosatuankerjaid."'", " ORDER BY KODE_SO ASC ");
$satuan_kerja->firstRow();
// echo $satuan_kerja->query;exit;
$infopenandatangankode= $satuan_kerja->getField("KODE_SURAT");
$infopenandatangannamapejabat= $satuan_kerja->getField("NAMA_PEGAWAI");
$infopenandatangannip= $satuan_kerja->getField("NIP");
$infojabatan= $satuan_kerja->getField("JABATAN");

$arrlog= array();
$index_data= 0;
$set= new SuratMasuk();
$set->selectByParamsDataLog(array("A.SURAT_MASUK_ID"=>$reqId),-1,-1);
while($set->nextRow())
{
    $arrlog[$index_data]["TANGGAL"] = dateTimeToPageCheck($set->getField("TANGGAL"));
    $arrlog[$index_data]["INFORMASI"] = $set->getField("INFORMASI");
    $arrlog[$index_data]["STATUS_SURAT"] = $set->getField("STATUS_SURAT");
    $arrlog[$index_data]["CATATAN"] = $set->getField("CATATAN");
    $index_data++;
}
$jumlahlog= $index_data;

$arrattachment= array();
$index_data= 0;
$set= new SuratMasuk();
$set->selectByParamsAttachment(array("A.SURAT_MASUK_ID" => (int)$reqId));
while($set->nextRow())
{
    $arrattachment[$index_data]["NAMA"] = $set->getField("NAMA");
    $arrattachment[$index_data]["UKURAN"] = $set->getField("UKURAN");
    $arrattachment[$index_data]["ATTACHMENT"] = $set->getField("ATTACHMENT");
    $arrattachment[$index_data]["TIPE"] = $set->getField("TIPE");
    $arrattachment[$index_data]["SURAT_MASUK_ATTACHMENT_ID"] = $set->getField("SURAT_MASUK_ATTACHMENT_ID");
    $index_data++;
}
$jumlahattachment= $index_data;

?>
<script type="text/javascript">
    function setkembali()
    {
        inforeload= "<?=infokembali($reqMode, $reqId, $reqRowId, $reqStatusSurat)?>";
        document.location.href= inforeload;
    }

    function setlihatdokumen()
    {
        document.location.href = 'main/index/status_detil?reqMode=<?=$reqMode?>&reqId=<?=$reqId?>';
    }

    function down(attach_id)
    {
        window.open("down?reqId=<?=$reqId?>&reqAttachId="+attach_id, 'Cetak');
    }

    function cetak(nama_dok)
    {
        window.open("down/cetak_agenda?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>&reqMode=<?=$reqMode?>&reqNamaDok="+nama_dok, 'Cetak');
    }
</script>
<div class="col-lg-12 col-konten-full">
    <div class="judul-halaman bg-course">
        <?
        if(!empty($reqMode))
        {
        ?>
        <a href="javascript:void(0)" onclick="setkembali();"><i class="fa fa-chevron-left"></i></a> 
        <?
        }
        ?>
        Agenda Nota Dinas 
        <?
        if(!empty($infostatus))
        {
        ?>
        (<?=$infostatus?>)
        <?
        }
        ?>
        <div class="btn-atas clearfix">
            <button class="btn btn-primary btn-sm pull-right" type="button" onClick="cetak('instruksi_direksi')"><i class="fa fa-print"></i> Cetak</button>
        </div>
    </div>
    <div class="konten-detil">

        <div class="table-responsive area-agenda-surat">
            <table class="table">
                <thead class="thead-light">
                    <tr class="active">
                        <th colspan="3">Informasi Surat</th>
                    </tr>
                </thead>
                <tbody>

                    <tr>
                        <td>Nomor Surat</td>
                        <td>:</td>
                        <td>
                            <ol class="list-unstyled">
                                <li><?=$infonomorsurat?></li>
                            </ol>
                        </td>
                    </tr>
                    <tr>
                        <td>Tanggal Surat</td>
                        <td>:</td>
                        <td><?=$infotanggalentri?></td>
                    </tr>
                    <tr>
                        <td>Sifat Surat</td>
                        <td>:</td>
                        <td><?=$infosifatnaskah?></td>
                    </tr>
                </tbody>
            </table>
            <table class="table">
                <thead class="thead-light">
                    <tr class="active">
                        <th colspan="3">Alamat Surat</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Penandatangan</td>
                        <td>:</td>
                        <td><?=$infojabatan?></td>
                    </tr>
                    <tr>
                        <td>Penerima</td>
                        <td>:</td>
                        <td>
                            <?
                            $indexdata= 0;
                            $arrKepada = json_decode($reqKepada);
                            foreach ($arrKepada as $key => $value) {
                            ?>
                            <ol class="list-unstyled">
                                <li><?=$value->SATUAN_KERJA?></li>
                            </ol>
                            <?
                            $indexdata++;
                            }

                            $arrKepadaKelompok = json_decode($reqKepadaKelompok);
                            foreach ($arrKepadaKelompok as $key => $value) {
                            ?>
                            <ol class="list-unstyled">
                                <li><?=$value->NAMA_KELOMPOK?></li>
                            </ol>
                            <?
                            $indexdata++;
                            }
                            ?>
                            <?
                            if($indexdata == 0)
                            {
                            ?>
                            <span>-</span>
                            <?
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Tembusan</td>
                        <td>:</td>
                        <td>
                            <?
                            $indexdata= 0;
                            $arrTembusan = json_decode($reqTembusan);
                            foreach ($arrTembusan as $key => $value) {
                            ?>
                            <ol class="list-unstyled">
                                <li><?=$value->SATUAN_KERJA?></li>
                            </ol>
                            <?
                            $indexdata++;
                            }

                            $arrTembusanKelompok = json_decode($reqTembusanKelompok);
                            foreach ($arrTembusanKelompok as $key => $value) {
                            ?>
                            <ol class="list-unstyled">
                                <li><?=$value->NAMA_KELOMPOK?></li>
                            </ol>
                            <?
                            $indexdata++;
                            }

                            if($indexdata == 0)
                            {
                            ?>
                            <span>-</span>
                            <?
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Pemesan</td>
                        <td>:</td>
                        <td><?=$infopemesanjabatan?></td>
                    </tr>
                    <tr>
                        <td>Catatan Pemesan</td>
                        <td>:</td>
                        <td><?=$infopemesansatuankerjaisi?></td>
                    </tr>
                </tbody>
            </table>
            <table class="table">
                <thead class="thead-light">
                    <tr class="active">
                        <th colspan="3">Perihal Surat</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Perihal</td>
                        <td>:</td>
                        <td><?=$infoperihal?></td>
                    </tr>
                    <tr>
                        <td>Lampiran</td>
                        <td>:</td>
                        <td>
                            <?
                            if($jumlahattachment == 0)
                            {
                            ?>
                            <ol class="list-unstyled">
                                <li>Tidak ada</li>
                            </ol>
                            <?
                            }
                            else
                            {
                            ?>
                            <ol class="pl-3">
                                <?
                                $arrexcept= [];
                                $arrexcept= array("xlsx", "xls", "doc", "docx", "ppt", "pptx", "txt");

                                for($index_data=0; $index_data < $jumlahattachment; $index_data++)
                                {
                                    $attnama= $arrattachment[$index_data]["NAMA"];
                                    $attukuran= $arrattachment[$index_data]["UKURAN"];
                                    $attlink= $arrattachment[$index_data]["ATTACHMENT"];
                                    $atttipe= $arrattachment[$index_data]["TIPE"];
                                    $attid= $arrattachment[$index_data]["SURAT_MASUK_ATTACHMENT_ID"];
                                    $atticon= infoiconlink($atttipe);
                                ?>
                                    <li>
                                        <div class="item">
                                            <div class="ikon"><?=$attnama?> <i class="fa <?=$atticon?>"></i></div>
                                            <div class="ukuran-file"><?=round(($attukuran/1024), 2)?> kb</div>
                                            <div class="hover-konten">
                                                <?
                                                if(in_array(strtolower($atttipe), $arrexcept)){}
                                                else
                                                {
                                                ?>
                                                <i style="cursor: pointer;" class="fa fa-eye" onclick="parent.openAdd('<?=base_url()."uploads/".$reqId."/".$attlink?>')"></i>
                                                <?
                                                }
                                                ?>
                                                <i style="cursor: pointer;" class="fa fa-download" onClick="down('<?=$attid?>')"></i>
                                            </div>
                                        </div>
                                    </li>
                                <?
                                }
                                ?>
                            </ol>
                            <?
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Dokumen </td>
                        <td>:</td>
                        <td>
                            <a class="btn btn-info btn-sm" href="javascript:void(0)" onclick="setlihatdokumen()">
                                <i aria-hidden="true" class="fa fa-eye"></i> Lihat surat
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>URL Google Drive</td>
                        <td>:</td>
                        <td>
                            <?
                            if(!empty($reqId))
                            {
                                $drive= new SuratMasuk();
                                $drive->selectByParamsSuratMasuk(array("A.SURAT_MASUK_ID"=>$reqId));
                                // echo $drive->query;exit;
                                $drive->firstRow();
                                $reqLampiranDrive= $drive->getField("LAMPIRAN_DRIVE");
                            ?>
                                <a href="<?=$reqLampiranDrive?>"><?=$reqLampiranDrive?></a>
                            <?
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Referensi</td>
                        <td>:</td>
                        <td>
                            <?
                            if(!empty($reqId))
                            {
                                $smref= new SuratMasukReference();
                                $smref->selectByParams(array("A.SURAT_MASUK_ID"=>$reqId));
                            }
                            ?>
                            <ol class='list-unstyled'>
                            <?
                            if(!empty($reqId))
                            {
                                while($smref->nextRow())
                                {
                                    $infosmrefid= $smref->getField("SM_REF_ID");
                                    $infosmrefnomor= $smref->getField("NOMOR");
                            ?>
                                <li><?=$infosmrefnomor?></li>
                            <?
                                }
                            }
                            ?>
                            </ol>
                        </td>
                    </tr>
                </tbody>
            </table>

            <table class="table">
                <thead class="thead-light">
                    <tr class="active">
                        <th colspan="2">Riwayat Konsep Surat</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="2">
                            <ol class="list-unstyled">
                                <?
                                for($index_data=0; $index_data < $jumlahlog; $index_data++)
                                {
                                ?>
                                <li>
                                    <span><?=$arrlog[$index_data]["TANGGAL"]?>, <?=$arrlog[$index_data]["INFORMASI"]?>, [<?=$arrlog[$index_data]["STATUS_SURAT"]?>].</span>
                                </li>
                                <li>
                                    <span><?=$arrlog[$index_data]["CATATAN"]?></span>
                                </li>
                                <li><span><br/></span></li>
                                <?
                                }
                                ?>
                            </ol>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>

</div>