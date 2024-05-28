<?php
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$tinggi = 262;

$reqTahun= $this->input->get("reqTahun");
$reqBulan= $this->input->get("reqBulan");
$reqStatusPegawai= $this->input->get("reqStatusPegawai");
$reqDepartemen = $this->KODE_CABANG;

if($reqTahun == "")	{
	$reqLastMonth = strtotime("0 months", strtotime(date("d-m-Y")));
	$reqBulan = strftime ( '%m' , $reqLastMonth );
	$reqTahun = strftime ( '%Y' , $reqLastMonth );
}

$date=$reqTahun."-".$reqBulan;
$day =  getDay(date("Y-m-t",strtotime($date)));
$date= 31;

$x_awal=1;

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
<title>Diklat</title>
<link rel="shortcut icon" type="image/ico" href="http://www.datatables.net/media/images/favicon.ico">
<!--<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="http://www.datatables.net/rss.xml">-->
<link href="<?=base_url()?>css/admin.css" rel="stylesheet" type="text/css">

<style type="text/css" media="screen">
    @import "<?=base_url()?>lib/media/css/site_jui.css";
    @import "<?=base_url()?>lib/media/css/demo_table_jui.css";
    @import "<?=base_url()?>lib/media/css/themes/base/jquery-ui.css";
</style>

<!-- FLEX MENU -->
<script type="text/javascript" src="<?=base_url()?>lib/Flex-Level-Drop-Down-Menu-v1.3/jquery.min.js"></script>

<script type="text/javascript">
$(document).ready( function () {
	// Run code
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
	}
	else
	{
		$("#flexmenu1").removeAttr("class");	
		$("#flexmenu1").removeAttr("id");	 				  
	}
});
</script>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>lib/Flex-Level-Drop-Down-Menu-v1.3/flexdropdown.css" />
<script type="text/javascript" src="<?=base_url()?>lib/Flex-Level-Drop-Down-Menu-v1.3/flexdropdown.js">

/***********************************************
* Flex Level Drop Down Menu- (c) Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for this script and 100s more
***********************************************/

</script>

<script type="text/javascript" src="<?=base_url()?>lib/media/js/complete.js"></script>


<link rel="stylesheet" type="text/css" href="<?=base_url()?>lib/DataTables-1.10.6/media/css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="<?=base_url()?>lib/DataTables-1.10.6/examples/resources/syntax/shCore.css">
<link rel="stylesheet" type="text/css" href="<?=base_url()?>lib/DataTables-1.10.6/examples/resources/demo.css">
<script type="text/javascript" language="javascript" src="<?=base_url()?>lib/DataTables-1.10.6/media/js/jquery.js"></script>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="<?=base_url()?>lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript" language="javascript" src="<?=base_url()?>lib/DataTables-1.10.6/media/js/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" src="<?=base_url()?>lib/DataTables-1.10.6/examples/resources/syntax/shCore.js"></script>
<script type="text/javascript" language="javascript" src="<?=base_url()?>lib/DataTables-1.10.6/examples/resources/demo.js"></script>	
<script type="text/javascript" language="javascript" src="<?=base_url()?>lib/DataTables-1.10.6/extensions/FixedColumns/js/dataTables.fixedColumns.js"></script>	
<script type="text/javascript" language="javascript" src="<?=base_url()?>lib/DataTables-1.10.6/extensions/TableTools/js/dataTables.tableTools.min.js"></script>	

<script type="text/javascript" charset="utf-8">
	var oTable;
    $(document).ready( function () {
										
        var id = -1;//simulation of id
		$(window).resize(function() {
		  console.log($(window).height());
		  $('.dataTables_scrollBody').css('height', ($(window).height() - <?=$tinggi?>));
		});
        oTable = $('#example').dataTable({ bJQueryUI: true,"iDisplayLength": 500,
			  /* UNTUK MENGHIDE KOLOM ID */
			 "aoColumns": [
							 { bVisible:false },
							 { bVisible:true },
							 { bVisible:true },
							 { bVisible:true },
							 { bVisible:true }
							 <?
							 $x=$x_awal;
							 while ($x <= $date) {
								echo ',{ bVisible:true}';
								echo ',{ bVisible:true}';
							 ?>
							 <?	
								$x++;
							 }
							 ?>
						],
			  "bSort":true,
			  "bProcessing": true,
			  "bServerSide": true,		
			  "sAjaxSource": "<?=base_url()?>absensi_rekap_json/rekapitulasi_absensi_json/?reqTahun=<?=$reqTahun?>&reqBulan=<?=$reqBulan?>&reqDepartemen=<?=$reqDepartemen?>",		
			  columnDefs: [{ className: 'never', targets: [ 0] }],
			  "sScrollY": ($(window).height() - <?=$tinggi?>),
			  "sScrollX": "100%",								  
			  "sScrollXInner": "100%",
			  "sPaginationType": "full_numbers"
			  });
			/* Click event handler */

			  /* RIGHT CLICK EVENT */
			  var anSelectedData = '';
			  var anSelectedId = '';
			  var anSelectedDownload = '';
			  var anSelectedPosition = '';	
			  			  
			  function fnGetSelected( oTableLocal )
			  {
				  var aReturn = new Array();
				  var aTrs = oTableLocal.fnGetNodes();
				  for ( var i=0 ; i<aTrs.length ; i++ )
				  {
					  if ( $(aTrs[i]).hasClass('row_selected') )
					  {
						  aReturn.push( aTrs[i] );
						  anSelectedPosition = i;
					  }
				  }
				  return aReturn;
			  }
		  
			  $("#example tbody").click(function(event) {
					  $(oTable.fnSettings().aoData).each(function (){
						  $(this.nTr).removeClass('row_selected');
					  });
					  $(event.target.parentNode).addClass('row_selected');
					  //
					  var anSelected = fnGetSelected(oTable);													
					  anSelectedData = String(oTable.fnGetData(anSelected[0]));
					  var element = anSelectedData.split(','); 
					  anSelectedId = element[0];
			  });
			  
			  $('#btnCetak').on('click', function () {
				  newWindow = window.open('rekapitulasi_absensi_cetak.php?reqBulan='+$("#reqBulan").val()+'&reqTahun='+ $("#reqTahun").val()+"&reqDepartemen="+$('#reqDepartemen').combotree('getValue'), 'Cetak');
				  newWindow.focus();
			  });
			  
			  $('#btnCetakHari').on('click', function () {
				  /*if(anSelectedData == "")
					  return false;*/				
				  //alert('rekapitulasi_terlambat_pulang_cepat_cetak.php?reqBulan='+ $("#bulan").val()+'&reqTahun='+ $("#tahun").val());
					var bulan = new Array();
					bulan[0] = "";
					bulan[1] = "Januari";
					bulan[2] = "Februari";
					bulan[3] = "Maret";
					bulan[4] = "April";
					bulan[5] = "Mei";
					bulan[6] = "Juni";
					bulan[7] = "Juli";
					bulan[8] = "Agustus";
					bulan[9] = "September";
					bulan[10] = "Oktober";
					bulan[11] = "November";
					bulan[12] = "Desember";
					$.messager.prompt('Cetak Rekap ' + bulan[parseInt($("#reqBulan").val())] + ' ' + $("#reqTahun").val(), 'Masukkan tanggal :', function(r){
						if (r){
						    newWindow = window.open('rekapitulasi_absensi_per_tanggal_cetak.php?reqBulan='+$("#reqBulan").val()+'&reqTahun='+ $("#reqTahun").val()+'&reqHari='+r+"&reqDepartemen="+$('#reqDepartemen').combotree('getValue'), 'Cetak');
						    newWindow.focus();
						}
					});
			
			  });
			  
			  $('#btnCetakMingguan').on('click', function () {
					var bulan = new Array();
					bulan[0] = "";
					bulan[1] = "Januari";
					bulan[2] = "Februari";
					bulan[3] = "Maret";
					bulan[4] = "April";
					bulan[5] = "Mei";
					bulan[6] = "Juni";
					bulan[7] = "Juli";
					bulan[8] = "Agustus";
					bulan[9] = "September";
					bulan[10] = "Oktober";
					bulan[11] = "November";
					bulan[12] = "Desember";
					//var validformat=/^\d{4}\-\d{2}\-\d{2}$/ ;
					//var validformat="^(0[1-9]|[12][0-9]|3[01])[-](0[1-9]|[12][0-9]|3[01])";
					//var validformat=/^(0?[1-9]|[12][0-9]|3[01])-(0?[1-9]|[12][0-9]|3[01])$/ ;
					var validformat="^(((0?[1-9]|1[012])/([01]?[1-9]|10|2[0-8])|(0?[13-9]|1[012])/(29|30)|(0?[13578]|1[02])/31)/(1[89]|20)[0-9]{2}|0?2/29/(1[89]|20)([24680][048]|[13579][26]))$";
					$.messager.prompt('Cetak Rekap Mingguan ' + bulan[parseInt($("#reqBulan").val())] + ' ' + $("#reqTahun").val(), 'Masukkan range tanggal (contoh:1-7) :', function(r){
						var arr= r.split("-");
						//alert(arr[0]+'---'+arr[1]);
						
						if (( arr[0] >= 1 && arr[0] <= 31) && ( arr[1] >= 1 && arr[1] <= 31))
						{
							newWindow = window.open('rekapitulasi_absensi_mingguan_tanggal_cetak.php?reqAwal='+arr[0]+'&reqAkhir='+arr[1]+'&reqBulan='+$("#reqBulan").val()+'&reqTahun='+ $("#reqTahun").val()+"&reqDepartemen="+$('#reqDepartemen').combotree('getValue'), 'Cetak');
							newWindow.focus();
						}
						else
						{
							alert("contoh data valid: 1-10.");
							return false;
						}
					});
			
			  });
			  
			   $('#btnResetAbsensi').on('click', function () {
				  if(anSelectedData == "")
					  return false;

				   $.messager.confirm('Konfirmasi','Reset presensi pegawai terpilih ?',function(r){
						if (r){

							var win = $.messager.progress({
								  title:'Proses data reset kehadiran.',
								  msg:'Proses data...'
							  });
							
							var jqxhr = $.get( "<?=base_url()?>absensi_rekap_json/reset_pegawai/?reqId="+anSelectedId+"&reqTahun="+$("#reqTahun").val()+"&reqBulan="+$("#reqBulan").val(), function(data) {
								$.messager.alert('Info',data, 'info');
								oTable.fnReloadAjax("<?=base_url()?>absensi_rekap_json/rekapitulasi_absensi_json/?reqTahun="+$("#reqTahun").val()+"&reqBulan="+$("#reqBulan").val()+"&reqDepartemen="+$('#reqDepartemen').combotree('getValue'));	
								$.messager.progress('close');	
							})
							.fail(function() {
								alert( "error" );
								$.messager.progress('close');	
							});								
						}
					});					  
					  		
			  });					  	  

			   $('#btnResetAbsensiAll').on('click', function () {

				   $.messager.confirm('Konfirmasi','Reset presensi departemen terpilih ?',function(r){
						if (r){

							var win = $.messager.progress({
								  title:'Proses data reset kehadiran.',
								  msg:'Proses data...'
							  });

							var jqxhr = $.get( "<?=base_url()?>absensi_rekap_json/reset_departemen/?reqId="+$('#reqDepartemen').combotree('getValue')+"&reqTahun="+$("#reqTahun").val()+"&reqBulan="+$("#reqBulan").val(), function(data) {
								$.messager.alert('Info',data, 'info');
								oTable.fnReloadAjax("<?=base_url()?>absensi_rekap_json/rekapitulasi_absensi_json/?reqTahun="+$("#reqTahun").val()+"&reqBulan="+$("#reqBulan").val()+"&reqDepartemen="+$('#reqDepartemen').combotree('getValue'));	
								$.messager.progress('close');	
							})
							.fail(function() {
								alert( "error" );
								$.messager.progress('close');	
							});
							

							//ASLI//
							/*
							var jqxhr = $.get( "<?=base_url()?>absensi_rekap_json/reset_departemen/?reqId="+$('#reqDepartemen').combotree('getValue')+"&reqTahun="+$("#reqTahun").val()+"&reqBulan="+$("#reqBulan").val(), function(data) {
								$.messager.alert('Info',data, 'info');
								oTable.fnReloadAjax("<?=base_url()?>absensi_rekap_json/rekapitulasi_absensi_json/?reqTahun="+$("#reqTahun").val()+"&reqBulan="+$("#reqBulan").val()+"&reqDepartemen="+$('#reqDepartemen').combotree('getValue'));	
							})
							.fail(function() {
								alert( "error" );
							});
							*/								
						}
					});					  
					  		
			  });
			  
			   $('#btnEdit').on('click', function () {
				  if(anSelectedData == "")
					  return false;				
				  window.parent.openPopup("<?=base_url()?>app/loadUrl/app/rekapitulasi_absensi_add/?reqTahun="+$("#reqTahun").val()+"&reqBulan="+$("#reqBulan").val()+"&reqId="+anSelectedId);
					
				  // tutup flex dropdown => untuk versi mobile
				  $('div.flexmenumobile').hide()
				  $('div.flexoverlay').css('display', 'none')
			  });
			  
			  $("#reqBulan").change(function() { 
				 oTable.fnReloadAjax("<?=base_url()?>absensi_rekap_json/rekapitulasi_absensi_json/?reqTahun="+$("#reqTahun").val()+"&reqBulan="+$("#reqBulan").val()+"&reqDepartemen="+$('#reqDepartemen').combotree('getValue'));				 	 
			  });

			 $("#reqTahun").change(function() {
				 oTable.fnReloadAjax("<?=base_url()?>absensi_rekap_json/rekapitulasi_absensi_json/?reqTahun="+$("#reqTahun").val()+"&reqBulan="+$("#reqBulan").val()+"&reqDepartemen="+$('#reqDepartemen').combotree('getValue'));				 	 
			  });	

			  $('#reqDepartemen').combotree({
					onSelect: function(param){
						oTable.fnReloadAjax("<?=base_url()?>absensi_rekap_json/rekapitulasi_absensi_json/?reqTahun="+$("#reqTahun").val()+"&reqBulan="+$("#reqBulan").val()+"&reqDepartemen="+param.id);	
					}
			  });
		});
</script>

    <!--RIGHT CLICK EVENT-->		
    <style>

	.vmenu{
		border:1px solid #aaa;
		position:absolute;
		background:#fff;
		display:none;font-size:0.75em;
	}
	.first_li{}
	.first_li span{
		width:100px;
		display:block;
		padding:5px 10px;
		cursor:pointer
	}
	.inner_li{display:none;margin-left:120px;position:absolute;border:1px solid #aaa;border-left:1px solid #ccc;margin-top:-28px;background:#fff;}
	.sep_li{border-top: 1px ridge #aaa;margin:5px 0}
	.fill_title{font-size:11px;font-weight:bold;/height:15px;/overflow:hidden;word-wrap:break-word;}
	</style>
    
    <link href="<?=base_url()?>lib/media/themes/main_datatables.css" rel="stylesheet" type="text/css" /> 
    <link href="<?=base_url()?>css/begron.css" rel="stylesheet" type="text/css">  
    <link href="<?=base_url()?>css/bluetabs.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="<?=base_url()?>js/dropdowntabs.js"></script>
      

</head>
<body style="overflow:hidden;">
<div id="begron"><img src="<?=base_url()?>images/bg-kanan.jpg" width="100%" height="100%" alt="Smile"></div>
<div id="wadah">
    <div class="judul-halaman">Rekapitulasi Presensi Jam Kerja</div>
    <div id="bluemenu" class="bluetabs" style="background:url(<?=base_url()?>css/media/bluetab.gif)">    
        <ul>
            <li>
            	<a href="#" title="Reset Absensi" id="btnResetAbsensi">&nbsp;Reset Presensi</a>
            	<a href="#" title="Reset Absensi Departemen" id="btnResetAbsensiAll">&nbsp;Reset Presensi Departemen</a>
             </li> 
            <li>
            	<a href="#" title="Cetak" id="btnCetak">&nbsp;Cetak</a>
            	<a href="#" title="Cetak" id="btnCetakHari">&nbsp;Cetak Rekap Harian</a>
            	<a href="#" title="Cetak" id="btnCetakMingguan">&nbsp;Cetak Rekap Mingguan</a>
            </li>
            <!--
            <li>
            	<a href="#" title="Ubah Absensi" id="btnEdit">&nbsp;Ubah</a>
            </li>
            -->
        </ul>
    </div>
    
    <div class="menu-aksi"><a href="#" data-flexmenu="flexmenu1"><img src="<?=base_url()?>images/tambah.png"></a></div>
    
    <div id="parameter-tambahan">
        Bulan :
            <select name="reqBulan" id="reqBulan">
            <?
            for($i=1; $i<=12; $i++)
            {
                $tempNama=getNameMonth($i);
                $temp=generateZeroDate($i,2);
            ?>
                <option value="<?=$temp?>" <? if($temp == $reqBulan) echo 'selected'?>><?=$tempNama?></option>
            <?
            }
            ?>
            </select>
        
        Tahun
            <select name="reqTahun" id="reqTahun">
                <? 
                for($i=date("Y")-2; $i < date("Y")+2; $i++)
                {
                ?>
                <option value="<?=$i?>" <? if($i == $reqTahun) echo 'selected'?>><?=$i?></option>
                <?
                }
                ?>
            </select>
        Departemen : <input id="reqDepartemen" class="easyui-combotree" name="reqDepartemen" data-options="url:'<?=base_url()?>departemen_combotree_json/json',
					valueField:'id',
					textField:'text'" style="width:300px;" value="<?=$reqDepartemen?>"> 
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
       <tr>
            <th rowspan="2" class="th_like" width="70px">ID</th>
            <th rowspan="2" class="th_like" width="70px">NRP</th>
            <th rowspan="2" class="th_like" width="450px">Nama</th>
            <th rowspan="2" class="th_like" width="200px">Jabatan</th>
            <th rowspan="2" class="th_like" width="50px">Kelas</th>
             <?
             $x=$x_awal;
             while ($x <= $date) {
             ?>
             <th colspan="2" width="80px" style="text-align:center"><?=$x?></th>
             <?	
                $x++;
             }
             ?>
        </tr>
        <tr>
            <?
            $x=$x_awal;
             while ($x <= $date) {
             ?>
             <th class="th_like" width="50px">IN</th>
             <th class="th_like" width="50px">OUT</th>
             <!--<th class="th_like" width="50px">J.JAM</th>-->
             <?
                $x++;
             }
             ?>
        </tr>
    </thead>
    </table> 
</div>
</body>
</html>