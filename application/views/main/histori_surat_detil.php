<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model("HistoriSurat");

$infoid= $reqId= $this->input->get("reqId");
$reqMode= $this->input->get("reqMode");

/*A.SURAT_OLD_ID, A.TAHUN, A.TIPE_SURAT, A.NOMOR_SURAT, A.NOMOR_SURAT_KONVERSI
            , A.FILE_PATH, A.QRCODE_PATH
            , TO_CHAR(A.TANGGAL_SURAT, 'YYYY-MM-DD') TANGGAL_SURAT, A.PERIHAL, A.PEMBUAT_NIP, A.PEMBUAT_NAMA
            , ambilhistorikepada(A.SURAT_OLD_ID) NAMA_TUJUAN
            , CASE A.TIPE_SURAT
            WHEN 'suratmasukmanual' THEN 'Surat Masuk Manual'
            WHEN 'suratedaran' THEN 'Surat Edaran'
            WHEN 'suratkeluar' THEN 'Surat Keluar'
            WHEN 'notadinas' THEN 'Nota Dinas' END TIPE_NAMA*/

$set= new HistoriSurat();
$statement= " AND A.SURAT_OLD_ID = ".$reqId;
$set->selectByParams(array(), -1,-1, $statement);
$set->firstRow();
// echo $set->query;exit;
$infotipenama= $set->getField("TIPE_NAMA");
$infonomorsurat= $set->getField("NOMOR_SURAT_KONVERSI");
$infotanggalentri= getFormattedExtDateTimeCheck($set->getField("TANGGAL_SURAT"), false);
$infopembuatnip= $set->getField("PEMBUAT_NIP");
$infopembuatnama= $set->getField("PEMBUAT_NAMA");
$infofile= $set->getField("FILE_PATH_NEW");
$infofileqr= $set->getField("QRCODE_NEW");

if(!empty($infopembuatnip))
    $infopembuatnama= $infopembuatnip." - ".$infopembuatnama;

$infonamatujuan= $set->getField("NAMA_TUJUAN");
$infoperihal= $set->getField("PERIHAL");


$arrattachment= array();
$index_data= 0;
$setdetil= new HistoriSurat();
$setdetil->selectByParamsLampiran(array("A.SURAT_OLD_ID" => (int)$reqId));
while($setdetil->nextRow())
{
    $infopathfile= $setdetil->getField("FILE_PATH_NEW");
    // echo $infopathfile;exit;
    $namafile= end(explode('/', $infopathfile));
    $tipenamafile= end(explode('.', $namafile));
    // echo $namafile;exit;
    $arrattachment[$index_data]["NAMA"] = $namafile;
    $arrattachment[$index_data]["TIPE"] = $tipenamafile;
    $arrattachment[$index_data]["ATTACHMENT"] = base_url().$infopathfile;
    $index_data++;
}
$jumlahattachment= $index_data;

$arrdisposisi= array();
$index_data= 0;
$setdetil= new HistoriSurat();
$setdetil->selectByParamsDisposisi(array("A.SURAT_OLD_ID" => (int)$reqId));
// echo $setdetil->query;exit;
while($setdetil->nextRow())
{
    $infopathfile= $setdetil->getField("PATH_DISPOSISI_NEW");
    // echo $infopathfile;exit;
    $namafile= end(explode('/', $infopathfile));
    $tipenamafile= end(explode('.', $namafile));
    // echo $namafile;exit;
    $arrdisposisi[$index_data]["NAMA"] = $namafile;
    $arrdisposisi[$index_data]["TIPE"] = $tipenamafile;
    $arrdisposisi[$index_data]["ATTACHMENT"] = base_url().$infopathfile;
    $index_data++;
}
$jumlahdisposisi= $index_data;
?>
<script type="text/javascript">
    function setkembali()
    {
        document.location.href= 'main/index/<?=$reqMode?>';
    }

    function setlihatdokumen(attach_id)
    {
        newWindow = window.open(attach_id, 'Cetak');
        newWindow.focus();
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
        Histori <?=$infotipenama?>
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
                        <td>Pembuat</td>
                        <td>:</td>
                        <td><?=$infopembuatnama?></td>
                    </tr>
                    <tr>
                        <td>Penerima</td>
                        <td>:</td>
                        <td><?=$infonamatujuan?></td>
                    </tr>
                    <tr>
                        <td>File Surat</td>
                        <td>:</td>                      
                        <td><i style="cursor: pointer;" class="fa fa-eye" onclick="parent.openAdd('<?=$infofile?>')"></i>                        
                        <i style="cursor: pointer;" class="fa fa-download" onClick="setlihatdokumen('<?=$infofile?>')"></i></td> 
                    </tr>
                    <tr>
                        <td>QR Code</td>
                        <td>:</td>                      
                        <td><i style="cursor: pointer;" class="fa fa-eye" onclick="parent.openAdd('<?=$infofileqr?>')"></i>                        
                        <i style="cursor: pointer;" class="fa fa-download" onClick="setlihatdokumen('<?=$infofileqr?>')"></i></td> 
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
                                    $atticon= infoiconlink($atttipe);
                                ?>
                                    <li>
                                        <div class="item">
                                            <div class="ikon"><?=$attnama?> <i class="fa <?=$atticon?>"></i></div>
                                            <!-- <div class="ukuran-file"><?=round(($attukuran/1024), 2)?> kb</div> -->
                                            <div class="hover-konten">
                                                <?
                                                if(in_array(strtolower($atttipe), $arrexcept)){}
                                                else
                                                {
                                                ?>
                                                <i style="cursor: pointer;" class="fa fa-eye" onclick="parent.openAdd('<?=$attlink?>')"></i>
                                                <?
                                                }
                                                ?>
                                                <i style="cursor: pointer;" class="fa fa-download" onClick="setlihatdokumen('<?=$attlink?>')"></i>
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
                    <!-- <tr>
                        <td>Dokumen </td>
                        <td>:</td>
                        <td>
                            <a class="btn btn-info btn-sm" href="javascript:void(0)" onclick="setlihatdokumen()">
                                <i aria-hidden="true" class="fa fa-eye"></i> Lihat surat
                            </a>
                        </td>
                    </tr> -->
                </tbody>
            </table>
            <table class="table">
                <thead class="thead-light">
                    <tr class="active">
                        <th colspan="3">Disposisi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Lampiran</td>
                        <td>:</td>
                        <td>
                            <?
                            if($jumlahdisposisi == 0)
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

                                for($index_data=0; $index_data < $jumlahdisposisi; $index_data++)
                                {
                                    $attnama= $arrdisposisi[$index_data]["NAMA"];
                                    $attukuran= $arrdisposisi[$index_data]["UKURAN"];
                                    $attlink= $arrdisposisi[$index_data]["ATTACHMENT"];
                                    $atttipe= $arrdisposisi[$index_data]["TIPE"];
                                    $atticon= infoiconlink($atttipe);
                                ?>
                                    <li>
                                        <div class="item">
                                            <div class="ikon"><?=$attnama?> <i class="fa <?=$atticon?>"></i></div>
                                            <!-- <div class="ukuran-file"><?=round(($attukuran/1024), 2)?> kb</div> -->
                                            <div class="hover-konten">
                                                <?
                                                if(in_array(strtolower($atttipe), $arrexcept)){}
                                                else
                                                {
                                                ?>
                                                <i style="cursor: pointer;" class="fa fa-eye" onclick="parent.openAdd('<?=$attlink?>')"></i>
                                                <?
                                                }
                                                ?>
                                                <i style="cursor: pointer;" class="fa fa-download" onClick="setlihatdokumen('<?=$attlink?>')"></i>
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
                    <!-- <tr>
                        <td>Dokumen </td>
                        <td>:</td>
                        <td>
                            <a class="btn btn-info btn-sm" href="javascript:void(0)" onclick="setlihatdokumen()">
                                <i aria-hidden="true" class="fa fa-eye"></i> Lihat surat
                            </a>
                        </td>
                    </tr> -->
                </tbody>
            </table>

        </div>

    </div>

</div>