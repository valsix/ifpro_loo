<?php
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$tinggi = 242;

$this->load->model('FingerscanLokasi');

$fingerscan_lokasi= new FingerscanLokasi();

$reqMesin = $this->input->get("reqMesin");
if ($reqMesin == '')
	$reqMesin = 2;
else
	$reqMesin;

if($this->KODE_CABANG == "KP")
	$arr = array();
else
	$arr = array("A.CABANG_ID" => $this->KODE_CABANG);	


$fingerscan_lokasi->selectByParams($arr);
$i=0;
while($fingerscan_lokasi->nextRow())
{
	$arrMesin[$i]["MESIN_ID"]		= $fingerscan_lokasi->getField("MESIN_ID");
	$arrMesin[$i]["NAMA_LOKASI"] 	= $fingerscan_lokasi->getField("NAMA_LOKASI");
	$i++;
}


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

<script type="text/javascript" charset="utf-8">
	$(function(){
		$('#ff').form({
			url:'<?=base_url()?>rekapitulasi_absensi_import_json/add',
			onSubmit:function(){
				if($("#reqLinkFile").val() == "")
				{
					alert("Browse file terlebih dahulu.");
					return false;
				}
				else if($("#reqMesin").val() == "")
				{
					alert("Pilih mesin terlebih dahulu.");
					return false;
				}
				else
				{
					var win = $.messager.progress({
						title:'Please waiting',
						msg:'Loading data...'
					});
					return $(this).form('validate');
				}
			},
			success:function(data){
				$.messager.progress('close');
				alert(data);
				document.location.href = 'rekapitulasi_absensi_import.php?reqMesin='+$("#reqMesin").val();
			}				
		});
	});
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
							 null,
							 null,
							 null,
							 null,
							 null	
						],
			  "bSort":true,
			  "bProcessing": true,
			  "bServerSide": true,		
			  "sAjaxSource": "<?=base_url()?>rekapitulasi_absensi_import_json/json/?reqMesin=<?=$reqMesin?>",		
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
			  
			  $('#btnProses').on('click', function () {
					$("#btnSubmit").click();
			  });

			  $('#btnImport').on('click', function () {

					if(confirm("Pastikan terdapat data pada monitoring import absensi, apabila data tidak muncul silahkan generate data terlebih dahulu. Lanjutkan import data absensi ?"))
					{
										  
						  var win = $.messager.progress({
							  title:'Import data absensi.',
							  msg:'Proses data...'
						  });									
						  var jqxhr = $.get("<?=base_url()?>rekapitulasi_absensi_import_json/import/?reqMesin="+$("#reqMesin").val(), function(data) {
							  
							  oTable.fnReloadAjax("<?=base_url()?>rekapitulasi_absensi_import_json/json/?reqMesin="+$("#reqMesin").val());	
							  $.messager.progress('close');	
						  })
						  .fail(function() {
							  alert( "error" );
							  $.messager.progress('close');	
						  });	
						  
					}
									
			  });

				$("#reqMesin").change(function() { 
					oTable.fnReloadAjax("<?=base_url()?>rekapitulasi_absensi_import_json/json/?reqMesin="+$("#reqMesin").val());				 	 
				});
							  		
			  
			  $('#rightclickarea').bind('contextmenu',function(e){
				  if(anSelectedData == '')	
					  return false;							
			  var $cmenu = $(this).next();
			  $('<div class="overlay"></div>').css({left : '0px', top : '0px',position: 'absolute', width: '100%', height: '100%', zIndex: '0' }).click(function() {				
				  $(this).remove();
				  $cmenu.hide();
			  }).bind('contextmenu' , function(){return false;}).appendTo(document.body);
			  $(this).next().css({ left: e.pageX, top: e.pageY, zIndex: '1' }).show();
		  
			  return false;
			   });
		  
			   $('.vmenu .first_li').live('click',function() {
				  if( $(this).children().size() == 1 ) {
					  if($(this).children().text() == 'Ubah')
					  {
						  $("#btnEdit").click();
					  }
					  else if($(this).children().text() == 'Hapus')
					  {
						  $("#btnDeleteRow").click();
					  }
					  $('.vmenu').hide();
					  $('.overlay').hide();
				  }
			   });

			  $(".first_li , .sec_li, .inner_li span").hover(function () {
				  $(this).css({backgroundColor : '#E0EDFE' , cursor : 'pointer'});
			  if ( $(this).children().size() >0 )
					  $(this).find('.inner_li').show();	
					  $(this).css({cursor : 'default'});
			  }, 
			  function () {
				  $(this).css('background-color' , '#fff' );
				  $(this).find('.inner_li').hide();
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
    <div class="judul-halaman">Import Absensi</div>
    <div id="bluemenu" class="bluetabs" style="background:url(<?=base_url()?>css/media/bluetab.gif)">    
        <ul>
            <li>
            <a href="#" id="btnProses" title="Generate Data">Generate Data</a>
            </li> 
            <li>
            <a href="#" id="btnImport" title="Impor Data">Import Data</a>
            </li>       
        </ul>
    </div>
    
    
    <div class="menu-aksi"><a href="#" data-flexmenu="flexmenu1"><img src="<?=base_url()?>images/tambah.png"></a></div>
    
    <div id="parameter-tambahan">
    <form id="ff" method="post" novalidate enctype="multipart/form-data">
        Tipe Generate
        <select name="reqTipe" id="reqTipe">
            <option value="-">-</option>
            <option value="/">/</option>
        </select> 
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        File
        <input type="file" name="reqLinkFile" id="reqLinkFile" class="easyui-validatebox" validType="fileType['dat']" />
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        Mesin
        <select name="reqMesin" id="reqMesin">
        	<option value="">-- Pilih Mesin --</option>
			<?
			for($i=0;$i<count($arrMesin);$i++)
			{
			?>
			<option value="<?=$arrMesin[$i]["MESIN_ID"]?>" <? if($reqMesin == $arrMesin[$i]["MESIN_ID"]) { ?> selected <? } ?>><?=$arrMesin[$i]["NAMA_LOKASI"]?></option>
			<?
			}
			?>
        </select>
        <input name="btnSubmit" id="btnSubmit" type="submit" value="Submit" style="display:none">
    </form>
    </div>
    
    <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
        <tr>
            <th width="100px">Finger Id</th>
            <th width="100px">NRP</th>
            <th width="300px">Nama</th>
            <th width="200px">Departemen</th>
            <th width="100px">Jam</th>                                            
        </tr>
    </thead>
    </table> 
</div>
</body>
</html>