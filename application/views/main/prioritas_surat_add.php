<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model("PrioritasSurat");
$prioritas_surat = new PrioritasSurat();

$reqId = $this->input->get("reqId");

if($reqId == ""){
    $reqMode = "insert";
}
else
{
	$reqMode = "update";
	$prioritas_surat->selectByParams(array("A.PRIORITAS_SURAT_ID" => $reqId));	
	$prioritas_surat->firstRow();
	$reqId            	    = $prioritas_surat->getField("PRIORITAS_SURAT_ID");
	$reqNama                = $prioritas_surat->getField("NAMA");
    $reqKode                = $prioritas_surat->getField("KODE");
    $reqLamaHari            = $prioritas_surat->getField("LAMA_HARI");
	$reqKeterangan          = $prioritas_surat->getField("KETERANGAN");
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
<base href="<?=base_url();?>">

<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/gaya.css">
<link rel="stylesheet" type="text/css" href="<?=base_url()?>lib/easyui/themes/default/easyui.css">
<link href="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?=base_url()?>lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript" src="<?=base_url()?>lib/easyui/globalfunction.js"></script>
<script type="text/javascript"> 
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

<script src="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/dist/js/eModal2.min.js"></script>
<script type="text/javascript">
    

    function openPopup(page) {
        eModal.iframe(page, 'Aplikasi E-Office - PT. Jembatan Nusantara')
    }
    
    function closePopup()
    {
        eModal.close();
    }
    
</script>
</head>

<body class="bg-kanan-full">
	<div id="judul-popup">Kelola Prioritas Surat</div>
	<div id="konten">
		<div id="popup-tabel2">
			<form id="ff" method="post" novalidate enctype="multipart/form-data">
				<table class="table">
					<thead>
						<tr>
							<td>Nama</td>
							<td>:</td>
							<td>
								<input type="text" id="reqNama" class="easyui-validatebox textbox form-control" required name="reqNama" value="<?=$reqNama?>" data-options="required:true" style="width:80%" />
							</td>
						</tr>
						<tr>
							<td>Lama Hari</td>
							<td>:</td>
							<td>
								<input type="text" id="reqLamaHari" class="easyui-validatebox textbox form-control" required name="reqLamaHari" value="<?=$reqLamaHari?>" data-options="required:true" style="width:80%" />
							</td>
						</tr>
						<tr>
							<td>Keterangan</td>
							<td>:</td>
							<td>
								<div style="width: 80%">
									<textarea name="reqKeterangan" style="height:100px"><?=$reqKeterangan ?></textarea>
								</div>
							</td>
						</tr>  
					</thead>
				</table>

				<input type="hidden" name="reqId" value="<?=$reqId?>" />
				<input type="hidden" name="reqMode" value="<?=$reqMode?>" />
				<div style="text-align:center;padding:5px">
                    <a href="javascript:void(0)" class="btn btn-primary" onclick="submitForm()">Submit</a>
                    <a href="javascript:void(0)" class="btn btn-warning" onclick="clearForm()">Clear</a>
                </div>

			</form>
		</div>
	</div>
</body>
</html>


<script>
$('#reqLamaHari').bind('keyup paste', function(){
	this.value = this.value.replace(/[^0-9]/g, '');
});

function submitForm(){
    $('#ff').form('submit',{
        url:'web/prioritas_surat_json/add',
        onSubmit:function(){
            return $(this).form('enableValidation').form('validate');
        },
        success:function(data){
            $.messager.alertLink('Info', data, 'info', "app/loadUrl/main/prioritas_surat");
        }
    });
}
function clearForm(){
    $('#ff').form('clear');
}
            
</script>