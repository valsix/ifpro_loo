<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

/* CHECK USER LOGIN 
$CI =& get_instance();
$CI->checkUserLogin();*/

$this->load->model('IjinKoreksi');

$ijin_koreksi = new IjinKoreksi();

$reqId = $this->input->get("reqId");
$tempDepartemen = $userLogin->idDepartemen;

if($reqId == ""){
	$reqMode = "insert";
}
else
{
	$reqMode = "update";	
	$ijin_koreksi->selectByParams(array('IJIN_KOREKSI_ID'=>$reqId), -1, -1);
	$ijin_koreksi->firstRow();
	
	$tempNama= $ijin_koreksi->getField('NAMA');
	$tempKode= $ijin_koreksi->getField('KODE');
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
		url:'<?=base_url()?>ijin_koreksi_json/add',
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

/*$.extend($.fn.validatebox.defaults.rules, {  
			BandingJam: {
				validator: function(value, param){
					var start = value;
					var end = $(param[0]).val();
					var dtStart = new Date("1/1/2007 " + start);
					var dtEnd = new Date("1/1/2007 " + end);
					
					if(dtEnd < dtStart) 	return true;
					else 					return false;
					//return value.length >= param[0];
				},
				message: 'Menit akhir lebih besar / sama dengan menit awal '
			}  
		});
});*/

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
	<div id="judul-popup">Tambah Jam Masuk</div>
	<div id="konten">
    	<div id="popup-tabel2">
            <form id="ff" method="post" novalidate>
                    <table class="table">
                    <thead>
                       <tr>           
                             <td>Kode</td><td>:</td>
                             <td>
                                <input name="reqKode" class="easyui-validatebox" required="true" style="width:50px" type="text" value="<?=$tempKode?>" />
                            </td>			
                        </tr>
                        <tr>           
                             <td>Nama</td><td>:</td>
                             <td>
                                <input name="reqNama" class="easyui-validatebox" required="true" style="width:170px" type="text" value="<?=$tempNama?>" />
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