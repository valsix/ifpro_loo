<?php
include_once("functions/string.func.php");
include_once("functions/date.func.php");
$this->load->model("SuratMasuk");

$reqFilename = $this->uri->segment(3, "");

$tinggi = 360;

$reqStatusSurat= $this->input->get("reqStatusSurat");

$set= new SuratMasuk();

// $statement= " AND ((A.USER_ATASAN_ID = '".$this->ID."' AND  A.APPROVAL_DATE IS NULL) OR (A.USER_ATASAN_ID = '".$this->USER_GROUP.$this->ID."' AND A.APPROVAL_DATE IS NOT NULL )) AND A.STATUS_SURAT IN ('VALIDASI', 'PARAF') ";
$statement= "
AND
(
	(
		(
			A.USER_ATASAN_ID = '".$this->ID."' AND A.APPROVAL_DATE IS NULL AND COALESCE(NULLIF(A.NIP_ATASAN_MUTASI, ''), NULL) IS NULL
			AND TERPARAF IS NULL
			--AND CASE WHEN A.STATUS_SURAT = 'PEMBUAT' THEN A.USER_ATASAN_ID = A.USER_ID END
		)
		OR 
		(
			A.NIP_ATASAN_MUTASI = '".$this->ID."' AND A.APPROVAL_DATE IS NULL AND COALESCE(NULLIF(A.USER_ATASAN_ID, ''), NULL) IS NOT NULL
			AND TERPARAF IS NULL
			-- TAMBAHAN ONE TES
			AND A.USER_ID IS NOT NULL
			--AND CASE WHEN A.STATUS_SURAT = 'PEMBUAT' THEN A.USER_ATASAN_ID = A.USER_ID END
		)
	) 
	OR 
	(
		(
			A.USER_ATASAN_ID = '".$this->USER_GROUP.$this->ID."' AND A.APPROVAL_DATE IS NOT NULL AND COALESCE(NULLIF(A.NIP_ATASAN_MUTASI, ''), NULL) IS NULL
		)
		OR 
		(
			A.NIP_ATASAN_MUTASI = '".$this->USER_GROUP.$this->ID."' AND A.APPROVAL_DATE IS NOT NULL AND COALESCE(NULLIF(A.USER_ATASAN_ID, ''), NULL) IS NOT NULL
		)
	)
	OR 
	(
		A.USER_ID = '".$this->ID."'
		AND CASE WHEN A.USER_ID = '".$this->ID."' THEN TERPARAF IS NOT NULL ELSE TERPARAF IS NULL END
		AND A.STATUS_SURAT = 'PEMBUAT'
	)
	OR 
	(
		A.USER_ID = '".$this->ID."'
		AND CASE WHEN A.USER_ID = '".$this->ID."' THEN TERPARAF IS NULL ELSE TERPARAF IS NOT NULL END
		AND A.STATUS_SURAT != 'PEMBUAT'
	)
)
AND A.STATUS_SURAT IN ('VALIDASI', 'PARAF', 'PEMBUAT') ";

$satuankerjaganti= "";
if($this->SATUAN_KERJA_ID_ASAL_ASLI == $this->SATUAN_KERJA_ID_ASAL)
{
}
else
{
	$satuankerjaganti= $this->SATUAN_KERJA_ID_ASAL;
}
$satuankerjaganti= $this->SATUAN_KERJA_ID_ASAL;

// if ($satuankerjaganti == "PST1164000") 
// 				$satuankerjaganti= "PST2000000";

// $jumlahperlupersetujuan= $set->getCountByParamsPersetujuan(array(), $this->ID, $statement);
$jumlahperlupersetujuan= $set->getCountByParamsNewPersetujuan(array(), $this->ID, $this->USER_GROUP, $statement, "", $satuankerjaganti);
// echo $set->query;exit;

$statement= "
AND SM_INFO IN ('AKAN_DISETUJUI', 'NEXT_DISETUJUI')
AND CASE WHEN A.TERPARAF = 1 AND A.JENIS_NASKAH_ID IN (8,17,18,19,20) THEN A.STATUS_SURAT IN ('PARAF', 'VALIDASI', 'PEMBUAT') ELSE A.STATUS_SURAT IN ('PARAF', 'VALIDASI') END
";

$satuankerjaganti= "";
if($this->SATUAN_KERJA_ID_ASAL_ASLI == $this->SATUAN_KERJA_ID_ASAL)
{
}
else
{
	$satuankerjaganti= $this->SATUAN_KERJA_ID_ASAL;
	// $statement.= " and exists
	// (
	// 	select 1
	// 	from
	// 	(
	// 		select a.surat_masuk_id
	// 		FROM surat_masuk a
	// 		JOIN surat_masuk_paraf b ON a.surat_masuk_id = b.surat_masuk_id and coalesce(nullif(b.status_paraf, ''), 'x') = 'x'
	// 		WHERE 1 = 1
	// 		and a.status_surat = 'PARAF'
	// 		and b.satuan_kerja_id_tujuan = '".$satuankerjaganti."'
	// 		group by a.surat_masuk_id
	// 	) xxx where a.surat_masuk_id = xxx.surat_masuk_id
	// )";
}
$infostatus= "1";
$satuankerjaganti= $this->SATUAN_KERJA_ID_ASAL;
$jumlahakandisetujui= $set->getCountByParamsStatusTujuan(array(), $this->ID, $infostatus, $satuankerjaganti, $statement);
// $jumlahakandisetujui= $set->getCountByParamsStatus(array(), $this->ID, $statement);
// echo $set->query;exit;

if(empty($reqStatusSurat))
	$reqStatusSurat= "PERLU_PERSETUJUAN";

$arrdata= array(
	array("label"=>"TANGGAL", "width"=>"100")
	, array("label"=>"NO. SURAT", "width"=>"")
	, array("label"=>"PERIHAL", "width"=>"")
	, array("label"=>"STATUS", "width"=>"")
	, array("label"=>"MENUNGGU PERSETUJUAN", "width"=>"")
	, array("label"=>"SISA STEP", "width"=>"")
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
				"sAjaxSource": "web/surat_masuk_json/jsonpersetujuan?reqStatusSurat=<?=$reqStatusSurat?>",
				"sScrollY": ($(window).height() - <?= $tinggi ?>),
				"sScrollX": "100%",
				"sScrollXInner": "100%",
				"sPaginationType": "full_numbers",
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
					// anSelectedId= anSelectedId[anSelectedId.length-1];
					anSelectedId= "";
				}
				// alert("--"+anSelectedId);

				if(anSelectedId !== "")
				window.location = ("<?= base_url() ?>main/index/perlu_persetujuan_detil/?reqMode=perlu_persetujuan&reqId=" + anSelectedId);
			});

			$("#reqStatusSurat").change(function() {
				setCariInfo();
			});

			$('#btnCari').on('click', function () {
				var reqStatusSurat= "";
				reqStatusSurat= $("#reqStatusSurat").val();

				document.location.href = 'main/index/<?= $reqFilename ?>?reqStatusSurat='+reqStatusSurat;
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
				<!-- <li>
					<a id="btnAdd" title="Tambah"><img src="<?= base_url() ?>images/icon-tambah.png" /> Tambah</a>
					<a id="btnEdit" title="Ubah"><img src="<?= base_url() ?>images/icon-edit.png" /> Ubah</a>
					<a id="btnDelete" title="Hapus"><img src="<?= base_url() ?>images/icon-hapus.png" /> Hapus</a>
				</li> -->
                <li class="pull-right">
                	<select id="reqStatusSurat">
                        <option value="PERLU_PERSETUJUAN" <? if($reqStatusSurat == "PERLU_PERSETUJUAN") echo "selected";?>>Perlu Persetujuan (<?=$jumlahperlupersetujuan?>)</option>
                        <option value="AKAN_DISETUJUI" <? if($reqStatusSurat == "AKAN_DISETUJUI") echo "selected";?>>Akan Disetujui (<?=$jumlahakandisetujui?>)</option>
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