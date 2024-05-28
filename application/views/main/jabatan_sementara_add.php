
<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");


$this->load->model("PejabatPengganti");
$pejabat_pengganti = new PejabatPengganti();

$reqId = $this->input->get("reqId");

if($reqId == ""){
    $reqMode = "insert";
    $reqSkCabangId= $this->CABANG_ID;
}
else
{
    $reqMode = "ubah";
    $pejabat_pengganti->selectByParamsSatuanKerja(array("A.PEJABAT_PENGGANTI_ID" => $reqId));
    $pejabat_pengganti->firstRow();
    // echo $pejabat_pengganti->query;exit;

    $reqId                      = $pejabat_pengganti->getField("PEJABAT_PENGGANTI_ID");
    $reqPegawaiId               = $pejabat_pengganti->getField("PEGAWAI_ID");
    $reqSatuanKerjaId           = $pejabat_pengganti->getField("SATUAN_KERJA_ID");
    $reqSatuanKerja             = $pejabat_pengganti->getField("SATUAN_KERJA");

    $reqNama                    = $pejabat_pengganti->getField("NAMA");
    $reqPegawaiIdPengganti      = $pejabat_pengganti->getField("PEGAWAI_ID_PENGGANTI");
    $reqNamaPengganti           = $pejabat_pengganti->getField("NAMA_PENGGANTI");
    $reqTanggalMulai            = $pejabat_pengganti->getField("TANGGAL_MULAI");
    $reqTanggalSelesai          = $pejabat_pengganti->getField("TANGGAL_SELESAI");

    // tambahan khusus
    $reqAnTambahan= $pejabat_pengganti->getField("AN_TAMBAHAN");
    $reqStatusAktif= $pejabat_pengganti->getField("STATUS_AKTIF");
    $reqSkCabangId= $pejabat_pengganti->getField("SK_CABANG_ID");
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
//             $.messager.alertLink('Info', data, 'info', "app/loadUrl/app/jenis_naskah");	
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
	<div id="judul-popup">Kelola Jabatan Sementara</div>
	<div id="konten">
    	<div id="popup-tabel2">

            <form id="ff" method="post" novalidate enctype="multipart/form-data">
                <table class="table">
                    <thead>
                        <tr>
                            <td>Unit Kerja</td>
                            <td>:</td>
                            <td>
                                <input type="text" class="easyui-combotree" id="reqCabangId"
                                data-options="width:'500'
                                , valueField:'SATUAN_KERJA_ID'
                                , textField:'SATUAN_KERJA'
                                , url:'web/satuan_kerja_json/combotreeallcabang'
                                , onSelect: function(rec){
                                    var url = 'web/satuan_kerja_json/combotreeallcabangsatker/?reqUnitKerjaId='+rec.SATUAN_KERJA_ID;
                                    $('#reqTipe').combotree('reload', url);
                                    $('#reqTipe').combotree('setValue', '');
                                }" value="<?=$reqSkCabangId?>" />
                            </td>
                        </tr>
                    	<tr>
                            <td>Satuan Kerja</td>
                            <td>:</td>
                            <td>
                                <input type="text" name="reqSatuanKerjaId" class="easyui-combotree"  id="reqTipe"
                                data-options="width:'500'
                                , valueField:'SATUAN_KERJA_ID'
                                , textField:'JABATAN'
                                , url:'web/satuan_kerja_json/combotreeallcabangsatker/?reqUnitKerjaId=<?=$this->CABANG_ID?>'
                                , onSelect: function(rec){
                                    $('#reqNama').val(rec.NAMA_PEGAWAI);
                                    $('#reqPegawaiId').val(rec.NIP);
                                    $('#reqSatuanKerja').val(rec.JABATAN);
                                }" value="<?=$reqSatuanKerjaId?>"   />
                                <input type="hidden" name="reqSatuanKerja" id="reqSatuanKerja" value="<?=$reqSatuanKerja?>">
                            </td>
                        </tr>
                        <tr>
                            <td>NIP Pejabat</td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqPegawaiId" class="easyui-validatebox textbox form-control" name="reqPegawaiId"  value="<?=$reqPegawaiId ?>" readonly style="width:20%" />
                            </td>
                        </tr>
                        <tr>           
                            <td>Nama Pejabat</td>
                            <td>:</td>
                            <td>
                             	<input type="text" id="reqNama" class="easyui-validatebox textbox form-control" name="reqNama"  value="<?=$reqNama ?>" readonly style="width:50%" />  
                            </td>
                        </tr>
                        <tr>
                            <td>Jenis Tugas</td>
                            <td>:</td>
                            <td>
                                <select name="reqAnTambahan" id="reqAnTambahan">
                                    <option value="" <? if($reqAnTambahan == "") echo "selected"?>></option>
                                    <option value="plt" <? if($reqAnTambahan == "plt") echo "selected"?>>Plt.</option>
                                    <option value="plh" <? if($reqAnTambahan == "plh") echo "selected"?>>Plh.</option>
                                </select>
                            </td>
                        </tr>
                        <tr>           
                            <td>Nip Pengganti</td>
                            <td>:</td>
                            <td>
                                <div style="width: 20%; float: left;">
                                    <input type="text" id="reqPegawaiIdPengganti" class="easyui-validatebox textbox form-control" required name="reqPegawaiIdPengganti"  value="<?=$reqPegawaiIdPengganti ?>" readonly data-options="required:true" style="width:100%" />
                                </div>
                                <div class="col-md-1">
                                    <?
                                    if($reqMode == "insert")
                                    {
                                    ?>
                                    <a id="btnAdd" onClick="openAdd('app/loadUrl/main/pegawai_pengganti_lookup')"><i class="fa fa-plus-square fa-lg" aria-hidden="true"></i> </a>
                                    <?
                                    }
                                    ?>
                                </div>
                            </td>
                        </tr>
                        <tr>           
                            <td>Nama Pengganti</td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqNamaPengganti" class="easyui-validatebox textbox form-control" required name="reqNamaPengganti"  value="<?=$reqNamaPengganti ?>" data-options="required:true" style="width:50%" />
                            </td>
                        </tr>
                        <tr>           
                            <td>Tanggal Mulai</td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqTanggalMulai" class="easyui-datebox" required name="reqTanggalMulai"  value="<?=$reqTanggalMulai ?>" data-options="required:true" style="width:100" panelWidth:"180"/>
                            </td>
                        </tr>
                        <tr>           
                            <td>Tanggal Selesai</td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqTanggalSelesai" class="easyui-datebox" required name="reqTanggalSelesai"  value="<?=$reqTanggalSelesai ?>" data-options="required:true" style="width:100" panelWidth:"180"/>
                            </td>
                        </tr>
                        <tr>           
                            <td>Status Aktif</td>
                            <td>:</td>
                            <td>
                                <select name="reqStatusAktif" id="reqStatusAktif">
                                    <option value="" <? if($reqStatusAktif == "") echo "selected"?>>Aktif</option>
                                    <option value="1" <? if($reqStatusAktif == "1") echo "selected"?>>Tidak</option>
                                </select>
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
        url:'web/pejabat_pengganti_json/add',
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
            // console.log(data);return false;
            arrData= data.split("-");
            infocheck= arrData[0];
            infodata= arrData[1];

            if(infocheck == "error")
            {
                $.messager.alert('Info', infodata, 'info');
            }
            else
            {
                $.messager.alertTopLink('Info', infodata, 'info', "main/index/jabatan_sementara");
            }
        }
    });
}
function clearForm(){
    $('#ff').form('clear');
}

function tambahPegawai(id, nama)
{
    $("#reqPegawaiId").val(id); 
    $("#reqNama").val(nama);
}

function tambahPegawaiPengganti(id, nama)
{
    $("#reqPegawaiIdPengganti").val(id);
    $("#reqNamaPengganti").val(nama);
}
            
</script>