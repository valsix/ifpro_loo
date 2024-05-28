<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

/* CHECK USER LOGIN 
$CI =& get_instance();
$CI->checkUserLogin();*/

$this->load->model('TiketPihakKetiga');

$tiket_pihak_ketiga = new TiketPihakKetiga();

$reqId = $this->input->get("reqId");

if($reqId == ""){
	$reqMode = "insert";
}
else
{
	$reqMode = "update";	
	$tiket_pihak_ketiga->selectByParams(array('TIKET_PIHAK_KETIGA_ID'=>$reqId), -1, -1);
	$tiket_pihak_ketiga->firstRow();

	$reqTiketPenerimaanId = $tiket_pihak_ketiga->getField("TIKET_PENERIMAAN_ID");
	$reqNamaRekanan = $tiket_pihak_ketiga->getField("NAMA_REKANAN");
	$reqTanggal = dateToPageCheck($tiket_pihak_ketiga->getField("TANGGAL"));
	$reqTanggalAwal = dateToPageCheck($tiket_pihak_ketiga->getField("TANGGAL_AWAL"));
	$reqTanggalAkhir = dateToPageCheck($tiket_pihak_ketiga->getField("TANGGAL_AKHIR"));
	$reqStatusSelesai = $tiket_pihak_ketiga->getField("STATUS_SELESAI");
	$reqLinkFile = $tiket_pihak_ketiga->getField("DOKUMENTASI");
	
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
		url:'<?=base_url()?>tiket_pihak_ketiga_json/add',
		onSubmit:function(){
			return $(this).form('validate');
		},
		success:function(data){
			$.messager.alert('Info', data, 'info');	
			
			$('#reqLinkFile').MultiFile('reset');
			
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
	$('#reqLinkFile').MultiFile({
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
	<div id="judul-popup">Tambah Tiket Penyelesaian</div>
	<div id="konten">
    	<div id="popup-tabel2">
            <form id="ff" method="post" novalidate enctype="multipart/form-data">
                    <table class="table">
                    <thead>
                        <tr>           
                             <td>Tiket Penerimaan</td>
                             <td>:</td>
                             <td>
                             <input type="text" name="reqTiketPenerimaanId" value="<?=$reqTiketPenerimaanId?>">
                             </td>			
                        </tr>
                        <tr>           
                             <td>Nama Rekanan</td>
                             <td>:</td>
                             <td>
                             <input type="text" name="reqNamaRekanan" value="<?=$reqNamaRekanan?>" size="50">
                             </td>			
                        </tr>
                        <tr>
                            <td>Tanggal</td>
                            <td>:</td>
                            <td>
                            <input class="easyui-datebox" name="reqTanggal" value="<?=$reqTanggal?>">
                            </td>
                        </tr> 
                        <tr>
                            <td>Tanggal Awal</td>
                            <td>:</td>
                            <td>
                            <input class="easyui-datebox" name="reqTanggalAwal" value="<?=$reqTanggalAwal?>">
                            </td>
                        </tr> 
                        <tr>
                            <td>Tanggal Akhir</td>
                            <td>:</td>
                            <td>
                            <input class="easyui-datebox" name="reqTanggalAkhir" value="<?=$reqTanggalAkhir?>">
                            </td>
                        </tr>
                        <tr>           
                             <td>Status Selesai</td>
                             <td>:</td>
                             <td>
                             <input type="text" name="reqStatusSelesai" value="<?=$reqStatusSelesai?>">
                             </td>			
                        </tr>
                        <tr>
                            <td>Dokumentasi</td>
                            <td>:</td>
                            <td>
                                 <input name="reqLinkFile[]" type="file" multiple class="maxsize-1024" accept="pdf|jpg|jpeg" id="reqLinkFile" value="" />
                            </td>
                        </tr>
                        <?
                        if($reqLinkFile == "")
                        {}
                        else
                        {
                        ?>
                        <tr>
                            <td>Upload File</td>
                            <td>:</td>
                            <td>
                                <table id="tableUpload">
                                <thead></thead>
                                <tbody>
                                <?
                                $arrDokumen = explode(",", $reqLinkFile);
                                for($i=0;$i<count($arrDokumen);$i++)
                                {
                                ?>
                                    <tr>
                                    <td><a style="cursor:pointer" onclick="$(this).parent().parent().remove();"><img src="<?=base_url()?>images/icon-hapus.png"/></a></td>
                                    <td><a href="<?=base_url()?>uploads/<?=$arrDokumen[$i]?>" target="_blank">File <?=($i+1)?></a><input type="hidden" name="reqLinkFileTemp[]" value="<?=$arrDokumen[$i]?>" /></td>
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