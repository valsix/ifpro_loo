<?php
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$tinggi = 242;

$reqDepartemen = $this->input->get("reqDepartemen");

$this->load->model('JamKerjaJenis');

$jam_kerja_jenis = new JamKerjaJenis();

$jam_kerja_jenis->selectByParams();
$i=0;

while($jam_kerja_jenis->nextRow())
{
	$arrJamKerjaJenis[$i]["NAMA"] = $jam_kerja_jenis->getField("NAMA");
	$arrJamKerjaJenis[$i]["WARNA"] = $jam_kerja_jenis->getField("WARNA");
	$arrJamKerjaJenis[$i]["JAM_KERJA_JENIS_ID"] = $jam_kerja_jenis->getField("JAM_KERJA_JENIS_ID");
	
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
							 null
						],
			  "bSort":true,
			  "bProcessing": true,
			  "bServerSide": true,		
			  "sScrollY": ($(window).height() - <?=$tinggi?>),
			  "sAjaxSource": "<?=base_url()?>pegawai_jenis_jam_kerja_json/json/?reqDepartemen=<?=$reqDepartemen?>",
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
			  
			  $('#btnAdd').on('click', function () {
				  window.parent.openPopup("<?=base_url()?>app/loadUrl/app/agenda_add");
				  
				  // tutup flex dropdown => untuk versi mobile
				  $('div.flexmenumobile').hide()
				  $('div.flexoverlay').css('display', 'none')

			  });
			  
			  $('#btnEdit').on('click', function () {
				  if(anSelectedData == "")
					  return false;				
				  window.parent.openPopup("<?=base_url()?>app/loadUrl/app/agenda_add/"+anSelectedId);
					
				  // tutup flex dropdown => untuk versi mobile
				  $('div.flexmenumobile').hide()
				  $('div.flexoverlay').css('display', 'none')
			  });
			  
			  $('#btnDelete').on('click', function () {
					if(anSelectedData == "")
						  return false;	
					$.messager.confirm('Konfirmasi',"Hapus data terpilih?",function(r){
						if (r){
							$.getJSON("<?=base_url()?>agenda_json/delete/"+anSelectedId,
							  function(data){
									  $.messager.alert('Info', data.PESAN, 'info');
									  oTable.fnReloadAjax("<?=base_url()?>agenda_json/json");
							});
												
						}
					});	
			  });
			   /* RIGHT CLICK EVENT */
			  var anSelectedData = '';
			  var anSelectedId = '';
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
		  	
			  $("#reqDepartemen").change(function() { 
				 oTable.fnReloadAjax("<?=base_url()?>pegawai_jenis_jam_kerja_json/json/?reqDepartemen=" +$("#reqDepartemen").val());				 	 
			  });
			
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
					  anSelectedNRP = element[1];
					  anSelectedNIPP = element[2];
					  anSelectedNama = element[3];
					  anSelectedJabatan = element[4];
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
		  
			   $('.vmenu .first_li').on('click',function() {
				  if( $(this).children().size() == 1 ) {
					
					<?
					for($i=0;$i<count($arrJamKerjaJenis);$i++)
					{
					?>
					  if($(this).children().text() == '<?=$arrJamKerjaJenis[$i]["NAMA"]?>')
					  {					
						 $.getJSON('pegawai_jenis_jam_kerja_json_set/?reqId='+anSelectedId+'&reqJamKerjaJenisId=<?=$arrJamKerjaJenis[$i]["JAM_KERJA_JENIS_ID"]?>', function (data) 
						  {
							  $.each(data, function (i, SingleElement) {
								  oTable.fnUpdate('<div style="background-color:#<?=$arrJamKerjaJenis[$i]["WARNA"]?>; width:120%; margin-top:-3px; padding-top:3px; float:left; position:relative; margin-bottom:-3px; padding-bottom:3px; border-right:15px solid #3F3; margin-left:-10px; padding-left:8px;">&nbsp;' + anSelectedNRP + '</div>', anSelectedPosition, 1, false);
								  oTable.fnUpdate('<div style="background-color:#<?=$arrJamKerjaJenis[$i]["WARNA"]?>; width:120%; margin-top:-3px; padding-top:3px; float:left; position:relative; margin-bottom:-3px; padding-bottom:3px; border-right:15px solid #3F3; margin-left:-10px; padding-left:8px;">&nbsp;' + anSelectedNIPP + '</div>', anSelectedPosition, 2, false);
								  oTable.fnUpdate('<div style="background-color:#<?=$arrJamKerjaJenis[$i]["WARNA"]?>; width:120%; margin-top:-3px; padding-top:3px; float:left; position:relative; margin-bottom:-3px; padding-bottom:3px; border-right:15px solid #3F3; margin-left:-10px; padding-left:8px;">&nbsp;' + anSelectedNama + '</div>', anSelectedPosition, 3, false);
								  oTable.fnUpdate('<div style="background-color:#<?=$arrJamKerjaJenis[$i]["WARNA"]?>; width:120%; margin-top:-3px; padding-top:3px; float:left; position:relative; margin-bottom:-3px; padding-bottom:3px; border-right:15px solid #3F3; margin-left:-10px; padding-left:8px;">&nbsp;' + anSelectedJabatan + '</div>', anSelectedPosition, 4, false);
								  oTable.fnUpdate('<div style="background-color:#<?=$arrJamKerjaJenis[$i]["WARNA"]?>; width:100%; margin-top:-3px; padding-top:3px; float:left; position:relative; margin-bottom:-3px; padding-bottom:3px; border-right:15px solid #3F3; margin-left:-10px; padding-left:8px;">&nbsp;<?=$arrJamKerjaJenis[$i]["NAMA"]?></div>', anSelectedPosition, 5, false);
	
							  });
						  });						
							   					  
					  }
				    <?
					}
					?>  
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
    <div class="judul-halaman">Data Pegawai Jam Kerja</div>
    <div id="bluemenu" class="bluetabs" style="background:url(<?=base_url()?>css/media/bluetab.gif)">    
        <ul>
			<li>
            </li>        
        </ul>
    </div>
    <div id="parameter-tambahan">
        Departemen : 
        <input class="easyui-combobox" name="reqDepartemen" style="width:280px;" data-options="
                                    url: '<?=base_url()?>departemen_combo_json/json',
                                    method: 'get',
                                    valueField:'value',
                                    textField:'text',
                                    groupField:'group'
                                " value="<?=$reqDepartemen?>">          
		&nbsp;&nbsp;
        Status Kuliah : 
        <select name="reqStatusKuliah" id="reqStatusKuliah">
            <option value="0">BOD</option>
            <option value="2">OPERASI</option>
            <option value="3">SDM</option>
        </select>   
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
       <tr>
            <th>Id</th>
            <th width="85px">NRP</th> 
            <th width="85px">NIPP</th>
            <th width="200px">Nama</th> 
            <th width="150px">Jabatan</th>
            <th width="150px">Departemen</th>
            <th width="150px">Jenis</th>
        </tr>
    </thead>
    </table> 
</div>
<!--RIGHT CLICK EVENT -->
<div class="vmenu">
    <?
    for($i=0;$i<count($arrJamKerjaJenis);$i++)
    {
    ?>
        <div class=""><span><?=$arrJamKerjaJenis[$i]["NAMA"]?></span></div>
    <?
    }
    ?>
</div>
<!--RIGHT CLICK EVENT -->
</body>
</html>