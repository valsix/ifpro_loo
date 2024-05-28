<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");    
?>
<?php /*?><!DOCTYPE html>
<html ng-app="app">
  <head>
    <base href="<?=base_url()?>">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title><?php */ ?>

    <script src="lib/easyui2/globalfunction.js"></script>

    <script src='lib/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script>
    <script src='lib/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
    <script src='lib/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>

    <link rel="stylesheet" type="text/css" href="lib/jquery-confirm-master/css/jquery-confirm.css"/>
    <script type="text/javascript" src="lib/jquery-confirm-master/js/jquery-confirm.js"></script>

    <link rel="stylesheet" href="css/gaya-surat.css" type="text/css">

    <div class="col-lg-12 col-konten-full">
        <div class="judul-halaman bg-course">
            <span><img src="images/icon-course.png"></span> Ubah Password
            <div class="btn-atas clearfix">
                <button class="btn btn-primary btn-sm pull-right" type="button" onClick="setsimpan()"><i class="fa fa-save"></i> Simpan</button>        
            </div>
        </div>
        <div class="konten-detil">
            <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">              
                <div class="tab-content">
                    <div id="tab-informasi" class="tab-pane fade in active">
                        <table class="table">
                            <thead>
                                <tr>
                                    <td>Username <span class="text-danger"></td>
                                    <td>:</td>
                                    <td><?=$this->ID?></td>
                                </tr>
                                <tr>
                                    <td>Password Baru <span class="text-danger">*</td>
                                    <td>:</td>
                                    <td>
                                        <input type="text" name="reqNewPass" id="reqNewPass" class="easyui-validatebox" style="width: 900px;" placeholder="Silakan tulis password baru" required />
                                    </td>
                                </tr>
                                <tr>
                                    <td>Password Lama <span class="text-danger">*</td>
                                    <td>:</td>
                                    <td>
                                        <input type="text" name="reqPass" id="reqPass" class="easyui-validatebox" style="width: 900px;" placeholder="Silakan tulis pasword lama" required />
                                    </td>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </form>
        </div>

    </div>

<!--// plugin-specific resources //-->


<script>
    function setsimpan()
    {
        $('#ff').form('submit', {
            url: 'web/user_login_json/ganti',
            onSubmit: function() {
                if($(this).form('enableValidation').form('validate'))
                {
                    var win = $.messager.progress({title:'Proses simpan data', msg:'Proses simpan data...'});
                }

                return $(this).form('enableValidation').form('validate');
            },
            success: function(data) {
                $.messager.progress('close');
                // console.log(data); return false;

                arrData = data.split("-");

                if (arrData[0] == "0") {
                    $.messager.alert('Info', arrData[1], 'info');
                    return;
                }

                $.messager.alertLink('Info', arrData[1], 'info', "app/loadUrl/main/ubah_password?reqMode=<?=$reqLinkMode?>&reqId=<?=$reqId?>");
            }
        });
    }

</script>
