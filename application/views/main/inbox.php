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

<script>
function openNav(idSurat, idDisposisi) {
	//alert(idSurat, idDisposisi);
	top.getJumlahSurat(idSurat, "INTERNAL");
	$("#divSurat"+idSurat).removeClass("terbaca0");
	$("#divSurat"+idSurat).addClass("terbaca1");
	
    document.getElementById("sisi-kanan").style.width = "calc(100% - 0px)";
    document.getElementById("sisi-kiri").style.marginLeft = "0%";
    //document.getElementById("sisi-kiri").style.width = "25%";
	document.getElementById("sisi-kiri").style.width = "100%";
	$("#divDetilSurat").html("");
	
	linkSurat = "inbox_detil_fhm";
		
	$.post( "app/loadUrl/template/"+linkSurat+"/", { reqId:idSurat, reqDisposisiId:idDisposisi })
	//$.post( "app/loadUrl/template/inbox_detil/", { reqId:idSurat, reqDisposisiId:idDisposisi })
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
</script>
<script>
function viewDetil(id,disposisi) {
	//alert("viewDetil");
	window.location.href = 'main/index/inbox_detil_fhm?reqId='+id+"&reqDisposisiId="+disposisi;
	/*$("#reqDisposisiId").val(disposisi);
	$("#reqId").val(id);
	$("#smbtn").click();*/
}
</script>

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
    <!--<style>
	html,
	body,
	body > .container-fluid{
		height: 100%;
	}
	</style>-->

    <div class="col-lg-12 col-konten-full">
        <div class="judul-halaman">Surat Masuk <!-- <span class="info">( <em><strong>double click</strong></em> untuk melihat detil <em>inbox</em> )</span> --></div>
        <div class="konten-detil">
        	<div id="sisi-kiri" class="area-kiri">
                <div class="area-surat-masuk-wrapper">
                    <div class="area-cari-surat">
                        <div class="searchbox" id="search_box_container"></div>
                    </div>
                    <div class="area-surat-masuk-baru" id="dataNaskah">
                 
                                    <?
                            $this->load->model("SuratMasuk");
                            $this->load->model("Disposisi");
                            $surat_masuk = new SuratMasuk();
                            $disposisi = new Disposisi();
                            $reqJenisTujuan = $this->input->get("reqJenisTujuan");
                                                  
                                    $this->load->library("Pagination");
                                    $showRecord = 6;
                                    $pageView = "web/surat_masuk_json/inbox/";

                                    // tambahan khusus
                                    $statement_privacy .= " 
                                    AND 
                                    (
                                      A.STATUS_SURAT = 'POSTING'
                                      OR
                                      A.STATUS_SURAT = 'TU-NOMOR'
                                      OR
                                      (
                                        A.STATUS_SURAT = 'TU-IN' AND
                                        EXISTS(SELECT 1 FROM SURAT_MASUK_ARSIP X WHERE X.SURAT_MASUK_ID = A.SURAT_MASUK_ID AND X.CABANG_ID = '".$this->CABANG_ID."')
                                      )
                                    ) 
                                    ";
                                  
                                    if($this->KD_LEVEL_PEJABAT == "")
                                    {
                                      $statement_privacy .= " AND (B.USER_ID = '".$this->ID."' OR B.USER_ID = '".$this->ID_ATASAN."' ) ";
                                    }
                                    else
                                    {
                                      $statement_privacy .= " AND (B.SATUAN_KERJA_ID_TUJUAN = '".$this->SATUAN_KERJA_ID_ASAL."' OR B.USER_ID = '".$this->ID."' OR B.USER_ID_OBSERVER = '".$this->ID."') ";
                                    }
                                    
                                    $statement= " AND (
                                                    UPPER(A.NO_AGENDA) LIKE '%".strtoupper($_GET['sSearch'])."%' OR 
                                                    UPPER(A.NOMOR) LIKE '%".strtoupper($_GET['sSearch'])."%' OR
                                                    UPPER(A.PERIHAL) LIKE '%".strtoupper($_GET['sSearch'])."%' OR 
                                                    UPPER(A.INSTANSI_ASAL) LIKE '%".strtoupper($_GET['sSearch'])."%' 
                                                    ) ";
                                    
                                      
                                    $rowCount = $surat_masuk->getCountByParamsInbox(array(), $statement_privacy.$statement);
                                    // echo $surat_masuk->query;exit;
                                    
                                  
                                    $arrSerialized = serialize($arrStatement);	
                                    $arrSerialized = str_replace('"', '@', $arrSerialized);		
                                    $pagConfig = array('baseURL'=>$pageView, 'showRecord' => $showRecord, 'totalRows'=>$rowCount, 'perPage'=>$showRecord, 'contentDiv'=>'dataNaskah', 'arrSerialized' => $arrSerialized, 'searchVarible' => "reqPencarian", 'searchVarible2' => "reqTahun");
                                    $pagination =  new Pagination($pagConfig);				
                                    
                                    $surat_masuk->selectByParamsInbox(array(), $showRecord, 0, $statement_privacy.$statement," ORDER BY A.TANGGAL_ENTRI DESC, B.TERBACA DESC "); 
                            // echo $surat_masuk->query;exit;
                            ?>
                        
                            <?
                                        while($surat_masuk->nextRow())		
                                        {	
                                            $reqId = $surat_masuk->getField("SURAT_MASUK_ID");	
                                            $reqDisposisiId = $surat_masuk->getField("DISPOSISI_ID");	
                                            $reqTerbaca = $surat_masuk->getField("TERBACA");	
                                        
                                        ?>
                            
                                <div class="list terbaca<?=(int)$reqTerbaca?>" id="divSurat<?=$reqId?>">
                                    <?php /*?><a onClick="openNav('<?=$reqId?>', '<?=$reqDisposisiId?>')"><?php */?>
                                    <a onclick="viewDetil(<?=$reqId?>,<?=$reqDisposisiId?>)">
                                    <?php /*?><a onclick="openNav('<?=$reqId?>', '<?=$reqDisposisiId?>')"><?php */?>
                                        <div class="avatar">
                                        <?=generateFoto("X", $surat_masuk->getField("USER_ATASAN"))?>
                                        </div>
                                        <div class="asal">
                                                                <?=$surat_masuk->getField("USER_ATASAN")?><br>
                                            <span><?=substr($surat_masuk->getField("USER_ATASAN_JABATAN"), 0, 40)?></span>
											<span><?=$surat_masuk->getField("ALAMAT_ASAL")?></span>
                                        </div>
                                        <div class="isi"><span class="judul"><?=truncate($surat_masuk->getField("PERIHAL")." - ".$surat_masuk->getField("ISI"), 15)?></span>
                                            <div class="atribut">
                                                <span><i class="fa fa-tags"></i> <?=$surat_masuk->getField("JENIS_NASKAH")?></span>
                                                <span><i class="fa fa-hashtag"></i> <?=$surat_masuk->getField("NOMOR")?></span>
                                                <span><i class="fa fa-exclamation-triangle"></i> <?=$surat_masuk->getField("SIFAT_NASKAH")?></span>
                                                <!--<span><i class="fa fa-exclamation-triangle"></i> <?=$surat_masuk->getField("PRIORITAS_SURAT")?></span>-->
                                            </div>
                                        </div>
                                        <div class="tanggal-info">
                                            <span class="tanggal"><i class="fa fa-clock-o"></i> <?=$surat_masuk->getField("TANGGAL_ENTRI")?></span>
                                        </div>
                                        <div class="clearfix"></div>
                                    </a>
                                </div>
                            
                            <?
                            }
                            ?>    
                        
                    </div> <!-- END area-surat-masuk-baru -->
                    <div class="area-pagination ssss">
                      <?=$pagination->createLinks()?> 
                    </div>
                </div>
            </div> <!-- END area-kiri -->
            <!--<div id="sisi-kanan" class="area-kanan area-konten-surat" style="background:#D9EEFA">-->
            <!--<div id="sisi-kanan" class="area-kanan area-konten-surat">
                <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
                <div class="area-surat-detil" id="divDetilSurat" style="z-index:-1"></div>
            </div>-->
            <div id="sisi-kanan" class="area-kanan area-konten-surat">
                <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
                
                <div class="area-surat-detil" id="divDetilSurat">
                        
                </div>
                
            </div>
            <div class="clearfix"></div>
        </div>
    </div> <!-- END col-md-12  col-konten-full -->	
    
    <!--<form method="post" action="main/index/inbox_detil" style="display: none;">
    
        <input type="text" id="reqId" name="reqId" value="">
        <input type="text" id="reqDisposisiId" name="reqDisposisiId" value="">
        <button type="submit" id="smbtn">
        
        </button>
      
    </form>-->
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


