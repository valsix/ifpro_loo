<?php
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$tinggi = 242;
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
							 { bVisible:false },
							 null,
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
			  "sAjaxSource": "<?=base_url()?>permohonan_lambat_pc_json/json",		  
			  "sScrollY": ($(window).height() - <?=$tinggi?>),
			  "sScrollX": "100%",								  
			  "sScrollXInner": "120%",
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
					  anSelectedPublish = element[1];
			  });
			  
			  $('#btnAdd').on('click', function () {
				  window.parent.openPopup("<?=base_url()?>app/loadUrl/app/permohonan_ijin_add/");
				  
				  // tutup flex dropdown => untuk versi mobile
				  $('div.flexmenumobile').hide()
				  $('div.flexoverlay').css('display', 'none')

			  });
			  
			  $('#btnEdit').on('click', function () {
				  if(anSelectedData == "")
					  return false;				
					
				if(anSelectedPublish == "" || anSelectedPublish == "0")	  
				  window.parent.openPopup("<?=base_url()?>app/loadUrl/app/permohonan_ijin_add/?reqId="+anSelectedId+"&reqMode=update");
				else
				  $.messager.alert('Info', "Permohonan telah diverifikasi.", 'info');
					
				  // tutup flex dropdown => untuk versi mobile
				  $('div.flexmenumobile').hide()
				  $('div.flexoverlay').css('display', 'none')
			  });
			  
			  $('#btnLihat').on('click', function () {
				  if(anSelectedData == "")
					  return false;				
					  
				  window.parent.openPopup("<?=base_url()?>app/loadUrl/app/permohonan_ijin_add/?reqId="+anSelectedId+"&reqMode=view");
				  
				  // tutup flex dropdown => untuk versi mobile
				  $('div.flexmenumobile').hide()
				  $('div.flexoverlay').css('display', 'none')
			  });
			  
			   $('#btnDelete').on('click', function () {
					if(anSelectedData == "")
						  return false;	
					$.messager.confirm('Konfirmasi',"Hapus data terpilih?",function(r){
						if (r){
							$.getJSON("<?=base_url()?>permohonan_lambat_pc_json/delete/?reqId="+anSelectedId,
							  function(data){
									  $.messager.alert('Info', data.PESAN, 'info');
									  oTable.fnReloadAjax("<?=base_url()?>permohonan_lambat_pc_json/json/?reqStatus=" +$("#reqStatus").val());
							});
												
						}
					});	
			  });
			   $("#reqStatus").change(function() { 			  
				 oTable.fnReloadAjax("<?=base_url()?>permohonan_lambat_pc_json/json/?reqStatus="+ $("#reqStatus").val());				 	 
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
    <div class="judul-halaman">Permohonan Terlambat / Pulang Cepat</div>
    <div id="bluemenu" class="bluetabs" style="background:url(<?=base_url()?>css/media/bluetab.gif)">    
        <ul>
			<li>
                <a href="#" id="btnLihat" title="Lihat"><img src="<?=base_url()?>images/icon-lihat.png" /> Lihat</a> 
                <? if($this->USER_GROUP_ID == 1 )
				{
				?>
                	<a href="#" id="btnDelete" title="Hapus"><img src="<?=base_url()?>images/icon-hapus.png" /> Hapus</a> 
				<?
				}
				else if($this->USER_GROUP_ID == 2)
				{
					if($this->KODE_STAFF == 5 || $this->KODE_STAFF == 7 || $this->KODE_STAFF == 8)
					{
					?>
                    <a href="#" id="btnEdit" title="Approval"><img src="<?=base_url()?>images/icon-verifikasi.png" /> Approval</a> 
					<?
					}
				}
				else
				{
				?>
                <a href="#" id="btnEdit" title="Approval"><img src="<?=base_url()?>images/icon-verifikasi.png" /> Approval</a> 
				<?
				}
				?>
            </li>        
        </ul>
    </div>
    <div id="parameter-tambahan">
    	Status : 
        <select id="reqStatus">
        	<option value="">Semua</option>        	
        	<option value="B">Belum Verifikasi</option>
        	<option value="S">Sudah Verifikasi</option>
        </select>
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
        <tr>
			<th>Id</th>
            <th>Id</th>
            <th width="160px">Nama Pegawai</th>
            <th width="130px">Nomor</th>
            <th width="100px">Jenis</th>
            <th width="100px">Tanggal</th>
            <th width="100px">Tanggal Ijin</th>
            <th width="80px">Datang</th>
            <th width="80px">Pulang</th>
            <th width="100px">Keperluan</th> 
            <th width="100px">Approval</th>  
            <th width="160px">Approval Lainnya</th>  
        </tr>
    </thead>
    </table> 
</div>
</body>
</html>