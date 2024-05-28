<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

/* CHECK USER LOGIN 
$CI =& get_instance();
$CI->checkUserLogin();*/

$this->load->model('PermohonanProyek');
$this->load->model('Pegawai');

$permohonan_proyek = new PermohonanProyek();
$permohonan_proyek_parent = new PermohonanProyek();

$reqId = $this->input->get("reqId");
$reqMode = $this->input->get("reqMode");
$reqPeriode = $this->input->get("reqPeriode");

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
	
	$reqNomor						= $permohonan_proyek->getField('NOMOR');
	$reqNama						= $permohonan_proyek->getField('NAMA');
	$reqNamaPegawaiPM				= $permohonan_proyek->getField('NAMA_PEGAWAI');
	$reqPegawaiIdPM					= $permohonan_proyek->getField('PEGAWAI_ID_PM');
	$reqCabangId					= $permohonan_proyek->getField('CABANG_ID');
	$reqNamaCabang					= $permohonan_proyek->getField('NAMA_CABANG');
	$reqTanggal						= $permohonan_proyek->getField('TANGGAL');
	$reqTanggalAwal					= $permohonan_proyek->getField('TANGGAL_AWAL');
	$reqTanggalAkhir				= $permohonan_proyek->getField('TANGGAL_AKHIR');
	
	$reqJamMasukAwal				= $permohonan_proyek->getField('JAM_MASUK_AWAL');
	$reqJamMasukAkhir				= $permohonan_proyek->getField('JAM_MASUK_AKHIR');
	$reqJamPulangAwal				= $permohonan_proyek->getField('JAM_PULANG_AWAL');
	$reqJamPulangAkhir				= $permohonan_proyek->getField('JAM_PULANG_AKHIR');
	
	$reqJamMasuk					= $permohonan_proyek->getField('JAM_MASUK');
	$reqJamPulang					= $permohonan_proyek->getField('JAM_PULANG');
	
	$reqNomorMesin					= $permohonan_proyek->getField('NOMOR_MESIN');
	$reqPegawaiIdApproval1			= $permohonan_proyek->getField('PEGAWAI_ID_APPROVAL1');
	$reqPegawaiIdApproval2			= $permohonan_proyek->getField('PEGAWAI_ID_APPROVAL2');
	
	$reqLampiran					= $permohonan_proyek->getField("LAMPIRAN");
	$reqPermohonanProyekParentId	= $permohonan_proyek->getField("PERMOHONAN_PROYEK_PARENT_ID");
	
	$permohonan_proyek_parent->selectByParams(array('PERMOHONAN_PROYEK_ID'=>$reqPermohonanProyekParentId), -1, -1);
	$permohonan_proyek_parent->firstRow();
	
	$reqNamaProyek			= $permohonan_proyek_parent->getField("NAMA");
	$reqTanggalAwalProyek 	= $permohonan_proyek_parent->getField("TANGGAL_AWAL");
	$reqTanggalAkhirProyek 	= $permohonan_proyek_parent->getField("TANGGAL_AKHIR");
	
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
			data = data.split("-");
			$.messager.alert('Info', data[1], 'info');	
			//$('#rst_form').click();
			top.frames['mainFrame'].location.reload();
			window.parent.location.href = "<?=base_url()?>app/loadUrl/app/permohonan_jadwal_proyek_garansi_add/?reqId=" + data[0];
			//top.closePopup();
		}
	});
	
	$('#reqTanggalAwal').datebox({
		onSelect: function(date){
			var proyek_id = $('#reqPermohonanProyekId').val();
			var selesai_proyek = $('#reqTanggalAkhirProyek').val();
			var mulai = $('#reqTanggalAwal').datebox('getValue');
			
			var jumlah = compareDates(mulai, "d-M-y", selesai_proyek, "d-M-y");
			
			if(proyek_id == "")
			{
				$('#reqTanggalAwal').datebox('setValue', '');
				$('#reqTanggalAkhir').datebox('setValue', '');
				$.messager.alert('Info', 'Pilih Nama Proyek Terlebih Dahulu.', 'info');	
				return;
			}
			
			if(jumlah == 0)
			{
				$('#reqTanggalAwal').datebox('setValue', '');
				$('#reqTanggalAkhir').datebox('setValue', '');
				$.messager.alert('Info', 'Tanggal awal Garansi Proyek tidak boleh lebih kecil dari tanggal akhir proyek.', 'info');	
				return;
			}
			
			$('#reqTanggalAkhir').datebox('setValue', '');
		}
	});
		
	$('#reqTanggalAkhir').datebox({
		onSelect: function(date){
			var proyek_id = $('#reqPermohonanProyekId').val();
			var mulai = $('#reqTanggalAwal').datebox('getValue');	
			var selesai = $('#reqTanggalAkhir').datebox('getValue');
			
			if(proyek_id == "")
			{
				$('#reqTanggalAwal').datebox('setValue', '');
				$('#reqTanggalAkhir').datebox('setValue', '');
				$.messager.alert('Info', 'Pilih Nama Proyek Terlebih Dahulu.', 'info');	
				return;
			}
			
			var selisih = get_day_between(mulai, selesai);
			
			if(mulai == "")
			{
				$('#reqTanggalAkhir').datebox('setValue', '');	
				$.messager.alert('Info', "Isi tanggal mulai terlebih dahulu.", 'info');		
				return;
			}
			
			if(Number(selisih) <= 0)
			{
				$('#reqTanggalAkhir').datebox('setValue', '');	
				$.messager.alert('Info', "Tanggal akhir lebih kecil.", 'info');	
				return;
			}
			
			
		}
	});
	
});

function pilih(namaProyek, id, tanggalAwal, tanggalAkhir)
{
	$("#reqPermohonanProyekId").val(id);
	$("#reqNama").val(namaProyek);
	
	$("#reqTanggalAwalProyek").val(tanggalAwal);
	$("#reqTanggalAkhirProyek").val(tanggalAkhir);
	
	$("#infoTanggalAwal").text();
	$("#infoTanggalAkhir").text();
	
	$("#infoTanggalAwal").text(tanggalAwal);
	$("#infoTanggalAkhir").text(tanggalAkhir);
	
	$("#trTanggalAwal").show();
	$("#trTanggalAkhir").show();
	
	//document.location.href = "<?=base_url()?>app/loadUrl/app/permohonan_perubahan_jadwal_proyek_add/?reqId="+id;
	
}

function create(namaPegawai, nrp)
{
	$("#reqNamaPegawaiPM").val(namaPegawai);
	$("#reqPegawaiIdPM").val(nrp);
}

function createRow(id)
{
	var jqxhr = $.get( "<?=base_url()?>cabang_json/getData/?reqId="+id, function(data) {
		$("#reqCabangId").val(id);
		$("#reqNamaCabang").val(data.NAMA);
	}, "json" );
}	

function createProyek(nomor, nama)
{
	$("#reqNomor").val(nomor);
	$("#reqNama").val(nama);
}

	<?
	if($reqMode == "view")
	{
	?>
	$("$btnCari").hide();
	$("#reqSubmit").hide();
	$("#reqReset").hide();
	$("input, textarea, select").prop("disabled", true);
	<?
	}
	?>	

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
	<div id="judul-popup">Data Garansi Proyek</div>
	<div id="konten">
    	<div id="popup-tabel2">
            <form id="ff" method="post" novalidate enctype="multipart/form-data">
            <table class="table">
                    <thead>
                    	<tr>
                        	<th colspan="3">Permohonan</th>
                        </tr>
                        <tr>
                            <td>Nama Proyek</td>
                            <td>:</td>
                            <td>
                            	<input type="hidden" id="reqPermohonanProyekId" name="reqPermohonanProyekId" value="<?=$reqPermohonanProyekParentId?>" />
                               	<input type="text" id="reqNama" name="reqNamaProyek" class="easyui-validatebox" style="width:350px;" value="<?=$reqNamaProyek?>" required="required" readonly="readonly" />
                               	<input id="btnCari" class="btn btn-xs btn-success" type="button" onClick="openPopup('<?=base_url()?>app/loadUrl/app/proyek_pencarian_garansi/')" value="Browse"/>
                            </td>
                        </tr>   
                        <tr id="trTanggalAwal" <? if($reqId == "") { ?> style="display:none" <? } ?>>
                        	<td>Tanggal Awal Proyek</td>
                            <td>:</td>
                            <td><span id="infoTanggalAwal"><?=$reqTanggalAwalProyek?></span></td>
                        </tr> 
                        <tr id="trTanggalAkhir" <? if($reqId == "") { ?> style="display:none" <? } ?>>
                        	<td>Tanggal Akhir Proyek</td>
                            <td>:</td>
                            <td><span id="infoTanggalAkhir"><?=$reqTanggalAkhirProyek?></span></td>
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
                            <td>Jam Masuk</td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqJamMasuk" name="reqJamMasuk" size="6" class="easyui-validatebox" value="<?=$reqJamMasuk?>" onkeydown="return format_menit(event,'reqJamMasuk');" maxlength="5"> * (format = hh:mm)
                            </td>
                        </tr>  
                        <tr>
                            <td>Jam Pulang</td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqJamPulang" name="reqJamPulang" size="6" class="easyui-validatebox" value="<?=$reqJamPulang?>" onkeydown="return format_menit(event,'reqJamPulang');" maxlength="5"> * (format = hh:mm)
                            </td>
                        </tr>  
                        <tr>
                            <td>Toleransi Jam Masuk</td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqJamMasukAwal" name="reqJamMasukAwal" size="6" class="easyui-validatebox" value="<?=$reqJamMasukAwal?>" onkeydown="return format_menit(event,'reqJamMasukAwal');" maxlength="5"> s/d
                                <input type="text" id="reqJamMasukAkhir" name="reqJamMasukAkhir" size="6" class="easyui-validatebox" value="<?=$reqJamMasukAkhir?>" onkeydown="return format_menit(event,'reqJamMasukAkhir');" maxlength="5"> * (format = hh:mm)
                            </td>
                        </tr>  
                        <tr>
                            <td>Toleransi Jam Pulang</td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqJamPulangAwal" name="reqJamPulangAwal" size="6" class="easyui-validatebox" value="<?=$reqJamPulangAwal?>" onkeydown="return format_menit(event,'reqJamPulangAwal');" maxlength="5"> s/d
                                <input type="text" id="reqJamPulangAkhir" name="reqJamPulangAkhir" size="6" class="easyui-validatebox" value="<?=$reqJamPulangAkhir?>" onkeydown="return format_menit(event,'reqJamPulangAkhir');" maxlength="5"> * (format = hh:mm)
                            </td>
                        </tr>
                        <!--<tr>
                            <td>Lampiran Pendukung</td>
                            <td>:</td>
                            <td>
                        		<input name="reqLampiran[]" type="file" multiple class="maxsize-1024" accept="pdf|jpg|jpeg" id="reqLampiran" value="" />
                            </td>
                        </tr>
                        <? 
						if ($reqLampiran == "") 
						{}
						else
						{
						?>
							<tr>
								<td>Dokumen</td>
								<td>:</td>
								<td colspan="4">
									<table id="tableUpload">
									<thead></thead>
									<tbody>
									<?
									$arrLampiran = explode(",", $reqLampiran);
									if($reqLampiran == "")
									{}
									else
									{
										for($i=0;$i<count($arrLampiran);$i++)
										{
										?>
											<tr>
											
											<td><img src="<?=base_url()?>images/icon-download.png" /><a href="<?=base_url()?>uploads/proyek/<?=$arrLampiran[$i]?>" target="_blank">File <?=($i+1)?></a><input type="hidden" name="reqLampiranTemp[]" value="<?=$arrLampiran[$i]?>" /></td>
											<td id="tdHapus"><a class="hapus" style="cursor:pointer" onclick="$(this).parent().parent().remove();"><img src="<?=base_url()?>images/icon-hapus.png" /></a></td>
											</tr>
										<?
										}
									}
									?>
									</tbody>
									</table>
								</td>
							</tr>
						<?
						}
						?>-->
                        <tr>
                            <td>Approval SPV Teknik</td>
                            <td>:</td>
                            <td>
                            	<?
								$pegawai_spv = new Pegawai();
								$pegawai_spv->selectByParams(array("A.JABATAN_ID" => 'KP00137D'));
								$pegawai_spv->firstRow();
								
								$reqPegawaiId = $pegawai_spv->getField("PEGAWAI_ID");
                                ?>
                                <input type="hidden" name="reqPegawaiIdApproval1" id="reqPegawaiIdApproval1" value="<?=$reqPegawaiId?>" />
                                <span><?=$pegawai_spv->getField("NAMA")." (".trim($pegawai_spv->getField("NAMA_STAFF"))." ".trim($pegawai_spv->getField("JABATAN")).")"?></span>
                                <!--
                                <input class="easyui-combobox" name="reqPegawaiIdApproval1" id="reqPegawaiIdApproval1" style="width:400px;" data-options="
                                    url: '<?=base_url()?>pegawai_json/combo_asman_proyek/?reqMode=jadwal',
                                    method: 'get',
                                    valueField:'id', 
                                    textField:'nama',
                                    editable:false
                                " value="<?=$reqPegawaiIdApproval1?>" required="required">
                                <a id="clueTipBox1" class="clueTipBox" title="Panduan|Apabila data SPV Admin tidak muncul, hubungi Administrator."><img src="<?=base_url()?>images/help.png"></a>
                                -->
                            </td>
                        </tr>        
                        <tr>
                            <td>Approval Manager Teknik</td>
                            <td>:</td>
                            <td>
                            	<?
								$pegawai_manager = new Pegawai();
								$pegawai_manager->selectByParams(array("A.JABATAN_ID" => 'KP00136D'));
								$pegawai_manager->firstRow();
								
								$reqPegawaiId = $pegawai_manager->getField("PEGAWAI_ID");
                                ?>
                                <input type="hidden" name="reqPegawaiIdApproval2" id="reqPegawaiIdApproval2" value="<?=$reqPegawaiId?>" />
                                <span><?=$pegawai_manager->getField("NAMA")." (".trim($pegawai_manager->getField("NAMA_STAFF"))." ".trim($pegawai_manager->getField("JABATAN")).")"?></span>
                               <!-- 
                               <input class="easyui-combobox" name="reqPegawaiIdApproval2" id="reqPegawaiIdApproval2" style="width:400px;" data-options="
                                    url: '<?=base_url()?>pegawai_json/combo_manajer_proyek/?reqMode=jadwal',
                                    method: 'get',
                                    valueField:'id', 
                                    textField:'nama',
                                    editable:false
                                " value="<?=$reqPegawaiIdApproval2?>" required>
                                <a id="clueTipBox2" class="clueTipBox" title="Panduan|Apabila data Manager Admin tidak muncul, hubungi Administrator."><img src="<?=base_url()?>images/help.png"></a>
                                -->
                            </td>
                        </tr>
                    </thead>
                    </table>
                    <input type="hidden" name="reqPegawaiId" value="<?=$reqPegawaiId?>" />
                    <input type="hidden" name="reqId" value="<?=$reqId?>" />
                    <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
                    <input type="hidden" name="reqTahun" value="<?=$reqTahun?>" />
                    <input type="hidden" id="reqTanggalAwalProyek" name="reqTanggalAwalProyek" value="<?=$reqTanggalAwalProyek?>" />
                    <input type="hidden" id="reqTanggalAkhirProyek" name="reqTanggalAkhirProyek" value="<?=$reqTanggalAkhirProyek?>" />
                    <input type="submit" id="reqSubmit" name="reqSubmit"  class="btn btn-primary" value="Submit" />
                    <input type="reset" id="reqReset"  class="btn btn-primary" value="Reset" />
                    </form>
        </div>
        </div>
    </div>
</body>
</html>