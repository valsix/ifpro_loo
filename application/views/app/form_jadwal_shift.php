<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

/* CHECK USER LOGIN 
$CI =& get_instance();
$CI->checkUserLogin();*/

$this->load->model('KelompokShift');
$this->load->model('KelompokShiftJadwal');
$this->load->model('KelompokShiftJam');
$this->load->model('PermohonanShift');

$kelompok_shift = new KelompokShift();
$kelompok_shift_jadwal = new KelompokShiftJadwal();
$kelompok_shift_jam = new KelompokShiftJam();
$permohonan_shift = new PermohonanShift();

$reqId = $this->input->get("reqId");
$permohonan_shift->selectByParams(array("A.PERMOHONAN_SHIFT_ID" => $reqId));
$permohonan_shift->firstRow();
$tahun = $permohonan_shift->getField("TAHUN");

$kelompok_shift_jadwal->selectByParams(array("A.PERMOHONAN_SHIFT_ID" => $reqId));
while($kelompok_shift_jadwal->nextRow())
{
	$arrData[$kelompok_shift_jadwal->getField("JAM_KODE").(int)$kelompok_shift_jadwal->getField("PERIODE").$kelompok_shift_jadwal->getField("HARI")] = $kelompok_shift_jadwal->getField("KELOMPOK");
}

$kelompok_shift_jam->selectByParams(array("A.PERMOHONAN_SHIFT_ID" => $reqId));
while($kelompok_shift_jam->nextRow())
{
	$arrJam[] = $kelompok_shift_jam->getField("KODE");	
}
?>

<html moznomarginboxes mozdisallowselectionprint>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>

<script type="text/javascript" src="<?=base_url()?>js/jquery-1.9.1.js"></script>

<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/gaya.css">

<link rel="stylesheet" type="text/css" href="<?=base_url()?>lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="<?=base_url()?>lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript" src="<?=base_url()?>lib/easyui/globalfunction.js"></script>

<!-- BOOTSTRAP CORE -->
<link href="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>


<style>
#iframeModal-upload{
	*border:1px solid red;
	height:100% !important;
}
body.modal-open{
	*border:5px solid cyan;
}
iframe.embed-responsive-item.tmp-modal-content{
	*height:700px !important;
}

#iframeModal-upload .modal-dialog.modal-lg{
	*border:2px solid #F60; 
	height:90% !important;
}
#iframeModal-upload .modal-content{
	*border:2px solid #9C3;
}

.modal-backdrop{
	*border:3px solid #FFF;
	height:100%;
	width:100%;
	position:absolute;
	z-index:999;
	background:url(<?=base_url()?>images/bg-popup2.png) top repeat-x;
}
</style>

<link rel="stylesheet" href="<?=base_url()?>css/laporan.css" type="text/css">
<style>
@media print {
	body{
		-webkit-print-color-adjust: exact;
	}
	/*tr.vendorListHeading {
                background-color: #1a4567 !important;
                -webkit-print-color-adjust: exact; 
    }*/
	tr#gelap{
		background-color: #f0f0f0 !important;
		*-webkit-print-color-adjust: exact; 
	}
	tr#terang{
		background-color: #FFF !important;
		*-webkit-print-color-adjust: exact; 
	}
}
@media print {
	/*.vendorListHeading th {
    color: white !important;
    }*/
}
</style>


<style type="text/css">

	@page 
	{
		size: auto;   /* auto is the current printer page size */	
		margin: 5mm 5mm;  /* this affects the margin in the printer settings */
	}

	table.table { page-break-inside:auto }
	tr.baris    { page-break-inside:avoid; page-break-after:auto }
	tr    { page-break-inside:avoid; page-break-after:auto }
	
	@media print {	
		.kop{
			border:1px solid red !important;
			display:none !important;
		}
	}

</style>

<style>
.kop-jadwal-shift{
	
}
.kop-jadwal-shift table{
	border-collapse:collapse;
	border:2px solid #000 !important;
	width:100%;
}
.kop-jadwal-shift table td{
	border:1px solid #000 !important;
	padding:2px;
}

</style>

<style>
table.table.overflow-y{
	border-collapse:collapse;
	border:2px solid #000;
	margin-top:20px;
}
table.table.overflow-y th{
	border:1px solid #000;
	
	
}
table.table.overflow-y th.atas{
	*background:#dddddd !important; 
	*-webkit-print-color-adjust: exact;
	border-bottom:4px solid #000 !important;
}
/*table.table.overflow-y tr.baris-data{
	border-bottom:4px solid #000 !important;
}*/
table.table.overflow-y thead th{
	*border-bottom:4px solid red !important;
}
table.table.overflow-y td.jam-dinas{
	border-right:1px solid #000;
}
table.table.overflow-y td{
	border:1px solid #000;
}

/****/
table td.bg-shift-biru{
	background:#363594 !important; 
	-webkit-print-color-adjust: exact;
}
table td.bg-shift-pink{
	background:#ff7d81 !important; 
	-webkit-print-color-adjust: exact;
}
table td.bg-shift-hijau{
	background:#00b73a !important; 
	-webkit-print-color-adjust: exact;
}
table td.bg-shift-putih{
	background:#FFF !important; 
	-webkit-print-color-adjust: exact;
}

/****/
table th.gelap{
	background:#f0f0f0 !important; 
	-webkit-print-color-adjust: exact;
	text-align:center !important;
}
table th.terang{
	background:#fff !important; 
	-webkit-print-color-adjust: exact;
	text-align:center !important;
}

</style>

</head>

<body class="bg-kanan-full">

<div class="area-tombol-cetak">
	<button class="cetak btn btn-info btn-sm" onClick="javascript:window.print();">Cetak</button>
</div>

<div class="konten-cetak">
	
    <div class="kop-jadwal-shift">
    	<table>
        	<tr>
            	<td width="3%" rowspan="5"><img src="<?=base_url()?>images/logo-pjb2.png"></td>
                <td width="70%" align="center"><strong>PT PEMBANGKITAN JAWA BALI SERVICES UNIT BISNIS <?=$permohonan_shift->getField("DEPARTEMEN")?> <?=$permohonan_shift->getField("CABANG")?></strong></td>
                <td width="12%">No. Dokumen</td>
                <td width="15%" class="noborder-left">: </td>
            </tr>
            <tr>
            	<td align="center"><strong>PJB INTEGRATED MANAGEMENT SYSTEM</strong></td>
                <td>Tanggal Terbit</td>
                <td>: &nbsp;</td>
            </tr>
            <tr>
            	<td align="center"><strong>FORMULIR</strong></td>
                <td>Revisi</td>
                <td>: &nbsp;</td>
            </tr>
            <tr>
            	<td align="center"><strong>JADWAL DINAS OPERATOR PRODUKSI TAHUN <?=$tahun?></strong></td>
                <td>Halaman</td>
                <td>: &nbsp;</td>
            </tr>
            <tr>
            	<td align="center"><strong><?=$nomor?></strong></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
        </table>
    </div>
    
	<table class="table overflow-y">
    <thead>
        <tr>
            <th class="atas" style="text-align:center; vertical-align:middle; background:black !important; color:#FFF !important;" rowspan="2">Bulan</th>
            <th class="atas" style="text-align:center; vertical-align:middle" rowspan="2">Jam Dinas</th>
            <th style="text-align:center" colspan="31">Tanggal</th>
        </tr> 
        <tr>
            <?
            for($i=1;$i<=31;$i++)
            {
            ?>
            <th class="atas" style="text-align:center"><?=$i?></th>
            <?
            }
            ?>
        </tr>
    </thead>
    <tbody id="tbData">
        <?
        for($j=1;$j<=12;$j++)
        {
			if ($j % 2 == 0){
				//$warna_row = "#FFF";
				$warna_bulan = "terang";
				
			}
			else{
				//$warna_row = "#f0f0f0";
				$warna_bulan = "gelap";
			}

			for($i=0;$i<count($arrJam);$i++)
			{
				
        ?>

            <tr>
				<?
                if($i == 0)
                {
                ?>            
                <th class="<?=$warna_bulan?>" rowspan="<?=count($arrJam)?>" style="vertical-align:middle"><?=getNameMonth($j)?></th>
                <?
				}
				?>
                <td><?=$arrJam[$i]?></td>
                <?
                for($k=1;$k<=31;$k++)
                {
					$bg_shift = "";
					if($arrData[$arrJam[$i].$j.$tahun.$k] == "A"){
						$bg_shift = "bg-shift-biru";
					}
					if($arrData[$arrJam[$i].$j.$tahun.$k] == "B"){
						$bg_shift = "bg-shift-pink";
					}
					if($arrData[$arrJam[$i].$j.$tahun.$k] == "C"){
						$bg_shift = "bg-shift-hijau";
					}
					if($arrData[$arrJam[$i].$j.$tahun.$k] == "D"){
						$bg_shift = "bg-shift-putih";
					}
                    
                ?>
                        <td class="<?=$bg_shift?>">
                            <label id="pagi-<?=$j?>-<?=$k?>"><?=$arrData[$arrJam[$i].$j.$tahun.$k]?></label>
                        </td>
                <?		
                }
                ?>
            </tr>
       <?
			}
        }
        ?>
    </tbody>
    </table>
</div>

	<?php /*?><div id="judul-popup">Jadwal Shift</div>
	<div id="konten" align="center">
        
    </div><?php */?>
</body>
</html>