<?php
/* INCLUDE FILE */
include_once("functions/date.func.php");
include_once("functions/default.func.php");
include_once("functions/string.func.php");
$reqRowId= $this->input->get("reqRowId");
$reqId= $this->input->get("reqId");

$this->load->model("SuratMasuk");
$this->load->model("SatuanKerja");
$this->load->model("Disposisi");
$this->load->model("DisposisiKelompok");

$set= new SuratMasuk();
$set->selectByParamsInfoDisposisi(array("A.DISPOSISI_ID"=>$reqRowId));
$set->firstRow();
    // echo $set->query;exit;
    // $infodisposisiid= $set->getField("DISPOSISI_ID");
    // $infodisposisiparentid= $set->getField("DISPOSISI_PARENT_ID");
$infodisposisistatus= $set->getField("STATUS_DISPOSISI");
$infodisposisiasal= $set->getField("NAMA_SATKER_ASAL");
$infodisposisiuser= $set->getField("NAMA_USER_ASAL");
$infodisposisiasalkepada= $set->getField("NAMA_SATKER");
$infodisposisiuserkepada= $set->getField("NAMA_USER");
$infodisposisitanggal= getFormattedInfoDateTimeCheck($set->getField("TANGGAL_DISPOSISI"));
$infodisposisikepada= $set->getField("INFO_KEPADA");
$infodisposisitindakan= $set->getField("TINDAKAN");
// echo $infodisposisitindakan;exit;
$infodisposisicatatan= $set->getField("CATATAN");
$infodisposisisifat= $set->getField("SIFAT_NAMA");
unset($set);
setlocale (LC_TIME, 'id_ID');
$dateObject = new DateTime($infodisposisitanggal);
$disposisitanggal= $dateObject->format("d-M-Y  H:i A");
// echo $disposisitanggal;exit;

$kondisilihat= "";
if($infodisposisistatus == "BALASAN"){}
else
{
    $kondisilihat= "1";
}

if(!empty($reqRowId))
{
    $surat_masuk= new SuratMasuk();
    $statement= " AND A.SURAT_MASUK_ID = ".$reqId." AND B.DISPOSISI_ID = ".$reqRowId;
    $surat_masuk->selectByParamsSuratMasuk(array(), -1,-1, $statement);
    $surat_masuk->firstRow();
    // echo $surat_masuk->query;exit;
    $infostatus= $surat_masuk->getField("INFO_STATUS");
    $infonomorsurat= $surat_masuk->getField("NOMOR");
    $infotanggalentri= getFormattedExtDateTimeCheck($surat_masuk->getField("TANGGAL_ENTRI"), false);
    $infosifatnaskah= $surat_masuk->getField("SIFAT_NASKAH");
    $infoperihal= $surat_masuk->getField("PERIHAL");
    $infosatuankerjaid= $surat_masuk->getField("SATUAN_KERJA_ID_ASAL");
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
    $infostatus= $surat_masuk->getField("INFO_STATUS");
    $infonomorsurat= $surat_masuk->getField("INFO_NOMOR_SURAT");
    $infotanggalentri= getFormattedExtDateTimeCheck($surat_masuk->getField("TANGGAL_ENTRI"), false);
    $infosifatnaskah= $surat_masuk->getField("SIFAT_NASKAH");
    $infoperihal= $surat_masuk->getField("PERIHAL");
    $infosatuankerjaid= $surat_masuk->getField("SATUAN_KERJA_ID_ASAL");
}

$disposisi= new Disposisi();
$reqKepada = $disposisi->getJson(array("SURAT_MASUK_ID" => $reqId, "STATUS_DISPOSISI" => "TUJUAN"));
$reqTembusan = $disposisi->getJson(array("SURAT_MASUK_ID" => $reqId, "STATUS_DISPOSISI" => "TEMBUSAN"));

$disposisi_kelompok = new DisposisiKelompok();
$reqKepadaKelompok = $disposisi_kelompok->getJson(array("SURAT_MASUK_ID" => $reqId, "STATUS_DISPOSISI" => "TUJUAN"));
$reqTembusanKelompok = $disposisi_kelompok->getJson(array("SURAT_MASUK_ID" => $reqId, "STATUS_DISPOSISI" => "TEMBUSAN"));

$satuan_kerja= new SatuanKerja();
$satuan_kerja->selectByParams(array(), -1, -1, " AND SATUAN_KERJA_ID = '".$infosatuankerjaid."'", " ORDER BY KODE_SO ASC ");
$satuan_kerja->firstRow();
// echo $satuan_kerja->query;exit;
$infopenandatangankode= $satuan_kerja->getField("KODE_SURAT");
$infopenandatangannamapejabat= $satuan_kerja->getField("NAMA_PEGAWAI");
$infopenandatangannip= $satuan_kerja->getField("NIP");
$infojabatan= $satuan_kerja->getField("JABATAN");

$arrdisposisi= array();
$index_data= 0;
$set= new Disposisi();
$set->selectByParams(array("A.SURAT_MASUK_ID"=>$reqId),-1,-1, "", "ORDER BY A.TANGGAL_DISPOSISI");
while($set->nextRow())
{
    $arrdisposisi[$index_data]["DISPOSISI_ID"]= $set->getField("DISPOSISI_ID");
    $arrdisposisi[$index_data]["DISPOSISI_PARENT_ID"]= $set->getField("DISPOSISI_PARENT_ID");
    $arrdisposisi[$index_data]["INFO_STATUS_DISPOSISI"]= $set->getField("INFO_STATUS_DISPOSISI");
    $arrdisposisi[$index_data]["NAMA_SATKER_ASAL"]= $set->getField("NAMA_SATKER_ASAL");
    $arrdisposisi[$index_data]["NAMA_SATKER"]= $set->getField("NAMA_SATKER");
    // NAMA_USER_ASAL
    // NAMA_USER
    $arrdisposisi[$index_data]["ISI"]= $set->getField("ISI");
    $arrdisposisi[$index_data]["KETERANGAN"]= $set->getField("KETERANGAN");
    $arrdisposisi[$index_data]["INFO_TANGGAL_DISPOSISI"]= getFormattedInfoDateTimeCheck($set->getField("INFO_TANGGAL_DISPOSISI"), "/");
    $index_data++;
}

$arrheaderdisposisi= array();
$infodisposisiid= "";
$arrayKey= in_array_column("0", "DISPOSISI_PARENT_ID", $arrdisposisi);
if(!empty($arrayKey))
{
    $index_data= $arrayKey[0];
    $infodisposisiid= $arrdisposisi[$index_data]["DISPOSISI_ID"];
    // print_r($arrayKey);exit;
}


$infonotatindakan= "";
$jumlahheader= 0;
if(!empty($infodisposisiid))
{
    $arrayKey= in_array_column($infodisposisiid, "DISPOSISI_PARENT_ID", $arrdisposisi);
    // print_r($arrayKey);exit;
    if(!empty($arrayKey))
    {
        for($i=0; $i < count($arrayKey); $i++)
        {
            $arrdata= [];
            $index_data= $arrayKey[$i];
            if($i == 0)
            {
                $infonotatindakan= $arrdisposisi[$index_data]["ISI"];
            }
            $arrdata["DISPOSISI_ID"]= $arrdisposisi[$index_data]["DISPOSISI_ID"];
            $arrdata["DISPOSISI_PARENT_ID"]= $arrdisposisi[$index_data]["DISPOSISI_PARENT_ID"];
            $arrdata["INFO_STATUS_DISPOSISI"]= $arrdisposisi[$index_data]["INFO_STATUS_DISPOSISI"];
            $arrdata["DARI"]= $arrdisposisi[$index_data]["NAMA_SATKER_ASAL"];
            $arrdata["KEPADA"]= $arrdisposisi[$index_data]["NAMA_SATKER"];
            $arrdata["ISI"]= $arrdisposisi[$index_data]["ISI"];
            $arrdata["KETERANGAN"]= $arrdisposisi[$index_data]["KETERANGAN"];
            $arrdata["INFO_TANGGAL_DISPOSISI"]= $arrdisposisi[$index_data]["INFO_TANGGAL_DISPOSISI"];
            array_push($arrheaderdisposisi, $arrdata);
            $jumlahheader++;
        }
    }
}
$infonotatindakan= str_replace(",", "<br>- ", $infonotatindakan);

function ambildata($arrdisposisi, &$arrdetildisposisi, $infodisposisiid)
{
    $arrayKey= in_array_column($infodisposisiid, "DISPOSISI_PARENT_ID", $arrdisposisi);
    if(!empty($arrayKey))
    {
        for($i=0; $i < count($arrayKey); $i++)
        {
            $arrdata= [];
            $index_data= $arrayKey[$i];
            $infodisposisiid= $arrdisposisi[$index_data]["DISPOSISI_ID"];
            $arrdata["DISPOSISI_ID"]= $infodisposisiid;
            $arrdata["DISPOSISI_PARENT_ID"]= $arrdisposisi[$index_data]["DISPOSISI_PARENT_ID"];
            $arrdata["INFO_STATUS_DISPOSISI"]= $arrdisposisi[$index_data]["INFO_STATUS_DISPOSISI"];
            // $arrdata["DARI"]= $arrdisposisi[$index_data]["NAMA_SATKER"];
            // $arrdata["KEPADA"]= $arrdisposisi[$index_data]["NAMA_SATKER_ASAL"];
            $arrdata["DARI"]= $arrdisposisi[$index_data]["NAMA_SATKER_ASAL"];
            $arrdata["KEPADA"]= $arrdisposisi[$index_data]["NAMA_SATKER"];
            $arrdata["ISI"]= $arrdisposisi[$index_data]["ISI"];
            $arrdata["KETERANGAN"]= $arrdisposisi[$index_data]["KETERANGAN"];
            $arrdata["INFO_TANGGAL_DISPOSISI"]= $arrdisposisi[$index_data]["INFO_TANGGAL_DISPOSISI"];
            array_push($arrdetildisposisi, $arrdata);

            ambildata($arrdisposisi, $arrdetildisposisi, $infodisposisiid);
            $jumlahheader++;
        }
    }
}

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);


?>

<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Untitled Document</title>
<style>
body, *{
	font-family: Arial;
	font-size: 13px;
}
table{
	border-collapse: collapse;
	width: 100%;
}
table td{
	vertical-align: top;
	padding: 5px 10px 5px 0px;
	*border: 1px solid black;
	*border-width: 1px 0;
	
}
.item-riwayat-disposisi{
	padding-top: 10px;
	*border-bottom: 1px solid rgba(0,0,0,0.3);
	page-break-inside: avoid;
}
/****/
p.catatan{
	border-bottom: 1px solid #EEEEEE;
	margin-bottom: 15px;
	padding-bottom: 15px;
	*border: 1px solid red !important;
}
</style>
<style type="text/css" media="print">
	@page 
	{
		size:  auto;
		margin: 0mm 10mm 0mm;
	}
</style>
</head>

<body>

<table>
	<tr>
   	  <td align="right" style="border-bottom: 1px solid black;"><img src="/images/logo.png" height="50"></td>
    </tr>
	<tr>
    	<td><strong>Disposisi</strong></td>
    </tr>
	<tr>
    	<td>
        <?=$infodisposisiuser?>
        <h3 style="margin: 0 0;"><?=$infodisposisiasal?></h3>
        <!-- 26 Okt 2020 12:19 PM -->
        <?=$disposisitanggal?>
        </td>
    </tr>
	<tr>
    	<td>
            <?
            if($kondisilihat == "1")
            {
            ?>
        	<strong>Kepada</strong>
            <ol>
            	<!-- <li>Direktur Teknik Dan Fasilitas</li>
                <li>Para Direktur</li>
                <li>Kepala Satuan Pengawasan Intern</li> -->
                <?=$infodisposisikepada?>
            </ol>
           
            
			<strong>Nota Tindakan</strong>
          	<ul>
            	<!-- <li>Untuk Menjadi Perhatian (UMP)</li>
                <li>Untuk Diketahui (UDK)</li> -->
                <?
                if(!empty($infodisposisitindakan))
                {
                    $infodisposisitindakan= explode(",", $infodisposisitindakan);
                    for($i=0; $i < count($infodisposisitindakan); $i++)
                    {
                        ?>
                        <li><?=$infodisposisitindakan[$i]?></li>
                        <?
                    }
                }
                ?>
            </ul>
            <?
            }
            ?>

            <?
            if($kondisilihat == "1")
            {
            ?>
            
            <strong>Catatan</strong>
             <?
            }
            else
            {
            ?>
             <strong>Kepada</strong>
             <p><?=$infodisposisiuserkepada."<br/>(".$infodisposisiasalkepada.")"?></p>
             <strong>Tanggapan</strong>
            <!-- <p>
            DT: Disetujui <br>
            CC: All BOD , Ka. SPI
            </p> -->
            <?
            }
            ?>
            <p><?=$infodisposisicatatan?></p>
            <?
            if($kondisilihat == "1")
            {
            ?>
           <!--  <strong>Sifat</strong>
            <div class="isi"><?=$infodisposisisifat?></div>
            <br> -->
            <?
            }
            ?>
            
            <hr>
            <table>
            <tr>
              <td colspan="3"><strong>Agenda Surat</strong></td>
            </tr>
            <tr>
              <td>Nomor Surat</td>
              <td>:</td>
              <!-- <td>0094/ND-DTF/X/ASDP-2020</td> -->
              <td><?=$infonomorsurat?></td>
            </tr>
            <tr>
              <td>Perihal</td>
              <td>:</td>
              <!-- <td>Permohonan Pelaksanaan Pemeriksaan atas Pekerjaan Perkerasan Beton Bertulang Pelabuhan Pagimana Cabang Luwuk Tahun 2018.</td> -->
              <td><?=$infoperihal?></td>
            </tr>
            <tr>
              <td>Diterima Tanggal</td>
              <td>:</td>
              <!-- <td>19 Okt 2020 19:50 PM</td> -->
              <td><?=$disposisitanggal?></td>
            </tr>
            <tr>
              <td>Pengirim</td>
              <td>:</td>
              <td><?=$infojabatan?></td>

              <!-- <td>Direktur Teknik Dan Fasilitas</td> -->
            </tr>
            <tr>
              <td>Penerima</td>
              <td>:</td>
                <td>
                    <ol class="list-unstyled" style="padding-left:1em;padding-bottom:1em;margin-top: 0%">
                <?
                $indexdata= 0;
                $no= 0;
                $arrKepada = json_decode($reqKepada);
                foreach ($arrKepada as $key => $value) {
                    $no++;
                ?>       
                    <li style="padding-left:0.5em"><?=$value->SATUAN_KERJA?></li>
                <?
                    $indexdata++;
                }
                    $arrKepadaKelompok = json_decode($reqKepadaKelompok);
                    foreach ($arrKepadaKelompok as $key => $value) {
                ?>
                     <li style="padding-left:0.5em"><?=$value->NAMA_KELOMPOK?></li>
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
                      </ol>
                </td>
              <!-- <td>Direktur Utama</td> -->
            </tr>
            </table>
            
            <hr>
            
            <p><strong>Riwayat Disposisi</strong></p>
            
            <div class="item-riwayat-disposisi">
              <?
              for($index_data=0; $index_data < $jumlahheader; $index_data++)
              {
                $infodisposisiid= $arrheaderdisposisi[$index_data]["DISPOSISI_ID"];
                // $infodisposisiid= 13;
                $arrdetildisposisi= [];
                ambildata($arrdisposisi, $arrdetildisposisi, $infodisposisiid);
                // print_r($arrdetildisposisi);exit;

                $infoactive= "";
                if($index_data == 0)
                {
                    $infoactive= "in active";
                }
                ?>
			
                <p style="font-weight: bold;"><?=$arrheaderdisposisi[$index_data]["INFO_STATUS_DISPOSISI"]?> Tanggal <?=$arrheaderdisposisi[$index_data]["INFO_TANGGAL_DISPOSISI"]?></p>
                <p><strong>Dari :</strong> <br> <?=$arrheaderdisposisi[$index_data]["DARI"]?></p>
                <p><strong>Kepada :</strong><br> <?=$arrheaderdisposisi[$index_data]["KEPADA"]?></p>
                <p><strong>Nota Tindakan :</strong><br>  - <?=$infonotatindakan?></p>
                <p class="catatan"><strong>Catatan :</strong><br>  <?=$arrheaderdisposisi[$index_data]["KETERANGAN"]?></p>
                <?
                    if(empty($arrdetildisposisi)){}
                    else
                {
                ?>
                    <?
                    for($i=0; $i < count($arrdetildisposisi); $i++)
                    {
                    ?>
                       
                    <p style="font-weight: bold;"><?=$arrdetildisposisi[$i]["INFO_STATUS_DISPOSISI"]?> Tanggal <?=$arrdetildisposisi[$i]["INFO_TANGGAL_DISPOSISI"]?></p>

                    <p><strong>Dari :</strong> <?=$arrdetildisposisi[$i]["DARI"]?></p>

                    <p><strong>Kepada :</strong> <?=$arrdetildisposisi[$i]["KEPADA"]?></p>

                    <p><strong>Nota Tindakan :</strong><br/>- <?=$infonotatindakan?><br/></p>
                    <p class="catatan"><strong>Catatan :</strong><br/><?=$arrdetildisposisi[$i]["KETERANGAN"]?><br/></p>
                        
                    <?
                    }
                    ?>
                <?
                }
                ?>
            	<!-- <p><strong>Disposisi Tanggal 20/11/2020 06:24</strong></p>
                <p>
                <strong>Dari:</strong><br>
                Manager Dukungan Layanan Korporasi 
                </p>
                
                <p>
                <strong>Kepada:</strong><br>
                Manager Tata Usaha SPI 
                </p>
                
                <p>
                <strong>Nota Tindakan:</strong><br>
                Untuk Menjadi Perhatian (UMP), Untuk Diketahui (UDK)
                </p>
                
                <p>
                <strong>Catatan:</strong><br>
                Lorem ipsum dolor sit amet consectetur adipiscing elit.
                </p> -->
			
                  <?
            }
            ?>
            </div>
            <!-- <div class="item-riwayat-disposisi">
            	<p><strong>Tanggapan Tanggal 20/11/2020 06:33</strong></p>
                <p>
                <strong>Dari:</strong><br>
                Manager Tata Usaha SPI 
                </p>
                
                <p>
                <strong>Kepada:</strong><br>
                Manager Dukungan Layanan Korporasi 
                </p>
                
                <p>
                <strong>Nota Tindakan:</strong><br>
                Agar ditindaklanjuti
                </p>
                
                <p>
                <strong>Catatan:</strong><br>
                Lorem ipsum dolor sit amet consectetur adipiscing elit.
                </p>
            </div> -->
		</td>
    </tr>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>