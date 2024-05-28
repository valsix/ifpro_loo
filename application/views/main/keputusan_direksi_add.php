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

$arrpasal=[];
// echo $reqId;exit;
$refDisposisiId = $this->input->get("refDisposisiId");

$reqIdDraft = $reqId;
if ($reqId == "") {
    $reqSifatNaskah= "Biasa";
    $reqButuhAksiId= 2;
    $reqJenisNaskah= "8";
    //$reqJenisNaskahNama= "Nota Dinas";
    $reqJenisNaskahNama= "Keputusan Direksi";
    $reqJenisTTD = "QRCODE";

    $reqMode = "insert";
    $reqStatusSurat = "DRAFT";
    $reqIsMeeting   = "T";
    //$reqPenyampaianSurat = "APLIKASI";
    // $reqParaf = "''";
    $reqParaf = "";

    if(!empty($reqReplyId))
    {
        $reqKepada = $disposisi->getreplyjson(array("SURAT_MASUK_ID" => $reqReplyId, "STATUS_DISPOSISI" => "TUJUAN"));
        $reqSatuanKerjaId= $this->SATUAN_KERJA_ID_ASAL;
        $reqPerintah = $disposisi->getreplyjson(array("SURAT_MASUK_ID" => $reqReplyId, "STATUS_DISPOSISI" => "PERINTAH_TUJUAN"));
        $reqSatuanKerjaId= $this->SATUAN_KERJA_ID_ASAL;
    }
    else
    {
        $reqKepada = "[]";
        $reqPerintah = "[]";
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
    $reqDasar = $surat_masuk->getField("DASAR");
    $reqIsiPerintah = $surat_masuk->getField("ISI_PERINTAH");
    $reqLainLain = $surat_masuk->getField("LAIN_LAIN");

    $reqMenimbang=  $surat_masuk->getField("MENIMBANG");
    $reqMengingat= $surat_masuk->getField("MENGINGAT");
    $reqMenetapkan=  $surat_masuk->getField("MENETAPKAN");
    $reqPertama= $surat_masuk->getField("PERTAMA");

     $reqBagianNama= $surat_masuk->getField("NAMA_PASAL");
     if ($reqBagianNama ==''){
        $reqBagianNama=='PASAL';
     }


    $arraypasalfield= array("KEDUA", "KETIGA", "KEEMPAT", "KELIMA", "KEENAM", "KETUJUH", "KEDELAPAN", "KESEMBILAN", "KESEPULUH", "KESEBELAS", "KEDUABELAS", "KETIGABELAS", "KEEMPATBELAS", "KELIMABELAS", "KEENAMBELAS", "KETUJUHBELAS", "KEDELAPANBELAS", "KESEMBILANBELAS", "KEDUAPULUH", "KEDUAPULUHSATU", "KEDUAPULUHDUA", "KEDUAPULUHTIGA", "KEDUAPULUHEMPAT", "KEDUAPULUHLIMA");

    for($ipasal=0; $ipasal < count($arraypasalfield); $ipasal++)
    {
        $valpasal= $surat_masuk->getField($arraypasalfield[$ipasal]);
        if(!empty($valpasal))
        {
            array_push($arrpasal, $surat_masuk->getField($arraypasalfield[$ipasal]));
        }
    }

    // $reqKedua= $surat_masuk->getField("KEDUA");
    // $reqKetiga= $surat_masuk->getField("KETIGA");
    // $reqKeempat=$surat_masuk->getField("KEEMPAT");
    // $reqKelima= $surat_masuk->getField("KELIMA");
    // $reqKeenam= $surat_masuk->getField("KEENAM");
    // $reqKetujuh= $surat_masuk->getField("KETUJUH");
    // $reqKedelapan= $surat_masuk->getField("KEDELAPAN");
    // $reqKesembilan= $surat_masuk->getField("KESEMBILAN");
    // $reqKesepuluh= $surat_masuk->getField("KESEPULUH");

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
            // redirect("main/index/approval");
        }
        else
        {
            // $set= new SuratMasuk();
            // $set->selectByParams(array("A.SURAT_MASUK_ID"=>$reqIdDraft));
            // $set->firstRow();
            // // echo $set->query;exit;
            // $infoperihal= $set->getField("PERIHAL");
            // $infojenisnaskahid= $set->getField("JENIS_NASKAH_ID");
            // unset($set);

            // $arrlink= $suratmasukinfo->infolinkdetil($infojenisnaskahid);
            // $infolinkdetil= $arrlink["linkstatusdetil"];

            // redirect("main/index/".$infolinkdetil."/?reqId=".$reqIdDraft);
            // redirect("main/index/newdraft");
        }
    }

    // tambahan khusus
    if(!empty($reqId))
    {
        $reqParaf = $surat_masuk_paraf->getParaf(array("SURAT_MASUK_ID" => $reqId));
        if($reqParaf == "'''")
            $reqParaf= "";

        $reqKepada = $disposisi->getJson(array("SURAT_MASUK_ID" => $reqId, "STATUS_DISPOSISI" => "TUJUAN"));
        $reqPerintah = $disposisi->getJson(array("SURAT_MASUK_ID" => $reqId, "STATUS_DISPOSISI" => "PERINTAH_TUJUAN"));

        // echo $reqKepada;exit;

        $reqTembusan = $disposisi->getJson(array("SURAT_MASUK_ID" => $reqId, "STATUS_DISPOSISI" => "TEMBUSAN"));

        $reqKepadaKelompok = $disposisi_kelompok->getJson(array("SURAT_MASUK_ID" => $reqId, "STATUS_DISPOSISI" => "TUJUAN"));
        $reqPerintahKelompok = $disposisi_kelompok->getJson(array("SURAT_MASUK_ID" => $reqId, "STATUS_DISPOSISI" => "PERINTAH_TUJUAN"));
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

        // print_r($arrreturnhirarki);exit;
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
    // print_r($arrinfokepada);exit();
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
    // print_r($arrinfotembusan);exit();
}

// tambahan khusus
$reqKelompokJabatan = "";
if(!empty($reqId))
{
    $suratmasukinfo->getInfo($reqId, "INTERNAL");
    $reqKelompokJabatan = $suratmasukinfo->KELOMPOK_JABATAN;
}

// tambahan khusus
// if ($reqStatusSurat == "VALIDASI" && $reqUserId != $this->ID) 
// {
//     if($reqUserAtasanId == $this->ID && $reqKelompokJabatan == "DIREKSI")
//     {
//         if(!empty($reqApprovalDate))
//         {
//             redirect("main/index/approval");
//         }
//     }
// }

$checkparafid= "";
// tambahan khusus
if (!empty($reqId))
{
    if($reqStatusSurat == "DRAFT" || $reqStatusSurat == "REVISI"){}
    elseif($reqStatusSurat == "PARAF" || $reqStatusSurat == "VALIDASI")
    {
        $set= new SuratMasukParaf();
        $infonextpemaraf= $set->getNextParaf(" AND COALESCE(NULLIF(A.STATUS_PARAF, ''), NULL) IS NULL AND A.SURAT_MASUK_ID = ".$reqIdDraft);
        // echo $infonextpemaraf;exit;
        // echo $set->query;exit;

        $set= new SuratMasuk();
        $statement= " AND ((A.USER_ATASAN_ID = '".$this->ID."' AND  A.APPROVAL_DATE IS NULL) OR (A.USER_ATASAN_ID = '".$this->USER_GROUP.$this->ID."' AND A.APPROVAL_DATE IS NOT NULL )) AND A.STATUS_SURAT IN ('PARAF', 'VALIDASI') AND A.SURAT_MASUK_ID = ".$reqIdDraft;
        if(!empty($infonextpemaraf))
        {
            $statement.= " AND COALESCE(A.NEXT_URUT,1) = ".$infonextpemaraf;
        }
        $sOrder= "";
        // $set->selectByParamsPersetujuan(array(), -1, -1, $this->ID, $statement, $sOrder);
        // $set->selectByParamsNewPersetujuan(array(), -1, -1, $this->ID, $this->USER_GROUP, $statement, $sOrder, $reqIdDraft);
        $satuankerjaganti= $this->SATUAN_KERJA_ID_ASAL;
        $set->selectByParamsNewPersetujuan(array(), -1, -1, $this->ID, $this->USER_GROUP, $statement, $sOrder, $reqIdDraft, $satuankerjaganti);
        // echo $set->query;exit;
        $set->firstRow();
        $checkparafid= $set->getField("SURAT_MASUK_ID");
        $checknextpemaraf= $set->getField("NEXT_URUT");
        $checkstatusbantu= $set->getField("STATUS_BANTU");
        $chekvalidasi= "";
        if(isset($checknextpemaraf))
            $chekvalidasi= "validasi";
        // echo $chekvalidasi."-".$checknextpemaraf."--".$infonextpemaraf;exit;

        if (empty($checkparafid) && empty($reqId))
        {
            redirect("main/index/newdraft");
        }
        else
        {
            if($checknextpemaraf == 0){}
            elseif((!empty($reqLinkMode) && $infonextpemaraf !== $checknextpemaraf) || empty($checknextpemaraf))
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
                echo "main/index/".$infolinkdetil."/?reqId=".$reqIdDraft;exit;

                redirect("main/index/".$infolinkdetil."/?reqId=".$reqIdDraft);
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

$totalpasal= count($arrpasal);
if($totalpasal == 0){
    $totalpasal= 1;
}
else{
    $totalpasal++;
}

// print_r($arrpasal);exit;
$nomorpasal= $totalpasal;
// $nomorpasal= 2;
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
    <div class="judul-halaman bg-course">
        <span><img src="images/icon-course.png"></span> Keputusan Direksi
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

            // tambahan khusus
            // if ($reqStatusSurat == "DRAFT" && !empty($reqId)) {
            ?>
                <!-- <button class="btn btn-primary btn-sm pull-right" type="button" onClick="submitForm('POSTING')"><i class="fa fa-paper-plane"></i> Kirim</button> -->
            <?
            // }
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

            if ($reqAksi == "koreksi") {
            } 
            else {
                if ($reqStatusSurat == "VALIDASI") {
            ?>
                    <!-- <button class="btn btn-primary btn-sm pull-right" type="button" onClick="submitForm('POSTING')"><i class="fa fa-paper-plane"></i> Kirim</button> -->
                <?
                } elseif ($reqId != "" && $reqUserAtasanId == $this->ID) {
                ?>
                    <!-- tutup dl -->
                    <!-- <button class="btn btn-primary btn-sm pull-right" type="button" onClick="approvalSurat()"><i class="fa fa-paper-plane"></i> Kirim</button> -->
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
                <!-- tutup dulu -->
                <!-- <button class="btn btn-primary btn-sm pull-right" type="button" onClick="approvalSurat()"><i class="fa fa-check-square-o"></i> Setujui</button> -->
            <?
                }
                else
                {
            ?>
                <!-- tutup dl -->
                <!-- <button class="btn btn-primary btn-sm pull-right" type="button" onClick="submitForm('POSTING')"><i class="fa fa-paper-plane"></i> Kirim</button> -->
            <?
                }
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
                                    <!-- <a onClick="top.openAdd('app/loadUrl/main/satuan_kerja_tujuan_lookup/?reqJenis=TUJUAN&reqJenisSurat=INTERNAL&reqIdField=divTujuanSurat')">Tujuan <i class="fa fa-users"></i></a> -->
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
                                            <!-- 
                                            <?
                                            $arrKepada = json_decode($reqKepada);
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
                                             -->
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            
                            <tr style="display: none">
                                <td>Tembusan</td>
                                <td>:</td>
                                <td>
                                    <!-- <a onClick="top.openAdd('app/loadUrl/main/satuan_kerja_tujuan_lookup/?reqJenis=TEMBUSAN&reqJenisSurat=INTERNAL&reqIdField=divTembusanSurat')">Tembusan <i class="fa fa-users" aria-hidden="true"></i></a> -->
                                    <a onClick="top.openAdd('app/loadUrl/main/satuan_kerja_tujuan_multi_lookup/?reqJenis=TEMBUSAN&reqJenisSurat=INTERNAL&reqIdField=divTembusanSurat')">Tembusan <i class="fa fa-users" aria-hidden="true"></i></a>
                                    <div class="inner" id="divTembusanSurat">
                                        <!--<div class="item" onClick="return confirm('Are you sure to delete?')">Lorem ipsu.pdf <i class="fa fa-times-circle"></i></div>-->
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
                                            <!-- 
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
                                                <div class="item">TEMBUSAN:<?= $value->NAMA_KELOMPOK ?>
                                                    <i class="fa fa-times-circle" onclick="$(this).parent().remove();"></i>
                                                    <input type="hidden" name="reqTujuanSuratValidasiKelompok" value="<?= $value->SATUAN_KERJA_KELOMPOK_ID ?>">
                                                    <input type="hidden" name="reqSatuanKerjaIdTembusan[]" value="<?= $value->SATUAN_KERJA_KELOMPOK_ID ?>">
                                                </div>
                                            <?
                                            }
                                            ?>
                                             -->
                                        </div>
        
                                    </div>
                                </td>
                            </tr>
                            <!-- <tr>
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
                            </tr> -->
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
                                    // $aksibutton= "1";
                                    // $checkparafid= 1;
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
                                    <!-- <input type="text" id="reqSatuanKerjaId" class="easyui-combobox" name="reqSatuanKerjaId" data-options="width:'500', panelHeight:'60', valueField:'id', textField:'text', url:'web/satuan_kerja_json/combo_level/?reqId=<?=$reqKdLevel?>',prompt:'Tentukan dari...',
                                        onSelect: function(rec){
                                            

                                            var url = 'web/permohonan_nomor_json/combo_statement/?reqSatuanKerjaId='+rec.SATUAN_KERJA_ID+'&reqJenisNaskahId='+$('#reqJenisNaskah').combobox('getValue')+'&reqTipeNaskah=INTERNAL';
                                            $('#reqPermohonanNomorId').combobox('reload', url);
                                            $('#reqPermohonanNomorId').combobox('setValue', '');
                                            $('#reqAsalSuratInstansi').val(rec.SATUAN_KERJA);
                                            var url = 'web/satuan_kerja_json/combo_paraf/?reqId='+rec.SATUAN_KERJA_ID;
                                            $('#reqSatuanKerjaIdParaf').combotree('reload', url);
                                            $('#reqSatuanKerjaIdParaf').combotree('setValue', '');
                                            setinfovalidasi();
                                        }" value="<?=$reqSatuanKerjaId?>" required="required" /> -->
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

                                    <!-- 
                                    onCheck: function(node, checked){
                                        clickNode($('#reqSatuanKerjaIdParaf'), node.id);
                                    }, 
                                    clickNode($('#reqSatuanKerjaIdParaf'));
                                    -->
                                    <!-- <input type="text" class="easyui-combotree" id="reqSatuanKerjaInfoParaf" data-options="
                                    onLoadSuccess: function (row, data) {
                                        loadNode($('#reqSatuanKerjaInfoParaf'));
                                    },
                                    onClick: function(node){
                                        clickNode($('#reqSatuanKerjaInfoParaf'), node.id);
                                    }
                                    , width:'500'
                                    , panelHeight:'120'
                                    , valueField:'id'
                                    , textField:'text'
                                    , url:'web/satuan_kerja_json/combo_paraf/?reqId=<?=$reqSatuanKerjaId?>'
                                    , value:[<?=$reqParaf?>]
                                    , multiple:true
                                    , multiSort:false
                                    , cascadeCheck: false
                                    , prompt:'Tentukan pemaraf naskah...'," value="" 
                                    <?
                                    /* JIKA BUKAN PEJABAT WAJIB NGISI */
                                    if ($this->KD_LEVEL_PEJABAT == "") 
                                    {
                                        if ($this->USER_GROUP == "SEKRETARIS"){} 
                                        else 
                                        {
                                        // required="required" 
                                    ?> 
                                    <?
                                        }
                                    }
                                    ?> 
                                    />
                                    <input type="hidden" id="reqSatuanKerjaIdParaf" name="reqSatuanKerjaIdParaf" /> -->



                                    <!-- <span style="display: <?=$infodisplay?>;">
                                        <input type="text" class="easyui-combotree" id="reqSatuanKerjaIdParaf" name="reqSatuanKerjaIdParaf[]" data-options="
                                        onLoadSuccess: function (row, data) {
                                            loadNode($('#reqSatuanKerjaIdParaf'));
                                        },
                                        onClick: function(node){
                                            clickNode($('#reqSatuanKerjaIdParaf'), node.id);
                                        }
                                        , width:'500'
                                        , panelHeight:'120'
                                        , valueField:'id'
                                        , textField:'text'
                                        , url:'web/satuan_kerja_json/combo_paraf/?reqId=<?=$reqSatuanKerjaId?>'
                                        , value:[<?=$reqParaf?>]
                                        , multiple:true
                                        , multiSort:false
                                        , cascadeCheck: false
                                        , prompt:'Tentukan pemaraf naskah...'," value="" 
                                        />
                                    </span>
                                    <input type="hidden" id="reqSatuanKerjaInfoParaf" />
                                    <div id="infodetilparaf"></div> -->
                                </td>
                            </tr>
                            <!-- <tr id="reqInfoKlasifikasi">
                                <td>Pola Klasifikasi <span class="text-danger">*</span></td>
                                <td>:</td>
                                <td>
                                    <input type="text" name="reqKlasifikasiId" class="easyui-combotree" id="reqKlasifikasiId" 
                                    data-options="
                                    onClick: function(rec){
                                        setinfovalidasi();
                                    }
                                    , width:'300',valueField:'id',textField:'text',url:'web/klasifikasi_json/combotree',prompt:'Tentukan klasifikasi naskah...'" value="<?= $reqKlasifikasiId ?>" required />
                                </td>
                            </tr> -->
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
                                <td>Menimbang <span class="text-danger">*</span></td>
                                <td>:</td>
                                <td>
                                    <textarea placeholder="isi pesan..." id="reqMenimbang" name="reqMenimbang"><?=$reqMenimbang?></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td>Mengingat <span class="text-danger">*</span></td>
                                <td>:</td>
                                <td>
                                    <textarea placeholder="isi pesan..." id="reqMengingat" name="reqMengingat"><?=$reqMengingat?></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td><b>MEMUTUSKAN </b><span class="text-danger">*</span></td>
                            </tr>
                            <tr>
                                <td>Menetapkan <span class="text-danger">*</span></td>
                                <td>:</td>
                                <td>
                                    <textarea placeholder="isi pesan..." id="reqMenetapkan" name="reqMenetapkan"><?=$reqMenetapkan?></textarea>
                                </td>
                            </tr>
                             <tr>
                                <td>Nama Bagian <span class="text-danger">*</span></td>
                                <td>:</td>
                                <td>
                                    <select name="NamaBagian" id="NamaBagian" onchange="selectNamaBagian()">
                                      <option value="BAB" <?if($reqBagianNama=="BAB"){?>selected<?}?>>BAB</option>
                                      <option value="PASAL"<?if($reqBagianNama=="PASAL"){?>selected<?}?>>PASAL</option>
                                    </select>
                                </td>
                            </tr>
                           <tr>
                                <td>
                                    <a onClick="tambahpasal()"><i class="fa fa-plus-circle fa-lg"></i></a>
                                    <label class='infonomorbap' <?if($reqBagianNama=='PASAL'){?>style="display: none;"<?}?>>BAB I <span class="text-danger">*</span></label>
                                    <label class='infonomorpasal' <?if($reqBagianNama=='BAB' || $reqBagianNama==''){?>style="display: none;"<?}?>>PASAL 1 <span class="text-danger">*</span></label>
                                </td>
                                <td>:</td>
                                <td>
                                    <textarea placeholder="isi pesan..." id="reqPertama" name="reqPertama"><?=$reqPertama?></textarea>
                                </td>
                            </tr>
                            <?
                            if(!empty($arrpasal))
                            {
                                $infonomor= $nomorpasal;
                                if($infonomor != "1") $infonomor= 2;
                                $idrand= rand();
                                for($ipasal=0; $ipasal < count($arrpasal); $ipasal++)
                                {
                                    $infonomorpasal= infonomor($infonomor,$reqJenisNaskah);
                                    $infonomorbap= infonomor($infonomor, $reqJenisNaskah);
                            ?>
                            <tr>
                                <td>
                                    <a onclick="$(this).parent().parent().remove(); hapuspasal()"><i class="fa fa-trash fa-lg"></i></a>
                                    <label class="infonomorpasal" <?if($reqBagianNama=='BAB' || $reqBagianNama==''){?>style="display: none;"<?}?>>
                                        <?=$infonomorpasal?>
                                    </label>
                                    <label class="infonomorbap" <?if($reqBagianNama=='PASAL'){?>style="display: none;"<?}?>>
                                        <?=$infonomorbap?>
                                    </label>
                                </td>
                                <td>:</td>
                                <td>
                                    <textarea placeholder="isi pesan..." class="froalaeditor" name="reqPasal[]" id="reqPasal<?=$idrand?>"><?=$arrpasal[$ipasal]?></textarea>
                                </td>
                            </tr>
                            <?
                                $infonomor++;
                                }
                            }
                            ?>
                            <tr>
                               <td id="tbodypasal" colspan="3"></td>
                            </tr>
                            <!-- <tr>
                                <td>Pasal 2 </td>
                                <td>:</td>
                                <td>
                                    <textarea placeholder="isi pesan..." id="reqKedua" name="reqKedua"><?=$reqKedua?></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td>Pasal 3 </td>
                                <td>:</td>
                                <td>
                                    <textarea placeholder="isi pesan..." id="reqKetiga" name="reqKetiga"><?=$reqKetiga?></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td>Pasal 4 </td>
                                <td>:</td>
                                <td>
                                    <textarea placeholder="isi pesan..." id="reqKeempat" name="reqKeempat"><?=$reqKeempat?></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td>Pasal 5 </td>
                                <td>:</td>
                                <td>
                                    <textarea placeholder="isi pesan..." id="reqKelima" name="reqKelima"><?=$reqKelima?></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td>Pasal 6 </td>
                                <td>:</td>
                                <td>
                                    <textarea placeholder="isi pesan..." id="reqKeenam" name="reqKeenam"><?=$reqKeenam?></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td>Pasal 7 </td>
                                <td>:</td>
                                <td>
                                    <textarea placeholder="isi pesan..." id="reqKetujuh" name="reqKetujuh"><?=$reqKetujuh?></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td>Pasal 8 </td>
                                <td>:</td>
                                <td>
                                    <textarea placeholder="isi pesan..." id="reqKedelapan" name="reqKedelapan"><?=$reqKedelapan?></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td>Pasal 9 </td>
                                <td>:</td>
                                <td>
                                    <textarea placeholder="isi pesan..." id="reqKesembilan" name="reqKesembilan"><?=$reqKesembilan?></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td>Pasal 10 </td>
                                <td>:</td>
                                <td>
                                    <textarea placeholder="isi pesan..." id="reqKesepuluh" name="reqKesepuluh"><?=$reqKesepuluh?></textarea>
                                </td>
                            </tr> -->

                            <tr>
                                <td>
                                    Lampiran
                                </td>
                                <td>:</td>
                                <td>
                                    <div class="kotak-dokumen">
                                        <div class="kontak">
                                            <div class="inner-lampiran">
                                                <!-- <input id= "reqFile" name="reqLinkFile[]" type="file" maxlength="5" class="multi maxsize-10240" accept="xlsx|xls|doc|docx|ppt|pptx|txt|pdf|jpg|jpeg|png|gif" value="" /> -->
                                                <input id= "reqFile" name="reqLinkFile[]" type="file" maxlength="6" class="multi maxsize-10240"  value="" />
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
                                    <!-- <select name="reqAtasNamaPilih" id="reqAtasNamaPilih" class="pull-left" style="margin-right: 10px;">
                                        <option value=""> - </option>
                                        <option value="an">A.n</option>
                                    </select>
                                    
                                    <div id="mydiv" style="display:none">
                                        <input type="text" name="reqAtasNama" class="easyui-validatebox" value="<?=$reqAtasNama?>">
                                    </div>
                                    
                                    <script type="text/javascript">
                                    $('#reqAtasNamaPilih').change(function () {
                                        if ($("#reqAtasNamaPilih").val() == "an") {
                                            $('#mydiv').show();
                                        } else $("#mydiv").hide();
                                    });
                                    </script> -->
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
                            
                            <!------------------------------------------------------>
                            
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
            <div style="display: none;">

                <input type="hidden" name="reqArsipId" id="reqArsipId" value="<?=$reqArsipId?>">
                <input type="hidden" name="reqJenisTTD" class="easyui-combobox" id="reqJenisTTD" value="<?=$reqJenisTTD?>" />
                <input type="hidden" name="reqJenisNaskah" class="easyui-combobox" id="reqJenisNaskah" value="<?=$reqJenisNaskah?>" />
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
            </div>
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
        console.log(IDFIELD);
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
                    //$("#divTujuanSurat").append(data);
                    //$("#divTembusanSurat").append(data);
                    
                    $("#"+IDFIELD).append(data);
                    setinfovalidasi();
                });
        }
    }

    $(function(){
        // setreference('4,1,12');

        infolampiran("");

        $("#reqFile").change(function(e) {
            infolampiran("plus");
        });

        // one tambahan validasi
        setinfovalidasi();
    });

    function infolampiran(mode)
    {
        // alert("");
        var infolampiran= "";
        infolampiran= setundefined($("#infolampiran").text());
        if(infolampiran == "")
            infolampiran= 0;

        if(mode == "plus"){
            infolampiran= parseInt(infolampiran) + 1;}
        else if(mode == "min" && parseInt(infolampiran) > 0){
            infolampiran= parseInt(infolampiran) - 1;
        }

        $("#infolampiran").text(infolampiran);
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

    $(function(){
        // setinfopenandatangan();
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
        reqPerihal= reqSatuanKerjaIdTujuan= reqSatuanKerjaId= reqSatuanKerjaIdParaf= reqKlasifikasiId=
        reqKeterangan= 
        reqButuhAksiId= reqSifatNaskah= reqDasar = reqIsiPerintah = reqLainLain = "";

        reqPerihal= $("#reqPerihal").val();
        reqUserAtasanId= setundefined($("#reqUserAtasanId").val());
        reqSatuanKerjaId= setundefined($("#reqSatuanKerjaId").combobox("getValue"));
        // reqSatuanKerjaIdParaf= setundefined($("#reqSatuanKerjaIdParaf").combobox("getValue"));
        // reqSatuanKerjaIdParaf= setundefined($("#reqSatuanKerjaIdParaf").val());
        // reqKlasifikasiId= setundefined($("#reqKlasifikasiId").combobox("getValue"));
        reqKlasifikasiId= "lewati";
        reqSatuanKerjaIdTujuan= setundefined($('[name^=reqSatuanKerjaIdTujuan]').val());

        reqMenimbang= jQuery($('textarea#reqMenimbang').froalaEditor('html.get')).text();
        reqMengingat= jQuery($('textarea#reqMengingat').froalaEditor('html.get')).text();
        reqMenetapkan= jQuery($('textarea#reqMenetapkan').froalaEditor('html.get')).text();
        reqPertama= jQuery($('textarea#reqPertama').froalaEditor('html.get')).text();

        reqButuhAksiId= setundefined($("#reqButuhAksiId").combobox("getValue"));
        reqSifatNaskah= setundefined($("#reqTipe").combobox("getValue"));

        $("#tab-informasi-danger").hide();
        $("#tab-informasi-success").show();
        // if(reqPerihal == "" || reqSatuanKerjaId == "" || reqSatuanKerjaIdParaf == "" || reqSatuanKerjaIdTujuan == "")
        if(((reqPerihal=="" || reqSatuanKerjaId=="" || reqUserAtasanId=="" || reqKlasifikasiId=="")  && reqIdd=="") || ((reqPerihal=="" || reqSatuanKerjaId=="" || reqSatuanKerjaIdTujuan=="" || reqUserAtasanId=="" || reqKlasifikasiId=="")  && reqIdd!=""))
        {
            $("#tab-informasi-danger").show();
            $("#tab-informasi-success").hide();
        }

        $("#tab-isi-danger").hide();
        $("#tab-isi-success").show();
        if(reqMenimbang == "Powered by Froala Editor" || reqMenimbang == "" || reqMengingat == "Powered by Froala Editor" || reqMengingat == "" || reqMenetapkan == "Powered by Froala Editor" || reqMenetapkan == "" || reqPertama == "Powered by Froala Editor" || reqPertama == "")
        {
            $("#tab-isi-danger").show();
            $("#tab-isi-success").hide();
        }

        // for (i=1; i < tinyMCE.editors.length; i++){
        //     var content = tinyMCE.editors[i].getContent();
        //     // console.log('Editor-Id(' + tinyMCE.editors[i].id + '):' + content);
        //     if (tinyMCE.editors[i].id=="reqPemesanSatuanKerjaIsi" || tinyMCE.editors[i].id=="reqKedua" || tinyMCE.editors[i].id=="reqKetiga" || tinyMCE.editors[i].id=="reqKeempat" || tinyMCE.editors[i].id=="reqKelima" || tinyMCE.editors[i].id=="reqKeenam" || tinyMCE.editors[i].id=="reqKetujuh" || tinyMCE.editors[i].id=="reqKedelapan" || tinyMCE.editors[i].id=="reqKesembilan" || tinyMCE.editors[i].id=="reqKesepuluh") {} 
        //     else 
        //     {
        //         if(content == "")
        //         {
        //             $("#tab-isi-danger").show();
        //             $("#tab-isi-success").hide();
        //         }
        //     }
        // }
        
        $("#tab-atribut-danger").hide();
        $("#tab-atribut-success").show();
        if(reqButuhAksiId == "" )
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

        // tambahan khusus
        reqPerihal= reqSatuanKerjaIdTujuan= reqSatuanKerjaId= reqSatuanKerjaIdParaf= reqKlasifikasiId=
        reqKeterangan= 
        reqButuhAksiId= reqSifatNaskah= 
        reqvalidasibaru= reqDasar= "";

        reqPerihal= $("#reqPerihal").val();
        reqSatuanKerjaId= setundefined($("#reqSatuanKerjaId").combobox("getValue"));
        // reqSatuanKerjaIdParaf= setundefined($("#reqSatuanKerjaIdParaf").combobox("getValue"));
        // reqSatuanKerjaIdParaf= setundefined($("#reqSatuanKerjaIdParaf").val());
        // reqKlasifikasiId= setundefined($("#reqKlasifikasiId").combobox("getValue"));
        reqKlasifikasiId= "lewati";
        reqSatuanKerjaIdTujuan= setundefined($('[name^=reqSatuanKerjaIdTujuan]').val());

        reqMenimbang= jQuery($('textarea#reqMenimbang').froalaEditor('html.get')).text();
        reqMengingat= jQuery($('textarea#reqMengingat').froalaEditor('html.get')).text();
        reqMenetapkan= jQuery($('textarea#reqMenetapkan').froalaEditor('html.get')).text();
        reqPertama= jQuery($('textarea#reqPertama').froalaEditor('html.get')).text();

        reqButuhAksiId= setundefined($("#reqButuhAksiId").combobox("getValue"));
        reqSifatNaskah= setundefined($("#reqTipe").combobox("getValue"));

        reqvalidasibaru= "1";
        // if(reqPerihal == "" || reqSatuanKerjaId == "" || reqSatuanKerjaIdParaf == "" || reqSatuanKerjaIdTujuan == "")
        if (reqPerihal!="" && reqSatuanKerjaId!="" && reqKlasifikasiId!="" && reqStatusSurat=='DRAFT')
        {
            $("#kepadaa").hide();
            $("#tab-informasi-danger").hide();
            $("#tab-informasi-success").show();
        }

        if(((reqPerihal=="" || reqSatuanKerjaId=="" || reqKlasifikasiId=="" || reqSatuanKerjaIdTujuan=="") && reqStatusSurat=='DRAFT') || (reqPerihal=="" || reqSatuanKerjaId=="" || reqSatuanKerjaIdTujuan=="" || reqKlasifikasiId=="") && reqStatusSurat=='UBAHDATADRAFTPARAF')
        {
            reqvalidasibaru= "";
            $('a[href="#tab-informasi"]').tab('show');
            // one tambahan validasi
            if (reqStatusSurat=='DRAFT' || reqStatusSurat=='UBAHDATADRAFTPARAF') 
            {
                $("#kepadaa").show();
                $("#tab-informasi-danger").show();
                $("#tab-informasi-success").hide();
            }
        }
        
        if(((reqMenimbang=="Powered by Froala Editor" || reqMenimbang=="") && reqvalidasibaru == "1") || ((reqMengingat=="Powered by Froala Editor" || reqMengingat=="") && reqvalidasibaru == "1") || ((reqMenetapkan=="Powered by Froala Editor" || reqMenetapkan=="") && reqvalidasibaru == "1") || ((reqPertama=="Powered by Froala Editor" || reqPertama=="") && reqvalidasibaru == "1"))
        {
            reqvalidasibaru= "";
            $('a[href="#tab-isi"]').tab('show');
        }

        // for (i=0; i < tinyMCE.editors.length; i++){
        //     var content = tinyMCE.editors[i].getContent();
        //     // alert('Editor-Id(' + tinyMCE.editors[i].id + '):' + content);
        //     if (tinyMCE.editors[i].id=="reqPemesanSatuanKerjaIsi" || tinyMCE.editors[i].id=="reqKedua" || tinyMCE.editors[i].id=="reqKetiga" || tinyMCE.editors[i].id=="reqKeempat" || tinyMCE.editors[i].id=="reqKelima" || tinyMCE.editors[i].id=="reqKeenam" || tinyMCE.editors[i].id=="reqKetujuh" || tinyMCE.editors[i].id=="reqKedelapan" || tinyMCE.editors[i].id=="reqKesembilan" || tinyMCE.editors[i].id=="reqKesepuluh") {} 
        //     else 
        //     {
        //         if(content == "" && reqvalidasibaru == "1")
        //         {
        //             reqvalidasibaru= "";
        //             $('a[href="#tab-isi"]').tab('show');
        //         }
        //     }
        // }
        
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
                    // infoisirevisi= "1";
                <?
                    }
                    else
                    {
                ?>
                    // infoisirevisi= "1";
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
                    $.messager.alertLink('Info', arrData[1], 'info', "app/loadUrl/main/surat_perintah_add?reqMode=<?=$reqLinkMode?>&reqId=<?=$reqId?>");
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
        // if ($('#reqJenisNaskah').combobox("getValue") == '5') {
        <?
        if(!empty($reqJenisNaskahNama))
        {
        ?>
        if ("<?=$reqJenisNaskahNama?>" == 'Nota Dinas') {
            $('#reqInfoKlasifikasi').hide();

            // $('#reqKlasifikasiId,#reqArsip').combotree({required: false});
            // $('#reqKlasifikasiId,#reqArsip').removeClass('validatebox-invalid');
            // $('#reqKlasifikasiId').combotree({required: false});
            // $('#reqKlasifikasiId').removeClass('validatebox-invalid');

            // $('#reqKlasifikasiId').combotree({
            //     required: false
            // });
            // $('#reqArsip').validatebox({
            //     required: false
            // });
        } 
        else 
        {
            // $('#reqInfoKlasifikasi').show();
            // $('#reqKlasifikasiId,#reqArsip').combotree({required: true});
            // $('#reqKlasifikasiId').combotree({required: true});

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

    function loadNode(cc)
    {
        var values= cc.combotree('getValues');
        // $("#reqSatuanKerjaIdParaf").val(values);
        $("#reqSatuanKerjaInfoParaf").val(values);
        var textdata= cc.combotree('getText');
        // console.log(textdata);
        infotextdata= textdata.split(",");
        // alert(infotextdata.length);

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
        // infoid= String($("#reqSatuanKerjaIdParaf").val()).split(",");
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
        // $("#reqSatuanKerjaIdParaf").val(values);
        $("#reqSatuanKerjaInfoParaf").val(values);
        var textdata= cc.combotree('getText');
        // console.log(textdata);
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
        // setreference('4,1,12');

        // one tambahan validasi
        setinfovalidasi();
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

    var arrnomorbap= ["BAB I <span class='text-danger'>*</span>", "BAB II", "BAB III", "BAB IV", "BAB V", "BAB VI", "BAB VII", "BAB VIII", "BAB IX", "BAB X", "BAB XI", "BAB XII", "BAB XIII", "BAB XIV", "BAB XV", "BAB XVI", "BAB XVII", "BAB XVIII", "BAB XIX", "BAB XX", "BAB XXI", "BAB XXII", "BAB XXIII", "BAB XXIV", "BAB XXV"];
    var arrnomorpasal= ["PASAL 1 <span class='text-danger'>*</span>", "PASAL 2", "PASAL 3", "PASAL 4", "PASAL 5", "PASAL 6", "PASAL 7", "PASAL 8", "PASAL 9", "PASAL 10", "PASAL 11", "PASAL 12", "PASAL 13", "PASAL 14", "PASAL 15", "PASAL 16", "PASAL 17", "PASAL 18", "PASAL 19", "PASAL 20", "PASAL 21", "PASAL 22", "PASAL 23", "PASAL 24", "PASAL 25"];
    var nomorpasal= "<?=$nomorpasal?>";
    
    function tambahpasal()
    {
        lastnomor= "";
        $('.infonomorpasal').each(function(index, obj){
            lastnomor= $(this).text();
        });

         var Filter = document.getElementById("NamaBagian").value;

        // var nomorpasal = arrnomorpasal.findIndex(pasaldata => pasaldata.value === lastnomor);
        // nomorpasal= arrnomorpasal.indexOf(lastnomor);
        // alert(nomorpasal);
        // console.log(nomorpasal);
        nomorpasal= parseInt(nomorpasal) + 1;
        // nomorpasal= parseInt(nomorpasal) + 1;
         $.get("app/loadUrl/main/keputusan_direksi_add_pasal?reqId=<?=$reqId?>&nomorpasal="+nomorpasal+"&NamaBagian="+Filter, function (data) {
            // console.log(data);
            $("#tbodypasal").append(data);
        }); 
    }

    function hapuspasal()
    {
        $('.infonomorpasal').each(function(index, obj){
            // console.log(index);
            // console.log(obj);
            // console.log($(this).html());
            $(this).html(arrnomorpasal[index]);
        });
        $('.infonomorbap').each(function(index, obj){
            // console.log(index);
            // console.log(obj);
            // console.log($(this).text());
            $(this).html(arrnomorbap[index]);
        });
        nomorpasal= parseInt(nomorpasal) - 1;
    }

    function setagenda()
    {
        document.location.href = 'main/index/keputusan_direksi_lihat?reqMode=keputusan_direksi_add&reqId=<?=$reqId?>';
    }
</script>

<script>
    $('textarea').focus(function() {
        //$(this).closest('.area-tulis-pesan').find('#button').show("slow");
    });
</script>

<!-- tiny MCE -->
<!--<script src="lib/tinyMCE/tinymce.min.js"></script>-->
<!-- , #reqKedua, #reqKetiga, #reqKeempat, #reqKelima, #reqKeenam, #reqKetujuh, #reqKedelapan, #reqKesembilan, #reqKesepuluh -->
<!-- <script src="lib/tinyMCE/tinymce.min.js"></script>
<script type="text/javascript">
    tinymce.init({
        selector: "#reqPemesanSatuanKerjaIsi, #reqMenimbang, #reqMengingat, #reqMenetapkan, #reqPertama, .tinyMCESimple",
        //height: 200,
        plugins: [
            "advlist autolink lists link image charmap print preview anchor",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime media table contextmenu paste"
        ],
        toolbar: "nonbreaking undo redo | styleselect | fontsizeselect fontselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",

        // setup: function(editor) {
        //     editor.on('keyup', function(e) {
        //         // console.log('edited. Contents: ' + editor.getContent());
        //         // check_submit();
        //         setinfovalidasi();
        //     });
        // }

        setup : function(ed) {
            // console.log(ed);return false;
            ed.on('keyup', function(e) {
                                // console.log('the content ', ed.getContent());return false;

                if($(ed).attr('id') == "reqMenimbang" || $(ed).attr('id') == "reqMengingat" || $(ed).attr('id') == "reqMenetapkan" || $(ed).attr('id') == "reqPertama")
                {
                    setinfovalidasi();
                }

                // if($(ed).attr('id') == "reqDasar" )
                // {
                //     setinfovalidasi();
                // }
                // console.log('the event object ', e);
                // console.log('the editor object ', ed);
                // console.log('the content ', ed.getContent());return false;
            });
            ed.on("init", function() {
                 if($(ed).attr('id') == "reqMenimbang" || $(ed).attr('id') == "reqMengingat" || $(ed).attr('id') == "reqMenetapkan" || $(ed).attr('id') == "reqPertama")
                {
                    setinfovalidasi();
                }
                // if($(ed).attr('id') == "reqDasar")
                // {
                //     setinfovalidasi();
                // }
            });
        }

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
</script> -->

<!-- jQUERY CONFIRM MASTER -->
<link rel="stylesheet" type="text/css" href="lib/jquery-confirm-master/css/jquery-confirm.css"/>
<script type="text/javascript" src="lib/jquery-confirm-master/js/jquery-confirm.js"></script>

<!-- </body>
</html>-->

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

    // event keyup
    $('textarea#reqMenimbang, textarea#reqMengingat, textarea#reqMenetapkan, textarea#reqPertama').on('froalaEditor.keyup', function (e, editor, keyupEvent) {
        setinfovalidasi();
    });

    // event like ready
    $('textarea#reqMenimbang, textarea#reqMengingat, textarea#reqMenetapkan, textarea#reqPertama').on('froalaEditor.html.set', function (e, editor) {
        setinfovalidasi();
    });

    // event paste after
    $('textarea#reqMenimbang, textarea#reqMengingat, textarea#reqMenetapkan, textarea#reqPertama').on('froalaEditor.paste.after', function (e, editor) {
        setinfovalidasi();
    });


    $(function(){
        $('textarea#reqMenimbang, textarea#reqMengingat, textarea#reqMenetapkan, textarea#reqPertama, .froalaeditor').froalaEditor({
            
            // key: "cC10A7C6B5B3C2C-8C2H2C4D4B6B2D2C4B1D1qkd1vwB-11pqD1J-7yA-16vtE-11otC-7yespzF4lb==",
            // key: "MA3A1A1G2H5A3nA16B10C7C6F2D4H4I2H3C8aD-17pfgki1aC8oilfdnC-7doiucf1jB1I-8r==",
            key: "qB1G1C1C1A1A2E7mD6F5F4E4E1B9D6C3C4A4g1Rd1Rb1MKF1AKUBWBOR==",
            
            imageUploadParam: 'image_param',
            
            // Set the image upload URL.
            imageUploadURL: '<?=base_url()?>upload',
            
            // Additional upload params.
            imageUploadParams: {id: 'my_editor'},
            
            // Set request type.
            imageUploadMethod: 'POST',
            
            // Set max image size to 5MB.
            imageMaxSize: 5 * 1024 * 1024,
            
            // Allow to upload PNG and JPG.
            imageAllowedTypes: ['jpeg', 'jpg', 'png'],
            
            events: {
                'image.beforeUpload': function (images) {
                console.log(images)
                // Return false if you want to stop the image upload.
                },
                'image.uploaded': function (response) {
                console.log(response)
                // Image was uploaded to the server.
                },
                'image.inserted': function ($img, response) {
                console.log($img, response)
                // Image was inserted in the editor.
                },
                'image.replaced': function ($img, response) {
                console.log($img, response)
                // Image was replaced in the editor.
                },
                'image.error': function (error, response) {
                console.log(error, response)
                // Bad link.
                // if (error.code == 1) { ... }
                
                // // No link in upload response.
                // else if (error.code == 2) { ... }
                
                // // Error during image upload.
                // else if (error.code == 3) { ... }
                
                // // Parsing response failed.
                // else if (error.code == 4) { ... }
                
                // // Image too text-large.
                // else if (error.code == 5) { ... }
                
                // // Invalid image type.
                // else if (error.code == 6) { ... }
                
                // // Image can be uploaded only to same domain in IE 8 and IE 9.
                // else if (error.code == 7) { ... }
                
                // Response contains the original server response to the request if available.
                }
            },
            tableCellStyles: {
                borderAll: "Border All",
                borderTop: "Border Top",
                borderBottom: "Border Bottom",
                borderLeft: "Border Left",
                borderRight: "Border Right",
            }
          
        })
    });

    $(document).ready(function() {
       // selectNamaBagian();
    });

    function selectNamaBagian() {
        var Filter = document.getElementById("NamaBagian").value;
        console.log(nomorpasal);

        if(Filter=='BAB')
        {
            $('.infonomorpasal').each(function(index, obj){
                // console.log(index);
                // console.log(obj);
                // console.log($(this).text());
                obj.style.display = "none";
            });
            $('.infonomorbap').each(function(index, obj){
                // console.log(index);
                // console.log(obj);
                // console.log($(this).text());
                obj.style.display = "";
            });
        }
        else
        {
            $('.infonomorpasal').each(function(index, obj){
                // console.log(index);
                // console.log(obj);
                // console.log($(this).text());
                obj.style.display = "";
            });
            $('.infonomorbap').each(function(index, obj){
                // console.log(index);
                // console.log(obj);
                // console.log($(this).text());
                obj.style.display = "none";
            });
        }
    }
</script>
<link rel="stylesheet" href="css/gaya-surat.css" type="text/css">
