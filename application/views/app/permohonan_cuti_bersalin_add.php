<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

/* CHECK USER LOGIN 
$CI =& get_instance();
$CI->checkUserLogin();*/

$this->load->model('JamKerjaRamadhan');

$jam_kerja_ramadhan = new JamKerjaRamadhan();

$reqId = $reqParse1;

if($reqMode == "view")
{
	$reqMode = "update";	
	$jam_kerja_ramadhan->selectByParams(array('JAM_KERJA_RAMADHAN_ID'=>$reqId), -1, -1);
	$jam_kerja_ramadhan->firstRow();
	
	$reqTahun= $jam_kerja_ramadhan->getField('TAHUN');
	
	$tempJamAwal= $jam_kerja_ramadhan->getField('JAM_AWAL');
	$tempJamAkhir= $jam_kerja_ramadhan->getField('JAM_AKHIR');
	$tempJumatAwal= $jam_kerja_ramadhan->getField('JUMAT_AWAL');
	$tempJumatAkhir= $jam_kerja_ramadhan->getField('JUMAT_AKHIR');
	$tempTanggalAwal= dateToPageCheck($jam_kerja_ramadhan->getField('TANGGAL_AWAL'));
	$tempTanggalAkhir= dateToPageCheck($jam_kerja_ramadhan->getField('TANGGAL_AKHIR'));
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
<script type="text/javascript">	
$(function(){
	<?
	if($reqMode == "view")
	{
	?>
	$("#reqSubmit").hide();
	$("#reqReset").hide();
	$("input, textarea").prop("disabled", true);
	<?
	}
	?>		
	
});
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

</head>

<body class="bg-kanan-full">
	<div id="judul-popup">Tambah Jam kerja Ramadhan</div>
	<div id="konten">
    	<div id="popup-tabel2">
            <form id="ff" method="post" novalidate enctype="multipart/form-data">
                    <table class="table">
                    <thead>
                        <tr>
                            <td>Nomor</td>
                            <td>:</td>
                            <td>
                                <input type="text" name="reqNomor" class="easyui-validatebox" style="width:250px;" value="<?=$reqNomor?>" />
                          </td>
                        </tr>   
                     	<tr>
                            <td>Tanggal</td>
                            <td>:</td>
                            <td>
                              <input class="easyui-datebox" name="reqTanggal" value="<?=$reqTanggal?>">
                            </td>
                        </tr>  
                        <tr>
                            <td>Tanggal Awal</td>
                            <td>:</td>
                            <td>
                                <input class="easyui-datebox" id="reqTanggalAwal" name="reqTanggalAwal" value="<?=$reqTanggalAwal?>" required>
                            </td>
                        </tr>  
                        <tr>
                            <td>Tanggal Akhir</td>
                            <td>:</td>
                            <td>
                                <input class="easyui-datebox" id="reqTanggalAkhir" name="reqTanggalAkhir" value="<?=$reqTanggalAkhir?>" required>
                            </td>
                        </tr>
                        <tr>
                            <td>Jumlah Hari</td>
                            <td>:</td>
                            <td>
                               <input type="text" id="reqJumlahHari" name="reqJumlahHari" class="easyui-validatebox" style="width:50px;" readonly value="<?=$reqJumlahHari?>" />
                            </td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td>:</td>
                            <td>
                            	<textarea name="reqAlamat" style="height:70px; width:250px;"><?=$reqAlamat?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>Telepon</td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqTelpon" name="reqTelpon" style="width:250px;" class="easyui-validatebox" value="<?=$reqTelpon?>" />
                            </td>
                        </tr>
                    </thead>
                    </table>
                    <input type="hidden" name="reqId" value="<?=$reqId?>" />
                    <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
                    <input type="submit" name="reqSubmit"  class="btn btn-primary" value="Submit" id="reqSubmit"/>
                    <input type="reset" id="rst_form"  class="btn btn-primary" value="Reset" id="reqReset"/>
                    
                    </form>
        </div>
        </div>
    </div>
</body>
</html>