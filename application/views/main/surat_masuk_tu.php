<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$reqId = $this->input->get("reqId");

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

	<?php /*?><script type="text/javascript" language="javascript" src="<?=base_url()?>lib/DataTables-1.10.6/media/js/jquery.js"></script><?php */ ?>
	<script type="text/javascript" language="javascript" src="<?= base_url() ?>lib/DataTables-1.10.6/media/js/jquery.dataTables.js"></script>
	<script type="text/javascript" language="javascript" src="<?= base_url() ?>lib/DataTables-1.10.6/examples/resources/syntax/shCore.js"></script>
	<script type="text/javascript" language="javascript" src="<?= base_url() ?>lib/DataTables-1.10.6/examples/resources/demo.js"></script>
	<script type="text/javascript" language="javascript" src="<?= base_url() ?>lib/DataTables-1.10.6/extensions/FixedColumns/js/dataTables.fixedColumns.js"></script>
	<script type="text/javascript" language="javascript" src="<?= base_url() ?>lib/DataTables-1.10.6/extensions/TableTools/js/dataTables.tableTools.min.js"></script>

	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>lib/easyui/themes/default/easyui.css">
	<script type="text/javascript" src="<?= base_url() ?>lib/easyui/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="<?= base_url() ?>lib/easyui/kalender-easyui.js"></script>
	<script type="text/javascript" src="<?= base_url() ?>lib/easyui/globalfunction.js"></script>


	<?php
$aColumns = array("SURAT_MASUK_ID", "NOMOR", "DARI", "KEPADA", "TANGGAL_ENTRI", 
						  "PERIHAL", "JENIS_NASKAH", "SIFAT_NASKAH", "DIBUAT_OLEH");
						  
$reqJenisTujuan = "AGD";		
?>

<script type="text/javascript" language="javascript" class="init">	

	var oTable;
    $(document).ready( function () {
		
        oTable = $('#example').dataTable({ bJQueryUI: true,"iDisplayLength": 25,
			/* UNTUK MENGHIDE KOLOM ID */
			"aoColumns": [
				{ bVisible:false },
				<?php
				// looping table TH
					for($i=2;$i<count($aColumns);$i++){
						echo 'null'.',';
					}
				?>
				null
			],
			"bSort":true,
			"bProcessing": true,
			"bServerSide": true,		
			"sAjaxSource": "web/surat_masuk_json/json_surat_masuk_tu",		
			columnDefs: [{ className: 'never', targets: [ 0 ] }],
			"sPaginationType": "full_numbers",
			  "scrollY": "calc(" + 100 + "vh - 200px)"
		});
		
		/* Click event handler */

		/* RIGHT CLICK EVENT */
		var anSelectedData = '';
		var anSelectedId = '';
		var anSelectedDownload = '';
		var anSelectedPosition = '';	

		function fnGetSelected( oTableLocal )
		{
			var aReturn = new Array();
			var aTrs = oTableLocal.fnGetNodes();
			for (var i=0;i<aTrs.length;i++)
			{
				if ($(aTrs[i]).hasClass('row_selected'))
				{
					aReturn.push(aTrs[i]);
					anSelectedPosition = i;
				}
			}
			return aReturn;
		}
		  
		$("#example tbody").click(function(event) {
			$(oTable.fnSettings().aoData).each(function(){
				$(this.nTr).removeClass('row_selected');
			});

			$(event.target.parentNode).addClass('row_selected');

			var anSelected = fnGetSelected(oTable);													
			anSelectedData = String(oTable.fnGetData(anSelected[0]));
			var element = anSelectedData.split(','); 
			anSelectedId = element[0];
			anSelectedStatus = element[1];
		});
			  	  
		$('#btnLihat').on('click', function () {
		  if(anSelectedData == "")
			  return false;	
		  document.location.href = "<?=base_url()?>main/index/surat_masuk_tu_lihat?reqId="+anSelectedId;  			
		
		});
		
	});

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
		<div class="judul-halaman">Register Surat Masuk (Aplikasi)</div>
		<div id="bluemenu" class="bluetabs">
			<ul>
				<li>
					<a id="btnLihat"><i class="fa fa-eye fa-lg" aria-hidden="true"></i> Lihat</a>
				</li>
			</ul>
		</div>
		<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
			<thead>
				<tr>
                    <th>ID</th>
                    <?php
                    for($i=1;$i<count($aColumns);$i++){
                    ?>
                    <th><?=str_replace('_',' ',$aColumns[$i])  ?></th>
                    <?php	
					};
                    ?>
                </tr>
			</thead>
		</table>
	</div>
</body>

</html>