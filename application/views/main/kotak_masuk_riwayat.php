<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");
$this->load->model("Disposisi");
$this->load->model("SuratMasuk");
$this->load->model("Pegawai");

$infoid= $reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqMode= $this->input->get("reqMode");
$reqStatusSurat= $this->input->get("reqStatusSurat");

$surat_masukSimple= new SuratMasuk();
// $statement= " AND SURAT_MASUK_ID = ".$reqId;
$surat_masukSimple->selectByParamsSimple(array("SURAT_MASUK_ID"=>$reqId), -1,-1 );
// echo $surat_masukSimple->query;exit;
$surat_masukSimple->firstRow();
$reqInfoNomor= $surat_masukSimple->getField("NOMOR");
$reqInfoPerihal= $surat_masukSimple->getField("PERIHAL");
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
// echo $set->query;exit;
while($set->nextRow())
{
    $arrdisposisi[$index_data]["DISPOSISI_ID"]= $set->getField("DISPOSISI_ID");
    $arrdisposisi[$index_data]["DISPOSISI_PARENT_ID"]= $set->getField("DISPOSISI_PARENT_ID");
    $arrdisposisi[$index_data]["INFO_STATUS_DISPOSISI"]= $set->getField("INFO_STATUS_DISPOSISI");
    $arrdisposisi[$index_data]["NAMA_SATKER_ASAL"]= $set->getField("NAMA_SATKER_ASAL");
    $arrdisposisi[$index_data]["NAMA_SATKER"]= $set->getField("NAMA_SATKER");
    $arrdisposisi[$index_data]["STATUS_DISPOSISI"]= $set->getField("STATUS_DISPOSISI");
    $arrdisposisi[$index_data]["STATUS_BANTU"]= $set->getField("STATUS_BANTU");
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
    for($i=0; $i < count($arrayKey); $i++)
    {
        $index_data= $arrayKey[$i];

        if(empty($infodisposisiid))
            $infodisposisiid= $arrdisposisi[$index_data]["DISPOSISI_ID"];
        else
            $infodisposisiid= $infodisposisiid.",".$arrdisposisi[$index_data]["DISPOSISI_ID"];
        // print_r($arrayKey);exit;
    }
}
// echo $infodisposisiid;exit;
// var_dump($arrdisposisi);exit;

$infonotatindakan= "";
$jumlahheader= 0;
if(!empty($infodisposisiid))
{
    $infodisposisiid= explode(",", $infodisposisiid);
    for($x=0; $x < count($infodisposisiid); $x++)
    {
        $arrayKey= in_array_column($infodisposisiid[$x], "DISPOSISI_PARENT_ID", $arrdisposisi);
        // print_r($arrayKey);exit;
        if(!empty($arrayKey))
        {
            for($i=0; $i < count($arrayKey); $i++)
            {
                $arrdata= [];
                $index_data= $arrayKey[$i];

                $arraycekduplikat= [];
                $arraycekduplikat= in_array_column($arrdisposisi[$index_data]["DISPOSISI_PARENT_ID"], "DISPOSISI_PARENT_ID", $arrheaderdisposisi);
                // print_r($arraycekduplikat);exit;
                if(empty($arraycekduplikat))
                {
                    if($i == 0)
                    {
                        $infonotatindakan= $arrdisposisi[$index_data]["ISI"];
                    }
                    $arrdata["DISPOSISI_ID"]= $arrdisposisi[$index_data]["DISPOSISI_ID"];
                    $arrdata["DISPOSISI_PARENT_ID"]= $arrdisposisi[$index_data]["DISPOSISI_PARENT_ID"];
                    $arrdata["INFO_STATUS_DISPOSISI"]= $arrdisposisi[$index_data]["INFO_STATUS_DISPOSISI"];
                    $arrdata["STATUS_DISPOSISI"]= $arrdisposisi[$index_data]["STATUS_DISPOSISI"];
                    $arrdata["DARI"]= $arrdisposisi[$index_data]["NAMA_SATKER_ASAL"];
                    $arrdata["KEPADA"]= $arrdisposisi[$index_data]["NAMA_SATKER"];
                    $arrdata["ISI"]= $arrdisposisi[$index_data]["ISI"];
                    $arrdata["KETERANGAN"]= $arrdisposisi[$index_data]["KETERANGAN"];
                    $arrdata["INFO_TANGGAL_DISPOSISI"]= $arrdisposisi[$index_data]["INFO_TANGGAL_DISPOSISI"];
                    array_push($arrheaderdisposisi, $arrdata);
                    // print_r($arrheaderdisposisi);exit;
                    $jumlahheader++;
                }
            }
        }
    }
}
$infonotatindakan= str_replace(",", "<br>- ", $infonotatindakan);
// print_r($arrheaderdisposisi);exit;

function ambildata($arrdisposisi, &$arrdetildisposisi, $infodisposisiid)
{
    $arrayKey= in_array_column($infodisposisiid, "DISPOSISI_PARENT_ID", $arrdisposisi);
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
            $arrdata["STATUS_DISPOSISI"]= $arrdisposisi[$index_data]["STATUS_DISPOSISI"];
            $arrdata["DARI"]= $arrdisposisi[$index_data]["NAMA_SATKER"];
            $arrdata["KEPADA"]= $arrdisposisi[$index_data]["NAMA_SATKER_ASAL"];
            $arrdata["ISI"]= $arrdisposisi[$index_data]["ISI"];
            $arrdata["KETERANGAN"]= $arrdisposisi[$index_data]["KETERANGAN"];
            $arrdata["INFO_TANGGAL_DISPOSISI"]= $arrdisposisi[$index_data]["INFO_TANGGAL_DISPOSISI"];
            array_push($arrdetildisposisi, $arrdata);

            ambildata($arrdisposisi, $arrdetildisposisi, $infodisposisiid);
            $jumlahheader++;
        }
    }
}

$infosuratstatus= "";
if(in_array("SURAT", explode(",", $this->USER_GROUP)))
{
    $setdetil= new SuratMasuk();
    $setdetil->selectByParamsInfoStatus(array("A.SURAT_MASUK_ID"=>$reqId));
    $setdetil->firstRow();
    $infosuratstatusnomor= $setdetil->getField("NOMOR");
    $infosuratstatuslabel= $setdetil->getField("INFO_STATUS");
    $infosuratstatuspersetujuan= $setdetil->getField("PERSETUJUAN_INFO");
    unset($setdetil);

    if(empty($infosuratstatusnomor))
    {
        $infosuratstatus= "1";
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

    function cetak()
    {
        window.open("down/cetak_riwayat?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>&reqMode=<?=$reqMode?>", 'Cetak');
    }

    function printExternal(url) {
        //alert("oke print");
        var printWindow = window.open( url, 'Print', 'left=200, top=200, width=950, height=500, toolbar=0, resizable=0');
    
        printWindow.addEventListener('load', function() {
            if (Boolean(printWindow.chrome)) {
                printWindow.print();
                setTimeout(function(){
                    printWindow.close();
                }, 500);
            } else {
                printWindow.print();
                //printWindow.close();
            }
        }, true);
    }
</script>
<div class="col-lg-12 col-konten-full">
	<!-- TAMPIL UNTUK : KOTAK KELUAR PENGIRIM  -->
	<div class="judul-halaman bg-course">
    	<?
        if(!empty($reqMode))
        {
        ?>
        <a href="javascript:void(0)" onclick="setkembali();"><i class="fa fa-chevron-left"></i></a> 
        <?
        }
        ?>
    	Riwayat

        <div class="btn-atas clearfix">
            <button class="btn btn-primary btn-sm pull-right" type="button" onclick="cetak()"><i class="fa fa-print"></i> Cetak</button>
        </div>
    </div>
    
    <!-- END TAMPIL UNTUK : KOTAK KELUAR PENGIRIM  -->
    
    
<div class="konten-detil">
    <?
    if($infosuratstatus == "1")
    {
    ?>
    <div class="judul-sub">Menunggu Persetujuan (<?=$infosuratstatuslabel?>)</div>
    <table>
        <thead>
            <tr>
                <td>
                    <?=$infosuratstatuspersetujuan?>
                </td>
            </tr>
        </thead>
    </table>
    <?
    }
    ?>

    <?
    if(empty(inforiwayatsurat($reqMode)))
    {
        $sr= new SuratMasuk();
        $sr->selectByParams(array("A.SURAT_MASUK_ID"=>$reqId));
        $sr->firstRow();
        $infojenisnaskahid= $sr->getField("JENIS_NASKAH_ID");

        $rwtbaca= new Disposisi();
        // $rwtbaca->selectByParams(array("A.STATUS_DISPOSISI"=>'TUJUAN', "A.SURAT_MASUK_ID"=>$reqId));
        $rwtbaca->selectByParams(array("A.SURAT_MASUK_ID"=>$reqId), -1,- 1, " AND A.STATUS_DISPOSISI IN ('TUJUAN', 'TEMBUSAN')");
        // $rwtbaca->firstRow();
        $infodisposisiterbacainfo= "";
        while($rwtbaca->nextRow())
        {
            $infoterbaca= $rwtbaca->getField("TERBACA_INFO");

            if(empty($infodisposisiterbacainfo))
                $infodisposisiterbacainfo= $infoterbaca;
            else
                $infodisposisiterbacainfo= $infodisposisiterbacainfo.";".$infoterbaca;
        }

        $infodetilbaca= "";
        if(!empty($infodisposisiterbacainfo))
        {
            $arrcheckterbaca= explode(";", $infodisposisiterbacainfo);
            // print_r($arrcheckterbaca);exit;
            $infodetilbaca= "1";
        }

        $rwt= new SuratMasuk();
        // if($infojenisnaskahid == "1")
        // {
            $rwt->selectByParamsRiwayatSuratStatusBantu(array(), -1,-1, " AND R_SM_ID = ".$reqId, "GROUP BY R_SM_ID, R_USER_ID_TUJUAN, R_NAMA_TUJUAN, R_STATUS_PEJABAT_GANTI, R_STATUS_BANTU");
        // }
        // else
        // {
        //     $rwt->selectByParamsRiwayatSurat(array(), -1,-1, " AND R_SM_ID = ".$reqId, "GROUP BY R_SM_ID, R_USER_ID_TUJUAN, R_NAMA_TUJUAN, R_TANGGAL, R_STATUS_PEJABAT_GANTI, R_STATUS_BANTU");
        // }
        // echo $rwt->query;exit;
    ?>
    <div class="judul-sub">Riwayat Surat</div>
	<ol  class="list-unstyled">
		<li>
		    <span><b>Nomor Surat :</b> <?=$reqInfoNomor?></span>
		</li>
		<li>
		    <span><b>Perihal :</b> <?=$reqInfoPerihal?></span>
		</li>
	</ol>

    <table>
        <thead>
            <tr>
                <th>Recipient</th>
                <th>Delivered</th>
                <th>Delivered Time</th>
                <th>Status Dibuka</th>
            </tr>
        </thead>
        <tbody>
            <?
            while($rwt->nextRow())
            {
                $rwtstatusbantu= $rwt->getField("R_STATUS_BANTU");
                $rwtjumlah= $rwt->getField("JUMLAH");
                $rwtidinfo= $rwt->getField("R_USER_ID_TUJUAN");
                $rwtinfo= $rwt->getField("R_NAMA_TUJUAN");
                $rwtstatusganti= $rwt->getField("R_STATUS_PEJABAT_GANTI");
                $rwttanggal= getFormattedInfoDateTimeCheck($rwt->getField("R_TANGGAL"),"/");
            ?>
            <tr>
                <td valign="top">
                    <?
                    // if($infojenisnaskahid == "1" && $rwtstatusbantu == "1")
                    if($rwtstatusbantu == "1")
                    {
                        echo $rwtinfo."<br/><b><i>User Bantu</i></b>";
                    }
                    else
                    {
                        echo $rwtinfo;
                    }
                    ?>
                </td>
                <td valign="top">
                    <?
                    if(empty($rwtstatusbantu))
                    {
                    ?>
                    <i class="fa fa-check" aria-hidden="true"></i>
                    <?
                    }
                    else
                    {
                        if($rwtjumlah > 0)
                        {
                    ?>
                        <i class="fa fa-check" aria-hidden="true"></i>
                    <?
                        }
                    }
                    ?>
                </td>
                <td valign="top">
                    <?
                    if(empty($rwtstatusbantu))
                    {
                    ?>
                    DELIVERED on <?=$rwttanggal?>
                    <?
                    }
                    else
                    {
                        if($rwtjumlah > 0)
                        {
                            $tempcheckterbaca= $arrcheckterbaca;
                            $infobantudetilbaca= "";
                            if(!empty($arrcheckterbaca) && !empty($infodisposisiterbacainfo))
                            {
                                $check= new SuratMasuk();
                                $check->selectByParamsBantu($rwtidinfo);
                                $check->firstRow();
                                $rwtidinfobantu= $check->getField("USER_BANTU");
                                unset($check);
                                // echo $rwtidinfobantu;exit;

                                while (list($key, $val) = each($arrcheckterbaca))
                                {
                                    $arrcheckterbacadetil= explode(",", $val);

                                    if(!empty($arrcheckterbacadetil[0]) && $arrcheckterbacadetil[0] == $rwtidinfobantu)
                                    {
                                        if(!empty($infobantudetilbaca))
                                        {
                                            $infobantudetilbaca.="<br>";
                                        }

                                        if($rwtstatusganti == "1")
                                        {
                                            $pegawaijabatan= $rwtinfo;
                                        }
                                        else
                                        {
                                            $pegawai= new Pegawai();
                                            $pegawai->selectByParams(array("A.PEGAWAI_ID"=>$arrcheckterbacadetil[0]));
                                            $pegawai->firstRow();
                                            $pegawaijabatan= $pegawai->getField("JABATAN");
                                            unset($pegawai);
                                        }

                                        $infobantudetilbaca.= getFormattedInfoDateTimeCheck($arrcheckterbacadetil[1], "/")." <br><i class='fa fa-folder-open' aria-hidden='true'></i> by ".$pegawaijabatan;
                                    }

                                }
                            }
                    ?>
                        <!-- FORWARDED -->
                        DELIVERED
                        on <?=$rwttanggal?>
                    <?
                        }
                    }
                    ?>
                </td>
                <td>
                    <?
                    if(!empty($infobantudetilbaca) && $rwtstatusbantu == "1")
                    {
                    ?>
                        <?=$infobantudetilbaca?>
                    <?
                    }
                    else
                    {
                        $tempcheckterbaca= $arrcheckterbaca;
                        $infodetilbaca= "";
                        if(!empty($arrcheckterbaca) && !empty($infodisposisiterbacainfo))
                        {
                            while (list($key, $val) = each($arrcheckterbaca))
                            {
                                $arrcheckterbacadetil= explode(",", $val);

                                if(!empty($arrcheckterbacadetil[0]) && $arrcheckterbacadetil[0] == $rwtidinfo)
                                {
                                    if(!empty($infodetilbaca))
                                    {
                                        $infodetilbaca.="<br>";
                                    }

                                    if($rwtstatusganti == "1")
                                    {
                                        $pegawaijabatan= $rwtinfo;
                                    }
                                    else
                                    {
                                        $pegawai= new Pegawai();
                                        $pegawai->selectByParams(array("A.PEGAWAI_ID"=>$arrcheckterbacadetil[0]));
                                        $pegawai->firstRow();
                                        $pegawaijabatan= $pegawai->getField("JABATAN");
                                        unset($pegawai);
                                    }

                                    $infodetilbaca.= getFormattedInfoDateTimeCheck($arrcheckterbacadetil[1], "/")." <br><i class='fa fa-folder-open' aria-hidden='true'></i> by ".$pegawaijabatan;
                                }

                            }
                            // echo $infodetilbaca;exit;
                            // print_r($arrcheckterbaca);exit;
                        }
                    ?>
                        <?=$infodetilbaca?>
                    <?
                        $arrcheckterbaca= $tempcheckterbaca;
                        // print_r($arrcheckterbaca);
                    }
                    ?>
                </td>
            </tr>
            <?
            }
            ?>
        </tbody>
    </table>
    <br>
    <?
    }
    ?>

    <div class="judul-sub">Riwayat Disposisi</div>
    <br>
    <div class="table-responsive area-agenda-surat">
        <ul class="nav nav-tabs">
            <?
            for($index_data=0; $index_data < $jumlahheader; $index_data++)
            {
                $infoid= $arrheaderdisposisi[$index_data]["DISPOSISI_ID"];
                // $infoheader= $arrheaderdisposisi[$index_data]["KEPADA"];
                $infoheader= $arrheaderdisposisi[$index_data]["DARI"];
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
            $infodisposisiparentid= $arrheaderdisposisi[$index_data]["DISPOSISI_PARENT_ID"];

            // $infodisposisiid= 13;
            $arrdetildisposisi= [];
            ambildata($arrdisposisi, $arrdetildisposisi, $infodisposisiparentid);
            // print_r($arrdetildisposisi);exit;

            $infoactive= "";
            if($index_data == 0)
            {
                $infoactive= "in active";

                $infostatusdisposisi= $arrheaderdisposisi[$index_data]["STATUS_DISPOSISI"];
                $infodari= $arrheaderdisposisi[$index_data]["DARI"];
                $infokepada= $arrheaderdisposisi[$index_data]["KEPADA"];
            }
        ?>
            <div id="tab-<?=$infodisposisiid?>" class="tab-pane fade <?=$infoactive?>">
                <ol class="list-unstyled">

                <!-- <li>
                    <?=$infodisposisiid?>
                    <span style="font-weight: bold;"><?=$arrheaderdisposisi[$index_data]["INFO_STATUS_DISPOSISI"]?> Tanggal <?=$arrheaderdisposisi[$index_data]["INFO_TANGGAL_DISPOSISI"]?></span>
                </li>
                <li>
                    <span><b>Dari :</b> <?=$infodari?></span>
                </li>
                <li>
                    <span><b>Kepada :</b> <?=$infokepada?></span>
                </li>
                <li>
                    <span><b>Nota Tindakan :</b><br/>- <?=$infonotatindakan?><br/></span>
                </li>
                <li class="catatan">
                    <span><b>Catatan :</b><br/><?=$arrheaderdisposisi[$index_data]["KETERANGAN"]?><br/></span>
                </li> -->

                <?
                if(empty($arrdetildisposisi)){}
                else
                {
                ?>
                    <?
                    for($i=0; $i < count($arrdetildisposisi); $i++)
                    {
                        $infodari= $arrdetildisposisi[$i]["KEPADA"];
                        $infokepada= $arrdetildisposisi[$i]["DARI"];
                    ?>
                    <li>
                        <span style="font-weight: bold;"><?=$arrdetildisposisi[$i]["INFO_STATUS_DISPOSISI"]?> Tanggal <?=$arrdetildisposisi[$i]["INFO_TANGGAL_DISPOSISI"]?></span>
                    </li>
                    <li>
                        <span><b>Dari :</b> <?=$infodari?></span>
                    </li>
                    <li>
                        <span><b>Kepada :</b> <?=$infokepada?></span>
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