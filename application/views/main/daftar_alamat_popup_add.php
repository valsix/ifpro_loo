<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model("DaftarAlamat");
$daftar_alamat = new DaftarAlamat();

$reqId = $this->input->get("reqId");
$reqCabangId = $this->CABANG_ID;

if($reqId == ""){
$reqMode                    = "insert";
}else{
$reqMode                    = "ubah";
$statement                  = " AND A.DAFTAR_ALAMAT_ID = ".$reqId;
$daftar_alamat->selectByParamsMonitoring(array(), -1,-1, $statement);

$daftar_alamat->firstRow();
$reqDaftarAlamatId          = $daftar_alamat->getField("DAFTAR_ALAMAT_ID");
$reqInstansi                = $daftar_alamat->getField("INSTANSI");
$reqAlamat                  = $daftar_alamat->getField("ALAMAT");
$reqKota                    = $daftar_alamat->getField("KOTA");
$reqNoTelp                  = $daftar_alamat->getField("NO_TELP");
$reqEmail                   = $daftar_alamat->getField("EMAIL");
$reqStatus                  = $daftar_alamat->getField("STATUS");
$reqKodePos                 = $daftar_alamat->getField("KODE_POS");
$reqFax                     = $daftar_alamat->getField("FAX");
$reqNamaKepala              = $daftar_alamat->getField("NAMA_KEPALA");
$reqJabatanKepala           = $daftar_alamat->getField("JABATAN_KEPALA");
$reqHp                      = $daftar_alamat->getField("HP");
$reqDaftarAlamatGroupId     = $daftar_alamat->getField("DAFTAR_ALAMAT_GROUP_ID");


}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
<base href="<?=base_url();?>">

<!-- Bootstrap Core CSS - Uses Bootswatch Flatly Theme: http://bootswatch.com/flatly/ -->
<link href="lib/valsix/css/bootstrap.min.css" rel="stylesheet">

<!-- Custom CSS -->
<link href="lib/valsix/css/freelancer.css" rel="stylesheet">

<!-- Custom Fonts -->
<link href="lib/valsix/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

<!-- CHART.JS -->
<script src="lib/Chart.js-master/Chart.js"></script>

<!-- ANOSLIDE -->
<link href="lib/anoslide/css/anoslide.css" rel="stylesheet" type="text/css" />
<!-- RESPONSIVE TAB MASTER -->
<link rel="stylesheet" href="lib/responsive-tabs-master/responsive-tabs2.css" type="text/css">
<!-- jQuery -->
<script src="lib/valsix/js/jquery.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="lib/valsix/js/bootstrap.min.js"></script>

<!-- Plugin JavaScript -->
<!--<script src="lib/valsix/js/jquery.easing.min.js"></script>-->
<script src="lib/valsix/js/classie.js"></script>
<script src="lib/valsix/js/cbpAnimatedHeader.js"></script>

<!-- Custom Theme JavaScript -->
<script src="lib/valsix/js/freelancer.js"></script>
<script type="text/javascript" src="<?=base_url()?>lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript" src="<?=base_url()?>lib/easyui/globalfunction.js"></script>
<script type="text/javascript"> 
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

<script src="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/dist/js/eModal2.min.js"></script>
<script type="text/javascript">
    

    function openPopup(page) {
        eModal.iframe(page, 'Aplikasi E-Office - PT. Jembatan Nusantara')
    }
    
    function closePopup()
    {
        eModal.close();
    }
    
</script>

<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/gaya.css">
<link rel="stylesheet" type="text/css" href="<?=base_url()?>lib/easyui/themes/default/easyui.css">
<link href="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

<style>
.container-treegrid {
    height: auto;
}
</style>
</head>

<body class="body-popup">
	
    <div class="container-fluid container-treegrid">
    	
        <div class="row row-treegrid">
        	<div class="col-md-12 col-treegrid">
            	<div class="area-konten-atas">
                	<div class="judul-halaman">Kelola Daftar Alamat</div>
                </div>
                <div id="konten">
                    <div id="popup-tabel2">
                        <form id="ff" method="post" novalidate enctype="multipart/form-data">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <td>Instansi</td>
                                        <td>:</td>
                                        <td>
                                            <input type="text" id="reqInstansi" class="easyui-validatebox textbox form-control" required name="reqInstansi"  value="<?=$reqInstansi ?>" data-options="required:true" style="width:80%" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Alamat</td>
                                        <td>:</td>
                                        <td>
                                            <input type="text" id="reqAlamat" class="easyui-validatebox textbox form-control" name="reqAlamat"  value="<?=$reqAlamat ?>" style="width:80%" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Kota</td>
                                        <td>:</td>
                                        <td>
                                            <input type="text" id="reqKota" class="easyui-validatebox textbox form-control" name="reqKota"  value="<?=$reqKota ?>" style="width:80%" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>No Telp</td>
                                        <td>:</td>
                                        <td>
                                            <input type="text" id="reqNoTelp" class="easyui-numberbox textbox form-control" name="reqNoTelp"  value="<?=$reqNoTelp ?>" style="width:250px" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Email</td>
                                        <td>:</td>
                                        <td>
                                            <input type="text" id="reqEmail" class="easyui-validatebox textbox form-control" name="reqEmail"  value="<?=$reqEmail ?>" style="width:20%" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Kode Pos</td>
                                        <td>:</td>
                                        <td>
                                            <input type="text" id="reqKodePos" class="easyui-validatebox textbox form-control" name="reqKodePos"  value="<?=$reqKodePos ?>" style="width:20%" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Fax</td>
                                        <td>:</td>
                                        <td>
                                            <input type="text" id="reqFax" class="easyui-validatebox textbox form-control" name="reqFax"  value="<?=$reqFax ?>" style="width:20%" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Nama Kepala</td>
                                        <td>:</td>
                                        <td>
                                            <input type="text" id="reqNamaKepala" class="easyui-validatebox textbox form-control" name="reqNamaKepala"  value="<?=$reqNamaKepala ?>" style="width:80%" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Jabatan Kepala</td>
                                        <td>:</td>
                                        <td>
                                            <input type="text" id="reqJabatanKepala" class="easyui-validatebox textbox form-control" name="reqJabatanKepala"  value="<?=$reqJabatanKepala ?>" style="width:80%" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Hp</td>
                                        <td>:</td>
                                        <td>
                                            <input type="text" id="reqHp" class="easyui-numberbox textbox form-control" name="reqHp"  value="<?=$reqHp ?>" style="width:250px" />
                                        </td>
                                    </tr>
                                </thead>
                            </table>
            
                            <input type="hidden" name="reqId" value="<?=$reqId?>" />
                            <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
                            <div style="text-align:center;padding:5px">
                                <a href="javascript:void(0)" class="btn btn-primary" onclick="submitForm()">Submit</a>
                                <a href="javascript:void(0)" class="btn btn-warning" onclick="clearForm()">Clear</a>
                            </div>
            
                        </form>
                    </div>
                </div>
			</div>
		</div>
	</div>
</body>
</html>


<script>
$('#reqNoTelp, #reqKodePos, #reqFax, #reqHp').bind('keyup paste', function(){
    this.value = this.value.replace(/[^0-9]/g, '');
});

function submitForm(){
    $('#ff').form('submit',{
        url:'web/daftar_alamat_json/add',
        onSubmit:function(){
            return $(this).form('enableValidation').form('validate');
        },
        success:function(data){
			//alert("j");
			top.closePopupAlamat();
            //closePopup()
        }
    });
}
function clearForm(){
    $('#ff').form('clear');
}
            
</script>

