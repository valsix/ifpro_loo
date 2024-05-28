<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

/* CHECK USER LOGIN 
$CI =& get_instance();
$CI->checkUserLogin();*/

$this->load->model('PermohonanProyek');
$this->load->model('KelompokProyekPegawai');

$permohonan_proyek= new PermohonanProyek();
$kelompok_proyek_pegawai = new KelompokProyekPegawai();

$reqId = $this->input->get("reqId");
$reqRowId = $this->input->get("reqRowId");

$reqTanggalMutasi = date('d-m-Y');

$reqMode = "insert";

$permohonan_proyek->selectByParamsMonitoring(array('A.PERMOHONAN_PROYEK_ID'=>$reqId), -1, -1);
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


$statement = " AND NOT EXISTS(SELECT 1 FROM PROYEK_MUTASI_PEGAWAI X WHERE X.PEGAWAI_ID = A.PEGAWAI_ID AND X.PERMOHONAN_PROYEK_ID_LAMA = '".$reqId."' AND NVL(X.APPROVAL, 'X') IN ('X', 'Y'))";
$kelompok_proyek_pegawai->selectByParams(array(), -1, -1, $statement);


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>

<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/gaya.css">

<link rel="stylesheet" type="text/css" href="<?=base_url()?>lib/easyui/themes/default/easyui.css">


<script src="<?=base_url()?>lib/startbootstrap-freelancer-1.0.3/js/jquery.js"></script>

<!--<script type="text/javascript" src="<?=base_url()?>js/jquery-1.6.1.min.js"></script>-->
<script type="text/javascript" src="<?=base_url()?>lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript" src="<?=base_url()?>lib/easyui/globalfunction.js"></script>
<script type="text/javascript">	
$(function(){
	$('#ff').form({		
		url:'<?=base_url()?>proyek_mutasi_pegawai_json/add',
		onSubmit:function(){
			return $(this).form('validate');
		},
		success:function(data){
			$.messager.alert('Info', data, 'info');	
			
			$('#reqLampiran').MultiFile('reset');
			
			<?
			if($reqMode == "update")
			{
			?>
				//document.location.reload();
			<?	
			}
			else
			{
			?>
				//$('#rst_form').click();
			<?
			}
			?>
			//top.frames['mainFrame'].location.reload();
		}
	});
	
	
});

$(document).ready( function () {
	$('#reqCentang').on('click', function () {
		$("input[id^=reqCheck]").prop('checked',$("#reqCentang").prop('checked'));
	});
	
	$('#reqTMTSelesaiLama').datebox({
		onSelect: function(date){
			var tanggal_awal = $('#reqTanggalAwal').val();	
			var tanggal_akhir = $('#reqTanggalAkhir').val();	
			var tmt_selesai_lama = $('#reqTMTSelesaiLama').datebox('getValue');	
			
			var jqxhr = $.get("<?=base_url()?>permohonan_proyek_json/cek_tanggal_range/?reqId=<?=$reqId?>&reqTmtSelesaiLama="+tmt_selesai_lama, function(data) {			
				var jumlah = data;
				
				if(Number(jumlah) <= 0)
				{
					$.messager.alert('Info', "Tanggal Selesai Proyek Lama Kurang/Melebihi Range Tanggal Proyek.", 'info');		
					return;
				}
					
			});
			
			
		}
	});
	
	$('#reqTMTMulaiBaru').datebox({
		onSelect: function(date){
			var tmt_selesai_lama = $('#reqTMTSelesaiLama').datebox('getValue');	
			var tmt_mulai_baru = $('#reqTMTMulaiBaru').datebox('getValue');	
			
			var selisih = get_day_between(tmt_selesai_lama, tmt_mulai_baru);
			
			if(tmt_selesai_lama == "")
			{
				$('#reqTMTSelesaiLama').datebox('setValue', '');	
				$.messager.alert('Info', "Isi TMT selesai proyek lama terlebih dahulu.", 'info');		
				return;
			}
			
			if(Number(selisih) <= 0)
			{
				$('#reqTMTMulaiBaru').datebox('setValue', '');
				$.messager.alert('Info', "TMT mulai proyek baru lebih kecil.", 'info');		
				return;
			}
		}
	});
	
	$('#btnSubmit').on('click', function () {
		var arrId = "0";
		
		$("input[id^=reqCheck]").each(function() {
			if($(this).is(':checked'))
			arrId += "," + $(this).val();
		});	
		
		if(arrId == "0")	
		{
			$.messager.alert('Info', "Pilih terlebih dahulu data yang akan dicentang.", 'info');
			return;
		}
		else
		{
			$("#reqRowId").val(arrId);
			$("#reqSubmit").click();
		}
		
	});
	
});

</script>

<!-- UPLOAD CORE -->
<script src="<?=base_url()?>lib/multifile-master/jquery.MultiFile.js"></script>
<script>
// wait for document to load
$(function(){
	
	// invoke plugin
	$('#reqLampiran').MultiFile({
	onFileChange: function(){
		console.log(this, arguments);
	}
	});

});

</script>

<!-- BOOTSTRAP CORE -->
<link href="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

</head>

<body class="bg-kanan-full">
	<div id="judul-popup">Mutasi Pegawai Proyek</div>
	<div id="konten">
    	<div id="popup-tabel2">
            <form id="ff" method="post" novalidate>
            		<table class="table">
                    <thead>
                        <tr>
                            <td>Nomor WO</td>
                            <td>:</td>
                            <td>
                               <span><?=$reqNomor?></span>
                            </td>
                            <td>Tanggal Awal Proyek</td>
                            <td>:</td>
                            <td>
                            	<span><?=$reqTanggalAwal?></span>
                            </td>
                        </tr>
                        <tr>
                            <td>Nama Proyek</td>
                            <td>:</td>
                            <td>
                               <span><?=$reqNama?></span>
                            </td>
                        	<td>Tanggal Akhir Proyek</td>
                            <td>:</td>
                            <td>
                            	<span><?=$reqTanggalAkhir?></span>
                            </td>
                        </tr>
                        <tr>
                            <td>Pegawai PM</td>
                            <td>:</td>
                            <td>
                                <span><?=$this->NAMA?></span>
                            </td>
                            <td>Lokasi</td>
                            <td>:</td>
                            <td>
                                <span>(<?=$reqCabangId?>) <?=$reqNamaCabang?></span>
                            </td>
                        </tr>
                    </thead>
                    </table>
            		<table class="table">
                    	<thead>
                        	<tr>
                    			<th style="text-align:center; width:10%"><input type="checkbox" id="reqCentang"></th>
                            	<th style="text-align:center; width:30%">NIP</th>
                                <th style="text-align:center; width:40%">Nama Pegawai</th>
                                <th style="text-align:center; width:20%">Jabatan</th>
                            </tr>
                        </thead>
                        <tbody>
                        	<?
							$i = 0;
							while($kelompok_proyek_pegawai->nextRow())
							{
							?>
                        	<tr>
                                <td style="text-align:center">
                                    <input type="checkbox" id="reqCheck<?=$i?>" value="<?=$kelompok_proyek_pegawai->getField("PEGAWAI_ID")?>">
                                </td>
                                <td><?=$kelompok_proyek_pegawai->getField("PEGAWAI_ID")?></td>
                                <td><?=$kelompok_proyek_pegawai->getField("NAMA_PEGAWAI")?></td>
                                <td><?=$kelompok_proyek_pegawai->getField("NAMA_JABATAN_PROYEK")?></td>
                            </tr>
							<?
							}
							?>
                        </tbody>
                    </table>
                    <table class="table">
                    <thead>
                        <tr>           
                             <td>Tanggal Mutasi</td><td>:</td>
                             <td>
                             	<input type="text" id="reqTanggalMutasi" name="reqTanggalMutasi" class="easyui-datebox" value="<?=$reqTanggalMutasi?>"></input>
                            </td>			
                        </tr>
                        <tr>
                        	<td>Proyek Baru</td><td>:</td>
                            <td>
                            	<input type="text" id="reqPermohonanProyekId" class="easyui-combobox" name="reqPermohonanProyekId" data-options="url:'<?=base_url()?>permohonan_proyek_json/combo/?reqId=<?=$reqId
								?>',
                                    valueField:'value',
                                    textField:'text'
                                    <? /*,
                                    onSelect: function(rec){
                                        
										$('#reqTanggalAwal').datebox('setValue', rec.tanggal_awal);
                                        $('#reqTanggalAkhir').datebox('setValue', rec.tanggal_akhir);
                                        $('#reqTMTSelesaiLama').datebox('setValue', '');					
                                        $('#reqTMTMulaiBaru').datebox('setValue', '');
										
                                  	}*/ ?>
                                    " style="width:300px;" value="<?=$reqPermohonanProyekId?>">
                            </td>
                        </tr>
                        <tr>
                        	<td>TMT Selesai Proyek Lama</td><td>:</td>
                            <td>
                            	<input type="text" id="reqTMTSelesaiLama" name="reqTMTSelesaiLama" class="easyui-datebox" value="<?=$reqTMTSelesaiLama?>" required="required">
                            </td>
                        </tr>
                        <tr>
                        	<td>TMT Mulai Proyek Baru</td><td>:</td>
                            <td>
                            	<input type="text" id="reqTMTMulaiBaru" name="reqTMTMulaiBaru" class="easyui-datebox" value="<?=$reqTMTMulaiBaru?>">
                            </td>
                        </tr>
                    </table>
                    </thead>
                    <input type="hidden" id="reqRowId" name="reqRowId" value="" />
                    <input type="hidden" name="reqId" value="<?=$reqId?>" />
                    <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
                    <input type="button" id="btnSubmit" name="btnSubmit" class="btn btn-primary" value="Submit"/>
                    <input type="submit" id="reqSubmit" name="reqSubmit"  class="btn btn-primary" value="Submit" style="display:none"/>
                    <input type="reset" id="rst_form"  class="btn btn-primary" value="Reset" />
                    
                    </form>
        </div>
        </div>
    </div>
</body>
</html>