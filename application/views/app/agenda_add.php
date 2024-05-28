<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model('Agenda');
$this->load->model('AgendaKategori');

$agenda= new Agenda();
$agenda_kategori= new AgendaKategori();

$reqId = $this->input->get("reqId");

if($reqId == ""){
	$reqMode = "insert";
}
else
{
	$reqMode = "update";	
	$agenda->selectByParams(array("AGENDA_ID" => $reqId));
	$agenda->firstRow();
	$reqAgendaKategoriId = $agenda->getField("AGENDA_KATEGORI_ID");
	$reqNama = $agenda->getField("NAMA");
	$reqKeterangan = $agenda->getField("KETERANGAN");
	$reqTanggalMulai = $agenda->getField("TANGGAL_MULAI");
	$reqTanggalSelesai = $agenda->getField("TANGGAL_SELESAI");
	$reqJamMulai = $agenda->getField("JAM_MULAI");
	$reqJamSelesai = $agenda->getField("JAM_SELESAI");
	$reqLampiran = $agenda->getField("LAMPIRAN");
	$reqPublish = $agenda->getField("PUBLISH");
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
$(function(){
	$('#ff').form({
		url:'<?=base_url()?>agenda_json/add',
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
				document.location.reload();
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

<body class="bg-kanan-full">
	<div id="judul-popup">Tambah Agenda</div>
	<div id="konten">
    	<div id="popup-tabel2">
            <form id="ff" method="post" novalidate enctype="multipart/form-data">
            <table class="table">
            <thead>
            	<tr>
                    <td>Kategori</td>
                    <td>:</td>
                    <td>
                        <select name="reqAgendaKategoriId">
                            <option value="">[-Pilih Agenda Kategori-]</option>
                            <?
							$statement = "";
                            $agenda_kategori->selectByParams(array(), -1, -1, $statement);
                            while($agenda_kategori->nextRow())
                            {
                            ?>
                            <option value="<?=$agenda_kategori->getField("AGENDA_KATEGORI_ID")?>" <? if($agenda_kategori->getField("AGENDA_KATEGORI_ID") == $reqAgendaKategoriId) { ?> selected="selected" <? } ?>>
                            	<?=$agenda_kategori->getField("NAMA")?>
                            </option>
                            <? 
						    } 
						    ?>
                    	</select>
                    </td>
                </tr>        
            	<tr>
                    <td>Nama</td>
                    <td>:</td>
                    <td>
                        <input type="text" name="reqNama" class="easyui-validatebox" style="width:350px;" value="<?=$reqNama?>" />
                    </td>
                </tr>   
                <tr>
                    <td>Keterangan</td>
                    <td>:</td>
                    <td>
                        <textarea name="reqKeterangan" style="height:70px; width:350px;"><?=$reqKeterangan?></textarea>
                    </td>
                </tr>  
                <tr>
                    <td>Tanggal Mulai</td>
                    <td>:</td>
                    <td>
                        <input class="easyui-datebox" name="reqTanggalMulai" value="<?=$reqTanggalMulai?>">
                    </td>
                </tr>  
                <tr>
                    <td>Tanggal Selesai</td>
                    <td>:</td>
                    <td>
                        <input class="easyui-datebox" name="reqTanggalSelesai" value="<?=$reqTanggalSelesai?>">
                    </td>
                </tr> 
                <tr>
                    <td>Jam Mulai</td>
                    <td>:</td>
                    <td>
                        <input type="text" id="reqJamMulai" name="reqJamMulai" size="6" class="easyui-validatebox" value="<?=$reqJamMulai?>" onkeydown="return format_menit(event,'reqJamMulai');" maxlength="5"> * (format = hh:mm)
                    </td>
                </tr>  
                <tr>
                    <td>Jam Selesai</td>
                    <td>:</td>
                    <td>
                        <input type="text" id="reqJamSelesai" name="reqJamSelesai" size="6" class="easyui-validatebox" value="<?=$reqJamSelesai?>" onkeydown="return format_menit(event,'reqJamSelesai');" maxlength="5"> * (format = hh:mm)
                    </td>
                </tr>  
                <tr>
                    <td>Publish</td>
                    <td>:</td>
                    <td>
                        
                    </td>
                </tr>
                <tr>
                    <td>Lampiran</td>
                    <td>:</td>
                    <td>
                        <input name="reqLampiran[]" type="file" multiple class="maxsize-1024" id="reqLampiran" value="" />
                    </td>
                </tr>  
                <?
                if($reqLampiran == "")
				{}
				else
				{
				?>
                <tr>
                    <td>Upload Dokumen</td>
                    <td>:</td>
                    <td>
                    	<table id="tableUpload">
                        <thead></thead>
                        <tbody>
                        <?
                        $arrDokumen = explode(",", $reqLampiran);
						for($i=0;$i<count($arrDokumen);$i++)
						{
						?>
                        	<tr>
                        	<td><a style="cursor:pointer" onclick="$(this).parent().parent().remove();"><img src="<?=base_url()?>images/icon-hapus.png"/></a></td>
                        	<td><a href="<?=base_url()?>uploads/<?=$arrDokumen[$i]?>" target="_blank"><?=$arrDokumen[$i]?></a><input type="hidden" name="reqLampiranTemp[]" value="<?=$arrDokumen[$i]?>" /></td>
                            </tr>
                        <?
						}
                        ?>
                        </tbody>
                        </table>
                    </td>                
                </tr>
                <?
				}
				?>
            </thead>
            </table>
            <input type="hidden" name="reqId" value="<?=$reqId?>" />
            <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
            <input type="submit" name="reqSubmit" value="Submit" />
            <input type="reset" id="rst_form" value="Reset" />
            
            </form>
        </div>
        </div>
    </div>
</body>
</html>