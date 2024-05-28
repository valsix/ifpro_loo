<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class surat_masuk_json extends CI_Controller {
	var $calendarId = 'cohu8p4q74iks6dpilk7mrpvi4@group.calendar.google.com';

	function __construct() {
		parent::__construct();
		
		if (!$this->kauth->getInstance()->hasIdentity())
		{
			redirect('login');
		}    
		$this->db->query("SET DATESTYLE TO PostgreSQL,European;");   
		$this->ID				= $this->kauth->getInstance()->getIdentity()->ID;   
		$this->NAMA				= $this->kauth->getInstance()->getIdentity()->NAMA;   
		$this->JABATAN			= $this->kauth->getInstance()->getIdentity()->JABATAN;   
		$this->HAK_AKSES		= $this->kauth->getInstance()->getIdentity()->HAK_AKSES;   
		$this->LAST_LOGIN		= $this->kauth->getInstance()->getIdentity()->LAST_LOGIN;   
		$this->USERNAME			= $this->kauth->getInstance()->getIdentity()->USERNAME;  
		$this->USER_LOGIN_ID	= $this->kauth->getInstance()->getIdentity()->USER_LOGIN_ID;  
		$this->USER_GROUP		= $this->kauth->getInstance()->getIdentity()->USER_GROUP;  
		$this->MULTIROLE		= $this->kauth->getInstance()->getIdentity()->MULTIROLE;  
		$this->CABANG_ID		= $this->kauth->getInstance()->getIdentity()->CABANG_ID;  
		$this->CABANG			= $this->kauth->getInstance()->getIdentity()->CABANG;  
		$this->SATUAN_KERJA_ID_ASAL	= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_ID_ASAL;  
		$this->SATUAN_KERJA_ASAL	= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_ASAL;  
		$this->SATUAN_KERJA_HIRARKI	= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_HIRARKI;  
		$this->SATUAN_KERJA_JABATAN	= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_JABATAN;  
		$this->KD_LEVEL			= $this->kauth->getInstance()->getIdentity()->KD_LEVEL;  
		$this->KD_LEVEL_PEJABAT = $this->kauth->getInstance()->getIdentity()->KD_LEVEL_PEJABAT;  
		$this->JENIS_KELAMIN 	= $this->kauth->getInstance()->getIdentity()->JENIS_KELAMIN;  
		$this->KELOMPOK_JABATAN = $this->kauth->getInstance()->getIdentity()->KELOMPOK_JABATAN;  
		$this->ID_ATASAN 		= $this->kauth->getInstance()->getIdentity()->ID_ATASAN;  
		
	}
	
	
	function approval()
	{
		
		$this->load->library("Pagination");
		$this->load->model("SuratMasuk");
		$this->load->model("Disposisi");
		$surat_masuk = new SuratMasuk();
		$disposisi = new Disposisi();
		$disposisi_keluar = new DisposisiKeluar();
		
		$reqJenisTujuan = $this->input->get("reqJenisTujuan");
		$reqPage = $this->input->post("page");
		$reqPencarian = $this->input->post("search");
		$reqShow = $this->input->post("show");
		$reqContent = $this->input->post("content");
		$reqArrStatement = unserialized($this->input->post("array_serialized"));
						
		//$statement_privacy .= " AND A.JENIS_TUJUAN = '".$reqJenisTujuan."' ";
		//$statement_privacy .= " AND A.SATUAN_KERJA_ID_ASAL = '".$this->SATUAN_KERJA_ID_ASAL."' ";
		
		$statement_privacy .= " AND (A.USER_ATASAN_ID = '".$this->ID."' OR A.USER_ATASAN_ID = '".$this->USER_GROUP.$this->ID."') ";
    
		$statement_privacy .= " AND A.STATUS_SURAT IN ('VALIDASI', 'PARAF') ";
		
		
		$statement= " AND (
						UPPER(A.NO_AGENDA) LIKE '%".strtoupper($_GET['sSearch'])."%' OR 
						UPPER(A.NOMOR) LIKE '%".strtoupper($_GET['sSearch'])."%' OR
						UPPER(A.PERIHAL) LIKE '%".strtoupper($_GET['sSearch'])."%' OR 
						UPPER(A.INSTANSI_ASAL) LIKE '%".strtoupper($_GET['sSearch'])."%' 
						) ";
		
		$rowCount = $surat_masuk->getCountByParamsMonitoringApproval(array(), $statement_privacy.$statement);
		
		$dsplyStart = !empty($reqPage)?$reqPage:0;
		$dsplyRange = $reqShow;
		
		//initialize pagination class
		$pagConfig = array('baseURL'=>'web/surat_masuk_json/approval', 'showRecord' => $reqShow, 'totalRows'=>$rowCount, 'currentPage'=>$dsplyStart, 'perPage'=>$dsplyRange, 'contentDiv'=>$reqContent, 'searchText' => $reqPencarian, 'arrSerialized' => $this->input->post("array_serialized"));
		$pagination =  new Pagination($pagConfig);
		
		if($rowCount > 0)
		{
			$surat_masuk->selectByParamsMonitoringApproval(array(), $dsplyRange, $dsplyStart, $statement_privacy.$statement);
			while($surat_masuk->nextRow())		
			{	
				$reqId = $surat_masuk->getField("SURAT_MASUK_ID");	
				$reqTerbaca = $surat_masuk->getField("TERBACA_VALIDASI");			
				$reqJenisSurat = $surat_masuk->getField("JENIS_SURAT");	
			?>
                        
                <div class="list terbaca<?=(int)$reqTerbaca?>" id="divSurat<?=$reqId?>">
                    <a onDblClick="openNav('<?=$reqId?>', '<?=$reqJenisSurat?>')">
                        <div class="avatar">
                        <?=generateFoto("X", $surat_masuk->getField("KEPADA"))?>
                        </div>
                        <div class="pengirim"><?=$surat_masuk->getField("KEPADA")?></div>
                         <div class="isi"><span class="judul"><?=truncate($surat_masuk->getField("PERIHAL")." - ".$surat_masuk->getField("ISI"), 12)?>....</span>
                            <div class="atribut">
                                <span><i class="fa fa-tags"></i> <?=$surat_masuk->getField("JENIS_NASKAH")?></span>
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
            
            <div class="area-pagination">
              <?=$pagination->createLinks()?> 
            </div>
            <? 
		}
	}
	
	
	
	function inbox()
	{
		
		$this->load->library("Pagination");
		$this->load->model("SuratMasuk");
		$this->load->model("Disposisi");
		$surat_masuk = new SuratMasuk();
		$disposisi = new Disposisi();
		$disposisi_keluar = new DisposisiKeluar();
		
		$reqJenisTujuan = $this->input->get("reqJenisTujuan");
		$reqPage = $this->input->post("page");
		$reqPencarian = $this->input->post("search");
		$reqShow = $this->input->post("show");
		$reqContent = $this->input->post("content");
		$reqArrStatement = unserialized($this->input->post("array_serialized"));
						
		$statement_privacy .= " AND A.STATUS_SURAT = 'POSTING' ";
		if($this->KD_LEVEL_PEJABAT == "")
			$statement_privacy .= " AND (B.USER_ID = '".$this->ID."' OR B.USER_ID = '".$this->ID_ATASAN."') ";
		else
			$statement_privacy .= " AND B.SATUAN_KERJA_ID_TUJUAN = '".$this->SATUAN_KERJA_ID_ASAL."' ";
		
		$statement= " AND (
						UPPER(A.NO_AGENDA) LIKE '%".strtoupper($_GET['sSearch'])."%' OR 
						UPPER(A.NOMOR) LIKE '%".strtoupper($_GET['sSearch'])."%' OR
						UPPER(A.PERIHAL) LIKE '%".strtoupper($_GET['sSearch'])."%' OR 
						UPPER(A.INSTANSI_ASAL) LIKE '%".strtoupper($_GET['sSearch'])."%' 
						) ";
						
		$rowCount = $surat_masuk->getCountByParamsInbox(array(), $statement_privacy.$statement);
		
		$dsplyStart = !empty($reqPage)?$reqPage:0;
		$dsplyRange = $reqShow;
		
		//initialize pagination class
		$pagConfig = array('baseURL'=>'web/surat_masuk_json/inbox', 'showRecord' => $reqShow, 'totalRows'=>$rowCount, 'currentPage'=>$dsplyStart, 'perPage'=>$dsplyRange, 'contentDiv'=>$reqContent, 'searchText' => $reqPencarian, 'arrSerialized' => $this->input->post("array_serialized"));
		$pagination =  new Pagination($pagConfig);
		
		if($rowCount > 0)
		{
			$surat_masuk->selectByParamsInbox(array(), $dsplyRange, $dsplyStart, $statement_privacy.$statement);
			
			while($surat_masuk->nextRow())		
			{	
				$reqId = $surat_masuk->getField("SURAT_MASUK_ID");	
				$reqDisposisiId = $surat_masuk->getField("DISPOSISI_ID");	
				$reqTerbaca = $surat_masuk->getField("TERBACA");
			?> 
                <div class="list terbaca<?=(int)$reqTerbaca?>" id="divSurat<?=$reqId?>">
                    <a onDblClick="openNav('<?=$reqId?>', '<?=$reqDisposisiId?>')">
                        <div class="avatar">
                        <?=generateFoto("X", $surat_masuk->getField("NAMA_USER_ASAL"))?>
                        </div>
                        <div class="asal">
                            <?=$surat_masuk->getField("NAMA_USER_ASAL")?><br>
                            <span><?=$surat_masuk->getField("NAMA_SATKER_ASAL")?></span>
                        </div>
                        <div class="isi"><span class="judul"><?=truncate($surat_masuk->getField("PERIHAL")." - ".$surat_masuk->getField("ISI"), 12)?>....</span>
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
            
            <div class="area-pagination">
              <?=$pagination->createLinks()?> 
            </div>
            <? 
		}
	}
	
	
	function sent()
	{
		
		$this->load->library("Pagination");
		$this->load->model("SuratMasuk");
		$this->load->model("Disposisi");
		$surat_masuk = new SuratMasuk();
		$disposisi = new Disposisi();
		$disposisi_keluar = new DisposisiKeluar();
		
		$reqJenisTujuan = $this->input->get("reqJenisTujuan");
		$reqPage = $this->input->post("page");
		$reqPencarian = $this->input->post("search");
		$reqShow = $this->input->post("show");
		$reqContent = $this->input->post("content");
		$reqArrStatement = unserialized($this->input->post("array_serialized"));
						
		$statement_privacy .= " AND (A.USER_ATASAN_ID = '".$this->ID_ATASAN."' OR A.USER_ID = '".$this->ID_ATASAN."' OR A.USER_ATASAN_ID = '".$this->ID."' OR A.USER_ID = '".$this->ID."' OR EXISTS(SELECT 1 FROM SURAT_MASUK_PARAF X WHERE X.SURAT_MASUK_ID = A.SURAT_MASUK_ID AND X.USER_ID = '".$this->ID."')) ";
		$statement_privacy .= " AND A.STATUS_SURAT IN ('POSTING') ";
		
		$statement= " AND (
						UPPER(A.NO_AGENDA) LIKE '%".strtoupper($_GET['sSearch'])."%' OR 
						UPPER(A.NOMOR) LIKE '%".strtoupper($_GET['sSearch'])."%' OR
						UPPER(A.PERIHAL) LIKE '%".strtoupper($_GET['sSearch'])."%' OR 
						UPPER(A.INSTANSI_ASAL) LIKE '%".strtoupper($_GET['sSearch'])."%' 
						) ";
		
		
		
		
						
		$rowCount = $surat_masuk->getCountByParamsMonitoringDraft(array(), $statement_privacy.$statement);
		
		$dsplyStart = !empty($reqPage)?$reqPage:0;
		$dsplyRange = $reqShow;
		
		//initialize pagination class
		$pagConfig = array('baseURL'=>'web/surat_masuk_json/sent', 'showRecord' => $reqShow, 'totalRows'=>$rowCount, 'currentPage'=>$dsplyStart, 'perPage'=>$dsplyRange, 'contentDiv'=>$reqContent, 'searchText' => $reqPencarian, 'arrSerialized' => $this->input->post("array_serialized"));
		$pagination =  new Pagination($pagConfig);
		
		if($rowCount > 0)
		{
			$surat_masuk->selectByParamsMonitoringSent(array(), $dsplyRange, $dsplyStart, $statement_privacy.$statement);
			while($surat_masuk->nextRow())		
			{	
				$reqId = $surat_masuk->getField("SURAT_MASUK_ID");	
				$reqTerbaca = $surat_masuk->getField("TERBACA_VALIDASI");		
				$reqJenisSurat = $surat_masuk->getField("JENIS_SURAT");			
			?> 
                        
                <div class="list terbaca" id="divSurat<?=$reqId?>">
                    <a onDblClick="openNav('<?=$reqId?>', '<?=$reqJenisSurat?>')">
                        <div class="avatar">
                        <?=generateFoto("X", $surat_masuk->getField("KEPADA"))?>
                        </div>
                        <div class="pengirim"><?=$surat_masuk->getField("KEPADA")?></div>
                        <div class="isi"><span class="judul"><strong><?=truncate($surat_masuk->getField("NOMOR")." - ".$surat_masuk->getField("PERIHAL"), 15)?></strong></span>
                            <div class="data-tambahan-sent tutupsurat">
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
            
            <div class="area-pagination">
              <?=$pagination->createLinks()?> 
            </div>
            <? 
		}
	}
	
	
	function draft()
	{
		
		$this->load->library("Pagination");
		$this->load->model("SuratMasuk");
		$this->load->model("Disposisi");
		$surat_masuk = new SuratMasuk();
		$disposisi = new Disposisi();
		$disposisi_keluar = new DisposisiKeluar();
		
		$reqJenisTujuan = $this->input->get("reqJenisTujuan");
		$reqPage = $this->input->post("page");
		$reqPencarian = $this->input->post("search");
		$reqShow = $this->input->post("show");
		$reqContent = $this->input->post("content");
		$reqArrStatement = unserialized($this->input->post("array_serialized"));
						
		//$statement_privacy .= " AND A.JENIS_TUJUAN = '".$reqJenisTujuan."' ";
		//$statement_privacy .= " AND A.SATUAN_KERJA_ID_ASAL = '".$this->SATUAN_KERJA_ID_ASAL."' ";
		
		$statement_privacy .= " AND A.USER_ID = '".$this->ID."' ";
		$statement_privacy .= " AND A.STATUS_SURAT IN ('PARAF', 'DRAFT', 'VALIDASI', 'REVISI') ";
		
		
		$statement= " AND (
						UPPER(A.NO_AGENDA) LIKE '%".strtoupper($_GET['sSearch'])."%' OR 
						UPPER(A.NOMOR) LIKE '%".strtoupper($_GET['sSearch'])."%' OR
						UPPER(A.PERIHAL) LIKE '%".strtoupper($_GET['sSearch'])."%' OR 
						UPPER(A.INSTANSI_ASAL) LIKE '%".strtoupper($_GET['sSearch'])."%' 
						) ";
		
		
		$rowCount = $surat_masuk->getCountByParamsMonitoringDraft(array(), $statement_privacy.$statement);
		
		$dsplyStart = !empty($reqPage)?$reqPage:0;
		$dsplyRange = $reqShow;
		
		//initialize pagination class
		$pagConfig = array('baseURL'=>'web/surat_masuk_json/draft', 'showRecord' => $reqShow, 'totalRows'=>$rowCount, 'currentPage'=>$dsplyStart, 'perPage'=>$dsplyRange, 'contentDiv'=>$reqContent, 'searchText' => $reqPencarian, 'arrSerialized' => $this->input->post("array_serialized"));
		$pagination =  new Pagination($pagConfig);
		
		if($rowCount > 0)
		{
			$surat_masuk->selectByParamsMonitoringDraft(array(), $dsplyRange, $dsplyStart, $statement_privacy.$statement);
			while($surat_masuk->nextRow())		
			{	
				$reqId = $surat_masuk->getField("SURAT_MASUK_ID");	
				$reqJenisSurat = $surat_masuk->getField("JENIS_SURAT");		
			
				$linkUbah = "surat_masuk_add";
				if($reqJenisSurat == "EKSTERNAL")		
					$linkUbah = "surat_keluar_add";
			?>
			
				<div class="list">
					<a onDblClick="document.location.href='app/loadUrl/app/<?=$linkUbah?>/?reqId=<?=$reqId?>'">
						<div class="status" style="display: inherit !important; text-align: center">
						
							<?
							if($surat_masuk->getField("STATUS_SURAT") == "VALIDASI")
							{
							?>
								<span class="fa fa-paper-plane" style="color:#000; font-size:15px"></span>
							   <span style="display:inline-block; width: 100%;"> kirim</span>
							<?
							}
							elseif($surat_masuk->getField("STATUS_SURAT") == "REVISI")
							{
							?>
								<span class="fa fa-edit" style="color:#F05154; font-size:15px"></span>
								<span style="display:inline-block; width: 100%;">revisi</span>
							<?
							}
							elseif($surat_masuk->getField("STATUS_SURAT") == "PARAF")
							{
							?>
								
								<span class="fa fa-pencil" style="color:#000; font-size:15px"></span>
								<span style="display:inline-block; width: 100%;">paraf</span>
							<?
							}
							else
							{
							?>
								<span class="fa fa-file-o" style="color:#000; font-size:15px"></span>
								<span style="display:inline-block; width: 100%;">draft</span>
								
							<?
							}
							?>
						</div>
						<div class="pengirim"><?=$surat_masuk->getField("KEPADA")?></div>
						<div class="isi"><span class="judul"><?=$surat_masuk->getField("PERIHAL")?></span> - <?=truncate($surat_masuk->getField("ISI"), 25)?>....
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
            <? 
		}
	}
	
	function json() 
	{
		$this->load->model("SuratMasuk");
		$surat_masuk = new SuratMasuk();

		$reqJenisTujuan = $this->input->get("reqJenisTujuan");
		// echo $reqKategori;exit;
		
		$aColumns = array("SURAT_MASUK_ID", "STATUS_SURAT", "NOMOR", "NO_AGENDA", "TANGGAL_ENTRI", "TANGGAL", 
						  "JENIS_NASKAH", "PERIHAL", "SIFAT_NASKAH", 
						  "KEPADA", "TEMBUSAN", "INSTANSI_ASAL", "TERBACA", "TERDISPOSISI", "TERBALAS", "USER_ID");
		$aColumnsAlias = array("A.SURAT_MASUK_ID", "A.STATUS_SURAT", "A.NOMOR", "A.NO_AGENDA", "A.TANGGAL_ENTRI", "A.TANGGAL", 
						  "JENIS_NASKAH_ID", "PERIHAL", "SIFAT_NASKAH", 
						  "PERIHAL", "PERIHAL", "INSTANSI_ASAL", "TERBALAS", "TERDISPOSISI", "TERBALAS", "USER_ID");

		/*
		 * Ordering
		 */
		if ( isset( $_GET['iSortCol_0'] ) )
		{
			$sOrder = " ORDER BY ";
			 
			//Go over all sorting cols
			for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
			{
				//If need to sort by current col
				if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
				{
					//Add to the order by clause
					$sOrder .= $aColumnsAlias[ intval( $_GET['iSortCol_'.$i] ) ];
					 
					//Determine if it is sorted asc or desc
					if (strcasecmp(( $_GET['sSortDir_'.$i] ), "asc") == 0)
					{
						$sOrder .=" asc, ";
					}else
					{
						$sOrder .=" desc, ";
					}
				}
			}
			
			//Remove the last space / comma
			$sOrder = substr_replace( $sOrder, "", -2 );
			
			//Check if there is an order by clause
			if ( trim($sOrder) == "ORDER BY A.SURAT_MASUK_ID asc" )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.TANGGAL_ENTRI DESC";
				 
			}
		}
		 
		/*
		 * Filtering
		 * NOTE this does not match the built-in DataTables filtering which does it
		 * word by word on any field. It's possible to do here, but concerned about efficiency
		 * on very large tables.
		 */
		$sWhere = "";
		$nWhereGenearalCount = 0;
		if (isset($_GET['sSearch']))
		{
			$sWhereGenearal = $_GET['sSearch'];
		}
		else
		{
			$sWhereGenearal = '';
		}
		
		if ( $_GET['sSearch'] != "" )
		{
			//Set a default where clause in order for the where clause not to fail
			//in cases where there are no searchable cols at all.
			$sWhere = " AND (";
			for ( $i=0 ; $i<count($aColumnsAlias)+1 ; $i++ )
			{
				//If current col has a search param
				if ( $_GET['bSearchable_'.$i] == "true" )
				{
					//Add the search to the where clause
					$sWhere .= $aColumnsAlias[$i]." LIKE '%".$_GET['sSearch']."%' OR ";
					$nWhereGenearalCount += 1;
				}
			}
			$sWhere = substr_replace( $sWhere, "", -3 );
			$sWhere .= ')';
		}
		 
		/* Individual column filtering */
		$sWhereSpecificArray = array();
		$sWhereSpecificArrayCount = 0;
		for ( $i=0 ; $i<count($aColumnsAlias) ; $i++ )
		{
			if ( $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
			{
				//If there was no where clause
				if ( $sWhere == "" )
				{
					$sWhere = "AND ";
				}
				else
				{
					$sWhere .= " AND ";
				}
				 
				//Add the clause of the specific col to the where clause
				$sWhere .= $aColumnsAlias[$i]." LIKE '%' || :whereSpecificParam".$sWhereSpecificArrayCount." || '%' ";
				 
				//Inc sWhereSpecificArrayCount. It is needed for the bind var.
				//We could just do count($sWhereSpecificArray) - but that would be less efficient.
				$sWhereSpecificArrayCount++;
				 
				//Add current search param to the array for later use (binding).
				$sWhereSpecificArray[] =  $_GET['sSearch_'.$i];
				 
			}
		}
		 
		//If there is still no where clause - set a general - always true where clause
		if ( $sWhere == "" )
		{
			$sWhere = " AND 1=1";
		}
		 
		//Bind variables.
		if ( isset( $_GET['iDisplayStart'] ))
		{
			$dsplyStart = $_GET['iDisplayStart'];
		}
		else{
			$dsplyStart = 0;
		}
		if ( isset( $_GET['iDisplayLength'] ) && $_GET['iDisplayLength'] != '-1' )
		{
			$dsplyRange = $_GET['iDisplayLength'];
			if ($dsplyRange > (2147483645 - intval($dsplyStart)))
			{
				$dsplyRange = 2147483645;
			}
			else
			{
				$dsplyRange = intval($dsplyRange);
			}
		}
		else
		{
			$dsplyRange = 2147483645;
		}


		$statement_privacy .= " AND A.JENIS_TUJUAN = '".$reqJenisTujuan."' ";
		//$statement_privacy .= " AND A.SATUAN_KERJA_ID_ASAL = '".$this->SATUAN_KERJA_ID_ASAL."' ";
		
		if($this->USER_GROUP == "SEKRETARIS")
			$statement_privacy .= " AND A.PENERIMA_SURAT = '".$this->SATUAN_KERJA_ID_ASAL."' ";
		elseif($this->USER_GROUP == "TATAUSAHA")
			$statement_privacy .= " AND A.PENERIMA_SURAT = '".$this->CABANG_ID."' ";
		else
			exit;
		
		$statement= " AND (
						UPPER(A.NO_AGENDA) LIKE '%".strtoupper($_GET['sSearch'])."%' OR 
						UPPER(A.NOMOR) LIKE '%".strtoupper($_GET['sSearch'])."%' OR
						UPPER(A.PERIHAL) LIKE '%".strtoupper($_GET['sSearch'])."%' OR 
						UPPER(A.INSTANSI_ASAL) LIKE '%".strtoupper($_GET['sSearch'])."%' 
						) ";
						
		$allRecord = $surat_masuk->getCountByParamsMonitoring(array(), $statement_privacy.$statement);
		// echo $allRecord;exit;
		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter =  $surat_masuk->getCountByParamsMonitoring(array(), $statement_privacy.$statement);
		
		 $surat_masuk->selectByParamsMonitoring(array(), $dsplyRange, $dsplyStart, $statement_privacy.$statement, $sOrder);
		
		// echo "IKI ".$_GET['iDisplayStart'];
		
			/*
			 * Output 
			 */
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $allRecord,
			"iTotalDisplayRecords" => $allRecordFilter,
			"aaData" => array()
		);
		
		while($surat_masuk->nextRow())		
		{		
			$row = array();		
			for ( $i=0 ; $i<count($aColumns) ; $i++ )		
			{	
				if($aColumns[$i] == "KETERANGAN")
					$row[] = truncate($surat_masuk->getField($aColumns[$i]), 2);
				elseif($aColumns[$i] == "TERBACA" || $aColumns[$i] == "TERDISPOSISI" || $aColumns[$i] == "TERBALAS")
				{
					if((int)$surat_masuk->getField($aColumns[$i]) > 0)
						$row[] = "<i class=\"fa fa-check fa-md\" aria-hidden=\"true\"></i>";
					else
						$row[] = "<i class=\"fa fa-close fa-md\" aria-hidden=\"true\"></i>";
				}					
				else
					$row[] = $surat_masuk->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		echo json_encode( $output );	

		
	}
	
	
	function json_surat_keluar_tu() 
	{
		$this->load->model("SuratMasuk");
		$surat_masuk = new SuratMasuk();

		$aColumns = array("SURAT_MASUK_ID", "STATUS_SURAT", "NOMOR", "INSTANSI_ASAL", "KEPADA", "TANGGAL_ENTRI", 
						  "PERIHAL", "JENIS_NASKAH", "SIFAT_NASKAH", "USER_ID");
		$aColumnsAlias = array("A.SURAT_MASUK_ID", "STATUS_SURAT", "A.STATUS_SURAT", "A.NOMOR", "A.TANGGAL_ENTRI", 
						  "PERIHAL", "JENIS_NASKAH_ID", "SIFAT_NASKAH", 
						  "USER_ID");

		/*
		 * Ordering
		 */
		if ( isset( $_GET['iSortCol_0'] ) )
		{
			$sOrder = " ORDER BY ";
			 
			//Go over all sorting cols
			for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
			{
				//If need to sort by current col
				if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
				{
					//Add to the order by clause
					$sOrder .= $aColumnsAlias[ intval( $_GET['iSortCol_'.$i] ) ];
					 
					//Determine if it is sorted asc or desc
					if (strcasecmp(( $_GET['sSortDir_'.$i] ), "asc") == 0)
					{
						$sOrder .=" asc, ";
					}else
					{
						$sOrder .=" desc, ";
					}
				}
			}
			
			//Remove the last space / comma
			$sOrder = substr_replace( $sOrder, "", -2 );
			
			//Check if there is an order by clause
			if ( trim($sOrder) == "ORDER BY A.SURAT_MASUK_ID asc" )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.TANGGAL_ENTRI DESC";
				 
			}
		}
		 
		/*
		 * Filtering
		 * NOTE this does not match the built-in DataTables filtering which does it
		 * word by word on any field. It's possible to do here, but concerned about efficiency
		 * on very large tables.
		 */
		$sWhere = "";
		$nWhereGenearalCount = 0;
		if (isset($_GET['sSearch']))
		{
			$sWhereGenearal = $_GET['sSearch'];
		}
		else
		{
			$sWhereGenearal = '';
		}
		
		if ( $_GET['sSearch'] != "" )
		{
			//Set a default where clause in order for the where clause not to fail
			//in cases where there are no searchable cols at all.
			$sWhere = " AND (";
			for ( $i=0 ; $i<count($aColumnsAlias)+1 ; $i++ )
			{
				//If current col has a search param
				if ( $_GET['bSearchable_'.$i] == "true" )
				{
					//Add the search to the where clause
					$sWhere .= $aColumnsAlias[$i]." LIKE '%".$_GET['sSearch']."%' OR ";
					$nWhereGenearalCount += 1;
				}
			}
			$sWhere = substr_replace( $sWhere, "", -3 );
			$sWhere .= ')';
		}
		 
		/* Individual column filtering */
		$sWhereSpecificArray = array();
		$sWhereSpecificArrayCount = 0;
		for ( $i=0 ; $i<count($aColumnsAlias) ; $i++ )
		{
			if ( $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
			{
				//If there was no where clause
				if ( $sWhere == "" )
				{
					$sWhere = "AND ";
				}
				else
				{
					$sWhere .= " AND ";
				}
				 
				//Add the clause of the specific col to the where clause
				$sWhere .= $aColumnsAlias[$i]." LIKE '%' || :whereSpecificParam".$sWhereSpecificArrayCount." || '%' ";
				 
				//Inc sWhereSpecificArrayCount. It is needed for the bind var.
				//We could just do count($sWhereSpecificArray) - but that would be less efficient.
				$sWhereSpecificArrayCount++;
				 
				//Add current search param to the array for later use (binding).
				$sWhereSpecificArray[] =  $_GET['sSearch_'.$i];
				 
			}
		}
		 
		//If there is still no where clause - set a general - always true where clause
		if ( $sWhere == "" )
		{
			$sWhere = " AND 1=1";
		}
		 
		//Bind variables.
		if ( isset( $_GET['iDisplayStart'] ))
		{
			$dsplyStart = $_GET['iDisplayStart'];
		}
		else{
			$dsplyStart = 0;
		}
		if ( isset( $_GET['iDisplayLength'] ) && $_GET['iDisplayLength'] != '-1' )
		{
			$dsplyRange = $_GET['iDisplayLength'];
			if ($dsplyRange > (2147483645 - intval($dsplyStart)))
			{
				$dsplyRange = 2147483645;
			}
			else
			{
				$dsplyRange = intval($dsplyRange);
			}
		}
		else
		{
			$dsplyRange = 2147483645;
		}


		$statement_privacy .= " AND A.STATUS_SURAT IN ('TU-OUT', 'TU-NOMOR') ";
		//$statement_privacy .= " AND A.SATUAN_KERJA_ID_ASAL = '".$this->SATUAN_KERJA_ID_ASAL."' ";
		if($this->USER_GROUP == "TATAUSAHA")
			$statement_privacy .= " AND A.CABANG_ID = '".$this->CABANG_ID."' ";
		else
			exit;
		
		$statement= " AND (
						UPPER(A.NO_AGENDA) LIKE '%".strtoupper($_GET['sSearch'])."%' OR 
						UPPER(A.NOMOR) LIKE '%".strtoupper($_GET['sSearch'])."%' OR
						UPPER(A.PERIHAL) LIKE '%".strtoupper($_GET['sSearch'])."%' OR 
						UPPER(A.INSTANSI_ASAL) LIKE '%".strtoupper($_GET['sSearch'])."%' 
						) ";
						
		$allRecord = $surat_masuk->getCountByParamsMonitoring(array(), $statement_privacy.$statement);
		// echo $allRecord;exit;
		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter =  $surat_masuk->getCountByParamsMonitoring(array(), $statement_privacy.$statement);
		
		 $surat_masuk->selectByParamsMonitoring(array(), $dsplyRange, $dsplyStart, $statement_privacy.$statement, $sOrder);
		
		// echo "IKI ".$_GET['iDisplayStart'];
		
			/*
			 * Output 
			 */
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $allRecord,
			"iTotalDisplayRecords" => $allRecordFilter,
			"aaData" => array()
		);
		
		while($surat_masuk->nextRow())		
		{		
			$row = array();		
			for ( $i=0 ; $i<count($aColumns) ; $i++ )		
			{	
				if($aColumns[$i] == "KETERANGAN")
					$row[] = truncate($surat_masuk->getField($aColumns[$i]), 2);
				elseif($aColumns[$i] == "TERBACA" || $aColumns[$i] == "TERDISPOSISI" || $aColumns[$i] == "TERBALAS")
				{
					if((int)$surat_masuk->getField($aColumns[$i]) > 0)
						$row[] = "<i class=\"fa fa-check fa-md\" aria-hidden=\"true\"></i>";
					else
						$row[] = "<i class=\"fa fa-close fa-md\" aria-hidden=\"true\"></i>";
				}					
				else
					$row[] = $surat_masuk->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		echo json_encode( $output );	

		
	}
	
	
	
	
	function json_surat_masuk_tu() 
	{
		$this->load->model("SuratMasuk");
		$surat_masuk = new SuratMasuk();

		$aColumns = array("SURAT_MASUK_ID", "NOMOR", "DARI", "KEPADA", "TANGGAL_ENTRI", 
						  "PERIHAL", "JENIS_NASKAH", "SIFAT_NASKAH", "USER_ID");
		$aColumnsAlias = array("A.SURAT_MASUK_ID", "A.STATUS_SURAT", "A.NOMOR", "A.TANGGAL_ENTRI", 
						  "PERIHAL", "JENIS_NASKAH_ID", "SIFAT_NASKAH", 
						  "USER_ID");

		/*
		 * Ordering
		 */
		if ( isset( $_GET['iSortCol_0'] ) )
		{
			$sOrder = " ORDER BY ";
			 
			//Go over all sorting cols
			for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
			{
				//If need to sort by current col
				if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
				{
					//Add to the order by clause
					$sOrder .= $aColumnsAlias[ intval( $_GET['iSortCol_'.$i] ) ];
					 
					//Determine if it is sorted asc or desc
					if (strcasecmp(( $_GET['sSortDir_'.$i] ), "asc") == 0)
					{
						$sOrder .=" asc, ";
					}else
					{
						$sOrder .=" desc, ";
					}
				}
			}
			
			//Remove the last space / comma
			$sOrder = substr_replace( $sOrder, "", -2 );
			
			//Check if there is an order by clause
			if ( trim($sOrder) == "ORDER BY A.SURAT_MASUK_ID asc" )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.TANGGAL_ENTRI DESC";
				 
			}
		}
		 
		/*
		 * Filtering
		 * NOTE this does not match the built-in DataTables filtering which does it
		 * word by word on any field. It's possible to do here, but concerned about efficiency
		 * on very large tables.
		 */
		$sWhere = "";
		$nWhereGenearalCount = 0;
		if (isset($_GET['sSearch']))
		{
			$sWhereGenearal = $_GET['sSearch'];
		}
		else
		{
			$sWhereGenearal = '';
		}
		
		if ( $_GET['sSearch'] != "" )
		{
			//Set a default where clause in order for the where clause not to fail
			//in cases where there are no searchable cols at all.
			$sWhere = " AND (";
			for ( $i=0 ; $i<count($aColumnsAlias)+1 ; $i++ )
			{
				//If current col has a search param
				if ( $_GET['bSearchable_'.$i] == "true" )
				{
					//Add the search to the where clause
					$sWhere .= $aColumnsAlias[$i]." LIKE '%".$_GET['sSearch']."%' OR ";
					$nWhereGenearalCount += 1;
				}
			}
			$sWhere = substr_replace( $sWhere, "", -3 );
			$sWhere .= ')';
		}
		 
		/* Individual column filtering */
		$sWhereSpecificArray = array();
		$sWhereSpecificArrayCount = 0;
		for ( $i=0 ; $i<count($aColumnsAlias) ; $i++ )
		{
			if ( $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
			{
				//If there was no where clause
				if ( $sWhere == "" )
				{
					$sWhere = "AND ";
				}
				else
				{
					$sWhere .= " AND ";
				}
				 
				//Add the clause of the specific col to the where clause
				$sWhere .= $aColumnsAlias[$i]." LIKE '%' || :whereSpecificParam".$sWhereSpecificArrayCount." || '%' ";
				 
				//Inc sWhereSpecificArrayCount. It is needed for the bind var.
				//We could just do count($sWhereSpecificArray) - but that would be less efficient.
				$sWhereSpecificArrayCount++;
				 
				//Add current search param to the array for later use (binding).
				$sWhereSpecificArray[] =  $_GET['sSearch_'.$i];
				 
			}
		}
		 
		//If there is still no where clause - set a general - always true where clause
		if ( $sWhere == "" )
		{
			$sWhere = " AND 1=1";
		}
		 
		//Bind variables.
		if ( isset( $_GET['iDisplayStart'] ))
		{
			$dsplyStart = $_GET['iDisplayStart'];
		}
		else{
			$dsplyStart = 0;
		}
		if ( isset( $_GET['iDisplayLength'] ) && $_GET['iDisplayLength'] != '-1' )
		{
			$dsplyRange = $_GET['iDisplayLength'];
			if ($dsplyRange > (2147483645 - intval($dsplyStart)))
			{
				$dsplyRange = 2147483645;
			}
			else
			{
				$dsplyRange = intval($dsplyRange);
			}
		}
		else
		{
			$dsplyRange = 2147483645;
		}


		$statement_privacy .= " AND A.STATUS_SURAT = 'TU-IN' ";
		$statement_privacy .= " AND NOT EXISTS(SELECT 1 FROM SURAT_MASUK_ARSIP X WHERE X.SURAT_MASUK_ID = A.SURAT_MASUK_ID AND X.CABANG_ID = '".$this->CABANG_ID."') ";
		//$statement_privacy .= " AND A.SATUAN_KERJA_ID_ASAL = '".$this->SATUAN_KERJA_ID_ASAL."' ";
		if($this->USER_GROUP == "TATAUSAHA")
			$statement_privacy .= " AND EXISTS(SELECT 1 FROM DISPOSISI X WHERE X.SURAT_MASUK_ID = A.SURAT_MASUK_ID AND X.CABANG_ID_TUJUAN = '".$this->CABANG_ID."') ";
		else
			exit;
		
		$statement= " AND (
						UPPER(A.NO_AGENDA) LIKE '%".strtoupper($_GET['sSearch'])."%' OR 
						UPPER(A.NOMOR) LIKE '%".strtoupper($_GET['sSearch'])."%' OR
						UPPER(A.PERIHAL) LIKE '%".strtoupper($_GET['sSearch'])."%' OR 
						UPPER(A.INSTANSI_ASAL) LIKE '%".strtoupper($_GET['sSearch'])."%' 
						) ";
						
		$allRecord = $surat_masuk->getCountByParamsMonitoring(array(), $statement_privacy.$statement);
		// echo $allRecord;exit;
		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter =  $surat_masuk->getCountByParamsMonitoring(array(), $statement_privacy.$statement);
		
		 $surat_masuk->selectByParamsMonitoring(array(), $dsplyRange, $dsplyStart, $statement_privacy.$statement, $sOrder);
		
		// echo "IKI ".$_GET['iDisplayStart'];
		
			/*
			 * Output 
			 */
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $allRecord,
			"iTotalDisplayRecords" => $allRecordFilter,
			"aaData" => array()
		);
		
		while($surat_masuk->nextRow())		
		{		
			$row = array();		
			for ( $i=0 ; $i<count($aColumns) ; $i++ )		
			{	
				if($aColumns[$i] == "KETERANGAN")
					$row[] = truncate($surat_masuk->getField($aColumns[$i]), 2);
				elseif($aColumns[$i] == "TERBACA" || $aColumns[$i] == "TERDISPOSISI" || $aColumns[$i] == "TERBALAS")
				{
					if((int)$surat_masuk->getField($aColumns[$i]) > 0)
						$row[] = "<i class=\"fa fa-check fa-md\" aria-hidden=\"true\"></i>";
					else
						$row[] = "<i class=\"fa fa-close fa-md\" aria-hidden=\"true\"></i>";
				}					
				else
					$row[] = $surat_masuk->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		echo json_encode( $output );	

		
	}
	
	
	function json_pemberitahuan() 
	{
		$this->load->model("SuratMasuk");
		$surat_masuk = new SuratMasuk();

		$reqJenisTujuan = $this->input->get("reqJenisTujuan");
		// echo $reqKategori;exit;
		
		$aColumns = array("SURAT_MASUK_ID", "STATUS_SURAT", "NOMOR", "NO_AGENDA", "TANGGAL_ENTRI", "TANGGAL", 
						  "JENIS_NASKAH", "PERIHAL", "SIFAT_NASKAH", 
						  "KEPADA", "TEMBUSAN", "INSTANSI_ASAL", "USER_ID");
		$aColumnsAlias = array("A.SURAT_MASUK_ID", "A.STATUS_SURAT", "A.NOMOR", "A.NO_AGENDA", "A.TANGGAL_ENTRI", "A.TANGGAL", 
						  "JENIS_NASKAH_ID", "PERIHAL", "SIFAT_NASKAH", 
						  "PERIHAL", "PERIHAL", "INSTANSI_ASAL", "USER_ID");

		/*
		 * Ordering
		 */
		if ( isset( $_GET['iSortCol_0'] ) )
		{
			$sOrder = " ORDER BY ";
			 
			//Go over all sorting cols
			for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
			{
				//If need to sort by current col
				if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
				{
					//Add to the order by clause
					$sOrder .= $aColumnsAlias[ intval( $_GET['iSortCol_'.$i] ) ];
					 
					//Determine if it is sorted asc or desc
					if (strcasecmp(( $_GET['sSortDir_'.$i] ), "asc") == 0)
					{
						$sOrder .=" asc, ";
					}else
					{
						$sOrder .=" desc, ";
					}
				}
			}
			
			//Remove the last space / comma
			$sOrder = substr_replace( $sOrder, "", -2 );
			
			//Check if there is an order by clause
			if ( trim($sOrder) == "ORDER BY A.SURAT_MASUK_ID asc" )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.TANGGAL_ENTRI DESC";
				 
			}
		}
		 
		/*
		 * Filtering
		 * NOTE this does not match the built-in DataTables filtering which does it
		 * word by word on any field. It's possible to do here, but concerned about efficiency
		 * on very large tables.
		 */
		$sWhere = "";
		$nWhereGenearalCount = 0;
		if (isset($_GET['sSearch']))
		{
			$sWhereGenearal = $_GET['sSearch'];
		}
		else
		{
			$sWhereGenearal = '';
		}
		
		if ( $_GET['sSearch'] != "" )
		{
			//Set a default where clause in order for the where clause not to fail
			//in cases where there are no searchable cols at all.
			$sWhere = " AND (";
			for ( $i=0 ; $i<count($aColumnsAlias)+1 ; $i++ )
			{
				//If current col has a search param
				if ( $_GET['bSearchable_'.$i] == "true" )
				{
					//Add the search to the where clause
					$sWhere .= $aColumnsAlias[$i]." LIKE '%".$_GET['sSearch']."%' OR ";
					$nWhereGenearalCount += 1;
				}
			}
			$sWhere = substr_replace( $sWhere, "", -3 );
			$sWhere .= ')';
		}
		 
		/* Individual column filtering */
		$sWhereSpecificArray = array();
		$sWhereSpecificArrayCount = 0;
		for ( $i=0 ; $i<count($aColumnsAlias) ; $i++ )
		{
			if ( $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
			{
				//If there was no where clause
				if ( $sWhere == "" )
				{
					$sWhere = "AND ";
				}
				else
				{
					$sWhere .= " AND ";
				}
				 
				//Add the clause of the specific col to the where clause
				$sWhere .= $aColumnsAlias[$i]." LIKE '%' || :whereSpecificParam".$sWhereSpecificArrayCount." || '%' ";
				 
				//Inc sWhereSpecificArrayCount. It is needed for the bind var.
				//We could just do count($sWhereSpecificArray) - but that would be less efficient.
				$sWhereSpecificArrayCount++;
				 
				//Add current search param to the array for later use (binding).
				$sWhereSpecificArray[] =  $_GET['sSearch_'.$i];
				 
			}
		}
		 
		//If there is still no where clause - set a general - always true where clause
		if ( $sWhere == "" )
		{
			$sWhere = " AND 1=1";
		}
		 
		//Bind variables.
		if ( isset( $_GET['iDisplayStart'] ))
		{
			$dsplyStart = $_GET['iDisplayStart'];
		}
		else{
			$dsplyStart = 0;
		}
		if ( isset( $_GET['iDisplayLength'] ) && $_GET['iDisplayLength'] != '-1' )
		{
			$dsplyRange = $_GET['iDisplayLength'];
			if ($dsplyRange > (2147483645 - intval($dsplyStart)))
			{
				$dsplyRange = 2147483645;
			}
			else
			{
				$dsplyRange = intval($dsplyRange);
			}
		}
		else
		{
			$dsplyRange = 2147483645;
		}


		$statement_privacy .= " AND A.JENIS_TUJUAN = '".$reqJenisTujuan."' ";
		
		$statement_privacy .= " AND A.USER_ID = '".$this->ID."' ";
		//$statement_privacy .= " AND A.SATUAN_KERJA_ID_ASAL = '".$this->SATUAN_KERJA_ID_ASAL."' ";
		
		$statement= " AND (
						UPPER(A.NO_AGENDA) LIKE '%".strtoupper($_GET['sSearch'])."%' OR 
						UPPER(A.NOMOR) LIKE '%".strtoupper($_GET['sSearch'])."%' OR
						UPPER(A.PERIHAL) LIKE '%".strtoupper($_GET['sSearch'])."%' OR 
						UPPER(A.INSTANSI_ASAL) LIKE '%".strtoupper($_GET['sSearch'])."%' 
						) ";
						
		$allRecord = $surat_masuk->getCountByParamsMonitoring(array(), $statement_privacy.$statement);
		// echo $allRecord;exit;
		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter =  $surat_masuk->getCountByParamsMonitoring(array(), $statement_privacy.$statement);
		
		 $surat_masuk->selectByParamsMonitoring(array(), $dsplyRange, $dsplyStart, $statement_privacy.$statement, $sOrder);
		
		// echo "IKI ".$_GET['iDisplayStart'];
		
			/*
			 * Output 
			 */
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $allRecord,
			"iTotalDisplayRecords" => $allRecordFilter,
			"aaData" => array()
		);
		
		while($surat_masuk->nextRow())		
		{		
			$row = array();		
			for ( $i=0 ; $i<count($aColumns) ; $i++ )		
			{	
				if($aColumns[$i] == "KETERANGAN")
					$row[] = truncate($surat_masuk->getField($aColumns[$i]), 2);
				elseif($aColumns[$i] == "TERBACA" || $aColumns[$i] == "TERDISPOSISI" || $aColumns[$i] == "TERBALAS")
				{
					if((int)$surat_masuk->getField($aColumns[$i]) > 0)
						$row[] = "<i class=\"fa fa-check fa-md\" aria-hidden=\"true\"></i>";
					else
						$row[] = "<i class=\"fa fa-close fa-md\" aria-hidden=\"true\"></i>";
						
					
				}
				else
					$row[] = $surat_masuk->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		echo json_encode( $output );	
	}

	function json_log_registrasi() 
	{
		$this->load->model("SuratMasuk");
		$surat_masuk = new SuratMasuk();

		$reqSatuanKerjaId = $this->input->get("reqSatuanKerjaId");
		$reqJenisNaskahId = $this->input->get("reqJenisNaskahId");
		// echo $reqJenisNaskahId;exit;
		
		$aColumns = array("SURAT_MASUK_ID", "NOMOR", "PERIHAL", "TANGGAL", "INSTANSI_TUJUAN");
		$aColumnsAlias = array("SURAT_MASUK_ID", "NOMOR", "PERIHAL", "TANGGAL", "INSTANSI_TUJUAN");

		/*
		 * Ordering
		 */
		if ( isset( $_GET['iSortCol_0'] ) )
		{
			$sOrder = " ORDER BY ";
			 
			//Go over all sorting cols
			for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
			{
				//If need to sort by current col
				if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
				{
					//Add to the order by clause
					$sOrder .= $aColumnsAlias[ intval( $_GET['iSortCol_'.$i] ) ];
					 
					//Determine if it is sorted asc or desc
					if (strcasecmp(( $_GET['sSortDir_'.$i] ), "asc") == 0)
					{
						$sOrder .=" asc, ";
					}else
					{
						$sOrder .=" desc, ";
					}
				}
			}
			
			//Remove the last space / comma
			$sOrder = substr_replace( $sOrder, "", -2 );
			
			//Check if there is an order by clause
			if ( trim($sOrder) == "ORDER BY A.TANGGAL asc" )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.TANGGAL DESC";
				 
			}
		}
		 
		/*
		 * Filtering
		 * NOTE this does not match the built-in DataTables filtering which does it
		 * word by word on any field. It's possible to do here, but concerned about efficiency
		 * on very large tables.
		 */
		$sWhere = "";
		$nWhereGenearalCount = 0;
		if (isset($_GET['sSearch']))
		{
			$sWhereGenearal = $_GET['sSearch'];
		}
		else
		{
			$sWhereGenearal = '';
		}
		
		if ( $_GET['sSearch'] != "" )
		{
			//Set a default where clause in order for the where clause not to fail
			//in cases where there are no searchable cols at all.
			$sWhere = " AND (";
			for ( $i=0 ; $i<count($aColumnsAlias)+1 ; $i++ )
			{
				//If current col has a search param
				if ( $_GET['bSearchable_'.$i] == "true" )
				{
					//Add the search to the where clause
					$sWhere .= $aColumnsAlias[$i]." LIKE '%".$_GET['sSearch']."%' OR ";
					$nWhereGenearalCount += 1;
				}
			}
			$sWhere = substr_replace( $sWhere, "", -3 );
			$sWhere .= ')';
		}
		 
		/* Individual column filtering */
		$sWhereSpecificArray = array();
		$sWhereSpecificArrayCount = 0;
		for ( $i=0 ; $i<count($aColumnsAlias) ; $i++ )
		{
			if ( $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
			{
				//If there was no where clause
				if ( $sWhere == "" )
				{
					$sWhere = "AND ";
				}
				else
				{
					$sWhere .= " AND ";
				}
				 
				//Add the clause of the specific col to the where clause
				$sWhere .= $aColumnsAlias[$i]." LIKE '%' || :whereSpecificParam".$sWhereSpecificArrayCount." || '%' ";
				 
				//Inc sWhereSpecificArrayCount. It is needed for the bind var.
				//We could just do count($sWhereSpecificArray) - but that would be less efficient.
				$sWhereSpecificArrayCount++;
				 
				//Add current search param to the array for later use (binding).
				$sWhereSpecificArray[] =  $_GET['sSearch_'.$i];
				 
			}
		}
		 
		//If there is still no where clause - set a general - always true where clause
		if ( $sWhere == "" )
		{
			$sWhere = " AND 1=1";
		}
		 
		//Bind variables.
		if ( isset( $_GET['iDisplayStart'] ))
		{
			$dsplyStart = $_GET['iDisplayStart'];
		}
		else{
			$dsplyStart = 0;
		}
		if ( isset( $_GET['iDisplayLength'] ) && $_GET['iDisplayLength'] != '-1' )
		{
			$dsplyRange = $_GET['iDisplayLength'];
			if ($dsplyRange > (2147483645 - intval($dsplyStart)))
			{
				$dsplyRange = 2147483645;
			}
			else
			{
				$dsplyRange = intval($dsplyRange);
			}
		}
		else
		{
			$dsplyRange = 2147483645;
		}


		if($reqSatuanKerjaId == ""){
			$statement_privacy .= " AND A.SATUAN_KERJA_ID_ASAL = '".$this->SATUAN_KERJA_ID_ASAL."' ";
		}
		else{
			$statement_privacy .= " AND A.SATUAN_KERJA_ID_ASAL = '".$reqSatuanKerjaId."' ";
		}

		if($reqJenisNaskahId == ""){
		}
		else{
			$statement_privacy .= " AND A.JENIS_NASKAH_ID = '".$reqJenisNaskahId."' ";
		}

		$statement_privacy .= " AND A.JENIS_TUJUAN = 'NI' ";
		$statement_privacy .= " AND NOT A.NOMOR = '' ";
						
		$allRecord = $surat_masuk->getCountByParamsLogRegistrasiKeluar(array(), $statement_privacy.$statement);
		// echo $allRecord;exit;
		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter =  $surat_masuk->getCountByParamsLogRegistrasiKeluar(array(), $statement_privacy.$statement);
		
		$surat_masuk->selectByParamsLogRegistrasiKeluar(array(), $dsplyRange, $dsplyStart, $statement_privacy.$statement, $sOrder);
		// echo $surat_masuk->query;exit;
		
			/*
			 * Output 
			 */
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $allRecord,
			"iTotalDisplayRecords" => $allRecordFilter,
			"aaData" => array()
		);
		
		while($surat_masuk->nextRow())		
		{		
			$row = array();		
			for ( $i=0 ; $i<count($aColumns) ; $i++ )		
			{	
				if($aColumns[$i] == "KETERANGAN")
					$row[] = truncate($surat_masuk->getField($aColumns[$i]), 2);
				elseif($aColumns[$i] == "TERBACA" || $aColumns[$i] == "TERDISPOSISI" || $aColumns[$i] == "TERBALAS")
				{
					if((int)$surat_masuk->getField($aColumns[$i]) > 0)
						$row[] = "<i class=\"fa fa-check fa-md\" aria-hidden=\"true\"></i>";
					else
						$row[] = "<i class=\"fa fa-close fa-md\" aria-hidden=\"true\"></i>";
						
					
				}
				else
					$row[] = $surat_masuk->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		echo json_encode( $output );	
	}
	
	function add() 
	{
		
		$this->load->model("SuratMasuk");
		$this->load->model("Disposisi");
		$this->load->model("DisposisiKelompok");
		$surat_masuk = new SuratMasuk();
		$disposisi = new Disposisi();
		$disposisi_kelompok = new DisposisiKelompok();

		$reqMode 					= $this->input->post("reqMode");
		$reqId 						= $this->input->post("reqId");
		$refDisposisiId 			= $this->input->post("refDisposisiId");
		
		if($refDisposisiId == "")
			$reqIdRef = "";
		else
		{
			$surat_masuk_ref = new SuratMasuk();
			$surat_masuk_ref->selectByParams(array(), -1, -1, " AND EXISTS(SELECT 1 FROM DISPOSISI X WHERE X.SURAT_MASUK_ID = A.SURAT_MASUK_ID AND MD5('BALAS' || X.DISPOSISI_ID) = '".$refDisposisiId."') ");
			$surat_masuk_ref->firstRow();
			$reqIdRef = $surat_masuk_ref->getField("SURAT_MASUK_ID");
		}

		$reqJenisTujuan = $this->input->post("reqJenisTujuan");
		$reqJenisNaskah = $this->input->post("reqJenisNaskah");
		$reqKdLevel = $this->input->post("reqKdLevel");
		$reqNoAgenda 	= $this->input->post("reqNoAgenda");
		$reqNoSurat 	= $this->input->post("reqNoSurat");
		$reqTanggal 	= $this->input->post("reqTanggal");
		$reqPerihal	    = $this->input->post("reqPerihal");
		$reqKeterangan  = $_POST["reqKeterangan"];
		$reqSifatNaskah = $this->input->post("reqSifatNaskah");
		$reqStatusSurat = $this->input->post("reqStatusSurat");
		
		$reqAsalSuratNama 		=  $this->input->post("reqAsalSuratNama");
		$reqAsalSuratKota 		=  $this->input->post("reqAsalSuratKota");
		$reqAsalSuratAlamat 	=  $this->input->post("reqAsalSuratAlamat");
		$reqAsalSuratInstansi	=  $this->input->post("reqAsalSuratInstansi");
		$reqLokasiSurat			=  $this->input->post("reqLokasiSurat");
		$reqSatuanKerjaIdTujuan		=  $this->input->post("reqSatuanKerjaIdTujuan");
		$reqSatuanKerjaIdTujuan		=  $this->input->post("reqSatuanKerjaIdTujuan");
		$reqSatuanKerjaIdTembusan	=  $this->input->post("reqSatuanKerjaIdTembusan");	
		$reqSatuanKerjaIdParaf   	=  $this->input->post("reqSatuanKerjaIdParaf");	
		$reqKlasifikasiId   	=  $this->input->post("reqKlasifikasiId");
		$reqPenyampaianSurat	=  $this->input->post("reqPenyampaianSurat");
		$reqSatuanKerjaId		=  $this->input->post("reqSatuanKerjaId");
		
		$reqTanggalKegiatan 	 =  $this->input->post("reqTanggalKegiatan");
		$reqTanggalKegiatanAkhir =  $this->input->post("reqTanggalKegiatanAkhir");
		$reqJamKegiatan          =  $this->input->post("reqJamKegiatan");
		$reqJamKegiatanAkhir     =  $this->input->post("reqJamKegiatanAkhir");
		$reqIsEmail       =  $this->input->post("reqIsEmail");
		$reqIsMeeting     =  $this->input->post("reqIsMeeting");
		$reqRevisi     	  =  $this->input->post("reqRevisi");
		$reqPrioritasSuratId     	  =  $this->input->post("reqPrioritasSuratId");
		$reqPermohonanNomorId     	  =  $this->input->post("reqPermohonanNomorId");
		$reqArsip     	  =  $this->input->post("reqArsip");
		$reqArsipId       =  $this->input->post("reqArsipId");
		$reqJenisTTD       =  $this->input->post("reqJenisTTD");
		
		$reqLinkFileNaskah = $_FILES["reqLinkFileNaskah"];
		$reqLinkFileNaskahTemp	=  $this->input->post("reqLinkFileNaskahTemp");
		
		$reqTarget       =  $this->input->post("reqTarget");
		if($reqTarget == "")
			$reqTarget = "INTERNAL";
		
		if($reqJenisTTD == "BASAH" && $reqStatusSurat == "POSTING")
		{
			if($reqMode == "insert")
			{
				echo "0-Simpan sebagai DRAFT terlebih dahulu untuk generate Naskah.";
				return;
			}
		}
		
		
		if(count($reqSatuanKerjaIdTujuan) == 0)
		{
			echo "0-Tujuan surat belum ditentukan.";	
			return;
		}
		if(trim($reqPerihal) == "")
		{
			echo "0-Judul surat belum diisi.";	
			return;
		}
		
		$reqTanggalKeg = "NULL";
		$reqTanggalKegAkhir = "NULL";
		if($reqIsMeeting == "Y")
		{
			if($reqTanggalKegiatan == "")
			{
				$reqTanggalKeg = "NULL";
				$reqTanggalKegAkhir = "NULL";
			}
			else
			{
				if($reqJamKegiatan == "")
					$reqTanggalKeg = "TO_TIMESTAMP('".$reqTanggalKegiatan."', 'DD-MM-YYYY')";
				else
					$reqTanggalKeg = "TO_TIMESTAMP('".$reqTanggalKegiatan." ".$reqJamKegiatan."', 'DD-MM-YYYY HH24:MI')";
				
				if($reqTanggalKegiatanAkhir == "")
				{
					$reqTanggalKegAkhir = "NULL";
				}
				else
				{
					if($reqJamKegiatanAkhir == "")
						$reqTanggalKegAkhir = "TO_TIMESTAMP('".$reqTanggalKegiatanAkhir."', 'DD-MM-YYYY')";
					else
						$reqTanggalKegAkhir = "TO_TIMESTAMP('".$reqTanggalKegiatanAkhir." ".$reqJamKegiatanAkhir."', 'DD-MM-YYYY HH24:MI')";
				}
				
			}
		}
		
		$surat_masuk->setField("TANGGAL_KEGIATAN", $reqTanggalKeg);
		$surat_masuk->setField("TANGGAL_KEGIATAN_AKHIR", $reqTanggalKegAkhir);
		$surat_masuk->setField("IS_MEETING", $reqIsMeeting);
		$surat_masuk->setField("IS_EMAIL", $reqIsEmail);
		$surat_masuk->setField("PRIORITAS_SURAT_ID", $reqPrioritasSuratId);
		$surat_masuk->setField("ARSIP_ID", $reqArsipId);
		$surat_masuk->setField("ARSIP", $reqArsip);
		$surat_masuk->setField("JENIS_TTD", $reqJenisTTD);
		
		
		if($this->USER_GROUP == "SEKRETARIS")
			$surat_masuk->setField("PENERIMA_SURAT", $this->SATUAN_KERJA_ID_ASAL);
		elseif($this->USER_GROUP == "TATAUSAHA")
			$surat_masuk->setField("PENERIMA_SURAT", $this->CABANG_ID);
		
		$surat_masuk->setField("PERMOHONAN_NOMOR_ID", $reqPermohonanNomorId);
		$surat_masuk->setField("PENYAMPAIAN_SURAT", $reqPenyampaianSurat);
		$surat_masuk->setField("JENIS_TUJUAN", $reqJenisTujuan);
		$surat_masuk->setField("SURAT_MASUK_REF_ID", $reqIdRef);
		$surat_masuk->setField("SURAT_MASUK_ID", $reqId);
		$surat_masuk->setField("NO_AGENDA", $reqNoAgenda);
		$surat_masuk->setField("LOKASI_SIMPAN", $reqLokasiSurat);
		$surat_masuk->setField("NOMOR", $reqNoSurat);
		$surat_masuk->setField("TANGGAL", "CURRENT_DATE");//dateToDbCheck($reqTanggal));
		$surat_masuk->setField("JENIS_NASKAH_ID", $reqJenisNaskah);
		$surat_masuk->setField("JENIS_NASKAH_LEVEL", $reqKdLevel);
		$surat_masuk->setField("SIFAT_NASKAH", $reqSifatNaskah); 
		$surat_masuk->setField("STATUS_SURAT", $reqStatusSurat);
		$surat_masuk->setField("PERIHAL", $reqPerihal);
		$surat_masuk->setField("KLASIFIKASI_ID", $reqKlasifikasiId);
		$surat_masuk->setField("SATUAN_KERJA_ID_ASAL", $reqSatuanKerjaId);
		$surat_masuk->setField("INSTANSI_ASAL", $reqAsalSuratInstansi);
		$surat_masuk->setField("ALAMAT_ASAL", $reqAsalSuratAlamat);
		$surat_masuk->setField("KOTA_ASAL", $reqAsalSuratKota);
		$surat_masuk->setField("KETERANGAN_ASAL", $reqAsalSuratNama);
		$surat_masuk->setField("ISI", str_replace("'", "&quot;", $reqKeterangan));
		$surat_masuk->setField("CATATAN", "");
		$surat_masuk->setField("USER_ID", $this->ID);
		$surat_masuk->setField("NAMA_USER", $this->NAMA);
		$surat_masuk->setField("CABANG_ID", $this->CABANG_ID);
		$surat_masuk->setField("TARGET", $reqTarget);
		
		
		$reqTanggalKegiatan 	 =  $this->input->post("reqTanggalKegiatan");
		$reqTanggalKegiatanAkhir =  $this->input->post("reqTanggalKegiatanAkhir");
		$reqJamKegiatan          =  $this->input->post("reqJamKegiatan");
		$reqJamKegiatanAkhir     =  $this->input->post("reqJamKegiatanAkhir");
		$reqIsEmail       =  $this->input->post("reqIsEmail");
		$reqIsMeeting     =  $this->input->post("reqIsMeeting");
		
		if($reqMode == "insert")
		{
			$surat_masuk->setField("LAST_CREATE_USER", $this->ID);
			$surat_masuk->insert();
			$reqId = $surat_masuk->id;
		}
		else
		{
			$surat_masuk->setField("LAST_UPDATE_USER", $this->ID);
			$surat_masuk->update();
		}
		
		
		if($reqTarget == "INTERNAL")
		{
			if($reqJenisTTD == "BASAH" && $reqStatusSurat == "POSTING")
			{
				/* CEK APAKAH PEMBUAT / SEKRETARIS NYA */
				$surat_masuk_asal = new SuratMasuk();
				$pemilikSurat = $surat_masuk_asal->getPemilikSurat(array("SURAT_MASUK_ID" => $reqId));
				
				if($this->ID == $pemilikSurat || $this->ID_ATASAN == $pemilikSurat)
				{
					
					if($reqLinkFileNaskah["name"] == "" && $reqLinkFileNaskahTemp == "")
					{
						echo "0-Upload naskah yang sudah ditandatangani terlebih dahulu.";
						return;
					}
					else
					{
		
		
						/* WAJIB UNTUK UPLOAD DATA */
						$this->load->library("FileHandler");
						$file = new FileHandler();
						$FILE_DIR= "uploads/".$reqId."/";
						makedirs($FILE_DIR);
						
						$reqLinkFileNaskah 		= $_FILES["reqLinkFileNaskah"];
						$reqLinkFileNaskahTemp	=  $this->input->post("reqLinkFileNaskahTemp");
				
				
						$reqJenis = "NASKAHTTD".generateZero($reqId, 5);
						$renameFileNaskah = $reqJenis.date("Ymdhis").rand().".".getExtension($reqLinkFileNaskah['name']);
						
						if($file->uploadToDir('reqLinkFileNaskah', $FILE_DIR, $renameFileNaskah))
							$insertLinkFileNaskah =  $renameFileNaskah;
						else
							$insertLinkFileNaskah =  $reqLinkFileNaskahTemp;								
							
						/*  UPDATE KE SURAT_PDF */	
						$surat_pdf = new SuratMasuk();
						$surat_pdf->setField("FIELD", "SURAT_PDF");
						$surat_pdf->setField("FIELD_VALUE", $insertLinkFileNaskah);
						$surat_pdf->setField("LAST_UPDATE_USER", $this->ID);
						$surat_pdf->setField("SURAT_MASUK_ID", $reqId);
						$surat_pdf->updateByField();
						
					}
				}	
			}
		}
		
		
		/* JIKA ADA PERMOHONAN NYA MAKA UPDATE NOMORNYA */	
		if($reqPermohonanNomorId == "")
		{}
		else
		{	
			$this->load->model("PermohonanNomor");
			$permohonan_nomor = new PermohonanNomor();
	
			//echo $reqMode;
			$permohonan_nomor->setField("FIELD", "SURAT_MASUK_ID");
			$permohonan_nomor->setField("FIELD_VALUE", $reqId);
			$permohonan_nomor->setField("PERMOHONAN_NOMOR_ID", $reqPermohonanNomorId);
			$permohonan_nomor->updateByField();
			
		}
		
		/* WAJIB UNTUK UPLOAD DATA */
		$this->load->library("FileHandler");
		$file = new FileHandler();
		$FILE_DIR= "uploads/".$reqId."/";
		makedirs($FILE_DIR);
		
		$reqLinkFile = $_FILES["reqLinkFile"];
		$reqLinkFileTempSize	=  $this->input->post("reqLinkFileTempSize");
		$reqLinkFileTempTipe	=  $this->input->post("reqLinkFileTempTipe");
		$reqLinkFileTemp		=  $this->input->post("reqLinkFileTemp");
		$reqLinkFileTempNama	=  $this->input->post("reqLinkFileTempNama");


		$surat_masuk_attachement = new SuratMasuk();
		$surat_masuk_attachement->setField("SURAT_MASUK_ID", $reqId);
		$surat_masuk_attachement->deleteAttachment();


		$reqJenis = $reqJenisTujuan.generateZero($reqId, 5);
		for($i=0;$i<count($reqLinkFile);$i++)
		{
			$renameFile = $reqJenis.date("Ymdhis").rand().".".getExtension($reqLinkFile['name'][$i]);
		
			if($file->uploadToDirArray('reqLinkFile', $FILE_DIR, $renameFile, $i))
			{	
				$insertLinkSize = $file->uploadedSize;
				$insertLinkTipe =  $file->uploadedExtension;
				$insertLinkFile =  $renameFile;
				
				if($insertLinkFile == "")
				{}
				else
				{
					$surat_masuk_attachement = new SuratMasuk();
					$surat_masuk_attachement->setField("SURAT_MASUK_ID", $reqId);
					$surat_masuk_attachement->setField("ATTACHMENT", $renameFile);
					$surat_masuk_attachement->setField("UKURAN", $insertLinkSize);
					$surat_masuk_attachement->setField("TIPE", $insertLinkTipe);
					$surat_masuk_attachement->setField("NAMA", $reqLinkFile['name'][$i]);
					$surat_masuk_attachement->setField("LAST_CREATE_USER", $this->ID);
					$surat_masuk_attachement->insertAttachment();
				}
			}
			
		}

		for($i=0;$i<count($reqLinkFileTemp);$i++)
		{ 
			$insertLinkSize = $reqLinkFileTempSize[$i];
			$insertLinkTipe =  $reqLinkFileTempTipe[$i];
			$insertLinkFile =  $reqLinkFileTemp[$i];
			$insertLinkNama =  $reqLinkFileTempNama[$i];
			
			if($insertLinkFile == "")
			{}
			else
			{
				$surat_masuk_attachement = new SuratMasuk();
				$surat_masuk_attachement->setField("SURAT_MASUK_ID", $reqId);
				$surat_masuk_attachement->setField("ATTACHMENT", $insertLinkFile);
				$surat_masuk_attachement->setField("UKURAN", $insertLinkSize);
				$surat_masuk_attachement->setField("TIPE", $insertLinkTipe);
				$surat_masuk_attachement->setField("NAMA", $insertLinkNama);
				$surat_masuk_attachement->setField("LAST_CREATE_USER", $this->ID);
				$surat_masuk_attachement->insertAttachment();
			}
			
		}


		$disposisi = new Disposisi();
		$disposisi->setField("SURAT_MASUK_ID", $reqId);
		$disposisi->setField("LAST_CREATE_USER", $this->ID);
		$disposisi->deleteParent();
		
		
		$disposisi_kelompok = new DisposisiKelompok();
		$disposisi_kelompok->setField("SURAT_MASUK_ID", $reqId);
		$disposisi_kelompok->setField("LAST_CREATE_USER", $this->ID);
		$disposisi_kelompok->deleteParent();
		
		for($i=0;$i<count($reqSatuanKerjaIdTujuan);$i++)
		{
			if($reqSatuanKerjaIdTujuan[$i] == "")
			{}
			else
			{
				/* JIKA TUJUAN KELOMPOK TAMPUNG SAJA DI DISPOSISI KELOMPOK */
				if(stristr($reqSatuanKerjaIdTujuan[$i], "KELOMPOK"))
				{
					$disposisi_kelompok = new DisposisiKelompok();
					$disposisi_kelompok->setField("SURAT_MASUK_ID", $reqId);
					$disposisi_kelompok->setField("SATUAN_KERJA_ID_ASAL", $reqSatuanKerjaId);
					$disposisi_kelompok->setField("SATUAN_KERJA_KELOMPOK_ID", str_replace("KELOMPOK", "", $reqSatuanKerjaIdTujuan[$i]));
					$disposisi_kelompok->setField("STATUS_DISPOSISI", "TUJUAN");
					$disposisi_kelompok->setField("LAST_CREATE_USER", $this->ID);
					$disposisi_kelompok->insert();
				}
				else
				{
					$disposisi = new Disposisi();
					$disposisi->setField("SURAT_MASUK_ID", $reqId);
					$disposisi->setField("SATUAN_KERJA_ID_ASAL", $reqSatuanKerjaId);
					$disposisi->setField("SATUAN_KERJA_ID_TUJUAN", $reqSatuanKerjaIdTujuan[$i]);
					$disposisi->setField("STATUS_DISPOSISI", "TUJUAN");
					$disposisi->setField("LAST_CREATE_USER", $this->ID);
					$disposisi->insert();
				}
			}
		}
		
		for($i=0;$i<count($reqSatuanKerjaIdTembusan);$i++)
		{
			if($reqSatuanKerjaIdTembusan[$i] == "")
			{}
			else
			{
				
				/* JIKA TUJUAN KELOMPOK TAMPUNG SAJA DI DISPOSISI KELOMPOK */
				if(stristr($reqSatuanKerjaIdTembusan[$i], "KELOMPOK"))
				{
					$disposisi_kelompok = new DisposisiKelompok();
					$disposisi_kelompok->setField("SURAT_MASUK_ID", $reqId);
					$disposisi_kelompok->setField("SATUAN_KERJA_ID_ASAL", $reqSatuanKerjaId);
					$disposisi_kelompok->setField("SATUAN_KERJA_KELOMPOK_ID", str_replace("KELOMPOK", "", $reqSatuanKerjaIdTembusan[$i]));
					$disposisi_kelompok->setField("STATUS_DISPOSISI", "TEMBUSAN");
					$disposisi_kelompok->setField("LAST_CREATE_USER", $this->ID);
					$disposisi_kelompok->insert();
				}
				else
				{
					$disposisi = new Disposisi();
					$disposisi->setField("SURAT_MASUK_ID", $reqId);
					$disposisi->setField("SATUAN_KERJA_ID_ASAL", $reqSatuanKerjaId);
					$disposisi->setField("SATUAN_KERJA_ID_TUJUAN", $reqSatuanKerjaIdTembusan[$i]);
					$disposisi->setField("STATUS_DISPOSISI", "TEMBUSAN");
					$disposisi->setField("LAST_CREATE_USER", $this->ID);
					$disposisi->insert();
				}
			}
		}
		
		$this->load->model("SuratMasukParaf");
		$surat_masuk_paraf = new SuratMasukParaf();
		$surat_masuk_paraf->setField("SURAT_MASUK_ID", $reqId);
		$surat_masuk_paraf->setField("LAST_CREATE_USER", $this->ID);
		$surat_masuk_paraf->deleteParent();
		
		for($i=0;$i<count($reqSatuanKerjaIdParaf);$i++)
		{
			if($reqSatuanKerjaIdParaf[$i] == "")
			{}
			else
			{
				$surat_masuk_paraf = new SuratMasukParaf();
				
				$adaData = $surat_masuk_paraf->getCountByParams(array("SURAT_MASUK_ID" => $reqId, "SATUAN_KERJA_ID_TUJUAN" => $reqSatuanKerjaIdParaf[$i]));
				
				if($adaData == 0)
				{
					$surat_masuk_paraf->setField("SURAT_MASUK_ID", $reqId);
					$surat_masuk_paraf->setField("SATUAN_KERJA_ID_TUJUAN", $reqSatuanKerjaIdParaf[$i]);
					$surat_masuk_paraf->setField("LAST_CREATE_USER", $this->ID);
					$surat_masuk_paraf->insert();
				}
				
			}
		}
		
		if($reqStatusSurat == "DRAFT")
		{
			echo $reqId."-Naskah berhasil disimpan sebagai DRAFT.";
			return;
		}
		elseif($reqStatusSurat == "VALIDASI")
		{
			echo $reqId."-Naskah berhasil disimpan sebagai DRAFT.";
			return;
		}
		elseif($reqStatusSurat == "REVISI")
		{
			$surat_masuk->setField("SURAT_MASUK_ID", $reqId);
			$surat_masuk->setField("REVISI", $reqRevisi);
			$surat_masuk->setField("SATUAN_KERJA_ID_ASAL", $reqSatuanKerjaId);
			$surat_masuk->setField("REVISI_BY", $this->USERNAME);
			if($surat_masuk->revisi())
			{
				$this->revisi_notifikasi($reqId);	
				echo "Naskah telah dikembalikan ke pembuat surat.";	
				return;
			}
		}
		
		/* CEK DULU AKSES SURAT */
		$surat_akses = new SuratMasuk();
		$aksesSurat = $surat_akses->getAksesSurat(array("A.SURAT_MASUK_ID" => $reqId, "A.USER_ID" => $this->ID));
		
		if($aksesSurat == "PEMARAF")
		{
			$this->paraf_proses($reqId, "APPROVAL");	
			return;
		}
		
		/* JIKA BUKAN DRAFT YANG HANDLE ADALAH POSTING_PROSES */
		$this->posting_proses($reqId);
	
	}
	
	function paraf()
	{
		$reqId	= $this->input->get('reqId');
		
		$this->paraf_proses($reqId, "PARAF");
		
	}
	
	function paraf_proses($reqId, $reqSource)
	{

		$this->load->model("SuratMasuk");
		$surat_masuk = new SuratMasuk();
		

		$kodeParaf = "PARAF".$this->ID.generateZero($reqId, 6).date("dmYHis");
		
				
		$surat_masuk->setField("SURAT_MASUK_ID", $reqId);
		$surat_masuk->setField("KODE_PARAF", $kodeParaf);
		$surat_masuk->setField("USER_ID", $this->ID);
		$surat_masuk->setField("LAST_UPDATE_USER", $this->USERNAME);
		   
		
		if($surat_masuk->paraf())
		{
			
			/* GENERATE QRCODE */
			include_once("libraries/phpqrcode/qrlib.php");
	
			$FILE_DIR= "uploads/".$reqId."/";
			makedirs($FILE_DIR);
			$filename = $FILE_DIR.$kodeParaf.'.png';
			$errorCorrectionLevel = 'L';   
			$matrixPointSize = 2;
			QRcode::png($kodeParaf, $filename, $errorCorrectionLevel, $matrixPointSize, 2);    
			/* END OF GENERATE QRCODE */
			
			$this->terparaf_notifikasi($reqId);	
			
			/* SETIAP POSTING HIT POSTING SUPAYA APABILA PARAF SUDAH KOMPLIT LANGSUNG TERPOSTING */
			$this->posting_proses($reqId, $reqSource);
			
		}
				
	}
	
	function revisi()
	{
		$reqId	= $this->input->post('reqId');
		$reqRevisi	= $this->input->post('reqRevisi');
		
		$this->load->model("SuratMasuk");
		$surat_masuk = new SuratMasuk();
		
		$surat_masuk->setField("SURAT_MASUK_ID", $reqId);
		$surat_masuk->setField("REVISI", $reqRevisi);
		$surat_masuk->setField("SATUAN_KERJA_ID_ASAL", $this->SATUAN_KERJA_ID_ASAL);
		$surat_masuk->setField("REVISI_BY", $this->USERNAME);
		if($surat_masuk->revisi())
		{
			$this->revisi_notifikasi($reqId);	
			echo "Data berhasil dikembalikan.";
		}
		
	}


	function approval_vp()
	{
		$reqId	= $this->input->get('reqId');
	
		$reqNomor = $this->db->query("SELECT NOMOR FROM SURAT_MASUK WHERE SURAT_MASUK_ID = '".$reqId."' ")->row()->nomor;
		
		$this->load->model("SuratMasuk");
		$surat_masuk = new SuratMasuk();
		$surat_masuk->setField("SURAT_MASUK_ID", $reqId);
		$surat_masuk->approvalSurat();
		
		if($reqNomor == "")
			echo "Data berhasil diteruskan ke sekretaris.";
		else
			$this->posting_proses($reqId, "POSTING");
		
		
	}
	
	function posting()
	{
		$reqId	= $this->input->get('reqId');
		$this->posting_proses($reqId, "POSTING");
	}
	

	function posting_proses($reqId, $reqSource="")
	{
		$this->load->library('GoogleClient');
		
		/*
		$google = new GoogleClient();
		$client = $google->getSimpleClient();
		if ($client->isAccessTokenExpired()) {
		    echo "<script type=\"text/javascript\">
		        winAuth = window.open('".base_url()."web/meeting_json/auth', '_blank');
		    </script>";
		    exit();
		}
		*/

		/* POSTING PROSES SEBENARNYA YANG HANDLE ADALAH TRIGGER */
		$this->load->model("SuratMasuk");
		$surat_masuk = new SuratMasuk();
		$surat_masuk->setField("SURAT_MASUK_ID", $reqId);
		$surat_masuk->setField("SATUAN_KERJA_ID_ASAL", $this->SATUAN_KERJA_ID_ASAL);
		$surat_masuk->setField("PEMARAF_ID", $this->ID);
		$surat_masuk->setField("FIELD", "STATUS_SURAT");
		$surat_masuk->setField("FIELD_VALUE", "POSTING"); // apabila yang bikin staff nya sama trigger sudah otomatis diganti VALIDASI
		$surat_masuk->setField("LAST_UPDATE_USER", $this->USERNAME);
		$surat_masuk->setField("USER_ID", $this->ID);
		if($surat_masuk->updateByFieldValidasi())
		{
			$statusSurat = $surat_masuk->getStatusSurat(array("A.SURAT_MASUK_ID" => $reqId));
			if($statusSurat == "VALIDASI")
			{
				$this->validasi_notifikasi($reqId);	
				if($reqSource == "PARAF")
					echo "Naskah berhasil diposting ke atasan untuk validasi.";	
				elseif($reqSource == "APPROVAL")
					echo "approval-Naskah berhasil diposting ke atasan untuk validasi.";
				else
					echo "draft-Naskah berhasil diposting ke atasan untuk validasi.";
			}
			elseif($statusSurat == "PARAF")
			{
				$this->paraf_notifikasi($reqId);	
				if($reqSource == "PARAF")
					echo "Naskah berhasil diparaf.";	
				else
					echo "draft-Naskah berhasil diposting ke pemaraf sebelum diposting ke tujuan.";
			}
			/* JIKA PENERBIT NOMOR ADALAH TU DAN BELUM DINOMORKAN!! */
			elseif($statusSurat == "TU-NOMOR")
			{
				
				/* SISIPKAN GENERATE QRCODE PENANDA TANGAN ASLI APABILA POSTING */
				$kodeParaf  = $surat_masuk->getTtdSurat(array("A.SURAT_MASUK_ID" => $reqId));
				include_once("libraries/phpqrcode/qrlib.php");
		
				$FILE_DIR= "uploads/".$reqId."/";
				makedirs($FILE_DIR);
				$filename = $FILE_DIR.$kodeParaf.'.png';
				$errorCorrectionLevel = 'L';   
				$matrixPointSize = 5;
				QRcode::png($kodeParaf, $filename, $errorCorrectionLevel, $matrixPointSize, 2);    
				/* END OF GENERATE QRCODE */		
				
				if($reqSource == "POSTING")
					echo "Naskah berhasil diposting ke Tata Usaha.";				
				else
					echo "sent-Naskah berhasil diposting ke Tata Usaha.";					
			}
			else
			{

				$targetSurat = $surat_masuk->getTarget(array("A.SURAT_MASUK_ID" => $reqId));

				if($targetSurat == "EKSTERNAL")
				{

					$this->load->model("SuratMasuk");
					$surat_masuk = new SuratMasuk();
					$surat_masuk->setField("SURAT_MASUK_ID", $reqId);
					$surat_masuk->setField("FIELD", "STATUS_SURAT");
					$surat_masuk->setField("FIELD_VALUE", "TU-OUT"); // apabila yang bikin staff nya sama trigger sudah otomatis diganti VALIDASI
					$surat_masuk->setField("LAST_UPDATE_USER", $this->USERNAME);
					$surat_masuk->updateByField();
					$statusSurat = "TATAUSAHA";

				}

				
				/* SISIPKAN GENERATE QRCODE PENANDA TANGAN ASLI APABILA POSTING */
				$kodeParaf  = $surat_masuk->getTtdSurat(array("A.SURAT_MASUK_ID" => $reqId));
		
				include_once("libraries/phpqrcode/qrlib.php");
		
				$FILE_DIR= "uploads/".$reqId."/";
				makedirs($FILE_DIR);
				$filename = $FILE_DIR.$kodeParaf.'.png';
				$errorCorrectionLevel = 'L';   
				$matrixPointSize = 5;
				QRcode::png($kodeParaf, $filename, $errorCorrectionLevel, $matrixPointSize, 2);    
				/* END OF GENERATE QRCODE */		
				
				
				
				/* SEND TO GOOGLE CALENDAR */
				


				/* AMBIL JADWAL DI SURAT MASUK */
				
				
				/* AMBIL PEGAWAI DI DISPOSISI -> USER_ID RELASI KE PEGAWAI_ID UNTUK MENDAPATKAN EMAIL */

				/* SEND TO GOOGLE CALENDAR */

				/* AMBIL JADWAL DI SURAT MASUK */

				/* AMBIL PEGAWAI DI DISPOSISI -> USER_ID RELASI KE PEGAWAI_ID UNTUK MENDAPATKAN EMAIL */

				$surat_masuk->selectByParamsGoogleCalendar(array("SURAT_MASUK_ID" => $reqId));
				if($surat_masuk->firstRow())
				{
					$tanggal_kegiatan = $surat_masuk->getField("TANGGAL_KEGIATAN");
					$tanggal_kegiatan_akhir = $surat_masuk->getField("TANGGAL_KEGIATAN_AKHIR");
					$summary = $surat_masuk->getField("PERIHAL");
					$description = dropAllHtml($surat_masuk->getField("ISI"));

					$start_date = date('Y-m-d\TH:i:s', $tanggal_kegiatan)."+07:00";
					$end_date = date('Y-m-d\TH:i:s', $tanggal_kegiatan_akhir)."+07:00";

					$surat_masuk_disposisi = new SuratMasuk();
					$surat_masuk_disposisi->selectByParamsEmailDisposisi(array("SURAT_MASUK_ID" => $reqId));
					$arr_email_tujuan = array();

					while ($surat_masuk_disposisi->nextRow()) {
						$email = $surat_masuk_disposisi->getField("EMAIL");
						if($email <> "")
							$arr_email_tujuan[] = array('email' => $email);
					}

					$google = new GoogleClient();
					$client = $google->getClient();
					$service = new Google_Service_Calendar($client);

					/* https://developers.google.com/calendar/v3/reference/events/insert */
					$arrEvent = array(
					  'summary' => $summary,
					  'description' => $description,
					  'start' => array(
						'dateTime' => $start_date  
					  ),
					  'end' => array(
						'dateTime' => $end_date 
					  ),
					);

					// invite email
					if(count($arr_email_tujuan) > 0)
					{
						$arrEvent['attendees'] = $arr_email_tujuan;
						$arrEvent['reminders'] = array(
							'useDefault' => FALSE,
							'overrides' => array(
							  array('method' => 'email', 'minutes' => 24 * 60),
							  array('method' => 'popup', 'minutes' => 10),
							),
						);
					}
					
					//$event = new Google_Service_Calendar_Event($arrEvent);
					// echo(json_encode($arrEvent));
					// exit();

					//$service->events->insert($this->calendarId, $event);
				}

				/* SEND TO GOOGLE CALENDAR */
				
				/* SEND TO GOOGLE CALENDAR */
				
				
				
				if($statusSurat == "TATAUSAHA")
				{
					if($reqSource == "POSTING")
						echo "Naskah berhasil diposting ke Tata Usaha.";				
					else
						echo "sent-Naskah berhasil diposting ke Tata Usaha.";					
				}
				else
				{
					$this->posting_notifikasi($reqId);	
					if($reqSource == "POSTING")
						echo "Naskah berhasil diposting.";				
					else
						echo "sent-Naskah berhasil diposting.";
				}
			}
		}
		
	}
	
	
	function paraf_notifikasi($reqId) 
	{
		
		$this->load->model("SuratMasuk");
		$surat_masuk = new SuratMasuk();
		$surat_masuk->selectByParamsMonitoring(array("A.SURAT_MASUK_ID" => $reqId));
		$surat_masuk->firstRow();
		$reqTitle = $surat_masuk->getField("NOMOR");
		$reqBody  = $surat_masuk->getField("PERIHAL");
		/* SEND PUSH NOTIF */
		$this->load->library("PushNotification"); 
		$this->load->model("UserLoginMobile");

		$user_login_mobile = new UserLoginMobile();
		$user_login_mobile->selectByParams(array("A.STATUS" => "1"), -1, -1, " AND EXISTS(SELECT 1 FROM SURAT_MASUK_PARAF X WHERE X.USER_ID = A.PEGAWAI_ID AND 
																														  X.SURAT_MASUK_ID = '".$reqId."' 
																						  ) ");
		while($user_login_mobile->nextRow())
		{
			$row = array();
			$row['to'] = $user_login_mobile->getField("TOKEN_FIREBASE");
			$row['data']["title"] = "[PARAF]".$reqTitle;
			$row['data']["body"]  = $reqBody;
			$row['data']["tipe"]  = "INTERNAL"; // INFORMASI / CHAT
			$pushData = $row;
			$pushNotification = new PushNotification();
			$pushNotification->send_notification_v2($pushData);
			unset($row);
		}	
		
	}	
	
	function revisi_notifikasi($reqId) 
	{
		
		$this->load->model("SuratMasuk");
		$surat_masuk = new SuratMasuk();
		$surat_masuk->selectByParamsMonitoring(array("A.SURAT_MASUK_ID" => $reqId));
		$surat_masuk->firstRow();
		$reqTitle = $surat_masuk->getField("NOMOR");
		$reqBody  = $surat_masuk->getField("PERIHAL");
		/* SEND PUSH NOTIF */
		$this->load->library("PushNotification"); 
		$this->load->model("UserLoginMobile");

		$user_login_mobile = new UserLoginMobile();
		$user_login_mobile->selectByParams(array("A.STATUS" => "1"), -1, -1, " AND EXISTS(SELECT 1 FROM SURAT_MASUK X WHERE X.USER_ID = A.PEGAWAI_ID AND 
																														  X.SURAT_MASUK_ID = '".$reqId."' AND 
																														  X.STATUS_SURAT IN ('REVISI') 
																						  ) ");
		while($user_login_mobile->nextRow())
		{
			$row = array();
			$row['to'] = $user_login_mobile->getField("TOKEN_FIREBASE");
			$row['data']["title"] = "[DRAFT]".$reqTitle;
			$row['data']["body"]  = $reqBody;
			$row['data']["tipe"]  = "INTERNAL"; // INFORMASI / CHAT
			$pushData = $row;
			$pushNotification = new PushNotification();
			$pushNotification->send_notification_v2($pushData);
			unset($row);
		}	
		
	}	
	
	function terparaf_notifikasi($reqId) 
	{
		
		$this->load->model("SuratMasuk");
		$surat_masuk = new SuratMasuk();
		$surat_masuk->selectByParamsMonitoring(array("A.SURAT_MASUK_ID" => $reqId));
		$surat_masuk->firstRow();
		$reqTitle = $surat_masuk->getField("NOMOR");
		$reqBody  = $surat_masuk->getField("PERIHAL");
		/* SEND PUSH NOTIF */
		$this->load->library("PushNotification"); 
		$this->load->model("UserLoginMobile");

		$user_login_mobile = new UserLoginMobile();
		$user_login_mobile->selectByParams(array("A.STATUS" => "1"), -1, -1, " AND EXISTS(SELECT 1 FROM SURAT_MASUK X WHERE X.USER_ATASAN_ID = A.PEGAWAI_ID AND 
																														  X.SURAT_MASUK_ID = '".$reqId."' 
																						  ) ");
		while($user_login_mobile->nextRow())
		{
			$row = array();
			$row['to'] = $user_login_mobile->getField("TOKEN_FIREBASE");
			$row['data']["title"] = "[TERPARAF]".$reqTitle;
			$row['data']["body"]  = $reqBody;
			$row['data']["tipe"]  = "INTERNAL"; // INFORMASI / CHAT
			$pushData = $row;
			$pushNotification = new PushNotification();
			$pushNotification->send_notification_v2($pushData);
			unset($row);
		}	
		
	}	
	
	function validasi_notifikasi($reqId) 
	{
		
		$this->load->model("SuratMasuk");
		$surat_masuk = new SuratMasuk();
		$surat_masuk->selectByParamsMonitoring(array("A.SURAT_MASUK_ID" => $reqId));
		$surat_masuk->firstRow();
		$reqTitle = $surat_masuk->getField("NOMOR");
		$reqBody  = $surat_masuk->getField("PERIHAL");
		/* SEND PUSH NOTIF */
		$this->load->library("PushNotification"); 
		$this->load->model("UserLoginMobile");

		$user_login_mobile = new UserLoginMobile();
		$user_login_mobile->selectByParams(array("A.STATUS" => "1"), -1, -1, " AND EXISTS(SELECT 1 FROM SURAT_MASUK X WHERE X.USER_ATASAN_ID = A.PEGAWAI_ID AND 
																														  X.SURAT_MASUK_ID = '".$reqId."' AND 
																														  X.STATUS_SURAT IN ('VALIDASI') 
																						  ) ");
		while($user_login_mobile->nextRow())
		{
			$row = array();
			$row['to'] = $user_login_mobile->getField("TOKEN_FIREBASE");
			$row['data']["title"] = "[DRAFT]".$reqTitle;
			$row['data']["body"]  = $reqBody;
			$row['data']["tipe"]  = "INTERNAL"; // INFORMASI / CHAT
			$pushData = $row;
			$pushNotification = new PushNotification();
			$pushNotification->send_notification_v2($pushData);
			unset($row);
		}	
		
	}	
	
	function posting_notifikasi($reqId) 
	{
		
		$this->load->model("SuratMasuk");
		$surat_masuk = new SuratMasuk();
		$surat_masuk->selectByParamsMonitoring(array("A.SURAT_MASUK_ID" => $reqId));
		$surat_masuk->firstRow();
		$reqTitle = $surat_masuk->getField("NOMOR");
		$reqBody  = $surat_masuk->getField("PERIHAL");
		
		
		/* SEND PUSH NOTIF */
		$this->load->library("PushNotification"); 
		$this->load->model("UserLoginMobile");

		$user_login_mobile = new UserLoginMobile();
		$user_login_mobile->selectByParams(array("A.STATUS" => "1"), -1, -1, " AND EXISTS(SELECT 1 FROM DISPOSISI X WHERE X.USER_ID = A.PEGAWAI_ID AND 
																														  X.SURAT_MASUK_ID = '".$reqId."' AND 
																														  X.STATUS_DISPOSISI IN ('TUJUAN', 'TEMBUSAN') 
																						  ) ");
		while($user_login_mobile->nextRow())
		{
			$row = array();
			$row['to'] = $user_login_mobile->getField("TOKEN_FIREBASE");
			$row['data']["title"] = $reqTitle;
			$row['data']["body"]  = $reqBody;
			$row['data']["tipe"]  = "INTERNAL"; // INFORMASI / CHAT
			$pushData = $row;
			$pushNotification = new PushNotification();
			$pushNotification->send_notification_v2($pushData);
			unset($row);
		}	
		
	}	
	
	function delete() 
	{
		$reqId	= $this->input->get('reqId');
		$this->load->model("SuratMasuk");
		$surat_masuk = new SuratMasuk();

		
		$surat_masuk->setField("SURAT_MASUK_ID", $reqId);
		$surat_masuk->setField("LAST_CREATE_USER", $this->ID);
		if($surat_masuk->delete())
			$arrJson["PESAN"] = "Data berhasil dihapus.";
		else
			$arrJson["PESAN"] = "Data gagal dihapus.";		
		
		echo json_encode($arrJson);
	}	
	
	function combo() 
	{
		$this->load->model("SuratMasuk");
		$surat_masuk = new SuratMasuk();

		$surat_masuk->selectByParams(array());
		$i = 0;
		while($surat_masuk->nextRow())
		{
			$arr_json[$i]['id']		= $surat_masuk->getField("SURAT_MASUK_ID");
			$arr_json[$i]['text']	= $surat_masuk->getField("NAMA");
			$i++;
		}
		
		echo json_encode($arr_json);
	}
	
	function get_no_surat()
	{
		$reqSatkerId	  = $this->SATUAN_KERJA_ID_ASAL;
		$reqJenisNaskahId = $this->input->post("reqJenisNaskahId");
		$reqJenisTujuan	  = $this->input->post("reqJenisTujuan");
		$reqTanggal 	  = $this->input->post("reqTanggal");

		$this->load->model("SuratMasuk");
		$surat_masuk = new SuratMasuk();

		echo $surat_masuk->getNoSurat($reqSatkerId, $reqJenisNaskahId, $reqJenisTujuan, $reqTanggal);
			
	}
	
	function posting_surat_keluar_tu_bak()
	{

		$reqId		=  $this->input->post("reqId");
		$reqArsip	=  $this->input->post("reqArsip");
		$reqArsipId	=  $this->input->post("reqArsipId");
		$reqMediaPengiriman	=  $this->input->post("reqMediaPengiriman");
		
		$this->load->model("SuratMasuk");
		/* WAJIB UNTUK UPLOAD DATA */
		$this->load->library("FileHandler");
		$file = new FileHandler();
		$FILE_DIR= "uploads/".$reqId."/";
		makedirs($FILE_DIR);
		
		$reqLinkFileNaskah 		= $_FILES["reqLinkFileNaskah"];
		$reqLinkFileNaskahTemp	=  $this->input->post("reqLinkFileNaskahTemp");

  
		if($reqLinkFileNaskah["name"] == "" && $reqLinkFileNaskahTemp == "")
		{
			echo "0-Upload naskah yang sudah ditandatangani terlebih dahulu.";
			return;
		}
		else
		{
			$reqJenis = "NASKAHTTD".generateZero($reqId, 5);
			$renameFileNaskah = $reqJenis.date("Ymdhis").rand().".".getExtension($reqLinkFileNaskah['name']);
			
			if($file->uploadToDir('reqLinkFileNaskah', $FILE_DIR, $renameFileNaskah))
				$insertLinkFileNaskah =  $renameFileNaskah;
			else
				$insertLinkFileNaskah =  $reqLinkFileNaskahTemp;								
				
			/*  UPDATE KE SURAT_PDF */	
			$surat_pdf = new SuratMasuk();
			$surat_pdf->setField("FIELD", "SURAT_PDF");
			$surat_pdf->setField("FIELD_VALUE", $insertLinkFileNaskah);
			$surat_pdf->setField("LAST_UPDATE_USER", $this->ID);
			$surat_pdf->setField("SURAT_MASUK_ID", $reqId);
			$surat_pdf->updateByField();
			
			
			if($reqMediaPengiriman == "1")
				$reqStatusSurat = "TU-IN";
			else
				$reqStatusSurat = "TU-SENT";
			
			/* UPDATE PENGIRIMAN */
			$surat_masuk = new SuratMasuk();
			$surat_masuk->setField("ARSIP_ID", $reqArsipId);
			$surat_masuk->setField("ARSIP", $reqArsip);
			$surat_masuk->setField("MEDIA_PENGIRIMAN_ID", $reqMediaPengiriman);
			$surat_masuk->setField("STATUS_SURAT", $reqStatusSurat);
			$surat_masuk->setField("LAST_UPDATE_USER", $this->ID);
			$surat_masuk->setField("SURAT_MASUK_ID", $reqId);
			$surat_masuk->kirimSuratKeluar();
			
			echo "1-Naskah berhasil diproses.";
			
		}
					
		
	}

	function posting_surat_keluar_tu()
	{

		$reqId		=  $this->input->post("reqId");
		$reqArsip	=  $this->input->post("reqArsip");
		$reqArsipId	=  $this->input->post("reqArsipId");
		$reqMediaPengiriman	=  $this->input->post("reqMediaPengiriman");
		$reqJenisTTD	=  $this->input->post("reqJenisTTD");
		
		$this->load->model("SuratMasuk");
		/* WAJIB UNTUK UPLOAD DATA */
		$this->load->library("FileHandler");
		$file = new FileHandler();
		$FILE_DIR= "uploads/".$reqId."/";
		makedirs($FILE_DIR);
		
		$reqLinkFileNaskah 		= $_FILES["reqLinkFileNaskah"];
		$reqLinkFileNaskahTemp	=  $this->input->post("reqLinkFileNaskahTemp");

  
		if($reqLinkFileNaskah["name"] == "" && $reqLinkFileNaskahTemp == "" && $reqJenisTTD == "BASAH")
		{
			echo "0-Upload naskah yang sudah ditandatangani terlebih dahulu.";
			return;
		}
		else
		{
			if($reqJenisTTD == "BASAH")
			{
				$reqJenis = "NASKAHTTD".generateZero($reqId, 5);
				$renameFileNaskah = $reqJenis.date("Ymdhis").rand().".".getExtension($reqLinkFileNaskah['name']);
				
				if($file->uploadToDir('reqLinkFileNaskah', $FILE_DIR, $renameFileNaskah))
					$insertLinkFileNaskah =  $renameFileNaskah;
				else
					$insertLinkFileNaskah =  $reqLinkFileNaskahTemp;								
					
				/*  UPDATE KE SURAT_PDF */	
				$surat_pdf = new SuratMasuk();
				$surat_pdf->setField("FIELD", "SURAT_PDF");
				$surat_pdf->setField("FIELD_VALUE", $insertLinkFileNaskah);
				$surat_pdf->setField("LAST_UPDATE_USER", $this->ID);
				$surat_pdf->setField("SURAT_MASUK_ID", $reqId);
				$surat_pdf->updateByField();
				
			}
			else
			{
				

		
				/* SISIPKAN GENERATE QRCODE PENANDA TANGAN ASLI APABILA POSTING */
				$surat_masuk = new SuratMasuk();
				$kodeParaf  = $surat_masuk->getTtdSurat(array("A.SURAT_MASUK_ID" => $reqId));
				include_once("libraries/phpqrcode/qrlib.php");
		
				$FILE_DIR= "uploads/".$reqId."/";
				makedirs($FILE_DIR);
				$filename = $FILE_DIR.$kodeParaf.'.png';
				$errorCorrectionLevel = 'L';   
				$matrixPointSize = 5;
				QRcode::png($kodeParaf, $filename, $errorCorrectionLevel, $matrixPointSize, 2);    
				/* END OF GENERATE QRCODE */	 
					
				/*  UPDATE KE SURAT_PDF */	
				$surat_pdf = new SuratMasuk();
				$surat_pdf->setField("FIELD", "TTD_KODE");
				$surat_pdf->setField("FIELD_VALUE", $kodeParaf);
				$surat_pdf->setField("LAST_UPDATE_USER", $this->ID);
				$surat_pdf->setField("SURAT_MASUK_ID", $reqId);
				$surat_pdf->updateByField();
				
								
			}
			
			if($reqMediaPengiriman == "1")
				$reqStatusSurat = "TU-IN";
			else
				$reqStatusSurat = "TU-SENT";
			
			/* UPDATE PENGIRIMAN */
			$surat_masuk = new SuratMasuk();
			$surat_masuk->setField("ARSIP_ID", $reqArsipId);
			$surat_masuk->setField("ARSIP", $reqArsip);
			$surat_masuk->setField("MEDIA_PENGIRIMAN_ID", $reqMediaPengiriman);
			$surat_masuk->setField("STATUS_SURAT", $reqStatusSurat);
			$surat_masuk->setField("LAST_UPDATE_USER", $this->ID);
			$surat_masuk->setField("SURAT_MASUK_ID", $reqId);
			$surat_masuk->kirimSuratKeluar();
			
			echo "1-Naskah berhasil diproses.";
			
		}
					
		
	}
	
	
	function posting_surat_nomor_tu()
	{

		$reqId		=  $this->input->post("reqId");
		$reqArsip	=  $this->input->post("reqArsip");
		$reqArsipId	=  $this->input->post("reqArsipId");
		$reqMediaPengiriman	=  $this->input->post("reqMediaPengiriman");
		$reqJenisTTD	=  $this->input->post("reqJenisTTD");
		
		$this->load->model("SuratMasuk");
		/* WAJIB UNTUK UPLOAD DATA */
		$this->load->library("FileHandler");
		$file = new FileHandler();
		$FILE_DIR= "uploads/".$reqId."/";
		makedirs($FILE_DIR);
		
		$reqLinkFileNaskah 		= $_FILES["reqLinkFileNaskah"];
		$reqLinkFileNaskahTemp	=  $this->input->post("reqLinkFileNaskahTemp");

  
		if($reqLinkFileNaskah["name"] == "" && $reqLinkFileNaskahTemp == "" && $reqJenisTTD == "BASAH")
		{
			echo "0-Upload naskah yang sudah ditandatangani terlebih dahulu.";
			return;
		}
		else
		{
			if($reqJenisTTD == "BASAH")
			{
				$reqJenis = "NASKAHTTD".generateZero($reqId, 5);
				$renameFileNaskah = $reqJenis.date("Ymdhis").rand().".".getExtension($reqLinkFileNaskah['name']);
				
				if($file->uploadToDir('reqLinkFileNaskah', $FILE_DIR, $renameFileNaskah))
					$insertLinkFileNaskah =  $renameFileNaskah;
				else
					$insertLinkFileNaskah =  $reqLinkFileNaskahTemp;								
					
				/*  UPDATE KE SURAT_PDF */	
				$surat_pdf = new SuratMasuk();
				$surat_pdf->setField("FIELD", "SURAT_PDF");
				$surat_pdf->setField("FIELD_VALUE", $insertLinkFileNaskah);
				$surat_pdf->setField("LAST_UPDATE_USER", $this->ID);
				$surat_pdf->setField("SURAT_MASUK_ID", $reqId);
				$surat_pdf->updateByField();
				
			}
			else
			{
				

		
				/* SISIPKAN GENERATE QRCODE PENANDA TANGAN ASLI APABILA POSTING */
				$surat_masuk = new SuratMasuk();
				$kodeParaf  = $surat_masuk->getTtdSurat(array("A.SURAT_MASUK_ID" => $reqId));
				include_once("libraries/phpqrcode/qrlib.php");
		
				$FILE_DIR= "uploads/".$reqId."/";
				makedirs($FILE_DIR);
				$filename = $FILE_DIR.$kodeParaf.'.png';
				$errorCorrectionLevel = 'L';   
				$matrixPointSize = 5;
				QRcode::png($kodeParaf, $filename, $errorCorrectionLevel, $matrixPointSize, 2);    
				/* END OF GENERATE QRCODE */	 
					
				/*  UPDATE KE SURAT_PDF */	
				$surat_pdf = new SuratMasuk();
				$surat_pdf->setField("FIELD", "TTD_KODE");
				$surat_pdf->setField("FIELD_VALUE", $kodeParaf);
				$surat_pdf->setField("LAST_UPDATE_USER", $this->ID);
				$surat_pdf->setField("SURAT_MASUK_ID", $reqId);
				$surat_pdf->updateByField();
				
								
			}
			
			$reqStatusSurat = "POSTING";
			
			/* UPDATE PENGIRIMAN */
			$surat_masuk = new SuratMasuk();
			$surat_masuk->setField("ARSIP_ID", $reqArsipId);
			$surat_masuk->setField("ARSIP", $reqArsip);
			$surat_masuk->setField("MEDIA_PENGIRIMAN_ID", $reqMediaPengiriman);
			$surat_masuk->setField("STATUS_SURAT", $reqStatusSurat);
			$surat_masuk->setField("LAST_UPDATE_USER", $this->ID);
			$surat_masuk->setField("SURAT_MASUK_ID", $reqId);
			$surat_masuk->kirimSuratKeluar();
			
			echo "1-Naskah berhasil diproses.";
			
		}
					
		
	}
	
	
	function posting_surat_masuk_tu()
	{

		$reqId		=  $this->input->post("reqId");
		$reqArsip	=  $this->input->post("reqArsip");
		$reqArsipId	=  $this->input->post("reqArsipId");
		
		$this->load->model("SuratMasuk");
		
		
		/* UPDATE PENGIRIMAN */
		$surat_masuk = new SuratMasuk();
		$surat_masuk->setField("ARSIP_TU_ID", $reqArsipId);
		$surat_masuk->setField("ARSIP_TU", $reqArsip);
		$surat_masuk->setField("CABANG_ID", $this->CABANG_ID);
		$surat_masuk->setField("ARSIP_BY", $this->ID);
		$surat_masuk->setField("SURAT_MASUK_ID", $reqId);
		$surat_masuk->teruskanSuratMasuk();
		echo "Naskah berhasil diteruskan.";
			
	}
	
	
}

