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
		url:'<?=base_url()?>pegawai_json/addImport',
		onSubmit:function(){
			return $(this).form('validate');
		},
		success:function(data){
			//alert(data);
			$.messager.alert('Info', data, 'info');	
			
			$('#reqLampiran').MultiFile('reset');
			
				document.location.reload();
				top.frames['mainFrame'].location.reload();
		}
	});
	
});
</script>

</head>

<body class="bg-kanan-full">
	<div id="judul-popup">Import Pegawai Pihak Ketiga</div>
	<div id="konten">
    	<div id="popup-tabel2">
            <form id="ff" method="post" novalidate enctype="multipart/form-data">
            <table class="table">
            <thead>      
                <tr>
                    <td>Download Template</td>
                    <td>:</td>
                    <td>
                        <a href="<?=base_url()?>app/loadUrl/app/pegawai_pihak_ketiga_import_template_excel" target="_blank"><img src="<?=base_url()?>images/icon-excel.png" /> Download </a>
                    </td>
                </tr>   
                <tr>
                    <td>File</td>
                    <td>:</td>
                    <td>
                        <input type="file" name="reqLinkFile" id="reqLinkFile" class="easyui-validatebox" validType="fileType['xls']" />
                    </td>
                </tr>
            </thead>
            </table>
            <input type="submit" name="reqSubmit" class="btn btn-primary" value="Submit" />
            
            </form>
        </div>
        </div>
    </div>
</body>
</html>