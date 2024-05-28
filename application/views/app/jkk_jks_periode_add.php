<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

/* CHECK USER LOGIN 
$CI =& get_instance();
$CI->checkUserLogin();*/

$this->load->model('JkkJksPeriode');

$jkk_jks_periode = new JkkJksPeriode();

$reqSemester = $this->input->get("reqSemester");
$reqTahun = $this->input->get("reqTahun");
$tempDepartemen = $userLogin->idDepartemen;

if($reqSemester == "" || $reqTahun == ""){
	$reqMode = "insert";
}
else
{
	$reqMode = "update";	
	$jkk_jks_periode->selectByParams(array('SEMESTER'=>$reqSemester, 'TAHUN'=>$reqTahun), -1, -1);
	$jkk_jks_periode->firstRow();
	
	$tempTahun= $jkk_jks_periode->getField('TAHUN');
    $tempSemester= $jkk_jks_periode->getField('SEMESTER');
    $tempNilai= $jkk_jks_periode->getField('NILAI');
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
		url:'<?=base_url()?>jkk_jks_periode_json/add',
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
	<div id="judul-popup">Tambah Jkk Jks Periode</div>
	<div id="konten">
    	<div id="popup-tabel2">
            <form id="ff" method="post" novalidate>
                    <table class="table">
                    <thead>
                        <tr>           
                             <td>Tahun</td><td>:</td>
                             <td>
                                <?
                                if($reqMode == "insert")
                                {
                                ?>
                                    <input name="reqTahun" class="easyui-validatebox" required="true" style="width:170px" type="text" value="<?=$tempTahun?>" />
                                <?
                                }
                                else
                                {
                                ?>
                                    <input name="reqTahun" type="hidden" value="<?=$tempTahun?>" />
                                    <span><?=$tempTahun?></span>
                                <?
                                }
                                ?>
                            </td>           
                        </tr>
                        <tr>           
                             <td>Semester</td><td>:</td>
                             <td>
                                <?
                                if($reqMode == "insert")
                                {
                                ?>
                                <select name="reqSemester" class="easyui-combobox" required="true" style="width: 120px;">
                                    <option value="1" <? if($tempSemester == "1") echo "selected"; ?> >SEMESTER I</option>
                                    <option value="2" <? if($tempSemester == "2") echo "selected"; ?> >SEMESTER II</option>
                                </select>
                                <?
                                }
                                else
                                {
                                ?>
                                    <input name="reqSemester" type="hidden" value="<?=$tempSemester?>" />
                                    <span><?=$tempSemester?></span>
                                <?
                                }
                                ?>
                            </td>           
                        </tr>
                        <tr>           
                             <td>Nilai</td><td>:</td>
                             <td>
                                <input name="reqNilai" class="easyui-validatebox" required="true" style="width:170px" type="text" value="<?=$tempNilai?>" />
                            </td>			
                        </tr> 
                    </table>
                    </thead>
                    <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
                    <input type="submit" name="reqSubmit"  class="btn btn-primary" value="Submit" />
                    <input type="reset" id="rst_form"  class="btn btn-primary" value="Reset" />
                    
                    </form>
        </div>
        </div>
    </div>
</body>
</html>