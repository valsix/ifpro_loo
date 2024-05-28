<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

/* CHECK USER LOGIN 
$CI =& get_instance();
$CI->checkUserLogin();*/

$this->load->model('FingerscanLokasi');

$fingerscan_lokasi= new FingerscanLokasi();

$reqId = $this->input->get("reqId");
$reqMode = $this->input->get("reqMode");

if($reqId == "")
{ 
$reqMode="insert";
}
else
{
	$fingerscan_lokasi->selectByParams(array("MESIN_ID" => $reqId));
	$fingerscan_lokasi->firstRow();
	$reqNama = $fingerscan_lokasi->getField("NAMA_LOKASI");
	$reqCabang = $fingerscan_lokasi->getField("NAMA_CABANG");
	$reqCabangId = $fingerscan_lokasi->getField("CABANG_ID");
	$reqIpAddress = $fingerscan_lokasi->getField("IP_ADDRESS");
	$reqPort = $fingerscan_lokasi->getField("PORT");
	$reqKeterangan = $fingerscan_lokasi->getField("KETERANGAN");
	$reqNomorMesin = $fingerscan_lokasi->getField("NOMOR_MESIN");
	
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>

<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/gaya.css">

<link rel="stylesheet" type="text/css" href="<?=base_url()?>lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="<?=base_url()?>js/jquery-1.9.1.js"></script>
<script type="text/javascript" src="<?=base_url()?>lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript" src="<?=base_url()?>lib/easyui/globalfunction.js"></script>


<!-- BOOTSTRAP CORE -->
<link href="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

<script type="text/javascript">	
	$(function(){
	$('#ff').form({
		url:'<?=base_url()?>fingerscan_lokasi_json/add',
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
	<div id="judul-popup">Lokasi Fingerscan</div>
	<div id="konten">
    	<div id="popup-tabel2">
            <form id="ff" method="post" novalidate enctype="multipart/form-data">
                    <table class="table">
                    <thead>
                    	<tr>
                            <td>Nama Lokasi</td>
                            <td>:</td>
                            <td>
                                <input type="text" name="reqNama" id="reqNama" value="<?=$reqNama?>" />
                          	</td>
                        </tr>   
                        <tr>
                            <td>Cabang</td>
                            <td>:</td>
                            <td>
                            	<input class="easyui-combobox" name="reqCabang" style="width:250px;" data-options="
                                    url: '<?=base_url()?>cabang_combo_json/json_all',
                                    method: 'get',
                                    valueField:'value', 
                                    textField:'text'
                                " value="<?=$reqCabangId?>">
                            <? /*
                                <input class="easyui-combobox" name="reqCabang" style="width:250px;" data-options="
                                    url: '<?=base_url()?>cabang_combo_json/json',
                                    method: 'get',
                                    valueField:'value', 
                                    textField:'text'
                                " value="<?=$reqCabangId?>">
							*/ ?>
                          	</td>
                        </tr>    
                        <tr>
                            <td>IP Address</td>
                            <td>:</td>
                            <td>
                                <input type="text" name="reqIpAddress" id="reqIpAddress" value="<?=$reqIpAddress?>" /></td>
                          	</td>
                        </tr>
                        <tr>
                            <td>Port</td>
                            <td>:</td>
                            <td>
                                <input type="text" name="reqPort" id="reqPort" value="<?=$reqPort?>" />
                          	</td>
                        </tr>
                        <tr>
                            <td>Nomor Mesin</td>
                            <td>:</td>
                            <td>
                                <input type="text" name="reqNomorMesin" id="reqNomorMesin" value="<?=$reqNomorMesin?>" />
                          	</td>
                        </tr>
                        <tr>
                            <td>Keterangan</td>
                            <td>:</td>
                            <td>
                                <textarea name="reqKeterangan" id="reqKeterangan"><?=$reqKeterangan?></textarea>
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