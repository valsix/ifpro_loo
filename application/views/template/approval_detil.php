<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->library("suratmasukinfo"); 
$suratmasukinfo = new suratmasukinfo();

$this->load->model("SuratMasuk");
$this->load->model("SuratMasukParaf");
$this->load->model("Disposisi");
$surat_masuk = new SuratMasuk();
$disposisi   = new Disposisi();
$surat_masuk_paraf = new SuratMasukParaf();

$reqJenisTujuan = "NI";
$reqId = $this->input->post("reqId");
// $reqId= 15;

$reqIdDraft = $reqId;
$reqMode = "ubah";

$statement_privacy .= " AND (A.USER_ATASAN_ID = '".$this->ID."' OR A.USER_ATASAN_ID = '".$this->USER_GROUP.$this->ID."') ";
$surat_masuk->selectByParamsMonitoringApproval(array("A.SURAT_MASUK_ID" => $reqId), -1, -1, $statement_privacy." AND STATUS_SURAT IN ('VALIDASI', 'PARAF') ");
// echo $surat_masuk->query;exit;
$surat_masuk->firstRow();

$refSuratId = $surat_masuk->getField("SURAT_MASUK_REF_ID");
$reqId          = $surat_masuk->getField("SURAT_MASUK_ID");
$reqJenisNaskah = $surat_masuk->getField("JENIS");
$reqKlasifikasiId = $surat_masuk->getField("KLASIFIKASI_ID");
$reqNoAgenda    = $surat_masuk->getField("NO_AGENDA");
$reqNoSurat     = $surat_masuk->getField("NOMOR");
$reqTanggal     = $surat_masuk->getField("TANGGAL");
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
$reqKepada      = $surat_masuk->getField("KEPADA");
$reqTembusan    = $surat_masuk->getField("TEMBUSAN");
$reqArsipId    = $surat_masuk->getField("ARSIP_ID");
$reqJenisSurat    = $surat_masuk->getField("JENIS_SURAT");

if($reqJenisSurat == "INTERNAL")
	$suratLink = "surat_masuk_add";
else
	$suratLink = "surat_keluar_add";


// if($reqId == "")
// 	exit;

$reqParaf       = $surat_masuk_paraf->getJson(array("SURAT_MASUK_ID" => $reqIdDraft));

$suratmasukinfo->getInfo($reqId, "INTERNAL");
$reqKelompokJabatan = $suratmasukinfo->KELOMPOK_JABATAN;

?>

<!-- EASYUI 1.4.5 -->
<link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyuinew.css">
<link rel="stylesheet" type="text/css" href="lib/easyui/themes/icon.css">
<script type="text/javascript" src="lib/easyui2/jquery-1.4.5.easyui.min.js"></script>
<script src="lib/easyui/globalfunction.js"></script> 	

<script type="text/javascript">
    function kirimSurat()
    {
        <?
        // if($reqArsipId == 0)
        // {
        ?>
        // $.messager.alert('Info', "Naskah belum diberkaskan, silahkan edit terlebih dahulu.", 'info');   
        <?
        // }
        // else
        // {
        ?>
        konfirmasiAksiRefreshSurat("Kirim naskah?","web/surat_masuk_json/posting", "<?=$reqId?>");          
        <?
        // }
        ?>
    }
    
    function approvalSurat()
    {
        konfirmasiAksiRefreshSurat("Teruskan naskah ke sekretaris?","web/surat_masuk_json/approval_vp", "<?=$reqId?>");     
    }

    function parafSurat()
    {
        konfirmasiAksiRefreshSurat("Paraf naskah?","web/surat_masuk_json/paraf", "<?=$reqId?>");            
    }

    function koreksiSurat()
    {
        // top.document.getElementById('contentFrame').src = 'app/loadUrl/app/<?=$suratLink?>/?reqId=<?=$reqIdDraft?>&reqAksi=koreksi';
        document.location.href = 'main/index/<?=$suratLink?>/?reqId=<?=$reqIdDraft?>&reqAksi=koreksi';
    }

    function editSurat()
    {
        // top.document.getElementById('contentFrame').src = 'app/loadUrl/app/<?=$suratLink?>/?reqId=<?=$reqIdDraft?>';
        document.location.href = 'main/index/<?=$suratLink?>/?reqId=<?=$reqIdDraft?>';
    }
</script>

<div class="data">
    <?php /*?><div class="header-profil">
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
            <div class="tanggal"><i class="fa fa-tags"></i> <?=$reqJenisNaskah?></div>
            <div class="tanggal"><i class="fa fa-exclamation-triangle"></i> <?=$reqSifatNaskah?></div>
            <div class="waktu"></div>
        </div>
    </div>
    <div class="clearfix"></div><?php */?>
    
    
    
    <div class="header-surat">
    	
        <div class="col-md-8 area-kiri">
            <div class="judul-surat">Perihal : <?=$reqPerihal?></div>
            
            <?
			if($reqJenisNaskah == "Nota Dinas"){ } else {
			?>
			<div class="klasifikasi-surat"><?=$reqKlasifikasiKode?> | <?=$reqKlasifikasi?></div>
			<? } ?>
        </div>
        <div class="col-md-4 area-kanan">
            <div class="aksi">
                <!--<a onClick="editSurat()"><i class="fa fa-edit"></i> Edit Naskah</a>-->
                <?
                if($reqStatusSurat == "PARAF")
				{
				?>
                <a onclick="parafSurat()" style="cursor: pointer;" class="btn btn-sm btn-primary"><i class="fa fa-check"></i> Paraf</a>
                <a onclick="koreksiSurat()" style="cursor: pointer;" class="btn btn-sm btn-warning"><i class="fa fa-pencil"></i> Koreksi</a>
                <?
				}
				?>
                <?
                if($reqStatusSurat == "VALIDASI")
				{
					if($this->USER_GROUP == "SEKRETARIS" && $reqKelompokJabatan == "DIREKSI")
					{
				?>
                    <a onclick="kirimSurat()" style="cursor: pointer;" class="btn btn-sm btn-primary"><i class="fa fa-send"></i> Kirim</a>
                    <a onclick="editSurat()" style="cursor: pointer;" class="btn btn-sm btn-warning"><i class="fa fa-pencil"></i> Edit</a>
                <?
					}
					elseif($reqUserAtasanId == $this->ID && $reqKelompokJabatan == "DIREKSI")
					{
				?>
                    <a onclick="approvalSurat()" style="cursor: pointer;" class="btn btn-sm btn-success"><i class="fa fa-send"></i> Setujui</a>
               		<a onclick="koreksiSurat()" style="cursor: pointer;" class="btn btn-sm btn-warning"><i class="fa fa-pencil"></i> Koreksi</a>
                <?		
					}
                    else{
                ?>
                    <a onclick="kirimSurat()" style="cursor: pointer;" class="btn btn-sm btn-primary"><i class="fa fa-send"></i> Kirim</a>
                    <a onclick="koreksiSurat()" style="cursor: pointer;" class="btn btn-sm btn-warning"><i class="fa fa-pencil"></i> Koreksi</a>
                <?
                    }
				}
				?>
                <?php /*?><a onclick="parent.openAdd('app/loadUrl/report/template/?reqId=<?=$reqId?>')" style="cursor: pointer;"><i class="fa fa-file-pdf-o"></i> view as PDF</a><?php */?>
                           
                
                <?php /*?><a><i class="fa fa-reply"></i></a>
                <a><i class="fa fa-reply-all"></i></a>
                <a onclick="parent.openAdd('app/loadUrl/app/balas_disposisi')"><i class="fa fa-share"></i></a>
                <a><i class="fa fa-trash"></i></a><?php */?>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
    
    <div class="konten-pdf">
    	<iframe src="app/loadUrl/report/template/?reqId=<?=$reqId?>"></iframe>
    </div>
    <?php /*?><div class="isi-surat">
        <?=$reqKeterangan?>
    </div><?php */?>
    <fieldset class="attachment-surat">
        <legend>Lampiran</legend>
        <div class="inner">
            <?
            $surat_masuk_attachment = new SuratMasuk();
            $surat_masuk_attachment->selectByParamsAttachment(array("A.SURAT_MASUK_ID" => (int)$reqId));
			$i = 0;
            while($surat_masuk_attachment->nextRow())
            {
            ?>
                <div class="item">
                    <div class="ikon"><i class="fa fa-file-pdf-o"></i></div>
                    <div class="nama-file"><?=$surat_masuk_attachment->getField("NAMA")?></div>
                    <div class="ukuran-file"><?=round(($surat_masuk_attachment->getField("UKURAN")/1024), 2)?> kb</div>
                    <div class="hover-konten">
                        <i class="fa fa-eye" onclick="parent.openAdd('<?=base_url()."uploads/".$reqId."/".$surat_masuk_attachment->getField("ATTACHMENT")?>')"></i>
                        <i class="fa fa-download" onclick="window.open('<?=base_url()."uploads/".$reqId."/".$surat_masuk_attachment->getField("ATTACHMENT")?>', '_blank')"></i>
                    </div>
                </div>
            <?
				$i++;
            }
			if($i == 0)
			{
            ?>
            <div style="margin: 0 15px 15px; color: red;"><i class="fa fa-exclamation-triangle"></i> Tidak ada lampiran</div>
            <?
			}
			?>
            <div class="clearfix"></div>
        </div>
    </fieldset>
</div>