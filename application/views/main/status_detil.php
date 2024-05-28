<?php
include_once("functions/string.func.php");
include_once("functions/date.func.php");
$this->load->library('suratmasukinfo');
$this->load->model("SuratMasuk");

$suratmasukinfo= new suratmasukinfo();

$reqId= $this->input->get("reqId");
$reqMode= $this->input->get("reqMode");

$suratmasukinfo->getAkses($reqId, $this->ID);
$aksesSurat= $suratmasukinfo->AKSES;
// echo $aksesSurat;exit;
if(empty($aksesSurat))
{
	redirect("main/index/status");
}

$set= new SuratMasuk();
$set->selectByParams(array("A.SURAT_MASUK_ID"=>$reqId));
$set->firstRow();
// echo $set->query;exit;
$infoperihal= $set->getField("PERIHAL");
$lampirandrive= $set->getField("LAMPIRAN_DRIVE");
// echo $lampirandrive;exit;

$infojenisnaskahid= $set->getField("JENIS_NASKAH_ID");
unset($set);

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

	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>lib/easyui/themes/default/easyui.css">
	<script type="text/javascript" src="<?= base_url() ?>lib/easyui/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="<?= base_url() ?>lib/easyui/kalender-easyui.js"></script>
	<script type="text/javascript" src="<?= base_url() ?>lib/easyui/globalfunction.js"></script>

	<link href="<?= base_url() ?>lib/media/themes/main_datatables.css" rel="stylesheet" type="text/css" />
	<link href="<?= base_url() ?>css/begron.css" rel="stylesheet" type="text/css">
	<link href="<?= base_url() ?>css/bluetabs.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="<?= base_url() ?>js/dropdowntabs.js"></script>
    
    <style>
	.test:after {
	  content: '\2807';
	  *font-size: 3em;
	  color: #2e2e2e
	}
	</style>

	<script type="text/javascript">
		function setkembali()
		{
			document.location.href = 'main/index/<?=$reqMode?>';
		}

		function setagenda()
		{
			<?
			if(empty($reqMode))
			{
			?>
			document.location.href = 'main/index/<?=$infolinkdetil?>?reqMode=<?=$reqMode?>&reqId=<?=$reqId?>';
			<?
			}
			else
			{
			?>
			document.location.href = 'main/index/<?=$infolinkdetil?>?reqMode=<?=$reqMode?>_detil&reqId=<?=$reqId?>';
			<?
			}
			?>
		}
	</script>
</head>

<body style="overflow:hidden;">
	<div id="begron"><img src="<?= base_url() ?>images/bg-kanan.jpg" width="100%" height="100%" alt="Smile"></div>
	<div id="wadah">
		<div class="judul-halaman" >
			<?
			if(!empty($reqMode))
			{
			?>
			<a href="javascript:void(0)" onclick="setkembali()"><i class="fa fa-chevron-left" aria-hidden="true"></i></a> 
			<?
			}
			?>
        	<!-- <?=$infoperihal?> -->
        	<span class="info-perihal"><?=$infoperihal?></span>            
            <div class="dropdown yamm-fw notifikasi pull-right area-button-judul"><a href="#" data-toggle="dropdown" class="dropdown-toggle"><i class="fa fa-ellipsis-v" aria-hidden="true"></i></a>
                <ul class="dropdown-menu">
                    <li>
                        <div class="area-3dots-menu">
                            <div><a href="javascript:void(0)" onclick="setagenda()">Agenda Surat</a></div>
                            <div><a onclick="window.print()"><i class="fa fa-print"></i> Print</a></div>
                        </div>
                    </li>
                </ul>
            </div>
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
                <?if ($lampirandrive!==''){?>
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
</body>

</html>