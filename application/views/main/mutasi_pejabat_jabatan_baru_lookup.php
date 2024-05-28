<?
$reqUnitKerjaId = $this->input->get("reqUnitKerjaId");
$reqTipe = $this->input->get("reqTipe");
$reqMode = $this->input->get("reqMode");
$reqJabatanJenis = $this->input->get("reqJabatanJenis");
$reqTipeInfo= infotipemutasi($reqTipe);

if($reqUnitKerjaId == "")
{
  $reqUnitKerjaId = $this->CABANG_ID;
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<base href="<?=base_url();?>" />
 <script src="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/js/bootstrap.js"></script>

<link rel="stylesheet" href="css/gaya.css" type="text/css">
<link rel="stylesheet" href="css/gaya-bootstrap.css" type="text/css">

<!-- BOOTSTRAP -->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link href="lib/bootstrap/bootstrap.css" rel="stylesheet">
<link rel="stylesheet" href="lib/font-awesome/4.5.0/css/font-awesome.css">

<script src="js/jquery-1.11.1.js" type="text/javascript" charset="utf-8"></script> 

    <style>
	.col-md-12{
		padding-left:0px;
		padding-right:0px;
	}
	</style>

<!-- EASYUI -->
<link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript" src="lib/easyui/globalfunction.js"></script>

<!-- FONT AWESOME -->
<link rel="stylesheet" href="lib/font-awesome-4.7.0/css/font-awesome.css" type="text/css">
    


</head>
<body class="body-popup">
	
    <div class="container-fluid container-treegrid">
    	
        <div class="row row-treegrid">
        	<div class="col-md-12 col-treegrid">
            	<div class="area-konten-atas">
                	<div class="judul-halaman"> Jabatan Baru (<?=$reqTipeInfo?>)</div>
                    <div class="area-menu-aksi">    
                        <div id="bluemenu" class="aksi-area">
                            <span>Unit Kerja </span>
                            <input type="text" name="reqUnitKerjaId" class="easyui-combobox" id="reqUnitKerjaId" 
                            data-options="
                            width:'400'
                            , editable:false
                            , valueField:'id'
                            , textField:'text'
                            , url:'web/mutasi_pejabat_json/combo_cabang_alamat/?reqJenisSurat=<?=$reqJenisSurat?>'
                            " 
                            value="<?=$reqUnitKerjaId?>" required />
                            <?
                            if($reqTipe == "2" && $reqMode == "jabatan_baru_cari")
                            {
                            ?>
                            <span>Jenis </span>
                            <input type="text" name="reqJabatanJenis" class="easyui-combobox" id="reqJabatanJenis" 
                            data-options="
                            width:'200'
                            , editable:false
                            , valueField:'id'
                            , textField:'text'
                            , url:'web/mutasi_pejabat_json/combojabatan/?reqMode=baru'
                            " 
                            value="<?=$reqJabatanJenis?>" 
                            />
                            <?
                            }
                            else if($reqTipe == "2" && $reqMode == "jabatan_baru")
                            {
                            ?>
                            <span>Jenis </span>
                            <input type="text" name="reqJabatanJenis" class="easyui-combobox" id="reqJabatanJenis" 
                            data-options="
                            width:'200'
                            , editable:false
                            , valueField:'id'
                            , textField:'text'
                            , url:'web/mutasi_pejabat_json/combojabatan/'
                            " 
                            value="<?=$reqJabatanJenis?>" 
                            />
                            <?
                            }
                            ?>

                            <span>Pencarian </span> 
                            <input type="text" name="reqPencarian" class="easyui-validatebox textbox form-control" id="reqPencarian" style="width:300px"> 
                        </div>
                        
                    </div>
                    
                </div>
                
				<div id="tableContainer" class="tableContainer tableContainer-treegrid">
                	<table id="treeSatker" class="easyui-treegrid" style="min-width:100px !important;height:300px"
                            data-options="
                                url: 'web/mutasi_pejabat_json/treetable_master/?reqUnitKerjaId=<?=$reqUnitKerjaId?>&reqMode=<?=$reqMode?>&reqTipe=<?=$reqTipe?>',
                                pagination: true, 
                                method: 'get',
                                idField: 'id',
                                treeField: 'NAMA',
                                fitColumns: true,
                                onBeforeLoad: function(row,param){
                                    if (!row) {    // load top level rows
                                        param.id = 0;    // set id=0, indicate to load new page rows
                                    }
                                }
                            ">
                        <thead>
                            <tr>                                
                                <th data-options="field:'NAMA',width:300">Unit Kerja</th>
                                <th data-options="field:'JABATAN',width:300">Jabatan</th>
                                <?
                                if($reqTipe == "2" && ($reqMode == "xxxjabatan_baru" || $reqMode == "jabatan_baru_cari")){}
                                else
                                {
                                ?>
                                <th data-options="field:'NAMA_PEGAWAI',width:200">Pejabat</th>
                                <th data-options="field:'NIP',width:100">NIP</th>
                                <?
                                }
                                ?>
                            </tr>
                        </thead>
                    </table>
                </div>
                
            </div>
        </div>        
    </div>
    
<script>
$(document).ready( function () {
	 
    $("#reqPencarian").focus();
    $('#reqUnitKerjaId').combobox({
        onSelect: function(param){
            <?
            // if($reqTipe == "2" && $reqMode == "jabatan_baru_cari")
            if($reqTipe == "2")
            {
            ?>
                var urlApp = 'web/mutasi_pejabat_json/treetable/?reqUnitKerjaId='+param.id+'&reqJabatanJenis='+$('#reqJabatanJenis').combobox("getValue")+'&reqMode=<?=$reqMode?>&reqPencarian='+$("#reqPencarian").val();
            <?
            }
            else
            {
            ?>
                var urlApp = 'web/mutasi_pejabat_json/treetable/?reqUnitKerjaId='+param.id+'&reqMode=<?=$reqMode?>&reqPencarian='+$("#reqPencarian").val();
            <?
            }
            ?>
            $('#treeSatker').treegrid(
            {
                url: urlApp
            });
        }
    });

    <?
    if($reqTipe == "2")
    {
    ?>
    $('#reqJabatanJenis').combobox({
        onSelect: function(param){
            var urlApp = 'web/mutasi_pejabat_json/treetable/?reqUnitKerjaId='+$('#reqUnitKerjaId').combobox("getValue")+'&reqJabatanJenis='+param.id+'&reqMode=<?=$reqMode?>&reqPencarian='+$("#reqPencarian").val();
            $('#treeSatker').treegrid(
            {
                url: urlApp
            });
        }
    });
    <?
    }
    ?>
		
    $('input[name=reqPencarian]').keyup(function() {
        var value = this.value;
        $("html, body").animate({ scrollTop: 0 });

        <?
        // if($reqTipe == "2" && $reqMode == "jabatan_baru_cari")
        if($reqTipe == "2")
        {
        ?>
            var urlApp = 'web/mutasi_pejabat_json/treetable/?reqUnitKerjaId='+$('#reqUnitKerjaId').combobox("getValue")+'&reqJabatanJenis='+$('#reqJabatanJenis').combobox("getValue")+'&reqMode=<?=$reqMode?>&reqPencarian='+value;
        <?
        }
        else
        {
        ?>
            var urlApp = 'web/mutasi_pejabat_json/treetable/?reqUnitKerjaId='+$('#reqUnitKerjaId').combobox("getValue")+'&reqMode=<?=$reqMode?>&reqPencarian='+value;
        <?
        }
        ?>
        $('#treeSatker').treegrid(
        {
            url: urlApp
        }); 
    });
        
    $('#treeSatker').treegrid({
        onDblClickRow: function(param){
            // top.tambahPegawai(param.id, param.text);
            <?
            if($reqTipe == "2" && $reqMode == "jabatan_baru_cari")
            {
            ?>
                if(param.CHECK_ADA_PEJABAT == 1)
                    top.jabatanbuatbaru(param.SATUAN_KERJA_ID, param.NAMA, param.NIP, param.KELOMPOK_JABATAN);
                else
                    top.jabatanbuatbaru(param.SATUAN_KERJA_ID, param.NAMA, '', param.KELOMPOK_JABATAN);
            <?
            }
            else
            {
            ?>
                if(typeof param.JABATAN==='undefined' || param.JABATAN===null) 
                {
                    $.messager.alert('Info', "Pejabat belum ditentukan.", 'info');  
                    return;
                }

                // if(typeof param.KELOMPOK_JABATAN==='undefined' || param.KELOMPOK_JABATAN===null  || param.KELOMPOK_JABATAN=== "") 
                // {
                //     $.messager.alert('Info', "Pilih Unit kerja yg kosong.", 'info');  
                //     return;
                // }

                if(param.CHECK_ADA_PEJABAT == 1)
                    top.jabatanBaru(param.SATUAN_KERJA_ID, param.NAMA, param.NIP, param.NAMA_PEGAWAI, param.MUTASI_JABATAN, param.KELOMPOK_JABATAN, param.MUTASI_AKSI_PEJABAT_PENGGANTI);
                else
                    top.jabatanBaru(param.SATUAN_KERJA_ID, '', '', '', param.MUTASI_JABATAN, param.KELOMPOK_JABATAN, param.MUTASI_AKSI_PEJABAT_PENGGANTI);
            <?
            }
            ?>
                
            $('#treeSatker').treegrid('deleteRow', param.id);
            top.closePopup();
        }
    });

});

$("#dnd-example tr").click(function(){
    $(this).addClass('selected').siblings().removeClass('selected');
    var id = $(this).find('td:first').attr('id');
    var title = $(this).find('td:first').attr('title');
}); 
</script>
    
<script>
	// Mendapatkan tinggi .area-konten-atas
	var divTinggi = $(".area-konten-atas").height();
	//alert(divTinggi);
	
	// Menentukan tinggi tableContainer
	$('#tableContainer').css({ 'height': 'calc(100% - ' + divTinggi+ 'px)' });
</script>
</body>
</html>