<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

/* CHECK USER LOGIN 
$CI =& get_instance();
$CI->checkUserLogin();*/

$this->load->model('PermohonanKeandalan');

$permohonan_keandalan = new PermohonanKeandalan();
$permohonan_keandalan_approval = new PermohonanKeandalan();

$reqId = $this->input->get("reqId");
$tempDepartemen = $userLogin->idDepartemen;

if($reqId == ""){
	$reqMode = "insert";
}
else
{
	$reqMode = "update";	
	$permohonan_keandalan->selectByParams(array('PERMOHONAN_KEANDALAN_ID'=>$reqId), -1, -1);
	$permohonan_keandalan->firstRow();
	
	$temNamaPegawai= $permohonan_keandalan->getField('NAMA_PEGAWAI');
	$tempJabatan= $permohonan_keandalan->getField('JABATAN');
	$tempNamaCabang= $permohonan_keandalan->getField('NAMA_CABANG');
	$tempDepartemen= $permohonan_keandalan->getField('DEPARTEMEN');
	$tempSubDepartemen= $permohonan_keandalan->getField('SUB_DEPARTEMEN');
	$tempTahun= $permohonan_keandalan->getField('TAHUN');
	$tempNomor= $permohonan_keandalan->getField('NOMOR');
	$tempTanggal= $permohonan_keandalan->getField('TANGGAL');
	$tempApproval= $permohonan_keandalan->getField('APPROVAL');
	$tempApprovalKeterangan= $permohonan_keandalan->getField('APPROVAL_KETERANGAN');
	$tempAlasanTolak= $permohonan_keandalan->getField('ALASAN_TOLAK');
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>

<script type="text/javascript" src="<?=base_url()?>js/jquery-1.9.1.js"></script>

<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/gaya.css">

<link rel="stylesheet" type="text/css" href="<?=base_url()?>lib/easyui/themes/default/easyui.css">
<?php /*?><script type="text/javascript" src="<?=base_url()?>js/jquery-1.6.1.min.js"></script><?php */?>
<script type="text/javascript" src="<?=base_url()?>lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript" src="<?=base_url()?>lib/easyui/globalfunction.js"></script>
<script type="text/javascript">	
$(function(){
	$('#ff').form({
		url:'<?=base_url()?>permohonan_keandalan_json/add',
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
</script>
<script>
function AlasanTolak(){
			var verifikasi = document.getElementById("reqAlasan").value;
			if (verifikasi == 'T'){
			document.getElementById("reqAlasanTolak").style.display="";	
			}else {
			document.getElementById("reqAlasanTolak").style.display="none";	
			}
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
<script src="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/dist/js/eModal-upload.min.js"></script>
<script type="text/javascript">
	

	// Display an modal whith iframe inside, with a title
	function openPopup(page) {
		//alert('test');
		
		eModal.iframe(page, 'Data Pegawai')
		
	}

	
</script>

<style>
#iframeModal-upload{
	*border:1px solid red;
	height:100% !important;
}
body.modal-open{
	*border:5px solid cyan;
}
iframe.embed-responsive-item.tmp-modal-content{
	*height:700px !important;
}

#iframeModal-upload .modal-dialog.modal-lg{
	*border:2px solid #F60; 
	height:90% !important;
}
#iframeModal-upload .modal-content{
	*border:2px solid #9C3;
}

.modal-backdrop{
	*border:3px solid #FFF;
	height:100%;
	width:100%;
	position:absolute;
	z-index:999;
	background:url(<?=base_url()?>images/bg-popup2.png) top repeat-x;
}
</style>

</head>

<body class="bg-kanan-full" onload="AlasanTolak()">
	<div id="judul-popup">Permohonan Jadwal Keandalan</div>
	<div id="konten">
    	<div id="popup-tabel2">
            <form id="ff" method="post" novalidate enctype="multipart/form-data">
            <table class="table">
                    <thead>
                    	<tr>
                        	<th colspan="3"><strong>PEMOHON<strong></th>
                        </tr>
                    	<tr>           
                             <td>Tahun</td>
                             <td>:</td>
                             <td><?=$tempTahun?> 
                            </td>			
                        </tr>
                        <tr>           
                             <td>Nama</td>
                             <td>:</td>
                             <td><?=$temNamaPegawai?>
                            </td>			
                        </tr>
                        <tr>           
                             <td>Jabatan</td>
                             <td>:</td>
                             <td><?=$tempJabatan?>
                            </td>			
                        </tr>
                        <tr>           
                             <td>Cabang</td>
                             <td>:</td>
                             <td><?=$tempNamaCabang?>
                            </td>			
                        </tr>
                         <tr>           
                             <td>Departemen</td>
                             <td>:</td>
                             <td><?=$tempDepartemen?>
                            </td>			
                        </tr>
                         <tr>           
                             <td>Sub Departemen</td>
                             <td>:</td>
                             <td><?=$tempSubDepartemen?>
                            </td>			
                        </tr>
                        <tr>           
                             <td>Nomor</td>
                             <td>:</td>
                             <td><?=$tempNomor?>
                            </td>			
                        </tr>
                        <tr>           
                             <td>Tanggal</td>
                             <td>:</td>
                             <td><?=$tempTanggal?>
                            </td>			
                        </tr>
                          <tr>
                        	<th colspan="3"><strong>VERIFIKASI<strong></th>
                        </tr>
                        <tr>
                            <td>Persetujuan</td>
                            <td>:</td>
                            <td>
                                <select name="reqAlasan" id="reqAlasan" onchange="AlasanTolak()">
                                	<option value="">Pilih</option>
                                    <option value="Y" <? if($reqAlasan == 'Y') { ?> selected="selected" <? } ?>>Disetujui</option>
                                    <option value="T" <? if($reqAlasan == 'T') { ?> selected="selected" <? } ?>>Ditolak</option>
                                </select>
                            </td>
                        </tr>
                        <tr id="reqAlasanTolak" style="display:none">
                        	<td>Alasan Penolakan</td>
                            <td>:</td>
                        	<td>
								<input type="text" class="easyui-validatebox" name="reqAlasanTolak" value="<?=$reqAlasanTolak?>" style="width:80%">
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