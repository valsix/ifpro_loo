<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Aplikasi Presensi - PJB Services</title>

    <!-- Bootstrap Core CSS -->
    <link href="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- Timeline CSS -->
    <link href="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/dist/css/timeline.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/bower_components/morrisjs/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <link rel="stylesheet" href="<?=base_url()?>css/admin.css" type="text/css">
    <link rel="stylesheet" href="<?=base_url()?>css/gaya.css" type="text/css">
    
</head>

<body style="overflow:hidden; background:#012c53 url(<?=base_url()?>images/bg-kiri-popup-pejabat.png) bottom left no-repeat;">
    <div id="wrapper">
    		
        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0; background:#d8ecff url(<?=base_url()?>images/bg-header-bootstrap.png) top right no-repeat; ">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?=base_url()?>app/admin"><img src="<?=base_url()?>images/logo2.png" style="margin-left:-15px;"></a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
            	<?php /*?><li>
                    <a href="<?=site_url('')?>">
                        <i class="fa fa-th fa-fw"></i>
                    </a>
                </li><?php */?>
                
                <li>
                    <a href="<?=site_url('')?>app/admin" title="Home">
                        <i class="fa fa-home fa-fw"></i>
                    </a>
                </li>
                
                <li>
                    <a href="<?=site_url('')?>" title="Main Page">
                        <i class="fa fa-globe fa-fw"></i>
                    </a>
                </li>
                
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="<?=base_url()?>login/logout"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse" >
                    <ul class="nav" id="side-menu" >
                    	
                        <li>
                        	
                            <!-- MENU KIRI -->
                            <div id="wrapper-accordion-menu">
                                <!-- MAIN MENU -->                         
                               <div class="accordionButton"><img src="<?=base_url()?>images/icon-menu.png">Presensi</div>
                                <div class="accordionContent">
                                	
                                    <div class="accordion-item"><a data-toggle="collapse" data-target=".navbar-collapse" href="<?=site_url('app/loadUrl/app/rekapitulasi_absensi_import')?>" target="mainFrame">Import Absensi</a></div>
                                    <div class="accordion-item"><a data-toggle="collapse" data-target=".navbar-collapse" href="<?=site_url('app/loadUrl/app/rekapitulasi_absensi')?>" target="mainFrame">Presensi Jam Kerja</a></div>
                                    <div class="accordion-item"><a href="<?=site_url('app/loadUrl/app/rekapitulasi_absensi_lembur')?>" target="mainFrame">Presensi Lembur</a></div>
                                    <!--<div class="accordion-item"><a href="<?=site_url('app/loadUrl/app/absensi_manual')?>" target="mainFrame">Presensi Manual</a></div>-->
                                    <!--<div class="accordion-item"><a href="<?=site_url('app/loadUrl/app/fingerscan')?>" target="mainFrame">Data Fingerscan</a></div>
                                    <div class="accordion-item"><a href="<?=site_url('app/loadUrl/app/absensi_ijin')?>" target="mainFrame">Presensi Ijin Pegawai</a></div>-->
                                </div>
                                
                                <div class="accordionButton"><img src="<?=base_url()?>images/icon-menu.png">Proses Rekap Presensi</div>
                                <div class="accordionContent">
                                    <div class="accordion-item"><a href="<?=site_url('app/loadUrl/app/absensi_koreksi')?>" target="mainFrame">Proses Rekap</a></div>
                                </div>
                                 <!--
                                <div class="accordionButton"><img src="<?=base_url()?>images/icon-menu.png">Jadwal Shift & Lembur</div>
                                <div class="accordionContent">
                                  <div class="accordion-item"><a href="<?=site_url('app/loadUrl/app/pegawai_jenis_jam_kerja')?>" target="mainFrame">Atur Jam Kerja Pegawai</a></div>
                                  <div class="accordion-item"><a href="<?=site_url('app/loadUrl/app/pegawai_jadwal')?>" target="mainFrame">Atur Jadwal Shift</a></div>
                                  <div class="accordion-item"><a href="<?=site_url('app/loadUrl/app/pegawai_lembur')?>" target="mainFrame">Atur Jadwal Lembur</a></div>
                                </div>
                                  -->
                                  
                                <div class="accordionButton"><img src="<?=base_url()?>images/icon-menu.png">Permohonan</div>
                                <div class="accordionContent">
                                  <div class="accordion-item"><a href="<?=site_url('app/loadUrl/app/permohonan_cuti_tahunan')?>" target="mainFrame">Cuti Tahunan</a></div>
                                  <div class="accordion-item"><a href="<?=site_url('app/loadUrl/app/permohonan_cuti_besar')?>" target="mainFrame">Cuti Besar</a></div>
                                  <div class="accordion-item"><a href="<?=site_url('app/loadUrl/app/permohonan_cuti_revisi')?>" target="mainFrame">Revisi Cuti</a></div>
                                  <div class="accordion-item"><a href="<?=site_url('app/loadUrl/app/permohonan_penangguhan_cuti')?>" target="mainFrame">Penangguhan Cuti</a></div>
                                  <div class="accordion-item"><a href="<?=site_url('app/loadUrl/app/permohonan_cuti_lainnya')?>" target="mainFrame">Cuti Lainnya</a></div>
                                  <div class="accordion-item"><a href="<?=site_url('app/loadUrl/app/permohonan_istirahat')?>" target="mainFrame">Istirahat</a></div>
                                  <div class="accordion-item"><a href="<?=site_url('app/loadUrl/app/permohonan_ijin_khusus')?>" target="mainFrame">Ijin Khusus</a></div>
                                  <div class="accordion-item"><a href="<?=site_url('app/loadUrl/app/permohonan_lembur')?>" target="mainFrame">Lembur</a></div>
                                  <!--<div class="accordion-item"><a href="<?=site_url('app/loadUrl/app/permohonan_terlambat')?>" target="mainFrame">Terlambat Pulang Cepat</a></div>-->
                                  <div class="accordion-item"><a href="<?=site_url('app/loadUrl/app/permohonan_ijin')?>" target="mainFrame">Terlambat / Pulang Cepat</a></div>
                                  <div class="accordion-item"><a href="<?=site_url('app/loadUrl/app/permohonan_insidentil')?>" target="mainFrame">Insidentil</a></div>
                                  <div class="accordion-item"><a href="<?=site_url('app/loadUrl/app/permohonan_oncall')?>" target="mainFrame">Oncall</a></div>
                                  <div class="accordion-item"><a href="<?=site_url('app/loadUrl/app/permohonan_ganti_shift')?>" target="mainFrame">Ganti Shift</a></div>
                                  <div class="accordion-item"><a href="<?=site_url('app/loadUrl/app/permohonan_jadwal_keandalan')?>" target="mainFrame">Jadwal Keandalan</a></div>
                                  <div class="accordion-item"><a href="<?=site_url('app/loadUrl/app/permohonan_jadwal_shift')?>" target="mainFrame">Jadwal Shift</a></div>
                                </div>
                                
                                <div class="accordionButton"><img src="<?=base_url()?>images/icon-menu.png">Rekapitulasi</div>
                                <div class="accordionContent">
                                  <div class="accordion-item"><a href="<?=site_url('app/loadUrl/app/rekapitulasi_kehadiran_unit')?>" target="mainFrame">Kehadiran</a></div>
                                  <div class="accordion-item"><a href="<?=site_url('app/loadUrl/app/rekapitulasi_potong_absensi')?>" target="mainFrame">Potongan Absensi</a></div>
                                  <div class="accordion-item"><a href="<?=site_url('app/loadUrl/app/rekapitulasi_total_jam_lembur')?>" target="mainFrame">Jam Lembur</a></div>
                                  <!--<div class="accordion-item"><a href="<?=site_url('app/loadUrl/app/rekapitulasi_lembur')?>" target="mainFrame">Lembur</a></div>-->
                                  <div class="accordion-item"><a href="<?=site_url('app/loadUrl/app/rekapitulasi_uang_makan')?>" target="mainFrame">Uang Makan</a></div>
                                  <div class="accordion-item"><a href="<?=site_url('app/loadUrl/app/rekapitulasi_jkk_jks')?>" target="mainFrame">JKK JKS</a></div>
                                  <div class="accordion-item"><a href="<?=site_url('app/loadUrl/app/rekapitulasi_keandalan')?>" target="mainFrame">Keandalan</a></div>
                                  <div class="accordion-item"><a href="<?=site_url('app/loadUrl/app/rekapitulasi_on_call')?>" target="mainFrame">On Call</a></div>
                                  <div class="accordion-item"><a href="<?=site_url('app/loadUrl/app/rekapitulasi_premi_shift')?>" target="mainFrame">Premi Shift</a></div>
                                </div>
                                
                                
                                <div class="accordionButton"><img src="<?=base_url()?>images/icon-menu.png">Monitoring</div>
                                <div class="accordionContent">
                                  <div class="accordion-item"><a href="<?=site_url('app/loadUrl/app/rekapitulasi_jam_kerja')?>" target="mainFrame">Jam Kerja</a></div>
                                  <div class="accordion-item"><a href="<?=site_url('app/loadUrl/app/rekapitulasi_jam_lembur')?>" target="mainFrame">Jam Lembur</a></div>
                                  <div class="accordion-item"><a href="<?=site_url('app/loadUrl/app/rekapitulasi_terlambat_pulang_cepat')?>" target="mainFrame">Terlambat & Pulang Cepat</a></div>
                                  <div class="accordion-item"><a href="<?=site_url('app/loadUrl/app/rekapitulasi_kehadiran')?>" target="mainFrame">Kehadiran Bulanan</a></div>
                                  <div class="accordion-item"><a href="<?=site_url('app/loadUrl/app/rekapitulasi_kehadiran_pegawai_tahun')?>" target="mainFrame">Kehadiran Tahunan</a></div>
                                  <div class="accordion-item"><a href="<?=site_url('app/loadUrl/app/rekapitulasi_sisa_cuti')?>" target="mainFrame">Sisa Cuti Tahunan</a></div>
                                  <div class="accordion-item"><a href="<?=site_url('app/loadUrl/app/rekapitulasi_sisa_cuti_besar')?>" target="mainFrame">Sisa Cuti Besar</a></div>
                                </div>
                                
                                <div class="accordionButton"><img src="<?=base_url()?>images/icon-menu.png">Master</div>
                                <div class="accordionContent">
                                  <div class="accordion-item"><a href="<?=site_url('app/loadUrl/app/jam_kerja')?>" target="mainFrame">Jam Kerja</a></div>
                                  <div class="accordion-item"><a href="<?=site_url('app/loadUrl/app/jam_kerja_ramadhan')?>" target="mainFrame">Jam Kerja Ramadhan</a></div>
                                  <div class="accordion-item"><a href="<?=site_url('app/loadUrl/app/hari_libur')?>" target="mainFrame">Hari Libur</a></div>
                                  <div class="accordion-item"><a href="<?=site_url('app/loadUrl/app/ijin_koreksi')?>" target="mainFrame">Ijin Koreksi</a></div>
                                  <div class="accordion-item"><a href="<?=site_url('app/loadUrl/app/penomoran_surat')?>" target="mainFrame">Penomoran Surat</a></div>
                                  <div class="accordion-item"><a href="<?=site_url('app/loadUrl/app/provinsi')?>" target="mainFrame">Provinsi</a></div>
                                   <!--<div class="accordion-item"><a href="<?=site_url('app/loadUrl/app/jam_kerja_shift')?>" target="mainFrame">Jam Kerja Shift</a></div>
                                  <div class="accordion-item"><a href="<?=site_url('app/loadUrl/app/jam_kerja_jenis')?>" target="mainFrame">Jenis Jam Kerja</a></div>
                                  <div class="accordion-item"><a href="<?=site_url('app/loadUrl/app/jam_masuk')?>" target="mainFrame">Jam Masuk</a></div>
                                  <div class="accordion-item"><a href="<?=site_url('app/loadUrl/app/jam_pulang')?>" target="mainFrame">Jam Pulang</a></div>-->
                                </div>  

                                <div class="accordionButton"><img src="<?=base_url()?>images/icon-menu.png">Kelola Aplikasi</div>
                                <div class="accordionContent">
                                  <div class="accordion-item"><a href="<?=site_url('app/loadUrl/app/pelaksana_harian')?>" target="mainFrame">Pelaksana Harian (PH)</a></div>
                                  <div class="accordion-item"><a href="<?=site_url('app/loadUrl/app/fingerscan_lokasi')?>" target="mainFrame">Register Mesin</a></div>
                                  <div class="accordion-item"><a href="<?=site_url('app/loadUrl/app/setting_aplikasi')?>" target="mainFrame">Setting Aplikasi</a></div>
                                  <div class="accordion-item"><a href="<?=site_url('app/loadUrl/app/user_group')?>" target="mainFrame">User Group</a></div>
                                  <div class="accordion-item"><a href="<?=site_url('app/loadUrl/app/user_login')?>" target="mainFrame">User Login</a></div>
                                  <div class="accordion-item"><a href="<?=site_url('app/loadUrl/app/rekapitulasi_pegawai_approval')?>" target="mainFrame">Monitoring Approval</a></div>
                                </div>  

                                
                                 <div class="accordionButton"><img src="<?=base_url()?>images/icon-menu.png">Sinkronisasi</div>
                                <div class="accordionContent">
                                  <div class="accordion-item"><a href="<?=site_url('app/loadUrl/app/sinkronisasi_pegawai')?>" target="mainFrame">Pegawai</a></div>
                                  <div class="accordion-item"><a href="<?=site_url('app/loadUrl/app/cabang')?>" target="mainFrame">Unit</a></div>
                                  <div class="accordion-item"><a href="<?=site_url('app/loadUrl/app/departemen')?>" target="mainFrame">Direktorat</a></div>
                                  <div class="accordion-item"><a href="<?=site_url('app/loadUrl/app/sub_departemen')?>" target="mainFrame">Sub Direktorat</a></div>
                                </div>   
                                 <div class="accordionButton"><img src="<?=base_url()?>images/icon-menu.png">Chatting</div>
                                <div class="accordionContent">
                                  <div class="accordion-item"><a href="<?=site_url('app/loadUrl/app/phpshoutbox')?>" target="mainFrame">Chatting</a></div>
                                </div>   
                                
                                
                               <!-- <div class="accordionButton"><img src="<?=base_url()?>images/icon-menu.png">Panduan</div>
                                <div class="accordionContent">
                                  <div class="accordion-item"><a href="<?=site_url('')?>" target="mainFrame">Panduan Penggunaan</a></div>
                                  <div class="accordion-item"><a href="<?=site_url('')?>" target="mainFrame">Alur Proses</a></div>
                                </div>-->
                                
                            </div>
                        
                            
                        </li>
                        
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>

        <!-- PAGE CONTENT BY VALSIX -->
        <div class="konten-utama">
        	<iframe src="<?=site_url('app/loadUrl/app/home')?>" name="mainFrame"></iframe>
        </div>
        
        
        
       

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/js/bootstrap.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/bower_components/metisMenu/dist/metisMenu.min.js"></script>
  
    <!-- Custom Theme JavaScript -->
    <script src="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/dist/js/sb-admin-2.js"></script>

	<!-- eModal -->
	<script src="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/dist/js/eModal-admin.min.js"></script>
    <style>
	.modal-kk{
		width:200px;
		height:200px !important;
		border:10px solid red;
		overflow:auto;
	}
	</style>
    <script type="text/javascript">
//		function changeElement('.modal-kk') {
//			var el = document.getElementsByClassName('.modal-kk');
//			el.style.color = "red";
//			el.style.fontSize = "15px";
//			el.style.backgroundColor = "#f00";
//		}

	$(document).ready(function(){
		$(this).find(".modal-kk").css({"border": "2px solid red !important"});
		//$(this).find("body").css({'border':'2px solid red !important'});
	});

		function openPopup(page) {
			eModal.iframe(page, 'Aplikasi Presensi - PJB Services')
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
		
		function closePopup(pesan)
		{
			eModal.alert(pesan);		
			setInterval(function(){ document.location.reload(); }, 2000); 	
		}
		
		// OK
		//function openPopup(page) {
		//	eModal.iframe(page, 'Aplikasi Presensi - PJB Services')
		//}
		
	</script>
    
	<!-- ACCORDION MENU -->
    <link href="<?=base_url()?>lib/jquery-accordion-menu/style/format.css" rel="stylesheet" type="text/css" />
    <link href="<?=base_url()?>lib/jquery-accordion-menu/style/text.css" rel="stylesheet" type="text/css" />
    <!--<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"> </script>-->
    <script type="text/javascript" src="<?=base_url()?>lib/jquery-accordion-menu/includes/javascript.js"> </script>
    <script type="text/javascript">
    $(document).ready(function() {
        /********************************************************************************************************************
        CLOSES ALL DIVS ON PAGE LOAD
        ********************************************************************************************************************/
		//$("div#wrapper-accordion-menu").show();
        $("div.accordionContent:first").show();
    });
	
	//if (!localStorage['done']) {
		//localStorage['done'] = 'yes';
		//myFunction();
		//$("div#wrapper-accordion-menu").hide();
	//}
    </script>

</body>

</html>

