<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model("SuratMasuk");
$this->load->model("SuratMasukParaf");
$this->load->model("Disposisi");
$surat_masuk = new SuratMasuk();
$disposisi   = new Disposisi();
$surat_masuk_paraf = new SuratMasukParaf();

$reqJenisTujuan = "NI";
$reqId = $this->input->post("reqId");

$reqIdDraft = $reqId;

$statement_privacy .= " AND (A.USER_ATASAN_ID = '".$this->ID_ATASAN."' OR A.USER_ID = '".$this->ID_ATASAN."' OR A.USER_ATASAN_ID = '".$this->ID."' OR A.USER_ID = '".$this->ID."' OR A.USER_ID_OBSERVER = '".$this->ID."' OR EXISTS(SELECT 1 FROM SURAT_MASUK_PARAF X WHERE X.SURAT_MASUK_ID = A.SURAT_MASUK_ID AND X.USER_ID = '".$this->ID."'))  ";
$statement_privacy .= " AND (A.STATUS_SURAT IN ('TATAUSAHA','POSTING') OR A.STATUS_SURAT LIKE 'TU%') ";

$reqMode = "ubah";
$surat_masuk->selectByParamsLihat(array("A.SURAT_MASUK_ID" => $reqId), -1, -1, $statement_privacy);
// echo $surat_masuk->query;exit;
$surat_masuk->firstRow();

$reqSatuanKerjaIdAsal = $surat_masuk->getField("SATUAN_KERJA_ID_ASAL");
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



if($reqId == "")
	exit;

$reqParaf       = $surat_masuk_paraf->getJson(array("SURAT_MASUK_ID" => $reqId));

?>

<style type="text/css" class="init">
    th, td { white-space: nowrap; }
    div.dataTables_wrapper {
        /*width: 100%;
        margin: 0 auto;
        border: 2px solid red;
        height: calc(100vh - 80px);*/
    }

    th{
        background-color: #157DBA;
        color: #fff;
        font-size: 12px;
    }

    tr{
        background-color: #9bd0f2;
        font-size: 12px;
    }

    tr:nth-child(even){
        background-color: #aed8f2;
        font-size: 12px;
    }
</style>

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
                <a onClick="parent.openAdd('app/loadUrl/report/template/?reqId=<?=$reqId?>')"><i class="fa fa-file-pdf-o"></i> view as PDF</a>
                <script>
				function editSurat()
				{
					top.document.getElementById('contentFrame').src = 'app/loadUrl/app/surat_masuk_add/?reqId=<?=$reqId?>';	
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
	$reqSifatNaskah = "BIASA";
	?>
        <fieldset class="attachment-surat">
            <legend style="font-weight: bold; font-size: 17px;">Histori Disposisi</legend>
            <div class="inner">
            <table class="table">
            <thead>
              <tr>
                <th style="width:60%">TUJUAN</th>
                <th style="width:15%">SEBAGAI</th>
                <?
                if(strtoupper($reqSifatNaskah) == "BIASA")
				{
				?>
                <th style="width:20%">DISPOSISI</th>
                <?
				}
				?>
                <th style="width:5%"><i class="fa fa-envelope-open-o fa-md" aria-hidden="true" title="TERBACA"></th>
                <th style="width:5%"><i class="fa fa-clone fa-md" aria-hidden="true" title="TERDISPOSISI"></th>
                <th style="width:5%"><i class="fa fa-paper-plane fa-md" aria-hidden="true" title="TERBALAS"></th>
                <th style="width:5%"><i class="fa fa-mail-forward fa-md" aria-hidden="true" title="TERUSKAN"></th>
                <?
                if(strtoupper($reqSifatNaskah) == "BIASA")
				{
				?>
                <th style="width:5%"><i class="fa fa-print fa-md" aria-hidden="true" title="CETAK DISPOSISI"></th>
                <?
				}
				?>
              </tr>
            <tbody>
            <?	
            $disposisi = new Disposisi();
            // $disposisi->selectByParams(array("A.SURAT_MASUK_ID" => (int)$reqId, "A.DISPOSISI_PARENT_ID" => "0"));
            $disposisi->selectByParams(array("A.SURAT_MASUK_ID" => (int)$reqId));
           
            while($disposisi->nextRow())
            {
            ?>
                <tr>
                    <td><?=$disposisi->getField("NAMA_SATKER")?></td>
                    <td><?=$disposisi->getField("STATUS_DISPOSISI")?></td>
					<?
                    if(strtoupper($reqSifatNaskah) == "BIASA")
                    {
                    ?>
                    <td><b><?=$disposisi->getField("ISI")?></b><br><?=$disposisi->getField("KETERANGAN")?></td>
                    <?
					}
					?>
                    <td><?
                        if((int)$disposisi->getField("TERBACA") > 0)
                            echo "<i class=\"fa fa-check fa-md\" aria-hidden=\"true\"></i>";
                        else
                            echo "<i class=\"fa fa-close fa-md\" aria-hidden=\"true\"></i>";												
                        ?>
                    </td>
                    <td>
                        <?
                        if((int)$disposisi->getField("TERDISPOSISI") > 0)
                            echo "<i class=\"fa fa-check fa-md\" aria-hidden=\"true\"></i>";
                        else
                            echo "<i class=\"fa fa-close fa-md\" aria-hidden=\"true\"></i>";												
                        ?>
                    </td>
                    <td>
                        <?
                        if((int)$disposisi->getField("TERBALAS") > 0)
                            echo "<i class=\"fa fa-check fa-md\" aria-hidden=\"true\"></i>";
                        else
                            echo "<i class=\"fa fa-close fa-md\" aria-hidden=\"true\"></i>";												
                        ?>
                    </td>
                    <td>
                        <?
                        if((int)$disposisi->getField("TERBALAS") > 0)
                            echo "<i class=\"fa fa-check fa-md\" aria-hidden=\"true\"></i>";
                        else
                            echo "<i class=\"fa fa-close fa-md\" aria-hidden=\"true\"></i>";												
                        ?>
                    </td>
					<?
                    if(strtoupper($reqSifatNaskah) == "BIASA")
                    {
                    ?>
                    <td>
                    <?
                    if($disposisi->getField("STATUS_DISPOSISI") == "DISPOSISI")
                    {
                    ?>
                    <i class="fa fa-file-o fa-md" aria-hidden="true" onClick="parent.openAdd('app/loadUrl/report/template_disposisi/?reqId=<?=$reqId?>&reqDisposisiId=<?=$disposisi->getField("DISPOSISI_ID")?>')"></i>
                    <?
                    }
                    ?>
                    </td>
                    <?
                    }
                    ?>
                </tr>
                <?=ambilDisposisi($reqId, $disposisi->getField("SATUAN_KERJA_ID_TUJUAN"), 1, $reqSifatNaskah)?>
            <?
            }
            
            function ambilDisposisi($reqId, $satuanKerjaIdAsal, $tingkat, $reqSifatNaskah)
            {
                $disposisi = new Disposisi();
                $disposisi->selectByParams(array("A.SURAT_MASUK_ID" => (int)$reqId, "A.SATUAN_KERJA_ID_ASAL" => $satuanKerjaIdAsal, "STATUS_DISPOSISI" => "DISPOSISI"));
                
                while($disposisi->nextRow())
                {
            ?>
                    <tr>
                        <td><?=generateTingkat($tingkat).$disposisi->getField("NAMA_SATKER")?></td>
                        <td><?=$disposisi->getField("STATUS_DISPOSISI")?></td>
						<?
                        if(strtoupper($reqSifatNaskah) == "BIASA")
                        {
                        ?>
                    	<td><?=$disposisi->getField("ISI")?></td>
                        <?
						}
						?>
                        <td><?
                            if((int)$disposisi->getField("TERBACA") > 0)
                                echo "<i class=\"fa fa-check fa-md\" aria-hidden=\"true\"></i>";
                            else
                                echo "<i class=\"fa fa-close fa-md\" aria-hidden=\"true\"></i>";												
                            ?>
                        </td>
                        <td>
                            <?
                            if((int)$disposisi->getField("TERDISPOSISI") > 0)
                                echo "<i class=\"fa fa-check fa-md\" aria-hidden=\"true\"></i>";
                            else
                                echo "<i class=\"fa fa-close fa-md\" aria-hidden=\"true\"></i>";												
                            ?>
                        </td>
                        <td>
                            <?
                            if((int)$disposisi->getField("TERBALAS") > 0)
                                echo "<i class=\"fa fa-check fa-md\" aria-hidden=\"true\"></i>";
                            else
                                echo "<i class=\"fa fa-close fa-md\" aria-hidden=\"true\"></i>";												
                            ?>
                        </td>
                        <td>
                            <?
                            if((int)$disposisi->getField("TERUSKAN") > 0)
                                echo "<i class=\"fa fa-check fa-md\" aria-hidden=\"true\"></i>";
                            else
                                echo "<i class=\"fa fa-close fa-md\" aria-hidden=\"true\"></i>";												
                            ?>
                        </td>
						<?
                        if(strtoupper($reqSifatNaskah) == "BIASA")
                        {
                        ?>
                        <td>
                        <?
                        if($disposisi->getField("STATUS_DISPOSISI") == "DISPOSISI")
                        {
                        ?>
                        <i class="fa fa-file-o fa-md" aria-hidden="true" onClick="parent.openAdd('app/loadUrl/report/template_disposisi/?reqId=<?=$reqId?>&reqDisposisiId=<?=$disposisi->getField("DISPOSISI_ID")?>')"></i>
                        <?
                        }
                        ?>
                        </td>
                        <?
						}
						?>
                    </tr>
                    <?=ambilDisposisi($reqId, $disposisi->getField("SATUAN_KERJA_ID_TUJUAN"), ($tingkat+1), $reqSifatNaskah)?>                                    
            <?	
                }
            }
            ?>
            </tbody>
            </thead>
            </table>
                <div class="clearfix"></div>
            </div>
        </fieldset>
    
</div>