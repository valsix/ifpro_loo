
<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$reqUnitKerjaId = $this->CABANG_ID; 
$reqUnitKerjaNama = $this->CABANG; 

$this->load->model("Pegawai");
$pegawai = new Pegawai();

$reqId = $this->input->get("reqId");

$readonly= "";
if($reqId == "")
{
    $reqMode = "insert";
    // $reqJabatan= "Sekretaris Direktur Teknik Dan Fasilitas";
}
else
{
    $readonly= 'readonly';
	$reqMode = "ubah";
	$pegawai->selectByParamsMonitoring(array("A.PEGAWAI_ID" => $reqId));
	$pegawai->firstRow();
    // echo $pegawai->query;exit;
    
	$reqId= $pegawai->getField("PEGAWAI_ID");
    $reqNip= $pegawai->getField("NIP");
	$reqNama= $pegawai->getField("NAMA");
	$reqJabatan= $pegawai->getField("JABATAN");
	$reqUnitKerjaId= $pegawai->getField("SATUAN_KERJA_ID");
	$reqUnitKerjaNama= $pegawai->getField("SATUAN_KERJA");
	$reqIdDirektorat= $pegawai->getField("DEPARTEMEN_ID");
	$reqNamaDirektorat= $pegawai->getField("DEPARTEMEN");
	$reqJenisPegawai= $pegawai->getField("JENIS_PEGAWAI");
    $reqEmail = $pegawai->getField("EMAIL");
    $reqAlamat = $pegawai->getField("ALAMAT");
    $reqTempatLahir = $pegawai->getField("TEMPAT_LAHIR");
    $reqBpjs = $pegawai->getField("BPJS");
    $reqNoRekening = $pegawai->getField("NOMOR_REKENING");
    $reqKtp = $pegawai->getField("KTP");
    $reqJenisKelamin = $pegawai->getField("JENIS_KELAMIN");
    $reqNpwp = $pegawai->getField("NPWP");
    $reqPendidikan = $pegawai->getField("LAST_PENDIDIKAN_ID");
    $reqNamaRekening = $pegawai->getField("BANK_ID");
    $reqAgama = $pegawai->getField("AGAMA_ID");
    $reqTanggalLahir = dateToPageCheck($pegawai->getField("TANGGAL_LAHIR"));
    $reqTanggaLMasuk = dateToPageCheck($pegawai->getField("TANGGAL_MASUK"));
}
// echo $reqTanggalLahir;exit;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
<base href="<?=base_url();?>">

<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/gaya.css">

<link rel="stylesheet" type="text/css" href="<?=base_url()?>lib/easyui/themes/default/easyui.css">

<link href="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

<script type="text/javascript" src="<?=base_url()?>lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript" src="<?=base_url()?>lib/easyui/globalfunction.js"></script>
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
	<div id="judul-popup">Pegawai</div>
	<div id="konten">
    	<div id="popup-tabel2">

            <form id="ff" method="post" novalidate enctype="multipart/form-data">
                <table class="table">
                    <thead>
                    	<tr>
                            <td>NIP</td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqNip" class="easyui-validatebox textbox form-control" required name="reqNip"  value="<?=$reqNip ?>" data-options="required:true" style="width:150px" <?=$readonly?> />
                            </td>
                        </tr>
                        <tr>
                            <td>Nama</td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqNama" class="easyui-validatebox textbox form-control" required name="reqNama"  value="<?=$reqNama ?>" data-options="required:true" style="width:60%" />
                            </td>
                        </tr>
                        <!-- <tr>
                            <td>Jenis Kelamin</td>
                            <td>:</td>
                            <td>
                                <input class="easyui-combotree textbox form-control" id="reqJenisKelamin" name="reqJenisKelamin" value="<?=$reqJenisKelamin?>" data-options="width:'100',panelHeight:'400',panelWidth:'100',editable:false,valueField:'id',textField:'text',url:'combo_json/comboJekel'"  /> 
                            </td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td>:</td>
                            <td>
                                
                                <textarea id="reqAlamat" class="easyui-validatebox textbox form-control" required name="reqAlamat" style="width:60%"><?=$reqAlamat ?></textarea>                            
                            </td>
                        </tr>
                        <tr>
                            <td>Tempat Tanggal Lahir</td>
                            <td>:</td>
                            <td>
                                <div class="row">
                                    <div class="col-md-3">
                                        <input type="text" id="reqTempatLahir" class="easyui-validatebox textbox form-control" required name="reqTempatLahir"  value="<?=$reqTempatLahir ?>" data-options="required:true" style="width:100%" />
                                    </div>
                                    <div class="col-md-1" style="width: 10px;font-size: 20px;">
                                        /
                                    </div>
                                    <div class="col-md-3">
                                        <input type="date" id="reqTanggalLahir" class="easyui-validatebox textbox form-control" required name="reqTanggalLahir"  value="<?=$reqTanggalLahir ?>" data-options="required:true" style="width:100%; height:30px" />
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqEmail" class="easyui-validatebox textbox form-control" data-options="required:true" name="reqEmail" value="<?=$reqEmail ?>" style="width:60%" />
                            </td>
                        </tr>
                        <tr>
                            <td>Tanggal Masuk Kerja</td>
                            <td>:</td>
                            <td>
                                <input type="date" id="reqTanggaLMasuk" class="easyui-validatebox textbox form-control" required name="reqTanggaLMasuk"  value="<?=$reqTanggaLMasuk ?>" data-options="required:true" style="width:20%; height:30px" />
                            </td>
                        </tr>
                        <tr>
                            <td>No BPJS</td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqBpjs" class="easyui-validatebox textbox form-control" data-options="required:true" name="reqBpjs" value="<?=$reqBpjs ?>" style="width:20%" />
                            </td>
                        </tr>
                        <tr>
                            <td>Rekening</td>
                            <td>:</td>
                            <td>
                                 <div class="row">
                                    <div class="col-md-3">
                                        <input class="easyui-combotree textbox form-control" id="reqNamaRekening" name="reqNamaRekening" value="<?=$reqNamaRekening?>" data-options="width:'200',panelHeight:'400',panelWidth:'100',editable:false,valueField:'id',textField:'text',url:'combo_json/comboBank'"  /> 
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" id="reqNoRekening" class="easyui-validatebox textbox form-control" required name="reqNoRekening"  value="<?=$reqNoRekening ?>" data-options="required:true" style="width:100%" />    
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Pendidikan</td>
                            <td>:</td>
                            <td>
                                <input class="easyui-combotree textbox form-control" id="reqPendidikan" name="reqPendidikan" value="<?=$reqPendidikan?>" data-options="width:'200',panelHeight:'400',panelWidth:'100',editable:false,valueField:'id',textField:'text',url:'combo_json/comboPendidikan'"  />     
                            </td>
                        </tr>
                        <tr>
                            <td>Agama</td>
                            <td>:</td>
                            <td>
                                <input class="easyui-combotree textbox form-control" id="reqAgama" name="reqAgama" value="<?=$reqAgama?>" data-options="width:'200',panelHeight:'400',panelWidth:'100',editable:false,valueField:'id',textField:'text',url:'combo_json/comboAgama'"  />     
                            </td>
                        </tr>
                        <tr>
                            <td>NPWP</td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqNpwp" class="easyui-validatebox textbox form-control" required name="reqNpwp"  value="<?=$reqNpwp ?>" data-options="required:true" style="width:60%" />
                            </td>
                        </tr>
                        <tr>
                            <td>KTP</td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqKtp" class="easyui-validatebox textbox form-control" required name="reqKtp"  value="<?=$reqKtp ?>" data-options="required:true" style="width:60%" />
                            </td>
                        </tr> -->
                        <!-- <tr>
                            <td>Jabatan</td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqJabatan" class="easyui-validatebox textbox form-control" required name="reqJabatan"  value="<?=$reqJabatan ?>" data-options="required:true" style="width:60%" <?=$readonly?> />
                            </td>
                        </tr> -->
                        <tr>
                            <td>Unit Kerja</td>
                            <td>:</td>
                            <td>
                                <?
                                if ($reqUnitKerjaId == 'PST' && empty($readonly)) 
                                {
                                ?>
                                    <input name="reqUnitKerjaId" id="reqUnitKerjaId" class="easyui-combotree textbox form-control" id="reqDepartemen" value="<?=$reqUnitKerjaId?>" data-options="width:'400',panelHeight:'400',panelWidth:'500',editable:false,valueField:'id',textField:'text',url:'web/satuan_kerja_json/combo_cabang'"  />
                                <?
                                }
                                else
                                {
                                ?>
                                    <input type="hidden" id="reqUnitKerjaId" class="easyui-validatebox textbox form-control" required name="reqUnitKerjaId"  value="<?=$reqUnitKerjaId ?>" data-options="required:true" style="width:60%" />
                                    <input type="text" id="reqUnitKerjaNama" class="easyui-validatebox textbox form-control" required name="reqUnitKerjaNama"  value="<?=$reqUnitKerjaNama ?>" data-options="required:true" style="width:60%" readonly />
                                <?
                                }
                                ?>
                            </td>
                        </tr>
                        <!-- <tr>           
                            <td>Direktorat</td>
                            <td>:</td>
                            <td>
                                 <div style="width: 60%; float: left;">
                                    <input type="hidden" name="reqIdDirektorat" id="reqIdDirektorat" value="<?=$reqIdDirektorat?>" />
                                    <input type="text" id="reqNamaDirektorat" class="easyui-validatebox textbox form-control" required  name="reqNamaDirektorat"  value="<?=$reqNamaDirektorat ?>"  style="width:100%" />
                                </div>
                                <?
                                if(empty($readonly))
                                {
                                ?>
                                <div class="col-md-1">
                                    <a id="btnAdd" onClick="openDirektorat()"><i class="fa fa-plus-square fa-lg" aria-hidden="true"></i> </a>
                                </div>
                                <?
                                }
                                ?>
                            </td>
                        </tr> -->
                        <tr>
                            <td>Jenis</td>
                            <td>:</td>
                            <td>
                                <input class="easyui-combotree textbox form-control" id="reqJenisPegawai" name="reqJenisPegawai" value="<?=$reqJenisPegawai?>"
                                data-options="width:'100',panelHeight:'400',panelWidth:'100',editable:false,valueField:'id',textField:'text',url:'web/pegawai_json/combojenis?reqMode=xxx'"  />
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
function addDirektorat(id, nama)
{
    $("#reqIdDirektorat").val(id); 
    $("#reqNamaDirektorat").val(nama); 
}

function adddetil(param)
{
    // console.log(param);
    // $("#reqUnitKerjaId").val(param.SATUAN_KERJA_ID_PARENT);
    // $("#reqUnitKerjaNama").val(param.UNIT_KERJA_NAMA);
    // $("#reqUnitKerjaNama").val(param.infonamacabang);
    $('#reqUnitKerjaId').combotree('setValue', param.SATUAN_KERJA_ID_PARENT);
    $("#reqIdDirektorat").val(param.id);
    $("#reqNamaDirektorat").val(param.NAMA);
    $("#reqJabatan").val(param.JABATAN);
}

function submitForm(){
    
    $('#ff').form('submit',{
        url:'web/pegawai_json/add',
        onSubmit:function(){
            return $(this).form('enableValidation').form('validate');
        },
        success:function(data){
            // console.log(data);return false;
            var data = data.split("-");
            if (data[0]=='xxx') 
            {
                $("#reqNip").val("");
                $.messager.alert('Warning', data[1], 'warning');
            } 
            else 
            {
                $.messager.alertTopLink('Info', data[1], 'info', "main/index/pegawai");
            }
        }
    });
}

function clearForm(){
    $('#ff').form('clear');
}

function setundefined(val)
{
    if(typeof val == "undefined")
        val= "";
    return val;
}

function openDirektorat()
{
    <?
    if ($reqUnitKerjaId == 'PST')
    {
    ?>
        reqUnitKerjaId= setundefined($("#reqUnitKerjaId").combobox("getValue"));
    <?
    }
    else
    {
    ?>
        reqUnitKerjaId= $("#reqUnitKerjaId").val();
    <?
    }
    ?>
    
    openAdd('app/loadUrl/main/pegawai_add_satuan_kerja_lookup?reqUnitKerjaId='+reqUnitKerjaId);
}

</script>