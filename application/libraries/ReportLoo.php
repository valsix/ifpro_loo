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
		$CI = &get_instance();

		$reqId= $arrparam["reqId"];
		$reqTemplate= $arrparam["reqTemplate"];
		$ttd= $arrparam["ttd"];
		$lihat= $arrparam["lihat"];
		// print_r($arrparam);exit;

		$this->reqId= $reqId;
		$this->reqTemplate= $reqTemplate;
		
		$lewatifile= "";
		$vfolder= "";
		if($reqTemplate == "loo_lampiran" || $reqTemplate == "loo")
		{
			$vid= $reqId;
			if(empty($vid)) $vid= -1;
			$CI->load->model("TrLoo");
			$set= new TrLoo();
			$set->selectByParams(array(), -1,-1, " AND A.TR_LOO_ID = ".$vid);
			$set->firstRow();
			$vstatusdata= $set->getField("STATUS_DATA");
			$vttdcode= $set->getField("TTD_KODE");
			// echo $vstatusdata;exit;

			$pesanQrCode = "ID: ".$set->getField("TTD_KODE")."\n";
			$pesanQrCode.= "ApprovedBy: ".$set->getField("USER_PENGIRIM_NAMA")."\n";
			$pesanQrCode.= "Nomor Surat: ".$set->getField("NOMOR_SURAT")."\n";
			// echo $pesanQrCode;exit;

			if($vstatusdata == "POSTING")
			{
				$lewatifile= "1";
			}

			$vfolder= "uploadsloo";
		}
		else if($reqTemplate == "loi")
		{
			$vid= $reqId;
			if(empty($vid)) $vid= -1;
			$CI->load->model("TrLoi");
			$set= new TrLoi();
			$set->selectByParams(array(), -1,-1, " AND A.TR_LOI_ID = ".$vid);
			$set->firstRow();
			$vstatusdata= $set->getField("STATUS_DATA");
			$vttdcode= $set->getField("TTD_KODE");
			// echo $vstatusdata;exit;

			$pesanQrCode = "ID: ".$set->getField("TTD_KODE")."\n";
			$pesanQrCode.= "ApprovedBy: ".$set->getField("USER_PENGIRIM_NAMA")."\n";
			$pesanQrCode.= "Nomor Surat: ".$set->getField("NOMOR_SURAT")."\n";
			// echo $pesanQrCode;exit;

			if($vstatusdata == "POSTING")
			{
				$lewatifile= "1";
			}

			$vfolder= "uploadsloi";
		}
		else if($reqTemplate == "psm")
		{
			$vfolder= "uploadspsm";
		}

		if(empty($vfolder))
		{
			exit;
		}

		$FILE_DIR_TEMPLATE= "uploadsloo/";
		$FILE_DIR= $vfolder."/".$this->reqId."/";

		if (!file_exists($FILE_DIR)) {
			makedirs($FILE_DIR, 0777, true);
		}
		chmod($FILE_DIR, 0777);

		$CI = &get_instance();
		$basereport= $CI->config->item('base_report');
		$urllink= $basereport."report/loadUrl/report/".$reqTemplate."_cetak_pdf/?reqId=".$this->reqId;
		if(!empty($ttd))
		{
			$urllink.= "&ttd=".$ttd;
		}

		if($lihat == "link")
		{
			echo $urllink;exit;
		}

		$vpngttd= $FILE_DIR.$vttdcode.".png";
		// kalau ttd 2 maka buat barcode
		if($ttd == 2)
		{
			$vgenerate= "_".$vttdcode;
			$lewatifile= "1";
			if(!file_exists($vpngttd))
			{
				$lewatifile= "";
				$errorCorrectionLevel = 'L';
				$matrixPointSize = 5;
				QRcode::png($pesanQrCode, $vpngttd, $errorCorrectionLevel, $matrixPointSize, 2);
			}
		}
		else
		{
			$vgenerate= "_".generateZero($reqId, 6);
		}

		if(!file_exists($vpngttd) && !empty($lewatifile))
		{
			$lewatifile= "";
		}

		$saveAs= $reqTemplate.$vgenerate.".pdf";

		$filelink= $FILE_DIR.$saveAs;
		if(file_exists($filelink) && !empty($lewatifile))
		{
			return $saveAs;exit;
		}
		// echo $lewatifile."<br/>";
		// echo $saveAs;exit;

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

		// kalau ada file hapus data
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

	function setpsmterbaca($arrparam)
	{
		$CI = &get_instance();
		$CI->load->model("TrPsm");
		$CI->load->model("TrPsmParaf");
		$arrgetsessionuser= $this->getsessionuser();
		// print_r($arrgetsessionuser);exit;
		$sessionloginid= $arrgetsessionuser["sessionloginid"];

		$reqId= $arrparam["reqId"];
		$reqStatusSurat= $arrparam["reqStatusSurat"];
		// print_r($arrparam);exit;

		if($reqStatusSurat == "VALIDASI")
		{
			$setdetil= new TrPsm();
			$setdetil->setField("FIELD", "TERBACA_VALIDASI");
			$setdetil->setField("FIELD_VALUE", "1");
			$setdetil->setField("LAST_UPDATE_USER", $sessionloginid);
			$setdetil->setField("TR_PSM_ID", $reqId);
			if($setdetil->updateByField())
			{
				$arrtriger= array("reqId"=>$reqId, "vjenis"=>"psm", "mode"=>"updateparaf");
				$this->trigerpaksa($arrtriger);
			}
		}
		else if($reqStatusSurat == "PARAF")
		{
			$setdetil= new TrPsmParaf();
			$setdetil->setField("LAST_UPDATE_USER", $sessionloginid);
			$setdetil->setField("USER_ID", $sessionloginid);
			$setdetil->setField("TR_PSM_ID", $reqId);
			if($setdetil->paraf())
			{
				$arrtriger= array("reqId"=>$reqId, "vjenis"=>"psm", "mode"=>"updateparaf");
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
		else if($vjenis == "psm")
		{
			$CI->load->model("TrPsm");
			$reqId= $arrparam["reqId"];
			$mode= $arrparam["mode"];

			$tgr= new TrPsm();
			$tgr->setField("TR_PSM_ID", $reqId);
			$tgr->setField("PAKSA_DB", $mode);
			$tgr->updatetriger();
		}
	}
	
}
