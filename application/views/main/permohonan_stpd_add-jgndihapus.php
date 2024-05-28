<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model("PermohonanStpd");
$this->load->model("Kelompok");
$this->load->model("SatuanKerja");

$reqId = $this->input->get("reqId");

$set = new PermohonanStpd();

if ($reqId == "") 
{
    $reqMode = "insert";

    $statementdetil= " AND A.NIP = '".$this->ID."'";
    $setdetil= new SatuanKerja();
    $setdetil->selectByParamsFix(array(), -1,-1, $statementdetil);
    // echo $setdetil->query;exit;
    $setdetil->firstRow();
    $reqRealisasiDisiapkanOleh= $reqPengajuanDisiapkanOleh= $setdetil->getField("NAMA");

    $statementdetil= " AND A.SATUAN_KERJA_ID ~ '^[0-9\.]+$' AND A.SATUAN_KERJA_ID IN (SELECT KODE_PARENT FROM SATUAN_KERJA_FIX WHERE NIP = '".$this->ID."')";
    $setdetil= new SatuanKerja();
    $setdetil->selectByParamsFix(array(), -1,-1, $statementdetil);
    // echo $setdetil->query;exit;
    $setdetil->firstRow();
    $reqPengajuanDisetujuiOleh= $setdetil->getField("NAMA");

    if(empty($reqPengajuanDisetujuiOleh))
    {
        $statementdetil= " AND A.NIP != '".$this->ID."' AND UPPER(KELOMPOK_JABATAN) = UPPER('direksi')";
        $setdetil= new SatuanKerja();
        $setdetil->selectByParamsFix(array(), -1,-1, $statementdetil);
        // echo $setdetil->query;exit;
        $setdetil->firstRow();
        $reqPengajuanDisetujuiOleh= $setdetil->getField("NAMA");
    }

    $statementdetil= " AND A.SETTING_MENGETAHUI_ID IN (SELECT SETTING_MENGETAHUI_ID FROM setting_mengetahui WHERE STATUS IS NULL)";
    $setdetil= new PermohonanStpd();
    $setdetil->selectmengetahuisetting(array(), -1, -1, $statementdetil);
    // echo $setdetil->query;exit;
    $setdetil->firstRow();
    $reqRealisasiMengetahuiOleh= $setdetil->getField("JABATAN");
}
else
{
    $reqMode = "update";
    $statement="";
    $set->selectByParamsDraft(array("A.PERMOHONAN_STPD_ID" => $reqId), -1, -1, $statement);
    // echo $set->query;exit;
    $set->firstRow();
    $reqId = $set->getField("PERMOHONAN_STPD_ID");
    $reqNomor = $set->getField("NOMOR");
    $reqTanggal = $set->getField("TANGGAL");
    $reqDokumenAcuan = $set->getField("DOKUMEN_ACUAN");
    $reqJumlah = $set->getField("JUMLAH_PELAKSANA");
    $reqLokasiDinas = $set->getField("LOKASI_DINAS");
    $reqTanggalBerangkat = $set->getField("TANGGAL_BERANGKAT");
    $reqTanggalKembali = $set->getField("TANGGAL_KEMBALI");
    $reqTotalPeriodeHari = $set->getField("TOTAL_PERIODE_HARI");
    $reqTotalPeriodeMalam = $set->getField("TOTAL_PERIODE_MALAM");
    $reqStatusSurat = $set->getField("STATUS_SURAT");

    $reqPemimpinId = $set->getField("PEMIMPIN_ID");
    $reqPelaksanaId = $set->getField("PELAKSANA_ID");
    $reqPengajuanDisiapkanId = $set->getField("PENGAJUAN_DISIAPKAN_ID");
    $reqPengajuanDisetujuiId = $set->getField("PENGAJUAN_DISETUJUI_ID");
    $reqRealisasiDisiapkanId = $set->getField("REALISASI_DISIAPKAN_ID");
    $reqRealisasiMengetahuiId = $set->getField("REALISASI_MENGETAHUI_ID");
    $reqRealisasiDisetujuiId = $set->getField("REALISASI_DISETUJUI_ID");

    $reqTotalRealisasi = $set->getField("TOTAL_REALISASI");
    $reqSatkerAsal = $set->getField("SATUAN_KERJA_ID_ASAL");
    unset($set);

    $satuan_kerja = new SatuanKerja();
    $satuan_kerja->selectByParams(array(), -1, -1, " AND A.SATUAN_KERJA_ID='".$reqPengajuanDisiapkanId."'", " ORDER BY KODE_SO ASC ");
    // echo $satuan_kerja->query;exit;
    $satuan_kerja->firstRow();
    $reqPengajuanDisiapkanOleh=$satuan_kerja->getField("NAMA");
    unset($satuan_kerja);

    $satuan_kerja = new SatuanKerja();
    $satuan_kerja->selectByParams(array(), -1, -1," AND A.SATUAN_KERJA_ID='".$reqPengajuanDisetujuiId."'", " ORDER BY KODE_SO ASC ");
    // echo $satuan_kerja->query;exit;
    $satuan_kerja->firstRow();
    $reqPengajuanDisetujuiOleh=$satuan_kerja->getField("NAMA");
    unset($satuan_kerja);

    $satuan_kerja = new SatuanKerja();
    $satuan_kerja->selectByParams(array(), -1, -1, " AND A.SATUAN_KERJA_ID='".$reqRealisasiDisiapkanId."'", " ORDER BY KODE_SO ASC ");
    // echo $satuan_kerja->query;exit;
    $satuan_kerja->firstRow();
    $reqRealisasiDisiapkanOleh=$satuan_kerja->getField("NAMA");
    unset($satuan_kerja);

    // $satuan_kerja = new SatuanKerja();
    // $satuan_kerja->selectByParams(array(), -1, -1, " AND A.SATUAN_KERJA_ID='".$reqRealisasiMengetahuiId."'", " ORDER BY KODE_SO ASC ");
    // // echo $satuan_kerja->query;exit;
    // $satuan_kerja->firstRow();
    // $reqRealisasiMengetahuiOleh=$satuan_kerja->getField("NAMA");
    // unset($satuan_kerja);

    $setdetil= new PermohonanStpd();
    $setdetil->selectrealisasiparaf(array("A.PERMOHONAN_STPD_ID" => $reqId), -1, -1);
    // echo $setdetil->query;exit;
    $setdetil->firstRow();
    $reqRealisasiMengetahuiOleh= $setdetil->getField("NAMA_SATKER");

    $jumlah= new PermohonanStpd();
    // $statement=" AND A.STATUS IS NULL AND A.SATUAN_KERJA_ID='".$this->SATUAN_KERJA_ID_ASAL."'";
    // $info_hak_akses_untuk_disetujui= $jumlah->getCountByParamsUntuk(array("A.PERMOHONAN_STPD_ID" => $reqId), $statement);
    $statement=" AND A.STATUS_SURAT = 'KIRIM' AND A.PENGAJUAN_DISETUJUI_ID = '".$this->SATUAN_KERJA_ID_ASAL."'";
    $info_hak_akses_untuk_disetujui= $jumlah->getCountByParams(array("A.PERMOHONAN_STPD_ID" => $reqId), $statement);
}

$arrKelompok=array();
$kelompok = new Kelompok();
$statement="   ";
if(!empty($reqId))
{
    $statementdetil= " AND B.PERMOHONAN_STPD_ID = ".$reqId;
}
else
{
    $statementdetil= " AND B.PERMOHONAN_STPD_ID IS NULL";
}
$kelompok->selectByParamsStpd(array(),-1,-1, $statement, $statementdetil);
// echo $kelompok->query;exit;
while ($kelompok->nextRow()) 
{
    $arrData=array();
    $arrData["KELOMPOK_ID"]= $kelompok->getField("KELOMPOK_ID");
    $arrData["NAMA"]= $kelompok->getField("NAMA");
    $arrData["BIAYA"]= $kelompok->getField("BIAYA");
    $arrData["ALOKASI_BIAYA"]= $kelompok->getField("ALOKASI_BIAYA");
    $arrData["PENGAJUAN_BIAYA"]= $kelompok->getField("PENGAJUAN_BIAYA");
    $arrData["KELOMPOK_ID_STPD"]= $kelompok->getField("KELOMPOK_ID_STPD");
    $arrData["KELOMPOK_ORANG"]= $kelompok->getField("KELOMPOK_ORANG");
    array_push($arrKelompok, $arrData);
}
// print_r($arrKelompok);exit;

$arrKelompokperjalanan=array();
$kelompok = new PermohonanStpd();
$statement="   ";

if(!empty($reqId))
{
    // AND A.ALOKASI_BIAYA <> 'uang_saku_kelompok' 
    $statement=" AND A.PERMOHONAN_STPD_ID = ".$reqId;

}
$kelompok->selectByParamsBiaya(array(),-1,-1,$statement);
// echo $kelompok->query;exit;
while ($kelompok->nextRow()) 
{
    $arrData=array();
    $arrData["ALOKASI_BIAYA"]= $kelompok->getField("ALOKASI_BIAYA");
    $arrData["carikunci"]= $kelompok->getField("KELOMPOK_ID");
    $arrData["PENGAJUAN_BIAYA"]= $kelompok->getField("PENGAJUAN_BIAYA");
    $arrData["BIAYA_AWAL"]= (float)$kelompok->getField("BIAYA_AWAL");
    array_push($arrKelompokperjalanan, $arrData);
}
// print_r($arrKelompokperjalanan);exit;

$arrAlokasi = infobiayadinas();
// print_r($arrAlokasi );exit;

$kondisimunculrealisasi= "";
if($reqStatusSurat == "SETUJU")
{
    $kondisimunculrealisasi= "1";
}
?>
<script src="lib/easyui2/globalfunction.js"></script>

<script src='lib/moment/moment-with-locales.js' type="text/javascript" language="javascript"></script>
<script src='lib/moment/moment-precise-range-custom.js' type="text/javascript" language="javascript"></script> 

<style type="text/css">
.column {
  float: left;
  width: 50%;
  padding: 5px;
}
</style>

<div class="col-lg-12 col-konten-full">
    <div class="judul-halaman bg-course">
        <span><img src="images/icon-course.png"></span> Permohonan STPD Add
        <div class="btn-atas clearfix">
            <?
            if(!empty($reqId)) 
            {
            ?>
            <a class="btn btn-danger btn-sm pull-right" id="buttonpdf" onClick="submitPreview()" style="cursor: pointer;"><i class="fa fa-file-pdf-o"></i> View as PDF</a>
            <?
            }
            ?>
            
            <?
            if($reqStatusSurat == "DRAFT" ||  $reqStatusSurat == "" || $reqStatusSurat=="REVISI")
            {
            ?>
                <?
                if($reqStatusSurat=="REVISI" && ($reqSatkerAsal ==  $this->SATUAN_KERJA_ID_ASAL))
                {
                ?>
                    <button class="btn btn-primary btn-sm pull-right" type="button" onClick="submitForm('KIRIM')"><i class="fa fa-paper-plane"></i> Kirim</button>
                <?       
                }
                else
                {
                ?>
                    <?
                    if($reqSatkerAsal ==  $this->SATUAN_KERJA_ID_ASAL || $reqSatkerAsal=="" )
                    {
                    ?>
                        <button class="btn btn-primary btn-sm pull-right" type="button" onClick="submitForm('KIRIM')"><i class="fa fa-paper-plane"></i> Kirim</button>
                        <button class="btn btn-default btn-sm pull-right" type="button" onClick="submitForm('DRAFT')"><i class="fa fa-file-o"></i> Draft</button>
                        <?
                        if (!empty($reqId)) 
                        {
                        ?>
                            <button class="btn btn-danger btn-sm pull-right" type="button" onClick="deleteForm()"><i class="fa fa-trash-o"></i> Hapus</button>
                        <?
                        }
                        ?>
                    <?
                    }
                    ?>
                <?
                }
                ?>
               
            <?
            }
            else
            {
            ?>
                <?
                if($reqStatusSurat != "SELESAI" && $info_hak_akses_untuk_disetujui == 1)
                {
                ?>
                    <button class="btn btn-primary btn-sm pull-right" type="button" onClick="submitForm('SETUJU')"><i class="fa fa-check-square-o"></i> Setujui</button>
                    <button class="btn btn-primary btn-sm pull-right" type="button" onClick="submitForm('REVISI')"><i class="fa fa-level-down"></i> Kembalikan</button>
                <?
                }
                ?>
            <?
            }
            ?>
        </div>
    </div>
    <div class="konten-detil">
        
        <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">
         
            <div class="tab-content">
                <table class="table" style="width: 100%">
                    <thead>
                        <tr>
                            <th colspan="3" class="padding-0">
                                <div class="judul-sub">
                                    Detail STPD
                                </div>
                            </th>
                        </tr>
                        <tr>
                            <td>Untuk</td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqPelaksanaId" class="easyui-combotree" name="reqPelaksanaId" data-options="width:'500'
                                , panelHeight:'120'
                                , valueField:'id'
                                , textField:'text'
                                , url:'web/satuan_kerja_json/combotreesatkerstpd/'
                                , prompt:'Tentukan Untuk...'," value="<?=$reqPelaksanaId?>"
                                />
                            </td>
                        </tr>
                        <tr>
                            <td>Nomor</td>
                            <td>:</td>
                            <td>
                                <span><?=$reqNomor?></span>
                            </td>
                        </tr>
                        <tr>
                            <td>Tanggal</td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqTanggal" class="easyui-datebox textbox form-control" name="reqTanggal" value="<?= $reqTanggal ?>" style="width:110px" />
                            </td>
                        </tr>
                        <tr>
                            <th colspan="3" class="padding-0">
                                <div class="judul-sub">
                                    DOKUMEN ACUAN
                                </div>
                            </th>
                        </tr>
                        <tr>
                            <td>Dokumen Acuan</td>
                            <td>:</td>
                            <td>
                                <textarea id="reqDokumenAcuan" name="reqDokumenAcuan"><?=$reqDokumenAcuan?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th colspan="3" class="padding-0">
                                <div class="judul-sub">
                                    PELAKSANA DINAS
                                </div>
                            </th>
                        </tr>
                        <tr>
                            <td>Jumlah </td>
                            <td>:</td>
                            <td>
                                <input type="text" name="reqJumlah" id="reqJumlah" class="easyui-validatebox" value="<?=$reqJumlah?>" style="width: 10%;"   /> &nbsp; Orang
                            </td>
                        </tr>
                        <tr>
                            <td> <h5> Data Pelaksana </h5> </td>
                        </tr>
                        <tr>
                            <td>Pemimpin</td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqPemimpinId" class="easyui-combotree" name="reqPemimpinId" data-options="width:'500'
                                , panelHeight:'120'
                                , valueField:'id'
                                , textField:'text'
                                , url:'web/satuan_kerja_json/combotreesatker/'
                                , prompt:'Tentukan Pemimpin...'," value="<?=$reqPemimpinId?>"
                                />
                            </td>
                        </tr>
                        <tr>
                            <td>Pelaksana <span class="text-danger" id="kepadaa">*</span></td>
                            <td>:</td>
                            <td>
                                <?
                                if(empty($reqReplyId))
                                {
                                ?>
                                <a onClick="top.openAdd('app/loadUrl/main/satuan_kerja_pelaksana_multi_lookup/?reqJenis=PELAKSANA&reqJenisSurat=INTERNAL&reqIdField=divTujuanSurat')">Pelaksana <i class="fa fa-users"></i></a>
                                <?
                                }
                                ?>

                                <div class="inner" id="divTujuanSurat">
                                    <div class="btn-group">
                                       <?
                                       $setinfo= new PermohonanStpd();
                                       $setinfo->selectByParamsUntuk(array(), -1, -1, " AND A.PERMOHONAN_STPD_ID = ".$reqId);
                                       while($setinfo->nextRow())
                                       {
                                            $untukid= $setinfo->getField("PERMOHONAN_STPD_UNTUK_ID");
                                            $valkepadaid= $setinfo->getField("SATUAN_KERJA_ID");
                                            $nama= $setinfo->getField("NAMA");
                                            $infogroupkelompok= $setinfo->getField("KELOMPOK_JABATAN");
                                        ?>
                                            <?
                                            if(!empty($untukid))
                                            {
                                                ?>
                                                <div class="item">PELAKSANA:<?=$nama?>
                                                <i class="fa fa-times-circle" onclick="$(this).parent().remove(); setinfovalidasi(); sethapusuangsaku('<?=$infogroupkelompok?>');"></i>
                                                <input type="hidden" name="reqUntukId[]" value="<?=$untukid?>">
                                                <input type="hidden" name="reqSatuanKerjaIdTujuan[]" value="<?=$valkepadaid?>">
                                                <input type="hidden" class="infogroupkelompok" value="<?=$infogroupkelompok?>" />
                                                </div>
                                            <?
                                            }
                                            ?>
                                        <?
                                            }
                                        ?>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th colspan="3" class="padding-0">
                                <div class="judul-sub">
                                    LOKASI DINAS
                                </div>
                            </th>
                        </tr>
                        <tr>
                            <td>lokasi Dinas</td>
                            <td>:</td>
                            <td>
                                <textarea placeholder="Isi Lokasi Dinas..." id="reqLokasiDinas" name="reqLokasiDinas"><?=$reqLokasiDinas?></textarea>
                            </td>
                        </tr>

                        <tr>
                            <th colspan="3" class="padding-0">
                                <div class="judul-sub">
                                    PERIODE DINAS
                                </div>
                            </th>
                        </tr>
                        <tr>
                            <td>Tanggal Berangkat</td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqTanggalBerangkat" class="easyui-datebox textbox form-control" name="reqTanggalBerangkat" value="<?= $reqTanggalBerangkat ?>" style="width:110px" />
                            </td>
                        </tr>
                        <tr>
                            <td>Tanggal Kembali</td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqTanggalKembali" class="easyui-datebox textbox form-control" name="reqTanggalKembali" value="<?= $reqTanggalKembali ?>" style="width:110px" />
                            </td>
                        </tr>
                        <div class="column">
                            <table class="table">
                               <tr>
                                    <td>Total Periode Dinas</td>
                                    <td>:</td>
                                    <td style="width: 10%" >
                                        <input type="text" id="reqTotalPeriodeHari" class="easyui-validatebox form-control" name="reqTotalPeriodeHari" value="<?= $reqTotalPeriodeHari ?>" style="width:110px"  /> Hari 
                                    </td>
                                    <td   ><input type="text" id="reqTotalPeriodeMalam" class="easyui-validatebox form-control" name="reqTotalPeriodeMalam" value="<?= $reqTotalPeriodeMalam ?>" style="width:110px"  /> Malam
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </thead>
                </table>
                <table>
                    <tr>
                        <th colspan="3" class="padding-0">
                            <div class="judul-sub">
                                ESTIMASI BIAYA DINAS
                            </div>
                        </th>
                    </tr>
                </table>
                <?
                if($reqStatusSurat=="DRAFT" || $reqStatusSurat=="REVISI" || $reqStatusSurat=="")
                {
                ?>
                <?
                }
                ?>
                <br>
                <table style="width: 100%"  >

                </table>
                <table id="tbbiaya" style="width: 100%">
                    <tr>
                        <td style="width: 20%" >Alokasi Biaya</td>
                        <td style="width: 20%"></td>
                        <td style="width: 15%" >Pengajuan Biaya</td>
                        <td style="width: 15%" class="realisasi" >Realisasi</td>
                    </tr>
                   <tr>
                    <?
                    foreach ($arrAlokasi as $key => $value) 
                    {
                        $infoalokasinama= $value["nama"];
                        $infocarikey= $infoalokasinama;
                        $arrcheckgetinfoalokasi= [];
                        $arrcheckgetinfoalokasi= in_array_column($infocarikey, "ALOKASI_BIAYA", $arrKelompokperjalanan);
                        
                        $vbiaya= 0;
                        if(!empty($arrcheckgetinfoalokasi) && !empty($reqId))
                        {
                            $indexcheckgetinfoalokasi= $arrcheckgetinfoalokasi[0];
                            $vbiaya= $arrKelompokperjalanan[$indexcheckgetinfoalokasi]["PENGAJUAN_BIAYA"];
                            $vbiaya= number_format($vbiaya,0,',','.');
                        }
                    ?>
                        <td>
                            <label><?=$infoalokasinama?></label>
                            <input class='easyui-validatebox textbox form-control' type='hidden' name='reqAlokasi[]' value='<?=$infoalokasinama?>' />
                        </td>
                        <td>
                             <input type='hidden' name="reqOrang[]" />
                        </td>
                        <td>
                            <input style="text-align: right;" class='uangclass easyui-validatebox textbox form-control' type='text' name='reqPengajuan[]' value='<?=$vbiaya?>' />
                        </td>
                        <td class="realisasi" style="width: 25%" >
                            <input class='uangclass easyui-validatebox textbox form-control txtCal' type='text' name='reqRealisasi[]' value='<?=$realisasi?>' />
                            <input type='hidden' name="reqKelompokId[]" value="" />
                            <input type='hidden' name="reqKelompokSimpan[]" value="" />
                            <input type='hidden' name="reqKelompokIdStpd[]" value="" />
                        </td>
                    </tr>
                    <?
                    }
                    ?>
                    <tr>
                        <td>
                            <label>Uang Saku</label>
                        </td>
                        <td>
                            <table style="width: 100%">
                                <?
                                foreach ($arrKelompok as $key => $value) 
                                {
                                    $vkelompokid= $value["KELOMPOK_ID"];
                                    $vkelompokorang= $value["KELOMPOK_ORANG"];
                                    $vkelompokidstpd= $value["KELOMPOK_ID_STPD"];
                                ?>
                                <tr class="trkelompokuangsaku<?=$vkelompokid?>">
                                    <td>
                                        <label for="orang"><?=$value["NAMA"]?></label>
                                    </td>
                                    <td>
                                        <input class='easyui-validatebox textbox form-control' type='hidden' name='reqAlokasi[]' value='uang_saku_kelompok' />
                                        <input class="easyui-validatebox textbox form-control" type='text' style="width: 50px" name="reqOrang[]" id="reqGroupOrang<?=$vkelompokid?>" value="<?=$vkelompokorang?>" readonly />
                                        <input type='hidden' id="vGroupOrang<?=$vkelompokid?>"  />
                                        <input type='hidden' name="reqKelompokId[]" value="<?=$vkelompokid?>" />
                                        <input type='hidden' name="reqKelompokSimpan[]" id="reqKelompokSimpan<?=$vkelompokid?>" />
                                        <input type='hidden' name="reqKelompokIdStpd[]" value="<?=$vkelompokidstpd?>" />
                                    </td>
                                    <td>
                                        <label>Orang</label>
                                    </td>
                                </tr>
                                <?   
                                }
                                ?>
                            </table>
                        </td>
                        <td style="width: 25%">
                            <table style="width: 100%">
                            <?
                            foreach ($arrKelompok as $key => $value) 
                            {
                                $vkelompokid= $value["KELOMPOK_ID"];
                                $vkelompokidstpd= $value["KELOMPOK_ID_STPD"];

                                $reqBiaya="0";
                                if(!empty($value["BIAYA"]))
                                {
                                    $reqBiaya= number_format($value["BIAYA"],0,',','.');
                                }

                                $reqPengajuan= "0";
                                $infoalokasinama= $vkelompokid;
                                $infocarikey= $infoalokasinama;
                                $arrcheckgetinfoalokasi= [];
                                $arrcheckgetinfoalokasi= in_array_column($infocarikey, "carikunci", $arrKelompokperjalanan);
                                if(!empty($arrcheckgetinfoalokasi))
                                {
                                    $indexcheckgetinfoalokasi= $arrcheckgetinfoalokasi[0];
                                    $vbiaya= $arrKelompokperjalanan[$indexcheckgetinfoalokasi]["PENGAJUAN_BIAYA"];
                                    $reqPengajuan= number_format($vbiaya,0,',','.');
                                }
                            ?>
                            <tr class="trkelompokuangsaku<?=$vkelompokid?>">
                                <td style="width: 30%; text-align: right;">
                                    <label id='reqSettingPengajuan<?=$vkelompokid?>'><?=$reqBiaya?></label>
                                    &nbsp;=&nbsp;
                                </td>
                                <td>
                                    <input style="text-align: right;" class='uangclass easyui-validatebox textbox form-control kondisiuang' type='text' name='reqPengajuan[]' id='reqPengajuan<?=$vkelompokid?>' value='<?=$reqPengajuan?>' readonly />
                                </td>
                            </tr>
                            <?   
                            }
                            ?>
                            </table>
                        </td>
                    </tr>
                </table>
                <table style="width: 100%">
                    <tr>
                        <td style="width: 70%;background-color: #dddada">
                            Total Realisasi
                        </td>
                        <td style="width: 30%">
                            IDR <span id="totalidr"><?=$reqTotalRealisasi?></span>
                        </td>
                    </tr>
                    <tr>
                        <td style="">
                            *Akomodasi disediakan berdasarkan ketentuan Perusahaan
                        </td>
                        <td style=""></td>
                    </tr>
                    <tr>
                        <td style="">
                            **Cash advance dilakukan settlement terpisah dari persetujuan ini
                        </td>
                        <td style=""></td>
                    </tr>
                </table>

                <?
                // if(!empty($reqId))
                // {
                ?>
                <table>
                    <tr>
                        <th colspan="3" class="padding-0">
                            <div class="judul-sub">
                                PENGAJUAN STPD
                            </div>
                        </th>
                    </tr>
                    <tr>
                        <td>Disiapkan oleh</td>
                        <td>:</td>
                        <td>
                            <span><?=$reqPengajuanDisiapkanOleh?></span>
                        </td>
                    </tr>
                    <tr>
                        <td>Disetujui oleh (Mgr/GM/BOD)</td>
                        <td>:</td>
                        <td>
                            <span><?=$reqPengajuanDisetujuiOleh?></span>
                        </td>
                    </tr>
                    
                </table>
                <table>
                    <tr>
                        <th colspan="3" class="padding-0">
                            <div class="judul-sub">
                                LAPORAN REALISASI STPD
                            </div>
                        </th>
                    </tr>
                    <tr>
                        <td>Disiapkan oleh</td>
                        <td>:</td>
                        <td>
                            <span><?=$reqRealisasiDisiapkanOleh?></span>
                        </td>
                    </tr>
                    <tr>
                        <td>Mengetahui SDM</td>
                        <td>:</td>
                        <td>
                            <span><?=$reqRealisasiMengetahuiOleh?></span>
                        </td>
                    </tr>
                </table>
                <?
                // }
                ?>

            </div>
            <div style="display: none;">               
                <input type="hidden" name="reqId" value="<?= $reqId ?>" />
                <input type="hidden" name="reqMode" value="<?= $reqMode ?>" />
                <input type="hidden" name="reqStatusSurat" id="reqStatusSurat" value="<?= $reqStatusSurat ?>" />
                <input type="hidden" name="reqStatusApprove" id="reqStatusApprove" value="<?=$reqStatusApprove?>" />
                <input type="hidden" name="reqTotalRealisasi" id="reqTotalRealisasi" value="<?=$reqTotalRealisasi?>" />
            </div>
        </form>
    </div>
</div>

<script src='lib/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script>
<script src='lib/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
<script src='lib/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>

<script>
    var status='<?=$reqStatusSurat?>';
    // console.log(status);
    $(".realisasi").hide();
    if(status=='SELESAI')
    {
        $(".realisasi").show();
    }

    $("#tbbiaya").on('input', '.txtCal', function () {
       var calculated_total_sum = 0;
     
       $("#tbbiaya .txtCal").each(function () {
           var get_textbox_value = $(this).val();
           var get_textbox_value = get_textbox_value.toString().replace(/\./g, '');
           // console.log(get_textbox_value);
           if ($.isNumeric(get_textbox_value)) {
              calculated_total_sum += parseFloat(get_textbox_value);
              }                  
        });
       var total= (calculated_total_sum/1000).toFixed(3);
       $("#totalidr").html(total);
       $("#reqTotalRealisasi").val(total);
    });

    var format = function(num){
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

    $('.orang').bind('keyup paste', function(){
        var numeric = $(this).val().replace(/\D/g, '');
        $(this).val(numeric);
    });

    $('.uangclass, .kondisiuang').bind('keyup paste', function(){
       var numeric = $(this).val().replace(/\D/g, '');
       $(this).val(format(numeric));
    });

    function deleteForm()
    {
        $.messager.confirm('Konfirmasi','Yakin menghapus draft ini ?',function(r){
            if (r){
                var jqxhr = $.get( 'web/permohonan_stpd_json/delete?reqId=<?=$reqId?>', function() {
                    document.location.href="main/index/permohonan_stpd";
                })
                .done(function() {
                    document.location.href="main/index/permohonan_stpd";
                })
                .fail(function() {
                    alert( "error" );
                });                             
            }
        });
    }

    function HapusBaris()
    {
        $.messager.confirm('Konfirmasi','Yakin menghapus baris ini ?',function(r){
            if (r){
                var jqxhr = $.get( 'web/permohonan_stpd_json/deletebiaya?reqId=<?=$reqId?>', function() {
                    document.location.href="main/index/permohonan_stpd_add/?reqId=<?=$reqId?>";
                })
                .done(function() {
                    document.location.href="main/index/permohonan_stpd_add/?reqId=<?=$reqId?>";
                })
                .fail(function() {
                    alert( "error" );
                });                             
            }
        });
    }

    $("#tbbiaya").on("click", ".btn-remove", function(){
        $(this).closest('tr').remove();
    });

    $(function(){
        /*$("#kelompok").hide();
        $("#kelompokbiaya").hide();
        $('#kelompok tr').hide();

        // $('#kelompokbiaya tr').hide();
        // var kelid = $("input[name='reqKelompokId[]']").map(function(){return $(this).val();}).get();

        var reqid='<?=$reqId?>';
        if(reqid)
        {
            $("#kelompokbiaya tr").hide();

            $('[name^=reqKelompokIdStpd]').each(function() {
                var value = $(this).val();
                if(value!=="")
                {
                    $("#kelompok").show();
                    $("#kelompokbiaya").show();
                    $("#"+value+", ."+value).show();
                }
                // console.log(value);
            });
        }*/

        // setinfopenandatangan();

        // one tambahan validasi
    });
  
    function submitForm(reqStatusSurat) {

        $("#reqStatusSurat").val(reqStatusSurat);

        if (reqStatusSurat == "DRAFT" )
        {
            var pesan = "Simpan surat sebagai draft?";
        }

        if (reqStatusSurat == "KIRIM")
        {
            var pesan = "Kirim Surat ?";
        }

        if (reqStatusSurat == "SETUJU")
        {
            var pesan = "Setujui Surat ?";
        }
      
        if (reqStatusSurat == "REVISI" )
        {
            infocontent= '<form action="" class="formName">' +
            '<div class="form-group">' +
            '<label>Isi komentar jika ingin mengirim dokumen ini!</label>' +
            '<input type="hidden" id="infoStatusApprove" value="" />' +
            '<input type="text" placeholder="Tuliskan komentar anda..." class="name form-control" required />' +
            '</div>' +
            '</form>';
          
            $.confirm({
                title: 'Komentar',
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
        else if (reqStatusSurat == "xKIRIM" || reqStatusSurat == "xSETUJU" || reqStatusSurat == "UBAHDATAVALIDASI")
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
            url: 'web/permohonan_stpd_json/add',
            onSubmit: function() {

                if ($(this).form('enableValidation').form('validate') == false) {
                    if ($("#button i").attr("class") == "fa fa-gears")
                    {
                        $("#button").click();
                    }

                    return false;
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

                arrData = data.split("-");

                if (arrData[0] == "0") {
                    $.messager.alert('Info', arrData[1], 'info');
                    return;
                }

                $.messager.alertLink('Info', arrData[1], 'info', "app/loadUrl/main/permohonan_stpd_add?reqMode=<?=$reqLinkMode?>&reqId=<?=$reqId?>");

            }
        });
    }

    function submitPreview() {
        parent.openAdd('app/loadUrl/r eport/template/?reqId=<?= $reqId ?>');
    }


    function clearForm() {
        $('#ff').form('clear');
    }

    function addmultisatuanKerja(JENIS, multiinfoid, multiinfonama, IDFIELD, kelompok,biaya) 
    {
        batas= multiinfoid.length;
        // console.log(batas);
        // console.log(kelompok);
        const hitung = {};
        const arrkel = kelompok;
        arrkel.forEach(function (x) { hitung[x] = (hitung[x] || 0) + 1; });
        // console.log(hitung);
        for (var key in hitung){
            $("#req"+key).val( hitung[key]);
        }

        if(batas > 0)
        {
            rekursivemultisatuanKerja(0, JENIS, multiinfoid, multiinfonama, IDFIELD, kelompok, biaya);
        }
    }

    function rekursivemultisatuanKerja(index, JENIS, multiinfoid, multiinfonama, IDFIELD, kelompok, biaya) 
    {
        urllink= "app/loadUrl/template/tujuan_surat_stpd";
        method= "POST";
        batas= multiinfoid.length;
        // console.log(kelompok);
        if(index < batas)
        {
            SATUAN_KERJA_ID= multiinfoid[index];
            NAMA= multiinfonama[index];
            kel= kelompok[index];
            kelbiaya= biaya[index];

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
                        reqNama: NAMA,
                        reqKelompokId: kel
                    },
                    // dataType: 'json',
                    success: function (response) {
                        $("#"+IDFIELD).append(response);
                        setinfovalidasi();

                        index= parseInt(index) + 1;
                        rekursivemultisatuanKerja(index, JENIS, multiinfoid, multiinfonama, IDFIELD, kelompok, biaya);
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
                rekursivemultisatuanKerja(index, JENIS, multiinfoid, multiinfonama, IDFIELD, kelompok, biaya);
            }
        }
        else
        {
            sethidekelompok("1");
        }
    }

    $(function(){
        sethidekelompok("");
    });

    function ReplaceString(oldS,newS,fullS) 
    {
        for (var i=0; i<fullS.length; i++) 
        {      
            if (fullS.substring(i,i+oldS.length) == oldS) 
            {         
                fullS = fullS.substring(0,i)+newS+fullS.substring(i+oldS.length,fullS.length)      
            }   
        }   
        return fullS
    }

    function FormatAngkaNumber(value)
    {
        var a = value;
        var nilai = ReplaceString('.','',a);
        var nilai = ReplaceString(',','.',nilai);

        // tambahan minus
        var nilai = ReplaceString('-','',nilai);
        return parseFloat(nilai);
    }

    function sethapusuangsaku(vinfoid)
    {
        vinfoval= $("#reqGroupOrang"+vinfoid).val();
        vinfoval= parseFloat(vinfoval) - 1;
        // console.log(vinfoid+"-"+vinfoval);
        $("#reqGroupOrang"+vinfoid).val(vinfoval);
        sethidekelompok("1");
    }

    function sethidekelompok(vmode)
    {
        // untuk mengisi total orang pada uang saku berdasarkan entri Pelaksana
        $(".infogroupkelompok").each(function() {
            infogroupkelompok= $(this).val();
            // console.log(infogroupkelompok);

            reqGroupOrang= $("#vGroupOrang"+infogroupkelompok).val();
            if(reqGroupOrang == "")
                reqGroupOrang= 0;
            reqGroupOrang= parseFloat(reqGroupOrang) + 1;

            $("#vGroupOrang"+infogroupkelompok+", #reqGroupOrang"+infogroupkelompok).val(reqGroupOrang);
        });
        $('[id^="vGroupOrang"]').val("");

        // untuk apabila status simpan di kondisikan tidak simpan, maka tidak perlu simpan
        $('[id^="reqGroupOrang"]').each(function(){
            vinfoid= $(this).attr('id');
            vinfoval= $(this).val();
            vinfoid= vinfoid.replace("reqGroupOrang", "");

            tidaksimpan= "tidaksimpan";
            $(".trkelompokuangsaku"+vinfoid).hide();
            if(vinfoval == "" || vinfoval == "0"){}
            else
            {
                $(".trkelompokuangsaku"+vinfoid).show();
                tidaksimpan= "";

                if(vmode == "1")
                {
                    reqSettingPengajuan= $("#reqSettingPengajuan"+vinfoid).text();
                    reqPengajuan= parseFloat(vinfoval) * FormatAngkaNumber(reqSettingPengajuan);
                    $("#reqPengajuan"+vinfoid).val(format(reqPengajuan));
                }
            }
            $("#reqKelompokSimpan"+vinfoid).val(tidaksimpan);
        });
    }

    function setinfovalidasi()
    {
        reqIdd= '<?=$reqId?>';
        // tambahan khusus
        reqPerihal= reqSatuanKerjaIdTujuan= reqSatuanKerjaIdTujuan= reqSatuanKerjaIdParaf=
        reqKeterangan= 
        reqButuhAksiId= reqSifatNaskah= "";

        reqSatuanKerjaIdTujuan= setundefined($('[name^=reqSatuanKerjaIdTujuan]').val());

        $("#tab-informasi-danger").hide();
        $("#tab-informasi-success").show();
        if(reqSatuanKerjaIdTujuan=="")
        {
            $("#tab-informasi-danger").show();
            $("#tab-informasi-success").hide();
        }

        $("#tab-isi-danger").hide();
        $("#tab-isi-success").show();
    }

    function setundefined(val)
    {
        if(typeof val == "undefined")
            val= "";
        return val;
    }

</script>

<!-- jQUERY CONFIRM MASTER -->
<link rel="stylesheet" type="text/css" href="lib/jquery-confirm-master/css/jquery-confirm.css"/>
<script type="text/javascript" src="lib/jquery-confirm-master/js/jquery-confirm.js"></script>

<link rel="stylesheet" href="css/gaya-surat.css" type="text/css">