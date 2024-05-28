<?
$reqUnitKerjaId = $this->input->get("reqUnitKerjaId");
$reqJenis = $this->input->get("reqJenis");
$reqJenisSurat = $this->input->get("reqJenisSurat");
$reqIdField = $this->input->get("reqIdField");

if($reqUnitKerjaId == "")
{
	if($reqJenisSurat == "EKSTERNAL"){
		$reqUnitKerjaId = "SBA";
    }
	else{
		$reqUnitKerjaId = $this->CABANG_ID;
    }
	
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
<script type="text/javascript">
    <?
    $valReload = $this->input->get('reqValReload');
    if(empty($valReload)){
        $valReload =0;
    }
    ?>
    var ii=<?=$valReload?>;
    var url = window.location.href;
    setTimeout(function(){
        //alert("reload");
        if(ii==0){
             window.location.href=url+"&reqValReload=1";
        }
  
    }, 100);
</script>
<body class="body-popup">
	
    <div class="container-fluid container-treegrid">
    	
        <div class="row row-treegrid">
        	<div class="col-md-12 col-treegrid">
            	<div class="area-konten-atas">
                	<div class="judul-halaman"> <?=ucwords(strtolower($reqJenis))?> Naskah</div>
                    <div class="area-menu-aksi">    
                        <!--Pencarian : <input type="text" name="reqPencarian" id="reqPencarian">-->
                        <div id="bluemenu" class="aksi-area">
                            <span>Unit Kerja </span>
                            <input type="text" name="reqUnitKerjaId" class="easyui-combobox" id="reqUnitKerjaId" 
                            data-options="
                            width:'400'
                            , editable:false
                            , valueField:'id'
                            , textField:'text'
                            , url:'web/satuan_kerja_json/combo_cabang_alamat/?reqJenisSurat=<?=$reqJenisSurat?>'
                            " 
                            value="<?=$reqUnitKerjaId?>" required />
                            <span>Pencarian </span> 
                            <input type="text" name="reqPencarian" class="easyui-validatebox textbox form-control" id="reqPencarian" style="width:300px"> 
                        </div>
                        
                    </div>
                    
                </div>
                
				<div id="tableContainer" class="tableContainer tableContainer-treegrid">
                	<table id="treeSatker" class="easyui-treegrid" style="min-width:100px !important;height:300px"
                            data-options="
                                url: 'web/satuan_kerja_json/treetable_master/?reqUnitKerjaId=<?=$reqUnitKerjaId?>&reqMode=<?=$reqJenis?>',
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
                                <th data-options="field:'NAMA_PEGAWAI',width:200">Pejabat</th>
                                <th data-options="field:'NIP',width:100">NIP</th>
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

            if(param.id == 0)
            {
                document.location.href = "app/loadUrl/main/daftar_alamat_tujuan_lookup/?reqJenis=<?=$reqJenis?>&reqJenisSurat=<?=$reqJenisSurat?>";	
                return;
            }
            else if(param.id == 1)
            {
                document.location.href = "app/loadUrl/main/kelompok_tujuan_lookup/?reqJenis=<?=$reqJenis?>&reqJenisSurat=<?=$reqJenisSurat?>";	
                return;					
            }

            var urlApp = 'web/satuan_kerja_json/treetable/?reqUnitKerjaId='+param.id+'&reqMode=<?=$reqJenis?>&reqPencarian='+$("#reqPencarian").val();
            $('#treeSatker').treegrid(
            {
                url: urlApp
            });
        }
    });
		
    $('input[name=reqPencarian]').keyup(function() {
        var value = this.value;
        $("html, body").animate({ scrollTop: 0 });


        var urlApp = 'web/satuan_kerja_json/treetable/?reqUnitKerjaId='+$('#reqUnitKerjaId').combobox("getValue")+'&reqMode=<?=$reqJenis?>&reqPencarian='+value;
        $('#treeSatker').treegrid(
        {
            url: urlApp
        }); 
    });
        
    $('#treeSatker').treegrid({
          onDblClickRow: function(param){
             //top.tambahPegawai(param.id, param.text);
			 if(typeof param.JABATAN==='undefined' || param.JABATAN===null) 
			 {
			 	$.messager.alert('Info', "Pejabat belum ditentukan.", 'info');	
				return;
			 }

             var tujuan= infosatuankerjaid= "";
             if(param.JABATAN == "-")
             {
                tujuan= 'Kelompok '+param.NAMA;
                infosatuankerjaid= param.id;
             }
             else
             {
			    tujuan= param.JABATAN + '['+param.SATUAN_KERJA_ID_PARENT+']';
                infosatuankerjaid= param.SATUAN_KERJA_ID;
             }
			 //top.document.getElementById('contentFrame').contentWindow.addSatuanKerja('<?=$reqJenis?>', param.SATUAN_KERJA_ID, tujuan);
			 var  divIdField = "<?=$reqIdField?>";
			 top.addSatuanKerjaPerintah('PERINTAH', infosatuankerjaid, tujuan, divIdField);
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