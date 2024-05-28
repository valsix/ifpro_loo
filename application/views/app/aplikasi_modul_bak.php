<?php
include_once("functions/string.func.php");
include_once("functions/date.func.php");
$this->load->model("Aplikasi");

$aplikasi = new Aplikasi();
$aplikasi_checkbox = new Aplikasi();

/* LOGIN CHECK */

$tinggi = 162;


$aplikasi_checkbox->selectByParams(array("TAHUN" => $this->TAHUN_TERPILIH), -1, -1, " AND NOT SASARAN_PARENT_ID = '0' ");
$i=0;
while($aplikasi_checkbox->nextRow())
{
	$arrAplikasiCheckbox[$i]["SASARAN_ID"] = $aplikasi_checkbox->getField("SASARAN_ID");
	$arrAplikasiCheckbox[$i]["NAMA"] = $aplikasi_checkbox->getField("NAMA");
	$i++;
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Dashboard - PJB Services</title>
<base href="<?=base_url()?>" />

<link href="css/admin.css" rel="stylesheet" type="text/css">
<link href="css/bluetabs.css" rel="stylesheet" type="text/css" />
<link href="css/gaya-treegrid.css" rel="stylesheet" type="text/css" />

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

    
<script type='text/javascript' src="lib/bootstrap/bootstrap.js"></script> 
<script type='text/javascript' src="lib/bootstrap/angular.js"></script> 

<!-- EASYUI -->
<!--<link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>  -->
<script type="text/javascript" src="lib/easyui/globalfunction.js"></script>

<script type="text/javascript">

function setAplikasi(ck, reqAplikasiId, reqModulTspiId)
{
	var win = $.messager.progress({
				  title:'Proses Update',
				  msg:'Updating data...'
			  });
											
	if(ck.checked)
	{
		var jqxhr = $.get("modul_tspi_sasaran_json/add/?reqMode=insert&reqAplikasiId="+reqAplikasiId+"&reqModulTspiId="+reqModulTspiId, function(data) {
			//$.messager.alert('Info', data, 'info');	
			$.messager.progress('close');
		})
		.fail(function() {
			$.messager.progress('close');
			//alert( "error" );
		});	
	}
	else
	{
		var jqxhr = $.get("modul_tspi_sasaran_json/add/?reqMode=delete&reqAplikasiId="+reqAplikasiId+"&reqModulTspiId="+reqModulTspiId, function(data) {
			//$.messager.alert('Info', data, 'info');	
			$.messager.progress('close');
		})
		.fail(function() {
			$.messager.progress('close');
			//alert( "error" );
		});	
	}
}
</script>

<!-- EASYUI -->
<link rel="stylesheet" type="text/css" href="lib/jquery-easyui-1.4.5/themes/default/easyui.css">
<link rel="stylesheet" type="text/css" href="lib/jquery-easyui-1.4.5/themes/icon.css">
<!--<link rel="stylesheet" type="text/css" href="lib/jquery-easyui-1.4.5/demo/demo.css">-->
<script type="text/javascript" src="lib/jquery-easyui-1.4.5/jquery.min.js"></script>
<script type="text/javascript" src="lib/jquery-easyui-1.4.5/jquery.easyui.min.js"></script>
<script type="text/javascript" src="lib/jquery-easyui-1.4.5/datagrid-scrollview.js"></script>

  
</head>

<body>
	
    <div class="container-fluid">
    	
        <div class="row">
        	<div class="col-md-12">
            	<div class="area-konten-atas">
                	<div class="judul-halaman">Aplikasi Modul</div>
                    <div id="bluemenu" class="bluetabs" style="background:url(css/media/bluetab.gif)">    
                        <ul>
                            <li>
                                <a id="btnAdd" title="Tambah">&nbsp;</a>
                            </li>        
                        </ul>
                    </div>
                    
                    
                </div>
                
				<div id="tableContainer" class="tableContainer">
                	
                    <table id="treeSatker" class="easyui-treegrid" style="width:700px;height:300px"
                            data-options="
                            	url: 'aplikasi_modul_json/json',
                                method: 'get',
                                rownumbers: true,
                                idField: 'ID',
                                treeField: 'NAME'
                            ">
                        <thead>
                                <tr>
                                    <th data-options="field:'NAME'" width="55%">Nama</th>
									
									<?
                                    for($i=0;$i<count($arrAplikasiCheckbox);$i++)
                                    {
                                    ?>
                                    <th data-options="field:'CHECKBOX<?=$i?>',align:'center'" width="15%"><?=$arrAplikasiCheckbox[$i]["NAMA"]?></th>
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
    <!-- /.container -->    
    
    <script>
		// Mendapatkan tinggi .area-konten-atas
		var divTinggi = $(".area-konten-atas").height();
		//alert(divTinggi);
		
		// Menentukan tinggi tableContainer
		$('#tableContainer').css({ 'height': 'calc(100vh - ' + divTinggi+ 'px)' });
		
		// Menentukan tinggi scrollContent
		// 34px adalah tinggi thead th
		// 32px adalah tinggi tfoot
		$('tbody.scrollContent').css({ 'height': 'calc(100vh - 34px - 32px - ' + divTinggi+ 'px)' });	
	</script>
    
	<script>

		$(document).ready( function () {
			$('#treeSatker').treegrid({
				  onClickRow: function(param){
						
						$("#bluemenu ul li").remove();
						
						if(param.GROUP == 'SASARAN')
							$("#bluemenu ul").append('<li><a onClick="window.parent.createWindow2(\'app/loadUrl/app/modul_tspi_add/?reqParentId=0&reqAplikasiId='+param.SASARAN_ID+'\');" title="Tambah Kategori"><img src="images/icon-tambah.png"> Tambah Kategori</a></li>');		   
						else if(param.GROUP == 'KATEGORI')
						{
							$("#bluemenu ul").append('<li><a onClick="window.parent.createWindow2(\'app/loadUrl/app/modul_tspi_add/?reqParentId='+param.MODUL_TSPI_ID+'&reqAplikasiId='+param.SASARAN_ID+'\');" title="Tambah Modul"><img src="images/icon-tambah.png"> Tambah Modul</a></li>');		   
			
							$("#bluemenu ul").append('<li>' +
													 '<a onClick="window.parent.createWindow2(\'app/loadUrl/app/modul_tspi_add/?reqId='+param.MODUL_TSPI_ID+'\');" title="Ubah Kategori"><img src="images/icon-edit.png"> Ubah Kategori</a>' + 
													 '<a onClick="deleteData(\'modul_tspi_json/delete/\', \''+param.MODUL_TSPI_ID+'\');" title="Hapus Kategori"><img src="images/icon-hapus.png"> Hapus Kategori</a>' + 
													 '</li>');		   
						}
						else if(param.GROUP == 'MODUL_TSPI')
						{			
							$("#bluemenu ul").append('<li>' +
													 '<a onClick="window.parent.createWindow2(\'app/loadUrl/app/modul_tspi_add/?reqParentId='+param.MODUL_TSPI_PARENT_ID+'&reqId='+param.MODUL_TSPI_ID+'\');" title="Ubah Modul"><img src="images/icon-edit.png"> Ubah Modul</a>' + 
													 '<a onClick="deleteData(\'modul_tspi_json/delete/\', \''+param.MODUL_TSPI_ID+'\');" title="Hapus Modul"><img src="images/icon-hapus.png"> Hapus Modul</a>' + 
													 '</li>');		   							
						}

				  }
			});
		});
	    
	</script>

</body>
</html>
