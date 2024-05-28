<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

/* CHECK USER LOGIN 
$CI =& get_instance();
$CI->checkUserLogin();*/

$this->load->model('Cabang');

$cabang = new Cabang();

$reqId = $this->input->get("reqId");
$reqSource = $this->input->get("reqSource");

if($reqId == ""){
	$reqMode = "insert";
}
else
{
	$reqMode = "update";	
	$cabang->selectByParams(array('CABANG_ID'=>$reqId), -1, -1);
	$cabang->firstRow();
	
	$reqCabangId= $cabang->getField('CABANG_ID');
	$reqNama= $cabang->getField('NAMA');
	$reqKeterangan= $cabang->getField('KETERANGAN');
	$reqSelisih= $cabang->getField('SELISIH');
	$reqLokasi= $cabang->getField('LOKASI');
	$reqLatitude= $cabang->getField('LATITUDE');
	$reqLongitude= $cabang->getField('LONGITUDE');
	$reqRadius= $cabang->getField('RADIUS');
	$reqAlamatKantor= $cabang->getField('ALAMAT_KANTOR');
	$reqNoTelepon= $cabang->getField('NO_TELEPON');
	$reqNoFax= $cabang->getField('NO_FAX');
	$reqStatusProyek= $cabang->getField('STATUS_PROYEK');
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
		url:'<?=base_url()?>cabang_json/add',
		onSubmit:function(){
			return $(this).form('validate');
		},
		success:function(data){
			data = data.split("-");
			$.messager.alert('Info', data[1], 'info');	
			
			$('#reqLampiran').MultiFile('reset');
			
			<?
			if($reqMode == "update")
			{
			?>
				document.location.reload();
				window.parent.closePopup();
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
			
			<?
			if($reqSource == "proyek")
			{
			?>
				parent.createRow(data[0]);
				window.parent.closePopup();
			<?
			}
			?>
			
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
	<div id="judul-popup">Tambah <? if($reqSource != "") echo "Lokasi Proyek"; else { ?>Unit<? } ?></div>
	<div id="konten">
    	<div id="popup-tabel2">
            <form id="ff" method="post" novalidate>
                    <table class="table">
                    <thead>
                    	<tr>           
                             <td>Cabang Id</td><td>:</td>
                             <td>
                             <input type="text" name="reqCabangId" class="easyui-validatebox" value="<?=$reqCabangId?>" required="required">
                            </td>			
                        </tr>
                        <tr>           
                             <td>Nama</td><td>:</td>
                             <td>
                             <input type="text" name="reqNama" class="easyui-validatebox" value="<?=$reqNama?>" required="required">
                            </td>			
                        </tr>
                        <!-- <tr>
                            <td>Keterangan</td><td>:</td>
                            <td>
                                <textarea name="reqKeterangan" class="easyui-validatebox" style="width:250px; height:10 0px;"><?=$reqKeterangan?></textarea>
                            </td>
                        </tr>
                        <tr>           
                             <td>Lokasi</td><td>:</td>
                             <td>
                             	<select name="reqLokasi" id="reqLokasi" class="easyui-combobox">
	                                <option value="">- PILIH LOKASI -</option>
                                	<option value="JAWA" <? if($reqLokasi == "JAWA") echo "selected"; ?>>JAWA</option>
                                	<option value="LUAR" <? if($reqLokasi == "LUAR") echo "selected"; ?>>LUAR JAWA</option>
                                </select>
                            </td>			
                        </tr> -->

                        <tr>
                            <td>Alamat Unit</td><td>:</td>
                            <td>
                                <textarea name="reqAlamatKantor" class="easyui-validatebox" style="width:250px; height:10 0px;"><?=$reqAlamatKantor?></textarea>
                            </td>
                        </tr>
                        <tr>           
                             <td>No Telepon</td><td>:</td>
                             <td>
                             <input type="text" name="reqNoTelepon" style="width: 300px;" class="easyui-validatebox" value="<?=$reqNoTelepon?>">
                            </td>			
                        </tr>
                        <tr>           
                             <td>No Fax</td><td>:</td>
                             <td>
                             <input type="text" name="reqNoFax" class="easyui-validatebox" value="<?=$reqNoFax?>">
                            </td>			
                        </tr>
                        <tr>           
                             <td>Latitude</td><td>:</td>
                             <td>
                             <input type="text" name="reqLatitude" class="easyui-validatebox" value="<?=$reqLatitude?>">
                            </td>			
                        </tr>
                        <tr>           
                             <td>Longitude</td><td>:</td>
                             <td>
                             <input type="text" name="reqLongitude" class="easyui-validatebox" value="<?=$reqLongitude?>">
                            </td>			
                        </tr>
                        <tr>           
                             <td>Radius</td><td>:</td>
                             <td>
                             <input type="text" name="reqRadius" class="easyui-validatebox" value="<?=$reqRadius?>"><span>&nbsp;&nbsp;Meter</span>
                            </td>			
                        </tr>
                    </table>
                    </thead>
                    <input type="hidden" name="reqSource" value="<?=$reqSource?>" />
                    <input type="hidden" name="reqId" value="<?=$reqId?>" />
                    <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
                    <input type="hidden" name="reqStatusProyek" value="<?=$reqStatusProyek?>" />
                    <input type="submit" name="reqSubmit"  class="btn btn-primary" value="Submit" />
                    <input type="reset" id="rst_form"  class="btn btn-primary" value="Reset" />
                    
                    </form>
        </div>
        </div>
    </div>
</body>
</html>