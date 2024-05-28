<?php

use PDFMerger\PDFMerger;

include_once("lib/phpqrcode/qrlib.php");
include_once("lib/vendor/autoload.php");
include_once("lib/MPDF60/mpdf.php");
include_once("lib/PDFMerger/PDFMerger.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");

class ReportPDF
{
	var $reqId;
	var $reqTemplate;
	var $reqJenisReport;
	function generate($reqId, $reqTemplate, $reqJenisReport = "")
	{
		$this->reqId = $reqId;
		$this->reqTemplate = $reqTemplate;
		$this->reqJenisReport = $reqJenisReport;

		$FILE_DIR_TEMPLATE = "uploads/";
		$FILE_DIR 		   = "uploads/" . $this->reqId . "/";

		if (!file_exists($FILE_DIR)) {
			// mkdir($FILE_DIR, 0777, true);
			makedirs($FILE_DIR, 0777, true);
		}

		$CI = &get_instance();
		$CI->load->library("suratmasukinfo");
		$suratmasukinfo = new suratmasukinfo();
		// var_dump($this->reqId);exit;
		$suratmasukinfo->getInfo($this->reqId);

		$mpdf = new mPDF('c', 'A4');
		$mpdf->AddPage(
			'P', // L - landscape, P - portrait
			'',
			'',
			'',
			'',
			25, // margin_left
			20, // margin right
			10, // margin top
			20, // margin bottom
			2, // margin header
			2
		);
		// $pdf->SetHTMLHeader('<div style="text-align: right; font-weight: bold;">My document</div>',true);
		//$mpdf=new mPDF('c','A4'); 
		//$mpdf=new mPDF('utf-8', array(297,420));
		// $mpdf->setFooter('{PAGENO}');
		$mpdf->mirrorMargins = 1;
		// $mpdf->SetHTMLHeader('');
		// $mpdf->SetHTMLFooter('
			
		// 	<div style="text-align: center;">
		// 	<img src="images/img-bangga.jpg" style="height: 35px;width: 40%";>
		// 	</div>
		// 	', 'O', true);

		$mpdf->SetDisplayMode('fullpage');

		$mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list

		// LOAD a stylesheet
		$stylesheet = file_get_contents('css/gaya-surat.css');
		$stylesheet .= file_get_contents('lib/tinyMCE/skins/lightgray/content.min.css');
		// $stylesheet .= file_get_contents('lib/tinyMCE/skins/lightgray/content.inline.min.css');
		// $stylesheet .= file_get_contents('lib/tinyMCE/skins/lightgray/skin.ie7.min.css');
		// $stylesheet .= file_get_contents('lib/tinyMCE/skins/lightgray/skin.min.css.css');

		$mpdf->WriteHTML($stylesheet, 1);	// The parameter 1 tells that this is css/style only and no body/html/text

		// echo "yyy";exit;

		if ($this->reqJenisReport == "")
			$template = $this->reqTemplate;
		else
			$template = $this->reqJenisReport;

		// echo $template;exit;
		$html .= file_get_contents($CI->config->item('base_report') . "report/loadUrl/report/" . $template . "/?reqJenisSurat=INTERNAL&reqId=" . $this->reqId);
		// echo $html;exit;

		$mpdf->WriteHTML($html, 2);

		$saveAs = (generateZero($suratmasukinfo->SURAT_MASUK_ID, 6) . generateZero($suratmasukinfo->SATUAN_KERJA_ID_ASAL, 6));

		//$mpdf->Output('aanwijzing.pdf','I');

		$mpdf->Output($FILE_DIR . $saveAs . ".pdf", "F");

		/*  JIKA TTD SUDAH MUNCUL MAKA FIX KAN  */
		if ($suratmasukinfo->NOMOR == "" || $suratmasukinfo->TTD_KODE == "" || $suratmasukinfo->JENIS_TTD == "BASAH") {
		} else {
			$CI = &get_instance();
			$CI->load->model("SuratMasuk");
			$surat_masuk = new SuratMasuk();

			$surat_masuk->setField("FIELD", "SURAT_PDF");
			$surat_masuk->setField("FIELD_VALUE", $saveAs . ".pdf");
			$surat_masuk->setField("LAST_UPDATE_USER", "SYSTEM");
			$surat_masuk->setField("SURAT_MASUK_ID", $this->reqId);
			$surat_masuk->updateByField();
		}


		// if ((int)$suratmasukinfo->JUMLAH_LAMPIRAN > 0) {

		// 	$pdf = new PDFMerger();
		// 	$pdf->addPDF($FILE_DIR . $saveAs . ".pdf", 'all');


		// 	$CI = &get_instance();
		// 	$CI->load->model("SuratMasuk");
		// 	$surat_masuk_attachment = new SuratMasuk();
		// 	$surat_masuk_attachment->selectByParamsAttachment(array("A.SURAT_MASUK_ID" => $this->reqId));
		// 	while ($surat_masuk_attachment->nextRow()) {
		// 		$pdf->addPDF($FILE_DIR . $surat_masuk_attachment->getField("ATTACHMENT"), 'all');
		// 	}

		// 	$pdf->merge('file', $FILE_DIR . $saveAs . ".pdf");
		// }
		return $saveAs . ".pdf";
	}
}
