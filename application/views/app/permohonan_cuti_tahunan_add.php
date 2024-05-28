<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

/* CHECK USER LOGIN 
$CI =& get_instance();
$CI->checkUserLogin();*/

$this->load->model('PermohonanCutiTahunan');
$permohonan_cuti_tahunan = new PermohonanCutiTahunan();
$permohonan_jatah_cuti_tahunan = new PermohonanCutiTahunan();
$permohonan_jatah_cuti_tangguhan = new PermohonanCutiTahunan();
/*$this->load->library("SettingApp"); $settingApp = new SettingApp();

$jatah_cuti = $settingApp->getSetting("CUTI_TAHUNAN_MAKSIMAL");
$total_cuti = $permohonan_cuti_tahunan->getSumByParams(array("TAHUN" => $settingApp->getSetting("CUTI_TAHUNAN_TAHUN_AKTIF"), "A.PEGAWAI_ID" => $this->ID));
$sisa_cuti = ($jatah_cuti - $total_cuti);*/

/* CEK APAKAH MASIH ADA JATAH DI TAHUN SEBELUMNYA DAN BERLAKU SAMPAI DENGAN MARET */
$sisa_cuti = 0;





$reqId = $this->input->get("reqId");
$reqMode = $this->input->get("reqMode");

if($reqId == "")
{ }
else
{
	$permohonan_cuti_tahunan->selectByParamsApproval($this->ID, array("PERMOHONAN_CUTI_TAHUNAN_ID" => $reqId));
	$permohonan_cuti_tahunan->firstRow();
	$reqApprovalKe = $permohonan_cuti_tahunan->getField("APPROVAL_KE");	
	$reqPegawaiId = $permohonan_cuti_tahunan->getField("PEGAWAI_ID");
	$reqTahun = $permohonan_cuti_tahunan->getField("TAHUN");
	$reqNomor = $permohonan_cuti_tahunan->getField("NOMOR");
	$reqTanggal = $permohonan_cuti_tahunan->getField("TANGGAL");
	$reqJabatan = $permohonan_cuti_tahunan->getField("JABATAN");
	$reqCabang = $permohonan_cuti_tahunan->getField("CABANG");
	$reqDepartemen = $permohonan_cuti_tahunan->getField("DEPARTEMEN");
	$reqSubDepartemen = $permohonan_cuti_tahunan->getField("SUB_DEPARTEMEN");
	$reqTanggalAwal = $permohonan_cuti_tahunan->getField("TANGGAL_AWAL");
	$reqTanggalAkhir = $permohonan_cuti_tahunan->getField("TANGGAL_AKHIR");
	$reqJumlahHari = $permohonan_cuti_tahunan->getField("LAMA_CUTI");
	$reqKeterangan = $permohonan_cuti_tahunan->getField("KETERANGAN");
	$reqAlamat = $permohonan_cuti_tahunan->getField("ALAMAT");
	$reqTelepon = $permohonan_cuti_tahunan->getField("TELEPON");
	$reqPegawaiIdApproval = $permohonan_cuti_tahunan->getField("PEGAWAI_ID_APPROVAL");
	$reqApproval = $permohonan_cuti_tahunan->getField("APPROVAL");
	$reqNamaPegawai = $permohonan_cuti_tahunan->getField("NAMA_PEGAWAI");
	$reqNamaCabang = $permohonan_cuti_tahunan->getField("NAMA_CABANG");
	$reqApprovalKeterangan = $permohonan_cuti_tahunan->getField("APPROVAL_KETERANGAN");
	$reqAlasanTolak = $permohonan_cuti_tahunan->getField("ALASAN_TOLAK");
    $reqApproval1= $permohonan_cuti_tahunan->getField("APPROVAL1");
	$reqStatusApprovalLain= $permohonan_cuti_tahunan->getField("STATUS_APPROVAL_LAIN");
	
    /*
	if($sisa_cuti == 0)
	{
		$permohonan_jatah_cuti_tahunan->selectByParamsJatahCutiTahunan(array("A.PEGAWAI_ID" => $reqPegawaiId));
		$permohonan_jatah_cuti_tahunan->firstRow();
	
		$jatah_cuti = $permohonan_jatah_cuti_tahunan->getField("TOTAL_CUTI");
		$total_cuti =  $permohonan_jatah_cuti_tahunan->getField("TOTAL_DIAMBIL");
		$sisa_cuti = $permohonan_jatah_cuti_tahunan->getField("TOTAL_SISA");
		$keterangan_cuti = "Tahunan";
		$reqTahun = $this->TAHUN_CUTI;
	}
	
	if(date("n") <= 3)
	{
		$permohonan_jatah_cuti_tangguhan->selectByParamsJatahCutiTangguhan(array("A.PEGAWAI_ID" => $reqPegawaiId));
		$permohonan_jatah_cuti_tangguhan->firstRow();
		$jatah_cuti = $permohonan_jatah_cuti_tangguhan->getField("TOTAL_PENANGGUHAN");
		$total_cuti = $permohonan_jatah_cuti_tangguhan->getField("TOTAL_DIAMBIL");
		$sisa_cuti = $permohonan_jatah_cuti_tangguhan->getField("TOTAL_SISA");
		$keterangan_cuti = "Tangguhan";
		$reqTahun = $this->TAHUN_CUTI - 1;
	}
    */


    $sisa_cuti = 0;
    // if(date("n") <= 3)
    if(getMonth($reqTanggalAwal) <= '03' )
    {
        // $permohonan_cuti_tahunan->selectByParamsJatahCutiTangguhan(array("A.PEGAWAI_ID" => $this->ID));
		$permohonan_cuti_tahunan_tangguhan = new PermohonanCutiTahunan();
        $permohonan_cuti_tahunan_tangguhan->selectByParamsJatahCutiTangguhan(array("A.PEGAWAI_ID" => $reqPegawaiId));
        $permohonan_cuti_tahunan_tangguhan->firstRow();
        $jatah_cuti = $permohonan_cuti_tahunan_tangguhan->getField("TOTAL_PENANGGUHAN");
        $total_cuti = $permohonan_cuti_tahunan_tangguhan->getField("TOTAL_DIAMBIL");
        $sisa_cuti = $permohonan_cuti_tahunan_tangguhan->getField("TOTAL_SISA");
        $keterangan_cuti = "Tangguhan";
        $reqTahun = $this->TAHUN_CUTI - 1;
    }

    if($sisa_cuti == 0)
    {
        // $permohonan_cuti_tahunan->selectByParamsJatahCutiTahunan(array("A.PEGAWAI_ID" => $this->ID));
		$permohonan_cuti_tahunan_jatah = new PermohonanCutiTahunan();		
        $permohonan_cuti_tahunan_jatah->selectByParamsJatahCutiTahunan(array("A.PEGAWAI_ID" => $reqPegawaiId));
        $permohonan_cuti_tahunan_jatah->firstRow();
        
        $jatah_cuti = $permohonan_cuti_tahunan_jatah->getField("TOTAL_CUTI");
        $total_cuti = $permohonan_cuti_tahunan_jatah->getField("TOTAL_DIAMBIL");
        $sisa_cuti = $permohonan_cuti_tahunan_jatah->getField("TOTAL_SISA");
        $keterangan_cuti = "Tahunan";
        $reqTahun = $this->TAHUN_CUTI;
    }
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
			url:'permohonan_cuti_tahunan_json/approval',
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
	<div id="judul-popup">Permohonan Cuti Tahunan</div>
	<div id="konten">
    	<div id="popup-tabel2">
            <form id="ff" method="post" novalidate enctype="multipart/form-data">
                    <table class="table">
                    <thead>
                    	<tr>
                        	<th colspan="3">Informasi Cuti <?=$keterangan_cuti?> <?=$reqTahun?></th>
                        </tr>
                        <tr>
                        	<td>Jatah Cuti <?=$keterangan_cuti?></td>
                            <td>:</td>
                            <td><?=$jatah_cuti;?>  hari</td>
                        </tr>
                        <tr>
                        	<td>Cuti Diambil</td>
                            <td>:</td>
                            <td><?=$total_cuti?>  hari</td>
                        </tr>
                        <tr>
                        	<td>Sisa Cuti</td>
                            <td>:</td>
                            <td><?=$sisa_cuti?> hari</td>
                        </tr>
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
                            <td>Tanggal Cuti</td>
                            <td>:</td>
                            <td>
                                <?=$reqTanggalAwal?> s/d <?=$reqTanggalAkhir?>
                            </td>
                        </tr>  
                        <tr>
                            <td>Lama Cuti</td>
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
                        	<th colspan="3">Approval <?=$permohonan_cuti_tahunan->getField("NAMA_APPROVAL1")?></th>
                        </tr>
                        <tr>
                            <td>Persetujuan</td>
                            <td>:</td>
                            <td>
                                <?=$permohonan_cuti_tahunan->getField("APPROVAL1")?>
                            </td>
                        </tr>
                        <?
                            if ($permohonan_cuti_tahunan->getField("APPROVAL1") == "Ditolak")
							{
							?>
                                <tr>
                                    <td>Alasan Penolakan</td>
                                    <td>:</td>
                                    <td>
                                        <?=$permohonan_cuti_tahunan->getField("ALASAN_TOLAK1")?>
                                    </td>
                                </tr>
                        <?
							}
						?>
                        <tr>
                        	<th colspan="3">Approval <?=$permohonan_cuti_tahunan->getField("NAMA_APPROVAL2")?></th>
                        </tr>
                        <tr>
                            <td>Persetujuan</td>
                            <td>:</td>
                            <td>
                                <?=$permohonan_cuti_tahunan->getField("APPROVAL2")?>
                            </td>
                        </tr>
                        <?
                            if ($permohonan_cuti_tahunan->getField("APPROVAL2") == "Ditolak")
							{
							?>
                                <tr>
                                    <td>Alasan Penolakan</td>
                                    <td>:</td>
                                    <td>
                                        <?=$permohonan_cuti_tahunan->getField("ALASAN_TOLAK2")?>
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
                                <?=$permohonan_cuti_tahunan->getField("STATUS_APPROVAL")?>&nbsp;<?=$permohonan_cuti_tahunan->getField("ALASAN_TOLAK")?>
                            </td>
                        </tr>
                        <tr>
                            <td>Persetujuan Lainnya</td>
                            <td>:</td>
                            <td>
                                <!-- <?=$permohonan_cuti_tahunan->getField("STATUS_APPROVAL_LAIN")?> -->
                                <?=$reqStatusApprovalLain?>
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
                                <!-- <?=$permohonan_cuti_tahunan->getField("STATUS_APPROVAL_LAIN")?> -->
                                <?=$reqStatusApprovalLain?>
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