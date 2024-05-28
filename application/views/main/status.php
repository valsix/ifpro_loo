<?php
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model("SuratMasuk");

$reqFilename = $this->uri->segment(3, "");

$reqStatusSurat= $this->input->get("reqStatusSurat");

$set= new SuratMasuk();

$statement= " 
AND CASE WHEN A.TERPARAF = 1 AND A.JENIS_NASKAH_ID IN (8,17,18,19,20) THEN SM_INFO IN ('AKAN_DISETUJUI') ELSE SM_INFO IN ('AKAN_DISETUJUI', 'PEMBUAT') END
AND CASE WHEN A.TERPARAF = 1 AND A.JENIS_NASKAH_ID IN (8,17,18,19,20) THEN A.STATUS_SURAT IN ('PARAF', 'VALIDASI', 'PEMBUAT') ELSE A.STATUS_SURAT IN ('PARAF', 'VALIDASI') END
";
// $jumlahparaf= $set->getCountByParamsStatus(array(), $this->ID, $statement);
$infostatus= "1";
$satuankerjaganti= $this->SATUAN_KERJA_ID_ASAL;
$jumlahparaf= $set->getCountByParamsStatusTujuan(array(), $this->ID, $infostatus, $satuankerjaganti, $statement);
// echo $set->query;exit;

$statement= " AND A.STATUS_SURAT = 'REVISI'";
$jumlahrevisi= $set->getCountByParamsStatus(array(), $this->ID, $statement);

if(empty($reqStatusSurat))
	$reqStatusSurat= "PARAF";


if($reqStatusSurat == "PARAF")
{
	$arrtabledata= array(
		array("label"=>"TANGGAL", "field"=> "INFO_STATUS_TANGGAL", "display"=>"",  "width"=>"25", "colspan"=>"", "rowspan"=>"")
		, array("label"=>"NO. SURAT", "field"=> "INFO_NOMOR_SURAT", "display"=>"",  "width"=>"20", "colspan"=>"", "rowspan"=>"")
		, array("label"=>"PERIHAL", "field"=> "PERIHAL", "display"=>"",  "width"=>"25", "colspan"=>"", "rowspan"=>"")
		, array("label"=>"STATUS", "field"=> "INFO_STATUS", "display"=>"",  "width"=>"", "colspan"=>"", "rowspan"=>"")
		, array("label"=>"MENUNGGU PERSETUJUAN", "field"=> "PERSETUJUAN_INFO", "display"=>"",  "width"=>"", "colspan"=>"", "rowspan"=>"")
		, array("label"=>"SISA STEP", "field"=> "JUMLAH_STEP", "display"=>"",  "width"=>"", "colspan"=>"", "rowspan"=>"")

		, array("label"=>"fieldid", "field"=> "SURAT_MASUK_ID", "display"=>"1",  "width"=>"", "colspan"=>"", "rowspan"=>"")
	);
}
else
{
	$arrtabledata= array(
		array("label"=>"TANGGAL", "field"=> "INFO_STATUS_TANGGAL", "display"=>"",  "width"=>"25", "colspan"=>"", "rowspan"=>"")
		, array("label"=>"NO. SURAT", "field"=> "INFO_NOMOR_SURAT", "display"=>"",  "width"=>"20", "colspan"=>"", "rowspan"=>"")
		, array("label"=>"PERIHAL", "field"=> "PERIHAL", "display"=>"",  "width"=>"25", "colspan"=>"", "rowspan"=>"")
		, array("label"=>"STATUS", "field"=> "INFO_STATUS", "display"=>"",  "width"=>"", "colspan"=>"", "rowspan"=>"")

		, array("label"=>"fieldid", "field"=> "SURAT_MASUK_ID", "display"=>"1",  "width"=>"", "colspan"=>"", "rowspan"=>"")
	);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>

<head>
	<base href="<?=base_url()?>">
	<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
	<title>Diklat</title>
	<link href="css/admin.css" rel="stylesheet" type="text/css">

	<link href="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

	<style type="text/css" media="screen">
		@import "lib/media/css/site_jui.css";
		@import "lib/media/css/demo_table_jui.css";
		@import "lib/media/css/themes/base/jquery-ui.css";
	</style>

	<link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.6/media/css/jquery.dataTables.css">
	<link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.6/examples/resources/syntax/shCore.css">
	<link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.6/examples/resources/demo.css">

	<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.6/media/js/jquery.dataTables.js"></script>
	<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.6/extensions/FixedColumns/js/dataTables.fixedColumns.js"></script>
	<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.6/extensions/TableTools/js/dataTables.tableTools.min.js"></script>

	<link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
	<script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="lib/easyui/kalender-easyui.js"></script>
	<script type="text/javascript" src="lib/easyui/globalfunction.js"></script>

	<script src="lib/js/valsix-serverside.js"></script>

	<link href="lib/media/themes/main_datatables.css" rel="stylesheet" type="text/css" />
	<link href="css/begron.css" rel="stylesheet" type="text/css">
	<link href="css/bluetabs.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="js/dropdowntabs.js"></script>
</head>

<body style="overflow:hidden;">
	<div id="begron"><img src="images/bg-kanan.jpg" width="100%" height="100%" alt="Smile"></div>
	<div id="wadah">
		<div class="judul-halaman">Data <?= str_replace("_", " ", $reqFilename) ?></div>
		<div id="bluemenu" class="bluetabs">
        	<ul>
        		<a href="#" id="btnCari" style="display: none;" title="Cari"></a>
        		<?
                	if(in_array("DIVISI", explode(",", $this->USER_GROUP))) {
                ?>
                <li>
                	<select id="reqPilihan" style="height: 30px;">
                		<option value="">Pribadi</option>
                		<option value="divisi">Divisi</option>
                	</select>
                </li>
                <?
            	}
                ?>
        		<li class="pull-right">
                	<select id="reqStatusSurat">
                        <option value="PARAF" <? if($reqStatusSurat == "PARAF") echo "selected";?>>In Progress (<?=$jumlahparaf?>)</option>
                        <option value="REVISI" <? if($reqStatusSurat == "REVISI") echo "selected";?>>Dikembalikan (<?=$jumlahrevisi?>)</option>
                    </select>
                </li>
			</ul>
		</div>

        <table id="example" class="table table-striped table-hover dt-responsive" cellspacing="0" width="100%">
        	<thead style="background: #1cb7cf; color: #FFF;">
				<tr>
                    <?php
                    foreach($arrtabledata as $valkey => $valitem) 
                    {
                    	$infotablelabel= $valitem["label"];
                    	$infotablecolspan= $valitem["colspan"];
                    	$infotablerowspan= $valitem["rowspan"];

                    	$infowidth= "";
                    	if(!empty($infotablecolspan))
                    	{
                    	}

                    	if(!empty($infotablelabel))
                    	{
                    ?>
                        <th style="text-align:center; width: <?=$infowidth?>%" colspan='<?=$infotablecolspan?>' rowspan='<?=$infotablerowspan?>'><?=$infotablelabel?></th>
                    <?
                    	}
                    }
                    ?>
                </tr>
            </thead>
        </table>
	</div>

<a href="#" id="triggercari" style="display:none" title="triggercari">triggercari</a>
<a href="#" id="btnCari" style="display: none;" title="Cari"></a>

<script type="text/javascript">
	var datanewtable;
	var infotableid= "example";
	var carijenis= "";
	var arrdata= <?php echo json_encode($arrtabledata); ?>;
	var indexfieldid= arrdata.length - 1;
	var valinfoid= valinforowid='';
	var datainforesponsive= "1";
	var datainfoscrollx= 100;

	// datainfostatesave= "1";
	datastateduration= 60 * 2;

	infobold= arrdata.length - 4;
	infocolor= arrdata.length - 3;

	infoscrolly= 50;

	$("#reqStatusSurat").change(function() {
		setCariInfo();
	});

	$("#reqPilihan").change(function() {
		var reqPilihan= reqPencarian= reqStatusSurat= "";
		reqStatusSurat= $("#reqStatusSurat").val();
		reqPencarian= $('#example_filter input').val();
		reqPilihan= $("#reqPilihan").val();
		if(typeof reqPilihan == "undefined")
        {
            reqPilihan= "";
        }

        jsonurl= "json/surat_masuk_json/jsonstatus?reqPencarian="+reqPencarian+"&reqStatusSurat="+reqStatusSurat+"&reqPilihan="+reqPilihan;
        datanewtable.DataTable().ajax.url(jsonurl).load();
	});

	$('#btnCari').on('click', function () {
		var reqTahun= reqPencarian= reqStatusSurat= "";
		reqStatusSurat= $("#reqStatusSurat").val();
		reqPencarian= $('#example_filter input').val();

		document.location.href= 'main/index/<?=$reqFilename?>?reqStatusSurat='+reqStatusSurat;
	});

	$("#triggercari").on("click", function () {
        if(carijenis == "1")
        {
            pencarian= $('#'+infotableid+'_filter input').val();
            datanewtable.DataTable().search( pencarian ).draw();
        }
        else
        {
            
        }
    });

	jQuery(document).ready(function() {
		var jsonurl= "json/surat_masuk_json/jsonstatus?reqStatusSurat=<?=$reqStatusSurat?>";
	    ajaxserverselectsingle.init(infotableid, jsonurl, arrdata);
	});

	function calltriggercari()
	{
	    $(document).ready( function () {
	      $("#triggercari").click();      
	    });
	}

	function setCariInfo()
	{
		$(document).ready( function () {
			$("#btnCari").click();
		});
	}

    $(document).ready(function() {
        var table = $('#example').DataTable();

        $('#example tbody').on( 'click', 'tr', function () {
            if ( $(this).hasClass('selected') ) {
                $(this).removeClass('selected');
            }
            else {
                table.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');

                var dataselected= datanewtable.DataTable().row(this).data();
                // console.log(dataselected);
                // console.log(Object.keys(dataselected).length);

                fieldinfoid= arrdata[indexfieldid]["field"];
                valinfoid= dataselected[fieldinfoid];
                // console.log(valinfoid+"-"+valinforowid);

                if(valinfoid !== "")
                {
					window.location = "main/index/status_detil/?reqMode=<?=$reqFilename?>&reqId="+valinfoid;
                }
            }
        } );

        $('#'+infotableid+' tbody').on( 'dblclick', 'tr', function () {
            $("#btnEdit").click();
        });

        $('#button').click( function () {
            table.row('.selected').remove().draw( false );
        } );
    } );
</script>

</body>
</html>