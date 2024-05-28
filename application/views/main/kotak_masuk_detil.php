<?php
include_once("functions/string.func.php");
include_once("functions/date.func.php");
$this->load->library('suratmasukinfo');
$this->load->model("SuratMasuk");
$this->load->model("Disposisi");
$this->load->model("SatuanKerja");

$suratmasukinfo= new suratmasukinfo();

$reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqMode= $this->input->get("reqMode");
$reqPilihan= $this->input->get("reqPilihan");
$cekquery= $this->input->get("c");

$infodivisi= "";
if($reqPilihan == "divisi")
    $infodivisi= "1";

$infogantijabatan= "";
if(in_array("SURAT", explode(",", $this->USER_GROUP))){}
else
{
    if($this->SATUAN_KERJA_ID_ASAL_ASLI == $this->SATUAN_KERJA_ID_ASAL){}
    else
    {
        $infogantijabatan= $this->SATUAN_KERJA_ID_ASAL;
    }
}

$suratmasukinfo->getAkses($reqId, $this->ID, $infodivisi, $infogantijabatan);
$aksesSurat= $suratmasukinfo->AKSES;
// echo $aksesSurat; //exit;
if(empty($aksesSurat))
{
    if(empty($reqMode))
        $reqMode= "kotak_masuk";
    redirect("main/index/".$reqMode);
}

$set= new SuratMasuk();
$set->selectByParams(array("A.SURAT_MASUK_ID"=>$reqId));
$set->firstRow();
// echo $set->query;exit;
$infoperihal= $set->getField("PERIHAL");
$lampirandrive= $set->getField("LAMPIRAN_DRIVE");
$infojenisnaskahid= $set->getField("JENIS_NASKAH_ID");
$infouserid= $set->getField("USER_ID");
unset($set);

$aksibutton= "";
if(($reqPilihan === 'divisi') && ($infouserid!==$this->ID))
    $aksibutton= "1";

$arrlink= $suratmasukinfo->infolinkdetil($infojenisnaskahid);
$infolinkdetil= $arrlink["linkstatusdetil"];
// print_r($arrlink);//exit;

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
    $arrattachment[$index_data]["SURAT_MASUK_ATTACHMENT_ID"] = $set->getField("SURAT_MASUK_ATTACHMENT_ID");
    $index_data++;
}
$jumlahattachment= $index_data;

if(!empty($reqRowId))
{
    // proses baca
    $setdetil= new Disposisi();
    $setdetil->selectByParams(array("A.DISPOSISI_ID"=>$reqRowId), -1, -1);
    $setdetil->firstRow();
    $infodisposisistatus= $setdetil->getField("STATUS_DISPOSISI");
    $infostatusbantu= $setdetil->getField("STATUS_BANTU");
    $infonamasatker= $setdetil->getField("NAMA_SATKER");
    $infosatuankerjaidtujuan= $setdetil->getField("SATUAN_KERJA_ID_TUJUAN");
    $infodisposisiterbaca= $setdetil->getField("TERBACA");
    $infodisposisiterbacainfo= $setdetil->getField("TERBACA_INFO");
    // echo $infodisposisiterbacainfo;exit;
    unset($setdetil);

    if($infodisposisistatus == "TUJUAN" || $infodisposisistatus == "TEMBUSAN")
    {
        $simpan= "";

        // echo $infodisposisistatus;exit;
        $setdetil= new Disposisi();
        // $setdetil->selectByParams(array("A.DISPOSISI_ID"=>$reqRowId , "A.USER_ID"=> $this->ID), -1, -1, " AND DISPOSISI_KELOMPOK_ID = 0");
        $statementdetil= " AND 
        (
            (
                A.USER_ID = '".$this->ID."' AND A.STATUS_BANTU IS NULL
            )
            OR
            EXISTS
            (
                SELECT 1
                FROM
                (
                    SELECT NIP, SATUAN_KERJA_ID FROM SATUAN_KERJA_FIX WHERE USER_BANTU = '".$this->ID."'
                ) XXX WHERE XXX.NIP = A.USER_ID --AND A.STATUS_BANTU = 1
                AND
                EXISTS
                (
                    SELECT 1
                    FROM
                    (
                        SELECT DISTINCT DISPOSISI_ID
                        FROM
                        (
                            SELECT DISPOSISI_ID
                            FROM DISPOSISI WHERE STATUS_BANTU = 1
                            -- AND SURAT_MASUK_ID IN
                            -- (
                            --     SELECT SURAT_MASUK_ID FROM SURAT_MASUK WHERE JENIS_NASKAH_ID = 1
                            -- )
                        ) A
                    ) YYY WHERE A.DISPOSISI_ID = YYY.DISPOSISI_ID
                )
            )
            OR
            EXISTS
            (
                SELECT 1
                FROM
                (
                    SELECT
                    CASE WHEN COALESCE(NULLIF(NIP, ''), 'X') = 'X' THEN NIP_OBSERVER ELSE NIP END NIP_OBSERVER
                    , SATUAN_KERJA_ID
                    FROM SATUAN_KERJA_FIX WHERE 1=1
                    AND (NIP_OBSERVER = '".$this->ID."' OR NIP = '".$this->ID."')
                ) X WHERE X.SATUAN_KERJA_ID = A.SATUAN_KERJA_ID_TUJUAN
                AND
                EXISTS
                (
                    SELECT 1
                    FROM
                    (
                        SELECT DISTINCT DISPOSISI_ID
                        FROM
                        (
                            SELECT DISPOSISI_ID
                            FROM DISPOSISI WHERE STATUS_BANTU IS NULL
                            -- AND SURAT_MASUK_ID IN
                            -- (
                            --     SELECT SURAT_MASUK_ID FROM SURAT_MASUK WHERE JENIS_NASKAH_ID = 1
                            -- )
                            -- UNION ALL
                            -- SELECT DISPOSISI_ID
                            -- FROM DISPOSISI WHERE
                            -- SURAT_MASUK_ID IN
                            -- (
                            --     SELECT SURAT_MASUK_ID FROM SURAT_MASUK WHERE JENIS_NASKAH_ID NOT IN (1)
                            -- )
                        ) A
                    ) YYY WHERE A.DISPOSISI_ID = YYY.DISPOSISI_ID
                )
            )
            
        )";
        $setdetil->selectByParams(array("A.DISPOSISI_ID"=>$reqRowId), -1, -1, $statementdetil." AND DISPOSISI_KELOMPOK_ID = 0");
        $setdetil->firstRow();

        if($cekquery == "q1")
        {
            echo $setdetil->query;exit;
        }
        $infodisposisiawaluserid= $infodisposisiuserid= $setdetil->getField("USER_ID");
        $infodisposisinipmutasi= $setdetil->getField("NIP_MUTASI");
        $infodisposisistatusbantu= $setdetil->getField("STATUS_BANTU");
        $infodisposisipejabatrehatsekarang= $setdetil->getField("PEJABAT_REHAT_SEKARANG_NIP");
        $infodisposisipejabatrehatcheck= $setdetil->getField("PEJABAT_REHAT_CHECK");
        // echo $infodisposisiuserid.";".$infodisposisistatusbantu.";".$infodisposisinipmutasi.";".$infodisposisipejabatrehatsekarang.";".$infodisposisipejabatrehatcheck;exit;

        // kalau jenis naskah surat keluar maka check dulu user bantu
        // if($infodisposisistatusbantu == "1" && ($infojenisnaskahid == 15 || $infojenisnaskahid == 1))
        // if($infodisposisistatusbantu == "1" && ($infojenisnaskahid == 1))
        if($infodisposisistatusbantu == "1")
        {
            $userbantu= new SatuanKerja();
            $userbantu->selectByParams(array(),-1,-1, " AND A.SATUAN_KERJA_ID = '".$setdetil->getField("SATUAN_KERJA_ID_TUJUAN")."'");
            $userbantu->firstRow();
            // echo $userbantu->query;exit;
            $infodisposisiuserid= $userbantu->getField("USER_BANTU");
            // echo $infodisposisiuserid;exit;
            unset($userbantu);
        }

        if($infodisposisipejabatrehatcheck == 0)
        {
            $infodisposisiuserid= $this->ID;
        }

        // if(!empty($infodisposisinipmutasi))
        // TAK KOMEN SIK
        /*if(!empty($infodisposisinipmutasi) && $infodisposisiawaluserid !== $infodisposisinipmutasi)
        {
            if($reqRowId == "432530" && $this->ID == "107991816")
            {
                // echo $infodisposisiuserid."==".$infodisposisinipmutasi;exit;
                // echo $setdetil->query;exit;
            }
            $infodisposisiuserid= $infodisposisinipmutasi;
        }*/

        // echo $infodisposisistatusbantu."--".$infodisposisiuserid."--".$this->ID;exit;
        // kalau nilai kosong maka bisa aksi
        $aksibutton= "";
        // if($infodisposisiuserid !== $this->ID && $infodisposisistatusbantu == "1")
        if($infodisposisiuserid !== $this->ID)
            $aksibutton= "1";

        if($reqPilihan === 'divisi')
            $aksibutton= "1";

        // kalau user tidak sama, langsung ambil id user sesuai login
        if($infodisposisiuserid !== $this->ID)
        {
            $infodisposisiuserid= $this->ID;
        }

        $setterbaca= $infodisposisiuserid.",".date("Y-m-d H:i:s");

        if($cekquery == "setterbaca")
        {
            echo $this->ID."\n";
            echo $setterbaca;exit;
        }
        
        unset($setdetil);

        if(!empty($infodisposisiuserid))
        {
            $simpan= "";
        }
        else
        {
            $setdetil= new Disposisi();
            $setdetil->selectByParamsPara(array("A.DISPOSISI_ID"=>$reqRowId , "XXX.PEGAWAI_ID"=> $this->ID), -1, -1, " AND DISPOSISI_KELOMPOK_ID > 0");
            $setdetil->firstRow();
            // echo $setdetil->query;exit;
            $infodisposisiuserid= $setdetil->getField("PEGAWAI_ID");
            $setterbaca= $infodisposisiuserid.",".date("Y-m-d H:i:s");

            if(empty($infodisposisiuserid))
            {
                $simpan= "1";
            }
        }
        // echo $simpan.$infodisposisiuserid;exit;
        
        $arrcheckterbaca= explode(";", $infodisposisiterbacainfo);
        if(!empty($arrcheckterbaca) && !empty($infodisposisiterbacainfo))
        {
            while (list($key, $val) = each($arrcheckterbaca))
            {
                $arrcheckterbacadetil= explode(",", $val);
                if($infodisposisiuserid == $arrcheckterbacadetil[0])
                {
                    $simpan= "1";
                    break;
                }
            }
            // echo $simpan;
            // print_r($arrcheckterbaca);exit;
        }

        if(empty($simpan))
        {
            // echo $infodisposisiterbacainfo;exit;
            if(empty($infodisposisiterbacainfo))
            {
                $infodisposisiterbacainfo= $setterbaca;
            }
            else
            {
                $infodisposisiterbacainfo= $infodisposisiterbacainfo.";".$setterbaca;
            }

            /*if(in_array("SURAT", explode(",", $this->USER_GROUP)))
            {
                $aksibutton= "1";
            }
            else
            {
                $setdetil= new Disposisi();
                $setdetil->setField("DISPOSISI_ID", $reqRowId);
                $setdetil->setField("TERBACA_INFO", $infodisposisiterbacainfo);
                $setdetil->updateterbaca();
                // echo $setdetil->query;exit;
            }*/

            // kalau ada id dan user yg membaca
            if(!empty($reqRowId) && !empty($infodisposisiterbacainfo))
            {
                $setdetil= new Disposisi();
                $setdetil->setField("DISPOSISI_ID", $reqRowId);
                $setdetil->setField("TERBACA_INFO", $infodisposisiterbacainfo);
                $setdetil->updateterbaca();

                if($cekquery == "updateterbaca")
                {
                    echo $setdetil->query;exit;
                }
            }
        }

        /*if(empty($infodisposisiterbaca))
        {
            $setdetil= new Disposisi();
            $setdetil->setField("DISPOSISI_ID", $reqRowId);
            $setdetil->updatestatusterbaca();
            // echo $setdetil->query;exit;   
        }*/
    }
}

if($infojenisnaskahid == "1")
{
    // exit;
    redirect("main/index/surat_masuk_manual_lihat?reqMode=".$reqMode."&reqId=".$reqId."&reqRowId=".$reqRowId);
}

$checkdisposisi= new Disposisi();
$statement= " AND A.STATUS_BANTU IS NULL AND A.SURAT_MASUK_ID = ".$reqId." AND A.SATUAN_KERJA_ID_TUJUAN = '".$infosatuankerjaidtujuan."'";
$checkdisposisi->selectByParams(array(), -1,-1, $statement);
$checkdisposisi->firstRow();
// echo $checkdisposisi->query;exit;
$checkdisposisiid= $checkdisposisi->getField("DISPOSISI_ID");
$checkdisposisikelompokid= $checkdisposisi->getField("DISPOSISI_KELOMPOK_ID");
// echo $infostatusbantu;exit;
$infoteruskan= "";
if($infostatusbantu == "1" && empty($checkdisposisiid))
{
    $infoteruskan= "1";
}

if($checkdisposisikelompokid > 0)
{
    $infoteruskan= "1";

    $userbantu= new SatuanKerja();
    $userbantu->selectByParams(array(),-1,-1, " AND A.USER_BANTU = '".$this->ID."'");
    $userbantu->firstRow();
    // echo $userbantu->query;exit;
    $infonamasatker= $userbantu->getField("JABATAN");
    $infosatuankerjaidtujuan= $userbantu->getField("SATUAN_KERJA_ID");
    unset($userbantu);

    $checkdisposisi= new Disposisi();
    $statement= " AND A.STATUS_BANTU IS NULL AND A.SURAT_MASUK_ID = ".$reqId." AND A.SATUAN_KERJA_ID_TUJUAN = '".$infosatuankerjaidtujuan."'";
    $checkdisposisi->selectByParams(array(), -1,-1, $statement);
    $checkdisposisi->firstRow();
    // echo $checkdisposisi->query;exit;
    $checkdisposisiid= $checkdisposisi->getField("DISPOSISI_ID");

    if(!empty($checkdisposisiid))
    {
        $infoteruskan= "";
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>

<head>
	<base href="<?= base_url() ?>">
	<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
	<title>Diklat</title>
	<link rel="shortcut icon" type="image/ico" href="https://www.datatables.net/media/images/favicon.ico">
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
    
	<script type="text/javascript">
		function setkembali()
		{
			document.location.href = 'main/index/<?=$reqMode?>';
		}

		function setagenda()
		{
			document.location.href = 'main/index/<?=$infolinkdetil?>?reqMode=<?=$reqMode?>_detil&reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>';
		}

		function setdisposisi()
		{
			document.location.href = 'main/index/kotak_masuk_input?reqMode=<?=$reqMode?>_detil&reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>';
		}

        function setreply(urllink)
        {
            document.location.href = 'main/index/'+urllink+'?reqReplyId=<?=$reqId?>';
        }

		function setriwayat()
		{
			document.location.href = 'main/index/kotak_masuk_riwayat?reqMode=<?=$reqMode?>_detil&reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>';
		}

        function setteruskan()
        {
            $.messager.confirm('Konfirmasi', "Apakah anda yakin di teruskan ke <?=$infonamasatker?> ", function(r) {
                if (r) {
                    urllink= "web/surat_masuk_json/disposisiteruskan";
                    reqId= "<?=$reqId?>";
                    reqRowId= "<?=$reqRowId?>";
                    reqDisposisiKelompokId= "<?=$checkdisposisikelompokid?>";
                    reqSatuanKerjaIdTujuan= "<?=$infosatuankerjaidtujuan?>";
                    var win = $.messager.progress({title:'Proses simpan data', msg:'Proses simpan data...'});

                    method= "POST";

                    $.ajax({
                        url: urllink,
                        method: method,
                        data: {
                            reqId: reqId, 
                            reqRowId: reqRowId,
                            reqDisposisiKelompokId: reqDisposisiKelompokId,
                            reqSatuanKerjaIdTujuan: reqSatuanKerjaIdTujuan
                        },
                        success: function (response) {
                            $.messager.progress('close');
                            // console.log(response);return false;
                            $.messager.alertTopLink('Info', response, 'info', 'main/index/kotak_masuk_detil/?reqMode=<?=$reqMode?>&reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>');
                        },
                        error: function (response) {
                        },
                        complete: function () {
                        }
                    });
                }
            });
        }

        function down(attach_id)
        {
            window.open("down?reqId=<?=$reqId?>&reqAttachId="+attach_id, 'Cetak');
        }
	</script>
</head>

<body style="overflow:hidden;">
	<div id="begron"><img src="<?= base_url() ?>images/bg-kanan.jpg" width="100%" height="100%" alt="Smile"></div>
	<div id="wadah">
		<div class="judul-halaman">
			<?
			if(!empty($reqMode))
			{
			?>
			<a href="javascript:void(0)" onclick="setkembali()"><i class="fa fa-chevron-left" aria-hidden="true"></i></a> 
			<?
			}
			?>
        	<span class="info-perihal"><?=$infoperihal?></span> 
            <div class="dropdown yamm-fw notifikasi pull-right area-button-judul">
            	<a href="#" data-toggle="dropdown" class="dropdown-toggle"><i class="fa fa-ellipsis-v" aria-hidden="true"></i></a>
                <ul class="dropdown-menu">
                    <li>
                        <div class="area-3dots-menu">
                            <?
                            if(empty($aksibutton))
                            {
                            ?>
                        	<div><a href="javascript:void(0)" onclick="setdisposisi()">Buat Disposisi</a></div>
                            <?
                            }
                            ?>
                            <?
                            if($infoteruskan == "1")
                            {
                            ?>
                            <div><a href="javascript:void(0)" onclick="setteruskan()">Teruskan</a></div>
                            <?
                            }
                            ?>
                            <div><a href="javascript:void(0)" onclick="setagenda()">Agenda Surat</a></div>
                            <!-- <div><a onclick="window.print()"><i class="fa fa-print"></i> Print</a></div> -->
                            <div><a href="app/loadUrl/report/template/?reqId=<?=$reqId?>&reqPilihan=<?=$reqPilihan?>" target="_blank"><i class="fa fa-print"></i> Print Surat</a></div>
                            <div><a href="javascript:void(0)" onclick="setriwayat()">Riwayat Surat</a></div>
                        </div>
                    </li>
                </ul>
            </div>
            <?
            if(!empty(infobuttonreply($reqMode)))
            {
                if(empty($aksibutton))
                {
            ?>
            <div class="dropdown yamm-fw notifikasi pull-right area-button-judul">
            	<a href="#" data-toggle="dropdown" class="dropdown-toggle"><i class="fa fa-reply" aria-hidden="true"></i></a>
                <ul class="dropdown-menu">
                    <li>
                        <div class="area-3dots-menu">
                            <div><a href="javascript:void(0)" onclick="setreply('nota_dinas_add');">Nota Dinas</a></div>
                            <div><a href="javascript:void(0)" onclick="setreply('surat_keluar_add');">Surat Keluar</a></div>
                            <div><a href="javascript:void(0)" onclick="setreply('surat_edaran_add');">Surat Edaran</a></div>
                        </div>
                    </li>
                </ul>
            </div>
            <?
                }
            }
            ?>
        </div>

        <?
        $classlampiran= "";
        if($jumlahattachment > 0)
        {
        	$classlampiran= "ada-lampiran";
        }
        ?>
        <div class="konten-pdf <?=$classlampiran?>">
        	<iframe name="contentFrame" src="app/loadUrl/report/template/?reqId=<?=$reqId?>&reqPilihan=<?=$reqPilihan?>"></iframe>
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
                    $attid= $arrattachment[$index_data]["SURAT_MASUK_ATTACHMENT_ID"];
        			$atticon= infoiconlink($atttipe);
        		?>
                <li>
                	<div class="item">
                        <div class="ikon">
                            <?=$attnama?> <i class="fa <?=$atticon?>"></i>
                        </div>
                        <div class="ukuran-file">
                            <?=round(($attukuran/1024), 2)?> kb

                            <?
                            if(in_array(strtolower($atttipe), $arrexcept))
                            {
                                ?>
                                <a onClick="down('<?=$attid?>')" >
                                    <i style="cursor: pointer;" class="fa fa-download" ></i>
                                </a>
                                <?
                            }
                            else
                            {
                                ?>
                                <a onclick="parent.openAdd('<?=base_url()."uploads/".$reqId."/".$attlink?>')" >
                                        <i style="cursor: pointer;" class="fa fa-eye" ></i>
                                </a>
                                |
                                <a onClick="down('<?=$attid?>')" >
                                    <i style="cursor: pointer;" class="fa fa-download" ></i>
                                </a>
                                <?
                            }
                            ?>
                        </div>
                    </div>
                </li>
                <?
            	}
                ?>
                <?if ($lampirandrive !=''){?>
                    <li>
                        <div class="item" onclick="window.open('<?=$lampirandrive?>', '_blank')">
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