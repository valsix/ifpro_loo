<?php
include_once("functions/string.func.php");
include_once("functions/date.func.php");

	
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

<script type="text/javascript" src="lib/FlexLevel-DropDown-Menu(v2.0)/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready( function () {
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
		
	}
	else
	{
		$("#flexmenu1").removeAttr("class");	
		$("#flexmenu1").removeAttr("id");	 				  
	}
});
</script>
<!-- FLEX DROPDOWN -->
<link rel="stylesheet" type="text/css" href="lib/FlexLevel-DropDown-Menu(v2.0)/flexdropdown.css" />
<script type="text/javascript" src="lib/FlexLevel-DropDown-Menu(v2.0)/flexdropdown.js">
/***********************************************
* Flex Level Drop Down Menu- (c) Dynamic Drive DHTML code library (www.dynamicdrive.com)
* Please keep this notice intact
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for this script and 100s more
***********************************************/
</script>

<!-- BOOTSTRAP -->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link href="lib/bootstrap/bootstrap.css" rel="stylesheet">
<link rel="stylesheet" href="lib/font-awesome/4.5.0/css/font-awesome.css">

<!--<script src="js/jquery-1.10.2.min.js" type="text/javascript" charset="utf-8"></script> -->
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
<script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script> -->
<script type="text/javascript" src="lib/easyui/globalfunction.js"></script>
 
<script type="text/javascript">

$(document).ready(function() {
	$(document).on("click", "#btnCetak", function(){
	
		alert("Arahkan cursor ke button Cetak (Tanpa di klik) kemudian pilih salah satu sub menu.");
	
	});	
	
	$('#reqUnit').combogrid({
		onSelect: function(index, row){
			var id = row.id;
			document.location.href = 'app/loadUrl/app/konkin_unit/?reqUnit='+ id;
		}
	});
	
	$('#btnCetakExcel').on('click', function () {
		newWindow = window.open('app/loadUrl/app/konkin_unit_cetak_excel/?reqUnit='+$("#reqUnit").combobox('getValue'), 'Cetak');
		newWindow.focus();
	});
	
	$('#btnCetakPdf').on('click', function () {
		newWindow = window.open('app/loadUrl/app/konkin_unit_cetak_pdf/?reqUnit='+$("#reqUnit").combobox('getValue'), 'Cetak');
		newWindow.focus();
	});
		
});

</script>

<!-- EASYUI -->
<link rel="stylesheet" type="text/css" href="lib/jquery-easyui-1.4.5/themes/default/easyui.css">
<link rel="stylesheet" type="text/css" href="lib/jquery-easyui-1.4.5/themes/icon.css">
<script type="text/javascript" src="lib/jquery-easyui-1.4.5/jquery.min.js"></script>
<script type="text/javascript" src="lib/jquery-easyui-1.4.5/jquery.easyui.min.js"></script>
<script type="text/javascript" src="lib/jquery-easyui-1.4.5/datagrid-scrollview.js"></script>

</head>

<body>
	
    <div class="container-fluid">
    	
        <div class="row konten-form">
        	<div class="col-md-12">
            	<div class="area-konten-atas">
                	<div class="judul-halaman">Aplikasi Modul</div>
                    <div id="bluemenu" class="bluetabs" style="background:url(css/media/bluetab.gif)">    
                        <ul>
                            <!--<li>
                                <a id="btnImport" onClick="window.parent.createWindow2('app/loadUrl/app/konkin_unit_import/');"><img src="images/icon-copy.png" /> Import </a>
                            </li>  
                            <li>
                            	<a data-flexmenu="flexmenu2" id="btnCetak"><img src="images/icon-excel.png" /> Cetak</a>
                                <ul id="flexmenu2" class="flexdropdownmenu">
                                    <li><a id="btnCetakExcel" title="Excel">Excel</a></li>
                                    <li><a id="btnCetakPdf" title="Pdf">PDF</a></li>
                                </ul>
                            </li>  -->   
                        </ul>
                    </div>
                </div>
                
				<div id="tableContainer" class="tableContainer">
                    <table id="treeSatker" class="easyui-treegrid" style="width:700px;height:300px"
                            data-options="
                                url: 'aplikasi_modul_json/json/',
                                method: 'get',
                                rownumbers: true,
                                idField: 'ID',
                                treeField: 'NAME'
                            ">
                        <thead>
                                <tr>                                                          
                                    <th data-options="field:'NAME'" width="52%">Nama</th>
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
		// 10px adalah penyesuaian
		$('#tableContainer').css({ 'height': 'calc(100vh - 10px - ' + divTinggi+ 'px)' });
		
		// Menentukan tinggi scrollContent
		// 34px adalah tinggi thead th
		// 50px adalah tinggi tfoot
		$('tbody.scrollContent').css({ 'height': 'calc(100vh - 34px - 50px - ' + divTinggi+ 'px)' });	
	</script>

	<script>

		$(document).ready( function () {
			$('#treeSatker').treegrid({
				  onClickRow: function(param){
						
					  $("#bluemenu ul li[data-li-id='ENTRI']").remove();

					  if(param.GROUP == 'MODUL')
					  {
						  $("#bluemenu ul").prepend('<li data-li-id="ENTRI">' +
												   '<a id="btnTambahIndikator" onClick="window.parent.createWindow2(\'app/loadUrl/app/konkin_unit_add/?reqParentId=0&reqUnit=<?=$reqUnit?>&reqModulKonkinUnitId='+param.MODUL_KONKIN_UNIT_ID+'\');" title="Tambah Aplikasi Modul"><img src="images/icon-tambah.png"> Tambah Aplikasi Modul</a>' + 
												   '</li>');		   
					  }
					  else if(param.GROUP == 'INDIKATOR')
					  {
						  var arrId = id.split("-");
						  var reqModulId = arrId[0];
						  var reqId = arrId[1];
										  
						  $("#bluemenu ul").prepend('<li data-li-id="ENTRI">' +
												   '<a onClick="window.parent.createWindow2(\'app/loadUrl/app/konkin_unit_add/?reqUnit=<?=$reqUnit?>&reqId='+param.KONKIN_UNIT_ID+'\');" title="Ubah Indikator"><img src="images/icon-edit.png"> Ubah Indikator</a>' + 
												   '<a onClick="deleteData(\'konkin_unit_json/delete/\', \''+param.KONKIN_UNIT_ID+'\');" title="Hapus Indikator"><img src="images/icon-hapus.png"> Hapus Indikator</a>' + 
												   '</li>');	
		  
						  $("#bluemenu ul").prepend('<li data-li-id="ENTRI"><a onClick="window.parent.createWindow2(\'app/loadUrl/app/konkin_unit_add/?reqUnit=<?=$reqUnit?>&reqParentId='+param.KONKIN_UNIT_ID+'&reqModulKonkinUnitId='+param.MODUL_KONKIN_UNIT_ID+'\');"><img src="images/icon-tambah.png" /> Tambah Sub Indikator</a></li>');		   
		  
											
					  }			
					  else if(param.GROUP == 'SUB_INDIKATOR')
					  {
		  
						  $("#bluemenu ul").prepend('<li data-li-id="ENTRI">' +
												   '<a onClick="window.parent.createWindow2(\'app/loadUrl/app/konkin_unit_add/?reqUnit=<?=$reqUnit?>&reqId='+param.KONKIN_UNIT_ID+'\');" title="Ubah Indikator"><img src="images/icon-edit.png"> Ubah Indikator</a>' + 
												   '<a onClick="deleteData(\'konkin_unit_json/delete/\', \''+param.KONKIN_UNIT_ID+'\');" title="Hapus Indikator"><img src="images/icon-hapus.png"> Hapus Indikator</a>' + 
												   '</li>');	
											
					  }
							
				  }
			});
		});
    
	</script>   
     
</body>
</html>
