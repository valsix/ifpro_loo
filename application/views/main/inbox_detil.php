<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model("SuratMasuk");
$this->load->model("SuratMasukParaf");
$this->load->model("Disposisi");
$surat_masuk = new SuratMasuk();
$disposisi   = new Disposisi();
$surat_masuk_paraf = new SuratMasukParaf();

$reqId = $this->input->get("reqId");
$reqDisposisiId = $this->input->get("reqDisposisiId");

$reqMode = $this->input->get("reqMode");

$reqIdDraft = $reqId;

// tambahan khusus
$statement_privacy .= " 
AND (
    A.STATUS_SURAT = 'POSTING' 
    OR
    A.STATUS_SURAT = 'TU-NOMOR'
    OR 
    (
        A.STATUS_SURAT = 'TU-IN' AND
        EXISTS(SELECT 1 FROM SURAT_MASUK_ARSIP X WHERE X.SURAT_MASUK_ID = A.SURAT_MASUK_ID AND X.CABANG_ID = '".$this->CABANG_ID."')
    )
) ";

if($this->KD_LEVEL_PEJABAT == "")
	$statement_privacy .= " AND (B.USER_ID = '".$this->ID."' OR B.USER_ID = '".$this->ID_ATASAN."') ";
else
	$statement_privacy .= " AND (B.SATUAN_KERJA_ID_TUJUAN = '".$this->SATUAN_KERJA_ID_ASAL."' OR B.USER_ID = '".$this->ID."') ";

$surat_masuk->selectByParamsInbox(array("A.SURAT_MASUK_ID" => $reqId, "B.DISPOSISI_ID" => $reqDisposisiId), -1, -1, $statement_privacy);
// echo $surat_masuk->query;exit;
$surat_masuk->firstRow();

$reqDisposisiId = $surat_masuk->getField("DISPOSISI_ID");
$refSuratId = $surat_masuk->getField("SURAT_MASUK_REF_ID");
$reqId          = $surat_masuk->getField("SURAT_MASUK_ID");
$reqJenisNaskah = $surat_masuk->getField("JENIS");
$reqKlasifikasiId = $surat_masuk->getField("KLASIFIKASI_ID");
$reqNoAgenda    = $surat_masuk->getField("NO_AGENDA");
$reqNoSurat     = $surat_masuk->getField("NOMOR");
$reqTanggal     = $surat_masuk->getField("TANGGAL");
$reqTanggalEntri     = $surat_masuk->getField("TANGGAL_ENTRI");
$reqPerihal     = $surat_masuk->getField("PERIHAL");
$reqKeterangan  = $surat_masuk->getField("ISI");
$reqSifatNaskah = $surat_masuk->getField("SIFAT_NASKAH");
$reqStatusSurat = $surat_masuk->getField("STATUS_SURAT");
$reqLokasiSurat = $surat_masuk->getField("LOKASI_SIMPAN");
$reqAsalSuratInstansi   =  $surat_masuk->getField("INSTANSI_ASAL");
$reqAsalSuratKota       =  $surat_masuk->getField("KOTA_ASAL");
$reqAsalSuratAlamat     =  $surat_masuk->getField("ALAMAT_ASAL");
$reqPenyampaianSurat    =  $surat_masuk->getField("PENYAMPAIAN_SURAT");
$reqUserAtasanId        =  $surat_masuk->getField("USER_ATASAN_ID");
$reqRevisi              =  $surat_masuk->getField("REVISI");
$reqTanggalKegiatan 	 =  $surat_masuk->getField("TANGGAL_KEGIATAN_EDIT");
$reqTanggalKegiatanAkhir =  $surat_masuk->getField("TANGGAL_KEGIATAN_AKHIR_EDIT");
$reqJamKegiatan          =  $surat_masuk->getField("JAM_KEGIATAN_EDIT");
$reqJamKegiatanAkhir     =  $surat_masuk->getField("JAM_KEGIATAN_AKHIR_EDIT");
$reqIsEmail       =  $surat_masuk->getField("IS_EMAIL");
$reqIsMeeting     =  $surat_masuk->getField("IS_MEETING");
$reqKlasifikasiKode     =  $surat_masuk->getField("KLASIFIKASI_KODE");
$reqKlasifikasi     =  $surat_masuk->getField("KLASIFIKASI");
$reqTERDISPOSISI     =  $surat_masuk->getField("TERDISPOSISI");


// tambahan khusus
$setdetil= new Disposisi();
$checkdata= $setdetil->getCountByParams(array(), " AND DISPOSISI_PARENT_ID = ".$reqDisposisiId);
if($checkdata > 0)
{
    if($reqMode == "DISPOSISI")
    {
        //redirect("main/index/inbox_detil_fhm/?reqId=".$reqId."&reqDisposisiId=".$reqDisposisiId);
		redirect("main/index/inbox");
    }
}

if($reqId == "")
	exit;

$reqParaf       = $surat_masuk_paraf->getJson(array("SURAT_MASUK_ID" => $reqId));
$reqKepada      = $disposisi->getJson(array("SURAT_MASUK_ID" => $reqId, "STATUS_DISPOSISI" => "TUJUAN"));
$reqTembusan    = $disposisi->getJson(array("SURAT_MASUK_ID" => $reqId, "STATUS_DISPOSISI" => "TEMBUSAN"));

?>

<?php /*?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<base href="<?=base_url()?>">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
<meta name="description" content="">
<meta name="author" content="">
<link rel="icon" href="libraries/bootstrap-3.3.7/docs/favicon.ico"><?php */?>

<?php /*?><script src="libraries/xeditable/jquery/jquery-1.9.1.js"></script><?php */?>
<!-- Bootstrap core CSS -->
<?php /*?><link href="libraries/bootstrap-3.3.7/docs/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="libraries/bootstrap-3.3.7/docs/assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">
<link href="libraries/bootstrap-3.3.7/docs/examples/starter-template/starter-template.css" rel="stylesheet">
<script src="libraries/bootstrap-3.3.7/docs/assets/js/ie-emulation-modes-warning.js"></script>

<link rel="stylesheet" href="css/gaya.css" type="text/css">
<link rel="stylesheet" href="libraries/font-awesome-4.7.0/css/font-awesome.css" type="text/css">
<link rel="stylesheet" href="css/gaya-egateway.css" type="text/css"><?php */?>
    

<?php /*?><script src='libraries/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script> 
<script src='libraries/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script> 
<script src='libraries/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script> 
<link rel="stylesheet" href="css/gaya-multifile.css" type="text/css"><?php */?>

<!--</head>

<body>-->

<div class="col-lg-12 col-konten-full">
    <div class="judul-halaman bg-course">
    	<? if($reqMode == "DISPOSISI") { ?>LEMBAR DISPOSISI<? } ?>
        <? if($reqMode == "BALAS") { ?>LEMBAR RESPON / BALASAN<? } ?>
        <? if($reqMode == "TERUSKAN") { ?>TERUSKAN SURAT<? } ?>
    </div>
    <div class="konten-detil">
    	<div class="area-kanan area-detil-surat">
            <div class="area-surat-detil">
            	
            	<div class="area-lembar-disposisi">
                
            	<?
                if($reqMode == "DISPOSISI")
				{
				?>
                
					<script>
                        function submitDisposisi(){
                            
                            $('#ff').form('submit',{
                                url:'web/inbox_json/add',
                                onSubmit:function(){
                                    var isiBalas = $("#reqBalasCepatCombo").combotree("getText");
                                    $("#reqBalasCepat").val(isiBalas);
                                    return $(this).form('enableValidation').form('validate');
                                },
                                success:function(data){
                                    var arrData = data.split("-");
                                    if(arrData[0] == 'X')
                                        $.messager.alert('Info', arrData[1], 'info'); 
                                    else
                                        $.messager.alertLink('Info', data, 'info', "app/loadUrl/app/inbox"); 
                                }
                            });
                        }
                        function clearForm(){
                            $('#ff').form('clear');
                        }
                                
                    </script> 
                    <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">
        
                        <!-- Adding "responsive" class triggers the magic -->
                          <div class="tabbable responsive">
                            <?php /*?><ul class="nav nav-tabs">
                              <li class="active"><a href="#tab1" data-toggle="tab">LEMBAR DISPOSISI</a></li>
                              <!--<li><a href="#tab2" data-toggle="tab">HISTORI DISPOSISI ANDA</a></li>
                              <li><a href="#tab3" data-toggle="tab">ISI DISPOSISI</a></li>-->
                            </ul><?php */?>
                            <div class="tab-content">
                              <div class="tab-pane fade in active" id="tab1">
                                <div class="form-group">
                                    <label for="reqNama" class="control-label col-md-2">Kepada</label>
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <input type="text" id="reqSatuanKerjaIdTujuan" class="easyui-combotree textbox form-control"   
                                                data-options="editable:false, cascadeCheck: false, required: true, valueField:'id',textField:'text',url:'web/satuan_kerja_json/disposisi/?reqTujuan=<?=$surat_masuk->getField("SATUAN_KERJA_ID_TUJUAN")?>',prompt:'Tentukan tujuan disposisi...'"
                                                multiple
                                                name="reqSatuanKerjaIdTujuan[]"  value="" style="width:300%" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
            
                                <div class="form-group">
                                    <label for="reqNama" class="control-label col-md-2">Nota Tindakan</label>
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <input type="text" id="reqBalasCepatCombo" class="easyui-combotree textbox form-control"  
                                                data-options="valueField:'id',textField:'text',url:'web/balas_disposisi_json/combo',prompt:'Isi disposisi...'"
                                                multiple
                                                name="reqBalasCepatCombo"  value="" style="width:300%" />
                                                <input type="hidden" id="reqBalasCepat" name="reqBalasCepat" value="">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="reqNama" class="control-label col-md-2">Catatan</label>
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <input type="text" class="easyui-validatebox textbox form-control"  
                                                data-options="prompt:'Isi pesan...'"
                                                name="reqKeterangan" id="reqKeterangan" style="width:100%" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="reqNama" class="control-label col-md-2" style="padding-top: 10px;">Sifat</label>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <label class="radio-inline">
                                                  <input type="radio" name="optradio" checked>Biasa
                                                </label>
                                                <label class="radio-inline">
                                                  <input type="radio" name="optradio">Rahasia
                                                </label>
                                                <label class="radio-inline">
                                                  <input type="radio" name="optradio">Segera
                                                </label>
                                                <label class="radio-inline">
                                                  <input type="radio" name="optradio">Sangat Segera
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                    	<input type="hidden" name="reqDisposisiId" value="<?=$reqDisposisiId?>">
                                        <input type="hidden" name="reqId" value="<?=$reqId?>">
                                        <button type="button" class="btn btn-sm btn-primary pull-right" onclick="submitDisposisi()"><i class="fa fa-paper-plane"></i> Kirim Disposisi</button>
                                    </div>
                                </div>
                                
                                <?php /*?><div class="form-group">
                                    <label for="reqNama" class="control-label col-md-2">Lampiran</label>
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <label for="file-upload" class="custom-file-upload">
                                                    <i class="fa fa-cloud-upload"></i> Pilih File
                                                </label>
                                                <input name="reqLinkFile[]" type="file" maxlength="4"  class="multi with-preview maxsize-2048" accept="pdf" value=""/>    
                                            </div>
                                        </div>
                                    </div>
                                </div><?php */?>
                                <?php /*?><div style=";padding:5px; text-align:center">
                                
                                    
                                </div><?php */?>
                              </div>
                            </div> <!-- /tab-content -->
                          </div> <!-- /tabbable -->
                </form>
                <?
                }
                ?>
                    
                    
                    
            	<?
                if($reqMode == "BALAS")
				{
				?>
                
					<script>
                        function submitBalas(){
                            
                            $('#ff').form('submit',{
                                url:'web/inbox_json/balas',
                                onSubmit:function(){
                                    var isiBalas = $("#reqBalasCepatCombo").combotree("getText");
                                    $("#reqBalasCepat").val(isiBalas);
                                    return $(this).form('enableValidation').form('validate');
                                },
                                success:function(data){
                                    var arrData = data.split("-");
                                    if(arrData[0] == 'X')
                                        $.messager.alert('Info', arrData[1], 'info'); 
                                    else
                                        $.messager.alertLink('Info', data, 'info', "app/loadUrl/app/inbox"); 
                                }
                            });
                        }
                        function clearForm(){
                            $('#ff').form('clear');
                        }
                                
                    </script> 
                    <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">
        
                        <!-- Adding "responsive" class triggers the magic -->
                          <div class="tabbable responsive">
                            <?php /*?><ul class="nav nav-tabs">
                              <li class="active"><a href="#tab1" data-toggle="tab">LEMBAR RESPON / BALASAN</a></li>
                              <!--<li><a href="#tab2" data-toggle="tab">HISTORI DISPOSISI ANDA</a></li>
                              <li><a href="#tab3" data-toggle="tab">ISI DISPOSISI</a></li>-->
                            </ul><?php */?>
                            <div class="tab-content">
                              <div class="tab-pane fade in active" id="tab1">
                                <div class="form-group">
                                    <label for="reqNama" class="control-label col-md-2">Kepada</label>
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <strong><?=$surat_masuk->getField("NAMA_SATKER_ASAL")?></strong>
                                                <input type="hidden" name="reqSatuanKerjaIdTujuan" value="<?=$surat_masuk->getField("SATUAN_KERJA_ID_ASAL")?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
            
                                <div class="form-group">
                                    <label for="reqNama" class="control-label col-md-2">Pesan</label>
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <input type="text" id="reqBalasCepatCombo" class="easyui-combotree textbox form-control"  
                                                data-options="valueField:'id',textField:'text',url:'web/balas_cepat_json/combo',prompt:'Isi balasan...'"
                                                multiple
                                                name="reqBalasCepatCombo"  value="" style="width: 300%" />
                                                <input type="hidden" id="reqBalasCepat" name="reqBalasCepat" value="">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="reqNama" class="control-label col-md-2">Keterangan</label>
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <input type="text" class="easyui-validatebox textbox form-control"  
                                                data-options="prompt:'Isi pesan...'"
                                                name="reqKeterangan" id="reqKeterangan" style="width:100%" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="reqNama" class="control-label col-md-2">Lampiran</label>
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <div class="col-md-12">

                                                <label for="file-upload" class="custom-file-upload">
                                                    <i class="fa fa-cloud-upload"></i> Pilih File
                                                </label>
                                                <input name="reqLinkFile[]" type="file" maxlength="4"  class="multi with-preview maxsize-2048" accept="pdf" value=""/>    
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div style=";padding:5px; text-align:center">
                                
                                    <input type="hidden" name="reqDisposisiId" value="<?=$reqDisposisiId?>">
                                    <input type="hidden" name="reqId" value="<?=$reqId?>">
                                    <button type="button" class="btn btn-primary" onclick="submitBalas()">Kirim Balasan</button>
                                </div>
                              </div>
                            </div> <!-- /tab-content -->
                          </div> <!-- /tabbable -->
                </form>
                <?
                }
                ?>
                
                
            	<?
                if($reqMode == "TERUSKAN")
				{
				?>
                
					<script>
                        function submitTeruskan(){
                            
                            $('#ff').form('submit',{
                                url:'web/inbox_json/teruskan',
                                onSubmit:function(){
                                    return $(this).form('enableValidation').form('validate');
                                },
                                success:function(data){
                                    var arrData = data.split("-");
                                    if(arrData[0] == 'X')
                                        $.messager.alert('Info', arrData[1], 'info'); 
                                    else
                                        $.messager.alertLink('Info', data, 'info', "app/loadUrl/app/inbox"); 
                                }
                            });
                        }
                        function clearForm(){
                            $('#ff').form('clear');
                        }
                                
                    </script> 
                    <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">
        
                        <!-- Adding "responsive" class triggers the magic -->
                          <div class="tabbable responsive">
                            <?php /*?><ul class="nav nav-tabs">
                              <li class="active"><a href="#tab1" data-toggle="tab">TERUSKAN SURAT</a></li>
                              <!--<li><a href="#tab2" data-toggle="tab">HISTORI DISPOSISI ANDA</a></li>
                              <li><a href="#tab3" data-toggle="tab">ISI DISPOSISI</a></li>-->
                            </ul><?php */?>
                            <div class="tab-content">
                              <div class="tab-pane fade in active" id="tab1">
                                <div class="form-group">
                                    <label for="reqNama" class="control-label col-md-2">Kepada</label>
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <input type="text" id="reqSatuanKerjaIdTujuan" class="easyui-combotree textbox form-control"   
                                                data-options="editable:false, cascadeCheck: false, required: true, valueField:'id',textField:'text',url:'web/satuan_kerja_json/teruskan',prompt:'Tentukan tujuan...'"
                                                multiple
                                                name="reqSatuanKerjaIdTujuan[]"  value="" style="width:300%" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
            
                                <div class="form-group">
                                    <label for="reqNama" class="control-label col-md-2">Pesan</label>
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <input type="text" class="easyui-validatebox textbox form-control"  
                                                data-options="prompt:'Isi pesan...'"
                                                name="reqBalasCepat"  style="width:100%" />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="reqNama" class="control-label col-md-2">Keterangan</label>
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <input type="text" class="easyui-validatebox textbox form-control"  
                                                data-options="prompt:'Isi pesan...'"
                                                name="reqKeterangan" id="reqKeterangan" style="width:100%" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div style=";padding:5px; text-align:center">
                                
                                    <input type="hidden" name="reqDisposisiId" value="<?=$reqDisposisiId?>">
                                    <input type="hidden" name="reqId" value="<?=$reqId?>">
                                    <button type="button" class="btn btn-primary" onclick="submitTeruskan()">Teruskan</button>
                                </div>
                              </div>
                            </div> <!-- /tab-content -->
                          </div> <!-- /tabbable -->
                </form>
                <?
                }
                ?>
                </div>
                <div class="data">
                    <div class="header-profil">
                        <div class="col-md-1 area-kiri">
                			<div class="avatar"><img src="images/img-user.png"></div>
                        </div>
                        <div class="col-md-8 area-kiri">
                            <div class="nama">
                            <?=$surat_masuk->getField("USER_ATASAN")?>
                            </div>
                            <div class="jabatan">
                            <?=$surat_masuk->getField("USER_ATASAN_JABATAN")?>
                            </div>
                            <div class="email"><?=getFormattedDate($reqTanggalEntri)?></div>
                        </div>
                        <div class="col-md-3 area-kanan">
                            <div class="tanggal"><i class="fa fa-tags"></i> <?=$reqJenisNaskah?></div>
                            <div class="tanggal"><i class="fa fa-exclamation-triangle"></i> <?=$reqSifatNaskah?></div>
                            <div class="waktu"></div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="header-surat">
                        <div class="col-md-8 area-kiri">
        					<div class="nomor-surat">No. surat : <?=$reqNoSurat?></div>
                            <div class="judul-surat"><?=$reqPerihal?></div>
                            <div class="klasifikasi-surat"><?=$reqKlasifikasiKode?> | <?=$reqKlasifikasi?></div>
                        </div>
                        <div class="col-md-4 area-kanan">
                            <div class="aksi">
                           		<a href="main/index/inbox"><i class="fa fa-backward"></i> back to Inbox</a>
                           		<a onClick="parent.openAdd('app/loadUrl/report/template/?reqId=<?=$reqId?>')"><i class="fa fa-file-pdf-o"></i> view as PDF</a>
                                <?php /*?><a><i class="fa fa-reply"></i></a>
                                <a><i class="fa fa-reply-all"></i></a>
                                <a onClick="parent.openAdd('app/loadUrl/app/balas_disposisi')"><i class="fa fa-share"></i></a>
                                <a><i class="fa fa-trash"></i></a><?php */?>
                            </div>
                        </div>
                    </div>
                    <div class="isi-surat">
                        <?=$reqKeterangan?>
                    </div>
                    <?
					$surat_masuk_attachment = new SuratMasuk();
					$adaAttachment = $surat_masuk_attachment->getCountByParamsAttachment(array("A.SURAT_MASUK_ID" => (int)$reqId));
					if($adaAttachment > 0)
					{
					$surat_masuk_attachment->selectByParamsAttachment(array("A.SURAT_MASUK_ID" => (int)$reqId));
					?>
					<fieldset class="attachment-surat">
						<legend>Attachment</legend>
						<div class="inner">
							<?
							while($surat_masuk_attachment->nextRow())
							{
							?>
							<div class="item">
								<div class="ikon"><i class="fa fa-file-pdf-o"></i></div>
								<div class="nama-file"><?=$surat_masuk_attachment->getField("NAMA")?></div>
								<div class="ukuran-file"><?=round(($surat_masuk_attachment->getField("UKURAN")/1024), 2)?> kb</div>
								<div class="hover-konten">
									<i class="fa fa-eye" onClick="parent.openAdd('<?=base_url()."uploads/".$reqId."/".$surat_masuk_attachment->getField("ATTACHMENT")?>')"></i>
									<i class="fa fa-download" onClick="window.open('<?=base_url()."uploads/".$reqId."/".$surat_masuk_attachment->getField("ATTACHMENT")?>', '_blank')"></i>
								</div>
							</div>
							<?
							}
							?>
							
							<div class="clearfix"></div>
						</div>
					</fieldset>
					<?
					}
					?>
                </div>
            </div>
            
        </div>
        <div class="clearfix"></div>
    </div>
</div>

<!--<div class="container-fluid">
<div class="row" style="position: relative;">
    <div class="col-md-12 col-konten-full">
        
        
    </div>

</div>
</div>-->

<!-- EASYUI 1.4.5 -->
<?php /*?><script src="libraries/easyui/globalfunction.js"></script>     
<link rel="stylesheet" type="text/css" href="libraries/easyui/themes/default/easyuinew.css">
<link rel="stylesheet" type="text/css" href="libraries/easyui/themes/icon.css">
<script type="text/javascript" src="libraries/easyui/jquery-1.4.5.easyui.min.js"></script>
<script type="text/javascript" src="libraries/easyui/kalender-easyui.js"></script>    <?php */?>
<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<?php /*?><script src="libraries/bootstrap-3.3.7/docs/dist/js/bootstrap.min.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="libraries/bootstrap-3.3.7/docs/assets/js/ie10-viewport-bug-workaround.js"></script>
<script>
function setURL(url){
    document.getElementById('iframe').src = url;
}
</script><?php */?>

<!--</body>
</html>
-->

<script src="lib/tinyMCE/tinymce.min.js"></script>
<script type="text/javascript">
    tinymce.init({
        selector: "#reqKeterangan",
        //height: 200,
        plugins: [
            "advlist autolink lists link image charmap print preview anchor",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime media table contextmenu paste"
        ],
        toolbar: "nonbreaking undo redo | styleselect | fontsizeselect fontselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",

        <?php /*?>setup: function (ed) {
				ed.on('focus', function () {
					$(this.contentAreaContainer.parentElement).find("div.mce-toolbar-grp").show();
					$(this.contentAreaContainer.parentElement).find("div.mce-toolbar").show();
					$(this.contentAreaContainer.parentElement).find("div.mce-tinymce").show();
					//$(this.contentAreaContainer.parentElement).find("div.mce-container-body.mce-stack-layout").show();					
				});
				ed.on('blur', function () {
					$(this.contentAreaContainer.parentElement).find("div.mce-toolbar-grp").hide();
					$(this.contentAreaContainer.parentElement).find("div.mce-toolbar").hide();
					$(this.contentAreaContainer.parentElement).find("div.mce-tinymce").hide();
					//$(this.contentAreaContainer.parentElement).find("div.mce-container-body.mce-stack-layout").hide();
				});
				ed.on("init", function() {
					$(this.contentAreaContainer.parentElement).find("div.mce-toolbar-grp").hide();
					$(this.contentAreaContainer.parentElement).find("div.mce-toolbar").hide();
					$(this.contentAreaContainer.parentElement).find("div.mce-tinymce").hide();
					//$(this.contentAreaContainer.parentElement).find("div.mce-container-body.mce-stack-layout").hide();
				});
			}<?php */ ?>

    });
</script>

