<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model('PermohonanInsidentil');

$permohonan_insidentil = new PermohonanInsidentil();

$reqId = $this->input->get("reqId");
$reqMode = $this->input->get("reqMode");

if($reqId == "")
{
	$reqMode = "insert";
	$reqTanggal = date("d-m-Y");
	$reqTahun = date("Y");
}
else
{
	$reqMode = "update";
	$permohonan_insidentil->selectByParams(array("PERMOHONAN_INSIDENTIL_ID" => $reqId));
	$permohonan_insidentil->firstRow();
	$reqPegawaiId = $permohonan_insidentil->getField("PEGAWAI_ID");
	$reqNoNotaDinas = $permohonan_insidentil->getField("NO_NOTA_DINAS");
	$reqTanggal = $permohonan_insidentil->getField("TANGGAL");
	$reqTanggalAwal = $permohonan_insidentil->getField("TANGGAL_AWAL");
	$reqTanggalAkhir = $permohonan_insidentil->getField("TANGGAL_AKHIR");
	$reqAlasan = $permohonan_insidentil->getField("ALASAN");
	$reqNamaPegawai = $permohonan_insidentil->getField("NAMA_PEGAWAI");
	$reqNotaDinasTemp = $permohonan_insidentil->getField("UPLOAD_NOTA_DINAS");
	$reqJumlahHari = $permohonan_insidentil->getField("JUMLAH_HARI");
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
<base href="<?=base_url();?>" />
<link rel="stylesheet" type="text/css" href="css/gaya.css">

<script src="<?=base_url()?>js/jquery-1.10.2.min.js" type="text/javascript" charset="utf-8"></script>  

<link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
<!--<script type="text/javascript" src="js/jquery-1.6.1.min.js"></script>-->
<script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript" src="lib/easyui/globalfunction.js"></script>

        
<!-- BOOTSTRAP CORE -->
<link href="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

<script type="text/javascript">	
$(function(){
	$('#ff').form({
		url:'<?=base_url()?>permohonan_insidentil_json/add',
		onSubmit:function(){
			return $(this).form('validate');	
		},
		success:function(data){
			//$.messager.alert('Info', data, 'info');
			top.frames['mainFrame'].location.reload();
			window.parent.closePopup(data);
		}
	});
	
	$('#reqTanggalAwal').datebox({
		onSelect: function(date){
			
			if($("#reqPegawaiId").val() == '')
			{
				$.messager.alert('Info', "Pilih pegawai terlebih dahulu.", 'info');		
				$('#reqTanggalAwal').datebox('setValue', '');						
			}
			
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
			
			var selisih = get_day_between(mulai, selesai);
			
			if(selisih <= 0)
			{
				$('#reqTanggalAkhir').datebox('setValue', '');					
				$("#reqJumlahHari").val('');
				$.messager.alert('Info', "Tanggal akhir lebih kecil.", 'info');		
				return;
			}
			
			$("#reqJumlahHari").val(selisih);
			
			getVerifikasiPermohonanByPegawai($("#reqPegawaiId").val(), mulai, selesai);
			
		}
	});
});	

var nomor = 1000;
function createRow(nip, nama)
{
	$("#reqPegawaiId").val(nip);
	$("#reqNamaPegawai").val(nama);
}

function openPencarianPegawai()
{
	openPopup('<?=base_url()?>app/loadUrl/app/pegawai_insidentil');
}
</script>

<!-- eModal -->
<script src="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/dist/js/eModal.min.js"></script>
<script type="text/javascript">

// Display an modal whith iframe inside, with a title
function openPopup(page) {
    eModal.iframe(page, 'Aplikasi Presensi - PJB Services')
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
	height:calc(100% - 100px);
	margin:10px 0 0 0;
	*border:1px solid red;
}
.modal-content{
	*border:2px solid cyan;
	height:calc(100% - 80px);
}

</style>

</head>

<body class="bg-kanan-full">
	<div id="judul-popup">Permohonan Cuti Tahunan</div>
	<div id="konten">
    	<div id="popup-tabel2">
            <form id="ff" method="post" novalidate enctype="multipart/form-data">
                    <table class="table">
                    <thead>
                        <tr>           
                             <td>Nama Pegawai</td><td>:</td>
                             <td>
                                <input type="hidden" id="reqPegawaiId" name="reqPegawaiId" class="easyui-validatebox" value="<?=$reqPegawaiId?>" />
                             	<input type="text"  id="reqNamaPegawai" name="reqNamaPegawai" value="<?=$reqNamaPegawai?>" size="50px">
                                <img src="<?=base_url()?>images/icon-tambah.png" onClick="openPencarianPegawai()">
                            </td>			
                        </tr>
                        <tr>
                            <td>No Nota Dinas</td>
                            <td>:</td>
                            <td>
                               <input type="text" id="reqNoNotaDinas" name="reqNoNotaDinas" class="easyui-validatebox" style="width:350px;" value="<?=$reqNoNotaDinas?>" required="required" />
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
                            <td>Alasan</td>
                            <td>:</td>
                            <td>
                                <textarea name="reqAlasan" style="height:70px; width:250px;"><?=$reqAlasan?></textarea>
                            </td>
                        </tr>   
                        <tr>
                            <td>Upload Nota Dinas</td>
                            <td>:</td>
                            <td>
                               <input type="file" style="width:200px" name="reqNotaDinas" id="reqNotaDinas" value="<?=$reqNotaDinas?>" />
                               <input type="hidden" name="reqNotaDinasTemp" value="<?=$reqNotaDinasTemp?>" />
                               <br />temp : <label id="idImageNama"><?=$reqNotaDinasTemp?></label>  <img src="images/tree-delete.png" onClick="deleteImage();">
                            </td>
                            <td>&nbsp;</td>
                        </tr>
                    </thead>
                    </table>
                    <input type="hidden" name="reqTahun" value="<?=$reqTahun?>" />
                    <input type="hidden" name="reqId" value="<?=$reqId?>" />
                    <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
                    <input type="submit" id="reqSubmit" name="reqSubmit" class="btn btn-primary"  value="Submit" />
            </form>
        </div>
        </div>
    </div>
</body>
</html>