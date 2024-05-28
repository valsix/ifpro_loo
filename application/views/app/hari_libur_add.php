<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

/* CHECK USER LOGIN 
$CI =& get_instance();
$CI->checkUserLogin();*/

$this->load->model('HariLibur');

$hari_libur = new HariLibur();

$reqId = $this->input->get("reqId");
$tempDepartemen = $userLogin->idDepartemen;

if($reqId == ""){
	$reqMode = "insert";
}
else
{
	$reqMode = "update";	
	$hari_libur->selectByParams(array('HARI_LIBUR_ID'=>$reqId), -1, -1);
	$hari_libur->firstRow();
	
	$tempStatusCutiBersama= $hari_libur->getField('STATUS_CUTI_BERSAMA');
	$tempNama= $hari_libur->getField('NAMA');
	$tempKeterangan= $hari_libur->getField('KETERANGAN');
	$tempTanggalAwal= $hari_libur->getField('TANGGAL_AWAL');
	$tempTanggalAkhir= $hari_libur->getField('TANGGAL_AKHIR');
	$tempTanggalFix= $hari_libur->getField('TANGGAL_FIX');
	$tempHari= substr($tempTanggalFix,0,2);
	$tempBulan= substr($tempTanggalFix,2,2);
	if($tempTanggalFix)	$tempPilih= 2;
	else				$tempPilih= 1;
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
<script type="text/javascript">	
function setValue(){
	$('#cc').combotree('setValue', '<?=$tempDepartemen?>');
	
	status=$('#reqPilih').val();
	if(status == 'Dinamis'){
		$('#reqTanggalAwal').addClass('required');
		$('#reqTanggalAkhir').addClass('required');
		$('#reqBulan').removeClass('required');
		$('#reqHari').removeClass('required');
		
		$('#reqBulan').val('');$('#reqHari').val('');
		
		$('#tr_tanggal_awal').show();
		$('#tr_tanggal_akhir').show();
		$('#tr_tanggal_fix').hide();
	}
	else if(status == 'Statis'){
		$('#reqTanggalAwal').removeClass('required');
		$('#reqTanggalAkhir').removeClass('required');
		$('#reqBulan').addClass('required');
		$('#reqHari').addClass('required');
		
		$('#reqTanggalAwal').val('');
		$('#reqTanggalAkhir').val('');
		
		$('#tr_tanggal_awal').hide();
		$('#tr_tanggal_akhir').hide();
		$('#tr_tanggal_fix').show();
	}		
}

$(function(){
	$('#ff').form({
		url:'<?=base_url()?>hari_libur_json/add',
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
				$('#rst_form').click();
			<?
			}
			?>
			top.frames['mainFrame'].location.reload();
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

<body class="bg-kanan-full" onLoad="setValue();">
	<div id="judul-popup">Tambah Hari Libur</div>
	<div id="konten">
    	<div id="popup-tabel2">
            <form id="ff" method="post" novalidate enctype="multipart/form-data">
            <table class="table">
                    <thead>
                        <tr>
                             <td>Pilih</td>
                             <td>
                                <? if ($reqId == ""){?>
                                    <select name="reqPilih" id="reqPilih" onchange="setValue()">
                                    <option>Dinamis</option>
                                    <option>Statis</option>
                                    </select>
                                <? } else { ?>
                                    <select name="reqPilih" id="reqPilih" disabled onchange="setValue()">
                                    <option <? if($tempPilih == 1) echo 'selected'?>>Dinamis</option>
                                    <option <? if($tempPilih == 2) echo 'selected'?>>Statis</option>
                                    </select>
                                <? } ?>
                            </td>			
                        </tr>
                        <tr id="tr_tanggal_awal">    
                            <td>Tanggal Awal</td>
                            <td>
                                <input id="dd" name="reqTanggalAwal" class="easyui-datebox" value="<?=$tempTanggalAwal?>"></input>                
                            </td>
                        </tr>  
                         <tr id="tr_tanggal_akhir">
                            <td>Tanggal Akhir</td>
                            <td>
                                <input id="dd" name="reqTanggalAkhir" class="easyui-datebox" value="<?=$tempTanggalAkhir?>"></input>                
                            </td>
                        </tr> 
                        <tr id="tr_tanggal_fix">
                             <td>Tanggal Fix</td>
                             <td>
                                <select name="reqHari" id="reqHari">
                                <option></option>
                                <? for($i=1;$i<31;$i++){?>
                                    <option value="<?=$i?>" <? if($i == $tempHari) echo 'selected';?>><?=$i?></option>
                                <? }?>
                                </select>
                                &nbsp;&nbsp;
                                <select name="reqBulan" id="reqBulan">
                                <option></option>
                                <? for($i=1;$i<=12;$i++){?>
                                    <option value="<?=$i?>" <? if($i == $tempBulan) echo 'selected';?>><?=getNameMonth($i)?></option>
                                <? }?>
                                </select>
                            </td>			
                        </tr>                    
                        <tr>           
                             <td>Nama</td>
                             <td>
                                <input name="reqNama" class="easyui-validatebox" required style="width:200px" type="text" value="<?=$tempNama?>" />
                            </td>			
                        </tr>
                        <tr>
                            <td>Keterangan</td>
                
                            <td>
                                <textarea name="reqKeterangan" style="width:250px; height:10 0px;"><?=$tempKeterangan?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>Status Cuti Bersama</td>
                            <td><input type="checkbox" name="reqStatusCutiBersama" value="1" <? if($tempStatusCutiBersama == 1) { ?> checked <? } ?>></td>
                        </tr>
                    </table>
                    </thead>
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