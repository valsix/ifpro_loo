
<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model("LokasiArsip");
$lokasi_arsip = new LokasiArsip();

$reqId = $this->input->get("reqId");
$reqParent = $this->input->get("reqParent");

$reqSatuanKerjaId = $this->SATUAN_KERJA_ID_ASAL;
$reqCabangId = $this->CABANG_ID;

if($reqId == ""){
    $reqMode = "insert";
    
    $kodeParent = new LokasiArsip();
    $kodeParent->selectByParams(array("A.LOKASI_ARSIP_ID" => $reqParent));
    // echo $kodeParent->query;exit;
    $kodeParent->firstRow();
    $reqKodeParent                = $kodeParent->getField("KODE");
}
else
{
    $reqMode = "update";
    $lokasi_arsip->selectByParams(array("A.LOKASI_ARSIP_ID" => $reqId));
    $lokasi_arsip->firstRow();

    $reqId                  = $lokasi_arsip->getField("LOKASI_ARSIP_ID");
    $reqParent              = $lokasi_arsip->getField("LOKASI_ARSIP_ID_PARENT");
    $reqNama                = $lokasi_arsip->getField("NAMA");
    $reqKode                = $lokasi_arsip->getField("KODE");
    $reqKeterangan          = $lokasi_arsip->getField("KETERANGAN");
    $reqCabangId            = $lokasi_arsip->getField("CABANG_ID");
    $reqSatuanKerjaId       = $lokasi_arsip->getField("SATUAN_KERJA_ID");
   
    $kodeParent = new LokasiArsip();
    $kodeParent->selectByParams(array("A.LOKASI_ARSIP_ID" => $reqParent));
    // echo $kodeParent->query;exit;
    $kodeParent->firstRow();
    $reqKodeParent                = $kodeParent->getField("KODE");
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

$(function(){
    
    $('#ff').form('submit',{
        url:'web/lokasi_arsip_json/add',
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
            $.messager.alertLink('Info', data, 'info', "app/loadUrl/app/master_lokasi_arsip");	
        }
    });
	
});

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
	<div id="judul-popup">Kelola Lokasi Arsip</div>
	<div id="konten">
    	<div id="popup-tabel2">
            
            <form id="ff" method="post" novalidate enctype="multipart/form-data">
                <div class="sub-judul-popup">
                    <h4><i class="fa fa-file-text fa-sm" style="color: #2d77be"></i> Data</h4>                      
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <td>Nama</td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqNama" class="easyui-validatebox textbox form-control" required name="reqNama" value="<?=$reqNama?>" data-options="required:true" style="width:50%" />
                            </td>
                        </tr>

                        <? if($reqParent!='0'){ ?>
                        <tr>
                            <td>Kode Induk</td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqKodeParent" class="easyui-validatebox textbox form-control" required name="reqKodeParent" value="<?=$reqKodeParent?>" data-options="required:true" style="width:50%" readonly/>
                            </td>
                        </tr>
                        <?
                        }
                        ?>

                        <tr>
                            <td>Kode</td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqKode" class="easyui-validatebox textbox form-control" required name="reqKode" value="<?=$reqKode?>" data-options="required:true" style="width:30%" />
                            </td>
                        </tr>
                        <tr>           
                            <td>Keterangan</td>
                            <td>:</td>
                            <td>
                             	<div style="width: 80%">
                                      <textarea name="reqKeterangan" style="width:100%; height:100px"><?=$reqKeterangan ?></textarea>
                                </div>
                            </td>
                        </tr>
                    </thead>
                </table>

                <input type="hidden" name="reqId" value="<?=$reqId?>" />
                <input type="hidden" name="reqParent" value="<?=$reqParent?>" />
                <input type="hidden" name="reqSatuanKerjaId" value="<?=$reqSatuanKerjaId?>" />
                <input type="hidden" name="reqCabangId" value="<?=$reqCabangId?>" />
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
    
    $('#btnNaskahTemplateId').on('click', function() {
        var naskahTemplate = $('#reqNaskahTemplate').combotree('getValue');
        // alert(naskahTemplate);close();
        if(naskahTemplate == ""){
            alert('Pilih template terlebih dahulu');
            return false;
        }
        else{
            parent.openAdd("report/loadTemplate/"+naskahTemplate);           
        }
    });
    
});

function submitForm(){
    
    $('#ff').form('submit',{
        url:'web/lokasi_arsip_json/add',
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
            $.messager.alertLink('Info', data, 'info', "app/loadUrl/main/lokasi");  
        }
    });
}
function clearForm(){
    $('#ff').form('clear');
}
            
</script>