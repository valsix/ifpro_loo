<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

/* CHECK USER LOGIN 
$CI =& get_instance();
$CI->checkUserLogin();*/

$this->load->model('PegawaiRegistrasiKecuali');

$pegawai_registrasi_kecuali = new PegawaiRegistrasiKecuali();

$reqId = $this->input->get("reqId");

if($reqId == ""){
    $reqMode = "insert";
}
else
{
    $reqMode = "update";    
    $pegawai_registrasi_kecuali->selectByParamsMonitoring(array('PEGAWAI_REGISTRASI_KECUALI_ID'=>$reqId), -1, -1);
    $pegawai_registrasi_kecuali->firstRow();
    
    $reqNamaPegawai= $pegawai_registrasi_kecuali->getField('NAMA_PEGAWAI');
    $reqPegawaiId= $pegawai_registrasi_kecuali->getField('PEGAWAI_ID');
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>

<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/gaya.css">

<script src="<?=base_url()?>js/jquery-1.10.2.min.js" type="text/javascript" charset="utf-8"></script>    

<link rel="stylesheet" type="text/css" href="<?=base_url()?>lib/easyui/themes/default/easyui.css">
<!--<script type="text/javascript" src="<?=base_url()?>js/jquery-1.6.1.min.js"></script>-->
<script type="text/javascript" src="<?=base_url()?>lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript" src="<?=base_url()?>lib/easyui/globalfunction.js"></script>
<script type="text/javascript"> 
function setValue(){
}

$(function(){
    $('#ff').form({
        url:'<?=base_url()?>pegawai_registrasi_kecuali_json/add',
        onSubmit:function(){
            return $(this).form('validate');
        },
        success:function(data){
            $.messager.alert('Info', data, 'info'); 
            
            $('#reqLampiran').MultiFile('reset');
            
            <?
            if($reqMode == "update")
            {
            ?>
                //document.location.reload();
            <?  
            }
            else
            {
            ?>
                $('#rst_form').click();
            <?
            }
            ?>
            top.frames['mainFrame'].location.reload();
        }
    });
    
    
});

function createDiganti(namaPegawai, nrp)
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
<link href="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- eModal -->
<script src="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/dist/js/eModal2.min.js"></script>
<script type="text/javascript">

// Display an modal whith iframe inside, with a title
function openPopup(page) {
    eModal.iframe(page, 'Aplikasi Presensi - PJB Services ')
}

//function closePopup(pesan)
function closePopup()
{
    eModal.close();
    //eModal.alert(pesan);      
    //setInterval(function(){ document.location.reload(); }, 2000);     
}
</script>

<!-- MODIF POPUP -->
<style>
.modal.in .modal-dialog {
    width:calc(100% - 15px);
    height:calc(100% - 20px);
    margin:10px 0 0 7px;
    *border:1px solid red;
}
.modal-content{
    *border:2px solid cyan;
    height:100%;
}

</style>
</head>

<body class="bg-kanan-full" onLoad="setValue();">
    <div id="judul-popup">Tambah Pegawai Registrasi Pengecualian</div>
    <div id="konten">
        <div id="popup-tabel2">
            <form id="ff" method="post" novalidate enctype="multipart/form-data">
            <table class="table">
                    <thead>
                        <tr>
                            <td>Nama Pegawai</td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqNamaPegawai" name="reqNamaPegawai" style="width:350px;" class="easyui-validatebox" value="<?=$reqNamaPegawai?>" readonly required> <input class="btn btn-xs btn-success" type="button" onClick="openPopup('<?=base_url()?>app/loadUrl/main/pegawai_pengganti_harian')" value="Browse"/>
                                <input type="hidden" id="reqPegawaiId" name="reqPegawaiId" value="<?=$reqPegawaiId?>" />
                            </td>
                        </tr>
                    </table>
                    </thead>
                    <input type="hidden" name="reqId" value="<?=$reqId?>" />
                    <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
                    <input type="submit" name="reqSubmit"  class="btn btn-primary" value="Submit" />
                    <input type="reset" id="rst_form"  class="btn btn-primary" value="Reset" />
                    
                    </form>
        </div>
        </div>
    </div>
</body>
</html>