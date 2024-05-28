
<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");


$this->load->model("SatuanKerjaKelompok");
$this->load->model("SatuanKerjaKelompokDetil");
$satuan_kerja_kelompok = new SatuanKerjaKelompok();
$satuan_kerja_kelompok_detil = new SatuanKerjaKelompokDetil();

$reqJenisTujuan = "PB";
$reqId = $this->input->get("reqId");

if($reqId == ""){
    $reqMode = "insert";
    $reqJabatan     = "";
}
else
{
    $reqMode = "ubah";
    $satuan_kerja_kelompok->selectByParams(array("A.SATUAN_KERJA_KELOMPOK_ID" => $reqId));
    $satuan_kerja_kelompok->firstRow();
    
    $reqSatuanKerjaKelompokId           = $satuan_kerja_kelompok->getField("SATUAN_KERJA_KELOMPOK_ID");
    $reqKode                            = $satuan_kerja_kelompok->getField("KODE");
    $reqNama                            = $satuan_kerja_kelompok->getField("NAMA");
    $reqKeterangan                      = $satuan_kerja_kelompok->getField("KETERANGAN");
    $reqCabang                          = $satuan_kerja_kelompok->getField("CABANG_ID");
    $reqJabatan     = $satuan_kerja_kelompok->getData(array("A.SATUAN_KERJA_KELOMPOK_ID" => $reqId));
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
<base href="<?=base_url();?>">

<script type="text/javascript" src="<?=base_url()?>js/jquery-1.9.1.js"></script>

<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/gaya.css">

<link rel="stylesheet" type="text/css" href="<?=base_url()?>lib/easyui/themes/default/easyui.css">

<link href="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

<?php /*?><script type="text/javascript" src="<?=base_url()?>js/jquery-1.6.1.min.js"></script><?php */?>
<script type="text/javascript" src="<?=base_url()?>lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript" src="<?=base_url()?>lib/easyui/globalfunction.js"></script>
<script type="text/javascript">	

$(function(){
    
    $('#ff').form('submit',{
        url:'web/jenis_naskah_json/add',
        onSubmit:function(){
            $("#reqKdLevel").val($("#reqKdLevelPilih").combotree("getValues"));	
            $("#reqNamaLevel").val($("#reqKdLevelPilih").combotree("getText"));	

            $("#reqKdLevelCabang").val($("#reqKdLevelCabangPilih").combotree("getValues"));	
            $("#reqNamaLevelCabang").val($("#reqKdLevelCabangPilih").combotree("getText"));	
            
            $("#reqTipeNaskah").val($("#reqTipeNaskahPilih").combotree("getValues"));	
            $("#reqJenisTTD").val($("#reqJenisTTDPilih").combotree("getValues"));   
            
            return $(this).form('enableValidation').form('validate');
        },
        success:function(data){
            $.messager.alertLink('Info', data, 'info', "app/loadUrl/app/jenis_naskah");	
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
<link href="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

<!-- eModal -->
<!--<script src="lib/startbootstrap-sb-admin-2-1.0.7/dist/js/eModal.min.js"></script>-->
<script src="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/dist/js/eModal2.min.js"></script>
<script type="text/javascript">
	

	function openPopup(page) {
    	eModal.iframe(page, 'Aplikasi E-Office - ASDP Indonesia Ferry')
	}
	
	function closePopup()
	{
		eModal.close();
	}
	
</script>
</head>

<body class="bg-kanan-full">
	<div id="judul-popup">Kelola Kelompok Jabatan</div>
	<div id="konten">
    	<div id="popup-tabel2">

            <form id="ff" method="post" novalidate enctype="multipart/form-data">
                <table class="table">
                    <thead>
                    	<tr>
                            <td>Nama Kelompok</td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqNama" class="easyui-validatebox textbox form-control" required name="reqNama"  value="<?=$reqNama ?>" data-options="required:true" style="width:50%" />
                            </td>
                        </tr>
                        <tr>
                            <td>Jabatan</td>
                            <td>:</td>
                            <td>
                                <?
                                if($reqJabatan == "")
                                {}
                                else
                                    $reqJabatanPilih = str_replace(",", "','", $reqJabatan);
                                ?>
                                    <input type="text" name="reqJabatanPilih" class="easyui-combotree"  id="reqJabatanPilih"
                                                    data-options="width:'500', panelHeight:'400', panelWidth:'600',valueField:'id',
                                                                    textField:'JABATAN', url:'web/satuan_kerja_json/combobox_cabang_jabatan',
                                                                    cascadeCheck:false,multiple:true,value:['<?=$reqJabatanPilih?>'],
                                                                    onCheck: function(node,checked){
                                                                        addJabatan(node.id, checked);  // alert node text property when clicked
                                                                    }"  />
                                                                    
                                                                    
                                <div id="divJabatan" style="display:none">
                                <?
                                $arrJabatan = explode(",",$reqJabatan);
                                for($i=0;$i<count($arrJabatan);$i++)
                                {
                                ?>
                                <input type="text" id="<?=$arrJabatan[$i]?>" name="reqSatuanKerjaId[]" value="<?=$arrJabatan[$i]?>">
                                <?
                                }
                                ?>
                            </td>
                        </tr>
                        <tr>           
                            <td>Unit Kerja</td>
                            <td>:</td>
                            <td>
                             	<?
                                if($reqCabang == "")
                                {}
                                else
                                    $reqCabangPilih = str_replace(",", "','", $reqCabang);
                                ?>
                                    <input type="text" name="reqCabangPilih" class="easyui-combotree"  id="reqCabangPilih"
                                                    data-options="width:'500', panelHeight:'300', panelWidth:'600',valueField:'id',
                                                                    textField:'JABATAN', url:'web/satuan_kerja_json/combo_cabang_all',
                                                                    cascadeCheck:false,multiple:true,value:['<?=$reqCabangPilih?>']"  />
                                <input type="hidden" id="reqCabang" name="reqCabang" value="<?=$reqCabang?>">   
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
        url:'web/jenis_naskah_json/add',
        onSubmit:function(){
            $("#reqKdLevel").val($("#reqKdLevelPilih").combotree("getValues")); 
            $("#reqNamaLevel").val($("#reqKdLevelPilih").combotree("getText")); 

            $("#reqKdLevelCabang").val($("#reqKdLevelCabangPilih").combotree("getValues")); 
            $("#reqNamaLevelCabang").val($("#reqKdLevelCabangPilih").combotree("getText")); 
            
            $("#reqTipeNaskah").val($("#reqTipeNaskahPilih").combotree("getValues"));   
            $("#reqJenisTTD").val($("#reqJenisTTDPilih").combotree("getValues"));   
            
            return $(this).form('enableValidation').form('validate');
        },
        success:function(data){
            $.messager.alertLink('Info', data, 'info', "app/loadUrl/admin/jenis_naskah");  
        }
    });
}
function clearForm(){
    $('#ff').form('clear');
}
            
</script>