<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");
$this->load->model("SuratMasuk");
$this->load->model("SatuanKerja");
$this->load->model("Disposisi");
$this->load->model("DisposisiKelompok");
$this->load->model("SuratMasukReference");

$infoid= $reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqMode= $this->input->get("reqMode");
$reqStatusSurat= $this->input->get("reqStatusSurat");
$reqNamaDok= $this->input->get("reqNamaDok");
$reqNamaDok= str_replace("_", " ", $reqNamaDok);

if(!empty($reqRowId))
{
    $surat_masuk= new SuratMasuk();
    $statement= " AND A.SURAT_MASUK_ID = ".$reqId." AND B.DISPOSISI_ID = ".$reqRowId;
    $surat_masuk->selectByParamsSuratMasuk(array(), -1,-1, $statement);
    $surat_masuk->firstRow();
    // echo $surat_masuk->query;exit;
    $infostatus= $surat_masuk->getField("INFO_STATUS");
    $infoketeranganisi= $surat_masuk->getField("KETERANGAN_ISI");
    $infoklasifikasi= $surat_masuk->getField("KODE_INFO");
    $infostatusbantu= $surat_masuk->getField("STATUS_BANTU");
    $infonamasatker= $surat_masuk->getField("NAMA_SATKER");
    $infosatuankerjaidtujuan= $surat_masuk->getField("SATUAN_KERJA_ID_TUJUAN");
    // echo $infonamasatker;exit;
    $infonomorsurat= $surat_masuk->getField("NOMOR_SURAT_INFO");
    $infodariinfo= $surat_masuk->getField("DARI_INFO");
    $infotanggalentri= getFormattedExtDateTimeCheck($surat_masuk->getField("TANGGAL_ENTRI"), false);
    $infosifatnaskah= $surat_masuk->getField("SIFAT_NASKAH");
    $infoperihal= $surat_masuk->getField("PERIHAL");
    $infosatuankerjaid= $surat_masuk->getField("SATUAN_KERJA_ID_ASAL");
    $infopemesansatuankerjaid= $surat_masuk->getField("PEMESAN_SATUAN_KERJA_ID");
    $infopemesansatuankerjaisi= $surat_masuk->getField("PEMESAN_SATUAN_KERJA_ISI");
	
	$checkdisposisi= new Disposisi();
    $statement= " AND A.STATUS_BANTU IS NULL AND A.SURAT_MASUK_ID = ".$reqId." AND A.SATUAN_KERJA_ID_TUJUAN = '".$infosatuankerjaidtujuan."'";
    $checkdisposisi->selectByParams(array(), -1,-1, $statement);
    $checkdisposisi->firstRow();
    // echo $checkdisposisi->query;exit;
    $checkdisposisiid= $checkdisposisi->getField("DISPOSISI_ID");
}
else
{
    $surat_masuk= new SuratMasuk();
    $statement= " AND A.SURAT_MASUK_ID = ".$reqId;
    $surat_masuk->selectByParamsStatus(array(), -1,-1, $this->ID, $statement);
    $surat_masuk->firstRow();
    // echo $surat_masuk->query;exit;

    if(empty($surat_masuk->getField("SURAT_MASUK_ID")))
    {
        $statement= " AND A.SURAT_MASUK_ID = ".$reqId." AND (A.STATUS_SURAT IN ('TATAUSAHA','POSTING') OR A.STATUS_SURAT LIKE 'TU%')";
        $surat_masuk->selectByParamsSuratKeluar(array(), -1,-1, $this->ID, $statement);
        $surat_masuk->firstRow();
    }

    // echo $surat_masuk->query;exit;
    $infoketeranganisi= $surat_masuk->getField("KETERANGAN_ISI");
    $infoklasifikasi= $surat_masuk->getField("KODE_INFO");
    $infostatus= $surat_masuk->getField("INFO_STATUS");
    $infonomorsurat= $surat_masuk->getField("NOMOR_SURAT_INFO");
    $infodariinfo= $surat_masuk->getField("DARI_INFO");
    $infotanggalentri= getFormattedExtDateTimeCheck($surat_masuk->getField("TANGGAL_ENTRI"), false);
    $infosifatnaskah= $surat_masuk->getField("SIFAT_NASKAH");
    $infoperihal= $surat_masuk->getField("PERIHAL");
    $infosatuankerjaid= $surat_masuk->getField("SATUAN_KERJA_ID_ASAL");
    $infopemesansatuankerjaid= $surat_masuk->getField("PEMESAN_SATUAN_KERJA_ID");
    $infopemesansatuankerjaisi= $surat_masuk->getField("PEMESAN_SATUAN_KERJA_ISI");
}

$disposisi= new Disposisi();
$reqKepada = $disposisi->getJson(array("SURAT_MASUK_ID" => $reqId, "STATUS_DISPOSISI" => "TUJUAN"));
$reqTembusan = $disposisi->getJson(array("SURAT_MASUK_ID" => $reqId, "STATUS_DISPOSISI" => "TEMBUSAN"));

$disposisi_kelompok = new DisposisiKelompok();
$reqKepadaKelompok = $disposisi_kelompok->getJson(array("SURAT_MASUK_ID" => $reqId, "STATUS_DISPOSISI" => "TUJUAN"));
$reqTembusanKelompok = $disposisi_kelompok->getJson(array("SURAT_MASUK_ID" => $reqId, "STATUS_DISPOSISI" => "TEMBUSAN"));

$satuan_kerja= new SatuanKerja();
$satuan_kerja->selectByParams(array(), -1, -1, " AND SATUAN_KERJA_ID = '".$infopemesansatuankerjaid."'", " ORDER BY KODE_SO ASC ");
$satuan_kerja->firstRow();
// echo $satuan_kerja->query;exit;
$infoppemesanenandatangankode= $satuan_kerja->getField("KODE_SURAT");
$infopemesanpenandatangannamapejabat= $satuan_kerja->getField("NAMA_PEGAWAI");
$infopemesanpenandatangannip= $satuan_kerja->getField("NIP");
$infopemesanjabatan= $satuan_kerja->getField("JABATAN");

$satuan_kerja= new SatuanKerja();
$satuan_kerja->selectByParams(array(), -1, -1, " AND SATUAN_KERJA_ID = '".$infosatuankerjaid."'", " ORDER BY KODE_SO ASC ");
$satuan_kerja->firstRow();
// echo $satuan_kerja->query;exit;
$infopenandatangankode= $satuan_kerja->getField("KODE_SURAT");
$infopenandatangannamapejabat= $satuan_kerja->getField("NAMA_PEGAWAI");
$infopenandatangannip= $satuan_kerja->getField("NIP");
$infojabatan= $satuan_kerja->getField("JABATAN");

$arrlog= array();
$index_data= 0;
$set= new SuratMasuk();
$set->selectByParamsDataLog(array("A.SURAT_MASUK_ID"=>$reqId),-1,-1);
while($set->nextRow())
{
    $arrlog[$index_data]["TANGGAL"] = dateTimeToPageCheck($set->getField("TANGGAL"));
    $arrlog[$index_data]["INFORMASI"] = $set->getField("INFORMASI");
    $arrlog[$index_data]["STATUS_SURAT"] = $set->getField("STATUS_SURAT");
    $arrlog[$index_data]["CATATAN"] = $set->getField("CATATAN");
    $index_data++;
}
$jumlahlog= $index_data;

$arrattachment= array();
$index_data= 0;
$set= new SuratMasuk();
$set->selectByParamsAttachment(array("A.SURAT_MASUK_ID" => (int)$reqId));
while($set->nextRow())
{
    $arrattachment[$index_data]["NAMA"] = $set->getField("NAMA");
    $arrattachment[$index_data]["UKURAN"] = $set->getField("UKURAN");
    $arrattachment[$index_data]["ATTACHMENT"] = $set->getField("ATTACHMENT");
    $arrattachment[$index_data]["TIPE"] = $set->getField("TIPE");
    $arrattachment[$index_data]["SURAT_MASUK_ATTACHMENT_ID"] = $set->getField("SURAT_MASUK_ATTACHMENT_ID");
    $index_data++;
}
$jumlahattachment= $index_data;

$infoteruskan= "";
if($infostatusbantu == "1" && empty($checkdisposisiid))
{
    $infoteruskan= "1";
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="<?=base_url()?>css/cetaknew.css">
</head>
<body>
    <div class="container">
     
        <div class="center" style="margin-bottom: 30px; text-align: center;">
            <p style="font-family: calibri; font-size: 14pt; text-transform: uppercase;"><strong>AGENDA <?=$reqNamaDok?></strong>
            </p>
        </div>
        <p style="font-family: calibri; font-size: 12pt; margin-bottom: 0px;"><strong>A.    INFORMASI SURAT</strong> </p>
        <table style="font-family: calibri; font-size: 12pt; width: 500px;  margin-right: auto; padding-left: 18px;">
            <tr>
                <td style="width: 150px">Nomor Surat</td>
                <td style="width: 15px">:</td>
                <td>
                    <?=$infonomorsurat?>
                </td>
            </tr>
            <tr>
                <td>Tanggal Surat</td>
                <td>:</td>
                <td><?=$infotanggalentri?></td>
            </tr>
            <tr>
                <td>Pola Klasifikasi</td>
                <td>:</td>
                <td><?=$infoklasifikasi?></td>
            </tr>
        </table>    

        <p style="font-family: calibri; font-size: 12pt; margin-bottom: 0px;"><strong>B.    ALAMAT SURAT</strong> </p>
        <table style="font-family: calibri; font-size: 12pt; width: 500px;  margin-right: auto; padding-left: 18px;">
            <tr>
                <td style="width: 150px">Penandatangan</td>
                <td style="width: 15px">:</td>
                <td><?=$infodariinfo?></td>
            </tr>
            <tr>
                <td>Penerima</td>
                <td>:</td>
                <td>
                    <?
                    $indexdata= 0;
                    $arrKepada = json_decode($reqKepada);
                    foreach ($arrKepada as $key => $value) {
                    ?>
                        <?=$value->SATUAN_KERJA?><br>
                    <?
                        $indexdata++;
                    }

                    $arrKepadaKelompok = json_decode($reqKepadaKelompok);
                    foreach ($arrKepadaKelompok as $key => $value) {
                    ?>
                        <?=$value->NAMA_KELOMPOK?><br>
                    <?
                        $indexdata++;
                    }
                    ?>
                    <?
                    if($indexdata == 0)
                    {
                    ?>
                        <span>-</span>
                    <?
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td>Tembusan</td>
                <td>:</td>
                <td>
                    <?
                    $indexdata= 0;
                    $arrTembusan = json_decode($reqTembusan);
                    foreach ($arrTembusan as $key => $value) {
                    ?>
                        <?=$value->SATUAN_KERJA?><br>
                    <?
                        $indexdata++;
                    }

                    $arrTembusanKelompok = json_decode($reqTembusanKelompok);
                    foreach ($arrTembusanKelompok as $key => $value) {
                    ?>
                        <?=$value->NAMA_KELOMPOK?><br>
                    <?
                        $indexdata++;
                    }

                    if($indexdata == 0)
                    {
                    ?>
                        <span>-</span>
                    <?
                    }
                    ?>
                </td>
            </tr>
        </table>                

        <p style="font-family: calibri; font-size: 12pt; margin-bottom: 0px;"><strong>C.    PERIHAL SURAT</strong> </p>
        <table style="font-family: calibri; font-size: 12pt; width: 500px;  margin-right: auto; padding-left: 18px;">
            <tr>
                <td style="width: 150px">Perihal</td>
                <td style="width: 15px">:</td>
                <td><?=$infoperihal?></td>
            </tr>
            <tr>
                <td>Sifat Surat</td>
                <td>:</td>
                <td><?=$infosifatnaskah?></td>
            </tr>
            <tr>
                <td>Catatan <!--Pemesan--></td>
                <td>:</td>
                <td><?=$infoketeranganisi?></td>
            </tr>
        </table>

        <p style="font-family: calibri; font-size: 12pt; margin-bottom: 0px;"><strong>D.    RIWAYAT KONSEP SURAT</strong> </p>
        <table style="font-family: calibri; font-size: 12pt; width: 600px;  margin-right: auto; padding-left: 18px;">
            <tr style="width: 150px">
                <td style="width: 15px" colspan="2">
                    <?
                    for($index_data=0; $index_data < $jumlahlog; $index_data++)
                    {
                    ?>
                        <span><?=$arrlog[$index_data]["TANGGAL"]?>, <?=$arrlog[$index_data]["INFORMASI"]?>, [<?=$arrlog[$index_data]["STATUS_SURAT"]?>].</span>
                        <i><span><?=$arrlog[$index_data]["CATATAN"]?></span></i><br>
                    <?
                    }
                    ?>
                </td>
            </tr>
        </table>
                        
    </div>
</body>
</html>