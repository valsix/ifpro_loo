
<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model("TrPsm");
$this->load->model("TrPsmDetil");
$this->load->model("Combo");
$this->load->model("TrPsmParaf");
$this->load->model("SatuanKerja");

$this->load->model("TrLoi");
$this->load->model("TrLoiDetil");
$this->load->model("TrLoiParaf");

$this->load->model("TrLoo");
$this->load->model("TrLooDetil");
$this->load->model("TrLooParaf");

$reqId= $this->input->get("reqId");
$reqVmode= $this->input->get("reqMode");

$cekquery= $this->input->get("c");

$reqIdDraft= $reqId;
$arrdetil= $arrlokasi= [];
$arrloidetil= $arrloilokasi= $arrloodetil= $arrloolokasi= [];
if(empty($reqId))
{
    $reqId= -1;
    $reqMode= "insert";
    $reqPph= "1.11";
}
else
{
    $reqMode = "ubah";
    $set= new TrPsm();
    $set->selectByParams(array("A.TR_PSM_ID" => $reqId));
    $set->firstRow();
    
    $reqTrLoiId= $set->getField("TR_LOI_ID");
    $reqTrLooId= $set->getField("TR_LOO_ID");
    $reqProdukId= $set->getField("PRODUK_ID");
    $reqCustomerId= $set->getField("CUSTOMER_ID");
    $reqLokasiLooId= $set->getField("LOKASI_LOO_ID");
    $reqPph= $set->getField("PPH");
    $reqTotalLuasIndoor= $set->getField("TOTAL_LUAS_INDOOR");
    $reqTotalLuasOutdoor= $set->getField("TOTAL_LUAS_OUTDOOR");
    $reqTotalLuas= $set->getField("TOTAL_LUAS");
    $reqHargaIndoorSewa= $set->getField("HARGA_INDOOR_SEWA");
    $reqHargaOutdoorSewa= $set->getField("HARGA_OUTDOOR_SEWA");
    $reqHargaIndoorService= $set->getField("HARGA_INDOOR_SERVICE");
    $reqHargaOutdoorService= $set->getField("HARGA_OUTDOOR_SERVICE");
    $reqDp= $set->getField("DP");
    $reqPeriodeSewa= $set->getField("PERIODE_SEWA");
    $reqTop= $set->getField("TOP");
    $reqSewaBiayaSatuanUnit= $set->getField("SEWA_BIAYA_SATUAN_UNIT");
    $reqSewaBiayaSatuanService= $set->getField("SEWA_BIAYA_SATUAN_SERVICE");
    $reqSewaTotalBiayaUnit= $set->getField("SEWA_TOTAL_BIAYA_UNIT");
    $reqSewaBiayaPerBulanUnit= $set->getField("SEWA_BIAYA_PER_BULAN_UNIT");
    $reqSewaBiayaPerBulanService= $set->getField("SEWA_BIAYA_PER_BULAN_SERVICE");
    $reqSewaTotalBiayaService= $set->getField("SEWA_TOTAL_BIAYA_SERVICE");
    $reqTotalBiayaPerBulanNoPpn= $set->getField("TOTAL_BIAYA_PER_BULAN_NO_PPN");
    $reqTotalBiayaNoPpn= $set->getField("TOTAL_BIAYA_NO_PPN");
    $reqTotalBiayaPerBulanPpn= $set->getField("TOTAL_BIAYA_PER_BULAN_PPN");
    $reqTotalBiayaPpn= $set->getField("TOTAL_BIAYA_PPN");

    $reqSecurityDeposit= $set->getField("SECURITY_DEPOSIT");
    $reqFittingOut= $set->getField("FITTING_OUT");

    $reqTanggalAwal= dateToPageCheck($set->getField("INFO_TANGGAL_AWAL"));
    $reqTanggalAkhir= dateToPageCheck($set->getField("INFO_TANGGAL_AKHIR"));
    $reqPromotionLevy= $set->getField("PROMOTION_LEVY");

    $reqSatuanKerjaPengirimId= $set->getField("SATUAN_KERJA_PENGIRIM_ID");
    $reqUserPengirimId= $set->getField("USER_PENGIRIM_ID");
    $reqStatusData= $set->getField("STATUS_DATA");
    $reqUserId= $set->getField("USER_PEMBUAT_ID");
    // $reqxxx= $set->getField("USER_POSISI_PARAF_ID");

    $satuan_kerja= new SatuanKerja();
    $satuan_kerja->selectByParams(array(), -1, -1, " AND SATUAN_KERJA_ID = '".$reqSatuanKerjaPengirimId."'", " ORDER BY KODE_SO ASC ");
    $satuan_kerja->firstRow();
    // echo $satuan_kerja->query;exit;
    $infopenandatangankode= $satuan_kerja->getField("KODE_SURAT");
    $infopenandatangannamapejabat= $satuan_kerja->getField("NAMA_PEGAWAI");
    $infopenandatangannip= $satuan_kerja->getField("NIP");
    $infosatuankerjainfo= $satuan_kerja->getField("JABATAN")." ".$infopenandatangannamapejabat." (".$infopenandatangannip.")";

    /*
    $set->setField("TOTAL_DISKON_INDOOR_SEWA", ValToNullDB(dotToNo($req)));
    $set->setField("TOTAL_DISKON_OUTDOOR_SEWA", ValToNullDB(dotToNo($req)));
    $set->setField("TOTAL_DISKON_INDOOR_SERVICE", ValToNullDB(dotToNo($req)));
    $set->setField("TOTAL_DISKON_OUTDOOR_SERVICE", ValToNullDB(dotToNo($req)));
    */

    $statement= " AND A.TR_PSM_ID = ".$reqId." AND VMODE ILIKE '%luas_sewa%'";
    $set= new TrPsmDetil();
    $set->selectlokasi(array(), -1,-1, $statement);
    // echo $set->query;exit;
    while($set->nextRow())
    {
        $arrdata= [];
        $arrdata["trloidetilid"]= $set->getField("TR_PSM_DETIL_ID");
        $arrdata["trloiid"]= $set->getField("TR_PSM_ID");
        $arrdata["vmode"]= $set->getField("VMODE");
        $arrdata["vid"]= $set->getField("VID");
        $arrdata["vnilai"]= $set->getField("NILAI");
        $arrdata["kode"]= $set->getField("KODE");
        $arrdata["nama"]= $set->getField("NAMA");
        $arrdata["lantai"]= $set->getField("LANTAI");
        array_push($arrlokasi, $arrdata);
    }

    $statement= " AND A.TR_PSM_ID = ".$reqId;
    $set= new TrPsmDetil();
    $set->selectByParams(array(), -1,-1, $statement);
    while($set->nextRow())
    {
        $valid= $set->getField("VID");
        $valmode= $set->getField("VMODE");
        $arrdata= [];
        $arrdata["keyrowdetil"]= $valid."-".$valmode;
        $arrdata["trloidetilid"]= $set->getField("TR_PSM_DETIL_ID");;
        $arrdata["trloiid"]= $set->getField("TR_PSM_ID");
        $arrdata["vmode"]= $valmode;
        $arrdata["vid"]= $valid;
        $arrdata["vnilai"]= $set->getField("NILAI");
        $arrdata["vketerangan"]= $set->getField("KETERANGAN");
        array_push($arrdetil, $arrdata);
    }

    // ================================================================================================================
    // data loi
    $set= new TrLoi();
    $set->selectByParams(array("A.TR_LOI_ID" => $reqTrLoiId));
    $set->firstRow();
    
    $reqLoiProdukId= $set->getField("PRODUK_ID");
    $reqLoiCustomerId= $set->getField("CUSTOMER_ID");
    $reqLoiLokasiLooId= $set->getField("LOKASI_LOO_ID");
    $reqLoiPph= $set->getField("PPH");
    $reqLoiTotalLuasIndoor= $set->getField("TOTAL_LUAS_INDOOR");
    $reqLoiTotalLuasOutdoor= $set->getField("TOTAL_LUAS_OUTDOOR");
    $reqLoiTotalLuas= $set->getField("TOTAL_LUAS");
    $reqLoiHargaIndoorSewa= $set->getField("HARGA_INDOOR_SEWA");
    $reqLoiHargaOutdoorSewa= $set->getField("HARGA_OUTDOOR_SEWA");
    $reqLoiHargaIndoorService= $set->getField("HARGA_INDOOR_SERVICE");
    $reqLoiHargaOutdoorService= $set->getField("HARGA_OUTDOOR_SERVICE");
    $reqLoiDp= $set->getField("DP");
    $reqLoiPeriodeSewa= $set->getField("PERIODE_SEWA");
    $reqLoiTop= $set->getField("TOP");
    $reqLoiSewaBiayaSatuanUnit= $set->getField("SEWA_BIAYA_SATUAN_UNIT");
    $reqLoiSewaBiayaSatuanService= $set->getField("SEWA_BIAYA_SATUAN_SERVICE");
    $reqLoiSewaTotalBiayaUnit= $set->getField("SEWA_TOTAL_BIAYA_UNIT");
    $reqLoiSewaBiayaPerBulanUnit= $set->getField("SEWA_BIAYA_PER_BULAN_UNIT");
    $reqLoiSewaBiayaPerBulanService= $set->getField("SEWA_BIAYA_PER_BULAN_SERVICE");
    $reqLoiSewaTotalBiayaService= $set->getField("SEWA_TOTAL_BIAYA_SERVICE");
    $reqLoiTotalBiayaPerBulanNoPpn= $set->getField("TOTAL_BIAYA_PER_BULAN_NO_PPN");
    $reqLoiTotalBiayaNoPpn= $set->getField("TOTAL_BIAYA_NO_PPN");
    $reqLoiTotalBiayaPerBulanPpn= $set->getField("TOTAL_BIAYA_PER_BULAN_PPN");
    $reqLoiTotalBiayaPpn= $set->getField("TOTAL_BIAYA_PPN");

    $reqLoiSecurityDeposit= $set->getField("SECURITY_DEPOSIT");
    $reqLoiFittingOut= $set->getField("FITTING_OUT");

    $reqLoiTanggalAwal= dateToPageCheck($set->getField("INFO_TANGGAL_AWAL"));
    $reqLoiTanggalAkhir= dateToPageCheck($set->getField("INFO_TANGGAL_AKHIR"));
    $reqLoiPromotionLevy= $set->getField("PROMOTION_LEVY");

    $reqLoiSatuanKerjaPengirimId= $set->getField("SATUAN_KERJA_PENGIRIM_ID");
    $reqLoiUserPengirimId= $set->getField("USER_PENGIRIM_ID");
    $reqLoiStatusData= $set->getField("STATUS_DATA");
    $reqLoiUserId= $set->getField("USER_PEMBUAT_ID");
    // $reqLoixxx= $set->getField("USER_POSISI_PARAF_ID");

    $satuan_kerja= new SatuanKerja();
    $satuan_kerja->selectByParams(array(), -1, -1, " AND SATUAN_KERJA_ID = '".$reqLoiSatuanKerjaPengirimId."'", " ORDER BY KODE_SO ASC ");
    $satuan_kerja->firstRow();
    // echo $satuan_kerja->query;exit;
    $infopenandatangankode= $satuan_kerja->getField("KODE_SURAT");
    $infopenandatangannamapejabat= $satuan_kerja->getField("NAMA_PEGAWAI");
    $infopenandatangannip= $satuan_kerja->getField("NIP");
    $infoloisatuankerjainfo= $satuan_kerja->getField("JABATAN")." ".$infopenandatangannamapejabat." (".$infopenandatangannip.")";

    /*
    $set->setField("TOTAL_DISKON_INDOOR_SEWA", ValToNullDB(dotToNo($reqLoi)));
    $set->setField("TOTAL_DISKON_OUTDOOR_SEWA", ValToNullDB(dotToNo($reqLoi)));
    $set->setField("TOTAL_DISKON_INDOOR_SERVICE", ValToNullDB(dotToNo($reqLoi)));
    $set->setField("TOTAL_DISKON_OUTDOOR_SERVICE", ValToNullDB(dotToNo($reqLoi)));
    */

    $statement= " AND A.TR_LOI_ID = ".$reqTrLoiId." AND VMODE ILIKE '%luas_sewa%'";
    $set= new TrLoiDetil();
    $set->selectlokasi(array(), -1,-1, $statement);
    // echo $set->query;exit;
    while($set->nextRow())
    {
        $arrdata= [];
        $arrdata["trloodetilid"]= $set->getField("TR_LOO_DETIL_ID");
        $arrdata["trlooid"]= $set->getField("TR_LOI_ID");
        $arrdata["vmode"]= $set->getField("VMODE");
        $arrdata["vid"]= $set->getField("VID");
        $arrdata["vnilai"]= $set->getField("NILAI");
        $arrdata["kode"]= $set->getField("KODE");
        $arrdata["nama"]= $set->getField("NAMA");
        $arrdata["lantai"]= $set->getField("LANTAI");
        array_push($arrloilokasi, $arrdata);
    }

    $statement= " AND A.TR_LOI_ID = ".$reqTrLoiId;
    $set= new TrLoiDetil();
    $set->selectByParams(array(), -1,-1, $statement);
    while($set->nextRow())
    {
        $valid= $set->getField("VID");
        $valmode= $set->getField("VMODE");
        $arrdata= [];
        $arrdata["keyrowdetil"]= $valid."-".$valmode;
        $arrdata["trloodetilid"]= $set->getField("TR_LOO_DETIL_ID");;
        $arrdata["trlooid"]= $set->getField("TR_LOI_ID");
        $arrdata["vmode"]= $valmode;
        $arrdata["vid"]= $valid;
        $arrdata["vnilai"]= $set->getField("NILAI");
        $arrdata["vketerangan"]= $set->getField("KETERANGAN");
        array_push($arrloidetil, $arrdata);
    }
    // ================================================================================================================

    // data loo
    $set= new TrLoo();
    $set->selectByParams(array("A.TR_LOO_ID" => $reqTrLooId));
    $set->firstRow();
    
    $reqLooProdukId= $set->getField("PRODUK_ID");
    $reqLooCustomerId= $set->getField("CUSTOMER_ID");
    $reqLooLokasiLooId= $set->getField("LOKASI_LOO_ID");
    $reqLooPph= $set->getField("PPH");
    $reqLooTotalLuasIndoor= $set->getField("TOTAL_LUAS_INDOOR");
    $reqLooTotalLuasOutdoor= $set->getField("TOTAL_LUAS_OUTDOOR");
    $reqLooTotalLuas= $set->getField("TOTAL_LUAS");
    $reqLooHargaIndoorSewa= $set->getField("HARGA_INDOOR_SEWA");
    $reqLooHargaOutdoorSewa= $set->getField("HARGA_OUTDOOR_SEWA");
    $reqLooHargaIndoorService= $set->getField("HARGA_INDOOR_SERVICE");
    $reqLooHargaOutdoorService= $set->getField("HARGA_OUTDOOR_SERVICE");
    $reqLooDp= $set->getField("DP");
    $reqLooPeriodeSewa= $set->getField("PERIODE_SEWA");
    $reqLooTop= $set->getField("TOP");
    $reqLooSewaBiayaSatuanUnit= $set->getField("SEWA_BIAYA_SATUAN_UNIT");
    $reqLooSewaBiayaSatuanService= $set->getField("SEWA_BIAYA_SATUAN_SERVICE");
    $reqLooSewaTotalBiayaUnit= $set->getField("SEWA_TOTAL_BIAYA_UNIT");
    $reqLooSewaBiayaPerBulanUnit= $set->getField("SEWA_BIAYA_PER_BULAN_UNIT");
    $reqLooSewaBiayaPerBulanService= $set->getField("SEWA_BIAYA_PER_BULAN_SERVICE");
    $reqLooSewaTotalBiayaService= $set->getField("SEWA_TOTAL_BIAYA_SERVICE");
    $reqLooTotalBiayaPerBulanNoPpn= $set->getField("TOTAL_BIAYA_PER_BULAN_NO_PPN");
    $reqLooTotalBiayaNoPpn= $set->getField("TOTAL_BIAYA_NO_PPN");
    $reqLooTotalBiayaPerBulanPpn= $set->getField("TOTAL_BIAYA_PER_BULAN_PPN");
    $reqLooTotalBiayaPpn= $set->getField("TOTAL_BIAYA_PPN");

    $reqLooSecurityDeposit= $set->getField("SECURITY_DEPOSIT");
    $reqLooFittingOut= $set->getField("FITTING_OUT");

    $reqLooSatuanKerjaPengirimId= $set->getField("SATUAN_KERJA_PENGIRIM_ID");
    $reqLooUserPengirimId= $set->getField("USER_PENGIRIM_ID");
    $reqLooStatusData= $set->getField("STATUS_DATA");
    $reqLooUserId= $set->getField("USER_PEMBUAT_ID");
    // $reqLooxxx= $set->getField("USER_POSISI_PARAF_ID");

    $satuan_kerja= new SatuanKerja();
    $satuan_kerja->selectByParams(array(), -1, -1, " AND SATUAN_KERJA_ID = '".$reqLooSatuanKerjaPengirimId."'", " ORDER BY KODE_SO ASC ");
    $satuan_kerja->firstRow();
    // echo $satuan_kerja->query;exit;
    $infopenandatangankode= $satuan_kerja->getField("KODE_SURAT");
    $infopenandatangannamapejabat= $satuan_kerja->getField("NAMA_PEGAWAI");
    $infopenandatangannip= $satuan_kerja->getField("NIP");
    $infoloosatuankerjainfo= $satuan_kerja->getField("JABATAN")." ".$infopenandatangannamapejabat." (".$infopenandatangannip.")";

    /*
    $set->setField("TOTAL_DISKON_INDOOR_SEWA", ValToNullDB(dotToNo($reqLoo)));
    $set->setField("TOTAL_DISKON_OUTDOOR_SEWA", ValToNullDB(dotToNo($reqLoo)));
    $set->setField("TOTAL_DISKON_INDOOR_SERVICE", ValToNullDB(dotToNo($reqLoo)));
    $set->setField("TOTAL_DISKON_OUTDOOR_SERVICE", ValToNullDB(dotToNo($reqLoo)));
    */

    $statement= " AND A.TR_LOO_ID = ".$reqTrLooId." AND VMODE ILIKE '%luas_sewa%'";
    $set= new TrLooDetil();
    $set->selectlokasi(array(), -1,-1, $statement);
    // echo $set->query;exit;
    while($set->nextRow())
    {
        $arrdata= [];
        $arrdata["trloodetilid"]= $set->getField("TR_LOO_DETIL_ID");
        $arrdata["trlooid"]= $set->getField("TR_LOO_ID");
        $arrdata["vmode"]= $set->getField("VMODE");
        $arrdata["vid"]= $set->getField("VID");
        $arrdata["vnilai"]= $set->getField("NILAI");
        $arrdata["kode"]= $set->getField("KODE");
        $arrdata["nama"]= $set->getField("NAMA");
        $arrdata["lantai"]= $set->getField("LANTAI");
        array_push($arrloolokasi, $arrdata);
    }

    $statement= " AND A.TR_LOO_ID = ".$reqTrLooId;
    $set= new TrLooDetil();
    $set->selectByParams(array(), -1,-1, $statement);
    while($set->nextRow())
    {
        $valid= $set->getField("VID");
        $valmode= $set->getField("VMODE");
        $arrdata= [];
        $arrdata["keyrowdetil"]= $valid."-".$valmode;
        $arrdata["trloodetilid"]= $set->getField("TR_LOO_DETIL_ID");;
        $arrdata["trlooid"]= $set->getField("TR_LOO_ID");
        $arrdata["vmode"]= $valmode;
        $arrdata["vid"]= $valid;
        $arrdata["vnilai"]= $set->getField("NILAI");
        $arrdata["vketerangan"]= $set->getField("KETERANGAN");
        array_push($arrloodetil, $arrdata);
    }

}
// print_r($arrlokasi);exit;
// print_r($arrdetil);exit;

$arrutilitycharge= [];
$set= new Combo();
$set->looutilityharge(array(), -1,-1, "", "ORDER BY UTILITY_CHARGE_ID");
while($set->nextRow())
{
    $arrdata= [];
    $arrdata["id"]= $set->getField("UTILITY_CHARGE_ID");
    $arrdata["lokasilooid"]= $set->getField("LOKASI_LOO_ID");
    $arrdata["nama"]= $set->getField("NAMA");
    $arrdata["ket"]= $set->getField("KETERANGAN");
    $arrdata["harga"]= $set->getField("HARGA");
    array_push($arrutilitycharge, $arrdata);
}
// print_r($arrutilitycharge);exit;

$arrlog= [];
$arrloilog= [];
$arrloolog= [];
$sessid= $this->ID;
$checkparafid= "";
if (!empty($reqId))
{
    if($reqStatusData == "DRAFT" || $reqStatusData == "REVISI"){}
    elseif($reqStatusData == "PARAF" || $reqStatusData == "VALIDASI")
    {
        $statement.= " AND A.USER_POSISI_PARAF_ID = '".$sessid."' AND A.TR_PSM_ID = ".$reqIdDraft;
        $set= new TrPsm();
        $set->selectdraft(array(), -1, -1, $statement);
        // echo $set->query;exit;
        $set->firstRow();
        $checkparafid= $set->getField("TR_PSM_ID");
        $checknextpemaraf= $set->getField("NEXT_URUT");
        $checkstatusbantu= $set->getField("STATUS_BANTU");
        $chekvalidasi= "";
        if(isset($checknextpemaraf))
            $chekvalidasi= "validasi";
        // echo $chekvalidasi."-".$checknextpemaraf."--".$infonextpemaraf;exit;

        if (empty($checkparafid) && empty($reqId))
        {
            redirect("main/index/psm_perlu_persetujuan");
        }
        else
        {
            /*if((!empty($reqLinkMode) && $infonextpemaraf !== $checknextpemaraf) || empty($checknextpemaraf))
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
            }*/
        }
    }
    else
    redirect("main/index/psm_draft");

    $set= new TrPsm();
    $set->selectByParamsDataLog(array("A.TR_PSM_ID"=>$reqId),-1,-1);
    while($set->nextRow())
    {
        $arrdata= [];
        $arrdata["TANGGAL"]= dateTimeToPageCheck($set->getField("TANGGAL"));
        $arrdata["INFORMASI"]= $set->getField("INFORMASI");
        $arrdata["nama"]= $set->getField("NAMA");
        $arrdata["STATUS_SURAT"]= $set->getField("STATUS_SURAT");
        $arrdata["CATATAN"]= $set->getField("CATATAN");
        array_push($arrlog, $arrdata);
    }

    $set= new TrLoi();
    $set->selectByParamsDataLog(array("A.TR_LOI_ID"=>$reqTrLoiId),-1,-1);
    while($set->nextRow())
    {
        $arrdata= [];
        $arrdata["TANGGAL"]= dateTimeToPageCheck($set->getField("TANGGAL"));
        $arrdata["INFORMASI"]= $set->getField("INFORMASI");
        $arrdata["nama"]= $set->getField("NAMA");
        $arrdata["STATUS_SURAT"]= $set->getField("STATUS_SURAT");
        $arrdata["CATATAN"]= $set->getField("CATATAN");
        array_push($arrloilog, $arrdata);
    }

    $set= new TrLoo();
    $set->selectByParamsDataLog(array("A.TR_LOO_ID"=>$reqTrLooId),-1,-1);
    while($set->nextRow())
    {
        $arrdata= [];
        $arrdata["TANGGAL"]= dateTimeToPageCheck($set->getField("TANGGAL"));
        $arrdata["INFORMASI"]= $set->getField("INFORMASI");
        $arrdata["nama"]= $set->getField("NAMA");
        $arrdata["STATUS_SURAT"]= $set->getField("STATUS_SURAT");
        $arrdata["CATATAN"]= $set->getField("CATATAN");
        array_push($arrloolog, $arrdata);
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
<base href="<?=base_url();?>">

<link rel="stylesheet" type="text/css" href="css/gaya.css">

<link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">

<link href="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

<script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript" src="lib/easyui/globalfunction.js"></script>

<!-- UPLOAD CORE -->
<script src="lib/multifile-master/jquery.MultiFile.js"></script>
<script>
// wait for document to load
$(function(){
    
    // invoke plugin
    $('#reqLampiran').MultiFile({
    onFileChange: function(){
        console.log(this, arguments);
    }
    });

});

</script>

<script src="lib/startbootstrap-sb-admin-2-1.0.7/dist/js/eModal2.min.js"></script>
<script type="text/javascript">
    

    function openPopup(page) {
        eModal.iframe(page, 'Aplikasi E-Office - PT. Jembatan Nusantara')
    }
    
    function closePopup()
    {
        eModal.close();
    }
    
</script>
</head>

<body>
    <div class="col-lg-12 col-konten-full">
        <div class="judul-halaman bg-course">
            <span><img src="images/icon-course.png"></span> Kelola LOI
            <div class="btn-atas clearfix">
                <?
                $aksibutton= "";
                if(!empty($reqId)) 
                {
                ?>
                    <a class="btn btn-danger btn-sm pull-right" id="buttonpdf" onClick="submitPreview()" style="cursor: pointer;"><i class="fa fa-file-pdf-o"></i> View as PDF</a>
                <?
                }

                if(!empty($reqId)) 
                {
                ?>
                    <!-- <a class="btn btn-danger btn-sm pull-right" id="buttonpdf" onClick="submitLampiran()" style="cursor: pointer;"><i class="fa fa-file-pdf-o"></i> Lampiran II</a> -->
                <?
                }
                ?>

                <?
                // tambahan khusus, kalau paraf sesuai urutan
                if ($reqStatusData == "PARAF" && !empty($checkparafid) && $reqUserId != $sessid) 
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

                if ($reqStatusData == "REVISI" && $reqUserId == $sessid)
                {
                    $aksibutton= "1";
                ?>
                    <button class="btn btn-primary btn-sm pull-right" type="button" onClick="submitForm('UBAHDATAREVISI')"><i class="fa fa-save"></i> Simpan</button>
                    <button class="btn btn-primary btn-sm pull-right" type="button" onClick="submitForm('UBAHDATAPOSTING')"><i class="fa fa-paper-plane"></i> Kirim</button>
                    <!-- <button class="btn btn-warning btn-sm pull-right" type="button" onClick="setagenda()"><i class="fa fa-list"></i> Agenda Surat</button> -->
                <?
                }

                if(empty($reqId) || ($reqStatusData == "DRAFT" && !empty($reqId)) ) 
                {
                    $aksibutton= "1";
                ?>
                    <button id="btnPARAF" class="btn btn-primary btn-sm pull-right" type="button" onClick="submitForm('UBAHDATADRAFTPARAF')">
                        <span style="display: none;" class="buttonspiner ic2-fa-spin-blue"></span>
                        <i class="fa fa-paper-plane"></i> Kirim
                    </button>

                    <button id="btnDRAFT" class="btn btn-default btn-sm pull-right" type="button" onClick="submitForm('DRAFT')">
                        <span style="display: none;" class="buttonspiner ic2-fa-spin-blue"></span>
                        <i class="fa fa-file-o"></i> Draft
                    </button>
                <?
                }

                if ($reqStatusData == "DRAFT" && !empty($reqId))
                {
                ?>
                    <button class="btn btn-danger btn-sm pull-right" type="button" onClick="deleteForm()"><i class="fa fa-trash-o"></i> Hapus</button>
                <?
                }

                if (!empty($reqId) && $reqStatusData == "VALIDASI" && $reqUserPengirimId == $sessid)
                {
                    $aksibutton= "1";
                ?>
                    <button class="btn btn-primary btn-sm pull-right" type="button" onClick="submitForm('UBAHDATAVALIDASI')"><i class="fa fa-save"></i> Simpan</button>
                    <button class="btn btn-primary btn-sm pull-right" type="button" onClick="submitForm('REVISI')"><i class="fa fa-level-down"></i> Kembalikan</button>
                    <button class="btn btn-primary btn-sm pull-right" type="button" onClick="submitForm('POSTING')"><i class="fa fa-check-square-o"></i> Setujui</button>

                <?
                }
                ?>
                
            </div>

            <div class="konten-detil" style="float: none;">

                <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a data-toggle="tab" href="#tab-psm">
                                PSM
                            </a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#tab-loi">
                                LOI
                            </a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#tab-loo">
                                LOO
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div id="tab-psm" class="tab-pane fade in active">

                            <div id="popup-tabel2">
                                <input type="hidden" name="reqStatusData" id="reqStatusData" value="<?=$reqStatusData?>" />
                                <input type="hidden" name="reqInfoLog" id="reqInfoLog" />
                                <input type="hidden" name="reqId" value="<?=$reqId?>" />
                                <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
                                <input type="hidden" name="cekquery" value="<?=$cekquery?>" />

                                <table class="table">
                                    <thead>
                                        <tr>           
                                            <td>Lokasi</td>
                                            <td>:</td>
                                            <td>
                                                <input type="hidden" id="sebelumLokasiLooId" value="<?=$reqLokasiLooId?>"  />
                                                <input type="text" name="reqLokasiLooId" class="easyui-combotree" id="reqLokasiLooId" 
                                                data-options="
                                                onClick: function(node){
                                                    sethargautilitycharge(node);
                                                }
                                                , width:'350'
                                                , valueField:'id'
                                                , textField:'text'
                                                , editable:false
                                                , url:'combo_json/comboLokasiLoo'
                                                " required value="<?=$reqLokasiLooId?>" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Customer</td>
                                            <td>:</td>
                                            <td>
                                                <input type="text" name="reqCustomerId" class="easyui-combobox" id="reqCustomerId" data-options="width:'350', valueField:'id', textField:'text', editable:false, url:'combo_json/comboCustomer?cek=pemilik'" required value="<?=$reqCustomerId?>" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Produk</td>
                                            <td>:</td>
                                            <td>
                                                <input type="text" name="reqProdukId" class="easyui-combobox" id="reqProdukId" data-options="width:'350', valueField:'id', textField:'text', editable:false, url:'combo_json/comboProduk'" required value="<?=$reqProdukId?>" />
                                                &nbsp;&nbsp;PPN
                                                <input type="text" class="vlxuangclass easyui-validatebox textbox form-control" name="reqPph" id="reqPph" style="width:5%; padding: initial; display: inline; text-align: right;" value="<?=numberToIna($reqPph)?>" />           
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Penanda tangan <span class="text-danger">*</span></td>
                                            <td>:</td>
                                            <td>
                                                <?
                                                $infodisplay= "";
                                                // if(!empty($checkparafid) || $reqStatusSurat == "REVISI")
                                                if(empty($aksibutton) || !empty($checkparafid) || $reqStatusSurat == "XXXREVISI")
                                                {
                                                    $infodisplay= "none";
                                                ?>
                                                <span style="display: none;">
                                                    <input type="text" id="reqSatuanKerjaPengirimId" class="easyui-combotree" name="reqSatuanKerjaPengirimId" value="<?=$reqSatuanKerjaPengirimId?>" />
                                                </span>
                                                <?=$infosatuankerjainfo?>
                                                <?
                                                }
                                                else
                                                {
                                                ?>
                                                <input type="text" id="reqSatuanKerjaPengirimId" class="easyui-combotree" name="reqSatuanKerjaPengirimId" data-options="
                                                onClick: function(rec){
                                                    $('#reqUserPengirimId').val(rec.NIP);
                                                    var url = 'web/satuan_kerja_json/combo_paraf/?reqId='+rec.SATUAN_KERJA_ID;

                                                    // tambahan khusus
                                                    if(rec.NIP == '')
                                                    {
                                                        $.messager.alert('Info', 'Pengirim belum di tentukan di master.', 'info');
                                                        $('#reqSatuanKerjaPengirimId').combotree('setValue', '');
                                                    }
                                                }
                                                , width:'500'
                                                , panelHeight:'120'
                                                , valueField:'id'
                                                , textField:'text'
                                                , url:'web/satuan_kerja_json/combotreesatker/'
                                                , prompt:'Tentukan Pengirim...'," value="<?=$reqSatuanKerjaPengirimId?>"
                                                required="required"
                                                />
                                                <?
                                                }
                                                ?>
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
                                                        $setinfoparaf= new TrPsmParaf();
                                                        $setinfoparaf->selectByParams(array(), -1, -1, " AND A.STATUS_BANTU IS NULL AND A.TR_PSM_ID = ".$reqId, "ORDER BY A.NO_URUT");
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
                                                            $set_attachement = new TrPsm();
                                                            $set_attachement->selectByParamsAttachment(array("A.TR_PSM_ID" => (int)$reqId));
                                                            while ($set_attachement->nextRow()) {
                                                                $attach_id= $set_attachement->getField("TR_PSM_ATTACHMENT_ID");
                                                            ?>
                                                                
                                                                <div class="MultiFile-label">
                                                                    <input type="hidden" name="reqLinkFileTemp[]" value="<?= $set_attachement->getField("ATTACHMENT") ?>" />
                                                                    <input type="hidden" name="reqLinkFileTempNama[]" value="<?= $set_attachement->getField("NAMA") ?>" />
                                                                    <input type="hidden" name="reqLinkFileTempTipe[]" value="<?= $set_attachement->getField("TIPE") ?>" />
                                                                    <input type="hidden" name="reqLinkFileTempSize[]" value="<?= $set_attachement->getField("UKURAN") ?>" />
                                                                    <a class="MultiFile-remove"><i class="fa fa-times-circle" onclick="infolampiran('min'); $(this).parent().parent().remove();"></i></a>
                    
                                                                    <?
                                                                    $arrexcept= array("xlsx", "xls", "doc", "docx", "ppt", "pptx", "txt");
                                                                    //$arrexcept= array("xlsx", "xls", "doc", "docx", "txt");
                                                                    if(in_array(strtolower($set_attachement->getField("TIPE")), $arrexcept))
                                                                    {
                                                                    ?>
                                                                    <?= $set_attachement->getField("NAMA") ?>
                                                                    <a onClick="down('<?=$attach_id?>')" >
                                                                        <i style="cursor: pointer;" class="fa fa-download" ></i>
                                                                    </a>
                                                                    <?
                                                                    }
                                                                    else
                                                                    {
                                                                    ?>
                                                                    <?= $set_attachement->getField("NAMA") ?>
                                                                    <a onClick="parent.openAdd('<?= base_url()."uploadsloi/".$reqId."/".$set_attachement->getField("ATTACHMENT") ?>')" >
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

                                        <!-- <tr>
                                            <td>Lokasi Lantai</td>
                                            <td>:</td>
                                            <td>
                                                <input type="text" name="reqLokasiLooId" class="easyui-combobox"  id="reqLokasiLooId"
                                                       data-options="width:'350', valueField:'id', textField:'text', editable:false, url:'combo_json/comboLokasiLooDetil'" required value="<?=$reqLokasiLooId?>" />
                                            </td>
                                        </tr> -->
                                    </thead>
                                </table>

                                <fieldset>
                                    <legend style="font-size: large;">
                                        Luas Sewa
                                    </legend>

                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td class="tdcolor">
                                                    Indoor
                                                    <?
                                                    if(empty($infodisplay))
                                                    {
                                                    ?>
                                                    <a onClick="openLookup('I')"><i class="fa fa-plus-square fa-lg" aria-hidden="true"></i></a>
                                                    <?
                                                    }
                                                    ?>
                                                </td>
                                                <td class="tdcolor">
                                                    Outdoor
                                                    <?
                                                    if(empty($infodisplay))
                                                    {
                                                    ?>
                                                    <a onClick="openLookup('O')"><i class="fa fa-plus-square fa-lg" aria-hidden="true"></i></a>
                                                    <?
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 50%">
                                                    <table class="table">
                                                        <tbody id="luassewaindoor">
                                                            <?
                                                            foreach ($arrlokasi as $k => $v)
                                                            {
                                                                // $vkeyid= $v["trloodetilid"];
                                                                // $vkeyid= $v["trlooid"];
                                                                $vkeyid= $v["vid"];
                                                                // $vlabel= $v["kode"]." - ".$v["nama"];
                                                                $vlabel= $v["kode"]." - ".$v["lantai"];
                                                                $vnilai= $v["vnilai"];
                                                                $vmode= $v["vmode"];
                                                                if($vmode == "luas_sewa_indoor")
                                                                {
                                                            ?>
                                                            <tr class="grouplokasiclass<?=$reqLokasiLooId?> groupclass<?=$vkeyid?>">
                                                                <td>
                                                                    <?=$vlabel?>
                                                                    <?
                                                                    if(empty($infodisplay))
                                                                    {
                                                                    ?>
                                                                    <i style="cursor:pointer" class="fa fa-times-circle text-danger" aria-hidden="true" onclick="hapusgroupclass('<?=$vkeyid?>');"></i>
                                                                    <?
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <td>:</td>
                                                                <td style="width:30%">
                                                                    <input type="hidden" name="vmode[]" value="luas_sewa_indoor" />
                                                                    <input type="hidden" name="vid[]" class="valsetid" value="<?=$vkeyid?>" />
                                                                    <input type="hidden" name="vketerangan[]" />
                                                                    <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control totalluasindoor" name="vnilai[]" placeholder="Isi Luas (m2)" data-options="required:true" style="width:85%; display: inline; text-align: right;" value="<?=numberToIna($vnilai)?>" /> <label class="labeltotal">m2</label>'
                                                                </td>
                                                            </tr>
                                                            <?
                                                                }
                                                            }
                                                            ?>
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <td colspan="2" style="text-align: right;">Total Luas Indoor</td>
                                                                <td style="width: 20%">
                                                                    <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" name="reqTotalLuasIndoor" id="reqTotalLuasIndoor" style="width:85%; display: inline; text-align: right;" value="<?=numberToIna($reqTotalLuasIndoor)?>" /> <label class="labeltotal">m2</label>
                                                                </td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </td>
                                                <td style="width: 50%">
                                                    <table class="table">
                                                        <tbody id="luassewaoutdoor">
                                                            <?
                                                            foreach ($arrlokasi as $k => $v)
                                                            {
                                                                // $vkeyid= $v["trloodetilid"];
                                                                // $vkeyid= $v["trlooid"];
                                                                $vkeyid= $v["vid"];
                                                                // $vlabel= $v["kode"]." - ".$v["nama"];
                                                                $vlabel= $v["kode"]." - ".$v["lantai"];
                                                                $vnilai= $v["vnilai"];
                                                                $vmode= $v["vmode"];
                                                                if($vmode == "luas_sewa_outdoor")
                                                                {
                                                            ?>
                                                            <tr class="grouplokasiclass<?=$reqLokasiLooId?> groupclass<?=$vkeyid?>">
                                                                <td>
                                                                    <?=$vlabel?>
                                                                    <?
                                                                    if(empty($infodisplay))
                                                                    {
                                                                    ?>
                                                                    <i style="cursor:pointer" class="fa fa-times-circle text-danger" aria-hidden="true" onclick="hapusgroupclass('<?=$vkeyid?>');"></i>
                                                                    <?
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <td>:</td>
                                                                <td style="width:30%">
                                                                    <input type="hidden" name="vmode[]" value="luas_sewa_outdoor" />
                                                                    <input type="hidden" name="vid[]" class="valsetid" value="<?=$vkeyid?>" />
                                                                    <input type="hidden" name="vketerangan[]" />
                                                                    <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control totalluasoutdoor" name="vnilai[]" placeholder="Isi Luas (m2)" data-options="required:true" style="width:85%; display: inline; text-align: right;" value="<?=numberToIna($vnilai)?>" /> <label class="labeltotal">m2</label>'
                                                                </td>
                                                            </tr>
                                                            <?
                                                                }
                                                            }
                                                            ?>
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <td colspan="2" style="text-align: right;">Total Luas Outdoor</td>
                                                                <td style="width: 20%">
                                                                    <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" name="reqTotalLuasOutdoor" id="reqTotalLuasOutdoor" style="width:85%; display: inline; text-align: right;" value="<?=numberToIna($reqTotalLuasOutdoor)?>" /> <label class="labeltotal">m2</label>
                                                                </td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" style="text-align: right;">
                                                    Total Luas Sewa
                                                    <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" name="reqTotalLuas" id="reqTotalLuas" style="width:15%; display: inline; text-align: right;" value="<?=numberToIna($reqTotalLuas)?>" /> <label class="labeltotal">m2</label>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </fieldset>

                                <fieldset>
                                    <legend style="font-size: large;">
                                        Tarif Sewa
                                    </legend>
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td colspan="3" class="tdcolor" style="width: 50%">
                                                    Indoor
                                                </td>
                                                <td colspan="3" class="tdcolor" style="width: 50%">
                                                    Outdoor
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="3">
                                                    <table class="table">
                                                        <tbody id="tarifinfosewaindoor">
                                                            <tr>
                                                                <td class="tdcolor" style="width: 27%">Unit</td>
                                                                <td class="tdcolor" style="width: 15%">Discount</td>
                                                                <td class="tdcolor" style="width: 29%">Tarif (after discount)</td>
                                                                <td class="tdcolor" style="width: 24%">Harga Sewa</td>
                                                            </tr>
                                                            <?
                                                            foreach ($arrlokasi as $k => $v)
                                                            {
                                                                // $vkeyid= $v["trloodetilid"];
                                                                // $vkeyid= $v["trlooid"];
                                                                $vkeyid= $v["vid"];
                                                                // $vlabel= $v["kode"]." - ".$v["nama"];
                                                                $vlabel= $v["kode"]." - ".$v["lantai"];
                                                                $vluas= $v["vnilai"];
                                                                $vmode= $v["vmode"];
                                                                if($vmode == "luas_sewa_indoor")
                                                                {
                                                            ?>
                                                            <tr class="grouplokasiclass<?=$reqLokasiLooId?> groupclass<?=$vkeyid?>">
                                                                <td colspan="4"><?=$vlabel?></td>
                                                            </tr>
                                                            <tr class="grouplokasiclass<?=$reqLokasiLooId?> groupclass<?=$vkeyid?>">
                                                                <td>
                                                                    <?
                                                                    $valnilai= 0;
                                                                    $valmode= "tarif_sewa_unit_indoor";
                                                                    $infocarikey= $vkeyid."-".$valmode;
                                                                    $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrdetil);
                                                                    if(!empty($arrkondisicheck))
                                                                    {
                                                                        $vindex= $arrkondisicheck[0];
                                                                        $valnilai= $arrdetil[$vindex]["vnilai"];
                                                                    }
                                                                    ?>
                                                                    <input type="hidden" name="vmode[]" value="<?=$valmode?>" />
                                                                    <input type="hidden" name="vid[]" class="valsetid" value="<?=$vkeyid?>" />
                                                                    <input type="hidden" name="vketerangan[]" />
                                                                    <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control totalsewaunitindoor" name="vnilai[]" placeholder="Isi Rp/m2" data-options="required:true" style="width:65%; display: inline; text-align: right;" value="<?=numberToIna($valnilai)?>" /> <label class="labeltotal">Rp/m2</label>
                                                                </td>
                                                                <td>
                                                                    <?
                                                                    $valnilai= 0;
                                                                    $valmode= "tarif_sewa_unit_indoor_diskon";
                                                                    $infocarikey= $vkeyid."-".$valmode;
                                                                    $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrdetil);
                                                                    if(!empty($arrkondisicheck))
                                                                    {
                                                                        $vindex= $arrkondisicheck[0];
                                                                        $valnilai= $arrdetil[$vindex]["vnilai"];
                                                                    }
                                                                    ?>
                                                                    <input type="hidden" name="vmode[]" value="<?=$valmode?>" />
                                                                    <input type="hidden" name="vid[]" class="valsetid" value="<?=$vkeyid?>" />
                                                                    <input type="hidden" name="vketerangan[]" />
                                                                    <input type="text" class="vlxuangclass easyui-validatebox textbox form-control totalsewaunitindoordiskon" name="vnilai[]" placeholder="Isi %" data-options="required:true" style="width:65%; display: inline; text-align: right;" value="<?=numberToIna($valnilai)?>" /> <label class="labeltotal">%</label>
                                                                </td>
                                                                <td>
                                                                    <?
                                                                    $valnilai= 0;
                                                                    $valmode= "tarif_sewa_unit_indoor_after_diskon";
                                                                    $infocarikey= $vkeyid."-".$valmode;
                                                                    $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrdetil);
                                                                    if(!empty($arrkondisicheck))
                                                                    {
                                                                        $vindex= $arrkondisicheck[0];
                                                                        $valnilai= $arrdetil[$vindex]["vnilai"];
                                                                    }
                                                                    ?>
                                                                    <input type="hidden" name="vmode[]" value="<?=$valmode?>" />
                                                                    <input type="hidden" name="vid[]" class="valsetid" value="<?=$vkeyid?>" />
                                                                    <input type="hidden" name="vketerangan[]" />
                                                                    <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control totalsewaunitindoorafterdiskon" name="vnilai[]" placeholder="Isi Rp/m2" data-options="required:true" style="width:65%; display: inline; text-align: right;" value="<?=numberToIna($valnilai)?>" /> <label class="labeltotal">Rp/m2</label>
                                                                </td>
                                                                <td>
                                                                    <?
                                                                    $valnilai= 0;
                                                                    $valmode= "tarif_sewa_unit_indoor_harga";
                                                                    $infocarikey= $vkeyid."-".$valmode;
                                                                    $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrdetil);
                                                                    if(!empty($arrkondisicheck))
                                                                    {
                                                                        $vindex= $arrkondisicheck[0];
                                                                        $valnilai= $arrdetil[$vindex]["vnilai"];
                                                                    }
                                                                    ?>
                                                                    <input type="hidden" name="vmode[]" value="<?=$valmode?>" />
                                                                    <input type="hidden" name="vid[]" class="valsetid" value="<?=$vkeyid?>" />
                                                                    <input type="hidden" name="vketerangan[]" />
                                                                    <input type="hidden" class="totalsewaunitindoorsewaluas" value="<?=numberToIna($vluas)?>" />
                                                                    <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control totalsewaunitindoorharga" name="vnilai[]" style="display: inline; text-align: right;" value="<?=numberToIna($valnilai)?>" />
                                                                </td>
                                                            </tr>
                                                            <?
                                                                }
                                                            }
                                                            ?>

                                                        </tbody>
                                                    </table>
                                                </td>
                                                <td colspan="3">
                                                    <table class="table">
                                                        <tbody id="tarifinfosewaoutdoor">
                                                            <tr>
                                                                <td class="tdcolor" style="width: 27%">Unit</td>
                                                                <td class="tdcolor" style="width: 15%">Discount</td>
                                                                <td class="tdcolor" style="width: 29%">Tarif (after discount)</td>
                                                                <td class="tdcolor" style="width: 24%">Harga Sewa</td>
                                                            </tr>
                                                            <?
                                                            foreach ($arrlokasi as $k => $v)
                                                            {
                                                                // $vkeyid= $v["trloodetilid"];
                                                                // $vkeyid= $v["trlooid"];
                                                                $vkeyid= $v["vid"];
                                                                // $vlabel= $v["kode"]." - ".$v["nama"];
                                                                $vlabel= $v["kode"]." - ".$v["lantai"];
                                                                $vluas= $v["vnilai"];
                                                                $vmode= $v["vmode"];
                                                                if($vmode == "luas_sewa_outdoor")
                                                                {
                                                            ?>
                                                            <tr class="grouplokasiclass<?=$reqLokasiLooId?> groupclass<?=$vkeyid?>">
                                                                <td colspan="4"><?=$vlabel?></td>
                                                            </tr>
                                                            <tr class="grouplokasiclass<?=$reqLokasiLooId?> groupclass<?=$vkeyid?>">
                                                                <td>
                                                                    <?
                                                                    $valnilai= 0;
                                                                    $valmode= "tarif_sewa_unit_outdoor";
                                                                    $infocarikey= $vkeyid."-".$valmode;
                                                                    $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrdetil);
                                                                    if(!empty($arrkondisicheck))
                                                                    {
                                                                        $vindex= $arrkondisicheck[0];
                                                                        $valnilai= $arrdetil[$vindex]["vnilai"];
                                                                    }
                                                                    ?>
                                                                    <input type="hidden" name="vmode[]" value="<?=$valmode?>" />
                                                                    <input type="hidden" name="vid[]" class="valsetid" value="<?=$vkeyid?>" />
                                                                    <input type="hidden" name="vketerangan[]" />
                                                                    <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control totalsewaunitoutdoor" name="vnilai[]" placeholder="Isi Rp/m2" data-options="required:true" style="width:65%; display: inline; text-align: right;" value="<?=numberToIna($valnilai)?>" /> <label class="labeltotal">Rp/m2</label>
                                                                </td>
                                                                <td>
                                                                    <?
                                                                    $valnilai= 0;
                                                                    $valmode= "tarif_sewa_unit_outdoor_diskon";
                                                                    $infocarikey= $vkeyid."-".$valmode;
                                                                    $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrdetil);
                                                                    if(!empty($arrkondisicheck))
                                                                    {
                                                                        $vindex= $arrkondisicheck[0];
                                                                        $valnilai= $arrdetil[$vindex]["vnilai"];
                                                                    }
                                                                    ?>
                                                                    <input type="hidden" name="vmode[]" value="<?=$valmode?>" />
                                                                    <input type="hidden" name="vid[]" class="valsetid" value="<?=$vkeyid?>" />
                                                                    <input type="hidden" name="vketerangan[]" />
                                                                    <input type="text" class="vlxuangclass easyui-validatebox textbox form-control totalsewaunitoutdoordiskon" name="vnilai[]" placeholder="Isi %" data-options="required:true" style="width:65%; display: inline; text-align: right;" value="<?=numberToIna($valnilai)?>" /> <label class="labeltotal">%</label>
                                                                </td>
                                                                <td>
                                                                    <?
                                                                    $valnilai= 0;
                                                                    $valmode= "tarif_sewa_unit_outdoor_after_diskon";
                                                                    $infocarikey= $vkeyid."-".$valmode;
                                                                    $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrdetil);
                                                                    if(!empty($arrkondisicheck))
                                                                    {
                                                                        $vindex= $arrkondisicheck[0];
                                                                        $valnilai= $arrdetil[$vindex]["vnilai"];
                                                                    }
                                                                    ?>
                                                                    <input type="hidden" name="vmode[]" value="<?=$valmode?>" />
                                                                    <input type="hidden" name="vid[]" class="valsetid" value="<?=$vkeyid?>" />
                                                                    <input type="hidden" name="vketerangan[]" />
                                                                    <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control totalsewaunitoutdoorafterdiskon" name="vnilai[]" placeholder="Isi Rp/m2" data-options="required:true" style="width:65%; display: inline; text-align: right;" value="<?=numberToIna($valnilai)?>" /> <label class="labeltotal">Rp/m2</label>
                                                                </td>
                                                                <td>
                                                                    <?
                                                                    $valnilai= 0;
                                                                    $valmode= "tarif_sewa_unit_outdoor_harga";
                                                                    $infocarikey= $vkeyid."-".$valmode;
                                                                    $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrdetil);
                                                                    if(!empty($arrkondisicheck))
                                                                    {
                                                                        $vindex= $arrkondisicheck[0];
                                                                        $valnilai= $arrdetil[$vindex]["vnilai"];
                                                                    }
                                                                    ?>
                                                                    <input type="hidden" name="vmode[]" value="<?=$valmode?>" />
                                                                    <input type="hidden" name="vid[]" class="valsetid" value="<?=$vkeyid?>" />
                                                                    <input type="hidden" name="vketerangan[]" />
                                                                    <input type="hidden" class="totalsewaunitoutdoorsewaluas" value="<?=numberToIna($vluas)?>" />
                                                                    <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control totalsewaunitoutdoorharga" name="vnilai[]" style="display: inline; text-align: right;" value="<?=numberToIna($valnilai)?>" />
                                                                </td>
                                                            </tr>
                                                            <?
                                                                }
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th colspan="6">Service Charge</th>
                                            </tr>
                                            <tr>
                                                <td colspan="3">
                                                    <table class="table">
                                                        <tbody id="tarifinfosewascindoor">
                                                            <tr>
                                                                <td class="tdcolor" style="width: 35%">Harga awal setelah ppn</td>
                                                                <td class="tdcolor" style="width: 20%">Discount</td>
                                                                <td class="tdcolor" style="width: 45%">Harga akhir setelah ppn (after disc)</td>
                                                            </tr>
                                                            <?
                                                            foreach ($arrlokasi as $k => $v)
                                                            {
                                                                // $vkeyid= $v["trloodetilid"];
                                                                $vkeyid= $v["vid"];
                                                                // $vlabel= $v["kode"]." - ".$v["nama"];
                                                                $vlabel= $v["kode"]." - ".$v["lantai"];
                                                                $vluas= $v["vnilai"];
                                                                $vmode= $v["vmode"];
                                                                if($vmode == "luas_sewa_indoor")
                                                                {
                                                            ?>
                                                            <tr class="grouplokasiclass<?=$reqLokasiLooId?> groupclass<?=$vkeyid?>">
                                                                <td colspan="4"><?=$vlabel?></td>
                                                            </tr>
                                                            <tr class="grouplokasiclass<?=$reqLokasiLooId?> groupclass<?=$vkeyid?>">
                                                                <td>
                                                                    <?
                                                                    $valnilai= 0;
                                                                    $valmode= "tarif_sewa_sc_indoor";
                                                                    $infocarikey= $vkeyid."-".$valmode;
                                                                    $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrdetil);
                                                                    if(!empty($arrkondisicheck))
                                                                    {
                                                                        $vindex= $arrkondisicheck[0];
                                                                        $valnilai= $arrdetil[$vindex]["vnilai"];
                                                                    }
                                                                    ?>
                                                                    <input type="hidden" name="vmode[]" value="<?=$valmode?>" />
                                                                    <input type="hidden" name="vid[]" class="valsetid" value="<?=$vkeyid?>" />
                                                                    <input type="hidden" name="vketerangan[]" />
                                                                    <input type="hidden" class="totalsewascindoor" name="vnilai[]" value="<?=numberToIna($valnilai)?>" />

                                                                    <?
                                                                    $valnilai= 0;
                                                                    $valmode= "tarif_sewa_sc_indoor_after_ppn";
                                                                    $infocarikey= $vkeyid."-".$valmode;
                                                                    $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrdetil);
                                                                    if(!empty($arrkondisicheck))
                                                                    {
                                                                        $vindex= $arrkondisicheck[0];
                                                                        $valnilai= $arrdetil[$vindex]["vnilai"];
                                                                    }
                                                                    ?>
                                                                    <input type="hidden" name="vmode[]" value="<?=$valmode?>" />
                                                                    <input type="hidden" name="vid[]" class="valsetid" value="<?=$vkeyid?>" />
                                                                    <input type="hidden" name="vketerangan[]" />
                                                                    <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control totalsewascindoorafterppn" name="vnilai[]" placeholder="Isi Rp/m2" data-options="required:true" style="width:65%; display: inline; text-align: right;" value="<?=numberToIna($valnilai)?>" /> <label class="labeltotal">Rp/m2</label>
                                                                </td>
                                                                <td>
                                                                    <?
                                                                    $valnilai= 0;
                                                                    $valmode= "tarif_sewa_sc_indoor_diskon";
                                                                    $infocarikey= $vkeyid."-".$valmode;
                                                                    $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrdetil);
                                                                    if(!empty($arrkondisicheck))
                                                                    {
                                                                        $vindex= $arrkondisicheck[0];
                                                                        $valnilai= $arrdetil[$vindex]["vnilai"];
                                                                    }
                                                                    ?>
                                                                    <input type="hidden" name="vmode[]" value="<?=$valmode?>" />
                                                                    <input type="hidden" name="vid[]" class="valsetid" value="<?=$vkeyid?>" />
                                                                    <input type="hidden" name="vketerangan[]" />
                                                                    <input type="text" class="vlxuangclass easyui-validatebox textbox form-control totalsewascindoordiskon" name="vnilai[]" placeholder="Isi %" data-options="required:true" style="width:65%; display: inline; text-align: right;" value="<?=numberToIna($valnilai)?>" /> <label class="labeltotal">%</label>

                                                                    <?
                                                                    $valnilai= 0;
                                                                    $valmode= "tarif_sewa_sc_indoor_after_diskon";
                                                                    $infocarikey= $vkeyid."-".$valmode;
                                                                    $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrdetil);
                                                                    if(!empty($arrkondisicheck))
                                                                    {
                                                                        $vindex= $arrkondisicheck[0];
                                                                        $valnilai= $arrdetil[$vindex]["vnilai"];
                                                                    }
                                                                    ?>
                                                                    <input type="hidden" name="vmode[]" value="<?=$valmode?>" />
                                                                    <input type="hidden" name="vid[]" class="valsetid" value="<?=$vkeyid?>" />
                                                                    <input type="hidden" name="vketerangan[]" />
                                                                    <input type="hidden" class="totalsewascindoorafterdiskon" name="vnilai[]" value="<?=numberToIna($valnilai)?>" />
                                                                </td>
                                                                <td>
                                                                    <?
                                                                    $valnilai= 0;
                                                                    $valmode= "tarif_sewa_sc_indoor_after_ppn_diskon";
                                                                    $infocarikey= $vkeyid."-".$valmode;
                                                                    $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrdetil);
                                                                    if(!empty($arrkondisicheck))
                                                                    {
                                                                        $vindex= $arrkondisicheck[0];
                                                                        $valnilai= $arrdetil[$vindex]["vnilai"];
                                                                    }
                                                                    ?>
                                                                    <input type="hidden" name="vmode[]" value="<?=$valmode?>" />
                                                                    <input type="hidden" name="vid[]" class="valsetid" value="<?=$vkeyid?>" />
                                                                    <input type="hidden" name="vketerangan[]" />
                                                                    <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control totalsewascindoorafterppndiskon" name="vnilai[]" placeholder="Isi Rp/m2" data-options="required:true" style="width:65%; display: inline; text-align: right;" value="<?=numberToIna($valnilai)?>" /> <label class="labeltotal">Rp/m2</label>
                                                                </td>
                                                            </tr>
                                                            <?
                                                                }
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </td>
                                                <td colspan="3">
                                                    <table class="table">
                                                        <tbody id="tarifinfosewascoutdoor">
                                                            <tr>
                                                                <td class="tdcolor" style="width: 35%">Harga awal setelah ppn</td>
                                                                <td class="tdcolor" style="width: 20%">Discount</td>
                                                                <td class="tdcolor" style="width: 45%">Harga akhir setelah ppn (after disc)</td>
                                                            </tr>
                                                            <?
                                                            foreach ($arrlokasi as $k => $v)
                                                            {
                                                                // $vkeyid= $v["trloodetilid"];
                                                                $vkeyid= $v["vid"];
                                                                // $vlabel= $v["kode"]." - ".$v["nama"];
                                                                $vlabel= $v["kode"]." - ".$v["lantai"];
                                                                $vluas= $v["vnilai"];
                                                                $vmode= $v["vmode"];
                                                                if($vmode == "luas_sewa_outdoor")
                                                                {
                                                            ?>
                                                            <tr class="grouplokasiclass<?=$reqLokasiLooId?> groupclass<?=$vkeyid?>">
                                                                <td colspan="4"><?=$vlabel?></td>
                                                            </tr>
                                                            <tr class="grouplokasiclass<?=$reqLokasiLooId?> groupclass<?=$vkeyid?>">
                                                                <td>
                                                                    <?
                                                                    $valnilai= 0;
                                                                    $valmode= "tarif_sewa_sc_outdoor";
                                                                    $infocarikey= $vkeyid."-".$valmode;
                                                                    $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrdetil);
                                                                    if(!empty($arrkondisicheck))
                                                                    {
                                                                        $vindex= $arrkondisicheck[0];
                                                                        $valnilai= $arrdetil[$vindex]["vnilai"];
                                                                    }
                                                                    ?>
                                                                    <input type="hidden" name="vmode[]" value="<?=$valmode?>" />
                                                                    <input type="hidden" name="vid[]" class="valsetid" value="<?=$vkeyid?>" />
                                                                    <input type="hidden" name="vketerangan[]" />
                                                                    <input type="hidden" class="totalsewascoutdoor" name="vnilai[]" value="<?=numberToIna($valnilai)?>" />

                                                                    <?
                                                                    $valnilai= 0;
                                                                    $valmode= "tarif_sewa_sc_outdoor_after_ppn";
                                                                    $infocarikey= $vkeyid."-".$valmode;
                                                                    $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrdetil);
                                                                    if(!empty($arrkondisicheck))
                                                                    {
                                                                        $vindex= $arrkondisicheck[0];
                                                                        $valnilai= $arrdetil[$vindex]["vnilai"];
                                                                    }
                                                                    ?>
                                                                    <input type="hidden" name="vmode[]" value="<?=$valmode?>" />
                                                                    <input type="hidden" name="vid[]" class="valsetid" value="<?=$vkeyid?>" />
                                                                    <input type="hidden" name="vketerangan[]" />
                                                                    <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control totalsewascoutdoorafterppn" name="vnilai[]" placeholder="Isi Rp/m2" data-options="required:true" style="width:65%; display: inline; text-align: right;" value="<?=numberToIna($valnilai)?>" /> <label class="labeltotal">Rp/m2</label>
                                                                </td>
                                                                <td>
                                                                    <?
                                                                    $valnilai= 0;
                                                                    $valmode= "tarif_sewa_sc_outdoor_diskon";
                                                                    $infocarikey= $vkeyid."-".$valmode;
                                                                    $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrdetil);
                                                                    if(!empty($arrkondisicheck))
                                                                    {
                                                                        $vindex= $arrkondisicheck[0];
                                                                        $valnilai= $arrdetil[$vindex]["vnilai"];
                                                                    }
                                                                    ?>
                                                                    <input type="hidden" name="vmode[]" value="<?=$valmode?>" />
                                                                    <input type="hidden" name="vid[]" class="valsetid" value="<?=$vkeyid?>" />
                                                                    <input type="hidden" name="vketerangan[]" />
                                                                    <input type="text" class="vlxuangclass easyui-validatebox textbox form-control totalsewascoutdoordiskon" name="vnilai[]" placeholder="Isi %" data-options="required:true" style="width:65%; display: inline; text-align: right;" value="<?=numberToIna($valnilai)?>" /> <label class="labeltotal">%</label>

                                                                    <?
                                                                    $valnilai= 0;
                                                                    $valmode= "tarif_sewa_sc_outdoor_after_diskon";
                                                                    $infocarikey= $vkeyid."-".$valmode;
                                                                    $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrdetil);
                                                                    if(!empty($arrkondisicheck))
                                                                    {
                                                                        $vindex= $arrkondisicheck[0];
                                                                        $valnilai= $arrdetil[$vindex]["vnilai"];
                                                                    }
                                                                    ?>
                                                                    <input type="hidden" name="vmode[]" value="<?=$valmode?>" />
                                                                    <input type="hidden" name="vid[]" class="valsetid" value="<?=$vkeyid?>" />
                                                                    <input type="hidden" name="vketerangan[]" />
                                                                    <input type="hidden" class="totalsewascoutdoorafterdiskon" name="vnilai[]" value="<?=numberToIna($valnilai)?>" />
                                                                </td>
                                                                <td>
                                                                    <?
                                                                    $valnilai= 0;
                                                                    $valmode= "tarif_sewa_sc_outdoor_after_ppn_diskon";
                                                                    $infocarikey= $vkeyid."-".$valmode;
                                                                    $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrdetil);
                                                                    if(!empty($arrkondisicheck))
                                                                    {
                                                                        $vindex= $arrkondisicheck[0];
                                                                        $valnilai= $arrdetil[$vindex]["vnilai"];
                                                                    }
                                                                    ?>
                                                                    <input type="hidden" name="vmode[]" value="<?=$valmode?>" />
                                                                    <input type="hidden" name="vid[]" class="valsetid" value="<?=$vkeyid?>" />
                                                                    <input type="hidden" name="vketerangan[]" />
                                                                    <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control totalsewascoutdoorafterppndiskon" name="vnilai[]" placeholder="Isi Rp/m2" data-options="required:true" style="width:65%; display: inline; text-align: right;" value="<?=numberToIna($valnilai)?>" /> <label class="labeltotal">Rp/m2</label>
                                                                </td>
                                                            </tr>
                                                            <?
                                                                }
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </fieldset>

                                <fieldset>
                                    <legend style="font-size: large;">
                                        Harga
                                    </legend>

                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td style="width: 25%; text-align: right;">
                                                    Sewa Indoor
                                                    <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" name="reqHargaIndoorSewa" id="reqHargaIndoorSewa" style="width:40%; display: inline; text-align: right;" value="<?=numberToIna($reqHargaIndoorSewa)?>" /> <label class="labelsumtotal">/ m2 / bulan</label>
                                                </td>
                                                <td style="width: 25%; text-align: right;">
                                                    Sewa Outdoor
                                                    <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" name="reqHargaOutdoorSewa" id="reqHargaOutdoorSewa" style="width:40%; display: inline; text-align: right;" value="<?=numberToIna($reqHargaOutdoorSewa)?>" /> <label class="labelsumtotal">/ m2 / bulan</label>
                                                </td>
                                                <td style="width: 25%; text-align: right;">
                                                    Service Charge Indoor
                                                    <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" name="reqHargaIndoorService" id="reqHargaIndoorService" style="width:40%; display: inline; text-align: right;" value="<?=numberToIna($reqHargaIndoorService)?>" /> <label class="labelsumtotal">/ m2 / bulan</label>
                                                </td>
                                                <td style="width: 25%; text-align: right;">
                                                    Service Charge Outdoor
                                                    <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" name="reqHargaOutdoorService" id="reqHargaOutdoorService" style="width:40%; display: inline; text-align: right;" value="<?=numberToIna($reqHargaOutdoorService)?>" /> <label class="labelsumtotal">/ m2 / bulan</label>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </fieldset>

                                <fieldset>
                                    <legend style="font-size: large;">
                                        Harga Utility Charge
                                    </legend>

                                    <table class="table">
                                        <tbody id="hargautilitycharge">
                                            <?
                                            foreach ($arrdetil as $k => $v)
                                            {
                                                $valid= $v["vid"];
                                                $valnilai= $v["vnilai"];
                                                $valketerangan= $v["vketerangan"];
                                                $vmode= $v["vmode"];
                                                $valmode= "harga_utility_charge";
                                                if($vmode == $valmode)
                                                {
                                                    $vnama= "";
                                                    $infocarikey= $valid;
                                                    $arrkondisicheck= in_array_column($infocarikey, "id", $arrutilitycharge);
                                                    if(!empty($arrkondisicheck))
                                                    {
                                                        $vindex= $arrkondisicheck[0];
                                                        $vnama= $arrutilitycharge[$vindex]["nama"];
                                                    }
                                            ?>
                                                    <tr class="grouplokasiclass<?=$reqLokasiLooId?>">
                                                        <td><?=$vnama?></td>
                                                        <td>:</td>
                                                        <td style="width:30%">
                                                            <input type="hidden" name="vmode[]" value="<?=$valmode?>" />
                                                            <input type="hidden" name="vid[]" class="valsetid" value="<?=$valid?>" />
                                                            <input type="hidden" name="vketerangan[]" value="<?=$valketerangan?>" />
                                                            <input type="text" class="vlxuangclass easyui-validatebox textbox form-control" name="vnilai[]" placeholder="Isi (<?=$valketerangan?>)" data-options="required:true" style="width:85%; display: inline; text-align: right;" value="<?=numberToIna($valnilai)?>" /> <label class="labeltotal"><?=$valketerangan?></label>
                                                        </td>
                                                    </tr>
                                            <?
                                                }
                                            }
                                            ?>
                                        </tbody>
                                    </table>

                                </fieldset>

                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th colspan="6">JANGKA WAKTU</th>
                                        </tr>
                                        <tr>
                                            <td colspan="6">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <td style="width: 20%">Tanggal</td>
                                                            <td style="width: 2%">:</td>
                                                            <td colspan="5">
                                                                <input type="text" id="reqTanggalAwal" class="easyui-datebox textbox form-control" required name="reqTanggalAwal" value="<?=$reqTanggalAwal?>" data-options="required:true" style="width:100%; height:30px" />
                                                                &nbsp;&nbsp;s/d&nbsp;&nbsp;
                                                                <input type="text" id="reqTanggalAkhir" class="easyui-datebox textbox form-control" required name="reqTanggalAkhir" value="<?=$reqTanggalAkhir?>" data-options="required:true" style="width:100%; height:30px" />
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Promotion Levy</td>
                                                            <td>:</td>
                                                            <td colspan="5">
                                                                <input type="text" id="reqPromotionLevy" class="vlxuangclass easyui-validatebox textbox form-control" required name="reqPromotionLevy" value="<?=numberToIna($reqPromotionLevy)?>" data-options="required:true" style="width:60%; display: inline; text-align: right;" /> <label class="labelsumtotal">/ m2 / bulan</label>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Down Payment</td>
                                                            <td>:</td>
                                                            <td>
                                                                <input type="text" id="reqDp" class="vlxuangclass easyui-validatebox textbox form-control" required name="reqDp" value="<?=numberToIna($reqDp)?>" data-options="required:true" style="width:60%; display: inline; text-align: right;" /> <label class="labeltotal">%</label>
                                                            </td>
                                                            <td>Periode Sewa</td>
                                                            <td>:</td>
                                                            <td>
                                                                <input type="text" id="reqPeriodeSewa" class="vlxuangclass easyui-validatebox textbox form-control" required name="reqPeriodeSewa" value="<?=numberToIna($reqPeriodeSewa)?>" data-options="required:true" style="width:60%; display: inline; text-align: right;" /> <label class="labeltotal">bulan</label>
                                                            </td>
                                                            <td style="width: 20%">TOP</td>
                                                            <td style="width: 2%">:</td>
                                                            <td>
                                                                <input type="text" id="reqTop" class="vlxuangclass easyui-validatebox textbox form-control" required name="reqTop" value="<?=numberToIna($reqTop)?>" data-options="required:true" style="width:60%; display: inline; text-align: right;" /> <label class="labeltotal">bulan</label>
                                                            </td>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th colspan="6">Jam Operasional</th>
                                        </tr>
                                        <tr>
                                            <td style="width: 28%">Gedung</td>
                                            <td style="width: 2%">:</td>
                                            <td style="width: 20%">
                                                <?
                                                $valketerangan= "10:00 s/d 22:00";
                                                $vkeyid= 1;
                                                $valmode= "jam_operasional_gedung";
                                                $infocarikey= $vkeyid."-".$valmode;
                                                $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrdetil);
                                                if(!empty($arrkondisicheck))
                                                {
                                                    $vindex= $arrkondisicheck[0];
                                                    $valketerangan= $arrdetil[$vindex]["vketerangan"];
                                                }
                                                ?>
                                                <input type="hidden" name="vmode[]" value="<?=$valmode?>" />
                                                <input type="hidden" name="vid[]" class="valsetid" value="<?=$vkeyid?>" />
                                                <input type="hidden" name="vnilai[]" value="" />
                                                <input type="text" class="easyui-validatebox textbox form-control" required name="vketerangan[]" value="<?=$valketerangan?>" data-options="required:true" style="width:90%; display: inline; text-align: right;" />
                                            </td>
                                            <td style="width: 28%">Tenant</td>
                                            <td style="width: 2%">:</td>
                                            <td style="width: 20%">
                                                <?
                                                $valketerangan= "10:00 s/d 22:00";
                                                $vkeyid= 2;
                                                $valmode= "jam_operasional_tenan";
                                                $infocarikey= $vkeyid."-".$valmode;
                                                $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrdetil);
                                                if(!empty($arrkondisicheck))
                                                {
                                                    $vindex= $arrkondisicheck[0];
                                                    $valketerangan= $arrdetil[$vindex]["vketerangan"];
                                                }
                                                ?>
                                                <input type="hidden" name="vmode[]" value="<?=$valmode?>" />
                                                <input type="hidden" name="vid[]" class="valsetid" value="<?=$vkeyid?>" />
                                                <input type="hidden" name="vnilai[]" value="" />
                                                <input type="text" class="easyui-validatebox textbox form-control" required name="vketerangan[]" value="<?=$valketerangan?>" data-options="required:true" style="width:90%; display: inline; text-align: right;" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <th colspan="6">RINCIAN NILAI SEWA</th>
                                        </tr>
                                        <tr>
                                            <td colspan="6">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <td style="width: 30%; text-align: right;">KETERANGAN</td>
                                                            <td style="width: 20%; text-align: right;">BIAYA SATUAN</td>
                                                            <td style="width: 20%; text-align: right;">PER BULAN</td>
                                                            <td style="text-align: right;">TOTAL</td>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>Harga Sewa Unit</td>
                                                            <td>
                                                                <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" name="reqSewaBiayaSatuanUnit" id="reqSewaBiayaSatuanUnit" style="display: inline; text-align: right;" value="<?=numberToIna($reqSewaBiayaSatuanUnit)?>" />
                                                            </td>
                                                            <td>
                                                                <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" name="reqSewaBiayaPerBulanUnit" id="reqSewaBiayaPerBulanUnit" style="display: inline; text-align: right;" value="<?=numberToIna($reqSewaBiayaPerBulanUnit)?>" />
                                                            </td>
                                                            <td>
                                                                <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" name="reqSewaTotalBiayaUnit" id="reqSewaTotalBiayaUnit" style="display: inline; text-align: right;" value="<?=numberToIna($reqSewaTotalBiayaUnit)?>" />
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Service Charge Tahun Pertama</td>
                                                            <td>
                                                                <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" name="reqSewaBiayaSatuanService" id="reqSewaBiayaSatuanService" style="display: inline; text-align: right;" value="<?=numberToIna($reqSewaBiayaSatuanService)?>" />
                                                            </td>
                                                            <td>
                                                                <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" name="reqSewaBiayaPerBulanService" id="reqSewaBiayaPerBulanService" style="display: inline; text-align: right;" value="<?=numberToIna($reqSewaBiayaPerBulanService)?>" />
                                                            </td>
                                                            <td>
                                                                <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" name="reqSewaTotalBiayaService" id="reqSewaTotalBiayaService" style="display: inline; text-align: right;" value="<?=numberToIna($reqSewaTotalBiayaService)?>" />
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Total (Tanpa PPN)</td>
                                                            <td></td>
                                                            <td>
                                                                <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" name="reqTotalBiayaPerBulanNoPpn" id="reqTotalBiayaPerBulanNoPpn" style="display: inline; text-align: right;" value="<?=numberToIna($reqTotalBiayaPerBulanNoPpn)?>" />
                                                            </td>
                                                            <td>
                                                                <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" name="reqTotalBiayaNoPpn" id="reqTotalBiayaNoPpn" style="display: inline; text-align: right;" value="<?=numberToIna($reqTotalBiayaNoPpn)?>" />
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Total (Dengan PPN)</td>
                                                            <td></td>
                                                            <td>
                                                                <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" name="reqTotalBiayaPerBulanPpn" id="reqTotalBiayaPerBulanPpn" style="display: inline; text-align: right;" value="<?=numberToIna($reqTotalBiayaPerBulanPpn)?>" />
                                                            </td>
                                                            <td>
                                                                <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" name="reqTotalBiayaPpn" id="reqTotalBiayaPpn" style="display: inline; text-align: right;" value="<?=numberToIna($reqTotalBiayaPpn)?>" />
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Jaminan Sewa (Security Deposit)</td>
                                            <td>:</td>
                                            <td>
                                                <input type="text" readonly id="reqSecurityDeposit" class="vlxuangclass easyui-validatebox textbox form-control" required name="reqSecurityDeposit" value="<?=numberToIna($reqSecurityDeposit)?>" data-options="required:true" style="display: inline; text-align: right;" />
                                            </td>
                                            <td>Fitting Out</td>
                                            <td>:</td>
                                            <td>
                                                <input type="text" id="reqFittingOut" class="vlxuangclass easyui-validatebox textbox form-control" required name="reqFittingOut" value="<?=numberToIna($reqFittingOut)?>" data-options="required:true" style="display: inline; text-align: right;" />
                                            </td>
                                        </tr>
                                    </thead>
                                </table>

                                <?
                                if(!empty($arrlog))
                                {
                                ?>
                                <table class="table">
                                    <thead class="thead-light">
                                        <tr class="active">
                                            <th colspan="2">Riwayat Konsep Surat</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="2">
                                                <ol class="list-unstyled">
                                                    <?
                                                    foreach ($arrlog as $k => $v)
                                                    {
                                                        $vinfologdetil= $v["TANGGAL"].", ".$v["INFORMASI"].", [".$v["STATUS_SURAT"]."].";
                                                        $vinfologcatatan= $v["CATATAN"];
                                                    ?>
                                                    <li>
                                                        <span><?=$vinfologdetil?></span>
                                                    </li>
                                                    <li>
                                                        <span><?=$vinfologcatatan?></span>
                                                    </li>
                                                    <li><span><br/></span></li>
                                                    <?
                                                    }
                                                    ?>
                                                </ol>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <?
                                }
                                ?>
                            </div>

                        </div>

                        <div id="tab-loi" class="tab-pane fade">

                            <table class="table">
                                <thead>
                                    <tr>           
                                        <td>Lokasi</td>
                                        <td>:</td>
                                        <td>
                                            <input disabled type="text" class="easyui-combotree"
                                            data-options="
                                            onClick: function(node){
                                                sethargautilitycharge(node);
                                            }
                                            , width:'350'
                                            , valueField:'id'
                                            , textField:'text'
                                            , editable:false
                                            , url:'combo_json/comboLokasiLoo'
                                            " required value="<?=$reqLoiLokasiLooId?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Customer</td>
                                        <td>:</td>
                                        <td>
                                            <input disabled type="text" class="easyui-combobox" data-options="width:'350', valueField:'id', textField:'text', editable:false, url:'combo_json/comboCustomer?cek=pemilik'" required value="<?=$reqLoiCustomerId?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Produk</td>
                                        <td>:</td>
                                        <td>
                                            <input disabled type="text" class="easyui-combobox" data-options="width:'350', valueField:'id', textField:'text', editable:false, url:'combo_json/comboProduk'" required value="<?=$reqLoiProdukId?>" />
                                            &nbsp;&nbsp;PPN
                                            <input readonly type="text" class="vlxuangclass easyui-validatebox textbox form-control" style="width:5%; padding: initial; display: inline; text-align: right;" value="<?=numberToIna($reqLoiPph)?>" />           
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Penanda tangan <span class="text-danger">*</span></td>
                                        <td>:</td>
                                        <td>
                                            <?=$infoloisatuankerjainfo?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Pemeriksa
                                        </td>
                                        <td>:</td>
                                        <td>
                                            <div class="inner">
                                                <?
                                                if(!empty($reqTrLoiId))
                                                {
                                                    $setinfoparaf= new TrLoiParaf();
                                                    $setinfoparaf->selectByParams(array(), -1, -1, " AND A.STATUS_BANTU IS NULL AND A.TR_LOI_ID = ".$reqTrLoiId, "ORDER BY A.NO_URUT");
                                                    while($setinfoparaf->nextRow())
                                                    {
                                                        $valparafnama= $setinfoparaf->getField("NAMA_SATKER");
                                                        $valparafid= $setinfoparaf->getField("SATUAN_KERJA_ID_TUJUAN");
                                                ?>
                                                    <div class="item">PARAF: <?=$valparafnama?></div>
                                                <?
                                                    }
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
                                                        <?
                                                        $set_attachement = new TrLoi();
                                                        $set_attachement->selectByParamsAttachment(array("A.TR_LOI_ID" => (int)$reqTrLoiId));
                                                        while ($set_attachement->nextRow()) {
                                                            $attach_id= $set_attachement->getField("TR_LOI_ATTACHMENT_ID");
                                                        ?>
                                                            
                                                            <div class="MultiFile-label">
                                                                <?
                                                                $arrexcept= array("xlsx", "xls", "doc", "docx", "ppt", "pptx", "txt");
                                                                //$arrexcept= array("xlsx", "xls", "doc", "docx", "txt");
                                                                if(in_array(strtolower($set_attachement->getField("TIPE")), $arrexcept))
                                                                {
                                                                ?>
                                                                <?= $set_attachement->getField("NAMA") ?>
                                                                <a onClick="down('<?=$attach_id?>')" >
                                                                    <i style="cursor: pointer;" class="fa fa-download" ></i>
                                                                </a>
                                                                <?
                                                                }
                                                                else
                                                                {
                                                                ?>
                                                                <?= $set_attachement->getField("NAMA") ?>
                                                                <a onClick="parent.openAdd('<?= base_url()."uploadsloo/".$reqTrLoiId."/".$set_attachement->getField("ATTACHMENT") ?>')" >
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

                                </thead>
                            </table>

                            <fieldset>
                                <legend style="font-size: large;">
                                    Luas Sewa
                                </legend>

                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td class="tdcolor">Indoor</td>
                                            <td class="tdcolor">Outdoor</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 50%">
                                                <table class="table">
                                                    <tbody>
                                                        <?
                                                        foreach ($arrloilokasi as $k => $v)
                                                        {
                                                            // $vkeyid= $v["trloodetilid"];
                                                            // $vkeyid= $v["trlooid"];
                                                            $vkeyid= $v["vid"];
                                                            // $vlabel= $v["kode"]." - ".$v["nama"];
                                                            $vlabel= $v["kode"]." - ".$v["lantai"];
                                                            $vnilai= $v["vnilai"];
                                                            $vmode= $v["vmode"];
                                                            if($vmode == "luas_sewa_indoor")
                                                            {
                                                        ?>
                                                        <tr>
                                                            <td>
                                                                <?=$vlabel?>
                                                            </td>
                                                            <td>:</td>
                                                            <td style="width:30%">
                                                                <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" placeholder="Isi Luas (m2)" style="width:85%; display: inline; text-align: right;" value="<?=numberToIna($vnilai)?>" /> <label class="labeltotal">m2</label>'
                                                            </td>
                                                        </tr>
                                                        <?
                                                            }
                                                        }
                                                        ?>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="2" style="text-align: right;">Total Luas Indoor</td>
                                                            <td style="width: 20%">
                                                                <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" style="width:85%; display: inline; text-align: right;" value="<?=numberToIna($reqLoiTotalLuasIndoor)?>" /> <label class="labeltotal">m2</label>
                                                            </td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </td>
                                            <td style="width: 50%">
                                                <table class="table">
                                                    <tbody>
                                                        <?
                                                        foreach ($arrloilokasi as $k => $v)
                                                        {
                                                            // $vkeyid= $v["trloodetilid"];
                                                            // $vkeyid= $v["trlooid"];
                                                            $vkeyid= $v["vid"];
                                                            // $vlabel= $v["kode"]." - ".$v["nama"];
                                                            $vlabel= $v["kode"]." - ".$v["lantai"];
                                                            $vnilai= $v["vnilai"];
                                                            $vmode= $v["vmode"];
                                                            if($vmode == "luas_sewa_outdoor")
                                                            {
                                                        ?>
                                                        <tr>
                                                            <td>
                                                                <?=$vlabel?>
                                                            </td>
                                                            <td>:</td>
                                                            <td style="width:30%">
                                                                <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" placeholder="Isi Luas (m2)" style="width:85%; display: inline; text-align: right;" value="<?=numberToIna($vnilai)?>" /> <label class="labeltotal">m2</label>'
                                                            </td>
                                                        </tr>
                                                        <?
                                                            }
                                                        }
                                                        ?>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="2" style="text-align: right;">Total Luas Outdoor</td>
                                                            <td style="width: 20%">
                                                                <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" style="width:85%; display: inline; text-align: right;" value="<?=numberToIna($reqLoiTotalLuasOutdoor)?>" /> <label class="labeltotal">m2</label>
                                                            </td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="text-align: right;">
                                                Total Luas Sewa
                                                <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" style="width:15%; display: inline; text-align: right;" value="<?=numberToIna($reqLoiTotalLuas)?>" /> <label class="labeltotal">m2</label>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </fieldset>

                            <fieldset>
                                <legend style="font-size: large;">
                                    Tarif Sewa
                                </legend>
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td colspan="3" class="tdcolor" style="width: 50%">Indoor</td>
                                            <td colspan="3" class="tdcolor" style="width: 50%">Outdoor</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3">
                                                <table class="table">
                                                    <tbody>
                                                        <tr>
                                                            <td class="tdcolor" style="width: 27%">Unit</td>
                                                            <td class="tdcolor" style="width: 15%">Discount</td>
                                                            <td class="tdcolor" style="width: 29%">Tarif (after discount)</td>
                                                            <td class="tdcolor" style="width: 24%">Harga Sewa</td>
                                                        </tr>
                                                        <?
                                                        foreach ($arrloilokasi as $k => $v)
                                                        {
                                                            // $vkeyid= $v["trloodetilid"];
                                                            // $vkeyid= $v["trlooid"];
                                                            $vkeyid= $v["vid"];
                                                            // $vlabel= $v["kode"]." - ".$v["nama"];
                                                            $vlabel= $v["kode"]." - ".$v["lantai"];
                                                            $vluas= $v["vnilai"];
                                                            $vmode= $v["vmode"];
                                                            if($vmode == "luas_sewa_indoor")
                                                            {
                                                        ?>
                                                        <tr>
                                                            <td colspan="4"><?=$vlabel?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <?
                                                                $valnilai= 0;
                                                                $valmode= "tarif_sewa_unit_indoor";
                                                                $infocarikey= $vkeyid."-".$valmode;
                                                                $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrloidetil);
                                                                if(!empty($arrkondisicheck))
                                                                {
                                                                    $vindex= $arrkondisicheck[0];
                                                                    $valnilai= $arrloidetil[$vindex]["vnilai"];
                                                                }
                                                                ?>
                                                                <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" name="vnilai[]" placeholder="Isi Rp/m2" style="width:65%; display: inline; text-align: right;" value="<?=numberToIna($valnilai)?>" /> <label class="labeltotal">Rp/m2</label>
                                                            </td>
                                                            <td>
                                                                <?
                                                                $valnilai= 0;
                                                                $valmode= "tarif_sewa_unit_indoor_diskon";
                                                                $infocarikey= $vkeyid."-".$valmode;
                                                                $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrloidetil);
                                                                if(!empty($arrkondisicheck))
                                                                {
                                                                    $vindex= $arrkondisicheck[0];
                                                                    $valnilai= $arrloidetil[$vindex]["vnilai"];
                                                                }
                                                                ?>
                                                                <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" placeholder="Isi %" style="width:65%; display: inline; text-align: right;" value="<?=numberToIna($valnilai)?>" /> <label class="labeltotal">%</label>
                                                            </td>
                                                            <td>
                                                                <?
                                                                $valnilai= 0;
                                                                $valmode= "tarif_sewa_unit_indoor_after_diskon";
                                                                $infocarikey= $vkeyid."-".$valmode;
                                                                $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrloidetil);
                                                                if(!empty($arrkondisicheck))
                                                                {
                                                                    $vindex= $arrkondisicheck[0];
                                                                    $valnilai= $arrloidetil[$vindex]["vnilai"];
                                                                }
                                                                ?>
                                                                <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" placeholder="Isi Rp/m2" style="width:65%; display: inline; text-align: right;" value="<?=numberToIna($valnilai)?>" /> <label class="labeltotal">Rp/m2</label>
                                                            </td>
                                                            <td>
                                                                <?
                                                                $valnilai= 0;
                                                                $valmode= "tarif_sewa_unit_indoor_harga";
                                                                $infocarikey= $vkeyid."-".$valmode;
                                                                $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrloidetil);
                                                                if(!empty($arrkondisicheck))
                                                                {
                                                                    $vindex= $arrkondisicheck[0];
                                                                    $valnilai= $arrloidetil[$vindex]["vnilai"];
                                                                }
                                                                ?>
                                                                <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" style="display: inline; text-align: right;" value="<?=numberToIna($valnilai)?>" />
                                                            </td>
                                                        </tr>
                                                        <?
                                                            }
                                                        }
                                                        ?>

                                                    </tbody>
                                                </table>
                                            </td>
                                            <td colspan="3">
                                                <table class="table">
                                                    <tbody>
                                                        <tr>
                                                            <td class="tdcolor" style="width: 27%">Unit</td>
                                                            <td class="tdcolor" style="width: 15%">Discount</td>
                                                            <td class="tdcolor" style="width: 29%">Tarif (after discount)</td>
                                                            <td class="tdcolor" style="width: 24%">Harga Sewa</td>
                                                        </tr>
                                                        <?
                                                        foreach ($arrloilokasi as $k => $v)
                                                        {
                                                            // $vkeyid= $v["trloodetilid"];
                                                            // $vkeyid= $v["trlooid"];
                                                            $vkeyid= $v["vid"];
                                                            // $vlabel= $v["kode"]." - ".$v["nama"];
                                                            $vlabel= $v["kode"]." - ".$v["lantai"];
                                                            $vluas= $v["vnilai"];
                                                            $vmode= $v["vmode"];
                                                            if($vmode == "luas_sewa_outdoor")
                                                            {
                                                        ?>
                                                        <tr>
                                                            <td colspan="4"><?=$vlabel?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <?
                                                                $valnilai= 0;
                                                                $valmode= "tarif_sewa_unit_outdoor";
                                                                $infocarikey= $vkeyid."-".$valmode;
                                                                $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrloidetil);
                                                                if(!empty($arrkondisicheck))
                                                                {
                                                                    $vindex= $arrkondisicheck[0];
                                                                    $valnilai= $arrloidetil[$vindex]["vnilai"];
                                                                }
                                                                ?>
                                                                <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" placeholder="Isi Rp/m2" style="width:65%; display: inline; text-align: right;" value="<?=numberToIna($valnilai)?>" /> <label class="labeltotal">Rp/m2</label>
                                                            </td>
                                                            <td>
                                                                <?
                                                                $valnilai= 0;
                                                                $valmode= "tarif_sewa_unit_outdoor_diskon";
                                                                $infocarikey= $vkeyid."-".$valmode;
                                                                $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrloidetil);
                                                                if(!empty($arrkondisicheck))
                                                                {
                                                                    $vindex= $arrkondisicheck[0];
                                                                    $valnilai= $arrloidetil[$vindex]["vnilai"];
                                                                }
                                                                ?>
                                                                <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" placeholder="Isi %" style="width:65%; display: inline; text-align: right;" value="<?=numberToIna($valnilai)?>" /> <label class="labeltotal">%</label>
                                                            </td>
                                                            <td>
                                                                <?
                                                                $valnilai= 0;
                                                                $valmode= "tarif_sewa_unit_outdoor_after_diskon";
                                                                $infocarikey= $vkeyid."-".$valmode;
                                                                $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrloidetil);
                                                                if(!empty($arrkondisicheck))
                                                                {
                                                                    $vindex= $arrkondisicheck[0];
                                                                    $valnilai= $arrloidetil[$vindex]["vnilai"];
                                                                }
                                                                ?>
                                                                <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" placeholder="Isi Rp/m2" style="width:65%; display: inline; text-align: right;" value="<?=numberToIna($valnilai)?>" /> <label class="labeltotal">Rp/m2</label>
                                                            </td>
                                                            <td>
                                                                <?
                                                                $valnilai= 0;
                                                                $valmode= "tarif_sewa_unit_outdoor_harga";
                                                                $infocarikey= $vkeyid."-".$valmode;
                                                                $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrloidetil);
                                                                if(!empty($arrkondisicheck))
                                                                {
                                                                    $vindex= $arrkondisicheck[0];
                                                                    $valnilai= $arrloidetil[$vindex]["vnilai"];
                                                                }
                                                                ?>
                                                                <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" style="display: inline; text-align: right;" value="<?=numberToIna($valnilai)?>" />
                                                            </td>
                                                        </tr>
                                                        <?
                                                            }
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th colspan="6">Service Charge</th>
                                        </tr>
                                        <tr>
                                            <td colspan="3">
                                                <table class="table">
                                                    <tbody>
                                                        <tr>
                                                            <td class="tdcolor" style="width: 35%">Harga awal setelah ppn</td>
                                                            <td class="tdcolor" style="width: 20%">Discount</td>
                                                            <td class="tdcolor" style="width: 45%">Harga akhir setelah ppn (after disc)</td>
                                                        </tr>
                                                        <?
                                                        foreach ($arrloilokasi as $k => $v)
                                                        {
                                                            // $vkeyid= $v["trloodetilid"];
                                                            $vkeyid= $v["vid"];
                                                            // $vlabel= $v["kode"]." - ".$v["nama"];
                                                            $vlabel= $v["kode"]." - ".$v["lantai"];
                                                            $vluas= $v["vnilai"];
                                                            $vmode= $v["vmode"];
                                                            if($vmode == "luas_sewa_indoor")
                                                            {
                                                        ?>
                                                        <tr>
                                                            <td colspan="4"><?=$vlabel?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <?
                                                                $valnilai= 0;
                                                                $valmode= "tarif_sewa_sc_indoor";
                                                                $infocarikey= $vkeyid."-".$valmode;
                                                                $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrloidetil);
                                                                if(!empty($arrkondisicheck))
                                                                {
                                                                    $vindex= $arrkondisicheck[0];
                                                                    $valnilai= $arrloidetil[$vindex]["vnilai"];
                                                                }
                                                                ?>


                                                                <?
                                                                $valnilai= 0;
                                                                $valmode= "tarif_sewa_sc_indoor_after_ppn";
                                                                $infocarikey= $vkeyid."-".$valmode;
                                                                $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrloidetil);
                                                                if(!empty($arrkondisicheck))
                                                                {
                                                                    $vindex= $arrkondisicheck[0];
                                                                    $valnilai= $arrloidetil[$vindex]["vnilai"];
                                                                }
                                                                ?>
                                                                <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" placeholder="Isi Rp/m2" style="width:65%; display: inline; text-align: right;" value="<?=numberToIna($valnilai)?>" /> <label class="labeltotal">Rp/m2</label>
                                                            </td>
                                                            <td>
                                                                <?
                                                                $valnilai= 0;
                                                                $valmode= "tarif_sewa_sc_indoor_diskon";
                                                                $infocarikey= $vkeyid."-".$valmode;
                                                                $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrloidetil);
                                                                if(!empty($arrkondisicheck))
                                                                {
                                                                    $vindex= $arrkondisicheck[0];
                                                                    $valnilai= $arrloidetil[$vindex]["vnilai"];
                                                                }
                                                                ?>
                                                                <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" placeholder="Isi %" style="width:65%; display: inline; text-align: right;" value="<?=numberToIna($valnilai)?>" /> <label class="labeltotal">%</label>

                                                                <?
                                                                $valnilai= 0;
                                                                $valmode= "tarif_sewa_sc_indoor_after_diskon";
                                                                $infocarikey= $vkeyid."-".$valmode;
                                                                $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrloidetil);
                                                                if(!empty($arrkondisicheck))
                                                                {
                                                                    $vindex= $arrkondisicheck[0];
                                                                    $valnilai= $arrloidetil[$vindex]["vnilai"];
                                                                }
                                                                ?>
                                                            </td>
                                                            <td>
                                                                <?
                                                                $valnilai= 0;
                                                                $valmode= "tarif_sewa_sc_indoor_after_ppn_diskon";
                                                                $infocarikey= $vkeyid."-".$valmode;
                                                                $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrloidetil);
                                                                if(!empty($arrkondisicheck))
                                                                {
                                                                    $vindex= $arrkondisicheck[0];
                                                                    $valnilai= $arrloidetil[$vindex]["vnilai"];
                                                                }
                                                                ?>
                                                                <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" placeholder="Isi Rp/m2" style="width:65%; display: inline; text-align: right;" value="<?=numberToIna($valnilai)?>" /> <label class="labeltotal">Rp/m2</label>
                                                            </td>
                                                        </tr>
                                                        <?
                                                            }
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td colspan="3">
                                                <table class="table">
                                                    <tbody>
                                                        <tr>
                                                            <td class="tdcolor" style="width: 35%">Harga awal setelah ppn</td>
                                                            <td class="tdcolor" style="width: 20%">Discount</td>
                                                            <td class="tdcolor" style="width: 45%">Harga akhir setelah ppn (after disc)</td>
                                                        </tr>
                                                        <?
                                                        foreach ($arrloilokasi as $k => $v)
                                                        {
                                                            // $vkeyid= $v["trloodetilid"];
                                                            $vkeyid= $v["vid"];
                                                            // $vlabel= $v["kode"]." - ".$v["nama"];
                                                            $vlabel= $v["kode"]." - ".$v["lantai"];
                                                            $vluas= $v["vnilai"];
                                                            $vmode= $v["vmode"];
                                                            if($vmode == "luas_sewa_outdoor")
                                                            {
                                                        ?>
                                                        <tr>
                                                            <td colspan="4"><?=$vlabel?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <?
                                                                $valnilai= 0;
                                                                $valmode= "tarif_sewa_sc_outdoor";
                                                                $infocarikey= $vkeyid."-".$valmode;
                                                                $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrloidetil);
                                                                if(!empty($arrkondisicheck))
                                                                {
                                                                    $vindex= $arrkondisicheck[0];
                                                                    $valnilai= $arrloidetil[$vindex]["vnilai"];
                                                                }
                                                                ?>

                                                                <?
                                                                $valnilai= 0;
                                                                $valmode= "tarif_sewa_sc_outdoor_after_ppn";
                                                                $infocarikey= $vkeyid."-".$valmode;
                                                                $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrloidetil);
                                                                if(!empty($arrkondisicheck))
                                                                {
                                                                    $vindex= $arrkondisicheck[0];
                                                                    $valnilai= $arrloidetil[$vindex]["vnilai"];
                                                                }
                                                                ?>
                                                                <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" placeholder="Isi Rp/m2" style="width:65%; display: inline; text-align: right;" value="<?=numberToIna($valnilai)?>" /> <label class="labeltotal">Rp/m2</label>
                                                            </td>
                                                            <td>
                                                                <?
                                                                $valnilai= 0;
                                                                $valmode= "tarif_sewa_sc_outdoor_diskon";
                                                                $infocarikey= $vkeyid."-".$valmode;
                                                                $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrloidetil);
                                                                if(!empty($arrkondisicheck))
                                                                {
                                                                    $vindex= $arrkondisicheck[0];
                                                                    $valnilai= $arrloidetil[$vindex]["vnilai"];
                                                                }
                                                                ?>
                                                                <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" placeholder="Isi %" style="width:65%; display: inline; text-align: right;" value="<?=numberToIna($valnilai)?>" /> <label class="labeltotal">%</label>

                                                                <?
                                                                $valnilai= 0;
                                                                $valmode= "tarif_sewa_sc_outdoor_after_diskon";
                                                                $infocarikey= $vkeyid."-".$valmode;
                                                                $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrloidetil);
                                                                if(!empty($arrkondisicheck))
                                                                {
                                                                    $vindex= $arrkondisicheck[0];
                                                                    $valnilai= $arrloidetil[$vindex]["vnilai"];
                                                                }
                                                                ?>
                                                            </td>
                                                            <td>
                                                                <?
                                                                $valnilai= 0;
                                                                $valmode= "tarif_sewa_sc_outdoor_after_ppn_diskon";
                                                                $infocarikey= $vkeyid."-".$valmode;
                                                                $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrloidetil);
                                                                if(!empty($arrkondisicheck))
                                                                {
                                                                    $vindex= $arrkondisicheck[0];
                                                                    $valnilai= $arrloidetil[$vindex]["vnilai"];
                                                                }
                                                                ?>
                                                                <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" placeholder="Isi Rp/m2" style="width:65%; display: inline; text-align: right;" value="<?=numberToIna($valnilai)?>" /> <label class="labeltotal">Rp/m2</label>
                                                            </td>
                                                        </tr>
                                                        <?
                                                            }
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </fieldset>

                            <fieldset>
                                <legend style="font-size: large;">
                                    Harga
                                </legend>

                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td style="width: 25%; text-align: right;">
                                                Sewa Indoor
                                                <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" style="width:40%; display: inline; text-align: right;" value="<?=numberToIna($reqLoiHargaIndoorSewa)?>" /> <label class="labelsumtotal">/ m2 / bulan</label>
                                            </td>
                                            <td style="width: 25%; text-align: right;">
                                                Sewa Outdoor
                                                <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" style="width:40%; display: inline; text-align: right;" value="<?=numberToIna($reqLoiHargaOutdoorSewa)?>" /> <label class="labelsumtotal">/ m2 / bulan</label>
                                            </td>
                                            <td style="width: 25%; text-align: right;">
                                                Service Charge Indoor
                                                <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" style="width:40%; display: inline; text-align: right;" value="<?=numberToIna($reqLoiHargaIndoorService)?>" /> <label class="labelsumtotal">/ m2 / bulan</label>
                                            </td>
                                            <td style="width: 25%; text-align: right;">
                                                Service Charge Outdoor
                                                <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" style="width:40%; display: inline; text-align: right;" value="<?=numberToIna($reqLoiHargaOutdoorService)?>" /> <label class="labelsumtotal">/ m2 / bulan</label>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </fieldset>

                            <fieldset>
                                <legend style="font-size: large;">
                                    Harga Utility Charge
                                </legend>

                                <table class="table">
                                    <tbody>
                                        <?
                                        foreach ($arrloidetil as $k => $v)
                                        {
                                            $valid= $v["vid"];
                                            $valnilai= $v["vnilai"];
                                            $valketerangan= $v["vketerangan"];
                                            $vmode= $v["vmode"];
                                            $valmode= "harga_utility_charge";
                                            if($vmode == $valmode)
                                            {
                                                $vnama= "";
                                                $infocarikey= $valid;
                                                $arrkondisicheck= in_array_column($infocarikey, "id", $arrutilitycharge);
                                                if(!empty($arrkondisicheck))
                                                {
                                                    $vindex= $arrkondisicheck[0];
                                                    $vnama= $arrutilitycharge[$vindex]["nama"];
                                                }
                                        ?>
                                                <tr>
                                                    <td><?=$vnama?></td>
                                                    <td>:</td>
                                                    <td style="width:30%">
                                                        <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" placeholder="Isi (<?=$valketerangan?>)" style="width:85%; display: inline; text-align: right;" value="<?=numberToIna($valnilai)?>" /> <label class="labeltotal"><?=$valketerangan?></label>
                                                    </td>
                                                </tr>
                                        <?
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>

                            </fieldset>

                            <table class="table">
                                <thead>
                                    <tr>
                                        <td colspan="6">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <td style="width: 20%">Tanggal</td>
                                                        <td style="width: 2%">:</td>
                                                        <td colspan="5">
                                                            <input type="text" disabled class="easyui-datebox textbox form-control" value="<?=$reqLoiTanggalAwal?>" style="width:100%; height:30px" />
                                                            &nbsp;&nbsp;s/d&nbsp;&nbsp;
                                                            <input type="text" disabled class="easyui-datebox textbox form-control" value="<?=$reqLoiTanggalAkhir?>" style="width:100%; height:30px" />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Promotion Levy</td>
                                                        <td>:</td>
                                                        <td colspan="5">
                                                            <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" value="<?=numberToIna($reqLoiPromotionLevy)?>" style="width:60%; display: inline; text-align: right;" /> <label class="labelsumtotal">/ m2 / bulan</label>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Down Payment</td>
                                                        <td>:</td>
                                                        <td>
                                                            <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" value="<?=numberToIna($reqLoiDp)?>" style="width:60%; display: inline; text-align: right;" /> <label class="labeltotal">%</label>
                                                        </td>
                                                        <td>Periode Sewa</td>
                                                        <td>:</td>
                                                        <td>
                                                            <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" value="<?=numberToIna($reqLoiPeriodeSewa)?>" style="width:60%; display: inline; text-align: right;" /> <label class="labeltotal">bulan</label>
                                                        </td>
                                                        <td style="width: 20%">TOP</td>
                                                        <td style="width: 2%">:</td>
                                                        <td>
                                                            <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" value="<?=numberToIna($reqLoiTop)?>" style="width:60%; display: inline; text-align: right;" /> <label class="labeltotal">bulan</label>
                                                        </td>
                                                    </tr>
                                                </thead>
                                            </table>

                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="6">Jam Operasional</th>
                                    </tr>
                                    <tr>
                                        <td style="width: 28%">Gedung</td>
                                        <td style="width: 2%">:</td>
                                        <td style="width: 20%">
                                            <?
                                            $valketerangan= "10:00 s/d 22:00";
                                            $vkeyid= 1;
                                            $valmode= "jam_operasional_gedung";
                                            $infocarikey= $vkeyid."-".$valmode;
                                            $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrloidetil);
                                            if(!empty($arrkondisicheck))
                                            {
                                                $vindex= $arrkondisicheck[0];
                                                $valketerangan= $arrloidetil[$vindex]["vketerangan"];
                                            }
                                            ?>
                                            <input type="text" readonly class="easyui-validatebox textbox form-control" required name="vketerangan[]" value="<?=$valketerangan?>" data-options="required:true" style="width:90%; display: inline; text-align: right;" />
                                        </td>
                                        <td style="width: 28%">Tenant</td>
                                        <td style="width: 2%">:</td>
                                        <td style="width: 20%">
                                            <?
                                            $valketerangan= "10:00 s/d 22:00";
                                            $vkeyid= 2;
                                            $valmode= "jam_operasional_tenan";
                                            $infocarikey= $vkeyid."-".$valmode;
                                            $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrloidetil);
                                            if(!empty($arrkondisicheck))
                                            {
                                                $vindex= $arrkondisicheck[0];
                                                $valketerangan= $arrloidetil[$vindex]["vketerangan"];
                                            }
                                            ?>
                                            <input type="text" readonly class="easyui-validatebox textbox form-control" required name="vketerangan[]" value="<?=$valketerangan?>" data-options="required:true" style="width:90%; display: inline; text-align: right;" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="6">RINCIAN NILAI SEWA</th>
                                    </tr>
                                    <tr>
                                        <td colspan="6">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <td style="width: 30%; text-align: right;">KETERANGAN</td>
                                                        <td style="width: 20%; text-align: right;">BIAYA SATUAN</td>
                                                        <td style="width: 20%; text-align: right;">PER BULAN</td>
                                                        <td style="text-align: right;">TOTAL</td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Harga Sewa Unit</td>
                                                        <td>
                                                            <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" style="display: inline; text-align: right;" value="<?=numberToIna($reqLoiSewaBiayaSatuanUnit)?>" />
                                                        </td>
                                                        <td>
                                                            <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" style="display: inline; text-align: right;" value="<?=numberToIna($reqLoiSewaBiayaPerBulanUnit)?>" />
                                                        </td>
                                                        <td>
                                                            <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" style="display: inline; text-align: right;" value="<?=numberToIna($reqLoiSewaTotalBiayaUnit)?>" />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Service Charge Tahun Pertama</td>
                                                        <td>
                                                            <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" style="display: inline; text-align: right;" value="<?=numberToIna($reqLoiSewaBiayaSatuanService)?>" />
                                                        </td>
                                                        <td>
                                                            <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" style="display: inline; text-align: right;" value="<?=numberToIna($reqLoiSewaBiayaPerBulanService)?>" />
                                                        </td>
                                                        <td>
                                                            <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" style="display: inline; text-align: right;" value="<?=numberToIna($reqLoiSewaTotalBiayaService)?>" />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Total (Tanpa PPN)</td>
                                                        <td></td>
                                                        <td>
                                                            <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" style="display: inline; text-align: right;" value="<?=numberToIna($reqLoiTotalBiayaPerBulanNoPpn)?>" />
                                                        </td>
                                                        <td>
                                                            <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" style="display: inline; text-align: right;" value="<?=numberToIna($reqLoiTotalBiayaNoPpn)?>" />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Total (Dengan PPN)</td>
                                                        <td></td>
                                                        <td>
                                                            <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" style="display: inline; text-align: right;" value="<?=numberToIna($reqLoiTotalBiayaPerBulanPpn)?>" />
                                                        </td>
                                                        <td>
                                                            <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" style="display: inline; text-align: right;" value="<?=numberToIna($reqLoiTotalBiayaPpn)?>" />
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Jaminan Sewa (Security Deposit)</td>
                                        <td>:</td>
                                        <td>
                                            <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" value="<?=numberToIna($reqLoiSecurityDeposit)?>" style="display: inline; text-align: right;" />
                                        </td>
                                        <td>Fitting Out</td>
                                        <td>:</td>
                                        <td>
                                            <input type="text" class="vlxuangclass easyui-validatebox textbox form-control" value="<?=numberToIna($reqLoiFittingOut)?>" style="display: inline; text-align: right;" />
                                        </td>
                                    </tr>
                                </thead>
                            </table>

                            <?
                            if(!empty($arrloilog))
                            {
                            ?>
                            <table class="table">
                                <thead class="thead-light">
                                    <tr class="active">
                                        <th colspan="2">Riwayat Konsep Surat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="2">
                                            <ol class="list-unstyled">
                                                <?
                                                foreach ($arrloilog as $k => $v)
                                                {
                                                    $vinfologdetil= $v["TANGGAL"].", ".$v["INFORMASI"].", [".$v["STATUS_SURAT"]."].";
                                                    $vinfologcatatan= $v["CATATAN"];
                                                ?>
                                                <li>
                                                    <span><?=$vinfologdetil?></span>
                                                </li>
                                                <li>
                                                    <span><?=$vinfologcatatan?></span>
                                                </li>
                                                <li><span><br/></span></li>
                                                <?
                                                }
                                                ?>
                                            </ol>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <?
                            }
                            ?>

                        </div>

                        <div id="tab-loo" class="tab-pane fade">

                            <table class="table">
                                <thead>
                                    <tr>           
                                        <td>Lokasi</td>
                                        <td>:</td>
                                        <td>
                                            <input disabled type="text" class="easyui-combotree"
                                            data-options="
                                            onClick: function(node){
                                                sethargautilitycharge(node);
                                            }
                                            , width:'350'
                                            , valueField:'id'
                                            , textField:'text'
                                            , editable:false
                                            , url:'combo_json/comboLokasiLoo'
                                            " required value="<?=$reqLooLokasiLooId?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Customer</td>
                                        <td>:</td>
                                        <td>
                                            <input disabled type="text" class="easyui-combobox" data-options="width:'350', valueField:'id', textField:'text', editable:false, url:'combo_json/comboCustomer?cek=pemilik'" required value="<?=$reqLooCustomerId?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Produk</td>
                                        <td>:</td>
                                        <td>
                                            <input disabled type="text" class="easyui-combobox" data-options="width:'350', valueField:'id', textField:'text', editable:false, url:'combo_json/comboProduk'" required value="<?=$reqLooProdukId?>" />
                                            &nbsp;&nbsp;PPN
                                            <input readonly type="text" class="vlxuangclass easyui-validatebox textbox form-control" style="width:5%; padding: initial; display: inline; text-align: right;" value="<?=numberToIna($reqLooPph)?>" />           
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Penanda tangan <span class="text-danger">*</span></td>
                                        <td>:</td>
                                        <td>
                                            <?=$infoloosatuankerjainfo?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Pemeriksa
                                        </td>
                                        <td>:</td>
                                        <td>
                                            <div class="inner">
                                                <?
                                                if(!empty($reqTrLooId))
                                                {
                                                    $setinfoparaf= new TrLooParaf();
                                                    $setinfoparaf->selectByParams(array(), -1, -1, " AND A.STATUS_BANTU IS NULL AND A.TR_LOO_ID = ".$reqTrLooId, "ORDER BY A.NO_URUT");
                                                    while($setinfoparaf->nextRow())
                                                    {
                                                        $valparafnama= $setinfoparaf->getField("NAMA_SATKER");
                                                        $valparafid= $setinfoparaf->getField("SATUAN_KERJA_ID_TUJUAN");
                                                ?>
                                                    <div class="item">PARAF: <?=$valparafnama?></div>
                                                <?
                                                    }
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
                                                        <?
                                                        $set_attachement = new TrLoo();
                                                        $set_attachement->selectByParamsAttachment(array("A.TR_LOO_ID" => (int)$reqTrLooId));
                                                        while ($set_attachement->nextRow()) {
                                                            $attach_id= $set_attachement->getField("TR_LOO_ATTACHMENT_ID");
                                                        ?>
                                                            
                                                            <div class="MultiFile-label">
                                                                <?
                                                                $arrexcept= array("xlsx", "xls", "doc", "docx", "ppt", "pptx", "txt");
                                                                //$arrexcept= array("xlsx", "xls", "doc", "docx", "txt");
                                                                if(in_array(strtolower($set_attachement->getField("TIPE")), $arrexcept))
                                                                {
                                                                ?>
                                                                <?= $set_attachement->getField("NAMA") ?>
                                                                <a onClick="down('<?=$attach_id?>')" >
                                                                    <i style="cursor: pointer;" class="fa fa-download" ></i>
                                                                </a>
                                                                <?
                                                                }
                                                                else
                                                                {
                                                                ?>
                                                                <?= $set_attachement->getField("NAMA") ?>
                                                                <a onClick="parent.openAdd('<?= base_url()."uploadsloo/".$reqTrLooId."/".$set_attachement->getField("ATTACHMENT") ?>')" >
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

                                </thead>
                            </table>

                            <fieldset>
                                <legend style="font-size: large;">
                                    Luas Sewa
                                </legend>

                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td class="tdcolor">Indoor</td>
                                            <td class="tdcolor">Outdoor</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 50%">
                                                <table class="table">
                                                    <tbody>
                                                        <?
                                                        foreach ($arrloolokasi as $k => $v)
                                                        {
                                                            // $vkeyid= $v["trloodetilid"];
                                                            // $vkeyid= $v["trlooid"];
                                                            $vkeyid= $v["vid"];
                                                            // $vlabel= $v["kode"]." - ".$v["nama"];
                                                            $vlabel= $v["kode"]." - ".$v["lantai"];
                                                            $vnilai= $v["vnilai"];
                                                            $vmode= $v["vmode"];
                                                            if($vmode == "luas_sewa_indoor")
                                                            {
                                                        ?>
                                                        <tr>
                                                            <td>
                                                                <?=$vlabel?>
                                                            </td>
                                                            <td>:</td>
                                                            <td style="width:30%">
                                                                <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" placeholder="Isi Luas (m2)" style="width:85%; display: inline; text-align: right;" value="<?=numberToIna($vnilai)?>" /> <label class="labeltotal">m2</label>'
                                                            </td>
                                                        </tr>
                                                        <?
                                                            }
                                                        }
                                                        ?>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="2" style="text-align: right;">Total Luas Indoor</td>
                                                            <td style="width: 20%">
                                                                <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" style="width:85%; display: inline; text-align: right;" value="<?=numberToIna($reqLooTotalLuasIndoor)?>" /> <label class="labeltotal">m2</label>
                                                            </td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </td>
                                            <td style="width: 50%">
                                                <table class="table">
                                                    <tbody>
                                                        <?
                                                        foreach ($arrloolokasi as $k => $v)
                                                        {
                                                            // $vkeyid= $v["trloodetilid"];
                                                            // $vkeyid= $v["trlooid"];
                                                            $vkeyid= $v["vid"];
                                                            // $vlabel= $v["kode"]." - ".$v["nama"];
                                                            $vlabel= $v["kode"]." - ".$v["lantai"];
                                                            $vnilai= $v["vnilai"];
                                                            $vmode= $v["vmode"];
                                                            if($vmode == "luas_sewa_outdoor")
                                                            {
                                                        ?>
                                                        <tr>
                                                            <td>
                                                                <?=$vlabel?>
                                                            </td>
                                                            <td>:</td>
                                                            <td style="width:30%">
                                                                <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" placeholder="Isi Luas (m2)" style="width:85%; display: inline; text-align: right;" value="<?=numberToIna($vnilai)?>" /> <label class="labeltotal">m2</label>'
                                                            </td>
                                                        </tr>
                                                        <?
                                                            }
                                                        }
                                                        ?>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="2" style="text-align: right;">Total Luas Outdoor</td>
                                                            <td style="width: 20%">
                                                                <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" style="width:85%; display: inline; text-align: right;" value="<?=numberToIna($reqLooTotalLuasOutdoor)?>" /> <label class="labeltotal">m2</label>
                                                            </td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="text-align: right;">
                                                Total Luas Sewa
                                                <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" style="width:15%; display: inline; text-align: right;" value="<?=numberToIna($reqLooTotalLuas)?>" /> <label class="labeltotal">m2</label>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </fieldset>

                            <fieldset>
                                <legend style="font-size: large;">
                                    Tarif Sewa
                                </legend>
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td colspan="3" class="tdcolor" style="width: 50%">Indoor</td>
                                            <td colspan="3" class="tdcolor" style="width: 50%">Outdoor</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3">
                                                <table class="table">
                                                    <tbody>
                                                        <tr>
                                                            <td class="tdcolor" style="width: 27%">Unit</td>
                                                            <td class="tdcolor" style="width: 15%">Discount</td>
                                                            <td class="tdcolor" style="width: 29%">Tarif (after discount)</td>
                                                            <td class="tdcolor" style="width: 24%">Harga Sewa</td>
                                                        </tr>
                                                        <?
                                                        foreach ($arrloolokasi as $k => $v)
                                                        {
                                                            // $vkeyid= $v["trloodetilid"];
                                                            // $vkeyid= $v["trlooid"];
                                                            $vkeyid= $v["vid"];
                                                            // $vlabel= $v["kode"]." - ".$v["nama"];
                                                            $vlabel= $v["kode"]." - ".$v["lantai"];
                                                            $vluas= $v["vnilai"];
                                                            $vmode= $v["vmode"];
                                                            if($vmode == "luas_sewa_indoor")
                                                            {
                                                        ?>
                                                        <tr>
                                                            <td colspan="4"><?=$vlabel?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <?
                                                                $valnilai= 0;
                                                                $valmode= "tarif_sewa_unit_indoor";
                                                                $infocarikey= $vkeyid."-".$valmode;
                                                                $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrloodetil);
                                                                if(!empty($arrkondisicheck))
                                                                {
                                                                    $vindex= $arrkondisicheck[0];
                                                                    $valnilai= $arrloodetil[$vindex]["vnilai"];
                                                                }
                                                                ?>
                                                                <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" name="vnilai[]" placeholder="Isi Rp/m2" style="width:65%; display: inline; text-align: right;" value="<?=numberToIna($valnilai)?>" /> <label class="labeltotal">Rp/m2</label>
                                                            </td>
                                                            <td>
                                                                <?
                                                                $valnilai= 0;
                                                                $valmode= "tarif_sewa_unit_indoor_diskon";
                                                                $infocarikey= $vkeyid."-".$valmode;
                                                                $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrloodetil);
                                                                if(!empty($arrkondisicheck))
                                                                {
                                                                    $vindex= $arrkondisicheck[0];
                                                                    $valnilai= $arrloodetil[$vindex]["vnilai"];
                                                                }
                                                                ?>
                                                                <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" placeholder="Isi %" style="width:65%; display: inline; text-align: right;" value="<?=numberToIna($valnilai)?>" /> <label class="labeltotal">%</label>
                                                            </td>
                                                            <td>
                                                                <?
                                                                $valnilai= 0;
                                                                $valmode= "tarif_sewa_unit_indoor_after_diskon";
                                                                $infocarikey= $vkeyid."-".$valmode;
                                                                $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrloodetil);
                                                                if(!empty($arrkondisicheck))
                                                                {
                                                                    $vindex= $arrkondisicheck[0];
                                                                    $valnilai= $arrloodetil[$vindex]["vnilai"];
                                                                }
                                                                ?>
                                                                <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" placeholder="Isi Rp/m2" style="width:65%; display: inline; text-align: right;" value="<?=numberToIna($valnilai)?>" /> <label class="labeltotal">Rp/m2</label>
                                                            </td>
                                                            <td>
                                                                <?
                                                                $valnilai= 0;
                                                                $valmode= "tarif_sewa_unit_indoor_harga";
                                                                $infocarikey= $vkeyid."-".$valmode;
                                                                $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrloodetil);
                                                                if(!empty($arrkondisicheck))
                                                                {
                                                                    $vindex= $arrkondisicheck[0];
                                                                    $valnilai= $arrloodetil[$vindex]["vnilai"];
                                                                }
                                                                ?>
                                                                <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" style="display: inline; text-align: right;" value="<?=numberToIna($valnilai)?>" />
                                                            </td>
                                                        </tr>
                                                        <?
                                                            }
                                                        }
                                                        ?>

                                                    </tbody>
                                                </table>
                                            </td>
                                            <td colspan="3">
                                                <table class="table">
                                                    <tbody>
                                                        <tr>
                                                            <td class="tdcolor" style="width: 27%">Unit</td>
                                                            <td class="tdcolor" style="width: 15%">Discount</td>
                                                            <td class="tdcolor" style="width: 29%">Tarif (after discount)</td>
                                                            <td class="tdcolor" style="width: 24%">Harga Sewa</td>
                                                        </tr>
                                                        <?
                                                        foreach ($arrloolokasi as $k => $v)
                                                        {
                                                            // $vkeyid= $v["trloodetilid"];
                                                            // $vkeyid= $v["trlooid"];
                                                            $vkeyid= $v["vid"];
                                                            // $vlabel= $v["kode"]." - ".$v["nama"];
                                                            $vlabel= $v["kode"]." - ".$v["lantai"];
                                                            $vluas= $v["vnilai"];
                                                            $vmode= $v["vmode"];
                                                            if($vmode == "luas_sewa_outdoor")
                                                            {
                                                        ?>
                                                        <tr>
                                                            <td colspan="4"><?=$vlabel?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <?
                                                                $valnilai= 0;
                                                                $valmode= "tarif_sewa_unit_outdoor";
                                                                $infocarikey= $vkeyid."-".$valmode;
                                                                $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrloodetil);
                                                                if(!empty($arrkondisicheck))
                                                                {
                                                                    $vindex= $arrkondisicheck[0];
                                                                    $valnilai= $arrloodetil[$vindex]["vnilai"];
                                                                }
                                                                ?>
                                                                <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" placeholder="Isi Rp/m2" style="width:65%; display: inline; text-align: right;" value="<?=numberToIna($valnilai)?>" /> <label class="labeltotal">Rp/m2</label>
                                                            </td>
                                                            <td>
                                                                <?
                                                                $valnilai= 0;
                                                                $valmode= "tarif_sewa_unit_outdoor_diskon";
                                                                $infocarikey= $vkeyid."-".$valmode;
                                                                $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrloodetil);
                                                                if(!empty($arrkondisicheck))
                                                                {
                                                                    $vindex= $arrkondisicheck[0];
                                                                    $valnilai= $arrloodetil[$vindex]["vnilai"];
                                                                }
                                                                ?>
                                                                <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" placeholder="Isi %" style="width:65%; display: inline; text-align: right;" value="<?=numberToIna($valnilai)?>" /> <label class="labeltotal">%</label>
                                                            </td>
                                                            <td>
                                                                <?
                                                                $valnilai= 0;
                                                                $valmode= "tarif_sewa_unit_outdoor_after_diskon";
                                                                $infocarikey= $vkeyid."-".$valmode;
                                                                $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrloodetil);
                                                                if(!empty($arrkondisicheck))
                                                                {
                                                                    $vindex= $arrkondisicheck[0];
                                                                    $valnilai= $arrloodetil[$vindex]["vnilai"];
                                                                }
                                                                ?>
                                                                <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" placeholder="Isi Rp/m2" style="width:65%; display: inline; text-align: right;" value="<?=numberToIna($valnilai)?>" /> <label class="labeltotal">Rp/m2</label>
                                                            </td>
                                                            <td>
                                                                <?
                                                                $valnilai= 0;
                                                                $valmode= "tarif_sewa_unit_outdoor_harga";
                                                                $infocarikey= $vkeyid."-".$valmode;
                                                                $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrloodetil);
                                                                if(!empty($arrkondisicheck))
                                                                {
                                                                    $vindex= $arrkondisicheck[0];
                                                                    $valnilai= $arrloodetil[$vindex]["vnilai"];
                                                                }
                                                                ?>
                                                                <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" style="display: inline; text-align: right;" value="<?=numberToIna($valnilai)?>" />
                                                            </td>
                                                        </tr>
                                                        <?
                                                            }
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th colspan="6">Service Charge</th>
                                        </tr>
                                        <tr>
                                            <td colspan="3">
                                                <table class="table">
                                                    <tbody>
                                                        <tr>
                                                            <td class="tdcolor" style="width: 35%">Harga awal setelah ppn</td>
                                                            <td class="tdcolor" style="width: 20%">Discount</td>
                                                            <td class="tdcolor" style="width: 45%">Harga akhir setelah ppn (after disc)</td>
                                                        </tr>
                                                        <?
                                                        foreach ($arrloolokasi as $k => $v)
                                                        {
                                                            // $vkeyid= $v["trloodetilid"];
                                                            $vkeyid= $v["vid"];
                                                            // $vlabel= $v["kode"]." - ".$v["nama"];
                                                            $vlabel= $v["kode"]." - ".$v["lantai"];
                                                            $vluas= $v["vnilai"];
                                                            $vmode= $v["vmode"];
                                                            if($vmode == "luas_sewa_indoor")
                                                            {
                                                        ?>
                                                        <tr>
                                                            <td colspan="4"><?=$vlabel?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <?
                                                                $valnilai= 0;
                                                                $valmode= "tarif_sewa_sc_indoor";
                                                                $infocarikey= $vkeyid."-".$valmode;
                                                                $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrloodetil);
                                                                if(!empty($arrkondisicheck))
                                                                {
                                                                    $vindex= $arrkondisicheck[0];
                                                                    $valnilai= $arrloodetil[$vindex]["vnilai"];
                                                                }
                                                                ?>


                                                                <?
                                                                $valnilai= 0;
                                                                $valmode= "tarif_sewa_sc_indoor_after_ppn";
                                                                $infocarikey= $vkeyid."-".$valmode;
                                                                $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrloodetil);
                                                                if(!empty($arrkondisicheck))
                                                                {
                                                                    $vindex= $arrkondisicheck[0];
                                                                    $valnilai= $arrloodetil[$vindex]["vnilai"];
                                                                }
                                                                ?>
                                                                <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" placeholder="Isi Rp/m2" style="width:65%; display: inline; text-align: right;" value="<?=numberToIna($valnilai)?>" /> <label class="labeltotal">Rp/m2</label>
                                                            </td>
                                                            <td>
                                                                <?
                                                                $valnilai= 0;
                                                                $valmode= "tarif_sewa_sc_indoor_diskon";
                                                                $infocarikey= $vkeyid."-".$valmode;
                                                                $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrloodetil);
                                                                if(!empty($arrkondisicheck))
                                                                {
                                                                    $vindex= $arrkondisicheck[0];
                                                                    $valnilai= $arrloodetil[$vindex]["vnilai"];
                                                                }
                                                                ?>
                                                                <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" placeholder="Isi %" style="width:65%; display: inline; text-align: right;" value="<?=numberToIna($valnilai)?>" /> <label class="labeltotal">%</label>

                                                                <?
                                                                $valnilai= 0;
                                                                $valmode= "tarif_sewa_sc_indoor_after_diskon";
                                                                $infocarikey= $vkeyid."-".$valmode;
                                                                $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrloodetil);
                                                                if(!empty($arrkondisicheck))
                                                                {
                                                                    $vindex= $arrkondisicheck[0];
                                                                    $valnilai= $arrloodetil[$vindex]["vnilai"];
                                                                }
                                                                ?>
                                                            </td>
                                                            <td>
                                                                <?
                                                                $valnilai= 0;
                                                                $valmode= "tarif_sewa_sc_indoor_after_ppn_diskon";
                                                                $infocarikey= $vkeyid."-".$valmode;
                                                                $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrloodetil);
                                                                if(!empty($arrkondisicheck))
                                                                {
                                                                    $vindex= $arrkondisicheck[0];
                                                                    $valnilai= $arrloodetil[$vindex]["vnilai"];
                                                                }
                                                                ?>
                                                                <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" placeholder="Isi Rp/m2" style="width:65%; display: inline; text-align: right;" value="<?=numberToIna($valnilai)?>" /> <label class="labeltotal">Rp/m2</label>
                                                            </td>
                                                        </tr>
                                                        <?
                                                            }
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td colspan="3">
                                                <table class="table">
                                                    <tbody>
                                                        <tr>
                                                            <td class="tdcolor" style="width: 35%">Harga awal setelah ppn</td>
                                                            <td class="tdcolor" style="width: 20%">Discount</td>
                                                            <td class="tdcolor" style="width: 45%">Harga akhir setelah ppn (after disc)</td>
                                                        </tr>
                                                        <?
                                                        foreach ($arrloolokasi as $k => $v)
                                                        {
                                                            // $vkeyid= $v["trloodetilid"];
                                                            $vkeyid= $v["vid"];
                                                            // $vlabel= $v["kode"]." - ".$v["nama"];
                                                            $vlabel= $v["kode"]." - ".$v["lantai"];
                                                            $vluas= $v["vnilai"];
                                                            $vmode= $v["vmode"];
                                                            if($vmode == "luas_sewa_outdoor")
                                                            {
                                                        ?>
                                                        <tr>
                                                            <td colspan="4"><?=$vlabel?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <?
                                                                $valnilai= 0;
                                                                $valmode= "tarif_sewa_sc_outdoor";
                                                                $infocarikey= $vkeyid."-".$valmode;
                                                                $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrloodetil);
                                                                if(!empty($arrkondisicheck))
                                                                {
                                                                    $vindex= $arrkondisicheck[0];
                                                                    $valnilai= $arrloodetil[$vindex]["vnilai"];
                                                                }
                                                                ?>

                                                                <?
                                                                $valnilai= 0;
                                                                $valmode= "tarif_sewa_sc_outdoor_after_ppn";
                                                                $infocarikey= $vkeyid."-".$valmode;
                                                                $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrloodetil);
                                                                if(!empty($arrkondisicheck))
                                                                {
                                                                    $vindex= $arrkondisicheck[0];
                                                                    $valnilai= $arrloodetil[$vindex]["vnilai"];
                                                                }
                                                                ?>
                                                                <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" placeholder="Isi Rp/m2" style="width:65%; display: inline; text-align: right;" value="<?=numberToIna($valnilai)?>" /> <label class="labeltotal">Rp/m2</label>
                                                            </td>
                                                            <td>
                                                                <?
                                                                $valnilai= 0;
                                                                $valmode= "tarif_sewa_sc_outdoor_diskon";
                                                                $infocarikey= $vkeyid."-".$valmode;
                                                                $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrloodetil);
                                                                if(!empty($arrkondisicheck))
                                                                {
                                                                    $vindex= $arrkondisicheck[0];
                                                                    $valnilai= $arrloodetil[$vindex]["vnilai"];
                                                                }
                                                                ?>
                                                                <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" placeholder="Isi %" style="width:65%; display: inline; text-align: right;" value="<?=numberToIna($valnilai)?>" /> <label class="labeltotal">%</label>

                                                                <?
                                                                $valnilai= 0;
                                                                $valmode= "tarif_sewa_sc_outdoor_after_diskon";
                                                                $infocarikey= $vkeyid."-".$valmode;
                                                                $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrloodetil);
                                                                if(!empty($arrkondisicheck))
                                                                {
                                                                    $vindex= $arrkondisicheck[0];
                                                                    $valnilai= $arrloodetil[$vindex]["vnilai"];
                                                                }
                                                                ?>
                                                            </td>
                                                            <td>
                                                                <?
                                                                $valnilai= 0;
                                                                $valmode= "tarif_sewa_sc_outdoor_after_ppn_diskon";
                                                                $infocarikey= $vkeyid."-".$valmode;
                                                                $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrloodetil);
                                                                if(!empty($arrkondisicheck))
                                                                {
                                                                    $vindex= $arrkondisicheck[0];
                                                                    $valnilai= $arrloodetil[$vindex]["vnilai"];
                                                                }
                                                                ?>
                                                                <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" placeholder="Isi Rp/m2" style="width:65%; display: inline; text-align: right;" value="<?=numberToIna($valnilai)?>" /> <label class="labeltotal">Rp/m2</label>
                                                            </td>
                                                        </tr>
                                                        <?
                                                            }
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </fieldset>

                            <fieldset>
                                <legend style="font-size: large;">
                                    Harga
                                </legend>

                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td style="width: 25%; text-align: right;">
                                                Sewa Indoor
                                                <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" style="width:40%; display: inline; text-align: right;" value="<?=numberToIna($reqLooHargaIndoorSewa)?>" /> <label class="labelsumtotal">/ m2 / bulan</label>
                                            </td>
                                            <td style="width: 25%; text-align: right;">
                                                Sewa Outdoor
                                                <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" style="width:40%; display: inline; text-align: right;" value="<?=numberToIna($reqLooHargaOutdoorSewa)?>" /> <label class="labelsumtotal">/ m2 / bulan</label>
                                            </td>
                                            <td style="width: 25%; text-align: right;">
                                                Service Charge Indoor
                                                <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" style="width:40%; display: inline; text-align: right;" value="<?=numberToIna($reqLooHargaIndoorService)?>" /> <label class="labelsumtotal">/ m2 / bulan</label>
                                            </td>
                                            <td style="width: 25%; text-align: right;">
                                                Service Charge Outdoor
                                                <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" style="width:40%; display: inline; text-align: right;" value="<?=numberToIna($reqLooHargaOutdoorService)?>" /> <label class="labelsumtotal">/ m2 / bulan</label>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </fieldset>

                            <fieldset>
                                <legend style="font-size: large;">
                                    Harga Utility Charge
                                </legend>

                                <table class="table">
                                    <tbody>
                                        <?
                                        foreach ($arrloodetil as $k => $v)
                                        {
                                            $valid= $v["vid"];
                                            $valnilai= $v["vnilai"];
                                            $valketerangan= $v["vketerangan"];
                                            $vmode= $v["vmode"];
                                            $valmode= "harga_utility_charge";
                                            if($vmode == $valmode)
                                            {
                                                $vnama= "";
                                                $infocarikey= $valid;
                                                $arrkondisicheck= in_array_column($infocarikey, "id", $arrutilitycharge);
                                                if(!empty($arrkondisicheck))
                                                {
                                                    $vindex= $arrkondisicheck[0];
                                                    $vnama= $arrutilitycharge[$vindex]["nama"];
                                                }
                                        ?>
                                                <tr>
                                                    <td><?=$vnama?></td>
                                                    <td>:</td>
                                                    <td style="width:30%">
                                                        <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" placeholder="Isi (<?=$valketerangan?>)" style="width:85%; display: inline; text-align: right;" value="<?=numberToIna($valnilai)?>" /> <label class="labeltotal"><?=$valketerangan?></label>
                                                    </td>
                                                </tr>
                                        <?
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>

                            </fieldset>

                            <table class="table">
                                <thead>
                                    <tr>
                                        <td colspan="6">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <td style="width: 20%">Down Payment</td>
                                                        <td style="width: 2%">:</td>
                                                        <td>
                                                            <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" value="<?=numberToIna($reqLooDp)?>" data-options="required:true" style="width:60%; display: inline; text-align: right;" /> <label class="labeltotal">%</label>
                                                        </td>
                                                        <td style="width: 20%">Periode Sewa</td>
                                                        <td style="width: 2%">:</td>
                                                        <td>
                                                            <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" value="<?=numberToIna($reqLooPeriodeSewa)?>" data-options="required:true" style="width:60%; display: inline; text-align: right;" /> <label class="labeltotal">bulan</label>
                                                        </td>
                                                        <td style="width: 20%">TOP</td>
                                                        <td style="width: 2%">:</td>
                                                        <td>
                                                            <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" value="<?=numberToIna($reqLooTop)?>" data-options="required:true" style="width:60%; display: inline; text-align: right;" /> <label class="labeltotal">bulan</label>
                                                        </td>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="6">Jam Operasional</th>
                                    </tr>
                                    <tr>
                                        <td style="width: 28%">Gedung</td>
                                        <td style="width: 2%">:</td>
                                        <td style="width: 20%">
                                            <?
                                            $valketerangan= "10:00 s/d 22:00";
                                            $vkeyid= 1;
                                            $valmode= "jam_operasional_gedung";
                                            $infocarikey= $vkeyid."-".$valmode;
                                            $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrloodetil);
                                            if(!empty($arrkondisicheck))
                                            {
                                                $vindex= $arrkondisicheck[0];
                                                $valketerangan= $arrloodetil[$vindex]["vketerangan"];
                                            }
                                            ?>
                                            <input type="text" readonly class="easyui-validatebox textbox form-control" required name="vketerangan[]" value="<?=$valketerangan?>" data-options="required:true" style="width:90%; display: inline; text-align: right;" />
                                        </td>
                                        <td style="width: 28%">Tenant</td>
                                        <td style="width: 2%">:</td>
                                        <td style="width: 20%">
                                            <?
                                            $valketerangan= "10:00 s/d 22:00";
                                            $vkeyid= 2;
                                            $valmode= "jam_operasional_tenan";
                                            $infocarikey= $vkeyid."-".$valmode;
                                            $arrkondisicheck= in_array_column($infocarikey, "keyrowdetil", $arrloodetil);
                                            if(!empty($arrkondisicheck))
                                            {
                                                $vindex= $arrkondisicheck[0];
                                                $valketerangan= $arrloodetil[$vindex]["vketerangan"];
                                            }
                                            ?>
                                            <input type="text" readonly class="easyui-validatebox textbox form-control" required name="vketerangan[]" value="<?=$valketerangan?>" data-options="required:true" style="width:90%; display: inline; text-align: right;" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="6">RINCIAN NILAI SEWA</th>
                                    </tr>
                                    <tr>
                                        <td colspan="6">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <td style="width: 30%; text-align: right;">KETERANGAN</td>
                                                        <td style="width: 20%; text-align: right;">BIAYA SATUAN</td>
                                                        <td style="width: 20%; text-align: right;">PER BULAN</td>
                                                        <td style="text-align: right;">TOTAL</td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Harga Sewa Unit</td>
                                                        <td>
                                                            <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" style="display: inline; text-align: right;" value="<?=numberToIna($reqLooSewaBiayaSatuanUnit)?>" />
                                                        </td>
                                                        <td>
                                                            <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" style="display: inline; text-align: right;" value="<?=numberToIna($reqLooSewaBiayaPerBulanUnit)?>" />
                                                        </td>
                                                        <td>
                                                            <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" style="display: inline; text-align: right;" value="<?=numberToIna($reqLooSewaTotalBiayaUnit)?>" />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Service Charge Tahun Pertama</td>
                                                        <td>
                                                            <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" style="display: inline; text-align: right;" value="<?=numberToIna($reqLooSewaBiayaSatuanService)?>" />
                                                        </td>
                                                        <td>
                                                            <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" style="display: inline; text-align: right;" value="<?=numberToIna($reqLooSewaBiayaPerBulanService)?>" />
                                                        </td>
                                                        <td>
                                                            <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" style="display: inline; text-align: right;" value="<?=numberToIna($reqLooSewaTotalBiayaService)?>" />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Total (Tanpa PPN)</td>
                                                        <td></td>
                                                        <td>
                                                            <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" style="display: inline; text-align: right;" value="<?=numberToIna($reqLooTotalBiayaPerBulanNoPpn)?>" />
                                                        </td>
                                                        <td>
                                                            <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" style="display: inline; text-align: right;" value="<?=numberToIna($reqLooTotalBiayaNoPpn)?>" />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Total (Dengan PPN)</td>
                                                        <td></td>
                                                        <td>
                                                            <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" style="display: inline; text-align: right;" value="<?=numberToIna($reqLooTotalBiayaPerBulanPpn)?>" />
                                                        </td>
                                                        <td>
                                                            <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" style="display: inline; text-align: right;" value="<?=numberToIna($reqLooTotalBiayaPpn)?>" />
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Jaminan Sewa (Security Deposit)</td>
                                        <td>:</td>
                                        <td>
                                            <input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control" value="<?=numberToIna($reqLooSecurityDeposit)?>" style="display: inline; text-align: right;" />
                                        </td>
                                        <td>Fitting Out</td>
                                        <td>:</td>
                                        <td>
                                            <input type="text" class="vlxuangclass easyui-validatebox textbox form-control" value="<?=numberToIna($reqLooFittingOut)?>" style="display: inline; text-align: right;" />
                                        </td>
                                    </tr>
                                </thead>
                            </table>

                            <?
                            if(!empty($arrloolog))
                            {
                            ?>
                            <table class="table">
                                <thead class="thead-light">
                                    <tr class="active">
                                        <th colspan="2">Riwayat Konsep Surat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="2">
                                            <ol class="list-unstyled">
                                                <?
                                                foreach ($arrloolog as $k => $v)
                                                {
                                                    $vinfologdetil= $v["TANGGAL"].", ".$v["INFORMASI"].", [".$v["STATUS_SURAT"]."].";
                                                    $vinfologcatatan= $v["CATATAN"];
                                                ?>
                                                <li>
                                                    <span><?=$vinfologdetil?></span>
                                                </li>
                                                <li>
                                                    <span><?=$vinfologcatatan?></span>
                                                </li>
                                                <li><span><br/></span></li>
                                                <?
                                                }
                                                ?>
                                            </ol>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <?
                            }
                            ?>

                        </div>

                    </div>
                </form>

            </div>

        </div>
    </div>
</body>
</html>


<script>

$(document).ready(function() {
    
});

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
            reqSatuanKerjaPengirimId= $("#reqSatuanKerjaPengirimId").combotree("getValue");
            if(reqSatuanKerjaPengirimId == SATUAN_KERJA_ID)
            {
                rv = false;
            }
            else
            {
                $('[name^=reqTujuanSuratParafValidasi]').each(function() {

                    if ($(this).val() == SATUAN_KERJA_ID) {
                        rv = false;
                        return false;
                    }

                });
            }
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

function setinfovalidasi()
{

}

arrutilitycharge= JSON.parse('<?=JSON_encode($arrutilitycharge, JSON_HEX_APOS)?>');
function sethargautilitycharge(node)
{
    // console.log(node)
    vid= node.id;
    vuc= node.uc;
    vuc= vuc.split(',');
    // console.log(vuc);
    sebelumLokasiLooId= $("#sebelumLokasiLooId").val();

    if(sebelumLokasiLooId !== "")
    {
        $("#luassewaindoor, #tarifinfosewaindoor, #tarifinfosewascindoor, #luassewaoutdoor, #tarifinfosewaoutdoor, #tarifinfosewascoutdoor").empty();
        globalhapusgroupclass(sebelumLokasiLooId, "lokasi");
    }

    $("#hargautilitycharge").empty();
    // console.log(arrutilitycharge)

    reqLokasiLooId= $("#reqLokasiLooId").combotree("getValue");
    varrutilitycharge= arrutilitycharge.filter(item => item.lokasilooid === reqLokasiLooId);
    // console.log(varrutilitycharge);

    if(Array.isArray(varrutilitycharge) && varrutilitycharge.length)
    {
        vtable= '';
        $.each(varrutilitycharge, function( index, value ) {
            // console.log( index + ": " + value["id"] );
            vdetilid= value["id"];

            if($.inArray(vdetilid, vuc) != -1)
            {
                vnama= value["nama"];
                vket= value["ket"];
                vharga= value["harga"];

                vtable+= ''
                +'<tr class="grouplokasiclass'+vid+'">'
                +   '<td>'+vnama+'</td>'
                +   '<td>:</td>'
                +   '<td style="width:30%">'
                +       '<input type="hidden" name="vmode[]" value="harga_utility_charge" />'
                +       '<input type="hidden" name="vid[]" class="valsetid" value="'+vdetilid+'" />'
                +       '<input type="hidden" name="vketerangan[]" value="'+vket+'" />'
                +       '<input type="text" class="vlxuangclass easyui-validatebox textbox form-control" name="vnilai[]" placeholder="Isi Luas (m2)" data-options="required:true" style="width:85%; display: inline; text-align: right;" value="'+setformat(vharga)+'" /> <label class="labeltotal">'+vket+'</label>'
                +   '</td>'
                +'</tr>';
            }
        });
    }
    $("#sebelumLokasiLooId").val(vid);
    $("#hargautilitycharge").append(vtable);
}

function openLookup(tipe) 
{
    tparam= [];

    // var obj = {};
    // obj["DESKRIPSI"] = "sdsasdasd";
    // obj["KODE"] = "kode";
    // obj["LANTAI"] = "lantai 11";
    // obj["LOKASI_LOO_ID"] = "1";
    // obj["NAMA"] = "nama";
    // obj["NAMA_LOKASI_LOO"] = "SOSORO MALL - MERAK";
    // obj["TIPE_INFO"] = null;
    // obj["id"] = "1";
    // obj["state"] = "open";
    // obj["LUAS"] = "95.58";
    // obj["KD_TARIF"] = "200000";
    // obj["TARIF_SC"] = "83243.24";
    // obj["text"] = "nama";
    // tparam.push(obj);
    // console.log(tparam);return false;
    // untuk percobanaan
    // addmulti("I", tparam);
    // addmulti("O", tparam);

    reqIdLokasi= $("#reqLokasiLooId").combotree("getValue");
    if (reqIdLokasi) 
    {
        openAdd('app/loadUrl/main/luas_sewa_lookup?reqId='+reqIdLokasi+'&reqTipe='+tipe)
    }
    else
    {
        $.messager.alert('Info', "Pilih Lokasi terlebih dahulu.", 'warning');
    }
}

function addmulti(vtipe, vparam)
{
    // console.log(vparam);return false;
    vparam.forEach(function (item, index) {
        // console.log(item, index);
        vitemid= item.id;
        vdetilparam= [];
        vdetilparam.id= vitemid;
        vdetilparam.nama= item.text;
        vdetilparam.nama_lokasi= item.NAMA_LOKASI_LOO;
        vdetilparam.kode= item.KODE;
        vdetilparam.lantai= item.LANTAI;
        vdetilparam.luas= item.LUAS;
        vdetilparam.kdtarif= item.KD_TARIF;
        vdetilparam.tarifsc= item.TARIF_SC;
        // vdetilparam.tarifsc= "92400";
        // console.log(vdetilparam);
        // param.id, param.text, param.NAMA_LOKASI_LOO, param.KODE, param.LANTAI

        vreturn= "";
        $(".totalsewaunitindoor, .totalsewaunitoutdoor").each(function(){
            checkvalid= $(this).closest('tr').find('.valsetid').val();
            // console.log(checkvalid);

            if(vitemid == checkvalid)
            {
                vreturn= "1";
                return false;
            }

        });

        if(vreturn == "")
        {
            appenddata(vtipe, vdetilparam);
        }
    });
}

function appenddata(vtipe, vdetilparam)
{
    reqLokasiLooId= $("#reqLokasiLooId").val();
    id= vdetilparam["id"];
    nama= vdetilparam["nama"];
    kode= vdetilparam["kode"];
    nama_lokasi= vdetilparam["nama_lokasi"];
    lantai= vdetilparam["lantai"];
    vluas= setformat(vdetilparam["luas"]);
    vkdtarif= setformat(vdetilparam["kdtarif"]);
    vtarifsc= setformat(vdetilparam["tarifsc"]);
    // console.log(vdetilparam);return false;
    // vluas= setformat(95.58);
    // vkdtarif= setformat(200000);

    if (vtipe=='I') 
    {
        vtable= ''
        +'<tr class="grouplokasiclass'+reqLokasiLooId+' groupclass'+id+'">'
        +   '<td>'+kode+' - '+lantai+' <i style="cursor:pointer" class="fa fa-times-circle text-danger" aria-hidden="true" onclick="hapusgroupclass('+id+');"></i></td>'
        +   '<td>:</td>'
        +   '<td style="width:30%">'
        +       '<input type="hidden" name="vmode[]" value="luas_sewa_indoor" />'
        +       '<input type="hidden" name="vid[]" class="valsetid" value="'+id+'" />'
        +       '<input type="hidden" name="vketerangan[]" />'
        +       '<input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control totalluasindoor" name="vnilai[]" placeholder="Isi Luas (m2)" data-options="required:true" style="width:85%; display: inline; text-align: right;" value="'+vluas+'" /> <label class="labeltotal">m2</label>'
        +   '</td>'
        +'</tr>';
        $("#luassewaindoor").append(vtable);
        hitungluas("totalluasindoor");

        vtable= ''
        +'<tr class="grouplokasiclass'+reqLokasiLooId+' groupclass'+id+'">'
        +   '<td colspan="4">'
        +       kode+' - '+lantai
        +   '</td>'
        +'</tr>'
        +'<tr class="grouplokasiclass'+reqLokasiLooId+' groupclass'+id+'">'
        +   '<td>'
        +       '<input type="hidden" name="vmode[]" value="tarif_sewa_unit_indoor" />'
        +       '<input type="hidden" name="vid[]" class="valsetid" value="'+id+'" />'
        +       '<input type="hidden" name="vketerangan[]" />'
        +       '<input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control totalsewaunitindoor" name="vnilai[]" placeholder="Isi Rp/m2" data-options="required:true" style="width:65%; display: inline; text-align: right;" value="'+vkdtarif+'" /> <label class="labeltotal">Rp/m2</label>'
        +   '</td>'
        +   '<td>'
        +       '<input type="hidden" name="vmode[]" value="tarif_sewa_unit_indoor_diskon" />'
        +       '<input type="hidden" name="vid[]" class="valsetid" value="'+id+'" />'
        +       '<input type="hidden" name="vketerangan[]" />'
        +       '<input type="text" class="vlxuangclass easyui-validatebox textbox form-control totalsewaunitindoordiskon" name="vnilai[]" placeholder="Isi %" data-options="required:true" style="width:65%; display: inline; text-align: right;" value="0" /> <label class="labeltotal">%</label>'
        +   '</td>'
        +   '<td>'
        +       '<input type="hidden" name="vmode[]" value="tarif_sewa_unit_indoor_after_diskon" />'
        +       '<input type="hidden" name="vid[]" class="valsetid" value="'+id+'" />'
        +       '<input type="hidden" name="vketerangan[]" />'
        +       '<input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control totalsewaunitindoorafterdiskon" name="vnilai[]" placeholder="Isi Rp/m2" data-options="required:true" style="width:65%; display: inline; text-align: right;" /> <label class="labeltotal">Rp/m2</label>'
        +   '</td>'
        +   '<td>'
        +       '<input type="hidden" name="vmode[]" value="tarif_sewa_unit_indoor_harga" />'
        +       '<input type="hidden" name="vid[]" class="valsetid" value="'+id+'" />'
        +       '<input type="hidden" name="vketerangan[]" />'
        +       '<input type="hidden" class="totalsewaunitindoorsewaluas" value="'+vluas+'" />'
        +       '<input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control totalsewaunitindoorharga" name="vnilai[]" style="display: inline; text-align: right;" />'
        +   '</td>'
        +'</tr>'
        ;
        $("#tarifinfosewaindoor").append(vtable);
        hitunghargasewa("totalsewaunitindoordiskon");

        reqPph= $("#reqPph").val();
        reqPph= FormatAngkaNumber(reqPph);
        reqPph= notnullval(reqPph);
        vsebelumpph= FormatAngkaNumber(vtarifsc);
        vsebelumpph= notnullval(vsebelumpph);

        vafterpph= vsebelumpph;
        if(reqPph > 0)
        {
            vafterpph= Math.round(parseFloat(vsebelumpph) * parseFloat(reqPph));
        }

        vtable= ''
        +'<tr class="grouplokasiclass'+reqLokasiLooId+' groupclass'+id+'">'
        +   '<td colspan="4">'
        +       kode+' - '+lantai
        +   '</td>'
        +'</tr>'
        +'<tr class="grouplokasiclass'+reqLokasiLooId+' groupclass'+id+'">'
        +   '<td>'
        +       '<input type="hidden" name="vmode[]" value="tarif_sewa_sc_indoor" />'
        +       '<input type="hidden" name="vid[]" class="valsetid" value="'+id+'" />'
        +       '<input type="hidden" name="vketerangan[]" />'
        +       '<input type="hidden" class="totalsewascindoor" name="vnilai[]" value="'+vtarifsc+'" />'

        +       '<input type="hidden" name="vmode[]" value="tarif_sewa_sc_indoor_after_ppn" />'
        +       '<input type="hidden" name="vid[]" class="valsetid" value="'+id+'" />'
        +       '<input type="hidden" name="vketerangan[]" />'
        +       '<input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control totalsewascindoorafterppn" name="vnilai[]" placeholder="Isi Rp/m2" data-options="required:true" style="width:65%; display: inline; text-align: right;" value="'+vafterpph+'" /> <label class="labeltotal">Rp/m2</label>'
        +   '</td>'
        +   '<td>'
        +       '<input type="hidden" name="vmode[]" value="tarif_sewa_sc_indoor_diskon" />'
        +       '<input type="hidden" name="vid[]" class="valsetid" value="'+id+'" />'
        +       '<input type="hidden" name="vketerangan[]" />'
        +       '<input type="text" class="vlxuangclass easyui-validatebox textbox form-control totalsewascindoordiskon" name="vnilai[]" placeholder="Isi %" data-options="required:true" style="width:65%; display: inline; text-align: right;" value="0" /> <label class="labeltotal">%</label>'

        +       '<input type="hidden" name="vmode[]" value="tarif_sewa_sc_indoor_after_diskon" />'
        +       '<input type="hidden" name="vid[]" class="valsetid" value="'+id+'" />'
        +       '<input type="hidden" name="vketerangan[]" />'
        +       '<input type="hidden" class="totalsewascindoorafterdiskon" name="vnilai[]" value="'+vtarifsc+'" />'
        +   '</td>'
        +   '<td>'
        +       '<input type="hidden" name="vmode[]" value="tarif_sewa_sc_indoor_after_ppn_diskon" />'
        +       '<input type="hidden" name="vid[]" class="valsetid" value="'+id+'" />'
        +       '<input type="hidden" name="vketerangan[]" />'
        +       '<input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control totalsewascindoorafterppndiskon" name="vnilai[]" placeholder="Isi Rp/m2" data-options="required:true" style="width:65%; display: inline; text-align: right;" value="'+vtarifsc+'" /> <label class="labeltotal">Rp/m2</label>'
        +   '</td>'
        +'</tr>'
        ;
        $("#tarifinfosewascindoor").append(vtable);
        hitunghargasewa("totalsewascindoordiskon");
    }
    else
    {
        vtable= ''
        +'<tr class="grouplokasiclass'+reqLokasiLooId+' groupclass'+id+'">'
        +   '<td>'+kode+' - '+lantai+' <i style="cursor:pointer" class="fa fa-times-circle text-danger" aria-hidden="true" onclick="hapusgroupclass('+id+');"></i></td>'
        +   '<td>:</td>'
        +   '<td>'
        +       '<input type="hidden" name="vmode[]" value="luas_sewa_outdoor" />'
        +       '<input type="hidden" name="vid[]" class="valsetid" value="'+id+'" />'
        +       '<input type="hidden" name="vketerangan[]" />'
        +       '<input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control totalluasoutdoor" name="vnilai[]" placeholder="Isi Luas (m2)" data-options="required:true" style="width:85%; display: inline; text-align: right;" value="'+vluas+'" /> <label class="labeltotal">m2</label>'
        +   '</td>'
        +'</tr>';
        $("#luassewaoutdoor").append(vtable);
        hitungluas("totalluasoutdoor");

        vtable= ''
        +'<tr class="grouplokasiclass'+reqLokasiLooId+' groupclass'+id+'">'
        +   '<td colspan="4">'
        +       kode+' - '+lantai
        +   '</td>'
        +'</tr>'
        +'<tr class="grouplokasiclass'+reqLokasiLooId+' groupclass'+id+'">'
        +   '<td>'
        +       '<input type="hidden" name="vmode[]" value="tarif_sewa_unit_outdoor" />'
        +       '<input type="hidden" name="vid[]" class="valsetid" value="'+id+'" />'
        +       '<input type="hidden" name="vketerangan[]" />'
        +       '<input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control totalsewaunitoutdoor" name="vnilai[]" placeholder="Isi Rp/m2" data-options="required:true" style="width:65%; display: inline; text-align: right;" value="'+vkdtarif+'" /> <label class="labeltotal">Rp/m2</label>'
        +   '</td>'
        +   '<td>'
        +       '<input type="hidden" name="vmode[]" value="tarif_sewa_unit_outdoor_diskon" />'
        +       '<input type="hidden" name="vid[]" class="valsetid" value="'+id+'" />'
        +       '<input type="hidden" name="vketerangan[]" />'
        +       '<input type="text" class="vlxuangclass easyui-validatebox textbox form-control totalsewaunitoutdoordiskon" name="vnilai[]" placeholder="Isi %" data-options="required:true" style="width:65%; display: inline; text-align: right;" value="0" /> <label class="labeltotal">%</label>'
        +   '</td>'
        +   '<td>'
        +       '<input type="hidden" name="vmode[]" value="tarif_sewa_unit_outdoor_after_diskon" />'
        +       '<input type="hidden" name="vid[]" class="valsetid" value="'+id+'" />'
        +       '<input type="hidden" name="vketerangan[]" />'
        +       '<input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control totalsewaunitoutdoorafterdiskon" name="vnilai[]" placeholder="Isi Rp/m2" data-options="required:true" style="width:65%; display: inline; text-align: right;" /> <label class="labeltotal">Rp/m2</label>'
        +   '</td>'
        +   '<td>'
        +       '<input type="hidden" name="vmode[]" value="tarif_sewa_unit_outdoor_harga" />'
        +       '<input type="hidden" name="vid[]" class="valsetid" value="'+id+'" />'
        +       '<input type="hidden" name="vketerangan[]" />'
        +       '<input type="hidden" class="totalsewaunitoutdoorsewaluas" value="'+vluas+'" />'
        +       '<input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control totalsewaunitoutdoorharga" name="vnilai[]" style="display: inline; text-align: right;" />'
        +   '</td>'
        +'</tr>'
        ;
        $("#tarifinfosewaoutdoor").append(vtable);
        hitunghargasewa("totalsewaunitoutdoordiskon");

        reqPph= $("#reqPph").val();
        reqPph= FormatAngkaNumber(reqPph);
        reqPph= notnullval(reqPph);
        vsebelumpph= FormatAngkaNumber(vtarifsc);
        vsebelumpph= notnullval(vsebelumpph);

        vafterpph= vsebelumpph;
        if(reqPph > 0)
        {
            vafterpph= Math.round(parseFloat(vsebelumpph) * parseFloat(reqPph));
        }

        vtable= ''
        +'<tr class="grouplokasiclass'+reqLokasiLooId+' groupclass'+id+'">'
        +   '<td colspan="4">'
        +       kode+' - '+lantai
        +   '</td>'
        +'</tr>'
        +'<tr class="grouplokasiclass'+reqLokasiLooId+' groupclass'+id+'">'
        +   '<td>'
        +       '<input type="hidden" name="vmode[]" value="tarif_sewa_sc_outdoor" />'
        +       '<input type="hidden" name="vid[]" class="valsetid" value="'+id+'" />'
        +       '<input type="hidden" name="vketerangan[]" />'
        +       '<input type="hidden" class="totalsewascoutdoor" name="vnilai[]" value="'+vtarifsc+'" />'

        +       '<input type="hidden" name="vmode[]" value="tarif_sewa_sc_outdoor_after_ppn" />'
        +       '<input type="hidden" name="vid[]" class="valsetid" value="'+id+'" />'
        +       '<input type="hidden" name="vketerangan[]" />'
        +       '<input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control totalsewascoutdoorafterppn" name="vnilai[]" placeholder="Isi Rp/m2" data-options="required:true" style="width:65%; display: inline; text-align: right;" value="'+vafterpph+'" /> <label class="labeltotal">Rp/m2</label>'
        +   '</td>'
        +   '<td>'
        +       '<input type="hidden" name="vmode[]" value="tarif_sewa_sc_outdoor_diskon" />'
        +       '<input type="hidden" name="vid[]" class="valsetid" value="'+id+'" />'
        +       '<input type="hidden" name="vketerangan[]" />'
        +       '<input type="text" class="vlxuangclass easyui-validatebox textbox form-control totalsewascoutdoordiskon" name="vnilai[]" placeholder="Isi %" data-options="required:true" style="width:65%; display: inline; text-align: right;" value="0" /> <label class="labeltotal">%</label>'

        +       '<input type="hidden" name="vmode[]" value="tarif_sewa_sc_outdoor_after_diskon" />'
        +       '<input type="hidden" name="vid[]" class="valsetid" value="'+id+'" />'
        +       '<input type="hidden" name="vketerangan[]" />'
        +       '<input type="hidden" class="totalsewascoutdoorafterdiskon" name="vnilai[]" value="'+vtarifsc+'" />'
        +   '</td>'
        +   '<td>'
        +       '<input type="hidden" name="vmode[]" value="tarif_sewa_sc_outdoor_after_ppn_diskon" />'
        +       '<input type="hidden" name="vid[]" class="valsetid" value="'+id+'" />'
        +       '<input type="hidden" name="vketerangan[]" />'
        +       '<input type="text" readonly class="vlxuangclass easyui-validatebox textbox form-control totalsewascoutdoorafterppndiskon" name="vnilai[]" placeholder="Isi Rp/m2" data-options="required:true" style="width:65%; display: inline; text-align: right;" value="'+vtarifsc+'" /> <label class="labeltotal">Rp/m2</label>'
        +   '</td>'
        +'</tr>'
        ;
        $("#tarifinfosewascoutdoor").append(vtable);
        hitunghargasewa("totalsewascoutdoordiskon");
    }

    callformatdyna();
}

var vlxformat;
callformatdyna();
function callformatdyna()
{
    $(function(){
        vlxformat = function(num){
            var str = num.toString().replace("", ""), parts = false, output = [], i = 1, formatted = null;
            if(str.indexOf(",") > 0) {
                parts = str.split(",");
                str = parts[0];
            }
            str = str.split("").reverse();
            for(var j = 0, len = str.length; j < len; j++) {
                if(str[j] != ".") {
                    output.push(str[j]);
                    if(i%3 == 0 && j < (len - 1)) {
                        output.push(".");
                    }
                    i++;
                }
            }
            formatted = output.reverse().join("");
            return( formatted + ((parts) ? "," + parts[1].substr(0, 2) : ""));
        };

        $('.vlxuangclass').bind('keyup paste', function(){
            var numeric = this.value.replace(/[^0-9\,]/g, '');
            $(this).val(vlxformat(numeric));
        });

        $(".totalluasindoor").keyup(function() {
            hitungluas("totalluasindoor");
        });

        $(".totalluasoutdoor").keyup(function() {
            hitungluas("totalluasoutdoor");
        });

        $(".totalsewaunitindoordiskon").keyup(function() {
            hitunghargasewa("totalsewaunitindoordiskon");
        });

        $(".totalsewaunitoutdoordiskon").keyup(function() {
            hitunghargasewa("totalsewaunitoutdoordiskon");
        });

        $(".totalsewascindoordiskon").keyup(function() {
            hitunghargasewa("totalsewascindoordiskon");
        });

        $(".totalsewascoutdoordiskon").keyup(function() {
            hitunghargasewa("totalsewascoutdoordiskon");
        });

        $("#reqPph, #reqPeriodeSewa").keyup(function() {
            hitungafterpph();
        });

        // default awal
        // hitungluas("totalluasindoor");
        // hitungluas("totalluasoutdoor");

    });
}

function hapusgroupclass(vid)
{
    $.messager.confirm('Konfirmasi','Yakin menghapus data terpilih ?',function(r){
        if (r){
            globalhapusgroupclass(vid, "");
        }
    });
}

function globalhapusgroupclass(vid, vmode)
{
    if(vmode == "lokasi")
    {
        $(".grouplokasiclass"+vid).remove();
    }
    else
    {
        $(".groupclass"+vid).remove();
    }

    hitungluas("totalluasoutdoor");
    hitungluas("totalluasindoor");

    hitungtotalharga("totalsewaunitindoordiskon");
    hitungtotalharga("totalsewaunitoutdoordiskon");
    hitungtotalharga("totalsewascindoordiskon");
    hitungtotalharga("totalsewascoutdoordiskon");
}

function hitungafterpph()
{
    reqPph= $("#reqPph").val();
    reqPph= FormatAngkaNumber(reqPph);
    reqPph= notnullval(reqPph);

    $(".totalsewascindoordiskon").each(function(){
        hitunghargasewa("totalsewascindoordiskon");
    });

    $(".totalsewascoutdoordiskon").each(function(){
        hitunghargasewa("totalsewascoutdoordiskon");
    });
}

function hitunghargasewa(vmode)
{
    reqPph= $("#reqPph").val();
    reqPph= FormatAngkaNumber(reqPph);
    reqPph= notnullval(reqPph);

    vtotal= 0;
    vindex= 0;
    $("."+vmode).each(function(){
        infoid= $(this).attr('id');
        infoval= $(this).val();
        infoval = infoval ? infoval : 0;
        vdiskon= FormatAngkaNumber(infoval);

        vawalharga= 0;
        if(vmode == "totalsewaunitindoordiskon")
        {
            vawalharga= $(this).closest('tr').find('.totalsewaunitindoor').val();
        }
        else if(vmode == "totalsewaunitoutdoordiskon")
        {
            vawalharga= $(this).closest('tr').find('.totalsewaunitoutdoor').val();
        }
        else if(vmode == "totalsewascindoordiskon")
        {
            vawalharga= $(this).closest('tr').find('.totalsewascindoor').val();
            // vawalharga= $(this).closest('tr').find('.totalsewascindoorafterpph').val();
        }
        else if(vmode == "totalsewascoutdoordiskon")
        {
            vawalharga= $(this).closest('tr').find('.totalsewascoutdoor').val();
            // vawalharga= $(this).closest('tr').find('.totalsewascoutdoorafterpph').val();
        }

        vawalharga= FormatAngkaNumber(vawalharga);
        vawalharga= notnullval(vawalharga);

        vafterdiskon= vawalharga - ( (vdiskon / 100) * vawalharga );

        if(vmode == "totalsewaunitindoordiskon")
        {
            $(this).closest('tr').find('.totalsewaunitindoorafterdiskon').val(setformat(vafterdiskon));
        }
        else if(vmode == "totalsewaunitoutdoordiskon")
        {
            $(this).closest('tr').find('.totalsewaunitoutdoorafterdiskon').val(setformat(vafterdiskon));
        }
        else if(vmode == "totalsewascindoordiskon")
        {
            $(this).closest('tr').find('.totalsewascindoorafterdiskon').val(setformat(vafterdiskon));
            vafterppndiskon= vafterdiskon;
            if(reqPph > 0)
            {
                vafterppndiskon= Math.round(parseFloat(vafterdiskon) * parseFloat(reqPph));
            }
            $(this).closest('tr').find('.totalsewascindoorafterppndiskon').val(setformat(vafterppndiskon));
        }
        else if(vmode == "totalsewascoutdoordiskon")
        {
            $(this).closest('tr').find('.totalsewascoutdoorafterdiskon').val(setformat(vafterdiskon));
            vafterppndiskon= vafterdiskon;
            if(reqPph > 0)
            {
                vafterppndiskon= Math.round(parseFloat(vafterdiskon) * parseFloat(reqPph));
            }
            $(this).closest('tr').find('.totalsewascoutdoorafterppndiskon').val(setformat(vafterppndiskon));
        }

        if(vmode == "totalsewaunitindoordiskon" || "totalsewaunitoutdoordiskon")
        {
            vsewaluas= 0;
            if(vmode == "totalsewaunitindoordiskon")
            {
                vsewaluas= $(this).closest('tr').find('.totalsewaunitindoorsewaluas').val();
            }
            else if(vmode == "totalsewaunitoutdoordiskon")
            {
                vsewaluas= $(this).closest('tr').find('.totalsewaunitoutdoorsewaluas').val();
            }

            vsewaluas= FormatAngkaNumber(vsewaluas);
            vsewaluas= notnullval(vsewaluas);

            vtotalharga= vafterdiskon * vsewaluas;
            if(vmode == "totalsewaunitindoordiskon")
            {
                $(this).closest('tr').find('.totalsewaunitindoorharga').val(setformat(vtotalharga));
            }
            else if(vmode == "totalsewaunitoutdoordiskon")
            {
                $(this).closest('tr').find('.totalsewaunitoutdoorharga').val(setformat(vtotalharga));
            }
        }
    });

    vmodetotal= "";
    if(vmode == "totalsewaunitindoordiskon")
    {
        vmodetotal= "totalsewaunitindoorharga";
    }
    else if(vmode == "totalsewaunitoutdoordiskon")
    {
        vmodetotal= "totalsewaunitoutdoorharga";
    }
    else if(vmode == "totalsewascindoordiskon")
    {
        // vmodetotal= "totalsewascindoorafterdiskon";
        vmodetotal= "totalsewascindoorafterppndiskon";
    }
    else if(vmode == "totalsewascoutdoordiskon")
    {
        // vmodetotal= "totalsewascoutdoorafterdiskon";
        vmodetotal= "totalsewascoutdoorafterppndiskon";
    }

    if(vmodetotal !== "")
    {
        hitungtotalharga(vmode);
    }
}

function hitungtotalharga(vmode)
{
    vmodetotal= "";
    if(vmode == "totalsewaunitindoordiskon")
    {
        vmodetotal= "totalsewaunitindoorharga";
    }
    else if(vmode == "totalsewaunitoutdoordiskon")
    {
        vmodetotal= "totalsewaunitoutdoorharga";
    }
    else if(vmode == "totalsewascindoordiskon")
    {
        // vmodetotal= "totalsewascindoorafterdiskon";
        vmodetotal= "totalsewascindoorafterppndiskon";
    }
    else if(vmode == "totalsewascoutdoordiskon")
    {
        // vmodetotal= "totalsewascoutdoorafterdiskon";
        vmodetotal= "totalsewascoutdoorafterppndiskon";

    }

    vtotal= 0;
    $("."+vmodetotal).each(function(){
        infoval= $(this).val();
        infoval = infoval ? infoval : 0;
        infoval= FormatAngkaNumber(infoval);

        vtotal= parseFloat(vtotal) + parseFloat(infoval);
    });

    vtotal= setformat(vtotal);
    if(vmode == "totalsewaunitindoordiskon")
    {
        $("#reqHargaIndoorSewa").val(vtotal);
    }
    else if(vmode == "totalsewaunitoutdoordiskon")
    {
        $("#reqHargaOutdoorSewa").val(vtotal);
    }
    else if(vmode == "totalsewascindoordiskon")
    {
        $("#reqHargaIndoorService").val(vtotal);
    }
    else if(vmode == "totalsewascoutdoordiskon")
    {
        $("#reqHargaOutdoorService").val(vtotal);
    }

    // rincian sewa
    vtotal= 0;
    $(".totalsewaunitindoorafterdiskon, .totalsewaunitoutdoorafterdiskon").each(function(){
        infoid= $(this).attr('id');
        infoval= $(this).val();
        infoval = infoval ? infoval : 0;
        infoval= FormatAngkaNumber(infoval);

        vtotal= parseFloat(vtotal) + parseFloat(infoval);
        // console.log(infoval);
    });
    // console.log(vtotal);
    vtotal= setformat(vtotal);
    $("#reqSewaBiayaSatuanUnit").val(vtotal);
    // ================================================

    reqHargaIndoorSewa= getvalnumber($("#reqHargaIndoorSewa").val());
    reqHargaOutdoorSewa= getvalnumber($("#reqHargaOutdoorSewa").val());
    // console.log(reqHargaIndoorSewa+"+"+reqHargaOutdoorSewa);
    vtotal= parseFloat(reqHargaIndoorSewa) + parseFloat(reqHargaOutdoorSewa);
    vtotal= setformat(vtotal);
    $("#reqSewaBiayaPerBulanUnit").val(vtotal);
    // ================================================

    reqSewaBiayaPerBulanUnit= getvalnumber($("#reqSewaBiayaPerBulanUnit").val());
    reqPeriodeSewa= getvalnumber($("#reqPeriodeSewa").val());
    vtotal= parseFloat(reqSewaBiayaPerBulanUnit) * parseFloat(reqPeriodeSewa);
    vtotal= setformat(vtotal);
    $("#reqSewaTotalBiayaUnit").val(vtotal);
    // ================================================

    vtotal= 0;
    // $(".totalsewascindoorafterdiskon, .totalsewascoutdoorafterdiskon").each(function(){
    $(".totalsewascindoorafterppndiskon, .totalsewascoutdoorafterppndiskon").each(function(){
        infoid= $(this).attr('id');
        infoval= $(this).val();
        infoval = infoval ? infoval : 0;
        infoval= FormatAngkaNumber(infoval);

        vtotal= parseFloat(vtotal) + parseFloat(infoval);
        // console.log(infoval);
    });
    // console.log(vtotal);
    vtotal= setformat(vtotal);
    $("#reqSewaBiayaSatuanService").val(vtotal);
    // ================================================

    reqHargaIndoorService= getvalnumber($("#reqHargaIndoorSewa").val());
    reqHargaOutdoorService= getvalnumber($("#reqHargaOutdoorService").val());
    // console.log(reqHargaIndoorService+"+"+reqHargaOutdoorService);
    vtotal= parseFloat(reqHargaIndoorService) + parseFloat(reqHargaOutdoorService);
    vtotal= setformat(vtotal);
    $("#reqSewaBiayaPerBulanService").val(vtotal);
    // ================================================

    reqSewaBiayaPerBulanService= getvalnumber($("#reqSewaBiayaPerBulanService").val());
    reqPeriodeSewa= getvalnumber($("#reqPeriodeSewa").val());
    vtotal= parseFloat(reqSewaBiayaPerBulanService) * parseFloat(reqPeriodeSewa);
    vtotal= setformat(vtotal);
    $("#reqSewaTotalBiayaService").val(vtotal);
    // ================================================

    reqSewaBiayaPerBulanUnit= getvalnumber($("#reqSewaBiayaPerBulanUnit").val());
    reqSewaBiayaPerBulanService= getvalnumber($("#reqSewaBiayaPerBulanService").val());
    // console.log(reqSewaBiayaPerBulanUnit+"+"+reqSewaBiayaPerBulanService);
    vtotal= parseFloat(reqSewaBiayaPerBulanUnit) + parseFloat(reqSewaBiayaPerBulanService);
    vtotal= setformat(vtotal);
    $("#reqTotalBiayaPerBulanNoPpn").val(vtotal);
    // ================================================

    reqSewaTotalBiayaUnit= getvalnumber($("#reqSewaTotalBiayaUnit").val());
    reqSewaTotalBiayaService= getvalnumber($("#reqSewaTotalBiayaService").val());
    // console.log(reqSewaTotalBiayaUnit+"+"+reqSewaTotalBiayaService);
    vtotal= parseFloat(reqSewaTotalBiayaUnit) + parseFloat(reqSewaTotalBiayaService);
    vtotal= setformat(vtotal);
    $("#reqTotalBiayaNoPpn").val(vtotal);
    // ================================================

    reqTotalBiayaPerBulanPpn= reqTotalBiayaPpn= 0;
    reqPph= getvalnumber($("#reqPph").val());
    if(reqPph > 0)
    {
        reqTotalBiayaPerBulanPpn= getvalnumber($("#reqTotalBiayaPerBulanNoPpn").val());
        reqTotalBiayaPerBulanPpn= parseFloat(reqTotalBiayaPerBulanPpn) * parseFloat(reqPph);

        reqTotalBiayaPpn= getvalnumber($("#reqTotalBiayaNoPpn").val());
        reqTotalBiayaPpn= parseFloat(reqTotalBiayaPpn) * parseFloat(reqPph);
    }
    $("#reqTotalBiayaPerBulanPpn").val(setformat(reqTotalBiayaPerBulanPpn));
    $("#reqTotalBiayaPpn").val(setformat(reqTotalBiayaPpn));
    // ================================================

    reqTotalBiayaPerBulanNoPpn= getvalnumber($("#reqTotalBiayaPerBulanNoPpn").val());
    vtotal= parseFloat(2) * parseFloat(reqTotalBiayaPerBulanNoPpn);
    vtotal= setformat(vtotal);
    $("#reqSecurityDeposit").val(vtotal);
    // ================================================
    
}

function getvalnumber(v)
{
    v= FormatAngkaNumber(v);
    v= notnullval(v);
    return v;
}

function hitungluas(vmode)
{
    vtotal= 0;
    vindex= 0;
    $("."+vmode).each(function(){
        infoid= $(this).attr('id');
        infoval= $(this).val();
        infoval = infoval ? infoval : 0;
        infoval= FormatAngkaNumber(infoval);
        // console.log(vmode+"xx"+infoval);
        vtotal= parseFloat(vtotal) + parseFloat(infoval);

        if(vmode == "totalluasindoor" || vmode == "totalluasoutdoor")
        {
            // $(this).parents().find('.valsetid').val(vindex);
            // $(this).closest('tr').find('.valsetid').val(vindex);
            vindex++;
        }

    });

    vtotal= setformat(vtotal);
    if(vmode == "totalluasindoor")
    {
        $("#reqTotalLuasIndoor").val(vtotal);
    }
    else if(vmode == "totalluasoutdoor")
    {
        $("#reqTotalLuasOutdoor").val(vtotal);
    }

    if(vmode == "totalluasindoor" || vmode == "totalluasoutdoor")
    {
        reqTotalLuasIndoor= $("#reqTotalLuasIndoor").val();
        reqTotalLuasIndoor= FormatAngkaNumber($("#reqTotalLuasIndoor").val());
        reqTotalLuasIndoor= notnullval(reqTotalLuasIndoor);
        // console.log(reqTotalLuasIndoor);

        reqTotalLuasOutdoor= $("#reqTotalLuasOutdoor").val();
        reqTotalLuasOutdoor= FormatAngkaNumber($("#reqTotalLuasOutdoor").val());
        reqTotalLuasOutdoor= notnullval(reqTotalLuasOutdoor);

        reqTotalLuas= reqTotalLuasIndoor + reqTotalLuasOutdoor;
        reqTotalLuas= setformat(reqTotalLuas);
        $("#reqTotalLuas").val(reqTotalLuas);
    }
}

function setformat(v)
{
    v= v.toString();
    v= ReplaceString('.',',',v);
    v= vlxformat(v);
    return v;
}

function notnullval(v)
{
    v= v ? v : 0;
    v= parseFloat(v);
    return v;
}

function submitPreview() 
{
    parent.openAdd('app/loadUrl/report/loo_cetak/?reqId=<?=$reqId?>&templateSurat=psm');
}

function submitLampiran() 
{
    // parent.openAdd('app/loadUrl/report/loo_cetak/?reqId=<?=$reqId?>&templateSurat=psm_lampiran');
}

function submitForm(reqStatusData){
    
    $("#reqStatusData").val(reqStatusData);

    var pesan = "Simpan surat sebagai draft?";
    if (reqStatusData == "POSTING")
    {
        // tambahan khusus
        <?
        if ($reqStatusData == "VALIDASI" && $reqUserId != $sessid) 
        {
            if($reqUserPengirimId == $sessid && $reqKelompokJabatan == "DIREKSI")
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
    if (reqStatusData == "REVISI")
    {
        infopesandetil= " kembalikan surat ke staff anda?";
    }

    if(reqStatusData == "PARAF" || reqStatusData == "UBAHDATADRAFTPARAF")
    {
        infopesandetil= " paraf naskah?";
    }

    if (reqStatusData == "POSTING" || reqStatusData == "PARAF" || reqStatusData == "REVISI" || reqStatusData == "UBAHDATAPOSTING" || reqStatusData == "UBAHDATADRAFTPARAF")
    {
        infocontent= '<form action="" class="formName">' +
        '<div class="form-group">' +
        '<label>Isi komentar jika ingin mengirim dokumen ini!</label>' +
        '<input type="hidden" id="infoStatusApprove" value="" />' +
        '<input type="text" placeholder="Tuliskan komentar anda..." class="name form-control" required />' +
        '</div>' +
        '</form>';

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

                        // setloading(reqStatusData);

                        <?
                        if(empty($reqId) || ($reqStatusData == "DRAFT" && !empty($reqId)) )
                        {
                        ?>
                            // infoStatusApprove= $("#infoStatusApprove").val();
                            // $("#reqStatusApprove").val(infoStatusApprove);
                        <?
                        }
                        ?>
                        // return false;

                        setsimpan(reqStatusData);
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
    else if (reqStatusData == "UBAHDATAPARAF" || reqStatusData == "UBAHDATAREVISI" || reqStatusData == "UBAHDATAVALIDASI")
    {
        setsimpan(reqStatusData);
    }
    else
    {
        $.messager.confirm('Konfirmasi', pesan, function(r) {
            if (r) {
                setsimpan(reqStatusData);
            }
        });
    }
}

function setsimpan(reqStatusData)
{
    $('#ff').form('submit',{
        url:'web/trpsm_json/add',
        onSubmit:function(){

            if($(this).form('enableValidation').form('validate'))
            {
                setloading(reqStatusData);
                var win = $.messager.progress({title:'Proses simpan data', msg:'Proses simpan data...'});
            }
            return $(this).form('enableValidation').form('validate');
        },
        success:function(data){
            $.messager.progress('close');
            $("#btn"+reqStatusData).removeClass('ic2-outlined-btn ic2-outlined-spin-blue-btn');
            $(".buttonspiner").hide();

            <?
            if(!empty($cekquery))
            {
            ?>
            console.log(data);return false;
            <?
            }
            ?>
            data= data.split("xxx");
            rowid= data[0];
            infodata= data[1];

            if(rowid == "")
            {
                $.messager.alert('Info', infodata, 'warning');
            }
            else
            {
                vreload= "main/index/psm_add/?reqId="+rowid;
                <?
                if(!empty($reqVmode))
                {
                ?>
                vreload+= "&reqMode=<?=$reqVmode?>";
                <?
                }
                ?>
                $.messager.alertTopLink('Info', infodata, 'info', vreload);
            }
        }
    });
}

function setloading(reqStatusData)
{
    $("#btn"+reqStatusData).addClass('ic2-outlined-btn ic2-outlined-spin-blue-btn');
    $(".buttonspiner").show();
}

function clearForm(){
    $('#ff').form('clear');
}
            
</script>

<link rel="stylesheet" type="text/css" href="lib/jquery-confirm-master/css/jquery-confirm.css"/>
<script type="text/javascript" src="lib/jquery-confirm-master/js/jquery-confirm.js"></script>

<style type="text/css">
    fieldset {
        border: 1px solid rgba(21, 125, 186, 0.1) !important;
        margin-top: 0px !important;
        padding: 0px 10px !important;
    }
</style>