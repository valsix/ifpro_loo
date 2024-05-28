<?
$reqJenis = $this->input->get("reqJenis");
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<base href="<?=base_url();?>" />

<link rel="stylesheet" href="css/gaya.css" type="text/css">
<link rel="stylesheet" href="css/gaya-bootstrap.css" type="text/css">

<!-- BOOTSTRAP -->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link href="lib/bootstrap/bootstrap.css" rel="stylesheet">
<link rel="stylesheet" href="lib/font-awesome-4.7.0/css/font-awesome.css">

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

<style>
.datagrid-cell-c2-text {
    width: auto !important;
}
.datagrid-cell-c2-NAMA{
    *width: 100% !important;
    *border: 1px solid red;
}

.datagrid-cell-c2-text {
    width: auto !important;
}

.panel {
    width: 100% !important;
}

.datagrid {
    width: 100% !important;
}

.datagrid-view {
    width: 100% !important;
}
</style>
    
</head>

<body class="body-popup">
	
    <div class="container-fluid container-treegrid">
    	
        <div class="row row-treegrid">
        	<div class="col-md-12 col-treegrid">
            	<div class="area-konten-atas">
                	<div class="judul-halaman"> Berkas</div>
                    
                    <div class="area-menu-aksi">    
                        <!--Pencarian : <input type="text" name="reqPencarian" id="reqPencarian">-->
                        <div id="bluemenu" class="aksi-area">
                            <span style="float: left;">Pencarian&nbsp;&nbsp;</span> 
                            <input type="text" name="reqPencarian" class="easyui-validatebox textbox form-control" id="reqPencarian" style="width:300px;float: left;"> 
                            <button type="button" class="btn btn-primary" id="btnAdd" style="float: right; margin-bottom: 5px;">
                                <i class="fa fa-plus-square fa-sm" aria-hidden="true"></i> Tambah
                            </button> 
                        </div>
                        
                    </div>
                    
                </div>
                
				<div id="tableContainer" class="tableContainer tableContainer-treegrid">
                	<table id="treeData" class="easyui-treegrid" style="width:300px !important;height:300px"
                            data-options="
                                url: 'web/arsip_json/combo/',
                                pagination: true, 
                                method: 'get',
                                idField: 'id',
                                treeField: 'KLASIFIKASI',
                                
                                onBeforeLoad: function(row,param){
                                    if (!row) {    // load top level rows
                                        param.id = 0;    // set id=0, indicate to load new page rows
                                    }
                                }
                            ">
                        <thead>
                            <tr>                             
                                <th data-options="field:'KLASIFIKASI'" >Klasifikasi</th>
                                <th data-options="field:'BERKAS'" >Berkas</th>
                                <th data-options="field:'KETERANGAN'" >Keterangan</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                
            </div>
        </div>        
    </div>
    
	<script>
	
	function downloadTemplate()
	{
		newWindow = window.open('web/satuan_kerja_json/excel/?reqUnitKerjaId=' + $('#reqUnitKerjaId').combobox('getValue'), 'Cetak');
		newWindow.focus();
			
			
	}
	
 $(document).ready( function () {
	 
		
		
        $('input[name=reqPencarian]').keyup(function() {
            var value = this.value;
            $("html, body").animate({ scrollTop: 0 });
    		
			if(value.length < 3)
				return;
				
            var urlApp = 'web/arsip_json/combo/?reqPencarian='+value;
            $('#treeData').treegrid(
            {
                url: urlApp
            }); 
        });
        
        $('#treeData').treegrid({
              onDblClickRow: function(param){
                 //top.tambahPegawai(param.id, param.text);	
				
                 // top.document.getElementById('contentFrame').contentWindow.addBerkas(param.ARSIP_ID, param.BERKAS);
				 parent.addBerkas(param.ARSIP_ID, param.BERKAS);
                 $('#treeData').treegrid('deleteRow', param.id);
				 top.closePopup();
              }
        });
    });
        
    $("#dnd-example tr").click(function(){
       $(this).addClass('selected').siblings().removeClass('selected');
       var id = $(this).find('td:first').attr('id');
       var title = $(this).find('td:first').attr('title');

        
    });
    
    $('#btnAdd').on('click', function () {
        document.location.href = "app/loadUrl/main/berkas_lookup_add";        
    });
    
	</script>
    
    <script>
		// Mendapatkan tinggi .area-konten-atas
		var divTinggi = $(".area-konten-atas").height();
		//alert(divTinggi);
		
		// Menentukan tinggi tableContainer
		$('#tableContainer').css({ 'height': 'calc(100% - ' + divTinggi+ 'px)' });
	</script>
    
    <script>
	var $element=$(window),lastWidth=$element.width(),lastHeight=$element.height();	
	function checkForChanges(){			
	   if ($element.width()!=lastWidth||$element.height()!=lastHeight){	
		$('#tableContainer').panel('resize');
		$('#tableContainer').datagrid('resize'); 
		lastWidth = $element.width();lastHeight=$element.height();	 
	   }
	   setTimeout(checkForChanges, 500);
	}
	checkForChanges();
	</script>

</body>
</html>
