<?
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/date.func.php");

$reqSasaranId = $this->input->get("reqSasaranId");
$reqParentId = $this->input->get("reqParentId");
$reqId = $this->input->get("reqId");

$this->load->model("ModulTspi");

$modul_tspi = new ModulTspi();
$modul_tspi->selectByParams(array("MODUL_TSPI_ID" => $reqId));
$modul_tspi->firstRow();
if($reqId == "")
{
	$reqMode = "insert";	
}
else
{
	$reqMode = "update";
	$reqKode = $modul_tspi->getField("KODE");
	$reqNama = $modul_tspi->getField("NAMA");
	$reqKeterangan = $modul_tspi->getField("KETERANGAN");
}

																		  
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
<base href="<?=base_url()?>" />
<link rel="stylesheet" type="text/css" href="css/gaya.css">

<link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="js/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript" src="lib/easyui/globalfunction.js"></script>
<script type="text/javascript">	
$(function(){
	$('#ff').form({
		url:'modul_tspi_json/add',
		onSubmit:function(){
			return $(this).form('validate');
		},
		success:function(data){
			$.messager.alert('Info', data, 'info');	
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
			$('#reqLinkFile').MultiFile('reset');
		}
	});
	
});
</script>

<!-- BOOTSTRAP CORE -->
<link href="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

</head>

<body class="bg-kanan-full">
	<div id="judul-popup">Tambah Modul TSPI</div>
	<div id="konten">
    	<div id="popup-tabel2">
            <form id="ff" method="post" novalidate enctype="multipart/form-data">
            <table class="table">
            <thead>               
                <tr <? if($reqParentId == "0") { ?> style="display:none" <? } ?>>
                    <td>Kode</td>
                    <td>:</td>
                    <td>
                    	<input type="text" class="easyui-textbox" name="reqKode" value="<?=$reqKode?>">
                    </td>
                </tr> 
                <tr>
                    <td>Nama</td>
                    <td>:</td>
                    <td>
                    	<input type="text" class="easyui-textbox" name="reqNama" value="<?=$reqNama?>">
                    </td>
                </tr>
                <tr>
                    <td>Keterangan</td>
                    <td>:</td>
                    <td>
                    	<textarea class="easyui-textbox" name="reqKeterangan"><?=$reqKeterangan?></textarea>
                    </td>
                </tr>
            </thead>
            </table>
            <input type="hidden" name="reqId" value="<?=$reqId?>" />
            <input type="hidden" name="reqParentId" value="<?=$reqParentId?>" />
            <input type="hidden" name="reqSasaranId" value="<?=$reqSasaranId?>" />
            <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
            <input type="submit" name="reqSubmit" value="Submit" />
            <input type="reset" id="rst_form" value="Reset" />
            
            </form>
        </div>
        </div>
    </div>
</body>
</html>