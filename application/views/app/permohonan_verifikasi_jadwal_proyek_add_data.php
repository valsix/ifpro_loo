<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

/* CHECK USER LOGIN 
$CI =& get_instance();
$CI->checkUserLogin();*/

$this->load->model('PermohonanProyek');

$permohonan_proyek = new PermohonanProyek();

$reqId = $this->input->get("reqId");
$reqMode = $this->input->get("reqMode");

if($reqId == ""){
	
	$reqTanggal = date("d-m-Y");
	$reqTahun = date("Y");
	$reqJamMasukAwal = "05:00";
	$reqJamMasukAkhir = "15:59";
	$reqJamPulangAwal = "16:00";
	$reqJamPulangAkhir = "04:59";
}
else
{
	$permohonan_proyek->selectByParamsMonitoringVerifikator($this->ID, array('PERMOHONAN_PROYEK_ID'=>$reqId), -1, -1);
	$permohonan_proyek->firstRow();
	
	$reqNomor				= $permohonan_proyek->getField('NOMOR');
	$reqNama				= $permohonan_proyek->getField('NAMA');
	$reqNamaPegawaiPM		= $permohonan_proyek->getField('NAMA_PEGAWAI');
	$reqPegawaiIdPM			= $permohonan_proyek->getField('PEGAWAI_ID_PM');
	$reqCabangId			= $permohonan_proyek->getField('CABANG_ID');
	$reqNamaCabang			= $permohonan_proyek->getField('NAMA_CABANG');
	$reqTanggal				= $permohonan_proyek->getField('TANGGAL');
	$reqTanggalAwal			= $permohonan_proyek->getField('TANGGAL_AWAL');
	$reqTanggalAkhir		= $permohonan_proyek->getField('TANGGAL_AKHIR');
	
	$reqJamMasukAwal		= $permohonan_proyek->getField('JAM_MASUK_AWAL');
	$reqJamMasukAkhir		= $permohonan_proyek->getField('JAM_MASUK_AKHIR');
	$reqJamPulangAwal		= $permohonan_proyek->getField('JAM_PULANG_AWAL');
	$reqJamPulangAkhir		= $permohonan_proyek->getField('JAM_PULANG_AKHIR');
	
	$reqNomorMesin			= $permohonan_proyek->getField('NOMOR_MESIN');
	$reqPegawaiIdApproval1	= $permohonan_proyek->getField('PEGAWAI_ID_APPROVAL1');
	$reqPegawaiIdApproval2	= $permohonan_proyek->getField('PEGAWAI_ID_APPROVAL2');
	
	$reqApproval1 			= $permohonan_proyek->getField("APPROVAL1");
	$reqApproval2 			= $permohonan_proyek->getField("APPROVAL2");
	$reqApprovalKe 			= $permohonan_proyek->getField("APPROVAL_KE");
	
	$reqLampiran 			= $permohonan_proyek->getField("LAMPIRAN");
	
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
		url:'<?=base_url()?>permohonan_proyek_json/approval',
		onSubmit:function(){
			var f = this;
			var opts = $.data(this, 'form').options;
			if($(this).form('validate') == false){
				return false;
			}
			<? if ($reqApproval1 == "Verifikasi" && $reqApprovalKe == "2") { ?>
			$.messager.confirm('Konfirmasi','Approval 1 belum memverifikasi, apakah anda yakin ingin melanjutkan?',function(r){
				if (r){
					var onSubmit = opts.onSubmit;
					opts.onSubmit = function(){};
					$(f).form('submit');
					opts.onSubmit = onSubmit;
				}
			})
			
			return false;
			<? 
			} 
			else 
			{ 
			?>
				return $(this).form('validate');
			<? 
			} 
			?>
			//return $(this).form('validate');
		},
		success:function(data){
			$.messager.alert('Info', data, 'info');	
			//$('#rst_form').click();
			top.frames['mainFrame'].location.reload();
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
                               <?=$reqNomor?>
                            </td>
                        </tr>
                        <tr>
                            <td>Nama Proyek</td>
                            <td>:</td>
                            <td>
                               <?=$reqNama?>
                            </td>
                        </tr>
                        <tr>
                        	<td>Pegawai PM</td>
                            <td>:</td>
                            <td>
                                <?=$reqNamaPegawaiPM?>
                            </td>
                        </tr>
                        <tr>
                        	<td>Lokasi</td>
                            <td>:</td>
                            <td>
                                <?=$reqNamaCabang?>
                            </td>
                        </tr>
                        <tr>
                            <td>Tanggal</td>
                            <td>:</td>
                            <td>
                              <?=$reqTanggal?>
                            </td>
                        </tr>   
                        <tr>
                            <td>Tanggal Awal</td>
                            <td>:</td>
                            <td>
                                <?=$reqTanggalAwal?>
                            </td>
                        </tr>  
                        <tr>
                            <td>Tanggal Akhir</td>
                            <td>:</td>
                            <td>
                                <?=$reqTanggalAkhir?>
                            </td>
                        </tr>
                        <tr>
                            <td>Toleransi Jam Masuk</td>
                            <td>:</td>
                            <td>
                                <?=$reqJamMasukAwal?> s/d <?=$reqJamMasukAkhir?> * (format = hh:mm)
                            </td>
                        </tr>  
                        <tr>
                            <td>Toleransi Jam Pulang</td>
                            <td>:</td>
                            <td>
                                <?=$reqJamPulangAwal?> s/d <?=$reqJamPulangAkhir?> * (format = hh:mm)
                            </td>
                        </tr>  
                        <tr>
                            <td>Nomor Mesin</td>
                            <td>:</td>
                            <td>
                               <?=$reqNomorMesin?>
                            </td>
                        </tr>
                        <tr>
                            <td>Lampiran Pendukung</td>
                            <td>:</td>
                            <td colspan="4">
                            <? 
                                if ($reqLampiran == "") 
                                {}
                                else
                                {
                                ?>
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
                                <?
                                }
                                ?>
                            </td>
                        </tr>
                        <?
						if ($reqMode == "view" && $reqPegawaiIdPM == $this->ID)
						{
                        ?>
                        <tr>
                        	<th colspan="3">Approval <?=$permohonan_proyek->getField("NAMA_APPROVAL1")?></th>
                        </tr>
                        <tr>
                            <td>Persetujuan</td>
                            <td>:</td>
                            <td>
                                <?=$permohonan_proyek->getField("APPROVAL1")?>
                            </td>
                        </tr>
                        <?
                            if ($permohonan_proyek->getField("APPROVAL1") == "Ditolak")
							{
							?>
                                <tr>
                                    <td>Alasan Penolakan</td>
                                    <td>:</td>
                                    <td>
                                        <?=$permohonan_proyek->getField("ALASAN_TOLAK1")?>
                                    </td>
                                </tr>
                        <?
							}
						?>
                        <tr>
                        	<th colspan="3">Approval <?=$permohonan_proyek->getField("NAMA_APPROVAL2")?></th>
                        </tr>
                        <tr>
                            <td>Persetujuan</td>
                            <td>:</td>
                            <td>
                                <?=$permohonan_proyek->getField("APPROVAL2")?>
                            </td>
                        </tr>
                        <?
                            if ($permohonan_proyek->getField("APPROVAL2") == "Ditolak")
							{
							?>
                                <tr>
                                    <td>Alasan Penolakan</td>
                                    <td>:</td>
                                    <td>
                                        <?=$permohonan_proyek->getField("ALASAN_TOLAK2")?>
                                    </td>
                                </tr>
                        <?
							}
						} 
						if ($reqMode == "view" && $reqApproval != "")
						{
                        ?>
                        <tr>
                        	<th colspan="3">Approval</th>
                        </tr>
                        <tr>
                            <td>Persetujuan Anda</td>
                            <td>:</td>
                            <td>
                                <?=$permohonan_proyek->getField("STATUS_APPROVAL")?>&nbsp;<?=$permohonan_proyek->getField("ALASAN_TOLAK")?>
                            </td>
                        </tr>
                        <tr>
                            <td>Persetujuan Lainnya</td>
                            <td>:</td>
                            <td>
                                <?=$permohonan_proyek->getField("STATUS_APPROVAL_LAIN")?>
                            </td>
                        </tr>
                        <?
						} 
						else if ($reqMode == "update")
						{
						?>
                        <tr>
                        	<th colspan="3">Approval</th>
                        </tr>
                        <tr>
                            <td>Persetujuan Lainnya</td>
                            <td>:</td>
                            <td>
                                <?=$permohonan_proyek->getField("STATUS_APPROVAL_LAIN")?>
                            </td>
                        </tr>
                        <tr>
                            <td>Persetujuan Anda</td>
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
                        <?
                        } 
						?>
                    </thead>
                    </table>
                    <input type="hidden" name="reqApprovalKe" value="<?=$reqApprovalKe?>" />
                    <input type="hidden" name="reqPegawaiId" value="<?=$reqPegawaiId?>" />
                    <input type="hidden" name="reqId" value="<?=$reqId?>" />
                    <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
                    <input type="hidden" name="reqTahun" value="<?=$reqTahun?>" />
                    <?
					if($reqMode == "update")
					{
                    ?>
                    <input type="submit" id="reqSubmit" name="reqSubmit"  class="btn btn-primary" value="Submit" />
                    <input type="reset" id="reqReset"  class="btn btn-primary" value="Reset" />
                    <?
					}
                    ?>
                    </form>
        </div>
        </div>
    </div>
</body>
</html>