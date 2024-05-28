<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

/* CHECK USER LOGIN 
$CI =& get_instance();
$CI->checkUserLogin();*/

$this->load->model('JamKhususCabang');

$jam_khusus_cabang = new JamKhususCabang();

$reqId = $this->input->get("reqId");
$reqCabangId = $this->input->get("reqCabangId");
$tempDepartemen = $userLogin->idDepartemen;

if($reqId == ""){
    $reqMode = "insert";
}
else
{
    $reqMode = "update";    
    $jam_khusus_cabang->selectByParams(array('JAM_KHUSUS_CABANG_ID'=>$reqId), -1, -1);
    $jam_khusus_cabang->firstRow();
    
    $reqCabangId     = $jam_khusus_cabang->getField('CABANG_ID');
    $reqJamAwal        = $jam_khusus_cabang->getField('JAM_AWAL');
    $reqJamAkhir       = $jam_khusus_cabang->getField('JAM_AKHIR');
    $reqTanggalAwal        = dateToPageCheck($jam_khusus_cabang->getField('TANGGAL_AWAL'));
    $reqTanggalAkhir       = dateToPageCheck($jam_khusus_cabang->getField('TANGGAL_AKHIR'));
    $reqJumatAwal      = $jam_khusus_cabang->getField('JUMAT_AWAL');
    $reqJumatAkhir     = $jam_khusus_cabang->getField('JUMAT_AKHIR');
    $reqIstirahatBiasa = $jam_khusus_cabang->getField('ISTIRAHAT_BIASA');
    $reqIstirahatJumat = $jam_khusus_cabang->getField('ISTIRAHAT_JUMAT');
	
    $reqJamAwalToleransi        = $jam_khusus_cabang->getField('TERLAMBAT_AWAL');
    $reqJamAkhirToleransi       = $jam_khusus_cabang->getField('TERLAMBAT_AKHIR');
    $reqJumatAwalToleransi      = $jam_khusus_cabang->getField('TERLAMBAT_AWAL_JUMAT');
    $reqJumatAkhirToleransi     = $jam_khusus_cabang->getField('TERLAMBAT_AKHIR_JUMAT');
	
}

// $statement = "";
// $jam_kerja_jenis->selectByParams(array(), -1, -1, $statement);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
<base href="<?=base_url()?>">
<link rel="stylesheet" type="text/css" href="css/gaya.css">

<link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="js/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript" src="lib/easyui/globalfunction.js"></script>
<script type="text/javascript"> 
$(function(){
    $('#ff').form({
        url:'jam_khusus_cabang_json/add',
        onSubmit:function(){
            return $(this).form('validate');
            if($("#reqCabangId").combobox('getValue') == "" || $("#reqCabangId").combobox('getValue') == null )
            {
                $.messager.alert('Info', "Cabang tidak boleh kosong.", 'info');
                
            }
            else
            {
                return $(this).form('validate');
            }
        },
        success:function(data){
            $.messager.alert('Info', data, 'info'); 
            // return false;    
            
            //$('#reqLampiran').MultiFile('reset');
            
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


<!-- BOOTSTRAP CORE -->
<link href="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

</head>

<body class="bg-kanan-full">
    <div id="judul-popup">Tambah Jam Khusus</div>
    <div id="konten">
        <div id="popup-tabel2">
            <form id="ff" method="post" novalidate>
                    <table class="table">
                    <thead>
                        <tr>
                            <td>Cabang</td>
                            <td>:</td>
                            <td>
                                <input class="easyui-combobox" id="reqCabangId" name="reqCabangId" style="width:350px;" data-options="
                                    url: 'cabang_combo_json/json',
                                    method: 'get',
                                    valueField:'value', 
                                    textField:'text'
                                " value="<?=$reqCabangId?>" required="required">
                            </td>
                        </tr>   
                        <tr>           
                             <td>Periode</td><td>:</td>
                             <td>
                                <input name="reqTanggalAwal" class="easyui-datebox" required type="text" value="<?=$reqTanggalAwal?>" />
                                s/d
                                <input name="reqTanggalAkhir" class="easyui-datebox" required type="text" value="<?=$reqTanggalAkhir?>" />
                            </td>			
                        </tr> 
                        <tr>
                        	<td colspan="3">(Senin s.d Kamis)</td>
                        </tr> 
                        <tr>           
                             <td>Jam Kerja</td><td>:</td>
                             <td>
                                Masuk <input class="easyui-timespinner" name="reqJamAwal" id="reqJamAwal" data-options="max:'23:59'" required="true" style="width:70px;" maxlength="5" value="<?=$reqJamAwal?>" onkeydown="return format_menit(event,'reqJamAwal');" />
                                <?php /*?><input  id="reqJamAwal" name="reqJamAwal" class="easyui-validatebox" required="true" style="width:40px" type="text" value="<?=$tempJamAwal?>" maxlength="5" onkeydown="return format_menit(event,'reqJamAwal');"/><?php */?>
                                Pulang 
                                <input class="easyui-timespinner" name="reqJamAkhir" id="reqJamAkhir" validType="BandingJam['#reqJamAwal']" data-options="max:'23:59'" required="true" style="width:70px;" maxlength="5" value="<?=$reqJamAkhir?>" onkeydown="return format_menit(event,'reqJamAkhir');" />
                            </td>           
                        </tr>   
                        <tr>           
                             <td>Toleransi Jam Kerja</td><td>:</td>
                             <td>
                                Masuk <input class="easyui-timespinner" name="reqJamAwalToleransi" id="reqJamAwalToleransi" data-options="max:'23:59'" required="true" style="width:70px;" maxlength="5" value="<?=$reqJamAwalToleransi?>" onkeydown="return format_menit(event,'reqJamAwalToleransi');" />
                                <?php /*?><input  id="reqJamAwal" name="reqJamAwal" class="easyui-validatebox" required="true" style="width:40px" type="text" value="<?=$tempJamAwal?>" maxlength="5" onkeydown="return format_menit(event,'reqJamAwal');"/><?php */?>
                                Pulang 
                                <input class="easyui-timespinner" name="reqJamAkhirToleransi" id="reqJamAkhirToleransi" validType="BandingJam['#reqJamAwalToleransi']" data-options="max:'23:59'" required="true" style="width:70px;" maxlength="5" value="<?=$reqJamAkhirToleransi?>" onkeydown="return format_menit(event,'reqJamAkhirToleransi');" />
                            </td>           
                        </tr>   
                        <tr>           
                             <td>Jam Istirahat</td><td>:</td>
                             <td>
                                <input class="easyui-timespinner" name="reqIstirahatBiasa" id="reqIstirahatBiasa" data-options="max:'23:59'" required="true" style="width:70px;" maxlength="5" value="<?=$reqIstirahatBiasa?>" onkeydown="return format_menit(event,'reqIstirahatBiasa');" />
                                <?php /*?><input  id="reqTerlambatAkhir" name="reqTerlambatAkhir" class="easyui-validatebox" required="true" style="width:40px" type="text" value="<?=$tempTerlambatAkhir?>" maxlength="5" onkeydown="return format_menit(event,'reqTerlambatAkhir');"/>    <?php */?>
                            </td>           
                        </tr> 
                        <tr>
                        	<td colspan="3">(Jum'at)</td>
                        </tr> 
                        <tr>           
                             <td>Jam Kerja</td><td>:</td>
                             <td>
                                <input class="easyui-timespinner" name="reqJumatAwal" id="reqJumatAwal" data-options="max:'23:59'" required="true" style="width:70px;" maxlength="5" value="<?=$reqJumatAwal?>" onkeydown="return format_menit(event,'reqJumatAwal');" />
                                <?php /*?><input  id="reqJamAwal" name="reqJamAwal" class="easyui-validatebox" required="true" style="width:40px" type="text" value="<?=$tempJamAwal?>" maxlength="5" onkeydown="return format_menit(event,'reqJamAwal');"/><?php */?>
                                S/D
                                <input class="easyui-timespinner" name="reqJumatAkhir" id="reqJumatAkhir" validType="BandingJam['#reqJumatAkhir']" data-options="max:'23:59'" required="true" style="width:70px;" maxlength="5" value="<?=$reqJumatAkhir?>" onkeydown="return format_menit(event,'reqJumatAkhir');" />
                            </td>           
                        </tr>
                        <tr>           
                             <td>Toleransi Jam Kerja</td><td>:</td>
                             <td>
                                <input class="easyui-timespinner" name="reqJumatAwalToleransi" id="reqJumatAwalToleransi" data-options="max:'23:59'" required="true" style="width:70px;" maxlength="5" value="<?=$reqJumatAwalToleransi?>" onkeydown="return format_menit(event,'reqJumatAwalToleransi');" />
                                <?php /*?><input  id="reqJamAwal" name="reqJamAwal" class="easyui-validatebox" required="true" style="width:40px" type="text" value="<?=$tempJamAwal?>" maxlength="5" onkeydown="return format_menit(event,'reqJamAwal');"/><?php */?>
                                S/D
                                <input class="easyui-timespinner" name="reqJumatAkhirToleransi" id="reqJumatAkhirToleransi" validType="BandingJam['#reqJumatAwalToleransi']" data-options="max:'23:59'" required="true" style="width:70px;" maxlength="5" value="<?=$reqJumatAkhirToleransi?>" onkeydown="return format_menit(event,'reqJumatAkhirToleransi');" />
                            </td>           
                        </tr>
                        <tr>           
                             <td>Jam Istirahat</td><td>:</td>
                             <td>
                                <input class="easyui-timespinner" name="reqIstirahatJumat" id="reqIstirahatJumat" data-options="max:'23:59'" required="true" style="width:70px;" maxlength="5" value="<?=$reqIstirahatJumat?>" onkeydown="return format_menit(event,'reqIstirahatJumat');" />
                                <?php /*?><input  id="reqTerlambatAkhir" name="reqTerlambatAkhir" class="easyui-validatebox" required="true" style="width:40px" type="text" value="<?=$tempTerlambatAkhir?>" maxlength="5" onkeydown="return format_menit(event,'reqTerlambatAkhir');"/>    <?php */?>
                            </td>           
                        </tr> 
                    </table>
                    </thead>
                    <input type="hidden" name="reqId" value="<?=$reqId?>" />
                    <input type="hidden" name="reqMode" value="<?=$reqMode?>" />                    
                    <!-- <input type="hidden" name="reqCabangId" value="<?=$reqCabangId?>" /> -->
                    <input type="submit" name="reqSubmit"  class="btn btn-primary" value="Submit" />
                    <input type="reset" id="rst_form"  class="btn btn-primary" value="Reset" />
                    
                    </form>
        </div>
        </div>
    </div>
</body>
</html>