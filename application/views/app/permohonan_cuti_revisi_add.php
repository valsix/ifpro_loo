<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

/* CHECK USER LOGIN 
$CI =& get_instance();
$CI->checkUserLogin();*/

$this->load->model('PermohonanCutiRevisi');
$this->load->model('PermohonanCutiRevisiDetil');
$this->load->library("SettingApp"); $settingApp = new SettingApp();

$permohonan_cuti_revisi = new PermohonanCutiRevisi();

$reqId = $this->input->get("reqId");
$reqMode = $this->input->get("reqMode");

if($reqId == "")
{ }
else
{
	$permohonan_cuti_revisi->selectByParamsApproval($this->ID, array("PERMOHONAN_CUTI_REVISI_ID" => $reqId));
	$permohonan_cuti_revisi->firstRow();
	$reqApprovalKe = $permohonan_cuti_revisi->getField("APPROVAL_KE");	
	$reqPegawaiId = $permohonan_cuti_revisi->getField("PEGAWAI_ID");
	$reqNomor = $permohonan_cuti_revisi->getField("NOMOR");
	$reqCabang = $permohonan_cuti_revisi->getField("CABANG");
	$reqTanggalAwalPermohonan = $permohonan_cuti_revisi->getField("TANGGAL_AWAL_PERMOHONAN");
	$reqTanggalAkhirPermohonan = $permohonan_cuti_revisi->getField("TANGGAL_AKHIR_PERMOHONAN");
	$reqJumlahHariPermohonan = $permohonan_cuti_revisi->getField("JUMLAH_HARI_PERMOHONAN");
	$reqTanggal = $permohonan_cuti_revisi->getField("TANGGAL");
	$reqTanggalAwalRevisi = $permohonan_cuti_revisi->getField("TANGGAL_AWAL_REVISI");
	$reqTanggalAkhirRevisi = $permohonan_cuti_revisi->getField("TANGGAL_AKHIR_REVISI");
	$reqJumlahHariRevisi = $permohonan_cuti_revisi->getField("JUMLAH_HARI_REVISI");
	$reqNamaApproval1 = $permohonan_cuti_revisi->getField("NAMA_APPROVAL1");
	$reqNamaApproval2 = $permohonan_cuti_revisi->getField("NAMA_APPROVAL2");
	$reqNamaPegawai = $permohonan_cuti_revisi->getField("NAMA_PEGAWAI");
	$reqNamaCabang = $permohonan_cuti_revisi->getField("NAMA_CABANG");
	$reqApprovalKeterangan1 = $permohonan_cuti_revisi->getField("APPROVAL_KETERANGAN1");
	$reqApprovalKeterangan2 = $permohonan_cuti_revisi->getField("APPROVAL_KETERANGAN2");
	$reqAlasanTolak1 = $permohonan_cuti_revisi->getField("ALASAN_TOLAK1");
	$reqAlasanTolak2 = $permohonan_cuti_revisi->getField("ALASAN_TOLAK2");
	$reqApproval1= $permohonan_cuti_revisi->getField("APPROVAL1");
	$reqApproval2= $permohonan_cuti_revisi->getField("APPROVAL2");
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
<base href="<?=base_url();?>" />
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
			url:'permohonan_cuti_revisi_json/approval',
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
	<div id="judul-popup">Permohonan Revisi Cuti</div>
	<div id="konten">
    	<div id="popup-tabel2">
            <form id="ff" method="post" novalidate enctype="multipart/form-data">
                    <table class="table">
                    <thead>
                        <tr>
                        	<th colspan="3">Permohonan</th>
                        </tr>
                        <tr>
                            <td>Nomor Cuti Tahunan</td>
                            <td>:</td>
                            <td>
                                <?=$reqNomor?>
                            </td>
                        </tr>
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
                      	<tr>
                            <td>Tanggal Revisi</td>
                            <td>:</td>
                            <td>
                              <?=$reqTanggal?>
                            </td>
                        </tr>   
                        <tr>
                            <td>Tanggal Permohonan Cuti</td>
                            <td>:</td>
                            <td>
                                <?=$reqTanggalAwalPermohonan?> s/d <?=$reqTanggalAkhirPermohonan?>
                            </td>
                        </tr>  
                        <tr>
                            <td>Lama Cuti</td>
                            <td>:</td>
                            <td>
                               <?=$reqJumlahHariPermohonan?> Hari
                            </td>
                        </tr>
                        <tr>
                        	<th colspan="3">Revisi Cuti</th>
                        </tr>
                        <tr>
                            <td>Tanggal Perubahan</td>
                            <td>:</td>
                            <td>
                               <?
							   $data = "";
                               $permohonan_cuti_revisi_detil = new PermohonanCutiRevisiDetil();
							   $permohonan_cuti_revisi_detil->selectByParams(array("PERMOHONAN_CUTI_REVISI_ID" => $reqId));
							   while($permohonan_cuti_revisi_detil->nextRow())
							   {
							     if($data == "")
								 	$data = $permohonan_cuti_revisi_detil->getField("TANGGAL_REVISI");
								 else
								 	$data .= ", ".$permohonan_cuti_revisi_detil->getField("TANGGAL_REVISI");
							   }
							   ?>
                               <?=$data?>
                            </td>
                        </tr>  
                        <tr>
                            <td>Lama Cuti</td>
                            <td>:</td>
                            <td>
                               <?=$reqJumlahHariRevisi?> Hari
                            </td>
                        </tr>
                        <?
						if ($reqMode == "view" && $reqPegawaiId == $this->ID)
						{
                        ?>
                        <tr>
                        	<th colspan="3">Approval <?=$permohonan_cuti_revisi->getField("NAMA_APPROVAL1")?></th>
                        </tr>
                        <tr>
                            <td>Persetujuan</td>
                            <td>:</td>
                            <td>
                                <?=$permohonan_cuti_revisi->getField("APPROVAL1")?>
                            </td>
                        </tr>
                        <?
                            if ($permohonan_cuti_revisi->getField("APPROVAL1") == "Ditolak")
							{
							?>
                                <tr>
                                    <td>Alasan Penolakan</td>
                                    <td>:</td>
                                    <td>
                                        <?=$permohonan_cuti_revisi->getField("ALASAN_TOLAK1")?>
                                    </td>
                                </tr>
                        <?
							}
						?>
                        <tr>
                        	<th colspan="3">Approval</th>
                        </tr>
                        <tr>
                            <td>Persetujuan</td>
                            <td>:</td>
                            <td>
                                <?=$permohonan_cuti_revisi->getField("APPROVAL2")?>
                            </td>
                        </tr>
                        <?
                            if ($permohonan_cuti_revisi->getField("APPROVAL2") == "Ditolak")
							{
							?>
                                <tr>
                                    <td>Alasan Penolakan</td>
                                    <td>:</td>
                                    <td>
                                        <?=$permohonan_cuti_revisi->getField("ALASAN_TOLAK2")?>
                                    </td>
                                </tr>
                        <?
							}
						} 
						if ($reqMode == "view" && ($reqApproval1 != "" || $reqApproval2 != ""))
						{
                        ?>
                        <tr>
                        	<th colspan="3">Approval</th>
                        </tr>
                        <tr>
                            <td>Persetujuan Anda</td>
                            <td>:</td>
                            <td>
                                <?=$permohonan_cuti_revisi->getField("STATUS_APPROVAL")?>&nbsp;<?=$permohonan_cuti_revisi->getField("ALASAN_TOLAK")?>
                            </td>
                        </tr>
                        <tr>
                            <td>Persetujuan Lainnya</td>
                            <td>:</td>
                            <td>
                                <?=$permohonan_cuti_revisi->getField("STATUS_APPROVAL_LAIN")?>
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
                                <?=$permohonan_cuti_revisi->getField("STATUS_APPROVAL_LAIN")?>
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