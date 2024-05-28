<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model("UserLogin");
$user_login = new UserLogin();

$reqId = $this->input->get("reqId");

if($reqId == ""){
$reqMode = "insert";
}
else
{
    $reqMode = "ubah";
    $user_login->selectByParams(array("A.USER_LOGIN_ID" => $reqId));
    
    $user_login->firstRow();
    $reqId                      = $user_login->getField("USER_LOGIN_ID");
    $reqNama                    = $user_login->getField("NAMA");
    $reqPegawaiId               = $user_login->getField("PEGAWAI_ID");
    $reqUserGroupId             = $user_login->getField("USER_GROUP_ID");
    $reqSatuanKerjaIdAsal       = $user_login->getField("SATUAN_KERJA_ID_ASAL");
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>

<base href="<?=base_url();?>" />

<script type="text/javascript" src="<?=base_url()?>js/jquery-1.9.1.js"></script>

<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/gaya.css">

<link rel="stylesheet" type="text/css" href="<?=base_url()?>lib/easyui/themes/default/easyui.css">

<link href="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

<!-- tiny MCE -->
<script src="<?=base_url()?>lib/tinyMCE/tinymce.min.js"></script>

<script type="text/javascript">
    tinymce.init({
        selector: "textarea",
        plugins: [
            "advlist autolink lists link image charmap print preview anchor",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime media table contextmenu paste"
        ],
        toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
        menubar: true,

    });
</script>

<?php /*?><script type="text/javascript" src="<?=base_url()?>js/jquery-1.6.1.min.js"></script><?php */?>
<script type="text/javascript" src="<?=base_url()?>lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript" src="<?=base_url()?>lib/easyui/globalfunction.js"></script>
<script type="text/javascript">	

$(function(){
	$('#ff').form({
		url:'<?=base_url()?>absensi_manual_json/add',
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
				document.location.reload();
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
<link href="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

<!-- eModal -->
<!--<script src="lib/startbootstrap-sb-admin-2-1.0.7/dist/js/eModal.min.js"></script>-->
<script src="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/dist/js/eModal2.min.js"></script>
<script type="text/javascript">
	

	function openPopup(page) {
    	eModal.iframe(page, 'Aplikasi E-Office - ASDP Indonesia Ferry')
	}
	
	function closePopup()
	{
		eModal.close();
	}
	
</script>
</head>

<body class="bg-kanan-full">
	<div id="judul-popup">Kelola User Login</div>
	<div id="konten">
    	<div id="popup-tabel2">
            <form id="ff" method="post" novalidate enctype="multipart/form-data">
                <table class="table">
                    <thead>
                    	<tr>
                            <td>NIP</td>
                            <td>:</td>
                            <td>
                                <div style="width: 20%; float: left;">
                                    <input type="text" id="reqPegawaiId" class="easyui-validatebox textbox form-control" required name="reqPegawaiId"  value="<?=$reqPegawaiId ?>" readonly data-options="required:true" style="width:100%" />
                                </div>
                                <div class="col-md-1">
                                    <?
                                    if($reqMode == "insert")
                                    {
                                    ?>
                                    <a id="btnAdd" onClick="openAdd('app/loadUrl/app/pegawai_pengganti_lookup')"><i class="fa fa-plus-square fa-lg" aria-hidden="true"></i> </a>
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
                                <input type="text" id="reqNama" class="easyui-validatebox textbox form-control" required name="reqNama"  value="<?=$reqNama ?>" readonly data-options="required:true" style="width:50%" />
                        	</td>
                        </tr>  
                        <tr>
                        	<td>User Group</td>
                        	<td>:</td>
                        	<td>
                                <?
                                $reqUserGroupPilih = str_replace(",", "','", $reqUserGroupId);
                                ?>
                                <input type="text" name="reqUserGroupPilih" class="easyui-combotree"  id="reqUserGroupPilih" 
                                		data-options="width:'300',valueField:'id',
                						textField:'text', editable:false,url:'combo_json/comboUserGroup',multiple:true,value:['<?=$reqUserGroupPilih?>']" required />
                                <input type="hidden" name="reqUserGroupId" id="reqUserGroupId"   value="<?=$reqUserGroupId?>">
                        	</td>
                        </tr> 
                        <tr>
                        	<td>User Group</td>
                        	<td>:</td>
                        	<td>
                                <input type="text" name="reqSatuanKerjaIdAsal" class="easyui-combotree"  id="reqTipe"
                                        data-options="width:'500',valueField:'SATUAN_KERJA_ID',textField:'JABATAN',url:'web/satuan_kerja_json/combotree_jabatan'"  value="<?=$reqSatuanKerjaIdAsal?>"   />
                        	</td>
                        </tr>  
                        
                    </thead>
                </table>

                <input type="hidden" name="reqId" value="<?=$reqId?>" />
                <input type="hidden" name="reqMode" value="<?=$reqMode?>" />

                <input type="submit" name="reqSubmit"  class="btn btn-primary" value="Submit" />
                <input type="reset" id="rst_form"  class="btn btn-primary" value="Reset" />
                    
            </form>
        </div>
    </div>
</body>
</html>