<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

/* CHECK USER LOGIN 
$CI =& get_instance();
$CI->checkUserLogin();*/

$this->load->model('PermohonanProyek');
$this->load->model('ProyekPegawaiPulangAwal');

$permohonan_proyek = new PermohonanProyek();
$proyek_pegawai_pulang_awal = new ProyekPegawaiPulangAwal();

$reqId = $this->input->get("reqId");
$reqMode = $this->input->get("reqMode");

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
		url:'<?=base_url()?>proyek_pegawai_pulang_awal_json/approval',
		onSubmit:function(){
			return $(this).form('validate');
		},
		success:function(data){
			$.messager.alert('Info', data, 'info');	
			//$('#rst_form').click();
			top.frames['mainFrame'].location.reload();
			document.location.reload();
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
	
	$( "#btnSubmit" ).click(function() {
		$.messager.confirm('Konfirmasi', "Apakah Anda yakin Menyetujui Anggota Proyek Pulang Awal ?",function(r){
			if (r){
	  			$( "#reqSubmit" ).click();
			}
		});
	});
	
});

var nomor = 1000;
function create(nip)
{
	nomor += 1;
	$(function () {
		$.get("<?=base_url()?>app/loadUrl/app/permohonan_proyek_anggota_pulang_awal_template/?reqId="+nip+"&reqNomor="+nomor, function (data) {
			$("#tbData").append(data);
		});
	});
}

function openPopupProyek(page) {
	eModal.iframe(page, 'Aplikasi Presensi - PJB Services')
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
<script src="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/dist/js/eModal-upload.min.js"></script>
<script src="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/dist/js/eModal2.min.js"></script>-->
<script src="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/dist/js/eModal2-proyek.min.js"></script>
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
	<div id="judul-popup">Data Anggota Proyek</div>
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
                    <table class="table">
                    <thead>
                        <tr>
                            <th style="text-align:center; width:10%">NIP <? if($reqMode == "update") { ?><a style="cursor:pointer" id="btnAdd" title="Tambah" onClick="openPopupProyek('<?=base_url()?>app/loadUrl/main/pegawai_proyek_anggota/?reqId=<?=$reqId?>&reqJenis=verifikator')"><img src="<?=base_url()?>images/icon-tambah.png" /></a><? } ?></th>
                            <th style="text-align:center; width:20%">Nama</th>
                            <th style="text-align:center; width:10%">Jabatan</th>
                            <th style="text-align:center; width:15%">Tanggal Pulang Awal</th>
                            <th style="text-align:center; width:20%">Alasan Pulang Awal</th>
                            <th style="text-align:center; width:10%">Lampiran</th>
                            <th style="text-align:center; width:10%">Aksi</th>
                        </tr> 
                    </thead>
                    <tbody id="tbData">
                    <?
                    $proyek_pegawai_pulang_awal = new ProyekPegawaiPulangAwal();
					$proyek_pegawai_pulang_awal->selectByParamsVerifikator($this->ID, array("A.PERMOHONAN_PROYEK_ID" => $reqId));
					$proyek_pegawai_pulang_awal->firstRow();
					
					$reqApprovalKe = $proyek_pegawai_pulang_awal->getField("APPROVAL_KE");
					$reqPegawaiIdApproval1 = $proyek_pegawai_pulang_awal->getField("PEGAWAI_ID_APPROVAL1");
					$reqPegawaiIdApproval2 = $proyek_pegawai_pulang_awal->getField("PEGAWAI_ID_APPROVAL2");
                    
                    $i = 0;
					$proyek_pegawai_pulang_awal->selectByParamsApproval($this->ID, array("A.PERMOHONAN_PROYEK_ID" => $reqId));
                    while($proyek_pegawai_pulang_awal->nextRow())
                    {
                        $reqLampiran = $proyek_pegawai_pulang_awal->getField("LAMPIRAN");
						$reqApproval = $proyek_pegawai_pulang_awal->getField("APPROVAL");
                    ?>
                        <tr id="<?=$i?>">
                            <td style="width:10%"><?=$proyek_pegawai_pulang_awal->getField("PEGAWAI_ID")?></td>
                            <td style="width:20%"><?=$proyek_pegawai_pulang_awal->getField("NAMA_PEGAWAI")?></td>
                            <td style="width:10%"><?=$proyek_pegawai_pulang_awal->getField("NAMA_JABATAN_PROYEK")?></td>
                            <td style="width:10%">
                            	<?
								if($reqApproval == "Y")
								{
								?>
									<span><?=$proyek_pegawai_pulang_awal->getField("TANGGAL_PULANG")?></span>
                                    <input type="hidden" id="reqTanggalPulang<?=$i?>" name="reqTanggalPulang[]" value="<?=$proyek_pegawai_pulang_awal->getField("TANGGAL_PULANG")?>"/>
								<?
								}
								else
								{
                                ?>
                        			<input type="text" class="easyui-datebox" id="reqTanggalPulang<?=$i?>" name="reqTanggalPulang[]" value="<?=$proyek_pegawai_pulang_awal->getField("TANGGAL_PULANG")?>" required="required"/>
                                <?
								}
                                ?>
                            </td>
                            <td style="width:20%">
                            	<?
								if($reqApproval == "Y")
								{
								?>
                                	<span><?=$proyek_pegawai_pulang_awal->getField("KETERANGAN")?></span>
                                    <input type="hidden" id="reqKeterangan<?=$i?>" name="reqKeterangan[]" value="<?=$proyek_pegawai_pulang_awal->getField("KETERANGAN")?>" />
                                <?
								}
								else
								{
                                ?>
                                	<input type="text" class="easyui-validatebox" id="reqKeterangan<?=$i?>" name="reqKeterangan[]" value="<?=$proyek_pegawai_pulang_awal->getField("KETERANGAN")?>" required="required" />
                                <?
								}
                                ?>
                            </td>
                            <td style="width:10%">
                            	<?
								if($reqApproval == "Y")
								{}
								else
								{
								?>
                            		<input name="reqLampiran[]" id="reqLampiran<?=$i?>" type="file" class="maxsize-1024" accept="pdf|jpg|jpeg" value="" />
                                <?
								}
                                ?>
                                <input name="reqLampiranTemp[]" type="hidden" id="reqLampiranTemp" value="<?=$reqLampiran?>" />
                                <?
                                if($reqLampiran == "")
                                {}
                                else
                                {
                                ?>
                                    <img src="<?=base_url()?>images/icon-download.png" /><a href="<?=base_url()?>uploads/pegawai_pulang_awal/<?=$reqLampiran?>" target="_blank">File</a>
                                <?
                                }
                                ?>
                            </td>
                            <td align="center">
                                <input type="hidden" name="reqProyekPegawaiPulangAwalId[]" value="<?=$proyek_pegawai_pulang_awal->getField("PROYEK_PEGAWAI_PULANG_AWAL_ID")?>">
                                <input type="hidden" name="reqPegawaiId[]" value="<?=$proyek_pegawai_pulang_awal->getField("PEGAWAI_ID")?>">
                                <input type="hidden" name="reqNama[]" value="<?=$proyek_pegawai_pulang_awal->getField("NAMA")?>">
                                <input type="hidden" name="reqApprovalKe[]" value="<?=$reqApprovalKe?>" />
                                <input type="hidden" name="reqPegawaiIdApproval1[]" value="<?=$reqPegawaiIdApproval1?>" />
                                <input type="hidden" name="reqPegawaiIdApproval2[]" value="<?=$reqPegawaiIdApproval2?>" />
                                <?
								if($proyek_pegawai_pulang_awal->getField("APPROVAL") == "Y")
								{
								?>
                                	<span> Disetujui </span>
								<?
								}
								else
								{
                                ?>
                                	<input class="btn btn-sm btn-danger" type="button" onClick="$('#reqDeletePegawaiPulangAwal').val($('#reqDeletePegawaiPulangAwal').val() + ',' + '<?=$proyek_pegawai_pulang_awal->getField("PROYEK_PEGAWAI_PULANG_AWAL_ID")?>'); $('#<?=$i?>').remove();" value="Hapus" />
                                <?
								}
                                ?>
                            </td>
                        </tr>                    
                    <?
                        $i++;
                    }
                    ?>
                    </tbody>
                    </table>
                    <input type="hidden" name="reqAlasan" value="Y" />
                    <input type="hidden" name="reqPegawaiId" value="<?=$reqPegawaiId?>" />
                    <input type="hidden" name="reqId" value="<?=$reqId?>" />
                    <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
                    <input type="hidden" name="reqTahun" value="<?=$reqTahun?>" />
                    <input type="hidden" name="reqDeletePegawaiPulangAwal" id="reqDeletePegawaiPulangAwal" value="0" />
                    <? 
					if($reqMode == "update") { 
					?>
                    <input type="button"  name="btnSubmit" id="btnSubmit" class="btn btn-primary" value="Submit"/>
                    <input type="submit" name="reqSubmit" id="reqSubmit"  class="btn btn-primary" value="Submit" style="display:none"/>
                    <!--<input type="reset" id="rst_form"  class="btn btn-primary" value="Reset" />-->
                    <?
					}
                    ?>
                    </form>
        </div>
        </div>
    </div>
</body>
</html>