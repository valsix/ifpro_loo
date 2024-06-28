
<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");


$this->load->model("Customer");
$set = new Customer();

$reqId = $this->input->get("reqId");

if($reqId == ""){
$reqMode = "insert";
}
else
{
	$reqMode = "ubah";
	$set->selectByParams(array("A.CUSTOMER_ID" => $reqId));
	$set->firstRow();
    
	$reqId= $set->getField("CUSTOMER_ID");
	$reqPic= $set->getField("PIC");
	$reqJenisPerusahaanId= $set->getField("JENIS_PERUSAHAAN_ID");
	$reqTelp= $set->getField("TELP");
	$reqEmail= $set->getField("EMAIL");
	$reqTempat= $set->getField("TEMPAT");
	$reqNamaPemilik= $set->getField("NAMA_PEMILIK");
    $reqNamaBrand= $set->getField("NAMA_BRAND");
    $reqNpwp= $set->getField("NPWP");
    $reqNpwpAlamat= $set->getField("NPWP_ALAMAT");
    $reqNomorNior= $set->getField("NOMOR_NIOR");
    $reqAlamatDomisili= $set->getField("ALAMAT_DOMISILI");
    $reqKedudukan= $set->getField("INFO_KEDUDUKAN");
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

<?php /*?><script type="text/javascript" src="<?=base_url()?>js/jquery-1.6.1.min.js"></script><?php */?>
<script type="text/javascript" src="<?=base_url()?>lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript" src="<?=base_url()?>lib/easyui/globalfunction.js"></script>
<script type="text/javascript">	

// $(function(){
    
//     $('#ff').form('submit',{
//         url:'web/customer_json/add',
//         onSubmit:function(){
//             $("#reqKdLevel").val($("#reqKdLevelPilih").combotree("getValues"));	
//             $("#reqNamaLevel").val($("#reqKdLevelPilih").combotree("getText"));	

//             $("#reqKdLevelCabang").val($("#reqKdLevelCabangPilih").combotree("getValues"));	
//             $("#reqNamaLevelCabang").val($("#reqKdLevelCabangPilih").combotree("getText"));	
            
//             $("#reqTipeNaskah").val($("#reqTipeNaskahPilih").combotree("getValues"));	
//             $("#reqJenisTTD").val($("#reqJenisTTDPilih").combotree("getValues"));   
            
//             return $(this).form('enableValidation').form('validate');
//         },
//         success:function(data){
//             $.messager.alertLink('Info', data, 'info', "app/loadUrl/admin/customer");	
//         }
//     });
	
// });

// function createRow(namaPegawai, nrp)
// {
// 	$("#reqNamaPegawai").val(namaPegawai);
// 	$("#reqPegawaiId").val(nrp);
// }
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
	<div id="judul-popup">Kelola Customer</div>
	<div id="konten">
    	<div id="popup-tabel2">

            <form id="ff" method="post" novalidate enctype="multipart/form-data">
                <table class="table">
                    <thead>
                        <tr>           
                            <td>Jenis Perusahaan</td>
                            <td>:</td>
                            <td>
                                <input type="text" name="reqJenisPerusahaanId" class="easyui-combobox" id="reqJenisPerusahaanId"
                                       data-options="width:'350', valueField:'id', textField:'text', editable:false, url:'combo_json/comboJenisPerusahaan'" required value="<?=$reqJenisPerusahaanId?>" />
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 20%">Nama Pemilik</td>
                            <td style="width: 10px">:</td>
                            <td style="width: 24%">
                                <input type="text" id="reqNamaPemilik" class="easyui-validatebox textbox form-control" required name="reqNamaPemilik" value="<?=$reqNamaPemilik?>" data-options="required:true" />
                            </td>
                            <td style="width: 20%">PIC</td>
                            <td style="width: 10px">:</td>
                            <td style="width: 24%">
                                <input type="text" id="reqPic" class="easyui-validatebox textbox form-control" required name="reqPic" value="<?=$reqPic?>" data-options="required:true" />
                            </td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqEmail" class="easyui-validatebox textbox form-control" required name="reqEmail" value="<?=$reqEmail?>" data-options="required:true" style="width:90%" />
                            </td>
                            <td>Telepon/HP</td>
                            <td>:</td>
                            <td>
                                <input id="reqTelp" class="easyui-validatebox textbox form-control" name="reqTelp" value="<?=$reqTelp?>" data-options="required:false" style="width:150px" />
                            </td>
                        </tr>
                        <tr>
                            <td>Nama Brand</td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqNamaBrand" class="easyui-validatebox textbox form-control" required name="reqNamaBrand" value="<?=$reqNamaBrand?>" data-options="required:true" />
                            </td>
                            <td>Tempat</td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqTempat" class="easyui-validatebox textbox form-control" required name="reqTempat" value="<?=$reqTempat?>" data-options="required:true" />
                            </td>
                        </tr>
                        <tr>
                            <td>Nomor NPWP</td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqNpwp" class="easyui-validatebox textbox form-control" required name="reqNpwp" value="<?=$reqNpwp?>" data-options="required:true" />
                            </td>
                            <td>Alamat NPWP</td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqNpwpAlamat" class="easyui-validatebox textbox form-control" required name="reqNpwpAlamat" value="<?=$reqNpwpAlamat?>" data-options="required:true" />
                            </td>
                        </tr>
                        <tr>
                            <td>Nomor NIORA</td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqNomorNior" class="easyui-validatebox textbox form-control" required name="reqNomorNior" value="<?=$reqNomorNior?>" data-options="required:true" />
                            </td>
                            <td>Alamat Domisili</td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqAlamatDomisili" class="easyui-validatebox textbox form-control" required name="reqAlamatDomisili" value="<?=$reqAlamatDomisili?>" data-options="required:true" />
                            </td>
                        </tr>

                        <tr>
                            <td>Keterangan Kedudukan</td>
                            <td>:</td>
                            <td colspan="4">
                                <textarea id="reqKedudukan" name="reqKedudukan" style="width:90%; height:100px"><?=$reqKedudukan?></textarea>
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

$(document).ready(function() {
    //$('#reqKdLevel').combobox('setValues', ['LEVEL3','LEVEL4']);
    
});

function submitForm(){
    
    $('#ff').form('submit',{
        url:'web/customer_json/add',
        onSubmit:function(){
            // $("#reqKdLevel").val($("#reqKdLevelPilih").combotree("getValues")); 
            // $("#reqNamaLevel").val($("#reqKdLevelPilih").combotree("getText")); 

            // $("#reqKdLevelCabang").val($("#reqKdLevelCabangPilih").combotree("getValues")); 
            // $("#reqNamaLevelCabang").val($("#reqKdLevelCabangPilih").combotree("getText")); 
            
            // $("#reqTipeNaskah").val($("#reqTipeNaskahPilih").combotree("getValues"));   
            // $("#reqJenisTTD").val($("#reqJenisTTDPilih").combotree("getValues"));   
            
            return $(this).form('enableValidation').form('validate');
        },
        success:function(data){
            $.messager.alertLink('Info', data, 'info', "main/index/customer");  
        }
    });
}
function clearForm(){
    $('#ff').form('clear');
}
            
</script>

<link rel="stylesheet" href="lib/froala_editor_2.9.8/css/froala_editor.css">
<link rel="stylesheet" href="lib/froala_editor_2.9.8/css/froala_style.css">
<link rel="stylesheet" href="lib/froala_editor_2.9.8/css/plugins/code_view.css">
<link rel="stylesheet" href="lib/froala_editor_2.9.8/css/plugins/draggable.css">
<link rel="stylesheet" href="lib/froala_editor_2.9.8/css/plugins/colors.css">
<link rel="stylesheet" href="lib/froala_editor_2.9.8/css/plugins/emoticons.css">
<link rel="stylesheet" href="lib/froala_editor_2.9.8/css/plugins/image_manager.css">
<link rel="stylesheet" href="lib/froala_editor_2.9.8/css/plugins/image.css">
<link rel="stylesheet" href="lib/froala_editor_2.9.8/css/plugins/line_breaker.css">
<link rel="stylesheet" href="lib/froala_editor_2.9.8/css/plugins/table.css">
<link rel="stylesheet" href="lib/froala_editor_2.9.8/css/plugins/char_counter.css">
<link rel="stylesheet" href="lib/froala_editor_2.9.8/css/plugins/video.css">
<link rel="stylesheet" href="lib/froala_editor_2.9.8/css/plugins/fullscreen.css">
<link rel="stylesheet" href="lib/froala_editor_2.9.8/css/plugins/file.css">
<link rel="stylesheet" href="lib/froala_editor_2.9.8/css/plugins/quick_insert.css">
<link rel="stylesheet" href="lib/froala_editor_2.9.8/css/plugins/help.css">
<link rel="stylesheet" href="lib/froala_editor_2.9.8/css/third_party/spell_checker.css">
<link rel="stylesheet" href="lib/froala_editor_2.9.8/css/plugins/special_characters.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/codemirror.min.css">

<style>
.ss {
    display: none;
}
</style>

<script type="text/javascript" src="lib/froala_editor_2.9.8/js/froala_editor.min.js" ></script>
<script type="text/javascript" src="lib/froala_editor_2.9.8/js/plugins/align.min.js"></script>
<script type="text/javascript" src="lib/froala_editor_2.9.8/js/plugins/char_counter.min.js"></script>
<script type="text/javascript" src="lib/froala_editor_2.9.8/js/plugins/code_beautifier.min.js"></script>
<script type="text/javascript" src="lib/froala_editor_2.9.8/js/plugins/code_view.min.js"></script>
<script type="text/javascript" src="lib/froala_editor_2.9.8/js/plugins/colors.min.js"></script>
<script type="text/javascript" src="lib/froala_editor_2.9.8/js/plugins/draggable.min.js"></script>
<script type="text/javascript" src="lib/froala_editor_2.9.8/js/plugins/emoticons.min.js"></script>
<script type="text/javascript" src="lib/froala_editor_2.9.8/js/plugins/entities.min.js"></script>
<script type="text/javascript" src="lib/froala_editor_2.9.8/js/plugins/file.min.js"></script>
<script type="text/javascript" src="lib/froala_editor_2.9.8/js/plugins/font_size.min.js"></script>
<script type="text/javascript" src="lib/froala_editor_2.9.8/js/plugins/font_family.min.js"></script>
<script type="text/javascript" src="lib/froala_editor_2.9.8/js/plugins/fullscreen.min.js"></script>
<script type="text/javascript" src="lib/froala_editor_2.9.8/js/plugins/image.min.js"></script>
<script type="text/javascript" src="lib/froala_editor_2.9.8/js/plugins/image_manager.min.js"></script>
<script type="text/javascript" src="lib/froala_editor_2.9.8/js/plugins/line_breaker.min.js"></script>
<script type="text/javascript" src="lib/froala_editor_2.9.8/js/plugins/inline_style.min.js"></script>
<script type="text/javascript" src="lib/froala_editor_2.9.8/js/plugins/link.min.js"></script>
<script type="text/javascript" src="lib/froala_editor_2.9.8/js/plugins/lists.min.js"></script>
<script type="text/javascript" src="lib/froala_editor_2.9.8/js/plugins/paragraph_format.min.js"></script>
<script type="text/javascript" src="lib/froala_editor_2.9.8/js/plugins/paragraph_style.min.js"></script>
<script type="text/javascript" src="lib/froala_editor_2.9.8/js/plugins/quick_insert.min.js"></script>
<script type="text/javascript" src="lib/froala_editor_2.9.8/js/plugins/quote.min.js"></script>
<script type="text/javascript" src="lib/froala_editor_2.9.8/js/plugins/table.min.js"></script>
<script type="text/javascript" src="lib/froala_editor_2.9.8/js/plugins/save.min.js"></script>
<script type="text/javascript" src="lib/froala_editor_2.9.8/js/plugins/url.min.js"></script>
<script type="text/javascript" src="lib/froala_editor_2.9.8/js/plugins/video.min.js"></script>
<script type="text/javascript" src="lib/froala_editor_2.9.8/js/plugins/help.min.js"></script>
<script type="text/javascript" src="lib/froala_editor_2.9.8/js/plugins/print.min.js"></script>
<script type="text/javascript" src="lib/froala_editor_2.9.8/js/third_party/spell_checker.min.js"></script>
<script type="text/javascript" src="lib/froala_editor_2.9.8/js/plugins/special_characters.min.js"></script>
<script type="text/javascript" src="lib/froala_editor_2.9.8/js/plugins/word_paste.min.js"></script>

<script>

    $(function(){
        $('textarea#reqKedudukan').froalaEditor({

            key: "qB1G1C1C1A1A2E7mD6F5F4E4E1B9D6C3C4A4g1Rd1Rb1MKF1AKUBWBOR==",
            
            imageUploadParam: 'image_param',
            
            // Set the image upload URL.
            imageUploadURL: '<?=base_url()?>upload',
            
            // Additional upload params.
            imageUploadParams: {id: 'my_editor'},
            
            // Set request type.
            imageUploadMethod: 'POST',
            
            // Set max image size to 5MB.
            imageMaxSize: 5 * 1024 * 1024,
            
            // Allow to upload PNG and JPG.
            imageAllowedTypes: ['jpeg', 'jpg', 'png'],
            
            events: {
                'image.beforeUpload': function (images) {
                // console.log(images)
                },
                'image.uploaded': function (response) {
                // console.log(response)
                },
                'image.inserted': function ($img, response) {
                console.log($img, response)
                // Image was inserted in the editor.
                },
                'image.replaced': function ($img, response) {
                // console.log($img, response)
                },
                'image.error': function (error, response) {
                // console.log(error, response)
                }
            },
            tableCellStyles: {
                borderAll: "Border All",
                borderTop: "Border Top",
                borderBottom: "Border Bottom",
                borderLeft: "Border Left",
                borderRight: "Border Right",
            }
          
        })
    });
</script>