<?php
include_once("functions/string.func.php");
include_once("functions/date.func.php");
$this->load->library('suratmasukinfo');
$this->load->model("SuratMasuk");
$this->load->model("Disposisi");

$suratmasukinfo= new suratmasukinfo();

$reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqMode= $this->input->get("reqMode");

$set= new SuratMasuk();
$set->selectByParams(array("A.SURAT_MASUK_ID"=>$reqId));
$set->firstRow();
// echo $set->query;exit;
$infoperihal= $set->getField("PERIHAL");
$lampirandrive= $set->getField("LAMPIRAN_DRIVE");
$infojenisnaskahid= $set->getField("JENIS_NASKAH_ID");
unset($set);

if(!empty($reqRowId))
{
    $statement= " AND A.STATUS_SURAT = 'POSTING' AND A.SURAT_MASUK_ID = ".$reqId." AND B.DISPOSISI_ID = ".$reqRowId;
    $infouserid= $this->ID;

    $cekvalidasi= "";
    $set= new SuratMasuk();
    $lihatquery= "";
    if($reqMode == "kotak_masuk_disposisi")
    {
        if($infouserid == "KKP_01")
        {
            $lihatquery= "1";
        }

        if($this->SATUAN_KERJA_ID_ASAL_ASLI == $this->SATUAN_KERJA_ID_ASAL){}
        else
        {
            $infouserid= $infouserid."pejabatpengganti".$this->SATUAN_KERJA_ID_ASAL;
        }
        $set->selectByParamsDisposisi(array(), -1, -1, $infouserid, $statement);
        $cekvalidasi= "1";
    }
    elseif($reqMode == "kotak_masuk_tanggapan")
    {
        $set->selectByParamsTanggapanDisposisi(array(), -1, -1, $infouserid, $statement);
        $cekvalidasi= "1";
    }
    elseif($reqMode == "kotak_keluar_disposisi")
    {
        $set->selectByParamsKeluarDisposisi(array(), -1, -1, $infouserid, $statement);
        $cekvalidasi= "1";
    }
    elseif($reqMode == "kotak_keluar_tanggapan")
    {
        $set->selectByParamsTanggapanKeluarDisposisi(array(), -1, -1, $infouserid, $statement);
        $cekvalidasi= "1";
    }

    if(!empty($lihatquery))
    {
        // echo $set->query;exit;
    }
    
    if($set->firstRow() == false && !empty($cekvalidasi))
    {
        redirect("main/index/".$reqMode);
    }
    
    $set= new SuratMasuk();
    $set->selectByParamsInfoDisposisi(array("A.DISPOSISI_ID"=>$reqRowId));
    $set->firstRow();
    // echo $set->query;exit;
    // $infodisposisiid= $set->getField("DISPOSISI_ID");
    // $infodisposisiparentid= $set->getField("DISPOSISI_PARENT_ID");
    $infosatuankerjaidtujuan= $set->getField("SATUAN_KERJA_ID_TUJUAN");
    $infodisposisistatus= $set->getField("STATUS_DISPOSISI");
    $infodisposisiasal= $set->getField("NAMA_SATKER_ASAL");
    $infodisposisiuser= $set->getField("NAMA_USER_ASAL");
    $infodisposisiasalkepada= $set->getField("NAMA_SATKER");
    $infodisposisiuserkepada= $set->getField("NAMA_USER");
    $infodisposisitanggal= getFormattedInfoDateTimeCheck($set->getField("TANGGAL_DISPOSISI"));
    $infodisposisikepada= $set->getField("INFO_KEPADA");
    $infodisposisitindakan= $set->getField("TINDAKAN");
    $infodisposisicatatan= $set->getField("CATATAN");
    $infodisposisisifat= $set->getField("SIFAT_NAMA");
    $infodisposisiuserid= $set->getField("USER_ID");
    $infodisposisiusermutasiid= $set->getField("NIP_MUTASI");
    $infodisposisiterbalas= $set->getField("TERBALAS");
    $infodisposisiterbacainfo= $set->getField("TERBACA_INFO");
    unset($set);

    if($infouserid == $infodisposisiuserid)
        $setterbaca= $infodisposisiuserid.",".date("Y-m-d H:i:s");
    else
        $setterbaca= $infouserid.",".date("Y-m-d H:i:s");

    $simpan= "";
    if(!empty($infodisposisiuserid))
    {
        $simpan= "";
    }

    // echo $simpan.$infodisposisiuserid;exit;
        
    $arrcheckterbaca= explode(";", $infodisposisiterbacainfo);
    if(!empty($arrcheckterbaca) && !empty($infodisposisiterbacainfo))
    {
        while (list($key, $val) = each($arrcheckterbaca))
        {
            $arrcheckterbacadetil= explode(",", $val);
            if($infodisposisiuserid == $arrcheckterbacadetil[0] && $infouserid == $arrcheckterbacadetil[0])
            {
                $simpan= "1";
                break;
            }

            if($infouserid == $arrcheckterbacadetil[0])
            {
                $simpan= "1";
                break;
            }
        }
        // echo $simpan;
        // print_r($arrcheckterbaca);exit;
    }

    if(!empty($infodisposisiusermutasiid))
    {
        $simpan= "";
        if($infouserid == $infodisposisiusermutasiid)
            $setterbaca= $infodisposisiusermutasiid.",".date("Y-m-d H:i:s");
        else
            $setterbaca= $infouserid.",".date("Y-m-d H:i:s");
    }

    $arrcheckterbaca= explode(";", $infodisposisiterbacainfo);
    if(!empty($arrcheckterbaca) && !empty($infodisposisiterbacainfo))
    {
        while (list($key, $val) = each($arrcheckterbaca))
        {
            $arrcheckterbacadetil= explode(",", $val);
            if($infodisposisiusermutasiid == $arrcheckterbacadetil[0] && $infouserid == $arrcheckterbacadetil[0])
            {
                $simpan= "1";
                break;
            }

            if($infouserid == $arrcheckterbacadetil[0])
            {
                $simpan= "1";
                break;
            }
        }
        // echo $simpan;
        // print_r($arrcheckterbaca);exit;
    }

    if(empty($simpan))
    {
        // echo $infodisposisiterbacainfo;exit;
        if(empty($infodisposisiterbacainfo))
        {
            $infodisposisiterbacainfo= $setterbaca;
        }
        else
        {
            $infodisposisiterbacainfo= $infodisposisiterbacainfo.";".$setterbaca;
        }
        $setdetil= new Disposisi();
        $setdetil->setField("DISPOSISI_ID", $reqRowId);
        $setdetil->setField("TERBACA_INFO", $infodisposisiterbacainfo);
        $setdetil->updateterbaca();
        // echo $setdetil->query;exit;
    }

    // if($reqMode == "kotak_keluar_tanggapan")
    // {
    //     $set= new SuratMasuk();
    //     $set->selectByParamsTanggapanKeluarDisposisi(array("A.SURAT_MASUK_ID"=>$reqId), -1,-1, $this->ID);
    //     $set->firstRow();
    //     $infodisposisiid= $set->getField("DISPOSISI_ID");
    //     if($infodisposisiid == $reqRowId)
    //     {
    //         // echo $infodisposisiid."Asd".$reqRowId;exit;
    //     }
    //     // echo $set->query;exit;
        
    // }

}

$kondisilihat= "";
if($infodisposisistatus == "BALASAN"){}
else
    $kondisilihat= "1";

$arrlink= $suratmasukinfo->infolinkdetil($infojenisnaskahid);
$infolinkdetil= $arrlink["linkstatusdetil"];
// print_r($arrlink);exit;

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
    $index_data++;
}
$jumlahattachment= $index_data;

if($infojenisnaskahid == "1")
{
    // exit;
    redirect("main/index/surat_masuk_manual_lihat?reqMode=".$reqMode."&reqId=".$reqId."&reqRowId=".$reqRowId);
}

$sessionsatuankerjaidasal= $this->SATUAN_KERJA_ID_ASAL;
$aksidisposisi= "1";
if($sessionsatuankerjaidasal == $infosatuankerjaidtujuan)
    $aksidisposisi= "";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>

<head>

    <base href="<?= base_url() ?>">
    <meta http-equiv="Content-type" content="text/html; charset=UTF-8">
    <title>Diklat</title>
    <link rel="shortcut icon" type="image/ico" href="http://www.datatables.net/media/images/favicon.ico">
    <!--<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="http://www.datatables.net/rss.xml">-->
    <link href="<?= base_url() ?>css/admin.css" rel="stylesheet" type="text/css">

    <!-- Custom Fonts -->
    <link href="<?= base_url() ?>lib/startbootstrap-sb-admin-2-1.0.7/bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <style type="text/css" media="screen">
        @import "<?= base_url() ?>lib/media/css/site_jui.css";
        @import "<?= base_url() ?>lib/media/css/demo_table_jui.css";
        @import "<?= base_url() ?>lib/media/css/themes/base/jquery-ui.css";
    </style>

    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>lib/DataTables-1.10.6/media/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>lib/DataTables-1.10.6/examples/resources/syntax/shCore.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>lib/DataTables-1.10.6/examples/resources/demo.css">

    <script type="text/javascript" language="javascript" src="<?= base_url() ?>lib/DataTables-1.10.6/media/js/jquery.dataTables.js"></script>
    <script type="text/javascript" language="javascript" src="<?= base_url() ?>lib/DataTables-1.10.6/examples/resources/syntax/shCore.js"></script>
    <script type="text/javascript" language="javascript" src="<?= base_url() ?>lib/DataTables-1.10.6/examples/resources/demo.js"></script>
    <script type="text/javascript" language="javascript" src="<?= base_url() ?>lib/DataTables-1.10.6/extensions/FixedColumns/js/dataTables.fixedColumns.js"></script>
    <script type="text/javascript" language="javascript" src="<?= base_url() ?>lib/DataTables-1.10.6/extensions/TableTools/js/dataTables.tableTools.min.js"></script>
    <script src="<?= base_url() ?>lib/print.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>lib/print.min.css">


    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>lib/easyui/themes/default/easyui.css">
    <script type="text/javascript" src="<?= base_url() ?>lib/easyui/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>lib/easyui/kalender-easyui.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>lib/easyui/globalfunction.js"></script>

    <link href="<?= base_url() ?>lib/media/themes/main_datatables.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url() ?>css/begron.css" rel="stylesheet" type="text/css">
    <link href="<?= base_url() ?>css/bluetabs.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="<?= base_url() ?>js/dropdowntabs.js"></script>
    
    <script type="text/javascript">
        function setkembali()
        {
            document.location.href = 'main/index/<?=$reqMode?>';
        }

        function setagenda()
        {
            document.location.href = 'main/index/<?=$infolinkdetil?>?reqMode=<?=$reqMode?>_detil&reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>';
        }

        function setdisposisi()
        {
            document.location.href = 'main/index/kotak_masuk_input?reqMode=<?=$reqMode?>_detil&reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>';
        }

        function setbalas()
        {
            document.location.href = 'main/index/kotak_masuk_balas?reqMode=<?=$reqMode?>_detil&reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>';
        }

        function setriwayat()
        {
            document.location.href = 'main/index/kotak_masuk_disposisi_riwayat?reqMode=<?=$reqMode?>_detil&reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>';
        }
    </script>
    
    <script>
    $(document).ready(function(){
      $(".info-disposisi-lihat").click(function(){
        $(".konten-disposisi-lihat").toggle(1000);
      });
    });
    </script>
</head>

<body style="overflow:hidden;">
    <div id="begron"><img src="<?= base_url() ?>images/bg-kanan.jpg" width="100%" height="100%" alt="Smile"></div>
    <div id="wadah">
        <div class="judul-halaman">
            <?
            if(!empty($reqMode))
            {
            ?>
            <a href="javascript:void(0)" onclick="setkembali()"><i class="fa fa-chevron-left" aria-hidden="true"></i></a> 
            <?
            }
            ?>
            <span class="info-perihal"><?=$infoperihal?></span> 
            <div class="dropdown yamm-fw notifikasi pull-right area-button-judul">
                <a href="#" data-toggle="dropdown" class="dropdown-toggle"><i class="fa fa-ellipsis-v" aria-hidden="true"></i></a>
                <ul class="dropdown-menu">
                    <li>
                        <div class="area-3dots-menu">
                            <?
                            if(empty(infobuttonaksi($reqMode)))
                            {
                            ?>
                            <div><a href="javascript:void(0)">Buat Disposisi</a></div>
                            <?
                            }
                            else
                            {
                                if(empty($aksidisposisi))
                                {
                            ?>
                            <div><a href="javascript:void(0)" onclick="setdisposisi()">Buat Disposisi</a></div>
                            <?
                                }
                            }
                            ?>
                            <div><a id="btnPrintDisposisi"><i class="fa fa-print"></i> Print Disposisi</a></div>
                            <div><a href="javascript:void(0)" onclick="setagenda()">Agenda Surat</a></div>
                            <div><a href="app/loadUrl/report/template/?reqId=<?=$reqId?>" target="_blank"><i class="fa fa-print"></i> Print Surat</a></div>
                          <!--   <div><a href="javascript:void(0)" onclick="printJS({printable:'<?= base_url() ?>uploads/<?=$reqId?>/000002PST1310000.pdf', type:'pdf', showModal:true})">
                                Print Surat
                            </a></div> -->
                    
                            <div><a href="javascript:void(0)" onclick="setriwayat()">Riwayat Disposisi</a></div>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="dropdown yamm-fw notifikasi pull-right area-button-judul">
                <?
                if(empty(infobuttonaksi($reqMode)))
                {
                ?>
                <!-- <a href="javascript:void(0)"><i class="fa fa-reply" aria-hidden="true"></i></a> -->
                <?
                }
                else
                {
                    // kalau terbalas kosong muncul tombol nya
                    if(empty($infodisposisiterbalas) && empty($aksidisposisi))
                    {
                ?>
                <a href="javascript:void(0)" onclick="setbalas()"><i class="fa fa-reply" aria-hidden="true"></i></a>
                <?
                    }
                }
                ?>
            </div>
            <div class="dropdown yamm-fw notifikasi pull-right area-button-judul">
                <a class="info-disposisi-lihat"><span>Info Disposisi</span> <i class="fa fa-info-circle"></i></a>
            </div>
            
        </div>        

        <div class="konten-disposisi-lihat">
            <div class="user-pengirim">
                <div class="ikon"><i class="fa fa-user-circle-o" aria-hidden="true"></i></div>
                <div class="data">
                    <div class="jabatan"><?=$infodisposisiasal?></div>
                    <div class="nama"><?=$infodisposisiuser?></div>
                    <div class="waktu"><?=$infodisposisitanggal?></div>
                </div>
                <div class="clearfix"></div>
            </div>
            
            <?
            if($kondisilihat == "1")
            {
            ?>
            <div class="title">Kepada</div>
            <div class="isi">
                <ol>
                    <?=$infodisposisikepada?>
                </ol>
            </div>
            <?
            }
            ?>
            
            <?
            if($kondisilihat == "1")
            {
            ?>
            <div class="title">Tindakan</div>
            <div class="isi">
                <ul>
                    <?
                    if(!empty($infodisposisitindakan))
                    {
                        $infodisposisitindakan= explode(",", $infodisposisitindakan);
                        for($i=0; $i < count($infodisposisitindakan); $i++)
                        {
                    ?>
                        <li><?=$infodisposisitindakan[$i]?></li>
                    <?
                        }
                    }
                    ?>
                </ul>
            </div>
            <?
            }
            ?>
            
            <?
            if($kondisilihat == "1")
            {
            ?>
            <div class="title">Catatan</div>
            <?
            }
            else
            {
            ?>
            <div class="title">Kepada</div>
            <div class="isi"><?=$infodisposisiuserkepada."<br/>(".$infodisposisiasalkepada.")"?></div>
            <div class="title">Tanggapan</div>
            <?
            }
            ?>
            <div class="isi"><?=$infodisposisicatatan?></div>

            
            <?
            if($kondisilihat == "1")
            {
            ?>
            <div class="title">Sifat</div>
            <div class="isi"><?=$infodisposisisifat?></div>
            <?
            }
            ?>

        </div>
        
        <?
        $classlampiran= "";
        if($jumlahattachment > 0)
        {
            $classlampiran= "ada-lampiran";
        }
        ?>
        <div class="konten-pdf <?=$classlampiran?>">
            <iframe name="contentFrame" src="app/loadUrl/report/template/?reqId=<?=$reqId?>"></iframe>
        </div>

        <?
        if(!empty($classlampiran))
        {
            $arrexcept= [];
            $arrexcept= array("xlsx", "xls", "doc", "docx", "ppt", "pptx", "txt");
        ?>
        <div class="konten-lampiran">
            <ol>
                <?
                for($index_data=0; $index_data < $jumlahattachment; $index_data++)
                {
                    $attnama= $arrattachment[$index_data]["NAMA"];
                    $attukuran= $arrattachment[$index_data]["UKURAN"];
                    $attlink= $arrattachment[$index_data]["ATTACHMENT"];
                    $atttipe= $arrattachment[$index_data]["TIPE"];
                    $atticon= infoiconlink($atttipe);
                ?>
                <li>
                    <div class="item"
                        <?
                        if(in_array(strtolower($atttipe), $arrexcept))
                        {
                        ?>
                        onclick="window.open('<?=base_url()."uploads/".$reqId."/".$attlink?>', '_blank')"
                        <?
                        }
                        else
                        {
                        ?>
                        onclick="parent.openAdd('<?=base_url()."uploads/".$reqId."/".$attlink?>')"
                        <?
                        }
                        ?>
                    >
                        <div class="ikon"><?=$attnama?> <i class="fa <?=$atticon?>"></i></div>
                        <div class="ukuran-file"><?=round(($attukuran/1024), 2)?> kb</div>
                    </div>
                </li>
                <?
                }
                ?>
                <?if ($lampirandrive !=''){?>
                    <li>
                           <div class="item"onclick="window.open('<?=$lampirandrive?>', '_blank')">
                            <div class="ikon"><?=$lampirandrive?> <i class="fa fa-link"></i></div>
                            <div class="ukuran-file"><?=round(($attukuran/1024), 2)?> kb</div>
                        </div>
                    </li>
                <?}?>
            </ol>
        </div>
        <?
        }
        ?>
    </div>
    
    <script>
    $('#btnPrintDisposisi').on('click', function () {
        //alert("btnPrintDisposisi");
        printExternal("<?=base_url()?>app/loadUrl/report/print_disposisi?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>");
        //printExternal();
          //if(anSelectedData == "")
        //    return false;             
          <?php /*?>window.parent.openPopup("<?=base_url()?>app/loadUrl/main/<?=$reqFilename?>_add/?reqId="+anSelectedId);<?php */?>
          <?php /*?>window.location = ("<?=base_url()?>main/index/<?=$reqFilename?>_add/?reqId="+anSelectedId);<?php */?>
            
          // tutup flex dropdown => untuk versi mobile
          //$('div.flexmenumobile').hide()
          //$('div.flexoverlay').css('display', 'none')
      });
    </script>
    
    <script>
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

    
</body>

</html>