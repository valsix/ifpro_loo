var ajaxserverselectsingle = function() {
    var cekreturn = function(checkvaldata) {
        valreturn= true;
        if(checkvaldata == "1")
            valreturn= false;

        return valreturn;
    };

    var initdynamistable = function(valtableid, valjsonurl, valarrdata, valgroup) {

        if(typeof datadefaultorder == "undefined")
        {
            datadefaultorder= 2;
        }
        infodefaultorder= datadefaultorder;
        // console.log(infodefaultorder);

        if(typeof datastateduration == "undefined")
        {
            // Set state duration to 1 day
            datastateduration= 60 * 60 * 24;
        }
        infostateduration= datastateduration;

        infocolumnsdef= [];
        infocolumns= [];
        infotargets= [];
        valarrdata.forEach(function (item, index) {
            infofield= item["field"];
            infodisplay= item["display"];

            infocolumnsdef.push(infofield);

            setdisplay= true;
            if(infodisplay == "1")
            {
                infotargets.push(index);
                setdisplay= false;
            }

            var infodetil= {};
            infodetil.data= infofield;
            infodetil.visible= setdisplay;
            infocolumns.push(infodetil);
        });
        infogroupfield= valarrdata[0]["field"];
        // console.log(valarrdata[0]["field"]);
        // console.log(infocolumns);
        // console.log(infotargets);
        // console.log(infocolumnsdef);

        var valorderdefault= valarrdata.length - infodefaultorder;
        var table; var groupColumn = valorderdefault;
        var collapsedGroups = {};
        datanewtable= $('#'+valtableid);

        if(typeof datainforesponsive == "undefined")
        {
            datainforesponsive= "";
        }
        inforesponsive= cekreturn(datainforesponsive);

        if(typeof datainfostatesave == "undefined")
        {
            // datainfostatesave= "1";
            datainfostatesave= "";
        }
        infostatesave= cekreturn(datainfostatesave);

        if(typeof carijenis == "undefined" || carijenis == "")
        {
            carijenis= "1";
        }

        if(typeof datainfoscrollx == "undefined")
        {
            datainfoscrollx= "";
        }
        infoscrollx= cekreturn(datainfoscrollx);

        if(typeof datapagelength == "undefined")
        {
            pagelength= 10;
        }
        else
        {
            pagelength= datapagelength;
        }


        if(valgroup == "1")
        {
            // console.log(infogroupfield);
            table= datanewtable.DataTable({
                bLengthChange : false
                // , bInfo : false 
                , pageLength: pagelength
                , responsive: inforesponsive
                , "scrollY": infoscrolly+"vh"
                , "scrollX": infoscrollx
                , "stateSave": infostatesave
                , "stateDuration": infostateduration
                // , responsive: true
                // , searchDelay: 500
                , processing: true
                , serverSide: true
                , rowGroup: {
                    className: 'table-group',
                    dataSrc: infogroupfield,
                    startRender: function ( rows, group ) {
                        var collapsed = !!collapsedGroups[group];
                        rows.nodes().each(function (r) {
                            r.style.display = collapsed ? 'none' : '';
                        });
                        return $('<tr/>')
                            .append('<td colspan="'+valarrdata.length+'">' + group + '</td>')
                            // .append('<td colspan="'+valarrdata.length+'">' + group + ' (' + rows.count() + ')</td>')
                            .attr('data-name', group)
                            .toggleClass('collapsed', collapsed);
                      },
                }
                , order: [[ valorderdefault, "desc" ]]
                , columnDefs: [
                    { className: 'never', targets: infotargets }
                ]

                , ajax: 
                {
                    url: valjsonurl
                    , type: 'POST'
                    , data: {columnsDef: infocolumnsdef},
                }
                , columns: infocolumns
                , "fnDrawCallback": function( oSettings ) {
                    $('#'+infotableid+'_filter input').unbind();
                    $('#'+infotableid+'_filter input').bind('keyup', function(e) {
                        if(e.keyCode == 13) {
                            // carijenis= "1";
                            calltriggercari();
                        }
                    });

                    reloadglobalklikcheck();
                }
                , "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
                    // console.log(aData);
                    /*var valueStyle= loopIndex= "";
                    valueStyle= nRow % 2;
                    loopIndex= 6;
                    
                    if( aData[7] == '1')
                    {
                        $($(nRow).children()).attr('class', 'hukumanstyle');
                    }
                    else if( aData[7] == '2')
                    {
                        $($(nRow).children()).attr('class', 'hukumanpernahstyle');
                    }*/
                    
                    // $($(nRow).children()).attr('class', 'warnatandamerah');
                }

            });

            $('#'+valtableid+' tbody').on('click', 'tr.dtrg-start', function() {
                var name = $(this).data('name');
                collapsedGroups[name] = !collapsedGroups[name];
                table.draw(false);
            });
        }
        else
        {
            table= datanewtable.DataTable({
                bLengthChange : false
                // , bInfo : false 
                , pageLength: pagelength
                // , sScrollX: 100,
                // , sScrollXInner: 100
                , "scrollY": infoscrolly+"vh"
                , "scrollX": infoscrollx
                , "stateSave": infostatesave
                , "stateDuration": infostateduration
                , responsive: inforesponsive
                // , searchDelay: 500
                , processing: true
                , serverSide: true
                , order: []
                // , order: [[ valorderdefault, "desc" ]]
                , columnDefs: [
                    { className: 'never', targets: infotargets }
                ]
                , ajax: 
                {
                    url: valjsonurl
                    , type: 'POST'
                    , data: {columnsDef: infocolumnsdef},
                }
                , columns: infocolumns
                , "fnDrawCallback": function( oSettings ) {
                    $('#'+infotableid+'_filter input').unbind();
                    $('#'+infotableid+'_filter input').bind('keyup', function(e) {
                        if(e.keyCode == 13) {
                            // carijenis= "1";
                            calltriggercari();
                        }
                    });

                    reloadglobalklikcheck();
                }
                , "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
                    // console.log(aData);
                    // console.log(valarrdata[infobold]["field"]);
                    color= bold= "";
                    if(typeof infobold == "undefined"){}
                    else
                    {
                        vbold= aData[valarrdata[infobold]["field"]];
                        bold= "bold";
                        if(vbold == "1")
                        {
                            bold= "";
                        }
                    }

                    if(typeof infocolor == "undefined"){}
                    else
                    {
                        vcolor= aData[valarrdata[infocolor]["field"]];
                        if( vcolor == 'Rahasia')
                        {
                            color= "fdd6d6";
                        }
                        else if( vcolor == 'Sangat Segera')
                        {
                            color= "ffeeba";
                        }
                        else if( vcolor == 'Segera')
                        {
                            color= "b4ebff";
                        }
                    }
                    // console.log(bold+"-"+color);

                    $($(nRow).children()).attr('style', 'font-weight:'+bold+'; background-color:#'+color);
                }

            });
        }
    };

    return {
        init: function(valtableid, valjsonurl, valarrdata, valgroup) {
            if(typeof valgroup==='undefined' || valgroup===null || valgroup == "") 
            {
                valgroup= "";
            }

            initdynamistable(valtableid, valjsonurl, valarrdata, valgroup);
        },
    };

}();

function reloadglobalklikcheck()
{
    if(typeof infoglobalarrid == "undefined")
    {
        return false;
    }
    
    reqinfoglobalid= String($("#reqGlobalValidasiCheck").val());
    // console.log(reqinfoglobalid);
    arrinfoglobalid= reqinfoglobalid.split(',');

    var i= "";
    if(reqinfoglobalid == ""){}
    else
    {
        infoglobalarrid= arrinfoglobalid;

        for(var i=0; i<infoglobalarrid.length; i++) 
        {
            $("#reqPilihCheck"+infoglobalarrid[i]).prop('checked', true);
            // console.log("#reqPilihCheck"+infoglobalarrid[i]);
        }
    }   
}

function setglobalklikcheck()
{
    if(typeof infoglobalarrid == "undefined")
    {
        return false;
    }

    reqinfoglobalid= String($("#reqGlobalValidasiCheck").val());
    // console.log(reqinfoglobalid);
    arrinfoglobalid= reqinfoglobalid.split(',');

    var i= "";
    if(reqinfoglobalid == ""){}
    else
    {
        infoglobalarrid= arrinfoglobalid;
        i= infoglobalarrid.length - 1;
        i= infoglobalarrid.length;
    }

    reqPilihCheck= reqpilihcheckval= reqNominalBantuan= reqNominalBantuanVal= reqCatatan= reqCatatanVal= "";
    $('input[id^="reqPilihCheck"]:checkbox:checked').each(function(i){
        reqPilihCheck= $(this).val();
        var id= $(this).attr('id');
        id= id.replace("reqPilihCheck", "");

        if(reqpilihcheckval == "")
        {
            reqpilihcheckval= reqPilihCheck;
            // reqNominalBantuanVal= reqNominalBantuan;
            // reqCatatanVal= reqCatatan;
        }
        else
        {
            reqpilihcheckval= reqpilihcheckval+","+reqPilihCheck;
            // reqNominalBantuanVal= reqNominalBantuanVal+","+reqNominalBantuan;
            // reqCatatanVal= reqCatatanVal+"||"+reqCatatan;
        }

        var elementRow= infoglobalarrid.indexOf(reqPilihCheck);
        if(elementRow == -1)
        {
            i= infoglobalarrid.length;

            infoglobalarrid[i]= reqPilihCheck;
        }

    });

    $('input[id^="reqPilihCheck"]:checkbox:not(:checked)').each(function(i){
        reqPilihCheck= $(this).val();
        var id= $(this).attr('id');
        id= id.replace("reqPilihCheck", "");

        var elementRow= infoglobalarrid.indexOf(reqPilihCheck);
        if(parseInt(elementRow) >= 0)
        {
            infoglobalarrid.splice(elementRow, 1);
        }
    });

    reqPilihCheck= reqpilihcheckval= reqNominalBantuan= reqNominalBantuanVal= reqCatatan= reqCatatanVal= "";
    reqTotalNominal= reqTotalOrang= 0;

    for(var i=0; i<infoglobalarrid.length; i++) 
    {
        if(reqpilihcheckval == "")
        {
            reqpilihcheckval= infoglobalarrid[i];
        }
        else
        {
            reqpilihcheckval= reqpilihcheckval+","+infoglobalarrid[i];
        }
    }
    // console.log(reqpilihcheckval);

    $("#reqGlobalValidasiCheck").val(reqpilihcheckval);
    // $("#reqValidasiForm").val(reqPilihCheckForm);
}