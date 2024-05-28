<?
include_once("functions/string.func.php");
$this->load->model("Pegawai");
$this->load->model("PegawaiStatusPegawai");
$this->load->model("Cabang");

$pegawai = new Pegawai();
$pegawai_kehadiran = new Pegawai();
$cabang = new Cabang();
$pegawai_status_pegawai = new PegawaiStatusPegawai();
$pegawai_umur = new Pegawai();

$total_pegawai = $pegawai->getCountByParamsEllipse(array(), " AND A.EMP_STATUS = 'A'");
$total_pegawai = numberToIna($total_pegawai);

$pegawai->selectByParamsJenisKelamin(array(), -1, -1, " AND A.EMP_STATUS = 'A'");
while($pegawai->nextRow())
{
	if($pegawai->getField("JENIS_KELAMIN") == "L")
	//if($pegawai->getField("GENDER") == "M")
		$total_laki = $pegawai->getField("JUMLAH");
	else
		$total_perempuan = $pegawai->getField("JUMLAH");
}

$pegawai_status_pegawai->selectByParamsGrafik(array(), -1, -1, " AND C.EMP_STATUS = 'A'");
//echo $pegawai_status_pegawai->query;
$pegawai_status_pegawai->firstRow();

$statement_cabang = " AND B.CABANG_ID NOT IN ('AK', 'ST', 'MKP', 'UMT', 'UPT', 'UBT', 'SKP', 'TJ', 'UGR', 'UMK', 'BE', 'SP', 'LB', 'PJB', 'PSC') ";
$pegawai_kehadiran->selectByParamsGrafikPresensi(array(), -1, -1, $statement_cabang);
$i = 0;
while($pegawai_kehadiran->nextRow())
{
	$arrPegawaiKehadiran[$i]["CABANG_ID"] = $pegawai_kehadiran->getField("CABANG_ID");
	$arrPegawaiKehadiran[$i]["NAMA"] = $pegawai_kehadiran->getField("NAMA");
	$arrPegawaiKehadiran[$i]["JUMLAH_PEGAWAI"] = $pegawai_kehadiran->getField("JUMLAH_PEGAWAI");
	$arrPegawaiKehadiran[$i]["HADIR"] = $pegawai_kehadiran->getField("HADIR");
	$arrPegawaiKehadiran[$i]["ALPHA"] = $pegawai_kehadiran->getField("ALPHA");
	
	$i++;	
}


$pegawai_umur->selectByParamsGrafikUmur(array(), -1, -1, " AND EMP_STATUS = 'A'");
$cabang->selectByParams();

?>


<link rel="stylesheet" href="css/home.css" type="text/css" />

<!-- SCROLLING TABLE MASTER -->
<link rel="stylesheet" href="lib/ScrollingTable-master/style.css" />


    <div class="col-lg-6">    
    	
        <div class="area-data-korporat">
        	<div class="area-jenis-kelamin">
            	<h4>Jenis Kelamin</h4>
                <div class="data">
                	<div class="laki-laki">
                    	<div class="ikon"><img src="images/icon-laki-laki.png"></div>
                        <div class="nilai">
                        	<span>Laki-laki</span>
                            <?=$total_laki?>
                        </div>
                    </div>
                	<div class="perempuan">
                    	<div class="ikon"><img src="images/icon-perempuan.png"></div>
                        <div class="nilai">
                        	<span>Perempuan</span>
                            <?=$total_perempuan?>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="area-total-pegawai">
            	<h4>Total Pegawai</h4>
                <div class="data"><img src="images/icon-total-pegawai.png"> <span><?=$total_pegawai?></span></div>
            </div>
            <div class="clearfix"></div>
            <div class="area-jenis-pegawai">
            	<h4>Jenis Pegawai</h4>
                <div class="inner">
                	<div class="data">
                    	<div class="item">
                            <div class="judul">Organik</div>
                            <div class="nilai"><?=$pegawai_status_pegawai->getField("JUMLAH_ORGANIK")?></div>
                        </div>
                    	<div class="item">
                            <div class="judul">Non-Organik</div>
                            <div class="nilai"><?=$pegawai_status_pegawai->getField("JUMLAH_NON_ORGANIK")?></div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="area-usia-rata2">
            	<h4>Usia Rata-rata</h4>
                <div class="inner">
                	<div class="grafik">
                		<div id="container"></div>
                    </div>
                    <div class="legend">
                        <?
                        $i = 0;
                        while($pegawai_umur->nextRow())
                        {
                            $arrUmur[$i]["KETERANGAN"] = "Usia ".$pegawai_umur->getField("KETERANGAN");
                            $arrUmur[$i]["PROSENTASE"] = $pegawai_umur->getField("PROSENTASE");
                        ?>
                            <div class="item">
                                <div class="judul">Usia <?=$pegawai_umur->getField("KETERANGAN")?></div>
                                <div class="nilai"><?=numberToIna($pegawai_umur->getField("JUMLAH"))?></div>
                            </div>
                       <?
                            $i++;
                       }
                       ?>    
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="clearfix"></div>
            
            <div class="area-peraturan">
            	<h4>Peraturan &amp; Download Dokumen</h4>
            	<div class="item">
                    <div class="ikon-dokumen"><img src="images/icon-pdf.png"></div>
                    <div class="data">
                        <div class="nomor">SK 057.K/010/DIR/2015</div>
                        <div class="nama">Penambahan Ketentuan Masuk Kerja Karyawan</div>
                    </div>
                    <div class="ikon-download-dokumen"><a href="#"><img src="images/icon-download-dokumen.png"></a></div>
                    <div class="clearfix"></div>
                </div>
            	<div class="item">
                    <div class="ikon-dokumen"><img src="images/icon-pdf.png"></div>
                    <div class="data">
                        <div class="nomor">SK 030.K/020/DIR/2017</div>
                        <div class="nama">Penetapan Pola Shift Operator</div>
                    </div>
                    <div class="ikon-download-dokumen"><a href="#"><img src="images/icon-download-dokumen.png"></a></div>
                    <div class="clearfix"></div>
                </div>
            	<div class="item">
                    <div class="ikon-dokumen"><img src="images/icon-pdf.png"></div>
                    <div class="data">
                        <div class="nomor">SK 098.K/010/DIR/2014</div>
                        <div class="nama">Ketentuan Masuk Kerja Kayawan</div>
                    </div>
                    <div class="ikon-download-dokumen"><a href="#"><img src="images/icon-download-dokumen.png"></a></div>
                    <div class="clearfix"></div>
                </div>
            	<div class="item">
                    <div class="ikon-dokumen"><img src="images/icon-pdf.png"></div>
                    <div class="data">
                        <div class="nomor">SK 086.K/010/DIR/2014</div>
                        <div class="nama">Cuti Karyawan</div>
                    </div>
                    <div class="ikon-download-dokumen"><a href="#"><img src="images/icon-download-dokumen.png"></a></div>
                    <div class="clearfix"></div>
                </div>
            	<div class="item">
                    <div class="ikon-dokumen"><img src="images/icon-pdf.png"></div>
                    <div class="data">
                        <div class="nama">Panduan penggunaan Aplikasi Presensi</div>
                    </div>
                    <div class="ikon-download-dokumen"><a href="#"><img src="images/icon-download-dokumen.png"></a></div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    	
    </div>
    
    <div class="col-lg-6 peta-cabang-area">
    	<div class="judul-halaman">Grafik Kehadiran</div>
    	<div id="grafik-kehadiran"></div>
        <div class="area-rekap-kehadiran">
        	<h4>Rekap Kehadiran</h4>
            <table id="Demo" style="width: 100%">
                <tr>
                    <th class="headerHor">No</th>
                    <th class="headerHor">Kode</th>
                    <th class="headerHor">Unit</th>
                    <th class="headerHor">Total</th>
                    <th class="headerHor">Hadir</th>
                    <th class="headerHor">Tidak Hadir</th>
                </tr>
                <?
                $i = 1;
                for($x=0;$x<count($arrPegawaiKehadiran);$x++)
                {
                    if($x == 0)
                    {
                        $cabangRekap = $arrPegawaiKehadiran[$x]["CABANG_ID"];
                        $cabangRekapHadir = $arrPegawaiKehadiran[$x]["HADIR"];
                        $cabangRekapAlpha = $arrPegawaiKehadiran[$x]["ALPHA"];
                    }
                    else
                    {
                        $cabangRekap      .= ",".$arrPegawaiKehadiran[$x]["CABANG_ID"];
                        $cabangRekapHadir .= ",".$arrPegawaiKehadiran[$x]["HADIR"];
                        $cabangRekapAlpha .= ",".$arrPegawaiKehadiran[$x]["ALPHA"];
                    }
                ?>
                <tr>
                    <td class="headerVer"><?=$i?></td>
                    <td><?=$arrPegawaiKehadiran[$x]["CABANG_ID"]?></td>
                    <td class="source-img"><span data-src="images/cabang/<?=$arrPegawaiKehadiran[$x]["CABANG_ID"]?>.jpg" class="source-img"><?=$arrPegawaiKehadiran[$x]["NAMA"]?></span></td>
                    <td><?=$arrPegawaiKehadiran[$x]["JUMLAH_PEGAWAI"]?></td>
                    <td><?=$arrPegawaiKehadiran[$x]["HADIR"]?></td>
                    <td><span class="alpha"><?=$arrPegawaiKehadiran[$x]["ALPHA"]?></span></td>
                </tr>
                <?
                    $i++;
                }
                ?>
            </table>
        </div>
    
        <!-- SCRIPT -->
        <!--<script src="lib/ScrollingTable-master/jquery.min.js"></script>-->
        <script src="lib/ScrollingTable-master/scrollingtable.js"></script>
        <script>
            $('#Demo').ScrollingTable();
        </script>
        
	</div>
</div>


<div class="row">
	<div class="col-lg-12">
        <div class="area-footer">
        	<div class="ikon"><img src="images/icon-building.png"></div>
            <div class="data">
        		<strong>KANTOR PUSAT</strong><br>
                Jl. Raya Bandara Juanda No.17<br>					
                Kabupaten Sidoarjo, Jawa Timur (61253)   
            </div>
            <div class="data">
            	<br><br>
            	Telp. (+62-31) <strong>8548391</strong>		
            </div>
            <div class="data">
            	<br><br>
            	Fax. (+62-31) <strong>8548360</strong>	
            </div>
            <div class="data">
            	<br><br>
            	E-Mail. <strong>info@pjbservices.com</strong>
            </div>
            <div class="clearfix"></div> 
        </div>
        
        											
    </div>
<!--</div>-->

<!--<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>-->
	<script src="lib/highcharts/highcharts.js"></script>
    <script src="lib/highcharts/exporting.js"></script>
    <script src="lib/highcharts/export-data.js"></script>
    <script src="lib/highcharts/accessibility.js"></script>
    
<script>
// Build the chart
Highcharts.chart('container', {
  chart: {
	plotBackgroundColor: null,
	plotBorderWidth: null,
	plotShadow: false,
	type: 'pie',
	
	backgroundColor: null 
  },
  title: {
	text: null
  },
	exporting: {
		 enabled: false
	},
	legend: {
        enabled: false
    },
  tooltip: {
    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
  },
  accessibility: {
    point: {
      valueSuffix: '%'
    }
  },
  plotOptions: {
    pie: {
      allowPointSelect: true,
      cursor: 'pointer',
      dataLabels: {
        enabled: false
	  },
      showInLegend: true,
	  borderWidth: 0 // < set this option
    }
  },
  series: [{
    name: 'Persentase',
    colorByPoint: true,
    data: [{
      name: '<?=$arrUmur[0]["KETERANGAN"]?>',
      y: <?=$arrUmur[0]["PROSENTASE"]?>,
      sliced: true,
      selected: true,
	  color: "#005cbd"
    }, {
      name: '<?=$arrUmur[1]["KETERANGAN"]?>',
      y: <?=$arrUmur[1]["PROSENTASE"]?>,
	  color: "#034489"
    }, {
      name: '<?=$arrUmur[2]["KETERANGAN"]?>',
      y: <?=$arrUmur[2]["PROSENTASE"]?>,
	  color: "#8c9097"
    }, {
      name: '<?=$arrUmur[3]["KETERANGAN"]?>',
      y: <?=$arrUmur[3]["PROSENTASE"]?>,
	  color: "#ffffff"
    }]
  }]
});
</script>

<!--- GRAFIK KEHADIRAN -->
<script>
$(function() {
  'use strict';
(function(factory) {
	if(typeof module === 'object' && module.exports) {
		module.exports = factory;
	} else {
		factory(Highcharts);
	}
}(function(Highcharts) {
	(function(H) {
		H.wrap(H.seriesTypes.column.prototype, 'translate', function(proceed) {
			const options = this.options;
			const topMargin = options.topMargin || 0;
			const bottomMargin = options.bottomMargin || 0;

			proceed.call(this);

			H.each(this.points, function(point) {
				if(options.borderRadiusTopLeft || options.borderRadiusTopRight || options.borderRadiusBottomRight || options.borderRadiusBottomLeft) {
					const w = point.shapeArgs.width;
					const h = point.shapeArgs.height;
					const x = point.shapeArgs.x;
					const y = point.shapeArgs.y;

					let radiusTopLeft = H.relativeLength(options.borderRadiusTopLeft || 0, w);
					let radiusTopRight = H.relativeLength(options.borderRadiusTopRight || 0, w);
					let radiusBottomRight = H.relativeLength(options.borderRadiusBottomRight || 0, w);
					let radiusBottomLeft = H.relativeLength(options.borderRadiusBottomLeft || 0, w);

					const maxR = Math.min(w, h) / 2

					radiusTopLeft = radiusTopLeft > maxR ? maxR : radiusTopLeft;
					radiusTopRight = radiusTopRight > maxR ? maxR : radiusTopRight;
					radiusBottomRight = radiusBottomRight > maxR ? maxR : radiusBottomRight;
					radiusBottomLeft = radiusBottomLeft > maxR ? maxR : radiusBottomLeft;

					point.dlBox = point.shapeArgs;

					point.shapeType = 'path';
					point.shapeArgs = {
						d: [
							'M', x + radiusTopLeft, y + topMargin,
							'L', x + w - radiusTopRight, y + topMargin,
							'C', x + w - radiusTopRight / 2, y, x + w, y + radiusTopRight / 2, x + w, y + radiusTopRight,
							'L', x + w, y + h - radiusBottomRight,
							'C', x + w, y + h - radiusBottomRight / 2, x + w - radiusBottomRight / 2, y + h, x + w - radiusBottomRight, y + h + bottomMargin,
							'L', x + radiusBottomLeft, y + h + bottomMargin,
							'C', x + radiusBottomLeft / 2, y + h, x, y + h - radiusBottomLeft / 2, x, y + h - radiusBottomLeft,
							'L', x, y + radiusTopLeft,
							'C', x, y + radiusTopLeft / 2, x + radiusTopLeft / 2, y, x + radiusTopLeft, y,
							'Z'
						]
					};
				}

			});
		});
	}(Highcharts));
}));

Highcharts.chart('grafik-kehadiran', {
    chart: {
        type: 'column',
		backgroundColor: null 
    },
	title: {
		text: null
	},
	
	exporting: {
		 enabled: false
	},
	legend: {
        enabled: false
    },
	
    xAxis: {
		categories: ['<?=str_replace(",","','", $cabangRekap)?>']
	},
	yAxis: {
		min: 0,
		title: {
			text: null
		  //text: 'Total fruit consumption'
		},
		stackLabels: {
		  enabled: true,
		  style: {
			fontWeight: 'bold',
			color: ( // theme
			  Highcharts.defaultOptions.title.style &&
			  Highcharts.defaultOptions.title.style.color
			) || 'gray'
		  }
		}
	},

    plotOptions: {
		column: {
			grouping: false,
			borderRadiusTopLeft: 10,
			borderRadiusTopRight: 10,
			borderWidth: 0 // < set this option
		}
	},

    series: [{
		name: 'Tidak Hadir',
		data: [<?=$cabangRekapAlpha?>],
		color: '#b90404'
	}, {
		name: 'Hadir',
		data: [<?=$cabangRekapHadir?>],
		color: '#0f8c20'
	}]
});
});
</script>

<script>
/*Highcharts.chart('grafik-kehadiran', {
  chart: {
    type: 'column'
  },
  title: {
    text: 'Stacked column chart'
  },
  xAxis: {
    categories: ['Apples', 'Oranges', 'Pears', 'Grapes', 'Bananas']
  },
  yAxis: {
    min: 0,
    title: {
      text: 'Total fruit consumption'
    },
    stackLabels: {
      enabled: true,
      style: {
        fontWeight: 'bold',
        color: ( // theme
          Highcharts.defaultOptions.title.style &&
          Highcharts.defaultOptions.title.style.color
        ) || 'gray'
      }
    }
  },
  legend: {
    align: 'right',
    x: -30,
    verticalAlign: 'top',
    y: 25,
    floating: true,
    backgroundColor:
      Highcharts.defaultOptions.legend.backgroundColor || 'white',
    borderColor: '#CCC',
    borderWidth: 1,
    shadow: false
  },
  tooltip: {
    headerFormat: '<b>{point.x}</b><br/>',
    pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
  },
  plotOptions: {
    column: {
      stacking: 'normal',
      dataLabels: {
        enabled: true
      },
	  borderRadiusTopLeft: 10,
	  borderRadiusTopRight: 10
    }
  },
  series: [{
    name: 'John',
    data: [5, 3, 4, 7, 2]
  }, {
    name: 'Jane',
    data: [2, 2, 3, 2, 1]
  }, {
    name: 'Joe',
    data: [3, 4, 4, 2, 5]
  }]
});*/
</script>

