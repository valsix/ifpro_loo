<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

/* CHECK USER LOGIN 
$CI =& get_instance();
$CI->checkUserLogin();*/

$this->load->model('UserLogin');
$this->load->model('UserGroup');

$user_login = new UserLogin();
$user_group = new UserGroup();

$reqId = $this->input->get("reqId");

if($reqId == ""){
	$reqMode = "insert";
	$reqTahun = date("Y");
}
else
{
	$reqMode = "update";	
	$user_login->selectByParams(array('USER_LOGIN_ID'=>$reqId), -1, -1);
	$user_login->firstRow();
	
	$reqUserGroupId= $user_login->getField('USER_GROUP_ID');
	$reqPegawaiId= $user_login->getField('PEGAWAI_ID');
	$reqNama= $user_login->getField('NAMA');
	$reqJabatan= $user_login->getField('JABATAN');
	$reqEmail= $user_login->getField('EMAIL');
	$reqTelepon= $user_login->getField('TELEPON');
	$reqStatus= $user_login->getField('STATUS');
	$reqUserLogin= $user_login->getField('USER_LOGIN');
	$reqUserPass= $user_login->getField('USER_PASS');
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
$(function(){
	$('#ff').form({
		url:'<?=base_url()?>user_login_json/add',
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

var nomor = 1000;
function createRow(nip, nama, jabatan, email, telepon)
{
	$("#reqPegawaiId").val(nip);
	$("#reqNama").val(nama);
	$("#reqJabatan").val(jabatan);
	$("#reqEmail").val(email);
	$("#reqTelepon").val(telepon);
}

function openPencarianPegawai()
{
	openPopup('<?=base_url()?>app/loadUrl/app/pegawai');
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
	margin:10px 0 0 0;
	*border:1px solid red;
}
.modal-content{
	*border:2px solid cyan;
	height:100%;
}

</style>

</head>

<body class="bg-kanan-full">
	<div id="judul-popup">Tambah User Login</div>
	<div id="konten">
    	<div id="popup-tabel2">
            <form id="ff" method="post" novalidate>
                    <table class="table">
                    <thead>
                    	<tr>           
                             <td>User Group</td><td>:</td>
                             <td>
                                <select name="reqUserGroupId">
                                    <option value="">[-Pilih User Group-]</option>
                                    <?
                                    $statement = "";
                                    $user_group->selectByParams(array(), -1, -1, $statement);
                                    while($user_group->nextRow())
                                    {
                                    ?>
                                    <option value="<?=($user_group->getField("USER_GROUP_ID"))?>" <? if($user_group->getField("USER_GROUP_ID") == $reqUserGroupId) { ?> selected="selected" <? } ?>>
                                        <?=($user_group->getField("NAMA"))?>
                                    </option>
                                        <? } ?>
                                    </option>
                                </select>
                            </td>			
                        </tr>
                        <tr>           
                             <td>Nama Pegawai</td><td>:</td>
                             <td>
                                <input type="hidden" id="reqPegawaiId" name="reqPegawaiId" class="easyui-validatebox" value="<?=$reqPegawaiId?>" />
                                <input type="hidden" id="reqJabatan" name="reqJabatan" class="easyui-validatebox" value="<?=$reqJabatan?>" />
                                <input type="hidden" id="reqEmail" name="reqEmail" class="easyui-validatebox" value="<?=$reqEmail?>" />
                                <input type="hidden" id="reqTelepon" name="reqTelepon" class="easyui-validatebox" value="<?=$reqTelepon?>" />
                             	<input type="text"  id="reqNama" name="reqNama" value="<?=$reqNama?>" size="50px">
                                <img src="<?=base_url()?>images/icon-tambah.png" onClick="openPencarianPegawai()">
                            </td>			
                        </tr>
                        <tr style="display:none">           
                             <td>User Login</td><td>:</td>
                             <td>
                             	<input type="text" name="reqUserLogin" value="<?=$reqUserLogin?>" size="50px">
                            </td>			
                        </tr>
                        <tr style="display:none">           
                             <td>User Password</td><td>:</td>
                             <td>
                             	<input type="password" name="reqUserPass" value="<?=$reqUserPass?>" size="50px">
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