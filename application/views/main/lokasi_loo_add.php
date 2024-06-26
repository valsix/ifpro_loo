
<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");


$this->load->model("LokasiLoo");
$this->load->model("UtilityCharge");
$lokasi_loo = new LokasiLoo();

$reqId = $this->input->get("reqId");

if($reqId == ""){
$reqMode = "insert";
}
else
{
    $reqMode = "ubah";
    $lokasi_loo->selectByParams(array("A.LOKASI_LOO_ID" => $reqId));
    $lokasi_loo->firstRow();
    
    $reqId= $lokasi_loo->getField("LOKASI_LOO_ID");
    $reqKode= $lokasi_loo->getField("KODE");
    $reqNama= $lokasi_loo->getField("NAMA");
    $reqServiceCharge= currencyToPage($lokasi_loo->getField("SERVICE_CHARGE"), false);
    $reqDeskripsi= $lokasi_loo->getField("DESKRIPSI");
    $reqUtilityCharge= $lokasi_loo->getField("Utility_Charge");
    $reqIdUtility= $lokasi_loo->getField("Utility_Charge");
    
    $reqEmail= $lokasi_loo->getField("EMAIL");
    $reqTelepon= $lokasi_loo->getField("TELEPON");
    $reqNamaPj= $lokasi_loo->getField("NAMA_PJ");
    $reqNamaBank= $lokasi_loo->getField("NAMA_BANK");
    $reqRekeningBank= $lokasi_loo->getField("REKENING_BANK");
    $reqAtasNamaBank= $lokasi_loo->getField("ATAS_NAMA_BANK");
    $reqNamaCabang= $lokasi_loo->getField("NAMA_CABANG");
    $reqTerletak= $lokasi_loo->getField("TERLETAK");
    $reqLokasiGedung= $lokasi_loo->getField("LOKASI_GEDUNG");
    $reqDasarHukum= $lokasi_loo->getField("DASAR_HUKUM");

    $reqIdUtilityarr= array();
    $reqValName='';
    if ($reqIdUtility) 
    {
        $reqIdUtilityarr= explode(",", $reqIdUtility);

        $lokasi_loo->selectByParamsLooUtilityCharge(array("A.LOKASI_LOO_ID" => $reqId),-1,-1," AND A.UTILITY_CHARGE_ID IN (".$reqIdUtility.")");

        $reqValName= $reqDataName= "";
        while($lokasi_loo->nextRow())
        {
            $reqDataName= $lokasi_loo->getField("NAMA_UTILITY_CHARGE");

            if(empty($reqValName))
                $reqValName= $reqDataName;
            else
            {
                $reqValName= $reqValName.",".$reqDataName;
            }
        }
    }
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
//         url:'web/lokasi_loo_json/add',
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
//             $.messager.alertLink('Info', data, 'info', "app/loadUrl/admin/lokasi_loo");    
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
<!-- <script src="<?=base_url()?>lib/multifile-master/jquery.MultiFile.js"></script> -->
<script>
// wait for document to load
$(function(){
    
    // invoke plugin
    /*$('#reqLampiran').MultiFile({
    onFileChange: function(){
        console.log(this, arguments);
    }
    });*/

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
                            <td>Service Charge</td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqServiceCharge" class="vlxuangclass easyui-validatebox textbox form-control totalluasoutdoor" required name="reqServiceCharge"  value="<?=$reqServiceCharge ?>" data-options="required:true" style="width:10%; display: inline; text-align: right;" />
                                <label> Rp / m² / bulan</label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Lampiran I
                            </td>
                            <td>:</td>
                            <td>
                                <div class="kotak-dokumen">
                                    <div class="kontak">
                                        <div class="inner-lampiran">
                                            <input id ="reqFile" name="reqLinkFile[]" type="file" maxlength="10" class="multi maxsize-10240" value="" />
                                            <?
                                            $set_attachement = new LokasiLoo();
                                            $set_attachement->selectByParamsAttachment(array("A.LOKASI_LOO_ID" => (int)$reqId), -1,-1, " AND COALESCE(NULLIF(A.VMODE, ''), NULL) IS NULL");
                                            while ($set_attachement->nextRow()) {
                                                $attach_id= $set_attachement->getField("LOKASI_LOO_ATTACHMENT_ID");
                                            ?>
                                                
                                                <div class="MultiFile-label">
                                                    <input type="hidden" name="reqLinkFileTemp[]" value="<?= $set_attachement->getField("ATTACHMENT") ?>" />
                                                    <input type="hidden" name="reqLinkFileTempNama[]" value="<?= $set_attachement->getField("NAMA") ?>" />
                                                    <input type="hidden" name="reqLinkFileTempTipe[]" value="<?= $set_attachement->getField("TIPE") ?>" />
                                                    <input type="hidden" name="reqLinkFileTempSize[]" value="<?= $set_attachement->getField("UKURAN") ?>" />
                                                    <a class="MultiFile-remove"><i class="fa fa-times-circle" onclick="$(this).parent().parent().remove();"></i></a>
    
                                                    <?
                                                    $arrexcept= array("xlsx", "xls", "doc", "docx", "ppt", "pptx", "txt");
                                                    //$arrexcept= array("xlsx", "xls", "doc", "docx", "txt");
                                                    if(in_array(strtolower($set_attachement->getField("TIPE")), $arrexcept))
                                                    {
                                                    ?>
                                                    <?= $set_attachement->getField("NAMA") ?>
                                                    <a onClick="down('<?=$attach_id?>', 'lokasiloo')" >
                                                        <i style="cursor: pointer;" class="fa fa-download" ></i>
                                                    </a>
                                                    <?
                                                    }
                                                    else
                                                    {
                                                    ?>
                                                    <?= $set_attachement->getField("NAMA") ?>
                                                    <a onClick="parent.openAdd('<?= base_url()."uploadslokasiloo/".$reqId."/".$set_attachement->getField("ATTACHMENT") ?>')" >
                                                        <i style="cursor: pointer;" class="fa fa-eye" ></i>
                                                    </a>
                                                    |
                                                    <a onClick="down('<?=$attach_id?>', 'lokasiloo')" >
                                                        <i style="cursor: pointer;" class="fa fa-download" ></i>
                                                    </a>
                                                    <?
                                                    }
                                                    ?>
                                                </div>
                                            <?
                                            }
                                            ?>
                                            
                                            <div class="small">Ukuran file maksimum yang diizinkan adalah 10 MB & Jenis file diterima: world, excel, ppt, pdf, jpg, jpeg, png</div>
                                            
                                        </div>
                                    </div>
    
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Deskripsi</td>
                            <td>:</td>
                            <td>
                                <textarea name="reqDeskripsi" class="easyui-validatebox textbox form-control" style="width:90%; height:100px"><?=$reqDeskripsi ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>Terletak di</td>
                            <td>:</td>
                            <td>
                                <textarea name="reqTerletak" class="easyui-validatebox textbox form-control" style="width:90%; height:50px"><?=$reqTerletak?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>Lokasi Gedung</td>
                            <td>:</td>
                            <td>
                                <textarea name="reqLokasiGedung" class="easyui-validatebox textbox form-control" style="width:90%; height:50px"><?=$reqLokasiGedung?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>Dasar Hukum</td>
                            <td>:</td>
                            <td>
                                <textarea name="reqDasarHukum" class="easyui-validatebox textbox form-control" style="width:90%; height:100px"><?=$reqDasarHukum?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>Utility Charge</td>
                            <td>:</td>
                            <td>
                                <div class="form-group">
                                    <ul class="list-unstyled">
                                        <?
                                        $set= new UtilityCharge();
                                        $set->selectByParams(array(), -1, -1);
                                        $i = 0;
                                        while($set->nextRow())
                                        {
                                            $setid= $set->getField("UTILITY_CHARGE_ID");
                                            $setnama= $set->getField("NAMA");

                                            $checked= '';
                                            if(in_array($setid, $reqIdUtilityarr))
                                            {
                                                $checked= 'checked';
                                            }
                                        ?>
                                            <li class="custom-control custom-checkbox">
                                                <input class="custom-control-input ng-untouched ng-pristine ng-valid" type="checkbox" id="reqRadio<?=$setid?>" <?=$checked?>>
                                                <label class="custom-control-label" id="reqRadioNama<?=$setid?>" for="reqRadio<?=$setid?>"><?=$setnama?></label>
                                            </li>
                                        <?
                                        }
                                        ?>
                                    </ul>
                                </div>

                                <input class="easyui-validatebox" required type="hidden" id="reqNamaUtility" name="reqNamaUtility" value="<?=$reqValName?>" />
                                <input class="easyui-validatebox" required type="hidden" id="reqIdUtility" name="reqIdUtility" value="<?=$reqIdUtility?>" />
                                <div id="infodetilbalas">
                                    <?
                                    if ($reqId) {
                                        $lokasi_loo->selectByParamsLooUtilityCharge(array("A.LOKASI_LOO_ID" => $reqId));

                                        $is = 0;
                                        ?>
                                        <ol>
                                        <?
                                        while($lokasi_loo->nextRow())
                                        {
                                            $setid= $lokasi_loo->getField("UTILITY_CHARGE_ID");
                                            $setnama= $lokasi_loo->getField("NAMA_UTILITY_CHARGE");
                                            $setharga= $lokasi_loo->getField("HARGA");
                                            $setket= $lokasi_loo->getField("KETERANGAN");
                                            ?>
                                                <li>
                                                    <table>
                                                        <tr>
                                                            <td style="width: 50px;"><?=$setnama?></td>
                                                            <td style="width: 150px;"><input type='text' id='reqSCHarga<?=$setnama?>' class='vlxuangclass easyui-validatebox textbox form-control totalluasoutdoor' required name='reqSCHarga[]' value='<?=$setharga?>' data-options='required:true' style='width:80%; display: inline; text-align: right;' /><input type='hidden' id='reqSCId<?=$setid?>' name='reqSCId[]' value='<?=$setid?>' />
                                                                <!-- <label><?=$setket?></label> -->
                                                            </td>
                                                        </tr> 
                                                    </table> 
                                                </li>
                                            <?
                                            $is++;
                                        }
                                        ?>
                                        </ol>
                                        <?
                                    }
                                        
                                    ?>
                                    
                                </div>
                            </td>
                        </tr> 
                        <!-- <tr>
                            <td>Utility Charge</td>
                            <td>:</td>
                            <td>
                                <?
                                $reqUtilityCharge = str_replace(",", "','", $reqUtilityCharge);
                                ?>
                                <input type="text" name="reqUtilityCharge" class="easyui-combotree"  id="reqUtilityCharge" 
                                        data-options="width:'300',valueField:'id',
                                        textField:'text', editable:false,url:'combo_json/comboUtilityCharge',multiple:true,value:['<?=$reqUtilityCharge?>'],
                                            onClick: function(node){
                                                var values= $('#reqUtilityCharge').combotree('getValues');
                                                // $('#reqUserGroupId').val(rec.id);
                                                $('#reqUtilityChargeId').val(values);
                                            },
                                            onCheck: function(node, checked){
                                                var values= $('#reqUtilityCharge').combotree('getValues');
                                                // $('#reqUserGroupId').val(rec.id);
                                                $('#reqUtilityChargeId').val(values);
                                            }" required />
                                <input type="hidden" name="reqUtilityChargeId" id="reqUtilityChargeId"   value="<?=$reqUtilityChargeId?>">
                            </td>
                        </tr>  -->
                        <tr>
                            <th colspan="3">Transfer Bank</th>
                        </tr>
                        <tr>
                            <td>Nama Bank </td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqNamaBank" class="easyui-validatebox textbox form-control" required name="reqNamaBank"  value="<?=$reqNamaBank ?>" data-options="required:true" style="width:90%" />
                            </td>
                        </tr>
                        <tr>
                            <td>Rekening Bank </td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqRekeningBank" class="easyui-validatebox textbox form-control" required name="reqRekeningBank"  value="<?=$reqRekeningBank ?>" data-options="required:true" style="width:90%" />
                            </td>
                        </tr>
                        <tr>
                            <td>Atas Nama Bank </td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqAtasNamaBank" class="easyui-validatebox textbox form-control" required name="reqAtasNamaBank"  value="<?=$reqAtasNamaBank ?>" data-options="required:true" style="width:90%" />
                            </td>
                        </tr>
                        <tr>
                            <td>Cabang </td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqNamaCabang" class="easyui-validatebox textbox form-control" required name="reqNamaCabang"  value="<?=$reqNamaCabang ?>" data-options="required:true" style="width:90%" />
                            </td>
                        </tr>
                        <tr>
                            <td>Email </td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqEmail" class="easyui-validatebox textbox form-control" required name="reqEmail"  value="<?=$reqEmail ?>" data-options="required:true" style="width:90%" />
                            </td>
                        </tr>
                        <tr>
                            <td>No. Telepon </td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqTelepon" class="easyui-validatebox textbox form-control" required name="reqTelepon"  value="<?=$reqTelepon ?>" data-options="required:true" style="width:90%" />
                            </td>
                        </tr>
                        <tr>
                            <td>Nama PJ </td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqNamaPj" class="easyui-validatebox textbox form-control" required name="reqNamaPj"  value="<?=$reqNamaPj ?>" data-options="required:true" style="width:90%" />
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
    $(function(){
        $('input[id^="reqRadio"]').change(function(e) {
            infoid= $(this).attr('id');
            infoid= infoid.split('reqRadio');
            infoid= infoid[1];
            infonama= $("#reqRadioNama"+infoid).text();

            reqNamaUtility= $("#reqNamaUtility").val();
            reqIdUtility= $("#reqIdUtility").val();

            if($(this).prop('checked')) {
                if(reqNamaUtility == "")
                    reqNamaUtility= infonama;
                else
                    reqNamaUtility= reqNamaUtility+","+infonama;


                if(reqIdUtility == "")
                    reqIdUtility= infoid;
                else
                    reqIdUtility= reqIdUtility+","+infoid;
            }
            else
            {
                reqNamaUtility= reqNamaUtility.replace(","+infonama, "");
                reqNamaUtility= reqNamaUtility.replace(infonama+",", "");
                reqNamaUtility= reqNamaUtility.replace(infonama, "");

                reqIdUtility= reqIdUtility.replace(","+infoid, "");
                reqIdUtility= reqIdUtility.replace(infoid+",", "");
                reqIdUtility= reqIdUtility.replace(infoid, "");
            }
            $("#reqNamaUtility").val(reqNamaUtility);
            $("#reqIdUtility").val(reqIdUtility);

            infoiddata= reqIdUtility.split(",");
            infotextdata= reqNamaUtility.split(",");
            infolabel= "infodetilbalas";

            infodetiltujuan= "<ol>";
            for(i=0; i < infotextdata.length; i++)
            {
                if(infotextdata[i] !== "")
                {
                    vals= $('#reqSCHarga'+infotextdata[i]).val();
                    if (vals==undefined||vals=='') 
                    {
                        vals= '';
                    }

                    infodetiltujuan+= "<li><table><tr><td style='width: 50px;'>"+infotextdata[i]+"</td><td style='width: 150px;'><input type='text' id='reqSCHarga"+infotextdata[i]+"' class='vlxuangclass easyui-validatebox textbox form-control totalluasoutdoor' required name='reqSCHarga[]' value='"+vals+"' data-options='required:true' style='width:80%; display: inline; text-align: right;' /><input type='hidden' id='reqSCId"+infoiddata[i]+"' name='reqSCId[]' value='"+infoiddata[i]+"' /></td></tr></table></li>";
                }
            }
            infodetiltujuan+= "</ol>";

            $("#"+infolabel).empty();
            $("#"+infolabel).html(infodetiltujuan);
        });
    });

$(document).ready(function() {
    
});

function down(attach_id, vmode)
{
    window.open("downloi?reqMode="+vmode+"&reqAttachId="+attach_id, 'Cetak');
}

function submitForm(){
    
    $('#ff').form('submit',{
        url:'web/lokasi_loo_json/add',
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
            $.messager.alertLink('Info', data, 'info', "main/index/lokasi_loo");  
        }
    });
}
function clearForm(){
    $('#ff').form('clear');
}
            
</script>