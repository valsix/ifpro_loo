<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");
$this->load->model("Disposisi");

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

$arrdisposisi= array();
$index_data= 0;
$set= new Disposisi();
$set->selectByParams(array("A.SURAT_MASUK_ID"=>$reqId),-1,-1, "", "ORDER BY A.TANGGAL_DISPOSISI");
// $set->selectByParams(array("A.SURAT_MASUK_ID"=>$reqId, "A.DISPOSISI_ID"=>$reqRowId),-1,-1, "", "ORDER BY A.TANGGAL_DISPOSISI");
// echo $set->query;exit;
while($set->nextRow())
{
    $arrdisposisi[$index_data]["DISPOSISI_ID"]= $set->getField("DISPOSISI_ID");
    $arrdisposisi[$index_data]["DISPOSISI_PARENT_ID"]= $set->getField("DISPOSISI_PARENT_ID");
    $arrdisposisi[$index_data]["INFO_STATUS_DISPOSISI"]= $set->getField("INFO_STATUS_DISPOSISI");
    $arrdisposisi[$index_data]["NAMA_SATKER_ASAL"]= $set->getField("NAMA_SATKER_ASAL");
    $arrdisposisi[$index_data]["NAMA_SATKER"]= $set->getField("NAMA_SATKER");
    // NAMA_USER_ASAL
    // NAMA_USER
    $arrdisposisi[$index_data]["ISI"]= $set->getField("ISI");
    $arrdisposisi[$index_data]["KETERANGAN"]= $set->getField("KETERANGAN");
    $arrdisposisi[$index_data]["INFO_TANGGAL_DISPOSISI"]= getFormattedInfoDateTimeCheck($set->getField("INFO_TANGGAL_DISPOSISI"), "/");
    $index_data++;
}
// print_r($arrdisposisi);exit;

$arrheaderdisposisi= array();
$infodisposisiid= "";
$arrayKey= in_array_column("0", "DISPOSISI_PARENT_ID", $arrdisposisi);

if(!empty($arrayKey))
{
    $index_data= $arrayKey[0];
    $infodisposisiid= $arrdisposisi[$index_data]["DISPOSISI_ID"];
    // print_r($arrayKey);exit;
}

$infonotatindakan= "";
$jumlahheader= 0;
if(!empty($infodisposisiid))
{
    // $arrayKey= in_array_column($infodisposisiid, "DISPOSISI_PARENT_ID", $arrdisposisi);
    $arrayKey= in_array_column($reqRowId, "DISPOSISI_ID", $arrdisposisi);
    // print_r($arrayKey);exit;
    if(!empty($arrayKey))
    {
        for($i=0; $i < count($arrayKey); $i++)
        {
            $arrdata= [];
            $index_data= $arrayKey[$i];
            if($i == 0)
            {
                $infonotatindakan= $arrdisposisi[$index_data]["ISI"];
            }
            $arrdata["DISPOSISI_ID"]= $arrdisposisi[$index_data]["DISPOSISI_ID"];
            $arrdata["DISPOSISI_PARENT_ID"]= $arrdisposisi[$index_data]["DISPOSISI_PARENT_ID"];
            $arrdata["INFO_STATUS_DISPOSISI"]= $arrdisposisi[$index_data]["INFO_STATUS_DISPOSISI"];
            $arrdata["DARI"]= $arrdisposisi[$index_data]["NAMA_SATKER_ASAL"];
            $arrdata["KEPADA"]= $arrdisposisi[$index_data]["NAMA_SATKER"];
            $arrdata["ISI"]= $arrdisposisi[$index_data]["ISI"];
            $arrdata["KETERANGAN"]= $arrdisposisi[$index_data]["KETERANGAN"];
            $arrdata["INFO_TANGGAL_DISPOSISI"]= $arrdisposisi[$index_data]["INFO_TANGGAL_DISPOSISI"];
            array_push($arrheaderdisposisi, $arrdata);
            $jumlahheader++;
        }
    }
}
$infonotatindakan= str_replace(",", "<br>- ", $infonotatindakan);
// print_r($arrheaderdisposisi);exit;

function ambildata($arrdisposisi, &$arrdetildisposisi, $infodisposisiid)
{
    $arrayKey = in_array_column($infodisposisiid, "DISPOSISI_PARENT_ID", $arrdisposisi);
    // print_r($arrayKey);exit;
    if(!empty($arrayKey))
    {
        for($i=0; $i < count($arrayKey); $i++)
        {
            $arrdata= [];
            $index_data= $arrayKey[$i];
            $infodisposisiid= $arrdisposisi[$index_data]["DISPOSISI_ID"];
            $arrdata["DISPOSISI_ID"]= $infodisposisiid;
            $arrdata["DISPOSISI_PARENT_ID"]= $arrdisposisi[$index_data]["DISPOSISI_PARENT_ID"];
            $arrdata["INFO_STATUS_DISPOSISI"]= $arrdisposisi[$index_data]["INFO_STATUS_DISPOSISI"];
            // $arrdata["DARI"]= $arrdisposisi[$index_data]["NAMA_SATKER"];
            // $arrdata["KEPADA"]= $arrdisposisi[$index_data]["NAMA_SATKER_ASAL"];
            $arrdata["DARI"]= $arrdisposisi[$index_data]["NAMA_SATKER_ASAL"];
            $arrdata["KEPADA"]= $arrdisposisi[$index_data]["NAMA_SATKER"];
            $arrdata["ISI"]= $arrdisposisi[$index_data]["ISI"];
            $arrdata["KETERANGAN"]= $arrdisposisi[$index_data]["KETERANGAN"];
            $arrdata["INFO_TANGGAL_DISPOSISI"]= $arrdisposisi[$index_data]["INFO_TANGGAL_DISPOSISI"];
            array_push($arrdetildisposisi, $arrdata);

            ambildata($arrdisposisi, $arrdetildisposisi, $infodisposisiid);
            $jumlahheader++;
        }
    }
}
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
        Riwayat Disposisi
    </div>
    <div class="konten-detil">

        <div class="table-responsive area-agenda-surat">
            <ul class="nav nav-tabs">
                <?
                for($index_data=0; $index_data < $jumlahheader; $index_data++)
                {
                    $infoid= $arrheaderdisposisi[$index_data]["DISPOSISI_ID"];
                    $infoheader= $arrheaderdisposisi[$index_data]["KEPADA"];
                    $infoactive= "";
                    if($index_data == 0)
                    {
                        $infoactive= "active";
                    }
                ?>
                    <li class="<?=$infoactive?>"><a data-toggle="tab" href="#tab-<?=$infoid?>"><?=$infoheader?></a></li>
                <?
                }
                ?>
            </ul>

            <div class="tab-content">
            <?
            for($index_data=0; $index_data < $jumlahheader; $index_data++)
            {
                $infodisposisiid= $arrheaderdisposisi[$index_data]["DISPOSISI_ID"];
                // $infodisposisiid= 13;
                $arrdetildisposisi= [];
                ambildata($arrdisposisi, $arrdetildisposisi, $infodisposisiid);
                // print_r($arrdetildisposisi);exit;

                $infoactive= "";
                if($index_data == 0)
                {
                    $infoactive= "in active";
                }
            ?>
                <div id="tab-<?=$infodisposisiid?>" class="tab-pane fade <?=$infoactive?>">
                    <ol class="list-unstyled">

                    <li>
                        <span style="font-weight: bold;"><?=$arrheaderdisposisi[$index_data]["INFO_STATUS_DISPOSISI"]?> Tanggal <?=$arrheaderdisposisi[$index_data]["INFO_TANGGAL_DISPOSISI"]?></span>
                    </li>
                    <li>
                        <span><b>Dari :</b> <?=$arrheaderdisposisi[$index_data]["DARI"]?></span>
                    </li>
                    <li>
                        <span><b>Kepada :</b> <?=$arrheaderdisposisi[$index_data]["KEPADA"]?></span>
                    </li>
                    <li>
                        <span><b>Nota Tindakan :</b><br/>- <?=$infonotatindakan?><br/></span>
                    </li>
                    <li class="catatan">
                        <span><b>Catatan :</b><br/><?=$arrheaderdisposisi[$index_data]["KETERANGAN"]?><br/></span>
                    </li>

                    <?
                    if(empty($arrdetildisposisi)){}
                    else
                    {
                    ?>
                        <?
                        for($i=0; $i < count($arrdetildisposisi); $i++)
                        {
                        ?>
                        <li>
                            <span style="font-weight: bold;"><?=$arrdetildisposisi[$i]["INFO_STATUS_DISPOSISI"]?> Tanggal <?=$arrdetildisposisi[$i]["INFO_TANGGAL_DISPOSISI"]?></span>
                        </li>
                        <li>
                            <span><b>Dari :</b> <?=$arrdetildisposisi[$i]["DARI"]?></span>
                        </li>
                        <li>
                            <span><b>Kepada :</b> <?=$arrdetildisposisi[$i]["KEPADA"]?></span>
                        </li>
                        <li>
                            <span><b>Nota Tindakan :</b><br/>- <?=$infonotatindakan?><br/></span>
                        </li>
                        <li class="catatan">
                            <span><b>Catatan :</b><br/><?=$arrdetildisposisi[$i]["KETERANGAN"]?><br/></span>
                        </li>
                        <?
                        }
                        ?>
                    <?
                    }
                    ?>
                    </ol>
                </div>
            <?
            }
            ?>
            </div>

            <?
            if($jumlahheader == 0)
            {
            ?>
            <ol class="list-unstyled">
                <li>Data disposisi belum ada.</li>
            </ol>
            <?
            }
            ?>
    </div>
</div>