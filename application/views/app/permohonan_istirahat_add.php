<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

/* CHECK USER LOGIN 
$CI =& get_instance();
$CI->checkUserLogin();*/

$this->load->model('PermohonanIstirahat');

$permohonan_istirahat= new PermohonanIstirahat();

$reqId = $this->input->get("reqId");
$reqMode = $this->input->get("reqMode");

if($reqId == "")
{ }
else
{
	$permohonan_istirahat->selectByParamsApproval($this->ID, array("PERMOHONAN_ISTIRAHAT_ID" => $reqId));
	$permohonan_istirahat->firstRow();
	$reqApprovalKe = $permohonan_istirahat->getField("APPROVAL_KE");
	$reqPegawaiId = $permohonan_istirahat->getField("PEGAWAI_ID");
	$reqTahun = $permohonan_istirahat->getField("TAHUN");
	$reqNomor = $permohonan_istirahat->getField("NOMOR");
	$reqTanggal = $permohonan_istirahat->getField("TANGGAL");
	$reqJabatan = $permohonan_istirahat->getField("JABATAN");
	$reqCabang = $permohonan_istirahat->getField("CABANG");
	$reqDepartemen = $permohonan_istirahat->getField("DEPARTEMEN");
	$reqSubDepartemen = $permohonan_istirahat->getField("SUB_DEPARTEMEN");
	$reqTanggalAwal = $permohonan_istirahat->getField("TANGGAL_AWAL");
	$reqTanggalAkhir = $permohonan_istirahat->getField("TANGGAL_AKHIR");
	$reqTanggalSebelum = $permohonan_istirahat->getField("TAMBAH_HARI_SEBELUM");
	$reqTanggalSesudah = $permohonan_istirahat->getField("TAMBAH_HARI_SESUDAH");
	$reqJumlahHari = $permohonan_istirahat->getField("LAMA_CUTI");
	$reqKeterangan = $permohonan_istirahat->getField("KETERANGAN");
	$reqAlamat = $permohonan_istirahat->getField("ALAMAT");
	$reqTelepon = $permohonan_istirahat->getField("TELEPON");
	$reqPegawaiIdApproval = $permohonan_istirahat->getField("PEGAWAI_ID_APPROVAL");
	$reqApproval = $permohonan_istirahat->getField("APPROVAL");
	$reqNamaPegawai = $permohonan_istirahat->getField("NAMA_PEGAWAI");
	$reqNamaCabang = $permohonan_istirahat->getField("NAMA_CABANG");
	$reqApprovalKeterangan = $permohonan_istirahat->getField("APPROVAL_KETERANGAN");
	$reqAlasanTolak = $permohonan_istirahat->getField("ALASAN_TOLAK");
	$reqKota = $permohonan_istirahat->getField("KOTA");
	$reqProvinsi = $permohonan_istirahat->getField("PROVINSI");
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
		url:'<?=base_url()?>permohonan_istirahat_json/approval',
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
		},
		success:function(data){
			$.messager.alert('Info', data, 'info');	
			$('#rst_form').click();
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


</head>

<body class="bg-kanan-full">
	<div id="judul-popup">Permohonan Istirahat</div>
	<div id="konten">
    	<div id="popup-tabel2">
            <form id="ff" method="post" novalidate enctype="multipart/form-data">
                    <table class="table">
                    <thead>
                    	<tr>
                        	<th colspan="3">Permohonan</th>
                        </tr>
                        <tr>
                            <td>Nomor</td>
                            <td>:</td>
                            <td>
                                <?=$reqNomor?>
                            </td>
                        </tr> 
                    	<tr>
                            <td style="width:20%">Nama Pegawai</td>
                            <td>:</td>
                            <td>
                                <?=$reqNamaPegawai?>
                          	</td>
                        </tr>        
                        <tr>
                            <td>Cabang</td>
                            <td>:</td>
                            <td>
                                <?=$reqNamaCabang?>
                          	</td>
                        </tr>   
                      	<tr>
                            <td>Tanggal Permohonan</td>
                            <td>:</td>
                            <td>
                              <?=$reqTanggal?>
                            </td>
                        </tr>  
                        <tr>
                            <td>Tanggal Istirahat</td>
                            <td>:</td>
                            <td>
                                <?=$reqTanggalAwal?> s/d  <?=$reqTanggalAkhir?>
                            </td>
                        </tr>       
                        <?
                        if($reqTanggalSebelum == "")
						{}
						else
						{
						?>
                        <tr style="display: none;">
                            <td>Penambahan Berangkat</td>
                            <td>:</td>
                            <td>
                                Ya (<?=$reqTanggalSebelum?>)
                            </td>
                        </tr>  
                        <?
						}
                        if($reqTanggalSesudah == "")
						{}
						else
						{
						?>
                        <tr style="display: none;">
                            <td>Penambahan Pulang</td>
                            <td>:</td>
                            <td>
                                Ya (<?=$reqTanggalSesudah?>)
                            </td>
                        </tr>  
                        <?
						}
						?>
                        <tr>
                            <td>Lama Istirahat</td>
                            <td>:</td>
                            <td>
                               <?=$reqJumlahHari?> Hari
                            </td>
                        </tr>
                        <tr>
                            <td>Keterangan</td>
                            <td>:</td>
                            <td>
                               <?=$reqKeterangan?>
                            </td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td>:</td>
                            <td>
                            	<?=$reqAlamat?>
                            </td>
                        </tr>
                        <tr>
                            <td>Kota</td>
                            <td>:</td>
                            <td>
                            	<?=$reqKota?> - <?=strtoupper($reqProvinsi)?>
                            </td>
                        </tr>
                        <tr>
                            <td>Telepon</td>
                            <td>:</td>
                            <td>
                               <?=$reqTelepon?>
                            </td>
                        </tr><?
						if ($reqMode == "view" && $reqPegawaiId == $this->ID)
						{
                        ?>
                        <tr>
                        	<th colspan="3">Approval <?=$permohonan_istirahat->getField("NAMA_APPROVAL1")?></th>
                        </tr>
                        <tr>
                            <td>Persetujuan</td>
                            <td>:</td>
                            <td>
                                <?=$permohonan_istirahat->getField("APPROVAL1")?>
                            </td>
                        </tr>
                        <?
                            if ($permohonan_istirahat->getField("APPROVAL1") == "Ditolak")
							{
							?>
                                <tr>
                                    <td>Alasan Penolakan</td>
                                    <td>:</td>
                                    <td>
                                        <?=$permohonan_istirahat->getField("ALASAN_TOLAK1")?>
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
                                <?=$permohonan_istirahat->getField("STATUS_APPROVAL")?>&nbsp;<?=$permohonan_istirahat->getField("ALASAN_TOLAK")?>
                            </td>
                        </tr>
                        <tr>
                            <td>Persetujuan Lainnya</td>
                            <td>:</td>
                            <td>
                                <?=$permohonan_istirahat->getField("STATUS_APPROVAL_LAIN")?>
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
                                <?=$permohonan_istirahat->getField("STATUS_APPROVAL_LAIN")?>
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
                    <input type="hidden" name="reqId" value="<?=$reqId?>" />
                    <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
                    <?
                    if ($reqMode == "view")
                    { }
                    else 
                    {
                    ?>
                    <input type="submit" id="reqSubmit" name="reqSubmit" class="btn btn-primary"  value="Submit" />
                    <?
                    }
                    ?>
            </form>
        </div>
        </div>
    </div>
</body>
</html>