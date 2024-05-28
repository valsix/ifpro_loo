<?
$reqId = $this->input->get("reqId");
$reqTipe = $this->input->get("reqTipe");

$nama= 'Outdoor';
if ($reqTipe=='I') 
{
    $nama= 'Indoor';
}

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<base href="<?=base_url();?>" />

<link rel="stylesheet" href="css/gaya.css" type="text/css">
<link rel="stylesheet" href="css/gaya-bootstrap.css" type="text/css">
 <script src="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/js/bootstrap.js"></script>

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
  
    }, 500);
</script>
<body class="body-popup">
	
    <div class="container-fluid container-treegrid">
    	
        <div class="row row-treegrid">
        	<div class="col-md-12 col-treegrid">
            	<div class="area-konten-atas">
                	<div class="judul-halaman">Lokasi <?=$nama?></div>
                    
                    <div class="area-menu-aksi">    
                        Pencarian : <input type="text" name="reqPencarian" id="reqPencarian">
                    </div>
                    
                </div>
                
				<div id="tableContainer" class="tableContainer tableContainer-treegrid">
                	<table id="treeSatker" class="easyui-treegrid" style="min-width:100px !important;height:300px"
                            data-options="
                                url: 'web/lokasi_loo_detil_json/combo/?reqId=<?=$reqId?>&reqTipe=<?=$reqTipe?>',
                                pagination: true,            
                                pageSize: 50,
								pageList: [50, 100],
                                method: 'get',
                                idField: 'id',
                                treeField: 'text',
                                fitColumns: true,
                                onBeforeLoad: function(row,param){
                                    if (!row) {    // load top level rows
                                        param.id = 0;    // set id=0, indicate to load new page rows
                                    }
                                }
                            ">
                        <thead>
                            <tr>
                                <th data-options="field:'NAMA_LOKASI_LOO',width:100">Nama Lokasi</th>
                                <th data-options="field:'text',width:100">Nama Detil Lokasi</th>
                                <th data-options="field:'KODE',width:100">Kode Detil Lokasi</th>
                                <th data-options="field:'LANTAI',width:300">Lantai</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                
            </div>
        </div>        
    </div>
    
	<script>
 $(document).ready( function () {
        $('input[name=reqPencarian]').keyup(function() {
            var value = this.value;
            $("html, body").animate({ scrollTop: 0 });
    
            var urlApp = 'web/lokasi_loo_detil_json/combo/?reqId=<?=$reqId?>&reqTipe=<?=$reqTipe?>&reqPencarian='+ value;
            $('#treeSatker').treegrid(
            {
                url: urlApp
            }); 
        });

        tipesss= '<?=$reqTipe?>';

        $('#treeSatker').treegrid({
              onDblClickRow: function(param){
				  // console.log(param.text);
                 // parent.tambahPegawaiPengganti(param.id, param.text);
                 top.appenddata(param.id, param.text, param.NAMA_LOKASI_LOO, param.KODE, param.LANTAI, tipesss);
				
                 $('#treeSatker').treegrid('deleteRow', param.id);
				 parent.closePopup();
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
