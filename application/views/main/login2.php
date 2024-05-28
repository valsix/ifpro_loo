

<script>
	
	$(function(){	
		$('#slick-toggle').click(function() {
			$("#reqBrowse").click();
		});
	});	
	function uploadFile(){
		$("#reqSubmit").click();
	}
</script>

<style>
body{
	overflow: hidden;
}
@media screen and (max-width:767px) {
		body{
			overflow: auto;
		}
}
</style>

<div class="col-lg-3 col-kiri">
    <div class="user-profil-area">
        <div class="area-foto">
            <div class="foto">
                <img id="slick-toggle" class="img-responsive" src="<?=getFotoProfile($this->USERNAME)?>" />
            </div>
        </div>
        <div class="area-nama">
            <div class="nama"><?=$this->NAMA?></div>
            <div class="id">( <?=$this->ID?> )</div>
            <div class="jabatan"><?=$this->JABATAN?></div>
        </div>
        <div class="clearfix"></div>
        <div class="area-cabang-departemen">
            <div class="cabang"><?=$this->CABANG?></div>
            <div class="departemen"><?=$this->DEPARTEMEN?> <?=$this->SUB_DEPARTEMEN?></div>
        </div>
    </div>
</div>
<div class="col-lg-9 col-kanan">
    <div class="row">
        <div class="col-md-6">
            <div class="area-personal-bawah row">
                <div class="area-menu-dashboard col-md-6" onClick="window.location = 'main/index/kotak_masuk';">
                    <div class="ikon"><i class="fa fa-inbox fa-3x" style="color: #6baa18;"></i></div>
                    <div class="data">
                        <div class="judul">Surat Masuk<br><span>belum terbaca</span></div>
                        <div class="nilai"><?php /*?><?=$jumlah_surat_masuk?><?php */?><span id="spanJumlahkotakmasuksemua"></span></div>
                    </div>
                </div>
                <div class="area-menu-dashboard col-md-6" onClick="window.location = 'main/index/kotak_masuk_disposisi';">
                    <div class="ikon"><i class="fa fa-share fa-2x" style="color: #0162b7;"></i></div>
                    <div class="data">
                        <div class="judul">Disposisi<!-- & Tanggapan--></div>
                        <div class="nilai"><?php /*?><?=$jumlah_disposisi ?><?php */?><?=$jumlah_disposisi ?></div>
                    </div>
                </div>
                <div class="area-menu-dashboard col-md-6" onClick="window.location = 'main/index/perlu_persetujuan';">
                    <div class="ikon"><i class="fa fa-file-text fa-2x" style="color: #ef5702;"></i></div>
                    <div class="data">
                        <div class="judul">Perlu Persetujuan</div>
                        <div class="nilai"><?php /*?><?=$jumlah_validasi?><?php */?><span id="spanjumlahpersetujuan"></span></div>
                    </div>
                </div>
                <div class="area-menu-dashboard col-md-6">
                    <div class="ikon"><i class="fa fa-archive fa-2x" style="color: #7a7a7a;"></i></div>
                    <div class="data">
                        <div class="judul">Status Delegasi</div>
                        <div class="nilai"><!--1-->-</div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="col-md-6">
            <div id="container" class="container-rekap-surat" style="height: 270px;"></div>
        </div>
    </div>
    <div class="row">
        <!--<div class="col-lg-8">-->
        <div class="col-md-6">
            <div class="area-kalender-libur">
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#kalender">AGENDA KEGIATAN</a></li>
                    <!--<li><a data-toggle="tab" href="#libur">LIBUR &amp; CUTI BERSAMA</a></li>-->
                </ul>
                <div class="tab-content">
                    <div id="kalender" class="tab-pane fade in active">
                        <div class="kalender-area" style="display:inline-table;">
                            <!-- Responsive calendar - START -->
                            <div class="responsive-calendar">
                                <div class="controls" style="display:inline-block; width:100%;">
                                    <a class="pull-left" data-go="prev"><div class="btn btn-primary"><img src="images/left-arrow.png"></div></a>
                                    <div class="bulan-tahun">
                                        <span data-head-year></span>
                                        <span data-head-month></span>                                        
                                    </div>
                                    <a class="pull-right" data-go="next"><div class="btn btn-primary"><img src="images/right-arrow.png"></div></a>
                                </div>
                                <div class="day-headers">
                                    <div class="day header">Mon</div>
                                    <div class="day header">Tue</div>
                                    <div class="day header">Wed</div>
                                    <div class="day header">Thu</div>
                                    <div class="day header">Fri</div>
                                    <div class="day header">Sat</div>
                                    <div class="day header">Sun</div>
                                </div>
                                <div class="days" data-group="days"></div>
                            </div>
                            <!-- Responsive calendar - END -->
                        </div>
                    </div>
                    <div id="libur" class="tab-pane fade">
                        <h3>Menu 1</h3>
                        <p>Some content in menu 1.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="area-peraturan">
                <h4>Peraturan &amp; Download Dokumen</h4>
                <?
                while ($peraturan->nextRow()) 
                {
                ?>
                <div class="item">
                    <div class="ikon-dokumen"><img src="images/icon-pdf.png"></div>
                    <div class="data">
                        <div class="nomor"><?=$peraturan->getField("NOMOR")?></div>
                        <div class="nama"><?=$peraturan->getField("NAMA")?></div>
                    </div>
                    <div class="ikon-download-dokumen"><a target="_blank" href="<?=base_url()."uploads/".$peraturan->getField("LINK_FILE")?>"><img src="images/icon-download-dokumen.png"></a></div>
                    <div class="clearfix"></div>
                </div>
                <?  
                }
                ?>
            </div>
        </div>
    </div>
</div>
       
<!-- RESPONSIVE CALENDAR -->
<link href="lib/responsive-calendar-0.9/css/responsive-calendar.css" rel="stylesheet">
<script src="lib/responsive-calendar-0.9/js/responsive-calendar.js"></script>
<script type="text/javascript">
 
  $(document).ready(function () {
      
    function addLeadingZero(num) {
      if (num < 10) {
        return "0" + num;
      } else {
        return "" + num;
      }
    }
              
    $(".responsive-calendar").responsiveCalendar({
      time: '<?=date("Y-m")?>',
      events: {
        //"":{}
			"2020-08-30": {},
			"2020-08-26": {}, 
			"2020-08-03": {}, 
			"2020-08-12": {},
            "2020-10-01": {"number": 5, "badgeClass": "badge-warning"},
            "2020-10-02": {"number": 1, "badgeClass": "badge-warning"}, 
            "2020-10-03": {"number": 1, "badgeClass": "badge-error"}
       
        },
        onDayClick: function(events) {
            // var arrKeterangan = new Array();
            // <?=$data?>
        
            // key = $(this).data('year')+'-'+addLeadingZero( $(this).data('month') )+'-'+addLeadingZero( $(this).data('day') );
            // successAlert(arrKeterangan[$(this).data('day')]); return false;
            //alert(arrKeterangan[$(this).data('day')]);
            //alert($(this).data('month')+''+$(this).data('day'));
            // alert('Keterangan',arrKeterangan[$(this).data('year')+''+addLeadingZero( $(this).data('month') )+''+$(this).data('day')]);
            var thisDayEvent, key;

            key = $(this).data('year')+'-'+addLeadingZero( $(this).data('month') )+'-'+addLeadingZero( $(this).data('day') );
            thisDayEvent = events[key];
            alert(JSON.stringify(thisDayEvent));
            // alert(JSON.stringify($(this).data()))
            //alert('hai');
       }
    });
    
  });
</script>

<!-- HIGHCHART -->
<script src="lib/highcharts/highcharts.js"></script>
<script src="lib/highcharts/exporting.js"></script>
<script src="lib/highcharts/export-data.js"></script>
<script src="lib/highcharts/accessibility.js"></script>

<script>
Highcharts.chart('container', {
    chart: {
        type: 'column',
		backgroundColor: null 
    },
	exporting: {
		enabled: false
	},
    title: {
        //text: 'Monthly Average Rainfall'
		text: 'Rekap Surat Perbulan'
    },
    subtitle: {
        //text: 'Source: WorldClimate.com'
		text: null
    },
    xAxis: {
        categories: [
            'Jan',
            'Feb',
            'Mar',
            'Apr',
            'May',
            'Jun',
            'Jul',
            'Aug',
            'Sep',
            'Oct',
            'Nov',
            'Dec'
        ],
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            //text: 'Rainfall (mm)'
			text: null
        }
    },
    tooltip: {
        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
            //'<td style="padding:0"><b>{point.y:.1f} mm</b></td></tr>',
			'<td style="padding:0"><b>{point.y:.1f} </b></td></tr>',
        footerFormat: '</table>',
        shared: true,
        useHTML: true
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0
        }
    },
    series: [{
        name: 'Surat Masuk',
        data: [49, 71, 106, 129, 144, 176, 135, 148, 216, 194, 95, 54],
		color: "#6baa18"

    }, {
        name: 'Disposisi & Tanggapan',
        data: [83, 78, 98, 93, 106, 84, 105, 104, 91, 83, 106, 92],
		color: "#0162b7"

    }, {
        name: 'Perlu Persetujuan',
        data: [48, 38, 39, 41, 47, 48, 59, 59, 52, 65, 59, 51],
		color: "#ef5702"

    }, {
        name: 'Status Delegasi',
        data: [42.4, 33.2, 34.5, 39.7, 52.6, 75.5, 57.4, 60.4, 47.6, 39.1, 46.8, 51.1],
		color: "#7a7a7a"

    }]
});
</script>
  