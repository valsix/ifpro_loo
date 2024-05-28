<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model("PermohonanStpd");

$reqId = $this->input->get("reqId");


$set = new PermohonanStpd();

if ($reqId == "") 
{
    $reqMode = "insert";
}
else
{
    $reqMode = "update";
    $statement="";
    $set->selectByParamsDraft(array("A.PERMOHONAN_STPD_ID" => $reqId), -1, -1, $statement);
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
    $reqRealisasiDisetujuiId = $set->getField("REALISASI_DISIAPKAN_ID");
    $reqRealisasiMengetahuiId = $set->getField("REALISASI_MENGETAHUI_ID");
    $reqRealisasiDisetujuiId = $set->getField("REALISASI_DISETUJUI_ID");

    $reqTotalRealisasi = $set->getField("TOTAL_REALISASI");
    $reqSatkerAsal = $set->getField("SATUAN_KERJA_ID_ASAL");

    unset($set);


}

$jumlah= new PermohonanStpd();
$statement=" AND A.STATUS IS NULL AND A.SATUAN_KERJA_ID='".$this->SATUAN_KERJA_ID_ASAL."'";
$hitung=$jumlah->getCountByParamsUntuk(array("A.PERMOHONAN_STPD_ID" => $reqId), $statement);





// print_r($hitung);exit;


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

<!--<div class="container-fluid" style="background-color:#fff">-->
<div class="col-lg-12 col-konten-full">
    <!--<div class="judul-halaman-tulis">Surat Internal</div>-->
    <div class="judul-halaman bg-course">
        <span><img src="images/icon-course.png"></span> Permohonan STPD Add
        <div class="btn-atas clearfix">
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
                if($reqStatusSurat != "SELESAI" && $hitung==1)
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
                                <td>Untuk <span class="text-danger" id="kepadaa">*</span></td>
                                <td>:</td>
                                <td>
                                    <?
                                    if(empty($reqReplyId))
                                    {
                                    ?>
                                    <a onClick="top.openAdd('app/loadUrl/main/satuan_kerja_tujuan_multi_lookup/?reqJenis=TUJUAN&reqJenisSurat=INTERNAL&reqIdField=divTujuanSurat')">Untuk <i class="fa fa-users"></i></a>
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
                                            ?>
                                                <?
                                                if(!empty($untukid))
                                                {
                                                    ?>
                                                    <div class="item">TUJUAN:<?=$nama?>
                                                    <i class="fa fa-times-circle" onclick="$(this).parent().remove(); setinfovalidasi();"></i>
                                                    <input type="hidden" name="reqUntukId[]" value="<?=$untukid?>">
                                                    <input type="hidden" name="reqSatuanKerjaIdTujuan[]" value="<?=$valkepadaid?>">
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
                                <td>Nomor <span class="text-danger">*</td>
                                <td>:</td>
                                <td>
                                    <input type="text" name="reqNomor" id="reqNomor" class="easyui-validatebox" value="<?=$reqNomor?>" style="width: 900px;"  required />
                                </td>
                            </tr>
                            <tr>
                                <td>Tanggal</td>
                                <td>:</td>
                                <td><input type="text" id="reqTanggal" class="easyui-datebox textbox form-control" name="reqTanggal" value="<?= $reqTanggal ?>" style="width:110px" /></td>
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
                                    <input type="text" name="reqJumlah" id="reqJumlah" class="easyui-validatebox" value="<?=$reqJumlah?>" style="width: 100px;"   /> &nbsp; Orang
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
                                <td>Pelaksana</td>
                                <td>:</td>
                                <td>
                                    <input type="text" id="reqPelaksanaId" class="easyui-combotree" name="reqPelaksanaId" data-options="width:'500'
                                    , panelHeight:'120'
                                    , valueField:'id'
                                    , textField:'text'
                                    , url:'web/satuan_kerja_json/combotreesatker/'
                                    , prompt:'Tentukan Pelaksana...'," value="<?=$reqPelaksanaId?>"
                                    />
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
                                <td><input type="text" id="reqTanggalBerangkat" class="easyui-datebox textbox form-control" name="reqTanggalBerangkat" value="<?= $reqTanggalBerangkat ?>" style="width:110px" /></td>
                            </tr>
                            <tr>
                                <td>Tanggal Kembali</td>
                                <td>:</td>
                                <td><input type="text" id="reqTanggalKembali" class="easyui-datebox textbox form-control" name="reqTanggalKembali" value="<?= $reqTanggalKembali ?>" style="width:110px" /></td>
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
                    <div style="margin-top: 10px"> <a href="javascript:void(0)" class="btn btn-primary btn-sm" onclick="AddBaris('')">Tambah</a></div>
                    <?
                    }
                    ?>
                    <br>
                    <table style="width: 100%"  >
                        <tr>
                            <td >Alokasi Biaya</td>
                            <td style="width: 25%" >Pengajuan Biaya</td>
                            <td style="width: 25%"  >Realisasi</td>
                            <td style="width: 5%"  >Aksi</td>
                        </tr>
                    </table>
                    <table id="tbbiaya" style="width: 100%">
                       <?
                        $setinfo= new PermohonanStpd();
                        $setinfo->selectByParamsBiaya(array(), -1, -1, " AND A.PERMOHONAN_STPD_ID = ".$reqId);
                        while($setinfo->nextRow())
                        {
                            $alokasi= $setinfo->getField("ALOKASI_BIAYA");
                            $pengajuan= $setinfo->getField("PENGAJUAN_BIAYA");
                            $realisasi= $setinfo->getField("REALISASI");
                        ?>
                            <tr>
                                <td><input class='easyui-validatebox textbox form-control' type='text' name='reqAlokasi[]' id='reqAlokasi' value='<?=$alokasi?>'  style=''></td>
                                <td style="width: 25%" ><input class='easyui-validatebox textbox form-control' type='text' name='reqPengajuan[]' id='reqPengajuan' value='<?=$pengajuan?>'  ></td>
                                <td style="width: 25%" ><input class='easyui-validatebox textbox form-control txtCal' type='text' name='reqRealisasi[]' id='reqRealisasi' value='<?=$realisasi?>'  ></td>
                                <td style="width: 5%">
                                      <?
                                      if($reqStatusSurat=="DRAFT" || $reqStatusSurat=="REVISI" || $reqStatusSurat=="")
                                      {
                                        ?>
                                         <span style='background-color: red; padding: 10px; border-radius: 5px;top: 50%;position: relative;'><a onclick='HapusBaris("<?=$reqId?>")' class='btn-remove'><i class='fa fa-trash fa-lg' style='color: white;' aria-hidden='true'></i></a></span>
                                        <?
                                    }
                                    ?>
                                   
                                </td>
                            </tr>
                        <?
                        }
                        ?>
                        
                    </table>
                    <table  style="width: 100%">
                        <tr>
                            <td style="width: 70%;background-color: #dddada ">Total Realisasi</td>
                            <td style="width: 30%">IDR <span id="totalidr" ><?=$reqTotalRealisasi?></span></td>
                        </tr>
                        <tr>
                            <td style="">*Akomodasi disediakan berdasarkan ketentuan Perusahaan</td>
                            <td style=""></td>
                        </tr>
                        <tr>
                            <td style="">**Cash advance dilakukan settlement terpisah dari persetujuan ini</td>
                            <td style=""></td>
                        </tr>
                    </table>
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
                                <input type="text" id="reqPengajuanDisiapkanId" class="easyui-combotree" name="reqPengajuanDisiapkanId" data-options="width:'500'
                                , panelHeight:'120'
                                , valueField:'id'
                                , textField:'text'
                                , url:'web/satuan_kerja_json/combotreesatker/'
                                , prompt:'Tentukan Pelaksana...'," value="<?=$reqPengajuanDisiapkanId?>"
                                />
                            </td>
                        </tr>
                        <tr>
                            <td>Disetujui oleh (Mgr/GM/BOD)</td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqPengajuanDisetujuiId" class="easyui-combotree" name="reqPengajuanDisetujuiId" data-options="width:'500'
                                , panelHeight:'120'
                                , valueField:'id'
                                , textField:'text'
                                , url:'web/satuan_kerja_json/combotreesatker/'
                                , prompt:'Tentukan Pelaksana...'," value="<?=$reqPengajuanDisetujuiId?>"
                                />
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
                                <input type="text" id="reqRealisasiDisetujuiId" class="easyui-combotree" name="reqRealisasiDisetujuiId" data-options="width:'500'
                                , panelHeight:'120'
                                , valueField:'id'
                                , textField:'text'
                                , url:'web/satuan_kerja_json/combotreesatker/'
                                , prompt:'Tentukan Pelaksana...'," value="<?=$reqRealisasiDisetujuiId?>"
                                />
                            </td>
                        </tr>
                        <tr>
                            <td>Mengetahui SDM</td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqRealisasiMengetahuiId" class="easyui-combotree" name="reqRealisasiMengetahuiId" data-options="width:'500'
                                , panelHeight:'120'
                                , valueField:'id'
                                , textField:'text'
                                , url:'web/satuan_kerja_json/combotreesatker/'
                                , prompt:'Tentukan Pelaksana...'," value="<?=$reqRealisasiMengetahuiId?>"
                                />
                            </td>
                        </tr>
                        <tr>
                            <td>Disetujui (BOD)</td>
                            <td>:</td>
                            <td>
                                <input type="text" id="reqRealisasiDisetujuiId" class="easyui-combotree" name="reqRealisasiDisetujuiId" data-options="width:'500'
                                , panelHeight:'120'
                                , valueField:'id'
                                , textField:'text'
                                , url:'web/satuan_kerja_json/combotreesatker/'
                                , prompt:'Tentukan Pelaksana...'," value="<?=$reqRealisasiDisetujuiId?>"
                                />
                            </td>
                        </tr>

                        
                    </table>

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
<!--</div>-->
<!-- /.container -->

<script src='lib/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script>
<script src='lib/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
<script src='lib/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>

<script>


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

    // $('#reqPengajuan,#reqRealisasi,#reqJumlah').bind('keyup paste', function(){
    //     this.value = this.value.replace(/[^0-9]/g, '');
    // });
    $('#reqPengajuan,#reqRealisasi,#reqJumlah').bind('keyup paste', function(){
         var numeric = $(this).val().replace(/\D/g, '');
        $(this).val(format(numeric));
    });

    function AddBaris(pengukuranid,tipeid,tabelid) {
        $.get("app/loadUrl/main/permohonan_stpd_add_biaya?reqTipePengukuranId="
            , function(data) 
        { 
            $("#tbbiaya").append(data);
        });
    }

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


  //   $('#reqTanggalBerangkat,#reqTanggalKembali').datebox({
  //       onSelect: function(date){
  //           var reqTanggalBerangkat =  $('#reqTanggalBerangkat').datebox('getValue');
  //           var reqTanggalKembali =  $('#reqTanggalKembali').datebox('getValue');
  //           var checkawal = reqTanggalBerangkat.split('-');
  //           var checkakhir = reqTanggalKembali.split('-');
  //           var checkawal = new Date(checkawal[2], checkawal[1] - 1, checkawal[0]);
  //           var checkakhir = new Date(checkakhir[2], checkakhir[1] - 1, checkakhir[0]);

  //           if(reqTanggalBerangkat !=="" && reqTanggalKembali !=="")
  //           {
  //               if(checkawal > checkakhir)
  //               {
  //                   $.messager.alert('Info', 'Rencana Eksekusi Tanggal Awal tidak boleh lebih dari Tanggal Akhir', 'warning');
  //                   reqDurasiRencana = $('#reqDurasiRencana').val("");
  //               }
  //               else
  //               {
  //                   var awal = moment(reqTanggalBerangkat,'DD-MM-YYYY');
  //                   var akhir = moment(reqTanggalKembali,'DD-MM-YYYY');
  //                   var durasi = moment.preciseDiff(awal, akhir);

  //                   if(durasi=="")
  //                   {
  //                     reqDurasiRencana =  $('#reqTotalPeriodeHari').val('0');
  //                 }
  //                 else
  //                 {
  //                     reqDurasiRencana = $('#reqTotalPeriodeHari').val(durasi);
  //                 }
  //             }

  //         }
  //     }
  // });


    $(function(){
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
        else if (reqStatusSurat == "KIRIM" || reqStatusSurat == "SETUJU" || reqStatusSurat == "UBAHDATAVALIDASI")
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
                // alert(data);return false;

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
        parent.openAdd('app/loadUrl/report/template/?reqId=<?= $reqId ?>');
    }


    function clearForm() {
        $('#ff').form('clear');
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
       

        function setundefined(val)
        {
            if(typeof val == "undefined")
                val= "";
            return val;
        }
    }

</script>
<!-- TODO: Missing CoffeeScript 2 -->
<script type="text/javascript">

</script>

<script>
    $('textarea').focus(function() {
        //$(this).closest('.area-tulis-pesan').find('#button').show("slow");
    });
</script>


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

    

    $(function(){
        $('textarea#reqKeterangan').froalaEditor({
            
            // key: "cC10A7C6B5B3C2C-8C2H2C4D4B6B2D2C4B1D1qkd1vwB-11pqD1J-7yA-16vtE-11otC-7yespzF4lb==",
            key: "MA3A1A1G2H5A3nA16B10C7C6F2D4H4I2H3C8aD-17pfgki1aC8oilfdnC-7doiucf1jB1I-8r==",
            
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
                // ,
                // 'keyup': function (keyupEvent) {
                // // Do something here.
                // // this is the editor instance.
                // console.log(keyupEvent);
                //     setinfovalidasi();
                // }
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
</script>
<link rel="stylesheet" href="css/gaya-surat.css" type="text/css">
