<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->library("suratmasukinfo");
$suratmasukinfo = new suratmasukinfo();

$this->load->model("UserCalendar");
$user_calendar = new UserCalendar();

$token = $user_calendar->getTokenGoogle($this->ID);

$infohakakses= $this->HAK_AKSES;

$this->load->model("SuratMasuk");
$this->load->model("SuratMasukParaf");
$this->load->model("Disposisi");
$this->load->model("DisposisiKelompok");
$this->load->model("SatuanKerja");
$this->load->model("SuratMasukReference");

$surat_masuk = new SuratMasuk();
$surat_masuk_attachment = new SuratMasuk();
$disposisi   = new Disposisi();
$disposisi_kelompok = new DisposisiKelompok();
$surat_masuk_paraf = new SuratMasukParaf();

$reqJenisTujuan = "NI";
$reqId = $this->input->get("reqId");
$reqAksi = $this->input->get("reqAksi");
$reqReplyId= $this->input->get("reqReplyId");

$reqLinkMode= $this->input->get("reqMode");
if(!empty($reqLinkMode))
{
    $reqLinkModeDetil= str_replace("_detil", "", $reqLinkMode);
}

// echo $reqId;exit;
$refDisposisiId = $this->input->get("refDisposisiId");

$reqIdDraft = $reqId;
if ($reqId == "") {
    $reqSifatNaskah= "Biasa";
    $reqButuhAksiId= 2;
    $reqJenisNaskah= "23";
    $reqJenisNaskahNama= "Laporan Kerusakan Inventaris";
    $reqJenisTTD = "QRCODE";

    $reqMode = "insert";
    $reqStatusSurat = "DRAFT";
    $reqIsMeeting   = "T";
    $reqParaf = "";

    if(!empty($reqReplyId))
    {
        $reqKepada = $disposisi->getreplyjson(array("SURAT_MASUK_ID" => $reqReplyId, "STATUS_DISPOSISI" => "TUJUAN"));
        $reqSatuanKerjaId= $this->SATUAN_KERJA_ID_ASAL;
    }
    else
    {
        $reqKepada = "[]";
    }

    $reqTembusan = "[]";
    $reqTanggal = date("d-m-Y");

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
    $reqButuhAksiId= $surat_masuk->getField("BUTUH_AKSI_ID");
    $reqTahun= $surat_masuk->getField("TAHUN");
    $reqLampiranDrive= $surat_masuk->getField("LAMPIRAN_DRIVE");

    $reqKlasifikasiId = $surat_masuk->getField("KLASIFIKASI_ID");
    $reqNoAgenda = $surat_masuk->getField("NO_AGENDA");

    // tambahan khusus
    $reqNoSurat = $surat_masuk->getField("NOMOR");
    if(empty($reqNoSurat))
        $reqNoSurat = $surat_masuk->getField("INFO_NOMOR_SURAT");

    $reqPemesanSatuanKerjaId= $surat_masuk->getField("PEMESAN_SATUAN_KERJA_ID");
    $reqPemesanSatuanKerjaIsi= $surat_masuk->getField("PEMESAN_SATUAN_KERJA_ISI");

    $reqTanggal = $surat_masuk->getField("TANGGAL");
    $reqPerihal = $surat_masuk->getField("PERIHAL");
    $reqKeterangan = $surat_masuk->getField("ISI");
    // var_dump($reqKeterangan); exit();
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

    $reqAnStatus= $surat_masuk->getField("AN_STATUS");
    $reqAnStatusNama= $surat_masuk->getField("AN_NAMA");

    $reqPenerbitNomor= "";
    if(!empty($reqJenisNaskah))
    {
        $reqPenerbitNomor = $this->db->query("SELECT PENERBIT_NOMOR FROM JENIS_NASKAH WHERE JENIS_NASKAH_ID = '" . $reqJenisNaskah . "' ")->row()->penerbit_nomor;
    }
    $reqUserId = $surat_masuk->getField("USER_ID");


    if ($reqPermohonanNomorId == "0")
        $reqPermohonanNomorId = "";

    $reqSatuanKerjaId = $surat_masuk->getField("SATUAN_KERJA_ID_ASAL");
    $reqStatusApprove= $surat_masuk->getField("STATUS_APPROVE");

    // tambahan khusus
    if(!empty($reqId))
    {
        $reqParaf = $surat_masuk_paraf->getParaf(array("SURAT_MASUK_ID" => $reqId));
        if($reqParaf == "'''")
            $reqParaf= "";

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

        $satuan_kerja= new SatuanKerja();
        $satuan_kerja->selectByParams(array(), -1, -1, " AND SATUAN_KERJA_ID = '".$reqSatuanKerjaId."'", " ORDER BY KODE_SO ASC ");
        $satuan_kerja->firstRow();
        // echo $satuan_kerja->query;exit;
        $infopenandatangankode= $satuan_kerja->getField("KODE_SURAT");
        $infopenandatangannamapejabat= $satuan_kerja->getField("NAMA_PEGAWAI");
        $infopenandatangannip= $satuan_kerja->getField("NIP");
        $infosatuankerjainfo= $satuan_kerja->getField("JABATAN")." ".$satuan_kerja->getField("NAMA_PEGAWAI")." (".$infopenandatangannip.")";

        $idata=0;
        $arrreturnhirarki= [];
        $satuan_kerja= new SatuanKerja();
        $satuan_kerja->selectByParamsHirarki($reqSatuanKerjaId);
        // echo $satuan_kerja->query;exit;
        while($satuan_kerja->nextRow()) 
        {
            if($idata == 1 || $idata == 2)
            {
                $datainfojson= 
                array(
                    "nama"=>$satuan_kerja->getField("NAMA")
                    , "nip"=>$satuan_kerja->getField("NIP")
                    , "lokasi"=>$satuan_kerja->getField("LOKASI")
                    , "kode"=>$satuan_kerja->getField("KODE_SURAT")
                    , "id"=>$satuan_kerja->getField("SATUAN_KERJA_ID")
                );
                array_push($arrreturnhirarki, $datainfojson);
            }
            elseif($idata == 0)
            {
                $parentdatainfojson= 
                array(
                    "nama"=>$satuan_kerja->getField("NAMA")
                    , "nip"=>$satuan_kerja->getField("NIP")
                    , "lokasi"=>$satuan_kerja->getField("LOKASI")
                    , "kode"=>$satuan_kerja->getField("KODE_SURAT")
                    , "id"=>$satuan_kerja->getField("SATUAN_KERJA_ID")
                );
            }
            $idata++;
        }

        if($idata > 0)
        {
            array_push($arrreturnhirarki, $parentdatainfojson);
        }

        if(!empty($arrreturnhirarki[0]["nama"]))
        {
            $infopenandatangandirektorat= $arrreturnhirarki[0]["nama"];
        }

        if(!empty($arrreturnhirarki[1]["nama"]))
        {
            $infopenandatangansubdirektorat= $arrreturnhirarki[1]["nama"];
            $infopenandatanganlokasi= $arrreturnhirarki[1]["lokasi"];

            $setdetil= new SatuanKerja();
            $setdetil->selectByParams(array(), -1,-1, " AND A.SATUAN_KERJA_ID = '".$arrreturnhirarki[1]["id"]."'");
            $setdetil->firstRow();
            $infopenandatangankodeunit= $setdetil->getField("SATUAN_KERJA_ID");
            $infopenandatangankota= $setdetil->getField("NAMA");
        }

        if(!empty($arrreturnhirarki[2]["nama"]))
        {
            $infopenandatanganlokasi= $arrreturnhirarki[2]["lokasi"];

            $setdetil= new SatuanKerja();
            $setdetil->selectByParams(array(), -1,-1, " AND A.SATUAN_KERJA_ID = '".$arrreturnhirarki[2]["id"]."'");
            $setdetil->firstRow();
            $infopenandatangankodeunit= $setdetil->getField("SATUAN_KERJA_ID");
            $infopenandatangankota= $setdetil->getField("NAMA");
        }
    }

}

if(!empty($reqKepada) || !empty($reqKepadaKelompok))
{
    $arrinfokepada= [];
    $ikepada=0;
    $arrkepada= json_decode($reqKepada);
    foreach ($arrkepada as $key => $value)
    {
        $arrinfokepada[$ikepada]["tipe"]= 'langsung';
        $arrinfokepada[$ikepada]["rowid"]= $value->DISPOSISI_ID;
        $arrinfokepada[$ikepada]["label"]= $value->SATUAN_KERJA;
        $arrinfokepada[$ikepada]["tujuan"]= $value->SATUAN_KERJA_ID;
        $arrinfokepada[$ikepada]["id"]= $value->SATUAN_KERJA_ID;
        $ikepada++;
    }

    $arrkepadakelompok= json_decode($reqKepadaKelompok);
    foreach ($arrkepadakelompok as $key => $value) 
    {
        $arrinfokepada[$ikepada]["tipe"]= 'kelompok';
        $arrinfokepada[$ikepada]["rowid"]= $value->DISPOSISI_ID;
        $arrinfokepada[$ikepada]["label"]= $value->NAMA_KELOMPOK;
        $arrinfokepada[$ikepada]["tujuan"]= $value->SATUAN_KERJA_KELOMPOK_ID;
        $arrinfokepada[$ikepada]["id"]= $value->SATUAN_KERJA_KELOMPOK_ID;
        $ikepada++;
    }

    $keys= array_column($arrinfokepada, "rowid");
    array_multisort($keys, SORT_ASC, $arrinfokepada);
}

if(!empty($reqTembusan) || !empty($reqTembusanKelompok))
{
    $arrinfotembusan= [];
    $itembusan=0;
    $arrtembusan= json_decode($reqTembusan);
    foreach ($arrtembusan as $key => $value)
    {
        $arrinfotembusan[$itembusan]["tipe"]= 'langsung';
        $arrinfotembusan[$itembusan]["rowid"]= $value->DISPOSISI_ID;
        $arrinfotembusan[$itembusan]["label"]= $value->SATUAN_KERJA;
        $arrinfotembusan[$itembusan]["tujuan"]= $value->SATUAN_KERJA_ID;
        $arrinfotembusan[$itembusan]["id"]= $value->SATUAN_KERJA_ID;
        $itembusan++;
    }

    $arrtembusankelompok= json_decode($reqTembusanKelompok);
    foreach ($arrtembusankelompok as $key => $value) 
    {
        $arrinfotembusan[$itembusan]["tipe"]= 'kelompok';
        $arrinfotembusan[$itembusan]["rowid"]= $value->DISPOSISI_ID;
        $arrinfotembusan[$itembusan]["label"]= $value->NAMA_KELOMPOK;
        $arrinfotembusan[$itembusan]["tujuan"]= $value->SATUAN_KERJA_KELOMPOK_ID;
        $arrinfotembusan[$itembusan]["id"]= $value->SATUAN_KERJA_KELOMPOK_ID;
        $itembusan++;
    }

    $keys= array_column($arrinfotembusan, "rowid");
    array_multisort($keys, SORT_ASC, $arrinfotembusan);
}

// tambahan khusus
$reqKelompokJabatan = "";
if(!empty($reqId))
{
    $suratmasukinfo->getInfo($reqId, "INTERNAL");
    $reqKelompokJabatan = $suratmasukinfo->KELOMPOK_JABATAN;
}

$checkparafid= "";
// tambahan khusus
if (!empty($reqId))
{
    if($reqStatusSurat == "DRAFT" || $reqStatusSurat == "REVISI"){}
    elseif($reqStatusSurat == "PARAF" || $reqStatusSurat == "VALIDASI")
    {
        $set= new SuratMasukParaf();
        $infonextpemaraf= $set->getNextParaf(" AND COALESCE(NULLIF(A.STATUS_PARAF, ''), NULL) IS NULL AND A.SURAT_MASUK_ID = ".$reqIdDraft);

        $set= new SuratMasuk();
        $statement= " AND ((A.USER_ATASAN_ID = '".$this->ID."' AND  A.APPROVAL_DATE IS NULL) OR (A.USER_ATASAN_ID = '".$this->USER_GROUP.$this->ID."' AND A.APPROVAL_DATE IS NOT NULL )) AND A.STATUS_SURAT IN ('PARAF', 'VALIDASI') AND A.SURAT_MASUK_ID = ".$reqIdDraft;
        if(!empty($infonextpemaraf))
        {
            $statement.= " AND COALESCE(A.NEXT_URUT,1) = ".$infonextpemaraf;
        }
        $sOrder= "";
        $satuankerjaganti= $this->SATUAN_KERJA_ID_ASAL;
        $set->selectByParamsNewPersetujuan(array(), -1, -1, $this->ID, $this->USER_GROUP, $statement, $sOrder, $reqIdDraft, $satuankerjaganti);
        $set->firstRow();
        $checkparafid= $set->getField("SURAT_MASUK_ID");
        $checknextpemaraf= $set->getField("NEXT_URUT");
        $checkstatusbantu= $set->getField("STATUS_BANTU");
        $chekvalidasi= "";
        if(isset($checknextpemaraf))
            $chekvalidasi= "validasi";
        
        if (empty($checkparafid) && empty($reqId))
        {
            redirect("main/index/newdraft");
        }
        else
        {
            if((!empty($reqLinkMode) && $infonextpemaraf !== $checknextpemaraf) || empty($checknextpemaraf))
            {
                if($chekvalidasi == "validasi"){}
                else
                {
                    // echo $chekvalidasi."-".$checknextpemaraf."--".$infonextpemaraf;exit;
                    $set= new SuratMasuk();
                    $set->selectByParams(array("A.SURAT_MASUK_ID"=>$reqIdDraft));
                    $set->firstRow();
                    // echo $set->query;exit;
                    $infoperihal= $set->getField("PERIHAL");
                    $infojenisnaskahid= $set->getField("JENIS_NASKAH_ID");
                    unset($set);

                    $arrlink= $suratmasukinfo->infolinkdetil($infojenisnaskahid);
                    $infolinkdetil= $arrlink["linkstatusdetil"];
                    // echo "main/index/".$infolinkdetil."/?reqId=".$reqIdDraft;exit;

                    redirect("main/index/".$infolinkdetil."/?reqId=".$reqIdDraft);
                }
            }
        }
    }
    else
    redirect("main/index/newdraft");
}
elseif (empty($reqId) && !empty($reqIdDraft))
{
    if(!empty($reqLinkMode))
    {
        $set= new SuratMasuk();
        $set->selectByParams(array("A.SURAT_MASUK_ID"=>$reqIdDraft));
        $set->firstRow();
        // echo $set->query;exit;
        $infoperihal= $set->getField("PERIHAL");
        $infojenisnaskahid= $set->getField("JENIS_NASKAH_ID");
        unset($set);

        $arrlink= $suratmasukinfo->infolinkdetil($infojenisnaskahid);
        $infolinkdetil= $arrlink["linkstatusdetil"];
        // echo "main/index/".$infolinkdetil."/?reqId=".$reqIdDraft;exit;

        redirect("main/index/".$infolinkdetil."/?reqId=".$reqIdDraft);
    }
    else
    {
        redirect("main/index/newdraft");
    }
}
?>
<?php /*?><!DOCTYPE html>
<html ng-app="app">
  <head>
    <base href="<?=base_url()?>">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title><?php */ ?>

    <script src="lib/easyui2/globalfunction.js"></script>
    <div class="col-lg-12 col-konten-full">
        <!--<div class="judul-halaman-tulis">Surat Internal</div>-->
        <div class="judul-halaman bg-course">
            <span><img src="images/icon-course.png"></span> Laporan Kerusakan Inventaris
            <div class="btn-atas clearfix">
                <?
                $aksibutton= "";
                if(!empty($reqId)) 
                {
                ?>
                <a class="btn btn-danger btn-sm pull-right" id="buttonpdf" onClick="submitPreview()" style="cursor: pointer;"><i class="fa fa-file-pdf-o"></i> View as PDF</a>
                <?
                }
                ?>
        
                <?
                // tambahan khusus, kalau paraf sesuai urutan
                if ($reqStatusSurat == "PARAF" && !empty($checkparafid) && $reqUserId != $this->ID) 
                {
                    $aksibutton= "1";

                    $infobutton= "Setujui";
                    if($checkstatusbantu == "1")
                        $infobutton= "Forward";
                ?>
                    <button class="btn btn-primary btn-sm pull-right" type="button" onClick="submitForm('UBAHDATAPARAF')"><i class="fa fa-save"></i> Simpan</button>
                    <button class="btn btn-primary btn-sm pull-right" type="button" onClick="submitForm('REVISI')"><i class="fa fa-level-down"></i> Kembalikan</button>
                    <button class="btn btn-primary btn-sm pull-right" type="button" onClick="submitForm('PARAF')"><i class="fa fa-check-square-o"></i> <?=$infobutton?></button>
                <?
                }

                if ($reqStatusSurat == "REVISI" && $reqUserId == $this->ID)
                {
                    $aksibutton= "1";
                ?>
                    <button class="btn btn-primary btn-sm pull-right" type="button" onClick="submitForm('UBAHDATAREVISI')"><i class="fa fa-save"></i> Simpan</button>
                    <button class="btn btn-primary btn-sm pull-right" type="button" onClick="submitForm('UBAHDATAPOSTING')"><i class="fa fa-paper-plane"></i> Kirim</button>
                    <button class="btn btn-warning btn-sm pull-right" type="button" onClick="setagenda()"><i class="fa fa-list"></i> Agenda Surat</button>
                <?
                }
                
                if ($reqId == "" || ($reqStatusSurat == "DRAFT" && !empty($reqId)) ) 
                {
                    $aksibutton= "1";
                ?>
                    <button class="btn btn-primary btn-sm pull-right" type="button" onClick="submitForm('UBAHDATADRAFTPARAF')"><i class="fa fa-paper-plane"></i> Kirim</button>
                    <button class="btn btn-default btn-sm pull-right" type="button" onClick="submitForm('DRAFT')"><i class="fa fa-file-o"></i> Draft</button>
                <?
                }

                if ($reqStatusSurat == "DRAFT" && !empty($reqId)) 
                {
                ?>
                    <button class="btn btn-danger btn-sm pull-right" type="button" onClick="deleteForm()"><i class="fa fa-trash-o"></i> Hapus</button>
                <?
                }

                // tambahan khusus
                if (!empty($reqId) && $reqStatusSurat == "VALIDASI" && $reqUserAtasanId == $this->ID)
                {
                    $aksibutton= "1";
                ?>
                    <button class="btn btn-primary btn-sm pull-right" type="button" onClick="submitForm('UBAHDATAVALIDASI')"><i class="fa fa-save"></i> Simpan</button>
                    <button class="btn btn-primary btn-sm pull-right" type="button" onClick="submitForm('REVISI')"><i class="fa fa-level-down"></i> Kembalikan</button>
                    <button class="btn btn-primary btn-sm pull-right" type="button" onClick="submitForm('POSTING')"><i class="fa fa-check-square-o"></i> Setujui</button>

                <?
                }

                if(!empty($reqLinkMode))
                {
                ?>
                <a class="btn btn-danger btn-sm pull-right" id="buttonpdf" onClick="setsurat()" style="cursor: pointer;"><i class="fa fa-file-pdf-o"></i> Lihat Surat</a>
                <?
                }
                ?>
            </div>
        </div>
        <div class="konten-detil">
            
            <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a data-toggle="tab" href="#tab-informasi">
                            <span style="display: none;" id="tab-informasi-success"><i class="fa fa-check-circle text-success" aria-hidden="true"></i></span>
                            <span style="display: none;" id="tab-informasi-danger"><i class="fa fa-times-circle text-danger" aria-hidden="true"></i></span>
                            Informasi Surat
                        </a>
                    </li>
                    <li>
                        <a data-toggle="tab" href="#tab-isi">
                            <span style="display: none;" id="tab-isi-success"><i class="fa fa-check-circle text-success" aria-hidden="true"></i></span>
                            <span style="display: none;" id="tab-isi-danger"><i class="fa fa-times-circle text-danger" aria-hidden="true"></i></span>
                            Isi
                        </a>
                    </li>
                    <li>
                        <a data-toggle="tab" href="#tab-atribut">
                            <span style="display: none;" id="tab-atribut-success"><i class="fa fa-check-circle text-success" aria-hidden="true"></i></span>
                            <span style="display: none;" id="tab-atribut-danger"><i class="fa fa-times-circle text-danger" aria-hidden="true"></i></span>
                            Atribut
                        </a>
                    </li>
                </ul>
                
                <div class="tab-content">
                    <div id="tab-informasi" class="tab-pane fade in active">
                        <table class="table">
                            <thead>
                                <tr>
                                    <td>Kepada <span class="text-danger" id="kepadaa">*</span></td>
                                    <td>:</td>
                                    <td>
                                        <?
                                        if(empty($reqReplyId))
                                        {
                                        ?>
                                        <a onClick="top.openAdd('app/loadUrl/main/satuan_kerja_tujuan_multi_lookup/?reqJenis=TUJUAN&reqJenisSurat=INTERNAL&reqIdField=divTujuanSurat')">Tujuan <i class="fa fa-users"></i></a>
                                        <?
                                        }
                                        ?>

                                        <div class="inner" id="divTujuanSurat">
                                            <div class="btn-group">
                                                <?
                                                if(!empty($arrinfokepada))
                                                {
                                                    foreach ($arrinfokepada as $key => $value) 
                                                    {
                                                        $valkepadatipe= $value["tipe"];
                                                        $valkepadarowid= $value["rowid"];
                                                        $valkepadalabel= $value["label"];
                                                        $valkepadatujuan= $value["tujuan"];
                                                        $valkepadaid= $value["id"];
                                                ?>
                                                    <div class="item">TUJUAN:<?=$valkepadalabel?>
                                                        <?
                                                        if($valkepadatipe == "langsung")
                                                        {
                                                            if(empty($reqReplyId))
                                                            {
                                                        ?>
                                                            <i class="fa fa-times-circle" onclick="$(this).parent().remove(); setinfovalidasi();"></i>
                                                        <?
                                                            }
                                                        ?>
                                                            <input type="hidden" name="reqTujuanSuratValidasi" value="<?=$valkepadatujuan?>">
                                                            <input type="hidden" name="reqSatuanKerjaIdTujuan[]" value="<?=$valkepadaid?>">
                                                        <?
                                                        }
                                                        elseif($valkepadatipe == "kelompok")
                                                        {
                                                        ?>
                                                            <i class="fa fa-times-circle" onclick="$(this).parent().remove(); setinfovalidasi();"></i>
                                                            <input type="hidden" name="reqTujuanSuratValidasiKelompok" value="<?=$valkepadatujuan?>">
                                                            <input type="hidden" name="reqSatuanKerjaIdTujuan[]" value="<?=$valkepadaid?>">
                                                        <?
                                                        }
                                                        ?>
                                                    </div>
                                                <?
                                                    }
                                                }
                                                ?>
                                                <?
                                                foreach ($arrKepada as $key => $value) {
                                                ?>
                                                    <div class="item">TUJUAN:<?= $value->SATUAN_KERJA ?>
                                                        <?
                                                        if(empty($reqReplyId))
                                                        {
                                                        ?>
                                                        <i class="fa fa-times-circle" onclick="$(this).parent().remove();"></i>
                                                        <?
                                                        }
                                                        ?>
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
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td>Tembusan</td>
                                    <td>:</td>
                                    <td>
                                        <a onClick="top.openAdd('app/loadUrl/main/satuan_kerja_tujuan_multi_lookup/?reqJenis=TEMBUSAN&reqJenisSurat=INTERNAL&reqIdField=divTembusanSurat')">Tembusan <i class="fa fa-users" aria-hidden="true"></i></a>
                                        <div class="inner" id="divTembusanSurat">
                                            <div class="btn-group">
                                                <?
                                                if(!empty($arrinfotembusan))
                                                {
                                                    foreach ($arrinfotembusan as $key => $value) 
                                                    {
                                                        $valkepadatipe= $value["tipe"];
                                                        $valkepadarowid= $value["rowid"];
                                                        $valkepadalabel= $value["label"];
                                                        $valkepadatujuan= $value["tujuan"];
                                                        $valkepadaid= $value["id"];
                                                ?>
                                                    <div class="item">TUJUAN:<?=$valkepadalabel?>
                                                        <?
                                                        if($valkepadatipe == "langsung")
                                                        {
                                                        ?>
                                                            <i class="fa fa-times-circle" onclick="$(this).parent().remove();"></i>
                                                            <input type="hidden" name="reqTujuanSuratValidasi" value="<?=$valkepadatujuan?>">
                                                            <input type="hidden" name="reqSatuanKerjaIdTembusan[]" value="<?=$valkepadaid?>">
                                                        <?
                                                        }
                                                        elseif($valkepadatipe == "kelompok")
                                                        {
                                                        ?>
                                                            <i class="fa fa-times-circle" onclick="$(this).parent().remove();"></i>
                                                            <input type="hidden" name="reqTujuanSuratValidasiKelompok" value="<?=$valkepadatujuan?>">
                                                            <input type="hidden" name="reqSatuanKerjaIdTembusan[]" value="<?=$valkepadaid?>">
                                                        <?
                                                        }
                                                        ?>
                                                    </div>
                                                <?
                                                    }
                                                }
                                                ?>
                                            </div>
            
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Pemesan</td>
                                    <td>:</td>
                                    <td>
                                        <input type="text" id="reqPemesanSatuanKerjaId" class="easyui-combotree" name="reqPemesanSatuanKerjaId" data-options="width:'500'
                                        , panelHeight:'120'
                                        , valueField:'id'
                                        , textField:'text'
                                        , url:'web/satuan_kerja_json/combotreesatker/'
                                        , prompt:'Tentukan Pemesan...'," value="<?=$reqPemesanSatuanKerjaId?>"
                                        />
                                    </td>
                                </tr>
                                <tr>
                                    <td>Catatan Pemesan</td>
                                    <td>:</td>
                                    <td>
                                        <textarea placeholder="Isi Catatan Pemesan..." id="reqPemesanSatuanKerjaIsi" name="reqPemesanSatuanKerjaIsi"><?=$reqPemesanSatuanKerjaIsi?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Pengirim <span class="text-danger">*</td>
                                    <td>:</td>
                                    <td>
                                        <?
                                        // kalau nota dinas perihal boleh edit
                                        $infodisplay= "";
                                        // if(!empty($checkparafid) || $reqStatusSurat == "REVISI")
                                        if(empty($aksibutton) || !empty($checkparafid) || $reqStatusSurat == "XXXREVISI")
                                        {
                                            $infodisplay= "none";
                                        ?>
                                        <span style="display: none;">
                                            <input type="text" id="reqSatuanKerjaId" class="easyui-combotree" name="reqSatuanKerjaId" value="<?=$reqSatuanKerjaId?>" />
                                        </span>
                                        <?=$infosatuankerjainfo?>
                                        <?
                                        }
                                        else
                                        {
                                        ?>
                                        <input type="text" id="reqSatuanKerjaId" class="easyui-combotree" name="reqSatuanKerjaId" data-options="
                                        onClick: function(rec){
                                            $('#infopenandatangankode').text(rec.KODE_SURAT);
                                            $('#infopenandatangannamapejabat').text(rec.NAMA_PEGAWAI);
                                            $('#infopenandatangannip').text(rec.NIP);
                                            $('#infopenandatangandirektorat').text(rec.DIREKTORAT);
                                            $('#infopenandatangansubdirektorat').text(rec.DIREKTORATSUB);
                                            $('#infopenandatanganlokasi').text(rec.DIREKTORATLOKASI);
                                            $('#infopenandatangankodeunit').text(rec.DIREKTORATUNITKODE);
                                            $('#infopenandatangankota').text(rec.DIREKTORATKOTA);
                                            $('#reqUserAtasanId').val(rec.NIP);
                                            $('#reqAsalSuratInstansi').val(rec.SATUAN_KERJA);
                                            var url = 'web/satuan_kerja_json/combo_paraf/?reqId='+rec.SATUAN_KERJA_ID;
                                            // $('#reqSatuanKerjaIdParaf').combotree('reload', url);
                                            // $('#reqSatuanKerjaIdParaf').combotree('setValue', '');

                                            // tambahan khusus
                                            if(rec.NIP == '')
                                            {
                                                $.messager.alert('Info', 'Pengirim belum di tentukan di master.', 'info');

                                                $('#infopenandatangankode, #infopenandatangannamapejabat, #infopenandatangannip, #infopenandatangandirektorat, #infopenandatangansubdirektorat, #infopenandatanganlokasi, #infopenandatangankodeunit, #infopenandatangankota').text('');
                                                $('#reqAsalSuratInstansi, #reqUserAtasanId').val(rec.NIP);
                                                $('#reqSatuanKerjaId').combotree('setValue', '');
                                            }
                                            setinfovalidasi();
                                        }
                                        , width:'500'
                                        , panelHeight:'120'
                                        , valueField:'id'
                                        , textField:'text'
                                        , url:'web/satuan_kerja_json/combotreesatker/'
                                        , prompt:'Tentukan Pengirim...'," value="<?=$reqSatuanKerjaId?>"
                                        required="required"
                                        />
                                        <?
                                        }
                                        ?>
                                        <input type="hidden" name="reqAsalSuratInstansi" id="reqAsalSuratInstansi" class="easyui-validatebox textbox" readonly data-options="required:true" value="<?= $this->SATUAN_KERJA_ASAL ?>" style="width:100%">
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td>
                                        Pemeriksa
                                        <?
                                        if(empty($infodisplay))
                                        {
                                        ?>
                                        <a onClick="top.openAdd('app/loadUrl/main/satuan_kerja_tujuan_multi_lookup/?reqJenis=PARAF&reqJenisSurat=INTERNAL&reqIdField=divpemeriksa')"><i class="fa fa-plus-circle fa-lg"></i></a>
                                        <?
                                        }
                                        ?>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <input type="hidden" id="reqSatuanKerjaIdParaf" />
                                        <div class="inner" id="divpemeriksa">
                                            <?
                                            if(!empty($reqId))
                                            {
                                                $setinfoparaf= new SuratMasukParaf();
                                                $setinfoparaf->selectByParams(array(), -1, -1, " AND A.STATUS_BANTU IS NULL AND A.SURAT_MASUK_ID = ".$reqId, "ORDER BY A.NO_URUT");
                                                while($setinfoparaf->nextRow())
                                                {
                                                    $valparafnama= $setinfoparaf->getField("NAMA_SATKER");
                                                    $valparafid= $setinfoparaf->getField("SATUAN_KERJA_ID_TUJUAN");
                                            ?>
                                                <div class="item">PARAF: <?=$valparafnama?>
                                                    <?
                                                    if(empty($infodisplay))
                                                    {
                                                    ?>
                                                    <i class="fa fa-times-circle" onclick="$(this).parent().remove();"></i>
                                                    <?
                                                    }
                                                    ?>
                                                    <input type="hidden" name="reqTujuanSuratParafValidasi" value="<?=$valparafid?>">
                                                    <input type="hidden" name="reqSatuanKerjaIdParaf[]" value="<?=$valparafid?>" />
                                                </div>
                                            <?
                                                }
                                            }
                                            ?>
                                        </div>
                                      
                                        <!-- <input type="hidden" id="reqSatuanKerjaIdParaf" name="reqSatuanKerjaIdParaf" /> -->

                                    </td>
                                </tr>
                                <tr>
                                    <td>Perihal <span class="text-danger">*</td>
                                    <td>:</td>
                                    <td>
                                        <input type="text" name="reqPerihal" id="reqPerihal" class="easyui-validatebox" value="<?=$reqPerihal?>" style="width: 900px;" placeholder="Silakan tulis judul surat Anda..." onkeyup="setinfovalidasi();" required />
                                    </td>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    
                    <div id="tab-isi" class="tab-pane fade">
                        <table class="table">
                            <thead>
                                <tr>
                                    <td>Isi Surat <span class="text-danger">*</span></td>
                                    <td>:</td>
                                    <td>
                                        <button class="btn btn-success btn-sm pull-left" type="button" onClick="addRow()"><i class="fa fa-plus-circle"></i> Tambah</button>
                                        <table style="width:100%;margin-left: 5px;">
                                            <thead>
                                                <th style="border: solid black 1pt;text-align: center; background-color: lightgray;">Nomor Inventaris</th>
                                                <th style="border: solid black 1pt;text-align: center; background-color: lightgray;">Nama Barang</th>
                                                <th style="border: solid black 1pt;text-align: center; background-color: lightgray;">Posisi Barang</th>
                                                <th style="border: solid black 1pt;text-align: center; background-color: lightgray;">Uraian Kerusakan</th>
                                                <th style="border: solid black 1pt;text-align: center; background-color: lightgray;">Penyebab Kerusakan</th>
                                                <th style="border: solid black 1pt;text-align: center; background-color: lightgray;">Usaha Penanggulangan</th>
                                                <th style="border: solid black 1pt;text-align: center; background-color: lightgray;">Aksi</th>
                                            </thead>
                                            <tbody id='tbodyObyek'>
                                                <?
                                                $KerusakanBarang = new SuratMasuk();
                                                $KerusakanBarang->selectByParamsKerusakan(array(), -1, -1, " AND SURAT_MASUK_ID =".$reqId);
                                                while($KerusakanBarang->nextRow()){
                                                    $reqKerusakanId = $KerusakanBarang->getField("SURAT_MASUK_KERUSAKAN_ID");
                                                    $reqKerusakanInventaris = $KerusakanBarang->getField("NO_INVENTARIS");
                                                    $reqKerusakanPosisi = $KerusakanBarang->getField("POSISI");
                                                    $reqKerusakanNama = $KerusakanBarang->getField("NAMA");
                                                    $reqKerusakanUraian = $KerusakanBarang->getField("KERUSAKAN");
                                                    $reqKerusakanPenyebab = $KerusakanBarang->getField("PENYEBAB");
                                                    $reqKerusakanPenanggulangan = $KerusakanBarang->getField("PENANGGULANGAN");
                                                ?>
                                                    <tr>
                                                        <td>
                                                            <input type="hidden" name="reqKerusakanId[]" value="<?=$reqKerusakanId?>">
                                                            <input type="text" name="reqKerusakanInventaris[]" class="easyui-validatebox" style="width: 100%;" value="<?=$reqKerusakanInventaris?>">
                                                        </td>
                                                        <td>
                                                            <input type="text" name="reqKerusakanNama[]" class="easyui-validatebox" style="width: 100%;" value="<?=$reqKerusakanNama?>">
                                                        </td>
                                                        <td>
                                                            <input type="text" name="reqKerusakanPosisi[]" class="easyui-validatebox" style="width: 100%;" value="<?=$reqKerusakanPosisi?>">
                                                        </td>
                                                        <td>
                                                            <textarea name="reqKerusakanUraian[]" class="easyui-validatebox" style="width: 100%;"><?=$reqKerusakanUraian?></textarea>
                                                        </td>        
                                                        <td>
                                                            <textarea name="reqKerusakanPenyebab[]" class="easyui-validatebox" style="width: 100%;"><?=$reqKerusakanPenyebab?></textarea>
                                                        </td>        
                                                        <td>
                                                            <textarea name="reqKerusakanPenanggulangan[]" class="easyui-validatebox" style="width: 100%;"><?=$reqKerusakanPenanggulangan?></textarea>
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-danger btn-sm pull-right" type="button" onClick="deleteRowSave(this,<?=$reqKerusakanId?>)"><i class="fa fa-trash-o"></i></button>
                                                        </td>
                                                    </tr>
                                                <?}?>
                                            </tbody>
                                        </table>
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
                                                    <input id ="reqFile" name="reqLinkFile[]" type="file" maxlength="10" class="multi maxsize-10240" value="" />
                                                    <?
                                                    $surat_masuk_attachment = new SuratMasuk();
                                                    $surat_masuk_attachment->selectByParamsAttachment(array("A.SURAT_MASUK_ID" => (int)$reqId));
                                                    while ($surat_masuk_attachment->nextRow()) {
                                                        $attach_id= $surat_masuk_attachment->getField("SURAT_MASUK_ATTACHMENT_ID");
                                                    ?>
                                                        
                                                        <div class="MultiFile-label">
                                                            <input type="hidden" name="reqLinkFileTemp[]" value="<?= $surat_masuk_attachment->getField("ATTACHMENT") ?>" />
                                                            <input type="hidden" name="reqLinkFileTempNama[]" value="<?= $surat_masuk_attachment->getField("NAMA") ?>" />
                                                            <input type="hidden" name="reqLinkFileTempTipe[]" value="<?= $surat_masuk_attachment->getField("TIPE") ?>" />
                                                            <input type="hidden" name="reqLinkFileTempSize[]" value="<?= $surat_masuk_attachment->getField("UKURAN") ?>" />
                                                            <a class="MultiFile-remove"><i class="fa fa-times-circle" onclick="infolampiran('min'); $(this).parent().parent().remove();"></i></a>
            
                                                            <?
                                                            $arrexcept= array("xlsx", "xls", "doc", "docx", "ppt", "pptx", "txt");
                                                            if(in_array(strtolower($surat_masuk_attachment->getField("TIPE")), $arrexcept))
                                                            {
                                                            ?>
                                                            <?= $surat_masuk_attachment->getField("NAMA") ?>
                                                            <a onClick="down('<?=$attach_id?>')" >
                                                                <i style="cursor: pointer;" class="fa fa-download" ></i>
                                                            </a>
                                                            <?
                                                            }
                                                            else
                                                            {
                                                            ?>
                                                            <?= $surat_masuk_attachment->getField("NAMA") ?>
                                                            <a onClick="parent.openAdd('<?= base_url() . "uploads/" . $reqId . "/" . $surat_masuk_attachment->getField("ATTACHMENT") ?>')" >
                                                                <i style="cursor: pointer;" class="fa fa-eye" ></i>
                                                            </a>
                                                            |
                                                            <a onClick="down('<?=$attach_id?>')" >
                                                                <i style="cursor: pointer;" class="fa fa-download" ></i>
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
                                <tr>
                                    <td>URL Google Drive <span class="text-danger"></td>
                                    <td>:</td>
                                    <td>
                                        <input type="text" name="reqLampiranDrive" id="reqLampiranDrive" class="easyui-validatebox" value="<?=$surat_masuk->getField("LAMPIRAN_DRIVE");?>" style="width: 900px;"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Referensi</td>
                                    <td>:</td>
                                    <td>
                                        <a onClick="openreference()">Referensi <i class="fa fa-external-link" aria-hidden="true"></i></a>
                                        <?
                                        if(!empty($reqId))
                                        {
                                            $smref= new SuratMasukReference();
                                            $smref->selectByParams(array("A.SURAT_MASUK_ID"=>$reqId));
                                        }
                                        ?>
                                        <div id="infodetilreference">
                                            <ol class='list-unstyled'>
                                            <?
                                            if(!empty($reqId))
                                            {
                                                while($smref->nextRow())
                                                {
                                                    $infosmrefid= $smref->getField("SM_REF_ID");
                                                    $infosmrefnomor= $smref->getField("NOMOR");
                                            ?>
                                                <li><i class='fa fa-times-circle' onclick='$(this).parent().remove();'></i><input type='hidden' name='reqSmRefMultiId[]' id='reqSmRefMultiId<?=$infosmrefid?>' value='<?=$infosmrefid?>' /> <?=$infosmrefnomor?></li>
                                            <?
                                                }
                                            }
                                            ?>
                                            </ol>
                                        </div>
                                    </td>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    
                    <div id="tab-atribut" class="tab-pane fade">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th colspan="3" class="padding-0">
                                        <div class="judul-sub">
                                            Detail Atribut Surat
                                        </div>
                                    </th>
                                </tr>
                                <tr>
                                    <td>Lampiran</td>
                                    <td>:</td>
                                    <td><label id="infolampiran"><?= $suratmasukinfo->JUMLAH_LAMPIRAN ?></label></td>
                                </tr>
                                <tr>
                                    <td>Butuh Aksi Balasan <span class="text-danger">*</span></td>
                                    <td>:</td>
                                    <td>
                                        <input type="text" name="reqButuhAksiId" class="easyui-combobox" id="reqButuhAksiId" data-options="width:'300', panelHeight:'100',editable:false, valueField:'id',textField:'text',url:'combo_json/comboaksibalasan',prompt:'Tentukan Butuh Aksi Balasan...'" value="<?=$reqButuhAksiId?>" required />
                                    </td>
                                </tr>
                                <tr>
                                    <td>Tenggat Waktu</td>
                                    <td>:</td>
                                    <td><input type="text" id="reqTenggatWaktu" class="easyui-datebox textbox form-control" name="reqTenggatWaktu" value="<?= $reqTanggalKegiatan ?>" style="width:110px" /></td>
                                </tr>
                                <tr>
                                    <td>Sifat <span class="text-danger">*</span></td>
                                    <td>:</td>
                                    <td>
                                        <input type="text" name="reqSifatNaskah" class="easyui-combobox" id="reqTipe" data-options="width:'300', panelHeight:'100',editable:false, valueField:'id',textField:'text',url:'web/sifat_surat_json/combo',prompt:'Tentukan sifat naskah...'" value="<?=$reqSifatNaskah?>" required />
                                    </td>
                                </tr>
                                <tr>
                                    <td>Tahun</td>
                                    <td>:</td>
                                    <td><?=$reqTahun?></td>
                                </tr>
                                <tr>
                                    <td>Nomor Surat</td>
                                    <td>:</td>
                                    <td><?=$reqNoSurat?></td>
                                </tr>
                                
                                <!-- SUB JUDUL -->
                                <tr>
                                    <th colspan="3" class="padding-0">
                                        <div class="judul-sub">
                                            Data Penandatangan Surat
                                        </div>
                                    </th>
                                </tr>
                                
                                <tr>
                                    <td>Kode Direksi/SM </td>
                                    <td>:</td>
                                    <td><label id="infopenandatangankode"><?=$infopenandatangankode?></label></td>
                                </tr>
                                <tr>
                                    <td>Nama Pejabat</td>
                                    <td>:</td>
                                    <td><label id="infopenandatangannamapejabat"><?=$infopenandatangannamapejabat?></label></td>
                                </tr>
                                <tr>
                                    <td>NUP</td>
                                    <td>:</td>
                                    <td><label id="infopenandatangannip"><?=$infopenandatangannip?></label></td>
                                </tr>
                                <tr>
                                    <td>Direktorat</td>
                                    <td>:</td>
                                    <td><label id="infopenandatangandirektorat"><?=$infopenandatangandirektorat?></label></td>
                                </tr>
                                <tr>
                                    <td>Sub Direktorat</td>
                                    <td>:</td>
                                    <td><label id="infopenandatangansubdirektorat"><?=$infopenandatangansubdirektorat?></label></td>
                                </tr>
                                <tr>
                                    <td>Kode Jabatan</td>
                                    <td>:</td>
                                    <td>-</td>
                                </tr>
                                <tr>
                                    <td>Atas Nama</td>
                                    <td>:</td>
                                    <td>
                                        <input type="hidden" name="reqAnStatus" id="reqAnStatus" value="<?=$reqAnStatus?>" />
                                        <input type="checkbox" id="reqAnStatusChecked" <? if($reqAnStatus == "1") echo "checked"?> class="form-control" style="display:inline-block; width: 30px; float:left;" >
                                        <input name="reqAnStatusNama" id="reqAnStatusNama" class="easyui-validatebox textbox form-control validatebox-text col-md-4" value="<?=$reqAnStatusNama?>" style="width:200px;">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Lokasi Kedudukan</td>
                                    <td>:</td>
                                    <td><label id="infopenandatanganlokasi"><?=$infopenandatanganlokasi?></label></td>
                                </tr>
                                <tr>
                                    <td>Kode Unit</td>
                                    <td>:</td>
                                    <td><label id="infopenandatangankodeunit"><?=$infopenandatangankodeunit?></label></td>
                                </tr>
                                <tr>
                                    <td>Kota</td>
                                    <td>:</td>
                                    <td><label id="infopenandatangankota"><?=$infopenandatangankota?></label></td>
                                </tr>
                            </thead>
                        </table>
                                
                        <table class="table" style="display: none;">
                            <thead>
                                <!-- SUB JUDUL -->
                                <tr>
                                    <th colspan="3" class="padding-0">
                                        <div class="judul-sub">
                                            Data Lama
                                        </div>
                                    </th>
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
                                
                            </thead>
                        </table>
                    </div>
                </div>

                <input type="hidden" name="reqArsipId" id="reqArsipId" value="<?=$reqArsipId?>">
                <input type="hidden" name="reqJenisTTD" class="easyui-combobox" id="reqJenisTTD" value="<?=$reqJenisTTD?>" />
                <input type="hidden" name="reqJenisNaskah" class="easyui-combobox" id="reqJenisNaskah" value="<?=$reqJenisNaskah?>" />
                <input type="hidden" name="reqKlasifikasiId" class="easyui-combotree" id="reqKlasifikasiId" value="<?=$reqKlasifikasiId?>" />
                <input type="hidden" name="reqPrioritasSuratId" class="easyui-combotree" id="reqPrioritasSuratId" value="<?=$reqPrioritasSuratId?>" />

                <input type="hidden" name="reqAsalSuratAlamat" id="reqAsalSuratAlamat" class="easyui-validatebox textbox" readonly value="<?=$this->CABANG?>" style="width:100%" />
                <input type="hidden" name="reqKdLevel" id="reqKdLevel" class="easyui-validatebox textbox" readonly value="<?=$reqKdLevel?>" style="width:100%" />
                <input type="hidden" name="reqTipeNaskah" id="reqTipeNaskah" class="easyui-validatebox textbox" readonly value="<?=$reqTipeNaskah?>" style="width:100%" />
                <input type="hidden" name="reqPenerbitNomor" id="reqPenerbitNomor" class="easyui-validatebox textbox" readonly value="<?=$reqPenerbitNomor?>" style="width:100%" />

                <input type="hidden" name="reqTarget" value="INTERNAL" />
                <input type="hidden" name="reqPenyampaianSurat" value="APLIKASI" />
                <input type="hidden" name="refDisposisiId" value="<?= $refDisposisiId ?>" />
                <input type="hidden" name="reqJenisTujuan" value="<?= $reqJenisTujuan ?>" />
                <input type="hidden" name="reqId" value="<?= $reqId ?>" />
                <input type="hidden" name="reqMode" value="<?= $reqMode ?>" />
                <input type="hidden" name="reqStatusSurat" id="reqStatusSurat" value="<?= $reqStatusSurat ?>" />
                <input type="hidden" name="reqUserAtasanId" id="reqUserAtasanId" value="<?=$reqUserAtasanId?>" />
                <input type="hidden" name="reqInfoLog" id="reqInfoLog" />
                <input type="hidden" name="reqStatusApprove" id="reqStatusApprove" value="<?=$reqStatusApprove?>" />
            </form>
        </div>
    </div>

<style id="compiled-css" type="text/css">

</style>

<!--// plugin-specific resources //-->
<script src='lib/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script>
<script src='lib/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
<script src='lib/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>

<script>
    function rekursivemultisatuanKerja(index, JENIS, multiinfoid, multiinfonama, IDFIELD) 
    {
        urllink= "app/loadUrl/template/tujuan_surat";
        method= "POST";
        batas= multiinfoid.length;
        if(index < batas)
        {
            SATUAN_KERJA_ID= multiinfoid[index];
            NAMA= multiinfonama[index];

            var rv = true;
            if(JENIS == "PARAF")
            {
                $('[name^=reqTujuanSuratParafValidasi]').each(function() {

                    if ($(this).val() == SATUAN_KERJA_ID) {
                        rv = false;
                        return false;
                    }

                });
            }
            else
            {
                $('[name^=reqTujuanSuratValidasi]').each(function() {

                    if ($(this).val() == SATUAN_KERJA_ID) {
                        rv = false;
                        return false;
                    }

                });
            }

            if (rv == true) 
            {
                $.ajax({
                    url: urllink,
                    method: method,
                    data: {
                        reqJenis: JENIS,
                        reqSatkerId: SATUAN_KERJA_ID,
                        reqNama: NAMA
                    },
                    // dataType: 'json',
                    success: function (response) {
                        $("#"+IDFIELD).append(response);
                        setinfovalidasi();

                        index= parseInt(index) + 1;
                        rekursivemultisatuanKerja(index, JENIS, multiinfoid, multiinfonama, IDFIELD);
                    },
                    error: function (response) {
                    },
                    complete: function () {
                    }
                });
            }
            else
            {
                index= parseInt(index) + 1;
                rekursivemultisatuanKerja(index, JENIS, multiinfoid, multiinfonama, IDFIELD);
            }
        }
    }

    function addmultisatuanKerja(JENIS, multiinfoid, multiinfonama, IDFIELD) 
    {
        batas= multiinfoid.length;
        // console.log(batas);

        if(batas > 0)
        {
            rekursivemultisatuanKerja(0, JENIS, multiinfoid, multiinfonama, IDFIELD);
        }
    }

    function addSatuanKerja(JENIS, SATUAN_KERJA_ID, NAMA, IDFIELD) {
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
                    $("#"+IDFIELD).append(data);
                    setinfovalidasi();
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
    }

    $(function(){
        setinfovalidasi();
    });

    function setundefined(val)
    {
        if(typeof val == "undefined")
            val= "";
        return val;
    }

    function setinfovalidasi()
    {
        reqIdd= '<?=$reqId?>';
        // tambahan khusus
        reqPerihal= reqSatuanKerjaIdTujuan= reqSatuanKerjaId= reqSatuanKerjaIdParaf=
        reqKeterangan= 
        reqButuhAksiId= reqSifatNaskah= "";

        reqPerihal= $("#reqPerihal").val();
        reqUserAtasanId= setundefined($("#reqUserAtasanId").val());
        reqSatuanKerjaId= setundefined($("#reqSatuanKerjaId").combobox("getValue"));
        reqSatuanKerjaIdTujuan= setundefined($('[name^=reqSatuanKerjaIdTujuan]').val());
        reqButuhAksiId= setundefined($("#reqButuhAksiId").combobox("getValue"));
        reqSifatNaskah= setundefined($("#reqTipe").combobox("getValue"));

        $("#tab-informasi-danger").hide();
        $("#tab-informasi-success").show();
        if(((reqPerihal == "" || reqSatuanKerjaId == "" || reqUserAtasanId == "") && reqIdd=="") || (reqSatuanKerjaIdTujuan == "" || reqPerihal == "" || reqSatuanKerjaId == "" || reqUserAtasanId == "") && reqIdd!="")
        {
            $("#tab-informasi-danger").show();
            $("#tab-informasi-success").hide();
        }

        $("#tab-isi-danger").hide();
        $("#tab-isi-success").show();
        
        $("#tab-atribut-danger").hide();
        $("#tab-atribut-success").show();
        if(reqButuhAksiId == "" || reqSifatNaskah == "")
        {
            $("#tab-atribut-danger").show();
            $("#tab-atribut-success").hide();
        }
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

        infopesandetil= "";
        if (reqStatusSurat == "REVISI")
        {
            // var pesan = "Kembalikan surat ke staff anda?";
            infopesandetil= " Kembalikan surat ke staff anda?";
        }

        // tambahan khusus
        if (reqStatusSurat == "PARAF")
        {
            infopesandetil= " Paraf naskah?";
            // var pesan = "Paraf naskah?";
        }

        // tambahan khusus
        reqPerihal= reqSatuanKerjaIdTujuan= reqSatuanKerjaId= reqSatuanKerjaIdParaf=
        reqKeterangan= 
        reqButuhAksiId= reqSifatNaskah= 
        reqvalidasibaru= "";

        reqPerihal= $("#reqPerihal").val();
        reqSatuanKerjaId= setundefined($("#reqSatuanKerjaId").combobox("getValue"));
        reqSatuanKerjaIdTujuan= setundefined($('[name^=reqSatuanKerjaIdTujuan]').val());
        reqButuhAksiId= setundefined($("#reqButuhAksiId").combobox("getValue"));
        reqSifatNaskah= setundefined($("#reqTipe").combobox("getValue"));

        reqvalidasibaru= "1";
        if (reqPerihal!="" && reqSatuanKerjaId!="" && reqStatusSurat=='DRAFT')
        {
            $("#kepadaa").hide();
            $("#tab-informasi-danger").hide();
            $("#tab-informasi-success").show();
        }

        if(((reqPerihal=="" || reqSatuanKerjaId=="") && reqStatusSurat=='DRAFT') || (reqPerihal=="" || reqSatuanKerjaId=="" || reqSatuanKerjaIdTujuan=="") && reqStatusSurat=='UBAHDATADRAFTPARAF')
        {
            reqvalidasibaru= "";
            $('a[href="#tab-informasi"]').tab('show');
            if (reqStatusSurat=='UBAHDATADRAFTPARAF') 
            {
                $("#kepadaa").show();
                $("#tab-informasi-danger").show();
                $("#tab-informasi-success").hide();
            }
        }
                
        if((reqButuhAksiId == "" || reqSifatNaskah == "") && reqvalidasibaru == "1")
        {
            reqvalidasibaru= "";
            $('a[href="#tab-atribut"]').tab('show');
        }

        if ($(this).form('enableValidation').form('validate') == false || reqvalidasibaru == "") {
            if ($("#button i").attr("class") == "fa fa-gears")
            {
                $("#button").click();
            }

            return false;
        }

        if (reqStatusSurat == "POSTING" || reqStatusSurat == "PARAF" || reqStatusSurat == "REVISI" || reqStatusSurat == "UBAHDATAPOSTING" || reqStatusSurat == "UBAHDATADRAFTPARAF")
        {
            <?
            if($infohakakses == "SEKRETARIS")
            {
            ?>
                infocontent= '<form action="" class="formName">' +
                '<div class="form-group">' +
                '<label>Perlu approval '+$("#reqSatuanKerjaId").combotree('getText')+'!</label>' +
                '<select id="infoStatusApprove"><option value="">Perlu</option><option value="1">Tidak Perlu</option></select>' +
                '</div>' +
                '<div class="form-group">' +
                '<label>Isi komentar jika ingin mengirim dokumen ini!</label>' +
                '<input type="text" placeholder="Tuliskan komentar anda..." class="name form-control" required />' +
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
                '<input type="hidden" id="infoStatusApprove" value="" />' +
                '<input type="text" placeholder="Tuliskan komentar anda..." class="name form-control" required />' +
                '</div>' +
                '</form>';
            <?
            }
            ?>

            $.confirm({
                title: 'Komentar'+infopesandetil,
                content: '' + infocontent
                ,
                buttons: {
                    formSubmit: {
                        text: 'OK',
                        btnClass: 'btn-blue',
                        action: function () {
                            var name = this.$content.find('.name').val();
                            if (!name) {
                                $.alert('<span style= color:red>Komentar wajib diisi !</span>');
                                return false;
                            }
                            $("#reqInfoLog").val(name);

                            <?
                            if ($reqId == "" || ($reqStatusSurat == "DRAFT" && !empty($reqId)) )
                            {
                            ?>
                                infoStatusApprove= $("#infoStatusApprove").val();
                                $("#reqStatusApprove").val(infoStatusApprove);
                            <?
                            }
                            ?>
                            // return false;

                            setsimpan();
                        }
                    },
                    cancel: function () {
                        //close
                    },
                },
                onContentReady: function () {
                    // you can bind to the form
                    var jc = this;
                    this.$content.find('form').on('submit', function (e) { // if the user submits the form by pressing enter in the field.
                        e.preventDefault();
                        jc.$$formSubmit.trigger('click'); // reference the button and click it
                    });
                }
            });
        }
        else if (reqStatusSurat == "UBAHDATAPARAF" || reqStatusSurat == "UBAHDATAREVISI" || reqStatusSurat == "UBAHDATAVALIDASI")
        {
            setsimpan();
        }
        else
        {
            $.messager.confirm('Konfirmasi', pesan, function(r) {
                if (r) {
                    setsimpan();
                }
            });
        }

    }

    function setsimpan()
    {
        // console.log($('#ff').serializeArray())
        // return;
        $('#ff').form('submit', {
            url: 'web/surat_masuk_json/add',
            onSubmit: function() {

                if ($(this).form('enableValidation').form('validate') == false) {
                    if ($("#button i").attr("class") == "fa fa-gears")
                    {
                        $("#button").click();
                    }

                    return false;
                }

                // tambahan khusus
                if(reqvalidasibaru == "")
                {
                    return false;
                }

                infoisirevisi= "";

                // tambahan khusus
                if (reqStatusSurat == "REVISI" || reqStatusSurat == "PARAF") {
                    if ($("#reqRevisi").val().trim() == "") {
                        if ($("#button i").attr("class") == "fa fa-gears")
                            $("#button").click();

                        return false;
                    }
                }

                if(infoisirevisi == "1")
                {
                    if ($("#reqRevisi").val().trim() == "") {
                        if ($("#button i").attr("class") == "fa fa-gears")
                            $("#button").click();

                        return false;
                    }
                }

                if($(this).form('enableValidation').form('validate'))
                {
                    var win = $.messager.progress({title:'Proses simpan data', msg:'Proses simpan data...'});
                }

                return $(this).form('enableValidation').form('validate');
            },
            success: function(data) {
                $.messager.progress('close');
                // console.log(data);return false;
                // alert(data);return false;

                arrData = data.split("-");

                if (arrData[0] == "0") {
                    $.messager.alert('Info', arrData[1], 'info');
                    return;
                }

                // HIT JUMLAH SURAT
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
                    $.messager.alertLink('Info', arrData[1], 'info', "app/loadUrl/main/nota_dinas_add?reqMode=<?=$reqLinkMode?>&reqId=<?=$reqId?>");
                }

            }
        });
    }

    function submitPreview() {
        parent.openAdd('app/loadUrl/report/template/?reqId=<?= $reqId ?>');
    }

    function deleteForm()
    {
        $.messager.confirm('Konfirmasi','Yakin menghapus draft ini ?',function(r){
            if (r){
                var jqxhr = $.get( 'web/surat_masuk_json/delete?reqId=<?=$reqId?>', function() {
                    document.location.href="main/index/newdraft";
                })
                .done(function() {
                    document.location.href="main/index/newdraft";
                })
                .fail(function() {
                    alert( "error" );
                });                             
            }
        });
    }

    function clearForm() {
        $('#ff').form('clear');
    }

    function setsurat()
    {
        document.location.href = 'main/index/<?=$reqLinkMode?>?reqMode=<?=$reqLinkModeDetil?>&reqId=<?=$reqId?>';
    }

    function down(attach_id)
    {
        window.open("down?reqId=<?=$reqId?>&reqAttachId="+attach_id, 'Cetak');
    }
</script>
<!-- TODO: Missing CoffeeScript 2 -->
<script type="text/javascript">
    $(document).ready(function() {
        reqIdd='<?=$reqId?>';
        if (reqIdd=='') 
        {
            $("#kepadaa").hide();
        } 
        else 
        {
            $("#kepadaa").show();
        }

        /* JIKA MEMO INTERN TIDAK WAJIB */
        <?
        if(!empty($reqJenisNaskahNama))
        {
            ?>
            if ("<?=$reqJenisNaskahNama?>" == 'Laporan Kerusakan Inventaris') {
                $('#reqInfoKlasifikasi').hide();
                $('#reqKlasifikasiId').combotree({required: false});
                $('#reqKlasifikasiId').removeClass('validatebox-invalid');
            } 
            else 
            {
                $('#reqInfoKlasifikasi').show();
                $('#reqKlasifikasiId').combotree({required: true});
            }
            <?
        }
        ?>
    });

    function loadNode(cc)
    {
        var values= cc.combotree('getValues');
        $("#reqSatuanKerjaInfoParaf").val(values);
        var textdata= cc.combotree('getText');
        infotextdata= textdata.split(",");

        infodetilparaf= "<ol>";
        for(i=0; i < infotextdata.length; i++)
        {
            if(infotextdata[i] !== "")
            {
                infodetilparaf+= "<li>"+infotextdata[i]+"</li>";
            }
        }
        infodetilparaf+= "</ol>";

        $("#infodetilparaf").empty();
        $("#infodetilparaf").html(infodetilparaf);
    }

    function clickNode(cc, id)
    {
        var infoid= [];
        infoid= String($("#reqSatuanKerjaInfoParaf").val()).split(",");
        var elementRow= infoid.indexOf(id);
        if(parseInt(elementRow) >= 0)
        {
          infoid.splice(elementRow, 1);
        }

        var node = cc.combotree('tree').tree('find', id);
        if (node.checked)
        {
            if(infoid[0] == "")
                infoid[0]= String(node.id);
            else
            infoid.push(String(node.id));
        }
        cc.combotree('setValues', infoid);

        var values= cc.combotree('getValues');
        $("#reqSatuanKerjaInfoParaf").val(values);
        var textdata= cc.combotree('getText');
        infotextdata= textdata.split(",");

        infodetilparaf= "<ol>";
        for(i=0; i < infotextdata.length; i++)
        {
            if(infotextdata[i] !== "")
            {
                infodetilparaf+= "<li>"+infotextdata[i]+"</li>";
            }
        }
        infodetilparaf+= "</ol>";

        $("#infodetilparaf").empty();
        $("#infodetilparaf").html(infodetilparaf);
    }

    $(function(){
        infolampiran("");
    });

    $(function(){
      $('#reqFile').change(function(){
        var url = $(this).val();
        console.log(url);
        var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
        if (input.files && input.files[0]&& (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) 
         {
            var reader = new FileReader();

            reader.onload = function (e) {
               $('#img').attr('src', e.target.result);
            }
           reader.readAsDataURL(input.files[0]);
        }
        else
        {
          $('#img').attr('src', '/assets/no_preview.png');
        }
      });

    });

    function openreference()
    {
        reqCheckId= "";
        $('input[id^="reqSmRefMultiId"]').each(function(){
            var id= $(this).attr('id');
            id= id.replace("reqSmRefMultiId", "");
            // $(this).prop('checked', true);
            // console.log(id);
            if(reqCheckId == "")
                reqCheckId= id;
            else
                reqCheckId= reqCheckId+","+id;
        });

        top.openAdd('app/loadUrl/main/referensi_lookup?reqCheckId='+reqCheckId);
    }

    function setreference(reqPilihId)
    {
        $.ajax({
            url: "web/surat_masuk_json/inforeference/?reqId="+reqPilihId,
            method: 'GET',
            success: function (response) {
                response= JSON.parse(response);

                infodetilparaf= "<ol class='list-unstyled'>";
                $.each(response, function( key, value) {
                    infodetilparaf+= "<li><i class='fa fa-times-circle' onclick='$(this).parent().remove();'></i><input type='hidden' name='reqSmRefMultiId[]' id='reqSmRefMultiId"+value.SURAT_MASUK_ID+"' value='"+value.SURAT_MASUK_ID+"' /> "+value.NOMOR+"</li>";
                });
                infodetilparaf+= "</ol>";

                $("#infodetilreference").empty();
                $("#infodetilreference").html(infodetilparaf);
            },
            error: function (response) {
                // geni.unblock('body');
                // swal('', response.responseJSON.message, 'error');
            },
            complete: function () {
            }
        });
    }

    function infolampiran(mode)
    {
        // alert("");
        var infolampiran= "";
        infolampiran= setundefined($("#infolampiran").text());
        if(infolampiran == "")
            infolampiran= 0;

        if(mode == "plus")
            infolampiran= parseInt(infolampiran) + 1;
        else if(mode == "min" && parseInt(infolampiran) > 0)
            infolampiran= parseInt(infolampiran) - 1;

        $("#infolampiran").text(infolampiran);
    }

    function setatasnama(info)
    {
        $("#reqAnStatus").val("");
        $("#reqAnStatusNama").attr("readonly", true);
        if($("#reqAnStatusChecked").prop('checked')) 
        {
            $("#reqAnStatus").val("1");
            $("#reqAnStatusNama").attr("readonly", false);
        }
        else
        {
            if(info == 2)
            {
                $("#reqAnStatusNama").val("");
            }
        }
    }

    $(function(){
        setatasnama(1);
        $("#reqAnStatusChecked").click(function () {
            setatasnama(2);
        });
    });

    function setagenda()
    {
        document.location.href = 'main/index/nota_dinas_lihat?reqMode=nota_dinas_add&reqId=<?=$reqId?>';
    }
</script>

<!-- jQUERY CONFIRM MASTER -->
<link rel="stylesheet" type="text/css" href="lib/jquery-confirm-master/css/jquery-confirm.css"/>
<script type="text/javascript" src="lib/jquery-confirm-master/js/jquery-confirm.js"></script>

<!-- WYSIWYG EDITOR -->
<link rel="stylesheet" href="lib/froala_editor_2.9.8/css/froala_editor.css">
<link rel="stylesheet" href="lib/froala_editor_2.9.8/css/froala_style.css">
<link rel="stylesheet" href="lib/froala_editor_2.9.8/css/plugins/code_view.css">
<link rel="stylesheet" href="lib/froala_editor_2.9.8/css/plugins/draggable.css">
<link rel="stylesheet" href="lib/froala_editor_2.9.8/css/plugins/colors.css">
<link rel="stylesheet" href="lib/froala_editor_2.9.8/css/plugins/emoticons.css">
<link rel="stylesheet" href="lib/froala_editor_2.9.8/css/plugins/image_manager.css">
<link rel="stylesheet" href="lib/froala_editor_2.9.8/css/plugins/image.css">
<link rel="stylesheet" href="lib/froala_editor_2.9.8/css/plugins/line_breaker.css">
<link rel="stylesheet" href="lib/froala_editor_2.9.8/css/plugins/table.css">
<link rel="stylesheet" href="lib/froala_editor_2.9.8/css/plugins/char_counter.css">
<link rel="stylesheet" href="lib/froala_editor_2.9.8/css/plugins/video.css">
<link rel="stylesheet" href="lib/froala_editor_2.9.8/css/plugins/fullscreen.css">
<link rel="stylesheet" href="lib/froala_editor_2.9.8/css/plugins/file.css">
<link rel="stylesheet" href="lib/froala_editor_2.9.8/css/plugins/quick_insert.css">
<link rel="stylesheet" href="lib/froala_editor_2.9.8/css/plugins/help.css">
<link rel="stylesheet" href="lib/froala_editor_2.9.8/css/third_party/spell_checker.css">
<link rel="stylesheet" href="lib/froala_editor_2.9.8/css/plugins/special_characters.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/codemirror.min.css">

<style>
.ss {
    display: none;
}
</style>

<script type="text/javascript" src="lib/froala_editor_2.9.8/js/froala_editor.min.js" ></script>
<script type="text/javascript" src="lib/froala_editor_2.9.8/js/plugins/align.min.js"></script>
<script type="text/javascript" src="lib/froala_editor_2.9.8/js/plugins/char_counter.min.js"></script>
<script type="text/javascript" src="lib/froala_editor_2.9.8/js/plugins/code_beautifier.min.js"></script>
<script type="text/javascript" src="lib/froala_editor_2.9.8/js/plugins/code_view.min.js"></script>
<script type="text/javascript" src="lib/froala_editor_2.9.8/js/plugins/colors.min.js"></script>
<script type="text/javascript" src="lib/froala_editor_2.9.8/js/plugins/draggable.min.js"></script>
<script type="text/javascript" src="lib/froala_editor_2.9.8/js/plugins/emoticons.min.js"></script>
<script type="text/javascript" src="lib/froala_editor_2.9.8/js/plugins/entities.min.js"></script>
<script type="text/javascript" src="lib/froala_editor_2.9.8/js/plugins/file.min.js"></script>
<script type="text/javascript" src="lib/froala_editor_2.9.8/js/plugins/font_size.min.js"></script>
<script type="text/javascript" src="lib/froala_editor_2.9.8/js/plugins/font_family.min.js"></script>
<script type="text/javascript" src="lib/froala_editor_2.9.8/js/plugins/fullscreen.min.js"></script>
<script type="text/javascript" src="lib/froala_editor_2.9.8/js/plugins/image.min.js"></script>
<script type="text/javascript" src="lib/froala_editor_2.9.8/js/plugins/image_manager.min.js"></script>
<script type="text/javascript" src="lib/froala_editor_2.9.8/js/plugins/line_breaker.min.js"></script>
<script type="text/javascript" src="lib/froala_editor_2.9.8/js/plugins/inline_style.min.js"></script>
<script type="text/javascript" src="lib/froala_editor_2.9.8/js/plugins/link.min.js"></script>
<script type="text/javascript" src="lib/froala_editor_2.9.8/js/plugins/lists.min.js"></script>
<script type="text/javascript" src="lib/froala_editor_2.9.8/js/plugins/paragraph_format.min.js"></script>
<script type="text/javascript" src="lib/froala_editor_2.9.8/js/plugins/paragraph_style.min.js"></script>
<script type="text/javascript" src="lib/froala_editor_2.9.8/js/plugins/quick_insert.min.js"></script>
<script type="text/javascript" src="lib/froala_editor_2.9.8/js/plugins/quote.min.js"></script>
<script type="text/javascript" src="lib/froala_editor_2.9.8/js/plugins/table.min.js"></script>
<script type="text/javascript" src="lib/froala_editor_2.9.8/js/plugins/save.min.js"></script>
<script type="text/javascript" src="lib/froala_editor_2.9.8/js/plugins/url.min.js"></script>
<script type="text/javascript" src="lib/froala_editor_2.9.8/js/plugins/video.min.js"></script>
<script type="text/javascript" src="lib/froala_editor_2.9.8/js/plugins/help.min.js"></script>
<script type="text/javascript" src="lib/froala_editor_2.9.8/js/plugins/print.min.js"></script>
<script type="text/javascript" src="lib/froala_editor_2.9.8/js/third_party/spell_checker.min.js"></script>
<script type="text/javascript" src="lib/froala_editor_2.9.8/js/plugins/special_characters.min.js"></script>
<script type="text/javascript" src="lib/froala_editor_2.9.8/js/plugins/word_paste.min.js"></script>

<script>
    function addRow(array) {
        // console.log("masuk");
        test = `<tr>
        <td><input type="hidden" name="reqKerusakanId[]"><input type="text" name="reqKerusakanInventaris[]" class="easyui-validatebox" style="width: 100%;"></td>
        <td><input type="text" name="reqKerusakanNama[]" class="easyui-validatebox" style="width: 100%;"></td>
        <td><input type="text" name="reqKerusakanPosisi[]" class="easyui-validatebox" style="width: 100%;"></td>
        <td><textarea name="reqKerusakanUraian[]" class="easyui-validatebox" style="width: 100%;"></textarea></td>        
        <td><textarea name="reqKerusakanPenyebab[]" class="easyui-validatebox" style="width: 100%;"></textarea></td>        
        <td><textarea name="reqKerusakanPenanggulangan[]" class="easyui-validatebox" style="width: 100%;"></textarea></td>
        <td><button class="btn btn-danger btn-sm pull-right" type="button" onClick="deleteRow(this)"><i class="fa fa-trash-o"></i></button></td>
        </tr>`;
        $("#tbodyObyek").append(test);
    }

    function deleteRow(ctl) {
        console.log('xxxx');
        // $(ctl).parents("tr").remove();
        $(ctl).closest("tr").remove();
    }

    function deleteRowSave(ctl,id)
    {
        $.messager.confirm('Konfirmasi','Yakin menghapus draft ini ?',function(r){
            if (r){
                var jqxhr = $.get( 'web/surat_masuk_json/deleteRow?reqId='+id, function() {
                    deleteRow(ctl);
                })
                .done(function() {
                    deleteRow(ctl);
                })
                .fail(function() {
                    alert( "error" );
                });                             
            }
        });
    }
</script>
<link rel="stylesheet" href="css/gaya-surat.css" type="text/css">
