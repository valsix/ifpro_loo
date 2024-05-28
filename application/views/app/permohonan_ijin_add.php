<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

/* CHECK USER LOGIN 
$CI =& get_instance();
$CI->checkUserLogin();*/

$this->load->model('PermohonanLambatPc');

$permohonan_lambat_pc = new PermohonanLambatPc();

$reqId = $this->input->get("reqId");
$reqMode = $this->input->get("reqMode");

if($reqId == "")
{ }
else
{
	$permohonan_lambat_pc->selectByParamsApproval($this->ID, array('PERMOHONAN_LAMBAT_PC_ID'=>$reqId));
	$permohonan_lambat_pc->firstRow();

	$reqApprovalKe = $permohonan_lambat_pc->getField("APPROVAL_KE");	
	$reqNamaPegawai= $permohonan_lambat_pc->getField('NAMA_PEGAWAI');
	$reqJabatan= $permohonan_lambat_pc->getField('JABATAN');
	$reqNamaCabang= $permohonan_lambat_pc->getField('NAMA_CABANG');
	$reqDepartemen= $permohonan_lambat_pc->getField('DEPARTEMEN');
	$reqSubDepartemen= $permohonan_lambat_pc->getField('SUB_DEPARTEMEN');
	$reqNomor= $permohonan_lambat_pc->getField('NOMOR');
	$reqTanggal= $permohonan_lambat_pc->getField('TANGGAL');
	$reqTanggalIjin= $permohonan_lambat_pc->getField('TANGGAL_IJIN');
	$reqKeperluan= $permohonan_lambat_pc->getField('KEPERLUAN');
	$reqJamDatang= $permohonan_lambat_pc->getField('JAM_DATANG');
	$reqJamPulang= $permohonan_lambat_pc->getField('JAM_PULANG');
	$reqKeterangan= $permohonan_lambat_pc->getField('KETERANGAN');
	$reqApproval = $permohonan_lambat_pc->getField("APPROVAL");
	$reqApprovalKeterangan = $permohonan_lambat_pc->getField("APPROVAL_KETERANGAN");
	$reqAlasanTolak = $permohonan_lambat_pc->getField("ALASAN_TOLAK");
	$reqApproval1= $permohonan_lambat_pc->getField("APPROVAL1");
	$reqTanggalAwal = $permohonan_lambat_pc->getField("TANGGAL_AWAL");
	$reqTanggalAkhir = $permohonan_lambat_pc->getField("TANGGAL_AKHIR");
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
		url:'<?=base_url()?>permohonan_lambat_pc_json/approval',
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
	
	setAlasanTolak();
	$("#reqAlasan").change(function() {
		setAlasanTolak();
		//alert("-");
		//OptionSetSplitHargaMasak(rowCount);
		//setHitungTotal();
	});
});
</script>

<script>
function setAlasanTolak()
{
	var reqAlasan= "";
	reqAlasan= $("#reqAlasan").val();
	//alert(reqAlasan);
	//$("#reqAlasanTolak").hide();
	document.getElementById("reqAlasanInfo").style.display="none";
	if(reqAlasan == "T")
	{
		document.getElementById("reqAlasanInfo").style.display="";	
		//$("#reqAlasanInfo").show();
	}
	else
	{
		$("#reqAlasanTolak").val("");
	}
	/*reqAlasanTolak;T
			var verifikasi = document.getElementById("reqAlasan").value;
			if (verifikasi == 'T'){
				document.getElementById("reqAlasanTolak").style.display="";	
				$('#reqAlasanTolak').val(null);
			}else {
				document.getElementById("reqAlasanTolak").style.display="none";
				$('#reqAlasanTolak').val(null);
			}*/
}
</script>

</head>

<body class="bg-kanan-full">
	<div id="judul-popup">Permohonan Terlambat / Pulang Cepat</div>
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
                             <td>Tanggal</td>
                             <td>:</td>
                             <td><?=$reqTanggal?>
                            </td>			
                        </tr>
                        <tr>           
                             <td>Keperluan</td>
                             <td>:</td>
                             <td><?=$reqKeperluan?> 
                            </td>			
                        </tr>
						<?
                        if ($reqTanggalAwal == '' || $reqTanggalAkhir == '')
						{
						?>
                        	<tr>
                                <td>Tanggal Ijin</td>
                                <td>:</td>
                                <td>
                                   <?=$reqTanggalIjin?>
                                </td>
                            </tr>
                            <tr>
                                <td>Jam Datang / Pulang</td>
                                <td>:</td>
                                <td>
                                   <?=$reqJamDatang.$reqJamPulang?> 
                                </td>
                            </tr>
                        <?
						}
						else
						{
                        ?>
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
                        <?
						}
                        ?>
                        <tr>           
                             <td>Keterangan</td>
                             <td>:</td>
                             <td><?=$reqKeterangan?> 
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
                            <td>Persetujuan Anda</td>
                            <td>:</td>
                            <td>
                                <?=$permohonan_lambat_pc->getField("STATUS_APPROVAL")?>&nbsp;<?=$permohonan_lambat_pc->getField("ALASAN_TOLAK")?>
                            </td>
                        </tr>
                        <tr>
                            <td>Persetujuan Lainnya</td>
                            <td>:</td>
                            <td>
                                <?=$permohonan_lambat_pc->getField("STATUS_APPROVAL_LAIN")?>
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
                                <?=$permohonan_lambat_pc->getField("STATUS_APPROVAL_LAIN")?>
                            </td>
                        </tr>
                        <tr>
                            <td>Persetujuan Anda</td>
                            <td>:</td>
                            <td>
                                <select name="reqAlasan" id="reqAlasan">
                                	<option value="">Pilih</option>
                                    <option value="Y" <? if($reqAlasan == 'Y') { ?> selected="selected" <? } ?>>Disetujui</option>
                                    <option value="T" <? if($reqAlasan == 'T') { ?> selected="selected" <? } ?>>Ditolak</option>
                                </select>
                            </td>
                        </tr>
                        <tr id="reqAlasanInfo" style="display:none">
                        	<td>Alasan Penolakan</td>
                            <td>:</td>
                        	<td>
								<input type="text" class="easyui-validatebox" id="reqAlasanTolak" name="reqAlasanTolak" value="<?=$reqAlasanTolak?>" style="width:80%">
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