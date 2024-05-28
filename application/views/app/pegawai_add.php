<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

/* CHECK USER LOGIN 
$CI =& get_instance();
$CI->checkUserLogin();*/

$this->load->model('Pegawai');

$pegawai= new Pegawai();

$reqId = $this->input->get("reqId");
$reqMode = $this->input->get("reqMode");

if($reqId == "")
{ 
	$reqMode = 'insert';
}
else
{
	$reqMode = 'update';
	$pegawai->selectByParams(array("A.PEGAWAI_ID" => $reqId));
	$pegawai->firstRow();
	$reqCabangId = $pegawai->getField("CABANG_ID");
	$reqDepartemenId = $pegawai->getField("DEPARTEMEN_ID");
	$reqSubDepartemenId = $pegawai->getField("SUB_DEPARTEMEN_ID");
	$reqFungsiId = $pegawai->getField("FUNGSI_ID");
	$reqStaffId = $pegawai->getField("STAFF_ID");
	$reqNrp = $pegawai->getField("NRP");
	$reqNama = $pegawai->getField("NAMA");
	$reqJenisKelamin = $pegawai->getField("JENIS_KELAMIN");
	$reqTempatLahir = $pegawai->getField("TEMPAT_LAHIR");
	$reqTanggalLahir = $pegawai->getField("TANGGAL_LAHIR");
	$reqAlamat = $pegawai->getField("ALAMAT");
	$reqJabatan = $pegawai->getField("JABATAN");
	$reqTanggalMasuk = $pegawai->getField("TANGGAL_MASUK");
	$reqEmail = $pegawai->getField("EMAIL");
	$reqTelepon = $pegawai->getField("TELEPON");
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>

<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/gaya.css">

<link rel="stylesheet" type="text/css" href="<?=base_url()?>lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="<?=base_url()?>js/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript" src="<?=base_url()?>lib/easyui/globalfunction.js"></script>


<!-- BOOTSTRAP CORE -->
<link href="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

<script type="text/javascript">	
	$(function(){
	$('#ff').form({
		url:'<?=base_url()?>pegawai_json/add',
		onSubmit:function(){
			return $(this).form('validate');
		},
		success:function(data){
			//alert(data);
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
</script>

</head>

<body class="bg-kanan-full">
	<div id="judul-popup">Data Pegawai</div>
	<div id="konten">
    	<div id="popup-tabel2">
            <form id="ff" method="post" novalidate enctype="multipart/form-data">
                    <table class="table">
                    <thead>
                    	<tr>
                            <td>NRP</td>
                            <td>:</td>
                            <td>
                                <input type="text" name="reqNrp" id="reqNrp" value="<?=$reqNrp?>" style="width:160px" maxlength="15"/>
                          	</td>
                        </tr>
                    	<tr>
                            <td>Nama</td>
                            <td>:</td>
                            <td>
                               <input type="text" name="reqNama" id="reqNama" value="<?=$reqNama?>" style="width:350px"/>
                          	</td>
                        </tr>        
                        <tr>
                            <td>Cabang</td>
                            <td>:</td>
                            <td>
                                <input class="easyui-combobox" name="reqCabangId" style="width:350px;" data-options="
                                    url: '<?=base_url()?>cabang_combo_json/json',
                                    method: 'get',
                                    valueField:'value', 
                                    textField:'text'
                                " value="<?=$reqCabangId?>">
                          	</td>
                        </tr>   
                        <tr>
                            <td>Departemen</td>
                            <td>:</td>
                            <td>
                                <input class="easyui-combobox" name="reqDepartemenId" style="width:350px;" data-options="
                                    url: '<?=base_url()?>departemen_combo_json/json',
                                    method: 'get',
                                    valueField:'value', 
                                    textField:'text'
                                " value="<?=$reqDepartemenId?>">
                          	</td>
                        </tr>   
                        <tr>
                            <td>Sub Departemen</td>
                            <td>:</td>
                            <td>
                                <input class="easyui-combobox" name="reqSubDepartemenId" style="width:350px;" data-options="
                                    url: '<?=base_url()?>sub_departemen_combo_json/json',
                                    method: 'get',
                                    valueField:'value', 
                                    textField:'text'
                                " value="<?=$reqSubDepartemenId?>">
                          	</td>
                        </tr>   
                        <tr>
                            <td>Fungsi</td>
                            <td>:</td>
                            <td>
                                <input class="easyui-combobox" name="reqFungsiId" style="width:350px;" data-options="
                                    url: '<?=base_url()?>fungsi_combo_json/json',
                                    method: 'get',
                                    valueField:'value', 
                                    textField:'text'
                                " value="<?=$reqFungsiId?>">
                          	</td>
                        </tr>   
                        <tr>
                            <td>Staff</td>
                            <td>:</td>
                            <td>
                                <input class="easyui-combobox" name="reqStaffId" style="width:350px;" data-options="
                                    url: '<?=base_url()?>staff_combo_json/json',
                                    method: 'get',
                                    valueField:'value', 
                                    textField:'text'
                                " value="<?=$reqStaffId?>">
                          	</td>
                        </tr>  
                        <!--<tr>
                            <td>Jabatan</td>
                            <td>:</td>
                            <td>
                                <input class="easyui-combobox" name="reqJabatanId" style="width:350px;" data-options="
                                    url: '<?=base_url()?>jabatan_combo_json/json',
                                    method: 'get',
                                    valueField:'value', 
                                    textField:'text'
                                " value="<?=$reqJabatanId?>">
                          	</td>
                        </tr> -->
                        <tr>
                            <td>Jenjang</td>
                            <td>:</td>
                            <td>
                                <input class="easyui-combobox" name="reqJenjangId" style="width:350px;" data-options="
                                    url: '<?=base_url()?>jenjang_combo_json/json',
                                    method: 'get',
                                    valueField:'value', 
                                    textField:'text'
                                " value="<?=$reqJenjangId?>">
                          	</td>
                        </tr> 
                        <tr>
                            <td>Jenis Kelamin</td>
                            <td>:</td>
                            <td>
                                <select name="reqJenisKelamin" id="reqJenisKelamin" style="width:350px">
                                  <option value="L" <? if($reqJenisKelamin == "L") echo "selected"; ?>>Laki-laki</option>
                                  <option value="P" <? if($reqJenisKelamin == "P") echo "selected"; ?>>Perempuan</option>
                                </select>
                          	</td>
                        </tr>
                        <tr>
                            <td>Tempat Lahir</td>
                            <td>:</td>
                            <td>
                                <input type="text" name="reqTempatLahir" id="reqTempatLahir" value="<?=$reqTempatLahir?>" style="width:350px"/>
                          	</td>
                        </tr>
                        <tr>
                            <td>Tanggal Lahir</td>
                            <td>:</td>
                            <td>
                              <input class="easyui-datebox" name="reqTanggalLahir" value="<?=$reqTanggalLahir?>">
                            </td>
                        </tr>  
                        <tr>
                            <td>Alamat</td>
                            <td>:</td>
                            <td>
                                <textarea name="reqAlamat" id="reqAlamat" style="width:350px"/> <?=$reqAlamat?> </textarea>
                          	</td>
                        </tr>
                        <tr>
                            <td>Jabatan</td>
                            <td>:</td>
                            <td>
                              <input type="text" name="reqJabatan" id="reqJabatan" value="<?=$reqJabatan?>" style="width:350px"/>
                            </td>
                        </tr>  
                        <tr>
                            <td>Tanggal Masuk</td>
                            <td>:</td>
                            <td>
                              <input class="easyui-datebox" name="reqTanggalMasuk" value="<?=$reqTanggalMasuk?>">
                            </td>
                        </tr>  
                        <tr>
                            <td>Email</td>
                            <td>:</td>
                            <td>
                              <input type="text" name="reqEmail" id="reqEmail" value="<?=$reqEmail?>" style="width:350px"/>
                            </td>
                        </tr>  
                        <tr>
                            <td>Telepon</td>
                            <td>:</td>
                            <td>
                              <input type="text" name="reqTelepon" id="reqTelepon" value="<?=$reqTelepon?>" style="width:350px"/>
                            </td>
                        </tr>  
                    </thead>
                    </table>
                    <input type="hidden" name="reqId" value="<?=$reqId?>" />
                    <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
                    <input type="submit" id="reqSubmit" name="reqSubmit" class="btn btn-primary"  value="Submit" />
                    
            </form>
        </div>
        </div>
    </div>
</body>
</html>