<?php
include_once("functions/string.func.php");
include_once("functions/date.func.php");
$this->load->model("JenisPermintaan");

$sasaran = new JenisPermintaan();

/* LOGIN CHECK */

$tinggi = 162;

$sasaran->selectByParams(array("JENIS_PERMINTAAN_PARENT_ID" => "0"));

function getChild($method, $parentId)
{
	$child = new JenisPermintaan();
	$child->selectByParams(array("JENIS_PERMINTAAN_PARENT_ID" => $parentId));
	while($child->nextRow())
	{
	?>
		<tr id='node--<?=$child->getField("JENIS_PERMINTAAN_ID")?>' class='child-of-node--<?=$parentId?>'>
			<td id="<?=$child->getField("JENIS_PERMINTAAN_ID")?>" title="SASARAN"><?=$child->getField("NAMA")?></td>
		</tr>
		 <?=getChild($method, $child->getField("JENIS_PERMINTAAN_ID"))?>
	<?		
	}										
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
<!--<link href="css/gaya-treetable.css" rel="stylesheet" type="text/css" />-->
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
<!--<link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">-->
<!--<script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>  -->
<script type="text/javascript" src="lib/easyui/globalfunction.js"></script>

<script type="text/javascript">

function addGroup()
{
	$.messager.confirm('Konfirmasi',"Tambah group baru ?",function(r){
		if (r){
			var jqxhr = $.get("sasaran_json/addParent", function(data) {
				//$.messager.alert('Info', data, 'info');	
				document.location.reload();
			})
			.fail(function() {
				alert( "error" );
			});								
							
		}
	});	
};
	  
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
	
    <div class="container-fluid container-treegrid">
    	
        <div class="row row-treegrid">
        	<div class="col-md-12 col-treegrid">
            	<div class="area-konten-atas">
                	<div class="judul-halaman">Jenis Peminatan</div>
                    <div id="bluemenu" class="bluetabs" style="background:url(css/media/bluetab.gif)">    
                        <ul>
                            <li>
                                <a onClick="addGroup()" title="Tambah"><img src="images/icon-tambah.png" /> Tambah Group</a>
                            </li>        
                        </ul>
                    </div>
                </div>
                
				<div id="tableContainer" class="tableContainer tableContainer-treegrid">
                	<table id="treeSatker" class="easyui-treegrid" style="width:700px;height:300px"
                            data-options="
                                url: 'jenis_permintaan_json/json',
                                method: 'get',
                                idField: 'id',
                                treeField: 'name'
                            ">
                        <thead>
                            <tr>
                                <th data-options="field:'name'" width="100%">Nama</th>
                            </tr>
                        </thead>
                    </table>
                	<!--<table id="treeSatker" class="easyui-treegrid" style="width:750px; height:100%;"-->
                    <?php /*?><table id="treeSatker" class="easyui-treegrid"
                            data-options="
                                url: 'sasaran_json/json',
                                method: 'get',
                                rownumbers: true,
                                idField: 'id',
                                treeField: 'name'
                            ">
                        <thead>
                                <tr>
                                    <!--<th data-options="field:'name',width:250">Persons</th>
                                    <th data-options="field:'id',width:100">Task Name</th>-->
                                    
                                    <th data-options="field:'name'" width="50%">Persons</th>
                                    <th data-options="field:'id'" width="50%">Task Name</th>
                                    
                                    
                                </tr>
                        </thead>
                    </table><?php */?>
                </div>
                
            </div>
        </div>        
    </div>
    
	<script>

    $(document).ready( function () {
        $('#treeSatker').treegrid({
              onClickRow: function(param){
				  
				  $("#bluemenu ul li").remove();
				  
				  if(param.group == 'GROUP')
				  {
	  
					  $("#bluemenu ul").append('<li>' +
											   '<a onClick="addGroup()" title="Tambah"><img src="images/icon-tambah.png" /> Tambah Group</a>' + 
											   '<a onClick="deleteData(\'sasaran_json/delete/\', \''+param.id+'\');" title="Hapus Group"><img src="images/icon-hapus.png"> Hapus Group</a>' + 
											   '</li>');			   
					  
					  $("#bluemenu ul").append('<li>' +
											   '<a id="btnTambahSasaran" onClick="window.parent.createWindow2(\'app/loadUrl/app/sasaran_add/?reqParentId='+param.id+'\');" title="Tambah Sasaran"><img src="images/icon-tambah-sasaran.png"> Tambah Sasaran</a>' + 
											   '</li>');		   
				  }
				  else if(param.group == 'SASARAN')
				  {
					  $("#bluemenu ul").append('<li><a onClick="addGroup()" title="Tambah"><img src="images/icon-tambah.png" /> Tambah Group</a>');		   
	  
					  $("#bluemenu ul").append('<li>' +
											   '<a onClick="window.parent.createWindow2(\'app/loadUrl/app/sasaran_add/?reqId='+param.id+'\');" title="Ubah Sasaran"><img src="images/icon-edit.png"> Ubah Sasaran</a>' + 
											   '<a onClick="deleteData(\'sasaran_json/delete/\', \''+param.id+'\');" title="Hapus Sasaran"><img src="images/icon-hapus.png"> Hapus Sasaran</a>' + 
											   '</li>');	
										
				  }
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
