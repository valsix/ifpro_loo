<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

/* CHECK USER LOGIN 
$CI =& get_instance();
$CI->checkUserLogin();*/

$this->load->model('KelompokKeandalan');
$this->load->model('KelompokKeandalanJadwal');
$this->load->model('PermohonanKeandalan');

$kelompok_keandalan = new KelompokKeandalan();
$kelompok_keandalan_jadwal = new KelompokKeandalanJadwal();
$permohonan_keandalan = new PermohonanKeandalan();

$reqId = $this->input->get("reqId");

$permohonan_keandalan->selectByParams(array("A.PERMOHONAN_KEANDALAN_ID" => $reqId));
$permohonan_keandalan->firstRow();
$tahun = $permohonan_keandalan->getField("TAHUN");

$kelompok_keandalan_jadwal->selectByParams(array("A.PERMOHONAN_KEANDALAN_ID" => $reqId));
$i = 0;
while($kelompok_keandalan_jadwal->nextRow())
{
	if($i == 0)
		$tanggal = "'".generateZero($kelompok_keandalan_jadwal->getField("HARI"), 2)."-".$kelompok_keandalan_jadwal->getField("BULAN")."-".$kelompok_keandalan_jadwal->getField("TAHUN")."'";
	else
		$tanggal .= ",'".generateZero($kelompok_keandalan_jadwal->getField("HARI"), 2)."-".$kelompok_keandalan_jadwal->getField("BULAN")."-".$kelompok_keandalan_jadwal->getField("TAHUN")."'";

	$i++;
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

<!-- loads jquery and jquery ui -->
<script type="text/javascript" src="<?=base_url()?>lib/multidate/js/jquery-1.11.1.js"></script>
<script type="text/javascript" src="<?=base_url()?>lib/multidate/js/jquery-ui-1.11.1.js"></script>
<script type="text/javascript" src="<?=base_url()?>lib/multidate/jquery-ui.multidatespicker.js"></script>
<script type="text/javascript">
<!--
    
    $(function() {
        var today = new Date();
        var y = today.getFullYear();
        $('#full-year').multiDatesPicker({
            addDates: [<?=$tanggal?>],
            numberOfMonths: [3,4],
			dateFormat: "dd-mm-yy", 
			defaultDate:"01-01-<?=$tahun?>"
        });
    });
// -->
</script>

<link rel="stylesheet" type="text/css" href="<?=base_url()?>lib/multidate/css/mdp.css">

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
	<div id="judul-popup">Jadwal Keandalan</div>
	<div id="konten" align="center">
		<div id="full-year"></div>
    </div>
</body>
</html>