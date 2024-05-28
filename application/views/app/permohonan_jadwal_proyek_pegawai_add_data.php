<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

/* CHECK USER LOGIN 
$CI =& get_instance();
$CI->checkUserLogin();*/

$this->load->model('PermohonanProyek');

$permohonan_proyek = new PermohonanProyek();

$reqId = $this->input->get("reqId");


if($reqId == ""){
	$reqMode = "insert";
	$reqTanggal = date("d-m-Y");
	$reqTahun = date("Y");
	$reqJamMasukAwal = "05:00";
	$reqJamMasukAkhir = "15:59";
	$reqJamPulangAwal = "16:00";
	$reqJamPulangAkhir = "04:59";
}
else
{
	$reqMode = "update";
	$permohonan_proyek->selectByParams(array('PERMOHONAN_PROYEK_ID'=>$reqId), -1, -1);
	$permohonan_proyek->firstRow();
	
	$reqNomor			= $permohonan_proyek->getField('NOMOR');
	$reqNama			= $permohonan_proyek->getField('NAMA');
	$reqNamaPegawaiPM	= $permohonan_proyek->getField('NAMA_PEGAWAI');
	$reqPegawaiIdPM		= $permohonan_proyek->getField('PEGAWAI_ID_PM');
	$reqCabangId		= $permohonan_proyek->getField('CABANG_ID');
	$reqNamaCabang		= $permohonan_proyek->getField('NAMA_CABANG');
	$reqTanggal			= $permohonan_proyek->getField('TANGGAL');
	$reqTanggalAwal		= $permohonan_proyek->getField('TANGGAL_AWAL');
	$reqTanggalAkhir	= $permohonan_proyek->getField('TANGGAL_AKHIR');
	
	$reqJamMasukAwal	= $permohonan_proyek->getField('JAM_MASUK_AWAL');
	$reqJamMasukAkhir	= $permohonan_proyek->getField('JAM_MASUK_AKHIR');
	$reqJamPulangAwal	= $permohonan_proyek->getField('JAM_PULANG_AWAL');
	$reqJamPulangAkhir	= $permohonan_proyek->getField('JAM_PULANG_AKHIR');
	
	$reqNomorMesin		= $permohonan_proyek->getField('NOMOR_MESIN');
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
		url:'<?=base_url()?>permohonan_proyek_json/add',
		onSubmit:function(){
			return $(this).form('validate');
		},
		success:function(data){
			$.messager.alert('Info', data, 'info');	
			//$('#rst_form').click();
			top.frames['mainFrame'].location.reload();
		}
	});
	
	$('#reqTanggalAwal').datebox({
		onSelect: function(date){
			$('#reqTanggalAkhir').datebox('setValue', '');		
		}
	});
		
	$('#reqTanggalAkhir').datebox({
		onSelect: function(date){
			var mulai = $('#reqTanggalAwal').datebox('getValue');	
			var selesai = $('#reqTanggalAkhir').datebox('getValue');	
			
			if(mulai == "")
			{
				$('#reqTanggalAkhir').datebox('setValue', '');	
				$.messager.alert('Info', "Isi tanggal mulai terlebih dahulu.", 'info');		
				return;
			}
			
			if(mulai > selesai)
			{
				$('#reqTanggalAkhir').datebox('setValue', '');	
				$.messager.alert('Info', "Tanggal akhir lebih kecil.", 'info');	
				return;
			}
		}
	});
	
});

function create(namaPegawai, nrp)
{
	$("#reqNamaPM").val(namaPegawai);
	$("#reqPegawaiIdPM").val(nrp);
}

function createRow(id)
{
	var jqxhr = $.get( "<?=base_url()?>cabang_json/getData/?reqId="+id, function(data) {
		$("#reqCabangId").val(id);
		$("#reqNamaCabang").val(data.NAMA);
	}, "json" );
}	
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

<!-- BOOTSTRAP CORE -->
<link href="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

<!-- eModal -->
<!--<script src="lib/startbootstrap-sb-admin-2-1.0.7/dist/js/eModal.min.js"></script>
<script src="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/dist/js/eModal-upload.min.js"></script>-->
<script src="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/dist/js/eModal2.min.js"></script>
<script type="text/javascript">
	

	// Display an modal whith iframe inside, with a title
	function openPopup(page) {
		//alert('test');
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
#reqAlasan{
 width:110px;   
}
</style>

</head>

<body class="bg-kanan-full" onload="AlasanTolak()">
	<div id="judul-popup">Data Proyek</div>
	<div id="konten">
    	<div id="popup-tabel2">
            <form id="ff" method="post" novalidate enctype="multipart/form-data">
            <table class="table">
                    <thead>
                    	<tr>
                        	<th colspan="3">Permohonan</th>
                        </tr>
                        <tr>
                            <td>Nomor WO</td>
                            <td>:</td>
                            <td>
                            	<span><?=$reqNomor?></span>
                            </td>
                        </tr>
                        <tr>
                            <td>Nama Proyek</td>
                            <td>:</td>
                            <td>
                            	<span><?=$reqNama?></span>
                            </td>
                        </tr>
                        <tr>
                        	<td>Pegawai PM</td>
                            <td>:</td>
                            <td>
                            	<span><?=$reqNamaPegawaiPM?></span>
                            </td>
                        </tr>
                        <tr>
                        	<td>Lokasi</td>
                            <td>:</td>
                            <td>
                            	<span><?=$reqNamaCabang?></span>
                            </td>
                        </tr>
                        <tr>
                            <td>Tanggal</td>
                            <td>:</td>
                            <td>
                            	<span><?=$reqTanggal?></span>
                            </td>
                        </tr>   
                        <tr>
                            <td>Tanggal Awal</td>
                            <td>:</td>
                            <td>
                            	<span><?=$reqTanggalAwal?></span>
                            </td>
                        </tr>  
                        <tr>
                            <td>Tanggal Akhir</td>
                            <td>:</td>
                            <td>
                            	<span><?=$reqTanggalAkhir?></span>
                            </td>
                        </tr>
                        <tr>
                            <td>Toleransi Jam Masuk</td>
                            <td>:</td>
                            <td>
                            	<span><?=$reqJamMasukAwal?> s/d <?=$reqJamMasukAkhir?></span>
                            </td>
                        </tr>  
                        <tr>
                            <td>Toleransi Jam Pulang</td>
                            <td>:</td>
                            <td>
                            	<span><?=$reqJamPulangAwal?> s/d <?=$reqJamPulangAkhir?></span>
                            </td>
                        </tr>  
                        <tr>
                            <td>Nomor Mesin</td>
                            <td>:</td>
                            <td>
                            	<span><?=$reqNomorMesin?></span>
                            </td>
                        </tr>
                    </thead>
                    </table>
                    <input type="hidden" name="reqPegawaiId" value="<?=$reqPegawaiId?>" />
                    <input type="hidden" name="reqId" value="<?=$reqId?>" />
                    <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
                    <input type="hidden" name="reqTahun" value="<?=$reqTahun?>" />
                    <!--
                    <input type="submit" name="reqSubmit"  class="btn btn-primary" value="Submit" />
                    <input type="reset" id="rst_form"  class="btn btn-primary" value="Reset" />
                    -->
                    </form>
        </div>
        </div>
    </div>
</body>
</html>