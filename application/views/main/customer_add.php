
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