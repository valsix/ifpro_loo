
<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");


$this->load->model("LokasiLooDetil");
$lokasi_loo_detil = new LokasiLooDetil();

$reqId = $this->input->get("reqId");

if($reqId == ""){
$reqMode = "insert";
}
else
{
    $reqMode = "ubah";
    $lokasi_loo_detil->selectByParams(array("A.LOKASI_LOO_DETIL_ID" => $reqId));
    $lokasi_loo_detil->firstRow();
    
    $reqId= $lokasi_loo_detil->getField("LOKASI_LOO_DETIL_ID");
    $reqLokasiLooId= $lokasi_loo_detil->getField("LOKASI_LOO_ID");
    $reqKode= $lokasi_loo_detil->getField("KODE");
    $reqNama= $lokasi_loo_detil->getField("NAMA");
    $reqLantaiLooId= $lokasi_loo_detil->getField("LANTAI_LOO_ID");
    $reqPrime= $lokasi_loo_detil->getField("PRIME");
    $reqLuas= $lokasi_loo_detil->getField("LUAS");
    $reqKdTarif= $lokasi_loo_detil->getField("KD_TARIF");
    $reqDeskripsi= $lokasi_loo_detil->getField("DESKRIPSI");
    
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
//         url:'web/lokasi_loo_detil_json/add',
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
//             $.messager.alertLink('Info', data, 'info', "app/loadUrl/admin/lokasi_loo_detil");    
//         }
//     });
    
// });

// function createRow(namaPegawai, nrp)
// {
//  $("#reqNamaPegawai").val(namaPegawai);
//  $("#reqPegawaiId").val(nrp);
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
    <div id="judul-popup">Kelola Lokasi LOO</div>
    <div id="konten">
        <div id="popup-tabel2">

            <form id="ff" method="post" novalidate enctype="multipart/form-data">
                <table class="table">
                    <thead>
                        <tr>           
                            <td>Lokasi</td>
                            <td>:</td>
                            <td>
                                <input type="text" name="reqLokasiLooId" class="easyui-combobox"  id="reqLokasiLooId"
                                       data-options="width:'350', valueField:'id', textField:'text', editable:false, url:'combo_json/comboLokasiLoo'" required value="<?=$reqLokasiLooId?>" />
                            </td>
                        </tr>
                        <tr>
                            <td>Kode</td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqKode" class="easyui-validatebox textbox form-control" required name="reqKode"  value="<?=$reqKode ?>" data-options="required:true" style="width:90%" />
                            </td>
                        </tr>
                        <tr>
                            <td>Nama</td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqNama" class="easyui-validatebox textbox form-control" required name="reqNama"  value="<?=$reqNama ?>" data-options="required:true" style="width:90%" />
                            </td>
                        </tr>
                        <tr>
                            <td>Lantai</td>
                            <td>:</td>
                            <td>
                                <input type="text" name="reqLantaiLooId" class="easyui-combobox"  id="reqLantaiLooId"
                                       data-options="width:'350', valueField:'id', textField:'text', editable:false, url:'combo_json/comboLantaiLoo'" required value="<?=$reqLantaiLooId?>" />
                            </td>
                        </tr>
                        <tr>           
                            <td>Prime</td>
                            <td>:</td>
                            <td>
                                <input type="text" name="reqPrime" class="easyui-combobox"  id="reqPrime"
                                       data-options="width:'350', valueField:'id', textField:'text', editable:false, url:'combo_json/comboPrime'" required value="<?=$reqPrime?>" />
                            </td>
                        </tr>
                        <tr>
                            <td>Luas</td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqLuas" class="easyui-validatebox textbox form-control" required name="reqLuas"  value="<?=$reqLuas ?>" data-options="required:true" style="width:90%" />
                            </td>
                        </tr>
                        <tr>
                            <td>KD Tarif</td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqKdTarif" class="vlxuangclass easyui-validatebox textbox form-control totalluasoutdoor" required name="reqKdTarif"  value="<?=$reqKdTarif ?>" data-options="required:true" style="width:90%" />
                            </td>
                        </tr>
                        <tr>
                            <td>Deskripsi</td>
                            <td>:</td>
                            <td>
                                <textarea name="reqDeskripsi" class="easyui-validatebox textbox form-control" style="width:90%; height:100px"><?=$reqDeskripsi ?></textarea>
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
    
});

function submitForm(){
    
    $('#ff').form('submit',{
        url:'web/lokasi_loo_detil_json/add',
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
            $.messager.alertLink('Info', data, 'info', "main/index/lokasi_loo_detil");  
        }
    });
}
function clearForm(){
    $('#ff').form('clear');
}
            
</script>