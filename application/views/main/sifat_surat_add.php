<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model("SifatSurat");
$sifat_surat = new SifatSurat();

$reqId = $this->input->get("reqId");

if($reqId == ""){
    $reqMode = "insert";
}
else
{
	$reqMode = "update";
	$sifat_surat->selectByParams(array("A.SIFAT_SURAT_ID" => $reqId));
	$sifat_surat->firstRow();

	$reqId            	    = $sifat_surat->getField("SIFAT_SURAT_ID");
	$reqNama                = $sifat_surat->getField("NAMA");
    $reqKode                = $sifat_surat->getField("KODE");
    $reqWarna               = $sifat_surat->getField("WARNA");
    $reqKunci               = $sifat_surat->getField("KUNCI");
	$reqKeterangan          = $sifat_surat->getField("KETERANGAN");
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>

<base href="<?=base_url();?>">

<?php /*?><script type="text/javascript" src="<?=base_url()?>js/jquery-1.9.1.js"></script><?php */?>

<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/gaya.css">

<link rel="stylesheet" type="text/css" href="<?=base_url()?>lib/easyui/themes/default/easyui.css">

<link href="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

<!-- tiny MCE -->
<script src="<?=base_url()?>lib/tinyMCE/tinymce.min.js"></script>

<script type="text/javascript">
    tinymce.init({
        selector: "textarea",
        plugins: [
            "advlist autolink lists link image charmap print preview anchor",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime media table contextmenu paste"
        ],
        toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
        menubar: true,

    });
</script>

<?php /*?><script type="text/javascript" src="<?=base_url()?>js/jquery-1.6.1.min.js"></script><?php */?>
<script type="text/javascript" src="<?=base_url()?>lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript" src="<?=base_url()?>lib/easyui/globalfunction.js"></script>
<script>	

function submitForm(){
    $('#ff').form('submit',{
        url:'web/sifat_surat_json/add',
        onSubmit:function(){
            return $(this).form('enableValidation').form('validate');
        },
        success:function(data){
            // alert(data);return false;
            $.messager.alertLink('Info', data, 'info', "app/loadUrl/admin/sifat_surat");
        }
    });
	
}
function clearForm(){
    $('#ff').form('clear');
}

function createRow(namaPegawai, nrp)
{
	$("#reqNamaPegawai").val(namaPegawai);
	$("#reqPegawaiId").val(nrp);
}
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
<?php /*?><link href="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/js/bootstrap.min.js"></script><?php */?>

<!-- eModal -->
<!--<script src="lib/startbootstrap-sb-admin-2-1.0.7/dist/js/eModal.min.js"></script>-->
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
	<div id="judul-popup">Kelola Sifat Surat</div>
	<div id="konten">
    	<div id="popup-tabel2">
            <form id="ff" method="post" novalidate enctype="multipart/form-data">
                <table class="table">
                    <thead>
                    	<tr>
                            <td>Kode</td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqKode" class="easyui-validatebox textbox form-control" required name="reqKode" value="<?=$reqKode?>" data-options="required:true" style="width:40%" />
                            </td>
                        </tr>
                        <tr>
                            <td>Nama</td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqNama" class="easyui-validatebox textbox form-control" required name="reqNama" value="<?=$reqNama?>" data-options="required:true" style="width:80%" />
                            </td>
                        </tr>
                        <tr>           
                            <td>Kunci</td>
                            <td>:</td>
                            <td>
                             	<input type="checkbox" id="reqKunci" class="easyui-validatebox textbox" name="reqKunci" value="<?=$reqKunci?>" data-options="required:false" <?if($reqKunci == "1"){?> checked <?}?> />
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
                <!-- <input type="submit" name="reqSubmit"  class="btn btn-primary" value="Submit" /> -->
                <!-- <input type="reset" id="rst_form"  class="btn btn-primary" value="Reset" /> -->

                <div style="text-align:center;padding:5px">
                    <a href="javascript:void(0)" class="btn btn-primary" onclick="submitForm()">Submit</a>
                    <a href="javascript:void(0)" class="btn btn-warning" onclick="clearForm()">Clear</a>
                </div>
                    
            </form>
        </div>
    </div>
</body>
</html>