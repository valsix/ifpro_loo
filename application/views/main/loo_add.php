
<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model("TrLoo");
$this->load->model("TrLooDetil");

$reqId= $this->input->get("reqId");

$arrdetil= $arrlokasi= [];
if(empty($reqId))
{
    $reqMode= "insert";
}
else
{
    $reqMode = "ubah";
    $set= new TrLoo();
    $set->selectByParams(array("A.TR_LOO_ID" => $reqId));
    $set->firstRow();
    
    $reqProdukId= $set->getField("PRODUK_ID");
    $reqCustomerId= $set->getField("CUSTOMER_ID");
    $reqLokasiLooId= $set->getField("LOKASI_LOO_ID");
    $reqTotalLuasIndoor= $set->getField("TOTAL_LUAS_INDOOR");
    $reqTotalLuasOutdoor= $set->getField("TOTAL_LUAS_OUTDOOR");
    $reqTotalLuas= $set->getField("TOTAL_LUAS");
    $reqHargaIndoorSewa= $set->getField("HARGA_INDOOR_SEWA");
    $reqHargaOutdoorSewa= $set->getField("HARGA_OUTDOOR_SEWA");
    $reqHargaIndoorService= $set->getField("HARGA_INDOOR_SERVICE");
    $reqHargaOutdoorService= $set->getField("HARGA_OUTDOOR_SERVICE");

    /*

        $set->setField("TOTAL_DISKON_INDOOR_SEWA", ValToNullDB(dotToNo($req)));
        $set->setField("TOTAL_DISKON_OUTDOOR_SEWA", ValToNullDB(dotToNo($req)));
        $set->setField("TOTAL_DISKON_INDOOR_SERVICE", ValToNullDB(dotToNo($req)));
        $set->setField("TOTAL_DISKON_OUTDOOR_SERVICE", ValToNullDB(dotToNo($req)));
        $set->setField("DP", ValToNullDB(dotToNo($reqDp)));
        $set->setField("PERIODE_SEWA", ValToNullDB(dotToNo($reqPeriodeSewa)));*/

    $statement= " AND A.TR_LOO_ID = ".$reqId." AND VMODE ILIKE '%luas_sewa%'";
    $set= new TrLooDetil();
    $set->selectlokasi(array(), -1,-1, $statement);
    while($set->nextRow())
    {
        $arrdata= [];
        $arrdata["rowdetilid"]= $set->getField("TR_LOO_DETIL_ID");
        $arrdata["rowid"]= $set->getField("TR_LOO_ID");
        $arrdata["vmode"]= $set->getField("VMODE");
        $arrdata["vid"]= $set->getField("VID");
        $arrdata["vnilai"]= $set->getField("NILAI");
        $arrdata["kode"]= $set->getField("KODE");
        $arrdata["nama"]= $set->getField("NAMA");
        $arrdata["lantai"]= $set->getField("LANTAI");
        array_push($arrlokasi, $arrdata);
    }

    $statement= " AND A.TR_LOO_ID = ".$reqId;
    $set= new TrLooDetil();
    $set->selectByParams(array(), -1,-1, $statement);
    while($set->nextRow())
    {
        $valid= $set->getField("VID");
        $valmode= $set->getField("VMODE");
        $arrdata= [];
        $arrdata["keyrowdetil"]= $valid."-".$valmode;
        $arrdata["rowdetilid"]= $set->getField("TR_LOO_DETIL_ID");;
        $arrdata["rowid"]= $set->getField("TR_LOO_ID");
        $arrdata["vmode"]= $valmode;
        $arrdata["vid"]= $valid;
        $arrdata["vnilai"]= $set->getField("NILAI");
        array_push($arrdetil, $arrdata);
    }
}
// print_r($arrlokasi);exit;
// print_r($arrdetil);exit;
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

                <input type="hidden" name="reqStatusData" id="reqStatusData" value="<?=$reqStatusData?>" />
                <input type="hidden" name="reqId" value="<?=$reqId?>" />
                <input type="hidden" name="reqMode" value="<?=$reqMode?>" />

                <table class="table">
                    <thead>
                        <tr>           
                            <td>Lokasi</td>
                            <td>:</td>
                            <td>
                                <input type="text" name="reqLokasiLooId" class="easyui-combotree" id="reqLokasiLooId" data-options="width:'350', valueField:'id', textField:'text', editable:false, url:'combo_json/comboLokasiLoo'" required value="<?=$reqLokasiLooId?>" />
                            </td>
                        </tr>
                        <tr>
                            <td>Customer</td>
                            <td>:</td>
                            <td>
                                <input type="text" name="reqCustomerId" class="easyui-combobox" id="reqCustomerId" data-options="width:'350', valueField:'id', textField:'text', editable:false, url:'combo_json/comboCustomer?cek=pemilik'" required value="<?=$reqCustomerId?>" />
                            </td>
                        </tr>
                        <tr>
                            <td>Produk</td>
                            <td>:</td>
                            <td>
                                <input type="text" name="reqProdukId" class="easyui-combobox" id="reqProdukId" data-options="width:'350', valueField:'id', textField:'text', editable:false, url:'combo_json/comboProduk'" required value="<?=$reqProdukId?>" />
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
                                            <?
                                            foreach ($arrlokasi as $k => $v)
                                            {
                                                $vkeyid= $v["rowdetilid"];
                                                // $vlabel= $v["kode"]." - ".$v["nama"];
                                                $vlabel= $v["kode"]." - ".$v["lantai"];
                                                $vnilai= $v["vnilai"];
                                                $vmode= $v["vmode"];
                                                if($vmode == "luas_sewa_indoor")
                                                {
                                            ?>
                                            <tr class="groupclass<?=$vkeyid?>">
                                                <td>
                                                    <?=$vlabel?> <i style="cursor:pointer" class="fa fa-times-circle text-danger" aria-hidden="true" onclick="hapusgroupclass('<?=$vkeyid?>');"></i>
                                                </td>
                                                <td>:</td>
                                                <td style="width:30%">
                                                    <input type="hidden" name="vmode[]" value="luas_sewa_indoor" />
                                                    <input type="hidden" name="vid[]" class="valsetid" value="<?=$vkeyid?>" />
                                                    <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control totalluasindoor" name="vnilai[]" placeholder="Isi Luas (m2)" data-options="required:true" style="width:85%; display: inline; text-align: right;" value="<?=numberToIna($vnilai)?>" /> <label class="labeltotal">m2</label>'
                                                </td>
                                            </tr>
                                            <?
                                                }
                                            }
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="2" style="text-align: right;">Total Luas Indoor</td>
                                                <td style="width: 20%">
                                                    <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" name="reqTotalLuasIndoor" id="reqTotalLuasIndoor" style="width:85%; display: inline; text-align: right;" value="<?=numberToIna($reqTotalLuasIndoor)?>" /> <label class="labeltotal">m2</label>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </td>
                                <td style="width: 50%">
                                    <table class="table">
                                        <tbody id="luassewaoutdoor">
                                            <?
                                            foreach ($arrlokasi as $k => $v)
                                            {
                                                $vkeyid= $v["rowdetilid"];
                                                // $vlabel= $v["kode"]." - ".$v["nama"];
                                                $vlabel= $v["kode"]." - ".$v["lantai"];
                                                $vnilai= $v["vnilai"];
                                                $vmode= $v["vmode"];
                                                if($vmode == "luas_sewa_outdoor")
                                                {
                                            ?>
                                            <tr class="groupclass<?=$vkeyid?>">
                                                <td>
                                                    <?=$vlabel?> <i style="cursor:pointer" class="fa fa-times-circle text-danger" aria-hidden="true" onclick="hapusgroupclass('<?=$vkeyid?>');"></i>
                                                </td>
                                                <td>:</td>
                                                <td style="width:30%">
                                                    <input type="hidden" name="vmode[]" value="luas_sewa_outdoor" />
                                                    <input type="hidden" name="vid[]" class="valsetid" value="<?=$vkeyid?>" />
                                                    <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control totalluasoutdoor" name="vnilai[]" placeholder="Isi Luas (m2)" data-options="required:true" style="width:85%; display: inline; text-align: right;" value="<?=numberToIna($vnilai)?>" /> <label class="labeltotal">m2</label>'
                                                </td>
                                            </tr>
                                            <?
                                                }
                                            }
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="2" style="text-align: right;">Total Luas Outdoor</td>
                                                <td style="width: 20%">
                                                    <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" name="reqTotalLuasOutdoor" id="reqTotalLuasOutdoor" style="width:85%; display: inline; text-align: right;" value="<?=numberToIna($reqTotalLuasOutdoor)?>" /> <label class="labeltotal">m2</label>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="text-align: right;">
                                    Total Luas Sewa
                                    <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" name="reqTotalLuas" id="reqTotalLuas" style="width:15%; display: inline; text-align: right;" value="<?=numberToIna($reqTotalLuas)?>" /> <label class="labeltotal">m2</label>
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
                                            <?
                                            foreach ($arrlokasi as $k => $v)
                                            {
                                                $vkeyid= $v["rowdetilid"];
                                                // $vlabel= $v["kode"]." - ".$v["nama"];
                                                $vlabel= $v["kode"]." - ".$v["lantai"];
                                                $vluas= $v["vnilai"];
                                                $vmode= $v["vmode"];
                                                if($vmode == "luas_sewa_indoor")
                                                {
                                            ?>
                                            <tr class="groupclass<?=$vkeyid?>">
                                                <td colspan="4"><?=$vlabel?></td>
                                            </tr>
                                            <tr class="groupclass<?=$vkeyid?>">
                                                <td>
                                                    <?
                                                    $valnilai= 0;
                                                    $valmode= "tarif_sewa_unit_indoor";
                                                    $infocarikey= $vkeyid."-".$valmode;
                                                    $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrdetil);
                                                    if(!empty($arrkondisicheck))
                                                    {
                                                        $vindex= $arrkondisicheck[0];
                                                        $valnilai= $arrdetil[$vindex]["vnilai"];
                                                    }
                                                    ?>
                                                    <input type="hidden" name="vmode[]" value="<?=$valmode?>" />
                                                    <input type="hidden" name="vid[]" class="valsetid" value="<?=$vkeyid?>" />
                                                    <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control totalsewaunitindoor" name="vnilai[]" placeholder="Isi Rp/m2" data-options="required:true" style="width:65%; display: inline; text-align: right;" value="<?=numberToIna($valnilai)?>" /> <label class="labeltotal">Rp/m2</label>
                                                </td>
                                                <td>
                                                    <?
                                                    $valnilai= 0;
                                                    $valmode= "tarif_sewa_unit_indoor_diskon";
                                                    $infocarikey= $vkeyid."-".$valmode;
                                                    $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrdetil);
                                                    if(!empty($arrkondisicheck))
                                                    {
                                                        $vindex= $arrkondisicheck[0];
                                                        $valnilai= $arrdetil[$vindex]["vnilai"];
                                                    }
                                                    ?>
                                                    <input type="hidden" name="vmode[]" value="<?=$valmode?>" />
                                                    <input type="hidden" name="vid[]" class="valsetid" value="<?=$vkeyid?>" />
                                                    <input type="text" class="vlxuangclass easyui-validatebox textbox form-control totalsewaunitindoordiskon" name="vnilai[]" placeholder="Isi %" data-options="required:true" style="width:65%; display: inline; text-align: right;" value="<?=numberToIna($valnilai)?>" /> <label class="labeltotal">%</label>
                                                </td>
                                                <td>
                                                    <?
                                                    $valnilai= 0;
                                                    $valmode= "tarif_sewa_unit_indoor_after_diskon";
                                                    $infocarikey= $vkeyid."-".$valmode;
                                                    $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrdetil);
                                                    if(!empty($arrkondisicheck))
                                                    {
                                                        $vindex= $arrkondisicheck[0];
                                                        $valnilai= $arrdetil[$vindex]["vnilai"];
                                                    }
                                                    ?>
                                                    <input type="hidden" name="vmode[]" value="<?=$valmode?>" />
                                                    <input type="hidden" name="vid[]" class="valsetid" value="<?=$vkeyid?>" />
                                                    <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control totalsewaunitindoorafterdiskon" name="vnilai[]" placeholder="Isi Rp/m2" data-options="required:true" style="width:65%; display: inline; text-align: right;" value="<?=numberToIna($valnilai)?>" /> <label class="labeltotal">Rp/m2</label>
                                                </td>
                                                <td>
                                                    <?
                                                    $valnilai= 0;
                                                    $valmode= "tarif_sewa_unit_indoor_harga";
                                                    $infocarikey= $vkeyid."-".$valmode;
                                                    $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrdetil);
                                                    if(!empty($arrkondisicheck))
                                                    {
                                                        $vindex= $arrkondisicheck[0];
                                                        $valnilai= $arrdetil[$vindex]["vnilai"];
                                                    }
                                                    ?>
                                                    <input type="hidden" name="vmode[]" value="<?=$valmode?>" />
                                                    <input type="hidden" name="vid[]" class="valsetid" value="<?=$vkeyid?>" />
                                                    <input type="hidden" class="totalsewaunitindoorsewaluas" value="<?=$vluas?>" />
                                                    <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control totalsewaunitindoorharga" name="vnilai[]" style="display: inline; text-align: right;" value="<?=numberToIna($valnilai)?>" />
                                                </td>
                                            </tr>
                                            <?
                                                }
                                            }
                                            ?>

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
                                            <?
                                            foreach ($arrlokasi as $k => $v)
                                            {
                                                $vkeyid= $v["rowdetilid"];
                                                // $vlabel= $v["kode"]." - ".$v["nama"];
                                                $vlabel= $v["kode"]." - ".$v["lantai"];
                                                $vluas= $v["vnilai"];
                                                $vmode= $v["vmode"];
                                                if($vmode == "luas_sewa_outdoor")
                                                {
                                            ?>
                                            <tr class="groupclass<?=$vkeyid?>">
                                                <td colspan="4"><?=$vlabel?></td>
                                            </tr>
                                            <tr class="groupclass<?=$vkeyid?>">
                                                <td>
                                                    <?
                                                    $valnilai= 0;
                                                    $valmode= "tarif_sewa_unit_outdoor";
                                                    $infocarikey= $vkeyid."-".$valmode;
                                                    $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrdetil);
                                                    if(!empty($arrkondisicheck))
                                                    {
                                                        $vindex= $arrkondisicheck[0];
                                                        $valnilai= $arrdetil[$vindex]["vnilai"];
                                                    }
                                                    ?>
                                                    <input type="hidden" name="vmode[]" value="<?=$valmode?>" />
                                                    <input type="hidden" name="vid[]" class="valsetid" value="<?=$vkeyid?>" />
                                                    <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control totalsewaunitoutdoor" name="vnilai[]" placeholder="Isi Rp/m2" data-options="required:true" style="width:65%; display: inline; text-align: right;" value="<?=numberToIna($valnilai)?>" /> <label class="labeltotal">Rp/m2</label>
                                                </td>
                                                <td>
                                                    <?
                                                    $valnilai= 0;
                                                    $valmode= "tarif_sewa_unit_outdoor_diskon";
                                                    $infocarikey= $vkeyid."-".$valmode;
                                                    $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrdetil);
                                                    if(!empty($arrkondisicheck))
                                                    {
                                                        $vindex= $arrkondisicheck[0];
                                                        $valnilai= $arrdetil[$vindex]["vnilai"];
                                                    }
                                                    ?>
                                                    <input type="hidden" name="vmode[]" value="<?=$valmode?>" />
                                                    <input type="hidden" name="vid[]" class="valsetid" value="<?=$vkeyid?>" />
                                                    <input type="text" class="vlxuangclass easyui-validatebox textbox form-control totalsewaunitoutdoordiskon" name="vnilai[]" placeholder="Isi %" data-options="required:true" style="width:65%; display: inline; text-align: right;" value="<?=numberToIna($valnilai)?>" /> <label class="labeltotal">%</label>
                                                </td>
                                                <td>
                                                    <?
                                                    $valnilai= 0;
                                                    $valmode= "tarif_sewa_unit_outdoor_after_diskon";
                                                    $infocarikey= $vkeyid."-".$valmode;
                                                    $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrdetil);
                                                    if(!empty($arrkondisicheck))
                                                    {
                                                        $vindex= $arrkondisicheck[0];
                                                        $valnilai= $arrdetil[$vindex]["vnilai"];
                                                    }
                                                    ?>
                                                    <input type="hidden" name="vmode[]" value="<?=$valmode?>" />
                                                    <input type="hidden" name="vid[]" class="valsetid" value="<?=$vkeyid?>" />
                                                    <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control totalsewaunitoutdoorafterdiskon" name="vnilai[]" placeholder="Isi Rp/m2" data-options="required:true" style="width:65%; display: inline; text-align: right;" value="<?=numberToIna($valnilai)?>" /> <label class="labeltotal">Rp/m2</label>
                                                </td>
                                                <td>
                                                    <?
                                                    $valnilai= 0;
                                                    $valmode= "tarif_sewa_unit_outdoor_harga";
                                                    $infocarikey= $vkeyid."-".$valmode;
                                                    $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrdetil);
                                                    if(!empty($arrkondisicheck))
                                                    {
                                                        $vindex= $arrkondisicheck[0];
                                                        $valnilai= $arrdetil[$vindex]["vnilai"];
                                                    }
                                                    ?>
                                                    <input type="hidden" name="vmode[]" value="<?=$valmode?>" />
                                                    <input type="hidden" name="vid[]" class="valsetid" value="<?=$vkeyid?>" />
                                                    <input type="hidden" class="totalsewaunitoutdoorsewaluas" value="<?=$vluas?>" />
                                                    <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control totalsewaunitoutdoorharga" name="vnilai[]" style="display: inline; text-align: right;" value="<?=numberToIna($valnilai)?>" />
                                                </td>
                                            </tr>
                                            <?
                                                }
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <table class="table">
                                        <tbody id="tarifinfosewascindoor">
                                            <tr>
                                                <td class="tdcolor" style="width: 35%">Service Charge</td>
                                                <td class="tdcolor" style="width: 30%">Discount</td>
                                                <td class="tdcolor" style="width: 35%">Tarif (after discount)</td>
                                            </tr>
                                            <?
                                            foreach ($arrlokasi as $k => $v)
                                            {
                                                $vkeyid= $v["rowdetilid"];
                                                // $vlabel= $v["kode"]." - ".$v["nama"];
                                                $vlabel= $v["kode"]." - ".$v["lantai"];
                                                $vluas= $v["vnilai"];
                                                $vmode= $v["vmode"];
                                                if($vmode == "luas_sewa_indoor")
                                                {
                                            ?>
                                            <tr class="groupclass<?=$vkeyid?>">
                                                <td colspan="4"><?=$vlabel?></td>
                                            </tr>
                                            <tr class="groupclass<?=$vkeyid?>">
                                                <td>
                                                    <?
                                                    $valnilai= 0;
                                                    $valmode= "tarif_sewa_sc_indoor";
                                                    $infocarikey= $vkeyid."-".$valmode;
                                                    $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrdetil);
                                                    if(!empty($arrkondisicheck))
                                                    {
                                                        $vindex= $arrkondisicheck[0];
                                                        $valnilai= $arrdetil[$vindex]["vnilai"];
                                                    }
                                                    ?>
                                                    <input type="hidden" name="vmode[]" value="<?=$valmode?>" />
                                                    <input type="hidden" name="vid[]" class="valsetid" value="<?=$vkeyid?>" />
                                                    <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control totalsewascindoor" name="vnilai[]" placeholder="Isi Rp/m2" data-options="required:true" style="width:65%; display: inline; text-align: right;" value="<?=numberToIna($valnilai)?>" /> <label class="labeltotal">Rp/m2</label>
                                                </td>
                                                <td>
                                                    <?
                                                    $valnilai= 0;
                                                    $valmode= "tarif_sewa_sc_indoor_diskon";
                                                    $infocarikey= $vkeyid."-".$valmode;
                                                    $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrdetil);
                                                    if(!empty($arrkondisicheck))
                                                    {
                                                        $vindex= $arrkondisicheck[0];
                                                        $valnilai= $arrdetil[$vindex]["vnilai"];
                                                    }
                                                    ?>
                                                    <input type="hidden" name="vmode[]" value="<?=$valmode?>" />
                                                    <input type="hidden" name="vid[]" class="valsetid" value="<?=$vkeyid?>" />
                                                    <input type="text" class="vlxuangclass easyui-validatebox textbox form-control totalsewascindoordiskon" name="vnilai[]" placeholder="Isi %" data-options="required:true" style="width:65%; display: inline; text-align: right;" value="<?=numberToIna($valnilai)?>" /> <label class="labeltotal">%</label>
                                                </td>
                                                <td>
                                                    <?
                                                    $valnilai= 0;
                                                    $valmode= "tarif_sewa_sc_indoor_after_diskon";
                                                    $infocarikey= $vkeyid."-".$valmode;
                                                    $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrdetil);
                                                    if(!empty($arrkondisicheck))
                                                    {
                                                        $vindex= $arrkondisicheck[0];
                                                        $valnilai= $arrdetil[$vindex]["vnilai"];
                                                    }
                                                    ?>
                                                    <input type="hidden" name="vmode[]" value="<?=$valmode?>" />
                                                    <input type="hidden" name="vid[]" class="valsetid" value="<?=$vkeyid?>" />
                                                    <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control totalsewascindoorafterdiskon" name="vnilai[]" placeholder="Isi Rp/m2" data-options="required:true" style="width:65%; display: inline; text-align: right;" value="<?=numberToIna($valnilai)?>" /> <label class="labeltotal">Rp/m2</label>
                                                </td>
                                            </tr>
                                            <?
                                                }
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </td>
                                <td colspan="3">
                                    <table class="table">
                                        <tbody id="tarifinfosewascoutdoor">
                                            <tr>
                                                <td class="tdcolor" style="width: 35%">Service Charge</td>
                                                <td class="tdcolor" style="width: 30%">Discount</td>
                                                <td class="tdcolor" style="width: 35%">Tarif (after discount)</td>
                                            </tr>
                                            <?
                                            foreach ($arrlokasi as $k => $v)
                                            {
                                                $vkeyid= $v["rowdetilid"];
                                                // $vlabel= $v["kode"]." - ".$v["nama"];
                                                $vlabel= $v["kode"]." - ".$v["lantai"];
                                                $vluas= $v["vnilai"];
                                                $vmode= $v["vmode"];
                                                if($vmode == "luas_sewa_outdoor")
                                                {
                                            ?>
                                            <tr class="groupclass<?=$vkeyid?>">
                                                <td colspan="4"><?=$vlabel?></td>
                                            </tr>
                                            <tr class="groupclass<?=$vkeyid?>">
                                                <td>
                                                    <?
                                                    $valnilai= 0;
                                                    $valmode= "tarif_sewa_sc_outdoor";
                                                    $infocarikey= $vkeyid."-".$valmode;
                                                    $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrdetil);
                                                    if(!empty($arrkondisicheck))
                                                    {
                                                        $vindex= $arrkondisicheck[0];
                                                        $valnilai= $arrdetil[$vindex]["vnilai"];
                                                    }
                                                    ?>
                                                    <input type="hidden" name="vmode[]" value="<?=$valmode?>" />
                                                    <input type="hidden" name="vid[]" class="valsetid" value="<?=$vkeyid?>" />
                                                    <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control totalsewascoutdoor" name="vnilai[]" placeholder="Isi Rp/m2" data-options="required:true" style="width:65%; display: inline; text-align: right;" value="<?=numberToIna($valnilai)?>" /> <label class="labeltotal">Rp/m2</label>
                                                </td>
                                                <td>
                                                    <?
                                                    $valnilai= 0;
                                                    $valmode= "tarif_sewa_sc_outdoor_diskon";
                                                    $infocarikey= $vkeyid."-".$valmode;
                                                    $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrdetil);
                                                    if(!empty($arrkondisicheck))
                                                    {
                                                        $vindex= $arrkondisicheck[0];
                                                        $valnilai= $arrdetil[$vindex]["vnilai"];
                                                    }
                                                    ?>
                                                    <input type="hidden" name="vmode[]" value="<?=$valmode?>" />
                                                    <input type="hidden" name="vid[]" class="valsetid" value="<?=$vkeyid?>" />
                                                    <input type="text" class="vlxuangclass easyui-validatebox textbox form-control totalsewascoutdoordiskon" name="vnilai[]" placeholder="Isi %" data-options="required:true" style="width:65%; display: inline; text-align: right;" value="<?=numberToIna($valnilai)?>" /> <label class="labeltotal">%</label>
                                                </td>
                                                <td>
                                                    <?
                                                    $valnilai= 0;
                                                    $valmode= "tarif_sewa_sc_outdoor_after_diskon";
                                                    $infocarikey= $vkeyid."-".$valmode;
                                                    $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrdetil);
                                                    if(!empty($arrkondisicheck))
                                                    {
                                                        $vindex= $arrkondisicheck[0];
                                                        $valnilai= $arrdetil[$vindex]["vnilai"];
                                                    }
                                                    ?>
                                                    <input type="hidden" name="vmode[]" value="<?=$valmode?>" />
                                                    <input type="hidden" name="vid[]" class="valsetid" value="<?=$vkeyid?>" />
                                                    <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control totalsewascoutdoorafterdiskon" name="vnilai[]" placeholder="Isi Rp/m2" data-options="required:true" style="width:65%; display: inline; text-align: right;" value="<?=numberToIna($valnilai)?>" /> <label class="labeltotal">Rp/m2</label>
                                                </td>
                                            </tr>
                                            <?
                                                }
                                            }
                                            ?>
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
                                <td style="width: 25%; text-align: right;">
                                    Sewa Indoor
                                    <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" name="reqHargaIndoorSewa" id="reqHargaIndoorSewa" style="width:40%; display: inline; text-align: right;" value="<?=numberToIna($reqHargaIndoorSewa)?>" /> <label class="labelsumtotal">/ m2 / bulan</label>
                                </td>
                                <td style="width: 25%; text-align: right;">
                                    Sewa Outdoor
                                    <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" name="reqHargaOutdoorSewa" id="reqHargaOutdoorSewa" style="width:40%; display: inline; text-align: right;" value="<?=numberToIna($reqHargaOutdoorSewa)?>" /> <label class="labelsumtotal">/ m2 / bulan</label>
                                </td>
                                <td style="width: 25%; text-align: right;">
                                    Service Charge Indoor
                                    <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" name="reqHargaIndoorService" id="reqHargaIndoorService" style="width:40%; display: inline; text-align: right;" value="<?=numberToIna($reqHargaIndoorService)?>" /> <label class="labelsumtotal">/ m2 / bulan</label>
                                </td>
                                <td style="width: 25%; text-align: right;">
                                    Service Charge Outdoor
                                    <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" name="reqHargaOutdoorService" id="reqHargaOutdoorService" style="width:40%; display: inline; text-align: right;" value="<?=numberToIna($reqHargaOutdoorService)?>" /> <label class="labelsumtotal">/ m2 / bulan</label>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>

                <!-- <table class="table">
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
                </table> -->

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
    obj["LUAS"] = "95.58";
    obj["KD_TARIF"] = "200000";
    obj["TARIF_SC"] = "92400";
    obj["text"] = "nama";
    tparam.push(obj);
    // console.log(tparam);return false;
    // untuk percobanaan
    // addmulti("I", tparam);
    // addmulti("O", tparam);*/

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
        // vdetilparam.tarifsc= item.TARIF_SC;
        vdetilparam.tarifsc= "92400";
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
    vtarifsc= setformat(vdetilparam["tarifsc"]);
    // console.log(vdetilparam);return false;
    // vluas= setformat(95.58);
    // vkdtarif= setformat(200000);

    if (vtipe=='I') 
    {
        vtable= ''
        +'<tr class="groupclass'+id+'">'
        +   '<td>'+kode+' - '+lantai+' <i style="cursor:pointer" class="fa fa-times-circle text-danger" aria-hidden="true" onclick="hapusgroupclass('+id+');"></i></td>'
        +   '<td>:</td>'
        +   '<td style="width:30%">'
        +       '<input type="hidden" name="vmode[]" value="luas_sewa_indoor" />'
        +       '<input type="hidden" name="vid[]" class="valsetid" value="'+id+'" />'
        +       '<input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control totalluasindoor" name="vnilai[]" placeholder="Isi Luas (m2)" data-options="required:true" style="width:85%; display: inline; text-align: right;" value="'+vluas+'" /> <label class="labeltotal">m2</label>'
        +   '</td>'
        +'</tr>';
        $("#luassewaindoor").append(vtable);
        hitungluas("totalluasindoor");

        vtable= ''
        +'<tr class="groupclass'+id+'">'
        +   '<td colspan="4">'
        +       kode+' - '+lantai
        +   '</td>'
        +'</tr>'
        +'<tr class="groupclass'+id+'">'
        +   '<td>'
        +       '<input type="hidden" name="vmode[]" value="tarif_sewa_unit_indoor" />'
        +       '<input type="hidden" name="vid[]" class="valsetid" value="'+id+'" />'
        +       '<input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control totalsewaunitindoor" name="vnilai[]" placeholder="Isi Rp/m2" data-options="required:true" style="width:65%; display: inline; text-align: right;" value="'+vkdtarif+'" /> <label class="labeltotal">Rp/m2</label>'
        +   '</td>'
        +   '<td>'
        +       '<input type="hidden" name="vmode[]" value="tarif_sewa_unit_indoor_diskon" />'
        +       '<input type="hidden" name="vid[]" class="valsetid" value="'+id+'" />'
        +       '<input type="text" class="vlxuangclass easyui-validatebox textbox form-control totalsewaunitindoordiskon" name="vnilai[]" placeholder="Isi %" data-options="required:true" style="width:65%; display: inline; text-align: right;" value="0" /> <label class="labeltotal">%</label>'
        +   '</td>'
        +   '<td>'
        +       '<input type="hidden" name="vmode[]" value="tarif_sewa_unit_indoor_after_diskon" />'
        +       '<input type="hidden" name="vid[]" class="valsetid" value="'+id+'" />'
        +       '<input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control totalsewaunitindoorafterdiskon" name="vnilai[]" placeholder="Isi Rp/m2" data-options="required:true" style="width:65%; display: inline; text-align: right;" /> <label class="labeltotal">Rp/m2</label>'
        +   '</td>'
        +   '<td>'
        +       '<input type="hidden" name="vmode[]" value="tarif_sewa_unit_indoor_harga" />'
        +       '<input type="hidden" name="vid[]" class="valsetid" value="'+id+'" />'
        +       '<input type="hidden" class="totalsewaunitindoorsewaluas" value="'+vluas+'" />'
        +       '<input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control totalsewaunitindoorharga" name="vnilai[]" style="display: inline; text-align: right;" />'
        +   '</td>'
        +'</tr>'
        ;
        $("#tarifinfosewaindoor").append(vtable);
        hitunghargasewa("totalsewaunitindoordiskon");

        vtable= ''
        +'<tr class="groupclass'+id+'">'
        +   '<td colspan="4">'
        +       kode+' - '+lantai
        +   '</td>'
        +'</tr>'
        +'<tr class="groupclass'+id+'">'
        +   '<td>'
        +       '<input type="hidden" name="vmode[]" value="tarif_sewa_sc_indoor" />'
        +       '<input type="hidden" name="vid[]" class="valsetid" value="'+id+'" />'
        +       '<input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control totalsewascindoor" name="vnilai[]" placeholder="Isi Rp/m2" data-options="required:true" style="width:65%; display: inline; text-align: right;" value="'+vtarifsc+'" /> <label class="labeltotal">Rp/m2</label>'
        +   '</td>'
        +   '<td>'
        +       '<input type="hidden" name="vmode[]" value="tarif_sewa_sc_indoor_diskon" />'
        +       '<input type="hidden" name="vid[]" class="valsetid" value="'+id+'" />'
        +       '<input type="text" class="vlxuangclass easyui-validatebox textbox form-control totalsewascindoordiskon" name="vnilai[]" placeholder="Isi %" data-options="required:true" style="width:65%; display: inline; text-align: right;" value="0" /> <label class="labeltotal">%</label>'
        +   '</td>'
        +   '<td>'
        +       '<input type="hidden" name="vmode[]" value="tarif_sewa_sc_indoor_after_diskon" />'
        +       '<input type="hidden" name="vid[]" class="valsetid" value="'+id+'" />'
        +       '<input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control totalsewascindoorafterdiskon" name="vnilai[]" placeholder="Isi Rp/m2" data-options="required:true" style="width:65%; display: inline; text-align: right;" /> <label class="labeltotal">Rp/m2</label>'
        +   '</td>'
        +'</tr>'
        ;
        $("#tarifinfosewascindoor").append(vtable);
        hitunghargasewa("totalsewascindoordiskon");
    }
    else
    {
        vtable= ''
        +'<tr class="groupclass'+id+'">'
        +   '<td>'+kode+' - '+lantai+'</td>'
        +   '<td>:</td>'
        +   '<td>'
        +       '<input type="hidden" name="vmode[]" value="luas_sewa_outdoor" />'
        +       '<input type="hidden" name="vid[]" class="valsetid" value="'+id+'" />'
        +       '<input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control totalluasoutdoor" name="vnilai[]" placeholder="Isi Luas (m2)" data-options="required:true" style="width:85%; display: inline; text-align: right;" value="'+vluas+'" /> <label class="labeltotal">m2</label>'
        +   '</td>'
        +'</tr>';
        $("#luassewaoutdoor").append(vtable);
        hitungluas("totalluasoutdoor");

        vtable= ''
        +'<tr class="groupclass'+id+'">'
        +   '<td colspan="4">'
        +       kode+' - '+lantai
        +   '</td>'
        +'</tr>'
        +'<tr class="groupclass'+id+'">'
        +   '<td>'
        +       '<input type="hidden" name="vmode[]" value="tarif_sewa_unit_outdoor" />'
        +       '<input type="hidden" name="vid[]" class="valsetid" value="'+id+'" />'
        +       '<input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control totalsewaunitoutdoor" name="vnilai[]" placeholder="Isi Rp/m2" data-options="required:true" style="width:65%; display: inline; text-align: right;" value="'+vkdtarif+'" /> <label class="labeltotal">Rp/m2</label>'
        +   '</td>'
        +   '<td>'
        +       '<input type="hidden" name="vmode[]" value="tarif_sewa_unit_outdoor_diskon" />'
        +       '<input type="hidden" name="vid[]" class="valsetid" value="'+id+'" />'
        +       '<input type="text" class="vlxuangclass easyui-validatebox textbox form-control totalsewaunitoutdoordiskon" name="vnilai[]" placeholder="Isi %" data-options="required:true" style="width:65%; display: inline; text-align: right;" value="0" /> <label class="labeltotal">%</label>'
        +   '</td>'
        +   '<td>'
        +       '<input type="hidden" name="vmode[]" value="tarif_sewa_unit_outdoor_after_diskon" />'
        +       '<input type="hidden" name="vid[]" class="valsetid" value="'+id+'" />'
        +       '<input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control totalsewaunitoutdoorafterdiskon" name="vnilai[]" placeholder="Isi Rp/m2" data-options="required:true" style="width:65%; display: inline; text-align: right;" /> <label class="labeltotal">Rp/m2</label>'
        +   '</td>'
        +   '<td>'
        +       '<input type="hidden" name="vmode[]" value="tarif_sewa_unit_outdoor_harga" />'
        +       '<input type="hidden" name="vid[]" class="valsetid" value="'+id+'" />'
        +       '<input type="hidden" class="totalsewaunitoutdoorsewaluas" value="'+vluas+'" />'
        +       '<input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control totalsewaunitoutdoorharga" name="vnilai[]" style="display: inline; text-align: right;" />'
        +   '</td>'
        +'</tr>'
        ;
        $("#tarifinfosewaoutdoor").append(vtable);
        hitunghargasewa("totalsewaunitoutdoordiskon");

        vtable= ''
        +'<tr class="groupclass'+id+'">'
        +   '<td colspan="4">'
        +       kode+' - '+lantai
        +   '</td>'
        +'</tr>'
        +'<tr class="groupclass'+id+'">'
        +   '<td>'
        +       '<input type="hidden" name="vmode[]" value="tarif_sewa_sc_outdoor" />'
        +       '<input type="hidden" name="vid[]" class="valsetid" value="'+id+'" />'
        +       '<input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control totalsewascoutdoor" name="vnilai[]" placeholder="Isi Rp/m2" data-options="required:true" style="width:65%; display: inline; text-align: right;" value="'+vtarifsc+'" /> <label class="labeltotal">Rp/m2</label>'
        +   '</td>'
        +   '<td>'
        +       '<input type="hidden" name="vmode[]" value="tarif_sewa_sc_outdoor_diskon" />'
        +       '<input type="hidden" name="vid[]" class="valsetid" value="'+id+'" />'
        +       '<input type="text" class="vlxuangclass easyui-validatebox textbox form-control totalsewascoutdoordiskon" name="vnilai[]" placeholder="Isi %" data-options="required:true" style="width:65%; display: inline; text-align: right;" value="0" /> <label class="labeltotal">%</label>'
        +   '</td>'
        +   '<td>'
        +       '<input type="hidden" name="vmode[]" value="tarif_sewa_sc_outdoor_after_diskon" />'
        +       '<input type="hidden" name="vid[]" class="valsetid" value="'+id+'" />'
        +       '<input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control totalsewascoutdoorafterdiskon" name="vnilai[]" placeholder="Isi Rp/m2" data-options="required:true" style="width:65%; display: inline; text-align: right;" /> <label class="labeltotal">Rp/m2</label>'
        +   '</td>'
        +'</tr>'
        ;
        $("#tarifinfosewascoutdoor").append(vtable);
        hitunghargasewa("totalsewascoutdoordiskon");
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
            hitunghargasewa("totalsewaunitindoordiskon");
        });

        $(".totalsewaunitoutdoordiskon").keyup(function() {
            hitunghargasewa("totalsewaunitoutdoordiskon");
        });

        $(".totalsewascindoordiskon").keyup(function() {
            hitunghargasewa("totalsewascindoordiskon");
        });

        $(".totalsewascoutdoordiskon").keyup(function() {
            hitunghargasewa("totalsewascoutdoordiskon");
        });


        // default awal
        // hitungluas("totalluasindoor");
        // hitungluas("totalluasoutdoor");

    });
}

function hapusgroupclass(vid)
{
    $.messager.confirm('Konfirmasi','Yakin menghapus data terpilih ?',function(r){
        if (r){
            $(".groupclass"+vid).remove();
            hitungluas("totalluasoutdoor");
            hitungluas("totalluasindoor");
        }
    });
}

function hitunghargasewa(vmode)
{
    vtotal= 0;
    vindex= 0;
    $("."+vmode).each(function(){
        infoid= $(this).attr('id');
        infoval= $(this).val();
        infoval = infoval ? infoval : 0;
        vdiskon= FormatAngkaNumber(infoval);

        vawalharga= 0;
        if(vmode == "totalsewaunitindoordiskon")
        {
            vawalharga= $(this).closest('tr').find('.totalsewaunitindoor').val();
        }
        else if(vmode == "totalsewaunitoutdoordiskon")
        {
            vawalharga= $(this).closest('tr').find('.totalsewaunitoutdoor').val();
        }
        else if(vmode == "totalsewascindoordiskon")
        {
            vawalharga= $(this).closest('tr').find('.totalsewascindoor').val();
        }
        else if(vmode == "totalsewascoutdoordiskon")
        {
            vawalharga= $(this).closest('tr').find('.totalsewascoutdoor').val();
        }

        vawalharga= FormatAngkaNumber(vawalharga);
        vawalharga= notnullval(vawalharga);

        vafterdiskon= vawalharga - ( (vdiskon / 100) * vawalharga );

        if(vmode == "totalsewaunitindoordiskon")
        {
            $(this).closest('tr').find('.totalsewaunitindoorafterdiskon').val(setformat(vafterdiskon));
        }
        else if(vmode == "totalsewaunitoutdoordiskon")
        {
            $(this).closest('tr').find('.totalsewaunitoutdoorafterdiskon').val(setformat(vafterdiskon));
        }
        else if(vmode == "totalsewascindoordiskon")
        {
            $(this).closest('tr').find('.totalsewascindoorafterdiskon').val(setformat(vafterdiskon));
        }
        else if(vmode == "totalsewascoutdoordiskon")
        {
            $(this).closest('tr').find('.totalsewascoutdoorafterdiskon').val(setformat(vafterdiskon));
        }

        if(vmode == "totalsewaunitindoordiskon" || "totalsewaunitoutdoordiskon")
        {
            vsewaluas= 0;
            if(vmode == "totalsewaunitindoordiskon")
            {
                vsewaluas= $(this).closest('tr').find('.totalsewaunitindoorsewaluas').val();
            }
            else if(vmode == "totalsewaunitoutdoordiskon")
            {
                vsewaluas= $(this).closest('tr').find('.totalsewaunitoutdoorsewaluas').val();
            }

            vsewaluas= FormatAngkaNumber(vsewaluas);
            vsewaluas= notnullval(vsewaluas);

            vtotalharga= vafterdiskon * vsewaluas;
            if(vmode == "totalsewaunitindoordiskon")
            {
                $(this).closest('tr').find('.totalsewaunitindoorharga').val(setformat(vtotalharga));
            }
            else if(vmode == "totalsewaunitoutdoordiskon")
            {
                $(this).closest('tr').find('.totalsewaunitoutdoorharga').val(setformat(vtotalharga));
            }
        }
    });

    vmodetotal= "";
    if(vmode == "totalsewaunitindoordiskon")
    {
        vmodetotal= "totalsewaunitindoorharga";
    }
    else if(vmode == "totalsewaunitoutdoordiskon")
    {
        vmodetotal= "totalsewaunitoutdoorharga";
    }
    else if(vmode == "totalsewascindoordiskon")
    {
        vmodetotal= "totalsewascindoorafterdiskon";
    }
    else if(vmode == "totalsewascoutdoordiskon")
    {
        vmodetotal= "totalsewascoutdoorafterdiskon";
    }

    if(vmodetotal !== "")
    {
        vtotal= 0;
        $("."+vmodetotal).each(function(){
            infoval= $(this).val();
            infoval = infoval ? infoval : 0;
            infoval= FormatAngkaNumber(infoval);

            vtotal= parseFloat(vtotal) + parseFloat(infoval);
        });

        vtotal= setformat(vtotal);
        if(vmode == "totalsewaunitindoordiskon")
        {
            $("#reqHargaIndoorSewa").val(vtotal);
        }
        else if(vmode == "totalsewaunitoutdoordiskon")
        {
            $("#reqHargaOutdoorSewa").val(vtotal);
        }
        else if(vmode == "totalsewascindoordiskon")
        {
            $("#reqHargaIndoorService").val(vtotal);
        }
        else if(vmode == "totalsewascoutdoordiskon")
        {
            $("#reqHargaOutdoorService").val(vtotal);
        }
    }
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
            // $(this).closest('tr').find('.valsetid').val(vindex);
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

function submitForm(reqStatusData){
    
    $("#reqStatusData").val(reqStatusData);

    $('#ff').form('submit',{
        url:'web/trloo_json/add',
        onSubmit:function(){
            return $(this).form('enableValidation').form('validate');
        },
        success:function(data){
            console.log(data);return false;
            $.messager.alertLink('Info', data, 'info', "main/index/loo_add");
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

    .labelsumtotal{
        width: 40%;
    }
</style>