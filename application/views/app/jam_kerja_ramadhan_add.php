<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

/* CHECK USER LOGIN 
$CI =& get_instance();
$CI->checkUserLogin();*/

$this->load->model('JamKerjaRamadhan');

$jam_kerja_ramadhan = new JamKerjaRamadhan();

$reqId = $this->input->get("reqId");

if($reqId == ""){
	$reqMode = "insert";
	$reqTahun = date("Y");
}
else
{
	$reqMode = "update";	
	$jam_kerja_ramadhan->selectByParams(array('JAM_KERJA_RAMADHAN_ID'=>$reqId), -1, -1);
	$jam_kerja_ramadhan->firstRow();
	
	$reqTahun= $jam_kerja_ramadhan->getField('TAHUN');
	
	$tempJamAwal= $jam_kerja_ramadhan->getField('JAM_AWAL');
	$tempJamAkhir= $jam_kerja_ramadhan->getField('JAM_AKHIR');
	$tempJumatAwal= $jam_kerja_ramadhan->getField('JUMAT_AWAL');
	$tempJumatAkhir= $jam_kerja_ramadhan->getField('JUMAT_AKHIR');
	$tempTanggalAwal= $jam_kerja_ramadhan->getField('TANGGAL_AWAL');
	$tempTanggalAkhir= $jam_kerja_ramadhan->getField('TANGGAL_AKHIR');
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
		url:'<?=base_url()?>jam_kerja_ramadhan_json/add',
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
	<div id="judul-popup">Tambah Jam kerja Ramadhan</div>
	<div id="konten">
    	<div id="popup-tabel2">
            <form id="ff" method="post" novalidate>
                    <table class="table">
                    <thead>
                        <tr>           
                             <td>Tahun</td><td>:</td>
                             <td>
                             <input type="text" name="reqTahun" value="<?=$reqTahun?>" readonly>
                            </td>			
                        </tr>
                        <tr>           
                             <td>Periode</td><td>:</td>
                             <td>
                                <input name="reqTanggalAwal" class="easyui-datebox" required type="text" value="<?=$tempTanggalAwal?>" />
                                s/d
                                <input name="reqTanggalAkhir" class="easyui-datebox" required type="text" value="<?=$tempTanggalAkhir?>" />
                            </td>			
                        </tr>
                        <tr>           
                             <td>Senin - Kamis</td><td>:</td>
                             <td>
                                <input class="easyui-timespinner" name="reqJamAwal" id="reqJamAwal" data-options="max:'23:59'" required style="width:60px;" maxlength="5" value="<?=$tempJamAwal?>" onkeydown="return format_menit(event,'reqJamAwal');" />
                                 s/d
                                <input class="easyui-timespinner" name="reqJamAkhir" id="reqJamAkhir" validType="BandingJam['#reqJamAwal']" data-options="max:'23:59'" required style="width:60px;" maxlength="5" value="<?=$tempJamAkhir?>" onkeydown="return format_menit(event,'reqJamAkhir');" />
                            </td>			
                        </tr>
                        <tr>           
                             <td>Jum'at</td><td>:</td>
                             <td>
                                <input class="easyui-timespinner" name="reqJumatAwal" id="reqJumatAwal" data-options="max:'23:59'" required style="width:60px;" maxlength="5" value="<?=$tempJumatAwal?>" onkeydown="return format_menit(event,'reqJumatAwal');" />
                                s/d
                                <input class="easyui-timespinner" name="reqJumatAkhir" id="reqJumatAkhir" validType="BandingJam['#reqJumatAwal']" data-options="max:'23:59'" required style="width:60px;" maxlength="5" value="<?=$tempJumatAkhir?>" onkeydown="return format_menit(event,'reqJumatAkhir');" />
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