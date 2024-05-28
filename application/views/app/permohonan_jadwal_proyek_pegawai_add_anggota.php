<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

/* CHECK USER LOGIN 
$CI =& get_instance();
$CI->checkUserLogin();*/

$this->load->model('KelompokProyekPegawai');
$this->load->model('PermohonanProyek');
$this->load->model('Pegawai');

$permohonan_proyek = new PermohonanProyek();
$kelompok_proyek_pegawai = new KelompokProyekPegawai();
$kelompok_proyek_pegawai_approval = new KelompokProyekPegawai();

$reqId = $this->input->get("reqId");

$kelompok_proyek_pegawai->selectByParams(array("A.PERMOHONAN_PROYEK_ID" => $reqId));

$permohonan_proyek->selectByParams(array("A.PERMOHONAN_PROYEK_ID" => $reqId));
$permohonan_proyek->firstRow();
$reqLampiranBso = $permohonan_proyek->getField("LAMPIRAN_BSO");
$reqPermohonanProyekParentId = $permohonan_proyek->getField("PERMOHONAN_PROYEK_PARENT_ID");

if($reqPermohonanProyekParentId != "0")
	$link = "pegawai_proyek_anggota_garansi/?reqId=".$reqId."&reqPermohonanProyekParentId=".$reqPermohonanProyekParentId."";
else
	$link = "pegawai_jabatan_proyek_pencarian/?reqId=".$reqId."";

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
		url:'<?=base_url()?>kelompok_proyek_pegawai_json/add/?reqMode=<?=$reqMode?>&reqId=<?=$reqId?>',
		onSubmit:function(){
			var rowCount = $('#tbData tr').length;
			
			if("<?=$reqMode?>" == "insert")
			{
				if(document.getElementsByClassName('MultiFile-title').length < 1)
				{
					$.messager.alert('Info', "Mohon Upload Lampiran BSO", 'info');	
					return false;
				}	
			}
			else
			{
				if(document.getElementsByClassName('MultiFile-title').length < 1 && $("#tableUpload tr").length < 1)
				{
					$.messager.alert('Info', "Mohon Upload Lampiran BSO", 'info');	
					return false;
				}
				else
				{
					
					if(rowCount < 1)
					{
						$.messager.alert('Info', 'Mohon pilih anggota proyek.', 'info');	
						return false;
					}
					else
					{
						return $(this).form('validate');
					}
				}
			}
			
			
			/*
			
			if(rowCount < 1)
			{
				$.messager.alert('Info', 'Mohon pilih anggota proyek.', 'info');	
				return false;
			}
			else
			{
				return $(this).form('validate');
			}
			*/
		},
		success:function(data){
			// alert(data); return false;
			data = data.split('#');
			if(data[0] == 'F')
				$.messager.alert('Info', data[1], 'info');	
			else
			{
				$.messager.alert('Info', data[1], 'info');	
				// return false;
				//$('#rst_form').click();
				top.frames['mainFrame'].location.reload();
				document.location.reload();
			}
		}
	});
	
	
});

/*
var nomor = 1000;
function create(nip, idKelompok)
{
	nomor += 1;
	$(function () {
		$.get("<?=base_url()?>app/loadUrl/main/permohonan_proyek_anggota_template/?reqId="+nip+"&reqNomor="+nomor+"&reqKelompokId="+idKelompok, function (data) {
			$("#tbData").append(data);
		});
	});
}
*/
var nomor = 1000;
function create(idKelompok)
{
	nomor += 1;
	$(function () {
		$.get("<?=base_url()?>app/loadUrl/main/permohonan_proyek_anggota_template/?reqId="+idKelompok+"&reqNomor="+nomor, function (data) {
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

<!-- UPLOAD CORE -->
<script src="<?=base_url()?>lib/multifile-master/jquery.MultiFile.js"></script>
<script>
// wait for document to load
$(function(){
	
	// invoke plugin
	$('#reqLampiranBso').MultiFile({
		onFileChange: function(){
			console.log(this, arguments);
		}
	});

});

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
        <span>
	        <a id="btnAdd" title="Tambah" onClick="openPopupProyek('<?=base_url()?>app/loadUrl/main/<?=$link?>')"><img src="<?=base_url()?>images/icon-tambah.png" /> Tambah</a>
        	<?php /*?><a id="btnAdd" title="Tambah" onClick="openPopupProyek('<?=base_url()?>app/loadUrl/main/pegawai_jabatan_proyek_pencarian/?reqId=<?=$reqId?>')"><img src="<?=base_url()?>images/icon-tambah.png" /> Tambah</a><?php */?>
        	<!--<a id="btnAdd" title="Tambah" onClick="openPopupProyek('<?=base_url()?>app/loadUrl/main/pegawai_proyek_pencarian/?reqId=<?=$reqId?>')"><img src="<?=base_url()?>images/icon-tambah.png" /> Tambah</a>-->
        </span>
    </div>
	<div id="konten">
    	<div id="popup-tabel2">
            <form id="ff" method="post" novalidate enctype="multipart/form-data">
                <table class="table">
                <thead>
                    <tr>
                        <th style="text-align:center; width:20%">NIP</th>
                        <th style="text-align:center; width:40%">Nama</th>
                        <th style="text-align:center; width:30%">Jabatan</th>
                        <th style="text-align:center; width:30%">TMT</th>
                        <th style="text-align:center; width:10%">Aksi</th>
                    </tr> 
                </thead>
                <tbody id="tbData">
                <?
                $i = 0;
				$kelompok_proyek_pegawai_approval->selectByParams(array("A.PERMOHONAN_PROYEK_ID" => $reqId), -1, -1, " AND (PEGAWAI_ID_APPROVAL1 IS NOT NULL OR PEGAWAI_ID_APPROVAL2 IS NOT NULL)");
				$kelompok_proyek_pegawai_approval->firstRow();
				$reqPegawaiIdApproval1 = $kelompok_proyek_pegawai_approval->getField("PEGAWAI_ID_APPROVAL1");
				$reqPegawaiIdApproval2 = $kelompok_proyek_pegawai_approval->getField("PEGAWAI_ID_APPROVAL2");
				
                while($kelompok_proyek_pegawai->nextRow())
                {
					
					if($kelompok_proyek_pegawai->getField("APPROVAL1") == "" || $kelompok_proyek_pegawai->getField("APPROVAL1") == "T")
					{
						$disabled = "";
					}
					else
					{
						$disabled = "disabled";
					}
                ?>
                    <tr id="<?=$i?>">
                        <td style="width:10%"><?=$kelompok_proyek_pegawai->getField("PEGAWAI_ID")?></td>
                        <td style="width:30%"><?=$kelompok_proyek_pegawai->getField("NAMA_PEGAWAI")?></td>
                        <td style="width:30%">
                        	<input class="easyui-combobox" id="reqJabatanProyekId" name="reqJabatanProyekId[]" style="width:350px;" data-options="
                                        url: '<?=base_url()?>jabatan_proyek_json/combo',
                                        method: 'get',
                                        valueField:'value', 
                                        textField:'text'
                                    " value="<?=$kelompok_proyek_pegawai->getField("JABATAN_PROYEK_ID")?>" required <?=$disabled?>>
                        </td>
                        <td>
                        	<input name="reqTanggalAwal[]" id="reqTanggalAwal" class="easyui-datebox" value="<?=$kelompok_proyek_pegawai->getField("TANGGAL_AWAL")?>" style="width:110%;" <?=$disabled?> />
                        </td>
                        <td align="center">
                            <input type="hidden" name="reqKelompokProyekPegawaiId[]" value="<?=$kelompok_proyek_pegawai->getField("KELOMPOK_PROYEK_PEGAWAI_ID")?>">
                            <input type="hidden" name="reqPegawaiId[]" value="<?=$kelompok_proyek_pegawai->getField("PEGAWAI_ID")?>">
                            <input type="hidden" name="reqNama[]" value="<?=$kelompok_proyek_pegawai->getField("NAMA_PEGAWAI")?>">
                            <?
							if($kelompok_proyek_pegawai->getField("APPROVAL1") == "" || $kelompok_proyek_pegawai->getField("APPROVAL1") == "T")
							{
                            ?>
                            <input class="btn btn-sm btn-danger" type="button" onClick="$('#reqDeleteKelompokProyekPegawai').val($('#reqDeleteKelompokProyekPegawai').val() + ',' + '<?=$kelompok_proyek_pegawai->getField("KELOMPOK_PROYEK_PEGAWAI_ID")?>'); $('#<?=$i?>').remove();" value="Hapus" />        
                            <?
							}
							else
							{
                            ?>
                            	<span><?=$kelompok_proyek_pegawai->getField("STATUS_APPROVAL1")?></span>
                            <?
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
                            <td>Lampiran BSO</td>
                            <td>:</td>
                            <td>
                        		<input name="reqLampiranBso[]" type="file" multiple class="maxsize-1024" accept="pdf|jpg|jpeg|xls|xlsx" id="reqLampiranBso" value="" <? if ($reqLampiranBso == "") { echo 'required'; } ?> />
                            </td>
                        </tr>
                        <? 
						if ($reqLampiranBso == "") 
						{}
						else
						{
						?>
							<tr>
								<td>Dokumen</td>
								<td>:</td>
								<td colspan="4">
									<table id="tableUpload">
									<thead></thead>
									<tbody>
									<?
									$arrLampiran = explode(",", $reqLampiranBso);
									if($reqLampiranBso == "")
									{}
									else
									{
										for($i=0;$i<count($arrLampiran);$i++)
										{
										?>
											<tr>
											
											<td><img src="<?=base_url()?>images/icon-download.png" /><a href="<?=base_url()?>uploads/proyek/bso/<?=$arrLampiran[$i]?>" target="_blank">File <?=($i+1)?></a><input type="hidden" name="reqLampiranBsoTemp[]" value="<?=$arrLampiran[$i]?>" /></td>
											<td id="tdHapus"><a class="hapus" style="cursor:pointer" onclick="$(this).parent().parent().remove();"><img src="<?=base_url()?>images/icon-hapus.png" /></a></td>
											</tr>
										<?
										}
									}
									?>
									</tbody>
									</table>
								</td>
							</tr>
						<?
						}
						?>
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
                                <a id="clueTipBox1" class="clueTipBox" title="Panduan|Apabila data Asman Admin tidak muncul, hubungi Administrator."><img src="<?=base_url()?>images/help.png"></a>
                                -->
                            </td>
                        </tr>        
                        <tr>
                            <td>Approval Manager Teknik</td>
                            <td>:</td>
                            <td>
                            	<?
								$pegawai_manager = new Pegawai();
								$pegawai_manager->selectByParams(array("A.JABATAN_ID" => 'KP00174D'));
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
                <input type="hidden" name="reqDeleteKelompokProyekPegawai" id="reqDeleteKelompokProyekPegawai" value="0" />
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