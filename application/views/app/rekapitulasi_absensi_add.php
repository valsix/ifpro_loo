<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

/* CHECK USER LOGIN 
$CI =& get_instance();
$CI->checkUserLogin();*/

$this->load->model('AbsensiRekap');

$absensi_rekap = new AbsensiRekap();

$reqId = $this->input->get("reqId");
$reqBulan = $this->input->get("reqBulan");
$reqTahun = $this->input->get("reqTahun");

$reqPeriode = $reqBulan.$reqTahun;

$jumHari = cal_days_in_month(CAL_GREGORIAN, date($reqBulan), date('Y'));
if($reqId == ""){
	$reqMode = "insert";
}
else
{
	$reqMode = "update";	
	$statement = "";
	$absensi_rekap->selectByParams(array("A.PEGAWAI_ID" => $reqId),-1,-1, "", $reqPeriode, "");
	$absensi_rekap->firstRow();
	$day = maxHariPeriode($reqPeriode);
	$reqTahun= substr($reqPeriode,2,4);
	$reqBulan= substr($reqPeriode,0,2);
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>

<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/gaya.css">

<link rel="stylesheet" type="text/css" href="<?=base_url()?>lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="<?=base_url()?>js/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript" src="<?=base_url()?>lib/easyui/globalfunction.js"></script>
<script type="text/javascript">	
function setValue(){
	$('#cc').combotree('setValue', '<?=$tempDepartemen?>');
	
	status=$('#reqPilih').val();
	if(status == 'Dinamis'){
		$('#reqTanggalAwal').addClass('required');
		$('#reqTanggalAkhir').addClass('required');
		$('#reqBulan').removeClass('required');
		$('#reqHari').removeClass('required');
		
		$('#reqBulan').val('');$('#reqHari').val('');
		
		$('#tr_tanggal_awal').show();
		$('#tr_tanggal_akhir').show();
		$('#tr_tanggal_fix').hide();
	}
	else if(status == 'Statis'){
		$('#reqTanggalAwal').removeClass('required');
		$('#reqTanggalAkhir').removeClass('required');
		$('#reqBulan').addClass('required');
		$('#reqHari').addClass('required');
		
		$('#reqTanggalAwal').val('');
		$('#reqTanggalAkhir').val('');
		
		$('#tr_tanggal_awal').hide();
		$('#tr_tanggal_akhir').hide();
		$('#tr_tanggal_fix').show();
	}		
}

$(function(){
	$('#ff').form({
		url:'<?=base_url()?>hari_libur_json/add',
		onSubmit:function(){
			return $(this).form('validate');
		},
		success:function(data){
			$.messager.alert('Info', data, 'info');	
			
			$('#reqLampiran').MultiFile('reset');
			
			<?
			if($reqMode == "update")
			{
			?>
				//document.location.reload();
			<?	
			}
			else
			{
			?>
				$('#rst_form').click();
			<?
			}
			?>
			top.frames['mainFrame'].location.reload();
		}
	});
	
});
</script>

<!-- UPLOAD CORE -->
<script src="<?=base_url()?>lib/multifile-master/jquery.MultiFile.js"></script>
<script>
// wait for document to load
$(function(){
	
	// invoke plugin
	$('#reqLampiran').MultiFile({
	onFileChange: function(){
		console.log(this, arguments);
	}
	});

});

</script>

<!-- BOOTSTRAP CORE -->
<link href="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

</head>

<body class="bg-kanan-full" onLoad="setValue();">
	<div id="judul-popup">Koreksi Rekap Absensi</div>
	<div id="konten">
    	<div id="popup-tabel2">
            <form id="ff" method="post" novalidate enctype="multipart/form-data">
            <table width="30%">
                <thead>
                    <tr>
                        <th rowspan="2" style="width:3%; text-align:center">Tanggal</th>
                        <th colspan="2" style="width:20%; text-align:center">Jam Kerja</th>
                    </tr>
                    <tr>
                        <th style="text-align:center">Masuk</th>
                        <th style="text-align:center">Pulang</th>
                    </tr>
                </thead>
                <tbody>
                    <?
                    for($i=1; $i<=$day; $i++)
                    {
                    ?>
                    <tr>
                        <td style="text-align:center"><?=$i?></td>
                        <td style="text-align:center">
                            <input class="easyui-timespinner" name="reqIn<?=$i?>" id="reqIn<?=$i?>" data-options="max:'23:59'" required style="width:70px;" maxlength="5" value="<?=$absensi_rekap->getField("IN_".$i)?>" onkeydown="return format_menit(event,'reqIn<?=$i?>');" />
                        </td>
                        <td style="text-align:center">
                        	<input class="easyui-timespinner" name="reqOut<?=$i?>" id="reqOut<?=$i?>" data-options="max:'23:59'" required style="width:70px;" maxlength="5" value="<?=$absensi_rekap->getField("OUT_".$i)?>" onkeydown="return format_menit(event,'reqOut<?=$i?>');" />
							
                        </td>
                    </tr>
                    <?	
                    }
                    ?>
                </tbody>
            </table>
            <br />
            <input type="hidden" name="reqId" value="<?=$reqId?>" />
            <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
            <input type="submit" name="reqSubmit"  class="btn btn-primary" value="Submit" />
            <input type="reset" id="rst_form"  class="btn btn-primary" value="Reset" />
            
            </form>
        </div>
        </div>
    </div>
</body>
</html>