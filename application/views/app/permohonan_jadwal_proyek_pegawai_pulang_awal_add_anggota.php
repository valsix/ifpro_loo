<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

/* CHECK USER LOGIN 
$CI =& get_instance();
$CI->checkUserLogin();*/

$this->load->model('ProyekPegawaiPulangAwal');
$this->load->model('PermohonanProyek');
$this->load->model('Pegawai');

$proyek_pegawai_pulang_awal = new ProyekPegawaiPulangAwal();
$proyek_pegawai_pulang_awal_approval = new ProyekPegawaiPulangAwal();
$permohonan_proyek = new PermohonanProyek();

$reqId = $this->input->get("reqId");


$proyek_pegawai_pulang_awal->selectByParams(array("A.PERMOHONAN_PROYEK_ID" => $reqId));

/*
$permohonan_proyek->selectByParams(array("A.PERMOHONAN_PROYEK_ID" => $reqId));
$permohonan_proyek->firstRow();
$reqTanggalAwalProyek = $permohonan_proyek->getField("TANGGAL_AWAL");
*/

if($reqId == "")
{}
else
{
	$reqMode = "update";
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
				alert(reqDelete);
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
	
});



var nomor = 1000;
function create(nip)
{
	nomor += 1;
	$(function () {
		$.get("<?=base_url()?>app/loadUrl/app/permohonan_proyek_anggota_pulang_awal_template/?reqId="+nip+"&reqNomor="+nomor, function (data) {
			$("#tbData").append(data);
		});
	});
}


function openPopupProyek(page) {
	eModal.iframe(page, 'Aplikasi Presensi - PJB Services')
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
<? /* <script src="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/dist/js/eModal2.min.js"></script> */ ?>
<script src="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/dist/js/eModal2-proyek.min.js"></script>

<script type="text/javascript">
	

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
	<div id="judul-popup">Data Anggota Proyek</div>
    <div class="aksi-area">
        <span><a id="btnAdd" title="Tambah" onClick="openPopupProyek('<?=base_url()?>app/loadUrl/main/pegawai_proyek_anggota/?reqId=<?=$reqId?>')"><img src="<?=base_url()?>images/icon-tambah.png" /> Tambah</a></span>
    </div>
	<div id="konten">
    	<div id="popup-tabel2">
            <form id="ff" method="post" novalidate enctype="multipart/form-data">
                <table class="table">
                <thead>
                    <tr>
                        <th style="text-align:center; width:15%">NIP</th>
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
				$proyek_pegawai_pulang_awal_approval->selectByParams(array("A.PERMOHONAN_PROYEK_ID" => $reqId), -1, -1, " AND (PEGAWAI_ID_APPROVAL1 IS NOT NULL OR PEGAWAI_ID_APPROVAL2 IS NOT NULL)");
				$proyek_pegawai_pulang_awal_approval->firstRow();
				$reqPegawaiIdApproval1 = $proyek_pegawai_pulang_awal_approval->getField("PEGAWAI_ID_APPROVAL1");
				$reqPegawaiIdApproval2 = $proyek_pegawai_pulang_awal_approval->getField("PEGAWAI_ID_APPROVAL2");
				
				
                $i = 0;
                while($proyek_pegawai_pulang_awal->nextRow())
                {
					$reqLampiran = $proyek_pegawai_pulang_awal->getField("LAMPIRAN");
                ?>
                    <tr id="<?=$i?>">
                        <td style="width:10%"><?=$proyek_pegawai_pulang_awal->getField("PEGAWAI_ID")?></td>
                        <td style="width:30%"><?=$proyek_pegawai_pulang_awal->getField("NAMA_PEGAWAI")?></td>
                        <td style="width:30%"><?=$proyek_pegawai_pulang_awal->getField("NAMA_JABATAN_PROYEK")?></td>
                        <td style="width:30%">
                        	<input type="text" class="easyui-datebox" id="reqTanggalPulang<?=$i?>" name="reqTanggalPulang[]" value="<?=$proyek_pegawai_pulang_awal->getField("TANGGAL_PULANG")?>" required="required"/>
                        </td>
                        <td>
                            <input type="text" class="easyui-validatebox" id="reqKeterangan<?=$i?>" name="reqKeterangan[]" value="<?=$proyek_pegawai_pulang_awal->getField("KETERANGAN")?>" required="required" />
                        </td>
                        <td>
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
                        </td>
                        <td align="center">
                            <input type="hidden" name="reqProyekPegawaiPulangAwalId[]" value="<?=$proyek_pegawai_pulang_awal->getField("PROYEK_PEGAWAI_PULANG_AWAL_ID")?>">
                            <input type="hidden" name="reqPegawaiId[]" value="<?=$proyek_pegawai_pulang_awal->getField("PEGAWAI_ID")?>">
                            <input type="hidden" name="reqNama[]" value="<?=$proyek_pegawai_pulang_awal->getField("NAMA")?>">
                            <input class="btn btn-sm btn-danger" type="button" onClick="$('#reqDeletePegawaiPulangAwal').val($('#reqDeletePegawaiPulangAwal').val() + ',' + '<?=$proyek_pegawai_pulang_awal->getField("PROYEK_PEGAWAI_PULANG_AWAL_ID")?>'); $('#<?=$i?>').remove();" value="Hapus" />        
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
                            <td style="width:25%">Approval Asman Resplan</td>
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
                            <td>Approval Manager Renbintek</td>
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
                <input type="hidden" name="reqDeletePegawaiPulangAwal" id="reqDeletePegawaiPulangAwal" value="0" />
                <input type="hidden" name="reqId" value="<?=$reqId?>" />
                <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
                <input type="submit" name="reqSubmit"  class="btn btn-primary" value="Submit" />
                <input type="reset" id="rst_form"  class="btn btn-primary" value="Reset" />
        	</form>
        </div>
        </div>
    </div>
</body>
</html>