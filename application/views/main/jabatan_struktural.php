<?php

$reqFilename = $this->uri->segment(3, "");


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
	<link rel="shortcut icon" type="image/ico" href="http://www.datatables.net/media/images/favicon.ico">
	<!--<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="http://www.datatables.net/rss.xml">-->
	<link href="<?= base_url() ?>css/admin.css" rel="stylesheet" type="text/css">

	<style type="text/css" media="screen">
		@import "<?= base_url() ?>lib/media/css/site_jui.css";
		@import "<?= base_url() ?>lib/media/css/demo_table_jui.css";
		@import "<?= base_url() ?>lib/media/css/themes/base/jquery-ui.css";
	</style>

	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>lib/DataTables-1.10.6/media/css/jquery.dataTables.css">
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>lib/DataTables-1.10.6/examples/resources/syntax/shCore.css">
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>lib/DataTables-1.10.6/examples/resources/demo.css">
	<?php /*?><script type="text/javascript" language="javascript" src="<?=base_url()?>lib/DataTables-1.10.6/media/js/jquery.js"></script><?php */ ?>
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>lib/easyui/themes/default/easyui.css">
	<script type="text/javascript" src="<?= base_url() ?>lib/easyui/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="<?= base_url() ?>lib/easyui/kalender-easyui.js"></script>
	<script type="text/javascript" language="javascript" src="<?= base_url() ?>lib/DataTables-1.10.6/media/js/jquery.dataTables.js"></script>
	<script type="text/javascript" language="javascript" src="<?= base_url() ?>lib/DataTables-1.10.6/examples/resources/syntax/shCore.js"></script>
	<script type="text/javascript" language="javascript" src="<?= base_url() ?>lib/DataTables-1.10.6/examples/resources/demo.js"></script>
	<script type="text/javascript" language="javascript" src="<?= base_url() ?>lib/DataTables-1.10.6/extensions/FixedColumns/js/dataTables.fixedColumns.js"></script>
	<script type="text/javascript" language="javascript" src="<?= base_url() ?>lib/DataTables-1.10.6/extensions/TableTools/js/dataTables.tableTools.min.js"></script>



	<?php
	// Header Nama TABEL TH
	$aColumns = array("SATUAN_KERJA_ID", "NAMA", "STATUS_AKTIF");
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
				"sAjaxSource": "web/jenis_naskah_json/json",
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

			$('#btnDelete').on('click', function() {
				if (anSelectedData == "") {
					alert("Pilih data terlebih dahulu!");
					return false;
				}

				deleteData("web/tingkat_perkembangan_json/delete", anSelectedId);

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

	<style>
		.datagrid-body {
			*height: calc(80vh - 190px) !important;
			*border: 2px solid red !important;
		}

		.datagrid-wrap.panel-body.panel-body-noheader {
			height: calc(80vh - 94px) !important;
			*border: 2px solid yellow !important;
		}
	</style>


</head>

<body style="overflow:hidden;">
	<div id="begron"><img src="<?= base_url() ?>images/bg-kanan.jpg" width="100%" height="100%" alt="Smile"></div>
	<div id="wadah">
		<div class="judul-halaman">Data <?= str_replace("_", " ", $reqFilename) ?></div>
		<div id="bluemenu" class="bluetabs">
			<ul>
				<li>
					<div style="padding-left: 10px">
						<form id="ff" class="easyui-form form-horizontal form-filter" method="post" novalidate enctype="multipart/form-data">
							<span>Unit Kerja </span>
							<input type="text" name="reqUnitKerjaId" class="easyui-combobox" id="reqUnitKerjaId" data-options="width:'400',editable:false, valueField:'id',textField:'text'" value="<?= $this->CABANG_ID ?>" required />
							<span>Pencarian </span> 
                            <input type="text" name="reqPencarian" class="easyui-validatebox textbox form-control" id="reqPencarian" style="width:300px"> 
							<!-- <span>Upload Kode Jabatan <a onClick="downloadTemplate()"><i class="fa fa-download"></i></a> </span>
							<span><input type="file" name="reqLinkFile" required class="easyui-validatebox"></span>
							<span><button type="button" class="btn btn-primary" onclick="submitForm()">Submit</button></span> -->
						</form>
					</div>
				</li>
			</ul>
		</div>

		<table id="treeSatker" class="easyui-treegrid" style="width:1270px; height:600px" data-options="
            ">
			<thead>
				<tr>
					<th data-options="field:'NAMA_SATKER'" width="450px">Unit Kerja</th>
					<th data-options="field:'JABATAN'" width="300px">Jabatan</th>
					<th data-options="field:'KELOMPOK_JABATAN'">Kelompok</th>
					<th data-options="field:'KODE_SURAT'">Kode&nbsp;Jabatan</th>
					<th data-options="field:'NAMA_PEGAWAI'">Nama Pejabat</th>
					<th data-options="field:'LOKASI'">Lokasi</th>
					<th data-options="field:'STATUS_AKTIF_DESC'">Status</th>
					<th field="LINK_URL" width="50%" align="center">Aksi</th>
				</tr>
			</thead>
		</table>
	</div>


	<script>
		function downloadTemplate() {
			newWindow = window.open('web/satuan_kerja_json/excel/?reqUnitKerjaId=' + $('#reqUnitKerjaId').combobox('getValue'), 'Cetak');
			newWindow.focus();
		}

		$(document).ready(function() {
			$("#reqPencarian").focus();
			var anSelectedId = "";

			$('#reqUnitKerjaId').combobox({
				url:'web/satuan_kerja_json/combo_cabang',
				onSelect: function(param) {
					var urlApp = "web/satuan_kerja_json/treetable_master/?reqUnitKerjaId="+param.id+"&reqPencarian="+$("#reqPencarian").val();
					$('#treeSatker').treegrid({
						url: urlApp
						, pageSize: 1
					});
				}
			});

			$('input[name=reqPencarian]').keyup(function(e) {
				var value = this.value;
				$("html, body").animate({ scrollTop: 0 });

				if(e.keyCode == 13) {
					var urlApp = 'web/satuan_kerja_json/treetable/?reqUnitKerjaId='+$('#reqUnitKerjaId').combobox("getValue")+'&reqPencarian='+value;
					$('#treeSatker').treegrid(
					{
						url: urlApp
					}); 
				}
			});

			$('#treeSatker').treegrid({
				url: 'web/satuan_kerja_json/treetable_master/',
                pagination: true,            
                pageSize: 50,
				pageList: [50, 100],
                method: 'get',
                idField: 'id',
                treeField: 'NAMA_SATKER',
                onBeforeLoad: function(row,param){
                    if (!row) {    // load top level rows
                        param.id = 0;    // set id=0, indicate to load new page rows
                    }
                }
				, onDblClickRow: function(param) {
					top.tambahPegawai(param.id, param.text);

					$('#treeSatker').treegrid('deleteRow', param.id);
					top.closePopup();
				},
				onClickRow: function(param) {

					$("#btnAktifkan").show();
					$("#btnNonAktifkan").show();

					if (param.STATUS_AKTIF == "1") {
						$("#btnAktifkan").hide();
					}

					if (param.STATUS_AKTIF == "0") {
						$("#btnNonAktifkan").hide();
					}

					anSelectedId = param.id;
				}
			});

			$('#btnAdd').on('click', function() {
				document.location.href = "app/loadUrl/app/master_jabatan_add";
			});

			$('#btnEdit').on('click', function() {
				if (anSelectedId == "") {
					return false;
				}

				document.location.href = "app/loadUrl/app/master_jabatan_add/?reqId=" + anSelectedId;
			});

			$('#btnAktifkan').on('click', function() {
				if (anSelectedId == "") {
					return false;
				}

				konfirmasiAksi("Aktifkan satuan kerja?", "web/satuan_kerja_json/aktif", anSelectedId);
			});


			$('#btnNonAktifkan').on('click', function() {
				if (anSelectedId == "") {
					return false;
				}

				konfirmasiAksi("Non-aktifkan satuan kerja?", "web/satuan_kerja_json/nonaktif", anSelectedId);
			});

			$('#btnDelete').on('click', function() {
				if (anSelectedId == "") {
					return false;
				}

				deleteData("web/satuan_kerja_json/delete", anSelectedId);
			});

		});

		$("#dnd-example tr").click(function() {
			$(this).addClass('selected').siblings().removeClass('selected');
			var id = $(this).find('td:first').attr('id');
			var title = $(this).find('td:first').attr('title');


		});

		function submitForm() {

			$('#ff').form('submit', {
				url: 'web/satuan_kerja_json/add_satker',
				onSubmit: function() {
					if ($(this).form('enableValidation').form('validate')) {
						var win = $.messager.progress({
							title: 'TNDE | PT Angkasa Pura I (Persero)',
							msg: 'proses data...'
						});
					}
					return $(this).form('enableValidation').form('validate');
				},
				success: function(data) {
					// alert(data);
					// return;
					$.messager.progress('close');
					$.messager.alertReload('Info', data, 'info');
				}
			});
		}

		function clearForm() {
			$('#ff').form('clear');
		}
	</script>

	<script>
		// Mendapatkan tinggi .area-konten-atas
		var divTinggi = $(".area-konten-atas").height();
		//alert(divTinggi);

		// Menentukan tinggi tableContainer
		$('#tableContainer').css({
			'height': 'calc(100% - ' + divTinggi + 'px)'
		});
	</script>

	<script>
		var $element = $(window),
			lastWidth = $element.width(),
			lastHeight = $element.height();

		function checkForChanges() {
			if ($element.width() != lastWidth || $element.height() != lastHeight) {
				$('#tableContainer').panel('resize');
				$('#tableContainer').datagrid('resize');
				lastWidth = $element.width();
				lastHeight = $element.height();
			}
			setTimeout(checkForChanges, 500);
		}
		checkForChanges();
	</script>
	
</body>

</html>