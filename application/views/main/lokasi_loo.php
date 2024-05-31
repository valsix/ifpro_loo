<?php


$reqFilename = $this->uri->segment(3, "");
// print_r($this->uri);
// exit;


include_once("functions/string.func.php");
include_once("functions/date.func.php");

$tinggi = 360;
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


	<?php
	// Header Nama TABEL TH
	$aColumns = array("LOKASI_LOO_ID", "KODE", "NAMA", "SERVICE CHARGE", "DESKRIPSI");
	?>

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
				"iDisplayLength": 500,
				/* UNTUK MENGHIDE KOLOM ID */
				"aoColumns": [{
						bVisible: false
					},
					<?php
					// 
					for ($i = 2; $i < count($aColumns); $i++) {
						echo 'null' . ',';
					}
					?>
					null
				],
				"bSort": true,
				"bProcessing": true,
				"bServerSide": true,
				"sAjaxSource": "web/lokasi_loo_json/json",
				"sScrollY": ($(window).height() - <?= $tinggi ?>),
				"sScrollX": "100%",
				"sScrollXInner": "100%",
				"sPaginationType": "full_numbers"
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
				//
				var anSelected = fnGetSelected(oTable);
				anSelectedData = String(oTable.fnGetData(anSelected[0]));
				var element = anSelectedData.split(',');
				anSelectedId = element[0];
			});

			$('#btnAdd').on('click', function() {
				<?php /*?>window.parent.openPopup("<?=base_url()?>app/loadUrl/main/<?=$reqFilename?>_add");<?php */ ?>
				window.location = ("<?= base_url() ?>main/index/<?= $reqFilename ?>_add");

				// tutup flex dropdown => untuk versi mobile
				$('div.flexmenumobile').hide()
				$('div.flexoverlay').css('display', 'none')

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

			$('#btnTransaksi').on('click', function() {
				if (anSelectedData == "")
					return false;
				<?php /*?>window.parent.openPopup("<?=base_url()?>app/loadUrl/main/<?=$reqFilename?>_transaksi/?reqId="+anSelectedId);<?php */ ?>
				window.location = ("<?= base_url() ?>main/index/<?= $reqFilename ?>_transaksi/?reqId=" + anSelectedId);


				// tutup flex dropdown => untuk versi mobile
				$('div.flexmenumobile').hide()
				$('div.flexoverlay').css('display', 'none')
			});

			$('#btnDelete').on('click', function() {
				if (anSelectedData == "") {
					alert("Pilih data terlebih dahulu!");
					return false;
				}

				deleteData("web/lokasi_loo_json/delete", anSelectedId);

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
		<div class="judul-halaman">Data <?= str_replace("_", " ", $reqFilename) ?></div>
		<div id="bluemenu" class="bluetabs">
			<ul>
				<li>
					<a id="btnAdd" title="Tambah"><img src="<?= base_url() ?>images/icon-tambah.png" /> Tambah</a>
					<a id="btnEdit" title="Ubah"><img src="<?= base_url() ?>images/icon-edit.png" /> Ubah</a>
					<a id="btnDelete" title="Hapus"><img src="<?= base_url() ?>images/icon-hapus.png" /> Hapus</a>
					<a id="btnTransaksi" title="Transaksi"><img src="<?= base_url() ?>images/icon-course.png" /> Formula Luasan</a>
				</li>
			</ul>
		</div>
		<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
			<thead>
				<tr>
				<tr>
					<th>ID</th>
					<?php
					for ($i = 1; $i < count($aColumns); $i++) {
					?>
						<th><?= str_replace('_', ' ', $aColumns[$i])  ?></th>
					<?php

					};
					?>
				</tr>
			</thead>
		</table>
	</div>
</body>

</html>