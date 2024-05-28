
<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model("PejabatPengganti");
$pejabat_pengganti = new PejabatPengganti();

$reqId = $this->input->get("reqId");
$reqMode = "insert";
$reqPegawaiCabangId= $this->CABANG_ID;

/*if($reqId == ""){
    $reqMode = "insert";
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
    $reqPegawaiCabangId= $pejabat_pengganti->getField("SK_CABANG_ID");
}*/

$arrtipe= datatipemutasi();
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
	<div id="judul-popup">Mutasi Jabatan</div>
	<div id="konten">
    	<div id="popup-tabel2">

            <form id="ff" method="post" novalidate enctype="multipart/form-data">
                <table class="table">
                    <thead>
                        <tr>
                            <td>Unit Kerja</td>
                            <td>:</td>
                            <td>
                                <input type="text" class="easyui-combotree" name="reqPegawaiCabangId" id="reqPegawaiCabangId"
                                data-options="width:'500'
                                , valueField:'SATUAN_KERJA_ID'
                                , textField:'SATUAN_KERJA'
                                , url:'web/satuan_kerja_json/combotreeallcabang'
                                , onSelect: function(rec){
                                    $('#reqUnitKerjaId, #reqUnitKerjaNama, #reqPegawaiNip, #reqPegawaiNama, #reqJabatanNama').val('');
                                }
                                " value="<?=$reqPegawaiCabangId?>" />
                            </td>
                        </tr>
                        <tr>           
                            <td>No SK</td>
                            <td>:</td>
                            <td>
                                <div>
                                    <input type="text" id="reqNoSk" class="easyui-validatebox textbox form-control" required name="reqNoSk"  value="<?=$reqNoSk?>" data-options="required:true" style="width:40%" />
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Tanggal Mutasi</td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqTanggalMutasi" class="easyui-datebox textbox form-control" name="reqTanggalMutasi" value="<?=$reqTanggalMutasi?>" data-options="required:true" style="width:110px" />
                            </td>
                        </tr>
                        <tr>           
                            <td>Nip</td>
                            <td>:</td>
                            <td>
                                <div style="width: 20%; float: left;">
                                    <input type="hidden" id="reqUnitKerjaId" name="reqUnitKerjaId" />
                                    <input type="hidden" id="reqUnitKerjaNama" name="reqUnitKerjaNama" />
                                    <input type="text" id="reqPegawaiNip" class="easyui-validatebox textbox form-control" required name="reqPegawaiNip" value="<?=$reqPegawaiNip?>" readonly data-options="required:true" style="width:100%" />
                                </div>
                                <div class="col-md-1">
                                    <?
                                    if($reqMode == "insert")
                                    {
                                    ?>
                                    <a id="btnAdd" onClick="lookupPegawai('')"><i class="fa fa-plus-square fa-lg" aria-hidden="true"></i> </a>
                                    <?
                                    }
                                    ?>
                                </div>
                            </td>
                        </tr>
                        <tr>           
                            <td>Nama</td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqPegawaiNama" class="easyui-validatebox textbox form-control" required name="reqPegawaiNama" value="<?=$reqPegawaiNama?>" readonly data-options="required:true" style="width:50%" />
                            </td>
                        </tr>
                      <!--   <tr>
                            <td>Jabatan</td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqJabatanNama" class="easyui-validatebox textbox form-control" required name="reqJabatanNama" value="<?=$reqJabatanNama?>" readonly data-options="required:true" style="width:50%" />
                            </td>
                        </tr> -->
                        <tr>
                          <td colspan="3"><strong><i class="fa fa-external-link-square" aria-hidden="true"></i> MUTASI KE :</strong></td>
                        </tr>
                        <tr>
                            <td>Jenis</td>
                            <td>:</td>
                            <td>
                                <select id="reqTipe" name="reqTipe">
                                    <?
                                    for($t=0; $t < count($arrtipe); $t++)
                                    {
                                        $infotipeval= $arrtipe[$t]["val"];
                                        $infotipelabel= $arrtipe[$t]["label"];
                                    ?>
                                        <option value="<?=$infotipeval?>"><?=$infotipelabel?></option>
                                    <?
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>           
                            <td>Jabatan Baru</td>
                            <td>:</td>
                            <td>
                                <div style="width: 50%; float: left;">
                                    <input type="hidden" id="reqKelompokJabatan" />
                                    <input type="hidden" id="reqAksiPejabatPengganti" name="reqAksiPejabatPengganti" />
                                    <input type="hidden" id="reqPegawaiJabatanNipBaru" name="reqPegawaiJabatanNipBaru" />
                                    <input type="hidden" id="reqPegawaiJabatanNamaBaru" name="reqPegawaiJabatanNamaBaru" />
                                    <input type="hidden" id="reqPegawaiJabatanUnitKerjaId" name="reqPegawaiJabatanUnitKerjaId" />
                                    <input type="hidden" id="reqPegawaiJabatanUnitKerjaNama" name="reqPegawaiJabatanUnitKerjaNama" />
                                    <input type="text" id="reqJabatanBaru" class="easyui-validatebox textbox form-control" required name="reqJabatanBaru" value="<?=$reqJabatanBaru ?>" readonly data-options="required:true" style="width:100%" />
                                </div>
                                <div class="col-md-1" id="infojabatanbaru">
                                    <?
                                    if($reqMode == "insert")
                                    {
                                    ?>
                                    <a id="btnAdd" onClick="lookupPegawai('jabatan_baru')"><i class="fa fa-plus-square fa-lg" aria-hidden="true"></i> </a>
                                    <?
                                    }
                                    ?>
                                </div>
                            </td>
                        </tr>
                        <tr id="infojabatanentri" style="display: none;">
                            <td>Pindah Ke Unit Kerja</td>
                            <td></td>
                            <td>
                                <div>
                                    <div style="width: 50%; float: left;" id="infojabatanidtujuan">
                                        <input type="text" id="reqPegawaiJabatanUnitKerjaEntriIdTujuan" name="reqPegawaiJabatanUnitKerjaEntriIdTujuan" class="easyui-validatebox textbox form-control" value="<?=$reqPegawaiJabatanUnitKerjaEntriIdTujuan?>" readonly style="width:100%" />
                                        <input type="hidden" id="reqPegawaiJabatanUnitKerjaEntriId" name="reqPegawaiJabatanUnitKerjaEntriId" value="<?=$reqPegawaiJabatanUnitKerjaEntriId?>" />
                                    </div>
                                    <div class="col-md-1" id="infojabatanidtujuanbutton">
                                        <a id="btnAdd" onClick="lookupPegawai('jabatan_baru_cari')"><i class="fa fa-plus-square fa-lg" aria-hidden="true"></i> </a>
                                        <br/><br/>
                                    </div>
                                    <div id="infojabatantujuan">
                                        <input type="text" id="reqPegawaiJabatanUnitKerjaEntri" class="easyui-validatebox textbox form-control" name="reqPegawaiJabatanUnitKerjaEntri" value="<?=$reqPegawaiJabatanUnitKerjaEntri?>" style="width:100%" />
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </thead>
                </table>

                <input type="hidden" name="reqId" value="<?=$reqId?>" />
                <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
                
                <div style="text-align:left;padding:5px">
                    <a href="javascript:void(0)" class="btn btn-primary" onclick="submitForm()">Mutasi</a>
                    <!-- <a href="javascript:void(0)" class="btn btn-warning" onclick="clearForm()">Clear</a> -->
                </div>
            </form>

        </div>
    </div>
</body>
</html>


<script>

$(document).ready(function() {
    //$('#reqKdLevel').combobox('setValues', ['LEVEL3','LEVEL4']);

    $("#reqTipe").change(function() { 
        var reqTipe= "";
        reqTipe= $("#reqTipe").val();

        $("#infojabatanbaru").show();
        $("#reqPegawaiJabatanUnitKerjaId, #reqPegawaiJabatanUnitKerjaNama, #reqPegawaiJabatanNipBaru, #reqPegawaiJabatanNamaBaru, #reqJabatanBaru, #reqKelompokJabatan, #reqPegawaiJabatanUnitKerjaEntri, #reqPegawaiJabatanUnitKerjaEntriIdTujuan, #reqPegawaiJabatanUnitKerjaEntriId").val("");

        if(reqTipe == "3")
        {
            $("#reqJabatanBaru").val("Pensiun");
            $("#infojabatanentri, #infojabatantujuan, #infojabatanbaru").hide();
            // $("#infojabatanentri, #infojabatantujuan, #infojabatanidtujuan, #infojabatanidtujuanbutton").hide();
        }

    });
});

function lookupPegawai(reqMode) {
    var unitkerja= $("#reqPegawaiCabangId").combotree("getValue");
    if (unitkerja=='') 
    {
        alert("Pilih Unit Kerja terlebih dahulu");
    } 
    else 
    {
        if(reqMode == "")
            openAdd('app/loadUrl/main/mutasi_pejabat_pegawai_pengganti_lookup/?reqSatuanKerjaId='+unitkerja);
        else
            openAdd('app/loadUrl/main/mutasi_pejabat_jabatan_baru_lookup?reqUnitKerjaId='+unitkerja+'&reqTipe='+$("#reqTipe").val()+'&reqMode='+reqMode);
    }
}

function submitForm(){
    validasi= $("#ff").form('enableValidation').form('validate');

    if(validasi)
    {
        reqKelompokJabatan= $("#reqKelompokJabatan").val();
        // if(reqKelompokJabatan == "")
        reqTipe= $("#reqTipe").val();
        if(reqTipe == "2")
        {
            // if(kelompokjabatan == ""){}
            // else
            // {
                reqPegawaiJabatanUnitKerjaEntri= $("#reqPegawaiJabatanUnitKerjaEntri").val();
                reqPegawaiJabatanUnitKerjaEntriId= $("#reqPegawaiJabatanUnitKerjaEntriId").val();
                infojabatantujuan= $('#infojabatantujuan').is(':visible');
                // alert(infojabatantujuan+"-"+reqPegawaiJabatanUnitKerjaEntri);
                // if(reqPegawaiJabatanUnitKerjaEntri == "" && reqAksiPejabatPengganti == "")
                if((reqPegawaiJabatanUnitKerjaEntri == "" || (reqPegawaiJabatanUnitKerjaEntriId == "" && reqKelompokJabatan !== "") ) && infojabatantujuan == true)
                {
                    $.messager.alert('Info', "Isi terlebih dahulu pindah ke unit kerja", 'info');
                    return false;
                }

                // $.messager.alert('Info', "xxxx", 'info');
                // return false;
            // }
        }

        pesan= "Apakah anda yakin mutasi pegawai?";
        $.messager.confirm('Konfirmasi', pesan, function(r) {
            if (r) {
                $('#ff').form('submit',{
                    url:'web/mutasi_pejabat_json/add',
                    onSubmit:function(){
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
                            $.messager.alertTopLink('Info', infodata, 'info', "main/index/mutasi_pejabat");
                        }
                    }
                });
            }
        });
    }
}
function clearForm(){
    $('#ff').form('clear');
}

function tambahPegawai(id, nama)
{
    $("#reqPegawaiId").val(id); 
    $("#reqNama").val(nama);
}

function tambahPegawaiPengganti(satuankerjaid, satuankerjanama, nip, pegawai, jabatan)
{
    $("#reqUnitKerjaId").val(satuankerjaid);
    $("#reqUnitKerjaNama").val(satuankerjanama);
    $("#reqPegawaiNip").val(nip);
    $("#reqPegawaiNama").val(pegawai);
    $("#reqJabatanNama").val(jabatan);
}

$("#infojabatanentri, #infojabatantujuan, #infojabatanidtujuan, #infojabatanidtujuanbutton").hide();
function jabatanBaru(satuankerjaid, satuankerjanama, nip, pegawai, jabatan, kelompokjabatan, aksipejabatpengganti)
{
    $("#reqPegawaiJabatanUnitKerjaEntri").val("");
    $("#reqPegawaiJabatanUnitKerjaId").val(satuankerjaid);
    $("#reqPegawaiJabatanUnitKerjaNama").val(satuankerjanama);
    $("#reqPegawaiJabatanNipBaru").val(nip);
    $("#reqPegawaiJabatanNamaBaru").val(pegawai);
    $("#reqJabatanBaru").val(jabatan);

    if(kelompokjabatan == null)
        kelompokjabatan= "";

    $("#reqKelompokJabatan").val(kelompokjabatan);
    $("#reqAksiPejabatPengganti").val(aksipejabatpengganti);
    $("#infojabatanentri, #infojabatantujuan, #infojabatanidtujuan, #infojabatanidtujuanbutton").hide();

    reqTipe= $("#reqTipe").val();
    if(reqTipe == "2")
    {
        if(nip == "" || nip == null)
        {
            if(kelompokjabatan == "")
            {
                $("#reqPegawaiJabatanUnitKerjaEntriId").val(satuankerjaid);
                $("#reqPegawaiJabatanUnitKerjaEntriIdTujuan").val(satuankerjanama);
                $("#infojabatanentri, #infojabatantujuan").show();
            }
        }
        else
        {
            if(kelompokjabatan == "")
            {
                $("#reqPegawaiJabatanUnitKerjaEntriId").val(satuankerjaid);
                $("#reqPegawaiJabatanUnitKerjaEntriIdTujuan").val(satuankerjanama);
                $("#infojabatanentri, #infojabatantujuan").show();
            }
            else
            {
                $("#reqPegawaiJabatanUnitKerjaEntriId, #reqPegawaiJabatanUnitKerjaEntriIdTujuan").val("");
                $("#infojabatanentri, #infojabatantujuan, #infojabatanidtujuan, #infojabatanidtujuanbutton").show();
            }
        }
    }
}

function jabatanbuatbaru(satuankerjaid, satuankerjanama, nip, kelompokjabatan)
{
    $("#reqPegawaiJabatanUnitKerjaEntri").val("");
    $("#reqPegawaiJabatanUnitKerjaEntriId").val(satuankerjaid);
    $("#reqPegawaiJabatanUnitKerjaEntriIdTujuan").val(satuankerjanama);

    if(kelompokjabatan == null)
        kelompokjabatan= "";
    $("#reqKelompokJabatan").val(kelompokjabatan);

    if(nip == "")
    {
        $("#reqAksiPejabatPengganti").val("1");
        $("#infojabatantujuan").hide();
    }

    if(kelompokjabatan == "")
    {
        $("#infojabatantujuan").show();
    }
}
            
</script>