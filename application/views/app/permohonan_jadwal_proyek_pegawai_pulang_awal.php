<?php
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$tinggi = 195;
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
	<script type="text/javascript" language="javascript" src="<?=base_url()?>lib/media/js/jquery.dataTables.rowGrouping.js"></script>   
  
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
							 { bVisible:false },
							 { bVisible:false },
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
			  "sAjaxSource": "<?=base_url()?>permohonan_proyek_json/json_pegawai_pulang_awal",		  
			  "sScrollY": ($(window).height() - <?=$tinggi?>),			
			  columnDefs: [{ className: 'never', targets: [ 0,1,2,3 ] }],	  
			  "sScrollX": "100%",								  
			  "sScrollXInner": "130%",
			  "sPaginationType": "full_numbers"
			  }).rowGrouping({	iGroupingColumnIndex: 3});
			/* Click event handler */

			  /* RIGHT CLICK EVENT */
			  var anSelectedData = '';
			  var anSelectedId = '';
			  var anSelectedDownload = '';
			  var anSelectedPosition = '';	
			  var anSelectedRow;	
			  var arrData;	
			  var anSelectedProjectId;
			  			  
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
					  anSelectedProyekPegawaiPulangAwalId = element[1];
					  anSelectedApproval = element[2];
					  
					  /*
					  $(this).parent().find(".row_selected").removeClass("row_selected");
					  
					  $(event.target.parentNode).addClass('row_selected');
					  //
					  var anSelected = fnGetSelected(oTable);													
					  anSelectedData = String(oTable.fnGetData(anSelected[0]));
					  var element = anSelectedData.split(','); 
					  anSelectedId = element[0];
					  //anSelectedStatus = element[1];
					  
					  anSelectedGroup = $(event.target.parentNode).find("td").eq(0).html();
					  anSelectedGroup = anSelectedGroup.replace(/\s/g, "_");
					  anSelectedGroup = anSelectedGroup.toUpperCase();
					  anSelectedRow = anSelected;
					  
					  $(oTable.fnSettings().aoData).each(function (){
						  $(this.nTr).removeClass('row_selected');
					  });
					  
					  arrData = anSelectedGroup.split("</DIV>");
					  anSelectedProjectId = arrData[0];
					  anSelectedProjectId = anSelectedProjectId.replace('<DIV_STYLE="DISPLAY:NONE">', '');
					  */
					  
			  });
			  
			  $('#btnAdd').on('click', function () {
				  window.parent.openPopup("<?=base_url()?>app/loadUrl/app/permohonan_jadwal_proyek_pegawai_pulang_awal_add/");
				  
				  // tutup flex dropdown => untuk versi mobile
				  $('div.flexmenumobile').hide()
				  $('div.flexoverlay').css('display', 'none')

			  });
			  
			   $('#btnEdit').on('click', function () {
				  if(anSelectedData == "")
					  return false;				
					
				  if(anSelectedApproval == "" || anSelectedApproval == null)
				  {
					  window.parent.openPopup("<?=base_url()?>app/loadUrl/app/permohonan_jadwal_proyek_pegawai_pulang_awal_add/?reqId="+anSelectedId+"&reqProyekPegawaiPulangAwalId="+anSelectedProyekPegawaiPulangAwalId);
				  }
				  else
				  {
					  $.messager.alert('Info', "Data telah diverifikasi.", 'info');
				  	  return false;
				  }
				
				  // tutup flex dropdown => untuk versi mobile
				  $('div.flexmenumobile').hide()
				  $('div.flexoverlay').css('display', 'none')
			  });
			  
			  $('#btnLihat').on('click', function () {
				  if(anSelectedData == "")
					  return false;				
					  
				  window.parent.openPopup("<?=base_url()?>app/loadUrl/app/permohonan_jadwal_shift_add/?reqId="+anSelectedId+"&reqMode=viewuser");
				  
				  // tutup flex dropdown => untuk versi mobile
				  $('div.flexmenumobile').hide()
				  $('div.flexoverlay').css('display', 'none')
			  });
			  
			   $('#btnDelete').on('click', function () {
					if(anSelectedData == "")
						  return false;	
					$.messager.confirm('Konfirmasi',"Hapus data terpilih?",function(r){
						if (r){
							$.getJSON("<?=base_url()?>permohonan_proyek_json/delete/"+anSelectedId,
							  function(data){
									  $.messager.alert('Info', data.PESAN, 'info');
									  oTable.fnReloadAjax("<?=base_url()?>permohonan_proyek_json/json_pegawai_pulang_awal/");
							});
												
						}
					});	
			  });
			  
			$('#btnPosting').on('click', function () {
				if(anSelectedData == "")
					return false;	
				
				if(anSelectedApproval == "" || anSelectedApproval == null)
				{
					$.messager.confirm('Konfirmasi',"Posting daftar anggota proyek pulang awal ?",function(r){
						if (r){
							$.getJSON("<?=base_url()?>proyek_pegawai_pulang_awal_json/posting/?reqId="+anSelectedId,
							function(data){
								$.messager.alert('Info', data.PESAN, 'info');
								oTable.fnReloadAjax("<?=base_url()?>permohonan_proyek_json/json_pegawai_pulang_awal/");
							});
						}
					});	
				}
				else
				{
					$.messager.alert('Info', "Data telah diverifikasi.", 'info');
				  	return false;
				}
			});
			  
			  $("#reqStatus").change(function() { 			  
				 oTable.fnReloadAjax("<?=base_url()?>permohonan_proyek_json/json_pegawai_pulang_awal/");				 	 
			  });
			  
			  $('#reqPermohonanProyekId').combobox({
					onSelect: function(param){
						oTable.fnReloadAjax("<?=base_url()?>permohonan_proyek_json/json_pegawai_pulang_awal/?reqPermohonanProyekId="+param.id);
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
    <div class="judul-halaman">Perubahan Jadwal Pegawai</div>
    <div id="bluemenu" class="bluetabs" style="background:url(<?=base_url()?>css/media/bluetab.gif)">    
        <ul>
			<li>
                <a href="#" id="btnAdd" title="Tambah"><img src="<?=base_url()?>images/icon-tambah.png" /> Tambah</a>
                <a href="#" id="btnEdit" title="Ubah"><img src="<?=base_url()?>images/icon-edit.png" /> Ubah</a>
                <?php /*?><a href="#" id="btnDelete" title="Hapus"><img src="<?=base_url()?>images/icon-hapus.png" /> Hapus</a><?php */?>   
            </li>            
            <li>
                <a id="btnPosting" title="Posting"><img src="<?=base_url()?>images/icon-posting.png" /> Posting</a>	
            </li> 
        </ul>
    </div>
    <div id="parameter-tambahan">
    	Nama Proyek : 
        <input class="easyui-combobox" id="reqPermohonanProyekId" name="reqPermohonanProyekId" style="width:350px;" data-options="
            url: '<?=base_url()?>permohonan_proyek_json/combobox_perubahan_jadwal',
            method: 'get',
            valueField:'id', 
            textField:'text'
        " value="<?=$reqPermohonanProyekId?>">
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
        <tr>
			<th>Id</th>
			<th>Id</th>
			<th>Id</th>
			<th>Id</th>
            <th width="100px">NID</th>
            <th width="100px">Nama Pegawai</th>
            <th width="100px">Jabatan Pegawai</th>
            <th width="100px">Tanggal Pulang Awal</th>
            <th width="100px">Alasan Pulang Awal</th>
            <th width="100px">Approval 1</th>
            <th width="100px">Approval 2</th>
        </tr>
    </thead>
    </table> 
</div>
</body>
</html>