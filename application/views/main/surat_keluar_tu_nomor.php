
<?
if($this->USER_GROUP == "TATAUSAHA")
{}
else{
    exit;
}

include_once("functions/string.func.php");
include_once("functions/date.func.php");
$this->load->model("SuratMasuk");
$this->load->model("Disposisi");
$surat_masuk = new SuratMasuk();
$disposisi   = new Disposisi();

$reqId = $this->input->get("reqId");

$reqMode = "ubah";

$surat_masuk->selectByParamsDetil(array("A.SURAT_MASUK_ID" => $reqId, "A.STATUS_SURAT" => "TU-NOMOR", "A.CABANG_ID" => $this->CABANG_ID));
// echo $surat_masuk->query;exit;
$surat_masuk->firstRow();

$reqId                  = $surat_masuk->getField("SURAT_MASUK_ID");
$reqJenisTujuanId       = $surat_masuk->getField("JENIS_TUJUAN_ID");
$reqJenisTujuan         = $surat_masuk->getField("JENIS_TUJUAN");
$reqJenisNaskah         = $surat_masuk->getField("JENIS_NASKAH");
$reqNoAgenda            = $surat_masuk->getField("NO_AGENDA");
$reqNomor               = $surat_masuk->getField("NOMOR");
$reqTanggal             = $surat_masuk->getField("TANGGAL");
$reqPerihal             = $surat_masuk->getField("PERIHAL");
$reqKeterangan          = $surat_masuk->getField("ISI");
$reqSifatNaskah         = $surat_masuk->getField("SIFAT_NASKAH");
$reqStatusSurat         = $surat_masuk->getField("STATUS_SURAT");
$reqLokasiSurat         = $surat_masuk->getField("LOKASI_SIMPAN");
$reqAsalSuratInstansi   = $surat_masuk->getField("INSTANSI_ASAL");
$reqAsalSuratKota       = $surat_masuk->getField("KOTA_ASAL"); 
$reqAsalSuratAlamat     = $surat_masuk->getField("ALAMAT_ASAL");
$reqPenulisSurat        = $surat_masuk->getField("NAMA_USER");
$reqPenyampaianSurat    = $surat_masuk->getField("PENYAMPAIAN_SURAT");
$reqSatuanKerjaIdAsal   = $surat_masuk->getField("SATUAN_KERJA_ID_ASAL");
$reqJenisNaskahId       = $surat_masuk->getField("JENIS_NASKAH_ID");
$reqKlasifikasiKode     = $surat_masuk->getField("KLASIFIKASI_JENIS");
$reqJenisTTD            = $surat_masuk->getField("JENIS_TTD");
$reqSuratPDF            = $surat_masuk->getField("SURAT_PDF");

if($reqId == "")
{
    // redirect("app/loadUrl/app/surat_keluar_tu");
    redirect("main/index/surat_keluar_tu");
}

$reqKepada      = $disposisi->getJson(array("SURAT_MASUK_ID" => $reqId, "STATUS_DISPOSISI" => "TUJUAN"));
$reqTembusan    = $disposisi->getJson(array("SURAT_MASUK_ID" => $reqId, "STATUS_DISPOSISI" => "TEMBUSAN"));
// echo $reqKepada;exit;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
<base href="<?=base_url();?>">

<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/gaya.css">
<link rel="stylesheet" type="text/css" href="<?=base_url()?>lib/easyui/themes/default/easyui.css">
<link href="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?=base_url()?>lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript" src="<?=base_url()?>lib/easyui/globalfunction.js"></script>
<script type="text/javascript"> 
</script>

<!-- UPLOAD CORE -->
<script src="<?=base_url()?>lib/multifile-master/jquery.MultiFile.js"></script>
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

<script src="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/dist/js/eModal2.min.js"></script>
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

<body class="bg-kanan-full">
    <div id="judul-popup">Register Surat Keluar</a> &rsaquo; Kelola Surat Keluar</div>
    <div id="konten">
        <div id="popup-tabel2">

            <form id="ff" method="post" novalidate enctype="multipart/form-data">
                <table class="table">
                    <thead>
                        <tr>
                            <td>Pengirim</td>
                            <td>:</td>
                            <td>
                                <?=$reqAsalSuratInstansi?><br>
                                <?=$reqAsalSuratAlamat?>
                            </td>
                        </tr>
                        <tr>
                            <td>Penyampaian Surat</td>
                            <td>:</td>
                            <td>
                                <label class="control-label control-label-text"><?=ucwords(strtolower($reqPenyampaianSurat))?></label>
                            </td>
                        </tr>
                        <tr>
                            <td>Nomor Surat</td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqNomor" class="easyui-validatebox textbox form-control" name="reqNomor" value="<?=$reqNomor?>" style="width:100%" readonly/>
                                <br/>
                                <a href="javascript:void(0)" class="btn btn-primary" onclick="generateNomor()"><i class="fa fa-gears"></i> Generate Nomor</a>
                            </td>
                        </tr>
                        <tr>
                            <td>Jenis Naskah</td>
                            <td>:</td>
                            <td>
                                <label class="control-label control-label-text"><?=$reqJenisNaskah?></label>
                            </td>
                        </tr>
                        <tr>
                            <td>Tanggal Surat</td>
                            <td>:</td>
                            <td>
                                <label class="control-label control-label-text"><?=getFormattedDate(dateToPageCheck($reqTanggal))?></label>
                            </td>
                        </tr>
                        <tr>
                            <td>Sifat Naskah</td>
                            <td>:</td>
                            <td>
                                <label class="control-label control-label-text"><?=$reqSifatNaskah?></label>
                            </td>
                        </tr>
                        <?
                        if($reqKepada == "" || $reqKepada == "[]")
                        {}
                        else
                        {
                        ?>
                        <tr>
                            <td>Kepada</td>
                            <td>:</td>
                            <td>
                                <label class="control-label control-label-text">
                                <?
                                error_reporting(1);
                                $arrKepada = json_decode($reqKepada);
                                $i = 0;
                                foreach ($arrKepada as $key => $value) {
                                   
                                   if($i == 0){
                                      echo $value->NAMA_PEGAWAI." - ".$value->SATUAN_KERJA."<br>".$value->CABANG;
                                   }
                                   else{
                                      echo "<br>".$value->NAMA_PEGAWAI." - ".$value->SATUAN_KERJA."<br>".$value->CABANG;    
                                   }
                                    
                                   $i++;                                   
                                }

                                ?>
                                </label>
                            </td>
                        </tr>
                        <?
                        }
                        ?>
                        <?
                        if($reqTembusan == "" || $reqTembusan == "[]")
                        {}
                        else
                        {
                        ?>
                        <tr>
                            <td>Tembusan</td>
                            <td>:</td>
                            <td>
                                <label class="control-label control-label-text">
                                <?
                                error_reporting(1);
                                $arrTembusan = json_decode($reqTembusan);
                                $i = 0;
                                foreach ($arrTembusan as $key => $value) {
                                   
                                   if($i == 0){
                                      echo $value->SATUAN_KERJA."<br>".$value->CABANG;
                                   }
                                   else{
                                      echo ", ".$value->SATUAN_KERJA."<br>".$value->CABANG; 
                                   }
                                    
                                   $i++;                                   
                                }

                                ?>
                                </label>
                            </td>
                        </tr>
                        <?
                        }
                        ?>
                        <tr>
                            <td>Naskah TTD Basah</td>
                            <td>:</td>
                            <td>
                                <?
                                if($reqSuratPDF == ""){
                                }
                                else{
                                ?>
                                <img src="images/pdf.png" class="MultiFile-preview" height="20px" onClick="openAdd('<?=base_url()."uploads/".$reqId."/".$reqSuratPDF?>')"> <a onClick="openAdd('<?=base_url()."uploads/".$reqId."/".$reqSuratPDF?>')"><?=$reqSuratPDF?></a>
                                <?
                                }
                                ?>
                            </td>
                        </tr>
                        <?
                        $surat_masuk_attachment = new SuratMasuk();
                        $adaAttachment = $surat_masuk_attachment->getCountByParamsAttachment(array("A.SURAT_MASUK_ID" => (int)$reqId));
                        if($adaAttachment > 0)
                        {
                        ?>
                        <tr>
                            <td>Lampiran</td>
                            <td>:</td>
                            <td>
                                <?
                                $surat_masuk_attachment->selectByParamsAttachment(array("A.SURAT_MASUK_ID" => (int)$reqId));
                                $i = 0;
                                while($surat_masuk_attachment->nextRow())
                                {
                                    if($i == 0)
                                    {}
                                    else
                                        echo ", ";  
                                ?>
                                    <img src="images/pdf.png" class="MultiFile-preview" height="20px" onClick="openAdd('<?=base_url()."uploads/".$reqId."/".$surat_masuk_attachment->getField("ATTACHMENT")?>')"> <a onClick="openAdd('<?=base_url()."uploads/".$reqId."/".$surat_masuk_attachment->getField("ATTACHMENT")?>')"><?=$surat_masuk_attachment->getField("NAMA")?></a>
                                <?
                                    $i++;
                                }
                                if($i == 0)
                                    echo "-";
                                ?>
                            </td>
                        </tr>
                        <?
                        }
                        ?>
                        <?
                        if($reqJenisTTD == "BASAH")
                        {
                        ?>
                        <tr>
                            <td>Naskah Asli</td>
                            <td>:</td>
                            <td>
                                <input name="reqLinkFileNaskah" type="file"  accept=".pdf" value=""/>
                                <input type="hidden" name="reqLinkFileNaskahTemp" value="<?=$reqSuratPdf?>">
                                <?
                                if($reqSuratPdf == "")
                                {}
                                else
                                {
                                ?>
                                <i style="font-size:12px"><strong>dokumen naskah telah diupload.</strong></i>
                                <?
                                }
                                ?>
                            </td>
                        </tr>
                        
                        <?
                        }
                        ?>
                    </thead>
                </table>

                <input type="hidden" name="reqMediaPengiriman" value="1" />
                <input type="hidden" name="reqId" value="<?=$reqId?>" />
                <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
                <input type="hidden" name="reqJenisTTD" value="<?=$reqJenisTTD?>" />
                <input type="hidden" name="reqStatusSurat" id="reqStatusSurat" value="<?=$reqStatusSurat?>" />

                <div style="text-align:center;padding:5px">
                    <a href="javascript:void(0)" class="btn btn-primary" onclick="submitForm()"><i class="fa fa-paper-plane-o"></i> Kirim Naskah</a>
                    <!-- <a href="javascript:void(0)" class="btn btn-danger" onclick="revisi()"><i class="fa fa-undo"></i> Revisi</a> -->
                    <a href="javascript:void(0)" class="btn btn-success" onClick="parent.openAdd('app/loadUrl/report/template/?reqId=<?=$reqId?>')"><i class="fa fa-file-pdf-o"></i> Lihat Draft</a>
                </div>
            </form>

        </div>
    </div>
</body>
</html>

<script>

function generateNomor()
{
    var reqNomor = $("#reqNomor").val();

    if(reqNomor != "")
    {
        $.messager.alert('Info', "Tidak dapat mengenerate nomor", 'info');
        return;
    }

    $.get("web/permohonan_nomor_json/generate_nomor/?reqId=<?=$reqId?>&reqSatuanKerjaIdAsal=<?=$reqSatuanKerjaIdAsal?>&reqJenisNaskahId=<?=$reqJenisNaskahId?>&reqSifatNaskah=<?=$reqSifatNaskah?>&reqKlasifikasiKode=<?=$reqKlasifikasiKode?>&reqJenisSurat=EKSTERNAL", function(data){       
        
        $("#reqNomor").val(data);

    });
}

function submitForm(){
    $('#ff').form('submit',{
        url:'web/surat_masuk_json/posting_surat_nomor_tu',
        onSubmit:function(){
            return $(this).form('enableValidation').form('validate');
        },
        success:function(data){
            // console.log(data);return false;
            $.messager.alertLink('Info', data, 'info', "app/loadUrl/main/surat_keluar_tu");
            // $.messager.alertLink('Info', data, 'info', "app/loadUrl/main/approval");
        }
    });
}
function clearForm(){
    $('#ff').form('clear');
}
            
</script>