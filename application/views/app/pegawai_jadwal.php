<?php
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/date.func.php");

$tinggi = 212;
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
			  "sAjaxSource": "<?=base_url()?>pegawai_jadwal_json/json/?reqCabang=<?=$reqCabang?>&reqTahun=<?=$reqTahun?>&reqBulan=<?=$reqBulan?>",			    
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
			  
			   $("#reqBulan").change(function() {
				 fnReloadAjax("pegawai_jadwal_json/?reqCabang="+$("#reqCabang").combobox("getValue")+"&reqTahun="+$("#reqTahun").val()+"&reqBulan="+$("#reqBulan").val()+"&reqStatus="+$("#reqStatus").val());				 	 
			  });

			  $("#reqTahun").change(function() { 
				 fnReloadAjax("pegawai_jadwal_json/?reqCabang="+$("#reqCabang").combobox("getValue")+"&reqTahun="+$("#reqTahun").val()+"&reqBulan="+$("#reqBulan").val()+"&reqStatus="+$("#reqStatus").val());				 	 
			  });
			  
			  $('#btnAdd').on('click', function () {
				  window.parent.openPopup("<?=base_url()?>app/loadUrl/app/jam_kerja_ramadhan_add");
				  
				  // tutup flex dropdown => untuk versi mobile
				  $('div.flexmenumobile').hide()
				  $('div.flexoverlay').css('display', 'none')

			  });
			  
			  $('#btnEdit').on('click', function () {
				  if(anSelectedData == "")
					  return false;				
				  window.parent.openPopup("<?=base_url()?>app/loadUrl/app/jam_kerja_ramadhan_add/"+anSelectedId);
					
				  // tutup flex dropdown => untuk versi mobile
				  $('div.flexmenumobile').hide()
				  $('div.flexoverlay').css('display', 'none')
			  });
			  
			  $('#btnDelete').on('click', function () {
					if(anSelectedData == "")
						  return false;	
					$.messager.confirm('Konfirmasi',"Hapus data terpilih?",function(r){
						if (r){
							$.getJSON("<?=base_url()?>jam_kerja_ramadhan_json/delete/"+anSelectedId,
							  function(data){
									  $.messager.alert('Info', data.PESAN, 'info');
									  oTable.fnReloadAjax("<?=base_url()?>jam_kerja_ramadhan_json/json");
							});
												
						}
					});	
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
    <div class="judul-halaman">Jadwal Pegawai Shift</div>
    <div id="bluemenu" class="bluetabs" style="background:url(<?=base_url()?>css/media/bluetab.gif)">    
        <ul>
			<li>
                <a href="#" id="btnSetting" title="setting"> Atur Jadwal</a>
                <a href="#" id="btnSalin" title="setting"> Salin Jadwal</a>
            </li>        
        </ul>
    </div>
    <div id="parameter-tambahan">
        Periode :
            <select name="reqBulan" id="reqBulan">
            <?
            for($i=1; $i<=12; $i++)
            {
				$tempNama=getNameMonth($i);
                $temp=generateZeroDate($i,2);
            ?>
                <option value="<?=$temp?>" <? if($temp == (int)$reqBulan) echo 'selected'?>><?=$tempNama?></option>
            <?
            }
            ?>
            </select>
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
        &nbsp;&nbsp;
        Status Entri :   
        	<select name="reqStatus" id="reqStatus">
            	<option value="">Semua</option>
            	<option value="0">Belum Entri</option>
            	<option value="1">Sudah Entri</option>
            </select>
        &nbsp;&nbsp; 
        Cabang : 
        <input class="easyui-combobox" name="reqCabang" style="width:280px;" data-options="
                                    url: '<?=base_url()?>cabang_combo_json/json?reqMode=pegawai_jadwal',
                                    method: 'get',
                                    valueField:'value',
                                    textField:'text',
                                    groupField:'group'
                                " value="<?=$reqCabang?>">             
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
        <tr>
            <th>Id</th>
            <th width="70px">NRP</th>
            <th width="250px">Nama</th>
			<th width="200px">Jabatan</th>
			<th width="50px">Kelas</th>
            <th width="200px">Kelompok</th>
            <th width="90px">Status</th>
        </tr>
    </thead>
    </table> 
</div>
</body>
</html>