<?
$reqUnitKerjaId = $this->input->get("reqUnitKerjaId");
$reqJenis = $this->input->get("reqJenis");
$reqJenisSurat = $this->input->get("reqJenisSurat");
$reqIdField = $this->input->get("reqIdField");

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
<script type="text/javascript">
    <?
    $valReload = $this->input->get('reqValReload');
    if(empty($valReload)){
        $valReload =0;
    }
    ?>
    // var ii=<?=$valReload?>;
    // var url = window.location.href;
    // setTimeout(function(){
    //     //alert("reload");
    //     if(ii==0){
    //          window.location.href=url+"?reqValReload=1";
    //     }
  
    // }, 100);
</script>
<body class="body-popup">
	
    <div class="container-fluid container-treegrid">
    	
        <div class="row row-treegrid">
        	<div class="col-md-12 col-treegrid">
            	<div class="area-konten-atas">
                	<div class="judul-halaman"> Direktorat</div>
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
                                
                            ">
                        <thead>
                            <tr>                                
                                <th data-options="field:'NAMA',width:300">Unit Kerja</th>
                                <th data-options="field:'JABATAN',width:300">Jabatan</th>
                                <th data-options="field:'NAMA_PEGAWAI',width:200">Pejabat</th>
                                <th data-options="field:'NIP',width:100">NIP</th>
                                <th field="LINK_URL_PEGAWAI" width="50%" align="center">Aksi</th>
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
        url:'web/satuan_kerja_json/combo_cabang'
        , onSelect: function(param){

            if(param.id == 0)
            {
                document.location.href = "app/loadUrl/main/daftar_alamat_tujuan_lookup/";	
                return;
            }
            else if(param.id == 1)
            {
                document.location.href = "app/loadUrl/main/kelompok_tujuan_lookup/";	
                return;					
            }

            // var urlApp = 'web/satuan_kerja_json/pegawaitreetable/?reqUnitKerjaId='+param.id+'&reqPencarian='+$("#reqPencarian").val();
            var urlApp = "web/satuan_kerja_json/treetable_master/?reqUnitKerjaId="+param.id+"&reqPencarian="+$("#reqPencarian").val();
            $('#treeSatker').treegrid(
            {
                url: urlApp
                , pageSize: 1//, pageList: [10,20,30,40,50]
                // , pageRows: 1
            });
        }
    });
	
    $('input[name=reqPencarian]').keyup(function(e) {
        var value = this.value;
        $("html, body").animate({ scrollTop: 0 });

        if(e.keyCode == 13) {
            // var urlApp = 'web/satuan_kerja_json/pegawaitreetable/?reqUnitKerjaId='+$('#reqUnitKerjaId').combobox("getValue")+'&reqPencarian='+value;
            var urlApp = 'web/satuan_kerja_json/treetable/?reqUnitKerjaId='+$('#reqUnitKerjaId').combobox("getValue")+'&reqPencarian='+value;
            $('#treeSatker').treegrid(
            {
                url: urlApp
            });
        }
    });
    
    // Junior Analis Riset Dan Pengembangan
    $('#treeSatker').treegrid({
        // url: 'web/satuan_kerja_json/pegawaitreetable/?reqUnitKerjaId=<?=$reqUnitKerjaId?>',
        url: 'web/satuan_kerja_json/treetable_master/?reqUnitKerjaId=<?=$reqUnitKerjaId?>',
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
        , onDblClickRow: function(param){
            infoparamnip= param.NIP;
            if(infoparamnip == null) infoparamnip= "";
            infoparamkelompokjabatan= param.KELOMPOK_JABATAN;
            if(infoparamkelompokjabatan == null) infoparamkelompokjabatan= "";

            if( infoparamkelompokjabatan == "" || (infoparamkelompokjabatan !== "" && infoparamnip !== "") )
            {
                $.messager.alert('Info', "Data tidak bisa di pilih.", 'info');
                return false;
            }

            // var infodetil= {};
            // infodetil.infonamacabang= String("asdsad");
            // param.push("asdasd");
            param.infonamacabang= String($('#reqUnitKerjaId').combobox("getText"));
            // console.log(param);return false;

            top.adddetil(param);
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