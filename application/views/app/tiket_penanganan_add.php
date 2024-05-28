<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

/* CHECK USER LOGIN 
$CI =& get_instance();
$CI->checkUserLogin();*/

$this->load->model('TiketPenanganan');

$tiket_penanganan = new TiketPenanganan();

$reqId = $this->input->get("reqId");

if($reqId == ""){
	$reqMode = "insert";
}
else
{
	$reqMode = "update";	
	$tiket_penanganan->selectByParams(array('TIKET_PENANGANAN_ID'=>$reqId), -1, -1);
	$tiket_penanganan->firstRow();

	$reqTingkatKesulitanId = $tiket_penanganan->getField("TINGKAT_KESULITAN_ID");
	$reqTiketPelimpahanId = $tiket_penanganan->getField("TIKET_PELIMPAHAN_ID");
	$reqTanggal = $tiket_penanganan->getField("TANGGAL");
	$reqEstimasiSelesai = $tiket_penanganan->getField("ESTIMASI_SELESAI");
	$reqEstimasiSelesaiSd = $tiket_penanganan->getField("ESTIMASI_SELESAI_SD");
	
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
		url:'<?=base_url()?>tiket_penanganan_json/add',
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
	<div id="judul-popup">Tambah Tiket Penanganan</div>
	<div id="konten">
    	<div id="popup-tabel2">
            <form id="ff" method="post" novalidate>
                    <table class="table">
                    <thead>
                        <tr>           
                             <td>Tingkat Kesulitan</td><td>:</td>
                             <td>
                             <input type="text" name="reqTingkatKesulitanId" value="<?=$reqTingkatKesulitanId?>">
                             </td>			
                        </tr>
                        <tr>           
                             <td>Tiket Pelimpahan</td><td>:</td>
                             <td>
                             <input type="text" name="reqTiketPelimpahanId" value="<?=$reqTiketPelimpahanId?>">
                             </td>			
                        </tr>
                        <tr>
                            <td>Tanggal</td>
                            <td>:</td>
                            <td>
                            <input class="easyui-datebox" name="reqTanggal" value="<?=$reqTanggal?>">
                            </td>
                        </tr>  
                        <tr>           
                             <td>Estimasi Selesai</td><td>:</td>
                             <td>
                             <input class="easyui-datebox" name="reqEstimasiSelesai" value="<?=$reqEstimasiSelesai?>">
                             </td>			
                        </tr>
                        <tr>
                            <td>Estimasi Selesai S/d</td><td>:</td>
                            <td>
                            <input class="easyui-datebox" name="reqEstimasiSelesaiSd" value="<?=$reqEstimasiSelesaiSd?>">
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