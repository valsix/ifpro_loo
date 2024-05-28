
<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");


// $this->load->model("LokasiLooDetil");
// $lokasi_loo_detil = new LokasiLooDetil();

// $reqId = $this->input->get("reqId");

// if($reqId == ""){
// $reqMode = "insert";
// }
// else
// {
//     $reqMode = "ubah";
//     $lokasi_loo_detil->selectByParams(array("A.LOKASI_LOO_DETIL_ID" => $reqId));
//     $lokasi_loo_detil->firstRow();
    
//     $reqId= $lokasi_loo_detil->getField("LOKASI_LOO_DETIL_ID");
//     $reqLokasiLooId= $lokasi_loo_detil->getField("LOKASI_LOO_ID");
//     $reqLantai= $lokasi_loo_detil->getField("LANTAI");
//     $reqKode= $lokasi_loo_detil->getField("KODE");
//     $reqNama= $lokasi_loo_detil->getField("NAMA");
//     $reqTipe= $lokasi_loo_detil->getField("TIPE");
//     $reqDeskripsi= $lokasi_loo_detil->getField("DESKRIPSI");
    
// }
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
    <div id="judul-popup">Kelola LOO</div>
    <div id="konten">
        <form id="ff" method="post" novalidate enctype="multipart/form-data">
            <div class="btn-atas clearfix">
                <a class="btn btn-danger btn-sm pull-right" id="buttonpdf" onClick="submitPreview()" style="cursor: pointer;"><i class="fa fa-file-pdf-o"></i> View as PDF</a>
                <button class="btn btn-default btn-sm pull-right" type="button" onClick="submitForm('DRAFT')"><i class="fa fa-file-o"></i> Draft</button>
            </div>

            <div id="popup-tabel2">

                    <table class="table">
                        <thead>
                            <tr>           
                                <td>Lokasi</td>
                                <td>:</td>
                                <td>
                                    <input type="text" name="reqLokasiLooId" class="easyui-combotree"  id="reqLokasiLooId"
                                           data-options="width:'350', valueField:'id', textField:'text', editable:false, url:'combo_json/comboLokasiLoo'" required value="<?=$reqLokasiLooId?>" />
                                </td>
                            </tr>
                            <tr>
                                <td>Customer</td>
                                <td>:</td>
                                <td>
                                    <input type="text" name="reqCustomerId" class="easyui-combobox"  id="reqCustomerId"
                                           data-options="width:'350', valueField:'id', textField:'text', editable:false, url:'combo_json/comboCustomer?cek=pemilik'" required value="<?=$reqCustomerId?>" />
                                </td>
                            </tr>
                            <tr>
                                <td>Produk</td>
                                <td>:</td>
                                <td>
                                    <input type="text" name="reqProdukId" class="easyui-combobox"  id="reqProdukId"
                                           data-options="width:'350', valueField:'id', textField:'text', editable:false, url:'combo_json/comboProduk'" required value="<?=$reqProdukId?>" />
                                </td>
                            </tr>
                            <!-- <tr>
                                <td>Lokasi Lantai</td>
                                <td>:</td>
                                <td>
                                    <input type="text" name="reqLokasiLooId" class="easyui-combobox"  id="reqLokasiLooId"
                                           data-options="width:'350', valueField:'id', textField:'text', editable:false, url:'combo_json/comboLokasiLooDetil'" required value="<?=$reqLokasiLooId?>" />
                                </td>
                            </tr> -->
                        </thead>
                    </table>

                    <fieldset>
                        <legend style="font-size: large;">
                            Luas Sewa
                        </legend>

                        <table class="table">
                            <tbody>
                                <tr>
                                    <td class="tdcolor">
                                        Indoor
                                        <a onClick="openLookup('I')"><i class="fa fa-plus-square fa-lg" aria-hidden="true"></i></a>
                                    </td>
                                    <td class="tdcolor">
                                        Outdoor
                                        <a onClick="openLookup('O')"><i class="fa fa-plus-square fa-lg" aria-hidden="true"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 50%">
                                        <table class="table">
                                            <tbody id="luassewaindoor">
                                                
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="2" style="text-align: right;">Total Luas Indoor</td>
                                                    <td style="width: 20%">
                                                        <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" name="reqTotalLuasIndoor" id="reqTotalLuasIndoor" style="width:85%; display: inline; text-align: right;" value="<?=$reqTotalLuasIndoor?>" /> <label class="labeltotal">m2</label>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </td>
                                    <td style="width: 50%">
                                        <table class="table">
                                            <tbody id="luassewaoutdoor">
                                                
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="2" style="text-align: right;">Total Luas Outdoor</td>
                                                    <td style="width: 20%">
                                                        <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" name="reqTotalLuasOutdoor" id="reqTotalLuasOutdoor" style="width:85%; display: inline; text-align: right;" value="<?=$reqTotalLuasOutdoor?>" /> <label class="labeltotal">m2</label>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="text-align: right;">
                                        Total Luas Sewa
                                        <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" name="reqTotalLuas" id="reqTotalLuas" style="width:15%; display: inline; text-align: right;" value="<?=$reqTotalLuas?>" /> <label class="labeltotal">m2</label>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </fieldset>

                    <fieldset>
                        <legend style="font-size: large;">
                            Tarif Sewa
                        </legend>
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td colspan="3" class="tdcolor" style="width: 50%">
                                        Indoor
                                    </td>
                                    <td colspan="3" class="tdcolor" style="width: 50%">
                                        Outdoor
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <table class="table">
                                            <tbody id="tarifinfosewaindoor">
                                                <tr>
                                                    <td class="tdcolor" style="width: 27%">Unit</td>
                                                    <td class="tdcolor" style="width: 10%">Discount</td>
                                                    <td class="tdcolor" style="width: 29%">Tarif (after discount)</td>
                                                    <td class="tdcolor" style="width: 29%">Harga Sewa</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td colspan="3">
                                        <table class="table">
                                            <tbody id="tarifinfosewaoutdoor">
                                                <tr>
                                                    <td class="tdcolor" style="width: 27%">Unit</td>
                                                    <td class="tdcolor" style="width: 10%">Discount</td>
                                                    <td class="tdcolor" style="width: 29%">Tarif (after discount)</td>
                                                    <td class="tdcolor" style="width: 29%">Harga Sewa</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <table class="table">
                                            <tbody id="tarifinfosewascindoor">
                                                <tr>
                                                    <td colspan="2" class="tdcolor" style="width: 35%">Service Charge</td>
                                                    <td colspan="2" class="tdcolor" style="width: 30%">Discount</td>
                                                    <td colspan="2" class="tdcolor" style="width: 35%">Tarif (after discount)</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td colspan="3">
                                        <table class="table">
                                            <tbody id="tarifinfosewascoutdoor">
                                                <tr>
                                                    <td colspan="2" class="tdcolor" style="width: 35%">Service Charge</td>
                                                    <td colspan="2" class="tdcolor" style="width: 30%">Discount</td>
                                                    <td colspan="2" class="tdcolor" style="width: 35%">Tarif (after discount)</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </fieldset>

                    <fieldset>
                        <legend style="font-size: large;">
                            Harga
                        </legend>

                        <table class="table">
                            <tbody>
                                <tr>
                                    <td style="text-align: right;">
                                        Sewa Indoor
                                        <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" name="reqHargaIndoorSewa" id="reqHargaIndoorSewa" style="width:15%; display: inline; text-align: right;" /> <label class="labeltotal">/ m2 / bulan</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: right;">
                                        Sewa Outdoor
                                        <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" name="reqHargaOutdoorSewa" id="reqHargaOutdoorSewa" style="width:15%; display: inline; text-align: right;" /> <label class="labeltotal">/ m2 / bulan</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: right;">
                                        Service Charge Indoor
                                        <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" name="reqHargaIndoorService" id="reqHargaIndoorService" style="width:15%; display: inline; text-align: right;" /> <label class="labeltotal">/ m2 / bulan</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: right;">
                                        Service Charge Outdoor
                                        <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" name="reqHargaOutdoorService" id="reqHargaOutdoorService" style="width:15%; display: inline; text-align: right;" /> <label class="labeltotal">/ m2 / bulan</label>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </fieldset>

                    <table class="table">
                        <thead>
                            <tr>
                                <td colspan="3">Harga Utility Charge</td>
                            </tr>
                            <tr>
                                <td>Listrik</td>
                                <td>:</td>
                                <td>
                                    <input type="text" id="reqNama" class="easyui-validatebox textbox form-control" required name="reqNama" value="<?=$reqNama ?>" data-options="required:true" style="width:90%; display: inline; text-align: right;" />
                                </td>
                            </tr>
                            <tr>
                                <td>Gas</td>
                                <td>:</td>
                                <td>
                                    <input type="text" id="reqNama" class="easyui-validatebox textbox form-control" required name="reqNama" value="<?=$reqNama ?>" data-options="required:true" style="width:90%; display: inline; text-align: right;" />
                                </td>
                            </tr>
                            <tr>
                                <td>Air</td>
                                <td>:</td>
                                <td>
                                    <input type="text" id="reqNama" class="easyui-validatebox textbox form-control" required name="reqNama" value="<?=$reqNama ?>" data-options="required:true" style="width:90%; display: inline; text-align: right;" />
                                </td>
                            </tr>
                            
                        </thead>
                    </table>

                    <table class="table">
                        <thead>
                            <tr>
                                <td>Down Payment</td>
                                <td>:</td>
                                <td>
                                    <input type="text" id="reqNama" class="easyui-validatebox textbox form-control" required name="reqNama" value="<?=$reqNama ?>" data-options="required:true" style="width:90%; display: inline; text-align: right;" />
                                </td>
                            </tr>
                            <tr>
                                <td>Periode Sewa</td>
                                <td>:</td>
                                <td>
                                    <input type="text" id="reqNama" class="easyui-validatebox textbox form-control" required name="reqNama" value="<?=$reqNama ?>" data-options="required:true" style="width:90%; display: inline; text-align: right;" />
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">Jam Operasional</td>
                            </tr>
                            <tr>
                                <td>Gedung</td>
                                <td>:</td>
                                <td>
                                    <input type="text" id="reqNama" class="easyui-validatebox textbox form-control" required name="reqNama" value="<?=$reqNama ?>" data-options="required:true" style="width:90%; display: inline; text-align: right;" />
                                </td>
                            </tr>
                            <tr>
                                <td>Tenant</td>
                                <td>:</td>
                                <td>
                                    <input type="text" id="reqNama" class="easyui-validatebox textbox form-control" required name="reqNama" value="<?=$reqNama ?>" data-options="required:true" style="width:90%; display: inline; text-align: right;" />
                                </td>
                            </tr>
                            
                        </thead>
                    </table>

                    <input type="hidden" name="reqId" value="<?=$reqId?>" />
                    <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
                    <!-- <div style="text-align:center;padding:5px">
                        <a href="javascript:void(0)" class="btn btn-primary" onclick="submitForm()">Submit</a>
                        <a href="javascript:void(0)" class="btn btn-warning" onclick="clearForm()">Clear</a>
                    </div> -->
                <!-- </form> -->

            </div>

        </form>
    </div>
</body>
</html>


<script>

$(document).ready(function() {
    
});

function openLookup(tipe) 
{
    /*tparam= [];

    var obj = {};
    obj["DESKRIPSI"] = "sdsasdasd";
    obj["KODE"] = "kode";
    obj["LANTAI"] = "lantai 11";
    obj["LOKASI_LOO_ID"] = "1";
    obj["NAMA"] = "nama";
    obj["NAMA_LOKASI_LOO"] = "SOSORO MALL - MERAK";
    obj["TIPE_INFO"] = null;
    obj["id"] = "1";
    obj["state"] = "open";
    obj["luas"] = "95.58";
    obj["kdtarif"] = "200000";
    obj["text"] = "nama";
    tparam.push(obj);
    // console.log(tparam);return false;
    // untuk percobanaan
    addmulti("I", tparam);
    addmulti("O", tparam);*/

    reqIdLokasi= $("#reqLokasiLooId").combotree("getValue");

    if (reqIdLokasi) 
    {
        openAdd('app/loadUrl/main/luas_sewa_lookup?reqId='+reqIdLokasi+'&reqTipe='+tipe)
    }
    else
    {
        $.messager.alert('Info', "Pilih Lokasi terlebih dahulu.", 'warning');
    }
}

function addmulti(vtipe, vparam)
{
    // console.log(vparam);return false;
    vparam.forEach(function (item, index) {
        // console.log(item, index);
        vdetilparam= [];
        vdetilparam.id= item.id;
        vdetilparam.nama= item.text;
        vdetilparam.nama_lokasi= item.NAMA_LOKASI_LOO;
        vdetilparam.kode= item.KODE;
        vdetilparam.lantai= item.LANTAI;
        vdetilparam.luas= item.LUAS;
        vdetilparam.kdtarif= item.KD_TARIF;
        // console.log(vdetilparam);
        // param.id, param.text, param.NAMA_LOKASI_LOO, param.KODE, param.LANTAI

        appenddata(vtipe, vdetilparam);
    });
}

function appenddata(vtipe, vdetilparam)
{
    id= vdetilparam["id"];
    nama= vdetilparam["nama"];
    kode= vdetilparam["kode"];
    nama_lokasi= vdetilparam["nama_lokasi"];
    lantai= vdetilparam["lantai"];
    vluas= setformat(vdetilparam["luas"]);
    vkdtarif= setformat(vdetilparam["kdtarif"]);
    // console.log(vdetilparam);return false;
    // vluas= setformat(95.58);
    // vkdtarif= setformat(200000);

    if (vtipe=='I') 
    {
        vtable= ''
        +'<tr>'
        +   '<td>'+kode+' - '+lantai+'</td>'
        +   '<td>:</td>'
        +   '<td style="width:30%">'
        +       '<input type="hidden" name="vmode[]" value="luas_sewa_indoor" />'
        +       '<input type="hidden" name="vid[]" class="valsetid" />'
        +       '<input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control totalluasindoor" name="vnilai[]" placeholder="Isi Luas (m2)" data-options="required:true" style="width:85%; display: inline; text-align: right;" value="'+vluas+'" /> <label class="labeltotal">m2</label>'
        +   '</td>'
        +'</tr>';
        $("#luassewaindoor").append(vtable);
        hitungluas("totalluasindoor");

        vtable= ''
        +'<tr>'
        +   '<td colspan="4">'
        +       kode+' - '+lantai
        +   '</td>'
        +'</tr>'
        +'<tr>'
        +   '<td>'
        +       '<input type="hidden" name="vmode[]" value="tarif_sewa_unit_indoor" />'
        +       '<input type="hidden" name="vid[]" class="valsetid" />'
        +       '<input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control totalsewaunitindoor" name="vnilai[]" placeholder="Isi Rp/m2" data-options="required:true" style="width:65%; display: inline; text-align: right;" value="'+vkdtarif+'" /> <label class="labeltotal">Rp/m2</label>'
        +   '</td>'
        +   '<td>'
        +       '<input type="hidden" name="vmode[]" value="tarif_sewa_unit_indoor_diskon" />'
        +       '<input type="hidden" name="vid[]" class="valsetid" />'
        +       '<input type="text" class="vlxuangclass easyui-validatebox textbox form-control totalsewaunitindoordiskon" name="vnilai[]" placeholder="Isi %" data-options="required:true" style="width:65%; display: inline; text-align: right;" value="0" /> <label class="labeltotal">%</label>'
        +   '</td>'
        +   '<td>'
        +       '<input type="hidden" name="vmode[]" value="tarif_sewa_unit_indoor_after_diskon" />'
        +       '<input type="hidden" name="vid[]" class="valsetid" />'
        +       '<input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control totalsewaunitindoorafterdiskon" name="vnilai[]" placeholder="Isi Rp/m2" data-options="required:true" style="width:65%; display: inline; text-align: right;" /> <label class="labeltotal">Rp/m2</label>'
        +   '</td>'
        +   '<td>'
        +       '<input type="hidden" name="vmode[]" value="tarif_sewa_unit_indoor_harga" />'
        +       '<input type="hidden" name="vid[]" class="valsetid" />'
        +       '<input type="hidden" class="totalsewaunitindoorsewaluas" value="'+vluas+'" />'
        +       '<input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control totalsewaunitindoorharga" name="vnilai[]" style="display: inline; text-align: right;" />'
        +   '</td>'
        +'</tr>'
        ;
        $("#tarifinfosewaindoor").append(vtable);
    }
    else
    {
        vtable= ''
        +'<tr>'
        +   '<td>'+kode+' - '+lantai+'</td>'
        +   '<td>:</td>'
        +   '<td>'
        +       '<input type="hidden" name="vmode[]" value="luas_sewa_outdoor" />'
        +       '<input type="hidden" name="vid[]" class="valsetid" />'
        +       '<input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control totalluasoutdoor" name="vnilai[]" placeholder="Isi Luas (m2)" data-options="required:true" style="width:85%; display: inline; text-align: right;" value="'+vluas+'" /> <label class="labeltotal">m2</label>'
        +   '</td>'
        +'</tr>';
        $("#luassewaoutdoor").append(vtable);
        hitungluas("totalluasoutdoor");

        vtable= ''
        +'<tr>'
        +   '<td colspan="4">'
        +       kode+' - '+lantai
        +   '</td>'
        +'</tr>'
        +'<tr>'
        +   '<td>'
        +       '<input type="hidden" name="vmode[]" value="tarif_sewa_unit_outdoor" />'
        +       '<input type="hidden" name="vid[]" class="valsetid" />'
        +       '<input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control totalsewaunitoutdoor" name="vnilai[]" placeholder="Isi Rp/m2" data-options="required:true" style="width:65%; display: inline; text-align: right;" value="'+vkdtarif+'" /> <label class="labeltotal">Rp/m2</label>'
        +   '</td>'
        +   '<td>'
        +       '<input type="hidden" name="vmode[]" value="tarif_sewa_unit_outdoor_diskon" />'
        +       '<input type="hidden" name="vid[]" class="valsetid" />'
        +       '<input type="text" class="vlxuangclass easyui-validatebox textbox form-control totalsewaunitoutdoordiskon" name="vnilai[]" placeholder="Isi %" data-options="required:true" style="width:65%; display: inline; text-align: right;" value="0" /> <label class="labeltotal">%</label>'
        +   '</td>'
        +   '<td>'
        +       '<input type="hidden" name="vmode[]" value="tarif_sewa_unit_outdoor_after_diskon" />'
        +       '<input type="hidden" name="vid[]" class="valsetid" />'
        +       '<input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control totalsewaunitoutdoorafterdiskon" name="vnilai[]" placeholder="Isi Rp/m2" data-options="required:true" style="width:65%; display: inline; text-align: right;" /> <label class="labeltotal">Rp/m2</label>'
        +   '</td>'
        +   '<td>'
        +       '<input type="hidden" name="vmode[]" value="tarif_sewa_unit_outdoor_harga" />'
        +       '<input type="hidden" name="vid[]" class="valsetid" />'
        +       '<input type="hidden" class="totalsewaunitoutdoorsewaluas" value="'+vluas+'" />'
        +       '<input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control totalsewaunitoutdoorharga" name="vnilai[]" style="display: inline; text-align: right;" />'
        +   '</td>'
        +'</tr>'
        ;
        $("#tarifinfosewaoutdoor").append(vtable);
    }

    callformatdyna();
}

var vlxformat;
callformatdyna();
function callformatdyna()
{
    $(function(){
        vlxformat = function(num){
            var str = num.toString().replace("", ""), parts = false, output = [], i = 1, formatted = null;
            if(str.indexOf(",") > 0) {
                parts = str.split(",");
                str = parts[0];
            }
            str = str.split("").reverse();
            for(var j = 0, len = str.length; j < len; j++) {
                if(str[j] != ".") {
                    output.push(str[j]);
                    if(i%3 == 0 && j < (len - 1)) {
                        output.push(".");
                    }
                    i++;
                }
            }
            formatted = output.reverse().join("");
            return( formatted + ((parts) ? "," + parts[1].substr(0, 2) : ""));
        };

        $('.vlxuangclass').bind('keyup paste', function(){
            var numeric = this.value.replace(/[^0-9\,]/g, '');
            $(this).val(vlxformat(numeric));
        });

        $(".totalluasindoor").keyup(function() {
            hitungluas("totalluasindoor");
        });

        $(".totalluasoutdoor").keyup(function() {
            hitungluas("totalluasoutdoor");
        });

        $(".totalsewaunitindoordiskon").keyup(function() {
            totalsewaunitindoordiskon= $(this).val();
            totalsewaunitindoordiskon= FormatAngkaNumber(totalsewaunitindoordiskon);
            totalsewaunitindoordiskon= notnullval(totalsewaunitindoordiskon);

            totalsewaunitindoor= $(this).closest('tr').find('.totalsewaunitindoor').val();
            totalsewaunitindoor= FormatAngkaNumber(totalsewaunitindoor);
            totalsewaunitindoor= notnullval(totalsewaunitindoor);

            // vlog= totalsewaunitindoor + " - " + " ( "+(totalsewaunitindoordiskon / 100)+" * "+totalsewaunitindoor+" )";
            // console.log(vlog);

            totalsewaunitindoorafterdiskon= totalsewaunitindoor - ( (totalsewaunitindoordiskon / 100) * totalsewaunitindoor );
            totalsewaunitindoorafterdiskon= setformat(totalsewaunitindoorafterdiskon);
            $(this).closest('tr').find('.totalsewaunitindoorafterdiskon').val(totalsewaunitindoorafterdiskon);

            // hitunghargasewa("totalsewaunitindoordiskon");

            totalsewaunitindoorsewaluas= $(this).closest('tr').find('.totalsewaunitindoorsewaluas').val();
            totalsewaunitindoorsewaluas= FormatAngkaNumber(totalsewaunitindoorsewaluas);
            totalsewaunitindoorsewaluas= notnullval(totalsewaunitindoorsewaluas);

            totalsewaunitindoorafterdiskon= $(this).closest('tr').find('.totalsewaunitindoorafterdiskon').val();
            totalsewaunitindoorafterdiskon= FormatAngkaNumber(totalsewaunitindoorafterdiskon);
            totalsewaunitindoorafterdiskon= notnullval(totalsewaunitindoorafterdiskon);

            totalsewaunitindoorharga= totalsewaunitindoorafterdiskon * totalsewaunitindoorsewaluas;
            totalsewaunitindoorharga= setformat(totalsewaunitindoorharga);
            $(this).closest('tr').find('.totalsewaunitindoorharga').val(totalsewaunitindoorharga);

        });

        $(".totalsewaunitoutdoordiskon").keyup(function() {
            totalsewaunitoutdoordiskon= $(this).val();
            totalsewaunitoutdoordiskon= FormatAngkaNumber(totalsewaunitoutdoordiskon);
            totalsewaunitoutdoordiskon= notnullval(totalsewaunitoutdoordiskon);

            totalsewaunitoutdoor= $(this).closest('tr').find('.totalsewaunitoutdoor').val();
            totalsewaunitoutdoor= FormatAngkaNumber(totalsewaunitoutdoor);
            totalsewaunitoutdoor= notnullval(totalsewaunitoutdoor);

            // vlog= totalsewaunitoutdoor + " - " + " ( "+(totalsewaunitoutdoordiskon / 100)+" * "+totalsewaunitoutdoor+" )";
            // console.log(vlog);

            totalsewaunitoutdoorafterdiskon= totalsewaunitoutdoor - ( (totalsewaunitoutdoordiskon / 100) * totalsewaunitoutdoor );
            totalsewaunitoutdoorafterdiskon= setformat(totalsewaunitoutdoorafterdiskon);
            $(this).closest('tr').find('.totalsewaunitoutdoorafterdiskon').val(totalsewaunitoutdoorafterdiskon);

            // hitunghargasewa("totalsewaunitoutdoordiskon");

            totalsewaunitoutdoorsewaluas= $(this).closest('tr').find('.totalsewaunitoutdoorsewaluas').val();
            totalsewaunitoutdoorsewaluas= FormatAngkaNumber(totalsewaunitoutdoorsewaluas);
            totalsewaunitoutdoorsewaluas= notnullval(totalsewaunitoutdoorsewaluas);

            totalsewaunitoutdoorafterdiskon= $(this).closest('tr').find('.totalsewaunitoutdoorafterdiskon').val();
            totalsewaunitoutdoorafterdiskon= FormatAngkaNumber(totalsewaunitoutdoorafterdiskon);
            totalsewaunitoutdoorafterdiskon= notnullval(totalsewaunitoutdoorafterdiskon);

            totalsewaunitoutdoorharga= totalsewaunitoutdoorafterdiskon * totalsewaunitoutdoorsewaluas;
            totalsewaunitoutdoorharga= setformat(totalsewaunitoutdoorharga);
            $(this).closest('tr').find('.totalsewaunitoutdoorharga').val(totalsewaunitoutdoorharga);
        });

    });
}

function hitunghargasewa(vmode)
{
    vtotal= 0;
    vindex= 0;
    $("."+vmode).each(function(){
        infoval= FormatAngkaNumber($(this).parents().find('.totalsewaunitindoorafterdiskon').val());
        infoval= notnullval(infoval);
        vtotal= parseFloat(vtotal) + parseFloat(infoval);

        // console.log(vtotal);
        vtotal= setformat(vtotal);

        if(vmode == "totalsewaunitindoordiskon")
        {
            $(this).closest('tr').find('.totalsewaunitindoorharga').val(vtotal);
        }
        else if(vmode == "totalsewaunitoutdoordiskon")
        {
            $(this).closest('tr').find('.totalsewaunitoutdoorharga').val(vtotal);
        }
    });
}

function hitungluas(vmode)
{
    vtotal= 0;
    vindex= 0;
    $("."+vmode).each(function(){
        infoid= $(this).attr('id');
        infoval= $(this).val();
        infoval = infoval ? infoval : 0;
        infoval= FormatAngkaNumber(infoval);
        // console.log("xx"+infoval);
        vtotal= parseFloat(vtotal) + parseFloat(infoval);

        if(vmode == "totalluasindoor" || vmode == "totalluasoutdoor")
        {
            // $(this).parents().find('.valsetid').val(vindex);
            $(this).closest('tr').find('.valsetid').val(vindex);
            vindex++;
        }

    });

    vtotal= setformat(vtotal);
    if(vmode == "totalluasindoor")
    {
        $("#reqTotalLuasIndoor").val(vtotal);
    }
    else if(vmode == "totalluasoutdoor")
    {
        $("#reqTotalLuasOutdoor").val(vtotal);
    }

    if(vmode == "totalluasindoor" || vmode == "totalluasoutdoor")
    {
        reqTotalLuasIndoor= $("#reqTotalLuasIndoor").val();
        reqTotalLuasIndoor= FormatAngkaNumber($("#reqTotalLuasIndoor").val());
        reqTotalLuasIndoor= notnullval(reqTotalLuasIndoor);
        // console.log(reqTotalLuasIndoor);

        reqTotalLuasOutdoor= $("#reqTotalLuasOutdoor").val();
        reqTotalLuasOutdoor= FormatAngkaNumber($("#reqTotalLuasOutdoor").val());
        reqTotalLuasOutdoor= notnullval(reqTotalLuasOutdoor);

        reqTotalLuas= reqTotalLuasIndoor + reqTotalLuasOutdoor;
        reqTotalLuas= setformat(reqTotalLuas);
        $("#reqTotalLuas").val(reqTotalLuas);
    }
}

function setformat(v)
{
    v= v.toString();
    v= ReplaceString('.',',',v);
    v= vlxformat(v);
    return v;
}

function notnullval(v)
{
    v= v ? v : 0;
    v= parseFloat(v);
    return v;
}

function submitPreview() 
{
    parent.openAdd('app/loadUrl/report/template/?reqId=<?= $reqId ?>');
}

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

<style type="text/css">
    fieldset {
        border: 1px solid rgba(21, 125, 186, 0.1) !important;
        margin-top: 0px !important;
        padding: 0px 10px !important;
    }

    .tdcolor{
        background-color: #d8e8f8; text-align: center;
    }

    .labeltotal{
        width: 8%;
    }
</style>