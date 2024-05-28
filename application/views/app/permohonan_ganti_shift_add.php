<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

/* CHECK USER LOGIN 
$CI =& get_instance();
$CI->checkUserLogin();*/

$this->load->model('PermohonanGantiJadwal');
$this->load->model('PermohonanGantiJadwalDetil');

$permohonan_ganti_jadwal = new PermohonanGantiJadwal();
$permohonan_ganti_jadwal_detil = new PermohonanGantiJadwalDetil();

$reqId = $this->input->get("reqId");
$reqMode = $this->input->get("reqMode");

if($reqId == "")
{ }
else
{
	$permohonan_ganti_jadwal->selectByParamsApproval($this->ID, array('PERMOHONAN_GANTI_JADWAL_ID'=>$reqId), -1, -1);
	$permohonan_ganti_jadwal->firstRow();

	$reqApprovalKe = $permohonan_ganti_jadwal->getField("APPROVAL_KE");	
	$reqPegawaiId= $permohonan_ganti_jadwal->getField('PEGAWAI_ID');
	$reqNamaPegawai= $permohonan_ganti_jadwal->getField('NAMA_PEGAWAI');
	$reqNamaCabang= $permohonan_ganti_jadwal->getField('NAMA_CABANG');
	$reqJabatan= $permohonan_ganti_jadwal->getField('JABATAN');
	$reqCabang= $permohonan_ganti_jadwal->getField('CABANG');
	$reqDepartemen= $permohonan_ganti_jadwal->getField('DEPARTEMEN');
	$reqSubDepartemen= $permohonan_ganti_jadwal->getField('SUB_DEPARTEMEN');
	$reqTeleponPemohon= $permohonan_ganti_jadwal->getField('TELEPON_PEMOHON');
	$reqNomor= $permohonan_ganti_jadwal->getField('NOMOR');
	$reqTanggal= $permohonan_ganti_jadwal->getField('TANGGAL');
	$reqTahun= $permohonan_ganti_jadwal->getField('TAHUN');
	$reqHariDiajukan= $permohonan_ganti_jadwal->getField('HARI_DIAJUKAN');
	$reqTanggalAwal= $permohonan_ganti_jadwal->getField('TANGGAL_AWAL');
	$reqTanggalAkhir= $permohonan_ganti_jadwal->getField('TANGGAL_AKHIR');
	$reqApproval = $permohonan_ganti_jadwal->getField("APPROVAL");
	$reqApproval1= $permohonan_ganti_jadwal->getField('APPROVAL1');
	$reqApprovalKeterangan= $permohonan_ganti_jadwal->getField('APPROVAL_KETERANGAN');
	$reqJenisGanti= $permohonan_ganti_jadwal->getField('JENIS_GANTI_KETERANGAN');
	
	$reqPengganti= $permohonan_ganti_jadwal->getField('NAMA_PENGGANTI');
	$reqJabatanPengganti= $permohonan_ganti_jadwal->getField('JABATAN_PENGGANTI');
	$reqCabangPengganti= $permohonan_ganti_jadwal->getField('CABANG_PENGGANTI');
	$reqDepartemenPengganti= $permohonan_ganti_jadwal->getField('DEPARTEMEN_PENGGANTI');
	$reqSubDepartemenPengganti= $permohonan_ganti_jadwal->getField('SUB_DEPARTEMEN_PENGGANTI');
	
	$reqApprovalKeterangan = $permohonan_ganti_jadwal->getField("APPROVAL_KETERANGAN");
	$reqAlasanTolak= $permohonan_ganti_jadwal->getField('ALASAN_TOLAK');
	
	$permohonan_ganti_jadwal_detil->selectByParams(array('PERMOHONAN_GANTI_JADWAL_ID'=>$reqId), -1, -1);
}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
<base href="<?=base_url()?>" />

<script type="text/javascript" src="js/jquery-1.9.1.js"></script>

<link rel="stylesheet" type="text/css" href="css/gaya.css">

<link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="js/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript" src="lib/easyui/globalfunction.js"></script>

<!-- BOOTSTRAP CORE -->
<link href="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

<script type="text/javascript">	
	$(function(){
	$('#ff').form({
		url:'permohonan_ganti_jadwal_json/approval',
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
			//alert(data);
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

<body class="bg-kanan-full" onload="AlasanTolak()">
	<div id="judul-popup">Permohonan Ganti Shift</div>
	<div id="konten">
    	<div id="popup-tabel2">
            <form id="ff" method="post" novalidate enctype="multipart/form-data">
            <table class="table">
                    <thead>
                    	<tr>
                        	<th colspan="3">PEMOHON</th>
                        </tr>
                        <tr>           
                             <td>Nomor</td>
                             <td>:</td>
                             <td><?=$reqNomor?>
                            </td>			
                        </tr>
                        <tr>           
                             <td>Tanggal</td>
                             <td>:</td>
                             <td><?=$reqTanggal?>
                            </td>			
                        </tr>
                        <tr>           
                             <td>Jenis Pergantian</td>
                             <td>:</td>
                             <td><?=$reqJenisGanti?>
                            </td>			
                        </tr>
                        <tr>           
                             <td>Nama Pemohon</td>
                             <td>:</td>
                             <td><?=$reqNamaPegawai?>
                            </td>			
                        </tr>
                        <tr>           
                             <td>Jabatan</td>
                             <td>:</td>
                             <td><?=$reqJabatan?>
                            </td>			
                        </tr>
                        <tr>           
                             <td>Nama Pengganti</td>
                             <td>:</td>
                             <td><?=$reqPengganti?>
                            </td>			
                        </tr>
                        <tr>           
                             <td>Jabatan Pengganti</td>
                             <td>:</td>
                             <td><?=$reqJabatanPengganti?>
                            </td>			
                        </tr>
                        <tr>           
                             <td>Telepon Pemohon</td>
                             <td>:</td>
                             <td><?=$reqTeleponPemohon?>
                            </td>			
                        </tr>
                        <tr>           
                             <td>Hari Diajukan</td>
                             <td>:</td>
                             <td><?=$reqHariDiajukan?>
                            </td>			
                        </tr>
                    	<tr>
                        	<th colspan="3">JADWAL DIAJUKAN</th>
                        </tr>                        
                        <?
                        while($permohonan_ganti_jadwal_detil->nextRow())
						{
						?>
                        <tr>           
                             <td><?=$permohonan_ganti_jadwal_detil->getField("TANGGAL")?></td>
                             <td>:</td>
                             <td>Jam Masuk : <?=$permohonan_ganti_jadwal_detil->getField("JAM_MASUK")?>, Jam Pulang : <?=$permohonan_ganti_jadwal_detil->getField("JAM_PULANG")?>
                            </td>			
                        </tr>
                        <?
						}
						?>
                        <?
						if ($reqMode == "view" && $reqPegawaiId == $this->ID)
						{
                        ?>
                        <tr>
                        	<th colspan="3">Approval <?=$permohonan_ganti_jadwal->getField("NAMA_APPROVAL1")?></th>
                        </tr>
                        <tr>
                            <td>Persetujuan</td>
                            <td>:</td>
                            <td>
                                <?=$permohonan_ganti_jadwal->getField("APPROVAL1")?>
                            </td>
                        </tr>
                        <?
                            if ($permohonan_ganti_jadwal->getField("APPROVAL1") == "Ditolak")
							{
							?>
                                <tr>
                                    <td>Alasan Penolakan</td>
                                    <td>:</td>
                                    <td>
                                        <?=$permohonan_ganti_jadwal->getField("ALASAN_TOLAK1")?>
                                    </td>
                                </tr>
                        <?
							}
						?>
                        <tr>
                        	<th colspan="3">Approval <?=$permohonan_ganti_jadwal->getField("NAMA_APPROVAL2")?></th>
                        </tr>
                        <tr>
                            <td>Persetujuan</td>
                            <td>:</td>
                            <td>
                                <?=$permohonan_ganti_jadwal->getField("APPROVAL2")?>
                            </td>
                        </tr>
                        <?
                            if ($permohonan_ganti_jadwal->getField("APPROVAL2") == "Ditolak")
							{
							?>
                                <tr>
                                    <td>Alasan Penolakan</td>
                                    <td>:</td>
                                    <td>
                                        <?=$permohonan_ganti_jadwal->getField("ALASAN_TOLAK2")?>
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
                                <?=$permohonan_ganti_jadwal->getField("STATUS_APPROVAL")?>&nbsp;<?=$permohonan_ganti_jadwal->getField("ALASAN_TOLAK")?>
                            </td>
                        </tr>
                        <tr>
                            <td>Persetujuan Lainnya</td>
                            <td>:</td>
                            <td>
                                <?=$permohonan_ganti_jadwal->getField("STATUS_APPROVAL_LAIN")?>
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
                                <?=$permohonan_ganti_jadwal->getField("STATUS_APPROVAL_LAIN")?>
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
                    </table>
                    </thead>
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