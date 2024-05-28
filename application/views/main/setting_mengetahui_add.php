
<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");


$this->load->model("SettingMengetahui");
$set = new SettingMengetahui();

$reqId = $this->input->get("reqId");

if($reqId == ""){
    $reqMode = "insert";
}
else
{
    $reqMode = "ubah";
    $set->selectByParams(array("A.SETTING_MENGETAHUI_ID" => $reqId));
    $set->firstRow();
    
    $reqId           = $set->getField("SETTING_MENGETAHUI_ID");
    $reqNama                            = $set->getField("NAMA");
    $reqStatus                     = $set->getField("STATUS");
    
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
	<div id="judul-popup">Kelola Setting Mengetahui</div>
	<div id="konten">
    	<div id="popup-tabel2">

            <form id="ff" method="post" novalidate enctype="multipart/form-data">
                <table class="table">
                    <thead>
                    	<tr>
                            <td>Nama </td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqNama" class="easyui-validatebox textbox form-control" required name="reqNama"  value="<?=$reqNama ?>" data-options="required:true" style="width:50%" />
                            </td>
                        </tr>
                        <tr>
                            <td>Status </td>
                            <td>:</td>
                            <td>
                                <select style="width:10%" class="easyui-validatebox textbox form-control"  name="reqStatus">
                                    <?
                                    $selected="";
                                    if($reqStatus)
                                        $selected='selected';
                                    ?>
                                    <option value="">Aktif</option>
                                    <option value="1"<?=$selected?>>Tidak Aktif</option>
                                </select>
                            </td>
                        </tr>
                    </thead>
                </table>
                <div><a href="javascript:void(0)" class="btn btn-primary" onclick="AddDetil()">Tambah</a></div>
                <br>

                <table class="table" id="tbdetil">
                    <thead>
                        <tr>
                            <th style="width: 10%">Urut </th>
                            <th>Satuan Kerja </th>
                            <th style="width: 10%">Aksi </th>
                        </tr>
                    </thead>
                    <tbody id="detil">

                        <?
                        $setinfo= new SettingMengetahui();
                        $setinfo->selectByParamsDetil(array("A.SETTING_MENGETAHUI_ID"=>(int)$reqId), -1, -1);
                        while($setinfo->nextRow())
                        {
                            $reqIdDetil= $setinfo->getField("SETTING_MENGETAHUI_DETIL_ID");
                            $reqUrut= $setinfo->getField("URUT");
                            $reqSatkerId= $setinfo->getField("SATUAN_KERJA_ID");
                        ?>
                        <tr>
                            <td><input type="text" id="reqUrut" class="easyui-validatebox textbox form-control" required name="reqUrut[]" id="reqUrut" value="<?=$reqUrut?>" data-options="required:true" style="width:50%" /></td>
                            <td>
                                <input type="text" id="reqSatkerId" class="easyui-combotree" name="reqSatkerId[]" required data-options="width:'500'
                                , panelHeight:'120'
                                , valueField:'id'
                                , textField:'text'
                                , url:'web/satuan_kerja_json/combotreesatker/'
                                , prompt:'Tentukan Untuk...'," value="<?=$reqSatkerId?>"
                                />
                            </td>
                            <td style="width: 5%">
                                <span style='background-color: red; padding: 5px; border-radius: 5px;top: 50%;position: relative;'><a onclick='HapusBaris("<?=$reqId?>","<?=$reqIdDetil?>")' class='btn-remove'><i class='fa fa-trash fa-lg' style='color: white;' aria-hidden='true'></i></a></span>
                            </td>
                        </tr>
                        <?
                        }
                        ?>
                    </tbody>
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

$("#tbdetil").on("click", ".btn-remove", function(){
    $(this).closest('tr').remove();
});

$('#reqUrut').bind('keyup paste', function(){
   var numeric = $(this).val().replace(/\D/g, '');
   $(this).val(numeric);
});

function HapusBaris(id,iddetil)
{
    $.messager.confirm('Konfirmasi','Yakin menghapus baris ini ?',function(r){
        if (r){
            var jqxhr = $.get( 'web/setting_mengetahui_json/deletedetil?reqId='+id+'&reqIdDetil='+iddetil, function() {
            })
            .done(function() {
                document.location.href="main/index/setting_mengetahui_add/?reqId=<?=$reqId?>";
            })
            .fail(function() {
                alert( "error" );
            });                             
        }
    });
}

function AddDetil() {
    $.get("app/loadUrl/main/template_mengetahui_add", function(data) { 
        $("#detil").append(data);
    });
}
function submitForm(){
    
    $('#ff').form('submit',{
        url:'web/setting_mengetahui_json/add',
        onSubmit:function(){
            return $(this).form('enableValidation').form('validate');
        },
        success:function(data){
            // console.log(data);return false;
            $.messager.alertLink('Info', data, 'info', "app/loadUrl/admin/kelompok");  
        }
    });
}
function clearForm(){
    $('#ff').form('clear');
}
  
</script>