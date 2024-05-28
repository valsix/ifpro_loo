
<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");


$this->load->model("Peraturan");
$peraturan = new Peraturan();

$reqId = $this->input->get("reqId");

if($reqId == ""){
    $reqMode = "insert";

}
else
{
	$reqMode = "ubah";
	$peraturan->selectByParams(array("A.PERATURAN_ID" => $reqId));
	$peraturan->firstRow();
    
	$reqId         = $peraturan->getField("PERATURAN_ID");
	$reqNama       = $peraturan->getField("NAMA");
	$reqNomor      = $peraturan->getField("NOMOR");
    $reqUrut       = $peraturan->getField("URUT");
    $reqFile       = $peraturan->getField("LINK_FILE");
}
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
<script type="text/javascript"> 
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
    <div id="judul-popup">Kelola Peraturan</div>
    <div id="konten">
        <div id="popup-tabel2">

            <form id="ff" method="post" novalidate enctype="multipart/form-data">
                <table class="table">
                    <thead>
                        <tr>
                            <td>Nama</td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqNama" class="easyui-validatebox textbox form-control" required name="reqNama"  value="<?=$reqNama ?>" data-options="required:true" style="width:90%" />
                            </td>
                        </tr>
                        <tr>
                            <td>Nomor</td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqNomor" class="easyui-validatebox textbox form-control" required name="reqNomor"  value="<?=$reqNomor ?>" data-options="required:true" style="width:90%" />
                            </td>
                        </tr>
                        <tr>
                            <td>Urut</td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqUrut" class="easyui-validatebox textbox form-control" required name="reqUrut"  value="<?=$reqUrut ?>" data-options="required:true" style="width:90%" />
                            </td>
                        </tr>
                        <tr>
                            <td>Dokumen</td>
                            <td>:</td>
                            <td>
                                <input readonly class="easyui-validatebox" type="hidden" name="reqLinkFileTemp" id="reqLinkFileTemp" value="<?=$reqFile?>" style="width:90%">
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
function submitForm(){
    $('#ff').form('submit',{
        url:'web/peraturan_json/add',
        onSubmit:function(){
            return $(this).form('enableValidation').form('validate');
        },
        success:function(data){
            // console.log(data);return false;
            $.messager.alertLink('Info', data, 'info', "main/index/peraturan");
        }
    });
}
function clearForm(){
    $('#ff').form('clear');
}
            
</script>