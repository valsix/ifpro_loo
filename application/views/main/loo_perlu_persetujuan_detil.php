<?php
include_once("functions/string.func.php");
include_once("functions/date.func.php");
$this->load->library('ReportLoo');
$this->load->library('suratmasukinfo');
$this->load->model("TrLoo");

$rloo= new ReportLoo();
$suratmasukinfo= new suratmasukinfo();

$reqId= $this->input->get("reqId");
$reqMode= $this->input->get("reqMode");

/*$set= new TrLoo();
$statement= " 
--AND SM_INFO NOT IN ('AKAN_DISETUJUI', 'NEXT_DISETUJUI')
AND 
(
	(
		(
			A.USER_ATASAN_ID = '".$this->ID."' AND A.APPROVAL_DATE IS NULL AND COALESCE(NULLIF(A.NIP_ATASAN_MUTASI, ''), NULL) IS NULL
			AND TERPARAF IS NULL
			--AND CASE WHEN A.STATUS_SURAT = 'PEMBUAT' THEN A.USER_ATASAN_ID = A.USER_ID END
		)
		OR 
		(
			A.NIP_ATASAN_MUTASI = '".$this->ID."' AND A.APPROVAL_DATE IS NULL AND COALESCE(NULLIF(A.USER_ATASAN_ID, ''), NULL) IS NOT NULL
			AND TERPARAF IS NULL
			-- TAMBAHAN ONE TES
			AND A.USER_ID IS NOT NULL
			--AND CASE WHEN A.STATUS_SURAT = 'PEMBUAT' THEN A.USER_ATASAN_ID = A.USER_ID END
		)
	) 
	OR 
	(
		(
			A.USER_ATASAN_ID = '".$this->USER_GROUP.$this->ID."' AND A.APPROVAL_DATE IS NOT NULL AND COALESCE(NULLIF(A.NIP_ATASAN_MUTASI, ''), NULL) IS NULL
		)
		OR 
		(
			A.NIP_ATASAN_MUTASI = '".$this->USER_GROUP.$this->ID."' AND A.APPROVAL_DATE IS NOT NULL AND COALESCE(NULLIF(A.USER_ATASAN_ID, ''), NULL) IS NOT NULL
		)
	)
	OR 
	(
		A.USER_ID = '".$this->ID."'
		AND CASE WHEN A.USER_ID = '".$this->ID."' THEN TERPARAF IS NOT NULL ELSE TERPARAF IS NULL END
		AND A.STATUS_SURAT = 'PEMBUAT'
	)
	OR 
	(
		A.USER_ID = '".$this->ID."'
		AND CASE WHEN A.USER_ID = '".$this->ID."' THEN TERPARAF IS NULL ELSE TERPARAF IS NOT NULL END
		AND A.STATUS_SURAT != 'PEMBUAT'
	)
) AND A.STATUS_SURAT IN ('VALIDASI', 'PARAF', 'PEMBUAT')";

$satuankerjaganti= "";
if($this->SATUAN_KERJA_ID_ASAL_ASLI == $this->SATUAN_KERJA_ID_ASAL)
{
}
else
{
	$satuankerjaganti= $this->SATUAN_KERJA_ID_ASAL;
}
$satuankerjaganti= $this->SATUAN_KERJA_ID_ASAL;

// if ($satuankerjaganti == "PST1164000") 
// 				$satuankerjaganti= "PST2000000";
				
$sOrder= "";
// $set->selectByParamsPersetujuan(array("A.TR_LOO_ID"=>$reqId), -1, -1, $this->ID, $statement, $sOrder);
$set->selectByParamsNewPersetujuan(array("A.TR_LOO_ID"=>$reqId), -1, -1, $this->ID, $this->USER_GROUP, $statement, $sOrder, $reqId, $satuankerjaganti);
$set->firstRow();
 // echo $set->query;exit;
$checkid= $set->getField("TR_LOO_ID");
$checkstatusbantu= $set->getField("STATUS_BANTU");
$checkstatussurat= $set->getField("STATUS_SURAT");
$checkuserid= $set->getField("USER_ID");
// echo $checkstatussurat;exit;
$checkinfonomorsurat= $set->getField("INFO_NOMOR_SURAT");
// echo $checkinfonomorsurat;exit;
$arrinfonomorsurat= explode("[...]", $checkinfonomorsurat);
// print_r($arrinfonomorsurat);exit;

// untuk ambil data nomor berdasarkan tanggal entri
$tanggalapproval= date("d-m-Y");
// $setlast= new TrLoo();
// $setlast->selectByParamsInfoLastNomorSurat(array("A.TR_LOO_ID"=>$reqId), -1, -1);
// $setlast->firstRow();
// $checkinfolastnomorsurat= $setlast->getField("INFO_NOMOR_SURAT");
$setlast= new TrLoo();
$setlast->selectByParamsCheckNomor("GETINFO", $reqId, "", dateToDbCheck($tanggalapproval));
// echo $setlast->query;exit;
$setlast->firstRow();
$checkinfolastnomorsurat= $setlast->getField("INFO_NOMOR_SURAT");
// echo $checkinfolastnomorsurat;exit;
unset($setlast);

$checkpernahsetujui= 0;
if (empty($checkid))
{
	$set= new TrLoo();
	$statement= " AND SM_INFO IN ('AKAN_DISETUJUI', 'PEMBUAT') AND A.STATUS_SURAT IN ('PARAF', 'VALIDASI', 'PEMBUAT')";
	$checkpernahsetujui= $set->getCountByParamsStatus(array("A.TR_LOO_ID"=>$reqId), $this->ID, $statement);
	// echo $set->query;exit;

	if($checkpernahsetujui > 0)
	{
		$set= new TrLoo();
        $set->selectByParams(array("A.TR_LOO_ID"=>$reqId));
        $set->firstRow();
        // echo $set->query;exit;
        $infojenisnaskahid= $set->getField("JENIS_NASKAH_ID");
        unset($set);

        $arrlink= $suratmasukinfo->infolinkdetil($infojenisnaskahid);
        $infolinkdetil= $arrlink["linkstatusdetil"];
        // echo "main/index/".$infolinkdetil."/?reqMode=".$reqMode."&reqId=".$reqId;exit;

        redirect("main/index/".$infolinkdetil."/?reqStatusSurat=AKAN_DISETUJUI&reqMode=".$reqMode."&reqId=".$reqId);
	}
	else
	{
		redirect("main/index/perlu_persetujuan");
	}
}*/

$set= new TrLoo();
$set->selectByParams(array("A.TR_LOO_ID"=>$reqId));
$set->firstRow();
// echo $set->query;exit;
$userposisiparaf= $set->getField("USER_POSISI_PARAF_ID");
$infojenissurat= $set->getField("JENIS_SURAT");
$lampirandrive= $set->getField("LAMPIRAN_DRIVE");
$infoperihal= $set->getField("PERIHAL");
$infojenisnaskahid= $set->getField("JENIS_NASKAH_ID");
$reqSatuanKerjaId= $set->getField("SATUAN_KERJA_ID_ASAL");
$reqJenisSurat= $set->getField("JENIS_SURAT");
$reqStatusSurat= $set->getField("STATUS_DATA");
// echo $reqJenisSurat;exit;
unset($set);

$sessid= $this->ID;
$sesssatuankerjaasalid= $this->SATUAN_KERJA_ID_ASAL_ASLI;
$sesssatuankerjaid= $this->SATUAN_KERJA_ID_ASAL;
$checkparafid= "";
if (!empty($reqId))
{
    /*$statement.= " AND A.USER_POSISI_PARAF_ID = '".$sessid."' AND A.TR_LOO_ID = ".$reqId;
    $set= new TrLoo();
    $set->selectdraft(array(), -1, -1, $statement);
    // echo $set->query;exit;
    $set->firstRow();
    $checkparafid= $set->getField("TR_LOO_ID");
    $checknextpemaraf= $set->getField("NEXT_URUT");
    $checkstatusbantu= $set->getField("STATUS_BANTU");
    $chekvalidasi= "";
    if(isset($checknextpemaraf))
        $chekvalidasi= "validasi";
    // echo $chekvalidasi."-".$checknextpemaraf."--".$infonextpemaraf;exit;
	*/
    $userposisibantuparaf= $sessid."-".$sesssatuankerjaid;
	if($userposisiparaf == $sessid || $userposisiparaf == $userposisibantuparaf){}
	else
	{
	    if(!empty($reqMode))
	    {
	        redirect("main/index/".$reqMode);
	    }
	    else
	    {
	        redirect("main/index/loo_perlu_persetujuan");
	    }
	}
}
$arrparam= ["reqId"=>$reqId, "reqStatusSurat"=>$reqStatusSurat];
$rloo->setterbaca($arrparam);

$infolinkdetil= $reqMode;
$infolinkedit= "loo_add";

$arrattachment= array();
$index_data= 0;
$set= new TrLoo();
$set->selectByParamsAttachment(array("A.TR_LOO_ID" => (int)$reqId));
while($set->nextRow())
{
    $arrattachment[$index_data]["NAMA"] = $set->getField("NAMA");
    $arrattachment[$index_data]["UKURAN"] = $set->getField("UKURAN");
    $arrattachment[$index_data]["ATTACHMENT"] = $set->getField("ATTACHMENT");
    $arrattachment[$index_data]["TIPE"] = $set->getField("TIPE");
    $index_data++;
}
$jumlahattachment= $index_data;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>

<head>
	<base href="<?=base_url()?>">
	<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
	<title>Diklat</title>
	<link rel="shortcut icon" type="image/ico" href="http://www.datatables.net/media/images/favicon.ico">
	<!--<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="http://www.datatables.net/rss.xml">-->
	<link href="css/admin.css" rel="stylesheet" type="text/css">

	<!-- Custom Fonts -->
	<link href="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

	<style type="text/css" media="screen">
		@import "lib/media/css/site_jui.css";
		@import "lib/media/css/demo_table_jui.css";
		@import "lib/media/css/themes/base/jquery-ui.css";
	</style>

	<link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.6/media/css/jquery.dataTables.css">
	<link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.6/examples/resources/syntax/shCore.css">
	<link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.6/examples/resources/demo.css">

	<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.6/media/js/jquery.dataTables.js"></script>
	<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.6/examples/resources/syntax/shCore.js"></script>
	<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.6/examples/resources/demo.js"></script>
	<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.6/extensions/FixedColumns/js/dataTables.fixedColumns.js"></script>
	<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.6/extensions/TableTools/js/dataTables.tableTools.min.js"></script>

	<link href="lib/media/themes/main_datatables.css" rel="stylesheet" type="text/css" />
	<link href="css/begron.css" rel="stylesheet" type="text/css">
	<link href="css/bluetabs.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="js/dropdowntabs.js"></script>
    
    <style>
	.test:after {
	  content: '\2807';
	  *font-size: 3em;
	  color: #2e2e2e
	}
	</style>

	<script type="text/javascript">
		function setagenda()
		{
			document.location.href = 'main/index/<?=$infolinkdetil?>?reqMode=<?=$reqMode?>_detil&reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>';
		}

		function setkembali()
		{
			document.location.href = 'main/index/<?=$reqMode?>';
		}

		function setdetil()
		{
			document.location.href = 'main/index/<?=$infolinkdetil?>';
		}

		function setubah()
		{
			document.location.href = 'main/index/<?=$infolinkedit?>?reqMode=loo_perlu_persetujuan_detil&reqId=<?=$reqId?>';
		}

		var urllink= reqMode= method= "";
		function submitForm(reqStatusSurat) 
		{
			infopesandetil= "Komentar";
	        if (reqStatusSurat == "REVISI")
	        {
	        	urllink= "web/trloo_json/revisi";
	        	reqMode= "manual";
	            infopesandetil += " Kembalikan surat ke staff anda?";
	            method= "POST";
	        }

	        if (reqStatusSurat == "PARAF")
	        {
	        	urllink= "web/trloo_json/logparaf";
	        	reqMode= "";
	            infopesandetil += " Paraf naskah?";
	            // method= "GET";
	            method= "POST";
	        }

	        if (reqStatusSurat == "POSTING")
	        {
	        	urllink= "web/trloo_json/logposting";
	        	reqMode= "";
	            infopesandetil += " Kirim naskah?";
	        	<?
	        	if($checkstatussurat == "PEMBUAT")
	        	{
	        	?>
	        	infopesandetil = " Entry tanggal approval?";
	        	<?
	        	}
	        	?>
	            // method= "GET";
	            method= "POST";
	        }

	        if (reqStatusSurat == "PARAF" || reqStatusSurat == "REVISI" || reqStatusSurat == "POSTING")
	        {
	        	<?
	        	if($checkstatussurat == "PEMBUAT")
	        	{
	        	?>
	        		infocontent= '<form action="" class="formName">' +
	        		'<div id="myDialog" title="Text Dialog">' +
	        			'<label>Isi tanggal terlebih dahulu!</label>' +
	        			'<input type="text" id="tanggalapproval" class="name form-control" required value="<?=$tanggalapproval?>" />' +
	        		'</div>' +
	        		'<div class="form-group">' +
	        		'<label>Isi nomor surat!</label><br/>' +
	        		'<span id="infonomorawal"><?=$arrinfonomorsurat[0]?></span><input type="text" id="InfoNomor" placeholder="Isi nomor surat anda..." /><span id="infonomorakhir"><?=$arrinfonomorsurat[1]?></span>' +
	        		'<br/>nomor surat terakhir : <label id="detilnomorakhir"><?=$checkinfolastnomorsurat?></label> '+
	        		'</div>' +
	                '</form>';
	        	<?
	        	}
	        	else
	        	{
	        	?>
		        	infocontent= '<form action="" class="formName">' +
	                '<div class="form-group">' +
	                '<label>Isi komentar jika ingin mengirim dokumen ini!</label>' +
	                '<input type="text" placeholder="Tuliskan komentar anda..." class="name form-control" required />' +
	                '</div>' +
	                '</form>';
	            <?
	        	}
	            ?>

	            $.confirm({
	                title: infopesandetil,
	                <?
		        	if($checkstatussurat == "PEMBUAT")
		        	{
		        	?>
	                onOpen: function(){
	                	$("#tanggalapproval").datepicker({ dateFormat: "dd-mm-yy" }).val();

	                	// untuk ambil data nomor berdasarkan tanggal entri
						$("#tanggalapproval").change(function(){
							setinfonomorsesuaitanggalentri();
						});
	                },
	                onClose: function(){
	                	$('#tanggalapproval').datepicker('destroy');
	                },
	                <?
	            	}
	                ?>
	                content: '' + infocontent
	                ,
	                buttons: {
	                    formSubmit: {
	                        text: 'OK',
	                        btnClass: 'btn-blue',
	                        action: function () {
	                            var name = this.$content.find('.name').val();

	                            infovalidasi= "Komentar wajib diisi !";
	                            <?
					        	if($checkstatussurat == "PEMBUAT")
					        	{
					        	?>
					        		InfoNomor= this.$content.find('#InfoNomor').val();
					        		$("#reqInfoNomor").val(InfoNomor);

					        		reqInfoNomor= $("#reqInfoNomor").val();
					        		if (reqInfoNomor == "")
					        		{
					        			$.alert('<span style= color:red>Nomor surat anda wajib diisi</span>');
					        			return false;
					        		}

					        		infovalidasi= "tanggal wajib diisi !";
					        	<?
					        	}
					        	?>
	                            if (!name) {
	                                $.alert('<span style= color:red>'+infovalidasi+'</span>');
	                                return false;
	                            }

	                            $("#reqInfoLog").val(name);
	                            // reqInfoNomor
	                            setsimpan();
	                        }
	                    },
	                    cancel: function () {
	                        //close
	                    },
	                },
	                onContentReady: function () {
	                	$('#InfoNomor').bind('keyup paste', function(){
							this.value = this.value.replace(/[^0-9]/g, '');
							// this.value = this.value.replace(/[^0-9\.]/g, '');
						});

	                    // you can bind to the form
	                    var jc = this;
	                    this.$content.find('form').on('submit', function (e) { // if the user submits the form by pressing enter in the field.
	                        e.preventDefault();
	                        jc.$$formSubmit.trigger('click'); // reference the button and click it
	                    });
	                }
	            });
	        }
		}

		// untuk ambil data nomor berdasarkan tanggal entri
		function setinfonomorsesuaitanggalentri()
		{
			tanggalapproval= $('#tanggalapproval').val();
			// console.log(tanggalapproval);
			infotanggalajax= "web/trloo_json/setinfonomorsesuaitanggalentri?v=<?=$reqId?>&t="+tanggalapproval;
			$.ajax({
				url: infotanggalajax,
				type: "GET",
				dataType: "json",
				success: function(responsedata){
					// console.log(responsedata);
					// console.log(responsedata["infonomorakhir"]);
					$("#detilnomorakhir").text(responsedata["detilnomorakhir"]);
					$("#infonomorawal").text(responsedata["infonomorawal"]);
					$("#infonomorakhir").text(responsedata["infonomorakhir"]);
              	}
            });
		}

		function setsimpan()
		{
			var reqId= reqSatuanKerjaIdAsal= reqInfoLog= reqInfoNomor= "";
			reqId= "<?=$reqId?>";
			reqSatuanKerjaIdAsal= "<?=$reqSatuanKerjaId?>";
			reqInfoLog= $("#reqInfoLog").val();
			reqInfoNomor= $("#reqInfoNomor").val();
			// alert(reqInfoLog);return false;
			
			var win = $.messager.progress({title:'Proses simpan data', msg:'Proses simpan data...'});
                
			$.ajax({
		        url: urllink,
		        method: method,
		        data: {
		            reqId: reqId, 
		            reqSatuanKerjaIdAsal: reqSatuanKerjaIdAsal, 
		            reqInfoLog: reqInfoLog,
		            reqInfoNomor: reqInfoNomor,
		            reqMode: reqMode
		        },
		        // dataType: 'json',
		        success: function (response) {
		        	$.messager.progress('close');
		        	// console.log(response);return false;
		        	if(response == "0")
		        	{
		        		$.alert({
			                title: 'Info',
			                content: '<span style= color:red>gagal posting, karena nomor surat sudah ada</span>'
			                , buttons: {
			                    formSubmit: {
			                        text: 'OK',
			                        btnClass: 'btn-blue',
			                        action: function () {
			                        	location.reload();
			                        }
			                    },
			                }
			            });
		        		return false;
		        	}
		        	else
		        	{
			        	// console.log(response);return false;
			        	setdetil();
		        	}
		        },
		        error: function (response) {
		        	// console.log(response);return false;
		        },
		        complete: function () {
		        }
		    });
		} 
	</script>
    
</head>

<body style="overflow:hidden;">
	<div id="begron"><img src="images/bg-kanan.jpg" width="100%" height="100%" alt="Smile"></div>
	<div id="wadah">
		<div class="judul-halaman">
        	<a href="javascript:void(0)" onclick="setkembali()"><i class="fa fa-chevron-left" aria-hidden="true"></i></a> 
            <span class="info-perihal"><?=$infoperihal?></span>
            <!-- <div class="dropdown yamm-fw notifikasi pull-right area-button-judul">
            	<a href="#" data-toggle="dropdown" class="dropdown-toggle"><i class="fa fa-ellipsis-v" aria-hidden="true"></i></a>
                <ul class="dropdown-menu">
                    <li>
                        <div class="area-3dots-menu">
                            <div><a href="javascript:void(0)" onclick="setagenda()">Agenda Surat</a></div>
                        </div>
                    </li>
                </ul>
            </div> -->
            <div class="area-button-judul pull-right">
            	<?
            	if($checkstatussurat == "PEMBUAT")
            	{
            		if($checkuserid == $this->ID)
            		{
            	?>
            	<button onClick="submitForm('POSTING')" class="btn btn-success btn-sm">Entry Date</button>
            	<?
            		}
            	}
            	else
            	{
            	?>
            		<button onclick="setubah()" class="btn btn-default btn-sm">Edit Surat</button>
	            	<?
	            	if($reqStatusSurat == "VALIDASI")
	            	{
	            	?>
	            	<button onClick="submitForm('POSTING')" class="btn btn-success btn-sm">Setuju</button>
	            	<?
	            	}
	            	else
	            	{
	            		$infobutton= "Setuju";
	            		if($checkstatusbantu == "1")
	            			$infobutton= "Forward";
	            	?>
	                <button onClick="submitForm('PARAF')" class="btn btn-success btn-sm"><?=$infobutton?></button>
	            	<?
	            	}
	            	?>
	                <button onClick="submitForm('REVISI')" class="btn btn-warning btn-sm">Kembalikan</button>
	            <?
	        	}
	            ?>
				<input type="hidden" name="reqInfoLog" id="reqInfoLog" />
				<input type="hidden" name="reqInfoNomor" id="reqInfoNomor" />
            </div>
            <div class="clearfix"></div>
        </div>

        <?
        $classlampiran= "";
        if($jumlahattachment > 0)
        {
        	$classlampiran= "ada-lampiran";
        }
        ?>
        <div class="konten-pdf <?=$classlampiran?>">
        	<iframe name="contentFrame" src="app/loadUrl/report/loo_cetak/?reqId=<?=$reqId?>&templateSurat=loo"></iframe>
        </div>

        <?
        if(!empty($classlampiran))
        {
        	$arrexcept= [];
        	$arrexcept= array("xlsx", "xls", "doc", "docx", "ppt", "pptx", "txt");
        ?>
        <div class="konten-lampiran">
        	<ol>
        		<?
        		for($index_data=0; $index_data < $jumlahattachment; $index_data++)
        		{
        			$attnama= $arrattachment[$index_data]["NAMA"];
        			$attukuran= $arrattachment[$index_data]["UKURAN"];
        			$attlink= $arrattachment[$index_data]["ATTACHMENT"];
        			$atttipe= $arrattachment[$index_data]["TIPE"];
        			$atticon= infoiconlink($atttipe);
        		?>
                <li>
                	<div class="item"
	                	<?
	                    if(in_array(strtolower($atttipe), $arrexcept))
	                    {
	                    ?>
	                    onclick="window.open('<?=base_url()."uploads/".$reqId."/".$attlink?>', '_blank')"
	                    <?
	                    }
	                    else
	                    {
	                    ?>
	                    onclick="parent.openAdd('<?=base_url()."uploads/".$reqId."/".$attlink?>')"
	                    <?
	                    }
	                    ?>
                    >
                        <div class="ikon"><?=$attnama?> <i class="fa <?=$atticon?>"></i></div>
                        <div class="ukuran-file"><?=round(($attukuran/1024), 2)?> kb</div>
                    </div>
                </li>
                <?
            	}
                ?>
                <?if ($lampirandrive !=''){?>
                    <li>
                           <div class="item"onclick="window.open('<?=$lampirandrive?>', '_blank')">
                            <div class="ikon"><?=$lampirandrive?> <i class="fa fa-link"></i></div>
                            <div class="ukuran-file"><?=round(($attukuran/1024), 2)?> kb</div>
                        </div>
                    </li>
                <?}?>
            </ol>
        </div>
        <?
    	}
        ?>
        
	</div>


    <!-- jQUERY CONFIRM MASTER -->
    <link rel="stylesheet" type="text/css" href="lib/jquery-confirm-master/css/jquery-confirm.css"/>
    <script type="text/javascript" src="lib/jquery-confirm-master/js/jquery-confirm.js"></script>

    <link rel="stylesheet" href="lib/js/jquery-ui.css">
    <script src="lib/js/jquery-ui.js"></script>

    <link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
	<script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="lib/easyui/kalender-easyui.js"></script>
	<script type="text/javascript" src="lib/easyui/globalfunction.js"></script>
    
</body>

</html>