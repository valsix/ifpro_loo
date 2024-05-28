<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

/* CHECK USER LOGIN 
$CI =& get_instance();
$CI->checkUserLogin();*/

$this->load->model('PermohonanOncall');

$permohonan_oncall = new PermohonanOncall();

$reqId = $this->input->get("reqId");
$reqMode = $this->input->get("reqMode");

if($reqId == "")
{ }
else
{
	$permohonan_oncall->selectByParamsApproval($this->ID, array('PERMOHONAN_ONCALL_ID'=>$reqId), -1, -1);
	$permohonan_oncall->firstRow();
	
	$reqApprovalKe = $permohonan_oncall->getField("APPROVAL_KE");	
	$reqNamaPegawai= $permohonan_oncall->getField('NAMA_PEGAWAI');
	$reqJabatan= $permohonan_oncall->getField('JABATAN');
	$reqNamaCabang= $permohonan_oncall->getField('NAMA_CABANG');
	$reqDepartemen= $permohonan_oncall->getField('DEPARTEMEN');
	$reqSubDepartemen= $permohonan_oncall->getField('SUB_DEPARTEMEN');
	
	$reqNomor= $permohonan_oncall->getField('NOMOR');
	$reqPenugas= $permohonan_oncall->getField('NAMA_APPROVAL1');
	$reqUraianPekerjaan= $permohonan_oncall->getField('URAIAN_PEKERJAAN');
	$reqHasilPekerjaan= $permohonan_oncall->getField('HASIL_PEKERJAAN');
	$reqAlasan= $permohonan_oncall->getField('ALASAN');
	$reqTanggal= $permohonan_oncall->getField('TANGGAL');
	$reqKendaraan= $permohonan_oncall->getField('KENDARAAN');
	$reqTanggalAwal= $permohonan_oncall->getField('TANGGAL_AWAL');
	$reqTanggalAkhir= $permohonan_oncall->getField('TANGGAL_AKHIR');
	$reqJamMasuk= $permohonan_oncall->getField('JAM_MASUK');
	$reqJamPulang= $permohonan_oncall->getField('JAM_PULANG');
	$reqApproval = $permohonan_oncall->getField("APPROVAL");
	$reqPemeriksa= $permohonan_oncall->getField('PEMERIKSA');
	$reqMengetahui= $permohonan_oncall->getField('NAMA_APPROVAL2');
	$reqApprovalKeterangan = $permohonan_oncall->getField("APPROVAL_KETERANGAN");
	$reqAlasanTolak= $permohonan_oncall->getField('ALASAN_TOLAK');
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
		url:'<?=base_url()?>permohonan_oncall_json/approval',
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
	<div id="judul-popup">Permohonan Oncall</div>
	<div id="konten">
    	<div id="popup-tabel2">
            <form id="ff" method="post" novalidate enctype="multipart/form-data">
            <table class="table">
                    <thead>
                    	<tr>
                        	<th colspan="3">PEMOHON</th>
                        </tr>
                        <tr>           
                             <td>Nama</td>
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
                             <td>Cabang</td>
                             <td>:</td>
                             <td><?=$reqNamaCabang?>
                            </td>			
                        </tr>
                        <tr>           
                             <td>Departemen</td>
                             <td>:</td>
                             <td><?=$reqDepartemen?> 
                            </td>			
                        </tr>
                        <tr>           
                             <td>Sub Departemen</td>
                             <td>:</td>
                             <td><?=$reqSubDepartemen?>
                            </td>			
                        </tr>
                        <tr>           
                             <td>Nomor</td>
                             <td>:</td>
                             <td><?=$reqNomor?> 
                            </td>			
                        </tr>
                        <tr>           
                             <td>Pegawai Penugas</td>
                             <td>:</td>
                             <td><?=$reqPenugas?> 
                            </td>			
                        </tr>
                        <tr>           
                             <td>Uraian Pekerjaan</td>
                             <td>:</td>
                             <td><?=$reqUraianPekerjaan?> 
                            </td>			
                        </tr>
                        <tr>           
                             <td>Hasil Pekerjaan</td>
                             <td>:</td>
                             <td><?=$reqHasilPekerjaan?> 
                            </td>			
                        </tr>
                        <tr>           
                             <td>Alasan</td>
                             <td>:</td>
                             <td><?=$reqAlasan?> 
                            </td>			
                        </tr>
                        <tr>           
                             <td>Tanggal</td>
                             <td>:</td>
                             <td><?=$reqTanggal?>
                            </td>			
                        </tr>
                        <tr>           
                             <td>Kendaraan</td>
                             <td>:</td>
                             <td><?=$reqKendaraan?>
                            </td>			
                        </tr>
                        <tr>           
                             <td>Tanggal Awal</td>
                             <td>:</td>
                             <td><?=$reqTanggalAwal?>
                            </td>			
                        </tr>
                        <tr>           
                             <td>Tanggal Akhir</td>
                             <td>:</td>
                             <td><?=$reqTanggalAkhir?>
                            </td>			
                        </tr>
                        <tr>           
                             <td>Jam Masuk</td>
                             <td>:</td>
                             <td><?=$reqJamMasuk?>
                            </td>			
                        </tr>
                        <tr>           
                             <td>Jam Pulang</td>
                             <td>:</td>
                             <td><?=$reqJamPulang?>
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