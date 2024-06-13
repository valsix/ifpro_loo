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

	function generate($arrparam)
	{
		$reqId= $arrparam["reqId"];
		$reqTemplate= $arrparam["reqTemplate"];
		$lihat= $arrparam["lihat"];
		// print_r($arrparam);exit;

		$this->reqId= $reqId;
		$this->reqTemplate= $reqTemplate;
		
		$FILE_DIR_TEMPLATE= "uploadsloo/";
		$FILE_DIR= "uploadsloo/".$this->reqId."/";

		if (!file_exists($FILE_DIR)) {
			makedirs($FILE_DIR, 0777, true);
		}
		chmod($FILE_DIR, 0777);

		$CI = &get_instance();
		$basereport= $CI->config->item('base_report');
		$urllink= $basereport."report/loadUrl/report/".$reqTemplate."_cetak_pdf/?reqJenisSurat=INTERNAL&reqId=".$this->reqId;

		if($lihat == "link")
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
		if ($lihat == "data")
		{
			echo $html;exit;
		}

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

		$vgenerate= "_".generateZero($reqId, 6);
		$saveAs= $reqTemplate.$vgenerate.".pdf";
		$filelink= $FILE_DIR.$saveAs;
		// echo $filelink;exit;
		unlink($filelink);
		// exit;
		$wkhtmltopdf->saveAs($filelink);
		unlink($FILE_DIR.$vgenerate.".pdf");
		chmod($FILE_DIR, 0555);

		/*if ($suratmasukinfo->NOMOR == "" || $suratmasukinfo->TTD_KODE == "" || $suratmasukinfo->JENIS_TTD == "BASAH") {
		} else {
			$CI = &get_instance();
			$CI->load->model("SuratMasuk");
			$surat_masuk = new SuratMasuk();

			$surat_masuk->setField("FIELD", "SURAT_PDF");
			$surat_masuk->setField("FIELD_VALUE", $saveAs);
			$surat_masuk->setField("LAST_UPDATE_USER", "SYSTEM");
			$surat_masuk->setField("SURAT_MASUK_ID", $this->reqId);
			$surat_masuk->updateByField();
		}*/

		return $saveAs;
		exit;
	}

	function setterbaca($arrparam)
	{
		$CI = &get_instance();
		$CI->load->model("TrLoo");
		$CI->load->model("TrLooParaf");
		$arrgetsessionuser= $this->getsessionuser();
		// print_r($arrgetsessionuser);exit;
		$sessionloginid= $arrgetsessionuser["sessionloginid"];

		$reqId= $arrparam["reqId"];
		$reqStatusSurat= $arrparam["reqStatusSurat"];
		// print_r($arrparam);exit;

		if($reqStatusSurat == "VALIDASI")
		{
			$setdetil= new TrLoo();
			$setdetil->setField("FIELD", "TERBACA_VALIDASI");
			$setdetil->setField("FIELD_VALUE", "1");
			$setdetil->setField("LAST_UPDATE_USER", $sessionloginid);
			$setdetil->setField("TR_LOO_ID", $reqId);
			if($setdetil->updateByField())
			{
				$arrtriger= array("reqId"=>$reqId, "vjenis"=>"loo", "mode"=>"updateparaf");
				$this->trigerpaksa($arrtriger);
			}
		}
		else if($reqStatusSurat == "PARAF")
		{
			$setdetil= new TrLooParaf();
			$setdetil->setField("LAST_UPDATE_USER", $sessionloginid);
			$setdetil->setField("USER_ID", $sessionloginid);
			$setdetil->setField("TR_LOO_ID", $reqId);
			if($setdetil->paraf())
			{
				$arrtriger= array("reqId"=>$reqId, "vjenis"=>"loo", "mode"=>"updateparaf");
				$this->trigerpaksa($arrtriger);
			}
		}
	}

	function setloiterbaca($arrparam)
	{
		$CI = &get_instance();
		$CI->load->model("TrLoi");
		$CI->load->model("TrLoiParaf");
		$arrgetsessionuser= $this->getsessionuser();
		// print_r($arrgetsessionuser);exit;
		$sessionloginid= $arrgetsessionuser["sessionloginid"];

		$reqId= $arrparam["reqId"];
		$reqStatusSurat= $arrparam["reqStatusSurat"];
		// print_r($arrparam);exit;

		if($reqStatusSurat == "VALIDASI")
		{
			$setdetil= new TrLoi();
			$setdetil->setField("FIELD", "TERBACA_VALIDASI");
			$setdetil->setField("FIELD_VALUE", "1");
			$setdetil->setField("LAST_UPDATE_USER", $sessionloginid);
			$setdetil->setField("TR_LOI_ID", $reqId);
			if($setdetil->updateByField())
			{
				$arrtriger= array("reqId"=>$reqId, "vjenis"=>"loi", "mode"=>"updateparaf");
				$this->trigerpaksa($arrtriger);
			}
		}
		else if($reqStatusSurat == "PARAF")
		{
			$setdetil= new TrLoiParaf();
			$setdetil->setField("LAST_UPDATE_USER", $sessionloginid);
			$setdetil->setField("USER_ID", $sessionloginid);
			$setdetil->setField("TR_LOI_ID", $reqId);
			if($setdetil->paraf())
			{
				$arrtriger= array("reqId"=>$reqId, "vjenis"=>"loi", "mode"=>"updateparaf");
				$this->trigerpaksa($arrtriger);
			}
		}
	}

	function getsessionuser()
	{
		$CI = &get_instance();

		$sessionloginid= $CI->kauth->getInstance()->getIdentity()->ID;

		$arrreturn= [];
		$arrreturn["sessionloginid"]= $sessionloginid;
		// print_r($arrreturn);exit;

		return $arrreturn;
	}

	function trigerpaksa($arrparam)
	{
		$CI = &get_instance();

		$vjenis= $arrparam["vjenis"];

		if($vjenis == "loo")
		{
			$CI->load->model("TrLoo");
			$reqId= $arrparam["reqId"];
			$mode= $arrparam["mode"];

			$tgr= new TrLoo();
			$tgr->setField("TR_LOO_ID", $reqId);
			$tgr->setField("PAKSA_DB", $mode);
			$tgr->updatetriger();
		}
		else if($vjenis == "loi")
		{
			$CI->load->model("TrLoi");
			$reqId= $arrparam["reqId"];
			$mode= $arrparam["mode"];

			$tgr= new TrLoi();
			$tgr->setField("TR_LOI_ID", $reqId);
			$tgr->setField("PAKSA_DB", $mode);
			$tgr->updatetriger();
		}
	}
	
}
