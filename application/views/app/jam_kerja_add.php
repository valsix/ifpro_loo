<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

/* CHECK USER LOGIN 
$CI =& get_instance();
$CI->checkUserLogin();*/

$this->load->model('JamKerja');
$this->load->model('JamKerjaJenis');

$jam_kerja = new JamKerja();
$jam_kerja_jenis = new JamKerjaJenis();

$reqId = $this->input->get("reqId");
$tempDepartemen = $userLogin->idDepartemen;

if($reqId == ""){
	$reqMode = "insert";
}
else
{
	$reqMode = "update";	
	$jam_kerja->selectByParams(array('JAM_KERJA_ID'=>$reqId), -1, -1);
	$jam_kerja->firstRow();
	
	$tempNama= $jam_kerja->getField('NAMA');
	$tempJamAwal= $jam_kerja->getField('JAM_AWAL');
	$tempJamAkhir= $jam_kerja->getField('JAM_AKHIR');
	$tempTerlambatAwal= $jam_kerja->getField('TERLAMBAT_AWAL');
	$tempTerlambatAkhir= $jam_kerja->getField('TERLAMBAT_AKHIR');
	$tempJamKerjaJenisId= $jam_kerja->getField('JAM_KERJA_JENIS_ID');
}

$statement = "";
$jam_kerja_jenis->selectByParams(array(), -1, -1, $statement);
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
		url:'<?=base_url()?>jam_kerja_json/add',
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
	<div id="judul-popup">Tambah Jam kerja</div>
	<div id="konten">
    	<div id="popup-tabel2">
            <form id="ff" method="post" novalidate>
                    <table class="table">
                    <thead>
                        <tr>           
                             <td>Jenis Jam Kerja</td><td>:</td>
                             <td>
                                <select name="reqJamKerjaJenis">
                                <? while($jam_kerja_jenis->nextRow()){?>     
                                    <option value="<?=$jam_kerja_jenis->getField('JAM_KERJA_JENIS_ID')?>" <? if($tempJamKerjaJenisId == $jam_kerja_jenis->getField('JAM_KERJA_JENIS_ID')) echo 'selected'?>> <?=$jam_kerja_jenis->getField('NAMA')?></option>
                                <? }?>
                                </select>  
                            </td>			
                        </tr>
                        <tr>           
                             <td>Nama</td><td>:</td>
                             <td>
                                <input name="reqNama" class="easyui-validatebox" required="true" style="width:170px" type="text" value="<?=$tempNama?>" />
                            </td>			
                        </tr>
                        <tr>           
                             <td>Jam Awal</td><td>:</td>
                             <td>
                                <input class="easyui-timespinner" name="reqJamAwal" id="reqJamAwal" data-options="max:'23:59'" required="true" style="width:70px;" maxlength="5" value="<?=$tempJamAwal?>" onkeydown="return format_menit(event,'reqJamAwal');" />
                                <?php /*?><input  id="reqJamAwal" name="reqJamAwal" class="easyui-validatebox" required="true" style="width:40px" type="text" value="<?=$tempJamAwal?>" maxlength="5" onkeydown="return format_menit(event,'reqJamAwal');"/><?php */?>
                            </td>			
                        </tr>
                        <tr>           
                             <td>Jam Akhir</td><td>:</td>
                             <td>
                                <input class="easyui-timespinner" name="reqJamAkhir" id="reqJamAkhir" validType="BandingJam['#reqJamAwal']" data-options="max:'23:59'" required="true" style="width:70px;" maxlength="5" value="<?=$tempJamAkhir?>" onkeydown="return format_menit(event,'reqJamAkhir');" />
                                <?php /*?><input  id="reqJamAkhir" name="reqJamAkhir" class="easyui-validatebox" required="true" style="width:40px" type="text" value="<?=$tempJamAkhir?>" maxlength="5" onkeydown="return format_menit(event,'reqJamAkhir');"/><?php */?>
                            </td>			
                        </tr>
                        <tr>           
                             <td>Terlambat Awal</td><td>:</td>
                             <td>
                                <input class="easyui-timespinner" name="reqTerlambatAwal" id="reqTerlambatAwal" data-options="max:'23:59'" required="true" style="width:70px;" maxlength="5" value="<?=$tempTerlambatAwal?>" onkeydown="return format_menit(event,'reqTerlambatAwal');" />
                                <?php /*?><input  id="reqTerlambatAwal" name="reqTerlambatAwal" class="easyui-validatebox" required="true" style="width:40px" type="text" value="<?=$tempTerlambatAwal?>" maxlength="5" onkeydown="return format_menit(event,'reqTerlambatAwal');"/>				<?php */?>
                            </td>			
                        </tr>
                        <tr>           
                             <td>Terlambat Akhir</td><td>:</td>
                             <td>
                                <input class="easyui-timespinner" name="reqTerlambatAkhir" id="reqTerlambatAkhir" validType="BandingJam['#reqTerlambatAwal']" data-options="max:'23:59'" required="true" style="width:70px;" maxlength="5" value="<?=$tempTerlambatAkhir?>" onkeydown="return format_menit(event,'reqTerlambatAkhir');" />
                                <?php /*?><input  id="reqTerlambatAkhir" name="reqTerlambatAkhir" class="easyui-validatebox" required="true" style="width:40px" type="text" value="<?=$tempTerlambatAkhir?>" maxlength="5" onkeydown="return format_menit(event,'reqTerlambatAkhir');"/>	<?php */?>
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