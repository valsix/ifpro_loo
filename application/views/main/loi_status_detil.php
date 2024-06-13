<?php
include_once("functions/string.func.php");
include_once("functions/date.func.php");
$this->load->library('ReportLoo');
$this->load->library('suratmasukinfo');
$this->load->model("TrLoi");

$rloo= new ReportLoo();
$suratmasukinfo= new suratmasukinfo();

$reqId= $this->input->get("reqId");
$reqMode= $this->input->get("reqMode");

/*$set= new TrLoi();
$statement= " 
--AND SM_INFO NOT IN ('AKAN_DISETUJUI', 'NEXT_DISETUJUI')
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
) AND A.STATUS_SURAT IN ('VALIDASI', 'PARAF', 'PEMBUAT')";

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
				
$sOrder= "";
// $set->selectByParamsPersetujuan(array("A.TR_LOI_ID"=>$reqId), -1, -1, $this->ID, $statement, $sOrder);
$set->selectByParamsNewPersetujuan(array("A.TR_LOI_ID"=>$reqId), -1, -1, $this->ID, $this->USER_GROUP, $statement, $sOrder, $reqId, $satuankerjaganti);
$set->firstRow();
 // echo $set->query;exit;
$checkid= $set->getField("TR_LOI_ID");
$checkstatusbantu= $set->getField("STATUS_BANTU");
$checkstatussurat= $set->getField("STATUS_SURAT");
$checkuserid= $set->getField("USER_ID");
// echo $checkstatussurat;exit;
$checkinfonomorsurat= $set->getField("INFO_NOMOR_SURAT");
// echo $checkinfonomorsurat;exit;
$arrinfonomorsurat= explode("[...]", $checkinfonomorsurat);
// print_r($arrinfonomorsurat);exit;

// untuk ambil data nomor berdasarkan tanggal entri
$tanggalapproval= date("d-m-Y");
// $setlast= new TrLoi();
// $setlast->selectByParamsInfoLastNomorSurat(array("A.TR_LOI_ID"=>$reqId), -1, -1);
// $setlast->firstRow();
// $checkinfolastnomorsurat= $setlast->getField("INFO_NOMOR_SURAT");
$setlast= new TrLoi();
$setlast->selectByParamsCheckNomor("GETINFO", $reqId, "", dateToDbCheck($tanggalapproval));
// echo $setlast->query;exit;
$setlast->firstRow();
$checkinfolastnomorsurat= $setlast->getField("INFO_NOMOR_SURAT");
// echo $checkinfolastnomorsurat;exit;
unset($setlast);

$checkpernahsetujui= 0;
if (empty($checkid))
{
	$set= new TrLoi();
	$statement= " AND SM_INFO IN ('AKAN_DISETUJUI', 'PEMBUAT') AND A.STATUS_SURAT IN ('PARAF', 'VALIDASI', 'PEMBUAT')";
	$checkpernahsetujui= $set->getCountByParamsStatus(array("A.TR_LOI_ID"=>$reqId), $this->ID, $statement);
	// echo $set->query;exit;

	if($checkpernahsetujui > 0)
	{
		$set= new TrLoi();
        $set->selectByParams(array("A.TR_LOI_ID"=>$reqId));
        $set->firstRow();
        // echo $set->query;exit;
        $infojenisnaskahid= $set->getField("JENIS_NASKAH_ID");
        unset($set);

        $arrlink= $suratmasukinfo->infolinkdetil($infojenisnaskahid);
        $infolinkdetil= $arrlink["linkstatusdetil"];
        // echo "main/index/".$infolinkdetil."/?reqMode=".$reqMode."&reqId=".$reqId;exit;

        redirect("main/index/".$infolinkdetil."/?reqStatusSurat=AKAN_DISETUJUI&reqMode=".$reqMode."&reqId=".$reqId);
	}
	else
	{
		redirect("main/index/perlu_persetujuan");
	}
}*/

$set= new TrLoi();
$set->selectByParams(array("A.TR_LOI_ID"=>$reqId));
$set->firstRow();
// echo $set->query;exit;
$infojenissurat= $set->getField("JENIS_SURAT");
$lampirandrive= $set->getField("LAMPIRAN_DRIVE");
$infoperihal= $set->getField("PERIHAL");
$infojenisnaskahid= $set->getField("JENIS_NASKAH_ID");
$reqSatuanKerjaId= $set->getField("SATUAN_KERJA_ID_ASAL");
$reqJenisSurat= $set->getField("JENIS_SURAT");
$reqStatusSurat= $set->getField("STATUS_DATA");
// echo $reqJenisSurat;exit;
unset($set);

$sessid= $this->ID;
$checkparafid= "";
if (!empty($reqId))
{
    /*$statement.= " AND A.USER_POSISI_PARAF_ID = '".$sessid."' AND A.TR_LOI_ID = ".$reqId;
    $set= new TrLoi();
    $set->selectdraft(array(), -1, -1, $statement);
    // echo $set->query;exit;
    $set->firstRow();
    $checkparafid= $set->getField("TR_LOI_ID");
    $checknextpemaraf= $set->getField("NEXT_URUT");
    $checkstatusbantu= $set->getField("STATUS_BANTU");
    $chekvalidasi= "";
    if(isset($checknextpemaraf))
        $chekvalidasi= "validasi";
    // echo $chekvalidasi."-".$checknextpemaraf."--".$infonextpemaraf;exit;

    if (empty($checkparafid) && empty($reqId) && !empty($reqMode))
    {
        redirect("main/index/".$reqMode);
    }
    else
    {
        redirect("main/index/loi_perlu_persetujuan");
    }*/
}
$arrparam= ["reqId"=>$reqId, "reqStatusSurat"=>$reqStatusSurat];
$rloo->setterbaca($arrparam);

$infolinkdetil= $reqMode;
$infolinkedit= "loi_add";

$arrattachment= array();
$index_data= 0;
$set= new TrLoi();
$set->selectByParamsAttachment(array("A.TR_LOI_ID" => (int)$reqId));
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
	<base href="<?=base_url()?>">
	<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
	<title>Diklat</title>
	<link rel="shortcut icon" type="image/ico" href="http://www.datatables.net/media/images/favicon.ico">
	<!--<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="http://www.datatables.net/rss.xml">-->
	<link href="css/admin.css" rel="stylesheet" type="text/css">

	<!-- Custom Fonts -->
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
	<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.6/examples/resources/syntax/shCore.js"></script>
	<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.6/examples/resources/demo.js"></script>
	<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.6/extensions/FixedColumns/js/dataTables.fixedColumns.js"></script>
	<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.6/extensions/TableTools/js/dataTables.tableTools.min.js"></script>

	<link href="lib/media/themes/main_datatables.css" rel="stylesheet" type="text/css" />
	<link href="css/begron.css" rel="stylesheet" type="text/css">
	<link href="css/bluetabs.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="js/dropdowntabs.js"></script>
    
    <style>
	.test:after {
	  content: '\2807';
	  *font-size: 3em;
	  color: #2e2e2e
	}
	</style>

	<script type="text/javascript">
		function setagenda()
		{
			document.location.href = 'main/index/<?=$infolinkdetil?>?reqMode=<?=$reqMode?>_detil&reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>';
		}

		function setkembali()
		{
			document.location.href = 'main/index/<?=$reqMode?>';
		}

	</script>
    
</head>

<body style="overflow:hidden;">
	<div id="begron"><img src="images/bg-kanan.jpg" width="100%" height="100%" alt="Smile"></div>
	<div id="wadah">
		<div class="judul-halaman">
        	<a href="javascript:void(0)" onclick="setkembali()"><i class="fa fa-chevron-left" aria-hidden="true"></i></a> 
            <span class="info-perihal"><?=$infoperihal?></span>
            <!-- <div class="dropdown yamm-fw notifikasi pull-right area-button-judul">
            	<a href="#" data-toggle="dropdown" class="dropdown-toggle"><i class="fa fa-ellipsis-v" aria-hidden="true"></i></a>
                <ul class="dropdown-menu">
                    <li>
                        <div class="area-3dots-menu">
                            <div><a href="javascript:void(0)" onclick="setagenda()">Agenda Surat</a></div>
                        </div>
                    </li>
                </ul>
            </div> -->
            <div class="area-button-judul pull-right">
				<input type="hidden" name="reqInfoLog" id="reqInfoLog" />
				<input type="hidden" name="reqInfoNomor" id="reqInfoNomor" />
            </div>
            <div class="clearfix"></div>
        </div>

        <?
        $classlampiran= "";
        if($jumlahattachment > 0)
        {
        	$classlampiran= "ada-lampiran";
        }
        ?>
        <div class="konten-pdf <?=$classlampiran?>">
        	<iframe name="contentFrame" src="app/loadUrl/report/loo_cetak/?reqId=<?=$reqId?>&templateSurat=loi"></iframe>
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


    <!-- jQUERY CONFIRM MASTER -->
    <link rel="stylesheet" type="text/css" href="lib/jquery-confirm-master/css/jquery-confirm.css"/>
    <script type="text/javascript" src="lib/jquery-confirm-master/js/jquery-confirm.js"></script>

    <link rel="stylesheet" href="lib/js/jquery-ui.css">
    <script src="lib/js/jquery-ui.js"></script>

    <link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
	<script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="lib/easyui/kalender-easyui.js"></script>
	<script type="text/javascript" src="lib/easyui/globalfunction.js"></script>
    
</body>

</html>