<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

/* CHECK USER LOGIN 
$CI =& get_instance();
$CI->checkUserLogin();*/

$this->load->model('KelompokShift');
$this->load->model('KelompokShiftJadwal');
$this->load->model('PermohonanShift');

$kelompok_shift = new KelompokShift();
$kelompok_shift_jadwal = new KelompokShiftJadwal();
$permohonan_shift = new PermohonanShift();

$reqId = $this->input->get("reqId");

$permohonan_shift->selectByParams(array("A.PERMOHONAN_SHIFT_ID" => $reqId));
$permohonan_shift->firstRow();
$tahun = $permohonan_shift->getField("TAHUN");

$kelompok_shift_jadwal->selectByParams(array("A.PERMOHONAN_SHIFT_ID" => $reqId));
while($kelompok_shift_jadwal->nextRow())
{
	$arrData[$kelompok_shift_jadwal->getField("JAM_KERJA_SHIFT_ID").(int)$kelompok_shift_jadwal->getField("PERIODE").$kelompok_shift_jadwal->getField("HARI")] = $kelompok_shift_jadwal->getField("KELOMPOK");
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
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

</head>

<body class="bg-kanan-full">
	<div id="judul-popup">Jadwal Shift</div>
	<div id="konten" align="center">
        <table class="table overflow-y">
        <thead>
            <tr>
                <th style="text-align:center; vertical-align:middle" rowspan="2">Bulan</th>
                <th style="text-align:center; vertical-align:middle" rowspan="2">Jam Dinas</th>
                <th style="text-align:center" colspan="31">Tanggal</th>
            </tr> 
            <tr>
                <?
                for($i=1;$i<=31;$i++)
                {
                ?>
                <th style="text-align:center"><?=$i?></th>
                <?
                }
                ?>
            </tr>
        </thead>
        <tbody id="tbData">
            <?
            for($j=1;$j<=12;$j++)
            {
            ?>
                <tr>
                    <th rowspan="4" style="vertical-align:middle"><?=getNameMonth($j)?></th>
                    <td>Pagi</td>
                    <?
                    for($k=1;$k<=31;$k++)
                    {
                        
                    ?>
                            <td>
                                <label id="pagi-<?=$j?>-<?=$k?>"><?=$arrData["1".$j.$tahun.$k]?></label>
                                <input type="hidden" name="reqPagi-<?=$j?>-<?=$k?>" id="reqPagi-<?=$j?>-<?=$k?>" value="<?=$arrData["1".$j.$tahun.$k]?>">
                            </td>
                    <?		
                    }
                    ?>
                </tr>
                <tr>
                    <td>Sore</td>
                    <?
                    for($k=1;$k<=31;$k++)
                    {
                    ?>
                            <td>
                                <label id="sore-<?=$j?>-<?=$k?>"><?=$arrData["2".$j.$tahun.$k]?></label>
                                <input type="hidden" name="reqSore-<?=$j?>-<?=$k?>" id="reqSore-<?=$j?>-<?=$k?>" value="<?=$arrData["2".$j.$tahun.$k]?>">
                            </td>
                    <?		
                    }
                    ?>
                </tr>
                <tr>
                    <td>Malam</td>
                    <?
                    for($k=1;$k<=31;$k++)
                    {
                    ?>
                            <td>
                                <label id="malam-<?=$j?>-<?=$k?>"><?=$arrData["3".$j.$tahun.$k]?></label>
                                <input type="hidden" name="reqMalam-<?=$j?>-<?=$k?>" id="reqMalam-<?=$j?>-<?=$k?>" value="<?=$arrData["3".$j.$tahun.$k]?>">
                            </td>
                    <?		
                    }
                    ?>
                </tr>
                <tr>
                    <td>Libur</td>
                    <?
                    for($k=1;$k<=31;$k++)
                    {
                        ?>
                            <td>
                                <label id="libur-<?=$j?>-<?=$k?>"><?=$arrData["4".$j.$tahun.$k]?></label>
                                <input type="hidden" name="reqLibur-<?=$j?>-<?=$k?>" id="reqLibur-<?=$j?>-<?=$k?>" value="<?=$arrData["4".$j.$tahun.$k]?>">
                            </td>                                
                        <?								
                    }
                    ?>                            
                </tr>
            <?
            }
            ?>
        </tbody>
        </table>
    </div>
</body>
</html>