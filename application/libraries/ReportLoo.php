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

class ReportLoo
{
	var $reqId;
	var $reqTemplate;
	var $reqJenisReport;

	function generate($reqId, $reqTemplate, $reqJenisReport = "", $lihat="")
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
		$basereport= $CI->config->item('base_report');
		$urllink= $basereport."report/loadUrl/report/".$reqTemplate."_cetak_pdf/?reqJenisSurat=INTERNAL&reqId=" .$this->reqId;

		if($lihat == "1")
		{
			echo $urllink;exit;
		}
		// echo $basereport."report/loadUrl/report/nota_dinas_bc"; exit;

		$arrContextOptions=array(
			"ssl"=>array(
				"verify_peer"=>false,
				"verify_peer_name"=>false,
			),
		);

		$html.= file_get_contents($urllink, false, stream_context_create($arrContextOptions));

		if ($reqId == 956)
		{
			// echo $urllink;exit;	
		}
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

		$saveAs= (generateZero($suratmasukinfo->SURAT_MASUK_ID, 6) . generateZero($suratmasukinfo->SATUAN_KERJA_ID_ASAL, 6)).".pdf";
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
		exit;
	}

	function generatexx($reqId, $reqTemplate, $reqJenisReport = "")
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
		$mpdf->curlAllowUnsafeSslRequests = true;		
		$mpdf->allow_charset_conversion = true;
		$mpdf->showImageErrors = true;
		
		$mpdf->AddPage(
			'P', // L - landscape, P - portrait
			'',
			'',
			'',
			'',
			25, // margin_left
			20, // margin right
			20, // margin top
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
		$mpdf->use_kwt = true;
		$mpdf->shrink_tables_to_fit = 1;
		$mpdf->keep_table_proportions = true;
		// $mpdf->shrink_tables_to_fit = 1;
		


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

		$arrContextOptions=array(
		      "ssl"=>array(
		            "verify_peer"=>false,
		            "verify_peer_name"=>false,
		        ),
		    );  

		// if($this->reqId == 79676)
		// {
		// 	// $mpdf->shrink_tables_to_fit=1;
		// 	echo $CI->config->item('base_report') . "report/loadUrl/report/" . $template . "/?reqJenisSurat=INTERNAL&reqId=" . $this->reqId;exit;
		// 	echo $html;exit;
		// 	// echo (generateZero($suratmasukinfo->SURAT_MASUK_ID, 6) . generateZero($suratmasukinfo->SATUAN_KERJA_ID_ASAL, 6));exit;
		// 	// echo $FILE_DIR.(generateZero($suratmasukinfo->SURAT_MASUK_ID, 6) . generateZero($suratmasukinfo->SATUAN_KERJA_ID_ASAL, 6));exit;
		// }

		// echo $template;exit;
		$infourl= $CI->config->item('base_report') . "report/loadUrl/report/" . $template . "/?reqJenisSurat=INTERNAL&reqId=" . $this->reqId;
		// echo $infourl;exit;
		$html .= file_get_contents($infourl, false, stream_context_create($arrContextOptions));
		$html= str_replace('dir="ltr"', '', $html);

		// if($this->reqId == 517)
		// {
		// 	echo $html;exit; 
		// }


		// echo $this->reqId;exit;
		// if($this->reqId == 79676)
		// {
		// 	// $mpdf->shrink_tables_to_fit=1;
		// 	echo $CI->config->item('base_report') . "report/loadUrl/report/" . $template . "/?reqJenisSurat=INTERNAL&reqId=" . $this->reqId;exit;
		// 	echo $html;exit;
		// 	// echo (generateZero($suratmasukinfo->SURAT_MASUK_ID, 6) . generateZero($suratmasukinfo->SATUAN_KERJA_ID_ASAL, 6));exit;
		// 	// echo $FILE_DIR.(generateZero($suratmasukinfo->SURAT_MASUK_ID, 6) . generateZero($suratmasukinfo->SATUAN_KERJA_ID_ASAL, 6));exit;
		// }

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
