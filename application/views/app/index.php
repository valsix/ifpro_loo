<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Aplikasi E-Office - ASDP Indonesia Ferry</title>

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
    
    <style>
	a.navbar-brand {
		*border: 1px solid red;
		padding-left: 30px;
		padding-right: 30px;
		
		display: flex;
		justify-content: center; /* align horizontal */
		align-items: center; /* align vertical */
	}
	a.navbar-brand img{
		height: 80%;
	}
	</style>
    
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
                <a class="navbar-brand" href="<?=base_url()?>app/admin"><img src="<?=base_url()?>images/logo-asdp.png" style="margin-left:-15px;"></a>
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
                    <a href="<?=site_url('')?>app/index/login" title="Main Page">
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
                               

                               <div class="accordionButton"><img src="<?=base_url()?>images/icon-menu.png">Naskah</div>
                                <div class="accordionContent">
                                    <div class="accordion-item"><a data-toggle="collapse" data-target=".navbar-collapse" href="<?=site_url('app/loadUrl/admin/jenis_naskah')?>" target="mainFrame">Jenis Naskah</a></div>
                                    <div class="accordion-item"><a data-toggle="collapse" data-target=".navbar-collapse" href="<?=site_url('app/loadUrl/admin/naskah_template')?>" target="mainFrame">Naskah Template</a></div>
                                    <div class="accordion-item"><a data-toggle="collapse" data-target=".navbar-collapse" href="<?=site_url('app/loadUrl/admin/sifat_surat')?>" target="mainFrame">Sifat Surat</a></div>
                                    <div class="accordion-item"><a data-toggle="collapse" data-target=".navbar-collapse" href="<?=site_url('app/loadUrl/admin/prioritas_surat')?>" target="mainFrame">Prioritas Surat</a></div>
                                    <div class="accordion-item"><a data-toggle="collapse" data-target=".navbar-collapse" href="<?=site_url('app/loadUrl/admin/media_pengiriman')?>" target="mainFrame">Media Pengiriman</a></div>
                                    <div class="accordion-item"><a data-toggle="collapse" data-target=".navbar-collapse" href="<?=site_url('app/loadUrl/admin/status_pengiriman')?>" target="mainFrame">Status Pengiriman</a></div>
                                    <div class="accordion-item"><a data-toggle="collapse" data-target=".navbar-collapse" href="<?=site_url('app/loadUrl/admin/balas_cepat')?>" target="mainFrame">Balas Cepat</a></div>
                                    <div class="accordion-item"><a data-toggle="collapse" data-target=".navbar-collapse" href="<?=site_url('app/loadUrl/admin/disposisi')?>" target="mainFrame">Disposisi</a></div>
                                    <div class="accordion-item"><a data-toggle="collapse" data-target=".navbar-collapse" href="<?=site_url('app/loadUrl/admin/daftar_alamat')?>" target="mainFrame">Daftar Alamat</a></div>
                                    <div class="accordion-item"><a data-toggle="collapse" data-target=".navbar-collapse" href="<?=site_url('app/loadUrl/admin/konten')?>" target="mainFrame">Konten</a></div>
                                    <div class="accordion-item"><a data-toggle="collapse" data-target=".navbar-collapse" href="<?=site_url('app/loadUrl/admin/faq')?>" target="mainFrame">FAQ</a></div>
                                </div>
                                
                                <div class="accordionButton"><img src="<?=base_url()?>images/icon-menu.png">Arsip</div>
                                <div class="accordionContent">
                                    <div class="accordion-item"><a data-toggle="collapse" data-target=".navbar-collapse" href="<?=site_url('app/loadUrl/admin/klasifikasi')?>" target="mainFrame">Klasifikasi</a></div>
                                    <div class="accordion-item"><a data-toggle="collapse" data-target=".navbar-collapse" href="<?=site_url('app/loadUrl/admin/media_arsip')?>" target="mainFrame">Media Arsip</a></div>
                                    <div class="accordion-item"><a data-toggle="collapse" data-target=".navbar-collapse" href="<?=site_url('app/loadUrl/admin/tingkat_perkembangan')?>" target="mainFrame">Tingkat Perkembangan</a></div>
                                    <div class="accordion-item"><a data-toggle="collapse" data-target=".navbar-collapse" href="<?=site_url('app/loadUrl/admin/penyusutan_akhir')?>" target="mainFrame">Penyusutan Akhir</a></div>
                                </div>

                                <div class="accordionButton"><img src="<?=base_url()?>images/icon-menu.png">Struktur Organisasi</div>
                                <div class="accordionContent">
                                    <div class="accordion-item"><a href="<?=site_url('app/loadUrl/admin/unit_kerja')?>" target="mainFrame">Unit Kerja</a></div>
                                    <div class="accordion-item"><a href="<?=site_url('app/loadUrl/admin/jabatan_struktural')?>" target="mainFrame">Jabatan Struktural</a></div>
                                    <div class="accordion-item"><a href="<?=site_url('app/loadUrl/admin/kelompok_jabatan')?>" target="mainFrame">Kelompok Jabatan</a></div>
                                    <div class="accordion-item"><a href="<?=site_url('app/loadUrl/admin/jabatan_sementara')?>" target="mainFrame">Jabatan Sementara</a></div>
                                </div>
                                
                                <div class="accordionButton"><img src="<?=base_url()?>images/icon-menu.png">User Management</div>
                                <div class="accordionContent">
                                    <div class="accordion-item"><a href="<?=site_url('app/loadUrl/admin/pegawai')?>" target="mainFrame">Pegawai</a></div>
                                    <div class="accordion-item"><a href="<?=site_url('app/loadUrl/admin/non_pegawai')?>" target="mainFrame">Non Pegawai</a></div>
                                    <div class="accordion-item"><a href="<?=site_url('app/loadUrl/admin/user_pengelola')?>" target="mainFrame">User Pengelola</a></div>
                                </div>
                                                   
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
        	<?
			if($this->session->userdata("PEGAWAI_ALPHA") == "1")
			{
				$this->session->set_userdata("PEGAWAI_ALPHA","");
			?>
        		<iframe src="<?=site_url('app/loadUrl/app/rekapitulasi_alpha_3hari')?>" name="mainFrame"></iframe>
			<?
			}
			else
			{
            ?>
        		<iframe src="<?=site_url('app/loadUrl/app/home')?>" name="mainFrame"></iframe>
            <?
			}
            ?>
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
			eModal.iframe(page, 'Aplikasi E-Office - ASDP Indonesia Ferry')
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

