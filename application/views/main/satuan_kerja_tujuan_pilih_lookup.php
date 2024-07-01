<?
$reqUnitKerjaId = $this->input->get("reqUnitKerjaId");
$reqJenis = $this->input->get("reqJenis");
$reqJenisSurat = $this->input->get("reqJenisSurat");
$reqIdField = $this->input->get("reqIdField");

if($reqUnitKerjaId == "")
{
    if($reqJenisSurat == "EKSTERNAL"){
        $reqUnitKerjaId = "SBA";
    }
    else{
        $reqUnitKerjaId = $this->CABANG_ID;
    }
    
}

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<base href="<?=base_url();?>" />

<script src="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/bower_components/jquery/dist/jquery.min.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/js/bootstrap.js"></script>

<!-- Bootstrap Core CSS - Uses Bootswatch Flatly Theme: http://bootswatch.com/flatly/ -->
<link href="<?=base_url()?>lib/valsix/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="css/gaya.css" type="text/css">
<!--<link rel="stylesheet" href="css/gaya-bootstrap.css" type="text/css">-->

<!-- BOOTSTRAP -->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<!--<link href="lib/bootstrap/bootstrap.css" rel="stylesheet">-->
<link rel="stylesheet" href="lib/font-awesome/4.5.0/css/font-awesome.css">

<!--<script src="js/jquery-1.11.1.js" type="text/javascript" charset="utf-8"></script> -->

    <style>
    .col-md-12{
        padding-left:0px;
        padding-right:0px;
    }
    </style>

<!-- DRAG DROP -->
<script type="text/javascript" src="js/jquery-1.7.1.js" ></script>
<!--<link rel="stylesheet" type="text/css" href="/css/normalize.css">-->
<script type="text/javascript" src="js/jquery-ui.js"></script>

<!-- EASYUI -->
<link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript" src="lib/easyui/globalfunction.js"></script>

<!-- FONT AWESOME -->
<link rel="stylesheet" href="lib/font-awesome-4.7.0/css/font-awesome.css" type="text/css">

<style type="text/css">
    .panel.combo-p{
        width: 300px !important;
    }
</style>

</head>
<script type="text/javascript">
    <?
    $valReload = $this->input->get('reqValReload');
    if(empty($valReload)){
        $valReload =0;
    }
    ?>
    // var ii=<?=$valReload?>;
    // var url = window.location.href;
    // setTimeout(function(){
    //     //alert("reload");
    //     if(ii==0){
    //          window.location.href=url+"&reqValReload=1";
    //     }
  
    // }, 100);
</script>

<style>
.col-md-6{
    float: left;
    width: 50%;
}
</style>
<body class="body-popup">
    
    <div class="container-fluid container-treegrid">
        <div class="row row-treegrid">
            <div class="col-md-3 col-treegrid">
                <div class="konten">
                    
                    
                    <div id="infodetilparaf">
                        <label>Pejabat Terpilih: </label>
                        
                        <button class="btn btn-primary btn-sm" type="button" onClick="setsatuankerjapilih()"><i class="fa fa-user-circle"></i> Ok</button>
                        <br><span>Pilih salah satu data!</span>
                    </div>
                </div>
            </div>
            <div class="col-md-9 col-treegrid">
                <div class="area-konten-atas">
                    <div class="judul-halaman"> <?=ucwords(strtolower($reqJenis))?> Naskah</div>
                    <div class="area-menu-aksi">    
                        <!--Pencarian : <input type="text" name="reqPencarian" id="reqPencarian">-->
                        <div id="bluemenu" class="aksi-area">
                            <label class="col-md-5">
                            <span>Unit Kerja </span>
                            <input type="text" name="reqUnitKerjaId" class="easyui-combobox" id="reqUnitKerjaId" 
                            data-options="
                            width:'300'
                            , editable:false
                            , valueField:'id'
                            , textField:'text'
                            , url:'web/satuan_kerja_json/combo_cabang_alamat/?reqJenisSurat=<?=$reqJenisSurat?>'
                            " 
                            value="<?=$reqUnitKerjaId?>" required />
                            </label>
                            <label class="col-md-7 text-right">
                            <span>Pencarian </span> 
                            <input type="text" name="reqPencarian" class="easyui-validatebox textbox form-control" id="reqPencarian" style="width:300px"> 
                            </label>
                        </div>
                        
                        
                    </div>
                    
                </div>
                
                <!-- singleSelect: false, -->
                <div id="tableContainer" class="tableContainer tableContainer-treegrid">
                    <table id="treeSatker" class="easyui-treegrid" style="min-width:100px !important;height:300px"
                            data-options="
                                url: 'web/satuan_kerja_json/treetablemasternew/?reqStatus=1&reqUnitKerjaId=<?=$reqUnitKerjaId?>&reqMode=<?=$reqJenis?>',
                                pagination: true, 
                                method: 'get',
                                idField: 'id',
                                treeField: 'NAMA',
                                fitColumns: true,
                                onBeforeLoad: function(row,param){
                                    if (!row) {    // load top level rows
                                        param.id = 0;    // set id=0, indicate to load new page rows
                                    }
                                }
                            ">
                        <thead>
                            <tr>
                                <!-- <th data-options="field:'ck',checkbox:true"></th> -->
                                <th data-options="field:'NAMA',width:300" formatter="formatcheckbox">Unit Kerja</th>
                                <th data-options="field:'JABATAN',width:300">Jabatan</th>
                                <th data-options="field:'NAMA_PEGAWAI',width:200">Pejabat</th>
                                <th data-options="field:'NIP',width:100">NIP</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                
            </div>
        </div>        
    </div>
    
<script>
var infoid= [];
var infonama= [];
var tempdataintegrasi= "";
function setsatuankerjapilih()
{
    // var values= $('#treeSatker').treegrid('getCheckedNodes');
    // console.log(values);
    // tempdataintegrasi= "";
    // getselected("");
    // console.log(infoid);
    // console.log(infonama);return false;

    if(infoid.length == 0)
    {
        $.messager.alert('Info', "Pilih data terlebih dahulu.", 'info');
        return;
    }
    else
    {
        var nodes = $("#treeSatker").treegrid("find",infoid);
        // console.log(nodes);return false;

        var divIdField = "<?=$reqIdField?>";
        top.addsaksi(nodes, divIdField);
        top.closePopup();
    }
}

function show(checkid)
{
    // var s = '#check_'+checkid;
    // var nodes = $("#treeSatker").treegrid("getChildren",checkid);
    // for(i=0;i<nodes.length;i++)
    // {
    //     // console.log(nodes[i]);
    //     idselected= nodes[i].SATUAN_KERJA_ID;
    //     infochecked= $(s)[0].checked;
    //     if(checkid == idselected)
    //     {
    //         $(('#check_'+idselected))[0].checked = infochecked;
    //         // console.log(infochecked);
    //         setinfo();
    //     }
    // }

    var s = '#check_'+checkid;
    var nodes = $("#treeSatker").treegrid("find",checkid);
    // console.log(nodes);
    idselected= nodes.SATUAN_KERJA_ID;
    jabataninfo= nodes.JABATAN_INFO;
    jabatan= nodes.JABATAN;
    namainfo= nodes.NAMA;

    var tujuan= "";
    if(jabatan == "-")
    {
        tujuan= 'Kelompok '+namainfo;
    }
    else
    {
        tujuan= jabataninfo;
    }
    // console.log(tujuan);

    if(typeof tujuan==='undefined' || tujuan===null || tujuan == "") 
    {
        $.messager.alert('Info', "Pejabat belum ditentukan.", 'info');  
        return;
    }
    else
    {
        // untuk pilih satu data saja
        infoid= []; infonama= [];
        $('[id^="check_"]').attr('checked', false);
        $('[id^="check_"]').prop('checked', false);
        $('#check_'+idselected).attr('checked', true);
        $('#check_'+idselected).prop('checked', true);

        infochecked= $(s)[0].checked;
        // console.log(infochecked);
        $(('#check_'+idselected))[0].checked = infochecked;

        if(infochecked == false)
        {
            var elementRow= infoid.indexOf(checkid);
            if(parseInt(elementRow) >= 0)
            {
                infoid.splice(elementRow, 1);
                infonama.splice(elementRow, 1);
            }
        }
        else
        {
            infoid.push(String(checkid));
            infonama.push(String(tujuan));
        }

        setinfo();
    }

}

function setinfo()
{
    idata=0;
    infodetilparaf= '<label>Pejabat Terpilih:</label><button class="btn btn-primary btn-sm" type="button" onClick="setsatuankerjapilih()"><i class="fa fa-user-circle"></i> Ok</button>';
    infodetilparaf+= "<ol id='SortMe'>";
    infoid.forEach(function (item, index) {
        // console.log(item, index);
        var nodes = $("#treeSatker").treegrid("find",item);
        if(typeof nodes==='undefined' || nodes===null || nodes == "")
        {
            jabatan= infonama[index];
            infodetilparaf+= "<li class='ListItem'>"+jabatan+"</li>";
        }
        else
        {
            jabataninfo= nodes.JABATAN_INFO;
            jabatan= nodes.JABATAN;
            namainfo= nodes.NAMA;

            var tujuan= "";
            if(jabatan == "-")
            {
                tujuan= 'Kelompok '+namainfo;
            }
            else
            {
                tujuan= jabataninfo;
            }
            infodetilparaf+= "<li class='ListItem'>"+tujuan+"</li>";
        }
        idata= parseInt(idata) + 1;
    });
    infodetilparaf+= "</ol><div class='text-danger'><i class='fa fa-info-circle' aria-hidden='true'></i> Ubah urutan pejabat dengan cara <strong><i>drag and drop</i></strong></div>";

    if(idata == 0)
    {
        infodetilparaf= '<label>Pejabat Terpilih: Pilih salah satu data</label><button class="btn btn-primary btn-sm" type="button" onClick="setsatuankerjapilih()"><i class="fa fa-user-circle"></i> Ok</button>';
    }

    $("#infodetilparaf").empty();
    $("#infodetilparaf").html(infodetilparaf);
    
    // SORT/CHANGE POSITION OF LIST ITEM
    var Items = $("#SortMe li");
    $('#SortMe').sortable({
        disabled: false,
        axis: 'y',
        forceHelperSize: true,
        start: function(evt, ui){
            $(ui.item).data('old-ndex' , ui.item.index());
        },
        update: function (event, ui) {
            var oldindex= $(ui.item).data('old-ndex');
            var newindex= ui.item.index();
            panjang= infoid.length;
            // console.log('old index -'+oldindex+' new index -'+newindex+' panjang -'+panjang);

            var tempinfoid= [];
            var tempinfonama= [];

            idtuker= infoid[oldindex];
            namatuker= infonama[oldindex];

            if(oldindex + 1 == panjang || oldindex > newindex)
            {
                infoid.splice(newindex, 0, idtuker);
                infonama.splice(newindex, 0, namatuker);

                delete infoid[oldindex+1];
                delete infonama[oldindex+1];
            }
            else
            {
                infoid.splice(newindex+1, 0, idtuker);
                infonama.splice(newindex+1, 0, namatuker);

                delete infoid[oldindex];
                delete infonama[oldindex];
            }

            infoid= clean(infoid);
            infonama= clean(infonama);

            // console.log(infonama);
        }
    }).disableSelection();
}

function clean(item) {
    var tempArr = [];
    for (var i = 0; i < item.length; i++) {
        if (item[i] !== undefined && item[i] != "") {
            tempArr.push(item[i]);
        }
    }
    return tempArr;
}

function getselected(mode)
{
    tempdataintegrasi= "";
    idata=0;
    infoid.forEach(function (item, index) {
        if(tempdataintegrasi == "")
            tempdataintegrasi= item;
        else
            tempdataintegrasi += ','+item;

        tujuan= $(('#check_'+item))[0];
        if(typeof tujuan==='undefined' || tujuan===null || tujuan == ""){}
        else
        {
            if(mode == "selected")
            {
                $(('#check_'+item))[0].checked = true;
            }
        }

        idata= parseInt(idata) + 1;
    });
}

function formatcheckbox(val,row)
{
    kelompokjabatan= row.KELOMPOK_JABATAN;
    if(kelompokjabatan == "" || kelompokjabatan == null)
        return row.NAMA;
    else
        return "<input type='checkbox' onclick=show('"+row.SATUAN_KERJA_ID+"') id='check_"+row.SATUAN_KERJA_ID+"' "+(row.checked?'checked':'')+"/>" + row.NAMA;
}

$(document).ready( function () {     
    $("#reqPencarian").focus();
    $('#reqUnitKerjaId').combobox({
        onSelect: function(param){

            if(param.id == 0)
            {
                document.location.href = "app/loadUrl/main/daftar_alamat_tujuan_lookup/?reqJenis=<?=$reqJenis?>&reqJenisSurat=<?=$reqJenisSurat?>"; 
                return;
            }
            else if(param.id == 1)
            {
                document.location.href = "app/loadUrl/main/kelompok_tujuan_lookup/?reqJenis=<?=$reqJenis?>&reqJenisSurat=<?=$reqJenisSurat?>";  
                return;                 
            }

            value= $("#reqPencarian").val();
            if (value == "")
            {
                urlApp = 'web/satuan_kerja_json/treetablemasternew/?reqStatus=1&reqUnitKerjaId='+param.id+'&reqMode=<?=$reqJenis?>';
            }
            else
            {
                urlApp = 'web/satuan_kerja_json/treetable/?reqUnitKerjaId='+param.id+'&reqMode=<?=$reqJenis?>&reqPencarian='+value;
            }
            $('#treeSatker').treegrid(
            {
                url: urlApp
                , pageSize: 1
            });
        }
    });
        
    $('input[name=reqPencarian]').keyup(function(e) {
        var value = this.value;
        $("html, body").animate({ scrollTop: 0 });
        const isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
        if (isMobile) {
             var urlApp = '';
                if (value == "")
                {
                    urlApp = 'web/satuan_kerja_json/treetablemasternew/?reqStatus=1&reqUnitKerjaId='+$('#reqUnitKerjaId').combobox("getValue")+'&reqMode=<?=$reqJenis?>';
                }
                else
                {
                    urlApp = 'web/satuan_kerja_json/treetable/?reqUnitKerjaId='+$('#reqUnitKerjaId').combobox("getValue")+'&reqMode=<?=$reqJenis?>&reqPencarian='+value;
                }

                $('#treeSatker').treegrid(
                {
                    url: urlApp,
                    onLoadSuccess: function(row,param){
                        getselected("selected");
                        // console.log("s");
                    }
                }); 
            }
        else{
            if(e.keyCode == 13) {
                var urlApp = '';
                if (value == "")
                {
                    urlApp = 'web/satuan_kerja_json/treetablemasternew/?reqStatus=1&reqUnitKerjaId='+$('#reqUnitKerjaId').combobox("getValue")+'&reqMode=<?=$reqJenis?>';
                }
                else
                {
                    urlApp = 'web/satuan_kerja_json/treetable/?reqUnitKerjaId='+$('#reqUnitKerjaId').combobox("getValue")+'&reqMode=<?=$reqJenis?>&reqPencarian='+value;
                }

                $('#treeSatker').treegrid(
                {
                    url: urlApp,
                    onLoadSuccess: function(row,param){
                        getselected("selected");
                        // console.log("s");
                    }
                }); 
            }
        }
    });
        
    // $('#reqPencarian').on('change', function(){
             
    //     var value = this.value;

    //     const isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
    //     if (isMobile) {
    //        if (value == "")
    //         {
    //             urlApp = 'web/satuan_kerja_json/treetablemasternew/?reqStatus=1&reqUnitKerjaId='+$('#reqUnitKerjaId').combobox("getValue")+'&reqMode=<?=$reqJenis?>';
    //         }
    //         else
    //         {
    //             urlApp = 'web/satuan_kerja_json/treetable/?reqUnitKerjaId='+$('#reqUnitKerjaId').combobox("getValue")+'&reqMode=<?=$reqJenis?>&reqPencarian='+value;
    //         }

    //         $('#treeSatker').treegrid(
    //         {
    //             url: urlApp,
    //             onLoadSuccess: function(row,param){
    //                 getselected("selected");
    //                 // console.log("s");
    //             }
    //         }); 
    //     }
    // });
    
/*$('#treeSatker').treegrid({
          onDblClickRow: function(param){
             //top.tambahPegawai(param.id, param.text);
             if(typeof param.JABATAN==='undefined' || param.JABATAN===null) 
             {
                $.messager.alert('Info', "Pejabat belum ditentukan.", 'info');  
                return;
             }

             var tujuan= infosatuankerjaid= "";
             if(param.JABATAN == "-")
             {
                tujuan= 'Kelompok '+param.NAMA;
                infosatuankerjaid= param.id;
             }
             else
             {
                tujuan= param.JABATAN + '['+param.SATUAN_KERJA_ID_PARENT+']';
                infosatuankerjaid= param.SATUAN_KERJA_ID;
             }
             //top.document.getElementById('contentFrame').contentWindow.addSatuanKerja('<?=$reqJenis?>', param.SATUAN_KERJA_ID, tujuan);
             var  divIdField = "<?=$reqIdField?>";
             // top.addSatuanKerja('<?=$reqJenis?>', infosatuankerjaid, tujuan, divIdField);
             // $('#treeSatker').treegrid('deleteRow', param.id);
             // top.closePopup();
          }
    });*/

});

$("#dnd-example tr").click(function(){
    $(this).addClass('selected').siblings().removeClass('selected');
    var id = $(this).find('td:first').attr('id');
    var title = $(this).find('td:first').attr('title');
}); 
</script>
    
<script>
    // Mendapatkan tinggi .area-konten-atas
    var divTinggi = $(".area-konten-atas").height();
    //alert(divTinggi);
    
    // Menentukan tinggi tableContainer
    $('#tableContainer').css({ 'height': 'calc(100% - ' + divTinggi+ 'px)' });
</script>

<!--<ol id="SortMe" class="Nodot">
    <li class="ListItem">Item 1</li>
    <li class="ListItem">Item 2</li>
    <li class="ListItem">Item 3</li>
    <li class="ListItem">Item 4</li>
    <li class="ListItem">Item 5</li>
</ol>-->
</body>
</html>