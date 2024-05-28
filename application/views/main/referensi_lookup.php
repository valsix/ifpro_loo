<?php
include_once("functions/string.func.php");
include_once("functions/date.func.php");
$this->load->model("SuratMasuk");

$reqFilename = $referensi_lookup;

$tinggi = 360;

$reqStatusSurat= $this->input->get("reqStatusSurat");
$reqCheckId= $this->input->get("reqCheckId");
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

if(empty($reqStatusSurat))
    $reqStatusSurat= "KOTAK_MASUK";

$arrdata= array(
    array("label"=>"Pilih", "width"=>"10")
    , array("label"=>"NO. SURAT", "width"=>"")
    , array("label"=>"PERIHAL", "width"=>"")
    , array("label"=>"TANGGAL", "width"=>"100")
);
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

    <?php ?><script type="text/javascript" language="javascript" src="<?=base_url()?>lib/DataTables-1.10.6/media/js/jquery.js"></script><?php  ?>
    <script type="text/javascript" language="javascript" src="<?= base_url() ?>lib/DataTables-1.10.6/media/js/jquery.dataTables.js"></script>
    <script type="text/javascript" language="javascript" src="<?= base_url() ?>lib/DataTables-1.10.6/examples/resources/syntax/shCore.js"></script>
    <script type="text/javascript" language="javascript" src="<?= base_url() ?>lib/DataTables-1.10.6/examples/resources/demo.js"></script>
    <script type="text/javascript" language="javascript" src="<?= base_url() ?>lib/DataTables-1.10.6/extensions/FixedColumns/js/dataTables.fixedColumns.js"></script>
    <script type="text/javascript" language="javascript" src="<?= base_url() ?>lib/DataTables-1.10.6/extensions/TableTools/js/dataTables.tableTools.min.js"></script>

    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>lib/easyui/themes/default/easyui.css">
    <script type="text/javascript" src="<?= base_url() ?>lib/easyui/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>lib/easyui/kalender-easyui.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>lib/easyui/globalfunction.js"></script>

    <script type="text/javascript" charset="utf-8">
        var oTable;
        var arrChecked = [];

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
                "sAjaxSource": "web/surat_masuk_json/jsonreference?reqStatusSurat=<?=$reqStatusSurat?>&reqTahun=<?=$reqTahun?>&reqCheckId=<?=$reqCheckId?>",
                "sScrollY": ($(window).height() - <?= $tinggi ?>),
                "sScrollX": "100%",
                "sScrollXInner": "100%",
                "sPaginationType": "full_numbers",
                "fnDrawCallback": function( oSettings ) {
                    setKlikCheck();
                }
                //"responsive": true
            });
            /* Click event handler */

            /* RIGHT CLICK EVENT */
            var anSelectedData = '';
            var anSelectedId = '';
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
                if(isNaN(anSelectedId))
                {
                    anSelectedId= anSelectedId[anSelectedId.length-1];
                }
                // alert("--"+anSelectedId);

                // window.location = ("<?= base_url() ?>main/index/perlu_persetujuan_detil/?reqMode=perlu_persetujuan&reqId=" + anSelectedId);
            });

            $("#reqStatusSurat, #reqTahun").change(function() {
                setCariInfo();
            });

            $('#btnCari').on('click', function () {
                var reqStatusSurat= reqTahun= "";
                reqStatusSurat= $("#reqStatusSurat").val();
                reqTahun= $("#reqTahun").val();
                reqPilihId= $("#reqPilihId").val();

                oTable.fnReloadAjax("web/surat_masuk_json/jsonreference?reqStatusSurat="+reqStatusSurat+"&reqTahun="+reqTahun+"&reqCheckId="+reqPilihId);

                // document.location.href = 'main/index/<?= $reqFilename ?>?reqStatusSurat='+reqStatusSurat;
                // document.location.href = 'app/loadUrl/main/referensi_lookup?reqStatusSurat='+reqStatusSurat;
            });

            $('#btnEdit').on('click', function() {
                if (anSelectedData == "")
                    return false;
                <?php /*?>window.parent.openPopup("<?=base_url()?>app/loadUrl/main/<?=$reqFilename?>_add/?reqId="+anSelectedId);<?php */ ?>
                window.location = ("<?= base_url() ?>main/index/<?= $reqFilename ?>_add/?reqId=" + anSelectedId);


                // tutup flex dropdown => untuk versi mobile
                $('div.flexmenumobile').hide()
                $('div.flexoverlay').css('display', 'none')
            });

            $('#btnPilih').on('click', function () {
                var reqPilihId= "";
                reqPilihId= $("#reqPilihId").val();

                if(reqPilihId == "")
                {
                    $.messager.alert('Info', "Pilih data terlebih dahulu", 'info');
                    return false;
                }

                $.messager.confirm('Konfirmasi',"Apakah anda yakin pilih, data terpilih?",function(r){
                    if (r)
                    {
                        if (typeof parent.setreference === 'function')
                        {
                            parent.setreference(reqPilihId);
                        }
                        top.closePopup();
                        // $.getJSON("../json-pengaturan/jadwal_awal_tes_add_simulasi_pegawai_hapus.php?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>&reqPilihId="+reqPilihId,
                        // function(data){
                        //     setCariInfo();
                        //     $("#reqPilihId").val("");
                        // });
                    }
                });
            });

        });
        
        function setCariInfo()
        {
            $(document).ready( function () {
                $("#btnCari").click();
            });
        }

        var infoIdArr= [];
        function setKlikCheck()
        {
            reqPilihId= String($("#reqPilihId").val());
            reqArrInfoId= reqPilihId.split(',');

            var i= "";
            if(reqPilihId == ""){}
            else
            {
                infoIdArr= reqArrInfoId;
                i= infoIdArr.length - 1;
                i= infoIdArr.length;
            }

            reqPilihCheck= reqPilihCheckVal= "";
            $('input[id^="reqPilihCheck"]:checkbox:checked').each(function(i){
                reqPilihCheck= $(this).val();
                var id= $(this).attr('id');
                id= id.replace("reqPilihCheck", "");

                if(reqPilihCheckVal == "")
                {
                    reqPilihCheckVal= reqPilihCheck;
                }
                else
                {
                    reqPilihCheckVal= reqPilihCheckVal+","+reqPilihCheck;
                }

                var elementRow= infoIdArr.indexOf(reqPilihCheck);
                if(elementRow == -1)
                {
                    i= infoIdArr.length;

                    infoIdArr[i]= reqPilihCheck;
                }

            });

            $('input[id^="reqPilihCheck"]:checkbox:not(:checked)').each(function(i){
                reqPilihCheck= $(this).val();
                var id= $(this).attr('id');
                id= id.replace("reqPilihCheck", "");

                var elementRow= infoIdArr.indexOf(reqPilihCheck);
                if(parseInt(elementRow) >= 0)
                {
                    infoIdArr.splice(elementRow, 1);
                }
            });

            reqPilihCheck= reqPilihCheckVal= "";
            for(var i=0; i<infoIdArr.length; i++) 
            {
                if(reqPilihCheckVal == "")
                {
                    reqPilihCheckVal= infoIdArr[i];
                }
                else
                {
                    reqPilihCheckVal= reqPilihCheckVal+","+infoIdArr[i];
                }
            }

            $("#reqPilihId").val(reqPilihCheckVal);
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
    
    <!-- ADD STYLE -->
    <link href="<?= base_url() ?>css/gaya.css" rel="stylesheet" type="text/css">
	<style>
	body{
		padding: 8px !important;
	}
	body, *{
		box-sizing: border-box !important;
	}
	.bluetabs{
		background: #f0f0ee;
		height: auto;
		padding: 10px;
		line-height: normal;
		display: inline-block;
		width: 100%;
		box-sizing: border-box;
		
		margin-bottom: 10px;
	}
	table.display{
		
	}
	table.display thead th{
		background: #157dba;
		color: #FFFFFF;
		padding: 3px 0px 3px 10px;
		cursor: pointer;
	}
	table.display tbody td{
		border: 1px dotted rgba(21,125,186,0.5);
	}
	table.display tbody tr:hover td{
		background: #eaf2ff
	}
	</style>
    <!-- END ADD STYLE -->
    
</head>

<body class="body-popup">
	<div class="container-fluid container-treegrid">
        
        <div class="row row-treegrid">
            <div class="col-md-12 col-treegrid">
            	<div class="area-konten-atas">
                	<div class="judul-halaman">Referensi</div>
                    <div id="bluemenu" class="bluetabs">
                        <ul>
                            <a href="#" id="btnCari" style="display: none;" title="Cari"></a>
                            <li><a id="btnPilih" title="Pilih"><i class="fa fa-check-circle" aria-hidden="true"></i> Pilih</a></li>
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
                                <select id="reqStatusSurat">
                                    <option value="KOTAK_MASUK" <? if($reqStatusSurat == "KOTAK_MASUK") echo "selected";?>>Kotak Masuk</option>
                                    <option value="DISPOSISI" <? if($reqStatusSurat == "DISPOSISI") echo "selected";?>>Disposisi</option>
                                </select>
                            </li>
                        </ul>
                    </div>
                    
				</div>

                <input type="hidden" id="reqPilihId" />
                <div class="area-datatable">
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
                
            </div>
		</div>
    </div>
    
    
</body>

</html>