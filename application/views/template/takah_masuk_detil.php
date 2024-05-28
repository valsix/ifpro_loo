<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model("TakahDokumen");
$takah_dokumen = new TakahDokumen();
$reqId = $this->input->post("reqId");
// echo $reqId;exit;

$reqMode = "ubah";

$statement_privacy = " AND SATUAN_KERJA_ID_TUJUAN ='".$this->SATUAN_KERJA_ID_ASAL."' "; 
$takah_dokumen->selectByParamsTakahMasuk(array("A.TAKAH_DOKUMEN_ID" => $reqId), -1, -1, $statement_privacy);
// echo $takah_dokumen->query;exit;
$takah_dokumen->firstRow();

$reqSatuanKerjaIdAsal = $takah_dokumen->getField("SATUAN_KERJA_ID_ASAL");
$refSuratId     = $takah_dokumen->getField("TAKAH_DOKUMEN_REF_ID");
$reqId          = $takah_dokumen->getField("TAKAH_DOKUMEN_ID");
$reqNoSurat     = $takah_dokumen->getField("NOMOR");
$reqKode        = $takah_dokumen->getField("KODE");
$reqTanggal     = $takah_dokumen->getField("TANGGAL");
$reqPerihal     = $takah_dokumen->getField("NAMA");
$reqKeterangan  = $takah_dokumen->getField("KETERANGAN");
$reqKepada      = $takah_dokumen->getField("ASAL");
$reqLampiran    = $takah_dokumen->getField("LAMPIRAN");

?>

<div class="data">
    <div class="header-profil">
        <div class="col-md-1 area-kiri">
            <div class="avatar"><?=generateFoto("X", $reqKepada)?></div>
        </div>
        <div class="col-md-8 area-kiri">
            <div class="nama">
            <?=$reqKepada?>
            </div>
            <div class="jabatan">
            <?
            if($reqTembusan == "")
			{}
			else
			{
			?>
            cc : <?=$reqTembusan?>
            <?
			}
			?>
            </div>
            <div class="email"><?=getFormattedDate($reqTanggal)?></div>
        </div>
        <div class="col-md-3 area-kanan">
            <div class="tanggal"><i class="fa fa-tags"></i> Kode Naskah <?=$reqKode?></div>
            <!-- <div class="tanggal"><i class="fa fa-exclamation-triangle"></i> <?=$reqSifatNaskah?></div> -->
            <div class="waktu"></div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="header-surat">
        <div class="col-md-8 area-kiri">
            <div class="judul-surat"><?=$reqPerihal?></div>
            <!-- <div class="klasifikasi-surat"><?=$reqKlasifikasiKode?> | <?=$reqKlasifikasi?></div> -->
        </div>
        <div class="col-md-4 area-kanan">
            <div class="aksi">
                <!-- <a onClick="parent.openAdd('app/loadUrl/report/template/?reqId=<?=$reqId?>')"><i class="fa fa-file-pdf-o"></i> view as PDF</a> -->
                <script>
				function editSurat()
				{
					top.document.getElementById('contentFrame').src = 'app/loadUrl/app/takah_dokumen_add/?reqId=<?=$reqId?>';	
				}
				</script>
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
    
    
    <fieldset class="attachment-surat">
        <legend>Attachment</legend>
        <div class="inner">
            <div class="item">
                <div class="ikon"><i class="fa fa-file-pdf-o"></i></div>
                <div class="nama-file"><?=$reqLampiran?></div>
                <!-- <div class="ukuran-file"><?=round(($reqLampiran/1024), 2)?> kb</div> -->
                <div class="hover-konten">
                    <i class="fa fa-eye" onClick="parent.openAdd('<?=base_url()."uploads/".$reqLampiran?>')"></i>
                    <i class="fa fa-download" onClick="window.open('<?=base_url()."uploads/".$reqLampiran?>', '_blank')"></i>
                </div>
            </div>
            
            <div class="clearfix"></div>
        </div>
    </fieldset>
    
    
</div>