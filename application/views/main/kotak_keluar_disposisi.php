<?php
include_once("functions/string.func.php");
include_once("functions/date.func.php");
$this->load->model("SuratMasuk");

$reqFilename = $this->uri->segment(3, "");

$tinggi = 360;

$reqJenisNaskahId= $this->input->get("reqJenisNaskahId");
$reqTahun= "";

$set= new SuratMasuk();
$arrTahun= array();
$index= 0;
$set->selectByParamsTahun();
//echo $set->errorMsg;exit;
//echo $set->query;exit;
while($set->nextRow())
{
    $arrTahun[$index]["TAHUN"] = $set->getField("TAHUN");
    $index++;
}
unset($set);
$jumlahtahun= $index;
//print_r($set);exit;

if($index > 0)
    $reqTahun= $arrTahun[0]["TAHUN"];

$arrdata= array(
    array("label"=>"NO. SURAT", "width"=>"100")
    , array("label"=>"DARI", "width"=>"")
    , array("label"=>"PERIHAL", "width"=>"")
    , array("label"=>"TANGGAL", "width"=>"")
    , array("label"=>"DISPOSISI KE", "width"=>"")
);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>

<head>
    <base href="<?= base_url() ?>">
    <meta http-equiv="Content-type" content="text/html; charset=UTF-8">
    <title>Diklat</title>
    <!-- <link rel="shortcut icon" type="image/ico" href="http://www.datatables.net/media/images/favicon.ico"> -->
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

    <?php /*?><script type="text/javascript" language="javascript" src="<?=base_url()?>lib/DataTables-1.10.6/media/js/jquery.js"></script><?php */ ?>
    <script type="text/javascript" language="javascript" src="<?= base_url() ?>lib/DataTables-1.10.6/media/js/jquery.dataTables.js"></script>
    <!-- <script type="text/javascript" language="javascript" src="<?= base_url() ?>lib/DataTables-1.10.6/examples/resources/syntax/shCore.js"></script> -->
    <!-- <script type="text/javascript" language="javascript" src="<?= base_url() ?>lib/DataTables-1.10.6/examples/resources/demo.js"></script> -->
    <script type="text/javascript" language="javascript" src="<?= base_url() ?>lib/DataTables-1.10.6/extensions/FixedColumns/js/dataTables.fixedColumns.js"></script>
    <script type="text/javascript" language="javascript" src="<?= base_url() ?>lib/DataTables-1.10.6/extensions/TableTools/js/dataTables.tableTools.min.js"></script>

    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>lib/easyui/themes/default/easyui.css">
    <script type="text/javascript" src="<?= base_url() ?>lib/easyui/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>lib/easyui/kalender-easyui.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>lib/easyui/globalfunction.js"></script>

    <script type="text/javascript" charset="utf-8">
        var oTable;
        $(document).ready(function() {

            var id = -1; //simulation of id
            $(window).resize(function() {
                console.log($(window).height());
                $('.dataTables_scrollBody').css('height', ($(window).height() - <?= $tinggi ?>));
            });
            oTable = $('#example').dataTable({
                bJQueryUI: true,
                "iDisplayLength": 25,
                /* UNTUK MENGHIDE KOLOM ID */
                "aoColumns": [
                <?
                for($i=0; $i < count($arrdata); $i++)
                {
                    if($i == 0){}
                    else
                        echo ",";
                ?>
                null
                <?
                }
                ?>
                ],
                "bSort": true,
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": "web/surat_masuk_json/jsonsuratkeluardisposisi?reqJenisNaskahId=<?=$reqJenisNaskahId?>&reqTahun=<?=$reqTahun?>",
                "sScrollY": ($(window).height() - <?= $tinggi ?>),
                "sScrollX": "100%",
                "sScrollXInner": "100%",
                "sPaginationType": "full_numbers",
                "fnDrawCallback": function( oSettings ) {
                    $('#example_filter input').unbind();
                    $('#example_filter input').bind('keyup', function(e) {
                        if(e.keyCode == 13) {
                            setCariInfo();
                        }
                    });
                }
                //"responsive": true
            });
            /* Click event handler */

            /* RIGHT CLICK EVENT */
            var anSelectedData = '';
            var anSelectedId = anSelectedRowId= '';
            var anSelectedDownload = '';
            var anSelectedPosition = '';

            function fnGetSelected(oTableLocal) {
                var aReturn = new Array();
                var aTrs = oTableLocal.fnGetNodes();
                for (var i = 0; i < aTrs.length; i++) {
                    if ($(aTrs[i]).hasClass('row_selected')) {
                        aReturn.push(aTrs[i]);
                        anSelectedPosition = i;
                    }
                }
                return aReturn;
            }

            $("#example tbody").click(function(event) {
                $(oTable.fnSettings().aoData).each(function() {
                    $(this.nTr).removeClass('row_selected');
                });
                $(event.target.parentNode).addClass('row_selected');
                var anSelected = fnGetSelected(oTable);
                anSelectedData = oTable.fnGetData(anSelected[0]);
                var element = anSelectedData;
                anSelectedId= element[element.length-1];
                anSelectedRowId= element[element.length-2];
                if(isNaN(anSelectedId))
				{
					// anSelectedId= anSelectedId[anSelectedId.length-1];
					anSelectedId= "";
				}
				// alert("--"+anSelectedId);

				if(anSelectedId !== "")
                window.location = ("<?= base_url() ?>main/index/kotak_masuk_disposisi_detil/?reqMode=kotak_keluar_disposisi&reqId="+anSelectedId+"&reqRowId="+anSelectedRowId);
            });

            $("#reqTahun").change(function() {
                setCariInfo();
            });

            $('#btnCari').on('click', function () {
                var reqTahun= reqPencarian= "";
                reqTahun= $("#reqTahun").val();
                reqPencarian= $('#example_filter input').val();

                oTable.fnReloadAjax("web/surat_masuk_json/jsonsuratkeluardisposisi?reqJenisNaskahId=<?=$reqJenisNaskahId?>&reqTahun="+reqTahun+"&reqPencarian="+reqPencarian);
            });

            $('#btnCetak').on('click', function () {
                var reqTahun= reqPencarian= "";
                reqTahun= $("#reqTahun").val();
                reqPencarian= $('#example_filter input').val();

                newWindow = window.open("<?= base_url() ?>app/loadUrl/main/kotak_keluar_disposisi_export/?reqJenisNaskahId=<?=$reqJenisNaskahId?>&reqTahun="+reqTahun+"&reqPencarian="+reqPencarian, 'Cetak');
                newWindow.focus();
            });

        });
        
        function setCariInfo()
        {
            $(document).ready( function () {
                $("#btnCari").click();
            });
        }
    </script>

    <!--RIGHT CLICK EVENT-->
    <style>
        .vmenu {
            border: 1px solid #aaa;
            position: absolute;
            background: #fff;
            display: none;
            font-size: 0.75em;
        }

        .first_li {}

        .first_li span {
            width: 100px;
            display: block;
            padding: 5px 10px;
            cursor: pointer
        }

        .inner_li {
            display: none;
            margin-left: 120px;
            position: absolute;
            border: 1px solid #aaa;
            border-left: 1px solid #ccc;
            margin-top: -28px;
            background: #fff;
        }

        .sep_li {
            border-top: 1px ridge #aaa;
            margin: 5px 0
        }

        .fill_title {
            font-size: 11px;
            font-weight: bold;/height: 15px;/overflow: hidden;
            word-wrap: break-word;
        }
    </style>

    <link href="<?= base_url() ?>lib/media/themes/main_datatables.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url() ?>css/begron.css" rel="stylesheet" type="text/css">
    <link href="<?= base_url() ?>css/bluetabs.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="<?= base_url() ?>js/dropdowntabs.js"></script>
</head>

<body style="overflow:hidden;">
    <div id="begron"><img src="<?= base_url() ?>images/bg-kanan.jpg" width="100%" height="100%" alt="Smile"></div>
    <div id="wadah">
        <div class="judul-halaman">Data <?= str_replace("_", " ", $reqFilename) ?></div>
        <div id="bluemenu" class="bluetabs">
            <ul>
                <a href="#" id="btnCari" style="display: none;" title="Cari"></a>
                <li>
                    <a class="btn btn-primary btn-sm" id="btnCetak"><i class="fa fa-file-excel-o"></i> Export Excel</a>
                </li>
                <li class="pull-right">
                    <select id="reqTahun">
                        <option value="">Semua</option>
                        <?
                        for($index= 0; $index < $jumlahtahun; $index++)
                        {
                            $infotahun= $arrTahun[$index]["TAHUN"]
                        ?>
                            <option value="<?=$infotahun?>" <? if($infotahun == $reqTahun) echo "selected";?>><?=$infotahun?></option>
                        <?
                        }
                        ?>
                    </select>
                </li>
            </ul>
        </div>
        <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
            <thead>
                <tr>
                    <?
                    for($i=0; $i < count($arrdata); $i++)
                    {
                        $infolabel= $arrdata[$i]["label"];
                        $infowidth= $arrdata[$i]["width"];
                    ?>
                        <th width="<?=$infowidth?>px"><?=$infolabel?></th>
                    <?
                    }
                    ?>
                </tr>
            </thead>
        </table>
    </div>
</body>

</html>