<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

/* CHECK USER LOGIN 
$CI =& get_instance();
$CI->checkUserLogin();*/

$this->load->model('PermohonanProyek');
$this->load->model('ProyekPegawaiPulangAwal');
$this->load->model('Pegawai');

$permohonan_proyek = new PermohonanProyek();
$proyek_pegawai_pulang_awal = new ProyekPegawaiPulangAwal();
$proyek_pegawai_pulang_awal_approval = new ProyekPegawaiPulangAwal();

$reqId = $this->input->get("reqId");
$reqProyekPegawaiPulangAwalId = $this->input->get("reqProyekPegawaiPulangAwalId");


if($reqId == ""){
	$reqMode = "insert";
	$reqTanggal = date("d-m-Y");
	$reqTahun = date("Y");
	$reqJamMasukAwal = "05:00";
	$reqJamMasukAkhir = "15:59";
	$reqJamPulangAwal = "16:00";
	$reqJamPulangAkhir = "04:59";
}
else
{
	$reqMode = "update";
	$permohonan_proyek->selectByParams(array('PERMOHONAN_PROYEK_ID'=>$reqId), -1, -1);
	$permohonan_proyek->firstRow();
	
	$reqNomor			= $permohonan_proyek->getField('NOMOR');
	$reqNama			= $permohonan_proyek->getField('NAMA');
	$reqNamaPegawaiPM	= $permohonan_proyek->getField('NAMA_PEGAWAI');
	$reqPegawaiIdPM		= $permohonan_proyek->getField('PEGAWAI_ID_PM');
	$reqCabangId		= $permohonan_proyek->getField('CABANG_ID');
	$reqNamaCabang		= $permohonan_proyek->getField('NAMA_CABANG');
	$reqTanggal			= $permohonan_proyek->getField('TANGGAL');
	$reqTanggalAwal		= $permohonan_proyek->getField('TANGGAL_AWAL');
	$reqTanggalAkhir	= $permohonan_proyek->getField('TANGGAL_AKHIR');
	
	$reqJamMasukAwal	= $permohonan_proyek->getField('JAM_MASUK_AWAL');
	$reqJamMasukAkhir	= $permohonan_proyek->getField('JAM_MASUK_AKHIR');
	$reqJamPulangAwal	= $permohonan_proyek->getField('JAM_PULANG_AWAL');
	$reqJamPulangAkhir	= $permohonan_proyek->getField('JAM_PULANG_AKHIR');
	
	$reqNomorMesin		= $permohonan_proyek->getField('NOMOR_MESIN');
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
		url:'<?=base_url()?>proyek_pegawai_pulang_awal_json/add/?reqMode=<?=$reqMode?>&reqId=<?=$reqId?>',
		onSubmit:function(){
			var rowCount = $('#tbData tr').length;
			var reqDelete = $("#reqDeletePegawaiPulangAwal").val();
			
			if(rowCount < 1 && reqDelete == "0")
			{
				$.messager.alert('Info', 'Mohon pilih pegawai pulang awal.', 'info');	
				return false;
			}
			else
			{
				return $(this).form('validate');
			}
		},
		success:function(data){
			$.messager.alert('Info', data, 'info');	
			//$('#rst_form').click();
			top.frames['mainFrame'].location.reload();
			document.location.reload();
		}
	});
	
	
	$( "#btnSubmitPosting" ).click(function() {
		
		$.messager.confirm('Konfirmasi','Apakah Anda yakin menyimpan dan memposting data?',function(r){
			if (r){
				$("#reqPosting").val('1');
				$("#reqSubmit").click();
			}
		});
	});
	
	$( "#btnSubmit" ).click(function() {
		$("#reqSubmit").click();
	});
	
	
	$("input[id^='reqTanggalPulang']").datebox({
		onSelect: function(date){
			var	id = $(this).attr('id').replace("reqTanggalPulang", ""); 
			
			var nama_proyek = $('#reqNamaProyek').val();
			
			/* DATE RANGE BETWEEN */
			
			var dateFrom = $('#reqTanggalAwal').val();	
			var dateTo = $('#reqTanggalAkhir').val();
			var dateCheck = $('#reqTanggalPulang'+id).datebox('getValue');	
			
			var d1 = dateFrom.split("-");
			var d2 = dateTo.split("-");
			var c = dateCheck.split("-");
			
			var from = new Date(d1[2], parseInt(d1[1])-1, d1[0]);  // -1 because months are from 0 to 11
			var to   = new Date(d2[2], parseInt(d2[1])-1, d2[0]);
			var check = new Date(c[2], parseInt(c[1])-1, c[0]);
			
			var fDate,lDate,cDate;
			fDate = Date.parse(from);
			lDate = Date.parse(to);
			cDate = Date.parse(check);
			
			if(nama_proyek == "")
			{
				$('#reqTanggalPulang'+id).datebox('setValue', '');	
				$.messager.alert('Info', "Pilih Proyek Terlebih Dahulu.", 'info');	
				return;
			}
			
			if((cDate <= lDate && cDate >= fDate)) {
				return true;
			}
			else
			{
				$.messager.alert('Info', 'Tanggal pulang awal melebihi range antara tanggal awal dan akhir proyek.', 'info');
				$('#reqTanggalPulang'+id).datebox('setValue', '');		
				//return false;
				
			}
			
		}
	});
	
});
	
function pilih(namaProyek, id, tanggalAwal, tanggalAkhir)
{
	document.location.href = "<?=base_url()?>app/loadUrl/app/permohonan_jadwal_proyek_pegawai_pulang_awal_add/?reqId="+id;
}
	

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

var nomor = 1000;
function create(nip)
{
	nomor += 1;
	$(function () {
		$.get("<?=base_url()?>app/loadUrl/app/permohonan_proyek_anggota_pulang_awal_template/?reqPermohonanProyekId=<?=$reqId?>&reqId="+nip+"&reqNomor="+nomor, function (data) {
			$("#tbData").append(data);
		});
	});
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
<!--<script src="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/dist/js/eModal2.min.js"></script>-->
<script src="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/dist/js/eModal2-proyek.min.js"></script>
<script type="text/javascript">
	

	function openPopupProyek(page) {
		eModal.iframe(page, 'Aplikasi Presensi - PJB Services')
	}

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
	<div id="judul-popup">Data Perubahan Jadwal Pegawai</div>
	<div id="konten">
    	<div id="popup-tabel2">
            <form id="ff" method="post" novalidate enctype="multipart/form-data">
            <table class="table">
                    <thead>
                    	<tr>
                        	<th colspan="6">Permohonan</th>
                        </tr>
                        <tr>
                            <td>Nama Proyek</td>
                            <td>:</td>
                            <td colspan="4">
                                <input type="text" id="reqNamaProyek" name="reqNamaProyek" style="width:350px;" class="easyui-validatebox" value="<?=$reqNama?>" readonly required> 
                                <? 
                                if($reqProyekPegawaiPulangAwalId == "") 
                                { 
                                ?>
                                    <input class="btn btn-xs btn-success" type="button" onClick="openPopup('<?=base_url()?>app/loadUrl/app/proyek_pencarian')" value="Browse"/>
                                <?
                                }
                                ?>
                                <input type="hidden" id="reqPermohonanProyekId" name="reqPermohonanProyekId" value="<?=$reqId?>" />
                            </td>
                        </tr>
                        <?
						if($reqId == "")
						{}
						else
						{
						?>
                        <tr>
                            <td>Nomor WO</td>
                            <td>:</td>
                            <td>
                            	<span><?=$reqNomor?></span>
                            </td>
                        	<td>Pegawai PM</td>
                            <td>:</td>
                            <td>
                            	<span><?=$reqNamaPegawaiPM?></span>
                            </td>
                        </tr>
                        <tr>
                        	<td>Lokasi</td>
                            <td>:</td>
                            <td>
                            	<span><?=$reqNamaCabang?></span>
                            </td>
                            <td>Nomor Mesin</td>
                            <td>:</td>
                            <td>
                            	<span><?=$reqNomorMesin?></span>
                            </td>
                        </tr>  
                        <tr>
                            <td>Tanggal Awal</td>
                            <td>:</td>
                            <td>
                            	<span><?=$reqTanggalAwal?></span>
                            </td>
                            <td>Tanggal Akhir</td>
                            <td>:</td>
                            <td>
                            	<span><?=$reqTanggalAkhir?></span>
                            </td>
                        </tr>
                        <?
						}
                        ?>
                    </thead>
                    </table>
                    <table class="table">
                    <thead>
                        <tr>
                            <th style="text-align:center; width:15%">NIP <a id="btnAdd" title="Tambah" style="cursor:pointer" onClick="openPopupProyek('<?=base_url()?>app/loadUrl/main/pegawai_proyek_anggota/?reqId=<?=$reqId?>')"><img src="<?=base_url()?>images/icon-tambah.png" /></a></th>
                            <th style="text-align:center; width:20%">Nama</th>
                            <th style="text-align:center; width:15%">Jabatan</th>
                            <th style="text-align:center; width:15%">Tanggal Pulang Awal</th>
                            <th style="text-align:center; width:15%">Alasan Pulang Awal</th>
                            <th style="text-align:center; width:10%">Lampiran</th>
                            <th style="text-align:center; width:10%">Aksi</th>
                        </tr> 
                    </thead>
                    <tbody id="tbData">
                    <?
                    $proyek_pegawai_pulang_awal_approval->selectByParams(array("A.PERMOHONAN_PROYEK_ID" => $reqId), -1, -1, " AND (A.PEGAWAI_ID_APPROVAL1 IS NOT NULL OR A.PEGAWAI_ID_APPROVAL2 IS NOT NULL)");
					$proyek_pegawai_pulang_awal_approval->firstRow();
                    $reqPegawaiIdApproval1 = $proyek_pegawai_pulang_awal_approval->getField("PEGAWAI_ID_APPROVAL1");
                    $reqPegawaiIdApproval2 = $proyek_pegawai_pulang_awal_approval->getField("PEGAWAI_ID_APPROVAL2");
                    
					if($reqProyekPegawaiPulangAwalId == "")
					{
						$statement = "";   
					}
					else
					{
						$statement = " AND NVL(A.APPROVAL1, 'X') IN ('X', 'P', 'T', 'Y')";
					}
					
                    $i = 0;
					$proyek_pegawai_pulang_awal->selectByParams(array("A.PERMOHONAN_PROYEK_ID" => $reqId), -1, -1, $statement);
                    while($proyek_pegawai_pulang_awal->nextRow())
                    {
                        $reqLampiran = $proyek_pegawai_pulang_awal->getField("LAMPIRAN");
                        $reqApproval1 = $proyek_pegawai_pulang_awal->getField("APPROVAL1");
                        $reqApproval2 = $proyek_pegawai_pulang_awal->getField("APPROVAL2");
                    ?>
                        <tr id="<?=$i?>">
                            <td style="width:10%"><?=$proyek_pegawai_pulang_awal->getField("PEGAWAI_ID")?></td>
                            <td style="width:20%"><?=$proyek_pegawai_pulang_awal->getField("NAMA_PEGAWAI")?></td>
                            <td style="width:20%"><?=$proyek_pegawai_pulang_awal->getField("NAMA_JABATAN_PROYEK")?></td>
                            <td style="width:20%">
                            	<?
								if($reqApproval1 == "")
								{
								?>
                                <input type="text" class="easyui-datebox" id="reqTanggalPulang<?=$i?>" name="reqTanggalPulang[]" value="<?=$proyek_pegawai_pulang_awal->getField("TANGGAL_PULANG")?>" required="required" data-options="editable:false"/>
                                <?
								}
								else
								{
								?>
                                	<input type="hidden" id="reqTanggalPulang<?=$i?>" name="reqTanggalPulang[]" value="<?=$proyek_pegawai_pulang_awal->getField("TANGGAL_PULANG")?>" style="display:none"/>
                                	<span><?=$proyek_pegawai_pulang_awal->getField("TANGGAL_PULANG")?></span>
								<?
								}
                                ?>
                            </td>
                            <td style="width:20%">
                            	<?
								if($reqApproval1 == "")
								{
								?>
                                <input type="text" class="easyui-validatebox" id="reqKeterangan<?=$i?>" name="reqKeterangan[]" value="<?=$proyek_pegawai_pulang_awal->getField("KETERANGAN")?>" required="required" />
                                <?
								}
								else
								{
								?>
                                	<input type="hidden" id="reqKeterangan<?=$i?>" name="reqKeterangan[]" value="<?=$proyek_pegawai_pulang_awal->getField("KETERANGAN")?>"/>
                                	<span><?=$proyek_pegawai_pulang_awal->getField("KETERANGAN")?></span>
								<?
								}
                                ?>
                            </td>
                            <td>
                            	<?
								if($reqApproval1 == "")
								{
								?>
                                    <input name="reqLampiran[]" id="reqLampiran<?=$i?>" type="file" class="maxsize-1024" accept="pdf|jpg|jpeg" value="" />
                                    <input name="reqLampiranTemp[]" type="hidden" id="reqLampiranTemp" value="<?=$reqLampiran?>" />
                                    <?
                                    if($reqLampiran == "")
                                    {}
                                    else
                                    {
                                    ?>
                                        <img src="<?=base_url()?>images/icon-download.png" /><a href="<?=base_url()?>uploads/pegawai_pulang_awal/<?=$reqLampiran?>" target="_blank">File</a>
                                    <?
                                    }
                                    ?>
                                <?
								}
								else
								{
								?>
                                	<input name="reqLampiran[]" id="reqLampiran<?=$i?>" type="file" class="maxsize-1024" accept="pdf|jpg|jpeg" value="" style="display:none"/>
                                    <input name="reqLampiranTemp[]" type="hidden" id="reqLampiranTemp" value="<?=$reqLampiran?>" />
                                	<img src="<?=base_url()?>images/icon-download.png" /><a href="<?=base_url()?>uploads/pegawai_pulang_awal/<?=$reqLampiran?>" target="_blank">File</a>
								<?
								}
                                ?>
                            </td>
                            <td align="center">
                                <input type="hidden" name="reqProyekPegawaiPulangAwalId[]" value="<?=$proyek_pegawai_pulang_awal->getField("PROYEK_PEGAWAI_PULANG_AWAL_ID")?>">
                                <input type="hidden" name="reqPegawaiId[]" value="<?=$proyek_pegawai_pulang_awal->getField("PEGAWAI_ID")?>">
                                <input type="hidden" name="reqNama[]" value="<?=$proyek_pegawai_pulang_awal->getField("NAMA")?>">
                                <input type="hidden" name="reqApproval1[]" value="<?=$proyek_pegawai_pulang_awal->getField("APPROVAL1")?>">
                                <?
								if($reqApproval1 == "")
								{
                                ?>
                                <input class="btn btn-sm btn-danger" type="button" onClick="$('#reqDeletePegawaiPulangAwal').val($('#reqDeletePegawaiPulangAwal').val() + ',' + '<?=$proyek_pegawai_pulang_awal->getField("PROYEK_PEGAWAI_PULANG_AWAL_ID")?>'); $('#<?=$i?>').remove();" value="Hapus" />        
                                <?
								}
								else
								{
									if($reqApproval1 == "P")
										echo "Posting";
									elseif($reqApproval1 == "Y")
										echo "Disetujui";
									else
										echo "Ditolak";
								}
                                ?>
                            </td>
                        </tr>                    
                    <?
                        $i++;
                    }
                    ?>
                    </tbody>
                    </table>
                    <table class="table">
                        <tbody>
                            <tr>
                                <td style="width:25%">Approval Asman Teknik</td>
                                <td>:</td>
                                <td>
                                    <?
                                    $pegawai_spv = new Pegawai();
                                    $pegawai_spv->selectByParams(array("A.JABATAN_ID" => 'KP00137D'));
                                    $pegawai_spv->firstRow();
                                    
                                    $reqPegawaiId = $pegawai_spv->getField("PEGAWAI_ID");
                                    ?>
                                    <input type="hidden" name="reqPegawaiIdApproval1" id="reqPegawaiIdApproval1" value="<?=$reqPegawaiId?>" />
                                    <span><?=$pegawai_spv->getField("NAMA")." (".trim($pegawai_spv->getField("NAMA_STAFF"))." ".trim($pegawai_spv->getField("JABATAN")).")"?></span>
                                    <!--
                                    <input class="easyui-combobox" name="reqPegawaiIdApproval1" id="reqPegawaiIdApproval1" style="width:400px;" data-options="
                                        url: '<?=base_url()?>pegawai_json/combo_asman_proyek/',
                                        method: 'get',
                                        valueField:'id', 
                                        textField:'nama',
                                        editable:false
                                    " value="<?=$reqPegawaiIdApproval1?>" required="required">
                                    <a id="clueTipBox1" class="clueTipBox" title="Panduan|Apabila data Asman tidak muncul, hubungi Administrator."><img src="<?=base_url()?>images/help.png"></a>
                                    -->
                                </td>
                            </tr>        
                            <tr>
                                <td>Approval Manager Teknik</td>
                                <td>:</td>
                                <td>
                                    <?
                                    $pegawai_manager = new Pegawai();
                                    $pegawai_manager->selectByParams(array("A.JABATAN_ID" => 'KP00136D'));
                                    $pegawai_manager->firstRow();
                                    
                                    $reqPegawaiId = $pegawai_manager->getField("PEGAWAI_ID");
                                    ?>
                                    <input type="hidden" name="reqPegawaiIdApproval2" id="reqPegawaiIdApproval2" value="<?=$reqPegawaiId?>" />
                                    <span><?=$pegawai_manager->getField("NAMA")." (".trim($pegawai_manager->getField("NAMA_STAFF"))." ".trim($pegawai_manager->getField("JABATAN")).")"?></span>
                                    <!--
                                    <input class="easyui-combobox" name="reqPegawaiIdApproval2" id="reqPegawaiIdApproval2" style="width:400px;" data-options="
                                        url: '<?=base_url()?>pegawai_json/combo_manajer_proyek/',
                                        method: 'get',
                                        valueField:'id', 
                                        textField:'nama',
                                        editable:false
                                    " value="<?=$reqPegawaiIdApproval2?>" required>
                                    <a id="clueTipBox2" class="clueTipBox" title="Panduan|Apabila data Manager tidak muncul, hubungi Administrator."><img src="<?=base_url()?>images/help.png"></a>
                                    -->
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <input type="hidden" name="reqTanggalAwal" id="reqTanggalAwal" value="<?=$reqTanggalAwal?>" />
                    <input type="hidden" name="reqTanggalAkhir" id="reqTanggalAkhir" value="<?=$reqTanggalAkhir?>" />
                    <input type="hidden" name="reqDeletePegawaiPulangAwal" id="reqDeletePegawaiPulangAwal" value="0" />
                    <input type="hidden" name="reqId" value="<?=$reqId?>" />
                    <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
                    <input type="hidden" name="reqPosting" id="reqPosting" value="0" />
                    <input type="button" id="btnSubmitPosting" class="btn btn-primary" value="Submit & Posting" />
                    <input type="button" id="btnSubmit" class="btn btn-primary" value="Submit" />
                    <input type="submit" id="reqSubmit" name="reqSubmit"  class="btn btn-primary" value="Submit" style="display:none"/>
                    <!--
                    <input type="submit" name="reqSubmit"  class="btn btn-primary" value="Submit" />
                    <input type="reset" id="rst_form"  class="btn btn-primary" value="Reset" />
                    -->
                    </form>
        </div>
        </div>
    </div>
</body>
</html>