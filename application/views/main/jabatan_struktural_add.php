
<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model("SatuanKerja");
$pejabat_pengganti = new SatuanKerja();

$reqId = $this->input->get("reqId");
$reqParentId = $this->input->get("reqParentId");
$reqMode= $this->input->get("reqMode");

if($reqMode == "insert")
{
    $reqMode = "insert";
    $statement= " AND A.SATUAN_KERJA_ID = '".$reqId."'";
    $pejabat_pengganti->selectByParams(array(),-1,-1,$statement,$order);
    $pejabat_pengganti->firstRow();
    $reqKodeSurat= $pejabat_pengganti->getField("KODE_SURAT");
    $reqParentId= $pejabat_pengganti->getField("SATUAN_KERJA_ID_PARENT");
    if(!empty($pejabat_pengganti->getField("JABATAN")))
        $reqParentUnitKerja= "Unit Kerja : ".$pejabat_pengganti->getField("NAMA")."; Jabatan : ".$pejabat_pengganti->getField("JABATAN");
    else
        $reqParentUnitKerja= $pejabat_pengganti->getField("NAMA");
}
else
{
    $statement = " AND A.SATUAN_KERJA_ID = '".$reqId."'";
    $reqMode = "ubah";
    $order="";
    $pejabat_pengganti->selectByParams(array(),-1,-1,$statement,$order);
    // echo $pejabat_pengganti->query; exit;
    $pejabat_pengganti->firstRow();
    $reqId= $pejabat_pengganti->getField("SATUAN_KERJA_ID");
    $reqUnitKerja= $pejabat_pengganti->getField("NAMA");
    $reqJabatan= $pejabat_pengganti->getField("JABATAN");
    $reqKelompokJabatan= $pejabat_pengganti->getField("KELOMPOK_JABATAN");
    $reqKodeSurat= $pejabat_pengganti->getField("KODE_SURAT");
    $reqNipPegawai= $pejabat_pengganti->getField("NIP");
    $reqNamaPegawai= $pejabat_pengganti->getField("NAMA_PEGAWAI");
    $reqUserBantu= $pejabat_pengganti->getField("USER_BANTU");
    $reqUserBantuNama= $pejabat_pengganti->getField("USER_BANTU_NAMA");
    $reqStatusAktif= $pejabat_pengganti->getField("STATUS_AKTIF");
    $reqUnitKerjaId= $pejabat_pengganti->getField("UNIT_KERJA_NAMA");

    $reqApprovalSttpd= $pejabat_pengganti->getField("APPROVAL_STTPD");
    $reqApprovalSttpdNama= $pejabat_pengganti->getField("APPROVAL_STTPD_NAMA");
}
// echo $reqLokasi; exit;
$arrkelompok= infokelompok();

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
	<div id="judul-popup">Kelola Jabatan Struktural</div>
	<div id="konten">
    	<div id="popup-tabel2">

            <form id="ff" method="post" novalidate enctype="multipart/form-data">
                <table class="table">
                    <thead>
                        <?
                        if(!empty($reqParentUnitKerja))
                        {
                        ?>
                        <tr>
                            <td>Parent</td>
                            <td>:</td>
                            <td>
                                <?=$reqParentUnitKerja?>
                                <input type="hidden" name="reqParentId" value="<?=$reqParentId?>" />
                            </td>
                        </tr>
                        <?
                        }
                        ?>
                        <tr>
                            <td>Unit Kerja</td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqUnitKerja" class="easyui-validatebox textbox form-control" name="reqUnitKerja"  value="<?=$reqUnitKerja ?>"  style="width:50%"/>
                            </td>
                        </tr>
                        <tr>           
                            <td>Jabatan</td>
                            <td>:</td>
                            <td>
                             	<input type="text" id="reqJabatan" class="easyui-validatebox textbox form-control" name="reqJabatan"  value="<?=$reqJabatan ?>"  style="width:50%" />  
                            </td>
                        </tr>
                        <tr>           
                            <td>Kelompok</td>
                            <td>:</td>
                            <td>
                                <select name="reqKelompokJabatan" id="reqKelompokJabatan"  class="easyui-validatebox" <?=$tempDisabled?>>
                                    <option value="" <? if($reqKelompokJabatan == "") echo "selected";?>>Belum Dipilih</option>
                                    <?
                                    for($x=0; $x < count($arrkelompok); $x++)
                                    {
                                      $infoid= $arrkelompok[$x]["id"];
                                      $infotext= $arrkelompok[$x]["nama"];
                                    ?>
                                    <option value="<?=$infoid?>" <? if($reqKelompokJabatan == $infoid) echo "selected";?>><?=$infotext?></option>
                                    <?
                                    }
                                    ?>
                                </select>
                    
                            </td>
                        </tr>
                        <?
                        if($reqMode !== "insert")
                        {
                        ?>
                        <tr>           
                            <td>Kode Jabatan</td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqKodeSurat" class="easyui-validatebox textbox form-control"  name="reqKodeSurat"  value="<?=$reqKodeSurat ?>" style="width:20%" />
                            </td>
                        </tr>
                        <tr>           
                            <td>Nama Pejabat</td>
                            <td>:</td>
                            <td>
                                 <div style="width: 50%; float: left;">
                                    <input type="hidden" name="reqNipPegawai" id="reqNipPegawai" value="<?=$reqNipPegawai?>" />
                                    <input type="text" id="reqNamaPegawai" class="easyui-validatebox textbox form-control" reXquired readonly name="reqNamaPegawai"  value="<?=$reqNamaPegawai ?>"  style="width:100%" />
                                </div>
                                <!-- <div class="col-md-1">
                                    <a id="btnAdd" onClick="openAdd('app/loadUrl/main/pegawai_pengganti_lookup')"><i class="fa fa-plus-square fa-lg" aria-hidden="true"></i> </a>
                                </div> -->
                            </td>
                        </tr>
                        <tr>           
                            <td>Status</td>
                            <td>:</td>
                            <td>
                                <select id="reqStatusAktif" name="reqStatusAktif">
                                    <option value="1" <? if($reqStatusAktif == "1") echo "selected";?>>Aktif</option>
                                    <option value="0" <? if($reqStatusAktif == "0") echo "selected";?>>Tidak Aktif</option>
                                </select>
                            </td>
                        </tr>
                        <tr>           
                            <td>Lokasi</td>
                            <td>:</td>
                            <td>
                               <input type="text" name="reqUnitKerjaId" class="easyui-combobox" id="reqUnitKerjaId" data-options="width:'400',editable:false, valueField:'id',textField:'text'"  value="<?=$reqUnitKerjaId ?>"  required />
                            </td>
                        </tr>
                        <tr>
                            <td>Sekretaris</td>
                            <td>:</td>
                            <td>
                            	
                                 <div style="width: 50%; float: left;">
                                    <label id="reqUserBantuNama"><?=$reqUserBantuNama?></label>
                                    <input type="hidden" id="reqUserBantu" name="reqUserBantu" value="<?=$reqUserBantu?>" />
                                </div>
                                <div class="col-md-2">
                                	<?
									// if($reqUserBantu == ""){
									?>
									<a id="btnAdd" onClick="openAdd('app/loadUrl/main/pegawai_user_bantu_lookup?reqParentId=<?=$reqParentId?>')"><i class="fa fa-plus-square fa-lg" aria-hidden="true"></i> </a>
									<?
									// } 
                                    // else {
									?>
									<a id="btnReset" onClick="resetValue()"><i class="fa fa-trash fa-lg text-danger" aria-hidden="true"></i> </a>
									<? 
                                    // } 
                                    ?>
                                    
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Approval Sttpd</td>
                            <td>:</td>
                            <td>
                                 <div style="width: 50%; float: left;">
                                    <label id="reqApprovalSttpdNama"><?=$reqApprovalSttpdNama?></label>
                                    <input type="hidden" id="reqApprovalSttpd" name="reqApprovalSttpd" value="<?=$reqApprovalSttpd?>" />
                                </div>
                                <div class="col-md-2">
                                    <a id="btnAdd" onClick="openAdd('app/loadUrl/main/pegawai_approval_sttpd_lookup?reqParentId=<?=$reqParentId?>')"><i class="fa fa-plus-square fa-lg" aria-hidden="true"></i> </a>
                                    <a id="btnReset" onClick="resetApprovalValue()"><i class="fa fa-trash fa-lg text-danger" aria-hidden="true"></i> </a>
                                </div>
                            </td>
                        </tr>
                        <?
                        }
                        ?>
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

function resetApprovalValue()
{
    var check = $('#reqApprovalSttpd').val();
    // console.log(check);
    if (check !== "")
    {
        $.messager.defaults.ok = 'Ya';
        $.messager.defaults.cancel = 'Tidak';
        reqmode= "user_bantu";
        infoMode= "Apakah anda yakin mereset data terpilih"
        $.messager.confirm('Konfirmasi', infoMode+" ?",function(r){
           if (r)
           {
              $("#reqApprovalSttpd").val('');
              $("#reqApprovalSttpdNama").text('');
           }
      });
    }
    else
    {
        $.messager.alert('Info', "Data yang dipilih sudah direset");
    }  
}

function setapprovalsttpd(id, nama)
{
    $("#reqApprovalSttpd").val(id);
    $("#reqApprovalSttpdNama").text(nama);
}

function resetValue()
{
    var check = $('#reqUserBantu').val();
    // console.log(check);
    if (check !== "")
    {
        $.messager.defaults.ok = 'Ya';
        $.messager.defaults.cancel = 'Tidak';
        reqmode= "user_bantu";
        infoMode= "Apakah anda yakin mereset data terpilih"
        $.messager.confirm('Konfirmasi', infoMode+" ?",function(r){
           if (r)
           {
              $("#reqUserBantu").val('');
              $("#reqUserBantuNama").text('');
           }
      });
    }
    else
    {
        $.messager.alert('Info', "Data yang dipilih sudah direset");
        // $("#reqUserBantu").val('');
        // $("#reqUserBantuNama").text('');
    }  
}

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

    $('#reqUnitKerjaId').combobox({
        url:'web/satuan_kerja_json/combo_cabang',
        onSelect: function(param) {
            var urlApp = "web/satuan_kerja_json/treetable_master/?reqUnitKerjaId="+param.id+"&reqPencarian="+$("#reqPencarian").val();
            $('#treeSatker').treegrid({
                url: urlApp
                , pageSize: 1
            });
        }
    });
    
});

function tambahPegawaiPengganti(id, nama)
{
    $("#reqNipPegawai").val(id); 
    $("#reqNamaPegawai").val(nama);
}

function setsekretaris(id, nama)
{
    $("#reqUserBantu").val(id);
    $("#reqUserBantuNama").text(nama);
}

function submitForm(){
    
    $('#ff').form('submit',{
        url:'web/jabatan_struktural_add_json/add',
        onSubmit:function(){
            
            return $(this).form('enableValidation').form('validate');
        },
        success:function(data){
            // console.log(data);return false;
            arrData= data.split("-");

            $.messager.alertLink('Info', arrData[1], 'info', "app/loadUrl/admin/jenis_naskah");  
        }
    });
}
function clearForm(){
    $('#ff').form('clear');
}
            
</script>