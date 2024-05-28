<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

/* CHECK USER LOGIN 
$CI =& get_instance();
$CI->checkUserLogin();*/

$this->load->model('PermohonanIjinKhusus');

$permohonan_ijin_khusus= new PermohonanIjinKhusus();

$reqId = $this->input->get("reqId");
$reqMode = $this->input->get("reqMode");

if($reqId == "")
{ }
else
{
	$permohonan_ijin_khusus->selectByParamsApproval($this->ID, array("PERMOHONAN_IJIN_KHUSUS_ID" => $reqId));
	$permohonan_ijin_khusus->firstRow();
	$reqApprovalKe = $permohonan_ijin_khusus->getField("APPROVAL_KE");
	$reqNomor = $permohonan_ijin_khusus->getField("NOMOR");
	$reqTanggal = $permohonan_ijin_khusus->getField("TANGGAL");
	$reqSubDepartemen = $permohonan_ijin_khusus->getField("SUB_DEPARTEMEN");
	$reqDepartemen = $permohonan_ijin_khusus->getField("DEPARTEMEN");
	$reqCabang = $permohonan_ijin_khusus->getField("CABANG");
	$reqJabatan = $permohonan_ijin_khusus->getField("JABATAN");
	$reqJenisIjin = $permohonan_ijin_khusus->getField("NAMA_IJIN_KOREKSI");
	$reqJumlahHari = $permohonan_ijin_khusus->getField("JUMLAH_HARI");
	$reqTanggalAwal = $permohonan_ijin_khusus->getField("TANGGAL_AWAL");
	$reqTanggalAkhir = $permohonan_ijin_khusus->getField("TANGGAL_AKHIR");
	$reqTanggalSebelum = $permohonan_ijin_khusus->getField("TAMBAH_HARI_SEBELUM");
	$reqTanggalSesudah = $permohonan_ijin_khusus->getField("TAMBAH_HARI_SESUDAH");
	$reqKeterangan = $permohonan_ijin_khusus->getField("KETERANGAN");
	$reqPegawaiIdManager = $permohonan_ijin_khusus->getField("PEGAWAI_ID_MANAGER");
	$reqPegawaiIdSdm = $permohonan_ijin_khusus->getField("PEGAWAI_ID_SDM");
	$reqPegawaiIdApproval = $permohonan_ijin_khusus->getField("PEGAWAI_ID_APPROVAL");
	$reqApproval = $permohonan_ijin_khusus->getField("APPROVAL");
	$reqIjinKoreksiId = $permohonan_ijin_khusus->getField("IJIN_KOREKSI_ID");
	$reqPegawaiId = $permohonan_ijin_khusus->getField("PEGAWAI_ID");
	$reqLampiran = $permohonan_ijin_khusus->getField("LAMPIRAN");
	$reqNamaPegawai = $permohonan_ijin_khusus->getField("NAMA_PEGAWAI");
	$reqNamaCabang = $permohonan_ijin_khusus->getField("NAMA_CABANG");
	$reqApprovalKeterangan = $permohonan_ijin_khusus->getField("APPROVAL_KETERANGAN");
	$reqAlasanTolak = $permohonan_ijin_khusus->getField("ALASAN_TOLAK");
	$reqApproval1= $permohonan_ijin_khusus->getField("APPROVAL1");
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
		url:'<?=base_url()?>permohonan_ijin_khusus_json/approval',
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
	<div id="judul-popup">Permohonan Ijin Khusus</div>
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
                            <td>Tanggal Permohonan</td>
                            <td>:</td>
                            <td>
                              <?=$reqTanggal?>
                            </td>
                        </tr> 
                        <tr>
                            <td>Jenis Ijin</td>
                            <td>:</td>
                            <td>
                                <?=$reqJenisIjin?>
                            </td>
                        </tr>       
                        <tr>
                            <td>Tanggal Ijin</td>
                            <td>:</td>
                            <td>
                                <?=$reqTanggalAwal?> s/d <?=$reqTanggalAkhir?>
                            </td>
                        </tr>  
						<?
                        if($reqTanggalSebelum == "")
						{}
						else
						{
						?>
                        <tr>
                            <td>Penambahan Berangkat</td>
                            <td>:</td>
                            <td>
                                Ya
                            </td>
                        </tr>  
                        <?
						}
						?>
                        <?
                        if($reqTanggalSesudah == "")
						{}
						else
						{
						?>
                        <tr>
                            <td>Penambahan Pulang</td>
                            <td>:</td>
                            <td>
                                Ya
                            </td>
                        </tr>  
                        <?
						}
						?>                                                
                        <tr>
                            <td>Jumlah Hari</td>
                            <td>:</td>
                            <td>
                               <?=$reqJumlahHari?> Hari
                            </td>
                        </tr>
                        <tr>
                            <td>Lampiran</td>
                            <td>:</td>
                            <td>
                            	<?
								$arrDokumen = explode(",", $reqLampiran);
								if($reqLampiran == "")
								{}
								else
								{
									for($i=0;$i<count($arrDokumen);$i++)
									{
									?>
										<img src="<?=base_url()?>images/icon-download.png" /> 
											<?php
											$dt_lampiran = explode('_',$arrDokumen[$i]);
											if($dt_lampiran[0]=='lampiran'){
											?>
												<a href="https://api.pjbservices.com/mobile/uploads/<?=$arrDokumen[$i]?>" target="_blank">File <?=($i+1)?></a>
											<?php
											}
											else{
											?>
												<a href="<?=base_url()?>uploads/<?=$arrDokumen[$i]?>" target="_blank">File <?=($i+1)?></a>
											<?php
											}
											?>
											
										
										<input type="hidden" name="reqLampiranTemp[]" value="<?=$arrDokumen[$i]?>" /> <br />
									<?
									}
								}
								?>
                            </td>
                        </tr>
						<?
						if ($reqMode == "view" && $reqPegawaiId == $this->ID)
						{
                        ?>
                        <tr>
                        	<th colspan="3">Approval <?=$permohonan_ijin_khusus->getField("NAMA_APPROVAL1")?></th>
                        </tr>
                        <tr>
                            <td>Persetujuan</td>
                            <td>:</td>
                            <td>
                                <?=$permohonan_ijin_khusus->getField("APPROVAL1")?>
                            </td>
                        </tr>
                        <?
                            if ($permohonan_ijin_khusus->getField("APPROVAL1") == "Ditolak")
							{
							?>
                                <tr>
                                    <td>Alasan Penolakan</td>
                                    <td>:</td>
                                    <td>
                                        <?=$permohonan_ijin_khusus->getField("ALASAN_TOLAK1")?>
                                    </td>
                                </tr>
                        <?
							}
						?>
                        <tr>
                        	<th colspan="3">Approval <?=$permohonan_ijin_khusus->getField("NAMA_APPROVAL2")?></th>
                        </tr>
                        <tr>
                            <td>Persetujuan</td>
                            <td>:</td>
                            <td>
                                <?=$permohonan_ijin_khusus->getField("APPROVAL2")?>
                            </td>
                        </tr>
                        <?
                            if ($permohonan_ijin_khusus->getField("APPROVAL2") == "Ditolak")
							{
							?>
                                <tr>
                                    <td>Alasan Penolakan</td>
                                    <td>:</td>
                                    <td>
                                        <?=$permohonan_ijin_khusus->getField("ALASAN_TOLAK2")?>
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
                                <?=$permohonan_ijin_khusus->getField("STATUS_APPROVAL")?>&nbsp;<?=$permohonan_ijin_khusus->getField("ALASAN_TOLAK")?>
                            </td>
                        </tr>
                        <tr>
                            <td>Persetujuan Lainnya</td>
                            <td>:</td>
                            <td>
                                <?=$permohonan_ijin_khusus->getField("STATUS_APPROVAL_LAIN")?>
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
                                <?=$permohonan_ijin_khusus->getField("STATUS_APPROVAL_LAIN")?>
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