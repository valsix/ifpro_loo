<?
$reqId = $this->input->get("reqId");
$reqTipe = $this->input->get("reqTipe");

$vnama= 'Lokasi ';
$vnamadetil= 'Outdoor';
if ($reqTipe=='I') 
{
    $vnamadetil= 'Indoor';
}
$vnama.= $vnamadetil;
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<base href="<?=base_url();?>" />

<script src="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/jquery/dist/jquery.min.js"></script>
<script src="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/js/bootstrap.js"></script>
<link href="lib/valsix/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/gaya.css" type="text/css">

<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link rel="stylesheet" href="lib/font-awesome/4.5.0/css/font-awesome.css">

<script type="text/javascript" src="js/jquery-1.7.1.js" ></script>
<script type="text/javascript" src="js/jquery-ui.js"></script>

<link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript" src="lib/easyui/globalfunction.js"></script>

<link rel="stylesheet" href="lib/font-awesome-4.7.0/css/font-awesome.css" type="text/css">

<style type="text/css">
    .panel.combo-p{
        width: 300px !important;
    }

    .col-md-12{
        padding-left:0px;
        padding-right:0px;
    }

    .col-md-6{
        float: left;
        width: 50%;
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
</script>

<body class="body-popup">
    
    <div class="container-fluid container-treegrid">
        <div class="row row-treegrid">
            <div class="col-md-3 col-treegrid">
                <div class="konten">
                    
                    
                    <div id="infodetilparaf">
                        <label>Lokasi Terpilih: </label>
                        
                        <button class="btn btn-primary btn-sm" type="button" onClick="setsatuankerjapilih()"><i class="fa fa-user-circle"></i> Ok</button>
                        <br><span>Pilih salah satu data!</span>
                    </div>
                </div>
            </div>
            <div class="col-md-9 col-treegrid">
                <div class="area-konten-atas">
                    <div class="judul-halaman"> <?=ucwords(strtolower($vnama))?></div>
                    <div class="area-menu-aksi">

                        <div id="bluemenu" class="aksi-area">
                            <label class="col-md-5"></label>

                            <label class="col-md-7 text-right">
                            <span>Pencarian </span> 
                            <input type="text" name="reqPencarian" class="easyui-validatebox textbox form-control" id="reqPencarian" style="width:300px"> 
                            </label>
                        </div>
                        
                    </div>
                    
                </div>
                
                <div id="tableContainer" class="tableContainer tableContainer-treegrid">
                    <table id="treeSatker" class="easyui-treegrid" style="min-width:100px !important;height:300px"
                            data-options="
                                url: 'web/lokasi_loo_detil_json/combo/?reqId=<?=$reqId?>&reqTipe=<?=$reqTipe?>',
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
                                <th data-options="field:'NAMA_LOKASI_LOO',width:100" formatter="formatcheckbox">Nama</th>
                                <th data-options="field:'text',width:100">Detil Lokasi</th>
                                <th data-options="field:'KODE',width:100">Kode</th>
                                <th data-options="field:'LANTAI',width:300">Lantai</th>
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
    if(infoid.length == 0)
    {
        $.messager.alert('Info', "Pilih data terlebih dahulu.", 'info');
        return;
    }
    else
    {
        vparam= [];
        infoid.forEach(function (item, index) {
            // console.log(item, index);
            var nodes = $("#treeSatker").treegrid("find",item);
            vparam.push(nodes);
        });
        // console.log(vparam);return false;
        top.addmulti('<?=$reqTipe?>', vparam);
        top.closePopup();
    }
}

function show(checkid)
{
    // console.log(checkid);
    var s = '#check_'+checkid;
    // console.log(s);
    var nodes = $("#treeSatker").treegrid("find",checkid);
    // console.log(nodes);
    idselected= nodes.id;
    namainfo= nodes.KODE + " - " + nodes.LANTAI;

    var tujuan= "";
    tujuan= namainfo;
    // console.log(tujuan);

    if(typeof tujuan==='undefined' || tujuan===null || tujuan == "") 
    {
        $.messager.alert('Info', "Lokasi belum ditentukan.", 'info');
        return;
    }
    else
    {
        infochecked= $(s)[0].checked;
        // console.log(infochecked);
        $(('#check_'+idselected))[0].checked = infochecked;

        // console.log(infoid);
        if(infochecked == false)
        {
            var elementRow= infoid.indexOf(checkid);
            // console.log(checkid);
            // console.log(elementRow);
            if(parseInt(elementRow) >= 0)
            {
                // console.log(infoid);
                // infoid.splice(newindex, 0, idtuker);
                // infonama.splice(newindex, 0, namatuker);

                delete infoid[elementRow];
                delete infonama[elementRow];
                // console.log(infoid);

                // let filtered = arr.filter((obj) => { return ![null, undefined].includes(obj) })
                infoid= infoid.filter(function (obj) { return ![null, undefined].includes(obj) })
                infonama= infonama.filter(function (obj) { return ![null, undefined].includes(obj) })
                // console.log(infoid);

                // infoid.splice(elementRow, 1);
                // infonama.splice(elementRow, 1);
            }
        }
        else
        {
            infoid.push(String(checkid));
            infonama.push(String(tujuan));
        }

        // console.log(infoid); console.log(infonama);
        setinfo();

    }

}

function setinfo()
{
    idata=0;
    infodetilparaf= '<label>Lokasi Terpilih:</label><button class="btn btn-primary btn-sm" type="button" onClick="setsatuankerjapilih()"><i class="fa fa-user-circle"></i> Ok</button>';
    infodetilparaf+= "<ol id='SortMe'>";

    infoid.forEach(function (item, index) {
        // console.log(item, index);
        var nodes = $("#treeSatker").treegrid("find",item);
        // console.log(nodes);
        if(typeof nodes==='undefined' || nodes===null || nodes == "")
        {
            // console.log("x");
            jabatan= infonama[index];
            infodetilparaf+= "<li class='ListItem'>"+jabatan+"</li>";
        }
        else
        {
            // console.log("y");
            namainfo= nodes.KODE + " - " + nodes.LANTAI;

            var tujuan= "";
            tujuan= namainfo;
            infodetilparaf+= "<li class='ListItem'>"+tujuan+"</li>";
        }
        idata= parseInt(idata) + 1;
    });
    infodetilparaf+= "</ol><div class='text-danger'><i class='fa fa-info-circle' aria-hidden='true'></i> Ubah urutan lokasi dengan cara <strong><i>drag and drop</i></strong></div>";

    if(idata == 0)
    {
        infodetilparaf= '<label>Pejabat Terpilih: Pilih salah satu data</label><button class="btn btn-primary btn-sm" type="button" onClick="setsatuankerjapilih()"><i class="fa fa-user-circle"></i> Ok</button>';
    }
    // console.log(infoid); console.log(infonama);

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

            // console.log(infoid); console.log(infonama);
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
    // console.log(val); console.log(row);

    vreturn= "";
    dnama= row.NAMA_LOKASI_LOO;
    // console.log(dnama);
    if(dnama == "" || dnama == null)
        vreturn= row.NAMA_LOKASI_LOO;
    else
        vreturn= "<input type='checkbox' onclick=show('"+row.id+"') id='check_"+row.id+"' "+(row.checked?'checked':'')+"/> " + row.NAMA_LOKASI_LOO;

    // console.log(vreturn);
    return vreturn;
}

$(document).ready( function () {     
    $("#reqPencarian").focus();
        
    $('input[name=reqPencarian]').keyup(function(e) {
        var value = this.value;
        $("html, body").animate({ scrollTop: 0 });
        // console.log('xxxx');
        const isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
        if (isMobile) {
             var urlApp = '';
                if (value == "")
                {
                    urlApp = 'web/lokasi_loo_detil_json/combo/?reqId=<?=$reqId?>&reqTipe=<?=$reqTipe?>';
                }
                else
                {
                    urlApp = 'web/lokasi_loo_detil_json/combo/?reqId=<?=$reqId?>&reqTipe=<?=$reqTipe?>&reqPencarian='+value;
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
                    urlApp = 'web/lokasi_loo_detil_json/combo/?reqId=<?=$reqId?>&reqTipe=<?=$reqTipe?>';
                }
                else
                {
                    urlApp = 'web/lokasi_loo_detil_json/combo/?reqId=<?=$reqId?>&reqTipe=<?=$reqTipe?>&reqPencarian='+value;
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

});

$("#dnd-example tr").click(function(){
    $(this).addClass('selected').siblings().removeClass('selected');
    var id = $(this).find('td:first').attr('id');
    var title = $(this).find('td:first').attr('title');
}); 
</script>
    
<script>
    var divTinggi = $(".area-konten-atas").height();
    // Menentukan tinggi tableContainer
    $('#tableContainer').css({ 'height': 'calc(100% - ' + divTinggi+ 'px)' });
</script>
</body>
</html>