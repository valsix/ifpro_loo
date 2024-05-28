<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->library("suratmasukinfo");
$suratmasukinfo = new suratmasukinfo();

$this->load->model("UserCalendar");
$user_calendar = new UserCalendar();

$token = $user_calendar->getTokenGoogle($this->ID);

/*if (!isset($token)) {
    echo "
        <script type=\"text/javascript\">
            winAuth = window.open('../../../web/meeting_json/auth', '_blank');
        </script>
    ";
    exit();
}
*/

$this->load->model("SuratMasuk");
$this->load->model("SuratMasukParaf");
$this->load->model("Disposisi");
$this->load->model("DisposisiKelompok");
$surat_masuk = new SuratMasuk();
$surat_masuk_attachment = new SuratMasuk();
$disposisi   = new Disposisi();
$disposisi_kelompok = new DisposisiKelompok();
$surat_masuk_paraf = new SuratMasukParaf();

$reqJenisTujuan = "NI";
$reqId = $this->input->get("reqId");
$reqAksi = $this->input->get("reqAksi");
// echo $reqId;exit;
$refDisposisiId = $this->input->get("refDisposisiId");

$reqIdDraft = $reqId;
if ($reqId == "") {
    $reqMode = "insert";
    $reqStatusSurat = "DRAFT";
    $reqIsMeeting   = "T";
    //$reqPenyampaianSurat = "APLIKASI";
    $reqParaf = "''";
    $reqKepada = "[]";
    $reqTembusan = "[]";
    $reqTanggal = date("d-m-Y");
    $reqJenisTTD = "QR";

    if ($refDisposisiId == "") {
    } else {
        $surat_masuk_ref = new SuratMasuk();
        $surat_masuk_ref->selectByParams(array(), -1, -1, " AND EXISTS(SELECT 1 FROM DISPOSISI X WHERE X.SURAT_MASUK_ID = A.SURAT_MASUK_ID AND MD5('BALAS' || X.DISPOSISI_ID) = '" . $refDisposisiId . "') ");
        $surat_masuk_ref->firstRow();
        $refPerihal = $surat_masuk_ref->getField("PERIHAL");
        $refNomor = $surat_masuk_ref->getField("NOMOR");
    }
} else {

    if ($this->USER_GROUP == "PEGAWAI") {
        $statement = " AND ( 
							(A.USER_ID = '" . $this->ID . "' AND STATUS_SURAT IN ('DRAFT', 'REVISI', 'PARAF')) 
                            OR 
                            (EXISTS(SELECT 1 FROM SURAT_MASUK_AKSES X WHERE X.SURAT_MASUK_ID = A.SURAT_MASUK_ID AND X.USER_ID = '" . $this->ID . "' AND STATUS_SURAT IN ('PARAF', 'VALIDASI'))) 
						) ";
    } else {
        $statement = " AND ( 
							(A.USER_ID = '" . $this->ID . "' AND STATUS_SURAT IN ('DRAFT', 'REVISI')) 
							OR 
							(EXISTS(SELECT 1 FROM SURAT_MASUK_AKSES X WHERE X.SURAT_MASUK_ID = A.SURAT_MASUK_ID AND X.USER_ID = '" . $this->ID . "' AND STATUS_SURAT IN ('PARAF', 'VALIDASI'))) 
						) ";
    }

    $reqMode = "ubah";
    $surat_masuk->selectByParams(array("A.SURAT_MASUK_ID" => $reqId), -1, -1, $statement);
    $surat_masuk->firstRow();
    // echo $surat_masuk->query;exit;

    $refSuratId = $surat_masuk->getField("SURAT_MASUK_REF_ID");
    $reqId = $surat_masuk->getField("SURAT_MASUK_ID");
    $reqJenisNaskah = $surat_masuk->getField("JENIS_NASKAH_ID");
    $reqJenisNaskahNama= $surat_masuk->getField("INFO_JENIS_NASKAH_NAMA");

    $reqKlasifikasiId = $surat_masuk->getField("KLASIFIKASI_ID");
    $reqNoAgenda = $surat_masuk->getField("NO_AGENDA");
    $reqNoSurat = $surat_masuk->getField("NOMOR");
    $reqTanggal = $surat_masuk->getField("TANGGAL");
    $reqPerihal = $surat_masuk->getField("PERIHAL");
    $reqKeterangan = $surat_masuk->getField("ISI");
    $reqSifatNaskah = $surat_masuk->getField("SIFAT_NASKAH");
    $reqStatusSurat = $surat_masuk->getField("STATUS_SURAT");
    $reqLokasiSurat = $surat_masuk->getField("LOKASI_SIMPAN");
    $reqAsalSuratInstansi =  $surat_masuk->getField("INSTANSI_ASAL");
    $reqAsalSuratKota = $surat_masuk->getField("KOTA_ASAL");
    $reqAsalSuratAlamat = $surat_masuk->getField("ALAMAT_ASAL");
    $reqPenyampaianSurat = $surat_masuk->getField("PENYAMPAIAN_SURAT");
    $reqUserAtasanId = $surat_masuk->getField("USER_ATASAN_ID");
    $reqRevisi = $surat_masuk->getField("REVISI");
    $reqTanggalKegiatan =  $surat_masuk->getField("TANGGAL_KEGIATAN_EDIT");
    $reqTanggalKegiatanAkhir = $surat_masuk->getField("TANGGAL_KEGIATAN_AKHIR_EDIT");
    $reqJamKegiatan = $surat_masuk->getField("JAM_KEGIATAN_EDIT");
    $reqJamKegiatanAkhir = $surat_masuk->getField("JAM_KEGIATAN_AKHIR_EDIT");
    $reqIsEmail = $surat_masuk->getField("IS_EMAIL");
    $reqIsMeeting = $surat_masuk->getField("IS_MEETING");
    $reqPrioritasSuratId = $surat_masuk->getField("PRIORITAS_SURAT_ID");
    $reqPermohonanNomorId = $surat_masuk->getField("PERMOHONAN_NOMOR_ID");
    $reqKdLevel = $surat_masuk->getField("JENIS_NASKAH_LEVEL");
    $reqArsipId = $surat_masuk->getField("ARSIP_ID");
    $reqArsip = $surat_masuk->getField("ARSIP");
    $reqJenisTTD = $surat_masuk->getField("JENIS_TTD");
    $reqSuratPdf = $surat_masuk->getField("SURAT_PDF");
    $reqKlasifikasiId = $surat_masuk->getField("KLASIFIKASI_ID");

    $reqPenerbitNomor= "";
    if(!empty($reqJenisNaskah))
    {
        $reqPenerbitNomor = $this->db->query("SELECT PENERBIT_NOMOR FROM JENIS_NASKAH WHERE JENIS_NASKAH_ID = '" . $reqJenisNaskah . "' ")->row()->penerbit_nomor;
    }
    $reqUserId = $surat_masuk->getField("USER_ID");


    if ($reqPermohonanNomorId == "0")
        $reqPermohonanNomorId = "";

    $reqSatuanKerjaId = $surat_masuk->getField("SATUAN_KERJA_ID_ASAL");

    // $surat_masuk_attachment = new SuratMasuk();
    // $surat_masuk_attachment->selectByParamsAttachment(array("A.SURAT_MASUK_ID" => (int)$reqId));
    // $surat_masuk_attachment->firstRow();
    // $reqLinkFileTempSize    =  $surat_masuk_attachment->getField("UKURAN");
    // $reqLinkFileTempTipe    =  $surat_masuk_attachment->getField("TIPE");
    // $reqLinkFileTemp        =  $surat_masuk_attachment->getField("ATTACHMENT");


    if ($reqId == "") {
        // redirect("app/loadUrl/main/draft_detil/?reqId=" . $reqIdDraft);
        if (($this->USER_GROUP == "SEKRETARIS" || $reqUserAtasanId == $this->ID) && $reqAksi !== "koreksi") 
        {
            // redirect("main/index/sent/?reqId=" . $reqIdDraft);
            redirect("main/index/approval");
        }
        else
        {
            redirect("main/index/draft_detil/?reqId=" . $reqIdDraft);
        }
    }

    $reqParaf = $surat_masuk_paraf->getParaf(array("SURAT_MASUK_ID" => $reqId));
    $reqKepada = $disposisi->getJson(array("SURAT_MASUK_ID" => $reqId, "STATUS_DISPOSISI" => "TUJUAN"));
    $reqTembusan = $disposisi->getJson(array("SURAT_MASUK_ID" => $reqId, "STATUS_DISPOSISI" => "TEMBUSAN"));

    $reqKepadaKelompok = $disposisi_kelompok->getJson(array("SURAT_MASUK_ID" => $reqId, "STATUS_DISPOSISI" => "TUJUAN"));
    $reqTembusanKelompok = $disposisi_kelompok->getJson(array("SURAT_MASUK_ID" => $reqId, "STATUS_DISPOSISI" => "TEMBUSAN"));

    $surat_masuk_ref = new SuratMasuk();
    $surat_masuk_ref->selectByParams(array("A.SURAT_MASUK_ID" => $refSuratId));
    $surat_masuk_ref->firstRow();
    $refPerihal = $surat_masuk_ref->getField("PERIHAL");
    $refNomor = $surat_masuk_ref->getField("NOMOR");

    $reqApprovalDate= $surat_masuk->getField("APPROVAL_DATE");

}

// tambahan khusus
$reqKelompokJabatan = "";
if(!empty($reqId))
{
    $suratmasukinfo->getInfo($reqId, "INTERNAL");
    $reqKelompokJabatan = $suratmasukinfo->KELOMPOK_JABATAN;
}

// tambahan khusus
if ($reqStatusSurat == "VALIDASI" && $reqUserId != $this->ID) 
{
    if($reqUserAtasanId == $this->ID && $reqKelompokJabatan == "DIREKSI")
    {
        if(!empty($reqApprovalDate))
        {
            redirect("main/index/approval");
        }
    }
}
// 
?>
<?php /*?><!DOCTYPE html>
<html ng-app="app">
  <head>
  	<base href="<?=base_url()?>">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title><?php */ ?>


<!--<script src="lib/xeditable/jquery/jquery-1.9.1.js"></script>-->
<!--<script type="text/javascript" src="lib/xeditable/jquery/jquery-1.8.3.js"></script>-->
<!-- bootstrap 3 -->
<!--<link href="lib/xeditable/bootstrap300/css/bootstrap.css" rel="stylesheet">
        <script src="lib/xeditable/bootstrap300/js/bootstrap.js"></script>-->

<script src="lib/easyui2/globalfunction.js"></script>

<!--<link rel="stylesheet" href="css/gaya.css" type="text/css">
    <link rel="stylesheet" href="lib/font-awesome-4.7.0/css/font-awesome.css" type="text/css">   -->


<!--<link rel="stylesheet" type="text/css" href="lib/bootstrap-3.3.7/dist/css/bootstrap.min.css">
    
    <style>
		span.combo{
				border:none;
				height:25px !important;
				width:100% !important;
				background:#f0f7fa !important;	
			}
		a.combo-arrow{
				height:25px !important;
		}	
		input.validatebox-readonly{
				border:none;
				height:20px !important;
				background:#f0f7fa !important;	
			}
		input.textbox-text{
				background:#f0f7fa !important;		
				height:25px !important;
				font-size:14px !important;	
			}
			
		.arsip input.validatebox-text{
				background:#f0f7fa !important;		
				font-size:14px !important;	
				height: 25px;
				padding-left:5px;
			}
		
		.arsip input.validatebox-invalid{
				border:none;
				background:#faf0f0 !important;		
				width:100% !important;	
			}
				
					
		input.validatebox-invalid{
				border:none;
				background:#faf0f0 !important;		
				width:100% !important;	
			}
			
	</style>-->
<!--</head>
  <body>-->


<!--<div class="container-fluid" style="background-color:#fff">-->
<div class="col-lg-12 col-konten-full">
    <!--<div class="judul-halaman-tulis">Surat Internal</div>-->
    <div class="judul-halaman bg-course"><span><img src="images/icon-course.png"></span> Surat Internal</div>
    <div class="konten-detil">
		
        <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">
            <div class="btn-atas clearfix">
                <?
                if ($reqId != "") {
                ?>
                <a class="btn btn-danger btn-sm pull-right" id="buttonpdf" onClick="submitPreview()" style="cursor: pointer;"><i class="fa fa-file-pdf-o"></i> View as PDF</a>
                <?
                }
                ?>

                <?
                if ($reqAksi == "koreksi") {
                } 
                else {
                    if ($reqStatusSurat == "VALIDASI") {
                ?>
                        <!-- <button class="btn btn-primary btn-sm pull-right" type="button" onClick="submitForm('POSTING')"><i class="fa fa-paper-plane"></i> Kirim</button> -->
                    <?
                    } elseif ($reqId != "" && $reqUserAtasanId == $this->ID) {
                    ?>
                        <button class="btn btn-primary btn-sm pull-right" type="button" onClick="approvalSurat()"><i class="fa fa-paper-plane"></i> Kirim</button>
                    <?
                    }
                }

                // tambahan khusus
                // if (($reqStatusSurat == "VALIDASI" || $reqStatusSurat == "PARAF") && $reqUserId != $this->ID) 
                if ($reqStatusSurat == "VALIDASI" && $reqUserId != $this->ID) 
                {
                ?>
                    <!-- <button class="btn btn-primary btn-sm pull-right" type="button" onClick="submitForm('REVISI')"><i class="fa fa-level-down"></i> Kembalikan</button> -->
                <?
                    if($reqUserAtasanId == $this->ID && $reqKelompokJabatan == "DIREKSI")
                    {
                ?>
                    <button class="btn btn-primary btn-sm pull-right" type="button" onClick="approvalSurat()"><i class="fa fa-check-square-o"></i> Setujui</button>
                <?
                    }
                    else
                    {
                ?>
                    <button class="btn btn-primary btn-sm pull-right" type="button" onClick="submitForm('POSTING')"><i class="fa fa-paper-plane"></i> Kirim</button>
                <?
                    }
                }

                // tambahan khusus
                if ($reqStatusSurat == "PARAF" && $reqUserId != $this->ID) 
                {
                ?>
                    <button class="btn btn-primary btn-sm pull-right" type="button" onClick="submitForm('PARAF')"><i class="fa fa-check-square-o"></i> Paraf</button>
                <?
                }
                // tambahan khusus
                if ($reqStatusSurat == "DRAFT" && !empty($reqId)) {
                ?>
                    <button class="btn btn-primary btn-sm pull-right" type="button" onClick="submitForm('POSTING')"><i class="fa fa-paper-plane"></i> Kirim</button>
                <?
                }
                if ($reqId == "" || $reqStatusSurat == "DRAFT") {
                ?>
                    <button class="btn btn-default btn-sm pull-right" type="button" onClick="submitForm('DRAFT')"><i class="fa fa-file-o"></i> Draft</button>
                <?
                }
                ?>

            </div>
            <table class="table">
                <thead>
                    <tr>
                        <td>Kepada</td>
                        <td>:</td>
                        <td>
                            <div class="inner" id="divTujuanSurat">
                                <!--<div class="item" onClick="return confirm('Are you sure to delete?')">Lorem ipsu.pdf <i class="fa fa-times-circle"></i></div>-->
                                <div class="btn-group">
                                    <?
                                    $arrKepada = json_decode($reqKepada);
                                    foreach ($arrKepada as $key => $value) {
                                    ?>
                                        <div class="item">TUJUAN:<?= $value->SATUAN_KERJA ?>
                                            <i class="fa fa-times-circle" onclick="$(this).parent().remove();"></i>
                                            <input type="hidden" name="reqTujuanSuratValidasi" value="<?= $value->SATUAN_KERJA_ID ?>">
                                            <input type="hidden" name="reqSatuanKerjaIdTujuan[]" value="<?= $value->SATUAN_KERJA_ID ?>">
                                        </div>
                                    <?
                                    }
                                    ?>
                                    <?
                                    $arrKepadaKelompok = json_decode($reqKepadaKelompok);
                                    foreach ($arrKepadaKelompok as $key => $value) {
                                    ?>
                                        <div class="item">TUJUAN:<?= $value->NAMA_KELOMPOK ?>
                                            <i class="fa fa-times-circle" onclick="$(this).parent().remove();"></i>
                                            <input type="hidden" name="reqTujuanSuratValidasiKelompok" value="<?= $value->SATUAN_KERJA_KELOMPOK_ID ?>">
                                            <input type="hidden" name="reqSatuanKerjaIdTujuan[]" value="<?= $value->SATUAN_KERJA_KELOMPOK_ID ?>">
                                        </div>
                                    <?
                                    }
                                    ?>
                                    <?
                                    $arrTembusan = json_decode($reqTembusan);
                                    foreach ($arrTembusan as $key => $value) {
                                    ?>
                                        <div class="item">TEMBUSAN:<?= $value->SATUAN_KERJA ?>
                                            <i class="fa fa-times-circle" onclick="$(this).parent().remove();"></i>
                                            <input type="hidden" name="reqTujuanSuratValidasi" value="<?= $value->SATUAN_KERJA_ID ?>">
                                            <input type="hidden" name="reqSatuanKerjaIdTembusan[]" value="<?= $value->SATUAN_KERJA_ID ?>">
                                        </div>
                                    <?
                                    }
                                    ?>

                                    <?
                                    $arrTembusanKelompok = json_decode($reqTembusanKelompok);
                                    foreach ($arrTembusanKelompok as $key => $value) {
                                    ?>
                                        <div class="item">TUJUAN:<?= $value->NAMA_KELOMPOK ?>
                                            <i class="fa fa-times-circle" onclick="$(this).parent().remove();"></i>
                                            <input type="hidden" name="reqTujuanSuratValidasiKelompok" value="<?= $value->SATUAN_KERJA_KELOMPOK_ID ?>">
                                            <input type="hidden" name="reqSatuanKerjaIdTujuan[]" value="<?= $value->SATUAN_KERJA_KELOMPOK_ID ?>">
                                        </div>
                                    <?
                                    }
                                    ?>
                                    <?
                                    /*
                                $arrParaf = json_decode($reqParaf);
                                foreach ($arrParaf as $key => $value) {
                                ?>
                                    <div class="item">PARAF:<?=$value->SATUAN_KERJA?> 
                                    <i class="fa fa-times-circle" onclick="$(this).parent().remove();"></i>
                                    <input type="hidden" name="reqTujuanSuratValidasi" value="<?=$value->SATUAN_KERJA_ID?>">
                                    <input type="hidden" name="reqSatuanKerjaIdParaf[]" value="<?=$value->SATUAN_KERJA_ID?>">
                                    </div>
                                <?    
                                }
                                */
                                    ?>
                                    <a class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" href="#">
                                        Tambah
                                        <span class="caret"></span>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a onClick="top.openAdd('app/loadUrl/main/satuan_kerja_tujuan_lookup/?reqJenis=TUJUAN&reqJenisSurat=INTERNAL')">Tujuan</a></li>
                                        <li><a onClick="top.openAdd('app/loadUrl/main/satuan_kerja_tujuan_lookup/?reqJenis=TEMBUSAN&reqJenisSurat=INTERNAL')">Tembusan</a></li>
                                    </ul>
                                </div>

                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td>Perihal</td>
                        <td>:</td>
                        <td>
                            <input type="text" name="reqPerihal" class="easyui-validatebox" value="<?=$reqPerihal?>" style="width: 900px;" placeholder="Silakan tulis judul surat Anda...">
                        </td>
                    </tr>

                    <tr>
                        <td>Pesan</td>
                        <td>:</td>
                        <td>
                            <textarea placeholder="isi pesan..." id="reqKeterangan" name="reqKeterangan"><?=$reqKeterangan?></textarea>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="3">
                            <?
                            if ($reqId == "") {
                            } else {
                            ?>
                                
                            <?
                            }
                            ?>
                        </td>
                    </tr>

                    <tr>
                        <th colspan="3" class="padding-0">
                            <div class="judul-sub">
                                Konfigurasi Asal Surat
                            </div>
                        </th>
                    </tr>

                    <tr>
                        <td>Jenis Naskah</td>
                        <td>:</td>
                        <td>
                            <input type="text" name="reqJenisNaskah" class="easyui-combobox" id="reqJenisNaskah" data-options="width:'300', panelHeight:'120',editable:false, valueField:'id',textField:'text',url:'web/jenis_naskah_json/combo_statement/?reqId=INTERNAL',prompt:'Tentukan jenis naskah...',
                                onSelect: function(rec){
                                    var url = 'web/satuan_kerja_json/combo_level/?reqId='+rec.KD_LEVEL;
                                    $('#reqSatuanKerjaId').combobox('reload', url);
                                    $('#reqSatuanKerjaId').combobox('setValue', '');
                                    $('#reqPermohonanNomorId').combobox('setValue', '');
                                    $('#reqSatuanKerjaIdParaf').combotree('setValue', '');
                                    $('#reqKdLevel').val(rec.KD_LEVEL);
                                    var url = 'combo_json/comboJenisTTD/?reqId='+rec.JENIS_TTD;
                                    $('#reqJenisTTD').combobox('reload', url);
                                    $('#reqJenisTTD').combobox('setValue', '');
                                    $('#reqPenerbitNomor').val(rec.PENERBIT_NOMOR);

                                    // tambahan khusus
                                    if(rec.text == 'Nota Dinas')
                                    {
                                        $('#reqInfoKlasifikasi').hide();

                                        // $('#reqKlasifikasiId,#reqArsip').combotree({required: false});
                                        // $('#reqKlasifikasiId,#reqArsip').removeClass('validatebox-invalid');
                                        $('#reqKlasifikasiId').combotree({required: false});
                                        $('#reqKlasifikasiId').removeClass('validatebox-invalid');

                                        // $('#reqKlasifikasiId').combotree({ required:false });
                                        // $('#reqArsip').validatebox({ required:false });
                                    }
                                    else
                                    {
                                        $('#reqInfoKlasifikasi').show();

                                        // $('#reqKlasifikasiId,#reqArsip').combotree({required: true});
                                        $('#reqKlasifikasiId').combotree({required: true});

                                        // $('#reqKlasifikasiId').combotree({ required:true });
                                        // $('#reqArsip').validatebox({ required:true });

                                    }

                                }" value="<?= $reqJenisNaskah ?>" required />
                            <input type="hidden" name="reqAsalSuratAlamat" id="reqAsalSuratAlamat" class="easyui-validatebox textbox" readonly value="<?= $this->CABANG ?>" style="width:100%">
                            <input type="hidden" name="reqKdLevel" id="reqKdLevel" class="easyui-validatebox textbox" readonly value="<?= $reqKdLevel ?>" style="width:100%">
                            <input type="hidden" name="reqTipeNaskah" id="reqTipeNaskah" class="easyui-validatebox textbox" readonly value="<?= $reqTipeNaskah ?>" style="width:100%">
                            <input type="hidden" name="reqPenerbitNomor" id="reqPenerbitNomor" class="easyui-validatebox textbox" readonly value="<?= $reqPenerbitNomor ?>" style="width:100%">
                        </td>
                    </tr>
                    <tr>
                        <td>Penandatangan</td>
                        <td>:</td>
                        <td>
                            <input type="text" id="reqSatuanKerjaId" class="easyui-combobox" name="reqSatuanKerjaId" data-options="width:'500', panelHeight:'60', valueField:'id', textField:'text', url:'web/satuan_kerja_json/combo_level/?reqId=<?=$reqKdLevel?>',prompt:'Tentukan dari...',
                                onSelect: function(rec){
                                    var url = 'web/permohonan_nomor_json/combo_statement/?reqSatuanKerjaId='+rec.SATUAN_KERJA_ID+'&reqJenisNaskahId='+$('#reqJenisNaskah').combobox('getValue')+'&reqTipeNaskah=INTERNAL';
                                    $('#reqPermohonanNomorId').combobox('reload', url);
                                    $('#reqPermohonanNomorId').combobox('setValue', '');
                                    $('#reqAsalSuratInstansi').val(rec.SATUAN_KERJA);
                                    var url = 'web/satuan_kerja_json/combo_paraf/?reqId='+rec.SATUAN_KERJA_ID;
                                    $('#reqSatuanKerjaIdParaf').combotree('reload', url);
                                    $('#reqSatuanKerjaIdParaf').combotree('setValue', '');
                                }" value="<?= $reqSatuanKerjaId ?>" required="required" />
                            <input type="hidden" name="reqAsalSuratInstansi" id="reqAsalSuratInstansi" class="easyui-validatebox textbox" readonly data-options="required:true" value="<?= $this->SATUAN_KERJA_ASAL ?>" style="width:100%">
                        </td>
                    </tr>
                    <!-- <tr>
                        <td>Pemaraf Naskah</td>
                        <td>:</td>
                        <td>
                            <input type="text" id="reqSatuanKerjaIdParaf" class="easyui-combotree" name="reqSatuanKerjaIdParaf[]" data-options="width:'500', panelHeight:'120', valueField:'id', textField:'text', url:'web/satuan_kerja_json/combo_paraf/?reqId=<?=$reqSatuanKerjaId?>', value:[<?=$reqParaf?>], multiple:true,prompt:'Tentukan pemaraf naskah...'," value="" 
                            <?
                            /* JIKA BUKAN PEJABAT WAJIB NGISI */
                            if ($this->KD_LEVEL_PEJABAT == "") 
                            {
                                if ($this->USER_GROUP == "SEKRETARIS"){} 
                                else 
                                {
                            ?> 
                                required="required" 
                            <?
                                }
                            }
                            ?> 
                            />
                        </td>
                    </tr> -->
                    <tr>
                        <td>Pemaraf Naskah</td>
                        <td>:</td>
                        <td>
                            <input type="text" id="reqSatuanKerjaIdParaf" class="easyui-combotree" name="reqSatuanKerjaIdParaf[]" data-options="
                            onLoadSuccess: function (row, data) {
                                clickNode($('#reqSatuanKerjaIdParaf'));
                            },
                            onClick: function(node){
                                clickNode($('#reqSatuanKerjaIdParaf'));
                            },
                            onCheck: function(node, checked){
                                clickNode($('#reqSatuanKerjaIdParaf'));
                            }
                            , width:'500'
                            , panelHeight:'120'
                            , valueField:'id'
                            , textField:'text'
                            , url:'web/satuan_kerja_json/combo_paraf/?reqId=<?=$reqSatuanKerjaId?>'
                            , value:[<?=$reqParaf?>]
                            , multiple:true
                            , prompt:'Tentukan pemaraf naskah...'," value="" 
                            <?
                            /* JIKA BUKAN PEJABAT WAJIB NGISI */
                            if ($this->KD_LEVEL_PEJABAT == "") 
                            {
                                if ($this->USER_GROUP == "SEKRETARIS"){} 
                                else 
                                {
                            ?> 
                                required="required" 
                            <?
                                }
                            }
                            ?> 
                            />

                            <div id="infodetilparaf">
                                <!-- <ul>
                                	<li>General Manager Cabang Ambon (SOEGIHARTONO )</li>
                                    <li>Manager Bagian Teknik Ambon (ANDHIK KANITRA DWIJAYANTO )</li>
                                    <li>Manager Keuangan Ambon (IQBAL MUHAMAD )</li>
                                    <li>Manager SDM dan Umum Ambon (RAHMADI NUGROHO )</li>
                                </ul> -->
                            </div>
                        </td>
                    </tr>


                    <tr>
                        <td>Tanda Tangan Naskah</td>
                        <td>:</td>
                        <td>
                            <!-- <input type="text" name="reqJenisTTD" class="easyui-combobox"  id="reqJenisTTD"
                            data-options="width:'300', panelHeight:'50',valueField:'id',textField:'text',url:'combo_json/comboTandaTangan',prompt:'Tentukan tanda tangan naskah...'"  value="<?= $reqJenisTTD ?>"  required /> -->
                            <input type="text" name="reqJenisTTD" class="easyui-combobox" id="reqJenisTTD" data-options="width:'300', panelHeight:'50',valueField:'id',textField:'text',url:'combo_json/comboJenisTTD',prompt:'Tentukan tanda tangan naskah...'" value="<?= $reqJenisTTD ?>" required />
                        </td>
                    </tr>

                    <tr>
                        <th colspan="3" class="padding-0">
                            <div class="judul-sub">
                                Konfigurasi Atribut Surat
                            </div>
                        </th>
                    </tr>
                    <tr style="display: none;">
                        <td>Nomor Naskah (jika ada)</td>
                        <td>:</td>
                        <td>
                            <input type="text" name="reqPermohonanNomorId" class="easyui-combobox" id="reqPermohonanNomorId" data-options="width:'300', panelHeight:'120',editable:false, valueField:'id',textField:'text',url:'web/permohonan_nomor_json/combo_statement/?reqSatuanKerjaId=<?= $reqSatuanKerjaId ?>&reqJenisNaskahId=<?= $reqJenisNaskah ?>&reqId=<?= $reqId ?>&reqTipeNaskah=INTERNAL',prompt:'Tentukan nomor naskah (jika ada)...'" value="<?= $reqPermohonanNomorId ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td>Sifat Naskah</td>
                        <td>:</td>
                        <td>
                            <input type="text" name="reqSifatNaskah" class="easyui-combobox" id="reqTipe" data-options="width:'300', panelHeight:'100',editable:false, valueField:'id',textField:'text',url:'web/sifat_surat_json/combo',prompt:'Tentukan sifat naskah...'" value="<?= $reqSifatNaskah ?>" required />
                        </td>
                    </tr>
                    <tr>
                        <td>Prioritas Naskah</td>
                        <td>:</td>
                        <td>
                            <input type="text" name="reqPrioritasSuratId" class="easyui-combotree" id="reqPrioritasSuratId" data-options="width:'300', panelHeight:'60',valueField:'id',textField:'text',url:'web/prioritas_surat_json/combo',prompt:'Tentukan prioritas naskah...'" value="<?= $reqPrioritasSuratId ?>" required />
                        </td>
                    </tr>

                        <tr style="display: none;">
                            <td>Berkaskan Naskah</td>
                            <td>:</td>
                            <td>
                                <div class="arsip col-md-4">
                                    <input type="text" name="reqArsip" class="easyui-validatebox textbox form-control" value="<?= $reqArsip ?>" id="reqArsip" placeholder="Tentukan berkas surat" />
                                    <input type="hidden" name="reqArsipId" id="reqArsipId" value="<?= $reqArsipId ?>">
                                </div>
                                <div class="col-md-1" style="margin-left:-30px">
                                    <a onClick="top.openAdd('app/loadUrl/main/berkas_lookup')"><i class="fa fa-search"></i></a>
                                </div>
                            </td>
                        </tr>

                        <tr id="reqInfoKlasifikasi">
                            <td>Klasifikasi Naskah</td>
                            <td>:</td>
                            <td>
                                <input type="text" name="reqKlasifikasiId" class="easyui-combotree" id="reqKlasifikasiId" data-options="width:'300',valueField:'id',textField:'text',url:'web/klasifikasi_json/combotree',prompt:'Tentukan klasifikasi naskah...'" value="<?= $reqKlasifikasiId ?>" required />
                            </td>
                        </tr>

                    <tr>
                        <td>Tanggal Naskah</td>
                        <td>:</td>
                        <td>
                            <div class="field-item">
                                <div class="pull-left" style="margin-right: 10px;"><span title="Tanggal Naskah"><input type="checkbox" id="reqIsMeeting" name="reqIsMeeting" onClick="kalenderKegiatan()" value="Y" <? if ($reqIsMeeting == "Y") { ?> checked <? } ?>></div>
                                <div class="pull-left">
                                    <label id="labelPesanKegiatan" <? if ($reqIsMeeting == "Y") { ?> style="display:none" <? } ?>>Tambahkan ke kalender kegiatan</label>
                                    <div id="divKegiatan" <? if ($reqIsMeeting == "Y") {
                                                            } else { ?> style="display:none" <? } ?>>
                                        <table style="width:100%;">
                                            <tr>
                                                <td style="width:10%">
                                                    <input type="text" id="reqTanggalKegiatan" class="easyui-datebox textbox form-control" name="reqTanggalKegiatan" value="<?= $reqTanggalKegiatan ?>" style="width:110px" />
                                                </td>
                                                <td style="width:10%">
                                                    <input type="text" id="reqTanggalKegiatanAkhir" class="easyui-datebox textbox form-control" name="reqTanggalKegiatanAkhir" value="<?= $reqTanggalKegiatanAkhir ?>" style="width:110px" />
                                                </td>
                                                <td>&nbsp;Jam&nbsp;</td>
                                                <td width="10%"><input name="reqJamKegiatan" id="reqJamKegiatan" class="easyui-validatebox textbox form-control" onkeydown="return format_menit(event,'reqJamKegiatan');" maxlength="5" value="<?= $reqJamKegiatan ?>" style="width:50px"></td>
                                                <td>&nbsp;s/d&nbsp;</td>
                                                <td width="10%"><input name="reqJamKegiatanAkhir" id="reqJamKegiatanAkhir" class="easyui-validatebox textbox form-control" onkeydown="return format_menit(event,'reqJamKegiatanAkhir');" maxlength="5" value="<?= $reqJamKegiatanAkhir ?>" style="width:50px"></td>
                                                <td width="80%"></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>:</td>
                        <td>
                            <div class="field-item">
                                <div class="pull-left" style="margin-right: 10px;"><span title="Tanggal Naskah"><input type="checkbox" id="reqIsEmail" name="reqIsEmail" value="Y" <? if ($reqIsEmail == "Y") { ?> checked <? } ?>></div>
                                <div class="pull-left">
                                    <label>Kirim ke email tujuan</label>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>
                            <div style="padding: 5px;">

                                <div class="clearfix"></div>
                                <?
                                if ($reqJenisTTD == "BASAH") {
                                    if ($reqNoSurat == "" && $reqPenerbitNomor == "TATAUSAHA") {
                                    } else {
                                ?>
                                        <div class="asal-surat">
                                            <fieldset>
                                                <legend>Naskah TTD</legend>
                                                <div class="col-md-12" style="margin:20px;">
                                                    <div class="title"></div>
                                                    <div class="data">
                                                        <input name="reqLinkFileNaskah" type="file" accept=".pdf" value="" />
                                                        <input type="hidden" name="reqLinkFileNaskahTemp" value="<?= $reqSuratPdf ?>">
                                                        <?
                                                        if ($reqSuratPdf == "") {
                                                        } else {
                                                        ?>
                                                            <i style="font-size:12px"><strong>dokumen naskah telah diupload.</strong></i>
                                                        <?
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>
                                            </fieldset>
                                        </div>
                                    <?
                                    }
                                }
                                if ($reqStatusSurat == "VALIDASI" || $reqStatusSurat == "PARAF") {
                                    if ($reqUserId == $this->ID) {
                                    } else {
                                    ?>
                                        <div class="asal-surat">
                                            <fieldset>
                                                <legend>Revisi</legend>
                                                <div class="col-md-12">
                                                    <div class="title"></div>
                                                    <div class="data">
                                                        <textarea style="width: 100%; height: 200px !important;" rows="10" id="reqRevisi" class="easyui-validatebox textbox-text" name="reqRevisi" placeholder="Tulis catatan revisi disini" required ><?=$reqRevisi?></textarea>
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>
                                            </fieldset>
                                        </div>
                                    <?
                                    }
                                }
                                /* JIKA STATUS DRAFT DAN SUDAH DI REVISI ATASAN */
                                if ($reqStatusSurat == "REVISI") {
                                    ?>
                                    <div class="asal-surat">
                                        <fieldset>
                                            <legend>Revisi</legend>
                                            <div class="col-md-12">
                                                <div class="title"></div>
                                                <div class="data">
                                                    <label class="revisi"><?= $reqRevisi ?></label>
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                        </fieldset>
                                    </div>
                                <?
                                }
                                ?>

                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        	Lampiran
                        </td>
                        <td>:</td>
                        <td>
                            <div class="kotak-dokumen">
                                <div class="kontak">
                                    <div class="inner-lampiran">
                                        <input name="reqLinkFile[]" type="file" maxlength="5" class="multi maxsize-10240" accept="xlsx|xls|doc|docx|ppt|pptx|txt|pdf|jpg|jpeg|png|gif" value="" />
                                        <?
                                        $surat_masuk_attachment = new SuratMasuk();
                                        $surat_masuk_attachment->selectByParamsAttachment(array("A.SURAT_MASUK_ID" => (int)$reqId));
                                        while ($surat_masuk_attachment->nextRow()) {
                                        ?>
                                            <input type="hidden" name="reqLinkFileTemp[]" value="<?= $surat_masuk_attachment->getField("ATTACHMENT") ?>" />
                                            <input type="hidden" name="reqLinkFileTempNama[]" value="<?= $surat_masuk_attachment->getField("NAMA") ?>" />
                                            <input type="hidden" name="reqLinkFileTempTipe[]" value="<?= $surat_masuk_attachment->getField("TIPE") ?>" />
                                            <input type="hidden" name="reqLinkFileTempSize[]" value="<?= $surat_masuk_attachment->getField("UKURAN") ?>" />
                                            <div class="MultiFile-label">
                                                <a class="MultiFile-remove"><i class="fa fa-times-circle" onclick="$(this).parent().parent().remove();"></i></a>

                                                <?
                                                $arrexcept= array("xlsx", "xls", "doc", "docx", "ppt", "pptx", "txt");
                                                if(in_array(strtolower($surat_masuk_attachment->getField("TIPE")), $arrexcept))
                                                {
                                                ?>
                                                <a href="<?= "uploads/" . $reqId . "/" . $surat_masuk_attachment->getField("ATTACHMENT") ?>" target="_blank">
                                                    <?= $surat_masuk_attachment->getField("NAMA") ?>
                                                </a>
                                                <?
                                                }
                                                else
                                                {
                                                ?>
                                                <a onClick="parent.openAdd('<?= base_url() . "uploads/" . $reqId . "/" . $surat_masuk_attachment->getField("ATTACHMENT") ?>')">
                                                    <?= $surat_masuk_attachment->getField("NAMA") ?>
                                                </a>
                                                <?
                                                }
                                                ?>
                                            </div>
                                        <?
                                        }
                                        ?>
                                        
                                        <div class="small">Ukuran file maksimum yang diizinkan adalah 10 MB & Jenis file diterima: world, excel, ppt, pdf, jpg, jpeg, png</div>
                                        
                                    </div>
                                </div>

                            </div>
                        </td>
                    </tr>
                </thead>
            </table>

            <input type="hidden" name="reqTarget" value="INTERNAL" />
            <input type="hidden" name="reqPenyampaianSurat" value="APLIKASI" />
            <input type="hidden" name="refDisposisiId" value="<?= $refDisposisiId ?>" />
            <input type="hidden" name="reqJenisTujuan" value="<?= $reqJenisTujuan ?>" />
            <input type="hidden" name="reqId" value="<?= $reqId ?>" />
            <input type="hidden" name="reqMode" value="<?= $reqMode ?>" />
            <input type="hidden" name="reqStatusSurat" id="reqStatusSurat" value="<?= $reqStatusSurat ?>" />

        </form>
    </div>

</div>
<!--</div>-->
<!-- /.container -->


<style id="compiled-css" type="text/css">

</style>

<!--// plugin-specific resources //-->
<script src='lib/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script>
<script src='lib/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
<script src='lib/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>

<!-- EASYUI 1.4.5 -->
<!--<link rel="stylesheet" type="text/css" href="lib/easyui2/themes/default/easyui.css">
        <link rel="stylesheet" type="text/css" href="lib/easyui2/themes/icon.css">
        <script type="text/javascript" src="lib/easyui2/jquery-1.4.5.easyui.min.js"></script>
	    <script type="text/javascript" src="lib/easyui2/kalender-easyui.js"></script>    -->
<script>
    function addSatuanKerja(JENIS, SATUAN_KERJA_ID, NAMA) {
        //alert("topp");
        var rv = true;
        $('[name^=reqTujuanSuratValidasi]').each(function() {

            if ($(this).val() == SATUAN_KERJA_ID) {
                rv = false;
                return false;
            }

        });

        if (rv == true) {

            $.post("app/loadUrl/template/tujuan_surat", {
                    reqJenis: JENIS,
                    reqSatkerId: SATUAN_KERJA_ID,
                    reqNama: NAMA
                })
                .done(function(data) {
                    $("#divTujuanSurat").append(data);
                });
        }
    }


    function addBerkas(ARSIP_ID, BERKAS) {
        $("#reqArsipId").val(ARSIP_ID);
        $("#reqArsip").val(BERKAS);
    }

    function kalenderKegiatan() {
        if ($('#reqIsMeeting').is(':checked')) {
            $('#labelPesanKegiatan').hide();
            $('#divKegiatan').show();
        } else {
            $('#labelPesanKegiatan').show();
            $('#divKegiatan').hide();
        }
    }

    function approvalSurat() {
        // tambahan khusus
        if ($("#reqRevisi").val().trim() == "") {
            $("#reqRevisi").focus();
            $.messager.alert('Info', "Isikan Revisi terlebih dahulu.", 'info');
            return false;
        }

        web_link= "web/surat_masuk_json/approval_vp";
        id= "<?=$reqId?>";
        konfirmasi= "Teruskan naskah ke sekretaris?";

        reqRevisi= $("#reqRevisi").val();
        reqRevisi= JSON.stringify(reqRevisi);
        reqRevisi= encodeURIComponent(reqRevisi);
        // console.log(reqRevisi);return false;

        $.messager.confirm('Konfirmasi',konfirmasi,function(r){
            if (r){
                
                var win = $.messager.progress({
                    title:'TNDE | PT Angkasa Pura I (Persero)',
                    msg:'proses data...'
                });                     
                
                var jqxhr = $.get( web_link+'?reqId='+id+"&reqRevisi="+reqRevisi, function(data) {
                    $.messager.progress('close');

                    /* HIT JUMLAH SURAT */
                    top.getJumlahSurat(0, "INTERNAL");
                    $.messager.alertLink('Info', data, 'info'); 
                    // return false;
                    // document.location.href = 'main/index/approval';
                })
                .fail(function() {
                    $.messager.progress('close');
                    alert( "error" );
                });                             
            }
        });

        // konfirmasiAksiRefreshLinkReload("Teruskan naskah ke sekretaris?", "web/surat_masuk_json/approval_vp", "<?= $reqId ?>", "main/index/approval");
        // konfirmasiAksiRefreshSurat("Teruskan naskah ke sekretaris?", "web/surat_masuk_json/approval_vp", "<?= $reqId ?>", "main/index/approval");
    }

    function submitForm(reqStatusSurat) {

        $("#reqStatusSurat").val(reqStatusSurat);

        var pesan = "Simpan surat sebagai draft?";
        if (reqStatusSurat == "POSTING")
        {
            // tambahan khusus
            <?
            if ($reqStatusSurat == "VALIDASI" && $reqUserId != $this->ID) 
            {
                if($reqUserAtasanId == $this->ID && $reqKelompokJabatan == "DIREKSI")
                {
            ?>
            <?
                }
                else
                {
            ?>
                var pesan = "Kirim naskah?";
            <?
                }
            }
            else
            {
            ?>
                var pesan = "Kirim surat ke tujuan?";
            <?
            }
            ?>
        }
        if (reqStatusSurat == "REVISI")
            var pesan = "Kembalikan surat ke staff anda?";

        // tambahan khusus
        if (reqStatusSurat == "PARAF")
            var pesan = "Paraf naskah?";

        <?
        if (($this->USER_GROUP == "SEKRETARIS" || $reqUserAtasanId == $this->ID) && $reqAksi !== "koreksi") 
        {
        ?>
            // tambahan khusus
            // reqArsipId= $("#reqArsipId").val();

            // if(reqArsipId == "" || reqArsipId == "0")
            // {
            //     $.messager.alert('Info', "Isikan Berkaskan Naskah terlebih dahulu", 'info');
            //     return false;
            // }
        <?
        }
        ?>

        $.messager.confirm('Konfirmasi', pesan, function(r) {
            if (r) {
                $('#ff').form('submit', {
                    url: 'web/surat_masuk_json/add',
                    onSubmit: function() {

                        if ($(this).form('enableValidation').form('validate') == false) {
                            if ($("#button i").attr("class") == "fa fa-gears")
                                $("#button").click();

                            return false;
                        }

                        // tambahan khusus
                        if (reqStatusSurat == "REVISI" || reqStatusSurat == "PARAF") {
                            if ($("#reqRevisi").val().trim() == "") {
                                if ($("#button i").attr("class") == "fa fa-gears")
                                    $("#button").click();

                                return false;
                            }
                        }

                        // tambahan khusus
                        infoisirevisi= "";
                        <?
                        if ($reqStatusSurat == "VALIDASI" && $reqUserId != $this->ID) 
                        {
                            if($reqUserAtasanId == $this->ID && $reqKelompokJabatan == "DIREKSI")
                            {
                        ?>
                            infoisirevisi= "1";
                        <?
                            }
                            else
                            {
                        ?>
                            infoisirevisi= "1";
                        <?
                            }
                        }
                        ?>

                        if(infoisirevisi == "1")
                        {
                            if ($("#reqRevisi").val().trim() == "") {
                                if ($("#button i").attr("class") == "fa fa-gears")
                                    $("#button").click();

                                return false;
                            }
                        }

                        return $(this).form('enableValidation').form('validate');
                    },
                    success: function(data) {
                        // console.log(data);return false;
                        // alert(data);
                        // return;

                        arrData = data.split("-");

                        if (arrData[0] == "0") {
                            $.messager.alert('Info', arrData[1], 'info');
                            return;
                        }

                        /* HIT JUMLAH SURAT */
                        top.getJumlahSurat(0, "INTERNAL");

                        // tambahan khusus
                        if (reqStatusSurat == "PARAF")
                        {
                            $.messager.alert('Info', arrData[1], 'info');
                            document.location.href = 'main/index/approval';
                        }
                        else if (reqStatusSurat == 'DRAFT')
                            $.messager.alertLink('Info', arrData[1], 'info', "app/loadUrl/main/surat_masuk_add/?reqId=" + arrData[0]);
                        else if (reqStatusSurat == 'REVISI')
                            $.messager.alertLink('Info', data, 'info', "app/loadUrl/main/approval");
                        else if (reqStatusSurat == 'POSTING') 
                        {
                            // tambahan khusus
                            if(infoisirevisi == "1")
                            {
                                $.messager.alert('Info', arrData[1], 'info');
                                document.location.href = 'main/index/approval';
                            }
                            else
                            {
                                arrData = data.split("-");
                                $.messager.alertLink('Info', arrData[1], 'info', "app/loadUrl/main/" + arrData[0]);
                            }
                        } 
                        else
                        {
                            $.messager.alertLink('Info', data, 'info', "app/loadUrl/main/draft");
                        }

                    }
                });
            }
        });
    }

    function submitPreview() {
        $('#ff').form('submit', {
            url: 'web/surat_masuk_json/add',
            onSubmit: function() {

                if ($(this).form('enableValidation').form('validate') == false) {
                    if ($("#button i").attr("class") == "fa fa-gears")
                        $("#button").click();

                    return false;
                }

                return $(this).form('enableValidation').form('validate');
            },
            success: function(data) {
                // console.log(data);return false;
                parent.openAdd('app/loadUrl/report/template/?reqId=<?= $reqId ?>');
            }
        });

    }



    function clearForm() {
        $('#ff').form('clear');
    }
</script>
<!-- TODO: Missing CoffeeScript 2 -->
<script type="text/javascript">
    $(document).ready(function() {

        /* JIKA MEMO INTERN TIDAK WAJIB */
        // if ($('#reqJenisNaskah').combobox("getValue") == '5') {
        <?
        if(!empty($reqJenisNaskahNama))
        {
        ?>
        if ("<?=$reqJenisNaskahNama?>" == 'Nota Dinas') {
            $('#reqInfoKlasifikasi').hide();

            // $('#reqKlasifikasiId,#reqArsip').combotree({required: false});
            // $('#reqKlasifikasiId,#reqArsip').removeClass('validatebox-invalid');
            $('#reqKlasifikasiId').combotree({required: false});
            $('#reqKlasifikasiId').removeClass('validatebox-invalid');

            // $('#reqKlasifikasiId').combotree({
            //     required: false
            // });
            // $('#reqArsip').validatebox({
            //     required: false
            // });
        } 
        else 
        {
            $('#reqInfoKlasifikasi').show();
            // $('#reqKlasifikasiId,#reqArsip').combotree({required: true});
            $('#reqKlasifikasiId').combotree({required: true});

            // $('#reqKlasifikasiId').combotree({
            //     required: true
            // });
            // $('#reqArsip').validatebox({
            //     required: true
            // });
        }
        <?
        }
        ?>

        // 5 agu 2020
        /*$("#hidden_content").hide();
        $("#button").toggle(function() {
        	$("#button i").attr("class", "fa fa-gears");
        }, function() {
        	$("#button i").attr("class", "fa fa-gears");
        }).click(function(){
        	$("#hidden_content").animate({width: 'toggle'}, "slow");
        });*/
    });

    function clickNode(cc)
    {
        var opts= cc.combotree('options');
        var values= cc.combotree('getValues');
        var textdata= cc.combotree('getText');
        // console.log(textdata);
        infotextdata= textdata.split(",");
        // alert(infotextdata.length);

        infodetilparaf= "<ul>";
        for(i=0; i < infotextdata.length; i++)
        {
            if(infotextdata[i] !== "")
            {
                infodetilparaf+= "<li>"+infotextdata[i]+"</li>";
            }
        }

        // infodetilparaf+= "<li>General Manager Cabang Ambon (SOEGIHARTONO )</li>";
        infodetilparaf+= "</ul>";

        $("#infodetilparaf").empty();
        $("#infodetilparaf").html(infodetilparaf);

        var t = cc.combotree('tree');   // get the tree object
        var n = t.tree('getSelected');      // get selected node
        // console.log(n);

        // $(function(){
        //     document.getElementById("reqRumpunId").value = n.rumpunid;
        //     document.getElementById('reqRumpunNama').innerText = n.rumpunnama;
        //     document.getElementById("reqSatkerId").value = n.satkerid;
        //     document.getElementById('reqSatkerNama').innerText = n.satkernama;
        //     $("#reqJabatanId").val(values);
        // });
    }
</script>

<script>
    $('textarea').focus(function() {
        //$(this).closest('.area-tulis-pesan').find('#button').show("slow");
    });
</script>

<!-- tiny MCE -->
<!--<script src="lib/tinyMCE/tinymce.min.js"></script>-->

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
<!-- </body>
</html>-->