<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model("SatuanKerja");
$pejabat_pengganti = new SatuanKerja();

$reqId = $this->input->get("reqId");
$reqParentId = $this->input->get("reqParentId");
$reqMode = $this->input->get("reqMode");
$reqUnitKerjaId= $reqParentId;

$reqKelompokJabatan= "KARYAWAN";
if($reqMode == "insert")
{
    $statement = " AND A.SATUAN_KERJA_ID = '".$reqId."'";
    $order="";
    $pejabat_pengganti->selectByParams(array(),-1,-1,$statement,$order);
    $pejabat_pengganti->firstRow();
    // echo $pejabat_pengganti->query;exit;
    if(!empty($pejabat_pengganti->getField("JABATAN")))
        $reqUnitKerjaParent= "Unit Kerja : ".$pejabat_pengganti->getField("NAMA")."; Jabatan : ".$pejabat_pengganti->getField("JABATAN");
    else
        $reqUnitKerjaParent= $pejabat_pengganti->getField("NAMA");
}
$arrkelompok= infokelompok();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
<base href="<?=base_url();?>">

<!-- Bootstrap Core CSS - Uses Bootswatch Flatly Theme: http://bootswatch.com/flatly/ -->
<link href="lib/valsix/css/bootstrap.min.css" rel="stylesheet">
<!-- jQuery -->
<script src="lib/valsix/js/jquery.js"></script>
<!-- Bootstrap Core JavaScript -->
<script src="lib/valsix/js/bootstrap.min.js"></script>

<?php /*?><script type="text/javascript" src="js/jquery-1.9.1.js"></script><?php */?>

<link rel="stylesheet" type="text/css" href="css/gaya.css">

<link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">

<link href="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

<?php /*?><script type="text/javascript" src="js/jquery-1.6.1.min.js"></script><?php */?>
<script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript" src="lib/easyui/globalfunction.js"></script>

<script src="lib/startbootstrap-sb-admin-2-1.0.7/dist/js/eModal2.min.js"></script>
<script type="text/javascript">

    function openPopup(page) {
        eModal.iframe(page, 'Aplikasi E-Office - PT. Jembatan Nusantara')
    }
    
    function closePopup()
    {
        eModal.close();
    }
    
</script>
<style>
html{
	height: 100%;
}
</style>
</head>

<body class="bg-kanan-full">
    <div id="judul-popup">Kelola Jabatan Struktural</div>
    <div id="konten">
        <div id="popup-tabel2">

            <form id="ff" method="post" novalidate enctype="multipart/form-data">
                <table class="table">
                    <thead>
                        <tr>
                            <td>Parent</td>
                            <td>:</td>
                            <td>
                                <?=$reqUnitKerjaParent?>
                            </td>
                        </tr>
                        <tr>
                            <td>Unit Kerja</td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqUnitKerja" class="easyui-validatebox textbox form-control" name="reqUnitKerja" required value="<?=$reqUnitKerja ?>"  style="width:50%" />
                            </td>
                        </tr>
                        <tr>           
                            <td>Jabatan</td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqJabatan" class="easyui-validatebox textbox form-control" name="reqJabatan" required value="<?=$reqJabatan ?>"  style="width:50%" />  
                            </td>
                        </tr>
                        <tr>           
                            <td>Kelompok</td>
                            <td>:</td>
                            <td>
                                <select name="reqKelompokJabatan" id="reqKelompokJabatan" class="easyui-validatebox" <?=$tempDisabled?>>
                                    <?
                                    for($x=0; $x < count($arrkelompok); $x++)
                                    {
                                      $infoid= $arrkelompok[$x]["id"];
                                      $infotext= $arrkelompok[$x]["nama"];
                                    ?>
                                    <option value="<?=$infoid?>" <? if($reqKelompokJabatan == $infoid) echo "selected";?>><?=$infotext?></option>
                                    <?
                                    }
                                    ?>
                                </select>
                    
                            </td>
                        </tr>
                        <tr>           
                            <td>Kode Jabatan</td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqKodeSurat" class="easyui-validatebox textbox form-control"  name="reqKodeSurat"  value="<?=$reqKodeSurat ?>" style="width:20%" />
                            </td>
                        </tr>
                    </thead>
                </table>

                <input type="hidden" name="reqId" value="<?=$reqId?>" />
                <input type="hidden" name="reqParentId" value="<?=$reqParentId?>" />
                <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
                
            </form>

            <div style="text-align:center;padding:5px">
                <a href="javascript:void(0)" class="btn btn-primary" onclick="submitForm()">Simpan</a>
                <a href="javascript:void(0)" class="btn btn-warning" onclick="kembaliform()">Kembali</a>
            </div>

        </div>
    </div>
</body>
</html>


<script>
function submitForm(){
    
    $('#ff').form('submit',{
        url:'web/jabatan_struktural_add_json/add',
        onSubmit:function(){
            
            return $(this).form('enableValidation').form('validate');
        },
        success:function(data){
            // console.log(data);return false;
            data = data.split("-");
            rowid= data[0];
            infodata= data[1];

            if(rowid == "xxx")
            {
                $.messager.alert('Info', infodata, 'info');
            }
            else
            {
                param= {};
                param.id= rowid;
                param.SATUAN_KERJA_ID_PARENT= "<?=$reqParentId?>";
                param.NAMA= $("#reqUnitKerja").val();
                param.JABATAN= $("#reqJabatan").val();
                // console.log(param);
                top.adddetil(param);
                top.closePopup();
            }
            // $.messager.alertTopLink('Info', data, 'info', "app/loadUrl/main/pegawai_add_satuan_kerja_lookup?reqUnitKerjaId=<?=$reqUnitKerjaId?>");
            
        }
    });
}

function kembaliform()
{
    document.location.href= "app/loadUrl/main/pegawai_add_satuan_kerja_lookup?reqUnitKerjaId=<?=$reqUnitKerjaId?>";
}
            
</script>