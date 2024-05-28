<?php
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/date.func.php");

$tinggi = 216;

$reqId 		= $this->input->get("reqId");
$reqPermohonanProyekId = $this->input->get("reqPermohonanProyekId");
$reqPeriode	= $this->input->get("reqPeriode");
$reqAwal	= $this->input->get("reqAwal");
$reqAkhir	= $this->input->get("reqAkhir");

if($reqAwal == "")
	$reqAwal = 0;

if($reqAkhir == "")
	$reqAkhir = 0;


$x_awal=$reqAwal;
$date= $reqAkhir;

$this->load->model("PermohonanProyek");
$permohonan_proyek = new PermohonanProyek();
$permohonan_proyek->selectByParams(array("PERMOHONAN_PROYEK_ID" => $reqPermohonanProyekId));
$permohonan_proyek->firstRow();
$reqProyek = $permohonan_proyek->getField("NAMA");

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
							 { bVisible:true },
							 { bVisible:true },
							 { bVisible:true },
							 null,
							 null,
							 null,
							 null,
							 null,
							 null,
							 null
							 <?
							 $x=$x_awal;
							 while ($x <= $date) {
								echo ',{ bVisible:true, "sClass": "center"}';
							 ?>
							 <?	
								$x++;
							 }
							 ?>	
						],
			  "bSort":true,
			  "bProcessing": true,
			  "bServerSide": true,		
			  "sAjaxSource": "<?=base_url()?>permohonan_proyek_json/json_monitoring/?reqPermohonanProyekId=<?=$reqPermohonanProyekId?>&reqPeriode=<?=$reqPeriode?>&reqAwal=<?=$reqAwal?>&reqAkhir=<?=$reqAkhir?>",			  
			  "sScrollY": ($(window).height() - <?=$tinggi?>),
			  "sScrollX": "100%",								  
			  "sScrollXInner": "300%",
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
			  
			  $('#btnAdd').on('click', function () {
				  //window.parent.openPopup("<?=base_url()?>app/loadUrl/app/jam_kerja_add");
				  window.parent.openPopupModif("<?=base_url()?>app/loadUrl/app/jam_kerja_add");
				  //$(this).parent('.modal-kk').css({width:'700px',
                  //             height:'auto', 
                  //            'max-height':'100%'});
				  
				  // tutup flex dropdown => untuk versi mobile
				  $('div.flexmenumobile').hide()
				  $('div.flexoverlay').css('display', 'none')

			  });
			  
			  $('#btnEdit').on('click', function () {
				  if(anSelectedData == "")
					  return false;				
				  window.parent.openPopup("<?=base_url()?>app/loadUrl/app/jam_kerja_add/"+anSelectedId);
					
				  // tutup flex dropdown => untuk versi mobile
				  $('div.flexmenumobile').hide()
				  $('div.flexoverlay').css('display', 'none')
			  });
			  
			  $('#reqPermohonanProyekId').combotree({
					onSelect: function(param){
						if(param.periode == "")
						{
							$.messager.alert('Info','Pilih periode proyek.');
							return;	
						}
						document.location.href = '<?=base_url()?>app/loadUrl/app/rekapitulasi_kehadiran_karyawan_proyek/?reqId='+param.id+'&reqPermohonanProyekId='+param.permohonan_proyek_id+'&reqPeriode='+param.periode+'&reqAwal='+param.tanggal_awal+'&reqAkhir='+param.tanggal_akhir;
					}
			  });
			  
			  $('#btnDelete').on('click', function () {
					if(anSelectedData == "")
						  return false;	
					$.messager.confirm('Konfirmasi',"Hapus data terpilih?",function(r){
						if (r){
							$.getJSON("<?=base_url()?>jam_kerja_json/delete/"+anSelectedId,
							  function(data){
									  $.messager.alert('Info', data.PESAN, 'info');
									  oTable.fnReloadAjax("<?=base_url()?>jam_kerja_json/json");
							});
												
						}
					});	
			  });
			  
			  $('#btnCetak').on('click', function () {
				  newWindow = window.open('rekapitulasi_kehadiran_unit_cetak.php?reqBulan='+$("#reqBulan").val()+'&reqTahun='+ $("#reqTahun").val()+"&reqDepartemen="+$('#reqDepartemen').combotree('getValue'), 'Cetak');
				  newWindow.focus();
			  });
			  
			  $('#btnCetakPeriode').on('click', function () {
				  
				  if($("#reqPermohonanProyekId").combotree('getValue') == "")
				  {
					  $.messager.alert('Info','Pilih periode proyek.');
					  return false;
				  }
				  
				  newWindow = window.open('<?=base_url()?>app/loadUrl/app/rekapitulasi_kehadiran_proyek_pegawai_ttp_cetak.php?reqPermohonanProyekId=<?=$reqPermohonanProyekId?>&reqPeriode=<?=$reqPeriode?>&reqAwal=<?=$reqAwal?>&reqAkhir=<?=$reqAkhir?>', 'Cetak');
				  newWindow.focus();
			  });
			  
			  $('#btnCetakProyek').on('click', function () {
				  
				  if($("#reqPermohonanProyekId").combotree('getValue') == "")
				  {
					  $.messager.alert('Info','Pilih periode proyek.');
					  return false;
				  }
				  
				  newWindow = window.open('<?=base_url()?>app/loadUrl/app/rekapitulasi_kehadiran_proyek_pegawai_ttp_proyek_cetak.php?reqPermohonanProyekId=<?=$reqPermohonanProyekId?>&reqPeriode=<?=$reqPeriode?>', 'Cetak');
				  newWindow.focus();
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
    <div class="judul-halaman">Rekapitulasi Absensi TTP Proyek : <?=$reqProyek?></div>
    <div id="bluemenu" class="bluetabs" style="background:url(<?=base_url()?>css/media/bluetab.gif)">    
        <ul>
			<li>
                <a data-flexmenu="flexmenu2" id="#">Cetak</a>
                <ul id="flexmenu2" class="flexdropdownmenu">
                    <li><a id="btnCetakPeriode" title="Periode">Periode</a></li>
                    <li><a id="btnCetakProyek" title="Proyek">Proyek</a></li>
                </ul>  
            </li>        
        </ul>
    </div>
    <div id="parameter-tambahan">
                Proyek Periode : <input id="reqPermohonanProyekId" class="easyui-combotree" name="reqPermohonanProyekId" data-options="url:'<?=base_url()?>permohonan_proyek_json/combotree_jadwal',
                            valueField:'id',
                            textField:'text'" style="width:300px;" value="<?=$reqId?>"> 
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
            <tr>
                <th width="70px" rowspan="2">NRP</th> 
                <th width="450px" rowspan="2">Nama</th>  
                <th width="100px" rowspan="2">Jabatan</th> 
                <th width="150px" rowspan="2">Total Kehadiran</th>
                <th width="150px" rowspan="2">Total Tidak&nbsp;Hadir</th>
                <th colspan="5" style="text-align:center">Ketidak Hadiran</th>
                <th colspan="31" style="text-align:center">BULAN</th> 
            </tr>
            <tr>
            	<th width="30px">Sakit</th>
            	<th width="30px">Ijin</th>
            	<th width="30px">Alfa</th>
            	<th width="30px">Cuti</th>
            	<th width="30px">Dinas</th> 
                <?
				$x=$x_awal;
                 while ($x <= $date) {
                 ?>
                 <th class="th_like" width="1px" style="text-align:center"><?=$x?></th>
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