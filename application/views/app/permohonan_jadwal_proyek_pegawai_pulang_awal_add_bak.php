<?
include_once("functions/default.func.php");
$reqId = $this->input->get("reqId");
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<meta name="viewport" content="initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,width=device-width,user-scalable=0"/>
<title>Aplikasi Presensi - PJB Services</title>
<base href="<?=base_url()?>">

 <!-- Bootstrap Core CSS -->
<link href="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- MetisMenu CSS -->
<link href="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">
<!-- Timeline CSS -->
<link href="lib/startbootstrap-sb-admin-2-1.0.7/dist/css/timeline.css" rel="stylesheet">
<!-- Custom CSS -->
<link href="lib/startbootstrap-sb-admin-2-1.0.7/dist/css/sb-admin-2.css" rel="stylesheet">
<!-- Morris Charts CSS -->
<link href="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/morrisjs/morris.css" rel="stylesheet">
<!-- Custom Fonts -->
<link href="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->

<script src="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/jquery/dist/jquery.min.js"></script>	
<!-- Bootstrap Core JavaScript -->
<script src="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Metis Menu Plugin JavaScript -->
<script src="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/metisMenu/dist/metisMenu.min.js"></script>
<!-- Custom Theme JavaScript -->
<script src="lib/startbootstrap-sb-admin-2-1.0.7/dist/js/sb-admin-2.js"></script>

<!---->
<link rel="stylesheet" href="css/admin.css" type="text/css">

<!--<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>-->
<script type="text/javascript">
function executeOnClick(varItem){
$("a").removeClass("aktif");

if(varItem == 'permohonan_jadwal_proyek_pegawai_add_data'){
	$("#permohonan_jadwal_proyek_pegawai_add_data").addClass("aktif");
	$('#permohonan_jadwal_proyek_pegawai_add_data').css({'background-position': '0 -27px'});
	mainFramePop.location.href='app/loadUrl/app/permohonan_jadwal_proyek_pegawai_add_data/?reqId=<?=$reqId?>';
	document.getElementById('trdetil').style.display = 'none';	
}
else if(varItem == 'permohonan_jadwal_proyek_pegawai_pulang_awal_add_anggota'){
	$("#permohonan_jadwal_proyek_pegawai_pulang_awal_add_anggota").addClass("aktif");
	$('#permohonan_jadwal_proyek_pegawai_pulang_awal_add_anggota').css({'background-position': '0 -27px'});
	mainFramePop.location.href='app/loadUrl/app/permohonan_jadwal_proyek_pegawai_pulang_awal_add_anggota/?reqId=<?=$reqId?>';
	document.getElementById('trdetil').style.display = 'none';	
}
return true;
}
</script>


<link rel="stylesheet" type="text/css" href="css/gaya.css">


</head>

<body leftmargin="0" rightmargin="0" topmargin="0" bottommargin="0">
	<div id="wrapper" class="bg-kiri-popup" style="overflow:hidden;">
		
        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top popup-atas" role="navigation" style="margin-bottom: 0;">
            <div class="navbar-header navbar-header-popup">
                <button type="button" class="navbar-toggle navbar-popup" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand">Form Anggota Proyek Pulang Awal</a>
            </div>
            <!-- /.navbar-header -->
			
            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li style="border:none;">
                            <div id="menu-kiri-judul">Main Menu</div>
                            <div id="menu-kiri">
                                <div><a id="permohonan_jadwal_proyek_pegawai_add_data" onClick="executeOnClick('permohonan_jadwal_proyek_pegawai_add_data');" class="aktif">Data Proyek</a></div>                                
                                <div><a id="permohonan_jadwal_proyek_pegawai_pulang_awal_add_anggota" onClick="executeOnClick('permohonan_jadwal_proyek_pegawai_pulang_awal_add_anggota');">Anggota Proyek</a></div>
                            </div>
                        </li>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>
        
        <!-- PAGE CONTENT BY VALSIX -->
        <div class="konten-utama-popup">
        	<table cellpadding="0" cellspacing="0" style="width:100%; height:100%;">
                <tr height="50%">
                    <td><iframe src="app/loadUrl/app/permohonan_jadwal_proyek_pegawai_add_data/?reqId=<?=$reqId?>" class="mainframe" id="idMainFrame" name="mainFramePop" width="100%" height="100%" scrolling="auto" frameborder="0" style="display:block;"></iframe></td>
                </tr>
                <tr height="50%" id="trdetil" style="display:none">
                    <td><iframe src="" class="mainframe" id="idMainFrameDetil" name="mainFrameDetilPop" width="100%" height="100%" scrolling="no" frameborder="0" style="display:block;"></iframe></td>
                </tr>
            </table>
        </div>

    </div>
    <!-- /#wrapper -->

</body>
</html>
