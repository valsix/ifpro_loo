<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

/* CHECK USER LOGIN 
$CI =& get_instance();
$CI->checkUserLogin();*/

$this->load->model('PermohonanProyek');
$this->load->model('PermohonanProyekPerubahan');
$this->load->model('Pegawai');

$permohonan_proyek = new PermohonanProyek();
$permohonan_proyek_perubahan = new PermohonanProyekPerubahan();

$reqId = $this->input->get("reqId");
$reqPermohoananProyekPerubahanId = $this->input->get("reqPermohoananProyekPerubahanId");

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



if($reqPermohoananProyekPerubahanId == "")
	$statement = " AND PERMOHONAN_PROYEK_ID = '".$reqId."' AND (APPROVAL1 NOT IN ('T', 'Y') OR APPROVAL2 NOT IN ('T', 'Y'))";
else
	$statement = " AND PERMOHONAN_PROYEK_PERUBAHAN_ID = '".$reqPermohoananProyekPerubahanId."'";
	
$permohonan_proyek_perubahan->selectByParams(array(), -1, -1, $statement);
$permohonan_proyek_perubahan->firstRow();

$reqPermohoananProyekPerubahanId 	= $permohonan_proyek_perubahan->getField("PERMOHONAN_PROYEK_PERUBAHAN_ID");
$reqTanggalAkhirSebelum				= $permohonan_proyek_perubahan->getField("TANGGAL_AKHIR_SEBELUM");
$reqTanggalAkhirSesudah			 	= $permohonan_proyek_perubahan->getField("TANGGAL_AKHIR_SESUDAH");
$reqKeterangan					 	= $permohonan_proyek_perubahan->getField("KETERANGAN");
$reqLampiran					 	= $permohonan_proyek_perubahan->getField("LAMPIRAN");
$reqPegawaiIdApproval1			 	= $permohonan_proyek_perubahan->getField("PEGAWAI_ID_APPROVAL1");
$reqPegawaiIdApproval2			 	= $permohonan_proyek_perubahan->getField("PEGAWAI_ID_APPROVAL2");

if($reqPermohoananProyekPerubahanId == ""){
	$reqMode = "insert";
}
else
{
	$reqMode = "update";
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
		url:'<?=base_url()?>permohonan_proyek_perubahan_json/add',
		onSubmit:function(){
			if("<?=$reqMode?>" == "insert")
			{
				if(document.getElementsByClassName('MultiFile-title').length < 1)
				{
					$.messager.alert('Info', "Mohon Upload Lampiran Pendukung", 'info');	
					return false;
				}	
			}
			else
			{
				if(document.getElementsByClassName('MultiFile-title').length < 1 && $("#tableUpload tr").length < 1)
				{
					$.messager.alert('Info', "Mohon Upload Lampiran Pendukung", 'info');	
					return false;
				}
				else
				{
					return $(this).form('validate');
				}
			}
		},
		success:function(data){
			$.messager.alert('Info', data, 'info');	
			//$('#rst_form').click();
			top.frames['mainFrame'].location.reload();
			document.location.reload();
		}
	});
		
	$('#reqTanggalAkhirSesudah').datebox({
		onSelect: function(date){
			/*
			var awal_sebelum = $('#reqTanggalAwal').datebox('getValue');
			var akhir_sebelum = $('#reqTanggalAkhir').datebox('getValue');	
			var akhir_sesudah = $('#reqTanggalAkhirSesudah').datebox('getValue');	
			*/
			
			var nama_proyek = $('#reqNamaProyek').val();
			var awal_sebelum = $('#reqTanggalAwal').val();
			var akhir_sebelum = $('#reqTanggalAkhir').val();	
			var akhir_sesudah = $('#reqTanggalAkhirSesudah').datebox('getValue');	
			
			var selisih = get_day_between(awal_sebelum, akhir_sesudah);
			
			if(nama_proyek == "")
			{
				$('#reqTanggalAkhirSesudah').datebox('setValue', '');	
				$.messager.alert('Info', "Pilih Proyek Terlebih Dahulu.", 'info');	
				return;
			}
			
			if(Number(selisih) <= 0 )
			{
				$('#reqTanggalAkhirSesudah').datebox('setValue', '');	
				$.messager.alert('Info', "Tanggal akhir perubahan proyek lebih kecil dari tanggal awal proyek.", 'info');	
				return;
			}
			
			if(akhir_sesudah == akhir_sebelum)
			{
				$('#reqTanggalAkhirSesudah').datebox('setValue', '');	
				$.messager.alert('Info', "Tanggal akhir perubahan proyek sama dengan tanggal akhir proyek.", 'info');	
				return;
			}
		}
	});
	
});

function pilih(namaProyek, id, tanggalAwal, tanggalAkhir)
{
	document.location.href = "<?=base_url()?>app/loadUrl/app/permohonan_perubahan_jadwal_proyek_add/?reqId="+id;
}

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
	<div id="judul-popup">Data Perubahan Jadwal Proyek</div>
	<div id="konten">
    	<div id="popup-tabel2">
            <form id="ff" method="post" novalidate enctype="multipart/form-data">
            <table class="table">
                <thead>
                    <tr>
                        <th colspan="6">Data Proyek</th>
                    </tr>
                    <tr>
                    	<td>Nama Proyek</td>
                        <td>:</td>
                        <td colspan="4">
                        	<input type="text" id="reqNamaProyek" name="reqNamaProyek" style="width:350px;" class="easyui-validatebox" value="<?=$reqNama?>" readonly required> 
                            <? 
							if($reqPermohoananProyekPerubahanId == "") 
							{ 
							?>
                            	<input class="btn btn-xs btn-success" type="button" onClick="openPopup('<?=base_url()?>app/loadUrl/app/proyek_pencarian')" value="Browse"/>
                            <?
							}
                            ?>
                            <input type="hidden" id="reqPermohonanProyekId" name="reqPermohonanProyekId" value="<?=$reqId?>" />
                        </td>
                    </tr>
                    <?
					if($reqId == "")
					{}
					else
					{
                    ?>
                    <tr>
                        <td>Nomor WO</td>
                        <td>:</td>
                        <td>
                            <span><?=$reqNomor?></span>
                        </td>
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
                        <td>Nomor Mesin</td>
                        <td>:</td>
                        <td>
                            <span><?=$reqNomorMesin?></span>
                        </td>
                    </tr>
                    <!--
                    <tr>
                        <td>Tanggal</td>
                        <td>:</td>
                        <td>
                            <span><?=$reqTanggal?></span>
                        </td>
                    </tr>   
                    -->
                    <tr>
                        <td>Tanggal Awal</td>
                        <td>:</td>
                        <td>
                            <span><?=$reqTanggalAwal?></span>
                        </td>
                        <td>Tanggal Akhir</td>
                        <td>:</td>
                        <td>
                            <span><?=$reqTanggalAkhir?></span>
                        </td>
                    </tr>
                    <!--
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
                    -->
                    <?
					}
                    ?>
                </thead>
			</table>
            
            <table class="table">
                <thead>
                    <tr>
                        <th colspan="3">Permohonan Perubahan Jadwal Proyek</th>
                    </tr>
                    <tr>
                        <td>Tanggal Akhir Perubahan</td>
                        <td>:</td>
                        <td>
                        	<input type="hidden" id="reqTanggalAkhirSebelum" name="reqTanggalAkhirSebelum" value="<?=$reqTanggalAkhir?>" required>
                            <input class="easyui-datebox" id="reqTanggalAkhirSesudah" name="reqTanggalAkhirSesudah" value="<?=$reqTanggalAkhirSesudah?>" required>
                        </td>
                    </tr>
                    <tr>
                        <td>Alasan Perubahan</td>
                        <td>:</td>
                        <td>
                        	<textarea name="reqKeterangan" style="width:350px; height:100px;"><?=$reqKeterangan?></textarea>
                        </td>
                    </tr>
                    <tr>
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
										
										<td><img src="<?=base_url()?>images/icon-download.png" /><a href="<?=base_url()?>uploads/perubahan_proyek/<?=$arrLampiran[$i]?>" target="_blank">File <?=($i+1)?></a><input type="hidden" name="reqLampiranTemp[]" value="<?=$arrLampiran[$i]?>" /></td>
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
					?>
                    <tr>
                        <td>Approval Asman Teknik</td>
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
            
            <input type="hidden" name="reqId" value="<?=$reqPermohoananProyekPerubahanId?>" />
            <input type="hidden" id="reqTanggalAwal" name="reqTanggalAwal" value="<?=$reqTanggalAwal?>" required>
            <input type="hidden" id="reqTanggalAkhir" name="reqTanggalAkhir" value="<?=$reqTanggalAkhir?>" required>
            <!--<input type="hidden" name="reqPermohonanProyekId" value="<?=$reqId?>" />-->
            <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
            <input type="submit" name="reqSubmit"  class="btn btn-primary" value="Submit" />
            <input type="reset" id="rst_form"  class="btn btn-primary" value="Reset" />
            
            </form>
        </div>
        </div>
    </div>
</body>
</html>