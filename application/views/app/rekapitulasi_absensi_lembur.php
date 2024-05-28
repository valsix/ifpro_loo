<?php
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$tinggi = 262;

$reqMode= $this->input->get("reqMode");
$reqTahun= $this->input->get("reqTahun");
$reqBulan= $this->input->get("reqBulan");
$reqDepartemen = $this->KODE_CABANG;

if($reqTahun == "")	{
	$reqLastMonth = strtotime("-1 months", strtotime(date("d-m-Y")));
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
	
	.dataTables_info { padding-top: 0; }
    .dataTables_paginate { padding-top: 0; }
    .css_right { float: right; }
    #example_wrapper .fg-toolbar { font-size: 0.7em }
    #theme_links span { float: left; padding: 2px 10px; }
	.libur { background-color:#F33; }
	.cuti { background-color:#FF0; }
	.ijin { background-color:#0F0; }	
	
	.merah{
		background:#ab3035;
		color:#FFF;
		border-bottom:1px solid #d2595e;
		padding:3px 9px 2px 10px ;
		margin-top:-3px;
		margin-bottom:-3px;
		margin-left:-10px;
		margin-right:-9px;
	}
	.putih{
		background:#FFF;
		color:#FFF;
		border-bottom:1px solid #e2e4ff;
		border-right:1px solid #e2e4ff;
		padding:3px 10px 2px 10px ;
		margin-top:-3px;
		margin-bottom:-3px;
		margin-left:-10px;
		margin-right:-10px;
	}
</style>

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
			  "sAjaxSource": "<?=base_url()?>absensi_rekap_lembur_json/rekapitulasi_absensi_lembur_json/?reqTahun=<?=$reqTahun?>&reqBulan=<?=$reqBulan?>&reqDepartemen=<?=$reqDepartemen?>",		
			  columnDefs: [{ className: 'never', targets: [ 0] }],
			  "sScrollY": ($(window).height() - <?=$tinggi?>),
			  "sScrollX": "100%",								  
			  "sScrollXInner": "500%",
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
			  
			  $("#reqBulan").change(function() { 
				 oTable.fnReloadAjax("<?=base_url()?>absensi_rekap_lembur_json/rekapitulasi_absensi_lembur_json/?reqTahun="+$("#reqTahun").val()+"&reqBulan="+$("#reqBulan").val()+"&reqDepartemen="+$('#reqDepartemen').combotree('getValue'));				 	 
			  });
			  
			  $("#reqTahun").change(function() { 
				 oTable.fnReloadAjax("<?=base_url()?>absensi_rekap_lembur_json/rekapitulasi_absensi_lembur_json/?reqTahun="+$("#reqTahun").val()+"&reqBulan="+$("#reqBulan").val()+"&reqDepartemen="+$('#reqDepartemen').combotree('getValue'));				 	 
			  });
			  
			  $('#reqDepartemen').combotree({
					onSelect: function(param){
						oTable.fnReloadAjax("<?=base_url()?>absensi_rekap_lembur_json/rekapitulasi_absensi_lembur_json/?reqTahun="+$("#reqTahun").val()+"&reqBulan="+$("#reqBulan").val()+"&reqDepartemen="+param.id);	
					}
			  });
			  
				function refreshfnHide()
				{
					var oTable = $('#example').dataTable();
					
					var bVis;
					 for(var i=40; i<66; i++)
					 {
						 if(bVis = oTable.fnSettings().aoColumns[i].bVisible == false)
							 fnShow(i);
					 }
					 var day = daysInMonth($("#reqBulan").val(), $("#reqTahun").val());
						for(var i=4+(day*3); i<66; i++)
							fnHide(i);					
				}
				
				function fnHide( iCol )
				{
					/* Get the DataTables object again - this is not a recreation, just a get of the object */
					var oTable = $('#example').dataTable();
					var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
					oTable.fnSetColumnVis( iCol,false);
				}
				function fnShow( iCol )
				{
					/* Get the DataTables object again - this is not a recreation, just a get of the object */
					var oTable = $('#example').dataTable();
					
					var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
					oTable.fnSetColumnVis( iCol, true );
				}			
				function daysInMonth(month, year) {
					return new Date(year, month, 0).getDate();
				}
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
    <div class="judul-halaman">Rekapitulasi Presensi Lembur</div>
    <div id="bluemenu" class="bluetabs" style="background:url(<?=base_url()?>css/media/bluetab.gif)">    
        <ul>
            <li></li>
        </ul>
    </div>
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