
<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");


$this->load->model("LokasiLoo");
$this->load->model("LokasiLooTransaksi");
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
    
}

$arrcombolantai=array();
$combolantai = new LokasiLooTransaksi();
$combolantai->selectByParamsComboLantai(array());
while($combolantai->nextRow()){
    array_push($arrcombolantai,array("id"=>$combolantai->getField('LANTAI_LOO_ID') , "text"=>$combolantai->getField('NAMA')));
}
// print_r($arrcombolantai) ;exit;
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
    <div id="judul-popup">Kelola Lokasi LOO</div>
    <div id="konten">
        <div id="popup-tabel2">

            <form id="ff" method="post" novalidate enctype="multipart/form-data">
                <table class="table">
                    <thead>
                        <tr>
                            <td>Kode</td>
                            <td>:</td>
                            <td><?=$reqKode ?></td>
                        </tr>
                        <tr>
                            <td>Nama</td>
                            <td>:</td>
                            <td><?=$reqNama ?></td>
                        </tr>
                        <tr>
                            <td>Service Charge</td>
                            <td>:</td>
                            <td><?=$reqServiceCharge ?> Rp / m² / bulan</td>
                        </tr>
                        <tr>
                            <td>Deskripsi</td>
                            <td>:</td>
                            <td><?=$reqDeskripsi ?></td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <table style="width: 100%; padding:1px" class="table" id='myTable'>
                                    <tr>
                                        <td style="width: 30%;">
                                            Area 
                                            <a id="btnAdd" title="Tambah"><img src="<?= base_url() ?>images/icon-tambah.png" /></a>
                                        </td>
                                        <td style="width: 20%;">Lantai</td>
                                        <td style="width: 10%;">Minimum</td>
                                        <td style="width: 10%;">Maximum</td>
                                        <td style="width: 25%;">Nilai</td>
                                        <td style="width: 5%;"></td>
                                    </tr>
                                    <?
                                    $table = new LokasiLooTransaksi();
                                    $table->selectByParams(array("LOKASI_LOO_ID" => $reqId));
                                    while($table->nextRow()){
                                        ?>
                                        <tr>
                                            <td>
                                                <input type="hidden" name="reqLantaiLooDetilId[]" value="<?=$table->getField("LANTAI_LOO_DETIL_ID")?>" />
                                                <select class="easyui-validatebox textbox form-control" name="reqLantai[]">
                                                <?for ($i=0; $i<count($arrcombolantai);$i++){?>
                                                    <option value="<?=$arrcombolantai[$i]['id']?>" 
                                                        <?if($table->getField("LANTAI_LOO_ID") == $arrcombolantai[$i]['id']){ echo "selected";}?>>
                                                        <?=$arrcombolantai[$i]['text']?>        
                                                    </option>
                                                <?}?>
                                                </select>
                                            </td>    
                                            <td>
                                                <select class="easyui-validatebox textbox form-control" name="reqArea[]">
                                                    <option value="I" <?if($table->getField("AREA")=='I'){echo "selected";}?>>Indoor</option>
                                                    <option value="O" <?if($table->getField("AREA")=='O'){echo "selected";}?>>Outdoor</option>
                                                    <option value="CL" <?if($table->getField("AREA")=='CL'){echo "selected";}?>>Casual Leasing</option>
                                                </select>
                                            </td>
                                            <td> 
                                                <input type="text" name="reqMin[]" class="easyui-validatebox textbox form-control" oninput="this.value = this.value.replace(/[^0-9.]/g, '')" value="<?=$table->getField("AWAL")?>"/>
                                            </td>
                                            <td> 
                                                <input type="text" name="reqMax[]" class="easyui-validatebox textbox form-control" oninput="this.value = this.value.replace(/[^0-9.]/g, '')" value="<?=$table->getField("AKHIR")?>"/>
                                            </td>
                                            <td> 
                                                <input type="text" name="reqNilai[]" class="vlxuangclass easyui-validatebox textbox form-control" value="<?=currencyToPage($table->getField("NILAI"),false)?>"/>
                                            </td>
                                            <td>
                                                <a onclick="btnDelete(<?=$table->getField("LANTAI_LOO_DETIL_ID")?>)" title="Hapus"><img src="<?= base_url() ?>images/icon-hapus.png" /></a>                    
                                            </td>
                                        </tr>
                                    <?}?>
                                </table>
                            </td>
                        </tr>
                    </thead>
                </table>

                <input type="hidden" name="reqId" value="<?=$reqId?>" />
                <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
                <div style="text-align:center;padding:5px">
                    <a href="javascript:void(0)" class="btn btn-primary" onclick="submitForm()">Submit</a>
                </div>
            </form>

        </div>
    </div>
</body>
</html>


<script>

$(document).ready(function() {
    
});

function submitForm(){
    
    $('#ff').form('submit',{
        url:'web/lokasi_loo_json/addTransksi',
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

$('#btnAdd').on('click', function() {
    areatable=`
        <select class="easyui-validatebox textbox form-control" name="reqLantai[]"> `;
    <?for ($i=0; $i<count($arrcombolantai);$i++){?>
        areatable=areatable+`            
                <option value="<?=$arrcombolantai[$i]['id']?>"><?=$arrcombolantai[$i]['text']?></option>
        `
    <?}?>
    areatable=areatable+`            
        </select>
    `
    table=`
    <tr>
        <td>`+areatable+`
        </td>
        <td>
            <input type="hidden" name="reqLantaiLooDetilId[]"/>
            <select class="easyui-validatebox textbox form-control" name="reqArea[]">
                <option value="I">Indoor</option>
                <option value="O">Outdoor</option>
                <option value="CL">Casual Leasing</option>
            </select>
        </td>
        <td> <input type="text" name="reqMin[]" class="easyui-validatebox textbox form-control" oninput="this.value = this.value.replace(/[^0-9.]/g, '')"/></td>
        <td> <input type="text" name="reqMax[]" class="easyui-validatebox textbox form-control" oninput="this.value = this.value.replace(/[^0-9.]/g, '')"/></td>
        <td> <input type="text" name="reqNilai[]" class="vlxuangclass easyui-validatebox textbox form-control"/></td>
    </tr>
    `;

   $('#myTable tr:last').after(table);

   var vlxformat = function(num){
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

});

function btnDelete(val){
    deleteData("web/lokasi_loo_json/deleteTransaksi", val);
}
            
</script>