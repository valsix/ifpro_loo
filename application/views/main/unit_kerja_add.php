
<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model("SatuanKerja");
$satuan_kerja = new SatuanKerja();

$reqId = $this->input->get("reqId");
// echo $reqId;exit;

if($reqId == ""){
    $reqMode = "insert";
    exit;
}
else
{
    $reqMode = "update";
    $satuan_kerja->selectByParamsAlamat(array("A.KODE" => $reqId));
    // echo $satuan_kerja->query;exit;
    $satuan_kerja->firstRow();

    $reqKodeSatker= $reqId;
    $reqId= $satuan_kerja->getField("KODE_UNIT_KERJA_ID");
    $reqNama            = $satuan_kerja->getField("NAMA");
    $reqAlamat          = $satuan_kerja->getField("ALAMAT");
    $reqKeterangan      = $satuan_kerja->getField("KETERANGAN");
    $reqTelepon         = $satuan_kerja->getField("TELEPON");
    $reqFax             = $satuan_kerja->getField("FAX");
    $reqLokasi          = $satuan_kerja->getField("LOKASI");

    $satuan_kerja->selectByParamsFix(array("A.SATUAN_KERJA_ID" => $reqKodeSatker));
    // echo $satuan_kerja->query;exit;
    $satuan_kerja->firstRow();
    $reqKode            = $satuan_kerja->getField("KODE_SURAT");
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
<script type="text/javascript">
function submitForm(){  
    $('#ff').form('submit',{
        url:'web/satuan_kerja_json/ubah_alamat',
        onSubmit:function(){
            return $(this).form('enableValidation').form('validate');
        },
        success:function(data){
            // var reqKode = $("#reqKode").val();
            var reqKode = "<?=$reqKodeSatker?>";

            // console.log(data);return false;
            // $.messager.alertLink('Info', data, 'info', "app/loadUrl/main/unit_kerja");
            $.messager.alertLink('Info', data, 'info');
            setTimeout(function(){
                var url="main/index/unit_kerja_add/?reqId="+reqKode;
                window.location.href=url;
            }, 700); 
            
        }
    });
}
function clearForm(){
    $('#ff').form('clear');
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
	<div id="judul-popup">Kelola Alamat</div>
	<div id="konten">
    	<div id="popup-tabel2">

            <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">
                <table class="table">
                    <thead>
                    	<tr>
                            <td>Nama</td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqNama" readonly class="easyui-validatebox textbox form-control" required name="reqNama" value="<?=$reqNama?>" data-options="required:true" style="width:50%" />
                            </td>
                        </tr>
                        <tr>           
                            <td>Keterangan</td>
                            <td>:</td>
                            <td>
                             	<div style="width: 80%">
                                      <textarea name="reqKeterangan" class="easyui-validatebox" style="height:100px"><?=$reqKeterangan ?></textarea>
                                </div>
                            </td>
                        </tr>
                        <tr>           
                            <td>Alamat</td>
                            <td>:</td>
                            <td>
                                <div style="width: 80%">
                                      <textarea name="reqAlamat" class="easyui-validatebox" style="height:100px"><?=$reqAlamat ?></textarea>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Telepon</td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqTelepon" class="easyui-validatebox textbox form-control" required name="reqTelepon" value="<?=$reqTelepon?>" data-options="required:true" style="width:20%" />
                            </td>
                        </tr>
                        <tr>
                            <td>Fax</td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqFax" class="easyui-validatebox textbox form-control" required name="reqFax" value="<?=$reqFax?>" data-options="required:true" style="width:20%" />
                            </td>
                        </tr>
                        <tr>
                            <td>Lokasi</td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqLokasi" class="easyui-validatebox textbox form-control" required name="reqLokasi" value="<?=$reqLokasi?>" data-options="required:true" style="width:50%" />
                            </td>
                        </tr>
                        <tr>
                            <td>Kode Surat</td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqKode" class="easyui-validatebox textbox form-control" required name="reqKode" value="<?=$reqKode?>" data-options="required:true" style="width:50%" />
                            </td>
                        </tr>
                        
                    </thead>
                </table>

                <input type="hidden" name="reqId" value="<?=$reqId?>" />
                <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
                <input type="hidden" name="reqKodeSatker" value="<?=$reqKodeSatker?>" />

                <div style="text-align:center;padding:5px">
                    <a href="javascript:void(0)" class="btn btn-primary" onclick="submitForm()">Submit</a>
                    <a href="javascript:void(0)" class="btn btn-warning" onclick="clearForm()">Clear</a>
                </div>
            </form>

        </div>
    </div>
</body>
</html>


