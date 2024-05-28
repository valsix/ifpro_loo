<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

/* CHECK USER LOGIN 
$CI =& get_instance();
$CI->checkUserLogin();*/

$this->load->model('UserGroup');

$user_group = new UserGroup();

$reqId = $this->input->get("reqId");

if($reqId == ""){
	$reqMode = "insert";
	$reqTahun = date("Y");
}
else
{
	$reqMode = "update";	
	$user_group->selectByParams(array('USER_GROUP_ID'=>$reqId), -1, -1);
	$user_group->firstRow();
	
	$reqNama= $user_group->getField('NAMA');
	$reqKeterangan= $user_group->getField('KETERANGAN');
	$reqAksesMaster= $user_group->getField('AKSES_MASTER');
	$reqAksesLaporan= $user_group->getField('AKSES_LAPORAN');
	$reqAksesPermohonan= $user_group->getField('AKSES_UNIT');
    $reqAksesProsesRekap= $user_group->getField('AKSES_PROSES_REKAP');
	$reqAksesRekapitulasi= $user_group->getField('AKSES_REKAPITULASI');
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
		url:'<?=base_url()?>user_group_json/add',
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
	<div id="judul-popup">Tambah User Group</div>
	<div id="konten">
    	<div id="popup-tabel2">
            <form id="ff" method="post" novalidate>
                    <table class="table">
                    <thead>
                        <tr>           
                             <td>Nama</td><td>:</td>
                             <td>
                             	<input type="text" name="reqNama" value="<?=$reqNama?>">
                            </td>			
                        </tr>
                        <tr>           
                             <td>Keterangan</td><td>:</td>
                             <td>
                             	<input type="text" name="reqKeterangan" value="<?=$reqKeterangan?>" size="80px">
                            </td>			
                        </tr>
                        <tr>           
                             <td>Akses Master</td><td>:</td>
                             <td>
                                <select name="reqAksesMaster">
                                    <option value="1" <? if($reqAksesMaster == "1") { ?> selected="selected" <? } ?>>
                                        Ya
                                    </option>
                                    <option value="0" <? if($reqAksesMaster == "0") { ?> selected="selected" <? } ?>>
                                        Tidak
                                    </option>
                                </select>
                            </td>			
                        </tr>
                        <tr>           
                             <td>Akses Laporan</td><td>:</td>
                             <td>
                                <select name="reqAksesLaporan">
                                    <option value="1" <? if($reqAksesLaporan == "1") { ?> selected="selected" <? } ?>>
                                        Ya
                                    </option>
                                    <option value="0" <? if($reqAksesLaporan == "0") { ?> selected="selected" <? } ?>>
                                        Tidak
                                    </option>
                                </select>
                            </td>			
                        </tr>
                        <tr>           
                             <td>Akses Proses Rekap</td><td>:</td>
                             <td>
                                <select name="reqAksesProsesRekap">
                                    <option value="1" <? if($reqAksesProsesRekap == "1") { ?> selected="selected" <? } ?>>
                                        Ya
                                    </option>
                                    <option value="0" <? if($reqAksesProsesRekap == "0") { ?> selected="selected" <? } ?>>
                                        Tidak
                                    </option>
                                </select>
                            </td>			
                        </tr>
                        <tr>           
                             <td>Akses Rekapitulasi</td><td>:</td>
                             <td>
                                <select name="reqAksesRekapitulasi">
                                    <option value="1" <? if($reqAksesRekapitulasi == "1") { ?> selected="selected" <? } ?>>
                                        Ya
                                    </option>
                                    <option value="0" <? if($reqAksesRekapitulasi == "0") { ?> selected="selected" <? } ?>>
                                        Tidak
                                    </option>
                                </select>
                            </td>           
                        </tr>
                        <tr>           
                             <td>Akses Seluruh Unit</td><td>:</td>
                             <td>
                                <select name="reqAksesPermohonan">
                                    <option value="1" <? if($reqAksesPermohonan == "1") { ?> selected="selected" <? } ?>>
                                        Ya
                                    </option>
                                    <option value="0" <? if($reqAksesPermohonan == "0") { ?> selected="selected" <? } ?>>
                                        Tidak
                                    </option>
                                </select>
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