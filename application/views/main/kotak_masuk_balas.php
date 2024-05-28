<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");
$this->load->model("SuratMasuk");
$this->load->model("SatuanKerja");
$this->load->model("Disposisi");

$infoid= $reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqMode= $this->input->get("reqMode");
$reqStatusSurat= $this->input->get("reqStatusSurat");

$surat_masuk= new SuratMasuk();
$statement= " AND A.SURAT_MASUK_ID = ".$reqId." AND B.DISPOSISI_ID = ".$reqRowId;
$surat_masuk->selectByParamsDisposisi(array(), -1,-1, $this->ID, $statement);
$surat_masuk->firstRow();
// echo $surat_masuk->query;exit;
if(empty($surat_masuk->getField("DISPOSISI_ID")))
{
    $statement= " AND A.SURAT_MASUK_ID = ".$reqId." AND B.DISPOSISI_ID = ".$reqRowId;
    $surat_masuk->selectByParamsTanggapanDisposisi(array(), -1,-1, $this->ID, $statement);
    $surat_masuk->firstRow();
    // echo $surat_masuk->query;exit;
}
$infojenisnaskahid= $surat_masuk->getField("JENIS_NASKAH_ID");
$infostatus= $surat_masuk->getField("INFO_STATUS");
$infonomorsuratinfo= $surat_masuk->getField("NOMOR_SURAT_INFO");
$infonomorsurat= $surat_masuk->getField("NOMOR");
$infotanggalentri= getFormattedExtDateTimeCheck($surat_masuk->getField("TANGGAL_ENTRI"), false);
$infosifatnaskah= $surat_masuk->getField("SIFAT_NASKAH");
$infoperihal= $surat_masuk->getField("PERIHAL");
$infosatuankerjaid= $surat_masuk->getField("SATUAN_KERJA_ID_ASAL");
$infodari= $surat_masuk->getField("NAMA_USER")." (".$surat_masuk->getField("NAMA_SATKER").")";
$infokepada= $surat_masuk->getField("NAMA_USER_ASAL")." (".$surat_masuk->getField("NAMA_SATKER_ASAL").")";

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
        Tanggapan Surat
        <div class="btn-atas clearfix">
            <button class="btn btn-primary btn-sm pull-right" type="button" onClick="submitForm()"><i class="fa fa-save"></i> Kirim Balasan</button>
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
                        <th colspan="3">Tanggapan Surat</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Dari</td>
                        <td>:</td>
                        <td>
                            <strong><?=$infodari?></strong>
                        </td>
                    </tr>
                    <tr>
                        <td>Kepada</td>
                        <td>:</td>
                        <td>
                            <strong><?=$infokepada?></strong>
                            <input type="hidden" name="reqSatuanKerjaIdTujuan" value="<?=$infosatuankerjaid?>" />
                        </td>
                    </tr>
                    <tr>
                        <td>Catatan</td>
                        <td>:</td>
                        <td>
                            <input type="text" class="easyui-validatebox textbox form-control"  data-options="prompt:'Isi pesan...'"name="reqKeterangan" id="reqKeterangan" style="width:100%" required />
                        </td>
                    </tr>
                    <tr style="display:none;">
                        <td>Lampiran</td>
                        <td>:</td>
                        <td>
                            <label for="file-upload" class="custom-file-upload">
                                <i class="fa fa-cloud-upload"></i> Pilih File
                            </label>
                            <input name="reqLinkFile[]" type="file" maxlength="5" class="multi maxsize-10240" accept="xlsx|xls|doc|docx|ppt|pptx|txt|pdf|jpg|jpeg|png|gif" value="" />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <input type="hidden" name="reqId" value="<?=$reqId?>" />
        <input type="hidden" name="reqDisposisiId" value="<?=$reqRowId?>" />
        </form>

    </div>

</div>
<script type="text/javascript">
    function loadNode(cc, valinfo, infolabel)
    {
        var values= cc.combotree('getValues');
        $("#"+valinfo).val(values);
        var textdata= cc.combotree('getText');
        // console.log(textdata);
        infotextdata= textdata.split(",");
        // alert(infotextdata.length);

        infodetiltujuan= "<ul>";
        for(i=0; i < infotextdata.length; i++)
        {
            if(infotextdata[i] !== "")
            {
                infodetiltujuan+= "<li>"+infotextdata[i]+"</li>";
            }
        }
        infodetiltujuan+= "</ul>";

        $("#"+infolabel).empty();
        $("#"+infolabel).html(infodetiltujuan);
    }

    function clickNode(cc, id, valinfo, infolabel)
    {
        var infoid= [];
        infoid= String($("#"+valinfo).val()).split(",");
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
        $("#"+valinfo).val(values);
        var textdata= cc.combotree('getText');
        // console.log(textdata);
        infotextdata= textdata.split(",");

        infodetiltujuan= "<ul>";
        for(i=0; i < infotextdata.length; i++)
        {
            if(infotextdata[i] !== "")
            {
                infodetiltujuan+= "<li>"+infotextdata[i]+"</li>";
            }
        }
        infodetiltujuan+= "</ul>";

        $("#"+infolabel).empty();
        $("#"+infolabel).html(infodetiltujuan);
    }

    function submitForm()
    {
        validasi= $("#ff").form('enableValidation').form('validate');

        if(validasi)
        {
            konfirmasi= "Apakah anda yakin simpan balas?";
            $.messager.confirm('Konfirmasi',konfirmasi,function(r){
                if (r){

                    inforeload= "<?=infokembali($reqMode, $reqId, $reqRowId, $reqStatusSurat)?>";

                    $('#ff').form('submit',{
                        url:'web/inbox_json/balas',
                        onSubmit:function(){
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