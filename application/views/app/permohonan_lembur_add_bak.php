<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

/* CHECK USER LOGIN 
$CI =& get_instance();
$CI->checkUserLogin();*/

$this->load->model('PermohonanLembur');

$permohonan_lembur= new PermohonanLembur();

$reqId = $this->input->get("reqId");
$reqMode = $this->input->get("reqMode");

if($reqId == "")
{ }
else
{
	$permohonan_lembur->selectByParams(array("PERMOHONAN_LEMBUR_ID" => $reqId));
	$permohonan_lembur->firstRow();
	$reqPegawaiId = $permohonan_lembur->getField("PEGAWAI_ID");
	$reqNomor = $permohonan_lembur->getField("NOMOR");
	$reqTanggal = $permohonan_lembur->getField("TANGGAL");
	$reqJabatan = $permohonan_lembur->getField("JABATAN");
	$reqCabang = $permohonan_lembur->getField("CABANG");
	$reqDepartemen = $permohonan_lembur->getField("DEPARTEMEN");
	$reqSubDepartemen = $permohonan_lembur->getField("SUB_DEPARTEMEN");
	$reqUraianPekerjaan = $permohonan_lembur->getField("URAIAN_PEKERJAAN");
	$reqSasaran = $permohonan_lembur->getField("SASARAN");
	$reqAlasan = $permohonan_lembur->getField("ALASAN");
	$reqTanggalAwal = $permohonan_lembur->getField("TANGGAL_AWAL");
	$reqJamMasuk = $permohonan_lembur->getField("JAM_MASUK");
	$reqTanggalAkhir = $permohonan_lembur->getField("TANGGAL_AKHIR");
	$reqJamPulang = $permohonan_lembur->getField("JAM_PULANG");
	$reqHasilPekerjaan = $permohonan_lembur->getField("HASIL_PEKERJAAN");
	$reqPegawaiIdPenugas = $permohonan_lembur->getField("PEGAWAI_ID_PENUGAS");
	$reqPegawaiIdPemeriksa = $permohonan_lembur->getField("PEGAWAI_ID_PEMERIKSA");
	$reqPegawaiIdMengetahui = $permohonan_lembur->getField("PEGAWAI_ID_MENGETAHUI");
	$reqPegawaiIdApproval = $permohonan_lembur->getField("PEGAWAI_ID_APPROVAL");
	$reqApproval = $permohonan_lembur->getField("APPROVAL");
	$reqNamaPegawai = $permohonan_lembur->getField("NAMA_PEGAWAI");
	$reqNamaCabang = $permohonan_lembur->getField("NAMA_CABANG");
	$reqApprovalKeterangan = $permohonan_lembur->getField("APPROVAL_KETERANGAN");
	$reqAlasanTolak = $permohonan_lembur->getField("ALASAN_TOLAK");
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
		url:'<?=base_url()?>permohonan_lembur_json/approval',
		onSubmit:function(){
			return $(this).form('validate');
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
	<div id="judul-popup">Permohonan Lembur</div>
	<div id="konten">
    	<div id="popup-tabel2">
            <form id="ff" method="post" novalidate enctype="multipart/form-data">
                    <table class="table">
                    <thead>
                    	<tr>
                        	<th colspan="3">Permohonan</th>
                        </tr>
                    	<tr>
                            <td>Nama Pegawai</td>
                            <td>:</td>
                            <td>
                                <?=$reqNamaPegawai?>
                          	</td>
                        </tr>        
                        <tr>
                            <td>Nama Cabang</td>
                            <td>:</td>
                            <td>
                                <?=$reqNamaCabang?>
                          	</td>
                        </tr>        
                        <tr>
                            <td>Nomor</td>
                            <td>:</td>
                            <td>
                                <?=$reqNomor?>
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
                            <td>Uraian Pekerjaan</td>
                            <td>:</td>
                            <td>
                                <?=$reqUraianPekerjaan?>
                            </td>
                        </tr>  
                        <tr>
                            <td>Sasaran</td>
                            <td>:</td>
                            <td>
                                <?=$reqSasaran?>
                            </td>
                        </tr> 
                        <tr>
                            <td>Alasan</td>
                            <td>:</td>
                            <td>
                                <?=$reqAlasan?>
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
                            <td>Jam Masuk</td>
                            <td>:</td>
                            <td>
                                <?=$reqJamMasuk?>
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
                            <td>Jam Pulang</td>
                            <td>:</td>
                            <td>
                                <?=$reqJamPulang?>
                            </td>
                        </tr>   
                        <tr>
                            <td>Hasil Pekerjaan</td>
                            <td>:</td>
                            <td>
                                <?=$reqHasilPekerjaan?>
                            </td>
                        </tr>
                        <?
						if ($reqMode == "view" && $reqApproval != "")
						{
                        ?>
                        <tr>
                        	<th colspan="3">Approval</th>
                        </tr>
                        <tr>
                            <td>Persetujuan</td>
                            <td>:</td>
                            <td>
                                <?=$reqApprovalKeterangan?>
                            </td>
                        </tr>
                        <?
                            if ($reqApproval == "Y")
							{ }
							else
							{
							?>
                                <tr>
                                    <td>Alasan Penolakan</td>
                                    <td>:</td>
                                    <td>
                                        <?=$reqAlasanTolak?>
                                    </td>
                                </tr>
                        <?
							}
						} 
						else if ($reqMode == "update")
						{
						?>
                        <tr>
                        	<th colspan="3">Approval</th>
                        </tr>
                        <tr>
                            <td>Persetujuan</td>
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