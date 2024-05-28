<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    
    <meta name="description" content="">
    <meta name="author" content="">
    <!--<link rel="icon" href="lib/bootstrap-3.3.7/docs/favicon.ico">-->

    <title>TNDE | PT Angkasa Pura I (Persero)</title>
	<base href="<?=base_url();?>" />

    <!-- Bootstrap core CSS -->
    <link href="lib/bootstrap-3.3.7/docs/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="lib/bootstrap-3.3.7/docs/assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="lib/bootstrap-3.3.7/docs/examples/sticky-footer-navbar/sticky-footer-navbar.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="lib/bootstrap-3.3.7/docs/assets/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <link rel="stylesheet" href="css/halaman.css" type="text/css">
    <link rel="stylesheet" href="css/gaya-egateway.css" type="text/css">
    <link rel="stylesheet" href="css/gaya-affix.css" type="text/css">
    <link rel="stylesheet" href="css/gaya-pagination.css" type="text/css">
    <link rel="stylesheet" href="css/gaya-datatable-egateway.css" type="text/css">
    <link rel="stylesheet" href="lib/font-awesome-4.7.0/css/font-awesome.css">
    
    <script type='text/javascript' src="lib/bootstrap/js/jquery-1.12.4.min.js"></script>
    
    <link href='css/pagination.css' rel='stylesheet' type='text/css'>
    
    <!-- DATATABLE -->
    <link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.7/extensions/Responsive/css/dataTables.responsive.css">
    <link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.7/examples/resources/syntax/shCore.css">
    <link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.7/examples/resources/demo.css">
    
    <script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/media/js/jquery.js"></script>
    <script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/media/js/jquery.dataTables.js"></script>
    <script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/media/js/fnReloadAjax.js"></script>
    <script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/extensions/Responsive/js/dataTables.responsive.js"></script>
    <script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/examples/resources/syntax/shCore.js"></script>
    <script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/examples/resources/demo.js"></script>	       
        
    <!-- PAGINATION -->
    <link rel="stylesheet" type="text/css" href="lib/drupal-pagination/pagination.css" />
    
    <!-- tiny MCE -->
    <script src="lib/tinyMCE/tinymce.min.js"></script>
    
    <script type="text/javascript">
    tinymce.init({
        selector: "textarea",
        plugins: [
        	"advlist autolink lists link image charmap print preview anchor",
        	"searchreplace visualblocks code fullscreen",
        	"insertdatetime media table contextmenu paste"
        ],
        toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
        menubar: true,
    
    });
    </script>
    
  </head>

  <body>
    
    <!-- Begin page content -->
    <div class="container-fluid">
    	<div class="row" style="position: relative;">
        
        <!------------ atas -------------->
<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model("Arsip");
$arsip = new Arsip();

$reqId = $this->input->get("reqId");

if($reqId == ""){
    $reqMode = "insert";
}
else
{
	$reqMode = "update";
	$arsip->selectByParams(array("A.ARSIP_ID" => $reqId, "A.SATUAN_KERJA_ID" => $this->SATUAN_KERJA_ID_ASAL));
	$arsip->firstRow();

    $reqId            	    = $arsip->getField("ARSIP_ID");
    $reqKlasifikasiId       = $arsip->getField("KLASIFIKASI_ID");
    $reqKlasifikasiKode       = $arsip->getField("KLASIFIKASI_KODE");
	
    $reqPenyusutanAkhirId   = $arsip->getField("PENYUSUTAN_AKHIR_ID");
    $reqLokasiArsipId       = $arsip->getField("LOKASI_ARSIP_ID");
	$reqNama                = $arsip->getField("NAMA");
    $reqKode                = $arsip->getField("KODE");
    $reqKeterangan          = $arsip->getField("KETERANGAN");
    $reqRetensiAktif        = $arsip->getField("RETENSI_AKTIF");
    $reqRetensiInaktif      = $arsip->getField("RETENSI_INAKTIF");
}
?>

<link rel="stylesheet" href="css/gaya-multifile.css" type="text/css">

<!--// plugin-specific resources //--> 
<script src='lib/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script> 
<script src='lib/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script> 
<script src='lib/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>

<script>
    function submitForm(){
        
        $('#ff').form('submit',{
            url:'web/arsip_json/add',
            onSubmit:function(){
                return $(this).form('enableValidation').form('validate');
            },
            success:function(data){
                //alert(data); return;
                $.messager.alertLink('Info', data, 'info', "app/loadUrl/main/berkas_lookup"); 
            }
        });
    }
    function clearForm(){
        $('#ff').form('clear');
    }
            
</script> 

<div class="col-md-12 col-konten-full">

    <div class="judul-halaman bg-course"> <a href="app/loadUrl/main/berkas_lookup">Kelola Berkas <?=$this->SATUAN_KERJA_ASAL?></a> &rsaquo; Kelola</div>   

    <div class="konten-area">
    	<div class="konten-inner">
            <div>
            	
                <!--<div class='panel-body'>-->
		        <!--<form class='form-horizontal' role='form'>-->
                <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">

                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Data</h3>                      
                    </div>

                    <div class="form-group">
                        <label for="reqPenyusutanAkhirId" class="control-label col-md-2">Klasifikasi</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqKlasifikasiId" class="easyui-combotree" style="min-width: 50%;" name="reqKlasifikasiId" data-options="valueField:'id', textField:'text', url:'web/klasifikasi_json/combotree_arsip',
                                    				onSelect: function(rec){
                                                    					if(rec.id == '<?=$reqKlasifikasiId?>')
    																		$('#reqKode').val('<?=$reqKode?>');
                                                                        else
	                                                                        $('#reqKode').val(rec.KODE_TERAKHIR);
                                                                        $('#reqRetensiAktif').val(rec.RETENSI_AKTIF);
                                                                        $('#reqRetensiInaktif').val(rec.RETENSI_INAKTIF);
                                                                        $('#reqKlasifikasiKode').val(rec.KODE);
                                                                        $('#reqPenyusutanAkhirId').combobox('setValue', rec.PENYUSUTAN_AKHIR_ID);
                                                                    }" value="<?=$reqKlasifikasiId?>" required="required"/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqKode" class="control-label col-md-2">Nomor Berkas</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-4">
                                    <input type="text" id="reqKode" readonly class="easyui-validatebox textbox form-control" required name="reqKode" value="<?=$reqKode?>" data-options="required:true" style="width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>
                       
                    <div class="form-group">
                        <label for="reqNama" class="control-label col-md-2">Judul Berkas</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqNama" class="easyui-validatebox textbox form-control" required name="reqNama" value="<?=$reqNama?>" data-options="required:true" style="width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqPenyusutanAkhirId" class="control-label col-md-2">Penyusutan Akhir</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqPenyusutanAkhirId" class="easyui-combobox" style="min-width: 50%;" name="reqPenyusutanAkhirId" data-options="valueField:'id', textField:'text', url:'web/penyusutan_akhir_json/combo'" value="<?=$reqPenyusutanAkhirId?>" required="required"/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqRetensiAktif" class="control-label col-md-2">Retensi Aktif</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqRetensiAktif" class="easyui-validatebox textbox form-control" required name="reqRetensiAktif" value="<?=$reqRetensiAktif?>" data-options="required:true" style="width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqRetensiInaktif" class="control-label col-md-2">Retensi Inaktif</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqRetensiInaktif" class="easyui-validatebox textbox form-control" required name="reqRetensiInaktif" value="<?=$reqRetensiInaktif?>" data-options="required:true" style="width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    
                    <div class="form-group">
                        <label for="reqLokasiArsipId" class="control-label col-md-2">Lokasi Arsip</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqLokasiArsipId" class="easyui-combotree" style="min-width: 50%;" name="reqLokasiArsipId" data-options="valueField:'id', textField:'text', url:'web/lokasi_arsip_json/combotree'" value="<?=$reqLokasiArsipId?>" required="required"/>
                                </div>
                            </div>
                        </div>
                    </div>

                                      
                    <div class="form-group">
                        <label for="reqKeterangan" class="control-label col-md-2">Isi Ringkas</label>
                        <div class="col-md-9">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea name="reqKeterangan" style="width:100%; height:200px"><?=$reqKeterangan ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="reqKlasifikasiKode" id="reqKlasifikasiKode" value="<?=$reqKlasifikasiKode?>" />
                    <input type="hidden" name="reqId" value="<?=$reqId?>" />
                    <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
                    
                </form>
			</div>

            <div style="text-align:center;padding:5px">
                <a href="javascript:void(0)" class="btn btn-primary" onclick="submitForm()">Submit</a>
                <a href="javascript:void(0)" class="btn btn-warning" onclick="clearForm()">Clear</a>
            </div>
            
        </div>
    </div>    
    
</div>




<!------------------------- bawah ------------------------------->

</div>
    </div>
	
    <!--<footer class="text-center footer">
        <span>Copyright © 2019 PT Angkasa Pura I (Persero). All Rights Reserved.</span>
    </footer>-->
    
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>-->
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
    <script src="lib/bootstrap-3.3.7/docs/dist/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="lib/bootstrap-3.3.7/docs/assets/js/ie10-viewport-bug-workaround.js"></script>
    
    <!-- SCROLLBAR -->
    <link rel="stylesheet" href="css/scrollbar.css" type="text/css">
	<script type='text/javascript' src="js/enscroll-0.6.0.min.js"></script>
    <script type='text/javascript'>//<![CDATA[
    $(function(){
        $('.operator-inner').enscroll({
            showOnHover: false,
            verticalTrackClass: 'track3',
            verticalHandleClass: 'handle3'
        });
    });//]]> 
    
    </script>    
    <!--<script src="lib/bootstrap/dist/js/bootstrap.min.js"></script>-->
    
    <!-- EASYUI 1.4.5 -->
    <link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
	<link rel="stylesheet" type="text/css" href="lib/easyui/themes/icon.css">
	<script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="lib/easyui/globalfunction.js"></script>
    <script type="text/javascript" src="lib/easyui/kalender-easyui.js"></script>    
    
    <!-- EMODAL -->
    <script src="lib/emodal/eModal.js"></script>
    <script src="lib/emodal/eModal-cabang.js"></script>
    
    <script>
    function openAdd(pageUrl) {
        eModal.iframe(pageUrl, 'TNDE | PT Angkasa Pura I (Persero)')
    }
    function openCabang(pageUrl) {
        eModalCabang.iframe(pageUrl, 'TNDE | PT Angkasa Pura I (Persero)')
    }
    function closePopup() {
        eModal.close();
    }
    
    function windowOpener(windowHeight, windowWidth, windowName, windowUri)
    {
        var centerWidth = (window.screen.width - windowWidth) / 2;
        var centerHeight = (window.screen.height - windowHeight) / 2;
    
        newWindow = window.open(windowUri, windowName, 'resizable=0,width=' + windowWidth + 
            ',height=' + windowHeight + 
            ',left=' + centerWidth + 
            ',top=' + centerHeight);
    
        newWindow.focus();
        return newWindow.name;
    }
    
    function windowOpenerPopup(windowHeight, windowWidth, windowName, windowUri)
    {
        var centerWidth = (window.screen.width - windowWidth) / 2;
        var centerHeight = (window.screen.height - windowHeight) / 2;
    
        newWindow = window.open(windowUri, windowName, 'resizable=1,scrollbars=yes,width=' + windowWidth + 
            ',height=' + windowHeight + 
            ',left=' + centerWidth + 
            ',top=' + centerHeight);
    
        newWindow.focus();
        return newWindow.name;
    }
    
    </script>
    
    <!-- ACCORDION -->
    <link href="lib/jquery-accordion-menu/style/format.css" rel="stylesheet" type="text/css" />
    <link href="lib/jquery-accordion-menu/style/text.css" rel="stylesheet" type="text/css" />
    <!--<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"> </script>-->
    <script type="text/javascript"> 
		
		$(document).ready(function() {
			
			$('div.accordionButton').click(function() {
				$('div.accordionContent').slideUp('normal');	
				$(this).next().slideDown('normal');
			});
			
			$("div.accordionContent").show();
			<!--$('div.accordionContent:first').show();-->
		
		});
		    
    </script>
    
    
    <!-- SELECTED ROW ON TABLE SCROLLING -->
    <style>
	*table#Demo tbody tr:nth-child(odd){ background-color: #ddf7ef;}
	table#Demo tbody tr:hover{background-color: #333; color: #FFFFFF;}
	table#Demo tbody tr.selectedRow{background-color: #0072bc; color: #FFFFFF;}
	</style>
    <script>
	$("table#Demo tbody tr").click(function(){
		//alert("haii");
		$("table tr").removeClass('selectedRow');
		$(this).addClass('selectedRow');
	});
	</script>
    
    <!-- CHANGE BGCOLOR WHEN SCROLL -->
    <script>
	$(function () {
	  $(document).scroll(function () {
		var $nav = $(".navbar-fixed-top");
		$nav.toggleClass('scrolled', $(this).scrollTop() > $nav.height());
	  });
	});
	</script>
    <style>
	.navbar-fixed-top.scrolled {
	  background-color: #03428b !important;
	  transition: background-color 1000ms linear;
	}
	</style>
  </body>
</html>


