<?php
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$tinggi = 216;

$reqId 		= $this->input->get("reqId");
//$reqPeriode	= $this->input->get("reqPeriode");
$reqBulan = $this->input->get("reqBulan");
$reqTahun = $this->input->get("reqTahun");

if($reqBulan == "")
	$reqBulan = date('m');

if($reqTahun == "")
	$reqTahun = date('Y');
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
<script type="text/javascript" src="<?=base_url()?>lib/FlexLevel-DropDown-Menu(v2.0)/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready( function () {
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
		
	}
	else
	{
		$("#flexmenu1").removeAttr("class");	
		$("#flexmenu1").removeAttr("id");	 				  
	}
});
</script>
<!-- FLEX DROPDOWN -->
<link rel="stylesheet" type="text/css" href="<?=base_url()?>lib/FlexLevel-DropDown-Menu(v2.0)/flexdropdown.css" />
<script type="text/javascript" src="<?=base_url()?>lib/FlexLevel-DropDown-Menu(v2.0)/flexdropdown.js">
/***********************************************
* Flex Level Drop Down Menu- (c) Dynamic Drive DHTML code library (www.dynamicdrive.com)
* Please keep this notice intact
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
							 null,
							 null,
							 null,
							 null,
							 null,
							 null,
							 null,
							 null,
							 null
						],
			  "bSort":true,
			  "bProcessing": true,
			  "bServerSide": true,		
			  "sAjaxSource": "<?=base_url()?>permohonan_proyek_json/json_monitoring_ttp/?reqBulan=<?=$reqBulan?>&reqTahun=<?=$reqTahun?>",		
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
			  
			  $('#btnCetakProyek').on('click', function () {
				  newWindow = window.open('<?=base_url()?>app/loadUrl/app/rekapitulasi_kehadiran_proyek_pegawai_ttp_cetak.php?reqId='+anSelectedId+'&reqBulan='+$("#reqBulan").val()+'&reqTahun='+ $("#reqTahun").val(), 'Cetak');
				  newWindow.focus();
			  });
			  
			   $('#btnEdit').on('click', function () {
				  if(anSelectedData == "")
					  return false;				
				  window.parent.openPopup("<?=base_url()?>app/loadUrl/app/rekapitulasi_absensi_add/?reqTahun="+$("#reqTahun").val()+"&reqBulan="+$("#reqBulan").val()+"&reqId="+anSelectedId);
					
				  // tutup flex dropdown => untuk versi mobile
				  $('div.flexmenumobile').hide()
				  $('div.flexoverlay').css('display', 'none')
			  });
			  
			  $("#reqBulan, #reqTahun").change(function() { 
				 oTable.fnReloadAjax("<?=base_url()?>permohonan_proyek_json/json_monitoring_ttp/?reqBulan="+$("#reqBulan").val()+"&reqTahun=" +$("#reqTahun").val());				 	 
			  });
			  
			  $('#reqDepartemen').combotree({																				
					onSelect: function(param){
						if(param.periode == "")
						{
							$.messager.alert('Info','Pilih periode proyek.');
							return;	
						}
						document.location.href = '<?=base_url()?>app/loadUrl/app/rekapitulasi_absensi_proyek/?reqId='+param.id+'&reqPermohonanProyekId='+param.permohonan_proyek_id+'&reqPeriode='+param.periode+'&reqAwal='+param.tanggal_awal+'&reqAkhir='+param.tanggal_akhir;
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
    <div class="judul-halaman">Rekapitulasi Presensi TTP</div>
    <div id="bluemenu" class="bluetabs" style="background:url(<?=base_url()?>css/media/bluetab.gif)">    
        <ul>
            <li><a id="btnCetakProyek" title="Cetak">Cetak</a></li>
            <!--
            <li>
            	<a data-flexmenu="flexmenu2" id="#">Cetak</a>
                <ul id="flexmenu2" class="flexdropdownmenu">
                    <li><a id="btnCetakPeriode" title="Periode">Periode</a></li>
                    <li><a id="btnCetakProyek" title="Proyek">Proyek</a></li>
                </ul>
            </li>
            
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
    
        Tahun :
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
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
       <tr>
            <th class="th_like" width="70px">ID</th>
            <th class="th_like" width="50px">Tahun</th>
            <th class="th_like" width="150px">Nama Lokasi</th>
            <th class="th_like" width="100px">Project Id</th>
            <th class="th_like" width="200px">Nama Proyek</th>
            <th class="th_like" width="70px">Pegawai PM</th>
            <th class="th_like" width="70px">Tanggal</th>
            <th class="th_like" width="70px">Tanggal Awal</th>
            <th class="th_like" width="70px">Tanggal Akhir</th>
            <th class="th_like" width="90px">No. Mesin Absen</th>
    </thead>
    </table> 
</div>
</body>
</html>