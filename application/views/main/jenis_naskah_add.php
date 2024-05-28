
<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");


$this->load->model("JenisNaskah");
$jenis_naskah = new JenisNaskah();

$reqId = $this->input->get("reqId");

if($reqId == ""){
$reqMode = "insert";
}
else
{
	$reqMode = "ubah";
	$jenis_naskah->selectByParams(array("A.JENIS_NASKAH_ID" => $reqId));
	$jenis_naskah->firstRow();
    
	$reqId            			= $jenis_naskah->getField("JENIS_NASKAH_ID");
	$reqNama                    = $jenis_naskah->getField("NAMA");
	$reqKeterangan              = $jenis_naskah->getField("KETERANGAN");
	$reqPrefix             		= $jenis_naskah->getField("PREFIX");
	$reqKodeSurat               = $jenis_naskah->getField("KODE_SURAT");
	$reqKodeSuratKeluar			= $jenis_naskah->getField("KODE_SURAT_KELUAR");
	$reqDigit					= $jenis_naskah->getField("DIGIT_NOMOR");
    $reqFile                    = $jenis_naskah->getField("ATTACHMENT");
    $reqTipeNaskah              = $jenis_naskah->getField("TIPE_NASKAH");
    $reqNaskahTemplate          = $jenis_naskah->getField("LINK_URL");
	$reqNamaLevel				= $jenis_naskah->getField("NAMA_LEVEL");
	$reqKdLevel					= $jenis_naskah->getField("KD_LEVEL");
	$reqNamaLevelCabang			= $jenis_naskah->getField("NAMA_LEVEL_CABANG");
	$reqKdLevelCabang			= $jenis_naskah->getField("KD_LEVEL_CABANG");
	$reqPenerbit				= $jenis_naskah->getField("PENERBIT_NOMOR");
    $reqJenisTTD                = $jenis_naskah->getField("JENIS_TTD");
	
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
//         url:'web/jenis_naskah_json/add',
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
//             $.messager.alertLink('Info', data, 'info', "app/loadUrl/admin/jenis_naskah");	
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
	<div id="judul-popup">Kelola Jenis Naskah</div>
	<div id="konten">
    	<div id="popup-tabel2">

            <form id="ff" method="post" novalidate enctype="multipart/form-data">
                <table class="table">
                    <thead>
                    	<tr>
                            <td>Jenis Naskah</td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqNama" class="easyui-validatebox textbox form-control" required name="reqNama"  value="<?=$reqNama ?>" data-options="required:true" style="width:90%" />
                            </td>
                        </tr>
                        <tr>
                            <td>Digit Nomor Surat</td>
                            <td>:</td>
                            <td>
                                <input  id="reqPrefix" class="easyui-numberbox textbox form-control" name="reqDigit"  value="<?=$reqDigit?>" data-options="required:false" style="width:150px" />
                            </td>
                        </tr>
                        <tr>           
                            <td>Tipe Naskah</td>
                            <td>:</td>
                            <td>
                             	<?
                               	$reqTipeNaskahPilih = str_replace(",", "','", $reqTipeNaskah);
                                ?>
                                <input type="text" name="reqTipeNaskahPilih" class="easyui-combotree"  id="reqTipeNaskahPilih" 
                                  	data-options="width:'350', valueField:'id', textField:'text', editable:false, url:'combo_json/comboTipeNaskah', multiple:true, value:['<?=$reqTipeNaskahPilih?>']" required />

                                <input type="hidden" name="reqTipeNaskah" id="reqTipeNaskah"   value="<?=$reqTipeNaskah?>">
                            </td>
                        </tr>
                        <tr>
                        	<td>Nomor Naskah  
                        		<a title="
                        			[SIFAT] : Sifat Surat,<br/>
                                    [SATKER] : Kode Jabatan,<br/>
                                    [KODECABANG] : Kode Cabang,<br/>
                                    [KLAS] : Kode Klasifikasi,<br/>
                                    [NOMOR] : Nomor Naskah,<br/>
                                    [BULAN] : Bulan MM,<br/>
                                    [TAHUN] : Tahun YYYY,<br/>
                                    [BULANROMAWI] : Bulan Romawi" class="easyui-tooltip"><i class="fa fa-info-circle"></i></a>
                        	</td>
                        	<td>:</td>
                        	<td>
                        		<input type="text" id="reqKodeSurat" class="easyui-validatebox form-control"  name="reqKodeSurat"  value="<?=$reqKodeSurat ?>" data-options="required:false" style="width:90%" />
                        	</td>
                        </tr>  
                        <tr>           
                            <td>Penerbit Nomor Naskah</td>
                            <td>:</td>
                            <td>
                                <input type="text" name="reqPenerbit" class="easyui-combobox"  id="reqPenerbit"
                                       data-options="width:'350', valueField:'id', textField:'text', editable:false, url:'combo_json/comboPenerbitNomor'" required value="<?=$reqPenerbit?>" />
                            </td>
                        </tr>
                        <tr>           
                            <td>Jenis Tanda Tangan</td>
                            <td>:</td>
                            <td>
                             	<?
                                $reqJenisTTDPilih = str_replace(",", "','", $reqJenisTTD);
                                ?>
                                <input type="text" name="reqJenisTTDPilih" class="easyui-combotree"  id="reqJenisTTDPilih"
                                        data-options="width:'350',valueField:'id', textField:'text', editable:false,url:'combo_json/comboTandaTangan',multiple:true,value:['<?=$reqJenisTTDPilih?>']" required />
                                                                
                                <input type="hidden" name="reqJenisTTD" id="reqJenisTTD"   value="<?=$reqJenisTTD?>">
                            </td>
                        </tr>
                        <tr>           
                            <td>Level Naskah (Pusat)</td>
                            <td>:</td>
                            <td>
                             	<?
                                $reqKdLevelPilih = str_replace(",", "','", $reqKdLevel);
                                ?>
                                <input type="text" name="reqKdLevelPilih" class="easyui-combotree"  id="reqKdLevelPilih"
                                        data-options="width:'350',valueField:'id', textField:'text', editable:false,url:'combo_json/comboLevel',multiple:true,value:['<?=$reqKdLevelPilih?>']" required />
                                                                
                                <input type="hidden" name="reqNamaLevel" id="reqNamaLevel"   value="<?=$reqNamaLevel?>">
                                <input type="hidden" name="reqKdLevel" id="reqKdLevel"   value="<?=$reqKdLevel?>">
                            </td>
                        </tr>
                        <tr>           
                            <td>Level Naskah (Cabang)</td>
                            <td>:</td>
                            <td>
                             	<?
                                $reqKdLevelCabangPilih = str_replace(",", "','", $reqKdLevelCabang);
                                ?>
                                <input type="text" name="reqKdLevelCabangPilih" class="easyui-combotree"  id="reqKdLevelCabangPilih"
                                        data-options="width:'350',valueField:'id',textField:'text', editable:false,url:'combo_json/comboLevelCabang',multiple:true,value:['<?=$reqKdLevelCabangPilih?>']"  />
                                                                
                                <input type="hidden" name="reqNamaLevelCabang" id="reqNamaLevelCabang"   value="<?=$reqNamaLevelCabang?>">
                                <input type="hidden" name="reqKdLevelCabang" id="reqKdLevelCabang"   value="<?=$reqKdLevelCabang?>">
                            </td>
                        </tr>
                        <tr>
                            <td>Keterangan</td>
                            <td>:</td>
                            <td>
                            	<textarea name="reqKeterangan" class="easyui-validatebox textbox form-control" style="width:90%; height:100px"><?=$reqKeterangan ?></textarea>
                            </td>
                        </tr>
                        <tr>
                        	<td>File</td>
                        	<td>:</td>
                        	<td>
                        		<input readonly class="easyui-validatebox" type="hidden" name="reqLinkFileTemp[]" id="reqLinkFileTemp<?=$id?>" value="<?=$reqFile?>" style="width:90%">
                                <input name="reqLinkFile[]" type="file" class="" value=""/>
                                <br>&nbsp;</br>
                                    <?
                                    if($reqFile == "")
                                    {}
                                    else
                                    {
                                    ?>
                                        <a href="uploads/<?=$reqFile?>" target="_blank"><i class="fa fa-download fa-lg"></i> Unduh</a>
                                    <?
                                    }
                                    ?>
                        	</td>
                        </tr>
                        <tr>
                        	<td>Template</td>
                        	<td>:</td>
                        	<td>
                        		<input type="text" id="reqNaskahTemplate" class="easyui-combobox" name="reqNaskahTemplate" data-options="width:'350', valueField:'id', textField:'text', url:'web/naskah_template_json/combo'" value="<?=$reqNaskahTemplate?>"/>
                        		&nbsp;
                                <a id="btnNaskahTemplateId"><button type="button" class="btn btn-success btn-sm"><i class="fa fa-eye fa-lg" aria-hidden="true"></i> Lihat</button></a>
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
		<?php /*?>alert(<?=$reqId?>);<?php */?>
        var naskahTemplate = $('#reqNaskahTemplate').combotree('getValue');
        // alert(naskahTemplate);close();
        if(naskahTemplate == ""){
            alert('Pilih template terlebih dahulu');
            return false;
        }
        else{
            parent.openAdd("report/loadTemplate/"+naskahTemplate+"/?reqId="+'<?=$reqId?>');           
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