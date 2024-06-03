<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

//$this->load->model("LiveChat");
//$live_chat = new LiveChat();
//$live_chat_history = new LiveChat();
//
//$live_chat->selectByParamsTo(array("A.PEGAWAI_ID_TO" => '012092713'));
//
//
//$live_chat_history->selectByParamsBy(array("A.PEGAWAI_ID_BY" => '072124180'));

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="images/logo.png" />

    <title>Aplikasi E-Office - PT. Indonesia Ferry Property</title>
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

    <!-- <link rel="stylesheet" href="lib/summernote/summernote-bs4.css">
    <script type="text/javascript" src="lib/summernote/summernote-bs4.js"></script> -->

    <!-- Plugin JavaScript -->
    <?php /*?><!--<script src="lib/valsix/js/jquery.easing.min.js"></script>-->
    <script src="lib/valsix/js/classie.js"></script>
    <script src="lib/valsix/js/cbpAnimatedHeader.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="lib/valsix/js/freelancer.js"></script><?php */?>
    
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
            eModal.iframe(page, 'Aplikasi E-Office - PT. Indonesia Ferry Property')
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
            eModal.iframe(page, 'Aplikasi E-Office - PT. Indonesia Ferry Property ')
        }
        function openCabang(page) {
            eModalCabang.iframe(pageUrl, 'Aplikasi E-Office - PT. Indonesia Ferry Property ')
        }
        function closePopup() {
            eModal.close();
        }
        function closePopupAlamat() {
            //alert("indexxxx");
            $('#reqEksternalKepadaInfo').combotree('reload');
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
    <!-- <script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/examples/resources/syntax/shCore.js"></script> -->
    <!-- <script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/examples/resources/demo.js"></script> -->
        
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
    

    <script type='text/javascript' src="lib/js/firebase-app.js"></script>
    <script type='text/javascript' src="lib/js/firebase-messaging.js"></script>

    <!-- <script type='text/javascript' src="js/firebase.js"></script> -->
    
    <style>
    .nav.navbar-nav.navbar-right{
        margin-right: 0px;
    }
    </style>

    <script src="lib/js/valsix-serverside.js"></script>
    
</head>

<body class="body-utama">
    
    <?php /*?>
    <div class="area-live-chat-button">
        <i class="fa fa-comments" aria-hidden="true"></i>
        <div class="area-live-chat" style="">
            <div class="container-fluid">
                <div class="messaging">
                  <div class="inbox_msg">
                    <!------------>
                    <div class="tab">
                        <div class="headind_srch">
                            <div class="srch_bar">
                              <div class="stylish-input-group">
                                <input type="text" class="search-bar"  placeholder="Cari nama" >
                                <span class="input-group-addon">
                                <button type="button"> <i class="fa fa-search" aria-hidden="true"></i> </button>
                                </span> </div>
                            </div>
                        </div>
              <? 
              $no = 0;  
               while($live_chat->nextRow()) 
                {
                      $nama = $live_chat->getField("NAMA");
                      $pesan = $live_chat->getField("PESAN");
                      ?>
                      <button class="tablinks" onclick="openCity(event, '<?=$no?>')">
                       <div class="chat_list active_chat">
                        <div class="chat_people">
                          <div class="chat_img"> <i class="fa fa-user-circle" aria-hidden="true"></i> </div>
                          <div class="chat_ib">
                            <h5><?=$nama?> <span class="chat_date">Dec 25</span></h5>
                            <p><?=$pesan?></p>
                          </div>
                        </div>
                      </div>
                      </button>
                  <?
                   $no ++;
                  }
                ?>   
                    
                    </div>

                    <? 
                    $no = 0;  
                    while($live_chat_history->nextRow()) 
                    {
                      $nama = $live_chat_history->getField("NAMA");
                      $pesan = $live_chat_history->getField("PESAN");
                    ?>

                    <div id="<?=$no?>" class="tabcontent">
                      <a class="close-message" onClick="closeMessage()"><i class="fa fa-times"></i></a>
                      <div class="nama-user">
                          <?=$nama?>
                        </div>
                        <div class="mesgs">
                          <div class="msg_history">
                            <div class="incoming_msg">
                              <div class="incoming_msg_img"> <i class="fa fa-user-circle" aria-hidden="true"></i> </div>
                              <div class="received_msg">
                                <div class="received_withd_msg">
                                  <p><?=$pesan?></p>
                                  <span class="time_date"> 11:01 AM    |    June 9</span></div>
                              </div>
                            </div>
                            <div class="outgoing_msg">
                              <div class="sent_msg">
                                <p>Test which is a new approach to have all
                                  solutions</p>
                                <span class="time_date"> 11:01 AM    |    June 9</span> </div>
                            </div>
                          </div>
                          <div class="type_msg">
                            <div class="input_msg_write">
                                <div class="element">
                                    <i class="fa fa-paperclip"></i><!--<span class="name">No file selected</span>-->
                                    <input class="browse" type="file" name="" id="">
                                </div>
                                <input type="text" class="write_msg" placeholder="Type a message" />
                                <button class="msg_send_btn" type="button"><i class="fa fa-paper-plane-o" aria-hidden="true"></i></button>
                            </div>
                          </div>
                        </div>
                    </div>




                      <?
                      $no ++;
                    }
                    ?>   
                    
   
                    </div>
                    
                    <script>
                    function openCity(evt, cityName) {
                        //alert("haii");
                        var i, tabcontent, tablinks;
                        tabcontent = document.getElementsByClassName("tabcontent");
                        
                        for (i = 0; i < tabcontent.length; i++) {
                            tabcontent[i].style.display = "none";
                            //tabcontent[i].classList.remove("show-tabcontent");
                        }
                        tablinks = document.getElementsByClassName("tablinks");
                        for (i = 0; i < tablinks.length; i++) {
                            tablinks[i].className = tablinks[i].className.replace(" active", "");
                        }
                        document.getElementById(cityName).style.display = "block";
                        document.getElementById(cityName).classList.add("show-tabcontent");
                        evt.currentTarget.className += " active";
                    }
                    
                    function closeMessage(evt, cityName){
                        //alert("closeMessage");
                        var i, tabcontent, tablinks;
                        tabcontent = document.getElementsByClassName("tabcontent");
                        for (i = 0; i < tabcontent.length; i++) {
                            //tabcontent[i].style.display = "none";
                            tabcontent[i].classList.remove("show-tabcontent");
                        }
                    }
                    
                    // Get the element with id="defaultOpen" and click on it
                    //document.getElementById("defaultOpen").click();
                    </script>
                    <!------------>
                    
                    </div>
                </div>
            </div>
        </div>
        <?php */?>
        
    </div> <!-- END area-live-chat-button -->
    
    <!-- Navigation -->
    <nav class="navbar yamm navbar-default navbar-fixed-top header-area" style="border:0px solid green;">
        <div class="container container-header">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header page-scroll" style="padding:0 0 !important;">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="main/index/login">
                    <!-- <img src="images/logo-jembatan-nusantara.png"> -->
                    <img src="images/logo.png" style="width: 75%; height:auto">
                    <span style="position: relative; padding-left: 15px;">
                        <p style="line-height: 10px; font-size: 12px; height: auto; display: inline-block; margin-top: -30px; position: absolute;">Indonesia Ferry &nbsp;Property</p>
                    </span>
                </a>
            </div>
            
            <!-- Collect the nav links, forms, and other content for toggling -->
            <!--<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1" style="padding-right:20px;">-->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li class="hidden">
                        <a href="#page-top"></a>
                    </li>
                    <?
                    if($this->ID == ""){
                    ?>
                    <li class="page-scroll">
                        <div class="login-area">
                            <form action="login/action" method="post">
                                <span class="judul">Login Area</span>
                                <span><input type="text" name="reqUser" placeholder="Username.."></span>
                                <span><input type="password" name="reqPasswd" placeholder="Password.."></span>
                                <span><input type="submit" value="Login"></span>
                            </form>
                        </div>
                    </li>
                    <?
                    } else {
                    ?>
                    <!-- <li><a href="app/index" <? if($pg == "home"){ ?>class="active"<? } ?>>Korporat</a></li> -->
                    <li><a href="main/index/login" <? if($pg == "login"){ ?>class="active"<? } ?>>Dashboard</a></li>
                    <?
                    if($this->USER_GROUP == "PEGAWAI" || in_array("PEGAWAI", explode(",", $this->USER_GROUP)) || $this->USER_GROUP == "SEKRETARIS" || in_array("SEKRETARIS", explode(",", $this->USER_GROUP))){
                    ?>
                    <li><a href="main/index/nota_dinas_add" <? if($pg == "surat_masuk_add"){ ?>class="active"<? } ?>>Naskah</a></li>
                    <?
                    }
                    ?>

                    <?
                    // echo "xxxxx".$this->NAMA; exit;
                    if($this->USER_GROUP == "ADMIN" || in_array("ADMIN", explode(",", $this->USER_GROUP))){
                    ?>

                      <?
                      if($this->CABANG_ID == "PST")
                      {
                      ?>
                      <li><a href="main/index/jenis_naskah" <? if($pg == "jenis_naskah"){ ?>class="active"<? } ?>>Master</a></li>
                      <?
                      }
                      else
                      {
                      ?>
                      <li><a href="main/index/unit_kerja" <? if($pg == "unit_kerja"){ ?>class="active"<? } ?>>Master</a></li>
                      <?
                      }
                      ?>

                    <?
                    }
                    ?>

                    <?
                    if($this->USER_GROUP == "TATAUSAHA"){
                    ?>
                      <li><a href="main/index/surat_masuk_tu" <? if($pg == "surat_masuk_tu"){ ?>class="active"<? } ?>>Naskah</a></li>
                    <?
                    }
                    ?>

                    <?php /*?><li><a href="main/index/coba_jenis_naskah" <? if($pg == "coba_jenis_naskah"){ ?>class="active"<? } ?>>Agenda</a></li><?php */?>
                    <?php /*?><li><a href="main/index/daily_report" <? if($pg == "daily_report"){ ?>class="active"<? } ?>>Naskah</a></li><?php */?>
                    <?php /*?><li><a href="main/index/review" <? if($pg == "review"){ ?>class="active"<? } ?>>Request Nomor</a></li><?php */?>
                    
                    <?php /*?><?
                    if($this->AKSES_MASTER == 1 || $this->AKSES_LAPORAN == 1 || $this->AKSES_PROSES_REKAP == 1 || $this->USERNAME == '9014140KP')
                        $caption = "Master";
                    else
                        $caption = "Verifikasi Permohonan";
                    ?>
                    <li><a href="app/admin"><?=$caption?></a></li><?php */?>
                    
                    <?php /*?><li class="dropdown yamm-fw notifikasi"><a href="#" data-toggle="dropdown" class="dropdown-toggle"><i class="fa fa-bell"></i><span class="badge badge-danger">3</span><b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li>
                                <div class="area-notifikasi-header">
                                    <div class="title">Notifikasi</div>
                                    <div class="inner">
                                        <div class="item">
                                            <div class="ikon"><i class="fa fa-bell"></i></div>
                                            <div class="keterangan">
                                                <a href="#">Lorem ipsum dolor sit amet, consectetur adipiscing elit...</a>
                                                <br><span class="waktu">3 menit yang lalu</span>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="item">
                                            <div class="ikon"><i class="fa fa-bell"></i></div>
                                            <div class="keterangan">
                                                <a href="#">Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua...</a>
                                                <br><span class="waktu">3 menit yang lalu</span>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="item">
                                            <div class="ikon"><i class="fa fa-bell"></i></div>
                                            <div class="keterangan">
                                                <a href="#">Excepteur sint occaecat cupidatat non proident...</a>
                                                <br><span class="waktu">3 menit yang lalu</span>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </li><?php */?>
                    <li class="area-akun">
                        <div class="inner">
                            <?=$this->NAMA?> <br>
                        <span class="id-akun">(<?=$this->ID?>)</span>

                        <!-- /.dropdown -->
                        <?
                        if($this->AN_TAMBAHAN)
                        {
                            ?>
                            <div class="select">
                                <select style="width:90% !important" name="reqSatker" id="reqSatker" onChange="changeSatker(this);">
                                    <?
                                    if ($this->SATKER_JABATAN_NOT_PENGGANTI) {
                                    ?>
                                        <option value="pribadi" <?if($this->SATKER_ID_NOT_PENGGANTI==$this->SATUAN_KERJA_ID_ASAL){ ?> selected <?} ?>> <?=$this->SATKER_JABATAN_NOT_PENGGANTI?></option>
                                    <?
                                    }
                                    ?>

                                    <?
                                    $arrmultijabatan= $this->MULTIJABATAN;
                                    for($imulti=0; $imulti < count($arrmultijabatan); $imulti++)
                                    {
                                        $infopltplhantambahan= $arrmultijabatan[$imulti]["AN_TAMBAHAN"];
                                        $infopltplhsatuankerjaid= $arrmultijabatan[$imulti]["SATKER_ID_PENGGANTI"];
                                        $infopltplhjabatan= $arrmultijabatan[$imulti]["SATKER_JABATAN_PENGGANTI"];
                                    ?>
                                        <option value="<?=$infopltplhantambahan?>" <?if($infopltplhsatuankerjaid==$this->SATUAN_KERJA_ID_ASAL){ ?> selected <?} ?>><?=$infopltplhjabatan?></option>
                                    <?
                                    }
                                    ?>
                                    <!-- <option value="<?=$this->AN_TAMBAHAN?>" <?if($this->SATKER_ID_PENGGANTI==$this->SATUAN_KERJA_ID_ASAL){ ?> selected <?} ?>><?=$this->SATKER_JABATAN_PENGGANTI?></option> -->
                                </select>
                            </div>
                            <?
                        }
                        else
                        {
                        ?>
                            <span class="id-akun">(<?=$this->SATUAN_KERJA_JABATAN?>)</span>
                        <?
                        }
                        ?>
                        
                      </div>
                    </li>
                    <li class="area-akun-gantipass">
                      <a href="main/index/ubah_password"><i class="fa fa-pencil-square-o"></i> Ubah Password</a>
                    </li>
                    <li class="area-akun-logout">
                        <a href="login/logout"><i class="fa fa-sign-out"></i> Logout</a>
                    </li>
                    <?
                    }
                    ?>
                    
                </ul>
            </div> <!-- /.navbar-collapse -->
            
        </div> <!-- /.container-fluid -->
        
        <?
        if($pg == "home" || $pg == "")
        {}
        else
        {
        ?>
        <div class="area-breadcrumb">
            <ul class="breadcrumb">
                <?php /*?><li><a href="app/index/login">Profil</a></li>                <?php */?>
                <li><a href="main/index/login">Home</a></li>                
                <?=$breadcrumb?>                
            </ul>
        </div>
        <?
        }
        ?>
    </nav>    
    
    <?
    if($pg == "" || $pg == "home"){
    ?>
    <div class="container" style="margin-top:91px;">
    <?
    } else {
    ?>
    <div class="container container-main" style="border:0px solid cyan;">
    <?
    }
    ?>
    
        <?
        if($pg == "" || $pg == "home" || $pg == "login"){
        ?>
        <div class="row area-utama">
        <?
        } else {
        ?>
        <div class="row area-utama">
        <?
        }
        ?>
            <?
            if($pg == "" || $pg == "home" || $pg == "login"){
            ?>
            <div class="col-lg-12">
            <?
            } else {

            $arrJudul = explode("_", $pg);
            $max = count($arrJudul) - 1;                
            if($arrJudul[$max] == "add" || $arrJudul[$max] == "kelompok" || $arrJudul[$max] == "jadwal")
            {       
                $link_monitoring = str_replace("_add", "", $pg);
                $link_monitoring = str_replace("_kelompok", "", $link_monitoring);
                
                $link_add = $pg;
            }
            else
            {
                $link_monitoring = $pg;
                $link_add = $pg."_add";
            }
            ?>
            
            
            <!-- CEK MOBILE ATAU DESKTOP -->
            <?php /*?><?php
            if(stristr($_SERVER['HTTP_USER_AGENT'], "Mobile")){ // if mobile browser
            ?>
            <div id="main">
                <button class="openbtn" onclick="openNav()">☰ Open Sidebar</button> 
            </div>
            <?
            } else { 
            ?>
            <script>
            //alert("desktop");
            </script>
            <?
            }
            ?><?php */?>
            
            <div id="main">
                <button class="openbtn" onclick="openNav()"><i class="fa fa-bars" aria-hidden="true"></i> Sidebar</button> 
            </div>
            
            <script>
            function openNav() {
              document.getElementById("mySidebar").style.width = "250px";
              document.getElementById("main").style.marginLeft = "250px";
            }
            
            function closeNav() {
              document.getElementById("mySidebar").style.width = "0";
              document.getElementById("main").style.marginLeft= "0";
            }
            </script>
            
            <div class="col-lg-2 area-sidebar-kiri sidebar" id="mySidebar">
                <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
                
                <nav class="navbar-default navbar-side sidebar-kiri" role="navigation">
                    <div class="sidebar-collapse">

                        <div id="my_menu" class="sdmenu">
                            <?
                            if( 
                                in_array("ADMIN", explode(",", $this->USER_GROUP)) == false
                                &&
                                (
                                    $pg == "surat_masuk_add" ||
                                    $pg == "surat_keluar_add" ||
                                    $pg == "inbox" ||
                                    $pg == "inbox_detil" ||
                                    $pg == "inbox_detil_fhm" ||
                                    $pg == "takah_masuk" ||
                                    $pg == "takah_masuk_detil" ||
                                    $pg == "draft" ||
                                    $pg == "draft_detil" ||
                                    $pg == "approval" ||
                                    $pg == "sent" ||

                                    $pg == "kotak_masuk" ||
                                    $pg == "kotak_masuk_detil" ||
                                    $pg == "kotak_masuk_riwayat" ||
                                    $pg == "kotak_masuk_input" ||
                                    $pg == "kotak_masuk_balas" ||

                                    $pg == "kotak_masuk_nota_dinas" ||
                                    $pg == "kotak_masuk_surat_keluar" ||
                                    $pg == "kotak_masuk_edaran" ||
                                    $pg == "kotak_masuk_perintah" ||
                                    $pg == "kotak_masuk_surat_keputusan_direksi" ||
                                    $pg == "kotak_masuk_keputusan_direksi" ||
                                    $pg == "kotak_masuk_instruksi_direksi" ||
                                    $pg == "kotak_masuk_surat_masuk_manual" ||

                                    $pg == "kotak_masuk_disposisi" ||
                                    $pg == "kotak_masuk_disposisi_detil" ||
                                    $pg == "kotak_masuk_disposisi_riwayat" ||
                                    $pg == "kotak_masuk_disposisi_input" ||
                                    $pg == "kotak_masuk_tanggapan" ||

                                    $pg == "kotak_keluar" ||
                                    $pg == "kotak_keluar_detil" ||

                                    $pg == "kotak_keluar_nota_dinas" ||
                                    $pg == "kotak_keluar_surat_keluar" ||
                                    $pg == "kotak_keluar_edaran" ||
                                    $pg == "kotak_keluar_perintah" ||
                                    $pg == "kotak_keluar_surat_keputusan_direksi" ||
                                    $pg == "kotak_keluar_keputusan_direksi" ||
                                    $pg == "kotak_keluar_instruksi_direksi" ||
                                    $pg == "kotak_keluar_surat_masuk_manual" ||

                                    $pg == "kotak_keluar_disposisi" ||
                                    $pg == "kotak_keluar_tanggapan" ||

                                    $pg == "nota_dinas_add" ||
                                    $pg == "nota_dinas_lihat" ||

                                    $pg == "surat_keluar_add" ||
                                    $pg == "surat_keluar_lihat" ||

                                    $pg == "surat_edaran_add" ||
                                    $pg == "surat_edaran_lihat" ||

                                    $pg == "keputusan_direksi_add" ||
                                    $pg == "keputusan_direksi_lihat" ||

                                    $pg == "surat_keputusan_direksi_add" ||
                                    $pg == "surat_keputusan_direksi_lihat" ||

                                    $pg == "surat_masuk_manual_add" ||
                                    $pg == "surat_masuk_manual_lihat" ||

                                    $pg == "petikan_skd_add" ||
                                    $pg == "petikan_skd_lihat" ||

                                    $pg == "surat_perintah_add" ||
                                    $pg == "surat_perintah_lihat" ||

                                    $pg == "instruksi_direksi_add" ||
                                    $pg == "instruksi_direksi_lihat" ||

                                    $pg == "newdraft" ||
                                    $pg == "newdraftmanual" ||
                                    $pg == "status" ||
                                    $pg == "status_detil" ||

                                    $pg == "perlu_persetujuan" ||
                                    $pg == "perlu_persetujuan_detil" ||
                                    
                                    $pg == "surat_pengantar_pengiriman" ||
                                    $pg == "bon_permintaan_barang" ||
                                    $pg == "laporan_kerusakan_inventaris" ||
                                    $pg == "laporan_kerusakan_kendaraan"||
                                
                                    $pg == "kotak_masuk_surat_pengantar_pengiriman" ||
                                    $pg == "kotak_masuk_bon_permintaan_barang" ||
                                    $pg == "kotak_masuk_laporan_kerusakan_inventaris" ||
                                    $pg == "kotak_masuk_laporan_kerusakan_kendaraan"||
                                
                                    $pg == "kotak_keluar_surat_pengantar_pengiriman" ||
                                    $pg == "kotak_keluar_bon_permintaan_barang" ||
                                    $pg == "kotak_keluar_laporan_kerusakan_inventaris" ||
                                    $pg == "kotak_keluar_laporan_kerusakan_kendaraan" ||
                                    $pg == "permohonan_stpd_add" ||
                                    $pg == "permohonan_stpd_draft" ||
                                    $pg == "permohonan_stpd_draft_revisi" ||
                                    $pg == "permohonan_stpd_draft_di_setujui" ||
                                    $pg == "permohonan_stpd_draft_realisasi" ||
                                    $pg == "permohonan_stpd_draft_realisasi_revisi" ||
                                    $pg == "permohonan_stpd_draft_realisasi_di_setujui" ||
                                    $pg == "permohonan_stpd_status" ||
                                    $pg == "permohonan_stpd_persetujuan"
                                     
                                
                                )
                            ){
                            ?>
                            <!---------- BARU ---------->
                            <div class="satu">
                              <span onclick="location.href='main/index/login';"><i class="fa fa-home fa-lg" style="color: #29b7ea"></i> Home</span>
                            </div>
                                        
                            <div>
                              <span><i class="fa fa-pencil-square-o fa-lg" style="color: #29b7ea"></i> Buat Surat</span>
                              <a class="menu-utama" href="main/index/nota_dinas_add"><i class="fa fa-pencil"></i>Nota Dinas</a>
                              <a class="menu-utama" href="main/index/surat_keluar_add"><i class="fa fa-pencil"></i>Surat Keluar</a>
                              <a class="menu-utama" href="main/index/surat_edaran_add"><i class="fa fa-pencil"></i>Surat Edaran</a>
                              <a class="menu-utama" href="main/index/surat_perintah_add"><i class="fa fa-pencil"></i>Surat Perintah</a>
                              <a class="menu-utama" href="main/index/surat_keputusan_direksi_add"><i class="fa fa-pencil"></i>Surat Keputusan Direksi</a>
                              <a class="menu-utama" href="main/index/keputusan_direksi_add"><i class="fa fa-pencil"></i>Keputusan Direksi</a>
                              <a class="menu-utama" href="main/index/instruksi_direksi_add"><i class="fa fa-pencil"></i>Instruksi Direksi</a>
                              <a class="menu-utama" href="main/index/petikan_skd_add"><i class="fa fa-pencil"></i>Petikan Surat Keputusan Direksi</a>
                              <a class="menu-utama" href="main/index/surat_masuk_manual_add"><i class="fa fa-pencil"></i>Surat Masuk Manual</a>
<!--                               <a class="menu-utama" href="main/index/surat_pengantar_pengiriman"><i class="fa fa-pencil"></i>Surat Pengantar Pengiriman</a>
                              <a class="menu-utama" href="main/index/bon_permintaan_barang"><i class="fa fa-pencil"></i>Bon Permintaan Barang Umum</a>
                              <a class="menu-utama" href="main/index/laporan_kerusakan_inventaris"><i class="fa fa-pencil"></i>Laporan Kerusakan Inventaris</a>
                              <a class="menu-utama" href="main/index/laporan_kerusakan_kendaraan"><i class="fa fa-pencil"></i>Laporan Kerusakan Kendaraan</a> -->
                            </div>
                            <div>
                              <span><i class="fa fa-pencil-square-o fa-lg" style="color: #29b7ea"></i> STPD</span>
                              <a class="menu-utama" href="main/index/permohonan_stpd_add"><i class="fa fa-pencil"></i>Permohonan</a>
                              <!-- <a class="menu-utama" href="main/index/permohonan_stpd_draft"><i class="fa fa-pencil"></i> Draft Permohonan</a>
                              <a class="menu-utama" href="main/index/permohonan_stpd_draft_revisi"><i class="fa fa-pencil"></i> Draft Permohonan Revisi</a>
                              <a class="menu-utama" href="main/index/permohonan_stpd_draft_di_setujui"><i class="fa fa-pencil"></i> Permohonan Disetujui</a>
                              <a class="menu-utama" href="main/index/permohonan_stpd_draft_realisasi"><i class="fa fa-pencil"></i> Draft Realisasi</a>
                              <a class="menu-utama" href="main/index/permohonan_stpd_draft_realisasi_revisi"><i class="fa fa-pencil"></i> Realisasi Permohonan Revisi</a>
                              <a class="menu-utama" href="main/index/permohonan_stpd_draft_realisasi_di_setujui"><i class="fa fa-pencil"></i> Realisasi Permohonan Disetujui</a> -->
                              <a class="menu-utama" href="main/index/permohonan_stpd_status"><i class="fa fa-pencil"></i>Status</a>
                             <!--  <a class="menu-utama" href="main/index/permohonan_stpd"><i class="fa fa-pencil"></i>Kotak Masuk</a>
                              <a class="menu-utama" href="main/index/permohonan_stpd_keluar"><i class="fa fa-pencil"></i>Kotak Keluar</a> -->
                              <a class="menu-utama" href="main/index/permohonan_stpd_persetujuan"><i class="fa fa-pencil"></i>Perlu Persetujuan</a>
                            </div>
                            <div>
                              <span><i class="fa fa-inbox fa-lg" style="color: #29b7ea"></i> Kotak Masuk</span>
                              <a class="menu-utama" href="main/index/kotak_masuk">Semua <span class="badge" id="spanJumlahkotakmasuksemua"></span></a>
                              <a class="menu-utama" href="main/index/kotak_masuk_nota_dinas">Nota Dinas <span class="badge" id="spanJumlahkotakmasuknotadinas"></span></a>
                              <a class="menu-utama" href="main/index/kotak_masuk_surat_keluar">Surat Keluar <span class="badge" id="spanJumlahkotakmasuksuratkeluar"></span></a>
                              <a class="menu-utama" href="main/index/kotak_masuk_edaran">Surat Edaran <span class="badge" id="spanJumlahkotakmasuksuratedaran"></span></a>
                              <a class="menu-utama" href="main/index/kotak_masuk_perintah">Surat Perintah <span class="badge" id="spanJumlahkotakmasuksuratperintah"></span></a>
                              <a class="menu-utama" href="main/index/kotak_masuk_surat_keputusan_direksi">Surat Keputusan Direksi <span class="badge" id="spanJumlahkotakmasuksuratkeputusandireksi"></span></a>
                              <a class="menu-utama" href="main/index/kotak_masuk_keputusan_direksi">Keputusan Direksi <span class="badge" id="spanJumlahkotakmasukkeputusandireksi"></span></a>
                              <a class="menu-utama" href="main/index/kotak_masuk_instruksi_direksi">Instruksi Direksi <span class="badge" id="spanJumlahkotakmasukinstruksidireksi"></span></a>
                              <a class="menu-utama" href="main/index/kotak_masuk_surat_masuk_manual">Surat Masuk Manual <span class="badge" id="spanJumlahkotakmasukmanual"></span></a>
                              <a class="menu-utama" href="main/index/kotak_masuk_disposisi">Disposisi</a>
                              <a class="menu-utama" href="main/index/kotak_masuk_tanggapan">Tanggapan Disposisi</a>
                              <!-- <a class="menu-utama" href="main/index/kotak_masuk_surat_pengantar_pengiriman"><i class="fa fa-pencil"></i>Surat Pengantar Pengiriman</a>
                              <a class="menu-utama" href="main/index/kotak_masuk_bon_permintaan_barang"><i class="fa fa-pencil"></i>Bon Permintaan Barang Umum</a>
                              <a class="menu-utama" href="main/index/kotak_masuk_laporan_kerusakan_inventaris"><i class="fa fa-pencil"></i>Laporan Kerusakan Inventaris</a>
                              <a class="menu-utama" href="main/index/kotak_masuk_laporan_kerusakan_kendaraan"><i class="fa fa-pencil"></i>Laporan Kerusakan Kendaraan</a> -->
                            </div>
                            <div>
                              <span><i class="fa fa-paper-plane fa-lg" style="color: #29b7ea"></i> Kotak Keluar</span>
                              <a class="menu-utama" href="main/index/kotak_keluar"><i class="fa fa-pencil"></i>Semua</a>
                              <a class="menu-utama" href="main/index/kotak_keluar_nota_dinas"><i class="fa fa-pencil"></i>Nota Dinas</a>
                              <a class="menu-utama" href="main/index/kotak_keluar_surat_keluar"><i class="fa fa-pencil"></i>Surat Keluar</a>
                              <a class="menu-utama" href="main/index/kotak_keluar_edaran"><i class="fa fa-pencil"></i>Surat Edaran</a>
                              <a class="menu-utama" href="main/index/kotak_keluar_perintah"><i class="fa fa-pencil"></i>Surat Perintah</a>
                              <a class="menu-utama" href="main/index/kotak_keluar_surat_keputusan_direksi"><i class="fa fa-pencil"></i>Surat Keputusan Direksi</a>
                              <a class="menu-utama" href="main/index/kotak_keluar_keputusan_direksi"><i class="fa fa-pencil"></i>Keputusan Direksi</a>
                              <a class="menu-utama" href="main/index/kotak_keluar_instruksi_direksi"><i class="fa fa-pencil"></i>Instruksi Direksi</a>
                              <a class="menu-utama" href="main/index/kotak_keluar_surat_masuk_manual"><i class="fa fa-pencil"></i>Surat Masuk Manual</a>
                              <a class="menu-utama" href="main/index/kotak_keluar_disposisi"><i class="fa fa-pencil"></i>Disposisi</a> 
                              <a class="menu-utama" href="main/index/kotak_keluar_tanggapan"><i class="fa fa-pencil"></i>Tanggapan Disposisi</a> 
<!--                               <a class="menu-utama" href="main/index/kotak_keluar_surat_pengantar_pengiriman"><i class="fa fa-pencil"></i>Surat Pengantar Pengiriman</a>
                              <a class="menu-utama" href="main/index/kotak_keluar_bon_permintaan_barang"><i class="fa fa-pencil"></i>Bon Permintaan Barang Umum</a>
                              <a class="menu-utama" href="main/index/kotak_keluar_laporan_kerusakan_inventaris"><i class="fa fa-pencil"></i>Laporan Kerusakan Inventaris</a>
                              <a class="menu-utama" href="main/index/kotak_keluar_laporan_kerusakan_kendaraan"><i class="fa fa-pencil"></i>Laporan Kerusakan Kendaraan</a> -->
                            </div>
                            <div>
                              <span><i class="fa fa-spinner fa-lg" style="color: #29b7ea"></i> Kotak Proses <!--<label class="badge" id="spanjumlahpersetujuan"></label>--></span>
                              <a class="menu-utama" href="main/index/perlu_persetujuan"><i class="fa fa-pencil"></i>Perlu Persetujuan </a> 
                              <a class="menu-utama" href="main/index/status"><i class="fa fa-pencil"></i>Status</a>
                              <!-- <a class="menu-utama" href="#"><i class="fa fa-pencil"></i>Pemesan</a> -->
                              <a class="menu-utama" href="main/index/newdraft"><i class="fa fa-pencil"></i> Draft <span class="badge" id="spanJumlahDraft"></span></a>
                              <a class="menu-utama" href="main/index/newdraftmanual"><i class="fa fa-pencil"></i> Draft Manual <span class="badge" id="spanJumlahDraftManual"></span></a>
                           </div>
                           <?
                           }
                           elseif(
                            $pg == "surat_masuk_tu" || 
                            $pg == "surat_masuk_tu_lihat" ||
                            $pg == "surat_keluar_tu" || 
                            $pg == "surat_keluar_tu_lihat" ||
                            $pg == "surat_keluar_tu_nomor"
                            )
                           {
                           ?>
                              <div >
                                <span onclick="location.href='main/index/login';"><i class="fa fa-home fa-lg" style="color: #29b7ea"></i> Home</span>
                              </div>
                              <div>
                                <span>Naskah</span>
                                <a class="menu-utama" href="main/index/surat_masuk_tu">Naskah Masuk</a>
                                <a class="menu-utama" href="main/index/surat_keluar_tu">Naskah Keluar</a>
                            </div>
                            <?
                            }
                            else 
                            {
                            ?>
                            <div>
                                <span onclick="location.href='main/index/login';"><i class="fa fa-home fa-lg" style="color: #29b7ea"></i> Home</span>
                            </div>
                            <?
                            if(in_array("SURAT", explode(",", $this->USER_GROUP)))
                            {
                            ?>
                            <div>
                              <span><i class="fa fa-inbox fa-lg" style="color: #29b7ea"></i> Kotak Masuk</span>
                              <a class="menu-utama" href="main/index/kotak_masuk">Semua <span class="badge" id="spanJumlahkotakmasuksemua"></span></a>
                              <a class="menu-utama" href="main/index/kotak_masuk_nota_dinas">Nota Dinas <span class="badge" id="spanJumlahkotakmasuknotadinas"></span></a>
                              <a class="menu-utama" href="main/index/kotak_masuk_surat_keluar">Surat Keluar <span class="badge" id="spanJumlahkotakmasuksuratkeluar"></span></a>
                              <a class="menu-utama" href="main/index/kotak_masuk_edaran">Surat Edaran <span class="badge" id="spanJumlahkotakmasuksuratedaran"></span></a>
                              <a class="menu-utama" href="main/index/kotak_masuk_perintah">Surat Perintah <span class="badge" id="spanJumlahkotakmasuksuratperintah"></span></a>
                              <a class="menu-utama" href="main/index/kotak_masuk_surat_keputusan_direksi">Surat Keputusan Direksi <span class="badge" id="spanJumlahkotakmasuksuratkeputusandireksi"></span></a>
                              <a class="menu-utama" href="main/index/kotak_masuk_keputusan_direksi">Keputusan Direksi <span class="badge" id="spanJumlahkotakmasukkeputusandireksi"></span></a>
                              <a class="menu-utama" href="main/index/kotak_masuk_instruksi_direksi">Instruksi Direksi <span class="badge" id="spanJumlahkotakmasukinstruksidireksi"></span></a>
                              <a class="menu-utama" href="main/index/kotak_masuk_surat_masuk_manual">Surat Masuk Manual <span class="badge" id="spanJumlahkotakmasukmanual"></span></a>
                              <a class="menu-utama" href="main/index/kotak_masuk_disposisi">Disposisi</a>
                              <a class="menu-utama" href="main/index/kotak_masuk_tanggapan">Tanggapan Disposisi</a>
                              <?
                              if($this->USER_GROUP == "ADMIN" || in_array("ADMIN", explode(",", $this->USER_GROUP)))
                              {
                              ?>
                              <a class="menu-utama" href="main/index/histori_surat">Histori Surat</a>
                              <?
                              }
                              ?>
                            </div> 
                            <div>
                              <span><i class="fa fa-paper-plane fa-lg" style="color: #29b7ea"></i> Kotak Keluar</span>
                              <a class="menu-utama" href="main/index/kotak_keluar"><i class="fa fa-pencil"></i>Semua</a>
                              <a class="menu-utama" href="main/index/kotak_keluar_nota_dinas"><i class="fa fa-pencil"></i>Nota Dinas</a>
                              <a class="menu-utama" href="main/index/kotak_keluar_surat_keluar"><i class="fa fa-pencil"></i>Surat Keluar</a>
                              <a class="menu-utama" href="main/index/kotak_keluar_edaran"><i class="fa fa-pencil"></i>Surat Edaran</a>
                              <a class="menu-utama" href="main/index/kotak_keluar_perintah"><i class="fa fa-pencil"></i>Surat Perintah</a>
                              <a class="menu-utama" href="main/index/kotak_keluar_surat_keputusan_direksi"><i class="fa fa-pencil"></i>Surat Keputusan Direksi</a>
                              <a class="menu-utama" href="main/index/kotak_keluar_keputusan_direksi"><i class="fa fa-pencil"></i>Keputusan Direksi</a>
                              <a class="menu-utama" href="main/index/kotak_keluar_instruksi_direksi"><i class="fa fa-pencil"></i>Instruksi Direksi</a>
                              <a class="menu-utama" href="main/index/kotak_keluar_surat_masuk_manual"><i class="fa fa-pencil"></i>Surat Masuk Manual</a>
                              <a class="menu-utama" href="main/index/kotak_keluar_disposisi"><i class="fa fa-pencil"></i>Disposisi</a> 
                              <a class="menu-utama" href="main/index/kotak_keluar_tanggapan"><i class="fa fa-pencil"></i>Tanggapan Disposisi</a> 
                               <!-- <a class="menu-utama" href="#"><i class="fa fa-pencil"></i>Batal</a>  -->
                            </div>
                            <?
                            }
                            ?>

                            <?
                            if($this->CABANG_ID == "PST")
                            {
                            ?>
                            <div>
                                <span><i class="fa fa-database fa-lg" style="color: #fa9f15"></i> Naskah</span>
                                <a class="menu-utama" href="main/index/jenis_naskah">Jenis Naskah</a>
                                <a class="menu-utama" href="main/index/naskah_template">Naskah Template</a>
                                <a class="menu-utama" href="main/index/sifat_surat">Sifat Surat</a>
                                <a class="menu-utama" href="main/index/disposisi_sifat_surat">Sifat Disposisi</a>
                                <a class="menu-utama" href="main/index/prioritas_surat">Prioritas Surat</a>
                                <a class="menu-utama" href="main/index/media_pengiriman">Media Pengiriman</a>
                                <a class="menu-utama" href="main/index/status_pengiriman">Status Pengiriman</a>
                                <a class="menu-utama" href="main/index/balas_cepat">Balas Cepat</a>
                                <a class="menu-utama" href="main/index/disposisi">Disposisi</a>
                                <a class="menu-utama" href="main/index/daftar_alamat">Daftar Alamat</a>
                                <a class="menu-utama" href="main/index/konten">Konten</a>
                                <a class="menu-utama" href="main/index/peraturan">Peraturan/Dokumen</a>
                                <a class="menu-utama" href="main/index/faq">FAQ</a>
                            </div>
                            
                            <div class="collapsed">
                                <span><i class="fa fa-archive fa-lg" style="color: #fa9f15"></i> Arsip</span>
                                <a class="menu-utama" href="main/index/klasifikasi">Klasifikasi</a>
                                <a class="menu-utama" href="main/index/media_arsip">Media Arsip</a>
                                <a class="menu-utama" href="main/index/tingkat_perkembangan">Tingkat Perkembangan</a>
                                <a class="menu-utama" href="main/index/penyusutan_akhir">Penyusutan Akhir</a>
                                <a class="menu-utama" href="main/index/lokasi">Lokasi Arsip</a>
                            </div>
                            <?
                            }
                            ?>
                                <!-- <a class="menu-utama" href="main/index/lokasi_arsip">Lokasi Arsip</a> -->
                            
                            <div class="collapsed">
                                <span><i class="fa fa-sitemap fa-lg" style="color: #fa9f15"></i> Struktur Organisasi</span>
                                <a class="menu-utama" href="main/index/unit_kerja">Unit Kerja</a>
                                <a class="menu-utama" href="main/index/jabatan_struktural">Jabatan Struktural</a>
                                <a class="menu-utama" href="main/index/kelompok_jabatan">Kelompok Jabatan</a>
                                <a class="menu-utama" href="main/index/jabatan_sementara">Jabatan Sementara</a>
                                <a class="menu-utama" href="main/index/mutasi_pejabat">Mutasi Pejabat</a>
                                <a class="menu-utama" href="main/index/kelompok">Kelompok</a>
                                <a class="menu-utama" href="main/index/setting_mengetahui">Setting Mengetahui</a>
                            </div>
                            
                            <div class="collapsed">
                                <span><i class="fa fa-users fa-lg" style="color: #fa9f15"></i> User Management</span>
                                <a class="menu-utama" href="main/index/pegawai">Pegawai</a>
                                <!-- <a class="menu-utama" href="main/index/non_pegawai">Non Pegawai</a> -->
                                <a class="menu-utama" href="main/index/user_pengelola">User Pengelola</a>
                                <!-- <a class="menu-utama" href="main/index/user_group">User Group</a> -->
                            </div>
                            
                            <div class="collapsed">
                                <span><i class="fa fa-users fa-lg" style="color: #fa9f15"></i> Master LOO - LOI</span>
                                <a class="menu-utama" href="main/index/jenis_perusahaan">Jenis Perusahaan</a>
                                <a class="menu-utama" href="main/index/customer">Customer</a>
                                <a class="menu-utama" href="main/index/produk">Produk</a>
                                <a class="menu-utama" href="main/index/utility_charge">Utility Charge</a>
                                <a class="menu-utama" href="main/index/lantai_loo">Lantai</a>
                                <a class="menu-utama" href="main/index/lokasi_loo">Lokasi</a>
                                <a class="menu-utama" href="main/index/lokasi_loo_detil">Lokasi Detil</a>
                                <!-- <a class="menu-utama" href="main/index/user_pengelola">User Pengelola</a> -->
                                <!-- <a class="menu-utama" href="main/index/non_pegawai">Non Pegawai</a> -->
                            </div>

                            <?
                            if($this->USER_GROUP == "LOOLOI" || in_array("LOOLOI", explode(",", $this->USER_GROUP)))
                            {
                            ?>
                                <div class="collapsed">
                                    <span><i class="fa fa-users fa-lg" style="color: #fa9f15"></i> LOO - LOI</span>
                                    <a class="menu-utama" href="main/index/loo_add">LOO</a>
                                    <a class="menu-utama" href="main/index/loo_draft">Draft LOO</a>
                                    <a class="menu-utama" href="main/index/loo_loi">Monitoring</a>
                                </div>
                            <?
                            }
                            ?>

                            
                            <?
                                }
                            ?>
                        </div>

                    </div>
                </nav>  
                <!-- /. NAV SIDE  -->
                        
            </div>
            <div class="col-lg-10 area-monitoring">
            <?
            }
            ?>
            
                <!--<div class="row area-monitoring-inner" style="background: url(images/bg-texture.jpg) !important; ">-->
                <div class="row area-monitoring-inner">
                    <?=($content ? $content:'')?>
                </div>
                <div class="clearfix"></div>
            </div>  
        </div>
    </div>
    
    <!-- TAB MENU -->
    <script>
    $(document).ready(function(){
        $('ul.tabs li').click(function(){
            var tab_id = $(this).attr('data-tab');
    
            $('ul.tabs li').removeClass('current');
            $('.tab-content').removeClass('current');
    
            $(this).addClass('current');
            $("#"+tab_id).addClass('current');
        })
    })
    </script>
    
    <!-- YAMM -->
    <script>
      $(function() {
        window.prettyPrint && prettyPrint()
        $(document).on('click', '.yamm .dropdown-menu', function(e) {
          e.stopPropagation()
        })
      })
    </script>
    
    <!-- TICKER -->
    <script src="lib/Responsive-jQuery-News-Ticker-Plugin-with-Bootstrap-3-Bootstrap-News-Box/scripts/jquery.bootstrap.newsbox.js" type="text/javascript"></script>

    <script type="text/javascript">
        $(function () {
            $(".demo1").bootstrapNews({
                newsPerPage: 3,
                autoplay: true,
                pauseOnHover:true,
                direction: 'up',
                newsTickerInterval: 4000,
                onToDo: function () {
                    //console.log(this);
                }
            });
            
            $(".demo2").bootstrapNews({
            newsPerPage: 6,
            autoplay: true,
            pauseOnHover: true,
            navigation: false,
            direction: 'up',
            newsTickerInterval: 2500,
            onToDo: function () {
                //console.log(this);
            }
        });
            
        });
    </script>
    
    <!-- jAlert MASTER -->
    <script src="lib/jAlert-master/src/jAlert-v3.min.js"></script>
    <script src="lib/jAlert-master/src/jAlert-functions.min.js"></script> <!-- COMPLETELY OPTIONAL -->
    
    <!-- FIRST TIME VISIT -->
    <!--<script src="lib/first-time-visit/js/jquery.js" type="text/javascript"></script>-->
    <script src="lib/first-time-visit/js/jquery_002.js" type="text/javascript"></script>
    <script type="text/javascript">
    var popupStatus = 0;
    
    //loading popup with jQuery magic!
    function loadPopup(){
        centerPopup();
        //loads popup only if it is disabled
        if(popupStatus==0){
            $("#backgroundPopup").css({
                "opacity": "0.7"
            });
            $("#backgroundPopup").fadeIn("slow");
            $("#popupContact").fadeIn("slow");
            popupStatus = 1;
        }
    }
    
    //disabling popup with jQuery magic!
    function disablePopup(){
        //disables popup only if it is enabled
        if(popupStatus==1){
            $("#backgroundPopup").fadeOut("slow");
            $("#popupContact").fadeOut("slow");
            popupStatus = 0;
        }
    }
    
    //centering popup
    function centerPopup(){
        //request data for centering
        var windowWidth = document.documentElement.clientWidth;  
        var windowHeight = document.documentElement.clientHeight;  
        var windowscrolltop = document.documentElement.scrollTop; 
        var windowscrollleft = document.documentElement.scrollLeft; 
        var popupHeight = $("#popupContact").height();
        var popupWidth = $("#popupContact").width();
        var toppos = windowHeight/2-popupHeight/2+windowscrolltop;
        var leftpos = windowWidth/2-popupWidth/2+windowscrollleft;
        //centering
        $("#popupContact").css({
            "position": "absolute",
            "top": toppos,
            "left": leftpos
        });
        //only need force for IE6
        
        $("#backgroundPopup").css({
            "height": windowHeight
        });
        
    }
    
    </script>
    <style>
    #popupContactClose{
    cursor: pointer;
    text-decoration:none;
    }
    #backgroundPopup{
        display:none;
        position:fixed;
        _position:absolute; /* hack for internet explorer 6*/
        height:100%;
        width:100%;
        top:0;
        left:0;
        background:#000000;
        border:1px solid #cecece;
        z-index:9999;
    }
    @media screen and (max-width:767px) {
        #backgroundPopup{
            border:none;
        }
    }
    #popupContact{
        display:none;
        position:fixed;
        _position:absolute; /* hack for internet explorer 6*/
        height:384px;
        width:408px;
        background:#edece7;
        *border:2px solid #cecece;
        z-index:10000;
        *padding:12px;
        font-size:13px;
        
        -webkit-border-radius: 7px;
        -moz-border-radius: 7px;
        border-radius: 7px;
    }
    @media screen and (max-width:767px) {
        #popupContact{
            width:90%;
            height:80%;
        }
    }
    #popupContact .header{
        background:#fefb00;
        display:inline-block;
        width:100%;
        
        -webkit-border-top-left-radius: 7px;
        -webkit-border-top-right-radius: 7px;
        -moz-border-radius-topleft: 7px;
        -moz-border-radius-topright: 7px;
        border-top-left-radius: 7px;
        border-top-right-radius: 7px;
        
        border-bottom:1px solid #d0d0d0;
    
    }
    #popupContact h1{
        text-align:left;
        color:#333;
        font-size:18px;
        text-transform:uppercase;
        padding:0 20px;
        *font-weight:700;
        
        padding-bottom:2px;
        *margin-bottom:20px;
        
        *background:red;
    }
    #popupContactClose{
        font-size:14px;
        line-height:14px;
        right:10px;
        top:10px;
        position:absolute;
        color:red;
        font-weight:700;
        display:block;
    }
    #popupContact .sub-header{
        background:#dbdbdb;
        padding:10px 20px;
        font-size:14px;
        font-weight:bold;
    }
    #contactArea{
        height:280px !important;
        overflow:auto !important;
    }
    #contactArea ul{
        list-style-type:decimal;
        *border:1px solid cyan;
        
        *padding:0 0;
        padding-right:20px;
    }
    #contactArea ul li{
        border-bottom:1px solid rgba(0,0,0,0.05);
        padding:10px 0 10px 20px;
        *display:inline-block;
        *width:100%;
        
        clear:both;
        float:left;
        width:100%;
    }
    #contactArea ul li span{
        float:left;
        width:calc(100% - 60px);
        font-size:14px;
        padding-right:15px;
    }
    #contactArea ul li a{
        float:right;
        width:60px;
        height:60px;
        line-height:60px;
        text-align:center;
        border:1px solid rgba(255,255,255,0.5);
        
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 4px;
    
    }
    #contactArea ul li i{
        display:inline-block;
        *border:1px solid red;
        line-height:60px;
        color:#999;
        
    }
    @media screen and (max-width:767px) {
        #contactArea{
            overflow:auto;
            *border:1px solid red;
            *max-height:calc(100vh - 230px);
            height:calc(100% - 100px);
        }
    }
    
    </style>
    
    <?
    if($this->session->userdata("AFTER_LOGIN") == "1" && ($this->USER_GROUP_ID == "1" || $this->USER_GROUP_ID == "2"))
    {
        $this->session->set_userdata("AFTER_LOGIN","");
    ?>
        <script>
        //CONTROLLING EVENTS IN jQuery
        $(document).ready(function(){
            
            setTimeout("loadPopup()",1000); 
            
            $("#popupContactClose").click(function(){
                disablePopup();
            });
            //Click out event!
            $("#backgroundPopup").click(function(){
                disablePopup();
            });
            //Press Escape event!
            $(document).keypress(function(e){
                if(e.keyCode==27 && popupStatus==1){
                    disablePopup();
                }
            });
            
            //Click pegawai alpha!
            $("#reqPegawaiAlpha").click(function(){
                <?
                $this->session->set_userdata("PEGAWAI_ALPHA", 1);
                ?>
                document.location.href = "app/admin/";
            });
        
        }); 
        
        </script>    
        <?
        $this->load->model('AbsensiRekap');

        $absensi_rekap = new AbsensiRekap();
        $absensi_rekap_count = new AbsensiRekap();
        
        if($this->USER_GROUP_ID == 1)
            $statement_privacy = " AND A >= 3 ";
        elseif($this->USER_GROUP_ID == 2)
            $statement_privacy = " AND A >= 3 AND A.CABANG_ID = '".$this->KODE_CABANG."'";
        
        $jumlah_data = $absensi_rekap_count->getCountByParamsRekapKehadiranKoreksiNotif(date("mY"), array(), $statement_privacy);
        $absensi_rekap->selectByParamsRekapKehadiranKoreksiSumCabang(date("mY"), array(), -1, -1, $statement_privacy, " ORDER BY CABANG_ID ASC");   
        if($jumlah_data > 0)
        {
            
        ?>
            <div id="popupContact">
                <div class="header">
                    <a id="popupContactClose"><i class="fa fa-times-circle fa-2x" aria-hidden="true"></i></a>
                    <h1><i class="fa fa-bell" aria-hidden="true"></i> Notifikasi</h1>
                </div>
                
                <div class="sub-header" id="jumlah-notifikasi-popup"><a id="reqPegawaiAlpha">Terdapat <?=$jumlah_data?> pegawai yang alpha lebih dari 3 hari</a></div>
                
                <div id="contactArea">
                    <ul>
                    <?
                    while($absensi_rekap->nextRow())
                    {
                    ?>
                        <li>
                            <span><?=$absensi_rekap->getField("NAMA_CABANG")?> = <?=$absensi_rekap->getField("JUMLAH")?> Karyawan</span>
                        </li>
                    <?
                    }                
                    ?>
                    </ul>
                </div>
                
            </div>
            <div id="backgroundPopup"></div>   
    <?
        }
    }
    ?>
    <!-- SHOUTBOX -->
    <?
    if($pg == "login")
    {
    ?>
    <script type="text/javascript" src="lib/shoutbox2/javascript/jquery.js"></script>
    <script type="text/javascript" src="lib/shoutbox2/javascript/jquery.form.js"></script>
    <?
    }
    ?>
    
    <?
    if($pg == "login")
    {} else {
    ?>
    <!-- SDMENU / menu kiri 
    <script type="text/javascript" src="lib/time/time.js"></script>-->
    <link rel="stylesheet" type="text/css" href="lib/sdmenu/sdmenu.css" />
    <script type="text/javascript" src="lib/sdmenu/sdmenu.js"></script>
    <script type="text/javascript">
    // <![CDATA[
    var myMenu;
    window.onload = function() {
        //goforit();
        myMenu = new SDMenu("my_menu");
        myMenu.init();
    };
    // ]]>
    </script>
    <?
    }
    ?>
    
    <!-- LIVE CHAT -->
    <?php /*?><script>
    $(document).ready(function(){
        $(".area-live-chat").hide();
        $('.area-live-chat-button').click(function(event) {
            $(".area-live-chat").toggle(500);
            disabledEventPropagation(event);
            //console.log('2nd event');
        });
        
        $('.area-live-chat').click(function(event) {
            disabledEventPropagation(event);
            //console.log('3rd event');
        });
    
        $('.fa-times').click(function(event) {
            $(".area-live-chat").hide(500);
        });
    
    
        $(document).click(function() {
            $(".area-live-chat").hide(500);
            //console.log('1st event');
        });
        
        $("i.fa-paperclip").click(function () {
            $("input[type='file'].browse").trigger('click');
        });
        
        function disabledEventPropagation(event) {
            if (event.stopPropagation) {
                event.stopPropagation();
            } else if (window.event) {
                window.event.cancelBubble = true;
            }
        }
    
    });
    </script><?php */?>
    
    <script>
    
    $('input[type="file"].browse').on('change', function() {
      var val = $(this).val();
      $(this).siblings('span').text(val);
    })
    </script>
    
    <!-- EMODAL -->
    <?php /*?><script src="libraries/emodal/eModal.js"></script>
    <script src="libraries/emodal/eModal-cabang.js"></script>
    
    <script>
    
    function openAdd(pageUrl) {
        //alert("hai");
        eModal.iframe(pageUrl, 'Aplikasi E-Office - PT. Indonesia Ferry Property ')
    }
    function openCabang(pageUrl) {
        eModalCabang.iframe(pageUrl, 'Aplikasi E-Office - PT. Indonesia Ferry Property ')
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
    
    </script><?php */?>
    
    <script>
    function ubahRole(e)
    {
        konfirmasiPostReload("Ubah role menjadi " + e.value + "?", 'login/change', e.value);
    }

    $( document ).ready(function() {
        <?
        $arrexcept= array("login", "login");
        if(in_array($pg, $arrexcept))
        {
        ?>
           getJumlahSurat(0, "INTERNAL");
        <?
        }
        ?>
    });
    
    function getJumlahSurat(id, jenisSurat)
    {
        <?
        // if(in_array("SURAT", explode(",", $this->USER_GROUP)))
        // {
        ?>
        // $('[id^="spanJumlah"]').hide();
        <?
        // }
        // else
        // {
        ?>
        $.get( "app/getJumlahSurat/?reqId="+id+"&reqJenisSurat="+jenisSurat, function( data ) {
            $("#spanJumlahInbox").text(data.JUMLAH_INBOX);
            $("#spanJumlahValidasi").text(data.JUMLAH_VALIDASI);
            $("#spanJumlahDraft").text(data.JUMLAH_DRAFT);
            $("#spanjumlahpersetujuan").text(data.JUMLAH_PERSETUJUAN);
            $("#spanJumlahDraftManual").text(data.JUMLAH_DRAFT_MANUAL);

            $("#spanJumlahkotakmasuksemua").text(data.JUMLAH_KOTAK_MASUK_SEMUA);
            $("#spanJumlahkotakmasuknotadinas").text(data.JUMLAH_KOTAK_MASUK_NOTA_DINAS);
            $("#spanJumlahkotakmasuksuratkeluar").text(data.JUMLAH_KOTAK_MASUK_SURAT_KELUAR);
            $("#spanJumlahkotakmasuksuratedaran").text(data.JUMLAH_KOTAK_MASUK_SURAT_EDARAN);
            $("#spanJumlahkotakmasuksuratkeputusandireksi").text(data.JUMLAH_KOTAK_MASUK_SURAT_KEPUTUSAN_DIREKSI);
            $("#spanJumlahkotakmasukkeputusandireksi").text(data.JUMLAH_KOTAK_MASUK_KEPUTUSAN_DIREKSI);
            $("#spanJumlahkotakmasukinstruksidireksi").text(data.JUMLAH_KOTAK_MASUK_INSTRUKSI_DIREKSI);
            $("#spanJumlahkotakmasuksuratperintah").text(data.JUMLAH_KOTAK_MASUK_SURAT_PERINTAH);
            $("#spanJumlahkotakmasukmanual").text(data.JUMLAH_KOTAK_MASUK_MANUAL);
        // keluar
        }, "json" );
        <?
        // }
        ?>
    }

    function changeSatker(e)
    {
        konfirmasiPostReload2("Ubah jabatan menjadi " + $("#reqSatker option:selected").text() + " ?", 'login/change_satker', e.value);
    }
    
    </script>
    
</body>

</html>
