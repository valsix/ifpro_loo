<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model("SuratKeluar");
$this->load->model("SuratKeluarParaf");
$this->load->model("DisposisiKeluar");
$surat_keluar = new SuratKeluar();
$disposisi   = new DisposisiKeluar();
$surat_keluar_paraf = new SuratKeluarParaf();

$reqJenisTujuan = "NI";
$reqId = $this->input->post("reqId");

$reqIdDraft = $reqId;

$reqMode = "ubah";
$surat_keluar->selectByParamsLihat(array("A.SURAT_KELUAR_ID" => $reqId, "A.USER_ATASAN_ID" => $this->ID), -1, -1, " AND STATUS_SURAT IN ('VALIDASI') ");
$surat_keluar->firstRow();

$refSuratId = $surat_keluar->getField("SURAT_KELUAR_REF_ID");
$reqId          = $surat_keluar->getField("SURAT_KELUAR_ID");
$reqJenisNaskah = $surat_keluar->getField("JENIS");
$reqKlasifikasiId = $surat_keluar->getField("KLASIFIKASI_ID");
$reqNoAgenda    = $surat_keluar->getField("NO_AGENDA");
$reqNoSurat     = $surat_keluar->getField("NOMOR");
$reqTanggal     = $surat_keluar->getField("TANGGAL");
$reqPerihal     = $surat_keluar->getField("PERIHAL");
$reqKeterangan  = $surat_keluar->getField("ISI");
$reqSifatNaskah = $surat_keluar->getField("SIFAT_NASKAH");
$reqStatusSurat = $surat_keluar->getField("STATUS_SURAT");
$reqLokasiSurat = $surat_keluar->getField("LOKASI_SIMPAN");
$reqAsalSuratInstansi   =  $surat_keluar->getField("INSTANSI_ASAL");
$reqAsalSuratKota       =  $surat_keluar->getField("KOTA_ASAL");
$reqAsalSuratAlamat     =  $surat_keluar->getField("ALAMAT_ASAL");
$reqPenyampaianSurat    =  $surat_keluar->getField("PENYAMPAIAN_SURAT");
$reqUserAtasanId        =  $surat_keluar->getField("USER_ATASAN_ID");
$reqRevisi              =  $surat_keluar->getField("REVISI");
$reqTanggalKegiatan 	 =  $surat_keluar->getField("TANGGAL_KEGIATAN_EDIT");
$reqTanggalKegiatanAkhir =  $surat_keluar->getField("TANGGAL_KEGIATAN_AKHIR_EDIT");
$reqJamKegiatan          =  $surat_keluar->getField("JAM_KEGIATAN_EDIT");
$reqJamKegiatanAkhir     =  $surat_keluar->getField("JAM_KEGIATAN_AKHIR_EDIT");
$reqIsEmail       =  $surat_keluar->getField("IS_EMAIL");
$reqIsMeeting     =  $surat_keluar->getField("IS_MEETING");
$reqKlasifikasiKode     =  $surat_keluar->getField("KLASIFIKASI_KODE");
$reqKlasifikasi     =  $surat_keluar->getField("KLASIFIKASI");
$reqKepada      = $surat_keluar->getField("KEPADA");
$reqTembusan    = $surat_keluar->getField("TEMBUSAN");




if($reqId == "")
	exit;

$reqParaf       = $surat_keluar_paraf->getJson(array("SURAT_KELUAR_ID" => $reqId));

?>

<div class="data">
    <div class="header-profil">
        <div class="col-md-1 area-kiri">
            <div class="avatar"><img src="images/img-user.png"></div>
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
            <div class="tanggal"><i class="fa fa-tags"></i> <?=$reqJenisNaskah?></div>
            <div class="tanggal"><i class="fa fa-exclamation-triangle"></i> <?=$reqSifatNaskah?></div>
            <div class="waktu"></div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="header-surat">
        <div class="col-md-8 area-kiri">
            <div class="judul-surat"><?=$reqPerihal?></div>
            <div class="klasifikasi-surat"><?=$reqKlasifikasiKode?> | <?=$reqKlasifikasi?></div>
        </div>
        <div class="col-md-4 area-kanan">
            <div class="aksi">
                <a onClick="editSurat()"><i class="fa fa-edit"></i> Edit Surat</a>
                <a onClick="parent.openAdd('app/loadUrl/report/template_keluar/?reqId=<?=$reqId?>')"><i class="fa fa-file-pdf-o"></i> view as PDF</a>
                <script>
				function editSurat()
				{
					top.document.getElementById('contentFrame').src = 'app/loadUrl/app/surat_keluar_add/?reqId=<?=$reqId?>';	
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
            <?
            $surat_keluar_attachment = new SuratKeluar();
            $surat_keluar_attachment->selectByParamsAttachment(array("A.SURAT_KELUAR_ID" => (int)$reqId));
            while($surat_keluar_attachment->nextRow())
            {
            ?>
            <div class="item">
                <div class="ikon"><i class="fa fa-file-pdf-o"></i></div>
                <div class="nama-file"><?=$surat_keluar_attachment->getField("NAMA")?></div>
                <div class="ukuran-file"><?=round(($surat_keluar_attachment->getField("UKURAN")/1024), 2)?> kb</div>
                <div class="hover-konten">
                    <i class="fa fa-eye" onClick="parent.openAdd('<?=base_url()."uploads/eksternal/".$reqId."/".$surat_keluar_attachment->getField("ATTACHMENT")?>')"></i>
                    <i class="fa fa-download" onClick="window.open('<?=base_url()."uploads/eksternal/".$reqId."/".$surat_keluar_attachment->getField("ATTACHMENT")?>', '_blank')"></i>
                </div>
            </div>
            <?
            }
            ?>
            
            <div class="clearfix"></div>
        </div>
    </fieldset>
</div>