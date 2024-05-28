<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

/* CHECK USER LOGIN 
$CI =& get_instance();
$CI->checkUserLogin();*/

$this->load->model('PermohonanCutiBersalin');

$permohonan_cuti_bersalin = new PermohonanCutiBersalin();

$reqId = $this->input->get("reqId");
$reqMode = $this->input->get("reqMode");

if($reqId == "")
{ }
else
{
	$permohonan_cuti_bersalin->selectByParamsApproval($this->ID, array("PERMOHONAN_CUTI_BERSALIN_ID" => $reqId));
	$permohonan_cuti_bersalin->firstRow();
	$reqApprovalKe = $permohonan_cuti_bersalin->getField("APPROVAL_KE");	
	$reqNomor = $permohonan_cuti_bersalin->getField("NOMOR");
	$reqTanggal = $permohonan_cuti_bersalin->getField("TANGGAL");
	$reqSubDepartemen = $permohonan_cuti_bersalin->getField("SUB_DEPARTEMEN");
	$reqDepartemen = $permohonan_cuti_bersalin->getField("DEPARTEMEN");
	$reqCabang = $permohonan_cuti_bersalin->getField("CABANG");
	$reqJabatan = $permohonan_cuti_bersalin->getField("JABATAN");
	$reqJumlahHari = $permohonan_cuti_bersalin->getField("JUMLAH_HARI");
	$reqTanggalAwal = $permohonan_cuti_bersalin->getField("TANGGAL_AWAL");
	$reqTanggalAkhir = $permohonan_cuti_bersalin->getField("TANGGAL_AKHIR");
	$reqAlamat = $permohonan_cuti_bersalin->getField("ALAMAT");
	$reqTelepon = $permohonan_cuti_bersalin->getField("TELEPON");
	$reqPegawaiIdApproval = $permohonan_cuti_bersalin->getField("PEGAWAI_ID_APPROVAL");
	$reqApproval = $permohonan_cuti_bersalin->getField("APPROVAL");
	$reqIjinKoreksiId = $permohonan_cuti_bersalin->getField("IJIN_KOREKSI_ID");
	$reqPegawaiId = $permohonan_cuti_bersalin->getField("PEGAWAI_ID");
	$reqNamaPegawai = $permohonan_cuti_bersalin->getField("NAMA_PEGAWAI");
	$reqNamaCabang = $permohonan_cuti_bersalin->getField("NAMA_CABANG");
	$reqJenisCuti = $permohonan_cuti_bersalin->getField("JENIS_CUTI");
	$reqApprovalKeterangan = $permohonan_cuti_bersalin->getField("APPROVAL_KETERANGAN");
	$reqAlasanTolak = $permohonan_cuti_bersalin->getField("ALASAN_TOLAK");
	$reqApproval1= $permohonan_cuti_bersalin->getField("APPROVAL1");
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
		url:'<?=base_url()?>permohonan_cuti_bersalin_json/approval',
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
	<div id="judul-popup">Permohonan Cuti Besar</div>
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
                    	<tr>
                            <td>Nama Pegawai</td>
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
                        </tr>   
                     	<tr>
                            <td>Tanggal Permohonan</td>
                            <td>:</td>
                            <td>
                              <?=$reqTanggal?>
                            </td>
                        </tr>
                        <tr>
                            <td>Jenis Cuti</td>
                            <td>:</td>
                            <td>
                                <?=$reqJenisCuti?>
                            </td>
                        </tr>            
                        <tr>
                            <td>Tanggal Cuti</td>
                            <td>:</td>
                            <td>
                                <?=$reqTanggalAwal?> s/d <?=$reqTanggalAkhir?>
                            </td>
                        </tr>  
                        <tr>
                            <td>Jumlah Hari</td>
                            <td>:</td>
                            <td>
                               <?=$reqJumlahHari?> Hari
                            </td>
                        </tr>
                        <tr>
                            <td>Alamat Cuti</td>
                            <td>:</td>
                            <td>
                            	<?=$reqAlamat?>
                            </td>
                        </tr>
                        <tr>
                            <td>Telepon</td>
                            <td>:</td>
                            <td>
                                <?=$reqTelepon?>
                            </td>
                        </tr>
                        <?
						if ($reqMode == "view" && $reqPegawaiId == $this->ID)
						{
                        ?>
                        <tr>
                        	<th colspan="3">Approval <?=$permohonan_cuti_bersalin->getField("NAMA_APPROVAL1")?></th>
                        </tr>
                        <tr>
                            <td>Persetujuan</td>
                            <td>:</td>
                            <td>
                                <?=$permohonan_cuti_bersalin->getField("APPROVAL1")?>
                            </td>
                        </tr>
                        <?
                            if ($permohonan_cuti_bersalin->getField("APPROVAL1") == "Ditolak")
							{
							?>
                                <tr>
                                    <td>Alasan Penolakan</td>
                                    <td>:</td>
                                    <td>
                                        <?=$permohonan_cuti_bersalin->getField("ALASAN_TOLAK1")?>
                                    </td>
                                </tr>
                        <?
							}
						?>
                        <tr>
                        	<th colspan="3">Approval <?=$permohonan_cuti_bersalin->getField("NAMA_APPROVAL2")?></th>
                        </tr>
                        <tr>
                            <td>Persetujuan</td>
                            <td>:</td>
                            <td>
                                <?=$permohonan_cuti_bersalin->getField("APPROVAL2")?>
                            </td>
                        </tr>
                        <?
                            if ($permohonan_cuti_bersalin->getField("APPROVAL2") == "Ditolak")
							{
							?>
                                <tr>
                                    <td>Alasan Penolakan</td>
                                    <td>:</td>
                                    <td>
                                        <?=$permohonan_cuti_bersalin->getField("ALASAN_TOLAK2")?>
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
                                <?=$permohonan_cuti_bersalin->getField("STATUS_APPROVAL")?>&nbsp;<?=$permohonan_cuti_bersalin->getField("ALASAN_TOLAK")?>
                            </td>
                        </tr>
                        <tr>
                            <td>Persetujuan Lainnya</td>
                            <td>:</td>
                            <td>
                                <?=$permohonan_cuti_bersalin->getField("STATUS_APPROVAL_LAIN")?>
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
                                <?=$permohonan_cuti_bersalin->getField("STATUS_APPROVAL_LAIN")?>
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