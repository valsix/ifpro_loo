<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

/* CHECK USER LOGIN 
$CI =& get_instance();
$CI->checkUserLogin();*/

$this->load->model('SettingAplikasi');

$setting_aplikasi= new SettingAplikasi();

$reqId = $this->input->get("reqId");
$reqMode = $this->input->get("reqMode");

if($reqId == "")
{ }
else
{
	$setting_aplikasi->selectByParams(array("NAMA" => $reqId));
	$setting_aplikasi->firstRow();
	$reqNama = $setting_aplikasi->getField("NAMA");
	$reqKeterangan = $setting_aplikasi->getField("KETERANGAN");
	$reqNilai = $setting_aplikasi->getField("NILAI");
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


<!-- BOOTSTRAP CORE -->
<link href="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

<script type="text/javascript">	
	$(function(){
	$('#ff').form({
		url:'<?=base_url()?>setting_aplikasi_json/add',
		onSubmit:function(){
			return $(this).form('validate');
		},
		success:function(data){
			$.messager.alert('Info', data, 'info');	
			$('#rst_form').click();
			top.frames['mainFrame'].location.reload();
		}
	});
	
});
</script>

</head>

<body class="bg-kanan-full">
	<div id="judul-popup">Setting Aplikasi</div>
	<div id="konten">
    	<div id="popup-tabel2">
            <form id="ff" method="post" novalidate enctype="multipart/form-data">
                    <table class="table">
                    <thead>
                    	<tr>
                            <td>Nama</td>
                            <td>:</td>
                            <td>
                                <?=$reqNama?>
                          	</td>
                        </tr>        
                        <tr>
                            <td>Keterangan</td>
                            <td>:</td>
                            <td>
                                <?=$reqKeterangan?>
                          	</td>
                        </tr>        
                        <tr>
                            <td>Nilai</td>
                            <td>:</td>
                            <td>
                                <input type="text" name="reqNilai" id="reqNilai" value="<?=$reqNilai?>" /></td>
                          	</td>
                        </tr>
                    </thead>
                    </table>
                    <input type="hidden" name="reqId" value="<?=$reqId?>" />
                    <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
                    <input type="submit" id="reqSubmit" name="reqSubmit" class="btn btn-primary"  value="Submit" />
                    
            </form>
        </div>
        </div>
    </div>
</body>
</html>