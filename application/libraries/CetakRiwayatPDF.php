<?php
include_once("lib/phpqrcode/qrlib.php");
include_once("lib/vendor/autoload.php");
include_once("lib/MPDF60/mpdf.php");
include_once("lib/PDFMerger/PDFMerger.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// use PDFMerger\PDFMerger;
require_once('vendor/autoload.php');
use mikehaertl\wkhtmlto\Pdf;
class CetakRiwayatPDF
{
	var $reqId;
	var $reqRowId;
	var $reqMode;
	function generate($reqId, $reqRowId, $reqMode)
	{
		$this->reqId = $reqId;
		$this->reqRowId = $reqRowId;
		$this->reqMode = $reqMode;

		$FILE_DIR_TEMPLATE = "uploads/";
		$FILE_DIR 		   = "uploads/" . $this->reqId . "/";

		if (!file_exists($FILE_DIR)) {
			// mkdir($FILE_DIR, 0777, true);
			makedirs($FILE_DIR, 0777, true);
		}
		$CI = &get_instance();
		$CI->load->library("suratmasukinfo");
		$suratmasukinfo = new suratmasukinfo();
		$suratmasukinfo->getInfo($this->reqId);

		if ($this->reqJenisReport == "")
			$template = $this->reqTemplate;
		else
			$template = $this->reqJenisReport;

		// echo $reqJenisReport; exit;

		$basereport= $CI->config->item('base_report');

		$urllink=  $basereport."main/loadUrl/main/cetak_riwayat?reqMode=". $this->reqMode ."&reqId=". $this->reqId ."&reqRowId=". $this->reqRowId;

		$arrContextOptions=array(
			"ssl"=>array(
				"verify_peer"=>false,
				"verify_peer_name"=>false,
			),
		);

		$html.= file_get_contents($urllink, false, stream_context_create($arrContextOptions));
		
		// echo $urllink;exit;
		// echo $html;exit;

		$wkhtmltopdf = new PDF($html);
		$wkhtmltopdf->setOptions(
		    array(
		        "javascript-delay" => 1000
		        , "margin-left"=> 20
		        , "margin-right"=> 20
				, "page-width" => '248'
				, "page-height" => '350'
		    )
		);

		// $saveAs= (generateZero($suratmasukinfo->SURAT_MASUK_ID, 6) . generateZero($suratmasukinfo->SATUAN_KERJA_ID_ASAL, 6)).".pdf";
		$saveAs = "riwayat_".$this->reqId.".pdf";

		// echo $FILE_DIR.$saveAs;exit;
		unlink($FILE_DIR.$saveAs);
		// exit;
		$wkhtmltopdf->saveAs($FILE_DIR.$saveAs);

		if ($suratmasukinfo->NOMOR == "" || $suratmasukinfo->TTD_KODE == "" || $suratmasukinfo->JENIS_TTD == "BASAH") {
		} else {
			$CI = &get_instance();
			$CI->load->model("SuratMasuk");
			$surat_masuk = new SuratMasuk();

			$surat_masuk->setField("FIELD", "SURAT_PDF");
			$surat_masuk->setField("FIELD_VALUE", $saveAs);
			$surat_masuk->setField("LAST_UPDATE_USER", "SYSTEM");
			$surat_masuk->setField("SURAT_MASUK_ID", $this->reqId);
			$surat_masuk->updateByField();
		}

		return $saveAs;
	}
}
