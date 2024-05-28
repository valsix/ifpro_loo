<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");
$this->load->model("SuratMasuk");
$this->load->model("SatuanKerja");
$this->load->model("Disposisi");
$this->load->model("BalasDisposisi");

$infoid= $reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqMode= $this->input->get("reqMode");
$reqStatusSurat= $this->input->get("reqStatusSurat");

if($reqUnitKerjaId == "")
{
    $reqUnitKerjaId = $this->CABANG_ID; 
}

if(!empty($reqRowId))
{
    $surat_masuk= new SuratMasuk();
    $statement= " AND A.SURAT_MASUK_ID = ".$reqId." AND B.DISPOSISI_ID = ".$reqRowId;
    $surat_masuk->selectByParamsSuratMasuk(array(), -1,-1, $statement);
    $surat_masuk->firstRow();
    // echo $surat_masuk->query;exit;
    $infojenisnaskahid= $surat_masuk->getField("JENIS_NASKAH_ID");
    $infonomorsuratinfo= $surat_masuk->getField("NOMOR_SURAT_INFO");
    $infostatus= $surat_masuk->getField("INFO_STATUS");
    $infonomorsurat= $surat_masuk->getField("NOMOR");
    $infotanggalentri= getFormattedExtDateTimeCheck($surat_masuk->getField("TANGGAL_ENTRI"), false);
    $infosifatnaskah= $surat_masuk->getField("SIFAT_NASKAH");
    $infoperihal= $surat_masuk->getField("PERIHAL");
    $infosatuankerjaid= $surat_masuk->getField("SATUAN_KERJA_ID_ASAL");
    $infosatuankerjatujuanid= $surat_masuk->getField("SATUAN_KERJA_ID_TUJUAN");
}
else
{
    $surat_masuk= new SuratMasuk();
    $statement= " AND A.SURAT_MASUK_ID = ".$reqId;
    $surat_masuk->selectByParamsStatus(array(), -1,-1, $this->ID, $statement);
    $surat_masuk->firstRow();
    // echo $surat_masuk->query;exit;
    $infojenisnaskahid= $surat_masuk->getField("JENIS_NASKAH_ID");
    $infonomorsuratinfo= $surat_masuk->getField("NOMOR_SURAT_INFO");
    $infostatus= $surat_masuk->getField("INFO_STATUS");
    $infonomorsurat= $surat_masuk->getField("INFO_NOMOR_SURAT");
    $infotanggalentri= getFormattedExtDateTimeCheck($surat_masuk->getField("TANGGAL_ENTRI"), false);
    $infosifatnaskah= $surat_masuk->getField("SIFAT_NASKAH");
    $infoperihal= $surat_masuk->getField("PERIHAL");
    $infosatuankerjaid= $surat_masuk->getField("SATUAN_KERJA_ID_ASAL");
    $infosatuankerjatujuanid= $surat_masuk->getField("SATUAN_KERJA_ID_TUJUAN");

    if(empty($infojenisnaskahid))
    {
        $statement= " AND A.SURAT_MASUK_ID = ".$reqId;
        $surat_masuk->selectByParamsSuratKeluar(array(), -1,-1, $this->ID, $statement);
        $surat_masuk->firstRow();
        // echo $surat_masuk->query;exit;
        $infojenisnaskahid= $surat_masuk->getField("JENIS_NASKAH_ID");
        $infonomorsuratinfo= $surat_masuk->getField("INFO_NOMOR_SURAT");
        $infonomorsurat= $surat_masuk->getField("INFO_NOMOR_SURAT");
        $infotanggalentri= getFormattedExtDateTimeCheck($surat_masuk->getField("TANGGAL_ENTRI"), false);
        $infosifatnaskah= $surat_masuk->getField("SIFAT_NASKAH");
        $infoperihal= $surat_masuk->getField("PERIHAL");
        $infosatuankerjatujuanid= $this->SATUAN_KERJA_ID_ASAL;
        $reqRowId= 0;
    }
}

// $reqSifatNaskah= $reqKeterangan= "Biasa";

if($infojenisnaskahid == "1")
    $infonomorsurat= $infonomorsuratinfo;
?>
<script type="text/javascript">
    function setkembali()
    {
        inforeload= "<?=infokembali($reqMode, $reqId, $reqRowId, $reqStatusSurat)?>";
        document.location.href= inforeload;
    }

    function setlihatdokumen()
    {
        document.location.href = 'main/index/status_detil?reqMode=<?=$reqMode?>&reqId=<?=$reqId?>';
    }
</script>
<div class="col-lg-12 col-konten-full">
    <div class="judul-halaman bg-course">
        <?
        if(!empty($reqMode))
        {
        ?>
        <a href="javascript:void(0)" onclick="setkembali();"><i class="fa fa-chevron-left"></i></a> 
        <?
        }
        ?>
        Disposisi Surat
        <div class="btn-atas clearfix">
            <button class="btn btn-primary btn-sm pull-right" type="button" onClick="submitForm()"><i class="fa fa-save"></i> Disposisi</button>
        </div>
    </div>
    <div class="konten-detil">
        <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">
        <div class="table-responsive area-agenda-surat">
            <table class="table">
                <thead class="thead-light">
                    <tr class="active">
                        <th colspan="3">Informasi Surat</th>
                    </tr>
                </thead>
                <tbody>

                    <tr>
                        <td>Nomor Surat</td>
                        <td>:</td>
                        <td>
                            <ol class="list-unstyled">
                                <li><?=$infonomorsurat?></li>
                            </ol>
                        </td>
                    </tr>
                    <tr>
                        <td>Tanggal Surat</td>
                        <td>:</td>
                        <td><?=$infotanggalentri?></td>
                    </tr>
                    <tr>
                        <td>Sifat Surat</td>
                        <td>:</td>
                        <td><?=$infosifatnaskah?></td>
                    </tr>
                    <tr>
                        <td>Perihal</td>
                        <td>:</td>
                        <td><?=$infoperihal?></td>
                    </tr>
                </tbody>
            </table>
            <table class="table">
                <thead class="thead-light">
                    <tr class="active">
                        <th colspan="3">Disposisi Surat</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Diteruskan kepada <span class="text-danger">*</span></td>
                        <td>:</td>
                        <td>
                            <a onClick="top.openAdd('app/loadUrl/main/satuan_kerja_tujuan_multi_lookup/?reqJenis=DISPOSISI&reqJenisSurat=INTERNAL&reqIdField=divTujuanSurat')">Tujuan <i class="fa fa-users"></i></a>

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
                                                <i class="fa fa-times-circle" onclick="$(this).parent().remove();"></i>
                                            <?
                                                }
                                            ?>
                                                <input type="hidden" name="reqTujuanSuratValidasi" value="<?=$valkepadatujuan?>">
                                                <input type="hidden" name="reqSatuanKerjaIdTujuan[]" value="<?=$valkepadaid?>">
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
                        <td>Nota Tindakan <span class="text-danger">*</span></td>
                        <td>:</td>
                        <td>
                        
                            <div class="form-group">
                                <ul class="list-unstyled">
                                    <?
                                    $balas_disposisi= new BalasDisposisi();
                                    $balas_disposisi->selectByParams(array(), -1, -1);
                                    $i = 0;
                                    while($balas_disposisi->nextRow())
                                    {
                                        $infobalasid= $balas_disposisi->getField("BALAS_DISPOSISI_ID");
                                        $infobalasnama= $balas_disposisi->getField("NAMA");
                                    ?>
                                        <li class="custom-control custom-checkbox">
                                            <input class="custom-control-input ng-untouched ng-pristine ng-valid" type="checkbox" id="reqRadio<?=$infobalasid?>">
                                            <label class="custom-control-label" id="reqRadioNama<?=$infobalasid?>" for="reqRadio<?=$infobalasid?>"><?=$infobalasnama?></label>
                                        </li>
                                    <?
                                    }
                                    ?>
								</ul>
							</div>

                            <input class="easyui-validatebox" required type="hidden" id="reqBalasCepat" name="reqBalasCepat" />
                            <div id="infodetilbalas"></div>
                        </td>
                    </tr>
                    <tr>
                        <td>Catatan</td>
                        <td>:</td>
                        <td>
                        	<textarea class="easyui-validatebox textbox form-control"  data-options="prompt:'Isi pesan...'"name="reqKeterangan" id="reqKeterangan" style="width:100%; padding: 5px;" value="<?=$reqKeterangan?>"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>Sifat <span class="text-danger">*</span></td>
                        <td>:</td>
                        <td>
                            <input type="text" name="reqSifatNaskah" class="easyui-combobox" id="reqSifatNaskah" 
                            data-options="
                            onChange: function(node){
                                $('#reqSifatNaskahNama').val(node);
                            }
                            , width:'300', panelHeight:'100',editable:false, valueField:'id',textField:'text',url:'web/disposisi_sifat_surat_json/combo',prompt:'Tentukan sifat...'" value="<?=$reqSifatNaskah?>" required />
                            <input type="hidden" id="reqSifatNaskahNama" name="reqSifatNaskahNama" />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <input type="hidden" name="reqInfoSatuankerjaTujuanId" value="<?=$infosatuankerjatujuanid?>" />
        <input type="hidden" name="reqId" value="<?=$reqId?>" />
        <input type="hidden" name="reqDisposisiId" value="<?=$reqRowId?>" />
        </form>

    </div>

</div>
<script type="text/javascript">
    $(function(){
        $('input[id^="reqRadio"]').change(function(e) {
            infoid= $(this).attr('id');
            infoid= infoid.split('reqRadio');
            infoid= infoid[1];
            infonama= $("#reqRadioNama"+infoid).text();

            reqBalasCepat= $("#reqBalasCepat").val();
            if($(this).prop('checked')) {
                if(reqBalasCepat == "")
                    reqBalasCepat= infonama;
                else
                    reqBalasCepat= reqBalasCepat+","+infonama;
            }
            else
            {
                reqBalasCepat= reqBalasCepat.replace(","+infonama, "");
                reqBalasCepat= reqBalasCepat.replace(infonama+",", "");
                reqBalasCepat= reqBalasCepat.replace(infonama, "");
            }
            $("#reqBalasCepat").val(reqBalasCepat);

            infotextdata= reqBalasCepat.split(",");
            infolabel= "infodetilbalas";

            infodetiltujuan= "<ol>";
            for(i=0; i < infotextdata.length; i++)
            {
                if(infotextdata[i] !== "")
                {
                    infodetiltujuan+= "<li>"+infotextdata[i]+"</li>";
                }
            }
            infodetiltujuan+= "</ol>";

            $("#"+infolabel).empty();
            $("#"+infolabel).html(infodetiltujuan);
        });
    });

    function setinfovalidasi(){}

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
            $('[name^=reqTujuanSuratValidasi]').each(function() {

                if ($(this).val() == SATUAN_KERJA_ID) {
                    rv = false;
                    return false;
                }

            });

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
    
    function submitForm()
    {
        validasi= $("#ff").form('enableValidation').form('validate');

        if(validasi)
        {
            validasi= false;
            $('[name^=reqTujuanSuratValidasi]').each(function() {
                validasi= true;
                return false;
            });
        }

        if(validasi)
        {
            konfirmasi= "Apakah anda yakin melakukan disposisi?";
            $.messager.confirm('Konfirmasi',konfirmasi,function(r){
                if (r){

                    inforeload= "<?=infokembali($reqMode, $reqId, $reqRowId, $reqStatusSurat)?>";

                    $('#ff').form('submit',{
                        url:'web/inbox_json/add',
                        onSubmit:function(param){
                            return $(this).form('enableValidation').form('validate');
                        },
                        success:function(data){
                            // console.log(data);return false;
                            var arrData = data.split("-");
                            if(arrData[0] == 'X')
                                $.messager.alert('Info', arrData[1], 'info'); 
                            else
                                $.messager.alertTopLink('Info', arrData[1], 'info', inforeload);
                        }
                    });
                }
            });
        }
        else
        {
            $.messager.alert('Info', "Isikan data terlebih dahulu", 'info');
        }
    }
</script>
<style type="text/css">
    .tree-checkbox{
        background: none;
    }
</style>