<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class surat_masuk_json extends CI_Controller
{
	var $calendarId = 'cohu8p4q74iks6dpilk7mrpvi4@group.calendar.google.com';

	function __construct()
	{
		parent::__construct();

		$reqToken = $this->input->get("reqToken");
		if ($reqToken == "") {
			$reqToken = $this->input->post("reqToken");
		}

		if (!empty($reqToken)) {
			$this->load->model('UserLoginMobile');
			$user_login_mobile = new UserLoginMobile();
			$reqPegawaiId = $user_login_mobile->getTokenPegawaiId(array("TOKEN" => $reqToken, "STATUS" => '1'));

			if ($reqPegawaiId == "0") {
				$arrReturn = array('status' => 'fail', 'message' => 'Sesi anda telah berakhir', 'code' => 502);
				echo json_encode($arrReturn);
				return;
			}

			$this->kauth->mobileVerification($reqPegawaiId, $reqToken);
			$this->CALLER = "MOBILE";
		}

		if (!$this->kauth->getInstance()->hasIdentity()) {
			redirect('login');
		}

		$this->db->query("SET DATESTYLE TO PostgreSQL,European;");
		$this->ID					= $this->kauth->getInstance()->getIdentity()->ID;
		$this->NAMA					= $this->kauth->getInstance()->getIdentity()->NAMA;
		$this->JABATAN				= $this->kauth->getInstance()->getIdentity()->JABATAN;
		$this->HAK_AKSES			= $this->kauth->getInstance()->getIdentity()->HAK_AKSES;
		$this->LAST_LOGIN			= $this->kauth->getInstance()->getIdentity()->LAST_LOGIN;
		$this->USERNAME				= $this->kauth->getInstance()->getIdentity()->USERNAME;
		$this->USER_LOGIN_ID		= $this->kauth->getInstance()->getIdentity()->USER_LOGIN_ID;
		$this->USER_GROUP			= $this->kauth->getInstance()->getIdentity()->USER_GROUP;
		$this->MULTIROLE			= $this->kauth->getInstance()->getIdentity()->MULTIROLE;
		$this->CABANG_ID			= $this->kauth->getInstance()->getIdentity()->CABANG_ID;
		$this->CABANG				= $this->kauth->getInstance()->getIdentity()->CABANG;
		$this->SATUAN_KERJA_ID_ASAL	= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_ID_ASAL;
		$this->SATUAN_KERJA_ASAL	= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_ASAL;
		$this->SATUAN_KERJA_HIRARKI	= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_HIRARKI;
		$this->SATUAN_KERJA_JABATAN	= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_JABATAN;
		$this->KD_LEVEL				= $this->kauth->getInstance()->getIdentity()->KD_LEVEL;
		$this->KD_LEVEL_PEJABAT 	= $this->kauth->getInstance()->getIdentity()->KD_LEVEL_PEJABAT;
		$this->JENIS_KELAMIN 		= $this->kauth->getInstance()->getIdentity()->JENIS_KELAMIN;
		$this->KELOMPOK_JABATAN 	= $this->kauth->getInstance()->getIdentity()->KELOMPOK_JABATAN;
		$this->KODE_PARENT= $this->kauth->getInstance()->getIdentity()->KODE_PARENT;
		$this->ID_ATASAN 			= $this->kauth->getInstance()->getIdentity()->ID_ATASAN;
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

		$statement_privacy .= " AND (A.USER_ATASAN_ID = '" . $this->ID . "' OR A.USER_ATASAN_ID = '" . $this->USER_GROUP . $this->ID . "') ";

		$statement_privacy .= " AND A.STATUS_SURAT IN ('VALIDASI', 'PARAF') ";


		$statement = " AND (
						UPPER(A.NO_AGENDA) LIKE '%" . strtoupper($_GET['sSearch']) . "%' OR 
						UPPER(A.NOMOR) LIKE '%" . strtoupper($_GET['sSearch']) . "%' OR
						UPPER(A.PERIHAL) LIKE '%" . strtoupper($_GET['sSearch']) . "%' OR 
						UPPER(A.INSTANSI_ASAL) LIKE '%" . strtoupper($_GET['sSearch']) . "%' 
						) ";

		$rowCount = $surat_masuk->getCountByParamsMonitoringApproval(array(), $statement_privacy . $statement);

		$dsplyStart = !empty($reqPage) ? $reqPage : 0;
		$dsplyRange = $reqShow;

		//initialize pagination class
		$pagConfig = array('baseURL' => 'web/surat_masuk_json/approval', 'showRecord' => $reqShow, 'totalRows' => $rowCount, 'currentPage' => $dsplyStart, 'perPage' => $dsplyRange, 'contentDiv' => $reqContent, 'searchText' => $reqPencarian, 'arrSerialized' => $this->input->post("array_serialized"));
		$pagination =  new Pagination($pagConfig);

		if ($rowCount > 0) {
			$surat_masuk->selectByParamsMonitoringApproval(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement);
			while ($surat_masuk->nextRow()) {
				$reqId = $surat_masuk->getField("SURAT_MASUK_ID");
				$reqTerbaca = $surat_masuk->getField("TERBACA_VALIDASI");
				$reqJenisSurat = $surat_masuk->getField("JENIS_SURAT");
?>

				<div class="list terbaca<?= (int)$reqTerbaca ?>" id="divSurat<?= $reqId ?>">
					<a onDblClick="openNav('<?= $reqId ?>', '<?= $reqJenisSurat ?>')">
						<div class="avatar">
							<?= generateFoto("X", $surat_masuk->getField("KEPADA")) ?>
						</div>
						<div class="pengirim"><?= truncate($surat_masuk->getField("KEPADA"), 5) ?></div>
						<div class="isi"><span class="judul"><?= truncate($surat_masuk->getField("PERIHAL") . " - " . $surat_masuk->getField("ISI"), 12) ?>....</span>
							<div class="atribut">
								<span><i class="fa fa-tags"></i> <?= $surat_masuk->getField("JENIS_NASKAH") ?></span>
								<span><i class="fa fa-exclamation-triangle"></i> <?= $surat_masuk->getField("SIFAT_NASKAH") ?></span>
								<!--<span><i class="fa fa-exclamation-triangle"></i> <?= $surat_masuk->getField("PRIORITAS_SURAT") ?></span>-->
							</div>
						</div>
						<div class="tanggal-info">
							<span class="tanggal"><i class="fa fa-clock-o"></i> <?= $surat_masuk->getField("TANGGAL_ENTRI") ?></span>
						</div>
						<div class="clearfix"></div>
					</a>
				</div>
			<?
			}
			?>

			<div class="area-pagination">
				<?= $pagination->createLinks() ?>
			</div>
			<?
		}
	}



	function inbox()
	{

		$this->load->library("Pagination");
		$this->load->model("SuratMasuk");
		$this->load->model("Disposisi");
		$this->load->model("DisposisiKeluar");
		$surat_masuk = new SuratMasuk();
		$disposisi = new Disposisi();
		$disposisi_keluar = new DisposisiKeluar();

		$reqJenisTujuan = $this->input->get("reqJenisTujuan");
		$reqPage = $this->input->post("page");
		$reqPencarian = $this->input->post("search");
		$reqShow = $this->input->post("show");
		$reqContent = $this->input->post("content");
		$reqArrStatement = unserialized($this->input->post("array_serialized"));

		// $statement_privacy .= " AND A.STATUS_SURAT = 'POSTING' ";
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

		if($this->KD_LEVEL_PEJABAT == ""){
			$statement_privacy .= " AND (B.USER_ID = '".$this->ID."' OR B.USER_ID = '".$this->ID_ATASAN."' ) ";
		}
		else{
			$statement_privacy .= " AND (B.SATUAN_KERJA_ID_TUJUAN = '".$this->SATUAN_KERJA_ID_ASAL."' OR B.USER_ID = '".$this->ID."' OR B.USER_ID_OBSERVER = '".$this->ID."') ";
		}

		// if($this->KD_LEVEL_PEJABAT == "")
		// 	$statement_privacy .= " AND (B.USER_ID = '".$this->ID."' OR B.USER_ID = '".$this->ID_ATASAN."') ";
		// else
		// 	$statement_privacy .= " AND B.SATUAN_KERJA_ID_TUJUAN = '".$this->SATUAN_KERJA_ID_ASAL."' ";

		// $statement= " AND (
		// 				UPPER(A.NO_AGENDA) LIKE '%".strtoupper($_GET['sSearch'])."%' OR 
		// 				UPPER(A.NOMOR) LIKE '%".strtoupper($_GET['sSearch'])."%' OR
		// 				UPPER(A.PERIHAL) LIKE '%".strtoupper($_GET['sSearch'])."%' OR 
		// 				UPPER(A.INSTANSI_ASAL) LIKE '%".strtoupper($_GET['sSearch'])."%' 
		// 				) ";

		$rowCount = $surat_masuk->getCountByParamsInbox(array(), $statement_privacy . $statement);

		$dsplyStart = !empty($reqPage) ? $reqPage : 0;
		$dsplyRange = $reqShow;

		//initialize pagination class
		$pagConfig = array('baseURL' => 'web/surat_masuk_json/inbox', 'showRecord' => $reqShow, 'totalRows' => $rowCount, 'currentPage' => $dsplyStart, 'perPage' => $dsplyRange, 'contentDiv' => $reqContent, 'searchText' => $reqPencarian, 'arrSerialized' => $this->input->post("array_serialized"));
		$pagination =  new Pagination($pagConfig);

		if ($rowCount > 0) {
			$surat_masuk->selectByParamsInbox(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, " ORDER BY A.TANGGAL_ENTRI DESC, B.TERBACA DESC ");

			while ($surat_masuk->nextRow()) {
				$reqId = $surat_masuk->getField("SURAT_MASUK_ID");
				$reqDisposisiId = $surat_masuk->getField("DISPOSISI_ID");
				$reqTerbaca = $surat_masuk->getField("TERBACA");
			?>
				<div class="list terbaca<?= (int)$reqTerbaca ?>" id="divSurat<?= $reqId ?>">
					<!-- // tambahan khusus -->
					<!-- <a onDblClick="openNav('<?= $reqId ?>', '<?= $reqDisposisiId ?>')"> -->
					<a onclick="viewDetil('<?= $reqId ?>', '<?= $reqDisposisiId ?>')">
						<div class="avatar">
							<?= generateFoto("X", $surat_masuk->getField("NAMA_USER_ASAL")) ?>
						</div>
						<div class="asal">
							<?= $surat_masuk->getField("NAMA_USER_ASAL") ?><br>
							<span><?= substr($surat_masuk->getField("NAMA_SATKER_ASAL"), 0, 30) ?><br><?= $surat_masuk->getField("ALAMAT_ASAL") ?></span>
						</div>
						<div class="isi"><span class="judul"><?= truncate($surat_masuk->getField("PERIHAL") . " - " . $surat_masuk->getField("ISI"), 12) ?>....</span>
							<div class="atribut">
								<span><i class="fa fa-tags"></i> <?= $surat_masuk->getField("JENIS_NASKAH") ?></span>
								<span><i class="fa fa-hashtag"></i> <?= $surat_masuk->getField("NOMOR") ?></span>
								<span><i class="fa fa-exclamation-triangle"></i> <?= $surat_masuk->getField("SIFAT_NASKAH") ?></span>
								<!--<span><i class="fa fa-exclamation-triangle"></i> <?= $surat_masuk->getField("PRIORITAS_SURAT") ?></span>-->
							</div>
						</div>
						<div class="tanggal-info">
							<span class="tanggal"><i class="fa fa-clock-o"></i> <?= $surat_masuk->getField("TANGGAL_ENTRI") ?></span>
						</div>
						<div class="clearfix"></div>
					</a>
				</div>
			<?
			}
			?>
			<script type="text/javascript">
				$(document).ready(function() {
					$('.ssss').empty();
				});
			</script>

			<div class="area-pagination">
				<?= $pagination->createLinks() ?>
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

		$statement_privacy .= " AND (A.USER_ATASAN_ID = '" . $this->ID_ATASAN . "' OR A.USER_ID = '" . $this->ID_ATASAN . "' OR A.USER_ATASAN_ID = '" . $this->ID . "' OR A.USER_ID = '" . $this->ID . "' OR EXISTS(SELECT 1 FROM SURAT_MASUK_PARAF X WHERE X.SURAT_MASUK_ID = A.SURAT_MASUK_ID AND X.USER_ID = '" . $this->ID . "')) ";
		$statement_privacy .= " AND A.STATUS_SURAT IN ('POSTING') ";

		$statement = " AND (
						UPPER(A.NO_AGENDA) LIKE '%" . strtoupper($_GET['sSearch']) . "%' OR 
						UPPER(A.NOMOR) LIKE '%" . strtoupper($_GET['sSearch']) . "%' OR
						UPPER(A.PERIHAL) LIKE '%" . strtoupper($_GET['sSearch']) . "%' OR 
						UPPER(A.INSTANSI_ASAL) LIKE '%" . strtoupper($_GET['sSearch']) . "%' 
						) ";





		$rowCount = $surat_masuk->getCountByParamsMonitoringDraft(array(), $statement_privacy . $statement);

		$dsplyStart = !empty($reqPage) ? $reqPage : 0;
		$dsplyRange = $reqShow;

		//initialize pagination class
		$pagConfig = array('baseURL' => 'web/surat_masuk_json/sent', 'showRecord' => $reqShow, 'totalRows' => $rowCount, 'currentPage' => $dsplyStart, 'perPage' => $dsplyRange, 'contentDiv' => $reqContent, 'searchText' => $reqPencarian, 'arrSerialized' => $this->input->post("array_serialized"));
		$pagination =  new Pagination($pagConfig);

		if ($rowCount > 0) {
			$surat_masuk->selectByParamsMonitoringSent(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement);
			while ($surat_masuk->nextRow()) {
				$reqId = $surat_masuk->getField("SURAT_MASUK_ID");
				$reqTerbaca = $surat_masuk->getField("TERBACA_VALIDASI");
				$reqJenisSurat = $surat_masuk->getField("JENIS_SURAT");
			?>

				<div class="list terbaca" id="divSurat<?= $reqId ?>">
					<a onDblClick="openNav('<?= $reqId ?>', '<?= $reqJenisSurat ?>')">
						<div class="avatar">
							<?= generateFoto("X", $surat_masuk->getField("KEPADA")) ?>
						</div>
						<div class="pengirim"><?= truncate($surat_masuk->getField("KEPADA"), 5) ?></div>
						<div class="isi"><span class="judul"><strong><?= truncate($surat_masuk->getField("NOMOR") . " - " . $surat_masuk->getField("PERIHAL"), 15) ?></strong></span>
							<div class="data-tambahan-sent tutupsurat">
								<div class="statussurat"><i class="fa fa-eye" title="Dibaca"></i>
									<?= statusCentang($surat_masuk->getField("TERBACA")) ?>
								</div>
								<div class="statussurat"><i class="fa fa-pencil-square-o" title="Didisposisikan"></i>
									<?= statusCentang($surat_masuk->getField("TERDISPOSISI")) ?>
								</div>
								<div class="statussurat"><i class="fa fa-mail-reply-all" title="Dibalas"></i>
									<?= statusCentang($surat_masuk->getField("TERBALAS")) ?>
								</div>
								<div class="statussurat"><i class="fa fa-mail-forward" title="Diteruskan"></i>
									<?= statusCentang($surat_masuk->getField("TERUSKAN")) ?>
								</div>
							</div>
						</div>
						<div class="tanggal-info">
							<span class="tanggal tutupsurat"><i class="fa fa-clock-o"></i> <?= $surat_masuk->getField("TANGGAL_ENTRI") ?></span>
						</div>
						<div class="clearfix"></div>
					</a>
				</div>
			<?
			}
			?>

			<div class="area-pagination">
				<?= $pagination->createLinks() ?>
			</div>
			<?
		}
	}


	function draft()
	{

		$this->load->library("Pagination");
		$this->load->model("SuratMasuk");
		$this->load->model("Disposisi");
		$this->load->model("DisposisiKeluar");

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

		$statement_privacy .= " AND A.USER_ID = '" . $this->ID . "' ";
		$statement_privacy .= " AND A.STATUS_SURAT IN ('PARAF', 'DRAFT', 'VALIDASI', 'REVISI') ";


		$statement = " AND (
						UPPER(A.NO_AGENDA) LIKE '%" . strtoupper($_GET['sSearch']) . "%' OR 
						UPPER(A.NOMOR) LIKE '%" . strtoupper($_GET['sSearch']) . "%' OR
						UPPER(A.PERIHAL) LIKE '%" . strtoupper($_GET['sSearch']) . "%' OR 
						UPPER(A.INSTANSI_ASAL) LIKE '%" . strtoupper($_GET['sSearch']) . "%' 
						) ";


		$rowCount = $surat_masuk->getCountByParamsMonitoringDraft(array(), $statement_privacy . $statement);

		$dsplyStart = !empty($reqPage) ? $reqPage : 0;
		$dsplyRange = $reqShow;

		//initialize pagination class
		$pagConfig = array('baseURL' => 'web/surat_masuk_json/draft', 'showRecord' => $reqShow, 'totalRows' => $rowCount, 'currentPage' => $dsplyStart, 'perPage' => $dsplyRange, 'contentDiv' => $reqContent, 'searchText' => $reqPencarian, 'arrSerialized' => $this->input->post("array_serialized"));
		$pagination =  new Pagination($pagConfig);

		if ($rowCount > 0) {
			$surat_masuk->selectByParamsMonitoringDraft(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement);
			while ($surat_masuk->nextRow()) {
				$reqId = $surat_masuk->getField("SURAT_MASUK_ID");
				$reqJenisSurat = $surat_masuk->getField("JENIS_SURAT");

				$linkUbah = "surat_masuk_add";

				if ($reqJenisSurat == "EKSTERNAL")
					$linkUbah = "surat_keluar_add";
			?>

				<div class="list">
					<?php /*?><a onDblClick="document.location.href='app/loadUrl/app/<?=$linkUbah?>/?reqId=<?=$reqId?>'"><?php */ ?>
					<a onDblClick="document.location.href='app/loadUrl/main/<?= $linkUbah ?>/?reqId=<?= $reqId ?>'">
						<div class="status" style="display: inherit !important; text-align: center">

							<?
							if ($surat_masuk->getField("STATUS_SURAT") == "VALIDASI") {
							?>
								<span class="fa fa-paper-plane" style="color:#000; font-size:15px"></span>
								<span style="display:inline-block; width: 100%;"> kirim</span>
							<?
							} elseif ($surat_masuk->getField("STATUS_SURAT") == "REVISI") {
							?>
								<span class="fa fa-edit" style="color:#F05154; font-size:15px"></span>
								<span style="display:inline-block; width: 100%;">revisi</span>
							<?
							} elseif ($surat_masuk->getField("STATUS_SURAT") == "PARAF") {
							?>

								<span class="fa fa-pencil" style="color:#000; font-size:15px"></span>
								<span style="display:inline-block; width: 100%;">paraf</span>
							<?
							} else {
							?>
								<span class="fa fa-file-o" style="color:#000; font-size:15px"></span>
								<span style="display:inline-block; width: 100%;">draft</span>

							<?
							}
							?>
						</div>
						<div class="pengirim"><?= truncate($surat_masuk->getField("KEPADA"), 5) ?></div>
						<div class="isi"><span class="judul"><?= $surat_masuk->getField("PERIHAL") ?></span> - <?= truncate($surat_masuk->getField("ISI"), 25) ?>
							<div class="atribut">
								<span><i class="fa fa-tags"></i> <?= $surat_masuk->getField("JENIS_NASKAH") ?></span>
								<span><i class="fa fa-exclamation-triangle"></i> <?= $surat_masuk->getField("SIFAT_NASKAH") ?></span>
								<!--<span><i class="fa fa-exclamation-triangle"></i> <?= $surat_masuk->getField("PRIORITAS_SURAT") ?></span>-->
							</div>
						</div>
						<div class="tanggal-info">
							<span><i class="fa fa-clock-o"></i> <?= $surat_masuk->getField("TANGGAL_ENTRI") ?></span>
						</div>
						<div class="clearfix"></div>
					</a>
				</div>
			<?
			}
			?>

			<div class="area-pagination">
				<?= $pagination->createLinks() ?>
			</div>
<?
		}
	}

	function newdraft()
	{

		$this->load->library("Pagination");
		$this->load->model("SuratMasuk");
		$this->load->model("Disposisi");
		$this->load->model("DisposisiKeluar");

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
        AND A.JENIS_NASKAH_ID NOT IN (1) ";
		// $statement_privacy .= " AND A.STATUS_SURAT IN ('PARAF', 'DRAFT', 'VALIDASI', 'REVISI') ";
		$statement_privacy .= " AND A.STATUS_SURAT IN ('DRAFT', 'REVISI') ";


		$statement = " AND (
						UPPER(A.NO_AGENDA) LIKE '%" . strtoupper($_GET['sSearch']) . "%' OR 
						UPPER(A.NOMOR) LIKE '%" . strtoupper($_GET['sSearch']) . "%' OR
						UPPER(A.PERIHAL) LIKE '%" . strtoupper($_GET['sSearch']) . "%' OR 
						UPPER(A.INSTANSI_ASAL) LIKE '%" . strtoupper($_GET['sSearch']) . "%' 
						) ";


		$rowCount = $surat_masuk->getCountByParamsMonitoringDraft(array(), $statement_privacy . $statement);

		$dsplyStart = !empty($reqPage) ? $reqPage : 0;
		$dsplyRange = $reqShow;

		//initialize pagination class
		$pagConfig = array('baseURL' => 'web/surat_masuk_json/newdraft', 'showRecord' => $reqShow, 'totalRows' => $rowCount, 'currentPage' => $dsplyStart, 'perPage' => $dsplyRange, 'contentDiv' => $reqContent, 'searchText' => $reqPencarian, 'arrSerialized' => $this->input->post("array_serialized"));
		$pagination =  new Pagination($pagConfig);

		if ($rowCount > 0) {
			$sOrder=" ORDER BY TANGGAL_ENTRI::TIMESTAMP DESC ";
			$surat_masuk->selectByParamsMonitoringDraft(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);
			while ($surat_masuk->nextRow()) {
				$reqId = $surat_masuk->getField("SURAT_MASUK_ID");
				$reqJenisSurat = $surat_masuk->getField("JENIS_SURAT");
				$reqJenisNaskahId= $surat_masuk->getField("JENIS_NASKAH_ID");

				$linkUbah= getJenisNaskah($reqJenisNaskahId);

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
						$linkUbah = "draft_pdf";
				}*/
			?>

				<div class="list">
					<?php /*?><a onDblClick="document.location.href='app/loadUrl/app/<?=$linkUbah?>/?reqId=<?=$reqId?>'"><?php */ ?>
					<a onClick="document.location.href='app/loadUrl/main/<?= $linkUbah ?>/?reqId=<?= $reqId ?>'">
						<div class="status" style="display: inherit !important; text-align: center">

							<?
							if ($surat_masuk->getField("STATUS_SURAT") == "VALIDASI") {
							?>
								<span class="fa fa-paper-plane" style="color:#000; font-size:15px"></span>
								<span style="display:inline-block; width: 100%;"> kirim</span>
							<?
							} elseif ($surat_masuk->getField("STATUS_SURAT") == "REVISI") {
							?>
								<span class="fa fa-edit" style="color:#F05154; font-size:15px"></span>
								<span style="display:inline-block; width: 100%;">revisi</span>
							<?
							} elseif ($surat_masuk->getField("STATUS_SURAT") == "PARAF") {
							?>

								<span class="fa fa-pencil" style="color:#000; font-size:15px"></span>
								<span style="display:inline-block; width: 100%;">paraf</span>
							<?
							} else {
							?>
								<span class="fa fa-file-o" style="color:#000; font-size:15px"></span>
								<span style="display:inline-block; width: 100%;">draft</span>

							<?
							}
							?>
						</div>
						<div class="pengirim"><?= truncate($surat_masuk->getField("KEPADA"), 5) ?></div>
						<div class="isi">
							<!-- <span class="judul"><?= $surat_masuk->getField("PERIHAL") ?></span> - <?= truncate($surat_masuk->getField("ISI"), 25) ?> -->
							<span class="judul"><?=$surat_masuk->getField("PERIHAL")?></span>
							<div class="atribut">
								<span><i class="fa fa-tags"></i> <?= $surat_masuk->getField("JENIS_NASKAH") ?></span>
								<span><i class="fa fa-exclamation-triangle"></i> <?= $surat_masuk->getField("SIFAT_NASKAH") ?></span>
								<!--<span><i class="fa fa-exclamation-triangle"></i> <?= $surat_masuk->getField("PRIORITAS_SURAT") ?></span>-->
							</div>
						</div>
						<div class="tanggal-info">
							<span><i class="fa fa-clock-o"></i> <?= $surat_masuk->getField("TANGGAL_ENTRI") ?></span>
						</div>
						<div class="clearfix"></div>
					</a>
				</div>
			<?
			}
			?>

			<div class="area-pagination">
				<?= $pagination->createLinks() ?>
			</div>
<?
		}
	}

	function newdraftmanual()
	{

		$this->load->library("Pagination");
		$this->load->model("SuratMasuk");
		$this->load->model("Disposisi");
		$this->load->model("DisposisiKeluar");

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


		$statement = " AND (
						UPPER(A.NO_AGENDA) LIKE '%" . strtoupper($_GET['sSearch']) . "%' OR 
						UPPER(A.NOMOR) LIKE '%" . strtoupper($_GET['sSearch']) . "%' OR
						UPPER(A.PERIHAL) LIKE '%" . strtoupper($_GET['sSearch']) . "%' OR 
						UPPER(A.INSTANSI_ASAL) LIKE '%" . strtoupper($_GET['sSearch']) . "%' 
						) ";


		$rowCount = $surat_masuk->getCountByParamsMonitoringDraft(array(), $statement_privacy . $statement);

		$dsplyStart = !empty($reqPage) ? $reqPage : 0;
		$dsplyRange = $reqShow;

		//initialize pagination class
		$pagConfig = array('baseURL' => 'web/surat_masuk_json/newdraftmanual', 'showRecord' => $reqShow, 'totalRows' => $rowCount, 'currentPage' => $dsplyStart, 'perPage' => $dsplyRange, 'contentDiv' => $reqContent, 'searchText' => $reqPencarian, 'arrSerialized' => $this->input->post("array_serialized"));
		$pagination =  new Pagination($pagConfig);

		if ($rowCount > 0) {
			$sOrder=" ORDER BY TANGGAL_ENTRI::TIMESTAMP DESC ";
			$surat_masuk->selectByParamsMonitoringDraft(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);
			while ($surat_masuk->nextRow()) {
				$reqId = $surat_masuk->getField("SURAT_MASUK_ID");
				$reqJenisSurat = $surat_masuk->getField("JENIS_SURAT");
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
						$linkUbah = "draft_pdf";
				}*/
			?>

				<div class="list">
					<?php /*?><a onDblClick="document.location.href='app/loadUrl/app/<?=$linkUbah?>/?reqId=<?=$reqId?>'"><?php */ ?>
					<a onClick="document.location.href='app/loadUrl/main/<?= $linkUbah ?>/?reqId=<?= $reqId ?>'">
						<div class="status" style="display: inherit !important; text-align: center">

							<?
							if ($surat_masuk->getField("STATUS_SURAT") == "VALIDASI") {
							?>
								<span class="fa fa-paper-plane" style="color:#000; font-size:15px"></span>
								<span style="display:inline-block; width: 100%;"> kirim</span>
							<?
							} elseif ($surat_masuk->getField("STATUS_SURAT") == "REVISI") {
							?>
								<span class="fa fa-edit" style="color:#F05154; font-size:15px"></span>
								<span style="display:inline-block; width: 100%;">revisi</span>
							<?
							} elseif ($surat_masuk->getField("STATUS_SURAT") == "PARAF") {
							?>

								<span class="fa fa-pencil" style="color:#000; font-size:15px"></span>
								<span style="display:inline-block; width: 100%;">paraf</span>
							<?
							} else {
							?>
								<span class="fa fa-file-o" style="color:#000; font-size:15px"></span>
								<span style="display:inline-block; width: 100%;">draft</span>

							<?
							}
							?>
						</div>
						<div class="pengirim"><?= truncate($surat_masuk->getField("KEPADA"), 5) ?></div>
						<div class="isi">
							<!-- <span class="judul"><?= $surat_masuk->getField("PERIHAL") ?></span> - <?= truncate($surat_masuk->getField("ISI"), 25) ?> -->
							<span class="judul"><?=$surat_masuk->getField("PERIHAL")?></span>
							<div class="atribut">
								<span><i class="fa fa-tags"></i> <?= $surat_masuk->getField("JENIS_NASKAH") ?></span>
								<span><i class="fa fa-exclamation-triangle"></i> <?= $surat_masuk->getField("SIFAT_NASKAH") ?></span>
								<!--<span><i class="fa fa-exclamation-triangle"></i> <?= $surat_masuk->getField("PRIORITAS_SURAT") ?></span>-->
							</div>
						</div>
						<div class="tanggal-info">
							<span><i class="fa fa-clock-o"></i> <?= $surat_masuk->getField("TANGGAL_ENTRI") ?></span>
						</div>
						<div class="clearfix"></div>
					</a>
				</div>
			<?
			}
			?>

			<div class="area-pagination">
				<?= $pagination->createLinks() ?>
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

		$aColumns = array(
			"SURAT_MASUK_ID", "STATUS_SURAT", "NOMOR", "NO_AGENDA", "TANGGAL_ENTRI", "TANGGAL",
			"JENIS_NASKAH", "PERIHAL", "SIFAT_NASKAH",
			"KEPADA", "TEMBUSAN", "INSTANSI_ASAL", "TERBACA", "TERDISPOSISI", "TERBALAS", "USER_ID"
		);
		$aColumnsAlias = array(
			"A.SURAT_MASUK_ID", "A.STATUS_SURAT", "A.NOMOR", "A.NO_AGENDA", "A.TANGGAL_ENTRI", "A.TANGGAL",
			"JENIS_NASKAH_ID", "PERIHAL", "SIFAT_NASKAH",
			"PERIHAL", "PERIHAL", "INSTANSI_ASAL", "TERBALAS", "TERDISPOSISI", "TERBALAS", "USER_ID"
		);

		/*
		 * Ordering
		 */
		if (isset($_GET['iSortCol_0'])) {
			$sOrder = " ORDER BY ";

			//Go over all sorting cols
			for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
				//If need to sort by current col
				if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
					//Add to the order by clause
					$sOrder .= $aColumnsAlias[intval($_GET['iSortCol_' . $i])];

					//Determine if it is sorted asc or desc
					if (strcasecmp(($_GET['sSortDir_' . $i]), "asc") == 0) {
						$sOrder .= " asc, ";
					} else {
						$sOrder .= " desc, ";
					}
				}
			}

			//Remove the last space / comma
			$sOrder = substr_replace($sOrder, "", -2);

			//Check if there is an order by clause
			if (trim($sOrder) == "ORDER BY A.SURAT_MASUK_ID asc") {
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
		if (isset($_GET['sSearch'])) {
			$sWhereGenearal = $_GET['sSearch'];
		} else {
			$sWhereGenearal = '';
		}

		if ($_GET['sSearch'] != "") {
			//Set a default where clause in order for the where clause not to fail
			//in cases where there are no searchable cols at all.
			$sWhere = " AND (";
			for ($i = 0; $i < count($aColumnsAlias) + 1; $i++) {
				//If current col has a search param
				if ($_GET['bSearchable_' . $i] == "true") {
					//Add the search to the where clause
					$sWhere .= $aColumnsAlias[$i] . " LIKE '%" . $_GET['sSearch'] . "%' OR ";
					$nWhereGenearalCount += 1;
				}
			}
			$sWhere = substr_replace($sWhere, "", -3);
			$sWhere .= ')';
		}

		/* Individual column filtering */
		$sWhereSpecificArray = array();
		$sWhereSpecificArrayCount = 0;
		for ($i = 0; $i < count($aColumnsAlias); $i++) {
			if ($_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {
				//If there was no where clause
				if ($sWhere == "") {
					$sWhere = "AND ";
				} else {
					$sWhere .= " AND ";
				}

				//Add the clause of the specific col to the where clause
				$sWhere .= $aColumnsAlias[$i] . " LIKE '%' || :whereSpecificParam" . $sWhereSpecificArrayCount . " || '%' ";

				//Inc sWhereSpecificArrayCount. It is needed for the bind var.
				//We could just do count($sWhereSpecificArray) - but that would be less efficient.
				$sWhereSpecificArrayCount++;

				//Add current search param to the array for later use (binding).
				$sWhereSpecificArray[] =  $_GET['sSearch_' . $i];
			}
		}

		//If there is still no where clause - set a general - always true where clause
		if ($sWhere == "") {
			$sWhere = " AND 1=1";
		}

		//Bind variables.
		if (isset($_GET['iDisplayStart'])) {
			$dsplyStart = $_GET['iDisplayStart'];
		} else {
			$dsplyStart = 0;
		}
		if (isset($_GET['iDisplayLength']) && $_GET['iDisplayLength'] != '-1') {
			$dsplyRange = $_GET['iDisplayLength'];
			if ($dsplyRange > (2147483645 - intval($dsplyStart))) {
				$dsplyRange = 2147483645;
			} else {
				$dsplyRange = intval($dsplyRange);
			}
		} else {
			$dsplyRange = 2147483645;
		}


		$statement_privacy .= " AND A.JENIS_TUJUAN = '" . $reqJenisTujuan . "' ";
		//$statement_privacy .= " AND A.SATUAN_KERJA_ID_ASAL = '".$this->SATUAN_KERJA_ID_ASAL."' ";

		if ($this->USER_GROUP == "SEKRETARIS")
			$statement_privacy .= " AND A.PENERIMA_SURAT = '" . $this->SATUAN_KERJA_ID_ASAL . "' ";
		elseif ($this->USER_GROUP == "TATAUSAHA")
			$statement_privacy .= " AND A.PENERIMA_SURAT = '" . $this->CABANG_ID . "' ";
		else
			exit;

		$statement = " AND (
						UPPER(A.NO_AGENDA) LIKE '%" . strtoupper($_GET['sSearch']) . "%' OR 
						UPPER(A.NOMOR) LIKE '%" . strtoupper($_GET['sSearch']) . "%' OR
						UPPER(A.PERIHAL) LIKE '%" . strtoupper($_GET['sSearch']) . "%' OR 
						UPPER(A.INSTANSI_ASAL) LIKE '%" . strtoupper($_GET['sSearch']) . "%' 
						) ";

		$allRecord = $surat_masuk->getCountByParamsMonitoring(array(), $statement_privacy . $statement);
		// echo $allRecord;exit;
		if ($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else
			$allRecordFilter =  $surat_masuk->getCountByParamsMonitoring(array(), $statement_privacy . $statement);

		$surat_masuk->selectByParamsMonitoring(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);

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

		while ($surat_masuk->nextRow()) {
			$row = array();
			for ($i = 0; $i < count($aColumns); $i++) {
				if ($aColumns[$i] == "KETERANGAN")
					$row[] = truncate($surat_masuk->getField($aColumns[$i]), 2);
				elseif ($aColumns[$i] == "TERBACA" || $aColumns[$i] == "TERDISPOSISI" || $aColumns[$i] == "TERBALAS") {
					if ((int)$surat_masuk->getField($aColumns[$i]) > 0)
						$row[] = "<i class=\"fa fa-check fa-md\" aria-hidden=\"true\"></i>";
					else
						$row[] = "<i class=\"fa fa-close fa-md\" aria-hidden=\"true\"></i>";
				} else
					$row[] = $surat_masuk->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		echo json_encode($output);
	}


	function json_surat_keluar_tu()
	{
		$this->load->model("SuratMasuk");
		$surat_masuk = new SuratMasuk();

		$aColumns = array(
			"SURAT_MASUK_ID", "STATUS_SURAT", "NOMOR", "PERIHAL", "TANGGAL_ENTRI", "KEPADA", "INSTANSI_ASAL",
			"JENIS_NASKAH", "SIFAT_NASKAH", "USER_ID"
		);
		$aColumnsAlias = array(
			"SURAT_MASUK_ID", "STATUS_SURAT", "NOMOR", "PERIHAL", "TANGGAL_ENTRI", "KEPADA", "INSTANSI_ASAL",
			"JENIS_NASKAH", "SIFAT_NASKAH", "USER_ID"
		);

		/*
		 * Ordering
		 */
		if (isset($_GET['iSortCol_0'])) {
			$sOrder = " ORDER BY ";

			//Go over all sorting cols
			for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
				//If need to sort by current col
				if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
					//Add to the order by clause
					$sOrder .= $aColumnsAlias[intval($_GET['iSortCol_' . $i])];

					//Determine if it is sorted asc or desc
					if (strcasecmp(($_GET['sSortDir_' . $i]), "asc") == 0) {
						$sOrder .= " asc, ";
					} else {
						$sOrder .= " desc, ";
					}
				}
			}

			//Remove the last space / comma
			$sOrder = substr_replace($sOrder, "", -2);

			//Check if there is an order by clause
			if (trim($sOrder) == "ORDER BY A.TANGGAL_ENTRI DESC") {
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
		if (isset($_GET['sSearch'])) {
			$sWhereGenearal = $_GET['sSearch'];
		} else {
			$sWhereGenearal = '';
		}

		if ($_GET['sSearch'] != "") {
			//Set a default where clause in order for the where clause not to fail
			//in cases where there are no searchable cols at all.
			$sWhere = " AND (";
			for ($i = 0; $i < count($aColumnsAlias) + 1; $i++) {
				//If current col has a search param
				if ($_GET['bSearchable_' . $i] == "true") {
					//Add the search to the where clause
					$sWhere .= $aColumnsAlias[$i] . " LIKE '%" . $_GET['sSearch'] . "%' OR ";
					$nWhereGenearalCount += 1;
				}
			}
			$sWhere = substr_replace($sWhere, "", -3);
			$sWhere .= ')';
		}

		/* Individual column filtering */
		$sWhereSpecificArray = array();
		$sWhereSpecificArrayCount = 0;
		for ($i = 0; $i < count($aColumnsAlias); $i++) {
			if ($_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {
				//If there was no where clause
				if ($sWhere == "") {
					$sWhere = "AND ";
				} else {
					$sWhere .= " AND ";
				}

				//Add the clause of the specific col to the where clause
				$sWhere .= $aColumnsAlias[$i] . " LIKE '%' || :whereSpecificParam" . $sWhereSpecificArrayCount . " || '%' ";

				//Inc sWhereSpecificArrayCount. It is needed for the bind var.
				//We could just do count($sWhereSpecificArray) - but that would be less efficient.
				$sWhereSpecificArrayCount++;

				//Add current search param to the array for later use (binding).
				$sWhereSpecificArray[] =  $_GET['sSearch_' . $i];
			}
		}

		//If there is still no where clause - set a general - always true where clause
		if ($sWhere == "") {
			$sWhere = " AND 1=1";
		}

		//Bind variables.
		if (isset($_GET['iDisplayStart'])) {
			$dsplyStart = $_GET['iDisplayStart'];
		} else {
			$dsplyStart = 0;
		}
		if (isset($_GET['iDisplayLength']) && $_GET['iDisplayLength'] != '-1') {
			$dsplyRange = $_GET['iDisplayLength'];
			if ($dsplyRange > (2147483645 - intval($dsplyStart))) {
				$dsplyRange = 2147483645;
			} else {
				$dsplyRange = intval($dsplyRange);
			}
		} else {
			$dsplyRange = 2147483645;
		}


		$statement_privacy .= " AND A.STATUS_SURAT IN ('TU-OUT', 'TU-NOMOR') ";
		//$statement_privacy .= " AND A.SATUAN_KERJA_ID_ASAL = '".$this->SATUAN_KERJA_ID_ASAL."' ";
		if ($this->USER_GROUP == "TATAUSAHA")
			$statement_privacy .= " AND A.CABANG_ID = '" . $this->CABANG_ID . "' ";
		else
			exit;

		$statement = " AND (
						UPPER(A.NO_AGENDA) LIKE '%" . strtoupper($_GET['sSearch']) . "%' OR 
						UPPER(A.NOMOR) LIKE '%" . strtoupper($_GET['sSearch']) . "%' OR
						UPPER(A.PERIHAL) LIKE '%" . strtoupper($_GET['sSearch']) . "%' OR 
						UPPER(A.INSTANSI_ASAL) LIKE '%" . strtoupper($_GET['sSearch']) . "%' 
						) ";

		$allRecord = $surat_masuk->getCountByParamsMonitoring(array(), $statement_privacy . $statement);
		// echo $allRecord;exit;
		if ($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else
			$allRecordFilter =  $surat_masuk->getCountByParamsMonitoring(array(), $statement_privacy . $statement);

		$surat_masuk->selectByParamsMonitoring(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, " ORDER BY A.TANGGAL_ENTRI DESC");

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

		while ($surat_masuk->nextRow()) {
			$row = array();
			for ($i = 0; $i < count($aColumns); $i++) {
				if ($aColumns[$i] == "KETERANGAN")
					$row[] = truncate($surat_masuk->getField($aColumns[$i]), 2);
				elseif ($aColumns[$i] == "TERBACA" || $aColumns[$i] == "TERDISPOSISI" || $aColumns[$i] == "TERBALAS") {
					if ((int)$surat_masuk->getField($aColumns[$i]) > 0)
						$row[] = "<i class=\"fa fa-check fa-md\" aria-hidden=\"true\"></i>";
					else
						$row[] = "<i class=\"fa fa-close fa-md\" aria-hidden=\"true\"></i>";
				} else
					$row[] = $surat_masuk->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		echo json_encode($output);
	}

	function json_surat_masuk_tu()
	{
		$this->load->model("SuratMasuk");
		$surat_masuk = new SuratMasuk();

		$aColumns = array(
			"SURAT_MASUK_ID", "NOMOR", "DARI", "KEPADA", "TANGGAL_ENTRI",
			"PERIHAL", "JENIS_NASKAH", "SIFAT_NASKAH", "USER_ID"
		);
		$aColumnsAlias = array(
			"A.SURAT_MASUK_ID", "A.STATUS_SURAT", "A.NOMOR", "A.TANGGAL_ENTRI",
			"PERIHAL", "JENIS_NASKAH_ID", "SIFAT_NASKAH",
			"USER_ID"
		);

		/*
		 * Ordering
		 */
		if (isset($_GET['iSortCol_0'])) {
			$sOrder = " ORDER BY ";

			//Go over all sorting cols
			for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
				//If need to sort by current col
				if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
					//Add to the order by clause
					$sOrder .= $aColumnsAlias[intval($_GET['iSortCol_' . $i])];

					//Determine if it is sorted asc or desc
					if (strcasecmp(($_GET['sSortDir_' . $i]), "asc") == 0) {
						$sOrder .= " asc, ";
					} else {
						$sOrder .= " desc, ";
					}
				}
			}

			//Remove the last space / comma
			$sOrder = substr_replace($sOrder, "", -2);

			//Check if there is an order by clause
			if (trim($sOrder) == "ORDER BY A.SURAT_MASUK_ID asc") {
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
		if (isset($_GET['sSearch'])) {
			$sWhereGenearal = $_GET['sSearch'];
		} else {
			$sWhereGenearal = '';
		}

		if ($_GET['sSearch'] != "") {
			//Set a default where clause in order for the where clause not to fail
			//in cases where there are no searchable cols at all.
			$sWhere = " AND (";
			for ($i = 0; $i < count($aColumnsAlias) + 1; $i++) {
				//If current col has a search param
				if ($_GET['bSearchable_' . $i] == "true") {
					//Add the search to the where clause
					$sWhere .= $aColumnsAlias[$i] . " LIKE '%" . $_GET['sSearch'] . "%' OR ";
					$nWhereGenearalCount += 1;
				}
			}
			$sWhere = substr_replace($sWhere, "", -3);
			$sWhere .= ')';
		}

		/* Individual column filtering */
		$sWhereSpecificArray = array();
		$sWhereSpecificArrayCount = 0;
		for ($i = 0; $i < count($aColumnsAlias); $i++) {
			if ($_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {
				//If there was no where clause
				if ($sWhere == "") {
					$sWhere = "AND ";
				} else {
					$sWhere .= " AND ";
				}

				//Add the clause of the specific col to the where clause
				$sWhere .= $aColumnsAlias[$i] . " LIKE '%' || :whereSpecificParam" . $sWhereSpecificArrayCount . " || '%' ";

				//Inc sWhereSpecificArrayCount. It is needed for the bind var.
				//We could just do count($sWhereSpecificArray) - but that would be less efficient.
				$sWhereSpecificArrayCount++;

				//Add current search param to the array for later use (binding).
				$sWhereSpecificArray[] =  $_GET['sSearch_' . $i];
			}
		}

		//If there is still no where clause - set a general - always true where clause
		if ($sWhere == "") {
			$sWhere = " AND 1=1";
		}

		//Bind variables.
		if (isset($_GET['iDisplayStart'])) {
			$dsplyStart = $_GET['iDisplayStart'];
		} else {
			$dsplyStart = 0;
		}
		if (isset($_GET['iDisplayLength']) && $_GET['iDisplayLength'] != '-1') {
			$dsplyRange = $_GET['iDisplayLength'];
			if ($dsplyRange > (2147483645 - intval($dsplyStart))) {
				$dsplyRange = 2147483645;
			} else {
				$dsplyRange = intval($dsplyRange);
			}
		} else {
			$dsplyRange = 2147483645;
		}


		$statement_privacy .= " AND A.STATUS_SURAT = 'TU-IN' ";
		$statement_privacy .= " AND NOT EXISTS(SELECT 1 FROM SURAT_MASUK_ARSIP X WHERE X.SURAT_MASUK_ID = A.SURAT_MASUK_ID AND X.CABANG_ID = '" . $this->CABANG_ID . "') ";
		//$statement_privacy .= " AND A.SATUAN_KERJA_ID_ASAL = '".$this->SATUAN_KERJA_ID_ASAL."' ";
		if ($this->USER_GROUP == "TATAUSAHA")
			$statement_privacy .= " AND EXISTS(SELECT 1 FROM DISPOSISI X WHERE X.SURAT_MASUK_ID = A.SURAT_MASUK_ID AND X.CABANG_ID_TUJUAN = '" . $this->CABANG_ID . "') ";
		else
			exit;

		$statement = " AND (
						UPPER(A.NO_AGENDA) LIKE '%" . strtoupper($_GET['sSearch']) . "%' OR 
						UPPER(A.NOMOR) LIKE '%" . strtoupper($_GET['sSearch']) . "%' OR
						UPPER(A.PERIHAL) LIKE '%" . strtoupper($_GET['sSearch']) . "%' OR 
						UPPER(A.INSTANSI_ASAL) LIKE '%" . strtoupper($_GET['sSearch']) . "%' 
						) ";

		$allRecord = $surat_masuk->getCountByParamsMonitoring(array(), $statement_privacy . $statement);
		// echo $allRecord;exit;
		if ($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else
			$allRecordFilter =  $surat_masuk->getCountByParamsMonitoring(array(), $statement_privacy . $statement);

		$surat_masuk->selectByParamsMonitoring(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);
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

		while ($surat_masuk->nextRow()) {
			$row = array();
			for ($i = 0; $i < count($aColumns); $i++) {
				if ($aColumns[$i] == "KETERANGAN")
					$row[] = truncate($surat_masuk->getField($aColumns[$i]), 2);
				elseif ($aColumns[$i] == "TERBACA" || $aColumns[$i] == "TERDISPOSISI" || $aColumns[$i] == "TERBALAS") {
					if ((int)$surat_masuk->getField($aColumns[$i]) > 0)
						$row[] = "<i class=\"fa fa-check fa-md\" aria-hidden=\"true\"></i>";
					else
						$row[] = "<i class=\"fa fa-close fa-md\" aria-hidden=\"true\"></i>";
				} else
					$row[] = $surat_masuk->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		echo json_encode($output);
	}


	function json_log_surat_masuk()
	{
		$this->load->model("SuratMasuk");
		$surat_masuk = new SuratMasuk();

		$reqSatuanKerjaId = $this->input->get("reqSatuanKerjaId");
		$reqJenisNaskahId = $this->input->get("reqJenisNaskahId");

		$aColumns = array(
			"SURAT_MASUK_ID", "NOMOR", "DARI", "KEPADA", "TANGGAL_ENTRI",
			"PERIHAL", "JENIS_NASKAH", "SIFAT_NASKAH", "USER_ID"
		);
		$aColumnsAlias = array(
			"A.SURAT_MASUK_ID", "A.STATUS_SURAT", "A.NOMOR", "A.TANGGAL_ENTRI",
			"PERIHAL", "JENIS_NASKAH_ID", "SIFAT_NASKAH",
			"USER_ID"
		);

		/*
		 * Ordering
		 */
		if (isset($_GET['iSortCol_0'])) {
			$sOrder = " ORDER BY ";

			//Go over all sorting cols
			for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
				//If need to sort by current col
				if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
					//Add to the order by clause
					$sOrder .= $aColumnsAlias[intval($_GET['iSortCol_' . $i])];

					//Determine if it is sorted asc or desc
					if (strcasecmp(($_GET['sSortDir_' . $i]), "asc") == 0) {
						$sOrder .= " asc, ";
					} else {
						$sOrder .= " desc, ";
					}
				}
			}

			//Remove the last space / comma
			$sOrder = substr_replace($sOrder, "", -2);

			//Check if there is an order by clause
			if (trim($sOrder) == "ORDER BY A.SURAT_MASUK_ID asc") {
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
		if (isset($_GET['sSearch'])) {
			$sWhereGenearal = $_GET['sSearch'];
		} else {
			$sWhereGenearal = '';
		}

		if ($_GET['sSearch'] != "") {
			//Set a default where clause in order for the where clause not to fail
			//in cases where there are no searchable cols at all.
			$sWhere = " AND (";
			for ($i = 0; $i < count($aColumnsAlias) + 1; $i++) {
				//If current col has a search param
				if ($_GET['bSearchable_' . $i] == "true") {
					//Add the search to the where clause
					$sWhere .= $aColumnsAlias[$i] . " LIKE '%" . $_GET['sSearch'] . "%' OR ";
					$nWhereGenearalCount += 1;
				}
			}
			$sWhere = substr_replace($sWhere, "", -3);
			$sWhere .= ')';
		}

		/* Individual column filtering */
		$sWhereSpecificArray = array();
		$sWhereSpecificArrayCount = 0;
		for ($i = 0; $i < count($aColumnsAlias); $i++) {
			if ($_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {
				//If there was no where clause
				if ($sWhere == "") {
					$sWhere = "AND ";
				} else {
					$sWhere .= " AND ";
				}

				//Add the clause of the specific col to the where clause
				$sWhere .= $aColumnsAlias[$i] . " LIKE '%' || :whereSpecificParam" . $sWhereSpecificArrayCount . " || '%' ";

				//Inc sWhereSpecificArrayCount. It is needed for the bind var.
				//We could just do count($sWhereSpecificArray) - but that would be less efficient.
				$sWhereSpecificArrayCount++;

				//Add current search param to the array for later use (binding).
				$sWhereSpecificArray[] =  $_GET['sSearch_' . $i];
			}
		}

		//If there is still no where clause - set a general - always true where clause
		if ($sWhere == "") {
			$sWhere = " AND 1=1";
		}

		//Bind variables.
		if (isset($_GET['iDisplayStart'])) {
			$dsplyStart = $_GET['iDisplayStart'];
		} else {
			$dsplyStart = 0;
		}
		if (isset($_GET['iDisplayLength']) && $_GET['iDisplayLength'] != '-1') {
			$dsplyRange = $_GET['iDisplayLength'];
			if ($dsplyRange > (2147483645 - intval($dsplyStart))) {
				$dsplyRange = 2147483645;
			} else {
				$dsplyRange = intval($dsplyRange);
			}
		} else {
			$dsplyRange = 2147483645;
		}


		$statement_privacy .= " AND A.STATUS_SURAT = 'TU-IN' ";
		$statement_privacy .= " AND NOT EXISTS(SELECT 1 FROM SURAT_MASUK_ARSIP X WHERE X.SURAT_MASUK_ID = A.SURAT_MASUK_ID AND X.CABANG_ID = '" . $this->CABANG_ID . "') ";
		$statement_privacy .= " AND EXISTS(SELECT 1 FROM DISPOSISI X WHERE X.SURAT_MASUK_ID = A.SURAT_MASUK_ID AND X.CABANG_ID_TUJUAN = '" . $this->CABANG_ID . "' AND X.SATUAN_KERJA_ID_TUJUAN = '" . $reqSatuanKerjaId . "') ";

		if (!empty($reqJenisNaskahId)) {
			$statement_privacy .= " AND A.JENIS_NASKAH_ID = '" . $reqJenisNaskahId . "' ";
		}

		$statement = " AND (
						UPPER(A.NO_AGENDA) LIKE '%" . strtoupper($_GET['sSearch']) . "%' OR 
						UPPER(A.NOMOR) LIKE '%" . strtoupper($_GET['sSearch']) . "%' OR
						UPPER(A.PERIHAL) LIKE '%" . strtoupper($_GET['sSearch']) . "%' OR 
						UPPER(A.INSTANSI_ASAL) LIKE '%" . strtoupper($_GET['sSearch']) . "%' 
						) ";

		$allRecord = $surat_masuk->getCountByParamsMonitoring(array(), $statement_privacy . $statement);
		// echo $allRecord;exit;
		if ($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else
			$allRecordFilter =  $surat_masuk->getCountByParamsMonitoring(array(), $statement_privacy . $statement);

		$surat_masuk->selectByParamsMonitoring(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);

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

		while ($surat_masuk->nextRow()) {
			$row = array();
			for ($i = 0; $i < count($aColumns); $i++) {
				if ($aColumns[$i] == "KETERANGAN")
					$row[] = truncate($surat_masuk->getField($aColumns[$i]), 2);
				elseif ($aColumns[$i] == "TERBACA" || $aColumns[$i] == "TERDISPOSISI" || $aColumns[$i] == "TERBALAS") {
					if ((int)$surat_masuk->getField($aColumns[$i]) > 0)
						$row[] = "<i class=\"fa fa-check fa-md\" aria-hidden=\"true\"></i>";
					else
						$row[] = "<i class=\"fa fa-close fa-md\" aria-hidden=\"true\"></i>";
				} else
					$row[] = $surat_masuk->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		echo json_encode($output);
	}

	function json_log_surat_keluar()
	{
		$this->load->model("SuratMasuk");
		$surat_masuk = new SuratMasuk();

		$reqSatuanKerjaId = $this->input->get("reqSatuanKerjaId");
		$reqJenisNaskahId = $this->input->get("reqJenisNaskahId");

		$aColumns = array(
			"SURAT_MASUK_ID", "STATUS_SURAT", "NOMOR", "PERIHAL", "TANGGAL_ENTRI", "KEPADA", "INSTANSI_ASAL",
			"JENIS_NASKAH", "SIFAT_NASKAH", "USER_ID"
		);
		$aColumnsAlias = array(
			"SURAT_MASUK_ID", "STATUS_SURAT", "NOMOR", "PERIHAL", "TANGGAL_ENTRI", "KEPADA", "INSTANSI_ASAL",
			"JENIS_NASKAH", "SIFAT_NASKAH", "USER_ID"
		);

		/*
		 * Ordering
		 */
		if (isset($_GET['iSortCol_0'])) {
			$sOrder = " ORDER BY ";

			//Go over all sorting cols
			for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
				//If need to sort by current col
				if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
					//Add to the order by clause
					$sOrder .= $aColumnsAlias[intval($_GET['iSortCol_' . $i])];

					//Determine if it is sorted asc or desc
					if (strcasecmp(($_GET['sSortDir_' . $i]), "asc") == 0) {
						$sOrder .= " asc, ";
					} else {
						$sOrder .= " desc, ";
					}
				}
			}

			//Remove the last space / comma
			$sOrder = substr_replace($sOrder, "", -2);

			//Check if there is an order by clause
			if (trim($sOrder) == "ORDER BY A.TANGGAL_ENTRI DESC") {
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
		if (isset($_GET['sSearch'])) {
			$sWhereGenearal = $_GET['sSearch'];
		} else {
			$sWhereGenearal = '';
		}

		if ($_GET['sSearch'] != "") {
			//Set a default where clause in order for the where clause not to fail
			//in cases where there are no searchable cols at all.
			$sWhere = " AND (";
			for ($i = 0; $i < count($aColumnsAlias) + 1; $i++) {
				//If current col has a search param
				if ($_GET['bSearchable_' . $i] == "true") {
					//Add the search to the where clause
					$sWhere .= $aColumnsAlias[$i] . " LIKE '%" . $_GET['sSearch'] . "%' OR ";
					$nWhereGenearalCount += 1;
				}
			}
			$sWhere = substr_replace($sWhere, "", -3);
			$sWhere .= ')';
		}

		/* Individual column filtering */
		$sWhereSpecificArray = array();
		$sWhereSpecificArrayCount = 0;
		for ($i = 0; $i < count($aColumnsAlias); $i++) {
			if ($_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {
				//If there was no where clause
				if ($sWhere == "") {
					$sWhere = "AND ";
				} else {
					$sWhere .= " AND ";
				}

				//Add the clause of the specific col to the where clause
				$sWhere .= $aColumnsAlias[$i] . " LIKE '%' || :whereSpecificParam" . $sWhereSpecificArrayCount . " || '%' ";

				//Inc sWhereSpecificArrayCount. It is needed for the bind var.
				//We could just do count($sWhereSpecificArray) - but that would be less efficient.
				$sWhereSpecificArrayCount++;

				//Add current search param to the array for later use (binding).
				$sWhereSpecificArray[] =  $_GET['sSearch_' . $i];
			}
		}

		//If there is still no where clause - set a general - always true where clause
		if ($sWhere == "") {
			$sWhere = " AND 1=1";
		}

		//Bind variables.
		if (isset($_GET['iDisplayStart'])) {
			$dsplyStart = $_GET['iDisplayStart'];
		} else {
			$dsplyStart = 0;
		}
		if (isset($_GET['iDisplayLength']) && $_GET['iDisplayLength'] != '-1') {
			$dsplyRange = $_GET['iDisplayLength'];
			if ($dsplyRange > (2147483645 - intval($dsplyStart))) {
				$dsplyRange = 2147483645;
			} else {
				$dsplyRange = intval($dsplyRange);
			}
		} else {
			$dsplyRange = 2147483645;
		}


		$statement_privacy .= " AND A.STATUS_SURAT IN ('POSTING', 'TU-IN', 'TU-OUT', 'TU-NOMOR') ";

		if (!empty($reqSatuanKerjaId)) {
			$statement_privacy .= " AND A.SATUAN_KERJA_ID_ASAL = '" . $reqSatuanKerjaId . "' ";
		}

		if (!empty($reqJenisNaskahId)) {
			$statement_privacy .= " AND A.JENIS_NASKAH_ID = '" . $reqJenisNaskahId . "' ";
		}

		$statement_privacy .= " AND NOT A.NOMOR IS NULL ";
		$statement_privacy .= " AND A.CABANG_ID = '" . $this->CABANG_ID . "' ";

		$statement = " AND (
						UPPER(A.NO_AGENDA) LIKE '%" . strtoupper($_GET['sSearch']) . "%' OR 
						UPPER(A.NOMOR) LIKE '%" . strtoupper($_GET['sSearch']) . "%' OR
						UPPER(A.PERIHAL) LIKE '%" . strtoupper($_GET['sSearch']) . "%' OR 
						UPPER(A.INSTANSI_ASAL) LIKE '%" . strtoupper($_GET['sSearch']) . "%' 
						) ";

		$allRecord = $surat_masuk->getCountByParamsMonitoring(array(), $statement_privacy . $statement);
		// echo $allRecord;exit;
		if ($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else
			$allRecordFilter =  $surat_masuk->getCountByParamsMonitoring(array(), $statement_privacy . $statement);

		$surat_masuk->selectByParamsMonitoring(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, " ORDER BY A.TANGGAL_ENTRI DESC");

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

		while ($surat_masuk->nextRow()) {
			$row = array();
			for ($i = 0; $i < count($aColumns); $i++) {
				if ($aColumns[$i] == "KETERANGAN")
					$row[] = truncate($surat_masuk->getField($aColumns[$i]), 2);
				elseif ($aColumns[$i] == "TERBACA" || $aColumns[$i] == "TERDISPOSISI" || $aColumns[$i] == "TERBALAS") {
					if ((int)$surat_masuk->getField($aColumns[$i]) > 0)
						$row[] = "<i class=\"fa fa-check fa-md\" aria-hidden=\"true\"></i>";
					else
						$row[] = "<i class=\"fa fa-close fa-md\" aria-hidden=\"true\"></i>";
				} else
					$row[] = $surat_masuk->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		echo json_encode($output);
	}


	function json_pemberitahuan()
	{
		$this->load->model("SuratMasuk");
		$surat_masuk = new SuratMasuk();

		$reqJenisTujuan = $this->input->get("reqJenisTujuan");
		// echo $reqKategori;exit;

		$aColumns = array(
			"SURAT_MASUK_ID", "STATUS_SURAT", "NOMOR", "NO_AGENDA", "TANGGAL_ENTRI", "TANGGAL",
			"JENIS_NASKAH", "PERIHAL", "SIFAT_NASKAH",
			"KEPADA", "TEMBUSAN", "INSTANSI_ASAL", "USER_ID"
		);
		$aColumnsAlias = array(
			"A.SURAT_MASUK_ID", "A.STATUS_SURAT", "A.NOMOR", "A.NO_AGENDA", "A.TANGGAL_ENTRI", "A.TANGGAL",
			"JENIS_NASKAH_ID", "PERIHAL", "SIFAT_NASKAH",
			"PERIHAL", "PERIHAL", "INSTANSI_ASAL", "USER_ID"
		);

		/*
		 * Ordering
		 */
		if (isset($_GET['iSortCol_0'])) {
			$sOrder = " ORDER BY ";

			//Go over all sorting cols
			for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
				//If need to sort by current col
				if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
					//Add to the order by clause
					$sOrder .= $aColumnsAlias[intval($_GET['iSortCol_' . $i])];

					//Determine if it is sorted asc or desc
					if (strcasecmp(($_GET['sSortDir_' . $i]), "asc") == 0) {
						$sOrder .= " asc, ";
					} else {
						$sOrder .= " desc, ";
					}
				}
			}

			//Remove the last space / comma
			$sOrder = substr_replace($sOrder, "", -2);

			//Check if there is an order by clause
			if (trim($sOrder) == "ORDER BY A.SURAT_MASUK_ID asc") {
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
		if (isset($_GET['sSearch'])) {
			$sWhereGenearal = $_GET['sSearch'];
		} else {
			$sWhereGenearal = '';
		}

		if ($_GET['sSearch'] != "") {
			//Set a default where clause in order for the where clause not to fail
			//in cases where there are no searchable cols at all.
			$sWhere = " AND (";
			for ($i = 0; $i < count($aColumnsAlias) + 1; $i++) {
				//If current col has a search param
				if ($_GET['bSearchable_' . $i] == "true") {
					//Add the search to the where clause
					$sWhere .= $aColumnsAlias[$i] . " LIKE '%" . $_GET['sSearch'] . "%' OR ";
					$nWhereGenearalCount += 1;
				}
			}
			$sWhere = substr_replace($sWhere, "", -3);
			$sWhere .= ')';
		}

		/* Individual column filtering */
		$sWhereSpecificArray = array();
		$sWhereSpecificArrayCount = 0;
		for ($i = 0; $i < count($aColumnsAlias); $i++) {
			if ($_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {
				//If there was no where clause
				if ($sWhere == "") {
					$sWhere = "AND ";
				} else {
					$sWhere .= " AND ";
				}

				//Add the clause of the specific col to the where clause
				$sWhere .= $aColumnsAlias[$i] . " LIKE '%' || :whereSpecificParam" . $sWhereSpecificArrayCount . " || '%' ";

				//Inc sWhereSpecificArrayCount. It is needed for the bind var.
				//We could just do count($sWhereSpecificArray) - but that would be less efficient.
				$sWhereSpecificArrayCount++;

				//Add current search param to the array for later use (binding).
				$sWhereSpecificArray[] =  $_GET['sSearch_' . $i];
			}
		}

		//If there is still no where clause - set a general - always true where clause
		if ($sWhere == "") {
			$sWhere = " AND 1=1";
		}

		//Bind variables.
		if (isset($_GET['iDisplayStart'])) {
			$dsplyStart = $_GET['iDisplayStart'];
		} else {
			$dsplyStart = 0;
		}
		if (isset($_GET['iDisplayLength']) && $_GET['iDisplayLength'] != '-1') {
			$dsplyRange = $_GET['iDisplayLength'];
			if ($dsplyRange > (2147483645 - intval($dsplyStart))) {
				$dsplyRange = 2147483645;
			} else {
				$dsplyRange = intval($dsplyRange);
			}
		} else {
			$dsplyRange = 2147483645;
		}


		$statement_privacy .= " AND A.JENIS_TUJUAN = '" . $reqJenisTujuan . "' ";

		$statement_privacy .= " AND A.USER_ID = '" . $this->ID . "' ";
		//$statement_privacy .= " AND A.SATUAN_KERJA_ID_ASAL = '".$this->SATUAN_KERJA_ID_ASAL."' ";

		$statement = " AND (
						UPPER(A.NO_AGENDA) LIKE '%" . strtoupper($_GET['sSearch']) . "%' OR 
						UPPER(A.NOMOR) LIKE '%" . strtoupper($_GET['sSearch']) . "%' OR
						UPPER(A.PERIHAL) LIKE '%" . strtoupper($_GET['sSearch']) . "%' OR 
						UPPER(A.INSTANSI_ASAL) LIKE '%" . strtoupper($_GET['sSearch']) . "%' 
						) ";

		$allRecord = $surat_masuk->getCountByParamsMonitoring(array(), $statement_privacy . $statement);
		// echo $allRecord;exit;
		if ($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else
			$allRecordFilter =  $surat_masuk->getCountByParamsMonitoring(array(), $statement_privacy . $statement);

		$surat_masuk->selectByParamsMonitoring(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);

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

		while ($surat_masuk->nextRow()) {
			$row = array();
			for ($i = 0; $i < count($aColumns); $i++) {
				if ($aColumns[$i] == "KETERANGAN")
					$row[] = truncate($surat_masuk->getField($aColumns[$i]), 2);
				elseif ($aColumns[$i] == "TERBACA" || $aColumns[$i] == "TERDISPOSISI" || $aColumns[$i] == "TERBALAS") {
					if ((int)$surat_masuk->getField($aColumns[$i]) > 0)
						$row[] = "<i class=\"fa fa-check fa-md\" aria-hidden=\"true\"></i>";
					else
						$row[] = "<i class=\"fa fa-close fa-md\" aria-hidden=\"true\"></i>";
				} else
					$row[] = $surat_masuk->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		echo json_encode($output);
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
		if (isset($_GET['iSortCol_0'])) {
			$sOrder = " ORDER BY ";

			//Go over all sorting cols
			for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
				//If need to sort by current col
				if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
					//Add to the order by clause
					$sOrder .= $aColumnsAlias[intval($_GET['iSortCol_' . $i])];

					//Determine if it is sorted asc or desc
					if (strcasecmp(($_GET['sSortDir_' . $i]), "asc") == 0) {
						$sOrder .= " asc, ";
					} else {
						$sOrder .= " desc, ";
					}
				}
			}

			//Remove the last space / comma
			$sOrder = substr_replace($sOrder, "", -2);

			//Check if there is an order by clause
			if (trim($sOrder) == "ORDER BY A.NOMOR DESC") {
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.NOMOR DESC";
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
		if (isset($_GET['sSearch'])) {
			$sWhereGenearal = $_GET['sSearch'];
		} else {
			$sWhereGenearal = '';
		}

		if ($_GET['sSearch'] != "") {
			//Set a default where clause in order for the where clause not to fail
			//in cases where there are no searchable cols at all.
			$sWhere = " AND (";
			for ($i = 0; $i < count($aColumnsAlias) + 1; $i++) {
				//If current col has a search param
				if ($_GET['bSearchable_' . $i] == "true") {
					//Add the search to the where clause
					$sWhere .= $aColumnsAlias[$i] . " LIKE '%" . $_GET['sSearch'] . "%' OR ";
					$nWhereGenearalCount += 1;
				}
			}
			$sWhere = substr_replace($sWhere, "", -3);
			$sWhere .= ')';
		}

		/* Individual column filtering */
		$sWhereSpecificArray = array();
		$sWhereSpecificArrayCount = 0;
		for ($i = 0; $i < count($aColumnsAlias); $i++) {
			if ($_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {
				//If there was no where clause
				if ($sWhere == "") {
					$sWhere = "AND ";
				} else {
					$sWhere .= " AND ";
				}

				//Add the clause of the specific col to the where clause
				$sWhere .= $aColumnsAlias[$i] . " LIKE '%' || :whereSpecificParam" . $sWhereSpecificArrayCount . " || '%' ";

				//Inc sWhereSpecificArrayCount. It is needed for the bind var.
				//We could just do count($sWhereSpecificArray) - but that would be less efficient.
				$sWhereSpecificArrayCount++;

				//Add current search param to the array for later use (binding).
				$sWhereSpecificArray[] =  $_GET['sSearch_' . $i];
			}
		}

		//If there is still no where clause - set a general - always true where clause
		if ($sWhere == "") {
			$sWhere = " AND 1=1";
		}

		//Bind variables.
		if (isset($_GET['iDisplayStart'])) {
			$dsplyStart = $_GET['iDisplayStart'];
		} else {
			$dsplyStart = 0;
		}
		if (isset($_GET['iDisplayLength']) && $_GET['iDisplayLength'] != '-1') {
			$dsplyRange = $_GET['iDisplayLength'];
			if ($dsplyRange > (2147483645 - intval($dsplyStart))) {
				$dsplyRange = 2147483645;
			} else {
				$dsplyRange = intval($dsplyRange);
			}
		} else {
			$dsplyRange = 2147483645;
		}


		if ($reqSatuanKerjaId == "") {
			$statement_privacy .= " AND A.SATUAN_KERJA_ID_ASAL = '" . $this->SATUAN_KERJA_ID_ASAL . "' ";
		} else {
			$statement_privacy .= " AND A.SATUAN_KERJA_ID_ASAL = '" . $reqSatuanKerjaId . "' ";
		}

		if ($reqJenisNaskahId == "") {
		} else {
			$statement_privacy .= " AND A.JENIS_NASKAH_ID = '" . $reqJenisNaskahId . "' ";
		}

		$statement_privacy .= " AND A.JENIS_TUJUAN = 'NI' ";
		$statement_privacy .= " AND NOT A.NOMOR = '' ";

		$allRecord = $surat_masuk->getCountByParamsLogRegistrasiKeluar(array(), $statement_privacy . $statement);
		// echo $allRecord;exit;
		if ($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else
			$allRecordFilter =  $surat_masuk->getCountByParamsLogRegistrasiKeluar(array(), $statement_privacy . $statement);

		$surat_masuk->selectByParamsLogRegistrasiKeluar(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, " ORDER BY A.NOMOR DESC");
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

		while ($surat_masuk->nextRow()) {
			$row = array();
			for ($i = 0; $i < count($aColumns); $i++) {
				if ($aColumns[$i] == "KETERANGAN")
					$row[] = truncate($surat_masuk->getField($aColumns[$i]), 2);
				elseif ($aColumns[$i] == "TERBACA" || $aColumns[$i] == "TERDISPOSISI" || $aColumns[$i] == "TERBALAS") {
					if ((int)$surat_masuk->getField($aColumns[$i]) > 0)
						$row[] = "<i class=\"fa fa-check fa-md\" aria-hidden=\"true\"></i>";
					else
						$row[] = "<i class=\"fa fa-close fa-md\" aria-hidden=\"true\"></i>";
				} else
					$row[] = $surat_masuk->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		echo json_encode($output);
	}

	function add()
	{
		// print_r($_FILES["reqLinkFile"]);exit;
		$this->load->model("SuratMasuk");
		$this->load->model("Disposisi");
		$this->load->model("DisposisiKelompok");
		$this->load->model("SatuanKerja");
		$this->load->model("DaftarAlamat");
		$this->load->model("SuratMasukReference");

		$surat_masuk = new SuratMasuk();
		$disposisi = new Disposisi();
		$disposisi_kelompok = new DisposisiKelompok();

		$reqMode 					= $this->input->post("reqMode");
		$reqId 						= $this->input->post("reqId");
		$refDisposisiId 			= $this->input->post("refDisposisiId");
		$reqSmRefMultiId 			= $this->input->post("reqSmRefMultiId");

		if ($refDisposisiId == "")
			$reqIdRef = "";
		else {
			$surat_masuk_ref = new SuratMasuk();
			$surat_masuk_ref->selectByParams(array(), -1, -1, " AND EXISTS(SELECT 1 FROM DISPOSISI X WHERE X.SURAT_MASUK_ID = A.SURAT_MASUK_ID AND MD5('BALAS' || X.DISPOSISI_ID) = '" . $refDisposisiId . "') ");
			$surat_masuk_ref->firstRow();
			$reqIdRef = $surat_masuk_ref->getField("SURAT_MASUK_ID");
		}

		// $libreplace= '<p data-f-id="pbf" style="text-align: center; font-size: 14px; margin-top: 30px; opacity: 0.65; font-family: sans-serif;">Powered by <a href="https://www.froala.com/wysiwyg-editor?pb=1" title="Froala Editor">Froala Editor</a></p>';

		$reqJenisTujuan 			= $this->input->post("reqJenisTujuan");
		$reqJenisNaskah 			= $this->input->post("reqJenisNaskah");
		$reqKdLevel 				= $this->input->post("reqKdLevel");
		$reqNoAgenda 				= $this->input->post("reqNoAgenda");
		$reqNoSurat 				= $this->input->post("reqNoSurat");
		$reqTanggal 				= $this->input->post("reqTanggal");
		$reqPerihal	    			= $this->input->post("reqPerihal");
		// $reqKeterangan  			= $_POST["reqKeterangan"];

		$reqKeterangan  			= $this->input->post("reqKeterangan");
		$reqKeterangan				= str_replace($libreplace, '', $reqKeterangan);

		$reqSifatNaskah 			= $this->input->post("reqSifatNaskah");
		$reqStatusSurat 			= $this->input->post("reqStatusSurat");

		$reqDasar  					= $this->input->post("reqDasar");
		$reqDasar  					= str_replace($libreplace, '', $reqDasar);

		$reqIsiPerintah  			= $this->input->post("reqIsiPerintah");
		$reqIsiPerintah  			= str_replace($libreplace, '', $reqIsiPerintah);

		$reqLainLain  				= $this->input->post("reqLainLain");
		$reqLainLain  				= str_replace($libreplace, '', $reqLainLain);

		$reqLampiranDrive  				= $this->input->post("reqLampiranDrive");
		// echo $reqLampiranDrive; exit;

		$reqEksternalKepadaId= $this->input->post("reqEksternalKepadaId");
		$reqEksternalKepadaNama= $this->input->post("reqEksternalKepadaNama");

		$reqEksternalTembusanId= $this->input->post("reqEksternalTembusanId");
		$reqEksternalTembusanNama= $this->input->post("reqEksternalTembusanNama");

		$reqMenimbang			= $this->input->post("reqMenimbang");
		$reqMenimbang			= str_replace($libreplace, '', $reqMenimbang);
		$reqMengingat			= $this->input->post("reqMengingat");
		$reqMengingat			= str_replace($libreplace, '', $reqMengingat);
		$reqMemperhatikan		= $this->input->post("reqMemperhatikan");
		$reqMemperhatikan		= str_replace($libreplace, '', $reqMemperhatikan);
		$reqMenetapkan			= $this->input->post("reqMenetapkan");
		$reqMenetapkan			= str_replace($libreplace, '', $reqMenetapkan);
		$reqPertama				= $this->input->post("reqPertama");
		$reqPertama				= str_replace($libreplace, '', $reqPertama);
		$reqPasal= $this->input->post("reqPasal");
		// print_r($reqPasal);exit;

		// $reqKedua= $this->input->post("reqKedua");
		// $reqKetiga= $this->input->post("reqKetiga");
		// $reqKeempat= $this->input->post("reqKeempat");
		// $reqKelima= $this->input->post("reqKelima");
		// $reqKeenam= $this->input->post("reqKeenam");
		// $reqKetujuh= $this->input->post("reqKetujuh");
		// $reqKedelapan= $this->input->post("reqKedelapan");
		// $reqKesembilan= $this->input->post("reqKesembilan");
		// $reqKesepuluh= $this->input->post("reqKesepuluh");
		// echo $reqEksternalKepadaNama;exit;

		// tambahan khusus
		if($reqStatusSurat == "UBAHDATAPARAF" || $reqStatusSurat == "UBAHDATAREVISI")
		{
			$reqKondisiStatusSurat= $reqStatusSurat;
			$reqStatusSurat= "DRAFT";
		}

		if($reqStatusSurat == "UBAHDATADRAFTPARAF")
		{
			$reqKondisiStatusSurat= $reqStatusSurat;
			$reqStatusSurat= "PARAF";
		}

		if($reqStatusSurat == "UBAHDATAPOSTING")
		{
			$reqKondisiStatusSurat= $reqStatusSurat;
			$reqStatusSurat= "POSTING";
		}

		if($reqStatusSurat == "UBAHDATAVALIDASI")
		{
			$reqKondisiStatusSurat= $reqStatusSurat;
			$reqStatusSurat= "VALIDASI";
		}

		$reqAsalSuratNama 			= $this->input->post("reqAsalSuratNama");
		$reqAsalSuratKota 			= $this->input->post("reqAsalSuratKota");
		$reqAsalSuratAlamat 		= $this->input->post("reqAsalSuratAlamat");
		$reqAsalSuratInstansi		= $this->input->post("reqAsalSuratInstansi");
		$reqLokasiSurat				= $this->input->post("reqLokasiSurat");
		$reqSatuanKerjaIdTujuan		= $this->input->post("reqSatuanKerjaIdTujuan");
		$reqSatuanKerjaIdTembusan	= $this->input->post("reqSatuanKerjaIdTembusan");
		$reqSatuanKerjaIdPerintah	= $this->input->post("reqSatuanKerjaIdPerintah");
		$reqSatuanKerjaIdParaf   	= $this->input->post("reqSatuanKerjaIdParaf");
		// print_r($reqSatuanKerjaIdTujuan);exit;
		$reqKlasifikasiId   		= $this->input->post("reqKlasifikasiId");
		$reqPenyampaianSurat		= $this->input->post("reqPenyampaianSurat");
		$reqSatuanKerjaId			= $this->input->post("reqSatuanKerjaId");
		$reqSatuanKerjaIdPetikan	= $this->input->post("reqSatuanKerjaIdPetikan");

		$reqTanggalKegiatan 	 	= $this->input->post("reqTanggalKegiatan");
		$reqTanggalKegiatanAkhir 	= $this->input->post("reqTanggalKegiatanAkhir");
		$reqJamKegiatan          	= $this->input->post("reqJamKegiatan");
		$reqJamKegiatanAkhir     	= $this->input->post("reqJamKegiatanAkhir");
		$reqIsEmail       			= $this->input->post("reqIsEmail");
		$reqIsMeeting     			= $this->input->post("reqIsMeeting");
		$reqRevisi     	  			= $this->input->post("reqRevisi");
		$reqPrioritasSuratId     	= $this->input->post("reqPrioritasSuratId");
		$reqPermohonanNomorId     	= $this->input->post("reqPermohonanNomorId");
		$reqArsip     	  			= $this->input->post("reqArsip");
		$reqArsipId       			= $this->input->post("reqArsipId");
		$reqJenisTTD       			= $this->input->post("reqJenisTTD");

		$reqAnStatus= $this->input->post("reqAnStatus");
		$reqAnStatusNama= $this->input->post("reqAnStatusNama");
		$reqButuhAksiId= $this->input->post("reqButuhAksiId");
		$reqPemesanSatuanKerjaId= $this->input->post("reqPemesanSatuanKerjaId");
		$reqPemesanSatuanKerjaIsi= $this->input->post("reqPemesanSatuanKerjaIsi");
		$reqInfoLog= $this->input->post("reqInfoLog");

		$reqLinkFileNaskah = $_FILES["reqLinkFileNaskah"];
		$reqLinkFileNaskahTemp	= $this->input->post("reqLinkFileNaskahTemp");
		$reqKotaTujuan       			= $this->input->post("reqKotaTujuan");

		$reqTarget = $this->input->post("reqTarget");
		if ($reqTarget == "")
			$reqTarget = "INTERNAL";

		if ($reqJenisTTD == "BASAH" && $reqStatusSurat == "POSTING") {
			if ($reqMode == "insert") {
				if ($this->CALLER == "MOBILE") {
					$arrReturn = array('status' => 'success', 'message' => 'Simpan sebagai DRAFT terlebih dahulu untuk generate Naskah', 'code' => 200);
					echo json_encode($arrReturn);
				} else {
					echo "0-Simpan sebagai DRAFT terlebih dahulu untuk generate Naskah";
					return;
				}
			}
		}

		if (count($reqSatuanKerjaIdTujuan) == 0) {
			if ($this->CALLER == "MOBILE") {
				$arrReturn = array('status' => 'success', 'message' => 'Tujuan surat belum ditentukan', 'code' => 200);
				echo json_encode($arrReturn);
			} else {
				echo "0-Tujuan surat belum ditentukan";
				return;
			}
		}

		if (trim($reqPerihal) == "") {
			if ($this->CALLER == "MOBILE") {
				$arrReturn = array('status' => 'success', 'message' => 'Perihal surat belum diisi', 'code' => 200);
				echo json_encode($arrReturn);
			} else {
				echo "0-Perihal surat belum diisi";
				return;
			}
		}

		$reqTanggalKeg = "NULL";
		$reqTanggalKegAkhir = "NULL";
		if ($reqIsMeeting == "Y") {
			if ($reqTanggalKegiatan == "") {
				$reqTanggalKeg = "NULL";
				$reqTanggalKegAkhir = "NULL";
			} else {
				if ($reqJamKegiatan == "")
					$reqTanggalKeg = "TO_TIMESTAMP('" . $reqTanggalKegiatan . "', 'DD-MM-YYYY')";
				else
					$reqTanggalKeg = "TO_TIMESTAMP('" . $reqTanggalKegiatan . " " . $reqJamKegiatan . "', 'DD-MM-YYYY HH24:MI')";

				if ($reqTanggalKegiatanAkhir == "") {
					$reqTanggalKegAkhir = "NULL";
				} else {
					if ($reqJamKegiatanAkhir == "")
						$reqTanggalKegAkhir = "TO_TIMESTAMP('" . $reqTanggalKegiatanAkhir . "', 'DD-MM-YYYY')";
					else
						$reqTanggalKegAkhir = "TO_TIMESTAMP('" . $reqTanggalKegiatanAkhir . " " . $reqJamKegiatanAkhir . "', 'DD-MM-YYYY HH24:MI')";
				}
			}
		}

		$surat_masuk->setField("TANGGAL_KEGIATAN", $reqTanggalKeg);
		$surat_masuk->setField("TANGGAL_KEGIATAN_AKHIR", $reqTanggalKegAkhir);
		$surat_masuk->setField("IS_MEETING", $reqIsMeeting);
		$surat_masuk->setField("IS_EMAIL", $reqIsEmail);
		$surat_masuk->setField("PRIORITAS_SURAT_ID", ValToNullDB($reqPrioritasSuratId));
		$surat_masuk->setField("ARSIP_ID", $reqArsipId);
		$surat_masuk->setField("ARSIP", $reqArsip);
		$surat_masuk->setField("JENIS_TTD", $reqJenisTTD);

		if ($this->USER_GROUP == "SEKRETARIS") {
			$surat_masuk->setField("PENERIMA_SURAT", $this->SATUAN_KERJA_ID_ASAL);
		} elseif ($this->USER_GROUP == "TATAUSAHA") {
			$surat_masuk->setField("PENERIMA_SURAT", $this->CABANG_ID);
		}

		$surat_masuk->setField("PERMOHONAN_NOMOR_ID", $reqPermohonanNomorId);
		$surat_masuk->setField("PENYAMPAIAN_SURAT", $reqPenyampaianSurat);
		$surat_masuk->setField("JENIS_TUJUAN", $reqJenisTujuan);
		$surat_masuk->setField("SURAT_MASUK_REF_ID", $reqIdRef);
		$surat_masuk->setField("SURAT_MASUK_ID", $reqId);
		$surat_masuk->setField("NO_AGENDA", $reqNoAgenda);
		$surat_masuk->setField("LOKASI_SIMPAN", $reqLokasiSurat);
		$surat_masuk->setField("NOMOR", $reqNoSurat);
		$surat_masuk->setField("TANGGAL", "CURRENT_DATE"); //dateToDbCheck($reqTanggal));
		$surat_masuk->setField("JENIS_NASKAH_ID", $reqJenisNaskah);
		$surat_masuk->setField("JENIS_NASKAH_LEVEL", $reqKdLevel);
		$surat_masuk->setField("SIFAT_NASKAH", $reqSifatNaskah);
		$surat_masuk->setField("STATUS_SURAT", $reqStatusSurat);
		$surat_masuk->setField("PERIHAL", $reqPerihal);
		$surat_masuk->setField("KLASIFIKASI_ID", $reqKlasifikasiId);
		$surat_masuk->setField("SATUAN_KERJA_ID_ASAL", $reqSatuanKerjaId);
		$surat_masuk->setField("SATUAN_KERJA_ID_ASAL_PETIKAN", $reqSatuanKerjaIdPetikan);
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
		$surat_masuk->setField("AN_STATUS", $reqAnStatus);
		$surat_masuk->setField("AN_NAMA", $reqAnStatusNama);
		$surat_masuk->setField("LAMPIRAN_DRIVE", $reqLampiranDrive);
		$surat_masuk->setField("BUTUH_AKSI_ID", ValToNullDB($reqButuhAksiId));
		$surat_masuk->setField("PEMESAN_SATUAN_KERJA_ID", ValToNullDB($reqPemesanSatuanKerjaId));
		$surat_masuk->setField("PEMESAN_SATUAN_KERJA_ISI", $reqPemesanSatuanKerjaIsi);
		$surat_masuk->setField("KOTA_TUJUAN", $reqKotaTujuan);
		$surat_masuk->setField("DASAR", str_replace("'", "&quot;", $reqDasar));
		$surat_masuk->setField("ISI_PERINTAH", str_replace("'", "&quot;", $reqIsiPerintah));
		$surat_masuk->setField("LAIN_LAIN", str_replace("'", "&quot;", $reqLainLain));

		$surat_masuk->setField("MENIMBANG",  str_replace("'", "&quot;", $reqMenimbang));
		$surat_masuk->setField("MENGINGAT",  str_replace("'", "&quot;", $reqMengingat));
		$surat_masuk->setField("MEMPERHATIKAN",  str_replace("'", "&quot;", $reqMemperhatikan));
		$surat_masuk->setField("MENETAPKAN",  str_replace("'", "&quot;", $reqMenetapkan));

		$surat_masuk->setField("PERTAMA",  str_replace("'", "&quot;", $reqPertama));

		$arraypasalfield= array("KEDUA", "KETIGA", "KEEMPAT", "KELIMA", "KEENAM", "KETUJUH", "KEDELAPAN", "KESEMBILAN", "KESEPULUH", "KESEBELAS", "KEDUABELAS", "KETIGABELAS", "KEEMPATBELAS", "KELIMABELAS", "KEENAMBELAS", "KETUJUHBELAS", "KEDELAPANBELAS", "KESEMBILANBELAS", "KEDUAPULUH", "KEDUAPULUHSATU", "KEDUAPULUHDUA", "KEDUAPULUHTIGA", "KEDUAPULUHEMPAT", "KEDUAPULUHLIMA");
		// $reqPasal= $this->input->post("reqPasal");
		if(!empty($reqPasal))
		{
			for($ipasal=0; $ipasal < count($reqPasal); $ipasal++)
			{
				$surat_masuk->setField($arraypasalfield[$ipasal],  str_replace("'", "&quot;", str_replace($libreplace, '', $reqPasal[$ipasal])));
			}
		}

		// $surat_masuk->setField("KEDUA",  str_replace("'", "&quot;", $reqKedua));
		// $surat_masuk->setField("KETIGA",  str_replace("'", "&quot;", $reqKetiga));
		// $surat_masuk->setField("KEEMPAT",  str_replace("'", "&quot;", $reqKeempat));
		// $surat_masuk->setField("KELIMA",  str_replace("'", "&quot;", $reqKelima));
		// $surat_masuk->setField("KEENAM",  str_replace("'", "&quot;", $reqKeenam));
		// $surat_masuk->setField("KETUJUH",  str_replace("'", "&quot;", $reqKetujuh));
		// $surat_masuk->setField("KEDELAPAN",  str_replace("'", "&quot;", $reqKedelapan));
		// $surat_masuk->setField("KESEMBILAN",  str_replace("'", "&quot;", $reqKesembilan));
		// $surat_masuk->setField("KESEPULUH",  str_replace("'", "&quot;", $reqKesepuluh));

		$reqDariInfo= $this->input->post("reqDariInfo");
		$reqNomorSuratInfo= $this->input->post("reqNomorSuratInfo");

		$surat_masuk->setField("DARI_INFO", $reqDariInfo);
		$surat_masuk->setField("NOMOR_SURAT_INFO", $reqNomorSuratInfo);

		$surat_masuk->setField("EKSTERNAL_KEPADA_ID", $reqEksternalKepadaId);
		$surat_masuk->setField("EKSTERNAL_KEPADA", $reqEksternalKepadaNama);
		$surat_masuk->setField("EKSTERNAL_TEMBUSAN", $reqEksternalTembusanNama);
		$surat_masuk->setField("EKSTERNAL_TEMBUSAN_ID", $reqEksternalTembusanId);

		$reqStatusApprove= $this->input->post("reqStatusApprove");
		$surat_masuk->setField("STATUS_APPROVE", $reqStatusApprove);

		$reqTanggalKegiatan 	 	= $this->input->post("reqTanggalKegiatan");
		$reqTanggalKegiatanAkhir 	= $this->input->post("reqTanggalKegiatanAkhir");
		$reqJamKegiatan          	= $this->input->post("reqJamKegiatan");
		$reqJamKegiatanAkhir     	= $this->input->post("reqJamKegiatanAkhir");
		$reqIsEmail       			= $this->input->post("reqIsEmail");
		$reqIsMeeting     			= $this->input->post("reqIsMeeting");

		if ($reqMode == "insert") {
			$surat_masuk->setField("LAST_CREATE_USER", $this->ID);
			$surat_masuk->insert();
			$reqId = $surat_masuk->id;
		} else {
			$surat_masuk->setField("LAST_UPDATE_USER", $this->ID);
			$surat_masuk->update();
		}
		// echo $surat_masuk->query;exit;

		// simpan log data, kalau ada data varible reqInfoLog
		if(!empty($reqInfoLog))
		{
			$slog= new SuratMasuk();
			$slog->setField("SURAT_MASUK_ID", $reqId);
			$slog->setField("STATUS_SURAT", $reqStatusSurat);
			$slog->setField("INFORMASI", $this->JABATAN." (".$this->NAMA.")");
			$slog->setField("CATATAN", $reqInfoLog);
			$slog->setField("LAST_CREATE_USER", $this->ID);
			$slog->insertlog();
			unset($slog);
		}

		if ($reqTarget == "INTERNAL") {
			if ($reqJenisTTD == "BASAH" && $reqStatusSurat == "VALIDASI") {
				/* CEK APAKAH PEMBUAT / SEKRETARIS NYA */
				$surat_masuk_asal = new SuratMasuk();
				$pemilikSurat = $surat_masuk_asal->getPemilikSurat(array("SURAT_MASUK_ID" => $reqId));

				if ($this->ID == $pemilikSurat || $this->ID_ATASAN == $pemilikSurat) {

					if ($reqLinkFileNaskah["name"] == "" && $reqLinkFileNaskahTemp == "") {
						if ($this->CALLER == "MOBILE") {
							$arrReturn = array('status' => 'success', 'message' => 'Upload naskah yang sudah ditandatangani terlebih dahulu', 'code' => 200);
							echo json_encode($arrReturn);
						} else {
							echo "0-Upload naskah yang sudah ditandatangani terlebih dahulu";
							return;
						}
					} else {


						/* WAJIB UNTUK UPLOAD DATA */
						$this->load->library("FileHandler");
						$file = new FileHandler();
						$FILE_DIR = "uploads/" . $reqId . "/";
						makedirs($FILE_DIR);

						$reqLinkFileNaskah 		= $_FILES["reqLinkFileNaskah"];
						$reqLinkFileNaskahTemp	=  $this->input->post("reqLinkFileNaskahTemp");


						$reqJenis = "NASKAHTTD" . generateZero($reqId, 5);
						$renameFileNaskah = $reqJenis . date("Ymdhis") . rand() . "." . getExtension($reqLinkFileNaskah['name']);

						if ($file->uploadToDir('reqLinkFileNaskah', $FILE_DIR, $renameFileNaskah)) {
							$insertLinkFileNaskah =  $renameFileNaskah;
						} else {
							$insertLinkFileNaskah =  $reqLinkFileNaskahTemp;
						}

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
		if ($reqPermohonanNomorId == "") {
		} else {
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
		$FILE_DIR = "uploads/" . $reqId . "/";
		makedirs($FILE_DIR);

		$reqLinkFile = $_FILES["reqLinkFile"];
		$reqLinkFileTempSize	=  $this->input->post("reqLinkFileTempSize");
		$reqLinkFileTempTipe	=  $this->input->post("reqLinkFileTempTipe");
		$reqLinkFileTemp		=  $this->input->post("reqLinkFileTemp");
		$reqLinkFileTempNama	=  $this->input->post("reqLinkFileTempNama");

		$surat_masuk_attachement = new SuratMasuk();
		$surat_masuk_attachement->setField("SURAT_MASUK_ID", $reqId);
		$surat_masuk_attachement->deleteAttachment();

		$reqJenis = $reqJenisTujuan . generateZero($reqId, 5);
		for ($i = 0; $i < count($reqLinkFile['name']); $i++) {
			$renameFile = $reqJenis . date("Ymdhis") . rand() . "." . getExtension($reqLinkFile['name'][$i]);
			
			if ($file->uploadToDirArray('reqLinkFile', $FILE_DIR, $renameFile, $i)) {
				$insertLinkSize = $file->uploadedSize;
				$insertLinkTipe =  $file->uploadedExtension;
				$insertLinkFile =  $renameFile;

				if ($insertLinkFile == "") {
				} else {
					$surat_masuk_attachement = new SuratMasuk();
					$surat_masuk_attachement->setField("SURAT_MASUK_ID", $reqId);
					$surat_masuk_attachement->setField("ATTACHMENT", setQuote($renameFile, ""));
					$surat_masuk_attachement->setField("UKURAN", $insertLinkSize);
					$surat_masuk_attachement->setField("TIPE", $insertLinkTipe);
					$surat_masuk_attachement->setField("NAMA", setQuote($reqLinkFile['name'][$i], ""));
					$surat_masuk_attachement->setField("LAST_CREATE_USER", $this->ID);
					$surat_masuk_attachement->insertAttachment();
					// echo $surat_masuk_attachement->query;exit;
				}
			}
		}

		for ($i = 0; $i < count($reqLinkFileTemp); $i++) {
			$insertLinkSize = $reqLinkFileTempSize[$i];
			$insertLinkTipe =  $reqLinkFileTempTipe[$i];
			$insertLinkFile =  $reqLinkFileTemp[$i];
			$insertLinkNama =  $reqLinkFileTempNama[$i];

			if ($insertLinkFile == "") {
			} else {
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

		for ($i = 0; $i < count($reqSatuanKerjaIdTujuan); $i++) {
			if ($reqSatuanKerjaIdTujuan[$i] == "") {
			} else {
				/* JIKA TUJUAN KELOMPOK TAMPUNG SAJA DI DISPOSISI KELOMPOK */
				if (stristr($reqSatuanKerjaIdTujuan[$i], "KELOMPOK")) {
					$disposisi_kelompok = new DisposisiKelompok();
					$disposisi_kelompok->setField("SURAT_MASUK_ID", $reqId);
					$disposisi_kelompok->setField("SATUAN_KERJA_ID_ASAL", $reqSatuanKerjaId);
					$disposisi_kelompok->setField("SATUAN_KERJA_KELOMPOK_ID", str_replace("KELOMPOK", "", $reqSatuanKerjaIdTujuan[$i]));
					$disposisi_kelompok->setField("STATUS_DISPOSISI", "TUJUAN");
					$disposisi_kelompok->setField("LAST_CREATE_USER", $this->ID);
					$disposisi_kelompok->insert();
				} else {
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

		for ($i = 0; $i < count($reqSatuanKerjaIdPerintah); $i++) {
			if ($reqSatuanKerjaIdPerintah[$i] == "") {
			} else {
				/* JIKA TUJUAN KELOMPOK TAMPUNG SAJA DI DISPOSISI KELOMPOK */
				
					$disposisi = new SuratMasuk();
					$disposisi->setField("SURAT_MASUK_ID", $reqId);
					$disposisi->setField("SATUAN_KERJA_ID_ASAL", $reqSatuanKerjaId);
					$disposisi->setField("SATUAN_KERJA_ID_PERINTAH", $reqSatuanKerjaIdPerintah[$i]);
					$disposisi->insertPerintah();
					// echo $disposisi->query;exit;
				
			}
		}

		for ($i = 0; $i < count($reqSatuanKerjaIdTembusan); $i++) {
			if ($reqSatuanKerjaIdTembusan[$i] == "") {
			} else {

				/* JIKA TUJUAN KELOMPOK TAMPUNG SAJA DI DISPOSISI KELOMPOK */
				if (stristr($reqSatuanKerjaIdTembusan[$i], "KELOMPOK")) {
					$disposisi_kelompok = new DisposisiKelompok();
					$disposisi_kelompok->setField("SURAT_MASUK_ID", $reqId);
					$disposisi_kelompok->setField("SATUAN_KERJA_ID_ASAL", $reqSatuanKerjaId);
					$disposisi_kelompok->setField("SATUAN_KERJA_KELOMPOK_ID", str_replace("KELOMPOK", "", $reqSatuanKerjaIdTembusan[$i]));
					$disposisi_kelompok->setField("STATUS_DISPOSISI", "TEMBUSAN");
					$disposisi_kelompok->setField("LAST_CREATE_USER", $this->ID);
					$disposisi_kelompok->insert();
				} else {
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

		// tambahan khusus
		$reqSmRefMultiId= $this->input->post("reqSmRefMultiId");
		// print_r($reqSmRefMultiId);exit;
		$smref= new SuratMasukReference();
		$smref->setField("SURAT_MASUK_ID", $reqId);
		$smref->setField("LAST_CREATE_USER", $this->ID);
		$smref->deleteParent();

		for ($i = 0; $i < count($reqSmRefMultiId); $i++) {

			if(!empty($reqSmRefMultiId))
			{
				$smref= new SuratMasukReference();
				$smref->setField("SURAT_MASUK_ID", $reqId);
				$smref->setField("SM_REF_ID", $reqSmRefMultiId[$i]);
				$smref->insert();
			}
		}

		// tambahan khusus
		if ( ($reqStatusSurat == "DRAFT" && empty($reqKondisiStatusSurat)) || ($reqStatusSurat == "PARAF" && $reqKondisiStatusSurat == "UBAHDATADRAFTPARAF") )
		{
			$this->load->model("SuratMasukParaf");
			$surat_masuk_paraf = new SuratMasukParaf();
			$surat_masuk_paraf->setField("SURAT_MASUK_ID", $reqId);
			$surat_masuk_paraf->setField("LAST_CREATE_USER", $this->ID);
			$surat_masuk_paraf->deleteParent();

			// tambahan khusus
			if(!empty($reqSatuanKerjaIdParaf))
			{
				// $reqSatuanKerjaIdParaf= explode(",", $reqSatuanKerjaIdParaf);
				for ($i = 0; $i < count($reqSatuanKerjaIdParaf); $i++) {
					if ($reqSatuanKerjaIdParaf[$i] == "") {
					} else {
						$surat_masuk_paraf = new SuratMasukParaf();

						$adaData = $surat_masuk_paraf->getCountByParams(array("SURAT_MASUK_ID" => $reqId, "SATUAN_KERJA_ID_TUJUAN" => $reqSatuanKerjaIdParaf[$i]));

						if ($adaData == 0) 
						{
							// tambahan khusus
							$userbantu= new SatuanKerja();
							$userbantu->selectByParams(array(),-1,-1, " AND A.SATUAN_KERJA_ID = '".$reqSatuanKerjaIdParaf[$i]."'");
							$userbantu->firstRow();
							$userbantuuserid= $userbantu->getField("USER_BANTU");
							unset($userbantu);

							if(!empty($userbantuuserid))
							{
								$surat_masuk_paraf = new SuratMasukParaf();
								$surat_masuk_paraf->setField("SURAT_MASUK_ID", $reqId);
								$surat_masuk_paraf->setField("SATUAN_KERJA_ID_TUJUAN", $reqSatuanKerjaIdParaf[$i]);
								$surat_masuk_paraf->setField("LAST_CREATE_USER", $this->ID);
								$surat_masuk_paraf->insertbantu();
							}
				
							$surat_masuk_paraf->setField("SURAT_MASUK_ID", $reqId);
							$surat_masuk_paraf->setField("SATUAN_KERJA_ID_TUJUAN", $reqSatuanKerjaIdParaf[$i]);
							$surat_masuk_paraf->setField("LAST_CREATE_USER", $this->ID);
							$surat_masuk_paraf->insert();
							// echo $surat_masuk_paraf->query;exit;
						}
					}
				}
			}

			// tambahan khusus
			$userbantu= new SatuanKerja();
			$userbantu->selectByParams(array(),-1,-1, " AND A.SATUAN_KERJA_ID = '".$reqSatuanKerjaId."'");
			$userbantu->firstRow();
			$userbantuuserid= $userbantu->getField("USER_BANTU");
			unset($userbantu);

			if(!empty($userbantuuserid))
			{
				$surat_masuk_paraf = new SuratMasukParaf();
				$surat_masuk_paraf->setField("SURAT_MASUK_ID", $reqId);
				$surat_masuk_paraf->setField("SATUAN_KERJA_ID_TUJUAN", $reqSatuanKerjaId);
				$surat_masuk_paraf->setField("LAST_CREATE_USER", $this->ID);
				$surat_masuk_paraf->insertbantu();
			}

			// kondisi tukar data paralel apabila urutan user bantu lebih besar
			$checkparaf= new SuratMasukParaf();
			$checkparaf->selectByParams(array(), -1, -1, " AND A.STATUS_BANTU IS NULL AND KONDISI_PARAF = 'PARALEL' AND A.SURAT_MASUK_ID = ".$reqId);
			$checkparaf->firstRow();
			$checkparafuserdireksi= $checkparaf->getField("NO_URUT");

			$checkparaf= new SuratMasukParaf();
			$checkparaf->selectByParams(array(), -1, -1, " AND A.STATUS_BANTU = 1 AND KONDISI_PARAF = 'PARALEL' AND A.SURAT_MASUK_ID = ".$reqId);
			$checkparaf->firstRow();
			$checkparafuserbantu= $checkparaf->getField("NO_URUT");

			// echo $checkparafuserdireksi."xxx".$checkparafuserbantu;exit;

			if(!empty($checkparafuserdireksi) && !empty($checkparafuserbantu) && $checkparafuserbantu > $checkparafuserdireksi)
			{
				$checkparaf= new SuratMasukParaf();
				$checkparaf->setField("SURAT_MASUK_ID", $reqId);
				$checkparaf->setField("NO_URUT_DIREKSI", $checkparafuserbantu);
				$checkparaf->setField("NO_URUT_BANTU", $checkparafuserdireksi);
				$checkparaf->tukarurutanparalel();
			}
		}

		if ($reqStatusSurat == "DRAFT") {
			if($reqKondisiStatusSurat == "UBAHDATAPARAF" || $reqKondisiStatusSurat == "UBAHDATAREVISI")
			{
				$inforeturninfo= "Naskah berhasil disimpan.";
			}
			else
			{
				$inforeturninfo= "Naskah berhasil disimpan sebagai DRAFT.";
			}

			if ($this->CALLER == "MOBILE") {
				$arrReturn = array('status' => 'success', 'message' => $reqId . '-'.$inforeturninfo, 'code' => 200);
				echo json_encode($arrReturn);
			} else {
				echo $reqId."-".$inforeturninfo;
				return;
			}
		} elseif ($reqStatusSurat == "VALIDASI") {
			if ($this->CALLER == "MOBILE") {
				$arrReturn = array('status' => 'success', 'message' => $reqId . '-Naskah berhasil disimpan', 'code' => 200);
				echo json_encode($arrReturn);
			} else {
				echo $reqId . "-Naskah berhasil disimpan";
				return;
			}
		} elseif ($reqStatusSurat == "REVISI") {
			$surat_masuk->setField("SURAT_MASUK_ID", $reqId);
			$surat_masuk->setField("REVISI", $reqRevisi);
			$surat_masuk->setField("SATUAN_KERJA_ID_ASAL", $reqSatuanKerjaId);
			$surat_masuk->setField("REVISI_BY", $this->USERNAME);
			if ($surat_masuk->revisi()) {
				$this->revisi_notifikasi($reqId);

				if ($this->CALLER == "MOBILE") {
					$arrReturn = array('status' => 'success', 'message' => 'Naskah telah dikembalikan ke pembuat surat', 'code' => 200);
					echo json_encode($arrReturn);
				} else {
					echo "Naskah telah dikembalikan ke pembuat surat";
					return;
				}
			}
		}

		// tambahan khusus
		// if ($reqStatusSurat == "VALIDASI" || $reqStatusSurat == "PARAF")
		// {
			if(!empty($reqRevisi))
			{
				$set= new SuratMasuk();
				$set->setField("SURAT_MASUK_ID", $reqId);
				$set->setField("REVISI", $reqRevisi);
				$set->setField("REVISI_BY", $this->USERNAME);
				$set->revisiinfo();
			}
		// }

		/* CEK DULU AKSES SURAT */
		$surat_akses = new SuratMasuk();
		// tambahan khusus
		// $aksesSurat = $surat_akses->getAksesSurat(array("A.SURAT_MASUK_ID" => $reqId, "A.USER_ID" => $this->ID));

		// kalau pembuat sendiri adalah pemaraf, maka
		// $aksesSurat = $surat_akses->getAksesSurat(array("A.SURAT_MASUK_ID" => $reqId, "A.USER_ID" => $this->ID, "A.AKSES" => 'PEMARAF'));
		$aksesSurat = $surat_akses->getAksesSurat(array("A.SURAT_MASUK_ID" => $reqId, "A.USER_ID" => $this->ID), " AND A.AKSES IN ('PEMARAF', 'PLHPEMARAF')");
		// if ($aksesSurat == "PEMARAF" && $reqStatusSurat !== "PARAF")
		// if ($aksesSurat == "PEMARAF")
		// if( ($aksesSurat == "PEMARAF" && $reqStatusSurat !== "PARAF") || $aksesSurat == "PLHPEMARAF")
		if( ($aksesSurat == "PEMARAF" && $reqStatusSurat !== "PARAF") || ($aksesSurat == "PEMARAF" && $reqStatusSurat == "PARAF") || $aksesSurat == "PLHPEMARAF")
		{
			$this->paraf_proses($reqId, "APPROVAL");
			return;
		}
		/* JIKA BUKAN DRAFT YANG HANDLE ADALAH POSTING_PROSES */
		$this->posting_proses($reqId);
	}

	function paraf()
	{
		$reqId= $this->input->get('reqId');

		$this->paraf_proses($reqId, "PARAF");
	}

	function logparaf()
	{
		$reqId= $this->input->post('reqId');
		$reqInfoLog= $this->input->post('reqInfoLog');

		$this->paraf_proses($reqId, "PARAF", $reqInfoLog);
	}

	function paraf_proses($reqId, $reqSource, $reqInfoLog= "")
	{

		$this->load->model("SuratMasuk");
		$surat_masuk = new SuratMasuk();

		$kodeParaf = "PARAF" . $this->ID . generateZero($reqId, 6) . date("dmYHis");

		$surat_masuk->setField("SURAT_MASUK_ID", $reqId);
		$surat_masuk->setField("KODE_PARAF", $kodeParaf);
		$surat_masuk->setField("USER_ID", $this->ID);
		$surat_masuk->setField("LAST_UPDATE_USER", $this->USERNAME);

		if ($surat_masuk->paraf()) {

			// simpan log data, kalau ada data varible reqInfoLog
			if(!empty($reqInfoLog))
			{
				$slog= new SuratMasuk();
				$slog->setField("SURAT_MASUK_ID", $reqId);
				$slog->setField("STATUS_SURAT", "PARAF");
				$slog->setField("INFORMASI", $this->JABATAN." (".$this->NAMA.")");
				$slog->setField("CATATAN", $reqInfoLog);
				$slog->setField("LAST_CREATE_USER", $this->ID);
				$slog->insertlog();
				unset($slog);
			}

			/* GENERATE QRCODE */
			include_once("libraries/phpqrcode/qrlib.php");

			$FILE_DIR = "uploads/" . $reqId . "/";
			makedirs($FILE_DIR);
			$filename = $FILE_DIR . $kodeParaf . '.png';
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
		$reqRevisi= $this->input->post('reqRevisi');
		$reqMode= $this->input->post('reqMode');
		$reqSatuanKerjaIdAsal= $this->input->post('reqSatuanKerjaIdAsal');
		$reqInfoLog= $this->input->post('reqInfoLog');

		$this->load->model("SuratMasuk");
		$surat_masuk = new SuratMasuk();
		

		if ($this->USER_GROUP == "TATAUSAHA") 
		{
			$reqSatuanKerjaIdAsal = $reqSatuanKerjaIdAsal;
		} 
		else 
		{
			if($reqMode == "manual"){}
			else
			$reqSatuanKerjaIdAsal = $this->SATUAN_KERJA_ID_ASAL;
		}

		$surat_masuk->setField("SURAT_MASUK_ID", $reqId);
		$surat_masuk->setField("REVISI", $reqRevisi);
		$surat_masuk->setField("SATUAN_KERJA_ID_ASAL", $reqSatuanKerjaIdAsal);
		$surat_masuk->setField("REVISI_BY", $this->USERNAME);
		if ($surat_masuk->revisi()) 
		{
			// simpan log data, kalau ada data varible reqInfoLog
			if(!empty($reqInfoLog))
			{
				$slog= new SuratMasuk();
				$slog->setField("SURAT_MASUK_ID", $reqId);
				$slog->setField("STATUS_SURAT", "REVISI");
				$slog->setField("INFORMASI", $this->JABATAN." (".$this->NAMA.")");
				$slog->setField("CATATAN", $reqInfoLog);
				$slog->setField("LAST_CREATE_USER", $this->ID);
				$slog->insertlog();
				unset($slog);
			}

			$this->revisi_notifikasi($reqId);

			if ($this->CALLER == "MOBILE") {
				$arrReturn = array('status' => 'success', 'message' => 'Naskah berhasil dikembalikan', 'code' => 200);
				echo json_encode($arrReturn);
			} else {
				echo "Naskah berhasil dikembalikan";
				return;
			}
		}
	}


	function approval_vp()
	{
		$reqId	= $this->input->get('reqId');

		$reqRevisi= $this->input->get('reqRevisi');
		// echo $reqRevisi;exit;

		$reqNomor = $this->db->query("SELECT NOMOR FROM SURAT_MASUK WHERE SURAT_MASUK_ID = '" . $reqId . "' ")->row()->nomor;

		$this->load->model("SuratMasuk");
		$surat_masuk = new SuratMasuk();
		$surat_masuk->setField("SURAT_MASUK_ID", $reqId);
		$surat_masuk->setField("STATUS_SURAT", "VALIDASI");
		$surat_masuk->approvalSurat();

		if(!empty($reqRevisi))
		{
			$reqRevisi= json_decode($reqRevisi, true);
			// echo $reqRevisi;exit;

			$set= new SuratMasuk();
			$set->setField("SURAT_MASUK_ID", $reqId);
			$set->setField("REVISI", $reqRevisi);
			$set->setField("REVISI_BY", $this->USERNAME);
			$set->revisiinfo();
		}

		// if($reqNomor == ""){

		// 	if($this->CALLER == "MOBILE")
		// 	{
		// 		$arrReturn = array('status' => 'success', 'message' => 'Naskah berhasil diteruskan ke Sekertaris', 'code' => 200);
		// 		echo json_encode($arrReturn);
		// 	}
		// 	else{
		// 		echo "Naskah berhasil diteruskan ke Sekertaris";
		// 	}
		// }
		// else{
		// 	$this->posting_proses($reqId, "POSTING");
		// }


		if ($this->CALLER == "MOBILE") {
			$arrReturn = array('status' => 'success', 'message' => 'Naskah berhasil diteruskan ke Sekretaris', 'code' => 200);
			echo json_encode($arrReturn);
		} else {
			echo "Naskah berhasil diteruskan ke Sekretaris";
		}
	}

	function posting()
	{
		$reqId	= $this->input->get('reqId');
		$this->posting_proses($reqId, "POSTING");
	}

	function logposting()
	{
		$reqId= $this->input->post('reqId');
		$reqInfoLog= $this->input->post('reqInfoLog');
		$reqInfoNomor= $this->input->post('reqInfoNomor');

		if(!empty($reqInfoNomor) && !empty($reqInfoLog))
		{
			$this->load->model("SuratMasuk");

			$checksurat= new SuratMasuk();
			$checksurat->selectByParamsCheckNomor("CHECK", $reqId, $reqInfoNomor, dateToDbCheck($reqInfoLog));
			$checksurat->firstRow();
			$valicheck= $checksurat->getField("INFO_NOMOR_SURAT");
			// echo $valicheck;exit;
			unset($checksurat);

			if($valicheck == "1")
			{
				$checksurat= new SuratMasuk();
				$checksurat->selectByParamsCheckNomor("SAVE", $reqId, $reqInfoNomor, dateToDbCheck($reqInfoLog));
				$checksurat->firstRow();
				$valicheck= $checksurat->getField("INFO_NOMOR_SURAT");
				// echo $valicheck;exit;
				unset($checksurat);
			}
			else
			{
				echo "0";
				exit;
			}
		}
		
		$this->posting_proses($reqId, "POSTING", $reqInfoLog);
	}

	function posting_proses($reqId, $reqSource = "", $reqInfoLog= "")
	{
		// exit;
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
		$this->load->model("SatuanKerja");

		$checksurat= new SuratMasuk();
		$cheksuratpembuat= $checksurat->getStatusSurat(array("A.SURAT_MASUK_ID" => $reqId));
		// echo $cheksuratpembuat."xx".$reqInfoLog;exit;

		$checksurat= "";
		if($cheksuratpembuat == "PEMBUAT")
		{
			$checksurat= new SuratMasuk();
			$checksurat->selectByParamsCheckNomor("GET", $reqId, "", dateToDbCheck($reqInfoLog));
			$checksurat->firstRow();
			$valicheck= $checksurat->getField("INFO_NOMOR_SURAT");
			// echo $valicheck;exit;
			unset($checksurat);
		}

		$surat_masuk = new SuratMasuk();
		$surat_masuk->setField("SURAT_MASUK_ID", $reqId);
		$surat_masuk->setField("NOMOR", $valicheck);
		$surat_masuk->setField("SATUAN_KERJA_ID_ASAL", $this->SATUAN_KERJA_ID_ASAL);
		$surat_masuk->setField("PEMARAF_ID", $this->ID);
		$surat_masuk->setField("FIELD", "STATUS_SURAT");
		$surat_masuk->setField("FIELD_VALUE", "POSTING"); // apabila yang bikin staff nya sama trigger sudah otomatis diganti VALIDASI
		$surat_masuk->setField("LAST_UPDATE_USER", $this->USERNAME);
		$surat_masuk->setField("USER_ID", $this->ID);

		$simpaninfo= "";
		if(empty($valicheck))
		{
			if ($surat_masuk->updateByFieldValidasi()) 
			{
				$simpaninfo= "1";
			}
		}
		else
		{
			if ($surat_masuk->updateByFieldValidasiNomor()) 
			{
				$simpaninfo= "1";
			}
		}

		// if ($surat_masuk->updateByFieldValidasi()) 
		if($simpaninfo == "1")
		{

			$statusSurat = $surat_masuk->getStatusSurat(array("A.SURAT_MASUK_ID" => $reqId));

			$tanggalapprovalpembuat= "";
			if($cheksuratpembuat == "PEMBUAT" && $statusSurat == "POSTING")
			{
				// tambahan khusus
				$setcek= new SuratMasuk();
				$setcek->selectByParams(array(),-1,-1, " AND A.SURAT_MASUK_ID = '".$reqId."'");
				$setcek->firstRow();
				$infoceksatuankerjaidasal= $setcek->getField("SATUAN_KERJA_ID_ASAL");
				$infocekuserid= $setcek->getField("USER_ID");
				unset($setcek);

				$setcek= new SatuanKerja();
				$setcek->selectByParams(array(),-1,-1, " AND A.SATUAN_KERJA_ID = '".$infoceksatuankerjaidasal."'");
				$setcek->firstRow();
				$infocekuserbantuid= $setcek->getField("USER_BANTU");
				unset($userbantu);

				// kalau pembuat user bantu maka qr code
				if($infocekuserid !== $infocekuserbantuid)
					$tanggalapprovalpembuat= $reqInfoLog;
			}
			// echo $tanggalapprovalpembuat;exit;

			// simpan log data, kalau ada data varible reqInfoLog
			if(!empty($reqInfoLog))
			{
				$slog= new SuratMasuk();
				$slog->setField("SURAT_MASUK_ID", $reqId);
				if($statusSurat == "PEMBUAT")
					$slog->setField("STATUS_SURAT", "PEMBUAT");
				else
					$slog->setField("STATUS_SURAT", "PARAF");
				$slog->setField("INFORMASI", $this->JABATAN." (".$this->NAMA.")");
				$slog->setField("CATATAN", $reqInfoLog);
				$slog->setField("LAST_CREATE_USER", $this->ID);
				$slog->insertlog();
				unset($slog);
			}

			if ($statusSurat == "VALIDASI") {
				$this->validasi_notifikasi($reqId);
				if ($reqSource == "PARAF") {
					if ($this->CALLER == "MOBILE") {
						$arrReturn = array('status' => 'success', 'message' => 'Naskah berhasil diposting ke atasan untuk validasi', 'code' => 200);
						echo json_encode($arrReturn);
					} else {
						echo "Naskah berhasil diposting ke atasan untuk validasi";
					}
				} elseif ($reqSource == "APPROVAL") {
					if ($this->CALLER == "MOBILE") {
						$arrReturn = array('status' => 'success', 'message' => 'approval-Naskah berhasil diposting ke atasan untuk validasi', 'code' => 200);
						echo json_encode($arrReturn);
					} else {
						echo "approval-Naskah berhasil diposting ke atasan untuk validasi";
						return;
					}
				} else {
					if ($this->CALLER == "MOBILE") {
						$arrReturn = array('status' => 'success', 'message' => 'draft-Naskah berhasil diposting ke atasan untuk validasi', 'code' => 200);
						echo json_encode($arrReturn);
					} else {
						echo "draft-Naskah berhasil diposting ke atasan untuk validasi";
						return;
					}
				}
			} elseif ($statusSurat == "PARAF") {
				$this->paraf_notifikasi($reqId);
				if ($reqSource == "PARAF") {
					if ($this->CALLER == "MOBILE") {
						$arrReturn = array('status' => 'success', 'message' => 'Naskah berhasil diparaf', 'code' => 200);
						echo json_encode($arrReturn);
					} else {
						echo "Naskah berhasil diparaf";
					}
				} else {
					if ($this->CALLER == "MOBILE") {
						$arrReturn = array('status' => 'success', 'message' => 'draft-Naskah berhasil diposting ke pemaraf sebelum diposting ke tujuan', 'code' => 200);
						echo json_encode($arrReturn);
					} else {
						echo "draft-Naskah berhasil diposting ke pemaraf sebelum diposting ke tujuan";
						return;
					}
				}
			}
			/* JIKA PENERBIT NOMOR ADALAH TU DAN BELUM DINOMORKAN!! */ elseif ($statusSurat == "TU-NOMOR") {

				/* SISIPKAN GENERATE QRCODE PENANDA TANGAN ASLI APABILA POSTING */
				$kodeParaf  = $surat_masuk->getTtdSurat(array("A.SURAT_MASUK_ID" => $reqId));
				include_once("libraries/phpqrcode/qrlib.php");

				$FILE_DIR = "uploads/" . $reqId . "/";
				makedirs($FILE_DIR);
				$filename = $FILE_DIR . $kodeParaf . '.png';
				$errorCorrectionLevel = 'L';
				$matrixPointSize = 5;
				QRcode::png($kodeParaf, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
				/* END OF GENERATE QRCODE */

				if ($reqSource == "POSTING") {
					if ($this->CALLER == "MOBILE") {
						$arrReturn = array('status' => 'success', 'message' => 'Naskah berhasil diposting ke Tata Usaha', 'code' => 200);
						echo json_encode($arrReturn);
					} else {
						echo "Naskah berhasil diposting ke Tata Usaha";
					}
				} else {
					if ($this->CALLER == "MOBILE") {
						$arrReturn = array('status' => 'success', 'message' => 'Naskah berhasil diposting ke Tata Usaha', 'code' => 200);
						echo json_encode($arrReturn);
					} else {
						echo "sent-Naskah berhasil diposting ke Tata Usaha";
						return;
					}
				}
			} 
			elseif ($statusSurat == "PEMBUAT") {
				// $this->pembuat_notifikasi($reqId);
				if ($this->CALLER == "MOBILE") {
					$arrReturn = array('status' => 'success', 'message' => 'pembuat-Naskah berhasil diposting ke pembuat, untuk entry date', 'code' => 200);
					echo json_encode($arrReturn);
				} else {
					echo "pembuat-Naskah berhasil diposting ke pembuat, untuk entry date";
					return;
				}
			}
			else {

				$targetSurat = $surat_masuk->getTarget(array("A.SURAT_MASUK_ID" => $reqId));

				if ($targetSurat == "EKSTERNAL") {

					$this->load->model("SuratMasuk");
					$surat_masuk = new SuratMasuk();
					$surat_masuk->setField("SURAT_MASUK_ID", $reqId);
					$surat_masuk->setField("FIELD", "STATUS_SURAT");
					$surat_masuk->setField("FIELD_VALUE", "TU-OUT"); // apabila yang bikin staff nya sama trigger sudah otomatis diganti VALIDASI
					$surat_masuk->setField("LAST_UPDATE_USER", $this->USERNAME);
					$surat_masuk->updateByField();
					$statusSurat = "TATAUSAHA";
				}

				// tambahan khusus
				$this->load->model("SuratMasuk");

				$surat_masuk = new SuratMasuk();
				$surat_masuk->setField("SURAT_MASUK_ID", $reqId);
				$surat_masuk->setField("FIELD", "APPROVAL_QR_DATE");
				if(!empty($tanggalapprovalpembuat))
				{
					$surat_masuk->setField("FIELD_VALUE", dateToDbCheck($tanggalapprovalpembuat));
					$surat_masuk->updateByFieldValueTime();
				}
				else
				{
					$surat_masuk->updateByFieldTime();
				}
				// echo $surat_masuk->query;exit;

				/* SISIPKAN GENERATE QRCODE PENANDA TANGAN ASLI APABILA POSTING */
				// $kodeParaf  = $surat_masuk->getTtdSurat(array("A.SURAT_MASUK_ID" => $reqId));
				$getinfottd = new SuratMasuk();
				$getinfottd->selectByParamsGetInfoTtdSurat(array("A.SURAT_MASUK_ID" => $reqId));
				$getinfottd->firstRow();

				// tambahan khusus
				$pesanQrCode = "ID: ".$getinfottd->getField("TTD_KODE")."\n";
				$pesanQrCode.= "ApprovedBy: ".$getinfottd->getField("APPROVED_BY")."\n";
				$pesanQrCode.= "Nomor Surat: ".$getinfottd->getField("NOMOR")."\n";

				$qrdate= $getinfottd->getField("APPROVAL_QR_DATE");
				if(!empty($qrdate))
				{
					$pesanQrCode.= "Tanggal Surat: ".str_replace("-", "/", dateTimeToPageCheck($qrdate));
				}

				include_once("libraries/phpqrcode/qrlib.php");

				$FILE_DIR = "uploads/" . $reqId . "/";
				makedirs($FILE_DIR);
				$filename = $FILE_DIR . $getinfottd->getField("TTD_KODE") . '.png';
				$errorCorrectionLevel = 'L';
				$matrixPointSize = 5;
				QRcode::png($pesanQrCode, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
				/* END OF GENERATE QRCODE */



				/* SEND TO GOOGLE CALENDAR */



				/* AMBIL JADWAL DI SURAT MASUK */


				/* AMBIL PEGAWAI DI DISPOSISI -> USER_ID RELASI KE PEGAWAI_ID UNTUK MENDAPATKAN EMAIL */

				/* SEND TO GOOGLE CALENDAR */

				/* AMBIL JADWAL DI SURAT MASUK */

				/* AMBIL PEGAWAI DI DISPOSISI -> USER_ID RELASI KE PEGAWAI_ID UNTUK MENDAPATKAN EMAIL */

				$surat_masuk->selectByParamsGoogleCalendar(array("SURAT_MASUK_ID" => $reqId));
				if ($surat_masuk->firstRow()) {
					$tanggal_kegiatan = $surat_masuk->getField("TANGGAL_KEGIATAN");
					$tanggal_kegiatan_akhir = $surat_masuk->getField("TANGGAL_KEGIATAN_AKHIR");
					$summary = $surat_masuk->getField("PERIHAL");
					$description = dropAllHtml($surat_masuk->getField("ISI"));

					$start_date = date('Y-m-d\TH:i:s', $tanggal_kegiatan) . "+07:00";
					$end_date = date('Y-m-d\TH:i:s', $tanggal_kegiatan_akhir) . "+07:00";

					$surat_masuk_disposisi = new SuratMasuk();
					$surat_masuk_disposisi->selectByParamsEmailDisposisi(array("SURAT_MASUK_ID" => $reqId));
					$arr_email_tujuan = array();

					while ($surat_masuk_disposisi->nextRow()) {
						$email = $surat_masuk_disposisi->getField("EMAIL");
						if ($email <> "")
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
					if (count($arr_email_tujuan) > 0) {
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



				if ($statusSurat == "TATAUSAHA") {
					if ($reqSource == "POSTING") {
						if ($this->CALLER == "MOBILE") {
							$arrReturn = array('status' => 'success', 'message' => 'Naskah berhasil diposting ke Tata Usaha', 'code' => 200);
							echo json_encode($arrReturn);
						} else {
							echo "Naskah berhasil diposting ke Tata Usaha";
						}
					} else {
						if ($this->CALLER == "MOBILE") {
							$arrReturn = array('status' => 'success', 'message' => 'Naskah berhasil diposting ke Tata Usaha', 'code' => 200);
							echo json_encode($arrReturn);
						} else {
							echo "sent-Naskah berhasil diposting ke Tata Usaha";
							return;
						}
					}
				} else {
					$this->posting_notifikasi($reqId);
					if ($reqSource == "POSTING") {
						if ($this->CALLER == "MOBILE") {
							$arrReturn = array('status' => 'success', 'message' => 'Naskah berhasil diposting', 'code' => 200);
							echo json_encode($arrReturn);
						} else {
							echo "Naskah berhasil diposting";
						}
					} else {
						if ($this->CALLER == "MOBILE") {
							$arrReturn = array('status' => 'success', 'message' => 'Naskah berhasil diposting', 'code' => 200);
							echo json_encode($arrReturn);
						} else {
							echo "sent-Naskah berhasil diposting";
							return;
						}
					}
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
																														  X.SURAT_MASUK_ID = '" . $reqId . "' 
																						  ) ");
		while ($user_login_mobile->nextRow()) {
			$row = array();
			$row['to'] = $user_login_mobile->getField("TOKEN_FIREBASE");
			$row['data']["title"] = "[PARAF]" . $reqTitle;
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
																														  X.SURAT_MASUK_ID = '" . $reqId . "' AND 
																														  X.STATUS_SURAT IN ('REVISI') 
																						  ) ");
		while ($user_login_mobile->nextRow()) {
			$row = array();
			$row['to'] = $user_login_mobile->getField("TOKEN_FIREBASE");
			$row['data']["title"] = "[DRAFT]" . $reqTitle;
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
																														  X.SURAT_MASUK_ID = '" . $reqId . "' 
																						  ) ");
		while ($user_login_mobile->nextRow()) {
			$row = array();
			$row['to'] = $user_login_mobile->getField("TOKEN_FIREBASE");
			$row['data']["title"] = "[TERPARAF]" . $reqTitle;
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
																														  X.SURAT_MASUK_ID = '" . $reqId . "' AND 
																														  X.STATUS_SURAT IN ('VALIDASI') 
																						  ) ");
		while ($user_login_mobile->nextRow()) {
			$row = array();
			$row['to'] = $user_login_mobile->getField("TOKEN_FIREBASE");
			$row['data']["title"] = "[DRAFT]" . $reqTitle;
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
																														  X.SURAT_MASUK_ID = '" . $reqId . "' AND 
																														  X.STATUS_DISPOSISI IN ('TUJUAN', 'TEMBUSAN') 
																						  ) ");
		while ($user_login_mobile->nextRow()) {
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
		if ($surat_masuk->delete())
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
		while ($surat_masuk->nextRow()) {
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
		$FILE_DIR = "uploads/" . $reqId . "/";
		makedirs($FILE_DIR);

		$reqLinkFileNaskah 		= $_FILES["reqLinkFileNaskah"];
		$reqLinkFileNaskahTemp	=  $this->input->post("reqLinkFileNaskahTemp");


		if ($reqLinkFileNaskah["name"] == "" && $reqLinkFileNaskahTemp == "") {
			echo "0-Upload naskah yang sudah ditandatangani terlebih dahulu.";
			return;

			if ($this->CALLER == "MOBILE") {
				$arrReturn = array('status' => 'success', 'message' => 'Naskah berhasil diposting', 'code' => 200);
				echo json_encode($arrReturn);
			} else {
				echo "sent-Naskah berhasil diposting";
				return;
			}
		} else {
			$reqJenis = "NASKAHTTD" . generateZero($reqId, 5);
			$renameFileNaskah = $reqJenis . date("Ymdhis") . rand() . "." . getExtension($reqLinkFileNaskah['name']);

			if ($file->uploadToDir('reqLinkFileNaskah', $FILE_DIR, $renameFileNaskah))
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


			if ($reqMediaPengiriman == "1")
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
		$FILE_DIR = "uploads/" . $reqId . "/";
		makedirs($FILE_DIR);

		$reqLinkFileNaskah 		= $_FILES["reqLinkFileNaskah"];
		$reqLinkFileNaskahTemp	=  $this->input->post("reqLinkFileNaskahTemp");


		if ($reqLinkFileNaskah["name"] == "" && $reqLinkFileNaskahTemp == "" && $reqJenisTTD == "BASAH") {
			if ($this->CALLER == "MOBILE") {
				$arrReturn = array('status' => 'success', 'message' => 'Upload naskah yang sudah ditandatangani terlebih dahulu', 'code' => 200);
				echo json_encode($arrReturn);
			} else {
				echo "0-Upload naskah yang sudah ditandatangani terlebih dahulu";
				return;
			}
		} else {
			if ($reqJenisTTD == "BASAH") {
				$reqJenis = "NASKAHTTD" . generateZero($reqId, 5);
				$renameFileNaskah = $reqJenis . date("Ymdhis") . rand() . "." . getExtension($reqLinkFileNaskah['name']);

				if ($file->uploadToDir('reqLinkFileNaskah', $FILE_DIR, $renameFileNaskah))
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
			} else {
				/* SISIPKAN GENERATE QRCODE PENANDA TANGAN ASLI APABILA POSTING */
				$surat_masuk = new SuratMasuk();
				$kodeParaf  = $surat_masuk->getTtdSurat(array("A.SURAT_MASUK_ID" => $reqId));
				include_once("libraries/phpqrcode/qrlib.php");

				$getinfottd = new SuratMasuk();
				$getinfottd->selectByParamsGetInfoTtdSurat(array("A.SURAT_MASUK_ID" => $reqId));
				$getinfottd->firstRow();

				// tambahan khusus
				$pesanQrCode = "ID: ".$getinfottd->getField("TTD_KODE")."\n";
				$pesanQrCode.= "ApprovedBy: ".$getinfottd->getField("APPROVED_BY")."\n";
				$pesanQrCode.= "Nomor Surat: ".$getinfottd->getField("NOMOR")."\n";

				$qrdate= $getinfottd->getField("APPROVAL_QR_DATE");
				if(!empty($qrdate))
				{
					$pesanQrCode.= "Tanggal Surat: ".str_replace("-", "/", dateTimeToPageCheck($qrdate));
				}

				$FILE_DIR = "uploads/" . $reqId . "/";
				makedirs($FILE_DIR);
				$filename = $FILE_DIR . $getinfottd->getField("TTD_KODE") . '.png';
				$errorCorrectionLevel = 'L';
				$matrixPointSize = 5;
				QRcode::png($pesanQrCode, $filename, $errorCorrectionLevel, $matrixPointSize, 2);

				/* END OF GENERATE QRCODE */

				/*  UPDATE KE SURAT_PDF */
				$surat_pdf = new SuratMasuk();
				$surat_pdf->setField("FIELD", "TTD_KODE");
				$surat_pdf->setField("FIELD_VALUE", $kodeParaf);
				$surat_pdf->setField("LAST_UPDATE_USER", $this->ID);
				$surat_pdf->setField("SURAT_MASUK_ID", $reqId);
				$surat_pdf->updateByField();
			}

			if ($reqMediaPengiriman == "1") {
				$reqStatusSurat = "TU-IN";
			} else {
				$reqStatusSurat = "TU-SENT";
			}

			/* UPDATE PENGIRIMAN */
			$surat_masuk = new SuratMasuk();
			$surat_masuk->setField("ARSIP_ID", $reqArsipId);
			$surat_masuk->setField("ARSIP", $reqArsip);
			$surat_masuk->setField("MEDIA_PENGIRIMAN_ID", $reqMediaPengiriman);
			$surat_masuk->setField("STATUS_SURAT", $reqStatusSurat);
			$surat_masuk->setField("LAST_UPDATE_USER", $this->ID);
			$surat_masuk->setField("SURAT_MASUK_ID", $reqId);
			$surat_masuk->kirimSuratKeluar();

			if ($this->CALLER == "MOBILE") {
				$arrReturn = array('status' => 'success', 'message' => 'Naskah berhasil diproses', 'code' => 200);
				echo json_encode($arrReturn);
			} else {
				echo "1-Naskah berhasil diproses";
			}
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
		$FILE_DIR = "uploads/" . $reqId . "/";
		makedirs($FILE_DIR);

		$reqLinkFileNaskah 		= $_FILES["reqLinkFileNaskah"];
		$reqLinkFileNaskahTemp	=  $this->input->post("reqLinkFileNaskahTemp");


		if ($reqLinkFileNaskah["name"] == "" && $reqLinkFileNaskahTemp == "" && $reqJenisTTD == "BASAH") {
			if ($this->CALLER == "MOBILE") {
				$arrReturn = array('status' => 'success', 'message' => 'Upload naskah yang sudah ditandatangani terlebih dahulu', 'code' => 200);
				echo json_encode($arrReturn);
			} else {
				echo "0-Upload naskah yang sudah ditandatangani terlebih dahulu";
				return;
			}
		} else {
			if ($reqJenisTTD == "BASAH") {
				$reqJenis = "NASKAHTTD" . generateZero($reqId, 5);
				$renameFileNaskah = $reqJenis . date("Ymdhis") . rand() . "." . getExtension($reqLinkFileNaskah['name']);

				if ($file->uploadToDir('reqLinkFileNaskah', $FILE_DIR, $renameFileNaskah))
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
			} else {

				/* SISIPKAN GENERATE QRCODE PENANDA TANGAN ASLI APABILA POSTING */
				$surat_masuk = new SuratMasuk();
				$kodeParaf  = $surat_masuk->getTtdSurat(array("A.SURAT_MASUK_ID" => $reqId));
				include_once("libraries/phpqrcode/qrlib.php");

				// tambahan khusus
				$this->load->model("SuratMasuk");
				$surat_masuk = new SuratMasuk();
				$surat_masuk->setField("SURAT_MASUK_ID", $reqId);
				$surat_masuk->setField("FIELD", "APPROVAL_QR_DATE");
				$surat_masuk->updateByFieldTime();

				// $kodeParaf  = $surat_masuk->getTtdSurat(array("A.SURAT_MASUK_ID" => $reqId));
				// $FILE_DIR = "uploads/" . $reqId . "/";
				// makedirs($FILE_DIR);
				// $filename = $FILE_DIR . $kodeParaf . '.png';
				// $errorCorrectionLevel = 'L';
				// $matrixPointSize = 5;
				// QRcode::png($kodeParaf, $filename, $errorCorrectionLevel, $matrixPointSize, 2);

				$getinfottd = new SuratMasuk();
				$getinfottd->selectByParamsGetInfoTtdSurat(array("A.SURAT_MASUK_ID" => $reqId));
				$getinfottd->firstRow();

				// tambahan khusus
				$pesanQrCode = "ID: ".$getinfottd->getField("TTD_KODE")."\n";
				$pesanQrCode.= "ApprovedBy: ".$getinfottd->getField("APPROVED_BY")."\n";
				$pesanQrCode.= "Nomor Surat: ".$getinfottd->getField("NOMOR")."\n";

				$qrdate= $getinfottd->getField("APPROVAL_QR_DATE");
				if(!empty($qrdate))
				{
					$pesanQrCode.= "Tanggal Surat: ".str_replace("-", "/", dateTimeToPageCheck($qrdate));
				}

				$FILE_DIR = "uploads/" . $reqId . "/";
				makedirs($FILE_DIR);
				$filename = $FILE_DIR . $getinfottd->getField("TTD_KODE") . '.png';
				$errorCorrectionLevel = 'L';
				$matrixPointSize = 5;
				QRcode::png($pesanQrCode, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
				
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
			// $surat_masuk = new SuratMasuk();
			// $surat_masuk->setField("ARSIP_ID", $reqArsipId);
			// $surat_masuk->setField("ARSIP", $reqArsip);
			// $surat_masuk->setField("MEDIA_PENGIRIMAN_ID", $reqMediaPengiriman);
			// $surat_masuk->setField("STATUS_SURAT", $reqStatusSurat);
			// $surat_masuk->setField("LAST_UPDATE_USER", $this->ID);
			// $surat_masuk->setField("SURAT_MASUK_ID", $reqId);
			// $surat_masuk->kirimSuratKeluar();

			if ($this->CALLER == "MOBILE") {
				$arrReturn = array('status' => 'success', 'message' => 'Naskah berhasil diproses', 'code' => 200);
				echo json_encode($arrReturn);
			} else {
				echo "1-Naskah berhasil diproses";
			}
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

		if ($this->CALLER == "MOBILE") {
			$arrReturn = array('status' => 'success', 'message' => 'Naskah berhasil diteruskan', 'code' => 200);
			echo json_encode($arrReturn);
		} else {
			echo "Naskah berhasil diteruskan";
		}
	}

	function jsonstatus()
	{
		$this->load->model("SuratMasuk");
		$surat_masuk = new SuratMasuk();

		$reqStatusSurat= $this->input->get("reqStatusSurat");

		if($reqStatusSurat == "PARAF")
		{
			$aColumns = array("INFO_STATUS_TANGGAL", "INFO_NOMOR_SURAT", "PERIHAL", "INFO_STATUS", "PERSETUJUAN_INFO", "JUMLAH_STEP", "SURAT_MASUK_ID");
		}
		else
		{
			$aColumns = array("INFO_STATUS_TANGGAL", "INFO_NOMOR_SURAT", "PERIHAL", "INFO_STATUS", "SURAT_MASUK_ID");
		}
		$aColumnsAlias = $aColumns;

		/*
		 * Ordering
		 */
		if (isset($_GET['iSortCol_0'])) {
			$sOrder = " ORDER BY ";

			//Go over all sorting cols
			for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
				//If need to sort by current col
				if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
					//Add to the order by clause
					$sOrder .= $aColumnsAlias[intval($_GET['iSortCol_' . $i])];

					//Determine if it is sorted asc or desc
					if (strcasecmp(($_GET['sSortDir_' . $i]), "asc") == 0) {
						$sOrder .= " asc, ";
					} else {
						$sOrder .= " desc, ";
					}
				}
			}

			//Remove the last space / comma
			$sOrder = substr_replace($sOrder, "", -2);

			//Check if there is an order by clause
			if (trim($sOrder) == "ORDER BY A.SURAT_MASUK_ID asc") {
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
		if (isset($_GET['sSearch'])) {
			$sWhereGenearal = $_GET['sSearch'];
		} else {
			$sWhereGenearal = '';
		}

		if ($_GET['sSearch'] != "") {
			//Set a default where clause in order for the where clause not to fail
			//in cases where there are no searchable cols at all.
			$sWhere = " AND (";
			for ($i = 0; $i < count($aColumnsAlias) + 1; $i++) {
				//If current col has a search param
				if ($_GET['bSearchable_' . $i] == "true") {
					//Add the search to the where clause
					$sWhere .= $aColumnsAlias[$i] . " LIKE '%" . $_GET['sSearch'] . "%' OR ";
					$nWhereGenearalCount += 1;
				}
			}
			$sWhere = substr_replace($sWhere, "", -3);
			$sWhere .= ')';
		}

		/* Individual column filtering */
		$sWhereSpecificArray = array();
		$sWhereSpecificArrayCount = 0;
		for ($i = 0; $i < count($aColumnsAlias); $i++) {
			if ($_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {
				//If there was no where clause
				if ($sWhere == "") {
					$sWhere = "AND ";
				} else {
					$sWhere .= " AND ";
				}

				//Add the clause of the specific col to the where clause
				$sWhere .= $aColumnsAlias[$i] . " LIKE '%' || :whereSpecificParam" . $sWhereSpecificArrayCount . " || '%' ";

				//Inc sWhereSpecificArrayCount. It is needed for the bind var.
				//We could just do count($sWhereSpecificArray) - but that would be less efficient.
				$sWhereSpecificArrayCount++;

				//Add current search param to the array for later use (binding).
				$sWhereSpecificArray[] =  $_GET['sSearch_' . $i];
			}
		}

		//If there is still no where clause - set a general - always true where clause
		if ($sWhere == "") {
			$sWhere = " AND 1=1";
		}

		//Bind variables.
		if (isset($_GET['iDisplayStart'])) {
			$dsplyStart = $_GET['iDisplayStart'];
		} else {
			$dsplyStart = 0;
		}
		if (isset($_GET['iDisplayLength']) && $_GET['iDisplayLength'] != '-1') {
			$dsplyRange = $_GET['iDisplayLength'];
			if ($dsplyRange > (2147483645 - intval($dsplyStart))) {
				$dsplyRange = 2147483645;
			} else {
				$dsplyRange = intval($dsplyRange);
			}
		} else {
			$dsplyRange = 2147483645;
		}


		// $statement_privacy .= " AND A.JENIS_TUJUAN = '" . $reqJenisTujuan . "' ";
		//$statement_privacy .= " AND A.SATUAN_KERJA_ID_ASAL = '".$this->SATUAN_KERJA_ID_ASAL."' ";

		// if ($this->USER_GROUP == "SEKRETARIS")
		// 	$statement_privacy .= " AND A.PENERIMA_SURAT = '" . $this->SATUAN_KERJA_ID_ASAL . "' ";
		// elseif ($this->USER_GROUP == "TATAUSAHA")
		// 	$statement_privacy .= " AND A.PENERIMA_SURAT = '" . $this->CABANG_ID . "' ";
		// else
		// 	exit;

		$statement= " AND CASE WHEN A.TERPARAF = 1 AND A.JENIS_NASKAH_ID IN (8,17,18,19,20) THEN SM_INFO IN ('AKAN_DISETUJUI') ELSE SM_INFO IN ('AKAN_DISETUJUI', 'PEMBUAT') END";

		if(!empty($reqStatusSurat))
		{
			if($reqStatusSurat == "PARAF")
				$statement.= " AND CASE WHEN A.TERPARAF = 1 AND A.JENIS_NASKAH_ID IN (8,17,18,19,20) THEN A.STATUS_SURAT IN ('PARAF', 'VALIDASI', 'PEMBUAT') ELSE A.STATUS_SURAT IN ('PARAF', 'VALIDASI') END";
			else
				$statement.= " AND A.STATUS_SURAT = '".$reqStatusSurat."'";
		}

		$searchJson= " 
		AND 
		(
			UPPER(A.NO_AGENDA) LIKE '%" . strtoupper($_GET['sSearch']) . "%' OR 
			UPPER(A.NOMOR) LIKE '%" . strtoupper($_GET['sSearch']) . "%' OR
			UPPER(A.PERIHAL) LIKE '%" . strtoupper($_GET['sSearch']) . "%' OR 
			UPPER(A.INSTANSI_ASAL) LIKE '%" . strtoupper($_GET['sSearch']) . "%' 
		)";

		$allRecord = $surat_masuk->getCountByParamsStatus(array(), $this->ID, $statement);
		// echo $allRecord;exit;
		if ($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else
			$allRecordFilter =  $surat_masuk->getCountByParamsStatus(array(), $this->ID, $statement.$searchJson);

		$sOrder = " ORDER BY INFO_STATUS_TANGGAL DESC";
		$surat_masuk->selectByParamsStatus(array(), $dsplyRange, $dsplyStart, $this->ID, $statement.$searchJson, $sOrder);
		// echo $surat_masuk->query;exit;
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

		while ($surat_masuk->nextRow()) {
			$row = array();
			for ($i = 0; $i < count($aColumns); $i++) {
				if ($aColumns[$i] == "KETERANGAN")
					$row[] = truncate($surat_masuk->getField($aColumns[$i]), 2);
				elseif ($aColumns[$i] == "INFO_STATUS_TANGGAL")
					$row[] = getFormattedExtDateTimeCheck($surat_masuk->getField($aColumns[$i]));
				elseif ($aColumns[$i] == "PERSETUJUAN_INFOs")
				{
					$row[] = htmlentities($surat_masuk->getField($aColumns[$i]), ENT_QUOTES, 'UTF-8');
					// $row[] = str_replace("<b>", "<\b>", $surat_masuk->getField($aColumns[$i]));
				}
				elseif ($aColumns[$i] == "TERBACA" || $aColumns[$i] == "TERDISPOSISI" || $aColumns[$i] == "TERBALAS") {
					if ((int)$surat_masuk->getField($aColumns[$i]) > 0)
						$row[] = "<i class=\"fa fa-check fa-md\" aria-hidden=\"true\"></i>";
					else
						$row[] = "<i class=\"fa fa-close fa-md\" aria-hidden=\"true\"></i>";
				} else
					$row[] = $surat_masuk->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}

		// echo json_encode($output, JSON_UNESCAPED_UNICODE);
		echo json_encode($output);
	}

	function jsonpersetujuan()
	{
		$this->load->model("SuratMasuk");
		$surat_masuk = new SuratMasuk();

		$reqStatusSurat = $this->input->get("reqStatusSurat");

		$aColumns = array("INFO_STATUS_TANGGAL", "INFO_NOMOR_SURAT", "PERIHAL", "INFO_STATUS", "PERSETUJUAN_INFO", "JUMLAH_STEP", "SURAT_MASUK_ID");
		$aColumnsAlias = $aColumns;

		/*
		 * Ordering
		 */
		if (isset($_GET['iSortCol_0'])) {
			$sOrder = " ORDER BY ";

			//Go over all sorting cols
			for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
				//If need to sort by current col
				if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
					//Add to the order by clause
					$sOrder .= $aColumnsAlias[intval($_GET['iSortCol_' . $i])];

					//Determine if it is sorted asc or desc
					if (strcasecmp(($_GET['sSortDir_' . $i]), "asc") == 0) {
						$sOrder .= " asc, ";
					} else {
						$sOrder .= " desc, ";
					}
				}
			}

			//Remove the last space / comma
			$sOrder = substr_replace($sOrder, "", -2);

			//Check if there is an order by clause
			if (trim($sOrder) == "ORDER BY A.SURAT_MASUK_ID asc") {
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
		if (isset($_GET['sSearch'])) {
			$sWhereGenearal = $_GET['sSearch'];
		} else {
			$sWhereGenearal = '';
		}

		if ($_GET['sSearch'] != "") {
			//Set a default where clause in order for the where clause not to fail
			//in cases where there are no searchable cols at all.
			$sWhere = " AND (";
			for ($i = 0; $i < count($aColumnsAlias) + 1; $i++) {
				//If current col has a search param
				if ($_GET['bSearchable_' . $i] == "true") {
					//Add the search to the where clause
					$sWhere .= $aColumnsAlias[$i] . " LIKE '%" . $_GET['sSearch'] . "%' OR ";
					$nWhereGenearalCount += 1;
				}
			}
			$sWhere = substr_replace($sWhere, "", -3);
			$sWhere .= ')';
		}

		/* Individual column filtering */
		$sWhereSpecificArray = array();
		$sWhereSpecificArrayCount = 0;
		for ($i = 0; $i < count($aColumnsAlias); $i++) {
			if ($_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {
				//If there was no where clause
				if ($sWhere == "") {
					$sWhere = "AND ";
				} else {
					$sWhere .= " AND ";
				}

				//Add the clause of the specific col to the where clause
				$sWhere .= $aColumnsAlias[$i] . " LIKE '%' || :whereSpecificParam" . $sWhereSpecificArrayCount . " || '%' ";

				//Inc sWhereSpecificArrayCount. It is needed for the bind var.
				//We could just do count($sWhereSpecificArray) - but that would be less efficient.
				$sWhereSpecificArrayCount++;

				//Add current search param to the array for later use (binding).
				$sWhereSpecificArray[] =  $_GET['sSearch_' . $i];
			}
		}

		//If there is still no where clause - set a general - always true where clause
		if ($sWhere == "") {
			$sWhere = " AND 1=1";
		}

		//Bind variables.
		if (isset($_GET['iDisplayStart'])) {
			$dsplyStart = $_GET['iDisplayStart'];
		} else {
			$dsplyStart = 0;
		}
		if (isset($_GET['iDisplayLength']) && $_GET['iDisplayLength'] != '-1') {
			$dsplyRange = $_GET['iDisplayLength'];
			if ($dsplyRange > (2147483645 - intval($dsplyStart))) {
				$dsplyRange = 2147483645;
			} else {
				$dsplyRange = intval($dsplyRange);
			}
		} else {
			$dsplyRange = 2147483645;
		}


		// $statement_privacy .= " AND A.JENIS_TUJUAN = '" . $reqJenisTujuan . "' ";
		//$statement_privacy .= " AND A.SATUAN_KERJA_ID_ASAL = '".$this->SATUAN_KERJA_ID_ASAL."' ";

		// if ($this->USER_GROUP == "SEKRETARIS")
		// 	$statement_privacy .= " AND A.PENERIMA_SURAT = '" . $this->SATUAN_KERJA_ID_ASAL . "' ";
		// elseif ($this->USER_GROUP == "TATAUSAHA")
		// 	$statement_privacy .= " AND A.PENERIMA_SURAT = '" . $this->CABANG_ID . "' ";
		// else
		// 	exit;

		$searchJson= " 
		AND 
		(
			UPPER(A.PERIHAL) LIKE '%" . strtoupper($_GET['sSearch']) . "%' OR
			UPPER(A.PERIHAL) LIKE '%" . strtoupper($_GET['sSearch']) . "%'
		)";

		if($reqStatusSurat == "PERLU_PERSETUJUAN")
		{
			$statement= " AND 
			(
				(
					(
						A.USER_ATASAN_ID = '".$this->ID."' AND A.APPROVAL_DATE IS NULL AND COALESCE(NULLIF(A.NIP_ATASAN_MUTASI, ''), NULL) IS NULL
						AND TERPARAF IS NULL
						--AND CASE WHEN A.STATUS_SURAT = 'PEMBUAT' THEN A.USER_ATASAN_ID = A.USER_ID END
					)
					OR 
					(
						A.NIP_ATASAN_MUTASI = '".$this->ID."' AND A.APPROVAL_DATE IS NULL AND COALESCE(NULLIF(A.USER_ATASAN_ID, ''), NULL) IS NOT NULL
						AND TERPARAF IS NULL
						--AND CASE WHEN A.STATUS_SURAT = 'PEMBUAT' THEN A.USER_ATASAN_ID = A.USER_ID END
					)
				) 
				OR 
				(
					(
						A.USER_ATASAN_ID = '".$this->USER_GROUP.$this->ID."' AND A.APPROVAL_DATE IS NOT NULL AND COALESCE(NULLIF(A.NIP_ATASAN_MUTASI, ''), NULL) IS NULL
					)
					OR 
					(
						A.NIP_ATASAN_MUTASI = '".$this->USER_GROUP.$this->ID."' AND A.APPROVAL_DATE IS NOT NULL AND COALESCE(NULLIF(A.USER_ATASAN_ID, ''), NULL) IS NOT NULL
					)
				)
				OR 
				(
					A.USER_ID = '".$this->ID."'
					AND CASE WHEN A.USER_ID = '".$this->ID."' THEN TERPARAF IS NOT NULL ELSE TERPARAF IS NULL END
					AND A.STATUS_SURAT = 'PEMBUAT'
				)
			) AND A.STATUS_SURAT IN ('VALIDASI', 'PARAF', 'PEMBUAT')";

			$allRecord = $surat_masuk->getCountByParamsPersetujuan(array(), $this->ID, $statement);
			// echo $allRecord;exit;
			if ($_GET['sSearch'] == "")
				$allRecordFilter = $allRecord;
			else
				$allRecordFilter =  $surat_masuk->getCountByParamsPersetujuan(array(), $this->ID, $statement.$searchJson);

			$sOrder = " ORDER BY INFO_STATUS_TANGGAL DESC";
			$surat_masuk->selectByParamsPersetujuan(array(), $dsplyRange, $dsplyStart, $this->ID, $statement.$searchJson, $sOrder);
		}
		else
		{
			$statement= " 
			AND SM_INFO IN ('AKAN_DISETUJUI', 'NEXT_DISETUJUI') 
			AND CASE WHEN A.TERPARAF = 1 AND A.JENIS_NASKAH_ID IN (8,17,18,19,20) THEN A.STATUS_SURAT IN ('PARAF', 'VALIDASI', 'PEMBUAT') ELSE A.STATUS_SURAT IN ('PARAF', 'VALIDASI') END";

			$allRecord = $surat_masuk->getCountByParamsStatus(array(), $this->ID, $statement);
			// echo $allRecord;exit;
			if ($_GET['sSearch'] == "")
				$allRecordFilter = $allRecord;
			else
				$allRecordFilter =  $surat_masuk->getCountByParamsStatus(array(), $this->ID, $statement.$searchJson);

			$sOrder = " ORDER BY INFO_STATUS_TANGGAL DESC";
			$surat_masuk->selectByParamsStatus(array(), $dsplyRange, $dsplyStart, $this->ID, $statement.$searchJson, $sOrder);
		}
		// echo $surat_masuk->query;exit;
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

		while ($surat_masuk->nextRow()) {
			$row = array();
			for ($i = 0; $i < count($aColumns); $i++) {
				if ($aColumns[$i] == "KETERANGAN")
					$row[] = truncate($surat_masuk->getField($aColumns[$i]), 2);
				elseif ($aColumns[$i] == "INFO_STATUS_TANGGAL")
					$row[] = getFormattedExtDateTimeCheck($surat_masuk->getField($aColumns[$i]));
				
				elseif ($aColumns[$i] == "TERBACA" || $aColumns[$i] == "TERDISPOSISI" || $aColumns[$i] == "TERBALAS") {
					if ((int)$surat_masuk->getField($aColumns[$i]) > 0)
						$row[] = "<i class=\"fa fa-check fa-md\" aria-hidden=\"true\"></i>";
					else
						$row[] = "<i class=\"fa fa-close fa-md\" aria-hidden=\"true\"></i>";
				} else
					$row[] = $surat_masuk->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		echo json_encode($output);
	}

	function jsonsuratmasuk()
	{
		$this->load->model("SuratMasuk");
		$surat_masuk = new SuratMasuk();

		$reqJenisNaskahId= $this->input->get("reqJenisNaskahId");
		$reqTahun= $this->input->get("reqTahun");

		$aColumns = array("NOMOR", "INFO_DARI", "PERIHAL", "TANGGAL_DISPOSISI", "INFO_TERBACA", "SIFAT_NASKAH", "DISPOSISI_ID", "SURAT_MASUK_ID");
		$aColumnsAlias = $aColumns;

		/*
		 * Ordering
		 */
		if (isset($_GET['iSortCol_0'])) {
			$sOrder = " ORDER BY ";

			//Go over all sorting cols
			for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
				//If need to sort by current col
				if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
					//Add to the order by clause
					$sOrder .= $aColumnsAlias[intval($_GET['iSortCol_' . $i])];

					//Determine if it is sorted asc or desc
					if (strcasecmp(($_GET['sSortDir_' . $i]), "asc") == 0) {
						$sOrder .= " asc, ";
					} else {
						$sOrder .= " desc, ";
					}
				}
			}

			//Remove the last space / comma
			$sOrder = substr_replace($sOrder, "", -2);

			//Check if there is an order by clause
			if (trim($sOrder) == "ORDER BY A.SURAT_MASUK_ID asc") {
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
		if (isset($_GET['sSearch'])) {
			$sWhereGenearal = $_GET['sSearch'];
		} else {
			$sWhereGenearal = '';
		}

		if ($_GET['sSearch'] != "") {
			//Set a default where clause in order for the where clause not to fail
			//in cases where there are no searchable cols at all.
			$sWhere = " AND (";
			for ($i = 0; $i < count($aColumnsAlias) + 1; $i++) {
				//If current col has a search param
				if ($_GET['bSearchable_' . $i] == "true") {
					//Add the search to the where clause
					$sWhere .= $aColumnsAlias[$i] . " LIKE '%" . $_GET['sSearch'] . "%' OR ";
					$nWhereGenearalCount += 1;
				}
			}
			$sWhere = substr_replace($sWhere, "", -3);
			$sWhere .= ')';
		}

		/* Individual column filtering */
		$sWhereSpecificArray = array();
		$sWhereSpecificArrayCount = 0;
		for ($i = 0; $i < count($aColumnsAlias); $i++) {
			if ($_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {
				//If there was no where clause
				if ($sWhere == "") {
					$sWhere = "AND ";
				} else {
					$sWhere .= " AND ";
				}

				//Add the clause of the specific col to the where clause
				$sWhere .= $aColumnsAlias[$i] . " LIKE '%' || :whereSpecificParam" . $sWhereSpecificArrayCount . " || '%' ";

				//Inc sWhereSpecificArrayCount. It is needed for the bind var.
				//We could just do count($sWhereSpecificArray) - but that would be less efficient.
				$sWhereSpecificArrayCount++;

				//Add current search param to the array for later use (binding).
				$sWhereSpecificArray[] =  $_GET['sSearch_' . $i];
			}
		}

		//If there is still no where clause - set a general - always true where clause
		if ($sWhere == "") {
			$sWhere = " AND 1=1";
		}

		//Bind variables.
		if (isset($_GET['iDisplayStart'])) {
			$dsplyStart = $_GET['iDisplayStart'];
		} else {
			$dsplyStart = 0;
		}
		if (isset($_GET['iDisplayLength']) && $_GET['iDisplayLength'] != '-1') {
			$dsplyRange = $_GET['iDisplayLength'];
			if ($dsplyRange > (2147483645 - intval($dsplyStart))) {
				$dsplyRange = 2147483645;
			} else {
				$dsplyRange = intval($dsplyRange);
			}
		} else {
			$dsplyRange = 2147483645;
		}

		$reqPencarian= $this->input->get("reqPencarian");
		$searchJson= " 
		AND 
		(
			UPPER(A.NO_AGENDA) LIKE '%".strtoupper($reqPencarian)."%' OR 
			UPPER(A.NOMOR) LIKE '%".strtoupper($reqPencarian)."%' OR
			UPPER(A.PERIHAL) LIKE '%".strtoupper($reqPencarian)."%' OR 
			UPPER(A.INSTANSI_ASAL) LIKE '%".strtoupper($reqPencarian)."%' OR 
			UPPER(A.DARI_INFO) LIKE '%".strtoupper($reqPencarian)."%' OR 
			UPPER(A.USER_ATASAN) LIKE '%".strtoupper($reqPencarian)."%' OR 
			UPPER(USER_ATASAN_JABATAN) LIKE '%".strtoupper($reqPencarian)."%' OR 
			UPPER(A.NOMOR_SURAT_INFO) LIKE '%".strtoupper($reqPencarian)."%'
		)";

		if(in_array("SURAT", explode(",", $this->USER_GROUP)))
		{
			$statement= " 
			AND EXISTS
			(
				SELECT 1
				FROM
				(
					SELECT X.SURAT_MASUK_ID, MAX(DISPOSISI_ID) DISPOSISI_ID
					FROM DISPOSISI X
					WHERE X.SATUAN_KERJA_ID_TUJUAN LIKE '".$this->CABANG_ID."%'
					AND X.DISPOSISI_PARENT_ID = 0
					GROUP BY X.SURAT_MASUK_ID
				) X WHERE X.SURAT_MASUK_ID = B.SURAT_MASUK_ID AND X.DISPOSISI_ID = B.DISPOSISI_ID
			)
			";
		}
		else
		{
			$statement= " 
			AND B.DISPOSISI_PARENT_ID = 0
			AND 
			(
				A.STATUS_SURAT = 'POSTING' OR
				A.STATUS_SURAT = 'TU-NOMOR' OR
				(
					A.STATUS_SURAT = 'TU-IN' AND
					EXISTS(SELECT 1 FROM SURAT_MASUK_ARSIP X WHERE X.SURAT_MASUK_ID = A.SURAT_MASUK_ID AND X.CABANG_ID = '".$this->CABANG_ID."')
				)
			)";

			if($this->KD_LEVEL_PEJABAT == "")
			{
				// $statement.= " AND (B.USER_ID = '".$this->ID."' OR B.USER_ID = '".$this->ID_ATASAN."' ) ";
				// ( B.USER_ID = '".$this->ID."' OR B.USER_ID = '".$this->ID_ATASAN."') AND B.STATUS_BANTU IS NULL
				$statement.= " AND 
				(
					(
						( B.USER_ID = '".$this->ID."' AND B.STATUS_BANTU IS NULL AND COALESCE(NULLIF(B.NIP_MUTASI, ''), NULL) IS NULL)
						OR
						EXISTS
						(
							SELECT 1
							FROM
							(
								SELECT NIP, SATUAN_KERJA_ID FROM SATUAN_KERJA_FIX WHERE USER_BANTU = '".$this->ID."'
							) XXX WHERE XXX.NIP = B.USER_ID --AND B.STATUS_BANTU = 1
							AND
							EXISTS
							(
								SELECT 1
								FROM
								(
									SELECT DISTINCT DISPOSISI_ID
									FROM
									(
										SELECT DISPOSISI_ID
										FROM DISPOSISI WHERE STATUS_BANTU = 1
										-- AND SURAT_MASUK_ID IN
										--(
										--	SELECT SURAT_MASUK_ID FROM SURAT_MASUK WHERE JENIS_NASKAH_ID = 1
										--)
									) A
								) YYY WHERE B.DISPOSISI_ID = YYY.DISPOSISI_ID
							)
						)
						AND B.DISPOSISI_KELOMPOK_ID = 0
					)
					OR -- SAKDURUNGE OR
					EXISTS
					(
						SELECT 1
						FROM
						(
							SELECT A.DISPOSISI_KELOMPOK_ID, A.SURAT_MASUK_ID
							FROM disposisi_kelompok A 
							INNER JOIN satuan_kerja_kelompok_group B ON A.SATUAN_KERJA_KELOMPOK_ID = B.SATUAN_KERJA_KELOMPOK_ID
							WHERE B.KELOMPOK_JABATAN = '".$this->KELOMPOK_JABATAN."'
						) X WHERE X.SURAT_MASUK_ID = B.SURAT_MASUK_ID AND X.DISPOSISI_KELOMPOK_ID = B.DISPOSISI_KELOMPOK_ID
					";

					// if($this->KELOMPOK_JABATAN == "KARYAWAN"){}
					// else
					// { 
						 $statement.= " 
						 AND B.SATUAN_KERJA_ID_TUJUAN = '".$this->CABANG_ID."' 
						 AND NOT EXISTS
						 (
							 SELECT 1
							 FROM
							 (
								 SELECT A.SATUAN_KERJA_ID_PARENT, A.KELOMPOK_JABATAN, B.SURAT_MASUK_ID, B.DISPOSISI_KELOMPOK_ID
								 FROM SATUAN_KERJA_FIX A
								 INNER JOIN DISPOSISI B ON A.SATUAN_KERJA_ID = B.SATUAN_KERJA_ID_TUJUAN
								 WHERE B.DISPOSISI_KELOMPOK_ID = 0 AND (NIP_OBSERVER = '".$this->ID."' OR NIP = '".$this->ID."')
								 AND A.KELOMPOK_JABATAN = '".$this->KELOMPOK_JABATAN."'
							 ) Y WHERE B.SATUAN_KERJA_ID_TUJUAN = Y.SATUAN_KERJA_ID_PARENT AND Y.SURAT_MASUK_ID = B.SURAT_MASUK_ID
						 )
						 ";
					// }
				$statement.= "
					)
					OR
					EXISTS
					(
						SELECT 1
						FROM
						(
							SELECT
							CASE WHEN COALESCE(NULLIF(NIP, ''), 'X') = 'X' THEN NIP_OBSERVER ELSE NIP END NIP_OBSERVER
							, SATUAN_KERJA_ID
							FROM SATUAN_KERJA_FIX WHERE 1=1
							AND (NIP_OBSERVER = '".$this->ID."' OR NIP = '".$this->ID."')
						) X WHERE X.SATUAN_KERJA_ID = B.SATUAN_KERJA_ID_TUJUAN
						AND
						EXISTS
						(
							SELECT 1
							FROM
							(
								SELECT DISTINCT DISPOSISI_ID
								FROM
								(
									SELECT DISPOSISI_ID
									FROM DISPOSISI 
									WHERE STATUS_BANTU IS NULL
									-- AND SURAT_MASUK_ID IN
									--(
									--	SELECT SURAT_MASUK_ID FROM SURAT_MASUK WHERE JENIS_NASKAH_ID = 1
									--)
									--UNION ALL
									--SELECT DISPOSISI_ID
									--FROM DISPOSISI WHERE
									--SURAT_MASUK_ID IN
									--(
									--	SELECT SURAT_MASUK_ID FROM SURAT_MASUK WHERE JENIS_NASKAH_ID NOT IN (1)
									--)
								) A
							) YYY WHERE B.DISPOSISI_ID = YYY.DISPOSISI_ID
						)
					)
				) ";
			}
			else
			{
				$statement.= " AND (B.SATUAN_KERJA_ID_TUJUAN = '".$this->SATUAN_KERJA_ID_ASAL."' OR B.USER_ID = '".$this->ID."' OR B.USER_ID_OBSERVER = '".$this->ID."') ";
			}
		}

		if(!empty($reqJenisNaskahId))
		{
			$statement.= " AND A.JENIS_NASKAH_ID IN (".$reqJenisNaskahId.")";
		}

		if(!empty($reqTahun))
		{
			$statement.= " AND A.TAHUN = ".$reqTahun;
		}
		//UJI COBA ANGGA
		//$statement.= " AND B.SATUAN_KERJA_ID_TUJUAN = '".$this->CABANG_ID."' ";

		$allRecord = $surat_masuk->getCountByParamsSuratMasuk(array(), $statement);
		// echo $allRecord;exit;
		if ($reqPencarian == "")
			$allRecordFilter = $allRecord;
		else
			$allRecordFilter =  $surat_masuk->getCountByParamsSuratMasuk(array(), $statement.$searchJson);

		// $sOrder= "";
		$sOrder = " ORDER BY B.TANGGAL_DISPOSISI DESC";
		$surat_masuk->selectByParamsSuratMasuk(array(), $dsplyRange, $dsplyStart, $statement.$searchJson, $sOrder);
		// echo $surat_masuk->query;exit;
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

		while ($surat_masuk->nextRow()) {
			$infojenisnaskahid= $surat_masuk->getField("JENIS_NASKAH_ID");
			$row = array();

			for ($i = 0; $i < count($aColumns); $i++) {
				if ($aColumns[$i] == "KETERANGAN")
					$row[] = truncate($surat_masuk->getField($aColumns[$i]), 2);
				elseif($aColumns[$i] == "NOMOR")
				{
					if($infojenisnaskahid == "1")
						$row[] = $surat_masuk->getField("NOMOR_SURAT_INFO");
					else
						$row[] = $surat_masuk->getField($aColumns[$i]);
				}
				elseif ($aColumns[$i] == "xSIFAT_NASKAH")
					$row[] = "Sangat Segera";
				elseif ($aColumns[$i] == "INFO_TERBACA")
				{
					$infoterbaca= "";
					if(in_array("SURAT", explode(",", $this->USER_GROUP)))
					{
						$infoterbaca= "1";
					}
					else
					{
						$infodisposisiuserid= $this->ID;
						$infodisposisiterbacainfo= $surat_masuk->getField("TERBACA_INFO");

						$arrcheckterbaca= explode(";", $infodisposisiterbacainfo);
				        if(!empty($arrcheckterbaca) && !empty($infodisposisiterbacainfo))
				        {
				            while (list($key, $val) = each($arrcheckterbaca))
				            {
				                $arrcheckterbacadetil= explode(",", $val);
				                if($infodisposisiuserid == $arrcheckterbacadetil[0])
				                {
				                    $infoterbaca= "1";
				                    break;
				                }
				            }
				        }
				    }
					$row[] = $infoterbaca;
				}
				elseif($aColumns[$i] == "INFO_DARI")
				{
					if($infojenisnaskahid == "1")
						$row[] = $surat_masuk->getField("DARI_INFO");
					else
						$row[] = $surat_masuk->getField("USER_ATASAN")."<br/>".$surat_masuk->getField("USER_ATASAN_JABATAN");
				}
				elseif ($aColumns[$i] == "TANGGAL_DISPOSISI")
				{
					$infoicondisposisi= "";
					if($surat_masuk->getField("TERDISPOSISI") == "1")
					{
						$infoicondisposisi= "<i class='fa fa-share-alt' aria-hidden='true'></i>";
					}
					$row[] = getFormattedExtDateTimeCheck($surat_masuk->getField($aColumns[$i]))." ".$infoicondisposisi;
				}
				elseif ($aColumns[$i] == "TERBACA" || $aColumns[$i] == "TERDISPOSISI" || $aColumns[$i] == "TERBALAS") {
					if ((int)$surat_masuk->getField($aColumns[$i]) > 0)
						$row[] = "<i class=\"fa fa-check fa-md\" aria-hidden=\"true\"></i>";
					else
						$row[] = "<i class=\"fa fa-close fa-md\" aria-hidden=\"true\"></i>";
				} else
					$row[] = $surat_masuk->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		echo json_encode($output);
	}

	function jsonsuratdisposisi()
	{
		$this->load->model("SuratMasuk");
		$surat_masuk = new SuratMasuk();

		$reqJenisNaskahId= $this->input->get("reqJenisNaskahId");
		$reqTahun= $this->input->get("reqTahun");

		$aColumns = array("NOMOR", "INFO_DARI", "PERIHAL", "TANGGAL_DISPOSISI", "DETIL_INFO_DARI_DIPOSISI", "INFO_TERBACA", "DISPOSISI_ID", "SURAT_MASUK_ID");
		$aColumnsAlias = $aColumns;

		/*
		 * Ordering
		 */
		if (isset($_GET['iSortCol_0'])) {
			$sOrder = " ORDER BY ";

			//Go over all sorting cols
			for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
				//If need to sort by current col
				if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
					//Add to the order by clause
					$sOrder .= $aColumnsAlias[intval($_GET['iSortCol_' . $i])];

					//Determine if it is sorted asc or desc
					if (strcasecmp(($_GET['sSortDir_' . $i]), "asc") == 0) {
						$sOrder .= " asc, ";
					} else {
						$sOrder .= " desc, ";
					}
				}
			}

			//Remove the last space / comma
			$sOrder = substr_replace($sOrder, "", -2);

			//Check if there is an order by clause
			if (trim($sOrder) == "ORDER BY A.SURAT_MASUK_ID asc") {
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
		if (isset($_GET['sSearch'])) {
			$sWhereGenearal = $_GET['sSearch'];
		} else {
			$sWhereGenearal = '';
		}

		if ($_GET['sSearch'] != "") {
			//Set a default where clause in order for the where clause not to fail
			//in cases where there are no searchable cols at all.
			$sWhere = " AND (";
			for ($i = 0; $i < count($aColumnsAlias) + 1; $i++) {
				//If current col has a search param
				if ($_GET['bSearchable_' . $i] == "true") {
					//Add the search to the where clause
					$sWhere .= $aColumnsAlias[$i] . " LIKE '%" . $_GET['sSearch'] . "%' OR ";
					$nWhereGenearalCount += 1;
				}
			}
			$sWhere = substr_replace($sWhere, "", -3);
			$sWhere .= ')';
		}

		/* Individual column filtering */
		$sWhereSpecificArray = array();
		$sWhereSpecificArrayCount = 0;
		for ($i = 0; $i < count($aColumnsAlias); $i++) {
			if ($_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {
				//If there was no where clause
				if ($sWhere == "") {
					$sWhere = "AND ";
				} else {
					$sWhere .= " AND ";
				}

				//Add the clause of the specific col to the where clause
				$sWhere .= $aColumnsAlias[$i] . " LIKE '%' || :whereSpecificParam" . $sWhereSpecificArrayCount . " || '%' ";

				//Inc sWhereSpecificArrayCount. It is needed for the bind var.
				//We could just do count($sWhereSpecificArray) - but that would be less efficient.
				$sWhereSpecificArrayCount++;

				//Add current search param to the array for later use (binding).
				$sWhereSpecificArray[] =  $_GET['sSearch_' . $i];
			}
		}

		//If there is still no where clause - set a general - always true where clause
		if ($sWhere == "") {
			$sWhere = " AND 1=1";
		}

		//Bind variables.
		if (isset($_GET['iDisplayStart'])) {
			$dsplyStart = $_GET['iDisplayStart'];
		} else {
			$dsplyStart = 0;
		}
		if (isset($_GET['iDisplayLength']) && $_GET['iDisplayLength'] != '-1') {
			$dsplyRange = $_GET['iDisplayLength'];
			if ($dsplyRange > (2147483645 - intval($dsplyStart))) {
				$dsplyRange = 2147483645;
			} else {
				$dsplyRange = intval($dsplyRange);
			}
		} else {
			$dsplyRange = 2147483645;
		}

		$reqPencarian= $this->input->get("reqPencarian");
		$searchJson= " 
		AND 
		(
			UPPER(A.NO_AGENDA) LIKE '%".strtoupper($reqPencarian)."%' OR 
			UPPER(A.NOMOR) LIKE '%".strtoupper($reqPencarian)."%' OR
			UPPER(A.PERIHAL) LIKE '%".strtoupper($reqPencarian)."%' OR 
			UPPER(A.INSTANSI_ASAL) LIKE '%".strtoupper($reqPencarian)."%' OR 
			UPPER(A.DARI_INFO) LIKE '%".strtoupper($reqPencarian)."%' OR 
			UPPER(A.USER_ATASAN) LIKE '%".strtoupper($reqPencarian)."%' OR 
			UPPER(USER_ATASAN_JABATAN) LIKE '%".strtoupper($reqPencarian)."%' OR 
			UPPER(A.NOMOR_SURAT_INFO) LIKE '%".strtoupper($reqPencarian)."%' OR 
			UPPER(B.NAMA_USER_ASAL) LIKE '%".strtoupper($reqPencarian)."%' OR 
			UPPER(B.NAMA_SATKER_ASAL) LIKE '%".strtoupper($reqPencarian)."%'
		)";

		$statement= "
		AND 
		(
			A.STATUS_SURAT = 'POSTING' OR
			A.STATUS_SURAT = 'TU-NOMOR' OR
			(
				A.STATUS_SURAT = 'TU-IN' AND
				EXISTS(SELECT 1 FROM SURAT_MASUK_ARSIP X WHERE X.SURAT_MASUK_ID = A.SURAT_MASUK_ID AND X.CABANG_ID = '".$this->CABANG_ID."')
			)
		)";

		if(!empty($reqJenisNaskahId))
		{
			$statement.= " AND A.JENIS_NASKAH_ID IN (".$reqJenisNaskahId.")";
		}

		if(!empty($reqTahun))
		{
			$statement.= " AND A.TAHUN = ".$reqTahun;
		}

		$allRecord = $surat_masuk->getCountByParamsDisposisi(array(), $this->ID, $statement);
		// echo $allRecord;exit;
		if ($reqPencarian == "")
			$allRecordFilter = $allRecord;
		else
			$allRecordFilter =  $surat_masuk->getCountByParamsDisposisi(array(), $this->ID, $statement.$searchJson);

		$sOrder = " ORDER BY B.TANGGAL_DISPOSISI DESC";
		$surat_masuk->selectByParamsDisposisi(array(), $dsplyRange, $dsplyStart, $this->ID, $statement.$searchJson, $sOrder);
		// echo $surat_masuk->query;exit;
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

		while ($surat_masuk->nextRow()) {
			$infojenisnaskahid= $surat_masuk->getField("JENIS_NASKAH_ID");

			$row = array();

			for ($i = 0; $i < count($aColumns); $i++) {
				if ($aColumns[$i] == "KETERANGAN")
					$row[] = truncate($surat_masuk->getField($aColumns[$i]), 2);
				elseif($aColumns[$i] == "NOMOR")
				{
					if($infojenisnaskahid == "1")
						$row[] = $surat_masuk->getField("NOMOR_SURAT_INFO");
					else
						$row[] = $surat_masuk->getField($aColumns[$i]);
				}
				elseif ($aColumns[$i] == "INFO_TERBACA")
				{
					$infoterbaca= "";
					$infodisposisiuserid= $this->ID;
					$infodisposisiterbacainfo= $surat_masuk->getField("TERBACA_INFO");

					$arrcheckterbaca= explode(";", $infodisposisiterbacainfo);
			        if(!empty($arrcheckterbaca) && !empty($infodisposisiterbacainfo))
			        {
			            while (list($key, $val) = each($arrcheckterbaca))
			            {
			                $arrcheckterbacadetil= explode(",", $val);
			                if($infodisposisiuserid == $arrcheckterbacadetil[0])
			                {
			                    $infoterbaca= "1";
			                    break;
			                }
			            }
			        }
					$row[] = $infoterbaca;
				}
				elseif($aColumns[$i] == "INFO_DARI")
				{
					if($infojenisnaskahid == "1")
						$row[] = $surat_masuk->getField("DARI_INFO");
					else
						$row[] = $surat_masuk->getField("USER_ATASAN")."<br/>".$surat_masuk->getField("USER_ATASAN_JABATAN");
				}
				elseif($aColumns[$i] == "DETIL_INFO_DARI_DIPOSISI")
				{
					$row[] = $surat_masuk->getField("NAMA_USER_ASAL")."<br/>".$surat_masuk->getField("NAMA_SATKER_ASAL");
				}
				elseif ($aColumns[$i] == "TANGGAL_DISPOSISI")
					$row[] = getFormattedExtDateTimeCheck($surat_masuk->getField($aColumns[$i]));
				
				elseif ($aColumns[$i] == "TERBACA" || $aColumns[$i] == "TERDISPOSISI" || $aColumns[$i] == "TERBALAS") {
					if ((int)$surat_masuk->getField($aColumns[$i]) > 0)
						$row[] = "<i class=\"fa fa-check fa-md\" aria-hidden=\"true\"></i>";
					else
						$row[] = "<i class=\"fa fa-close fa-md\" aria-hidden=\"true\"></i>";
				} else
					$row[] = $surat_masuk->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		echo json_encode($output);
	}

	function jsonsurattanggapan()
	{
		$this->load->model("SuratMasuk");
		$surat_masuk = new SuratMasuk();

		$reqJenisNaskahId= $this->input->get("reqJenisNaskahId");
		$reqTahun= $this->input->get("reqTahun");

		$aColumns = array("NOMOR", "INFO_DARI", "PERIHAL", "TANGGAL_DISPOSISI", "DETIL_INFO_DARI_DIPOSISI", "INFO_TERBACA", "DISPOSISI_ID", "SURAT_MASUK_ID");
		$aColumnsAlias = $aColumns;

		/*
		 * Ordering
		 */
		if (isset($_GET['iSortCol_0'])) {
			$sOrder = " ORDER BY ";

			//Go over all sorting cols
			for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
				//If need to sort by current col
				if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
					//Add to the order by clause
					$sOrder .= $aColumnsAlias[intval($_GET['iSortCol_' . $i])];

					//Determine if it is sorted asc or desc
					if (strcasecmp(($_GET['sSortDir_' . $i]), "asc") == 0) {
						$sOrder .= " asc, ";
					} else {
						$sOrder .= " desc, ";
					}
				}
			}

			//Remove the last space / comma
			$sOrder = substr_replace($sOrder, "", -2);

			//Check if there is an order by clause
			if (trim($sOrder) == "ORDER BY A.SURAT_MASUK_ID asc") {
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
		if (isset($_GET['sSearch'])) {
			$sWhereGenearal = $_GET['sSearch'];
		} else {
			$sWhereGenearal = '';
		}

		if ($_GET['sSearch'] != "") {
			//Set a default where clause in order for the where clause not to fail
			//in cases where there are no searchable cols at all.
			$sWhere = " AND (";
			for ($i = 0; $i < count($aColumnsAlias) + 1; $i++) {
				//If current col has a search param
				if ($_GET['bSearchable_' . $i] == "true") {
					//Add the search to the where clause
					$sWhere .= $aColumnsAlias[$i] . " LIKE '%" . $_GET['sSearch'] . "%' OR ";
					$nWhereGenearalCount += 1;
				}
			}
			$sWhere = substr_replace($sWhere, "", -3);
			$sWhere .= ')';
		}

		/* Individual column filtering */
		$sWhereSpecificArray = array();
		$sWhereSpecificArrayCount = 0;
		for ($i = 0; $i < count($aColumnsAlias); $i++) {
			if ($_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {
				//If there was no where clause
				if ($sWhere == "") {
					$sWhere = "AND ";
				} else {
					$sWhere .= " AND ";
				}

				//Add the clause of the specific col to the where clause
				$sWhere .= $aColumnsAlias[$i] . " LIKE '%' || :whereSpecificParam" . $sWhereSpecificArrayCount . " || '%' ";

				//Inc sWhereSpecificArrayCount. It is needed for the bind var.
				//We could just do count($sWhereSpecificArray) - but that would be less efficient.
				$sWhereSpecificArrayCount++;

				//Add current search param to the array for later use (binding).
				$sWhereSpecificArray[] =  $_GET['sSearch_' . $i];
			}
		}

		//If there is still no where clause - set a general - always true where clause
		if ($sWhere == "") {
			$sWhere = " AND 1=1";
		}

		//Bind variables.
		if (isset($_GET['iDisplayStart'])) {
			$dsplyStart = $_GET['iDisplayStart'];
		} else {
			$dsplyStart = 0;
		}
		if (isset($_GET['iDisplayLength']) && $_GET['iDisplayLength'] != '-1') {
			$dsplyRange = $_GET['iDisplayLength'];
			if ($dsplyRange > (2147483645 - intval($dsplyStart))) {
				$dsplyRange = 2147483645;
			} else {
				$dsplyRange = intval($dsplyRange);
			}
		} else {
			$dsplyRange = 2147483645;
		}

		$reqPencarian= $this->input->get("reqPencarian");
		$searchJson= " 
		AND 
		(
			UPPER(A.NO_AGENDA) LIKE '%".strtoupper($reqPencarian)."%' OR 
			UPPER(A.NOMOR) LIKE '%".strtoupper($reqPencarian)."%' OR
			UPPER(A.PERIHAL) LIKE '%".strtoupper($reqPencarian)."%' OR 
			UPPER(A.INSTANSI_ASAL) LIKE '%".strtoupper($reqPencarian)."%' OR 
			UPPER(A.DARI_INFO) LIKE '%".strtoupper($reqPencarian)."%' OR 
			UPPER(A.USER_ATASAN) LIKE '%".strtoupper($reqPencarian)."%' OR 
			UPPER(USER_ATASAN_JABATAN) LIKE '%".strtoupper($reqPencarian)."%' OR 
			UPPER(A.NOMOR_SURAT_INFO) LIKE '%".strtoupper($reqPencarian)."%' OR 
			UPPER(B.NAMA_USER_ASAL) LIKE '%".strtoupper($reqPencarian)."%' OR 
			UPPER(B.NAMA_SATKER_ASAL) LIKE '%".strtoupper($reqPencarian)."%'
		)";

		$statement= "
		AND 
		(
			A.STATUS_SURAT = 'POSTING' OR
			A.STATUS_SURAT = 'TU-NOMOR' OR
			(
				A.STATUS_SURAT = 'TU-IN' AND
				EXISTS(SELECT 1 FROM SURAT_MASUK_ARSIP X WHERE X.SURAT_MASUK_ID = A.SURAT_MASUK_ID AND X.CABANG_ID = '".$this->CABANG_ID."')
			)
		)";

		if(!empty($reqJenisNaskahId))
		{
			$statement.= " AND A.JENIS_NASKAH_ID IN (".$reqJenisNaskahId.")";
		}

		if(!empty($reqTahun))
		{
			$statement.= " AND A.TAHUN = ".$reqTahun;
		}

		$allRecord = $surat_masuk->getCountByParamsTanggapanDisposisi(array(), $this->ID, $statement);
		// echo $allRecord;exit;
		if ($reqPencarian == "")
			$allRecordFilter = $allRecord;
		else
			$allRecordFilter =  $surat_masuk->getCountByParamsTanggapanDisposisi(array(), $this->ID, $statement.$searchJson);

		$sOrder = " ORDER BY B.TANGGAL_DISPOSISI DESC";
		$surat_masuk->selectByParamsTanggapanDisposisi(array(), $dsplyRange, $dsplyStart, $this->ID, $statement.$searchJson, $sOrder);
		// echo $surat_masuk->query;exit;
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

		while ($surat_masuk->nextRow()) {
			$infojenisnaskahid= $surat_masuk->getField("JENIS_NASKAH_ID");

			$row = array();

			for ($i = 0; $i < count($aColumns); $i++) {
				if ($aColumns[$i] == "KETERANGAN")
					$row[] = truncate($surat_masuk->getField($aColumns[$i]), 2);
				elseif($aColumns[$i] == "NOMOR")
				{
					if($infojenisnaskahid == "1")
						$row[] = $surat_masuk->getField("NOMOR_SURAT_INFO");
					else
						$row[] = $surat_masuk->getField($aColumns[$i]);
				}
				elseif ($aColumns[$i] == "INFO_TERBACA")
				{
					$infoterbaca= "";
					$infodisposisiuserid= $this->ID;
					$infodisposisiterbacainfo= $surat_masuk->getField("TERBACA_INFO");

					$arrcheckterbaca= explode(";", $infodisposisiterbacainfo);
			        if(!empty($arrcheckterbaca) && !empty($infodisposisiterbacainfo))
			        {
			            while (list($key, $val) = each($arrcheckterbaca))
			            {
			                $arrcheckterbacadetil= explode(",", $val);
			                if($infodisposisiuserid == $arrcheckterbacadetil[0])
			                {
			                    $infoterbaca= "1";
			                    break;
			                }
			            }
			        }
					$row[] = $infoterbaca;
				}
				elseif($aColumns[$i] == "INFO_DARI")
				{
					if($infojenisnaskahid == "1")
						$row[] = $surat_masuk->getField("DARI_INFO");
					else
						$row[] = $surat_masuk->getField("USER_ATASAN")."<br/>".$surat_masuk->getField("USER_ATASAN_JABATAN");
				}
				elseif($aColumns[$i] == "DETIL_INFO_DARI_DIPOSISI")
					$row[] = $surat_masuk->getField("NAMA_USER_ASAL")."<br/>".$surat_masuk->getField("NAMA_SATKER_ASAL");
				elseif ($aColumns[$i] == "TANGGAL_DISPOSISI")
					$row[] = getFormattedExtDateTimeCheck($surat_masuk->getField($aColumns[$i]));
				
				elseif ($aColumns[$i] == "TERBACA" || $aColumns[$i] == "TERDISPOSISI" || $aColumns[$i] == "TERBALAS") {
					if ((int)$surat_masuk->getField($aColumns[$i]) > 0)
						$row[] = "<i class=\"fa fa-check fa-md\" aria-hidden=\"true\"></i>";
					else
						$row[] = "<i class=\"fa fa-close fa-md\" aria-hidden=\"true\"></i>";
				} else
					$row[] = $surat_masuk->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		echo json_encode($output);
	}

	function jsonsuratkeluar()
	{
		$this->load->model("SuratMasuk");
		$surat_masuk = new SuratMasuk();

		$reqJenisNaskahId= $this->input->get("reqJenisNaskahId");
		$reqTahun= $this->input->get("reqTahun");

		$aColumns = array("NOMOR", "INFO_DARI", "PERIHAL", "TANGGAL_DISPOSISI", "SURAT_MASUK_ID");
		$aColumnsAlias = $aColumns;

		/*
		 * Ordering
		 */
		if (isset($_GET['iSortCol_0'])) {
			$sOrder = " ORDER BY ";

			//Go over all sorting cols
			for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
				//If need to sort by current col
				if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
					//Add to the order by clause
					$sOrder .= $aColumnsAlias[intval($_GET['iSortCol_' . $i])];

					//Determine if it is sorted asc or desc
					if (strcasecmp(($_GET['sSortDir_' . $i]), "asc") == 0) {
						$sOrder .= " asc, ";
					} else {
						$sOrder .= " desc, ";
					}
				}
			}

			//Remove the last space / comma
			$sOrder = substr_replace($sOrder, "", -2);

			//Check if there is an order by clause
			if (trim($sOrder) == "ORDER BY A.SURAT_MASUK_ID asc") {
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
		if (isset($_GET['sSearch'])) {
			$sWhereGenearal = $_GET['sSearch'];
		} else {
			$sWhereGenearal = '';
		}

		if ($_GET['sSearch'] != "") {
			//Set a default where clause in order for the where clause not to fail
			//in cases where there are no searchable cols at all.
			$sWhere = " AND (";
			for ($i = 0; $i < count($aColumnsAlias) + 1; $i++) {
				//If current col has a search param
				if ($_GET['bSearchable_' . $i] == "true") {
					//Add the search to the where clause
					$sWhere .= $aColumnsAlias[$i] . " LIKE '%" . $_GET['sSearch'] . "%' OR ";
					$nWhereGenearalCount += 1;
				}
			}
			$sWhere = substr_replace($sWhere, "", -3);
			$sWhere .= ')';
		}

		/* Individual column filtering */
		$sWhereSpecificArray = array();
		$sWhereSpecificArrayCount = 0;
		for ($i = 0; $i < count($aColumnsAlias); $i++) {
			if ($_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {
				//If there was no where clause
				if ($sWhere == "") {
					$sWhere = "AND ";
				} else {
					$sWhere .= " AND ";
				}

				//Add the clause of the specific col to the where clause
				$sWhere .= $aColumnsAlias[$i] . " LIKE '%' || :whereSpecificParam" . $sWhereSpecificArrayCount . " || '%' ";

				//Inc sWhereSpecificArrayCount. It is needed for the bind var.
				//We could just do count($sWhereSpecificArray) - but that would be less efficient.
				$sWhereSpecificArrayCount++;

				//Add current search param to the array for later use (binding).
				$sWhereSpecificArray[] =  $_GET['sSearch_' . $i];
			}
		}

		//If there is still no where clause - set a general - always true where clause
		if ($sWhere == "") {
			$sWhere = " AND 1=1";
		}

		//Bind variables.
		if (isset($_GET['iDisplayStart'])) {
			$dsplyStart = $_GET['iDisplayStart'];
		} else {
			$dsplyStart = 0;
		}
		if (isset($_GET['iDisplayLength']) && $_GET['iDisplayLength'] != '-1') {
			$dsplyRange = $_GET['iDisplayLength'];
			if ($dsplyRange > (2147483645 - intval($dsplyStart))) {
				$dsplyRange = 2147483645;
			} else {
				$dsplyRange = intval($dsplyRange);
			}
		} else {
			$dsplyRange = 2147483645;
		}

		$reqPencarian= $this->input->get("reqPencarian");
		$searchJson= " 
		AND 
		(
			UPPER(A.NO_AGENDA) LIKE '%".strtoupper($reqPencarian)."%' OR 
			UPPER(A.NOMOR) LIKE '%".strtoupper($reqPencarian)."%' OR
			UPPER(A.PERIHAL) LIKE '%".strtoupper($reqPencarian)."%' OR 
			UPPER(A.INSTANSI_ASAL) LIKE '%".strtoupper($reqPencarian)."%' OR 
			UPPER(A.DARI_INFO) LIKE '%".strtoupper($reqPencarian)."%' OR 
			UPPER(A.USER_ATASAN) LIKE '%".strtoupper($reqPencarian)."%' OR 
			UPPER(USER_ATASAN_JABATAN) LIKE '%".strtoupper($reqPencarian)."%' OR 
			UPPER(A.NOMOR_SURAT_INFO) LIKE '%".strtoupper($reqPencarian)."%'
		)";

		$statement= " AND (A.STATUS_SURAT IN ('TATAUSAHA','POSTING') OR A.STATUS_SURAT LIKE 'TU%')";

		if(!empty($reqJenisNaskahId))
		{
			$statement.= " AND A.JENIS_NASKAH_ID IN (".$reqJenisNaskahId.")";
		}

		if(!empty($reqTahun))
		{
			$statement.= " AND A.TAHUN = ".$reqTahun;
		}

		if(in_array("SURAT", explode(",", $this->USER_GROUP)))
		{
			$statement.="
			AND EXISTS
			(
				SELECT 1
				FROM
				(
					SELECT X.SURAT_MASUK_ID, MAX(DISPOSISI_ID) DISPOSISI_ID
					FROM DISPOSISI X
					WHERE X.SATUAN_KERJA_ID_ASAL LIKE '".$this->CABANG_ID."%'
					AND X.DISPOSISI_PARENT_ID = 0
					GROUP BY X.SURAT_MASUK_ID
				) X WHERE X.SURAT_MASUK_ID = A.SURAT_MASUK_ID AND X.DISPOSISI_ID = B.DISPOSISI_ID
			)
			";

			$allRecord = $surat_masuk->getCountByParamsAdminSuratKeluar(array(), $this->ID, $statement);
			// echo $allRecord;exit;
			if ($reqPencarian == "")
				$allRecordFilter = $allRecord;
			else
				$allRecordFilter =  $surat_masuk->getCountByParamsAdminSuratKeluar(array(), $this->ID, $statement.$searchJson);

			$sOrder = " ORDER BY B.TANGGAL_DISPOSISI DESC";
			$surat_masuk->selectByParamsAdminSuratKeluar(array(), $dsplyRange, $dsplyStart, $this->ID, $statement.$searchJson, $sOrder);
		}
		else
		{
			$allRecord = $surat_masuk->getCountByParamsSuratKeluar(array(), $this->ID, $statement);
			// echo $allRecord;exit;
			if ($reqPencarian == "")
				$allRecordFilter = $allRecord;
			else
				$allRecordFilter =  $surat_masuk->getCountByParamsSuratKeluar(array(), $this->ID, $statement.$searchJson);

			$sOrder = " ORDER BY B.TANGGAL_DISPOSISI DESC";
			$surat_masuk->selectByParamsSuratKeluar(array(), $dsplyRange, $dsplyStart, $this->ID, $statement.$searchJson, $sOrder);
		}
		// echo $surat_masuk->query;exit;
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

		while ($surat_masuk->nextRow()) {
			$infojenisnaskahid= $surat_masuk->getField("JENIS_NASKAH_ID");

			$row = array();

			for ($i = 0; $i < count($aColumns); $i++) {
				if ($aColumns[$i] == "KETERANGAN")
					$row[] = truncate($surat_masuk->getField($aColumns[$i]), 2);
				elseif($aColumns[$i] == "NOMOR")
				{
					if($infojenisnaskahid == "1")
						$row[] = $surat_masuk->getField("NOMOR_SURAT_INFO");
					else
						$row[] = $surat_masuk->getField($aColumns[$i]);
				}
				elseif($aColumns[$i] == "INFO_DARI")
				{
					if($infojenisnaskahid == "1")
						$row[] = $surat_masuk->getField("DARI_INFO");
					else
						$row[] = $surat_masuk->getField("USER_ATASAN")."<br/>".$surat_masuk->getField("USER_ATASAN_JABATAN");
				}
				elseif ($aColumns[$i] == "TANGGAL_DISPOSISI")
					$row[] = getFormattedExtDateTimeCheck($surat_masuk->getField($aColumns[$i]));
				
				elseif ($aColumns[$i] == "TERBACA" || $aColumns[$i] == "TERDISPOSISI" || $aColumns[$i] == "TERBALAS") {
					if ((int)$surat_masuk->getField($aColumns[$i]) > 0)
						$row[] = "<i class=\"fa fa-check fa-md\" aria-hidden=\"true\"></i>";
					else
						$row[] = "<i class=\"fa fa-close fa-md\" aria-hidden=\"true\"></i>";
				} else
					$row[] = $surat_masuk->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		echo json_encode($output);
	}

	function jsonsuratkeluardisposisi()
	{
		$this->load->model("SuratMasuk");
		$surat_masuk = new SuratMasuk();

		$reqJenisNaskahId= $this->input->get("reqJenisNaskahId");
		$reqTahun= $this->input->get("reqTahun");

		// $aColumns = array("NOMOR", "INFO_DARI", "PERIHAL", "TANGGAL_DISPOSISI", "DISPOSISI_DARI", "DISPOSISI_KE", "DISPOSISI_ID", "SURAT_MASUK_ID");
		$aColumns = array("NOMOR", "INFO_DARI", "PERIHAL", "TANGGAL_DISPOSISI", "DETIL_INFO_KEPADA_DIPOSISI", "DISPOSISI_ID", "SURAT_MASUK_ID");
		$aColumnsAlias = $aColumns;

		/*
		 * Ordering
		 */
		if (isset($_GET['iSortCol_0'])) {
			$sOrder = " ORDER BY ";

			//Go over all sorting cols
			for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
				//If need to sort by current col
				if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
					//Add to the order by clause
					$sOrder .= $aColumnsAlias[intval($_GET['iSortCol_' . $i])];

					//Determine if it is sorted asc or desc
					if (strcasecmp(($_GET['sSortDir_' . $i]), "asc") == 0) {
						$sOrder .= " asc, ";
					} else {
						$sOrder .= " desc, ";
					}
				}
			}

			//Remove the last space / comma
			$sOrder = substr_replace($sOrder, "", -2);

			//Check if there is an order by clause
			if (trim($sOrder) == "ORDER BY A.SURAT_MASUK_ID asc") {
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
		if (isset($_GET['sSearch'])) {
			$sWhereGenearal = $_GET['sSearch'];
		} else {
			$sWhereGenearal = '';
		}

		if ($_GET['sSearch'] != "") {
			//Set a default where clause in order for the where clause not to fail
			//in cases where there are no searchable cols at all.
			$sWhere = " AND (";
			for ($i = 0; $i < count($aColumnsAlias) + 1; $i++) {
				//If current col has a search param
				if ($_GET['bSearchable_' . $i] == "true") {
					//Add the search to the where clause
					$sWhere .= $aColumnsAlias[$i] . " LIKE '%" . $_GET['sSearch'] . "%' OR ";
					$nWhereGenearalCount += 1;
				}
			}
			$sWhere = substr_replace($sWhere, "", -3);
			$sWhere .= ')';
		}

		/* Individual column filtering */
		$sWhereSpecificArray = array();
		$sWhereSpecificArrayCount = 0;
		for ($i = 0; $i < count($aColumnsAlias); $i++) {
			if ($_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {
				//If there was no where clause
				if ($sWhere == "") {
					$sWhere = "AND ";
				} else {
					$sWhere .= " AND ";
				}

				//Add the clause of the specific col to the where clause
				$sWhere .= $aColumnsAlias[$i] . " LIKE '%' || :whereSpecificParam" . $sWhereSpecificArrayCount . " || '%' ";

				//Inc sWhereSpecificArrayCount. It is needed for the bind var.
				//We could just do count($sWhereSpecificArray) - but that would be less efficient.
				$sWhereSpecificArrayCount++;

				//Add current search param to the array for later use (binding).
				$sWhereSpecificArray[] =  $_GET['sSearch_' . $i];
			}
		}

		//If there is still no where clause - set a general - always true where clause
		if ($sWhere == "") {
			$sWhere = " AND 1=1";
		}

		//Bind variables.
		if (isset($_GET['iDisplayStart'])) {
			$dsplyStart = $_GET['iDisplayStart'];
		} else {
			$dsplyStart = 0;
		}
		if (isset($_GET['iDisplayLength']) && $_GET['iDisplayLength'] != '-1') {
			$dsplyRange = $_GET['iDisplayLength'];
			if ($dsplyRange > (2147483645 - intval($dsplyStart))) {
				$dsplyRange = 2147483645;
			} else {
				$dsplyRange = intval($dsplyRange);
			}
		} else {
			$dsplyRange = 2147483645;
		}

		$reqPencarian= $this->input->get("reqPencarian");
		$searchJson= " 
		AND 
		(
			UPPER(A.NO_AGENDA) LIKE '%".strtoupper($reqPencarian)."%' OR 
			UPPER(A.NOMOR) LIKE '%".strtoupper($reqPencarian)."%' OR
			UPPER(A.PERIHAL) LIKE '%".strtoupper($reqPencarian)."%' OR 
			UPPER(A.INSTANSI_ASAL) LIKE '%".strtoupper($reqPencarian)."%' OR 
			UPPER(A.DARI_INFO) LIKE '%".strtoupper($reqPencarian)."%' OR 
			UPPER(A.USER_ATASAN) LIKE '%".strtoupper($reqPencarian)."%' OR 
			UPPER(USER_ATASAN_JABATAN) LIKE '%".strtoupper($reqPencarian)."%' OR 
			UPPER(INFO_KEPADA_DIPOSISI(B.DISPOSISI_PARENT_ID, TO_CHAR(B.TANGGAL_DISPOSISI, 'YYYY-MM-DD HH24:MI'))) LIKE '%".strtoupper($reqPencarian)."%'
		)";

		$statement= "
		AND 
		(
			A.STATUS_SURAT = 'POSTING' OR
			A.STATUS_SURAT = 'TU-NOMOR' OR
			(
				A.STATUS_SURAT = 'TU-IN' AND
				EXISTS(SELECT 1 FROM SURAT_MASUK_ARSIP X WHERE X.SURAT_MASUK_ID = A.SURAT_MASUK_ID AND X.CABANG_ID = '".$this->CABANG_ID."')
			)
		)";

		if(!empty($reqJenisNaskahId))
		{
			$statement.= " AND A.JENIS_NASKAH_ID IN (".$reqJenisNaskahId.")";
		}

		if(!empty($reqTahun))
		{
			$statement.= " AND A.TAHUN = ".$reqTahun;
		}

		$allRecord = $surat_masuk->getCountByParamsKeluarDisposisi(array(), $this->ID, $statement);
		// echo $allRecord;exit;
		if ($reqPencarian == "")
			$allRecordFilter = $allRecord;
		else
			$allRecordFilter =  $surat_masuk->getCountByParamsKeluarDisposisi(array(), $this->ID, $statement.$searchJson);

		$sOrder = " ORDER BY B.TANGGAL_DISPOSISI DESC";
		$surat_masuk->selectByParamsKeluarDisposisi(array(), $dsplyRange, $dsplyStart, $this->ID, $statement.$searchJson, $sOrder);
		// echo $this->SATUAN_KERJA_ID_ASAL;exit;
		// echo $surat_masuk->query;exit;
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

		while ($surat_masuk->nextRow()) {
			$infojenisnaskahid= $surat_masuk->getField("JENIS_NASKAH_ID");
			$row = array();

			for ($i = 0; $i < count($aColumns); $i++) {
				if ($aColumns[$i] == "KETERANGAN")
					$row[] = truncate($surat_masuk->getField($aColumns[$i]), 2);
				elseif($aColumns[$i] == "NOMOR")
				{
					if($infojenisnaskahid == "1")
						$row[] = $surat_masuk->getField("NOMOR_SURAT_INFO");
					else
						$row[] = $surat_masuk->getField($aColumns[$i]);
				}
				elseif($aColumns[$i] == "INFO_DARI")
					$row[] = $surat_masuk->getField("USER_ATASAN")."<br/>".$surat_masuk->getField("USER_ATASAN_JABATAN");
				elseif ($aColumns[$i] == "TANGGAL_DISPOSISI")
					$row[] = getFormattedExtDateTimeCheck($surat_masuk->getField($aColumns[$i]));
				
				elseif ($aColumns[$i] == "TERBACA" || $aColumns[$i] == "TERDISPOSISI" || $aColumns[$i] == "TERBALAS") {
					if ((int)$surat_masuk->getField($aColumns[$i]) > 0)
						$row[] = "<i class=\"fa fa-check fa-md\" aria-hidden=\"true\"></i>";
					else
						$row[] = "<i class=\"fa fa-close fa-md\" aria-hidden=\"true\"></i>";
				} else
					$row[] = $surat_masuk->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		echo json_encode($output);
	}

	function jsonsuratkeluartanggapan()
	{
		$this->load->model("SuratMasuk");
		$surat_masuk = new SuratMasuk();

		$reqJenisNaskahId= $this->input->get("reqJenisNaskahId");
		$reqTahun= $this->input->get("reqTahun");

		$aColumns = array("NOMOR", "INFO_DARI", "PERIHAL", "TANGGAL_DISPOSISI", "DETIL_INFO_DARI_DIPOSISI", "DISPOSISI_ID", "SURAT_MASUK_ID");
		$aColumnsAlias = $aColumns;

		/*
		 * Ordering
		 */
		if (isset($_GET['iSortCol_0'])) {
			$sOrder = " ORDER BY ";

			//Go over all sorting cols
			for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
				//If need to sort by current col
				if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
					//Add to the order by clause
					$sOrder .= $aColumnsAlias[intval($_GET['iSortCol_' . $i])];

					//Determine if it is sorted asc or desc
					if (strcasecmp(($_GET['sSortDir_' . $i]), "asc") == 0) {
						$sOrder .= " asc, ";
					} else {
						$sOrder .= " desc, ";
					}
				}
			}

			//Remove the last space / comma
			$sOrder = substr_replace($sOrder, "", -2);

			//Check if there is an order by clause
			if (trim($sOrder) == "ORDER BY A.SURAT_MASUK_ID asc") {
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
		if (isset($_GET['sSearch'])) {
			$sWhereGenearal = $_GET['sSearch'];
		} else {
			$sWhereGenearal = '';
		}

		if ($_GET['sSearch'] != "") {
			//Set a default where clause in order for the where clause not to fail
			//in cases where there are no searchable cols at all.
			$sWhere = " AND (";
			for ($i = 0; $i < count($aColumnsAlias) + 1; $i++) {
				//If current col has a search param
				if ($_GET['bSearchable_' . $i] == "true") {
					//Add the search to the where clause
					$sWhere .= $aColumnsAlias[$i] . " LIKE '%" . $_GET['sSearch'] . "%' OR ";
					$nWhereGenearalCount += 1;
				}
			}
			$sWhere = substr_replace($sWhere, "", -3);
			$sWhere .= ')';
		}

		/* Individual column filtering */
		$sWhereSpecificArray = array();
		$sWhereSpecificArrayCount = 0;
		for ($i = 0; $i < count($aColumnsAlias); $i++) {
			if ($_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {
				//If there was no where clause
				if ($sWhere == "") {
					$sWhere = "AND ";
				} else {
					$sWhere .= " AND ";
				}

				//Add the clause of the specific col to the where clause
				$sWhere .= $aColumnsAlias[$i] . " LIKE '%' || :whereSpecificParam" . $sWhereSpecificArrayCount . " || '%' ";

				//Inc sWhereSpecificArrayCount. It is needed for the bind var.
				//We could just do count($sWhereSpecificArray) - but that would be less efficient.
				$sWhereSpecificArrayCount++;

				//Add current search param to the array for later use (binding).
				$sWhereSpecificArray[] =  $_GET['sSearch_' . $i];
			}
		}

		//If there is still no where clause - set a general - always true where clause
		if ($sWhere == "") {
			$sWhere = " AND 1=1";
		}

		//Bind variables.
		if (isset($_GET['iDisplayStart'])) {
			$dsplyStart = $_GET['iDisplayStart'];
		} else {
			$dsplyStart = 0;
		}
		if (isset($_GET['iDisplayLength']) && $_GET['iDisplayLength'] != '-1') {
			$dsplyRange = $_GET['iDisplayLength'];
			if ($dsplyRange > (2147483645 - intval($dsplyStart))) {
				$dsplyRange = 2147483645;
			} else {
				$dsplyRange = intval($dsplyRange);
			}
		} else {
			$dsplyRange = 2147483645;
		}

		$reqPencarian= $this->input->get("reqPencarian");
		$searchJson= " 
		AND 
		(
			UPPER(A.NO_AGENDA) LIKE '%".strtoupper($reqPencarian)."%' OR 
			UPPER(A.NOMOR) LIKE '%".strtoupper($reqPencarian)."%' OR
			UPPER(A.PERIHAL) LIKE '%".strtoupper($reqPencarian)."%' OR 
			UPPER(A.INSTANSI_ASAL) LIKE '%".strtoupper($reqPencarian)."%' OR 
			UPPER(A.DARI_INFO) LIKE '%".strtoupper($reqPencarian)."%' OR 
			UPPER(A.USER_ATASAN) LIKE '%".strtoupper($reqPencarian)."%' OR 
			UPPER(USER_ATASAN_JABATAN) LIKE '%".strtoupper($reqPencarian)."%' OR 
			UPPER(B.NAMA_USER) LIKE '%".strtoupper($reqPencarian)."%' OR 
			UPPER(B.NAMA_SATKER) LIKE '%".strtoupper($reqPencarian)."%'
		)";

		$statement= "
		AND 
		(
			A.STATUS_SURAT = 'POSTING' OR
			A.STATUS_SURAT = 'TU-NOMOR' OR
			(
				A.STATUS_SURAT = 'TU-IN' AND
				EXISTS(SELECT 1 FROM SURAT_MASUK_ARSIP X WHERE X.SURAT_MASUK_ID = A.SURAT_MASUK_ID AND X.CABANG_ID = '".$this->CABANG_ID."')
			)
		)";

		if(!empty($reqJenisNaskahId))
		{
			$statement.= " AND A.JENIS_NASKAH_ID IN (".$reqJenisNaskahId.")";
		}

		if(!empty($reqTahun))
		{
			$statement.= " AND A.TAHUN = ".$reqTahun;
		}

		$allRecord = $surat_masuk->getCountByParamsTanggapanKeluarDisposisi(array(), $this->ID, $statement);
		// echo $allRecord;exit;
		if ($reqPencarian == "")
			$allRecordFilter = $allRecord;
		else
			$allRecordFilter =  $surat_masuk->getCountByParamsTanggapanKeluarDisposisi(array(), $this->ID, $statement.$searchJson);

		$sOrder = " ORDER BY B.TANGGAL_DISPOSISI DESC";
		$surat_masuk->selectByParamsTanggapanKeluarDisposisi(array(), $dsplyRange, $dsplyStart, $this->ID, $statement.$searchJson, $sOrder);
		// echo $surat_masuk->query;exit;
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

		while ($surat_masuk->nextRow()) {
			$row = array();

			for ($i = 0; $i < count($aColumns); $i++) {
				if ($aColumns[$i] == "KETERANGAN")
					$row[] = truncate($surat_masuk->getField($aColumns[$i]), 2);
				elseif($aColumns[$i] == "INFO_DARI")
					$row[] = $surat_masuk->getField("USER_ATASAN")."<br/>".$surat_masuk->getField("USER_ATASAN_JABATAN");
				elseif ($aColumns[$i] == "TANGGAL_DISPOSISI")
					$row[] = getFormattedExtDateTimeCheck($surat_masuk->getField($aColumns[$i]));
				elseif($aColumns[$i] == "DETIL_INFO_DARI_DIPOSISI")
					$row[] = $surat_masuk->getField("NAMA_USER")."<br/>".$surat_masuk->getField("NAMA_SATKER");
				elseif ($aColumns[$i] == "TERBACA" || $aColumns[$i] == "TERDISPOSISI" || $aColumns[$i] == "TERBALAS") {
					if ((int)$surat_masuk->getField($aColumns[$i]) > 0)
						$row[] = "<i class=\"fa fa-check fa-md\" aria-hidden=\"true\"></i>";
					else
						$row[] = "<i class=\"fa fa-close fa-md\" aria-hidden=\"true\"></i>";
				} else
					$row[] = $surat_masuk->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		echo json_encode($output);
	}

	function jsonreference()
	{
		$this->load->model("SuratMasuk");
		$surat_masuk = new SuratMasuk();

		$reqStatusSurat= $this->input->get("reqStatusSurat");
		$reqTahun= $this->input->get("reqTahun");
		$reqCheckId= httpFilterGet("reqCheckId");
		$arrayCheckId= explode(',', $reqCheckId);

		$aColumns = array("CHECK", "NOMOR", "PERIHAL", "TANGGAL_ENTRI", "SURAT_MASUK_ID");
		$aColumnsAlias = $aColumns;

		/*
		 * Ordering
		 */
		if (isset($_GET['iSortCol_0'])) {
			$sOrder = " ORDER BY ";

			//Go over all sorting cols
			for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
				//If need to sort by current col
				if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
					//Add to the order by clause
					$sOrder .= $aColumnsAlias[intval($_GET['iSortCol_' . $i])];

					//Determine if it is sorted asc or desc
					if (strcasecmp(($_GET['sSortDir_' . $i]), "asc") == 0) {
						$sOrder .= " asc, ";
					} else {
						$sOrder .= " desc, ";
					}
				}
			}

			//Remove the last space / comma
			$sOrder = substr_replace($sOrder, "", -2);

			//Check if there is an order by clause
			if (trim($sOrder) == "ORDER BY A.SURAT_MASUK_ID asc") {
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
		if (isset($_GET['sSearch'])) {
			$sWhereGenearal = $_GET['sSearch'];
		} else {
			$sWhereGenearal = '';
		}

		if ($_GET['sSearch'] != "") {
			//Set a default where clause in order for the where clause not to fail
			//in cases where there are no searchable cols at all.
			$sWhere = " AND (";
			for ($i = 0; $i < count($aColumnsAlias) + 1; $i++) {
				//If current col has a search param
				if ($_GET['bSearchable_' . $i] == "true") {
					//Add the search to the where clause
					$sWhere .= $aColumnsAlias[$i] . " LIKE '%" . $_GET['sSearch'] . "%' OR ";
					$nWhereGenearalCount += 1;
				}
			}
			$sWhere = substr_replace($sWhere, "", -3);
			$sWhere .= ')';
		}

		/* Individual column filtering */
		$sWhereSpecificArray = array();
		$sWhereSpecificArrayCount = 0;
		for ($i = 0; $i < count($aColumnsAlias); $i++) {
			if ($_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {
				//If there was no where clause
				if ($sWhere == "") {
					$sWhere = "AND ";
				} else {
					$sWhere .= " AND ";
				}

				//Add the clause of the specific col to the where clause
				$sWhere .= $aColumnsAlias[$i] . " LIKE '%' || :whereSpecificParam" . $sWhereSpecificArrayCount . " || '%' ";

				//Inc sWhereSpecificArrayCount. It is needed for the bind var.
				//We could just do count($sWhereSpecificArray) - but that would be less efficient.
				$sWhereSpecificArrayCount++;

				//Add current search param to the array for later use (binding).
				$sWhereSpecificArray[] =  $_GET['sSearch_' . $i];
			}
		}

		//If there is still no where clause - set a general - always true where clause
		if ($sWhere == "") {
			$sWhere = " AND 1=1";
		}

		//Bind variables.
		if (isset($_GET['iDisplayStart'])) {
			$dsplyStart = $_GET['iDisplayStart'];
		} else {
			$dsplyStart = 0;
		}
		if (isset($_GET['iDisplayLength']) && $_GET['iDisplayLength'] != '-1') {
			$dsplyRange = $_GET['iDisplayLength'];
			if ($dsplyRange > (2147483645 - intval($dsplyStart))) {
				$dsplyRange = 2147483645;
			} else {
				$dsplyRange = intval($dsplyRange);
			}
		} else {
			$dsplyRange = 2147483645;
		}

		if($reqStatusSurat == "KOTAK_MASUK")
		{
			$statement= " 
			AND B.DISPOSISI_PARENT_ID = 0
			AND 
			(
				A.STATUS_SURAT = 'POSTING' OR
				A.STATUS_SURAT = 'TU-NOMOR' OR
				(
					A.STATUS_SURAT = 'TU-IN' AND
					EXISTS(SELECT 1 FROM SURAT_MASUK_ARSIP X WHERE X.SURAT_MASUK_ID = A.SURAT_MASUK_ID AND X.CABANG_ID = '".$this->CABANG_ID."')
				)
			)";

			if($this->KD_LEVEL_PEJABAT == "")
			{
				// $statement.= " AND (B.USER_ID = '".$this->ID."' OR B.USER_ID = '".$this->ID_ATASAN."' ) ";
				$statement.= " AND 
				(
					(
						( B.USER_ID = '".$this->ID."' OR B.USER_ID = '".$this->ID_ATASAN."')
						AND B.DISPOSISI_KELOMPOK_ID = 0
					)
					OR
					EXISTS
					(
						SELECT 1
						FROM
						(
							SELECT A.DISPOSISI_KELOMPOK_ID, A.SURAT_MASUK_ID
							FROM disposisi_kelompok A 
							INNER JOIN satuan_kerja_kelompok_group B ON A.SATUAN_KERJA_KELOMPOK_ID = B.SATUAN_KERJA_KELOMPOK_ID
							WHERE B.KELOMPOK_JABATAN = '".$this->KELOMPOK_JABATAN."'
						) X WHERE X.SURAT_MASUK_ID = B.SURAT_MASUK_ID
					";

					// if($this->KELOMPOK_JABATAN == "KARYAWAN"){}
					// else
					// {
						$statement.= " AND B.SATUAN_KERJA_ID_TUJUAN = '".$this->CABANG_ID."' ";
					// }
				$statement.= "
					)
				) ";
			}
			else
			{
				$statement.= " AND (B.SATUAN_KERJA_ID_TUJUAN = '".$this->SATUAN_KERJA_ID_ASAL."' OR B.USER_ID = '".$this->ID."' OR B.USER_ID_OBSERVER = '".$this->ID."') ";
			}

			if(!empty($reqJenisNaskahId))
			{
				$statement.= " AND A.JENIS_NASKAH_ID IN (".$reqJenisNaskahId.")";
			}

			if(!empty($reqTahun))
			{
				$statement.= " AND A.TAHUN = ".$reqTahun;
			}

			$allRecord = $surat_masuk->getCountByParamsSuratMasuk(array(), $statement);
			// echo $allRecord;exit;
			if ($_GET['sSearch'] == "")
				$allRecordFilter = $allRecord;
			else
				$allRecordFilter =  $surat_masuk->getCountByParamsSuratMasuk(array(), $statement.$searchJson);

			$sOrder= "";
			$surat_masuk->selectByParamsSuratMasuk(array(), $dsplyRange, $dsplyStart, $statement.$searchJson, $sOrder);
		}
		else
		{
			$searchJson= " 
			AND 
			(
				UPPER(A.NO_AGENDA) LIKE '%".strtoupper($_GET['sSearch'])."%' OR 
				UPPER(A.NOMOR) LIKE '%".strtoupper($_GET['sSearch'])."%' OR
				UPPER(A.PERIHAL) LIKE '%".strtoupper($_GET['sSearch'])."%' OR 
				UPPER(A.INSTANSI_ASAL) LIKE '%".strtoupper($_GET['sSearch'])."%'
			)";

			$statement= "
			AND 
			(
				A.STATUS_SURAT = 'POSTING' OR
				A.STATUS_SURAT = 'TU-NOMOR' OR
				(
					A.STATUS_SURAT = 'TU-IN' AND
					EXISTS(SELECT 1 FROM SURAT_MASUK_ARSIP X WHERE X.SURAT_MASUK_ID = A.SURAT_MASUK_ID AND X.CABANG_ID = '".$this->CABANG_ID."')
				)
			)";

			if(!empty($reqJenisNaskahId))
			{
				$statement.= " AND A.JENIS_NASKAH_ID IN (".$reqJenisNaskahId.")";
			}

			if(!empty($reqTahun))
			{
				$statement.= " AND A.TAHUN = ".$reqTahun;
			}

			$allRecord = $surat_masuk->getCountByParamsDisposisi(array(), $this->ID, $statement);
			// echo $allRecord;exit;
			if ($_GET['sSearch'] == "")
				$allRecordFilter = $allRecord;
			else
				$allRecordFilter =  $surat_masuk->getCountByParamsDisposisi(array(), $this->ID, $statement.$searchJson);

			$sOrder= "";
			$surat_masuk->selectByParamsDisposisi(array(), $dsplyRange, $dsplyStart, $this->ID, $statement.$searchJson, $sOrder);
		}
		// echo $surat_masuk->query;exit;
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

		while ($surat_masuk->nextRow()) {
			$row = array();
			for ($i = 0; $i < count($aColumns); $i++) {
				if($aColumns[$i] == 'CHECK')
				{
					$checked= "";
					$tempCheckId= $surat_masuk->getField("SURAT_MASUK_ID");
					if(in_array($tempCheckId, $arrayCheckId))
					{
						$checked= "checked";
					}

					$row[] = "<input type='checkbox' $checked onclick='setKlikCheck()' class='editor-active' id='reqPilihCheck".$tempCheckId."' ".$checked." value='".$tempCheckId."'>";
				}
				elseif ($aColumns[$i] == "KETERANGAN")
					$row[] = truncate($surat_masuk->getField($aColumns[$i]), 2);
				elseif ($aColumns[$i] == "INFO_STATUS_TANGGAL" || $aColumns[$i] == "TANGGAL_ENTRI")
					$row[] = getFormattedExtDateTimeCheck($surat_masuk->getField($aColumns[$i]));
				elseif ($aColumns[$i] == "TERBACA" || $aColumns[$i] == "TERDISPOSISI" || $aColumns[$i] == "TERBALAS") {
					if ((int)$surat_masuk->getField($aColumns[$i]) > 0)
						$row[] = "<i class=\"fa fa-check fa-md\" aria-hidden=\"true\"></i>";
					else
						$row[] = "<i class=\"fa fa-close fa-md\" aria-hidden=\"true\"></i>";
				} else
					$row[] = $surat_masuk->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		echo json_encode($output);
	}

	function jsonnewdraft()
	{
		$this->load->model("SuratMasuk");
		$surat_masuk = new SuratMasuk();

		$reqMode= $this->input->get("reqMode");

		$aColumns = array("INFO_NOMOR_SURAT", "INFO_DARI", "PERIHAL", "TANGGAL_ENTRI", "JENIS_NASKAH_LINK", "SURAT_MASUK_ID");
		$aColumnsAlias = $aColumns;

		/*
		 * Ordering
		 */
		if (isset($_GET['iSortCol_0'])) {
			$sOrder = " ORDER BY ";

			//Go over all sorting cols
			for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
				//If need to sort by current col
				if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
					//Add to the order by clause
					$sOrder .= $aColumnsAlias[intval($_GET['iSortCol_' . $i])];

					//Determine if it is sorted asc or desc
					if (strcasecmp(($_GET['sSortDir_' . $i]), "asc") == 0) {
						$sOrder .= " asc, ";
					} else {
						$sOrder .= " desc, ";
					}
				}
			}

			//Remove the last space / comma
			$sOrder = substr_replace($sOrder, "", -2);

			//Check if there is an order by clause
			if (trim($sOrder) == "ORDER BY A.SURAT_MASUK_ID asc") {
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
		if (isset($_GET['sSearch'])) {
			$sWhereGenearal = $_GET['sSearch'];
		} else {
			$sWhereGenearal = '';
		}

		if ($_GET['sSearch'] != "") {
			//Set a default where clause in order for the where clause not to fail
			//in cases where there are no searchable cols at all.
			$sWhere = " AND (";
			for ($i = 0; $i < count($aColumnsAlias) + 1; $i++) {
				//If current col has a search param
				if ($_GET['bSearchable_' . $i] == "true") {
					//Add the search to the where clause
					$sWhere .= $aColumnsAlias[$i] . " LIKE '%" . $_GET['sSearch'] . "%' OR ";
					$nWhereGenearalCount += 1;
				}
			}
			$sWhere = substr_replace($sWhere, "", -3);
			$sWhere .= ')';
		}

		/* Individual column filtering */
		$sWhereSpecificArray = array();
		$sWhereSpecificArrayCount = 0;
		for ($i = 0; $i < count($aColumnsAlias); $i++) {
			if ($_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {
				//If there was no where clause
				if ($sWhere == "") {
					$sWhere = "AND ";
				} else {
					$sWhere .= " AND ";
				}

				//Add the clause of the specific col to the where clause
				$sWhere .= $aColumnsAlias[$i] . " LIKE '%' || :whereSpecificParam" . $sWhereSpecificArrayCount . " || '%' ";

				//Inc sWhereSpecificArrayCount. It is needed for the bind var.
				//We could just do count($sWhereSpecificArray) - but that would be less efficient.
				$sWhereSpecificArrayCount++;

				//Add current search param to the array for later use (binding).
				$sWhereSpecificArray[] =  $_GET['sSearch_' . $i];
			}
		}

		//If there is still no where clause - set a general - always true where clause
		if ($sWhere == "") {
			$sWhere = " AND 1=1";
		}

		//Bind variables.
		if (isset($_GET['iDisplayStart'])) {
			$dsplyStart = $_GET['iDisplayStart'];
		} else {
			$dsplyStart = 0;
		}
		if (isset($_GET['iDisplayLength']) && $_GET['iDisplayLength'] != '-1') {
			$dsplyRange = $_GET['iDisplayLength'];
			if ($dsplyRange > (2147483645 - intval($dsplyStart))) {
				$dsplyRange = 2147483645;
			} else {
				$dsplyRange = intval($dsplyRange);
			}
		} else {
			$dsplyRange = 2147483645;
		}

		$reqPencarian= $this->input->get("reqPencarian");
		$searchJson= " 
		AND 
		(
			UPPER(A.NO_AGENDA) LIKE '%".strtoupper($reqPencarian)."%' OR 
			UPPER(A.NOMOR) LIKE '%".strtoupper($reqPencarian)."%' OR
			UPPER(A.PERIHAL) LIKE '%".strtoupper($reqPencarian)."%' OR 
			UPPER(A.INSTANSI_ASAL) LIKE '%".strtoupper($reqPencarian)."%' OR 
			UPPER(A.USER_ATASAN) LIKE '%".strtoupper($reqPencarian)."%' OR 
			UPPER(A.USER_ATASAN_JABATAN) LIKE '%".strtoupper($reqPencarian)."%' OR 
			UPPER(SM.NOMOR_SURAT_INFO) LIKE '%".strtoupper($reqPencarian)."%'
		)";

		$statement= " 
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
        AND A.STATUS_SURAT IN ('DRAFT', 'REVISI')
        ";

        if($reqMode == "manual")
        {
        	$statement.= " AND A.JENIS_NASKAH_ID IN (1)";
        }
        else
        {
        	$statement.= " AND A.JENIS_NASKAH_ID NOT IN (1)";
        }


		$allRecord = $surat_masuk->getCountByParamsMonitoringDraftInfo(array(), $statement);
		// echo $allRecord;exit;
		if ($reqPencarian == "")
			$allRecordFilter = $allRecord;
		else
			$allRecordFilter =  $surat_masuk->getCountByParamsMonitoringDraftInfo(array(), $statement.$searchJson);

		// $sOrder= "";
		$sOrder = " ORDER BY A.TANGGAL_ENTRI DESC";
		$surat_masuk->selectByParamsMonitoringDraftInfo(array(), $dsplyRange, $dsplyStart, $statement.$searchJson, $sOrder);
		// echo $surat_masuk->query;exit;
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

		while ($surat_masuk->nextRow()) {
			$infojenisnaskahid= $surat_masuk->getField("JENIS_NASKAH_ID");
			$row = array();

			for ($i = 0; $i < count($aColumns); $i++) {
				if($aColumns[$i] == "INFO_NOMOR_SURAT")
				{
					if($infojenisnaskahid == "1")
						$row[] = $surat_masuk->getField("NOMOR_SURAT_INFO");
					else
						$row[] = $surat_masuk->getField($aColumns[$i]);
				}
				elseif ($aColumns[$i] == "JENIS_NASKAH_LINK")
					$row[] = getJenisNaskah($infojenisnaskahid);
				elseif($aColumns[$i] == "INFO_DARI")
				{
					if($infojenisnaskahid == "1")
						$row[] = $surat_masuk->getField("DARI_INFO");
					else
						$row[] = $surat_masuk->getField("USER_ATASAN")."<br/>".$surat_masuk->getField("USER_ATASAN_JABATAN");
				}
				else
					$row[] = $surat_masuk->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		echo json_encode($output);
	}

	function inforeference()
	{
		$this->load->model("SuratMasuk");
		$set= new SuratMasuk();

		$reqId= $this->input->get("reqId");
		// echo $reqId;exit;

		$arr_json= array();
		$i=0;
		$set->selectByParams(array(), -1, -1, " AND A.SURAT_MASUK_ID IN (".$reqId.")");
		while($set->nextRow())
		{
			$arr_json[$i]['SURAT_MASUK_ID']= $set->getField("SURAT_MASUK_ID");
			$arr_json[$i]['NOMOR']= $set->getField("NOMOR");
			$i++;
		}

		echo json_encode($arr_json);
	}

	function disposisiteruskan()
	{
		$this->load->model("SuratMasuk");

		$reqId= $this->input->post('reqId');
		$reqRowId= $this->input->post('reqRowId');

		$set= new SuratMasuk();
		$set->setField("SURAT_MASUK_ID", $reqId);
		$set->setField("DISPOSISI_ID", $reqRowId);
		$set->setField("LAST_CREATE_USER", $this->ID);
		$set->disposisiteruskan();

		echo "Surat berhasil di teruskan";
	}

}