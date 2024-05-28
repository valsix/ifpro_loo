<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Aplikasi E-Office - PT. Jembatan Nusantara</title>
	<base href="<?=base_url();?>">
    
    <!-- Bootstrap Core CSS - Uses Bootswatch Flatly Theme: http://bootswatch.com/flatly/ -->
    <link href="lib/valsix/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="lib/valsix/css/freelancer.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="lib/valsix/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    
    <!-- CHART.JS -->
    <script src="lib/Chart.js-master/Chart.js"></script>
	
	<!-- SKILLSET -->
    <?
    if($pg == "" || $pg == "home"){
	?>
    <link rel="stylesheet" href="lib/skillset/skillset.css" type="text/css" />
    <?
	} else {}
	?>
    
    <?
    if($pg == "" || $pg == "home"){
	?>
    <!-- ANOSLIDE -->
    <link href="lib/anoslide/css/anoslide.css" rel="stylesheet" type="text/css" />
    <!-- RESPONSIVE TAB MASTER -->
    <link rel="stylesheet" href="lib/responsive-tabs-master/responsive-tabs2.css" type="text/css">
    
    <?
	}
	?>
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
    
    <?
    if($pg == "permohonan_jadwal_keandalan_jadwal")
	{
	?>
	<script type="text/javascript" src="lib/multidate/js/jquery-1.11.1.js"></script>
    <script type="text/javascript" src="lib/multidate/js/jquery-ui-1.11.1.js"></script>
    <?
	}
	?>
    <!-- EMODAL -->
	<script src="lib/startbootstrap-sb-admin-2-1.0.7/dist/js/eModal.min.js"></script>
    <style>
	.modal-kk{
		width:200px;
		height:200px !important;
		border:10px solid red;
		overflow:auto;
	}
	.modal-dialog{
		border:0px solid red;
		height:calc(100% - 120px) !important;
	}
	.dataTables_wrapper.no-footer{
		background-color:transparent;
	}
	</style>
    <script type="text/javascript">

	$(document).ready(function(){
		$(this).find(".modal-kk").css({"border": "2px solid red !important"});
		//$(this).find("body").css({'border':'2px solid red !important'});
	});

		function openPopup(page) {
			eModal.iframe(page, 'Aplikasi E-Office - PT. Jembatan Nusantara')
            // eModal.ajax(page, 'Aplikasi E-Office - PT. Jembatan Nusantara')
		}
		
		function openPopupModif(page, judul) {
			eModal.iframe({
			url: page,
			//size:ukuran,
			//size:"width=800,toolbar=1,resizable=1,scrollbars=yes,height=400,top=100,left=100",
			size:eModal.size.kk,
			title:judul
			});
		}
	</script>
	
    <!-- dari tnde -->
    <script src="lib/emodal/eModal.js"></script>
    <script src="lib/emodal/eModal-cabang.js"></script>
    <script>
		// from tnde
		function openAdd(page) {
			//alert("hai");
			eModal.iframe(page, 'Aplikasi E-Office - PT. Jembatan Nusantara ')
		}
		function openCabang(page) {
			eModalCabang.iframe(pageUrl, 'Aplikasi E-Office - PT. Jembatan Nusantara ')
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
    
    <!-- DATATABLE -->
	<link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.7/extensions/Responsive/css/dataTables.responsive.css">
	<link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.7/examples/resources/syntax/shCore.css">
	<link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.7/examples/resources/demo.css">
    <style type="text/css" class="init">
	table.dataTable th,
	table.dataTable td {
		white-space: nowrap;
	}
	
	table.display tr.even.row_selected td {
		background-color: #B0BED9;
	}
	
	table.display tr.odd.row_selected td {
		background-color: #9FAFD1;
	}
	</style>
    
	<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/media/js/jquery.dataTables.js"></script>
	<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/extensions/Responsive/js/dataTables.responsive.js"></script>
	<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/examples/resources/syntax/shCore.js"></script>
	<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/examples/resources/demo.js"></script>
        
    <!-- EASYUI -->
    <link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
    <script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="lib/easyui/kalender-easyui.js"></script>
    <script type="text/javascript" src="lib/easyui/globalfunction.js"></script>
    
    <!-- BARU -->
    <!--<script src="lib/easyui2/globalfunction.js"></script>      
    <link rel="stylesheet" type="text/css" href="lib/easyui2/themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="lib/easyui2/themes/icon.css">
    <script type="text/javascript" src="lib/easyui2/jquery-1.4.5.easyui.min.js"></script>
    <script type="text/javascript" src="lib/easyui2/kalender-easyui.js"></script>  -->
        
    <!-- YAMM -->
	<!-- CEK MOBILE ATAU DESKTOP -->
    <?php
	if(stristr($_SERVER['HTTP_USER_AGENT'], "Mobile")){ // if mobile browser
	?>
    
	<?
	} else { 
	?>
	<link href="lib/yamm3-master/yamm/yamm.css" rel="stylesheet">
    <?
	}
	?>
    
    <!-- TICKER -->
    <link href="lib/Responsive-jQuery-News-Ticker-Plugin-with-Bootstrap-3-Bootstrap-News-Box/css/site.css" rel="stylesheet" type="text/css" />
    
    <!-- jAlert MASTER -->
    <link rel="stylesheet" href="lib/jAlert-master/src/jAlert-v3.css">
    
    <!-- CUSTOMIZE -->
    <link rel="stylesheet" href="css/gaya.css" type="text/css">
    <link rel="stylesheet" href="css/monitoring.css" type="text/css">
    
    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="lib/font-awesome-4.7.0/css/font-awesome.css" type="text/css">

    <!-- The core Firebase JS SDK is always required and must be listed first -->
    <!-- <script src="https://www.gstatic.com/firebasejs/5.5.9/firebase.js"></script> -->
    <script type="text/javascript">
      var base_url = '<?=base_url()?>';
    </script>
    <script type='text/javascript' src="lib/js/firebase.js"></script>
    
    
    
</head>

<body class="body-utama">
	
	<div class="container-fluid">
    	<div class="row">
        	<div class="col-md-12">
            	<h4 class="judul-halaman" style="margin-left: 0px;">Isi komentar jika ingin mengirim dokumen ini!</h4>
                <form class="form-horizontal" action="/action_page.php">
                	
                    <div class="form-group">
                    	<div class="col-sm-offset-1 col-sm-10">
                        	<textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-sm-offset-1 col-sm-10">
                        	<button type="submit" class="btn btn-default">Submit</button>
                        </div>
                    </div>
                </form> 
            </div>
        </div>
    </div>
    
</body>

</html>
