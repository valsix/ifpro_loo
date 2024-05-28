<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

/* CHECK USER LOGIN 
$CI =& get_instance();
$CI->checkUserLogin();*/

$this->load->model('JamPulang');

$jam_pulang = new JamPulang();

$reqId = $this->input->get("reqId");
$tempDepartemen = $userLogin->idDepartemen;

if($reqId == ""){
	$reqMode = "insert";
}
else
{
	$reqMode = "update";	
	$jam_pulang->selectByParams(array('JAM_PULANG_ID'=>$reqId), -1, -1);
	$jam_pulang->firstRow();
	
	$tempNama= $jam_pulang->getField('NAMA');
	$tempKeterangan= $jam_pulang->getField('KETERANGAN');
	$tempToleransiTerlambat= $jam_pulang->getField('TOLERANSI_TERLAMBAT');
	$tempToleransiAmbilDataAwal= $jam_pulang->getField('TOLERANSI_AMBIL_DATA_AWAL');
	$tempToleransiAmbilDataAkhir= $jam_pulang->getField('TOLERANSI_AMBIL_DATA_AKHIR');
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
$(function(){
	$('#ff').form({
		url:'<?=base_url()?>jam_pulang_json/add',
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
				document.location.reload();
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

<body class="bg-kanan-full">
	<div id="judul-popup">Tambah Jam Pulang</div>
	<div id="konten">
    	<div id="popup-tabel2">
            <form id="ff" method="post" novalidate>
                    <table class="table">
                    <thead>
                       <tr>           
                             <td>Nama</td><td>:</td>
                             <td>
                                <input class="easyui-timespinner" name="reqNama" id="reqNama" data-options="max:'23:59'" required style="width:70px;" maxlength="5" value="<?=$tempNama?>" onkeydown="return format_menit(event,'reqNama');" />
                            </td>			
                        </tr>
                        <tr>           
                             <td>Toleransi Terlambat</td><td>:</td>
                             <td>
                                <input class="easyui-timespinner" name="reqToleransiTerlambat" id="reqToleransiTerlambat" data-options="max:'23:59'" required style="width:70px;" maxlength="5" value="<?=$tempToleransiTerlambat?>" onkeydown="return format_menit(event,'reqToleransiTerlambat');" />
                            </td>			
                        </tr>
                        <tr>           
                             <td>Toleransi Ambil Data Awal</td><td>:</td>
                             <td>
                                <input class="easyui-timespinner" name="reqToleransiAmbilDataAwal" id="reqToleransiAmbilDataAwal" data-options="max:'23:59'" required style="width:70px;" maxlength="5" value="<?=$tempToleransiAmbilDataAwal?>" onkeydown="return format_menit(event,'reqToleransiAmbilDataAwal');" />
                             </td>			
                        </tr>
                        <tr>           
                             <td>Toleransi Ambil Data Akhir</td><td>:</td>
                             <td>
                                <input class="easyui-timespinner" name="reqToleransiAmbilDataAkhir" id="reqToleransiAmbilDataAkhir" data-options="max:'23:59'" required style="width:70px;" maxlength="5" value="<?=$tempToleransiAmbilDataAkhir?>" onkeydown="return format_menit(event,'reqToleransiAmbilDataAkhir');" />
                            </td>			
                        </tr>
                        <tr>           
                             <td>Keterangan</td><td>:</td>
                             <td>
                                <textarea cols="31" name="reqKeterangan"><?=$tempKeterangan?></textarea>
                             </td>			
                        </tr> 
                    </table>
                    </thead>
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