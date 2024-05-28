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
$reqId = $this->input->get("reqId");
$reqDisposisiId = $this->input->get("reqDisposisiId");


$reqIdDraft = $reqId;

$reqMode = "ubah";
$statement_privacy .= " AND (A.STATUS_SURAT = 'POSTING' OR 
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
$reqTERUSKAN     =  $surat_masuk->getField("TERUSKAN");
$reqTERBALAS     =  $surat_masuk->getField("TERBALAS");
$reqJenisTujuan     =  $surat_masuk->getField("JENIS_TUJUAN_ID");

if($reqId == ""){
	exit;
}

$reqParaf       = $surat_masuk_paraf->getJson(array("SURAT_MASUK_ID" => $reqId));
$reqKepada      = $disposisi->getJson(array("SURAT_MASUK_ID" => $reqId, "STATUS_DISPOSISI" => "TUJUAN"));
$reqTembusan    = $disposisi->getJson(array("SURAT_MASUK_ID" => $reqId, "STATUS_DISPOSISI" => "TEMBUSAN"));

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

<!-- ACCORDION -->
<link href="libraries/jquery-accordion-menu/style/format.css" rel="stylesheet" type="text/css" />
<link href="libraries/jquery-accordion-menu/style/text.css" rel="stylesheet" type="text/css" />
<!--<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"> </script>-->
<script type="text/javascript" src="libraries/jquery-accordion-menu/includes/javascript.js"> </script>

<style>
.accordionButton {
    /*width: 100%;
    padding-left: 20px;
    padding-right: 20px;
    height: 39px;
    line-height: 39px;
    float: left;
    background: #f1f3f5;
    color: #77726f;
    *font-size: 12px;
    border-bottom: 1px solid #dbdee3;
    cursor: pointer;*/
    width: 100%;
    padding-left: 20px;
    padding-right: 20px;
    height: auto;
    line-height: normal;
    float: left;
    background: none;
    color: #77726f;
    *font-size: 12px;
    border-bottom: none;
    cursor: pointer;
    
    *border: 2px solid red;
    
    background: rgba(255,255,255,0.4);
    padding: 10px;
    
    -webkit-border-radius: 4px;
    -moz-border-radius: 4px;
    border-radius: 4px; 
    
    margin-bottom: 10px;

}
.accordionButton:hover{
    background: rgba(255,255,255,0.4);
}
.accordionContent {
    /*width: 100%;
    float: left;
    background: #e9ebec;
    display: none;
    border-top: 1px solid #f8f9fa;
    min-height: calc(100% - 470px);
    height: -moz-calc(100% - 300px);
    height: -webkit-calc(100% - 300px);
    height: -o-calc(100% - 300px);
    height: calc(100% - 300px);
    overflow: auto;*/
    width: 100%;
    float: left;
    background: none;
    display: none;
    border-top: none;
    min-height: calc(100% - 470px);
    height: -moz-calc(100% - 300px);
    height: -webkit-calc(100% - 300px);
    height: -o-calc(100% - 300px);
    height: calc(100% - 300px);
    overflow: auto;
    
    *border: 2px solid red;
    padding-bottom: 5px;
}
.nav-tabs {
    border-bottom: none;
}
</style>

<div class="data">


    <div class="header-profil">
        <div class="col-md-1 area-kiri">
            <div class="avatar"><?=generateFoto("X", $surat_masuk->getField("USER_ATASAN"))?></div>
        </div>
        <div class="col-md-8 area-kiri">
            <div class="nama">
            <?=$surat_masuk->getField("USER_ATASAN")?>
            </div>
            <div class="jabatan">
            <?=$surat_masuk->getField("USER_ATASAN_JABATAN")?>
            </div>
            <div class="jabatan">
            <?=$surat_masuk->getField("INSTANSI_ASAL")?>
            </div>
            <div class="jabatan">
            <?=$surat_masuk->getField("ALAMAT_ASAL")?>
            </div>
            <div class="email"><?=getFormattedDate($surat_masuk->getField("TANGGAL_ENTRI"))?></div>
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
        	<div class="nomor-surat">No. surat : <?=$reqNoSurat?></div>
            <div class="klasifikasi-surat"><?=$reqKlasifikasiKode?> | <?=$reqKlasifikasi?></div>
        </div>
        <div class="col-md-4 area-kanan">
            <div class="aksi">
            <?
            if($surat_masuk->getField("USER_ID_OBSERVER") == $this->ID)
			{}
			else
			{
			?>
					<?
                    if($reqTERDISPOSISI == "")
                    {
                        if($this->KD_LEVEL_PEJABAT == "" && $this->ID_ATASAN = "")
                        {}
                        else
                        {
                    ?>
                         <a onClick="aksiSurat('DISPOSISI')"><i class="fa fa-pencil-square-o"></i> Disposisi</a>
                    <?
                        }
                    }
                    ?>
                <?
                if($reqJenisTujuan == "AGD")
                {}
                else
                {
                ?>                
                    <?
                    if($reqTERBALAS == "")
                    {
                    ?> 
                    <a onClick="aksiSurat('BALAS')"><i class="fa fa-mail-reply"></i> Balas</a>
                    <?
                    }
                    ?>
                <?
                }
                ?>
                    <?
                    if($reqTERUSKAN == "")
                    {
                    ?>
                    <a onClick="aksiSurat('TERUSKAN')"><i class="fa fa-mail-forward"></i> Teruskan</a>
                    <?
                    }
                    ?>
			
            <?
			}
			?>
            
			<?
            if($reqJenisTujuan == "AGD")
            {}
            else
            {
            ?>                
                <a onClick="parent.openAdd('app/loadUrl/report/template/?reqId=<?=$reqId?>')"><i class="fa fa-file-pdf-o"></i> view as PDF</a>
                
            <?
			}
			?>
            <script>
				function aksiSurat(AKSI)
				{
					top.document.getElementById('contentFrame').src = 'app/loadUrl/app/inbox_detil/?reqId=<?=$reqId?>&reqDisposisiId=<?=$reqDisposisiId?>&reqMode='+AKSI;	
				}
			</script>
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



    
    <?
    if($surat_masuk->getField("DISPOSISI") == "")
    {}
    else
    {
    ?>
    
        <!-- Adding "responsive" class triggers the magic -->
          <div class="tabbable responsive">

            <div class="header-profil">
                <div class="col-md-1 area-kiri">
                    <div class="avatar"><?=generateFoto("X", $surat_masuk->getField("NAMA_USER_ASAL"))?></div>
                </div>
                <div class="col-md-8 area-kiri">
                    <div class="nama">
                    <?=$surat_masuk->getField("NAMA_USER_ASAL")?>
                    </div>
                    <div class="jabatan">
                    <?=$surat_masuk->getField("NAMA_SATKER_ASAL")?>
                    </div>
                    <div class="email"><?=getFormattedDate($surat_masuk->getField("TANGGAL_DISPOSISI"))?></div>
                </div>    
            </div>

            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab1" data-toggle="tab"><?=$surat_masuk->getField("STATUS_DISPOSISI")?></a></li>
            </ul>
             <div class="field-item">
              <div class="tab-pane fade in active" id="tab1">
                <div class="form-group">
                    <div class="col-md-12">
                        <span style="font-weight: bold;">Pesan :</span>
                    </div>
                    <div class="col-md-12">
                        <?=$surat_masuk->getField("DISPOSISI")?>
                    </div>
                    <div class="col-md-12" style="margin-top: 10px;">
                        <span style="font-weight: bold;">Keterangan :</span>
                    </div>
                    <div class="col-md-12">
                        <?=$surat_masuk->getField("KETERANGAN")?>
                    </div>
                </div>
              </div>
            </div> <!-- /tab-content -->
         </div> <!-- /tabbable -->

    <?
    }
    ?>
    <div class="clearfix"></div>
    <?
	if($reqTERDISPOSISI != "" || $reqTERBALAS != "" || $reqTERUSKAN != "")
	{
	?>
        <fieldset class="attachment-surat">
            <legend>Histori Surat</legend>
            <div class="inner">
              <table class="table">
            <thead>
              <tr>
                <th style="width:45%">TUJUAN</th>
                <th style="width:37%">ISI</th>
                <th style="width:6%"><i class="fa fa-envelope-open-o fa-md" aria-hidden="true" title="TERBACA"></th>
                <th style="width:6%"><i class="fa fa-clone fa-md" aria-hidden="true" title="TERDISPOSISI"></th>
                <th style="width:6%"><i class="fa fa-paper-plane fa-md" aria-hidden="true" title="TERBALAS"></th>
                <th style="width:6%"><i class="fa fa-mail-forward fa-md" aria-hidden="true" title="TERUSKAN"></th>
                <th style="width:6%"><i class="fa fa-print fa-md" aria-hidden="true" title="CETAK DISPOSISI"></th>
              </tr>
            <tbody>
            <?	
            function ambilDisposisi($reqId, $reqDisposisiId, $tingkat)
            {
                $disposisi = new Disposisi();
                $disposisi->selectByParams(array("A.SURAT_MASUK_ID" => (int)$reqId, "A.DISPOSISI_PARENT_ID" => (int)$reqDisposisiId));
                
                while($disposisi->nextRow())
                {
					if(stristr($disposisi->getField("STATUS_DISPOSISI"), "DISPOSISI"))
						$icon = "fa-pencil-square-o";
					elseif($disposisi->getField("STATUS_DISPOSISI") == "BALASAN")
						$icon = "fa-mail-reply";
					elseif($disposisi->getField("STATUS_DISPOSISI") == "TERUSAN")
						$icon = "fa-mail-forward";
            ?>
                    <tr>
                        <td><?=generateTingkat($tingkat)."<i class='fa ".$icon."'></i> ".$disposisi->getField("NAMA_SATKER")?>
                             <br>
                             <?=generateTingkat($tingkat).$disposisi->getField("TANGGAL_DISPOSISI")?>
                        </td>
                    	<td><b><?=$disposisi->getField("ISI")?></b><br><?=$disposisi->getField("KETERANGAN")?></td>
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
                    </tr>
                    <?=ambilDisposisi($reqId, $disposisi->getField("DISPOSISI_ID"), ($tingkat+1))?>                                    
            <?	
                }
            }
			
            $disposisi = new Disposisi();
            $disposisi->selectByParams(array("A.DISPOSISI_PARENT_ID" => (int)$reqDisposisiId));
            $i = 0;
            while($disposisi->nextRow())
            {
				if(stristr($disposisi->getField("STATUS_DISPOSISI"), "DISPOSISI"))
					$icon = "fa-pencil-square-o";
				elseif($disposisi->getField("STATUS_DISPOSISI") == "BALASAN")
					$icon = "fa-mail-reply";
				elseif($disposisi->getField("STATUS_DISPOSISI") == "TERUSAN")
					$icon = "fa-mail-forward";
					
            ?>
                <tr>
                    <td><i class="fa <?=$icon?>"></i> <?=$disposisi->getField("NAMA_SATKER")?>
                         <br>
                         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?=$disposisi->getField("TANGGAL_DISPOSISI")?>
                </td>
                    <td><?=$disposisi->getField("ISI")?></td>
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
                </tr>
                <?=ambilDisposisi($reqId, $disposisi->getField("DISPOSISI_ID"), 1)?>
            <?
            }
			
            ?>
            </tbody>
            </thead>
            </table>
                <div class="clearfix"></div>
            </div>
        </fieldset>
    
    <?
	}
	?>
</div>