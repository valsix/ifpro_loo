<?
$this->load->model("JenisNaskah");
$this->load->model("SifatSurat");
$jenis_naskah = new JenisNaskah();
$sifat_surat = new SifatSurat();

?>


<!-- Bootstrap core CSS -->
<!--<link href="lib/bootstrap-3.3.7/docs/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="lib/bootstrap-3.3.7/docs/assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">
<link href="lib/bootstrap-3.3.7/docs/examples/starter-template/starter-template.css" rel="stylesheet">
<script src="lib/bootstrap-3.3.7/docs/assets/js/ie-emulation-modes-warning.js"></script>-->

<script>
function openNav() {
    document.getElementById("sisi-kanan").style.width = "calc(60% - 0px)";
    document.getElementById("sisi-kiri").style.marginLeft = "0%";
    document.getElementById("sisi-kiri").style.width = "40%";
    //document.getElementById("openbtn").style.display = "none";
    
}

function closeNav() {
    document.getElementById("sisi-kanan").style.width = "0";
    document.getElementById("sisi-kiri").style.marginLeft= "0";
    document.getElementById("sisi-kiri").style.width = "100%";
    //document.getElementById("openbtn").style.display = "inline";
  
}
</script>
<!--<link rel="stylesheet" href="css/gaya.css" type="text/css">
<link rel="stylesheet" href="lib/font-awesome-4.7.0/css/font-awesome.css" type="text/css">-->

<!-- SLICK -->
<link rel="stylesheet" type="text/css" href="lib/slick-1.8.1/slick/slick.css">
<link rel="stylesheet" type="text/css" href="lib/slick-1.8.1/slick/slick-theme.css">

<link rel="stylesheet" href="lib/visualsearch/visualsearch-datauri.css" type="text/css" media="screen" charset="utf-8">
<link rel="stylesheet" href="lib/visualsearch/jquery-ui.css">
<script src="lib/visualsearch/dependencies.js" type="text/javascript" charset="utf-8"></script>
<script src="lib/visualsearch/visualsearch.js" type="text/javascript" charset="utf-8"></script>
<script src="lib/visualsearch/jquery.ui.js" type="text/javascript" charset="utf-8"></script>

    <script type="text/javascript" charset="utf-8">
      $(document).ready(function() {
      
      var displayDatepicker = function (callback) {
      var input = $('.search_facet.is_editing input.search_facet_input')
    
      var removeDatepicker = function () {
        input.datepicker("destroy");
        
        setTimeout(function () {  
          $('.ui-autocomplete-input').trigger({
          type: 'keypress',
          which: 13
        });
        }, 500);
        
        
      }
    
      // Put a selected date into the VS autocomplete and trigger click
      var setVisualSearch = function (date) {
        removeDatepicker()
        callback([date])
        $("ul.VS-interface:visible li.ui-menu-item a:first").click()
      }
    
      input.datepicker({
        dateFormat: 'yy-mm-dd',
        onSelect: setVisualSearch,
        onClose: removeDatepicker
      })
      input.datepicker('show')
      }
    
    
        window.visualSearch = VS.init({
          container  : $('#search_box_container'),
          query      : '',
      showFacets : true,
          unquotable : [
            'TANGGAL',
            'TANGGALAKHIR',
            'JENISNASKAH',
            'SIFATNASKAH', 
      'STATUSSURAT', 
      'PENCARIAN'
          ],
          callbacks  : {
            search : function(query, searchCollection) {
             //alert(query);
            },
            facetMatches : function(callback) {
              callback(['TANGGAL','TANGGALAKHIR',
                'JENISNASKAH', 'SIFATNASKAH', 'STATUSSURAT', 'PENCARIAN'
              ]);
            },
            valueMatches : function(facet, searchTerm, callback) {
              switch (facet) {
               case 'TANGGAL':
                  setTimeout(function () { displayDatepicker(callback) }, 0);
                  break;
               case 'TANGGALAKHIR':
                  setTimeout(function () { displayDatepicker(callback) }, 0);
                  break;
               case 'JENISNASKAH':
                  callback(<?=$jenis_naskah->getJson()?>);
                  break;
                case 'SIFATNASKAH':
                  callback(<?=$sifat_surat->getJson()?>);
                  break;
                case 'STATUSSURAT':
                  callback(['Dibaca', 'Didisposikan', 'BelumDibaca', 'BelumDisposisi']);
                  break;
              }
            }
          }
        });
      });
    </script>

  <div class="col-lg-12 col-konten-full">
        <div class="judul-halaman bg-course">Draft Manual <!-- <span>( Dobel klik untuk melihat detil draft. )</span> --></div>
        <div class="konten-detil">
          <div id="sisi-kiri" class="area-kiri">
                <div class="area-surat-masuk-wrapper">
                    <div class="area-cari-surat">
                        <div class="searchbox" id="search_box_container"></div>
                    </div>
                    
                    <?
                    $this->load->model("SuratMasuk");
                    $this->load->model("Disposisi");
                    $this->load->model("DisposisiKeluar");
                    $surat_masuk = new SuratMasuk();
                    $disposisi = new Disposisi();
                    $disposisi_keluar = new DisposisiKeluar();
                    $reqJenisTujuan = $this->input->get("reqJenisTujuan");
                                    
                    $this->load->library("Pagination");
                    $showRecord = 6;
                    $pageView = "web/surat_masuk_json/newdraftmanual/";
    
                    //$statement_privacy .= " AND A.JENIS_TUJUAN = '".$reqJenisTujuan."' ";
                    //$statement_privacy .= " AND A.SATUAN_KERJA_ID_ASAL = '".$this->SATUAN_KERJA_ID_ASAL."' ";
                    
                    $statement_privacy .= "
                    AND 
                    (
                      (
                        A.USER_ID = '".$this->ID."' AND COALESCE(NULLIF(A.NIP_MUTASI, ''), NULL) IS NULL
                      )
                      OR 
                      (
                        A.NIP_MUTASI = '".$this->ID."' AND COALESCE(NULLIF(A.USER_ID, ''), NULL) IS NOT NULL
                      )
                    )
                    AND A.JENIS_NASKAH_ID IN (1) ";
                    // $statement_privacy .= " AND A.STATUS_SURAT IN ('PARAF', 'DRAFT', 'VALIDASI', 'REVISI') ";
                    $statement_privacy .= " AND A.STATUS_SURAT IN ('DRAFT', 'REVISI') ";
                    
                    
                    $statement= " AND (
                                    UPPER(A.NO_AGENDA) LIKE '%".strtoupper($_GET['sSearch'])."%' OR 
                                    UPPER(A.NOMOR) LIKE '%".strtoupper($_GET['sSearch'])."%' OR
                                    UPPER(A.PERIHAL) LIKE '%".strtoupper($_GET['sSearch'])."%' OR 
                                    UPPER(A.INSTANSI_ASAL) LIKE '%".strtoupper($_GET['sSearch'])."%' 
                                    ) ";
                    
                    
                    $rowCount = $surat_masuk->getCountByParamsMonitoringDraft(array(), $statement_privacy.$statement);
                    
                    $arrSerialized = serialize($arrStatement);  
                    $arrSerialized = str_replace('"', '@', $arrSerialized);   
                    $pagConfig = array('baseURL'=>$pageView, 'showRecord' => $showRecord, 'totalRows'=>$rowCount, 'perPage'=>$showRecord, 'contentDiv'=>'dataNaskah', 'arrSerialized' => $arrSerialized, 'searchVarible' => "reqPencarian", 'searchVarible2' => "reqTahun");
                    $pagination =  new Pagination($pagConfig);        
    
                    $sOrder=" ORDER BY TANGGAL_ENTRI::TIMESTAMP DESC ";
                    $surat_masuk->selectByParamsMonitoringDraft(array(), $showRecord, 0, $statement_privacy.$statement, $sOrder);
                    // echo $surat_masuk->query;exit;
                    ?>
                    <div class="area-surat-masuk-baru" id="dataNaskah">
                    <?
                    while($surat_masuk->nextRow())    
                    { 
                      $reqId = $surat_masuk->getField("SURAT_MASUK_ID");
                      $reqJenisSurat = $surat_masuk->getField("JENIS_SURAT");
                      $reqJenisNaskah= $surat_masuk->getField("JENIS_NASKAH");
                      $reqJenisNaskahId= $surat_masuk->getField("JENIS_NASKAH_ID");

                      if($reqJenisNaskahId == "1")
                        $linkUbah = "surat_masuk_manual_add";

                      /*if($reqJenisSurat == "EKSTERNAL")
                      {
                        $linkUbah = "surat_keluar_add";
                      }
                      else
                      {
                        // $linkUbah = "surat_masuk_add";
                        if($reqJenisNaskah == "Nota Dinas")
                          $linkUbah = "nota_dinas_add";
                        else
                        //$linkUbah = "draft_pdf";
                          $linkUbah = "draft_detil";
                      }*/
                            ?>
                            
                                <div class="list">
                                    <!-- <a onDblClick="document.location.href='app/loadUrl/app/<?=$linkUbah?>/?reqId=<?=$reqId?>'"> -->
                                    <?php /*?><a onClick="document.location.href='app/loadUrl/app/<?=$linkUbah?>/?reqId=<?=$reqId?>'"><?php */?>
                                    <a onClick="document.location.href='main/index/<?=$linkUbah?>/?reqId=<?=$reqId?>'">
                                        <div class="status" style="display: inherit !important; text-align: center">
                                        
                                            <?
                                            if($surat_masuk->getField("STATUS_SURAT") == "VALIDASI")
                                            {
                                            ?>
                                                <span class="fa fa-paper-plane" style="color:#000; font-size:15px"></span>
                                               <span style="display:inline-block; width: 100%;"> Validasi</span>
                                            <?
                                            }
                                            elseif($surat_masuk->getField("STATUS_SURAT") == "REVISI")
                                            {
                                            ?>
                                                <span class="fa fa-edit" style="color:#F05154; font-size:15px"></span>
                                                <span style="display:inline-block; width: 100%;">Revisi</span>
                                            <?
                                            }
                                            elseif($surat_masuk->getField("STATUS_SURAT") == "PARAF")
                                            {
                                            ?>
                                                
                                                <span class="fa fa-pencil" style="color:#000; font-size:15px"></span>
                                                <span style="display:inline-block; width: 100%;">Paraf</span>
                                            <?
                                            }
                                            elseif($surat_masuk->getField("STATUS_SURAT") == "TATAUSAHA")
                                            {
                                            ?>
                                                <span class="fa fa-user" style="color:#000; font-size:15px"></span>
                                                <span style="display:inline-block; width: 100%;">TU</span>
                                            <?
                                            }
                                            else
                                            {
                                            ?>
                                                <span class="fa fa-file-o" style="color:#000; font-size:15px"></span>
                                                <span style="display:inline-block; width: 100%;">Draft</span>
                                                
                                            <?
                                            }
                                            ?>
                                        </div>
                                        <div class="pengirim"><?=truncate($surat_masuk->getField("KEPADA"),5)?></div>
                                         <div class="isi">
                                          <!-- <span class="judul"><?=truncate($surat_masuk->getField("PERIHAL")." - ".$surat_masuk->getField("ISI"), 15)?></span> -->
                                          <span class="judul"><?=$surat_masuk->getField("PERIHAL")?></span>
                                            <div class="atribut">
                                                <span><i class="fa fa-tags"></i> <?=$surat_masuk->getField("JENIS_NASKAH")?></span>
                                                <span><i class="fa fa-exclamation-triangle"></i> <?=$surat_masuk->getField("SIFAT_NASKAH")?></span>
                                                <!--<span><i class="fa fa-exclamation-triangle"></i> <?=$surat_masuk->getField("PRIORITAS_SURAT")?></span>-->
                                            </div>
                                        </div>
                                        <div class="tanggal-info">
                                            <span><i class="fa fa-clock-o"></i> <?=$surat_masuk->getField("TANGGAL_ENTRI")?></span>
                                        </div>
                                        <div class="clearfix"></div>
                                    </a>
                                </div>
                            <?
                            }
                            ?>

                            <div class="area-pagination">
                              <?=$pagination->createLinks()?> 
                            </div>

                    </div>  
                    
                    
                </div>
            </div> <!-- END area-kiri -->
            <div class="clearfix"></div>
        </div>
  </div>
    

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<!--<script src="lib/bootstrap-3.3.7/docs/dist/js/bootstrap.min.js"></script>-->
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<!--<script src="lib/bootstrap-3.3.7/docs/assets/js/ie10-viewport-bug-workaround.js"></script>-->
<script>
function setURL(url){
    document.getElementById('iframe').src = url;
}
</script>
<script src="lib/slick-1.8.1/slick/slick.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">

// paging
    var $status = $('.pagingInfo');
    var $slickElement = $('.slider');
    
    $slickElement.on('init reInit afterChange', function (event, slick, currentSlide, nextSlide) {
      //currentSlide is undefined on init -- set it to 0 in this case (currentSlide is 0 based)
      var i = (currentSlide ? currentSlide : 0) + 1;
      $status.text('');
    });
    
$(document).on('ready', function() {
  
  $(".ui-autocomplete-input").attr("readonly", true);
      
  $(".lazy").slick({
    lazyLoad: 'ondemand', // ondemand progressive anticipated
    infinite: true,
    //autoplay: true
  });
  
    
});
</script>

