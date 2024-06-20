<?php
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model("TrPsm");

$reqFilename= $this->uri->segment(3, "");
$reqStatusSurat= $this->input->get("reqStatusSurat");

$arrtabledata= array(
    array("label"=>"NO. SURAT", "field"=> "INFO_NOMOR_SURAT", "display"=>"",  "width"=>"20", "colspan"=>"", "rowspan"=>"")
    , array("label"=>"MENUNGGU PERSETUJUAN", "field"=> "PERSETUJUAN_INFO", "display"=>"",  "width"=>"25", "colspan"=>"", "rowspan"=>"")
    , array("label"=>"SISA STEP", "field"=> "JUMLAH_STEP", "display"=>"",  "width"=>"25", "colspan"=>"", "rowspan"=>"")

    , array("label"=>"fieldid", "field"=> "TR_PSM_ID", "display"=>"1",  "width"=>"", "colspan"=>"", "rowspan"=>"")
);

if(empty($reqStatusSurat))
	$reqStatusSurat= "PARAF";

$sessid= $this->ID;
$statementglobal= " AND A.USER_LIHAT_STATUS LIKE '%".$sessid."%'";

$statement= " AND A.USER_POSISI_PARAF_ID != '".$sessid."' AND A.STATUS_DATA IN ('PARAF', 'VALIDASI')";
$set= new TrPsm();
$jumlahparaf= $set->getCountByParams(array(), $statement);

$statement= " AND A.STATUS_DATA IN ('REVISI')";
$set= new TrPsm();
$jumlahrevisi= $set->getCountByParams(array(), $statement);
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
                <li>
                    <!-- <a id="btnEdit" title="Ubah"><img src="images/icon-edit.png" /> Ubah</a> -->
                	<!-- <a id="btnCetak"><i class="fa fa-file-excel-o"></i> Export Excel</a> -->
                </li>
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

	// infobold= arrdata.length - 4;
	// infocolor= arrdata.length - 3;

	infoscrolly= 50;

	$('#btnCetak').on('click', function () {
		var reqTahun= reqPencarian= "";
		reqTahun= $("#reqTahun").val();
		reqPencarian= $('#example_filter input').val();
		reqPilihan= $("#reqPilihan").val();
		reqPilihan= $("#reqPilihan").val();
		if(typeof reqPilihan == "undefined")
        {
            reqPilihan= "";
        }

		newWindow = window.open("app/loadUrl/main/kotak_masuk_export/?reqJenisNaskahId=<?=$reqJenisNaskahId?>&reqTahun="+reqTahun+"&reqPencarian="+reqPencarian+"&reqPilihan="+reqPilihan, 'Cetak');
		newWindow.focus();
	});

	$("#reqStatusSurat").change(function() {
		setCariInfo();
	});

	$('#btnCari').on('click', function () {
		var reqTahun= reqPencarian= reqStatusSurat= "";
		// reqPencarian= $('#example_filter input').val();
		reqStatusSurat= $("#reqStatusSurat").val();
		document.location.href = 'main/index/<?=$reqFilename?>?reqStatusSurat='+reqStatusSurat;

        /*jsonurl= "json/tr_loo_json/jsonstatus?reqPencarian="+reqPencarian;
        datanewtable.DataTable().ajax.url(jsonurl).load();*/
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
		var jsonurl= "json/tr_loo_json/jsonpsmstatus?reqStatusSurat=<?=$reqStatusSurat?>";
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
                fieldinforowid= arrdata[parseFloat(indexfieldid) - 1]["field"];
                valinfoid= dataselected[fieldinfoid];
                valinforowid= dataselected[fieldinforowid];
                // console.log(valinfoid+"-"+valinforowid);

                if(valinfoid !== "")
                {
                	reqPilihan= $("#reqPilihan").val();
					if(typeof reqPilihan == "undefined")
			        {
			            reqPilihan= "";
			        }

					window.location = "main/index/psm_status_detil/?reqMode=<?=$reqFilename?>&reqId="+valinfoid;
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