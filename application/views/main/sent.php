<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");
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

<!--<script>
function openNav(idSurat, jenisSurat) {
	
    document.getElementById("sisi-kanan").style.width = "calc(75% - 0px)";
    document.getElementById("sisi-kiri").style.marginLeft = "0%";
    //document.getElementById("sisi-kiri").style.width = "25%";
	document.getElementById("sisi-kiri").style.width = "100%";
	$("#divDetilSurat").html("");
	
	linkSurat = "sent_detil";
		
	$.post( "app/loadUrl/template/"+linkSurat+"/", { reqId:idSurat })
	  .done(function( data ) {
		$("#divDetilSurat").append(data);
	});	

	
}

function closeNav() {
    document.getElementById("sisi-kanan").style.width = "0";
    document.getElementById("sisi-kiri").style.marginLeft= "0";
    document.getElementById("sisi-kiri").style.width = "100%";
    //document.getElementById("openbtn").style.display = "inline";
  
}
</script>-->
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
        <div class="judul-halaman bg-course">Terkirim</div>
        <div class="konten-detil">
            <div id="sisi-kiri" class="area-kiri">
                <div class="area-surat-masuk-wrapper">
                    <div class="area-cari-surat">
                        <div class="searchbox" id="search_box_container"></div>
                    </div>
                        <?
                        $this->load->model("SuratMasuk");
                        $this->load->model("Disposisi");
                        $surat_masuk = new SuratMasuk();
                        $disposisi = new Disposisi();
                        $reqJenisTujuan = $this->input->get("reqJenisTujuan");
                                    
                        $this->load->library("Pagination");
                        $showRecord = 6;
                        $pageView = "web/surat_masuk_json/sent/";
                                        
                        //$statement_privacy .= " AND A.JENIS_TUJUAN = '".$reqJenisTujuan."' ";
                        //$statement_privacy .= " AND A.SATUAN_KERJA_ID_ASAL = '".$this->SATUAN_KERJA_ID_ASAL."' ";
                        
                        /* YANG BISA BUKA ADALAH SEKRETARISNYA DAN ORANG ITU SENDIRI */						
                      
                        $statement_privacy .= " AND (A.USER_ATASAN_ID = '".$this->ID_ATASAN."' OR A.USER_ID = '".$this->ID_ATASAN."' OR A.USER_ATASAN_ID = '".$this->ID."' OR A.USER_ID = '".$this->ID."' OR A.USER_ID_OBSERVER = '".$this->ID."' OR EXISTS(SELECT 1 FROM SURAT_MASUK_PARAF X WHERE X.SURAT_MASUK_ID = A.SURAT_MASUK_ID AND X.USER_ID = '".$this->ID."')) ";
                        $statement_privacy .= " AND (A.STATUS_SURAT IN ('TATAUSAHA','POSTING') OR A.STATUS_SURAT LIKE 'TU%') ";
                        
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
                        $surat_masuk->selectByParamsMonitoringSent(array(), $showRecord, 0, $statement_privacy.$statement, $sOrder);
                        // echo $surat_masuk->query;exit;
                        ?>
                        
                            <div class="area-surat-masuk-baru" id="dataNaskah">
                            <?
                            while($surat_masuk->nextRow())		
                            {	
                                $reqId = $surat_masuk->getField("SURAT_MASUK_ID");	
                                $reqTerbaca = $surat_masuk->getField("TERBACA_VALIDASI");		
                                $reqJenisSurat = $surat_masuk->getField("JENIS_SURAT");			
                                $reqStatusSurat = $surat_masuk->getField("STATUS_SURAT");			
                                
                            
                            ?>
                            
                                <div class="list terbaca" id="divSurat<?=$reqId?>">
                                    <a onClick="openNav('<?=$reqId?>', '<?=$reqJenisSurat?>')">
                                        <div class="avatar">
                                        <?=generateFoto("X", $surat_masuk->getField("KEPADA"))?>
                                        </div>
                                        <div class="pengirim"><?=truncate($surat_masuk->getField("KEPADA"),5)?></div>
                                        <div class="isi"><span class="judul"><strong><?=truncate($surat_masuk->getField("NOMOR")." - ".$surat_masuk->getField("PERIHAL"), 15)?></strong></span>
                                            <div class="data-tambahan-sent tutupsurat">
                                                <?
                                                if($reqJenisSurat == "EKSTERNAL")
                                                {
                                                    if($reqStatusSurat == "TU-OUT")
                                                    {
                                                    ?>
                                                        <div class="statussurat" style="width:150px !important"><i class="fa fa-flag" title="Status"></i> 
                                                            Verifikasi TU
                                                        </div>                                                
                                                    <?
                                                    }
                                                    else
                                                    {
                                                        ?>
                                                        <div class="statussurat"><i class="fa fa-flag" title="Status"></i> 
                                                            Naskah dikirim media <?=$surat_masuk->getField("MEDIA_PENGIRIMAN")?>
                                                        </div>              
                                                        <?
                                                        if($surat_masuk->getField("MEDIA_PENGIRIMAN_ID") == "1")
                                                        {
                                                        ?>
                                                        <div class="statussurat"><i class="fa fa-eye" title="Dibaca"></i> 
                                                            <?=statusCentang($surat_masuk->getField("TERBACA"))?>
                                                        </div>
                                                        <div class="statussurat"><i class="fa fa-pencil-square-o" title="Didisposisikan"></i>
                                                            <?=statusCentang($surat_masuk->getField("TERDISPOSISI"))?>
                                                        </div>
                                                        <div class="statussurat"><i class="fa fa-mail-reply-all" title="Dibalas"></i>
                                                            <?=statusCentang($surat_masuk->getField("TERBALAS"))?>
                                                        </div>
                                                        <div class="statussurat"><i class="fa fa-mail-forward" title="Diteruskan"></i>
                                                            <?=statusCentang($surat_masuk->getField("TERUSKAN"))?>
                                                        </div>
                                                        <?
                                                        }
                                                    }
                                                }
                                                else
                                                {
                                                ?>
                                                    <div class="statussurat"><i class="fa fa-eye" title="Dibaca"></i> 
                                                        <?=statusCentang($surat_masuk->getField("TERBACA"))?>
                                                    </div>
                                                    <div class="statussurat"><i class="fa fa-pencil-square-o" title="Didisposisikan"></i>
                                                        <?=statusCentang($surat_masuk->getField("TERDISPOSISI"))?>
                                                    </div>
                                                    <div class="statussurat"><i class="fa fa-mail-reply-all" title="Dibalas"></i>
                                                        <?=statusCentang($surat_masuk->getField("TERBALAS"))?>
                                                    </div>
                                                    <div class="statussurat"><i class="fa fa-mail-forward" title="Diteruskan"></i>
                                                        <?=statusCentang($surat_masuk->getField("TERUSKAN"))?>
                                                    </div>
                                                <?
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="tanggal-info">
                                            <span class="tanggal tutupsurat"><i class="fa fa-clock-o"></i> <?=$surat_masuk->getField("TANGGAL_ENTRI")?></span>
                                        </div>
                                        <div class="clearfix"></div>
                                    </a>
                                </div>
                            <?
                            }
                            ?>
                    </div>  
                    <div class="area-pagination">
                    	<?=$pagination->createLinks()?> 
                    </div>
                </div>
            </div> <!-- END area-kiri -->
            <!--<div id="sisi-kanan" class="area-kanan area-konten-surat" style="background:#D9EEFA">-->
            <!--<div id="sisi-kanan" class="area-kanan area-konten-surat">
                <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
                
                <div class="area-surat-detil" id="divDetilSurat">
                        
                </div>
                
            </div>-->
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



